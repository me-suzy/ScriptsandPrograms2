<?  ##############################################
   ### SquizLib ------------------------------###
  ##- Include Files ------ PHP4 --------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## File: cache/cache.inc
## Desc: A class that manages caching objects.
## $Source: /home/cvsroot/squizlib/cache/file_cache.inc,v $
## $Revision: 2.10 $
## $Author: achadszinow $
## $Date: 2003/06/10 23:12:53 $
#######################################################################
require_once(dirname(__FILE__)."/cache.inc");
define('SQUIZLIB_NUM_CACHE_DIRS', 20);
#---------------------------------------------------------------------#

class File_Cache extends Cache {
	
	var $dir = 'cache';
	
	 ##############
	# constructor:
	function File_Cache($dir='cache') {
		if (!file_exists($dir) || !is_dir($dir)) {
			$this->_set_error("Cache directory ($dir) does not exist!",__FILE__,__LINE__);
			return false;
		}
		if (!file_exists("$dir/0/squizlib_file_cache.checked_directories")) {
			$ou = umask(0);
			foreach (range(0, SQUIZLIB_NUM_CACHE_DIRS) as $hash_dir) {
				if (!is_dir("$dir/$hash_dir")) {
					if (!mkdir("$dir/$hash_dir",0775)) {
						$this->_set_error("Cache directory ($dir/$hash_dir) does not exist and can't be created!",__FILE__,__LINE__);
						return false;
					}
				}
			}
			touch("$dir/0/squizlib_file_cache.checked_directories");
			chmod("$dir/0/squizlib_file_cache.checked_directories",0664);
			umask($uo);
		}
		$this->dir = $dir;
		return true;
	}	

	 ##########################################
	# Wipes out the entrie cache, clean slate
	function wipe() {
		foreach (range(0,SQUIZLIB_NUM_CACHE_DIRS) as $hash_dir) {
			if ($dir = @opendir("$this->dir/$hash_dir")) {
				while (($file = readdir($dir)) !== false) {
					if ($file == '.' || $file == '..') continue;
					if (strstr($file, '.')) {
						unlink("$this->dir/$hash_dir/$file");
					}
				}
				closedir($dir);
			}
		}
	}

	function aquire_lock($id=0, $type='page') {
		if (!$id || !$type) return false;
		$lock_file = $this->dir.'/0/lock.'.$id.'.'.$type;
		if (!is_file($lock_file) && touch($lock_file)) return true;

		if (is_file($lock_file)) {
			if (date("U") - filemtime($lock_file) > 300) {
				$this->_set_error("Lock file ($lock_file) has been around for over 300 seconds. Removing", __FILE__, __LINE__);
				unlink($lock_file);
			}
		}

		return false;
	}

	function release_lock($id=0, $type='page') {
		if (!$id || !$type) return false;
		$lock_file = $this->dir.'/0/lock.'.$id.'.'.$type;
		if (is_file($lock_file) && unlink($lock_file)) return true;
		return false;
	}

	 #############################
	# Write an object to the cache
	function write($object, $id, $type) {
		if (!$id || !$type) return false;
		if(!Cache::write($object,$id,$type)) return false;
		array_contents($object, 2);
		$str  = serialize($object);
		if (!string_to_file($str,$this->get_filename($id,$type))) {
			$this->_set_error("Unable to open cache file $this->dir/$id.$type for writing. Could not obtain cache-writing lock.",__FILE__,__LINE__);
			return false;
		}
		return true;
	}

	 ###############################
	# Read an object from the cache
	function read($id="", $type="") {
		if (!$id || !$type) return false;
		if(!Cache::read($id,$type)) return false;
		if(!$this->exists($id,$type)) return false;
		if (!$f = fopen($this->get_filename($id, $type),"r")) {
			$this->_set_error("Unable to open cache file $this->dir/$id.$type for reading.",__FILE__,__LINE__);
			return false;
		}
		$string = fread($f,filesize($this->get_filename($id, $type)));
		fclose($f);
		$msg = unserialize($string);
		return $msg;
	}


	 ###########################################
	# Removes an object from the cache
	function clear($id="", $type="") {
		if (!$id || !$type) return false;
		if(!Cache::clear($id,$type)) return false;
		if ($this->exists($id, $type)) {
			$msg = unlink($this->get_filename($id,$type));
			return $msg;
		}
		return true;
	}

	 #############################
	# Does the cache entry exist?
	function exists($id="",$type="") {
		if (!$id || !$type) return false;
		if(!Cache::exists($id,$type)) return false;
		$msg = file_exists($this->get_filename($id,$type));
		return $msg;
	}

	 ###########################################################
	# Returns the timestamp the last time the cache was updated
	function timestamp($id="",$type="") {
		if (!$id || !$type) return false;
		if(!$this->exists($id,$type)) return false;
		if(!Cache::timestamp($id,$type)) return false;
		$msg = (int) filemtime($this->get_filename($id, $type));
		return $msg;
	}

	###################################################################

	 ###############################################################################
	# Given an id and a type, returns the full path and filename whre itll be saved
	function get_filename($id="", $type="") {
		if (!$id || !$type) return false;
		$hash_dir = $this->sum_digits($id);
		return "$this->dir/$hash_dir/$id.$type";
	}
	 ###################################################################################################
	# Keeps adding all the digits in a number until the number is smaller than SQUIZLIB_NUM_CACHE_DIRS 
	function sum_digits($num=0) {
		# If num is a string, give it a value of 0
		$num = trim($num);
		if ((int) $num != $num) return 0;

		do {
			$sum = 0; # the running total of digits for THIS RUN
			$len = strlen($num);
			for ($i = 0; $i < $len; $i++) {
				$sum += $num[$i];
			}
			# cast sum to string so we can loop over its digits next time if we have to
			$num = "$sum";
		} while ($sum > SQUIZLIB_NUM_CACHE_DIRS);
		return $sum;
	}

}


?>
