<?php

/** @file SSRating.class.php
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
 *	@date March, 2004
 */

/** Represents a single rating of any type of object
  * The rating class represents a rating record in 
  *	the database. Ratings are input from the user for
  * objects like stories, scenes and other authors.
  * @author Karim Shehadeh
  *	@date 3/8/2004
  */
class SSRating extends SSTableObject
{
	/** Constructor: Adds required properties */
	function SSRating () {
		parent::SSTableObject ();
	}	

	/** Retrieves the classes friendly type name
	 * @param bool $asPlural True to return the plural version, false otherwise.
	 * @return string The name as a string
	 *  @access public
	 */

	function getTypeName ($asPlural) {		
		return $asPlural ? STR_120 : STR_121;
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
		$this->_addProperty ('subject_type', 0);
		$this->_addProperty ('subject_id', 0);
		$this->_addProperty ('story_id', 0);
		$this->_addProperty ('rating', 0);
		$this->_addProperty ('date', 0);
		$this->_addProperty ('weight', 0);
		$this->_addProperty ('ip', '');
		$this->_addProperty ('comment', '');
		$this->_addProperty ('client_info', '');
		$this->_addProperty ('classification_id', 0);
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
					$GLOBALS[$tableConstant]['fields']['SUBJECT_TYPE'] => $this->get ('subject_type'),
					$GLOBALS[$tableConstant]['fields']['SUBJECT_ID'] => $this->get ('subject_id'),
					$GLOBALS[$tableConstant]['fields']['STORY_ID'] => $this->get ('story_id'),
					$GLOBALS[$tableConstant]['fields']['RATING'] => $this->get (PROP_RATING),
					$GLOBALS[$tableConstant]['fields']['WEIGHT'] => $this->get ('weight'),
					$GLOBALS[$tableConstant]['fields']['DATE'] => $this->get ('date'),
					$GLOBALS[$tableConstant]['fields']['RATING_IP'] => $this->get ('ip'),
					$GLOBALS[$tableConstant]['fields']['COMMENT'] => $this->get ('comment'),
					$GLOBALS[$tableConstant]['fields']['CLASSIFICATION_ID'] => $this->get ('classification_id'),
					$GLOBALS[$tableConstant]['fields']['CLIENT_INFO'] => $this->get ('client_info')
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
		$this->set ('subject_type', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['SUBJECT_TYPE']]);
		$this->set ('subject_id', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['SUBJECT_ID']]);
		$this->set ('story_id', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['STORY_ID']]);
		$this->set ('rating', $dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['RATING']]);
		$this->set ('classification_id', $dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['CLASSIFICATION_ID']]);
		$this->set ('weight', $dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['WEIGHT']]);
		$this->set ('date', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['DATE']]);
		$this->set ('ip', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['RATING_IP']]);
		$this->set ('comment', stripslashes (@$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['COMMENT']]));
		$this->set ('client_info', $dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['CLIENT_INFO']]);
		return $this->requiredFieldsValid ();
	}

    /**
     * Gets the associative array required to mark a record as deleted
     * @param bool $checkKey If this is true, then the unique key field for the object is checked for validity as well
     * @return array An associative (key>value) array populated with the keys/values required to mark the object as deleted
     */
    function _getDBKeyValueForDelete ()
    {
		return array ();
    } 
	
	/**  Gets the key string for the associative array that contains field information for the table associated with this object.
     *	@see tables.inc.php
     *	@return string The key field string for the assocative array that contains field information for the table associated with this object.
	 */
	function _getTableConstant () {
		return 'TABLE_RATING';
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
			$this->addError (STR_123, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get('subject_type') == '') {
			$this->addError (STR_124, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get('subject_id') == '') {
			$this->addError (STR_125, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}

		if ($this->get('rating') == '') {
			$this->addError (STR_126, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}		
		
		if ($checkKey) {
			if ($this->get (PROP_ID) == '') {
				$this->addError (STR_12, ERROR_TYPE_SERIOUS);
				$invalidField = true;
			}
		}
		
		return !$invalidField;
	}

	/**
	 * Adds smarty variables to the given smarty object to prepare 
	 *  the scene form for display. 
	 * 
	 * @param Smarty $smarty The smarty object to get the details of the form
	 * @param bool $editForm Whether or not we're constructing an edit form or a creation form.
	 **/
	function prepareFormTemplate (&$smarty, $editForm=false) {
		
		// This is NEVER an editing form so ignore that parameter.
		
		$array = array ();
		
		// Prepare the rating combo
		$array['rating']['values'] = array ();
		$array['rating']['output'] = array ();
		array_push ($array['rating']['values'],0);
		array_push ($array['rating']['output'],'Not Rated');
		foreach ($GLOBALS['ratings'] as $key=>$value) {
			array_push ($array['rating']['values'], $value);	
			array_push ($array['rating']['output'], $key);
		}		
		$array['rating']['selected'] = 0;
		
		$smarty->assign ('rating_form', $array);
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
	
	/**  This will create a new rating in the database
	 * 	Often, classifications and ratings are added together.  This
	 *	will automatically classify if a non-empty string is given in
	 *	the classification parameter.
	 
     *	@param SSObject $object The object being rated
     *	@param int $rating The rating for the object - an integer between 1 and 10
     *	@param string $comment A string of text passed along with the rating.
     *	@return bool Returns true if the rating was created, false otherwise.						
	 */
	function rate ($object, $rating, $comment='', $classification='') {
	
		$this->set ('subject_type', $object->getType());
		$this->set ('subject_id', $object->get (PROP_ID));
		$this->set ('rating', $rating);
		$this->set ('date', time ());
		$this->set ('ip', $_SERVER['REMOTE_ADDR']);
		$this->set ('client_info', $_SERVER['HTTP_USER_AGENT']);
		$this->set ('comment', $comment);
		$this->set ('classification_id', 0);											
		
		// Keep track of the associated story (if there is one) for future reference.
		if ($object->getType() == OBJECT_TYPE_STORY) {
			$this->set ('story_id', $object->get (PROP_ID));
		}
		else {
			$this->set ('story_id', $object->hasProperty (PROP_STORY_ID) ? $object->get (PROP_STORY_ID) : 0);
		}

		$user = $GLOBALS['APP']->getLoggedInUserObject ();
		if ($user) {
			$this->set (PROP_USERNAME, $user->get ('username'));				
			$this->set ('weight', $this->calculateWeight ());
			if ($this->add ()) {
				
				return true;
			}
		}
		else {
			$this->addError (STR_128, ERROR_TYPE_SERIOUS);
		}
		
		return false;
	}

	/** Initializes smarty variables to display this object
     *	Override for custom object.
     *	@param array[Ref] $array On output, the array containing the object's properties
     *	@param bool $getPropertiesOnly This should be true if the caller would like to retrieve
     *			data such as name, story id, description rather than detailed information
     *			about the scenes that this fork points to.
	 */
	function prepareSmartyVariables (&$array, $getPropertiesOnly=false) {

		$array ['rating'] = $this->get (PROP_RATING);
		$array ['rating_name'] = '';
		foreach ($GLOBALS['ratings'] as $rating=>$value) {
			if ($value == $array['rating']) {
				$array ['rating_name'] = $rating;
			}
		}
		
		$rname = 'Uknown Rating';
		foreach ($GLOBALS['ratings'] as $name=>$value) {
			if ($value == $this->get (PROP_RATING)) {
				$rname = $name;
				break;
			}
		}
		
		$array ['rating_name'] = $rname;
		
		$array ['weight'] = $this->get ('weight');
		$array ['id'] = $this->get (PROP_ID);
		$array ['user_id'] = $this->get (PROP_USERNAME);
		
		$array ['subject_type'] = $this->get ('subject_type');
		$array ['subject_id'] = $this->get ('subject_id');
		$array ['subject_type_values'] = array ('fork'=>OBJECT_TYPE_FORK, 'scene'=>OBJECT_TYPE_SCENE, 'story'=>OBJECT_TYPE_STORY);

		$subject = generateObject ($this->get ('subject_type'), $this->get ('subject_id'));		
		$array ['subject'] = array ();
		$subject->prepareSmartyVariables ($array['subject']);

		$array['story_id'] = $this->get (PROP_STORY_ID);
		
		$array ['comment'] = stripslashes ($this->get ('comment'));		
		$array ['ip'] = $this->get ('ip');
		$array ['client_info'] = $this->get ('client_info');
	}
	
	/** Calculates the weight based on the user_id stored in the object
	 *
	 * The weight property allows certain users to lend more weight
	 *	to their rating than other users' ratings.
	 *
	 * @return int The new weight for the rating or -1 if the value could not be calculated.
	 */
	function calculateWeight () {
		
		// Always one for now.
		return 1;
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
	function handleFormSubmit ($editForm=false) {
		// Make sure that everything went okay with the load
		//	before processing the edit.
		if (!$editForm || ($editForm && $this->get (PROP_ID) > 0)) {

			// Now get the new values 
			$rating = $GLOBALS['APP']->queryPostValue ('rating');
			$comment = $GLOBALS['APP']->queryPostValue ('rating_comment');

			// Now get the object being rated
			$object = generateObject ($GLOBALS['APP']->queryPostValue ('subject_type'), $GLOBALS['APP']->queryPostValue ('subject_id'));
			if ($object) {
				
				// Attempt to rate the object.
				return $this->rate ($object, $rating, $comment);
			}
			else {
				$this->addError (STR_129, ERROR_TYPE_SERIOUS);
			}
		}

		return false;
	}

	/**  Displays the object on the user's browser using the appropriate view template
     *		The appropriate view template is specific to the object's type
	 */
	function view () {
		
		if ($this->requiredFieldsValid (true)) {
			
			$array = array ();
			$this->prepareSmartyVariables ($array);
			
			$smarty = new SSSmarty;
			$smarty->assign ('ss_rating', $array);
		}
		else {
			$this->addError (STR_130, ERROR_TYPE_SERIOUS);
		}
	}
}

?>
