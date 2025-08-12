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
if(isset($limitdays))
	$maxage=$limitdays;
if(isset($enddate))
	$maxage=0;
setlocale(LC_TIME, $def_locales[$act_lang]);
if($heading)
	$pageheading=$heading;
else
	$pageheading=$l_news;
if(($enablesubscriptions==1) && ($displaysubscriptionbox==1))
	$page="subscription";
if(!isset($sortorder))
	$sortorder=0;
$actdate = date("Y-m-d 23:59:59");
if(!isset($category))
	$category=0;
if(!isset($start))
	$start=0;
$baseurl="$act_script_url?$langvar=$act_lang&layout=$layout&category=$category&sortorder=$sortorder";
if(isset($maxannounce))
	$baseurl.="&maxannounce=$maxannounce";
if(isset($catframe))
	$baseurl.="&catframe=$catframe";
if(isset($limitdays))
	$baseurl.="&limitdays=$limitdays";
if(isset($startdate))
	$baseurl.="&startdate=$startdate";
if(isset($enddate))
	$baseurl.="&enddate=$enddate";
if(isset($maxentries))
	$baseurl.="&maxentries=$maxentries";
$backurl=$baseurl."&start=$start";
$backurl=urlencode($backurl);
if(!isset($catframe))
	$catframe=0;
if($lastvisitcookie==1)
	include("./includes/lastvisit.inc");
include('./includes/header.inc');
if(($news4showcat==1) && ($category<0))
	$colspan=3;
else
	$colspan=2;
if($n4nodate==1)
	$colspan--;
