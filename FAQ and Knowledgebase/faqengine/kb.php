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
if(!isset($navframe))
	$navframe=0;
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
if(!isset($mode))
	$mode=$kbmode;
if(!isset($start))
	$start=0;
if($ratingspublic==1)
	include_once("./includes/rating_display.inc");
?>
<html>
<head>
<?php
include("./includes/js/global.inc");
include("./includes/js/kbsearch.inc");
?>
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
	include_once("./metadata.php");
else
{
?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
<title><?php echo $l_kb_heading?></title>
<?php
}
?>
<meta name="fid" content="022a9b32a909bf2b875da24f0c8f1225">
</head>
<body bgcolor="<?php echo $page_bgcolor?>" link="<?php echo $LinkColor?>" vlink="<?php echo $VLinkColor?>" alink="<?php echo $ALinkColor?>" text="<?php echo $FontColor?>" <?php echo $addbodytags?>>
<?php
if($usecustomheader==1)
{
	echo "<div style=\"clear:both\">";
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
	echo "</div>";
}
?>
<div align="<?php echo $tblalign?>" style="clear:both">
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tblalign?>" VALIGN="TOP">
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE"
<?php if((isset($prog) || isset($programm)) && ($allowlists==1)) echo "width=\"98%\""?>><a name="#top">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize3?>; color: <?php echo $HeadingFontColor?>; font-weight: bold;">
<?php echo $l_kb_heading?></span></a>
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
<TD ALIGN="CENTER" VALIGN="MIDDLE" colspan="4">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $FontColor?>;">
<?php
		$shutdowntext=stripslashes($myrow["shutdowntext"]);
		$shutdowntext = undo_htmlspecialchars($shutdowntext);
		echo $shutdowntext;
		echo "</span></td></tr></table></td></tr></table></div>";
		include('./includes/bottom.inc');
		exit;
	}
}
if((isset($prog) || (isset($programm) && ($programm>-1))) && ($allowlists==1))
{
	if(isset($programm))
	{
		$sql="select * from ".$tableprefix."_programm where prognr='$programm'";
		if(!$result = faqe_db_query($sql, $db))
	    	die("Could not connect to the database.");
		if ($myrow = faqe_db_fetch_array($result))
			$myprog=$myrow["progid"];
	}
	else
		$myprog=$prog;
	echo "<td width=\"2%\" nowrap>";
	echo "<a class=\"mainaction\" href=\"".$url_faqengine."/kb_all.php?prog=$myprog&amp;$langvar=$act_lang";
	if(isset($layout))
		echo "&amp;layout=$layout";
	echo "\" target=\"kball\">";
	if($listpic)
		echo "<img src=\"$listpic\" border=\"0\" align=\"middle\" title=\"$l_kb_listlink\" alt=\"$l_kb_listlink\"></a></td>";
	else
	{
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $HeadingFontColor;\">";
		echo "[$l_kb_listlink]</span></a> ";
	}
}
?>
</tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<?php
if($navframe==1)
{
	$form_url=$url_faqengine."/kbframe.php";
	$form_target="_parent";
}
else
{
	$form_url=$act_script_url;
	$form_target="_self";
}
if($mode=="listall")
{
	include("./includes/kb_listall.inc");
}
if($mode=="catlist")
{
	include("./includes/kb_catlist.inc");
}
if($mode=="category")
{
	include("./includes/kb_category.inc");
}
if($mode=="proglist")
{
	include("./includes/kb_proglist.inc");
}
if($mode=="display")
{
	include("./includes/kb_display.inc");
}
if($mode=="wizard")
{
	include("./includes/kb_wizard.inc");
}
if($mode=="search")
{
	include("./includes/kb_search.inc");
}
echo "</div>";
include_once('./includes/bottom.inc');
?>