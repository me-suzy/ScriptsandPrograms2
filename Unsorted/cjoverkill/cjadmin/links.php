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

$stime=localtime();
$thishour=$stime[2];
$bg[$thishour]="bgcolor=\"#CCCCCC\"";

$tms="Link report";

$sql=@mysql_query("SELECT * FROM cjoverkill_links ORDER BY cjlink ASC") OR 
  print_error(mysql_error());

cjoverkill_disconnect();

echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
	<html>
	<head>
	<title>$tms</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link href=\"../cj-style.css\" rel=\"stylesheet\" type=\"text/css\">
	</head>
	<body>
	<div align=\"center\"><strong><font size=\"4\">$tms<br>
	</font></strong><br>
	</div>
	<table width=\"700\" border=\"1\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\">
	<tr>
	<td><table width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\">
	<tr class=\"toprowssmall\">
	<td>Link</td>
	<td>00<br>
	-<br>
	01</td>
	<td>01<br>
        -<br>
	02</td>
	<td> 02<br>
	-<br>
	03</td>
	<td>03<br>
	-<br>
	04</td>
	<td>04<br>
	-<br>
	05</td>
	<td>05<br>
	-<br>
	06</td>
	<td>06<br>
	-<br>
	07</td>
	<td>07<br>
	-<br>
	08</td>
	<td>08<br>
	-<br>
	09</td>
	<td>09<br>
	-<br>
	10</td>
	<td>10<br>
	-<br>
	11</td>
	<td>11<br>
	-<br>
	12</td>
	<td>12<br>
	-<br>
	13</td>
	<td>13<br>
	-<br>
	14</td>
	<td>14<br>
	-<br>
	15</td>
	<td>15<br>
	-<br>
	16</td>
	<td>16<br>
	-<br>
	17</td>
	<td>17<br>
	-<br>
	18</td>
	<td>18<br>
	-<br>
	19</td>
	<td>19<br>
	-<br>
	20</td>
	<td>20<br>
	-<br>
	21</td>
	<td>21<br>
	-<br>
	22</td>
	<td>22<br>
	-<br>
	23</td>
	<td>23<br>
	-<br>
	00</td>
	<td>Total</td>
	</tr>
	");
if (@mysql_num_rows($sql)==0) {
    echo ("<tr class=\"normalrowsmall\">
	    <td colspan=\"26\"><font size=\"3\"><br><b>No Data</b><br><br></font></td>
	    </tr>
	    ");
}
else {
    while ($tmp_sql=@mysql_fetch_array($sql)) {
	extract($tmp_sql);
	$tot=$h0+$h1+$h2+$h3+$h4+$h5+$h6+$h7+$h8+$h9+$h10+$h11+$h12+$h13+$h14+$h15+$h16+$h17+$h18+$h19+$h20+$h21+$h22+$h23;
	echo ("<tr class=\"normalrowsmall\">
		<td align=\"left\">$cjlink</td>
		<td $bg[0]>$h0</td>
		<td $bg[1]>$h1</td>
		<td $bg[2]>$h2</td>
		<td $bg[3]>$h3</td>
		<td $bg[4]>$h4</td>
		<td $bg[5]>$h5</td>
		<td $bg[6]>$h6</td>
		<td $bg[7]>$h7</td>
		<td $bg[8]>$h8</td>
		<td $bg[9]>$h9</td>
		<td $bg[10]>$h10</td>
		<td $bg[11]>$h11</td>
		<td $bg[12]>$h12</td>
		<td $bg[13]>$h13</td>
		<td $bg[14]>$h14</td>
		<td $bg[15]>$h15</td>
		<td $bg[16]>$h16</td>
		<td $bg[17]>$h17</td>
		<td $bg[18]>$h18</td>
		<td $bg[19]>$h19</td>
		<td $bg[20]>$h20</td>
		<td $bg[21]>$h21</td>
		<td $bg[22]>$h22</td>
		<td $bg[23]>$h23</td>
		<td>$tot</td>
		</tr>
		");
    }
}
echo ("</table></td>
	</tr>
	</table> 
	<br>
	<div align=\"center\"><font size=\"2\">
	<a href=\"javascript:window.close()\">Close Window</a></font></div>
	</body>
	</html>
	");

?>
