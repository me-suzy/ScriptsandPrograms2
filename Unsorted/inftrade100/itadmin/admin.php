<?php
require("../it/dbsettings.php");
require("../it/update.php");

$msg = "";

if ( $_GET["daystats"] ) { daystats(); }
if ( $_GET["addsite"] ) { addsite(); }
if ( $_GET["settings"] ) { settings(); }
if ( $_GET["editsite"] ) { editsite(); }
if ( $_GET["delsite"] ) { delsite(); }
if ( $_GET["statsite"] ) { statsite(); }
if ( $_GET["iplog"] ) { iplog(); }
if ( $_GET["reflog"] ) { reflog(); }
if ( $_GET["blacklist"] ) { blacklist(); }
if ( $_GET["history"] ) { history(); }
if ( $_GET["updatetop"] ) { updatetop(); }

if ( $_POST["addsite2"] ) { addsite2(); }
if ( $_POST["editsite2"] ) { editsite2(); }
if ( $_POST["delsite2"] ) { delsite2(); }
if ( $_POST["savesettings"] ) { savesettings(); }
if ( $_POST["addblacklist"] ) { addblacklist(); }
if ( $_POST["delblacklist"] ) { delblacklist(); }
exit;

function daystats() {
global $db_host, $db_user, $db_pw, $db_database;

$timmar = array("00","01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23");
$timmard = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23);

$tid = localtime(time());
$thour = $tid[2];

$link = mysql_connect($db_host, $db_user, $db_pw)
	or die("Could not connect : " . mysql_error());
mysql_select_db($db_database) or die(mysql_error());

$query = "SELECT sum(in0),sum(in1),sum(in2),sum(in3),sum(in4),sum(in5),sum(in6),sum(in7),sum(in8),sum(in9),sum(in10),sum(in11),sum(in12),sum(in13),sum(in14),sum(in15),sum(in16),sum(in17),sum(in18),sum(in19),sum(in20),sum(in21),sum(in22),sum(in23) FROM sites"; 
$result = mysql_query($query) or die(mysql_error());
$in_stats = mysql_fetch_array($result, MYSQL_NUM);
$query = "SELECT sum(out0),sum(out1),sum(out2),sum(out3),sum(out4),sum(out5),sum(out6),sum(out7),sum(out8),sum(out9),sum(out10),sum(out11),sum(out12),sum(out13),sum(out14),sum(out15),sum(out16),sum(out17),sum(out18),sum(out19),sum(out20),sum(out21),sum(out22),sum(out23) FROM sites"; 
$result = mysql_query($query) or die(mysql_error());
$out_stats = mysql_fetch_array($result, MYSQL_NUM);
$query = "SELECT sum(clk0),sum(clk1),sum(clk2),sum(clk3),sum(clk4),sum(clk5),sum(clk6),sum(clk7),sum(clk8),sum(clk9),sum(clk10),sum(clk11),sum(clk12),sum(clk13),sum(clk14),sum(clk15),sum(clk16),sum(clk17),sum(clk18),sum(clk19),sum(clk20),sum(clk21),sum(clk22),sum(clk23) FROM sites"; 
$result = mysql_query($query) or die(mysql_error());
$clk_stats = mysql_fetch_array($result, MYSQL_NUM);

$all_stats = array_merge($in_stats, $out_stats, $clk_stats);
rsort($all_stats, SORT_NUMERIC);
$btop = $all_stats[0];

printheader("Last 24 Hour Stats");

print <<<END
<table border="0" cellspacing="0" cellpadding="0" bgcolor="#E8E8E8"><tr><td>
<table border="0" cellspacing="1" cellpadding="2">
<tr>
<td valign="bottom" bgcolor="#E8E8E8">&nbsp;</td>
END;

$j = $thour+1;
for ($i = 1; $i <= 24; $i++, $j++) {
if ($j > 23) { $j = 0; }

$in_height = (int) ($in_stats[$j] / ($btop / 120));
$out_height = (int) ($out_stats[$j] / ($btop / 120));
$clk_height = (int) ($clk_stats[$j] / ($btop / 120));

print <<<END
<td align="center" valign="bottom" bgcolor="#FFFFFF">
<table border="0" cellspacing="1" cellpadding="0"><tr>
<td align="center" valign="bottom"><img src="statred.gif" width="6" height="$in_height" alt="" border="0"></td>
<td align="center" valign="bottom"><img src="statblue.gif" width="6" height="$out_height" alt="" border="0"></td>
<td align="center" valign="bottom"><img src="statgrey.gif" width="6" height="$clk_height" alt="" border="0"></td>
</tr></table>
</td>
END;
}
print "</tr><tr><td align=\"center\" bgcolor=\"#FFFFFF\"><font face=\"Tahoma\" size=\"-1\">Hour</font></td>";
$j = $thour+1;
for ($i = 1; $i <= 24; $i++, $j++) {
	if ($j > 23) { $j = 0; }
	$t = $timmar[$j];
	if ($j == $thour) { $t = "T"; }
	print "<td align=\"center\" bgcolor=\"#FFFFFF\"><font face=\"Tahoma\" size=\"-1\">$t</font></td>";
	}

print "</tr><tr><td align=\"center\" bgcolor=\"#FFFFFF\"><font face=\"Tahoma\" size=\"-2\">In</font></td>";
$j = $thour+1;
for ($i = 1; $i <= 24; $i++, $j++) {
	if ($j > 23) { $j = 0; }
	print "<td align=\"center\" bgcolor=\"#FFFFFF\"><font face=\"Tahoma\" size=\"-2\">$in_stats[$j]</font></td>";
	}
