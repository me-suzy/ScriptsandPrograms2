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

$sql=@mysql_query("SELECT * FROM cjoverkill_daily ORDER BY fecha DESC") OR 
  print_error(mysql_error());

cjoverkill_disconnect();

echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
	<html>
	<head>
	<title>Daily Stats</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link href=\"../cj-style.css\" rel=\"stylesheet\" type=\"text/css\">
	</head>
	<body> 
	<div align=\"center\"><strong><font size=\"4\">Daily Stats<br>
	</font></strong></div>
	<table width=\"450\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	<tr>
	<td><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\">
	<tr class=\"toprows\">
	<td>Date</td>
	<td>Raw In</td>
	<td>Unique In</td>
	<td>Clicks</td>
	<td>Out</td>
	<td>Unique %</td>
	<td>Productivity</td>
	</tr>
	");
while ($tmp_sql=@mysql_fetch_array($sql)) {
    extract($tmp_sql);
    if ($uniq_in!=0) {
	$prod="".round($clicks/$uniq_in*100,2)."%";
	$unique="".round($uniq_in/$raw_in*100,2) . "%";
    }
    else { 
	$prod = "no hits"; 
	$unique = "no hits"; 
    }
    echo ("<tr class=\"normalrow\">
	    <td>$fecha</td>
	    <td>$raw_in</td>
	    <td>$uniq_in</td>
	    <td>$clicks</td>
	    <td>$uniq_out</td>
	    <td>$unique</td>
	    <td>$prod</td>
	    </tr>
	    ");
}
echo ("</table></td>
	</tr>
	</table><br><br>
	<div align=\"center\"><font size=\"2\"><a href=\"javascript:window.close()\">Close Window</a></font>
	</div> 
	</body>
	</html>
	");
?>
