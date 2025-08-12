<?php
/** @file SSMod.class.php
 *	Copyright (C) 2004  Karim Shehadeh
 *	
 *	This program is free software; you can redistribute it and/or
 *	modify it under the terms of the GNU General Public License
 *	as published by the Free Software Foundation; either version 2
 *	of the License, or (at your option) any later version.
 *	
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *	
 *	You should have received a copy of the GNU General Public License
 *	along with this program; if not, write to the Free Software
 *	Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *	@version 0.1
 *	@date October, 2003	
 */

/**  Represents a single modification to the database
	Every time a scene, story or fork is edited, a new 
	modification record is added to the database.  It helps
	trace a story's history.
*/
class SSMod extends SSTableObject
{
	/** Constructor: Adds required properties*/
	function SSMod () {
		parent::SSTableObject ();
	}	
	
	/** Adds all associated properties
     *	This need only be called once per instantiation of this class
     *	and is handled automatically by the base class as long
     *	as its constructor is called.		
	*/
	function _addProperties () {
		$this->_addProperty (PROP_ID, 0);
		$this->_addProperty ('target_type', '');
		$this->_addProperty ('target_id', '');
		$this->_addProperty (PROP_STORY_ID, '');
		$this->_addProperty (PROP_USERNAME, '');
		$this->_addProperty ('mod_date', 0);
		$this->_addProperty ('mod_ip', 0);
		$this->_addProperty ('action', 0);
		$this->_addProperty ('mod_data', 0);
		$this->_addProperty ('client_info', '');
	}
	
	/**  Gets all the database field names with associated values
     *	This will return an associative array where the keys
     *	are the table field names and the values are the values
     *	stored in this class.
     *	@param bool $includeDBKey If true, then the key field for the table is returned in the array as well.
     *	@return array An associative array of dbase fields => class values
	*/
	function getDBKeyValueArray ($includeDBKey) {
	
		$tableConstant = $this->_getTableConstant();
		
		$fields = array
				(
					$GLOBALS[$tableConstant]['fields']['TARGET_TYPE'] => $this->get ('target_type'),
					$GLOBALS[$tableConstant]['fields']['TARGET_ID'] => $this->get ('target_id'),
					$GLOBALS[$tableConstant]['fields']['STORY_ID'] => $this->get (PROP_STORY_ID),
					$GLOBALS[$tableConstant]['fields']['USER_ID'] => $this->get (PROP_USERNAME),
					$GLOBALS[$tableConstant]['fields']['MOD_DATE'] => $this->get ('mod_date'),
					$GLOBALS[$tableConstant]['fields']['MOD_IP'] => $this->get ('mod_ip'),
					$GLOBALS[$tableConstant]['fields']['ACTION'] => $this->get ('action'),
					$GLOBALS[$tableConstant]['fields']['MOD_DATA'] => $this->get ('mod_data'),
					$GLOBALS[$tableConstant]['fields']['CLIENT_INFO'] => $this->get ('client_info'),
				);
				
		if ($includeDBKey) {
			$fields [$GLOBALS[$tableConstant]['fields']['ID']] = $this->get (PROP_ID);
		}
		
		return $fields;
	}
	
	/**  Gets the associative array required to mark a record as deleted
     *	@param bool $checkKey If this is true, then the unique key field for the object is checked for validity as well
     *	@return array An associative (key>value) array populated with the keys/values required to mark the object as deleted
	*/
	function _getDBKeyValueForDelete () {
			return array ();
	}
	
	/**  Gets the key string for the associative array that contains field information for the table associated with this object.
     *	@see tables.inc.php
     *	@return string The key field string for the assocative array that contains field information for the table associated with this object.
	*/
	function _getTableConstant () {
		return 'TABLE_MOD';
	}
	
	/**  Checks if the data stored in this object is valid.
     *	All this does is verify that there is valid data in the *required*
     *	fields.
     *	@param bool $checkKey If this is true, then the unique key field for the object is checked for validity as well
     *	@return bool True, if all required fields are valid.
	*/
	function requiredFieldsValid ($checkKey = false) {
	
		$invalidField = false;
		
		if ($this->get('target_type') == '') {
			$this->addError (STR_101, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get('target_id') == '') {
			$this->addError (STR_102, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get (PROP_USERNAME) == '') {
			$this->addError (STR_103, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get('mod_date') == '') {
			$this->addError (STR_104, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get('action') == '') {
			$this->addError (STR_105, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}		
		
		if ($checkKey) {
			if ($this->get (PROP_ID) == '') {
				$this->addError (STR_106, ERROR_TYPE_SERIOUS);
				$invalidField = true;
			}
		}
		
		return !$invalidField;
	}
	
	/**  Returns the unique ID for this object as stored in memory (NOT THE DATABASE)
     *	@param bool $fieldName If true, then this function will return the table's field name for the unique ID instead of the actual unique ID
     *	@return mixed The unique ID field for this object.  If an array is returned, then more than one field makes up a key.
	*/
	function getUniqueID ($fieldName=false) {
	
		if ($fieldName) {
			return $GLOBALS[$this->_getTableConstant()]['fields']['ID'];
		}
		else {
			return $this->get (PROP_ID);
		}
	}
}
?>