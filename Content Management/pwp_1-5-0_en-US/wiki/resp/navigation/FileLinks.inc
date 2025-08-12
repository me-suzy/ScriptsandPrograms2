<?php
/**
 * This response formulates a search for BackLinks
 * for uploaded files (images, links to documents)
 * and forwards to the search result page.
 * Let's keep this response separated in case we need to
 * distinguish this request from the 'real'
 * BackLinks. (We could use BackLinks.inc with
 * a different parameter.)
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Upload.inc' );

class FileLinks extends Response {

	function FileLinks( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gError;

		if (empty($_REQUEST['iPage']) or !Upload::isValidFileName($_REQUEST['iPage'])) {
			$gError->add("Missing parameter.");
		}

		if (!Upload::fileExists($_REQUEST['iPage'])) {
			$gError->add("This file does not exist (any longer): {$_REQUEST['iPage']}. Cannot list BackLinks.");
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig;

		$args['Heading'] = "BackLinks: {$_REQUEST['iPage']}";
		$this->changeState('navigation/FullTextSearchResult');

		$_REQUEST['iSearchText'] = $_REQUEST['iPage'];
		$_REQUEST['iSearchWiki'] = 1;
	}
}

?>