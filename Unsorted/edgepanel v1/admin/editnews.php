<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| editnews.php :: Admin news editing page                              |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*    $Id: editnews.php,v 1.00.0.1 25/10/2002 17:51:43 mark Exp $     */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(2);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

# Check we have a news ID, if not redirect

if(!isset($_SUBMIT['id'])) {
	
	header("Location: news.php");
	
	exit();
	
}

# Check if there has been a form submitted, and if so edit the news item

if($_SUBMIT['edit'] == 1) {
	
	# Form submitted, edit news
	
	$db = new Database;
	
	$db->Connect($CONF['dbname']);
	
	$query = "UPDATE `$CONF[table_prefix]news` SET title = '".
	          addslashes($_SUBMIT['title'])."',description = '".addslashes($_SUBMIT['description']).
	          "',addedby = '".addslashes($_SUBMIT['addedby'])."' WHERE id = '$_SUBMIT[id]'";
	          
	$result = $db->Query($query);
	
	if($result) {
		
		output("<div class=heading>Edit News Item</div>Your news item has been updated successfully.
		<br><br><a href='news.php'>&raquo; Return</a>");
		
		$template->createPage();
		
		exit();
		
	}
	else {
		
		output("<div class=heading>Edit News Item</div>Your news item could not be updated. Please try again.
		<br><br><a href='editnews.php?id=$_SUBMIT[id]'>&raquo; Return</a>");
		
		$template->createPage();
		
		exit();
		
	}
	
}	

# Display the editing form

$db = new Database;

$db->Connect($CONF['dbname']);

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]news` WHERE id = '$_SUBMIT[id]'");

$row_info = $db->fetch_row($result);

output("<div class=heading>Edit News Changes</div>Please make the required changes and click submit.");

output("<script language=javascript>
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
<input type=hidden name=edit value=1>
<input type=hidden name=id value='$_SUBMIT[id]'>");

tableheading("Edit News");

output("
<tr bgcolor=#F5F5F5><td $left_border>&nbsp;&nbsp;<b>News Title:</b> (*)<br>&nbsp;&nbsp;Brief title for this item</td><td $right_border><input type=text name=title value=\"".ereg_replace("\"","&quot;",$row_info[title])."\"></td></tr>
<tr bgcolor=#EEEEEE><td $left_border valign=top>&nbsp;&nbsp;<b>News Story:</b> (*)<br>&nbsp;&nbsp;The full news story</td><td $right_border><textarea cols=30 rows=7 name=description>$row_info[description]</textarea></td></tr>
<tr bgcolor=#F5F5F5><td $left_border>&nbsp;&nbsp;<b>Added By:</b><br>&nbsp;&nbsp;Your name (optional)</td><td $right_border><input type=text name=addedby value=\"$row_info[addedby]\"></td></tr>
</table><br><input type=submit value='Save Changes'>
</form>");

$template->createPage();

exit();

?>