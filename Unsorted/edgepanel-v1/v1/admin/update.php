<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| defaults.php :: Admin default value control                          |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*    $Id: defaults.php,v 1.00.0.1 05/11/2002 19:18:03 mark Exp $     */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

//----------------------------------
# Authorise the administrator

authadmin(1);

//----------------------------------
# New template

$template = new Template;

$template->template = "../includes/admin.inc";

//----------------------------------
# Output page heading and content

output("<div class=heading>Check For Update</div>Please wait while we
	retrieve the latest version information from the server.<br><br>");

output("<b>Checking for update......</b><br><br>");

$fp = @fopen("http://totalfreelance.com/edgepanel/update_info.php?v=$CONF[version]", "r");

if(!$fp) {
	
	output("<font color=#990000><b>Error:</b> Failed to connect</font>");
	
}
else {
	
	while($data = fread($fp,1024)) {
		
		output($data);
		
	}
	
}

//----------------------------------

$template->createPage();

?>