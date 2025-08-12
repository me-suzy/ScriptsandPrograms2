<?php
/** @file SSStoryCollection.class.php
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
 *	@date 3/2/2004
 */

/** Contains information about the contributions made by the user.
 *
 * Used when displaying information about the contributions a user has made.
 *
 * @author Karim Shehadeh
 * @version 0.1
 */
class SSStoryContrib
{
	/** The story object that's been contributed.
	 * @var SSStory The 
	 */
	var $story = NULL;

	/** The array of fork objects that have been contributed
	 * @var array
	 */
	var $forks = array ();
	
	/** The array of scene objects that have been contributed
	 * @var array
	 */
	var $scenes = array ();
}

/** Used to collect and display story objects
 *	@author Karim Shehadeh
 *	@date 3/2/2004
 */
class SSStoryCollection extends SSCollection
{
	/** Retrieves all stories that has the given status
	 *
	 * Defaults to active status.
	 *
	 * @param integer $status The status to match against when doing the search
	 * @return array An array of SSStory objects or an empty array if none was found.
	 */
	function getStoriesByStatus ($status=STORY_STATUS_ACTIVE) {
		
		// Get the stories started by this user
		$where = array ('status' => $status);
		
		$this->load ($where);
		return $this->getObjects ();
	}

	/** Retrieves all stories authored by the given user
	 *
	 * If no user is given, then the logged in user is used.
	 *
	 * @param string $username The username of the author to retrieve (logged in user
	 					is used if no user is specified.
	 * @return array An array of SSStory objects or an empty array if none was found.
	 */
	function getStoriesByUser ($username='') {
		
		// Clear it here just in case we don't find anything in
		//	the database.
		//$smarty->assign ('ss_story_list', array ());
	
		if (!$username) {
			$user = $GLOBALS['APP']->getLoggedInUserObject ();
			$username = $user->get ('username');
		}
	
		// Get the stories started by this user
		$where = array ();
		if ($username) {
			$where = array ('user_id' => '"'.$username.'"');
		}		
		
		$this->load ($where);
		return $this->getObjects ();
	}
			
	/** Finds a story in a list of contribution objects.
	 *
	 * @param array $contribArray The array of contribution objects
	 * @param SSStory $story The story object to find.
	 * @return int The index into the given array or -1 if not found.
	 */
	function findStoryInContribution (&$contribArray, $story) {
		
		$index = 0;
		for ($i=0;$i<count($contribArray);$i++) {
			if ($contribArray[$i]->story->get (PROP_ID) == $story->get (PROP_ID)) {
				return $i;
			}
		}
		
		return false;
	}
	
	/** Retrieve stories sorted by a type of modification by date
	 * Allows caller to get a list of, for example, recently changed or 
	 *	recently modified stories, scenes, etc.
	 *
	 * @param string $sortByField The name of the field to sort by
	 * @param integer $action The action to limit the search to.
	 * @param integer $maxCount The maximum number of stories that can be returned
	 * @return array An array of SSStory objects.
	 */
	function getStoriesSortedByMod ($sortByField, $action, $maxCount) {
		
		$stories = array ();
		$table = $GLOBALS['TABLE_MOD']['name'];
		
		// We have to see if any scenes or forks were added or changed
		//	also.			
		if($action == MOD_ACTION_EDIT) {
			$query = "SELECT DISTINCT (story_id) FROM ".$table." WHERE story_id > 0  AND ((action=".MOD_ACTION_EDIT.") OR 						(action=".MOD_ACTION_ADD." AND (subject_type <> ".OBJECT_TYPE_STORY."))) ORDER BY ".$sortByField." DESC LIMIT $maxCount";
		}
		else if ($action == MOD_ACTION_ADD) {
			$query = "SELECT DISTINCT (story_id) FROM ".$table." WHERE story_id > 0  AND action=".MOD_ACTION_ADD." AND subject_type = ".OBJECT_TYPE_STORY." ORDER BY ".$sortByField." DESC LIMIT $maxCount";
		}
		else {
			die ("UNKNOWN ACTION GIVEN (".__FILE__."|".__FUNCTION__."|".__LINE__.")");
		}
		
		$results = $GLOBALS['DBASE']->simpleQuery ($query);
		if (!DB::isError ($results)) {
						
			$resultObj = new DB_result ($GLOBALS['DBASE'],$results);
			while (($array = $resultObj->fetchRow ())) {
								
				$story_id = $array [$GLOBALS['TABLE_MOD']['fields']['STORY_ID']];
				if ($story_id > 0) {
					
					// If we have a story ID then we can assume that the story was
					//	modified in some way.
					$story = generateObject (OBJECT_TYPE_STORY, $story_id);                   
					if ($story && $story->hasPermissionToReadStory ()) {						
						// Add the story to the array if it's not already in there
						array_push ($stories, $story);
					}
				}
			}
		}
		
		return $stories;
	}
	
