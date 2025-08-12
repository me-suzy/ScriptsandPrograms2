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


if( $L_TYPE == 'staff' || $L_TYPE == 'admin' )
{
	
	switch ( TYPE )
	{
		
		case 'diary':
			
			if( SUBM != NULL )
			{
				
				if( empty( $_POST['diary'] ))
				{
					echo $error['fields'];
				}
				else
				{
					$Record = $db->query( "SELECT id FROM `phpdesk_diary` WHERE `admin_user` = '". USER ."'" );
					if( $db->num( $Record ))
					{
						$SQL = "UPDATE `phpdesk_diary` SET `text` = '". $_POST['diary'] ."' WHERE "
							  ."`admin_user` = '". USER ."'";
					}
					else
					{
						$SQL = "INSERT INTO `phpdesk_diary` SET `admin_user` = '". USER ."', `text` = '" 
							  . $_POST['diary'] . "'";
					}
					
					if( $db->query( $SQL ))
					{
						echo $success['update_diary'];
					}
				}
				
			}
	
	}
}

	

?>