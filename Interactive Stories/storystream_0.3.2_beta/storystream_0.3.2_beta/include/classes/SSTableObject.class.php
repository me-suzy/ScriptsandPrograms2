<?php
/** @file SSTableObject.class.php
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

/**  Represents an object that has a presence in a database
 *	Derive from this object if your new object represents a record
 *	in the database.  This class provides very useful methods for 
 *	accessing the database such as adding,removing and marking records
 *	as deleted.  Certain method MUST be overridden, though
 */
class SSTableObject extends SSObject
{
	var $_trueDeletionEnabled = false;
	
	/** Constructor: Calls _addsProperties function to initialize object properties
	*/
	function SSTableObject () {
		$this->_addProperties ();
	}

	/**  Adds all associated properties=
     *	This need only be called once per instantiation of this class
     *	and is handled automaticby the base class as long
     *	as its constructor is called.		
     */
	function _addProperties () {
		
	}
	
	/**  Gets all the database field names with associated values
     *	This will return an associative array where the keys
     *	are the table field names and the values are the values
     *	stored in this class.
     *	@param bool $includeDBKey If true, then the key field for the table is returned in the array as well.
     *	@return array An associative array of dbase fields => class values
     *	@access public
	 */
	function getDBKeyValueArray ($includeDBKey) {
		die ("getDBKeyValueArray is a PURE VIRTUAL function and MUST be implemented in derived classes");
	}
	
	/**  Sets all the object properties based on the given database field values
     *	Given is an associative array where the keys are the database field names
     *	and the values are the values of those fields.
     *	@param array $dbFieldsAndValues The associative array of database field names and values.
     *	@return bool True if all the required fields were found and copied over.
     *	@access protected
	 */
	function _setDBKeyValueArray ($dbFieldsAndValues) {
		die ("_setDBKeyValueArray is a PURE VIRTUAL function and MUST be implemented in derived classes");
	}
	
	/**  Gets the associative array required to mark a record as deleted
     *	@param bool $checkKey If this is true, then the unique key field for the object is checked for validity as well
     *	@return array An associative (key>value) array populated with the keys/values required to mark the object as deleted
     *	@access protected
	 */
	function _getDBKeyValueForDelete () {
		die ("_getDBKeyValueForDelete is a PURE VIRTUAL function and MUST be implemented in derived classes");
	}
	
	/**  Gets the key string for the associative array that contains field information for the table associated with this object.
     *	@see tables.inc.php
     *	@return string The key field string for the assocative array that contains field information for the table associated with this object.
     *	@access protected
	 */
	function _getTableConstant () {
		die ("_getTableConstant is a PURE VIRTUAL function and MUST be implemented in derived classes");
	}
	
	/**  Checks if the data stored in this object is valid.
     *	All this does is verify that there is valid data in the *required*
     *	fields.
     *	@param bool $checkKey If this is true, then the unique key field for the object is checked for validity as well
     *	@return bool True, if all required fields are valid.
	 */
	function requiredFieldsValid ($checkKey = false) {
		die ("requiredFieldsValid is a PURE VIRTUAL function and MUST be implemented in derived classes");
	}
	
	/**  Returns the unique ID for this object as stored in memory (NOT THE DATABASE)
     *	@param bool $fieldName If true, then this function will return the table's field name for the unique ID instead of the actual unique ID
     *	@return mixed The unique ID field for this object.  If an array is returned, then more than one field makes up a key.
	*/
	function getUniqueID ($fieldName=false) {
		die ("getUniqueID is a PURE VIRTUAL function and MUST be implemented in derived classes");
	}
	
	/** 
	 *	Caches this object to make queries more efficient.  Note that
	 *  this is not saved in the session.  But it will cut down dramatically
	 * 	on repeat querries in the same script
	 *	@return bool Returns true if the object has been cached.  False otherwise.
	 */
	function cacheObject () {		
		$GLOBALS['CACHE'][$this->getType().$this->getUniqueID()] = $this;
	}	
	
	/**
	 * Removes all cached items from the cache global
	 * 
	 */
	function clearCache () {
		$GLOBALS['CACHE'] = array ();
	}
	
