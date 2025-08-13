<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| servernews.php :: Admin server news script                           |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*    $Id: servernews.php,v 1.00.0.1 27/09/2002 17:07:40 mark Exp $   */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(2);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

output("<div class=heading>Server News</div>
        To update and add news items to the servers on the system use the
        features below. Server News items give you the opportunity to notify
        users in advance of events such as server upgrades or outage.<br><br>"
        .admininfobox("If a news item was originally applied to more than one server,
        when it is edited it will <b>still</b> apply to all servers to which it was applied,
        and the saved changes will also apply.")."<br>");
        
if($_SUBMIT['s'] == 1) {
	
	output("<font color=#006633><b>Success:</b> The Operation Completed Successfully.</font><br><br>");
	
}

if($_SUBMIT['a'] == 1) {
	
	output("<font color=#006633><b>Success:</b> News item added successfully.</font><br><br>");
	
}

tableheading("Server News");

$db = new Database;

$db->Connect($CONF['dbname']);

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]servers` ORDER BY `title` ASC");

if($db->num_rows($result) == 0) {
	
	output("<tr bgcolor=$_TEMPLATE[light_background]><td colspan=2 style=\"
	border-bottom: 1px solid $_TEMPLATE[border_color];
	border-left: 1px solid $_TEMPLATE[border_color];
	border-right: 1px solid $_TEMPLATE[border_color];\">
	&nbsp;&nbsp;There are currently no servers on the system.</td></tr>");
	
}

$indicator=0;

while($row_info = $db->fetch_row($result)) {
	
	# Usual indicator crap
	
	$indicator == 0 ? $color = $_TEMPLATE['light_background'] : $color = $_TEMPLATE['dark_background'];
	$indicator == 0 ? $indicator = 1 : $indicator = 0;
	
	# Print all servers and news items, first the title row	
	
	output("<tr bgcolor=$color><td colspan=2 style=\"
	border-left: 1px solid $_TEMPLATE[border_color];
	border-right: 1px solid $_TEMPLATE[border_color];\">
	&nbsp;&nbsp;<b>$row_info[title]</b>
	</td></tr>");
	
	# Now print all dependant news items
	
	$news = $db->Query("SELECT * FROM `$CONF[table_prefix]servernews` 
	                    WHERE servers LIKE '%::$row_info[id]' OR 
	                          servers LIKE '$row_info[id]::%' OR 
	                          servers LIKE '%::$row_info[id]::%' OR 
	                          servers LIKE '$row_info[id]' 
	                    ORDER BY `dateadded` DESC");
	
	if($db->num_rows($news) == 0) {
		
		output("<tr bgcolor=$color><td colspan=2 style=\"
		border-left: 1px solid $_TEMPLATE[border_color];
		border-right: 1px solid $_TEMPLATE[border_color];
		border-bottom: 1px solid $_TEMPLATE[border_color];\">
		&nbsp;&nbsp;&nbsp;&nbsp; - There are no news items on this server
		</td></tr>");
		
	}
	
	$i=0;
	$num_news = $db->num_rows($news);

	while($news_info = $db->fetch_row($news)) {
		
		# Print news item information
		
		output("<tr bgcolor=$color><td width=50% height=25 style=\"
		border-left: 1px solid $_TEMPLATE[border_color];");
		
		if($i == ($num_news - 1)) {
			
			output("border-bottom: 1px solid $_TEMPLATE[border_color];");
			
		}
		
		output("\">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=../i/news-item.jpg align=center> 
		$news_info[subject] :: ".date("m/d/y G:i:s",$news_info['dateadded'])."</td>
		
		<td height=27 style=\"
		border-right: 1px solid $_TEMPLATE[border_color];");
		
		if($i == ($num_news - 1)) {
			
			output("border-bottom: 1px solid $_TEMPLATE[border_color];");
			
		}
		
		output("\">[ <a href='copyservernews.php?id=$news_info[id]'>Copy</a> |
		<a href='editservernews.php?id=$news_info[id]'>Edit</a> | 
		<a href='deleteservernews.php?id=$news_info[id]&sid=$row_info[id]'>Delete</a> ]</td></tr>");
		
		$i++;
		
	}
	
}

output("</table><br>");

# Now for the addition table

output("<div class=heading>Add a News Item</div>Fill in this form to create a new news item.<br><br>");

tableheading("Add News");

# Build the server list

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]servers` ORDER BY `title` ASC");

$servers = "<table width=100% cellpadding=2 cellspacing=2>";

$indicator = 0;

if($db->num_rows($result) == 0) {
	
	$servers .= "<tr><td>No Servers On System</td></tr>";
	
}

//----------------------------------

while($row_info = mysql_fetch_array($result)) {
	
	# Add each server into the table
	
         $indicator == 0 ? $start = "<tr><td>" : $start = "<td>";
         $indicator == 0 ? $end = "</td>" : $end = "</td></tr>";
         $indicator == 0 ? $indicator = 1 : $indicator = 0;
         
         $servers .= "$start <input type=checkbox name=server_$row_info[id] value='1'> ".substr($row_info[title],0,16).".. $end\n";
         
}

$servers .= "<tr><td><hr size=1 noshade color=$_TEMPLATE[border_color]></td><td> </td></tr>
<tr><td colspan=2><input type=checkbox name=all value=1> Apply News Item To All Servers</td></tr></table>";

# Print the table rows and submit button

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
	
	<form action='addservernews.php' method='post' name=theForm onSubmit=\"return validateForm()\">
	<input type=hidden name=add value='1'>
	<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"
	border-left: 1px solid $_TEMPLATE[border_color];
	border-bottom: 1px solid $_TEMPLATE[border_color];\" valign=top>
        &nbsp;&nbsp;<b>Servers:</b> (*)<br>&nbsp;&nbsp;The servers to which this news
        item will be added</td>
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
	<input type=text name=subject>
	</td></tr>");

output("<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"
	border-left: 1px solid $_TEMPLATE[border_color];
	border-bottom: 1px solid $_TEMPLATE[border_color];\" valign=top>
        &nbsp;&nbsp;<b>Message:</b> (*)<br>&nbsp;&nbsp;The body of the news item</td>
        <td style=\"
	border-right: 1px solid $_TEMPLATE[border_color];
	border-bottom: 1px solid $_TEMPLATE[border_color];\">
	<textarea cols=30 rows=7 name=message></textarea>
	</td></tr>");

output("<tr bgcolor=$_TEMPLATE[dark_background]><td width=50% style=\"
	border-left: 1px solid $_TEMPLATE[border_color];
	border-bottom: 1px solid $_TEMPLATE[border_color];\" valign=top>
        &nbsp;&nbsp;<b>Added By:</b><br>&nbsp;&nbsp;Your Name/Username</td>
        <td style=\"
	border-right: 1px solid $_TEMPLATE[border_color];
	border-bottom: 1px solid $_TEMPLATE[border_color];\">
	<input type=text name=addedby>
	</td></tr>");

output("</table><br><input type=submit value='Add News Item'><br><br>");

$template->createPage();

?>