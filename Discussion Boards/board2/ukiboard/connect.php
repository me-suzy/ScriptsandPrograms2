<?php

$dbhost = "localhost";		// adresa serveru	// server address
$dbusername = "";		// jméno uivatele	// user name
$dbname = "";			// jméno databáze	// database name
$dbpassword = ""; 		// heslo uivatele	// user password

$Con2 = mysql_connect($dbhost,$dbusername,$dbpassword);

if (!$Con2) {
  echo "Nepodaøilo se navázat spojení.\n";
  } else {
    MySQL_Select_DB("$dbname");
}

$table_prefix = "ub_";
$tblname_admin = $table_prefix.admin;
$tblname_config = $table_prefix.config;
$tblname_head = $table_prefix.head;
$tblname_topic = $table_prefix.topic;

?>