	/** Retrieves the cached object, if any, that is of the same type 
	 *	with the same ID.
	 *	@return mixed The SSTableObject-derived object that has been cached or 
	 *			NULL if the object has not been cached.
	 */ 
	function getCachedObject () {
		if (isset ($GLOBALS['CACHE'][$this->getType().$this->getUniqueID()])) {
			return $GLOBALS['CACHE'][$this->getType().$this->getUniqueID()];
		}
		else {
			return NULL;
		}
	}
	
	/** Call this load this object from the database
	 * 	The unique ID property must be set before this function is called.
	 *	@return bool Returns true if loaded, false otherwise
	 */
	function load () {
	
		// Empty the error queue
		$this->clearErrors ();
		
		//echo "Class: ".get_class($this)."<br>";
		//echo "ID: ".$this->getUniqueID()."<br>";
		
		$object = $this->getCachedObject();
		if (!$object) {
			if ($this->getUniqueID () != '') {
	
				$query = 'SELECT * FROM '.$GLOBALS[$this->_getTableConstant()]['name'].' WHERE '.$this->getUniqueID (true).'='.$this->getUniqueID (false);
				$fields = $GLOBALS['DBASE']->getAssoc ($query);
	
				if (!DB::isError ($fields))	{						
				
					$keyFieldName = str_replace ('\'', '', $this->getUniqueID (false));
					// Check to see if the record with the given ID was found.
					if (isset ($fields[$keyFieldName])) {
						
						// Add the key field to the array of fields.
						$fields[$keyFieldName][$this->getUniqueID (true)] = $keyFieldName;
						
						// Now load the data into the object.
						if ($this->_setDBKeyValueArray ($fields[$keyFieldName])) {
							$this->cacheObject();
							return true;
						}
						
					}
				}
				else {
					$this->addErrorObject ($result, ERROR_TYPE_SERIOUS);
				}
			}
			else {
				$this->addError (sprintf (STR_208, get_class ($this).'('.$this->getUniqueID().')'), ERROR_TYPE_SERIOUS);
			}
			return false;
		}
		else {
		    copyObject ($object, $this);
			return true;
		}
	}
	
	/**  Add this object to the database
     *	This assumes that the required properties have been filled in.
     *	The required properties include:
     *		* name
     *		* description
     *		* user_id
     *		* type
     *		* permission
     *	@return bool True if the object was added successfully, false otherwise.
     */
	function add () {
			
		// Empty the error queue
		$this->clearErrors ();
		
		if ($this->requiredFieldsValid ()) {		
		
			$fields = $this->getDBKeyValueArray (false);
						
			// Insert into the database
			$h = $GLOBALS['DBASE']->autoPrepare ($GLOBALS[$this->_getTableConstant()]['name'], array_keys ($fields));
			$result = $GLOBALS['DBASE']->execute ($h, array_values ($fields));

			if (!DB::isError ($result)) {

				// The database query was a success
				$this->set (PROP_ID, mysql_insert_id() );
								
				// Broadcast a system notification
				$GLOBALS['EVENTS']->onObjectAddedToDatabase ($this);
				
				return true;
			}

			$this->addErrorObject ($result, ERROR_TYPE_SERIOUS);
		}		
		return false;
	}
	
	/**  This will update an object in the database with the data stored in this object
     *	@return bool True if the object was updated successfully
	 */
	function update () {
	
		// Empty the error queue
		$this->clearErrors ();
		
		if ($this->requiredFieldsValid (true)) {		
		
			$fields = $this->getDBKeyValueArray (false);
						
			$where = $this->getUniqueID (true).'='.$this->getUniqueID ();
			
			// Update the database
			$h = $GLOBALS['DBASE']->autoPrepare ($GLOBALS[$this->_getTableConstant()]['name'], array_keys ($fields), DB_AUTOQUERY_UPDATE, $where);
			$result = $GLOBALS['DBASE']->execute ($h, array_values ($fields));
			
			if (!DB::isError ($result)) {
			
				// The database query was a success							
				
				// Broadcast a system notification
				$GLOBALS['EVENTS']->onObjectEditedInDatabase ($this);
				
				return true;
			}
			
			$this->addErrorObject ($result, ERROR_TYPE_SERIOUS);
		}		
		return false;
	}

