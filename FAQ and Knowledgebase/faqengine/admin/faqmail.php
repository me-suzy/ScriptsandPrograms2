<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
include_once('./newsfunctions.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
$page="faqemail";
$page_title=$l_emailfaq;
require_once('./heading.php');
if(!isset($storefaqfilter) && ($admstorefaqfilters==1))
{
	$admcookievals="";
	if($new_global_handling)
	{
		if(isset($_COOKIE[$admcookiename]))
			$admcookievals = $_COOKIE[$admcookiename];
	}
	else
	{
		if(isset($_COOKIE[$admcookiename]))
			$admcookievals = $_COOKIE[$admcookiename];
	}
	if($admcookievals)
	{
			if(faqe_array_key_exists($admcookievals,"faqemail_filterlang"))
				$filterlang=$admcookievals["faqemail_filterlang"];
			if(faqe_array_key_exists($admcookievals,"faqemail_sorting"))
				$sorting=$admcookievals["faqemail_sorting"];
	}
}
if(!isset($filterlang))
	$filterlang="none";
if(!isset($sorting))
	$sorting=11;
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if($subscriptionavail==0)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}

if($admin_rights < 2)
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	die("$l_functionnotallowed");
}
if(($userdata["hideemail"]==0) && (strlen($userdata["email"])<1))
{
	echo "<tr class=\"errorrow\"><td align=\"center\">";
	echo "$l_nonewssend<br>";
	echo "$l_reason: $l_noadminmail";
	echo "</td></tr></table></td></tr></table>";
	include('./trailer.php');
	exit;
}
if(isset($mode))
{
	$modsql="select * from ".$tableprefix."_programm_admins where prognr=$input_prognr and usernr=$act_usernr";
	if(!$modresult = faqe_db_query($modsql, $db)) {
		die("<tr class=\"errorrow\"><td align=\"center\">Could not connect to the database.");
	}
	if($modrow=faqe_db_fetch_array($modresult))
		$ismod=1;
	else
		$ismod=0;
	if(($admin_rights<3) && ($ismod==0))
	{
		echo "<tr class=\"errorrow\"><td align=\"center\">";
		die("$l_functionnotallowed");
	}
	include_once('./includes/mail_styles.inc');
	include_once('../includes/htmlMimeMail.inc');
	if($use_smtpmail)
	{
		include_once('../includes/smtp.inc');
		include_once('../includes/RFC822.inc');
	}
	$sql = "select * from ".$tableprefix."_programm where prognr=$input_prognr";
	if(!$result = faqe_db_query($sql, $db))
		die("Could not connect to the database.");
	if (!$myrow = faqe_db_fetch_array($result))
		die($l_nosuchprog);
	if($myrow["htmlmailtype"]==0)
		include_once('./includes/html_mail.inc');
	else
		include_once('./includes/html_mail_2.inc');
	include_once('./includes/asc_mail.inc');
	$prognr=$myrow["prognr"];
	$progid=$myrow["progid"];
	$proglang=$myrow["language"];
	$progname=$myrow["programmname"];
	include('./language/faqmail_'.$proglang.'.php');
	$progname=undo_htmlentities(stripslashes($myrow["programmname"]));
	$subject=str_replace("{progname}",$progname,$l_fm['mailsubject']);
	$html_mailmsg=get_faqsashtml($prognr, $progname, $proglang, $l_fm);
	$asc_mailmsg=get_faqsasasc($prognr, $progname, $l_fm);
	$sql = "select * from ".$tableprefix."_subscriptions where confirmed=1 and language='$proglang' and progid='$progid' group by subscriptionnr";
	if(!$result = mysql_query($sql, $db))
		die("Unable to connect to database.".mysql_error());
	$numreceivers=0;
	while($myrow=mysql_fetch_array($result))
	{
		$receiverdata[$numreceivers]=$myrow;
		$numreceivers++;
	}
	$nummails=0;
	for($i=0;$i<$numreceivers;$i++)
	{
		$myrow=$receiverdata[$i];
		$mail = new htmlMimeMail();
		$mail->setCrlf($crlf);
		$sql2="select * from ".$tableprefix."_texts where lang='$proglang' and textid='mlrem'";
		if(!$result2 = mysql_query($sql2, $db))
			die("Unable to connect to database.".mysql_error());
		if(!$myrow2=mysql_fetch_array($result2))
			$mailremark="";
		else
			$mailremark=$myrow2["text"];
		$unsubscribeurl=$faqe_fullurl."/subscription.php?$langvar=$proglang&prog=".$progid."&id=".$myrow["unsubscribeid"]."&mode=remove&email=".$myrow["email"];
		$unsubscribeurl_html="<font face=\"Verdana, Geneva, Arial, Helvetica, sans-serif\" size=\"1\"><a href=\"$unsubscribeurl\">$unsubscribeurl</a></font>";
		if($mailremark)
			$html_remark=str_replace("{unsubscribeurl}",$unsubscribeurl_html,$mailremark);
		else
			$html_remark="unsubscribe: $unsubscribeurl_html";
		$html_remark=str_replace("\n","<br>".$crlf,$html_remark);
		if($mailremark)
			$asc_remark=str_replace("{unsubscribeurl}",$unsubscribeurl,strip_tags($mailremark));
		else
			$asc_remark="unsubscribe: $unsubscribeurl";
		$asc_remark=str_replace("\n",$crlf,$asc_remark);
		if(($zlibavail==1) && ($myrow["compression"]==1))
		{
			$sql2="select * from ".$tableprefix."_texts where lang='$proglang' and textid='compmail'";
			if(!$result2 = mysql_query($sql2, $db))
				die("Unable to connect to database.".mysql_error());
			if(!$myrow2=mysql_fetch_array($result2))
				$tmpmail=$l_fm['compmail'];
			else
				$tmpmail=$myrow2["text"];
			$tmpmail=str_replace("{progname}",$progname,$tmpmail);
			$tmpmail=str_replace("{proglang}",$proglang,$tmpmail);
			$userhtmlbody=str_replace("{actdate}",date($l_fm['dateformat']),$tmpmail);
			$userascbody=strip_tags(undo_htmlentities($userhtmlbody));
			$attach_html=gzencode($html_mailmsg);
			$attach_asc=gzencode($asc_mailmsg);
			if($myrow["emailtype"]==0)
				$mail->add_attachment($attach_html, "faq.html.gz", "application/x-gzip-compressed");
			$mail->add_attachment($attach_asc, "faq.txt.gz", "application/x-gzip-compressed");
		}
		else
		{
			$userhtmlbody=$html_mailmsg;
			$userascbody=$asc_mailmsg;
		}
		if(strlen($html_remark)>0)
			$userhtmlbody.="<hr>$html_remark<br><br>".$crlf;
		if($defmailsig)
			$userhtmlbody.="---<BR>".str_replace("\n","<BR>".$crlf,$defmailsig).$crlf;
		if(strlen($asc_remark)>0)
			$userascbody.="---------------------------".$crlf.$asc_remark.$crlf.$crlf;
		if($defmailsig)
			$userascbody.="---".$crlf.str_replace("\n",$crlf,$defmailsig);
		$mail->setTextCharset($contentcharset);
		if($myrow["emailtype"]==0)
		{
			$mail->setHTMLCharset($contentcharset);
	        $mail->setHTML($userhtmlbody, $userascbody);
	    }
	    else
	    	$mail->setText($userascbody);
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
	    $nummails++;
	}
	$actdate = date($db_dateformat_short);
	$sql="update ".$tableprefix."_programm set lastmailed='$actdate' where progid='$progid' and language='$proglang'";
	if(!$result = mysql_query($sql, $db))
	    die("Unable to connect to database.".mysql_error());
	echo "<tr class=\"displayrow\" align=\"center\"><td>";
	echo str_replace("{nummails}",$nummails,$l_mailssent);
	echo "</td></tr></table></td></tr></table>";
	echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_emailfaq</a></div>";
}
else
{
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
$sql="select * from ".$tableprefix."_programm ";
if(isset($filterlang) && ($filterlang!="none"))
	$sql.="where language='$filterlang' ";
switch($sorting)
{
	case 12:
		$sql.=" order by prognr desc";
		break;
	case 21:
		$sql.=" order by progid asc";
		break;
	case 22:
		$sql.=" order by progid desc";
		break;
	case 31:
		$sql.=" order by programmname asc";
		break;
	case 32:
		$sql.=" order by programmname desc";
		break;
	case 41:
		$sql.=" order by lastmailed asc";
		break;
	case 42:
		$sql.=" order by lastmailed desc";
		break;
	default:
		$sql.=" order by prognr asc";
		break;
}
if(!$result = faqe_db_query($sql, $db)) {
    die("<tr class=\"errorrow\"><td>Could not connect to the database.");
}
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<tr class=\"displayrow\"><td align=\"center\">";
	echo $l_noentries;
	echo "</td></tr></table></td></tr></table>";
}
else
{
	$baseurl="$act_script_url?$langvar=$act_lang";
	if(isset($filterlang))
		$baseurl.="&filterlang=$filterlang";
	if($admstorefaqfilters==1)
		$baseurl.="&storefaqfilter=1";
	$maxsortcol=4;
	echo "<tr class=\"rowheadings\">";
	echo "<td align=\"center\" width=\"10%\">";
	$sorturl=getSortURL($sorting, 1, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>#</b></a>";
	echo getSortMarker($sorting, 1, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"20%\">";
	$sorturl=getSortURL($sorting, 2, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_id</b></a>";
	echo getSortMarker($sorting, 2, $maxsortcol);
	echo "</td>";
	echo "<td align=\"center\" width=\"40%\">";
	$sorturl=getSortURL($sorting, 3, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_progname</b></a>";
	echo getSortMarker($sorting, 3, $maxsortcol);
	echo "</td>";
	echo "<td class=\"rowheadings\" align=\"center\" width=\"10%\"><b>$l_language</b></td>";
	echo "<td align=\"center\" width=\"20%\">";
	$sorturl=getSortURL($sorting, 4, $maxsortcol, $baseurl);
	echo "<a class=\"rowheadings\" href=\"".do_url_session($sorturl)."\">";
	echo "<b>$l_lastsent</b></a>";
	echo getSortMarker($sorting, 4, $maxsortcol);
	echo "</td>";
	echo "<td>&nbsp;</td></tr>";
	do {
		$act_id=$myrow["prognr"];
		echo "<tr class=\"displayrow\">";
		echo "<td align=\"center\">".$myrow["prognr"]."</td>";
		echo "<td align=\"center\">".$myrow["progid"]."</td>";
		echo "<td>";
		echo display_encoded($myrow["programmname"]);
		echo "</td>";
		echo "<td align=\"center\">".$myrow["language"]."</td>";
		echo "<td align=\"center\">".$myrow["lastmailed"]."</td>";
		echo "<td>";
		$modsql="select * from ".$tableprefix."_programm_admins where prognr=$act_id and usernr=$act_usernr";
		if(!$modresult = faqe_db_query($modsql, $db)) {
		    die("Could not connect to the database.");
		}
		if($modrow=faqe_db_fetch_array($modresult))
			$ismod=1;
		else
			$ismod=0;
		if(($admin_rights>2) || ($ismod==1))
		{
			echo "<a class=\"listlink2\" href=\"".do_url_session("$act_script_url?mode=sendfaq&input_prognr=$act_id&$langvar=$act_lang")."\">";
			echo "<img src=\"gfx/sendmail.gif\" border=\"0\" title=\"$l_emailfaq2\" alt=\"$l_emailfaq2\"></a>";
		}
		echo "</td></tr>";
	} while($myrow = faqe_db_fetch_array($result));
	echo "</table></tr></td></table>";
}
if($admin_rights > 1)
	include('./includes/language_filterbox.inc');
}
include('./trailer.php');
?>