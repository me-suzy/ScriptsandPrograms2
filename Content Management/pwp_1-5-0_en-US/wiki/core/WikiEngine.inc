<?php
/**
 * It translates incomming wiki code into HTML. It does not
 * add HEAD and BODY tags. Static pages are done by the StaticEngine.
 * Requires a UNIX formatted text with (only) \n as line separator.
 * This class is to be applied as sigleton ($gEngine).
 * For the weird use of preg_replace_callback with an array see
 * commented manual @php.net
 * Refactored in 1.5.0. Interface compatible to 1.4.x.
 * Extended rules: <strike>, images inside of links, [:wiki link]
 * list with 3 levels (2 before), tables with alignment, {{variables}}.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */
require_once( BASE_PATH.'core/Data.inc' );

define('IMAGE_EXTENSIONS', 'gif|jpg|jpeg|png'); //use | with no spaces
define('PROTOCOLS', 'http:\/\/|https:\/\/|ftp:\/\/|mailto:|file:\/\/');

//construct the singleton, which will be created upon require_once()
$gEngine = new WikiEngine();

class WikiEngine {

	/** Limit for Wiki pages marked as recently modified. */
	var $_mRecentlyChangedLimit = 0;

	/** Will contain the TOC, filled by callback function. */
	var $_mToc = '';

	/** Saves temporarily the <nowiki> sections. */
	var $_mEscapes = array();

	/**
	 * Initialize the engine once.
	 */
	function WikiEngine() {
		global $gConfig;
		$this->_mRecentlyChangedLimit = time() - (3*86400);
		$gConfig->mVars['||--'] = "<table width='100%' border='0' cellspacing='8' class='layout'><tr><td class='layout' valign='top'>";
		$gConfig->mVars['--||'] = "</td></tr></table>";
		$gConfig->mVars['--'] = "</td></tr><tr><td class='layout' valign='top'>";
		$gConfig->mVars['||'] =  "</td><td class='layout' valign='top'>";
	}

	/** Translates Wiki code into HTML, utilizing various private helper functions. */
	function transformHtml( &$text ) {

		$text = "<!PWP_BDRY content begin PWP_BDRY>\n".stripslashes( $text )."<!PWP_BDRY content end PWP_BDRY>\n"; //undo magic quotes

		$this->_escapeNowiki( $text );
		$this->_replaceVariables( $text ); //{var:name} {v:name} {{name}}
		$this->concatLines( $text );       //concat "." lines; call immediately after variables
		$this->_markTextElements( $text ); //<P> <HR> <BR>;
		$this->_replaceHeadings( $text );  //must be called before other <html tags>

		$this->_emphasize( $text ); // <em> <strong> <strike> <u>
		$this->_subSup( $text );    // <sub> <sup>

		$text = str_replace( "\n ", "\n,", $text); //saves a call to preg_replace() for ' ' = PRE
		$this->_replaceBlock( ',', 1, '<pre>', '</pre>', $text );
		$this->_replaceBlock( '~', 2, '<div align="right">', '</div>', $text );
		$this->_replaceBlock( '~', 1, '<div align="center">', '</div>', $text );
		$this->_replaceBlock( ':', 2, '<blockquote><blockquote>', '</blockquote></blockquote>', $text );
		$this->_replaceBlock( ':', 1, '<blockquote>', '</blockquote>', $text );
		$this->transformComments( $text ); //i.e. a block replacement, overridden by StaticEngine
		$this->_cleanUpBlock( ',:~!', 1, 2, $text );

		$this->_replaceList( $text ); // <ul>, <ol>, <dl> (also nested)

		$this->_addToc( $text ); 		//table of contents
		$this->linkWiki( $text );	    // [[ ]], overridden by StaticEngine
		$this->_linkInterWiki( $text ); // [Wiki:name page] [w:name page] [:name page] get converted into an external link
		$this->_linkExternal( $text ); 	// [ ] relative links and inline links http:// etc.

		$this->_createTables ( $text );
		$this->_unescapeNowiki( $text );
		$this->_addInfoLine( $text );
		$text = str_replace('PWP_BDRY', '--', $text);
	}

	/** Translates comments. StaticEngine overrides this method. */
	function transformComments( &$text ) {
		$this->_replaceBlock( '!', 1, '<div><small class="gray">/** ', ' */</small></div>', $text );
	}

	/** Concats lines starting with a dot. */
	function concatLines( &$text ) {
		$text = str_replace( "\n.", ' ', $text ); //easy, isn't it?
	}

