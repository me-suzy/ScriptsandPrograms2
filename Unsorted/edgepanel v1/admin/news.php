<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| news.php :: Admin news management page                               |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*      $Id: news.php,v 1.00.0.1 11/11/2002 18:40:17 mark Exp $       */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(2);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

# If a form has been submitted, we need to add the news item

if($_SUBMIT['add'] == 1) {
	
	output("<div class=heading>Create a News Item</div>");
	
	$db = new Database;
	
	$db->Connect($CONF['dbname']);
	
	$query = "INSERT INTO `$CONF[table_prefix]news` VALUES ('','".
	          addslashes($_SUBMIT['title'])."','".addslashes($_SUBMIT['description']).
	          "','".time()."','".addslashes($_SUBMIT['addedby'])."')";
	
	output("<br><b>1.</b> Attempting to add database entry: ");
	
	$result = $db->Query($query);
	
	if($result) {
		
		output("<font color=#006633><b>Done.</b></font></b><br><br>
		<font color=#006633><b>Success:</b> Your news story has been added. <a href='news.php'>Return To News Management</a>.");
		
	}
	else {
		
		output("<font color=#990000><b>Failed.</b></font></b><br><br><font color=#990000>
		        <b>Error:</b> Your news story could not be added. MySQL Said: "
		        .mysql_error().". Please try again shortly.");
		
		$error=1;
		
	}
	
	$template->createPage();
	
	exit();

}	

# Header 

output("<div class=heading>&raquo; News Management</div>Please select a news story
        you would like to edit/remove or add a new story at the bottom of
        the page.<br><br>");

output("<table width=100% cellpadding=0 cellspacing=0><tr><td colspan=2 
        style=\"border-bottom: 1px solid #999999\"><table width=100 height=100% 
        cellspacing=0 cellpadding=0><tr><td bgcolor=#999999><b><center><font color=white>
        News Items</fon></b></td><td> </td></tr></table></td></tr>");

# Output all the news stories for editing

$db = new Database;

$db->Connect($CONF['dbname']);

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]news` ORDER BY `dateadded` DESC");

if($db->num_rows($result) == 0) {
	
	output("<tr bgcolor=#f5f5f5><td colspan=2 style=\"border-bottom: 1px solid #999999;
	        border-left: 1px solid $_TEMPLATE[border_color];
	        border-right: 1px solid $_TEMPLATE[border_color]\">
	        &nbsp;&nbsp;No news items in database</td></tr>");
	
}

# Print all news items

$i=1;

while($row_info = $db->fetch_row($result)) {
	
	$indicator == 0 ? $color=$_TEMPLATE['light_background'] : $color = $_TEMPLATE['dark_background'];
	$indicator == 0 ? $indicator = 1 : $indicator = 0;
	
	output("<tr bgcolor=$color><td style=\"border-bottom: 1px solid #999999;border-left: 1px solid #999999\">
	        &nbsp; <img src=../i/news-item.jpg align=bottom> &nbsp;$row_info[title]</td><td style=\"border-bottom: 1px solid #999999;border-right: 1px solid #999999\">
	        [ <a href='editnews.php?id=$row_info[id]'>Edit</a> | <a href='deletenews.php?id=$row_info[id]'>
	        Delete</a> ]</td></tr>");
	
	$i++;
	
}

# Finish table

output("</table>");

# Add news form

output("<br>
<script language=javascript>
function validateForm() {

         if(document.theForm.title.value == \"\") {

              alert(\"You must enter a value for the title field.\");
              document.theForm.title.focus();
              return false;

         }

         if(document.theForm.description.value == \"\") {

              alert(\"You must enter a news story.\");
              document.theForm.description.focus();
              return false;

         }

}
</script>
<form action='$PHP_SELF' method='post' name=theForm onSubmit=\"return validateForm()\">
<input type=hidden name=add value=1>

<table width=100% cellpadding=0 cellspacing=0>
<tr><td colspan=2 style=\"border-bottom: 1px solid $_TEMPLATE[border_color]\"><b><table width=130 cellpadding=0 cellspacing=0><tr><td bgcolor=$_TEMPLATE[border_color]><font color=white><b><center>Add a News Item</center></b></font></td></tr></table></td></tr>
<tr bgcolor=$_TEMPLATE[light_background]><td style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color]\">&nbsp;&nbsp;<b>News Title:</b> (*)<br>&nbsp;&nbsp;Brief title for this item</td><td style=\"border-bottom: 1px solid #999999;border-right: 1px solid $_TEMPLATE[border_color]\"><input type=text name=title></td></tr>
<tr bgcolor=$_TEMPLATE[dark_background]><td style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color]\" valign=top>&nbsp;&nbsp;<b>News Story:</b> (*)<br>&nbsp;&nbsp;The full news story</td><td style=\"border-bottom: 1px solid #999999;border-right: 1px solid $_TEMPLATE[border_color]\"><textarea cols=30 rows=7 name=description></textarea></td></tr>
<tr bgcolor=$_TEMPLATE[light_background]><td style=\"border-bottom: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color]\" >&nbsp;&nbsp;<b>Added By:</b><br>&nbsp;&nbsp;Your name (optional)</td><td style=\"border-bottom: 1px solid #999999;border-right: 1px solid $_TEMPLATE[border_color]\"><input type=text name=addedby></td></tr>
</table><br><input type=submit value='Add News Item'>
</form>


");

$template->createPage();

?>