<?php
/**
 * The Upload class is to be applied as static class (:: syntax).
 * It manages the uploaded files.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

/** RegExp or enumeration of script extensions. */
define( 'SCRIPT_EXTENSIONS', 'php\d*|pl|py|phtml|sh|bat|cmd|cgi|vb|jsp|asp');

class Upload {

	/**
	 * Returns a sorted 2D array of all uploaded files.
	 * The array structure:
	 * key = file name and extension
	 * value = data array with this structure:
	 *  name => file name and extension, no path
	 *  size => file size in byte
	 *  time => creation time in seconds since 1970
	 */
	function &getIndexList( ) {
		$arrayRows = array();
		if ($dir = @opendir(UPLOAD_PATH)) {
			while (($file = readdir($dir)) !== false) {
			    if ( $file{0} != '.' ) { //new in 1.3.8; .htaccess and MacOSX files
			    	$arrayRows[$file] = array( 'name'=>$file, 'size'=>filesize(UPLOAD_PATH.$file), 'time'=>filectime(UPLOAD_PATH.$file));
			    }
			}
			closedir($dir);
		}
		ksort($arrayRows);
		return $arrayRows;
	}

	/**
	 * Returns a sorted 2D array of recently changed files.
	 * Expects the $maxAge in days.
	 * The array structure:
	 * key = time stamp
	 * value = data array with this structure:
	 *  name => real file name, no path, no extension
	 *  size => file size in byte
	 *  time => modification time in seconds since 1970
	 */
	function &getRecentChangesList( $maxAge ) {
		$oldestDate = time() - ($maxAge * 86400);
		$arrayRows = array();
		if ($dir = @opendir(UPLOAD_PATH)) {
			$fileName = '';
			while (($file = readdir($dir)) !== false) {
			    if ( $file{0} != '.' ) { //new in 1.3.8; .htaccess and MacOSX files
			    	$time = filemtime(UPLOAD_PATH.$file);
			    	if ( $time > $oldestDate ) {
			    		$arrayRows["$time.$file"] = array( 'name'=>$file, 'size'=>filesize(UPLOAD_PATH.$file), 'time'=>$time);
			    	}
			    }
			}
			closedir($dir);
		}
		krsort( $arrayRows );
		return $arrayRows;
	}


	/**
	 * Returns a sorted array of all data files
	 * who's name matches a search string. The string must not
	 * contain path elements or wildcards.
	 * Returns an empty array if nothing is found.
 	 * The array structure:
	 * key = file name without extension
	 * value = data array with this structure:
	 *  name => real file name, no path, no extension
	 *  size => file size in byte
	 *  time => modification time in seconds since 1970
	 */
	function &getFileSearchList( $search ) {
		$arrayRows = array();
		if ($dir = @opendir(UPLOAD_PATH)) {
			while (($file = readdir($dir)) !== false) {
			    if ( $file{0} != '.' ) { //new in 1.3.8; .htaccess and MacOSX files
			    	if ( stristr( $file, $search ) ) {
			    		$arrayRows[$file] = array( 'name'=>$file, 'size'=>filesize(UPLOAD_PATH.$file), 'time'=>filemtime(UPLOAD_PATH.$file));
			    	}
			    }
			}
			closedir($dir);
		}
		ksort($arrayRows);
		return $arrayRows;
	}

	/**
	 * Returns a sorted array of all uploaded files
	 * which actually contain a search string. The string must not
	 * contain path elements or wildcards.
	 * Returns an empty array if nothing is found.
 	 * The array structure:
	 * key = file name without extension
	 * value = data array with this structure:
	 *  name => real file name, no path, no extension
	 *  size => file size in byte
	 *  time => modification time in seconds since 1970
	 */
	function &getTextSearchList( $search, $lazy = FALSE ) {
		global $gConfig;

		$arrayRows = array();
		if ($dir = @opendir(UPLOAD_PATH)) {
			$file     = 0;
			$size     = 0;
			$text     = '';
			while (($fileName = readdir($dir)) !== false) {
			    if ( $fileName{0} != '.' and strpos( ",{$gConfig->mProp['TextFileTypes']}", substr( $fileName, strrpos( $fileName, '.' ) ) ) ) {
				    $size = ($lazy)?(2048):(filesize(UPLOAD_PATH.$fileName));
					$file = fopen( UPLOAD_PATH.$fileName, 'r' );
					$text = fread( $file, $size );
					fclose($file);
			    	if ( stristr( $text, $search ) !== FALSE ) {
    					$arrayRows[$fileName] = array( 'name'=>$fileName, 'size'=>filesize(UPLOAD_PATH.$fileName), 'time'=>filemtime(UPLOAD_PATH.$fileName));
			    	}
			    }
			}
			closedir($dir);
		}
		ksort($arrayRows);
		return $arrayRows;
	}

	/**
	 * Copies a new uploaded file from its /tmp position.
	 * Expects the PHP temp file name  with path and
	 * a file name with extension and without path.
	 * Returns true on success.
	 */
	function make( $tempFileName, $fileName ) {
		if (file_exists($tempFileName)) {
			if (copy( $tempFileName, UPLOAD_PATH.$fileName )) {
				unlink( $tempFileName );
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
		return file_exists( UPLOAD_PATH.$fileName );
	}


	/**
	 * Returns the file ceation time in seconds since 1970.
	 * Returns 0 if the file does not exist
	 * Expects a file name without extension and path.
	 */
	function getFileCreatedTime( $fileName ) {
		$fileName = UPLOAD_PATH.$fileName;
		if (file_exists( $fileName )) {
			return filectime( $fileName );
		}
		return 0;
	}

	/**
	 * Returns the file size in bytes.
	 * Returns 0 if the file does not exist
	 * Expects a file name without extension and path.
	 */
	function getFileSize( $fileName ) {
		$fileName = UPLOAD_PATH.$fileName;
		if (file_exists( $fileName )) {
			return filesize( $fileName );
		}
		return 0;
	}

	/**
	 * Renames an uploaded file
	 * Expects a file name with extension but without path.
	 * Returns true on success.
	 */
	function renamePage( $src, $target ) {
		return rename( UPLOAD_PATH.$src, UPLOAD_PATH.$target );
	}

	/**
	 * Returns TRUE if the argument is a valid file name without
	 * path and without special chars.
	 * IMPORTANT: Prevents from going upward a path like "../../etc/passwd"
	 */
	function isValidFileName( $name ) {
		//reg exp: no [] (Wiki links!), no file system chars (*/?|\)
		return !preg_match( "{[\\]\\[\\*\\s\\\/\\?!\\|<>\"']+}", $name ) and ($name{0} != '.') and ('' != $name);
	}
}
?>