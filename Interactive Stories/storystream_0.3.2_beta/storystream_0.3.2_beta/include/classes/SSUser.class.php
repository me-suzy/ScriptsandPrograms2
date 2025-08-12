<?php
/** @file SSUser.class.php
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

/**  Represents a single registered user of the system
	The user record maintains a host of important information regarding 
	the user including session information (when they last logged in,
	when they last did something on the site, and of course personal
	info).
*/
class SSUser extends SSTableObject
{
	var $_hiddenHash = 'TudH1O39;*fda';
	
	/** Constructor: Adds required properties*/
	function SSUser () {
		parent::SSTableObject ();
	}	
	
	/**  Gets the hash specific to this user
     *	@return string An encrypted hash that's unique to this user
	*/
	function getUserHash () {
		return md5 ($this->get (PROP_USERNAME).$this->_hiddenHash);
	}
	
	/**  Gets the user from the DB that has the given session_id 
     *	@param string $session_id The session ID to look up.
     *	@return bool true if the user was found and loaded
	*/
	function setUserFromSessionID ($sessionID) {	

		// Empty the error queue
		$this->clearErrors ();
		$tableConstant = $this->_getTableConstant();
		$query = 'SELECT * FROM '.$GLOBALS[$tableConstant]['name'].' WHERE '.$GLOBALS[$tableConstant]['fields']['LAST_SESSION_ID'].'="'.$sessionID.'"';
		
		$fields = $GLOBALS['DBASE']->getAssoc ($query);
		if (!DB::isError ($fields) && (count ($fields) == 1))	{						
				
			foreach ($fields as $key=>$row)  {			
				
				// Add the key field to the array of fields.
				$fields[$key][$this->getUniqueID (true)] = $key;
				
				// Now load the data into the object.
				return $this->_setDBKeyValueArray ($fields[$key]);
			}
		}
		else if (DB::isError ($fields)){
			$this->addErrorObject ($fields, ERROR_TYPE_SERIOUS);
		}
		
		return false;
	}
	
