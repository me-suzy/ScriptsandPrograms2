<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| deletefield.php :: Admin support field deletion script               |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*   $Id: deletefield.php,v 1.00.0.1 27/10/2002 12:17:32 mark Exp $   */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(2);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

# Check we have a field to remove

if(!isset($_SUBMIT['f'])) {
	
	header("Location: supportfields.php");
	
	exit();
	
}

# Delete the field

$db = new Database;

$db->Connect($CONF['dbname']);

$query = "ALTER TABLE `$CONF[table_prefix]tickets` DROP `$_SUBMIT[f]`";

$exc_fields = array("id","subject","priority","message","parent_id","datestarted","status","is_reply","category","user_id","admin_id");

if(!in_array($_SUBMIT['f'],$exc_fields)) {

	$result = $db->Query($query);
	
}

header("Location: supportfields.php");

?>