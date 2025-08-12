<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('./config.php');
require_once('./functions.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./includes/get_settings.inc');
require_once('./includes/block_leacher.inc');
setlocale(LC_TIME, $def_locales[$act_lang]);
if(($enablesubscriptions==1) && ($displaysubscriptionbox==1))
	$page="subscription";
if($heading)
	$pageheading=$heading;
else
	$pageheading=$l_news;
if(isset($limitdays))
	$maxage=$limitdays;
if(isset($enddate))
	$maxage=0;
if(!isset($sortorder))
	$sortorder=0;
$actdate = date("Y-m-d 23:59:59");
$baseurl="$act_script_url?$langvar=$act_lang&layout=$layout&sortorder=$sortorder";
if(isset($maxannounce))
	$baseurl.="&maxannounce=$maxannounce";
if(isset($limitdays))
	$baseurl.="&limitdays=$limitdays";
if(isset($startdate))
	$baseurl.="&startdate=$startdate";
if(isset($enddate))
	$baseurl.="&enddate=$enddate";
if(isset($maxentries))
{
	$baseurl.="&maxentries=$maxentries";
	$news3entries=$maxentries;
}
$backurl=urlencode($baseurl);
if($lastvisitcookie==1)
	include("./includes/lastvisit.inc");
include('./includes/header.inc');
?>
<div align="<?php echo $tblalign?>">
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tblalign?>" class="sntable">
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
if(strlen($heading)>0)
{
?>
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><b><?php echo $heading?></b></font></td></tr>
<?php
}
if($enablesearch==1)
{
	echo "<tr bgcolor=\"$headingbgcolor\"><td align=\"left\" colspan=\"2\">";
	echo "<font face=\"$headingfont\" size=\"1\" color=\"$headingfontcolor\">";
	echo "<a href=\"search.php?$langvar=$act_lang&amp;layout=$layout&amp;category=-1&amp;backurl=$backurl\">";
	echo "<img src=\"$url_gfx/$searchpic\" border=\"0\" align=\"absmiddle\" title=\"$l_search\" alt=\"$l_search\">";
	echo "</a></font></td></tr>";
}
echo "<tr bgcolor=\"$bordercolor\"><td align=\"left\" colspan=\"2\">";
echo "<table align=\"left\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr bgcolor=\"$categorybgcolor\">";
if($enablepropose==1)
{
		echo "<td bgcolor=\"$headingbgcolor\" align=\"center\" width=\"2%\">";
		echo "<font face=\"$contentfont\" size=\"1\" color=\"$headingfontcolor\">";
		echo "<a href=\"propose.php?$langvar=$act_lang&amp;layout=$layout&amp;category=0&amp;mode=new&amp;backurl=$backurl\">";
		echo "<img src=\"$url_gfx/$proposepic\" border=\"0\" align=\"absmiddle\" title=\"$l_propose_news\" alt=\"$l_propose_news\"></a>";
		echo "</font></td>";
}
else
		echo "<td align=\"center\" width=\"2%\">&nbsp;</td>";
