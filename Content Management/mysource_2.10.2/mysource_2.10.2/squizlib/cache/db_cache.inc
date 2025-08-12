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
## File: cache/db_cache.inc
## Desc: A class that manages caching objects using a database.
## $Source: /home/cvsroot/squizlib/cache/db_cache.inc,v $
## $Revision: 2.3 $
## $Author: csmith $
## $Date: 2003/05/26 05:48:24 $
#######################################################################
# Requires object from squizlib
require_once(dirname(__FILE__)."/cache.inc");
global $SQUIZLIB_PATH;
require_once("$SQUIZLIB_PATH/db/db.inc");
#---------------------------------------------------------------------#

/*
DROP TABLE cache;
CREATE TABLE cache (
	id    VARCHAR(127) NOT NULL,
	type  VARCHAR(127) NOT NULL,
	data  LONGTEXT     NOT NULL,
	mtime TIMESTAMP    NOT NULL,
	locked CHAR(1)		NOT NULL,
	primary key (id, type)
);
INSERT INTO cache VALUES('1','object','blah',now());
SELECT * FROM cache;
*/

class Db_Cache extends Cache {
	
	 #####################
	# Variables
	var $db = '';
	var $table_name = '';

	
	 ##############
	# constructor:
	function Db_Cache($n='',$l = 'root',$p = '',$h = 'localhost',$t = 'cache') {
		$this->db = new Db($n,$l,$p,$h);
		$this->table_name = $t;
	}	

	 ##########################################
	# Wipes out the entrie cache, clean slate
	function wipe() {
		return $this->db->delete("DELETE FROM $this->table_name");
	}
	
	 #############################
	# Write an object to the cache
	function write(&$object, $id='', $type='') {
		if (!$object || !$id || !$type) return false;
		if(!Cache::write($object,$id,$type)) return false;
		$this->db->replace("REPLACE INTO $this->table_name (id, type, data, mtime) VALUES ('$id', '$type', '".addslashes(serialize($object))."',now())");
		return true;
	}

	 ###############################
	# Read an object from the cache
	function read($id='', $type='') {
		if (!$id || !$type) return false;
		if(!Cache::read($id,$type)) return false;
		$string = $this->db->single_element("SELECT data FROM $this->table_name WHERE id='$id' AND type='$type'");
		if ($string) return unserialize($string);
		return false;
	}


	 ###########################################
	# Removes an object from the cache
	function clear($id='', $type='') {
		if (!$id || !$type) return false;
		if(!Cache::clear($id,$type)) return false;
		return $this->db->delete("DELETE FROM $this->table_name WHERE id='$id' AND type='$type'");
	}

	 #############################
	# Does the cache entry exist?
	function exists($id='', $type='') {
		if (!$id || !$type) return false;
		if(!Cache::exists($id,$type)) return false;
		return (int) $this->db->single_element("SELECT count(id) FROM $this->table_name WHERE id='$id' AND type='$type'");
	}

	 ###########################################################
	# Returns the timestamp the last time the cache was updated
	function timestamp($id='', $type='') {
		if (!$id || !$type) return false;
		if(!Cache::timestamp($id,$type)) return false;
		return (int) $this->db->single_element("SELECT UNIX_TIMESTAMP(mtime) FROM $this->table_name WHERE id='$id' AND type='$type'");
	}

	function aquire_lock($id=0, $type='') {
		$result = $this->db->single_element("SELECT locked FROM $this->table_name WHERE id='$id' AND type='$type'");
		if (!$result) return false;
		if ($result['locked']) return false;
		return (int) $this->db->INSERT("UPDATE $this->table_name SET locked='1' WHERE id='$id' AND type='$type'");
	}

	function release_lock($id=0, $type='page') {
		$result = $this->db->single_element("SELECT locked FROM $this->table_name WHERE id='$id' AND type='$type'");
		if (!$result) return false;
		if ($result['locked']) return false;
		return (int) $this->db->INSERT("UPDATE $this->table_name SET locked='0' WHERE id='$id' AND type='$type'");
	}

}

?>