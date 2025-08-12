<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
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
if(!isset($email) || !$email)
{
	$errmsg.="<tr bgcolor=\"#c0c0c0\" align=\"center\"><td class=\"error\">";
	$errmsg.="<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
	$errmsg.="$l_noemail</span></td></tr>";
	$errors=1;
}
else if(!validate_email($email))
{
	$errmsg.="<tr bgcolor=\"#c0c0c0\" align=\"center\"><td class=\"error\">";
	$errmsg.="<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
	$errmsg.= "$l_novalidemail</span></td></tr>";
	$errors=1;
}
if($errors==0)
{
	$sql="select * from ".$tableprefix."_subscriptions where email='$email' and progid='$prog' and language='$act_lang'";
	if(!$result = faqe_db_query($sql, $db))
		die("Could not connect to the database.");
	if($myrow=faqe_db_fetch_array($result))
	{
		$errmsg.="<tr bgcolor=\"#c0c0c0\" align=\"center\"><td class=\"error\">";
		$errmsg.="<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		if($myrow["confirmed"]==1)
			$errmsg.= "$l_allready_subscribed</span></td></tr>";
		else
			$errmsg.= "$l_allready_pending</span></td></tr>";
		$errors=1;
	}
}
if($errors==1)
{
	echo $errmsg;
	echo "<tr bgcolor=\"$actionbgcolor\" align=\"center\"><td class=\"actionline\">";
	echo "<span style=\"font-face: $FontFace; font-size: $actionlinefontsize; color: $FontColor;\">";
	echo "<a class=\"actionline\" href=\"javascript:history.back()\">$l_back</a>";
	echo "</span></td></tr></table></td></tr></table></div>";
	include_once("./includes/bottom.inc");
	exit;
}
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
		if(!$result = faqe_db_query($sql, $db))
			die("Could not connect to the database.");
	}while($myrow=faqe_db_fetch_array($result));
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
		if(!$result = faqe_db_query($sql, $db))
			die("Could not connect to the database.");
	}while($myrow=faqe_db_fetch_array($result));
}
$sql = "insert into ".$tableprefix."_subscriptions (email, confirmed, language, subscribeid, enterdate, emailtype, unsubscribeid, progid, compression) ";
$sql.= "values ('$email', $confirmed, '$act_lang', $subscribeid, '$actdate', $emailtype, $unsubscribeid, '$prog', $compress)";
if(!$result = faqe_db_query($sql, $db))
	die("Could not connect to the database.");
if($maxconfirmtime>0)
{
	$confirmhours=$maxconfirmtime*24;
	$confirmtime="$confirmhours $l_hours";
	$confirmurl=$faqe_fullurl."/subscription.php?$langvar=$act_lang&mode=confirm&email=$email&id=$subscribeid&prog=$prog";
	$mailmsg = $l_subscriptionconfirmmail;
	$mailmsg = str_replace("{confirmtime}",$confirmtime,$mailmsg);
	$mailmsg = str_replace("{progname}",$progname,$mailmsg);
	$mailmsg = str_replace("{confirmurl}",$confirmurl,$mailmsg);
	if($defmailsig)
		$mailmsg.= "\n\n---\n$defmailsig\n\n\n";
	$mailmsg=str_replace("\n",$crlf,$mailmsg);
	$mailmsg_html=$l_subscriptionconfirmmail_html;
	if($defmailsig)
		$mailmsg_html.= "\n\n<hr>\n$defmailsig\n\n\n";
	$mailmsg_html = str_replace("{confirmtime}",$confirmtime,$mailmsg_html);
	$mailmsg_html = str_replace("{progname}",$progname,$mailmsg_html);
	$mailmsg_html = str_replace("{confirmurl}",$confirmurl,$mailmsg_html);
	$mailmsg_html = str_replace("\n","<br>".$crlf,$mailmsg_html);
	$subject = $l_subscriptionconfirmsubject;
	$subject = str_replace("{progname}",$progname,$subject);
	$mail = new htmlMimeMail();
	$mail->setCrlf($crlf);
	$mail->setTextCharset($contentcharset);
	if($emailtype==0)
	{
		$mail->setHTMLCharset($contentcharset);
		$mail->setHTML($mailmsg_html, $mailmsg);
	}
	else
		$mail->setText($mailmsg);
	$mail->setSubject($subject);
	$mail->setFrom($faqemail);
	if(!$insafemode)
		@set_time_limit($msendlimit);
	$receiver=array();
	array_push($receiver,$email);
	if($use_smtpmail)
	{
		$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
		$mail->send($receiver, "smtp");
	}
	else
		$mail->send($receiver, "mail");
}
echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
echo "<td align=\"center\" colspan=\"2\">";
echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
echo str_replace("{progname}",$progname,$l_subscriptiondone)."</span></td></tr>";
echo "<tr bgcolor=\"$actionbgcolor\" align=\"center\">";
echo "<td align=\"center\" colspan=\"2\">";
echo "<span style=\"font-face: $FontFace; font-size: $actionlinefontsize; color: $FontColor;\">";
echo "<a class=\"actionlink\" href=\"$backurl\">$l_back2faq</a></span></td></tr>";
echo "</table></td></tr></table></div>";
include_once("./includes/bottom.inc");
exit;
?>