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
if(!language_avail($act_lang))
	die ("Language <b>$act_lang</b> not configured");
include_once('./includes/get_settings.inc');
require_once('./includes/block_leacher.inc');
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
if($allowsearch!=1)
	die($l_function_disabled);
if(!isset($stype))
	$stype=0;
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
if($newtime>0)
{
	$actdate=getdate(time());
	$datedays=dateToJuliandays($actdate["mday"],$actdate["mon"],$actdate["year"]);
	$newdatedays = $datedays-$newtime;
	$newdate=juliandaysToDate($newdatedays,"Y-m-d");
}
if(isset($specmode) && ($specmode="search"))
	$dosearch=1;
if(!isset($dosearch) || isset($clear))
{
	$search_head="";
	$search_question="";
	$search_answer="";
	$answer_option="all";
	$criteria_option="any";
	if(!isset($newdays))
		$newdays=0;
	$search_comments="";
	$enablesummary=1;
	$search_questions="";
	$search_arguments="";
	$max_results=0;
	$doquestionsearch=0;
	$docommentsearch=0;
	$searchmethod=$defsearchmethod;
}
else
{
	if(isset($local_searchcomments))
		$docommentsearch=1;
	else
		$docommentsearch=0;
	if(isset($local_searchquestions))
		$doquestionsearch=1;
	else
		$doquestionsearch=0;
}
if(isset($enablesummary))
	$local_showsummary=1;
else
	$local_showsummary=0;
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
include("./includes/js/global.inc");
include("./includes/js/search.inc");
?>
</head>
<body onLoad="submitByEnter()" bgcolor="<?php echo $page_bgcolor?>" link="<?php echo $LinkColor?>" vlink="<?php echo $VLinkColor?>" alink="<?php echo $ALinkColor?>" text="<?php echo $FontColor?>" <?php echo $addbodytags?>>
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
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE" width="95%">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize3?>; color: <?php echo $HeadingFontColor?>; font-weight: bold">
<?php echo $l_searchheading?></span>
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
		include_once('./includes/bottom.inc');
		exit;
	}
}
?>
<td align="right" valign="MIDDLE" width="2%" nowrap>
<?php
if($navframe==1)
	$linkurl=$url_faqengine."/faqframe.php";
else
	$linkurl=$url_faqengine."/faq.php";
if(!isset($prog) || !$prog)
	echo "<a class=\"mainaction\" href=\"$linkurl?list=progs&$langvar=$act_lang";
else
	echo "<a class=\"mainaction\" href=\"$linkurl?list=all&prog=$prog&$langvar=$act_lang";
if(isset($onlynewfaq))
	echo "&amp;onlynewfaq=$onlynewfaq";
if(isset($limitprog))
	echo "&amp;limitprog=$limitprog";
if(isset($layout))
	echo "&amp;layout=$layout";
echo "\"";
if($navframe==1)
	echo " target=\"_parent\"";
echo ">";
if($backpic)
	echo "<img src=\"$backpic\" border=\"0\" title=\"$l_faqlink\" alt=\"$l_faqlink\"></a> ";
else
{
	echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $HeadingFontColor;\">";
	echo "[$l_back]</span></a> ";
}
?>
</a>
<a class="mainaction" href="javascript:openSearchHelp('<?php echo $url_faqengine?>/help/<?php echo $act_lang?>/search.php?<?php echo "$langvar=$act_lang"?>')">
<?php
if($helppic)
	echo "<img src=\"$helppic\" border=\"0\" title=\"$l_help\" alt=\"$l_help\"></a>";
else
{
	echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $HeadingFontColor;\">";
	echo "[$l_help]</span></a> ";
}
?>
</td></tr>
<tr bgcolor="<?php echo $subheadingbgcolor?>"><td align="center" colspan="2">
<span style="color: <?php echo $SubheadingFontColor?>; font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; font-weight: bold;">
<?php
if($stype==1)
	echo $l_advanced_search;
else
	echo $l_simple_search;
?>
</span></td></tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<form name="searchform" onsubmit="return checkform();" action="<?php echo $act_script_url?>" method="post">
<?php
	if(is_konqueror())
		echo "<tr><td></td></tr>";
	if(isset($layout))
		echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
	if(isset($limitprog))
		echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
	if($navframe==1)
		echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
	if(isset($onlynewfaq))
		echo "<input type=\"hidden\" name=\"onlynewfaq\" value=\"$onlynewfaq\">";
	if(isset($prog) && $prog)
		echo "<input type=\"hidden\" name=\"prog\" value=\"$prog\">";
