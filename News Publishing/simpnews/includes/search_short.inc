<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$localbackurl="$act_script_url?$langvar=$act_lang&layout=$layout";
if(isset($start))
	$localbackurl.="&start=$start";
if($srchcat>=0)
	$localbackurl.="&srchcat=$srchcat";
$localbackurl.="&category=$category&mode=search&searchvalues=$searchvalues&searchpart=$searchpart";
if(isset($searchstart))
	$localbackurl.="&searchstart=$searchstart";
$localbackurl.="&backurl=$backurl";
while($myrow=mysql_fetch_array($result))
{
	if($myrow["linknewsnr"]==0)
		$entrydata=$myrow;
	else
	{
		$tmpsql="select * from ".$tableprefix."_data where newsnr=".$myrow["linknewsnr"];
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		if(!$tmprow=mysql_fetch_array($tmpresult))
			die("<tr class=\"errorrow\"><td>Unable to get data");
		$entrydata=$tmprow;
	}
	list($mydate,$mytime)=explode(" ",$myrow["date"]);
	list($year, $month, $day) = explode("-", $mydate);
	list($hour, $min, $sec) = explode(":",$mytime);
	if($month>0)
	{
		$displaytime=mktime($hour,$min,$sec,$month,$day,$year);
		$displaytime=transposetime($displaytime,$servertimezone,$displaytimezone);
		$displaydate=date($dateformat,$displaytime);
	}
	else
		$displaydate="";
	echo "<tr><td align=\"right\" width=\"15%\" bgcolor=\"$timestampbgcolor\">";
	echo "<font face=\"$timestampfont\" size=\"$sshort_timestampfontsize\" color=\"$timestampfontcolor\">";
	echo get_start_tag($timestampstyle);
	echo $displaydate;
	echo get_end_tag($timestampstyle);
	echo "</font></td>";
	if(strlen($entrydata["heading"])>0)
	{
		$entryheading=stripslashes($myrow["heading"]);
		$entryheading=search_highlight($entryheading,$musts,$cans);
		$displaytext=undo_htmlspecialchars(do_htmlentities($entryheading));
	}
	else
	{
		$displaytext = stripslashes($entrydata["text"]);
		$displaytext = undo_htmlspecialchars($displaytext);
		$displaytext = str_replace("<BR>"," ",$displaytext);
		$displaytext = strip_tags($displaytext);
		if(strlen($displaytext)>$searchshortchars)
			$displaytext = search_highlight(subwords($displaytext,$searchshortchars),$musts,$cans);
	}
	
	echo "<td class=\"newsbox\" bgcolor=\"$newsheadingbgcolor\" align=\"left\">";
	echo "<font face=\"$newsheadingfont\" size=\"$sshort_headingfontsize\" color=\"$newsheadingfontcolor\">";
	$linkdest="singlenews.php?$langvar=$act_lang&newsnr=".$entrydata["newsnr"]."&backurl=".urlencode($localbackurl)."&backtxt=$l_back2search&nonav=1";
	$linkdest=addhighlights($linkdest,$musts,$cans);
	echo "<a title=\"$l_showentry\" class=\"newslink\" href=\"$linkdest\" target=\"$searchdetailtarget\">";
	echo $displaytext;
	echo "</a></font></td>";
}
?>