print "</tr><tr><td align=\"center\" bgcolor=\"#FFFFFF\"><font face=\"Tahoma\" size=\"-2\">Out</font></td>";
$j = $thour+1;
for ($i = 1; $i <= 24; $i++, $j++) {
	if ($j > 23) { $j = 0; }
	print "<td align=\"center\" bgcolor=\"#FFFFFF\"><font face=\"Tahoma\" size=\"-2\">$out_stats[$j]</font></td>";
	}
print "</tr><tr><td align=\"center\" bgcolor=\"#FFFFFF\"><font face=\"Tahoma\" size=\"-2\">Click</font></td>";
$j = $thour+1;
for ($i = 1; $i <= 24; $i++, $j++) {
	if ($j > 23) { $j = 0; }
	print "<td align=\"center\" bgcolor=\"#FFFFFF\"><font face=\"Tahoma\" size=\"-2\">$clk_stats[$j]</font></td>";
	}

print <<<END
</tr>
END;

$result = mysql_query("SELECT clk0,clk1,clk2,clk3,clk4,clk5,clk6,clk7,clk8,clk9,clk10,clk11,clk12,clk13,clk14,clk15,clk16,clk17,clk18,clk19,clk20,clk21,clk22,clk23,linkname FROM links") or die(mysql_error());

print <<<END
<tr><td colspan="25" bgcolor="#E8E8E8" align="center"><strong>Link Stats</strong></tr>
END;
while( $line = mysql_fetch_array($result, MYSQL_NUM) ) {
	print "<tr><td align=\"center\" bgcolor=\"#FFFFFF\"><font face=\"Tahoma\" size=\"-2\">{$line[24]}</font></td>";
	$j = $thour+1;
	for ($i = 1; $i <= 24; $i++, $j++) {
		if ($j > 23) { $j = 0; }
		print "<td align=\"center\" bgcolor=\"#FFFFFF\"><font face=\"Tahoma\" size=\"-2\">{$line[$j]}</font></td>";
		}
	print "</tr>";
	}
print <<<END
</table></td></tr></table><br>
<form><input type="submit" name="close" value="Close Window" class="but" onClick="window.close();"></form>
END;
mysql_close($link);

printfoot();
exit;
}

function addsite() {
global $db_host, $db_user, $db_pw, $db_database;
$link = mysql_connect($db_host, $db_user, $db_pw)
	or die("Could not connect : " . mysql_error());
mysql_select_db($db_database) or die(mysql_error());

$result = mysql_query("SELECT defratio, pratio FROM settings") or die(mysql_error());
$line = mysql_fetch_array($result, MYSQL_NUM);

mysql_close($link);

$prat1 = "";
$prat2 = "";
if( $line[1] == 0 ) { $prat1 = " selected"; }
if( $line[1] == 1 ) { $prat2 = " selected"; }

printheader("Add Site");
print <<<END
<table><form action="admin.php" method="post">
<tr><td>Site URL</td><td><input type="text" name="siteurl" size="40" maxlength="99" value="http://" class="inp"></td></tr>
<tr><td>Site Name</td><td><input type="text" name="sitename" size="40" maxlength="49" class="inp"></td></tr>
<tr><td>Site Description</td><td><input type="text" name="sitedesc" size="40" maxlength="99" class="inp"></td></tr>
<tr><td>Ratio</td><td><input type="text" name="siteratio" size="6" maxlength="4" value="{$line[0]}" class="inp"></td></tr>
<tr><td>Ratio On <a href="#" onClick="window.open('help.php?t=ratioon', '_blank', 'width=230,height=350,resizable=0,scrollbars=0,status=0');">?</a></td><td>
<select name="pratio" class="inp">
<option value="0"$prat1>Hits In</option>
<option value="1"$prat2>Productivity</option>
</select>
</td></tr>
<tr><td>E-mail</td><td><input type="text" name="wmemail" size="40" maxlength="49" class="inp"></td></tr>
<tr><td>ICQ</td><td><input type="text" name="wmicq" size="40" maxlength="14" class="inp"></td></tr>
</table>
<br>
<table><tr>
<td><input type="submit" name="addsite2" value="Add Site" class="butf"></form></td>
<td><form><input type="submit" name="close" value="Cancel" onClick="window.close();" class="butf"></form></td>
</tr></table>
<br>
END;
printfoot();
exit;
}

function addsite2() {
global $db_host, $db_user, $db_pw, $db_database;

if (!get_magic_quotes_gpc()) {
	$siteurl = addslashes($_POST["siteurl"]);
	$sitename = addslashes($_POST["sitename"]);
	$sitedesc = addslashes($_POST["sitedesc"]);
	$siteratio = addslashes($_POST["siteratio"]);
	$wmemail = addslashes($_POST["wmemail"]);
	$wmicq = addslashes($_POST["wmicq"]);
	}
else {
	$siteurl = $_POST["siteurl"];
	$sitename = $_POST["sitename"];
	$sitedesc = $_POST["sitedesc"];
	$siteratio = $_POST["siteratio"];
	$wmemail = $_POST["wmemail"];
	$wmicq = $_POST["wmicq"];
	}

$refa = explode("/",$siteurl);
preg_match("/(www\.)*(.*)/",$refa[2],$refd);
$sitedomain = $refd[2];

$link = mysql_connect($db_host, $db_user, $db_pw)
	or die("Could not connect : " . mysql_error());
mysql_select_db($db_database) or die(mysql_error());

$query = "INSERT INTO sites(sitedomain, siteurl, sitename, sitedesc, ratio, wmemail, wmicq) VALUES ('$sitedomain','$siteurl','$sitename','$sitedesc',$siteratio,'$wmemail','$wmicq')";
$result = mysql_query($query) or die(mysql_error());

mysql_close($link);

printheader("Site Added");
print <<<END
Site Added.
<br><br>
<form><input type="submit" name="cancel" value="Close Window" onClick="window.close();" class="but"></form>
END;
printfoot();

exit;
}

