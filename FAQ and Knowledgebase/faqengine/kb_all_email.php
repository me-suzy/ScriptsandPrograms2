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
include_once('./includes/get_settings.inc');
require_once('./includes/block_leacher.inc');
include_once('./includes/htmlMimeMail.inc');
if($use_smtpmail)
{
	include_once('./includes/smtp.inc');
	include_once('./includes/RFC822.inc');
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
if(!language_avail($act_lang))
	die ("Language <b>$act_lang</b> not configured");
include_once('./language/lang_'.$act_lang.'.php');
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
if((@fopen("./config.php", "a")) && !$noseccheck)
{
	die($l_config_writeable);
}
if(($allowemail!=1) || ($allowlists!=1))
	die($l_function_disabled);
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
}
?>
<div align="<?php echo $tblalign?>">
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tblalign?>">
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" WIDTH="95%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize3?>; color: <?php echo $HeadingFontColor?>; font-weight: bold;">
<b><?php echo $l_kb_heading?></span></td>
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
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $FontColor?>;">
<?php
		$shutdowntext=stripslashes($myrow["shutdowntext"]);
		$shutdowntext = undo_htmlspecialchars($shutdowntext);
		echo $shutdowntext;
		echo "</font></td></tr></table></td></tr></table></div>";
		include('./includes/bottom.inc');
		exit;
	}
}
if(!isset($tomail))
{
?>
</table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="LEFT">
<td align="right" width="40%">
<form name="emailform" onsubmit="return checkform();" method="post" action="<?php echo $act_script_url?>">
<?php
if(isset($layout))
	echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="nr" value="<?php echo $nr?>">
<input type="hidden" name="catnr" value="<?php echo $catnr?>">
<input type="hidden" name="prog" value="<?php echo $prog?>">
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
</tr></table></td></tr></table></div>
<?php
	include('./includes/bottom.inc');
	exit;
}
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
<a href="javascript:history.back()"><?php echo $l_back?></a></span>
</td></tr></table></td></tr></table>
<?php
	include('./includes/bottom.inc');
	exit;
}
$asc_mailbody="";
$html_mailbody="";
if(!isset($prog))
	die("Calling error. No prog found");
