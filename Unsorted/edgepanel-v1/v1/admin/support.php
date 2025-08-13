<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| support.php :: Admin support page                                    |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*    $Id: support.php,v 0.20.0.1 26/10/2002 21:01:15 mark Exp $      */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(3);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

# Connect to the database


$db = new Database;

$db->Connect($CONF['dbname']);

# Check if the update form has been submitted

if($_SUBMIT['update'] == 1) {
	
	# Process the form
	
	switch($_SUBMIT['action']) {
		
		# Process the relevant action
		
		case "delete"; 
		
			# Delete all the selected tickets
			# Select all tickets, in order, from the database,
			# to see if they were selected
			
			$result = $db->Query("SELECT * FROM `$CONF[table_prefix]tickets` 
					    ORDER BY `datestarted` DESC");
			
			while($row_info = $db->fetch_row($result)) {
				
				$string = "select_" . $row_info['id'];
				
				if($_SUBMIT[$string] == 1) {
					
					$delete = $db->Query("DELETE FROM `$CONF[table_prefix]tickets`
							    WHERE id = '$row_info[id]'
							    OR parent_id = '$row_info[id]'");			
					
				}
				
			}					
			
			# All deleted - set value
			
			$completed=1;
			
		break;
		
		case "massreply"; 
		
			# To reply en masse, we're gonna build an array of the
			# selected servers, then redirect to the mass reply
			# script (massreply.php)
			
			$result = $db->Query("SELECT * FROM `$CONF[table_prefix]tickets` 
					    ORDER BY `datestarted` DESC");
			
			$servers = array();
			
			while($row_info = $db->fetch_row($result)) {
				
				$string = "select_" . $row_info['id'];
				
				if($_SUBMIT[$string] == 1) {
					
					$servers[] = "$row_info[id]";
					
				}
				
			}					
			
			# All deleted - set value
			
			$server_string = implode($servers,"::");
			
			# Redirect to massreply.php
			
			header("Location: massreply.php?s=$server_string");
			
			exit();
			
		break;
		
		case "hold";
		
			# Set status of all selected tickets to hold
			
			$result = $db->Query("SELECT * FROM `$CONF[table_prefix]tickets` 
					    ORDER BY `datestarted` DESC");
			
			$servers = array();
			
			while($row_info = $db->fetch_row($result)) {
				
				$string = "select_" . $row_info['id'];
				
				if($_SUBMIT[$string] == 1) {
					
					$update = $db->Query("UPDATE `$CONF[table_prefix]tickets`
							    SET status = 'hold'
							    WHERE id = '$row_info[id]'");
					
				}
				
			}
			
			# All servers updated
			
			$completed = 1;
			
		break;			
		
	}
		
}

output("<div class=heading>Answer Support Tickets</div>Welcome to the Help Desk. Below
	you will see 'Your Support Tickets', which is a summary of all the tickets
	that you have replied to and have been assigned to you. There is also a summar
	of all the 'Unassigned' that no-one has yet replied to.<br><br>");

if($completed == 1) {
	
	output("<font color=#00663><b>Success:</b> The operation completed successfully.<br><br></font>");
	
}

output("<table width=100% cellpadding=0 cellspacing=0>
         <tr><td colspan=2 style=\"border-bottom: 1px solid $_TEMPLATE[border_color]\">
          <table width=148 cellpadding=0 cellspacing=0><tr><td bgcolor=$_TEMPLATE[border_color]>
          <font color=white><b><center>Your Support Tickets</center></b></font>
          </td></tr></table>
         </td></tr>
	<form action='$PHP_SELF' method='post'>
         <input type=hidden value=1 name=update>");

# Retrieve all support tickets and print them

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]tickets`
		    WHERE is_reply = '0' 
		    AND admin_id = '".$HTTP_COOKIE_VARS["admin_data"]["3"]."'
		    AND status = 'open'
		    AND parent_id = '0'
		    ORDER BY `datestarted` DESC");

if($db->num_rows($result) == 0) {
	
	# Print a no support tickets message
	
	output("<tr bgcolor=$_TEMPLATE[light_background]><td colspan=2 style=\"
	border-bottom: 1px solid $_TEMPLATE[border_color];
	border-left: 1px solid $_TEMPLATE[border_color];
	border-right: 1px solid $_TEMPLATE[border_color];\">
	&nbsp;&nbsp; There are currently no tickets assigned to you.");
	
}

$indicator=1;

while($row_info = $db->fetch_row($result)) {
	
	# Set up the variable color/indicator
	
	$indicator == 0 ? $indicator = 1 : $indicator=0;
	$indicator == 0 ? $color = $_TEMPLATE['light_background'] : $color = $_TEMPLATE['dark_background'];
	
	# See who this is from
	
	if($row_info['is_reply'] == 1) {
	
		$person = "You";
	
	}
	else {
	
		$person = "User";
	
	}
	
	# Print out each row
	
	output("<tr bgcolor=$color><td height=23 width=85% style=\"
	border-left: 1px solid $_TEMPLATE[border_color];\">	
	&nbsp;&nbsp;<img src=../i/news-item.jpg align=center>
	<a href='answersupport.php?id=$row_info[id]'>$row_info[subject]</a>
	:: ".date("m/d/y G:i:s",$row_info['datestarted'])." by <i>$person</i></td>
	<td style=\"
	
	border-right: 1px solid $_TEMPLATE[border_color];\">
	<span class='' name=server_$row_info[id] id=server_$row_info[id]>
	<input type=checkbox name=select_$row_info[id] value=1 onClick=\"setColor('server_$row_info[id]')\"> 
	<b>Select</b></span></td>
	</tr>");
	
	//----------------------------------	
	# Show any dependents
	
	$deps = $db->Query("SELECT * FROM `$CONF[table_prefix]tickets`
			  WHERE parent_id = '$row_info[id]'");
	
	$num_rows = $db->num_rows($deps);
	
	$i=0;
	$bottom = "";
	
	while($dep_info = $db->fetch_row($deps)) {
		
		if($i == ($num_rows - 1)) {
			
			$bottom = "border-bottom: 1px solid $_TEMPLATE[border_color];";
			
		}
		
		# See who this is from
	
		if($dep_info['is_reply'] == 1) {
	
			$person = "You";
	
		}
		else {
	
			$person = "User";
	
		}
		
		output("<tr bgcolor=$color><td height=23 width=85% style=\"
		border-left: 1px solid $_TEMPLATE[border_color];
		$bottom\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='answersupport.php?id=$dep_info[id]'>
		$dep_info[subject]</a> :: ".date("m/d/y G:i:s",$dep_info['datestarted'])." by <i>$person</i>
		</td><Td style=\"
		$bottom
		border-right: 1px solid $_TEMPLATE[border_color];\"> 
		<span class='' name=server_$row_info[id] id=server_$row_info[id]>
		<input type=checkbox name=select_$dep_info[id] value=1 onClick=\"setColor('server_$row_info[id]')\"> 
		<b>Select</b></span>
		</td></tr>");
		
		$i++;
				
	}
	
}

output("<tr><td colspan=2 height=40><b>With Selected</b>: <input type=radio name=action value=massreply> Mass Reply 
                                      <input type=radio name=action value=delete> Delete
				  <!-- Hold feature for future use -->

				  <!-- <input type=radio name=action value=hold> Hold&nbsp;&nbsp; -->

                                      <input type=submit value='Go'></td></tr></table></form>");

//----------------------------------
# Now for the unassigned tickets

tableheading("Unassigned");

output("<form action='$PHP_SELF' method='post'>
         <input type=hidden value=1 name=update>");

//----------------------------------
# Retrieve all support tickets and print them

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]tickets`
		    WHERE is_reply = '0' 
		    AND admin_id = '0'
		    AND status = 'open'
		    ORDER BY `datestarted` DESC");

//----------------------------------

if($db->num_rows($result) == 0) {
	
	# Print a no support tickets message
	
	output("<tr bgcolor=$_TEMPLATE[light_background]><td colspan=2 style=\"
	border-bottom: 1px solid $_TEMPLATE[border_color];
	border-left: 1px solid $_TEMPLATE[border_color];
	border-right: 1px solid $_TEMPLATE[border_color];\">
	&nbsp;&nbsp; No tickets to display.");
	
}

//----------------------------------

$indicator=1;

while($row_info = $db->fetch_row($result)) {
	
	# Set up the variable color/indicator
	
	$indicator == 0 ? $indicator = 1 : $indicator=0;
	$indicator == 0 ? $color = $_TEMPLATE['light_background'] : $color = $_TEMPLATE['dark_background'];
	
	# Print out each row
	
	output("<tr bgcolor=$color><td height=23 width=85% style=\"
	border-bottom: 1px solid $_TEMPLATE[border_color];	
	border-left: 1px solid $_TEMPLATE[border_color];\">	
	&nbsp;&nbsp;<img src=../i/news-item.jpg align=center>
	<a href='answersupport.php?id=$row_info[id]'>$row_info[subject]</a>
	:: ".date("m/d/y G:i:s",$row_info['datestarted'])."</td>
	<td style=\"
	border-bottom: 1px solid $_TEMPLATE[border_color];
	border-right: 1px solid $_TEMPLATE[border_color];\">
	<span class='' name=server_$row_info[id] id=server_$row_info[id]>
	<input type=checkbox name=select_$row_info[id] value=1 onClick=\"setColor('server_$row_info[id]')\"> 
	<b>Select</b></span></td>
	</tr>");
	
}

//----------------------------------

output("<tr><td colspan=2 height=40><b>With Selected</b>: <input type=radio name=action value=massreply> Mass Reply 
                                      <input type=radio name=action value=delete> Delete
				  <!-- Hold feature for future use -->

				  <!-- <input type=radio name=action value=hold> Hold&nbsp;&nbsp; -->

                                      <input type=submit value='Go'></td></tr></table></form>");

//----------------------------------

output("<b>Additional Options:</b> &raquo; <a href='supportfields.php'>Manage Support Ticket Fields</a>");

$template->createPage();

?>