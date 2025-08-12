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
if(!isset($start))
	$start=0;
$baseparams="?$langvar=$act_lang&amp;layout=$layout&amp;backscript=wap_catlist2";
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
$wap_data.="</p>".$crlf;
if($start==0)
{
	if($wap_cl_description)
		$wap_data.="<p>".wml_encode($wap_cl_description)."</p>".$crlf;
	if($wap_copyright)
		$wap_data.="<p>".wml_encode($wap_copyright)."</p>".$crlf;
}
$sql="select * from ".$tableprefix."_wap_catlist where layoutid='$layout' order by displaypos asc";
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
if($numentries<1)
{
	$wap_data="<?xml version=\"1.0\" encoding=\"$contentcharset\" ?>".$crlf;
	$wap_data.="<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/DTD/wml_1.1.xml\">".$crlf;
	$wap_data.="<wml>".$crlf;
	$wap_data.="<card>".$crlf;
	$wap_data.="<p>".wml_encode($l_catlist)."</p>".$crlf;
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
	if($myrow["catnr"]==0)
		$catname=$l_general;
	else
	{
		$tmpsql="select * from ".$tableprefix."_categories where catnr=".$myrow["catnr"];
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("Unable to connect to database.".mysql_error());
		if($tmprow=mysql_fetch_array($tmpresult))
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
	if(bittst($myrow["modes"],BIT_1))
		$wap_data.="<p><a href=\"wap_short_news.php".$baseparams."&amp;category=".$myrow["catnr"]."\">".wml_encode($catname)."</a></p>".$crlf;
	if(bittst($myrow["modes"],BIT_2))
		$wap_data.="<p><a href=\"wap_short_events.php".$baseparams."&amp;category=".$myrow["catnr"]."\">".wml_encode($catname)."</a></p>".$crlf;
	if(bittst($myrow["modes"],BIT_3))
		$wap_data.="<p><a href=\"wap_short_events2.php".$baseparams."&amp;category=".$myrow["catnr"]."\">".wml_encode($catname)."</a></p>".$crlf;
	if(bittst($myrow["modes"],BIT_4))
		$wap_data.="<p><a href=\"wap_short_announce.php".$baseparams."&amp;category=".$myrow["catnr"]."\">".wml_encode($catname)."</a></p>".$crlf;
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