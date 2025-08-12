<?
$user = "admin";
$pass = "pass";

if ($action == "login") {
	if ((!$username) || (!$password)) {
		echo "Please enter a username and password.";
		exit();
	}
	elseif ($username != $user) {
		echo "Wrong username.";
		exit();
	}
	elseif ($password != $pass) {
		echo "Wrong password. ";
		exit();
	}
	elseif ((strcmp($password, $pass) == 0) && (strcmp($username, $user) == 0)) { 
		setcookie("logincookie[password]", $password, time()+86400);
		setcookie("logincookie[username]", $username, time()+86400);
	}
	else {
		echo "Invalid login.";
		exit();
	}
}
else {
	if (($logincookie[password] == "") || ($logincookie[username] == "")) {
		// Login Page
			echo "<form method=\"post\" action=\"?action=login\" name=\"login\">\n";
		echo "Username:<br><input type=\"text\" name=\"username\" /> <br /><br>\n";
		echo "Password:<br><input type=\"password\" name=\"password\" /> Password<br />\n";
		echo "<input type=\"submit\" value=\"Login\" />\n";
		echo "</form>";
		exit();
	}
	elseif ((strcmp($logincookie[password], $pass) == 0) && (strcmp($logincookie[username], $user) == 0)) { 
		setcookie("logincookie[password]", $logincookie[password], time()+86400);
		setcookie("logincookie[username]", $logincookie[username], time()+86400);
	}
	else {
		echo "Invalid login.";
		exit();
	}
} ?>
<?php 

include("config.php");

if($action == "ban") {
mysql_query("INSERT INTO banned VALUES ('$ip')");
echo "<font size='3' face='$face'><center>IP ($ip) Banned</center><br><br>";
}

if($action == "unban") {
mysql_query("DELETE FROM banned WHERE ip = '$ip'");
echo "<font size='3' face='$face'><center>IP ($ip) Unbanned</center><br><br>";
}



if ($action == "delete") {
mysql_query("DELETE FROM pb WHERE id = '$id'");
		echo "<meta http-equiv='refresh' content='0; URL=?'>";
	}



?> 

 <?php


echo "<center> <font size='2' face='$face'>Plug-Board Admin Panel<br><a href='http://www.plug-world.net'>Plug-Board from Plug-World</a></font><br><br>";


$query = "SELECT * FROM pb ORDER BY id DESC LIMIT $maxdata";
$result = mysql_query($query);

while ($plug = mysql_fetch_array($result)) {
		echo "<font size=\"2\" face=\"$face\"><a href=\"$plug[url]\" target=\"_blank\"><img src=\"$plug[button]\" width=\"88\" height=\"31\" border=\"0\" alt=\"Plug\" /></a><br> $plug[ip] <a href=\"?action=ban&ip=$plug[ip]&username=$u&password=$p\">[Ban]</a> | <a href=\"?action=unban&ip=$plug[ip]&username=$u&password=$p\">[Un-Ban]</a>   <br><a href=\"?action=delete&id=$plug[id]\">[Delete]</a><br><br><CENTER><FORM ACTION=\"?action=update\" METHOD=\"post\" NAME=\"plug\">
<font size=\"2\" face=\"Tahoma\">Button URL:<br>
</font><input type=\"hidden\" name=\"id\" value=\"$plug[id]\" />   
<INPUT NAME=\"button\" size=\"20\" style=\"font-family: Tahoma; font-size: 10pt; border-style: solid; border-width: 1; padding-left: 4; padding-right: 4; padding-top: 1; padding-bottom: 1\" value=\"$plug[button]\"><BR>
<font face=\"Tahoma\" size=\"2\">Website URL:<br>
</font> 
<INPUT NAME=\"url\" size=\"20\" style=\"font-family: Tahoma; font-size: 10pt; border-style: solid; border-width: 1; padding-left: 4; padding-right: 4; padding-top: 1; padding-bottom: 1\" value=\"$plug[url]\"><BR>
<INPUT TYPE=submit VALUE=\" Update! \" style=\"font-family: Tahoma; font-size: 10pt; border-style: solid; border-width: 1; padding-left: 4; padding-right: 4; padding-top: 1; padding-bottom: 1;  size=40\"> 
</FORM></CENTER><br><br></font>"; 
}

?>





<?php



if ($action == "update") {
mysql_query("UPDATE pb SET url = '$url', button = '$button' WHERE id = '$id'");
echo "<meta http-equiv='refresh' content='0; URL=?'>";
}


?>