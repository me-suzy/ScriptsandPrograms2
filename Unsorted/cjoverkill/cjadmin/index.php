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

require ("../cj-functions.inc.php");
$loginpcheck="^[[:alnum:]]{1,}$"; // Login and password pattern. Edit if you know what you do 
$maxlogin=250; // Maximum login lenght. Edit if you know what you do 
$maxpasswd=250; // maximum password lenght. Edit if you know what you do 
$cookietime=3600; // Time before auto logout (in seconds). Increase this one if you want. 
$cookietime2=$cookietime;
$stime=localtime();
$thishour=$stime[2];
if ($_POST["login"]!="" && $_POST["passwd"]!="") {
    $tmp_login=$_POST["login"];
    $tmp_passwd=$_POST["passwd"];
    $login="";
    $passwd="";
    $passwd2="";
    require ("../cj-conf.inc.php");
    if (eregi($loginpcheck,$tmp_login) && eregi($loginpcheck,$tmp_passwd) && strlen($tmp_login)<=$maxlogin && strlen($tmp_passwd)<=$maxpasswd){
	cjoverkill_connect();
	$logon=@mysql_query("SELECT login, passwd, PASSWORD('$tmp_passwd') AS passwd2 FROM cjoverkill_settings") OR
	  print_error(mysql_error());
	$cj_row=@mysql_fetch_row($logon);
	$login=$cj_row[0];
	$passwd=$cj_row[1];
	$passwd2=$cj_row[2];
	cjoverkill_disconnect();
	if ($login==$tmp_login && $passwd==$passwd2){
	    setcookie("cjoverkill_admin",time()."|$tmp_login|$tmp_passwd",time()+999999);
	    header("Location: trades.php");
	    exit;
	}
	else {
	    cjoverkill_connect();
	    if ($_SERVER["HTTP_X_FORWARDED_FOR"]){ 
		$proxy=$_SERVER["REMOTE_ADDR"];
		$ip=$_SERVER["HTTP_X_FORWARDED_FOR"];
	    }
	    else {
		$ip=$_SERVER["REMOTE_ADDR"];
		$proxy="";
	    }
	    $what="Failed login attempt on admin login form";
	    @mysql_query("INSERT INTO cjoverkill_security (fecha,what,ip,proxy,hour) VALUES 
			   (NOW(), '$what', '$ip', '$proxy','$thishour')") OR
	      print_error(mysql_error());
	    cjoverkill_disconnect();
	    print_error("Wrong username or password");
	}
    }
    else {
	cjoverkill_connect();
	if ($_SERVER["HTTP_X_FORWARDED_FOR"]){
	    $proxy=$_SERVER["REMOTE_ADDR"];
	    $ip=$_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	else {
	    $ip=$_SERVER["REMOTE_ADDR"];
	    $proxy="";
	}
	$what="possible SQL injection attempt on admin login form";
	@mysql_query("INSERT INTO cjoverkill_security (fecha,what,ip,proxy,hour) 
	  VALUES (NOW(), '$what', '$ip', '$proxy', '$thishour')") OR
	  print_error(mysql_error());
	cjoverkill_disconnect();
	print_error("Login and password do not match the security criteria<BR>
		      Are you trying to hack me duhdah?
		      ");
    }
}

echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
	<html>
	<head>
	<title>CjOverkill Admin - Login</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link href=\"../cj-style.css\" rel=\"stylesheet\" type=\"text/css\">
	</head>
	<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#000000\" vlink=\"#000000\" alink=\"#000000\">
	<form action=\"index.php\" method=\"POST\">
	<table width=\"600\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	<tr>
	<td>
	<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
	<tr class=\"toprows\">
	<td><div align=\"center\"><strong><font size=\"4\">$cjoverkill_version</font></strong></div></td>
	</tr>
	<tr>
	<td class=\"normalrow\">&nbsp;</td>
	</tr>
	<tr> 
	<td><div align=\"center\">
	<p class=\"toprows\"><strong><font size=\"4\">Enter login and password below</font></strong></p>
	</div></td>
	</tr>
	<tr>
	<td class=\"normalrow\"><div align=\"center\"> 
	<table width=\"350\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\"> 
	<tr> 
	<td>&nbsp;</td>
	<td>&nbsp;</td> 
	</tr>
	<tr> 
	<td width=\"200\"><strong><font size=\"2\">Login:</font></strong></td>
	<td width=\"250\"><input name=\"login\" type=\"text\" id=\"login\" size=\"30\" maxlength=\"250\"></td>
	</tr>
	<tr> 
	<td width=\"200\"><strong><font size=\"2\">Password:</font></strong></td> 
	<td width=\"250\"><input name=\"passwd\" type=\"password\" id=\"passwd\" size=\"30\" maxlength=\"250\"></td> 
	</tr>
	<tr> 
	<td>&nbsp;</td>
	<td><input name=\"login2\" type=\"submit\" class=\"buttons\" id=\"login2\" value=\" Login \"></td> 
	</tr> 
	</table> 
	</div></td> 
	</tr> 
	<tr> 
	<td class=\"normalrow\">&nbsp;</td> 
	</tr> 
	</table>
	</form>
	</body> 
	</html>
	");
?>

