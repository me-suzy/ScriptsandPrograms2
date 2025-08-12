<?php
/**
 * This response clears the cache and forwards to the extra menu page.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Cache.inc' );

class ClearCache extends Response {

	function ClearCache( &$buffer, &$args ) {
		$this->Response( $buffer, $args, TRUE );
	}

	function service( &$buffer, &$args ) {

		$this->changeState('extra/Extra');

		if (Cache::clearAll()) {
			$args['extraStatus'] = "OK, removed all cached lists.";
		} else {
			$args['extraStatus'] = "Error while clearing the cache, reason unknown. Retry the action.";
		}
	}
}
?>