echo "<td align=\"left\" width=\"98%\">";
echo "<font face=\"$categoryfont\" size=\"$categoryfontsize\" color=\"$categoryfontcolor\">";
echo "&nbsp;<a title=\"$l_showcategory\" class=\"catlink\" href=\"news4.php?$langvar=$act_lang&amp;layout=$layout&amp;category=0&amp;srcurl=$backurl\" target=\"$news2target\">";
echo get_start_tag($categorystyle);
echo "$l_general";
echo get_end_tag($categorystyle);
echo "</a></font></td></tr></table></td></tr>";
$announceavail=false;
if(bittst($announceoptions,BIT_2))
{
		$acttime=transposetime(time(),$servertimezone,$displaytimezone);
		$tmpsql = "select * from ".$tableprefix."_announce where (expiredate>=$acttime or expiredate=0)  and (firstdate<=$acttime or firstdate=0) and category=0 ";
		if(isset($link_date))
			$tmpsql.=" and DATE_FORMAT(date,'%Y-%m-%d')='$link_date' ";
		if($separatebylang==1)
			$tmpsql.="and lang='$act_lang' ";
		switch($sortorder)
		{
			case 0:
				$tmpsql.=" order by date desc";
				break;
			case 1:
				$tmpsql.=" order by date asc";
				break;
			case 2:
				$tmpsql.=" order by heading asc";
				break;
			case 3:
				$tmpsql.=" order by heading desc";
				break;
		}
		if(isset($maxannounce))
			$tmpsql.=" limit $maxannounce";
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if(mysql_num_rows($tmpresult)>0)
		{
			$announceavail=true;
			while($tmprow=mysql_fetch_array($tmpresult))
			{
				echo "<tr><td align=\"right\" width=\"15%\" bgcolor=\"$timestampbgcolor\">";
				list($mydate,$mytime)=explode(" ",$tmprow["date"]);
				list($year, $month, $day) = explode("-", $mydate);
				list($hour, $min, $sec) = explode(":",$mytime);
				if($month>0)
				{
					$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
					$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
					$displaydate=strftime($news3dateformat,$displaytime);
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
				if(strlen($tmprow["heading"])>0)
					$displaytext=undo_html_ampersand(do_htmlentities($tmprow["heading"]));
				else
				{
					$displaytext = stripslashes($tmprow["text"]);
					$displaytext = undo_htmlspecialchars($displaytext);
					$displaytext = strip_tags($displaytext);
					if(strlen($displaytext)>$news3maxchars)
						$displaytext = subwords($displaytext,$news3maxchars);
				}
				echo "<td class=\"newsbox\" bgcolor=\"$newsheadingbgcolor\" align=\"left\">";
				echo "<font face=\"$newsheadingfont\" size=\"$news3fontsize\" color=\"$newsheadingfontcolor\">";
				if($tmprow["tickerurl"])
					$linkdest=$tmprow["tickerurl"];
				else
					$linkdest="announce.php?$langvar=$act_lang&layout=$layout&announcenr=".$tmprow["entrynr"]."&backurl=$backurl";
				echo "<a title=\"$l_showentry\" class=\"newslink\" href=\"$linkdest\" target=\"$news3linktarget\">";
				echo $displaytext;
				echo "</a></font></td></tr>";
			}
		}
}
$sql = "select * from ".$tableprefix."_data where category=0 ";
if($separatebylang==1)
	$sql.="and lang='$act_lang' ";
if(isset($startdate))
	$sql.= "and date>='$startdate' ";
if(isset($enddate))
	$sql.= "and date<='$enddate' ";
if($maxage>0)
	$sql.= "and date >= date_sub('$actdate', INTERVAL $maxage DAY) ";
if($showfuturenews==0)
	$sql.="and date<='$actdate' ";
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
$numentries=mysql_numrows($result);
$sql .=" limit $news3entries";
if(!$result = mysql_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
$numdisplayed=mysql_numrows($result);
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
	echo "<tr><td align=\"right\" width=\"15%\" bgcolor=\"$timestampbgcolor\">";
	list($mydate,$mytime)=explode(" ",$myrow["date"]);
	list($year, $month, $day) = explode("-", $mydate);
	list($hour, $min, $sec) = explode(":",$mytime);
	if($month>0)
	{
		$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
		$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
		$displaydate=strftime($news3dateformat,$displaytime);
	}
	else
		$displaydate="";
	echo "<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
	if(isset($lastvisitdate))
	{
		list($mydate,$mytime)=explode(" ",$myrow["date"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		$thisentrydate=mktime($hour,$min,$sec,$month,$day,$year);
		if($thisentrydate>=$lastvisitdate)
			echo "<img src=\"$url_gfx/$newentrypic\" border=\"0\" align=\"absmiddle\">&nbsp;&nbsp;";
	}
	echo get_start_tag($timestampstyle);
	echo $displaydate;
	echo get_end_tag($timestampstyle);
	echo "</font></td>";
	if(strlen($entrydata["heading"])>0)
		$displaytext=undo_html_ampersand(do_htmlentities($entrydata["heading"]));
	else
	{
		$displaytext = stripslashes($entrydata["text"]);
		$displaytext = undo_htmlspecialchars($displaytext);
		$displaytext = strip_tags($displaytext);
		if(strlen($displaytext)>$news3maxchars)
			$displaytext = subwords($displaytext,$news3maxchars);
	}
	echo "<td class=\"newsbox\" bgcolor=\"$newsheadingbgcolor\" align=\"left\">";
	echo "<font face=\"$newsheadingfont\" size=\"$news3fontsize\" color=\"$newsheadingfontcolor\">";
	if($entrydata["tickerurl"])
		$linkdest=$entrydata["tickerurl"];
	else
		$linkdest="singlenews.php?$langvar=$act_lang&layout=$layout&newsnr=".$entrydata["newsnr"]."&srcurl=$backurl&srcscript=$act_script_url";
	if($news3useddlink)
	{
		$tempsql="select f.filename, f.filesize, f.mimetype, na.* from ".$tableprefix."_news_attachs na, ".$tableprefix."_files f where f.entrynr=na.attachnr and na.newsnr=".$entrydata["newsnr"]." order by na.entrynr asc";
		if(!$tempresult=mysql_query($tempsql,$db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if($temprow=mysql_fetch_array($tempresult))
			$linkdest="sndownload.php?entrynr=".$temprow["attachnr"];
	}
	echo "<a title=\"$l_showentry\" class=\"newslink\" href=\"$linkdest\" target=\"$news3linktarget\">";
	echo $displaytext;
	echo "</a></font></td></tr>";
}
if(($numdisplayed==0) && (!$announceavail))
{
	echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"center\" colspan=\"2\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo $l_nonewnews;
	echo "</font></td></tr>";
}
if($numdisplayed<$numentries)
{
?>
<tr bgcolor="<?php echo $headingbgcolor?>"><td align="right" colspan="2">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<a class="morelink" href="news4.php?<?php echo "$langvar=$act_lang"?>&amp;layout=<?php echo $layout?>&amp;category=0&amp;srcurl=<?php echo $backurl?>" target="<?php echo $news2target?>">
<?php echo "[$l_morenews]"?></a></font></td></tr>
<?php
}
echo "<tr bgcolor=\"$bordercolor\"><td colspan=\"2\">";
echo "<img src=\"$url_gfx/space.gif\" border=\"0\" width=\"1\" height=\"1\">";
echo "</td></tr>";
$sql = "select * from ".$tableprefix."_categories";
if(!$result = mysql_query($sql, $db))
    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
while($myrow=mysql_fetch_array($result))
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
	echo "<tr bgcolor=\"$categorybgcolor\"><td align=\"left\" colspan=\"2\">";
	echo "<table align=\"left\" width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr bgcolor=\"$categorybgcolor\">";
	if(($enablepropose==1) && ($myrow["enablepropose"]==1))
	{
			echo "<td bgcolor=\"$headingbgcolor\" align=\"center\" width=\"2%\">";
			echo "<font face=\"$contentfont\" size=\"1\" color=\"$headingfontcolor\">";
			echo "<a href=\"propose.php?$langvar=$act_lang&amp;layout=$layout&amp;category=".$myrow["catnr"]."&amp;mode=new&amp;backurl=$backurl\">";
			echo "<img src=\"$url_gfx/$proposepic\" border=\"0\" align=\"absmiddle\" title=\"$l_propose_news\" alt=\"$l_propose_news\"></a>";
			echo "</font></td>";
	}
	else
			echo "<td align=\"center\" width=\"2%\">&nbsp;</td>";
	echo "<td align=\"left\" width=\"98%\">";
	if(bittst($myrow["iconoptions"],BIT_3))
		echo "<img src=\"".$url_icons."/".$myrow["icon"]."\" border=\"0\" align=\"absmiddle\"> ";
	echo "<font face=\"$categoryfont\" size=\"$categoryfontsize\" color=\"$categoryfontcolor\">";
	echo "&nbsp;<a class=\"catlink\" href=\"news4.php?$langvar=$act_lang&amp;layout=$layout&amp;category=".$myrow["catnr"]."&amp;srcurl=$backurl\" target=\"$news2target\">";
	echo get_start_tag($categorystyle);
	echo $cattext;
	echo get_end_tag($categorystyle);
	echo "</a></font></td></tr></table></td></tr>";
	$announceavail=false;
	if(bittst($announceoptions,BIT_2))
	{
			$acttime=transposetime(time(),$servertimezone,$displaytimezone);
			$tmpsql = "select * from ".$tableprefix."_announce where (expiredate>=$acttime or expiredate=0)  and (firstdate<=$acttime or firstdate=0) and category=".$myrow["catnr"]." ";
			if(isset($link_date))
				$tmpsql.=" and DATE_FORMAT(date,'%Y-%m-%d')='$link_date' ";
			if($separatebylang==1)
				$tmpsql.="and lang='$act_lang' ";
			switch($sortorder)
			{
				case 0:
					$tmpsql.=" order by date desc";
					break;
				case 1:
					$tmpsql.=" order by date asc";
					break;
				case 2:
					$tmpsql.=" order by heading asc";
					break;
				case 3:
					$tmpsql.=" order by heading desc";
					break;
			}
			if(isset($maxannounce))
				$tmpsql.=" limit $maxannounce";
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			if(mysql_num_rows($tmpresult)>0)
			{
				$announceavail=true;
				while($tmprow=mysql_fetch_array($tmpresult))
				{
					echo "<tr><td align=\"right\" width=\"15%\" bgcolor=\"$timestampbgcolor\">";
					list($mydate,$mytime)=explode(" ",$tmprow["date"]);
					list($year, $month, $day) = explode("-", $mydate);
					list($hour, $min, $sec) = explode(":",$mytime);
					if($month>0)
					{
						$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
						$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
						$displaydate=strftime($news3dateformat,$displaytime);
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
					if(strlen($tmprow["heading"])>0)
						$displaytext=undo_html_ampersand(do_htmlentities($tmprow["heading"]));
					else
					{
						$displaytext = stripslashes($tmprow["text"]);
						$displaytext = undo_htmlspecialchars($displaytext);
						$displaytext = strip_tags($displaytext);
						if(strlen($displaytext)>$news3maxchars)
							$displaytext = subwords($displaytext,$news3maxchars);
					}
					echo "<td class=\"newsbox\" bgcolor=\"$newsheadingbgcolor\" align=\"left\">";
					echo "<font face=\"$newsheadingfont\" size=\"$news3fontsize\" color=\"$newsheadingfontcolor\">";
					$linkdest="announce.php?$langvar=$act_lang&layout=$layout&announcenr=".$tmprow["entrynr"]."&backurl=$backurl";
					echo "<a title=\"$l_showentry\" class=\"newslink\" href=\"$linkdest\" target=\"$news3linktarget\">";
					echo $displaytext;
					echo "</a></font></td></tr>";
				}
			}
	}
	$sql2 = "select * from ".$tableprefix."_data where category=".$myrow["catnr"]." ";
	if($separatebylang==1)
	{
		$sql2.="and lang='$act_lang' ";
	}
	if(isset($startdate))
		$sql2.= "and date>='$startdate' ";
	if(isset($enddate))
		$sql2.= "and date<='$enddate' ";
	if($maxage>0)
		$sql2.= "and date >= date_sub('$actdate', INTERVAL $maxage DAY) ";
	$sql2.="and date<='$actdate' ";
	switch($sortorder)
	{
		case 0:
			$sql2.=" order by date desc";
			break;
		case 1:
			$sql2.=" order by date asc";
			break;
		case 2:
			$sql2.=" order by heading asc";
			break;
		case 3:
			$sql2.=" order by heading desc";
			break;
	}
	if(!$dataresult = mysql_query($sql2, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	$numentries=mysql_numrows($dataresult);
	$sql2 .=" limit $news3entries";
	if(!$dataresult = mysql_query($sql2, $db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	$numdisplayed=mysql_numrows($dataresult);
	while($datarow=mysql_fetch_array($dataresult))
	{
		if($datarow["linknewsnr"]==0)
			$entrydata=$datarow;
		else
		{
			$tmpsql="select * from ".$tableprefix."_data where newsnr=".$datarow["linknewsnr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			if(!$tmprow=mysql_fetch_array($tmpresult))
				die("<tr class=\"errorrow\"><td>Unable to get data");
			$entrydata=$tmprow;
		}
		echo "<tr><td align=\"right\" width=\"15%\" bgcolor=\"$timestampbgcolor\">";
		list($mydate,$mytime)=explode(" ",$datarow["date"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
		{
			$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
			$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
			$displaydate=strftime($news3dateformat,$displaytime);
		}
		else
			$displaydate="";
		echo "<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
		if(isset($lastvisitdate))
		{
			list($mydate,$mytime)=explode(" ",$datarow["date"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			$thisentrydate=mktime($hour,$min,$sec,$month,$day,$year);
			if($thisentrydate>=$lastvisitdate)
				echo "<img src=\"$url_gfx/$newentrypic\" border=\"0\" align=\"absmiddle\">&nbsp;&nbsp;";
		}
		echo get_start_tag($timestampstyle);
		echo $displaydate;
		echo get_end_tag($timestampstyle);
		echo "</font></td>";
		if(strlen($entrydata["heading"])>0)
			$displaytext=undo_html_ampersand(do_htmlentities($entrydata["heading"]));
		else
		{
			$displaytext = stripslashes($entrydata["text"]);
			$displaytext = undo_htmlspecialchars($displaytext);
			$displaytext = strip_tags($displaytext);
			if(strlen($displaytext)>$news3maxchars)
				$displaytext = subwords($displaytext,$news3maxchars);
		}
		echo "<td class=\"newsbox\" bgcolor=\"$newsheadingbgcolor\" align=\"left\">";
		echo "<font face=\"$newsheadingfont\" size=\"$news3fontsize\" color=\"$newsheadingfontcolor\">";
		if($entrydata["tickerurl"])
			$linkdest=$entrydata["tickerurl"];
		else
			$linkdest="singlenews.php?$langvar=$act_lang&layout=$layout&newsnr=".$entrydata["newsnr"]."&backurl=$backurl&srcscript=$act_script_url";
		if($news3useddlink)
		{
			$tempsql="select f.filename, f.filesize, f.mimetype, na.* from ".$tableprefix."_news_attachs na, ".$tableprefix."_files f where f.entrynr=na.attachnr and na.newsnr=".$entrydata["newsnr"]." order by na.entrynr asc";
			if(!$tempresult=mysql_query($tempsql,$db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			if($temprow=mysql_fetch_array($tempresult))
				$linkdest="sndownload.php?entrynr=".$temprow["attachnr"];
		}
		echo "<a title=\"$l_showentry\" class=\"newslink\" href=\"$linkdest\" target=\"$news3linktarget\">";
		echo $displaytext;
		echo "</a></font></td></tr>";
	}
	if(($numdisplayed==0) && !$announceavail)
	{
		echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"center\" colspan=\"2\">";
		echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo $l_nonewnews;
		echo "</font></td></tr>";
	}
	if($numdisplayed<$numentries)
	{
?>
<tr bgcolor="<?php echo $headingbgcolor?>"><td align="right" colspan="2">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<a class="morelink" href="news4.php?<?php echo $langvar=$act_lang?>&amp;layout=<?php echo $layout?>&amp;category=<?php echo $myrow["catnr"]?>&amp;srcurl=<?php echo $backurl?>" target="<?php echo $news2target?>">
<?php echo "[$l_morenews]"?></a></font></td></tr>
<?php
	}
	echo "<tr bgcolor=\"$bordercolor\"><td colspan=\"2\">";
	echo "<img src=\"$url_gfx/space.gif\" border=\"0\" width=\"1\" height=\"1\">";
	echo "</td></tr>";
}
if($enablesubscriptions==1)
{
	$backurl_form=urldecode($backurl);
	if($displaysubscriptionbox==1)
	{
?>
<TR BGCOLOR="<?php echo $subscriptionbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2">
<form name="subscriptionform" onsubmit="return checkform()" method="post" action="subscription.php">
<input type="hidden" name="layout" value="<?php echo $layout?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="backurl" value="<?php echo $backurl_form?>">
<table width="100%" bgcolor="<?php echo $subscriptionbgcolor?>" align="center">
<tr><td align="center" colspan="2">
<font face="<?php echo $subscriptionfont?>" size="<?php echo $subscriptionfontsize?>" color="<?php echo $subscriptionfontcolor?>">
<b><?php echo $l_subscribe?></b></font></td></tr>
<tr><td align="right" width="30%"><font face="<?php echo $subscriptionfont?>" size="<?php echo $subscriptionfontsize?>" color="<?php echo $subscriptionfontcolor?>">
<?php echo $l_category?>:</font></td><td>
<select class="subscription" name="newscat">
<option value="0"><?php echo $l_all_cats?></option>
<?php
$sql="select * from ".$tableprefix."_categories order by displaypos asc";
if(!$result = mysql_query($sql, $db))
    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
while($myrow=mysql_fetch_array($result))
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
	echo "<option value=\"".$myrow["catnr"]."\">";
	echo $cattext;
	echo "</option>";
}
?>
</select></td></tr>
<tr><td align="right" width="30%"><font face="<?php echo $subscriptionfont?>" size="<?php echo $subscriptionfontsize?>" color="<?php echo $subscriptionfontcolor?>">
<?php echo $l_email?>:</font></td><td><font face="<?php echo $subscriptionfont?>" size="<?php echo $subscriptionfontsize?>" color="<?php echo $subscriptionfontcolor?>">
<input class="snewsinput" type="text" name="email" size="40" maxlength="240"></font></td></tr>
<?php
if($subemailtype==0)
{
?>
<tr><td align="right" width="30%" valign="top"><font face="<?php echo $subscriptionfont?>" size="<?php echo $subscriptionfontsize?>" color="<?php echo $subscriptionfontcolor?>">
<?php echo $l_emailtype?>:</font></td><td><font face="<?php echo $subscriptionfont?>" size="<?php echo $subscriptionfontsize?>" color="<?php echo $subscriptionfontcolor?>">
<input type="radio" name="emailtype" value="0" checked> <?php echo $l_htmlmail?><br>
<input type="radio" name="emailtype" value="1"> <?php echo $l_ascmail?></font></td></tr>
<?php
}
else
	echo "<input type=\"hidden\" name=\"emailtype\" value=\"".($subemailtype-1)."\">";
?>
<tr><td>&nbsp;</td><td align="left" valign="top"><font face="<?php echo $subscriptionfont?>" size="<?php echo $subscriptionfontsize?>" color="<?php echo $subscriptionfontcolor?>">
<input type="radio" name="mode" value="subscribe" checked> <?php echo $l_subscribe?><br>
<input type="radio" name="mode" value="unsubscribe"> <?php echo $l_unsubscribe?></font></td></tr>
<tr><td align="center" colspan="2"><font face="<?php echo $subscriptionfont?>" size="<?php echo $subscriptionfontsize?>" color="<?php echo $subscriptionfontcolor?>">
<input class="snewsbutton" type="submit" value="<?php echo $l_ok?>"></font></td></tr>
</table></form></td></tr>
<?php
	}
	else if($displaysubscriptionbox==2)
	{
?>
<form method="post" action="subscription.php">
<?php
if(is_konqueror())
	echo "<tr><td></td></tr>";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="layout" value="<?php echo $layout?>">
<TR BGCOLOR="<?php echo $subscriptionbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2">
<font face="<?php echo $subscriptionfont?>" size="<?php echo $subscriptionfontsize?>" color="<?php echo $subscriptionfontcolor?>">
<b><?php echo $l_subscribe?>:</b>&nbsp;<select class="subscription" name="category">
<option value="0"><?php echo $l_all_cats?></option>
<?php
$sql="select * from ".$tableprefix."_categories order by displaypos asc";
if(!$result = mysql_query($sql, $db))
    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
while($myrow=mysql_fetch_array($result))
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
	echo "<option value=\"".$myrow["catnr"]."\">";
	echo $cattext;
	echo "</option>";
}
?>
</select>&nbsp;
<input type="hidden" name="backurl" value="<?php echo htmlentities($backurl_form)?>">
<input type="submit" name="submit" value="<?php echo $l_ok?>" class="snewsbutton">
</font></td></tr></form>
<?php
	}
}
echo "</table></td></tr></table></div>";
include ("./includes/footer.inc");
?>