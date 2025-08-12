<?php
include 'config.php';
ob_start();
$connection = @mysql_connect($hostname, $user, $pass) or die(mysql_error());
$dbs = @mysql_select_db($database, $connection) or die(mysql_error());

$sql = "SELECT * FROM  $userstable WHERE username = '$_POST[username]' AND password = md5('$_POST[password]')";
$result = @mysql_query($sql,$connection) or die(mysql_error());
$num = @mysql_num_rows($result);

if ($num != 0) {
$cookie_name = "auth";
$cookie_value = "fook!$_POST[username]";
$cookie_expire = time()+7200;
$cookie_domain = $domain;

setcookie($cookie_name, $cookie_value, $cookie_expire, "/", $cookie_domain, 0);
header ("Location: http://" . $domain  . $directory . "index.php");
ob_end_flush();
exit;
}


include 'format.css';
echo "<br><br><br><br><br><br><br><br><br><br><br>";
echo "<table cellpadding=\"3\" class=\"boldb\" width=\"100%\" align=\"center\"><tr><td align=\"right\"><img align=\"absmiddle\" src=\"../images/taskdriverlogo.jpg\"> </td><td width=\"42%\" ><b><i>Task Tracking Software</i></b></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></table>";
echo "<table align=\"center\" class=\"black\" width=\"100%\"><tr><td colspan=\"9\"></td></tr>";
echo "<form action=\"./login.php\" method=\"POST\">";
echo "<tr><td align=\"right\"><b>Username:</b></td><td width=\"10%\"><input type=\"text\" name=\"username\" size=\"20\"></td><td bgcolor=\"#E6F0FF\"> <a href=\"register.php\">Create Account</a></td><td bgcolor=\"#EBF3FE\"></td><td bgcolor=\"#F1F6FD\"></td><td bgcolor=\"#F4F8FD\"></td><td bgcolor=\"#F6F9FD\"></td><td bgcolor=\"#F9FBFD\"></td><td bgcolor=\"#FDFDFD\"></td></tr>";
echo "<tr><td align=\"right\"><b>Password:</b></td><td width=\"10%\"><input type=\"password\" name=\"password\" size=\"20\"></td><td bgcolor=\"#E6F0FF\"> <a href=\"passwordreset.php?step=1\">Reset Password</a></td><td bgcolor=\"#EBF3FE\"></td><td bgcolor=\"#F1F6FD\"></td><td bgcolor=\"#F4F8FD\"></td><td bgcolor=\"#F6F9FD\"></td><td bgcolor=\"#F9FBFD\"></td><td bgcolor=\"#FDFDFD\"></td></tr>";
echo "<tr><td></td><td align=\"center\"><input type=\"submit\" value=\"Authenticate\"></td><td></td></tr>";
echo "</form>";
echo "</table>";

?>