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
include('./language/lang_'.$act_lang.'.php');
$page_title=$l_faq_title;
$page="faq";
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
		if(faqe_array_key_exists($admcookievals,"faq_filterprog"))
			$filterprog=$admcookievals["faq_filterprog"];
		if(faqe_array_key_exists($admcookievals,"faq_filtercat"))
			$filtercat=$admcookievals["faq_filtercat"];
		if(faqe_array_key_exists($admcookievals,"faq_filterlang"))
			$filterlang=$admcookievals["faq_filterlang"];
		if(faqe_array_key_exists($admcookievals,"faq_sorting"))
			$sorting=$admcookievals["faq_sorting"];
		if(faqe_array_key_exists($admcookievals,"faq_hideunassigned"))
			$hideunassigned=$admcookievals["faq_hideunassigned"];
	}
}
if(!isset($filterprog) || ($filterprog<0))
	if(isset($sorting) && ($sorting>=100))
		unset($sorting);
if(!isset($hideunassigned))
	$hideunassigned=$admhideunassigned;
?>
<table align="center" width="80%" CELLPADDING="1" CELLSPACING="0" border="0" valign="top">
<tr><TD BGCOLOR="#000000">
<?php
if(isset($mode))
{
	if($mode=="mklink")
	{
		include("./includes/faq_link.inc");
	}
	if($mode=="addlink")
	{
		include("./includes/faq_ladd.inc");
	}
	if($mode=="display")
	{
		include("./includes/faq_display.inc");
	}
	if($mode=="new")
	{
		include("./includes/faq_new.inc");
	}
	if($mode=="new2")
	{
		include("./includes/faq_new2.inc");
	}
	if($mode=="add")
	{
		include("./includes/faq_add.inc");
	}
	if($mode=="delattach")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\">><td align=\"center\">";
			die("$l_functionnotallowed");
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		if(($admdelconfirm==1) && !isset($delconfirmed))
		{
			echo "<tr class=\"displayrow\"><td align=\"center\">";
			echo "$l_confirmdel ($l_attachement - FAQ# $input_faqnr)";
			echo "</td></tr>";
			echo "<form action=\"$act_script_url\" method=\"post\">";
			echo "<tr class=\"actionrow\"><td align=\"center\">";
			echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
			echo "<input type=\"hidden\" name=\"mode\" value=\"delete\">";
			echo "<input type=\"hidden\" name=\"delconfirmed\" value=\"1\">";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<input type=\"hidden\" name=\"input_faqnr\" value=\"$input_faqnr\">";
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			echo "<input class=\"faqebutton\" type=\"submit\" name=\"submit\" value=\"$l_yes\">&nbsp;&nbsp;";
			echo "<input class=\"faqebutton\" type=\"button\" onclick=\"history.back()\" value=\"$l_no\">";
			echo "</td></tr></table></td></tr></table>";
			include('./trailer.php');
			exit;
		}
		$deleteSQL = "delete from ".$tableprefix."_faq_attachs where (faqnr=$input_faqnr)";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_attachementdeleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_faqlist</a></div>";
	}
	if($mode=="delete")
	{
		if($admin_rights < 2)
		{
			echo "<tr class=\"errorrow\"><td align=\"center\">";
			die($l_functionnotallowed);
		}
?>
<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1">
<?php
		if(($admdelconfirm==1) && !isset($delconfirmed))
		{
			echo "<tr class=\"displayrow\"><td align=\"center\">";
			echo "$l_confirmdel (FAQ# $input_faqnr)";
			echo "</td></tr>";
			echo "<form action=\"$act_script_url\" method=\"post\">";
			echo "<tr class=\"actionrow\"><td align=\"center\">";
			echo "<input type=\"hidden\" name=\"$langvar\" value=\"$act_lang\">";
			echo "<input type=\"hidden\" name=\"mode\" value=\"delete\">";
			echo "<input type=\"hidden\" name=\"oldcat\" value=\"$oldcat\">";
			echo "<input type=\"hidden\" name=\"delconfirmed\" value=\"1\">";
			if($sessid_url)
				echo "<input type=\"hidden\" name=\"$sesscookiename\" value=\"$url_sessid\">";
			echo "<input type=\"hidden\" name=\"input_faqnr\" value=\"$input_faqnr\">";
			if(is_konqueror())
				echo "<tr><td></td></tr>";
			echo "<input class=\"faqebutton\" type=\"submit\" name=\"submit\" value=\"$l_yes\">&nbsp;&nbsp;";
			echo "<input class=\"faqebutton\" type=\"button\" onclick=\"history.back()\" value=\"$l_no\">";
			echo "</td></tr></table></td></tr></table>";
			include('./trailer.php');
			exit;
		}
		$deleteSQL = "delete from ".$tableprefix."_faq_attachs where (faqnr=$input_faqnr)";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_data where (linkedfaq=$input_faqnr)";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "delete from ".$tableprefix."_data where (faqnr=$input_faqnr)";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$sql = "UPDATE ".$tableprefix."_category SET numfaqs = numfaqs - 1 WHERE (catnr = $oldcat)";
		@faqe_db_query($sql, $db);
		$deleteSQL = "delete from ".$tableprefix."_faq_keywords where (faqnr=$input_faqnr)";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		purge_keywords($db);
		$deleteSQL = "delete from ".$tableprefix."_faq_ref where (srcfaqnr=$input_faqnr) or (destfaqnr=$input_faqnr)";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		$deleteSQL = "DELETE FROM ".$tableprefix."_related_faq WHERE srcfaq = '$input_faqnr' or destfaq = '$input_faqnr'";
		$success = faqe_db_query($deleteSQL,$db);
		if (!$success)
			die("<tr class=\"errorrow\"><td>$l_cantdelete.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_faq_entry $l_deleted<br>";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_faqlist</a></div>";
	}
	if($mode=="edit")
	{
		include("./includes/faq_edit.inc");
	}
	if($mode=="edit2")
	{
		include("./includes/faq_edit2.inc");
	}
	if($mode=="update")
	{
		include("./includes/faq_update.inc");
	}
	if($mode=="resetviews")
	{
		$sql = "UPDATE ".$tableprefix."_data SET views=0 WHERE (faqnr = $input_faqnr)";
		if(!$result = faqe_db_query($sql, $db))
		    die("<tr class=\"errorrow\" align=\"center\"><td>Unable to update the database.");
		echo "<tr class=\"displayrow\" align=\"center\"><td>";
		echo "$l_viewsreset";
		echo "</td></tr></table></td></tr></table>";
		echo "<div class=\"bottombox\" align=\"center\"><a href=\"".do_url_session("$act_script_url?$langvar=$act_lang")."\">$l_faqlist</a></div>";
	}
}
else
{
	if(isset($sorting) && ($sorting>=100))
		include("./includes/faq_list2.inc");
	else
		include("./includes/faq_list.inc");
}
include('./trailer.php');
?>