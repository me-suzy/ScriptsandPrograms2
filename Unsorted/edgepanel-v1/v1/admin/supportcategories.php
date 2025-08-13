<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| supportcategories.php :: Admin support categories management         |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*$Id: supportcategories.php,v 1.00.0.1 08/10/2002 20:37:16 mark Exp $*/

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

# Check of a form submission

if($_SUBMIT['add'] == 1) {
	
	$query = "INSERT INTO `$CONF[table_prefix]categories`
		VALUES (
		'',
		'".addslashes($_SUBMIT['title'])."',";
	
	if($_SUBMIT['cat'] == "none") {
		
		$query .= "'0','')";
		
	}
	else {
		
		$query .= "'1','$_SUBMIT[cat]')";
		
	}
	
	$result = $db->Query($query);
	
	# Categories added!
	
}		

# Page header

output("<div class=heading>Support Categories</div>Support categories are used
        to determine the nature (category) of a particular query. Use the following
        tools to edit/delete categories.<br><br>");

if($_SUBMIT['c'] == 1) {
	
	output("<font color=#006633><b>Success:</b> (1) Category updated successfully</font><br><br>");
	
}

tableheading("Edit Category");

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]categories`
		    WHERE is_scat = '0'
		    ORDER BY title ASC");

if($db->num_rows($result) == 0) {
	
	# No categories
	
	output("<tr bgcolor=$_TEMPLATE[light_background]>
	<td style=\"border-left: 1px solid $_TEMPLATE[border_color];
	border-right: 1px solid $_TEMPLATE[border_color];
	border-bottom: 1px solid $_TEMPLATE[border_color];\" colspan=2>
	&nbsp;&nbsp;There are currently no categories on the system
	</td>
	</tr>");
	
}

# Output all categories

while($row_info = $db->fetch_row($result)) {
	
	# Setup indicators
	
	$indicator == 0 ? $color = $_TEMPLATE['light_background'] : $color = $_TEMPLATE['dark_background'];
	$indicator == 0 ? $indicator = 1 : $indicator = 0;
	
	# Retrieve any dependants
	
	$subs = $db->Query("SELECT * FROM `$CONF[table_prefix]categories`
			  WHERE is_scat = '1'
			  AND parent_id = '$row_info[id]'");
		
	# Show the row
	
	output("<tr bgcolor=$color><td width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color];\">
	&nbsp;&nbsp;<b>$row_info[title]</b>
	</td>
	<td width=50% style=\"border-right: 1px solid $_TEMPLATE[border_color];\">
	[ <a href='editsupportcategory.php?id=$row_info[id]'>Edit</a> | <a href='deletesupportcategory.php?id=$row_info[id]'>Delete</a> ]
	</td></tr>");
	
	# Show dependants
	
	if($db->num_rows($subs) == 0) {
		
		output("<tr bgcolor=$color><td colspan=2 style=\"
		border-left: 1px solid $_TEMPLATE[border_color];
		border-right: 1px solid $_TEMPLATE[border_color];
		border-bottom: 1px solid $_TEMPLATE[border_color];\">
		&nbsp;&nbsp;&nbsp;&nbsp; - There are no subcategories for this main category
		</td></tr>");
		
	}
	
	$i=0;
	$num_subs = $db->num_rows($subs);

	while($sub_info = $db->fetch_row($subs)) {
		
		# Print news item information
		
		output("<tr bgcolor=$color><td width=50% height=25 style=\"
		border-left: 1px solid $_TEMPLATE[border_color];");
		
		if($i == ($num_subs - 1)) {
			
			output("border-bottom: 1px solid $_TEMPLATE[border_color];");
			
		}
		
		output("\">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src=../i/news-item.jpg align=center> 
		$sub_info[title]</td>
		
		<td height=27 style=\"
		border-right: 1px solid $_TEMPLATE[border_color];");
		
		if($i == ($num_subs - 1)) {
			
			output("border-bottom: 1px solid $_TEMPLATE[border_color];");
			
		}
		
		output("\">[ <a href='editsupportcategory.php?id=$sub_info[id]'>Edit</a> | 
		<a href='deletesupportcategory.php?id=$sub_info[id]'>Delete</a> ]</td></tr>");
		
		$i++;
		
	}
	
}	

output("</table><br>");

# Build category tree

$categories = "<select name=cat><option value=none>Add as a main category</option><option value=none>---------------------</option>";

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]categories`
		    WHERE is_scat = '0' 
		    ORDER BY `title` ASC");

while($row_info = $db->fetch_row($result)) {
	
	# Add to the tree
	
	$categories .= "<option value=$row_info[id]>- $row_info[title]</option>\n";
	
}

$categories .= "</select>";

# Table to add category

tableheading("Add Category");
output("<script language=javascript>
        function validateForm () {

             if(document.theForm.title.value == \"\") {

		alert(\"You must enter a value in the title field\");
		document.theForm.title.focus();
		return false;

             }

        }
        </script>");
output("<form action='$PHP_SELF' method='post' name=theForm onSubmit='return validateForm()'>");
output("<input type=hidden name=add value=1>");
output("<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">&nbsp;&nbsp;<b>Title:</b> (*)<br>&nbsp;&nbsp;The title of this category</td><td style=\"border-right: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\"><input type=text name=title></td></tr>");
output("<tr bgcolor=$_TEMPLATE[dark_background]><td width=50% style=\"border-left: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">&nbsp;&nbsp;<b>Sub-Category:</b><br>&nbsp;&nbsp;If you want this to be a sub-category, select its<br>&nbsp;&nbsp;main category</td><td style=\"border-right: 1px solid $_TEMPLATE[border_color];border-bottom: 1px solid $_TEMPLATE[border_color]\">$categories</td></tr>");

output("</table><br><input type=submit value='Add Category'></form>");

# Create the page

$template->createPage();

?>