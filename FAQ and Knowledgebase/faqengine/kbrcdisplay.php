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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
if(!isset($start))
	$start=0;
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
if((!$noseccheck && @fopen("config.php", "a")))
{
	die($l_config_writeable);
}
if(!isset($prog))
	$prog="";
if($ratingspublic!=1)
	die($l_function_disabled);
if(!isset($articlenr))
	die($l_callingerror);
include_once("./includes/rating_display.inc");
?>
<html>
<head>
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
if(file_exists("metadata.php"))
	include_once("./metadata.php");
else
{
?>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $contentcharset?>">
<title><?php echo $l_ratingcomments?></title>
<?php
}
?>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
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
		echo "$pageheader\n";
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
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tblalign?>">
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<TD class="mainheading" ALIGN="CENTER" VALIGN="MIDDLE" WIDTH="95%"><a name="#top">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize3?>; color: <?php echo $HeadingFontColor?>; font-weight: bold;">
<?php echo $l_ratingcomments?></span></a>
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
		echo "</font></td></tr></table></td></tr></table></div>";
		include_once('./includes/bottom.inc');
		exit;
	}
}
?>
</td>
</tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr BGCOLOR="<?php echo $subheadingbgcolor?>" ALIGN="CENTER">
<td colspan="2" class="subheading" align="center">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold;">
<?php echo $l_kbarticle." #".$articlenr?>
</span></td></tr>
<?php
$sql="select * from ".$tableprefix."_kb_ratings where articlenr='$articlenr'";
if(!$result=faqe_db_query($sql,$db))
{
	echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	die("Could not connect to the database.");
}
$num_results=faqe_db_num_rows($result);
if($maxentries>0)
{
	include_once("./includes/faq_pagenav.inc");
	if(isset($start) && ($start>0) && ($num_results>$maxentries))
	{
		$sql .=" limit $start,$maxentries";
	}
	else
	{
		$sql .=" limit $maxentries";
		$start=0;
	}
	if(!$result = faqe_db_query($sql, $db))
		die("Could not connect to the database.".faqe_db_error());
}
if(!$myrow=faqe_db_fetch_array($result))
{
	echo "<tr><td bgcolor=\"$heading_bgcolor\">";
	die("unkown $l_kbarticle #");
}
$numdisplayed=$start+faqe_db_num_rows($result);
do{
		echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
		echo "<td class=\"rating\" bgcolor=\"$group_bgcolor\" colspan=\"2\" valign=\"top\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize5; color: $GroupFontColor; font-weight: bold;\">";
		echo "#".$myrow["entrynr"];
		echo "</span></td></tr>";
		echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
		echo "<td class=\"rating\" bgcolor=\"$group_bgcolor\" colspan=\"2\" valign=\"top\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo display_ratings($myrow["rating"],1);
		echo "</span></td></tr>";
		echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
		echo "<td class=\"rating\" bgcolor=\"$group_bgcolor\" width=\"20%\" valign=\"top\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo "$l_comment:";
		echo "</span></td>";
		$displaycomment=display_encoded($myrow["comment"]);
		$displaycomment=str_replace("\n","<br>",$displaycomment);
		echo "<td class=\"rating\" bgcolor=\"$group_bgcolor\" width=\"80%\" valign=\"top\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo $displaycomment;
		echo "</span></td></tr>";
}while($myrow=faqe_db_fetch_array($result));
if(($maxentries>0) && ($num_results>$maxentries))
{
		$url_start="$act_script_url?$langvar=$act_lang&amp;articlenr=$articlenr";
		echo "<tr bgcolor=\"$actionbgcolor\"><td class=\"pagenav\" align=\"center\" valign=\"middle\" colspan=\"2\">";
		faq_pagenav($start, $maxentries, $num_results, $numdisplayed, $url_start);
		echo "</td></tr>";
}
echo "</table></td></tr></table></div>";
include_once("./includes/bottom.inc");
?>
