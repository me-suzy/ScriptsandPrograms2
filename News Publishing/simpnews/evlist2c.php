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
if(!isset($expanded))
	$expanded=0;
if(!isset($exptype))
	$exptype="none";
if(!isset($sortorder))
	$sortorder=0;
if(!isset($maxannounce))
	$maxannounce=0;
if(bittst($announceoptions,BIT_2))
	include_once('./includes/an_disp.inc');
setlocale(LC_TIME, $def_locales[$act_lang]);
if($news5displayicons==1)
	$maincolspan=3;
else
	$maincolspan=2;
$heading=$eventheading;
if($eventheading)
	$pageheading=$eventheading;
else
	$pageheading=$l_events;
$actdate = date("Y-m-d H:i:00");
$baseparams="?$langvar=$act_lang&layout=$layout&sortorder=$sortorder&category=$category";
if(isset($limitdays))
	$baseparams.="&limitdays=$limitdays";
if($maxannounce>0)
	$baseparams.="&maxannounce=$maxannounce";
if(isset($startdate))
	$baseparams.="&startdate=$startdate";
if(isset($enddate))
	$baseparams.="&enddate=$enddate";
$baseurl=$act_script_url.$baseparams;
$baseurl2="evlist2.php".$baseparams;
$backurl=urlencode($baseurl);
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
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="<?php echo $maincolspan?>"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><b><?php echo $eventheading?></b></font></td></tr>
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
		if(bittst($myrow["iconoptions"],BIT_7))
			echo "><img src=\"".$url_icons."/".$myrow["icon"]."\" border=\"0\" align=\"middle\"></td><td align=\"center\" colspan=\"".($maincolspan-1)."\">";
		else
			echo " colspan=\"$maincolspan\">";
		echo "<font face=\"$headingfont\" size=\"$contentfontsize\" color=\"$headingfontcolor\">";
		echo "<b>".$cattext."</b>";
		echo "</font></td></tr>";
		if($myrow["headertext"])
		{
			echo "<tr bgcolor=\"$catinfobgcolor\">";
			echo "<td colspan=\"$maincolspan\" class=\"catinfo\" align=\"left\">";
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
if(isset($startdate))
	$news5startdate=$startdate;
if(isset($enddate))
	$news5enddate=$enddate;
if(isset($limitdays))
{
	$new5startdate=date("Y-m-d");
	$news5enddate= date("Y-m-d",time()+($limitdays*24*60*60));
}
list($year, $month, $day) = explode("-", $news5startdate);
$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
$displaystartdate=strftime($news5headingdateformat,$displaytime);
list($year, $month, $day) = explode("-", $news5enddate);
$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
$displayenddate=strftime($news5headingdateformat,$displaytime);
echo "<tr bgcolor=\"$headingbgcolor\">";
echo "<td align=\"center\" colspan=\"$maincolspan\">";
echo "<font face=\"$headingfont\" size=\"$contentfontsize\" color=\"$headingfontcolor\">";
echo "<b>$displaystartdate - $displayenddate</b>";
echo "</font></td></tr>";
if(($news5noglobalprint==0) || ($asclist==1) || ($enableevpropose==1) || ($exporttype>0) || ($enableevsearch==1))
{
?>
<tr bgcolor="<?php echo $headingbgcolor?>">
<td align="right" colspan="<?php echo $maincolspan?>" valign="middle">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<?php
if($news5noglobalprint==0)
{
	echo "<a href=\"$baseurl2&mode=print\" target=\"printwindow\">";
	if($printpic)
		echo "<img src=\"$url_gfx/$printpic\" border=\"0\" align=\"absmiddle\" title=\"$l_print\" alt=\"$l_print\">";
	else
		echo "[$l_print]";
	echo "</a>";
}
if($asclist==1)
{
	echo "&nbsp;&nbsp;<a href=\"evlist2_asc.php?$langvar=$act_lang&amp;layout=$layout&amp;category=$category&amp;sortorder=$sortorder";
	if(isset($limitdays))
		echo "&amp;limitdays=$limitdays";
	if(isset($startdate))
		echo "&amp;startdate=$startdate";
	if(isset($enddate))
		echo "&amp;enddate=$enddate";
	if($maxannounce>0)
		echo "&amp;maxannounce=$maxannounce";
	echo "\">";
	echo "<img src=\"$url_gfx/$asclistpic\" border=\"0\" align=\"absmiddle\" title=\"$l_asclist\" alt=\"$l_asclist\"></a>";
}
if($exporttype>0)
{
	echo "&nbsp;&nbsp;<a href=\"csv_export.php?$langvar=$act_lang&amp;layout=$layout&amp;category=$category&sortorder=$sortorder";
	if(isset($limitdays))
		echo "&amp;limitdays=$limitdays";
	if(isset($startdate))
		echo "&amp;startdate=$startdate";
	if(isset($enddate))
		echo "&amp;enddate=$enddate";
	if($maxannounce>0)
		echo "&amp;maxannounce=$maxannounce";
	echo "\">";
	echo "<img src=\"$url_gfx/$csvexportpic\" border=\"0\" align=\"absmiddle\" title=\"$l_csvexport\" alt=\"$l_csvexport\"></a>";
}
if(($enableevpropose==1) && ($category>=0))
{
	echo "&nbsp;&nbsp;<a href=\"evpropose.php?$langvar=$act_lang&amp;layout=$layout&amp;category=$category&amp;mode=new&amp;backurl=$backurl\">";
	echo "<img src=\"$url_gfx/$proposepic\" border=\"0\" align=\"absmiddle\" title=\"$l_propose_event\" alt=\"$l_propose_event\"></a>";
}
if($enableevsearch==1)
{
	echo "&nbsp;&nbsp;<a href=\"evsearch.php?$langvar=$act_lang&amp;layout=$layout&amp;category=$category&amp;backurl=$backurl\">";
	echo "<img src=\"$url_gfx/$searchpic\" border=\"0\" align=\"absmiddle\" title=\"$l_search\" alt=\"$l_search\"></a>";
}
echo "</font></td></tr>";
}
$announceavail=false;
if(bittst($announceoptions,BIT_1) && !bittst($announceoptions,BIT_6))
{
	$acttime=transposetime(time(),$servertimezone,$displaytimezone);
	$tmpsql = "select * from ".$tableprefix."_announce where (expiredate>=$acttime or expiredate=0)  and (firstdate<=$acttime or firstdate=0)";
	$tmpsql.=" and DATE_FORMAT(date,'%Y-%m-%d')>='$news5startdate' and DATE_FORMAT(date,'%Y-%m-%d')<'$news5enddate' ";
	if($separatebylang==1)
		$tmpsql.="and lang='$act_lang' ";
	if($category>0)
		$tmpsql.= "and (category='$category' or category=0)";
	else if($category==0)
		$tmpsql.= "and category=0";
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
	if(!$tmpresult = mysql_query($tmpsql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if(mysql_num_rows($tmpresult)>0)
	{
		$announceavail=true;
		while($tmprow=mysql_fetch_array($tmpresult))
		{
			echo "<tr><td align=\"right\" valign=\"top\" class=\"timestampcol\" bgcolor=\"$timestampbgcolor\">";
			list($mydate,$mytime)=explode(" ",$tmprow["date"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			if($month>0)
			{
				$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
				$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
				$displaydate=strftime($news5dateformat,$displaytime);
			}
			else
				$displaydate="";
			echo "<font face=\"$timestampfont\" size=\"$ev2_timestampfontsize\" color=\"$timestampfontcolor\">";
			if($tmprow["category"]==0)
				echo "<img src=\"$url_gfx/$gannouncepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_global_announcement\" title=\"$l_global_announcement\"> ";
			else
				echo "<img src=\"$url_gfx/$announcepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_announcement\" title=\"$l_announcement\"> ";
			echo get_start_tag($ev2_timestampstyle);
			echo $displaydate;
			echo get_end_tag($ev2_timestampstyle);
			echo "</font></td>";
			if($news5displayicons==1)
			{
				echo "<td width=\"2%\" height=\"100%\" valign=\"top\" align=\"center\" bgcolor=\"$contentbgcolor\">";
				if($tmprow["headingicon"])
					echo "<img src=\"$url_icons/".$tmprow["headingicon"]."\" border=\"0\" align=\"middle\"> ";
				else
					echo "&nbsp;";
				echo "</td>";
			}
			echo "<td align=\"center\"><table class=\"eventbox\" width=\"100%\" height=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\" cellspacing=\"0\" cellpadding=\"0\">";
			echo "<tr bgcolor=\"$newsheadingbgcolor\"><td align=\"left\">";
			if(($exptype!="an") || ($expanded!=$tmprow["entrynr"]))
			{
				echo "<a href=\"".$baseurl."&exptype=an&expentry=".$tmprow["entrynr"]."\">";
				echo "<img align=\"top\" src=\"".$url_gfx."/".$expandpic."\" border=\"0\" title=\"$l_expand\">";
				echo "</a> ";
			}
			echo "<font face=\"$newsheadingfont\" size=\"$ev2_newsheadingfontsize\" color=\"$newsheadingfontcolor\">";
			echo get_start_tag($ev2_newsheadingstyle);
			echo display_encoded($tmprow["heading"]);
			echo get_end_tag($ev2_newsheadingstyle);
			echo "</font></td></tr>";
			if(($exptype=="an") && ($expanded==$tmprow["entrynr"]))
			{
				echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\">";
				echo "<table width=\"100%\" align=\"left\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
				echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\" colspan=\"3\">";
				echo "<font face=\"$contentfont\" size=\"$ev2_contentfontsize\" color=\"$contentfontcolor\">";
				$displaytext=stripslashes($tmprow["text"]);
				$displaytext = undo_htmlspecialchars($displaytext);
				echo $displaytext."</font></td></tr>";
				if($news5displayposter && (strlen($tmprow["poster"])>0) && ($attachpos==0))
				{
					$posterlinked=false;
					echo "<tr bgcolor=\"$posterbgcolor\"><td align=\"left\" colspan=\"3\">";
					if($linkposter==1)
					{
						$postersql="select * from ".$tableprefix."_users where usernr=".$tmprow["posterid"];
						if(!$posterresult = mysql_query($postersql, $db))
							die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
						if($posterrow=mysql_fetch_array($posterresult))
						{
							$email=emailencode(stripslashes($posterrow["email"]));
							echo "<a class=\"posterlink\" href=\"mailto:".$email."\">";
							$posterlinked=true;
						}
					}
					echo "<font face=\"$posterfont\" size=\"$ev2_posterfontsize\" color=\"$posterfontcolor\">";
					echo get_start_tag($ev2_posterstyle);
					echo "$l_poster: ".do_htmlentities($tmprow["poster"]);
					echo get_end_tag($ev2_posterstyle);
					echo "</font>";
					if($posterlinked)
						echo "</a>";
					echo "</td></tr>";
				}
				if($attachpos==0)
					echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\">";
				displayattachs_announce($tmprow["entrynr"],$attachpos, $nofileinfo);
				if($news5displayposter && (strlen($tmprow["poster"])>0) && ($attachpos==1))
				{
					$posterlinked=false;
					echo "<tr bgcolor=\"$posterbgcolor\"><td align=\"left\" colspan=\"3\">";
					if($linkposter==1)
					{
						$postersql="select * from ".$tableprefix."_users where usernr=".$tmprow["posterid"];
						if(!$posterresult = mysql_query($postersql, $db))
							die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
						if($posterrow=mysql_fetch_array($posterresult))
						{
							$email=emailencode(stripslashes($posterrow["email"]));
							echo "<a class=\"posterlink\" href=\"mailto:".$email."\">";
							$posterlinked=true;
						}
					}
					echo "<font face=\"$posterfont\" size=\"$ev2_posterfontsize\" color=\"$posterfontcolor\">";
					echo get_start_tag($ev2_posterstyle);
					echo "$l_poster: ".do_htmlentities($tmprow["poster"]);
					echo get_end_tag($ev2_posterstyle);
					echo "</font>";
					if($posterlinked)
						echo "</a>";
					echo "</td></tr>";
					echo "<tr bgcolor=\"$contentbgcolor\"><td>&nbsp;</td>";
				}
				echo "<td align=\"right\" colspan=\"2\">";
				echo "<font face=\"$contentfont\" size=\"1\" color=\"$contentfontcolor\">";
				if($noprinticon==0)
				{
					echo "<a href=\"announceprint.php?$langvar=$act_lang&amp;layout=$layout&amp;announcenr=".$tmprow["entrynr"]."\" target=\"printwindow\">";
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
			echo "</table></td></tr>";
		}
	}
}
$sql = "select * from ".$tableprefix."_events where date >= '$news5startdate' and date <= '$news5enddate' ";
if($separatebylang==1)
	$sql.=" and lang='$act_lang'";
if($category>=0)
	$sql.= " and category='$category'";
else
{
	$sql.=" and linkeventnr=0";
	$tmpsql="select * from ".$tableprefix."_categories where hideintotallist=1";
	if(!$tmpresult = mysql_query($tmpsql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	while($tmprow=mysql_fetch_array($tmpresult))
		$sql.=" and category!=".$tmprow["catnr"];
}
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
if(!$result = mysql_query($sql, $db))
    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
$currentyear=0;
$currentmonth=0;
if($myrow=mysql_fetch_array($result))
{
	do
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
		list($tempdate, $temptime) = explode(" ", $entrydata["date"]);
		list($year, $month, $day) = explode("-", $tempdate);
		if($month>0)
		{
			$displaytime=mktime(0,0,0,$month,$day,$year);
			$link_date=date("Y-m-d",$displaytime);
			$displaydate=strftime($news5dateformat,$displaytime);
		}
		else
			$displaydate="";
		if($currentyear!=$year)
		{
			$currentyear=$year;
			echo "<tr><td align=\"left\" colspan=\"$maincolspan\" bgcolor=\"$news5yearbgcolor\">";
			echo "<font face=\"$news5yearfont\" size=\"$news5yearfontsize\" color=\"$news5yearfontcolor\">";
			echo get_start_tag($news5yearfontstyle);
			echo $year;
			echo get_end_tag($news5yearfontstyle);
			echo "</font></td></tr>";
		}
		if($currentmonth!=$month)
		{
			$currentmonth=$month;
			echo "<tr><td align=\"left\" colspan=\"$maincolspan\" bgcolor=\"$news5monthbgcolor\">";
			echo "<font face=\"$news5monthfont\" size=\"$news5monthfontsize\" color=\"$news5monthfontcolor\">";
			echo get_start_tag($news5monthfontstyle);
			echo $l_monthname[$month-1];
			if($news5monthdisplayyear==1)
				echo " ".$year;
			echo get_end_tag($news5monthfontstyle);
			echo "</font></td></tr>";
			if(bittst($announceoptions,BIT_1) && bittst($announceoptions,BIT_6))
				display_announcements2c($year,$month,$category, $act_lang, $layout, false, $maxannounce,$exptype,$expanded,$baseurl);
		}
		echo "<tr>";
		echo "<td class=\"timestampcol\" valign=\"top\" height=\"100%\" align=\"right\" bgcolor=\"$timestampbgcolor\">";
		echo "<font face=\"$timestampfont\" size=\"$ev2_timestampfontsize\" color=\"$timestampfontcolor\">";
		if(isset($lastvisitdate))
		{
			list($mydate,$mytime)=explode(" ",$myrow["added"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			$thisentrydate=mktime($hour,$min,$sec,$month,$day,$year);
			if($thisentrydate>=$lastvisitdate)
				echo "<img src=\"$url_gfx/$newentrypic\" border=\"0\" align=\"absmiddle\"><br>";
		}
		echo get_start_tag($ev2_timestampstyle);
		echo $displaydate;
		echo get_end_tag($ev2_timestampstyle);
		echo "</font></td>";
		if($news5displayicons==1)
		{
			echo "<td width=\"2%\" height=\"100%\" valign=\"top\" align=\"center\" bgcolor=\"$contentbgcolor\">";
			if($entrydata["headingicon"])
				echo "<img src=\"$url_icons/".$entrydata["headingicon"]."\" border=\"0\" align=\"middle\"> ";
			else
				echo "&nbsp;";
			echo "</td>";
		}
		echo "<td align=\"center\"><table class=\"eventbox\" height=\"100%\" width=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\" cellspacing=\"0\" cellpadding=\"0\">";
		echo "<tr bgcolor=\"$newsheadingbgcolor\"><td align=\"left\" colspan=\"3\">";
		if(($exptype!="event") || ($expanded!=$myrow["eventnr"]))
		{
			echo "<a href=\"".$baseurl."&exptype=event&expanded=".$myrow["eventnr"]."\">";
			echo "<img align=\"top\" src=\"".$url_gfx."/".$expandpic."\" border=\"0\" title=\"$l_expand\">";
			echo "</a> ";
		}
		echo "<font face=\"$newsheadingfont\" size=\"$ev2_newsheadingfontsize\" color=\"$newsheadingfontcolor\">";
		echo get_start_tag($ev2_newsheadingstyle);
		echo display_encoded($entrydata["heading"]);
		echo get_end_tag($ev2_newsheadingstyle);
		echo "</font></td></tr>";
		if(($exptype=="event") && ($expanded==$myrow["eventnr"]))
		{
			echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\" colspan=\"3\">";
			echo "<table width=\"100%\" align=\"left\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
			echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\" colspan=\"3\">";
			echo "<font face=\"$contentfont\" size=\"$ev2_contentfontsize\" color=\"$contentfontcolor\">";
			$displaytext=stripslashes($entrydata["text"]);
			$displaytext = undo_htmlspecialchars($displaytext);
			echo $displaytext."</font></td></tr>";
			if($news5displayposter && (strlen($entrydata["poster"])>0) && ($attachpos==0))
			{
				$posterlinked=false;
				echo "<tr bgcolor=\"$posterbgcolor\"><td align=\"left\" colspan=\"3\">";
				if($linkposter==1)
				{
					$postersql="select * from ".$tableprefix."_users where usernr=".$entrydata["posterid"];
					if(!$posterresult = mysql_query($postersql, $db))
						die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
					if($posterrow=mysql_fetch_array($posterresult))
					{
						$email=emailencode(stripslashes($posterrow["email"]));
						echo "<a class=\"posterlink\" href=\"mailto:".$email."\">";
						$posterlinked=true;
					}
				}
				echo "<font face=\"$posterfont\" size=\"$ev2_posterfontsize\" color=\"$posterfontcolor\">";
				echo get_start_tag($ev2_posterstyle);
				echo "$l_poster: ".do_htmlentities($entrydata["poster"]);
				echo get_end_tag($ev2_posterstyle);
				echo "</font>";
				if($posterlinked)
					echo "</a>";
				echo "</td></tr>";
			}
			if($attachpos==0)
				echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\">";
			displayattachs_events($entrydata["eventnr"],$attachpos, $nofileinfo);
			if($news5displayposter && (strlen($entrydata["poster"])>0) && ($attachpos==1))
			{
				$posterlinked=false;
				echo "<tr bgcolor=\"$posterbgcolor\"><td align=\"left\" colspan=\"3\">";
				if($linkposter==1)
				{
					$postersql="select * from ".$tableprefix."_users where usernr=".$entrydata["posterid"];
					if(!$posterresult = mysql_query($postersql, $db))
						die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
					if($posterrow=mysql_fetch_array($posterresult))
					{
						$email=emailencode(stripslashes($posterrow["email"]));
						echo "<a class=\"posterlink\" href=\"mailto:".$email."\">";
						$posterlinked=true;
					}
				}
				echo "<font face=\"$posterfont\" size=\"$ev2_posterfontsize\" color=\"$posterfontcolor\">";
				echo get_start_tag($ev2_posterstyle);
				echo "$l_poster: ".do_htmlentities($entrydata["poster"]);
				echo get_end_tag($ev2_posterstyle);
				echo "</font>";
				if($posterlinked)
					echo "</a>";
				echo "</td></tr>";
				echo "<tr bgcolor=\"$contentbgcolor\"><td>&nbsp;</td>";
			}
			echo "<td align=\"right\" colspan=\"2\">";
			echo "<font face=\"$contentfont\" size=\"1\" color=\"$contentfontcolor\">";
			if($noprinticon==0)
			{
				echo "<a href=\"evprint.php?$langvar=$act_lang&amp;layout=$layout&amp;eventnr=".$entrydata["eventnr"]."\" target=\"printwindow\">";
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
		echo "</table></td></tr>";
	}while($myrow=mysql_fetch_array($result));
}
else if(!$announceavail)
{
	echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"center\" colspan=\"2\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo $l_noentries;
	echo "</font></td></tr>";
}
if(($news5noglobalprint==0) || ($asclist==1) || ($enableevpropose==1) || ($exporttype>0) || ($enableevsearch==1))
{
?>
<tr bgcolor="<?php echo $headingbgcolor?>">
<td align="right" colspan="<?php echo $maincolspan?>" valign="middle">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<?php
if($news5noglobalprint==0)
{
	echo "<a href=\"$baseurl2&mode=print\" target=\"printwindow\">";
	if($printpic)
		echo "<img src=\"$url_gfx/$printpic\" border=\"0\" align=\"absmiddle\" title=\"$l_print\" alt=\"$l_print\">";
	else
		echo "[$l_print]";
	echo "</a>";
}
if($asclist==1)
{
	echo "&nbsp;&nbsp;<a href=\"evlist2_asc.php?$langvar=$act_lang&amp;layout=$layout&amp;category=$category&amp;sortorder=$sortorder";
	if(isset($limitdays))
		echo "&amp;limitdays=$limitdays";
	if(isset($startdate))
		echo "&amp;startdate=$startdate";
	if(isset($enddate))
		echo "&amp;enddate=$enddate";
	if($maxannounce>0)
		echo "&amp;maxannounce=$maxannounce";
	echo "\">";
	echo "<img src=\"$url_gfx/$asclistpic\" border=\"0\" align=\"absmiddle\" title=\"$l_asclist\" alt=\"$l_asclist\"></a>";
}
if($exporttype>0)
{
	echo "&nbsp;&nbsp;<a href=\"csv_export.php?$langvar=$act_lang&amp;layout=$layout&amp;category=$category&sortorder=$sortorder";
	if(isset($limitdays))
		echo "&amp;limitdays=$limitdays";
	if(isset($startdate))
		echo "&amp;startdate=$startdate";
	if(isset($enddate))
		echo "&amp;enddate=$enddate";
	if($maxannounce>0)
		echo "&amp;maxannounce=$maxannounce";
	echo "\">";
	echo "<img src=\"$url_gfx/$csvexportpic\" border=\"0\" align=\"absmiddle\" title=\"$l_csvexport\" alt=\"$l_csvexport\"></a>";
}
if(($enableevpropose==1) && ($category>=0))
{
	echo "&nbsp;&nbsp;<a href=\"evpropose.php?$langvar=$act_lang&amp;layout=$layout&amp;category=$category&amp;mode=new&amp;backurl=$backurl\">";
	echo "<img src=\"$url_gfx/$proposepic\" border=\"0\" align=\"absmiddle\" title=\"$l_propose_event\" alt=\"$l_propose_event\"></a>";
}
if($enableevsearch==1)
{
	echo "&nbsp;&nbsp;<a href=\"evsearch.php?$langvar=$act_lang&amp;layout=$layout&amp;category=$category&amp;backurl=$backurl\">";
	echo "<img src=\"$url_gfx/$searchpic\" border=\"0\" align=\"absmiddle\" title=\"$l_search\" alt=\"$l_search\"></a>";
}
echo "</font></td></tr>";
}
echo "</table></td></tr></table></div>";
include ("./includes/footer.inc");
?>