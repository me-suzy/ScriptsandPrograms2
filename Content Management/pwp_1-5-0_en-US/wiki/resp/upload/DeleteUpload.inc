<?php
/**
 * This response moves an uploaded file into the trash.
 * Forwards to the UploadList.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Upload.inc' );
require_once( BASE_PATH.'core/Trash.inc' );
require_once( BASE_PATH.'core/Cache.inc' );

class DeleteUpload extends Response {

	function DeleteUpload( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gError, $HTTP_POST_FILES;

		if (!isset($_REQUEST['iPage']) or !Upload::isValidFileName($_REQUEST['iPage'])) {
			$gError->add("Missing or invalid file name, cannot delete!");
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig, $gError;

		$f = $_REQUEST['iPage'];

		if ( !Upload::fileExists($f) ) {
			$gError->add("File does not exist: <b>$f</b>. (Maybe deleted by other one?)");
		} else if ( !Trash::storeUpload( $f ) ) {
			$gError->add("Cannot delete existing file <b>$f</b>. (Disk full?)");
		} else {
			echo "<p>OK: File <b>$f</b> deleted.<br></p>\n";
			Cache::clearNumerical( INDEX_FILE_NAME ); //cached list of files table
		}

		$this->changeState('upload/UploadList');
		$_REQUEST['iPage'] = NULL;
	}
}

?>
