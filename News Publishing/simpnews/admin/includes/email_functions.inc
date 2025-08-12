<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function email_single_news($heading, $newstext, $newslang, $db, $subject, $sender, $sendername, $newsdate, $poster, $newsnr, $icon, $newscat)
{
	global $path_attach, $path_simpnews, $url_icons, $url_gfx, $path_gfx, $tableprefix, $simpnews_fullurl, $l_poster, $copyright_url, $copyright_note, $contentcharset, $version;
	global $use_smtpmail, $smtpserver, $smtpport, $smtpsenderdomain, $smtpauth, $smtpuser, $smtppasswd, $servertimezone, $displaytimezone, $newsletternoicons;
	global $langvar, $mailattach, $attach_in_fs, $msendlimit, $newsletterattachinlinepix, $l_sendingmail, $sendnewsdelay;
	global $showsendprogress, $senddelayinterval, $sendprogressautohide, $l_hideprogressbox, $crlf, $emailerrordie, $userdata;
	global $insafemode, $newsletterlinking, $mailmaxlinelength;

	include_once('../includes/htmlMimeMail.inc');
	if($use_smtpmail)
	{
		include_once('../includes/smtp.inc');
		include_once('../includes/RFC822.inc');
	}
	require("./language/mail_".$newslang.".php");
	$cat_excludefromnewsletter=0;
	if($newscat>0)
	{
		$tmpsql="select * from ".$tableprefix."_categories where catnr=$newscat";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("Unable to connect to database.".mysql_error());
		if($tmprow=mysql_fetch_array($tmpresult))
			$cat_excludefromnewsletter=$tmprow["excludefromnewsletter"];
		@mysql_free_result($tmpresult);
	}
	$sql="select * from ".$tableprefix."_layout where lang='$newslang' and deflayout=1";
	if(!$result = mysql_query($sql, $db))
		die("Unable to connect to database.".mysql_error());
	if(!$myrow=mysql_fetch_array($result))
		die("Layout setup error");
	$dateformat=$myrow["dateformat"];
	$timestampfontcolor=$myrow["timestampfontcolor"];
	$timestampfontsize=$myrow["timestampfontsize"];
	$timestampfont=$myrow["timestampfont"];
	$timestampstyle=$myrow["timestampstyle"];
	$timestampbgcolor=$myrow["timestampbgcolor"];
	$globalheading=$myrow["heading"];
	$headingbgcolor=$myrow["headingbgcolor"];
	$headingfontcolor=$myrow["headingfontcolor"];
	$headingfont=$myrow["headingfont"];
	$headingfontsize=$myrow["headingfontsize"];
	$bordercolor=$myrow["bordercolor"];
	$contentbgcolor=$myrow["contentbgcolor"];
	$contentfontcolor=$myrow["contentfontcolor"];
	$contentfont=$myrow["contentfont"];
	$contentfontsize=$myrow["contentfontsize"];
	$TableWidth=$myrow["TableWidth"];
	$newsheadingbgcolor=$myrow["newsheadingbgcolor"];
	$newsheadingfontcolor=$myrow["newsheadingfontcolor"];
	$newsheadingstyle=$myrow["newsheadingstyle"];
	$newsheadingfont=$myrow["newsheadingfont"];
	$newsheadingfontsize=$myrow["newsheadingfontsize"];
	$displayposter=$myrow["displayposter"];
	$posterbgcolor=$myrow["posterbgcolor"];
	$posterfontcolor=$myrow["posterfontcolor"];
	$posterfont=$myrow["posterfont"];
	$posterfontsize=$myrow["posterfontsize"];
	$posterstyle=$myrow["posterstyle"];
	$copyrightbgcolor=$myrow["copyrightbgcolor"];
	$copyrightfontcolor=$myrow["copyrightfontcolor"];
	$copyrightfont=$myrow["copyrightfont"];
	$copyrightfontsize=$myrow["copyrightfontsize"];
	$attachpic=$myrow["attachpic"];
	$newsletterbgcolor=$myrow["newsletterbgcolor"];
	$newslettercustomheader=$myrow["newslettercustomheader"];
	$newslettercustomfooter=$myrow["newslettercustomfooter"];
	$newsletteralign=$myrow["newsletteralign"];
	@mysql_free_result($myrow);
	$heading=stripslashes($heading);
	$newstext=stripslashes($newstext);
	$subject=stripslashes($subject);
	list($mydate,$mytime)=explode(" ",$newsdate);
	list($year, $month, $day) = explode("-", $mydate);
	list($hour, $min, $sec) = explode(":",$mytime);
	$temptime=mktime($hour,$min,$sec,$month,$day,$year);
	$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
	$displaydate=date($dateformat,$temptime);
	$actdate = date("Y-m-d H:i:s");
	$asc_mailmsg="$displaydate:".$crlf;
	$html_mailmsg="<html>";
	switch($newsletteralign)
	{
		case 0:
			$tblalign="left";
			break;
		case 1:
			$tblalign="right";
			break;
		default:
			$tblalign="center";
			break;
	}
	$html_mailmsg.= "<head>";
	$html_mailmsg.= "<style>";
	if($newsletteralign<2)
	{
		$html_mailmsg.= "table.sntable {".$crlf;
		$html_mailmsg.= "float: $tblalign;".$crlf;
		$html_mailmsg.= "}".$crlf;
	}
	$html_mailmsg.= "a.headinglink{".$crlf;
	$html_mailmsg.= "color: $newsheadingfontcolor;".$crlf;
	$html_mailmsg.= "}".$crlf;
	$html_mailmsg.= "</style>";
	$html_mailmsg.= "</head>";
	$html_mailmsg.="<body bgcolor=\"$newsletterbgcolor\">";
	if($newslettercustomheader)
	{
		$html_mailmsg.="<div align=\"$tblalign\">";
		if($newsletterattachinlinepix==1)
			$newslettercustomheader=recode_img_for_emails($newslettercustomheader);
		$html_mailmsg.=$newslettercustomheader;
		$html_mailmsg.="</div>";
	}
	$html_mailmsg.="<div align=\"$tblalign\"><table width=\"$TableWidth\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\" align=\"$tblalign\" valign=\"top\" class=\"sntable\">";
	$html_mailmsg.="<tr><td bgcolor=\"$bordercolor\">";
	$html_mailmsg.="<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">";
	if(strlen($globalheading)>0)
	{
		$html_mailmsg.="<tr bgcolor=\"$headingbgcolor\" align=\"center\">";
		$html_mailmsg.="<td align=\"center\" valign=\"middle\"";
		if($newsletternoicons==0)
			$html_mailmsg.=" colspan=\"2\"";
		$html_mailmsg.="><font face=\"$headingfont\" size=\"$headingfontsize\" color=\"$headingfontcolor\"><b>$globalheading</b></font></td></tr>";
	}
	if($newsletternoicons==0)
	{
		$html_mailmsg.="<tr>";
		$html_mailmsg.="<td width=\"2%\" height=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\">";
		if($icon)
			$html_mailmsg.=str_replace($url_gfx."/","","<img src=\"$url_icons/$icon\" border=\"0\" align=\"middle\">");
		else
			$html_mailmsg.="&nbsp;";
		$html_mailmsg.="</td>";
		$html_mailmsg.="<td align=\"center\"><table width=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\" cellspacing=\"0\" cellpadding=\"0\">";
	}
	$html_mailmsg.="<tr bgcolor=\"$timestampbgcolor\"><td align=\"left\">";
	$html_mailmsg.="<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
	$html_mailmsg.=get_start_tag($timestampstyle);
	$html_mailmsg.=$displaydate;
	$html_mailmsg.=get_end_tag($timestampstyle);
	$html_mailmsg.="</font></td></tr>";
	$entrylink=$simpnews_fullurl."singlenews.php?$langvar=$newslang&category=$newscat&newsnr=$newsnr";
	if(strlen($heading)>0)
	{
		$html_mailmsg.="<tr bgcolor=\"$newsheadingbgcolor\"><td align=\"left\">";
		if($newsletterlinking==2)
			$html_mailmsg.="<a class=\"headinglink\" href=\"".$entrylink."\">";
		$html_mailmsg.="<font face=\"$newsheadingfont\" size=\"$newsheadingfontsize\" color=\"$newsheadingfontcolor\">";
		$html_mailmsg.=get_start_tag($newsheadingstyle);
		$html_mailmsg.=do_htmlentities($heading);
		$html_mailmsg.=get_end_tag($newsheadingstyle);
		$html_mailmsg.="</font>";
		if($newsletterlinking==2)
			$html_mailmsg.="</a>";
		$html_mailmsg.="</td></tr>";
		$asc_heading=strip_tags($heading);
		$asc_mailmsg.=$asc_heading;
		$asc_mailmsg.=$crlf;
		for($i=0;$i<strlen($asc_heading);$i++)
			$asc_mailmsg.="-";
		$asc_mailmsg.=$crlf;
	}
	$html_mailmsg.="<tr bgcolor=\"$contentbgcolor\"><td align=\"left\">";
	$html_mailmsg.="<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	$html_news=stripslashes($newstext);
	$html_news=undo_htmlspecialchars($html_news);
	$html_news=str_replace("<BR>","<br>",$html_news);
	if($newsletterlinking==2)
		$html_news.="<br><a href=\"".$entrylink."\">".$l_mail_readmore."</a>";
	if($newsletterattachinlinepix==1)
		$html_news=recode_img_for_emails($html_news);
	$asc_newstext=preg_replace("#<BR>#si",$crlf,$html_news);
	$html_mailmsg.=$html_news."</font></td></tr>";
	$asc_newstext=undo_htmlentities($asc_newstext);
	$asc_newstext=strip_tags($asc_newstext);
	if($newsletterlinking!=0)
		$asc_newstext.=$crlf."[".$entrylink."]";
	$asc_mailmsg.=$asc_newstext.$crlf;
	if($displayposter && (strlen($poster)>0))
	{
		$html_mailmsg.="<tr bgcolor=\"$posterbgcolor\"><td align=\"left\">";
		$html_mailmsg.="<font face=\"$posterfont\" size=\"$posterfontsize\" color=\"$posterfontcolor\">";
		$html_mailmsg.=get_start_tag($posterstyle);
		$html_mailmsg.="$l_poster: ".do_htmlentities($poster);
		$html_mailmsg.=get_end_tag($posterstyle);
		$html_mailmsg.="</font></td></tr>";
	}
	if($mailattach==0)
	{
		$attachsql="select f.filename, f.filesize, f.mimetype, na.* from ".$tableprefix."_news_attachs na, ".$tableprefix."_files f where f.entrynr=na.attachnr and na.newsnr=$newsnr";
		if(!$attachresult = mysql_query($attachsql, $db))
			die("Could not connect to the database.");
		if($attachrow=mysql_fetch_array($attachresult))
		{
			do{
				$html_mailmsg.="<tr bgcolor=\"$contentbgcolor\">";
				$html_mailmsg.="<td align=\"left\">";
				$html_mailmsg.="<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
				$fileinfo=$attachrow["filename"]." (".format_bytes($attachrow["filesize"]).")";
				$mimesql="select * from ".$tableprefix."_mimetypes where mimetype='".$attachrow["mimetype"]."'";
				$imgtxt="";
				if(!$mimeresult = mysql_query($mimesql, $db))
					die("Could not connect to the database.");
				if($mimerow=mysql_fetch_array($mimeresult))
				{
					$fdsql="select * from ".$tableprefix."_filetypedescription where language='$newslang' and mimetype=".$mimerow["entrynr"];
					if(!$fdresult = mysql_query($fdsql, $db))
						die("Could not connect to the database.");
					if($fdrow=mysql_fetch_array($fdresult))
						$dfileinfo=$fdrow["description"].": ".$fileinfo;
					else
						$dfileinfo=$fileinfo;
					if($mimerow["icon"])
						$imgtxt="<img class=\"attach\" src=\"$url_gfx/".$mimerow["icon"]."\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\"> ";
					else
						$imgtxt="<img src=\"$url_gfx/$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\">";
				}
				else
					$imgtxt="<img src=\"$url_gfx/$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$fileinfo\" alt=\"$fileinfo\">";
				if($newsletternoicons==0)
				{
					$html_mailmsg.="&nbsp;<a href=\"".$simpnews_fullurl."sndownload.php?entrynr=".$attachrow["attachnr"]."\" target=\"_blank\">";
					$html_mailmsg.=str_replace($url_gfx."/","",$imgtxt);
					$html_mailmsg.="</a>&nbsp; ";
				}
				else
					$html_mailmsg.="Attachement:&nbsp;";
				$html_mailmsg.="<a href=\"".$simpnews_fullurl."sndownload.php?entrynr=".$attachrow["attachnr"]."\" target=\"_blank\">Download</a></font></td></tr>";
				$html_mailmsg.="</td></tr>";
				$asc_mailmsg.= "Download attachement: ".$simpnews_fullurl."sndownload.php?entrynr=".$attachrow["attachnr"].$crlf;
			}while($attachrow=mysql_fetch_array($attachresult));
		}
		@mysql_free_result($attachresult);
	}
	$asc_mailmsg.=$crlf;
	if($newsletternoicons==0)
		$html_mailmsg.="</table></td></tr>";
	$html_mailmsg.="<tr bgcolor=\"$copyrightbgcolor\" align=\"center\">";
	$html_mailmsg.="<td align=\"center\" valign=\"middle\"";
	if($newsletternoicons==0)
		$html_mailmsg.=" colspan=\"2\"";
	$html_mailmsg.="><font face=\"$copyrightfont\" size=\"$copyrightfontsize\" color=\"$copyrightfontcolor\">";
	$html_mailmsg.="Powered by SimpNews $version</td></tr>";
	$html_mailmsg.="</table></td></tr></table></div><br clear=\"all\">";
	if($newslettercustomfooter)
	{
		$html_mailmsg.="<div align=\"$tblalign\">";
		if($newsletterattachinlinepix==1)
			$newslettercustomfooter=recode_img_for_emails($newslettercustomfooter);
		$html_mailmsg.=$newslettercustomfooter;
		$html_mailmsg.="</div>";
	}
	if($newsletternoicons==0)
		$html_mailmsg=recode_emoticons_for_emails($html_mailmsg);
	$sql = "select * from ".$tableprefix."_subscriptions where (confirmed=1 and language='$newslang') and (category=$newscat";
	if($cat_excludefromnewsletter==0)
		$sql.=" or category=0";
	$sql.=") group by subscriptionnr";
	if(!$result = mysql_query($sql, $db))
		die("Unable to connect to database.".mysql_error());
	$sentmails=0;
	if($showsendprogress==1)
		echo "<div id =\"progressbox\" class=\"progress\">$l_sendingmail: ";
	$rcvdat=array();
	while($myrow=mysql_fetch_array($result))
		array_push($rcvdat,$myrow);
	@mysql_free_result($result);
	for($y=0;$y<count($rcvdat);$y++)
	{
		if(!$insafemode)
			@set_time_limit($msendlimit);
		$myrow=$rcvdat[$y];
		if($showsendprogress==1)
		{
			if(($sentmails%100)==0)
				echo "<br>";
			echo "&bull;";
			flush();
		}
		$mail = new htmlMimeMail();
		$sql2="select * from ".$tableprefix."_layout where lang='$newslang' and deflayout=1";
		if(!$result2 = mysql_query($sql2, $db))
			die("Unable to connect to database.".mysql_error());
		if(!$myrow2=mysql_fetch_array($result2))
			die("Layout setup error");
		$unsubscribeurl=$simpnews_fullurl."subscription.php?$langvar=$newslang&id=".$myrow["unsubscribeid"]."&mode=remove&email=".$myrow["email"];
		$unsubscribeurl_html="<font face=\"Verdana, Geneva, Arial, Helvetica, sans-serif\" size=\"1\"><a href=\"$unsubscribeurl\">$unsubscribeurl</a></font>";
		if($myrow2["emailremark"])
			$html_remark=str_replace("{unsubscribeurl}",$unsubscribeurl_html,$myrow2["emailremark"]);
		else
			$html_remark="unsubscribe: $unsubscribeurl_html";
		$html_remark=str_replace("\n","<br>",$html_remark);
		$html_remark=str_replace("\r","",$html_remark);
		if($myrow2["emailremark"])
			$asc_remark=str_replace("{unsubscribeurl}",$unsubscribeurl,strip_tags($myrow2["emailremark"]));
		else
			$asc_remark="unsubscribe: $unsubscribeurl";
		$asc_remark=str_replace("\n",$crlf,$asc_remark);
		$userhtmlbody=$html_mailmsg;
		if(strlen($html_remark)>0)
			$userhtmlbody.="<hr>$html_remark<br><br>";
		if($myrow2["defsignature"])
		{
			$userhtmlbody.="---<br>";
			$html_sig=str_replace("\n","<br>",$myrow2["defsignature"]);
			$html_sig=str_replace("\r","",$html_sig);
			$userhtmlbody.=$html_sig;
		}
		$userhtmlbody.="</body></html>";
		$userhtmlbody=remove_spcode_markers($userhtmlbody);
		$userascbody=$asc_mailmsg;
		if(strlen($asc_remark)>0)
			$userascbody.="---------------------------".$crlf.$asc_remark.$crlf.$crlf;
		if($myrow2["defsignature"])
			$userascbody.="---".$crlf.str_replace("\n",$crlf,$myrow2["defsignature"]).$crlf;
		$mail->setCrlf($crlf);
		$mail->setTextWrap($mailmaxlinelength);
		$mail->setTextCharset($contentcharset);
		if($myrow["emailtype"]==0)
		{
			$mail->setHTMLCharset($contentcharset);
			if(($newsletternoicons==0) || ($newsletterattachinlinepix==1))
				$mail->setHTML($userhtmlbody,$userascbody,$path_gfx."/");
			else
				$mail->setHTML($userhtmlbody,$userascbody);
		}
		else
		{
	    		$mail->setText($userascbody);
		}
		if($mailattach==1)
		{
		    	$tmpsql="select files.* from ".$tableprefix."_news_attachs na, ".$tableprefix."_files files where files.entrynr=na.attachnr and na.newsnr=$newsnr";
			if(!$tmpresult = mysql_query($tmpsql, $db))
			    die("Unable to connect to database.".mysql_error());
			while($tmprow=mysql_fetch_array($tmpresult))
			{
				if(!$attach_in_fs)
					$file_data=$tmprow["bindata"];
				else
					$file_data=get_file($path_attach."/".$tmprow["fs_filename"]);
				if($file_data)
					$mail->addAttachment($file_data, $tmprow["filename"], $tmprow["mimetype"],"base64");
			}
			@mysql_free_result($tmpresult);
	    	}
		if($sendername)
			$fromadr="\"$sendername\" <$sender>";
		else
			$fromadr=$sender;
	    	$mail->setSubject($subject);
	    	$mail->setFrom($fromadr);
	    	$receiver=array();
	    	array_push($receiver,$myrow["email"]);
		if($use_smtpmail)
		{
			$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
	        	$sendresult=$mail->send($receiver, "smtp");
		}
		else
	    		$sendresult=$mail->send($receiver, "mail");
	    	$addlogtxt="email_single_news - subscription#: ".$myrow["subscriptionnr"]." - admin: ".$userdata["username"];
	    	$addlogtxt.=" - news# $newsnr";
	    	$addlogtxt.=" - cat# $newscat";
	    	do_emaillog($sendresult,$myrow["email"],$addlogtxt);
		if(!$sendresult)
		{
			echo "<br>Unable to send email for ".$myrow["email"]." (#".$myrow["subscriptionnr"].")";
			if($use_smtpmail)
			{
				echo "<br>";
				print_array($mail->errors);
			}
			if($emailerrordie==1)
				die();
			else
				echo "<br>";
		}
		$sql2="update ".$tableprefix."_subscriptions set lastsent='$actdate' where subscriptionnr=".$myrow["subscriptionnr"];
		if(!$result2 = mysql_query($sql2, $db))
		    die("Unable to connect to database.".mysql_error());
		$sentmails++;
		if(($sendnewsdelay>0) && (($sentmails%$senddelayinterval)==0))
		{
			if(!$insafemode)
			{
				if($msendlimit>($sendnewsdelay/1000+2))
					@set_time_limit($msendlimit);
				else
					@set_time_limit($sendnewsdelay/1000+2);
			}
			usleep($sendnewsdelay);
		}
	}
	if($newscat!=0)
	{
		$sql="update ".$tableprefix."_categories set nlsenddate='$actdate' where catnr=$newscat";
		if(!$result = mysql_query($sql, $db))
		    die("Unable to connect to database.".mysql_error());
	}
	if($showsendprogress==1)
	{
		echo "<br>";
		echo "<a href=\"javascript:hideprogressbox()\"";
		echo " class=\"actionlink\">$l_hideprogressbox</a>";
		echo "</div>\n";
		echo "<script type=\"text/javascript\" language=\"javascript\">\n";
		echo "<!--\n";
		echo "	hideprogressbox();\n";
		echo "// -->\n";
		echo "</script>\n";
		flush();
	}
	return $sentmails;
}

