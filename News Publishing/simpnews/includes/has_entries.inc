<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function has_entries($link_date, $newsineventcal, $lang, $tableprefix, $db, $category, $lastvisitdate)
{
	global $separatebylang, $announceoptions, $servertimezone, $displaytimezone, $actdate;
	global $showfuturenews;
	$hasentries=0;
	if($lastvisitdate>0)
		$visitdate = date("Y-m-d H:i:00",$lastvisitdate);
	$sql = "select * from ".$tableprefix."_events where DATE_FORMAT(date,'%Y-%m-%d')='$link_date' ";
	if($separatebylang==1)
		$sql.= "and lang='$lang' ";
	if($category>=0)
		$sql.= "and category='$category' ";
	else
	{
		$sql.="and linkeventnr=0 ";
		$tmpsql="select * from ".$tableprefix."_categories where hideintotallist=1";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		while($tmprow=mysql_fetch_array($tmpresult))
			$sql.="and category!=".$tmprow["catnr"]." ";
	}
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if(mysql_num_rows($result)>0)
		$hasentries=1;
	if(($lastvisitdate>0) && ($hasentries>0))
	{
		$sql.= " and added>='$visitdate'";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if(mysql_num_rows($result)>0)
			$hasentries=2;
	}
	if(bittst($announceoptions,BIT_3))
	{
		$acttime=transposetime(time(),$servertimezone,$displaytimezone);
		$sql = "select * from ".$tableprefix."_announce where DATE_FORMAT(date,'%Y-%m-%d')='$link_date' and (expiredate>=$acttime or expiredate=0) and (firstdate<=$acttime or firstdate=0) ";
		if($separatebylang==1)
			$sql.="and lang='$lang' ";
		if($category>0)
			$sql.= "and (category='$category' or category=0)";
		else if($category==0)
			$sql.= "and category=0";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if((mysql_num_rows($result)>0) && ($hasentries<1))
		{
			list($year, $month, $day) = explode("-", $link_date);
			$linkdate=mktime(0,0,0,$month,$day,$year);
			if($linkdate>$lastvisitdate)
				$hasentries=2;
			else
				$hasentries=1;
		}
	}
	if(($newsineventcal==1) && ($hasentries<1))
	{
		$sql ="select * from ".$tableprefix."_data where DATE_FORMAT(date,'%Y-%m-%d')='$link_date' ";
		if($showfuturenews==0)
			$sql.="and date<='$actdate' ";
		if($separatebylang==1)
			$sql.="and lang='$lang' ";
		if($category>=0)
			$sql.= "and category='$category' ";
		else
		{
			$sql.="and linknewsnr=0 ";
			$tmpsql="select * from ".$tableprefix."_categories where hideintotallist=1";
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			while($tmprow=mysql_fetch_array($tmpresult))
				$sql.="and category!=".$tmprow["catnr"]." ";
		}
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if((mysql_num_rows($result)>0) && ($hasentries<1))
		{
			list($year, $month, $day) = explode("-", $link_date);
			$linkdate=mktime(0,0,0,$month,$day,$year);
			if($linkdate>$lastvisitdate)
				$hasentries=2;
			else
				$hasentries=1;
		}
	}
	return $hasentries;
}
function get_num_events($link_date, $newsineventcal, $lang, $tableprefix, $db, $category, $lastvisitdate)
{
	global 	$separatebylang, $actdate;
	$numentries=0;
	if($lastvisitdate>0)
		$visitdate = date("Y-m-d H:i:00",$lastvisitdate);
	$sql = "select * from ".$tableprefix."_events where DATE_FORMAT(date,'%Y-%m-%d')='$link_date' ";
	if($separatebylang==1)
		$sql.= "and lang='$lang' ";
	if($category>=0)
		$sql.= "and category='$category' ";
	else
	{
		$sql.="and linkeventnr=0 ";
		$tmpsql="select * from ".$tableprefix."_categories where hideintotallist=1";
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		while($tmprow=mysql_fetch_array($tmpresult))
			$sql.="and category!=".$tmprow["catnr"]." ";
	}
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if(mysql_num_rows($result)>0)
		$numentries+=mysql_num_rows($result);
	if($newsineventcal==1)
	{
		$sql = "select * from ".$tableprefix."_data where DATE_FORMAT(date,'%Y-%m-%d')='$link_date' ";
		$sql.= "and date<='$actdate' ";
		if($separatebylang==1)
			$sql.="and lang='$lang' ";
		if($category>=0)
			$sql.= "and category='$category' ";
		else
		{
			$sql.="and linknewsnr=0 ";
			$tmpsql="select * from ".$tableprefix."_categories where hideintotallist=1";
			if(!$tmpresult = mysql_query($tmpsql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			while($tmprow=mysql_fetch_array($tmpresult))
				$sql.="and category!=".$tmprow["catnr"]." ";
		}
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if(mysql_num_rows($result)>0)
			$numentries+=mysql_num_rows($result);
	}
	return $numentries;
}

function display_event_shorts($link_date, $newsineventcal, $lang, $tableprefix, $db, $category, $lastvisitdate, $shortlength, $shortnum, $onlyheadings, $backurl, $altlinkdest="")
{
	global $separatebylang, $layout, $newsheadingstyle, $l_more, $url_gfx, $newentrypic, $l_showevents, $url_simpnews, $morepic, $actdate;
	global $announceoptions, $announcepic, $langvar, $l_announcements, $gannouncepic, $maxevcannounce, $servertimezone, $displaytimezone;
	
	$entriesleft=$maxevcannounce;
	if(bittst($announceoptions,BIT_3))
	{
		$acttime=transposetime(time(),$servertimezone,$displaytimezone);
		$sql = "select * from ".$tableprefix."_announce where DATE_FORMAT(date,'%Y-%m-%d')='$link_date' and (expiredate>=$acttime or expiredate=0) and (firstdate<=$acttime or firstdate=0) ";
		if($separatebylang==1)
			$sql.="and lang='$lang' ";
		if($category>0)
			$sql.= "and (category='$category' or category=0)";
		else if($category==0)
			$sql.= "and category=0";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if(mysql_num_rows($result)>0)
		{
			if(!bittst($announceoptions,BIT_1))
				$announceurl=$url_simpnews."/announce.php?$langvar=$lang&category=$category&link_date=$link_date&backurl=".urlencode($backurl);
			else
				$announceurl=$url_simpnews."/events.php?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=$category&backurl=".urlencode($backurl);
			echo "<table class=\"eventshorts\" width=\"100%\">";
			if(!bittst($announceoptions,BIT_4))
			{
				echo "<tr class=\"eventshorts\"><td class=\"eventshorts\" align=\"right\">";
				echo "<a alt=\"$l_announcements\" title=\"$l_announcements\" href=\"$announceurl\">";
				echo "<img src=\"$url_gfx/$announcepic\" align=\"absmiddle\" border=\"0\"></a>";
				echo "</td></tr>";
			}
			else
			{
				while(($entriesleft>0) && ($myrow=mysql_fetch_array($result)))
				{
					$entriesleft--;
					echo "<tr class=\"eventshorts\"><td class=\"eventshorts\" align=\"left\">";
					echo "<a title=\"$l_announcements\" class=\"eventshorts\" href=\"$announceurl\">";
					if(strlen($myrow["heading"])>0)
					{
						echo get_start_tag($newsheadingstyle);
						echo undo_html_ampersand(do_htmlentities(stripslashes($myrow["heading"])));
						echo get_end_tag($newsheadingstyle);
						echo "<br>";
					}
					if($onlyheadings!=1)
					{
						$displaytext=undo_htmlspecialchars(stripslashes($myrow["text"]));
						if($shortlength>0)
						{
							$displaytext=strip_tags($displaytext);
							$displaytext=substr($displaytext,0,$shortlength);
						}
						echo $displaytext;
					}
					echo "</span>&nbsp; <a title=\"$l_announcements\" class=\"eventshorts\" href=\"$announceurl\">";
					if($myrow["category"]==0)
						$displaypic=$gannouncepic;
					else
						$displaypic=$announcepic;
					echo "<img src=\"$url_gfx/$displaypic\" border=\"0\" align=\"absmiddle\" alt=\"$l_announcements\" title=\"$l_announcements\">";
					echo "</a></td></tr>";
				}
			}
			echo "</table>";
		}
	}
	$entriesleft=$shortnum;
	if($lastvisitdate>0)
		$visitdate = date("Y-m-d H:i:00",$lastvisitdate);
	$sql = "select * from ".$tableprefix."_events where DATE_FORMAT(date,'%Y-%m-%d')='$link_date' ";
	if($separatebylang==1)
		$sql.= "and lang='$lang' ";
	if($category>=0)
		$sql.= "and category='$category'";
	else
	{
		$sql.="and linkeventnr=0 ";
		$tmpsql="select * from ".$tableprefix."_categories where hideintotallist=1";
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		while($tmprow=mysql_fetch_array($tmpresult))
			$sql.="and category!=".$tmprow["catnr"]." ";
	}
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if(mysql_num_rows($result)>0)
	{
		echo "<table class=\"eventshorts\" width=\"100%\">";
		while(($entriesleft>0) && ($myrow=mysql_fetch_array($result)))
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
			echo "<tr class=\"eventshorts\"><td class=\"eventshorts\" align=\"left\">";
			$entriesleft--;
			if(isset($lastvisitdate))
			{
				list($mydate,$mytime)=explode(" ",$entrydata["added"]);
				list($year, $month, $day) = explode("-", $mydate);
				list($hour, $min, $sec) = explode(":",$mytime);
				$thisentrydate=mktime($hour,$min,$sec,$month,$day,$year);
				if($thisentrydate>=$lastvisitdate)
				echo "<img src=\"$url_gfx/$newentrypic\" border=\"0\" align=\"absmiddle\">";
			}
			if(!$morepic)
			{
				$linkdest=$url_simpnews."/events.php?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=$category&backurl=".urlencode($backurl);
				echo "<a title=\"$l_showevents\" class=\"eventshorts\" href=\"$linkdest\">";
			}
			else
				echo "<span class=\"eventshorts\">";
			if(strlen($entrydata["heading"])>0)
			{
				echo get_start_tag($newsheadingstyle);
				echo undo_html_ampersand(do_htmlentities(stripslashes($entrydata["heading"])));
				echo get_end_tag($newsheadingstyle);
				echo "<br>";
			}
			if($onlyheadings!=1)
			{
				$displaytext=undo_htmlspecialchars(stripslashes($entrydata["text"]));
				if($shortlength>0)
				{
					$displaytext=strip_tags($displaytext);
					$displaytext=substr($displaytext,0,$shortlength);
				}
				echo $displaytext;
			}
			if($morepic)
			{
				$linkdest=$url_simpnews."/events.php?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=$category&backurl=".urlencode($backurl);
				echo "</span>&nbsp; <a title=\"$l_showevents\" class=\"eventshorts\" href=\"$linkdest\">";
				echo "<img src=\"$url_gfx/$morepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_showevents\" title=\"$l_showevents\">";
			}
			echo "</a></td></tr>";
		}
		echo "</table>";
	}
	if($entriesleft<1)
	{
		$linkdest=$url_simpnews."/events.php?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=$category&backurl=".urlencode($backurl);
		echo "<a title=\"$l_showevents\" class=\"eventlink\" href=\"$linkdest\">";
		if($morepic)
			echo "<img src=\"$url_gfx/$morepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_showevents\" title=\"$l_showevents\">";
		else
			echo $l_more;
		echo "</a>";
		return;
	}

	if($newsineventcal==0)
		return;
	$sql = "select * from ".$tableprefix."_data where DATE_FORMAT(date,'%Y-%m-%d')='$link_date' ";
	$sql.= "and date<='$actdate' ";
	if($separatebylang==1)
		$sql.="and lang='$lang' ";
	if($category>=0)
		$sql.= "and category='$category' ";
	else
	{
		$sql.="and linknewsnr=0 ";
		$tmpsql="select * from ".$tableprefix."_categories where hideintotallist=1";
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		while($tmprow=mysql_fetch_array($tmpresult))
			$sql.="and category!=".$tmprow["catnr"]." ";
	}
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if(mysql_num_rows($result)>0)
	{
		echo "<table class=\"eventshorts\" width=\"100%\">";
		while(($entriesleft>0) && ($myrow=mysql_fetch_array($result)))
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
			echo "<tr class=\"eventshorts\"><td class=\"eventshorts\" align=\"left\">";
			$entriesleft--;
			if(isset($lastvisitdate))
			{
				list($mydate,$mytime)=explode(" ",$myrow["date"]);
				list($year, $month, $day) = explode("-", $mydate);
				list($hour, $min, $sec) = explode(":",$mytime);
				$thisentrydate=mktime($hour,$min,$sec,$month,$day,$year);
				if($thisentrydate>=$lastvisitdate)
				echo "<img src=\"$url_gfx/$newentrypic\" border=\"0\" align=\"absmiddle\">";
			}
			if(!$morepic)
			{
				$linkdest=$url_simpnews."/events.php?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=".$entrydata["category"]."&backurl=".urlencode($backurl);
				echo "<a title=\"$l_showevents\" class=\"eventshorts\" href=\"$linkdest\">";
			}
			else
				echo "<span class=\"eventshorts\">";
			if(strlen($entrydata["heading"])>0)
			{
				echo get_start_tag($newsheadingstyle);
				echo undo_html_ampersand(do_htmlentities(stripslashes($entrydata["heading"])));
				echo get_end_tag($newsheadingstyle);
				echo "<br>";
			}
			if($onlyheadings!=1)
			{
				$displaytext=undo_htmlspecialchars(stripslashes($entrydata["text"]));
				if($shortlength>0)
				{
					$displaytext=strip_tags($displaytext);
					$displaytext=substr($displaytext,0,$shortlength);
				}
				echo $displaytext;
			}
			if($morepic)
			{
				$linkdest=$url_simpnews."/events.php?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=$category&backurl=".urlencode($backurl);
				echo "</span>&nbsp; <a title=\"$l_showevents\" class=\"eventshorts\" href=\"$linkdest\">";
				echo "<img src=\"$url_gfx/$morepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_showevents\" title=\"$l_showevents\">";
			}
			echo "</a></td></tr>";
		}
		echo "</table>";
	}
	if($entriesleft<1)
	{
		$linkdest=$url_simpnews."/events.php?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=$category&backurl=".urlencode($backurl);
		echo "<a title=\"$l_showevents\" class=\"eventlink\" href=\"$linkdest\">";
		if($morepic)
			echo "<img src=\"$url_gfx/$morepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_showevents\" title=\"$l_showevents\">";
		else
			echo $l_more;
		echo "</a>";
		return;
	}
}
function display_event_shorts2($link_date, $newsineventcal, $lang, $tableprefix, $db, $category, $lastvisitdate, $shortlength, $shortnum, $onlyheadings, $backurl)
{
	global $separatebylang, $layout, $newsheadingstyle, $l_more, $url_gfx, $newentrypic, $l_showevents, $url_simpnews, $morepic, $actdate;
	global $announceoptions, $announcepic, $langvar, $l_announcements, $gannouncepic, $maxevcannounce, $servertimezone, $displaytimezone;
	global $usehnlinkdest, $hnlinkdest, $hnlinkdestan, $hnlinkdestev;
	
	$entriesleft=$maxevcannounce;
	if(bittst($announceoptions,BIT_3))
	{
		$acttime=transposetime(time(),$servertimezone,$displaytimezone);
		$sql = "select * from ".$tableprefix."_announce where DATE_FORMAT(date,'%Y-%m-%d')='$link_date' and (expiredate>=$acttime or expiredate=0) and (firstdate<=$acttime or firstdate=0) ";
		if($separatebylang==1)
			$sql.="and lang='$lang' ";
		if($category>0)
			$sql.= "and (category='$category' or category=0)";
		else if($category==0)
			$sql.= "and category=0";
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if(mysql_num_rows($result)>0)
		{
			if(!bittst($announceoptions,BIT_1))
			{
				if($usehnlinkdest==1)
					$announceurl="$hnlinkdestan?$langvar=$lang&category=$category&link_date=$link_date&backurl=".urlencode($backurl);
				else
					$announceurl=$url_simpnews."/announce.php?$langvar=$lang&category=$category&link_date=$link_date&backurl=".urlencode($backurl);
			}
			else
			{
				if($usehnlinkdest==1)
					$announceurl="$hnlinkdestev?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=$category&backurl=".urlencode($backurl);
				else
					$announceurl=$url_simpnews."/events.php?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=$category&backurl=".urlencode($backurl);
			}
			echo "<table class=\"eventshorts\" width=\"100%\">";
			if(!bittst($announceoptions,BIT_4))
			{
				echo "<tr class=\"eventshorts\"><td class=\"eventshorts\" align=\"right\">";
				echo "<a alt=\"$l_announcements\" title=\"$l_announcements\" href=\"$announceurl\">";
				echo "<img src=\"$url_gfx/$announcepic\" align=\"absmiddle\" border=\"0\"></a>";
				echo "</td></tr>";
			}
			else
			{
				while(($entriesleft>0) && ($myrow=mysql_fetch_array($result)))
				{
					$entriesleft--;
					echo "<tr class=\"eventshorts\"><td class=\"eventshorts\" align=\"left\">";
					echo "<a title=\"$l_announcements\" class=\"eventshorts\" href=\"$announceurl\">";
					if(strlen($myrow["heading"])>0)
					{
						echo get_start_tag($newsheadingstyle);
						echo undo_html_ampersand(do_htmlentities(stripslashes($myrow["heading"])));
						echo get_end_tag($newsheadingstyle);
						echo "<br>";
					}
					if($onlyheadings!=1)
					{
						$displaytext=undo_htmlspecialchars(stripslashes($myrow["text"]));
						if($shortlength>0)
						{
							$displaytext=strip_tags($displaytext);
							$displaytext=substr($displaytext,0,$shortlength);
						}
						echo $displaytext;
					}
					echo "</span>&nbsp; <a title=\"$l_announcements\" class=\"eventshorts\" href=\"$announceurl\">";
					if($myrow["category"]==0)
						$displaypic=$gannouncepic;
					else
						$displaypic=$announcepic;
					echo "<img src=\"$url_gfx/$displaypic\" border=\"0\" align=\"absmiddle\" alt=\"$l_announcements\" title=\"$l_announcements\">";
					echo "</a></td></tr>";
				}
			}
			echo "</table>";
		}
	}
	$entriesleft=$shortnum;
	if($lastvisitdate>0)
		$visitdate = date("Y-m-d H:i:00",$lastvisitdate);
	$sql = "select * from ".$tableprefix."_events where DATE_FORMAT(date,'%Y-%m-%d')='$link_date' ";
	if($separatebylang==1)
		$sql.= "and lang='$lang' ";
	if($category>=0)
		$sql.= "and category='$category'";
	else
	{
		$sql.="and linkeventnr=0 ";
		$tmpsql="select * from ".$tableprefix."_categories where hideintotallist=1";
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		while($tmprow=mysql_fetch_array($tmpresult))
			$sql.="and category!=".$tmprow["catnr"]." ";
	}
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if(mysql_num_rows($result)>0)
	{
		echo "<table class=\"eventshorts\" width=\"100%\">";
		while(($entriesleft>0) && ($myrow=mysql_fetch_array($result)))
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
			echo "<tr class=\"eventshorts\"><td class=\"eventshorts\" align=\"left\">";
			$entriesleft--;
			if(isset($lastvisitdate))
			{
				list($mydate,$mytime)=explode(" ",$entrydata["added"]);
				list($year, $month, $day) = explode("-", $mydate);
				list($hour, $min, $sec) = explode(":",$mytime);
				$thisentrydate=mktime($hour,$min,$sec,$month,$day,$year);
				if($thisentrydate>=$lastvisitdate)
				echo "<img src=\"$url_gfx/$newentrypic\" border=\"0\" align=\"absmiddle\">";
			}
			if(!$morepic)
			{
				if($usehnlinkdest==1)
					$linkdest="$hnlinkdestev?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=$category&backurl=".urlencode($backurl);
				else
					$linkdest=$url_simpnews."/events.php?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=$category&backurl=".urlencode($backurl);
				echo "<a title=\"$l_showevents\" class=\"eventshorts\" href=\"$linkdest\">";
			}
			else
				echo "<span class=\"eventshorts\">";
			if(strlen($entrydata["heading"])>0)
			{
				echo get_start_tag($newsheadingstyle);
				echo undo_html_ampersand(do_htmlentities(stripslashes($entrydata["heading"])));
				echo get_end_tag($newsheadingstyle);
				echo "<br>";
			}
			if($onlyheadings!=1)
			{
				$displaytext=undo_htmlspecialchars(stripslashes($entrydata["text"]));
				if($shortlength>0)
				{
					$displaytext=strip_tags($displaytext);
					$displaytext=substr($displaytext,0,$shortlength);
				}
				echo $displaytext;
			}
			if($morepic)
			{
				if($usehnlinkdest==1)
					$linkdest="$hnlinkdestev?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=$category&backurl=".urlencode($backurl);
				else
					$linkdest=$url_simpnews."/events.php?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=$category&backurl=".urlencode($backurl);
				echo "</span>&nbsp; <a title=\"$l_showevents\" class=\"eventshorts\" href=\"$linkdest\">";
				echo "<img src=\"$url_gfx/$morepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_showevents\" title=\"$l_showevents\">";
			}
			echo "</a></td></tr>";
		}
		echo "</table>";
	}
	if($entriesleft<1)
	{
		if($usehnlinkdest==1)
			$linkdest="$hnlinkdestev?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=$category&backurl=".urlencode($backurl);
		else
			$linkdest=$url_simpnews."/events.php?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=$category&backurl=".urlencode($backurl);
		echo "<a title=\"$l_showevents\" class=\"eventlink\" href=\"$linkdest\">";
		if($morepic)
			echo "<img src=\"$url_gfx/$morepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_showevents\" title=\"$l_showevents\">";
		else
			echo $l_more;
		echo "</a>";
		return;
	}

	if($newsineventcal==0)
		return;
	$sql = "select * from ".$tableprefix."_data where DATE_FORMAT(date,'%Y-%m-%d')='$link_date' ";
	$sql.= "and date<='$actdate' ";
	if($separatebylang==1)
		$sql.="and lang='$lang' ";
	if($category>=0)
		$sql.= "and category='$category' ";
	else
	{
		$sql.="and linknewsnr=0 ";
		$tmpsql="select * from ".$tableprefix."_categories where hideintotallist=1";
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		while($tmprow=mysql_fetch_array($tmpresult))
			$sql.="and category!=".$tmprow["catnr"]." ";
	}
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if(mysql_num_rows($result)>0)
	{
		echo "<table class=\"eventshorts\" width=\"100%\">";
		while(($entriesleft>0) && ($myrow=mysql_fetch_array($result)))
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
			echo "<tr class=\"eventshorts\"><td class=\"eventshorts\" align=\"left\">";
			$entriesleft--;
			if(isset($lastvisitdate))
			{
				list($mydate,$mytime)=explode(" ",$myrow["date"]);
				list($year, $month, $day) = explode("-", $mydate);
				list($hour, $min, $sec) = explode(":",$mytime);
				$thisentrydate=mktime($hour,$min,$sec,$month,$day,$year);
				if($thisentrydate>=$lastvisitdate)
				echo "<img src=\"$url_gfx/$newentrypic\" border=\"0\" align=\"absmiddle\">";
			}
			if(!$morepic)
			{
				if($usehnlinkdest==1)
					$linkdest="$hnlinkdestev?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=".$entrydata["category"]."&backurl=".urlencode($backurl);
				else
					$linkdest=$url_simpnews."/events.php?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=".$entrydata["category"]."&backurl=".urlencode($backurl);
				echo "<a title=\"$l_showevents\" class=\"eventshorts\" href=\"$linkdest\">";
			}
			else
				echo "<span class=\"eventshorts\">";
			if(strlen($entrydata["heading"])>0)
			{
				echo get_start_tag($newsheadingstyle);
				echo undo_html_ampersand(do_htmlentities(stripslashes($entrydata["heading"])));
				echo get_end_tag($newsheadingstyle);
				echo "<br>";
			}
			if($onlyheadings!=1)
			{
				$displaytext=undo_htmlspecialchars(stripslashes($entrydata["text"]));
				if($shortlength>0)
				{
					$displaytext=strip_tags($displaytext);
					$displaytext=substr($displaytext,0,$shortlength);
				}
				echo $displaytext;
			}
			if($morepic)
			{
				if($usehnlinkdest==1)
					$linkdest="$hnlinkdestev?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=$category&backurl=".urlencode($backurl);
				else
					$linkdest=$url_simpnews."/events.php?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=$category&backurl=".urlencode($backurl);
				echo "</span>&nbsp; <a title=\"$l_showevents\" class=\"eventshorts\" href=\"$linkdest\">";
				echo "<img src=\"$url_gfx/$morepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_showevents\" title=\"$l_showevents\">";
			}
			echo "</a></td></tr>";
		}
		echo "</table>";
	}
	if($entriesleft<1)
	{
		if($usehnlinkdest==1)
			$linkdest="$hnlinkdestev?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=$category&backurl=".urlencode($backurl);
		else
			$linkdest=$url_simpnews."/events.php?$langvar=$lang&amp;layout=$layout&amp;link_date=$link_date&amp;category=$category&backurl=".urlencode($backurl);
		echo "<a title=\"$l_showevents\" class=\"eventlink\" href=\"$linkdest\">";
		if($morepic)
			echo "<img src=\"$url_gfx/$morepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_showevents\" title=\"$l_showevents\">";
		else
			echo $l_more;
		echo "</a>";
		return;
	}
}

