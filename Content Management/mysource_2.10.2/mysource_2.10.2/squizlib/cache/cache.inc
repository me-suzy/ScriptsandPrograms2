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
## $Source: /home/cvsroot/squizlib/cache/cache.inc,v $
## $Revision: 2.5 $
## $Author: csmith $
## $Date: 2003/06/10 00:06:10 $
#######################################################################
# Requires object from squizlib
include_once("$SQUIZLIB_PATH/object/object.inc");
require_once(dirname(__FILE__)."/cacheableobject.inc");
#---------------------------------------------------------------------#

 #################
# To be inherited
class Cache extends Object {
	
	 ##############
	# constructor:
	function Cache() {
	}	


	 ##########################################
	# Wipes out the entrie cache, clean slate
	function wipe() {
		return true;
	}

	function aquire_lock($id=0, $type='page') {
		if (!$id || !$type) return false;
	}

	function release_lock($id=0, $type='page') {
		if (!$id || !$type) return false;
	}


	 #############################
	# Write an object to the cache
	function write(&$object, $id, $type) {
		if (!$id || !$type) return false;
		# Don't check object - we could be writing the site_page_index which is definitely not an object.
		if (!isset($id)) {
			$this->_set_error("Attempt to cache without a valid ID, Type: $type",__FILE__,__LINE__);
			return false;
		} elseif (!$type) {
			$this->_set_error("Attempt to cache without giving a data type, ID: $id",__FILE__,__LINE__);
			return false;
		} else return true;
	}

	 ###############################
	# Read an object from the cache
	function read($id, $type) {
		if (!$id || !$type) return false;
		if (!isset($id)) {
			$this->_set_error("Unable to read from cache, no ID supplied, Type: $type",__FILE__,__LINE__);
			return false;
		} elseif (!$type) {
			$this->_set_error("Unable to read from cache, no data type supplied, ID: $id",__FILE__,__LINE__);
			return false;
		} else return true;
	}


	 ###########################################
	# Removes an object from the cache
	function clear($id, $type) {
		if (!$id || !$type) return false;
		if (!isset($id)) {
			$this->_set_error("Unable to clear cache entry, no ID supplied, Type: $type",__FILE__,__LINE__);
			return false;
		} elseif (!$type) {
			$this->_set_error("Unable to clear cache entry, no data type supplied, ID: $id",__FILE__,__LINE__);
			return false;
		} else return true;
	}

	 ############################
	# Does the cache entry exist?
	function exists($id,$type) {
		if (!$id || !$type) return false;
		if (!isset($id)) {
			$this->_set_error("Unable to check cache entry existance, no ID supplied, Type: $type",__FILE__,__LINE__);
			return false;
		} elseif (!$type) {
			$this->_set_error("Unable to check cache entry existance, no data type supplied, ID: $id",__FILE__,__LINE__);
			return false;
		} else return true;
	}
	
	 ###########################################################
	# Returns the timestamp the last time the cache was updated
	function timestamp($id,$type) {
		if (!$id || !$type) return false;
		if (!isset($id)) {
			$this->_set_error("Unable to get cache entry timestamp, no ID supplied, Type: $type",__FILE__,__LINE__);
			return false;
		} elseif (!$type) {
			$this->_set_error("Unable to get cache entry timestamp, no data type supplied, ID: $id",__FILE__,__LINE__);
			return false;
		} else return true;
	}

	 ###########################################################
	# Returns the age, in seconds, of a particular cache file
	function age($id,$type) {
		if (!$id || !$type) return false;
		return time() - $this->timestamp($id,$type);
	}

	 ################################################################
	# Takes a timestamps and answers "Is the cache older than this?"
	function older($id,$type,$time) {
		if (!$id || !$type || !$time) return false;
		return ($time >= $this->timestamp($id,$type));
	}

}


?>
