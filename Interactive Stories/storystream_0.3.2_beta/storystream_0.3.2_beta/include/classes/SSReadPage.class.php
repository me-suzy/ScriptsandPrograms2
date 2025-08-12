<?php
/** @file SSReadPage.class.php
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
 * Contains the SSForkPage class used to render story related
 *	administration content.
 */

/**  Handles the display and processing for a scene page
*/
class SSReadPage extends SSPage {

	/**  Displays the content of a page
	*/
	function _displayContent () {
	
		// The section of the site 
		$this->set ('section', SECTION_READ);
		
		// Sets the title as it will appear in the caption of the web client.
		$this->set ('title', STR_132);
		
		$this->_formHandling ();
		
		$a = $this->queryGetValue (PAGE_ACTION);
		if ($a == PAGE_ACTION_BROWSE) {
			
			$lists = new SSItemLists;
			$c = $this->queryGetValue (PAGE_ACTION_CLASSIFICATION);
			if ($c && 
				in_array (strtolower ($c), array_to_lower($GLOBALS['classifications']))) {
				
				// Display all stories that are classificed as $c
				$classifications = array ();
				$class = array ('name'=>$c);
				$storyObjects = $lists->getClassifiedAsItems (OBJECT_TYPE_STORY, $c, 0);	
				if (count ($storyObjects) > 0) {	
					$class['stories'] = $lists->convertListToSmartyVariables ($storyObjects);
				}
				else {
					$class['stories'] = array ();
				}
				$class['display_more'] = false;
				$class ['is_adult'] = in_array (strtolower($c), array_to_lower($GLOBALS['adult'])) ? true : false;
				
				array_push ($classifications, $class);
				$this->_smarty->assign ('ss_classification_list', $classifications);
				
			}
			else {
			
				// Story classification list
				$classifications = array ();
				foreach ($GLOBALS['classifications'] as $classification) {
					$class = array ('name'=>$classification);
					
					$storyObjects = $lists->getClassifiedAsItems (OBJECT_TYPE_STORY, $classification, 50);	
					if (count ($storyObjects) > 0) {	
						$class['stories'] = $lists->convertListToSmartyVariables ($storyObjects);
					}
					else {
						$class['stories'] = array ();
					}
					$class['display_more'] = false;
					$class ['is_adult'] = in_array (strtolower($classification), array_to_lower($GLOBALS['adult'])) ? true : false;
					
										
					array_push ($classifications, $class);
				}
			}
			$this->_smarty->display ('components/section_storyclassificationlist.tpl');
			
			$this->set ('left_sidebar', true);
			$this->set ('right_sidebar', false);			
		}
		else if ($a == PAGE_ACTION_READ_REVIEWS) {

			if ($GLOBALS['APP']->isUserLoggedIn ()) {		
			
				$type = $GLOBALS['APP']->queryGetValue ('t');
				$id = $this->queryGetValue ('i');
				$coll = new SSCollection ('ssrating');
				$object = generateObject ($type, $id);
				
				if ($object) {
								
					$this->_smarty->assign ('title', $object->get ('name'));
					$this->_smarty->assign ('type', $type);
					$this->_smarty->assign ('id', $id);
					
					$subjectType = $GLOBALS['TABLE_RATING']['fields']['SUBJECT_TYPE'];
					$subjectID = $GLOBALS['TABLE_RATING']['fields']['SUBJECT_ID'];
					$date = $GLOBALS['TABLE_RATING']['fields']['DATE'];
					$constraints = array ($subjectType=>$type, $subjectID=>$id);
					if ($coll->load ($constraints, array ($date=>'DESC'))) {
					
						$ratings = $coll->getObjects ();
						$ss_reviews = array ();
						foreach ($ratings as $rating) {
							$array = array ();					
							$rating->prepareSmartyVariables ($array);
							$ss_reviews[] = $array;
						}
					}
					else {
						$ss_reviews = array ();
					}
											
					$this->_smarty->assign ('ss_reviews', $ss_reviews);
					$this->_smarty->display ('reading/read_reviews.tpl');
				}
				else {
					$this->addError (STR_133, ERROR_TYPE_SERIOUS);
				}
			}			
			else {
				$this->addError (STR_134, ERROR_TYPE_SERIOUS);
			}
			$this->set ('left_sidebar', true);
			$this->set ('right_sidebar', false);		
		}
		else {
			$type = $this->queryGetValue ('t');
			$id = $this->queryGetValue ('i');
			if ($type == OBJECT_TYPE_STREAM) {
	
				if ($this->queryGetValue ('print') != '') {
					$this->set ('template', 'print.tpl');
				}
				
				// It's not necessarily assumed that the scene is what
				//	the user is currently reading when they click on the
				//	Read Book link.  They could be reading the root story
				//	object.  We must distinguish between the two by using
				//	the end type parameter (et).   If none is given then
				//	we assume it's a scene we're looking at (for backward
				//	compatibility.				
				$endType = $this->queryGetValue ('et');
				if (!$endType) {
					$endType = OBJECT_TYPE_SCENE;
				}
				$stream = new SSStoryPath;
				$stream->readStory ($endType, $id);
			}
			else {				
				$object = generateObject ($type, $id);
				if ($object) {
					$object->read ();
				}
			}
		}
		
		// In case they click on something temporary and want to snap back.
		$GLOBALS['APP']->storeLocation ();
	}

