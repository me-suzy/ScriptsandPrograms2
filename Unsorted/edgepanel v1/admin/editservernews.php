<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| editservernews.php :: Admin news editing function                    |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*  $Id: editservernews.php,v 1.00.0.1 30/09/2002 18:16:43 mark Exp $ */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(2);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

# Check we have an ID

if(!isset($_SUBMIT['id'])) {
	
	header("Location: servernews.php");
	
	exit();
	
}

# Connect to the database

$db = new Database;

$db->Connect($CONF['dbname']);

# See if a form has been submitted

if($_SUBMIT['edit'] == 1) {
	
	# Process the form
	# First: establish the server string
	
	$servers = array();
	
	# Database query
	
	$result = $db->Query("SELECT * FROM `$CONF[table_prefix]servers`
                      	    ORDER BY `title` ASC");
	
	while($row_info = $db->fetch_row($result)) {
		
		$string = "server_".$row_info['id'];
		
		if($_SUBMIT[$string] == 1) {
			
			$servers[] = "$row_info[id]";
			
		}
		
	}
	
	$server_string = implode($servers,"::");
	
	$query = "UPDATE `$CONF[table_prefix]servernews`
		 SET subject = '".addslashes($_SUBMIT['subject'])."',
	              message = '".addslashes($_SUBMIT['message'])."',
	              addedby = '".addslashes($_SUBMIT['addedby'])."',
	              servers = '$server_string'
	          WHERE id = '$_SUBMIT[id]'";
	
	$result = $db->Query($query);
	
	header("Location: servernews.php?s=1");
	
	exit();
	
}	

# Retrieve server news item

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]servernews`
                      WHERE id = '$_SUBMIT[id]'");

$row_info = $db->fetch_row($result);

# Ouput the editing form

output("<div class=heading>Edit Server News Item</div>Please make the changes
        you require then click submit.<br><br>");

tableheading("Edit News");

# Build array of servers

$servers_array = explode("::",$row_info['servers']);

# Now we need to build the listing of servers

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]servers` ORDER BY `title` ASC");

$servers = "<table width=100% cellpadding=2 cellspacing=2>";

$indicator = 0;

while($server_info = mysql_fetch_array($result)) {
	
	# Add each server into the table
	
         $indicator == 0 ? $start = "<tr><td>" : $start = "<td>";
         $indicator == 0 ? $end = "</td>" : $end = "</td></tr>";
         $indicator == 0 ? $indicator = 1 : $indicator = 0;
         
         $servers .= "$start <input type=checkbox name=server_$server_info[id] value='1' ";

         in_array("$server_info[id]",$servers_array) ? $servers .= " checked " : $servers .= "";
         
         $servers .= ">".substr($server_info[title],0,16).".. $end\n";
         
}

$servers .= "</table>";

output(" <script language=javascript>
         function validateForm() {

         	if(document.theForm.subject.value == \"\") {

              		alert(\"You must enter a value for the title field.\");
              		document.theForm.subject.focus();
              		return false;

         	}

         	if(document.theForm.message.value == \"\") {

              		alert(\"You must enter a news story.\");
              		document.theForm.message.focus();
              		return false;

        		}

         }
         </script>
	
	<form action='$PHP_SELF' method='post' name=theForm onSubmit=\"return validateForm()\">
	<input type=hidden name=edit value='1'>
	<input type=hidden name=id value=$_SUBMIT[id]>
	<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"
	border-left: 1px solid $_TEMPLATE[border_color];
	border-bottom: 1px solid $_TEMPLATE[border_color];\" valign=top>
        &nbsp;&nbsp;<b>Servers:</b> (*)<br>&nbsp;&nbsp;The servers using this item</td>
        <td style=\"
	border-right: 1px solid $_TEMPLATE[border_color];
	border-bottom: 1px solid $_TEMPLATE[border_color];\">$servers</td></tr>");

output("<tr bgcolor=$_TEMPLATE[dark_background]><td width=50% style=\"
	border-left: 1px solid $_TEMPLATE[border_color];
	border-bottom: 1px solid $_TEMPLATE[border_color];\" valign=top>
        &nbsp;&nbsp;<b>Title:</b> (*)<br>&nbsp;&nbsp;The title of the news item</td>
        <td style=\"
	border-right: 1px solid $_TEMPLATE[border_color];
	border-bottom: 1px solid $_TEMPLATE[border_color];\">
	<input type=text name=subject value=\"".clear(&$row_info['subject'])."\">
	</td></tr>");

output("<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"
	border-left: 1px solid $_TEMPLATE[border_color];
	border-bottom: 1px solid $_TEMPLATE[border_color];\" valign=top>
        &nbsp;&nbsp;<b>Message:</b> (*)<br>&nbsp;&nbsp;The body of the news item</td>
        <td style=\"
	border-right: 1px solid $_TEMPLATE[border_color];
	border-bottom: 1px solid $_TEMPLATE[border_color];\">
	<textarea cols=30 rows=7 name=message>".clear(&$row_info['message'])."</textarea>
	</td></tr>");

output("<tr bgcolor=$_TEMPLATE[dark_background]><td width=50% style=\"
	border-left: 1px solid $_TEMPLATE[border_color];
	border-bottom: 1px solid $_TEMPLATE[border_color];\" valign=top>
        &nbsp;&nbsp;<b>Added By:</b><br>&nbsp;&nbsp;Your Name/Username</td>
        <td style=\"
	border-right: 1px solid $_TEMPLATE[border_color];
	border-bottom: 1px solid $_TEMPLATE[border_color];\">
	<input type=text name=addedby value=\"".clear(&$row_info['addedby'])."\">
	</td></tr>");

output("</table><br><input type=submit value='Save Changes'></form><a href='servernews.php'>&raquo; Return To Server News Management</a><br><br>");

$template->createPage();

?>