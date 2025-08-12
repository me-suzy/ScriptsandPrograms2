<?php
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
/***************************************************************************
 * Created by: Boesch IT-Consulting (info@boesch-it.de)
 * (c)2002-2005 Boesch IT-Consulting
 * *************************************************************************/
require('./config.php');
require('./functions.php');
require_once('./includes/htmlMimeMail.inc');
if($use_smtpmail)
{
	require_once('./includes/smtp.inc');
	require_once('./includes/RFC822.inc');
}
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">";
if(!isset($lang) || !$lang)
	$lang=$default_lang;
require('./language/lang_'.$lang.'.php');
$showingform=false;
$sql = "select * from ".$tableprefix."_layout where (layoutnr=1)";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.");
if ($myrow = mysql_fetch_array($result))
{
	$FontFace=$myrow["fontface"];
	$FontSize1=$myrow["fontsize1"];
	$FontSize2=$myrow["fontsize2"];
	$FontSize3=$myrow["fontsize3"];
	$FontSize4=$myrow["fontsize4"];
	$FontSize5=$myrow["fontsize5"];
	$FontColor=$myrow["fontcolor"];
	$TableWidth=$myrow["tablewidth"];
	$heading_bgcolor=$myrow["headingbg"];
	$table_bgcolor=$myrow["bgcolor1"];
	$row_bgcolor=$myrow["bgcolor2"];
	$group_bgcolor=$myrow["bgcolor3"];
	$page_bgcolor=$myrow["pagebg"];
	$HeadingFontColor=$myrow["headingfontcolor"];
	$SubheadingFontColor=$myrow["subheadingfontcolor"];
	$GroupFontColor=$myrow["groupfontcolor"];
	$LinkColor=$myrow["linkcolor"];
	$VLinkColor=$myrow["vlinkcolor"];
	$ALinkColor=$myrow["alinkcolor"];
	$TableDescFontColor=$myrow["tabledescfontcolor"];
	$dateformat=$myrow["dateformat"];
	$progsysmail=$myrow["progsysmail"];
	$psysmailname=$myrow["psysmailname"];
	$server_timezone=$myrow["timezone"];
	$mailsig=$myrow["mailsig"];
	$checkrefs=$myrow["checkrefs"];
	$refchkaffects=$myrow["refchkaffects"];
	$msendlimit=$myrow["msendlimit"];
	if(!$progsysmail)
		$progsysmail="progsys@foo.bar";
}
else
	die("Layout not set up");
if(!isset($prog))
	die($l_callingerror."<br>".$l_notdefined.": prog");
if(($checkrefs==1) && bittst($refchkaffects,BIT_6))
{
	if(!ref_allowed())
		die("Direct linking from this site ($HTTP_REFERER) not allowed");
}
else if($checkrefs==2)
{
	if(ref_forbidden())
		die("Direct linking from this site ($HTTP_REFERER) not allowed");
}
$sql = "select * from ".$tableprefix."_programm where progid='$prog' and language='$lang'";
if(!$result = mysql_query($sql, $db))
	die("Could not connect to the database.");
if(!$myrow=mysql_fetch_array($result))
	die("No such program");
$stylesheet=$myrow["stylesheet"];
$usecustomheader=$myrow["usecustomheader"];
$usecustomfooter=$myrow["usecustomfooter"];
$headerfile=$myrow["headerfile"];
$footerfile=$myrow["footerfile"];
$pageheader=$myrow["pageheader"];
$pagefooter=$myrow["pagefooter"];
$enablenewsletter=$myrow["enablenewsletter"];
$progname=$myrow["programmname"];
$prognr=$myrow["prognr"];
$newsletterfreemailer=$myrow["newsletterfreemailer"];
$maxconfirmtime=$myrow["maxconfirmtime"];
if(strlen($myrow["emailname"])>0)
	$psysmailname=$myrow["emailname"];
if((!$pageheader) && (!$headerfile))
	$usecustomheader=0;
