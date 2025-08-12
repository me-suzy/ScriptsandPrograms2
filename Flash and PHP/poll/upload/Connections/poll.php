<?php


$hostname_poll = "localhost";//This is the host-You can leave it like this
$database_poll = "root";//This is the database name
$username_poll = "user";//This is the username associated with the DB
$password_poll = "password";//And ,ofcourse, the password


$poll = mysql_connect($hostname_poll, $username_poll, $password_poll) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_select_db($database_poll);
?>