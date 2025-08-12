<?php
		$dbhost = "localhost";
		$dbname = "estore";
		$dbuser = "root";
		$dbpasswd = "";
		$prefix = "";
		$allowdemo = "0";
		$demologin = "";
		$demopass = "";		
		if(!$db = @mysql_connect($dbhost, $dbuser, $dbpasswd)) die('<font size=+1>An Error Occurred</font><hr>Unable to connect to the database. <BR>Check $dbhost, $dbuser, and $dbpasswd in config.php.');
		if(!@mysql_select_db($dbname,$db)) die("<font size=+1>An Error Occurred</font><hr>Unable to find the database <b>$dbname</b> on your MySQL server.");
?>