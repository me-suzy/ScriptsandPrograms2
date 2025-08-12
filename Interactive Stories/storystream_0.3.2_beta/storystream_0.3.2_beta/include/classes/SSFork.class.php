<?php

/** @file SSFork.class.php
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

/** Represents a single fork from one scene to another
	<p> 
  	The fork class is used to add, edit, view and otherwise
 	manipulate forks in the database.  It's also used to link
 	two scenes in a database.
*/
class SSFork extends SSTableObject
{
	var $_nextScenes = false;
	
	/** Constructor: Adds required properties */
	function SSFork () {
		parent::SSTableObject ();
	}	

	/** Retrieves the classes friendly type name
	 * @param bool $asPlural True to return the plural version, false otherwise.
	 * @return string The name as a string
	 *  @access public
	 */

	function getTypeName ($asPlural) {		
		return $asPlural ? 'forks' : 'fork';
	}

	/** Returns an object type code of OBJECT_TYPE_UNKNOWN if unspecified
	 * @return int The object type value
	 * @access public
	 */
	function getType () {
		return OBJECT_TYPE_FORK;
	}


	/** Adds all associated properties
	  *
	  *	This need only be called once per instantiation of this class
	  *	and is handled automatically by the base class as long
	  *	as its constructor is called.		
	  */
	function _addProperties () {

		$this->_addProperty (PROP_ID, 0);
		$this->_addProperty (PROP_USERNAME, 0);
		$this->_addProperty (PROP_NAME, '');
		$this->_addProperty (PROP_DESCRIPTION, '');
		$this->_addProperty (PROP_FROM_SCENE_ID, '');
		$this->_addProperty (PROP_STORY_ID, 0);
		$this->_addProperty ('start_mod_id', 0);
		$this->_addProperty ('last_mod_id', 0);
		$this->_addProperty (PROP_CHOSEN_COUNT, 0);
		$this->_addProperty (PROP_STATUS, FORK_STATUS_ACTIVE);
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
					$GLOBALS[$tableConstant]['fields']['USER_ID'] => $this->get (PROP_USERNAME),
					$GLOBALS[$tableConstant]['fields']['NAME'] => $this->get (PROP_NAME),
					$GLOBALS[$tableConstant]['fields']['DESCRIPTION'] => $this->get (PROP_DESCRIPTION),
					$GLOBALS[$tableConstant]['fields']['FROM_SCENE'] => $this->get (PROP_FROM_SCENE_ID),
					$GLOBALS[$tableConstant]['fields']['CHOSEN_COUNT'] => $this->get (PROP_CHOSEN_COUNT),
					$GLOBALS[$tableConstant]['fields']['START_MOD'] => $this->get ('start_mod_id'),
					$GLOBALS[$tableConstant]['fields']['LAST_MOD'] => $this->get ('last_mod_id'),
					$GLOBALS[$tableConstant]['fields']['STATUS'] => $this->get (PROP_STATUS),
					$GLOBALS[$tableConstant]['fields']['STORY_ID'] => $this->get (PROP_STORY_ID)
				);

