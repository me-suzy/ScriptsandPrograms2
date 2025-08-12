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
if(!isset($eventnr))
	die("calling error");
setlocale(LC_TIME, $def_locales[$act_lang]);
$actdate = date("Y-m-d H:i:00");
$wap_data="<?xml version=\"1.0\" encoding=\"$contentcharset\" ?>".$crlf;
$wap_data.="<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/DTD/wml_1.1.xml\">".$crlf;
$wap_data.="<wml>".$crlf;
$wap_data.="<card>".$crlf;
$wap_data.="<p>".wml_encode($l_events)."</p>".$crlf;
$sql="select * from ".$tableprefix."_events where eventnr=$eventnr";
if(!$result = mysql_query($sql, $db))
	die("Unable to connect to database.".mysql_error());
if(!$myrow=mysql_fetch_array($result))
	die("No such event ($eventnr)");
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
if($displaydate)
	$wap_data.="<p>".$displaydate.":</p>".$crlf;
$wap_data.="<p>".$heading."</p>".$crlf;
$wap_data.="<p>".$text."</p>".$crlf;
$wap_data.="<p><br /></p>".$crlf;
$backurl="wap_short_events2.php?$langvar=$act_lang&amp;category=$category&amp;layout=$layout&amp;sortorder=$sortorder";
if(isset($backscript))
	$backurl.="&amp;backscript=$backscript";
if(isset($limitentries))
	$backurl.="&amp;limitentries=$limitentries";
if(isset($limitdays))
	$backurl.="&amp;limitdays=$limitdays";
$wap_data.="<p><a href=\"".$backurl."\">".wml_encode($l_back)."</a></p>".$crlf;
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
?>