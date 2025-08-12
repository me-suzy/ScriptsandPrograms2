<?php
/**
 * @file SSScene.class.php
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
 * @version 0.1
 * @date October, 2003
 */

/**
 * Represents a single scene in the database
 * The scene class is used to add, edit, view and otherwise
 * manipulate scenes in the database
 */
class SSScene extends SSContentObject {

	var $_nextForks = false;
	
    /**
     * Constructor: Adds required properties
     */
    function SSScene ()
    {
        parent::SSTableObject ();
    } 

	/** Retrieves the classes friendly type name
	 * @param bool $asPlural True to return the plural version, false otherwise.
	 * @return string The name as a string
	*  @access public
	 */
	function getTypeName ($asPlural) {		
		return $asPlural ? STR_138 : STR_139;
	}

	/** Returns an object type code of OBJECT_TYPE_UNKNOWN if unspecified
	 * @return int The object type value
	 * @access public
	 */
	function getType () {
		return OBJECT_TYPE_SCENE;
	}

    /**
     * Adds all associated properties
     * This need only be called once per instantiation of this class
     * and is handled automatically by the base class as long
     * as its constructor is called.
     */
    function _addProperties ()
    {
        $this->_addProperty (PROP_ID, 0);
        $this->_addProperty (PROP_NAME, '');
        $this->_addProperty (PROP_DESCRIPTION, '');
        $this->_addProperty (PROP_USERNAME, '');
        $this->_addProperty (PROP_STORY_ID, 0);
        $this->_addProperty (PROP_SOURCE_FORK_ID, 0);
		$this->_addProperty (PROP_END_FORK_ID, 0);
        $this->_addProperty (PROP_STATUS, 0);
        $this->_addProperty (PROP_TYPE, 0);
        $this->_addProperty (PROP_DATA_TEXT, '');
		$this->_addProperty (PROP_PHPBB_TOPIC_ID, '');

        $this->_addProperty ('rating', 0);
        $this->_addProperty ('start_mod_id', 0);
        $this->_addProperty ('last_mod_id', 0);
		
		parent::_addProperties ();
    } 

