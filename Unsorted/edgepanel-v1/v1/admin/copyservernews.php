<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| copyservernews.php :: Admin copy server news script                  |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/* $Id: copyservernews.php,v 1.00.0.1 27/09/2002 21:56:00 mark Exp $  */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(2);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

# Start a new database connection

$db = new Database;

$db->Connect($CONF['dbname']);

# Retrieve news item information

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]servernews`
                      WHERE id = '$_SUBMIT[id]'");

$row_info = $db->fetch_row($result);

# If a form has been submitted, submit it!

if($_SUBMIT['copy'] == 1) {
	
	# Determine the action
	
	switch($_SUBMIT['action']) {
		
		case "spec";
		
			# First of all we need to retrive the current servers
			# that this news item is on
			
			$result = $db->Query("SELECT * FROM `$CONF[table_prefix]servernews`
			                      WHERE id = '$_SUBMIT[id]'");
			
			$row_info = $db->fetch_row($result);
			
			# Split up the servers into the $servers array
			
			$servers = explode("::",$row_info['servers']);
		
			# Copy to a specific server

			$result = $db->Query("SELECT * FROM `$CONF[table_prefix]servers`
                      			    ORDER BY `title` ASC");

			# Run through servers, add the right server to the $servers array
			
			while($row_info = $db->fetch_row($result)) {			
		
				if($_SUBMIT['server'] == $row_info['id']) {
			
					$servers[] = "$row_info[id]";
			
				}
		
			}
			
			# Rebuild server string
			
			$server_string = implode($servers,"::");
			
			# Execute query
			
			$query = "UPDATE `$CONF[table_prefix]servernews`
			          SET servers = '$server_string'
			          WHERE id = '$_SUBMIT[id]'";
			
			$result = $db->Query($query);
			
			header("Location: servernews.php?s=1");
			
			exit();
			
		break;
		
		case "all";
		
			# Copy to all servers
			
			$result = $db->Query("SELECT * FROM `$CONF[table_prefix]servers`
                      			    ORDER BY `title` ASC");
			
			$servers = array();
			
			while($row_info = $db->fetch_row($result)) {
		
				$servers[] = "$row_info[id]";
		
			}
			
			$server_string = implode($servers,"::");
			
			$query = "UPDATE `$CONF[table_prefix]servernews`
			          SET servers = '$server_string'
			          WHERE id = '$_SUBMIT[id]'";
			
			$result = $db->Query($query);
			
			header("Location: servernews.php?s=1");
			
			exit();

		break;
		
	}
	
}

# Output page heading

output("<div class=heading>Copy News Item</div>If you would like to copy
        this news item onto other servers, please select the type of copy
        that you would like to perform:<br><br>

        <b>Copying News Item:</b> $row_info[subject]<br><br>");

# We need to construct a list of servers

$servers = "<select name=server>";

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]servers`
                      ORDER BY `title` ASC");

while($row_info = $db->fetch_row($result)) {
	
	$servers .= "<option value=$row_info[id]>$row_info[title]</option>";
	
}

$servers .= "</select>";

output("<table width=100% cellpadding=0 cellspacing=0>
        <form action='$PHP_SELF' method='post'>
        <input type=hidden name=copy value=1>
        <input type=hidden name=id value=$_SUBMIT[id]>
        <tr><td width=25 height=30><input type=radio name=action value='spec'></td>
        <td>Copy to this server: $servers</td></tr>
        <tr><td width=25 height=30><input type=radio name=action value='all'></td>
        <td>Copy this news item to all other servers</td></tr>
        </table><br><input type=submit value='Copy News Item'></form>");


$template->createPage();

?>