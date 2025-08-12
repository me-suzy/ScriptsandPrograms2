<?php
/**
 * This response shows a wiki page, either out of the cache or
 * it generates a page.
 * As of PWP 1.5.0 Wiki pages are not cached any longer. (Just lists.)
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/WikiEngine.inc' );

class ViewPage extends Response {

	function ViewPage( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gConfig, $gError;

		if (!(isset($_REQUEST['iPage']) and Data::isValidFileName($_REQUEST['iPage']))) {
			$_REQUEST['iPage'] = $gConfig->mProp['StartPage'];
		}

		if (!Data::fileExists($_REQUEST['iPage'])) {
			$gError->add("This page does neither exist nor was its creation requested: {$_REQUEST['iPage']}");
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig, $gError, $gEngine;

		$args['Heading'] = $_REQUEST['iPage'];
		$this->changeState('out/Html');

		//process wiki code, no cache to be used or chache miss happened
		$text =& Data::retrieve( $_REQUEST['iPage'] );
		if ($text !== FALSE) { //type equal
			$gEngine->transformHtml( $text );
			$text = "<div id='textcolumn'>\n$text\n</div>";
			echo $buffer.$text; //that's all!
		} else {
			$gError->add("Could not read Wiki page: {$_REQUEST['iPage']} (Maybe erased?)");
		}
	}
}

?>