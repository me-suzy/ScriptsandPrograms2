<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
echo "<tr bgcolor=\"$irow_bgcolor\"><td align=\"center\" colspan=\"2\">";
echo "<span style=\"font-face: $FontFace; font-size: $irow_fontsize; color: $irow_fontcolor; font-weight: bold;\">";
echo $l_unsubscribe;
echo "</span></td></tr>\n";
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
	$errmsg.="<tr bgcolor=\"#c0c0c0\" align=\"center\"><td class=\"error\">";
	$errmsg.="<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
	$errmsg.="$l_noemail</span></td></tr>";
	$errors=1;
}
if($errors==0)
{
	$sql="select * from ".$tableprefix."_subscriptions where email='$email' and confirmed=1 and progid='$prog' and language='$act_lang'";
	if(!$result = mysql_query($sql, $db))
		die("Could not connect to the database.");
	if(!$myrow=mysql_fetch_array($result))
	{
		$errmsg.="<tr bgcolor=\"#c0c0c0\" align=\"center\"><td class=\"error\">";
		$errmsg.="<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		$errmsg.="$l_noremoveentry<span></td></tr>";
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
$confirmurl=$faqe_fullurl."/subscription.php?$langvar=$act_lang&mode=remove&email=$email&id=".$myrow["unsubscribeid"]."&prog=$prog";
$mailmsg = $l_unsubscriptionconfirmmail;
$mailmsg = str_replace("{progname}",$progname,$mailmsg);
$mailmsg = str_replace("{confirmurl}",$confirmurl,$mailmsg);
if($defmailsig)
	$mailmsg.= "\n\n---\n$defmailsig\n\n\n";
$mailmsg = str_replace("\n",$crlf,$mailmsg);
$mailmsg_html = $l_unsubscriptionconfirmmail_html;
if($defmailsig)
	$mailmsg_html.= "\n\n<hr>\n$defmailsig\n\n\n";
$mailmsg_html = str_replace("{progname}",$progname,$mailmsg_html);
$mailmsg_html = str_replace("{confirmurl}",$confirmurl,$mailmsg_html);
$mailmsg_html = str_replace("\n","<br>".$crlf,$mailmsg_html);
$subject = $l_unsubscriptionconfirmsubject;
$subject = str_replace("{progname}",$progname,$subject);
$mail = new htmlMimeMail();
$mail->setCrlf($crlf);
$mail->setTextCharset($contentcharset);
if($myrow["emailtype"]==0)
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
array_push($receiver,$myrow["email"]);
if($use_smtpmail)
{
	$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
	$mail->send($receiver, "smtp");
}
else
	$mail->send($receiver, "mail");
echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
echo "<td align=\"center\" colspan=\"2\">";
echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
echo "$l_unsubscribesent</span></td></tr>";
echo "<tr bgcolor=\"$actionbgcolor\" align=\"center\">";
echo "<td align=\"center\" colspan=\"2\">";
echo "<span style=\"font-face: $FontFace; font-size: $actionlinefontsize; color: $FontColor;\">";
echo "<a class=\"actionlink\" href=\"$backurl\">$l_back2faq</a></span></td></tr>";
echo "</table></td></tr></table></div>";
include_once("./includes/bottom.inc");
exit;
?>