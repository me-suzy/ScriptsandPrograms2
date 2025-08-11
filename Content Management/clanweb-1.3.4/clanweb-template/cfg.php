<?php

$db_prefix = 'cat_';
$dbname = 'cat_db';
$dbuser = 'dbuser';
$dbpw = 'dbpw';
$dbhost = 'localhost';

$conn_id = mysql_connect($dbhost, $dbuser, $dbpw);
mysql_select_db ($dbname); 

?>
