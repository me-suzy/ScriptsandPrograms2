<?php
	//Login Process Page
	//Created July 24, 2005
	//Created by Jason Farrell

	include_once "./classes/user.php";
	if (isset($_POST['uname'], $_POST['passwd'])) {
		if (!mysql_ping()) die("Unable to Connect to Database");	
		
		$q = "select id from " . DB_PREFIX . "accounts where user = '" . mysql_real_escape_string($_POST['uname']) . "' and pass = '" . md5($_POST['passwd']) . "'";
		$s = mysql_query($q) or die(mysql_error());
		if (mysql_num_rows($s)) {
			$user = new User(mysql_result($s, 0));
			$_SESSION['enduser'] = serialize($user);
			$_SESSION['loggedIn'] = true;
			
			if ($user->get('securityLevel') > 0) {
				header("Location: DataAccess.php?filter=active");	
			}
		}
		else {
			$error_msg = "Invalid Login";	
		}
	}
?>