function settings() {
global $db_host, $db_user, $db_pw, $db_database;

$link = mysql_connect($db_host, $db_user, $db_pw)
	or die("Could not connect : " . mysql_error());
mysql_select_db($db_database) or die(mysql_error());

$query = "SELECT sitename, siteurl, wmemail, wmicq, defurl, defratio, pratio, minprod, minprodact, minin, mininact, wmform, review, rdnocookie FROM settings";
$result = mysql_query($query) or die(mysql_error());
$line = mysql_fetch_array($result, MYSQL_ASSOC);

$sitename = $line["sitename"];
$siteurl = $line["siteurl"];
$wmemail = $line["wmemail"];
$wmicq = $line["wmicq"];
$defurl = $line["defurl"];
$defratio = $line["defratio"];
$minprod = $line["minprod"];
$minprodact = $line["minprodact"];
$minin = $line["minin"];
$mininact = $line["mininact"];
$wmform = $line["wmform"];
$review = $line["review"];
$rdnocookie = $line["rdnocookie"];
$pratio = $line["pratio"];

mysql_close($link);

$prat1 = "";
$prat2 = "";
if( $line['pratio'] == 0 ) { $prat1 = " selected"; }
if( $line['pratio'] == 1 ) { $prat2 = " selected"; }

$minprodacts1 = "";
$minprodacts2 = "";
if( $minprodact == 0) { $minprodacts1 = " selected"; }
else {  $minprodacts2 = " selected"; }

$mininacts1 = "";
$mininacts2 = "";
if( $mininact == 0) { $mininacts1 = " selected"; }
else {  $mininacts2 = " selected"; }

$reviews1 = "";
$reviews2 = "";
if( $review == 0) { $reviews1 = " selected"; }
else {  $reviews2 = " selected"; }

$wmforms1 = "";
$wmforms2 = "";
if( $wmform == 0) { $wmforms1 = " selected"; }
else {  $wmforms2 = " selected"; }

$rdnocookies1 = "";
$rdnocookies2 = "";
if( $rdnocookie == 0) { $rdnocookies1 = " selected"; }
else {  $rdnocookies2 = " selected"; }

printheader("Settings");

print <<<END
<table><form action="admin.php" method="post">
<tr><td>Site Name</td><td colspan="3"><input type="text" name="sitename" size="45" value="$sitename" class="inp"></td></tr>
<tr><td>Site URL</td><td colspan="3"><input type="text" name="siteurl" size="45" value="$siteurl" class="inp"></td></tr>
<tr><td>E-mail</td><td colspan="3"><input type="text" name="wmemail" size="45" value="$wmemail" class="inp"></td></tr>
<tr><td>ICQ #</td><td colspan="3"><input type="text" name="wmicq" value="$wmicq" class="inp"></td></tr>
<tr><td>Default URL</td><td colspan="3"><input type="text" name="defurl" size="45" value="$defurl" class="inp"></td></tr>
<tr><td>Redirect Nocookie <a href="#" onClick="window.open('help.php?t=nocookie', '_blank', 'width=230,height=350,resizable=0,scrollbars=0,status=0');">?</a></td><td colspan="3">
<select name="rdnocookie"><option value="1"$rdnocookies2>Yes</option><option value="0"$rdnocookies1>No</option></select>
</td></tr>
<tr><td>Default Ratio</td><td colspan="3"><input type="text" name="defratio" size="7" maxlength="4" value="$defratio" class="inp"></td></tr>
<tr><td>Default Ratio On <a href="#" onClick="window.open('help.php?t=ratioon', '_blank', 'width=230,height=350,resizable=0,scrollbars=0,status=0');">?</a></td><td colspan="3"><select name="pratio" class="inp">
<option value="0"$prat1>Hits In</option>
<option value="1"$prat2>Productivity</option>
</select>
</td></tr>
<tr><td>Min. Prod.</td><td><input type="text" name="minprod" size="7" maxlength="5" value="$minprod" class="inp"></td><td>Min Prod. Action</td><td>
<select name="minprodact"><option value="0"$minprodacts1>Pause</option>
<option value="1"$minprodacts2>Delete</option>
</select></td></tr>
<tr><td>Min. Hits In</td><td><input type="text" name="minin" size="7" maxlength="5" value="$minin" class="inp"></td><td>Min Hits Action</td><td>
<select name="mininact"><option value="0"$mininacts1>Pause</option>
<option value="1"$mininacts2>Delete</option>
</select></td></tr>
<tr><td>Signup Form</td><td colspan="3"><select name="wmform" class="inp"><option value="0"$wmforms1>Closed</option>
<option value="1"$wmforms2>Open</option>
</select></td></tr>
<tr><td>Review New</td><td colspan="3"><select name="review" class="inp"><option value="0"$reviews1>No</option>
<option value="1"$reviews2>Yes</option>
</select></td></tr>
</table>
<br>
<table><tr>
<td><input type="submit" name="savesettings" value="Save Settings" class="butf"></form></td>
<td><form><input type="submit" name="close" value="Cancel" onClick="window.close();" class="butf"></form></td>
</tr></table>
END;
printfoot();

exit;
}

