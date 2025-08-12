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

$ip_limit="5000";

if (!isset($_GET["id"]) || $_GET["id"]=="") { 
    print_error("You must select a trade for this"); 
}
$tid=$_GET["id"];
$sql=@mysql_query("SELECT domain FROM cjoverkill_trades WHERE trade_id=$tid") OR 
  print_error(mysql_error());
$tmp=@mysql_fetch_array($sql);
extract($tmp);
$sql=@mysql_query("SELECT referer,raw_in FROM cjoverkill_ref WHERE trade_id=$tid ORDER BY raw_in DESC,referer DESC LIMIT 0,$ip_limit") OR
  print_error(mysql_error());
cjoverkill_disconnect();
$tms="Referer Log for $domain";

echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
	<html>
	<head>
	<title>$tms</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link href=\"../cj-style.css\" rel=\"stylesheet\" type=\"text/css\">
	</head>
	<body bgcolor=\"#FFFFFF\" text=\"#000000\" link=\"#000000\" vlink=\"#000000\" alink=\"#000000\">
	<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\"> 
	<tr>
	<td colspan=\"4\" align=\"center\"><strong><font size=\"4\">
	$tms 
	</font></strong></td>
	</tr> 
	<tr>
	<td height=\"10\" colspan=\"4\">&nbsp;</td>
	</tr>
	<tr>
	<td colspan=\"4\"><font size=\"1\"><b><font size=\"2\">Top $ip_limit Referers</font></b></font></td>
	</tr>
	<tr>
	<td colspan=\"4\">&nbsp;</td> 
	</tr> 
	<tr>
	<tr class=\"toprows\">
	<td width=\"15%\">Hits</td>
	<td widt=\"85%\">Referer</td>
	</tr>
	");

while ($tmp=@mysql_fetch_array($sql)) {
    extract ($tmp);
    echo ("<tr align=\"left\">
	    <td><font size=\"2\">$raw_in</font></td>
	    <td><font size=\"2\"><a href=\"$referer\" target=\"_blank\">$referer</a></font></td>
	    </tr>
	    ");
}
echo("<tr>
	<td colspan=\"4\">&nbsp;</td>
	</tr>
	<tr>
	<td colspan=\"4\" align=\"center\"><font size=\"2\"><a href=\"javascript:window.close()\">Close Window</a></font></td>
	</tr>
	</table>
	</body>
	</html>
	");
?>
	
