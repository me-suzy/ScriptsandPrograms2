<?php

/******************************************************
 * CjOverkill version 2.0.1
 * © Kaloyan Olegov Georgiev
 * http://www.icefire.org/
 * spam@icefire.org
 * 
 * Please read the lisence before you start editing this script.
 * 
********************************************************/

include ("../cj-conf.inc.php");
include ("../cj-functions.inc.php");   
cjoverkill_connect();
 
include ("security.inc.php");

$tms="Password change";

$sql=@mysql_query("SELECT login FROM cjoverkill_settings");
$tmp=@mysql_fetch_array($sql);
extract($tmp);

if ($_POST["update"]!="") {
    $npassword=$_POST["npassword"];
    $npassword2=$_POST["npassword2"];
    $nlogin=$_POST["nlogin"];
    if (($npassword!=$npassword2 || strlen($npassword)>$maxpasswd || strlen($nlogin)>$maxlogin) && eregi($loginpcheck,$npassword) && eregi($loginpcheck,$nlogin)) { 
	$tms="Passwords not uptated because of this possible errors:<br>
	       Passwords do not match<br>
	       Password or Login values are too long<br>
	       Password or login values contain strange characters
	       ";
    }
    else {
	@mysql_query("UPDATE cjoverkill_settings SET login='$nlogin', passwd=PASSWORD('$npassword')") OR
	  print_error(mysql_error());
	setcookie("cjoverkill_admin", time()."|$nlogin|$npassword",time()+99999);
	$tms="Username and password updated";
    }
}
cjoverkill_disconnect();

echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
	<html>
	<head>
	<title>$tms</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link href=\"../cj-style.css\" rel=\"stylesheet\" type=\"text/css\">
	</head>
	<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#000000\" vlink=\"#000000\" alink=\"#000000\">
	<form action=\"changepass.php\" method=\"POST\">
	<div align=\"center\"><strong><font size=\"4\">$tms</font></strong><br><br>
	</div>
	<table width=\"350\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	<tr>
	<td><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">
	<tr>
	<td colspan=\"2\" class=\"toprows\">Change Username and Password</td>
	</tr>
	<tr class=\"normalrow\">
	<td align=\"left\">Username:</td>
	<td align=\"left\"> <input name=\"nlogin\" type=\"text\" id=\"nlogin\" size=\"30\" maxlength=\"250\" value=\"$login\"></td>
	</tr>
	<tr class=\"normalrow\">
	<td align=\"left\">Password:</td>
	<td align=\"left\"><input name=\"npassword\" type=\"password\" id=\"npassword\" size=\"30\" maxlength=\"250\"></td>
	</tr>
	<tr class=\"normalrow\">
	<td align=\"left\">Confirm Password:</td>
	<td align=\"left\"><input name=\"npassword2\" type=\"password\" id=\"npassword2\" size=\"30\" maxlength=\"250\"></td>
	</tr>
	<tr class=\"normalrow\">
	<td align=\"left\">&nbsp;</td>
	<td align=\"left\"><input name=\"update\" type=\"submit\" class=\"buttons\" id=\"changepass\" value=\"Update\"></td>
	</tr>
	</table></td>
	</tr>
	</table>
	</form>
	<p align=\"center\"><font size=\"2\"><a href=\"javascript:window.close()\">Close Window</a></font></p>
	</body>
	</html>
	");

?>
