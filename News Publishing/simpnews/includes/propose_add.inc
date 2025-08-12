<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
		if($proposenotify==1)
		{
			include_once('./includes/htmlMimeMail.inc');
			if($use_smtpmail)
			{
				include_once('./includes/smtp.inc');
				include_once('./includes/RFC822.inc');
			}
		}
		$errors=0;
		$errmsg="";
		if(!isset($email))
			$email="";
		if(!isset($poster))
			$poster="";
		if(!isset($input_heading))
			$input_heading="";
		if(!isset($entrytxt))
			$entrytxt="";
		$poster=trim($poster);
		$entrytxt=trim($entrytxt);
		$heading=trim($heading);
		$email=trim($email);
		if(!isset($postingid) || !$postingid)
		{
			include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_propose_news?></font></td></tr>
<?php
			echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			echo "$l_callingerror</td></tr>";
			echo "</table></td></tr></table>";
			include_once("./includes/footer.inc");
			exit;
		}
		if(bittst($proposereq,BIT_1) && !$poster)
		{
			$errors=1;
			$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.="$l_noname</td></tr>";
		}
		if(bittst($proposereq,BIT_2) && !$email)
		{
			$errors=1;
			$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.="$l_noemail</td></tr>";
		}
		if($email)
		{
			if(!validate_email($email))
			{
				$errors=1;
				$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
				$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
				$errmsg.="$l_novalidemail</td></tr>";
			}
			else
				$email=strtolower($email);
		}
		if(bittst($proposereq,BIT_3) && !$input_heading)
		{
			$errors=1;
			$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.="$l_noheading</td></tr>";
		}
		if(!isset($entrytxt) || !$entrytxt)
		{
			$errors=1;
			$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.="$l_notext</td></tr>";
		}
		if(isset($password) && $password)
		{
			if(!isset($email) || !$email)
			{
				$errors=1;
				$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
				$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
				$errmsg.="$l_noemail</td></tr>";
			}
			else if($password!=$password2)
			{
				$errors=1;
				$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
				$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
				$errmsg.="$l_passwordmismatch</td></tr>";
			}
		}
		if($upload_avail)
		{
			if($new_global_handling)
				@$tmp_file=$_FILES['userfile']['tmp_name'];
			else
				$tmp_file=$HTTP_POST_FILES['userfile']['tmp_name'];
			if(is_uploaded_file($tmp_file))
			{
				if($new_global_handling)
				{
					$filename=$_FILES['userfile']['name'];
					$filesize=$_FILES['userfile']['size'];
				}
				else
				{
					$filename=$HTTP_POST_FILES['userfile']['name'];
					$filesize=$HTTP_POST_FILES['userfile']['size'];
				}
				if(!isAllowedFileType($filename))
				{
					$errors=1;
					$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
					$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
					$errmsg.=sprintf($l_unallowedfiletype,getRealFileExtension($filename));
					$errmsg.="</td></tr>";
				}
			}
		}
		if($errors==0)
		{
			$sql="select * from ".$tableprefix."_tmpdata where postingid='".$postingid."'";
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
<td colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_propose_news?></font></td></tr>
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
		$posterip = get_userip();
		if($email)
		{
			$postersql="select * from ".$tableprefix."_poster where email='$email'";
			if(!$presult = mysql_query($postersql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
			if($prow=mysql_fetch_array($presult))
			{
				$posterid=$prow["entrynr"];
			}
			else
			{
				if(bittst($proposepermissions,BIT_5))
					$disablebbcode=1;
				else
					$disablebbcode=0;
				if(bittst($proposepermissions,BIT_7))
					$disablefileupload=1;
				else
					$disablefileupload=0;
				$postersql="insert into ".$tableprefix."_poster (email, name, disablebbcode, disablefileupload) values ('$email', '$poster', $disablebbcode, $disablefileupload)";
				if(!$presult = mysql_query($postersql, $db))
					die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
				$posterid=mysql_insert_id($db);
			}
			if(isset($password) && $password)
			{
				$pid=0;
				do{
					$maximum=9999999999;
					if($maximum>mt_getrandmax())
						$maximum=mt_getrandmax();
					mt_srand((double)microtime()*1000000);
					$pid=mt_rand(10000,$maximum);
					$sql = "select * from ".$tableprefix."_poster where pid='$pid'";
					if(!$result = mysql_query($sql, $db))
						die("<tr class=\"errorrow\"><td>Could not connect to the database.");
				}while($myrow=mysql_fetch_array($result));
				$sql="update ".$tableprefix."_poster set pid='$pid', ";
				if($prop_nopwconfirm==1)
					$sql.="pwconfirmed=1, ";
				else
					$sql.="pwconfirmed=0, ";
				$sql.="password='".md5($password)."' where entrynr='$posterid'";
				if(!$result = mysql_query($sql, $db))
					die("<tr class=\"errorrow\"><td>Could not connect to the database.");
				if($prop_nopwconfirm==0)
				{
					$confirmurl=$simpnews_fullurl."propose.php?$langvar=$act_lang&mode=confirmpw&poster=$posterid&pid='$pid'";
					$mailmsg=str_replace("{sitename}",$sitename,$l_pwconfirmmail);
					$mailmsg=str_replace("{confirmurl}",$confirmurl,$mailmsg);
					if($defsignature)
						$mailmsg.= "\n\n---\n$defsignature\n\n\n";
					$mailmsg=str_replace("\n",$crlf,$mailmsg);
					$mailmsg_html=str_replace("{sitename}",$sitename,$l_pwconfirmmail_html);
					$mailmsg_html=str_replace("{confirmurl}",$confirmurl,$mailmsg_html);
					if($defsignature)
						$mailmsg_html.= "\n\n<hr>\n$defsignature\n\n\n";
					$mailmsg_html = str_replace("\n","<br>".$crlf,$mailmsg_html);
					$subject = $l_pwconfirmsubject;
					$subject = str_replace("{sitename}",$sitename,$subject);
					$mail = new htmlMimeMail();
					$mail->setCrlf($crlf);
					$mail->setTextWrap($mailmaxlinelength);
					$mail->setHTMLCharset($contentcharset);
					$mail->setTextCharset($contentcharset);
					$mail->setHTML($mailmsg_html,$mailmsg);
					$mail->setSubject($subject);
					if($simpnewsmailname)
						$fromadr="\"$simpnewsmailname\" <$simpnewsmail>";
					else
						$fromadr=$simpnewsmail;
					$mail->setFrom($fromadr);
					$receivers=array();
					array_push($receivers,$email);
					if(!$insafemode)
						@set_time_limit($msendlimit);
					if($use_smtpmail)
					{
						$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
						$sendresult=$mail->send($receivers, "smtp");
					}
					else
						$sendresult=$mail->send($receivers, "mail");
					do_emaillog($sendresult,$email,"new proposal (1)");
				}
			}
		}
		else
			$posterid=0;
		$sql = "insert into ".$tableprefix."_tmpdata (lang, date, text, heading, category, posterip, posterid, postingid) ";
		$sql.= "values ('$act_lang', '$actdate', '$entrytxt', '$input_heading', '$category', '$posterip', $posterid, '$postingid')";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
		$proposenr=mysql_insert_id($db);
		
		if($upload_avail)
		{
			$errors=0;
			if($new_global_handling)
				@$tmp_file=$_FILES['userfile']['tmp_name'];
			else
				$tmp_file=$HTTP_POST_FILES['userfile']['tmp_name'];
			if(is_uploaded_file($tmp_file))
			{
				if($new_global_handling)
				{
					$filename=$_FILES['userfile']['name'];
					$filesize=$_FILES['userfile']['size'];
				}
				else
				{
					$filename=$HTTP_POST_FILES['userfile']['name'];
					$filesize=$HTTP_POST_FILES['userfile']['size'];
				}
				if(!isAllowedFileType($filename))
				{
					$errors=1;
				}
				else
				{
					$filedata = addslashes(get_file($tmp_file));
					$filetype=getUploadFileType($filename);
					if($attach_in_fs)
					{
						$filedata="";
						if ( preg_match("/[\\/:*?\"<>|]/i", $filename) )
							$errors = 1;
						else
						{
							if(file_exists($path_attach."/".$filename))
							{
								$tmpnum=1;
								$tmpext=getRealFileExtension($filename);
								$tmpfilename=getRealFilename($filename);
								while(file_exists($path_attach."/".$tmpfilename."_".$tmpnum.".".$tmpext))
									$tmpnum++;
								$physfile=$tmpfilename."_".$tmpnum.".".$tmpext;
							}
							else
								$physfile=$filename;
							if(!move_uploaded_file($tmp_file,$path_attach."/".$physfile))
								$errors=1;
							else if($attach_do_chmod)
								chmod($patch_attach."/".$physfile, $attach_fmode);
						}
					}
				}
				if($errors==0)
				{
					$sql = "INSERT INTO ".$tableprefix."_files (filename, filesize, mimetype, bindata, description";
					if($attach_in_fs)
						$sql.=", fs_filename";
					$sql.= ") VALUES (";
					$sql.="'$filename', '$filesize', '$filetype', '$filedata', '$filedescription'";
					if($attach_in_fs)
						$sql.=", '$physfile'";
					$sql.=")";
					if(!$result = mysql_query($sql, $db))
					    die("<tr class=\"errorrow\"><td>Unable to add file to database.");
					$filenr=mysql_insert_id($db);
					$sql= "insert into ".$tableprefix."_tmpnews_attachs (newsnr, attachnr) values ($proposenr, $filenr)";
					if(!$result = mysql_query($sql, $db))
						die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
				}
			}
		}
		if($proposenotify==1)
		{
			if($category>0)
			{
				$tmpsql="select * from ".$tableprefix."_categories where catnr='$category'";
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
			if(($notifymode==0) || ($category==0))
				$tmpsql="select u.* from ".$tableprefix."_notifylist nl, ".$tableprefix."_users u where u.usernr=nl.usernr";
			else
				$tmpsql="select u.* from ".$tableprefix."_users u, ".$tableprefix."_cat_adm ca where u.usernr=ca.usernr and ca.catnr='$category'";
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
			while($tmprow=mysql_fetch_array($tmpresult))
			{
				$linkurl=$simpnews_fullurl."admin/proposes.php?$langvar=".$tmprow["language"]."&mode=display&input_entrynr=$proposenr";
				$tmpsql2="select * from ".$tableprefix."_texts where lang='".$tmprow["language"]."' and textid='notsubj'";
				if(!$tmpresult2 = mysql_query($tmpsql2, $db))
					die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
				if(!$tmprow2=mysql_fetch_array($tmpresult2))
					$subject=$l_notsubj;
				else
					$subject=undo_htmlspecialchars($tmprow2["text"]);
				$subject=str_replace("{sitename}",$simpnewssitename,$subject);
				$subject=str_replace("{ptype}","News",$subject);
				$subject=strip_tags($subject);
				$tmpsql2="select * from ".$tableprefix."_texts where lang='".$tmprow["language"]."' and textid='notmsg'";
				if(!$tmpresult2 = mysql_query($tmpsql2, $db))
					die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
				if(!$tmprow2=mysql_fetch_array($tmpresult2))
					$mailmsg=$l_notmsg;
				else
					$mailmsg=undo_htmlspecialchars($tmprow2["text"]);
				$mailmsg=str_replace("{sitename}",$simpnewssitename,$mailmsg);
				$mailmsg=str_replace("{ptype}","News",$mailmsg);
				$mailmsg=str_replace("{category}",$catname,$mailmsg);
				$mailmsg=str_replace("{linkurl}","<a href=\"$linkurl\">$linkurl</a>",$mailmsg);
				$mailmsg=str_replace("{postername}",$poster,$mailmsg);
				$mailmsg=str_replace("{postermail}",$email,$mailmsg);
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
				do_emaillog($sendresult,$tmprow["email"],"new proposal");
			}
		}
		$redirect=1;
		include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_propose_news?></font></td></tr>
<?php
		echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo "$l_proposeposted</td></tr>";
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
		echo "<a class=\"actionlink\" href=\"$backurl\">";
		if(isset($backtxt) && $backtxt)
			echo $backtxt;
		else
			echo "$l_news";
		echo "</a></font></td></tr>";
?>