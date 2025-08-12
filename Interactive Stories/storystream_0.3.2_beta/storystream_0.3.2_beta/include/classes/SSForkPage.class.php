<?php
/** @file SSForkPage.class.php
 * Contains the SSForkPage class used to render story related
 *	administration content.
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
 */

/**  Handles the display and processing for a scene page
*/
class SSForkPage extends SSPage {

	/** Called before any rendering of the page is done */
	function _preRender () {
	
		$GLOBALS['APP']->set ('section', SECTION_ADMIN_MAIN);	
		
		parent::_preRender();
	}
	
	/**  Displays the content of a page
	*/
	function _displayContent () {

		$action = $GLOBALS['APP']->queryGetValue (PAGE_ACTION);
				
		// The section of the site 
		$this->set ('section', SECTION_SCENE);
		
		// Sets the title as it will appear in the caption of the web client.
		$this->set ('title', STR_66);
				
		////////////////////////////////////////////////////////////////////////////
		// ADD/EDIT STORY/FORK FORM
		////////////////////////////////////////////////////////////////////////////
		$this->_formHandling ();

		////////////////////////////////////////////////////////////////////////////
		// HANDLE FORK ACTIONS
		////////////////////////////////////////////////////////////////////////////
		
		$forkID = $GLOBALS['APP']->queryGetValue (PAGE_ACTION_FORK_ID);
		$fork = new SSFork;
		$fork->set (PROP_ID, $forkID);
		if ($forkID > 0 && $fork->load()) {				
			if ($action == 'view') {
				$fork->view ();
			}
			else if ($action == 'delete') {
				$fork->delete ();
				$fork->view ();
			}
			else if ($action == 'undelete') {
				$fork->undelete ();
				$fork->view ();
			}
			else if ($action == 'true_delete') {
			
				if ($fork->trueDelete()) {
					$storyID = $fork->get (PROP_STORY_ID);
					$story = new SSStory;
					$story->set (PROP_ID, $storyID);
					if ($storyID > 0 && $story->load()) {
						$story->view ();
					}
				}
				else {
					$fork->view();
				}
			}
		}
	}

	/**  Handles the display and processing of a form.
	*/
	function _formHandling () {

		$submit = $this->_getFormAction ();
		$fork = new SSFork;
		$smarty = new SSSmarty;

		$submitSuccess = false;
		if ($submit != '') {

			$fork = new SSFork;
			$form_type = $GLOBALS['APP']->queryPostValue ('form_type');
			$isEdit = ($form_type == 'edit');
			if ($isEdit) {

				// If we're editing a fork then we must first
				//	load the original to verify that the story with
				//	the given ID exists.
				$fork->set (PROP_ID, $GLOBALS['APP']->queryPostValue (PAGE_ACTION_FORK_ID));
				if (!$fork->load ()) {
					// Invalidate the story
					$fork->set (PROP_ID, 0);
				}
			}

			$submitSuccess = $fork->handleFormSubmit ($isEdit);
		}

		$action = $GLOBALS['APP']->queryGetValue (PAGE_ACTION);

		// Get the last used form data (if any).
		$memObj = $GLOBALS['APP']->retrieveValue ('FORK_ADDEDIT_OBJ');

		$action = $this->_getPageAction ();
		if ($submitSuccess == false && 
			((strcasecmp ($action, 'edit') == 0) || (strcasecmp ($action, 'add') == 0))) {
			
			$isEdit = (strcasecmp ($action, 'edit') == 0);
			$fork->displayForm ($isEdit, '');
			
			$this->set ('left_sidebar', false);
			$this->set ('right_sidebar', false);			
		}
		else if ($submit != ''){
			$verb = (strcasecmp ($action, 'edit') == 0) ? STR_68 : STR_69;
			$this->addNotification (sprintf (STR_67, $verb));
			$fork->view ();
		}		
	}
	
	/** Override in page object to determine if the user has permission to view the requested info
	 *
	 * @return bool True if permission is granted, false otherwise.
	 *  @access protected
	 */
	function _hasPermission () {
		
		$action = $this->queryValue (PAGE_ACTION);
		$user = $GLOBALS['APP']->getLoggedInUserObject();
		
		if ($action == 'view') {
			$fork = new SSFork;
			$id = $GLOBALS['APP']->queryValue (PAGE_ACTION_FORK_ID);
			$fork->set (PROP_ID, $id);
			if ($fork->load ()) {
				return $fork->hasPermissionToViewFork ();
			}
		}
		else {
			if ($action == 'add') {
				$scene = new SSScene;
				$sceneID = $this->queryValue (PAGE_ACTION_SCENE_ID);
				if ($sceneID > 0) {
					$scene->set (PROP_ID, $sceneID);
					if ($scene->load ()) {
						return $scene->hasPermissionToAddFork ();
					}
				}
				else {
					// Ask the story for permission since
					//	it looks like we're adding a beginning
					//	or ending fork.
					$storyID = $this->queryValue (PAGE_ACTION_STORY_ID);
					$story = new SSStory;
					$story->set (PROP_ID, $storyID);
					if ($story->load()) {
						
						if (!$story->hasPermissionToAddScene ()) {							
							return false;
						}
						
						return true;
					}					
				}
			}
			else {
				$fork = new SSFork;
				$id = $GLOBALS['APP']->queryValue (PAGE_ACTION_FORK_ID);
				$fork->set (PROP_ID, $id);
				if ($fork->load ()) {
					return $fork->hasPermissionToEditFork ();
				}
			}
		}
		return false;
	}
	
}
?>