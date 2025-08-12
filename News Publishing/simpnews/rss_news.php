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
require_once('./includes/rss_get_settings.inc');
require_once('./includes/rss_functions.inc');
if($rss_enable==0)
	die("disabled");
if(isset($limitdays))
	$maxage=$limitdays;
if(isset($enddate))
	$maxage=0;
if(isset($maxentries))
	$limitentries=$maxentries;
if(!isset($limitentries) && ($rss_maxentries>0))
	$limitentries=$rss_maxentries;
setlocale(LC_TIME, $def_locales[$act_lang]);
$actdate = date("Y-m-d 23:59:59");
if(!isset($start))
	$start=0;
if(!isset($sortorder))
	$sortorder=0;
$numentries=0;
$rss_data="<?xml version=\"1.0\" encoding=\"$contentcharset\" ?>".$crlf;
$rss_data.="<rss version=\"0.91\">".$crlf;
$rss_data.="<channel>".$crlf;
if($category>0)
{
	$sql = "select * from ".$tableprefix."_categories where catnr='$category'";
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if($myrow=mysql_fetch_array($result))
	{
		if($myrow["rss_channel_title"])
			$rss_channel['title']=$myrow["rss_channel_title"];
		if($myrow["rss_channel_description"])
			$rss_channel['description']=$myrow["rss_channel_description"];
		if($myrow["rss_channel_link"])
			$rss_channel['link']=$myrow["rss_channel_link"];
		if($myrow["rss_channel_copyright"])
			$rss_channel['copyright']=$myrow["rss_channel_copyright"];
		if($myrow["rss_channel_editor"])
			$rss_channel['editor']=$myrow["rss_channel_editor"];
	}
}
$rss_data.="<title>".$rss_channel['title']."</title>".$crlf;
$rss_data.="<language>".$act_lang."</language>".$crlf;
$rss_data.="<description>".$rss_channel['description']."</description>".$crlf;
$rss_data.="<link>".$rss_channel['link']."</link>".$crlf;
if($rss_channel['copyright'])
	$rss_data.="<copyright>".$rss_channel['copyright']."</copyright>".$crlf;
if($rss_channel['editor'])
	$rss_data.="<managingEditor>".$rss_channel['editor']."</managingEditor>".$crlf;
if($rss_channel['webmaster'])
	$rss_data.="<webMaster>".$rss_channel['webmaster']."</webMaster>".$crlf;
$changetime=0;
$sql = "select max(date) as maxdate from ".$tableprefix."_data where rss_nopublish=0 ";
if($category>=0)
	$sql.="and category='$category' ";
else
{
	$sql.="and linknewsnr=0 ";
	$tmpsql="select * from ".$tableprefix."_categories where hideintotallist=1";
	if(!$tmpresult = mysql_query($tmpsql, $db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	while($tmprow=mysql_fetch_array($tmpresult))
		$sql.="and category!=".$tmprow["catnr"]." ";
}
if($separatebylang==1)
	$sql.="and lang='$act_lang' ";
if($maxage>0)
	$sql.= "and date >= date_sub('$actdate', INTERVAL $maxage DAY) ";
$sql.="and date<='$actdate ' ";
if(!$result = mysql_query($sql, $db))
	die("Unable to connect to database.".mysql_error());
if($myrow=mysql_fetch_array($result))
{
	if($myrow["maxdate"])
	{
		list($mydate,$mytime)=explode(" ",$myrow["maxdate"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		$temptime=mktime($hour,$min,$sec,$month,$day,$year);
		$temptime=transposetimegmt($temptime,$servertimezone);
		if($temptime>$changetime)
			$changetime=$temptime;
	}
}
if($changetime<1)
	$changetime=transposetimegmt(time(),$servertimezone);
$rss_data.="<pubDate>".date("D, d M Y H:i:s \G\M\T",$changetime)."</pubDate>".$crlf;
$rss_data.="<lastBuildDate>".date("D, d M Y H:i:s \G\M\T",$changetime)."</lastBuildDate>".$crlf;
$baseurl=$simpnews_fullurl."singlenews.php?$langvar=$act_lang&layout=$layout&category=$category";
$sql = "select * from ".$tableprefix."_data where rss_nopublish=0 ";
if($category>=0)
	$sql.="and category='$category' ";
else
{
	$sql.="and linknewsnr=0 ";
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
if(isset($limitentries))
	$sql.=" limit $limitentries";
if(!$result = mysql_query($sql, $db))
	die("Unable to connect to database.".mysql_error());
if($myrow=mysql_fetch_array($result))
{
	do{
		if($myrow["linknewsnr"]==0)
			$entrydata=$myrow;
		else
		{
			$tmpsql="select * from ".$tableprefix."_data where newsnr=".$myrow["linknewsnr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("Unable to connect to database.".mysql_error());
			if(!$tmprow=mysql_fetch_array($tmpresult))
				die("DB error: no news entry for link (".$myrow["linknewsnr"].")");
			$entrydata=$tmprow;
		}
		if($entrydata["rss_nopublish"]==0)
		{
			$numentries++;
			$rss_data.="<item>".$crlf;
			if($entrydata["rss_short"])
				$text=stripslashes($entrydata["rss_short"]);
			else
			{
				$text=stripslashes($entrydata["text"]);
				$text=undo_htmlentities($text);
				$text=strip_tags($text);
				$text=substr($text,0,$rss_auto['short']);
			}
			if($entrydata["heading"])
			{
				$heading=stripslashes($entrydata["heading"]);
				$heading=undo_htmlentities($heading);
				$heading=strip_tags($heading);
			}
			else
				$heading=substr($text,0,$rss_auto['title']);
			$heading=do_htmlspecialchars($heading);
			$text=do_htmlspecialchars($text);
			$rss_data.="<title>".$heading."</title>".$crlf;
			$rss_data.="<description>".$text."</description>".$crlf;
			$rss_data.="<link>".do_htmlspecialchars($baseurl."&newsnr=".$entrydata["newsnr"])."</link>".$crlf;
			$rss_data.="</item>".$crlf;
		}
	}while($myrow=mysql_fetch_array($result));
}
$rss_data.="</channel>".$crlf;
$rss_data.="</rss>".$crlf;
conditionalGet($changetime);
header('Content-Type: text/xml');
header("Content-length: " . strlen($rss_data) . "\n");
print($rss_data);
exit;
?>