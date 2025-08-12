<?php
/** @file SSItemLists.class.php
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
 *	@date April, 2004
 */

/** Generate commonly used collections of various objects
 * 	Unlike a collection, though, the arrays returned are
 *	not extracted solely on the basis of the values in 
 * 	table that contains those items.  Also, more than one
 *	type of object can be in an array returned from this 
 *	class
 *	@author Karim Shehadeh
 * 	@date 4/1/2004
 */
class SSItemLists extends SSObject
{

	/** Returns an array of all or some of the announcements in the list.
	 * 	If the limit is greater than 0 then only the top $limit 
	 *	announcements are retrieved starting from the most recent.
	 *	@param int $limit The maximum number of announcements to retrieve
	 *	@return mixed If successful then an array of SSAnnouncement objects or false if there was a database problem
	 */
	function getAnnouncements ($limit=0) {
	
		$announcementTable = $GLOBALS['TABLE_ANNOUNCEMENTS']['name'];
		$dateField = $GLOBALS['TABLE_ANNOUNCEMENTS']['fields']['DATE'];
		
		// View all announcements
		$query = "SELECT * FROM $announcementTable ORDER BY $dateField DESC";
		if ($limit > 0) {
			$query .= ' LIMIT '.$limit;
		}
		
		$result = $GLOBALS['DBASE']->simpleQuery ($query);
		if (!DB::isError ($result)) {
		
			$resultObj = new DB_result ($GLOBALS['DBASE'],$result);
			$announcements = array ();
			while (($array = $resultObj->fetchRow ())) {

				$announcement = new SSAnnouncement;
				$announcement->_setDBKeyValueArray ($array);
				$announcements[] = $announcement;						
			}
			return $announcements;
		}
		else {
			$this->addErrorObject ($result, ERROR_TYPE_SERIOUS);
			return false;
		}
	}
	
	/** 
	 * Get a list of objects that have been recently changed by the given
	 *	user - use the logged in user if no username is given
	 * @param int $maxCount The maximum number of results to return
	 * @param string $username The name of the user who's activity is to be retrieved.
	 * @return array An array of recently changed objects (derived from SSTableObject)
	 */
	function getUserRecentlyChanged ($maxCount=15, $username='') {
		
		// Default to logged in user.
		if ($username == '') {
			$user = $GLOBALS['APP']->getLoggedInUserObject();
			$username = $user->get (PROP_USERNAME);			
		}
		
		$objects = array ();
		$table = $GLOBALS['TABLE_MOD']['name'];
		$userField = $GLOBALS['TABLE_MOD']['fields']['USER_ID'];
		$dateField = $GLOBALS['TABLE_MOD']['fields']['MOD_DATE'];
		$actionField = $GLOBALS['TABLE_MOD']['fields']['ACTION'];
		
		// We have to see if any scenes or forks were added or changed
		//	also.			
		$query = "SELECT * FROM ".$table." WHERE story_id > 0  AND (($actionField=".MOD_ACTION_EDIT.") OR 
					($actionField=".MOD_ACTION_ADD.") OR ($actionField=".MOD_ACTION_DELETE.")) AND ($userField='$username') ORDER BY ".$dateField." DESC LIMIT $maxCount";
		
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		if (!DB::isError ($results)) {
						
			$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
			while (($array = $resultObj->fetchRow ())) {
								
				// If we have a story ID then we can assume that the story was
				//	modified in some way.			
				$object = generateObject ($array[$GLOBALS['TABLE_MOD']['fields']['TARGET_TYPE']], $array[$GLOBALS['TABLE_MOD']['fields']['TARGET_ID']]);
				if ($object) {			
					$object->_addProperty ('mod_date', $array[$dateField]);
					$object->_addProperty ('mod_action', $array[$actionField]);
					$object->_addProperty ('mod_deleted', false);
					
					// Add the story to the array if it's not already in there
					array_push ($objects, $object);
				}
				else {
					// ssmodeThe object isn't there so use the instance that's stored in the 
					// 	record.
					$object = unserialize ($array[$GLOBALS['TABLE_MOD']['fields']['MOD_DATA']]);					
					$object->_addProperty ('mod_date', $array[$dateField]);
					$object->_addProperty ('mod_action', $array[$actionField]);
					$object->_addProperty ('mod_deleted', true);
					
					array_push ($objects, $object);
				}
			}
		}
		
