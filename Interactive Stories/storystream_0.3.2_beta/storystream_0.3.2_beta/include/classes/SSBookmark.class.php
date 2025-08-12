<?php
/** @file SSBookmark.class.php
 *
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
 *	
 *	@version 0.1
 *	@date March, 2004
 */

/** Represents a pointer to a fork, story or scene
	Bookmarks are conveniences for users allowing
	them to return to a point in the story where
	last they left off.
	@author Karim Shehadeh
	@date 3/12/2003
*/
class SSBookmark extends SSTableObject
{
	/** Constructor: Adds required properties*/
	function SSBookmark () {
		parent::SSTableObject ();
	}	
	
	/** Returns an object type code of OBJECT_TYPE_UNKNOWN if unspecified
	 * @return int The object type value
	 * @access public
	 */
	function getType () {
		return OBJECT_TYPE_BOOKMARK;
	}
	
	/** Retrieves the classes friendly type name
	 * @param bool $asPlural True to return the plural version, false otherwise.
	 * @return string The name as a string
	 *  @access public
	 */
	function getTypeName ($asPlural) {		
		return $asPlural ? 'bookmarks' : 'bookmark';
	}
	
	/**  Adds all associated properties
     *	This need only be called once per instantiation of this class
     *	and is handled automatically by the base class as long
     *	as its constructor is called.		
	*/
	function _addProperties () {
		$this->_addProperty (PROP_ID, 0);
		$this->_addProperty ('subject_type', '');
		$this->_addProperty ('subject_id', '');
		$this->_addProperty (PROP_USERNAME, '');
		$this->_addProperty ('date', 0);
		$this->_addProperty (PROP_NAME, '');
		$this->_addProperty ('notes', '');
	}
	
