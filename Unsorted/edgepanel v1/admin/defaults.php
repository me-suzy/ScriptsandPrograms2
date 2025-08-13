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

# Authorise the administrator

authadmin(1);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

//----------------------------------
# Check for form submital

if($_SUBMIT['save'] == 1) {
	
	//----------------------------------
	# Open configuration file
	
	# Attempt to CHMOD the config file,
	# if it is unwriteable
		
	if(!is_writeable("../includes/conf.global.php")) {
			
		@chmod("../includes/conf.global.php","0777");
			
	}
		
	# Firstly backup the config file
		
	if(is_file("../includes/conf.global.bak")) {
			
		@unlink("../includes/conf.global.bak");
			
	}
		
	@copy("../includes/conf.global.php","../includes/conf.global.bak");
	
	//----------------------------------
	
	# We need to save the option, we means we need to open the config file
		
	$contents = file("../includes/conf.global.php");
		
	# Replace the relevant part of the file
			
	$replace = "\$CONF[\"sn_delete\"] = \"$_SUBMIT[sn_delete]\";\n";
	$replace1 = "\$CONF[\"db_cleanup\"] = \"$_SUBMIT[db_cleanup]\";\n";	
		
	$i=0;
		
	foreach($contents as $line) {
			
		if(substr($line,0,18) == "\$CONF[\"sn_delete\"]") {
				
			$contents[$i] = $replace;
				
		}
		
		if(substr($line,0,19) == "\$CONF[\"db_cleanup\"]") {
			
			$contents[$i] = $replace1;
			
		}
			
		$i++;
			
	}
								
	# Now put the file back together
		
	@unlink("../includes/conf.global.php");
	
	$fp = fopen("../includes/conf.global.php","w");
		
	fwrite($fp,implode($contents,""));
		
	fclose($fp);
	
	//----------------------------------
	# Print success message
	
	output("<div class=heading>Script Defaults</div>Please set the defaults
        		as you wish and click 'Save Changes'.<br><br>");
	
	output("<font color=#006633><b>Success:</b> Your script defaults have been saved.<br>");
	
	$template->createPage();
	
	exit();
	
}
//----------------------------------
# Print the page

output("<div class=heading>Script Defaults</div>Please set the defaults
        as you wish and click 'Save Changes'.<br><br>");

tableheading("Script Defaults");

output("<form action='$PHP_SELF' method='post'>
	<input type=hidden name=save value=1>");

switch($CONF["sn_delete"]) {
	
	case "";
	
		$node1 = "selected";
		
	break;
	
	case "all";
	
		$all = "selected";
		
	break;
	
	case "unique";
	
		$un1 = "selected";
		
	break;
	
}

output("<tr height=30 bgcolor=$_TEMPLATE[light_background]><Td width=50% $left_border>&nbsp;&nbsp;<b>Server News Deletion:</b></td>
	<td $right_border><select name=sn_delete width=20><option value='' $node1>No Default</option>
	<option value=all $all>Delete News
	Item From All Servers</option><option value=unique $un1>Delete
	Only From the Selected Server</option></select></td></tr>");

switch($CONF["db_cleanup"]) {
	
	case "";
	
		$node = "selected";
		
	break;
	
	case "obsoletes";
	
		$ob = "selected";
		
	break;
	
	case "unique";
	
		$un = "selected";
		
	break;
	
}

output("<tr height=30 bgcolor=$_TEMPLATE[dark_background]><Td width=50% $left_border>&nbsp;&nbsp;<b>Database Cleanup:</b></td>
	<td $right_border><select name=db_cleanup><option value='' $node>No Default</option>
	<option value=obsoletes $ob>Remove only obsolete items</option><option value=unique $un>Empty the
	entire database (excluding admins)</option></select></td></tr>");

output("</table><br><input type=submit value='Save Changes'></form>");

//----------------------------------

$template->createPage();

?>