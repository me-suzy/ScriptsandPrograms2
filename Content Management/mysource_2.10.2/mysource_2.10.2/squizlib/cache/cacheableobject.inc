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
## $Source: /home/cvsroot/squizlib/cache/cacheableobject.inc,v $
## $Revision: 2.7 $
## $Author: csmith $
## $Date: 2003/05/26 05:48:24 $
#######################################################################
# This class assumes a global variable $CACHE has been established
# and is an object of the class "cache".
#---------------------------------------------------------------------#


 ##################################################################
# A special inherible class of objects which can cache themselves.
class CacheableObject extends Object {

	var $id=0; # Used to distinguish an object from others of its class
	var $cached = array();

	 #################
	# Check the cache
	function load_from_cache($id='') {
		if (empty($id)) $id = $this->id;
		global $CACHE;
		if(!$CACHE || get_parent_class($CACHE) != "cache") {
			$this->_set_error("Can't find global \$CACHE object!",__FILE__,__LINE__);
			return false;
		}
		if($object = $CACHE->read($id,get_class($this))) {
			if (get_class($object) == get_class($this)) {
				$this = $object;
				return true;
			} else {
				$this->_set_error("Attempt to load ".get_class($this)." ($id) yielded something weird from the cache:" .get_class($object),__FILE__,__LINE__);
				return false;
			}
		}
	}

	 ######################
	# Saves to the cache
	function save_to_cache($id="") {
		if (empty($id)) $id = $this->id;
		global $CACHE;
		if(!$CACHE || get_parent_class($CACHE) != 'cache') {
			$this->_set_error('Can\'t find global $CACHE object!',__FILE__,__LINE__);
			return false;
		}
		$this->cached[$id] = true;
		if($CACHE->write($this,$id,get_class($this))) {
			return true;
		}
		$this->cached[$id] = false;
		return false;
	}


	 ######################
	# Saves to the cache
	function clear_cache($id="") {
		if(empty($id)) $id = $this->id;
		if(isset($this->cached[$id]) && !$this->cached[$id]) return true;
		global $CACHE;
		if(!$CACHE || get_parent_class($CACHE) != "cache") {
			$this->_set_error("Can't find global \$CACHE object!",__FILE__,__LINE__);
			return false;
		}
		if($CACHE->clear($id,get_class($this))) {
			$this->cached[$id] = false;
			return true;
		}
		return false;
	}

	 ######################################
	# Returns the timestamp of the cache
	function get_cache_timestamp($id="") {
		if (empty($id)) $id = $this->id;
		global $CACHE;
		return $CACHE->timestamp($id, get_class($this));
	}

	function aquire_lock($id=0, $type='') {
		if (empty($id)) $id = $this->id;
		global $CACHE;
		return $CACHE->aquire_lock($id, get_class($this));
	}

	function release_lock($id=0, $type='') {
		if (empty($id)) $id = $this->id;
		global $CACHE;
		return $CACHE->release_lock($id, get_class($this));
	}

}

?>