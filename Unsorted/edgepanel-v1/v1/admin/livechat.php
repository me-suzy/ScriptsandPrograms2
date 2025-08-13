<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| livechat.php :: Admin live chat loader                               |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*    $Id: livechat.php,v 1.00.0.1 05/11/2002 18:58:58 mark Exp $     */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(3);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

//----------------------------------
# Connect to database

$db = new Database;

$db->Connect($CONF['dbname']);

//----------------------------------
# Output header

output("<script language=Javascript>
	function openConsole() {

		newWindow = window.open(\"chatconsole.php\",\"AdminChatConsole\",\"WIDTH=500,HEIGHT=300,statusbar=no,menubar=no\");

	}
	</script>");

//----------------------------------

output("<div class=heading>Live Chat</div>The live chat module can be used
	to provide support in real time. Please note, that the module can
	be a strain on server resources when run on older servers.<br><br>");

//----------------------------------
# Check live chat module is enabled

if($CONF['livechat'] == "off") {
	
	output("<font color=#990000><b>Error:</b> The live chat module is disabled.</font>
		<a href='config.php'>Enable It Here</a>");
	
	$template->createPage();
	
	exit();
	
}

//----------------------------------
# Link to launch console

output("<b><a href='javascript:openConsole()'>
	&raquo; Open Live Chat Console</a>");

$template->createPage();

?>