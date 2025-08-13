<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| massreply.php :: Admin mass support ticket reply script              |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*   $Id: massreply.php,v 0.20.0.1 20/09/2002 21:00:00 mark Exp $     */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(3);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

# Check we have a server list, if not, bye bye

if(!isset($_SUBMIT['s']) || ($_SUBMIT['s'] == "")) {
	
	# Bye Bye
	
	header("Location: support.php");
	
	exit();
	
}

# Connect to database

$db = new Database;

$db->Connect($CONF['dbname']);

# Check if the save news form has been submitted

if($_SUBMIT['savenews'] == 1) {
	
	# Save the news item, depending on the action
	
	switch($_SUBMIT['action']) {
		
		case "news";
		
			# Add a normal news item to the database
			
			$query = "INSERT INTO `$CONF[table_prefix]news` 
				VALUES 
				('',
			          '".addslashes($_SUBMIT['subject'])."',
				 '".addslashes($_SUBMIT['message'])."',
	          		 '".time()."',
	          		 '".addslashes($_SUBMIT['addedby'])."')";
			
			$result = $db->Query($query);
			
			header("Location: support.php?completed=1");
			
			exit();
			
		break;
		
		case "servernews";
		
			# Add server news items to the database
			
			# First we need to get a list of all servers, check if they have been
			# selected, and if so add them to the array

			$servers = array();

			$result = $db->Query("SELECT * FROM `$CONF[table_prefix]servers`
                      		             ORDER BY `title` ASC");
				
			if($_SUBMIT['all'] == 1) {
	
				# Add all servers to the array
	
				while($row_info = $db->fetch_row($result)) {
		
					$servers[] = "$row_info[id]";
		
				}

			}
			else {
	
				# Add only selected servers
	
				while($row_info = $db->fetch_row($result)) {
		
					$string = "server_".$row_info['id'];
		
					if($_SUBMIT[$string] == 1) {
							
						$servers[] = "$row_info[id]";
						
					}
		
				}
	
			}

			# Check we have some servers

			if(count($servers) == 0) {
	
				header("Location: support.php");
	
				exit();
	
			}
				
			# Construct the server string

			$server_string = implode($servers,"::");

			$query = "INSERT INTO `$CONF[table_prefix]servernews` VALUES (
                       		 '',
                       		 '".addslashes($_SUBMIT['subject'])."',
		     		 '".addslashes($_SUBMIT['message'])."',
		     		 '".time()."',
                       		 '$_SUBMIT[addedby]',
                       		 '$server_string')";

			$result = $db->Query($query);
				
			header("Location: support.php?completed=1");
				
			exit();
			
		break;
		
	}
	
}

# Check if a form has been submitted

