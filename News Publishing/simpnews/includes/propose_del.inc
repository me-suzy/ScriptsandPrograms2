<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
include_once('./includes/htmlMimeMail.inc');
if($use_smtpmail)
{
	include_once('./includes/smtp.inc');
	include_once('./includes/RFC822.inc');
}
include_once('./includes/sesshandling.inc');
if(!is_loggedin())
{
	include_once("./includes/head.inc");
	$link="$act_script_url?$langvar=$lang&layout=$layout&mode=edlst";
	if(isset($backurl))
		$link.="&backurl=".urlencode($backurl);
?>
<tr bgcolor="<?php echo $contentbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php echo "$l_loginfirst"?></font></td></tr>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $headingfontcolor?>">
<a href="<?php echo $link?>" class="actionlink"><?php echo $l_login?></a></font>
</td></tr>
<?php
	exit;
}
else
{
	include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2">
<font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>">
<?php echo $l_edit_proposed_news?></font>
</td></tr>
<?php
	if(isset($newsnr))
	{
		if(bittst($proposepermissions,BIT_2))
		{
			$sql="delete from ".$tableprefix."_data where newsnr='$newsnr'";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			$sql = "delete from ".$tableprefix."_search where newsnr='$newsnr'";
			if(!$result = mysql_query($sql, $db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
			$sql = "delete from ".$tableprefix."_news_attachs where newsnr='$newsnr'";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
		}
		else
		{
			$tmpsql="select * from ".$tableprefix."_texts where textid='delnews' and lang='$act_lang'";
			if(!$result = mysql_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.");
			if($myrow=mysql_fetch_array($result))
				$mailmsg_html = stripslashes($myrow["text"]);
			else
				$mailmsg_html = $l_delnewsmail;
			$tmpsql="select * from ".$tableprefix."_data where newsnr='$newsnr'";
			if(!$result = mysql_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.");
			if(!$myrow=mysql_fetch_array($result))
				die("<tr class=\"errorrow\"><td>Unable to connect to database.");
			$mailmsg_html=str_replace("{email}",$userdata["email"],$mailmsg_html);
			$mailmsg_html=str_replace("{newsnr}",$newsnr,$mailmsg_html);
			$mailmsg=undo_htmlentities($mailmsg_html);
			$mailmsg=str_replace("<BR>","\r\n",$mailmsg);
			$mailmsg=strip_tags($mailmsg);
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
			if(($notifymode==0) || ($myrow["category"]==0))
				$tmpsql="select u.* from ".$tableprefix."_notifylist nl, ".$tableprefix."_users u where u.usernr=nl.usernr";
			else
				$tmpsql="select u.* from ".$tableprefix."_users u, ".$tableprefix."_cat_adm ca where u.usernr=ca.usernr and ca.catnr=".$myrow["category"];
			if(!$tmpresult = mysql_query($tmpsql, $db))
				die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
			while($tmprow=mysql_fetch_array($tmpresult))
			{
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
				do_emaillog($sendresult,$tmprow["email"],"delete proposal");
			}
		}
	}
	else if(isset($propnr))
	{
		$sql="delete from ".$tableprefix."_tmpdata where entrynr='$propnr'";
		if(!$result = mysql_query($sql, $db))
			die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	}
	else
		die("<tr class=\"errorrow\"><td>".$l_callingerror);
	echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
	echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo "$l_entrydeleted</td></tr>";
	$linkurl="$act_script_url?$langvar=$act_lang&mode=edlst&layout=$layout";
	if(isset($backurl))
		$linkurl.="&backurl=".urlencode($backurl);
	echo "<tr bgcolor=\"$headingbgcolor\" align=\"center\">";
	echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$headingfontcolor\">";
	echo "<a class=\"actionlink\" href=\"$linkurl\">";
	echo "$l_editownproposals</a></font></td></tr>";
}
?>