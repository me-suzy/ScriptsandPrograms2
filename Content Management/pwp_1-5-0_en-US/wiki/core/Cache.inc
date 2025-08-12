<?php

/**
 * This class controls the cached files in the cache directory.
 * As of version 1.5.0 the cache is only in use for
 * lists. It might become extended to store search results as well...
 * (Caching Wiki pages did not increase the performance significantly.)
 * The cache is to be applied as static class (:: syntax).
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

define( 'INDEX_FILE_NAME', 'index.files.pwp' );
define( 'INDEX_PAGE_NAME', 'index.pages.pwp' );
define( 'SEARCH_PAGENAME', 'search.page.pwp.' ); //md5 to be appended
define( 'SEARCH_TEXT', 'search.txt.pwp.' );

class Cache {

	/**
	 * Stores a new file or overwrites an existing file in the
	 * cache. Expects the base file name without extension and path.
	 * $text contains the file contents as HTML, not Wiki code!
	 * Returns true on success.
	 */
	function store( $fileName, &$text, $append = '' ) {
		$fileName = CACHE_PATH.$fileName.DATA_EXTENSION;
		$file = fopen( $fileName, 'w+' );
		if ($file) {
			fwrite( $file, $text.$append );
			fclose( $file );
			clearstatcache();
			return true;
		}
		return false;
	}

	/**
	 * Fetches a file from the cache returns its contents.
	 * Returns false if the file is not in the cache.
	 * Expects the base file name without extension and path.
	 * The file will contain HTML, not Wiki code!
	 */
	function retrieve( $fileName ) {
		$fileName = CACHE_PATH.$fileName.DATA_EXTENSION;
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
	 * Erases the whole cache. Only files with the DATA_EXTENSION are
	 * affected. A call to this method will be necessary
	 * on creation of a file (the questionmark links in other files might
	 * become invalid) and upon delition of a file (we might need new
	 * questionmark links in other files).
	 * Returns true on success.
	 */
	function clearAll() {
		clearstatcache();
		if ($dir = @opendir(CACHE_PATH)) {
		  while (($file = readdir($dir)) !== false) {
		    if ( strpos($file, DATA_EXTENSION) ) {
		    	unlink( CACHE_PATH.$file );
		    }
		  }
		  closedir($dir);
		  clearstatcache();
		  return true;
		}
		return false;
	}

	/**
	 * Erases a single file from the cache. A call to this method will be necessary
	 * after editing (i.e. saving) a file.  Expects the base file name without
	 * extension and path.
	 */
	function clear( $fileName ) {
		$fileName = CACHE_PATH.$fileName.DATA_EXTENSION;
		clearstatcache();
		if (file_exists( $fileName )) {
			unlink( $fileName );
			clearstatcache();
		}
	}

	/**
	 * Erases several files from cache following the pattern name[1..n].extension.
	 * Required for removing the cached split list pages.
	 * Expects the base file name without
	 * extension and path.
	 */
	function clearNumerical( $fileName ) {
		$i = 1;
		clearstatcache();
		while (file_exists( CACHE_PATH.$fileName.$i.DATA_EXTENSION )) {
			unlink( CACHE_PATH.$fileName.$i.DATA_EXTENSION );
			clearstatcache(); //must be in loop; otherwise trouble under Linux
			++$i;
		}
	}

	/**
	 * Returns a user-readable cache age minutes / hours / days
	 */
	function getAge( $fileName ) {
		$fileName = CACHE_PATH.$fileName.DATA_EXTENSION;
		if (file_exists( $fileName )) {
			$time = time() - filemtime($fileName);
			if ($time > 86400) {
				return floor($time/86400). ' day(s)';
			} elseif ( $time > 3600 ) {
				return floor($time/3600). ' hour(s)';
			} else {
				return floor($time/60). ' minutes(s)';
			}
		} else {
			return '<span class="gray">(not in cache)</span>';
		}
	}
}

?>