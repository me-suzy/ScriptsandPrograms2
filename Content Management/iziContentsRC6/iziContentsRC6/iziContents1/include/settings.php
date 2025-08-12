<?php

/***************************************************************************

 settings.php
 -------------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/

if ($_SERVER["SCRIPT_NAME"] == '') {
	if ($_SERVER["PHP_SELF"] != '') { $_SERVER["SCRIPT_NAME"] = $_SERVER["PHP_SELF"];
	} elseif ($_SERVER["SCRIPT_FILENAME"] != '') { $_SERVER["SCRIPT_NAME"] = str_replace($_SERVER["DOCUMENT_ROOT"],"",$_SERVER["SCRIPT_FILENAME"]);
	} elseif ($_SERVER["PATH_TRANSLATED"] != '') { $_SERVER["SCRIPT_NAME"] = str_replace($_SERVER["DOCUMENT_ROOT"],"",$_SERVER["PATH_TRANSLATED"]); }
}
if ($_SERVER["PHP_SELF"] == '') { $_SERVER["PHP_SELF"] = $_SERVER["SCRIPT_NAME"]; }
if ($_SERVER["REQUEST_URI"] == '') {
	if ($_SERVER["QUERY_STRING"] != '') { $_SERVER["REQUEST_URI"] = $_SERVER["SCRIPT_NAME"];
	} else { $_SERVER["REQUEST_URI"] = $_SERVER["SCRIPT_NAME"].'?'.$_SERVER["QUERY_STRING"]; }
}


$GLOBALS["safe_mode"]		= (bool) ini_get("safe_mode");
$GLOBALS["open_basedir"] = ini_get("open_basedir");
$GLOBALS["file_uploads"] = (bool) ini_get("file_uploads");

// Default language is English
$GLOBALS["gsLanguage"] = 'en';
GetSettings();
GetUserSettings();
GetLanguageSettings();


if ($_SERVER["HTTP_REFERER"] == '') {
	if ($GLOBALS["gsSecureServer"] == 'Y') { $_SERVER["PAGE_URL"] = 'https://'; } else { $GLOBALS["PAGE_URL"] = 'http://'; }
	if ($_SERVER["HTTP_HOST"] != 'localhost') {
		if (substr($_SERVER["HTTP_HOST"],0,4) != 'www.') { $GLOBALS["PAGE_URL"] .= 'www.'; }
		$GLOBALS["PAGE_URL"] .= $_SERVER["HTTP_HOST"];
	}
	if (($_SERVER["SERVER_PORT"] != '') && ($_SERVER["SERVER_PORT"] != '80')) { $GLOBALS["PAGE_URL"] .= ':'.$_SERVER["SERVER_PORT"]; }
	$GLOBALS["PAGE_URL"] .= $_SERVER["REQUEST_URI"];
} else { $GLOBALS["PAGE_URL"] = $_SERVER["HTTP_REFERER"]; }


function GetSettings()
{
//						Site Options
$settingkeys = array(	'gsMultiSite'					=> 'multisite',
						'gsMultiSiteAuthors'			=> 'multisiteauthors',
						'gsMultiLanguage'				=> 'multilanguage',
						'gsMultiTheme'					=> 'multitheme',
						'gsUse_compression'				=> 'use_compression',
						'gsTimegen_display'				=> 'timegen_display',
						'gsDefault_language'			=> 'default_language',
						'gsSecureServer'				=> 'secureserver',
						'gsSectionSecurity'				=> 'sectionsecurity',
						'gsDateFormat'					=> 'dateformat',
						'gsTimezone'					=> 'timezone',
						'gsUseFrames'					=> 'useframes',
						'gsSiteStats'					=> 'sitestats',
						'gsSitetitle'					=> 'sitetitle',
						'gsSitedesc'					=> 'sitedesc',
						'gsSitekeywords'				=> 'sitekeywords',
						'gnTopFrameHeight'				=> 'topframe_height',
						'gsTopHtml'						=> 'tophtml',
						'gsShowTopMenu'					=> 'showtopmenu',
						'gnTopMenuFrameHeight'			=> 'topmenuframe_height',
						'gsUserdataFrame'				=> 'userdataframe',
						'gnUserdataFrameWidth'			=> 'userdataframewidth',
						'gsMenuFrameAlign'				=> 'menuframealign',
						'gnLeftFrameWidth'				=> 'leftframe_width',
						'gsExpandMenus'					=> 'expandmenus',
						'gsCollapseMenus'				=> 'collapsemenus',
						'gsMenuHover'					=> 'hoverdisplay',
						'gsLRContentFrame'				=> 'lrcontentframe',
						'gnRightColumnWidth'			=> 'rightcolumnwidth',
						'gnImageColumnBreak'			=> 'imagecolumnbreak',
						'gsShowBanners'					=> 'showbanners',
						'gnBottomFrame'					=> 'bottomframe',
						'gnBottomFrameHeight'			=> 'bottomframe_height',
						'gsFooter'						=> 'footer',
						'gsSiteWidth'					=> 'sitewidth',
						'gsSiteHeight'					=> 'siteheight',
//						Graphic Settings
						'gsHomepageLogo'				=> 'homepagelogo',
						'gsMainBg'						=> 'mainbg',
						'gbMainBgRep'					=> 'mainbgrep',
						'gbMainBgFix'					=> 'mainbgfix',
						'gbMainBgPos'					=> 'mainbgpos',
						'gsHeaderBg'					=> 'headerbg',
						'gbHeaderBgRep'					=> 'headerbgrep',
						'gbHeaderBgPos'					=> 'headerbgpos',
						'gsMenuBg'						=> 'menubg',
						'gbMenuBgRep'					=> 'menubgrep',
						'gbMenuBgFix'					=> 'menubgfix',
						'gbMenuBgPos'					=> 'menubgpos',
						'gsTopMenuBg'					=> 'topmenubg',
						'gbTopMenuBgRep'				=> 'topmenubgrep',
						'gbTopMenuBgFix'				=> 'topmenubgfix',
						'gbTopMenuBgPos'				=> 'topmenubgpos',
						'gsTopBg'						=> 'topbg',
						'gbTopBgRep'					=> 'topbgrep',
						'gbTopBgFix'					=> 'topbgfix',
						'gbTopBgPos'					=> 'topbgpos',
						'gsFooterBg'					=> 'footerbg',
						'gbFooterBgRep'					=> 'footerbgrep',
						'gbFooterBgFix'					=> 'footerbgfix',
						'gbFooterBgPos'					=> 'footerbgpos',
						'gsBorderBg'					=> 'borderbg',
						'gbBorderBgRep'					=> 'borderbgrep',
						'gbBorderBgFix'					=> 'borderbgfix',
						'gbBorderBgPos'					=> 'borderbgpos',
						'favicon'						=> 'favicon',
//						Colour Settings
						'bgcolor_main'					=> 'bgcolor_main',
						'bgcolor_menu'					=> 'bgcolor_menu',
						'bgcolor_header'				=> 'bgcolor_header',
						'bgcolor_footer'				=> 'bgcolor_footer',
						'bgcolor_topmenu'				=> 'bgcolor_topmenu',
						'bgcolor_border'				=> 'bgcolor_border',
						'gsFont1'						=> 'font1',
						'gsFontSize2'					=> 'fontsize2',
						'gsRColHeaderFontSize'			=> 'rcol_headerfontsize',
						'gsTopMenuFontSize'				=> 'topmenu_fontsize',
						'gsTopMenuFontStyle'			=> 'topmenu_fontstyle',
						'gsFontSize3'					=> 'fontsize3',
						'gsFontStyle3'					=> 'fontstyle3',
						'color_ahref'					=> 'color_ahref',
						'color_ahref_hover'				=> 'color_ahref_hover',
						'color_ahref_visited'			=> 'color_ahref_visited',
						'menu_color_ahref'				=> 'menu_color_ahref',
						'menu_color_ahref_hover'		=> 'menu_color_ahref_hover',
						'menu_color_ahref_visited'		=> 'menu_color_ahref_visited',
						'topmenu_color_ahref'			=> 'topmenu_color_ahref',
						'topmenu_color_ahref_hover'		=> 'topmenu_color_ahref_hover',
						'topmenu_color_ahref_visited'	=> 'topmenu_color_ahref_visited',
						'rcol_color_ahref'				=> 'rcol_color_ahref',
						'rcol_color_ahref_hover'		=> 'rcol_color_ahref_hover',
						'rcol_color_ahref_visited'		=> 'rcol_color_ahref_visited',
						'color_ahref_small'				=> 'color_ahref_small',
						'color_ahref_small_hover'		=> 'color_ahref_small_hover',
						'color_ahref_small_visited'		=> 'color_ahref_small_visited',
						'gsSmallFontSize'				=> 'smallfontsize',
						'bgcolor_headercnt'				=> 'bgcolor_headercnt',
						'color_header'					=> 'color_header',
						'bgcolor_cnttbl'				=> 'bgcolor_cnttbl',
						'color_td'						=> 'color_td',
						'gsBgcolor_headertsr'			=> 'bgcolor_headertsr',
						'gsColor_tsrheader'				=> 'color_tsrheader',
						'gsBgcolor_tsrtbl'				=> 'bgcolor_tsrtbl',
						'gsColor_tsrtd'					=> 'color_tsrtd',
						'gsFontSize1'					=> 'fontsize1',
						'gsRColFontSize'				=> 'rcolfontsize',
						'gsRColFontStyle'				=> 'rcol_fontstyle',
						'gsFontStyle1'					=> 'fontstyle1',
						'rcol_bgcolor_headercnt'		=> 'rcol_bgcolor_headercnt',
						'rcol_color_header'				=> 'rcol_color_header',
						'rcol_bgcolor_cnttbl'			=> 'rcol_bgcolor_cnttbl',
						'rcol_color_td'					=> 'rcol_color_td',
						'gsErrFormFontColor'			=> 'errform_font_color',
//						Menu Settings
						'gsMenuDistance1'				=> 'menudistance1',
						'gsMenuDistance2'				=> 'menudistance2',
						'gsMenuDistance3'				=> 'menudistance3',
						'gsMenuDistance4'				=> 'menudistance4',
						'gsTopMenuBorder'				=> 'topmenuborder',
						'gsTopMenuAlign'				=> 'topmenualign',
						'gsTopMenuSeparator'			=> 'topmenuseparator',
						'gsTopMenuRows'					=> 'topmenurows',
						'gsMenuBorder'					=> 'menuborder',
						'gsPrivateMenus'				=> 'privatemenus',
						'gsShowMouseover'				=> 'showdhtml',
//						Icons
						'gsExpandIcon'					=> 'expandicon',
						'gsCollapseIcon'				=> 'collapseicon',
						'gsNoExpandIcon'				=> 'noexpandicon',
						'gsSecureIcon'					=> 'secureicon',
						'gsPrintIcon'					=> 'printicon',
                        'gsPDFIcon'						=>  'PDFIcon',
						'gsTellFriendIcon'				=>  'tellfriendicon',
						'gsRatingIcon'					=> 'ratingicon',
						'gsCommentIcon'					=> 'commenticon',
						'gsRatingImage1'				=> 'ratingimage1',
						'gsRatingImage2'				=> 'ratingimage2',
						'gsFirstPageIcon'				=> 'firstpageicon',
						'gsPrevPageIcon'				=> 'prevpageicon',
						'gsNextPageIcon'				=> 'nextpageicon',
						'gsLastPageIcon'				=> 'lastpageicon',
						'gsListStyleIcon'				=> 'liststyleicon',
//						Registration Settings
						'registration_hash'				=> 'registration_hash',
						'registration_details'			=> 'registration_details',
						'registration_date'				=> 'registration_date',
						'PoweredByEZC'					=> 'PoweredByEZC',
//						HTMLArea Settings
						'WYSIWYG_Version'				=> 'WYSIWYG_Version',
						'WYSIWYG_TableManager'			=> 'WYSIWYG_TableManager',
						'WYSIWYG_SpellChecker'			=> 'WYSIWYG_SpellChecker',
//						Other Settings
						'gsBreadcrumb'					=> 'breadcrumb',
						'gnBreadcrumbSeparator'			=> 'breadcrumbseparator',
						'gsBookmark'					=> 'bookmark',
						'gsHomepageGroup'				=> 'homepagegroup',
						'gsHomepageTopGroup'			=> 'homepagetopgroup',
						'gsVisitorStats'				=> 'visitorstats',
						'gsAllowRatings'				=> 'allowratings',
						'gsRatingMin'					=> 'ratingmin',
						'gsRatingMax'					=> 'ratingmax',
						'gsAllowComments'				=> 'allowcomments',
						'gsVetComments'					=> 'vetcomments',
						'gsPrivDefaultGroup'			=> 'privdefault',
						'gsAdminPrivGroup'				=> 'adminprivgroup',
						'gsPrinterFriendly'				=> 'printerfriendly',
						'gsTeaserWithDetails'			=> 'teaserwithdetails',
						'gsServerUserEmail'				=> 'serveruseremail',
						'gsPhoneStatus'					=> 'phone_status',
						'gsFaxStatus'					=> 'fax_status',
						'gsAddressStatus'				=> 'address_status',
						'gsCityStatus'					=> 'city_status',
						'gsStateStatus'					=> 'state_status',
						'gsZipStatus'					=> 'zip_status',
						'gsCountryStatus'				=> 'country_status',
						'gsLanguageStatus'				=> 'language_status',
						'gsWebsiteStatus'				=> 'website_status',
						'gsNewsletterStatus'			=> 'newsletter_status',
						'gsCommentsStatus'				=> 'comments_status',
						'gsShowHelptexts'				=> 'show_helptexts',
						'gsHelptextFontSize'			=> 'helptext_fontsize',
						'gsHelptextColor'				=> 'helptext_color',
						'gsSendConfMail'				=> 'sendconfirmationmail',
						'color_h1'						=> 'color_h1',
						'gsRightTable'					=> 'righttable', 
// Version
						'Version'						=> 'version');


	$strQuery="SELECT * FROM ".$GLOBALS["eztbSettings"];
	$result = dbRetrieve($strQuery,true,0,0);

	while ($rs = dbFetch($result)) {
		$settingname  = $rs["settingname"];
		$settingvalue = trim($rs["settingvalue"]);
		$settingref = array_search($settingname,$settingkeys);
		if($settingname == 'version'){
		$GLOBALS[$settingref] = "Version ".$settingvalue;
		} else {
		$GLOBALS[$settingref] = $settingvalue;
		}
//		$GLOBALS[$settingname] = $settingvalue;
	}
	dbFreeResult($result);
} // function GetSettings()


function GetUserSettings()
{
	global $EZ_SESSION_VARS;

	// Site-admin's default language setting
	if ($GLOBALS["gsDefault_language"] != '') {
		$GLOBALS["gsLanguage"] = $GLOBALS["gsDefault_language"];
	}
	// User's preferred language setting
	if ((isset($EZ_SESSION_VARS["LoginCookie"])) && ($EZ_SESSION_VARS["LoginCookie"] != '')) {
		$strQuery = "SELECT login,language FROM ".$GLOBALS["eztbAuthors"]." WHERE login='".$EZ_SESSION_VARS["LoginCookie"]."'";
		$result = dbRetrieve($strQuery,true,0,0);
		$rs		= dbFetch($result);
		if (($rs["login"] == $EZ_SESSION_VARS["LoginCookie"]) && (trim($rs["language"]) != '')) {
			$GLOBALS["gsLanguage"] = $rs["language"];
		}
		dbFreeResult($result);
	}
	if ($EZ_SESSION_VARS["Language"] != '') { $GLOBALS["gsLanguage"] = $EZ_SESSION_VARS["Language"]; }
} // function GetUserSettings()


function GetLanguageSettings()
{
	$GLOBALS["gsCharset"] = 'iso-8859-1';
	$GLOBALS["direction"] = 'ltr';
	$GLOBALS["right"] = 'right';
	$GLOBALS["left"] = 'left';
	$strQuery = "SELECT * FROM ".$GLOBALS["eztbLanguages"]." WHERE languagecode='".$GLOBALS["gsLanguage"]."'";
	$result = dbRetrieve($strQuery,true,0,0);
	$rs			= dbFetch($result);
	if ($rs["languagecode"] == $GLOBALS["gsLanguage"]) {
		if (trim($rs["charset"]) != '') { $GLOBALS["gsCharset"] = $rs["charset"]; }
		if (trim($rs["direction"]) != '') { $GLOBALS["gsDirection"] = $rs["direction"]; }
		if ($GLOBALS["gsDirection"] == 'rtl') {
			$GLOBALS["right"] = 'left';
			$GLOBALS["left"] = 'right';
		}
	}
	dbFreeResult($result);
} // function GetLanguageSettings()

?>
