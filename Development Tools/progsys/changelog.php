<?php
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
/***************************************************************************
 * Created by: Boesch IT-Consulting (info@boesch-it.de)
 * (c)2002-2005 Boesch IT-Consulting
 * *************************************************************************/
require('./config.php');
require('./functions.php');
header('Pragma: no-cache');
header('Expires: 0');
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">";
$sql = "select * from ".$tableprefix."_layout where (layoutnr=1)";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
{
	$FontFace=$myrow["fontface"];
	$FontSize1=$myrow["fontsize1"];
	$FontSize2=$myrow["fontsize2"];
	$FontSize3=$myrow["fontsize3"];
	$FontSize4=$myrow["fontsize4"];
	$FontSize5=$myrow["fontsize5"];
	$FontColor=$myrow["fontcolor"];
	$TableWidth=$myrow["tablewidth"];
	$heading_bgcolor=$myrow["headingbg"];
	$table_bgcolor=$myrow["bgcolor1"];
	$row_bgcolor=$myrow["bgcolor2"];
	$group_bgcolor=$myrow["bgcolor3"];
	$page_bgcolor=$myrow["pagebg"];
	$HeadingFontColor=$myrow["headingfontcolor"];
	$SubheadingFontColor=$myrow["subheadingfontcolor"];
	$GroupFontColor=$myrow["groupfontcolor"];
	$LinkColor=$myrow["linkcolor"];
	$VLinkColor=$myrow["vlinkcolor"];
	$ALinkColor=$myrow["alinkcolor"];
	$TableDescFontColor=$myrow["tabledescfontcolor"];
	$dateformat=$myrow["dateformat"];
	$progsysmail=$myrow["progsysmail"];
	$server_timezone=$myrow["timezone"];
	$entriesperpage=$myrow["entriesperpage"];
	$checkrefs=$myrow["checkrefs"];
	$refchkaffects=$myrow["refchkaffects"];
	if(!$progsysmail)
		$progsysmail="progsys@foo.bar";
}
else
	die("Layout not set up");
if(!isset($lang) || !$lang)
	$lang=$default_lang;
if(!language_avail($lang))
	die ("Language <b>$lang</b> not configured");
include('language/lang_'.$lang.'.php');
if(($checkrefs==1) && bittst($refchkaffects,BIT_2))
{
	if(!ref_allowed())
		die("Direct linking from this site ($HTTP_REFERER) not allowed");
}
else if($checkrefs==2)
{
	if(ref_forbidden())
		die("Direct linking from this site ($HTTP_REFERER) not allowed");
}
if(isset($beta) && $protectbeta)
{
	if(!isset($REMOTE_USER) || !$REMOTE_USER)
		die($l_forbidden);
}
if(!isset($beta))
	$beta=0;
else
	$beta=1;
if(!isset($prog))
	die($l_callingerror);
$sql = "select * from ".$tableprefix."_programm where progid='$prog' and language='$lang'";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.");
if(!$myrow=mysql_fetch_array($result))
	die("No such programm");
if(($beta!=0) && ($myrow["hasbeta"]!=1))
	die($l_forbidden);
$stylesheet=$myrow["stylesheet"];
$usecustomheader=$myrow["usecustomheader"];
$usecustomfooter=$myrow["usecustomfooter"];
$headerfile=$myrow["headerfile"];
$footerfile=$myrow["footerfile"];
$pageheader=$myrow["pageheader"];
$pagefooter=$myrow["pagefooter"];
if((!$pageheader) && (!$headerfile))
	$usecustomheader=0;
if((!$pagefooter) && (!$footerfile))
	$usecustomfooter=0;
?>
<html>
<head>
<?php
if(file_exists("metadata.php"))
	include ("metadata.php");
?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
<title><?php echo $l_changelog_heading?></title>
<?php
echo"<link rel=stylesheet href=\"progsys.css\" type=\"text/css\">";
if($stylesheet)
	echo"<link rel=stylesheet href=\"$stylesheet\" type=\"text/css\">";
