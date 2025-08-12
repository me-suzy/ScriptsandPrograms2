<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('./config.php');
require_once('./functions.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
require_once('./includes/get_settings.inc');
require_once('./includes/block_leacher.inc');
if($emailnews==0)
	die($l_functiondisabled);
setlocale(LC_TIME, $def_locales[$act_lang]);
$pageheading=$l_emailentry;
if($lastvisitcookie==1)
	include("./includes/lastvisit.inc");
$page="newsmail";
include('./includes/header.inc');
echo "<div align=\"$tblalign\"><table width=\"$TableWidth\" border=\"0\" CELLPADDING=\"1\" CELLSPACING=\"0\" class=\"sntable\" align=\"$tblalign\">";
echo "<tr><TD BGCOLOR=\"$bordercolor\">";
echo "<TABLE BORDER=\"0\" CELLPADDING=\"1\" CELLSPACING=\"1\" WIDTH=\"100%\">";
echo "<tr bgcolor=\"$headingbgcolor\">";
echo "<td align=\"center\" colspan=\"2\">";
echo "<font face=\"$headingfont\" size=\"$headingfontsize\" color=\"$headingfontcolor\">";
echo $l_emailentry;
echo "</font></td></tr>";
if(!isset($newsnr))
	die("<tr class=\"errorrow\"><td>Calling error");
if(isset($mode))
{
	include_once('./includes/htmlMimeMail.inc');
	if($use_smtpmail)
	{
		include_once('./includes/smtp.inc');
		include_once('./includes/RFC822.inc');
	}
	$errors=0;
	$errmsg="";
	if(!isset($sendermail) || !$sendermail)
	{
		$errors=1;
			$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.="$l_nosendermail</td></tr>";
	}
	else if(!validate_email($sendermail))
	{
		$errors=1;
		$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		$errmsg.="$l_novalidsendermail</td></tr>";
	}

	if(!isset($receivermail) || !$receivermail)
	{
		$errors=1;
			$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
			$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
			$errmsg.="$l_noreceivermail</td></tr>";
	}
	else if(!validate_email($receivermail))
	{
		$errors=1;
		$errmsg.="<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		$errmsg.="<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		$errmsg.="$l_novalidreceivermail</td></tr>";
	}

	if($errors==1)
	{
		echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
		echo "<td><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo $errmsg;
		echo "</font></td></tr>";
		echo "<tr bgcolor=\"$headingbgcolor\" align=\"center\">";
		echo "<td align=\"center\" colspan=\"2\"><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
		echo "<a class=\"actionlink\" href=\"javascript:history.back()\">$l_back</a></td></tr>";
		echo "</table></td></tr></table>";
		include_once("./includes/footer.inc");
		exit;
	}
	$sql="select * from ".$tableprefix."_data where newsnr=$newsnr";
	if(!$result = mysql_query($sql, $db))
		die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	if(!$myrow=mysql_fetch_array($result))
		die("<tr class=\"errorrow\"><td>No such entry");
	list($mydate,$mytime)=explode(" ",$myrow["date"]);
	list($year, $month, $day) = explode("-", $mydate);
	list($hour, $min, $sec) = explode(":",$mytime);
	$temptime=mktime($hour,$min,$sec,$month,$day,$year);
	$temptime=transposetime($temptime,$servertimezone,$displaytimezone);
	$displaydate=date($dateformat,$temptime);
	$asc_mailmsg="$displaydate:".$crlf;
	$html_mailmsg="<html>";
	if($tblalign!="center")
	{
		$html_mailmsg.= "<head>\n";
		$html_mailmsg.= "<style>\n";
		$html_mailmsg.= "table.sntable {\n";
		$html_mailmsg.= "  float: $tblalign;\n";
		$html_mailmsg.= "}\n";
		$html_mailmsg.= "</style>\n";
		$html_mailmsg.= "</head>\n";
	}
	$html_mailmsg.="<body bgcolor=\"$emailbgcolor\">";
	if($emailcustomheader)
		$html_mailmsg.="<div align=\"$tblalign\">".$emailcustomheader."</div>";
	$html_mailmsg.="<div align=\"$tblalign\"><table width=\"$TableWidth\" border=\"0\" CELLPADDING=\"1\" CELLSPACING=\"0\" ALIGN=\"$tblalign\" VALIGN=\"TOP\" class=\"sntable\">".$crlf;
	$html_mailmsg.="<tr><TD BGCOLOR=\"$bordercolor\">".$crlf;
	$html_mailmsg.="<TABLE BORDER=\"0\" CELLPADDING=\"1\" CELLSPACING=\"1\" WIDTH=\"100%\">".$crlf;
	$html_mailmsg.="<tr bgcolor=\"$timestampbgcolor\"><td align=\"left\">".$crlf;
	$html_mailmsg.="<font face=\"$timestampfont\" size=\"$timestampfontsize\" color=\"$timestampfontcolor\">".$crlf;
	$html_mailmsg.=get_start_tag($timestampstyle);
	$html_mailmsg.=$displaydate;
	$html_mailmsg.=get_end_tag($timestampstyle);
	$html_mailmsg.="</font></td></tr>".$crlf;
	if(strlen($myrow["heading"])>0)
	{
		$html_mailmsg.="<tr bgcolor=\"$newsheadingbgcolor\"><td align=\"left\">".$crlf;
		$html_mailmsg.="<font face=\"$newsheadingfont\" size=\"$newsheadingfontsize\" color=\"$newsheadingfontcolor\">".$crlf;
		$html_mailmsg.=get_start_tag($newsheadingstyle);
		$html_mailmsg.=undo_html_ampersand(do_htmlentities($heading));
		$html_mailmsg.=get_end_tag($newsheadingstyle);
		$html_mailmsg.="</font></td></tr>".$crlf;
		$asc_heading=strip_tags($heading);
		$asc_mailmsg.=$asc_heading;
		$asc_mailmsg.=$crlf;
		for($i=0;$i<strlen($asc_heading);$i++)
			$asc_mailmsg.="-";
		$asc_mailmsg.=$crlf;
	}
	$html_mailmsg.="<tr bgcolor=\"$contentbgcolor\"><td align=\"left\">".$crlf;
	$html_mailmsg.="<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">".$crlf;
	$html_text=stripslashes($myrow["text"]);
	$html_text=undo_htmlspecialchars($html_text);
	$html_text=recode_img_for_emails($html_text);
	$asc_text=str_replace("<BR>",$crlf,$html_text);
	$html_text=str_replace("<BR>","<BR>".$crlf,$html_text);
	$html_mailmsg.=$html_text."</font></td></tr>".$crlf;
	$asc_text=undo_htmlentities($asc_text);
	$asc_text=strip_tags($asc_text);
	$asc_mailmsg.=$asc_text.$crlf;
	if($displayposter && (strlen($myrow["poster"])>0))
	{
		$html_mailmsg.="<tr bgcolor=\"$posterbgcolor\"><td align=\"left\">".$crlf;
		$html_mailmsg.="<font face=\"$posterfont\" size=\"$posterfontsize\" color=\"$posterfontcolor\">".$crlf;
		$html_mailmsg.=get_start_tag($posterstyle);
		$html_mailmsg.="$l_poster: ".do_htmlentities($myrow["poster"]);
		$html_mailmsg.=get_end_tag($posterstyle);
		$html_mailmsg.="</font></td></tr>".$crlf;
	}
	$attachsql="select f.filename, f.filesize, f.mimetype, na.* from ".$tableprefix."_news_attachs na, ".$tableprefix."_files f where f.entrynr=na.attachnr and na.newsnr=$newsnr";
	if(!$attachresult = mysql_query($attachsql, $db))
		die("<tr class=\"errorrow\"><td>Could not connect to the database.".mysql_error());
	if($attachrow=mysql_fetch_array($attachresult))
	{
		do{
			$html_mailmsg.="<tr bgcolor=\"$contentbgcolor\">".$crlf;
			$html_mailmsg.="<td align=\"left\">".$crlf;
			$html_mailmsg.="<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">".$crlf;
			$fileinfo=$attachrow["filename"]." (".format_bytes($attachrow["filesize"]).")";
			$html_mailmsg.="<a href=\"".$simpnews_fullurl."sndownload.php?entrynr=".$attachrow["attachnr"]."\" target=\"_blank\">$l_attachement</a></font></td></tr>".$crlf;
			$html_mailmsg.="</td></tr>";
			$asc_mailmsg.= "$l_attachement: ".$simpnews_fullurl."sndownload.php?entrynr=".$attachrow["attachnr"].$crlf;
		}while($attachrow=mysql_fetch_array($attachresult));
	}
	$asc_mailmsg.=$crlf;
	$html_mailmsg.="<TR BGCOLOR=\"$copyrightbgcolor\" ALIGN=\"CENTER\">".$crlf;
	$html_mailmsg.="<TD ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"";
	$html_mailmsg.="><font face=\"$copyrightfont\" size=\"$copyrightfontsize\" color=\"$copyrightfontcolor\">".$crlf;
	if($contentcopy)
	{
		$html_mailmsg.=$l_content." ".undo_htmlspecialchars(do_htmlentities(stripslashes($contentcopy)))."<br>";
	}
	else
	{
		$html_mailmsg.=$l_content." ".$simpnewssitename."<br>";
	}
	$html_mailmsg.="Powered by SimpNews $version</td></tr>";
	if($l_translationnote)
		$html_mailmsg.="<br>$l_translationnote";
	$html_mailmgs.="</td></tr>".$crlf;
	$html_mailmsg.="</table></td></tr></table></div><br clear=\"all\">".$crlf;
	if($emailcustomfooter)
		$html_mailmsg.="<div align=\"$tblalign\">".$emailcustomfooter."</div><br clear=\"all\">";
	$html_mailmsg=recode_emoticons_for_emails($html_mailmsg);
	$mail = new htmlMimeMail();
	$html_mailmsg.="---<BR>".str_replace("\n","<BR>".$crlf,$defsignature).$crlf;
	$html_mailmsg.="</body></html>";
	$asc_mailmsg.="---".$crlf.str_replace("\n",$crlf,$defsignature);
	$mail->setCrlf($crlf);
	$mail->setTextWrap($mailmaxlinelength);
	$mail->setHTMLCharset($contentcharset);
	$mail->setTextCharset($contentcharset);
	$mail->setHTML($html_mailmsg,$asc_mailmsg,$path_gfx."/");
	if($mailsubject)
		$mailsubject=undo_htmlentities($mailsubject);
	else
		$mailsubject=$l_mailentrysubj;
   	$mail->setSubject($mailsubject);
   	$mail->setFrom($sendermail);
	$receiver=array();
	array_push($receiver,$receivermail);
	if(!$insafemode)
		@set_time_limit($msendlimit);
	if($use_smtpmail)
	{
		$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
		$sendresult=$mail->send($receiver, "smtp");
	}
	else
		$sendresult=$mail->send($receiver, "mail");
	do_emaillog($sendresult,$receivermail,"newsmail.php");
	echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
	echo "<td><font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo $l_entrymailed;
	echo "</font></td></tr>";
}
else
{
	if($emailpageremark)
	{
		echo "</table></td></tr></table></div>\n";
		echo "<div align=\"$tblalign\">$emailpageremark</div>";
		echo "<div align=\"$tblalign\"><table width=\"$TableWidth\" border=\"0\" CELLPADDING=\"1\" CELLSPACING=\"0\" class=\"sntable\" align=\"$tblalign\">";
		echo "<tr><TD BGCOLOR=\"$bordercolor\">";
		echo "<TABLE BORDER=\"0\" CELLPADDING=\"1\" CELLSPACING=\"1\" WIDTH=\"100%\">";
	}
	echo "<form name=\"inputform\" onsubmit=\"return checkform()\" action=\"$act_script_url\" method=\"post\">";
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
	echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
	echo "<input type=\"hidden\" name=\"newsnr\" value=\"$newsnr\">";
	echo "<input type=\"hidden\" name=\"mode\" value=\"send\">";
	echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
	echo "<td align=\"right\" width=\"30%\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo $l_sendermail.": <sup>*</sup>";
	echo "</font></td>";
	echo "<td align=\"left\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo "<input type=\"text\" name=\"sendermail\" class=\"snewsinput\" size=\"40\" maxlength=\"255\">";
	echo "</font></td></tr>";
	echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
	echo "<td align=\"right\" width=\"30%\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo $l_receivermail.": <sup>*</sup>";
	echo "</font></td>";
	echo "<td align=\"left\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo "<input type=\"text\" name=\"receivermail\" class=\"snewsinput\" size=\"40\" maxlength=\"255\">";
	echo "</font></td></tr>";
	echo "<tr bgcolor=\"$contentbgcolor\" align=\"center\">";
	echo "<td align=\"right\" width=\"30%\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo $l_subject.":";
	echo "</font></td>";
	echo "<td align=\"left\">";
	echo "<font face=\"$contentfont\" size=\"$contentfontsize\" color=\"$contentfontcolor\">";
	echo "<input type=\"text\" name=\"mailsubject\" class=\"snewsinput\" size=\"40\" maxlength=\"80\">";
	echo "</font></td></tr>";
	echo "<TR BGCOLOR=\"$headingbgcolor\" ALIGN=\"CENTER\">";
	echo "<td align=\"center\" colspan=\"2\">";
	echo "<input class=\"snewsbutton\" type=\"submit\" value=\"$l_ok\">";
	echo "</td></tr></form>";
}
echo "</table></td></tr></table></div>\n";
include ("./includes/footer.inc");
?>