?>
<input type="hidden" name="<?php echo $langvar?>" value="<?php echo $act_lang?>">
<input type="hidden" name="stype" value="<?php echo $stype?>">
<?php
$sql = "select * from ".$tableprefix."_texts where textid='searchpre' and lang='$act_lang'";
if(!$result = faqe_db_query($sql, $db)) {
	die("<tr bgcolor=\"$heading_bgcolor\" align=\"center\"><td>Could not connect to the database.");
}
if($myrow=faqe_db_fetch_array($result))
{
	$displaytext=stripslashes($myrow["text"]);
	$displaytext = undo_htmlspecialchars($displaytext);
	echo "<tr bgcolor=\"$group_bgcolor\"><td align=\"center\" colspan=\"2\">";
	echo "<span style=\"font-face: $FontFace; font-size: $FontSize5; color: $FontColor;\">";
	echo $displaytext;
	echo "</span></td></tr>\n";
}
if($stype==1)
{
?>
<tr><td align="left" colspan="2" bgcolor="<?php echo $subheadingbgcolor?>">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold;">
<?php echo $l_searchfaq?>:</span></td></tr>
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<td bgcolor="<?php echo $group_bgcolor?>" ALIGN="LEFT" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $GroupFontColor?>; font-weight: bold;">
<?php echo $l_searchheadings?>:</span></td></tr>
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<td align="left" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $GroupFontColor?>;">
<input class="faqeinput" type="text" name="search_head" value="<?php echo display_encoded($search_head)?>" size="<?php echo $search_inputfieldwidth?>" maxlength="255">
<a href="javascript:srchTool('search_head')"><img src="<?php echo $srchtoolpic?>" border="0" align="absmiddle" title="<?php echo $l_srchtool?>" alt="<?php echo $l_srchtool?>"></a></span>
</td></tr>
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<td bgcolor="<?php echo $group_bgcolor?>" ALIGN="LEFT" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $GroupFontColor?>; font-weight: bold;">
<?php echo $l_searchquestions?>:</span></td></tr>
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<td align="left" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $GroupFontColor?>;">
<input class="faqeinput" type="text" name="search_question" value="<?php echo display_encoded($search_question)?>" size="<?php echo $search_inputfieldwidth?>" maxlength="255"></span>
<a href="javascript:srchTool('search_question')"><img src="<?php echo $srchtoolpic?>" border="0" align="absmiddle" title="<?php echo $l_srchtool?>" alt="<?php echo $l_srchtool?>"></a></span>
</td></tr>
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<td bgcolor="<?php echo $group_bgcolor?>" ALIGN="LEFT" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $GroupFontColor?>; font-weight: bold;">
<?php echo $l_searchanswers?>:</span></td></tr>
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<td align="left" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $GroupFontColor?>;">
<input class="faqeinput" type="text" name="search_answer" value="<?php echo display_encoded($search_answer)?>" size="<?php echo $search_inputfieldwidth?>" maxlength="255"></span>
<a href="javascript:srchTool('search_answer')"><img src="<?php echo $srchtoolpic?>" border="0" align="absmiddle" title="<?php echo $l_srchtool?>" alt="<?php echo $l_srchtool?>"></a></span>
</td></tr>
<tr BGCOLOR="<?php echo $group_bgcolor?>">
<td align="center" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $GroupFontColor?>;">
<?php echo $l_chaincriterias?>:
<select class="faqeselect2" name="criteria_option">
<option value="all" <?php if($criteria_option=="all") echo "selected"?>><?php echo $l_allcriterias?></option>
<option value="any" <?php if($criteria_option=="any") echo "selected"?>><?php echo $l_anycriteria?></option>
</select></span></td></tr>
<tr><td align="center" bgcolor="<?php echo $row_bgcolor?>" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $GroupFontColor?>;">
<?php echo $l_searchnew?> <select class="faqeselect2" name="newdays">
<option value="0"><?php echo $l_any?></option>
<?php
for($i=1;$i<15;$i++)
{
	echo "<option value=\"$i\"";
	if($newdays==$i)
		echo "selected";
	echo ">$i</option>";
}
?>
</select>&nbsp;<?php echo $l_days?>
</span></td></tr>
<?php
if(($allowusercomments==1) && ($searchcomments==1))
{
?>
<tr><td align="left" colspan="2" bgcolor="<?php echo $subheadingbgcolor?>">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold;">
<?php echo $l_searchcomments?>:</span></td></tr>
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<td width="55%" align="left" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $GroupFontColor?>;">
<input class="faqeinput" type="text" name="search_comments" value="<?php echo display_encoded($search_comments)?>" size="<?php echo $search_inputfieldwidth?>" maxlength="255"></span>
<a href="javascript:srchTool('search_comments')"><img src="<?php echo $srchtoolpic?>" border="0" align="absmiddle" title="<?php echo $l_srchtool?>" alt="<?php echo $l_srchtool?>"></a></span>
</td></tr>
<?php
}
if(($allowquestions==1) && ($searchquestions==1))
{
?>
<tr><td align="left" colspan="2" bgcolor="<?php echo $subheadingbgcolor?>">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold;">
<?php echo $l_search_userquestions?>:</span></td></tr>
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<td width="55%" align="left" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $GroupFontColor?>;">
<input class="faqeinput" type="text" name="search_questions" value="<?php echo display_encoded($search_questions)?>" size="<?php echo $search_inputfieldwidth?>" maxlength="255"></span>
<a href="javascript:srchTool('search_questions')"><img src="<?php echo $srchtoolpic?>" border="0" align="absmiddle" title="<?php echo $l_srchtool?>" alt="<?php echo $l_srchtool?>"></a></span>
</td></tr>
<?php
}
}
else
{
?>
<input type="hidden" name="criteria_option" value="any">
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<td align="left" colspan="2">
<input class="faqeinput" type="text" name="search_arguments" value="<?php echo display_encoded($search_arguments)?>" size="<?php echo $search_inputfieldwidth?>" maxlength="255">
<a href="javascript:srchTool('search_arguments')"><img src="<?php echo $srchtoolpic?>" border="0" align="absmiddle" title="<?php echo $l_srchtool?>" alt="<?php echo $l_srchtool?>"></a></span>
</td></tr>
<?php
if($enablekeywordsearch==1)
{
?>
<tr bgcolor="<?php echo $group_bgcolor?>"><td align="center" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $GroupFontColor?>;">
<input type="radio" name="searchmethod" value="0" <?php if($searchmethod==0) echo "checked"?>>
<?php echo $l_keywordsearch?>&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio" name="searchmethod" value="1" <?php if($searchmethod==1) echo "checked"?>>
<?php echo $l_fulltextsearch?></span></td></tr>
<?php
}
else
	echo "<input type=\"hidden\" name=\"searchmethod\" value=\"1\">";
?>
<tr BGCOLOR="<?php echo $group_bgcolor?>" ALIGN="CENTER">
<?php
if(($allowquestions==1) && ($searchquestions==1))
{
	echo "<tr bgcolor=\"$group_bgcolor\"><td align=\"center\" colspan=\"2\">";
	echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
	echo "<input type=\"checkbox\" name=\"local_searchquestions\"";
	if($doquestionsearch==1)
		echo " checked";
	echo"> $l_questionsearch</span></td></tr>";
}
if(($allowusercomments==1) && ($searchcomments==1))
{
	echo "<tr bgcolor=\"$group_bgcolor\"><td align=\"center\" colspan=\"2\">";
	echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
	echo "<input type=\"checkbox\" name=\"local_searchcomments\"";
	if($docommentsearch==1)
		echo " checked";
	echo"> $l_commentsearch</span></td></tr>";
}
}
if(($showsummary==1))
{
?>
<tr><td align="center" colspan="2" bgcolor="<?php echo $group_bgcolor?>">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<input type="checkbox" name="enablesummary" value="1" <?php if($local_showsummary==1) echo "checked"?>>
<?php echo $l_showsummary?></span></td></tr>
<?php
}
?>
<tr bgcolor="<?php echo $group_bgcolor?>"><td align="center" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize1?>; color: <?php echo $FontColor?>;">
<?php echo "$l_max_results1 "?>
<select class="faqeselect2" name="max_results">
<option value="0"
<?php
	if($max_results==0)
		echo "selected";
