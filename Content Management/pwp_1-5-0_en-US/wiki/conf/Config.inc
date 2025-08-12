<?php
/**
 * A global configuration object for the whole application.
 * Member $mProp is directly accessible, not via get methods.
 * This way the settings can be applied in here docs and strings.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */
define( 'PWP_VERSION', '1-5-0' );

define( 'DATA_EXTENSION',   '._pwp_data' );
define( 'STATIC_EXTENSION', '.html' ); //html without _pwp_ !

define( 'DATA_PATH',   BASE_PATH.'data/' );
define( 'LOCK_PATH',   BASE_PATH.'lock/' );
define( 'CACHE_PATH',  BASE_PATH.'cache/' );
define( 'STATIC_PATH', '../static/' );
define( 'TRASH_PATH',  BASE_PATH.'trash/' );
define( 'HISTORY_PATH',BASE_PATH.'history/' );
define( 'UPLOAD_PATH', BASE_PATH.'upload/' );

class Config {

	/** Globally available properties. */
	var $mProp = array();

	/** InterWiki link bases. */
	var $mIWiki = array();

	/** Global Variables. */
	var $mVars = array();

	/**
	 * Constructs the global Config object.
	 * First object to be created upon any request.
	 */
	function Config() {

		//---uncomment the lines below if you want to bypass a proxy cache---
		//header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
		//header("Cache-Control: post-check=0, pre-check=0", false);
		//header("Pragma: no-cache");  // HTTP/1.0

		$this->_setInterWikiLinks();
		$this->_setVariables();
		$this->_setProperties(); //must be called after variable setting
	}

