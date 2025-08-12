<?php
/**
 * This response clears all files from trash older
 * than a few days (configured) and forwards to the extra menu page.
 * The trash contains both, Wiki pages and uploaded files.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Trash.inc' );

class DeleteTrash extends Response {

	function DeleteTrash( &$buffer, &$args ) {
		$this->Response( $buffer, $args, TRUE );
	}


	function service( &$buffer, &$args ) {

		$this->changeState('extra/Extra');

		$counter = Trash::clearAll();

		if ($counter !== FALSE) { //check equal and type
			$args['extraStatus'] = "OK, $counter files removed from trash.";
		} else {
			$args['extraStatus'] = "Error while removing files from trash. Reason unknown.";
		}
	}
}

?>