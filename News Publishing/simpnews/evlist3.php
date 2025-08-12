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
if(!isset($mode))
	$mode="";
if(!isset($sortorder))
	$sortorder=0;
if(!isset($maxannounce))
	$maxannounce=0;
if(bittst($announceoptions,BIT_2))
	include_once('./includes/an_disp.inc');
if($news4showcat==1)
	$colspan=3;
else
	$colspan=2;
setlocale(LC_TIME, $def_locales[$act_lang]);
$heading=$eventheading;
if($eventheading)
	$pageheading=$eventheading;
else
	$pageheading=$l_events;
$actdate = date("Y-m-d H:i:00");
if(!isset($start))
	$start=0;
$baseurl="$act_script_url?$langvar=$act_lang&layout=$layout&sortorder=$sortorder";
if($maxannounce>0)
	$baseurl.="&maxannounce=$maxannounce";
if(isset($startdate))
	$baseurl.="&startdate=$startdate";
if(isset($enddate))
	$baseurl.="&enddate=$enddate";
if(isset($limitdays))
	$baseurl.="&limitdays=$limitdays";
$backurl=$baseurl."&start=$start";
$backurl=urlencode($backurl);
if($lastvisitcookie==1)
	include("./includes/lastvisit.inc");
if(isset($limitdays))
{
	$startdate=date("Y-m-d");
	$enddate= date("Y-m-d",time()+($limitdays*24*60*60));
}
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
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="<?php echo $colspan?>"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><b><?php echo $eventheading?></b></font></td></tr>
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
			echo "><img src=\"".$url_icons."/".$myrow["icon"]."\" border=\"0\" align=\"middle\"></td><td align=\"center\" colspan=\"".($colspan-1)."\">";
		else
			echo " colspan=\"$colspan\">";
		echo "<font face=\"$headingfont\" size=\"$contentfontsize\" color=\"$headingfontcolor\">";
		echo "<b>".$cattext."</b>";
		echo "</font></td></tr>";
		if($myrow["headertext"])
		{
			echo "<tr bgcolor=\"$catinfobgcolor\">";
			echo "<td colspan=\"$colspan\" class=\"catinfo\" align=\"left\">";
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
if(($enableevpropose==1) || ($enableevsearch==1))
{
?>
<tr bgcolor="<?php echo $headingbgcolor?>">
<td align="right" colspan="<?php echo $colspan?>" valign="middle">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<?php
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
$sql = "select * from ".$tableprefix."_events ";
if($category>=0)
	$sql.= "where category='$category'";
else
{
	$sql.="where linkeventnr=0";
	$tmpsql="select * from ".$tableprefix."_categories where hideintotallist=1";
	if(!$tmpresult = mysql_query($tmpsql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	while($tmprow=mysql_fetch_array($tmpresult))
		$sql.=" and category!=".$tmprow["catnr"];
}
if($separatebylang==1)
	$sql.=" and lang='$act_lang'";
if(isset($startdate))
	$sql.= " and DATE_FORMAT(date,'%Y-%m-%d')>='$startdate'";
if(isset($enddate))
	$sql.= " and DATE_FORMAT(date,'%Y-%m-%d')<='$enddate'";
if($maxage>0)
{
	$actdate = date("Y-m-d");
	$sql.= " and DATE_FORMAT(date,'%Y-%m-%d') >= date_sub('$actdate', INTERVAL $maxage DAY)";
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
$numentries=mysql_num_rows($result);
$announceavail=false;
if($numentries>0)
{
	if($entriesperpage>0)
	{
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
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		$numrows=mysql_num_rows($result);
		if($numentries>$entriesperpage)
		{
			echo "<tr bgcolor=\"$headingbgcolor\"><td align=\"center\" colspan=\"$colspan\">";
			echo "<font face=\"$headingfont\" size=\"1\" color=\"$headingfontcolor\">";
			if(floor(($start+$entriesperpage)/$entriesperpage)>1)
			{
				echo "<a class=\"pagenav\" href=\"$baseurl\">";
				if($pagepic_first)
					echo "<img src=\"$url_gfx/$pagepic_first\" border=\"0\" align=\"absmiddle\" title=\"$l_page_first\" alt=\"$l_page_first\">";
				else
					echo "<b>[&lt;&lt;]</b>";
				echo "</a> ";
				echo "<a class=\"pagenav\" href=\"$baseurl&start=".($start-$entriesperpage)."\">";
				if($pagepic_back)
					echo "<img src=\"$url_gfx/$pagepic_back\" border=\"0\" align=\"absmiddle\" title=\"$l_page_back\" alt=\"$l_page_back\">";
				else
					echo "<b>[&lt;]</b>";
				echo "</a> ";
			}
			echo " <b>$l_page</b> ";
			for($i=1;$i<($numentries/$entriesperpage)+1;$i++)
			{
				if(floor(($start+$entriesperpage)/$entriesperpage)!=$i)
				{
					echo "<a class=\"pagenav\" href=\"$baseurl&start=".(($i-1)*$entriesperpage);
					echo "\"><b>[$i]</b></a> ";
				}
				else
					echo "<b>($i)</b> ";
			}
			if($start < (($i-2)*$entriesperpage))
			{
				echo "<a class=\"pagenav\" href=\"$baseurl&start=".($start+$entriesperpage)."\">";
				if($pagepic_next)
					echo "<img src=\"$url_gfx/$pagepic_next\" border=\"0\" align=\"absmiddle\" title=\"$l_page_forward\" alt=\"$l_page_forward\">";
				else
					echo "<b>[&gt;]</b>";
				echo "</a> ";
				echo "<a class=\"pagenav\" href=\"$baseurl&start=".(($i-2)*$entriesperpage)."\">";
				if($pagepic_last)
					echo "<img src=\"$url_gfx/$pagepic_last\" border=\"0\" align=\"absmiddle\" title=\"$l_page_last\" alt=\"$l_page_last\">";
				else
					echo "<b>[&gt;&gt;]</b>";
				echo "</a> ";
			}
			echo "</font></td></tr>";
		}
	}
}
if(bittst($announceoptions,BIT_1) && ($start==0))
{
	$acttime=transposetime(time(),$servertimezone,$displaytimezone);
	$tmpsql = "select * from ".$tableprefix."_announce where (expiredate>=$acttime or expiredate=0)  and (firstdate<=$acttime or firstdate=0)";
	if(isset($startdate))
		$tmpsql.= " and DATE_FORMAT(date,'%Y-%m-%d')>='$startdate'";
	if(isset($enddate))
		$tmpsql.= " and DATE_FORMAT(date,'%Y-%m-%d')<='$enddate'";
	if($maxage>0)
	{
		$actdate = date("Y-m-d");
		$tmpsql.= " and DATE_FORMAT(date,'%Y-%m-%d') >= date_sub('$actdate', INTERVAL $maxage DAY)";
	}
	if($separatebylang==1)
		$tmpsql.=" and lang='$act_lang'";
	if($category>0)
		$tmpsql.= " and (category='$category' or category=0)";
	else if($category==0)
		$tmpsql.= " and category=0";
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
			echo "<tr><td align=\"right\" class=\"timestampcol\" bgcolor=\"$timestampbgcolor\">";
			list($mydate,$mytime)=explode(" ",$tmprow["date"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			if($month>0)
			{
				$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
				$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
				$displaydate=strftime($ev3_dateformat,$displaytime);
			}
			else
				$displaydate="";
			echo "<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
			if($tmprow["category"]==0)
				echo "<img style=\"margin: 1px\" src=\"$url_gfx/$gannouncepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_global_announcement\" title=\"$l_global_announcement\"> ";
			else
				echo "<img style=\"margin: 1px\" src=\"$url_gfx/$announcepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_announcement\" title=\"$l_announcement\"> ";
			echo get_start_tag($timestampstyle);
			echo $displaydate;
			echo get_end_tag($timestampstyle);
			echo "</font></td>";
			if(($news4showcat==1) && ($category<0))
			{
				if($tmprow["category"]>0)
				{
					$catsql="select * from ".$tableprefix."_categories where catnr=".$tmprow["category"];
					if(!$catresult = mysql_query($catsql, $db))
						die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
					if($catrow=mysql_fetch_array($catresult))
						$catname=do_htmlentities($catrow["catname"]);
					else
						$catname=$l_unknown;
				}
				else
					$catname=$l_general;
				echo "<td align=\"left\" width=\"10%\" bgcolor=\"$timestampbgcolor\">";
				echo "<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
				echo $catname;
				echo "</font></td>";
			}
			if(strlen($tmprow["heading"])>0)
				$displaytext=undo_html_ampersand(do_htmlentities($tmprow["heading"]));
			else
			{
				$displaytext = stripslashes($tmprow["text"]);
				$displaytext = undo_htmlspecialchars($displaytext);
				$displaytext = strip_tags($displaytext);
				if(strlen($displaytext)>$news4maxchars)
					$displaytext = subwords($displaytext,$news4maxchars);
			}
			echo "<td class=\"newsbox\" bgcolor=\"$newsheadingbgcolor\" align=\"left\">";
			echo "<font face=\"$newsheadingfont\" size=\"$news4fontsize\" color=\"$newsheadingfontcolor\">";
			if($tmprow["tickerurl"])
				$linkdest=$tmprow["tickerurl"];
			else
				$linkdest="announce.php?$langvar=$act_lang&layout=$layout&category=$category&announcenr=".$tmprow["entrynr"]."&backurl=$backurl";
			echo "<a title=\"$l_showentry\" class=\"newslink\" href=\"$linkdest\" target=\"$news4linktarget\">";
			echo $displaytext;
			echo "</a></font></td></tr>";
		}
	}
}
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
		list($hour, $min, $sec) = explode(":",$temptime);
		list($year, $month, $day) = explode("-", $tempdate);
		$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
		$link_date=date("Y-m-d",$displaytime);
		$displaydate=strftime($ev3_dateformat,$displaytime);
		echo "<tr>";
		echo "<td class=\"timestampcol\" height=\"100%\" align=\"right\" bgcolor=\"$timestampbgcolor\" valign=\"top\">";
		echo "<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
		if(isset($lastvisitdate))
		{
			list($mydate,$mytime)=explode(" ",$myrow["added"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			$thisentrydate=mktime($hour,$min,$sec,$month,$day,$year);
			if($thisentrydate>=$lastvisitdate)
				echo "<img src=\"$url_gfx/$newentrypic\" border=\"0\" align=\"absmiddle\"><br>";
		}
		echo get_start_tag($timestampstyle);
		echo $displaydate;
		echo get_end_tag($timestampstyle);
		echo "</font></td>";
		if(($news4showcat==1) && ($category<0))
		{
			if($entrydata["category"]>0)
			{
				$catsql="select * from ".$tableprefix."_categories where catnr=".$entrydata["category"];
				if(!$catresult = mysql_query($catsql, $db))
					die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
				if($catrow=mysql_fetch_array($catresult))
					$catname=do_htmlentities($catrow["catname"]);
				else
					$catname=$l_unknown;
			}
			else
				$catname=$l_general;
			echo "<td align=\"left\" width=\"10%\" bgcolor=\"$timestampbgcolor\">";
			echo "<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
			echo $catname;
			echo "</font></td>";
		}
		if(strlen($entrydata["heading"])>0)
			$displaytext=undo_html_ampersand(do_htmlentities($entrydata["heading"]));
		else
		{
			$displaytext = stripslashes($entrydata["text"]);
			$displaytext = undo_htmlspecialchars($displaytext);
			$displaytext = strip_tags($displaytext);
			if(strlen($displaytext)>$news4maxchars)
				$displaytext = subwords($displaytext,$news4maxchars);
		}
		echo "<td class=\"newsbox\" bgcolor=\"$newsheadingbgcolor\" align=\"left\">";
		echo "<font face=\"$newsheadingfont\" size=\"$news4fontsize\" color=\"$newsheadingfontcolor\">";
		$linkdest="events.php?$langvar=$act_lang&layout=$layout&category=$category&link_date=".$link_date."&backurl=$backurl&srcscript=$act_script_url";
		if($news4useddlink)
		{
			$tempsql="select f.filename, f.filesize, f.mimetype, na.* from ".$tableprefix."_news_attachs na, ".$tableprefix."_files f where f.entrynr=na.attachnr and na.newsnr=".$entrydata["newsnr"]." order by na.entrynr asc";
			if(!$tempresult=mysql_query($tempsql,$db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			if($temprow=mysql_fetch_array($tempresult))
				$linkdest="sndownload.php?entrynr=".$temprow["attachnr"];
		}
		echo "<a title=\"$l_showentry\" class=\"newslink\" href=\"$linkdest\" target=\"$news4linktarget\">";
		echo $displaytext;
		echo "</a></font></td></tr>";
	}while($myrow=mysql_fetch_array($result));
	if($entriesperpage>0)
	{
		if($numentries>$entriesperpage)
		{
			echo "<tr bgcolor=\"$headingbgcolor\"><td align=\"center\" colspan=\"$colspan\">";
			echo "<font face=\"$headingfont\" size=\"1\" color=\"$headingfontcolor\">";
			if(floor(($start+$entriesperpage)/$entriesperpage)>1)
			{
				echo "<a class=\"pagenav\" href=\"$baseurl\">";
				if($pagepic_first)
					echo "<img src=\"$url_gfx/$pagepic_first\" border=\"0\" align=\"absmiddle\" title=\"$l_page_first\" alt=\"$l_page_first\">";
				else
					echo "<b>[&lt;&lt;]</b>";
				echo "</a> ";
				echo "<a class=\"pagenav\" href=\"$baseurl&start=".($start-$entriesperpage)."\">";
				if($pagepic_back)
					echo "<img src=\"$url_gfx/$pagepic_back\" border=\"0\" align=\"absmiddle\" title=\"$l_page_back\" alt=\"$l_page_back\">";
				else
					echo "<b>[&lt;]</b>";
				echo "</a> ";
			}
			echo " <b>$l_page</b> ";
			for($i=1;$i<($numentries/$entriesperpage)+1;$i++)
			{
				if(floor(($start+$entriesperpage)/$entriesperpage)!=$i)
				{
					echo "<a class=\"pagenav\" href=\"$baseurl&start=".(($i-1)*$entriesperpage);
					echo "\"><b>[$i]</b></a> ";
				}
				else
					echo "<b>($i)</b> ";
			}
			if($start < (($i-2)*$entriesperpage))
			{
				echo "<a class=\"pagenav\" href=\"$baseurl&start=".($start+$entriesperpage)."\">";
				if($pagepic_next)
					echo "<img src=\"$url_gfx/$pagepic_next\" border=\"0\" align=\"absmiddle\" title=\"$l_page_forward\" alt=\"$l_page_forward\">";
				else
					echo "<b>[&gt;]</b>";
				echo "</a> ";
				echo "<a class=\"pagenav\" href=\"$baseurl&start=".(($i-2)*$entriesperpage)."\">";
				if($pagepic_last)
					echo "<img src=\"$url_gfx/$pagepic_last\" border=\"0\" align=\"absmiddle\" title=\"$l_page_last\" alt=\"$l_page_last\">";
				else
					echo "<b>[&gt;&gt;]</b>";
				echo "</a> ";
			}
			echo "</font></td></tr>";
		}
	}
}
else if(!$announceavail)
{
	echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"center\" colspan=\"$colspan\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo $l_noentries;
	echo "</font></td></tr>";
}
if(($enableevpropose==1) || ($enableevsearch==1))
{
?>
<tr bgcolor="<?php echo $headingbgcolor?>">
<td align="right" colspan="<?php echo $colspan?>" valign="middle">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<?php
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