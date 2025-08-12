<?php

/** @file SSStoryPath.class.php
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
 *	@date February, 2004
 */

class SSNode {
	var $currentObject = NULL;
	var $sourceObject = NULL;
	var $forwardLinkArray = array ();
}



/** A utility class for generating paths and hierarchies in individual stories.
	This class is capable of generating complex hierarchies or
	linear paths describing the structure of a story.  It does
	the work of traversing the database records to create this
	path.
*/

class SSStoryPath extends SSObject
{
	/** Generates breadcrumb trail from fork to story beginning
		@param integer $startingSceneID The scene ID to backtrack from
		@return An array of objects that starts with the scene object identified
					by the given scene ID and ending with the story object
					at the heart of the stream.					
	*/
	function backtrackFromScene ($startingSceneID) {
	
		$finalTrace = array ();
		$scene = new SSScene;
		$scene->set (PROP_ID, $startingSceneID);
		if ($scene->load ()) {
			array_push ($finalTrace, $scene);
			$fork = $scene->getIncomingFork ();
			if ($fork) {
				$trace = $this->backtrackFromFork ($fork->get (PROP_ID));
				$finalTrace = array_merge ($finalTrace, $trace);
			}
			else {
				$this->addError (STR_202,ERROR_TYPE_SERIOUS);	
			}
		}
		else {
			$this->addError (STR_203,ERROR_TYPE_SERIOUS);		
		}
		
		return $finalTrace;
	}

	/** Generates breadcrumb trail from fork to story beginning
		@param integer $startingForkID The fork ID to backtrack from
		@return An array of objects that starts with the fork object identified
					by the given fork ID and ending with the story object
					at the heart of the stream.					
	*/
	function backtrackFromFork ($startingForkID) {
	
		$trace = array ();
		$fork = new SSFork;
		$fork->set (PROP_ID, $startingForkID);
		if ($fork->load ()) {
			// Add the first fork to the trace
			array_push ($trace, $fork);			
			
			// Now determine the source scene, if any.
			$scene = $fork->getPreviousScene ();

			// While there are still source scenes
			//	traverse the dbase looking for them.
			//	Once we run out then we have hit the
			//	beginning of the path.
			while ($scene) {
				// Add the previous scene to the trace
				array_push ($trace, $scene);
				$fork = $scene->getIncomingFork ();
				if ($fork) {
					// Add the previous fork to the trace.
					array_push ($trace, $fork);

					// Now see if there's a previous 
					//	scene from this fork.  If not,
					//	then we are at the beginning.
					$scene = $fork->getPreviousScene ();
				}
				else {
					$this->addError (sprintf (STR_204, $fork->get (PROP_NAME),$fork->get (PROP_ID)), ERROR_TYPE_SERIOUS);
				}
			}
			//  $fork should be set to the fork closest to the story object at the heart of the story
			$story = $fork->getSourceStory ();
			if ($story) {
				array_push ($trace, $story);
			}
			else {
				$this->addError (sprintf (STR_204, $fork->get (PROP_NAME),$fork->get (PROP_ID)), ERROR_TYPE_SERIOUS);
			}
		}		
		else {
			$this->addError (STR_205,ERROR_TYPE_SERIOUS);		
		}

		return $trace;
	}

