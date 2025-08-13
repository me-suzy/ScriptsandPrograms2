<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| logout.php :: Admin logout page                                      |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*     $Id: logout.php,v 1.00.0.1 20/09/2002 17:46:44 mark Exp $      */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Just delete the cookies and redirect!

setcookie("admin_data[0]");
setcookie("admin_data[1]");
setcookie("admin_data[2]");
setcookie("admin_data[3]");

header("Location: login.php");

?>