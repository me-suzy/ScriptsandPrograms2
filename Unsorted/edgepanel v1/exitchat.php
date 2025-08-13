<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| openchat.php :: User script to check for an operator and provide     |
|                  a link for initation of support chat                |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*    $Id: openchat.php,v 1.00.0.1 03/11/2002 12:44:15 mark Exp $     */

# Get Includes

require_once "./includes/functions.php";        # Functions Library
require_once "./includes/conf.global.php";      # Configuration Settings

# Check the user is authorised

auth_user();

# Create a new template object

$template = new Template;

# Connect to database

$db = new Database;

$db->Connect($CONF['dbname']);

# Chat id 

$chat_id = $HTTP_COOKIE_VARS['chat_id'];

# Clear cookie

setcookie("chat_id");

$db->Query("UPDATE `$CONF[table_prefix]livechats`
	   SET closed = '1'
	   WHERE id = '$chat_id'");

echo("<script language=javascript>
	parent.window.close();
	</script>");

?>