	/** Retrieves all stories contributed to by the given user
	 *
	 * If no user is given, then the logged in user is used. A user
	 *	contributes to a story when he or she adds a fork
	 *	or scene to a story started by another author.
	 *
	 * @param string $username The username of the author who contributed 
	 * 						(logged in user is used if no user is specified.
	 * @return array An array of SSStoryContrib objects or an empty array if none was found.
	 */
	function getStoriesContribToByUser ($username='') {
		
		if (!$username) {
			
			$user = $GLOBALS['APP']->getLoggedInUser ();
			if ($user) {
				$username = $user->get ('username');
			}
			else {
				// You have to be logged in to get this
				//	information.
				return array ();
			}
		}
		
		$forkCollection = new SSCollection ('SSFork');
		$sceneCollection = new SSCollection ('SSScene');
		
		$queryForks = 'SELECT fork_id,story_id FROM '.$GLOBALS['TABLE_FORK']['name'].' WHERE user_id="'.$username.'"';
		$queryScenes = 'SELECT scene_id,story_id FROM '.$GLOBALS['TABLE_SCENE']['name'].' WHERE user_id="'.$username.'"';
		$queryStories = 'SELECT story_id FROM '.$GLOBALS['TABLE_STORY']['name'].' WHERE user_id="'.$username.'"';

		$resultForks = $GLOBALS['DBASE']->simpleQuery ($queryForks);
		$resultScenes = $GLOBALS['DBASE']->simpleQuery ($queryScenes);
		$resultStories = $GLOBALS['DBASE']->simpleQuery ($queryStories);
		$contribs = array ();	
		
		// STORIES
		if (!DB::isError ($resultStories)) {
			
			$resultObj = new DB_result ($GLOBALS['DBASE'],$resultStories);
			while (($array = $resultObj->fetchRow ())) {
				
				$storyID = $array['story_id'];
				$story = new SSStory;
				$story->set (PROP_ID, $storyID);
				$story->load ();
				// Get the index of an existing contribution or 
				//	add it to the existing one.
				if ($story->hasPermissionToReadStory ()) {
					
					$index = $this->findStoryInContribution ($contribs, $story);
					if ($index === false) {
						$contrib = new SSStoryContrib;				
						$contrib->story = $story;
						array_push ($contribs, $contrib);
						$index = count ($contribs)-1;
						
						// Now add the object to the collection
						$this->addObject ($story);
					}
				}				
			}
		}
		else {
			$this->addErrorObject ($resultScenes, ERROR_TYPE_SERIOUS);
		}		
		
		// SCENES
		if (!DB::isError ($resultScenes)) {
			
			$resultObj = new DB_result ($GLOBALS['DBASE'],$resultScenes);
			while (($array = $resultObj->fetchRow ())) {
				
				$storyID = $array['story_id'];
				$story = new SSStory;
				$story->set (PROP_ID, $storyID);
				$story->load ();

				if ($story->hasPermissionToReadStory ()) {
					// Get the index of an existing contribution or 
					//	add it to the existing one.
					$index = $this->findStoryInContribution ($contribs, $story);
					if ($index === false) {
						$contrib = new SSStoryContrib;				
						$contrib->story = $story;
						array_push ($contribs, $contrib);
						$index = count ($contribs)-1;
						
						// Now add the object to the collection
							$this->addObject ($story);
					}
				
					// Now add the scene to the list of contributions.
					$scene = new SSScene;
					$scene->set (PROP_ID, $array['scene_id']);
					$scene->load ();
					array_push ($contribs[$index]->scenes, $scene);
				}
			}				
		}
		else {
			$this->addErrorObject ($resultScenes, ERROR_TYPE_SERIOUS);
		}
		
		// FORKS
		$stories = $this->get ('list');
		if (!DB::isError ($resultForks)) {
			
			$resultObj = new DB_result ($GLOBALS['DBASE'],$resultForks);
			while (($array = $resultObj->fetchRow ())) {
				
				$storyID = $array['story_id'];
				
				$story = new SSStory;
				$story->set (PROP_ID, $storyID);
				$story->load ();

				if ($story->hasPermissionToReadStory ()) {		
					
					// Get the index of an existing contribution or 
					//	add it to the existing one.
					$index = $this->findStoryInContribution ($contribs, $story);
					if ($index === false) {
						$contrib = new SSStoryContrib;				
						$contrib->story = $story;
						array_push ($contribs, $contrib);
						$index = count ($contribs)-1;
	
						// Now add the object to the collection
						$this->addObject ($story);				
					}
				
					$fork = new SSFork;
					$fork->set (PROP_ID, $array['fork_id']);
					$fork->load ();
					array_push ($contribs[$index]->forks, $fork);
				}				
			}
		}
		else {
			$this->addErrorObject ($resultForks, ERROR_TYPE_SERIOUS);
		}
		
		return $contribs;
	}
	
