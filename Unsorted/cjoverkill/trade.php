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

include ("cj-conf.inc.php");
include ("cj-functions.inc.php");  
cjoverkill_connect();

$maxstr=250;
$maxicq=50;

$stime=localtime();
$thishour=$stime[2];

$sql=@mysql_query("SELECT * FROM cjoverkill_settings") OR 
  print_error(mysql_error());
$tmp=@mysql_fetch_array($sql);
extract($tmp);

$added="N";

if ($_POST["add"]!="" && $signup!=0) {
    if ($_POST["url"]=="") { 
	$tms[]="Enter an URL to trade with"; 
    }
    if ($_POST["email"]=="") { 
	$tms[]="Enter an email address"; 
    }
    else {
	$tmp_url=$_POST["url"];
	$tmp_email=$_POST["email"];
	$tmp_icq=$_POST["icq"];
	$tmp_name=$_POST["site_name"];
	$tmp_desc=$_POST["site_desc"];
	$tmp_passwd=$_POST["pass"];
	if (strlen($tmp_icq)>$maxicq || strlen($tmp_url)>$maxstr || strlen($tmp_email)>$maxstr || strlen($tmp_name)>$maxstr || strlen($tmp_desc)>$maxstr || strlen($tmp_pass)>maxstr){
	    if ($_SERVER["HTTP_X_FORWARDED_FOR"]){
		$proxy=$_SERVER["REMOTE_ADDR"];
		$ip=$_SERVER["HTTP_X_FORWARDED_FOR"];
	    }
	    else {
		$ip=$_SERVER["REMOTE_ADDR"];
		$proxy="";
	    }
	    $what="Possible overflow attempt on webmaster signup page";
	    @mysql_query("INSERT INTO cjoverkill_security (fecha,what,ip,proxy,hour) 
	      VALUES (NOW(), '$what', '$ip', '$proxy', '$thishour')") OR
	      print_error(mysql_error());
	    cjoverkill_disconnect();
	    print_error("Your values appear to be too long<BR>
			  Are you trying to hack me duhdah?
			  ");
	}
	$tmp_uri= parse_url($tmp_url);
	$domain=str_replace("www.","",$tmp_uri["host"]);
	$url="http://".$tmp_uri["host"].$tmp_uri["path"];
	if ($tmp_uri["query"]!=""){
	    $url=$url."?".$tmp_uri["query"];
	}
	if ($_POST["url"]!=$url || $domain == "") {
	    $tms[]="$_POST[url] is not valid url";
	}
	$sql=@mysql_query("SELECT domain FROM cjoverkill_blacklist WHERE 
			    domain='$domain' OR 
			    email='$tmp_email' OR 
			    icq='$tmp_icq'") OR
	  print_error(mysql_error());
	if (mysql_num_rows($sql)>0) { 
	    print_error("You are blacklisted on this site !!!"); 
	}
	$sql=@mysql_query("SELECT domain FROM cjoverkill_trades WHERE domain='$domain'") OR 
	  print_error(mysql_error());
	if (mysql_num_rows($sql)>0) { 
	    $tms[]="This site already exists in the trading database"; 
	}
	if (!isset($tms)) {
	    if ($signup==2){
		$signup=0;
	    }
	    if ($tmp_passwd==""){
		$tmp_passwd="NNN";
	    }
	    @mysql_query("INSERT INTO cjoverkill_trades 
			   (domain, url, site_name, site_desc, email, icq, return, min_p, max_p, max_px, max_clicks,
			    max_ip, status, passwd, max_ret) VALUES 
			   ('$domain', '$url', '$tmp_name', '$tmp_desc', '$tmp_email', '$tmp_icq', '$return',
			    '$min_p', '$max_p', '$max_px', '$max_clicks', '$max_ip', '$signup', '$tmp_passwd', '$max_ret')") OR
	      print_error(mysql_error());
	    @mysql_query("INSERT INTO cjoverkill_stats (trade_id) VALUES (LAST_INSERT_ID())") OR 
	      print_error(mysql_error());
	    @mysql_query("INSERT INTO cjoverkill_forces (trade_id) VALUES (LAST_INSERT_ID())") OR 
	      print_error(mysql_error());
	    $sql5=@mysql_query("SELECT trade_id FROM cjoverkill_trades WHERE domain='$domain'") OR
	      print_error(mysql_error());
	    $tmp5=@mysql_fetch_array($sql5);
	    extract($tmp5);
	    $added="Y";
	}
    }
}

cjoverkill_disconnect();

echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
	<html>
	<head>
	<title>$cjoverkill_version</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link href=\"cj-style.css\" rel=\"stylesheet\" type=\"text/css\">
	</head>
	<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#000000\" vlink=\"#000000\" alink=\"#000000\">
	");
if ($added=="Y"){
    echo ("<div align=\"center\"><font size=\"4\"><strong>Thank you for trading with $site_name<br>
	    <br>
	    Send Traffic To: $site_url<br>
	    Or To: $site_url?tid=$trade_id</strong></font><br></div>
	    ");
}
elseif (isset($tms)){
    echo ("<div align=\"center\"><font size=\"4\"><strong>No Way:<br>");
    for ($i=0; $i<sizeof($tms); $i++) {
	echo ("$tms[$i]<br>");
    }
    echo ("</strong></font></div>");
}
else {
    echo ("<table width=\"500\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	    <tr class=\"toprows\">
	    <td height=\"28\"><div align=\"center\"><strong><font size=\"4\">$cjoverkill_version Powered Site</a></font></strong></div></td>
	    </tr>
	    <tr>
	    <td height=\"150\" class=\"normalrow\"><strong>This Site is Powered By $cjoverkill_version<br>
	    A Free Traffic Trading Script with Anticheat and Security Features</strong><br>
	    <strong><a href=\"http://cjoverkill.icefire.org/\" target=\"_blank\">
	    Click Here To Get Your FREE Copy</a></strong><br>
	    </td>
	    </tr>
	    <tr>
	    <td class=\"normalrow\"><br><br><br><strong>
	    This script has very advanced anticheat and security system!<br>
	    If you are a cheater go away NOW!<br>
	    Sending lots of raw hits will not help you here!<br>
	    Sending lots of proxy hits will not help you here!<br>
	    Sending hitbot traffic will not help you here!<br>
	    Sending lots of crap country traffic will not help you here!<br>
	    Sending lots of HEAD requests will not help you here!<br>
	    <br>
	    If you think you are a hacker you should try with any other script<br>
	    All security violations and hacking attempts are logged (included IP and Proxy)<br>
	    If you feel you can 0wn th4 M47r1x then try with any other script but not this one<br>
	    If you still feel too 31337, then you can try your skills <a href=\"http://cjoverkill.icefire.org/\" target=\"_blank\">
	    Here</a><br>
	    </strong>
	    <br><br>
	    </td>
	    </tr>
	    <tr>
	    <td>
	    <form action=\"trade.php\" method=\"POST\">
	    <table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\" class=\"normalrow\">
	    <tr>
	    <td class=\"toprows\" colspan=\"2\"><font size=\"3\">Rules</font></td>
	    </tr>
	    <td colspan=\"2\">$rules<br><br>
	    <b><font size=\"3\">Send Traffic To: $site_url<br>
	    <b><font size=\"3\">Admin ICQ UIN: $admin_icq<br>
	    </td>
	    </tr>
	    ");
    if ($signup!=0){
	echo("<tr>
	    <td class=\"toprows\" colspan=\"2\"><font size=\"3\">Trade Info</font></td>
	    </tr>
	    <tr align=\"left\">
	    <td width=\"150\"><b>Site URL:</b></td>
	    <td width=\"350\"><input name=\"url\" type=\"text\" id=\"url\" size=\"30\" maxlength=\"250\"></td>
	    </tr>
	    <tr align=\"left\">
	    <td width=\"150\"><b>Site Name:</b></td>
	    <td width=\"350\"><input name=\"site_name\" type=\"text\" id=\"site_name\" size=\"30\" maxlength=\"250\"></td>
	    </tr>
	    <tr align=\"left\">
	    <td width=\"150\"><b>Site Description:</b></td>
	    <td width=\"350\"><input name=\"site_desc\" type=\"text\" id=\"site_desc\" size=\"30\" maxlength=\"250\"></td>
	    </tr>
	    <tr align=\"left\">
	    <td width=\"150\"><b>Your Email:</b></td>
	    <td width=\"350\"><input name=\"email\" type=\"text\" id=\"email\" size=\"30\" maxlength=\"250\"></td>
	    </tr>
	    <tr align=\"left\">
	    <td width=\"150\"><b>Your ICQ UIN:</b></td>
	    <td width=\"350\"><input name=\"icq\" type=\"text\" id=\"icq\" size=\"30\" maxlength=\"50\"></td>
	    </tr>
	    <tr align=\"left\">
	    <td width=\"150\"><b>Password To Check Stats:</b></td>
	    <td width=\"350\"><input name=\"pass\" type=\"text\" id=\"pass\" size=\"30\" maxlength=\"250\"></td>
	    </tr>
	    <tr align=\"left\">
	    <td width=\"150\">&nbsp;</td>
	    <td width=\"350\"><input name=\"add\" type=\"submit\" class=\"buttons\" value=\"Trade\"></td>
	    </tr>
	    ");
    }
    else{
	echo ("<tr>
		<td colspan=\"2\"><font size=\"3\"><br><br><b>Sorry, no automatic trades accepted.<br>Contact webmaster directly.<br>
		<br><br></b></font>
		</td>
		</tr>
		");
    }
    echo ("</table></td>
	    </tr>
	    </table>
	    </form>
	    </body>
	    </html>
	    ");
}

?>
