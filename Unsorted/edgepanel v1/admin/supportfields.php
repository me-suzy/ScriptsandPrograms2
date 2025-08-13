<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| supportfields.php :: Admin support fields management                 |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*  $Id: supportfields.php,v 1.00.0.1 20/09/2002 21:00:00 mark Exp $  */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(2);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";


output("<div class=heading>Support Ticket Fields</div>To add a new field to the support ticket database,
        i.e. to allow users to input a separate piece of information to the default options, please create
        and edit fields below.<br><br>");

# Connect to database

$db = new Database;

$db->Connect($CONF['dbname']);

if($_SUBMIT['add'] == 1) {
	
	# Add a new field
	
	$query = "ALTER TABLE `$CONF[table_prefix]tickets`
	          ADD COLUMN `$_SUBMIT[field]` LONGTEXT NOT NULL 
	          AFTER `message`";
	
	$result = $db->Query($query);
	
	if($result) {
		
		output("<font color=#006633><b>Success:</b> Your field has been added.</font><br><br>");
		
	}
	else {
		
		output("<font color=#990000><b>Error:</b> Your field could not be added. Please try again.</font><br><br>");
		
	}
	
}

output("<table width=100% cellpadding=0 cellspacing=0>
         <tr><td colspan=2 style=\"border-bottom: 1px solid $_TEMPLATE[border_color]\">
          <table width=115 cellpadding=0 cellspacing=0><tr><td bgcolor=$_TEMPLATE[border_color]>
          <font color=white><b><center>Current Fields</center></b></font>
          </td></tr></table>
         </td></tr>");

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

# Now we print any fields

if(count($added_fields) == 0) {
	
	output("<tr bgcolor=$_TEMPLATE[light_background]><td colspan=2 style=\"
	border-bottom: 1px solid $_TEMPLATE[border_color];
	border-left: 1px solid $_TEMPLATE[border_color];
	border-right: 1px solid $_TEMPLATE[border_color];\" height=20>
	&nbsp;&nbsp;You have added no fields to the support ticket system.
	</td></tr>");
	
}

$indicator = 0;

foreach($added_fields as $field) {
	
	$indicator == 0 ? $color = $_TEMPLATE['light_background'] : $color = $_TEMPLATE['dark_background'];
	$indicator == 0 ? $indicator = 1 : $indicator = 0;
	
	output("<tr bgcolor=$color><td width=50% height=20 style=\"
	border-bottom: 1px solid $_TEMPLATE[border_color];
	border-left: 1px solid $_TEMPLATE[border_color];\">
	&nbsp;&nbsp;<img src=../i/news-item.jpg align=center> $field</td>
	<td style=\"
	border-bottom: 1px solid $_TEMPLATE[border_color];
	border-right: 1px solid $_TEMPLATE[border_color];\">
	[ <a href='deletefield.php?f=$field'>Remove</a> ]
	</td></tr>");
	
}

output("</table><br>

<div class=heading>Add a New Field</div>Adding a field will allow you to specify a piece
of information you would like a client to provide when they submit a support ticket, like
their Client ID, or a similar bit of information.<br><br>".admininfobox("Added fields, are, by default,
non-required fields.")."<br>");

tableheading("New Field");

output("<script language=javascript>
function validateForm() {

        if(document.theForm.field.value == \"\") {
           
                alert(\"You must enter a value for the field name field.\");
                document.theForm.field.focus();
                return false;

        }

}
</script>
<form action='supportfields.php' method='post' name=theForm onSubmit='return validateForm()'>
<input type=hidden name=add value=1>
<tr bgcolor=$_TEMPLATE[light_background]><td style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color]\" height=30>&nbsp;&nbsp;<b>Field Name:</b> (*)</td><td style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-right: 1px solid $_TEMPLATE[border_color]\" ><input type=text name=field></td></tr>");

output("</table><br><input type=submit value='Create New Field'></form>");

$template->createPage();

?>