<?php
//###########################
// LiveResponse - By Kayako Web Solutions
//
// Copyright (c) 2001 Kayako Web Solutions
// Unauthorized reproduction is not allowed
// License Agreement available in /doc dir.
//==============================
//                   www.kayako.com
//###########################

// Hostname of your database server
$db_host = "";
// Your database username
$db_user = "";
// database password
$db_pass = "";
// the database that LiveResponse will use
$db_database = "kayako_liveresponse";

// Your Database Server Type
// Currently only mySQL is supported.
$db_type = "mysql";

mysql_connect($db_host, $db_user, $db_pass);
mysql_selectdb($db_database);

?>