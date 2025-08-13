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

/*      $Id: news.php,v 0.20.0.1 17/10/2002 21:18:23 mark Exp $       */

# Get Includes

require_once "./includes/functions.php";        # Functions Library
require_once "./includes/conf.global.php";      # Configuration Settings

# Check we have a news id

if(!isset($_SUBMIT['id'])) {
	
	header("Location: index.php");
	
	exit();
	
}

# Start a new template object

$template = new Template;

# New database object

$db = new Database;

$db->Connect($CONF['dbname']);

# Select the news item

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]news` WHERE id = '$_SUBMIT[id]'");

# Get data

$row_info = $db->fetch_row($result);

output("<div class=heading>$row_info[title] :: ".
        date("m/d/y",$row_info['dateadded'])."</div><br>");

output("$row_info[description]");

output("<br><br>&raquo; <a href='index.php'>Return Home</a>");

$template->createPage();

?>