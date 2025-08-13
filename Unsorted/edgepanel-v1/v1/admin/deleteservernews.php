<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| deleteservernews.php :: Admin server news deletion script            |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/* $Id: deleteservernews.php,v 1.00.0.1 27/09/2002 17:07:40 mark Exp $*/

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(2);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

# If we don't have a news id, we're gonna redirect them!

if(!isset($_SUBMIT['id']) or !isset($_SUBMIT['sid'])) {
	
	# Bye bye!
	
	header("Location: servernews.php");
	
	exit();
	
}

# Database connection

$db = new Database;

$db->Connect($CONF['dbname']);

# If a form has been submitted, then we need to do the relevant action

if($_SUBMIT['delete'] == 1) {
	
	# Firstly, if the option is set, we need to save the
	# action that they selected
	
	if($_SUBMIT['rem'] == 1) {
		
		# Attempt to CHMOD the config file
		
		if(!is_writeable("../includes/conf.global.php")) {
			
			@chmod("../includes/conf.global.php","0777");
			
		}
		
		# Firstly backup the config file
		
		if(is_file("../includes/conf.global.bak")) {
			
			@unlink("../includes/conf.global.bak");
			
		}
		
		@copy("../includes/conf.global.php","../includes/conf.global.bak");
		
		# We need to save the option, we means we need to open the config file
		
		$contents = file("../includes/conf.global.php");
		
		# Replace the relevant part of the file
		
		$regex = "\$CONF\[\"sn_delete\"\].+=.+\".+\";";
		
		$replace = "\$CONF[\"sn_delete\"] = \"$_SUBMIT[action]\";\n";
		
		$i=0;
		
		foreach($contents as $line) {
			
			if(substr($line,0,18) == "\$CONF[\"sn_delete\"]") {
				
				$contents[$i] = $replace;
				
			}
			
			$i++;
			
		}
								
		# Now put the file back together
		
		unlink("../includes/conf.global.php");
	
		$fp = fopen("../includes/conf.global.php","w");
		
		fwrite($fp,implode($contents,""));
		
		fclose($fp);
		
	}
	
	# Process the form
	
	switch($_SUBMIT['action']) {
		
		case "all";
		
			$query = "DELETE FROM `$CONF[table_prefix]servernews`
		          	 WHERE id = '$_SUBMIT[id]'";
		
			$result = $db->Query($query);
		
			# All deleted, redirect
		
			header("Location: servernews.php?s=1");
		
			exit();
			
		break;
		
		case "unique"; # Delete only this server
	
			# This is more complex, as we need to remove this server
			# from the array of servers, AND check that if this is
			# the ONLY server, remove the news item from the server
		
			# First get the info of this news item
		
			$result = $db->Query("SELECT * FROM `$CONF[table_prefix]servernews`
                                        	    WHERE id = '$_SUBMIT[id]'");
		
			$row_info = $db->fetch_row($result);
		
			$current_servers = explode("::",$row_info['servers']); // Array of current servers
			
			# Now we're gonna build a new array of servers, excluding
			# this one
			
			$new_servers = array();
			
			foreach($current_servers as $server) {
			
				if($server != $_SUBMIT[sid]) {
				
					# This isn't the server we're deleting from,
					# so add it to the array
				
					$new_servers[] = $server;
				
				}
			
			}
		
			# Now, if the server array is empty, we're gonna remove the
			# news item completely, if not, we're gonna rebuild the array
			# of servers and update the row
		
			if(count($new_servers) == 0) {
			
				$result = $db->Query("DELETE FROM `$CONF[table_prefix]servernews`
			                      WHERE id = '$_SUBMIT[id]'");
			
				header("Location: servernews.php?s=1");
			
				exit();
			
			}
			else {
			
				$server_field = implode($new_servers,"::");
			
				$result = $db->Query("UPDATE `$CONF[table_prefix]servernews`
			                      SET servers = '$server_field'
			                      WHERE id = '$_SUBMIT[id]'");
			
				header("Location: servernews.php?s=1");
			
				exit();
			
			}
		
		break;
		
	}

}

# We're gonna see if an option is set for this, if not, we need to ask

switch($CONF['sn_delete']) {
	
	case ""; # No option set, we need to ask
	
		# First check if this news item only applies to one server
		# if so, delete it anyway.
		
		$result = $db->Query("SELECT * FROM `$CONF[table_prefix]servernews`
                                        WHERE id = '$_SUBMIT[id]'");

		$row_info = $db->fetch_row($result);
		
		if($row_info['servers'] == $_SUBMIT['sid']) {
			
			$result = $db->Query("DELETE FROM `$CONF[table_prefix]servernews`
		          WHERE id = '$_SUBMIT[id]'");
			
			header("Location: servernews.php?s=1");
			
			exit();
			
		}
		
		# Ask what they want to do
		
		output("<div class=heading>Delete News Item</div>The news item you are trying
		to delete is used on more than one server. Would you like to:<br><br>");
		
		output("<table width=100% cellpadding=0 cellspacing=0>
		<form action='$PHP_SELF' method='post'>
		<input type=hidden name=delete value=1>
		<input type=hidden name=id value=$_SUBMIT[id]>
		<input type=hidden name=sid value=$_SUBMIT[sid]>
		<tr><td width=25 height=30><input type=radio name=action value='all'></td><td>Delete this news
		item from all servers that it is currently on.</td></tr>
		<tr><td width=25  height=30><input type=radio name=action value='unique'></td><td>Delete this news
		item only from the server I selected.</td></tr>
		<tr><td colspan=2><hr color=$_TEMPLATE[border_color] size=1 noshade width=55% align=left></td></tr>
		<tr><td width=25 height=30><input type=checkbox name=rem value=1></td><td> Remember my decision</td></tr>");
		
		output("</table><br><input type=submit value='Delete News Item'>");
		
		$template->createPage();
		
		exit();

	
	break;
	
	case "all"; # Delete this news item completely
	
		$query = "DELETE FROM `$CONF[table_prefix]servernews`
		          WHERE id = '$_SUBMIT[id]'";
		
		$result = $db->Query($query);
		
		# All deleted, redirect
		
		header("Location: servernews.php?s=1");
		
		exit();
	
	break;
	
	case "unique"; # Delete only this server
	
		# This is more complex, as we need to remove this server
		# from the array of servers, AND check that if this is
		# the ONLY server, remove the news item from the server
		
		# First get the info of this news item
		
		$result = $db->Query("SELECT * FROM `$CONF[table_prefix]servernews`
                                        WHERE id = '$_SUBMIT[id]'");
		
		$row_info = $db->fetch_row($result);
		
		$current_servers = explode("::",$row_info['servers']); // Array of current servers
		
		# Now we're gonna build a new array of servers, excluding
		# this one
		
		$new_servers = array();
		
		foreach($current_servers as $server) {
			
			if($server != $_SUBMIT[sid]) {
				
				# This isn't the server we're deleting from,
				# so add it to the array
				
				$new_servers[] = $server;
				
			}
			
		}
		
		# Now, if the server array is empty, we're gonna remove the
		# news item completely, if not, we're gonna rebuild the array
		# of servers and update the row
		
		if(count($new_servers) == 0) {
			
			$result = $db->Query("DELETE FROM `$CONF[table_prefix]servernews`
			                      WHERE id = '$_SUBMIT[id]'");
			
			header("Location: servernews.php?s=1");
			
			exit();
			
		}
		else {
			
			$server_field = implode($new_servers,"::");
			
			$result = $db->Query("UPDATE `$CONF[table_prefix]servernews`
			                      SET servers = '$server_field'
			                      WHERE id = '$_SUBMIT[id]'");
			
			header("Location: servernews.php?s=1");
			
			exit();
			
		}
		
	break;
	
	
}

?>