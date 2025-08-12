<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
require_once('./admchk.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page_title=$l_subscribers;
require_once('./heading.php');
$sql = "select * from ".$tableprefix."_settings where (settingnr=1)";
if(!$result = mysql_query($sql, $db))
    die("Could not connect to the database.");
if ($myrow = mysql_fetch_array($result))
	$maxconfirmtime=$myrow["maxconfirmtime"];
else
	$maxconfirmtime=0;
$dateformat="Y-m-d H:i:s";
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<tr class="inforow"><td align="center" colspan="5"><b><?php echo $l_stats?></b></td></tr>
<?php
if($admin_rights < 2)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
$sql = "select * from ".$tableprefix."_subscriptions";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.");
$totalentries=mysql_num_rows($result);
if($totalentries<1)
{
	echo "<tr class=\"displayrow\"><td align=\"center\" colspan=\"4\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
	echo "<div class=\"bottombox\" align=\"center\">";
	echo "<a href=\"".do_url_session("subscribers.php?$langvar=$act_lang")."\">";
	echo "$l_subscribers</a></div>";
	include('./trailer.php');
	exit;
}
?>
<tr class="displayrow"><td align="right" width="30%"><?php echo $l_total?>:</td>
<td align="left" colspan="4"><?php echo $totalentries?></td></tr>
<?php
$sql="select * from ".$tableprefix."_subscriptions where confirmed=1";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.".mysql_error());
$numentries=mysql_num_rows($result);
$percentage=round(($numentries/$totalentries)*100);
echo "<tr class=\"displayrow\"><td align=\"right\">".$l_confirmed.":</td>";
echo "<td align=\"right\" width=\"10%\">$numentries</td>";
echo "<td align=\"right\" width=\"10%\">".$percentage."%</td>";
echo "<td>";
echo "<img class=\"statbar\" src=\"gfx/bargif.gif\" border=\"0\" width=\"".(round($percentage/3)*3)."\" height=\"10\">";
echo "</td>";
echo "</tr>";
$sql="select * from ".$tableprefix."_subscriptions where confirmed=0";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.".mysql_error());
$numentries=mysql_num_rows($result);
$percentage=round(($numentries/$totalentries)*100);
echo "<tr class=\"displayrow\"><td align=\"right\">".$l_unconfirmed.":</td>";
echo "<td align=\"right\" width=\"10%\">$numentries</td>";
echo "<td align=\"right\" width=\"10%\">".$percentage."%</td>";
echo "<td>";
echo "<img class=\"statbar\" src=\"gfx/bargif.gif\" border=\"0\" width=\"".(round($percentage/3)*3)."\" height=\"10\">";
echo "</td>";
echo "</tr>";
$actdate = date("Y-m-d H:i:s");
$confirmtime=($maxconfirmtime*24)+1;
$sql = "select * from ".$tableprefix."_subscriptions where confirmed=0 and enterdate<=DATE_SUB('$actdate', INTERVAL $confirmtime HOUR)";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.".mysql_error());
$numentries=mysql_num_rows($result);
$percentage=round(($numentries/$totalentries)*100);
echo "<tr class=\"displayrow\"><td align=\"right\">".$l_overdue.":</td>";
echo "<td align=\"right\" width=\"10%\">$numentries</td>";
echo "<td align=\"right\" width=\"10%\">".$percentage."%</td>";
echo "<td>";
echo "<img class=\"statbar\" src=\"gfx/bargif.gif\" border=\"0\" width=\"".(round($percentage/3)*3)."\" height=\"10\">";
echo "</td>";
echo "</tr>";
?>
<tr class="listheading0"><td align="left" colspan="5"><?php echo $l_languages?></td></tr>
<?php
$avail_langs=language_list("../language/");
for($i=0;$i<count($avail_langs);$i++)
{
	$sql="select * from ".$tableprefix."_subscriptions where language='".$avail_langs[$i]."'";
	if(!$result = mysql_query($sql, $db))
		die("Could not connect to the database.".mysql_error());
	$numentries=mysql_num_rows($result);
	$percentage=round(($numentries/$totalentries)*100);
	echo "<tr class=\"displayrow\"><td align=\"right\">".$avail_langs[$i].":</td>";
	echo "<td align=\"right\" width=\"10%\">$numentries</td>";
	echo "<td align=\"right\" width=\"10%\">".$percentage."%</td>";
	echo "<td>";
	echo "<img class=\"statbar\" src=\"gfx/bargif.gif\" border=\"0\" width=\"".(round($percentage/3)*3)."\" height=\"10\">";
	echo "</td>";
	echo "</tr>";
}
?>
<tr class="listheading0"><td align="left" colspan="5"><?php echo $l_emailtype?></td></tr>
<?php
for($i=0;$i<count($l_emailtypes);$i++)
{
	$sql="select * from ".$tableprefix."_subscriptions where emailtype=$i";
	if(!$result = mysql_query($sql, $db))
		die("Could not connect to the database.".mysql_error());
	$numentries=mysql_num_rows($result);
	$percentage=round(($numentries/$totalentries)*100);
	echo "<tr class=\"displayrow\"><td align=\"right\">".$l_emailtypes[$i].":</td>";
	echo "<td align=\"right\" width=\"10%\">$numentries</td>";
	echo "<td align=\"right\" width=\"10%\">".$percentage."%</td>";
	echo "<td>";
	echo "<img class=\"statbar\" src=\"gfx/bargif.gif\" border=\"0\" width=\"".(round($percentage/3)*3)."\" height=\"10\">";
	echo "</td>";
	echo "</tr>";
}
?>
<tr class="listheading0"><td align="left" colspan="5"><?php echo $l_categories?></td></tr>
<?php
$sql="select * from ".$tableprefix."_subscriptions where category=0";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.".mysql_error());
$numentries=mysql_num_rows($result);
$percentage=round(($numentries/$totalentries)*100);
echo "<tr class=\"displayrow\"><td align=\"right\">".$l_all.":</td>";
echo "<td align=\"right\" width=\"10%\">$numentries</td>";
echo "<td align=\"right\" width=\"10%\">".$percentage."%</td>";
echo "<td>";
echo "<img class=\"statbar\" src=\"gfx/bargif.gif\" border=\"0\" width=\"".(round($percentage/3)*3)."\" height=\"10\">";
echo "</td>";
echo "</tr>";
$sql="select * from ".$tableprefix."_categories";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.".mysql_error());
while($myrow=mysql_fetch_array($result))
{
	$tmpsql="select * from ".$tableprefix."_subscriptions where category=".$myrow["catnr"];
	if(!$tmpresult = mysql_query($tmpsql, $db))
		die("Could not connect to the database.".mysql_error());
	$numentries=mysql_num_rows($tmpresult);
	$percentage=round(($numentries/$totalentries)*100);
	echo "<tr class=\"displayrow\"><td align=\"right\">".$myrow["catname"].":</td>";
	echo "<td align=\"right\" width=\"10%\">$numentries</td>";
	echo "<td align=\"right\" width=\"10%\">".$percentage."%</td>";
	echo "<td>";
	echo "<img class=\"statbar\" src=\"gfx/bargif.gif\" border=\"0\" width=\"".(round($percentage/3)*3)."\" height=\"10\">";
	echo "</td>";
	echo "</tr>";
}
echo "</table></tr></td></table>";
echo "<div class=\"bottombox\" align=\"center\">";
echo "<a href=\"".do_url_session("subscribers.php?$langvar=$act_lang")."\">";
echo "$l_subscribers</a></div>";
include('./trailer.php');
?>