	/**  This will mark an object as deleted.  
     *	Note that the object is not actually removed from the database.  Instead, it's
     *	simply marked as deleted.
     *	@return bool True if the object was marked as deleted successfully
	 */
	function delete () {
	
		// Empty the error queue
		$this->clearErrors ();
		
		// Get the where condition for this specific object.  It will
		//	be used in whatever SQL statement is used.
		$where = $this->getUniqueID (true).'='.$this->getUniqueID (false);
				
		if ($this->getUniqueID () != '') {		
		
			$fields = $this->_getDBKeyValueForDelete ();					
			if (!$this->isTrueDeletionEnabled() && is_array ($fields) && (count ($fields)>0)){		
				// Update the database
				$h = $GLOBALS['DBASE']->autoPrepare ($GLOBALS[$this->_getTableConstant()]['name'], array_keys ($fields), DB_AUTOQUERY_UPDATE, $where);
				$result = $GLOBALS['DBASE']->execute ($h, array_values ($fields));
				
				if (!DB::isError ($result)) {
				
					// The database query was a success
					$this->addNotification ('The '.$this->getTypeName (false).' called "'.$this->get (PROP_NAME).'" was successfully marked as "DELETED"');
					return true;
				}
				
				$this->addErrorObject ($result, ERROR_TYPE_SERIOUS);
			}
			else {

				// Delete the record completely
				$query = 'DELETE FROM '.$GLOBALS[$this->_getTableConstant()]['name'].' WHERE '.$where;
				$result = $GLOBALS['DBASE']->simpleQuery ($query);				
				if (DB::isError($result)) {
					$this->addErrorObject ($result, ERROR_TYPE_SERIOUS);
				}
				else {
					$name = $this->hasProperty ('name') ? $this->get (PROP_NAME) : '';					
					$type = $this->getTypeName (false);
								
					// The database query was a success
					if ($name == '') {
						$this->addNotification (sprintf (STR_209, $type));
					}
					else {
						$this->addNotification (sprintf (STR_210, $type, $name));
					}
					
					// Now we need to remove all ratings that reference this scene
					$GLOBALS['EVENTS']->onObjectRemovedFromDatabase ($this);
					
					return true;
				}
			}
		}		
		else {
			$this->addError (STR_211, ERROR_TYPE_SERIOUS);
		}
		return false;
	}
	
	/** Displays the object for reading (by any user)
	 *
	 * This is different from viewing in that viewing
	 *	displays all properties of the object and is meant
	 *	for author/admin use.  Reading is used to display
	 *	any story/scene/fork purely for the purpose of 
	 *	getting at the content.  The html is output
	 *	immediately.
	 */
	function read () {
		die ("read is a PURE VIRTUAL function and MUST be implemented in derived classes");
	}
	
	/**  Makes sure that the given field values are unique to the table	
     *	This will check the associated table for any rows that contains the 
     *	same value for the same field as the ones given.
     *	@param string $field The name of the field to check
     *	@pararm mixed $value The value of the field to check.  IMPORTANT: If this is a string then it must be quoted before passing in.
     *	@return bool true if the field is unique, false if there is a duplicate.
	 */
	function verifyFieldUnique ($field, $value) {
		
		$query = 'SELECT * FROM '.$GLOBALS[$this->_getTableConstant()]['name'].' WHERE '.$field.'='.$value;
		$fields = $GLOBALS['DBASE']->getAssoc ($query);
		
		if (!DB::isError ($fields))	{		
			if (count ($fields) == 0) {
				return true;
			}
		}
		else {
			$this->addErrorObject ($result, ERROR_TYPE_SERIOUS);
		}		
		
		return false;
	}

	/** Provides a shortcut to rating and classifying an applicable object
	 * Use this instead of creating your own SSRating instance
	 * and going through the trouble of setting it up, etc.
	 * @param int $rating The rating value.
	 * @param string $classification The classification value (can be empty)
	 * @param string $comment The comment value (can be empty)
	 * @return bool True if the rating/classification was successful.
	 */
	function rateAndClassify ($rating,$classification, $comment) {
	
		$rating = new SSRating;
		return $rating->rate ($this, $rating, $comment. $classification);
	}
		
	/** 
	 * True deletion forces a complete removal of a record when 'delete' is called
	 *  Use this function to enable it or disable it.
	 *  @param bool $enable True to force the deletion, false to use the derived class's interpretation of delete
	 */
	function _enableTrueDeletion ($enable) {
		$this->_trueDeletionEnabled = $enable;
	}
	
