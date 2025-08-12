<?
require('../prepend.inc.php');
?>
<?
include("./header.inc.php");

$seitenaufrufe = 0;
$punkte = 0;
$punktesparen = 0;
$date = date( "d.m.Y" );
$time = date( "H:i" );
$user = mysql_num_rows(mysql_query("SELECT id FROM `demo_a_accounts`"));
$usersparen = mysql_num_rows(mysql_query("SELECT id FROM `demo_a_accounts` WHERE `savepoints` > '0'"));
$site = mysql_num_rows(mysql_query("SELECT url FROM `demo_a_accounts`"));
$holen = mysql_query("SELECT points FROM `demo_a_accounts` WHERE `savepoints` = '0'");

require("./header.inc.php");

while ($myrow = mysql_fetch_row($holen)) {
$punkte = $myrow[0] + $punkte;
};

$punktsparen = mysql_query("SELECT points FROM `demo_a_accounts` WHERE `savepoints` > '0'");

require("header.inc.php");

while ($myrow = mysql_fetch_row($punktsparen)) {

$punktesparen = $myrow[0] + $punktesparen;

};

$punktn = mysql_query("SELECT views FROM `demo_a_accounts`");

require("header.inc.php");

while ($myrow = mysql_fetch_row($punktn)) {

$punkt = $myrow[0] + $punkt;

};

$einblendung = 0;
$click = 0;
$anzahl = mysql_num_rows(mysql_query("SELECT id FROM `demo_a_banners`"));
$bannerb = mysql_query("SELECT views, clicks FROM `demo_a_banners`");

require("header.inc.php");

while ($myrow = mysql_fetch_row($bannerb)) {

$einblendung = $myrow[0] + $einblendung;
$click = $myrow[1] + $click;
$clickss = $click - $click - $click;
};

$zeit = time ();
$nichtmehrgueltig = $zeit-19;
$query = "DELETE FROM demo_a_klicksp WHERE timefeld <= ".$nichtmehrgueltig;
mysql_query($query);

$userb = mysql_num_rows(mysql_query("SELECT user FROM `demo_a_klicksp`"));
$surfer = $userb + 0;
?>
<?
include("../templates/admin-header.txt");
?>
<TABLE bgcolor="#FFFFFF" bordercolor="#000000" border="0" align="center">
<TR>
  <TD width="300"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Service started:</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><? echo "$seitenstart"; ?></TD>
</TR>
<TR>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Stats last updated:</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><? echo "$date $time"; ?></TD>
</TR>
<TR>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Registered users:</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><? echo "$user"; ?></TD>
</TR>
<TR>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Registered websites:</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><? echo "$site"; ?></TD>
</TR>
<TR>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Amount of points in system:</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><? echo "$punkte"; ?></TD>
</TR>
<TR>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Users saving their points:</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><? echo "$usersparen"; ?></TD>
</TR>
<TR>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Saved points:</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><? echo "$punktesparen"; ?></TD>
</TR>
<TR>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Amount of generated visits:</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><? echo "$punkt"; ?></TD>
</TR>
<TR>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Surfing at the moment:</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><? echo "$surfer"; ?> User</TD>
</TR></TABLE><br><center>
<font size="4" color="red" face="Verdana, Arial, Helvetica, sans-serif"><b><U>Banner stats</u></b></font><br><br>
<center><TABLE bgcolor="#FFFFFF" bordercolor="#000000" border="0">
<TR>
  <TD width="300"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Active banners</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><? echo "$anzahl"; ?></TD>
</TR>
<TR>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Bannerviews remaining</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><? echo "$einblendung"; ?></TD>
</TR>
<TR>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Bannerclicks generated</TD>
  <TD><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><? echo "$clickss"; ?></TD>
</TR>
</TABLE>
<?
include("../templates/admin-footer.txt");
?>