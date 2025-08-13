<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| logout.php :: Logout page                                            |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*     $Id: logout.php,v 1.00.0.1 28/10/2002 21:09:13 mark Exp $      */

# Get Includes

require_once "./includes/functions.php";       # Functions Library
require_once "./includes/conf.global.php";     # Configuration Settings

# Just delete the cookies and redirect!

setcookie("user_data[0]");
setcookie("user_data[1]");
setcookie("user_data[2]");

header("Location: login.php");

?>