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
$appletversion="2.03";
$crlf="\r\n";
if(isset($download))
{
	header('Content-Type: application/octetstream');
	header('Content-Disposition: filename="starscroller_'.$act_lang.'.txt"');
}
else
	header('Content-Type: text/plain');
if(!isset($limitdays))
	$limitdays=$ss_maxdays;
if(!isset($limitentries))
	$limitentries=$ss_maxentries;
echo $appletversion.$crlf;
echo md5($snpurl.$snprogname.$copyright_note).$crlf;
$basedir="LR";
switch($ss_dir)
{
	case 0:
		$basedir="BT";
		break;
	case 1:
		$basedir="TB";
		break;
	case 2:
		$basedir="LR";
		break;
	case 3:
		$basedir="RL";
		break;
	case 4:
		$basedir="FT";
		break;
}
echo "BC".$sep_char.$ss_bgcolor.$sep_char."CL".$sep_char.$ss_fontcolor.$sep_char."FS".$sep_char.$ss_fontsize.$sep_char."FN".$sep_char.$ss_font.$sep_char."FW".$sep_char.$ss_fontstyle;
if($ss_stars>0)
	echo $sep_char."SN";
else
	echo $sep_char."SO";
switch($ss_stars)
{
	case 1:
		echo $sep_char."SU";
		break;
	case 2:
		echo $sep_char."SD";
		break;
	case 3:
		echo $sep_char."SL";
		break;
	case 4:
		echo $sep_char."SR";
		break;
	case 5:
		echo $sep_char."SO";
		break;
}
echo $sep_char."S".$ss_speed;
if($ss_shadow==1)
	echo $sep_char."SHON";
else
	echo $sep_char."SHOFF";
if(bittst($announceoptions,BIT_8))
{
		$acttime=transposetime(time(),$servertimezone,$displaytimezone);
		$sql = "select * from ".$tableprefix."_announce where (expiredate>=$acttime or expiredate=0) and (firstdate<=$acttime or firstdate=0) ";
		if($limitdays>=0)
		{
			$actdate = date("Y-m-d H:i:s");
			$sql.= "and date >= date_sub('$actdate', INTERVAL $limitdays DAY) ";
		}
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
				$un_displayed=false;
				$firstpart=true;
				if($ss_nolinking==0)
				{
					echo $sep_char."UR".$sep_char;
					if($myrow["tickerurl"])
						echo $myrow["tickerurl"];
					else
					{
						if($useappletlinkdest==1)
							echo "$appletlinkdestan?announcenr=".$myrow["entrynr"]."&$langvar=$act_lang&layout=$layout&category=".$myrow["category"];
						else
							echo "http://".$simpnewssitename.$url_simpnews."/announce.php?announcenr=".$myrow["entrynr"]."&$langvar=$act_lang&layout=$layout&category=".$myrow["category"];
					}
				}
				else
					$un_displayed=true;
				list($mydate,$mytime)=explode(" ",$myrow["date"]);
				list($year, $month, $day) = explode("-", $mydate);
				list($hour, $min, $sec) = explode(":",$mytime);
				if($month>0)
				{
					echo $sep_char."$basedir".$sep_char;
					$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
					$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
					$displaydate=date($newsscrollerdateformat,$displaytime);
					echo $displaydate." ";
					if($myrow["category"]==0)
					{
						if($applet_ganmark)
							echo $applet_ganmark;
						else
							echo "(".undo_htmlentities($l_global_announcement)."):";
					}
					else
					{
						if($applet_anmark)
							echo $applet_anmark;
						else
							echo "(".undo_htmlentities($l_announcement)."):";
					}
					if(!$un_displayed)
					{
						echo $sep_char."UN";
						$un_displayed=true;
					}
					echo $sep_char."$basedir".$sep_char;
					$firstpart=false;
				}
				if($myrow["heading"])
				{
					if(!$un_displayed)
					{
						echo $sep_char."UN".$sep_char;
						$un_displayed=true;
					}
					if($firstpart)
					{
						echo $sep_char."$basedir".$sep_char;
						$firstpart=false;
					}
					$displayheading=undo_htmlentities(stripslashes($myrow["heading"]));
					echo $displayheading.$sep_char."$basedir".$sep_char;
				}
				if(!$un_displayed)
				{
					echo $sep_char."UN".$sep_char;
					$un_displayed=true;
				}
				if($firstpart)
				{
					echo $sep_char."$basedir".$sep_char;
					$firstpart=false;
				}
				$displaytext = undo_htmlspecialchars(stripslashes($myrow["text"]));
				$displaytext = str_replace("\r","",$displaytext);
				$displaytext = str_replace("\n","",$displaytext);
				$displaytext = undo_htmlentities($displaytext);
				$displaytext = str_replace("<BR>",$sep_char."$basedir".$sep_char,$displaytext);
				$displaytext = strip_tags($displaytext);
				echo $displaytext;
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
{
	$actdate = date("Y-m-d H:i:s");
	$sql.= "and date >= date_sub('$actdate', INTERVAL $limitdays DAY) ";
}
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
		$un_displayed=false;
		$firstpart=true;
		if($ss_nolinking==0)
		{
			echo $sep_char."UR".$sep_char;
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
		else
			$un_displayed=true;
		list($mydate,$mytime)=explode(" ",$myrow["date"]);
		list($year, $month, $day) = explode("-", $mydate);
		list($hour, $min, $sec) = explode(":",$mytime);
		if($month>0)
		{
			echo $sep_char."$basedir".$sep_char;
			$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
			$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
			$displaydate=date($newsscrollerdateformat,$displaytime);
			echo $displaydate.":";
			if(!$un_displayed)
			{
				echo $sep_char."UN";
				$un_displayed=true;
			}
			echo $sep_char."$basedir".$sep_char;
			$firstpart=false;
		}
		if($entrydata["heading"])
		{
			if(!$un_displayed)
			{
				echo $sep_char."UN".$sep_char;
				$un_displayed=true;
			}
			if($firstpart)
			{
				echo $sep_char."$basedir".$sep_char;
				$firstpart=false;
			}
			$displayheading=undo_htmlentities(stripslashes($entrydata["heading"]));
			echo $displayheading.$sep_char."$basedir".$sep_char;
		}
		if(!$un_displayed)
		{
			echo $sep_char."UN".$sep_char;
			$un_displayed=true;
		}
		if($firstpart)
		{
			echo $sep_char."$basedir".$sep_char;
			$firstpart=false;
		}
		$displaytext = undo_htmlspecialchars(stripslashes($entrydata["text"]));
		$displaytext = str_replace("\r","",$displaytext);
		$displaytext = str_replace("\n","",$displaytext);
		$displaytext = undo_htmlentities($displaytext);
		$displaytext = str_replace("<BR>",$sep_char."$basedir".$sep_char,$displaytext);
		$displaytext = strip_tags($displaytext);
		echo $displaytext;
	}
}
else if(!$announceavail)
{
		if($newsscrollernolinking==0)
		{
			echo $sep_char."UR".$sep_char;
			echo "http://".$simpnewssitename.$url_simpnews."/news.php?$langvar=$act_lang&layout=$layout&category=$category";
			echo $sep_char."UN".$sep_char;
		}
		echo "$basedir".$sep_char;
		echo "$l_nonewnews";
}
?>
