<?php
/** @file SSAuthorFrontPage.class.php
 * Renders the main authoring page.
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
 *	@date March, 2004
 */

/**  Handles the display and processing for a scene page
*/
class SSAuthorFrontPage extends SSPage {
	
	/** Called before any rendering of the page is done */
	function _preRender () {
	
		$GLOBALS['APP']->set ('section', SECTION_ADMIN_MAIN);	
		
		// Check for login/logout
		$this->_smarty->processUserActions ();
		parent::_preRender();
	}
	
	/**  Displays the content of a page
	*/
	function _displayContent () {
					
		// Sets the title as it will appear in the caption of the web client.
		$this->set ('title', STR_16);
						
		// Setup content area
		$user = $GLOBALS['APP']->getLoggedInUserObject ();
		$coll = new SSStoryCollection ('SSStory');
		
		
		if ($user) {
						
			// Recent change list.
			$list = new SSItemLists;
			$changed = $list->getUserRecentlyChanged ();
			$this->_smarty->assign ('ss_recent_changes', $list->convertListToSmartyVariables ($changed));
			
			// Contributions
			$coll->clear ();
			$contribs = array ();
			$coll->prepareContributionList ($array, $user->get('username'));
			$this->_smarty->assign ('ss_story_contrib_list', $array);
			
			$coll->clear ();
			$coll->prepareSmartyStatusStoryList ($this->_smarty, STORY_STATUS_ACTIVE);
		}
				
		// Output the index.
		$this->set ('left_sidebar', true);
		$this->set ('right_sidebar', false);		
		$this->_smarty->display ('authoring/index_author.tpl');
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