	/** Generates a breadcrumb string from a trace array 
		The array required is one output from one of the backtrack
		functions in this class.
		@param array $traceArray an array of story,scene and fork objects.
		@param bool $forReading If true, then the links generated will be to read the content, if false then they will be for viewing.
		@return string The breadcrumb string.
	*/
	function generateBreadcrumbString ($traceArray, $forReading=false) {
			
		$traceArray = array_reverse ($traceArray);
		$breadcrumb = array();
		$index = 0;		
		foreach ($traceArray as $step) {
			$tpl = '';
			$name = '';
			$description = '';
			$view_link = '';
			$edit_link = '';
			$read_link = '';
			$is_deleted = false;
			switch (strtolower (get_class ($step))) {
				case 'ssfork':
					$tpl = 'components/bubble_fork.tpl';
					$view_link = "fork.php?a=view&fork_id=".$step->get (PROP_ID);
					$read_link = "read.php?t=".OBJECT_TYPE_FORK."&i=".$step->get (PROP_ID);
					$edit_link = "fork.php?a=edit&fork_id=".$step->get (PROP_ID);
					$description = $step->get (PROP_DESCRIPTION);
					$name = $step->get (PROP_NAME);
					$is_deleted = ($step->get (PROP_STATUS) == FORK_STATUS_DELETED);
					
					break;
				case 'ssscene':
					$tpl = 'components/bubble_scene.tpl';
					$view_link = "scene.php?a=view&scene_id=".$step->get (PROP_ID);
					$read_link = "read.php?t=".OBJECT_TYPE_SCENE."&i=".$step->get (PROP_ID);
					$edit_link = "scene.php?a=edit&scene_id=".$step->get (PROP_ID);
					$description = $step->get (PROP_DESCRIPTION);
					$is_deleted = ($step->get (PROP_STATUS) == SCENE_STATUS_DELETED);
					
					$name = $step->get (PROP_NAME);
					break;
				case 'ssstory':				
					$tpl = 'components/bubble_story.tpl';
					$view_link = "story.php?a=view&story_id=".$step->get (PROP_ID);
					$read_link = "read.php?t=".OBJECT_TYPE_STORY."&i=".$step->get (PROP_ID);
					$edit_link = "story.php?a=edit&story_id=".$step->get (PROP_ID);
					$description = $step->get (PROP_DESCRIPTION);
					$name = $step->get (PROP_NAME);
					$is_deleted = $step->get (PROP_STATUS) == STORY_STATUS_DELETED;
					break;
				default:
					trigger_error ('Found an invalid object in the breadcrumb string: '.get_class ($step));
					break;
			}
				
			if ($tpl) {
				$bubble = array ();
				$bubble['name'] = $name;
				$bubble['description'] = '';
				
				if ($forReading) {
					$bubble['read_link'] = $read_link;
				}
				else {
					$bubble['view_link'] = $view_link;
					$bubble['edit_link'] = $edit_link;
				}
							
				$bubble['level'] = $index;
				$bubble['is_deleted'] = $is_deleted;
		
				$smarty = new SSSmarty;
				$smarty->assign ('bubble', $bubble);
				$html = $smarty->fetch ($tpl);
				array_push ($breadcrumb, $html);
			}
			
			$index++;
		}

		$output = '';
		$index = 0;
		foreach ($breadcrumb as $node) {

			
			$output .= '<div style="padding-bottom: 6px; padding-right: 6px; float:left">';
			if ($index > 0)
			{
				$output .= '<font color="#999999">&gt;</font>&nbsp;&nbsp;';
			}		
			
			if ($index == (count($breadcrumb)-1))
			{
				$output .= '<span style="font-weight:bold">'.$node.'</span>';
			}
			else
			{
				$output .= $node;
			}
			$output .= '</div>';
			
			$index++;
		}
		return $output;
	}

