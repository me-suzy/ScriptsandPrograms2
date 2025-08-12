<?php
/** @file SSApp.class.php
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

/** The application object is a singleton that controls all the 
	various contained objects, their interactions and their
	interfaces to the user.
*/
class SSApp extends SSObject 
{	
	/**  Constructor */
	function SSApp () {
		$this->_addProperties ();
	}
	
	function _addProperties () {
		$this->_addProperty ('user_obj','');
		$this->_addProperty ('last_view_record', 0);
		$this->_addProperty ('last_mod_record', 0);			
		$this->_addProperty ('section', 0);
		$this->_addProperty ('debug', '');		
	}
	
	/**  Adds a global error that will be made visible to the user automatically
     *	@param PEARErr $errorObj The error object to add to the global list
     *	@param int $type The type of error this is		
	*/
	function addGlobalError ($errorObj, $type) {
	
		$this->addErrorObject ($errorObj, $type, false);
	}
	
	/**  Adds a global notification that will be made visible to the user automatically
     *	@param string $message The message to add
	*/
	function addGlobalNotification ($message) {
	
		$this->addNotification ($message, false);
	}
	
	/**  This will return an array of error strings
     *	@return array An array of all the error strings queued up.
	*/
	function getGlobalErrors () {

		$errorObjects = $this->getErrors ();	
		$errors = array ();
		if (count ($errorObjects) > 0) {
			foreach ($errorObjects as $err) {
			
				$msg = $err->getMessage();
				array_push ($errors, $msg);
			}
		}
		return $errors;		
	}
	
	/**  Called at the beginning of any root script to establish a logged in user
     *	This will check to see if this session recognizes any user as logged in
     *	and sets up the environment with that user as the logged in user.  It
     *	also stores that user's object in this application object.		
	*/
	function setupUserEnvironment () {
				
		$user = new SSUser;
		
		// Finds the user in the database that is currently active under this session.
		if ($user->setUserFromSessionID (session_id()))	{
		
			// Now compare the user's hash with the session hash (for double confirmation)
			if ($user->getUserHash () == @$_SESSION['USER_HASH']) {			
				
				// Now set the user object to this user.
				$this->set ('user_obj', $user);
			}
		}
		else {
		
			// Make sure that there is no logged in user variables set.
			$this->logoutUser ();
		}
	}
	
	/**  Called at the beginning of any root script to establish a logged in user
     *	This will check to see if this session recognizes any user as logged in
     *	and sets up the environment with that user as the logged in user.  It
     *	also stores that user's object in this application object.		
	*/
	function setupDiscussionEnvironment () {
		
	}
	
	/**  Get the logged in user object
     *	@return SSUser Returns the logged in user object or NULL if there is no logged in user
	*/
	function& getLoggedInUserObject () {
		
		$ret = $this->get ('user_obj');
		return $ret;
	}
	
	/**  Logs in a new user with the given password
     *	@param $username The user's name as entered by the user attempting to login
     *	@param $password The password entered by the user trying to login
     *	@return bool True, if logged in successfully		
	*/
	function loginUser ($username, $password) {
	
		$user = new SSUser;
		if ($user->setUser ($username)) {
		
			// Store the logged in user in the session.
			$_SESSION['USER_HASH'] = $user->getUserHash ();
			
			if ($user->login ($password)) {
			
				$this->set ('user_obj', $user);
				return true;
			}
		}		
		else { 
			$this->addError (STR_11, ERROR_TYPE_SERIOUS, false);
		}
		
		// Clear the user.
		$this->set ('user_obj', '');
		
		return false;
	}
	
	/**  Logs out the logged in user
	*/
	function logoutUser () {
	
		if ($this->isUserLoggedIn ()) {
		
			$user = $this->get ('user_obj');
			$user->logout ();
			$this->set ('user_obj', '');
			$_SESSION['USER_HASH'] = '';
		}
	}
	
	/**  Determines if there's a user logged in.
     *	@return bool True, if logged in successfully		
	*/
	function isUserLoggedIn () {

		$user = $this->get ('user_obj');
		
		if ($this->get ('user_obj') && 
			(@$_SESSION['USER_HASH'] != '')) {
			
			return true;
		}
		
		return false;
	}
	
	/**  Checks the user type of the logged in user against the given user type
     *	@param int $type The type of user to check against.
     *	@return bool True is returned if the user types are equal
	*/
	function verifyUserType ($type) {
	
		if ($this->isUserLoggedIn ()) {
			$user = &$this->get ('user_obj');
			return ($user->get ('user_type') >= $type);
		}
		
		return false;
	}
	
