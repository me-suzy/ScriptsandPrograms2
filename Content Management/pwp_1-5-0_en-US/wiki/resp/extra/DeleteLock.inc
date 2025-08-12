<?php
/**
 * This response clears all lock files from the lock folder.
 * Usually, this action is not required at all. May speed up
 * PWP if there are many lock files but only a few Wiki pages
 * are maintained actively.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Lock.inc' );

class DeleteLock extends Response {

	function DeleteLock( &$buffer, &$args ) {
		$this->Response( $buffer, $args, TRUE );
	}

	function service( &$buffer, &$args ) {

		$this->changeState('extra/Extra');

		$counter = Lock::clearAll();

		if ($counter !== FALSE) { //check equal and type
			$args['extraStatus'] = "OK, all lock files removed.";
		} else {
			$args['extraStatus'] = "Error while removing lock files. Reason unknown.";
		}
	}
}
?>