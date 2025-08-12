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
if(!isset($sortorder))
	$sortorder=0;
if($heading)
	$pageheading=$heading;
else
	$pageheading=$l_news;
$actdate = date("Y-m-d H:i:00");
if(!isset($category))
	$category=0;
if(!isset($start))
	$start=0;
$baseurl="$act_script_url?$langvar=$act_lang&layout=$layout&category=$category";
$backurl=$baseurl."&start=$start";
$backurl=urlencode($backurl);
if($category>0)
{
	$sql="select * from ".$tableprefix."_categories where catnr='$category'";
	if(!$result = mysql_query($sql, $db))
	    die("Unable to connect to database.".mysql_error());
	if(!$myrow=mysql_fetch_array($result))
		die("No such category");
	if($myrow["isarchiv"]!=1)
		$redirect="news4.php?$langvar=$act_lang&layout=$layout&category=$category";
}
else
	$redirect="news4.php?$langvar=$act_lang&layout=$layout&category=$category";
if(isset($redirect))
{
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
	echo "<META HTTP-EQUIV=\"refresh\" content=\"0.01; URL=$redirect\">";
	exit;
}
if($lastvisitcookie==1)
	include("./includes/lastvisit.inc");
include('./includes/header.inc');
echo "<div align=\"$tblalign\"><table width=\"$TableWidth\" border=\"0\" CELLPADDING=\"1\" CELLSPACING=\"0\" class=\"sntable\" align=\"$tblalign\">";
?>
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
if($category<0)
	$enablepropose=0;
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
$sql = "select * from ".$tableprefix."_data where category='$category' ";
if($separatebylang==1)
	$sql.="and lang='$act_lang' ";
switch($sortorder)
{
	case 10:
		$sql.= "order by heading asc";
		break;
	case 11:
		$sql.= "order by heading desc";
		break;
	case 20:
		$sql.= "order by date asc";
		break;
	case 21:
		$sql.= "order by date desc";
		break;
	default:
		$sql.= "order by displaypos asc";
		break;
}
if(!$result = mysql_query($sql, $db))
    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
$numentries=mysql_numrows($result);
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
		if(($enablesearch==1) || ($pagenavdetails==1))
		{
			if(!isset($start))
				$start=0;
			echo "<TR BGCOLOR=\"$headingbgcolor\" ALIGN=\"CENTER\">";
			if($enablesearch==1)
			{
				echo "<TD ALIGN=\"center\" VALIGN=\"MIDDLE\" width=\"5%\">";
				echo "<font face=\"$contentfont\" size=\"1\" color=\"$headingfontcolor\">";
				echo "<a href=\"search.php?$langvar=$act_lang&amp;layout=$layout&amp;category=$category&amp;start=$start&amp;backurl=$backurl\">";
				echo "<img src=\"$url_gfx/$searchpic\" border=\"0\" align=\"absmiddle\" title=\"$l_search\" alt=\"$l_search\"></a>";
				echo "</font></td>";
			}
			if(($numrows>0) && ($pagenavdetails==1))
			{
				echo "<TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\" ";
				if(($enablesearch==1) || ($enablepropose==1))
					echo "width=\"95%\"";
				else
					echo "colspan=\"2\"";
				echo "><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$headingfontcolor\">";
				if(($entriesperpage+$start)>$numentries)
					$displayresults=$numentries;
				else
					$displayresults=($entriesperpage+$start);
				$displaystart=$start+1;
				$displayend=$displayresults;
				echo "<b>$l_page ".ceil(($start/$entriesperpage)+1)."/".ceil(($numentries/$entriesperpage))."</b><br><b>($l_entries $displaystart - $displayend $l_of $numentries)</b>";
				echo "</font></td></tr>";
			}
		}
	}
	else if($enablesearch==1)
	{
		echo "<tr bgcolor=\"$headingbgcolor\"><td align=\"left\" colspan=\"2\">";
		echo "<font face=\"$headingfont\" size=\"1\" color=\"$headingfontcolor\">";
		echo "<a href=\"search.php?$langvar=$act_lang&amp;layout=$layout&amp;category=$category&amp;backurl=$backurl\">";
		echo "<img src=\"$url_gfx/$searchpic\" border=\"0\" align=\"absmiddle\" title=\"$l_search\" alt=\"$l_search\"></a>";
		echo "</font></td></tr>";
	}
	if(($entriesperpage>0) && ($numentries>$entriesperpage))
	{
		if($pagenavdetails==1)
		{
			echo "</tr><tr bgcolor=\"$headingbgcolor\"><td align=\"center\" colspan=\"2\">";
		}
		else
		{
			if($enablesearch==1)
				echo "<td align=\"center\">";
			else
				echo "<tr bgcolor=\"$headingbgcolor\"><td align=\"center\" colspan=\"2\">";
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
		echo "<td>&nbsp;</td></tr>";
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
	echo "<tr><td align=\"right\" width=\"15%\" bgcolor=\"$timestampbgcolor\">";
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
	echo "<tr bgcolor=\"$headingbgcolor\"><td align=\"center\" colspan=\"2\">";
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
	echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"center\" colspan=\"2\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo $l_noentries;
	echo "</font></td></tr>";
}
echo "</table></td></tr></table></div>";
include ("./includes/footer.inc");
?>