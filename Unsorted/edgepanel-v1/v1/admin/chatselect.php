<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| chatselect.php :: Admin live chat main window - chat selection       |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*   $Id: chatselect.php,v 1.00.0.1 09/11/2002 20:18:51 mark Exp $    */

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
# Set admin status to logged in

$result = $db->Query("UPDATE `$CONF[table_prefix]admins`
		    SET in_chat = '1'
		    WHERE id = '".$HTTP_COOKIE_VARS['admin_data']['3']."'");

//----------------------------------
# We're using a special template
# :: Print the top HTML/frameset

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

echo("<div class=heading>Live Chat</div>You are now logged in to live chat.
      When you leave, please be sure to use the \"Exit Chat\" button, so your
      status is set to Offline.<br><br><a href='selectchat.php'>&raquo; Check For Chat
      Requests</a>");

//----------------------------------
# That's all folks

?>