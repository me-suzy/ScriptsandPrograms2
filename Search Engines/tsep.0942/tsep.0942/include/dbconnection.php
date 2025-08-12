<?php
/**
* create a database connection (from Dreamweaver)
*
* @param string $db_server
* @param string $db_usrname
* @param string $db_pwd
* @author Dreamweaver
*
* following will be filled automatically by SubVersion!
* Do not change by hand!
*  $LastChangedDate: 2005-06-11 18:55:44 +0200 (Sa, 11 Jun 2005) $
*  @lastedited $LastChangedBy: toon $
*  $LastChangedRevision: 134 $
*
*/
	$dbopen= mysql_connect ($db_server, $db_usrname, $db_pwd);
	if (!$dbopen)
	die ("couldn't connect to MySql".mysql_error());
	mysql_select_db ($db_name,$dbopen)
	or die ("Couldn't Connect to table'$db_name'".mysql_error());

?>