function email_single_event($heading, $eventtext, $eventlang, $db, $subject, $sender, $sendername, $eventdate, $poster, $eventnr, $icon, $eventcat)
{
	global $path_simpnews, $url_icons, $url_gfx, $path_gfx, $tableprefix, $simpnews_fullurl, $l_poster, $copyright_url, $copyright_note, $contentcharset, $version;
	global $use_smtpmail, $smtpserver, $smtpport, $smtpsenderdomain, $smtpauth, $smtpuser, $smtppasswd, $servertimezone, $displaytimezone, $newsletternoicons;
	global $langvar, $mailattach, $attach_in_fs, $msendlimit, $l_sendingmail, $sendnewsdelay;
	global $showsendprogress, $senddelayinterval, $sendprogressautohide, $l_hideprogressbox, $crlf;
	global $newsletterattachinlinepix, $emailerrordie, $userdata, $insafemode, $newsletterlinking, $mailmaxlinelength;

	include_once('../includes/htmlMimeMail.inc');
	if($use_smtpmail)
	{
		include_once('../includes/smtp.inc');
		include_once('../includes/RFC822.inc');
	}
	include("./language/mail_".$eventlang.".php");
	$cat_excludefromnewsletter=0;
	if($eventcat>0)
	{
		$tmpsql="select * from ".$tableprefix."_categories where catnr=$eventcat";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("Unable to connect to database.".mysql_error());
		if($tmprow=mysql_fetch_array($tmpresult))
			$cat_excludefromnewsletter=$tmprow["excludefromnewsletter"];
		@mysql_free_result($tmpresult);
	}
	$sql="select * from ".$tableprefix."_layout where lang='$eventlang' and deflayout=1";
	if(!$result = mysql_query($sql, $db))
	    die("Unable to connect to database.".mysql_error());
	if(!$myrow=mysql_fetch_array($result))
		die("Layout setup error");
	$event_dateformat=$myrow["event_dateformat"];
	$event_dateformat2=$myrow["event_dateformat2"];
	$timestampfontcolor=$myrow["timestampfontcolor"];
	$timestampfontsize=$myrow["timestampfontsize"];
	$timestampfont=$myrow["timestampfont"];
	$timestampstyle=$myrow["timestampstyle"];
	$timestampbgcolor=$myrow["timestampbgcolor"];
	$globalheading=$myrow["eventheading"];
	$headingbgcolor=$myrow["headingbgcolor"];
	$headingfontcolor=$myrow["headingfontcolor"];
	$headingfont=$myrow["headingfont"];
	$headingfontsize=$myrow["headingfontsize"];
	$bordercolor=$myrow["bordercolor"];
	$contentbgcolor=$myrow["contentbgcolor"];
	$contentfontcolor=$myrow["contentfontcolor"];
	$contentfont=$myrow["contentfont"];
	$contentfontsize=$myrow["contentfontsize"];
	$TableWidth=$myrow["TableWidth"];
	$newsheadingbgcolor=$myrow["newsheadingbgcolor"];
	$newsheadingfontcolor=$myrow["newsheadingfontcolor"];
	$newsheadingstyle=$myrow["newsheadingstyle"];
	$newsheadingfont=$myrow["newsheadingfont"];
	$newsheadingfontsize=$myrow["newsheadingfontsize"];
	$displayposter=$myrow["displayposter"];
	$posterbgcolor=$myrow["posterbgcolor"];
	$posterfontcolor=$myrow["posterfontcolor"];
	$posterfont=$myrow["posterfont"];
	$posterfontsize=$myrow["posterfontsize"];
	$posterstyle=$myrow["posterstyle"];
	$copyrightbgcolor=$myrow["copyrightbgcolor"];
	$copyrightfontcolor=$myrow["copyrightfontcolor"];
	$copyrightfont=$myrow["copyrightfont"];
	$copyrightfontsize=$myrow["copyrightfontsize"];
	$attachpic=$myrow["attachpic"];
	$newsletteralign=$myrow["newsletteralign"];
	$newsletterbgcolor=$myrow["newsletterbgcolor"];
	$newslettercustomheader=$myrow["newslettercustomheader"];
	$newslettercustomfooter=$myrow["newslettercustomfooter"];
	@mysql_free_result($myrow);
	$heading=stripslashes($heading);
	$eventtext=stripslashes($eventtext);
	$subject=stripslashes($subject);
	list($tmpdate, $tmptime)=explode(" ",$eventdate);
	list($year, $month, $day) = explode("-", $tmpdate);
	list($hour, $min, $sec) = explode(":", $tmptime);
	$temptime=mktime($hour,$min,$sec,$month,$day,$year);
	$link_date=date("Y-m-d",$temptime);
	if(($hour>0) || ($min>0))
		$displaydate=date($event_dateformat2,mktime($hour,$min,0,$month,$day,$year));
	else
		$displaydate=date($event_dateformat,mktime(0,0,0,$month,$day,$year));
	$actdate = date("Y-m-d H:i:s");
	$asc_mailmsg="$displaydate:".$crlf;
	$html_mailmsg="<html>";
	switch($newsletteralign)
	{
		case 0:
			$tblalign="left";
			break;
		case 1:
			$tblalign="right";
			break;
		default:
			$tblalign="center";
			break;
	}
	if($newsletteralign<2)
	{
		$html_mailmsg.= "<head>";
		$html_mailmsg.= "<style>";
		$html_mailmsg.= "table.sntable {";
		$html_mailmsg.= "float: $tblalign;";
		$html_mailmsg.= "}";
		$html_mailmsg.= "</style>";
		$html_mailmsg.= "</head>";
	}
	$html_mailmsg.="<body bgcolor=\"$newsletterbgcolor\">";
	if($newslettercustomheader)
	{
		$html_mailmsg.="<div align=\"$tblalign\">";
		if($newsletterattachinlinepix==1)
			$newslettercustomheader=recode_img_for_emails($newslettercustomheader);
		$html_mailmsg.=$newslettercustomheader;
		$html_mailmsg.="</div>";
	}
	$html_mailmsg.="<div align=\"$tblalign\"><table width=\"$TableWidth\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\" align=\"$tblalign\" valign=\"top\" class=\"sntable\">";
	$html_mailmsg.="<tr><td bgcolor=\"$bordercolor\">";
	$html_mailmsg.="<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" width=\"100%\">";
	if(strlen($globalheading)>0)
	{
		$html_mailmsg.="<tr bgcolor=\"$headingbgcolor\" align=\"center\">";
		$html_mailmsg.="<td";
		if($newsletternoicons==0)
			$html_mailmsg.=" colspan=\"2\"";
		$html_mailmsg.=" align=\"center\" valign=\"middle\"><font face=\"$headingfont\" size=\"$headingfontsize\" color=\"$headingfontcolor\"><b>$globalheading</b></font></td></tr>";
	}
	if($newsletternoicons==0)
	{
		$html_mailmsg.="<tr>";
		$html_mailmsg.="<td width=\"2%\" height=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\">";
		if($icon)
			$html_mailmsg.=str_replace($url_gfx."/","","<img src=\"$url_icons/$icon\" border=\"0\" align=\"middle\">");
		else
			$html_mailmsg.="&nbsp;";
		$html_mailmsg.="</td>";
		$html_mailmsg.="<td  align=\"center\"><table width=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\" cellspacing=\"0\" cellpadding=\"0\">";
	}
	$html_mailmsg.="<tr bgcolor=\"$timestampbgcolor\"><td align=\"left\">";
	$html_mailmsg.="<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">";
	$html_mailmsg.=get_start_tag($timestampstyle);
	$html_mailmsg.=$displaydate;
	$html_mailmsg.=get_end_tag($timestampstyle);
	$html_mailmsg.="</font></td></tr>";
	$entrylink=$simpnews_fullurl."events.php?$langvar=$eventlang&category=$eventcat&link_date=".$link_date;
	if(strlen($heading)>0)
	{
		$html_mailmsg.="<tr bgcolor=\"$newsheadingbgcolor\"><td align=\"left\">";
		$html_mailmsg.="<font face=\"$newsheadingfont\" size=\"$newsheadingfontsize\" color=\"$newsheadingfontcolor\">";
		if($newsletterlinking==1)
			$html_mailmsg.="<a href=\"".$entrylink."\">";
		$html_mailmsg.=get_start_tag($newsheadingstyle);
		$html_mailmsg.=do_htmlentities($heading);
		$html_mailmsg.=get_end_tag($newsheadingstyle);
		if($newsletterlinking==1)
			$html_mailmsg.="</a>";
		$html_mailmsg.="</font></td></tr>";
		$asc_heading=strip_tags($heading);
		$asc_mailmsg.=$asc_heading;
		$asc_mailmsg.=$crlf;
		for($i=0;$i<strlen($asc_heading);$i++)
			$asc_mailmsg.="-";
		$asc_mailmsg.=$crlf;
	}
	$html_mailmsg.="<tr bgcolor=\"$contentbgcolor\"><td align=\"left\">";
	$html_mailmsg.="<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	$html_event=stripslashes($eventtext);
	$html_event=undo_htmlspecialchars($html_event);
	if($newsletterlinking==2)
		$html_event.="<br><a href=\"".$entrylink."\">".$l_mail_readmore."</a>";
	if($newsletterattachinlinepix==1)
		$html_event=recode_img_for_emails($html_event);
	$asc_eventtext=str_replace("<BR>",$crlf,$html_event);
	$html_mailmsg.=$html_event."</font></td></tr>";
	$asc_eventtext=undo_htmlentities($asc_eventtext);
	$asc_eventtext=strip_tags($asc_eventtext);
	if($newsletterlinking!=0)
		$asc_eventtext.=$crlf."[".$entrylink."]";
	$asc_mailmsg.=$asc_eventtext.$crlf;
	if($displayposter && (strlen($poster)>0))
	{
		$html_mailmsg.="<tr bgcolor=\"$posterbgcolor\"><td align=\"left\">";
		$html_mailmsg.="<font face=\"$posterfont\" size=\"$posterfontsize\" color=\"$posterfontcolor\">";
		$html_mailmsg.=get_start_tag($posterstyle);
		$html_mailmsg.="$l_poster: ".do_htmlentities($poster);
		$html_mailmsg.=get_end_tag($posterstyle);
		$html_mailmsg.="</font></td></tr>";
	}
	if($mailattach==0)
	{
		$attachsql="select f.filename, f.filesize, f.mimetype, eva.* from ".$tableprefix."_events_attachs eva, ".$tableprefix."_files f where f.entrynr=eva.attachnr and eva.eventnr=$eventnr";
		if(!$attachresult = mysql_query($attachsql, $db))
			die("Could not connect to the database.");
		if($attachrow=mysql_fetch_array($attachresult))
		{
			do{
				$html_mailmsg.="<tr bgcolor=\"$contentbgcolor\">";
				$html_mailmsg.="<td align=\"left\">";
				$html_mailmsg.="<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
				$fileinfo=$attachrow["filename"]." (".format_bytes($attachrow["filesize"]).")";
				$mimesql="select * from ".$tableprefix."_mimetypes where mimetype='".$attachrow["mimetype"]."'";
				$imgtxt="";
				if(!$mimeresult = mysql_query($mimesql, $db))
					die("Could not connect to the database.");
				if($mimerow=mysql_fetch_array($mimeresult))
				{
					$fdsql="select * from ".$tableprefix."_filetypedescription where language='$eventlang' and mimetype=".$mimerow["entrynr"];
					if(!$fdresult = mysql_query($fdsql, $db))
						die("Could not connect to the database.");
					if($fdrow=mysql_fetch_array($fdresult))
						$dfileinfo=$fdrow["description"].": ".$fileinfo;
					else
						$dfileinfo=$fileinfo;
					if($mimerow["icon"])
						$imgtxt="<img class=\"attach\" src=\"$url_gfx/".$mimerow["icon"]."\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\"> ";
					else
						$imgtxt="<img src=\"$url_gfx/$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\">";
				}
				else
					$imgtxt="<img src=\"$url_gfx/$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$fileinfo\" alt=\"$fileinfo\">";
				if($newsletternoicons==0)
				{
					$html_mailmsg.="&nbsp;<a href=\"".$simpnews_fullurl."sndownload.php?entrynr=".$attachrow["attachnr"]."\" target=\"_blank\">";
					$html_mailmsg.=str_replace($url_gfx."/","",$imgtxt);
					$html_mailmsg.="</a>&nbsp; ";
				}
				else
					$html_mailmsg.="Attachement:&nbsp;";
				$html_mailmsg.="<a href=\"".$simpnews_fullurl."sndownload.php?entrynr=".$attachrow["attachnr"]."\" target=\"_blank\">Download</a></font></td></tr>";
				$html_mailmsg.="</td></tr>";
				$asc_mailmsg.= "Download attachement: ".$simpnews_fullurl."sndownload.php?entrynr=".$attachrow["attachnr"].$crlf;
			}while($attachrow=mysql_fetch_array($attachresult));
			@mysql_free_result($attachresult);
		}
	}
	$asc_mailmsg.=$crlf;
	if($newsletternoicons==0)
		$html_mailmsg.="</table></td></tr>";
	$html_mailmsg.="<TR BGCOLOR=\"$copyrightbgcolor\" ALIGN=\"CENTER\">";
	$html_mailmsg.="<TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"";
	if($newsletternoicons==0)
		$html_mailmsg.=" colspan=\"2\"";
	$html_mailmsg.="><font face=\"$copyrightfont\" size=\"$copyrightfontsize\" color=\"$copyrightfontcolor\">";
	$html_mailmsg.="Powered by SimpNews $version</td></tr>";
	$html_mailmsg.="</table></td></tr></table></div><br clear=\"all\">";
	if($newslettercustomfooter)
	{
		$html_mailmsg.="<div align=\"$tblalign\">";
		if($newsletterattachinlinepix==1)
			$newslettercustomfooter=recode_img_for_emails($newslettercustomfooter);
		$html_mailmsg.=$newslettercustomfooter;
		$html_mailmsg.="</div>";
	}
	if($newsletternoicons==0)
		$html_mailmsg=recode_emoticons_for_emails($html_mailmsg);
	$sql = "select * from ".$tableprefix."_subscriptions where (confirmed=1 and language='$eventlang') and (category=$eventcat";
	if($cat_excludefromnewsletter==0)
		$sql.=" or category=0";
	$sql.=") group by subscriptionnr";
	if(!$result = mysql_query($sql, $db))
	    die("Unable to connect to database.".mysql_error());
	$sentmails=0;
	if($showsendprogress==1)
		echo "<div id=\"progressbox\" class=\"progress\">$l_sendingmail: ";
	$rcvdat=array();
	while($myrow=mysql_fetch_array($result))
		array_push($rcvdat,$myrow);
	@mysql_free_result($result);
	for($y=0;$y<count($rcvdat);$y++)
	{
		if(!$insafemode)
			@set_time_limit($msendlimit);
		$myrow=$rcvdat[$y];
		if($showsendprogress==1)
		{
			if(($sentmails%100)==0)
				echo "<br>";
			echo "&bull;";
			flush();
		}
		$mail = new htmlMimeMail();
		$sql2="select * from ".$tableprefix."_layout where lang='$eventlang' and deflayout=1";
		if(!$result2 = mysql_query($sql2, $db))
		    die("Unable to connect to database.".mysql_error());
		if(!$myrow2=mysql_fetch_array($result2))
			die("Layout setup error");
		$unsubscribeurl=$simpnews_fullurl."subscription.php?$langvar=$eventlang&id=".$myrow["unsubscribeid"]."&mode=remove&email=".$myrow["email"];
		$unsubscribeurl_html="<font face=\"Verdana, Geneva, Arial, Helvetica, sans-serif\" size=\"1\"><a href=\"$unsubscribeurl\">$unsubscribeurl</a></font>";
		if($myrow2["emailremark"])
			$html_remark=str_replace("{unsubscribeurl}",$unsubscribeurl_html,$myrow2["emailremark"]);
		else
			$html_remark="unsubscribe: $unsubscribeurl_html";
		$html_remark=str_replace("\n","<br>",$html_remark);
		$html_remark=str_replace("\r","",$html_remark);
		if($myrow2["emailremark"])
			$asc_remark=str_replace("{unsubscribeurl}",$unsubscribeurl,strip_tags($myrow2["emailremark"]));
		else
			$asc_remark="unsubscribe: $unsubscribeurl";
		$asc_remark=str_replace("\n",$crlf,$asc_remark);
		$userhtmlbody=$html_mailmsg;
		if(strlen($html_remark)>0)
			$userhtmlbody.="<hr>$html_remark<br><br>";
		if($myrow2["defsignature"])
		{
			$userhtmlbody.="---<BR>";
			$html_sig=str_replace("\n","<BR>",$myrow2["defsignature"]);
			$html_sig=str_replace("\r","",$html_sig);
			$userhtmlbody.=$html_sig;
		}
		$userhtmlbody.="</body></html>";
		$userhtmlbody=remove_spcode_markers($userhtmlbody);
		$userascbody=$asc_mailmsg;
		if(strlen($asc_remark)>0)
			$userascbody.="---------------------------".$crlf.$asc_remark.$crlf.$crlf;
		if($myrow2["defsignature"])
			$userascbody.="---".$crlf.str_replace("\n",$crlf,$myrow2["defsignature"]);
		$mail->setCrlf($crlf);
		$mail->setTextWrap($mailmaxlinelength);
		$mail->setTextCharset($contentcharset);
		if($myrow["emailtype"]==0)
		{
			$mail->setHTMLCharset($contentcharset);
			if(($newsletternoicons==0) || ($newsletterattachinlinepix==1))
				$mail->setHTML($userhtmlbody,$userascbody,$path_gfx."/");
			else
				$mail->setHTML($userhtmlbody,$userascbody);
	    	}
	    	else
	    	{
    			$mail->setText($userascbody);
	    	}
	    	if($mailattach==1)
	    	{
	    		$tmpsql="select files.* from ".$tableprefix."_events_attachs eva, ".$tableprefix."_files files where files.entrynr=eva.attachnr and eva.eventnr=$eventnr";
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
		if($sendername)
			$fromadr="\"$sendername\" <$sender>";
		else
			$fromadr=$sender;
	    	$mail->setSubject($subject);
	    	$mail->setFrom($fromadr);
	    	if(!$insafemode)
			@set_time_limit($msendlimit);
		$toadr=array();
		array_push($toadr,$myrow["email"]);
		if($use_smtpmail)
		{
			$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
	        	$sendresult=$mail->send($toadr, "smtp");
		}
		else
	    		$sendresult=$mail->send($toadr, "mail");
	    	$addlogtxt="email_single_event - subscription#: ".$myrow["subscriptionnr"]." - admin: ".$userdata["username"];
	    	$addlogtxt.=" - event# $eventnr";
	    	$addlogtxt.=" - cat# $eventcat";
	    	do_emaillog($sendresult,$myrow["email"],$addlogtxt);
		if(!$sendresult)
		{
			echo "<br>Unable to send email for ".$myrow["email"]." (#".$myrow["subscriptionnr"].")";
			if($use_smtpmail)
			{
				echo "<br>";
				print_array($mail->errors);
			}
			if($emailerrordie==1)
				die();
			else
				echo "<br>";
		}
		@mysql_free_result($result2);
		$sql2="update ".$tableprefix."_subscriptions set lastsent='$actdate' where subscriptionnr=".$myrow["subscriptionnr"];
		if(!$result2 = mysql_query($sql2, $db))
		    die("Unable to connect to database.".mysql_error());
		$sentmails++;
		if(($sendnewsdelay>0) && (($sentmails%$senddelayinterval)==0))
		{
			if(!$insafemode)
			{
				if($msendlimit>($sendnewsdelay/1000+2))
					@set_time_limit($msendlimit);
				else
					@set_time_limit($sendnewsdelay/1000+2);
			}
			usleep($sendnewsdelay);
		}
	}
	if($eventcat!=0)
	{
		$sql="update ".$tableprefix."_categories set nlsenddate='$actdate' where catnr=$eventcat";
		if(!$result = mysql_query($sql, $db))
		    die("Unable to connect to database.".mysql_error());

	}
	if($showsendprogress==1)
	{
		echo "<br>";
		echo "<a href=\"javascript:hideprogressbox()\"";
		echo " class=\"actionlink\">$l_hideprogressbox</a>";
		echo "</div>\n";
		echo "<script type=\"text/javascript\" language=\"javascript\">\n";
		echo "<!--\n";
		echo "	hideprogressbox();\n";
		echo "// -->\n";
		echo "</script>\n";
		flush();
	}
	return $sentmails;
}

function email_single_announce($heading, $announcetext, $announcelang, $db, $subject, $sender, $sendername, $announcedate, $poster, $announcenr, $icon, $announcecat)
{
	global $path_attach, $path_simpnews, $url_icons, $url_gfx, $path_gfx, $tableprefix, $simpnews_fullurl, $l_poster, $copyright_url, $copyright_note, $contentcharset, $version;
	global $use_smtpmail, $smtpserver, $smtpport, $smtpsenderdomain, $smtpauth, $smtpuser, $smtppasswd, $servertimezone, $displaytimezone, $newsletternoicons;
	global $langvar, $mailattach, $attach_in_fs, $msendlimit, $newsletterattachinlinepix, $l_sendingmail, $sendnewsdelay;
	global $showsendprogress, $senddelayinterval, $sendprogressautohide, $l_hideprogressbox;
	global $l_announcement, $l_global_announcement, $crlf, $emailerrordie, $userdata, $insafemode, $newsletterlinking, $mailmaxlinelength;
	
	include_once('../includes/htmlMimeMail.inc');
	if($use_smtpmail)
	{
		include_once('../includes/smtp.inc');
		include_once('../includes/RFC822.inc');
	}
	include("./language/mail_".$announcelang.".php");
	$cat_excludefromnewsletter=0;
	if($announcecat>0)
	{
		$tmpsql="select * from ".$tableprefix."_categories where catnr=$announcecat";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("Unable to connect to database.".mysql_error());
		if($tmprow=mysql_fetch_array($tmpresult))
			$cat_excludefromnewsletter=$tmprow["excludefromnewsletter"];
		@mysql_free_result($tmpresult);
	}
	$sql="select * from ".$tableprefix."_layout where lang='$announcelang' and deflayout=1";
	if(!$result = mysql_query($sql, $db))
	    die("Unable to connect to database.".mysql_error());
	if(!$myrow=mysql_fetch_array($result))
		die("Layout setup error");
	$dateformat=$myrow["dateformat"];
	$timestampfontcolor=$myrow["timestampfontcolor"];
	$timestampfontsize=$myrow["timestampfontsize"];
	$timestampfont=$myrow["timestampfont"];
	$timestampstyle=$myrow["timestampstyle"];
	$timestampbgcolor=$myrow["timestampbgcolor"];
	$globalheading=$myrow["heading"];
	$headingbgcolor=$myrow["headingbgcolor"];
	$headingfontcolor=$myrow["headingfontcolor"];
	$headingfont=$myrow["headingfont"];
	$headingfontsize=$myrow["headingfontsize"];
	$bordercolor=$myrow["bordercolor"];
	$contentbgcolor=$myrow["contentbgcolor"];
	$contentfontcolor=$myrow["contentfontcolor"];
	$contentfont=$myrow["contentfont"];
	$contentfontsize=$myrow["contentfontsize"];
	$TableWidth=$myrow["TableWidth"];
	$newsheadingbgcolor=$myrow["newsheadingbgcolor"];
	$newsheadingfontcolor=$myrow["newsheadingfontcolor"];
	$newsheadingstyle=$myrow["newsheadingstyle"];
	$newsheadingfont=$myrow["newsheadingfont"];
	$newsheadingfontsize=$myrow["newsheadingfontsize"];
	$displayposter=$myrow["displayposter"];
	$posterbgcolor=$myrow["posterbgcolor"];
	$posterfontcolor=$myrow["posterfontcolor"];
	$posterfont=$myrow["posterfont"];
	$posterfontsize=$myrow["posterfontsize"];
	$posterstyle=$myrow["posterstyle"];
	$copyrightbgcolor=$myrow["copyrightbgcolor"];
	$copyrightfontcolor=$myrow["copyrightfontcolor"];
	$copyrightfont=$myrow["copyrightfont"];
	$copyrightfontsize=$myrow["copyrightfontsize"];
	$attachpic=$myrow["attachpic"];
	$newsletterbgcolor=$myrow["newsletterbgcolor"];
	$newslettercustomheader=$myrow["newslettercustomheader"];
	$newslettercustomfooter=$myrow["newslettercustomfooter"];
	$newsletteralign=$myrow["newsletteralign"];
	$announcepic=$myrow["announcepic"];
	$gannouncepic=$myrow["gannouncepic"];
	@mysql_free_result($myrow);
	$heading=stripslashes($heading);
	$announcetext=stripslashes($announcetext);
	$subject=stripslashes($subject);
	list($mydate,$mytime)=explode(" ",$announcedate);
	list($year, $month, $day) = explode("-", $mydate);
	list($hour, $min, $sec) = explode(":",$mytime);
	$temptime=mktime($hour,$min,$sec,$month,$day,$year);
	$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
	$displaydate=date($dateformat,$temptime);
	$actdate = date("Y-m-d H:i:s");
	$asc_mailmsg="$displaydate:".$crlf;
	$html_mailmsg="<html>";
	switch($newsletteralign)
	{
		case 0:
			$tblalign="left";
			break;
		case 1:
			$tblalign="right";
			break;
		default:
			$tblalign="center";
			break;
	}
	if($newsletteralign<2)
	{
		$html_mailmsg.= "<head>\n";
		$html_mailmsg.= "<style>\n";
		$html_mailmsg.= "table.sntable {\n";
		$html_mailmsg.= "  float: $tblalign;\n";
		$html_mailmsg.= "}\n";
		$html_mailmsg.= "</style>\n";
		$html_mailmsg.= "</head>\n";
	}
	$html_mailmsg.="<body bgcolor=\"$newsletterbgcolor\">";
	if($newslettercustomheader)
	{
		$html_mailmsg.="<div align=\"$tblalign\">";
		if($newsletterattachinlinepix==1)
			$newslettercustomheader=recode_img_for_emails($newslettercustomheader);
		$html_mailmsg.=$newslettercustomheader;
		$html_mailmsg.="</div>";
	}
	$html_mailmsg.="<div align=\"$tblalign\"><table width=\"$TableWidth\" border=\"0\" CELLPADDING=\"1\" CELLSPACING=\"0\" ALIGN=\"$tblalign\" VALIGN=\"TOP\" class=\"sntable\">".$crlf;
	$html_mailmsg.="<tr><TD BGCOLOR=\"$bordercolor\">".$crlf;
	$html_mailmsg.="<TABLE BORDER=\"0\" CELLPADDING=\"1\" CELLSPACING=\"1\" WIDTH=\"100%\">".$crlf;
	if(strlen($globalheading)>0)
	{
		$html_mailmsg.="<TR BGCOLOR=\"$headingbgcolor\" ALIGN=\"CENTER\">".$crlf;
		$html_mailmsg.="<TD";
		if($newsletternoicons==0)
			$html_mailmsg.=" colspan=\"2\"";
		$html_mailmsg.=" ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"><font face=\"$headingfont\" size=\"$headingfontsize\" color=\"$headingfontcolor\"><b>$globalheading</b></font></td></tr>".$crlf;
	}
	if($newsletternoicons==0)
	{
		$html_mailmsg.="<tr>".$crlf;
		$html_mailmsg.="<td width=\"2%\" height=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\">".$crlf;
		if($icon)
			$html_mailmsg.=str_replace($url_gfx."/","","<img src=\"$url_icons/$icon\" border=\"0\" align=\"middle\"> ".$crlf);
		else
			$html_mailmsg.="&nbsp;";
		$html_mailmsg.="</td>";
		$html_mailmsg.="<td  align=\"center\"><table width=\"100%\" align=\"center\" bgcolor=\"$contentbgcolor\" cellspacing=\"0\" cellpadding=\"0\">".$crlf;
	}
	$html_mailmsg.="<tr bgcolor=\"$timestampbgcolor\"><td align=\"left\">".$crlf;
	if($newsletternoicons==0)
	{
		if($announcecat==0)
			$html_mailmsg.="<img src=\"$gannouncepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_global_announcement\" title=\"$l_global_announcement\"> ";
		else
			$html_mailmsg.="<img src=\"$announcepic\" border=\"0\" align=\"absmiddle\" alt=\"$l_announcement\" title=\"$l_announcement\"> ";
	}
	$html_mailmsg.="<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">".$crlf;
	$html_mailmsg.=get_start_tag($timestampstyle);
	$html_mailmsg.=$displaydate;
	$html_mailmsg.=get_end_tag($timestampstyle);
	$html_mailmsg.="</font></td></tr>".$crlf;
	$entrylink=$simpnews_fullurl."announce.php?$langvar=$announcelang&category=$announcecat&announcenr=$announcenr";
	if(strlen($heading)>0)
	{
		$html_mailmsg.="<tr bgcolor=\"$newsheadingbgcolor\"><td align=\"left\">".$crlf;
		$html_mailmsg.="<font face=\"$newsheadingfont\" size=\"$newsheadingfontsize\" color=\"$newsheadingfontcolor\">".$crlf;
		if($newsletterlinking==1)
			$html_mailmsg.="<a href=\"".$entrylink."\">";
		$html_mailmsg.=get_start_tag($newsheadingstyle);
		$html_mailmsg.=do_htmlentities($heading);
		$html_mailmsg.=get_end_tag($newsheadingstyle);
		if($newsletterlinking==1)
			$html_mailmsg.="</a>";
		$html_mailmsg.="</font></td></tr>".$crlf;
		$asc_heading=strip_tags($heading);
		$asc_mailmsg.=$asc_heading;
		$asc_mailmsg.=$crlf;
		for($i=0;$i<strlen($asc_heading);$i++)
			$asc_mailmsg.="-";
		$asc_mailmsg.=$crlf;
	}
	$html_mailmsg.="<tr bgcolor=\"$contentbgcolor\"><td align=\"left\">".$crlf;
	$html_mailmsg.="<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">".$crlf;
	$html_news=stripslashes($announcetext);
	$html_news=undo_htmlspecialchars($html_news);
	if($newsletterlinking==2)
		$html_news.="<br><a href=\"".$entrylink."\">".$l_mail_readmore."</a>";
	if($newsletterattachinlinepix==1)
		$html_news=recode_img_for_emails($html_news);
	$asc_newstext=str_replace("<BR>",$crlf,$html_news);
	$html_news=str_replace("<BR>","<br>",$html_news);
	$html_mailmsg.=$html_news."</font></td></tr>".$crlf;
	$asc_newstext=undo_htmlentities($asc_newstext);
	$asc_newstext=strip_tags($asc_newstext);
	if($newsletterlinking!=0)
		$asc_newstext.=$crlf."[".$entrylink."]";
	$asc_mailmsg.=$asc_newstext.$crlf;
	if($displayposter && (strlen($poster)>0))
	{
		$html_mailmsg.="<tr bgcolor=\"$posterbgcolor\"><td align=\"left\">".$crlf;
		$html_mailmsg.="<font face=\"$posterfont\" size=\"$posterfontsize\" color=\"$posterfontcolor\">".$crlf;
		$html_mailmsg.=get_start_tag($posterstyle);
		$html_mailmsg.="$l_poster: ".do_htmlentities($poster);
		$html_mailmsg.=get_end_tag($posterstyle);
		$html_mailmsg.="</font></td></tr>".$crlf;
	}
	if($mailattach==0)
	{
		$attachsql="select f.filename, f.filesize, f.mimetype, ana.* from ".$tableprefix."_announce_attachs ana, ".$tableprefix."_files f where f.entrynr=ana.attachnr and ana.announcenr=$announcenr";
		if(!$attachresult = mysql_query($attachsql, $db))
			die("Could not connect to the database.");
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
					$fdsql="select * from ".$tableprefix."_filetypedescription where language='$announcelang' and mimetype=".$mimerow["entrynr"];
					if(!$fdresult = mysql_query($fdsql, $db))
						die("Could not connect to the database.");
					if($fdrow=mysql_fetch_array($fdresult))
						$dfileinfo=$fdrow["description"].": ".$fileinfo;
					else
						$dfileinfo=$fileinfo;
					if($mimerow["icon"])
						$imgtxt="<img class=\"attach\" src=\"$url_gfx/".$mimerow["icon"]."\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\"> ";
					else
						$imgtxt="<img src=\"$url_gfx/$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\">";
				}
				else
					$imgtxt="<img src=\"$url_gfx/$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$fileinfo\" alt=\"$fileinfo\">";
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
				$asc_mailmsg.= "Download attachement: ".$simpnews_fullurl."sndownload.php?entrynr=".$attachrow["attachnr"].$crlf;
			}while($attachrow=mysql_fetch_array($attachresult));
		}
		@mysql_free_result($attachresult);
	}
	$asc_mailmsg.=$crlf;
	if($newsletternoicons==0)
		$html_mailmsg.="</table></td></tr>";
	$html_mailmsg.="<TR BGCOLOR=\"$copyrightbgcolor\" ALIGN=\"CENTER\">".$crlf;
	$html_mailmsg.="<TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"";
	if($newsletternoicons==0)
		$html_mailmsg.=" colspan=\"2\"";
	$html_mailmsg.="><font face=\"$copyrightfont\" size=\"$copyrightfontsize\" color=\"$copyrightfontcolor\">".$crlf;
	$html_mailmsg.="Powered by SimpNews $version</td></tr>";
	$html_mailmsg.="</table></td></tr></table></div><br clear=\"all\">".$crlf;
	if($newslettercustomfooter)
	{
		$html_mailmsg.="<div align=\"$tblalign\">";
		if($newsletterattachinlinepix==1)
			$newslettercustomfooter=recode_img_for_emails($newslettercustomfooter);
		$html_mailmsg.=$newslettercustomfooter;
		$html_mailmsg.="</div>";
	}
	if($newsletternoicons==0)
		$html_mailmsg=recode_emoticons_for_emails($html_mailmsg);
	$sql = "select * from ".$tableprefix."_subscriptions where (confirmed=1 and language='$announcelang') ";
	if($announcecat>0)
	{
		$sql.= "and (category=$announcecat";
		if($cat_excludefromnewsletter==0)
			$sql.=" or category=0";
		$sql.=") ";
	}
	$sql.="group by subscriptionnr";
	if(!$result = mysql_query($sql, $db))
	    die("Unable to connect to database.".mysql_error());
	$sentmails=0;
	if($showsendprogress==1)
		echo "<div id =\"progressbox\" class=\"progress\">$l_sendingmail: ";
	$rcvdat=array();
	while($myrow=mysql_fetch_array($result))
		array_push($rcvdat,$myrow);
	@mysql_free_result($result);
	for($y=0;$y<count($rcvdat);$y++)
	{
		if(!$insafemode)
			@set_time_limit($msendlimit);
		$myrow=$rcvdat[$y];
		if($showsendprogress==1)
		{
			if(($sentmails%100)==0)
				echo "<br>";
			echo "&bull;";
			flush();
		}
		$mail = new htmlMimeMail();
		$sql2="select * from ".$tableprefix."_layout where lang='$announcelang' and deflayout=1";
		if(!$result2 = mysql_query($sql2, $db))
		    die("Unable to connect to database.".mysql_error());
		if(!$myrow2=mysql_fetch_array($result2))
			die("Layout setup error");
		$unsubscribeurl=$simpnews_fullurl."subscription.php?$langvar=$announcelang&id=".$myrow["unsubscribeid"]."&mode=remove&email=".$myrow["email"];
		$unsubscribeurl_html="<font face=\"Verdana, Geneva, Arial, Helvetica, sans-serif\" size=\"1\"><a href=\"$unsubscribeurl\">$unsubscribeurl</a></font>";
		if($myrow2["emailremark"])
			$html_remark=str_replace("{unsubscribeurl}",$unsubscribeurl_html,$myrow2["emailremark"]);
		else
			$html_remark="unsubscribe: $unsubscribeurl_html";
		$html_remark=str_replace("\n","<br>".$crlf,$html_remark);
		if($myrow2["emailremark"])
			$asc_remark=str_replace("{unsubscribeurl}",$unsubscribeurl,strip_tags($myrow2["emailremark"]));
		else
			$asc_remark="unsubscribe: $unsubscribeurl";
		$asc_remark=str_replace("\n",$crlf,$asc_remark);
		$userhtmlbody=$html_mailmsg;
		if(strlen($html_remark)>0)
			$userhtmlbody.="<hr>$html_remark<br><br>".$crlf;
		$userhtmlbody.="---<BR>".str_replace("\n","<BR>".$crlf,$myrow2["defsignature"]).$crlf;
		$userhtmlbody.="</body></html>";
		$userascbody=$asc_mailmsg;
		if(strlen($asc_remark)>0)
			$userascbody.="---------------------------".$crlf.$asc_remark.$crlf.$crlf;
		$userascbody.="---".$crlf.str_replace("\n",$crlf,$myrow2["defsignature"]);
		$mail->setCrlf($crlf);
		$mail->setTextWrap($mailmaxlinelength);
		$mail->setTextCharset($contentcharset);
		if($myrow["emailtype"]==0)
		{
			$mail->setHTMLCharset($contentcharset);
			if(($newsletternoicons==0) || ($newsletterattachinlinepix==1))
				$mail->setHTML($userhtmlbody,$userascbody,$path_gfx."/");
			else
				$mail->setHTML($userhtmlbody,$userascbody);
		}
		else
		{
	    		$mail->setText($userascbody);
		}
		if($mailattach==1)
		{
		    	$tmpsql="select files.* from ".$tableprefix."_announce_attachs ana, ".$tableprefix."_files files where files.entrynr=ana.attachnr and ana.announcenr=$announcenr";
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
		if($sendername)
			$fromadr="\"$sendername\" <$sender>";
		else
			$fromadr=$sender;
		$mail->setSubject($subject);
		$mail->setFrom($fromadr);
		$receiver=array();
		array_push($receiver,$myrow["email"]);
		if($use_smtpmail)
		{
			$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
			$sendresult=$mail->send($receiver, "smtp");
		}
		else
			$sendresult=$mail->send($receiver, "mail");
	    	$addlogtxt="email_single_announce - subscription#: ".$myrow["subscriptionnr"]." - admin: ".$userdata["username"];
	    	$addlogtxt.=" - announcement# $announcenr";
	    	$addlogtxt.=" - cat# $announcecat";
		do_emaillog($sendresult,$myrow["email"],$addlogtxt);
		if(!$sendresult)
		{
			echo "<br>Unable to send email for ".$myrow["email"]." (#".$myrow["subscriptionnr"].")";
			if($use_smtpmail)
			{
				echo "<br>";
				print_array($mail->errors);
			}
			if($emailerrordie==1)
				die();
			else
				echo "<br>";
		}
		$sql2="update ".$tableprefix."_subscriptions set lastsent='$actdate' where subscriptionnr=".$myrow["subscriptionnr"];
		if(!$result2 = mysql_query($sql2, $db))
		    die("Unable to connect to database.".mysql_error());
		$sentmails++;
		if(($sendnewsdelay>0) && (($sentmails%$senddelayinterval)==0))
		{
			if(!$insafemode)
			{
				if($msendlimit>($sendnewsdelay/1000+2))
					@set_time_limit($msendlimit);
				else
					@set_time_limit($sendnewsdelay/1000+2);
			}
			usleep($sendnewsdelay);
		}
	}
	if($showsendprogress==1)
	{
		echo "<br>";
		echo "<a href=\"javascript:hideprogressbox()\"";
		echo " class=\"actionlink\">$l_hideprogressbox</a>";
		echo "</div>\n";
		echo "<script type=\"text/javascript\" language=\"javascript\">\n";
		echo "<!--\n";
		echo "	hideprogressbox();\n";
		echo "// -->\n";
		echo "</script>\n";
		flush();
	}
	return $sentmails;
}
?>