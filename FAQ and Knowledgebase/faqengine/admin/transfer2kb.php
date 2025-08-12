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
$page_title=$l_transfer2kb2;
$page="kb";
$uses_bbcode=true;
require_once('./heading.php');
if(!isset($storefaqfilter) && ($admstorefaqfilters==1))
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
include("./includes/kb_trans.inc");
include('./trailer.php');
?>