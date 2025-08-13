<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| changepass.php :: User password changing script                      |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*   $Id: changepass.php,v 1.00.0.1 03/11/2002 12:44:15 mark Exp $    */

# Get Includes

require_once "./includes/functions.php";        # Functions Library
require_once "./includes/conf.global.php";      # Configuration Settings

# Check the user is authorised

auth_user();

# Create a new template object

$template = new Template;

# Database connection

$db = new Database;

$db->Connect($CONF['dbname']);

# Quick config check

if($CONF['userdriver'] != "database") {
	
	header("Location: index.php");
	
	exit();
	
}

# Change password then

if($_SUBMIT['update'] == 1) {
	
	$req = array("currpass","newpass","confnewpass");
	
	foreach($req as $field) {
		
		if(empty($_SUBMIT[ $field ])) {
			
			header("Location: changepass.php");
			
			exit();
			
		}
		
	}
	
	//----------------------------------
	
	if($_SUBMIT['newpass'] != $_SUBMIT['confnewpass']) {
		
		header("Location: changepass.php");
		
		exit();
		
	}
	
	//----------------------------------
	
	if(crypt($_SUBMIT['currpass'],"DD") != $HTTP_COOKIE_VARS['user_data']['1']) {
		
		header("Location: changepass.php");
		
		exit();
		
	}
	
	//----------------------------------
	
	$result = $db->Query("UPDATE `$CONF[table_prefix]users`
			    SET password = '".crypt($_SUBMIT['newpass'],"DD")."',
				plain_password = '$_SUBMIT[newpass]'
			    WHERE id = '".$HTTP_COOKIE_VARS['user_data']['2']."'");
	
	if($result) {
		
		output("<div class=heading>Change Password</div><br>Please change your password
		and click submit.<br><br>");
		
		output("<font color=#006633><b>Success:</b> Your password has been changed. <a href='login.php'>&raquo; Re-Login</a>");
		
		$template->createPage();
		
		exit();
		
	}
	
}

# Output page

output("<div class=heading>Change Password</div><br>Please change your password
	and click submit.<br><br>");

output("<table width=100% cellpadding=0 cellspacing=0>
	<form action='$PHP_SELF' method=post>
	<input type=hidden name=update value=1>
	<tr height=20><td width=40%><b>Current Pass:</b> (*)</td><td><input type=password name=currpass></td></tr>
	<tr height=20><td width=40%><b>New Pass:</b> (*)</td><td><input type=password name=newpass></td></tr>
	<tr height=20><td width=40%><b>Confirm Pass:</b> (*)</td><td><input type=password name=confnewpass></td></tr>
	</table><br><input type=submit value='Change Password'></form>");

$template->createPage();

?>