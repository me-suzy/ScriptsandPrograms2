<?php
/**
 * This response saves an uploaded file and forwards
 * to UploadList.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Upload.inc' );
require_once( BASE_PATH.'core/Trash.inc' );
require_once( BASE_PATH.'core/History.inc' );
require_once( BASE_PATH.'core/Cache.inc' );

class UploadSave extends Response {

	function UploadSave( &$buffer, &$args ) {
		$this->Response( $buffer, $args );
	}

	function verify( &$args ) {
		global $gError;

		//check for submitted file
		if (!isset($_FILES['iNewFile']['size']) or ($_FILES['iNewFile']['size'] < 1)) {
			$gError->add("File was not submitted or file has zero length.");
		} else if (strpos( $_FILES['iNewFile']['name'], DATA_EXTENSION )) {
			$gError->add("Cannot upload files having this as part of the name: ".DATA_EXTENSION);
		}

		//check for overwrite trigger
		if (!isset($_REQUEST['iOverwrite']) or $_REQUEST['iOverwrite'] != 1) {
			$_REQUEST['iOverwrite'] = 0;
		}
	}

	function service( &$buffer, &$args ) {
		global $gConfig, $gError;

		$newName = str_replace( ' ', '_', $_FILES['iNewFile']['name']); //no spaces please!
		if (preg_match('/^.*\.('.SCRIPT_EXTENSIONS.')$/i', $newName)) {
			$newName .= '.txt'; //script code becomes text (security)
		}
		if (!Upload::isValidFileName($newName)) {
			$gError->add("The file name '$newName' contains invalid characters. Please rename before uploading.");
		}

		//check file size limitation
		if (filesize($_FILES['iNewFile']['tmp_name'])>($gConfig->mProp['MaxUploadSize'] * 1024)) {
			unlink($_FILES['iNewFile']['tmp_name']);
			$gError->add("File is bigger than the allowed size of {$gConfig->mProp['MaxUploadSize']} KB.");
		}

		//move trashed file of same name into history
		//otherwise we get a conflict if the file gets deleted and is already in trash
		if ( Trash::fileExists($newName)) {
			if ($_REQUEST['iOverwrite'] == 1) {
				Trash::reStoreUpload( $newName );
			} else {
				$gError->add("File is still in trash: <b>$newName</b>. (Check overwrite radio button to ignore.)");
				return true;
			}
		}

		//check for already existing file, put it into history
		if ( Upload::fileExists($newName) ) {
			if ($_REQUEST['iOverwrite'] == 1) {
				History::storeUpload( $newName );
			} else {
				$gError->add("File already exists: <b>$newName</b>. (Check overwrite radio button to ignore.)");
				return true;
			}
		}

		//copy tmp file into upload dir
		if ( Upload::make( $_FILES['iNewFile']['tmp_name'], $newName ) ) {
			echo "<p>OK: File <b>$newName</b> has been uploaded successfully.</p>\n";
			Cache::clearNumerical( INDEX_FILE_NAME ); //cached list of files table
		} else {
			$gError->add("Cannot copy uploaded file from /tmp/* to <b>$newName</b>.");
		}
		$this->changeState('upload/UploadList');
	}
}

?>
