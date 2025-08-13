<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| index.php :: Admin index page                                        |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*     $Id: index.php,v 1.00.0.1 20/09/2002 21:00:00 mark Exp $       */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(3);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

output("<div class=heading>Welcome To Your Admin Panel</div>Please select 
        an action that you would like to perform.<br><br>");

if($_SUBMIT['e'] == 1) {
	
	output("<font color=#990000><b>Error:</b> You have been redirected, as you do not have permission to
		access the page you just attempted to view.<br><br>");
	
}

$template->createPage();

?>