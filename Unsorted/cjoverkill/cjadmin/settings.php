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

if ($_POST["update"] != "") {
    $site_url=$_POST["site_url"];
    $site_name=$_POST["site_name"];
    $site_desc=$_POST["site_desc"];
    $admin_email=$_POST["admin_email"];
    $admin_icq=$_POST["admin_icq"];
    $return=$_POST["return"];
    $max_p=$_POST["max_p"];
    $min_p=$_POST["min_p"];
    $max_px=$_POST["max_px"];
    $max_clicks=$_POST["max_clicks"];
    $max_ip=$_POST["max_ip"];
    $max_ret=$_POST["max_ret"];
    $altout=$_POST["altout"];
    if ($altout==""){
	$altout="http://payload.icefire.org/cjoverkill.php";
    }
    $filter_url_default=$_POST["filter_url_default"];
    if ($filter_url_default==""){
	$filter_url_default="http://payload.icefire.org/cjoverkill.php";
    }
    $rules=$_POST["rules"];
    $min_uniq=$_POST["min_uniq"];
    $signup=$_POST["signup"];
    $px_enable=$_POST["px_enable"];
    $ip_enable=$_POST["ip_enable"];
    $clicks_enable=$_POST["clicks_enable"];
    $cheatstart=$_POST["cheatstart"];
    $trade_method=$_POST["trade_method"];
    @mysql_query("UPDATE cjoverkill_settings SET 
		   site_url='$site_url', site_name='$site_name', site_desc='$site_desc',
		   admin_email='$admin_email', admin_icq='$admin_icq',
		   return='$return', max_p='$max_p', min_p='$min_p',
		   max_px='$max_px', max_clicks='$max_clicks', max_ip='$max_ip',
		   altout='$altout', rules='$rules', min_uniq='$min_uniq', signup='$signup',
		   px_enable='$px_enable', ip_enable='$ip_enable', clicks_enable='$clicks_enable',
		   cheatstart='$cheatstart', max_ret='$max_ret', filter_url_default='$filter_url_default',
		   trade_method='$trade_method'") OR
      print_error(mysql_error());
    $tms="Settings updated";
}
else {
    $tms="Settings";
}

$sql=@mysql_query("SELECT * FROM cjoverkill_settings");
$tmp=@mysql_fetch_array($sql);
extract($tmp);
cjoverkill_disconnect();

echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
	<html>
	<head>
	<title>$tms</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link href=\"../cj-style.css\" rel=\"stylesheet\" type=\"text/css\">
	</head>
	<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#000000\" vlink=\"#000000\" alink=\"#000000\">
	<form action=\"settings.php\" method=\"POST\">
	<div align=\"center\"><strong><font size=\"4\">$tms<br>
	<br>
	</font></strong> </div>
	<table width=\"400\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	<tr>
	<td><table width=\"100%\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\" class=\"normalrow\">
	<tr class=\"toprows\">
	<td colspan=\"2\">Site Info</td>
	</tr> 
	<tr>
	<td align=\"left\">Site URL:</td>
	<td align=\"left\"><input name=\"site_url\" type=\"text\" size=\"25\" maxlength=\"250\" value=\"$site_url\"></td>
	</tr>
	<tr>
	<td align=\"left\">Site Name:</td>
	<td align=\"left\"><input name=\"site_name\" type=\"text\" size=\"25\" maxlength=\"250\" value=\"$site_name\"></td>
	</tr>
	<tr>
	<td align=\"left\">Site Description:</td>
	<td align=\"left\"><input name=\"site_desc\" type=\"text\" size=\"25\" maxlength=\"250\" value=\"$site_desc\"></td>
	</tr>
	<tr class=\"toprows\">
	<td colspan=\"2\">Webmaster Info</td>
	</tr>
	<tr>
	<td align=\"left\">Your Email:</td>
	<td align=\"left\"><input name=\"admin_email\" type=\"text\" size=\"25\" maxlength=\"250\" value=\"$admin_email\"></td>
	</tr>
	<tr>
	<td align=\"left\">Your ICQ UIN:</td>
	<td align=\"left\"><input name=\"admin_icq\" type=\"text\"  size=\"25\" maxlength=\"250\" value=\"$admin_icq\"></td>
	</tr>
	<tr class=\"toprows\">
	<td colspan=\"2\">Default Trade Settings</td>
	</tr>
	<tr>
	<td align=\"left\">Default Return Ratio:</td>
	<td align=\"left\"><input name=\"return\" type=\"text\" size=\"25\" maxlength=\"5\" value=\"$return\"></td>
	</tr>
	<tr>
	<td align=\"left\">Default Max Return %:</td>
	<td align=\"left\"><input name=\"max_ret\" type=\"text\" size=\"25\" maxlength=\"5\" value=\"$max_ret\"></td>
	</tr>
	<tr>
	<td align=\"left\">Default Max Prod:</td>
	<td align=\"left\"><input name=\"max_p\" type=\"text\" size=\"25\" maxlength=\"5\" value=\"$max_p\"></td>
	</tr>
	<tr>
	<td align=\"left\">Default Min Prod:</td>
	<td align=\"left\"><input name=\"min_p\" type=\"text\" size=\"25\" maxlength=\"5\" value=\"$min_p\"></td>
	</tr>
	<tr>
	<td align=\"left\">Default Max Proxy %:</td>
	<td align=\"left\"><input name=\"max_px\" type=\"text\" size=\"25\" maxlength=\"2\" value=\"$max_px\">%</td>
	</tr>
	<tr>
	<td align=\"left\">Default Max Repeated IPs:</td>
	<td align=\"left\"><input name=\"max_ip\" type=\"text\" size=\"25\" maxlength=\"2\" value=\"$max_ip\"></td>
	</tr>
	<tr>
	<td align=\"left\">Default Max Clicks:</td>
	<td align=\"left\"><input name=\"max_clicks\" type=\"text\" size=\"25\" maxlength=\"11\" value=\"$max_clicks\"></td>
	</tr>
	<tr>
	<td align=\"left\">Minimum Hits To Check For Cheats:</td>
	<td align=\"left\"><input name=\"cheatstart\" type=\"text\" size=\"25\" maxlength=\"11\" value=\"$cheatstart\"></td>
	</tr>
	<tr class=\"toprows\">
	<td colspan=\"2\">Trading Method</td>
	</tr>
	<tr>
	<td align=\"left\">Trading Method:</td>
	<td align=\"left\"><select name=\"trade_method\"><option value=\"1\" ");
