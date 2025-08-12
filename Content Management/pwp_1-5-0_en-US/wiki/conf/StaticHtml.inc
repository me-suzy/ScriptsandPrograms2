<?php

/**
 * Provides HTML header and footer for static web pages
 * and code for a static "index.html".
 * To be used as static class (StaticHtml::).
 * Header, menu and footer for dynamic pages can be found
 * in resp/out/Html.inc.
 * Queries the settings of $gConfig->mProp['*']} which
 * are also applied for the dynamic web pages.
 *
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

define( 'DIRECTORY_INDEX', 'index' ); //index{.html} or default{.html} (no extension, please)


class StaticHtml {

	/**
	 * Define your header here.
	 * Returns a string.
	 * $wikiPage contains the page name without path and extension.
	 */
	function getHeader( $wikiPage ) {
		global $gConfig;
		$ext = STATIC_EXTENSION; //defined in conf/Config
		return <<<eoh
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>
<html>
<head>
	<meta name="Keywords" content="{$gConfig->mProp['CommonKeywords']}">
	<meta name="Description" content="{$gConfig->mProp['CommonDescription']}">
	<meta name="Language" content="{$gConfig->mProp['Language']}">
	<meta name="Generator" content="PWP Wiki Processor">
	<meta name="robots" content="index,follow">
	<title>{$gConfig->mProp['HtmlTitle']} &raquo; $wikiPage</title>
	{$gConfig->mProp["StyleSheets"]}
</head>
<body>
	<div id='uppermenu'>
		{$gConfig->mProp['LogoImg']}
		<h1>$wikiPage</h1>
		<p>
		| <a href='{$gConfig->mProp['StartPage']}$ext'>{$gConfig->mProp['StartPage']}</a> |
		</p>
	</div>
	<div id='contents'>
eoh;
	}

	/**
	 * Define your footer here.
	 * Returns a string.
	 * $wikiPage contains the page name without path and extension.
	 */
	function getFooter( $wikiPage ) {
		global $gConfig;
		return <<<eoh
	</div>
	{$gConfig->mProp['FooterLine']}
</body>
</html>
eoh;
	}

	/**
	 * Returns a directory index file for the web server (e.g. index.html).
	 * This file will only be written if it doesn't exist.
	 * You can also create a wiki page called 'index' :-)
	 */
	function getDirIndex() {
		global $gConfig;
		$ext = STATIC_EXTENSION;
		return <<<eoh
<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>
<html>
<head>
	<meta name="robots" content="noindex,follow">
	<title>{$gConfig->mProp['HtmlTitle']}</title>
	{$gConfig->mProp["StyleSheets"]}
	<meta http-equiv="REFRESH" content="0; URL={$gConfig->mProp['StartPage']}$ext">
</head>
<body>
<a class='gray' href='{$gConfig->mProp['StartPage']}$ext'>&raquo;Go!</a>
</body>
</html>
eoh;
	}
}
?>