	/**  Handles filling in a smarty array of storiy contributions
     *	Variables that are initialized are:
     *		ss_story_contrib_list
     *		
     *	@param array $array The array that will contain all the smarty variables
	 					necessary to display the contribution list.
     *	@param string $userName The username of the user whose stories will be retrieved. Empty to get all stories
     *	@return bool True if the smarty variable was assigned successfully.
	*/
	function prepareContributionList (&$array, $username='') {
		
		$contributions =  $this->getStoriesContribToByUser ($username);
		
		$contribs = array ();			
		foreach ($contributions as $contrib) {
			
			$story = array ();
			$contrib->story->prepareSmartyVariables ($story, false);			
			$contribSmarty = array ();
			$contribSmarty ['story'] = $story;
			
			$contribSmarty['scenes'] = array ();
			foreach ($contrib->scenes as $scene) {
				
				$sceneArray = array();
				$scene->prepareSmartyVariables ($sceneArray, true);
				array_push ($contribSmarty['scenes'], $sceneArray);
			}
			
			$contribSmarty['forks'] = array ();
			foreach ($contrib->forks as $fork) {
				
				$forkArray = array();
				$fork->prepareSmartyVariables ($forkArray, true);
				array_push ($contribSmarty['forks'], $forkArray);
			}
			
			array_push ($contribs, $contribSmarty);
			
		}
		$array = $contribs;
		return true;
	}

	/**  Handles filling in a smarty array of published stories
     *	Variables that are initialized are:
     *		ss_story_list
     *		
     *	@param string $userName The username of the user whose stories will be retrieved. Empty to get all stories
     *	@return bool True if the smarty variable was assigned successfully.
	*/
	function prepareSmartyStatusStoryList (&$smarty, $status=STORY_STATUS_ACTIVE) {
	
		$storyObjects = array ();
		$storyObjects = $this->getStoriesByStatus ($status);
		
		$storyList = array ();
		foreach ($storyObjects as $story) {
		
			$ss_story = array ();
			$story->prepareSmartyVariables ($ss_story);			
			array_push ($storyList, $ss_story);			
		}
		
		$smarty->assign ('ss_published_story_list', $storyList);
	}
		
	/**  Handles filling in a smarty array of recently created stories
     *	Variables that are initialized are:
     *		ss_story_list
     *		
     *	@param string $userName The username of the user whose stories will be retrieved. Empty to get all stories
     *	@return bool True if the smarty variable was assigned successfully.
	*/
	function prepareSmartyRecentlyCreatedStoryList (&$smarty, $maxCount) {
	
		$storyObjects = array ();
		$storyObjects = $this->getStoriesSortedByMod ($GLOBALS['TABLE_MOD']['fields']['MOD_DATE'], MOD_ACTION_ADD, $maxCount);
		
		$storyList = array ();
		foreach ($storyObjects as $story) {
		
			$ss_story = array ();
			$story->prepareSmartyVariables ($ss_story, true);			
			array_push ($storyList, $ss_story);			
		}
		
		$smarty->assign ('ss_recent_add_story_list', $storyList);
	}
	
	/**  Handles filling in a smarty array of recently changed stories
     *	Variables that are initialized are:
     *		ss_story_list
     *		
     *	@param string $userName The username of the user whose stories will be retrieved. Empty to get all stories
     *	@return bool True if the smarty variable was assigned successfully.
	*/
	function prepareSmartyRecentlyChangedStoryList (&$smarty, $maxCount) {
	
		$storyObjects = array ();
		$storyObjects = $this->getStoriesSortedByMod ($GLOBALS['TABLE_MOD']['fields']['MOD_DATE'], MOD_ACTION_EDIT, $maxCount);
		

		
		$storyList = array ();
		foreach ($storyObjects as $story) {
               
			$ss_story = array ();
			$story->prepareSmartyVariables ($ss_story, true);			
			array_push ($storyList, $ss_story);			
		}
		
		$smarty->assign ('ss_recent_edit_story_list', $storyList);
	}
	
	/**  Handles filling in a smarty array of stories
     *	Variables that are initialized are:
     *		ss_story_list
     *		
     *	@param string $userName The username of the user whose stories will be retrieved. Empty to get all stories
     *	@return bool True if the smarty variable was assigned successfully.
	*/
	function prepareSmartyStoryList (&$smarty, $username='') {
	
		$storyObjects = array ();
		$storyObjects = $this->getStoriesByUser ($username);
		
		$storyList = array ();
		foreach ($storyObjects as $story) {
		
			$ss_story = array ();
			$story->prepareSmartyVariables ($ss_story, true);			
			array_push ($storyList, $ss_story);			
		}
		
		$smarty->assign ('ss_story_list', $storyList);
	}
}
?>
