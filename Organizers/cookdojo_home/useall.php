<?php
	include ('lib/global.php');
	include ('lib/config.php');
	include ('lib/mysqlfunc.php');
	
	$connection = mysql_connect ($mysql_hostname, $mysql_username, $mysql_password);
		if (!$connection) die ("Couldn't establish connection");

	mysql_select_db($mysql_database);

?>