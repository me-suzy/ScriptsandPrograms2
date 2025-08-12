<?php

// << -------------------------------------------------------------------- >>
// >> ExoHelpDesk File
// >>
// >> RATE . PHP File - All rating stuff in this file
// >> Started : January 07, 2004
// >> Edited  : January 07, 2004
// << -------------------------------------------------------------------- >>

	// Check For An Id
	if ( empty ( $_GET['id'] )  )
	{
		// Print Out Error
		echo $error['id_missing'];
	}
	else
	{
		// Prepare A Query To Check If A Record Exists
		$CHECK  =  $db->query ( "SELECT * FROM `phpdesk_ratings` WHERE ratedby = '" . USER ."' AND `uid` = '" . $_GET['id'] ."' AND `type` = '" . ACT ."'" );

		if ( USER == NULL )
		{
			// Prepare A Query To Check If A Record Exists
			$CHECK  =  $db->query ( "SELECT * FROM `phpdesk_ratings` WHERE ratedby = 'Guest' AND `uid` = '" . $_GET['id'] ."' AND `ip` = '" . $_SERVER['REMOTE_ADDR'] ."'" );
		}
	
		// If Already Rated	
		if ( $db->num ( $CHECK ) )
		{
			// Print Error Message
			echo $error['rated_ago'];
		}
		elseif ( SUBM == NULL )
		{
			// Get What Action
			$WHO  =  ucfirst ( ACT );
			_parse ( $tpl_dir . 'add.tpl' );
			
			// Prepare Template Vars
			$READ =  $class->read;
			$TOP  =  template ( $READ, NULL, $T_ST );
			$DOWN =  template ( $READ, $T_ST . 'rate', '/#rate]' );
			
			// Print Out Page
			echo $TOP . $DOWN;
		}
		else
		{
			// Prepare Right Rating Var
			$RATING =  ( $_POST['rating'] == 0 ) ? "0" : $_POST['rating'];
			
			// Check If A Member Or Guest	
			$USER   =  ( USER == NULL ) ? "Guest" : USER;
			
			// Prepare SQL Query
			$SQL  =  "INSERT INTO `phpdesk_ratings` ( `rating`,`type`,`uid`,`ratedby`,`ip` ) VALUES ( "
					."'" . $RATING . "', '" . strtolower ( $_GET['action'] ) ."', '" . $_GET['id'] ."', "
					."'" . $USER . "', '" . $_SERVER['REMOTE_ADDR'] .  "')";
			
			// If Query Successfull			
			if ( $db->query ( $SQL ) )
			{
				if ( ACT == 'staff' )
				{
					$QY  =  $db->query ( "SELECT * FROM `phpdesk_ratings` WHERE `type` = 'staff' AND `uid` = '" . $_GET['id'] ."'" );
					$CT   =  $db->num ( $QY );
					$RATE =  0;
						
					while ( $FY  =  $db->fetch ( $QY ) )
					{
						$RATE += $FY['rating'];
					}

					$RATING  =  ( $RATE / ( $CT * 5 ) * 100 );
					$RATING  =  number_format ( ( 5 * $RATING / 100 ), 2 );

					$RATING  =  ( $CT > 0 ) ? $RATING : NULL;
					
					$db->query ( "UPDATE `phpdesk_staff` SET `rating` = '" . $RATING . "' WHERE `id` = '" . $_GET['id'] ."'" );
					
				}
				
				// Print Success Message
				echo $success['rated'];
			}
				
		}
		
	}

?>