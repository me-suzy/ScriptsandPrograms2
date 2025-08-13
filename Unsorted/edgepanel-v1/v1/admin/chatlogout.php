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
# Set their status to gone

$result = $db->Query("UPDATE `$CONF[table_prefix]admins`
		    SET in_chat = '0'
		    WHERE id = '".$HTTP_COOKIE_VARS['admin_data']['3']."'");

echo("<script language=javascript>
	parent.window.close();
	</script>");

?>