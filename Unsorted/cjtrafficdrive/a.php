<?php
//Traffic-Drive CJ/TGP v5.14 free, a.php
include("vars.php");include("wmvars.php");include("admvars.php");
if (!$action) {
?>
<html>
<head>
<title>Traffic-Drive Admin Login</title>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
-->
</style>
</head>
<body bgcolor=white text=black link=black>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Admin Login</font>
</td></tr></table>
<form method="POST">
<p align=center>
password: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=pw maxlength=10>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=submit name=action value=login style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
exit;
}
$link = mysql_connect ($hst, $usr , $psw);
mysql_select_db ($db);
$q = "select admpass from mset where k='aHR0cDovL3d3dy5hbGxtYXh4eC5jb20vZnJlZQ=='";
$r = mysql_query($q);
$msetstr = mysql_fetch_array($r);
if ($pw != $msetstr["admpass"]) {
echo "invalid password";
mysql_close($link);
exit;
}
if (($action=="login") or ($action=="refresh") or ($action=="cancel") or ($action=="back")) {
?>
<html>
<head>
<title>Traffic-Drive Admin Interface</title>
<style type="text/css">
<!--
BODY{font-size:xx-small;font-family:arial;font-weight:bold;color:black;background-color:white;}
a { color:black;text-decoration:underline;font-weight:bold}
-->
</style>
</head>
<body bgcolor=white text=black link=black>
<?php
$hr=date("G");$tm = time();
$q = "select * from sts where k='aHR0cDovL3d3dy5hbGxtYXh4eC5jb20vZnJlZQ=='";
$r = mysql_query($q);
$str1 = mysql_fetch_array($r);
$dat = date("G:i:s");
$dat2 = date("l dS of F Y");
$dt = abs($tm - $str1["lu"]);
$qq = "select * from mset where k='aHR0cDovL3d3dy5hbGxtYXh4eC5jb20vZnJlZQ=='";
$rr = mysql_query($qq);
$msetstr = mysql_fetch_array($rr);
if(!$ntblord) $ntblord=$tblord;
if(!$ntbltype) $ntbltype=$tbltype;
if (($ntblord!=$tblord)or($ntbltype!=$tbltype)) {
$fp = fopen("admvars.php","w") or die ("Can not create admvars.php");
$admvars = "<?php\n"."\$tblord='$ntblord';\n"."\$tbltype='$ntbltype';\n"."?>";
fwrite($fp, $admvars);fclose($fp);
}
switch($ntblord){
case "owed":$qord="owed desc";break;
case "alpha":$qord="tr.d";break;
case "rat":$qord="tr.r desc";break;
case "rin":$qord="rin$hr desc";break;
case "uin":$qord="uin$hr desc";break;
case "cl":$qord="cl$hr desc";break;
case "gcl":$qord="gcl$hr desc";break;
case "out":$qord="out$hr desc";break;
case "dowed":$qord="dowed desc";break;
case "drin":$qord="drin desc";break;
case "duin":$qord="duin desc";break;
case "dcl":$qord="dcl desc";break;
case "dgcl":$qord="dgcl desc";break;
case "dout":$qord="dout desc";break;
default:$qord="owed desc";
}
?>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<a href="<?php echo $msetstr["murl"]; ?>"><font size=3><b><?php echo $msetstr["mtitle"]; ?></b></font></a><br>
<font size=2><b><?php echo $dat; ?></b> </font><font size=1><?php echo $dat2; ?> Last Hour: <?php echo $str1["lh"]; ?> Last Update: <?php echo $str1["lu"]; ?> Time: <?php echo $tm; ?> dt: <?php echo $dt; ?><br>
<font size=1>Admin Interface version 5.14, (c) Traffic-Drive 2001-2002</font>
</td></tr></table><br>
<table align=center border=1 bordercolor=black cellspacing=0 style="font-size:x-small;font-family:arial;font-weight:bold;color:black">
<tr>
<td align=center bgcolor=silver width=100>Traffic-Drive</td>
<td align=center bgcolor=silver width=50>rin</td>
<td align=center bgcolor=silver width=50>uin</td>
<td align=center bgcolor=silver width=50>cl</td>
<td align=center bgcolor=silver width=50>gcl</td>
<td align=center bgcolor=silver width=50>out</td>
<td align=center bgcolor=silver width=50>pr%</td>
</tr><tr>
<td align=center bgcolor=silver width=100>Hourly</td>
<td align=center width=50 bgcolor="#80FFFF"><?php echo $str1["hrin"]; ?></td>
<td align=center width=50 bgcolor="#00FFFF"><?php echo $str1["huin"]; ?></td>
<td align=center width=50 bgcolor="#80FF80"><?php echo $str1["hcl"]; ?></td>
<td align=center width=50 bgcolor="#00FF80"><?php echo $str1["hgcl"]; ?></td>
<td align=center width=50 bgcolor="#FFFF00"><?php echo $str1["hout"]; ?></td>
<td align=center width=50 bgcolor="#FF80FF"><?php if($str1["hrin"]!=0)echo ceil($str1["hcl"]/$str1["hrin"]*100);else echo "0"; ?></td>
</tr><tr>
<td align=center bgcolor=silver width=100>Daily</td>
<td align=center width=50 bgcolor="#80FFFF"><?php echo $str1["rin"]; ?></td>
<td align=center width=50 bgcolor="#00FFFF"><?php echo $str1["uin"]; ?></td>
<td align=center width=50 bgcolor="#80FF80"><?php echo $str1["cl"]; ?></td>
<td align=center width=50 bgcolor="#00FF80"><?php echo $str1["gcl"]; ?></td>
<td align=center width=50 bgcolor="#FFFF00"><?php echo $str1["out"]; ?></td>
<td align=center width=50 bgcolor="#FF80FF"><?php if($str1["rin"]!=0)echo ceil($str1["cl"]/$str1["rin"]*100);else echo "0"; ?></td>
</tr>
<?php
$q2="select hr.rin$hr as srin,hr.uin$hr as suin,hr.cl$hr as scl,hr.gcl$hr as sgcl,tr.drin as sdrin,tr.duin as sduin,tr.dcl as sdcl,tr.dgcl as sdgcl,tr.dout as sdout from hr,tr where hr.d='nourl' and tr.d='nourl'";
$r2=mysql_query($q2);
$s=mysql_fetch_array($r2);
if (($str1["rin"]+$str1["hrin"]-$s["srin"]-$s["sdrin"]+$str1["cl"]+$str1["hcl"]-$s["scl"]-$s["sdcl"])==0) $aratio=0; else $aratio=ceil(($str1["out"]+$str1["hout"]-$s["sout"]-$s["sdout"])/($str1["rin"]+$str1["hrin"]-$s["srin"]-$s["sdrin"]+$str1["cl"]+$str1["hcl"]-$s["scl"]-$s["sdcl"])*200);
if (($str1["rin"]+$str1["hrin"]-$s["srin"]-$s["sdrin"])==0) $rratio=0; else $rratio=ceil(($str1["out"]+$str1["hout"]-$s["sout"]-$s["sdout"])/($str1["rin"]+$str1["hrin"]-$s["srin"]-$s["sdrin"])*100);
if (($str1["uin"]+$str1["huin"]-$s["suin"]-$s["sduin"])==0) $uratio=0; else $uratio=ceil(($str1["out"]+$str1["hout"]-$s["sout"]-$s["sdout"])/($str1["uin"]+$str1["huin"]-$s["suin"]-$s["sduin"])*100);
if (($str1["cl"]+$str1["hcl"]-$s["scl"]-$s["sdcl"])==0) $cratio=0; else $cratio=ceil(($str1["out"]+$str1["hout"]-$s["sout"]-$s["sdout"])/($str1["cl"]+$str1["hcl"]-$s["scl"]-$s["sdcl"])*100);
?>
<tr bgcolor=silver>
<td colspan=7 align=center>
actual average ratios (exclude "nourl")<br>
type a: <?php echo $aratio ?>%&nbsp;&nbsp;&nbsp;
type r: <?php echo $rratio ?>%&nbsp;&nbsp;&nbsp;
type u: <?php echo $uratio ?>%&nbsp;&nbsp;&nbsp;
type c: <?php echo $cratio ?>%
</td>
</tr></table>
<form method="POST">
<table align=center bgcolor=silver border=1 cellspacing=0 bordercolor=black width=100%><tr>
<td align=center width=15%>
<a href="http://www.traffic-drive.com/scripts"><font size=2>Traffic-Drive</font></a>
</td><td align=center width=70%>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=stats style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=settings style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=link_track style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=ref_all style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=IP_all style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=black_list style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=hurl style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial"><br>
<font size=2><b>Mass Edit</b></font><br>
<input type=submit name=action value=tp style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=tr style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=rat style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=chf style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=st style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=fr style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=in_tr style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=out_tr style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=h_in_tr style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=h_out_tr style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=res_all style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td><td align=center width=15%>
<a href="http://www.traffic-drive.com/scripts"><font size=2>Free Updates</font></a>
</td>
</tr></table><br>
<table border=1 bordercolor=black cellspacing=0 align=center style="font-size:xx-small;font-family:arial;color:black;background-color:silver" width=100%>
<tr><td align=center>
Table order <input type=radio name=ntblord value="owed"<?php if ((!$ntblord)or($ntblord=="owed")) echo " checked"; ?>>owed
<input type=radio name=ntblord value="alpha"<?php if ($ntblord=="alpha") echo " checked"; ?>>alphabet
<input type=radio name=ntblord value="rat"<?php if ($ntblord=="rat") echo " checked"; ?>>ratio
<input type=radio name=ntblord value="rin"<?php if ($ntblord=="rin") echo " checked"; ?>>rin
<input type=radio name=ntblord value="uin"<?php if ($ntblord=="uin") echo " checked"; ?>>uin
<input type=radio name=ntblord value="cl"<?php if ($ntblord=="cl") echo " checked"; ?>>cl
<input type=radio name=ntblord value="gcl"<?php if ($ntblord=="gcl") echo " checked"; ?>>gcl
<input type=radio name=ntblord value="out"<?php if ($ntblord=="out") echo " checked"; ?>>out
<input type=radio name=ntblord value="dowed"<?php if ($ntblord=="dowed") echo " checked"; ?>>dowed
<input type=radio name=ntblord value="drin"<?php if ($ntblord=="drin") echo " checked"; ?>>rin23
<input type=radio name=ntblord value="duin"<?php if ($ntblord=="duin") echo " checked"; ?>>uin23
<input type=radio name=ntblord value="dcl"<?php if ($ntblord=="dcl") echo " checked"; ?>>cl23
<input type=radio name=ntblord value="dgcl"<?php if ($ntblord=="dgcl") echo " checked"; ?>>gcl23
<input type=radio name=ntblord value="dout"<?php if ($ntblord=="dout") echo " checked"; ?>>out23<br>
Table type <input type=radio name=ntbltype value="stats"<?php if ($ntbltype=="stats") echo " checked"; ?>>stats
<input type=radio name=ntbltype value="settings"<?php if ($ntbltype=="settings") echo " checked"; ?>>settings
</td><td align=center>
<input type=submit name=action value=refresh style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
<?php
if ($ntbltype=="stats") {
?>
<form method="POST">
<table border=1 bordercolor=black cellspacing=0 align=center style="font-size:xx-small;font-family:arial;color:black;background-color:white">
<tr bgcolor=silver>
<td colspan=5 align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=sts style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=edit style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=del style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=res style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=lnk style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=ref style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=IP style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td>
<td colspan=11 align=center><font size=2>Hourly</font></td>
<td colspan=7 align=center><font size=2>Last 23 hours</font></td>
</tr>
<tr bgcolor=silver>
<td align=center width=5><input type=radio name=d value="nourl" checked></td>
<td align=center width=100><font size=2>domain</font></td>
<td align=center width=10><font size=2>tp</font></td>
<td align=center width=10><font size=2>tr</font></td>
<td align=center width=20><font size=2>rat</font></td>
<td align=center width=30><font size=2>owed</font></td>
<td align=center width=30><font size=2>chf</font></td>
<td align=center width=10><font size=2>a</font></td>
<td align=center width=10><font size=2>st</font></td>
<td align=center width=20><font size=2>fr</font></td>
<td align=center width=30><font size=2>rin</font></td>
<td align=center width=30><font size=2>uin</font></td>
<td align=center width=30><font size=2>cl</font></td>
<td align=center width=30><font size=2>gcl</font></td>
<td align=center width=30><font size=2>pr%</font></td>
<td align=center width=30><font size=2>out</font></td>
<td align=center width=30><font size=2>rin</font></td>
<td align=center width=30><font size=2>uin</font></td>
<td align=center width=30><font size=2>cl</font></td>
<td align=center width=30><font size=2>gcl</font></td>
<td align=center width=30><font size=2>pr%</font></td>
<td align=center width=30><font size=2>out</font></td>
<td align=center width=30><font size=2>dowed</font></td>
</tr>
<?php
$q = "select * from tr,hr where tr.d=hr.d order by ".$qord;
$r = mysql_query($q);
$numtrades=mysql_num_rows($r);
while ($s = mysql_fetch_array($r)) {
if ($s["rin$hr"]==0) $hpr=0; else $hpr= ceil($s["cl$hr"]/$s["rin$hr"]*100); 
if ($s["drin"]==0) $dpr=0; else $dpr= ceil($s["dcl"]/$s["drin"]*100); 
?>
<tr align=center style="
{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link {color:black;text-decoration:underline;font-weight:bold} 
">
<td><input type=radio name=d value=<?php echo $s["d"]; ?>></td>
<td<?php if ($s["a"]==0) echo " bgcolor=#D8D6D6"; ?>><font size=1><a href="<?php echo $s["u"]; ?>"><?php echo $s["d"]; ?></a></font></td>
<td><font size=1><?php echo $s["t"]; ?></font></td>
<td><font size=1><?php echo $s["ln"]; ?></font></td>
<td><font size=1><?php echo $s["r"]; ?></font></td>
<td bgcolor="#FF8000"><font size=1><?php echo $s["owed"]; ?></font></td>
<td><font size=1><?php if ($s["hf"]>0) echo $s["hf"]; else echo "-";?></font></td>
<td<?php if ($s["a"]!=$s["a$hr"]) echo " bgcolor=#D8D6D6"; ?>><font size=1><?php echo $s["a"]; ?></font></td>
<td<?php if ($s["a"]!=$s["a$hr"]) echo " bgcolor=#D8D6D6"; ?>><font size=1><?php echo $s["a$hr"]; ?></font></td>
<td><font size=1><?php echo $s["f$hr"]; ?></font></td>
<td bgcolor="#80FFFF"><font size=1><?php echo $s["rin$hr"]; ?></font></td>
<td bgcolor="#00FFFF"><font size=1><?php echo $s["uin$hr"]; ?></font></td>
<td bgcolor="#80FF80"><font size=1><?php echo $s["cl$hr"]; ?></font></td>
<td bgcolor="#00FF80"><font size=1><?php echo $s["gcl$hr"]; ?></font></td>
<td bgcolor="#FF80FF"><font size=1><?php echo $hpr; ?></font></td>
<td bgcolor="#FFFF00"><font size=1><?php echo $s["out$hr"]; ?></font></td>
<td bgcolor=#80FFFF><font size=1><?php echo $s["drin"]; ?></font></td>
<td bgcolor=#00FFFF><font size=1><?php echo $s["duin"]; ?></font></td>
<td bgcolor=#80FF80><font size=1><?php echo $s["dcl"]; ?></font></td>
<td bgcolor=#00FF80><font size=1><?php echo $s["dgcl"]; ?></font></td>
<td bgcolor="#FF80FF"><font size=1><?php echo $dpr; ?></font></td>
<td bgcolor="#FFFF00"><font size=1><?php echo $s["dout"]; ?></font></td>
<td bgcolor="#FF8000"><font size=1><?php echo $s["dowed"]; ?></font></td>
<tr>
<?php
}
?>
</form>
<form method="POST">
<tr bgcolor=silver align=center><td colspan=5>
<font size=2><b><?php echo $numtrades-1; ?></b> trades total</font>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=add style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td><td colspan=18 align="center">(c) Traffic-Drive 2001-2002</td></tr>
</form>
<tr bgcolor=silver align=left><td colspan=23><font face=arial size=2>
<ul>
<li><b>tp</b> - trade type; a - average raw in - clicks, c - clicks(productivity), u - unique in, r - raw in, f - force only</li>
<li><b>tr</b> - target (a - mixed site, p - pictures only, m - movies only)</li>
<li><b>rat</b> - ratio</li>
<li><b>owed</b> - hits owed to site right now; first site in the list first get hits if all hourly forces is out (chf is "-" for all sites)<br>
trade type a: <b>owed = dowed + (rin + cl)/2*rat/100 - out + fr</b><br>
trade type c: <b>owed = dowed + cl*rat/100 - out + fr</b><br>
trade type u: <b>owed = dowed + uin*rat/100 - out + fr</b><br>
trade type r: <b>owed = dowed + rin*rat/100 - out + fr</b><br>
trade type f: <b>owed = fr</b> (not available)
</li>
<li><b>chf</b> - number of hourly forced hits which have stayed for the site in current hour; same as <b>fr</b> by the beginning of the current hour; you can set up this value at any time by edit (force right now)</li>
<li><b>a</b> - actual group of site; usually same as <b>st</b>, however, if works any of triggers(in or out), this value is reset to 0; if this value is 0 then out to site will not be in the current hour (inactive trade)</li>
<li><b>st</b> - group of site for current hour; that was entered for site, may be 0 (for inactive trade)</li>
<li><b>fr</b> - force for current hour; that was entered for site</li>
<li><b>rin</b> - raw in</li>
<li><b>uin</b> - unique in</li>
<li><b>cl</b> - clicks</li>
<li><b>gcl</b> - gallery clicks; if you use o.php?url=... or o.php?hurl=...; counted hits only really out to url(hurl), not to trade, all other counted by <b>cl</b></li>
<li><b>pr</b> - true productivity; counted without gallery clicks</li>
<li><b>out</b> - number of outs to site</li>
<li><b>dowed</b> - hits owed to site by the beginning of the current hour<br>
trade type a: <b>dowed = (rin23 + cl23)/2*rat/100 - out23</b><br>
trade type c: <b>dowed = cl23*rat/100 - out23</b><br>
trade type u: <b>dowed = uin23*rat/100 - out23</b><br>
trade type r: <b>dowed = rin23*rat/100 - out23</b><br>
trade type f: <b>dowed = 0</b> (not available)
</li>
</ul>
</font>
</td></tr>
</table>
</body>
</html>
<?php
} else {
?>
<form method="POST">
<table border=1 bordercolor=black cellspacing=0 align=center style="font-size:xx-small;font-family:arial;color:black;background-color:white">
<tr bgcolor=silver>
<td colspan=2 align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=sts style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=edit style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=del style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=res style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial"><br>
<input type=submit name=action value=lnk style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=ref style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=IP style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td>
<td colspan=4 align=center><font size=2>Triggers</font></td>
<td colspan=24 align=center><font size=2>Forces</font></td>
</tr>
<tr align=center style="
{font-size:x-small;font-family:arial;color:black;background-color:silver;}
a:link {color:black;text-decoration:underline;font-weight:bold} 
">
<td width=5><input type=radio name=d value="nourl" checked></td>
<td width=200><font size=1>domain</font></td>
<td width=20><font size=1>itr</font></td>
<td width=20><font size=1>otr</font></td>
<td width=20><font size=1>hitr</font></td>
<td width=20><font size=1>hotr</font></td>
<td width=20><font size=1>fr0</font></td>
<td width=20><font size=1>fr1</font></td>
<td width=20><font size=1>fr2</font></td>
<td width=20><font size=1>fr3</font></td>
<td width=20><font size=1>fr4</font></td>
<td width=20><font size=1>fr5</font></td>
<td width=20><font size=1>fr6</font></td>
<td width=20><font size=1>fr7</font></td>
<td width=20><font size=1>fr8</font></td>
<td width=20><font size=1>fr9</font></td>
<td width=20><font size=1>fr10</font></td>
<td width=20><font size=1>fr11</font></td>
<td width=20><font size=1>fr12</font></td>
<td width=20><font size=1>fr13</font></td>
<td width=20><font size=1>fr14</font></td>
<td width=20><font size=1>fr15</font></td>
<td width=20><font size=1>fr16</font></td>
<td width=20><font size=1>fr17</font></td>
<td width=20><font size=1>fr18</font></td>
<td width=20><font size=1>fr19</font></td>
<td width=20><font size=1>fr20</font></td>
<td width=20><font size=1>fr21</font></td>
<td width=20><font size=1>fr22</font></td>
<td width=20><font size=1>fr23</font></td>
</tr>
<?php
$q = "select * from tr,hr where tr.d=hr.d order by ".$qord;
$r = mysql_query($q);
$numtrades=mysql_num_rows($r);
$fr=array();
while ($s = mysql_fetch_array($r)) {
?>
<tr align=center style="
{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link {color:black;text-decoration:underline;font-weight:bold} 
">
<td><input type=radio name=d value=<?php echo $s["d"]; ?>></td>
<td<?php if ($s["a"]==0) echo " bgcolor=#D8D6D6"; ?>><font size=1><a href="<?php echo $s["u"]; ?>"><?php echo $s["d"]; ?></a></font></td>
<td bgcolor="#80FFFF"><font size=1><?php echo $s["tr"]; ?></font></td>
<td bgcolor="#FFFF00"><font size=1><?php echo $s["otr"]; ?></font></td>
<td bgcolor="#80FFFF"><font size=1><?php echo $s["htr"]; ?></font></td>
<td bgcolor="#FFFF00"><font size=1><?php echo $s["hotr"]; ?></font></td>
<?php
for ($i=0; $i<24; $i++){
?>
<td<?php if ($s["a$i"]==0) echo " bgcolor=#D8D6D6";else if($i==$hr)echo " bgcolor=#ff0000"?>><font size=1><?php echo $s["f$i"]; ?></font></td>
<?php
$fr[$i]=$fr[$i]+$s["f$i"];
}
?>
<tr>
<?php
}
?>
</form>
<tr bgcolor=silver align=center>
<form method="POST"><td colspan=2>
<font size=2><b><?php echo $numtrades-1; ?></b> trades total</font>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=add style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></form>
<td colspan=4>Total Forces:</td>
<?php
for ($i=0; $i<24; $i++){
?>
<td><font size=1><?php echo $fr[$i]; ?></font></td>
<?php
}
?>
</tr>
<tr bgcolor=silver align=left><td colspan=30><font face=arial size=2>
<br><ul>
<li><b>itr</b> - in trigger</li>
<li><b>otr</b> - out trigger</li>
<li><b>hitr</b> - previous hour in trigger</li>
<li><b>hotr</b> - previous hour out trigger</li>
<li><b>frN</b> - hourly force</li>
</ul>
</font>
</td></tr>
</table>
</body>
</html>
<?php
}
mysql_close($link);
exit;
}
if ($action=="edit") {
?>
<html>
<head>
<title>Traffic-Drive Edit Trade</title>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
</head>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Edit Trade</font>
</td></tr></table>
<?php
$q = "select * from tr where d='$d'";
$r = mysql_query ($q) or die ("Select from tr failed");
$trstr = mysql_fetch_array($r);
$q = "select * from hr where d='$d'";
$r = mysql_query ($q) or die ("Select from hr failed");
$hrstr = mysql_fetch_array($r);
?>
<form method=POST>
<p align=center>
<input type=hidden name=d value="<?php echo $trstr["d"]; ?>">
<input type=hidden name=pw value="<?php echo $pw; ?>">
domain: <font size=3><b><?php echo $trstr["d"]; ?></b></font><br>
url: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=yurl value="<?php echo $trstr["u"]; ?>" size="30" maxlength="100">
title: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=ytitle value="<?php echo $trstr["tl"]; ?>" size="30" maxlength="100"><br>
email: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=yemail value="<?php echo $trstr["em"]; ?>" size="30" maxlength="100">
icq: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=yicq value="<?php echo $trstr["icq"]; ?>" size="20" maxlength="20">
nick: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=ynick value="<?php echo $trstr["nick"]; ?>" size="20" maxlength="20"><br>
password: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=ypsw value="<?php echo $trstr["pw"]; ?>" size="10" maxlength="10"><br>
</p>
<hr color=black>
<p align=center>
Total:<br>
rin: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=rin value="<?php echo $trstr["rin"]; ?>" size="6" maxlength="6">
uin: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=uin value="<?php echo $trstr["uin"]; ?>" size="6" maxlength="6">
cl: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=cl value="<?php echo $trstr["cl"]; ?>" size="6" maxlength="6">
gcl: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=gcl value="<?php echo $trstr["gcl"]; ?>" size="6" maxlength="6">
out: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=out value="<?php echo $trstr["out"]; ?>" size="6" maxlength="6">
prod: <font size=2><b>
<?php
if ($trstr["rin"]==0) $pr=0; else $pr=ceil($trstr["cl"]/$trstr["rin"]*100);
echo $pr; 
?>
</b></font>
owed: <font size=2><b>
<?php
if ($trstr["t"]=="a") $towed = ceil(($trstr["uin"]+$trstr["cl"])*$trstr["r"]/200-$trstr["out"]);
if ($trstr["t"]=="r") $towed = ceil($trstr["rin"]*$trstr["r"]/100-$trstr["out"]);
if ($trstr["t"]=="u") $towed = ceil($trstr["uin"]*$trstr["r"]/100-$trstr["out"]);
if ($trstr["t"]=="c") $towed = ceil($trstr["cl"]*$trstr["r"]/100-$trstr["out"]);
if ($trstr["t"]=="f") $towed = 0;
echo $towed; 
?>
</b></font><br>
trade type(a,c,r,u,f):
<input type="Radio" name=t value="a" <?php if ($trstr["t"]=="a") echo "checked"; ?>>average raw_in-clicks
<input type="Radio" name=t value="c" <?php if ($trstr["t"]=="c") echo "checked"; ?>>clicks(productivity)
<input type="Radio" name=t value="r" <?php if ($trstr["t"]=="r") echo "checked"; ?>>raw in
<input type="Radio" name=t value="u" <?php if ($trstr["t"]=="u") echo "checked"; ?>>unique in
<input type="Radio" name=t value="f" <?php if ($trstr["t"]=="f") echo "checked"; ?>>force only<br><br>
ratio: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=ratio value="<?php echo $trstr["r"]; ?>" size="3" maxlength="3"><br>
in trigger: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=tr value="<?php echo $trstr["tr"]; ?>" size="3" maxlength="3">
out trigger: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=otr value="<?php echo $trstr["otr"]; ?>" size="3" maxlength="3"><br>
previous hour in trigger: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=htr value="<?php echo $trstr["htr"]; ?>" size="3" maxlength="3">
previous hour out trigger: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=hotr value="<?php echo $trstr["hotr"]; ?>" size="3" maxlength="3"><br>
force right now: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=hf value="<?php if ($trstr["hf"]>0) echo $trstr["hf"]; else echo "0";?>" size="3" maxlength="3"><br>
Target:
<input type="Radio" name=ln value="a" <?php if ($trstr["ln"]=="a") echo "checked"; ?>>all
<input type="Radio" name=ln value="p" <?php if ($trstr["ln"]=="p") echo "checked"; ?>>pictures only
<input type="Radio" name=ln value="m" <?php if ($trstr["ln"]=="m") echo "checked"; ?>>movies only<br>
actual group (a): <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=actgrp value="<?php echo $trstr["a"]; ?>" size="1" maxlength="1"><br>
<input type="Radio" name=etr value="1" <?php if ($trstr["etr"]=="1") echo "checked"; ?>>check triggers for current hour
<input type="Radio" name=etr value="0" <?php if ($trstr["etr"]=="0") echo "checked"; ?>>don't check triggers for current hour<br><br>
<input type="Radio" name=itl value="1" <?php if ($trstr["itl"]=="1") echo "checked"; ?>>include in top list
<input type="Radio" name=itl value="0" <?php if ($trstr["itl"]=="0") echo "checked"; ?>>don't include in top list<br><br>
<?php
if ($trstr["d"]!="nourl"){
?>
individual pages prefix: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=fpr value="<?php echo $trstr["fpr"]; ?>" size="10" maxlength="10"><br>
<?php
} else {
?>
<input type=hidden name=fpr value=""><br>
<?php
}
?>
</p>
<table align=center><tr><td align=center colspan=12>
<font size=2><b>Hourly Groups(st) and Forces(fr) (red - current hour):</b></font>
</td></tr><tr>
<?php
$curhr = date("G");
for ($hr=0; $hr<24; $hr++) {
if ($curhr != $hr) $marker="black"; else $marker="red";
if ($hr == 12) echo "</tr><tr><td colspan=24><hr color=black></td></tr><tr>";
?>
<td width=60>
<font size="1" color=<?php echo $marker; ?>>st<?php echo $hr; ?><br></font><input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=<?php echo "a[$hr]"; ?> value="<?php echo $hrstr["a$hr"]; ?>" size="1" maxlength="1"><br>
<font size="1" color=<?php echo $marker; ?>>fr<?php echo $hr; ?><br></font><input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=<?php echo "f[$hr]"; ?> value="<?php echo $hrstr["f$hr"]; ?>" size="3" maxlength="3">
</td>
<?php
}
?>
</tr></table>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=submit name=action value=cancel  style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=save  style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="save") {
?>
<html>
<head>
<title>Traffic-Drive Edit Trade</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Edit Trade</font>
</td></tr></table>
<?php
ignore_user_abort(true);
$curhr = date("G");
$q1 = "update hr set ";
for($i=0; $i<24; $i++) {
if ($i==$curhr) $achr=$a[$i];
$q1 = $q1 . " f$i='$f[$i]', a$i='$a[$i]',";
}
$q1 = substr($q1, 0, strlen($q1)-1);
$q1 = $q1 . " where d='$d'";
$r = mysql_query ($q1) or die ("Update hr failed");
$q = "update tr set u='$yurl', tl='$ytitle', em='$yemail', icq='$yicq', nick='$ynick', pw='$ypsw', rin='$rin', uin='$uin', cl='$cl', gcl='$gcl', out='$out', r='$ratio', t='$t', tr='$tr', otr='$otr', a='$actgrp', hf='$hf', ln='$ln',htr='$htr',hotr='$hotr',etr='$etr',itl='$itl',fpr='$fpr' where d='$d'";	
$r = mysql_query ($q) or die ("Update tr failed");
ignore_user_abort(false);
?>
<p align=center>Done</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="del") {
?>
<html>
<head>
<title>Traffic-Drive Trade Delete</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Trade Delete</font>
</td></tr></table>
<p align=center>Delete domain <b><?php echo $d; ?></b>?</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=hidden name=d value="<?php echo $d; ?>">
<input type=submit name=action value=delete style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=delete_and_blacklist style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=cancel style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if (($action=="delete")or($action=="delete_and_blacklist")) {
?>
<html>
<head>
<title>Traffic-Drive Trade Delete</title>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
</head>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Trade Delete</font>
</td></tr></table>
<?php
if ($d=="nourl") {
echo "<p align=center>Can't delete <b>nourl</b>!</p>";
}
else {
ignore_user_abort(true);
$q = "delete from tr where d='$d'";	
$r = mysql_query ($q) or die ("Delete from tr failed");
$q = "delete from hr where d='$d'";
$r = mysql_query ($q) or die ("Delete from hr failed");
ignore_user_abort(false);
if ($action=="delete_and_blacklist") {
$q = "insert into bl values ('$d')";
$r = mysql_query ($q) or die ("Add to blacklist failed");
echo "<p align=center>Domain <b>".$d."</b> is deleted and blacklisted</p>";
} else echo "<p align=center>Domain <b>".$d."</b> is deleted</p>";
}
?>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="link_track") {
?>
<html>
<head>
<title>Traffic-Drive Link Tracking</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Link Tracking (last 23 hours)</font>
</td></tr></table>
<form method="POST">
<p align=center>
<?php
$q = "select tr,SUM(cl) as scl from gal group by tr order by scl desc";	
$r = mysql_query ($q) or die ("Can't select from gal");
if (mysql_num_rows($r)==0) {
echo "<b>None</b>";
}
else 
while ($galstr = mysql_fetch_array($r)) {
?>
<input type="radio" name="tr" value="<?php echo $galstr["tr"]; ?>" checked>
<?php
echo $galstr["tr"]." : ".$galstr["scl"]."<br>";
}
?>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=clear_links style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=analyze_link style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="clear_links") {
?>
<html>
<head>
<title>Traffic-Drive Clear Links</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Clear Links</font>
</td></tr></table>
<?php
ignore_user_abort(true);
$q = "delete from gal";	
$r = mysql_query ($q) or die ("Can't delete from gal");
ignore_user_abort(false);
?>
<p align=center>Done</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="analyze_link") {
?>
<html>
<head>
<title>Traffic-Drive Link Tracking</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Link Tracking (last 23 hours): <?php echo $tr; ?></font>
</td></tr></table>
<form method="POST">
<p align=center>
<?php
$q = "select d,SUM(cl) as scl from gal where tr='$tr' group by d order by scl desc";	
$r = mysql_query ($q) or die ("Can't select from gal");
if (mysql_num_rows($r)==0) {
echo "<b>None</b>";
}
else 
while ($galstr = mysql_fetch_array($r)) {
$dtmp=$galstr["d"];
$q1 = "select SUM(cl) as scl from gal where d='$dtmp' group by d";
$r1 = mysql_query ($q1) or die ("Can't select from gal");
  while ($galstr1 = mysql_fetch_array($r1)) {
echo $galstr["d"]." : <b>".$galstr["scl"]."</b> of <b>".$galstr1["scl"]."</b> total,  %: <b>".ceil($galstr["scl"]/$galstr1["scl"]*100)."</b><br>";
}
}
?>
</p>
<hr color="#000000">
<p align=center>
<b>no clicks:</b><br><br>
<?php
$q = "select d,SUM(cl) as scl from gal group by d order by scl desc";
$r = mysql_query ($q) or die ("Can't select from gal");
while ($galstr = mysql_fetch_array($r)) {
$dtmp=$galstr["d"];
$q1 = "select d from gal where ((d='$dtmp') and (tr='$tr'))";
$r1 = mysql_query ($q1) or die ("Can't select from gal");
if (mysql_num_rows($r1)==0) {
echo $galstr["d"]." : <b>0</b> of <b>".$galstr["scl"]."</b> total,  %: <b>0</b><br>";
}
}
?>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=clear_links style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}


if ($action=="lnk") {
?>
<html>
<head>
<title>Traffic-Drive Link Tracking</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Link Tracking (last 23 hours): <b><?php echo $d; ?></b></font>
</td></tr></table>
<form method="POST">
<p align=center>
<?php
$q = "select tr,d,SUM(cl) as scl from gal where d='$d' group by tr order by scl desc";	
$r = mysql_query ($q) or die ("Can't select from gal");
if (mysql_num_rows($r)==0) {
echo "<b>None</b>";
}
else 
while ($galstr = mysql_fetch_array($r)) {
echo $galstr["tr"]." : <b>".$galstr["scl"]."</b><br>";
}
?>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="hurl") {
?>
<html>
<head>
<title>Traffic-Drive URLs Table</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive URLs Table</font>
</td></tr></table>
<form method="POST">
<p align=center>
<?php
$q = "select * from hurl order by l";	
$r = mysql_query ($q) or die ("Can't select from hurl");
if (mysql_num_rows($r)==0) {
echo "<b>None</b>";
}
else 
while ($str = mysql_fetch_array($r)) {
?>
<input type="radio" name="l" value="<?php echo $str["l"]; ?>" checked>
<?php
echo $str["l"]." : ".$str["u"]."<br>";
}
?>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=clear_hurls style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=add_hurl style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=edit_hurl style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=delete_hurl style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="clear_hurls") {
?>
<html>
<head>
<title>Traffic-Drive Clear URLs Table</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Clear URLs Table</font>
</td></tr></table>
<form method="POST">
<p align=center>
<?php
$q = "delete from hurl";	
$r = mysql_query ($q) or die ("Can't delete from hurl");
echo "<b>Done</b>";

?>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=hurl style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=add_hurl style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=edit_hurl style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="add_hurl") {
?>
<html>
<head>
<title>Traffic-Drive Add URL in URLs Table</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Add URL in URLs Table</font>
</td></tr></table>
<form method="POST">
<p align=center>
hurl alias: <input type=Text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=l size=20 maxlength=50><br>
url: <input type=Text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=u size=50 maxlength=100 value="http://"><br>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=hurl style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=add_hurl_final style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="add_hurl_final") {
?>
<html>
<head>
<title>Traffic-Drive Add URL in URLs Table</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Add URL in URLs Table</font>
</td></tr></table>
<form method="POST">
<p align=center>
<?php
$q = "select l from hurl where l='$l'";	
$r = mysql_query ($q) or die ("Can't select from hurl");
if (mysql_num_rows($r)!=0) echo "<b>hurl alias already in database</b>";
else {
$q = "insert into hurl values ('$l','$u')";
$r = mysql_query ($q) or die ("Can't insert into hurl");
echo "<b>Done</b>";
}
?>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=hurl style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="edit_hurl") {
?>
<html>
<head>
<title>Traffic-Drive Edit URL in URLs Table</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Edit URL in URLs Table</font>
</td></tr></table>
<form method="POST">
<p align=center>
<?php
$q = "select * from hurl where l='$l'";	
$r = mysql_query ($q) or die ("Can't select from hurl");
if (mysql_num_rows($r)==0) {echo "<b>can't find hurl alias in database</b>";mysql_close ($link);exit;}
else $s = mysql_fetch_array($r);
?>
hurl alias: <input type=Text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=l size=20 maxlength=50 value="<?php echo $s["l"];?>"><br>
url: <input type=Text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=u size=50 maxlength=100 value="<?php echo $s["u"];?>"><br>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=hurl style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=edit_hurl_final style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="edit_hurl_final") {
?>
<html>
<head>
<title>Traffic-Drive Edit URL in URLs Table</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Edit URL in URLs Table</font>
</td></tr></table>
<form method="POST">
<p align=center>
<?php
$q = "update hurl set u='$u' where l='$l'";	
$r = mysql_query ($q) or die ("Can't update hurl");
echo "<b>Done</b>";
?>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=hurl style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="delete_hurl") {
?>
<html>
<head>
<title>Traffic-Drive Delete URL from URLs Table</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Delete URL from URLs Table</font>
</td></tr></table>
<form method="POST">
<p align=center>
Delete hurl alias: <b><?php echo $l;?></b> from database?
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=hidden name=l value="<?php echo $l; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=hurl style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=delete_hurl_final style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="delete_hurl_final") {
?>
<html>
<head>
<title>Traffic-Drive Edit URL in URLs Table</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Edit URL in URLs Table</font>
</td></tr></table>
<form method="POST">
<p align=center>
<?php
$q = "delete from hurl where l='$l'";	
$r = mysql_query ($q) or die ("Can't delete from hurl");
echo "<b>Done</b>";
?>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=hurl style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="ref_all") {
?>
<html>
<head>
<title>Traffic-Drive Refering URL Log</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Refering URL Log (last 23 hours)</font>
</td></tr></table>
<form method="POST">
<p align=center>
<?php
$q = "select rurl,SUM(rin) as srin from rf group by rurl order by srin desc";	
$r = mysql_query ($q) or die ("Can't select from rf");
if (mysql_num_rows($r)==0) {
echo "<b>None</b>";
}
else 
while ($rfstr = mysql_fetch_array($r)) {
echo $rfstr["rurl"]." : ".$rfstr["srin"]."<br>";
}
?>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=clear_log style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="clear_log") {
?>
<html>
<head>
<title>Traffic-Drive Clear Refering URL Log</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Clear Refering URL Log (last 23 hours)</font>
</td></tr></table>
<form method="POST">
<p align=center>
<?php
$q = "delete from rf";	
$r = mysql_query ($q) or die ("Can't delete from rf");
?>
<b>Done</b>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="ref") {
?>
<html>
<head>
<title>Traffic-Drive Refering URL Log</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Refering URL Log (last 23 hours) domain:<?php echo $d ?></font>
</td></tr></table>
<form method="POST">
<p align=center>
<?php
$q = "select rurl,SUM(rin) as srin from rf where d='$d' group by rurl order by srin desc";	
$r = mysql_query ($q) or die ("Can't select from rf");
if (mysql_num_rows($r)==0) {
echo "<b>None</b>";
}
else 
while ($rfstr = mysql_fetch_array($r)) {
echo $rfstr["rurl"]." : ".$rfstr["srin"]."<br>";
}
?>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="black_list") {
?>
<html>
<head>
<title>Traffic-Drive Black List</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Black List</font>
</td></tr></table>
<p align=center>
<?php
$q = "select d from bl";	
$r = mysql_query ($q) or die ("Can't select from bl");
if (mysql_num_rows($r)==0) {
echo "<b>None</b>";
}
else 
while ($blstr = mysql_fetch_array($r)) {
echo "<b>".$blstr["d"]."</b><br>";
}
?>
</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=add_to_bl style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=delete_from_bl style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="add_to_bl") {
?>
<html>
<head>
<title>Traffic-Drive Add To Black List</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Add To Black List</font>
</td></tr></table>
<form method="POST">
<p align=center>
Domain: <input type=Text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=dom size=20 maxlength=100>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=cancel style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=add_to_bl_final style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="add_to_bl_final") {
?>
<html>
<head>
<title>Traffic-Drive Add To Black List</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Add To Black List</font>
</td></tr></table>
<p align=center>
<?php
if ($dom!="") {
$q = "select d from bl where d='$dom'";
$r = mysql_query ($q) or die ("Can't select from bl");
if (mysql_num_rows($r)==0) {
$q1 = "insert into bl values ('$dom')";	
$r1 = mysql_query ($q1) or die ("Can't insert to bl");
echo "Domain <b>".$dom."</b> is added to Black List</b>";
}
else {
echo "Domain <b>".$dom."</b> is already in Black List</b>";
}
}
else {
echo "Please, go back and fill out domain form";
}
?>
</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=black_list style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="delete_from_bl") {
?>
<html>
<head>
<title>Traffic-Drive Delete From Black List</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Delete From Black List</font>
</td></tr></table>
<form method="POST">
<p align=center>
Domain: <input type=Text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=dom size=20 maxlength=100>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=cancel style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=delete_from_bl_final style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="delete_from_bl_final") {
?>
<html>
<head>
<title>Traffic-Drive Delete From Black List</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Delete From Black List</font>
</td></tr></table>
<p align=center>
<?php
if ($dom!="") {
$q = "select d from bl where d='$dom'";
$r = mysql_query ($q) or die ("Can't select from bl");
if (mysql_num_rows($r)!=0) {
$q1 = "delete from bl where d='$dom'";	
$r1 = mysql_query ($q1) or die ("Can't delete from bl");
echo "Domain <b>".$dom."</b> is deleted from Black List</b>";
}
else {
echo "Domain <b>".$dom."</b> is not in Black List</b>";
}
}
else {
echo "Please, go back and fill out domain form";
}
?>
</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=black_list style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="settings") {
$q = "select * from mset where k='aHR0cDovL3d3dy5hbGxtYXh4eC5jb20vZnJlZQ=='";	
$r = mysql_query ($q) or die ("Can't select from mset");
$msetstr = mysql_fetch_array($r);
$q = "select u from tr where d='nourl'";
$r = mysql_query ($q) or die ("Can't select from tr");
$trstr = mysql_fetch_array($r);	
?>
<html>
<head>
<title>Traffic-Drive Settings</title>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
</head>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Settings</font>
</td></tr></table>
<form method=POST>
<p align=center>
url: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=murl value="<?php echo $msetstr["murl"]; ?>" size="30" maxlength="100">
title: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=mtitle value="<?php echo $msetstr["mtitle"]; ?>" size="30" maxlength="100"><br>
email: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=memail value="<?php echo $msetstr["memail"]; ?>" size="30" maxlength="100">
icq: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=micq value="<?php echo $msetstr["micq"]; ?>" size="20" maxlength="20"><br>
ext url: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=exturl value="<?php echo $trstr["u"]; ?>" size="30" maxlength="100"><br><br>
default trade type:<br>
<input type="Radio" name="dt" value="a" checked>average raw_in-clicks(default, best working)
<input type="Radio" name="dt" value="c">clicks(productivity)
<input type="Radio" name="dt" value="r">raw in
<input type="Radio" name="dt" value="u">unique in
<input type="Radio" name="dt" value="f">force only<br><br>
default ratio: <input style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" type=text name=dr value="<?php echo $msetstr["dr"]; ?>" size="3" maxlength="3">
default hourly force: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=df value="<?php echo $msetstr["df"]; ?>" size="3" maxlength="3">
default group: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=ndg value="<?php echo $dg; ?>" size="1" maxlength="1"><br><br>
default in trigger: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=dtr value="<?php echo $msetstr["dtr"]; ?>" size="3" maxlength="3">
default out trigger: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=dotr value="<?php echo $msetstr["dotr"]; ?>" size="3" maxlength="3"><br>
default previous hour in trigger: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=dhtr value="<?php echo $msetstr["dhtr"]; ?>" size="3" maxlength="3">
default previous hour out trigger: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=dhotr value="<?php echo $msetstr["dhotr"]; ?>" size="3" maxlength="3"><br>
</p>
<hr color=black>
<p align=center>
recalculate time (seconds): <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=nrt value="<?php echo $rt; ?>" size="3" maxlength="3">
unique time (IP-log time) (seconds): <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=nript value="<?php if ($ript) echo $ript; else echo "3600"; ?>" size="6" maxlength="6"><br>
</p>
<hr color=black>
<p align=center>
<b>In Pages:</b><br>
file extension: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=niext value="<?php echo $iext; ?>" size="6" maxlength="6"><br>
<input type="Radio" name="nitype" value="0"<?php if ($itype==0) echo " checked" ?>>include (SSI don't work in included files)<br>
<input type="Radio" name="nitype" value="1"<?php if ($itype==1) echo " checked" ?>>redirect (only if "i.php" or "index.php" is used as in-file)<br>
</p>
<hr color=black>
<p align=center>
<b>TGP & Real content features:</b><br>
default out to url(hurl) percent: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=ngp value="<?php echo $gp; ?>" size="3" maxlength="3"><br>
<input type="Radio" name="ndgfc" value="1" checked>first time click to url(hurl) by default
<input type="Radio" name="ndgfc" value="0">don't use first time click by default<br> 
<input type="Radio" name="ndgtr" value="1" checked>track any click to url(hurl) as first by default
<input type="Radio" name="ndgtr" value="0">don't track any click to url(hurl) as first by default<br> 
</p>
<hr color=black>
<p align=center>
<b>Do not change this section, if you don't want to use target out features</b><br><br>
default out to site with:
<input type="Radio" name="ndln" value="a" <?php if ($dln=="a") echo "checked"; ?>>mixed (default)
<input type="Radio" name="ndln" value="p" <?php if ($dln=="p") echo "checked"; ?>>pictures only
<input type="Radio" name="ndln" value="m" <?php if ($dln=="m") echo "checked"; ?>>movies only<br><br>
<input type="Radio" name="nelnwm" value="0" <?php if ($elnwm=="0") echo "checked"; ?>>disable webmaster to pick target of out (default)
<input type="Radio" name="nelnwm" value="1" <?php if ($elnwm=="1") echo "checked"; ?>>enable webmaster to pick target of out<br><br>
default webmaster site type (if webmaster can't pick site type):<br>
<input type="Radio" name="ndlnwm" value="a" <?php if ($dlnwm=="a") echo "checked"; ?>>mixed (default)
<input type="Radio" name="ndlnwm" value="p" <?php if ($dlnwm=="p") echo "checked"; ?>>pictures only
<input type="Radio" name="ndlnwm" value="m" <?php if ($dlnwm=="m") echo "checked"; ?>>movies only<br><br><br>
new site group:<br>
<input type="Radio" name="ngpwmflag" value="1" <?php if ($gpwm==$dg) echo "checked"; ?>>default group<br>
<input type="Radio" name="ngpwmflag" value="0" <?php if ($gpwm!=$dg) echo "checked"; ?>>enable webmaster to pick the group (non-blank fields):<br>
group 1 <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name="ngpwma[1]" value="<?php echo $gpwma[1]; ?>" size="20" maxlength="30"><br>
group 2 <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name="ngpwma[2]" value="<?php echo $gpwma[2]; ?>" size="20" maxlength="30"><br>
group 3 <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name="ngpwma[3]" value="<?php echo $gpwma[3]; ?>" size="20" maxlength="30"><br>
group 4 <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name="ngpwma[4]" value="<?php echo $gpwma[4]; ?>" size="20" maxlength="30"><br>
group 5 <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name="ngpwma[5]" value="<?php echo $gpwma[5]; ?>" size="20" maxlength="30"><br>
group 6 <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name="ngpwma[6]" value="<?php echo $gpwma[6]; ?>" size="20" maxlength="30"><br>
group 7 <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name="ngpwma[7]" value="<?php echo $gpwma[7]; ?>" size="20" maxlength="30"><br>
group 8 <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name="ngpwma[8]" value="<?php echo $gpwma[8]; ?>" size="20" maxlength="30"><br>
group 9 <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name="ngpwma[9]" value="<?php echo $gpwma[9]; ?>" size="20" maxlength="30"><br>
<br><br>
Include new site in Top List:<br>
<input type="Radio" name="ntlwm" value="1" <?php if ($tlwm==1) echo "checked"; ?>>yes
<input type="Radio" name="ntlwm" value="0" <?php if ($tlwm!=1) echo "checked"; ?>>no<br>
</p>
<hr color=black>
<p align=center>
<b>Default out to:</b><br>
<input type="Radio" name="ndogflag" value="1" <?php if ($dog==$dg) echo "checked"; ?>>default group<br>
<input type="Radio" name="ndogflag" value="0" <?php if ($dog!=$dg) echo "checked"; ?>>custom groups:<br>
set of groups (divided by "-", e.g.:"1-3-7"): <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=ndog value="<?php echo $dog; ?>" size="20" maxlength="30">
</p>
<hr color=black>
<p align=center>
max toplist members (0 if you don't want to use toplist features): <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=ntopmax value="<?php echo $topmax; ?>" size="3" maxlength="3"><br>
<b>Top List Order:</b><br>
previous hour: <input type="Radio" name="ntoporder" value="rin" <?php if ($toporder=="rin") echo "checked"; ?>>rin
<input type="Radio" name="ntoporder" value="uin" <?php if ($toporder=="uin") echo "checked"; ?>>uin
<input type="Radio" name="ntoporder" value="cl" <?php if ($toporder=="cl") echo "checked"; ?>>cl
<input type="Radio" name="ntoporder" value="gcl" <?php if ($toporder=="gcl") echo "checked"; ?>>gcl<br>
last 23 hours: <input type="Radio" name="ntoporder" value="drin" <?php if ($toporder=="drin") echo "checked"; ?>>rin
<input type="Radio" name="ntoporder" value="duin" <?php if ($toporder=="duin") echo "checked"; ?>>uin
<input type="Radio" name="ntoporder" value="dcl" <?php if ($toporder=="dcl") echo "checked"; ?>>cl
<input type="Radio" name="ntoporder" value="dgcl" <?php if ($toporder=="dgcl") echo "checked"; ?>>gcl<br>
<p>
<hr color=black>
<p align=center>
<input type="Radio" name="nrlog" value="0" <?php if ($rlog=="0") echo "checked"; ?>>don't log refering URLs
<input type="Radio" name="nrlog" value="1" <?php if ($rlog=="1") echo "checked"; ?>>log refering URLs
<p>
<hr color=black>
<p align=center>
Webmaster Signup and Stats Control:<br>
<input type="Radio" name="nswmst" value="1" <?php if ($swmst=="1") echo "checked"; ?>>enable webmaster signup
<input type="Radio" name="nswmst" value="0" <?php if ($swmst=="0") echo "checked"; ?>>disable webmaster signup<br>
<input type="Radio" name="newmst" value="1" <?php if ($ewmst=="1") echo "checked"; ?>>enable webmaster stats
<input type="Radio" name="newmst" value="0" <?php if ($ewmst=="0") echo "checked"; ?>>disable webmaster stats<br><br>
If Webmaster Stats is enabled:<br>
<input type="Radio" name="nrwmst" value="1" <?php if ($rwmst=="1") echo "checked"; ?>>show raw in
<input type="Radio" name="nrwmst" value="0" <?php if ($rwmst=="0") echo "checked"; ?>>don't show raw in<br>
<input type="Radio" name="nuwmst" value="1" <?php if ($uwmst=="1") echo "checked"; ?>>show unique in
<input type="Radio" name="nuwmst" value="0" <?php if ($uwmst=="0") echo "checked"; ?>>don't show unique in<br>
<input type="Radio" name="ncwmst" value="1" <?php if ($cwmst=="1") echo "checked"; ?>>show clicks
<input type="Radio" name="ncwmst" value="0" <?php if ($cwmst=="0") echo "checked"; ?>>don't show clicks<br>
<input type="Radio" name="ngwmst" value="1" <?php if ($gwmst=="1") echo "checked"; ?>>show gallery clicks
<input type="Radio" name="ngwmst" value="0" <?php if ($gwmst=="0") echo "checked"; ?>>don't show gallery clicks<br>
<input type="Radio" name="npwmst" value="1" <?php if ($pwmst=="1") echo "checked"; ?>>show productivity
<input type="Radio" name="npwmst" value="0" <?php if ($pwmst=="0") echo "checked"; ?>>don't show productivity<br>
<input type="Radio" name="ntwmst" value="1" <?php if ($twmst=="1") echo "checked"; ?>>show total
<input type="Radio" name="ntwmst" value="0" <?php if ($twmst=="0") echo "checked"; ?>>don't show total<br>
</p>
<hr color=black>
<p align=center>
admin password: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=admpass value="<?php echo $msetstr["admpass"]; ?>" size="10" maxlength="10"><br>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=cancel style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=set style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="set") {
?>
<html>
<head>
<title>Traffic-Drive Settings</title>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
</head>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Settings</font>
</td></tr></table>
<?php
ignore_user_abort(true);
$q = "update mset set murl='$murl', mtitle='$mtitle', memail='$memail', micq='$micq', dr='$dr', df='$df', dt='$dt', dtr='$dtr', dotr='$dotr', admpass='$admpass', dhtr='$dhtr', dhotr='$dhotr' where k='aHR0cDovL3d3dy5hbGxtYXh4eC5jb20vZnJlZQ=='";
$r = mysql_query ($q) or die ("Update mset failed");
$q = "update tr set u='$exturl' where d='nourl'";
$r = mysql_query ($q) or die ("Update tr failed");
$pw=$admpass;
$fp = fopen("vars.php" , "w") or die ("Can not create vars.php");
if ($ndogflag=='1') $ndog=$ndg; else $ndog=eregi_replace(" ","",$ndog);
$nipref=pathinfo("$murl");$ipref=$nipref["dirname"]."/";
$vars = "<?php\n"."\$hst='$hst';\n"."\$usr='$usr';\n"."\$psw='$psw';\n"."\$db='$db';\n"."\$gp='$ngp';\n"."\$rt='$nrt';\n"."\$dg='$ndg';\n"."\$topmax='$ntopmax';\n"."\$toporder='$ntoporder';\n"."\$dln='$ndln';\n"."\$ript='$nript';\n"."\$rlog='$nrlog';\n"."\$dog='$ndog';\n"."\$dgfc='$ndgfc';\n"."\$dgtr='$ndgtr';\n"."\$iext='$niext';\n"."\$itype='$nitype';\n"."\$ipref='$ipref';\n"."?>";
fwrite($fp, $vars);
fclose($fp);
$fp = fopen("wmvars.php" , "w") or die ("Can not create wmvars.php");
$wmvars = "<?php\n"."\$elnwm='$nelnwm';\n"."\$dlnwm='$ndlnwm';\n"."\$swmst='$nswmst';\n"."\$ewmst='$newmst';\n"."\$rwmst='$nrwmst';\n"."\$uwmst='$nuwmst';\n"."\$cwmst='$ncwmst';\n"."\$gwmst='$ngwmst';\n"."\$pwmst='$npwmst';\n"."\$twmst='$ntwmst';\n"."\$tlwm='$ntlwm';\n";
if ($ngpwmflag==1) $wmvars=$wmvars."\$gpwm='$ndg';\n";
else {
$wmvars=$wmvars."\$gpwm='n';\n";
for ($i=1; $i<10; $i++) {
$ngpwma[$i]=trim($ngpwma[$i]);
if ($ngpwma[$i]!="") $wmvars=$wmvars."\$gpwma[$i]='$ngpwma[$i]';\n";
}
}
$wmvars=$wmvars."?>";
fwrite($fp, $wmvars);fclose($fp);
ignore_user_abort(false);
?>
<p align=center>Done</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="stats") {
$q = "select mtitle from mset where k='aHR0cDovL3d3dy5hbGxtYXh4eC5jb20vZnJlZQ=='";
$r = mysql_query($q);
$msetstr = mysql_fetch_array($r);
?>
<html>
<head>
<title>Traffic-Drive Total Stats</title>
<style type="text/css">
<!--
BODY{font-size:xx-small;font-family:arial;color:black;background-color:white;}
a { color:black;text-decoration:underline;font-weight:bold} 
-->
</style>
</head>
<body bgcolor=white text=black link=black>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=4><b><?php echo $msetstr["mtitle"]; ?></b><br>total stats<br></font><font size=2>powered by <a href="http://www.traffic-drive.com/scripts">Traffic-Drive CJ/TGP v5.14 free</a>, get your traffic machine <a href="http://www.traffic-drive.com/scripts">here</a></font>
</td></tr></table><br>
<table align=center width=750 bgcolor=silver><tr>
<td width=30 bgcolor=white align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>hr</font></td></tr></table></td>
<td width=40 bgcolor=#80FFFF align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>rin</font></td></tr></table></td>
<td width=40 bgcolor=#80FFFF align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>uin</font></td></tr></table></td>
<td width=40 bgcolor=#00FF80 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>cl</font></td></tr></table></td>
<td width=40 bgcolor=#00FF80 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>gcl</font></td></tr></table></td>
<td width=40 bgcolor=#FFFF00 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>out</font></td></tr></table></td>
<td width=40 bgcolor=#FF80C0 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>pr%</font></td></tr></table></td>
<td>
<table align=center width=480 cellpadding=0 cellspacing=0>
<tr><td align=center><font face=arial size=2><b>total</b> (last 23 hours)</font></td></tr>
</table>
</td>
</tr></table>
<?php
$maxv=1;
$hr=date("G");
for($i=0; $i<24; $i++) {
$q = "select * from hr";
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
$q = "select * from hr";
$r = mysql_query ($q);
$hrin=0; $huin=0; $hcl=0; $hgcl=0; $hout=0;
while ($row = mysql_fetch_array($r)) {
$hrin=$hrin+$row["rin$i"]; $huin=$huin+$row["uin$i"]; $hcl=$hcl+$row["cl$i"]; $hgcl=$hgcl+$row["gcl$i"]; $hout=$hout+$row["out$i"];
}
if ($hrin == 0) { $hpr=0; } else { $hpr=ceil($hcl/$hrin*100); }
if ($i==$hr) $hcolor="red"; else $hcolor="white";
?>
<table align=center width=750 bgcolor=silver><tr>
<td width=30 bgcolor=<?php echo $hcolor; ?> align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1><?php echo $i; ?></font></td></tr></table></td>
<td width=40 bgcolor=#80FFFF align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1><?php echo $hrin; ?></font></td></tr></table></td>
<td width=40 bgcolor=#80FFFF align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1><?php echo $huin; ?></font></td></tr></table></td>
<td width=40 bgcolor=#00FF80 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1><?php echo $hcl; ?></font></td></tr></table></td>
<td width=40 bgcolor=#00FF80 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1><?php echo $hgcl; ?></font></td></tr></table></td>
<td width=40 bgcolor=#FFFF00 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1><?php echo $hout; ?></font></td></tr></table></td>
<td width=40 bgcolor=#FF80C0 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1><?php echo $hpr; ?></font></td></tr></table></td>
<td>
<table align=center width=480 cellpadding=0 cellspacing=0>
<tr><td height=3 width=<?php echo ceil($hrin*480/$maxv); ?> bgcolor="#80FFFF"></td><td width=<?php echo 480-ceil($hrin*480/$maxv); ?> bgcolor=black></td></tr>
</table>
<table align=center width=480 cellpadding=0 cellspacing=0>
<tr><td height=3 width=<?php echo ceil($huin*480/$maxv); ?> bgcolor="#80FFFF"></td><td width=<?php echo 480-ceil($huin*480/$maxv); ?> bgcolor=black></td></tr>
</table>
<table align=center width=480 cellpadding=0 cellspacing=0>
<tr><td height=3 width=<?php echo ceil($hcl*480/$maxv); ?> bgcolor="#00FF80"></td><td width=<?php echo 480-ceil($hcl*480/$maxv); ?> bgcolor=black></td></tr>
</table>
<table align=center width=480 cellpadding=0 cellspacing=0>
<tr><td height=3 width=<?php echo ceil($hgcl*480/$maxv); ?> bgcolor="#00FF80"></td><td width=<?php echo 480-ceil($hgcl*480/$maxv); ?> bgcolor=black></td></tr>
</table>
<table align=center width=480 cellpadding=0 cellspacing=0>
<tr><td height=3 width=<?php echo ceil($hout*480/$maxv); ?> bgcolor="#FFFF00"></td><td width=<?php echo 480-ceil($hout*480/$maxv); ?> bgcolor=black></td></tr>
</table>
</td>
</tr></table>
<?php
}
?>
<table align=center width=750 bgcolor=silver border=0><tr>
<td width=26 align=center bgcolor=red><font face=arial size=1>XX</font></td>
<td><font size=2 face=arial>current hour, next after - hours of previous day</font></td>
</tr></table>
<?php
$q = "select SUM(rin) as srin,SUM(uin) as suin,SUM(cl) as scl,SUM(gcl) as sgcl,SUM(out) as sout from tr";
$r = mysql_query ($q);
$trrow = mysql_fetch_array($r);
?>
<table align=center width=750 bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>total <b>all</b> -
<?php
echo " rin: ".$trrow["srin"]." ";
echo " uin: ".$trrow["suin"]." ";
echo " cl: ".$trrow["scl"]." ";
echo " gcl: ".$trrow["sgcl"]." ";
echo " out: ".$trrow["sout"]." ";
if ($trrow["srin"]==0) echo " pr%: 0"; else {echo "pr%: ".ceil($trrow["scl"]/$trrow["srin"]*100);};
?>
</font>
</td></tr></table>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=stats style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="sts") {
$q = "select mtitle from mset where k='aHR0cDovL3d3dy5hbGxtYXh4eC5jb20vZnJlZQ=='";
$r = mysql_query($q);
$msetstr = mysql_fetch_array($r);
?>
<html>
<head>
<title>Traffic-Drive Domain Stats</title>
<style type="text/css">
<!--
BODY{font-size:xx-small;font-family:arial;color:black;background-color:white;}
a { color:black;text-decoration:underline;font-weight:bold} 
-->
</style>
</head>
<body bgcolor=white text=black link=black>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=4><b><?php echo $msetstr["mtitle"]; ?></b><br><?php echo $d; ?> stats<br></font><font size=2>powered by <a href="http://www.traffic-drive.com/scripts">Traffic-Drive CJ/TGP v5.14 free</a>, get your traffic machine <a href="http://www.traffic-drive.com/scripts">here</a></font>
</td></tr></table><br>
<table align=center width=750 bgcolor=silver><tr>
<td width=30 bgcolor=white align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>hr</font></td></tr></table></td>
<td width=40 bgcolor=#80FFFF align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>rin</font></td></tr></table></td>
<td width=40 bgcolor=#80FFFF align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>uin</font></td></tr></table></td>
<td width=40 bgcolor=#00FF80 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>cl</font></td></tr></table></td>
<td width=40 bgcolor=#00FF80 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>gcl</font></td></tr></table></td>
<td width=40 bgcolor=#FFFF00 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>out</font></td></tr></table></td>
<td width=40 bgcolor=#FF80C0 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1>pr%</font></td></tr></table></td>
<td>
<table align=center width=480 cellpadding=0 cellspacing=0>
<tr><td align=center><font face=arial size=2><b><?php echo $d; ?></b> (last 23 hours)</font></td></tr>
</table>
</td>
</tr></table>
<?php
$maxv=1;
$hr=date("G");
for($i=0; $i<24; $i++) {
$q = "select * from hr where d='$d'";
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
$q = "select * from hr where d='$d'";
$r = mysql_query ($q);
$hrin=0; $huin=0; $hcl=0; $hgcl=0; $hout=0;
while ($row = mysql_fetch_array($r)) {
$hrin=$hrin+$row["rin$i"]; $huin=$huin+$row["uin$i"]; $hcl=$hcl+$row["cl$i"]; $hgcl=$hgcl+$row["gcl$i"]; $hout=$hout+$row["out$i"];
}
if ($hrin == 0) { $hpr=0; } else { $hpr=ceil($hcl/$hrin*100); }
if ($i==$hr) $hcolor="red"; else $hcolor="white";
?>
<table align=center width=750 bgcolor=silver><tr>
<td width=30 bgcolor=<?php echo $hcolor; ?> align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1><?php echo $i; ?></font></td></tr></table></td>
<td width=40 bgcolor=#80FFFF align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1><?php echo $hrin; ?></font></td></tr></table></td>
<td width=40 bgcolor=#80FFFF align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1><?php echo $huin; ?></font></td></tr></table></td>
<td width=40 bgcolor=#00FF80 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1><?php echo $hcl; ?></font></td></tr></table></td>
<td width=40 bgcolor=#00FF80 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1><?php echo $hgcl; ?></font></td></tr></table></td>
<td width=40 bgcolor=#FFFF00 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1><?php echo $hout; ?></font></td></tr></table></td>
<td width=40 bgcolor=#FF80C0 align=right><table cellpadding=0 cellspacing=0><tr><td align=center><font face=arial size=1><?php echo $hpr; ?></font></td></tr></table></td>
<td>
<table align=center width=480 cellpadding=0 cellspacing=0>
<tr><td height=3 width=<?php echo ceil($hrin*480/$maxv); ?> bgcolor="#80FFFF"></td><td width=<?php echo 480-ceil($hrin*480/$maxv); ?> bgcolor=black></td></tr>
</table>
<table align=center width=480 cellpadding=0 cellspacing=0>
<tr><td height=3 width=<?php echo ceil($huin*480/$maxv); ?> bgcolor="#80FFFF"></td><td width=<?php echo 480-ceil($huin*480/$maxv); ?> bgcolor=black></td></tr>
</table>
<table align=center width=480 cellpadding=0 cellspacing=0>
<tr><td height=3 width=<?php echo ceil($hcl*480/$maxv); ?> bgcolor="#00FF80"></td><td width=<?php echo 480-ceil($hcl*480/$maxv); ?> bgcolor=black></td></tr>
</table>
<table align=center width=480 cellpadding=0 cellspacing=0>
<tr><td height=3 width=<?php echo ceil($hgcl*480/$maxv); ?> bgcolor="#00FF80"></td><td width=<?php echo 480-ceil($hgcl*480/$maxv); ?> bgcolor=black></td></tr>
</table>
<table align=center width=480 cellpadding=0 cellspacing=0>
<tr><td height=3 width=<?php echo ceil($hout*480/$maxv); ?> bgcolor="#FFFF00"></td><td width=<?php echo 480-ceil($hout*480/$maxv); ?> bgcolor=black></td></tr>
</table>
</td>
</tr></table>
<?php
}
?>
<table align=center width=750 bgcolor=silver border=0><tr>
<td width=26 align=center bgcolor=red><font face=arial size=1>XX</font></td>
<td><font size=2 face=arial>current hour, next after - hours of previous day</font></td>
</tr></table>
<?php
$q = "select rin,uin,cl,gcl,out from tr where d='$d'";
$r = mysql_query ($q);
$trrow = mysql_fetch_array($r);
?>
<table align=center width=750 bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>total <b><?php echo $d; ?></b> -
<?php
echo " rin: ".$trrow["rin"]." ";
echo " uin: ".$trrow["uin"]." ";
echo " cl: ".$trrow["cl"]." ";
echo " gcl: ".$trrow["gcl"]." ";
echo " out: ".$trrow["out"]." ";
if ($trrow["rin"]==0) echo " pr%: 0"; else {echo "pr%: ".ceil($trrow["cl"]/$trrow["rin"]*100);};
?>
</font>
</td></tr></table>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=hidden name=d value="<?php echo $d; ?>">
<input type=submit name=action value=sts style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="res") {
?>
<html>
<head>
<title>Traffic-Drive Reset Hourly Stats</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Reset Hourly Stats</font>
</td></tr></table>
<form method="POST">
<p align=center>
Reset last 24 hours Stats for domain <b><?php echo $d; ?></b><br>
A You Sure?
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=hidden name=d value="<?php echo $d; ?>">
<input type=submit name=action value=cancel style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=reset style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="reset") {
?>
<html>
<head>
<title>Traffic-Drive Reset Hourly Stats</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Reset Hourly Stats</font>
</td></tr></table>
<?php
ignore_user_abort(true);
for ($i=0; $i<24; $i++) {
$q = "update hr set rin$i='0', uin$i='0', cl$i='0', gcl$i='0', out$i='0' where d='$d'";
$r = mysql_query ($q) or die ("Can't update hr");
$q = "update tr set owed='0',dowed='0',drin='0',duin='0',dcl='0',dgcl='0',dout='0' where d='$d'";
$r = mysql_query ($q) or die ("Can't update tr");
}
ignore_user_abort(false);
?>
<p align=center>Done</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="add") {
?>
<html>
<head>
<title>Traffic-Drive Add Trade</title>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
</head>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Add Trade</font>
</td></tr></table>
<?php
$q = "select * from mset where k='aHR0cDovL3d3dy5hbGxtYXh4eC5jb20vZnJlZQ=='";
$r = mysql_query ($q) or die ("Select from tr failed");
$msetstr = mysql_fetch_array($r);
?>
<form method=POST>
<p align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
domain: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=ydom value="newdomain.com" size="30" maxlength="100"><br>
url: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=yurl value="http://www.newurl.com" size="30" maxlength="100">
title: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=ytitle value="New Title" size="30" maxlength="100"><br>
email: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=yemail value="new@email.com" size="30" maxlength="100">
icq: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=yicq value="NewIcqNumber" size="20" maxlength="20">
nick: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=ynick value="NewNick" size="20" maxlength="20"><br>
password: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=ypsw value="NewPassword" size="10" maxlength="10"><br>
</p>
<hr color=black>
<p align=center>
Total:<br>
rin: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=rin value="0" size="6" maxlength="6">
uin: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=uin value="0" size="6" maxlength="6">
cl: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=cl value="0" size="6" maxlength="6">
gcl: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=gcl value="0" size="6" maxlength="6">
out: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=out value="0" size="6" maxlength="6"><br>
trade type(a,c,r,u,f):
<input type="Radio" name=t value="a" <?php if ($msetstr["dt"]=="a") echo "checked"; ?>>average raw_in-clicks
<input type="Radio" name=t value="c" <?php if ($msetstr["dt"]=="c") echo "checked"; ?>>clicks(productivity)
<input type="Radio" name=t value="r" <?php if ($msetstr["dt"]=="r") echo "checked"; ?>>raw in
<input type="Radio" name=t value="u" <?php if ($msetstr["dt"]=="u") echo "checked"; ?>>unique in
<input type="Radio" name=t value="f" <?php if ($msetstr["dt"]=="f") echo "checked"; ?>>force only<br><br>
ratio: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=ratio value="<?php echo $msetstr["dr"]; ?>" size="3" maxlength="3"><br>
in trigger: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=tr value="<?php echo $msetstr["dtr"]; ?>" size="3" maxlength="3">
out trigger: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=otr value="<?php echo $msetstr["dotr"]; ?>" size="3" maxlength="3"><br>
previous hour in trigger: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=htr value="<?php echo $msetstr["dhtr"]; ?>" size="3" maxlength="3">
previous hour out trigger: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=hotr value="<?php echo $msetstr["dhotr"]; ?>" size="3" maxlength="3"><br>
force right now: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=hf value="<?php echo $msetstr["df"]; ?>" size="3" maxlength="3"><br>
Target:
<input type="Radio" name=ln value="a" <?php if ($dln=="a") echo "checked"; ?>>all
<input type="Radio" name=ln value="p" <?php if ($dln=="p") echo "checked"; ?>>pictures only
<input type="Radio" name=ln value="m" <?php if ($dln=="m") echo "checked"; ?>>movies only<br>
actual group (a): <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=actgrp value="<?php echo $dg; ?>" size="1" maxlength="1"><br>
<input type="Radio" name=etr value="1" checked>check triggers for current hour
<input type="Radio" name=etr value="0">don't check triggers for current hour<br><br>
<input type="Radio" name=itl value="1" checked>include in top list
<input type="Radio" name=itl value="0">don't include in top list<br><br>
individual pages prefix: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=fpr value="" size="10" maxlength="10"><br>
</p>
<table align=center><tr><td align=center colspan=12>
<font size=2><b>Hourly Groups(st) and Forces(fr) (red - current hour):</b></font>
</td></tr><tr>
<?php
$curhr = date("G");
for ($hr=0; $hr<24; $hr++) {
if ($curhr != $hr) $marker="black"; else $marker="red";
if ($hr == 12) echo "</tr><tr><td colspan=24><hr color=black></td></tr><tr>";
?>
<td width=60>
<font size="1" color=<?php echo $marker; ?>>st<?php echo $hr; ?><br></font><input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=<?php echo "a[$hr]"; ?> value="<?php echo $dg; ?>" size="1" maxlength="1"><br>
<font size="1" color=<?php echo $marker; ?>>fr<?php echo $hr; ?><br></font><input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=<?php echo "f[$hr]"; ?> value="<?php echo $msetstr["df"]; ?>" size="3" maxlength="3">
</td>
<?php
}
?>
</tr></table>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=submit name=action value=cancel style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=add_final style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="add_final") {
?>
<html>
<head>
<title>Traffic-Drive Add Trade</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Add Trade</font>
</td></tr></table>
<p align=center>
<?php
$ydom = trim($ydom);
$q = "select d from tr where d='$ydom'";
$r = mysql_query ($q) or die ("Select from tr failed");
if (mysql_num_rows ($r)==0) {
ignore_user_abort(true);
$curhr = date("G");
$q1 = "insert into hr values ('$ydom'";
for($i=0; $i<24; $i++) {
if ($i==$curhr) $achr=$a[$i];
$q1 = $q1 . ",'0','0','0','0','0','$f[$i]','$a[$i]'";
}
$q1 = $q1 . ")";
$r1 = mysql_query ($q1) or die ("Insert into hr failed");
$q = "insert into tr values ('$ydom','$yurl','$ytitle','$yemail','$yicq','$ynick','$ypsw','$rin','$uin','$cl','$gcl','$out','$ratio', '$t', '$tr', '$otr', '0','0','$actgrp', '$hf', '$ln','0','0','0','0','0','$htr','$hotr','$etr','$itl','$fpr')";	
$r = mysql_query ($q) or die ("Insert into tr failed");
ignore_user_abort(false);
echo "Done";
}
else echo "Domain already in database";
?>
</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="res_all") {
?>
<html>
<head>
<title>Traffic-Drive Reset Hourly Stats For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Reset Hourly Stats For All Domains</font>
</td></tr></table>
<form method="POST">
<p align=center>
Reset last 24 hours Stats for all domains?</b><br>
A You Sure?
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=cancel style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=reset_all style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="reset_all") {
?>
<html>
<head>
<title>Traffic-Drive Reset Hourly Stats For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Reset Hourly Stats For All</font>
</td></tr></table>
<?php
ignore_user_abort(true);
for ($i=0; $i<24; $i++) {
$q = "update hr set rin$i='0', uin$i='0', cl$i='0', gcl$i='0', out$i='0'";
$r = mysql_query ($q) or die ("Can't update hr");
$q = "update tr set owed='0',dowed='0',drin='0',duin='0',dcl='0',dgcl='0',dout='0'";
$r = mysql_query ($q) or die ("Can't update tr");
}
ignore_user_abort(false);
?>
<p align=center>Done</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="tp") {
?>
<html>
<head>
<title>Traffic-Drive Set tp For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Set <b>tp</b> For All Domains</font>
</td></tr></table>
<form method="POST">
<p align=center>
Set <b>tp</b> for all domains<br><br>
trade type(a,c,r,u):
<input type="Radio" name=t value="a" checked>average raw_in-clicks
<input type="Radio" name=t value="c">clicks(productivity)
<input type="Radio" name=t value="r">raw in
<input type="Radio" name=t value="u">unique in<br><br>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=cancel style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=tp_all style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="tp_all") {
?>
<html>
<head>
<title>Traffic-Drive Set tp For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Set <b>tp</b> For All Domains</font>
</td></tr></table>
<?php
ignore_user_abort(true);
$q = "update tr set t='$t' where d!='nourl'";
$r = mysql_query ($q) or die ("Can't update tr");
ignore_user_abort(false);
?>
<p align=center>Done</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="tr") {
?>
<html>
<head>
<title>Traffic-Drive Set tr For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Set <b>tr</b> For All Domains</font>
</td></tr></table>
<form method="POST">
<p align=center>
Set <b>tr</b> for all domains<br><br>
Target:
<input type="Radio" name=ln value="a" checked>all
<input type="Radio" name=ln value="p">pictures only
<input type="Radio" name=ln value="m">movies only<br>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=cancel style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=tr_all style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="tr_all") {
?>
<html>
<head>
<title>Traffic-Drive Set tr For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Set <b>tr</b> For All Domains</font>
</td></tr></table>
<?php
ignore_user_abort(true);
$q = "update tr set ln='$ln' where d!='nourl'";
$r = mysql_query ($q) or die ("Can't update tr");
ignore_user_abort(false);
?>
<p align=center>Done</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="rat") {
?>
<html>
<head>
<title>Traffic-Drive Set rat For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Set <b>rat</b> For All Domains</font>
</td></tr></table>
<form method="POST">
<p align=center>
Set <b>rat</b> for all domains<br><br>
ratio: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=ratio value="110" size="3" maxlength="3">
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=cancel style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=rat_all style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="rat_all") {
?>
<html>
<head>
<title>Traffic-Drive Set rat For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Set <b>rat</b> For All Domains</font>
</td></tr></table>
<?php
ignore_user_abort(true);
$q = "update tr set r='$ratio' where d!='nourl'";
$r = mysql_query ($q) or die ("Can't update tr");
ignore_user_abort(false);
?>
<p align=center>Done</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="chf") {
?>
<html>
<head>
<title>Traffic-Drive Set chf For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Set <b>chf</b> For All Domains</font>
</td></tr></table>
<form method="POST">
<p align=center>
Set <b>chf</b> for all domains<br><br>
chf: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=chf value="0" size="3" maxlength="3">
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=cancel style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=chf_all style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="chf_all") {
?>
<html>
<head>
<title>Traffic-Drive Set chf For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Set <b>chf</b> For All Domains</font>
</td></tr></table>
<?php
ignore_user_abort(true);
$q = "update tr set hf='$chf' where d!='nourl'";
$r = mysql_query ($q) or die ("Can't update tr");
ignore_user_abort(false);
?>
<p align=center>Done</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="st") {
?>
<html>
<head>
<title>Traffic-Drive Set st For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Set <b>st</b> For All Domains</font>
</td></tr></table>
<form method="POST">
<p align=center>
Set <b>st</b> for all domains<br><br>
<table><tr>
<?php
$curhr = date("G");
for ($hr=0; $hr<24; $hr++) {
if ($curhr != $hr) $marker="black"; else $marker="red";
if ($hr == 12) echo "</tr><tr><td colspan=24><hr color=black></td></tr><tr>";
?>
<td width=60>
<font size="1" color=<?php echo $marker; ?>>st<?php echo $hr; ?><br></font><input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=<?php echo "a[$hr]"; ?> value="<?php echo $dg; ?>" size="1" maxlength="1">
</td>
<?php
}
?>
</tr></table>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=cancel style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=st_all style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="st_all") {
?>
<html>
<head>
<title>Traffic-Drive Set st For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Set <b>st</b> For All Domains</font>
</td></tr></table>
<?php
ignore_user_abort(true);
$q1 = "update hr set ";
for($i=0; $i<24; $i++) {
$q1 = $q1 . " a$i='$a[$i]',";
}
$q1 = substr($q1, 0, strlen($q1)-1);
$q1 = $q1 . " where d!='nourl'";
$r1 = mysql_query ($q1) or die ("Can't update hr");
ignore_user_abort(false);
?>
<p align=center>Done</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="fr") {
?>
<html>
<head>
<title>Traffic-Drive Set fr For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Set <b>fr</b> For All Domains</font>
</td></tr></table>
<form method="POST">
<p align=center>
Set <b>fr</b> for all domains<br><br>
<table><tr>
<?php
$curhr = date("G");
for ($hr=0; $hr<24; $hr++) {
if ($curhr != $hr) $marker="black"; else $marker="red";
if ($hr == 12) echo "</tr><tr><td colspan=24><hr color=black></td></tr><tr>";
?>
<td width=60>
<font size="1" color=<?php echo $marker; ?>>fr<?php echo $hr; ?><br></font><input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=<?php echo "f[$hr]"; ?> value="0" size="3" maxlength="3">
</td>
<?php
}
?>
</tr></table>
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=cancel style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=fr_all style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="fr_all") {
?>
<html>
<head>
<title>Traffic-Drive Set fr For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Set <b>fr</b> For All Domains</font>
</td></tr></table>
<?php
ignore_user_abort(true);
$q1 = "update hr set ";
for($i=0; $i<24; $i++) {
$q1 = $q1 . " f$i='$f[$i]',";
}
$q1 = substr($q1, 0, strlen($q1)-1);
$q1 = $q1 . " where d!='nourl'";
$r1 = mysql_query ($q1) or die ("Can't update hr");
ignore_user_abort(false);
?>
<p align=center>Done</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="in_tr") {
?>
<html>
<head>
<title>Traffic-Drive Set in trigger For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Set <b>in trigger</b> For All Domains</font>
</td></tr></table>
<form method="POST">
<p align=center>
Set <b>in trigger</b> for all domains<br><br>
in trigger: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=in_trigger value="0" size="3" maxlength="3">
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=cancel style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=in_trigger_all style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="in_trigger_all") {
?>
<html>
<head>
<title>Traffic-Drive Set in trigger For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Set <b>in trigger</b> For All Domains</font>
</td></tr></table>
<?php
ignore_user_abort(true);
$q = "update tr set tr='$in_trigger' where d!='nourl'";
$r = mysql_query ($q) or die ("Can't update tr");
ignore_user_abort(false);
?>
<p align=center>Done</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="out_tr") {
?>
<html>
<head>
<title>Traffic-Drive Set out trigger For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Set <b>out trigger</b> For All Domains</font>
</td></tr></table>
<form method="POST">
<p align=center>
Set <b>out trigger</b> for all domains<br><br>
out trigger: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=out_trigger value="0" size="3" maxlength="3">
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=cancel style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=out_trigger_all style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="out_trigger_all") {
?>
<html>
<head>
<title>Traffic-Drive Set out trigger For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Set <b>out trigger</b> For All Domains</font>
</td></tr></table>
<?php
ignore_user_abort(true);
$q = "update tr set otr='$out_trigger' where d!='nourl'";
$r = mysql_query ($q) or die ("Can't update tr");
ignore_user_abort(false);
?>
<p align=center>Done</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="h_in_tr") {
?>
<html>
<head>
<title>Traffic-Drive Set previous hour in trigger For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Set <b>previous hour in trigger</b> For All Domains</font>
</td></tr></table>
<form method="POST">
<p align=center>
Set <b>previous hour in trigger</b> for all domains<br><br>
previous hour in trigger: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=h_in_trigger value="0" size="3" maxlength="3">
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=cancel style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=h_in_trigger_all style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="h_in_trigger_all") {
?>
<html>
<head>
<title>Traffic-Drive Set previous hour in trigger For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Set <b>previous hour in trigger</b> For All Domains</font>
</td></tr></table>
<?php
ignore_user_abort(true);
$q = "update tr set htr='$h_in_trigger' where d!='nourl'";
$r = mysql_query ($q) or die ("Can't update tr");
ignore_user_abort(false);
?>
<p align=center>Done</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="h_out_tr") {
?>
<html>
<head>
<title>Traffic-Drive Set previous hour out trigger For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Set <b>previous hour out trigger</b> For All Domains</font>
</td></tr></table>
<form method="POST">
<p align=center>
Set <b>previous hour out trigger</b> for all domains<br><br>
previous hour out trigger: <input type=text style="font-size: x-small; font-family: arial; background-color: white; border-width: 1; border-color: Black; border-style: solid;" name=h_out_trigger value="0" size="3" maxlength="3">
</p>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=cancel style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
<input type=submit name=action value=h_out_trigger_all style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="h_out_trigger_all") {
?>
<html>
<head>
<title>Traffic-Drive Set previous hour out trigger For All Domains</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive Set <b>previous hour out trigger</b> For All Domains</font>
</td></tr></table>
<?php
ignore_user_abort(true);
$q = "update tr set hotr='$h_out_trigger' where d!='nourl'";
$r = mysql_query ($q) or die ("Can't update tr");
ignore_user_abort(false);
?>
<p align=center>Done</p>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="IP_all") {
?>
<html>
<head>
<title>Traffic-Drive IP-log</title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive IP-log For All Domains</font>
</td></tr></table><br><br>
<table align=center width=300><tr>
<?php
echo "<tr><td align=center colspan=3><b>From (order by time)</b></td></tr>";
$r=mysql_query("select * from ipfrom order by tm");
while ($s=mysql_fetch_array($r)) echo "<tr><td width=100>".date("H:i",$s["tm"])."</td><td width=100>".$s["ip"]."</td><td width=100><b>".$s["d"]."</b></td></tr>";
echo "<tr><td align=center colspan=3><b>To (order by time)</b></td></tr>";
$r=mysql_query("select * from ipto order by tm");
while ($s=mysql_fetch_array($r)) echo "<tr><td width=100>".date("H:i",$s["tm"])."</td><td width=100>".$s["ip"]."</td><td width=100><b>".$s["d"]."</b></td></tr>";
?>
</tr></table>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
if ($action=="IP") {
?>
<html>
<head>
<title>Traffic-Drive IP-log for <?php echo "$d"; ?></title>
</head>
<style type="text/css">
<!--
BODY{font-size:x-small;font-family:arial;color:black;background-color:white;}
a:link { color:black;text-decoration:none;font-weight:bold} 
-->
</style>
<body>
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<font size=2>Traffic-Drive IP-log For <b><?php echo "$d"; ?></b></font>
</td></tr></table><br><br>
<table align=center width=600>
<tr><td width=200><b>From</b></td><td width=200><b>IP</b></td><td width=200><b>To</b></td></tr>
<tr><td colspan=3><hr color=black></td></tr>
<?php
$q="select * from ipfrom where d='$d' order by tm";
$r=mysql_query($q);
if (mysql_num_rows($r)>0) {
while ($s=mysql_fetch_array($r)) {
$ipfrom=$s["ip"]; $tm=$s["tm"];
echo "<tr><td width=200>first visits from<br>";
$q1="select * from ipfrom where (ip='$ipfrom' and tm<'$tm') order by tm";
$r1=mysql_query($q1);
if (mysql_num_rows($r1)>0) {
  while ($s1=mysql_fetch_array($r1)) echo date("H:i",$s1['tm'])." <b>".$s1['d']."</b><br>";
} else echo "<b>unique</b>";
echo "<hr color=black>last visits from<br>";
$q1="select * from ipfrom where (ip='$ipfrom' and tm>'$tm') order by tm";
$r1=mysql_query($q1);
if (mysql_num_rows($r1)>0) {
  while ($s1=mysql_fetch_array($r1)) echo date("H:i",$s1['tm'])." <b>".$s1['d']."</b><br>";
} else echo "<b>none</b>";
echo "</td><td width=200>".date("H:i",$tm)." <b>".$ipfrom."</b></td><td width=200>";
$q1="select * from ipto where ip='$ipfrom' order by tm";
$r1=mysql_query($q1);
if (mysql_num_rows($r1)>0) {
  while ($s1=mysql_fetch_array($r1)) echo date("H:i",$s1['tm'])." <b>".$s1['d']."</b><br>";
} else echo "<b>none</b>";
echo "</td></tr>";
echo "<tr><td colspan=3><hr color=black></td></tr>";
}
} else echo "none";
?>
</table>
<form method="POST">
<table align=center width=90% bgcolor=silver border=1 bordercolor=black cellspacing=0><tr><td width=100% align=center>
<input type=hidden name=pw value="<?php echo $pw; ?>">
<input type=submit name=action value=back style="border-width: 1; border-style: solid; font-size: x-small; font-family: arial">
</td></tr></table>
</form>
</body>
</html>
<?php
mysql_close ($link);
exit;
}
?>
