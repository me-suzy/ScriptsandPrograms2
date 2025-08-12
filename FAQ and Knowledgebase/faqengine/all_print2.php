<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require('./config.php');
require('./functions.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
if(!language_avail($act_lang))
	die ("Language <b>$act_lang</b> not configured");
include_once('./includes/get_settings.inc');
require_once('./includes/block_leacher.inc');
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
if(($zlibavail==1) && ($allsendcompressed==1))
	ob_start("ob_gzhandler");
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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
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
<title><?php echo $l_heading?></title>
<?php
}
?>
</head>
<body bgcolor="#ffffff" text="#000000" <?php echo $addbodytags?>>
<?php
if(($usecustomheader==1) && ($printheader==1))
{
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
}
$sql = "select * from ".$tableprefix."_misc";
if(!$result = faqe_db_query($sql, $db)) {
    die("Could not connect to the database.");
}
if ($myrow = faqe_db_fetch_array($result))
{
	if($myrow["shutdown"]==1)
	{
		$shutdowntext=stripslashes($myrow["shutdowntext"]);
		$shutdowntext = undo_htmlspecialchars($shutdowntext);
		echo $shutdowntext;
		exit;
	}
}
if(!isset($prog))
	die($l_calling_error);
if($allowlists!=1)
	die($l_function_disabled);
