<?php

// << -------------------------------------------------------------------- >>
// >> ExoHelpDesk File
// >>
// >> TROUBLES . PHP File - TroubleShooter For ExoPHPDesk
// >> Started : January 11, 2004
// >> Edited  : January 15, 2004
// << -------------------------------------------------------------------- >>

// Check For Direct Access
if( !isset( $InDirectCall ) )
{
	die( 'NO DIRECT ACCESS' );
}

// Check For Auth
if( $NO_AUTH == 0 && $L_TYPE == 'admin' )
{

	// Function To Get Childs Of A TroubleShoot
	function getChilds ( $ID , $COUNT = 0, $ADD = '', $OL_ID = '', $TPL = '' )
	{
		// Required Variables
		global $db, $T_W_WRAP;
				
		// SQL Query
		$QY  =  $db->query ( "SELECT * FROM `phpdesk_troubles` WHERE `parent` = '" . $ID . "'" );
				
		// LOOP To Get All Childs
		while ( $FY  =  $db->fetch ( $QY ) )
		{
			while ( $Z < $COUNT )
			{
				$Z++;
				$NBSP .= '&nbsp;&nbsp;';
			}
				
			if ( $ADD == 1 )
			{
				$SEL  = ( $OL_ID == $FY['id'] ) ? ' selected' : NULL;
				$OUT .= '<option value="'. $FY['id'] .'"'. $SEL .'>'. $NBSP .' - ' . $FY['title'] . '</option>';
			}
			else
			{
				$END  = ( strlen ( $FY['text'] ) > $T_W_WRAP ) ? '....' : NULL;
				$FY['text']  = substr ( $FY['text'], 0, $T_W_WRAP  ) . $END;
				
				$FY['title'] = ( $COUNT > 0 ) ? '<i>' . $FY['title'] . '</i>' : $FY['title'];
				
				$TMP  = rpl ( '^TITLE^', $NBSP .' - ' . $FY['title'], $TPL );
				$TMP  = rpl ( '^td_bg^', 'ticketbg', $TMP );
				$TMP  = rpl ( '^ID^', $FY['id'], $TMP );
				$OUT .= rpl ( '^TEXT^', $NBSP . $FY['text'], $TMP );
			}
					
			$Check  =  $db->query ( "SELECT id FROM `phpdesk_troubles` WHERE `parent` = '" . $FY['id'] ."'" );
				
			// If Childs Exists
			if ( $db->num ( $Check ) )
			{
				$OUT .= getChilds ( $FY['id'], $COUNT + 1, $ADD, $OL_ID, $TPL );
			}					
								
		}								
				
		return $OUT;
	}
	
	// Check For Type
	switch( $_GET['type'] )
	{
		// If No Type
		case 'list':
			
			$Lists  =  $db->query ( "SELECT * FROM `phpdesk_troubles` WHERE `isparent` = '1'" );
			
			_parse ( $tpl_dir . 'troubleshooter.tpl' );
			$Read  =  $class->read;
			$Read  =  template ( $Read, $T_ST . 'lists', '/#lists]' );
			$Top   =  template ( $Read, $T_ST . 'top', '/#top]' );
			$TPL   =  template ( $Read, $T_ST . 'list', '/#list]' );

			echo $Top;

			while ( $List  =  $db->fetch ( $Lists ) )
			{
				// Prepare Parents List
				$List['text'] = substr ( $List['text'], 0, $T_W_WRAP );
				$OUT  =  rpl ( '^TITLE^', '<b>' . $List['title'] . '</b>', $TPL );
				$OUT  =  rpl ( '^TEXT^', '<b>'. $List['text'] . '</b>', $OUT );
				$OUT  =  rpl ( '^td_bg^', 'ticketbg2', rpl ( '^ID^', $List['id'], $OUT ) );
				echo $OUT;

				// Print Childs Using getChilds Function				
				echo getChilds ( $List['id'], 0, 0, 0, $TPL );
				
			}
		
			break;
		
		case 'add':
		
			if ( SUBM == NULL )
			{
				// Check If Editing
				if ( !empty ( $_GET['id'] ) )
				{
					
					$Check  =  $db->query ( "SELECT text,title,parent,view FROM `phpdesk_troubles` WHERE `id` = '" . $_GET['id'] ."'" );
					
					if ( !$db->num ( $Check ) )
					{
						$_GET['id'] = NULL;
					}
					else
					{
						$FC    =  $db->fetch ( $Check );
						$TEXT  =  $FC['text'];
						$TITLE =  $FC['title'];
						$PAREN =  $FC['parent'];
						$VIEW  =  "<option value='{$FC['view']}'>" . $FC['view'] . '</option>';

					}
				}
				
				$QPAREN  =  $db->query ( "SELECT `id`,`title` FROM `phpdesk_troubles` WHERE `isparent` = '1'" );
				if ( $db->num ( $QPAREN ) )
				{
					$PARENTS  =  NULL;
					while ( $FPT = $db->fetch ( $QPAREN ) )
					{
						$SEL  =  ( $PAREN == $FPT['id'] ) ? ' selected' : NULL;
						$PARENTS .=  '<option value="'. $FPT['id'] .'"'. $SEL .'>'. $FPT['title'] .'( '. $FPT['id'] .' )'
									.'</option>';
						$PARENTS .=  getChilds ( $FPT['id'], 0, 1, $PAREN );
					}
									
				}
				
				_parse ( $tpl_dir . 'troubleshooter.tpl' );
				$Read  =  $class->read;
				$Read  =  template ( $Read, $T_ST . 'add', '/#add]' );
				$Read  =  rpl ( '^PARENTS^', $PARENTS, $Read );
				
				// OutPut
				echo $Read;
			
			}
			else
			{
				$P = $_POST;
				
				// Check If All Fields Filled
				if ( array_search ( '', $P ) )
				{
					echo $error['fields'];
				}
				else
				{
					
					// Check If Is Parent
					$ISPAR  =  ( strtolower ( $P['parent'] ) == 'none' ) ? '1' : '0';
					
					// Prepare SQL Query
					$SQL  =  "INSERT INTO `phpdesk_troubles` SET `title` = '" . $P['title'] ."', `text` = '" . $P['text']
							."', `parent` = '" . $P['parent'] ."', `isparent` = '". $ISPAR ."', `view` = '{$P['view']}'";
					
					// If ID Exists
					if ( !empty ( $_GET['id'] ) )
					{
						// Prepare SQL Query
						$SQL  =  "UPDATE `phpdesk_troubles` SET `title` = '" . $P['title'] ."', `text` = '" . $P['text']
								."', `parent` = '" . $P['parent'] ."', `isparent` = '". $ISPAR ."', `view` = '{$P['view']}' 
								 WHERE `id` = '" . $_GET['id'] ."'";
					}
					
					// Execute The Query
					if ( $db->query ( $SQL ) )
					{
						// Find Out The Action Taken
						$ACT  =  ( empty ( $_GET['id'] ) ) ? 'Added' : 'Edited';
						echo rpl ( '^ACTED^', $ACT, $success['troubleshooter'] );
					}
								
				}
						
			}
			
			break;
			
		case 'delete':
			
			// Check For Childs
			$CCheck  =  $db->query ( "SELECT * FROM `phpdesk_troubles` WHERE `parent` = '" . $_GET['id'] . "'" );
			$PCheck  =  $db->query ( "SELECT * FROM `phpdesk_troubles` WHERE `id` = '" . $_GET['id'] ."' AND `isparent` = '1'" );
			
			// Check For An ID
			if ( empty ( $_GET['id'] ) )
			{
				// OutPut Error Message
				echo $error['id_missing'];
			}
			elseif ( !$db->num ( $PCheck ) && $db->num ( $CCheck ) )
			{
				echo $error['child_exists'];
			}
			elseif ( $_GET['confirm'] != 'YES' )
			{
				if ( $db->num ( $PCheck ) )
				{
					$END = '<i>Warning: It will also delete all the childs of this parent.</i><br />';
				}
				
				// CONFIRMATION
				echo 'Do you really want to delete the TroubleShooter: <b>'. $_GET['id'] .'</b><br />'.$END;
				echo '[ <a href="'. $_SERVER['PHP_SELF'] .'&type=delete&confirm=YES&id='.$_GET['id'].'">Yes</a> ] ';
				echo '[ <a href="'. $_SERVER['HTTP_REFERER'] .'">No</a> ] <br />';				
							
			}
			else
			{
				// If Deleting A Parent TS
				if ( $db->num ( $PCheck ) )
				{
					// Prepare A Function
					function deleteChilds ( $ID )
					{
						global $db, $SQLs;
						
						$TEMP  = $db->query ( "SELECT id FROM `phpdesk_troubles` WHERE `parent` = '" . $ID ."'" );
						$SQLs .= "DELETE FROM `phpdesk_troubles` WHERE `id` = '" . $ID . "'" . "|||";

						while ( $F = $db->fetch ( $TEMP ) )
						{
							deleteChilds ( $F['id'] );
						}
						
						return $SQLs;
					}
					
					// Array Of Queries
					$SQLs = explode ( "|||", deleteChilds ( $_GET['id'] ) );
					
					// Execution Of Queries
					foreach ( $SQLs as $sql )
					{
						if ( !empty ( $sql ) )
						{
							@$db->query ( $sql );
						}
					}
				}
				
				// Delete Parent T.Shooter
				$SQL = "DELETE FROM `phpdesk_troubles` WHERE `id` = '" . $_GET['id'] . "'";
				
				if ( $db->query ( $SQL ) )
				{
					// Ouput Success Message
					echo $success['del_troubles'];
				}
								
			}
			
			
	} // END SWITCH

} // END AUTH CHECK

