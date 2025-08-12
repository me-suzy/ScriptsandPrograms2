<?php
/**
 * This class is to be applied as static class (:: syntax).
 * It manages the deleted files (wiki pages and uploaded files).
 * Deleting a file means to place it in the trash bin, leaving older
 * revisions in the history directory untouched. You can delete files
 * physically from trash and history if they are older than a few days
 * (configurable). This prevents an attack on a completely
 * open Wiki: The authors can restore the files.
 * Wiki pages in the trash still contain Wiki code, not HTML.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

class Trash {

	/**
	 * Places a  upload file in the trash.
	 * Expects the base file name with extension, no path.
	 * Returns true on success.
	 */
	function storeUpload( $fileName ) {
		return Trash::_store( UPLOAD_PATH.$fileName, $fileName );
	}

	/**
	 * Places a  data file in the trash. This operation outdates the cache.
	 * Expects the base file name, no path, no extension.
	 * Returns true on success.
	 */
	function store( $fileName ) {
		return Trash::_store( DATA_PATH.$fileName.DATA_EXTENSION, $fileName.DATA_EXTENSION );
	}

	/**
	 * Helper for storeX().
	 */
	function _store( $realName, $fileName ) {
		global $gConfig;
		//there is no 'move' in PHP 4.2?
		if (file_exists($realName)) {
			if (copy( $realName, TRASH_PATH.$fileName)) {
				unlink( $realName );
				clearstatcache();
				return true;
			}
		}
		return false;
	}


	/**
	 * Returns a 'trashed' file in Wiki code or false if the
	 * requested file does not exist.
	 * Expects the base file name without extension and path.
	 */
	function retrieve( $fileName ) {
		$fileName = TRASH_PATH.$fileName.DATA_EXTENSION;
		if (file_exists( $fileName ) ) {
			$file = fopen( $fileName, 'r' );
			$text= fread( $file, filesize($fileName) );
			fclose( $file );
			return $text;
		} else {
			return false;
		}
	}

	/**
	 * @deprectated Use getSeparatedLists() instead.
	 * Returns a sorted 2D array of all trashed files.
	 * The array structure:
	 * key = file name
	 * value = data array with this structure:
	 *  name => real file name, no path, data extension if data file
	 *  size => file size in byte
	 *  time => modification time in seconds since 1970
	 */
	function &getList() {
		$arrayRows = array();
		if ($dir = @opendir(TRASH_PATH)) {
			$fileName = '';
			while (($file = readdir($dir)) !== false) {
			    if ( $file{0} != '.' ) { //new in 1.3.8; .htaccess and MacOSX files
			    	$arrayRows[$file] = array( 'name'=>$file, 'size'=>filesize(TRASH_PATH.$file), 'time'=>filectime(TRASH_PATH.$file));
			    }
			}
			closedir($dir);
		}
		ksort($arrayRows);
		return $arrayRows;
	}

	/**
	 * Returns a list containing two arrays. The first one contains all Wiki pages
	 * from the trash bin, the second one all uploaded files.
	 * The array structure:
	 * key = file name
	 * value = data array with this structure:
	 *  name => real file name, no path, data extension if data file
	 *  size => file size in byte
	 *  time => modification time in seconds since 1970
	 *  (since 1.5.0)
	 */
	function getSeparatedLists() {
		$arrayPages  = array();
		$arrayUpload = array();
		if ($dir = @opendir(TRASH_PATH)) {
			$fileName = '';
			while (($file = readdir($dir)) !== false) {
			    if ( $file{0} != '.' ) { //new in 1.3.8; .htaccess and MacOSX files
			    	if (strpos($file, DATA_EXTENSION)) {
			    		$arrayPages[$file] = array( 'name'=>$file, 'size'=>filesize(TRASH_PATH.$file), 'time'=>filectime(TRASH_PATH.$file));
			    	} else {
			    		$arrayUpload[$file] = array( 'name'=>$file, 'size'=>filesize(TRASH_PATH.$file), 'time'=>filectime(TRASH_PATH.$file));
			    	}
			    }
			}
			closedir($dir);
		}
		ksort($arrayPages);
		ksort($arrayUpload);
		return array($arrayPages, $arrayUpload);
	}

	/**
	 * Deletes all 'old' trash files physically, the definition of 'old' is based
	 * upon a config entry. Newer trash files never get deleted.
	 * Returns the number of deleted files on success, false on error.
	 */
	function clearAll() {
		global $gConfig;

		//check if we keep all files for ever
		if ($gConfig->mProp['MinFileAge'] < 0) {
			return 0;
		}

		$counter = 0;
		if ($dir = @opendir(TRASH_PATH)) {
		  $now = time();
		  while (($file = readdir($dir)) !== false) {
		  	if ( $file{0} != '.' ) { //new in 1.3.8; .htaccess and MacOSX files
			  	$time = filectime(TRASH_PATH.$file);  //create time
			    if ( ($now - $time) > ($gConfig->mProp['MinFileAge'] * 86400)) {
			    	//files will be hidden in history until history gets cleared
			    	//no dependencies between History and Trash!
			    	//History::clear( $file );
			    	unlink( TRASH_PATH.$file );
			    	$counter++;
			    }
		    }
		  }
		  closedir($dir);
		  clearstatcache();
		  return $counter;
		}
		return false;
	}

	/**
	 * Places a trash file in the data directory.
	 * Expects the base file name, no path, no extension.
	 * Returns true on success.
	 */
	function reStore( $fileName ) {
		return Trash::_reStore( $fileName.DATA_EXTENSION, DATA_PATH.$fileName.DATA_EXTENSION);
	}

	/**
	 * Places a trash file in the upload directory.
	 * Expects the base file name withextension, no path.
	 * Returns true on success.
	 */
	function reStoreUpload( $fileName ) {
		return Trash::_reStore( $fileName, UPLOAD_PATH.$fileName);
	}

	/** Helper function. Copies from A to B. */
	function _reStore( $trashName, $realName ) {
		$trashName = TRASH_PATH.$trashName;
		if (file_exists($trashName)) {
			if (copy( $trashName, $realName )) {
				unlink( $trashName );
				clearstatcache();
				return true;
			}
		}
		return false;
	}

	/**
	 * Knows if currently a data exists. Used for link generation.
	 * Expects a file name without extension and path.
	 */
	function fileExists( $fileName ) {
		return file_exists( TRASH_PATH.$fileName );
	}
}

?>