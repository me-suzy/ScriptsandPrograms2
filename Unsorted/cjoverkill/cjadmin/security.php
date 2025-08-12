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

switch ($order_by){
 case "ip":
    $sql=@mysql_query("SELECT * FROM cjoverkill_security ORDER BY 
			ip DESC, proxy DESC, what DESC, fecha DESC, hour DESC LIMIT 0,$ip_limit") OR
      print_error(mysql_error());
    break;
 case "proxy":
    $sql=@mysql_query("SELECT * FROM cjoverkill_security ORDER BY 
			proxy DESC, ip DESC, what DESC, fecha DESC, hour DESC LIMIT 0,$ip_limit") OR
      print_error(mysql_error());
    break;
 case "what":
    $sql=@mysql_query("SELECT * FROM cjoverkill_security ORDER BY
			what DESC, ip DESC, proxy DESC, fecha DESC, hour DESC LIMIT 0,$ip_limit") OR
      print_error(mysql_error());
    break;
 case "fecha":
    $sql=@mysql_query("SELECT * FROM cjoverkill_security ORDER BY
			fecha DESC, ip DESC, proxy DESC, what DESC, hour DESC LIMIT 0,$ip_limit") OR
      print_error(mysql_error());
    break;
 case "hour":
    $sql=@mysql_query("SELECT * FROM cjoverkill_security ORDER BY 
			hour DESC, ip DESC, proxy DESC, what DESC, fecha DESC, LIMIT 0,$ip_limit") OR
      print_error(mysql_error());
    break;
 default:
    $sql=@mysql_query("SELECT * FROM cjoverkill_security ORDER BY 
			ip DESC, proxy DESC, what DESC, fecha DESC, hour DESC LIMIT 0,$ip_limit") OR
      print_error(mysql_error());
    break;

    
}
cjoverkill_disconnect();

$tms="SECURITY WARNINGS";

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
	<td colspan=\"5\" align=\"center\"><strong><font size=\"4\">$tms</font></strong></td>
	</tr> 
	<tr>
	<td height=\"10\" colspan=\"4\">&nbsp;</td>
	</tr>
	<tr>
	<td colspan=\"5\"><font size=\"1\"><b><font size=\"2\">Last $ip_limit SECURITY WARNINGS</font></b></font></td>
	</tr>
	<tr>
	<td colspan=\"5\">&nbsp;</td> 
	</tr>
	<tr class=\"toprows\">
	<td width=\"20%\">IP</td>
	<td width=\"20%\">Proxy</td>
	<td width=\"20%\">Warning</td>
	<td width=\"20%\">Date</td>
	<td width=\"20%\">Hour</td>
	</tr>
	<tr align=\"center\">
	<td align=\"center\"><font size=\"2\"><a href=\"security.php?order_by=ip\" target=\"_self\">Order</a></font></td>
	<td align=\"center\"><font size=\"2\"><a href=\"security.php?order_by=proxy\" target=\"_self\">Order</a></font></td>
	<td align=\"center\"><font size=\"2\"><a href=\"security.php?order_by=what\" target=\"_self\">Order</a></font></td>
	<td align=\"center\"><font size=\"2\"><a href=\"security.php?order_by=fecha\" target=\"_self\">Order</a></font></td>
	<td align=\"center\"><font size=\"2\"><a href=\"security.php?order_by=hour\" target=\"_self\">Order</a></font></td>
	</tr>
	");
while ($tmp=@mysql_fetch_array($sql)) {
    extract ($tmp);
    echo ("<tr align=\"left\">
	    <td><font size=\"2\">$ip</font></td>
	    <td><font size=\"2\">$proxy</font></td>
	    <td><font size=\"2\">$what</font></td>
	    <td><font size=\"2\">$fecha</font></td>
	    <td><font size=\"2\">$hour</font></td>
	    </tr>
	    ");
}
echo("<tr>
       <td colspan=\"5\">&nbsp;</td>
       </tr>
       <tr>
       <td colspan=\"5\" align=\"center\"><font size=\"2\"><a href=\"javascript:window.close()\">Close Window</a></font></td>
       </tr>
       </table>
       </body>
       </html>
       ");
?>

	