	/**  Use this to remember certain settings within a session
     *	This is useful for remembering form information WITHIN A SESSION.
     *	All this information will be lost when the user's session
     *	is terminated (when the browser is closed, for example).
     *	@param string $key This is the key you will use to retrieve the object later
     *	@param mixed $object A variable you need to store (a copy will be made)
	*/
	function rememberValue ($key, $var) {
	
		if (!isset ($_SESSION['MEMORY'])) {
			$_SESSION['MEMORY'] = array ();
		}
			
		// Store a copy of this object in memory
		$_SESSION['MEMORY'][$key] = $var;
	}
	
	/**  Use this to retrieve a stored session variable 
     *	@param string $key The key used to store the variable
     *	@return mixed The value of the stored variable.
	*/
	function retrieveValue ($key) {
	
		if (isset ($_SESSION['MEMORY']) &&
			isset ($_SESSION['MEMORY'][$key])) {
			
			return $_SESSION['MEMORY'][$key];
		}
		
		return '';
	}
	
	/**
	* Constructs the current URL with parameters
	* @param exclusions array An array of parameter names that should be excluded from the list of attached URL parameters
	* @return string The current URL
	*/
	function _getURLWithParameters ($exclusions=false)
	{	
		$params = '';
	
		if (!$exclusions || !is_array ($exclusions))	
			$exclusions = array();
			
		while (list ($key, $val) = each ($_GET)) 
		{
			// Only add to the list if this is not one of the exclusions
			if (!$exclusions || 
				(!isset ($exclusions[$key]) && (array_search ($key, $exclusions) === false)))
			{
				$params .= "$key=$val&";
			}
		}	
		
		if ($params != '')
		{
			$params = '?'.$params;
		}
		
		reset ($_GET);
	
		// Get rid of the trailing ampersand if there is one.
		if ($params != '')
		{
			if ($params[strlen ($params)-1] == '&')
				$params = substr ($params, 0, strlen ($params)-1);
		}			
		
		$url = basename ($_SERVER['PHP_SELF']).$params;
		return $url;
	}	
	
	/**  Use this to store the current web page
     *	This will not store the page if there is POST
     *	data in the queue because it probably means
     *	that the page is not one we should come back to
     *	without the information.
     *	@see redirectToLastLocation
	*/
	function storeLocation () {
		
		if (count ($_POST) == 0) {
			$_SESSION['LAST_URL'] = $this->_getURLWithParameters ();			
		}
	}
	
	/** Retrieves the last location stored in the session
	 *  @return string The URL of the last location stored in the session
	 *  @access public
	 */
	function getLocation () {
		return isset ($_SESSION ['LAST_URL']) ? $_SESSION ['LAST_URL'] : '';
	}
	
	/**  Redirects the user's browser to the last stored page.
     *	If there is no page stored then the user is redirected
     *	to the front page.
     *	@see storeLocation
	*/
	function redirectToLastLocation () {
			
		$url = $this->getLocation ();
		if ($url) {
			header ('Location:'.$url);
		}
	}

	/**  Converts a class name to a item type that's used in the database
     *	@param string $className The name of the class (case-insensitive)
     *	@return int The item type value or 0 if there was an error.
	*/
	function getItemTypeFromClassName ($className) 
	{
		switch (strtolower ($className)) {
			case 'ssstory':
				return ITEM_TYPE_STORY;
			case 'ssscene':
				return ITEM_TYPE_SCENE;
			case 'ssfork':
				return ITEM_TYPE_FORK;
			default:
				// Unknown item type given
				$this->addCriticalError (STR_12.$className.'"');
				return 0;
		}
	}	
	
