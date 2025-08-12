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
$page_title=$l_sys_stats;
require_once('./heading.php');
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($admin_rights < 3)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
echo "<table align=\"center\" width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">";
echo "<tr class=\"listheading0\"><td align=\"center\" colspan=\"3\">";
echo "<b>$l_numentries</b>";
echo "</td></tr>";
$sql="select count(*) as numcats from ".$tableprefix."_categories";
if(!$result = mysql_query($sql))
	die("<tr class=\"errorrow\"><td>Could not connect to database. ".mysql_error());
if(!$myrow=mysql_fetch_array($result))
	die("<tr class=\"errorrow\"><td>Could not get data from database. ".mysql_error());
echo "<tr class=\"displayrow\"><td align=\"right\" width=\"50%\">";
echo $l_categories.":";
echo "</td><td align=\"right\" width=\"20%\">".$myrow["numcats"]."</td><td width=\"30%\">&nbsp;</td></tr>";
$sql="select count(*) as numnews from ".$tableprefix."_data";
if(!$result = mysql_query($sql))
	die("<tr class=\"errorrow\"><td>Could not connect to database. ".mysql_error());
if(!$myrow=mysql_fetch_array($result))
	die("<tr class=\"errorrow\"><td>Could not get data from database. ".mysql_error());
echo "<tr class=\"displayrow\"><td align=\"right\" width=\"50%\">";
echo $l_news.":";
echo "</td><td align=\"right\" width=\"20%\">".$myrow["numnews"]."</td><td width=\"30%\">&nbsp;</td></tr>";
$sql="select count(*) as numevents from ".$tableprefix."_events";
if(!$result = mysql_query($sql))
	die("<tr class=\"errorrow\"><td>Could not connect to database. ".mysql_error());
if(!$myrow=mysql_fetch_array($result))
	die("<tr class=\"errorrow\"><td>Could not get data from database. ".mysql_error());
echo "<tr class=\"displayrow\"><td align=\"right\" width=\"50%\">";
echo $l_events.":";
echo "</td><td align=\"right\" width=\"20%\">".$myrow["numevents"]."</td><td width=\"30%\">&nbsp;</td></tr>";
$sql="select count(*) as numannounce from ".$tableprefix."_announce";
if(!$result = mysql_query($sql))
	die("<tr class=\"errorrow\"><td>Could not connect to database. ".mysql_error());
if(!$myrow=mysql_fetch_array($result))
	die("<tr class=\"errorrow\"><td>Could not get data from database. ".mysql_error());
echo "<tr class=\"displayrow\"><td align=\"right\" width=\"50%\">";
echo $l_announcements.":";
echo "</td><td align=\"right\" width=\"20%\">".$myrow["numannounce"]."</td><td width=\"30%\">&nbsp;</td></tr>";
echo "<tr class=\"listheading1\"><td align=\"center\" colspan=\"3\">";
echo "<b>$l_sysdata</b></td></tr>";
$sql="select count(*) as numadmins from ".$tableprefix."_users";
if(!$result = mysql_query($sql))
	die("<tr class=\"errorrow\"><td>Could not connect to database. ".mysql_error());
if(!$myrow=mysql_fetch_array($result))
	die("<tr class=\"errorrow\"><td>Could not get data from database. ".mysql_error());
echo "<tr class=\"displayrow\"><td align=\"right\" width=\"50%\">";
echo $l_admins.":";
echo "</td><td align=\"right\" width=\"20%\">".$myrow["numadmins"]."</td><td width=\"30%\">&nbsp;</td></tr>";
$sql="select count(*) as numposter from ".$tableprefix."_poster";
if(!$result = mysql_query($sql))
	die("<tr class=\"errorrow\"><td>Could not connect to database. ".mysql_error());
if(!$myrow=mysql_fetch_array($result))
	die("<tr class=\"errorrow\"><td>Could not get data from database. ".mysql_error());
