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
if(!isset($sortorder))
	$sortorder=0;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./includes/get_settings.inc');
require_once('./includes/block_leacher.inc');
require_once('./includes/entry_functions.inc');
$heading=$eventheading;
if($eventheading)
	$pageheading=$eventheading;
else
	$pageheading=$l_events;
$baseurl="$act_script_url?$langvar=$act_lang&layout=$layout&category=$category&sortorder=$sortorder";
if(isset($startdate))
	$baseurl.="&startdate=$startdate";
if(isset($enddate))
	$baseurl.="&enddate=$enddate";
if(isset($limitdays))
	$baseurl.="&limitdays=$limitdays";
$localbackurl=urlencode($baseurl);
$actdate = date("Y-m-d 23:59:59");
if(isset($limitdays))
{
	$startdate=date("Y-m-d 00:00:00");
	$enddate= date("Y-m-d 00:00:00",time()+($limitdays*24*60*60));
}
$propdisplayed=false;
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
if(strlen($eventheading)>0)
{
?>
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><b><?php echo $eventheading?></b></font></td></tr>
<?php
}
if(($enableevpropose==1) && ($maxpropose>0))
{
	$sql="select count(entrynr) as numpropose from ".$tableprefix."_tmpevents";
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if($myrow=mysql_fetch_array($result))
		if($myrow["numpropose"]>=$maxpropose)
			$enableevpropose=0;
}
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
		if($enableevpropose==1)
			$enableevpropose=$myrow["enablepropose"];
		echo "<tr bgcolor=\"$headingbgcolor\">";
		echo "<td align=\"center\"";
		if(bittst($myrow["iconoptions"],BIT_6))
			echo "><img src=\"".$url_icons."/".$myrow["icon"]."\" border=\"0\" align=\"middle\"></td><td align=\"center\">";
		else
			echo " colspan=\"2\">";
		echo "<font face=\"$headingfont\" size=\"$contentfontsize\" color=\"$headingfontcolor\">";
		echo "<b>".$cattext."</b>";
		echo "</font></td></tr>";
		if($myrow["headertext"])
		{
			echo "<tr bgcolor=\"$catinfobgcolor\">";
			echo "<td class=\"catinfo\" align=\"left\" colspan=\"2\">";
			echo "<font face=\"$catinfofont\" size=\"$catinfofontsize\" color=\"$catinfofontcolor\">";
			$displaytext=stripslashes($myrow["headertext"]);
			$displaytext = undo_htmlspecialchars($displaytext);
			echo $displaytext;
			echo "</font></td></tr>";
		}
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
if(isset($backurl) || ($enableevpropose==1) || isset($link_date) || ($enableevsearch==1))
{
	echo "<tr bgcolor=\"$headingbgcolor\">";
	echo "<td align=\"center\" width=\"5%\">";
	if(isset($backurl) || ($enableevpropose==1) || ($enableevsearch==1))
	{
		if(isset($backurl))
			echo "<a href=\"$backurl\"><img src=\"$url_gfx/$backpic\" border=\"0\" align=\"absmiddle\" title=\"$l_back\" alt=\"$l_back\"></a>";
		if(($enableevpropose==1) && ($category>=0))
		{
			$propdisplayed=true;
			if(isset($backurl))
				echo " ";
			echo "<a href=\"evpropose.php?$langvar=$act_lang&amp;layout=$layout&amp;category=$category&amp;mode=new&amp;backurl=";
			echo $localbackurl;
			echo "\">";
			echo "<img src=\"$url_gfx/$proposepic\" border=\"0\" align=\"absmiddle\" title=\"$l_propose_event\" alt=\"$l_propose_event\"></a>";
		}
		if($enableevsearch==1)
		{
			echo "<a href=\"evsearch.php?$langvar=$act_lang&amp;layout=$layout&amp;category=$category&amp;backurl=";
			echo $localbackurl;
			echo "\">";
			echo "<img src=\"$url_gfx/$searchpic\" border=\"0\" align=\"absmiddle\" title=\"$l_search\" alt=\"$l_search\"></a>";
		}
	}
	echo "&nbsp;";
	echo "</td>";
	if(isset($link_date))
	{
		list($year, $month, $day) = explode("-", $link_date);
		if($month>0)
			$displaydate=date($event_dateformat,mktime(0,0,0,$month,$day,$year));
		else
			$displaydate="";
		echo "<td align=\"center\">";
		echo "<font face=\"$headingfont\" size=\"$contentfontsize\" color=\"$headingfontcolor\">";
		echo "<b>$displaydate</b></font></td></tr>";
	}
	else
		echo "<td>&nbsp;</td></tr>";
}
$announceavail=false;
if(bittst($announceoptions,BIT_1))
{
		$acttime=transposetime(time(),$servertimezone,$displaytimezone);
		$sql = "select * from ".$tableprefix."_announce where (expiredate>=$acttime or expiredate=0) and (firstdate<=$acttime or firstdate=0)";
		if(isset($link_date))
			$sql.=" and DATE_FORMAT(date,'%Y-%m-%d')='$link_date' ";
		if($separatebylang==1)
			$sql.="and lang='$act_lang' ";
		if($category>0)
			$sql.= "and (category='$category' or category=0)";
		else if($category==0)
			$sql.= "and category=0";
		if(isset($link_date))
			$sql.=" order by category asc";
		else
		{
			switch($sortorder)
			{
				case 0:
					$sql.=" order by date desc";
					break;
				case 1:
					$sql.=" order by date asc";
					break;
				case 2:
					$sql.=" order by heading asc";
					break;
				case 3:
					$sql.=" order by heading desc";
					break;
			}
		}
		if(isset($maxannounce))
			$sql.=" limit $maxannounce";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$announceavail=true;
			while($myrow=mysql_fetch_array($result))
			{
				if(($useviewcounts==1) && isset($link_date))
				{
					$tmpsql = "UPDATE ".$tableprefix."_announce SET views = views + 1 WHERE (entrynr = ".$myrow["entrynr"].")";
					@mysql_query($tmpsql, $db);
				}
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
				echo "</td></tr>";
				echo "</table></td></tr>";
			}
		}
}
$eventsavail=false;
$sql = "select * from ".$tableprefix."_events";
if(isset($eventnr))
	$sql.=" where eventnr='".$eventnr."'";
