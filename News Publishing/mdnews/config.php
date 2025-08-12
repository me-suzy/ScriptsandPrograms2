<?php
/*
--------------------------------------------------------------
|MD News version 1                                           |
|(c)Matthew Dingley 2002                                     |
|For more scripts or assistance go to MD Web at:             |
|www.matthewdingley.co.uk                                    |
|For information on how to install see the readme            |
--------------------------------------------------------------
*/

//Set variables

//Your host - usually local host but check with your system administrator if your are not sure
$host = "localhost";

//The name of the database you are using
$databasename= "databasename";

//Your username for your database
$username = "username";

//Your password for your database
$password = "password";

//The number of news items you want shown per page in the news archive
$entriesToPage="4";

//The number of news items to show for the latest piece of news
$shownum = "3";

//Open database

$db = mysql_connect("$host", "$username", "$password");

mysql_select_db("$databasename", $db);

?>