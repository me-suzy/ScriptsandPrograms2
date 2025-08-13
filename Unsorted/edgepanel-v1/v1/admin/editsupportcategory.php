<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| editsupportcategory.php :: Admin support categories editing          |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*$Id: editsupportcate--.php,v 1.00.0.1 09/10/2002 22:11:23 mark Exp $*/

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

# Check we have an id

if(!isset($_SUBMIT['id']) or ($_SUBMIT['id'] == "")) {
	
	# Bye Bye
	
	header("Location: supportcategories.php");
	
	exit();
	
}

# See if we have an edit

if($_SUBMIT['edit'] == 1) {
	
	if($_SUBMIT['is_scat'] == 1) {
				
		$query = "UPDATE `$CONF[table_prefix]categories`
		 	 SET
		 	 title = '".addslashes($_SUBMIT['title'])."',
			 parent_id = '$_SUBMIT[cat]'
			 WHERE id = '$_SUBMIT[id]'";
		
	}
	else
	{
		
		$query = "UPDATE `$CONF[table_prefix]categories`
		 	 SET
		 	 title = '".addslashes($_SUBMIT['title'])."'
			 WHERE id = '$_SUBMIT[id]'";
		
	}
	
	$result = $db->Query($query);

	header("Location: supportcategories.php?c=1");
	
	exit();
	
}

# Retrieve server info

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]categories`
		    WHERE id = '$_SUBMIT[id]'");

$row_info = $db->fetch_row($result);

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]categories`
		    WHERE is_scat = '0' 
		    ORDER BY `title` ASC");

$categories = "<select name=cat>";

while($crow_info = $db->fetch_row($result)) {
	
	# Add to the tree
	
	$categories .= "<option value=$crow_info[id]";
	
	if($crow_info['id'] == $row_info['parent_id']) {
		
		$categories .= " selected ";
		
	}	
	
	$categories .= ">- $crow_info[title]</option>\n";
	
}

$categories .= "</select>";

# Page header

output("<div class=heading>Edit Support Category</div>Please make the required
	changes then click submit.<br><br>");

output("<script language=javascript>
        function validateForm () {

             if(document.theForm.title.value == \"\") {

		alert(\"You must enter a value in the title field\");
		document.theForm.title.focus();
		return false;

             }

        }
        </script>");

tableheading("Edit Category");

output("<form action='$PHP_SELF' method=post name=theForm onSubmit='return validateForm()'>");
output("<input type=hidden name=edit value='1'>");
output("<input type=hidden name=id value='$_SUBMIT[id]'>");

output("<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">&nbsp;&nbsp;<b>Title:</b> (*)<br>&nbsp;&nbsp;The title of this category</td><td style=\"border-right: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\"><input type=text name=title value=\"".clear($row_info['title'])."\"></td></tr>");

# Output subcat row if it is a subcat

if($row_info['is_scat'] == 1) {
	
	output("<input type=hidden name=is_scat value=1>");	
	output("<tr bgcolor=$_TEMPLATE[dark_background]><td width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">&nbsp;&nbsp;<b>Sub-Category:</b><br>&nbsp;&nbsp;If you want this to be a sub-category, select its<br>&nbsp;&nbsp;main category</td><td style=\"border-right: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">$categories</td></tr>");
	
}

output("</table><br><input type=submit value='Save Changes'></form>");

$template->createPage();

?>