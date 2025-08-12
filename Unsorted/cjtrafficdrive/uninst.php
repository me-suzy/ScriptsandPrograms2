<?php
//Traffic-Drive CJ/TGP v5.14 free, uninst.php
if ($action != "uninstall") {
?>
<html>
<head>
<title>Traffic-Drive Uninstall</title>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
</head>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2><b>Traffic-Drive Uninstall</b></font>
</td></tr></table>
<form method=POST>
<p align=center>
Uninstall will delete tables <b>mset, tr, hr, sts, gal, bl, rf, ipfrom, ipto, hurl</b> from your database
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=submit name=action value=uninstall style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
}
else {
?>
<html>
<head>
<title>Traffic-Drive Uninstall</title>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
</head>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2><b>Traffic-Drive Uninstall</b></font>
</td></tr></table>
<p align=center>Please, wait ...</p>
<?php
ignore_user_abort(true);
include("./vars.php");
$link = mysql_connect ($hst, $usr , $psw);
mysql_select_db ($db);
?>		
<p align=center>Connected successfully<br>Delete tables ...</p>
<?php	
$q="drop table mset";$r=mysql_query($q);
$q="drop table tr";$r=mysql_query($q);
$q="drop table hr";$r=mysql_query($q);
$q="drop table sts";$r=mysql_query($q);
$q="drop table gal";$r=mysql_query($q);
$q="drop table bl";$r=mysql_query($q);
$q="drop table rf";$r=mysql_query($q);
$q="drop table ipfrom";$r=mysql_query($q);
$q="drop table ipto";$r=mysql_query($q);
$q="drop table hurl";$r=mysql_query($q);
?>
<p align=center><font size=4>Uninstall Done<br><br><b>Now manually delete Traffic-Drive files from your server</b></font></p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2><b>Traffic-Drive Uninstall</b></font>
</td></tr></table>
</body>
</html>
<?php
mysql_close ($link);
}
?>
