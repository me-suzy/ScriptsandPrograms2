<?php
/** @file SSObject.class.php
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

/**  Base class for all objects in the system
	The base object will handle a variety of necessities
	required by most objects in the system.
*/
class SSObject extends PEAR
{
	/** An array of error codes (int) representing the most recent internal errors */
	var $_internalErrors = array ();
	
	/** An array of PEAR_Error error objects */
	var $_errors = array ();
	
	/** An array of notification strings */
	var $_notifications = array ();
	
	/** An array of class properties keyed by property name */
	var $_properties = array ();
	
	/** Retrieves the classes friendly type name
	 * @param bool $asPlural True to return the plural version, false otherwise.
	 * @return string The name as a string
	*  @access public
	 */
	function getTypeName ($asPlural) {		
		return $asPlural ? STR_111 : STR_112;
	}
	
	/** Returns an object type code of OBJECT_TYPE_UNKNOWN if unspecified
	 * @return int The object type value
	 * @access public
	 */
	function getType () {
		return OBJECT_TYPE_NONE;
	}
	
	/**  Adds a notification message to the queue
     *	@param string $message The notification string (can include HTML)
	*/
	function addNotification ($message, $addToGlobalList=true) {
	
		$errObj = new SSError ($message);
		array_push ($this->_notifications, $message);
		if ($addToGlobalList) {
			$GLOBALS['APP']->addGlobalNotification ($message);
		}
	}
	
	/** Removes all notifications from the queue of notifications 
	*/
	function clearNotifications () {
		$this->_notifications = array ();
	}
	
	/** Returns an array of notification objects
     *	@return array An array of strings
	*/
	function getNotifications () {
	
		return $this->_notifications;
	}
	
	/** Adds a new error message to the queue.
     *	@param string $message The message associated with this error.
     *	@param int $type The type of error that this is
     *	@see ERROR_TYPE_WARNING, ERROR_TYPE_SERIOUS, ERROR_TYPE_FATAL
	*/
	function addError ($message, $type, $addToGlobalList=true){

		$errObj = new SSError ($message);
		$this->addErrorObject ($errObj, $type, $addToGlobalList);
	}
	
	/** Adds an error code to the queue that is not meant to be seen by the user
		@param int $errorcode One of the error codes enumerated in const.inc.php
	*/
	function addInternalError ($errorcode) {
		$this->_internalErrors[] = $errorcode;
	}
	
	/** Adds a new error message object to the queue.
     *	Unlike addError, this will take a preexisting error
     *	object and add it to the queue.
     *	@param string $message The message associated with this error.
     *	@param int $type The type of error that this is
     *	@see ERROR_TYPE_WARNING, ERROR_TYPE_SERIOUS, ERROR_TYPE_FATAL
	*/
	function addErrorObject ($obj, $type, $addToGlobalList=true) {
			
		if (is_subclass_of ($obj, 'PEAR_Error')) {
					
			array_push ($this->_errors, $obj);
			if ($addToGlobalList)
				$GLOBALS['APP']->addGlobalError ($obj, $type);
			}
	}
	
	/** Returns an array of error objects
     *	@return array An array of PEAR_Error objects
	*/
	function getErrors () {
	
		return $this->_errors;
	}
	
	/** Returns the number of errors in the queue.
     *	@return int The number of errors in the queue.
	*/
	function getErrorCount () {
	
		return count ($this->_errors);
	}
	
	/** Removes all errors from the queue of errors 
	*/
	function clearErrors () {
		
		$this->_errors = array ();
	}
	
	/** Adds/Modifies a property for the class 
     *	Use this instead of 'set' when adding 
     *	a property to the list of properties.  Use
     *	set when changing an existing property.
     *	@access protected
     *	@param mixed $key The name of the property
     *	@param mixed $value The value of the property
	*/
	function _addProperty ($key, $value) {
		
		$this->_properties[$key] = $value;
	}

	/** Determines if the given property exists for this object 
	 * @param string $key The property to check for 
	 * @return bool True if the property exists for this object, false otherwise
	 */
	function hasProperty ($key) {
		return isset ($this->_properties[$key]);
	}
	