?>
><?php echo $l_all?></option>
<?php
for($i=5;$i<51;$i+=5)
{
	echo "<option value=\"$i\"";
	if($i==$max_results)
		echo " selected";
	echo ">$i</option>";
}
?>
</select>
<?php echo " $l_max_results2"?>
</span></td></tr>
<tr><td align="center" colspan="2" bgcolor="<?php echo $actionbgcolor?>">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $actionlinefontsize?>; color: <?php echo $FontColor?>;">
<input type="hidden" name="specmode" value="">
<input class="faqebutton" type="submit" name="dosearch" value="<?php echo $l_dosearch?>">
<input class="faqebutton" type="reset" value="<?php echo $l_reset?>">
<input class="faqebutton" type="submit" name="clear" onclick="overridecheck=true;" value="<?php echo $l_clear?>">
</span></td></tr>
<?php
if($stype==0)
{
?>
<tr BGCOLOR="<?php echo $actionbgcolor?>" ALIGN="CENTER">
<td align="center" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $actionlinefontsize?>; color: <?php echo $FontColor?>;">
<?php
if(isset($prog) && $prog)
	echo "<a class=\"actionline\" href=\"$act_script_url?$langvar=$act_lang&amp;prog=$prog&amp;stype=1";
else
	echo "<a class=\"actionline\" href=\"$act_script_url?$langvar=$act_lang&amp;stype=1";
if(isset($onlynewfaq))
	echo "&amp;onlynewfaq=$onlynewfaq";
if($navframe==1)
	echo "&amp;navframe=1";
if(isset($limitprog))
	echo "&amp;limitprog=$limitprog";
if(isset($layout))
	echo "&amp;layout=$layout";
echo "\">$l_advanced_search</a>";
?>
</span></td></tr>
<?php
}
else
{
?>
<tr BGCOLOR="<?php echo $actionbgcolor?>" ALIGN="CENTER">
<td align="center" colspan="2">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $actionlinefontsize?>; color: <?php echo $FontColor?>;">
<?php
	if(isset($prog) && $prog)
		echo "<a class=\"actionline\" href=\"$act_script_url?$langvar=$act_lang&amp;prog=$prog&amp;stype=0";
	else
		echo "<a class=\"actionline\" href=\"$act_script_url?$langvar=$act_lang&amp;stype=0";
	if(isset($onlynewfaq))
		echo "&amp;onlynewfaq=$onlynewfaq";
	if($navframe==1)
		echo "&amp;navframe=1";
	if(isset($limitprog))
		echo "&amp;limitprog=$limitprog";
	if(isset($layout))
		echo "&amp;layout=$layout";
	echo "\">$l_simple_search</a>";
?>
</span></td></tr>
<?php
}
echo "</form></table></td></tr></table>";
if(($enablelanguageselector==1) && !isset($dosearch))
{
	echo "<table style=\"clear:both\" width=\"$TableWidth\" align=\"$tblalign\" border=\"0\">\n";
	echo "<form method=\"post\" action=\"$act_script_url\">";
	echo "<tr bgcolor=\"$group_bgcolor\"><td class=\"langselect\" align=\"center\">\n";
	if(isset($onlynewfaq))
		echo "<input type=\"hidden\" name=\"onlynewfaq\" value=\"$onlynewfaq\">";
	if($navframe==1)
		echo "<input type=\"hidden\" name=\"navframe\" value=\"1\">";
	if(isset($limitprog))
		echo "<input type=\"hidden\" name=\"limitprog\" value=\"$limitprog\">";
	if(isset($layout))
		echo "<input type=\"hidden\" name=\"layout\" value=\"$layout\">";
	echo "<input type=\"hidden\" name=\"stype\" value=\"$stype\">";
	echo "<font style=\"font-face: $FontFace; font-size: $langselectfontsize; color: $FontColor;\">";
	echo "$l_changelang: ".language_select($act_lang,$langvar,"./language","langselect");
	echo "&nbsp;&nbsp;&nbsp;<input class=\"langselect\" type=\"submit\" value=\"$l_ok\">";
	echo "</span></td></tr></form></table>\n";
}
if(isset($dosearch))
{
	if(!isset($searchmethod))
		$searchmethod=1;
	$logtxt ="[".date($logdateformat)."] {FAQ";
	if($searchmethod==0)
		$logtxt.=" - Keywords} ";
	else
		$logtxt.=" - Fulltext} ";
	echo "<br>";
	if($stype==0)
	{
		$newdays=0;
		$search_head=trim($search_arguments);
		$search_question=trim($search_arguments);
		$search_answer=trim($search_arguments);
		if(($allowusercomments==1) && ($searchcomments==1) && ($docommentsearch==1))
			$search_comments=trim($search_arguments);
		else
			$search_comments="";
		if(($allowquestions==1) && ($searchquestions==1) && ($doquestionsearch==1))
			$search_questions=trim($search_arguments);
		else
			$search_questions="";
		if($searchmethod==0)
			$logtxt.=$search_arguments." ";
	}
	if(($stype!=0) || ($searchmethod!=0))
	{
		$num_results_faq=0;
		$numcriterias=0;
		$sql ="SELECT dat.* from ".$tableprefix."_data dat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog ";
		if(($progrestrict==0) || !isset($prog) || !$prog)
			$sql .="where cat.programm=prog.prognr and dat.category=cat.catnr and prog.language='$act_lang' and (";
		else
			$sql .="where cat.programm=prog.prognr and dat.category=cat.catnr and prog.progid='$prog' and prog.language='$act_lang' and (";
		$search_head=trim($search_head);
		if($search_head)
		{
			$logtxt.=$search_head." ";
			$search_head=do_htmlentities($search_head);
			$musts=array();
			$cans=array();
			$nots=array();
			$numcriterias+=1;
			$searchcriterias=0;
			$searchterms = explode(" ",$search_head);
			foreach($searchterms as $searchstring)
			{
				$qualifier=substr($searchstring,0,1);
				if($qualifier=='-')
				{
					array_push($nots,substr($searchstring,1,strlen($searchstring)-1));
				}elseif ($qualifier=='+')
				{
					array_push($musts,substr($searchstring,1,strlen($searchstring)-1));
				}
				else
				{
					array_push($cans,$searchstring);
				}
			}
			$first=1;
			if(count($musts)>0)
			{
				$sql .="((";
				$searchcriterias++;
				for($i=0;$i<count($musts);$i++)
				{
					if($first==1)
						$first=0;
					else
						$sql .=" and ";
					$sql.="dat.heading like '%".$musts[$i]."%'";
				}
				$sql .=")";

			}
			$first=1;
			if(count($nots)>0)
			{
				if($searchcriterias>0)
					$sql .=" and ";
				else
					$sql.="(";
				$sql .="(";
				$searchcriterias++;
				for($i=0;$i<count($nots);$i++)
				{
					if($first==1)
					$first=0;
					else
						$sql .=" and ";
					$sql.="dat.heading not like '%".$nots[$i]."%'";
				}
				$sql .=")";
			}
			$first=1;
			if((count($cans)>0) && (count($musts)<1))
			{
				if($searchcriterias>0)
					$sql .=" and ";
				else
					$sql .="(";
				$sql .="(";
				$searchcriterias++;
				for($i=0;$i<count($cans);$i++)
				{
					if($first==1)
						$first=0;
					else
						$sql .=" or ";
					$sql.="dat.heading like '%".$cans[$i]."%'";
				}
				$sql .=")";
			}
			if($searchcriterias>0)
				$sql.=")";
		}
		if($newdays>0)
		{
			$actdate2=getdate(time());
			$datedays2=dateToJuliandays($actdate2["mday"],$actdate2["mon"],$actdate2["year"]);
			$newdatedays2 = $datedays2-$newdays;
			$newdate2=juliandaysToDate($newdatedays2,"Y-m-d");
			if($numcriterias>0)
				$sql .=" AND ";
			$numcriterias = $numcriterias + 1;
			$sql .="(dat.editdate >= '$newdate2')";
		}
		$search_question=trim($search_question);
		if($search_question)
		{
			$logtxt.=$search_question." ";
			$search_question=do_htmlentities($search_question);
			$musts=array();
			$cans=array();
			$nots=array();
			if($numcriterias>0)
			{
				if($criteria_option=="all")
					$sql .=" AND ";
				else
					$sql .=" OR ";
			}
			$numcriterias+=1;
			$searchcriterias=0;
			$searchterms = explode(" ",$search_question);
			foreach($searchterms as $searchstring)
			{
				$qualifier=substr($searchstring,0,1);
				if($qualifier=='-')
				{
					array_push($nots,substr($searchstring,1,strlen($searchstring)-1));
				}elseif ($qualifier=='+')
				{
					array_push($musts,substr($searchstring,1,strlen($searchstring)-1));
				}
				else
				{
					array_push($cans,$searchstring);
				}
			}
			$first=1;
			if(count($musts)>0)
			{
				$sql .="((";
				$searchcriterias++;
				for($i=0;$i<count($musts);$i++)
				{
					if($first==1)
						$first=0;
					else
						$sql .=" and ";
					$sql.="dat.questiontext like '%".$musts[$i]."%'";
				}
				$sql .=")";
			}
			$first=1;
			if(count($nots)>0)
			{
				if($searchcriterias>0)
					$sql .=" and ";
				else
					$sql .="(";
				$sql .="(";
				$searchcriterias++;
				for($i=0;$i<count($nots);$i++)
				{
					if($first==1)
						$first=0;
					else
						$sql .=" and ";
					$sql.="dat.questiontext not like '%".$nots[$i]."%'";
				}
				$sql .=")";
			}
			$first=1;
			if((count($cans)>0) && (count($musts)<1))
			{
				if($searchcriterias>0)
					$sql .=" and ";
				else
					$sql .="(";
				$sql .="(";
				$searchcriterias++;
				for($i=0;$i<count($cans);$i++)
				{
					if($first==1)
						$first=0;
					else
						$sql .=" or ";
					$sql.="dat.questiontext like '%".$cans[$i]."%'";
				}
				$sql .=")";
			}
			if($searchcriterias>0)
				$sql.=")";
		}
		$search_answer=trim($search_answer);
		if($search_answer)
		{
			$logtxt.=$search_answer." ";
			$search_answer=do_htmlentities($search_answer);
			$musts=array();
			$cans=array();
			$nots=array();
			if($numcriterias>0)
			{
				if($criteria_option=="all")
					$sql .=" AND ";
				else
					$sql .=" OR ";
			}
			$numcriterias+=1;
			$searchcriterias=0;
			$searchterms = explode(" ",$search_answer);
			foreach($searchterms as $searchstring)
			{
				$qualifier=substr($searchstring,0,1);
				if($qualifier=='-')
				{
					array_push($nots,substr($searchstring,1,strlen($searchstring)-1));
				}elseif ($qualifier=='+')
				{
					array_push($musts,substr($searchstring,1,strlen($searchstring)-1));
				}
				else
				{
					array_push($cans,$searchstring);
				}
			}
			$first=1;
			if(count($musts)>0)
			{
				$sql .="((";
				$searchcriterias++;
				for($i=0;$i<count($musts);$i++)
				{
					if($first==1)
						$first=0;
					else
						$sql .=" and ";
					$sql.="dat.answertext like '%".$musts[$i]."%'";
				}
				$sql .=")";
			}
			$first=1;
			if(count($nots)>0)
			{
				if($searchcriterias>0)
					$sql .=" and ";
				else
					$sql.="(";
				$sql .="(";
				$searchcriterias++;
				for($i=0;$i<count($nots);$i++)
				{
					if($first==1)
						$first=0;
					else
						$sql .=" and ";
					$sql.="dat.answertext not like '%".$nots[$i]."%'";
				}
				$sql .=")";
			}
			$first=1;
			if((count($cans)>0) && (count($musts)<1))
			{
				if($searchcriterias>0)
					$sql .=" and ";
				else
					$sql.="(";
				$sql .="(";
				$searchcriterias++;
				for($i=0;$i<count($cans);$i++)
				{
					if($first==1)
						$first=0;
					else
						$sql .=" or ";
					$sql.="dat.answertext like '%".$cans[$i]."%'";
				}
				$sql .=")";
			}
			if($searchcriterias>0)
				$sql.=")";
		}
	}
	if(($stype==0) && ($searchmethod==0))
	{
		$num_results_faq=0;
		$numcriterias=0;
		$sql = "select faq.* from ".$tableprefix."_data faq, ".$tableprefix."_category cat, ".$tableprefix."_programm prog ";
		if(($progrestrict==0) || !isset($prog) || !$prog)
			$sql .="where cat.programm=prog.prognr and faq.category=cat.catnr and prog.language='$act_lang' and ";
		else
			$sql .="where cat.programm=prog.prognr and faq.category=cat.catnr and prog.progid='$prog' and prog.language='$act_lang' and ";
		$faqnrs=array();
		$excludefaqs=array();
		$musts=array();
		$cans=array();
		$nots=array();
		if($search_arguments)
		{
			$searchterms = explode(" ",$search_arguments);
			foreach($searchterms as $searchstring)
			{
				$qualifier=substr($searchstring,0,1);
				if($qualifier=='-')
				{
					array_push($nots,substr($searchstring,1,strlen($searchstring)-1));
				}elseif ($qualifier=='+')
				{
					array_push($musts,substr($searchstring,1,strlen($searchstring)-1));
				}
				else
				{
					array_push($cans,$searchstring);
				}
			}
			if(count($nots)>0)
			{
				$numcriterias++;
				$tempsql="select faq.faqnr from ".$tableprefix."_faq_keywords faq, ".$tableprefix."_keywords kw where faq.keywordnr=kw.keywordnr";
				for($i=0;$i<count($nots);$i++)
				{
					$tempsql .=" and ";
					if($keywordsearchmode==0)
						$tempsql.="kw.keyword ='".$nots[$i]."'";
					else
						$tempsql.="kw.keyword like '%".$nots[$i]."%'";
				}
				if(!$result = faqe_db_query($tempsql, $db)) {
					die("Could not connect to the database (3).".faqe_db_error());
				}
				while($temprow=faqe_db_fetch_array($result))
				{
					array_push($excludefaqs,$temprow["faqnr"]);
				}
			}
			if(count($musts)>0)
			{
				$numcriterias++;
				$tempsql="select faq.faqnr from ".$tableprefix."_faq_keywords faq, ".$tableprefix."_keywords kw where faq.keywordnr=kw.keywordnr";
				for($i=0;$i<count($musts);$i++)
				{
					$tempsql .= " and ";
					if($keywordsearchmode==0)
						$tempsql .="kw.keyword='".$musts[$i]."'";
					else
						$tempsql.="kw.keyword like '%".$musts[$i]."%'";
				}
				if(!$result = faqe_db_query($tempsql, $db)) {
					die("Could not connect to the database (3).".faqe_db_error());
				}
				while($temprow=faqe_db_fetch_array($result))
				{
					if(!in_array($temprow["faqnr"],$excludefaqs))
						array_push($faqnrs,$temprow["faqnr"]);
				}
			}
			if((count($cans)>0) && (count($musts)<1))
			{
				$numcriterias++;
				$tempsql="select faq.faqnr from ".$tableprefix."_faq_keywords faq, ".$tableprefix."_keywords kw where faq.keywordnr=kw.keywordnr and (";
				$first=1;
				for($i=0;$i<count($cans);$i++)
				{
					if($first==1)
						$first=0;
					else
						$tempsql .=" or ";
					if($keywordsearchmode==0)
						$tempsql.="kw.keyword='".$cans[$i]."'";
					else
						$tempsql.="kw.keyword like '%".$cans[$i]."%'";
				}
				$tempsql.=")";
				if(!$result = faqe_db_query($tempsql, $db)) {
					die("Could not connect to the database (3).".faqe_db_error());
				}
				while($temprow=faqe_db_fetch_array($result))
				{
					if(!in_array($temprow["faqnr"],$excludefaqs))
						array_push($faqnrs,$temprow["faqnr"]);
				}
			}
		}
	}
	$questioncount=0;
	$search_questions=trim($search_questions);
	if(($allowquestions==1) && ($search_questions) && ($searchquestions==1))
	{
		$musts=array();
		$cans=array();
		$nots=array();
		$searchcriterias=0;
		$sql_questions="select * from ".$tableprefix."_questions WHERE ";
		$sql_questions .=" (";
		$searchterms = explode(" ",$search_questions);
		foreach($searchterms as $searchstring)
		{
			$qualifier=substr($searchstring,0,1);
			if($qualifier=='-')
			{
				array_push($nots,substr($searchstring,1,strlen($searchstring)-1));
			}elseif ($qualifier=='+')
			{
				array_push($musts,substr($searchstring,1,strlen($searchstring)-1));
			}
			else
			{
				array_push($cans,$searchstring);
			}
		}
		$first=1;
		if(count($musts)>0)
		{
			$questioncount++;
			$sql_questions .="(";
			$searchcriterias++;
			for($i=0;$i<count($musts);$i++)
			{
				if($first==1)
					$first=0;
				else
					$sql_questions .=" and ";
				$sql_questions .="(questions like '%".$musts[$i]."%' or answer like '%".$musts[$i]."%')";
			}
			$sql_questions .=")";
		}
		$first=1;
		if(count($nots)>0)
		{
			$questioncount++;
			if($searchcriterias>0)
				$sql_questions .=" and ";
			$sql_questions .="(";
			$searchcriterias++;
			for($i=0;$i<count($nots);$i++)
			{
				if($first==1)
					$first=0;
				else
					$sql_questions .=" and ";
				$sql_questions .="(question not like '%".$nots[$i]."%' and answer not like '%".$nots[$i]."%')";
			}
			$sql_questions .=")";
		}
		$first=1;
		if((count($cans)>0) && (count($musts)<1))
		{
			$questioncount++;
			if($searchcriterias>0)
				$sql_questions .=" and ";
			$sql_questions .="(";
			$searchcriterias++;
			for($i=0;$i<count($cans);$i++)
			{
				if($first==1)
					$first=0;
				else
					$sql_questions .=" or ";
				$sql_questions .="(question like '%".$cans[$i]."%' or answer like '%".$cans[$i]."%')";
			}
			$sql_questions .=")";
		}
		$sql_questions .=") and (publish=1) group by questionnr order by enterdate desc";
	}
	$commentcount=0;
	$search_comments=trim($search_comments);
	if(($allowusercomments==1) && ($search_comments) && ($searchcomments==1))
	{
		$musts=array();
		$cans=array();
		$nots=array();
		$searchcriterias=0;
		$sql_comment="select * from ".$tableprefix."_comments WHERE ";
		$sql_comment .=" (";
		$searchterms = explode(" ",$search_comments);
		foreach($searchterms as $searchstring)
		{
			$qualifier=substr($searchstring,0,1);
			if($qualifier=='-')
			{
				array_push($nots,substr($searchstring,1,strlen($searchstring)-1));
			}elseif ($qualifier=='+')
			{
				array_push($musts,substr($searchstring,1,strlen($searchstring)-1));
			}
			else
			{
				array_push($cans,$searchstring);
			}
		}
		$first=1;
		if(count($musts)>0)
		{
			$commentcount++;
			$sql_comment .="(";
			$searchcriterias++;
			for($i=0;$i<count($musts);$i++)
			{
				if($first==1)
					$first=0;
				else
					$sql_comment .=" and ";
				$sql_comment .="comment like '%".$musts[$i]."%'";
			}
			$sql_comment .=")";
		}
		$first=1;
		if(count($nots)>0)
		{
			$commentcount++;
			if($searchcriterias>0)
				$sql_comment .=" and ";
			$sql_comment .="(";
			$searchcriterias++;
			for($i=0;$i<count($nots);$i++)
			{
				if($first==1)
					$first=0;
				else
					$sql_comment .=" and ";
				$sql_comment .="comment not like '%".$nots[$i]."%'";
			}
			$sql_comment .=")";
		}
		$first=1;
		if((count($cans)>0) && (count($musts)<1))
		{
			$commentcount++;
			if($searchcriterias>0)
				$sql_comment .=" and ";
			$sql_comment .="(";
			$searchcriterias++;
			for($i=0;$i<count($cans);$i++)
			{
				if($first==1)
					$first=0;
				else
					$sql_comment .=" or ";
				$sql_comment .="comment like '%".$cans[$i]."%'";
			}
			$sql_comment .=")";
		}
		$sql_comment .=") group by commentnr order by postdate";
	}
?>
<table style="clear:both" width="<?php echo $TableWidth?>" border="0" CELLPADDING="1" CELLSPACING="0" ALIGN="<?php echo $tblalign?>" VALIGN="TOP">
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $heading_bgcolor?>" ALIGN="CENTER">
<TD ALIGN="CENTER" VALIGN="MIDDLE">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize3?>; color: <?php echo $HeadingFontColor?>; font-weight: bold;">
<?php echo $l_searchresult?>:</span>
</td></tr></table></td></tr>
<tr><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="<?php echo $tablepadding?>" CELLSPACING="<?php echo $tablespacing?>" WIDTH="100%">
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<?php
	if(($numcriterias<1) && ($commentcount<1) && ($questioncount<1))
	{
		$num_results=0;
		echo "<td bgcolor=\"$group_bgcolor\" ALIGN=\"CENTER\">";
		echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
		echo $l_searchnoquery;
		echo "</span></td></tr>";
	}
	else
	{
		if($numcriterias>0)
		{
			if(($stype==0) && ($searchmethod==0))
			{
				if(count($faqnrs)>0)
				{
					$sql .=" faq.faqnr in (";
					$first=1;
					for($i=0;$i<count($faqnrs);$i++)
					{
						if($first==1)
							$first=0;
						else
							$sql.=", ";
						$sql.=$faqnrs[$i];
					}
					$sql .=") group by faq.faqnr order by faq.editdate desc";
					if(!$result = faqe_db_query($sql, $db)) {
						die("Could not connect to the database (3).".faqe_db_error());
					}
					$num_results = faqe_db_num_rows($result);
				}
				else
					$num_results = 0;
			}
			else
			{
				$sql .=") group by dat.faqnr order BY dat.editdate desc";
				if(!$result = faqe_db_query($sql, $db)) {
					die("Could not connect to the database (3).".faqe_db_error());
				}
				$num_results = faqe_db_num_rows($result);
			}
?>
<tr><td align="left" colspan="3" bgcolor="<?php echo $subheadingbgcolor?>">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold;">
<?php echo $l_searchfaq?>:</span></td></tr>
<?php
			$num_results_faq = $num_results;
			$logtxt.="{FAQ: ".$num_results."} ";
			if($num_results<1)
			{
				echo "<td bgcolor=\"$group_bgcolor\" ALIGN=\"CENTER\" colspan=\"3\">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
				echo $l_searchnonefound;
				echo "</span></td></tr>";
			}
			else
			{
				if($max_results>0)
				{
					if(isset($start) && ($start>0) && ($num_results>$max_results))
					{
						$sql .=" limit $start,$max_results";
					}
					else
					{
						$sql .=" limit $max_results";
						$start=0;
					}
					if(!$result = faqe_db_query($sql, $db))
						die("Could not connect to the database (3).".faqe_db_error());
				}
				echo "<td bgcolor=\"$group_bgcolor\" ALIGN=\"CENTER\" colspan=\"3\">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize5; color: $FontColor\">";
				echo "$num_results $l_searchnumresults";
				if(($max_results>0) && ($num_results>$max_results))
				{
					if(($max_results+$start)>$num_results)
						$displayresults=$num_results;
					else
						$displayresults=($max_results+$start);
					echo "<br>($l_showing: ".($start+1)." - $displayresults)";
				}
				echo "</span></td></tr>";
				WHILE ($myrow=faqe_db_fetch_array($result))
				{
					if($myrow["linkedfaq"]!=0)
					{
						$faqsql="select * from ".$tableprefix."_data where faqnr=".$myrow["linkedfaq"];
						if(!$faqresult = faqe_db_query($faqsql, $db))
						{
							echo "<tr><td bgcolor=\"$heading_bgcolor\">";
					    		die("Could not connect to the database.");
					    	}
						if (!$faqrow = faqe_db_fetch_array($faqresult))
						{
							echo "<tr><td bgcolor=\"$heading_bgcolor\">";
							die("dead FAQ link");
						}
					}
					else
						$faqrow=$myrow;
					list($year, $month, $day) = explode("-", $faqrow["editdate"]);
					if($month>0)
						$displaydate=date($dateformat,mktime(0,0,0,$month,$day,$year));
					else
						$displaydate="";
					$catnr=$myrow["category"];
					$sql = "select * from ".$tableprefix."_category where (catnr='$catnr')";
					if(!$result2 = faqe_db_query($sql, $db)) {
						die("Could not connect to the database (3).");
					}
					if($myrow2=faqe_db_fetch_array($result2))
					{
						$prognr=$myrow2["programm"];
						$catname=$myrow2["categoryname"];
					}
					else
					{
						$prognr=0;
						$catname="";
					}
					$sql = "select * from ".$tableprefix."_programm where (prognr='$prognr')";
					if(!$result2 = faqe_db_query($sql, $db)) {
						die("Could not connect to the database (3).");
					}
					if($myrow2=faqe_db_fetch_array($result2))
					{
						$progid=$myrow2["progid"];
						$progname=$myrow2["programmname"];
						$language=$myrow2["language"];
					}
					else
					{
						$progid="";
						$progname="";
						$language=$default_lang;
					}
					echo "<tr><td bgcolor=\"$row_bgcolor\" ALIGN=\"CENTER\" width=\"5%\">";
					if($newtime>0)
					{
						list($year, $month, $day) = explode("-", $faqrow["editdate"]);
						$tempdays=dateToJuliandays($day, $month, $year);
						if($tempdays>$newdatedays)
							echo "<img src=\"$newpic\" border=\"0\" title=\"$l_newfaq2\" alt=\"$l_newfaq2\">";
						else
							echo "&nbsp;";
					}
					echo "</td><td bgcolor=\"$row_bgcolor\" ALIGN=\"LEFT\" width=\"85%\">";
					echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
					if($navframe==1)
						$linkurl=$url_faqengine."/faqframe.php";
					else
						$linkurl=$url_faqengine."/faq.php";
					$linkurl.="?display=faq&amp;faqnr=".$myrow["faqnr"]."&amp;catnr=$catnr&amp;prog=$progid&amp;$langvar=$language";
					if(isset($limitprog))
						$linkurl.="&amp;limitprog=$limitprog";
					if(isset($onlynewfaq))
						$linkurl.="&amp;onlynewfaq=$onlynewfaq";
					if(isset($layout))
						$linkurl.="&amp;layout=$layout";
					$linkurl=addhighlights($linkurl,$musts,$cans);
					echo "<a href=\"$linkurl\"";
					if($navframe==1)
						echo " target=\"_parent\"";
					echo ">";
					if(bittst($searchoptions,BIT_1))
					{
						echo display_encoded($progname);
						if(bittst($searchoptions,BIT_2))
							echo " : ".display_encoded($catname);
						if(bittst($searchoptions,BIT_3) && ($myrow["subcategory"]!=0))
						{
							$subcatsql="select * from ".$tableprefix."_subcategory where catnr=".$myrow["subcategory"];
							if(!$subcatresult = faqe_db_query($subcatsql, $db))
								die("Could not connect to the database");
							if($subcatrow=faqe_db_fetch_array($subcatresult))
							echo " : ".display_encoded($subcatrow["categoryname"]);
						}
						echo " : ";
					}
					echo undo_html_ampersand(stripslashes($myrow["heading"]));
					echo "</a></span>";
					if(($showsummary==1) && ($local_showsummary==1))
					{
						$summarytext=get_summary($faqrow["questiontext"],$summarylength);
						$summarytext=search_highlight($summarytext,$musts,$cans);
						echo "<br><span style=\"font-face: $FontFace; font-size: $FontSize4;\">$l_question: $summarytext</span>";
						$summarytext=get_summary($faqrow["answertext"],$summarylength);
						$summarytext=search_highlight($summarytext,$musts,$cans);
						echo "<br><span style=\"font-face: $FontFace; font-size: $FontSize4;\">$l_answer: $summarytext</span>";
					}
					echo "</td>";
					echo "<td bgcolor=\"$row_bgcolor\" width=\"10%\" align=\"center\">";
					echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
					echo $displaydate."</span></td></tr>";
				}
				if(($max_results>0) && ($num_results>$max_results))
				{
					$urlstart="$act_script_url?lang=$lang&amp;stype=$stype";
					$urlstart.="&amp;max_results=$max_results";
					$urlstart.="&amp;criteria_option=$criteria_option";
					if(isset($onlynewfaq))
						$urlstart.="&amp;onlynewfaq=$onlynewfaq";
					if($stype==0)
						$urlstart.="&amp;search_arguments=".urlencode($search_arguments);
					else
					{
						$urlstart.="&amp;search_head=".urlencode($search_head);
						$urlstart.="&amp;search_question=".urlencode($search_question);
						$urlstart.="&amp;search_answer=".urlencode($search_answer);
						$urlstart.="&amp;search_comments=".urlencode($search_comments);
						$urlstart.="&amp;search_questions=".urlencode($search_questions);
						$urlstart.="&amp;newdays=$newdays";
					}
					if(isset($searchmethod))
						$urlstart.="&amp;searchmethod=$searchmethod";
					if(isset($local_searchquestions))
						$urlstart.="&amp;local_searchquestions=$local_searchquestions";
					if(isset($local_searchcomments))
						$urlstart.="&amp;local_searchcomments=$local_searchcomments";
					if(isset($enablesummary))
						$urlstart.="&amp;enablesummary=$enablesummary";
					$urlstart.="&amp;dosearch=1";
					if($navframe==1)
						$urlstart.="&amp;navframe=1";
					if(isset($limitprog))
						$urlstart.="&amp;limitprog=$limitprog";
					if(isset($layout))
						$urlstart.="&amp;layout=$layout";
					if(($max_results+$start)>$num_results)
						$displayresults=$num_results;
					else
						$displayresults=($max_results+$start);
					$displaystart=$start+1;
					$displayend=$displayresults;
					echo "<tr><td align=\"center\" colspan=\"3\" bgcolor=\"$actionbgcolor\">";
					echo "<table width=\"100%\" align=\"center\">";
					echo "<tr><td class=\"pagenav\" align=\"left\" width=\"10%\">";
					echo "<span style=\"font-face: $FontFace; font-size: $pagenavfontsize color: $pagenavfontcolor;\">";
					if((floor(($start+$max_results)/$max_results)>1) && ($usepagenavicons==1))
					{
						echo "<a class=\"pagenav\" href=\"$urlstart&amp;start=0\">";
						echo "<img src=\"".$firstpagepic."\" border=\"0\" align=\"middle\" title=\"$l_page_first\" alt=\"$l_page_first\">";
						echo "</a> ";
						echo "<a class=\"pagenav\" href=\"$urlstart&amp;start=".($start-$max_results)."\">";
						echo "<img src=\"".$prevpagepic."\" border=\"0\" align=\"middle\" title=\"$l_page_back\" alt=\"$l_page_back\">";
						echo "</a> ";
					}
					else
						echo "&nbsp;";
					echo "</span></td><td width=\"80%\" align=\"center\">";
					echo "<span style=\"font-face: $FontFace; font-size: $pagenavfontsize; color: $pagenavfontcolor;\">";
					echo "$l_page ";
					for($i=1;$i<($num_results/$max_results)+1;$i++)
					{
						if(floor(($start+$max_results)/$max_results)!=$i)
						{
							echo "<a class=\"pagenav\" href=\"$urlstart&amp;start=".(($i-1)*$max_results)."\">";
							echo "$i</a> ";
						}
						else
							echo "$i ";
					}
					echo "</span></td><td align=\"right\" width=\"10%\">";
					echo "<span style=\"font-face: $FontFace; font-size: $pagenavfontsize; color: $pagenavfontcolor; font-weight: bold\">";
					if(($start < (($i-2)*$max_results)) && ($usepagenavicons==1))
					{
						echo "<a class=\"pagenav\" href=\"$urlstart&amp;start=".($start+$max_results);
						echo "\">";
						echo "<img src=\"".$nextpagepic."\" border=\"0\" align=\"middle\" title=\"$l_page_forward\" alt=\"$l_page_forward\">";
						echo "</a> ";
						echo "<a class=\"pagenav\" href=\"$urlstart&amp;start=".(($i-2)*$max_results);
						echo "\">";
						echo "<img src=\"".$lastpagepic."\" border=\"0\" align=\"middle\" title=\"$l_page_last\" alt=\"$l_page_last\">";
						echo "</a> ";
					}
					else
						echo "&nbsp;";
					echo "</span></td></tr>";
					echo "</table>";
					echo "</td></tr>";
				}
			}
		}
		if(($allowusercomments==1) && ($commentcount>0) && ($searchcomments==1))
		{
			if(!$result = faqe_db_query($sql_comment, $db)) {
				die("Could not connect to the database (4).");
			}
?>
<tr><td align="left" colspan="3" bgcolor="<?php echo $subheadingbgcolor?>">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold;">
<?php echo $l_searchcomments?>:</span></td></tr>
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<?php
			$num_results = faqe_db_num_rows($result);
			$logtxt.="{Comments: ".$num_results."} ";
			if(!$num_results)
			{
				echo "<td bgcolor=\"$group_bgcolor\" ALIGN=\"CENTER\" colspan=\"3\">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
				echo $l_searchnonefound;
				echo "</span></td></tr>";
			}
			else
			{
				if($max_results>0)
				{
					$sql_comment.=" limit $max_results";
					if(!$result = faqe_db_query($sql_comment, $db)) {
						die("Could not connect to the database (4).");
					}
				}
				echo "<td bgcolor=\"$group_bgcolor\" ALIGN=\"CENTER\" colspan=\"3\">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize5; color: $FontColor;\">";
				echo "$num_results $l_searchnumresults";
				if(($max_results>0) && ($num_results>$max_results))
					echo "<br>($l_showing: $max_results)";
				echo "</span></td></tr>";
				WHILE ($myrow=faqe_db_fetch_array($result))
				{
					list($date,$time) = explode(" ",$myrow["postdate"]);
					list($year, $month, $day) = explode("-", $date);
					if($month>0)
						$displaydate=date($dateformat,mktime(0,0,0,$month,$day,$year));
					else
						$displaydate="";
					echo "<tr bgcolor=\"$row_bgcolor\">";
					echo "<td>&nbsp;</td>";
					$tmpsql = "select prog.programmname, cat.categoryname, dat.heading, prog.progid, cat.catnr from ".$tableprefix."_programm prog, ".$tableprefix."_category cat, ".$tableprefix."_data dat ";
					$tmpsql .="where prog.prognr=cat.programm and cat.catnr = dat.category and dat.faqnr=".$myrow["faqnr"];
					if(!$tmpresult = faqe_db_query($tmpsql, $db)) {
						die("Could not connect to the database (5).");
					}
					if(!$tmprow=faqe_db_fetch_array($tmpresult))
					{
						$displaytext=get_summary($myrow["comment"],$summarylength);
						$summarytext="";
					}
					else
					{
						$displaytext=display_encoded($tmprow["programmname"]).":".display_encoded($tmprow["categoryname"]).":".undo_html_ampersand(stripslashes($tmprow["heading"]));
						if(($showsummary==1) && ($local_showsummary==1))
						{
							$summarytext=display_encoded(get_summary($myrow["comment"],$summarylength));
						}
					}
					echo "<td bgcolor=\"$row_bgcolor\" ALIGN=\"LEFT\" width=\"85%\">";
					echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
					$linkurl=$url_faqengine."/comment.php?mode=read&amp;prog=".$tmprow["progid"]."&amp;catnr=".$tmprow["catnr"]."&amp;faqnr=".$myrow["faqnr"]."&amp;commentnr=".$myrow["commentnr"]."&amp;$langvar=$act_lang";
					if(isset($onlynewfaq))
						$linkurl.="&amp;onlynewfaq=$onlynewfaq";
					if($navframe==1)
						$linkurl.="&amp;navframe=1";
					if(isset($limitprog))
						$linkurl.="&amp;limitprog=$limitprog";
					if(isset($layout))
						$linkurl.="&amp;layout=$layout";
					$linkurl=addhighlights($linkurl,$musts,$cans);
					echo "<a href=\"$linkurl\">";
					echo "$displaytext</a></span>";
					if($summarytext)
						echo "<br><span style=\"font-face: $FontFace; font-size: $FontSize4;\">$l_comment: $summarytext</span>";
					echo "</td>";
					echo "<td bgcolor=\"$row_bgcolor\" width=\"10%\" align=\"center\">";
					echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
					echo $displaydate."</font></td></tr>";
				}
			}
		}
		if(($allowquestions==1) && ($questioncount>0) && ($searchquestions==1))
		{
			if(!$result = faqe_db_query($sql_questions, $db)) {
				die("Could not connect to the database (5).");
			}
?>
<tr><td align="left" colspan="3" bgcolor="<?php echo $subheadingbgcolor?>">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $FontSize2?>; color: <?php echo $SubheadingFontColor?>; font-weight: bold;">
<?php echo $l_search_userquestions?>:</span></td></tr>
<tr BGCOLOR="<?php echo $row_bgcolor?>" ALIGN="CENTER">
<?php
			$num_results = faqe_db_num_rows($result);
			$logtxt.="{Userquestions: ".$num_results."} ";
			if(!$num_results)
			{
				echo "<td bgcolor=\"$group_bgcolor\" ALIGN=\"CENTER\" colspan=\"3\">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
				echo $l_searchnonefound;
				echo "</span></td></tr>";
			}
			else
			{
				if($max_results>0)
				{
					$sql_questions.=" limit $max_results";
					if(!$result = faqe_db_query($sql_questions, $db)) {
						die("Could not connect to the database (5).");
					}
				}
				echo "<td bgcolor=\"$group_bgcolor\" ALIGN=\"CENTER\" colspan=\"3\">";
				echo "<span style=\"font-face: $FontFace; font-size: $FontSize5; color: $FontColor;\">";
				echo "$num_results $l_searchnumresults";
				if(($max_results>0) && ($num_results>$max_results))
					echo "<br>($l_showing: $max_results)";
				echo "</span></td></tr>";
				WHILE ($myrow=faqe_db_fetch_array($result))
				{
					$progsql="select * from ".$tableprefix."_programm where prognr=".$myrow["prognr"];
					if(!$progresult = faqe_db_query($progsql, $db)) {
						die("Could not connect to the database (6).");
					}
					if(!$progrow=faqe_db_fetch_array($progresult)) {
						die("Could not connect to the database (6b).");
					}
					list($date,$time) = explode(" ",$myrow["enterdate"]);
					list($year, $month, $day) = explode("-", $date);
					if($month>0)
						$displaydate=date($dateformat,mktime(0,0,0,$month,$day,$year));
					else
						$displaydate="";
					echo "<tr bgcolor=\"$row_bgcolor\">";
					echo "<td>&nbsp;</td>";
					$displaytext=display_encoded(get_summary($myrow["question"],$summarylength));
					echo "<td bgcolor=\"$row_bgcolor\" ALIGN=\"LEFT\" width=\"85%\">";
					echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
					if($navframe==1)
						$linkurl=$url_faqengine."/faqframe.php?special=question&";
					else
						$linkurl=$url_faqengine."/question.php?";
					$linkurl.="mode=read&amp;prog=".$progrow["progid"]."&amp;question=".$myrow["questionnr"]."&amp;$langvar=$act_lang";
					if(isset($onlynewfaq))
						$linkurl.="&amp;onlynewfaq=$onlynewfaq";
					if(isset($limitprog))
						$linkurl.="&amp;limitprog=$limitprog";
					if(isset($layout))
						$linkurl.="&amp;layout=$layout";
					$linkurl=addhighlights($linkurl,$musts,$cans);
					echo "<a href=\"$linkurl\"";
					if($navframe==1)
						echo " target=\"_parent\"";
					echo ">$displaytext</a></span>";
					if(($showsummary==1) && ($local_showsummary==1) && ($myrow["answerauthor"]>0))
					{
						$summarytext=display_encoded(get_summary($myrow["answer"],$summarylength));
						$summarytext=search_highlight($summarytext, $musts, $cans);
						echo "<br><span style=\"font-face: $FontFace; font-size: $FontSize4;\">$l_answer: $summarytext</span>";
					}
					echo "</td>";
					echo "<td bgcolor=\"$row_bgcolor\" width=\"10%\" align=\"center\">";
					echo "<span style=\"font-face: $FontFace; font-size: $FontSize1; color: $FontColor;\">";
					echo $displaydate."</span></td></tr>";
				}
			}
		}
	}
	if($dosearchlog==1)
	{
		$logfile=@fopen($path_logfiles."/search.log","a");
		if($logfile)
		{
			fwrite($logfile,$logtxt.$crlf);
			fclose($logfile);
		}
	}
	if($newtime>0 && $num_results_faq)
	{
?>
<tr BGCOLOR="<?php echo $newinfobgcolor?>" valign="middle"><td colspan="3">
<span style="font-face: <?php echo $FontFace?>; font-size: <?php echo $newinfofontsize?>; color: <?php echo $newinfofontcolor?>"><img src="<?php echo $newpic?>" border="0" align="middle" title="<?php echo $l_newfaq2?>" alt="<?php echo $l_newfaq2?>"> = <?php echo str_replace("{newtime}",$newtime,$l_newfaq)?></span></td></tr>
<?php
	}
	echo "</table></td></tr></table>";
}
echo "</div>";
include_once('./includes/bottom.inc');
?>