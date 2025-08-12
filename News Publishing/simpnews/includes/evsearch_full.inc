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
$entrycount=1;
while($myrow=mysql_fetch_array($result))
{
	echo "<tr><td width=\"2%\" height=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\">";
	if($myrow["headingicon"])
		echo "<img src=\"$url_icons/".$myrow["headingicon"]."\" border=\"0\" align=\"middle\"> ";
	else
		echo "&nbsp;";
	echo "</td>";
	echo "<td align=\"center\">";
	echo "<table width=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\" cellspacing=\"0\" cellpadding=\"0\">";
	list($mydate,$mytime)=explode(" ",$myrow["date"]);
	list($year, $month, $day) = explode("-", $mydate);
	list($hour, $min, $sec) = explode(":",$mytime);
	if($month>0)
	{
		if(($hour>0) || ($min>0))
			$displaydate=date($event_dateformat2,mktime($hour,$min,0,$month,$day,$year));
		else
			$displaydate=date($event_dateformat,mktime(0,0,0,$month,$day,$year));
	}
	else
		$displaydate="";
	echo "<tr><td align=\"left\" bgcolor=\"$timestampbgcolor\" colspan=\"3\">";
	echo "<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
	echo get_start_tag($timestampstyle);
	echo $displaydate;
	echo get_end_tag($timestampstyle);
	echo "</font></td></tr>";
	$hasheading=0;
	if(strlen($myrow["heading"])>0)
	{
		$hasheading=1;
		echo "<tr bgcolor=\"$newsheadingbgcolor\"><td id=\"headingrow".$entrycount."\" align=\"left\" colspan=\"3\"";
		if($searchmaxchars>0)
		{
			$linkdest="events.php?$langvar=$act_lang&eventnr=".$myrow["eventnr"]."&backurl=".urlencode($localbackurl)."&backtxt=$l_back2search&nonav=1";
			$linkdest=addhighlights($linkdest,$musts,$cans);
			echo " class=\"linkcell\" onMouseOver=\"highlightentry($entrycount,$hasheading)\" onMouseOut=\"unhighlightentry($entrycount, $hasheading)\"";
			if($searchdetailtarget)
				echo " onClick=\"window.open('$linkdest','$searchdetailtarget')\"";
			else
				echo " onClick=\"window.location.href='$linkdest'\"";
		}
		echo ">";
		echo "<font face=\"$newsheadingfont\" size=\"$newsheadingfontsize\" color=\"$newsheadingfontcolor\">";
		echo get_start_tag($newsheadingstyle);
		$entryheading=stripslashes($myrow["heading"]);
		$entryheading=search_highlight($entryheading,$musts,$cans);
		echo undo_htmlspecialchars(do_htmlentities($entryheading));
		echo get_end_tag($newsheadingstyle);
		echo "</font></td></tr>";
	}
	$linkdest="events.php?$langvar=$act_lang&eventnr=".$myrow["eventnr"]."&backurl=".urlencode($localbackurl)."&backtxt=$l_back2search&nonav=1";
	$linkdest=addhighlights($linkdest,$musts,$cans);
	echo "<tr bgcolor=\"$contentbgcolor\">";
	echo "<td id=\"entryrow".$entrycount."\" align=\"left\" colspan=\"3\"";
	if($searchmaxchars>0)
	{
		echo " class=\"linkcell\" onMouseOver=\"highlightentry($entrycount, $hasheading)\" onMouseOut=\"unhighlightentry($entrycount, $hasheading)\"";
		if($searchdetailtarget)
			echo " onClick=\"window.open('$linkdest','$searchdetailtarget')\"";
		else
			echo " onClick=\"window.location.href='$linkdest'\"";
	}
	echo ">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	$displaytext=stripslashes($myrow["text"]);
	$displaytext=undo_htmlentities($displaytext);
	if($searchmaxchars>0)
	{
		$displaytext=str_replace("<BR>"," ",$displaytext);
		$displaytext=substr(strip_tags($displaytext),0,$searchmaxchars);
	}
	$displaytext=search_highlight($displaytext,$musts,$cans);
	$displaytext=undo_htmlspecialchars(do_htmlentities($displaytext));
	if($searchmaxchars>0)
	{
		$displaytext.=" <a title=\"$l_showentry\" class=\"newslink\" href=\"$linkdest\" target=\"$searchdetailtarget\">";
		$displaytext.="[...]</a>";
	}
	echo $displaytext."</font></td></tr>";
	if($displayposter && (strlen($myrow["poster"])>0))
	{
		echo "<tr bgcolor=\"$posterbgcolor\"><td align=\"left\" colspan=\"3\">";
		echo "<font face=\"$posterfont\" size=\"$posterfontsize\" color=\"$posterfontcolor\">";
		echo get_start_tag($posterstyle);
		echo "$l_poster: ".do_htmlentities($myrow["poster"]);
		echo get_end_tag($posterstyle);
		echo "</font></td></tr>";
	}
	echo "<tr bgcolor=\"$contentbgcolor\">";
	echo "<tr bgcolor=\"$contentbgcolor\">";
	echo "<td align=\"left\">";
	$attachsql="select f.filename, f.filesize, f.mimetype, f.description, eva.* from ".$tableprefix."_events_attachs eva, ".$tableprefix."_files f where f.entrynr=eva.attachnr and eva.eventnr=".$myrow["eventnr"];
	if(!$attachresult = mysql_query($attachsql, $db))
	   	die("<tr class=\"errorrow\"><td>Could not connect to the database.");
	if($attachrow=mysql_fetch_array($attachresult))
	{
		echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">";
		do{
			echo "<TR ALIGN=\"LEFT\"><td>";
			echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$fileinfo=$attachrow["filename"]." (".format_bytes($attachrow["filesize"]).")";
			if($attachrow["description"])
				$fileinfo.=" ".$attachrow["description"];
			echo "&nbsp;<a href=\"sndownload.php?entrynr=".$attachrow["attachnr"]."\" target=\"_blank\">";
			$mimesql="select * from ".$tableprefix."_mimetypes where mimetype='".$attachrow["mimetype"]."'";
			if(!$mimeresult = mysql_query($mimesql, $db))
			   	die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if($mimerow=mysql_fetch_array($mimeresult))
			{
				$fdsql="select * from ".$tableprefix."_filetypedescription where language='$act_lang' and mimetype=".$mimerow["entrynr"];
				if(!$fdresult = mysql_query($fdsql, $db))
				   	die("<tr class=\"errorrow\"><td>Could not connect to the database.");
				if($fdrow=mysql_fetch_array($fdresult))
					$dfileinfo=$fdrow["description"].": ".$fileinfo;
				else
					$dfileinfo=$fileinfo;
				if($mimerow["icon"])
					echo "<img class=\"attach\" src=\"$url_gfx/".$mimerow["icon"]."\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\"> ";
				else
					echo "<img src=\"$url_gfx/$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\">";
			}
			else
				echo "<img src=\"$url_gfx/$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$fileinfo\" alt=\"$fileinfo\">";
			echo " $l_attachement&nbsp;</a>";
			if($nofileinfo==0)
				echo " [".$fileinfo."]";
			echo "</td></tr>";
		}while($attachrow=mysql_fetch_array($attachresult));
		echo "</table>";
	}
	else
		echo "&nbsp;";
	echo "</td><td align=\"right\">";
	echo "<font face=\"$contentfont\" size=\"1\" color=\"$contentfontcolor\">";
	if($noprinticon==0)
	{
		echo "<a href=\"evprint.php?$langvar=$act_lang&layout=$layout&eventnr=".$myrow["eventnr"]."\" target=\"printwindow\">";
		if($printpic)
			echo "<img src=\"$url_gfx/$printpic\" border=\"0\" align=\"absmiddle\" title=\"$l_print\" alt=\"$l_print\">";
		else
			echo "[$l_print]";
		echo "</a>&nbsp; ";
	}
	if($nogotopicon==0)
	{
		echo "<a href=\"#top\"><img src=\"$url_gfx/$pagetoppic\" border=\"0\" align=\"absmiddle\" title=\"$l_gotop\" alt=\"$l_gotop\"></a>";
	}
	if(($noprinticon==0) && ($nogotopicon==0))
		echo "&nbsp;";
	echo "</td></tr>";
	echo "</table></td></tr>";
	$entrycount++;
}
?>