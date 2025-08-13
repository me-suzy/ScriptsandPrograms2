<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| useradmins.php :: Admin user management script                       |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*   $Id: useradmins.php,v 1.00.0.1 16/10/2002 21:32:21 mark Exp $    */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(1);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

# Connect to database

$db = new Database;

$db->Connect($CONF['dbname']);

# We should have an id

if(empty($_SUBMIT['id'])) {
	
	header("Location: useradmins.php");
	
	exit();
	
}

# Check the admin has permission to do this
# before going any further

if($HTTP_COOKIE_VARS['admin_data']['2'] > 1) {
	
	header("Location: useradmins.php?e=1");
	
	exit();
	
}

# Remove the user

$query = "DELETE FROM `$CONF[table_prefix]admins`
	 WHERE id = '$_SUBMIT[id]'";

$result = $db->Query($query);

header("Location: useradmins.php");

exit();

?>