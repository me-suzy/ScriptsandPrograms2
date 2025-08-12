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
		$poster=trim($poster);
		$comment=trim($comment);
		if(!isset($postingid) || !$postingid)
		{
			echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			echo "$l_callingerror</td></tr>";
			echo "</table></td></tr></table>";
			include_once("./includes/footer.inc");
			exit;
		}
		if(!isset($entryref) || !$entryref)
		{
			echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			echo "$l_callingerror</td></tr>";
			echo "</table></td></tr></table>";
			include_once("./includes/footer.inc");
			exit;
		}
		if(!isset($poster) || !$poster)
		{
			$errors=1;
			$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.="$l_noname</td></tr>";
		}
		if(!isset($comment) || !$comment)
		{
			$errors=1;
			$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.="$l_nocomment</td></tr>";
		}
		if(!isset($email))
			$email="";
		if($email)
		{
			if(!validate_email($email))
			{
				$errors=1;
				$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
				$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
				$errmsg.="$l_novalidemail</td></tr>";
			}
		}
		if($errors==0)
		{
			$sql="select * from ".$tableprefix."_comments where postingid='".$postingid."'";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
			if($tmprow=mysql_fetch_array($result))
			{
				$errors=1;
				$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
				$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
				$errmsg.="$l_noflooding</td></tr>";
			}
		}
		if($errors==1)
		{
			include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_writecomment?></font></td></tr>
<?php
			echo $errmsg;
			echo "<tr bgcolor=\"$headingbgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			echo "<a class=\"actionlink\" href=\"javascript:history.back()\">$l_back</a></td></tr>";
			echo "</table></td></tr></table>";
			include_once("./includes/footer.inc");
			exit;
		}
		$actdate = date("Y-m-d H:i:s");
		if($usebwlist==1)
			$comment=censor_bad_words($comment,$badwordprefix,$db);
		$sql = "insert into ".$tableprefix."_comments (poster, email, entryref, comment, enterdate, postingid) values ('$poster', '$email', '$entryref', '$comment', '$actdate', '$postingid')";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		if($newcomnotify==1)
		{
			$tmpsql="select u.* from ".$tableprefix."_newcommentnotify ncn, ".$tableprefix."_users u where u.usernr=ncn.usernr";
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
			while($tmprow=mysql_fetch_array($tmpresult))
			{
				$linkurl=$simpnews_fullurl."admin/comments.php?$langvar=".$tmprow["language"]."&entryref=$entryref";
				$tmpsql2="select * from ".$tableprefix."_texts where lang='".$tmprow["language"]."' and textid='newcommentsubj'";
				if(!$tmpresult2 = mysql_query($tmpsql2, $db))
					die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
				if(!$tmprow2=mysql_fetch_array($tmpresult2))
					$subject=$l_newcommentsubj;
				else
					$subject=undo_htmlspecialchars($tmprow2["text"]);
				$subject=str_replace("{sitename}",$simpnewssitename,$subject);
				$subject=strip_tags($subject);
				$tmpsql2="select * from ".$tableprefix."_texts where lang='".$tmprow["language"]."' and textid='newcommentmsg'";
				if(!$tmpresult2 = mysql_query($tmpsql2, $db))
					die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
				if(!$tmprow2=mysql_fetch_array($tmpresult2))
					$mailmsg=$l_newcommentmsg;
				else
					$mailmsg=undo_htmlspecialchars($tmprow2["text"]);
				$mailmsg=str_replace("{sitename}",$simpnewssitename,$mailmsg);
				$mailmsg=str_replace("{email}",$email,$mailmsg);
				$mailmsg=str_replace("{poster}",$poster,$mailmsg);
				$mailmsg=str_replace("{entry}",$entryref,$mailmsg);
				$mailmsg=str_replace("{linkurl}","<a href=\"$linkurl\">$linkurl</a>",$mailmsg);
				$mailmsg_asc=str_replace("<BR>","\r\n",$mailmsg);
				$mailmsg_asc=strip_tags($mailmsg_asc);
				$mail = new htmlMimeMail();
				$mail->setCrlf($crlf);
				$mail->setTextWrap($mailmaxlinelength);
				$mail->setHTMLCharset($contentcharset);
				$mail->setTextCharset($contentcharset);
				$mail->setHTML($mailmsg,$mailmsg_asc);
				$mail->setSubject($subject);
				if($simpnewsmailname)
					$fromadr="\"$simpnewsmailname\" <$simpnewsmail>";
				else
					$fromadr=$simpnewsmail;
				$mail->setFrom($fromadr);
				$receiver=array();
				array_push($receiver,$tmprow["email"]);
				if(!$insafemode)
					@set_time_limit($msendlimit);
				if($use_smtpmail)
				{
					$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
					$sendresult=$mail->send($receiver, "smtp");
				}
				else
					$sendresult=$mail->send($receiver, "mail");
				do_emaillog($sendresult,$tmprow["email"],"adding comment");
			}
		}
		$redirect=1;
		include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_writecomment?></font></td></tr>
<?php
		echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo "$l_commentposted</td></tr>";
		if(!isset($backurl))
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
?>