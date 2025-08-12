<?php
/**
 * @file SSUserPage.class.php
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
 * @version 0.1
 * @date October, 2003
 */

/**  Handles the display and processing for a user page
 *	User pages include anything having to do with the processing
 *	of users and user settings or actions.
 *	@author Karim Shehadeh
 *	@date 2/24/2004
*/
class SSUserPage extends SSPage {


	/**  Called before any rendering of the page is done */
	function _preRender () {
	
		$GLOBALS['APP']->set ('section', SECTION_MEMBER);	
		
		parent::_preRender();
	}
	
	/**  Displays the content of a page
	*/
	function _displayContent () {
	
		$smarty = new SSSmarty;
		
		$action = $GLOBALS['APP']->queryGetValue (PAGE_ACTION);
		
		// Sets the title as it will appear in the caption of the web client.
		$this->set ('title', STR_243);
		
		// See if a user is trying to login or out through this page.
		$smarty->prepareUserVariables ();
		
		// We assume that the registration form will be displayed
		//	though this may be changed in the near future.
		$displayRegistrationForm = true;
				
		$this->_formHandling ();
			
		// CONFIRM USER
		if (strcasecmp ($action, 'confirm') == 0) {
		
			$this->set ('title', STR_244);
			
			$displayRegistrationForm = false;
			
			$email = $GLOBALS['APP']->queryGetValue ('email');	
			$hash = $GLOBALS['APP']->queryGetValue ('hash');
			
			$user = new SSUser;
			if ($user->confirmUser ($hash, $email)) {
				$this->addNotification (STR_245);
			}
		}		
		else if ($action == '' && $GLOBALS['APP']->isUserLoggedIn()) {
			
			$user = $GLOBALS['APP']->getLoggedInUserObject();
			
			// Get the user statistics.
			$array = array ();
			$user->prepareStatisticsSmartyVariables ($array);
			$smarty->assign ('ss_stats', $array);
			
			// Group Data
			$smarty->prepareGroupVariables ();
			
			// No action specified and already logged in so display the 
			//	main page for members.
			$smarty->display ('members/index_member.tpl');
		}
	}	
	
	/** Override in page object to determine if the user has permission to view the requested info
	 *
	 * @return bool True if permission is granted, false otherwise.
	 *  @access protected
	 */
	function _hasPermission () {
		
		return true;
	}
	
	/** Handles the display and processing of a form.
	 *	Always call the parent when processing is complete.
	 */
	function _formHandling () {
	
		$GLOBALS['APP']->rememberValue ('REGISTRATION_USER_OBJ', NULL);		
		
		$action = $this->queryValue ('a');
		$submit = $this->queryPostValue ('submit');
		$formSuccess = false;
		
		if ($submit != '') {
			
			$user = new SSUser;
			if ($action == 'edit') {
				$id = $this->queryPostValue ('username');
				if ($id) {
					$user->set ('username', $id);
					if (!$user->load ()) {
						$this->addError (sprintf (STR_246, $id));
						return false;
					}
				}
			}
			
			if ($action == 'message')
			{
				$user = $GLOBALS['APP']->getLoggedInUserObject();
				if ($user) {
					$touser = new SSUser;
					$touser->set ('username', $this->queryValue ('username'));
					if ($touser->load()) {				
						if ($user->sendMessage ($touser, $this->queryPostValue ('subject'), $this->queryPostValue ('message'))) {
							$this->addNotification (sprintf ("The message was successfully sent to %s", $touser->get ('username')));
							$formSuccess = true;
						}
					}
				}
				else {
					$this->addError ("You must be logged in to send messages to other users", false);
				}
			}
			else {
				$formSuccess = $user->handleFormSubmit ($action == 'edit');
				if ($formSuccess) {
					
					if ($action == 'edit') {
						$this->addNotification (sprintf (STR_247, $user->get ('username')));
					}
				}
			}
		}
		
		// GET REGISTRATION INFO
		if (($action == 'add' || ($action == '' && !$GLOBALS['APP']->isUserLoggedIn())) && !$formSuccess) {
		
			$user = $GLOBALS['APP']->retrieveValue ('REGISTRATION_USER_OBJ');
			if (!$user) {
				$user = new SSUser;
			}			
			$user->displayForm (false);
		}
		else if ($action == 'edit' && !$formSuccess) {
		    
			$user = $GLOBALS['APP']->getLoggedInUserObject ();
			if ($user) {
				$user->displayForm (true);
			}
		}	
		else if ($action == 'message' && !$formSuccess) {
			
			// Display the form to send a message to another member
			$user = $GLOBALS['APP']->getLoggedInUserObject ();
			if ($user) {
				
				$touser = new SSUser;
				$touser->set ('username',$this->queryValue ('username'));
				if ($touser->load()) {
					$user->displaySendMessageForm ($touser);
				}
				else {
					$this->addError (sprintf ("The user %s could not be found", $touser->get('username')), false);
				}
			}
			else {
				$this->addError ("You must be logged in to send messages to other users", false);
			}
		}
		
		parent::_formHandling ();
	}	
}
?>