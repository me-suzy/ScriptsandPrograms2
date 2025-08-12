<?php

/* Written by Gerben Schmidt, http://scripts.zomp.nl */

// edit these variables to match your MySQL database settings
$dbhost="localhost";
$dbname="database_name";
$dbuser="database_user";
$dbpass="password";

connectToDB();


/*----------------------------------------------------------------------------*/

/* Prefix for table names (handy if you want to have multiple zomplog installations in one database */

$prefix = "zomplog";


/*----------------------------------------------------------------------------*/

/* Don't change below */

// name of table for db in mysql database
$table = "$prefix" . "_db";

// name of table for comments in mysql database
$table_comments = "$prefix" . "_comments";

// name of table for user management in mysql database
$table_users = "$prefix" . "_users";

// name of table for categories in mysql database
$table_cat = "$prefix" . "_cat";

// name of table for settings in mysql database
$table_settings = "$prefix" . "_settings";

// name of table for moblog settings in mysql database
$table_moblog = "$prefix" . "_moblog";

// name of table for pages in mysql database
$table_pages = "$prefix" . "_pages";

// name of table for banned ip-adresses in mysql database
$table_banned = "$prefix" . "_banned";

// zomplog version
$version = "3.4";

?>