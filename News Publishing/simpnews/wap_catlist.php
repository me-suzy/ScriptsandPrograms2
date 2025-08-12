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
if($wap_enable==0)
	die("disabled");
setlocale(LC_TIME, $def_locales[$act_lang]);
$actdate = date("Y-m-d H:i:00");
$wap_data="<?xml version=\"1.0\" encoding=\"$contentcharset\" ?>".$crlf;
$wap_data.="<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/DTD/wml_1.1.xml\">".$crlf;
$wap_data.="<wml>".$crlf;
if(!isset($mode))
	$mode="news";
if(!isset($start))
	$start=0;
if($mode=="news")
{
	$modetext=$l_news;
	$baseurl="wap_short_news.php";
}
if($mode=="events")
{
	$modetext=$l_events;
	$baseurl="wap_short_events.php";
}
if($mode=="events2")
{
	$modetext=$l_events;
	$baseurl="wap_short_events2.php";
}
if($mode=="announce")
{
	$modetext=$l_announcements;
	$baseurl="wap_short_announce.php";
}
if($mode=="evsearch")
{
	$modetext=$l_search_events;
	$baseurl="wap_ev_search.php";
}
$baseurl.="?$langvar=$act_lang&amp;layout=$layout&amp;backscript=wap_catlist";
$navurl=$act_script_url."?$langvar=$act_lang&amp;layout=$layout";
$wap_data.="<card>".$crlf;
if($wap_cl_logo)
{
	$imagesize = GetImageSize ($path_gfx."/".$wap_cl_logo);
	$wap_data.="<p><img src=\"".$url_gfx."/".$wap_cl_logo."\" width=\"".$imagesize[0]."\" height=\"".$imagesize[1]."\" /> ".$crlf;
}
else
	$wap_data.="<p>";
if($wap_cl_title)
	$wap_data.=wml_encode($wap_cl_title);
else
	$wap_data.=wml_encode($l_catlist);
if(!bittst($wap_options,BIT_11))
	$wap_data.="<br />".wml_encode($l_mode).": ".wml_encode($modetext);
$wap_data.="</p>".$crlf;
if($start==0)
{
	if($wap_cl_description)
		$wap_data.="<p>".wml_encode($wap_cl_description)."</p>".$crlf;
	if($wap_copyright)
		$wap_data.="<p>".wml_encode($wap_copyright)."</p>".$crlf;
}
if(!bittst($wap_options,BIT_6))
	$sql="select * from ".$tableprefix."_categories where hideincatlist=0 and hideintotallist=0 order by displaypos asc";
else
	$sql="select cat.* from ".$tableprefix."_categories cat, ".$tableprefix."_wap_catlist wc where cat.catnr=wc.catnr and wc.layoutid='$layout' order by cat.displaypos asc";
if(!$result = mysql_query($sql, $db))
    die("Unable to connect to database.".mysql_error());
$numentries=mysql_num_rows($result);
if($wap_catlist_epp>0)
{
	$currentpage=ceil(($start/$wap_catlist_epp)+1);
	$lastpage=ceil($numentries/$wap_catlist_epp);
	if(!bittst($wap_options,BIT_12))
		$wap_data.="<p>".wml_encode($l_page)." ".$currentpage."/".$lastpage."</p>".$crlf;
}
$wap_data.="<p><br/></p>".$crlf;
if($start==0)
{
	if(!bittst($wap_options,BIT_6))
	{
		$numentries++;
		$wap_data.="<p><a href=\"".$baseurl."&amp;category=0\">".wml_encode($l_general)."</a></p>".$crlf;
	}
	else
	{
		$tmpsql="select * from ".$tableprefix."_wap_catlist where catnr=0 and layoutid='$layout'";
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("Unable to connect to database.".mysql_error());
		if(mysql_num_rows($tmpresult)>0)
		{
			$numentries++;
			$wap_data.="<p><a href=\"".$baseurl."&amp;category=0\">".wml_encode($l_general)."</a></p>".$crlf;
		}
	}
}
if($numentries<1)
{
	$wap_data="<?xml version=\"1.0\" encoding=\"$contentcharset\" ?>".$crlf;
	$wap_data.="<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/DTD/wml_1.1.xml\">".$crlf;
	$wap_data.="<wml>".$crlf;
	$wap_data.="<card>".$crlf;
	$wap_data.="<p>".wml_encode($l_catlist)." - ".wml_encode($modetext)."</p>".$crlf;
	$wap_data.="<p><br/></p>";
	$wap_data.="<p>".wml_encode($l_noentries)."</p>".$crlf;
	$wap_data.="</card>".$crlf;
	$wap_data.="</wml>".$crlf;
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-cache, must-revalidate");
	header("Cache-Control: post-check=0,pre-check=0");
	header("Cache-Control: max-age=0");
	header("Pragma: no-cache");
	header('Content-Type: text/vnd.wap.wml');
	header("Content-length: " . strlen($wap_data) . "\n");
	print($wap_data);
	exit;
}
if($wap_catlist_epp>0)
	$sql.=" limit $start,$wap_catlist_epp";
if(!$result = mysql_query($sql, $db))
    die("Unable to connect to database.".mysql_error());
while($myrow=mysql_fetch_array($result))
{
	$cattext=undo_htmlentities(stripslashes($myrow["catname"]));
	$tmpsql="select * from ".$tableprefix."_catnames where catnr=".$myrow["catnr"]." and lang='".$act_lang."'";
	if(!$tmpresult=mysql_query($tmpsql,$db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if($tmprow=mysql_fetch_array($tmpresult))
	{
		if(strlen($tmprow["catname"])>0)
			$cattext=undo_htmlentities(stripslashes($tmprow["catname"]));
	}
	$wap_data.="<p><a href=\"".$baseurl."&amp;category=".$myrow["catnr"]."\">";
	$wap_data.=wml_encode($cattext)."</a></p>".$crlf;
}
if($wap_catlist_epp>0)
{
	if($lastpage>1)
		$wap_data.="<p><br /></p>".$crlf;
	if($currentpage>1)
		$wap_data.="<p><a href=\"".$navurl."&amp;start=".($start-$wap_catlist_epp)."\">".wml_encode($l_prevpage)."</a></p>".$crlf;
	if($currentpage < $lastpage)
		$wap_data.="<p><a href=\"".$navurl."&amp;start=".($start+$wap_catlist_epp)."\">".wml_encode($l_nextpage)."</a></p>".$crlf;
}
$wap_data.="</card>".$crlf;
$wap_data.="</wml>".$crlf;
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache");
header("Cache-Control: post-check=0,pre-check=0");
header("Cache-Control: max-age=0");
header("Pragma: no-cache");
header('Content-Type: text/vnd.wap.wml');
header("Content-length: " . strlen($wap_data) . "\n");
print($wap_data);
exit;
?>