<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| selectchat.php :: Admin live chat main window - chat selection       |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*   $Id: selectchat.php,v 1.00.0.1 09/11/2002 20:18:51 mark Exp $    */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(3);

//----------------------------------
# Connect to database

$db = new Database;

$db->Connect($CONF["dbname"]);

//----------------------------------
# Output page header

echo(" <style type=\"text/css\">
<!--
BODY { font-family: Verdana; font-size: 11px; color: #383838;line-height: 20px}
td {  font-family: Verdana; font-size: 11px; color: #383838;line-height: 20px}
a {  color: #4F769E; text-decoration: none}
a:hover {  text-decoration: underline}
.heading { color: #4F769E;font-weight: bold}
INPUT {  font-family: Verdana; font-size: 11px; color: #383838;}
SELECT {  font-family: Verdana; font-size: 11px; color: #383838;}
TEXTAREA {  font-family: Verdana; font-size: 11px; color: #383838;}
.highlight { color: #EE9700;}
.unhighlight { }
-->

</style>");

echo(" <div class=heading>Select a Chat</div>The following are a list of chats
	that are currently open and without a member of the support staff:<br><br>");

//----------------------------------
# Get the time as of 1 minute ago
# ( so closed chats aren't included )
# 60 seconds = 1 minute

$cutoff = time() - 60;

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]livechats`
		    WHERE admin_id = '0'
		    AND lastactivity > '$cutoff'
		    AND closed = '0'");

echo("<ul>");

if($db->num_rows($result) == 0) {
	
	echo("<li>There are currently no open chats</li>");
	
}

while($row_info = $db->fetch_row($result)) {
	
	echo("<li><a href='openchat.php?id=$row_info[id]'>Chat No # $row_info[id]
	started ".date("m/d/y G:i:s",$row_info['datestarted'])."</a></li>");
	
}

echo("</ul><a href='selectchat.php'>&raquo; Refresh</a>");

?>