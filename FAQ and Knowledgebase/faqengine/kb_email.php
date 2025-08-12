<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('./config.php');
require_once('./functions.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
require_once('./includes/get_settings.inc');
require_once('./includes/block_leacher.inc');
include_once('./includes/htmlMimeMail.inc');
if($use_smtpmail)
{
	include_once('./includes/smtp.inc');
	include_once('./includes/RFC822.inc');
}
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">";
if(!language_avail($act_lang))
	die ("Language <b>$act_lang</b> not configured");
include_once('./language/lang_'.$act_lang.'.php');
if(!isset($nobacklink))
	$nobacklink=0;
if($blockoldbrowser==1)
{
	if(is_ns3() || is_msie3())
	{
		$sql="select * from ".$tableprefix."_texts where textid='oldbrowser' and lang='$act_lang'";
		if(!$result = mysql_query($sql, $db))
		    die("Could not connect to the database.");
		if($myrow = mysql_fetch_array($result))
			echo undo_htmlspecialchars($myrow["text"]);
		else
			echo $l_oldbrowser;
		exit;
	}
}
if($allowemail!=1)
	die ("Function disabled");
if(!isset($kbnr))
	die ("Calling error");
if((@fopen("./config.php", "a")) && !$noseccheck)
{
	die($l_config_writeable);
}
?>
<html>
<head>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<?php
if(is_ns4() && $ns4style)
	echo"<link rel=stylesheet href=\"$ns4style\" type=\"text/css\">\n";
else if(is_ns6() && $ns6style)
	echo"<link rel=stylesheet href=\"$ns6style\" type=\"text/css\">\n";
else if(is_opera() && $operastyle)
	echo"<link rel=stylesheet href=\"$operastyle\" type=\"text/css\">\n";
else if(is_konqueror() && $konquerorstyle)
	echo"<link rel=stylesheet href=\"$konquerorstyle\" type=\"text/css\">\n";
else if(is_gecko() && $geckostyle)
	echo"<link rel=stylesheet href=\"$geckostyle\" type=\"text/css\">\n";
else if($stylesheet)
	echo"<link rel=stylesheet href=\"$stylesheet\" type=\"text/css\">\n";
include_once('./includes/styles.inc');
if(file_exists("./metadata.php"))
	include ("./metadata.php");
else
{
?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
<title><?php echo $l_kb_heading?></title>
<?php
}
?>
<script type="text/javascript" language="JavaScript" src="./js/emailcheck.js"></script>
<script type="text/javascript" language="JavaScript">
<!--
function checkform()
{
	if(document.emailform.frommail.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_nofrommail)?>");
		return false;
	}
	if(!emailCheck(document.emailform.frommail.value))
	{
		alert("<?php echo undo_htmlentities($l_invalidfrommail)?>");
		return false;
	}
	if(document.emailform.tomail.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_notomail)?>");
		return false;
	}
	if(!emailCheck(document.emailform.tomail.value))
	{
		alert("<?php echo undo_htmlentities($l_invalidtomail)?>");
		return false;
	}
	return true;
}
//  End -->
</script>
</head>
<body bgcolor="<?php echo $page_bgcolor?>" link="<?php echo $LinkColor?>" vlink="<?php echo $VLinkColor?>" alink="<?php echo $ALinkColor?>" text="<?php echo $FontColor?>" <?php echo $addbodytags?>>
<?php
if($usecustomheader==1)
{
	echo "<div style=\"clear:both\">";
	if(($headerfile) && ($headerfilepos==0))
	{
		if(is_phpfile($headerfile))
			include($headerfile);
		else
			file_output($headerfile);
	}
	echo "$pageheader";
	if(($headerfile) && ($headerfilepos==1))
	{
		if(is_phpfile($headerfile))
			include($headerfile);
		else
			file_output($headerfile);
	}
	echo "</div>";
}
?>
<div align="<?php echo $tblalign?>" style="clear:both">
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tblalign?>">
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" WIDTH="95%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize3?>; color: <?php echo $HeadingFontColor?>; font-weight: bold;">
<?php echo $l_kb_heading?></span></td>
<td align="right" valign="middle" width="5%">
<?php
if($nobacklink==0)
{
	echo "<a class=\"mainaction\" href=\"kb.php?mode=display&amp;kbnr=$kbnr&amp;$langvar=$act_lang&amp;prog=$prog";
	if(isset($layout))
		echo "&amp;layout=$layout";
	echo "\">";
	if($backpic)
		echo "<img src=\"$backpic\" border=\"0\" title=\"$l_kblink\" alt=\"$l_kblink\"></a>";
	else
	{
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $HeadingFontColor;\">";
		echo "[$l_back]</span></a> ";
	}
}
else
{
?>
<span stlye="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize4?>;">
<a class="pFo" href="javascript:parent.window.focus();top.window.close()"><img src="<?php echo $closepic?>" border="0" title="<?php echo $l_close?>" alt="<?php echo $l_close?>"></a>
</span>
<?php
}
?>
</td></tr>
<?php
$sql = "select * from ".$tableprefix."_misc";
if(!$result = faqe_db_query($sql, $db)) {
    die("Could not connect to the database.");
}
if ($myrow = faqe_db_fetch_array($result))
{
	if($myrow["shutdown"]==1)
	{
?>
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php
		$shutdowntext=stripslashes($myrow["shutdowntext"]);
		$shutdowntext = undo_htmlspecialchars($shutdowntext);
		echo $shutdowntext;
		echo "</span></td></tr></table></td></tr></table></div>";
		include_once('./includes/bottom.inc');
		exit;
	}
}
if(!isset($email))
{
?>
</table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="LEFT">
<td align="right" width="40%">
<form name="emailform" onsubmit="return checkform();" method="post" action="<?php echo $act_script_url?>">
<?php
if($nobacklink==1)
	echo "<input type=\"hidden\" name=\"nobacklink\" value=\"1\">";
if(isset($layout))
	echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="kbnr" value="<?php echo $kbnr?>">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php echo $l_sendername?>:</span></td>
<td align="left" width="60%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<input class="faqeinput" type="text" name="fromname" size="30" maxlength="100"></span></td></tr>
<TR BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="LEFT">
<td align="right" width="40%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>; font-weight: bold;">
<?php echo $l_sendermail?>:</span></td>
<td align="left" width="60%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<input class="faqeinput" type="text" name="frommail" size="30" maxlength="100"></span></td></tr>
<TR BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="LEFT">
<td align="right" width="40%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>; font-weight: bold;">
<?php echo $l_receivermail?>:</span></td>
<td align="left" width="60%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<input class="faqeinput" type="text" name="tomail" size="30" maxlength="100"></span></td></tr>
<TR BGCOLOR="<?php echo $actionbgcolor?>" ALIGN="LEFT">
<td align="center" colspan="2"><input class="faqebutton" type="submit" name="email" value="<?php echo $l_send?>"></td></form></tr>
<?php
}
else
{
	$errors=0;
	$errmsg="";
	if(!isset($frommail) || !$frommail)
	{
		$errmsg .= "$l_nofrommail<br>";
		$errors=1;
	}
	if(!isset($tomail) || !$tomail)
	{
		$errmsg .= "$l_notomail<br>";
		$errors=1;
	}
	if(!validate_email($frommail))
	{
		$errmsg .= "$l_invalidfrommail<br>";
		$errors=1;
	}
	if(!validate_email($tomail))
	{
		$errmsg .= "$l_invalidtomail<br>";
		$errors=1;
	}
	if($errors==1)
	{
?>
</table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="LEFT">
<td align="center" width="100%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php echo $errmsg?></span></td>
<tr bgcolor="<?php echo $actionbgcolor?>" align="center"><td>
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $actionlinefontsize?>; color: <?php echo $FontColor?>;">
<a href="javascript:history.back()"><?php echo $l_back?></a>
</span></td></tr></table></td></tr></table></div>
<?php
		include_once('./includes/bottom.inc');
		exit;
	}
	$sql = "select * from ".$tableprefix."_kb_articles where (articlenr='$kbnr')";
	if(!$result = faqe_db_query($sql, $db))
		die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.".faqe_db_error());
	if (!$myrow = faqe_db_fetch_array($result))
		die("<tr bgcolor=\"#cccccc\"><td>no such entry");
	$sql = "select * from ".$tableprefix."_programm where (prognr=".$myrow["programm"].")";
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr bgcolor=\"#cccccc\"><td align=\"center\">Could not connect to the database (3).");
	}
	if ($temprow = faqe_db_fetch_array($result))
	{
		$asc_progname=undo_htmlentities(stripslashes($temprow["programmname"]));
		$html_progname=display_encoded($temprow["programmname"]);
	}
	else
	{
		$asc_progname=$l_undefined;
		$html_progname=$l_undefined;
	}
	$articlelink=$faqe_fullurl."/kb.php?mode=display&$langvar=$act_lang&kbnr=$kbnr";
	if(isset($layout))
		$articlelink.="&layout=$layout";
	$headingtext=stripslashes($myrow["heading"]);
	$headingtext=undo_htmlentities($headingtext);
	$articletext=stripslashes($myrow["article"]);
	$articletext = str_replace("<BR>", "\r\n", $articletext);
	$articletext = undo_htmlentities($articletext);
	$articletext = strip_tags($articletext);
	$articletext = str_replace("{bbc_code}",$l_bbccode,$articletext);
	$articletext = str_replace("{bbc_quote}",$l_bbcquote,$articletext);
	$articletext = undo_htmlentities($articletext);
	$asc_mailbody = $l_mailprelude."\r\n";
	$asc_mailbody .= "$asc_progname: ".$headingtext."\r\n\r\n";
	$asc_mailbody .= $articletext;
	$asc_mailbody .= "\r\n\r\n$articlelink";
	if($contentcopy)
		$asc_mailbody.="\r\n\r\n".undo_htmlentities("$l_content ".undo_htmlspecialchars($contentcopy)).$crlf;
	else
		$asc_mailbody.="\r\n\r\n".undo_htmlentities("$l_content ".$faqsitename).$crlf;
	$asc_mailbody.= "\r\n\r\n";
	$asc_mailbody.=undo_htmlentities($l_generated_with)." FAQEngine V$faqeversion".$crlf;
	if($l_translationnote)
		$asc_mailbody.=undo_htmlentities($l_translationnote).$crlf;
	$headingtext=undo_html_ampersand(stripslashes($myrow["heading"]));
	$articletext = stripslashes($myrow["article"]);
	$articletext = str_replace("<BR>", $crlf, $articletext);
	$articletext = undo_htmlentities($articletext);
	$articletext = str_replace("{bbc_code}",$l_bbccode,$articletext);
	$articletext = str_replace("{bbc_quote}",$l_bbcquote,$articletext);
	$articletext = str_replace("{lang}","$langvar=$act_lang",$articletext);
	$articletext = str_replace("{url_faqengine}",$faqe_fullurl,$articletext);
	$html_mailbody = "<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
	$html_mailbody.= $l_mailprelude."</span><br>";
	$html_mailbody.= "<span style=\"font-face: $FontFace; font-size: $FontSize5;\">";
	$html_mailbody.= $html_progname.": ".$headingtext."</span><br><br>";
	$html_mailbody.= "<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
	$html_mailbody.= $articletext."</span><br><br>";
	$html_mailbody.="<br><hr>";
	$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
	$html_mailbody.="<a href=\"$articlelink\">$l_kb_article</a></span><br><hr>";
	$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize4;\">";
	if($contentcopy)
		$html_mailbody.="$l_content ".display_encoded($contentcopy)."<br>";
	else
		$html_mailbody.="$l_content ".$faqsitename."<br>";
	$html_mailbody.= "$l_generated_with FAQEngine v$faqeversion";
	if($l_translationnote)
		$html_mailbody.="<br>$l_translationnote";
	$html_mailbody.= "</span>";
	$tmpsql="select * from ".$tableprefix."_texts where textid='kbmnote' and lang='$act_lang'";
	if(!$tmpresult = mysql_query($tmpsql, $db))
		die("Could not connect to the database.");
	if($tmprow = mysql_fetch_array($tmpresult))
	{
		$mailnote=undo_htmlspecialchars(stripslashes($tmprow["text"]));
		$mailnote=str_replace("{sendermail}",$frommail,$mailnote);
		$mailnote=str_replace("{sendername}",$fromname,$mailnote);
		$mailnote_asc=str_replace("<BR>","\r\n",$mailnote);
		$mailnote_asc=undo_htmlentities($mailnote_asc);
		$asc_mailbody.=$crlf;
		$asc_mailbody.=$mailnote_asc.$crlf;
		$html_mailbody.="<br>";
		$html_mailbody.=$mailnote;
		$html_mailbody.="<br>";
	}
	$mail = new htmlMimeMail();
	$mail->setCrlf($crlf);
	$mail->setTextCharset($contentcharset);
	if($disablehtmlemail==0)
	{
		$mail->setHTMLCharset($contentcharset);
		$mail->setHTML($html_mailbody, $asc_mailbody);
	}
	else
		$mail->setText($asc_mailbody);
	if($fromname)
		$fromadr = "\"$fromname\" <$frommail>";
	else
		$fromadr = $frommail;
	$tmpsql="select * from ".$tableprefix."_texts where textid='kbmsubj' and lang='$act_lang'";
	if(!$tmpresult = mysql_query($tmpsql, $db))
		die("Could not connect to the database.");
	if($tmprow = mysql_fetch_array($tmpresult))
	{
		$mailsubject=undo_htmlspecialchars($tmprow["text"]);
		$mailsubject=str_replace("\r","",$mailsubject);
		$mailsubject=str_replace("\n"," ",$mailsubject);
		$mailsubject=str_replace("{progname}",$asc_progname,$mailsubject);
	}
	else
		$mailsubject=$l_kb_mailsubject;
	$mail->setSubject($mailsubject);
	$mail->setFrom($fromadr);
	if(!$insafemode)
		@set_time_limit($msendlimit);
	$receiver=array();
	array_push($receiver,$tomail);
	if($use_smtpmail)
	{
		$mail->setSMTPParams($smtpserver,$smtpport,NULL,$smtpauth,$smtpuser,$smtppasswd);
		$mail->send($receiver, "smtp");
	}
	else
		$mail->send($receiver, "mail");
?>
</table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="LEFT">
<td align="center" width="100%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php echo $l_mailsent?></span></td>
<?php
}
echo "</tr></table></td></tr></table></div>";
include_once('./includes/bottom.inc');
?>