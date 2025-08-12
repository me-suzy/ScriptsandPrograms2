<?php
/** @file SSGroupPage.class.php
 * 	Renders group pages
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
class SSGroupPage extends SSPage {

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
		$this->set ('title', 'Groups');
								
		////////////////////////////////////////////////////////////////////////////
		// ADD GROUP FORM
		////////////////////////////////////////////////////////////////////////////
		$this->_formHandling ();
		
		$groupID = $GLOBALS['APP']->queryGetValue (PAGE_ACTION_GROUP_ID);
		$group = false;
		if ($groupID) {
			$group = generateObject (OBJECT_TYPE_GROUP, $groupID);
		}

		$user = $GLOBALS['APP']->getLoggedInUserObject ();
		
		$uid = $this->queryGetValue ('uid');
		$actUser = false;
		if ($uid) {
			// Get the user object of the user being acted on. Used
			//	with ban/unban/uninvite users.
			$actUser = new SSUser;
			$actUser->set (PROP_USERNAME, $uid);
			$actUser->load ($actUser);
		}
						
		$hasAdminPermission = $group && $user && (($user->get ('user_type') == USER_TYPE_ADMIN) || ($user->get(PROP_USERNAME) == $group->get (PROP_USERNAME)));
		if ($action == PAGE_ACTION_DELETE) {
			if ($group && $hasAdminPermission) {
				$group->delete();
			}
			else {
				if ($group) {
					$this->addError ('You do not have permission to delete this group.', ERROR_TYPE_SERIOUS);
				}
				else {					
					$this->addError ('A group with this ID does not exist.', ERROR_TYPE_SERIOUS);
				}
			}
		}
		else if ($action == PAGE_ACTION_WITHDRAW) {
			if ($group && ($user->get(PROP_USERNAME) == $actUser->get (PROP_USERNAME))) {
				if ($group->withdrawMembership($actUser)) {
					$this->addNotification ('You have successfully withdrawn your membership in "'.$group->get (PROP_NAME).'"');
				}
				$group->view();
			}
			else {
				if ($group) {
					$this->addError ('You do not have permission to decline the invitation.', ERROR_TYPE_SERIOUS);
				}
				else {					
					$this->addError ('A group with this ID does not exist.', ERROR_TYPE_SERIOUS);
				}
			}
		}			
		else if ($action == PAGE_ACTION_DECLINE_INVITATION) {
			if ($group && ($user->get(PROP_USERNAME) == $actUser->get (PROP_USERNAME))) {
				if ($group->cancelInvitedUser($actUser)) {
					$this->addNotification ('You have successfully declined membership in "'.$group->get (PROP_NAME).'"');
				}
				$group->view();
			}
			else {
				if ($group) {
					$this->addError ('You do not have permission to decline the invitation.', ERROR_TYPE_SERIOUS);
				}
				else {					
					$this->addError ('A group with this ID does not exist.', ERROR_TYPE_SERIOUS);
				}
			}
		}		
		else if ($action == PAGE_ACTION_ACCEPT_INVITATION) {
			if ($group && ($user->get(PROP_USERNAME) == $actUser->get (PROP_USERNAME))) {
				if ($group->joinInvitedUser($actUser)) {
					$this->addNotification ('You have successfully become a member of "'.$group->get (PROP_NAME).'"');
				}
				$group->view();
			}
			else {
				if ($group) {
					$this->addError ('You do not have permission to accept the invitation.', ERROR_TYPE_SERIOUS);
				}
				else {					
					$this->addError ('A group with this ID does not exist.', ERROR_TYPE_SERIOUS);
				}
			}
		}		
		else if ($action == PAGE_ACTION_BAN_USER) {
			if ($group && $hasAdminPermission) {
				$group->expelUser($actUser);
				$group->view();
			}
			else {
				if ($group) {
					$this->addError ('You do not have permission to ban this user.', ERROR_TYPE_SERIOUS);
				}
				else {					
					$this->addError ('A group with this ID does not exist.', ERROR_TYPE_SERIOUS);
				}
			}
		}
		else if ($action == PAGE_ACTION_UNBAN_USER) {
			if ($group && $hasAdminPermission) {
				$group->unexpelUser($actUser);
				$group->view();
			}
			else {
				if ($group) {
					$this->addError ('You do not have permission to unban this user.', ERROR_TYPE_SERIOUS);
				}
				else {					
					$this->addError ('A group with this ID does not exist.', ERROR_TYPE_SERIOUS);
				}
			}		}
		else if ($action == PAGE_ACTION_UNINVITE_USER) {
		
			if ($group && $hasAdminPermission) {
				$group->uninviteUser($actUser);
				$group->view();
			}
			else {
				if ($group) {
					$this->addError ('You do not have permission to uninvite this user.', ERROR_TYPE_SERIOUS);
				}
				else {					
					$this->addError ('A group with this ID does not exist.', ERROR_TYPE_SERIOUS);
				}
			}
		}
		else {		
			////////////////////////////////////////////////////////////////////////////
			// VIEW GROUP
			////////////////////////////////////////////////////////////////////////////
			if ($group && ($action == PAGE_ACTION_VIEW)) {
				$group->view();
			}
		}
	}

	/**  Handles the display and processing of a form.
		Always call the parent when processing is complete.
	*/
	function _formHandling () {

		$submit = $this->_getFormAction ();
		$group = new SSGroup;
		$action = $this->queryGetValue (PAGE_ACTION);
		
		$submitSuccess = true;
		if ($submit != '') {

			$form_type = $this->queryPostValue ('form_type');			
			$isEdit = ($form_type == 'edit');
			if ($isEdit) {		
				$group = generateObject (OBJECT_TYPE_GROUP, $this->queryPostValue ('group_id'));
			}
			
			$submitSuccess = $group->handleFormSubmit (($action == PAGE_ACTION_EDIT));
			if ($submitSuccess) {				
				$group->view ();
			}
		}

		if ($action == PAGE_ACTION_ADD) {
			// Get the last used form data (if any).
			$memObj = $GLOBALS['APP']->retrieveValue ('GRUOP_ADDEDIT_OBJ');
	
			if (($submit == '') || !$submitSuccess) {
	
				// When adding,we can just copy over the entire object if
				//	there was a submit error.
				if (!$submitSuccess && $memObj) {
					$group = $memObj;
				}
	
				// This is just an add so there's no need to prepopulate
				$group->prepareFormTemplate	($this->_smarty, false);
	
				$this->_smarty->display ('members/form_group.tpl');
				
				$this->set ('left_sidebar', false);
				$this->set ('right_sidebar', false);
			}
		}
		else if ($action == PAGE_ACTION_EDIT) {
			// Get the last used form data (if any).
			$memObj = $GLOBALS['APP']->retrieveValue ('GRUOP_ADDEDIT_OBJ');
	
			if (($submit == '') || !$submitSuccess) {

				if (!$submitSuccess && $memObj) {
					$group = $memObj;
				}
				else {
					// Use the stored object.
					$group = generateObject (OBJECT_TYPE_GROUP, $this->queryGetValue (PAGE_ACTION_GROUP_ID));
				}
	
				$group->prepareFormTemplate	($this->_smarty, true);
	
				$this->_smarty->display ('members/form_group.tpl');
				
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
				return true;
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
