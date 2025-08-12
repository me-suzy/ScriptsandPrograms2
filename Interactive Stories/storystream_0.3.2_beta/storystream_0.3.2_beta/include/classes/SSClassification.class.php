<?php
/** @file SSClassification.class.php
 * 	
 * 	Contains class that represents a user's classification of a storystream object.
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

/** Represents a single classification of any type of object
  *	<p>
  * Objects, particularly scenes and stories, can be classified
  *	using one of the built-in or custom classifications.  These
  *	classifications can help visitors to the site determine which
  *	stories or streams should be read.
  * @author Karim Shehadeh
  *	@date 3/8/2004
  */
class SSClassification extends SSTableObject
{
	/** Constructor: Adds required properties */
	function SSClassification () {
		parent::SSTableObject ();
	}	
	
	/** Retrieves the classes friendly type name
	 * @param bool $asPlural True to return the plural version, false otherwise.
	 * @return string The name as a string
	 *  @access public
	 */
	function getTypeName ($asPlural) {		
		return $asPlural ? 'classifications' : 'classification';
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
		$this->_addProperty ('subject_type', '');
		$this->_addProperty ('subject_id', 0);
		$this->_addProperty (PROP_STORY_ID, 0);
		$this->_addProperty ('classification', '');
		$this->_addProperty ('weight', '');
		$this->_addProperty ('rating_id', 0);
		$this->_addProperty ('ip', '');
		$this->_addProperty ('client_info', '');
		$this->_addProperty ('date', 0);
		$this->_addProperty ('comment', '');
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
					$GLOBALS[$tableConstant]['fields']['STORY_ID'] => $this->get (PROP_STORY_ID),
					$GLOBALS[$tableConstant]['fields']['CLASSIFICATION'] => $this->get ('classification'),
					$GLOBALS[$tableConstant]['fields']['WEIGHT'] => $this->get ('weight'),
					$GLOBALS[$tableConstant]['fields']['RATING_ID'] => $this->get ('rating_id'),
					$GLOBALS[$tableConstant]['fields']['DATE'] => $this->get ('date'),
					$GLOBALS[$tableConstant]['fields']['IP'] => $this->get ('ip'),
					$GLOBALS[$tableConstant]['fields']['CLIENT_INFO'] => $this->get ('client_info'),
					$GLOBALS[$tableConstant]['fields']['COMMENT'] => $this->get ('comment')
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
		$this->set (PROP_STORY_ID, @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['STORY_ID']]);
		$this->set ('classification', $dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['CLASSIFICATION']]);
		$this->set ('rating_id', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['RATING_ID']]);
		$this->set ('weight', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['WEIGHT']]);
		$this->set ('date', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['DATE']]);
		$this->set ('ip', $dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['IP']]);
		$this->set ('client_info', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['CLIENT_INFO']]);
		$this->set ('comment', @$dbFieldsAndValues[$GLOBALS[$tableConstant]['fields']['COMMENT']]);
		
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
		return 'TABLE_CLASSIFICATION';
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
			$this->addError (STR_29, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get('subject_type') == '') {
			$this->addError (STR_30, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}
		if ($this->get('subject_id') == '') {
			$this->addError (STR_31, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}

		if ($this->get('classification') == '') {
			$this->addError (STR_32, ERROR_TYPE_SERIOUS);
			$invalidField = true;
		}		
		
		if ($checkKey) {
			if ($this->get (PROP_ID) == '') {
				$this->addError (STR_33, ERROR_TYPE_SERIOUS);
				$invalidField = true;
			}
		}
		
		return !$invalidField;
	}

	/** Initializes smarty variables to display this object
     *	Override for custom object.
     *	@param array[Ref] $array On output, the array containing the object's properties
     *	@param bool $getPropertiesOnly This should be true if the caller would like to retrieve
     *			data such as name, story id, description rather than detailed information
     *			about the scenes that this fork points to.
	 */
	function prepareSmartyVariables (&$array, $getPropertiesOnly=false) {

		$array ['classification'] = $this->get ('classification');
		$array ['weight'] = $this->get ('weight');
		$array ['id'] = $this->get (PROP_ID);
		$array ['user_id'] = $this->get (PROP_USERNAME);
		$array ['comment'] = stripslashes ($this->get ('comment'));
		$array ['subject_type'] = $this->get ('subject_type');
		$array ['subject_type_values'] = array ('fork'=>OBJECT_TYPE_FORK, 'scene'=>OBJECT_TYPE_SCENE, 'story'=>OBJECT_TYPE_STORY);
		$array ['story_id'] = $this->get (PROP_STORY_ID);

		$subject = generateObject ($this->get ('subject_type'), $this->get ('subject_id'));		
		$array ['subject'] = array ();
		$subject->prepareSmartyVariables ($array['subject'], true);
	}

	/** Confirms that the given classification can be used with the given object
	 * 	The only classifications that can be used with an object are those that
	 * 	have either been custom defined by the author of the associated story or
	 *	those that are built-in to storystream.
	 *	@param SSObject $object A classifiable object.
	 *	@param string $classification The 
	 * 	@return bool True if the confirmation is valid, false otherwise.
	 *	@access public
	 *	@access static
	 */
	function checkClassification ($object, $classification) {
				
		return true;
		
		/* CLASSIFICATION LISTS NOT SUPPORTED YET
		$tableName = $GLOBALS['TABLE_CLASSIFICATION_LIST']['name'];
		$classField = $GLOBALS['TABLE_CLASSIFICATION_LIST']['fields']['CLASSIFICATION'];
		$subjTypeField = $GLOBALS['TABLE_CLASSIFICATION_LIST']['fields']['SUBJECT_TYPE'];
		$subjIDField = $GLOBALS['TABLE_CLASSIFICATION_LIST']['fields']['SUBJECT_ID'];
		
		$query = 'SELECT * FROM '.$tableName.' WHERE '.$classField.'="'.$classification.
					'" AND (('.$subjTypeField.'='.$object->getType().' AND '.$subjIDField.'='.$object->get (PROP_ID).') 
					OR ('.$subjTypeField.'='.OBJECT_TYPE_NONE.'))';
					
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		if (!DB::isError ($results)) {
			return ($GLOBALS['DBASE']->numRows($results) > 0);
		}
		else {
			$this->addError ("Unable to confirm that given classification is valid for the selected object", ERROR_TYPE_SERIOUS);
		}
		
		return false;
		*/
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
		
		if (!$editForm || ($editForm && $this->get (PROP_ID) > 0)) {

			// Now get the new values 
			$classification = $GLOBALS['APP']->queryPostValue ('classification');
			$comment = $GLOBALS['APP']->queryPostValue ('classification_comment');

			// Now get the object being rated
			$object = generateObject ($GLOBALS['APP']->queryPostValue ('subject_type'), $GLOBALS['APP']->queryPostValue ('subject_id'));
			if ($object) {
				
				// Attempt to rate the object.
				return $this->classify ($object, $classification, $comment);
			}
			else {
				$this->addError (STR_34, ERROR_TYPE_SERIOUS);
			}
		}

		return false;
	}	
	/** This will create a new classification in the database
	 * 	This will handle calculating the weight of the classification
	 *	based on the logged in user.
     *	@param SSObject $object The object being classified
     *	@param string $classification The classification for the object - a string stored in the classifications database
     *	@return bool Returns true if the classification was created, false otherwise.						
	 */
	function classify ($object, $classification, $comment) {
		
		if ($this->checkClassification ($object, $classification)) {
			
			$storyID = 0;
			if ($object->getType () == OBJECT_TYPE_STORY) {
				$storyID = $object->get (PROP_ID);
			}
			else if ($object->hasProperty (PROP_STORY_ID)) {
				$storyID = $object->get (PROP_STORY_ID);
			}
			
			$this->set ('subject_type', $object->getType());
			$this->set ('subject_id', $object->get (PROP_ID));
			$this->set ('classification', $classification);
			$this->set ('comment', $comment);
			$this->set (PROP_STORY_ID, $storyID);
			
			$user = $GLOBALS['APP']->getLoggedInUserObject ();
			if ($user) {
				$this->set (PROP_USERNAME, $user->get ('username'));				
				$this->set ('weight', $this->calculateWeight ());
				
				$this->set ('date', time ());
				$this->set ('ip', $_SERVER['REMOTE_ADDR']);
				$this->set ('client_info', $_SERVER['HTTP_USER_AGENT']);
						
				if ($this->add ()) {
					return true;
				}
			}
			else {
				$this->addError ("You must be logged in to classify a story, scene or stream", ERROR_TYPE_SERIOUS);
			}
		}
		else {
			$this->addError ("The given classification value ('".$classification."') is not applicable to 
								the given ".$object->getTypeName(false), ERROR_TYPE_SERIOUS);
		}
		
		return false;
	}

	/** Calculates the weight based on the user_id stored in the object
	 *
	 * The weight property allows certain users to lend more weight
	 *	to their classification than other users'.
	 *
	 * @return int The new weight for the classification or -1 if the value could not be calculated.
	 */
	function calculateWeight () {
		return 1;
	}
	
	/**  Displays the object on the user's browser using the appropriate view template
     *		The appropriate view template is specific to the object's type
	 */
	function view () {
		
		if ($this->requiredFieldsValid (true)) {
			
			$array = array ();
			$this->prepareSmartyVariables ($array);
			
			$smarty = new SSSmarty;
			$smarty->assign ('ss_classification', $array);
		}
		else {
			$this->addError (STR_33, ERROR_TYPE_SERIOUS);
		}
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
		
		// Prepare the classification combo
		$array['classification']['values'] = array ();
		$array['classification']['output'] = array ();
		array_push ($array['classification']['values'],'');
		array_push ($array['classification']['output'],'Not Classified');
		foreach ($GLOBALS['classifications'] as $classification) {
			array_push ($array['classification']['values'], $classification);	
			array_push ($array['classification']['output'], $classification);
		}		
		$array['classification']['selected'] = '';
		
		$smarty->assign ('classification_form', $array);
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
	
}

?>
