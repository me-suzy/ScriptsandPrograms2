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

if ($_POST["update"]!=""){
    $return=$_POST["return"];
    $max_p=$_POST["max_p"];
    $min_p=$_POST["min_p"];
    $max_px=$_POST["max_px"];
    $max_clicks=$_POST["max_clicks"];
    $max_ip=$_POST["max_ip"];
    $max_ret=$_POST["max_ret"];
    @mysql_query("UPDATE cjoverkill_trades SET return='$return', max_p='$max_p', min_p='$min_p',
		   max_px='$max_px', max_clicks='$max_clicks', max_ip='$max_ip', max_ret='$max_ret'") OR
      print_error(mysql_error());
    @mysql_query("UPDATE cjoverkill_settings SET return='$return', max_p='$max_p', min_p='$min_p',
		   max_px='$max_px', max_clicks='$max_clicks', max_ip='$max_ip', max_ret='$max_ret'") OR
      print_error(mysql_error());
    $sql=@mysql_query("SELECT return, max_p, min_p, max_px, max_clicks,
			max_ip, max_ret FROM cjoverkill_settings") OR
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    $tms="Trades were mass edited";
}
else {
    $sql=@mysql_query("SELECT return, max_p, min_p, max_px, max_clicks,
			max_ip, max_ret FROM cjoverkill_settings") OR
      print_error(mysql_error());
    $tmp=@mysql_fetch_array($sql);
    extract($tmp);
    $tms="Mass Edit";
}
echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
	<html>
	<head>
	<title>$tms</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link href=\"../cj-style.css\" rel=\"stylesheet\" type=\"text/css\">
	</head>
	<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#000000\" vlink=\"#000000\" alink=\"#000000\">
	<form action=\"edit-mass.php\" method=\"POST\">
	<div align=\"center\"><strong><font size=\"4\">$tms<br>
	<br>
	</font></strong> </div>
	<table width=\"400\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	<tr>
	<td><table width=\"100%\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\" class=\"normalrow\">
	<tr class=\"toprows\">
	<td colspan=\"2\">Mass Edit</td>
	</tr>
	<tr>
	<td align=\"left\">Return Ratio:</td>
	<td align=\"left\"><input name=\"return\" type=\"text\" size=\"25\" maxlength=\"5\" value=\"$return\"></td>
	</tr>
	<tr>
	<td align=\"left\">Max Return %:</td>
	<td align=\"left\"><input name=\"max_ret\" type=\"text\" size=\"25\" maxlength=\"25\" value=\"$max_ret\">%</td>
	</tr>
	<tr>
	<td align=\"left\">Max Prod:</td>
	<td align=\"left\"><input name=\"max_p\" type=\"text\" size=\"25\" maxlength=\"5\" value=\"$max_p\"></td>
	</tr>
	<tr>
	<td align=\"left\">Min Prod:</td>
	<td align=\"left\"><input name=\"min_p\" type=\"text\" size=\"25\" maxlength=\"5\" value=\"$min_p\"></td>
	</tr>
	<tr>
	<td align=\"left\">Max Proxy %:</td>
	<td align=\"left\"><input name=\"max_px\" type=\"text\" size=\"25\" maxlength=\"2\" value=\"$max_px\">%</td>
	</tr>
	<td align=\"left\">Max Repeated IPs:</td>
	<td align=\"left\"><input name=\"max_ip\" type=\"text\" size=\"25\" maxlength=\"2\" value=\"$max_ip\"></td>
	</tr>
	<tr>
	<td align=\"left\">Max Clicks:</td>
	<td align=\"left\"><input name=\"max_clicks\" type=\"text\" size=\"25\" maxlength=\"11\" value=\"$max_clicks\"></td>
	</tr>
	<tr>
	<td align=\"left\">&nbsp;</td>
	<td align=\"left\"><input name=\"update\" type=\"submit\" class=\"buttons\" value=\"Mass Edit\"></td>
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
