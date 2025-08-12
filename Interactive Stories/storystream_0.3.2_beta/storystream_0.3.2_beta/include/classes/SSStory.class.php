<?php
/** @file SSStory.class.php
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

/**  Represents a single story in the database
	The story class is used to add, edit, view and otherwise
	manipulate stories in the database
*/
class SSStory extends SSContentObject
{
	var $_startForks = false;
	var $_endForks = false;
	
	/** Constructor: Adds required properties*/
	function SSStory () {
		parent::SSTableObject ();
	}	
	
	/** Retrieves the classes friendly type name
	 * @param bool $asPlural True to return the plural version, false otherwise.
	 * @return string The name as a string
	 *  @access public
	 */
	function getTypeName ($asPlural) {		
		return $asPlural ? STR_167 : STR_168;
	}
	
	/** Returns an object type code of OBJECT_TYPE_UNKNOWN if unspecified
	 * @return int The object type value
	 * @access public
	 */
	function getType () {
		return OBJECT_TYPE_STORY;
	}
	
	/**  Adds all associated properties=
     *	This need only be called once per instantiation of this class
     *	and is handled automatically by the base class as long
     *	as its constructor is called.		
	*/
	function _addProperties () {
		$this->_addProperty (PROP_ID, 0);
		$this->_addProperty (PROP_NAME, '');
		$this->_addProperty (PROP_SYNOPSIS, '');
		$this->_addProperty (PROP_DESCRIPTION, '');
		$this->_addProperty (PROP_USERNAME, '');
		$this->_addProperty (PROP_STATUS, 0);
		$this->_addProperty (PROP_TYPE, 0);
		$this->_addProperty (PROP_PHPBB_TOPIC_ID, 0);
		
		$this->_addProperty ('start_mod_id', 0);
		$this->_addProperty ('last_mod_id', 0);
		$this->_addProperty ('degrees', 0);
		$this->_addProperty ('begin_scene_id', 0);
		$this->_addProperty ('end_scene_id', 0);
		$this->_addProperty (PROP_RATING, 0);
		$this->_addProperty (PROP_PERMISSION, 0);
		$this->_addProperty (PROP_GROUP_ID, 0);
		
		parent::_addProperties ();
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
		$tableData = $GLOBALS[$tableConstant];
		$fields = array
				(
					$tableData['fields']['NAME'] => $this->get (PROP_NAME),
					$tableData['fields']['DESCRIP'] => $this->get (PROP_DESCRIPTION),
					$tableData['fields']['SYNOPSIS'] => $this->get (PROP_SYNOPSIS),
					$tableData['fields']['USER'] => $this->get (PROP_USERNAME),
					$tableData['fields']['TYPE'] => $this->get (PROP_TYPE),
					$tableData['fields']['STATUS'] => $this->get (PROP_STATUS),
					$tableData['fields']['PHPBB_TOPIC_ID'] => $this->get (PROP_PHPBB_TOPIC_ID),
					$tableData['fields']['DATA_TYPE'] => $this->get (PROP_DATA_TYPE),
					$tableData['fields']['DATA_BINARY'] => $this->get (PROP_DATA_BINARY),
					$tableData['fields']['DATA_PROPS'] => $this->get (PROP_DATA_PROPERTIES),
					$tableData['fields']['RATING'] => $this->get (PROP_RATING),
					$tableData['fields']['PERM'] => $this->get (PROP_PERMISSION),
					$tableData['fields']['LICENSE_URL'] => $this->get (PROP_LICENSE_URL),
					$tableData['fields']['LICENSE_NAME'] => $this->get (PROP_LICENSE_NAME),
					$tableData['fields']['LICENSE_CODE'] => $this->get (PROP_LICENSE_CODE),
					$tableData['fields']['START_MOD'] => $this->get ('start_mod_id'),
					$tableData['fields']['LAST_MOD'] => $this->get ('last_mod_id'),
					$tableData['fields']['DEGREES'] => $this->get ('degrees'),
					$tableData['fields']['BEGIN_SCENE'] => $this->get ('begin_scene_id'),
					$tableData['fields']['GROUP_ID'] => $this->get (PROP_GROUP_ID),
					$tableData['fields']['END_SCENE'] => $this->get ('end_scene_id')
				);
		
		if ($includeDBKey) {
			$fields [$tableData['fields']['ID']] = $this->get (PROP_ID);
		}
		
		return $fields;
	}

