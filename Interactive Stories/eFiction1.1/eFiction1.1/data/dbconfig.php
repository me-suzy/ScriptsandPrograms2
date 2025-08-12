<?php

$dbhost = 'localhost';
$dbuser = 'databaseuser';
$dbpass = 'databasepassword';
$dbname = 'databasename';

$mysql_access = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname, $mysql_access);

?>