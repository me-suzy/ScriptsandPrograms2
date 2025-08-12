<?php

/**
 * This class is to be applied as static class (:: syntax).
 * It controls the static files, which are complete HTML files.
 * The static files will be
 * created and deleted all together. At this point we are in danger
 * of exceeding the script timeout of 30s configured by web providers.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */

class Statics {

	/**
	 * Returns a menu with actions for exporting static pages.
	 */
	function getMenu() {
		$staticPath = STATIC_PATH;
		return <<<eoh
<ul>
<li> Clear exported Wiki pages:
[<a href='{$_SERVER['PHP_SELF']}?iRequest=static/ClearStatic&amp;iType=pages'>ALL</a>] or
[<a href='{$_SERVER['PHP_SELF']}?iRequest=static/ClearStatic&amp;iMode=outdated&amp;iType=pages'>Only outdated</a>]
</li><li> Clear exported uploaded files:
[<a href='{$_SERVER['PHP_SELF']}?iRequest=static/ClearStatic&amp;iType=files'>ALL</a>] or
[<a href='{$_SERVER['PHP_SELF']}?iRequest=static/ClearStatic&amp;iMode=outdated&amp;iType=files'>Only outdated</a>]
</li><li>Export:
[<a href='{$_SERVER['PHP_SELF']}?iRequest=static/ExportPages'>Wiki pages</a>] and
[<a href='{$_SERVER['PHP_SELF']}?iRequest=static/ExportFiles'>Uploaded files</a>]
</li><li> View:
[<a href='$staticPath'>Goto exported pages</a>]
</li>
</ul>
eoh;
	}

	/**
	 * Erases all static files. Only files with the STATIC_EXTENSION,
	 * which is most likly '.html', are affected.
	 * Returns true on success.
	 */
	function clearAll() {
		if ($dir = @opendir(STATIC_PATH)) {
		  while (($file = readdir($dir)) !== false) {
		    if ( strpos($file, STATIC_EXTENSION) ) {
		    	unlink( STATIC_PATH.$file );
		    }
		  }
		  closedir($dir);
		  return true;
		}
		return false;
	}

	/**
	 * Erases old static files. Only files with the STATIC_EXTENSION,
	 * which is most likly '.html', are affected.
	 * Compares the time stamp of a static page to the time stamp of the
	 * corresponding Wiki page.
	 * Returns an arry with names of deleted pages.
	 */
	function clearOld() {
		$res        = array();
		$pwpTime    = 0;
		$staticTime = 0;

		if ($dir = @opendir(STATIC_PATH)) {
		  while (($file = readdir($dir)) !== false) {
		    if ( strpos($file, STATIC_EXTENSION) ) {
   			  	$pwpTime    = Data::getFileModTime( str_replace( STATIC_EXTENSION, '', $file ) );
			  	$staticTime = filemtime( STATIC_PATH.$file );
			  	if ( $staticTime < $pwpTime ) {
			    	unlink( STATIC_PATH.$file );
					$res[] = $file.'(ok)';
				}
		    }
		  }
		  closedir($dir);
		  sort( $res );
		}
		return $res;
	}

	/**
	 * Erases all uploaded files in the static upload dir.
	 * Returns true on success.
	 */
	function clearAllUploaded() {
		if ($dir = @opendir(STATIC_PATH.UPLOAD_PATH)) {
		  while (($file = readdir($dir)) !== false) {
		    if ( $file{0} != '.' ) { //new in 1.3.8; .htaccess and MacOSX files
		    	unlink( STATIC_PATH.UPLOAD_PATH.$file );
		    }
		  }
		  closedir($dir);
		  return true;
		}
		return false;
	}

	/**
	 * Erases outdated uploaded files in the static upload dir.
	 * Compares the time stamp to the time stamp from the "dynamic"
	 * upload file.
	 * Returns an arry with names of deleted files.
	 */
	function clearOldUploaded() {
		$res        = array();
		$pwpTime    = 0;
		$staticTime = 0;
		if ($dir = @opendir(STATIC_PATH.UPLOAD_PATH)) {
		  while (($file = readdir($dir)) !== false) {
		    if ( $file{0} != '.' ) { //new in 1.3.8; .htaccess and MacOSX files
    		  	$pwpTime    = Upload::getFileCreatedTime( $file );
			  	$staticTime = filemtime( STATIC_PATH.UPLOAD_PATH.$file );
				if ($staticTime < $pwpTime) {
		    		unlink( STATIC_PATH.UPLOAD_PATH.$file );
		    		$res[] = $file.'(ok)';
		    	}
		    }
		  }
		  closedir($dir);
		  sort( $res );
		}
		return $res;
	}

	/**
	 * Stores all files in the UPLOAD_PATH in the STATIC_PATH.UPLOAD_PATH
	 * if they don't already exist. Skipps existing files.
	 * Returns an array with the names of the uploaded pages stored in static.
	 */
	function &storeAllUploaded() {
		if ($dir = @opendir(UPLOAD_PATH)) {
		  $res = array();
		  while (($file = readdir($dir)) !== false) {
		    if ( $file{0} != '.' and !file_exists( STATIC_PATH.UPLOAD_PATH.$file ) ) {
		    	copy( UPLOAD_PATH.$file, STATIC_PATH.UPLOAD_PATH.$file );
		    	$res[] = $file.'(ok)';
		    }
		  }
		  closedir($dir);
		  sort( $res );
		}
		return $res;
	}


	/**
	 * Stores a new static file or overwrites an existing file
	 * Expects the base file name without extension and path.
	 * $text contains the file contents as HTML with HEAD and BODY,
	 * not Wiki code!
	 * Returns true on success.
	 */
	function store( $fileName, &$text ) {
		$fileName = STATIC_PATH.$fileName.STATIC_EXTENSION;
		$file = fopen( $fileName, 'w+' );
		if ($file) {
			$length = fwrite( $file, $text );
			fclose( $file );
			if ($length != strlen( $text )) {
				unlink( $fileName );
				return false;
			}
			return true;
		}
		return false;
	}

	/**
	 * Knows if currently a static exists. Used for link generation.
	 * Expects a file name without extension and path.
	 */
	function fileExists( $fileName ) {
		return file_exists( STATIC_PATH.$fileName.STATIC_EXTENSION );
	}
}

?>