	/** Gets all the database field names with associated values
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
					$GLOBALS[$tableConstant]['fields']['SUBJECT_TYPE'] => $this->get ('subject_type'),
					$GLOBALS[$tableConstant]['fields']['SUBJECT_ID'] => $this->get ('subject_id'),
					$GLOBALS[$tableConstant]['fields']['USER_ID'] => $this->get (PROP_USERNAME),
					$GLOBALS[$tableConstant]['fields']['DATE'] => $this->get ('date'),
					$GLOBALS[$tableConstant]['fields']['NAME'] => $this->get (PROP_NAME),
					$GLOBALS[$tableConstant]['fields']['NOTES'] => $this->get ('notes')
				);
				
		if ($includeDBKey) {
			$fields [$GLOBALS[$tableConstant]['fields']['ID']] = $this->get (PROP_ID);
		}
		
		return $fields;
	}
	
    /**
     * Sets all the object properties based on the given database field values
     * Given is an associative array where the keys are the database field names
     * and the values are the values of those fields.
     * @param array $dbFieldsAndValues The associative array of database field names and values.
     * @return bool True if all the required fields were found and copied over.
     */
    function _setDBKeyValueArray ($dbFieldsAndValues)
    {
        $tableConstant = $this->_getTableConstant();
        $this->set (PROP_ID, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['ID']]);
        $this->set ('subject_type', $dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['SUBJECT_TYPE']]);
        $this->set ('subject_id', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['SUBJECT_ID']]);
        $this->set (PROP_USERNAME, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['USER_ID']]);
        $this->set ('date', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['DATE']]);
        $this->set (PROP_NAME, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['NAME']]);
        $this->set ('notes', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['NOTES']]);

        return $this->requiredFieldsValid ();
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
		return 'TABLE_BOOKMARKS';
	}

	/**  Checks if the data stored in this object is valid.
     *	All this does is verify that there is valid data in the *required*
     *	fields.
     *	@param bool $checkKey If this is true, then the unique key field for the object is checked for validity as well
     *	@return bool True, if all required fields are valid.
	*/
	function requiredFieldsValid ($checkKey = false) {
	
		$invalidField = false;
		
		if ($this->get('subject_type') == '') {
			$this->addError (STR_17, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get('subject_id') == '') {
			$this->addError (STR_18, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get (PROP_USERNAME) == '') {
			$this->addError (STR_19, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get('date') == '') {
			$this->addError (STR_20, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get (PROP_NAME) == '') {
			$this->addError (STR_21, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		
		if ($checkKey) {
			if ($this->get (PROP_ID) == '') {
				$this->addError (STR_22, ERROR_TYPE_SERIOUS);
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
	
	/** Initializes smarty variables to display this object
     *	Override for custom object.
     *	@param array[Ref] $array On output, the array containing the object's properties
     *	@param bool $getPropertiesOnly This should be true if the caller would like to retrieve
     *			data such as name, story id, description rather than detailed information
     *			about the scenes that this fork points to.
	 */
	function prepareSmartyVariables (&$array, $getPropertiesOnly=false) {
		
		$array ['id'] = $this->get (PROP_ID);
		$array ['name'] = stripslashes ($this->get (PROP_NAME));
		$array ['notes'] = stripslashes ($this->get ('notes'));
		$array ['subject_type'] = $this->get ('subject_type');
		$array ['link'] = READING_ROOT.'/read.php?t='.$this->get ('subject_type').'&i='.$this->get ('subject_id');
		$array ['type_values'] = array ('story'=>OBJECT_TYPE_STORY, 'scene'=>OBJECT_TYPE_SCENE, 'fork'=>OBJECT_TYPE_FORK);
		$array ['subject'] = array ();
		
		if (!$getPropertiesOnly) {		
			$object = generateObject ($this->get ('subject_type'), $this->get ('subject_id'));
			if ($object) {
				$object->prepareSmartyVariables ($array['subject'], true);
			}
					
			$storyPath = new SSStoryPath;
			$trace = array ();
			switch ($this->get ('subject_type')) {
				case OBJECT_TYPE_SCENE:
					$trace = $storyPath->backtrackFromScene($object->get (PROP_ID));
					break;
				case OBJECT_TYPE_STORY:
					$trace = array($object);
					break;
				case OBJECT_TYPE_FORK:
					$trace = $storyPath->backtrackFromFork  ($object->get (PROP_ID));
					break;
			}
			if (count ($trace) > 0) {
				$array['breadcrumb'] = $storyPath->generateBreadcrumbString ($trace, true);
			}
		}
	}	
	
	/**  Displays the fork add/edit form in the client
     *	This will take into account if the form should be an edit
     *	form or an new forkform.
     *	@param bool $isEditForm True if the form to be displayed is for editng an existing scene.
     *			It's false if the scene will be added as a new one.
     *	@param mixed $data Associated data with the form - see description for more on this
     *	@param bool $afterError True if the form is being displayed after an error occured in a previous submit.
     *	@return bool Returns true if the form was displayed successfully.
	 */
	function displayForm ($isEditForm, $data='', $afterError=false) {
		
		$smarty = new SSSmarty;
		$smartyBookmark = array ();

		// Get the last used form data (if any).
		$memObj = $GLOBALS['APP']->retrieveValue ('BOOKMARK_ADDEDIT_OBJ');

		if ($isEditForm) {

			$this->set (PROP_ID, $GLOBALS['APP']->queryValue ('bookmark_id'));
			if ($this->load ()) {

				// If there was a submit error then use
				//	the values that were previously entered
				//	instead of those that are in the database.
				if ($afterError && $memObj) {

					$name = $memObj->get (PROP_NAME);
					$notes = $memObj->get ('notes');

					$this->set (PROP_NAME, $name);
					$this->set ('notes', $notes);
				}

				$this->prepareFormTemplate ($smarty, true);
			}
			else {
				$this->addError (sprintf (STR_23, $this->get (PROP_ID)), ERROR_TYPE_SERIOUS);
				return false;
			}
		}
		else {

			// When adding,we can just copy over the entire object if
			//	there was a submit error.
			if ($afterError && $memObj) {
			    copyObject ($memObj, $this);
			}
			else {
				// What should this bookmark point to.
				$this->set ('subject_type', $this->queryValue ('st'));
				$this->set ('subject_id', $this->queryValue ('si'));
			}
			
			// This is just an add so there's no need to prepopulate		
			$this->prepareFormTemplate ($smarty, false);			
		}
				
		$smarty->display ('reading/form_bookmark.tpl');
	}	
	
	/**  Prepares all the variables for the story form Smarty template
     *	This will prepopulate the smarty template with all the information
     *	relevant to this story object.  So, if you're editing, you must
     *	first load the story from the database then call this method.  If
     *	this is an add form then you can set the defaults for the form by
     *	setting the properties of this object.
     *	@param Smarty $smarty [Ref] The Smarty object to populate with form data
     *	@param bool $editForm Indicates whether or not this is an edit form
	 */
	function prepareFormTemplate (&$smarty, $editForm) {
		
		$formProperites = array ();
		$formProperties['edit'] = $editForm;
		$smarty->assign ('ss_form', $formProperties);

		$bmProperties = array ();
		$this->prepareSmartyVariables ($bmProperties);
		$smarty->assign ('ss_bookmark', $bmProperties);
	}	
	
	/**  Handles submission of fork form data
     *	This will take care of retrieving the POST parameters,
     *	then using the data, if valid, to add to or change
     *	the databse.
     *	@param bool $editForm True if the data being submitted
     *				is to be used for editing the database, false
     *				indicates that it's for adding.
     *	@return bool True if the submission was successful, false otherwise.
	 */
	function handleFormSubmit ($editForm) {
		
		
		if ($editForm) {
			$bookmark_id = $this->queryPostValue ('bookmark_id');
			$this->set (PROP_ID, $bookmark_id);
			if (!$this->load ()) {
				// Invalid bookmark edited.
				return false;
			}
			else {
				// Edit a bookmark
				$bookmark = generateObject (OBJECT_TYPE_BOOKMARK, $bookmark_id);
				if ($bookmark) {
					$bookmark->set (PROP_NAME, $GLOBALS['APP']->queryValue ('name'));
					$bookmark->set ('notes',$GLOBALS['APP']->queryValue ('notes'));

					if ($bookmark->update ()) {
						$this->addNotification (STR_24);
						return true;
					}					
				}
			}
		}
		else {
			
			// Add a bookmark
			$type = $GLOBALS['APP']->queryValue ('st');
			$id = $GLOBALS['APP']->queryValue ('si');
			$object = generateObject ($type, $id);
			if ($object) {
				
				$user = $GLOBALS['APP']->getLoggedInUserObject ();
				
				if ($user) {
					$this->set ('date', time ());
					
					$this->set (PROP_USERNAME, $user->get ('username'));
					$this->set ('subject_type', $type);
					$this->set ('subject_id', $id);
					$this->set (PROP_NAME, $GLOBALS['APP']->queryValue ('name'));
					$this->set ('notes', $GLOBALS['APP']->queryValue ('notes'));
					if ($this->add ()) {
						$this->addNotification (STR_25);
						return true;
					}
				}
				else {
					$this->addError (STR_26);
				}
			}
			else {
				$this->addError (STR_27);
			}
		}
		
		return false;
	}	
	
	/**  Displays the object on the user's browser using the appropriate view template
     *		The appropriate view template is specific to the object's type
	 */
	function view () {
		
		if ($this->requiredFieldsValid (true)) {
			
			$ss_bookmark = array ();
			$this->prepareSmartyVariables ($ss_bookmark);

			$smarty = new SSSmarty;
			$smarty->assign ('ss_bookmark', $ss_bookmark);
			
			 	
		 	// Note the fork view in the dbase and display
			echo $smarty->display ('reading/view_bookmark.tpl');
		}
	}
	
}
?>
