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
if($wap_evlist2_epp<1)
	$wap_evlist2_epp=1;
if(!isset($backscript))
	$backscript="wap_catlist";
if(isset($limitdays))
	$maxage=$limitdays;
else
	$maxage=$wap_ev_maxdays;
setlocale(LC_TIME, $def_locales[$act_lang]);
$actdate = date("Y-m-d H:i:00");
if(!isset($start))
	$start=-1;
if(!isset($sortorder))
	$sortorder=0;
if(isset($maxentries))
	$limitentries=$maxentries;
if(!isset($limitentries))
{
	if($wap_ev_maxentries>0)
		$limitentries=$wap_ev_maxentries;
}
if(!isset($mode))
{
	if(bittst($wap_options,BIT_2))
		$mode="list";
	else
		$mode="today";
}
$numentries=0;
$baseparams="$langvar=$act_lang&amp;category=$category&amp;layout=$layout&amp;sortorder=$sortorder&amp;backscript=$backscript";
if(isset($limitentries))
	$baseparams.="&amp;limitentries=$limitentries";
if(isset($limitdays))
	$baseparams.="&amp;limitdays=$limitdays";
$baseurl=$act_script_url."?".$baseparams;
$wap_data="<?xml version=\"1.0\" encoding=\"$contentcharset\" ?>".$crlf;
$wap_data.="<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/DTD/wml_1.1.xml\">".$crlf;
$wap_data.="<wml>".$crlf;
$catname="";
if($category>0)
{
	$sql = "select * from ".$tableprefix."_categories where catnr='$category'";
	if(!$result = mysql_query($sql, $db))
	    die("Unable to connect to database.".mysql_error());
	if($myrow=mysql_fetch_array($result))
	{
		$catname=undo_htmlentities(stripslashes($myrow["catname"]));
		$tmpsql="select * from ".$tableprefix."_catnames where catnr=".$myrow["catnr"]." and lang='".$act_lang."'";
		if(!$tmpresult=mysql_query($tmpsql,$db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if($tmprow=mysql_fetch_array($tmpresult))
		{
			if(strlen($tmprow["catname"])>0)
				$catname=undo_htmlentities(stripslashes($tmprow["catname"]));
		}
	}
}
else if($category==0)
	$catname=$l_general;
if($start<0)
{
	$totalentries=0;
	$wap_data.="<card>".$crlf;
	$wap_data.="<p>".wml_encode($wap_ev_title)."</p>".$crlf;
	$wap_data.="<p>".wml_encode($wap_ev_description)."</p>".$crlf;
	if($catname)
		$wap_data.="<p>".wml_encode("$l_category: ".$catname)."</p>".$crlf;
	if($wap_copyright)
		$wap_data.="<p>".wml_encode($wap_copyright)."</p>".$crlf;
	$changetime=0;
	$sql = "select max(added) as maxdate, count(*) as totalentries from ".$tableprefix."_events where wap_nopublish=0 ";
	if($category>=0)
		$sql.="and category='$category' ";
	else
	{
		$sql.="and linkeventnr=0 ";
		$tmpsql="select * from ".$tableprefix."_categories where hideintotallist=1";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("Unable to connect to database.".mysql_error());
		while($tmprow=mysql_fetch_array($tmpresult))
			$sql.="and category!=".$tmprow["catnr"]." ";
	}
	if($separatebylang==1)
		$sql.="and lang='$act_lang' ";
	if($mode=="today")
		$sql.="and DATE_FORMAT(date,'%Y-%m-%d')='".date("Y-m-d")."' ";
	else
	{
		$sql.="and DATE_FORMAT(date,'%Y-%m-%d')>='".date("Y-m-d")."' ";
		if($maxage>0)
		{
			$actdate = date("Y-m-d H:i:s");
			$sql.= "and DATE_FORMAT(date,'%Y-%m-%d') <= DATE_FORMAT(date_add('$actdate', INTERVAL $maxage DAY),'%Y-%m-%d') ";
		}
	}
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
		if($myrow["totalentries"])
		{
			$totalentries=$myrow["totalentries"];
			if(isset($limitentries) && ($totalentries>$limitentries))
				$totalentries=$limitentries;
		}
	}
	if($changetime<1)
		$changetime=transposetimegmt(time(),$servertimezone);
	if($mode=="today")
		$wap_data.="<p>".wml_encode($l_events_for_today)."</p>".$crlf;
	else if($maxage>0)
		$wap_data.="<p>".wml_encode(sprintf($l_events_for_days,$maxage))."</p>".$crlf;
	if(($totalentries>0) && bittst($wap_options,BIT_3))
		$wap_data.="<p>".wml_encode(undo_htmlentities($l_numentries)).": ".$totalentries."</p>".$crlf;
	if(bittst($wap_options,BIT_9))
		$wap_data.="<p>".wml_encode($l_lastupdated.": ".date("D, d M Y H:i:s \G\M\T",$changetime))."</p>".$crlf;
	if($totalentries>0)
		$wap_data.="<p>".$crlf."<a href=\"".$baseurl."&amp;start=0\">".wml_encode($l_goto_events)."</a>".$crlf."</p>".$crlf;
	$searchlink="wap_ev_search.php?$langvar=$act_lang&amp;layout=$layout&amp;backscript=$backscript";
	$wap_data.="<p>".$crlf."<a href=\"$searchlink\">".wml_encode($l_search_events)."</a>".$crlf."</p>".$crlf;
	if(bittst($wap_options,BIT_10))
	{
		$wap_data.="<p><br /></p>".$crlf;
		$wap_data.="<p><a href=\"".$backscript.".php?mode=events2&amp;$langvar=$act_lang&amp;layout=$layout&amp;start=0\">";
		$wap_data.=wml_encode($l_listofcats);
		$wap_data.="</a></p>".$crlf;
	}
	$wap_data.="</card>".$crlf;
}
else
{
	$restarttext=wml_encode($l_gotostart);
	$restarturl=$baseurl."&amp;start=-1";
	$sql = "select * from ".$tableprefix."_events where wap_nopublish=0 ";
	if($category>=0)
		$sql.="and category='$category' ";
	else
	{
		$sql.="and linkeventnr=0 ";
		$tmpsql="select * from ".$tableprefix."_categories where hideintotallist=1";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		while($tmprow=mysql_fetch_array($tmpresult))
			$sql.="and category!=".$tmprow["catnr"]." ";
	}
	if($separatebylang==1)
		$sql.="and lang='$act_lang' ";
	if($mode=="today")
		$sql.="and DATE_FORMAT(date,'%Y-%m-%d')=DATE_FORMAT('".date("Y-m-d")."','%Y-%m-%d') ";
	else
	{
		$sql.="and DATE_FORMAT(date,'%Y-%m-%d') >= DATE_FORMAT('".date("Y-m-d")."','%Y-%m-%d') ";
		if($maxage>0)
		{
			$actdate = date("Y-m-d H:i:s");
			$sql.= "and DATE_FORMAT(date,'%Y-%m-%d') <= DATE_FORMAT(date_add('$actdate', INTERVAL $maxage DAY),'%Y-%m-%d') ";
		}
	}
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
		die("Unable to connect to database.".mysql_error());
	$wap_data.="<card>".$crlf;
	$numentries=mysql_num_rows($result);
	$wap_data.="<p>".wml_encode($l_events);
	$currentpage=ceil(($start/$wap_evlist2_epp)+1);
	$lastpage=ceil($numentries/$wap_evlist2_epp);
	if(!bittst($wap_options,BIT_12))
		$wap_data.=" - ".wml_encode($l_page)." ".$currentpage."/".$lastpage;
	$wap_data.="</p>".$crlf;
	if($wap_catlist_epp>0)
		$sql.=" limit $start,$wap_evlist2_epp";
	if(!$result = mysql_query($sql, $db))
		die("Unable to connect to database.".mysql_error());
	if($myrow=mysql_fetch_array($result))
	{
		do{
			if($myrow["linkeventnr"]==0)
				$entrydata=$myrow;
			else
			{
				$tmpsql="select * from ".$tableprefix."_events where eventnr=".$myrow["linkeventnr"];
				if(!$tmpresult = mysql_query($tmpsql, $db))
					die("Unable to connect to database.".mysql_error());
				if(!$tmprow=mysql_fetch_array($tmpresult))
					die("DB error: no news entry for link (".$myrow["linkeventnr"].")");
				$entrydata=$tmprow;
			}
			if($entrydata["wap_short"])
				$text=stripslashes($entrydata["wap_short"]);
			else
			{
				$text=stripslashes($entrydata["text"]);
				$text=undo_htmlentities($text);
				$text=strip_tags($text);
				$text=substr($text,0,$wap_auto['short']);
			}
			if($entrydata["heading"])
			{
				$heading=stripslashes($entrydata["heading"]);
				$heading=undo_htmlentities($heading);
				$heading=strip_tags($heading);
			}
			else
				$heading=substr($text,0,$wap_auto['title']);
			list($mydate,$mytime)=explode(" ",$entrydata["date"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			if($month>0)
			{
				$temptime=mktime($hour,$min,$sec,$month,$day,$year);
				$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
				if(($hour>0) || ($min>0))
					$displaydate=date($event_dateformat2,mktime($hour,$min,0,$month,$day,$year));
				else
					$displaydate=date($event_dateformat,mktime(0,0,0,$month,$day,$year));
			}
			else
				$displaydate="";
			$heading=wml_encode($heading);
			$text=wml_encode($text);
			$wap_data.="<p>";
			if($displaydate)
				$wap_data.=$displaydate.": ";
			$wap_data.="<a href=\"wap_event.php?".$baseparams."&amp;eventnr=".$entrydata["eventnr"]."&amp;start=".$start."\">";
			$wap_data.=$heading."</a></p>".$crlf;
		}while($myrow=mysql_fetch_array($result));
		$wap_data.="<p><br /></p>".$crlf;
		if($currentpage>1)
			$wap_data.="<p><a href=\"".$baseurl."&amp;start=".($start-$wap_evlist2_epp)."\">".wml_encode($l_prevpage)."</a></p>".$crlf;
		if($currentpage < $lastpage)
			$wap_data.="<p><a href=\"".$baseurl."&amp;start=".($start+$wap_evlist2_epp)."\">".wml_encode($l_nextpage)."</a></p>".$crlf;
		$wap_data.="<p><a href=\"".$restarturl."\">".$restarttext."</a></p>".$crlf;
	}
	else
	{
		$wap_data.="<p>".wml_encode(undo_htmlentities($l_noentries))."</p>".$crlf;
		$wap_data.="<p>".$crlf."<a href=\"".$restarturl."\">".$restarttext."</a>".$crlf."</p>".$crlf;
	}
	if(bittst($wap_options,BIT_10))
	{
		$wap_data.="<p><br /></p>".$crlf;
		$wap_data.="<p><a href=\"".$backscript.".php?mode=events2&amp;$langvar=$act_lang&amp;layout=$layout&amp;start=0\">";
		$wap_data.=wml_encode($l_listofcats);
		$wap_data.="</a></p>".$crlf;
	}
	$wap_data.="</card>".$crlf;
}
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
?>