function savesettings() {
global $db_host, $db_user, $db_pw, $db_database, $msg;

if (!get_magic_quotes_gpc()) {
	$siteurl = addslashes($_POST["siteurl"]);
	$sitename = addslashes($_POST["sitename"]);
	$wmemail = addslashes($_POST["wmemail"]);
	$wmicq = addslashes($_POST["wmicq"]);
	$defurl = addslashes($_POST["defurl"]);
	$defratio = addslashes($_POST["defratio"]);
	$minprod = addslashes($_POST["minprod"]);
	$minprodact = addslashes($_POST["minprodact"]);
	$minin = addslashes($_POST["minin"]);
	$mininact = addslashes($_POST["mininact"]);
	$wmform = addslashes($_POST["wmform"]);
	$review = addslashes($_POST["review"]);
	$rdnocookie = addslashes($_POST["rdnocookie"]);
	$pratio = addslashes($_POST["pratio"]);
	}
else {
	$siteurl = $_POST["siteurl"];
	$sitename = $_POST["sitename"];
	$wmemail = $_POST["wmemail"];
	$wmicq = $_POST["wmicq"];
	$defurl = $_POST["defurl"];
	$defratio = $_POST["defratio"];
	$minprod = $_POST["minprod"];
	$minprodact = $_POST["minprodact"];
	$minin = $_POST["minin"];
	$mininact = $_POST["mininact"];
	$wmform = $_POST["wmform"];
	$review = $_POST["review"];
	$rdnocookie = $_POST["rdnocookie"];
	$pratio = $_POST["pratio"];
	}

$link = mysql_connect($db_host, $db_user, $db_pw)
	or die("Could not connect : " . mysql_error());
mysql_select_db($db_database) or die(mysql_error());

$query = "UPDATE settings SET sitename='$sitename',siteurl='$siteurl',wmemail='$wmemail',wmicq='$wmicq',defurl='$defurl',defratio='$defratio',minprod='$minprod',minprodact='$minprodact',minin='$minin',mininact='$mininact',wmform='$wmform',review='$review', rdnocookie='$rdnocookie', pratio='$pratio'";
$result = mysql_query($query) or die(mysql_error());

mysql_close($link);

$msg = "Settings Saved";
settings();
exit;
}

function editsite() {

$siteid = $_GET["siteid"];

global $db_host, $db_user, $db_pw, $db_database;

$link = mysql_connect($db_host, $db_user, $db_pw)
	or die("Could not connect : " . mysql_error());
mysql_select_db($db_database) or die(mysql_error());

$query = "SELECT sitename,sitedesc,siteurl,wmemail,wmicq,ratio,pratio,status,force0,force1,force2,force3,force4,force5,force6,force7,force8,force9,force10,force11,force12,force13,force14,force15,force16,force17,force18,force19,force20,force21,force22,force23 FROM sites WHERE siteid=$siteid";
$result = mysql_query($query) or die(mysql_error());
$line = mysql_fetch_array($result, MYSQL_ASSOC);

$status = $line["status"];

$prat1 = "";
$prat2 = "";
if( $line['pratio'] == 0 ) { $prat1 = " selected"; }
if( $line['pratio'] == 1 ) { $prat2 = " selected"; }

$ss1 = "";
$ss2 = "";
$ss3 = "";
if( $status == 0 ) { $ss1 = " selected"; }
if( $status == 1 ) { $ss2 = " selected"; }
if( $status == 5 || $status ==6)  { $ss3 = " selected"; }

mysql_close($link);
printheader("Edit Site");

print <<<END
<script language="javascript">
function setall() {
this.editform.force0.value=this.editform.setallval.value;
this.editform.force1.value=this.editform.setallval.value;
this.editform.force2.value=this.editform.setallval.value;
this.editform.force3.value=this.editform.setallval.value;
this.editform.force4.value=this.editform.setallval.value;
this.editform.force5.value=this.editform.setallval.value;
this.editform.force6.value=this.editform.setallval.value;
this.editform.force7.value=this.editform.setallval.value;
this.editform.force8.value=this.editform.setallval.value;
this.editform.force9.value=this.editform.setallval.value;
this.editform.force10.value=this.editform.setallval.value;
this.editform.force11.value=this.editform.setallval.value;
this.editform.force12.value=this.editform.setallval.value;
this.editform.force13.value=this.editform.setallval.value;
this.editform.force14.value=this.editform.setallval.value;
this.editform.force15.value=this.editform.setallval.value;
this.editform.force16.value=this.editform.setallval.value;
this.editform.force17.value=this.editform.setallval.value;
this.editform.force18.value=this.editform.setallval.value;
this.editform.force19.value=this.editform.setallval.value;
this.editform.force20.value=this.editform.setallval.value;
this.editform.force21.value=this.editform.setallval.value;
this.editform.force22.value=this.editform.setallval.value;
this.editform.force23.value=this.editform.setallval.value;
}
</script>

<table><form action="admin.php?siteid=$siteid" method="post" name="editform">
<tr><td>Site Name</td><td><input type="text" name="sitename" size="40" maxlength="99" class="inp" value="{$line['sitename']}"></td></tr>
<tr><td>Description</td><td><input type="text" name="sitedesc" size="40" maxlength="99" class="inp" value="{$line['sitedesc']}"></td></tr>
<tr><td>URL</td><td><input type="text" name="siteurl" size="40" maxlength="99" class="inp" value="{$line['siteurl']}"></td></tr>
<tr><td>E-mail</td><td><input type="text" name="wmemail" size="40" maxlength="99" class="inp" value="{$line['wmemail']}"></td></tr>
<tr><td>ICQ</td><td><input type="text" name="wmicq" size="15" maxlength="99" class="inp" value="{$line['wmicq']}"></td></tr>
<tr><td>Ratio</td><td><input type="text" name="ratio" size="10" maxlength="5" class="inp" value="{$line['ratio']}"></td></tr>
<tr><td>Ratio On <a href="#" onClick="window.open('help.php?t=ratioon', '_blank', 'width=230,height=350,resizable=0,scrollbars=0,status=0');">?</a></td><td>
<select name="pratio" class="inp">
<option value="0"$prat1>Hits In</option>
<option value="1"$prat2>Productivity</option>
</select>
</td></tr>
<tr><td>Status</td><td>
<select name="status" class="inp">
<option value="0"$ss1>Normal</option>
<option value="1"$ss2>High Priority</option>
<option value="5"$ss3>Paused</option>
</select>
</td></tr>
<br>
</table>
<br>Force
<table border="0" cellpadding="5" cellspacing="0">
<tr>
<td>00</td><td><input type="text" name="force0" size="2" maxlength="4" value="{$line['force0']}" class="inp"></td>
<td>01</td><td><input type="text" name="force1" size="2" maxlength="4" value="{$line['force1']}" class="inp"></td>
<td>02</td><td><input type="text" name="force2" size="2" maxlength="4" value="{$line['force2']}" class="inp"></td>
<td>03</td><td><input type="text" name="force3" size="2" maxlength="4" value="{$line['force3']}" class="inp"></td>
<td>04</td><td><input type="text" name="force4" size="2" maxlength="4" value="{$line['force4']}" class="inp"></td>
<td>05</td><td><input type="text" name="force5" size="2" maxlength="4" value="{$line['force5']}" class="inp"></td>
</tr>
<tr>
<td>06</td><td><input type="text" name="force6" size="2" maxlength="4" value="{$line['force6']}" class="inp"></td>
<td>07</td><td><input type="text" name="force7" size="2" maxlength="4" value="{$line['force7']}" class="inp"></td>
<td>08</td><td><input type="text" name="force8" size="2" maxlength="4" value="{$line['force8']}" class="inp"></td>
<td>09</td><td><input type="text" name="force9" size="2" maxlength="4" value="{$line['force9']}" class="inp"></td>
<td>10</td><td><input type="text" name="force10" size="2" maxlength="4" value="{$line['force10']}" class="inp"></td>
<td>11</td><td><input type="text" name="force11" size="2" maxlength="4" value="{$line['force11']}" class="inp"></td>
</tr>
<tr>
<td>12</td><td><input type="text" name="force12" size="2" maxlength="4" value="{$line['force12']}" class="inp"></td>
<td>13</td><td><input type="text" name="force13" size="2" maxlength="4" value="{$line['force13']}" class="inp"></td>
<td>14</td><td><input type="text" name="force14" size="2" maxlength="4" value="{$line['force14']}" class="inp"></td>
<td>15</td><td><input type="text" name="force15" size="2" maxlength="4" value="{$line['force15']}" class="inp"></td>
<td>16</td><td><input type="text" name="force16" size="2" maxlength="4" value="{$line['force16']}" class="inp"></td>
<td>17</td><td><input type="text" name="force17" size="2" maxlength="4" value="{$line['force17']}" class="inp"></td>
</tr>
<tr>
<td>18</td><td><input type="text" name="force18" size="2" maxlength="4" value="{$line['force18']}" class="inp"></td>
<td>19</td><td><input type="text" name="force19" size="2" maxlength="4" value="{$line['force19']}" class="inp"></td>
<td>20</td><td><input type="text" name="force20" size="2" maxlength="4" value="{$line['force20']}" class="inp"></td>
<td>21</td><td><input type="text" name="force21" size="2" maxlength="4" value="{$line['force21']}" class="inp"></td>
<td>22</td><td><input type="text" name="force22" size="2" maxlength="4" value="{$line['force22']}" class="inp"></td>
<td>23</td><td><input type="text" name="force23" size="2" maxlength="4" value="{$line['force23']}" class="inp"></td>
</tr>
<tr>
<td colspan="12" align="center">
<input type="text" name="setallval" size="3" maxlength="4" value="0" class="inp">
<input type="button" name="setallf" value="Set All" class="butf" onClick="setall()">
</td>
</tr>
</table>
<br>
<input type="hidden" name="siteid" value="$siteid">
<table cellpadding="3" border="0"><tr>
<td><input type="submit" name="editsite2" value="Save Changes" class="butf"></td>
<td><input type="submit" name="cancel" value="Close" onClick="window.close();" class="butf"></td>
</tr></table>
</form>
END;
print foot();

exit;
}

