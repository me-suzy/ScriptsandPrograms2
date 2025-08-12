<?php
/**
 * This response shows a list of all uploaded files (like Index
 * for wiki pages). Also provides an upload form.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

require_once( BASE_PATH.'core/Upload.inc' );
require_once( BASE_PATH.'core/Cache.inc' );
require_once( BASE_PATH.'core/Lists.inc' );

class UploadList extends Response {

	function UploadList( &$buffer, &$args ) {
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


		echo $buffer;
		$this->_printForm();

		$this->changeState('out/Html');
		$args['Heading'] = "Index of all uploaded files";

		//try to fetch the file from cache
		$text =& Cache::retrieve( INDEX_FILE_NAME.$_REQUEST['iFileListPart'] );
		if ($text !== FALSE) { //type equal
			echo $text;
			return true;
		}

		//ok we failed, build the index file
		$text = Lists::getIndex( UPLOAD::getIndexList(), LIST_UPLOAD );
		Cache::store( INDEX_FILE_NAME.$_REQUEST['iFileListPart'], $text, ' <small>(cached)</small>' );
		echo $text;
	}


	/** Helper: The upload form. */
	function _printForm() {
		global $gConfig;
		$readableLength = number_format( $gConfig->mProp['MaxUploadSize'], 0, ',', '.');
		$size = $gConfig->mProp['MaxUploadSize'] * 1024;
		$now = time();
		echo <<<eoh
<form action='{$_SERVER['PHP_SELF']}' method='post' enctype='multipart/form-data'>
	<input type='hidden' name='iRequest' value='upload/UploadSave'>
	<b>Upload a file:</b>
	<input type='file' name='iNewFile' size='27' maxlength='$size'>
	<input type='hidden' name='iAction' value='upload'>
	<input type='submit' value='OK'>
	<br>
	<input type='radio' name='iOverwrite' value='0' checked> Cancel if file already exists.
	<br>
	<input type='radio' name='iOverwrite' value='1' > Overwrite existing file, save old revison.
	<br>
	Max. file size: $readableLength KB
</form>
eoh;
	}
}

?>
