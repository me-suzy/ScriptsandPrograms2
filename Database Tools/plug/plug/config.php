<?php

$user = "[TO_ADD]";   // Your MYSQL username
$pw = "[TO ADD]";	          //  YOUR MYSQL password
$host = "localhost";	         // Your host, most of the time just enter localhost
$database = "[TO_ADD]";    // MYSQL Database
$maxdata = "30"; // Max Number of plugs on the Plugboard.
$website = "plug-world.net/plug"; // URL to plugboard please don't use http://www. and a slash at the end.
$face = "Tahoma"; // The Plug-Boards Font

$connect= mysql_connect($host,$user,$pw)
	or die ("Couldn't connect.");
$db = mysql_select_db($database,$connect)
	or die ("Couldn't select database.");
?>
