<?php
/** @file SSEventHandler.class.php
 *	Copyright (C) 2004  Karim Shehadeh
 *
 * 	Contains the implementation of the SSEventHandler class.
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
 *	@date April, 2004	
 */
 
/** A singleton object that is meant to handle all types of StoryStream events
 *	Whenever an event occurs in StoryStream, a method of this class
 *	should be called to handle any actions that should take place
 *	as a result of the event occuring.
 *	@author Karim Shehade
 *	@date 4/22/2004
 */
class SSEventHandler extends SSObject
{
	/** Called when an object derived from SSTableObject is added to the database
	 *	This function is called automatically if the SSTableObject::add method
	 *	is used to add the object to the database.
	 *	@param SSTableObject $object The object that was added.
	 *	@return bool Returns true if the given object type is handled.
	 */
	function onObjectAddedToDatabase ($object) {
	
		switch ($object->getType()) {
			case OBJECT_TYPE_FORK:
				return $this->onForkAdded ($object);
			case OBJECT_TYPE_SCENE:
				return $this->onSceneAdded ($object);
			case OBJECT_TYPE_STORY:
				return $this->onStoryAdded ($object);
			case OBJECT_TYPE_ANNOUNCEMENT:
				return $this->onAnnouncementAdded ($object);				
			case OBJECT_TYPE_NONE:
			case OBJECT_TYPE_SCENE:
			case OBJECT_TYPE_BOOKMARK:
			case OBJECT_TYPE_CLASSIFICATION:
			case OBJECT_TYPE_VIEW:
			case OBJECT_TYPE_MOD:
			case OBJECT_TYPE_GROUP:
				return true;
			default:
				$this->addError (STR_43, ERROR_TYPE_SERIOUS);
		}
		
		return false;
	}
	/** Called when an object derived from SSTableObject is removed from the database
	 *	This function is called automatically if the SSTableObject::delete method
	 *	is used to delete the object to the database.
	 *	@param SSTableObject $object The object that was edited.
	 *	@return bool Returns true if the given object type is handled.
	 */
	function onObjectRemovedFromDatabase ($object) {
		switch ($object->getType()) {
			case OBJECT_TYPE_FORK:
				return $this->onForkRemoved ($object);
			case OBJECT_TYPE_SCENE:
				return $this->onSceneRemoved ($object);
			case OBJECT_TYPE_STORY:
				return $this->onStoryRemoved ($object);
			case OBJECT_TYPE_NONE:
			case OBJECT_TYPE_SCENE:
			case OBJECT_TYPE_BOOKMARK:
			case OBJECT_TYPE_CLASSIFICATION:
			case OBJECT_TYPE_VIEW:
			case OBJECT_TYPE_GROUP:
			case OBJECT_TYPE_MOD:
			case OBJECT_TYPE_ANNOUNCEMENT:
				return true;
			default:
				$this->addError (STR_44, ERROR_TYPE_SERIOUS);
		}
	}
	
	
	/** Called when an object derived from SSTableObject is edited in the database
	 *	This function is called automatically if the SSTableObject::update method
	 *	is used to update the object to the database.
	 *	@param SSTableObject $object The object that was edited.
	 *	@return bool Returns true if the given object type is handled.
	 */
	function onObjectEditedInDatabase ($object) {
		switch ($object->getType()) {
			case OBJECT_TYPE_FORK:
				return $this->onForkEdited ($object);
			case OBJECT_TYPE_SCENE:
				return $this->onSceneEdited ($object);
			case OBJECT_TYPE_STORY:
				return $this->onStoryEdited ($object);
			case OBJECT_TYPE_NONE:
			case OBJECT_TYPE_SCENE:
			case OBJECT_TYPE_BOOKMARK:
			case OBJECT_TYPE_CLASSIFICATION:
			case OBJECT_TYPE_VIEW:
			case OBJECT_TYPE_GROUP:
			case OBJECT_TYPE_MOD:
				return true;
			default:
				$this->addError (STR_45, ERROR_TYPE_SERIOUS);
		}
	}

	/** Called when a new announcement has been added to the database 
	 * @param SSAnnouncement $announcement The scene that was added.
	 */
	function onAnnouncementAdded ($announcement) {
		
		
		// Notify users.
		$GLOBALS['NOTIFY']->sendAnnouncementNotification ($announcement);
	}

	/** Called when a new fork has been added to the database 
	 * @param SSFork $fork The fork that was added.
	 */
	function onForkAdded ($fork) {
		// Log this add
		$GLOBALS['APP']->addModifyRecord ($fork, MOD_ACTION_ADD);	
		
		// Notify users.
		$GLOBALS['NOTIFY']->sendForkNotification ($fork);
	}
	

	/** Called when a new scene has been added to the database 
	 * @param SSScene $scene The scene that was added.
	 */
	function onSceneAdded ($scene) {
	
		// Log this add
		$GLOBALS['APP']->addModifyRecord ($scene, MOD_ACTION_ADD);	
		
		// Notify users.
		$GLOBALS['NOTIFY']->sendSceneNotification ($scene);
	}
	

	/** Called when a new story has been added to the database 
	 * @param SSStory $story The scene that was added.
	 */
	function onStoryAdded ($story) {
	
		// Log this add
		$GLOBALS['APP']->addModifyRecord ($story, MOD_ACTION_ADD);	
		
		// Notify users.
		$GLOBALS['NOTIFY']->sendStoryNotification ($story);
	}