	/**  This will add a mod record to the database indicating that the given object was modified by a user
     *	The function will take care of adding the more generic data
     *	like the user who modified it, the time and date, and other info
     *	@param SSTableObject $objectModified This must be a table class derived object
     *	@return mixed A copy of the mod record (SSMod) that was added or false if there was a problem
	*/
	function addModifyRecord ($objectModified, $action) {
	
		if (!is_object ($objectModified)) {
			return false;
		}
		
		$type = $objectModified->getType();
		if ($type != 0) {
		
			$mod = new SSMod;
			$id = $objectModified->getUniqueID ();
			
			if ($id != 0) {
				
				$user = $this->getLoggedInUserObject ();
				$userName = '';
				
				// Modification ALWAYS requires a logged in user.
				if ($user) {
					$userName = $user->get ('username');
					
					$story_id = 0;
					if ($objectModified->getType() == OBJECT_TYPE_STORY) {						
						$story_id = $id;
					}
					else {
						if ($objectModified->hasProperty (PROP_STORY_ID)) {
							$story_id = $objectModified->get (PROP_STORY_ID);
						}
						else {
							$story_id = 0;
						}
					}
					
					$mod->set (PROP_USERNAME, $userName);
					$mod->set ('target_id', $id);
					$mod->set ('target_type', $type);
					$mod->set (PROP_STORY_ID, $story_id);
					$mod->set ('mod_date', time ());
					$mod->set ('mod_ip', $_SERVER['REMOTE_ADDR']);
					$mod->set ('client_info', $_SERVER['HTTP_USER_AGENT']);
					$mod->set ('action', $action);
					$mod->set ('mod_data', serialize ($objectModified));
					
					if ($mod->add ()) {
						return $mod;
					}
				}
				else {
					$this->addCriticalError (sprintf (STR_13, $objectModified->getTypeName(false)));
				}
			}
			else {
				$this->addError (sprintf (STR_14, $objectModified->getTypeName(false)));
			}
		}
		return false;
	}
	
	/**  This will add a view record to the database indicating that the given object was viewed by a user
     *	The function will take care of adding the more generic data
     *	like the user who viewed it, the time and date, and other info
     *	@param SSTableObject $objectViewed This must be a table class derived object
     *	@return bool A copy of the view record (SSView) that was added or false if there was a problem
	*/
	function addViewRecord ($objectViewed) {
	
		$type = $this->getItemTypeFromClassName (get_class ($objectViewed));
		if ($type != 0) {
		
			$view = new SSView;
			$id = $objectViewed->getUniqueID ();
			
			if ($id != 0) {
				
				$user = $this->getLoggedInUserObject ();
				$userName = '';
				
				// If someone is logged in then get the username but
				//	it's not required; guests can view things, too
				if ($user) {
					$userName = $user->get ('username');
				}
	
				$story_id = 0;
				if ($objectViewed->getType() == OBJECT_TYPE_STORY) {
					$story_id = $id;
				}
				else {
					$story_id = $objectViewed->get (PROP_STORY_ID);
				}
							
				$view->set (PROP_USERNAME, $userName);
				$view->set ('target_id', $id);
				$view->set ('target_type', $type);
				$view->set (PROP_STORY_ID, $story_id);
				$view->set ('view_date', time ());
				$view->set ('view_ip', $_SERVER['REMOTE_ADDR']);
				$view->set ('client_info', $_SERVER['HTTP_USER_AGENT']);
				
				if ($view->add ()) {
					return $view;
				}
			}
			else {
				$this->addError (sprintf (STR_15,  get_class ($objectViewed)));
			}
		}
		return false;
	}

	/** Adds a message to the debug window when DEBUG flag is on.
	 *	@param string $text The text of the message to display
	 *  @param string $file The name/path of the file requesting the message
	 *  @param string $function The line number requesting the message
	 *  @param string $line The name of the function requesting the message
	 *  @param string $class The name of the class requesting the message
	 *  @access public
	 */
	function addDebugMessage ($text, $file='', $function='', $line='', $class='') {				
		if (SS_DEBUG) {
			$debug = $this->get ('debug');
			$debug .= '<table width="100%"><tr><td bgcolor="#CC0000"><font color="#FFFFFF"><b>';
			$debug .= 'File: '.basename ($file).' | Function: '.$function.' | Class: '.$class.' | Line: '.$line.'</b></td></tr>';
			$debug .= '<tr><td>'.$text.'</td></tr></table>';
			
			$this->set ('debug', $debug);
		}
	}
	
	/** Determines if the logged in user has permission to add a story
	 *
	 * @return bool Returns true if permission is granted, false otherwise.
	 *  @access public
	 */
	function hasPermissionToAddStory () {
	
		if ($this->isUserLoggedIn()) {
			return true;
		}
	}	

	/** Returns the global theme set in the configuration file
	 * 	The path takes into account the location of the active script.
	 *	@return string The path to the active theme's root directory.
	 */
	function getThemeDir () {		
		return $GLOBALS['SCRIPT_ROOT'].'themes/'.$GLOBALS['global_theme'];
	}
}

?>