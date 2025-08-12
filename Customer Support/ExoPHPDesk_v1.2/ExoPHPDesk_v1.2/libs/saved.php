<?php

// << -------------------------------------------------------------------- >>
// >> ExoHelpDesk File
// >>
// >> SAVED . PHP File - Save Responses For Quick Use
// >> Started : January 03, 2004
// >> Edited  : January 25, 2004
// << -------------------------------------------------------------------- >>

// Check For Direct Access
if( !isset( $InDirectCall ) )
{
	die( 'NO DIRECT ACCESS' );
}

// CHECK FOR AUTHORIZATION
if( $L_TYPE != 'members' )
{
	// CHECK FOR TYPE
	switch( $_GET['type'] )
	{
		case 'list':
			
			// SELECT SAVED RESPONSES FROM TABLE
			$Q  =  $db->query ( "SELECT * FROM `phpdesk_saved` WHERE `type` = 'Response'" );
			
			// PARSE THE LIST FILE
			_parse ( $tpl_dir . 'list.tpl' );
			$Read    =  $class->read;
			
			// GET TEMPLATES
			$Top     =  template ( $Read, NULL, $T_ST );
			$Down    =  template ( $Read, $T_ST . 'saved', '/#saved]' );
			$List    =  template ( $Down, $T_ST, $T_ED );
			$Down    =  template ( $Down, NULL, $T_ST );
			
			// Print Page
			echo $Top . $Down;
			
			while ( $F = $db->fetch ( $Q ) )
			{
				// Plus The Counter
				$X++;
				
				// Get Background Class
				$BG     =  ( is_float ( $X / 2) ) ? "tdbg1" : "tdbg2";
				
				// Prepare Links
				$Links  =  '[ <a href="'. $_SERVER['PHP_SELF'] .'l_type='. $L_TYPE .'&type=add&id='. $F['id'] .'">Edit</a> ] ';

				// If Admin Logged In
				if ( $L_TYPE != 'staff' )
				{
					$Links .=  '[ <a href="'. $_SERVER['PHP_SELF'] .'l_type='. $L_TYPE .'&type=delete&id='. $F['id'] .'">Delete</a> ] ';
				}
				
				// Prepare List
				$Out    =  str_replace ( '^TITLE^', $F['title'], $List );
				$Out    =  str_replace ( '^ACTIONS^', $Links, $Out );
				$Out    =  str_replace ( '^td_bg^', $BG, $Out );

				// Print Out
				echo $Out;
			}
			
			break;
				
		case 'add':
			
			// If Form Not Submitted
			if ( SUBM == NULL )
			{
				// If Editing ( ID Available )
				if ( !empty ( $_GET['id'] ) )
				{
					// SQL Query
					$Q  =  $db->query ( "SELECT * FROM phpdesk_saved WHERE `id` = '" . $_GET['id'] ."'" );
					
					// If Record Not Found
					if ( !$db->num ( $Q ) )
					{
						echo $error['no_auth_or_record'];
					}
					else
					{
						// Fetch
						$F = $db->fetch ( $Q );
						
						// Prepare Vars
						$Title  =  $F['title'];
						$Text   =  $F['text'];
					}
				}
				
				// Parse ADD . TPL File
				_parse ( $tpl_dir . 'add.tpl' );
				
				// Prepare Page
				$Read  =  $class->read;
				$Top   =  template ( $Read, NULL, $T_ST );
				$Down  =  template ( $Read, $T_ST . 'saved', '/#saved]' );
			
				// Print Out
				echo $Top . $Down;
			}
			// If Form Submitted
			else
			{
				// Assign $_POST to $P
				$P = $_POST;
				
				// Check for empty fields
				if ( array_search ( '', $P ) )
				{
					echo $error['fields'];
				}
				else
				{
					// Prepare SQL Query
					$SQL = "INSERT INTO `phpdesk_saved` SET `title` = '". $P['title'] ."', `text` = '". $P['text'] ."',"
						  ."`type` = 'Response'";
					
					// If Editing
					if ( !empty ( $_GET['id'] ) )
					{
						// Prepare SQL Query
						$SQL = "UPDATE `phpdesk_saved` SET `title` = '". $P['title'] ."', `text` = '". $P['text'] ."' "
							  ."WHERE `id` = '" . $_GET['id'] ."'";
					}
					
					// If Query Execution Successfull
					if ( $db->query ( $SQL ) )
					{
						// Print Success Message
						echo $success['save_response'];
					}
									
				}
					
			}
			
			break;

		case 'delete': // IF ACTION IS TO DELETE
				
			// IF NO ID FOUND
			if( !isset( $_GET['id'] ) )
			{
				// Print Error Message	
				echo $error['id_missing'];
				
			}
			// If Staff Trying To Delete
			elseif ( $L_TYPE == 'staff' )
			{
				// Print Error Message
				echo $error['no_auth_or_record'];
				
			}
			elseif( $_GET['confirm'] != 'YES' )
			{
				// CONFIRMATION
				echo 'Do you really want to delete the saved response: <b>'.$_GET['id'].'</b><br />';
				echo '[ <a href="'.$_SERVER['PHP_SELF'].'l_type='. $_GET['l_type'] .'&type=delete&confirm=YES&id='.$_GET['id'].'&tid='. $_GET['tid'] .'">Yes</a> ] ';
				echo '[ <a href="'. $_SERVER['HTTP_REFERER'] .'">No</a> ] <br />';
			}
			else
			{
				// DELETE QUERY
				if ( $db->query ( "DELETE FROM `phpdesk_saved` WHERE id = '". $_GET['id'] ."'" ) )
				{
					// Print Success Message
					echo $success['del_saved'];
				}
			}
			break;
							
		default:
			// If No Known Type
			break;
		
			
	} // END SWITCH

} // END AUTH CHECK

?>