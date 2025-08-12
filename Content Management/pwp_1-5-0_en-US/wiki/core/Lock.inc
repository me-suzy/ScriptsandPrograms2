<?php

/**
 * The Lock class is to be applied as static class (:: syntax).
 * It adresses the locking functionality while a page is beeing edited.
 * Lock files also have the DATA_EXTENSION. These files must contain
 * a time stamp and the remote user name separated by a comma.
 * Each lock file corresponds to a data file (Wiki page) but the
 * existence of a lock file is not required. A time index of 0 means
 * unlocked; other time stamps mark the moment the lock was set.
 *
 * @author Lars Ackermann
 * @status Part of PWP Wiki Processor, licensed under GPL.
 * $Id: $
 */
class Lock {

	/**
	 * Erases all lock files. Only files with the DATA_EXTENSION are
	 * affected. A call to this method is usually not necessary.
	 * Returns true on success.
	 */
	function clearAll() {
		if ($dir = @opendir(LOCK_PATH)) {
		  while (($file = readdir($dir)) !== false) {
		    if ( strpos($file, DATA_EXTENSION) ) {
		    	unlink( LOCK_PATH.$file );
		    }
		  }
		  closedir($dir);
		  clearstatcache();
		  return true;
		}
		return false;
	}

	/**
	 * Deletes a single lock file physically (on move to trash).
	 * Expects a file name without extension and path.
	 * Returns true on success.
	 */
	function clear( $fileName ) {
		if (file_exists( LOCK_PATH.$fileName.DATA_EXTENSION )) {
			clearstatcache();
			return unlink( LOCK_PATH.$fileName.DATA_EXTENSION );
		}
		return true;
	}

	/**
	 * Creates a new lock on a data file. The file must not be
	 * locked at the moment.
	 * Expects a file name without extension and path.
	 * Returns true on success.
	 */
	function create( $fileName ) {
		global $gConfig;

		if (!$gConfig->mProp['LockForEdit'] ) {
			return true; //locking disabled
		}

		$file = fopen( LOCK_PATH.$fileName.DATA_EXTENSION, 'w+' );
		if ($file) {
			fwrite($file, (time()).','.$_SERVER['REMOTE_USER']);
			fclose($file);
			clearstatcache();
			return true;
		}
		return false;
	}

	/**
	 * Unlocks a single data file.
	 * Expects a file name without extension and path.
	 * Returns true on success.
	 */
	function release( $fileName ) {
		global $gConfig;

		if (!$gConfig->mProp['LockForEdit'] ) {
			return true; //locking disabled
		}

		if (file_exists( LOCK_PATH.$fileName.DATA_EXTENSION )) {
			$file = fopen( LOCK_PATH.$fileName.DATA_EXTENSION, 'w' );
			if ($file) {
				fwrite($file, '0,'.$_SERVER['REMOTE_USER']);
				fclose($file);
				clearstatcache();
			} else {
				return false; //unknown error
			}
		}
		return true;
	}

	/**
	 * Returns true if a file is locked for editing.
	 * The lock duration in seconds and the
	 * locking user can be found in the passed arguments.
	 */
	function isLocked( $fileName, &$tstamp, &$user ) {
		global $gConfig;

		if (!$gConfig->mProp['LockForEdit'] ) {
			return false; //locking disabled
		}

		$fullName = LOCK_PATH.$fileName.DATA_EXTENSION;
		if (file_exists( $fullName )) {
			$file = fopen( $fullName, 'r' );
			if ($file) {
				list($tstamp, $user) = explode(',', fread($file, filesize($fullName)) );
				fclose($file);
				if ($tstamp == 0) {
					return false;
				} else {
					$tstamp = time() - $tstamp;
					return true;
				}
			}
		}
		return false;
	}
}
?>