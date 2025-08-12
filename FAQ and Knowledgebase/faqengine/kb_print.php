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
$FontColor="#000000";
$heading_bgcolor="#ffffff";
$table_bgcolor="#000000";
$row_bgcolor="#ffffff";
$group_bgcolor="#dddddd";
$page_bgcolor="#ffffff";
$HeadingFontColor="#000000";
$SubheadingFontColor="#000000";
$GroupFontColor="#000000";
$TableDescFontColor="#000000";
$copyrightbgcolor="#ffffff";
$subheadingbgcolor="#ffffff";
$actionbgcolor="#ffffff";
$newinfobgcolor="#ffffff";
$subcatbgcolor="#eeeeee";
$subcatfontcolor="#000000";
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
if((@fopen("./config.php", "a")) && !$noseccheck)
{
	die($l_config_writeable);
}
if(!isset($prog))
	$prog="";
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
<title><?php echo $l_print_heading?></title>
<?php
}
?>
</head>
<body bgcolor="#FFFFFF" link="<?php echo $LinkColor?>" vlink="<?php echo $VLinkColor?>" alink="<?php echo $ALinkColor?>" text="#000000" <?php echo $addbodytags?>>
<?php
if(($usecustomheader==1) && ($printheader==1))
{
	echo "<div style=\"clear:both\">";
	if(($headerfile) && ($headerfilepos==0))
	{
		if(is_phpfile($headerfile))
			include($headerfile);
		else
			file_output($headerfile);
	}
	echo $pageheader;
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
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tblalign?>" VALIGN="TOP">
<tr><TD BGCOLOR="#000000">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
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
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>;">
<?php
		$shutdowntext=stripslashes($myrow["shutdowntext"]);
		$shutdowntext = undo_htmlspecialchars($shutdowntext);
		echo $shutdowntext;
		echo "</span></td></tr></table></td></tr></table></div>";
		include_once('./includes/bottom.inc');
		exit;
	}
}
if(!isset($kbnr))
	die("Calling error. No kbnr found");
$sql = "select * from ".$tableprefix."_kb_articles where (articlenr='$kbnr')";
if(!$result = faqe_db_query($sql, $db))
	die("<tr bgcolor=\"#cccccc\"><td>Could not connect to the database.".faqe_db_error());
if (!$myrow = faqe_db_fetch_array($result))
	die("<tr bgcolor=\"#cccccc\"><td>no such entry");
