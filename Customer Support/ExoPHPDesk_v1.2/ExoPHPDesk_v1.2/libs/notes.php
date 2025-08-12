<?php

// << -------------------------------------------------------------------- >>
// >> ExoHelpDesk File
// >>
// >> NOTES . PHP File - For Notes Adding/Editing
// >> Started : December 08, 2003
// >> Edited  : January 24, 2004
// << -------------------------------------------------------------------- >>

// Check For Direct Access
if( !isset( $InDirectCall ) )
{
	die( 'NO DIRECT ACCESS' );
}

// FUNCTION TO INSERT A NOTE
function insert_note( $USER, $NOTE, $TID, $EDIT = '', $ID = '' )
{
	// GLOBAL VARS
	global $db, $success;
		
	// IF EDITING
	if( $EDIT != 'edit' )
	{
		// SQL QUERY
		$SQL = "INSERT INTO `phpdesk_notes` (`tid`,`sname`,`note`,`posted`)
				VALUES(
				'". $TID ."',
				'". $USER ."',
				'". $NOTE ."',
				'". time() ."'
				)";
	}
	else
	{
		// SQL QUERY
		$SQL = "UPDATE `phpdesk_notes` SET
				`note` = '". $NOTE ."'
				WHERE `tid` = '". $TID ."' 
				AND `id` = '". $ID ."'";
	}
		
	// EXECUTE SQL QUERY
	if( $db->query( $SQL ) )
	{
		echo $success['add_edit_note'];
	}

}
	
// IF MEMBER LOGGED IN AS STAFF THEN CHECK
// IF THERE ARE ENOUGH PERMISSIONS
if( $L_TYPE == 'staff' )
{
	// SQL QUERY
	$Q = $db->query( $sel_staff." WHERE username='".USER."'" );
	$F = $db->fetch( $Q );
		
	$Q1 = $db->query( "SELECT * FROM phpdesk_tickets WHERE id='".$_GET['tid']."'" );
	$F1 = $db->fetch( $Q1 );
		
	if( $F['groups'] != 'ALL' )
	{
		$GROUPS = explode( "|||", $F['groups'] );
		foreach( $GROUPS as $GROUP )
		{
			if( $F1['group'] == $GROUP )
			{
				$HAVE = 'YES';
				BREAK;
			}
		}
	}
	else
	{
		$HAVE = 'YES';
	}
}
	
// IF LOGGED IN AS STAFF AND HAVE PERMISSIONS
if( $L_TYPE == 'staff' && $HAVE != 'YES' )
{
	
	echo $error['no_auth_or_record'];
	
}
elseif( empty( $_GET['tid'] ) )
{
	// EMPTY TICKET ID
	echo $error['id_missing'];
	
}
else
{
	// PARSE THE TEMPLATE FILE
	_parse($tpl_dir.'add.tpl');
	$READ  = $class->read;
	
	// TOP & DOWN PART OF TEMPLATE
	$TOP   =  template ( $READ, NULL, $T_ST );
	$DOWN  =  template ( $READ, $T_ST . 'note', '/#note]' );		
		
	// CHECK WHAT ACTION TO TAKE - add/edit/delete
	switch( $_GET['type'] )
	{
		case 'add': // IF ACTION IS TO ADD
		
			if( SUBM == "" )
			{
				echo $TOP . str_replace( '^n_note^', '', $DOWN );
				break;
			}
			else
			{
				insert_note( USER, $_POST['note'], $_GET['tid'] );
			}
			break;
		
		case 'edit': // IF ACTION IS TO EDIT

			if( SUBM == "" )
			{
				if( empty( $_GET['id'] ) )
				{
					echo $error['id_missing'];
				}
				else
				{
					if( $L_TYPE == 'admin' )
					{
						$Q = $db->query( "SELECT * FROM `phpdesk_notes` WHERE `id` = '". $_GET['id'] ."'" );						
					}
					else
					{
						$Q = $db->query( "SELECT * FROM `phpdesk_notes` WHERE `id` = '". $_GET['id'] 
										."' AND `sname` = '". USER ."'");
					}
					if ( $db->num($Q) )
					{
						$F = $db->fetch( $Q );
						$OUT = str_replace( '^n_note^', $F['note'], $DOWN );
						echo $TOP . $OUT;
					}
				}
			
			}
			else
			{
				// INSERT NOTE USING insert_note() FUNCTION
				insert_note( USER, $_POST['note'], $_GET['tid'], 'edit', $_GET['id'] );
			}
			break;
			
		case 'delete': // IF ACTION IS TO DELETE
				
			// IF NO ID FOUND
			if( !isset( $_GET['id'] ) )
			{
					
				echo $error['id_missing'];
				
			}
			elseif( $_GET['confirm'] != 'YES' )
			{
				// CONFIRMATION
				echo 'Do you really want to delete the response: <b>'.$_GET['id'].'</b><br />';
				echo '[ <a href="'.$_SERVER['PHP_SELF'].'type=delete&confirm=YES&id='.$_GET['id'].'&tid='. $_GET['tid'] .'">Yes</a> ] ';
				echo '[ <a href="'. $_SERVER['HTTP_REFERER'] .'">No</a> ] <br />';
			}
			else
			{
				// IF LOGGED IN AS STAFF
				if( $L_TYPE == 'staff' )
				{
					// VALIDATE ACCESS TO NOTE
					$Q = $db->query($sel_note." WHERE `id` = '". $_GET['id'] ."' AND sname = '". USER ."'");
					if( !$db->num($Q) )
					{
						echo $error['no_auth_or_record'];
						$STOP = 1;
					}
				}
				
				// IF ACCESS FOUND
				if( $STOP != 1 )
				{
					// DELETE QUERY
					$db->query( "DELETE FROM `phpdesk_notes` WHERE id = '". $_GET['id'] ."'" );
					echo $success['del_note'];
				}
			}
			break;

			
	} // END SWITCH

} // END VALIDATION
	

?>