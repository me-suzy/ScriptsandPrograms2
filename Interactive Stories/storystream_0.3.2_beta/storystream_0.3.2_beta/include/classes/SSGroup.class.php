<?php
/** @file SSGroup.class.php
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
 *	@date May, 2004	
 */

/** Represents a single group in StoryStream
	<p> 
  	The group class can be used to manage a single
	StoryStream group.  Use this class to get information
	such as which users are in the group, what are the
	properties of the group, etc.
	
	@author Karim Shehadeh
	@date 5/17/2004
*/
class SSGroup extends SSTableObject
{
	/** Constructor: Adds required properties */
	function SSGroup () {
		parent::SSTableObject ();
	}	

	/** Retrieves the classes friendly type name
	 * @param bool $asPlural True to return the plural version, false otherwise.
	 * @return string The name as a string
	 *  @access public
	 */

	function getTypeName ($asPlural) {		
		return $asPlural ? STR_70 : STR_71;
	}

	/** Returns an object type code of OBJECT_TYPE_UNKNOWN if unspecified
	 * @return int The object type value
	 * @access public
	 */
	function getType () {
		return OBJECT_TYPE_GROUP;
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
		$this->_addProperty (PROP_STATUS, '');
		$this->_addProperty (PROP_DATE, 0);
		$this->_addProperty (PROP_USERS, array());
		$this->_addProperty (PROP_ALLOW_VIEW, 1);
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
					$GLOBALS[$tableConstant]['fields']['USERNAME'] => $this->get (PROP_USERNAME),
					$GLOBALS[$tableConstant]['fields']['NAME'] => $this->get (PROP_NAME),
					$GLOBALS[$tableConstant]['fields']['DESCRIPTION'] => $this->get (PROP_DESCRIPTION),
					$GLOBALS[$tableConstant]['fields']['DATE'] => $this->get (PROP_DATE),
					$GLOBALS[$tableConstant]['fields']['STATUS'] => $this->get (PROP_STATUS),
					$GLOBALS[$tableConstant]['fields']['ALLOW_VIEW'] => $this->get (PROP_ALLOW_VIEW),
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
		$this->set (PROP_USERNAME, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['USERNAME']]);
		$this->set (PROP_NAME, stripslashes (@$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['NAME']]));
		$this->set (PROP_DESCRIPTION, stripslashes (@$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['DESCRIPTION']]));
		$this->set (PROP_DATE, stripslashes (@$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['DATE']]));
		$this->set (PROP_STATUS, stripslashes (@$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['STATUS']]));
		$this->set (PROP_ALLOW_VIEW, stripslashes (@$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['ALLOW_VIEW']]));
		
		return $this->requiredFieldsValid ();
	}

	/**  Gets the associative array required to mark a record as deleted
     *	@param bool $checkKey If this is true, then the unique key field for the object is checked for validity as well
     *	@return array An associative (key>value) array populated with the keys/values required to mark the object as deleted
     *	@access protected
	 */
	function _getDBKeyValueForDelete () {

		return false;
	}	
	/**  Gets the key string for the associative array that contains field information for the table associated with this object.
     *	@see tables.inc.php
     *	@return string The key field string for the assocative array that contains field information for the table associated with this object.
	 */
	function _getTableConstant () {
		return 'TABLE_GROUP';
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
			$this->addError (STR_72, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get (PROP_USERNAME) == '') {
			$this->addError (STR_73, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get (PROP_DATE) == '') {
			$this->addError (STR_74, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get (PROP_STATUS) == '') {
			$this->addError (STR_75, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}

		if ($checkKey) {
			if ($this->get (PROP_ID) == '') {
				$this->addError (STR_76, ERROR_TYPE_SERIOUS);
				$invalidField = true;
			}
		}
		
		return !$invalidField;
	}
		
	/**  This will mark an object as deleted.  
     *	Note that the object is not actually removed from the database.  Instead, it's
     *	simply marked as deleted.
     *	@return bool True if the object was marked as deleted successfully
	 */
	function delete () {
		
		$this->_enableTrueDeletion(true);
		if (parent::delete ()) {
			
			// Now delete the entries in all related tables
			$users = array ();
			$tableName = $GLOBALS['TABLE_GROUP_USER_MAPPING']['name'];
			$groupIDField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['ID'];
			$query = "DELETE FROM $tableName WHERE $groupIDField=".$this->get(PROP_ID);
			$results = $GLOBALS['DBASE']->simpleQuery ($query);
			return (!DB::isError ($results));			
		}
		
		return false;
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
            case GROUP_STATUS_ACTIVE:
                return STR_77;
            case GROUP_STATUS_FROZEN:
                return STR_78;
            default :
                return STR_79;
        } 
    } 

    /**
     * Converts the user type code to a human-readable string
     * 
     * @return string The type code string or 'UNKNOWN USER TYPE CODE' if invalid
     */
    function userTypeCodeToString ($code)
    {
        switch ($code) {
            case GROUP_USER_TYPE_ADMIN:
                return STR_80;
            case GROUP_USER_TYPE_MEMBER:
                return STR_81;
            case GROUP_USER_TYPE_INVITED:
                return STR_82;
            case GROUP_USER_TYPE_EXPELLED:
                return STR_83;
            default :
                return STR_84;
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
		$array ['username'] = $this->get (PROP_USERNAME);
		$array ['description'] = $this->get (PROP_DESCRIPTION);
		$array ['date'] = $this->get (PROP_DATE);
		$array ['status_int'] = $this->get (PROP_STATUS);
		$array ['status_string'] = $this->statusCodeToString ($this->get (PROP_STATUS));
		$array ['nonmember_viewable'] = $this->get (PROP_ALLOW_VIEW);
		
		// Make sure we have a list of all the users (not done automatically)
		$users = $this->get (PROP_USERS);
		if (!$getPropertiesOnly && (count ($users) == 0)) {
			$this->collectUsers ();
			$users = $this->get (PROP_USERS);
		}

		// Get the list of users in the group.
		$array['users'] = array ();
		foreach ($users as $user) {
			
			$userArray = array ();
			$user->prepareSmartyVariables ($userArray);
			$userArray['group_date'] = $user->get ('group_date');
			
			$type = $user->get ('group_type');
			$userArray['group_type'] = $type;
			$userArray['group_type_string'] = $this->userTypeCodeToString ($type);
						
			 if ($type == GROUP_USER_TYPE_MEMBER) {
				$userArray['action'] = GROUP_USER_ACTION_BAN;
			}
			else if ($type == GROUP_USER_TYPE_EXPELLED) {
				$userArray['action'] = GROUP_USER_ACTION_UNBAN;
			}
			else if ($type == GROUP_USER_TYPE_INVITED) {
				$userArray['action'] = GROUP_USER_ACTION_UNINVITE;
			}
			else {
				$userArray['action'] = "";
			}
			
			
			$array['users'][] = $userArray;
		}
		
		// Now get a list of stories in the group
		$stories = $this->getStories ();
		$array['stories'] = array ();
		foreach ($stories as $story) {
			$storyArray = array ();
			$story->prepareSmartyVariables ($storyArray, true);
			$array['stories'][] = $storyArray;
		}
	}

	/** Determines if the given user is in the group
		@param SSUser The user to check
		@return bool Returns true if the given user is in the group, false otherwise.
	*/
	function isUserInGroup ($user) {
		
		// Make sure we have a list of all the users (not done automatically)
		$users = $this->get (PROP_USERS);
		if (count ($users) == 0) {			
			// Check the database directly if we don't already have a list.
			$tableName = $GLOBALS['TABLE_GROUP_USER_MAPPING']['name'];
			$groupIDField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['ID'];
			$usernameField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['USERNAME'];
			$typeField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['TYPE'];
					
			$query = "SELECT * FROM $tableName WHERE $groupIDField=".$this->get ('id')." 
						AND $usernameField='".$user->get(PROP_USERNAME)."' AND 
							($typeField=".GROUP_USER_TYPE_MEMBER." OR 
								$typeField=".GROUP_USER_TYPE_ADMIN.") ORDER BY 
								$usernameField DESC";
						
			$results = $GLOBALS['DBASE']->simpleQuery ($query);
			if (!DB::isError ($results)) {
				$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
				return $resultObj->numrows() > 0;
			}			
		}		
		else {
			foreach ($this->get (PROP_USERS) as $userRec) {
				if ($userRec->get (PROP_USERNAME) == $user->get (PROP_USERNAME)) {
					return true;
				}
			}
		}
		
		return false;
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
		$memObj = $GLOBALS['APP']->retrieveValue ('GROUP_ADDEDIT_OBJ');

		if ($isEditForm) {

			$this->set (PROP_ID, $GLOBALS['APP']->queryValue (PAGE_ACTION_GROUP_ID));
			if ($this->load ()) {

				// If there was a submit error then use
				//	the values that were previously entered
				//	instead of those that are in the database.
				if ($afterError && $memObj) {

					$defaultTitle = $memObj->get (PROP_NAME);
					$defaultDescription = $memObj->get (PROP_DESCRIPTION);

					$this->set (PROP_NAME, $defaultTitle);
					$this->set (PROP_DESCRIPTION, $defaultDescription);
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
					$this->set (PROP_USERNAME, $user->get (PROP_USERNAME));
				}
			}
		}
				
		$smarty->display ('members/form_group.tpl');
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

		// Form data (is this an edit or not)?		
		$formProperties = array ();
		$formProperties['edit'] = $editForm;
		$smarty->assign ('ss_form', $formProperties);

		// Prepare the action information and whether or not
		//	to display the
		$groupProperties = array ();		
		$this->prepareSmartyVariables ($groupProperties);
		
		// Prepare status selection array
		$statusNames = array ($this->statusCodeToString(GROUP_STATUS_ACTIVE), $this->statusCodeToString(GROUP_STATUS_FROZEN));
		$statusValues = array (GROUP_STATUS_ACTIVE, GROUP_STATUS_FROZEN);
				
		// Prepare the status array.
		$status = array();
		$status['selected'] = $this->get (PROP_STATUS);
		$status['values'] = $statusValues;
		$status['output'] = $statusNames;
		$groupProperties['status'] = $status;		
		
		$smarty->assign ('ss_group', $groupProperties);
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
		
		if ($this->queryPostValue('submit') == SUBMIT_ACTION_INVITE_USERS) {
			
			// First, load this group from the database
			$this->set (PROP_ID, $this->queryPostValue ('group_id'));
			if ($this->load ()) {
				$userListString = $this->queryPostValue ('users');
				if ($userListString == "") {
					$this->addError (IDS_85, ERROR_TYPE_SERIOUS);
					return false;
				}
				else {
					$usernames = explode (',', $userListString);
					if (count($usernames)>0) {
						foreach ($usernames as $username) {
							$username = trim ($username);
							
							$user = new SSUser;
							$user->set (PROP_USERNAME, $username);
							if ($user->load()) {
								if (!$this->inviteUser ($user)) {
									$this->addNotification (sprintf (STR_86, $username));
								}
							}
							else {
								$this->addError (sprintf (STR_87, $username), ERROR_TYPE_SERIOUS);
							}
						}
						return true;
					}
					else {
						$this->addError (STR_88);
						return false;
					}
				}
			}
			else {
				$this->addError (sprintf (STR_89, $this->get (PROP_ID)), ERROR_TYPE_SERIOUS);
				return false;
			}
		}
		else {
			// Make sure that everything went okay with the load
			//	before processing the edit.
			if (!$editForm || ($editForm && $this->get (PROP_ID) > 0)) {
	
				// We need this to store the username of the user who 
				//	created the group along with the properties of the
				//	group.
				$user = $GLOBALS['APP']->getLoggedInUserObject ();
				
				// Now set the new values
				$this->set (PROP_NAME, strip_tags ($GLOBALS['APP']->queryPostValue ('name')));
				
				if (!$editForm) {
					// If we're adding the group then we should use the logged in user
					//	as the user who is administering the group.
					$this->set (PROP_USERNAME, $user->get (PROP_USERNAME));
					
					// This is the date that the group was created so we don't change
					//	that field when the user is editing the group. 
					$this->set (PROP_DATE, time ());
				}
				
				$this->set (PROP_DESCRIPTION, strip_tags ($GLOBALS['APP']->queryPostValue ('description')));
				$this->set (PROP_STATUS, $GLOBALS['APP']->queryPostValue ('status'));						
				$this->set (PROP_ALLOW_VIEW, $this->queryPostValue ('allow_nonmember_viewing'));
				
				// Keep track of the values in case we need to return to the form
				$GLOBALS['APP']->rememberValue ('GROUP_ADDEDIT_OBJ', $this);
	
				if ($this->requiredFieldsValid ()) {
					// And update the database with the new/changed record.
					if ($editForm) {
						return $this->update ();
					}
					else {
						if ($this->add ()) {
							
							// Now udpate the group->user mapping table with the 
							//	admin user.						
							return $this->addAdminUser ($user);
						}
					}
				}
			}
			else {
				$this->addError (STR_90, ERROR_TYPE_SERIOUS);
			}
		}

		return false;
	}

	/**  Displays the object on the user's browser using the appropriate view template
     *		The appropriate view template is specific to the object's type
	 */
	function view () {
		
		if ($this->requiredFieldsValid (true)) {
			
			$ss_group = array();
			
			$this->prepareSmartyVariables ($ss_group);
			
		 	// Note the fork view in the dbase and display
			$smarty = new SSSmarty;
			$smarty->prepareUserVariables ();
			$smarty->assign ('ss_group', $ss_group);
			echo $smarty->display ('members/view_group.tpl');
		}
		else {
			$this->addError (STR_91, ERROR_TYPE_SERIOUS);
		}
	}

	/** Adds a user to the list of members of this group
		@param SSUser $user The user to change
		@param int $type The type to change to.
		@return bool True if the user was added successfully
	*/
	function addUser ($user, $type) {
		
		$tableName = $GLOBALS['TABLE_GROUP_USER_MAPPING']['name'];
		$groupIDField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['ID'];
		$usernameField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['USERNAME'];
		$dateField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['DATE'];
		$typeField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['TYPE'];
				
		$query = "SELECT * FROM $tableName WHERE $usernameField='".$user->get(PROP_USERNAME)."' AND $groupIDField=".$this->get ('id');
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		if (!DB::isError ($results)) {
			$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
			if ($resultObj->numRows() > 0) {
				$this->addError (STR_92, ERROR_TYPE_SERIOUS);
				return false;
			}
		}
		else {
			$this->addError (STR_93, ERROR_TYPE_SERIOUS);
			return false;
		}
		
		$query = "INSERT INTO $tableName ($groupIDField,$usernameField,$dateField,$typeField) 
					VALUES (".$this->get('id').", '".$user->get(PROP_USERNAME)."',".time().",$type)";
		
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		if (!DB::isError ($results)) {
			return true;
		}
		else {
			$this->addError (sprintf (STR_94, $user->get (PROP_USERNAME)));
			$this->addErrorObject ($results, ERROR_TYPE_SERIOUS);
			return false;
		}
	}

	/** 
	 * Removes the user from the list of users in the group no matter what the status is.
	 * @param SSUser $user The user to remove.
	 * @return bool Returns true if the user was removed successfully.
	 */
	function removeUser ($user) {
		
		$tableName = $GLOBALS['TABLE_GROUP_USER_MAPPING']['name'];
		$groupIDField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['ID'];
		$usernameField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['USERNAME'];
		$dateField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['DATE'];
		$typeField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['TYPE'];
				
		$query = "DELETE FROM $tableName WHERE $usernameField='".$user->get(PROP_USERNAME)."' AND $groupIDField=".$this->get ('id');
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		if (!DB::isError ($results)) {
			return true;
		}
		else {
			$this->addError (STR_95, ERROR_TYPE_SERIOUS);
			return false;
		}
	}
	
	/** Sets a group member's type in the database to the one given here.
		@param SSUser $user The user to change
		@param int $type The type to change to.
		@return bool True if the user type was set successfully
	*/
	function setUserType ($user, $type) {
		
		$tableName = $GLOBALS['TABLE_GROUP_USER_MAPPING']['name'];
		$groupIDField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['ID'];
		$usernameField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['USERNAME'];
		$dateField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['DATE'];
		$typeField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['TYPE'];
				
		$query = "UPDATE $tableName SET $typeField=$type WHERE $usernameField='".$user->get(PROP_USERNAME)."'";
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		if (!DB::isError ($results)) {
			return true;
		}
		else {
			$this->addError (STR_96, ERROR_TYPE_SERIOUS);
			return false;
		}
	}
	
	/** 
	 * Adds the admin user to the list.  Will not add
	 * if there is already an admin user. 
	 * @param SSUser $user The user to add to the list.
	 * @return bool Returns true if the user was added.
	 */		
	function addAdminUser ($user) {
			
		return $this->addUser ($user, GROUP_USER_TYPE_ADMIN); 
	}
	
	/** Adds the user to the group->user map as being invited so that the next time that user
		logs in, they will see that they have been invited
		@param SSUser $user The user who is to be invited.
		@return bool Returns true if the user was invited
	*/		
	function inviteUser ($user) {
		return $this->addUser ($user, GROUP_USER_TYPE_INVITED);
	}

	/** Adds the user to the group->user map as being invited so that the next time that user
		logs in, they will see that they have been invited
		@param SSUser $user The user who is to be invited.
		@return bool Returns true if the user was invited
	*/		
	function uninviteUser ($user) {
		return $this->removeUser ($user);
	}
	
	/** Tags the given invited user as being a member
		@param SSUser $user The user who was invited and is to become a member
		@return bool Returns true if the user was marked as a member
	*/		
	function joinInvitedUser ($user) {		
		return $this->setUserType ($user, GROUP_USER_TYPE_MEMBER);
	}
	
	/** Removes the user from the groups list of potential users permanently
		@param SSUser $user The user who was invited and is now declining
		@return bool Returns true if the user was removed from the list.
	*/		
	function cancelInvitedUser ($user) {		
		return $this->removeUser ($user);
	}
	
	/** Removes the user from the groups list of members 
		@param SSUser $user The user who is withdrawing
		@return bool Returns true if the user was removed from the list.
	*/		
	function withdrawMembership ($user) {		
		return $this->removeUser ($user);
	}

	/** Marks the user as expelled from the group
		@param SSUser $user The user who is to be expelled from the group
		@return bool Returns true if the user was marked as expelled.
	*/
	function expelUser ($user) {
		return $this->setUserType ($user, GROUP_USER_TYPE_EXPELLED);
	}
	
	/** Marks the user as expelled from the group
		@param SSUser $user The user who is to be expelled from the group
		@return bool Returns true if the user was marked as expelled.
	*/
	function unexpelUser ($user) {
		return $this->setUserType ($user, GROUP_USER_TYPE_MEMBER);
	}	
	/** Forces the object to get a list of all members of the group and store it in the 'users' property
	*/
	function collectUsers () {
		
		$users = array ();
		$tableName = $GLOBALS['TABLE_GROUP_USER_MAPPING']['name'];
		$groupIDField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['ID'];
		$usernameField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['USERNAME'];
		$dateField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['DATE'];
		$typeField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['TYPE'];
				
		$query = "SELECT * FROM $tableName WHERE $groupIDField=".$this->get ('id')." ORDER BY $usernameField DESC";
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		if (!DB::isError ($results)) {
			$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
			while ($array = $resultObj->fetchRow ()) {
				$user = new SSUser;
				$user->set (PROP_USERNAME, $array[$usernameField]);
				if ($user->load ()) {
					$user->_addProperty ('group_date', $array[$dateField]);
					$user->_addProperty ('group_type', $array[$typeField]);
					$users[] = $user;
				}
				else {
					$this->addError (sprintf (STR_97, $user->get (PROP_USERNAME)), ERROR_TYPE_SERIOUS);
				}
			}
		}
		else {
			$this->addError (sprintf (STR_98, $this->get ('PROP_NAME')), ERROR_TYPE_SERIOUS);
		}
		
		$this->set (PROP_USERS, $users);
	}
	
	/** 
	 * Retrieves an array of stories that are contained within this group
	 * @return mixed Returns an array of SSStory objects or false if there was a problem.
	 */
	function getStories () {
		
		$coll = new SSCollection ('ssstory');
		$where = array ($GLOBALS['TABLE_STORY']['fields']['GROUP_ID']=>$this->get (PROP_ID));
		$coll->load ($where);
		return $coll->getObjects ();		
	}
}
?>
