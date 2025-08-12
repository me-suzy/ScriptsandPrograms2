<?php
/** @file SSAnnouncePage.class.php
 * Renders announcement pages
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

/**  Handles the display and processing for a popup page
*/
class SSAnnouncePage extends SSPage {

	/** Called before any rendering of the page is done */
	function _preRender () {
	
		$GLOBALS['APP']->set ('section', SECTION_MEMBER);	
		
		parent::_preRender();
	}

	/** Displays the content of a page
	*/
	function _displayContent () {

		$action = $GLOBALS['APP']->queryGetValue (PAGE_ACTION);
						
		// Sets the title as it will appear in the caption of the web client.
		$this->set ('title', STR_1);
								
		////////////////////////////////////////////////////////////////////////////
		// ADD ANNOUNCEMENT FORM
		////////////////////////////////////////////////////////////////////////////
		$this->_formHandling ();
		
		$announceID = $GLOBALS['APP']->queryGetValue (PAGE_ACTION_ANNOUNCE_ID);
		$announcement = false;
		if ($announceID) {
			$announcement = new SSAnnouncement;
			$announcement->set (PROP_ID, $announceID);
			if (!$announcement->load ()) {
				$announcement = false;
			}
		}

		$user = $GLOBALS['APP']->getLoggedInUserObject ();
		if ($action == PAGE_ACTION_DELETE && 
			$announcement && $user && 
			($user->get ('user_type') == USER_TYPE_ADMIN)) {
			
			$accouncement = new SSAnnouncement;
			$accouncement->set (PROP_ID, $GLOBALS['APP']->queryGetValue (PAGE_ACTION_ANNOUNCE_ID));
			if ($announcement->load ()) {
				$announcement->delete();
			}
		}
		else {		
			////////////////////////////////////////////////////////////////////////////
			// VIEW ANNOUNCEMENTS
			////////////////////////////////////////////////////////////////////////////
			if ($announcement && ($action == PAGE_ACTION_VIEW)) {
				$announcement->view();
			}
			else if ($action == PAGE_ACTION_VIEW){
			
				$list = new SSItemLists;
				$announcements = $list->getAnnouncements (0);
				if ($announcements) {
					$array = $list->convertListToSmartyVariables ($announcements);
					$this->_smarty->assign ('ss_announcements', $array);
					$this->_smarty->display ('members/list_announcements.tpl');
				}
			}
		}
	}

	/**  Handles the display and processing of a form.
		Always call the parent when processing is complete.
	*/
	function _formHandling () {

		$submit = $this->_getFormAction ();
		$announce = new SSAnnouncement;
		$smarty = new SSSmarty;


		$submitSuccess = true;
		if ($submit != '') {

			$submitSuccess = $announce->handleFormSubmit (false);
			if ($submitSuccess) {				
				$announce->view ();
			}
		}

		$action = $GLOBALS['APP']->queryGetValue (PAGE_ACTION);

		if ($action == 'add') {
			// Get the last used form data (if any).
			$memObj = $GLOBALS['APP']->retrieveValue ('ANNOUNCEMENT_ADDEDIT_OBJ');
	
			if (($submit == '') || !$submitSuccess) {
	
				// When adding,we can just copy over the entire object if
				//	there was a submit error.
				if (!$submitSuccess && $memObj) {
					$announce = $memObj;
				}
	
				// This is just an add so there's no need to prepopulate
				$announce->prepareFormTemplate	($smarty, false);
	
				$smarty->display ('admin/form_announce.tpl');
				
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

		// Only admins can add and delete announcements.
		if ($action == PAGE_ACTION_ADD ||
			$action == PAGE_ACTION_DELETE) {
			
			$user = $GLOBALS['APP']->getLoggedInUserObject();
			if ($user) {
				return ($user->get ('user_type') == USER_TYPE_ADMIN);
			}
			else {
				// Not logged in.
				return false;
			}
		}		
		
		return true;
	}
	
}
?>