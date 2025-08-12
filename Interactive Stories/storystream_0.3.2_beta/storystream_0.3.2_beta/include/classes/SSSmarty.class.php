<?php
/** @file SSSmarty.class.php
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
	@version 0.1
	@date October, 2003	
*/

/** Extends the smarty template clas to include
	some storystream specific methods.  Note that this
	uses the global application object extensively.
*/
class SSSmarty extends Smarty 
{	
	/**  Constructor */
	function SSSmarty () {	
	
		$this->cache_dir = $GLOBALS['SCRIPT_ROOT'].'include/smarty/cache';	
		$this->compile_dir = $GLOBALS['SCRIPT_ROOT'].'include/smarty/templates_c';
		$this->template_dir = $GLOBALS['SCRIPT_ROOT'].'themes/'.$GLOBALS['global_theme'].'/templates';
		$this->config_dir = $GLOBALS['SCRIPT_ROOT'].'include/smarty/configs';
		$this->clear_all_cache ();
		$this->compile_check = true;
		$this->debugging = false;
		$this->assign ('script_root', $GLOBALS['SCRIPT_ROOT']);
		$this->assign ('authoring_root', AUTHORING_ROOT.'/');
		$this->assign ('member_root', MEMBER_ROOT.'/');
		$this->assign ('reading_root', READING_ROOT.'/');
		$this->assign ('section_values', array ('author'=>SECTION_ADMIN_MAIN, 'main'=>SECTION_MAIN, 'reading'=>SECTION_READ, 'member'=>SECTION_MEMBER));

		// Make sure the compile dir has write permissions
		$perms = fileperms ($this->compile_dir);
		if (!(($perms & 0x0010) &&
			($perms & 0x0080) &&
			($perms & 0x0002)))
		{
			die ('The server does not have appropriate write permissions in the compile directory for smarty templates.');
		}
		
		if (SS_DEBUG) {		
			// Since the compiled files are not tied to a static
			//	source file, there's no need to keep them - they're not reused.
			$this->clear_compiled_tpl ();
		}
	}
	
	function prepareAnnouncementVariables ()
	{
		$list = new SSItemLists;
		$announcements = $list->getAnnouncements (10);
		if ($announcements) {
			$array = $list->convertListToSmartyVariables ($announcements);
			$this->assign ('ss_announce_list_brief', $array);
		}
	}
	
	/** 
	* Sets up all group information for the currently logged in user
	* 	ss_group_info
	*/
	function prepareGroupVariables (){
		
		$group_smarty = array();
		
		$loggedIn = $GLOBALS['APP']->isUserLoggedIn ();
		$user_smarty['logged_in'] = $loggedIn;
		
		if ($loggedIn) {
			$user = $GLOBALS['APP']->getLoggedInUserObject ();
			if ($user) {
				// Get the list of groups which this user started
				$groups = $user->getGroupAdminList();
				$group_smarty['mygroups'] = array();
				foreach ($groups as $group) {
					$array = array();
					$group->prepareSmartyVariables ($array);
					$group_smarty['mygroups'][] = $array;
				}
				
				// Get the invitation list for the user.
				$groups = $user->getInvitationList();
				$group_smarty['invitations'] = array();
				foreach ($groups as $group) {
					$array = array();
					$group->prepareSmartyVariables ($array);
					$group_smarty['invitations'][] = $array;
				}		
				
				// Get the list of groups to which this user belongs
				$groups = $user->getGroupList();
				$group_smarty['memberships'] = array();
				foreach ($groups as $group) {
					$array = array();
					$group->prepareSmartyVariables ($array);
					$group_smarty['memberships'][] = $array;
				}
			}
		}
		$this->assign ("ss_group_info", $group_smarty);
	}
	/**  Sets up all user related smarty template variables
     *	Variables that are initialized are:
     *		ss_user
	*/
	function prepareUserVariables () {
					
		$user_smarty = array ();
		$user_smarty['username'] = '';
		$user_smarty['login_time'] = '';
		$user_smarty['logged_in'] = false;		
		
		$loggedIn = $GLOBALS['APP']->isUserLoggedIn ();
		$user_smarty['logged_in'] = $loggedIn;
		
		if ($loggedIn) {
			$user = $GLOBALS['APP']->getLoggedInUserObject ();
			if ($user) {
				$user_smarty['user_type'] = $user->get ('user_type');
				$user_smarty['username'] = $user->get ('username');
				$user_smarty['login_time'] = date ('F j, Y, g:i a', $user->get ('date_lastlogin'));
			}
		}
		
		$this->assign ("ss_user", $user_smarty);
	}
	
	/**  Sets up all error variables for the template 
     *	Variables that are initialized are:
     *		SS_Errors - array of strings
	*/
	function prepareErrorValues () {
		
		$this->assign ("SS_Errors", $GLOBALS['APP']->getGlobalErrors ());
	}
	
	/**  Sets up all notification variables for the template 
     *	Variables that are initialized are:
     *		SS_Notifications - array of strings
	*/
	function prepareNotificationValues () {
		
		$this->assign ("SS_Notifications", $GLOBALS['APP']->getNotifications ());
	}
	
	/**  Handles any user registration/login/logout actions input via form variables
     *	This can be called before the output of a template's contents
     *	to process a login, logout, registration requests.		
	*/
	function processUserActions () {
	
		$submit = $GLOBALS['APP']->queryPostValue ('submit');
		if (strcasecmp ($submit, 'login') == 0) {
			
			$user = $GLOBALS['APP']->queryPostValue ('username');
			$pass = $GLOBALS['APP']->queryPostValue ('password');
						
			// Errors are logged to the global error queue
			$GLOBALS['APP']->loginUser ($user, $pass);
		}
		else {
		
			if (isset ($_GET['logout']))  {
			
				// Errors are logged to the global error queue
				$GLOBALS['APP']->logoutUser ();
			}
		}
	}
	
	/**  Prepares smarty variables for the outer template.
     *	@param SSPageInfo $info Information about the page to be displayed
	*/	
	function prepareMainTemplate ($info) {
	
		// Setup content area
		$user = $GLOBALS['APP']->getLoggedInUserObject ();
		
		$ss_page = $this->get_template_vars('ss_page');
		if ($ss_page['left_sidebar']) {
			$coll = new SSStoryCollection ('SSStory');
			$coll->clear ();
			if ($GLOBALS['APP']->isUserLoggedIn()) {
				$coll->prepareSmartyStoryList ($this);
				
				$bmColl = new SSBookmarkCollection ('SSBookmark');
				if ($GLOBALS['APP']->isUserLoggedIn()) {
					$bmColl->prepareSmartyUserBookmarkList ($this);
				}
			}	
			$coll->prepareSmartyRecentlyCreatedStoryList ($this, 10);
			$coll->prepareSmartyRecentlyChangedStoryList ($this, 10);
						
		}
		
		$this->prepareErrorValues ();
		$this->prepareNotificationValues ();
		$this->assign ('ss_content', $info->get ('content'));
		$this->assign ('ss_title', $info->get ('title'));
	}
}

?>