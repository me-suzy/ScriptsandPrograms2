<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| index.php :: Displays login if user is not authenticated, then shows |
| the user their main options and the network news                     |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*     $Id: index.php,v 1.00.0.1 03/11/2002 12:44:15 mark Exp $       */

# Get Includes

require_once "./includes/functions.php";        # Functions Library
require_once "./includes/conf.global.php";      # Configuration Settings

# Check the user is authorised

auth_user();

# Create a new template object

$template = new Template;

output("<div class=heading>Welcome to $CONF[sitename] $CONF[script_name]
	</div><br>Please select an action you would like to perform:");

output("<ul>
         <li><a href='serverstatus.php'>View Server Status</a></li>
         <li><a href='newticket.php'>Open Support Ticket</a></li>
         <li><a href='viewtickets.php'>View Support Tickets</a></li>");

if($CONF['livechat'] == "on") {

	output("<li><a href='livechat.php'>Live Support Chat</a></li>");
	
}

if($CONF['userdriver'] == "database") {
	
	output("<li><a href='changepass.php'>Change Your Password</a></li>");
	
}

output("</ul>
        If you require further help, please refer to the links at the top
        of the page.");

$template->createPage();

?>