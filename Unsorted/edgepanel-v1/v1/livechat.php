<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| livechat.php :: User script to check for an operator and provide     |
|                  a link for initation of support chat                |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*    $Id: livechat.php,v 1.00.0.1 03/11/2002 12:44:15 mark Exp $     */

# Get Includes

require_once "./includes/functions.php";        # Functions Library
require_once "./includes/conf.global.php";      # Configuration Settings

# Check the user is authorised

auth_user();

# Create a new template object

$template = new Template;

# Output header

output("<div class=heading>Live Chat</div><br>To initiate a live chat
	please click on the link below.<br><br>");

output("<script language=Javascript>
	function openConsole() {

		newWindow = window.open(\"userchatconsole.php\",\"ChatConsole\",\"WIDTH=500,HEIGHT=300,statusbar=no,menubar=no\");

	}
	</script>");

# Connect to database

$db = new Database;

$db->Connect($CONF['dbname']);

# Check there is an admin online

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]admins`
		    WHERE in_chat = '1'");

if($db->num_rows($result) == 0) {
	
	output("There are currently no operators available.");
	
}
else {
	
	output("<b><a href='javascript:openConsole()'>&raquo; Open Chat Window</a>");
	
}

output("<br><br><a href='index.php'>&raquo; Return</a>");

$template->createPage();

?>