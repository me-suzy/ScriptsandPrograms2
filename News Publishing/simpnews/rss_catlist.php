<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
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
require_once('./includes/wap_get_settings.inc');
if($rss_enable==0)
	die("disabled");
setlocale(LC_TIME, $def_locales[$act_lang]);
$actdate = date("Y-m-d H:i:00");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="SimpNews v<?php echo $version?>, <?php echo $copyright_asc?>">
<meta name="fid" content="<?php echo $fid?>">
<?php
$pageheading=$l_rss_newsfeeds;
if(file_exists("metadata.php"))
	include ("metadata.php");
else
{
?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
<title><?php echo $pageheading?></title>
<?php
}
if(is_ns4() && $ns4style)
	echo"<link rel=stylesheet href=\"$ns4style\" type=\"text/css\">\n";
else if(is_ns6() && $ns6style)
	echo"<link rel=stylesheet href=\"$ns6style\" type=\"text/css\">\n";
else if(is_opera() && $operastyle)
	echo"<link rel=stylesheet href=\"$operastyle\" type=\"text/css\">\n";
else if(is_konqueror() && $konquerorstyle)
	echo"<link rel=stylesheet href=\"$konquerorstyle\" type=\"text/css\">\n";
else if(is_gecko() && $geckostyle)
	echo"<link rel=stylesheet href=\"$geckostyle\" type=\"text/css\">\n";
else
	echo"<link rel=stylesheet href=\"$stylesheet\" type=\"text/css\">\n";
include_once('./includes/styles.inc');
if($rsspic)
	$colspan=2;
else
	$colspan=1;
?>
</head>
<body bgcolor="<?php echo $pagebgcolor?>" text="<?php echo $contentfontcolor?>" <?php echo $addbodytags?>>
<div align=<?php echo $tblalign?>">
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" class="sntable" align="<?php echo $tblalign?>">
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $headingbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" COLSPAN="<?php echo $colspan?>"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><b><?php echo $l_rss_newsfeeds?></b></font></td></tr>
<?php
$sql="select * from ".$tableprefix."_texts where textid='rsscl_pre' and lang='$act_lang'";
if(!$result = mysql_query($sql, $db))
    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
if($myrow=mysql_fetch_array($result))
{
	echo "<tr bgcolor=\"$contentbgcolor\"><td colspan=\"$colspan\" align=\"left\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo undo_htmlspecialchars(stripslashes($myrow["text"]));
	echo "</font></td></tr>";
}
$sql="select * from ".$tableprefix."_rss_catlist where layoutid='$layout' order by displaypos asc";
if(!$result = mysql_query($sql, $db))
    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
while($myrow=mysql_fetch_array($result))
{
	if($myrow["catnr"]==0)
		$catname=$l_general;
	else
	{
		$catsql="select * from ".$tableprefix."_categories where catnr=".$myrow["catnr"];
		if(!$catresult = mysql_query($catsql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if($catrow=mysql_fetch_array($catresult))
		{
			$catname=undo_htmlentities(stripslashes($catrow["catname"]));
			$tmpsql2="select * from ".$tableprefix."_catnames where catnr=".$catrow["catnr"]." and lang='".$act_lang."'";
			if(!$tmpresult2=mysql_query($tmpsql2,$db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			if($tmprow2=mysql_fetch_array($tmpresult2))
			{
				if(strlen($tmprow2["catname"])>0)
					$cattext=undo_htmlentities(stripslashes($tmprow2["catname"]));
			}
		}
		else
			$catname=$l_unknown;
	}
	echo "<tr bgcolor=\"$contentbgcolor\">";
	if($rsspic)
	{
		echo "<td width=\"5%\" align=\"center\">";
		echo "<a class=\"catlistlink\" href=\"".$simpnews_fullurl."rss_news.php?$langvar=$act_lang&layout=$layout&category=".$myrow["catnr"]."\" target=\"rssfeed\">";
		echo "<img src=\"".$url_gfx."/".$rsspic."\" border=\"0\"></a></td>";
	}
	echo "<td align=\"left\">&nbsp;<a class=\"catlistlink\" href=\"".$simpnews_fullurl."rss_news.php?$langvar=$act_lang&layout=$layout&category=".$myrow["catnr"]."\" target=\"rssfeed\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo $catname."</font></a></td></tr>";
}
echo "</table></td></tr></table>";
echo "</div>\n";
include ("./includes/footer.inc");
?>