	/**
	 * Recognizes the headings. The <A NAME> prepares the table of contents.
	 * New in 1.5.0: Uses pseudo tags like <h===h>: just one regexp required!
	 */
	function _replaceHeadings( &$text ) {
		$text = preg_replace( '/^(~{0,2})(={1,6})\s*(.*?)\s*=*\s*$/m', '\1<a name="\3"><h\2h>\3</h\2h></a>', $text );
		$text = str_replace( array('======h>', '=====h>',  '====h>', '===h>', '==h>','=h>' ), array('6>', '5>', '4>','3>', '2>', '1>'), $text );
	}

	/**
	 * Will create the table of contents (toc) if defined in config.
	 * Uses headings H1 and H2. Is rather expensive an can be turned of
	 * in config. Requires headings to be translated into HTML with anchors.
	 */
	function _addToc( &$text ) {
		global $gConfig;

		if (!$gConfig->mProp['CreateTOC']) {
			return true;
		}
		$this->_mToc = ''; //initialise, will be called often when creating static pages
		preg_replace_callback( '/<a name="([^"]+)"><h([12])/mi', array($this, '_storeToc'), $text );
		$text = ($this->_mToc=='')?($text):($this->_mToc.'<hr size="1" noshade>'.$text);
	}

	/** Callback function for TOC. Works only within a singleton. */
	function _storeToc( &$args ) {
		global $gEngine; //$this-> doesn't exist in callback!
		$gEngine->_mToc .= ($args[2] == 1)?("<a href=\"#{$args[1]}\">{$args[1]}</a><br>"):("&nbsp;&middot;&nbsp;<a href=\"#{$args[1]}\">{$args[1]}</a><br>");
	}

	/** Recognizes an empty line as a <p> and -_ as <br> and ---- as <hr>. */
	function _markTextElements( &$text ) {
		$text = preg_replace( "/\n{2,}/s", "\n<p>\n", $text );
		$text = preg_replace( '/^-{4,}\s*$/m', '<hr>', $text );
		$text = str_replace( '-_', '<br>', $text );
	}

	/**
	 * Transforms nested ul,ol, dl (*,#,;) lists. New in 1.5.0: Support for 3 levels, fewer
	 * instructions due to ommiting </li> which copes with HTML 4.01. Basic idea:
	 * Mark a block of 3 list items, then blocks of 2 and 1 list item(s) with respect
	 * to surrounding blocks using pseudo tags. Use cheap string replace to transform
	 * the pseudo tags. Replace the *,#,; by <li> and <dt>, <dl> tags in the end.
	 */
	function _replaceList( &$text ) {
		//create <######>..</######> pseudo tags for string replacement, level 3 down to 1
		$text = preg_replace( '/\n([\*#;]{3,3})(.*?)\n(?![#\*;]{3,3})/si', "\n<\\1\\1l>\\1\\2</\\1\\1l>\n", $text );
		$text = preg_replace( '/\n([\*#;]{2,2})(?![\*#];)(.*?)\n(?!([#\*;]{2,3}|<[#\*;]{6,6}l>))/si', "\n<\\1\\1\\1l>\\1\\2</\\1\\1\\1l>\n", $text );
		$text = preg_replace( '/\n([\*#;])(?![\*#;])(.*?)\n(?!([#\*;]{1,3}|<[#\*;]{6,6}l>))/si', "\n<\\1\\1\\1\\1\\1\\1l>\\1\\2</\\1\\1\\1\\1\\1\\1l>\n", $text );
		//convert pseudo tags into HTML list tags
		$text = str_replace( array('######l>', '******l>', ';;;;;;l>'), array('ol>', 'ul>', 'dl>'), $text );
		//create list item tags <li> and <dt>,<dd>
		$text = preg_replace( '/(\n<[uo]l>|\n)[\*#]{1,3}/si', '\1<li>', $text );
		$text = preg_replace( '/(\n<dl>|\n)[;]{1,3}\s*(.+?)\s*:/si', '\1<dt><a name="\2">\2</a><dd>', $text );
	}

	/**
	 * Replaces a block of lines starting with the same sign.
	 * Forms one HTML block. Examples: <blockquote>, <pre>.
	 * Requires some clean up afterwards because we replace just the
	 * beginning marker of the block and close it at the end.
	 */
	function _replaceBlock( $startChar, $charCount, $openTag, $closeTag, &$text ) {
		$text = preg_replace( "/\n$startChar\{$charCount,$charCount}(?!$startChar)(.*?)\n(?!$startChar\{$charCount,$charCount})/s", "\n$openTag\\1$closeTag\n", $text );
	}

	/** Cleans up the leftover block markers within a block. */
	function _cleanUpBlock( $chars, $min, $max, &$text ) {
		$text = preg_replace( "/\n([$chars]\{$min,$max})/s", "\n", $text );
	}