function editsite2() {
global $db_host, $db_user, $db_pw, $db_database, $msg;

if (!get_magic_quotes_gpc()) {
	$siteurl = addslashes($_POST["siteurl"]);
	$sitename = addslashes($_POST["sitename"]);
	$sitedesc = addslashes($_POST["sitedesc"]);
	$wmemail = addslashes($_POST["wmemail"]);
	$wmicq = addslashes($_POST["wmicq"]);
	}
else {
	$siteurl = $_POST["siteurl"];
	$sitename = $_POST["sitename"];
	$sitedesc = $_POST["sitedesc"];
	$wmemail = $_POST["wmemail"];
	$wmicq = $_POST["wmicq"];
	}

$siteid = $_POST["siteid"];

$link = mysql_connect($db_host, $db_user, $db_pw)
	or die("Could not connect : " . mysql_error());
mysql_select_db($db_database) or die(mysql_error());

$query = "UPDATE sites SET sitename='$sitename',sitedesc='$sitedesc',siteurl='$siteurl',wmemail='$wmemail',wmicq='$wmicq',ratio='{$_POST['ratio']}',pratio='{$_POST['pratio']}', status='{$_POST['status']}',force0='{$_POST['force0']}',force1='{$_POST['force1']}',force2='{$_POST['force2']}',force3='{$_POST['force3']}',force4='{$_POST['force4']}',force5='{$_POST['force5']}',force6='{$_POST['force6']}',force7='{$_POST['force7']}',force8='{$_POST['force8']}',force9='{$_POST['force9']}',force10='{$_POST['force10']}',force11='{$_POST['force11']}',force12='{$_POST['force12']}',force13='{$_POST['force13']}',force14='{$_POST['force14']}',force15='{$_POST['force15']}',force16='{$_POST['force16']}',force17='{$_POST['force17']}',force18='{$_POST['force18']}',force19='{$_POST['force19']}',force20='{$_POST['force20']}',force21='{$_POST['force21']}',force22='{$_POST['force22']}',force23='{$_POST['force23']}' WHERE siteid=$siteid";
$result = mysql_query($query) or die(mysql_error());

mysql_close($link);

$msg = "Changes Saved";
editsite();
exit;

}

