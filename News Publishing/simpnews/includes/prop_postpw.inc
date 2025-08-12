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
$errors=0;
$errmsg="";
if(!$password || !$password2)
{
	$errmsg.="<tr class=\"errorrow\"><td align=\"center\">";
	$errmsg.="$l_nopassword</td></tr>";
	$errors=1;
}
if($password2!=$password)
{
	$errmsg.="<tr class=\"errorrow\"><td align=\"center\">";
	$errmsg.="$l_passwordmismatch</td></tr>";
	$errors=1;
}
if($errors==1)
{
	include_once("./includes/head.inc");
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_proppw?></font></td></tr>
<?php
	echo $errmsg;
	echo "<tr bgcolor=\"$headingbgcolor\" align=\"center\">";
	echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo "<a class=\"actionlink\" href=\"javascript:history.back()\">$l_back</a></td></tr>";
	echo "</table></td></tr></table>";
	include_once("./includes/footer.inc");
	exit;
}
$sql="select * from ".$tableprefix."_poster where entrynr='$posternr'";
if(!$result = mysql_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Could not connect to the database.");
if(!$myrow=mysql_fetch_array($result))
	die("<tr class=\"errorrow\"><td>Could not connect to the database.");
$rcvmail=$myrow["email"];
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
$sql.="password='".md5($password)."' where entrynr='$posternr'";
if(!$result = mysql_query($sql, $db))
	die("<tr class=\"errorrow\"><td>Could not connect to the database.");
if($prop_nopwconfirm==0)
{
	$confirmurl=$simpnews_fullurl."$basescript?$langvar=$act_lang&mode=confirmpw&poster=$posternr&pid='$pid'";
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
	array_push($receivers,$rcvmail);
	if(!$insafemode)
		@set_time_limit($msendlimit);
	if($use_smtpmail)
	{
		$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
		$sendresult=$mail->send($receivers, "smtp");
	}
	else
		$sendresult=$mail->send($receivers, "mail");
	do_emaillog($sendresult,$rcvmail,"proposal passwordrequest");
}
$redirect=1;
include_once('./includes/head.inc');
?>
<tr bgcolor="<?php echo $headingbgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $headingfont?>" size="<?php echo $headingfontsize?>" color="<?php echo $headingfontcolor?>"><?php echo $l_proppw?></font></td></tr>
<?php
		echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		if($prop_nopwconfirm==1)
			echo $l_pwset;
		else
			echo str_replace("{email}",$rcvmail,$l_pwrequested);
		echo "</font></td></tr>";
		if($redirectdelay>=0)
		{
			echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			echo "$l_redirected</font></td></tr>";
		}
		if(isset($backurl))
		{
			echo "<tr bgcolor=\"$headingbgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$headingfontcolor\">";
			echo "<a class=\"actionlink\" href=\"$backurl\">$backmsg</a></font></td></tr>";
		}
		echo "</table></td></tr></table>";
		include_once("./includes/footer.inc");
		exit;
?>