	/** Called when an existing fork has been edited in the database 
	 * @param SSFork $fork The fork that was edited.
	 */
	function onForkEdited ($fork) {
	
		// Log this edit
		$GLOBALS['APP']->addModifyRecord ($fork, MOD_ACTION_EDIT);
	}
	

	/** Called when an existing scene has been edited in the database 
	 * @param SSScene $scene The fork that was edited.
	 */
	function onSceneEdited ($scene) {
	
		// Log this edit
		$GLOBALS['APP']->addModifyRecord ($scene, MOD_ACTION_EDIT);
	}
	

	/** Called when an existing story has been edited in the database 
	 * @param SSStory $story The story that was edited.
	 */
	function onStoryEdited ($story) {
	
		// Log this edit
		$GLOBALS['APP']->addModifyRecord ($story, MOD_ACTION_EDIT);
	}
	
	/** Called when an existing fork has been edited in the database 
	 * @param SSFork $fork The fork that was edited.
	 */
	function onForkRemoved ($fork) {		
		// Although the record won't be availalble for lookup,
		// 	last known properties will be serialized into the record.
		$GLOBALS['APP']->addModifyRecord ($fork, MOD_ACTION_DELETE);
	}
	

	/** Called when an existing scene has been edited in the database 
	 * @param SSScene $scene The fork that was edited.
	 */
	function onSceneRemoved ($scene) {
		// Although the record won't be availalble for lookup,
		// 	last known properties will be serialized into the record.
		$GLOBALS['APP']->addModifyRecord ($scene, MOD_ACTION_DELETE);
		$this->_removeObjectRatings (OBJECT_TYPE_SCENE, $scene->get ('id'));
		$this->_removeObjectClassifications (OBJECT_TYPE_SCENE, $scene->get ('id'));
		$this->_removeObjectBookmarks (OBJECT_TYPE_SCENE, $scene->get ('id'));
	}
	

	/** Called when an existing story has been edited in the database 
	 * @param SSStory $story The story that was edited.
	 */
	function onStoryRemoved ($story) {
		// Although the record won't be availalble for lookup,
		// 	last known properties will be serialized into the record.
		$GLOBALS['APP']->addModifyRecord ($story, MOD_ACTION_DELETE);
		$this->_removeObjectRatings (OBJECT_TYPE_STORY, $story->get ('id'));
		$this->_removeObjectClassifications (OBJECT_TYPE_STORY, $story->get ('id'));
		$this->_removeObjectBookmarks (OBJECT_TYPE_STORY, $story->get ('id'));
	}
	
	/** Removes all ratings that link to the given object
	 *	@param int $type The type code for the object (e.g. OBJECT_TYPE_STORY)
	 *	@param int $id The ID of the object
	 *	@return bool True if deleted successfully, false otherwise.
	 */
	function _removeObjectRatings ($type, $id) {
	
		$subjectTypeField = $GLOBALS['TABLE_RATING']['fields']['SUBJECT_TYPE'];
		$subjectIDField = $GLOBALS['TABLE_RATING']['fields']['SUBJECT_ID'];
		$ratingTable = $GLOBALS['TABLE_RATING']['name'];
		
		$query = "DELETE FROM $ratingTable WHERE $subjectTypeField=$type AND $subjectIDField=$id";
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		if (DB::isError ($results)) {
			$this->addError ($results, ERROR_TYPE_SERIOUS);
			return false;
		}
		
		return true;
	}
	
	/** Removes all classifications that link to the given object
	 *	@param int $type The type code for the object (e.g. OBJECT_TYPE_STORY)
	 *	@param int $id The ID of the object
	 *	@return bool True if deleted successfully, false otherwise.
	 */
	function _removeObjectClassifications ($type, $id) {
	
		$subjectTypeField = $GLOBALS['TABLE_CLASSIFICATION']['fields']['SUBJECT_TYPE'];
		$subjectIDField = $GLOBALS['TABLE_CLASSIFICATION']['fields']['SUBJECT_ID'];
		$classifyTable = $GLOBALS['TABLE_CLASSIFICATION']['name'];
		
		$query = "DELETE FROM $classifyTable WHERE $subjectTypeField=$type AND $subjectIDField=$id";
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		if (DB::isError ($results)) {
			$this->addError ($results, ERROR_TYPE_SERIOUS);
			return false;
		}
		
		return true;
	}
	
	/** Removes all bookmarks that link to the given object
	 *	@param int $type The type code for the object (e.g. OBJECT_TYPE_STORY)
	 *	@param int $id The ID of the object
	 *	@return bool True if deleted successfully, false otherwise.
	 */
	function _removeObjectBookmarks ($type, $id) {
	
		$subjectTypeField = $GLOBALS['TABLE_BOOKMARKS']['fields']['SUBJECT_TYPE'];
		$subjectIDField = $GLOBALS['TABLE_BOOKMARKS']['fields']['SUBJECT_ID'];
		$bookmarkTable = $GLOBALS['TABLE_BOOKMARKS']['name'];
		
		$query = "DELETE FROM $bookmarkTable WHERE $subjectTypeField=$type AND $subjectIDField=$id";
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		if (DB::isError ($results)) {
			$this->addError ($results, ERROR_TYPE_SERIOUS);
			return false;
		}
		
		return true;
	}
}

?>
