<?php
/** @file SSScenePage.class.php
 * Renders scene authoring content
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

/**  Handles the display and processing for a scene page
*/
class SSScenePage extends SSPage {

	/**  called before any rendering of the page is done */
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
		$this->set ('title', STR_163);
				
		////////////////////////////////////////////////////////////////////////////
		// ADD/EDIT SCENE FORM
		////////////////////////////////////////////////////////////////////////////
		$this->_formHandling ();
		
		////////////////////////////////////////////////////////////////////////////
		// ACTION HANDLING
		////////////////////////////////////////////////////////////////////////////
		$id = $GLOBALS['APP']->queryGetValue (PAGE_ACTION_SCENE_ID);
		if ($id > 0) {
		
			// Display the beginning of a story
			$scene = new SSScene;
			$scene->set (PROP_ID, $id);
			if ($scene->load()) {
				
				if ($action == 'view') {
					$scene->view ();
				}
				else if ($action == 'true_delete') {
				
					$storyID = $scene->get (PROP_STORY_ID);
					
					// We assume that the user has confirmed the delete.
					if ($scene->trueDelete ()) {					
						// View the associated story now that the 
						//	scene has been deleted.
						$story = new SSStory;
						$story->set (PROP_ID, $storyID);
						if ($story->load ()) {
							$story->view ();
						}
					}
					else {
						$scene->view ();
					}
					
				}
				else if ($action == 'delete') {
					$scene->delete ();
					$scene->view ();
				}
				else if ($action == 'undelete') {
					$scene->undelete ();
					$scene->view ();
				}
			}
			else {
				$GLOBALS['APP']->addError (sprintf (STR_164, $id), ERROR_TYPE_SERIOUS);
			}
		}
	}	
	
	/**  Handles the display and processing of a form.
	*/
	function _formHandling () {
	
		$submit = $this->_getFormAction ();
		
		// This is used to keep track of whether or not there
		//	was a problem with any form submissions.
		$submitError = true;
		
		////////////////////////////////////////////////////////////////////////////
		// SUBMIT SCENE PROPERTIES (ADD/EDIT)
		////////////////////////////////////////////////////////////////////////////
		$scene = new SSScene;
		if ($submit != '') {
		
			$continueProcessing = true;
			
			$form_type = $GLOBALS['APP']->queryPostValue ('form_type');
			if ($form_type == 'edit') {
			
				// If we're editing a scene then we must first
				//	load the original to verify that the scene with
				//	the given ID exists.
				$sceneID = $GLOBALS['APP']->queryPostValue (PAGE_ACTION_SCENE_ID);
				
				$scene->set (PROP_ID, $sceneID);
				if ($scene->load ()) {
					$submitError = !$scene->handleFormSubmit (true);
				}
				else {
					$GLOBALS['APP']->addError (sprintf (STR_165,$sceneID), ERROR_TYPE_SERIOUS);
				}
			}
			else {
				$submitError = !$scene->handleFormSubmit (false);
			}
		}
		
		$action = $this->_getPageAction ();
		if ($submitError == true && 
			((strcasecmp ($action, 'edit') == 0) || (strcasecmp ($action, 'add') == 0))) {
			
			$isEdit = (strcasecmp ($action, 'edit') == 0);
			$scene = new SSScene;
			$scene->displayForm ($isEdit, '');
			
			$this->set ('left_sidebar', false);
			$this->set ('right_sidebar', false);
			
		}
		else if ($submit != ''){
			$verb = (strcasecmp ($action, 'edit') == 0) ? STR_68 : STR_69;
			$this->addNotification (sprintf (STR_166, $verb));
			$scene->view ();
			
		}
		
		parent::_formHandling ();		
	}
	
	/** Override in page object to determine if the user has permission to view the requested info
	 *
	 * @return bool True if permission is granted, false otherwise.
	 *  @access protected
	 */
	function _hasPermission () {
		
		$action = $this->queryGetValue (PAGE_ACTION);
		$user = $GLOBALS['APP']->getLoggedInUserObject();
		
		if ($action == 'view') {
			
			$scene = new SSScene;
			$id = $this->queryValue (PAGE_ACTION_SCENE_ID);
			$scene->set (PROP_ID, $id);
			if ($scene->load ()) {
				return $scene->hasPermissionToViewScene ();
			}
		}
		else {
			if ($action == 'add') {
				$story = new SSStory;
				$story->set (PROP_ID, $this->queryValue (PAGE_ACTION_STORY_ID));
				if ($story->load ()) {
					return $story->hasPermissionToAddScene ();
				}
			}
			else {
				$scene = new SSScene;
				$id = $this->queryValue (PAGE_ACTION_SCENE_ID);
				$scene->set (PROP_ID, $id);
				if ($scene->load ()) {
					return $scene->hasPermissionToEditScene ();
				}
			}
		}
		
		return false;
	}
	
}
?>