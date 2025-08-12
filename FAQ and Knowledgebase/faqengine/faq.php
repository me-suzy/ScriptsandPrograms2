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
if(!isset($navframe))
	$navframe=0;
include_once('./includes/get_settings.inc');
require_once('./includes/block_leacher.inc');
if(isset($limitprog))
	$show_proglist=0;
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
if(!isset($start))
	$start=0;
if(!language_avail($act_lang))
	faqe_die_asc("Language <b>$act_lang</b> not configured");
include_once('./language/lang_'.$act_lang.'.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
if($blockoldbrowser==1)
{
	if(is_ns3() || is_msie3())
	{
		$sql="select * from ".$tableprefix."_texts where textid='oldbrowser' and lang='$act_lang'";
		if(!$result = faqe_db_query($sql, $db))
		    die("Could not connect to the database.");
		if($myrow = faqe_db_fetch_array($result))
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
if(!isset($prog))
	$prog="";
if($ratingspublic==1)
	include_once("./includes/rating_display.inc");
if($newtime>0)
{
	$actdate=getdate(time());
	$datedays=dateToJuliandays($actdate["mday"],$actdate["mon"],$actdate["year"]);
	$newdatedays = $datedays-$newtime;
	$newdate=juliandaysToDate($newdatedays,"Y-m-d");
}
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
include("./includes/js/global.inc");
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
<table class="faqetable" width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tblalign?>">
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<TD class="mainheading" ALIGN="CENTER" VALIGN="MIDDLE" WIDTH="95%"><a name="#top">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize3?>; color: <?php echo $HeadingFontColor?>; font-weight: bold">
<?php echo $l_heading?></span></a></td>
<?php
$sql = "select * from ".$tableprefix."_misc";
if(!$result = faqe_db_query($sql, $db))
    die("Could not connect to the database.");
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
echo "<TD class=\"mainaction\" WIDTH=\"5%\" ALIGN=\"RIGHT\" VALIGN=\"MIDDLE\" nowrap>";
$displayall=1;
if( (!isset($prog)) || (!$prog) || ($allowlists==0) )
	$displayall=0;
if($displayall==1)
{
	echo "<a class=\"mainaction\" href=\"".$url_faqengine."/all.php?prog=$prog&amp;$langvar=$act_lang";
	if(isset($onlynewfaq))
		echo "&amp;onlynewfaq=$onlynewfaq";
	if(isset($layout))
		echo "&amp;layout=$layout";
	echo "\" target=\"faqall\">";
	if($listpic)
		echo "<img src=\"$listpic\" border=\"0\" align=\"middle\" title=\"$l_listlink\" alt=\"$l_listlink\"></a>";
	else
	{
			echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $HeadingFontColor;\">";
			echo "[$l_listlink]</span></a>";
	}
}
if($allowsearch==1)
{
	if(isset($prog))
	{
		echo "<a class=\"mainaction\" href=\"".$url_faqengine."/search.php?prog=$prog&amp;$langvar=$act_lang";
		if(isset($layout))
			echo "&amp;layout=$layout";
		if(isset($onlynewfaq))
			echo "&amp;onlynewfaq=$onlynewfaq";
		if($navframe==1)
			echo "&amp;navframe=1";
		if(isset($limitprog))
			echo "&amp;limitprog=$limitprog";
		echo "\">";
		if($searchpic)
			echo " <img src=\"$searchpic\" border=\"0\" align=\"middle\" title=\"$l_searchlink\" alt=\"$l_searchlink\"></a>";
		else
		{
			echo " <span style=\"font-face: $FontFace; font-size: $FontSize1; color: $HeadingFontColor;\">";
			echo "$l_search</span></a>";
		}
	}
	else
	{
		echo "<a class=\"mainaction\" href=\"".$url_faqengine."/search.php?$langvar=$act_lang";
		if(isset($layout))
			echo "&amp;layout=$layout";
		if(isset($onlynewfaq))
			echo "&amp;onlynewfaq=$onlynewfaq";
		if(navframe==1)
			echo "&amp;navframe=1";
		if(isset($limitprog))
			echo "&amp;limitprog=$limitprog";
		echo "\">";
		if($searchpic)
			echo " <img src=\"echo $searchpic\" border=\"0\" align=\"middle\" title=\"$l_searchlink\" alt=\"$l_searchlink\"></a>";
		else
		{
			echo " <span style=\"font-face: $FontFace; font-size: $FontSize1; color: $HeadingFontColor;\">";
			echo "$l_search</span></a>";
		}
	}
}
if(($allowquestions==1) && (!isset($display)))
{
	echo "<a class=\"mainaction\" href=\"".$url_faqengine."/question.php?$langvar=$act_lang&amp;type=new";
	if(isset($layout))
		echo "&amp;layout=$layout";
	if(isset($prog) && $prog)
		echo "&amp;prog=$prog";
	if(isset($onlynewfaq))
		echo "&amp;onlynewfaq=$onlynewfaq";
	if($navframe==1)
		echo "&amp;navframe=1";
	if(isset($limitprog))
		echo "&amp;limitprog=$limitprog";
	echo "\">";
	if($questionpic)
		echo " <img src=\"$questionpic\" border=\"0\" align=\"middle\" title=\"$l_askquestion\" alt=\"$l_askquestion\"></a>";
	else
	{
		echo " <span style=\"font-face: $FontFace; font-size: $FontSize1; color: $HeadingFontColor;\">";
		echo "[$l_askquestion]</span></a>";
	}

}
if(isset($prog) && $prog && !isset($display) && ($subscriptionavail==1))
{
	$tmpsql="select * from ".$tableprefix."_programm where progid='$prog' and language='$act_lang'";
	if(!$tmpresult = faqe_db_query($tmpsql, $db))
		die("Could not connect to the database.");
	if ($tmprow = faqe_db_fetch_array($tmpresult))
	{
		if($tmprow["subscriptionavail"]==1)
		{
			echo "<a class=\"mainaction\" href=\"".$url_faqengine."/subscription.php?$langvar=$act_lang&amp;type=new";
			if(isset($layout))
				echo "&amp;layout=$layout";
			if(isset($prog) && $prog)
				echo "&amp;prog=$prog";
			if(isset($onlynewfaq))
				echo "&amp;onlynewfaq=$onlynewfaq";
			if($navframe==1)
				echo "&amp;navframe=1";
			if(isset($limitprog))
				echo "&amp;limitprog=$limitprog";
			echo "\">";
			if($subscriptionpic)
				echo " <img src=\"$subscriptionpic\" border=\"0\" align=\"middle\" title=\"$l_subscribe\" alt=\"$l_subscribe\"></a>";
			else
			{
				echo " <span style=\"font-face: $FontFace; font-size: $FontSize1; color: $HeadingFontColor;\">";
				echo "[$l_subscribe]</span></a>";
			}
		}
	}
}
echo "</td></tr></table></td></tr>";
if(isset($list))
{
	// List all Categories for the given programm
	if($list=="categories")
	{
		include_once("./includes/faq_list_cats.inc");
	}
	if($list=="questions")
	{
		include_once("./includes/faq_list_qes.inc");
	}
	if($list=="category")
	{
		include_once("./includes/faq_list_cat.inc");
	}
	if($list=="subcategory")
	{
		include_once("./includes/faq_list_subcat.inc");
	}
	if($list=="all")
	{
		include_once("./includes/faq_list_all.inc");
	}
	if($list=="progs")
	{
		include_once("./includes/faq_list_progs.inc");
	}
}
else if(isset($display))
{
	include_once("./includes/faq_display.inc");
}
else
{
	include_once("./includes/faq_latest.inc");
}
echo "</div>";
include_once("./includes/bottom.inc");
?>
