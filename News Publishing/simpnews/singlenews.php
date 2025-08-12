<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('./config.php');
require_once('./functions.php');
if(isset($highlight))
	$highlightwords=$highlight;
else
	$highlightwords="";
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
if($commentsinline==1)
	require_once('./includes/com_inline.inc');
if(!isset($newsnr))
	die($l_callingerror);
$actdate = date("Y-m-d H:i:00");
if($heading)
	$pageheading=$heading;
else
	$pageheading=$l_news;
if(!isset($backurl))
	$backurl="news.php?$langvar=$act_lang&category=$category&layout=$layout";
if(!isset($srcscript))
	$srcscript="news.php";
if($sncatlink=="source script")
	$catlink=$srcscript;
else
	$catlink=$sncatlink;
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
		if($enablerating==1)
			$enablerating=$myrow2["enablerating"];
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
		echo "<a title=\"$l_showcategory\" class=\"catlink\" href=\"".$catlink."?$langvar=$act_lang&amp;layout=$layout&amp;category=".$myrow2["catnr"]."\">";
		echo "<b>".$cattext."</b>";
		echo "</a></font></td></tr>";
		$catfooteroptions=$myrow2["footeroptions"];
		$catfooter=$myrow2["customfooter"];
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
else if($myrow["category"]==0)
{
	echo "<tr bgcolor=\"$headingbgcolor\">";
	echo "<td align=\"center\" colspan=\"2\">";
	echo "<font face=\"$headingfont\" size=\"$contentfontsize\" color=\"$headingfontcolor\">";
	echo "<a title=\"$l_showcategory\" class=\"catlink\" href=\"".$catlink."?$langvar=$act_lang&amp;layout=$layout&amp;category=0\">";
	echo "<b>".$l_general."</b>";
	echo "</a></font></td></tr>";
}
if($myrow["norating"]==1)
	$enablerating=0;
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
if($snnodate==0)
{
	echo "<tr><td align=\"left\" bgcolor=\"$timestampbgcolor\"";
	if(($allowcomments==0) || ($myrow["allowcomments"]==0))
		echo "colspan=\"3\"";
	else
		echo "width=\"80%\"";
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
}
if(($allowcomments==1) && ($myrow["allowcomments"]==1))
{
	if($snnodate==1)
		echo "<tr>";
	echo "<td class=\"commentaction\" align=\"right\" bgcolor=\"$timestampbgcolor\" width=\"1%\" valign=\"top\">";
	echo "<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
	$tempsql="select * from ".$tableprefix."_comments where entryref=".$myrow["newsnr"];
	if(!$tempresult = mysql_query($tempsql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	$numcomments=mysql_num_rows($tempresult);
	if(($numcomments>0) && ($commentsinline==0))
	{
		echo "<a class=\"commentlink\" href=\"comment.php?mode=display&amp;$langvar=$act_lang&amp;entryref=".$myrow["newsnr"];
		echo "&amp;backurl=".urlencode($backurl)."\">";
		if($commentspic)
			echo "<img style=\"margin: 1px\" src=\"".$url_gfx."/$commentspic\" border=\"0\" alt=\"$l_comments: $numcomments\" title=\"$l_comments: $numcomments\">";
		else
			echo "$l_comments:&nbsp;$numcomments";
		echo "</a>";
	}
	else
		echo "&nbsp;";
	echo "</font></td>";
	echo "<td class=\"commentaction\" align=\"right\" bgcolor=\"$timestampbgcolor\" width=\"1%\">";
	echo "<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
	echo "<a class=\"commentlink\" href=\"comment.php?$langvar=$act_lang&amp;mode=new&amp;entryref=".$myrow["newsnr"];
	if(isset($backurl))
		echo "&amp;backurl=".urlencode($backurl);
	echo "\">";
	if($writecommentpic)
		echo "<img style=\"margin: 1px\" src=\"".$url_gfx."/$writecommentpic\" border=\"0\" alt=\"$l_writecomment\" title=\"$l_writecomment\">";
	else
		echo "$l_writecomment";
	echo "</a></font></td>";
}
echo "</tr>";
if(strlen($myrow["heading"])>0)
{
	echo "<tr bgcolor=\"$newsheadingbgcolor\"><td align=\"left\" colspan=\"3\">";
	echo "<font face=\"$newsheadingfont\" size=\"$newsheadingfontsize\" color=\"$newsheadingfontcolor\">";
	echo get_start_tag($newsheadingstyle);
	if(isset($highlight))
	{
		$entryheading=stripslashes($myrow["heading"]);
		$entryheading=highlight_words($entryheading,$highlightwords);
		echo undo_htmlspecialchars(do_htmlentities($entryheading));
	}
	else
		echo undo_html_ampersand(do_htmlentities($myrow["heading"]));
	echo get_end_tag($newsheadingstyle);
	echo "</font></td></tr>";
}
echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\" colspan=\"3\">";
echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
if(isset($highlight))
{
	$displaytext=stripslashes($myrow["text"]);
	$displaytext=undo_htmlentities($displaytext);
	$displaytext=highlight_words($displaytext,$highlightwords);
	$displaytext=undo_htmlspecialchars(do_htmlentities($displaytext));
}
else
{
	$displaytext=stripslashes($myrow["text"]);
	$displaytext = undo_htmlspecialchars($displaytext);
}
echo $displaytext."</font></td></tr>";
if($displayposter && (strlen($myrow["poster"])>0) && ($attachpos==0))
	posterline($myrow["posterid"], $myrow["poster"], $linkposter, $myrow["exposter"]);
if($attachpos==0)
	echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\">";
displayattachs_news($myrow["newsnr"],$attachpos, $nofileinfo);
if($attachpos==1)
{
	if($displayposter && (strlen($myrow["poster"])>0))
		posterline($myrow["posterid"], $myrow["poster"], $linkposter, $myrow["exposter"]);
	echo "<tr bgcolor=\"$contentbgcolor\"><td>&nbsp;</td>";
}
echo "<td align=\"right\" colspan=\"2\">";
echo "<font face=\"$contentfont\" size=\"1\" color=\"$contentfontcolor\">";
if($noprinticon==0)
{
	echo "<a href=\"print.php?$langvar=$act_lang&layout=$layout&newsnr=".$myrow["newsnr"]."\" target=\"printwindow\">";
	if($printpic_small)
		echo "<img class=\"iconbutton\" src=\"$url_gfx/$printpic_small\" border=\"0\" align=\"absmiddle\" title=\"$l_print\" alt=\"$l_print\">";
	else
		echo "[$l_print]";
	echo "</a>";
}
if($nogotopicon==0)
	echo "<a href=\"#top\"><img class=\"iconbutton\" src=\"$url_gfx/$pagetoppic\" border=\"0\" align=\"absmiddle\" title=\"$l_gotop\" alt=\"$l_gotop\"></a>";
if($emailnews==1)
	echo "<a href=\"newsmail.php?$langvar=$act_lang&layout=$layout&newsnr=".$myrow["newsnr"]."\" target=\"mailwindow\"><img class=\"iconbutton\" src=\"$url_gfx/$emailpic\" border=\"0\" align=\"absmiddle\" title=\"$l_emailentry\" alt=\"$l_emailentry\"></a>";
echo "</td></tr>";
if(($allowcomments==1) && ($myrow["allowcomments"]==1) && ($commentsinline==1))
	display_inline_comments($myrow["newsnr"]);
echo "</table></td></tr>";
if($useviewcounts==1)
{
	$tmpsql = "UPDATE ".$tableprefix."_data SET views = views + 1 WHERE (newsnr = $newsnr)";
	@mysql_query($tmpsql, $db);
}
$preventry="";
$nextentry="";
if(!isset($nonav))
{
	$tempsql="select * from ".$tableprefix."_data ";
	$firstarg=1;
	if($category>=0)
	{
		$firstarg=0;
		$tempsql.="where category='$category' ";
	}
	if($separatebylang==1)
	{
		if($firstarg==1)
		{
			$firstarg=0;
			$tempsql.="where lang='$act_lang' ";
		}
		else
			$tempsql.="and lang='$act_lang' ";
	}
	$tempsql.="order by date desc";
	if(!$tempresult=mysql_query($tempsql,$db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	$entryfound=0;
	if($temprow=mysql_fetch_array($tempresult))
	{
		do{
			if($entryfound==1)
			{
				$nextentry=$temprow;
				$entryfound=2;
			}
			if($temprow["newsnr"]==$newsnr)
				$entryfound=1;
			if($entryfound<1)
				$preventry=$temprow;
		}while($temprow=mysql_fetch_array($tempresult));
	}
}
if(($enablerating==1) && bittst($ratingdisplay,BIT_1))
{
	if(!isset($dorating))
	{
		echo "<form method=\"post\" action=\"$act_script_url\">";
		echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
		echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
		echo "<input type=\"hidden\" name=\"category\" value=\"category\">";
		echo "<input type=\"hidden\" name=\"newsnr\" value=\"$newsnr\">";
		echo "<input type=\"hidden\" name=\"dorating\" value=\"1\">";
		if(isset($srcscript))
			echo "<input type=\"hidden\" name=\"srcscript\" value=\"".do_htmlentities($srcscript)."\">";
		if(isset($backurl))
			echo "<input type=\"hidden\" name=\"backurl\" value=\"".do_htmlentities($backurl)."\">";
		echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"left\" colspan=\"2\">";
		include("./includes/ratingbox.inc");
		echo "&nbsp;";
		echo "<input type=\"submit\" name=\"submit\" value=\"$l_ok\" class=\"snewsbutton\">";
		echo "</font></td></tr></form>";
	}
	else
	{
		$ratingsql="update ".$tableprefix."_data set ratingcount=ratingcount+1, ratings=ratings+$ratingvalue where newsnr=$newsnr";
		if(!$ratingresult=mysql_query($ratingsql,$db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		echo "<tr bgcolor=\"$contentbgcolor\"><td align=\"center\" colspan=\"2\">";
		echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo $l_ratingadded;
		echo "</font></td></tr>";
	}
}
?>
<tr bgcolor="<?php echo $headingbgcolor?>"><td align="center" colspan="2">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php
if($preventry)
{
	echo "<a class=\"pagenav\" href=\"$act_script_url?$langvar=$act_lang&amp;layout=$layout&amp;category=$category&amp;newsnr=".$preventry["newsnr"];
	if($backurl)
		echo "&amp;backurl=".urlencode($backurl);
	echo "\">";
	if($pagepic_back)
		echo "<img src=\"$url_gfx/$pagepic_back\" border=\"0\" align=\"absmiddle\" title=\"$l_back\" alt=\"$l_back\">";
	else
		echo "<b>[&lt;]</b>";
	echo "</a>&nbsp;&nbsp;";

}
if(($sn_hideallnewslink==0) || isset($backtxt))
{
	if($backurl)
		$listlink=$backurl;
	else
	{
		if($sncatlink=="source script")
			$listlink="news.php?$langvar=$act_lang&amp;layout=$layout&amp;category=$category";
		else
			$listlink="$sncatlink?$langvar=$act_lang&amp;layout=$layout&amp;category=$category";
	}
	if(isset($backtxt))
		$displaytxt=$backtxt;
	else
		$displaytxt=$l_allnews;
	echo "<a class=\"actionlink\" href=\"$listlink\">$displaytxt</a>";
}
if($nextentry)
{
	echo "&nbsp;&nbsp;<a class=\"pagenav\" href=\"$act_script_url?$langvar=$act_lang&amp;layout=$layout&amp;category=$category&amp;newsnr=".$nextentry["newsnr"];
	if($backurl)
		echo "&amp;backurl=".urlencode($backurl);
	echo "\">";
	if($pagepic_next)
		echo "<img src=\"$url_gfx/$pagepic_next\" border=\"0\" align=\"absmiddle\" title=\"$l_more\" alt=\"$l_more\">";
	else
		echo "<b>[&gt;]</b>";
	echo "</a>";

}
echo "</font></td></tr>";
echo "</table></td></tr></table></div>";
include ("./includes/footer.inc");
?>