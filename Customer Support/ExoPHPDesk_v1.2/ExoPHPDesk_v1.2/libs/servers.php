<?php

// << -------------------------------------------------------------------- >>
// >> ExoHelpDesk File
// >>
// >> SERVERS . PHP File - For Server Status/Add/Delete/Edit
// >> Started : December 08, 2003
// >> Edited  : February 15, 2004
// << -------------------------------------------------------------------- >>

// Check For Direct Access
if( !isset( $InDirectCall ) )
{
	die( 'NO DIRECT ACCESS' );
}

// If Action = Edit And No Id Available
if( empty( $_GET['id'] ) && TYPE == 'edit' )
{
	// Print Error Message
	echo $error['id_missing'];
	
}
// If Members Aren't Allowed To See Server Stats
elseif ( $L_TYPE == 'members' && $MEM_SERV == 0 )
{
	// Print Error Message
	echo $error['mem_serv'];
}
else
{

	// CHECK WHAT ACTION TO TAKE - add/edit/delete
	switch( TYPE )
	{
		case 'add': // IF ACTION IS TO ADD
		
			if( $L_TYPE != 'admin' )
			{
				echo $error['no_auth_or_record'];
			}
			// If Form Not Submitted
			elseif( SUBM == "" )
			{

				// If Editing
				if ( !empty ( $_GET['id'] ) )
				{
					// SQL Query
					$Q = $db->query ( "SELECT * FROM `phpdesk_servers` WHERE `id` = '" . $_GET['id'] ."'" );
						
					// If No Records
					if ( !$db->num ( $Q ) )
					{
						$_GET['id'] = NULL;
						echo $error['id_missing'];
					}
					else
					{
						$F = $db->fetch ( $Q );
						
						// NORMAL VARS
						$NAME = $F['name'];
						$IP   = $F['ip'];
						$NEWS = $F['news'];
						
						// CREATE PORTS VARIABLES
						$WEB_PORT    = $F['web_port'];
						$MYSQL_PORT  = $F['mysql_port'];
						$FTP_PORT    = $F['ftp_port'];
						$POP3_PORT   = $F['pop3_port'];
						$IMAP_PORT   = $F['imap_port'];
						$SMTP_PORT   = $F['smtp_port'];
						$TELNET_PORT = $F['telnet_port'];
						$SSH_PORT    = $F['ssh_port'];
						
					}
				}
						
				// PARSE THE TEMPLATE FILE
				_parse ( $tpl_dir.'add.tpl' );
				$READ = $class->read;
		
				// PREPARE TOP AND DOWN PART OF TEMPLATE
				$TOP  = template ( $READ, NULL, $T_ST );
				$DOWN = template ( $READ, $T_ST . 'server', '/#server]' );				
				echo $TOP . $DOWN;
				break;
			}
			else
			{
				// Strip Out Required Stuff From $_POST	
				$P = ex_strip ( $_POST );
				
				// If Name OR IP Field Empty 
				if ( !isset ( $_POST['name'] ) || !isset ( $_POST['ip'] ) )
				{
					echo $error['fields'];
				}
				else
				{
					// Prepare SQL Query
					$SQL = "INSERT INTO `phpdesk_servers` SET `name` = '" . $P['name'] ."', `ip` = '" . $P['ip']
						   ."', `news` = '" . $P['news'] . "', `web_port` = '". $P['web_port'] ."', `mysql_port` = "
						   ."'". $P['mysql_port'] ."', `ftp_port` = '" . $P['ftp_port'] ."', `pop3_port` = '"
						   . $P['pop3_port'] . "', `imap_port` = '". $P['imap_port'] ."', `smtp_port` = '". $P['smtp_port']
						   ."', `telnet_port` = '". $P['telnet_port'] ."', `ssh_port` = '". $P['ssh_port'] ."'";
					
					// If Editing
					if ( !empty( $_GET['id'] ) )
					{
						// Prepare SQL Query
						$SQL = "UPDATE `phpdesk_servers` SET `name` = '" . $P['name'] ."', `ip` = '" . $P['ip']
						   ."', `news` = '" . $P['news'] . "', `web_port` = '". $P['web_port'] ."', `mysql_port` = "
						   ."'". $P['mysql_port'] ."', `ftp_port` = '" . $P['ftp_port'] ."', `pop3_port` = '"
						   . $P['pop3_port'] . "', `imap_port` = '". $P['imap_port'] ."', `smtp_port` = '". $P['smtp_port']
						   ."', `telnet_port` = '". $P['telnet_port'] ."', `ssh_port` = '". $P['ssh_port'] ."' "
						   ."WHERE `id` = '" . $_GET['id'] . "'";

					}
						
					// If Execution Successfull
					if ( $db->query ( $SQL ) )
					{
						// Find Out Whether Editing Or Adding
						$ACTED = ( !empty ( $_GET['id'] ) ) ? "Edited" : "Added";

						// Print Success Message
						echo str_replace ( '^ACTED^', $ACTED, $success['server'] );
					}
				}
			}
				
			break;
		
		case 'list': // IF ACTION IS TO LIST SERVERS
				
			// PARSE TEMPLATE FILE
			_parse ( $tpl_dir . 'list.tpl' );
			$READ = $class->read;
				
			// PREPARE TEMPLATE VARS
			$TOP  = template ( $READ, NULL, $T_ST );
			$MIDD = template ( $READ, $T_ST . 'servers', '/#servers]' );
			$DOWN = template ( $MIDD, $T_ST, $T_ED );
			$MIDD = template ( $MIDD, NULL, $T_ST );
			
			// Print Out
			echo $TOP . $MIDD;
			
			// SQL QUERY TO FETCH SERVERS TABLE
			$Q = $db->query ( "SELECT * FROM `phpdesk_servers`" );
			
			while ( $F = $db->fetch ( $Q ) )
			{
				// Extend The Counter
				$X++;
					
				// BACKGROUND CLASS FOR TD
				$BG = ( is_float( $X / 2 ) ) ? 'tdbg1' : 'tdbg2';
					
				// PREPARE LINKS
				$DELETE = '[ <a href="'. $_SERVER['PHP_SELF'] .'type=delete&id='. $F['id'] .'">'
						 . $tpl['delete'] . '</a> ]';

				$EDIT   = '[ <a href="'. $_SERVER['PHP_SELF'] .'type=add&id='. $F['id'] .'">'
						 . $tpl['edit'] . '</a> ]';
				
				$DOWNT  = '[ <a href="'. $_SERVER['PHP_SELF'] .'type=downtimes&id='. $F['id'] .'">'
						 . 'Downtimes</a> ]';
							 
				$STATUS = '[ <a href="'. $_SERVER['PHP_SELF'] .'type=status&id='. $F['id'] .'">'
						 . $tpl['status'] . '</a> ]';

				// If User Isn't Admin Then Remove Edit And Delete Links
				if ( $L_TYPE != 'admin' )
				{
					$EDIT  =  $DELETE  =  $DOWNT  =  '';
				}							 
					
				// PREPARE VARS TO BE SENT
				$OUT = str_replace ( '^NAME^', $F['name'], str_replace ( '^IP^', $F['ip'], $DOWN ) );
				$OUT = str_replace ( '^edit^', $EDIT, str_replace ( '^td_bg^', $BG, $OUT ) );
				$OUT = str_replace ( '^status^', $STATUS, str_replace ( '^del^', $DELETE, $OUT ) );
				$OUT = rpl( '^down^', $DOWNT, $OUT );
				
				// Print Out
				echo $OUT;
			}
				
			break;
			
		case 'status':
				
			// SQL QUERY
			$Q = $db->query ( "SELECT * FROM `phpdesk_servers` WHERE `id` = '" . $_GET['id'] ."'" );
				
			// VALIDATION
			if ( !isset ( $_GET['id'] ) )
			{
				// Print Error Message
				echo $error['id_missing'];
				
			}
			elseif ( !$db->num ( $Q ) )
			{
				// Print Error Message
				echo $error['no_such_serv'];
			}
			else
			{
				// SET THE MAX EXECUTION TIME
				ini_set( "max_execution_time", "15" );
					
				// FETCH FROM TABLE
				$F = $db->fetch ( $Q );
					
				// ARRAY OF PORTS
				$PORTS = array (
							$tpl['web_service']   => $F['web_port'],
							$tpl['mysql_service'] => $F['mysql_port'],
							$tpl['ftp_service']   => $F['ftp_port'],
							$tpl['pop3_service']  => $F['pop3_port'],
							$tpl['smtp_service']  => $F['smtp_port'],
							$tpl['imap_service']  => $F['imap_port'],
							$tpl['telnet_service']=> $F['telnet_port'],
							$tpl['ssh_service']   => $F['ssh_port'],
							);
					
				// Parse LIST . TPL File
				_parse ( $tpl_dir . 'list.tpl' );
				
				$READ  =  $class->read;

				// Prepare Template
				$HEAD  =  template ( $READ, NULL, $T_ST );
				$MIDD  =  template ( $READ, $T_ST . 'ports', '/#ports]' );
				$LIST  =  template ( $MIDD, $T_ST, $T_ED );
				$MIDD  =  template ( $MIDD, NULL, $T_ST );
				$OUT   =  $X  =  NULL;
				
				// Print Out
				echo '<br />' . $HEAD . $MIDD;
				
				// Loop To Check All Ports Stats And Put Them Back
				while ( list ( $TEXT, $PORT ) = each ( $PORTS ) )
				{
					if ( $PORT != 0 )
					{
						// Extend The Counter
						$X++;
							
						// Prepare Background
						$BG = ( is_float ( $X / 2 ) ) ? 'tdbg1' : 'tdbg2';
							
						// Try To Open A Connection To The Server / Port
						$STATUS = @fsockopen ( $F['ip'], $PORT, $ERROR, $ERROR_STR, 4 );
						
						// If Connection Failed
						if ( !$STATUS )
						{
							// Prepare Out Vars
							$TMP  =  str_replace ( '^TEXT^', $TEXT, $LIST );
							$TMP  =  str_replace ( '^td_bg^', $BG, $TMP );
							$OUT .=  str_replace ( '^RUNNING^', $tpl['s_offline'], $TMP );
								
							$FURTHUR = 1;
						}
						else
						{
							$TMP  =  str_replace ( '^TEXT^', $TEXT, $LIST );
							$TMP  =  str_replace ( '^td_bg^', $BG, $TMP );								
							$OUT .=  str_replace ( '^RUNNING^', $tpl['s_online'], $TMP );
						}
						
						$PORT_EXISTS = 1;
					}
				}
					
				if ( $PORT_EXISTS == 0 )
				{
					echo $general['no_ports'];
				}
				else
				{
					echo $OUT . '</table>';
					if ( $FURTHUR != 0 )
					{
						echo $tpl['serv_down'];
					}
				}
				
			}
				
			break;

		case 'downtimes':
			// IF NO ID FOUND
			if( !isset( $_GET['id'] ) )
			{
					
				echo $error['id_missing'];
					
			}
			elseif ( $L_TYPE != 'admin' )
			{
				echo $error['no_auth_or_record'];
			}
			else
			{
				$SQ = $db->query( "SELECT down FROM phpdesk_servers WHERE id = '{$_GET[id]}'" );
				$SF = $db->fetch( $SQ );
				
				$List = explode( "\n", $SF['down'] );

				_parse( $tpl_dir . 'view.tpl' );
				$Read = getBlock( $class->read, 'SERV_DOWN' );
				$Top  = template( $Read, NULL, $T_ST );
				$list = template( $Read, $T_ST, $T_ED );
				
				echo $Top;
				
				foreach( $List AS $Down )
				{
					if( empty( $Down ))
					{
						CONTINUE;
					}
					
					$X++;
					
					// Explode
					$Down = explode( '|||', $Down );
					
					// Prepare Background
					$BG = ( is_float( $X / 2 )) ? 'tdbg1' : 'tdbg2';
					
					// Prepare output
					$OUT = rpl( '^td_bg^', $BG, rpl( '^TIME^', exo_date( 'F d, Y  H:i', $Down[0] ), $list ));
					$OUT = rpl( '^PORT^', $Down[1], $OUT );
					
					echo $OUT;
					
				}
				
				echo '</table>';
			
			}			
			
			break;
			
		case 'delete': // IF ACTION IS TO DELETE
				
			// IF NO ID FOUND
			if( !isset( $_GET['id'] ) )
			{
					
				echo $error['id_missing'];
					
			}
			elseif ( $L_TYPE != 'admin' )
			{
				echo $error['no_auth_or_record'];
			}
			elseif( $_GET['confirm'] != 'YES' )
			{
				// CONFIRMATION
				echo 'Do you really want to delete the server: <b>'. $_GET['id'] .'</b><br />';
				echo '[ <a href="'.$_SERVER['PHP_SELF'].'l_type='. $_GET['l_type'] .'&type=delete&confirm=YES&id='.$_GET['id'].'">Yes</a> ] ';
				echo '[ <a href="'. $_SERVER['HTTP_REFERER'] .'">No</a> ] <br />';
			}
			else
			{
				// DELETE QUERY
				if ( $db->query( "DELETE FROM `phpdesk_servers` WHERE id = '". $_GET['id'] ."'" ) )
				{
					// PRINT OUT SUCCESS
					echo str_replace ( '^ACTED^', 'Deleted', $success['server'] );
					
				}

			}
			break;

			
	} // END SWITCH

} // END VALIDATION
?>