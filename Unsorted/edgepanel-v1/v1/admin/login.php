<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| login.php :: Admin login page                                        |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*      $Id: login.php,v 1.00.0.1 16/10/2002 17:44:41 mark Exp $       */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

# Form submitted?

if($_SUBMIT['login'] == 1) {
	
	# Prepare the errors array
	
	$errorcodes = array();
	
	# Check the submitted form
	
	if(empty($_SUBMIT['username']) or empty($_SUBMIT['password'])) {
		
		$errorcodes[] = "1";
		$error=1;
		
	}
	
	# Attempt to retrive user from database
	
	$db = new Database;
	
	$db->Connect($CONF['dbname']);
	
	$_SUBMIT['password'] = crypt($_SUBMIT['password'],"DD");
	
	$result = $db->Query("SELECT * FROM `$CONF[table_prefix]admins`
			    WHERE
			    username = '$_SUBMIT[username]' AND
			    password = '$_SUBMIT[password]'");
	
	# Check a user is present
	
	if($db->num_rows($result) != 1) {
		
		$errorcodes[] = "2";
		$error=1;
		
	}
	else {
		
		# Login the user and redirect
		
		$row_info = $db->fetch_row($result);
		
		setcookie("admin_data[0]",$_SUBMIT['username']);
		setcookie("admin_data[1]",$_SUBMIT['password']);
		setcookie("admin_data[2]",$row_info['level']);
		setcookie("admin_data[3]",$row_info['id']);
		
		header("Location: index.php");
		
		exit();
		
	}
	
}

output("<div class=heading>Welcome To $CONF[script_name]</div>Enter your 
        username and password to login to the admin panel.<br><br>");

# Build the errors array

$errors = array("1" => "You did not fill in all required fields.",
	       "2" => "Your username/password combination is incorrect.");
	      
# Print errors (if necessary)

if($error == 1) {

	output("<b><font color=#990000>The Following Errors Were Found:</font></b><ul>");
	
	foreach($errorcodes as $code) {
		
		output("<li>$errors[$code]</li>");
		
	}
	
	output("</ul>");
	
}

tableheading("Login");
output("<form action='$PHP_SELF' method='post'>");
output("<input type=hidden name=login value=1>");
output("<tr bgcolor=$_TEMPLATE[light_background]><td height=30 style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color];\">&nbsp;&nbsp;<b>Username:</b></td><td style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-right: 1px solid $_TEMPLATE[border_color];\"><input type=text name=username></td></tr>");
output("<tr bgcolor=$_TEMPLATE[dark_background]><td height=30 style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color];\">&nbsp;&nbsp;<b>Password:</b></td><td style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-right: 1px solid $_TEMPLATE[border_color];\"><input type=password name=password></td></tr>");

output("</table><br><input type=submit value='Login'></form>Forgotten Your Password? - Please contact the
	system administrator for the recovery of your password.</a>");

$template->createPage();

exit();

?>