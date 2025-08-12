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
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">";
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
if(!isset($prog))
	die($l_calling_error);
if($allowlists!=1)
	die($l_function_disabled);
?>
<html>
<head>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<meta name="fid" content="022a9b32a909bf2b875da24f0c8f1225">
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
</head>
<body bgcolor="<?php echo $page_bgcolor?>" link="<?php echo $LinkColor?>" vlink="<?php echo $VLinkColor?>" alink="<?php echo $ALinkColor?>" text="<?php echo $FontColor?>" <?php echo $addbodytags?>>
<?php
if($usecustomheader==1)
{
	if(($headerfile) && ($headerfilepos==0))
	{
		if(is_phpfile($headerfile))
			include_once($headerfile);
		else
			file_output($headerfile);
	}
	echo $pageheader;
	if(($headerfile) && ($headerfilepos==1))
	{
		if(is_phpfile($headerfile))
			include_once($headerfile);
		else
			file_output($headerfile);
	}
}
?>
<div align="<?php echo $tblalign?>">
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tblalign?>" VALIGN="TOP">
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" width="95%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize3?>; color: <?php echo $HeadingFontColor?>; font-weight: bold;">
<?php echo $l_kb_heading?></span>
</td>
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
</tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php
		$shutdowntext=stripslashes($myrow["shutdowntext"]);
		$shutdowntext = undo_htmlspecialchars($shutdowntext);
		echo $shutdowntext;
		echo "</span></td></tr></table></td></tr></table></div>";
		include('./includes/bottom.inc');
		exit;
	}
}
?>
<td align="right" valign="MIDDLE" width="5%">
<span stlye="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize4?>;">
<a class="pFo" href="javascript:parent.window.focus();top.window.close()"><img src="<?php echo $closepic?>" border="0" title="<?php echo $l_close?>" alt="<?php echo $l_close?>"></a>
</span></td></tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr BGCOLOR="<?php echo $group_bgcolor?>" ALIGN="CENTER">
<?php
$link=$url_faqengine."/kb_all_html.php?prog=$prog&amp;$langvar=$act_lang";
if(isset($layout))
	$link.="&amp;layout=$layout";
echo "<td align=\"center\" valign=\"middle\" bgcolor=\"$group_bgcolor\" class=\"rowlink\"";
if($hovercells==1)
	echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$group_bgcolor'\" onclick=\"window.location.href='$link'\"";
echo ">";
?>
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<a href="<?php echo $link?>"><?php echo $l_kb_htmllist?></a></span></td></tr>
<tr BGCOLOR="<?php echo $group_bgcolor?>" ALIGN="CENTER">
<?php
$link=$url_faqengine."/kb_all_print.php?prog=$prog&amp;$langvar=$act_lang";
if(isset($layout))
	$link.="&amp;layout=$layout";
echo "<td align=\"center\" valign=\"middle\" bgcolor=\"$group_bgcolor\" class=\"rowlink\"";
if($hovercells==1)
	echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$group_bgcolor'\" onclick=\"window.location.href='$link'\"";
echo ">";
?>
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<a href="<?php echo $link?>"><?php echo $l_kb_printlist?></a>
</span></td></tr>
<?php
if($disableasclist==0)
{
	echo "<tr BGCOLOR=\"$group_bgcolor\" ALIGN=\"CENTER\">";
	$link=$url_faqengine."/kb_all_ascii.php?prog=$prog&amp;$langvar=$act_lang";
	if(isset($layout))
		$link.="&amp;layout=$layout";
	echo "<td align=\"center\" valign=\"middle\" bgcolor=\"$group_bgcolor\" class=\"rowlink\"";
	if($hovercells==1)
		echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$group_bgcolor'\" onclick=\"window.location.href='$link'\"";
	echo ">";
?>
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<a href="<?php echo $link?>"><?php echo $l_kb_asciilist?></a></span></td></tr>
<?php
}
if($allowemail==1)
{
	echo "<tr BGCOLOR=\"$group_bgcolor\" ALIGN=\"CENTER\">";
	$link=$url_faqengine."/kb_all_email.php?prog=$prog&amp;$langvar=$act_lang";
	if(isset($layout))
		$link.="&amp;layout=$layout";
	echo "<td align=\"center\" valign=\"middle\" bgcolor=\"$group_bgcolor\" class=\"rowlink\"";
	if($hovercells==1)
		echo " onMouseOver=\"this.style.backgroundColor='$activcellcolor';\" onMouseOut=\"this.style.backgroundColor='$group_bgcolor'\" onclick=\"window.location.href='$link'\"";
	echo ">";
?>
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<a href="<?php echo $link?>"><?php echo $l_kb_maillist?></a></span></td></tr>
<?php
}
echo "</table></td></tr></table></div>";
include('./includes/bottom.inc');
?>