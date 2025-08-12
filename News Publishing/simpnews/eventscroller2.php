<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require('./config.php');
require('./functions.php');
$crlf="\r\n";
header('Content-Type: text/plain');
$appletversion="2.03";
if(!isset($sortorder))
	$sortorder=1;
if(!isset($category))
	$category=0;
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
include('./language/lang_'.$act_lang.'.php');
require_once('./includes/get_settings.inc');
if(!isset($scrolltype))
	$scrolltype=$newsscrollertype;
if(!isset($limitentries))
	$limitentries=$newsscrollermaxentries;
$acttime=transposetime(time(),$servertimezone,$displaytimezone);
$actdate=date("Y-m-d",$acttime);
$bgcolor=str_replace("#","",$newsscrollerbgcolor);
$bgcolor=hexdec($bgcolor);
$fontcolor=str_replace("#","",$newsscrollerfontcolor);
$fontcolor=hexdec($fontcolor);
echo $appletversion.$crlf;
echo md5($snpurl.$snprogname.$copyright_note).$crlf;
$announceavail=false;
if(bittst($announceoptions,BIT_8))
{
		$sql = "select * from ".$tableprefix."_announce where (expiredate>=$acttime or expiredate=0)  and (firstdate<=$acttime or firstdate=0)";
		if(!isset($limitdays) || ($limitdays==0))
			$sql.=" and DATE_FORMAT(date,'%Y-%m-%d')='$actdate' ";
		else
			$sql.=" and DATE_FORMAT(date,'%Y-%m-%d')>='$actdate' and date<=date_add('$actdate',INTERVAL $limitdays DAY)";
		if($separatebylang==1)
			$sql.="and lang='$act_lang' ";
		if($category>0)
			$sql.= "and (category='$category' or category=0)";
		else if($category==0)
			$sql.= "and category=0";
		$sql.=" order by category asc";
		if(isset($maxannounce))
			$sql.=" limit $maxannounce";
		if(!$result = mysql_query($sql, $db))
		    die("Unable to connect to database.".mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$announceavail=true;
			while($myrow=mysql_fetch_array($result))
			{
				if(isset($limitdays) && ($limitdays>0))
				{
					list($year, $month, $day) = explode("-", $myrow["date"]);
					if($month>0)
					{
						$displaytime=mktime(0,0,0,$month,$day,$year);
						$displaydate=date($newsscrollerdateformat,$displaytime);
						echo $displaydate.":\\n";
					}
				}
				if($myrow["heading"])
				{
					$displayheading=undo_htmlentities(stripslashes($myrow["heading"]));
					if($scrolltype==1)
						$displayheading.=": ";
					else
						$displayheading.="\\n";
					echo $displayheading;
				}
				$displaytext = undo_htmlspecialchars(stripslashes($myrow["text"]));
				$displaytext = str_replace("\r","",$displaytext);
				$displaytext = undo_htmlentities($displaytext);
				if($scrolltype==1)
					$displaytext = str_replace("<BR>","    ",$displaytext);
				else
					$displaytext = str_replace("<BR>","\\n",$displaytext);
				$displaytext = strip_tags($displaytext);
				if(($newsscrollermaxchars>0) && (strlen($displaytext)>$newsscrollermaxchars))
				{
					$text = explode(" ", $displaytext);
					$i = 0;
					$length = 0;
					$displaytext="";
					while(($i<count($text)) && ($length<$newsscrollermaxchars))
					{
						$length+=strlen($text[$i]);
						if($length<=$newsscrollermaxchars)
						{
							$displaytext.=$text[$i]." ";
							$i++;
						}
					}
					if($i<count($text))
						$displaytext.="...";
				}
				echo $displaytext." $sep_char ";
				if($newsscrollernolinking==0)
				{
					list($mydate,$mytime)=explode(" ",$myrow["date"]);
					list($year, $month, $day) = explode("-", $mydate);
					list($hour, $min, $sec) = explode(":",$mytime);
					$temptime=mktime($hour,$min,$sec,$month,$day,$year);
					$link_date=date("Y-m-d",$temptime);
					if(!bittst($announceoptions,BIT_1))
					{
						if($useappletlinkdest==1)
							$announceurl="$appletlinkdestan?$langvar=$act_lang&category=$category&link_date=".$link_date;
						else
							$announceurl="http://".$simpnewssitename.$url_simpnews."/announce.php?$langvar=$act_lang&category=$category&link_date=".$link_date;
					}
					else
					{
						if($useappletlinkdest==1)
							$announceurl="$appletlinkdestev?$langvar=$act_lang&layout=$layout&link_date=".$link_date."&category=$category";
						else
							$announceurl="http://".$simpnewssitename.$url_simpnews."/events.php?$langvar=$act_lang&layout=$layout&link_date=".$link_date."&category=$category";
					}
					echo $announceurl;
				}
				echo "$sep_char $bgcolor $sep_char $fontcolor $sep_char $newsscrollerfont $sep_char $newsscrollerfontsize $sep_char $newsscrollertarget $sep_char ";
				echo "0 $sep_char 2".$crlf;
			}
		}
}
$sql = "select * from ".$tableprefix."_events ";
if(!isset($limitdays) || ($limitdays==0))
	$sql.="where DATE_FORMAT(date,'%Y-%m-%d')='$actdate' ";
