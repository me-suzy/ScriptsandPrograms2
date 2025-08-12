<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$path_simpnews=dirname(__FILE__);
require_once($path_simpnews.'/config.php');
require_once($path_simpnews.'/functions.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
include($path_simpnews.'/language/lang_'.$act_lang.'.php');
include($path_simpnews.'/includes/get_settings.inc');
if($rss_enable==0)
	die("disabled");
setlocale(LC_TIME, $def_locales[$act_lang]);
?>
<table width="<?php echo $TableWidth2?>" border="0" CELLPADDING="1" CELLSPACING="0" class="sntable" align="<?php echo $tblalign?>">
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
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
$sql = "select * from ".$tableprefix."_rss_catlist where layoutid='$layout' order by displaypos asc";
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
include($path_simpnews."/includes/footer2.inc");
?>