// CHECK FOR TROUBLESHOOTER AUTHS
if ( ( $MEM_TROUBLESHOOTER == 1 || $NO_AUTH == 0 ) && ( TYPE == 'view' || TYPE == NULL ) )
{
	if ( TYPE == NULL )
	{
		
		// Parse/Print Template	
		_parse ( $tpl_dir . 'troubleshooter.tpl' );
		$Read  =  template ( $class->read, $T_ST . 'viewall', '/#viewall]' );
		$Top   =  template ( $Read, $T_ST . 'top', '/#top]' );
		$List  =  template ( $Read, $T_ST . 'list', '/#list]' );
		
		$PARE  =  $db->query ( "SELECT title,id,text FROM `phpdesk_troubles` WHERE `isparent` = '1'" );

		echo $Top;

		if ( $db->num ( $PARE ) )
		{
			$X = 0;
			while ( $F = $db->fetch ( $PARE ) )
			{
				$X++;
				$F['title'] = '<a href="'. $_SERVER['PHP_SELF'] .'type=view&id='. $F['id'] .'">'. $F['title'] .'</a>';
				$OUT  =  rpl ( '^NO^', $X, $List );
				$OUT  =  rpl ( '^TITLE^', $F['title'], $OUT );
				$OUT  =  rpl ( '^TEXT^', substr ( $F['text'], 0, $T_W_WRAP ), $OUT );
				echo $OUT;
			}
		}
		
		echo '</table>';
	
	}
	elseif ( TYPE == 'view' )
	{

		$ID  =  ( !empty ( $_POST['option'] ) ) ? $_POST['option'] : $_GET['id'];
		$QY  =  $db->query ( "SELECT * FROM `phpdesk_troubles` WHERE `id` = '" . $ID ."'" );
		// Fetch
		$FY  =  $db->fetch ( $QY );
		
		if ( empty ( $_GET['id'] ) && empty ( $_POST['option'] ) )
		{
			echo $error['id_missing'];
		}
		elseif( $FY['view'] != 'All' && $L_TYPE == NULL )
		{
			echo $error['trouble_no_view'];
		}
		elseif( !$db->num ( $QY ) )
		{
			echo $error['no_auth_or_record'];
		}
		else
		{		
			// Find Out Parent ID
			$PAR   =  ( $FY['isparent'] == '1' ) ? $FY['id'] : $_GET['parent'];
			$TEXT  =  rpl ( "\n", '<br>', $FY['text'] );
			$TITLE =  $FY['title'];
				
			// Parse The Template File And Prepare Vars
			_parse ( $tpl_dir . 'troubleshooter.tpl' );
			$Read  =  $class->read;
			$Read  =  template ( $Read, $T_ST . 'view', '/#view]' );
			$Pare  =  template ( $Read, $T_ST . 'par', '/#par]' );
			$CTop  =  template ( $Read, '/#par]', $T_ST . 'child' );
			$Child =  template ( $Read, $T_ST . 'child', '/#child]' );
			$CEnd  =  template ( $Read, '/#child]', NULL );
				
			// Parent TPL
			echo $Pare;
				
			// Find Out Childs
			$Childs  =  $db->query ( "SELECT * FROM `phpdesk_troubles` WHERE `parent` = '" . $FY['id'] ."'" );
				
			// If Records Found
			if ( $db->num ( $Childs ) )
			{
					
				// Child Upper TPL
				echo $CTop;

				// ---- GET OPTIONS FOR TROUBLESHOOTER ---- //
				while ( $CY = $db->fetch ( $Childs ) )
				{
					//$Text  =  ( !empty ( $CY['text'] ) ) ? '<br>' . $CY['text'] : NULL;
						
					$Out  =  rpl ( '^ID^', $CY['id'], $Child );
					$Out  =  rpl ( '^TITLE^', $CY['title'], $Out );
				
					// Print Out
					echo $Out;
	
				}
				// ---- GET OPTIONS FOR TROUBLESHOOTER ---- //					
					
				// Child Down TPL
				echo $CEnd;

			}
			else
			{
				// No More Options
				echo $general['no_more_opt'];
									
			}
	
		} // End Validation
	}

} // End Auth Check

?>