	/**  Retrieves the value of a property named '$key'
     *	Override this to change the behaviour of the get.
     *	@access public
     *	@param mixed $key The name of the property
     *	@return mixed The reference to the value
	*/
	function get ($key) {
	
		// If we're debugging and the property does not exist then 
		//	find out who called this function so we can give a more
		//	detailed error description.
		if (!isset ($this->_properties [$key]) && SS_DEBUG) {		
			$trace = debug_backtrace ();
			$msg = '';
			$msg .= '<br>'.STR_114.': '.$key;
			$msg .= '<br>'.STR_115.': '.get_class ($this);
			$msg .= '<br>'.STR_116.': ';
			foreach ($trace as $stackPosition) {
				$msg .= '<strong>'.$stackPosition['function']. '</strong> < ';
			}
			$msg .= '<strong>SCRIPT</strong>';
			
			trigger_error ($msg);
			return '';
		}
		
		return @$this->_properties [$key];
	}

	/**  Sets the value of a property named '$key'
     *	Override this to change the behavior of the set.  Note that
     *	this function will make a copy of the given value. When you
     *	do a get, though, it will return a reference to the object
     *	stored in the list.
     *	@access public
     *	@param mixed $key The name of the property
     *	@return boolean True if the property exists and was set, false otherwise.
	*/
	function set ($key, $value) {
	
		if (array_key_exists ($key, $this->_properties)) {
			$this->_properties [$key] = $value;
			return true;
		}
		
		return false;
	}
	
	/**  Gets the GET value for the named property
     *	@param string $name The name of the property
     *	@param mixed $defaultValue The value to return if the property does not exist
     *	@return mixed The value of the given property or the default value if it does not exist
	*/
	function queryGetValue ($name, $defaultValue='') {
	
		if (isset ($_GET[$name])) {
			return $_GET[$name];
		}
		
		return $defaultValue;
	}

	/**  Gets the POST value for the named property
     *	@param string $name The name of the property
     *	@param mixed $defaultValue The value to return if the property does not exist
     *	@return mixed The value of the given property or the default value if it does not exist
	*/
	function queryPostValue ($name, $defaultValue='') {
	
		if (isset ($_POST[$name])) {
			return $_POST[$name];
		}
		
		return $defaultValue;
	}

	/**  Gets the GET or POST value for the named property
     *	This checks the GET parameters first, then the POST
     *	@param string $name The name of the property
     *	@param mixed $defaultValue The value to return if the property does not exist
     *	@return mixed The value of the given property or the default value if it does not exist
	*/
	function queryValue ($name, $defaultValue='') {
	
		if (isset ($_GET[$name])) {
			return $_GET[$name];
		}
		else if (isset ($_POST[$name])) {
			return $_POST[$name];
		}
		
		return $defaultValue;
	}
	
	/**  This will add an error to the queue and send an email to karim
     *	@param string $description The error's description
	*/
	function addCriticalError ($description) {
		
		$crlf = "\r\n";
		$subject = 'StoryStream Critical Error From '.$_SERVER['HTTP_HOST'];
		$msg = 'Script: '.$_SERVER['SCRIPT_NAME'].$crlf;
		$msg .= 'Error: '.$description;
		$msg .= 'Time: '.date ('F j, Y, g:i a', time ());
		
		// Send the record to someone
		mail ('storystream@karimspot.com', $subject, $msg, 'From: storystream@karimspot.com');
		
		// Now go ahead and add the error to the queue.
		$this->addError ($description, ERROR_TYPE_SERIOUS);
	}
	
	/** Initializes smarty variables to display this object
	 *  Override for custom object.
	 *  @param TSmarty[Ref] $smarty A reference to the smarty object that will be populated
	 */
	function prepareSmartyVariables (&$smarty) {
	
		// BASE DOES NOTHING
	}
	
	/** Returns the user object of the user who owns this object
	 * This requires that the class includes a property called
	 * user_id.
	 * @param SSObject $object An object based on SSObject
	 * @return mixed Either returns the SSUser owner or NULL if user
	 *			user is not supported.
	 */
	function getObjectOwner ($object) {
		
		if ($this->hasProperty ('user_id')) {
			$user = new SSUser;
			$user->set (PROP_ID, $this->get (PROP_USERNAME));
			if ($user->load ()) {
				return $user;
			}
		}
		
		return NULL;
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
		
		// Return the key name itself if this is not overridden.
		return $key;
	}	
};
?>