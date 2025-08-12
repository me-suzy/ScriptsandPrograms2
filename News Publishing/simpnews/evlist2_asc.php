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
require_once('./includes/block_leacher.inc');
setlocale(LC_TIME, $def_locales[$act_lang]);
$data="";
if(isset($startdate))
	$news5startdate=$startdate;
if(isset($enddate))
	$news5enddate=$enddate;
if(isset($limitdays))
{
	$news5startdate=date("Y-m-d");
	$news5enddate= date("Y-m-d",time()+($limitdays*24*60*60));
}
if(bittst($announceoptions,BIT_2))
	include_once('./includes/an_disp.inc');
if(!isset($sortorder))
	$sortorder=0;
if(!isset($maxannounce))
	$maxannounce=0;
$sql = "select * from ".$tableprefix."_events where date >= '$news5startdate' and date <= '$news5enddate' ";
if($separatebylang==1)
	$sql.=" and lang='$act_lang'";
if($category>=0)
	$sql.= " and category='$category'";
else
{
	$sql.=" and linkeventnr=0";
	$tmpsql="select * from ".$tableprefix."_categories where hideintotallist=1";
	if(!$tmpresult = mysql_query($tmpsql, $db))
	    die("Unable to connect to database.".mysql_error());
	while($tmprow=mysql_fetch_array($tmpresult))
		$sql.=" and category!=".$tmprow["catnr"];
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
$currentyear=0;
$currentmonth=0;
if($myrow=mysql_fetch_array($result))
{
	do
	{
		if($myrow["linkeventnr"]==0)
			$entrydata=$myrow;
		else
		{
			$tmpsql="select * from ".$tableprefix."_events where eventnr=".$myrow["linkeventnr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			if(!$tmprow=mysql_fetch_array($tmpresult))
				die("<tr class=\"errorrow\"><td>Unable to get data");
			$entrydata=$tmprow;
		}
		list($tempdate, $temptime) = explode(" ", $entrydata["date"]);
		list($year, $month, $day) = explode("-", $tempdate);
		if($month>0)
		{
			$displaytime=mktime(0,0,0,$month,$day,$year);
			$displaydate=strftime($news5dateformat,$displaytime);
		}
		else
			$displaydate="";
		if($currentyear!=$year)
		{
			$currentyear=$year;
			$data.=$year.$crlf;
		}
		if($currentmonth!=$month)
		{
			$data.=$l_monthname[$month-1];
			if($news5monthdisplayyear==1)
				$data.=" ".$year;
			$data.=$crlf;
			if(bittst($announceoptions,BIT_1) && bittst($announceoptions,BIT_7))
				$data.=get_announcements2($year,$month,$category, $act_lang, $layou, $maxannounce);
		}
		$data.=$displaydate."\t";
		if(strlen($entrydata["heading"])>0)
		{
			$displayheading=undo_htmlentities(stripslashes($entrydata["heading"]));
			$displayheading=strip_tags($displayheading);
			$data.=$displayheading."\t";
		}
		else
			$data.="\t\t";
		$displaytext=undo_htmlentities(stripslashes($entrydata["text"]));
		$displaytext=strip_tags($displaytext);
		$data.=$displaytext."\t";
		if($news5displayposter && (strlen($entrydata["poster"])>0))
		{
			$displayposter=undo_htmlentities(stripslashes($entrydata["poster"]));
			$displayposter=strip_tags($displayposter);
			$data.=undo_htmlentities($l_poster).": ".$displayposter;
		}
		$data.=$crlf;
	}while($myrow=mysql_fetch_array($result));
}
header('Content-Type: application/octetstream');
header("Content-Transfer-Encoding: binary\n");
header('Content-Disposition: filename="events.txt"');
header("Content-length: " . strlen($data) . "\n");
print($data);
?>