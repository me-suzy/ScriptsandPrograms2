<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| removeadmin.php :: Admin user removal script                         |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*   $Id: removeadmin.php,v 1.00.0.1 16/10/2002 21:33:15 mark Exp $   */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(1);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

# Connect to database

$db = new Database;

$db->Connect($CONF['dbname']);

# See if we have a form submitally

if($_SUBMIT['add'] == 1) {
	
	# Check the admin has permission to do this
	# before going any further

	if($HTTP_COOKIE_VARS['admin_data']['2'] > 1) {
	
		header("Location: useradmins.php?e=1");
	
		exit();
	
	}
	
	# Add the administrator
	
	$query = "INSERT INTO `$CONF[table_prefix]admins`
		 VALUES (
		 '',
		 '".addslashes($_SUBMIT['username'])."',
		 '".crypt($_SUBMIT['password'],"DD")."',";
	
	if($_SUBMIT['plain'] != 1) {
		
		$query .= "'',";
						
	}
	else {
		
		$query .= "'$_SUBMIT[password]',";
		
	}
	
	$query .= "'$_SUBMIT[level]','0')";
	
	$result = $db->Query($query);
	
	# Administrator added
	
}

output("<div class=heading>Manage Users</div>The following tools can be used
        to control all users that use the admin panel. If you would like to
        add support staff, please <a href='usersstaff.php'>go here</a>. For
        explanations of user levers please consult the user guide.<br><br>");

if($_SUBMIT['e'] == 1) {
	
	output("<font color='#990000'><b>Error:</b> You do not have permission to perform that operation.</font><br><br>");
	
}

tableheading("Edit Users");

# Select all admins and print

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]admins`
		    ORDER BY username ASC");

if($db->num_rows($result) == 0) {
	
	output("<tr bgcolor=$_TEMPLATE[light_background]><td $full_border>
		&nbsp;&nbsp;There are currently no admins on the system
		</td></tr>");
	
}

$indicator=0;

while($row_info = $db->fetch_row($result)) {
	
	# Usual indicator rubbish
	
	$indicator==0 ? $color=$_TEMPLATE['light_background'] : $color=$_TEMPLATE['dark_background'];
	$indicator==0 ? $indicator=1 : $indicator=0;
	
	# Levels array
	
	$levels = array("1" => "Super Administrator",
		       "2" => "General Administrator",
		       "3" => "Support Staff");
	
	# Print the row
	
	output("<tr bgcolor=$color><td $left_border width=50%>&nbsp;&nbsp;<b>$row_info[username]</b> :: (".$levels[$row_info[level]].")</td>
		<td width=50% $right_border>[ <a href='removeadmin.php?id=$row_info[id]'>Remove</a> ]</td></tr>");
	
}

output("</table><br>");

tableheading("Add Admin");
output("<script language=javascript>
	function validateForm() {

		if(document.theForm.username.value == \"\") {

			alert(\"You must enter a value in the username field.\");
			document.theForm.username.focus();
			return false;

		}

		if(document.theForm.password.value == \"\") {

			alert(\"You must enter a value in the password field.\");
			document.theForm.password.focus();
			return false;

		}
	
	}
	</script>");
output("<form action='$PHP_SELF' method='post' name=theForm value='return validateForm()'>");
output("<input type=hidden name=add value=1>");
output("<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">&nbsp;&nbsp;<b>Username:</b> (*)<br>&nbsp;&nbsp;The username of this admin</td><td width=50% style=\"border-right: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\"><input type=text name=username></td></tr>");
output("<tr bgcolor=$_TEMPLATE[dark_background]><td width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">&nbsp;&nbsp;<b>Password:</b> (*)<br>&nbsp;&nbsp;The password of this admin</td><td width=50% style=\"border-right: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\"><input type=password name=password></td></tr>");
output("<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">&nbsp;&nbsp;<b>Password Encryption:</b><br>&nbsp;&nbsp;Would you like to store a plain text password?*</td><td width=50% style=\"border-right: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\"><input type=radio name=plain value=1> Yes <input type=radio name=plain value=0> No</td></tr>");
output("<tr bgcolor=$_TEMPLATE[dark_background]><td width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">&nbsp;&nbsp;<b>User Level:</b><br>&nbsp;&nbsp;The level of this admin</td><td width=50% style=\"border-right: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\"><select name=level><option value=1>Super Administrator</option><option value=2>Admin</option><option value=3>Support Staff</option></select></td></tr>");
output("</table><br><input type=submit value='Add Admin'></form>* Storing a plain text password will make it easier to retrieve a password if the user forgets his/her password. However,
        it does make it potentially easier to steal this user's password.<br><br>");

$template->createPage();

?>