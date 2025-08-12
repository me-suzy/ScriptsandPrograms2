<?php

/**
 * This class is to be applied as static class (:: syntax).
 * It manages the history files (wiki and uploaded). The history files have as
 * file extension the time stamp of seconds since 1970.
 * Older revisions of wiki pages still contain Wiki code, not HTML.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

class History {

	/**
	 * Places a copy of a data file in the history.
	 * Expects the base file name, no path, no extension.
	 * Returns true on success.
	 */
	function store( $fileName ) {
		return History::_store(DATA_PATH.$fileName.DATA_EXTENSION, $fileName);
	}

	/**
	 * Places a copy of an uploaded file in the history.
	 * Expects the base file name with extension, no path.
	 * Returns true on success.
	 */
	function storeUpload( $fileName ) {
		return History::_store( UPLOAD_PATH.$fileName, $fileName );
	}

	/** Helper function. Won't copy zero bytes files. */
	function _store( $realName, $historyName ) {
		if (file_exists($realName)) {
			if ((filesize($realName) == 0) or copy( $realName, HISTORY_PATH.$historyName.'.'.time())) {
				clearstatcache();
				return true;
			}
		}
		return false;
	}


	/**
	 * Returns a sorted 2D array of all history entries of
	 * a file. The array may be empty.
	 * The array structure:
	 * key = timestamp from filename
	 * value = data array with this structure:
	 *  name => real file name, no path, no extension
	 *  size => file size in byte
	 *  time => timestamp from filename
	 */
	function &getList( $fileName ) {
		$arrayRows = array();
		$arrayFileTime = array();
		$case = (preg_match('/win/i', php_uname('s')))?('i'):(''); //search case insensitive on Windows; fixes a lowercase bug caused by a search
		if ($dir = @opendir(HISTORY_PATH)) {
			while (($file = readdir($dir)) !== false) {
			    if ( preg_match( "/$fileName\\.([0-9]{10,})/$case", "$file", $arrayFileTime  ) == 1 ) {
			    	//deprecated: $time = filemtime(HISTORY_PATH.$file);
			    	$arrayRows[$arrayFileTime[1]] = array( 'name'=>$file, 'size'=>filesize(HISTORY_PATH.$file), 'time'=>$arrayFileTime[1]);
			    }
			}
			closedir($dir);
		}
		krsort($arrayRows); //reverse order: biggest date first
		return $arrayRows;
	}

	/**
	 * Renames all matching files, keeps the time stamp part of the file name.
	 */
	function renamePage( $src, $target ) {
		$arrFiles = History::getList( $src );
		foreach ( $arrFiles as $key => $file ) {
			rename( HISTORY_PATH.$file['name'], HISTORY_PATH.$target.'.'.$file['time'] );
		}
		clearstatcache();
	}

	/**
	 * Returns the file name with time stamp of the most recent
	 * history entry.
	 * @return E.g. "MyPage.1234567890" or an empty string if there is no history entry.
	 */
	function getNewestFileName( $fileName ) {
		$max =  ''; //may differ from $result on WinOS in rare occassions
		$result = '';
		$case = (preg_match('/win/i', @php_uname('s')))?('i'):('');
		if ($dir = @opendir(HISTORY_PATH)) {
			while (($file = readdir($dir)) !== false) {
				if ( preg_match( "/$fileName\\.[0-9]{10,}/$case", "$file"  ) == 1 ) {
					$normalizedName = (!empty($case))?(strtolower($file)):($file);
					if ($normalizedName > $max) {
						$max = $normalizedName;
						$result = $file; //do not alter case in result
					}
			    }
			}
			closedir($dir);
		}
		return $result;
	}

	/**
	 * Deletes all old revisions, the definition of 'old' is based
	 * upon a config entry.
	 * Returns the number of deleted files on success, false on error.
	 */
	function clearAll() {
		global $gConfig;

		//check if we keep all files for ever
		if ($gConfig->mProp['MinFileAge'] < 0) {
			return 0;
		}

		$counter = 0;
		if ($dir = @opendir(HISTORY_PATH)) {
		  $now = time();
		  while (($file = readdir($dir)) !== false) {
		  	if ( $file{0} != '.' ) { //new in 1.3.8; .htaccess and MacOSX files
			  	$time = filectime(HISTORY_PATH.$file);  //create time
			    if ( ($now - $time) > ($gConfig->mProp['MinFileAge'] * 86400)) {
			    	unlink( HISTORY_PATH.$file );
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
	 * Places a copy of a history file in the data directory.
	 * Expects the base file name, no path, no extension.
	 * $timeIndex marks exactly an existing file in the history directory.
	 * Stores the existing data file as a history file.
	 * Returns true on success.
	 */
	function reStore( $fileName, $timeIndex ) {
		return History::store( $fileName ) and History::_reStore( $fileName, DATA_PATH.$fileName.DATA_EXTENSION, HISTORY_PATH.$timeIndex );
	}

	/**
	 * Places a copy of a history file in the upload directory.
	 * Expects the base file name with extension, no path.
	 * $timeIndex marks exactly an existing file in the history directory.
	 * Stores the existing upload file as a history file.
	 * Returns true on success.
	 */
	function reStoreUpload( $fileName, $timeIndex ) {
		return History::storeUpload( $fileName ) and History::_reStore( $fileName, UPLOAD_PATH.$fileName, HISTORY_PATH.$timeIndex );
	}

	/**
	 * Helper for reStoreX().
	 */
	function _reStore( $fileName, $realName, $histName ) {
		global $gConfig;
		if (file_exists($histName) ) {
			if (copy( $histName, $realName )) {
				touch( $realName ); //re-activate is like editing
				clearstatcache();
				return true;
			}
		}
		return false;
	}

	/**
	 * Returns an history file in Wiki code or false if the
	 * requested file does not exist.
	 * Expects the base file name WITH extension BUT WITHOUT path.
	 */
	function retrieve( $fileName ) {
		$fileName = HISTORY_PATH.$fileName;
		if (file_exists( $fileName )) {
			$file = fopen( $fileName, 'r' );
			$text= fread( $file, filesize($fileName) );
			fclose( $file );
			return $text;
		} else {
			return false;
		}
	}

	/**
	 * Knows if currently a data exists. Used for link generation.
	 * Expects a file name WITH numeric index extension BUT WITHOUT path.
	 */
	function fileExists( $fileName ) {
		return file_exists( HISTORY_PATH.$fileName );
	}
}

?>