<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| deletesupportcategory.php :: Admin support categories deletion       |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*$Id: deletesupportc---.php,v 1.00.0.1 09/10/2002 21:33:19 mark Exp $*/

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(2);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

# Connect to database

$db = new Database;

$db->Connect($CONF['dbname']);

# Check we have an id

if(!isset($_SUBMIT['id']) or ($_SUBMIT['id'] == "")) {
	
	# Bye Bye
	
	header("Location: supportcategories.php");
	
	exit();
	
}

# Run the query and redirect

$result = $db->Query("DELETE FROM `$CONF[table_prefix]categories`
		    WHERE id = '$_SUBMIT[id]'
		    OR parent_id = '$_SUBMIT[id]'");

header("Location: supportcategories.php");

?>