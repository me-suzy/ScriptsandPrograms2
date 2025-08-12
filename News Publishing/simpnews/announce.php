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
require_once('./includes/entry_functions.inc');
$heading=$eventheading;
$pageheading=$l_announcements;
$actdate = date("Y-m-d H:i:00");
if($lastvisitcookie==1)
	include("./includes/lastvisit.inc");
include('./includes/header.inc');
?>
<div align="<?php echo $tblalign?>">
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tblalign?>" class="sntable">
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<a name="top"></a>
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
if(strlen($pageheading)>0)
{
?>
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><b><?php echo $pageheading?></b></font></td></tr>
<?php
}
if($catgeory<0)
	$enablepropose=0;
if(isset($backurl) || $category>0)
{
	echo "<tr bgcolor=\"$headingbgcolor\" width=\"5%\">";
	echo "<td align=\"center\">";
	if(isset($backurl))
		echo "<a href=\"$backurl\"><img src=\"$url_gfx/$backpic\" border=\"0\" align=\"absmiddle\" title=\"$l_back\" alt=\"$l_back\"></a>";
	else
		echo "&nbsp;";
	echo "</td>";
	echo "<td align=\"center\">";
	if($category>0)
	{
		$sql = "select * from ".$tableprefix."_categories where catnr='$category'";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if($myrow=mysql_fetch_array($result))
		{
			$cattext=display_encoded(stripslashes($myrow["catname"]));
			$tmpsql="select * from ".$tableprefix."_catnames where catnr=".$myrow["catnr"]." and lang='".$act_lang."'";
			if(!$tmpresult=mysql_query($tmpsql,$db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			if($tmprow=mysql_fetch_array($tmpresult))
			{
				if(strlen($tmprow["catname"])>0)
					$cattext=display_encoded(stripslashes($tmprow["catname"]));
			}
			if($enablepropose==1)
				$enablepropose=$myrow["enablepropose"];
			echo "<font face=\"$headingfont\" size=\"$contentfontsize\" color=\"$headingfontcolor\">";
			echo "<b>".$cattext."</b></font>";
			$catfooteroptions=$myrow["footeroptions"];
			$catfooter=$myrow["customfooter"];
			if(!$catfooter)
				$catfooteroptions=0;
			if(bittst($catfooteroptions,BIT_1) && bittst($catfooteroptions,BIT_2))
			{
				$customfooter=$catfooter;
				$usecustomfooter=1;
				$footerfile="";
			}
		}
	}
	else
		echo "&nbsp;";
	echo "</td></tr>";
}
$acttime=transposetime(time(),$servertimezone,$displaytimezone);
$sql = "select * from ".$tableprefix."_announce where (expiredate>=$acttime or expiredate=0) and (firstdate<=$acttime or firstdate=0)";
if(isset($announcenr))
{
	$sql.="and entrynr=$announcenr";
	if($useviewcounts==1)
	{
		$tmpsql = "UPDATE ".$tableprefix."_announce SET views = views + 1 WHERE (entrynr = $announcenr)";
		@mysql_query($tmpsql, $db);
	}
}
else
{
	if(isset($link_date))
		$sql.=" and DATE_FORMAT(date,'%Y-%m-%d')='$link_date' ";
	else
	{
		if(isset($startdate))
			$sql.= "and DATE_FORMAT(date,'%Y-%m-%d')>='$startdate' ";
		if(isset($enddate))
			$sql.= "and DATE_FORMAT(date,'%Y-%m-%d')<='$enddate' ";
	}
	if($separatebylang==1)
		$sql.="and lang='$act_lang' ";
	if($category>0)
		$sql.= "and (category='$category' or category=0)";
	else if($category==0)
		$sql.= "and category=0";
	if(!isset($link_date))
		$sql.=" order by date desc";
	else
		$sql.=" order by category asc";
}
if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
if(mysql_num_rows($result)>0)
{
	while($myrow=mysql_fetch_array($result))
	{
		echo "<tr><td width=\"2%\" height=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\">";
		if($myrow["headingicon"])
			echo "<img src=\"$url_icons/".$myrow["headingicon"]."\" border=\"0\" align=\"middle\"> ";
		else
			echo "&nbsp;";
		echo "</td>";
		echo "<td align=\"center\"><table class=\"eventbox\" width=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\" cellspacing=\"0\" cellpadding=\"0\">";
		list($mydate,$mytime)=explode(" ",$myrow["date"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
		{
			$temptime=mktime($hour,$min,$sec,$month,$day,$year);
			$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
			$displaydate=date($dateformat,$temptime);
		}
		else
			$displaydate="";
		echo "<tr><td align=\"left\" bgcolor=\"$timestampbgcolor\" colspan=\"3\">";
		if($myrow["category"]==0)
			echo "<img src=\"$url_gfx/$gannouncepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_global_announcement\" title=\"$l_global_announcement\"> ";
		else
			echo "<img src=\"$url_gfx/$announcepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_announcement\" title=\"$l_announcement\"> ";
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
			echo undo_html_ampersand(do_htmlentities($myrow["heading"]));
			echo get_end_tag($newsheadingstyle);
			echo "</font></td></tr>";
		}
		echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\" colspan=\"3\">";
		echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		$displaytext=stripslashes($myrow["text"]);
		$displaytext = undo_htmlspecialchars($displaytext);
		echo $displaytext."</font></td></tr>";
		if($displayposter && (strlen($myrow["poster"])>0) && ($attachpos==0))
			posterline($myrow["posterid"], $myrow["poster"], $linkposter);
		if($attachpos==0)
			echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\">";
		displayattachs_announce($myrow["entrynr"],$attachpos, $nofileinfo);
		if($attachpos==1)
		{
			if($displayposter && (strlen($myrow["poster"])>0))
				posterline($myrow["posterid"], $myrow["poster"], $linkposter);
			echo "<tr bgcolor=\"$contentbgcolor\"><td>&nbsp;</td>";
		}
		echo "<td align=\"right\" colspan=\"2\">";
		echo "<font face=\"$contentfont\" size=\"1\" color=\"$contentfontcolor\">";
		if($noprinticon==0)
		{
			echo "<a href=\"announceprint.php?$langvar=$act_lang&layout=$layout&announcenr=".$myrow["entrynr"]."\" target=\"printwindow\">";
			if($printpic_small)
				echo "<img class=\"iconbutton\" src=\"$url_gfx/$printpic_small\" border=\"0\" align=\"absmiddle\" title=\"$l_print\" alt=\"$l_print\">";
			else
				echo "[$l_print]";
			echo "</a>";
		}
		if($nogotopicon==0)
			echo "<a href=\"#top\"><img class=\"iconbutton\" src=\"$url_gfx/$pagetoppic\" border=\"0\" align=\"absmiddle\" title=\"$l_gotop\" alt=\"$l_gotop\"></a>";
		if($emailnews==1)
			echo "<a href=\"anmail.php?$langvar=$act_lang&layout=$layout&announcenr=".$myrow["entrynr"]."\" target=\"mailwindow\"><img class=\"iconbutton\" src=\"$url_gfx/$emailpic\" border=\"0\" align=\"absmiddle\" title=\"$l_emailentry\" alt=\"$l_emailentry\"></a>";
		echo "</font></td></tr>";
		echo "</table></td></tr>";
	}
}
echo "</table></td></tr></table></div>";
include ("./includes/footer.inc");
?>