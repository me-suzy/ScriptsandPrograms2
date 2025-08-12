<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('../config.php');
require_once('./auth.php');
if(!isset($$langvar) || !$$langvar)
	$act_lang=$admin_lang;
else
	$act_lang=$$langvar;
include_once('./language/lang_'.$act_lang.'.php');
if(!isset($page_title))
	$page_title=$l_kb_article;
$page="kb";
$uses_bbcode=true;
require_once('./heading.php');
if(!isset($storefaqfilter) && ($admstorefaqfilters==1) && (!isset($override)))
{
	$admcookievals="";
	if($new_global_handling)
	{
		if(isset($_COOKIE[$admcookiename]))
			$admcookievals = $_COOKIE[$admcookiename];
	}
	else
	{
		if(isset($_COOKIE[$admcookiename]))
			$admcookievals = $_COOKIE[$admcookiename];
	}
	if($admcookievals)
	{
		if(faqe_array_key_exists($admcookievals,"kb_filterprog"))
			$filterprog=$admcookievals["kb_filterprog"];
		if(faqe_array_key_exists($admcookievals,"kb_filtercat"))
			$filtercat=$admcookievals["kb_filtercat"];
		if(faqe_array_key_exists($admcookievals,"kb_filterlang"))
			$filterlang=$admcookievals["kb_filterlang"];
		if(faqe_array_key_exists($admcookievals,"kb_sorting"))
			$sorting=$admcookievals["kb_sorting"];
		if(faqe_array_key_exists($admcookievals,"kb_hideunassigned"))
			$hideunassigned=$admcookievals["kb_hideunassigned"];
	}
}
if(!isset($filterprog))
	$filterprog=-1;
if(!isset($filtercat))
	$filtercat=-1;
if(!isset($filterlang))
	$filterlang="none";
if(!isset($hideunassigned))
	$hideunassigned=$admhideunassigned;
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(isset($mode))
{
	if($mode=="delattach")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$deleteSQL = "delete from ".$tableprefix."_kb_attachs where (articlenr=$input_articlenr)";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_attachementdeleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_articlelist</a></div>";
	}
	if($mode=="display")
	{
		include("./includes/kb_display.inc");
	}
	// Page called with some special mode
	if($mode=="new")
	{
		include("./includes/kb_new.inc");
	}
	if($mode=="new2")
	{
		include("./includes/kb_new2.inc");
	}
	if($mode=="add")
	{
		include("./includes/kb_add.inc");
	}
	if($mode=="delete")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		$deleteSQL = "delete from ".$tableprefix."_kb_attachs where (articlenr=$input_articlenr)";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_kb_articles where (articlenr=$input_articlenr)";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_kb_os where (articlenr=$input_articlenr)";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_kb_keywords where (articlenr=$input_articlenr)";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		purge_keywords($db);
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "<i>$heading</i> $l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_articlelist</a></div>";
	}
	if($mode=="edit")
	{
		include("./includes/kb_edit.inc");
	}
	if($mode=="edit2")
	{
		include("./includes/kb_edit2.inc");
	}
	if($mode=="update")
	{
		include("./includes/kb_update.inc");
	}
}
else
	include("./includes/kb_list.inc");
include('./trailer.php');
?>