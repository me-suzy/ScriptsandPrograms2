<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
		$errors=0;
		$errmsg="";
		include_once('./includes/htmlMimeMail.inc');
		if($use_smtpmail)
		{
			include_once('./includes/smtp.inc');
			include_once('./includes/RFC822.inc');
		}
		if(!isset($email) || !$email)
		{
			$errmsg.= "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.= "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.= $l_missingemail."</td></tr>";
			$errors=1;
		}
		if(!isset($id) || !$id)
		{
			$errmsg.= "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.= "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.= $l_missingid."</td></tr>";
			$errors=1;
		}
		if($errors==0)
		{
			$actdate = date("Y-m-d H:i:s");
			$confirmtime=($maxconfirmtime*24)+1;
			$sql = "select * from ".$tableprefix."_subscriptions where email='$email' and subscribeid='$id' and enterdate>=DATE_SUB('$actdate', INTERVAL $confirmtime HOUR)";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if(!$myrow=mysql_fetch_array($result))
			{
				$errmsg.= "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
				$errmsg.= "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
				$errmsg.= $l_noconfirmentry."</td></tr>";
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
			echo "</table></td></tr></table>";
			include_once("./includes/footer.inc");
			exit;
		}
		do{
			$maximum=9999999999;
			if($maximum>mt_getrandmax())
				$maximum=mt_getrandmax();
			mt_srand((double)microtime()*1000000);
			$unsubscribeid=mt_rand(10000,$maximum);
			$sql = "select * from ".$tableprefix."_subscriptions where unsubscribeid='$unsubscribeid'";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		}while(mysql_num_rows($result)>0);
		$sql = "select * from ".$tableprefix."_subscriptions where email='$email' and subscribeid='$id'";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		$subscriberrow=mysql_fetch_array($result);
		$sql = "update ".$tableprefix."_subscriptions set subscribeid=0, confirmed=1, lastsent='$actdate', unsubscribeid='$unsubscribeid' where email='$email' and subscribeid='$id'";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.");
		$category=$subscriberrow["category"];
		if($newsubscriptionnotify==1)
		{
			if($subscriberrow["category"]>0)
			{
				$tmpsql="select * from ".$tableprefix."_categories where catnr=".$subscriberrow["category"];
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
					$catname="???";
			}
			else
				$catname=$l_general;
			$tmpsql="select u.* from ".$tableprefix."_newsubnotify nsn, ".$tableprefix."_users u where u.usernr=nsn.usernr";
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
			while($tmprow=mysql_fetch_array($tmpresult))
			{
				if($tmprow["email"])
				{
					$linkurl=$simpnews_fullurl."admin/subscribers.php?$langvar=".$tmprow["language"]."&mode=display&input_subscriptionnr=".$subscriberrow["subscriptionnr"];
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
					$mailmsg=str_replace("{email}",$subscriberrow["email"],$mailmsg);
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
					do_emaillog($sendresult,$tmprow["email"],"confirm subscription");
				}
			}
		}
		$redirect=1;
		include_once('./includes/head.inc');
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_subscriptionprelude?></font></td></tr>
<?php
		echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo "$l_subscriptionconfirmed</td></tr>";
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