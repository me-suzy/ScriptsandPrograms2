<?php
/** @file SSBookmarkPage.class.php
 * Contains the SSBookmarkPage class used to render bookmark content
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
class SSBookmarkPage extends SSPage {

	/**  Displays the content of a page
	*/
	function _displayContent () {

		$action = $GLOBALS['APP']->queryGetValue (PAGE_ACTION);
				
		// Sets the title as it will appear in the caption of the web client.
		$this->set ('title', STR_28);
				
		////////////////////////////////////////////////////////////////////////////
		// ADD/EDIT STORY/FORK FORM
		////////////////////////////////////////////////////////////////////////////
		$this->_formHandling ();

		////////////////////////////////////////////////////////////////////////////
		// HANDLE FORK ACTIONS
		////////////////////////////////////////////////////////////////////////////
		
		$bookmark_id = $GLOBALS['APP']->queryGetValue ('bmi');
		if ($action == 'view' && $bookmark_id > 0) {
			// Find and display the bookmark information			
			$bookmark = new SSBookmark;
			$bookmark->set (PROP_ID, $bookmark_id);
			if ($bookmark->load ()) {
				$bookmark->view ();
			}
		}
		else if ($action == 'delete' && $bookmark_id > 0) {
			$bookmark = generateObject (OBJECT_TYPE_BOOKMARK, $bookmark_id);
			if ($bookmark) {
				$bookmark->delete ();
				
				// Jump back to the source page.
				$url = $GLOBALS['APP']->getLocation ();
				if ($url) {
					$this->setRedirect ($url, 2);
				}
				
			}
		}
		else if ($action == PAGE_ACTION_BOOKMARK_MANAGER)
		{
			if ($GLOBALS['APP']->isUserLoggedIn ()) {
				$bookmarks = new SSBookmarkCollection;
				$bookmarks->prepareSmartyUserBookmarkList ($this->_smarty);
				$this->_smarty->display ('reading/bookmark_manager.tpl');								
				$GLOBALS['APP']->storeLocation();
			}
		}
		
	}

	/**  Handles the display and processing of a form.
	*/
	function _formHandling () {

		$submit = $this->_getFormAction ();
		$action = $this->queryGetValue (PAGE_ACTION);
		$bookmark = new SSBookmark;
		$formtype = $this->queryValue ('form_type');
		$submitSuccess = true;
		
		// If the bookmarks form is being submitted then
		//	let the bookmark object handle the submission.
		if ($submit) {
			if (!$bookmark->handleFormSubmit ($formtype == 'edit')) {
				
				// Try again.
				$bookmark->displayForm ($formtype == 'edit');
			}
			else {
			
				// Jump back to the source page.
				$url = $GLOBALS['APP']->getLocation ();
				if ($url) {
					$this->setRedirect ($url, 3);
				}
			}			
		}
		else {
			
			// We just need to display the form.
			if ($action == 'edit' || $action == 'add') {
				
				// Display the form (perhaps another time)
				$bookmark->displayForm ($action == 'edit');
			}
		}
	}
	
	/** Override in page object to determine if the user has permission to view the requested info
	 *
	 * @return bool True if permission is granted, false otherwise.
	 *  @access protected
	 */
	function _hasPermission () {

		if ($GLOBALS['APP']->isUserLoggedIn ()) {		
			return true;
		}
		return false;
	}

		
}
?>