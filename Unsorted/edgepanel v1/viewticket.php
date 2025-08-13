<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| viewticket.php :: Allows the user to view his support tickets any    |
|                    any replies relevant to those tickets             | 
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*    $Id: viewticket.php,v 1.00.0.1 27/10/2002 11:19:38 mark Exp $   */

//----------------------------------
# Get Includes

require_once "./includes/functions.php";        # Functions Library
require_once "./includes/conf.global.php";      # Configuration Settings

//----------------------------------
# Check the user is authorised

auth_user();

//----------------------------------
# Create a new template object

$template = new Template;

//----------------------------------
# Database connection

$db = new Database;

$db->Connect($CONF['dbname']);

//----------------------------------
# Check we have a script ID

if(empty($_SUBMIT['id'])) {
	
	header("Location: viewtickets.php");
	
	exit();
	
}

//----------------------------------
# Retrieve ticket from database

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]tickets`
		    WHERE id = '$_SUBMIT[id]'
		    AND user_id = '".$HTTP_COOKIE_VARS['user_data']['2']."'");

$row_info = $db->fetch_row($result);

//----------------------------------
# Print out page header/ticket details

output("<div class=heading>View Ticket</div>This ticket contains
	the following information:<br><br>");

output("<table cellpadding=0 cellspacing=0 style=\"border: 1px 
	solid #495760\" width=100% bgcolor=#CFD4D6>");

output("<tr height=20><td width=40%>&nbsp;&nbsp;<b>ID:</b></td><td width=60%># $row_info[id]</td></tr>");
output("<tr height=20><td width=40%>&nbsp;&nbsp;<b>Subject:</b></td><td width=60%>$row_info[subject]</td></tr>");
output("<tr height=20><td width=40% valign=top>&nbsp;&nbsp;<b>Message:</b></td><td width=60%>$row_info[message]</td></tr>");
output("<tr height=20><td width=40%>&nbsp;&nbsp;<b>Category:</b></td><td width=60%>$row_info[category]</td></tr>");
output("<tr height=20><td width=40%>&nbsp;&nbsp;<b>Status:</b></td><td width=60%>".ucfirst($row_info['status'])."</td></tr>");

//----------------------------------

if($row_info['is_reply'] == 0) {
	
	$person = "You";
	
}
else {
	
	# We need to find out the name of the admin that sent this
	
	$result = $db->Query("SELECT username FROM `$CONF[table_prefix]admins`
			    WHERE id = '$row_info[admin_id]'");
	
	$admin_info = $db->fetch_row($result);
	
	$person = $admin_info['username'];
	
}

//----------------------------------

output("</table><br>
	This ticket was sent: ".date("m/d/y G:i:s",$row_info['datestarted'])." by <i>$person</i><br>
	<br>");


//----------------------------------
# This just makes sure it shows ALL
# relevant tickets, if the original ticket
# is being displayed

if($row_info['parent_id'] == 0) {
	
	$row_info['parent_id'] = $row_info['id'];
	
}

//----------------------------------
# Now we need to print a reply form,
# if the ticket is still set to open

if($row_info['status'] == "open") {
	
		output("<script language=javascript>
			function validateForm() {

				if(document.theForm.subject.value == \"\") {

					alert(\"You must enter a subject.\");
					document.theForm.subject.focus();
					return false;

				}

				if(document.theForm.message.value == \"\") {

					alert(\"You must enter a message.\");
					document.theForm.message.focus();
					return false;

				}

			}
			</script>");

		output("<div class=heading>Reply To Ticket</div>If you would like to reply, enter your
			information and click submit.<br><br>
			<table width=100% cellpadding=0 cellspacing=0 >
			<form action='newticket.php' method='post' onsubmit='return validateForm()' name=theForm>
			<input type=hidden name=add value=1 />
			<input type=hidden name=category value=\"".clear($row_info['category'])."\">
			<input type=hidden name=parent_id value='$row_info[parent_id]'>
			<input type=hidden name=admin_id value='$row_info[admin_id]'>
			<tr><td width=50% valign=top><b>Subject:</b> (*)</td><td width=50%><input type=text name=subject size=22></td></tr>");
			
		# Print any added fields

		# We need to look at the structure of the tickets table, and see what fields
		# have been added

		$result = mysql_list_fields($CONF['dbname'],"$CONF[table_prefix]tickets",$db->connection);

		$num_fields = mysql_num_fields($result);

		# Now we have the fields, we're going to build an array of user added fields,
		# ensuring we exclude the default fields

		# The fields to be excluded array

		$exc_fields = array("id","subject","priority","message","parent_id","datestarted","status","is_reply","category","user_id","admin_id");

		$added_fields = array();

		for ($i = 0; $i < $num_fields; $i++) {
	
			if(!in_array(mysql_field_name($result, $i),$exc_fields)) {
		
				$added_fields[] = mysql_field_name($result,$i);
		
			}
	
		}

		foreach($added_fields as $field) {
			
			output("<tr><td width=50% valign=top><b>$field:</b></td><td width=50% valign=top><input type=text name=".input_name($field)." size=22></td></tr>");
	
		}

		output("<tr><td width=50% valign=top><b>Message:</b> (*)</td><td width=50% valign=top><textarea rows=7 cols=22 name=message></textarea></td></tr>
			</table>");

		output("<br />
			<input type=submit value='Submit Reply' /> <input type=reset /></form>");
		
}

//----------------------------------
# Now we're gonna print all relevant tickets
# in descending order of date sent

output("<div class=heading>Relevant Tickets</div>Here are all the tickets related to this ticket:<br><br>
	<table cellpadding=0 cellspacing=0 style=\"border: 1px 
	solid #495760\" width=100% bgcolor=#CFD4D6>");

//----------------------------------

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]tickets`
		    WHERE user_id = '".$HTTP_COOKIE_VARS['user_data']['2']."'
		    AND parent_id = '$row_info[parent_id]'
		    OR id = '$row_info[parent_id]'
		    ORDER BY `datestarted` DESC");

while($row_info = $db->fetch_row($result)) {
	
	if($row_info['is_reply'] == 0) {
	
		$person = "You";
	
	}
	else {
	
		# We need to find out the name of the admin that sent this
	
		$admin = $db->Query("SELECT username FROM `$CONF[table_prefix]admins`
			    	   WHERE id = '$row_info[admin_id]'");
	
		$admin_info = $db->fetch_row($admin);
	
		$person = $admin_info['username'];
	
	}
	
	output("<tr height=20><td>&nbsp;&nbsp;<b><a href='viewticket.php?id=$row_info[id]'>$row_info[subject]</a></b> :: Sent ".date("m/d/y G:i:s",$row_info['datestarted'])." by <i>$person</i></td></tr>");
	
}

output("</table><br><a href='viewtickets.php'>&raquo; Return</a>");

//----------------------------------

$template->createPage();

?>