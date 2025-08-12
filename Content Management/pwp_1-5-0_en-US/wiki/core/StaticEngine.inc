<?php

/**
 * This class is to be applied as sigleton ($gEngine->).
 * It translates incomming wiki code to suitable code for static
 * pages without links to PHP scripts. Produces HTML but it
 * does not add HEAD and BODY tags. Head and body are defined
 * in conf/StaticHtml.
 * The only diffrence between dynamic and static pages are the
 * file name and the Wiki links. In case of static pages, they will
 * point to HTML files instead of PHP scripts.
 * This class simply overrides some methods.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

//construct the singleton, which will be created upon require_once()
$gEngine = new StaticEngine();

class StaticEngine extends WikiEngine {

	function StaticEngine() {
		global $gConfig;
		$this->WikiEngine();
		if (!empty($gConfig->mIWiki['.'])) { //important for local links - uploaded files
			$gConfig->mIWiki['.'] .= STATIC_PATH;
		}
	}

	/** Hides pages "under construction" and forwards to parent method. */
	function transformHtml( &$text ) {
		global $gConfig;

		//hide pages with !? comment
		$arrMatches = array();
		parent::concatLines( $text ); //!? might be a multiline info
		if (preg_match('/^!\?(.+)$/m', $text, $arrMatches)) {
			$text = $arrMatches[1]; //[0] contains original text
		}
		$text = (trim($text)=='')?($gConfig->mProp['ConstructionMessage']):($text);
		parent::transformHtml( $text );
	}

	/** Removes comment lines starting with a '!'. */
	function transformComments( &$text ) {
		global $gConfig;
		if ( $gConfig->mProp['RemoveCommentsStatic'] ) {
			$text = preg_replace( "/(^!{1,1}[^\-]{1,1}.*)$/m", '', $text ); //avoid becoming paragraphs (empty line)
		} else {
			parent::transformComments( $text );
		}
	}

	/**
	 * Link to Wiki pages using syntax [[url{|or text}]].
	 * For this weird use of callback see commented manual @php.net
	 */
	function linkWiki( &$text ) {
		$text = preg_replace_callback( '/\[{2,2}([^\s|\]#]+)(#?.*?)(?(?=[\s|])[\s|](.+?)\]{2,2}|\]{2,2})/s', array($this, '_getStaticLink'), $text );
	}

	/** Helper. */
	function _getStaticLink( $args ) {

		//if no special link text is given
		if (empty($args[2])) {
			$args[2] = $args[1];
		}

		$ext = STATIC_EXTENSION;
		if (Data::fileExists( $args[1] )) {
			return "<a href='{$args[1]}$ext'>{$args[2]}</a>";
		} else {
			return "<u title='The page {$args[1]} does not yet exist.'>{$args[2]}</u>";
		}
	}
}

?>