    /**
     * Gets all the database field names with associated values
     * This will return an associative array where the keys
     * are the table field names and the values are the values
     * stored in this class.
     * @param bool $includeDBKey If true, then the key field for the table is returned in the array as well.
     * @return array An associative array of dbase fields => class values
     */
    function getDBKeyValueArray ($includeDBKey)
    {
        $tableConstant = $this->_getTableConstant();

        $fields = array
        (	
        	$GLOBALS[$tableConstant]['fields']['NAME'] => $this->get (PROP_NAME),        	
            $GLOBALS[$tableConstant]['fields']['DESCRIP'] => $this->get (PROP_DESCRIPTION),
            $GLOBALS[$tableConstant]['fields']['USER'] => $this->get (PROP_USERNAME),
            $GLOBALS[$tableConstant]['fields']['TYPE'] => $this->get (PROP_TYPE),
            $GLOBALS[$tableConstant]['fields']['RATING'] => $this->get (PROP_RATING),
            $GLOBALS[$tableConstant]['fields']['STORYID'] => $this->get (PROP_STORY_ID),
            $GLOBALS[$tableConstant]['fields']['SOURCE_FORK'] => $this->get (PROP_SOURCE_FORK_ID),
            $GLOBALS[$tableConstant]['fields']['END_FORK'] => $this->get (PROP_END_FORK_ID),
            $GLOBALS[$tableConstant]['fields']['START_MOD'] => $this->get ('start_mod_id'),
            $GLOBALS[$tableConstant]['fields']['LAST_MOD'] => $this->get ('last_mod_id'),
            $GLOBALS[$tableConstant]['fields']['STATUS'] => $this->get (PROP_STATUS),
            $GLOBALS[$tableConstant]['fields']['DATA_TEXT'] => $this->get (PROP_DATA_TEXT),
            $GLOBALS[$tableConstant]['fields']['DATA_TYPE'] => $this->get (PROP_DATA_TYPE),
            $GLOBALS[$tableConstant]['fields']['DATA_BINARY'] => $this->get (PROP_DATA_BINARY),
            $GLOBALS[$tableConstant]['fields']['DATA_PROPS'] => $this->get (PROP_DATA_PROPERTIES),
            $GLOBALS[$tableConstant]['fields']['PHPBB_TOPIC_ID'] => $this->get (PROP_PHPBB_TOPIC_ID),
            $GLOBALS[$tableConstant]['fields']['LICENSE_URL'] => $this->get (PROP_LICENSE_URL),
            $GLOBALS[$tableConstant]['fields']['LICENSE_NAME'] => $this->get (PROP_LICENSE_NAME),
            $GLOBALS[$tableConstant]['fields']['LICENSE_CODE'] => $this->get (PROP_LICENSE_CODE)
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
        $this->set (PROP_NAME, stripslashes (@$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['NAME']]));
        $this->set (PROP_DESCRIPTION, stripslashes (@$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['DESCRIP']]));
        $this->set (PROP_USERNAME, $dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['USER']]);
        $this->set (PROP_TYPE, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['TYPE']]);
        $this->set (PROP_STORY_ID, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['STORYID']]);
        $this->set (PROP_STATUS, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['STATUS']]);
        $this->set (PROP_PHPBB_TOPIC_ID, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['PHPBB_TOPIC_ID']]);
		
        $this->set (PROP_SOURCE_FORK_ID, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['SOURCE_FORK']]);
		$this->set (PROP_END_FORK_ID, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['END_FORK']]);
        $this->set (PROP_DATA_TEXT, stripslashes (@$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['DATA_TEXT']]));
        $this->set (PROP_DATA_TYPE, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['DATA_TYPE']]);
        $this->set (PROP_DATA_BINARY, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['DATA_BINARY']]);
        $this->set (PROP_DATA_PROPERTIES, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['DATA_PROPS']]);
		$this->set (PROP_LICENSE_URL, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['LICENSE_URL']]);
		$this->set (PROP_LICENSE_NAME, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['LICENSE_NAME']]);
		$this->set (PROP_LICENSE_CODE, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['LICENSE_CODE']]);

        $this->set ('rating', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['RATING']]);
        $this->set ('start_mod_id', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['START_MOD']]);
        $this->set ('last_mod_id', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['LAST_MOD']]);
		
        return $this->requiredFieldsValid ();
    } 

    /**
     * Gets the story object associated with this scene
     * 
     * @return mixed referenced to the story object as stored in the scene.
     */
    function _getStoryObject ()
    {
        $story = new SSStory;
		$story->set (PROP_ID, $this->get (PROP_STORY_ID));
        if ($story->load ()) {
            return $story;			
        } 
		return NULL;
    } 

    /**
     * Gets the associative array required to mark a record as deleted
     * @param bool $checkKey If this is true, then the unique key field for the object is checked for validity as well
     * @return array An associative (key>value) array populated with the keys/values required to mark the object as deleted
     */
    function _getDBKeyValueForDelete ()
    {
		$this->set (PROP_STATUS, SCENE_STATUS_DELETED);
        $fields = array ($GLOBALS[$this->_getTableConstant()]['fields']['STATUS'] => SCENE_STATUS_DELETED);
        return $fields;
    } 

    /**
     * Gets the key string for the associative array that contains field information for the table associated with this object.
     * @see tables.inc.php
     * @return string The key field string for the assocative array that contains field information for the table associated with this object.
     */
    function _getTableConstant () {
        return 'TABLE_SCENE';
    } 

    /**
     * Checks if the data stored in this object is valid.
     * All this does is verify that there is valid data in the *required*
     * fields.
     * @param bool $checkKey If this is true, then the unique key field for the object is checked for validity as well
     * @return bool True, if all required fields are valid.
     */
    function requiredFieldsValid ($checkKey = false) {
        $invalidField = false;

        if ($this->get (PROP_NAME) == '') {
            $this->addError (STR_140, ERROR_TYPE_SERIOUS);
            $invalidField = true;
        } 
		
        if ($this->get (PROP_STORY_ID) == '') {
            $this->addError (STR_144, ERROR_TYPE_SERIOUS);
            $invalidField = true;
        } 
        if ($this->get('source_fork_id') == '') {
            $this->addError (STR_141, ERROR_TYPE_SERIOUS);
            $invalidField = true;
        } 
        if ($this->get (PROP_STATUS) == '') {
            $this->addError (STR_142, ERROR_TYPE_SERIOUS);
            $invalidField = true;
        } 

        if ($checkKey) {
            if ($this->get (PROP_ID) == '') {
                $this->addError (STR_143, ERROR_TYPE_SERIOUS);
                $invalidField = true;
            } 
        } 

        return !$invalidField;
    } 

    /**
     * Displays the scene add/edit form in the client
     * This will take into account if the form should be an edit
     * form or an new scene form.  The data paramter is used to indicate
     * whether or not the scene being added/edited is a beginning, end or
     * middle scene.  It can be one of the following values:
     * SCENE_TYPE_BEGINNING
     * SCENE_TYPE_ENDING
     * SCENE_TYPE_MIDDLE
     * @param bool $isEditForm True if the form to be displayed is for editng an existing scene.
     * It's false if the scene will be added as a new one.
     * @param mixed $data Associated data with the form - see description for more on this
     * @param bool $afterError True if the form is being displayed after an error occured in a previous submit.
     * @return bool Returns true if the form was displayed successfully.
     */
    function displayForm ($isEditForm, $data, $afterError = false)
    {
        $smarty = new SSSmarty;
        $smartyScene = array (); 
        // Get the last used form data (if any).
        $memObj = $GLOBALS['APP']->retrieveValue ('SCENE_ADDEDIT_OBJ'); 
		
        // Which story is this for?
        $this->set (PROP_STORY_ID, $GLOBALS['APP']->queryValue (PAGE_ACTION_STORY_ID));
		
		// Which fork are we adding to?
        $forkID = $GLOBALS['APP']->queryValue (PAGE_ACTION_FORK_ID);
		
        if ($isEditForm) {
            $this->set (PROP_ID, $GLOBALS['APP']->queryValue (PAGE_ACTION_SCENE_ID));
			
            if ($this->load ()) {
                // If there was a submit error then use
                // the values that were previously entered
                // instead of those that are in the database.
                if ($afterError && $memObj) {
                    $defaultTitle = $memObj->get (PROP_NAME);
                    $defaultDescription = $memObj->get (PROP_DESCRIPTION);
                    $defaultStatus = $memObj->get (PROP_STATUS);
                    $defaultDataType = $memObj->get (PROP_DATA_TYPE);
					$defaultEndFork = $memObj->get (PROP_END_FORK_ID);

                    $this->set (PROP_NAME, $defaultTitle);
                    $this->set (PROP_DESCRIPTION, $defaultDescription);
                    $this->set (PROP_STATUS, $defaultStatus);
                    $this->set (PROP_DATA_TYPE, $defaultDataType);
                } 

                $this->prepareFormTemplate ($smarty, true);
            } 
        } else {
            // When adding,we can just copy over the entire object if
            // there was a submit error.
            if ($afterError && $memObj) {
                copyObject ($memObj, $this);
            } 
			else {
				// Set the fork from which this scene will be created.
				$this->set (PROP_SOURCE_FORK_ID, $forkID);
			}
			
			$fork = generateObject (OBJECT_TYPE_FORK, $forkID);
			if ($fork) {
			
				$storyID = $fork->get (PROP_STORY_ID);
				$story = generateObject (OBJECT_TYPE_STORY, $storyID);
				if ($story) {
				
					// Can only add to a story that is active or is in 
					//	draft and the user adding to the story is the user
					//	who created the story.
					if ($story->canAddToStory ()) {
						// This is just an add so there's no need to prepopulate
						$this->prepareFormTemplate ($smarty, false);
					}
					else {
						$this->addError (STR_145, ERROR_TYPE_SERIOUS);
						return false;
					}
				}
				else {
					$this->addError (STR_146, ERROR_TYPE_SERIOUS);
					return false;
				}
			}
			else {
				$this->addError (STR_147, ERROR_TYPE_SERIOUS);
				return false;
			}
        } 

        $smarty->display ('authoring/form_scene.tpl');
    } 

	/**
	 * Adds smarty variables to the given smarty object to prepare 
	 *  the scene form for display. 
	 * 
	 * @param Smarty $smarty The smarty object to get the details of the form
	 * @param bool $editForm Whether or not we're constructing an edit form or a creation form.
	 **/
	function prepareFormTemplate (&$smarty, $editForm) {

		// Prepare the action information and whether or not
		//	to display the
		$sceneProperties = array ();
		$formProperties = array ();
		$formProperties['edit'] = $editForm;

		$smarty->assign ('ss_form', $formProperties);

		// Prepare the status array.
		$sceneProperties['status']['values'] = array (SCENE_STATUS_DRAFT, SCENE_STATUS_ACTIVE);
		$sceneProperties['status']['output'] = array ('Draft', 'Active');
		$sceneProperties['status']['selected'] = $this->get (PROP_STATUS);

		// Prepare the fork list
		$sceneProperties['fork_names'] = array ();
		$sceneProperties['fork_ids'] = array ();
		$forks = $this->getOutgoingForks ();
		if (is_array ($forks)) {
			foreach ($forks as $fork) {
				$sceneProperties['fork_names'][] = $fork->get (PROP_NAME);
				$sceneProperties['fork_ids'][] = $fork->get (PROP_ID);
			}
		}
		

		// Whether or not to display the writing toolbar
		$sceneProperties['show_toolbar'] = true;
		$browser = new SSBrowserCap ('', true);
		if (strpos ($browser->property('long_name'), 'safari') !== false) {
			$sceneProperties['show_toolbar'] = false;
		}		
		
		// Prepare the data type array.
		$sceneProperties['data']['values'] = array (SCENE_DATA_NONE, SCENE_DATA_IMAGE, SCENE_DATA_FLASH, SCENE_DATA_SOUND);
		$sceneProperties['data']['output'] = array ('Text Only', 'Image (JPEG or PNG)', 'Macromedia Flash (SWF)', 'Sound (MP3)');
		$sceneProperties['data']['selected'] = $this->get (PROP_DATA_TYPE);
				
		$sceneProperties['name'] = $this->get (PROP_NAME);
		$sceneProperties['description'] = $this->get (PROP_DESCRIPTION);
		$sceneProperties['story_id'] = $this->get (PROP_STORY_ID);
		$sceneProperties['id'] = $this->get (PROP_ID);
		$sceneProperties['text'] = $this->get (PROP_DATA_TEXT);
		$sceneProperties['source_fork_id'] = $this->get (PROP_SOURCE_FORK_ID);
		$sceneProperties['media'] = array ();

		$this->prepareSmartyMediaVariables ($sceneProperties['media']);
		
		$storyPath = new SSStoryPath;
		$trace = $storyPath->backtrackFromFork ($this->get (PROP_SOURCE_FORK_ID));
		$sceneProperties['breadcrumb'] = $storyPath->generateBreadcrumbString ($trace);

		$fork = new SSFork;
		$fork->set (PROP_ID, $sceneProperties['source_fork_id']);
		if ($fork->load ()) {

		 	// Setup smarty fork properties.
		 	$forkProperties = array ();
		 	$fork->prepareSmartyVariables ($forkProperties);
			$smarty->assign ('ss_fork', $forkProperties);
						
			// Setup scene/story properties.
			$story = new SSStory;
			$story->set (PROP_ID, $fork->get (PROP_STORY_ID));
			if ($story->load ()) {

				$sceneProperties['story_name'] = $story->get (PROP_NAME);
				$sceneProperties['story_id'] = $story->get (PROP_ID);

				// End forks
				$forks = $story->getEndForkList ();
				$sceneProperties['end_fork'] = array ();
				$sceneProperties['story_has_end_fork'] = false;
				if ((count ($forks) > 0) && ($story->get (PROP_TYPE) == STORY_TYPE_BEGIN_END)) {									
							
					$sceneProperties['story_has_end_fork'] = true;
												
					$sceneProperties['end_fork']['output'] = array ();
					$sceneProperties['end_fork']['values'] = array ();
					
					foreach ($forks as $fork) {
						array_push ($sceneProperties['end_fork']['output'], $fork->get (PROP_NAME));
						array_push ($sceneProperties['end_fork']['values'], $fork->get (PROP_ID));
					}													
				}
				
				$sceneProperties['end_fork']['selected'] = $this->get (PROP_END_FORK_ID);
			}

			$smarty->assign ('ss_scene', $sceneProperties);
		}
	}
	
    /**
     * Handles submission of story form data
     * This will take care of retrieving the POST parameters,
     * then using the data, if valid, to add to or change
     * the databse.
     * @param bool $editForm True if the data being submitted
     * is to be used for editing the database, false
     * indicates that it's for adding.
     * @return bool True if the submission was successful, false otherwise.
     */
    function handleFormSubmit ($editForm)
    { 
		$success = false;
		
        // Make sure that everything went okay with the load
        // before processing the edit.
        if (!$editForm || ($editForm && $this->get (PROP_ID) > 0)) {
	        
            // Now set the new values
            $this->set (PROP_NAME, strip_tags ($GLOBALS['APP']->queryPostValue ('name')));
            $this->set (PROP_DESCRIPTION, strip_tags ($GLOBALS['APP']->queryPostValue ('description')));
            $this->set (PROP_STATUS, $GLOBALS['APP']->queryPostValue ('status'));
            $this->set (PROP_STORY_ID, $GLOBALS['APP']->queryPostValue (PAGE_ACTION_STORY_ID));
			
			// Make sure that the source fork is not deleted.
			$sourceForkID = $GLOBALS['APP']->queryPostValue ('source_fork_id');
			$fork = generateObject (OBJECT_TYPE_FORK, $sourceForkID);
			if (!$fork || ($fork->get ('status') == FORK_STATUS_DELETED)) {
				$this->addError ('You cannot add a scene to a fork that is marked deleted or does not exist', ERROR_TYPE_SERIOUS);
				return false;
			}
			
			$this->set (PROP_SOURCE_FORK_ID, $sourceForkID); 
			$this->set (PROP_END_FORK_ID, $GLOBALS['APP']->queryPostValue ('end_fork_id')); 
						
			// The datatype in the database is stored when the data is posted.
            $dataType = $GLOBALS['APP']->queryPostValue ('data_type');
						
			// Remove any and all HTML from the text.  We only use BBCode
			//	from hereon out.
			$text = strip_tags ($GLOBALS['APP']->queryPostValue ('data_text'));
            $this->set (PROP_DATA_TEXT, $text); 
			
            // And update the database with the new/changed record.
            if ($editForm) {	   
			
				if ($this->queryPostValue ('remove_media')) {
					// The user wants to clear whatever file is in the dbase for this scene
					$this->set (PROP_DATA_BINARY, '');
					$this->set (PROP_DATA_TYPE, '');
					$this->set (PROP_DATA_PROPERTIES, '');
				}
			         
                // Keep track of the values in case we need to return to the form
                $GLOBALS['APP']->rememberValue ('SCENE_ADDEDIT_OBJ', $this);
                $success = $this->update ();
            } 
			else {
	            
                // Use default values for the status and permission
                $this->set ('permission', STORY_PERMISSION_REGISTERED_READWRITE);

                $user = $GLOBALS['APP']->getLoggedInUserObject ();
                if ($user) {
	                
                    // Set the originating author to the logged in user.
                    $this->set (PROP_USERNAME, $user->get ('username')); 
                    
                    // Keep track of the values in case we need to return to the form
                    $GLOBALS['APP']->rememberValue ('SCENE_ADDEDIT_OBJ', $this);

                    $success = $this->add ();
                } 
            } 
        } else {
            $this->addError (STR_148, ERROR_TYPE_SERIOUS);
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
            case SCENE_STATUS_DRAFT:
                return STR_149;
            case SCENE_STATUS_ACTIVE:
                return STR_150;
            case SCENE_STATUS_DELETED:
                return STR_151;
            default :
                return ;
        } 
    } 

    /**
     * Finds the fork that leads from the currently stored scene object.
     * If the scene object doesn't have a valid ID then this will fail.
     * If no forks were found then this will fail - and it might mean
     * that this is the last scene in a story.
     * 
     * @return mixed Returns false if no forks could not be found or if
     * the scene could not be found, otherwise it returns an
     * array of fork objects found.
     */
    function getOutgoingForks ()
    {
		$cachedScene = $this->getCachedObject ();
		if ($cachedScene && (is_array ($cachedScene->_nextForks))) {
			
			// The fork list has already been retrieved for this
			//	script so use what was already gotten.
			copyObject ($cachedScene, $this);
			return $this->_nextForks;
		}
		    	
        $forks = false;
		$fork = new SSFork;
		
		// The ID of this object could be zero if 
		//	if the user hasn't created the scene in 
		//	the database yet.
		if ($this->get (PROP_ID) > 0) {
		
			$query = 'SELECT * FROM ' . $GLOBALS[$fork->_getTableConstant()]['name'] . ' WHERE ' . 
					$GLOBALS[$fork->_getTableConstant()]['fields']['FROM_SCENE'] . ' = ' . 
					$this->get (PROP_ID);
			
			$results = $GLOBALS['DBASE']->getAssoc ($query);
			if (!DB::isError ($results)) {
				$forks = array ();
				foreach ($results as $key => $row) {
				
					// Create a new fork
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
				$this->_nextForks = $forks;
				$this->cacheObject ();
	
				if (count ($forks) == 0) {
					$forks = false;
				} 
				
			} else {
				$this->addErrorObject ($results, ERROR_TYPE_SERIOUS);
			} 
		}
		
        return $forks;
    } 
    /**
     * Finds the fork that leads to the currently stored scene object.
     * If the scene object doesn't have a valid ID then this will fail.
     * If the scene object is the first scene in a story then this will
     * fail
     * 
     * @return mixed Returns false if the fork could not be found or if
     * the scene could not be found, otherwise it returns the
     * fork object found.
     */
    function getIncomingFork ()
    {
        $fork = new SSFork;
		$fork->set (PROP_ID, $this->get (PROP_SOURCE_FORK_ID));
		if ($fork->load ()) {
			return $fork;
        } 
		else {
			$this->addError (sprintf (STR_153, $this->get (PROP_NAME), $this->get (PROP_ID)), ERROR_TYPE_SERIOUS);
			return NULL;
		}

    } 

    /**
     * Initializes smarty variables to display this object
     * Override for custom object.
     * 
     * 	@param array $ [Ref] $array On output, the array containing the object's properties
		@param bool $getPropertiesOnly This is ignored with objects of this type
	 */
    function prepareSmartyVariables (&$array, $getPropertiesOnly=false)
    {
        $array ['name'] = stripslashes ($this->get (PROP_NAME));
        $array ['id'] = $this->get (PROP_ID);
        $array ['description'] = stripslashes ($this->get (PROP_DESCRIPTION));
		$array ['story_id'] = $this->get (PROP_STORY_ID);
		$array ['user_id'] = $this->get (PROP_USERNAME);
		$array ['phpbb_topic_id'] = $this->get (PROP_PHPBB_TOPIC_ID);		
		$array ['license_code'] = $this->get (PROP_LICENSE_CODE);		
		$array ['license_name'] = $this->get (PROP_LICENSE_NAME);		
		$array ['license_url'] = $this->get (PROP_LICENSE_URL);		
		$array ['url_root'] = $GLOBALS['baseUrl'];
		$array ['object_type'] = $this->getType();
		
        $array ['status'] = $this->statusCodeToString ($this->get (PROP_STATUS));
		$array ['status_values'] = array ('construction'=>SCENE_STATUS_DRAFT,'complete'=>SCENE_STATUS_ACTIVE,'deleted'=>SCENE_STATUS_DELETED);
		$array ['status_int'] = $this->get (PROP_STATUS);
		
		// Get a list of all the forks off of this scene.
		$forks =$this->getOutgoingForks ();
		$array ['outgoing_forks'] = array();
		if (is_array ($forks)) {
			foreach ($forks as $fork) {
				
				$forkProps = array ();
				$fork->prepareSmartyVariables ($forkProps, true);
				array_push ($array ['outgoing_forks'], $forkProps);
			}
		}
		
		// Once we get the previous fork we can determine if
		//	this is true.
		$array['last_scene'] = false;
		
		// Display the fork leading to this scene.
        $array ['source_fork_id'] = $this->get (PROP_SOURCE_FORK_ID);
		$fork = new SSFork;
		$fork->set (PROP_ID, $this->get (PROP_SOURCE_FORK_ID));
		$array['incoming_fork'] = array ();
		if ($fork->load ()) {
			$fork->prepareSmartyVariables ($array ['incoming_fork'], true);
			
			// Now set whether or not this scene branches off a 
			//	an END fork.
			if ($fork->isEndFork ()) {
				$array['last_scene'] = true;
			}
		}
		
		// Get the associated story.
        $story = $this->_getStoryObject ();
		if ($story) {
			$array['story_has_end'] = $story->get (PROP_TYPE) == STORY_TYPE_BEGIN_END;
			
			$array ['end_fork'] = array();
			if (!$array['last_scene']) {
			
				// If this scene doesn't branch off of an end scene
				//	then determine the end fork that should be branched
				//	from this scene.  Only valid when the story is
				//	of type BEGIN_END
				if ($story->get (PROP_TYPE) == STORY_TYPE_BEGIN_END) {
				
					// Display the end fork associated with this story (if any)
					$endForkID = $this->get (PROP_END_FORK_ID);
					$fork = new SSFork;
					$fork->set (PROP_ID, $endForkID);
					if ($endForkID > 0 && $fork->load ()) {
						$fork->prepareSmartyVariables ($array['end_fork']);
					}
				}
			}
		}
										        
		$this->prepareSmartyMediaVariables ($array['media']);
		        
		$bbcode = new SSBBCode;
        $array ['text'] = $bbcode->parse_bbcode (stripslashes ($this->get (PROP_DATA_TEXT)));
        
		// Setup which actions can be performed on this story
		$array['actions'] = array ();
		$array['actions']['add_fork'] = $this->hasPermissionToAddFork ();
		$array['actions']['edit_scene'] = $this->hasPermissionToEditScene ();
		$array['actions']['delete_scene'] = $array['actions']['edit_scene'];        
		
		// Prepare bookmark info
		if (!$getPropertiesOnly) {
			$bookmark = $this->getObjectBookmark ();
			$ss_bookmark ['has_bookmark'] = false;
			if ($bookmark) {
				$ss_bookmark ['has_bookmark'] = true;
				$bookmark->prepareSmartyVariables ($ss_bookmark);
			}
			$array['bookmark'] = $ss_bookmark;	
		}
    } 

				
    /**
     * Displays the object on the user's browser using the appropriate view template
     * The appropriate view template is specific to the object's type
     */
    function view ()
    {
        if ($this->requiredFieldsValid (true)) {
		
			$this->updateLicense ();
			
            $ss_scene = array ();
					
            $this->prepareSmartyVariables ($ss_scene);
			
			// Get story history
			$versions = new SSVersionControl;
			$versions->setObject ($this);
			$ss_scene['history'] = $versions->getHistory ();
			
			$storyPath = new SSStoryPath;
			$trace = $storyPath->backtrackFromScene ($this->get (PROP_ID));
			$ss_scene['breadcrumb'] = $storyPath->generateBreadcrumbString ($trace);
						
			$ss_scene['binary_preview'] = $this->getBinaryLinkOrPreview ();
			
            $smarty = new SSSmarty;
            $smarty->assign ('ss_scene', $ss_scene);
			$smarty->prepareUserVariables ();
			
            echo $smarty->display ('authoring/view_scene.tpl');
        } else {
            $this->addError (STR_154, ERROR_TYPE_SERIOUS);
        } 
    } 

    /**
     * Returns the unique ID for this object as stored in memory (NOT THE DATABASE)
     * @param bool $fieldName If true, then this function will return the table's field name for the unique ID instead of the actual unique ID
     * @return mixed The unique ID field for this object.  If an array is returned, then more than one field makes up a key.
     */
    function getUniqueID ($fieldName = false)
    {
        if ($fieldName) {
            return $GLOBALS[$this->_getTableConstant()]['fields']['ID'];
        } else {
            return $this->get (PROP_ID);
        } 
    } 

    /**
     * Collects in an array all scenes that are marked as connected to this one in the fork table
     * @return array An array of connected scene objects (next scenes, that is)
     */
    function getNextScenes ()
    {
        $nextScenes = array ();
        $query = 'SELECT * FROM ' . $GLOBALS['TABLE_FORK']['name'] . ' WHERE ' . $GLOBALS['TABLE_FORK']['fields']['FROM_SCENE'] . '=' . $this->get (PROP_ID);

        $records = $GLOBALS['DBASE']->getAssoc ($query);
        foreach ($records as $key => $fields) {
            $fork = new SSFork;
            $fork->set (PROP_ID, $key);
            if ($fork->load ()) {
                $scene = $fork->getNextScene ();
                array_push ($nextScenes, array ('scene' => $scene, 'fork' => $fork));
            } 
        } 
        return $nextScenes;
    } 
		
	/** 
	 * Removes the delete status and replaces it with the draft status
	 * @return bool True if the scene was undeleted, false otherwise
	 */
	function undelete () {
	
		$this->set (PROP_STATUS, SCENE_STATUS_DRAFT);
		if ($this->update ()) {
			$this->addNotification (sprintf (STR_155, $this->get (PROP_NAME)));
			return true;		
		}
	}
	
	/** 
	 * Deletes a scene by actually removing the record from the database
	 * @return bool True if the scene was deleted, false otherwise
	 */
	function trueDelete() {
	
		// First, make sure that there are no forks coming off of this scene
		$scenes = $this->getOutgoingForks ();
		if (!$scenes) {
			$this->_enableTrueDeletion (true);
			if ($this->delete ()) {
				
				return true;
			}
		}
		else {
			$this->addError (STR_156, ERROR_TYPE_SERIOUS);
		}
		
		return false;
	}
	
	/** Determines if this scene has any forks coming off of it.
	 *
	 * @return bool True if this is a leaf scene (no forks), false if there are forks.
	 */
	function isLeafScene () {
		
        $forks = false;
		$fork = new SSFork;
        $query = 'SELECT * FROM ' . $GLOBALS[$fork->_getTableConstant()]['name'] . ' WHERE ' . 
				$GLOBALS[$fork->_getTableConstant()]['fields']['FROM_SCENE'] . ' = ' . 
				$this->get (PROP_ID);
		
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
        if (!DB::isError($results)) {
            return ($GLOBALS['DBASE']->numRows($results) == 0);
        } else {
	        $this->addError (sprintf (STR_157, $this->get (PROP_NAME), $results->getMessage()), ERROR_TYPE_SERIOUS);
        }
        return false;		
	}
		
	/** Determines if the logged in user has permission to add a fork to this scene
	 *
	 * @return bool Returns true if permission is granted, false otherwise.
	*  @access public
	 */
	function hasPermissionToAddFork () {
		
		// Right now, anyone who is registered has permission to add a scene to any story.
		if ($GLOBALS['APP']->isUserLoggedIn ()) {
			return true;
		}
	}
	
	/** Determines if the logged in user has permission to edit the scene
	 *
	 * 	@return bool Returns true if permission is granted, false otherwise.
	 *  @access public
	 */
	function hasPermissionToEditScene () {
		
		// The author and the administrator can edit the scene.
		$user = $GLOBALS['APP']->getLoggedInUserObject ();
		if ($user && (($user->get ('username') == $this->get (PROP_USERNAME)) ||
					  ($user->get ('user_type') == USER_TYPE_ADMIN))) {
			return true;
		}
	}

	/** Determines if the logged in user has permission to view the scene details
	 *
	 * 	@return bool Returns true if permission is granted, false otherwise.
	 *  @access public
	 */
	function hasPermissionToViewScene () {
		
		// Right now, anyone who is registered has permission to view the scene
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
		
			if ($this->get (PROP_STATUS) == SCENE_STATUS_DELETED) {
				$this->addError (STR_158, ERROR_TYPE_SERIOUS);
				return;
			}
			
			$smarty = new SSSmarty;
			
			$smarty->prepareUserVariables ();
			
			// Get scene properties
			$ss_scene = array ();
			$this->prepareSmartyVariables ($ss_scene);
						
			$storyPath = new SSStoryPath;
			$trace = $storyPath->backtrackFromScene ($this->get (PROP_ID));
			$ss_scene['breadcrumb'] = $storyPath->generateBreadcrumbString ($trace, true);

			$GLOBALS['APP']->addViewRecord ($this);
			
			// Prepare the rating forms/data
			$ss_scene['rating'] = array ('is_rated'=>false);
			$rating = $this->getObjectRating();
			if ($rating) {				
				$array = array ();
				$rating->prepareSmartyVariables ($array);
				$array ['is_rated'] = true;
				$ss_scene['rating'] = $array;
			}
			else {
				$rating = new SSRating;
				$rating->prepareFormTemplate ($smarty);
			}

			// Prepare the classification forms/data
			$ss_scene['classification'] = array ('is_classified'=>false);
			$classification = $this->getObjectClassification();			
			if ($classification) {
				$array = array ();
				$classification->prepareSmartyVariables ($array);
				$array ['is_classified'] = true;				
				$ss_scene['classification'] = $array;				
			}
			else {
				$classify = new SSClassification;
				$classify->prepareFormTemplate ($smarty);
			}
				
			// Prepare the average review score
			$ss_scene['avg_rating'] = $this->getAverageRating();
			
			$totalClassifications = 0;
			$ss_scene['classification']['all'] = $this->getClassificationBreakdown ($totalClassifications);
			$ss_scene['classification']['total'] = $totalClassifications;
						
			$bmc = new SSBookmarkCollection();
			$bmc->prepareSmartyUserBookmarkList ($smarty);
						
			$smarty->assign ('ss_scene', $ss_scene);
			$smarty->display ('reading/read_scene.tpl');
		}
		else {
			$this->addError (STR_160, ERROR_TYPE_SERIOUS);
		}
	}
		
	/** Determines if a user can add a fork to this scene
	 * 	Scenescan only be added to if they are active or if the 
	 *	story is a draft and the user adding is the user who created
	 *	the story.
	 *	@return bool True if the story can be added to, false otherwise.
	 */
	function canAddToScene () {
	
		$user = $GLOBALS['APP']->getLoggedInUserObject ();
		if ($user) {
			return ($this->get (PROP_STATUS) == SCENE_STATUS_ACTIVE ||
					($this->get (PROP_STATUS) == SCENE_STATUS_DRAFT && ($user->get (PROP_USERNAME) == $this->get (PROP_USERNAME))));
		}
		
		return false;
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
				$name = 'scene name';
				$diff = true;
				break;
			case PROP_DESCRIPTION:
				$name = 'description';
				$diff = true;
				break;
			case PROP_USERNAME:
				$name = 'author';
				$diff = true;
				break;
			case PROP_STATUS:
				$name = 'status';
				$mapping = array (SCENE_STATUS_ACTIVE=>'active', SCENE_STATUS_DRAFT=>'draft', SCENE_STATUS_DELETED=>'deleted');
				$diff = true;
				break;
			case PROP_PHPBB_TOPIC_ID:
				$name = 'phpBB topic id';
				$diff = false;
				break;
			case PROP_DATA_TEXT:
				$name = 'scene text';
				$diff = true;
				break;
			case PROP_SOURCE_FORK_ID:
				$name = 'story fork id';
				$diff = false;
				break;
			case PROP_END_FORK_ID:
				$name = 'end fork id';
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
