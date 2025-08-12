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
include('./language/lang_'.$act_lang.'.php');
require_once('./includes/get_settings.inc');
$appletversion="2.03";
$crlf="\r\n";
$actdate = date("Y-m-d 23:59:59");
if(isset($download))
{
	header('Content-Type: application/octetstream');
	header('Content-Disposition: filename="newstyper_'.$act_lang.'.txt"');
}
else
	header('Content-Type: text/plain');
if(!isset($limitentries))
	$limitentries=$newstypermaxentries;
if(!isset($limitdays))
	$limitdays=$newstypermaxdays;
echo $appletversion.$crlf;
echo md5($snpurl.$snprogname.$copyright_note).$crlf;
$sql = "select * from ".$tableprefix."_data ";
if($category>=0)
	$sql.="where category='$category' ";
else
{
	$sql.="where linknewsnr=0 ";
	$tmpsql="select * from ".$tableprefix."_categories where hideintotallist=1";
	if(!$tmpresult = mysql_query($tmpsql, $db))
	    die("Unable to connect to database.".mysql_error());
	while($tmprow=mysql_fetch_array($tmpresult))
		$sql.="and category!=".$tmprow["catnr"]." ";
}
if($separatebylang==1)
	$sql.= "and lang='$act_lang' ";
if($limitdays>=0)
	$sql.= "and date >= date_sub('$actdate', INTERVAL $limitdays DAY) ";
if($showfuturenews==0)
	$sql.="and date<='$actdate' ";
$sql.= "order by date desc";
if($limitentries > 0)
	$sql.=" limit $limitentries";
if(!$result = mysql_query($sql, $db))
    die();
$numnews=mysql_num_rows($result);
$numentries=$numnews;
$numannounce=0;
if(bittst($announceoptions,BIT_8))
{
		$acttime=transposetime(time(),$servertimezone,$displaytimezone);
		$tmpsql = "select * from ".$tableprefix."_announce where (expiredate>=$acttime or expiredate=0) and (firstdate<=$acttime or firstdate=0) ";
		if($limitdays>=0)
			$tmpsql.= "and date >= date_sub('$actdate', INTERVAL $limitdays DAY) ";
		if($separatebylang==1)
			$tmpsql.="and lang='$act_lang' ";
		if($category>0)
			$tmpsql.= "and (category='$category' or category=0)";
		else if($category==0)
			$tmpsql.= "and category=0";
		$tmpsql.= " order by date desc";
		if(isset($maxannounce))
			$tmpsql.=" limit $maxannounce";
		else if($limitentries > 0)
			$tmpsql.=" limit $limitentries";
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("Unable to connect to database.".mysql_error());
		$numannounce=mysql_num_rows($tmpresult);
		$numentries+=$numannounce;
}
if($numentries>0)
	echo $numentries.$crlf;
if(bittst($announceoptions,BIT_8))
{
		if($numannounce>0)
		{
			while($tmprow=mysql_fetch_array($tmpresult))
			{
				$msgtext="";
				if($newstyperdisplaydate==1)
				{
					list($mydate,$mytime)=explode(" ",$tmprow["date"]);
					list($year, $month, $day) = explode("-", $mydate);
					list($hour, $min, $sec) = explode(":",$mytime);
					if($month>0)
					{
						$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
						$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
						$displaydate=date($newstyperdateformat,$displaytime);
						$msgtext.=$displaydate." ";
						if($tmprow["category"]==0)
						{
							if($applet_ganmark)
								$msgtext.=$applet_ganmark." ";
							else
								$msgtext.="(".undo_htmlentities($l_global_announcement)."): ";
						}
						else
						{
							if($applet_anmark)
								$msgtext.=$applet_anmark." ";
							else
								$msgtext.="(".undo_htmlentities($l_announcement)."): ";
						}
					}
				}
				if($tmprow["heading"])
				{
					$displayheading=undo_htmlentities(stripslashes($tmprow["heading"]));
					$msgtext.=$displayheading." ";
				}
				$displaytext = undo_htmlentities(stripslashes($tmprow["text"]));
				$displaytext = str_replace("\r","",$displaytext);
				$displaytext = str_replace("<BR>"," ",$displaytext);
				$displaytext = strip_tags($displaytext);
				if(($newstypermaxchars>0) && (strlen($displaytext)>$newstypermaxchars))
				{
					$text = explode(" ", $displaytext);
					$i = 0;
					$length = 0;
					$displaytext="";
					while(($i<count($text)) && ($length<$newstypermaxchars))
					{
						$length+=strlen($text[$i]);
						if($length<=$newstypermaxchars)
						{
							$displaytext.=$text[$i]." ";
							$i++;
						}
					}
					if($i<count($text))
						$displaytext.="...";
				}
				$msgtext.=$displaytext;
				echo $msgtext.$crlf;
			}
		}
}
if($numnews>0)
{
	while($myrow=mysql_fetch_array($result))
	{
		if($myrow["linknewsnr"]==0)
			$entrydata=$myrow;
		else
		{
			$tmpsql="select * from ".$tableprefix."_data where newsnr=".$myrow["linknewsnr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
			if(!$tmprow=mysql_fetch_array($tmpresult))
				die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
			$entrydata=$tmprow;
		}
		$msgtext="";
		if(isset($lastvisitdate))
		{
			list($mydate,$mytime)=explode(" ",$myrow["date"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			$thisentrydate=mktime($hour,$min,$sec,$month,$day,$year);
			if($thisentrydate>=$lastvisitdate)
			$msgtext.="$l_new ";
		}
		if($newstyperdisplaydate==1)
		{
			list($mydate,$mytime)=explode(" ",$myrow["date"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			if($month>0)
			{
				$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
				$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
				$displaydate=date($newstyperdateformat,$displaytime);
				$msgtext.=$displaydate.": ";
			}
		}
		if($entrydata["heading"])
		{
			$displayheading=undo_htmlentities(stripslashes($entrydata["heading"]));
			$msgtext.=$displayheading." ";
		}
		$displaytext = undo_htmlentities(stripslashes($entrydata["text"]));
		$displaytext = str_replace("\r","",$displaytext);
		$displaytext = str_replace("<BR>"," ",$displaytext);
		$displaytext = strip_tags($displaytext);
		if(($newstypermaxchars>0) && (strlen($displaytext)>$newstypermaxchars))
		{
			$text = explode(" ", $displaytext);
			$i = 0;
			$length = 0;
			$displaytext="";
			while(($i<count($text)) && ($length<$newstypermaxchars))
			{
				$length+=strlen($text[$i]);
				if($length<=$newstypermaxchars)
				{
					$displaytext.=$text[$i]." ";
					$i++;
				}
			}
			if($i<count($text))
				$displaytext.="...";
		}
		$msgtext.=$displaytext;
		echo $msgtext.$crlf;
	}
}
else if($numentries==0)
{
	echo "1$crlf";
	echo "$l_nonewnews$crlf";
}
?>
