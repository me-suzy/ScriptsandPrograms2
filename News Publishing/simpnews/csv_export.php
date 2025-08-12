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
$data="";
include_once('./language/lang_'.$act_lang.'.php');
require_once('./includes/get_settings.inc');
require_once('./includes/block_leacher.inc');
setlocale(LC_TIME, $def_locales[$act_lang]);
if(!isset($maxannounce))
	$maxannounce=0;
if($exporttype<1)
	die("$l_functiondisabled");
if(isset($startdate))
	$news5startdate=$startdate;
if(isset($enddate))
	$news5enddate=$enddate;
if(!isset($sortorder))
	$sortorder=0;
$sql = "select * from ".$tableprefix."_events where date >= '$news5startdate' and date <= '$news5enddate' ";
if($separatebylang==1)
	$sql.=" and lang='$act_lang'";
if($exporttype==1)
	$sql.=" and exposter=0";
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
$first=true;
if(bittst($csvexportfields,BIT_1))
{
	$data.="\"".undo_htmlentities($l_date)."\"";
	$first=false;
}
if(bittst($csvexportfields,BIT_2))
{
	if($first)
		$first=false;
	else
		$data.=",";
	$data.="\"".undo_htmlentities($l_language)."\"";
}
if(bittst($csvexportfields,BIT_3))
{
	if($first)
		$first=false;
	else
		$data.=",";
	$data.="\"".undo_htmlentities($l_category)."\"";
}
if(bittst($csvexportfields,BIT_4))
{
	if($first)
		$first=false;
	else
		$data.=",";
	$data.="\"".undo_htmlentities($l_heading)."\"";
}
if(bittst($csvexportfields,BIT_5))
{
	if($first)
		$first=false;
	else
		$data.=",";
	$data.="\"".undo_htmlentities($l_text)."\"";
}
if(bittst($csvexportfields,BIT_6))
{
	if($first)
		$first=false;
	else
		$data.=",";
	$data.="\"".undo_htmlentities($l_poster)."\"";
}
$data.=$crlf;
while($myrow=mysql_fetch_array($result))
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
	$first=true;
	list($tempdate, $temptime) = explode(" ", $myrow["date"]);
	list($year, $month, $day) = explode("-", $tempdate);
	if($month>0)
	{
		$displaytime=mktime(0,0,0,$month,$day,$year);
		$displaydate=strftime($csvexportdateformat,$displaytime);
	}
	else
		$displaydate="";
	if(bittst($csvexportfields,BIT_1))
	{
		$data.="$displaydate";
		$first=false;
	}
	if(bittst($csvexportfields,BIT_2))
	{
		if($first)
			$first=false;
		else
			$data.=",";
		$data.="\"".$entrydata["lang"]."\"";
	}
	if(bittst($csvexportfields,BIT_3))
	{
		if($myrow["category"]>0)
		{
			$tmpsql="select * from ".$tableprefix."_categories where catnr=".$myrow["category"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
    				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
    			if($tmprow=mysql_fetch_array($tmpresult))
    			{
				$catname=undo_htmlentities(stripslashes($tmprow["catname"]));
				$tmpsql2="select * from ".$tableprefix."_catnames where catnr=".$tmprow["catnr"]." and lang='".$act_lang."'";
				if(!$tmpresult2=mysql_query($tmpsql2,$db))
					die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
				if($tmprow2=mysql_fetch_array($tmpresult2))
				{
					if(strlen($tmprow2["catname"])>0)
						$catname=undo_htmlentities(stripslashes($tmprow2["catname"]));
				}
    			}
    			else
    				$catname="";
    		}
    		else
    			$catname=undo_htmlentities($l_general);
		if($first)
			$first=false;
		else
			$data.=",";
		$data.="\"$catname\"";
	}
	if(bittst($csvexportfields,BIT_4))
	{
		if($first)
			$first=false;
		else
			$data.=",";
		$displayheading=undo_htmlentities(stripslashes($entrydata["heading"]));
		$displayheading=strip_tags($displayheading);
		$displayheading=str_replace("\"","\"\"",$displayheading);
		$data.="\"$displayheading\"";
	}
	if(bittst($csvexportfields,BIT_5))
	{
		if($first)
			$first=false;
		else
			$data.=",";
		$displaytext=undo_htmlentities(stripslashes($entrydata["text"]));
		$displaytext=strip_tags($displaytext);
		$displaytext=str_replace("\"","\"\"",$displaytext);
		$data.="\"$displaytext\"";
	}
	if(bittst($csvexportfields,BIT_6))
	{
		if($first)
			$first=false;
		else
			$data.=",";
		$displayposter=undo_htmlentities(stripslashes($entrydata["poster"]));
		$displayposter=strip_tags($displayposter);
		$displayposter=str_replace("\"","\"\"",$displayposter);
		$data.="\"$displayposter\"";
	}
	$data.=$crlf;
}
header('Content-Type: application/octetstream');
header("Content-Transfer-Encoding: binary\n");
header('Content-Disposition: filename="events.csv"');
header("Content-length: " . strlen($data) . "\n");
print($data);
?>