function delsite() {
global $db_host, $db_user, $db_pw, $db_database;

$siteid = $_GET["delsite"];

$link = mysql_connect($db_host, $db_user, $db_pw)
	or die("Could not connect : " . mysql_error());
mysql_select_db($db_database) or die(mysql_error());
$result = mysql_query("SELECT siteid, sitedomain FROM sites WHERE siteid=$siteid") or die(mysql_error());
$line = mysql_fetch_array($result, MYSQL_ASSOC);
mysql_close($link);

printheader("Delete Site");
print <<<END
<br>
Delete <strong>{$line['sitedomain']}</strong>?
<br><form action="admin.php" method="post">
<table><tr><td>
<input type="checkbox" name="blist" value="1">
</td><td>Add To BlackList</td></tr></table>
<br>
<input type="hidden" name="siteid" value="{$line['siteid']}">
<input type="hidden" name="sitedomain" value="{$line['sitedomain']}">
<table><tr>
<td><input type="submit" name="delsite2" value="Delete" class="butf"></td>
<td><input type="submit" name="cancel" value="Cancel" onClick="window.close();" class="butf"></td>
</tr></table>
</form>
END;
printfoot();
exit;
}

function delsite2() {

global $db_host, $db_user, $db_pw, $db_database;

$siteid = $_POST["siteid"];
$sitedomain = $_POST["sitedomain"];

$link = mysql_connect($db_host, $db_user, $db_pw)
	or die("Could not connect : " . mysql_error());
mysql_select_db($db_database) or die(mysql_error());
$result = mysql_query("DELETE FROM sites WHERE siteid=$siteid") or die(mysql_error());

if( $_POST["blist"] ) {
	$result = mysql_query("INSERT INTO blacklist (domain) VALUES ('$sitedomain')");
	}
mysql_close($link);

printheader("Site Deleted");
print <<<END
<br><bR>
$sitedomain Deleted!
<br><br>
<form><input type="submit" name="cancel" value="Close Window" onClick="window.close();" class="but"></form>
END;
printfoot();

exit;
}

function statsite() {
global $db_host, $db_user, $db_pw, $db_database;

$siteid = $_GET["statsite"];

$link = mysql_connect($db_host, $db_user, $db_pw)
	or die("Could not connect : " . mysql_error());
mysql_select_db($db_database) or die(mysql_error());

$result = mysql_query("SELECT sitedomain FROM sites WHERE siteid=$siteid");
$sitedomain = mysql_fetch_array($result, MYSQL_NUM);

$query = "SELECT in0,in1,in2,in3,in4,in5,in6,in7,in8,in9,in10,in11,in12,in13,in14,in15,in16,in17,in18,in19,in20,in21,in22,in23 FROM sites WHERE siteid=$siteid"; 
$result = mysql_query($query) or die(mysql_error());
$in_stats = mysql_fetch_array($result, MYSQL_NUM);
$query = "SELECT out0,out1,out2,out3,out4,out5,out6,out7,out8,out9,out10,out11,out12,out13,out14,out15,out16,out17,out18,out19,out20,out21,out22,out23 FROM sites WHERE siteid=$siteid"; 
$result = mysql_query($query) or die(mysql_error());
$out_stats = mysql_fetch_array($result, MYSQL_NUM);
$query = "SELECT clk0,clk1,clk2,clk3,clk4,clk5,clk6,clk7,clk8,clk9,clk10,clk11,clk12,clk13,clk14,clk15,clk16,clk17,clk18,clk19,clk20,clk21,clk22,clk23 FROM sites WHERE siteid=$siteid"; 
$result = mysql_query($query) or die(mysql_error());
$clk_stats = mysql_fetch_array($result, MYSQL_NUM);

mysql_close($link);

printheader("{$sitedomain[0]} - Last 24 Hour Stats");
printdia($in_stats, $out_stats, $clk_stats);

print <<<END
<table border="0" cellspacing="0" cellpadding="0"><tr>
<td><iframe src="admin.php?iplog=1&siteid=$siteid" width="200" height="250" frameborder="0"></iframe></td>
<td width="10">&nbsp;</td>
<td><iframe src="admin.php?reflog=1&siteid=$siteid" width="470" height="250" frameborder="0"></iframe></td>
</tr></table>
<form><input type="submit" name="close" value="Close Window" class="but" onClick="window.close();"></form>
END;

printfoot();
exit;
}

function iplog() {
global $db_host, $db_user, $db_pw, $db_database;
$siteid = $_GET["siteid"];
$ippage = $_GET["iplog"];
$ipoff = ($ippage - 1) * 15;

$link = mysql_connect($db_host, $db_user, $db_pw)
	or die("Could not connect : " . mysql_error());
mysql_select_db($db_database) or die(mysql_error());

$query = "SELECT siteid, ip, count(*) AS cip FROM visitlog WHERE siteid=$siteid AND UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(tid) < 86400 GROUP BY ip ORDER BY cip DESC LIMIT $ipoff,15";
$result = mysql_query($query) or die(mysql_error());
printheader("IP Log");
print "<strong>IP Log</strong><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
while( $line = mysql_fetch_array($result, MYSQL_ASSOC) ) {
	print "<tr><td class=\"small\">{$line['ip']}</td><td align=\"right\" class=\"small\">{$line['cip']}</td></tr>";
	}
print "</table>";


if( $ippage <= 1 ) {
	$prevtext = "&lt;&lt; Prev";
	}
else {
	$prevpage = $ippage - 1;
	$prevtext = "<a href=\"admin.php?iplog=$prevpage&siteid=$siteid\">&lt;&lt; Prev</a>";
	}
$nextpage = $ippage + 1;

$nexttext = "<a href=\"admin.php?iplog=$nextpage&siteid=$siteid\">Next &gt;&gt;</a>";

print "<table><tr><td align=\"center\">$prevtext&nbsp;&nbsp;&nbsp;$nexttext</td></tr></table>";
printfoot();

mysql_close($link);
exit;
}

