<?php
//Traffic-Drive CJ/TGP v5.14 free, w.php
include("vars.php");
include("wmvars.php");
$link = mysql_connect ($hst, $usr , $psw);
mysql_select_db ($db);
$q = "select * from mset where k='aHR0cDovL3d3dy5hbGxtYXh4eC5jb20vZnJlZQ=='";
$r = mysql_query($q);
$msetstr = mysql_fetch_array($r);
if (!$action) {
?>
<html>
<head>
<title><?php echo $msetstr["mtitle"]; ?> Traffic-Drive trade traffic</title>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;font-weight:bold;color:black;background-color:white;}
a { color:black;text-decoration:underline;font-weight:bold} 
-->
</style>
</head>
<body bgcolor=white text=black link=black vlink=black alink=black>
<?php
if ($swmst == 1) {
?>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=4><b><?php echo $msetstr["mtitle"]; ?></b><br>trade traffic form<br></font><font size=2>powered by <a href="http://www.traffic-drive.com/scripts">Traffic-Drive CJ/TGP v5.14 free</a>, get your traffic machine <a href="http://www.traffic-drive.com/scripts">here</a></font>
</td></tr></table><br>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=50% align=center>
<font size=2><?php include("wm1.txt"); ?></font>
</td></tr></table><br>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=50% align=center>
<font size=2>Please, fill out form and send all hits to<br><font size=4><?php echo $msetstr["murl"]; ?></font></font>
</td></tr></table><br>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=50% align=center>
<table align=center width=100% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=50% align=center>
<font size=2><b>Your Domain</b> (required)</font></td><td width=50%><input type="text" size="20" maxlength="100" name="dom" value="yourdomain.com" style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;"><br><font size=1>do not include "http://" and "www";<br>if you have third level domain,<br>enter all subdomains (some.yourdomain.com)</font>
</td></tr></table><br>
<table align=center width=100% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=50% align=center>
<font size=2><b>Url to Send Hits To</b> (required) </font></td><td width=50%><input type="text" size="40" maxlength="100" name="u" value="http://yourdomain.com/index.html" style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;">
</td></tr></table><br>
<table align=center width=100% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=50% align=center>
<font size=2><b>Your Site Name</b> (required) </font></td><td width=50%><input type="text" size="40" maxlength="100" name="tl" value="Your Site Name" style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;">
</td></tr></table><br>
<table align=center width=100% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=50% align=center>
<font size=2><b>Your Password</b> (required) </font></td><td width=50%><input type="text" size="20" maxlength="20" name="pw" value="" style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;">
</td></tr></table><br>
<?php
if ($elnwm==1) {
?>
<table align=center width=100% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2><b>Type of Your Site</b></font><br>
<input type="Radio" name="ln" value="a" <?php if ($dlnwm=="a") echo "checked"; ?>><font size=2>Mixed</font>
<input type="Radio" name="ln" value="p" <?php if ($dlnwm=="p") echo "checked"; ?>><font size=2>Pictures Only</font>
<input type="Radio" name="ln" value="m" <?php if ($dlnwm=="m") echo "checked"; ?>><font size=2>Movies Only</font>
</td></tr></table><br>
<?php
}

if ($gpwm=='n') {
?>
<table align=center width=100% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2><b>Category of Your Site</b></font>&nbsp;&nbsp;&nbsp;
<select name="category">
<?php
for ($i==1; $i<10; $i++) {
if ($gpwma[$i]) {
?>
<option value="<?php echo $i; ?>"><?php echo $gpwma[$i]; ?></option>
<?php
}
}
?>
</select>
</td></tr></table><br>
<?php
}
?>

<table align=center width=100% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2><b>Your ICQ# </b></font><input type="text" size="10" maxlength="20" name="icq" value="#ICQ" style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;">
<font size=2><b>Your Nick </b></font><input type="text" size="10" maxlength="20" name="nick" value="Nick" style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;">
<font size=2><b>Your Email </b></font><input type="text" size="20" maxlength="100" name="em" value="your@email.com" style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;">
</td></tr></table><br>
<table align=center width=100% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=submit name=action value=signup style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</td></tr></table>
</form>
<br>
<?php
}
else {
?>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=50% align=center>
<font size=2>webmaster signup form temporarily closed</font>
</td></tr></table><br>
<?php
}
if ($ewmst==1) {
?>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=4><b><?php echo $msetstr["mtitle"]; ?></b><br>check your stats<br></font><font size=2>powered by <a href="http://www.traffic-drive.com/scripts">Traffic-Drive CJ/TGP v5.14 free</a>, get your traffic machine <a href="http://www.traffic-drive.com/scripts">here</a></font>
</td></tr></table><br>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=50% align=center>
<table align=center width=100% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=50% align=center>
<font size=2><b>Your Domain</b></font></td><td width=50%><input type="text" size="20" maxlength="100" name="dom" value="" style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;">
</td></tr></table><br>
<table align=center width=100% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=50% align=center>
<font size=2><b>Your Password</b></font></td><td width=50%><input type="text" size="20" maxlength="20" name="pw" value="" style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;">
</td></tr></table><br>
<table align=center width=100% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=submit name=action value=stats style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</td></tr></table>
</form>
<?php
}
?>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>If you have any questions, just email at <a href="mailto:<?php echo $msetstr["memail"]; ?>?subject=Trade"><?php echo $msetstr["memail"]; ?></a> or icq at <b><?php echo $msetstr["micq"]; ?></b><br>
powered by <a href="http://www.traffic-drive.com/scripts">Traffic-Drive CJ/TGP v5.14 free</a></font>
</td></tr></table>
</body>
</html>
<?php
mysql_close($link);
exit;
}
if (($action=="signup") and ($swmst == 1)) {
if (($dom == '') or ($pw == '')) die ('Please go back and fill out all required fields');
$dom=addslashes($dom);$dom=eregi_replace(" ","",$dom);
$pw=addslashes($pw);$pw=eregi_replace(" ","",$pw);
$u=addslashes($u);$tl=addslashes($tl);$em=addslashes($em);$icq=addslashes($icq);$nick=addslashes($nick);$ln=addslashes($ln);
$q = "select * from bl where d = '$dom'";
$r = mysql_query($q);
if (mysql_num_rows($r) != 0)  die ('Your domain is blacklisted');
$q = "select * from tr where d = '$dom'";
$r = mysql_query($q);
if (mysql_num_rows($r) != 0)  die ('Domain already exists in the database') ;
$dr = $msetstr["dr"]; $dt = $msetstr["dt"]; $dtr = $msetstr["dtr"];$dotr = $msetstr["dotr"];$dhtr = $msetstr["dhtr"]; $dhotr = $msetstr["dhotr"];
$df = $msetstr["df"];
if (!$ln) $ln = $dlnwm;
if ($gpwm=='n') $dg=(int)$category;
$q = "insert into tr values ('$dom', '$u', '$tl', '$em', '$icq', '$nick', '$pw', '0', '0', '0', '0', '0', '$dr', '$dt', '$dtr', '$dotr', '0', '0', '0', '0', '$ln','0','0','0','0','0','$dhtr','$dhotr','1','$tlwm','')";	
$r = mysql_query($q);
$q = "insert into hr values ('$dom'";
for($i=0; $i<24; $i++) {
$q = $q . ",'0','0','0','0','0','$df','$dg'";
}
$q = $q . ")";
$r = mysql_query($q);
?>
<html>
<head>
<title><?php echo $msetstr["mtitle"]; ?> Traffic-Drive signup complete</title>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;font-weight:bold;color:black;background-color:white;}
a { color:black;text-decoration:underline;font-weight:bold} 
-->
</style>
</head>
<body bgcolor="white" text="black" link="black" vlink="black" alink="black">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=4><b><?php echo $msetstr["mtitle"]; ?></b><br>signup complete!<br></font><font size=2>powered by <a href="http://www.traffic-drive.com/scripts">Traffic-Drive CJ/TGP v5.14 free</a>, get your traffic machine <a href="http://www.traffic-drive.com/scripts">here</a></font>
</td></tr></table><br>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=50% align=center>
<font size=2><?php include("wm2.txt"); ?></font>
</td></tr></table><br>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=50% align=center>
<font size=2>send all hits to<br><font size=4><?php echo $msetstr["murl"]; ?></font></font>
</td></tr></table><br>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>If you have any questions, just email at <a href="mailto:<?php echo $msetstr["memail"]; ?>?subject=Trade"><?php echo $msetstr["memail"]; ?></a> or icq at <b><?php echo $msetstr["micq"]; ?></b><br>
powered by <a href="http://www.traffic-drive.com/scripts">Traffic-Drive CJ/TGP v5.14 free</a></font>
</td></tr></table>
</body>
</html>
<?php
mysql_close($link);
exit;
}
if (($action=="stats") and ($dom != "nourl") and ($ewmst == 1)) {
$dom=addslashes($dom);
$q = "select d from tr where d='$dom'";
$r = mysql_query($q);
if (mysql_num_rows($r)>0) {
$q = "select pw from tr where d='$dom'";
$r = mysql_query($q);
$trstr=mysql_fetch_array($r);
if ($trstr["pw"]==$pw) {
$tbw = 550+$rwmst*40+$uwmst*40+$cwmst*40+$gwmst*40+$pwmst*40;
?>
<html>
<head>
<title>Traffic-Drive Webmaster Stats</title>
<style type="text/css">
<!--
BODY{font-size:xx-small;font-family:arial;color:black;background-color:white;}
a { color:black;text-decoration:underline;font-weight:bold} 
-->
</style>
</head>
<body bgcolor=white text=black link=black>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=4><b><?php echo $msetstr["mtitle"]; ?></b><br>webmaster stats<br></font><font size=2>powered by <a href="http://www.traffic-drive.com/scripts">Traffic-Drive CJ/TGP v5.14 free</a>, get your traffic machine <a href="http://www.traffic-drive.com/scripts">here</a></font>
</td></tr></table><br>
<table align=center width=<?php echo $tbw; ?> bgcolor=silver><tr>
<td width=30 bgcolor=white align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>hr</font></td></tr></table></td>
<?php
if ($rwmst > 0) echo "<td width=40 bgcolor=#80FFFF align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>rin</font></td></tr></table></td>";
if ($uwmst > 0) echo "<td width=40 bgcolor=#80FFFF align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>uin</font></td></tr></table></td>";
if ($cwmst > 0) echo "<td width=40 bgcolor=#00FF80 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>cl</font></td></tr></table></td>";
if ($gwmst > 0) echo "<td width=40 bgcolor=#00FF80 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>gcl</font></td></tr></table></td>";
?>
<td width=40 bgcolor=#FFFF00 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>out</font></td></tr></table></td>
<?php
if ($pwmst > 0) echo "<td width=40 bgcolor=#FF80C0 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>pr%</font></td></tr></table></td>";
?>
<td>
<table align=center width=480 cellpadding=0 cellspacing=0>
<tr><td align=center><font face=arial size=2><b><?php echo $dom; ?></b> (last 23 hours)</font></td></tr>
</table>
</td>
</tr></table>
<?php
$maxv=1;
$hr=date("G");
for($i=0; $i<24; $i++) {
$q = "select * from hr where d='$dom'";
$r = mysql_query ($q);
$hrin=0; $huin=0; $hcl=0; $hgcl=0; $hout=0;
while ($row = mysql_fetch_array($r)) {
$hrin=$hrin+$row["rin$i"]; $huin=$huin+$row["uin$i"]; $hcl=$hcl+$row["cl$i"]; $hgcl=$hgcl+$row["gcl$i"]; $hout=$hout+$row["out$i"];
}
if ($hrin > $maxv) $maxv=$hrin;
if ($hout > $maxv) $maxv=$hout;
if ($hcl > $maxv) $maxv=$hcl;
if ($hgcl > $maxv) $maxv=$hgcl;
}
for($i=0; $i<24; $i++) {
$q = "select * from hr where d='$dom'";
$r = mysql_query ($q);
$hrin=0; $huin=0; $hcl=0; $hgcl=0; $hout=0;
while ($row = mysql_fetch_array($r)) {
$hrin=$hrin+$row["rin$i"]; $huin=$huin+$row["uin$i"]; $hcl=$hcl+$row["cl$i"]; $hgcl=$hgcl+$row["gcl$i"]; $hout=$hout+$row["out$i"];
}
if ($hrin == 0) { $hpr=0; } else { $hpr=ceil($hcl/$hrin*100); }
if ($i==$hr) $hcolor="red"; else $hcolor="white";
?>
<table align=center width=<?php echo $tbw; ?> bgcolor=silver><tr>
<td width=30 bgcolor=<?php echo $hcolor; ?> align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1><?php echo $i; ?></font></td></tr></table></td>
<?php
if ($rwmst > 0) echo "<td width=40 bgcolor=#80FFFF align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>".$hrin."</font></td></tr></table></td>";
if ($uwmst > 0) echo "<td width=40 bgcolor=#80FFFF align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>".$huin."</font></td></tr></table></td>";
if ($cwmst > 0) echo "<td width=40 bgcolor=#00FF80 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>".$hcl."</font></td></tr></table></td>";
if ($gwmst > 0) echo "<td width=40 bgcolor=#00FF80 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>".$hgcl."</font></td></tr></table></td>";
?>
<td width=40 bgcolor=#FFFF00 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1><?php echo $hout; ?></font></td></tr></table></td>
<?php
if ($pwmst > 0) echo "<td width=40 bgcolor=#FF80C0 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>".$hpr."</font></td></tr></table></td>";
?>
<td>
<?php
if ($rwmst > 0) {
?>
<table align=center width=480 cellpadding=0 cellspacing=0>
<tr><td height=3 width=<?php echo ceil($hrin*480/$maxv); ?> bgcolor="#80FFFF"></td><td width=<?php echo 480-ceil($hrin*480/$maxv); ?> bgcolor=black></td></tr>
</table>
<?php
}
if ($uwmst > 0) {
?>
<table align=center width=480 cellpadding=0 cellspacing=0>
<tr><td height=3 width=<?php echo ceil($huin*480/$maxv); ?> bgcolor="#80FFFF"></td><td width=<?php echo 480-ceil($huin*480/$maxv); ?> bgcolor=black></td></tr>
</table>
<?php
}
if ($cwmst > 0) {
?>
<table align=center width=480 cellpadding=0 cellspacing=0>
<tr><td height=3 width=<?php echo ceil($hcl*480/$maxv); ?> bgcolor="#00FF80"></td><td width=<?php echo 480-ceil($hcl*480/$maxv); ?> bgcolor=black></td></tr>
</table>
<?php
}
if ($gwmst > 0) {
?>
<table align=center width=480 cellpadding=0 cellspacing=0>
<tr><td height=3 width=<?php echo ceil($hgcl*480/$maxv); ?> bgcolor="#00FF80"></td><td width=<?php echo 480-ceil($hgcl*480/$maxv); ?> bgcolor=black></td></tr>
</table>
<?php
}
?>
<table align=center width=480 cellpadding=0 cellspacing=0>
<tr><td height=3 width=<?php echo ceil($hout*480/$maxv); ?> bgcolor="#FFFF00"></td><td width=<?php echo 480-ceil($hout*480/$maxv); ?> bgcolor=black></td></tr>
</table>
</td>
</tr></table>
<?php
}
?>
<table align=center width=<?php echo $tbw; ?> bgcolor=silver border=0><tr>
<td width=26 align=center bgcolor=red><font face=arial size=1>XX</font></td>
<td><font size=2 face=arial>current hour, next after - hours of previous day</font></td>
</tr></table>
<?php
if ($twmst > 0) {
$q = "select rin,uin,cl,gcl,out from tr where d='$dom'";
$r = mysql_query ($q);
$trrow = mysql_fetch_array($r);
?>
<table align=center width=<?php echo $tbw; ?> bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>total <b><?php echo $dom; ?></b> -
<?php
if ($rwmst > 0) echo " rin: ".$trrow["rin"]." ";
if ($uwmst > 0) echo " uin: ".$trrow["uin"]." ";
if ($cwmst > 0) echo " cl: ".$trrow["cl"]." ";
if ($gwmst > 0) echo " gcl: ".$trrow["gcl"]." ";
echo " out: ".$trrow["out"]." ";
if ($pwmst > 0) { if ($trrow["rin"]==0) echo " pr%: 0"; else {echo "pr%: ".ceil($trrow["cl"]/$trrow["rin"]*100);}}
?>
</font>
</td></tr></table>
<?php
}
?>
<br><table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>If you have any questions, just email at <a href="mailto:<?php echo $msetstr["memail"]; ?>?subject=Trade"><?php echo $msetstr["memail"]; ?></a> or icq at <b><?php echo $msetstr["micq"]; ?></b><br>
powered by <a href="http://www.traffic-drive.com/scripts">Traffic-Drive CJ/TGP v5.14 free</a></font>
</td></tr></table>
</body>
</html>
<?php
}
else {
echo "login incorrect";
}
}
else {
echo "login incorrect";
}
mysql_close($link);
exit;
}
?>
