<?php
/**
 * This response shows the index of all wiki pages.
 * Prints either a short or a long version (as configured).
 * Doesn't show uploaded pages. The Index is cached.
 * The index file name is contained in Data.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Cache.inc' );
require_once( BASE_PATH.'core/Lists.inc' );

class ViewIndex extends Response {

	function ViewIndex( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		if (empty($_REQUEST['iFileListPart']) || $_REQUEST['iFileListPart'] < 1) {
			$_REQUEST['iFileListPart'] = 1;
		}
		if (empty($_REQUEST['iPageListPart']) || $_REQUEST['iPageListPart'] < 1) {
			$_REQUEST['iPageListPart'] = 1;
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig;

		$args['Heading'] = 'Index of all Wiki pages';
		$this->changeState('out/Html');

		//try to fetch the file from cache
		$text =& Cache::retrieve( INDEX_PAGE_NAME.$_REQUEST['iPageListPart'] );
		if ($text !== FALSE) { //type equal
			echo $text;
			return true;
		}

		//ok we failed, build the index file
		$text = Lists::getIndex( Data::getIndexList(), LIST_PAGE );

		Cache::store( INDEX_PAGE_NAME.$_REQUEST['iPageListPart'], $text, ' <small>(cached)</small>' );
		echo $text;
	}
}

?>