		if ($includeDBKey) {
			$fields [$GLOBALS[$tableConstant]['fields']['ID']] = $this->get (PROP_ID);
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
		$this->set (PROP_USERNAME, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['USER_ID']]);
		$this->set (PROP_NAME, stripslashes (@$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['NAME']]));
		$this->set (PROP_DESCRIPTION, stripslashes (@$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['DESCRIPTION']]));
		$this->set (PROP_CHOSEN_COUNT, $dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['CHOSEN_COUNT']]);
		$this->set (PROP_STORY_ID, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['STORY_ID']]);
		$this->set ('start_mod_id', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['START_MOD']]);
		$this->set ('last_mod_id', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['LAST_MOD']]);
		$this->set (PROP_STATUS, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['STATUS']]);
		$this->set (PROP_FROM_SCENE_ID, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['FROM_SCENE']]);
		return $this->requiredFieldsValid ();
	}

	/**  Gets the associative array required to mark a record as deleted
     *	@param bool $checkKey If this is true, then the unique key field for the object is checked for validity as well
     *	@return array An associative (key>value) array populated with the keys/values required to mark the object as deleted
	 */
	function _getDBKeyValueForDelete () {

		$this->set (PROP_STATUS, FORK_STATUS_DELETED);
		$fields = array ($GLOBALS[$this->_getTableConstant()]['fields']['STATUS'] => FORK_STATUS_DELETED);								
		return $fields;
	}

	

	/**  Gets the key string for the associative array that contains field information for the table associated with this object.
     *	@see tables.inc.php
     *	@return string The key field string for the assocative array that contains field information for the table associated with this object.
	 */
	function _getTableConstant () {
		return 'TABLE_FORK';
	}

	/**  Checks if the data stored in this object is valid.
     *	All this does is verify that there is valid data in the *required*
     *	fields.
     *	@param bool $checkKey If this is true, then the unique key field for the object is checked for validity as well
     *	@return bool True, if all required fields are valid.
	 */
	function requiredFieldsValid ($checkKey = false) {
		$invalidField = false;

		if ($this->get (PROP_USERNAME) == '') {
			$this->addError (STR_47, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get (PROP_NAME) == '') {
			$this->addError (STR_48, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}

		if ($this->get (PROP_STATUS) == '') {
			$this->addError (STR_49, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}		
		
		if ($checkKey) {
			if ($this->get (PROP_ID) == '') {
				$this->addError (STR_50, ERROR_TYPE_SERIOUS);
				$invalidField = true;
			}
		}
		
		return !$invalidField;
	}

	/**  This will create a new fork in the database with the given connection attributes
     *	@param string $name The name of the fork as it will appear to the user
     *	@param int $storyID The story with which this is associated. REQUIRED.
     *	@param int $previousSceneID The scene from which this fork comes.  
     *					If 0, then there is no scene preceding the fork which happens when forking to a beginning scene from a story route.
     *					If -1, then this is a fork with no defined previous scene but must have one eventually to conclude the story.
     *	@param int $nextSceneID The scene to which this fork leads.  
     *					If this is 0 then this fork leads to the beginning of the story (an end-fork).
     *					If -1, then this is a fork that must eventually have a next scene but doesn't yet.
     *	@return bool Returns true if the fork was created, false otherwise.						
	 */
	function createFork ($name, $description, $storyID, $previousSceneID=0, $nextSceneID=0) {
		$this->set (PROP_NAME, $name);
		$this->set (PROP_DESCRIPTION, $description);

		$story = new SSStory;
		$story->set (PROP_ID, $storyID);
		if ($story->load ()) {
			$this->set (PROP_FROM_SCENE_ID, $previousSceneID);
			$this->set ('to_scene', $nextSceneID);
			$this->set (PROP_STORY_ID, $storyID);
			$this->set (PROP_STATUS, FORK_STATUS_ACTIVE);
			
			$user = $GLOBALS['APP']->getLoggedInUser();
			$this->set (PROP_USERNAME, $user ? $user->get (PROP_ID) : 0);
			
			return $this->add ();
		}
		else {
			$this->addError (STR_54, ERROR_TYPE_SERIOUS);
		}
		
		return false;
	}

	/**  Connects the fork to a next scene (or changes the existing one)'
     *	@param SSScene $scene The next scene in the fork
     *	@return bool True if the scene was connected, false otherwise.
	 */
	function setNextScene ($scene) {

		$this->set ('to_scene', $scene->get (PROP_ID));
		return $this->update ();
	}

	/**  Connects the fork to a previous scene (or changes the existing one)'
     *	@param SSScene $scene The previous scene in the fork
     *	@return bool True if the scene was connected, false otherwise.
	 */
	function setPreviousScene ($scene) {
	
		$this->set (PROP_FROM_SCENE_ID, $scene->get (PROP_ID));
		return $this->update ();
	}

	/**  Returns the scene that is the source of this fork.
     *	If there is no previous scene then this will return NULL.
     *	@return mixed If there is a previous scene then a SSScene object is returned.  Otherwise, NULL is returned
	 */
	function getPreviousScene () {
		$id = $this->get (PROP_FROM_SCENE_ID);
		if ($id > 0) {
			$scene = new SSScene;
			$scene->set (PROP_ID, $id);
			if ($scene->load ()) {
				return $scene;
			}
		}
		return NULL;
	}

	/**  Returns the story that is the source of this fork.
     *	If there is no source story then this will return NULL.
     *	@return mixed If there is a source story then an SSStory object is returned.  Otherwise, NULL is returned
	 */
	function getSourceStory() {
		$id = $this->get (PROP_STORY_ID);
		$story = new SSStory;
		$story->set (PROP_ID, $id);
		if ($story->load ($id)) {
			return $story;
		}
		else {
			return NULL;
		}
	}

	/**  Returns the scene that is the destination of this fork.
     *	If there is no previous scene then this will return NULL.  
     *	@return mixed If there is one or more previous scenes then an 
     *	 		array of SSScene object is returned.  Otherwise, false is returned
	 */
	function getNextScenes () {

		
		$cachedFork = $this->getCachedObject ();
		if ($cachedFork && (is_array ($cachedFork->_nextScenes))) {
			
			// The fork list has already been retrieved for this
			//	script so use what was already gotten.
			copyObject ($cachedFork, $this);	
			return $this->_nextScenes;
		}
		
		$scene = new SSScene;
		$query = 'SELECT * FROM '.$GLOBALS[$scene->_getTableConstant()]['name'].' WHERE ';
		$query .= $GLOBALS[$scene->_getTableConstant ()]['fields']['SOURCE_FORK'].'='.$this->get (PROP_ID);
		$results = $GLOBALS['DBASE']->getAssoc ($query);
		if (!DB::isError ($results)) {

			$scenes = array ();
			foreach ($results as $key=>$row) {

				// Create a new fork
				$fork = new SSScene;

				// Add the key field to the array of fields.
				$results[$key][$scene->getUniqueID (true)] = $key;

				// Now load the data into the object.
				$scene->_setDBKeyValueArray ($results[$key]);

				// Add to the array of forks.
				array_push ($scenes, $scene);
			}

			// Store the forks and cache the object so
			//	we can get this list later, if necessary
			$this->_endForks = $scenes;
			$this->cacheObject ();

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
	
	/**  Returns the story associated with this fork
     *	If there is no story then this will return NULL.  
     *	@return mixed If there is a story associated with this fork then a SSStory object is returned.  Otherwise, NULL is returned
	 */
	function getStory () {
		
		$id = $this->get (PROP_STORY_ID);
		if ($id > 0) {
			$story = new SSStory;
			$story->set (PROP_ID, $id);
			if ($story->load ()) {
				return $story;
			}
		}
		return NULL;
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

    /**
     * Converts the status code to a human-readable string
     * 
     * @return string The status code string or 'UNKNOWN STATUS CODE' if invalid
     */
    function statusCodeToString ($code)
    {
        switch ($code) {
            case FORK_STATUS_ACTIVE:
                return 'Active';
            case FORK_STATUS_DELETED:
                return 'Deleted';
            default :
                return 'UNKNOWN STATUS CODE';
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

		$array ['name'] = $this->get (PROP_NAME);
		$array ['id'] = $this->get (PROP_ID);
		$array ['user_id'] = $this->get (PROP_USERNAME);
		$array ['description'] = $this->get (PROP_DESCRIPTION);
		$array ['from_scene_id'] = $this->get (PROP_FROM_SCENE_ID);		
		$array ['start_mod_id'] = $this->get ('start_mod_id');
		$array ['last_mod_id'] = $this->get ('last_mod_id');
		$array ['story_id'] = $this->get (PROP_STORY_ID);
		$array ['chosen_count'] = $this->get (PROP_CHOSEN_COUNT);
        $array ['status'] = $this->statusCodeToString ($this->get (PROP_STATUS));
		$array ['status_values'] = array ('active'=>FORK_STATUS_ACTIVE,'deleted'=>FORK_STATUS_DELETED);
		$array ['status_int'] = $this->get (PROP_STATUS);
		$array ['object_type'] = $this->getType();
		$scene = $this->getPreviousScene ();
		
		// Get previous scene in the list.		
		if ($scene) {
			$sceneProps = array ();
			if ($getPropertiesOnly) {
				$sceneProps ['id'] = $scene->get (PROP_ID);
				$sceneProps ['name'] = $scene->get (PROP_NAME);
				$sceneProps ['description'] = $scene->get (PROP_DESCRIPTION);
				$sceneProps ['status_int'] = $scene->get (PROP_STATUS);
			}
			else {
				$scene->prepareSmartyVariables ($sceneProps);
			}
			$array ['from_scene'] = $sceneProps;
		}
		else {
			// There is no from scene
		    $array ['from_scene']['name'] = 'None';
			$array ['from_scene']['description'] = '';
			$array ['from_scene']['status_int'] = 0;
		}

		// Get the next scene in the list.
		$scenes = $this->getNextScenes();
		$array ['to_scenes'] = array ();
		if ($scenes) {
			foreach ($scenes as $scene) {
				$sceneProps = array ();				
				if ($getPropertiesOnly) {
					$sceneProps ['id'] = $scene->get (PROP_ID);
					$sceneProps ['name'] = $scene->get (PROP_NAME);
					$sceneProps ['description'] = $scene->get (PROP_DESCRIPTION);
					$sceneProps ['status_int'] = $scene->get (PROP_STATUS);
				}
				else {
					$scene->prepareSmartyVariables ($sceneProps);
				}
				array_push ($array ['to_scenes'], $sceneProps);
			}
		}
		
		// Setup which actions can be performed on this story
		$array['actions'] = array ();
		$array['actions']['edit_fork'] = $this->hasPermissionToEditFork ();
		$array['actions']['delete_fork'] = $this->hasPermissionToEditFork ();
		$array['actions']['add_scene'] = $this->hasPermissionToAddScene ();
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
	function displayForm ($isEditForm, $data, $afterError=false) {

		$smarty = new SSSmarty;
		$smartyScene = array ();

		// Get the last used form data (if any).
		$memObj = $GLOBALS['APP']->retrieveValue ('SCENE_ADDEDIT_OBJ');

		// Which story is this for?
		$this->set (PROP_STORY_ID, $GLOBALS['APP']->queryValue ('story_id'));
		$forkID = $GLOBALS['APP']->queryValue (PAGE_ACTION_FORK_ID);
		$this->set (PROP_FROM_SCENE_ID, $GLOBALS['APP']->queryValue (PAGE_ACTION_SCENE_ID));
		
		if ($isEditForm) {

			$this->set (PROP_ID, $GLOBALS['APP']->queryValue (PAGE_ACTION_FORK_ID));
			if ($this->load ()) {

				// If there was a submit error then use
				//	the values that were previously entered
				//	instead of those that are in the database.
				if ($afterError && $memObj) {

					$defaultTitle = $memObj->get (PROP_NAME);
					$defaultDescription = $memObj->get (PROP_DESCRIPTION);
					$defaultStatus = $memObj->get (PROP_STATUS);

					$this->set (PROP_NAME, $defaultTitle);
					$this->set (PROP_DESCRIPTION, $defaultDescription);
					$this->set (PROP_STATUS, $defaultStatus);
					$this->set (PROP_DATA_TYPE, $defaultDataType);
				}

				$this->prepareFormTemplate ($smarty, true);
			}
		}
		else {

			// When adding,we can just copy over the entire object if
			//	there was a submit error.
			if ($afterError && $memObj) {
			    copyObject ($memObj, $this);	
			}
			else {				
				$user = $GLOBALS['APP']->getLoggedInUserObject ();
				if ($user) {
					$this->set (PROP_USERNAME, $user->get ('username'));
				}
			}
				
			$storyID = $this->get (PROP_STORY_ID);
			$story = generateObject (OBJECT_TYPE_STORY, $storyID);
			if ($story) {
			
				// Can only add to a story that is active or is in 
				//	draft and the user adding to the story is the user
				//	who created the story.
				if ($story->canAddToStory ()) {
				
					// If we're adding a fork to a scene then the 
					//	scene must be active or in draft mode and 
					//	the user logged in must be the one who created the
					//	scene.
					if ($this->get (PROP_FROM_SCENE_ID) > 0) {
						$scene = generateObject (OBJECT_TYPE_SCENE, $this->get (PROP_FROM_SCENE_ID));
						if ($scene) {
							if (!$scene->canAddToScene ()) {
								$this->addError (STR_54, ERROR_TYPE_SERIOUS); 
								return false;
							}
						}
					}
					
					// This is just an add so there's no need to prepopulate
					$this->prepareFormTemplate ($smarty, false);
				}
				else {
					$this->addError (STR_55, ERROR_TYPE_SERIOUS);
					return false;
				}
			}
			else {
				$this->addError (STR_56, ERROR_TYPE_SERIOUS);
				return false;
			}
		}
				
		$smarty->display ('authoring/form_fork.tpl');
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

		// Prepare the action information and whether or not
		//	to display the
		$forkProperties = array ();
		$formProperites = array ();
		$formProperties['edit'] = $editForm;
		$smarty->assign ('ss_form', $formProperties);

		// Prepare story
		$story = new SSStory;
		$story->set (PROP_ID, $this->get (PROP_STORY_ID));
		if ($story->load ()) {

			$storyProperties = array ();
			$story->prepareSmartyVariables ($storyProperties);
			$smarty->assign ('ss_story', $storyProperties);

			// Prepare type selection array
			$statusNames = array ('Active', 'Deleted');
			$typeValues = array (FORK_STATUS_ACTIVE, FORK_STATUS_DELETED);

			// Is initial fork
			$sceneProperties = array ();
			if ($this->get (PROP_FROM_SCENE_ID) > 0) {
				
				// This is not an initial fork so get the scene
				//	properties
				$scene = new SSScene;
				$scene->set (PROP_ID, $this->get (PROP_FROM_SCENE_ID));
				if ($scene->load ()) {
					$scene->prepareSmartyVariables ($sceneProperties,false);
				}
			}
			
			$smarty->assign ('ss_scene', $sceneProperties);

			// Prepare the type array.
			$forkProperties['status_select']['output'] = $statusNames;
			$forkProperties['status_select']['values'] = $typeValues;

			$this->prepareSmartyVariables ($forkProperties);
			$smarty->assign ('ss_fork', $forkProperties);
		}
		else {
			$this->addError (STR_57, ERROR_TYPE_SERIOUS);
		}
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
		// Make sure that everything went okay with the load
		//	before processing the edit.
		if (!$editForm || ($editForm && $this->get (PROP_ID) > 0)) {

			// Now set the new values
			$this->set (PROP_NAME, strip_tags ($GLOBALS['APP']->queryPostValue ('name')));
			$this->set (PROP_USERNAME, $GLOBALS['APP']->queryPostValue ('user_id'));
			$this->set (PROP_DESCRIPTION, strip_tags ($GLOBALS['APP']->queryPostValue ('description')));
			$this->set (PROP_STATUS, $GLOBALS['APP']->queryPostValue ('status'));
						
			$fromSceneID = $GLOBALS['APP']->queryPostValue (PAGE_ACTION_SCENE_ID);
			$storyID = $GLOBALS['APP']->queryPostValue ('story_id');
			$story = NULL;
			$scene = NULL;
			
			if ($fromSceneID > 0) {
				// Make  sure that the from scene is valid.  0 is valid because that
				//	means that this is a beginning fork.
				$scene = generateObject (OBJECT_TYPE_SCENE, $fromSceneID);
				if (!$scene || ($scene->get ('status') == SCENE_STATUS_DELETED)) {
					$this->addError ('You cannot add a fork to a scene that has been marked deleted or does not exist.');
					return false;
				}
			}
			else {
				// Make  sure that the from scene is valid.  0 is valid because that
				//	means that this is a beginning fork.
				$story = generateObject (OBJECT_TYPE_STORY, $storyID);
				if (!$story || ($story->get ('status') == STORY_STATUS_DELETED)) {
					$this->addError ('You cannot add a fork to a story that has been marked deleted or does not exist.');
					return false;
				}				
			}
			
			$this->set (PROP_FROM_SCENE_ID, $fromSceneID);
			$this->set (PROP_STORY_ID, $storyID);
			$this->set ('start_mod_id', $GLOBALS['APP']->queryPostValue ('start_mod_id'));
			$this->set ('last_mod_id', $GLOBALS['APP']->queryPostValue ('last_mod_id'));

			// Keep track of the values in case we need to return to the form
			$GLOBALS['APP']->rememberValue ('FORK_ADDEDIT_OBJ', $this);
			if (!$editForm) {
				
				// Verify degree count will allow the addition of another fork.
				if ($story) {
					// We only have to verify the degree count if the user
					//	adding a middle fork (not an end or beginning fork)
					if ($this->get (PROP_FROM_SCENE_ID) > 0) {
						$maxDegrees = $story->get ('degrees');
											
						$path = new SSStoryPath;
						$forkCount = $path->getForkCountFromScene ($this->get (PROP_FROM_SCENE_ID));
											
						if ($forkCount == ($maxDegrees - 1)) {
							
							// The user has reached the maximum number of forks
							$this->addError (sprintf (STR_58, $maxDegrees), ERROR_TYPE_SERIOUS);
							return false;
						}
					}
				}
				
				// Verify that we're not adding a fork to a scene that is 
				//	based on an end fork already.
				if ($this->get (PROP_FROM_SCENE_ID) > 0) {
					if ($scene) {
						$fork = $scene->getIncomingFork ();
						if ($fork) {
							if ($fork->get (PROP_FROM_SCENE_ID) == -1) {
								$this->addError (STR_59, ERROR_TYPE_SERIOUS);
								return false;
							}
						}
					}
				}
			}
				
			// And update the database with the new/changed record.
			if ($editForm) {
				return $this->update ();
			}
			else {
				return $this->add ();
			}
		}
		else {
			$this->addError (STR_60, ERROR_TYPE_SERIOUS);
		}

		return false;
	}

	/**  Displays the object on the user's browser using the appropriate view template
     *		The appropriate view template is specific to the object's type
	 */
	function view () {
		
		if ($this->requiredFieldsValid (true)) {
			
			$ss_fork = array ();
			$this->prepareSmartyVariables ($ss_fork);

			$storyPath = new SSStoryPath;
			$trace = $storyPath->backtrackFromFork ($this->get (PROP_ID));
			$ss_fork['breadcrumb'] = $storyPath->generateBreadcrumbString ($trace);

			$smarty = new SSSmarty;
			$smarty->assign ('ss_fork', $ss_fork);
			
			// Setup the from-scene properties for display
			$scene = $this->getPreviousScene ();
			$sceneProperties = array ();
			if ($scene) {
			 	$scene->prepareSmartyVariables ($sceneProperties, true);
			}
			else {
			 	$sceneProperties ['id'] = 0;
			}

			$smarty->assign ('ss_from_scene', $sceneProperties);

			// Setup the parent story properties
			$story = new SSStory;
			$story->set (PROP_ID, $this->get (PROP_STORY_ID));
			if ($story->load ()) {
				
			 	$storyProperties = array ();
			 	$story->prepareSmartyVariables ($storyProperties);
			 	$smarty->assign ('ss_story', $storyProperties);
			 	
			 	// Note the fork view in the dbase and display
				echo $smarty->display ('authoring/view_fork.tpl');
			}
			else {
            	$this->addError (STR_61, ERROR_TYPE_SERIOUS);
			}
		}
		else {
			$this->addError (STR_62, ERROR_TYPE_SERIOUS);
		}
	}

	/** Verifies that the fork can be deleted before calling parent.
	 * We have to make sure that if the user wants to delete this fork then
	 * the associated story doesn't have the following status/type combo:
	 *	1) Active
	 *	2) Beginning Scene or Beginning and End Scene.
	 * 	3) This fork is the only beginning scene.
	 *  
	 * @return bool True if deleted, false otherwise.
	*/
	function delete () {
		// First, get the story.
		$story = $this->getStory ();
		if ($story) {

			// See if this story is meant to have a beginning fork
			//	and whether or not it's active.
			if (($story->get (PROP_TYPE) != STORY_TYPE_NO_BEGIN_NO_END) &&
				($story->get (PROP_STATUS) == STORY_STATUS_ACTIVE)) {

				// Check for 2 or more active forks.
				$forks = $story->getForkList ();
				$activeForks = array ();	
				foreach ($forks as $fork) {
					if ($fork->get (PROP_STATUS) == FORK_STATUS_ACTIVE &&
						$fork->get (PROP_ID) != $this->get (PROP_ID)) {
						array_push ($activeForks, $fork);
					}
				}
				
				if (count ($activeForks) == 0) {				
					$this->addError (STR_63, ERROR_TYPE_SERIOUS);
					return false;
				}
			}
		}
		return parent::delete ();
	}

	/** 
	 * Removes the delete status and replaces it with the draft status
	 * @return bool True if the scene was undeleted, false otherwise
	 */
	function undelete () {
		$this->set (PROP_STATUS, FORK_STATUS_ACTIVE);
		if ($this->update ()) {
			$this->addNotification (sprintf (STR_64, $this->get(PROP_NAME)));
			return true;		
		}
	}

	/** 
	 * Deletes a scene by actually removing the record from the database
	 * @return bool True if the scene was deleted, false otherwise
	 */
	function trueDelete() {

		// First, make sure that there are no forks coming off of this scene
		$scenes = $this->getNextScenes ();
		if (!$scenes) {
			$this->_enableTrueDeletion (true);
			if ($this->delete ()) {
				return true;
			}
		}
		else {
			$this->addError (STR_65, ERROR_TYPE_SERIOUS);
		}

		return false;
	}
	/** Determines if this fork is set as an end fork (the last in the stream)
	 *
	 * The end fork is determined by the from_scene_id property. If
	 *	it is set to -1 then it is an end fork.
	 *
	 * @return bool True if this is an end fork.
	 */
	function isEndFork () {
		return ($this->get (PROP_FROM_SCENE_ID) == -1);
	}
	
	/** Determines if the logged in user has permission to add a fork to this scene
	 *
	 * @return bool Returns true if permission is granted, false otherwise.
	 *  @access public
	 */
	function hasPermissionToEditFork () {
		
		// The author and the administrator can edit the fork.
		$user = $GLOBALS['APP']->getLoggedInUserObject ();
		if ($user && (($user->get ('username') == $this->get (PROP_USERNAME)) ||
					  ($user->get ('user_type') == USER_TYPE_ADMIN))) {
			return true;
		}
	}

	/** Determines if the logged in user has permission to add a scene to the fork.
	 *
	 * 	@return bool Returns true if permission is granted, false otherwise.
	 *  @access public
	 */
	function hasPermissionToAddScene () {
		
		$story = new SSStory;
		$story->set (PROP_ID, $this->get (PROP_STORY_ID));
		if ($story->load ()) {			
			if ($story->hasPermissionToAddScene()) {
				return true;
			}
		}
		return false;
	}
	
	/** Determines if the logged in user has permission to view the fork details
	 *
	 * 	@return bool Returns true if permission is granted, false otherwise.
	 *  @access public
	 */
	function hasPermissionToViewFork () {
		
		// Right now, anyone who is registered has permission to view the fork
		if ($GLOBALS['APP']->isUserLoggedIn ()) {
			return true;
		}
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
		
			$smarty = new SSSmarty;
			$smarty->prepareUserVariables ();
		
			// Get fork properties
			$ss_fork = array ();
			$this->prepareSmartyVariables ($ss_fork);
						
			$storyPath = new SSStoryPath;
			$trace = $storyPath->backtrackFromFork ($this->get (PROP_ID));
			$ss_fork['breadcrumb'] = $storyPath->generateBreadcrumbString ($trace, true);

			$smarty->assign ('ss_fork', $ss_fork);
			$smarty->display ('reading/read_fork.tpl');
		}
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
				$name = 'scene id';
				$diff = false;
				break;
			case PROP_NAME:
				$name = 'fork name';
				$diff = false;
				break;
			case PROP_DESCRIPTION:
				$name = 'description';
				$diff = true;
				break;
			case PROP_USERNAME:
				$name = 'author';
				$diff = false;
				break;
			case PROP_STATUS:
				$name = 'status';
				$mapping = array (FORK_STATUS_ACTIVE=>'active', FORK_STATUS_DRAFT=>'draft', SCENE_STATUS_DELETED=>'deleted');
				$diff = false;
				break;
			case PROP_FROM_SCENE_ID:
				$name = 'from scene id';
				$diff = false;
				break;
			case PROP_STORY_ID:
				$name = 'story id';
				$diff = false;
				break;		
			default:
				return parent::getPropertyInfo ($key);
		}
		
		return array ('name'=>$name, 'mapping'=>$mapping, 'diff'=>$diff);
	}		
}

?>
