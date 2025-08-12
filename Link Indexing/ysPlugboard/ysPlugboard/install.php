<?php
// Yoursite.nu Plugboard 1.0
// Created by Linda - Please see http://www.yoursite.nu/mbforum.php?id=8 if you need help
// Do not redistribute this code
?>
<html>
<head>
<title>ysPlugboard Install</title>
</head>
<body style="background-color:#F2F2F2; font-family: trebuchet MS, sans-serif; font-size:12px;">
<div style="background-color:#ffffff; border:2px solid orange; padding:10px; margin:0px auto; width:60%;">
<font color="orange"><b>ysPlugboard Installation</b></font><br><br>
<?
include ("plug_settings.php");

if (!$conf['mysql_host'] || !$conf['mysql_user'] || !$conf['mysql_pass']) {
	echo "<br><br><font color=red><b>You did not enter your mysql host, username or password.  Please edit plug_settings.php and enter your details.</b></font>";
	exit;
}



// Do not edit anything below unless you know what you're doing!!!


// Connect to database

$connect = mysql_connect ($conf['mysql_host'], $conf['mysql_user'], $conf['mysql_pass']);

	if (!$connect) {
	   die("<br><br><font color=red><b>Unable to connect to MySQL, please check that the hostname, username and password in plug_settings.php are right.<br>You will need to create a mysql database in your control panel first and make sure you give the username permission to that database.</b></font>");
	}
	else {

		$connectdb = mysql_select_db($conf['mysql_db']);
		if (!$connectdb) {
		   die("	<br><br><font color=red><b>Unable to connect to Database, please check your database name in plug_settings.php.</b></font>");	
		}
		else {
			echo "<font color=green><b>Connected to database successfully.</font><br><br>";




			$create = mysql_query('CREATE TABLE plugboard(plug_id int(11) NOT NULL AUTO_INCREMENT, button VARCHAR(255), url VARCHAR(255), ip VARCHAR(25), PRIMARY KEY(plug_id))');
			$create = mysql_query('CREATE TABLE banned(id int(11) NOT NULL AUTO_INCREMENT, ip VARCHAR(30), PRIMARY KEY(id))');
			if (!$create) {
				echo "<font color=red>Problem creating table or tables already created.</font>";
			}
			else {
				$button = "http://www.yoursite.nu/images/ysbutton.gif";
				$url = "http://www.yoursite.nu";
				mysql_query("INSERT INTO plugboard(button, url) VALUES ('$button', '$url')");
				echo "<font color=green><b>Database Table created successfully.<br><br><font size=2>Congratulations, ysPlugboard has been successfully installed, you must now <font color=red>delete install.php</font> and then you may start using your plugboard.</font></font>";
			}













		}
	}


?>
</div>
</body>
</html>