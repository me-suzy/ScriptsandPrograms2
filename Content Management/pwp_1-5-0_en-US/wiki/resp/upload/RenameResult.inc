<?php
/**
 * This response renames an existing file
 * and forwards to view this file. Tt does take care
 * of the history entries.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Upload.inc' );
require_once( BASE_PATH.'core/Trash.inc' );
require_once( BASE_PATH.'core/History.inc' );
require_once( BASE_PATH.'core/Cache.inc' );

class RenameResult extends Response {

	function RenameResult( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gError;

		//source must exist
		if (empty($_REQUEST['iPage'])) {
			$gError->add("Missing parameter.");
		} else if (!Upload::fileExists($_REQUEST['iPage'])) {
			$gError->add("This file does NOT exists and cannot be renamed: {$_REQUEST['iPage']}");
		}

		//target has to obey the Wiki name rules and must not exist
		if (empty($_REQUEST['iNewFile'])) {
			$gError->add("Missing parameter.");
		} elseif (!Upload::isValidFileName($_REQUEST['iNewFile'])) {
			$gError->add("The submitted file name is empty or contains invalid characters. Do not use &lt; &gt; * | etc.");
		} else if (Upload::fileExists($_REQUEST['iNewFile'])) {
			$gError->add("This file does already exists and cannot be used as rename target: {$_REQUEST['iNewFile']}");
		} else if ( preg_match('/^.*\.('.SCRIPT_EXTENSIONS.')$/i', $_REQUEST['iNewFile']) ) {
			$gError->add("Cannot be renamed into an executable. This file name is invalid: {$_REQUEST['iNewFile']}");
		}
	}

	function service( &$buffer, &$args ) {
		global $gError;

		//cannot copy a file that still exists in trash:
		if (!Trash::fileExists( $_REQUEST['iNewFile'] )) {

			if (Upload::renamePage($_REQUEST['iPage'], $_REQUEST['iNewFile'])) {
				History::renamePage($_REQUEST['iPage'], $_REQUEST['iNewFile']);
				Cache::clearNumerical( INDEX_FILE_NAME );
				$this->changeState('upload/UploadList');
			} else {
				$gError->add("Could not rename file: {$_REQUEST['iPage']}");
			}

		} else {
			$gError->add("The file <strong>{$_REQUEST['iNewFile']}</strong> already exists was moved into the trash bin. Aborted rename operation.");
		}
	}

}

?>