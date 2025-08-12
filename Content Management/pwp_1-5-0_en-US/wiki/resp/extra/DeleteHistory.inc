<?php
/**
 * This response clears all history files older
 * than a few days (configured) and forwards to the extra menu page.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/History.inc' );

class DeleteHistory extends Response {

	function DeleteHistory( &$buffer, &$args ) {
		$this->Response( $buffer, $args, TRUE );
	}


	function service( &$buffer, &$args ) {

		$this->changeState('extra/Extra');

		$counter = History::clearAll();

		if ($counter !== FALSE) { //check equal and type
			$args['extraStatus'] = "OK, $counter files removed from history.";
		} else {
			$args['extraStatus'] = "Error while removing old revisions. Reason unknown.";
		}

	}

}

?>