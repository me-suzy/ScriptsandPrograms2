<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| deleteserver.php :: Admin server removal script                      |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*  $Id: deleteserver.php,v 1.00.0.1 20/09/2002 21:00:00 mark Exp $   */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(2);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

# Check we have a news script ID

if(empty($_SUBMIT['id'])) {
	
	header("Location: servers.php");
	
	exit();
	
}

# Remove the news item and redirect

$db = new Database;

$db->Connect($CONF['dbname']);

$result = $db->Query("DELETE FROM `$CONF[table_prefix]servers` WHERE id = '$_SUBMIT[id]'");

$db->Close();

header("Location: servers.php");

?>