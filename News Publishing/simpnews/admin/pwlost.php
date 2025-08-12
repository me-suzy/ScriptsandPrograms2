<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./functions.php');
require_once('../functions.php');
require_once('./admchk.php');
if($admoldhdr)
{
	header('Pragma: no-cache');
	header('Expires: 0');
}
else
{
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
	header("Cache-Control: no-cache, must-revalidate");
	header("Pragma: no-cache");
}
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
if(!isset($username))
	$username="";
include('./language/lang_'.$act_lang.'.php');
$sql = "select * from ".$tableprefix."_settings where (settingnr=1)";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.");
if ($myrow = mysql_fetch_array($result))
{
	$simpnewsmail=$myrow["simpnewsmail"];
	if(!$simpnewsmail)
		$simpnewsmail="simpnews@foo.bar";
	$emaillog=$myrow["emaillog"];
}
else
{
	$simpnewsmail="simpnews@foo.bar";
	$emaillog=0;
}
if(!$enablerecoverpw)
	die($l_functionnotallowed);
?>
<html>
<head>
<title>SimpNews - <?php echo $l_administration?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
<?php
	if(is_ns4())
		echo "<link rel=stylesheet href=\"./css/snadm_ns4.css\" type=\"text/css\">\n";
	else if(is_ns6())
		echo "<link rel=stylesheet href=\"./css/snadm_ns6.css\" type=\"text/css\">\n";
	else if(is_opera())
		echo "<link rel=stylesheet href=\"./css/snadm_opera.css\" type=\"text/css\">\n";
	else if(is_konqueror())
		echo "<link rel=stylesheet href=\"./css/snadm_konqueror.css\" type=\"text/css\">\n";
	else if(is_gecko())
		echo "<link rel=stylesheet href=\"./css/snadm_gecko.css\" type=\"text/css\">\n";
	else
		echo "<link rel=stylesheet href=\"./css/snadm.css\" type=\"text/css\">\n";
?>
</head>
<body>
<table width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr><td align="CENTER" class="prognamerow"><h1>SimpNews v<?php echo $version?></h1></td></tr>
<tr><td align="CENTER" class="pagetitlerow"><h2><?php echo $l_pwlost?></h2></td></tr>
</table>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
if(isset($mode))
{
	if($mode=="recover")
	{
		if(!$username)
		{
?>
<tr class="errorrow"><td align="center" colspan="2">
<?php echo $l_nousername?></td></tr>
<tr class="inputrow"><form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<td align="right" width="30%"><?php echo $l_username?>:</td><td><input class="sninput" type="text" name="username" size="40" maxlength="80"></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="recover"><input class="snbutton" type="submit" value="<?php echo $l_submit?>"></td></tr>
</form>
<?php
		}
		else
		{
			$username=strtolower($username);
			$sql = "select * from ".$tableprefix."_users where username='$username'";
			if(!$result = mysql_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
			if($myrow=mysql_fetch_array($result))
			{
				if(($myrow["lockpw"]==0) && $myrow["email"] && ($myrow["lockentry"]==0))
				{
					do{
						$maximum=9999999999;
						if($maximum>mt_getrandmax())
							$maximum=mt_getrandmax();
						mt_srand((double)microtime()*1000000);
						$autopin=mt_rand(10000,$maximum);
						$tempsql = "select * from ".$tableprefix."_users where autopin=$autopin";
						if(!$tempresult = mysql_query($tempsql, $db))
							die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
					}while($temprow=mysql_fetch_array($tempresult));
					$updatesql = "update ".$tableprefix."_users set autopin=$autopin where username='$username'";
					if(!$updateresult = mysql_query($updatesql, $db))
						die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
					include_once($path_simpnews.'/includes/htmlMimeMail.inc');
					include_once($path_simpnews.'/includes/smtp.inc');
					include_once($path_simpnews.'/includes/RFC822.inc');
					$subject = "Lost password SimpNews ($simpnewssitename)";
					$mailmsg = "Sie haben bei SimpNews auf $simpnewssitename ein neues Passwort fuer Ihren Administrationszugang angefordert.".$crlf;
					$mailmsg.= "Bitte benutzen Sie bei Ihrer naechsten Anmeldung das folgende temporaere Passwort und vergeben Sie ein neues.".$crlf;
					$mailmsg.= "Sollten Sie das Passwort nicht angefordert haben, so melden Sie sich bitte so bald als moeglich mit Ihrem alten Passwort an.".$crlf;
					$mailmsg.= "Das temporaere Passwort wird dann geloescht und verliert seine Gueltigkeit".$crlf.$crlf;
					$mailmsg.= "You have requested a new password for your administration account at SimpNews on $simpnewssitename.".$crlf.$crlf;
					$mailmsg.= "Please use the temporary password provided in this email the next time you login and enter a new one.".$crlf;
					$mailmsg.= "If this request was not initiated by you, please login as soon as possible using your old password.".$crlf;
					$mailmsg.= "Doing so will erase the temporary password, so it no longer will be valid.".$crlf.$crlf;
					$mailmsg .="temporaeres Passwort/temporary password: $autopin".$crlf.$crlf.$crlf.$crlf;
					$mail = new htmlMimeMail();
					$mail->setCrlf($crlf);
					$mail->setTextWrap(80);
					$mail->setTextCharset($contentcharset);
					$mail->setText($mailmsg);
					$mail->setSubject($subject);
					$mail->setFrom($simpnewsmail);
					$receiver=array($myrow["email"]);
					if($use_smtpmail)
					{
						$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
						$sendresult=$mail->send($receiver, "smtp");
					}
					else
						$sendresult=$mail->send($receiver, "mail");
					do_emaillog($sendresult,$myrow["email"],"pwlost.php");
?>
<tr class="displayrow"><td align="center" colspan="2">
<?php echo $l_pwmailed?></td></tr>
<?php
				}
				else
				{
?>
<tr class="errorrow"><td align="center" colspan="2">
<?php echo $l_nonewpw?></td></tr>
<?php
				}
			}
			else
			{
?>
<tr class="errorrow"><td align="center" colspan="2">
<?php echo $l_nonewpw?></td></tr>
<?php
			}
		}
	}
}
else
{
?>
<tr class="displayrow"><td align="center" colspan="2">
<?php echo $l_enterusername?></td></tr>
<tr class="inputrow"><form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<td align="right" width="30%"><?php echo $l_username?>:</td><td><input class="sninput" type="text" name="username" size="40" maxlength="80"></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="recover"><input class="snbutton" type="submit" value="<?php echo $l_submit?>"></td></tr>
</form>
<?php
}
?>
</table></td></tr></table>
<?php
echo "<hr><div class=\"copyright\" align=\"center\">$copyright_url $copyright_note</div>";
?>
</body></html>
