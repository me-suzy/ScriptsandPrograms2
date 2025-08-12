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

if ($_POST["update"]!="") { 
    cjoverkill_toplist(1);
    $tms="Toplists Updated"; 
}
else { 
    $tms="Toplist Generation";
}
cjoverkill_disconnect();

echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
	<html>
	<html>
	<head>
	<title>Toplist</title>
	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
	<link href=\"../cj-style.css\" rel=\"stylesheet\" type=\"text/css\">
	</head>
	<body>
	<form name=\"form1\" method=\"POST\">
	<table width=\"450\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
	<tr>
	<td><div align=\"center\"><b><font size=\"4\">$tms</font></b></div></td>
	</tr>
	<tr>
	<td><font size=\"2\">Toplist templates in your &quot;toplist&quot; directory:</font></td>
	</tr>
	<tr>
	<td><font size=\"2\">
	
	");
$fdir=@opendir("../toplist") OR 
  print_error("Could not open \"toplist\"<BR>Make shure it exists and is writable");
while (($tfile=readdir($fdir))!==FALSE){
    if (substr($tfile, -6)==".templ") {
	$tfile2=str_replace(".templ",".html",$tfile);
	echo ("$tfile - <a href=\"../toplist/$tfile\" target=\"_blank\">View Template</a> - 
		<a href=\"../toplist/$tfile2\" target=\"_blank\">View Toplist</a> - 
		<a href=\"#\" onclick=\"document.form1.code.value='&lt;!--#include virtual=&quot;toplist/$tfile2&quot; --&gt;'\">
		Gen Code</a><br>
		");
    }
}
echo ("<center><br><input type=\"text\" name=\"code\" size=\"50\" value=\"Toplist include code\"><br>
	<input type=\"submit\" name=\"update\" value=\"Regenerate All Toplists\"><br>
	Insert the generated code above, in your page where you want the toplist displayed
	</center>
	</font></td>
	</tr>
	<tr> 
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td><div align=\"center\"><strong><font size=\"4\">Toplist Template Codes</font></strong></div></td>
	</tr>
	<tr>
	<td><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	<tr>
	<td width=\"40%\"><font size=\"2\">##sitedomain1##<br>
	##sitename1##<br>
	##sitedesc1##<br>
	##in1## <br>
	##link1##
	</font></td>
	<td width=\"60%\"><font size=\"2\">Domain of your number 1 trade.<br>
	Site name of your number 1 trade.<br>
	Site description of your number 1 trade.<br>
	Unique hits in for your number 1 trade.<br>
	Link to the trade with site name</font></td>
	</tr>
	</table></td>
	</tr>
	<tr>
	<td><font size=\"2\">Change &quot;##sitedomain1##&quot; to &quot;##sitedomain2##&quot; to display 
	the domain of your number 2 trade.<br>
	</tr>
	</table>
	</form>
	<div align=\"center\"><font size=\"2\"><a href=\"javascript:window.close()\">Close Window</a></font></div>
	</body>
	</html>
	");
	

?>
