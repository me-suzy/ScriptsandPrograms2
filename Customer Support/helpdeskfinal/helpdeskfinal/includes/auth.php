<?php
$hd_cookie	= unserialize(stripslashes($_COOKIE['hd_userdata']));

//
// Project: Help Desk support system
// Description: User authentication. Gets user log status and info.
//

require_once "db.php";
require_once "config.php";

	

$user_id		= $hd_cookie['user_id'];
$user_password	= $hd_cookie['auth'];

// If cookie
if(!empty($user_id) && !empty($user_password))
{
	// Get user info and populate '$hduser' array
	$sql = "SELECT * FROM $TABLE_USERS WHERE user_id='$user_id' AND user_password='$user_password'";

	$r_user = mysql_query($sql) or
				error("Cannot authenticate user.");

	// If user info is correct
	if(mysql_num_rows($r_user)==1)
	{	
		$db_user = mysql_fetch_assoc($r_user);
	
		// Popuplate the '$hduser' array with the users info
		while($i < mysql_num_fields($r_user))
		{
			$db_field = mysql_fetch_field($r_user, $i);
	
			$hduser[$db_field->name] = $db_user[$db_field->name];
			
			$i++;
		}
		
		// Set logged in flag
		$hduser['logged_in'] = true;
	}
}
?>