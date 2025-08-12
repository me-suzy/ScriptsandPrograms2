<?php
/**
 * This response lists all files currently in the trash bin.
 * Provides links for re-activation.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Lists.inc' );
require_once( BASE_PATH.'core/Trash.inc' );

class TrashList extends Response {

	function TrashList( &$buffer, &$args ) {
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

		list($arrPages, $arrUploads) = Trash::getSeparatedLists();

		echo "<h3>Wiki pages</h3>\n";
		if (sizeof($arrPages) == 0) {
			echo '<p>No matching Wiki pages found.</p>';
		} else {
			echo Lists::getIndex( $arrPages, LIST_TRASH_PAGE );
		}
		echo "<hr size='1' noshade>\n<h3>Uploaded files</h3>\n";
		if (sizeof($arrUploads) == 0) {
			echo '<p>No matching uploaded files found.</p>';
		} else {
			echo Lists::getIndex( $arrUploads, LIST_TRASH_UPLOAD );
		}

		$args['Heading'] = 'Trash Bin Contents';
		$this->changeState('out/Html');

	}
}

?>