<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| newticket.php :: Allows the user to open a new support ticket        | 
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*    $Id: newticket.php,v 1.00.0.1 27/10/2002 11:19:38 mark Exp $    */

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

# Check to see if a support ticket is being added

if($_SUBMIT['add'] == 1) {
	
	# Add the ticket
	# :: Start building the query
	
	$query = "INSERT INTO `$CONF[table_prefix]tickets`
		 VALUES
		 ('',
		  '".addslashes($_SUBMIT['subject'])."',
		  'High',
		  '".addslashes(nl2br($_SUBMIT['message']))."',";
	
	# Add the ticket
	# :: Add to the query the user added fields
	#  : Retrive all the fields from database

	$result = mysql_list_fields($CONF['dbname'],"$CONF[table_prefix]tickets",$db->connection);
	
	$num_fields = mysql_num_fields($result);

	# Add the ticket
	#  : Now we have the fields, we're going to build an array of user added fields,
	#  : ensuring we exclude the default fields
	#  : The fields to be excluded array

	$exc_fields = array("id","subject","priority","message","parent_id","datestarted","status","is_reply","category","user_id","admin_id");

	$added_fields = array();
	
	for ($i = 0; $i < $num_fields; $i++) {
	
		if(!in_array(mysql_field_name($result, $i),$exc_fields)) {
		
			$added_fields[] = mysql_field_name($result,$i);
		
		}
		
	}

	#  : Now we must add all the fields 

	foreach($added_fields as $field) {
		
		#  : Now we must get the value of this variable
		#    and add it to the query
		
		$var_name = input_name($field);
		
		$value = $$var_name;
		
		$query .= "'".addslashes($value)."',";
		
	}

	# Add the ticket
	# :: Now complete the query
	
	$query .= "'$_SUBMIT[parent_id]',
		  '".time()."',
		  'open',
		  '0',
		  '".addslashes($_SUBMIT['category'])."',
		  '".$HTTP_COOKIE_VARS['user_data']['2']."',
		  '$_SUBMIT[admin_id]')";
	
	# Add the ticket
	# :: Execute the query
	
	$result = $db->Query($query);
	
	if($result) {
		
		output("<div class=heading>New Support Ticket</div><br>Your
			support ticket has been added and is ticket number
			# ".mysql_insert_id().". Responses to the ticket
			will appear in your account or via email.<br><br>
			&raquo; <a href='index.php'>Return</a>");
		
		$template->createPage();
		
		exit();
		
	}
	
	# Add the ticket
	# :: Finished
	
}	

# Create category list

$categories = "<select name=category>";

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]categories`
		    WHERE is_scat = '0'
		    ORDER BY title ASC");
	
$categories .= "<option value=\"General Enquiry\">+ General Enquiry</option>";
	

# Build list of categories

while($row_info = $db->fetch_row($result)) {

	# Add main category
	
	$categories .= "<option value=\"".clear($row_info['title'])."\">+ $row_info[title]</option>";
	
	# Retrieve any dependants
	
	$subs = $db->Query("SELECT * FROM `$CONF[table_prefix]categories`
			  WHERE is_scat = '1'
			  AND parent_id = '$row_info[id]'");
	
	while($sub_info = $db->fetch_row($subs)) {
		
		$categories .= "<option value=\"".clear($sub_info['title'])."\">&nbsp;- &nbsp;$sub_info[title]</option>";
		
	}
		
}

$categories .= "</select>";

# Output the page header

output("<div class=heading>New Support Ticket</div><br>Please fill in this form
        to submit a support ticket and a member of our support team will contact
        you as soon as possible. Please try and give a full description of the
        problem as you can.<br><br>");

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

output("<table width=100% cellpadding=0 cellspacing=0>
	<form action='$PHP_SELF' method='post' onsubmit='return validateForm()' name=theForm>
	<input type=hidden name=add value=1 />
	<tr><td width=50% valign=top><b>Subject:</b> (*)</td><td width=50%><input type=text name=subject size=22></td></tr>
	<tr><td width=50% valign=top><b>Category:</b></td><td width=50%>$categories</td></tr>");

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
	<input type=submit value='Open Ticket' /> <input type=reset />
	</form><a href='index.php'>&raquo; Return</a>");

# Create Page

$template->CreatePage();

?>