if ($trade_method==1){
    echo ("selected");
}
echo (">Overkill</option>
	<option value=\"2\" ");
if ($trade_method==2){
    echo ("selected");
}
echo (">Pure Productivity</option>
	<option value=\"3\" ");
if ($trade_method==3){
    echo ("selected");
}
echo (">Uniques</option>
	</td>
	</tr>
	<tr class=\"toprows\">
	<td colspan=\"2\">Cheat Protections</td>
	</tr>
	<tr>
	<td align=\"left\">Proxy Protection:</td>
	<td align=\"left\"><select name=\"px_enable\"><option value=\"1\" ");
if ($px_enable==1){
    echo ("selected");
}
echo (">Enabled</option>\n
	<option value=\"0\" ");
if ($px_enable==0){
    echo ("selected");
}
echo (">Disabled</option>
	</td>
	</tr>
	<tr>
	<td align=\"left\">Repeated IP Protection:</td>
	<td align=\"left\"><select name=\"ip_enable\"><option value=\"1\" ");
if ($ip_enable==1){
    echo ("selected");
}
echo (">Enabled</option>\n
	<option value=\"0\" ");
if ($ip_enable==0){
    echo ("selected");
}
echo (">Disabled</option>
	</td>
	</tr>
	<tr>
	<td align=\"left\">Excesive Clicks Protection:</td>
	<td align=\"left\"><select name=\"clicks_enable\"><option value=\"1\" ");
if ($clicks_enable==1){
    echo ("selected");
}
echo (">Enabled</option>\n
	<option value=\"0\" ");
if ($clicks_enable==0){
    echo ("selected");
}
echo (">Disabled</option>
	</td>
	</tr>
	<tr class=\"toprows\">
	<td colspan=\"2\">Other Settings</td>
	</tr>
	<tr>
	<td align=\"left\">
	<a title=\"A trade will be disabled if it has not sent 'Minimum Hits In' the last 24 hours\">Minimum Hits In:</a></td>
	<td align=\"left\"><input name=\"min_uniq\" type=\"text\"  size=\"25\" maxlength=\"11\" value=\"$min_uniq\"></td>
	</tr>
	<tr>
	<td align=\"left\">Alternative Out URL:</td>
	<td align=\"left\"><input name=\"altout\" type=\"text\" size=\"25\" maxlength=\"250\" value=\"$altout\"></td>
	</tr>
	<tr>
	<td align=\"left\">Default filter URL:</td>
	<td align=\"left\"><input name=\"filter_url_default\" type=\"text\" size=\"25\" maxlength=\"250\" value=\"$filter_url_default\"></td>
	</tr>
	<td align=\"left\">Signup Page Status:</td>
	<td align=\"left\"><select name=\"signup\">
	<option value=\"1\" ");
if ($signup==1){
    echo ("selected");
}
echo (">Enabled - Normal</option>\n
	<option value=\"0\" ");
if ($signup==0){
    echo ("selected");
}
echo (">Disabled - No new trades</option>\n
	<option value=\"2\" ");
if ($signup==2) {
    echo ("selected");
}
echo (">Enabled- New Trades Auto Disabled</option>
	</td>
	</tr>
	<tr>
	<td align=\"left\">Rules:</td>
	<td align=\"left\"><textarea cols=\"25\" rows=\"10\" name=\"rules\">$rules</textarea></td>
	</tr>
	<tr>
	<td align=\"left\">&nbsp;</td>
	<td align=\"left\"><input name=\"update\" type=\"submit\" class=\"buttons\" value=\"Update Settings\"></td>
	</tr>
	</table></td>
	</tr> 
	</table>
	<p align=\"center\"><font size=\"2\"><a href=\"javascript:window.close()\">Close Window</a></font></p>
	</form>
	</body>
	</html>
	");

?>
