<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
while(list($null, $newsnr) = each($_POST["selnews"]))
{
	$sql2="select * from ".$tableprefix."_data where newsnr=$newsnr";
	if(!$result2 = mysql_query($sql2, $db))
		die("Unable to connect to database.".mysql_error());
	if(!$myrow2=mysql_fetch_array($result2))
		die("Unable to connect to database.".mysql_error());
	$numnews++;
	array_push($sentnewsentries,$myrow2["newsnr"]);
	list($mydate,$mytime)=explode(" ",$myrow2["date"]);
	list($year, $month, $day) = explode("-", $mydate);
	list($hour, $min, $sec) = explode(":",$mytime);
	$temptime=mktime($hour,$min,$sec,$month,$day,$year);
	$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
	$displaydate=date($dateformat,$temptime);
	$asc_mailmsg.="$displaydate:".$crlf;
	if($newsletternoicons==0)
	{
		$html_mailmsg.="<tr>".$crlf;
		$html_mailmsg.="<td width=\"2%\" height=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\">".$crlf;
		if($myrow2["headingicon"])
			$html_mailmsg.=str_replace($url_gfx."/","","<img src=\"$url_icons/".$myrow2["headingicon"]."\" border=\"0\" align=\"middle\"> ".$crlf);
		else
			$html_mailmsg.="&nbsp;";
		$html_mailmsg.="</td>";
		$html_mailmsg.="<td  align=\"center\">";
		$html_mailmsg.="<table width=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\" cellspacing=\"0\" cellpadding=\"0\">".$crlf;
	}
	$html_mailmsg.="<tr bgcolor=\"$timestampbgcolor\">";
	$html_mailmsg.="<td align=\"left\">".$crlf;
	$html_mailmsg.="<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">".$crlf;
	$html_mailmsg.=get_start_tag($timestampstyle);
	$html_mailmsg.=$displaydate;
	$html_mailmsg.=get_end_tag($timestampstyle);
	$html_mailmsg.="</font></td></tr>".$crlf;
	$entrylink=$simpnews_fullurl."singlenews.php?$langvar=".$filterlang."&category=".$myrow2["category"]."&newsnr=".$myrow2["newsnr"];
	if(strlen($myrow2["heading"])>0)
	{
		$asc_heading=strip_tags($myrow2["heading"]);
		$asc_mailmsg.=$asc_heading;
		$asc_mailmsg.=$crlf;
		for($i=0;$i<strlen($asc_heading);$i++)
			$asc_mailmsg.="-";
		$asc_mailmsg.=$crlf;
		$html_mailmsg.="<tr bgcolor=\"$newsheadingbgcolor\"><td align=\"left\">".$crlf;
		$html_mailmsg.="<font face=\"$newsheadingfont\" size=\"$newsheadingfontsize\" color=\"$newsheadingfontcolor\">".$crlf;
		if($newsletterlinking==1)
			$html_mailmsg.="<a href=\"".$entrylink."\">";
		$html_mailmsg.=get_start_tag($newsheadingstyle);
		$html_mailmsg.=display_encoded(stripslashes($myrow2["heading"]));
		$html_mailmsg.=get_end_tag($newsheadingstyle);
		if($newsletterlinking==1)
			$html_mailmsg.="</a>";
		$html_mailmsg.="</font></td></tr>".$crlf;
	}
	$html_mailmsg.="<tr bgcolor=\"$contentbgcolor\"><td align=\"left\">".$crlf;
	$html_mailmsg.="<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">".$crlf;
	$html_news=stripslashes($myrow2["text"]);
	$html_news=undo_htmlspecialchars($html_news);
	if($newsletterlinking==2)
		$html_news.="<br><a href=\"".$entrylink."\">".$l_mail_readmore."</a>";
	if($newsletterattachinlinepix==1)
		$html_news=recode_img_for_emails($html_news);
	$asc_newstext=str_replace("<BR>",$crlf,$html_news);
	$html_news=str_replace("<BR>","<BR>".$crlf,$html_news);
	$html_mailmsg.=$html_news."</font></td></tr>".$crlf;
	$asc_newstext=undo_htmlentities($asc_newstext);
	$asc_newstext=strip_tags($asc_newstext);
	if($newsletterlinking!=0)
		$asc_newstext.=$crlf."[".$entrylink."]";
	$asc_mailmsg.=$asc_newstext.$crlf;
	if($displayposter && (strlen($myrow2["poster"])>0))
	{
		$html_mailmsg.="<tr bgcolor=\"$posterbgcolor\"><td align=\"left\">".$crlf;
		$html_mailmsg.="<font face=\"$posterfont\" size=\"$posterfontsize\" color=\"$posterfontcolor\">".$crlf;
		$html_mailmsg.=get_start_tag($posterstyle);
		$html_mailmsg.="$l_poster: ".display_encoded($myrow2["poster"]);
		$html_mailmsg.=get_end_tag($posterstyle);
		$html_mailmsg.="</font></td></tr>".$crlf;
	}
	if($mailattach==0)
	{
		$attachsql="select f.filename, f.filesize, f.mimetype, na.* from ".$tableprefix."_news_attachs na, ".$tableprefix."_files f where f.entrynr=na.attachnr and na.newsnr=".$myrow2["newsnr"];
		if(!$attachresult = mysql_query($attachsql, $db))
			die("Unable to connect to database.".mysql_error());
		if($attachrow=mysql_fetch_array($attachresult))
		{
			do{
				$html_mailmsg.="<tr bgcolor=\"$contentbgcolor\">".$crlf;
				$html_mailmsg.="<td align=\"left\">".$crlf;
				$html_mailmsg.="<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">".$crlf;
				$fileinfo=$attachrow["filename"]." (".format_bytes($attachrow["filesize"]).")";
				$mimesql="select * from ".$tableprefix."_mimetypes where mimetype='".$attachrow["mimetype"]."'";
				$imgtxt="";
				if(!$mimeresult = mysql_query($mimesql, $db))
					die("Could not connect to the database.");
				if($mimerow=mysql_fetch_array($mimeresult))
				{
					$fdsql="select * from ".$tableprefix."_filetypedescription where language='$act_lang' and mimetype=".$mimerow["entrynr"];
					if(!$fdresult = mysql_query($fdsql, $db))
						die("Could not connect to the database.");
					if($fdrow=mysql_fetch_array($fdresult))
						$dfileinfo=$fdrow["description"].": ".$fileinfo;
					else
						$dfileinfo=$fileinfo;
					if($mimerow["icon"])
						$imgtxt="<img class=\"attach\" src=\"".$mimerow["icon"]."\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\"> ";
					else
						$imgtxt="<img src=\"$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\">";
				}
				else
					$imgtxt="<img src=\"$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$fileinfo\" alt=\"$fileinfo\">";
				if($newsletternoicons==0)
				{
					$html_mailmsg.="&nbsp;<a href=\"".$simpnews_fullurl."sndownload.php?entrynr=".$attachrow["attachnr"]."\" target=\"_blank\">";
					$html_mailmsg.=str_replace($url_gfx."/","",$imgtxt);
					$html_mailmsg.="</a>&nbsp; ";
				}
				else
					$html_mailmsg.="Attachement:&nbsp;";
				$html_mailmsg.="<a href=\"".$simpnews_fullurl."sndownload.php?entrynr=".$attachrow["attachnr"]."\" target=\"_blank\">Download</a></font></td></tr>".$crlf;
				$html_mailmsg.="</td></tr>";
				$asc_mailmsg.= "Download attachement: ".$simpnews_fullurl."sndownload.php?entrynr=".$attachrow["newsnr"].$crlf;
			}while($attachrow=mysql_fetch_array($attachresult));
		}
		@mysql_free_result($attachresult);
	}
	else
	{
		$tmpsql="select files.* from ".$tableprefix."_news_attachs na, ".$tableprefix."_files files where files.entrynr=na.attachnr and na.newsnr=".$myrow2["newsnr"];
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("Unable to connect to database.".mysql_error());
		while($tmprow=mysql_fetch_array($tmpresult))
		{
			if(!$attach_in_fs)
				$file_data=$tmprow["bindata"];
			else
				$file_data=get_file($path_attach."/".$tmprow["fs_filename"]);
			$mail->addAttachment($file_data, $tmprow["filename"], $tmprow["mimetype"],"base64");
		}
		@mysql_free_result($tmpresult);
	}
	$asc_mailmsg.=$crlf;
	if($newsletternoicons==0)
		$html_mailmsg.="</table></td></tr>".$crlf;
	@mysql_free_result($result2);
}
?>