	/**  Sets all the object properties based on the given database field values
     *	Given is an associative array where the keys are the database field names
     *	and the values are the values of those fields.
     *	@param array $dbFieldsAndValues The associative array of database field names and values.
     *	@return bool True if all the required fields were found and copied over.
	*/
	function _setDBKeyValueArray ($dbFieldsAndValues) {
	
		$tableConstant = $this->_getTableConstant();
		$this->set (PROP_ID, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['ID']]);
		$this->set (PROP_NAME, stripslashes (@$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['NAME']]));
		$this->set (PROP_DESCRIPTION, stripslashes (@$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['DESCRIP']]));
		$this->set (PROP_SYNOPSIS, stripslashes (@$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['SYNOPSIS']]));
		$this->set (PROP_USERNAME, $dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['USER']]);
		$this->set (PROP_TYPE, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['TYPE']]);
		$this->set (PROP_STATUS, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['STATUS']]);
		$this->set (PROP_PHPBB_TOPIC_ID, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['PHPBB_TOPIC_ID']]);
        $this->set (PROP_DATA_TYPE, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['DATA_TYPE']]);
        $this->set (PROP_DATA_BINARY, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['DATA_BINARY']]);
        $this->set (PROP_DATA_PROPERTIES, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['DATA_PROPS']]);
		$this->set (PROP_LICENSE_URL, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['LICENSE_URL']]);
		$this->set (PROP_LICENSE_NAME, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['LICENSE_NAME']]);
		$this->set (PROP_LICENSE_CODE, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['LICENSE_CODE']]);
		$this->set (PROP_GROUP_ID, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['GROUP_ID']]);
		
		$this->set ('start_mod_id', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['START_MOD']]);
		$this->set ('last_mod_id', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['LAST_MOD']]);
		$this->set ('rating', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['RATING']]);
		$this->set ('permission', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['PERM']]);
		$this->set ('degrees', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['DEGREES']]);
		$this->set ('begin_scene_id', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['BEGIN_SCENE']]);
		$this->set ('end_scene_id', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['END_SCENE']]);
						
		return $this->requiredFieldsValid ();
	}
	
	/**  Gets the associative array required to mark a record as deleted
     *	@param bool $checkKey If this is true, then the unique key field for the object is checked for validity as well
     *	@return array An associative (key>value) array populated with the keys/values required to mark the object as deleted
	*/
	function _getDBKeyValueForDelete () {			
		$this->set (PROP_STATUS, STORY_STATUS_DELETED);
		$fields = array ($GLOBALS[$this->_getTableConstant()]['fields']['STATUS'] => STORY_STATUS_DELETED);								
		return $fields;
	}
	
	/**  Gets the key string for the associative array that contains field information for the table associated with this object.
     *	@see tables.inc.php
     *	@return string The key field string for the assocative array that contains field information for the table associated with this object.
	*/
	function _getTableConstant () {
		return 'TABLE_STORY';
	}
	
	/**  Checks if the data stored in this object is valid.
     *	All this does is verify that there is valid data in the *required*
     *	fields.
     *	@param bool $checkKey If this is true, then the unique key field for the object is checked for validity as well
     *	@return bool True, if all required fields are valid.
	*/
	function requiredFieldsValid ($checkKey = false) {
	
		$invalidField = false;
		
		if ($this->get (PROP_NAME) == '') {
			$this->addError (STR_169, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get (PROP_TYPE) == '') {
			$this->addError (STR_170, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get (PROP_STATUS) == '') {
			$this->addError (STR_171, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}		
		
		if ($checkKey) {
			if ($this->get (PROP_ID) == '') {
				$this->addError (STR_172, ERROR_TYPE_SERIOUS);
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

		$storyProperties = array ();
		
		// Form data (is this an edit or not)?		
		$formProperties = array ();
		$formProperties['edit'] = $editForm;
		$smarty->assign ('ss_form', $formProperties);

		// Prepare type selection array
		$typeNames = array (STR_173, STR_174, STR_175);
		$typeValues = array (STORY_TYPE_BEGIN, STORY_TYPE_BEGIN_END, STORY_TYPE_NO_BEGIN_NO_END);
				
		// Prepare the type array.
		$type = array();
		$type['selected'] = $this->get (PROP_TYPE);
		$type['values'] = $typeValues;
		$type['output'] = $typeNames;
		$storyProperties['types'] = $type;		

		// Prepare the fork list
		$storyProperties['fork_names'] = array ();
		$storyProperties['fork_ids'] = array ();
		$forks = $this->getForkList ();
		if (is_array ($forks)) {
			foreach ($forks as $fork) {
				$storyProperties['fork_names'][] = $fork->get (PROP_NAME);
				$storyProperties['fork_ids'][] = $fork->get (PROP_ID);
			}
		}

		
		// Whether or not to display the writing toolbar
		$storyProperties['show_toolbar'] = true;
		$browser = new SSBrowserCap ('', true);
		
		if (strpos ($browser->property('long_name'), 'safari') !== false) {			
			$storyProperties['show_toolbar'] = false;
		}		

		$storyProperties['media'] = array ();
		$this->prepareSmartyMediaVariables ($storyProperties['media']);
		
		$storyProperties['type'] = STORY_TYPE_BEGIN;
		$storyProperties['name'] = $this->get (PROP_NAME);
		$storyProperties['description'] = $this->get (PROP_DESCRIPTION);
		$storyProperties['degrees'] = $this->get ('degrees');
		$storyProperties['id'] = $this->get (PROP_ID);
		$storyProperties['status'] = $this->get (PROP_STATUS);
		$storyProperties['synopsis'] = $this->get (PROP_SYNOPSIS);

		$smarty->assign ('ss_story', $storyProperties);
		
		// Now get a list of groups to which this user can add this story.
		$user = $GLOBALS['APP']->getLoggedInUserObject();
		$groups = $user->getGroupList ();
		$adminGroups = $user->getGroupAdminList ();
		$groups = array_merge ($groups, $adminGroups);
		$ss_groups = array('output'=>array(),'values'=>array());
		foreach ($groups as $group) {
			$ss_groups['output'][] = $group->get(PROP_NAME);
			$ss_groups['values'][] = $group->get(PROP_ID);
		}
		$ss_groups['selected'] = $this->get (PROP_GROUP_ID);
		
		$smarty->assign ('ss_groups', $ss_groups);
	}

	/**  Handles submission of story form data
     *	This will take care of retrieving the POST parameters,
     *	then using the data, if valid, to add to or change
     *	the databse.
     *	@param bool $editForm True if the data being submitted
     *				is to be used for editing the database, false
     *				indicates that it's for adding.
     *	@return bool True if the submission was successful, false otherwise.
	*/
	function handleFormSubmit ($editForm) {
	
		$success = false;
		// Make sure that everything went okay with the load
		//	before processing the edit.
		if (!$editForm || ($editForm && $this->get (PROP_ID) > 0)) {
	
			// Now set the new values
			$this->set (PROP_NAME, strip_tags ($GLOBALS['APP']->queryPostValue ('name')));
			$this->set (PROP_DESCRIPTION, strip_tags ($GLOBALS['APP']->queryPostValue ('description')));
			$this->set (PROP_SYNOPSIS, strip_tags ($GLOBALS['APP']->queryPostValue ('synopsis')));
			$this->set (PROP_GROUP_ID, $GLOBALS['APP']->queryPostValue ('group_id'));

			// The type and degrees field is set when the story is created
			//	and cannot be changed after the fact.			
			if (!$editForm) {
				$this->set (PROP_TYPE, $GLOBALS['APP']->queryPostValue ('type'));
			}
			else {
				$type = $GLOBALS['APP']->queryPostValue ('type');
				if ($type != STORY_TYPE_NO_BEGIN_NO_END) {
				
					// Check to see if the story is active.  If it is then you 
					//	cannot make this change without first making it a draft.
					if ($this->get (PROP_STATUS) == STORY_STATUS_ACTIVE) {
						$this->addError (STR_176, ERROR_TYPE_SERIOUS);
						return false;
					}
				}
				$this->set (PROP_TYPE, $type);				
			}

			// And update the database with the new/changed record.
			if ($editForm) {
				
				if ($this->queryPostValue ('remove_media')) {
					// The user wants to clear whatever file is in the dbase for this scene
					$this->set (PROP_DATA_BINARY, '');
					$this->set (PROP_DATA_TYPE, '');
					$this->set (PROP_DATA_PROPERTIES, '');
				}
				
				if ($this->get (PROP_STATUS) != STORY_STATUS_DELETED) {
					// Keep track of the values in case we need to return to the form
					$GLOBALS['APP']->rememberValue ('STORY_ADDEDIT_OBJ', $this);
					$success = $this->update ();
				}
				else {
					$this->addError (STR_177, ERROR_TYPE_SERIOUS);
					return false;
				}
			}
			else {

				// Use default values for the status and permission
				$this->set (PROP_STATUS, STORY_STATUS_DRAFT);
				$this->set ('permission', STORY_PERMISSION_REGISTERED_READWRITE);

				$user = $GLOBALS['APP']->getLoggedInUserObject ();
				if ($user) {
				
					// Set the originating author to the logged in user.
					$this->set (PROP_USERNAME, $user->get ('username'));

					// Keep track of the values in case we need to return to the form
					$GLOBALS['APP']->rememberValue ('STORY_ADDEDIT_OBJ', $this);
					
					// Submit to database.
                    $success = $this->add ();
				}
			}
		}
		else {
			$this->addError (STR_178, ERROR_TYPE_SERIOUS);
			return false;
		}
		
		if ($success) {
			$this->handleMediaSubmit ();
			return true;
		}
		
		return false;
	}
	
    /**
     * Converts the status code to a human-readable string
     * 
     * @return string The status code string or 'UNKNOWN STATUS CODE' if invalid
     */
    function statusCodeToString ($code)
    {
        switch ($code) {
            case STORY_STATUS_ACTIVE:
                return STR_179;
            case STORY_STATUS_DRAFT:
                return STR_180;
            case STORY_STATUS_DELETED:
                return STR_181;
            default :
                return STR_182;
        } 
    } 

    /**
     * Converts the type code to a human-readable string
     * 
     * @return string The type code string or 'UNKNOWN TYPE' if invalid
     */
    function typeCodeToString ($code)
    {
        switch ($code) {
            case STORY_TYPE_BEGIN:
                return STR_183;
            case STORY_TYPE_BEGIN_END:
                return STR_184;
            case STORY_TYPE_NO_BEGIN_NO_END:
                return STR_185;
            default :
                return STR_186;
        } 
    } 

	/** Initializes smarty variables to display this object
     *	Override for custom object.
     *	@param array[Ref] $array On output, the array containing the object's properties
     *	@param bool $getPropertiesOnly This is ignored with objects of this type.
	*/
	function prepareSmartyVariables (&$array, $getPropertiesOnly=false) {

		$array ['id'] = $this->get (PROP_ID);
		$array ['name'] = stripslashes ($this->get (PROP_NAME));
		
		$bbcode = new SSBBCode;
		$array ['description'] = $bbcode->parse_bbcode (stripslashes ($this->get (PROP_DESCRIPTION)));
		$array ['forks'] = $this->get ('degrees');
		$array ['synopsis'] = stripslashes ($this->get (PROP_SYNOPSIS));
		$array ['username'] = $this->get (PROP_USERNAME);
		$array ['phpbb_topic_id'] = $this->get (PROP_PHPBB_TOPIC_ID);		
		$array ['license_code'] = $this->get (PROP_LICENSE_CODE);		
		$array ['license_name'] = $this->get (PROP_LICENSE_NAME);		
		$array ['license_url'] = $this->get (PROP_LICENSE_URL);
		$array ['object_type'] = $this->getType();		
		$array ['url_root'] = $GLOBALS['baseUrl'];
		
		$user = $GLOBALS['APP']->getLoggedInUserObject();
		if (!$user || ($this->get(PROP_USERNAME) != $user->get (PROP_USERNAME))) {
			
			// Only get user information from the database if the current
			//	user is not the same as the story's author.  This saves
			//	us a query (this function is called *a lot*)
			$user = new SSUser;
			$user->set ('username', $this->get (PROP_USERNAME));
			if (!$user->load ()) {
				$this->addError ('Unable to get user information for story: '.$this->get(PROP_NAME), ERROR_TYPE_SERIOUS);
			}		
		}
		
		$array ['user_email'] = $user->get ('email');
		$array ['user_first_name'] = $user->get ('first_name');
		$array ['user_last_name'] = $user->get ('last_name');
						
		$array ['type'] = $this->typeCodeToString ($this->get (PROP_TYPE));
		$array ['type_int'] = $this->get (PROP_TYPE);
		$array ['type_values'] = array ('begin'=>STORY_TYPE_BEGIN, 'begin_end'=>STORY_TYPE_BEGIN_END, 'none'=>STORY_TYPE_NO_BEGIN_NO_END);
						
        $array ['status'] = $this->statusCodeToString ($this->get (PROP_STATUS));
		$array ['status_values'] = array ('active'=>STORY_STATUS_ACTIVE,'draft'=>STORY_STATUS_DRAFT,'deleted'=>STORY_STATUS_DELETED);
		$array ['status_int'] = $this->get (PROP_STATUS);
		
		// Setup the list of starting forks for the story
		if (!$getPropertiesOnly) {
			$fork = new SSFork;
			$forkList = $this->getForkList ();
			$forkListProperties = array ();
	
			if ($forkList) {
				foreach ($forkList as $fork) {
				 	$forkListItem = array ();
					
					// Get the smarty variables for the fork list 
			 		$fork->prepareSmartyVariables ($forkListItem, true);
				 	array_push ($forkListProperties, $forkListItem);
				}
			}
			$array ['fork_list'] = $forkListProperties;
		
			// Setup the list of ending forks for the story
			$forkList = $this->getEndForkList ();
			$forkListProperties = array ();
	
			if ($forkList) {
				foreach ($forkList as $fork) {
					
				 	$forkListItem = array ();
					
					// Get the smarty variables for the fork list 
			 		$fork->prepareSmartyVariables ($forkListItem, true);
				 	array_push ($forkListProperties, $forkListItem);
				}
			}
			$array ['end_fork_list'] = $forkListProperties;
			
			// Get bookmark information
			$bookmark = $this->getObjectBookmark ();
			$ss_bookmark ['has_bookmark'] = false;
			if ($bookmark) {
				$ss_bookmark ['has_bookmark'] = true;
				$bookmark->prepareSmartyVariables ($ss_bookmark);
			}
			$array['bookmark'] = $ss_bookmark;
			
			// Get group information
			$ss_group = array ();
			if ($this->get (PROP_GROUP_ID) > 0) {
				$group = generateObject (OBJECT_TYPE_GROUP, $this->get (PROP_GROUP_ID));				
				if ($group) {
					$group->prepareSmartyVariables ($ss_group);
				}
			}
			$array['group'] = $ss_group;			
		}
		
		// Setup which actions can be performed on this story
		$array['actions'] = array ();
		$array['actions']['add_scene'] = $this->hasPermissionToAddScene ();
		$array['actions']['edit_story'] = $this->hasPermissionToEditStory ();
		$array['actions']['add_fork'] = $array['actions']['edit_story'];
		$array['actions']['delete_story'] = $array['actions']['edit_story'];
		
		$this->prepareSmartyMediaVariables ($array['media']);
	}

	/**  Retrieves the list of forks that stems from a particular story's scene
     *	If the scene ID is 0 then the function will look for
     *	beginning forks only.
     *	@return mixed An array of found forks (false if none found)
	*/
	function getForkList () {

		$cachedStory = $this->getCachedObject ();
		if ($cachedStory && (is_array ($cachedStory->_startForks))) {
			
			// The fork list has already been retrieved for this
			//	script so use what was already gotten.
			copyObject ($cachedStory, $this);
			return $this->_startForks;
		}
						
		$storyID = $this->get (PROP_ID);
		
		$fork = new SSFork;
		
		// Look for forks from the beginning of the story.
	 	$query = 'SELECT * FROM '.$GLOBALS[$fork->_getTableConstant()]['name'];
	 	$query .= ' WHERE '.$GLOBALS[$fork->_getTableConstant()]['fields']['STORY_ID'].'='.$storyID;
	 	$query .= ' AND '.$GLOBALS[$fork->_getTableConstant()]['fields']['FROM_SCENE'].'=0';

		$results = $GLOBALS['DBASE']->getAssoc ($query);
		if (!DB::isError ($results)) {

			$forks = array ();
			foreach ($results as $key=>$row) {

				// The fork that will be added to the array
				$fork = new SSFork;
				
				// Add the key field to the array of fields.
				$results[$key][$fork->getUniqueID (true)] = $key;

				// Now load the data into the object.
				$fork->_setDBKeyValueArray ($results[$key]);

				// Add to the array of forks.
				array_push ($forks, $fork);
			}

			// Store the forks and cache the object so
			//	we can get this list later, if necessary
			$this->_startForks = $forks;
			$this->cacheObject ();
			
			if (count ($forks) == 0) {
				$forks = false;
			}
			 
			return $forks;
		}
		else {
			$this->addErrorObject ($results, ERROR_TYPE_SERIOUS);
			return false;
		}
	}
	
	/**  Displays the object on the user's browser using the appropriate view template
     *	The appropriate view template is specific to the object's type
	*/
	function view () {
	
		if ($this->requiredFieldsValid (true)) {
		
			$smarty = new SSSmarty;
			$smarty->prepareUserVariables ();
			
			// Get story properties
			$ss_story = array ();
			$this->prepareSmartyVariables ($ss_story);
			
			// Get story history
			$versions = new SSVersionControl;
			$versions->setObject ($this);
			$ss_story['history'] = $versions->getHistory ();
			
			// Generate story view
			$storyPath = new SSStoryPath;
			$hierarchy = $storyPath->getHierarchy ($this);
			$id = -1;
			$ss_story ['hierarchy'] = $storyPath->generateHierarchyOutput ($hierarchy, $id);

			$ss_story['binary_preview'] = $this->getBinaryLinkOrPreview ();
			
			$smarty->assign ('ss_story', $ss_story);

			$smarty->display ('authoring/view_story.tpl');
		}
		else {
			$this->addError (STR_187, ERROR_TYPE_SERIOUS);
		}
	}
	
	/**  Retrieves the total number of scenes associated with this story
     *	This will return the total number of undeleted scenes in the story.
     *	@return int The total number of scenes in the story
	*/
	function getSceneCount () {
	
		// TODO: Come back to this
		return 0;
	}
	
	/**  Retrieves an array of forks that lead to the last scene in the story
     *	@return mixed Returns an array of SSFork objects or false if there are no end forks.
	*/
	function getEndForkList () {
		
		$cachedStory = $this->getCachedObject ();
		if ($cachedStory && (is_array ($cachedStory->_endForks))) {
			
			// The fork list has already been retrieved for this
			//	script so use what was already gotten.
			copyObject ($cachedStory, $this);
			return $this->_endForks;
		}
				
		$storyID = $this->get (PROP_ID);
		
		$fork = new SSFork;
		
		// Look for forks to the end of the story.
	 	$query = 'SELECT * FROM '.$GLOBALS[$fork->_getTableConstant()]['name'];
	 	$query .= ' WHERE '.$GLOBALS[$fork->_getTableConstant()]['fields']['STORY_ID'].'='.$storyID;
	 	$query .= ' AND '.$GLOBALS[$fork->_getTableConstant()]['fields']['FROM_SCENE'].'= -1';

		$results = $GLOBALS['DBASE']->getAssoc ($query);
		if (!DB::isError ($results)) {

			$forks = array ();
			foreach ($results as $key=>$row) {

				// The fork that will be added to the array
				$fork = new SSFork;
				
				// Add the key field to the array of fields.
				$results[$key][$fork->getUniqueID (true)] = $key;

				// Now load the data into the object.
				$fork->_setDBKeyValueArray ($results[$key]);

				// Add to the array of forks.
				array_push ($forks, $fork);
			}

			// Store the forks and cache the object so
			//	we can get this list later, if necessary
			$this->_endForks = $forks;
			$this->cacheObject ();

			if (count ($forks) == 0) {
				$forks = false;
			}
			
			return $forks;
		}
		else {
			$this->addErrorObject ($results, ERROR_TYPE_SERIOUS);
			return false;
		}
	}
	
		
	/** 
	 * Removes the delete status and replaces it with the draft status
	 * @return bool True if the scene was undeleted, false otherwise
	 */
	function undelete () {
	
		$this->set (PROP_STATUS, STORY_STATUS_DRAFT);
		if ($this->update ()) {
			$this->addNotification (sprintf (STR_188, $this->get (PROP_NAME)));
			return true;		
		}
	}
	
	/** 
	 * Deletes a scene by actually removing the record from the database
	 * @return bool True if the scene was deleted, false otherwise
	 */
	function trueDelete() {
	
		// First, make sure that there are no forks coming off of this scene
		$forks = $this->getForkList ();
		if (!$forks) {
			$this->_enableTrueDeletion (true);
			if ($this->delete ()) {
				return true;
			}
		}
		else {
			$this->addError (STR_189, ERROR_TYPE_SERIOUS);
		}
		
		return false;
	}
		
	/** Retrieves ALL scenes associated witht this story.
	 *
	 * @return mixed An array of scenes or false if none was found.
	 */
	function getAllScenes () {
		
		$scene = new SSScene;
		
		// Look for forks to the end of the story.
	 	$query = 'SELECT * FROM '.$GLOBALS[$scene->_getTableConstant()]['name'];
	 	$query .= ' WHERE '.$GLOBALS[$scene->_getTableConstant()]['fields']['STORYID'].'='.$this->get (PROP_ID);
	 	
		$results = $GLOBALS['DBASE']->getAssoc ($query);
		if (!DB::isError ($results)) {

			$scenes = array ();
			foreach ($results as $key=>$row) {

				// The fork that will be added to the array
				$scene = new SSScene;
				
				// Add the key field to the array of fields.
				$results[$key][$scene->getUniqueID (true)] = $key;

				// Now load the data into the object.
				$scene->_setDBKeyValueArray ($results[$key]);

				// Add to the array of forks.
				array_push ($scenes, $scene);
			}

			if (count ($scenes) == 0) {
				$scenes = false;
			}

			return $scenes;
		}
		else {
			$this->addErrorObject ($results, ERROR_TYPE_SERIOUS);
			return false;
		}
	}
	
	/** Verifies that all leaf scenes have an associated end fork if the story type is begin_end
	 *
	 * A story cannot be activated if there isn't an end fork 
	 * for each leaf scene
	 
	 * @return mixed If an array is returned then it contains all the scenes 
	 *			that do not have end forks.  If true is returned then all
	 *			scenes have end forks or the story type does not require it.
	 */
	function verifyLeafScenes () {
		
		// Get all scenes associated with the story.
		$scenes = $this->getAllScenes ();
		$scenesWithoutEndForks = array ();
		if (is_array ($scenes)) {
			foreach ($scenes as $scene) {
				
				if ($scene->isLeafScene()) {
					
					// We *can* allow leaf scenes that are branched off
					//	of END FORKs.  We don't require that these scenes
					//	have additional forks branching from them.
					$fork = $scene->getIncomingFork ();
					if (!$fork->isEndFork()) {
						if ($scene->get (PROP_END_FORK_ID) <= 0) {							
							array_push ($scenesWithoutEndForks, $scene);
						}
					}
				}
			}
		}
		
		if (count ($scenesWithoutEndForks) == 0) {
			return true;
		}
		else {
			return $scenesWithoutEndForks;
		}
	}

	/** 
	 * Activates a story after verifying correct properties
	 * @return bool Returns true if activated, false otherwise.
	 */
	function activate () {
		
		$status = $this->get (PROP_STATUS);
		$type = $this->get (PROP_TYPE);
		if ($status == STORY_STATUS_DRAFT) {

			if ($type == STORY_TYPE_BEGIN ||
				$type == STORY_TYPE_BEGIN_END) {		
					
				$forks = $this->getForkList ();
				
				$hasActiveFork = false;
				if (is_array ($forks)) {
					
					// Check for beginning fork
					/////////////////////////////////////////
					foreach ($forks as $fork) {
						
						if ($fork->get (PROP_STATUS) == FORK_STATUS_ACTIVE) {
							$hasActiveFork = true;
							break;
						}
					}
				}
				
				if (!$hasActiveFork) {
					
					$this->addError (STR_190, ERROR_TYPE_SERIOUS);
					return false;
				}
			
				// Check for ending fork if type requires it.
				/////////////////////////////////////////
				if ($type == STORY_TYPE_BEGIN_END) {
					
					$forks = $this->getEndForkList ();
					$hasActiveFork = false;
					
					if ($forks) {
						// Check for beginning fork
						foreach ($forks as $fork) {
							
							if ($fork->get (PROP_STATUS) == FORK_STATUS_ACTIVE) {
								$hasActiveFork = true;
								break;
							}
						}
					}
					
					if (!$hasActiveFork) {
						
						$this->addError (STR_191, ERROR_TYPE_SERIOUS);
						return false;
					}
				
					// Check for leaf scenes without end forks
					/////////////////////////////////////////

					// Now make sure that all leaf scenes in the story have a link to one of the
					//	end forks that exist for this story.  This is necessary to make sure that
					//	all streams run through from beginning to end.
					$scenesWithoutEnds = $this->verifyLeafScenes ();
					if (is_array ($scenesWithoutEnds)) {
												
						$msg = STR_192."\r\n";
						$msg .= '<ol>'."\r\n";
						foreach ($scenesWithoutEnds as $scene) {
							$link = '<a href="scene.php?a=view&scene_id='.$scene->get (PROP_ID).'">'.$scene->get (PROP_NAME).'</a>';
							$msg .= '<li>'.$link.'</li>'."\r\n";
						} 						
						$msg .= '</ol>'."\r\n";
						
						$this->addError ($msg, ERROR_TYPE_SERIOUS);
						return false;
					}					
				}				
			}
			
			$this->set (PROP_STATUS, STORY_STATUS_ACTIVE);
			if ($this->update ()) {
				
				// Notify the user that the story was successfully activated.
				$this->addNotification (sprintf (STR_520, $this->get (PROP_NAME)));
				
				// Notify users that the story is now available for viewing.
				$GLOBALS['NOTIFY']->sendStoryNotification ($this);
				
				return true;
			}
		}
		else {
		
			$this->addError (STR_193, ERROR_TYPE_SERIOUS);
		}
		
		return false;
	}

	/** 
	 * Sets the status of a story to draft after verifying correct properties
	 * @return bool Returns true if made a draft, false otherwise.
	 */
	function draft () {
	
		$status = $this->get (PROP_STATUS);
		$type = $this->get (PROP_TYPE);
		if ($status != STORY_STATUS_DRAFT) {
			
			$this->set (PROP_STATUS, STORY_STATUS_DRAFT);
			if ($this->update ()) {
				$this->addNotification ('The story called "'.$this->get (PROP_NAME).'" was successfully set to "DRAFT" status.');
			}
		}
		else {
		
			$this->addError (STR_194);
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
		
		if ($this->requiredFieldsValid (true)) {
			
			if ($this->get (PROP_STATUS) == STORY_STATUS_DELETED) {
				// This story is no longer available for reading 
				//	since it has been deleted.
				echo '<div align="center" style="font-size: 12px; font-weight: bold; color: #CC0000">
							'.STR_195.'
					  </div>';
				return;				
			}
			
			$smarty = new SSSmarty;
			$smarty->prepareUserVariables ();
			
			// Get story properties
			$ss_story = array ();
			$this->prepareSmartyVariables ($ss_story);
			
			
			// Prepare the rating forms/data
			$ss_story['rating'] = array ('is_rated'=>false);
			$rating = $this->getObjectRating();
			if ($rating) {				
				$array = array ();
				$rating->prepareSmartyVariables ($array);
				$array ['is_rated'] = true;
				$ss_story['rating'] = $array;
			}
			else {
				$rating = new SSRating;
				$rating->prepareFormTemplate ($smarty);
			}

			// Prepare the classification forms/data
			$ss_story['classification'] = array ('is_classified'=>false);
			$classification = $this->getObjectClassification();			
			if ($classification) {
				$array = array ();
				$classification->prepareSmartyVariables ($array);
				$array ['is_classified'] = true;
				$ss_story['classification'] = $array;				
			}
			else {
				$classify = new SSClassification;
				$classify->prepareFormTemplate ($smarty);
			}
				
			// Prepare the average review score
			$ss_story['avg_rating'] = $this->getAverageRating();
			
			$totalClassifications = 0;
			$ss_story['classification']['all'] = $this->getClassificationBreakdown ($totalClassifications);
			$ss_story['classification']['total'] = $totalClassifications;						
			
			$bmc = new SSBookmarkCollection();
			$bmc->prepareSmartyUserBookmarkList ($smarty);
			
			$smarty->assign ('ss_story', $ss_story);
			$GLOBALS['APP']->addViewRecord ($this);
			$smarty->display ('reading/read_story.tpl');
		}
		else {
			$this->addError (STR_197, ERROR_TYPE_SERIOUS);
		}
	}

	/** 
	 * Retrieves the object of the group in which this story is contained.
	 * @return mixed Returns the SSGroup object or false if there is no parent group.
	 */
	function getGroup () {
		if ($this->get (PROP_GROUP_ID) == 0) {
			return false;
		}
		
		return generateObject (OBJECT_TYPE_GROUP, $this->get (PROP_GROUP_ID));
	}
	
	/** 
	 * Checks to see if the user who is logged in is retricted from modifying
	 * the story because he/she is not a member of the group.
	 * @return bool Returns true if the user may modify the story, false otherwise.
	 */
	function loggedInUserHasGroupsPermission () {
		// Determine if the user is a member of the group to which
		//	this story belongs.
		$group = $this->getGroup ();
		if ($group) {
			$user = $GLOBALS['APP']->getLoggedInUserObject ();
			if ($user && ($group->isUserInGroup ($user) || ($user->get ('user_type') == USER_TYPE_ADMIN))) {
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return true;
		}
	}
	
	/** Determines if the logged in user has permission to edit the story
	 * 
	 *	Edit Permissions for stories are as follows:
	 *		Only the author and administrator can edit a story.
	 * 
	 * 	@return bool Returns true if permission is granted, false otherwise.
	 *  @access public
	 */
	function hasPermissionToEditStory () {
		
		// The author and the administrator can edit the story.
		$user = $GLOBALS['APP']->getLoggedInUserObject ();
		if ($user && 
			(($user->get ('username') == $this->get (PROP_USERNAME)) ||
					  ($user->get ('user_type') == USER_TYPE_ADMIN))) {
			return true;
		}
	}
		
	/** Determines if the logged in user has permission to add a scene to this story
	 * 
	 *	Add Permissions for scenes/forks in stories are as follows:
	 *		Only the author and administrator can add a new scene or fork
	 *			to the story.
	 * 
	 * @return bool Returns true if permission is granted, false otherwise.
	 *  @access public
	 */
	function hasPermissionToAddScene () {
		return $this->canAddToStory ();		
	}
	
	/** Determines if the logged in user has permission to view the story details
	 *	View Permissions for stories are as follows:
	 *		If the story is not active then only the administrator or the author
	 *			can view the story details.
	 *		If the story is in a group and active then only group members may 
	 *			view the story details.
	 *		If the story is not in a group and active then anyone may view the
	 *			story details.
	 * 	@return bool Returns true if permission is granted, false otherwise.
	 *  @access public
	 */
	function hasPermissionToViewStory () {
		
		// Right now, anyone who is registered has permission to view the story
		if ($GLOBALS['APP']->isUserLoggedIn ()) {			
			$user = $GLOBALS['APP']->getLoggedInUserObject ();
			
			// Verify that the story is either active or that the user is the
			//	author or an administrator.
			if ($this->get (PROP_STATUS) == STORY_STATUS_ACTIVE ||
				($user && 
					($user->get ('user_type') == USER_TYPE_ADMIN || 
					$user->get (PROP_USERNAME) == $this->get (PROP_USERNAME)))) {
						
				if ($this->loggedInUserHasGroupsPermission()) {
					return true;
				}
			}
		}
		
		return false;
	}
	
	/** Determines if the logged in user has permission to read the story details
	 *	Read Permissions for stories are as follows:
	 *		If the story is not active then only the administrator or the author
	 *			can read the story.
	 *		If the story is in a group and active and the group specifies that only
	 * 			only members may read the story then members can read the story.
	 *		If the story is in a group and active and the group specifies that anyone
	 * 			can read the story then anyone can read the story, including guests.
	 *		If the story is not in a group and active then anyone may read the
	 *			story.

	 * 	@return bool Returns true if permission is granted, false otherwise.
	 *  @access public
	 */
	function hasPermissionToReadStory () {
		
		$user = $GLOBALS['APP']->getLoggedInUserObject ();
		
		// If the story is not active then only the admin or the 
		//	author can read the story.
		if ($this->get (PROP_STATUS) == STORY_STATUS_ACTIVE ||
			($user && 
				($user->get ('user_type') == USER_TYPE_ADMIN || 
				$user->get (PROP_USERNAME) == $this->get (PROP_USERNAME)))) {

			// Now check if there are any group restrictions.  If there's no
			//	group associated with the story then there are no group 
			//	restrictions.
			if ($this->loggedInUserHasGroupsPermission()) {
				// members can always read group stories
				return true;
			}
			else {
				
				$group = $this->getGroup ();
				
				// non-members can only view group stories if
				//	allowed by group admin.
				if ($group->get (PROP_ALLOW_VIEW)) {
					return true;
				}
			}
		}
		
		return false;
	}	
	/** Determines if a user can add a scene or fork to this story
	 * 	Stories can only be added to if they are active or if the 
	 *	story is a draft and the user adding is the user who created
	 *	the story.
	 *	@return bool True if the story can be added to, false otherwise.
	 */
	function canAddToStory () {
	
		$user = $GLOBALS['APP']->getLoggedInUserObject ();
		if ($user) {
					
			if (!$this->loggedInUserHasGroupsPermission ()) {
				return false;
			}
			
			$isActive = ($this->get (PROP_STATUS) == STORY_STATUS_ACTIVE);
			$isOriginatingUserEditingDraft = 
				($this->get (PROP_STATUS) == STORY_STATUS_DRAFT) && 
				($user->get (PROP_USERNAME) == $this->get (PROP_USERNAME));

			
			return $isActive || $isOriginatingUserEditingDraft;				
		}
		
		return false;
	}		
	
	/** 
	 * Calculates the average rating for the story and contained scenes (optionally)
	 * @param bool $includeScenes If true, then the average will include all ratings for all scenes within the story
	 * @return float The average rating as a decimal number.
	 */
	function calculateAverageRating ($includeScenes) {
	
		$subjectTypeField = $GLOBALS['TABLE_RATING']['fields']['SUBJECT_TYPE'];
		$subjectIDField = $GLOBALS['TABLE_RATING']['fields']['SUBJECT_ID'];
		$storyIDField = $GLOBALS['TABLE_RATING']['fields']['STORY_ID'];
		$ratingField = $GLOBALS['TABLE_RATING']['fields']['RATING'];
		$ratingTable = $GLOBALS['TABLE_RATING']['name'];
		
		$query = "SELECT AVG($ratingField) AS average FROM $ratingTable 
					WHERE $storyIDField = ".$this->get(PROP_ID);
		
		// Do not include scene ratings in that story.
		if (!$includeScenes) {
			$query .= " AND $subjectTypeField = ".OBJECT_TYPE_STORY;
		}
		
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		if (!DB::isError ($results)) {
									
			$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
			while (($array = $resultObj->fetchRow ())) {
				return $array['average'];
			}
		}
		else {
			$this->addErrorObject ($results, ERROR_TYPER_SERIOUS);
		}		
	}
	
	/**
	 * Determines whether or not a notification should be 
	 * sent based on the permissions associated with the story/group
	 * @return bool Returns true if a notification should be sent.
	 */
	function shouldSendNotification () {

		// If the story is still in draft then don't send out a notification
		if ($this->get (PROP_STATUS) != STORY_STATUS_ACTIVE) {
			return false;
		}
				
		return true;
	}
	
	/** 
	 * Retrieves information about a property value
	 *	Information that is retrieved includes the following:
	 *	
	 *	'name' - The friendly name for the property
	 *	'diff' - Whether or not the property can be diffed against other versions
	 *
	 * @param string $key The name of the property
	 * @return array See the description for a list of the array contents.
	 */
	function getPropertyInfo ($key) {

		$name = '';
		$diff = false;
		$mapping = array ();
		
		switch ($key) {
			case PROP_ID:
				$name = 'story id';
				$diff = false;
				break;
			case PROP_NAME:
				$name = 'story name';
				$diff = false;
				break;
			case PROP_SYNOPSIS:
				$name = 'synopsis';
				$diff = true;
				break;
			case PROP_DESCRIPTION:
				$name = 'first scene text';
				$diff = true;
				break;
			case PROP_USERNAME:
				$name = 'author';
				$diff = false;
				break;
			case PROP_STATUS:
				$name = 'status';
				$mapping = array (STORY_STATUS_ACTIVE=>'active', STORY_STATUS_DRAFT=>'draft', STORY_STATUS_DELETED=>'deleted');
				$diff = false;
				break;
			case PROP_TYPE:
				$name = 'story type';
				$diff = false;
				break;
			case PROP_PHPBB_TOPIC_ID:
				$name = 'phpBB topic id';
				$diff = false;
				break;
			case PROP_RATING:
				$name = 'story rating';
				$diff = false;
				break;
			case PROP_PERMISSION:
				$name = 'permission';
				$diff = false;
				break;
			case PROP_GROUP_ID:
				$name = 'group id';
				$diff = false;
				break;
			default:
				return parent::getPropertyInfo ($key);
		}
		
		return array ('name'=>$name, 'mapping'=>$mapping, 'diff'=>$diff);
	}	
	
}
?>
