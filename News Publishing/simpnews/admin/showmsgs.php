<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./auth.php');
$page_title=$l_admmsgs;
require_once('./heading.php');
$sql = "select * from ".$tableprefix."_settings where (settingnr=1)";
if(!$result = mysql_query($sql, $db)) {
	die("Could not connect to the database.");
}
if($myrow = mysql_fetch_array($result))
{
	$usemenubar=$myrow["usemenubar"];
	$servertimezone=$myrow["servertimezone"];
	$displaytimezone=$myrow["displaytimezone"];
	$admrestrict=$myrow["admrestrict"];
	$newsletternoicons=$myrow["newsletternoicons"];
	$admonlyentryheadings=$myrow["admonlyentryheadings"];
	$admentrychars=$myrow["admentrychars"];
	$admdelconfirm=$myrow["admdelconfirm"];
	$mailattach=$myrow["mailattach"];
	$evnewsletterinclude=$myrow["evnewsletterinclude"];
	$msendlimit=$myrow["msendlimit"];
	$admepp=$myrow["admepp"];
	$secsettings=$myrow["secsettings"];
	$bbcimgdefalign=$myrow["bbcimgdefalign"];
}
else
{
	$usemenubar=0;
	$servertimezone=0;
	$displaytimezone=0;
	$admrestrict=0;
	$newsletternoicons=1;
	$admonlyentryheadings=0;
	$admentrychars=20;
	$admdelconfirm=0;
	$mailattach=0;
	$evnewsletterinclude=0;
	$msendlimit=30;
	$admepp=0;
	$secsettings=0;
	$bbcimgdefalign="center";
}
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
$sql = "select * from ".$tableprefix."_globalmsg where added>='".$userdata["lastlogin"]."' and lang='$act_lang' order by added desc";
if(!$result = mysql_query($sql, $db))
    die("Unable to connect to database.".mysql_error());
while($myrow=mysql_fetch_array($result))
{
echo "<tr class=\"displayrow\">";
echo "<td align=\"center\"><table width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">";
list($mydate,$mytime)=explode(" ",$myrow["added"]);
list($year, $month, $day) = explode("-", $mydate);
list($hour, $min, $sec) = explode(":",$mytime);
if($month>0)
{
	$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
	$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
	$displaydate=date($l_admdateformat,$displaytime);
}
else
	$displaydate="";
echo "<tr class=\"newsdate\"><td align=\"left\" colspan=\"3\">";
echo $displaydate;
echo "</td></tr>";
if(strlen($myrow["heading"])>0)
{
	echo "<tr class=\"newsheading\"><td align=\"left\" colspan=\"3\">";
	echo display_encoded($myrow["heading"]);
	echo "</td></tr>";
}
echo "<tr class=\"newsentry\"><td align=\"left\" colspan=\"3\">";
$displaytext=stripslashes($myrow["text"]);
$displaytext = undo_htmlspecialchars($displaytext);
echo $displaytext."</td></tr></table></td></tr>";
}
echo "</table></td></tr></table>";
echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session($forward)."\">$l_forward</a></div>";
include('./trailer.php');
?>