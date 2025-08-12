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
$order_by=$_GET["order_by"];
$sql=@mysql_query("SELECT domain FROM cjoverkill_trades WHERE trade_id=$tid") OR 
  print_error(mysql_error());
$tmp=@mysql_fetch_array($sql);
extract($tmp);
switch ($order_by){
 case "raw":
    $sql=@mysql_query("SELECT ip,proxy,raw_in,clicks,hour FROM cjoverkill_iplog_in WHERE trade_id=$tid 
			ORDER BY raw_in DESC,clicks DESC,ip DESC,proxy DESC,hour ASC LIMIT 0,$ip_limit") OR
      print_error(mysql_error());
    break;
 case "clicks":
    $sql=@mysql_query("SELECT ip,proxy,raw_in,clicks,hour FROM cjoverkill_iplog_in WHERE trade_id=$tid 
			ORDER BY clicks DESC,raw_in DESC,ip DESC,proxy DESC,hour ASC LIMIT 0,$ip_limit") OR
      print_error(mysql_error());
    break;
 case "hour":
    $sql=@mysql_query("SELECT ip,proxy,raw_in,clicks,hour FROM cjoverkill_iplog_in WHERE trade_id=$tid 
			ORDER BY hour ASC,raw_in DESC,clicks DESC,ip DESC,proxy DESC LIMIT 0,$ip_limit") OR
      print_error(mysql_error());
    break;
 case "ip":
    $sql=@mysql_query("SELECT ip,proxy,raw_in,clicks,hour FROM cjoverkill_iplog_in WHERE trade_id=$tid 
			ORDER BY ip DESC,raw_in DESC,clicks DESC,proxy DESC,hour ASC LIMIT 0,$ip_limit") OR
      print_error(mysql_error());
    break;
 case "proxy":
    $sql=@mysql_query("SELECT ip,proxy,raw_in,clicks,hour FROM cjoverkill_iplog_in WHERE trade_id=$tid 
			ORDER BY proxy DESC,raw_in DESC,clicks DESC,ip DESC,hour ASC LIMIT 0,$ip_limit") OR
      print_error(mysql_error());
    break;

 default:
    $sql=@mysql_query("SELECT ip,proxy,raw_in,clicks,hour FROM cjoverkill_iplog_in WHERE trade_id=$tid 
			ORDER BY raw_in DESC,clicks DESC,ip DESC,proxy DESC,hour ASC LIMIT 0,$ip_limit") OR
      print_error(mysql_error());
    break;
}

    
    
cjoverkill_disconnect();
$tms="IN IP Log for $domain";

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
	    <td colspan=\"5\"><font size=\"1\"><b><font size=\"2\">Top $ip_limit Incoming IPs</font></b></font></td>
	  </tr>
	  <tr>
	    <td colspan=\"5\">&nbsp;</td> 
	  </tr> 
	<tr class=\"toprows\">
	<td width=\"20%\">Raw IN</td>
	<td width=\"20%\">IP</td>
	<td width=\"20%\">Proxy</td>
	<td width=\"20%\">Clicks</td>
	<td width=\"20%\">Hour</td>
	</tr>
	<tr class=\"normalrows\">
	<td align=\"center\"><font size=\"2\"><a href=\"iplog-in.php?id=$tid&order_by=raw\" target=\"_self\">Order</a></font></td>
	<td align=\"center\"><font size=\"2\"><a href=\"iplog-in.php?id=$tid&order_by=ip\" target=\"_self\">Order</a></font></td>
	<td align=\"center\"><font size=\"2\"><a href=\"iplog-in.php?id=$tid&order_by=proxy\" target=\"_self\">Order</a></font></td>
	<td align=\"center\"><font size=\"2\"><a href=\"iplog-in.php?id=$tid&order_by=clicks\" target=\"_self\">Order</a></font></td>
	<td align=\"center\"><font size=\"2\"><a href=\"iplog-in.php?id=$tid&order_by=hour\" target=\"_self\">Order</a></font></td>
	</tr>
	");

while ($tmp=@mysql_fetch_array($sql)) {
    extract ($tmp);
    echo ("<tr align=\"center\">
	    <td>$raw_in</td>
	    <td>$ip</td>
	    <td>$proxy</td>
	    <td>$clicks</td>
	    <td>$hour</td>
	    </tr>");
}
echo ("<tr>
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
	
