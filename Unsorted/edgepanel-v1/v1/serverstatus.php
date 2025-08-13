<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| serverstatus.php :: Displays the status of the server selected by    |
| the user                                                             | 
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*  $Id: serverstatus.php,v 1.00.0.1 17/10/2002 21:14:05 mark Exp $   */

# Get Includes

require_once "./includes/functions.php";        # Functions Library
require_once "./includes/conf.global.php";      # Configuration Settings

# Check the user is authorised

auth_user();

# Create a new template object

$template = new Template;

# Connect to the database

$db = new Database;

$db->Connect($CONF['dbname']);

# If a server has been selected, show it's details

if(isset($_SUBMIT['s']) && ($_SUBMIT['s'] != "")) {
			
	# Print out the server details
	
	$result = $db->Query("SELECT * FROM `$CONF[table_prefix]servers`
			    WHERE id = '$_SUBMIT[s]'");
	
	# Check this server exists
	
	if($db->num_rows($result) == 0) {
		
		header("Location: serverstatus.php");
		
		exit();
		
	}
	
	# Get server details
	
	$server_info = $db->fetch_row($result);
	
	# Set the error handler, so if an execution time
	# is exceeded, it displays a message, not an error
			
	$old_error_handler = set_error_handler("timeout_error");
	
	# Set the execution time to a high value
	
	ini_set("max_execution_time","10");
	
	# Print server details
	
	output("<div class=heading>Viewing Server: $server_info[title]</div><br>
	        Please wait while we try and determine the server status (<b>Note:</b> This
	        may take a few minutes):<br><br><table width=100% cellpadding=0 cellspacing=0>");
	
	# Initial check on the server, to see if it exists
	
	$check = fsockopen("$server_info[ip]", 80, $errno, $errstr, 2);
	
	if(!$check) {
		
		output("</table><font color=#990000><b>Error:</b></font> There was an error reaching 
			the server you are attempting to access,
	        		suggesting that the server is experiencing major difficulties. You can
	        		either:
	        		<br><br>&raquo; <a href='newticket.php'>Open A New Support Ticket</a><br>
	        		&raquo; <a href='serverstatus.php'>View Another Server's Status</a>");
		
		$template->createPage();
		
		exit();
		
	}
	
	# Create an array of services to be checked, in the format
	# SERVICE => PORT
	
	$services = array(
			"Web Services" => $server_info['web_port'],
			"SSH Services" => $server_info['ssh_port'],
			"Telnet"       => $server_info['telnet_port'],
			"FTP Services" => $server_info['ftp_port'],
			"SMTP (Email)" => $server_info['smtp_port'],
			"POP3 (Email)" => $server_info['pop3_port'],
			"MySQL"        => $server_info['mysql_port']
			);
			
	# For every services in the services array, check its status
	
	foreach($services as $service => $port) {
		
		# Check they want this port checked
		
		if($port != "0") {
		
			# Open socket connection
		
			$noc_con = fsockopen("$server_info[ip]", $port, $errno, $errstr, 2);

			# Print service name
		
			output("<tr height=25><td width=50%><b>${service}:</b></td>");
		
			# Check socket
		
			if(!$noc_con) {
											
				output("<td width=50%><span class=difficulties>Experiencing Difficulties
			        		</span></td></tr>");
			
			}
			else {
			
				# Running normally
			
				fclose($noc_con);
			
				output("<td width=50%><span class=normal>Running Normally</span></td></tr>");
			
			}
			
		}
		
	}	
		
	# Checked all services
	
	output("</table><br>If you are experiencing difficulties with this server, you can 
	<a href='newticket.php'>Open a Support Ticket</a> for further help.</a><br><br>
	<div class=heading>Server News</div><br>
	The following news items are applicable to this server:<ul>");
	
	# Print server news items
	
	$news = $db->Query("SELECT * FROM `$CONF[table_prefix]servernews` 
	                    WHERE servers LIKE '%::$_SUBMIT[s]' OR 
	                          servers LIKE '$_SUBMIT[s]::%' OR 
	                          servers LIKE '%::$_SUBMIT[s]::%' OR 
	                          servers LIKE '$_SUBMIT[s]' 
	                    ORDER BY `dateadded` DESC");
	
	while($row_info = $db->fetch_row($news)) {
		
		output("<li><a href='servernews.php?id=$row_info[id]'>$row_info[subject]</a></li>");
		
	}
	
	if($db->num_rows($news) == 0) {
		
		output("<li>There are no news items applicable to this server.</li>");
		
	}
	
	output("</ul>");
	
	output("<b>&raquo; <a href='serverstatus.php'>Select Another Server</a></b>");

	# All done, create page
	
	$template->createPage();
	
	exit();
	
}

# Print a list of servers

output("<div class=heading>Server Status</div><br>Please select the server
        that you would like to view the status of (<b>Note:</b> The page
        may take a while to load):");

output("<ul>");

# Select servers from database

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]servers`
                      ORDER BY `title` ASC");

if($db->num_rows($result) == 0) {
	
	output("<li>There are currently no servers on the system.</li>");
	
}

while($row_info = mysql_fetch_array($result)) {
	
	output("<li><a href='serverstatus.php?s=$row_info[id]'>$row_info[title]</a></li>");
	
}

output("</ul><a href='index.php'>&raquo; Return</a>");

$template->createPage();

?>