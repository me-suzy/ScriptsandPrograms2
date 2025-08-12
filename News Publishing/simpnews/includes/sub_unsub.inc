<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
		include_once('./includes/htmlMimeMail.inc');
		if($use_smtpmail)
		{
			include_once('./includes/smtp.inc');
			include_once('./includes/RFC822.inc');
		}
		$errors=0;
		$errmsg="";
		if(!isset($email) || !$email)
		{
			$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.="$l_noemail</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$sql="select * from ".$tableprefix."_subscriptions where email='$email' and confirmed=1 and category='$newscat'";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if(!$myrow=mysql_fetch_array($result))
			{
				$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
				$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
				$errmsg.="$l_noremoveentry</td></tr>";
				$errors=1;
			}
		}
		if($errors==1)
		{
			include_once('./includes/head.inc');
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_subscriptionprelude?></font></td></tr>
<?php
			echo $errmsg;
			echo "<tr bgcolor=\"$headingbgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			echo "<a class=\"actionlink\" href=\"javascript:history.back()\">$l_back</a></td></tr>";
			echo "</table></td></tr></table>";
			include_once("./includes/footer.inc");
			exit;
		}
		$confirmurl=$simpnews_fullurl."subscription.php?$langvar=$act_lang&mode=remove&email=$email&id=".$myrow["unsubscribeid"];
		$tmpsql="select * from ".$tableprefix."_texts where textid='usnot' and lang='".$myrow["language"]."'";
		if(!$tmpresult = mysql_query($tmpsql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		if($tmprow=mysql_fetch_array($tmpresult))
		{
			$mailmsg=stripslashes($tmprow["text"]);
			$mailmsg=undo_htmlspecialchars($mailmsg);
			$mailmsg_html=stripslashes($tmprow["text"]);
		}
		else
		{
			$mailmsg = $l_unsubscriptionconfirmmail;
			$mailmsg_html = $l_unsubscriptionconfirmmail_html;
		}
		$mailmsg = str_replace("{sitename}",$sitename,$mailmsg);
		$mailmsg = str_replace("{confirmurl}",$confirmurl,$mailmsg);
		$mailmsg.= "\n\n---\n$defsignature\n\n\n";
		$mailmsg = str_replace("\n",$crlf,$mailmsg);
		$mailmsg_html = str_replace("{sitename}",$sitename,$mailmsg_html);
		$mailmsg_html = str_replace("{confirmurl}",$confirmurl,$mailmsg_html);
		$mailmsg_html.= "\n\n<hr>\n$defsignature\n\n\n";
		$mailmsg_html = str_replace("\n","<br>".$crlf,$mailmsg_html);
		$mailmsg_html = undo_htmlspecialchars($mailmsg_html);
		$subject = $l_unsubscriptionconfirmsubject;
		$subject = str_replace("{sitename}",$sitename,$subject);
		if($simpnewsmailname)
			$fromadr="\"$simpnewsmailname\" <$simpnewsmail>";
		else
			$fromadr=$simpnewsmail;
		$mail = new htmlMimeMail();
		$mail->setCrlf($crlf);
		$mail->setTextWrap($mailmaxlinelength);
		$mail->setTextCharset($contentcharset);
		if($myrow["emailtype"]==0)
		{
			$mail->setHTMLCharset($contentcharset);
			$mail->setHTML($mailmsg_html,$mailmsg);
		}
		else
		{
	    		$mail->setText($mailmsg);
		}
		$mail->setSubject($subject);
		$mail->setFrom($fromadr);
		$receiver=array();
		array_push($receiver,$myrow["email"]);
		if(!$insafemode)
			@set_time_limit($msendlimit);
		if($use_smtpmail)
		{
			$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
			$sendresult=$mail->send($receiver, "smtp");
		}
		else
			$sendresult=$mail->send($receiver, "mail");
		do_emaillog($sendresult,$myrow["email"],"unsubscribe newsletter");
		$redirect=1;
		include_once('./includes/head.inc');
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_subscriptionprelude?></font></td></tr>
<?php
		echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo "$l_unsubscribesent</td></tr>";
		if($subredirecturl)
			$backurl=$subredirecturl;
		else if(!isset($backurl))
		{
			if(!isset($category))
				$category=0;
			$backurl="news.php?$langvar=$act_lang&amp;layout=$layout&amp;category=$category";
		}
		if($redirectdelay>=0)
		{
			echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			echo "$l_redirected</font></td></tr>";
		}
		echo "<tr bgcolor=\"$headingbgcolor\" align=\"center\">";
		echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$headingfontcolor\">";
		echo "<a class=\"actionlink\" href=\"$backurl\">$l_news</a></font></td></tr>";
		echo "</table></td></tr></table>";
		include_once("./includes/footer.inc");
		exit;
?>