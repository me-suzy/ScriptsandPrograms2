<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require('./config.php');
require('./functions.php');
if(!isset($category))
	$category=0;
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
include('./language/lang_'.$act_lang.'.php');
require_once('./includes/get_settings.inc');
$crlf="\r\n";
$appletversion="2.03";
$actdate = date("Y-m-d 23:59:59");
if(isset($download))
{
	header('Content-Type: application/octetstream');
	header('Content-Disposition: filename="newsscroller_'.$act_lang.'.txt"');
}
else
	header('Content-Type: text/plain');
if(!isset($scrolltype))
	$scrolltype=$newsscrollertype;
if(!isset($limitdays))
	$limitdays=$newsscrollermaxdays;
if(!isset($limitentries))
	$limitentries=$newsscrollermaxentries;
$bgcolor=str_replace("#","",$newsscrollerbgcolor);
$bgcolor=hexdec($bgcolor);
$fontcolor=str_replace("#","",$newsscrollerfontcolor);
$fontcolor=hexdec($fontcolor);
echo $appletversion.$crlf;
echo md5($snpurl.$snprogname.$copyright_note).$crlf;
$announceavail=false;
if(bittst($announceoptions,BIT_8))
{
		$acttime=transposetime(time(),$servertimezone,$displaytimezone);
		$sql = "select * from ".$tableprefix."_announce where (expiredate>=$acttime or expiredate=0) and (firstdate<=$acttime or firstdate=0) ";
		if($limitdays>=0)
			$sql.= "and date >= date_sub('$actdate', INTERVAL $limitdays DAY) ";
		if($separatebylang==1)
			$sql.="and lang='$act_lang' ";
		if($category>0)
			$sql.= "and (category='$category' or category=0)";
		else if($category==0)
			$sql.= "and category=0";
		$sql.= " order by date desc";
		if(isset($maxannounce))
			$sql.=" limit $maxannounce";
		else if($limitentries > 0)
			$sql.=" limit $limitentries";
		if(!$result = mysql_query($sql, $db))
		    die("Unable to connect to database.".mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$announceavail=true;
			while($myrow=mysql_fetch_array($result))
			{
				if($newsscrollerdisplaydate)
				{
					list($mydate,$mytime)=explode(" ",$myrow["date"]);
					list($year, $month, $day) = explode("-", $mydate);
					list($hour, $min, $sec) = explode(":",$mytime);
					if($month>0)
					{
						$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
						$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
						$displaydate=date($newsscrollerdateformat,$displaytime);
						echo $displaydate.":";
						if($scrolltype==1)
							echo " ";
						else
							echo "\\n";
					}
				}
				if($myrow["category"]==0)
				{
					if($applet_ganmark)
						echo "$applet_ganmark\\n";
				}
				else
				{
					if($applet_anmark)
						echo "$applet_anmark\\n";
				}
				if($myrow["heading"])
				{
					$displayheading=undo_htmlentities(stripslashes($myrow["heading"]));
					if($scrolltype==1)
						$displayheading.=": ";
					else
					{
						if($newsscrollerheadingsep==1)
						{
							$tmpdata="";
							for($i=0;$i<$newsscrollernumsepchars;$i++)
								$tmpdata.=$newsscrollerheadingsepchar;
							$displayheading.="\\n".$tmpdata;
						}
						$displayheading.="\\n";
					}
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
					if($myrow["tickerurl"])
						echo $myrow["tickerurl"];
					else
					{
						if($useappletlinkdest==1)
							echo "$appletlinkdestan?layout=$layout&$langvar=$act_lang&category=$category&announcenr=".$myrow["entrynr"];
						else
							echo "http://".$simpnewssitename.$url_simpnews."/announce.php?layout=$layout&$langvar=$act_lang&category=$category&announcenr=".$myrow["entrynr"];
					}
				}
				echo "$sep_char $bgcolor $sep_char $fontcolor $sep_char $newsscrollerfont $sep_char $newsscrollerfontsize $sep_char $newsscrollertarget $sep_char ";
				echo "0 $sep_char 2".$crlf;
			}
		}
}
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
	$sql.="and lang='$act_lang' ";
if($limitdays>=0)
	$sql.= "and date >= date_sub('$actdate', INTERVAL $limitdays DAY) ";
if($showfuturenews==0)
	$sql.="and date<='$actdate' ";
$sql.= "order by date desc";
if($limitentries > 0)
	$sql.=" limit $limitentries";
if(!$result = mysql_query($sql, $db))
	die();
if(mysql_num_rows($result)>0)
{
	while($myrow=mysql_fetch_array($result))
	{
		if($myrow["linknewsnr"]==0)
			$entrydata=$myrow;
		else
		{
			$tmpsql="select * from ".$tableprefix."_data where newsnr=".$myrow["linknewsnr"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("Unable to connect to database.".mysql_error());
			if(!$tmprow=mysql_fetch_array($tmpresult))
				die("Unable to connect to database.".mysql_error());
			$entrydata=$tmprow;
		}
		if($newsscrollerdisplaydate)
		{
			list($mydate,$mytime)=explode(" ",$myrow["date"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			if($month>0)
			{
				$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
				$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
				$displaydate=date($newsscrollerdateformat,$displaytime);
				echo $displaydate.":";
				if($scrolltype==1)
					echo " ";
				else
					echo "\\n";
			}
		}
		if($entrydata["heading"])
		{
			$displayheading=undo_htmlentities(stripslashes($entrydata["heading"]));
			if($scrolltype==1)
				$displayheading.=": ";
			else
			{
				if($newsscrollerheadingsep==1)
				{
					$tmpdata="";
					for($i=0;$i<$newsscrollernumsepchars;$i++)
						$tmpdata.=$newsscrollerheadingsepchar;
					$displayheading.="\\n".$tmpdata;
				}
				$displayheading.="\\n";
			}
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
			if($entrydata["tickerurl"])
				echo $entrydata["tickerurl"];
			else
			{
				if($useappletlinkdest==1)
					echo "$appletlinkdest?newsnr=".$entrydata["newsnr"]."&$langvar=$act_lang&layout=$layout&category=".$entrydata["category"];
				else
					echo "http://".$simpnewssitename.$url_simpnews."/singlenews.php?newsnr=".$entrydata["newsnr"]."&$langvar=$act_lang&layout=$layout&category=".$entrydata["category"];
			}
		}
		echo "$sep_char $bgcolor $sep_char $fontcolor $sep_char $newsscrollerfont $sep_char $newsscrollerfontsize $sep_char $newsscrollertarget $sep_char ";
		if(isset($lastvisitdate))
		{
			list($mydate,$mytime)=explode(" ",$myrow["date"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			$thisentrydate=mktime($hour,$min,$sec,$month,$day,$year);
			if($thisentrydate>=$lastvisitdate)
				echo "1 $sep_char";
			else
				echo "0 $sep_char";
		}
		else
			echo "0 $sep_char";
		echo " 2$crlf";
	}
}
else if(!$announceavail)
{
		echo "$l_nonewnews $sep_char ";
		if($newsscrollernolinking==0)
			echo "http://".$simpnewssitename.$url_simpnews."/news.php?$langvar=$act_lang&layout=$layout&category=$category";
		echo "$sep_char $bgcolor $sep_char $fontcolor $sep_char $newsscrollerfont $sep_char $newsscrollerfontsize $sep_char $newsscrollertarget $sep_char 1 $sep_char 0\n";
}
?>
