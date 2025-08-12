<?php

// << -------------------------------------------------------------------- >>
// >> ExoHelpDesk File
// >>
// >> ANNOUNCE . PHP File - For Announcements Related Stuff
// >> Started : February 16, 2004
// >> Edited  : February 18, 2004
// << -------------------------------------------------------------------- >>

// Check For Direct Access
if( !isset( $InDirectCall ) )
{
	die( 'NO DIRECT ACCESS' );
}

switch( $_GET['type'] )
{

	case 'add':
		if( $L_TYPE == 'admin' || ( $StaffAnnounce == 1 && $L_TYPE == 'staff' ))
		{
			if( SUBM == NULL )
			{
				if( !empty( $_GET['id'] ))
				{
					$ANQ = $db->query( "SELECT * FROM phpdesk_announce WHERE `id` = '". $_GET['id'] ."'" );
					$ANF = $db->fetch( $ANQ );
					
					$TITLE = $ANF['title'];
					$TEXT  = $ANF['text'];
					$OPTN  = '<option value="'. $ANF['access'] .'">'. $ANF['access'] .'</option>';
					$Never = '<option value="0">Never</option>';
					$OPTN2 = ( $ANF['expire'] == '0' ) ? $Never : '<option value="'. $ANF['expire'] .'">'. $ANF['expire'] .' Days</option>';
				}
				_parse( $tpl_dir . 'add.tpl' );
				$Top  = template( $class->read, NULL, $T_ST );
				$Read = template( $class->read, $T_ST . 'announce', '/#announce]' );
				
				echo $Top . $Read;
			}
			else
			{
				$P = $_POST;
				
				if( array_search( '', $P ))
				{
					echo $error['fields'];
				}			
				else
				{
					$Expire = ( $P['expire'] == 0 ) ? '0' : time() + ( 86400 * $P['expire'] );
					
					$SQL = "INSERT INTO phpdesk_announce SET `title` = '{$P['title']}', `text` = '{$P['text']}', 
								`access` = '{$P['view']}', `expire` = '{$Expire}', `added` = UNIX_TIMESTAMP()";
					
					if( !empty( $_GET['id'] ))
					{
						$SQL = "UPDATE phpdesk_announce SET `title` = '{$P['title']}', `text` = '{$P['text']}', 
								`access` = '{$P['view']}', `expire` = '{$Expire}' WHERE `id` = '{$_GET['id']}'";						
					}
					
					if( $db->query( $SQL ))
					{
						echo $success['announce'];
					}
				}
			}
		} // END AUTH CHECK
		break;
		
	case 'view':
		
		if( $L_TYPE == 'members' )
		{
			$Check = $db->query( "SELECT * FROM phpdesk_announce WHERE `id` = '{$_GET['id']}' 
									AND `access` = 'All'" );
		}
		else
		{
			$Check = $db->query( "SELECT * FROM phpdesk_announce WHERE `id` = '{$_GET['id']}'" );		
		}

		if( !$db->num( $Check ))
		{
			echo $error['no_auth_or_record'];
		}
		else
		{
			$F = $db->fetch( $Check );
			$TITLE  =  $F['title'];
			$TEXT   =  rpl( "\n", '<br />', strip( $F['text'] ));
			$ADDED  =  exo_date( 'F d, Y', $F['added'] );
			
			_parse( $tpl_dir . 'view.tpl' );
			echo getBlock( $class->read, 'ANNOUNCE' );
		}

		break;
	
	case 'delete':
		// IF NO ID FOUND
		if( !isset( $_GET['id'] ) )
		{
					
			echo $error['id_missing'];
				
		}
		elseif( ( $StaffAnnounce != 1 && $L_TYPE == 'staff' ) && $L_TYPE != 'admin' )
		{
			echo $error['no_auth_or_record'];
		}
		elseif( $_GET['confirm'] != 'YES' )
		{
			// CONFIRMATION
			echo 'Do you really want to delete the announcement: <b>'.$_GET['id'].'</b><br />';
			echo '[ <a href="'.$_SERVER['PHP_SELF'].'type=delete&confirm=YES&id='.$_GET['id'].'">Yes</a> ] ';
			echo '[ <a href="'. $_SERVER['HTTP_REFERER'] .'">No</a> ] <br />';
		}
		else
		{
			if( $db->query( "DELETE FROM phpdesk_announce WHERE `id` = '{$_GET['id']}'" ))
			{
				echo $success['del_announce'];
			}
		}
		break;
		
	default:
		
		_parse( $tpl_dir . 'list.tpl' );
		$Top  = template( $class->read, NULL, $T_ST );
		$Read = template( $class->read, $T_ST.'announce', '/#announce]' );
		$List = template( $Read, $T_ST, $T_ED );
		$Read = template( $Read, NULL, $T_ST );
		
		if( $L_TYPE == 'members' )
		{
			$ALQ  = $db->query( "SELECT title,expire,access,id FROM phpdesk_announce WHERE 
									`expire` >= UNIX_TIMESTAMP() OR `expire` = '0' ORDER 
									 by `added` DESC" );
		}
		else
		{
			$ALQ  = $db->query( "SELECT title,expire,access,id FROM phpdesk_announce ORDER by 
									`added` DESC" );		
		}

		
		echo $Top . $Read;
		
		while( $ALF = $db->fetch( $ALQ ))
		{
			if( $L_TYPE == 'members' && $ALF['access'] != 'All' )
			{
				CONTINUE;
			}		
			$X++;
			
			$BG = ( is_float( $X / 2 )) ? 'tdbg1' : 'tdbg2';
			
			$ACT  = NULL;
			if( $L_TYPE == 'admin' || ( $StaffAnnounce == 1 && $L_TYPE == 'staff' ))
			{			
				$ACT .= "[ <a href='{$_SERVER['PHP_SELF']}type=add&id={$ALF['id']}'>Edit</a> ] ";
				$ACT .= "[ <a href='{$_SERVER['PHP_SELF']}type=delete&id={$ALF['id']}'>Delete</a> ] ";
			}
			$ACT .= "[ <a href='{$_SERVER['PHP_SELF']}type=view&id={$ALF['id']}'>View</a> ] ";
			
			$EXPIRE = ( $ALF['expire'] == '0' ) ? 'Never' : exo_date( 'F d, Y', $ALF['expire'] );
			$OUT = rpl( '^TITLE^', $ALF['title'], rpl( '^EXPIRE^', $EXPIRE, $List ));
			$OUT = rpl( '^ACTIONS^', $ACT, rpl( '^td_bg^', $BG, $OUT ));
			
			echo $OUT;
		}
		
		echo '</table>';
		
		break;
		
} // END SWITCH

?>