	/**  Called before any rendering of the page is done */
	function _preRender () {
	
		$GLOBALS['APP']->set ('section', SECTION_READ);	
		$this->set ('left_sidebar', false);
		$this->set ('right_sidebar', false);
		
		parent::_preRender();
	}
	
	/**  Handles the display and processing of a form.
	*/
	function _formHandling () {

		$submit = $this->_getFormAction ();
		if ($submit != '') {
						
			if ($submit == SUBMIT_ACTION_RATE || $submit == SUBMIT_ACTION_SUBMIT_ALL) {			
				$rating = new SSRating;
				$submitSuccess = $rating->handleFormSubmit ();
			}
			
			if ($submit == SUBMIT_ACTION_CLASSIFY || $submit == SUBMIT_ACTION_SUBMIT_ALL) {
				$class = new SSClassification;
				$submitSuccess = $class->handleFormSubmit ();
			}
			
			$type = $GLOBALS['APP']->queryGetValue ('t');
			$id = $this->queryGetValue ('i');
			$obj = generateObject ($type, $id);
			
			if (($submit == SUBMIT_ACTION_CLEAR_RATING) || ($submit == SUBMIT_ACTION_CLEAR_ALL)) {
				$rating = $obj->getObjectRating();
				if ($rating) {
					$rating->delete ();
				}
			}
			if ($submit == SUBMIT_ACTION_CLEAR_GENRE || $submit == SUBMIT_ACTION_CLEAR_ALL) {
				$classification = $obj->getObjectClassification ();
				if ($classification) {
					$classification->delete ();
				}
			}
			
			if ($submit == SUBMIT_ACTION_POST) {
				
				$phpbbTopicID = $obj->get (PROP_PHPBB_TOPIC_ID);
				if ($phpbbTopicID > 0) {
					$discussion = new SSDiscussionBoard;
				
					$subject = @$_POST['subject'];
					$body = @$_POST['body'];
					
					if ($body) {
						
						if (!$subject) {
							$subject = $obj->get (PROP_NAME);
						}
						
						if (!$discussion->reply ($phpbbTopicID, $subject, $body)){ 
							
							$this->addError (STR_135, ERROR_TYPE_SERIOUS);
						}	
					}
					else {
						$this->addError (STR_136, ERROR_TYPE_SERIOUS);
					}
				}
				else {
					$this->addError (sprintf (STR_137, $obj->getTypeName(false)), ERROR_TYPE_SERIOUS);
				}
			}
			
		}
		
		parent::_formHandling ();		
	}
	
	/** Override in page object to determine if the user has permission to view the requested info
	 *
	 * @return bool True if permission is granted, false otherwise.
	 *  @access protected
	 */
	function _hasPermission () {

		$type = $this->queryGetValue ('t');
		$id = $this->queryGetValue ('i');

		if ($type)
		{
			$obj = generateObject ($type, $id);
			if (($obj && ($obj->getType() == OBJECT_TYPE_STORY || $obj->hasProperty (PROP_STORY_ID))) ||
				(!$obj && ($type == OBJECT_TYPE_STREAM))){
				
				
				// Get the associated story object (if this isn't a story object
				//	itself).
				$story = false;
				if ($type == OBJECT_TYPE_STREAM) {
					
					// This is a stream so we need to check what the ID is
					//	so that we can determine if we need to find the
					//	story object.
					$endType = $this->queryGetValue ('et');
					if (!$endType) {
						$endType = OBJECT_TYPE_SCENE;
					}			
					
					$obj = generateObject ($endType, $id);
				}
	
				if ($obj->getType() != OBJECT_TYPE_STORY) {
					$story_id = $obj->get (PROP_STORY_ID);
					$story = generateObject (OBJECT_TYPE_STORY, $story_id);
				}
				else {
					$story = $obj;
				}
				
				if ($story) {				
					// Sometimes, only members of groups can read contained
					//	stories. Check on that.
					return $story->hasPermissionToReadStory();
				}
				
			}
			else if (!$obj && $type==OBJECT_TYPE_STREAM){
				
				// The user is reading a book.
				return true;
			}
			else {
				
				$this->addError ('The object given could not be found in the database.', ERROR_TYPE_SERIOUS);
				return true;
			}
		}
		else
		{
			return true;
		}
		
		return false;
	}
	
}
?>