if(($enablecatlist==1) && ($catframe==0))
{
?>
<div align="<?php echo $tblalign?>">
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" class="sntable" align="<?php echo $tblalign?>">
<tr><td width="<?php echo $clleftwidth?>" height="100%" valign="top">
<table width="100%" border="0" CELLPADDING="1" CELLSPACING="0" class="sntable" align="center">
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%" valign="top">
<TR BGCOLOR="<?php echo $clheadingbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $clheadingfont?>" size="<?php echo $clheadingfontsize?>" color="<?php echo $clheadingfontcolor?>"><b><?php echo $l_catlist?></b></font></td></tr>
<tr bgcolor="<?php echo $clcontentbgcolor?>"><td align="left">
<?php
	if($category==0)
		$actbgcolor=$clcontenthighlight;
	else
		$actbgcolor=$clcontentbgcolor;
	echo "<tr bgcolor=\"$actbgcolor\">";
	echo "<td align=\"left\"";
	if($clnowrap==1)
		echo " nowrap";
	echo ">";
	$catlink=$baseurl."&category=0";
	if((($clactdontlink==1) && ($category!=0)) || ($clactdontlink==0))
	{
		echo "<a class=\"catlistlink\" href=\"$catlink\">";
	}
	echo "<font size=\"$clcontentfontsize\" face=\"$clcontentfont\" color=\"$clcontentfontcolor\">";
	echo "$l_general</font>";
	if((($clactdontlink==1) && ($category!=0)) || ($clactdontlink==0))
		echo "</a>";
	echo "</td></tr>";
	$sql = "select * from ".$tableprefix."_categories where hideincatlist=0 order by displaypos asc";
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	while($myrow=mysql_fetch_array($result))
	{
		$catlink=$baseurl."&category=".$myrow["catnr"];
		$cattext=display_encoded(stripslashes($myrow["catname"]));
		$tmpsql="select * from ".$tableprefix."_catnames where catnr=".$myrow["catnr"]." and lang='".$act_lang."'";
		if(!$tmpresult=mysql_query($tmpsql,$db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if($tmprow=mysql_fetch_array($tmpresult))
		{
			if(strlen($tmprow["catname"])>0)
				$cattext=display_encoded(stripslashes($tmprow["catname"]));
		}
		if($myrow["catnr"]==$category)
			$actbgcolor=$clcontenthighlight;
		else
			$actbgcolor=$clcontentbgcolor;
		echo "<tr bgcolor=\"$actbgcolor\"><td align=\"left\"";
		if($clnowrap==1)
			echo " nowrap";
		echo ">";
		if((($clactdontlink==1) && ($category!=$myrow["catnr"])) || ($clactdontlink==0))
		{
			echo "<a class=\"catlistlink\" href=\"$catlink\">";
		}
		echo "<font size=\"$clcontentfontsize\" face=\"$clcontentfont\" color=\"$clcontentfontcolor\">";
		echo "$cattext</font>";
		if((($clactdontlink==1) && ($category!=$myrow["catnr"])) || ($clactdontlink==0))
			echo "</a>";
		echo "</td></tr>";
	}
	echo "</table></td></tr></table></td><td valign=\"top\" width=\"$clrightwidth\">";
}
if(($enablecatlist==1) && ($catframe==0))
	echo "<table width=\"100%\" border=\"0\" CELLPADDING=\"1\" CELLSPACING=\"0\" class=\"sntable\" align=\"center\">";
else
	echo "<div align=\"$tblalign\"><table width=\"$TableWidth\" border=\"0\" CELLPADDING=\"1\" CELLSPACING=\"0\" class=\"sntable\" align=\"$tblalign\">";
?>
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
if(strlen($heading)>0)
{
?>
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="<?php echo $colspan?>"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><b><?php echo $heading?></b></font></td></tr>
<?php
}
if(($enablepropose==1) && ($maxpropose>0))
{
	$sql="select count(entrynr) as numpropose from ".$tableprefix."_tmpdata";
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if($myrow=mysql_fetch_array($result))
		if($myrow["numpropose"]>=$maxpropose)
			$enablepropose=0;
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
		if($enablepropose==1)
			$enablepropose=$myrow["enablepropose"];
		echo "<tr bgcolor=\"$headingbgcolor\">";
		echo "<td align=\"center\"";
		if(bittst($myrow["iconoptions"],BIT_4))
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
$sql = "select * from ".$tableprefix."_data ";
if($category>=0)
	$sql.="where category='$category' ";
else
{
	$sql.="where linknewsnr=0 ";
	$tmpsql="select * from ".$tableprefix."_categories where hideintotallist=1";
	if(!$tmpresult = mysql_query($tmpsql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	while($tmprow=mysql_fetch_array($tmpresult))
		$sql.="and category!=".$tmprow["catnr"]." ";
}
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
if($numentries>0)
{
	echo "<TR BGCOLOR=\"$bordercolor\" ALIGN=\"CENTER\"><td colspan=\"$colspan\"><table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\">";
	if($entriesperpage>0)
	{
		if(isset($start) && ($start>0) && ($numentries>$entriesperpage))
		{
			if(!isset($maxentries) || (($start+entriesperpage) <= $maxentries))
				$sql .=" limit $start,$entriesperpage";
			else
			{
				$limiting=$maxentries-start;
				$sql .=" limit $start,$limiting";
			}
		}
		else
		{
			if(!isset($maxentries) || ($maxentries>$entriesperpage))
				$sql .=" limit $entriesperpage";
			else
				$sql .=" limit $maxentries";
			$start=0;
		}
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		$numrows=mysql_num_rows($result);
		if(($enablesearch==1) || ($enablepropose==1) || ($pagenavdetails==1))
		{
			if(!isset($start))
				$start=0;
			echo "<tr bgcolor=\"$headingbgcolor\">";
			if(($enablesearch==1) || ($enablepropose==1) || isset($backurl))
			{
				echo "<TD ALIGN=\"center\" VALIGN=\"MIDDLE\" width=\"5%\" nowrap>";
				echo "<font face=\"$contentfont\" size=\"1\" color=\"$headingfontcolor\">";
				if($enablesearch==1)
				{
					echo "<a href=\"search.php?$langvar=$act_lang&amp;layout=$layout&amp;category=$category&amp;start=$start&amp;backurl=$backurl\">";
					echo "<img src=\"$url_gfx/$searchpic\" border=\"0\" align=\"absmiddle\" title=\"$l_search\" alt=\"$l_search\"></a>";
				}
				if(($enablepropose==1) && ($category>=0))
				{
					if($enablesearch==1)
						echo "&nbsp; ";
					echo "<a href=\"propose.php?$langvar=$act_lang&amp;layout=$layout&amp;category=$category&amp;start=$start&amp;mode=new&amp;backurl=$backurl\">";
					echo "<img src=\"$url_gfx/$proposepic\" border=\"0\" align=\"absmiddle\" title=\"$l_propose_news\" alt=\"$l_propose_news\"></a>";
				}
				if(isset($srcurl))
				{
					if(($enablesearch==1) || ($enablepropose==1))
						echo "&nbsp; ";
					echo "<a href=\"$srcurl\"><img src=\"$url_gfx/$backpic\" border=\"0\" align=\"absmiddle\" title=\"$l_back\" alt=\"$l_back\"></a>";
				}
				echo "</font></td>";
			}
			if(($numrows>0) && ($pagenavdetails==1))
			{
				echo "<TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\" ";
				if(($enablesearch==1) || ($enablepropose==1))
					echo "width=\"95%\"";
				echo "><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$headingfontcolor\">";
				if(($entriesperpage+$start)>$numentries)
					$displayresults=$numentries;
				else
					$displayresults=($entriesperpage+$start);
				$displaystart=$start+1;
				$displayend=$displayresults;
				echo "<b>$l_page ".ceil(($start/$entriesperpage)+1)."/".ceil(($numentries/$entriesperpage))."</b><br><b>($l_entries $displaystart - $displayend $l_of $numentries)</b>";
				echo "</font></td>";
			}
		}
	}
	else if(($enablesearch==1) || ($enablepropose==1))
	{
		echo "<tr bgcolor=\"$headingbgcolor\">";
		echo "<td align=\"left\">";
		echo "<font face=\"$headingfont\" size=\"1\" color=\"$headingfontcolor\">";
		if($enablesearch==1)
		{
			echo "<a href=\"search.php?$langvar=$act_lang&amp;layout=$layout&amp;category=$category&amp;backurl=$backurl\">";
			echo "<img src=\"$url_gfx/$searchpic\" border=\"0\" align=\"absmiddle\" title=\"$l_search\" alt=\"$l_search\"></a>";
		}
		if(($enablepropose==1) && ($category>=0))
		{
			if($enablesearch==1)
				echo "&nbsp; ";
			echo "<a href=\"propose.php?$langvar=$act_lang&amp;layout=$layout&amp;category=$category&amp;start=$start&amp;mode=new&amp;backurl=$backurl\">";
			echo "<img src=\"$url_gfx/$proposepic\" border=\"0\" align=\"absmiddle\" title=\"$l_propose_news\" alt=\"$l_propose_news\"></a>";
		}
		echo "</font>";
		echo "</td></tr>";
	}
	if(($entriesperpage>0) && ($numentries>$entriesperpage))
	{
		if($pagenavdetails==1)
		{
			echo "</tr><tr class =\"pagenav\" bgcolor=\"$headingbgcolor\"><td align=\"center\" colspan=\"$colspan\">";
		}
		else
		{
			if(($enablesearch==1) || ($enablepropose==1))
				echo "<td align=\"center\">";
			else
				echo "<tr bgcolor=\"$headingbgcolor\"><td align=\"center\">";
		}
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
	else if(($pagenavdetails==0) && (($enablesearch==1) || ($enablepropose==1)) && ($entriesperpage>0))
	{
		echo "<td>>&nbsp;</td></tr>";
	}
	echo "</table></td></tr>";
}
$announceavail=false;
if(bittst($announceoptions,BIT_2) && ($start==0))
{
		$acttime=transposetime(time(),$servertimezone,$displaytimezone);
		$tmpsql = "select * from ".$tableprefix."_announce where (expiredate>=$acttime or expiredate=0)  and (firstdate<=$acttime or firstdate=0) ";
		if(isset($link_date))
			$tmpsql.=" and DATE_FORMAT(date,'%Y-%m-%d')='$link_date' ";
		if($separatebylang==1)
			$tmpsql.="and lang='$act_lang' ";
		if($category>0)
			$tmpsql.= "and (category='$category' or category=0)";
		else if($category==0)
			$tmpsql.= "and category=0";
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
				echo "<tr>";
				if($n4nodate==0)
				{
					echo "<td align=\"right\" width=\"15%\" bgcolor=\"$timestampbgcolor\">";
					list($mydate,$mytime)=explode(" ",$tmprow["date"]);
					list($year, $month, $day) = explode("-", $mydate);
					list($hour, $min, $sec) = explode(":",$mytime);
					if($month>0)
					{
						$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
						$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
						$displaydate=strftime($news4dateformat,$displaytime);
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
				}
				if(($news4showcat==1) && ($category<0))
				{
					if($tmprow["category"]>0)
					{
						$catsql="select * from ".$tableprefix."_categories where catnr=".$tmprow["category"];
						if(!$catresult = mysql_query($catsql, $db))
							die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
						if($catrow=mysql_fetch_array($catresult))
						{
							$catname=display_encoded(stripslashes($catrow["catname"]));
							$tmpsql2="select * from ".$tableprefix."_catnames where catnr=".$catrow["catnr"]." and lang='".$act_lang."'";
							if(!$tmpresult2=mysql_query($tmpsql2,$db))
								die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
							if($tmprow2=mysql_fetch_array($tmpresult2))
							{
								if(strlen($tmprow2["catname"])>0)
									$cattext=display_encoded(stripslashes($tmprow2["catname"]));
							}
						}
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
					$displaytext="";
				$tmptext = stripslashes($tmprow["text"]);
				$tmptext = undo_htmlspecialchars($tmptext);
				$tmptext = strip_tags($tmptext);
				if(strlen($tmptext)>$news4maxchars)
					$tmptext = subwords($tmptext,$news4maxchars);
				if(strlen($tmptext)>0)
				{
					if(strlen($displaytext)>0)
					{
						$displaytext.="<br>";
						if(bittst($news4addoptions,BIT_1))
							$displaytext.="<br>";
					}
					$displaytext.=$tmptext;
				}
				echo "<td class=\"n4entry\" bgcolor=\"$newsheadingbgcolor\" align=\"left\">";
				if($n4nodate==1)
				{
					if($tmprow["category"]==0)
						echo "<img src=\"$url_gfx/$gannouncepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_global_announcement\" title=\"$l_global_announcement\"> ";
					else
						echo "<img src=\"$url_gfx/$announcepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_announcement\" title=\"$l_announcement\"> ";
				}
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
if($numentries>0)
{
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
		echo "<tr>";
		if($n4nodate==0)
		{
			echo "<td align=\"right\" width=\"15%\" bgcolor=\"$timestampbgcolor\">";
			list($mydate,$mytime)=explode(" ",$myrow["date"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			if($month>0)
			{
				$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
				$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
				$displaydate=strftime($news4dateformat,$displaytime);
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
		}
		if(($news4showcat==1) && ($category<0))
		{
			if($entrydata["category"]>0)
			{
				$catsql="select * from ".$tableprefix."_categories where catnr=".$entrydata["category"];
				if(!$catresult = mysql_query($catsql, $db))
					die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
				if($catrow=mysql_fetch_array($catresult))
				{
					$catname=display_encoded(stripslashes($catrow["catname"]));
					$tmpsql2="select * from ".$tableprefix."_catnames where catnr=".$catrow["catnr"]." and lang='".$act_lang."'";
					if(!$tmpresult2=mysql_query($tmpsql2,$db))
						die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
					if($tmprow2=mysql_fetch_array($tmpresult2))
					{
						if(strlen($tmprow2["catname"])>0)
							$cattext=display_encoded(stripslashes($tmprow2["catname"]));
					}
				}
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
			$displaytext="";
		$tmptext = stripslashes($entrydata["text"]);
		$tmptext = undo_htmlspecialchars($tmptext);
		$tmptext = strip_tags($tmptext);
		if(strlen($tmptext)>$news4maxchars)
			$tmptext = subwords($tmptext,$news4maxchars);
		if(strlen($tmptext)>0)
		{
			if(strlen($displaytext)>0)
			{
				$displaytext.="<br>";
				if(bittst($news4addoptions,BIT_1))
					$displaytext.="<br>";
			}
			$displaytext.=$tmptext;
		}
		echo "<td class=\"n4entry\" bgcolor=\"$newsheadingbgcolor\" align=\"left\">";
		echo "<font face=\"$newsheadingfont\" size=\"$news4fontsize\" color=\"$newsheadingfontcolor\">";
		if($entrydata["tickerurl"])
			$linkdest=$entrydata["tickerurl"];
		else
			$linkdest="singlenews.php?$langvar=$act_lang&layout=$layout&category=$category&newsnr=".$entrydata["newsnr"]."&backurl=$backurl&srcscript=$act_script_url";
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
	}
	if(($entriesperpage>0) && ($numentries>$entriesperpage))
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
else
{
	if(($enablepropose==1) && ($category>=0))
	{
		echo "<tr bgcolor=\"$headingbgcolor\"><td align=\"left\" colspan=\"$colspan\">";
		echo "<font face=\"$contentfont\" size=\"1\" color=\"$headingfontcolor\">";
		echo "<a href=\"propose.php?$langvar=$act_lang&amp;layout=$layout&amp;category=$category&amp;start=$start&amp;mode=new&amp;backurl=$backurl\">";
		echo "<img src=\"$url_gfx/$proposepic\" border=\"0\" align=\"absmiddle\" title=\"$l_propose_news\" alt=\"$l_propose_news\"></a>";
		echo "</font></td></tr>";
	}
	if(!$announceavail)
	{
		echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"center\" colspan=\"$colspan\">";
		echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo $l_noentries;
		echo "</font></td></tr>";
	}
}
if($enablesubscriptions==1)
{
	$backurl_form=urldecode($backurl);
	if($displaysubscriptionbox==1)
	{
?>
<TR BGCOLOR="<?php echo $subscriptionbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="<?php echo $colspan?>">
<form name="subscriptionform" onsubmit="return checkform()" method="post" action="subscription.php">
<input type="hidden" name="layout" value="<?php echo $layout?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="backurl" value="<?php echo htmlentities($backurl_form)?>">
<input type="hidden" name="newscat" value="<?php echo $category?>">
<table width="100%" bgcolor="<?php echo $subscriptionbgcolor?>" align="center">
<tr><td align="center" colspan="2">
<font face="<?php echo $subscriptionfont?>" size="<?php echo $subscriptionfontsize?>" color="<?php echo $subscriptionfontcolor?>">
<b><?php echo $l_subscribe?></b></font></td></tr>
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
<TR BGCOLOR="<?php echo $subscriptionbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="<?php echo $colspan?>">
<a class="actionlink" href="subscription.php?<?php echo "$langvar=$act_lang"?>&amp;layout=<?php echo $layout?>&amp;category=<?php echo $category?>&amp;backurl=<?php echo $backurl?>">
<font face="<?php echo $subscriptionfont?>" size="<?php echo $subscriptionfontsize?>" color="<?php echo $subscriptionfontcolor?>">
<?php echo $l_subscribe?></font></a></td></tr>
<?php
	}
}
if(($enablecatlist==1) && ($catframe==0))
{
	echo "</table></td></tr>";
}
echo "</table></td></tr></table></div>";
include ("./includes/footer.inc");
?>