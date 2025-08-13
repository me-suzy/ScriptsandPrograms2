<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| users.php :: Admin user management page                              |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*      $Id: users.php,v 1.00.0.1 11/11/2002 19:56:43 mark Exp $      */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(2);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

# Connect to database

$db = new Database;

$db->Connect($CONF['dbname']);

//----------------------------------
# Output page header

if($_SUBMIT['add'] == 1) {
	
	$query = "INSERT INTO `$CONF[table_prefix]users` VALUES
         	  ('',
          	   '$_SUBMIT[username]',
          	   '".crypt($_SUBMIT['password'],"DD")."',
          	   '$_SUBMIT[password]',
          	   '$_SUBMIT[username]')";
	
	if($db->Query($query)) {
	
		$s=1;
		
	}
	else {
		
		$e=1;
		
	}
	
}

if(isset($_SUBMIT['r'])) {
	
	$query = "DELETE FROM `$CONF[table_prefix]users`
		 WHERE id = '$_SUBMIT[r]'";
	
	if($db->Query($query)) {
	
		$s=1;
		
	}
	else {
		
		$e=1;
		
	}
	
}


output("<div class=heading>Manage Users</div>Use the following tools to manage
 	the users using the database.<br><br>".admininfobox("If you ever change
	the user database driver, be sure to empty the database first, or
	security will be compromised.")."<br>");

if($s == 1) {
	
	output("<font color=#006633><b>Success:</b> The Operation completed successfully.</font><br><br>");
	
}

if($e == 1) {
	
	output("<font color=#990000><b>Error:</b> The operation could not be completed.</font><br><br>");
	
}

if($CONF['userdriver'] == "modernbill") {
	
	output("<font color=#990000><b>Error:</b> The EdgePanel User Database driver is not in use.</font>");
	
	$template->createPage();
	
	exit();
	
}

tableheading("Edit Users");

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]users`
		    ORDER BY `email` ASC");

$total_rows = $db->num_rows($result);

if(isset($_SUBMIT['start']) && $_SUBMIT['start'] != 0) {

	$result = $db->Query("SELECT * FROM `$CONF[table_prefix]users`
		    	    ORDER BY `email` ASC LIMIT 10,$_SUBMIT[start]");
	
}
else {
	
	$result = $db->Query("SELECT * FROM `$CONF[table_prefix]users`
		    	    ORDER BY `email` ASC LIMIT 10");
		
}

if($db->num_rows($result) == 0) {
	
	output("<tr bgcolor=$_TEMPLATE[light_background]><td colspan=2 $full_border>
		&nbsp;&nbsp;There are no users in the database.</td></tr>");
	
}

$indicator=0;

while($row_info = $db->fetch_row($result)) {
	
	# Usual indicators
	
	$indicator == 0 ? $color = $_TEMPLATE['light_background'] : $color = $_TEMPLATE['dark_background'];
	$indicator == 0 ? $indicator = 1 : $indicator = 0;
	
	# Output row
	
	output("<tr bgcolor=$color><td width=50% $left_border>
		&nbsp;&nbsp;<b>$row_info[email]</b></td><td width=50% $right_border>
		<a href='users.php?r=$row_info[id]'>Remove</a></td></tr>");
	
}

//----------------------------------
# Next/Previous buttons

if($_SUBMIT['start'] > 0) {
	
	$previous = "<a href='users.php?start=".($_SUBMIT['start'] - 10)."'>« Previous</a>";
	
}
else {
	
	$previous = "« Previous";
	
}

if($total_rows > 10) {
	
	if(($total_rows - $_SUBMIT['start']) > 10) {
		
		$next = "<a href='users.php?start=".($_SUBMIT['start'] + 10)."'>Next »</a>";
		
	}
	
}
else {
	
	$next = "Next »";
	
}

output("<tr><td width=50%>$previous</td>
	   <td width=50%><div align=right>$next</div></td></tr>");

//----------------------------------

output("</table><br>");

tableheading("Add User");
output("<script language=Javascript>
	function validateForm() {

		if(document.theForm.username.value == \"\") {

			alert(\"You must enter a username.\");
			document.theForm.username.focus();
			return false;

		}

		if(document.theForm.password.value == \"\") {

			alert(\"You must enter a password.\");
			document.theForm.password.focus();
			return false;

		}

	}
	</script>");
output("<form action='$PHP_SELF' method=post name=theForm onsubmit='return validateForm()'>");
output("<input type=hidden name=add value=1>");
output("<tr bgcolor=$_TEMPLATE[light_background]><td width=50% $left_border>&nbsp;&nbsp;<b>Email Address:</b> (*)<br>&nbsp;&nbsp;The email of this user</td><td $right_border><input type=text name=username></td></tr>");
output("<tr bgcolor=$_TEMPLATE[dark_background]><td width=50% $left_border>&nbsp;&nbsp;<b>Password:</b> (*)<br>&nbsp;&nbsp;The password of this user</td><td $right_border><input type=password name=password></td></tr>");

output("</table><br><input type=submit value='Add User'></form>");

$template->createPage();

?>