$sql = "select * from ".$tableprefix."_programm where (progid='$prog') and (language='$lang')";
if(!$result = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
if (!$myrow = faqe_db_fetch_array($result))
   	die($l_nosuchprog);
$progname=$myrow["programmname"];
$asc_mailbody.="$l_kb_heading$crlf";
$asc_mailbody.="$l_progname: ".undo_htmlentities(stripslashes($myrow["programmname"]))."$crlf$crlf";
$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize3;\">$l_kb_heading</span><br><br>";
$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize2;\">$l_progname: ".display_encoded($myrow["programmname"])."</span><br><hr><br>";
$prognr=$myrow["prognr"];
$faqcount=1;
$sql = "select * from ".$tableprefix."_kb_articles where programm='$prognr' and category=0 order by displaypos";
if(!$result = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
if($myrow = faqe_db_fetch_array($result))
{
	$asc_mailbody.=$l_withoutcat."$crlf";
	$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize5;\">";
	$html_mailbody.=$l_withoutcat."</span><br>";
	do{
		$headingtext=stripslashes($myrow["heading"]);
		$headingtext=undo_htmlentities($headingtext);
		$asc_mailbody.="$faqcount. ".$headingtext."$crlf";
		$headingtext=undo_html_ampersand(stripslashes($myrow["heading"]));
		$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
		$html_mailbody.="$faqcount. ".$headingtext."</span><br>";
		$faqcount++;
	}while($myrow = faqe_db_fetch_array($result));
	$asc_mailbody.=$crlf;
}

$sql = "select * from ".$tableprefix."_kb_cat where (programm='$prognr') order by displaypos";
if(!$result = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
while($myrow = faqe_db_fetch_array($result))
{
	$asc_mailbody.=$myrow["catname"]."$crlf";
	$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize5;\">";
	$html_mailbody.=display_encoded($myrow["catname"])."</span><br>";
	$sql = "select * from ".$tableprefix."_kb_articles where (category=".$myrow["catnr"].") and subcategory=0";
	$sql.=" order by displaypos asc";
	if(!$result2 = faqe_db_query($sql, $db))
	   	die("Could not connect to the database.");
	if (!$myrow2 = faqe_db_fetch_array($result2))
	{
		$asc_mailbody.=undo_htmlentities($l_noentries).$crlf.$crlf;
		$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
		$html_mailbody.="$l_noentries</span><br><br>";
	}
	else
	{
		do{
			$headingtext=stripslashes($myrow2["heading"]);
			$headingtext=undo_htmlentities($headingtext);
			$asc_mailbody.="$faqcount. ".$headingtext."$crlf";
			$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
			$headingtext=undo_html_ampersand(stripslashes($myrow2["heading"]));
			$html_mailbody.="$faqcount. ".$headingtext."</span><br>";
			$faqcount++;
		}while($myrow2 = faqe_db_fetch_array($result2));
		$asc_mailbody.=$crlf;
		$html_mailbody.="<br>";
	}
	$sql = "select * from ".$tableprefix."_kb_subcat where category=".$myrow["catnr"]." order by displaypos asc";
	if(!$result2 = faqe_db_query($sql, $db))
	   	die("Could not connect to the database.");
	if ($myrow2 = faqe_db_fetch_array($result2))
	{
		do{
			$asc_mailbody.=" ".$myrow2["catname"]."$crlf";
			$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize5; font-style:italic;\">";
			$html_mailbody.=" ".display_encoded($myrow2["catname"])."</span><br>";
			$sql = "select * from ".$tableprefix."_kb_articles where subcategory=".$myrow2["catnr"]." order by displaypos asc";
			if(!$result3 = faqe_db_query($sql, $db))
			   	die("Could not connect to the database.");
			if (!$myrow3 = faqe_db_fetch_array($result3))
			{
				$asc_mailbody.=undo_htmlentities($l_noentries).$crlf.$crlf;
				$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
				$html_mailbody.=$l_noentries."</span><br><br>";
			}
			else
			{
				do{
					$headingtext=stripslashes($myrow3["heading"]);
					$headingtext=undo_htmlentities($headingtext);
					$asc_mailbody.="$faqcount. ".$headingtext."$crlf";
					$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
					$html_mailbody.="$faqcount. ".undo_html_ampersand(stripslashes($myrow3["heading"]))."</span><br>";
					$faqcount+=1;
				}while($myrow3 = faqe_db_fetch_array($result3));
				$asc_mailbody.=$crlf;
				$html_mailbody.="<br>";
			}
		}while($myrow2 = faqe_db_fetch_array($result2));
	}
}
$asc_mailbody.="$crlf$crlf";
$html_mailbody.="<br><hr><br>";
$faqcount=1;
$sql = "select * from ".$tableprefix."_kb_articles where programm='$prognr' and category=0 order by displaypos asc";
if(!$result = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
if($myrow = faqe_db_fetch_array($result))
{
	$asc_mailbody.=$l_withoutcat."$crlf";
	$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize5;\">";
	$html_mailbody.=$l_withoutcat."</span><br>";
	do{
		$headingtext = stripslashes($myrow["heading"]);
		$headingtext = undo_htmlentities($headingtext);
		$articletext = stripslashes($myrow["article"]);
		$articletext = str_replace("<BR>", $crlf, $articletext);
		$articletext = undo_htmlentities($articletext);
		$articletext = strip_tags($articletext);
		$articletext = str_replace("{bbc_code}",$l_bbccode,$articletext);
		$articletext = str_replace("{bbc_quote}",$l_bbcquote,$articletext);
		$articletext = undo_htmlentities($articletext);
		$asc_mailbody.="$faqcount. ".$headingtext."$crlf";
		$asc_mailbody.=$articletext."$crlf$crlf";
		$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
		$headingtext = undo_html_ampersand(stripslashes($myrow["heading"]));
		$articletext = stripslashes($myrow["article"]);
		$articletext = str_replace("<BR>", $crlf, $articletext);
		$articletext = undo_htmlentities($articletext);
		$articletext = str_replace("{bbc_code}",$l_bbccode,$articletext);
		$articletext = str_replace("{bbc_quote}",$l_bbcquote,$articletext);
		$articletext = str_replace("{lang}","$langvar=$act_lang",$articletext);
		$articletext = str_replace("{url_faqengine}",$faqe_fullurl,$articletext);
		$html_mailbody.="$faqcount. <b>$headingtext</b><br>";
		$html_mailbody.=$articletext."<br><br></span>";
		$faqcount++;
	}while($myrow = faqe_db_fetch_array($result));
	$asc_mailbody.=$crlf.$crlf;
	$html_mailbody.="<br><br>";
}
$sql = "select * from ".$tableprefix."_kb_cat where (programm='$prognr') order by displaypos asc";
if(!$result = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
while($myrow = faqe_db_fetch_array($result))
{
	$asc_mailbody.=$myrow["catname"]."$crlf";
	$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize5;\">";
	$html_mailbody.=display_encoded($myrow["catname"])."</span><br>";
	$sql = "select * from ".$tableprefix."_kb_articles where (category=".$myrow["catnr"].") and subcategory=0";
	$sql.=" order by displaypos asc";
	if(!$result2 = faqe_db_query($sql, $db))
	   	die("Could not connect to the database.");
	if (!$myrow2 = faqe_db_fetch_array($result2))
	{
		$asc_mailbody.=undo_htmlentities($l_noentries).$crlf.$crlf;
		$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
		$html_mailbody.="$l_noentries</span><br><br>";
	}
	else
	{
		do{
			$headingtext=stripslashes($myrow2["heading"]);
			$headingtext=undo_htmlentities($headingtext);
			$articletext=stripslashes($myrow2["article"]);
			$articletext = str_replace("<BR>", $crlf, $articletext);
			$articletext = undo_htmlentities($articletext);
			$articletext = strip_tags($articletext);
			$articletext = undo_htmlentities($articletext);
			$articletext = str_replace("{bbc_code}",$l_bbccode,$articletext);
			$articletext = str_replace("{bbc_quote}",$l_bbcquote,$articletext);
			$asc_mailbody.="$faqcount. ".$headingtext."$crlf";
			$asc_mailbody.=$articletext."$crlf$crlf";
			$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
			$headingtext = undo_html_ampersand(stripslashes($myrow2["heading"]));
			$articletext = stripslashes($myrow2["article"]);
			$articletext = str_replace("<BR>", $crlf, $articletext);
			$articletext = undo_htmlentities($articletext);
			$articletext = str_replace("{bbc_code}",$l_bbccode,$articletext);
			$articletext = str_replace("{bbc_quote}",$l_bbcquote,$articletext);
			$articletext = str_replace("{lang}","$langvar=$act_lang",$articletext);
			$articletext = str_replace("{url_faqengine}",$faqe_fullurl,$articletext);
			$html_mailbody.="$faqcount. <b>$headingtext</b><br>";
			$html_mailbody.=$articletext."<br><br></span>";
			$faqcount+=1;
		}while($myrow2 = faqe_db_fetch_array($result2));
	}
	$sql = "select * from ".$tableprefix."_kb_subcat where category=".$myrow["catnr"]." order by displaypos asc";
	if(!$result2 = faqe_db_query($sql, $db))
	   	die("Could not connect to the database.");
	if ($myrow2 = faqe_db_fetch_array($result2))
	{
		do{
			$asc_mailbody.=" ".$myrow2["catname"]."$crlf";
			$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize5; font-style: italic;\">";
			$html_mailbody.="&nbsp;".display_encoded($myrow2["catname"])."</span><br>";
			$sql = "select * from ".$tableprefix."_kb_articles where subcategory=".$myrow2["catnr"];
			$sql.=" order by displaypos asc";
			if(!$result3 = faqe_db_query($sql, $db))
			   	die("Could not connect to the database.");
			if (!$myrow3 = faqe_db_fetch_array($result3))
			{
				$asc_mailbody.=undo_htmlentities($l_noentries).$crlf.$crlf;
				$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
				$html_mailbody.="$l_noentries</span><br><br>";
			}
			else
			{
				do{
					$headingtext=stripslashes($myrow3["heading"]);
					$headingtext=undo_htmlentities($headingtext);
					$articletext=stripslashes($myrow3["article"]);
					$articletext = str_replace("<BR>", $crlf, $articletext);
					$articletext = undo_htmlentities($articletext);
					$articletext = strip_tags($articletext);
					$articletext = undo_htmlentities($articletext);
					$articletext = str_replace("{bbc_code}",$l_bbccode,$articletext);
					$articletext = str_replace("{bbc_quote}",$l_bbcquote,$articletext);
					$asc_mailbody.="$faqcount. ".$headingtext."$crlf";
					$asc_mailbody.=$articletext."$crlf$crlf";
					$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
					$headingtext = undo_html_ampersand(stripslashes($myrow3["heading"]));
					$articletext = stripslashes($myrow3["article"]);
					$articletext = str_replace("<BR>", $crlf, $articletext);
					$articletext = undo_htmlentities($articletext);
					$articletext = str_replace("{bbc_code}",$l_bbccode,$articletext);
					$articletext = str_replace("{bbc_quote}",$l_bbcquote,$articletext);
					$articletext = str_replace("{lang}","$langvar=$act_lang",$articletext);
					$articletext = str_replace("{url_faqengine}",$faqe_fullurl,$articletext);
					$html_mailbody.="$faqcount. <b>$headingtext</b><br>";
					$html_mailbody.=$articletext."<br><br></span>";
					$faqcount+=1;
				}while($myrow3 = faqe_db_fetch_array($result3));
			}
		} while($myrow2 = faqe_db_fetch_array($result2));
	}
}
$actdate=date("$dateformat H:i");
$asc_mailbody.="\r\n\r\n".$faqe_fullurl."/kb.php?$langvar=$act_lang\r\n\r\n";
$asc_mailbody.="$l_generated: $actdate$crlf";
$tmpmsg="$l_timezone_note ";
$tmpmsg.=timezonename($server_timezone);
$gmtoffset=tzgmtoffset($server_timezone);
if($gmtoffset)
	$tmpmsg.=" (".$gmtoffset.")";
$asc_mailbody.=undo_htmlentities($tmpmsg)."$crlf";
if($contentcopy)
	$asc_mailbody.=undo_htmlentities("$l_content ".undo_htmlspecialchars($contentcopy)).$crlf;
else
	$asc_mailbody.=undo_htmlentities("$l_content ".$faqsitename).$crlf;
$asc_mailbody.=undo_htmlentities($l_generated_with)." FAQEngine v$faqeversion".$crlf;
if($l_translationnote)
	$asc_mailbody.=undo_htmlentities($l_translationnote).$crlf;
$html_mailbody.="<br><hr>";
$myurl=$faqe_fullurl."/kb.php?$langvar=$act_lang";
if(isset($layout))
	$myurl.="&amp;layout=$layout";
$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
$html_mailbody.="<a href=\"$myurl\">$l_kb</a></span><br><hr>";
$html_mailbody.="<span style=\"font-face: $FontFace; font-size: $FontSize4;\">";
$html_mailbody.="$l_generated: $actdate<br>";
$html_mailbody.="$l_timezone_note ";
$html_mailbody.=timezonename($server_timezone);
$html_mailbody.=" (".tzgmtoffset($server_timezone).")<br>";
if($contentcopy)
	$html_mailbody.="<br>$l_content ".display_encoded($contentcopy)."<br>";
else
	$html_mailbody.="<br>$l_content ".$faqsitename."<br>";
$html_mailbody.="$l_generated_with FAQEngine v$faqeversion";
if($l_translationnote)
	$html_mailbody.="<br>$l_translationnote";
$html_mailbody.="</span>";
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
$tmpsql="select * from ".$tableprefix."_texts where textid='kbmasubj' and lang='$act_lang'";
if(!$tmpresult = mysql_query($tmpsql, $db))
	die("Could not connect to the database.");
if($tmprow = mysql_fetch_array($tmpresult))
{
	$mailsubject=undo_htmlspecialchars($tmprow["text"]);
	$mailsubject=str_replace("\r","",$mailsubject);
	$mailsubject=str_replace("\n"," ",$mailsubject);
	$mailsubject=str_replace("{progname}",undo_htmlentities(stripslashes($progname)),$mailsubject);
}
else
	$mailsubject=$l_kbmailallsubject;
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
<?php echo $l_mailsent?>
</font></td>
</tr></table></td></tr></table></div>
<?php
include('./includes/bottom.inc');
?>