$sql = "select * from ".$tableprefix."_programm where (progid='$prog') and (language='$lang')";
if(!$result = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
if (!$myrow = faqe_db_fetch_array($result))
   	die($l_nosuchprog);
?>
<div align="<?php echo $tblalign?>">
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tblalign?>">
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER"><TD ALIGN="CENTER" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize3?>; color: <?php echo $HeadingFontColor?>; font-weight: bold;">
<?php echo $l_heading?></span></td></tr>
<TR BGCOLOR="<?php echo $subheadingbgcolor?>" ALIGN="CENTER"><TD ALIGN="CENTER" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold;">
<?php echo "$l_progname: ".display_encoded($myrow["programmname"])?></span></td></tr>
<?php
$prognr=$myrow["prognr"];
$sql = "select * from ".$tableprefix."_category where (programm='$prognr') order by displaypos";
if(!$result = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
if (!$myrow = faqe_db_fetch_array($result))
   	die($l_noentries);
$catcount=0;
?>
<TR BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER"><TD ALIGN="center" VALIGN="MIDDLE">
<table bgcolor="<?php echo $row_bgcolor?>" width="100%" cellpadding="0" cellspacing="0">
<tr><td><ul class="faqe">
<?php
do{
	$catcount++;
	$subcatcount=1;
?>
<li>
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $GroupFontColor?>;">
<?php echo $catcount.". ".display_encoded($myrow["categoryname"])?></span><br>
<?php
$sql = "select * from ".$tableprefix."_subcategory where (category=".$myrow["catnr"].")";
if(!$result2 = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
$numsubcats=faqe_db_num_rows($result2);
$sql = "select * from ".$tableprefix."_data where (category=".$myrow["catnr"].") and subcategory=0";
if($faqsortmethod==0)
	$sql.=" order by editdate desc";
else
	$sql.=" order by displaypos asc";
if(!$result2 = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
if($numsubcats>0)
{
?>
<ul class="faqe"><li>
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $subcatfontsize?>; color: <?php echo $subcatfontcolor?>; font-style: italic;">
<?php echo $catcount.".".$subcatcount.". ".$l_withoutsubcat?></span><br>
<?php
}
?>
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php
if (!$myrow2 = faqe_db_fetch_array($result2))
	echo "$l_noentries<br>";
else
{
	$faqcount=1;
	echo "<ul class=\"faqe\">";
	do{
		echo "<li>$catcount.";
		if($numsubcats>0)
			echo "$subcatcount.";
		echo "$faqcount ".undo_html_ampersand(stripslashes($myrow2["heading"]))."<br>";
		$faqcount+=1;
	}while($myrow2 = faqe_db_fetch_array($result2));
	echo "</ul>";
}
echo "</span>";
$sql = "select * from ".$tableprefix."_subcategory where category=".$myrow["catnr"]." order by displaypos asc";
if(!$result2 = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
if ($myrow2 = faqe_db_fetch_array($result2))
{
	do{
		$subcatcount++;
?>
<li>
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $subcatfontsize?>; color: <?php echo $subcatfontcolor?>; font-style: italic;">
<?php echo $catcount.".".$subcatcount.". ".display_encoded($myrow2["categoryname"])?></span><br>
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php
		$sql = "select * from ".$tableprefix."_data where subcategory=".$myrow2["catnr"];
		if(!$result3 = faqe_db_query($sql, $db))
		   	die("Could not connect to the database.");
		if (!$myrow3 = faqe_db_fetch_array($result3))
			echo "$l_noentries<br>";
		else
		{
			$faqcount=1;
			echo "<ul class=\"faqe\">";
			do{
				echo "<li>$catcount.";
				if($numsubcats>0)
				echo "$subcatcount.";
				echo "$faqcount ".$myrow3["heading"]."<br>";
				$faqcount+=1;
			}while($myrow3 = faqe_db_fetch_array($result3));
			echo "</ul>";
		}
	}while($myrow2 = faqe_db_fetch_array($result2));
	echo "</span>";
}
if($numsubcats>0)
	echo "</ul>";
} while($myrow = faqe_db_fetch_array($result));
echo "</ul></td></tr></table>";
$sql = "select * from ".$tableprefix."_category where (programm='$prognr') order by displaypos";
if(!$result = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
if (!$myrow = faqe_db_fetch_array($result))
   	die($l_noentries);
$catcount=0;
do{
	$subcatcount=1;
	$catcount++;
?>
<TR BGCOLOR="<?php echo $group_bgcolor?>" ALIGN="CENTER"><TD ALIGN="left" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $GroupFontColor?>;">
<?php echo $catcount.". ".display_encoded($myrow["categoryname"])?></span></td></tr>
<?php
$sql = "select * from ".$tableprefix."_subcategory where (category=".$myrow["catnr"].")";
if(!$result2 = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
$numsubcats=faqe_db_num_rows($result2);
if($numsubcats>0)
{
	switch($subcatfontstyle)
	{
		case 1:
			$addstyle="font-style: italic;";
			break;
		case 2:
			$addstyle="font-weight: bold;";
			break;
		case 3:
			$addstyle="font-weight: bold; font-style: italic;";
			break;
		default:
			$addstyle="";
			break;
	}
?>
<TR BGCOLOR="<?php echo $subcatbgcolor?>" ALIGN="CENTER"><TD ALIGN="left" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $subcatfontsize?>; color: <?php echo $subcatfontcolor?>; <?php echo $addstyle?>">
<?php echo "$catcount.$subcatcount. $l_withoutsubcat"?></span></td></tr>
<?php
}
$sql = "select * from ".$tableprefix."_data where (category=".$myrow["catnr"].") and subcategory=0";
if($faqsortmethod==0)
	$sql.=" order by editdate desc";
else
	$sql.=" order by displaypos asc";
if(!$result2 = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
?>
<TR BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER"><TD ALIGN="left" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php
if (!$myrow2 = faqe_db_fetch_array($result2))
	echo $l_noentries;
else
{
	$faqcount=1;
	do{
		if($myrow2["linkedfaq"]!=0)
		{
			$tmpsql="select * from ".$tableprefix."_data where faqnr=".$myrow2["linkedfaq"];
			if(!$tmpresult = faqe_db_query($tmpsql, $db))
			   	die("Could not connect to the database.");
			if(!$tmprow = faqe_db_fetch_array($tmpresult))
				die("corrupted database");
			$entrydata=$tmprow;
		}
		else
			$entrydata=$myrow2;
		$questiontext=stripslashes($entrydata["questiontext"]);
		$questiontext=str_replace("{lang}","$langvar=$act_lang",$questiontext);
		$questiontext=str_replace("{url_faqengine}",$url_faqengine,$questiontext);
		$questiontext=str_replace("{onlynewfaq}",0,$questiontext);
		$questiontext = str_replace("{bbc_code}",$l_bbccode,$questiontext);
		$questiontext = str_replace("{bbc_quote}",$l_bbcquote,$questiontext);
		$questiontext = undo_htmlspecialchars($questiontext);
		$answertext=stripslashes($entrydata["answertext"]);
		$answertext=str_replace("{lang}","$langvar=$act_lang",$answertext);
		$answertext=str_replace("{url_faqengine}",$url_faqengine,$answertext);
		$answertext=str_replace("{onlynewfaq}",0,$answertext);
		$answertext = str_replace("{bbc_code}",$l_bbccode,$answertext);
		$answertext = str_replace("{bbc_quote}",$l_bbcquote,$answertext);
		$answertext = undo_htmlspecialchars($answertext);
		echo "$catcount.";
		if($numsubcats>0)
			echo "$subcatcount.";
		echo "$faqcount. <b>".undo_html_ampersand(stripslashes($myrow2["heading"]))."</b><br>";
		echo "<i>$l_question:</i><br>";
		echo $questiontext."<br>";
		echo "<i>$l_answer:</i><br>";
		echo $answertext."<br><br>";
		$faqcount+=1;
	}while($myrow2 = faqe_db_fetch_array($result2));
}
echo "</span></td></tr>";
$sql = "select * from ".$tableprefix."_subcategory where category=".$myrow["catnr"]." order by displaypos asc";
if(!$result2 = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
if ($myrow2 = faqe_db_fetch_array($result2))
{
	switch($subcatfontstyle)
	{
		case 1:
			$addstyle="font-style: italic;";
			break;
		case 2:
			$addstyle="font-weight: bold;";
			break;
		case 3:
			$addstyle="font-weight: bold; font-style: italic;";
			break;
		default:
			$addstyle="";
			break;
	}
	do{
		$subcatcount++;
?>
<TR BGCOLOR="<?php echo $subcatbgcolor?>" ALIGN="CENTER"><TD ALIGN="left" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $subcatfontsize?>; color: <?php echo $subcatfontcolor?>; <?php echo $addstyle?>">
<?php echo $catcount.".".$subcatcount.". ".display_encoded($myrow2["categoryname"])?></span></td></tr>
<?php
$sql = "select * from ".$tableprefix."_data where subcategory=".$myrow2["catnr"];
if($faqsortmethod==0)
	$sql.=" order by editdate desc";
else
	$sql.=" order by displaypos asc";
if(!$result3 = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
?>
<TR BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER"><TD ALIGN="left" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php
if (!$myrow3 = faqe_db_fetch_array($result3))
	echo $l_noentries;
else
{
	$faqcount=1;
	do{
		if($myrow3["linkedfaq"]!=0)
		{
			$tmpsql="select * from ".$tableprefix."_data where faqnr=".$myrow3["linkedfaq"];
			if(!$tmpresult = faqe_db_query($tmpsql, $db))
			   	die("Could not connect to the database.");
			if(!$tmprow = faqe_db_fetch_array($tmpresult))
				die("corrupted database");
			$entrydata=$tmprow;
		}
		else
			$entrydata=$myrow3;
		$questiontext=stripslashes($entrydata["questiontext"]);
		$questiontext=str_replace("{lang}","$langvar=$act_lang",$questiontext);
		$questiontext=str_replace("{url_faqengine}",$url_faqengine,$questiontext);
		$questiontext=str_replace("{onlynewfaq}",0,$questiontext);
		$questiontext = str_replace("{bbc_code}",$l_bbccode,$questiontext);
		$questiontext = str_replace("{bbc_quote}",$l_bbcquote,$questiontext);
		$questiontext = undo_htmlspecialchars($questiontext);
		$answertext=stripslashes($entrydata["answertext"]);
		$answertext=str_replace("{lang}","$langvar=$act_lang",$answertext);
		$answertext=str_replace("{url_faqengine}",$url_faqengine,$answertext);
		$answertext=str_replace("{onlynewfaq}",0,$answertext);
		$answertext = str_replace("{bbc_code}",$l_bbccode,$answertext);
		$answertext = str_replace("{bbc_quote}",$l_bbcquote,$answertext);
		$answertext = undo_htmlspecialchars($answertext);
		echo "$catcount.$subcatcount.$faqcount.<b>".undo_html_ampersand(stripslashes($myrow3["heading"]))."</b><br>";
		echo "<i>$l_question:</i><br>";
		echo $questiontext."<br>";
		echo "<i>$l_answer:</i><br>";
		echo $answertext."<br><br>";
		$faqcount+=1;
	}while($myrow3 = faqe_db_fetch_array($result3));
}
echo "</span></td></tr>";
} while($myrow2 = faqe_db_fetch_array($result2));
}
} while($myrow = faqe_db_fetch_array($result));
?>
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
<table align="center" bgcolor="<?php echo $copyrightbgcolor?>" width="<?php echo $TableWidth?>" border="0">
<tr><td align="center">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize4?>; color: <?php echo $FontColor?>;">
<?php
$actdate=date("$dateformat H:i");
echo "$l_generated: $actdate<br>";
if($showtimezone==1)
{
	echo "<span class=\"timezone\">$l_timezone_note ".timezonename($server_timezone);
	$gmtoffset=tzgmtoffset($server_timezone);
	if($gmtoffset)
		echo " (".$gmtoffset.")";
	echo "</span><br>";
}
?>
<?php
if($contentcopy)
	echo "<br><span class=\"contentcopy\">$l_content ".display_encoded($contentcopy)."</span><br>";
else
	echo "<br><span class=\"contentcopy\">$l_content ".$faqsitename."</span><br>";
echo "$l_generated_with $copyright_url $copyright_note";
if($l_translationnote)
	echo "<br>$l_translationnote";
echo "</span></td></tr></table></div>";
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