function event_markers($link_date, $newsineventcal, $lang, $tableprefix, $db, $category, $lastvisitdate)
{
	global 	$separatebylang, $l_general, $evmarkcolgeneral, $actdate;
	if($category!=-1)
		return;
	if($lastvisitdate>0)
		$visitdate = date("Y-m-d H:i:00",$lastvisitdate);		
	$sql = "select * from ".$tableprefix."_events where DATE_FORMAT(date,'%Y-%m-%d')='$link_date' ";
	if($separatebylang==1)
		$sql.= "and lang='$lang' ";
	$sql.= "and category=0";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if(mysql_num_rows($result)>0)
		echo "<img class=\"evmarker\" style=\"background-color: $evmarkcolgeneral\" src=\"gfx/space.gif\" height=\"8\" width=\"8\" title=\"$l_general\" alt=\"$l_general\">";
	else if($newsineventcal)
	{
		$sql2 = "select * from ".$tableprefix."_data where DATE_FORMAT(date,'%Y-%m-%d')='$link_date' ";
		$sql2.= "and date<='$actdate' ";
		if($separatebylang==1)
			$sql2.="and lang='$lang' ";
		$sql2.="and category=0";
		if(!$result2 = mysql_query($sql2, $db))
	    		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if(mysql_num_rows($result2)>0)
			echo "<img class=\"evmarker\" style=\"background-color: $evmarkcolgeneral\" src=\"gfx/space.gif\" height=\"8\" width=\"8\" title=\"$l_general\" alt=\"$l_general\">";
	}
	$tmpsql="select * from ".$tableprefix."_categories where hideintotallist=0 order by displaypos asc";
	if(!$tmpresult = mysql_query($tmpsql, $db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	while($tmprow=mysql_fetch_array($tmpresult))
	{
		$sql = "select * from ".$tableprefix."_events where DATE_FORMAT(date,'%Y-%m-%d')='$link_date' ";
		if($separatebylang==1)
			$sql.= "and lang='$lang' ";
		$sql.="and linkeventnr=0 ";
		$sql.="and category=".$tmprow["catnr"];
		if(!$result = mysql_query($sql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$cattext=display_encoded(stripslashes($tmprow["catname"]));
			$tmpsql2="select * from ".$tableprefix."_catnames where catnr=".$tmprow["catnr"]." and lang='".$act_lang."'";
			if(!$tmpresult2=mysql_query($tmpsql2,$db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			if($tmprow2=mysql_fetch_array($tmpresult2))
			{
				if(strlen($tmprow2["catname"])>0)
					$cattext=display_encoded(stripslashes($tmprow2["catname"]));
			}
			echo "<img class=\"evmarker\" style=\"background-color: ".$tmprow["evmarkcolor"]."\" src=\"gfx/space.gif\" height=\"8\" width=\"8\" title=\"".$cattext."\" alt=\"".$cattext."\">";
		}
		else if($newsineventcal)
		{
			$sql2 = "select * from ".$tableprefix."_data where DATE_FORMAT(date,'%Y-%m-%d')='$link_date' ";
			$sql2.= "and date<='$actdate' ";
			if($separatebylang==1)
				$sql2.="and lang='$lang' ";
			$sql2.="and category=".$tmprow["catnr"];
			if(!$result2 = mysql_query($sql2, $db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			if(mysql_num_rows($result2)>0)
			{
				$cattext=display_encoded(stripslashes($tmprow["catname"]));
				$tmpsql2="select * from ".$tableprefix."_catnames where catnr=".$tmprow["catnr"]." and lang='".$act_lang."'";
				if(!$tmpresult2=mysql_query($tmpsql2,$db))
					die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
				if($tmprow2=mysql_fetch_array($tmpresult2))
				{
					if(strlen($tmprow2["catname"])>0)
						$cattext=display_encoded(stripslashes($tmprow2["catname"]));
				}
				echo "<img class=\"evmarker\" style=\"background-color: ".$tmprow["evmarkcolor"]."\" src=\"gfx/space.gif\" height=\"8\" width=\"8\" title=\"".$cattext."\" alt=\"".$cattext."\">";
			}
		}
	}
}
?>