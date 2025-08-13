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

# Connect to database

$db = new Database;

$db->Connect($CONF['dbname']);

echo("<html>
<frameset rows=\"225,*\" frameborder=\"NO\" border=\"0\" framespacing=\"0\"> 
  <frame name=\"scriptFrame\" scrolling=\"NO\" noresize src=\"chatscript.php\" >
  <frame name=\"postFrame\" scrolling=\"NO\" noresize src=\"chatpost.php\" >
</frameset></html>");

?>