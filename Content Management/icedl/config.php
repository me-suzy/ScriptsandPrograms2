<?php
// General Settings
$table_size = "290"; // The size of the table the downloads is in.
$username2 = "test"; // Your Username
$password2 = "test"; // Your Password
// MySQL Connection Settings
$username1 = "-"; // your MySQL USER NAME
$password1 = "-"; // your MySQL PASSWORD
$host = "localhost"; // your MySQL HOST
$database = "-"; // Your MySQL DATABASE 
mysql_connect($host,$username1,$password1) or die("There was an error connecting the database!<br>" . mysql_error());
mysql_select_db($database) or die("Cannot SELECT the DATABASE!<br>" . mysql_error());
?>