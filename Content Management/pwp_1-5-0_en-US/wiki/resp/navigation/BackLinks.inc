<?php
/**
 * This response formulates a search for BackLinks
 * and forwards to the search result page.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Data.inc' );

class BackLinks extends Response {

	function BackLinks( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gError;

		if (empty($_REQUEST['iPage']) or !Data::isValidFileName($_REQUEST['iPage'])) {
			$gError->add("Missing parameter.");
		}

		if (!Data::fileExists($_REQUEST['iPage'])) {
			$gError->add("This page does not exist (any longer): {$_REQUEST['iPage']}. Cannot list BackLinks.");
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig;

		$args['Heading'] = "BackLinks: {$_REQUEST['iPage']}";
		$this->changeState('navigation/FullTextSearchResult');

		$_REQUEST['iSearchText'] = '[['.$_REQUEST['iPage'];
		$_REQUEST['iRedoUrl'] = "navigation/BackLinks&amp;iPage={$_REQUEST['iPage']}";
		$_REQUEST['iSearchWiki'] = 1;
	}
}

?>