else
	$sql.="where DATE_FORMAT(date,'%Y-%m-%d')>='$actdate' and DATE_FORMAT(date,'%Y-%m-%d')<=date_add('$actdate',INTERVAL $limitdays DAY)";
if($separatebylang==1)
	$sql.="and lang='$act_lang' ";
if($category>=0)
	$sql.= "and category='$category' ";
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
if($limitentries > 0)
	$sql.=" limit $limitentries";
if(!$result = mysql_query($sql, $db))
	die();
if(mysql_num_rows($result)>0)
{
	while($myrow=mysql_fetch_array($result))
	{
		if($myrow["linkeventnr"]==0)
			$entrydata=$myrow;
		else
		{
			$tmpsql="select * from ".$tableprefix."_events where eventnr=".$myrow["linkeventnr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("Unable to connect to database.");
			if(!$tmprow=mysql_fetch_array($tmpresult))
				die("Unable to connect to database.");
			$entrydata=$tmprow;
		}
		if(isset($limitdays) && ($limitdays>0))
		{
			list($tmpdate, $tmptime)=explode(" ",$entrydata["date"]);
			list($year, $month, $day) = explode("-", $tmpdate);
			list($hour, $min, $null) = explode(":", $tmptime);
			if(($hour>0) || ($min>0))
				$displaydate=date($event_dateformat2,mktime($hour,$min,0,$month,$day,$year));
			else
				$displaydate=date($event_dateformat,mktime(0,0,0,$month,$day,$year));
			echo $displaydate.":\\n";
		}
		if($entrydata["heading"])
		{
			$displayheading=undo_htmlentities(stripslashes($entrydata["heading"]));
			if($scrolltype==1)
				$displayheading.=": ";
			else
				$displayheading.="\\n";
			echo $displayheading;
		}
		$displaytext = undo_htmlspecialchars(stripslashes($entrydata["text"]));
		$displaytext = str_replace("\r","",$displaytext);
		$displaytext = undo_htmlentities($displaytext);
		if($scrolltype==1)
			$displaytext = str_replace("<BR>","    ",$displaytext);
		else
			$displaytext = str_replace("<BR>","\\n",$displaytext);
		$displaytext = strip_tags($displaytext);
		if(($newsscrollermaxchars>0) && (strlen($displaytext)>$newsscrollermaxchars))
		{
			$text = explode(" ", $displaytext);
			$i = 0;
			$length = 0;
			$displaytext="";
			while(($i<count($text)) && ($length<$newsscrollermaxchars))
			{
				$length+=strlen($text[$i]);
				if($length<=$newsscrollermaxchars)
				{
					$displaytext.=$text[$i]." ";
					$i++;
				}
			}
			if($i<count($text))
				$displaytext.="...";
		}
		echo $displaytext." $sep_char ";
		if($newsscrollernolinking==0)
		{
			list($mydate,$mytime)=explode(" ",$myrow["date"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			$temptime=mktime($hour,$min,$sec,$month,$day,$year);
			$link_date=date("Y-m-d",$temptime);
			if($useappletlinkdest==1)
				$linkdest="$appletlinkdestev?link_date=".$link_date."&$langvar=$act_lang&layout=$layout&category=$category";
			else
				$linkdest="http://".$simpnewssitename.$url_simpnews."/events.php?link_date=".$link_date."&$langvar=$act_lang&layout=$layout&category=$category";
			echo $linkdest;
		}
		echo "$sep_char $bgcolor $sep_char $fontcolor $sep_char $newsscrollerfont $sep_char $newsscrollerfontsize $sep_char $newsscrollertarget $sep_char ";
		echo "0 $sep_char 2".$crlf;
	}
}
else if(!$announceavail)
{
		if(!isset($limitdays) || ($limitdays==0))
			echo "$l_noeventstoday2 $sep_char ";
		else
			echo "$l_noevents $sep_char ";
		if($newsscrollernolinking==0)
		{
			if($evscrollevcal2==1)
				echo "$evscrollcal2dest?$langvar=$act_lang&layout=$layout&category=$category";
			else
				echo "http://".$simpnewssitename.$url_simpnews."/eventcal.php?$langvar=$act_lang&layout=$layout&category=$category";
		}
		echo "$sep_char $bgcolor $sep_char $fontcolor $sep_char $newsscrollerfont $sep_char $newsscrollerfontsize $sep_char $newsscrollertarget $sep_char 1 $sep_char 0".$crlf;

}
?>