	/** Define all properties here.	 */
	function _setProperties() {

// --- Application control ---

		/** TRUE|FALSE: Compress HTML into a zip stream. */
		$this->mProp['UseGzipHandler'] 	= FALSE;

		/** DANGER! TRUE|FALSE: Edit variables and InterWiki links in a web form. */
		$this->mProp['AllowEditConfig']	= FALSE;

		/** TRUE|FALSE: Use JavaScript links to lock out search engine spiders. */
		$this->mProp['UseJSLinks'] 		= FALSE;

		/** Comma separated list of text file extensions, don't include spaces. */
		$this->mProp['TextFileTypes']	= '.txt,.htm,.xml';

		/** TRUE|FALSE: Use file lock warning while a user edits. */
		$this->mProp['LockForEdit']	= TRUE;

		/** -1|0|N: Minimal age in days before files in history and trash can be deleted physically. */
		$this->mProp['MinFileAge'] = 20;

		/** String pattern: see PHP documentation of date(), ISO format: 'Y-m-d H:i:s' */
		$this->mProp['DateFormat'] = 'l, Y-m-d H:i';

		/** INTEGER: 1=Top&Bottom, 2=Left 3=Right*/
		$this->mProp['MenuType'] = 1;

		/** Comma separated list of all admin user names. New in 1.5.0. */
		$this->mProp['Admins'] = '';

// --- General HTML settings ---

		/** Page title and (fallback) heading, do not use HTML tags in this string. */
		$this->mProp['HtmlTitle']	 		= 'PWP Wiki Processor';

		/** Page description in HEAD META tag. */
		$this->mProp['CommonDescription'] 	= "PWP Wiki Processor, a GPL based PHP software.";

		/** Page keywords in HEAD META tag. */
		$this->mProp['CommonKeywords'] 		= "PWP, PHP, Wiki, Processor, WikiWiki, Community, Download, Software, Content Management, Documentation, CMS";

		/** Page language in HEAD META tag as two char ISO code. Samples: en, fr, de, dk, se, es, it, hu.*/
		$this->mProp['Language'] 			= "en";

		/** Logo, use a whole IMG tag. The path should be relative. */
		$this->mProp['LogoImg']	 	= "<img src='../img/wiki.jpg' align='right' alt='PWP wiki processor' border='0'>";

		/** A style tag to include a style sheet. The path should be relative. */
		$this->mProp['StyleSheets']	= '<style type="text/css">@import url(../css/standard.css);</style>';

		/** Wiki name of the entry page. It must exist in the data directory (with correct extension). */
		$this->mProp['StartPage']	= "StartPage";

		/** String at the bottom of every page. May contain HTML. */
		$this->mProp['FooterLine']	= "<p>&nbsp;&nbsp;&nbsp;<small>(Powered by <a href='http://www.lars-ackermann.de/wiki/static'>PWP Version 1-5-0 </a>)</small></p>";

// --- Static HTML settings ---

		/** TRUE|FALSE: Allows or denies the creation of static pages. */
		$this->mProp['EnableStaticExport'] = TRUE;

		/** TRUE|FALSE: Removes all comments (Lines starting with '!') in static pages. */
		$this->mProp['RemoveCommentsStatic'] = TRUE;

		/** TEXT: The default message for static "under construction" pages. */
		$this->mProp['ConstructionMessage'] = 'Sorry, this page is still <strong>under construction</strong>.';

// --- Edit, Save, View ---

		/** TRUE|FALSE: Create a table of contents on top of each page, uses H1 and H2 */
		$this->mProp['CreateTOC'] = TRUE;

		/** TRUE|FALSE: Show/hide info line at the bottom of a page. */
		$this->mProp['ShowInfoLine'] = FALSE;

		/** INTEGER: Number of rows of the text edit box. Default: 21. */
		$this->mProp['EditRows'] = 21;

		/** INTEGER: Number of chars in one line of  the text edit box. Default: 85. */
		$this->mProp['EditColumns'] = 85;

		/** STRING: Enumeration of allowed HTML tags inside a Wiki file, '' disables HTML. */
		$this->mProp['AllowedTags'] = '';

		/** TRUE|FALSE: The edit form will support snippets (text fragments) if set to TRUE. */
		$this->mProp['UseSnippets'] = TRUE;

		/** INTEGER: Max. size for uploaded files in KBytes. */
		$this->mProp['MaxUploadSize'] = 1024;

		/** INTEGER: Max size for Wiki pages in KB. (Wiki code size, not HTML) */
		$this->mProp['MaxPostSize'] = 64;

		/** INTEGER: 1=single column, n=multi columns in lists. */
		$this->mProp['IndexType'] = 1;

		/** INTEGER: List entries per page. */
		$this->mProp['ListLength'] = 50;

		/** INTEGER: 1: always record a change in the history,
		             0: leave it to the user (checkbox in edit view),
		            -1: never record any changes in the history.^
		    (since 1.5.0) */
		$this->mProp['HistoryBehaviour'] = 0;

// --- Calendar ---

		/** TRUE|FALSE: Enable calendar creation. */
		$this->mProp['EnableCalendar'] = TRUE;

		/** TRUE|FALSE: Week starts on sunday. */
		$this->mProp['IsUSCalendar'] = TRUE;
	}

// --- Inter Wiki ---

	/**
	 * Stores InterWiki link bases.
	 * Do not use comments at the end of the line, this section is web-editable.
	 * See user manual for variable '/'.
	 */
	function _setInterWikiLinks() {
		$this->mIWiki['.'] = '';
		$this->mIWiki['usemod'] = "http://www.usemod.com/cgi-bin/wiki.pl?";
		$this->mIWiki['file'] = "upload/";
	}

// --- Global Variables ---

	/**
	 * Stores global variables. Adress a variable like this:
	 * "Call our sales contact {v:SalesContact}".
	 * A variable is a string which may contain Wiki code or HTML.
	 * Do not use comments at the end of the line, this section is web-editable.
	 */
	function _setVariables() {
		$this->mVars['Glossary'] = "<img src='../img/i12_info_blue.gif' border='0' alt='Glossary'>";
		$this->mVars['Goto'] = "<img src='../img/i12_see_blue.gif' border='0' alt='Bibliography'>";
		$this->mVars['SalesContact'] = "/Mike (555-084628)/";
		$this->mVars['Version'] = PWP_VERSION;
	}
}

?>