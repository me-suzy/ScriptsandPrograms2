<?php
/** @file SSStoryPage.class.php
 * Contains the SSStoryPage class used to render story related
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
class SSStoryPage extends SSPage {
	
	/** Called before any rendering of the page is done */
	function _preRender () {
	
		$GLOBALS['APP']->set ('section', SECTION_ADMIN_MAIN);	
		
		parent::_preRender();
	}

	/** Displays the content of a page
	*/
	function _displayContent () {

		$action = $GLOBALS['APP']->queryGetValue (PAGE_ACTION);
				
		// The section of the site 
		$this->set ('section', SECTION_SCENE);
		
		// Sets the title as it will appear in the caption of the web client.
		$this->set ('title', STR_200);
				
		////////////////////////////////////////////////////////////////////////////
		// ADD/EDIT STORY/FORK FORM
		////////////////////////////////////////////////////////////////////////////
		$this->_formHandling ();

		////////////////////////////////////////////////////////////////////////////
		// VIEW STORIES
		////////////////////////////////////////////////////////////////////////////
		$objType = $this->queryGetValue ('objtype');
		
		$storyID = $GLOBALS['APP']->queryGetValue (PAGE_ACTION_STORY_ID);
		$story = new SSStory;
		$story->set (PROP_ID, $storyID);
		if ($storyID > 0 && $story->load ()) {
			if ($action == 'view') {
				// View a specific story
				$story->view();
			}
			else if ($action == 'delete') {
				$story->delete ();
				$story->view();
			}
			else if ($action == 'undelete') {
				$story->undelete ();
				$story->view();
			}
			else if ($action == 'true_delete') {
			
				if (!$story->trueDelete()) {
					$story->view();
				}
			}
			else if ($action == 'activate') {
				$story->activate ();
				$story->view();
			}
			else if ($action == 'draft') {
				$story->draft ();
				$story->view();
			}
		}
	}

	/**  Handles the display and processing of a form.
		Always call the parent when processing is complete.
	*/
	function _formHandling () {

		$submit = $this->_getFormAction ();
		$story = new SSStory;
		$smarty = new SSSmarty;


		$submitSuccess = true;
		if ($submit != '') {

			$story = new SSStory;
			$form_type = $GLOBALS['APP']->queryPostValue ('form_type');
			$isEdit = ($form_type == 'edit');
			if ($isEdit) {

				// If we're editing a story then we must first
				//	load the original to verify that the story with
				//	the given ID exists.
				$story->set (PROP_ID, $GLOBALS['APP']->queryPostValue (PAGE_ACTION_STORY_ID));
				if (!$story->load ()) {

					// Invalidate the story
					$story->set (PROP_ID, 0);
				}
			}

			$submitSuccess = $story->handleFormSubmit ($isEdit);
			if ($submitSuccess) {
				
				$action = ($isEdit ? STR_68 : STR_69);
				$this->addNotification (sprintf (STR_201, $action));
				$story->view ();
			}
		}

		$action = $GLOBALS['APP']->queryGetValue (PAGE_ACTION);

		// Get the last used form data (if any).
		$memObj = $GLOBALS['APP']->retrieveValue ('STORY_ADDEDIT_OBJ');

		if (($submit == '') || !$submitSuccess) {
			if (strcasecmp ($action, 'edit')==0) {

				$id = $GLOBALS['APP']->queryGetValue (PAGE_ACTION_STORY_ID);
				$story->set (PROP_ID, $id);
				if ($story->load ()) {

					// If there was a submit error then use
					//	the values that were previously entered
					//	instead of those that are in the database.
					if (!$submitSuccess && $memObj) {

						$defaultTitle = $memObj->get (PROP_NAME);
						$defaultDescription = $memObj->get (PROP_DESCRIPTION);
						$defaultType = $memObj->get (PROP_TYPE);

						$story->set (PROP_NAME, $defaultTitle);
						$story->set (PROP_DESCRIPTION, $defaultDescription);
						$story->set (PROP_TYPE, $defaultType);
					}

					$story->prepareFormTemplate	($smarty, true);

					$smarty->display ('authoring/form_story.tpl');
					
					$this->set ('left_sidebar', false);
					$this->set ('right_sidebar', false);
					
				}
			}
			else if (strcasecmp ($action, 'add')==0) {

				// When adding,we can just copy over the entire object if
				//	there was a submit error.
				if (!$submitSuccess && $memObj) {
					$story = $memObj;
				}

				// This is just an add so there's no need to prepopulate
				$story->prepareFormTemplate	($smarty, false);

				$smarty->display ('authoring/form_story.tpl');
				
				$this->set ('left_sidebar', false);
				$this->set ('right_sidebar', false);
				
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
		
		$action = $this->queryValue (PAGE_ACTION);
		
		if ($action == 'view') {
			$story = new SSStory;
			$id = $GLOBALS['APP']->queryValue (PAGE_ACTION_STORY_ID);
			$story->set (PROP_ID, $id);
			if ($story->load ()) {
				return $story->hasPermissionToViewStory ();
			}
		}
		else {
			if ($action == 'add') {
				return $GLOBALS['APP']->hasPermissionToAddStory ();
			}
			else {
				$story = new SSStory;
				$id = $GLOBALS['APP']->queryValue (PAGE_ACTION_STORY_ID);
				$story->set (PROP_ID, $id);
				if ($story->load ()) {
					return $story->hasPermissionToEditStory ();
				}
			}
		}
		
		return false;
	}
	
}
?>