	/**  Adds all associated properties
     *	This need only be called once per instantiation of this class
     *	and is handled automatically by the base class as long
     *	as its constructor is called.		
	*/
	function _addProperties () {
		$this->_addProperty ('first_name', '');
		$this->_addProperty ('last_name', '');
		$this->_addProperty ('email', '');
		$this->_addProperty (PROP_USERNAME, '');
		$this->_addProperty ('password', '');
		$this->_addProperty ('date_joined', 0);
		$this->_addProperty ('date_lastlogin', 0);
		$this->_addProperty ('last_session_id', 0);
		$this->_addProperty ('last_activitydate', 0);
		$this->_addProperty ('user_type', 0);
		$this->_addProperty (PROP_STATUS, 0);
		$this->_addProperty ('hash', '');
		$this->_addProperty ('login_ip', '');
		$this->_addProperty ('login_client_info', '');
		$this->_addProperty ('phpbb_user_id', 0);
		$this->_addProperty ('phpbb_session_id', '');
		$this->_addProperty ('email_notify_new_story', false);
		$this->_addProperty ('email_notify_new_scene_fork', false);
		$this->_addProperty ('email_notify_updates', false);
		$this->_addProperty (PROP_RANK, 0);
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
					$GLOBALS[$tableConstant]['fields']['FIRST_NAME'] => $this->get ('first_name'),
					$GLOBALS[$tableConstant]['fields']['LAST_NAME'] => $this->get ('last_name'),
					$GLOBALS[$tableConstant]['fields']['PASSWORD'] => $this->get ('password'),
					$GLOBALS[$tableConstant]['fields']['DATE_JOINED'] => $this->get ('date_joined'),
					$GLOBALS[$tableConstant]['fields']['DATE_LAST_LOGIN'] => $this->get ('date_lastlogin'),
					$GLOBALS[$tableConstant]['fields']['LAST_SESSION_ID'] => $this->get ('last_session_id'),
					$GLOBALS[$tableConstant]['fields']['LAST_ACTIVITY_DATE'] => $this->get ('last_activitydate'),
					$GLOBALS[$tableConstant]['fields']['USER_TYPE'] => $this->get ('user_type'),
					$GLOBALS[$tableConstant]['fields']['STATUS'] => $this->get (PROP_STATUS),
					$GLOBALS[$tableConstant]['fields']['EMAIL'] => $this->get ('email'),
					$GLOBALS[$tableConstant]['fields']['HASH'] => $this->get ('hash'),
					$GLOBALS[$tableConstant]['fields']['LOGIN_IP'] => $this->get ('login_ip'),
					$GLOBALS[$tableConstant]['fields']['LOGIN_CLIENT_INFO'] => $this->get ('login_client_info'),
					$GLOBALS[$tableConstant]['fields']['PHPBB_USER_ID'] => $this->get ('phpbb_user_id'),
					$GLOBALS[$tableConstant]['fields']['PHPBB_SESSION_ID'] => $this->get ('phpbb_session_id'),
					$GLOBALS[$tableConstant]['fields']['NOTIFY_NEW_STORY'] => $this->get ('email_notify_new_story'),
					$GLOBALS[$tableConstant]['fields']['NOTIFY_NEW_SCENE_FORK'] => $this->get ('email_notify_new_scene_fork'),
					$GLOBALS[$tableConstant]['fields']['NOTIFY_UPDATES'] => $this->get ('email_notify_updates'),
					$GLOBALS[$tableConstant]['fields']['RANK'] => $this->get ('rank')
		);
						
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
		$this->set ('first_name', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['FIRST_NAME']]);
		$this->set ('last_name', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['LAST_NAME']]);
		$this->set (PROP_USERNAME, $dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['USERNAME']]);
		$this->set ('password', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['PASSWORD']]);
		$this->set ('date_joined', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['DATE_JOINED']]);
		$this->set ('date_lastlogin', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['DATE_LAST_LOGIN']]);
		$this->set ('last_session_id', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['LAST_SESSION_ID']]);
		$this->set ('last_activitydate', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['LAST_ACTIVITY_DATE']]);
		$this->set ('user_type', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['USER_TYPE']]);
		$this->set (PROP_STATUS, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['STATUS']]);
		$this->set ('email', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['EMAIL']]);
		$this->set ('hash', $dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['HASH']]);
		$this->set ('login_ip', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['LOGIN_IP']]);
		$this->set ('login_client_info', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['LOGIN_CLIENT_INFO']]);
		$this->set ('phpbb_user_id', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['PHPBB_USER_ID']]);
		$this->set ('phpbb_session_id', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['PHPBB_SESSION_ID']]);
		$this->set ('email_notify_new_story', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['NOTIFY_NEW_STORY']]);
		$this->set ('email_notify_new_scene_fork', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['NOTIFY_NEW_SCENE_FORK']]);
		$this->set ('email_notify_updates', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['NOTIFY_UPDATES']]);
		$this->set (PROP_RANK, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['RANK']]);
		return $this->requiredFieldsValid ();
	}
	
	/**  Gets the associative array required to mark a record as deleted
     *	@param bool $checkKey If this is true, then the unique key field for the object is checked for validity as well
     *	@return array An associative (key>value) array populated with the keys/values required to mark the object as deleted
	*/
	function _getDBKeyValueForDelete () {
			$fields = array ($GLOBALS[$this->_getTableConstant()]['fields']['STATUS'] => SCENE_STATUS_DELETED);								
			return $fields;
	}
	
	/**  Gets the key string for the associative array that contains field information for the table associated with this object.
     *	@see tables.inc.php
     *	@return string The key field string for the assocative array that contains field information for the table associated with this object.
	*/
	function _getTableConstant () {
		return 'TABLE_USER';
	}
	
	/**  Checks if the data stored in this object is valid.
     *	All this does is verify that there is valid data in the *required*
     *	fields.
     *	@param bool $checkKey If this is true, then the unique key field for the object is checked for validity as well
     *	@return bool True, if all required fields are valid.
	*/
	function requiredFieldsValid ($checkKey = false) {
	
		$invalidField = false;
		
		if ($this->get(PROP_USERNAME) == '') {
			$this->addError (STR_214, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get('password') == '') {
			$this->addError (STR_215, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get('user_type') == '') {
			$this->addError (STR_216, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get (PROP_STATUS) == '') {
			$this->addError (STR_217, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}		
		
		return !$invalidField;
	}
	
	/**  Determines if a user has confirmed his registration
     *	To confirm, a user must get an email and click on a link in that 
     *	email to confirm him or herself.
     *	@return bool Returns true if the user is a confirmed user.
	*/
	function isConfirmed ($user) {		
		return $this->get (PROP_STATUS) != USER_STATUS_UNCONFIRMED;
	}
	
	/**  Populates this object with data from the user record with the given username
     *	@param string $username The name of the user to lookup in the database
     *	@return bool True, if the user was found and the data loaded, false otherwise.
	*/
	function setUser ($username) {
	
		$this->set (PROP_USERNAME, $username);
		return $this->load ();
	}
	
	/**  matches the unencoded password given to the encoded password stored in the database
     *	@pararm string $password The password that's entered by the user.
     *	@return True if the password matches the one in the database.
	*/
	function validatePassword ($password) {
		
		if ($password != '') {
			return ($this->_encryptPassword ($password) == $this->get('password'));
		}
		
		return false;
	}
	
	/**  Verifies that the stored email address is a valid one
     *	@return bool True if the email is valid, false otherwise.
	*/
	function _checkEmailFormat () {
	
		$email = $this->get ('email');
		if (!ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'. '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email)) {
			$this->addError (STR_218, ERROR_TYPE_SERIOUS);
			return false;			
		}
		
		return true;
	}
	
	/**  Verifies that the stored username is a valid and secure one
     *	@return bool True if the username is valid, false otherwise.
	*/
	function _checkUsernameFormat () {
	
		$errorCount = 0;
		$username = $this->get (PROP_USERNAME);
		
	   // no spaces
		if (strrpos($username,' ') > 0) {
			$this->addError (STR_219, ERROR_TYPE_SERIOUS);
			$errorCount++;
		}
	
		// must have at least one character
		if (strspn($username,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ") == 0) {
			$this->addError (STR_220, ERROR_TYPE_SERIOUS);
			$errorCount++;
		}
	
		// must contain all legal characters
		if (strspn($username,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_")!= strlen($username)) {
			$this->addError (STR_221, ERROR_TYPE_SERIOUS);
			$errorCount++;
	}
	
		// min and max length
		if (strlen($username) < 5) {
			$this->addError (STR_222, ERROR_TYPE_SERIOUS);
			$errorCount++;
		}
		if (strlen($username) > 35) {
			$this->addError (STR_223, ERROR_TYPE_SERIOUS);
			$errorCount++;
		}
	
		// illegal names
		if (eregi("^((root)|(bin)|(daemon)|(adm)|(lp)|(sync)|(shutdown)|(halt)|(mail)|(news)"
			. "|(uucp)|(operator)|(games)|(mysql)|(httpd)|(nobody)|(dummy)"
			. "|(www)|(cvs)|(shell)|(ftp)|(irc)|(debian)|(ns)|(download))$",$username)) {
			
			$this->addError (STR_224, ERROR_TYPE_SERIOUS);
			$errorCount++;
		}
		if (eregi("^(anoncvs_)",$username)) {
			$this->addError (STR_225, ERROR_TYPE_SERIOUS);
			$errorCount++;
		}
	
		return ($errorCount == 0);
	}
	
	/**  Verifies that the stored password is a valid and secure password
     *	@return bool True if the password is valid, false otherwise.
	*/
	function _checkPasswordFormat () {
		
		$errorCount = 0;
		$password = $this->get ('password');
		if (strlen ($password) <= 6) {
		
			$this->addError (STR_226, ERROR_TYPE_SERIOUS);
			$errorCount++;
		}
		
		if (ctype_alpha ($password)) {
			
			$this->addError (STR_227, ERROR_TYPE_SERIOUS);
			$errorCount++;
		}
		
		return ($errorCount==0);
	}
	
	/**  Changes this user's email address
     *	This will send a confirmation email to the user's new email address
     *	which the user will have to respond to before the user is reconfirmed.
     *	@param The new email address.
     *	@return bool True if the confirmation email was sent.
	*/
	function newEmailAddress ($email) {
	
		if ($this->_checkEmailFormat ($email)) {
		
			// First, mark this user as unconfirmed again
			$this->set (PROP_STATUS, USER_STATUS_UNCONFIRMED);
			
			// Set up the new email address
			$this->set ('email', $email);
			
			// Now, try updating the record in the database with the
			//	new data
			if ($this->update ()) {
			
				// Now send the confirmation email address
				return $this->_sendConfirmationEmail ();
			}
		}
		return false;
	}
	
	/**  Registers a new user by adding them to the database and sending a confirmation email
     *	This function will not actually confirm the user. That 
     *	will be done by the confirmUser method of this class.
     *	@return bool True if the registration was successful, false otherwise.
	*/
	function newRegistration () {
	
		$this->clearErrors ();
	
		// The new email address
		$email = $this->get ('email');
		
		// This is the hash that has to be matched in order to 
		//	confirm the email address once the user gets the email.
		$this->set ('hash', md5 ($email.$this->_hiddenHash));
		
		// Mark the user as unconfirmed
		$this->set (PROP_STATUS, USER_STATUS_UNCONFIRMED);
		
		// Store when the registration was done
		$this->set ('date_joined', time ());

		// Do not allow any user type but 'registered' automatically register.
		$this->set ('user_type', USER_TYPE_REGISTERED);
					
		// Make sure that there are no other users with that email address
		if (!$this->verifyFieldUnique ($GLOBALS[$this->_getTableConstant()]['fields']['EMAIL'], "'$email'")) {
			$this->addError (STR_228, ERROR_TYPE_SERIOUS);
			return false;
		}
		
		// Make sure that there are no other users with that username
		//	Even thought the DB will catch this, we might as well look here to 
		//	display a gentler message.
		$username = $this->get (PROP_USERNAME);
		if (!$this->verifyFieldUnique ($GLOBALS[$this->_getTableConstant()]['fields']['USERNAME'], "'$username'")) {
			$this->addError (STR_229, ERROR_TYPE_SERIOUS);
			return false;
		}		
		
		// Now add the user to the database.
		if ($this->add ()) {
		
			// Send an email to the user.
			if ($GLOBALS['NOTIFY']->_sendRegistrationEmail ($this)) {
				$this->addNotification (STR_230);			
			    return true;
			}
		}
		
		return false;
	}
	
	/**  Marks the user as confirmed in the database after verifying the hash and email
     *	@param string $hash  The hash string sent as a parameter in the confirmation link
     *	@param string $email The email sent as a parameter in the confirmation link
     *	@return bool True if the user was confirmed, false otherwise.
	*/
	function confirmUser ($hash, $email) {
	
	   //verify that they didn't tamper with the email address
		$newHash=md5($email.$this->_hiddenHash);
		if ($newHash && ($newHash==$hash)) {
		
			$tableName = $GLOBALS[$this->_getTableConstant()]['name'];
			$hashFieldName = $GLOBALS[$this->_getTableConstant()]['fields']['HASH'];
			
			// Find this record in the db
			$query = 'SELECT * FROM '.$tableName.' WHERE '.$hashFieldName.'="'.$hash.'"';
			$results = $GLOBALS['DBASE']->getAssoc ($query);
			
			if (!DB::isError ($results)) {
			
				foreach ($results as $key=>$fields) {
				
					// If we got here then the record was found
					//	so we should update this object with the data
					//	from the record.
					$this->setUser ($key);	
					
					// Now we should set the user object to be confirmed
					$this->set (PROP_STATUS, USER_STATUS_ACTIVE);
					
					// Now try and add the user to the phpbb database
					$discuss = new SSDiscussionBoard;
					$userID = $discuss->addUser ($this->get (PROP_USERNAME), $this->get ('password'), $this->get ('email'));
					if ($userID !== false) {
					
						$this->set ('phpbb_user_id', $userID);
						
						// Now we need to update the record
						if ($this->update ()) {										
							return true;
						}
					}
					// error condition is added to the error log by the ssphpbb class.					
				}
			} 
			else {			
				$this->addErrorObject ($results, ERROR_TYPE_SERIOUS);
			}
		} 
		else {
			$this->addError (STR_231, ERROR_TYPE_SERIOUS);
		}	
			return false;
	}
	
	/**  Logs out this logged in user.
     *	All that's done here is that the user's session ID field
     *	is cleared so that it will never match what's returned
     *	by session_id.
     *	@return bool True if the user is logged in.
	*/
	function logout () {
	
		if ($this->isLoggedIn ()) {

			$this->set ('last_session_id', '');
			if ($this->update ()) {
			
				return true;
			}
		}
		
		return false;
	}
	
	/**  Determines if this user is currently logged in	
     *	To determine if a user is logged in, the system will compare
     *	the current session ID (in PHP) to the session ID stored
     *	in the database for the user (which is updated each time 
     *	the user is logged in).  The session ID generally is stored
     *	as long as the user's browser is not closed.  The php.ini
     *	file will determine the timeout period for a session.
     *	@return bool True if the user is logged in.
	*/
	function isLoggedIn () {
		
		if ($this->get ('last_session_id') == session_id ()) {
			return true;		
		}
		
		return false;
	}
	
	/**  Logs in a user and updates the dbase to reflect the new state
     *	@param string $password The clear text password entered by the user.
     *	@return bool True if the login was successful.
	*/
	function login ($password) {
			
		// validate the password first.
		if (md5 ($password) == $this->get ('password')) {
		
			// Make sure that the user's status is active 
			if ($this->get (PROP_STATUS) == USER_STATUS_ACTIVE) {

				// Set session and activity related items.
				$this->set ('last_session_id', session_id ());
				$this->set ('last_activitydate', time());
				$this->set ('date_lastlogin', time());
				$this->set ('login_ip', $_SERVER['REMOTE_ADDR']);
				$this->set ('login_client_info', $_SERVER['HTTP_USER_AGENT']);
				
				// Update the database with the new info
				if ($this->update ()) {				
					return true;
				}
				else {
					$this->addError (STR_233, ERROR_TYPE_SERIOUS);
				}
				
			}				
			else {
				switch ($this->get (PROP_STATUS)) {
				case USER_STATUS_UNCONFIRMED:
					$this->addError (STR_234, ERROR_TYPE_SERIOUS);
					break;
				case USER_STATUS_FROZEN:
					$this->addError (STR_235, ERROR_TYPE_SERIOUS);
					break;
				case USER_STATUS_STALE:
					$this->addError (STR_236, ERROR_TYPE_SERIOUS);
					break;
				case USER_STATUS_DELETED:
					$this->addError (STR_237, ERROR_TYPE_SERIOUS);
					break;
				}
			}
		}
		else {
		
			$this->addError (STR_238, ERROR_TYPE_SERIOUS);
			
			// Security measure.
			sleep (3);
		}
		
		return false;
	}
	
	/**  Encrypts a password using MD5 encryption.
     *	@param string $password The unencrypted password.
     *	@return string The encrypted password
	*/
	function _encryptPassword ($password) {
	
		if ($password != '') {
			return md5 ($password);
		}
		else {
			$this->addError (STR_239, ERROR_TYPE_SERIOUS);
		}
		
		return '';
	}
	
	/**  Returns the unique ID for this object as stored in memory (NOT THE DATABASE)
		@param bool $fieldName If true, then this function will return the table's field name for the unique ID instead of the actual unique ID
		@return mixed The unique ID field for this object.  If an array is returned, then more than one field makes up a key.
	*/
	function getUniqueID ($fieldName=false) {
	
		if ($fieldName) {
			return $GLOBALS[$this->_getTableConstant()]['fields']['USERNAME'];
		}
		else {
			return '\''.$this->get (PROP_USERNAME).'\'';
		}
	}
	
	/**
	 * Handles submission of story form data
	 * This will take care of retrieving the POST parameters,
	 *	then using the data, if valid, to add to or change
	 *	the databse.
	 * 
	 * @param bool $editForm True if the data being submitted
	 *					is to be used for editing the database, false
	 *					indicates that it's for adding.
	 * @return bool True if the submission was successful, false otherwise.
	 **/
	function handleFormSubmit ($editForm) {
	
		// Make sure that if this is an edit form then
		//	the id field has a valid ID number. The update
		//	will verify that it's in the database.
		if (!$editForm || ($editForm && $this->get (PROP_USERNAME))) {		
				
			// Now set the new values
			
			// First and last name are not required so they can 
			//	be empt6y.
			$this->set ('first_name', $GLOBALS['APP']->queryPostValue ('first_name'));
			$this->set ('last_name', $GLOBALS['APP']->queryPostValue ('last_name'));
			$this->set ('email', $GLOBALS['APP']->queryPostValue ('email'));				
			$this->set (PROP_USERNAME, $this->queryPostValue ('username'));
			$this->set ('email_notify_new_story', $GLOBALS['APP']->queryPostValue ('notify_story') == '' ? false : true);
			$this->set ('email_notify_new_scene_fork', $GLOBALS['APP']->queryPostValue ('notify_scene_fork') == '' ? false : true);				
			$this->set ('email_notify_updates', $this->queryPostValue ('notify_updates') == '' ? false : true);
			$GLOBALS['APP']->rememberValue ('REGISTRATION_USER_OBJ', $this);
			
			// Get the username if this is a registration form.
			if (!$editForm) {
				if (!$this->_checkUsernameFormat()) {
					// invalid username
					return false;
				}
			}
			
			// Validate and set email address
			if (!$this->_checkEmailFormat($this->get ('email'))) {
				return false;
			}			
			
			// Check if the user wanted to enter new passwords.
			//	If they did, then confirm that the passwords
			//	are valid and that they match each other.
			$pass1 = $this->queryPostValue ('password1');
			if ($pass1 != '') {
			
				// Now get the second password and compare
				//	to the first one.	
			    $pass2 = $this->queryPostValue ('password2');
				if ($pass1 == $pass2) {
				 	
					// Validate the password.					
					$this->set ('password', $pass1);
					if ($this->_checkPasswordFormat ()) {
					    
						// Encrypt it.
						$this->set ('password', $this->_encryptPassword ($pass1));
					}
					else {
					
						// There was a problem with the password
						//	format so use the error that was set by
						//	the checker function and 
						return false;
					}
				}
				else {
				 	$this->addError (STR_240, ERROR_TYPE_SERIOUS);
					return false;
				}
			}
			else if (!$editForm) {
				$this->addError (STR_241, ERROR_TYPE_SERIOUS);
				return false;
		    }
			 			 
			if (!$editForm) { 
				return $this->newRegistration();
			}
			else {
				return $this->update ();
			}
		}
		
		return false;
	}

	/** Gathers all the statistics associated with a user and puts them into a smarty accessible array
	 * @param array $array The array that will contain the statistics.
	 */
	function prepareStatisticsSmartyVariables (&$array) {
	
		$array ['stat_active_stories'] = $this->getActiveStoryCount();
		$array ['stat_scenes_added_others'] = $this->getScenesPostedToOtherUserStoriesCount(); 
		$array ['stat_scenes_added_own'] = $this->getScenesPostedToOwnStoriesCount();;
		$array ['stat_forks_added_others'] = $this->getForksPostedToOtherUserStoriesCount();
		$array ['stat_forks_added_own'] = $this->getForksPostedToOwnStoriesCount();
		
		$totals = 0;
		$array ['stat_average_story_rating'] = floatval ($this->getAverageStoryRating ($totals));
		$array ['stat_total_story_ratings'] = $totals;
		$array ['stat_average_scene_rating'] = floatval ($this->getAverageSceneRating ($totals));
		$array ['stat_total_scene_ratings'] = $totals;
		
		$array ['stat_total_ratings_posted'] = $this->getRatedObjectOthersCount ();
		$array ['stat_total_classifications_posted'] = $this->getClassifiedObjectOthersCount();

		$array ['stat_rank'] = $this->calculateRank ();		
	}
	
	/** Initializes smarty variables to display this object
     *	Override for custom object.
     *	@param array[Ref] $array On output, the array containing the object's properties
     *	@param bool $getPropertiesOnly This is ignored with objects of this type.
	*/
	function prepareSmartyVariables (&$array, $getPropertiesOnly=false) {
		
		$array ['first_name'] = $this->get ('first_name');
		$array ['last_name'] = $this->get ('last_name');
		$array ['email'] = $this->get ('email');
		$array [PROP_USERNAME] = $this->get ('username');
		$array ['email'] = $this->get ('email');
		$array ['logged_in'] = $this->isLoggedIn ();
		$array ['email_notify_new_story'] = $this->get ('email_notify_new_story');
		$array ['email_notify_new_scene_fork'] = $this->get ('email_notify_new_scene_fork');
		$array ['email_notify_updates'] = $this->get ('email_notify_updates');
		$array ['user_type'] = $this->get ('user_type');		
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
		$formProperties = array ();
		$formProperties['edit'] = $editForm;
		$smarty->assign ('ss_form', $formProperties);

		$userProperties = array ();		
		$this->prepareSmartyVariables ($userProperties);		
		$smarty->assign ('ss_user', $userProperties);
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
	* 							It's false if the scene will be added to the database.
	* @param mixed $data Associated data with the form - see description for more on this
	* @param bool $afterError True if the form is being displayed after an error occured in a previous submit.
	* @return bool Returns true if the form was displayed successfully.
	*/
	function displayForm ($isEditForm, $data='', $afterError=false) {

		$smarty = new SSSmarty;
		$ss_user = array ();
		$this->prepareFormTemplate ($smarty, $isEditForm);			
	
		$smarty->display ('members/form_user.tpl');					
		
		return true;
	}
	
	function displaySendMessageForm ($toUser)
	{
		$smarty = new SSSmarty;
		$ss_user = array ();
		
		$userProperties = array ();		
		$this->prepareSmartyVariables ($userProperties);		
		$smarty->assign ('ss_from_user', $userProperties);
		
		$toUser->prepareSmartyVariables ($userProperties);		
		$smarty->assign ('ss_to_user', $userProperties);
		
		$smarty->display ('members/form_message.tpl');
	}
	
	/** Returns the total number of forks originated by this author that have been added to a story that was NOT originated by this author
	 * 	@return int The total number of forks
	 */	
	function getForksPostedToOtherUserStoriesCount () {
		
		$forkIDField = $GLOBALS['TABLE_FORK']['fields']['ID'];
		$storyIDField = $GLOBALS['TABLE_STORY']['fields']['ID'];
		$forkStoryIDField = $GLOBALS['TABLE_FORK']['fields']['STORY_ID'];
		$sceneNameField = $GLOBALS['TABLE_FORK']['fields']['NAME'];
		$storyUserIDField = $GLOBALS['TABLE_STORY']['fields']['USER'];
		$forkUserIDField = $GLOBALS['TABLE_FORK']['fields']['USER_ID'];
		$statusField = $GLOBALS['TABLE_FORK']['fields']['STATUS'];
		$forkTable = $GLOBALS['TABLE_FORK']['name'];
		$storyTable = $GLOBALS['TABLE_STORY']['name'];
		$username = $this->get (PROP_USERNAME);		
		$query = "SELECT COUNT(forks.$forkIDField) AS fork_count 
					FROM $forkTable AS forks 
					INNER JOIN $storyTable ON forks.$forkStoryIDField=$storyTable.$storyIDField 
					WHERE $storyTable.$storyUserIDField <> '$username' 
						AND forks.$forkUserIDField = '$username' 
						AND forks.$statusField = ".FORK_STATUS_ACTIVE." 
					GROUP BY forks.$forkUserIDField";
					
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		$count = 0;
		if (!DB::isError ($results)) {
			$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
			$array = $resultObj->fetchRow ();		
			$count = $array['fork_count'];
		}
		return intval ($count);
	}
	
	/** Returns the total number of forks originated by this author that have been added to a story that was also originated by this author
	 * 	@return int The total number of forks
	 */	
	function getForksPostedToOwnStoriesCount () {
		$forkIDField = $GLOBALS['TABLE_FORK']['fields']['ID'];
		$storyIDField = $GLOBALS['TABLE_STORY']['fields']['ID'];
		$forkStoryIDField = $GLOBALS['TABLE_FORK']['fields']['STORY_ID'];
		$sceneNameField = $GLOBALS['TABLE_FORK']['fields']['NAME'];
		$storyUserIDField = $GLOBALS['TABLE_STORY']['fields']['USER'];
		$forkUserIDField = $GLOBALS['TABLE_FORK']['fields']['USER_ID'];
		$statusField = $GLOBALS['TABLE_FORK']['fields']['STATUS'];
		$forkTable = $GLOBALS['TABLE_FORK']['name'];
		$storyTable = $GLOBALS['TABLE_STORY']['name'];
		$username = $this->get (PROP_USERNAME);		
		$query = "SELECT COUNT(forks.$forkIDField) AS fork_count
					FROM $forkTable AS forks 
					INNER JOIN $storyTable ON forks.$forkStoryIDField=$storyTable.$storyIDField 
					WHERE $storyTable.$storyUserIDField = '$username' 
						AND forks.$forkUserIDField = '$username' 
						AND forks.$statusField = ".FORK_STATUS_ACTIVE." 
					GROUP BY forks.$forkUserIDField";
					
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		echo mysql_error ();
		//echo $query;

		$count = 0;
		if (!DB::isError ($results)) {
			$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
			$array = $resultObj->fetchRow ();		
			$count = $array['fork_count'];
		}
		
		return intval ($count);
	}
	
	/** Sends a message to the given user with the given subject and message
	 *  @param SSUser $touser The user object to receive the message
	 *	@param string $subject The subject text
	 *	@param string $message The message text
	 *	@return bool Returns true if the message was sent successfully.
	 */
	function sendMessage ($touser, $subject, $message)
	{
		$subject = ' Message: '.$subject;
		$message = $this->get('username').' from '.$GLOBALS['site_name'].' has sent you a message:'."\r\n\r\n".$message;
		$message .= "\r\n".'To respond to this user, login in to '.$GLOBALS['site_name'].' then visit ';
		
		$message .= $GLOBALS['baseUrl'].'members/index.php?a=message&username='.$this->get('username');
		
		$from = $GLOBALS['site_name'].' Post Office <notifier@storystream.org>';
		$to = $touser->get ('email');
		
		$notify = new SSNotification;
		return $notify->sendMail($from, $to, $subject, $message);		
	}

	/** Returns the total number of scenes originated by this author that have been added to a story that was NOT originated by this author
	 * 	@return int The total number of scenes
	 */	
	function getScenesPostedToOtherUserStoriesCount () {
		
		$sceneIDField = $GLOBALS['TABLE_SCENE']['fields']['ID'];
		$storyIDField = $GLOBALS['TABLE_STORY']['fields']['ID'];
		$sceneStoryIDField = $GLOBALS['TABLE_SCENE']['fields']['STORYID'];
		$sceneNameField = $GLOBALS['TABLE_SCENE']['fields']['NAME'];
		$storyUserIDField = $GLOBALS['TABLE_STORY']['fields']['USER'];
		$sceneUserIDField = $GLOBALS['TABLE_SCENE']['fields']['USER'];
		$statusField = $GLOBALS['TABLE_SCENE']['fields']['STATUS'];
		$sceneTable = $GLOBALS['TABLE_SCENE']['name'];
		$storyTable = $GLOBALS['TABLE_STORY']['name'];
		$username = $this->get (PROP_USERNAME);		
		$query = "SELECT COUNT(scenes.$sceneIDField) AS scene_count
					FROM $sceneTable AS scenes 
					INNER JOIN $storyTable ON scenes.$sceneStoryIDField=$storyTable.$storyIDField 
					WHERE $storyTable.$storyUserIDField <> '$username' 
						AND scenes.$sceneUserIDField = '$username' 
						AND scenes.$statusField = ".SCENE_STATUS_ACTIVE." 
					GROUP BY scenes.$sceneUserIDField";
					
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		$count = 0;
		if (!DB::isError ($results)) {
			$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
			$array = $resultObj->fetchRow ();		
			$count = $array['scene_count'];
		}
		return intval ($count);
	}
	
	/** Returns the total number of scenes originated by this author that have been added to a story that was also originated by this author
	 * 	@return int The total number of scenes
	 */	
	function getScenesPostedToOwnStoriesCount () {
		$sceneIDField = $GLOBALS['TABLE_SCENE']['fields']['ID'];
		$storyIDField = $GLOBALS['TABLE_STORY']['fields']['ID'];
		$sceneStoryIDField = $GLOBALS['TABLE_SCENE']['fields']['STORYID'];
		$sceneNameField = $GLOBALS['TABLE_SCENE']['fields']['NAME'];
		$storyUserIDField = $GLOBALS['TABLE_STORY']['fields']['USER'];
		$sceneUserIDField = $GLOBALS['TABLE_SCENE']['fields']['USER'];
		$statusField = $GLOBALS['TABLE_SCENE']['fields']['STATUS'];
		$sceneTable = $GLOBALS['TABLE_SCENE']['name'];
		$storyTable = $GLOBALS['TABLE_STORY']['name'];
		$username = $this->get (PROP_USERNAME);		
		$query = "SELECT COUNT(scenes.$sceneIDField) AS scene_count
					FROM $sceneTable AS scenes 
					INNER JOIN $storyTable ON scenes.$sceneStoryIDField=$storyTable.$storyIDField 
					WHERE $storyTable.$storyUserIDField = '$username' 
						AND scenes.$sceneUserIDField = '$username' 
						AND scenes.$statusField = ".SCENE_STATUS_ACTIVE." 
					GROUP BY scenes.$sceneUserIDField";
					
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		$count = 0;
		if (!DB::isError ($results)) {
			$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
			$array = $resultObj->fetchRow ();		
			$count = $array['scene_count'];
		}
		
		return intval ($count);
	}
	
	/** Returns the total number of active stories originated by this user
	 * 	@return int The total number of stories
	 */	
	function getActiveStoryCount () {
	
		$storyIDField = $GLOBALS['TABLE_STORY']['fields']['ID'];
		$storyUserIDField = $GLOBALS['TABLE_STORY']['fields']['USER'];
		$sceneUserIDField = $GLOBALS['TABLE_SCENE']['fields']['USER'];
		$statusField = $GLOBALS['TABLE_SCENE']['fields']['STATUS'];
		$sceneTable = $GLOBALS['TABLE_SCENE']['name'];
		$storyTable = $GLOBALS['TABLE_STORY']['name'];
		$username = $this->get (PROP_USERNAME);		
		$query = "SELECT COUNT($storyIDField) AS story_count
					FROM $storyTable 
					WHERE $storyUserIDField = '$username' 
						AND $storyTable.$statusField = ".STORY_STATUS_ACTIVE;
					
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		$count = 0;
		if (!DB::isError ($results)) {
			$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
			$array = $resultObj->fetchRow ();		
			$count = $array['story_count'];
		}
		
		return intval ($count);
	}
	
	/** Returns the total number of posts made by this user
	 * This includes automatic posts done by the system when a scene or story is submitted.
	 * 	@return int The total number of posts.
	 */	
	function getTotalUserPosts () {
	
		$discuss = new SSDiscussionBoard;
		$DTP = $discuss->getTotalUserPosts($this->get ('phpbb_user_id'));		
		if ($DTP === false) $DTP = 0;	
		return $DTP;	
	}
	
	/** Returns the average scene rating for all of this user's scenes
	 * This is not specific to a single scene but includes all scenes
	 * 	originated by this user that have been rated.
	 *	@param int $totalRatings On output, the total number of ratings that have been posted.
	 * 	@return int The average rating.
	 */	
	function getAverageSceneRating (&$totalRatings) {
	
		$subjectTypeField = $GLOBALS['TABLE_RATING']['fields']['SUBJECT_TYPE'];
		$subjectIDField = $GLOBALS['TABLE_RATING']['fields']['SUBJECT_ID'];
		$ratingField = $GLOBALS['TABLE_RATING']['fields']['RATING'];
		$ratingStoryIDField = $GLOBALS['TABLE_RATING']['fields']['STORY_ID'];
		$storyIDField = $GLOBALS['TABLE_STORY']['fields']['ID'];
		$sceneStoryIDField = $GLOBALS['TABLE_SCENE']['fields']['STORYID'];
		$sceneUserIDField = $GLOBALS['TABLE_SCENE']['fields']['USER'];
		$ratingTable = $GLOBALS['TABLE_RATING']['name'];
		$sceneTable = $GLOBALS['TABLE_SCENE']['name'];
		$storyTable = $GLOBALS['TABLE_STORY']['name'];
	
		$query = "SELECT COUNT(*) AS total_ratings, AVG ($ratingTable.$ratingField) AS avg_rating 
					FROM $ratingTable
					INNER JOIN $sceneTable 
						ON $sceneTable.$sceneStoryIDField = $ratingTable.$ratingStoryIDField 
					WHERE $sceneTable.$sceneUserIDField = '".$this->get (PROP_USERNAME)."'
					GROUP BY $sceneTable.$sceneUserIDField";					
					
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		$avg = 0;
		$totalRatings = 0;
		if (!DB::isError ($results)) {
			$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
			$array = $resultObj->fetchRow ();		
			$totalRatings = $array['total_ratings'];
			$avg = floatval ($array['avg_rating']);			
		}
		
		return $avg;
	}
	
	/** Returns the average story rating for all of this user's stories
	 * This is not specific to a single story but includes all stories
	 * 	originated by this user that have been rated.
	 *	@param int $totalRatings On output, the total number of ratings that have been posted.
	 * 	@return int The average rating.
	 */	
	function getAverageStoryRating (&$totalRatings) {
	
		$subjectTypeField = $GLOBALS['TABLE_RATING']['fields']['SUBJECT_TYPE'];
		$subjectIDField = $GLOBALS['TABLE_RATING']['fields']['SUBJECT_ID'];
		$ratingField = $GLOBALS['TABLE_RATING']['fields']['RATING'];
		$ratingStoryIDField = $GLOBALS['TABLE_RATING']['fields']['STORY_ID'];
		$storyIDField = $GLOBALS['TABLE_STORY']['fields']['ID'];
		$storyUserIDField = $GLOBALS['TABLE_STORY']['fields']['USER'];
		$ratingTable = $GLOBALS['TABLE_RATING']['name'];
		$sceneTable = $GLOBALS['TABLE_SCENE']['name'];
		$storyTable = $GLOBALS['TABLE_STORY']['name'];
	
		$query = "SELECT COUNT(*) AS total_ratings, AVG ($ratingTable.$ratingField) AS avg_rating 
					FROM $ratingTable
					INNER JOIN $storyTable 
						ON $storyTable.$storyIDField = $ratingTable.$ratingStoryIDField 
					WHERE $storyTable.$storyUserIDField = '".$this->get (PROP_USERNAME)."'
					GROUP BY $storyTable.$storyUserIDField";					
					
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		$avg = 0;
		$totalRatings = 0;
		if (!DB::isError ($results)) {

			$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
			$array = $resultObj->fetchRow ();		
			$totalRatings = $array['total_ratings'];
			$avg = floatval ($array['avg_rating']);			
		}
		
		return $avg;
	}
	
	/** Returns the number of times this user has rated an object 
	 * Note that this count will include ratings of his own
	 *	objects.
	 * @return int The count of ratings
	 */
	function getRatedObjectOthersCount () {
		$ratingUserIDField = $GLOBALS['TABLE_RATING']['fields']['USER_ID'];
		$ratingTable = $GLOBALS['TABLE_RATING']['name'];
		$username = $this->get (PROP_USERNAME);		
		$query = "SELECT COUNT(*) AS rating_count
					FROM $ratingTable 
					WHERE $ratingUserIDField = '$username'";
					
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		if (!DB::isError ($results)) {	
			$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
			$array = $resultObj->fetchRow ();
			$count = $array['rating_count'];
		}
		
		return $count;
	}
	
	/** Returns the number of times this user has classified an object 
	 * Note that this count will include classifications of his own
	 *	objects.
	 * @return int The count of classifications
	 */
	function getClassifiedObjectOthersCount () {
		$classifyUserIDField = $GLOBALS['TABLE_CLASSIFICATION']['fields']['USER_ID'];
		$classifyTable = $GLOBALS['TABLE_CLASSIFICATION']['name'];
		$username = $this->get (PROP_USERNAME);		
		$query = "SELECT COUNT(*) AS classify_count
					FROM $classifyTable 
					WHERE $classifyUserIDField = '$username'";
					
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		$count = 0;
		if (!DB::isError ($results)) {
			$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
			$array = $resultObj->fetchRow ();
			$count = $array['classify_count'];
		}
		
		return $count;
	}
	
	/** This will calculate the user's rank based on data in the database
	 *	@return float The rank of the user as a floating point number.
	 */
	function calculateRank () {
	
		// Rank is the calculated as follows:
		//	SP = Stories Posted
		//	SCPY = Scenes Posted to own stories
		//	SCPO = Scenes Posted to others stories
		//	FPY = Forks Posted to own stories
		//	FPO = Forks Posted to other stories
		//	DTP = Discussion Topics Posted
		//	R = Average Rating of Posted Objects (Stories, Scenes)
		//	TR = Total Ratings
		//	RO = Objects you've rated that are not your own.
		//	CO = Objects you've classified that are not your own.
		//
		//	(SP + (SCPY*.5) + (SCPO*2) + (FPY*.5) + (FPO*2) + (DTP*.45) + ((R/5)*TR) + (RO*.5) + (CO*.5)
		
		$SP = $this->getActiveStoryCount();
		$SCPY = $this->getScenesPostedToOtherUserStoriesCount ();		
		$SCPO = $this->getScenesPostedToOwnStoriesCount ();		
		$FPY = $this->getForksPostedToOtherUserStoriesCount ();		
		$FPO = $this->getForksPostedToOwnStoriesCount ();	
		$DTP = $this->getTotalUserPosts();		
		
		$sceneTotal = 0;
		$storyTotal = 0;
		$sceneAvg = $this->getAverageSceneRating ($sceneTotal);
		$storyAvg = $this->getAverageStoryRating ($storyTotal);
		$TR = $sceneTotal + $storyTotal;
		$R = ((floatval ($sceneAvg) + floatval($storyAvg)) / 2.0);
		$RO = $this->getRatedObjectOthersCount();
		$CO = $this->getClassifiedObjectOthersCount();
				
		$rank = (floatval ($SP) + (floatval ($SCPY)*.5) + (floatval ($SCPO)*2) + 
					(floatval ($FPY)*.5) + (floatval ($FPO)*2) + (floatval ($DTP)*.45) + 
					((floatval ($R)/5)*floatval ($TR)) + (floatval ($RO)*.5) + (floatval ($CO)*.5));
				
		// Save this value for quick reference later.
		$this->set (PROP_RANK, $rank);
		
		// Store the rank in the database for future reference.
		$tableConstant = $this->_getTableConstant();
		$rankField = $GLOBALS[$tableConstant]['fields']['RANK'];
		$usernameField = $GLOBALS[$tableConstant]['fields']['USERNAME'];
		$tableName = $GLOBALS[$tableConstant]['name'];
		$query = "UPDATE $tableName SET $rankField=$rank WHERE $usernameField='".$this->get(PROP_USERNAME)."'";  
		$result = $GLOBALS['DBASE']->simpleQuery ($query);
		if (DB::isError ($result)) {
			$this->addNotification (sprintf (STR_242, $result->getMessage()));
		}
		
		return $rank;
	}
	
	
	/** 
	 * Retrieves a list of groups in which the user has the given type of memberhsip
	 * @param int $userType The type of user this user should be listed as in the member table
	 * @return array A list of groups returned or an empty array of none found.
	 */
	function getGroupListByType ($userType) {
		$groups = array ();
		$tableName = $GLOBALS['TABLE_GROUP_USER_MAPPING']['name'];
		$groupIDField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['ID'];
		$usernameField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['USERNAME'];
		$dateField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['DATE'];
		$typeField = $GLOBALS['TABLE_GROUP_USER_MAPPING']['fields']['TYPE'];
				
		$query = "SELECT * FROM $tableName WHERE $usernameField='".$this->get(PROP_USERNAME)."' AND $typeField=".$userType;
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		if (!DB::isError ($results)) {
			$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
			while ($array = $resultObj->fetchrow ()) {
				$group = new SSGroup;
				$group->set (PROP_ID, $array[$groupIDField]);
				if ($group->load ()) {
					$groups[] = $group;
				}
			}
		}
		else {
			$this->addErrorObject ($results, ERROR_TYPE_SERIOUS);
		}
		
		return $groups;	
	}
	
	/** On output, contains a list of all the groups to which
		this user has been invited
		@return array An array of SSGroup objects or an empty array if there are no invitations
	*/
	function getInvitationList () {
		
		return $this->getGroupListByType (GROUP_USER_TYPE_INVITED);
	}
	
	/** On output, contains a list of all the groups which this user is a member of.
		@return array An array of SSGroup objects or an empty array if there are no invitations
	*/
	function getGroupAdminList () {		
			return $this->getGroupListByType (GROUP_USER_TYPE_ADMIN);
	}	
	/** On output, contains a list of all the groups which this user is a member of.
		@return array An array of SSGroup objects or an empty array if there are no invitations
	*/
	function getGroupList () {
		return $this->getGroupListByType (GROUP_USER_TYPE_MEMBER);
	}
}
?>
