<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| login.php :: Allows the user to login to the system                  |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*     $Id: login.php,v 1.00.0.1 28/10/2002 21:03:32 mark Exp $       */

# Get Includes

require_once "./includes/functions.php";        # Functions Library
require_once "./includes/conf.global.php";      # Configuration Settings

# Start a new template object

$template = new Template;

# Check if a form has been submitted

if($_SUBMIT['login'] == 1) {
	
	# Login User
	# :: Check fields are present
	
	if(empty($_SUBMIT['email']) or empty($_SUBMIT['password'])) {
		
		$error=1;
		
	}
	
	# Which login method are we using?
	
	switch($CONF['userdriver']) {
		
		case "modernbill";
		
			# This database connection
		
			$db = new Database;
	
			$db->Connect($CONF['mbdbname']);
			
			# This query
				
			$query = "SELECT * FROM `$CONF[mbtable_prefix]client_info`
			    	 WHERE
			    	 client_email = '$_SUBMIT[email]' AND
			    	 client_real_pass  = '$_SUBMIT[password]'";
			
			$password = $_SUBMIT['password'];
			
			$id = "client_id";
		
		break;
		
		case "database";
		
			# This database connection
		
			$db = new Database;
	
			$db->Connect($CONF['dbname']);
			
			# This query
				
			$query = "SELECT * FROM `$CONF[table_prefix]users`
			    	 WHERE
			    	 email = '$_SUBMIT[email]' AND
			    	 password  = '".crypt($_SUBMIT['password'],"DD")."'";
			
			$password = crypt($_SUBMIT['password'],"DD");
			
			$id = "id";
		
		break;
		
	}
			
	$result = $db->Query($query);
	
	# Login User
	# :: Check the query returned a user
	
	if($db->num_rows($result) == 0) {
		
		$error=1;
		
	}
	
	# Login User
	# :: If there hasn't been any error, set
	#    the cookie and redirect
	
	if($error != 1) {
		
		# Login the user and redirect
		
		$row_info = $db->fetch_row($result);
		
		setcookie("user_data[0]",$_SUBMIT['email']);
		setcookie("user_data[1]",$password);
		setcookie("user_data[2]",$row_info[ $id ]);
		
		header("Location: index.php");
		
		exit();
		
	}
	
}

output("<div class=heading>User Login</div><br>Please login to the system:<br><br>");

if($error == 1) {
	
	output("<font color=#990000><b>Login Error:</b> Please re-enter your details.</font><br><br>");
	
}

output("<table width=100% cellpadding=0 cellspacing=3>
	<form action='$PHP_SELF' method='post'>
	<input type=hidden name=login value=1>
	<tr><td><b>Email Address:</b> (*)</td><td><input type=text name=email value=\"$_SUBMIT[email]\"></td></tr>
	<tr><td><b>Password:</b> (*)</td><td><input type=password name=password></td></tr>
	</table><br><input type=submit value='Login'> <input type=reset></form></a>");

if($CONF['userdriver'] == "database") {
	
	output("<a href='forgotpass.php'>Forgotten Your
	Password?");
	
}

$template->createPage();

?>