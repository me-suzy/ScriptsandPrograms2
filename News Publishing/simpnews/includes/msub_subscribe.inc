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
		$errmsg="";
		$errors=0;
		if(!isset($emailtype))
			$emailtype=1;
		if(!isset($newscat))
		{
			$errmsg.= "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.= "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.= "$l_nocatselected</td></tr>";
			$errors=1;
		}
		if(!isset($email) || !$email)
		{
			$errmsg.= "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.= "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.= "$l_noemail</td></tr>";
			$errors=1;
		}
		else if(!validate_email($email))
		{
			$errmsg.= "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.= "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.= "$l_novalidemail</td></tr>";
			$errors=1;
		}
		else if($subscriptionfreemailer==0)
		{
			if (forbidden_freemailer($email, $db))
			{
				$errmsg.= "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
				$errmsg.= "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
				$errmsg.= "$l_forbidden_freemailer</td></tr>";
				$errors=1;
			}
		}
		if($errors==0)
		{
			for($i=0;$i<count($newscat);$i++)
			{
				$sql="select * from ".$tableprefix."_subscriptions where email='$email' and (category=".$newscat[$i]." or category=0)";
				if(!$result = mysql_query($sql, $db))
					die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
				if($myrow=mysql_fetch_array($result))
				{
					$catsql="select * from ".$tableprefix."_categories where catnr=".$newscat[$i];
					if(!$catresult = mysql_query($catsql, $db))
						die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
					if($catrow=mysql_fetch_array($catresult))
					{
						$catname=stripslashes($catrow["catname"]);
						$tmpsql2="select * from ".$tableprefix."_catnames where catnr=".$catrow["catnr"]." and lang='".$act_lang."'";
						if(!$tmpresult2=mysql_query($tmpsql2,$db))
							die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
						if($tmprow2=mysql_fetch_array($tmpresult2))
						{
							if(strlen($tmprow2["catname"])>0)
								$catname=stripslashes($tmprow2["catname"]);
						}
					}
					else
						$catname=$l_unknown;
					$errmsg.= "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
					$errmsg.= "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
					if($myrow["confirmed"]==1)
						$errmsg.= "$l_allready_subscribed<br>$l_category: <i>".display_encoded($catname)."</i></td></tr>";
					else
						$errmsg.= "$l_allready_pending<br>$l_category: <i>".display_encoded($catname)."</i></td></tr>";
					$errors=1;
				}
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
			echo "<a class=\"actionlink\" href=\"javascript:history.back()\">$l_back</a>";
			echo "</td></tr></table></td></tr></table>";
			include_once("./includes/footer.inc");
			exit;
		}
		for($i=0;$i<count($newscat);$i++)
		{
			$actdate = date("Y-m-d H:i:s");
			if($maxconfirmtime==0)
			{
				$confirmed=1;
				$subscribeid=0;
				do{
					$maximum=9999999999;
					if($maximum>mt_getrandmax())
						$maximum=mt_getrandmax();
					mt_srand((double)microtime()*1000000);
					$unsubscribeid=mt_rand(10000,$maximum);
					$sql = "select * from ".$tableprefix."_subscriptions where unsubscribeid='$unsubscribeid'";
					if(!$result = mysql_query($sql, $db))
						die("<tr class=\"errorrow\"><td>Could not connect to the database.");
				}while($myrow=mysql_fetch_array($result));
			}
			else
			{
				$unsubscribeid=0;
				$confirmed=0;
				do{
					$maximum=9999999999;
					if($maximum>mt_getrandmax())
						$maximum=mt_getrandmax();
					mt_srand((double)microtime()*1000000);
					$subscribeid=mt_rand(10000,$maximum);
					$sql = "select * from ".$tableprefix."_subscriptions where subscribeid='$subscribeid'";
					if(!$result = mysql_query($sql, $db))
						die("<tr class=\"errorrow\"><td>Could not connect to the database.");
				}while($myrow=mysql_fetch_array($result));
			}
			$sql = "insert into ".$tableprefix."_subscriptions (email, confirmed, language, subscribeid, enterdate, lastsent, emailtype, unsubscribeid, category) ";
			$sql.= "values ('$email', $confirmed, '$act_lang', $subscribeid, '$actdate', '$actdate', $emailtype, $unsubscribeid, $newscat[$i])";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			$subscriptionnr=mysql_insert_id($db);
			if($maxconfirmtime>0)
			{
				$tmpsql="select * from ".$tableprefix."_categories where catnr=".$newscat[$i];
				if(!$tmpresult = mysql_query($tmpsql, $db))
					die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
				if($tmprow=mysql_fetch_array($tmpresult))
				{
					$catname=stripslashes($tmprow["catname"]);
					$tmpsql2="select * from ".$tableprefix."_catnames where catnr=".$tmprow["catnr"]." and lang='".$act_lang."'";
					if(!$tmpresult2=mysql_query($tmpsql2,$db))
						die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
					if($tmprow2=mysql_fetch_array($tmpresult2))
					{
						if(strlen($tmprow2["catname"])>0)
							$catname=stripslashes($tmprow2["catname"]);
					}
				}
				else
					$catname=$l_unknown;
				$confirmhours=$maxconfirmtime*24;
				$confirmtime="$confirmhours $l_hours";
				$confirmurl=$simpnews_fullurl."subscription.php?$langvar=$act_lang&mode=confirm&email=$email&id=$subscribeid";
				$tmpsql="select * from ".$tableprefix."_texts where textid='msubnot' and lang='$act_lang'";
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

					$mailmsg = $l_msubscriptionconfirmmail;
					$mailmsg_html=$l_msubscriptionconfirmmail_html;
				}
				$mailmsg = str_replace("{catname}",$catname,$mailmsg);
				$mailmsg = str_replace("{confirmtime}",$confirmtime,$mailmsg);
				$mailmsg = str_replace("{sitename}",$sitename,$mailmsg);
				$mailmsg = str_replace("{confirmurl}",$confirmurl,$mailmsg);
				$mailmsg.= "\n\n---\n$defsignature\n\n\n";
				$mailmsg=str_replace("\r","",$mailmsg);
				$mailmsg=str_replace("\n",$crlf,$mailmsg);
				$mailmsg_html = str_replace("{catname}",$catname,$mailmsg_html);
				$mailmsg_html = str_replace("{confirmtime}",$confirmtime,$mailmsg_html);
				$mailmsg_html = str_replace("{sitename}",$sitename,$mailmsg_html);
				$mailmsg_html = str_replace("{confirmurl}",$confirmurl,$mailmsg_html);
				$mailmsg_html.= "\n\n<hr>\n$defsignature\n\n\n";
				$mailmsg_html = str_replace("\n","<br>".$crlf,$mailmsg_html);
				$mailmsg_html = undo_htmlspecialchars($mailmsg_html);
				$subject = $l_subscriptionconfirmsubject;
				$subject = str_replace("{sitename}",$sitename,$subject);
				if($simpnewsmailname)
					$fromadr="\"$simpnewsmailname\" <$simpnewsmail>";
				else
					$fromadr=$simpnewsmail;
				$mail = new htmlMimeMail();
				$mail->setCrlf($crlf);
				$mail->setTextWrap($mailmaxlinelength);
				$mail->setTextCharset($contentcharset);
				if($emailtype==0)
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
				$receiver=array($email);
				if(!$insafemode)
					@set_time_limit($msendlimit);
				if($use_smtpmail)
				{
					$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
					$sendresult=$mail->send($receiver, "smtp");
				}
				else
					$sendresult=$mail->send($receiver, "mail");
				do_emaillog($sendresult,$email,"subcribe newsletter");
			}
			else if($newsubscriptionnotify==1)
			{
				$tmpsql="select * from ".$tableprefix."_categories where catnr=".$newscat[$i];
				if(!$tmpresult = mysql_query($tmpsql, $db))
					die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
				if($tmprow=mysql_fetch_array($tmpresult))
				{
					$catname=stripslashes($tmprow["catname"]);
					$tmpsql2="select * from ".$tableprefix."_catnames where catnr=".$tmprow["catnr"]." and lang='".$act_lang."'";
					if(!$tmpresult2=mysql_query($tmpsql2,$db))
						die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
					if($tmprow2=mysql_fetch_array($tmpresult2))
					{
						if(strlen($tmprow2["catname"])>0)
							$catname=stripslashes($tmprow2["catname"]);
					}
				}
				else
					$catname=$l_unknown;
				$tmpsql="select u.* from ".$tableprefix."_newsubnotify nsn, ".$tableprefix."_users u where u.usernr=nsn.usernr";
				if(!$tmpresult = mysql_query($tmpsql, $db))
					die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
				while($tmprow=mysql_fetch_array($tmpresult))
				{
					$linkurl=$simpnews_fullurl."admin/subscribers.php?$langvar=".$tmprow["language"]."&mode=display&input_subscriptionnr=$subscriptionnr";
					$tmpsql2="select * from ".$tableprefix."_texts where lang='".$tmprow["language"]."' and textid='newsubsubj'";
					if(!$tmpresult2 = mysql_query($tmpsql2, $db))
						die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
					if(!$tmprow2=mysql_fetch_array($tmpresult2))
						$subject=$l_newsubsubj;
					else
						$subject=undo_htmlspecialchars($tmprow2["text"]);
					$subject=str_replace("{sitename}",$simpnewssitename,$subject);
					$subject=strip_tags($subject);
					$tmpsql2="select * from ".$tableprefix."_texts where lang='".$tmprow["language"]."' and textid='newsubmsg'";
					if(!$tmpresult2 = mysql_query($tmpsql2, $db))
						die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
					if(!$tmprow2=mysql_fetch_array($tmpresult2))
						$mailmsg=$l_newsubmsg;
					else
						$mailmsg=undo_htmlspecialchars($tmprow2["text"]);
					$mailmsg=str_replace("{sitename}",$simpnewssitename,$mailmsg);
					$mailmsg=str_replace("{email}",$email,$mailmsg);
					$mailmsg=str_replace("{category}",$catname,$mailmsg);
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
					$receiver=array($tmprow["email"]);
					if(!$insafemode)
						@set_time_limit($msendlimit);
					if($use_smtpmail)
					{
						$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
						$sendresult=$mail->send($receiver, "smtp");
					}
					else
						$sendresult=$mail->send($receiver, "mail");
					do_emaillog($sendresult,$tmprow["email"],"new newsletter subscription (1)");
				}
			}
		}
		$redirect=1;
		if($subredirecturl)
			$backurl=$subredirecturl;
		include_once('./includes/head.inc');
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_subscriptionprelude?></font></td></tr>
<?php
		echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo "$l_subscriptiondone</font></td></tr>";
		if($maxconfirmtime>0)
		{
			echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$tmpsql="select * from ".$tableprefix."_texts where textid='subconfinfo' and lang='$act_lang'";
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if($tmprow=mysql_fetch_array($tmpresult))
				echo stripslashes($tmprow["text"]);
			else
				echo "$l_subscriptionconfirminfo";
			echo "</font></td></tr>";
		}
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
		echo "</table></td></tr></table>";
		include_once("./includes/footer.inc");
		exit;
?>