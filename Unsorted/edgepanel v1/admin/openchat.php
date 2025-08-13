<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| livechat.php :: Admin live chat loader                               |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*    $Id: livechat.php,v 1.00.0.1 05/11/2002 18:58:58 mark Exp $     */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(3);

//----------------------------------
# Check we have a script ID

if(empty($_SUBMIT['id'])) {
	
	header("Location: selectchat.php");
	
	exit();
	
}

echo("<html>
<frameset rows=\"225,*\" frameborder=\"NO\" border=\"0\" framespacing=\"0\"> 
  <frame name=\"scriptFrame\" scrolling=\"NO\" noresize src=\"chatscript.php?id=$_SUBMIT[id]\" >
  <frame name=\"postFrame\" scrolling=\"NO\" noresize src=\"chatpost.php?id=$_SUBMIT[id]\" >
</frameset></html>");




?>