else
{
	if($category>=0)
		$sql.=" where category='$category'";
	else
	{
		$sql.=" where linkeventnr=0";
		$tmpsql="select * from ".$tableprefix."_categories where hideintotallist=1";
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		while($tmprow=mysql_fetch_array($tmpresult))
			$sql.=" and category!=".$tmprow["catnr"];
	}
	if(isset($link_date))
		$sql.=" and DATE_FORMAT(date,'%Y-%m-%d')='$link_date'";
	else
	{
		if(isset($startdate))
			$sql.= " and DATE_FORMAT(date,'%Y-%m-%d')>='$startdate' ";
		if(isset($enddate))
			$sql.= " and DATE_FORMAT(date,'%Y-%m-%d')<='$enddate'";
	}
	if($separatebylang==1)
		$sql.=" and lang='$act_lang'";
	if(!isset($link_date))
	{
		switch($sortorder)
		{
			case 0:
				$sql.=" order by date desc";
				break;
			case 1:
				$sql.=" order by date asc";
				break;
			case 2:
				$sql.=" order by heading asc";
				break;
			case 3:
				$sql.=" order by heading desc";
				break;
		}
	}
}
if(!$result = mysql_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
if(mysql_num_rows($result)>0)
{
	$eventsavail=true;
	echo "<tr bgcolor=\"$headingbgcolor\">";
	echo "<td align=\"center\" width=\"5%\">";
	if(bittst($announceoptions,BIT_1) && ($announceavail==true))
	{
		if(isset($backurl))
			echo "<a href=\"$backurl\"><img src=\"$url_gfx/$backpic\" border=\"0\" align=\"absmiddle\" title=\"$l_back\" alt=\"$l_back\"></a>";
		else
			echo "&nbsp;";
	}
	else
		echo "&nbsp;";
	echo "</td>";
	if(isset($link_date))
	{
		echo "<td align=\"center\">";
		echo "<font face=\"$headingfont\" size=\"$contentfontsize\" color=\"$headingfontcolor\">";
		echo "<b>$l_eventsforthisdate</b></font>";
	}
	else
	{
		echo "<td align=\"center\">";
		echo "<font face=\"$headingfont\" size=\"$contentfontsize\" color=\"$headingfontcolor\">";
		echo "<b>$l_events</b></font>";
	}
	echo "</td></tr>";
}
while($myrow=mysql_fetch_array($result))
{
	if($myrow["linkeventnr"]==0)
		$entrydata=$myrow;
	else
	{
		$tmpsql="select * from ".$tableprefix."_events where eventnr=".$myrow["linkeventnr"];
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if(!$tmprow=mysql_fetch_array($tmpresult))
			die("<tr class=\"errorrow\"><td>Unable to get data");
		$entrydata=$tmprow;
	}
	if(($useviewcounts==1) && isset($link_date))
	{
		$tmpsql = "UPDATE ".$tableprefix."_events SET views = views + 1 WHERE (eventnr = ".$entrydata["eventnr"].")";
		@mysql_query($tmpsql, $db);
	}
	echo "<tr>";
	echo "<td width=\"2%\" height=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\">";
	echo "<a name=\"".$myrow["eventnr"]."\">";
	if($entrydata["headingicon"])
		echo "<img src=\"$url_icons/".$entrydata["headingicon"]."\" border=\"0\" align=\"middle\"> ";
	else
		echo "&nbsp;";
	echo "</a>";
	echo "</td>";
	echo "<td align=\"center\"><table class=\"eventbox\" width=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\" cellspacing=\"0\" cellpadding=\"0\">";
	list($tmpdate, $tmptime)=explode(" ",$entrydata["date"]);
	list($year, $month, $day) = explode("-", $tmpdate);
	list($hour, $min, $null) = explode(":", $tmptime);
	if(($hour>0) || ($min>0))
		$displaydate=date($event_dateformat2,mktime($hour,$min,0,$month,$day,$year));
	else
		$displaydate=date($event_dateformat,mktime(0,0,0,$month,$day,$year));
	echo "<tr><td align=\"left\" bgcolor=\"$timestampbgcolor\" colspan=\"3\">";
	if(isset($highlight) && ($highlight==$myrow["eventnr"]))
		echo "<img src=\"$url_gfx/$highlightmarker\" border=\"0\"> ";
	echo "<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
	echo get_start_tag($timestampstyle);
	echo $displaydate;
	echo get_end_tag($timestampstyle);
	if(isset($lastvisitdate))
	{
		list($mydate,$mytime)=explode(" ",$myrow["added"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		$thisentrydate=mktime($hour,$min,$sec,$month,$day,$year);
		if($thisentrydate>=$lastvisitdate)
			echo "&nbsp;&nbsp;<img src=\"$url_gfx/$newentrypic\" border=\"0\" align=\"absmiddle\">";
	}
	echo "</font></td>";
	echo "</tr>";
	if(strlen($entrydata["heading"])>0)
	{
		echo "<tr bgcolor=\"$newsheadingbgcolor\"><td align=\"left\" colspan=\"3\">";
		echo "<font face=\"$newsheadingfont\" size=\"$newsheadingfontsize\" color=\"$newsheadingfontcolor\">";
		echo get_start_tag($newsheadingstyle);
		echo undo_html_ampersand(do_htmlentities($entrydata["heading"]));
		echo get_end_tag($newsheadingstyle);
		echo "</font></td></tr>";
	}
	echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\" colspan=\"3\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	$displaytext=stripslashes($entrydata["text"]);
	$displaytext = undo_htmlspecialchars($displaytext);
	echo $displaytext."</font></td></tr>";
	if($displayposter && (strlen($entrydata["poster"])>0) && ($attachpos==0))
		posterline($entrydata["posterid"], $entrydata["poster"], $linkposter, $entrydata["exposter"]);
	if($attachpos==0)
		echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\">";
	displayattachs_events($entrydata["eventnr"],$attachpos, $nofileinfo);
	if($attachpos==1)
	{
		if($displayposter && (strlen($entrydata["poster"])>0))
			posterline($entrydata["posterid"], $entrydata["poster"], $linkposter, $entrydata["exposter"]);
		echo "<tr bgcolor=\"$contentbgcolor\"><td>&nbsp;</td>";
	}
	echo "<td align=\"right\" colspan=\"2\">";
	if($noprinticon==0)
	{
		echo "<a href=\"evprint.php?$langvar=$act_lang&layout=$layout&eventnr=".$entrydata["eventnr"]."\" target=\"printwindow\">";
		if($printpic_small)
			echo "<img class=\"iconbutton\" src=\"$url_gfx/$printpic_small\" border=\"0\" align=\"absmiddle\" title=\"$l_print\" alt=\"$l_print\">";
		else
			echo "[$l_print]";
		echo "</a>";
	}
	if($nogotopicon==0)
		echo "<a href=\"#top\"><img class=\"iconbutton\" src=\"$url_gfx/$pagetoppic\" border=\"0\" align=\"absmiddle\" title=\"$l_gotop\" alt=\"$l_gotop\"></a>";
	echo "</td></tr>";
	echo "</table></td></tr>";
}
if(($newsineventcal==1) && isset($link_date))
{
$sql = "select * from ".$tableprefix."_data where DATE_FORMAT(date,'%Y-%m-%d')='$link_date' ";
$sql.= "and date<='$actdate' ";
if($separatebylang==1)
	$sql.="and lang='$act_lang' ";
if($category>=0)
	$sql.= "and category='$category' ";
else
	$sql.= "and linknewsnr=0 ";
if(!isset($link_date))
{
	switch($sortorder)
	{
		case 0:
			$sql.=" order by date desc";
			break;
		case 1:
			$sql.=" order by date asc";
			break;
		case 2:
			$sql.=" order by heading asc";
			break;
		case 3:
			$sql.=" order by heading desc";
			break;
	}
}
if(!$result = mysql_query($sql, $db))
    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
if(mysql_num_rows($result)>0)
{
	echo "<tr bgcolor=\"$headingbgcolor\">";
	echo "<td align=\"center\">";
	if((bittst($announceoptions,BIT_1) && $announceavail) || ($eventsavail))
	{
		if(isset($backurl))
			echo "<a href=\"$backurl\"><img src=\"$url_gfx/$backpic\" border=\"0\" align=\"absmiddle\" title=\"$l_back\" alt=\"$l_back\"></a>";
		else
			echo "&nbsp;";
	}
	else
		echo "&nbsp;";
	echo "</td>";
	echo "<td align=\"center\">";
	echo "<font face=\"$headingfont\" size=\"$contentfontsize\" color=\"$headingfontcolor\">";
	echo "<b>$l_newsforthisdate</b></font></td></tr>";
}
while($myrow=mysql_fetch_array($result))
{
	if($myrow["linknewsnr"]==0)
		$entrydata=$myrow;
	else
	{
		$tmpsql="select * from ".$tableprefix."_data where newsnr=".$myrow["linknewsnr"];
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if(!$tmprow=mysql_fetch_array($tmpresult))
			die("<tr class=\"errorrow\"><td>Unable to get data");
		$entrydata=$tmprow;
	}
	echo "<tr><td width=\"2%\" height=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\">";
	if($entrydata["headingicon"])
		echo "<img src=\"$url_icons/".$entrydata["headingicon"]."\" border=\"0\" align=\"middle\"> ";
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
	echo "<tr><td align=\"left\" bgcolor=\"$timestampbgcolor\"";
	if(($allowcomments==0) || ($myrow["allowcomments"]==0))
		echo " colspan=\"3\"";
	else
		echo " width=\"80%\"";
	echo ">";
	echo "<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
	echo get_start_tag($timestampstyle);
	echo $displaydate;
	echo get_end_tag($timestampstyle);
	if(isset($lastvisitdate))
	{
		list($mydate,$mytime)=explode(" ",$myrow["date"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		$thisentrydate=mktime($hour,$min,$sec,$month,$day,$year);
		if($thisentrydate>=$lastvisitdate)
			echo "&nbsp;&nbsp;<img src=\"$url_gfx/$newentrypic\" border=\"0\" align=\"absmiddle\">";
	}
	echo "</font></td>";
	if(($allowcomments==1) && ($entrydata["allowcomments"]==1))
	{
		echo "<td align=\"right\" bgcolor=\"$timestampbgcolor\" width=\"10%\" valign=\"top\">";
		echo "<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
		$tempsql="select * from ".$tableprefix."_comments where entryref=".$entrydata["newsnr"];
		if(!$tempresult = mysql_query($tempsql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		$numcomments=mysql_num_rows($tempresult);
		if($numcomments>0)
			echo "<a class=\"commentlink\" href=\"comment.php?mode=display&amp;$langvar=$act_lang&amp;entryref=".$entrydata["newsnr"]."\">$l_comments:&nbsp;$numcomments</a>&nbsp;&nbsp;";
		else
			echo "&nbsp;";
		echo "</font></td>";
		echo "<td align=\"center\" bgcolor=\"$timestampbgcolor\" width=\"10%\">";
		echo "<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
		echo "<a class=\"commentlink\" href=\"comment.php?$langvar=$act_lang&amp;mode=new&amp;entryref=".$entrydata["newsnr"]."\">$l_writecomment</a>";
		echo "</font></td>";
	}
	echo "</tr>";
	if(strlen($entrydata["heading"])>0)
	{
		echo "<tr bgcolor=\"$newsheadingbgcolor\"><td align=\"left\" colspan=\"3\">";
		echo "<font face=\"$newsheadingfont\" size=\"$newsheadingfontsize\" color=\"$newsheadingfontcolor\">";
		echo get_start_tag($newsheadingstyle);
		echo undo_html_ampersand(do_htmlentities($entrydata["heading"]));
		echo get_end_tag($newsheadingstyle);
		echo "</font></td></tr>";
	}
	echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\" colspan=\"3\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	$displaytext=stripslashes($entrydata["text"]);
	$displaytext = undo_htmlspecialchars($displaytext);
	echo $displaytext."</font></td></tr>";
	if($displayposter && (strlen($entrydata["poster"])>0) && ($attachpos==0))
		posterline($entrydata["posterid"], $entrydata["poster"], $linkposter, $entrydata["exposter"]);
	if($attachpos==0)
		echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\">";
	displayattachs_news($entrydata["newsnr"],$attachpos, $nofileinfo);
	if($attachpos==1)
	{
		if($displayposter && (strlen($entrydata["poster"])>0))
			posterline($entrydata["posterid"], $entrydata["poster"], $linkposter, $entrydata["exposter"]);
		echo "<tr bgcolor=\"$contentbgcolor\"><td>&nbsp;</td>";
	}
	echo "<td align=\"right\" colspan=\"2\">";
	if($noprinticon==0)
	{
		echo "<a href=\"print.php?$langvar=$act_lang&layout=$layout&newsnr=".$entrydata["newsnr"]."\" target=\"printwindow\">";
		if($printpic_small)
			echo "<img class=\"iconbutton\" src=\"$url_gfx/$printpic_small\" border=\"0\" align=\"absmiddle\" title=\"$l_print\" alt=\"$l_print\">";
		else
			echo "[$l_print]";
		echo "</a>";
	}
	if($nogotopicon==0)
		echo "<a href=\"#top\"><img class=\"iconbutton\" src=\"$url_gfx/$pagetoppic\" border=\"0\" align=\"absmiddle\" title=\"$l_gotop\" alt=\"$l_gotop\"></a>";
	echo "</td></tr>";
	echo "</table></td></tr>";
}
}
echo "</table></td></tr></table></div>";
include ("./includes/footer.inc");
?>