?>
<tr bgcolor="<?php echo $subheadingbgcolor?>"><td class="subheading" align="center" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold;">
<?php echo $l_kbarticle?> #<?php echo $myrow["articlenr"]?></span></td>
<td align="right" valign="middle" width="5%">
<?php
if($nobacklink==0)
{
	echo "<a class=\"mainaction\" href=\"$url_faqengine/kb.php?mode=display&amp;kbnr=$kbnr&amp;$langvar=$act_lang&amp;prog=$prog";
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
</td>
</tr></table></td></tr>
<tr><TD BGCOLOR="#000000">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<tr bgcolor="<?php echo $group_bgcolor?>"><td align="center" colspan="2" class="kbheading">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize5?>; color: <?php echo $FontColor?>; font-weight: bold;">
<?php echo undo_html_ampersand(stripslashes($myrow["heading"]))?></span></td></tr>
<tr bgcolor="<?php echo $row_bgcolor?>"><td align="right" width="20%" class="proginfo">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php echo $l_program?>:</span></td>
<td width="80%"><span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php
	$sql = "select * from ".$tableprefix."_programm where (prognr=".$myrow["programm"].")";
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr bgcolor=\"#cccccc\"><td align=\"center\">Could not connect to the database (3).");
	}
	if ($temprow = faqe_db_fetch_array($result))
	{
		$progname=display_encoded($temprow["programmname"]);
		$proglang=$temprow["language"];
	}
	else
	{
		$progname=$l_undefined;
		$proglang=$l_none;
	}
	echo "$progname [$proglang]</span></td></tr>";
	$sql = "select os.* from ".$tableprefix."_os os, ".$tableprefix."_kb_os kbos where kbos.articlenr=".$myrow["articlenr"]." and kbos.osnr=os.osnr";
	if(!$result = faqe_db_query($sql, $db)) {
		die("<tr bgcolor=\"#cccccc\"><td align=\"center\">Could not connect to the database (3).");
	}
	if($temprow=faqe_db_fetch_array($result))
	{
		echo "<tr bgcolor=\"$row_bgcolor\"><td width=\"20%\" class=\"os\" align=\"right\" valign=\"top\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo "$l_affectedos:</span></td>";
		$oslist="<ul>";
		do{
			$oslist.="<li>".display_encoded($temprow["osname"]);
		}while($temprow=faqe_db_fetch_array($result));
		$oslist.="</ul>";
		echo "<td width=\"80%\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo "$oslist</span></td></tr>";
	}
	$articletext=stripslashes($myrow["article"]);
	$articletext = undo_htmlspecialchars($articletext);
	$articletext=str_replace("{lang}","$langvar=$act_lang",$articletext);
	$articletext=str_replace("{url_faqengine}",$url_faqengine,$articletext);
	$articletext = str_replace("{bbc_code}",$l_bbccode,$articletext);
	$articletext = str_replace("{bbc_quote}",$l_bbcquote,$articletext);
?>
<tr bgcolor="<?php echo $row_bgcolor?>"><td class="article" align="left" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php echo $articletext?></span></td></tr>
</table></td></tr></table></div>
<?php
if(($usecustomfooter==1) && ($copyrightpos==0) && ($printfooter==1))
{
	echo "<div style=\"clear:both\">";
	if(($footerfile) && ($footerfilepos==0))
	{
		if(is_phpfile($footerfile))
			include($footerfile);
		else
			file_output($footerfile);
	}
	echo $pagefooter;
	if(($footerfile) && ($footerfilepos==1))
	{
		if(is_phpfile($footerfile))
			include($footerfile);
		else
			file_output($footerfile);
	}
	echo "</div>";
}
?>
<div style="clear:both" align="<?php echo $tblalign?>">
<table align="<?php echo $tblalign?>" bgcolor="#ffffff" width="<?php echo $TableWidth?>" border="0">
<tr><td align="center">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize4?>; color: <?php echo $FontColor?>;">
<?php
if($showcurrtime)
{
	$displaytime=date("H:i");
	echo "$l_currtime $displaytime<br>";
}
if($showtimezone==1)
{
	echo "<span class=\"timezone\">$l_timezone_note ".timezonename($server_timezone);
	$gmtoffset=tzgmtoffset($server_timezone);
	if($gmtoffset)
		echo " (".$gmtoffset.")";
	echo "</span><br>";
}
if($contentcopy)
	echo "<br><span class=\"contentcopy\">$l_content ".display_encoded($contentcopy)."</span><br>";
else
	echo "<br><span class=\"contentcopy\">$l_content ".$faqsitename."</span><br>";
echo "$l_generated_with $copyright_url $copyright_note";
if($l_translationnote)
	echo "<br>$l_translationnote";
?>
</td></tr></table>
</div>
<?php
if(($usecustomfooter==1) && ($copyrightpos==1) && ($printfooter==1))
{
	echo "<div style=\"clear:both\">";
	if(($footerfile) && ($footerfilepos==0))
	{
		if(is_phpfile($footerfile))
			include($footerfile);
		else
			file_output($footerfile);
	}
	echo $pagefooter;
	if(($footerfile) && ($footerfilepos==1))
	{
		if(is_phpfile($footerfile))
			include($footerfile);
		else
			file_output($footerfile);
	}
	echo "</div>";
}
?>
</body></html>
