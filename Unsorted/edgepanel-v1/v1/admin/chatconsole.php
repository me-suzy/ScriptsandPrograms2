<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| chatconsole.php :: Admin live chat console                           |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*   $Id: chatconsole.php,v 1.00.0.1 05/11/2002 18:58:58 mark Exp $   */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(3);

//----------------------------------
# We're using a special template
# :: Print the top HTML/frameset

echo( implode( file("../includes/chat-header.inc") , " " ));

//----------------------------------
# That's all folks

?>