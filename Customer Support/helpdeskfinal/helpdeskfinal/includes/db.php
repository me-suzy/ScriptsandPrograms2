<?php
require_once "config.php";

// Connect to server and database
$db_conn = mysql_connect($dbsrv_host, $dbsrv_username, $dbsrv_password) or
				die("Cannot connect to mySQL server.");

mysql_select_db($dbsrv_name, $db_conn) or
	die("Cannot connect to database:<br>".mysql_error());
?>