function reflog() {
global $db_host, $db_user, $db_pw, $db_database;
$siteid = $_GET["siteid"];
$refpage = $_GET["reflog"];
$refoff = ($refpage - 1) * 15;

$link = mysql_connect($db_host, $db_user, $db_pw)
	or die("Could not connect : " . mysql_error());
mysql_select_db($db_database) or die(mysql_error());

$query = "SELECT siteid, referer, count(*) AS cref FROM visitlog WHERE siteid=$siteid AND UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(tid) < 86400 GROUP BY referer ORDER BY cref DESC LIMIT $refoff,15";
$result = mysql_query($query) or die(mysql_error());
printheader("Referer Log");
print "<strong>Referering URLs</strong><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
while( $line = mysql_fetch_array($result, MYSQL_ASSOC) ) {
	print "<tr><td class=\"small\">{$line['referer']}</td><td align=\"right\" class=\"small\">{$line['cref']}</td></tr>";
	}
print "</table>";

if( $refpage <= 1 ) {
	$prevtext = "&lt;&lt; Prev";
	}
else {
	$prevpage = $refpage - 1;
	$prevtext = "<a href=\"admin.php?reflog=$prevpage&siteid=$siteid\">&lt;&lt; Prev</a>";
	}
$nextpage = $refpage + 1;

$nexttext = "<a href=\"admin.php?reflog=$nextpage&siteid=$siteid\">Next &gt;&gt;</a>";

print "<table><tr><td align=\"center\">$prevtext&nbsp;&nbsp;&nbsp;$nexttext</td></tr></table>";

printfoot();

mysql_close($link);
exit;
}

function blacklist() {
global $db_host, $db_user, $db_pw, $db_database;
$link = mysql_connect($db_host, $db_user, $db_pw)
	or die("Could not connect : " . mysql_error());
mysql_select_db($db_database) or die(mysql_error());

$query = "SELECT bid,domain FROM blacklist";
$result = mysql_query($query) or die(mysql_error());

printheader("Blacklist");
print <<<END
<form action="admin.php" method="post">
<input type="text" name="blacklistdomain" value="" class="inp">
<input type="submit" name="addblacklist" value="Add Domain" class="but">
<hr width="100%" size="1" color="#000000" noshade>
<table border="0" cellspacing="0" cellpadding="0">
END;

while( $line = mysql_fetch_array($result, MYSQL_ASSOC) ) {
	print "<tr><td><input type=\"checkbox\" name=\"domid[]\" value=\"{$line['bid']}\"></td><td>{$line['domain']}</td></tr>";
	}

print <<<END
</table>
<hr width="100%" size="1" color="#000000" noshade>
<input type="submit" name="delblacklist" value="Remove Selected" class="but">
</form>
END;

printfoot();

mysql_close($link);
exit;
}

function addblacklist() {
global $msg;
$bldomain = $_POST["blacklistdomain"];
if( $bldomain != "" )
	{
	global $db_host, $db_user, $db_pw, $db_database;
	$link = mysql_connect($db_host, $db_user, $db_pw)
		or die("Could not connect : " . mysql_error());
	mysql_select_db($db_database) or die(mysql_error());

	$result = mysql_query("INSERT INTO blacklist (domain) VALUES ('$bldomain')") or die(mysql_error());

	mysql_close($link);
	$msg = "$bldomain Added To BlackList.";
	}
blacklist();
exit;
}

function delblacklist() {
global $msg;
$domid = $_POST["domid"];

global $db_host, $db_user, $db_pw, $db_database;
$link = mysql_connect($db_host, $db_user, $db_pw)
	or die("Could not connect : " . mysql_error());
mysql_select_db($db_database) or die(mysql_error());

for($i = 0; $i < sizeof($domid); $i++) {
	$result = mysql_query("DELETE FROM blacklist WHERE bid='{$domid[$i]}'") or die(mysql_error());
	}

mysql_close($link);

$msg = "Selected Domains Removed.";
blacklist();
exit;
}

function updatetop() {
global $db_host, $db_user, $db_pw, $db_database;
$link = mysql_connect($db_host, $db_user, $db_pw)
	or die("Could not connect : " . mysql_error());
mysql_select_db($db_database) or die(mysql_error());

createtoplists();
printheader("Toplists updated");
print <<<END
<br><bR>
Toplists has been updated.
<br><br>
<form><input type="submit" name="cancel" value="Close Window" onClick="window.close();" class="but"></form>
END;
printfoot();

mysql_close($link);
exit;
}

function history() {

global $db_host, $db_user, $db_pw, $db_database;
$link = mysql_connect($db_host, $db_user, $db_pw)
	or die("Could not connect : " . mysql_error());
mysql_select_db($db_database) or die(mysql_error());

$query = "SELECT datum,hitsin,hitsout,clicks FROM history ORDER BY hid DESC";
$result = mysql_query($query) or die(mysql_error());

printheader("History");
print <<<END
<table cellspacing="1" cellpadding="3" bgcolor="#000040">
<tr>
<td bgcolor="#C0C0C0" align="center" width="130">Date</td>
<td bgcolor="#C0C0C0" align="right" width="80">In</td>
<td bgcolor="#C0C0C0" align="right" width="80">Out</td>
<td bgcolor="#C0C0C0" align="right" width="80">Clicks</td>
<td bgcolor="#C0C0C0" align="center" width="100">Prod</td>
</tr>
END;
while( $line = mysql_fetch_array($result, MYSQL_ASSOC) ) {
	$prod = (int)(($line['clicks'] * 100) / $line['hitsin']) ."%";
	print "<tr><td bgcolor=\"#E8E8E8\" align=\"center\">{$line['datum']}</td><td bgcolor=\"#E8E8E8\" align=\"right\">{$line['hitsin']}</td><td bgcolor=\"#E8E8E8\" align=\"right\">{$line['hitsout']}</td><td bgcolor=\"#E8E8E8\" align=\"right\">{$line['clicks']}</td><td bgcolor=\"#E8E8E8\" align=\"center\">$prod</td></tr>";
	}
print "</table>";
printfoot();

mysql_close($link);

}

