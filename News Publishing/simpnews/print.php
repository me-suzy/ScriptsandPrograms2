<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('./config.php');
require_once('./functions.php');
if(!isset($category))
	$category=0;
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./includes/get_settings.inc');
require_once('./includes/block_leacher.inc');
if(!isset($newsnr))
	die($l_callingerror);
$actdate = date("Y-m-d H:i:00");
if($heading)
	$pageheading=$heading;
else
	$pageheading=$l_news;
$headingbgcolor="#ffffff";
$headingfontcolor="#000000";
$pagebgcolor="#ffffff";
$bordercolor="#000000";
$contentbgcolor="#ffffff";
$contentfontcolor="#000000";
$timestampfontcolor="#000000";
$newsheadingbgcolor="#ffffff";
$posterbgcolor="#ffffff";
$posterfontcolor="#000000";
$timestampbgcolor="#ffffff";
$copyrightbgcolor="#eeeeee";
$copyrightfontcolor="#000000";
$styletype=2;
if($printheader==0)
{
	$usecustomheader=0;
	$usecustomfooter=0;
}
include('./includes/header.inc');
?>
<div align="<?php echo $tblalign?>">
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tblalign?>" class="sntable">
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<a name="top"></a>
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
if(strlen($heading)>0)
{
?>
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><b><?php echo $heading?></b></font></td></tr>
<?php
}
$sql = "select * from ".$tableprefix."_data where newsnr=$newsnr";
if(!$result = mysql_query($sql, $db))
    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
if(!$myrow=mysql_fetch_array($result))
	die("<tr class=\"errorrow\"><td>".$l_nosuchentry);
if($myrow["category"]>0)
{
	$sql = "select * from ".$tableprefix."_categories where catnr=".$myrow["category"];
	if(!$result2 = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if($myrow2=mysql_fetch_array($result2))
	{
		$cattext=display_encoded(stripslashes($myrow2["catname"]));
		$tmpsql="select * from ".$tableprefix."_catnames where catnr=".$myrow2["catnr"]." and lang='".$act_lang."'";
		if(!$tmpresult=mysql_query($tmpsql,$db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if($tmprow=mysql_fetch_array($tmpresult))
		{
			if(strlen($tmprow["catname"])>0)
				$cattext=display_encoded(stripslashes($tmprow["catname"]));
		}
		echo "<tr bgcolor=\"$headingbgcolor\">";
		echo "<td align=\"center\" colspan=\"2\">";
		echo "<font face=\"$headingfont\" size=\"$contentfontsize\" color=\"$headingfontcolor\">";
		echo "<b>".$cattext."</b>";
		echo "</font></td></tr>";
	}
}
echo "<tr><td width=\"2%\" height=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\">";
if($myrow["headingicon"])
	echo "<img src=\"$url_icons/".$myrow["headingicon"]."\" border=\"0\" align=\"middle\"> ";
else
	echo "&nbsp;";
echo "</td>";
echo "<td align=\"center\"><table class=\"newsbox\" width=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\" cellspacing=\"0\" cellpadding=\"0\">";
list($mydate,$mytime)=explode(" ",$myrow["date"]);
list($year, $month, $day) = explode("-", $mydate);
list($hour, $min, $sec) = explode(":",$mytime);
if($month>0)
{
	$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
	$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
	$displaydate=date($dateformat,$displaytime);
}
else
	$displaydate="";
echo "<tr><td align=\"left\" bgcolor=\"$timestampbgcolor\"";
echo "width=\"80%\"";
echo ">";
echo "<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
echo get_start_tag($timestampstyle);
echo $displaydate;
echo get_end_tag($timestampstyle);
echo "</font></td>";
echo "</tr>";
if(strlen($myrow["heading"])>0)
{
	echo "<tr bgcolor=\"$newsheadingbgcolor\"><td align=\"left\" colspan=\"3\">";
	echo "<font face=\"$newsheadingfont\" size=\"$newsheadingfontsize\" color=\"$newsheadingfontcolor\">";
	echo get_start_tag($newsheadingstyle);
	echo do_htmlentities($myrow["heading"]);
	echo get_end_tag($newsheadingstyle);
	echo "</font></td></tr>";
}
echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\" colspan=\"3\">";
echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
$displaytext=stripslashes($myrow["text"]);
$displaytext = undo_htmlspecialchars($displaytext);
echo $displaytext."</font></td></tr>";
if($displayposter && (strlen($myrow["poster"])>0))
{
	echo "<tr bgcolor=\"$posterbgcolor\"><td align=\"left\" colspan=\"3\">";
	echo "<font face=\"$posterfont\" size=\"$posterfontsize\" color=\"$posterfontcolor\">";
	echo get_start_tag($posterstyle);
	echo "$l_poster: ".do_htmlentities($myrow["poster"]);
	echo get_end_tag($posterstyle);
	echo "</font></td></tr>";
}
echo "</table></td></tr>";
echo "</table></td></tr></table></div>";
include ("./includes/footer3.inc");
?>