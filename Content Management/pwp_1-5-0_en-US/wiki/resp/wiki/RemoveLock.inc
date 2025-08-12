<?php
/**
 * This response forcefully removes a lock from a page by setting
 * the lock time stamp to 0. Forwards to the response requested
 * in 'iGoto'.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Lock.inc' );

class RemoveLock extends Response {

	function RemoveLock( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {

		if (!isset($_REQUEST['iPage']) or !Data::isValidFileName($_REQUEST['iPage'])) {
			$gError->add("Missing or invalid page name, cannot unlock page!");
		} else if (!Data::fileExists($_REQUEST['iPage'])) {
			$gError->add("This file does not exist and cannot be unlocked: {$_REQUEST['iPage']}");
		}

		if (!isset($_REQUEST['iGoto'])) {
			$_REQUEST['iGoto'] = 'wiki/ViewPage';
		}
	}

	function service( &$buffer, &$args ) {
		Lock::release($_REQUEST['iPage']);
		$this->changeState($_REQUEST['iGoto']);
	}
}
?>