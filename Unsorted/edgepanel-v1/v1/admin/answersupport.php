<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| answersupport.php :: Admin support ticked response script            |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*  $Id: answersupport.php,v 1.00.0.1 27/10/2002 16:58:44 mark Exp $  */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(3);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

# New database connection

$db = new Database;

$db->Connect($CONF['dbname']);

# Data Verification
# :: Check we have a ticket id

if(!isset($_SUBMIT['id'])) {
	
	header("Location: support.php");
	
	exit();
	
}

# Send Ticket Response
# :: Do we need to change the category?

if($_SUBMIT['changecat'] == 1) {
	
	$query = "UPDATE `$CONF[table_prefix]tickets`
		 SET
		 category = '$_SUBMIT[category]'
		 WHERE id = '$_SUBMIT[id]'";
	
	$result = $db->Query($query);
	
}

# Send Ticket Response
# :: Determine if a form has been submitted

if($_SUBMIT['send'] == 1) {
	
	# Send Ticket Response
	# :: Perform a switch to cover the different types
	#    of ticket response - ticket/email
	
	switch($_SUBMIT['type']) {
		
		# Send Ticket Response
		# :: Send this as a ticket
		
		case "ticket"; 
		
			# Send Ticket Response
			# :: Begin to construct the query
			
			if($_SUBMIT['parent_id'] == "0") {
				
				$_SUBMIT['parent_id'] = $_SUBMIT['id'];
				
			}
			
			//----------------------------------
			
			$query = "INSERT INTO `$CONF[table_prefix]tickets`
				 (id,subject,priority,message,parent_id,datestarted,status,is_reply,category,user_id,admin_id)
				 VALUES (
				 '',
				 '".addslashes($_SUBMIT['subject'])."',
				 'high',
				 '".addslashes(nl2br($_SUBMIT['message']))."',
				 '$_SUBMIT[parent_id]',
				 '".time()."',
				 '$_SUBMIT[status]',
				 '1',
				 '".addslashes($_SUBMIT['category'])."',
				 '$_SUBMIT[user_id]',
				 '".$HTTP_COOKIE_VARS['admin_data']['3']."')";
			
			# Send Ticket Response
			# :: Add query to database
			
			$result = $db->Query($query);
			
			# Sent Ticket Response
			# :: Set all relevant tickets' status' to closed
			
			$result = $db->Query("UPDATE `$CONF[table_prefix]tickets` SET
					    status = '$_SUBMIT[status]'
					    WHERE parent_id = '$_SUBMIT[parent_id]'
					    OR id = '$_SUBMIT[parent_id]'");
			
			# Send Ticket Response
			# :: Update old ticket for this admin
			
			$result = $db->Query("UPDATE `$CONF[table_prefix]tickets`
					    SET admin_id = '".$HTTP_COOKIE_VARS['admin_data']['3']."'
					    WHERE id = '$_SUBMIT[id]'");
			
			# Send Ticket Response
			# :: Print success message
			
			output("
			<meta http-equiv='refresh' content='2; url=support.php'>
			<div class=heading>Answer Support Ticket</div>Your support ticket has been sent. Returning to help desk...<br><br>
			<font color=#006633><b>Success:</b> Your response has been sent.");
			
			$template->createPage();
			
			exit();
			
		break;
		
	}
	
}
# --------------------------------------------------------
# Page Output
# :: Page header

output("<div class=heading>Answer Support Ticket</div>Please type your
        reply to this ticket and click submit.<br><br>");

# Print Ticket
# :: Retrieve ticket from database

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]tickets`
		    WHERE id = '$_SUBMIT[id]'");

$row_info = $db->fetch_row($result);

# Print Ticket
# :: Print the user's ticket

tableheading("User Ticket");

output("<tr bgcolor=$_TEMPLATE[light_background]><td width=20% $left_border>&nbsp;&nbsp;<b>ID:</b></td>
	<td $right_border># $row_info[id]</td></tr>");
output("<tr bgcolor=$_TEMPLATE[dark_background]><td width=20% $left_border>&nbsp;&nbsp;<b>Subject:</b></td>
	<td $right_border>$row_info[subject]</td></tr>");
output("<tr bgcolor=$_TEMPLATE[light_background]><td width=20% $left_border valign=top>&nbsp;&nbsp;<b>Message:</b></td>
	<td $right_border>$row_info[message]</td></tr>");

# Print Ticket
# :: Print the admin added fields

$result = mysql_list_fields($CONF['dbname'],"$CONF[table_prefix]tickets",$db->connection);
	
$num_fields = mysql_num_fields($result);

$exc_fields = array("id","subject","priority","message","parent_id","datestarted","status","is_reply","category","user_id","admin_id");

$added_fields = array();
	
for ($i = 0; $i < $num_fields; $i++) {
	
	if(!in_array(mysql_field_name($result, $i),$exc_fields)) {
		
		$added_fields[] = mysql_field_name($result,$i);
	
	}
		
}

$indicator = 0;

foreach($added_fields as $field) {
	
	# Color indicators
	
	$indicator == 0 ? $color = $_TEMPLATE['dark_background'] : $color = $_TEMPLATE['light_background'];
	$indicator == 0 ? $indicator = 1 : $indicator = 0;
	
	# Print row
	
	output("<tr bgcolor=$color><td width=20% $left_border valign=top>&nbsp;&nbsp;<b>$field:</b></td>
	<td $right_border>$row_info[$field]&nbsp;</td></tr>");
	
}

$indicator == 0 ? $color = $_TEMPLATE['dark_background'] : $color = $_TEMPLATE['light_background'];

	# --------------------------------------------------------
	# Create category list

	$categories = "<select name=category>";

	$result = $db->Query("SELECT * FROM `$CONF[table_prefix]categories`
		    	    WHERE is_scat = '0'
		    	    ORDER BY title ASC");
	
	if($row_info['category'] == "General Enquiry") {
		
		$c1 = "selected";
		
	}

	$categories .= "<option value=\"General Enquiry\" $c1>+ General Enquiry</option>";

	# Build list of categories

	while($cat_info = $db->fetch_row($result)) {

		# Add main category
		
		if($row_info['category'] == $cat_info['title']) {
				
			$c = "selected";
				
		}
		else {
			
			$c = "";
			
		}
	
		$categories .= "<option value=\"".clear($cat_info['title'])."\" $c>+ $cat_info[title]</option>";
	
		# Retrieve any dependants
	
		$subs = $db->Query("SELECT * FROM `$CONF[table_prefix]categories`
			  	  WHERE is_scat = '1'
			  	  AND parent_id = '$cat_info[id]'");
	
		while($sub_info = $db->fetch_row($subs)) {
			
			if($row_info['category'] == $sub_info['title']) {
				
				$s = "selected";
				
			}
			else {
				
				$s = "";
				
			}
		
			$categories .= "<option value=\"".clear($sub_info['title'])."\" $s>&nbsp;- &nbsp;$sub_info[title]</option>";
		
		}
		
	}

	$categories .= "</select>";

	# End Category List
	# --------------------------------------------------------

output("<tr bgcolor=$color><td width=20% $left_border valign=top>&nbsp;&nbsp;<b>Category:</b></td>
	<td $right_border>$row_info[category]&nbsp;<table><tr><td><form action='$PHP_SELF' method='post'><input type=hidden name=changecat value=1><input type=hidden name=id value='$_SUBMIT[id]'><b>Change To:</b> $categories <input type=submit value='Go'></td></tr></table></td></form></tr>");

output("</table><br>");

# Ticket Reponse
# :: Print the form

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

tableheading("Ticket Response");
output("<form action='$PHP_SELF' method='post' name=theForm onsubmit='return validateForm()'>");
output("<input type=hidden name=send value=1>");
output("<input type=hidden name=id value='$_SUBMIT[id]'>");
output("<input type=hidden name=user_id value='$row_info[user_id]'>");
output("<input type=hidden name=parent_id value='$row_info[parent_id]'>");
output("<input type=hidden name=category value=\"".clear($row_info['category'])."\">");
output("<tr bgcolor=$_TEMPLATE[light_background]><td width=50% $left_border>&nbsp;&nbsp;<b>Subject:</b> (*)<br>
	&nbsp;&nbsp;The subject of your response</td>
	<td width=50% $right_border><input type=text name=subject value=\"Re: ".clear($row_info['subject'])."\"></td></tr>");
output("<tr bgcolor=$_TEMPLATE[dark_background]><td width=50% $left_border valign=top>&nbsp;&nbsp;<b>Message:</b> (*)<br>
	&nbsp;&nbsp;Your ticket response</td>
	<td width=50% $right_border><textarea cols=30 rows=7 name=message></textarea></td></tr>");
output("<input type=hidden name=type value=ticket>");
output("<tr bgcolor=$_TEMPLATE[light_background]><td width=50% $left_border valign=top>&nbsp;&nbsp;<b>Status:</b> (*)<br>
	&nbsp;&nbsp;Set ticket status to:</td>
	<td width=50% $right_border><select name=status><option value='open'>Open</option><option value='closed'>Closed</option></select></td></tr>");

output("</table><br>
	<input type=submit value='Send Reply'> <input type=reset></form>");

# Page Output
# :: Create Page

$template->createPage();

?>