<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| servernews.php ::  server news viewing script                        |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*    $Id: servernews.php,v 1.00.0.1 17/10s/2002 21:20:40 mark Exp $   */

# Get Includes

require_once "./includes/functions.php";       # Functions Library
require_once "./includes/conf.global.php";     # Configuration Settings

# New template

$template = new Template;

$template->template = "./includes/template.inc";

# Database connection

$db = new Database;

$db->Connect($CONF['dbname']);

# Check an id is present

if(empty($_SUBMIT['id'])) {
	
	# Print a list of servers to view news for
	
}

# Select news item

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]servernews` 
		    WHERE id = '$_SUBMIT[id]'");

$row_info = $db->fetch_row($result);

output("<div class=heading>$row_info[subject] :: ".
        date("m/d/y",$row_info['dateadded'])."</div><br>");
        
output("$row_info[message]");

output("<br><br><b>&raquo; <a href='serverstatus.php'>Select Another Server</a></b>");

$template->createPage();

?>