		return $objects;
		
	}
	
	/** Retrieves a list of features writers based on certain statistics
	 *  Writers who have the highest reviews, highest number of stories
	 *  appear first in the list. 
	 *  @param int $maxCount The maximum number of users to return
	 */
	function getFeaturedMembers ($maxCount=3) {
				
		$featured = array ();
		$rankField = $GLOBALS['TABLE_USER']['fields']['RANK'];
		$tableName = $GLOBALS['TABLE_USER']['name'];
		$query = "SELECT * FROM $tableName WHERE $rankField > 0 ORDER BY $rankField DESC LIMIT $maxCount";
		$result = $GLOBALS['DBASE']->simpleQuery ($query);
		if (!DB::isError ($result)) {
			$resultObj = new DB_result ($GLOBALS['DBASE'],$result);
			while (($array = $resultObj->fetchRow ())) {
							
				$user = new SSUser;
				$user->_setDBKeyValueArray ($array);
				
				if ($user->get (PROP_STATUS) == USER_STATUS_ACTIVE) {
					$featured[] = $user;
				}
			}		
		}
		
		return $featured;
	}
		
	/** Retrieves objects of the given type with the highest view count.  Capped at $maxCount		
	 * @param int $objectType The type of object being searched for.
	 * @param int $maxCount The maximum number of objects to return.
	 * @return array An array of objects (type dependant on $objectType)
	 *  @access public
	 */
	function getMostViewedItems ($objectType, $maxCount) {
	
		// This is required because we're adding properties to the object which
		//	aren't carried over.
		SSTableObject::clearCache ();
		
		$viewTable = $GLOBALS['TABLE_VIEW']['name'];
		if ($objectType == OBJECT_TYPE_STORY) {
			$subjectTypeField = $GLOBALS['TABLE_VIEW']['fields']['TARGET_TYPE'];
			$subjectIDField = $GLOBALS['TABLE_VIEW']['fields']['STORY_ID'];
			
			// When we look for story view counts we have to take into account scenes
			//	in the story that were viewed so we have to modify the SQL to adjust for that.
			//	To do so, we just look for all records that have a story_id of non-null.  These 
			//	are objects that are part of the story.
			$query = "SELECT $subjectTypeField, $subjectIDField, COUNT($subjectIDField) AS views FROM $viewTable 
						WHERE $subjectIDField <> 0 GROUP BY $subjectIDField ORDER BY views DESC";
		}
		else {
			$subjectTypeField = $GLOBALS['TABLE_VIEW']['fields']['TARGET_TYPE'];
			$subjectIDField = $GLOBALS['TABLE_VIEW']['fields']['TARGET_ID'];
			
			$query = "SELECT $subjectTypeField, $subjectIDField, COUNT($subjectIDField) AS views FROM $viewTable 
						WHERE $subjectTypeField = $objectType GROUP BY $subjectTypeField, $subjectIDField ORDER BY views DESC";
		}		
				
		if ($maxCount > 0) {
			$query .= ' LIMIT '.$maxCount;
		}
		
		$objects = array ();

		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		if (!DB::isError ($results)) {
									
			$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
			while (($array = $resultObj->fetchRow ())) {

				$id = $array [$subjectIDField];
				if ($id > 0) {
					
					// If we have a story ID then we can assume that the story was
					//	modified in some way.		
					$object = generateObject ($objectType, $id);
										
					if ($object) {
										
						$object->_addProperty ('view_count', $array['views']);	
						
						// Get the associated story (if not a story itself)
						$story = false;
						if ($object->getType () != OBJECT_TYPE_STORY) {
							$storyID = $object->get (PROP_STORY_ID);							
							$story = generateObject (OBJECT_TYPE_STORY, $storyID);
						}
						else {
							$story = $object;
						}					
						
						if ($story && $story->hasPermissionToReadStory()) {
							if ($object->getType() == OBJECT_TYPE_STORY) {													
								if ($object->get (PROP_STATUS) == STORY_STATUS_ACTIVE) {
								
									// Add the story to the array if it's not already in there
									array_push ($objects, $object);
								}
							}
							else if ($object->getType() == OBJECT_TYPE_SCENE) {
								if ($object->get (PROP_STATUS) == SCENE_STATUS_ACTIVE) {
									// Add the story to the array if it's not already in there
									array_push ($objects, $object);
								}
							}
							else {
								$this->addError (STR_99, ERROR_TYPE_SERIOUS);
							}
						}
					}
				}
			}
		}
						
		return $objects;
		
	}
	
	/** Retrieves objects of the given type with the highest rating.  Capped at $maxCount		
	 * @param int $objectType The type of object being searched for.
	 * @param int $maxCount The maximum number of objects to return.
	 * @return array An array of objects (type dependant on $objectType)
	 *  @access public
	 */
	function getHighestRatedItems ($objectType, $maxCount) {
		
		// This is required because we're adding properties to the object which
		//	aren't carried over.
		SSTableObject::clearCache ();
		
		$subjectTypeField = $GLOBALS['TABLE_RATING']['fields']['SUBJECT_TYPE'];
		$subjectIDField = $GLOBALS['TABLE_RATING']['fields']['SUBJECT_ID'];
		$ratingField = $GLOBALS['TABLE_RATING']['fields']['RATING'];
		$storyIDField = $GLOBALS['TABLE_RATING']['fields']['STORY_ID'];
		$ratingTable = $GLOBALS['TABLE_RATING']['name'];
		
		if ($objectType == OBJECT_TYPE_STORY) {
			$query = "SELECT $storyIDField, AVG($ratingField) AS average FROM $ratingTable 
						GROUP BY $storyIDField";
		}
		else {
			$query = "SELECT $subjectTypeField, $subjectIDField, AVG($ratingField) AS average FROM $ratingTable 
						WHERE $subjectTypeField = $objectType GROUP BY $subjectTypeField, $subjectIDField";
		}
					
		$query .= ' ORDER BY average DESC';
		
		if ($maxCount > 0) {
			$query .= ' LIMIT '.$maxCount;
		}

		$objects = array ();

		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		if (!DB::isError ($results)) {
									
			$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
			while (($array = $resultObj->fetchRow ())) {

				if ($objectType == OBJECT_TYPE_STORY) {
					$id = $array [$storyIDField];
				}
				else {
					$id = $array [$subjectIDField];
				}
				if ($id > 0) {
					
					// If we have a story ID then we can assume that the story was
					//	modified in some way.						
					$object = generateObject ($objectType, $id);
					if ($object) {
						
						// Get the associated story (if not a story itself)
						$story = false;
						if ($object->getType () != OBJECT_TYPE_STORY) {
							$storyID = $object->get (PROP_STORY_ID);							
							$story = generateObject (OBJECT_TYPE_STORY, $storyID);
						}
						else {
							$story = $object;
						}
						
						if ($story && $story->hasPermissionToReadStory()) {
							
							$object->_addProperty ('avg_rating', $array['average']);						
							if ($object->getType() == OBJECT_TYPE_STORY) {						
								if ($object->get (PROP_STATUS) == STORY_STATUS_ACTIVE) {
									// Add the story to the array if it's not already in there
									array_push ($objects, $object);
								}
							}
							else if ($object->getType() == OBJECT_TYPE_SCENE) {
								if ($object->get (PROP_STATUS) == SCENE_STATUS_ACTIVE) {
									// Add the story to the array if it's not already in there
									array_push ($objects, $object);
								}
							}
							else {
								$this->addError (STR_99);
							}
						}
					}
				}
			}
		}
			
		return $objects;
		
	}
			
	/** Retrieves objects of the given type classified as the given .  Capped at $maxCount		
	 * @param int $objectType The type of object being searched for.
	 * @param int $maxCount The maximum number of objects to return.
	 * @param int $maxCount The maximum number of objects to return.
	 * @return array An array of objects (type dependant on $objectType)
	 *  @access public
	 */
	function getClassifiedAsItems ($objectType, $classification, $maxCount) {
		
		// This is required because we're adding properties to the object which
		//	aren't carried over.
		SSTableObject::clearCache ();
		
		$subjectTypeField = $GLOBALS['TABLE_CLASSIFICATION']['fields']['SUBJECT_TYPE'];
		$subjectIDField = $GLOBALS['TABLE_CLASSIFICATION']['fields']['SUBJECT_ID'];
		$classifyField = $GLOBALS['TABLE_CLASSIFICATION']['fields']['CLASSIFICATION'];
		$classifyTable = $GLOBALS['TABLE_CLASSIFICATION']['name'];
		$storyIDField = $GLOBALS['TABLE_CLASSIFICATION']['fields']['STORY_ID'];
				
		if ($objectType == OBJECT_TYPE_STORY) {
			$query = "SELECT $storyIDField, $storyIDField, COUNT($classifyField) AS classification_count FROM $classifyTable 
						WHERE $classifyField = '$classification' GROUP BY $storyIDField ORDER BY classification_count DESC";
		}
		else {
			$query = "SELECT $subjectIDField, $subjectTypeField, COUNT($classifyField) AS classification_count FROM $classifyTable 
						WHERE $classifyField = '$classification' AND $subjectTypeField = $objectType GROUP BY $subjectIDField ORDER BY classification_count DESC";
		}
					
		if ($maxCount > 0) {
			$query .= ' LIMIT '.$maxCount;
		}
		
		$objects = array ();

		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		
		if (!DB::isError ($results)) {
									
			$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
			while (($array = $resultObj->fetchRow ())) {

				if ($objectType == OBJECT_TYPE_STORY) {
					$id = $array [$storyIDField];
				}
				else {
					$id = $array [$subjectIDField];
				}
				
				if ($id > 0 && $array ['classification_count'] > 0) {
					
					// If we have a story ID then we can assume that the story was
					//	modified in some way.						
					$object = generateObject ($objectType, $id);
					if ($object) {
						
						$object->_addProperty ('classification_count', $array['classification_count']);
						
						// Get the associated story (if not a story itself)
						$story = false;
						if ($object->getType () != OBJECT_TYPE_STORY) {
							$storyID = $object->get (PROP_STORY_ID);							
							$story = generateObject (OBJECT_TYPE_STORY, $storyID);
						}
						else {
							$story = $object;
						}
						
						if ($story && $story->hasPermissionToReadStory()) {
							
							if ($object->getType() == OBJECT_TYPE_STORY) {			
								if ($object->get (PROP_STATUS) == STORY_STATUS_ACTIVE) {
									// Add the story to the array if it's not already in there
									array_push ($objects, $object);
								}
							}
							else if ($object->getType() == OBJECT_TYPE_SCENE) {
								if ($object->get (PROP_STATUS) == SCENE_STATUS_ACTIVE) {
									// Add the story to the array if it's not already in there
									array_push ($objects, $object);
								}
							}
							else {
								$this->addError (STR_99);
							}
						}
					}
				}
			}
		}
		
		return $objects;		
	}
	
	/** Takes a list of objects and generates smarty variables
	 *
	 * @param array $list An array of SSObject-derived objects
	 * @return array An array of arrays containing smarty values for each object.
	 * @access public
	 */
	function convertListToSmartyVariables (&$list) {
		
		$smartyValue = array ();
		foreach ($list as $item) {
			$array = array ();
			
			// The class has to implement the prepareSmartyVariables method.
			if (method_exists ($item, 'prepareSmartyVariables')) {
				
				$item->prepareSmartyVariables ($array);
								
				// Other possible variables.
				if ($item->hasProperty('avg_rating'))
				{
					$array ['avg_rating'] = $item->get ('avg_rating');
				}
				else			
				{
					$array ['avg_rating'] = 'error';
				}
	
				if ($item->hasProperty('classification_count'))
				{
					$array ['classification_count'] = $item->get ('classification_count');
				}	
				else
				{			
					$array ['classification_count'] = 'error';
				}

				if ($item->hasProperty('view_count'))
				{
					$array ['view_count'] = $item->get ('view_count');
				}		
				else
				{
					$array ['view_count'] = 'error';	
				}

				if ($item->hasProperty('mod_date'))
				{
					$array ['mod_date'] = $item->get ('mod_date');
				}				

				if ($item->hasProperty('mod_action'))
				{
					$array ['mod_action'] = $item->get ('mod_action');
				}				

				if ($item->hasProperty('mod_deleted'))
				{
					$array ['mod_deleted'] = $item->get ('mod_deleted');
				}				

				array_push ($smartyValue, $array);
			}			
		}
		
		return $smartyValue;
	}

}
?>