if($_SUBMIT['send'] == 1) {
	
	# Process the form. First: split the ticket string
	
	$tickets = explode("::",$_SUBMIT['s']);
	
	# Check the ticket type is set, if not set it to ticket
	
	if(!isset($_SUBMIT['type']) or ($_SUBMIT['type'] == "")) {
		
		$_SUBMIT['type'] = "ticket";
		
	}
	
	# For each of the tickets, submit the response
	
	foreach($tickets as $ticket) {
		
		# Perform the relevant action
		
		switch($_SUBMIT['type']) {
			
			case "ticket"; 
			
				# We are submitting a support ticket, not an email
				
				$query = "INSERT INTO `$CONF[table_prefix]tickets`
					(id,subject,message,parent_id,datestarted,status,is_reply)
				         VALUES
				         ('',
					'".addslashes($_SUBMIT['subject'])."',
					'".addslashes($_SUBMIT['message'])."',
					'$ticket',
					'".time()."',
					'open',
					'1')";
				
				$result = $db->Query($query);
								
			break;
			
			case "email";
			
				# We are sending the response as an email	
				
			break;
			
		}
		
	}
	
	# Now, if news story is checked, we need to give them some options
	
	if($_SUBMIT['addnews'] == 1) {
		
		output("<div class=heading>Save News Item</div>You selected that you would like your
	        	        post saved as a news item. Please select how you would like the post to be saved:<br><br>");
		
		# Build the servers table
		
		$result = $db->Query("SELECT * FROM `servers` ORDER BY `title` ASC");

		$servers = "<div align=right><table width=100% cellpadding=2 cellspacing=2 align=right>";

		$indicator = 0;

		while($row_info = mysql_fetch_array($result)) {
	
			# Add each server into the table
	
         		$indicator == 0 ? $start = "<tr><td>" : $start = "<td>";
         		$indicator == 0 ? $end = "</td>" : $end = "</td></tr>";
         		$indicator == 0 ? $indicator = 1 : $indicator = 0;
         
         		$servers .= "$start <input type=checkbox name=server_$row_info[id] value='1'> ".substr($row_info[title],0,16).".. $end\n";
         
		}

		$servers .= "<tr><td><hr size=1 noshade color=$_TEMPLATE[border_color]></td><td> </td></tr>
		<tr><td colspan=2><input type=checkbox name=all value=1> Apply News Item To All Servers</td></tr></table>";
		
		output("<form action='$PHP_SELF' method='post'>
		        <input type=hidden name=savenews value=1>
		        <input type=hidden name=subject value=\"".clear($_SUBMIT['subject'])."\">
		        <input type=hidden name=message value=\"".clear($_SUBMIT['message'])."\">
		        <input type=hidden name=addedby value=\"".clear($_SUBMIT['addedby'])."\">
		        <input type=hidden name=s value=\"$_SUBMIT[s]\">
		        <table width=100% cellpadding=0 cellspacing=0>
		        <tr><td width=25 height=30><input type=radio name=action value='news'></td>
		        <td>Add this post as a normal news item</td></tr>
		
		<tr><td colspan=2><hr color=$_TEMPLATE[border_color] size=1 noshade width=100% align=left></td></tr>
		
		<tr><td width=5 height=30 valign=top><input type=radio name=action value='servernews'></td>
		<td><table width=100% cellpadding=0 cellspacing=0><tr><td width=45% valign=top>
		Add this post as a server news item on:</td>
		<td width=55%>$servers</td></tr></table>
		</td></tr>	
		
		</table><br><input type=submit value='Save News Item'></form>");
		
		$template->createPage();
		
		exit();
		
	}

	header("Location: support.php?completed=1");
	
	exit();
	
}

# Output the page heading

output("<div class=heading>Mass Reply</div>To reply to the tickets you have selected,
        please fill in the form below:<br><br>You are replying to:
        <ul>");

# Split the support ticket ids and print their titles

$tickets = explode("::",$_SUBMIT['s']);

foreach($tickets as $ticket) {
	
	$result = $db->Query("SELECT * FROM `$CONF[table_prefix]tickets`
			    WHERE id = '$ticket'");
	
	$row_info = $db->fetch_row($result);
	
	output("<li><b>$row_info[subject]</b></li>\n");
	
}

output("</ul>");

# Form validation javascript

output("<script language=javascript>
        function validateForm() {

                 if(document.theForm.subject.value == \"\") {

			alert(\"You must enter a value for the subject field.\");
			document.theForm.subject.focus();
			return false;

                 }

	        if(document.theForm.message.value == \"\") {

			alert(\"You must enter a value for the message field.\");
			document.theForm.message.focus();
			return false;

                 }

	}
         </script>");

tableheading("Mass Reply");

output("<form action='$PHP_SELF' method='post' name=theForm onSubmit='return validateForm()'>
        <input type=hidden name=send value=1>
        <input type=hidden name=s value='$_SUBMIT[s]'>");

output("<tr bgcolor=$_TEMPLATE[light_background]><td width=50% style=\"
        border-bottom: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color];\">
        &nbsp;&nbsp;<b>Subject:</b> (*)<br>&nbsp;&nbsp;The subject of your reply</td>
        <td width=50% style=\"
        border-bottom: 1px solid $_TEMPLATE[border_color];border-right: 1px solid $_TEMPLATE[border_color];\">
        <input type=text name=subject>
        </td>
        </tr>");

output("<tr bgcolor=$_TEMPLATE[dark_background]><td width=50% valign=top style=\"
        border-bottom: 1px solid $_TEMPLATE[border_color];border-left: 1px solid $_TEMPLATE[border_color];\">
        &nbsp;&nbsp;<b>Message:</b> (*)<br>&nbsp;&nbsp;The body of your reply</td>
        <td width=50% style=\"
        border-bottom: 1px solid $_TEMPLATE[border_color];border-right: 1px solid $_TEMPLATE[border_color];\">
        <textarea name=message cols=30 rows=7></textarea>
        </td>
        </tr>");

output("<input type=hidden name=type value=ticket>");

output("<tr><td colspan=2 height=35><input type=checkbox name=addnews value='1'> Automatically make this into a news/server news item</td></tr>");

output("</table><br><input type=submit value='Send Reply'></form>");

$template->createPage();

?>