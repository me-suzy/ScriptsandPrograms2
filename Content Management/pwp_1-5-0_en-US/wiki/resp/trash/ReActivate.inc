<?php
/**
 * This response unerases a file from Trash, either a wiki
 * page or an uploaded file.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Data.inc' );
require_once( BASE_PATH.'core/Upload.inc' );
require_once( BASE_PATH.'core/Cache.inc' );
require_once( BASE_PATH.'core/Trash.inc' );
require_once( BASE_PATH.'core/History.inc' );

class ReActivate extends Response {

	function ReActivate( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {

		if (!isset($_REQUEST['iTrashPage']) or !Data::isValidFileName($_REQUEST['iTrashPage'])) {
			$gError->add("Missing or invalid file name, cannot unerase!");
		} else if (!Trash::fileExists($_REQUEST['iTrashPage'])) {
			$gError->add("File not found in trash: {$_REQUEST['iTrashPage']}");
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig, $gError;

		if ( !strpos( $_REQUEST['iTrashPage'],  DATA_EXTENSION ) ) {

			//save existing version (could be created by another user again)
			if (Upload::fileExists( $_REQUEST['iTrashPage'])) {
				History::storeUpload(  $_REQUEST['iTrashPage'] );
			}

			if (!Trash::reStoreUpload( $_REQUEST['iTrashPage'] )) {
				$gError->add("Could not unerase file: {$_REQUEST['iTrashPage']}.");
			} else {
				Cache::clearNumerical(INDEX_FILE_NAME);
			}

			$this->changeState('upload/UploadList');

		} else {

			$this->changeState('wiki/ViewPage');
			$_REQUEST['iPage'] = $_REQUEST['iTrashPage'];

			//save existing version (could be created by another user again)
			$tempName = str_replace(DATA_EXTENSION, '',  $_REQUEST['iTrashPage']);
			if (Data::fileExists($tempName)) {
				History::store($tempName);
			}

			if (Trash::reStore($tempName)) {
				Cache::clear($tempName);
				Cache::clearNumerical(INDEX_PAGE_NAME);
				$_REQUEST['iPage'] = $tempName; //adjust parameter for normal use
			} else {
				$gError->add("Could not unerase page: {$_REQUEST['iTrashPage']}");
			}
		}
	}
}

?>