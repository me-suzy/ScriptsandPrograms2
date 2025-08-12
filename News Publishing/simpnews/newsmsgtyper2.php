<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require('./config.php');
require('./functions.php');
if(!isset($category))
	$category=0;
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./includes/get_settings.inc');
$crlf="\n";
$appletversion="2.03";
if(isset($download))
{
	$crlf="\r\n";
	header('Content-Type: application/octetstream');
	header('Content-Disposition: filename="newsmsgtyper_'.$act_lang.'.txt"');
}
$actdate = date("Y-m-d 23:59:59");
if(!isset($limitdays))
	$limitdays=$newstyper2maxdays;
if(!isset($limitentries))
	$limitentries=$newstyper2maxentries;
echo $appletversion.$crlf;
echo md5($snpurl.$snprogname.$copyright_note).$crlf;
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
				if($newstyper2displaydate==1)
				{
					list($mydate,$mytime)=explode(" ",$myrow["date"]);
					list($year, $month, $day) = explode("-", $mydate);
					list($hour, $min, $sec) = explode(":",$mytime);
					if($month>0)
					{
						$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
						$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
						$displaydate=date($newstyper2dateformat,$displaytime);
						echo $displaydate." ";
						if($myrow["category"]==0)
						{
							if($applet_ganmark)
								echo $applet_ganmark.$crlf;
							else
								echo "(".undo_htmlentities($l_global_announcement)."):$crlf";
						}
						else
						{
							if($applet_anmark)
								echo $applet_anmark.$crlf;
							else
								echo "(".undo_htmlentities($l_announcement)."):$crlf";
						}
					}
				}
				if($myrow["heading"])
				{
					$displayheading=undo_htmlentities(stripslashes($myrow["heading"]));
					$displayheading.=$crlf;
					echo $displayheading;
				}
				$displaytext = undo_htmlspecialchars(stripslashes($myrow["text"]));
				$displaytext = str_replace("\r","",$displaytext);
				$displaytext = undo_htmlentities($displaytext);
				$displaytext = str_replace("<BR>",$crlf,$displaytext);
				$displaytext = strip_tags($displaytext);
				if(($newstyper2maxchars>0) && (strlen($displaytext)>$newstyper2maxchars))
				{
					$text = explode(" ", $displaytext);
					$i = 0;
					$length = 0;
					$displaytext="";
					while(($i<count($text)) && ($length<$newstyper2maxchars))
					{
						$length+=strlen($text[$i]);
						if($length<=$newstyper2maxchars)
						{
							$displaytext.=$text[$i]." ";
							$i++;
						}
					}
					if($i<count($text))
						$displaytext.="...";
				}
				echo $displaytext.$crlf;
				if($newstyper2waitentry==1)
					echo "<more>$crlf";
				if($newstyper2newscreen==1)
					echo "<newpage>$crlf";
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
		if($newstyper2displaydate==1)
		{
			list($mydate,$mytime)=explode(" ",$myrow["date"]);
			list($year, $month, $day) = explode("-", $mydate);
			list($hour, $min, $sec) = explode(":",$mytime);
			if($month>0)
			{
				$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
				$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
				$displaydate=date($newstyper2dateformat,$displaytime);
				echo $displaydate.":$crlf";
			}
		}
		if($entrydata["heading"])
		{
			$displayheading=undo_htmlentities(stripslashes($entrydata["heading"]));
			$displayheading.=$crlf;
			echo $displayheading;
		}
		$displaytext = undo_htmlspecialchars(stripslashes($entrydata["text"]));
		$displaytext = str_replace("\r","",$displaytext);
		$displaytext = undo_htmlentities($displaytext);
		$displaytext = str_replace("<BR>",$crlf,$displaytext);
		$displaytext = strip_tags($displaytext);
		if(($newstyper2maxchars>0) && (strlen($displaytext)>$newstyper2maxchars))
		{
			$text = explode(" ", $displaytext);
			$i = 0;
			$length = 0;
			$displaytext="";
			while(($i<count($text)) && ($length<$newstyper2maxchars))
			{
				$length+=strlen($text[$i]);
				if($length<=$newstyper2maxchars)
				{
					$displaytext.=$text[$i]." ";
					$i++;
				}
			}
			if($i<count($text))
				$displaytext.="...";
		}
		echo $displaytext.$crlf;
		if($newstyper2waitentry==1)
			echo "<more>".$crlf;
		if($newstyper2newscreen==1)
			echo "<newpage>".$crlf;
	}
}
else if(!$announceavail)
{
		echo "$l_nonewnews$crlf<PAUSE 1000>$crlf";
}
?>