	/** Executes regular expressions for <strong>, <underline>, <emphasize> and <strike>(new in 1.5.0). */
	function _emphasize( &$text ) {
		$text = preg_replace( '/(?<![\w\[:<\/\d\.;])\/(?![\s\/>])(.+?)\/(?![\w~\/>\d])/s', '<em>\1</em>', $text );
		$text = preg_replace( '/(?<![\w<_\d])_(?![\s_])(.+?)_(?![\w_\d])/s', '<u>\1</u>', $text );
		$text = preg_replace( '/(?<![\w<+\d])\+(?![\s+])(.+?)\+(?![\w+\d])/s', '<strong>\1</strong>', $text );
		$text = preg_replace( '/(?<![\w<\-\|\d\!])--(?![\s\->\|])(.+?)--(?![\w\->\|\d])/s', '<strike>\1</strike>', $text );
		//$text = preg_replace('/--(?![\s\->\|])(.+?)--/', '<strike>\1</strike>', $text );
		//now the single quotes, 2..5 with three possible meanings
		$text = preg_replace( "/(?<![\\w<'])([']{2,5})(?![\s'])(.+?)[']{2,5}(?![\w'])/s", '<\1quote>\2</\1quote>', $text );
		$text = str_replace( array("</'''''quote>", "<'''''quote>", "'''quote>", "''quote>"), array('</em></strong>', '<strong><em>', 'strong>', 'em>'), $text );
	}

	/**
	 * Executes regular expressions for <sub>, <sup> using pseudo tags.
	 * Rewritten in 1.5.0. Look ahead and behind copes with lines of ^^^^.
	 */
	function _subSup( &$text ) {
		$text = preg_replace( '/(?<![\s\n\^])([\^]{1,2}(?![\^\s\n]))([^\n\^]+?)([\^]{1,2}(?!\^))/s', '<\1x>\2</\1x>', $text );
		$text = str_replace( '^^x>', 'sub>', $text);
		$text = str_replace( '^x>', 'sup>', $text);
	}

	/**
	 * Link to Wiki pages using syntax [[file{|text}]] or space instead of "|".
	 * Uses a condition (?(cond)yes|no) to spot the link text. Refactored in 1.5.0.
	 */
	function linkWiki( &$text ) {
		$text = preg_replace_callback( '/\[{2,2}([^\s|\]#]+)(#?.*?)(?(?=[\s|])[\s|](.+?)\]{2,2}|\]{2,2})/s', array($this, '_getWikiLink'), $text );
	}

	/** Helper linkWiki. Callback function for PREG. */
	function _getWikiLink( $args ) {

		if (!isset($args[3])) {
			$args[3] = $args[1]; //if no special link text is given
		}
		if (Data::fileExists( $args[1] )) {
			$markAsNew = ((int)Data::getFileModTime($args[1]) > $this->_mRecentlyChangedLimit)?('<em title="Recently modified.">*</em>'):('');
			return "<a href='{$_SERVER['PHP_SELF']}?iRequest=wiki/ViewPage&amp;iPage={$args[1]}{$args[2]}' title='View page: {$args[1]}{$args[2]}'>$args[3]$markAsNew</a>";
		} else {
			return "<u>$args[3]</u>(<a href='{$_SERVER['PHP_SELF']}?iRequest=wiki/CreatePage&amp;iPage={$args[1]}' title='Create page: {$args[1]}'>?</a>)";
		}
	}

	/** Removes comments and escaped sections and stores latter ones in _mEscapes[] */
	function _escapeNowiki( &$text ) {
		//remove "real" comments
		$text = preg_replace( '/!--(.+?)--!/s', '', $text );
		//remove and save fragments
		$text = preg_replace_callback( '/!-(.+?)-!/s', array($this, '_storeEscapes'), $text );
	}

	/** Helper for _escapeNowiki(). */
	function _storeEscapes( $args ) {
		global $gEngine; //$this-> doesn't exist in callback!
		$gEngine->_mEscapes[] = $args[1];
		$number = sizeof($gEngine->_mEscapes) - 1;
		return "<escaped_$number>";
	}

	/** Undoes the _escapeNowiki() action. */
	function _unescapeNowiki( &$text ) {
		$count = sizeof( $this->_mEscapes );
		for ($i=0; $i<$count; $i++) {
			$text = str_replace("<escaped_$i>", $this->_mEscapes[$i], $text);
		}
	}

	/**
	 * Link to InterWiki pages using syntax [Wiki:name|{text or image}], [{name {text or image}}]
	 * Changed in 1.5.0, converts InterWiki link to ordinary [link|text] for later translation.
	 */
	function _linkInterWiki( &$text ) {
		global $gConfig;
		$text = preg_replace_callback( '/\[(-?)(?:w[ik]{0,3}:|{)(.+?)[\s|:]([^#\]\}]*)(#?.*?)[\]\}]{1,2}/si', array($this, '_getInterLink'), $text );
	}

