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
if(!isset($navframe))
	$navframe=0;
if($usevisitcookie)
{
	$actdate = date("Y-m-d");
	$cookieexpire=time()+(365*24*60*60);
	$cookiedate="";
	if($new_global_handling)
	{
		if(isset($_COOKIE[$cookiename]))
		{
			$cookiedata=$_COOKIE[$cookiename];
			if(faqe_array_key_exists($cookiedata,"date"))
				$cookiedate=$cookiedata["date"];
		}
	}
	else
	{
		if(isset($_COOKIE[$cookiename]))
		{
			$cookiedata=$_COOKIE[$cookiename];
			if(faqe_array_key_exists($cookiedata,"date"))
				$cookiedate=$cookiedata["date"];
		}
	}
	if($cookiedate && (strpos($cookiedate,"-")>0))
	{
		$today=getdate(time());
		$datedays=dateToJuliandays($today["mday"],$today["mon"],$today["year"]);
		list($year, $month, $day) = explode("-", $cookiedate);
		$cookiedatedays=dateToJuliandays($day,$month,$year);
		$datedifference = $datedays-$cookiedatedays;
		if($datedifference<1)
		{
			if(!isset($onlynewfaq))
				$onlynewfaq=1;
		}
		else
		{
			$onlynewfaq=$datedifference;
			setcookie($cookiename."[date]",$actdate,$cookieexpire,$cookiepath,$cookiedomain,$cookiesecure);
		}
	}
	else
		setcookie($cookiename."[date]",$actdate,$cookieexpire,$cookiepath,$cookiedomain,$cookiesecure);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
if(!language_avail($act_lang))
	faqe_die_asc("Language <b>$act_lang</b> not configured");
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
	faqe_die_asc($l_config_writeable);
}
if($subscriptionavail!=1)
	die($l_function_disabled);
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
<title><?php echo $l_heading?></title>
<?php
}
?>
<meta name="generator" content="FAQEngine v<?php echo $faqeversion?>, <?php echo $copyright_asc?>">
<meta name="fid" content="022a9b32a909bf2b875da24f0c8f1225">
<script type="text/javascript" language="JavaScript" src="./js/emailcheck.js"></script>
<script type="text/javascript" language="JavaScript">
<!--
function checkform()
{
	if(document.subscriptionform.email.value.length<1)
	{
		alert("<?php echo undo_htmlentities($l_noemail)?>");
		return false;
	}
	if(!emailCheck(document.subscriptionform.email.value))
	{
		alert("<?php echo undo_htmlentities($l_invalidemail)?>");
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
<TD class="mainheading" ALIGN="CENTER" VALIGN="MIDDLE" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize3?>; color: <?php echo $HeadingFontColor?>; font-weight: bold">
<?php echo $l_heading?></span></a></td></tr>
<?php
$sql = "select * from ".$tableprefix."_misc";
if(!$result = faqe_db_query($sql, $db))
	die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
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
		include_once('./includes/bottom.inc');
		exit;
	}
}
if(!isset($prog) && isset($mode))
	die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>calling error");
if(!isset($backurl) || !$backurl)
{
	if(isset($prog))
	{
		$backurl=$url_faqengine."/faq.php?list=all&prog=$prog&".$langvar."=$act_lang";
		if(isset($onlynewfaq))
			$backurl.="&onlynewfaq=$onlynewfaq";
		if($navframe==1)
			$backurl.="&navframe=1";
		if(isset($limitprog))
			$backurl.="&limitprog=$limitprog";
	}
	else
		$backurl="";
}
echo "<tr BGCOLOR=\"$subheadingbgcolor\">";
echo "<TD class=\"subheading\" ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"";
if($backurl)
	echo " width=\"95%\"";
else
	echo " colspan=\"2\"";
echo ">";
echo "<span style=\"font-face: $FontFace; font-size: $FontSize2; color: $SubheadingFontColor; font-weight: bold\">";
echo $l_subscribe."</span></td>";
if($backurl)
{
	echo "<td align=\"center\" valign=\"MIDDLE\" width=\"5%\">";
	echo "<a class=\"backurl\" href=\"$backurl\">";
	if($backpic)
		echo "<img src=\"$backpic\" border=\"0\" title=\"$l_faqlink\" alt=\"$l_faqlink\"></a>";
	else
	{
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $HeadingFontColor;\">";
		echo "[$l_back]</span></a> ";
	}
	echo "</td>";
}
echo "</tr></table></td></tr>";
echo "<tr><TD BGCOLOR=\"$table_bgcolor\">";
echo "<TABLE BORDER=\"0\" CELLPADDING=\"1\" CELLSPACING=\"1\" WIDTH=\"100%\">";
if(isset($prog))
{
	$sql="select * from ".$tableprefix."_programm where progid='$prog' and language='$act_lang'";
	if(!$result = faqe_db_query($sql, $db))
	    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
	if(!$myrow = faqe_db_fetch_array($result))
	    die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>no such prog.");
	$progname=display_encoded($myrow["programmname"]);
	$prognr=$myrow["prognr"];
	echo "<tr bgcolor=\"$irow_bgcolor\"><td align=\"center\" colspan=\"2\">";
	echo "<span style=\"font-face: $FontFace; font-size: $irow_fontsize; color: $irow_fontcolor; font-weight: bold;\">";
	echo $l_program.": ".$progname;
	echo "</span></td></tr>\n";
}
if(isset($mode))
{
	if($mode=="confirm")
	{
		include_once("./includes/sub_confirm.inc");
	}
	if($mode=="remove")
	{
		include_once("./includes/sub_remove.inc");
	}
	if($mode=="unsubscribe")
	{
		include_once("./includes/sub_unsub.inc");
	}
	if($mode=="delete")
	{
		include_once("./includes/sub_del.inc");
	}
	if($mode=="subscribe")
	{
		include_once("./includes/sub_subscribe.inc");
	}
}
else
{
	include_once("./includes/sub_main.inc");
}
?>
