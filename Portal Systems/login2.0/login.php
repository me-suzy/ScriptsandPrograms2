<?php

include 'config.php';

ob_start();
echo "<center><font size\"2\" face=\"Tahoma\"> Network-13 Login System 2.0</font></center><br>";
echo "<font face=\"Arial\">Login to the members area.<br></font><br>";
echo "<form action=\"./login.php\" method=\"POST\">";
echo "<font face=\"Arial\">Name: </font> <br><input type=\"text\" name=\"username\" size=\"20\"><br>";
echo "<font face=\"Arial\">Pass: </font> <br><input type=\"password\" name=\"password\" size=\"20\"><br>";
echo "<input type=\"submit\" value=\"Login!\">";
echo "</form>";
echo "<br><font face=\"Times New Roman\"> </font><font face=\"Arial\">Dont have an account? Register <a href=\"register.php\">here!</a>";
echo "<br> Forgot your username &amp; password? Click <a href=\"reset.php\">here!</a>";
echo "<br> Reset your password? Click <a href=\"passwordreset.php?step=1\">here!</a></font>";

$connection = @mysql_connect($hostname, $user, $pass)
or die(mysql_error());
$dbs = @mysql_select_db($database, $connection) or
die(mysql_error());

$sql = "SELECT * FROM $userstable WHERE username = '$_POST[username]' AND password = '$_POST[password]'";
$result = @mysql_query($sql,$connection) or die(mysql_error());
$num = @mysql_num_rows($result);

if ($num != 0) {
$cookie_name = "auth";
$cookie_value = "fook!$_POST[username]";
$cookie_expire = "0";
$cookie_domain = $domain;

setcookie($cookie_name, $cookie_value, $cookie_expire, "/", $cookie_domain, 0);
header ("Location: http://" . $domain  . $directory . "index.php");

ob_end_flush();

exit;
}
?>