	/** 
	 * Determines if true deletion is enabled.
	 *  @return bool True if enable,  false otherwise
	 */
	function isTrueDeletionEnabled () {
		return $this->_trueDeletionEnabled;
	}
	
	/** Determines if this object is rated in the database
	 *	If the username is empty then the logged in user
	 *	used.  
	 *  @param string $username The user's username or empty to use the logged in user
	 *	@return SSRating The rating or NULL if not found.
	 */
	function getObjectRating ($username='') {
				
		$user = $GLOBALS['APP']->getLoggedInUserObject();
		if ($user) {
			$username = $user->get ('username');
			$subject_type = $this->getType ();
			$subject_id = $this->get (PROP_ID);
			
			$tablename = $GLOBALS['TABLE_RATING']['name'];
			$userfield = $GLOBALS['TABLE_RATING']['fields']['USER_ID'];
			$subjecttypefield = $GLOBALS['TABLE_RATING']['fields']['SUBJECT_TYPE'];
			$subjectIDfield = $GLOBALS['TABLE_RATING']['fields']['SUBJECT_ID'];
			
			// Load the classification object
			$query = 'SELECT * FROM '.$tablename.' WHERE '.$userfield.'="'.$username.'" AND '.$subjectIDfield.'='.$subject_id.' AND '.$subjecttypefield.'='.$subject_type;
			$results = $GLOBALS['DBASE']->simpleQuery ($query);
			
			if (!DB::isError ($results)) {
	
				$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
				while ($array = $resultObj->fetchRow ()) {
					
					$rating = new SSRating;
					$rating->_setDBKeyValueArray ($array);
					
					return $rating;
				}		
			}
			else {
				$this->addNotification (sprintf (STR_212, $this->getTypeName (false)));
			}
		}
		
		return NULL;
	}
	
	/** Determines if this object is classified in the database
	 *	If the username is empty then the logged in user
	 *	used.  
	 *  @param string $username The user's username or empty to use the logged in user
	 *	@return SSClassification The classification or NULL if not found.
	 */
	function getObjectClassification ($username='') {
		
		$user = $GLOBALS['APP']->getLoggedInUserObject();
		if ($user) {
			$subject_type = $this->getType ();
			$subject_id = $this->get (PROP_ID);
			$username = $user->get ('username');
			$tablename = $GLOBALS['TABLE_CLASSIFICATION']['name'];
			$userfield = $GLOBALS['TABLE_CLASSIFICATION']['fields']['USER_ID'];
			$subjecttypefield = $GLOBALS['TABLE_CLASSIFICATION']['fields']['SUBJECT_TYPE'];
			$subjectIDfield = $GLOBALS['TABLE_CLASSIFICATION']['fields']['SUBJECT_ID'];
			
			$query = 'SELECT * FROM '.$tablename.' WHERE '.$userfield.'="'.$username.'" AND '.$subjectIDfield.'='.$subject_id.' AND '.$subjecttypefield.'='.$subject_type;		
			$results = $GLOBALS['DBASE']->simpleQuery ($query);
			if (!DB::isError ($results)) {			
				$resultObj = new DB_result ($GLOBALS['DBASE'],$results);			
				while (($array = $resultObj->fetchRow ())) {
					
					$class = new SSClassification;
					$class->_setDBKeyValueArray ($array);
					return $class;
				}		
			}
			else {
				$this->addNotification (sprintf (STR_213, $this->getTypeName (false)));
			}
		}
		
		return NULL;
	}

	/** Returns the average rating for this object by the given or active user.
		@param string $username The login name of the user to retrieve an average rating for
		@return float The average rating as a floating point number.
	 */
	function getAverageRating () {
		
		$tableName = $GLOBALS['TABLE_RATING']['name'];
		$avgField = $GLOBALS['TABLE_RATING']['fields']['RATING'];
		$subjTypeField = $GLOBALS['TABLE_RATING']['fields']['SUBJECT_TYPE'];
		$subjIDField = $GLOBALS['TABLE_RATING']['fields']['SUBJECT_ID'];
		
		$type = $this->getType ();
		$id = $this->getUniqueID();
		
		$query = "SELECT AVG ($avgField) AS average , COUNT($avgField) AS count FROM $tableName WHERE $subjTypeField=$type AND $subjIDField=$id";
		$results = $GLOBALS['DBASE']->simpleQuery ($query);		
		if (!DB::isError ($results)) {
			
			$resultObj = new DB_Result ($GLOBALS['DBASE'], $results);
			$array = $resultObj->fetchRow ();			
			return array ('average'=>$array ['average'],'total'=>$array ['count']);
		}
		
		return array ();
	}		
	