?>
</head>
<body bgcolor="<?php echo $page_bgcolor?>" link="<?php echo $LinkColor?>" vlink="<?php echo $VLinkColor?>" alink="<?php echo $ALinkColor?>" text="<?php echo $FontColor?>">
<?php
if($usecustomheader==1)
{
	if($headerfile)
		include($headerfile);
	echo $pageheader;
}
?>
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="CENTER" VALIGN="TOP">
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize3?>" color="<?php echo $HeadingFontColor?>"><b><?php echo $l_changelog_heading?></b></font></td>
<?php
$sql = "select * from ".$tableprefix."_misc";
if(!$result = mysql_query($sql, $db)) {
    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
{
	if($myrow["shutdown"]==1)
	{
?>
</tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $SubheadingFontColor?>">
<?php
		$shutdowntext=stripslashes($myrow["shutdowntext"]);
		$shutdowntext = undo_htmlspecialchars($shutdowntext);
		echo $shutdowntext;
		echo "</font></td></tr></table></td></tr></table>";
		echo "<br><div align=\"center\"><font face=\"$FontFace\" SIZE=\"$FontSize4\">";
		echo "<span class=\"timezone\">$l_timezone_note ".timezonename($server_timezone);
		$gmtoffset=tzgmtoffset($server_timezone);
		if($gmtoffset)
			echo " (".$gmtoffset.")";
		echo "</span><br>";
		echo "$l_powered_by $copyright_url, $copyright_note</font></div>";
		exit;
	}
}
$sql = "select * from ".$tableprefix."_programm where progid='$prog' and language='$lang'";
if(!$result = mysql_query($sql, $db))
    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
if(!$myrow=mysql_fetch_array($result))
	die ("<tr bgcolor=\"$group_bgcolor\" align=\"center\"><td>$l_noentries</td></tr>");
$prognr=$myrow["prognr"];
$progname=$myrow["programmname"];
$progheading=$l_programm.": ".$progname;
if($beta!=0)
	$progheading.=" (Beta)";
?>
<tr BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" width="100%"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $SubheadingFontColor?>"><b><?php echo $progheading?></b></font>
</td>
</tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
$sql = "select * from ".$tableprefix."_changelog where programm='$prognr' and isbeta='$beta' order by versiondate desc, version desc";
if(!$result = mysql_query($sql, $db))
    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.".mysql_error());
$numentries=mysql_numrows($result);
if($numentries<1)
	echo "<tr bgcolor=\"$group_bgcolor\" align=\"center\"><td>$l_noentries</td></tr>";
else
{
	if($entriesperpage>0)
	{
?>
<TR BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize5?>" color="<?php echo $SubheadingFontColor?>">
<?php
		if(isset($start) && ($start>0) && ($numentries>$entriesperpage))
		{
			$sql .=" limit $start,$entriesperpage";
		}
		else
		{
			$sql .=" limit $entriesperpage";
			$start=0;
		}
		if(!$result = mysql_query($sql, $db))
		    die("Unable to connect to database.".mysql_error());
		if(mysql_num_rows($result)>0)
		{
			if(($entriesperpage+$start)>$numentries)
				$displayresults=$numentries;
			else
				$displayresults=($entriesperpage+$start);
			$displaystart=$start+1;
			$displayend=$displayresults;
			echo "<b>$l_page ".ceil(($start/$entriesperpage)+1)."/".ceil(($numentries/$entriesperpage))."</b><br><b>($l_entries $displaystart - $displayend $l_of $numentries)</b>";
		}
		else
			echo "&nbsp;";
		echo "</font></td></tr>";
	}
	if(($entriesperpage>0) && ($numentries>$entriesperpage))
	{
		echo "<tr bgcolor=\"$heading_bgcolor\"><td align=\"center\" colspan=\"2\">";
		echo "<font face=\"$FontFace\" size=\"$FontSize1\" color=\"$SubheadingFontColor\">";
		if(floor(($start+$entriesperpage)/$entriesperpage)>1)
		{
			echo "<a href=\"$act_script_url?lang=$lang&amp;prog=$prog&amp;start=0";
			if($beta==1)
				echo"&amp;beta=1";
			echo "\"><b>[&lt;&lt;]</b></a> ";
			echo "<a href=\"$act_script_url?lang=$lang&amp;prog=$prog&amp;start=".($start-$entriesperpage);
			if($beta==1)
				echo"&amp;beta=1";
			echo "\"><b>[&lt;]</b></a> ";
		}
		for($i=1;$i<($numentries/$entriesperpage)+1;$i++)
		{
			if(floor(($start+$entriesperpage)/$entriesperpage)!=$i)
			{
				echo "<a href=\"$act_script_url?lang=$lang&amp;prog=$prog&amp;start=".(($i-1)*$entriesperpage);
				if($beta==1)
					echo"&amp;beta=1";
				echo "\"><b>[$i]</b></a> ";
			}
			else
				echo "<b>($i)</b> ";
		}
		if($start < (($i-2)*$entriesperpage))
		{
			echo "<a href=\"$act_script_url?lang=$lang&amp;prog=$prog&amp;start=".($start+$entriesperpage);
			if($beta==1)
				echo"&amp;beta=1";
			echo "\"><b>[&gt;]</b></a> ";
			echo "<a href=\"$act_script_url?lang=$lang&amp;prog=$prog&amp;start=".(($i-2)*$entriesperpage);
			if($beta==1)
				echo"&amp;beta=1";
			echo "\"><b>[&gt;&gt;]</b></a> ";
		}
		echo "</font></td></tr>";
	}
	while($myrow=mysql_fetch_array($result))
	{
		list($year, $month, $day) = explode("-", $myrow["versiondate"]);
		if($month>0)
			$displaydate=date($dateformat,mktime(0,0,0,$month,$day,$year));
		else
			$displaydate="";
		echo "<tr bgcolor=\"$group_bgcolor\">";
		echo "<td align=\"left\">$l_version ".$myrow["version"].", ".$displaydate."</td></tr>";
		echo "<tr bgcolor=\"$row_bgcolor\"><td align=\"left\">";
		$changelogtext=stripslashes($myrow["changes"]);
		$changelogtext = undo_htmlspecialchars($changelogtext);
		echo $changelogtext;
		echo "</td></tr>";
	}
	if(($entriesperpage>0) && ($numentries>$entriesperpage))
	{
		echo "<tr bgcolor=\"$heading_bgcolor\"><td align=\"center\" colspan=\"2\">";
		echo "<font face=\"$FontFace\" size=\"$FontSize1\" color=\"$SubheadingFontColor\">";
		if(floor(($start+$entriesperpage)/$entriesperpage)>1)
		{
			echo "<a href=\"$act_script_url?lang=$lang&amp;prog=$prog&amp;start=0";
			if($beta==1)
				echo"&amp;beta=1";
			echo "\"><b>[&lt;&lt;]</b></a> ";
			echo "<a href=\"$act_script_url?lang=$lang&amp;prog=$prog&amp;start=".($start-$entriesperpage);
			if($beta==1)
				echo"&amp;beta=1";
			echo "\"><b>[&lt;]</b></a> ";
		}
		for($i=1;$i<($numentries/$entriesperpage)+1;$i++)
		{
			if(floor(($start+$entriesperpage)/$entriesperpage)!=$i)
			{
				echo "<a href=\"$act_script_url?lang=$lang&amp;prog=$prog&amp;start=".(($i-1)*$entriesperpage);
				if($beta==1)
					echo"&amp;beta=1";
				echo "\"><b>[$i]</b></a> ";
			}
			else
				echo "<b>($i)</b> ";
		}
		if($start < (($i-2)*$entriesperpage))
		{
			echo "<a href=\"$act_script_url?lang=$lang&amp;prog=$prog&amp;start=".($start+$entriesperpage);
			if($beta==1)
				echo"&amp;beta=1";
			echo "\"><b>[&gt;]</b></a> ";
			echo "<a href=\"$act_script_url?lang=$lang&amp;prog=$prog&amp;start=".(($i-2)*$entriesperpage);
			if($beta==1)
				echo"&amp;beta=1";
			echo "\"><b>[&gt;&gt;]</b></a> ";
		}
		echo "</font></td></tr>";
	}
}
?>
</table></td></tr></table>
<?php
echo "<br><div align=\"center\"><font face=\"$FontFace\" SIZE=\"$FontSize4\">";
echo "<span class=\"timezone\">$l_timezone_note ".timezonename($server_timezone);
$gmtoffset=tzgmtoffset($server_timezone);
if($gmtoffset)
	echo " (".$gmtoffset.")";
echo "</span><br>";
echo "$l_powered_by $copyright_url, $copyright_note</font></div>";
if($usecustomfooter==1)
{
	if($footerfile)
		include($footerfile);
	echo $pagefooter;
}
?>
</body></html>
