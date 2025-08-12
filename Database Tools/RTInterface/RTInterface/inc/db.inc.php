<?php


require("error.inc.php");

function db_connect()
{
	global $dbconnect, $dbhost, $dbusername, $dbuserpass, $dbname;

	if (!$dbconnect) $dbconnect = mysql_connect($dbhost, $dbusername, $dbuserpass);
	if (!$dbconnect) {
		return 0;
	} elseif (!mysql_select_db($dbname)) {
		return 0;
	} else {
		return $dbconnect;
	} // if

} // db_connect

?>