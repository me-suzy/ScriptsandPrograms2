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
if(!language_avail($act_lang))
	die ("Language <b>$act_lang</b> not configured");
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
if(!$result = faqe_db_query($sql, $db))
	die("Could not connect to the database.");
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
<table width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tbalalign?>">
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER"><TD ALIGN="CENTER" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize3?>; color: <?php echo $HeadingFontColor?>; font-weight: bold;">
<?php
if(bittst($listoptions,BIT_1))
	echo "<a name=\"#top\">$l_heading</a>";
else
	echo $l_heading;
?>
</span></td></tr>
<TR BGCOLOR="<?php echo $subheadingbgcolor?>" ALIGN="CENTER"><TD ALIGN="CENTER" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold;">
<?php echo "$l_progname: ".display_encoded($myrow["programmname"])?></span></td></tr>
<?php
$prognr=$myrow["prognr"];
$sql = "select * from ".$tableprefix."_category where (programm='$prognr') order by displaypos";
if(!$result = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
if (!$myrow = faqe_db_fetch_array($result))
{
	echo "<TR BGCOLOR=\"$row_bgcolor\" ALIGN=\"LEFT\">";
	echo "<td class=\"listrow\" align=\"center\">";
	echo "<span style=\"font-face: $FontFace; font-size: $FontSize1;\">";
	die($l_noentries);
}
$faqcount=1;
do{
?>
<TR BGCOLOR="<?php echo $group_bgcolor?>" ALIGN="CENTER"><TD class="entrybox" ALIGN="left" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $GroupFontColor?>;">
<?php
	echo "<a class=\"groupheadinglink\" href=\"#cat".$myrow["catnr"]."\" name=\"#cat_top".$myrow["catnr"]."\">";
	echo display_encoded($myrow["categoryname"]);
	echo "</a></span></td></tr>";
$sql = "select * from ".$tableprefix."_data where (category=".$myrow["catnr"].") and subcategory=0";
if($faqsortmethod==0)
	$sql.=" order by editdate desc";
else
	$sql.=" order by displaypos asc";
if(!$result2 = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
?>
<TR BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER"><TD class="entrybox" ALIGN="left" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php
if (!$myrow2 = faqe_db_fetch_array($result2))
	echo $l_noentries;
else
{
	do{
		echo "$faqcount. <a class=\"clistlink\" href=\"#".$myrow2["faqnr"]."\">".undo_html_ampersand(stripslashes($myrow2["heading"]))."</a><br>";
		$faqcount+=1;
	}while($myrow2 = faqe_db_fetch_array($result2));
}
echo "</span></td></tr>";
$sql = "select * from ".$tableprefix."_subcategory where category=".$myrow["catnr"]." order by displaypos asc";
if(!$result2 = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
if ($myrow2 = faqe_db_fetch_array($result2))
{
	do{
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
<TR BGCOLOR="<?php echo $subcatbgcolor?>" ALIGN="CENTER"><TD class="entrybox" ALIGN="left" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $subcatfontsize?>; color: <?php echo $subcatfontcolor?>; <?php echo $addstyle?>">
<?php
	echo "<a class=\"subcatlink\" name=\"#subcat_top".$myrow2["catnr"]."\" href=\"#subcat".$myrow2["catnr"]."\">";
	echo display_encoded($myrow2["categoryname"]);
	echo "</a></span></td></tr>";
?>
<TR BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER"><TD class="entrybox" ALIGN="left" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php
		$sql = "select * from ".$tableprefix."_data where subcategory=".$myrow2["catnr"];
		if(!$result3 = faqe_db_query($sql, $db))
		   	die("Could not connect to the database.");
		if (!$myrow3 = faqe_db_fetch_array($result3))
			echo $l_noentries;
		else
		{
			do{
				echo "$faqcount. <a class=\"clistlink\" href=\"#".$myrow3["faqnr"]."\">".$myrow3["heading"]."</a><br>";
				$faqcount+=1;
			}while($myrow3 = faqe_db_fetch_array($result3));
		}
	}while($myrow2 = faqe_db_fetch_array($result2));
}
echo "</span></td></tr>";
} while($myrow = faqe_db_fetch_array($result));
$sql = "select * from ".$tableprefix."_category where (programm='$prognr') order by displaypos";
if(!$result = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
if (!$myrow = faqe_db_fetch_array($result))
   	die($l_noentries);
$faqcount=1;
do{
?>
<TR BGCOLOR="<?php echo $group_bgcolor?>" ALIGN="CENTER"><TD class="entrybox" ALIGN="left" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $GroupFontColor?>;">
<?php
	echo "<a class=\"groupheadinglink\" name=\"#cat".$myrow["catnr"]."\"";
	if(bittst($listoptions,BIT_3))
		echo " href=\"#cat_top".$myrow["catnr"]."\"";
	echo ">";
	echo display_encoded($myrow["categoryname"]);
	echo "</a></span></td></tr>";
$sql = "select * from ".$tableprefix."_data where (category=".$myrow["catnr"].") and subcategory=0";
if($faqsortmethod==0)
	$sql.=" order by editdate desc";
else
	$sql.=" order by displaypos asc";
if(!$result2 = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
?>
<TR BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER"><TD class="entrybox" ALIGN="left" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php
if (!$myrow2 = faqe_db_fetch_array($result2))
{
	echo $l_noentries;
	if(bittst($listoptions,BIT_1))
	{
		echo "<br><a href=\"#top\">";
		if(bittst($listoptions,BIT_2))
			echo "<img src=\"$pagetoppic\" align=\"middle\" border=\"0\" alt=\"$l_top\" title=\"$l_top\">";
		else
			echo "[$l_top]";
		echo "</a>";
	}
}
else
{
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
		$questiontext=undo_htmlspecialchars($questiontext);
		$questiontext=list_recode_ref($questiontext,$prog);
		$questiontext=str_replace("{lang}","$langvar=$act_lang",$questiontext);
		$questiontext=str_replace("{url_faqengine}",$url_faqengine,$questiontext);
		$questiontext=str_replace("{onlynewfaq}",0,$questiontext);
		$questiontext=str_replace("{bbc_code}",$l_bbccode,$questiontext);
		$questiontext=str_replace("{bbc_quote}",$l_bbcquote,$questiontext);
		$answertext=stripslashes($entrydata["answertext"]);
		$answertext=undo_htmlspecialchars($answertext);
		$answertext=list_recode_ref($answertext,$prog);
		$answertext=str_replace("{lang}","$langvar=$act_lang",$answertext);
		$answertext=str_replace("{url_faqengine}",$url_faqengine,$answertext);
		$answertext=str_replace("{onlynewfaq}",0,$answertext);
		$answertext=str_replace("{bbc_code}",$l_bbccode,$answertext);
		$answertext=str_replace("{bbc_quote}",$l_bbcquote,$answertext);
		echo "$faqcount. <a name=\"#".$myrow2["faqnr"]."\"><b>".undo_html_ampersand(stripslashes($myrow2["heading"]))."</b></a><br>";
		echo "<i>$l_question:</i><br>";
		echo $questiontext."<br>";
		echo "<i>$l_answer:</i><br>";
		echo $answertext."<br>";
		$faqcount+=1;
		$attachsql="select f.filename, f.filesize, f.mimetype, f.description, fa.* from ".$tableprefix."_faq_attachs fa, ".$tableprefix."_files f where f.entrynr=fa.attachnr and fa.faqnr=".$entrydata["faqnr"];
		if(!$attachresult = faqe_db_query($attachsql, $db))
		   	die("Could not connect to the database.");
		while($attachrow=faqe_db_fetch_array($attachresult))
		{
			echo "<a href=\"download.php?attachnr=".$attachrow["attachnr"]."\">";
			$fileinfo=$attachrow["filename"]." (".format_bytes($attachrow["filesize"]).")";
			if($attachrow["description"])
				$fileinfo.="\n".$attachrow["description"];
			$mimesql="select * from ".$tableprefix."_mimetypes where mimetype='".$attachrow["mimetype"]."'";
			if(!$mimeresult = faqe_db_query($mimesql, $db))
			   	die("Could not connect to the database.");
			if($mimerow=faqe_db_fetch_array($mimeresult))
			{
				$fdsql="select * from ".$tableprefix."_filetypedescription where language='$act_lang' and mimetype=".$mimerow["entrynr"];
				if(!$fdresult = faqe_db_query($fdsql, $db))
				   	die("Could not connect to the database.");
				if($fdrow=faqe_db_fetch_array($fdresult))
					$dfileinfo=$fdrow["description"].": ".$fileinfo;
				else
					$dfileinfo=$fileinfo;
				if($mimerow["icon"])
					echo "<img class=\"attach\" src=\"$url_faqengine/gfx/".$mimerow["icon"]."\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\"> ";
				else
					echo "<img class=\"attach\" src=\"$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\"> ";
			}
			else if($attachpic)
				echo "<img class=\"attach\" src=\"$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$fileinfo\" alt=\"$fileinfo\"> ";
			else
				echo "&nbsp;";
			echo "$l_attachement</a>";
			if($displayattachinfo==1)
				echo " (".$attachrow["filename"].", ".$attachrow["filesize"]." Bytes)";
			echo "<br>";
		}
		echo "<br>";
		if(bittst($listoptions,BIT_1))
		{
			echo "<a href=\"#top\">";
			if(bittst($listoptions,BIT_2))
				echo "<img src=\"$pagetoppic\" align=\"middle\" border=\"0\" alt=\"$l_top\" title=\"$l_top\">";
			else
				echo "[$l_top]";
			echo "</a><br><br>";
		}
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
?>
<TR BGCOLOR="<?php echo $subcatbgcolor?>" ALIGN="CENTER"><TD class="entrybox" ALIGN="left" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $subcatfontsize?>; color: <?php echo $subcatfontcolor?>; <?php echo $addstyle?>">
<?php
	echo "<a class=\"subcatlink\" name=\"#subcat".$myrow2["catnr"]."\"";
	if(bittst($listoptions,BIT_3))
		echo " href=\"#subcat_top".$myrow2["catnr"]."\"";
	echo ">";
	echo display_encoded($myrow2["categoryname"]);
	echo "</span></td></tr>";
$sql = "select * from ".$tableprefix."_data where subcategory=".$myrow2["catnr"];
if($faqsortmethod==0)
	$sql.=" order by editdate desc";
else
	$sql.=" order by displaypos asc";
if(!$result3 = faqe_db_query($sql, $db))
   	die("Could not connect to the database.");
?>
<TR BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER"><TD class="entrybox" ALIGN="left" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php
if (!$myrow3 = faqe_db_fetch_array($result3))
{
	echo $l_noentries;
	if(bittst($listoptions,BIT_1))
	{
		echo "<br><a href=\"#top\">";
		if(bittst($listoptions,BIT_2))
			echo "<img src=\"$pagetoppic\" align=\"middle\" border=\"0\" alt=\"$l_top\" title=\"$l_top\">";
		else
			echo "[$l_top]";
		echo "</a>";
	}
}
else
{
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
		$questiontext = undo_htmlspecialchars($questiontext);
		$questiontext = list_recode_ref($questiontext,$prog);
		$questiontext=str_replace("{lang}","$langvar=$act_lang",$questiontext);
		$questiontext=str_replace("{url_faqengine}",$url_faqengine,$questiontext);
		$questiontext=str_replace("{onlynewfaq}",0,$questiontext);
		$questiontext = str_replace("{bbc_code}",$l_bbccode,$questiontext);
		$questiontext = str_replace("{bbc_quote}",$l_bbcquote,$questiontext);
		$answertext=stripslashes($entrydata["answertext"]);
		$answertext = undo_htmlspecialchars($answertext);
		$answertext = list_recode_ref($answertext,$prog);
		$answertext=str_replace("{lang}","$langvar=$act_lang",$answertext);
		$answertext=str_replace("{url_faqengine}",$url_faqengine,$answertext);
		$answertext=str_replace("{onlynewfaq}",0,$answertext);
		$answertext = str_replace("{bbc_code}",$l_bbccode,$answertext);
		$answertext = str_replace("{bbc_quote}",$l_bbcquote,$answertext);
		echo "$faqcount. <a name=\"#".$myrow3["faqnr"]."\"><b>".undo_html_ampersand(stripslashes($myrow3["heading"]))."</b></a><br>";
		echo "<i>$l_question:</i><br>";
		echo $questiontext."<br>";
		echo "<i>$l_answer:</i><br>";
		echo $answertext."<br>";
		$attachsql="select f.filename, f.filesize, f.mimetype, f.description, fa.* from ".$tableprefix."_faq_attachs fa, ".$tableprefix."_files f where f.entrynr=fa.attachnr and fa.faqnr=".$entrydata["faqnr"];
		if(!$attachresult = faqe_db_query($attachsql, $db))
		   	die("Could not connect to the database.");
		while($attachrow=faqe_db_fetch_array($attachresult))
		{
			echo "<a href=\"download.php?attachnr=".$attachrow["attachnr"]."\">";
			$fileinfo=$attachrow["filename"]." (".format_bytes($attachrow["filesize"]).")";
			if($attachrow["description"])
				$fileinfo.="\n".$attachrow["description"];
			$mimesql="select * from ".$tableprefix."_mimetypes where mimetype='".$attachrow["mimetype"]."'";
			if(!$mimeresult = faqe_db_query($mimesql, $db))
			   	die("Could not connect to the database.");
			if($mimerow=faqe_db_fetch_array($mimeresult))
			{
				$fdsql="select * from ".$tableprefix."_filetypedescription where language='$act_lang' and mimetype=".$mimerow["entrynr"];
				if(!$fdresult = faqe_db_query($fdsql, $db))
				   	die("Could not connect to the database.");
				if($fdrow=faqe_db_fetch_array($fdresult))
					$dfileinfo=$fdrow["description"].": ".$fileinfo;
				else
					$dfileinfo=$fileinfo;
				if($mimerow["icon"])
					echo "<img class=\"attach\" src=\"$url_faqengine/gfx/".$mimerow["icon"]."\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\"> ";
				else
					echo "<img class=\"attach\" src=\"$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$dfileinfo\" alt=\"$dfileinfo\"> ";
			}
			else if($attachpic)
				echo "<img class=\"attach\" src=\"$attachpic\" border=\"0\" align=\"absmiddle\" title=\"$fileinfo\" alt=\"$fileinfo\"> ";
			else
				echo "&nbsp;";
			echo "$l_attachement</a>";
			if($displayattachinfo==1)
				echo " (".$attachrow["filename"].", ".$attachrow["filesize"]." Bytes)";
			echo "<br>";
		}
		echo "<br>";
		if(bittst($listoptions,BIT_1))
		{
			echo "<a href=\"#top\">";
			if(bittst($listoptions,BIT_2))
				echo "<img src=\"$pagetoppic\" align=\"middle\" border=\"0\" alt=\"$l_top\" title=\"$l_top\">";
			else
				echo "[$l_top]";
			echo "</a><br><br>";
		}
		$faqcount+=1;
	}while($myrow3 = faqe_db_fetch_array($result3));
}
echo "</span></td></tr>";
} while($myrow2 = faqe_db_fetch_array($result2));
}
} while($myrow = faqe_db_fetch_array($result));
echo "</table></td></tr></table></div>";
include_once('./includes/bottom.inc');
?>