	/** Helper _linkInterWiki. Callback function for PREG. No regexp since 1.5.0. Main funtion uses a conditional match. */
	function _getInterLink( $args ) {
		global $gConfig;
		//1=InterWiki link name, 2=file to link to, 3=optional target within file (#...)
		if (isset( $gConfig->mIWiki[ $args[2] ])) {
			return "[$args[1]{$gConfig->mIWiki[ $args[2] ]}{$args[3]}{$args[4]}|$args[2]:{$args[3]}]";
		} else {
			return "<span class='error' title='This InterWiki link is not defined: $args[2]'>$args[2]:$args[3](!)</span>";
		}
	}

	/** Replaces variables using syntax {v:name} or {{name}} (new in 1.5.0). */
	function _replaceVariables( &$text ) {
		$text = preg_replace_callback( '/\{(?:v[ar]{0,2}:|\{)([^\}]+)\}{1,2}/i', array($this, '_getVariable'), $text );
	}

	/** Helper _replaceVariables. Callback function for PREG. */
	function _getVariable( $args ) {
		global $gConfig;

		if (isset( $gConfig->mVars[ $args[1] ])) {
			return $gConfig->mVars[ $args[1] ];
		} else {
			return "<span class='error' title='This variable is not defined: $args[1]'>\{$args[1]}(!)</span>";
		}
	}

	/**
	 * Link to external pages/images using syntax [url{|or text}] or http://
	 * (i.e. both, relative links and inline links). Rewritten in 1.5.0.
	 * There must be a leading non-word char, supressing translation of 'int[]', etc.
	 * TODO: check preceeding chars for first and last regexp! Compare to 1.4.2.
	 */
	function _linkExternal( &$text ) {
		//link images: [url] [url|text] url; must be first call; otherwise links would match image URLs as well
		$text = preg_replace( '/([\n\s>;])(?(?!\[-)\[([\w\d\/:\.\+\-~\?&;]+?(?:'.IMAGE_EXTENSIONS.'))[\s|]*([^\]]*)\]|(http:\/\/[\w\d\/:\.\+\-]*?(?:'.IMAGE_EXTENSIONS.')))/si', '\1<img src="\2\4" alt="\3" border="0">', $text );
		//link external: [url|text] [url text]
		$text = preg_replace( '/\[-?([\w\d\/:\.\+\-~\?&;]+?)[\s|]([^\]]+)\]/s', '<a href="\1">&raquo;\2</a>', $text );
		//link external: [url] url
		$text = preg_replace( '/([\n\s>;])(?(?=\[-?)\[-?([\w\d\/:\.\+\-~\?&;]+?)\]|((?:'.PROTOCOLS.')[^\s<\n)]*?)([\s<\n]))/si', '\1<a href="\2\3">&raquo;\2\3</a>\4', $text );
	}

	/**
	 * Builds a table ||cell1||cell2||~cell3||~~cell4||
	 * One row MUST be on one line. Old table start/end ||-- not
	 * required but still supported.
	 */
	function _createTables( &$text ) {
		//tables are rare, check first and save expensive calls on failure
		if (strpos($text, "\n||") !== false ) {
			//block replacement; for compatibility reasons (prior to 1.5.0) ||-- --|| is supported as well
			$text = preg_replace( '/(?:\n\|\|--\s*)?\n(\|\|[\-]?.*?)\n(?:--\|\|\s*\n)?(?!\|\|)/s', "\n<table border='0' cellspacing='2' cellpadding='2'>\n\\1\n</table>\n", $text );
			//TH ||-h1-||- h2 -||
			$text = str_replace( array("-||\n", "\n||-", '-||-'), array("</th></tr>\n", "\n<tr><th>", '</th><th>'), $text );
			//TD ||cell1|| cell2 ||
			$text = str_replace( array("\n||", "||\n", '||'), array("\n<tr><td>", "</td></tr>\n", '</td><td>'), $text );
			//center and right alignment
			$text = str_replace( array('<td>~~', '<th>~~', '<td>~','<th>~'), array('<td align="right">', '<th align="right">', '<td align="center">', '<th align="center">'), $text );
		}
	}

	/** Adds a line with created date, file size at the end of $text. */
	function _addInfoLine( &$text ) {
		global $gConfig;
		if ($gConfig->mProp['ShowInfoLine']) {
			$text .= '<hr size="1" noshade><small>'.Data::getFileInfo($_REQUEST['iPage']).'</small>';
		}
	}
}
?>