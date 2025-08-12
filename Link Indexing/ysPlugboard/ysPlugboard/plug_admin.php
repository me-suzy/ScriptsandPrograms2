<?php
// Yoursite.nu Plugboard 1.0
// Created by Linda - Please see http://www.yoursite.nu/mbforum.php?id=8 if you need help
// Do not redistribute this code

include ("plug_settings.php");

mysql_connect ($conf['mysql_host'], $conf['mysql_user'], $conf['mysql_pass']); 
mysql_select_db($conf['mysql_db']);

$areaname = "ysPlugboard Administration";

if ($_SERVER["PHP_AUTH_USER"] == "" || $_SERVER["PHP_AUTH_PW"] == "" || $_SERVER["PHP_AUTH_USER"] != $admin_username || $_SERVER["PHP_AUTH_PW"] != $admin_password) {
    header("HTTP/1.0 401 Unauthorized");
    header("WWW-Authenticate: Basic realm=\"$areaname\"");
    echo "<h1>Authorization Required.</h1>";
    die();
}
?>

<html>
<head>
<title>ysPlugboard Administration</title>
</head>
<body style="background-color:#F2F2F2; font-family: trebuchet MS, sans-serif; font-size:12px;">
<div style="background-color:#ffffff; border:2px solid orange; padding:10px; margin:0px auto; width:60%;">
<font color="orange"><b>ysPlugboard Administration</b></font><br><br>

<b>Current Buttons</b>
<blockquote>
<?php
if ($action=="delete") {
	$id = $_POST["id"]; 
			mysql_query("DELETE from plugboard WHERE plug_id = '$id'");
	echo "<font color=green>Button Successfully Deleted</font><br>";
	}


$sqlbuttons = mysql_query("SELECT * FROM plugboard ORDER BY plug_id DESC LIMIT $limit");
	while($outbuttons = mysql_fetch_array($sqlbuttons)) { 
		$pid = $outbuttons[plug_id];
		$url = $outbuttons[url];
		$ip = $outbuttons[ip];
		$button = $outbuttons[button];


	echo "<a href=\"$url\" target=\"_blank\"><img src=\"$button\" height=\"$button_height\" width=\"$button_width\" alt=\"$url\" border=\"0\" style=\"vertical-align:middle\"></a> $ip [<a href=\"plug_admin.php?action=delete&id=$pid\">Delete</a>]<br>";
	}
?>
</blockquote>
<b>Ban IPs</b>
<blockquote>
<form action="plug_admin.php?action=banip" method="post">
Ban an IP: <input type="text" name="banip"><input type="submit" value="Ban IP">
</form>
<?php
if ($action=="banip") {
	$banip = $_POST["banip"]; 
	mysql_query("INSERT INTO banned(ip) VALUES ('$banip')");
	echo "<font color=green>IP Successfully banned</font><br>";
	}
if ($action=="delip") {
			mysql_query("DELETE from banned WHERE id = '$id'");
	echo "<font color=green>Banned IP Successfully removed</font><br>";
}
$sqlips = mysql_query("SELECT * FROM banned");
	while($outips = mysql_fetch_array($sqlips)) { 
		$bannedip = $outips[ip];
		$bannedid = $outips[id];


	echo "$bannedip - [<a href=\"plug_admin.php?action=delip&id=$bannedid\">Remove</a>]<br>";
	}
?>
</blockquote>
</div>
</body>
</html>