<?php

/*---------------------------------------------------------------------+
| User Support System, Integrated With Modernbill                      |
+----------------------------------------------------------------------+
| (C) Copyright 2002 Mark Carruth                                      |
+----------------------------------------------------------------------+
|                                                                      |
| editserver.php :: Admin server editing page                          |
|                                                                      |
+----------------------------------------------------------------------+
| Author: Mark Carruth (mcarruth@totalfreelance.com)                   |
+---------------------------------------------------------------------*/

/*   $Id: editserver.php,v 1.00.0.1 08/10/2002 19:05:31 mark Exp $    */

# Get Includes

require_once "../includes/functions.php";       # Functions Library
require_once "../includes/conf.global.php";     # Configuration Settings

# Authorise the administrator

authadmin(2);

# New template

$template = new Template;

$template->template = "../includes/admin.inc";

# Check we have a news ID, if not redirect

if(!isset($_SUBMIT['id'])) {
	
	header("Location: servers.php");
	
	exit();
	
}

# Attempt to update the database with the submitted data
	
$db = new Database;
	
$db->Connect($CONF['dbname']);
	
$query = "UPDATE `$CONF[table_prefix]servers` SET title = '".addslashes($_SUBMIT['title'])."',
                                                  ip = '$_SUBMIT[ip]',
				              type = '$_SUBMIT[type]',
					     mbuserid = '$_SUBMIT[mbuserid]',
					     web_port = '$_SUBMIT[web_port]',
					     ssh_port = '$_SUBMIT[ssh_port]',
					     telnet_port = '$_SUBMIT[telnet_port]',
					     ftp_port = '$_SUBMIT[ftp_port]',
					     smtp_port = '$_SUBMIT[smtp_port]',
					     pop3_port = '$_SUBMIT[pop3_port]',
					     mysql_port = '$_SUBMIT[mysql_port]'
                                                  WHERE id = '$_SUBMIT[id]'";
	          
$result = $db->Query($query);
	
if($result) {
		
	header("Location: servers.php?es=1");
	
	$template->createPage();

	exit();
		
}
else {
		
	header("Location: servers.php?es=0&edit=$_SUBMIT[edit]");
		
	$template->createPage();
		
	exit();
		
}	

?>