if((!$pagefooter) && (!$footerfile))
	$usecustomfooter=0;
if(!isset($beta))
	$beta=0;
if(($beta==1) && $protectbeta)
{
	if(!isset($REMOTE_USER) || !$REMOTE_USER)
		die($l_forbidden);
}
if(($beta!=0) && ($myrow["hasbeta"]!=1))
	die($l_forbidden);
$prtxt="&quot;$progname";
if($beta==1)
	$prtxt.=" (Beta)";
$prtxt.="&quot;";
$subscriptionprelude=str_replace("{progname}",$prtxt,$l_subscriptionprelude);
$unsubscriptionprelude=str_replace("{progname}",$prtxt,$l_unsubscriptionprelude);
?>
<html>
<head>
<meta name="generator" content="ProgSys v<?php echo $version?>, <?php echo $copyright_asc?>">
<?php
if(file_exists("metadata.php"))
	include ("metadata.php");
include("./includes/styles.inc");
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title><?php echo $l_newsletter_heading?></title>
<?php
echo "<link rel=stylesheet href=\"progsys.css\" type=\"text/css\">";
if($stylesheet)
	echo "<link rel=stylesheet href=\"$stylesheet\" type=\"text/css\">\n";
?>
</head>
<body bgcolor="<?php echo $page_bgcolor?>" link="<?php echo $LinkColor?>" vlink="<?php echo $VLinkColor?>" alink="<?php echo $ALinkColor?>" text="<?php echo $FontColor?>">
<?php
if($usecustomheader==1)
{
	if($headerfile)
		include($headerfile);
	echo $pageheader;
}
?>
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="CENTER" VALIGN="TOP">
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize3?>" color="<?php echo $HeadingFontColor?>"><b><?php echo $l_newsletter_heading?></b></font></td></tr>
<?php
$sql = "select * from ".$tableprefix."_misc";
if(!$result = mysql_query($sql, $db)) {
    die("Could not connect to the database.");
}
if ($myrow = mysql_fetch_array($result))
{
	if($myrow["shutdown"]==1)
	{
?>
</tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $bordercolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr BGCOLOR="<?php echo $contentbgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $contentfont?>" size="<?php echo $contentfontsize?>" color="<?php echo $contentfontcolor?>">
<?php
		$shutdowntext=stripslashes($myrow["shutdowntext"]);
		$shutdowntext = undo_htmlspecialchars($shutdowntext);
		echo $shutdowntext;
		echo "</font></td></tr></table></td></tr></table>";
?>
</td></tr></table>
<?php
		echo "<div align=\"center\">Generated by $copyright_url, $copyright_note</div>";
		exit;
	}
}
if($enablenewsletter==0)
	die("<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"CENTER\"><td align=\"center\">$l_functiondisabled");