	/** Retrieves an associative array with the key being the classification and the value being the percent classified as such
		@param string $username The username or empty to use the logged in user.
		@return array The array is arranged like this:
						[class1=>'24',class2=>'26',class3=>'45',class4='5']
					  or is empty if there was an error.
	 */
	function getClassificationBreakdown (&$totalClassifications){
		
		$final = array ();
		$tableName = $GLOBALS['TABLE_CLASSIFICATION']['name'];
		$classField = $GLOBALS['TABLE_CLASSIFICATION']['fields']['CLASSIFICATION'];
		$subjTypeField = $GLOBALS['TABLE_CLASSIFICATION']['fields']['SUBJECT_TYPE'];
		$subjIDField = $GLOBALS['TABLE_CLASSIFICATION']['fields']['SUBJECT_ID'];
		$usernameField = $GLOBALS['TABLE_CLASSIFICATION']['fields']['USER_ID'];
		$type = $this->getType ();
		$id = $this->getUniqueID();
		
		// Get total
		$query = "SELECT COUNT(*) AS count FROM $tableName WHERE $subjTypeField=$type AND $subjIDField=$id";
		$results = $GLOBALS['DBASE']->simpleQuery ($query);		
		$totalClassifications = 0;
		if (!DB::isError ($results)) {
			
			$resultObj = new DB_Result ($GLOBALS['DBASE'],$results);
			$array = $resultObj->fetchRow ();
			$totalClassifications = $array['count'];
		}

		if ($totalClassifications > 0) {
			
			$queryTmp = "SELECT COUNT(*) AS count FROM $tableName WHERE $subjTypeField=$type AND $subjIDField=$id AND $classField='%s'";
			foreach ($GLOBALS['classifications'] as $cl) {
				
				$query = sprintf ($queryTmp, $cl);
				$results = $GLOBALS['DBASE']->simpleQuery ($query);		
				if (!DB::isError ($results)) {
									
					$resultObj = new DB_Result ($GLOBALS['DBASE'],$results);
					$array = $resultObj->fetchRow ();
					array_push ($final, array ('name'=>$cl, 'percent'=>($array['count']/$totalClassifications)*100));
				}
			}
		}
		
		return $final;
	}
	
	/** Returns the bookmark (if any) for this object
	 *	@param SSBookmark The bookmark object or NULL if none found.
	 */
	function getObjectBookmark () {

		$user = $GLOBALS['APP']->getLoggedInUserObject();
		if ($user) {
			$subject_type = $this->getType ();
			$subject_id = $this->get (PROP_ID);
			$username = $user->get ('username');
			$tablename = $GLOBALS['TABLE_BOOKMARKS']['name'];
			$userfield = $GLOBALS['TABLE_BOOKMARKS']['fields']['USER_ID'];
			$subjecttypefield = $GLOBALS['TABLE_BOOKMARKS']['fields']['SUBJECT_TYPE'];
			$subjectIDfield = $GLOBALS['TABLE_BOOKMARKS']['fields']['SUBJECT_ID'];
			
			$query = 'SELECT * FROM '.$tablename.' WHERE '.$userfield.'="'.$username.'" AND '.$subjectIDfield.'='.$subject_id.' AND '.$subjecttypefield.'='.$subject_type;		
			$results = $GLOBALS['DBASE']->simpleQuery ($query);
			if (!DB::isError ($results)) {			
				$resultObj = new DB_result ($GLOBALS['DBASE'],$results);			
				while (($array = $resultObj->fetchRow ())) {
					
					$bookmark = new SSBookmark;
					$bookmark->_setDBKeyValueArray ($array);
					return $bookmark;
				}		
			}
		}
		
		return NULL;
	}
	
	
}
?>
