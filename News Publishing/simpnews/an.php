<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$path_simpnews=dirname(__FILE__);
require_once($path_simpnews.'/config.php');
require_once($path_simpnews.'/functions.php');
if(!isset($category))
	$category=0;
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
include($path_simpnews.'/language/lang_'.$act_lang.'.php');
include($path_simpnews.'/includes/get_settings.inc');
include($path_simpnews.'/includes/styles2.inc');
if(isset($limitentries))
	$numhotnews=$limitentries;
if(!isset($maxannounce))
	$maxannounce=0;
if($hn_linklayout)
	$layout=$hn_linklayout;
?>
<table width="<?php echo $TableWidth2?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tblalign?>" class="sntable">
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
if((strlen($heading)>0) && ($hotscriptsnoheading==0))
{
?>
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><b><?php echo $heading?></b></font></td></tr>
<?php
}
$sql = "select * from ".$tableprefix."_misc";
if(!$result = mysql_query($sql, $db)) {
    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
{
	if($myrow["shutdown"]==1)
	{
?>
</tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr BGCOLOR="<?php echo $contentbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php
		$shutdowntext=stripslashes($myrow["shutdowntext"]);
		$shutdowntext = undo_htmlspecialchars($shutdowntext);
		echo $shutdowntext;
		echo "</font></td></tr></table></td></tr></table>";
?>
</td></tr></table>
<?php
		include($path_simpnews.'/includes/footer2.inc');
		echo "</body></html>";
		exit;
	}
}
$acttime=transposetime(time(),$servertimezone,$displaytimezone);
$tmpsql = "select * from ".$tableprefix."_announce where (expiredate>=$acttime or expiredate=0)  and (firstdate<=$acttime or firstdate=0)";
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
	$tmpsql.= " limit $maxannounce";
else
	$tmpsql .=" limit $numhotnews";
if(!$tmpresult = mysql_query($tmpsql, $db))
    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
if(mysql_num_rows($tmpresult)>0)
{
	while($tmprow=mysql_fetch_array($tmpresult))
	{
		echo "<tr>";
		if($hotnewsicons==1)
		{
			echo "<td width=\"2%\" height=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\">";
			if($tmprow["headingicon"])
				echo "<img src=\"$url_icons/".$tmprow["headingicon"]."\" border=\"0\" align=\"middle\"> ";
			echo "</td>";
		}
		echo "<td align=\"center\"><table class=\"newsbox\" width=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\" cellspacing=\"0\" cellpadding=\"0\">";
		list($mydate,$mytime)=explode(" ",$tmprow["date"]);
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
		echo "<tr><td align=\"left\" bgcolor=\"$timestampbgcolor\">";
		echo "<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
		if($tmprow["category"]==0)
			echo "<img style=\"margin: 1px\" src=\"$url_gfx/$gannouncepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_global_announcement\" title=\"$l_global_announcement\"> ";
		else
			echo "<img style=\"margin: 1px\" src=\"$url_gfx/$announcepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_announcement\" title=\"$l_announcement\"> ";
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

				if($usehnlinkdest==1)
					$linkdest="$hnlinkdestan?$langvar=$act_lang&layout=$layout&category=$category&announcenr=".$tmprow["entrynr"];
				else
					$linkdest=$url_simpnews."/announce.php?$langvar=$act_lang&layout=$layout&category=$category&announcenr=".$tmprow["entrynr"];
				echo " <a href=\"$linkdest\"";
				if($hotnewstarget)
					echo " target=\"$hotnewstarget\"";
				echo ">";
			}
			$displayheading=undo_html_ampersand($tmprow["heading"]);
			if($hotnewsnohtmlformatting==1)
				$displayheading=strip_tags($displayheading);
			$displayheading=display_encoded($displayheading);
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
			$displaytext = str_replace("<BR>"," ",$displaytext);
			if(($hotnewsmaxchars>0) && strlen($displaytext)>$hotnewsmaxchars)
			{
				$displaytext=undo_htmlentities($displaytext);
				$displaytext=strip_tags($displaytext);
				$displaytext=substr($displaytext,0,$hotnewsmaxchars);
				$displaytext=display_encoded($displaytext);
				if($usehnlinkdest==1)
					$linkdest="$hnlinkdestan?$langvar=$act_lang&layout=$layout&category=$category&announcenr=".$tmprow["entrynr"];
				else
					$linkdest=$url_simpnews."/announce.php?$langvar=$act_lang&layout=$layout&category=$category&announcenr=".$tmprow["entrynr"];
				$displaytext.=" <a href=\"$linkdest\"";
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
else
{
		echo "<tr>";
		echo "<td align=\"center\" bgcolor=\"$contentbgcolor\">";
		echo $l_noannouncements;
		echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo "</font></td></tr>";
}
echo "</table></td></tr></table>";
include($path_simpnews.'/includes/footer2.inc');
?>