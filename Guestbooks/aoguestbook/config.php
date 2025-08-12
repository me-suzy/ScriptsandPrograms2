<?php

$dbhost    = "localhost";
$dbuser    = "#####";
$dbpass    = "#####";
$dbname    = "#####";

$db = mysql_connect($dbhost, $dbuser, $dbpass)
or die("<b>Error:</b> Failed to connect to database");

mysql_select_db($dbname, $db)
or die("<b>Error:</b> Failed to select database");

?>