<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function display_announcements($year,$month,$category,$act_lang,$layout,$linktarget,$maxannounce=0)
{
	global $tableprefix, $servertimezone, $displaytimezone, $separatebylang, $db, $langvar;
	global $timestampbgcolor, $timestampfont, $timestampfontsize, $timestampfontcolor, $timestampstyle;
	global $news5dateformat, $l_announcement, $l_global_announcement, $gannouncepic, $announcepic;
	global $backurl, $url_gfx, $l_showentry, $news5linktarget;
	global $newsheadingfont, $newsheadingstyle, $newsheadingfontcolor, $newsheadingfontsize, $newsheadingbgcolor;
	
	$acttime=transposetime(time(),$servertimezone,$displaytimezone);
	$tmpstart=mktime(0,0,0,$month,1,$year);
	$month++;
	if($month>12)
	{
		$month=1;
		$year++;
	}
	$tmpstop=mktime(0,0,0,$month,1,$year);
	$startdate=date("Y-m-d",$tmpstart);
	$enddate=date("Y-m-d",$tmpstop);
	$tmpsql = "select * from ".$tableprefix."_announce where (expiredate>=$acttime or expiredate=0) and (firstdate<=$acttime or firstdate=0) ";
	$tmpsql.=" and DATE_FORMAT(date,'%Y-%m-%d')>='$startdate' and DATE_FORMAT(date,'%Y-%m-%d')<'$enddate' ";
	if($separatebylang==1)
		$tmpsql.="and lang='$act_lang' ";
	if($category>0)
		$tmpsql.= "and (category='$category' or category=0)";
	else if($category==0)
		$tmpsql.= "and category=0";
	$tmpsql.=" order by date desc";
	if($maxannounce>0)
		$tmpsql.=" limit $maxannounce";
	if(!$tmpresult = mysql_query($tmpsql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if(mysql_num_rows($tmpresult)>0)
	{
		$announceavail=true;
		while($tmprow=mysql_fetch_array($tmpresult))
		{
			echo "<tr><td align=\"right\" class=\"timestampcol\" bgcolor=\"".$layout["timestampbgcolor"]."\">";
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
			echo "<font face=\"".$layout["timestampfont"]."\" size=\"".$layout["timestampfontsize"]."\" color=\"".$layout["timestampfontcolor"]."\">";
			if($tmprow["category"]==0)
				echo "<img src=\"$url_gfx/$gannouncepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_global_announcement\" title=\"$l_global_announcement\"> ";
			else
				echo "<img src=\"$url_gfx/$announcepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_announcement\" title=\"$l_announcement\"> ";
			echo get_start_tag($layout["timestampstyle"]);
			echo $displaydate;
			echo get_end_tag($layout["timestampstyle"]);
			echo "</font></td>";
			if(strlen($tmprow["heading"])>0)
				$displaytext=display_encoded($tmprow["heading"]);
			else
			{
				$displaytext = stripslashes($tmprow["text"]);
				$displaytext = undo_htmlspecialchars($displaytext);
				$displaytext = strip_tags($displaytext);
				if(strlen($displaytext)>$news4maxchars)
					$displaytext = subwords($displaytext,$news4maxchars);
			}
			echo "<td class=\"newsbox\" bgcolor=\"".$layout["newsheadingbgcolor"]."\" align=\"left\">";
			echo "<font face=\"".$layout["newsheadingfont"]."\" size=\"".$layout["newsheadingfontsize"]."\" color=\"".$layout["newsheadingfontcolor"]."\">";
			$linkdest="announce.php?$langvar=$act_lang&announcenr=".$tmprow["entrynr"]."&backurl=$backurl";
			echo "<a title=\"$l_showentry\" class=\"newslink\" href=\"$linkdest\" target=\"$linktarget\">";
			echo $displaytext;
			echo "</a></font></td></tr>";
		}
	}
}
function display_announcements2($year,$month,$category,$act_lang,$layout, $disableprint=false, $maxannounce=0)
{
	global $tableprefix, $servertimezone, $displaytimezone, $separatebylang, $db, $langvar;
	global $timestampbgcolor, $timestampfont, $timestampfontsize, $timestampfontcolor, $timestampstyle;
	global $news5dateformat, $l_announcement, $l_global_announcement, $gannouncepic, $announcepic;
	global $backurl, $url_gfx, $l_showentry, $news5linktarget, $news5displayicons, $news5displayposter;
	global $newsheadingfont, $ev2_newsheadingstyle, $newsheadingfontcolor, $ev2_newsheadingfontsize, $newsheadingbgcolor;
	global $l_gotop, $printpic_small, $l_print, $pagetoppic, $l_poster;
	global $contentbgcolor, $contentfont, $contentfontcolor, $contentfontsize;
	global $posterstyle, $posterfont, $posterfontsize, $posterfontcolor, $posterbgcolor;
	global $noprinticon, $nogotopicon, $url_icons;

	$acttime=transposetime(time(),$servertimezone,$displaytimezone);
	$tmpstart=mktime(0,0,0,$month,1,$year);
	$month++;
	if($month>12)
	{
		$month=1;
		$year++;
	}
	$tmpstop=mktime(0,0,0,$month,1,$year);
	$startdate=date("Y-m-d",$tmpstart);
	$enddate=date("Y-m-d",$tmpstop);
	$tmpsql = "select * from ".$tableprefix."_announce where (expiredate>=$acttime or expiredate=0) and (firstdate<=$acttime or firstdate=0) ";
	$tmpsql.=" and DATE_FORMAT(date,'%Y-%m-%d')>='$startdate' and DATE_FORMAT(date,'%Y-%m-%d')<'$enddate' ";
	if($separatebylang==1)
		$tmpsql.="and lang='$act_lang' ";
	if($category>0)
		$tmpsql.= "and (category='$category' or category=0)";
	else if($category==0)
		$tmpsql.= "and category=0";
	$tmpsql.=" order by date desc";
	if($maxannounce>0)
		$tmpsql.=" limit $maxannounce";
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
				$displaydate=strftime($news5dateformat,$displaytime);
			}
			else
				$displaydate="";
			echo "<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
			if($tmprow["category"]==0)
				echo "<img src=\"$url_gfx/$gannouncepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_global_announcement\" title=\"$l_global_announcement\"> ";
			else
				echo "<img src=\"$url_gfx/$announcepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_announcement\" title=\"$l_announcement\"> ";
			echo get_start_tag($timestampstyle);
			echo $displaydate;
			echo get_end_tag($timestampstyle);
			echo "</font></td>";
			if($news5displayicons==1)
			{
				echo "<td width=\"2%\" height=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\">";
				if($tmprow["headingicon"])
					echo "<img src=\"$url_icons/".$tmprow["headingicon"]."\" border=\"0\" align=\"middle\"> ";
				else
					echo "&nbsp;";
				echo "</td>";
			}
			echo "<td align=\"center\"><table class=\"eventbox\" width=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\" cellspacing=\"0\" cellpadding=\"0\">";
			if(strlen($tmprow["heading"])>0)
			{
				echo "<tr bgcolor=\"$newsheadingbgcolor\"><td align=\"left\" colspan=\"3\">";
				echo "<font face=\"$newsheadingfont\" size=\"$ev2_newsheadingfontsize\" color=\"$newsheadingfontcolor\">";
				echo get_start_tag($ev2_newsheadingstyle);
				echo display_encoded($tmprow["heading"]);
				echo get_end_tag($ev2_newsheadingstyle);
				echo "</font></td></tr>";
			}
			echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\" colspan=\"3\">";
			echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$displaytext=stripslashes($tmprow["text"]);
			$displaytext = undo_htmlspecialchars($displaytext);
			echo $displaytext."</font></td></tr>";
			if($news5displayposter && (strlen($tmprow["poster"])>0))
			{
				echo "<tr bgcolor=\"$posterbgcolor\"><td align=\"left\" colspan=\"3\">";
				echo "<font face=\"$posterfont\" size=\"$posterfontsize\" color=\"$posterfontcolor\">";
				echo get_start_tag($posterstyle);
				echo "$l_poster: ".do_htmlentities($tmprow["poster"]);
				echo get_end_tag($posterstyle);
				echo "</font></td></tr>";
			}
			if(!$disableprint)
			{
				echo "<tr bgcolor=\"$contentbgcolor\">";
				echo "<td align=\"left\">";
				echo "&nbsp;";
				echo "</td><td align=\"right\" colspan=\"2\">";
				echo "<font face=\"$contentfont\" size=\"1\" color=\"$contentfontcolor\">";
				if($noprinticon==0)
				{
					echo "<a href=\"announceprint.php?$langvar=$act_lang&layout=$layout&announcenr=".$tmprow["entrynr"]."\" target=\"printwindow\">";
					if($printpic_small)
						echo "<img src=\"$url_gfx/$printpic_small\" border=\"0\" align=\"absmiddle\" title=\"$l_print\" alt=\"$l_print\">";
					else
						echo "[$l_print]";
					echo "</a>&nbsp; ";
				}
				if($nogotopicon==0)
					echo "<a href=\"#top\"><img src=\"$url_gfx/$pagetoppic\" border=\"0\" align=\"absmiddle\" title=\"$l_gotop\" alt=\"$l_gotop\"></a>";
				echo "</font></td></tr>";
			}
			echo "</table></td></tr>";
		}
	}
}
function display_announcements2b($year,$month,$category,$act_lang,$layout,$disableprint,$maxannounce,$entrynr)
{
	global $tableprefix, $servertimezone, $displaytimezone, $separatebylang, $db, $langvar;
	global $timestampbgcolor, $timestampfont, $timestampfontsize, $timestampfontcolor, $timestampstyle;
	global $news5dateformat, $l_announcement, $l_global_announcement, $gannouncepic, $announcepic;
	global $backurl, $url_gfx, $l_showentry, $news5linktarget, $news5displayicons, $news5displayposter;
	global $newsheadingfont, $ev2_newsheadingstyle, $newsheadingfontcolor, $ev2_newsheadingfontsize, $newsheadingbgcolor;
	global $l_gotop, $printpic_small, $l_print, $pagetoppic, $l_poster;
	global $contentbgcolor, $contentfont, $contentfontcolor, $contentfontsize;
	global $posterstyle, $posterfont, $posterfontsize, $posterfontcolor, $posterbgcolor;
	global $noprinticon, $nogotopicon, $url_icons, $l_expand, $expandpic;

	$acttime=transposetime(time(),$servertimezone,$displaytimezone);
	$tmpstart=mktime(0,0,0,$month,1,$year);
	$month++;
	if($month>12)
	{
		$month=1;
		$year++;
	}
	$tmpstop=mktime(0,0,0,$month,1,$year);
	$startdate=date("Y-m-d",$tmpstart);
	$enddate=date("Y-m-d",$tmpstop);
	$tmpsql = "select * from ".$tableprefix."_announce where (expiredate>=$acttime or expiredate=0) and (firstdate<=$acttime or firstdate=0) ";
	$tmpsql.=" and DATE_FORMAT(date,'%Y-%m-%d')>='$startdate' and DATE_FORMAT(date,'%Y-%m-%d')<'$enddate' ";
	if($separatebylang==1)
		$tmpsql.="and lang='$act_lang' ";
	if($category>0)
		$tmpsql.= "and (category='$category' or category=0)";
	else if($category==0)
		$tmpsql.= "and category=0";
	$tmpsql.=" order by date desc";
	if($maxannounce>0)
		$tmpsql.=" limit $maxannounce";
	if(!$tmpresult = mysql_query($tmpsql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if(mysql_num_rows($tmpresult)>0)
	{
		$announceavail=true;
		while($tmprow=mysql_fetch_array($tmpresult))
		{
			echo "<tr><td align=\"right\" valign=\"top\" class=\"timestampcol\" bgcolor=\"$timestampbgcolor\">";
			$entrynr++;
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
			echo "<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
			if($tmprow["category"]==0)
				echo "<img src=\"$url_gfx/$gannouncepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_global_announcement\" title=\"$l_global_announcement\"> ";
			else
				echo "<img src=\"$url_gfx/$announcepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_announcement\" title=\"$l_announcement\"> ";
			echo get_start_tag($timestampstyle);
			echo $displaydate;
			echo get_end_tag($timestampstyle);
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
			echo "<td align=\"center\"><table class=\"eventbox\" height=\"100%\" width=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\" cellspacing=\"0\" cellpadding=\"0\">";
			echo "<tr bgcolor=\"$newsheadingbgcolor\"><td align=\"left\" colspan=\"3\">";
			echo "<a href=\"javascript:entryAction($entrynr)\">";
			echo "<img align=\"top\" id=\"entryimg".$entrynr."\" src=\"".$url_gfx."/".$expandpic."\" border=\"0\" title=\"$l_expand\">";
			echo "</a> ";
			echo "<font face=\"$newsheadingfont\" size=\"$ev2_newsheadingfontsize\" color=\"$newsheadingfontcolor\">";
			echo get_start_tag($ev2_newsheadingstyle);
			echo display_encoded($tmprow["heading"]);
			echo get_end_tag($ev2_newsheadingstyle);
			echo "</font></td></tr>";
			echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\" colspan=\"3\">";
			echo "<table width=\"100%\" align=\"left\" cellspacing=\"0\" cellpadding=\"0\" id=\"entry".$entrynr."\" border=\"0\" style=\"visibility: hidden; display: none\">";
			echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\" colspan=\"3\">";
			echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$displaytext=stripslashes($tmprow["text"]);
			$displaytext = undo_htmlspecialchars($displaytext);
			echo $displaytext."</font></td></tr>";
			if($news5displayposter && (strlen($tmprow["poster"])>0))
			{
				echo "<tr bgcolor=\"$posterbgcolor\"><td align=\"left\" colspan=\"3\">";
				echo "<font face=\"$posterfont\" size=\"$posterfontsize\" color=\"$posterfontcolor\">";
				echo get_start_tag($posterstyle);
				echo "$l_poster: ".do_htmlentities($tmprow["poster"]);
				echo get_end_tag($posterstyle);
				echo "</font></td></tr>";
			}
			if(!$disableprint)
			{
				echo "<tr bgcolor=\"$contentbgcolor\">";
				echo "<td align=\"left\">";
				echo "&nbsp;";
				echo "</td><td align=\"right\" colspan=\"2\">";
				echo "<font face=\"$contentfont\" size=\"1\" color=\"$contentfontcolor\">";
				if($noprinticon==0)
				{
					echo "<a href=\"announceprint.php?$langvar=$act_lang&layout=$layout&announcenr=".$tmprow["entrynr"]."\" target=\"printwindow\">";
					if($printpic_small)
						echo "<img src=\"$url_gfx/$printpic_small\" border=\"0\" align=\"absmiddle\" title=\"$l_print\" alt=\"$l_print\">";
					else
						echo "[$l_print]";
					echo "</a>&nbsp; ";
				}
				if($nogotopicon==0)
					echo "<a href=\"#top\"><img src=\"$url_gfx/$pagetoppic\" border=\"0\" align=\"absmiddle\" title=\"$l_gotop\" alt=\"$l_gotop\"></a>";
				echo "</font></td></tr>";
			}
			echo "</table></td></tr></table></td></tr>";
		}
	}
	return $entrynr;
}
function display_announcements2c($year,$month,$category,$act_lang,$layout,$disableprint,$maxannounce,$exptype,$expanded, $baseurl)
{
	global $tableprefix, $servertimezone, $displaytimezone, $separatebylang, $db, $langvar;
	global $timestampbgcolor, $timestampfont, $timestampfontsize, $timestampfontcolor, $timestampstyle;
	global $news5dateformat, $l_announcement, $l_global_announcement, $gannouncepic, $announcepic;
	global $backurl, $url_gfx, $l_showentry, $news5linktarget, $news5displayicons, $news5displayposter;
	global $newsheadingfont, $ev2_newsheadingstyle, $newsheadingfontcolor, $ev2_newsheadingfontsize, $newsheadingbgcolor;
	global $l_gotop, $printpic_small, $l_print, $pagetoppic, $l_poster;
	global $contentbgcolor, $contentfont, $contentfontcolor, $contentfontsize;
	global $posterstyle, $posterfont, $posterfontsize, $posterfontcolor, $posterbgcolor;
	global $noprinticon, $nogotopicon, $url_icons, $l_expand, $expandpic;

	$acttime=transposetime(time(),$servertimezone,$displaytimezone);
	$tmpstart=mktime(0,0,0,$month,1,$year);
	$month++;
	if($month>12)
	{
		$month=1;
		$year++;
	}
	$tmpstop=mktime(0,0,0,$month,1,$year);
	$startdate=date("Y-m-d",$tmpstart);
	$enddate=date("Y-m-d",$tmpstop);
	$tmpsql = "select * from ".$tableprefix."_announce where (expiredate>=$acttime or expiredate=0) and (firstdate<=$acttime or firstdate=0) ";
	$tmpsql.=" and DATE_FORMAT(date,'%Y-%m-%d')>='$startdate' and DATE_FORMAT(date,'%Y-%m-%d')<'$enddate' ";
	if($separatebylang==1)
		$tmpsql.="and lang='$act_lang' ";
	if($category>0)
		$tmpsql.= "and (category='$category' or category=0)";
	else if($category==0)
		$tmpsql.= "and category=0";
	$tmpsql.=" order by date desc";
	if($maxannounce>0)
		$tmpsql.=" limit $maxannounce";
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
			echo "<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
			if($tmprow["category"]==0)
				echo "<img src=\"$url_gfx/$gannouncepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_global_announcement\" title=\"$l_global_announcement\"> ";
			else
				echo "<img src=\"$url_gfx/$announcepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_announcement\" title=\"$l_announcement\"> ";
			echo get_start_tag($timestampstyle);
			echo $displaydate;
			echo get_end_tag($timestampstyle);
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
			echo "<td align=\"center\"><table class=\"eventbox\" height=\"100%\" width=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\" cellspacing=\"0\" cellpadding=\"0\">";
			echo "<tr bgcolor=\"$newsheadingbgcolor\"><td align=\"left\" colspan=\"3\">";
			if(($exptype!="an") || ($expanded!=$tmprow["entrynr"]))
			{
				echo "<a href=\"$baseurl&exptype=an&expanded=".$tmprow["entrynr"]."\">";
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
				echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\" colspan=\"3\">";
				echo "<table width=\"100%\" align=\"left\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
				echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\" colspan=\"3\">";
				echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
				$displaytext=stripslashes($tmprow["text"]);
				$displaytext = undo_htmlspecialchars($displaytext);
				echo $displaytext."</font></td></tr>";
				if($news5displayposter && (strlen($tmprow["poster"])>0))
				{
					echo "<tr bgcolor=\"$posterbgcolor\"><td align=\"left\" colspan=\"3\">";
					echo "<font face=\"$posterfont\" size=\"$posterfontsize\" color=\"$posterfontcolor\">";
					echo get_start_tag($posterstyle);
					echo "$l_poster: ".do_htmlentities($tmprow["poster"]);
					echo get_end_tag($posterstyle);
					echo "</font></td></tr>";
				}
				if(!$disableprint)
				{
					echo "<tr bgcolor=\"$contentbgcolor\">";
					echo "<td align=\"left\">";
					echo "&nbsp;";
					echo "</td><td align=\"right\" colspan=\"2\">";
					echo "<font face=\"$contentfont\" size=\"1\" color=\"$contentfontcolor\">";
					if($noprinticon==0)
					{
						echo "<a href=\"announceprint.php?$langvar=$act_lang&layout=$layout&announcenr=".$tmprow["entrynr"]."\" target=\"printwindow\">";
						if($printpic_small)
							echo "<img src=\"$url_gfx/$printpic_small\" border=\"0\" align=\"absmiddle\" title=\"$l_print\" alt=\"$l_print\">";
						else
							echo "[$l_print]";
						echo "</a>&nbsp; ";
					}
					if($nogotopicon==0)
						echo "<a href=\"#top\"><img src=\"$url_gfx/$pagetoppic\" border=\"0\" align=\"absmiddle\" title=\"$l_gotop\" alt=\"$l_gotop\"></a>";
					echo "</font></td></tr>";
				}
				echo "</table></td></tr>";
			}
			echo "</table></td></tr>";
		}
	}
}
function get_announcements2($year,$month,$category,$act_lang,$layout,$maxannounce=0)
{
	global $tableprefix, $servertimezone, $displaytimezone, $separatebylang, $db, $langvar;
	global $l_poster, $crlf, $news5dateformat, $news5displayposter, $l_announcement, $l_global_announcement;

	$tmpdata="";
	$acttime=transposetime(time(),$servertimezone,$displaytimezone);
	$tmpstart=mktime(0,0,0,$month,1,$year);
	$month++;
	if($month>12)
	{
		$month=1;
		$year++;
	}
	$tmpstop=mktime(0,0,0,$month,1,$year);
	$startdate=date("Y-m-d",$tmpstart);
	$enddate=date("Y-m-d",$tmpstop);
	$tmpsql = "select * from ".$tableprefix."_announce where (expiredate>=$acttime or expiredate=0) and (firstdate<=$acttime or firstdate=0) ";
	$tmpsql.=" and DATE_FORMAT(date,'%Y-%m-%d')>='$startdate' and DATE_FORMAT(date,'%Y-%m-%d')<'$enddate' ";
	if($separatebylang==1)
		$tmpsql.="and lang='$act_lang' ";
	if($category>0)
		$tmpsql.= "and (category='$category' or category=0)";
	else if($category==0)
		$tmpsql.= "and category=0";
	$tmpsql.=" order by date desc";
	if($maxannounce>0)
		$tmpsql.=" limit $maxannounce";
	if(!$tmpresult = mysql_query($tmpsql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if(mysql_num_rows($tmpresult)>0)
	{
		while($tmprow=mysql_fetch_array($tmpresult))
		{
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
			$tmpdata.=$displaydate."\t";
			if($tmprow["category"]==0)
				$tmpdata.=undo_htmlentities($l_global_announcement);
			else
				$tmpdata.=undo_htmlentities($l_announcement);
			if(strlen($tmprow["heading"])>0)
			{
				$displayheading=undo_htmlentities(stripslashes($tmprow["heading"]));
				$displayheading=strip_tags($displayheading);
				$tmpdata.=": ".$displayheading."\t";
			}
			else
				$tmpdata.=" \t";
			$displaytext=undo_htmlentities(stripslashes($tmprow["text"]));
			$displaytext=strip_tags($displaytext);
			$tmpdata.=$displaytext."\t";
			if($news5displayposter && (strlen($tmprow["poster"])>0))
			{
				$displayposter=undo_htmlentities(stripslashes($tmprow["poster"]));
				$displayposter=strip_tags($displayposter);
				$tmpdata.=undo_htmlentities($l_poster).": ".$displayposter;
			}
			$tmpdata.=$crlf;
		}
	}
	return $tmpdata;
}
function display_announcements3($year,$month,$category,$act_lang,$layout,$maxannounce=0)
{
	global $tableprefix, $servertimezone, $displaytimezone, $separatebylang, $db, $langvar;
	global $timestampbgcolor, $timestampfont, $timestampfontsize, $timestampfontcolor, $timestampstyle;
	global $news5dateformat, $l_announcement, $l_global_announcement, $gannouncepic, $announcepic;
	global $backurl, $url_gfx, $l_showentry, $news5linktarget, $maxage;
	global $newsheadingfont, $newsheadingstyle, $newsheadingfontcolor, $newsheadingfontsize, $newsheadingbgcolor;
	global $hotnewsicons, $hotnewsmaxchars, $hotnewsdisplayposter, $hotnewsnohtmlformatting, $hn_newsheadingfontsize;
	global $contentbgcolor, $contentfont, $contentfontcolor, $contentfontsize;
	global $posterstyle, $posterfont, $posterfontsize, $posterfontcolor, $posterbgcolor;
	
	$acttime=transposetime(time(),$servertimezone,$displaytimezone);
	$tmpstart=mktime(0,0,0,$month,1,$year);
	$month++;
	if($month>12)
	{
		$month=1;
		$year++;
	}
	$tmpstop=mktime(0,0,0,$month,1,$year);
	$startdate=date("Y-m-d",$tmpstart);
	$enddate=date("Y-m-d",$tmpstop);
	$tmpsql = "select * from ".$tableprefix."_announce where (expiredate>=$acttime or expiredate=0) and (firstdate<=$acttime or firstdate=0) ";
	$tmpsql.=" and DATE_FORMAT(date,'%Y-%m-%d')>='$startdate' and DATE_FORMAT(date,'%Y-%m-%d')<'$enddate' ";
	if($separatebylang==1)
		$tmpsql.="and lang='$act_lang' ";
	if($maxage>0)
	{
		$actdate = date("Y-m-d H:i:s");
		$tmpsql.= "and date >= date_sub('$actdate', INTERVAL $maxage DAY) ";
	}
	if($category>0)
		$tmpsql.= "and (category='$category' or category=0)";
	else if($category==0)
		$tmpsql.= "and category=0";
	$tmpsql.=" order by date desc";
	if($maxannounce>0)
		$tmpsql.=" limit $maxannounce";
	if(!$tmpresult = mysql_query($tmpsql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if(mysql_num_rows($tmpresult)>0)
	{
		$announceavail=true;
		while($tmprow=mysql_fetch_array($tmpresult))
		{
			echo "<tr>";
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
			if($hotnewsicons==1)
			{
				echo "<td width=\"2%\" height=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\">";
				if($tmprow["headingicon"])
					echo "<img src=\"$url_icons/".$tmprow["headingicon"]."\" border=\"0\" align=\"middle\"> ";
				echo "</td>";
			}
			echo "<td align=\"center\"><table class=\"newsbox\" width=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\" cellspacing=\"0\" cellpadding=\"0\">";
			echo "<tr><td align=\"left\" bgcolor=\"$timestampbgcolor\">";
			echo "<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
			if($tmprow["category"]==0)
				echo "<img src=\"$url_gfx/$gannouncepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_global_announcement\" title=\"$l_global_announcement\"> ";
			else
				echo "<img src=\"$url_gfx/$announcepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_announcement\" title=\"$l_announcement\"> ";
			echo get_start_tag($timestampstyle);
			echo $displaydate;
			echo get_end_tag($timestampstyle);
			echo "</font></td></tr>";
			if(strlen($tmprow["heading"])>0)
			{
				echo "<tr bgcolor=\"$newsheadingbgcolor\"><td align=\"left\">";
				echo "<font face=\"$newsheadingfont\" size=\"$hn_newsheadingfontsize\" color=\"$newsheadingfontcolor\">";
				if($hotnewsmaxchars<1)
				{
					echo " <a href=\"".$url_simpnews."/announce.php?$langvar=$act_lang&layout=$layout&category=$category&announcenr=".$tmprow["entrynr"]."\"";
					if($hotnewstarget)
						echo " target=\"$hotnewstarget\"";
					echo ">";
				}
				$displayheading=undo_html_ampersand($tmprow["heading"]);
				if($hotnewsnohtmlformatting==1)
					$displayheading=strip_tags($displayheading);
				$displayheading=do_htmlentities($displayheading);
				$displayheading=undo_htmlspecialchars($displayheading);
				if($hotnewsnohtmlformatting==0)
					echo get_start_tag($newsheadingstyle);
				echo $displayheading;
				if($hotnewsnohtmlformatting==0)
					echo get_end_tag($newsheadingstyle);
				if($hotnewsmaxchars<1)
					echo "</a>";
				echo "</font></td></tr>";
			}
			if($hotnewsmaxchars!=0)
			{
				echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\">";
				echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
				$displaytext=stripslashes($tmprow["text"]);
				$displaytext = undo_htmlspecialchars($displaytext);
				if(($hotnewsmaxchars>0) && strlen($displaytext)>$hotnewsmaxchars)
				{
					$displaytext=undo_htmlentities($displaytext);
					$displaytext=strip_tags($displaytext);
					$displaytext=substr($displaytext,0,$hotnewsmaxchars);
					$displaytext=do_htmlentities($displaytext);
					$displaytext.=" <a href=\"".$url_simpnews."/announce.php?$langvar=$act_lang&layout=$layout&category=$category&announcenr=".$tmprow["announcenr"]."\"";
					if($hotnewstarget)
						$displaytext.=" target=\"$hotnewstarget\"";
					$displaytext.=">[...]</a>";
				} else if($hotnewsnohtmlformatting==1)
					strip_tags($displaytext);
				echo $displaytext."</font></td></tr>";
			}
			if($hotnewsdisplayposter && (strlen($tmprow["poster"])>0))
			{
				echo "<tr bgcolor=\"$posterbgcolor\"><td align=\"left\">";
				echo "<font face=\"$posterfont\" size=\"$posterfontsize\" color=\"$posterfontcolor\">";
				if($hotnewsnohtmlformatting==0)
					echo get_start_tag($posterstyle);
				echo "$l_poster: ".do_htmlentities($tmprow["poster"]);
				if($hotnewsnohtmlformatting==0)
					echo get_end_tag($posterstyle);
				echo "</font></td></tr>";
			}
			echo "</table></td></tr>";
		}
	}
}
?>