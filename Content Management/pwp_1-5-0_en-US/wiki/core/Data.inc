<?php

/**
 * The Date class manages the creation of new pages, saving of changes,
 * the page index and the recent changes.
 * It is to be applied as static class (:: syntax).
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

class Data {

	/**
	 * Takes care to adjust various characters in an incomming string.
	 * Removes \r. (We want Unix file format.) Takes care of some HTML and entities.
	 * Used before saving and before forwarding to conflict.
	 */
	function preformatText( &$text ) {
		global $gConfig;
		//get rid of \r for reg exp (Can Mac cope with \n? It's OpenBSD underneath...)
		if (strpos($text, "\n") === FALSE) {
			//MAC?!
			$text = str_replace( "\r", "\n", $text );
		} else {
			//Windows has \r\n
			$text = str_replace( "\r", '', $text );
		}

		if ($gConfig->mProp['AllowedTags'] == '' ) {
			//htmlspecialchars would touch quotes, use str_replace
			$text = str_replace( array('& ', '>', '<', '"'), array('&amp; ', '&gt;', '&lt;', '&quot;'), $text);
		} else {
			//preserve < > in a mathematical meaning; would cause problems with strip_tags
			$text = str_replace( array('& ', ' > ', ' < ', '"'), array('&amp; ', ' &gt; ', ' &lt; ', '&quot;'), $text);
			$text = strip_tags( $text, $gConfig->mProp['AllowedTags'] );
		}
	}

	/**
	 * Returns a sorted 2D array of all data files.
	 * The array structure:
	 * key = file name
	 * value = data array with this structure:
	 *  name => real file name, no path, no extension
	 *  size => file size in byte
	 *  time => modification time in seconds since 1970
	 */
	function &getIndexList( ) {
		$arrayRows = array();
		if ($dir = @opendir(DATA_PATH)) {
			$fileName = '';
			while (($file = readdir($dir)) !== false) {
			    if ( $file{0} != '.' ) { //new in 1.3.8; .htaccess and MacOSX files
			    	$fileName = str_replace(DATA_EXTENSION, '', $file);
			    	$arrayRows[$file] = array( 'name'=>$fileName, 'size'=>filesize(DATA_PATH.$file), 'time'=>filemtime(DATA_PATH.$file));
			    }
			}
			closedir($dir);
		}
		ksort($arrayRows);
		return $arrayRows;
	}


	/**
	 * Returns a sorted 2D array of recently changed data files.
	 * Expects the $maxAge in days.
	 * The array structure:
	 * key = change time index
	 * value = data array with this structure:
	 *  name => real file name, no path, no extension
	 *  size => file size in byte
	 *  time => modification time in seconds since 1970
	 */
	function &getRecentChangesList( $maxAge ) {
		$oldestDate = time() - ($maxAge * 86400);
		$arrayRows = array();
		if ($dir = @opendir(DATA_PATH)) {
			$fileName = '';
			while (($file = readdir($dir)) !== false) {
			    if ( $file{0} != '.' ) { //new in 1.3.8; .htaccess and MacOSX files
			    	$time = filemtime(DATA_PATH.$file);
			    	if ( $time > $oldestDate ) {
			    		$fileName = str_replace(DATA_EXTENSION, '', $file);
			    		$arrayRows["$time.$fileName"] = array( 'name'=>$fileName, 'size'=>filesize(DATA_PATH.$file), 'time'=>$time);
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
		if ($dir = @opendir(DATA_PATH)) {
			$fileName = '';
			while (($file = readdir($dir)) !== false) {
			    if ( $file{0} != '.' ) { //new in 1.3.8; .htaccess and MacOSX files
			    	$fileName = str_replace(DATA_EXTENSION, '', $file);
			    	if ( stristr( $fileName, $search ) ) {
			    		$arrayRows[$fileName] = array( 'name'=>$fileName, 'size'=>filesize(DATA_PATH.$file), 'time'=>filemtime(DATA_PATH.$file));
			    	}
			    }
			}
			closedir($dir);
		}
		ksort($arrayRows);
		return $arrayRows;
	}

	/**
	 * Returns a sorted array of all data files
	 * which actually contain a search string. The string must not
	 * contain path elements or wildcards.
	 * $lazy restricts the search to the first 2KB of each file.
	 * Returns an empty array if nothing is found.
 	 * The array structure:
	 * key = file name without extension
	 * value = data array with this structure:
	 *  name => real file name, no path, no extension
	 *  size => file size in byte
	 *  time => modification time in seconds since 1970
	 */
	function &getTextSearchList( $search, $lazy = FALSE ) {
		$arrayRows = array();
		if ($dir = @opendir(DATA_PATH)) {
			$file     = 0;
			$size     = 0;
			$text     = '';
			$wikiName = '';
			while (($fileName = readdir($dir)) !== false) {
			    if ( $fileName{0} != '.' ) { //new in 1.3.8; .htaccess and MacOSX files
				    $size = ($lazy)?(2048):(filesize(DATA_PATH.$fileName));
				    if ($size > 0) {
						$file = fopen( DATA_PATH.$fileName, 'r' );
						$text = fread( $file, $size );
						fclose($file);
				    	if ( stristr( $text, $search ) !== FALSE ) {
				    		$wikiName = str_replace(DATA_EXTENSION, '', $fileName);
	    					$arrayRows[$wikiName] = array( 'name'=>$wikiName, 'size'=>filesize(DATA_PATH.$fileName), 'time'=>filemtime(DATA_PATH.$fileName));
				    	}
			    	}
			    }
			}
			closedir($dir);
		}
		ksort($arrayRows);
		return $arrayRows;
	}

	/**
	 * Returns an array of report settings. I.e. %%f1:f2:fN%%.
	 * Keys are the Wiki page names.
	 * The array values are further arrays containing the
	 * field values obtained by splitting the string on colons.
	 */
	function &getReportList( $name ) {
		$arrayRows = array();
		if ($dir = @opendir(DATA_PATH)) {
			while (($fileName = readdir($dir)) !== false) {
			    if ( $fileName{0} != '.' ) { //new in 1.3.8; .htaccess and MacOSX files
			    	$size = filesize(DATA_PATH.$fileName);
			    	if ($size > 0) {
						$file = fopen( DATA_PATH.$fileName, 'r' );
						$text = fread( $file, $size );
						fclose($file);
				    	if ( stristr( $text, "%%$name" ) !== FALSE ) {
				    		$wikiName = str_replace(DATA_EXTENSION, '', $fileName);
				    		$matches = array();
				    		if (preg_match("/%%($name.*?)%%/i", $text, $matches) > 0) {
	    						$arrayRows[$wikiName] = explode(':', $matches[1]);
	    					}
				    	}
			    	}
			    }
			}
			closedir($dir);
		}
		ksort($arrayRows);
		return $arrayRows;
	}

	/**
	 * Copies a data file
	 * Expects a file name without extension and path.
	 * Returns true on success.
	 */
	function copyPage( $src, $target ) {
		return copy( DATA_PATH.$src.DATA_EXTENSION, DATA_PATH.$target.DATA_EXTENSION );
	}

	/**
	 * Renames a data file
	 * Expects a file name without extension and path.
	 * Returns true on success.
	 */
	function renamePage( $src, $target ) {
		return rename( DATA_PATH.$src.DATA_EXTENSION, DATA_PATH.$target.DATA_EXTENSION );
	}

	/**
	 * Physically deletes a data file
	 * Expects a file name without extension and path.
	 * Returns true on success.
	 */
	function erasePage( $page ) {
		clearstatcache();
		$file = DATA_PATH.$page.DATA_EXTENSION;
		if (file_exists($file)) {
			return unlink( $file );
		} else {
			return TRUE; //job is done, nervertheless
		}
	}

	/**
	 * Creates a new data file.
	 * This operation outdates the whole cache.
	 * Expects a file name without extension and path.
	 * Returns true on success.
	 */
	function make( $fileName ) {
		$file = fopen( DATA_PATH.$fileName.DATA_EXTENSION, 'w+' );
		if ($file) {
			fclose($file);
			clearstatcache();
			return true;
		}
		return false;
	}

	/**
	 * Saves text into an existing data file.
	 * Expects a file name without extension and path.
	 * Returns true on success.
	 */
	function store( $fileName, &$text ) {
		$fullName = DATA_PATH.$fileName.DATA_EXTENSION;
		$file = fopen( $fullName, 'w+' );
		if ($file) {
			if (fwrite($file, $text) < 0) { // -1 on error
				fclose($file);
				clearstatcache();
				return false;
			} else {
				fclose($file);
				clearstatcache();
				return true;
			}
		}
		return false;
	}

	/**
	 * Returns a data file in Wiki code or false if the
	 * requested file does not exist.
	 * Expects the base file name without extension and path.
	 */
	function &retrieve( $fileName ) {
		$fileName = DATA_PATH.$fileName.DATA_EXTENSION;
		if (file_exists( $fileName )) {
			$text = '';
			if (filesize($fileName) > 0) {
				$file = fopen( $fileName, 'r' );
				$text = fread( $file, filesize($fileName) );
				fclose( $file );
			}
			return $text;
		} else {
			return false;
		}
	}

	/**
	 * Knows if currently a Wiki page exists. Used for link generation.
	 * Expects a file name without extension and path.
	 */
	function fileExists( $fileName ) {
		return file_exists( DATA_PATH.$fileName.DATA_EXTENSION );
	}

	/**
	 * Returns an info string with creation date, modified date, file size.
	 * Returns an empty string if the file does not exist.
	 * Expects a file name without extension and path.
	 */
	function getFileInfo( $fileName ) {
		global $gConfig;
		$fileName = DATA_PATH.$fileName.DATA_EXTENSION;
		$res = '';
		if (file_exists( $fileName )) {
			$now      = time();
			$created  = filectime( $fileName );
			$modified = filemtime( $fileName );
			$ageCreated  = (int)(($now - $created) / 86400);
			$ageModified = (int)(($now - $modified) / 86400);
			$res  = 'Created : '.date( $gConfig->mProp['DateFormat'], $created  )." (-$ageCreated days)<br>";
			$res .= 'Modified: '.date( $gConfig->mProp['DateFormat'], $modified )." (-$ageModified days)<br>";
			$res .= 'Size: '.(int)((filesize( $fileName ) + 511) /1024).' KB';
		}
		return $res;
	}

	/**
	 * Returns the file modification time in seconds since 1970.
	 * Returns 0 if the file does not exist.
	 * Expects a file name without extension and path.
	 */
	function getFileModTime( $fileName ) {
		$fileName = DATA_PATH.$fileName.DATA_EXTENSION;
		if (file_exists( $fileName )) {
			return filemtime( $fileName );
		}
		return 0;
	}

	/**
	 * Returns the file size in bytes.
	 * Returns 0 if the file does not exist
	 * Expects a file name without extension and path.
	 */
	function getFileSize( $fileName ) {
		$fileName = DATA_PATH.$fileName.DATA_EXTENSION;
		if (file_exists( $fileName )) {
			return filesize( $fileName );
		}
		return 0;
	}

	/**
	 * Returns TRUE if the argument is a valid file name without
	 * path and without special chars.
	 * IMPORTANT: Prevents from going upward a path like "../../etc/passwd"
	 */
	function isValidFileName( $name ) {
		//reg exp: no [] (Wiki links!), no file system chars (*/?!|\)
		return  !preg_match( "{[\\]\\[\\*\\s\\\/\\?!\\|<>\"']+}", $name ) and ($name{0} != '.') and ('' != $name);
	}
}
?>