echo "<tr class=\"displayrow\"><td align=\"right\" width=\"50%\">";
echo $l_pposter.":";
echo "</td><td align=\"right\" width=\"20%\">".$myrow["numposter"]."</td><td width=\"30%\">&nbsp;</td></tr>";
echo "<tr class=\"listheading0\"><td align=\"center\" colspan=\"3\">";
echo "<b>$l_dbinfos</b>";
echo "</td></tr>";
$dbtotal=0;
$spaceused=0;
$sql="show table status from $dbname like '".$tableprefix."%'";
if(!$result = mysql_query($sql))
	die("<tr class=\"errorrow\"><td>Could not connect to database. ".mysql_error());
while($myrow = mysql_fetch_array($result)) {
	$spaceused+=$myrow["Data_length"];
	$spaceused+=$myrow["Index_length"];
}
$dbtotal+=$spaceused;
$spaceused = format_bytes($spaceused);
echo "<tr class=\"displayrow\"><td align=\"right\" width=\"50%\">";
echo "$l_spaceused:<br><span class=\"remark\">(".$tableprefix."_*)</span></td>";
echo "<td align=\"right\" width=\"20%\" valign=\"bottom\">$spaceused</td><td width=\"30%\">&nbsp;";
echo "</td></tr>";
if($hcprefix!=$tableprefix)
{
	$spaceused=0;
	$sql="show table status from $dbname like '".$hcprefix."_hostcache'";
	if(!$result = mysql_query($sql))
		die("<tr class=\"errorrow\"><td>Could not connect to database. ".mysql_error());
	while($myrow = mysql_fetch_array($result)) {
		$spaceused+=$myrow["Data_length"];
		$spaceused+=$myrow["Index_length"];
	}
	$dbtotal+=$spaceused;
	$spaceused = format_bytes($spaceused);
	echo "<tr class=\"displayrow\"><td align=\"right\" width=\"50%\">";
	echo "$l_hostcache:<br><span class=\"remark\">(".$hcprefix."_hostcache)</span></td>";
	echo "<td align=\"right\" width=\"20%\" valign=\"bottom\">$spaceused</td><td width=\"30%\">&nbsp;";
	echo "</td></tr>";
}
if($banprefix!=$tableprefix)
{
	$spaceused=0;
	$sql="show table status from $dbname like '".$banprefix."_banlist'";
	if(!$result = mysql_query($sql))
		die("<tr class=\"errorrow\"><td>Could not connect to database. ".mysql_error());
	while($myrow = mysql_fetch_array($result)) {
		$spaceused+=$myrow["Data_length"];
		$spaceused+=$myrow["Index_length"];
	}
	$dbtotal+=$spaceused;
	$spaceused = format_bytes($spaceused);
	echo "<tr class=\"displayrow\"><td align=\"right\" width=\"50%\">";
	echo "$l_ipbanlist:<br><span class=\"remark\">(".$banprefix."_banlist)</span></td>";
	echo "<td align=\"right\" width=\"20%\" valign=\"bottom\">$spaceused</td><td width=\"30%\">&nbsp;";
	echo "</td></tr>";
}
if($leacherprefix != $tableprefix)
{
	$spaceused=0;
	$sql="show table status from $dbname like '".$leacherprefix."_leachers'";
	if(!$result = mysql_query($sql))
		die("<tr class=\"errorrow\"><td>Could not connect to database. ".mysql_error());
	while($myrow = mysql_fetch_array($result)) {
		$spaceused+=$myrow["Data_length"];
		$spaceused+=$myrow["Index_length"];
	}
	$dbtotal+=$spaceused;
	$spaceused = format_bytes($spaceused);
	echo "<tr class=\"displayrow\"><td align=\"right\" width=\"50%\">";
	echo "$l_offlinebrowser:<br><span class=\"remark\">(".$leacherprefix."_leachers)</span></td>";
	echo "<td align=\"right\" width=\"20%\" valign=\"bottom\">$spaceused</td><td width=\"30%\">&nbsp;";
	echo "</td></tr>";
}
$dbtotal = format_bytes($dbtotal);
echo "<tr class=\"displayrow\"><td align=\"right\" width=\"50%\">";
echo "$l_total_amount:</td>";
echo "<td align=\"right\" width=\"20%\">$dbtotal</td><td width=\"30%\">&nbsp;";
echo "</td></tr>";
echo "<tr class=\"listheading0\"><td align=\"center\" colspan=\"3\">";
echo "<b>$l_diskusage</b>";
$excludedirs=array($path_icons."/",$path_emoticons."/");
$spaceused = disk_usage($path_simpnews."/gfx",255,$excludedirs);
$spaceused = format_bytes($spaceused);
$numfiles = count_files($path_simpnews."/gfx",255,$excludedirs);
echo "<tr class=\"displayrow\"><td align=\"right\" width=\"50%\">";
echo "$l_graphics:</td>";
echo "<td align=\"right\" width=\"20%\" valign=\"bottom\">$spaceused</td>";
echo "<td width=\"30%\" align=\"right\" valign=\"bottom\">$numfiles $l_files";
echo "</td></tr>";
$spaceused = disk_usage($path_emoticons,255);
$spaceused = format_bytes($spaceused);
$numfiles = count_files($path_emoticons,255);
echo "<tr class=\"displayrow\"><td align=\"right\" width=\"50%\">";
echo "$l_emoticons:<br><span class=\"remark\">(".$path_emoticons.")</td>";
echo "<td align=\"right\" width=\"20%\" valign=\"bottom\">$spaceused</td>";
echo "<td width=\"30%\" align=\"right\" valign=\"bottom\">$numfiles $l_files";
echo "</td></tr>";
$spaceused = disk_usage($path_icons,255);
$spaceused = format_bytes($spaceused);
$numfiles = count_files($path_icons,255);
echo "<tr class=\"displayrow\"><td align=\"right\" width=\"50%\">";
echo "$l_icons:<br><span class=\"remark\">(".$path_icons.")</span></td>";
echo "<td align=\"right\" width=\"20%\" valign=\"bottom\">$spaceused</td>";
echo "<td width=\"30%\" align=\"right\" valign=\"bottom\">$numfiles $l_files";
echo "</td></tr>";
if($attach_in_fs)
{
	$spaceused = disk_usage($path_attach,255);
	$spaceused = format_bytes($spaceused);
	$numfiles = count_files($path_attach,255);
	echo "<tr class=\"displayrow\"><td align=\"right\" width=\"50%\">";
	echo "$l_attachements:<br><span class=\"remark\">(".$path_attach.")</span></td>";
	echo "<td align=\"right\" width=\"20%\" valign=\"bottom\">$spaceused</td>";
	echo "<td width=\"30%\" align=\"right\" valign=\"bottom\">$numfiles $l_files";
	echo "</td></tr>";
}
if(isset($path_logfiles) && file_exists($path_logfiles))
{
	$spaceused = disk_usage($path_logfiles,255);
	$spaceused = format_bytes($spaceused);
	$numfiles = count_files($path_logfiles,255);
	echo "<tr class=\"displayrow\"><td align=\"right\" width=\"50%\">";
	echo "$l_logfiles:<br><span class=\"remark\">(".$path_logfiles.")</span></td>";
	echo "<td align=\"right\" width=\"20%\" valign=\"bottom\">$spaceused</td>";
	echo "<td width=\"30%\" align=\"right\" valign=\"bottom\">$numfiles $l_files";
	echo "</td></tr>";
}
$spaceused = disk_usage($path_simpnews,255);
$spaceused = format_bytes($spaceused);
echo "<tr class=\"displayrow\"><td align=\"right\" width=\"50%\">";
echo "$l_total_amount:</td>";
echo "<td align=\"right\" width=\"20%\">$spaceused</td><td width=\"30%\">&nbsp;";
echo "</td></tr>";
echo "</table></td></tr></table>";
include('./trailer.php');
?>