function printdia($indata, $outdata, $clkdata) {
$timmar = array("00","01","02","03","04","05","06","07","08","09","10","11","12","13","14","15","16","17","18","19","20","21","22","23");
$timmard = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23);

$tid = localtime(time());
$thour = $tid[2];

$all_stats = array_merge($indata, $outdata, $clkdata);
rsort($all_stats, SORT_NUMERIC);
$btop = $all_stats[0];

print <<<END
<table border="0" cellspacing="0" cellpadding="0" bgcolor="#E8E8E8"><tr><td>
<table border="0" cellspacing="1" cellpadding="2">
<tr>
<td valign="bottom" bgcolor="#E8E8E8">&nbsp;</td>
END;

$j = $thour+1;
for ($i = 1; $i <= 24; $i++, $j++) {
if ($j > 23) { $j = 0; }

$in_height = (int) ($indata[$j] / ($btop / 120));
$out_height = (int) ($outdata[$j] / ($btop / 120));
$clk_height = (int) ($clkdata[$j] / ($btop / 120));

print <<<END
<td align="center" valign="bottom" bgcolor="#FFFFFF">
<table border="0" cellspacing="1" cellpadding="0"><tr>
<td align="center" valign="bottom"><img src="statred.gif" width="6" height="$in_height" alt="" border="0"></td>
<td align="center" valign="bottom"><img src="statblue.gif" width="6" height="$out_height" alt="" border="0"></td>
<td align="center" valign="bottom"><img src="statgrey.gif" width="6" height="$clk_height" alt="" border="0"></td>
</tr></table>
</td>
END;
}
print "</tr><tr><td align=\"center\" bgcolor=\"#FFFFFF\"><font face=\"Tahoma\" size=\"-1\">Hour</font></td>";
$j = $thour+1;
for ($i = 1; $i <= 24; $i++, $j++) {
	if ($j > 23) { $j = 0; }
	$t = $timmar[$j];
	if ($j == $thour) { $t = "T"; }
	print "<td align=\"center\" bgcolor=\"#FFFFFF\"><font face=\"Tahoma\" size=\"-1\">$t</font></td>";
	}

print "</tr><tr><td align=\"center\" bgcolor=\"#FFFFFF\"><font face=\"Tahoma\" size=\"-2\">In</font></td>";
$j = $thour+1;
for ($i = 1; $i <= 24; $i++, $j++) {
	if ($j > 23) { $j = 0; }
	print "<td align=\"center\" bgcolor=\"#FFFFFF\"><font face=\"Tahoma\" size=\"-2\">$indata[$j]</font></td>";
	}
print "</tr><tr><td align=\"center\" bgcolor=\"#FFFFFF\"><font face=\"Tahoma\" size=\"-2\">Out</font></td>";
$j = $thour+1;
for ($i = 1; $i <= 24; $i++, $j++) {
	if ($j > 23) { $j = 0; }
	print "<td align=\"center\" bgcolor=\"#FFFFFF\"><font face=\"Tahoma\" size=\"-2\">$outdata[$j]</font></td>";
	}
print "</tr><tr><td align=\"center\" bgcolor=\"#FFFFFF\"><font face=\"Tahoma\" size=\"-2\">Click</font></td>";
$j = $thour+1;
for ($i = 1; $i <= 24; $i++, $j++) {
	if ($j > 23) { $j = 0; }
	print "<td align=\"center\" bgcolor=\"#FFFFFF\"><font face=\"Tahoma\" size=\"-2\">$clkdata[$j]</font></td>";
	}

print "</tr></table></td></tr></table>";

}

function printheader($title) {
global $msg;
print <<<END
<html>
<head>
<title>$title</title>
<style>
body {font-family: Verdana, Arial, Helvetica, sans-serif; font-size : x-small; color : #000000; font-weight : normal; text-decoration : none;}
td {font-family: Verdana, Arial, Helvetica, sans-serif; font-size : x-small; color : #000000; font-weight : normal; text-decoration : none;}
td.small {font-family: Verdana, Arial, Helvetica, sans-serif; font-size : xx-small; color : #000000; font-weight : normal; text-decoration : none;}
a:link { text-decoration : none;}
a:visited { text-decoration : none;}
a:hover { text-decoration : underline;}
.but {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: x-small; color : #000000; font-weight: normal; background-color: #E8E8E8; border: 1px solid #000000; height: 21; cursor: hand; }
.butf {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: x-small; color : #000000; font-weight: normal; background-color: #E8E8E8; border: 1px solid #000000; height: 21; cursor: hand; width: 130; }
.inp {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: x-small; color : #000000; font-weight: normal; background-color: #FFFFFF; border: 1px solid #000000; }
.radio1 { color : #FFFFFF; background-color: #000040; cursor : hand; height:14}
.hh {font-family: Verdana, Arial, Helvetica, sans-serif; font-size : small; color : #000000; font-weight : bold; text-decoration : none;}
.men1 {font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif; font-size : x-small; color : #000000; font-weight : normal; text-decoration : none;}
</style>
</head>
<body bgcolor="#FFFFFF" text="#000000" link="#0000FF" vlink="#0000FF" alink="#0000FF">
<div align="center">
<font color="#800000"><strong>$msg</strong></font>
END;
}

function printfoot() {
print <<<END
</div>
</body>
</html>
END;
}
?>