	/** Generates a block of text containing all the content from the stream
	 *
	 * This function will trace back to the beginning of the story including
	 *	text in all forks and scenes
	 *
	 * @param integer $startingSceneID The ID of the scene to track back from.
	 * @return string The HTML to display the stream.
	 */
	function readStory ($type, $id) {
		
		$streamArray = array ();
		if ($type == OBJECT_TYPE_STORY) {		
			$object = generateObject ($type, $id);
			if ($object) {
				$streamArray = array ($object);
			}
			else {
				$this->addError (STR_207, ERROR_TYPE_SERIOUS);
				return '';
			}
		}
		else {
			$streamArray = $this->backtrackFromScene ($id);
			$streamArray = array_reverse ($streamArray);
		}
		
		if (count ($streamArray) > 0) {
			
			$enoughForPage = false;
			$sceneCount = 1;
			$smartyData = array ();
			foreach ($streamArray as $object) {
								
				switch ($object->getType ()) {
					case OBJECT_TYPE_FORK:
						$s1 ['name'] = 'ss_chapter';
						$s1 ['template'] = 'reading/read_chapter.tpl';
						$s1 ['data']['fork_text'] = $object->get (PROP_DESCRIPTION);			
						$s1[] = $s1;
						$enoughForPage = false;
						break;
					case OBJECT_TYPE_SCENE:
						$s1 ['data']['scene_title'] = $object->get (PROP_NAME);
						$s1 ['data']['scene_text'] = $object->get (PROP_DATA_TEXT);						
						$s1 ['data']['chapter'] = $sceneCount;
						$s1 ['data']['license_url'] = $object->get (PROP_LICENSE_URL);
						$s1 ['data']['license_name'] = $object->get (PROP_LICENSE_NAME);
						$smartyData[] = $s1;
						$enoughForPage = true;
						$sceneCount++;
						break;
					case OBJECT_TYPE_STORY:
					
						$smartyData = array ();
						$s1 ['name'] = 'ss_story';
						$s1 ['data']['scene_text'] = $object->get (PROP_DESCRIPTION);						
						$s1 ['data']['title'] = $object->get (PROP_NAME);
						$s1 ['data']['username'] = $object->get (PROP_USERNAME);
						$s1['template'] = 'reading/read_cover.tpl';
						$smartyData[] = $s1;
																				
						$s2 ['name'] = 'ss_chapter';
						$s2 ['template'] = 'reading/read_chapter.tpl';
						$s2 ['data']['scene_title'] = $object->get (PROP_NAME);
						$s2 ['data']['scene_text'] = $object->get (PROP_DESCRIPTION);						
						$s2 ['data']['fork_text'] = $object->get (PROP_SYNOPSIS);			
						$s2 ['data']['chapter'] = $sceneCount;
						$s2 ['data']['license_url'] = '';
						$s2 ['data']['license_name'] = '';
						$smartyData[] = $s2;
						$sceneCount++;
																											
						$enoughForPage = true;
						break;						
				}
				
				if ($enoughForPage) {	
					
					foreach ($smartyData as $data) {
						// Display what we have for the page.
						$smarty = new SSSmarty;			
						$smarty->assign ($data['name'], $data['data']);
						$smarty->display ($data['template']);						
						echo '<p>';
					}
					
					// Clear the data container
					$smartyData = array ();
				}					
			}
		}
	}
	
	/** Retrieves the number of forks leading from the given scene to the starting fork (inclusive)
	 * @param int $sceneID The ID of the scene to backtrack from to count the number of forks
	 * @return int The number of forks. 
	 */		
	function getForkCountFromScene ($sceneID) {
		
		$forkCount = 0;
		$breadcrumb = $this->backtrackFromScene ($sceneID);
		if (is_array ($breadcrumb)) {
			foreach ($breadcrumb as $crumb) {
				if (get_class ($crumb) == 'ssfork') {
					$forkCount++;
				}
			}
		}

		return $forkCount;
	}