if(isset($mode))
{
	if($mode=="subscribe")
	{
?>
<tr bgcolor="<?php echo $heading_bgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize3?>" color="<?php echo $HeadingFontColor?>"><?php echo $subscriptionprelude?></font></td></tr>
<?php
		if(!isset($emailtype))
			$emailtype=1;
		if(!isset($email) || !$email)
		{
			echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
			echo "$l_noemail</td></tr>";
			echo "<tr bgcolor=\"$heading_bgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize1\" color=\"$FontColor\">";
			die("<a href=\"javascript:history.back()\">$l_back</a>");
		}
		if(!validate_email($email))
		{
			echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
			echo "$l_noemail</td></tr>";
			echo "<tr bgcolor=\"$heading_bgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize1\" color=\"$FontColor\">";
			die("<a href=\"javascript:history.back()\">$l_back</a>");
		}
		if($newsletterfreemailer==0)
		{
			if (forbidden_freemailer($email, $db))
			{
				echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
				echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
				echo "$l_forbidden_freemailer</td></tr>";
				echo "<tr bgcolor=\"$heading_bgcolor\" align=\"center\">";
				echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize1\" color=\"$FontColor\">";
				die("<a href=\"javascript:history.back()\">$l_back</a>");
			}
		}
		$sql="select * from ".$tableprefix."_newsletter where email='$email' and programm='$prognr' and listtype='$beta'";
		if(!$result = mysql_query($sql, $db))
			die("<tr bgcolor=\"$row_bgcolor\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
		if($myrow=mysql_fetch_array($result))
		{
				echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
				echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
				if($myrow["confirmed"]==1)
					echo "$l_allready_subscribed</td></tr>";
				else
					echo "$l_allready_pending</td></tr>";
				echo "<tr bgcolor=\"$heading_bgcolor\" align=\"center\">";
				echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize1\" color=\"$FontColor\">";
				die("<a href=\"javascript:history.back()\">$l_back</a>");
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
				$sql = "select * from ".$tableprefix."_newsletter where unsubscribeid=$unsubscribeid";
				if(!$result = mysql_query($sql, $db))
					die("<tr bgcolor=\"$contentbgcolor\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
			}while($myrow=mysql_fetch_array($result));
		}
		else
		{
			$confirmed=0;
			$unsubscribeid=0;
			do{
				$maximum=9999999999;
				if($maximum>mt_getrandmax())
					$maximum=mt_getrandmax();
				mt_srand((double)microtime()*1000000);
				$subscribeid=mt_rand(10000,$maximum);
				$sql = "select * from ".$tableprefix."_newsletter where subscribeid=$subscribeid";
				if(!$result = mysql_query($sql, $db))
					die("<tr bgcolor=\"row_bgcolor\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
			}while($myrow=mysql_fetch_array($result));
		}
		$subscribername="";
		if(strlen($firstname)>0)
			$subscribername.=$firstname;
		if(strlen($surename)>0)
		{
			if(strlen($firstname)>0)
				$subscribername.=" ";
			$subscribername.=$surename;
		}
		$sql = "insert into ".$tableprefix."_newsletter (email, confirmed, subscribeid, enterdate, emailtype, programm, unsubscribeid, listtype, subscribername, userip) ";
		$sql.= "values ('$email', $confirmed, $subscribeid, '$actdate', $emailtype, $prognr, $unsubscribeid, $beta, '$subscribername', '".get_userip()."')";
		if(!$result = mysql_query($sql, $db))
			die("<tr bgcolor=\"$row_bgcolor\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
		if($maxconfirmtime>0)
		{
			$confirmhours=$maxconfirmtime*24;
			$confirmtime="$confirmhours $l_hours";
			$confirmurl=$progsys_fullurl."subscription.php?lang=$lang&mode=confirm&email=".urlencode($email)."&id=$subscribeid&prog=$prog";
			$mailmsg = $l_subscriptionconfirmmail;
			$mailmsg = str_replace("{confirmtime}",$confirmtime,$mailmsg);
			$progtxt=$progname;
			if($beta==1)
				$progtxt.=" (Beta)";
			$mailmsg = str_replace("{progname}",$progtxt,$mailmsg);
			$mailmsg = str_replace("{confirmurl}",$confirmurl,$mailmsg);
			$mailmsg.= "\n\n---\n$mailsig\n\n\n";
			$mailmsg = str_replace("\n",$crlf,$mailmsg);
			$subject = $l_subscriptionconfirmsubject;
			$subject = str_replace("{progname}",$progtxt,$subject);
			@set_time_limit($msendlimit);
			if(strlen($psysmailname)>0)
				$psysfrom="\"".$psysmailname."\" <".$progsysmail.">";
			else
				$psysfrom=$progsysmail;
			$mail = new htmlMimeMail();
			$mail->setCrlf($crlf);
			$mail->setTextCharset($contentcharset);
			$mail->setText($mailmsg);
			$mail->setSubject($subject);
			$mail->setFrom($psysfrom);
			if(strlen($subscribername)>0)
				$tmpreceiver="\"".$subscribername."\" <".$email.">";
			else
				$tmpreceiver=$email;
			$currentreceiver=array($tmpreceiver);
			if($use_smtpmail)
			{
				$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
				$mail->send($currentreceiver, "smtp");
			}
			else
				$mail->send($currentreceiver, "mail");
		}
		echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
		echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
		echo "$l_subscriptiondone</td></tr>";
		if($maxconfirmtime>0)
		{
			echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
			echo "$l_subscriptionconfirminfo</td></tr>";
		}
	}
	if($mode=="confirm")
	{
?>
<tr bgcolor="<?php echo $heading_bgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize3?>" color="<?php echo $HeadingFontColor?>"><?php echo $subscriptionprelude?></font></td></tr>
<?php
		if(!isset($email) || !$email)
		{
			echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
			echo die($l_missingemail);
		}
		if(!isset($id) || !$id)
		{
			echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
			echo die($l_missingid);
		}
		$actdate = date("Y-m-d H:i:s");
		$confirmtime=($maxconfirmtime*24)+1;
		$sql = "select * from ".$tableprefix."_newsletter where email='$email' and subscribeid=$id and enterdate>=DATE_SUB('$actdate', INTERVAL $confirmtime HOUR)";
		if(!$result = mysql_query($sql, $db))
			die("<tr bgcolor=\"$row_bgcolor\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
		if(!$myrow=mysql_fetch_array($result))
		{
			echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
			echo die($l_noconfirmentry);
		}
		do{
			$maximum=9999999999;
			if($maximum>mt_getrandmax())
				$maximum=mt_getrandmax();
			mt_srand((double)microtime()*1000000);
			$unsubscribeid=mt_rand(10000,$maximum);
			$sql = "select * from ".$tableprefix."_newsletter where unsubscribeid=$unsubscribeid";
			if(!$result = mysql_query($sql, $db))
				die("<tr bgcolor=\"$row_bgcolor\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
		}while($myrow=mysql_fetch_array($result));
		$sql = "update ".$tableprefix."_newsletter set subscribeid=0, confirmed=1, unsubscribeid=$unsubscribeid where email='$email' and subscribeid=$id";
		if(!$result = mysql_query($sql, $db))
			die("<tr bgcolor=\"$row_bgcolor\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
		echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
		echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
		echo "$l_subscriptionconfirmed</td></tr>";
	}
	if($mode=="remove")
	{
?>
<tr bgcolor="<?php echo $heading_bgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize3?>" color="<?php echo $HeadingFontColor?>"><?php echo $subscriptionprelude?></font></td></tr>
<?php
		if(!isset($email) || !$email)
		{
			echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
			echo die($l_missingemail);
		}
		if(!isset($id) || !$id)
		{
			echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
			echo die($l_missingid);
		}
		$sql = "select * from ".$tableprefix."_newsletter where email='$email' and unsubscribeid=$id";
		if(!$result = mysql_query($sql, $db))
			die("<tr bgcolor=\"$row_bgcolor\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
		if(!$myrow=mysql_fetch_array($result))
		{
			echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
			echo die($l_noremoveentry);
		}
		$progtxt="<i>$progname";
		if($beta==1)
			$progtxt.=" (Beta)";
		$progtxt.="</i>";
		$removeprelude = str_replace("{email}",$email,$l_subscriptionremoveprelude);
		$removeprelude = str_replace("{progname}",$progtxt,$removeprelude);
?>
<TR BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="2"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>">
<?php echo $removeprelude?></font></td></tr>
<form method="post" action="<?php echo $act_script_url?>">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="email" value="<?php echo $email?>">
<input type="hidden" name="id" value="<?php echo $id?>">
<input type="hidden" name="prog" value="<?php echo $prog?>">
<input type="hidden" name="mode" value="delete">
<input type="hidden" name="beta" value="<?php echo $beta?>">
<TR BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<td align="center" colspan="2"><input class="psysbutton" type="submit" value="<?php echo $l_yes?>"></td></tr></form>
<?php
	}
	if($mode=="unsubscribe")
	{
?>
<tr bgcolor="<?php echo $heading_bgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize3?>" color="<?php echo $HeadingFontColor?>"><?php echo $unsubscriptionprelude?></font></td></tr>
<?php
		if(!isset($email) || !$email)
		{
			echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
			echo "$l_noemail</td></tr>";
			echo "<tr bgcolor=\"$heading_bgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize1\" color=\"$FontColor\">";
			die("<a href=\"javascript:history.back()\">$l_back</a>");
		}
		if(!validate_email($email))
		{
			echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
			echo "$l_noemail</td></tr>";
			echo "<tr bgcolor=\"$heading_bgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize1\" color=\"$FontColor\">";
			die("<a href=\"javascript:history.back()\">$l_back</a>");
		}
		$sql="select * from ".$tableprefix."_newsletter where email='$email' and confirmed=1 and programm='$prognr' and listtype='$beta'";
		if(!$result = mysql_query($sql, $db))
			die("<tr bgcolor=\"$contentbgcolor\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.");
		if(!$myrow=mysql_fetch_array($result))
		{
			echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
			echo "$l_noremoveentry</td></tr>";
			echo "<tr bgcolor=\"$heading_bgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize1\" color=\"$FontColor\">";
			die("<a href=\"javascript:history.back()\">$l_back</a>");
		}
		$confirmurl=$progsys_fullurl."subscription.php?lang=$lang&prog=$prog&mode=remove&email=".urlencode($email)."&id=".urlencode($myrow["unsubscribeid"]);
		$mailmsg = $l_unsubscriptionconfirmmail;
		$progtxt=$progname;
		if($beta==1)
			$progtxt.=" (Beta)";
		$mailmsg = str_replace("{progname}",$progtxt,$mailmsg);
		$mailmsg = str_replace("{confirmurl}",$confirmurl,$mailmsg);
		$mailmsg.= "\n\n---\n$mailsig\n\n\n";
		$mailmsg = str_replace("\n",$crlf,$mailmsg);
		$subject = $l_unsubscriptionconfirmsubject;
		$subject = str_replace("{progname}",$progtxt,$subject);
		@set_time_limit($msendlimit);
		if(strlen($psysmailname)>0)
			$psysfrom="\"".$psysmailname."\" <".$progsysmail.">";
		else
			$psysfrom=$progsysmail;
		$mail = new htmlMimeMail();
		$mail->setCrlf($crlf);
		$mail->setTextCharset($contentcharset);
		$mail->setSubject($subject);
		$mail->setFrom($psysfrom);
	   	$mail->setText($mailmsg);
		$currentreceiver=array($myrow["email"]);
		if($use_smtpmail)
		{
			$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
	        $mail->send($currentreceiver, "smtp");
		}
		else
	    	$mail->send($currentreceiver, "mail");
		echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
		echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
		echo "$l_unsubscribesent</td></tr>";
	}
	if($mode=="delete")
	{
?>
<tr bgcolor="<?php echo $heading_bgcolor?>" align="center">
<td colspan="2"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize3?>" color="<?php echo $HeadingFontColor?>"><?php echo $subscriptionprelude?></font></td></tr>
<?php
		if(!isset($email) || !$email)
		{
			echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
			die($l_missingemail);
		}
		if(!isset($id) || !$id)
		{
			echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
			echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
			die($l_missingid);
		}
		$sql = "delete from ".$tableprefix."_newsletter where email='$email' and unsubscribeid=$id";
		if(!$result = mysql_query($sql, $db))
			die("<tr bgcolor=\"$row_bgcolor\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
		echo "<tr bgcolor=\"$row_bgcolor\" align=\"center\">";
		echo "<td align=\"center\" colspan=\"2\"><font face=\"$FontFace\" size=\"$FontSize2\" color=\"$FontColor\">";
		echo "$l_unsubscribed</td></tr>";
	}
}
else
{
?>
<tr bgcolor="<?php echo $heading_bgcolor?>" align="center">
<td align="center" colspan="2"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize3?>" color="<?php echo $HeadingFontColor?>"><?php echo $subscriptionprelude?></font></td></tr>
<?php
$sql="select * from ".$tableprefix."_texts where textid='subpre' and lang='$lang'";
if(!$result = mysql_query($sql, $db))
	die("<tr bgcolor=\"$row_bgcolor\" align=\"center\"><td align=\"center\" colspan=\"2\">Could not connect to the database.".mysql_error());
if($myrow=mysql_fetch_array($result))
{
	$preludetext=undo_htmlspecialchars(stripslashes($myrow["text"]));
?>
<tr bgcolor="<?php echo $heading_bgcolor?>" align="center">
<td align="center" colspan="2"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $HeadingFontColor?>"><?php echo $preludetext?></font></td></tr>
<?php
}
$showingform=true;
?>
<form action="<?php echo $act_script_url?>" method="post">
<input type="hidden" name="lang" value="<?php echo $lang?>">
<input type="hidden" name="prog" value="<?php echo $prog?>">
<input type="hidden" name="beta" value="<?php echo $beta?>">
<tr bgcolor="<?php echo $row_bgcolor?>" align="center">
<td align="right" width="30%"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><?php echo $l_firstname?>:</font></td>
<td align="left"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><input class="psysinput" type="text" name="firstname" maxlength="240" size="40"></font></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>" align="center">
<td align="right" width="30%"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><?php echo $l_surename?>:</font></td>
<td align="left"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><input class="psysinput" type="text" name="surename" maxlength="240" size="40"></font></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>" align="center">
<td align="right" width="30%"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><a class="remlink" href="#rem1"><sup>*</sup></a>&nbsp;<?php echo $l_email?>:
<?php if($newsletterfreemailer==0) echo "<br>$l_nofreemailer"?></font></td>
<td align="left"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><input class="psysinput" type="text" name="email" maxlength="240" size="40"></font></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>" align="center">
<td align="right" width="30%" valign="top"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><?php echo $l_emailtype?>:
<td align="left"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>">
<input type="radio" name="emailtype" value="0" checked> <?php echo $l_htmlmail?><br>
<input type="radio" name="emailtype" value="1"> <?php echo $l_ascmail?></font></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>" align="center">
<td>&nbsp;</td><td align="left"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>">
<input type="radio" name="mode" value="subscribe" checked><?php echo $l_subscribe?><br>
<input type="radio" name="mode" value="unsubscribe"><?php echo $l_unsubscribe?></font></td></tr>
<TR BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<td align="center" colspan="2"><font face="<?php echo $FontFace?>" size="<?php echo $FontSize2?>" color="<?php echo $FontColor?>"><input class="psysbutton" type="submit" value="<?php echo $l_ok?>"></font></td></tr></form>
<?php
}
echo "</table></td></tr></table>";
if($showingform)
	echo "<br><div align=\"left\"><a name=\"rem1\"><span class=\"remark\"><sup>*</sup></a>&nbsp;".$l_mandatory_fields."</span></div>";
echo "<br><div align=\"center\"><font face=\"$FontFace\" SIZE=\"$FontSize4\">";
echo "<span class=\"timezone\">$l_timezone_note ".timezonename($server_timezone);
$gmtoffset=tzgmtoffset($server_timezone);
if($gmtoffset)
	echo " (".$gmtoffset.")";
echo "</span><br>";
echo "$l_powered_by $copyright_url, $copyright_note</font></div>";
if($usecustomfooter==1)
{
	if($footerfile)
		include($footerfile);
	echo $pagefooter;
}
?>
</body></html>