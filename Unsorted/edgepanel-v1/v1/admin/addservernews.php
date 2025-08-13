<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| addservernews.php :: Admin add server news script                    |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*  $Id: addservernews.php,v 1.00.0.1 27/09/2002 21:18:49 mark Exp $  */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(2);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

# New database connection

$db = new Database;

$db->Connect($CONF['dbname']);

# First we need to get a list of all servers, check if they have been
# selected, and if so add them to the array

$servers = array();

$result = $db->Query("SELECT * FROM `$CONF[table_prefix]servers`
                      ORDER BY `title` ASC");

# First see if "all" has been selected

if($_SUBMIT['all'] == 1) {
	
	# Add all servers to the array
	
	while($row_info = $db->fetch_row($result)) {
		
		$servers[] = "$row_info[id]";
		
	}

}
else {
	
	# Add only selected servers
	
	while($row_info = $db->fetch_row($result)) {
		
		$string = "server_".$row_info['id'];
		
		if($_SUBMIT[$string] == 1) {
			
			$servers[] = "$row_info[id]";
			
		}
		
	}
	
}

# Check we have some servers

if(count($servers) == 0) {
	
	header("Location: servernews.php");
	
	exit();
	
}

# Construct the server string

$server_string = implode($servers,"::");

$query = "INSERT INTO `$CONF[table_prefix]servernews` VALUES (
                       '',
                       '".addslashes($_SUBMIT['subject'])."',
		     '".addslashes($_SUBMIT['message'])."',
		     '".time()."',
                       '$_SUBMIT[addedby]',
                       '$server_string')";

$result = $db->Query($query);

header("Location: servernews.php?a=1");

?>