	/** Takes a hierarchy structure and outputs it to HTML
		@param array $hierarchy The hierarchy of nodes as returned from
						getHierarchy
		@return string The HTML needed to display the hierarchy.
	*/
	function generateHierarchyOutput ($hierarchy, &$nextID, $parentNodeID=-1) {

		$final = '';
		$siblingIndex = 0;
		foreach ($hierarchy->forwardLinkArray as $node) {
			
			$is_deleted = false;
			$is_active = false;
			$name = '';
			$view_link = '';
			$edit_link = '';
			$node_image = '';
			
			$smarty = new SSSmarty;
			$description = '';
			$storyID = 0;
			switch (strtolower (get_class ($node->currentObject))) {		
				case 'ssstory':				
					$is_deleted = ($node->currentObject->get (PROP_STATUS) == STORY_STATUS_DELETED);
					$is_active = ($node->currentObject->get (PROP_STATUS) == STORY_STATUS_ACTIVE);
					$name .= $node->currentObject->get (PROP_NAME);
					$view_link = 'story.php?a=view&story_id='.$node->currentObject->get (PROP_ID); 
					$edit_link = 'story.php?a=edit&story_id='.$node->currentObject->get (PROP_ID);
					$description = $node->currentObject->get (PROP_DESCRIPTION);
					$node_image = $GLOBALS['SCRIPT_ROOT'].'themes/default/images/tree/story_node.gif';
					$tpl = 'components/bubble_story.tpl';
					$storyID = $node->currentObject->get('id');
					break;

				case 'ssscene':
					$is_deleted = ($node->currentObject->get (PROP_STATUS) == SCENE_STATUS_DELETED);
					$is_active = ($node->currentObject->get (PROP_STATUS) == SCENE_STATUS_ACTIVE);
					$name .= $node->currentObject->get (PROP_NAME);
					$view_link = 'scene.php?a=view&scene_id='.$node->currentObject->get (PROP_ID); 
					$edit_link = 'scene.php?a=edit&scene_id='.$node->currentObject->get (PROP_ID);
					$description = $node->currentObject->get (PROP_DESCRIPTION);
					$node_image = $GLOBALS['SCRIPT_ROOT'].'themes/default/images/tree/scene_node.gif';
					$tpl = 'components/bubble_scene.tpl';
					$storyID = $node->currentObject->get('story_id');
					break;

				case 'ssfork':
					$is_deleted = ($node->currentObject->get (PROP_STATUS) == FORK_STATUS_DELETED);
					$is_active = ($node->currentObject->get (PROP_STATUS) == FORK_STATUS_ACTIVE);
					$name .= $node->currentObject->get (PROP_NAME);
					$view_link = 'fork.php?a=view&fork_id='.$node->currentObject->get (PROP_ID); 
					$edit_link = 'fork.php?a=edit&fork_id='.$node->currentObject->get (PROP_ID);
					$description = $node->currentObject->get (PROP_DESCRIPTION);
					$node_image = $GLOBALS['SCRIPT_ROOT'].'themes/default/images/tree/fork_node.gif';
					$tpl = 'components/bubble_fork.tpl';
					$storyID = $node->currentObject->get('story_id');
					break;
			}

	  		// Get this node's ID and increment the ID for the next one.
			$id = ++$nextID;
			
			if (strlen ($description) > 25) {			
				$description = substr ($description, 0, 25);
				$description .= '...';
			}
			
			// Strip single quotes and carriage returns
			$description = str_replace (array ('\'', '\n'), array ('', ' '), $description);
			$name = str_replace (array ('\'', '\n'), array ('', ' '), $name);
			
			// If the parent node is non-empty, then we have to insert this
			//	new node inside the parent.
			$final .= "\r\n"."story".$storyID.".add($id,$parentNodeID,'$name','$view_link','$description','','$node_image','$node_image');";
			
			if (count ($node->forwardLinkArray) > 0) {
				$final .= $this->generateHierarchyOutput ($node, $nextID, $id);
			}	
		}
		
		return $final;
	}
	
	/** 
	 * Gets the hierarchy from the given root object down 
	 *	The hierarchy is arranged as a collection of nested SSNode
	 *	objects.  
	 *	@param $rootObject The root SSObject (should be a story,scene or fork)
	 *	@return SSNode Returns an SSNode object that represents the given root object 
	 *			and contains the sub-nodes.
	 */
	function getHierarchy ($rootObject) {

		// Create a root node.
		$root = new SSNode;
		$root->currentObject = NULL;
		$root->sourceObject = NULL;
		$root->forwardLinkArray = array ($this->_getHierarchy ($rootObject, NULL));
				
		return $root; 
	}
	
	/** 
	 * Gets the hierarchy from the given root object down 
	 *	The hierarchy is arranged as a collection of nested SSNode
	 *	objects.   This is called recursively internally.  DO NOT USE THIS METHOD
	 *	outside of this class.
	 *	@param $rootObject The root SSObject (should be a story,scene or fork)
	 * 	@param $previousObject The last handled object (if any). NULL if none.
	 *	@return SSNode Returns an SSNode object that represents the given root object 
	 *			and contains the sub-nodes.
	 */
	function _getHierarchy ($rootObject, $previousObject) {
		// Get all root forks.
		$node = new SSNode;
		$node->currentObject = $rootObject;
		$node->sourceObject = $previousObject;
		$objects = array ();		
		switch (strtolower (get_class ($rootObject))) {		
			case 'ssstory':
				$objects = $rootObject->getForkList ();
				break;
			case 'ssscene':
				$objects = $rootObject->getOutgoingForks ();
				break;
			case 'ssfork':
				$objects = $rootObject->getNextScenes ();
		}
		// Nowhere to go from here so return what we have
		//	so far
		if (!is_array ($objects) || (count ($objects)==0)) {
			$node->forwardLinkArray = array ();
			return $node;
		}
		// Now create a branch for each of the branches off of
		//	the given root object.
		foreach ($objects as $object) {
			array_push ($node->forwardLinkArray, $this->_getHierarchy ($object, $rootObject));
		}
		return $node;
	}	
}

?>
