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
include('./language/lang_'.$act_lang.'.php');
require_once('./includes/get_settings.inc');
$crlf="\r\n";
$appletversion="2.03";
if(isset($download))
{
	header('Content-Type: application/octetstream');
	header('Content-Disposition: filename="newsticker_'.$act_lang.'.txt"');
}
else
	header('Content-Type: text/plain');
if(!isset($limitentries))
	$limitentries=$newstickermaxentries;
if(!isset($limitdays))
	$limitdays=$newstickermaxdays;
echo $appletversion.$crlf;
echo md5($snpurl.$snprogname.$copyright_note).$crlf;
$actdate = date("Y-m-d 23:59:59");
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
				if($myrow["heading"])
				{
					if($myrow["category"]==0)
					{
						if($applet_ganmark)
							echo "$applet_ganmark ";
						else
							echo undo_htmlentities($l_global_announcement).": ";
					}
					else
					{
						if($applet_anmark)
							echo "$applet_anmark ";
						else
							echo undo_htmlentities($l_announcement).": ";
					}
					$displayheading=undo_htmlentities(stripslashes($myrow["heading"]));
					$displayheading=str_replace(";","",$displayheading);
					echo $displayheading." $sep_char ";
					if($myrow["tickerurl"])
						echo $myrow["tickerurl"];
					else
					{
						if($useappletlinkdest==1)
							echo "$appletlinkdestan?announcenr=".$myrow["entrynr"]."&$langvar=$act_lang&layout=$layout&category=".$myrow["category"];
						else
							echo "http://".$simpnewssitename.$url_simpnews."/announce.php?announcenr=".$myrow["entrynr"]."&$langvar=$act_lang&layout=$layout&category=".$myrow["category"];
					}
					echo " $sep_char $newstickertarget$crlf";
				}
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
				die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
			if(!$tmprow=mysql_fetch_array($tmpresult))
				die("<tr bgcolor=\"#cccccc\"><td>Unable to connect to database.".mysql_error());
			$entrydata=$tmprow;
		}
		if($entrydata["heading"])
		{
			$displayheading=undo_htmlentities(stripslashes($entrydata["heading"]));
			$displayheading=str_replace(";","",$displayheading);
			echo $displayheading." $sep_char ";
			if($entrydata["tickerurl"])
				echo $entrydata["tickerurl"];
			else
			{
				if($useappletlinkdest==1)
					echo "$appletlinkdest?newsnr=".$entrydata["newsnr"]."&$langvar=$act_lang&layout=$layout&category=".$entrydata["category"];
				else
					echo "http://".$simpnewssitename.$url_simpnews."/singlenews.php?newsnr=".$entrydata["newsnr"]."&$langvar=$act_lang&layout=$layout&category=".$entrydata["category"];
			}
			echo " $sep_char $newstickertarget$crlf";
		}
	}
}
else if(!$announceavail)
		echo "$l_nonewnews $sep_char http://".$simpnewssitename.$url_simpnews."/news.php?$langvar=$act_lang&layout=$layout&category=$category $sep_char $newstickertarget$crlf";
?>
