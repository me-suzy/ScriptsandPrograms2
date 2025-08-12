<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('../functions.php');
require_once('./functions.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$sql = "select * from ".$tableprefix."_settings where settingnr=1";
if(!$result = faqe_db_query($sql, $db))
	die("Could not connect to the database.");
if ($myrow = faqe_db_fetch_array($result))
{
	$faqemail=$myrow["faqemail"];
	if(!$faqemail)
		$faqemail="faq@foo.bar";
}
else
{
	$faqemail="faq@foo.bar";
}
if($enable_htaccess)
	die($l_notavail_htaccess);
if(!$enablerecoverpw)
	die($l_functionnotallowed);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<title>FAQEngine - Administration</title>
<?php
	if(is_ns4())
		echo "<link rel=stylesheet href=./css/faqeadm_ns4.css type=text/css>\n";
	else if(is_ns6())
		echo "<link rel=stylesheet href=./css/faqeadm_ns6.css type=text/css>\n";
	else if(is_opera())
		echo "<link rel=stylesheet href=./css/faqeadm_opera.css type=text/css>\n";
	else if(is_konqueror())
		echo "<link rel=stylesheet href=./css/faqeadm_konqueror.css type=text/css>\n";
	else if(is_gecko())
		echo "<link rel=stylesheet href=./css/faqeadm_gecko.css type=text/css>\n";
	else
		echo "<link rel=stylesheet href=./css/faqeadm.css type=text/css>\n";
?>
</head>
<body>
<table width="80%" align="CENTER" calign="MIDDLE" border="0" cellspacing="0" cellpadding="0">
<tr class="prognamerow"><td class="prognamerow" align="center"><h1>FAQEngine v<?php echo $faqeversion?></h1></td></tr>
<tr class="prognamerow"><td class="pagetitlerow" align="center"><h2><?php echo $l_pwlost?></h2></td></tr>
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
<td align="right" width="30%"><?php echo $l_username?>:</td><td><input class="faqeinput" type="text" name="username" size="40" maxlength="80"></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="recover"><input class="faqebutton" type="submit" value="<?php echo $l_submit?>"></td></tr>
</form>
<?php
		}
		else
		{
			$username=strtolower($username);
			$sql = "select * from ".$tableprefix."_admins where username='$username'";
			if(!$result = faqe_db_query($sql, $db))
			    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
			if($myrow=faqe_db_fetch_array($result))
			{
				if(($myrow["lockpw"]==0) && ($myrow["lockentry"]==0) && $myrow["email"])
				{
					do{
						$maximum=9999999999;
						if($maximum>mt_getrandmax())
							$maximum=mt_getrandmax();
						mt_srand((double)microtime()*1000000);
						$autopin=mt_rand(10000,$maximum);
						$tempsql = "select * from ".$tableprefix."_admins where autopin=$autopin";
						if(!$tempresult = faqe_db_query($tempsql, $db))
							db_die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
					}while($temprow=faqe_db_fetch_array($tempresult));
					$updatesql = "update ".$tableprefix."_admins set autopin=$autopin where username='$username'";
					if(!$updateresult = faqe_db_query($updatesql, $db))
						die("<tr class=\"errorrow\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
					$fromadr = "From:".$faqemail."\r\n";
					$subject = $l_pwlost_mailsubj;
					$subject = str_replace("{sitename}",$faqsitename,$subject);
					$mailmsg = $l_pwlost_mailbody;
					$mailmsg = str_replace("{sitename}",$faqsitename,$mailmsg);
					$mailmsg = str_replace("{tmppw}",$autopin,$mailmsg);
					$mailmsg.="\r\n\r\n";
					if($use_smtpmail)
						mail_smtp($myrow["email"],$subject,$mailmsg,$faqemail);
					else
						mail($myrow["email"],$subject,$mailmsg,$fromadr);
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
<tr class="errrorrow"><td align="center" colspan="2">
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
<td align="right" width="30%"><?php echo $l_username?>:</td><td><input class="faqeinput" type="text" name="username" size="40" maxlength="80"></td></tr>
<tr class="actionrow"><td align="center" colspan="2"><input type="hidden" name="mode" value="recover"><input class="faqebutton" type="submit" value="<?php echo $l_submit?>"></td></tr>
</form>
<?php
}
?>
</table></td></tr></table>
<?php
echo "<hr><div class=\"copyright\" align=\"center\">$copyright_url $copyright_note</div>";
?>
</body></html>
