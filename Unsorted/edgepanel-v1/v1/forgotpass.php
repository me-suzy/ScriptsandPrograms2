<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| forgotpass.php :: User password recovery script                      |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*   $Id: forgotpass.php,v 1.00.0.1 28/10/2002 21:03:32 mark Exp $    */

# Get Includes

require_once "./includes/functions.php";        # Functions Library
require_once "./includes/conf.global.php";      # Configuration Settings

# Start a new template object

$template = new Template;

# bye bye if we're using modernbill

if($CONF['userdriver'] != "database") {
	
	header("Location: login.php");
	
	exit();
	
}

# Recover password or not?

if($_SUBMIT['rec'] == 1) {
	
	if(empty($_SUBMIT['email'])) {
		
		header("Location: forgotpass.php");
		
		exit();
		
	}
	
	$db = new Database;
	
	$db->Connect($CONF['dbname']);
	
	$result = $db->Query("SELECT plain_password FROM `$CONF[table_prefix]users`
			    WHERE email = '$_SUBMIT[email]'");
	
	$row_info = $db->fetch_row($result);
	
	if($row_info['plain_password'] != "") {
		
		mail("$_SUBMIT[email]","Password Recovery From $CONF[sitename]","You requested a password recovery from $CONF[sitename] $CONF[script_name].\n\nYour password is: $row_info[plain_password]\n\nRegards,\n-------------\nThe $CONF[sitename] Team");
		
		output("<div class=heading>Forgotten Password</div><br>To recover your password
			enter your email address and your password will be emailed to you.<br><br><font color=#006633>
			<b>Success:</b> Your password has been emailed to you.</font><br><br><a href='login.php'>
			&raquo; Return to Login</a>");
		
		$template->createPage();
		
		exit();
		
	}
	
}

# Form

output("<div class=heading>Forgotten Password</div><br>To recover your password
	enter your email address and your password will be emailed to you.
	<form action='$PHP_SELF' method=post>
	<input type=hidden name=rec value=1>");

output("<b>Email Address:</b> <input type=text name=email><input type=submit value='Go'>");

output("</form><a href='login.php'>&raquo; Return To Login</a>");

$template->createPage();

?>