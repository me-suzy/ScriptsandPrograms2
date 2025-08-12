<?php

// << -------------------------------------------------------------------- >>
// >> EXO Helpdesk Registration File
// >>
// >> REGISTER . PHP File - User Registration Of HelpDesk
// >> Started : November 14, 2003
// >> Edited  : June 02, 2004
// << -------------------------------------------------------------------- >>

ob_start();

$EX_FILE  =  1;

include_once ( 'common.php' );

$_Q = $db->query ("SELECT * FROM phpdesk_configs WHERE registrations='Open'" );

if ( $NO_AUTH != 1 )
{
	// IF USER COOKIES EXISTS
	header( "Location: index.php" );
}
elseif(!$db->num($_Q))
{
	echo $error['register_close'];
}
else
{

	if( SUBM == NULL )
	{
		_parse( $tpl_dir . 'profile.tpl' );
		$read = getBlock( $class->read, 'MEMBER' );
		$n_pm = '<option value="1">Yes</option><option value="0">No</option>';
		$read = str_replace('^n_pm^',$n_pm, str_replace('^n_response^', $n_pm, $read));
		
		// PARSE ADD TICKET . TPL FILE
		_parse ( $tpl_dir . 'tickets.tpl' );
		
		// PREPARE VARIABLES
		$READ  =  getBlock( $class->read, 'ADD_TICKET' );
		$READ  =  rpl( '^e_text^', NULL, $READ );

		// THE OUTPUT VARIABLES
		$LIST  =  template ( $READ, $T_ST, $T_ED );
		$READ  =  template ( $READ, NULL, $T_ST );
			
		// GET FIELDS USING FUNCTION
		$FIELDS = get_fields( $LIST,'profile' );
		
		echo rpl( '^FIELDS^', $FIELDS, $read );
	}
	else
	{
		
		$_Q = $db->query("SELECT * FROM phpdesk_members WHERE username='{$_POST['username']}'");
		$_Q1 = $db->query("SELECT * FROM phpdesk_staff WHERE username='{$_POST['username']}'");
		$_Q2 = $db->query("SELECT * FROM phpdesk_admin WHERE name='{$_POST['username']}'");
		
		//
		// VALIDATIONS!!!
		// First validate the user fields
		// Second validate the custom fields
		//
		$VALIDATE = validate('user',$_POST);
		$valFields = validate('fields', $_POST, 'Profile');
		
		
		if( $valFields == 1 )
		{
			echo $error['fields'];
		}
		elseif( $VALIDATE != "" )
		{
			echo $VALIDATE;
		}
		elseif($db->num($_Q) || $db->num($_Q1) || $db->num($_Q2))
		{
			echo $error['user_exists'];		
		}
		else
		{
			//
			// Get an ID
			// Pretty messy right now... :(
			//
			$Q  = $db->query( "SELECT * FROM phpdesk_staff" );
			$Q1 = $db->query( "SELECT * FROM phpdesk_members" );
			$Q2 = $db->query( "SELECT * FROM phpdesk_admin" );
			$total = $db->num($Q) + $db->num($Q1) + $db->num($Q2) + 2;
			while($x < $total)
			{	
				$x++;
				$Q = $db->query("SELECT * FROM phpdesk_staff WHERE id='{$x}'");
				$Q1 = $db->query("SELECT * FROM phpdesk_members WHERE id='{$x}'");
				$Q2 = $db->query("SELECT * FROM phpdesk_admin WHERE id='{$x}'");
																
				if(!$db->num($Q) && !$db->num($Q1) && !$db->num($Q2))
				{
					$id_got = $x;
					break;
				}
			}
			
			// Get Fields and Values for mySQL
			$FIELDS  =  get_fields ( '', 'profile', 'SQL' );
			$VALUES  =  val_fields ( $_POST, 'profile' );
			
			$sql = "INSERT INTO phpdesk_members (id,username,name,password,email,website,notify_pm,notify_response,registered,tppage,`FIELDS`,`VALUES`)
			VALUES('".$id_got."','".trim($_POST['username'])."', '".$_POST['name']."', '".md5($_POST['password'])."', '".$_POST['email']."', '".$_POST['website']."',
			'".$_POST['n_pm']."', '".$_POST['n_response']."', '".time()."', '".$_POST['tppage']."', '$FIELDS', '$VALUES')";

			if($db->query($sql))
			{
				echo $success['register'];
			}

			echo mail_it('register', $_POST['email'], $general['mail_title']);
		}
	}
}

// INCLUDE FOOTER FILE
include_once ( 'footer.php' );

// Flush all the headers
ob_end_flush();

?>