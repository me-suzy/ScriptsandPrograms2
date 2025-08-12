<?php

/**  Handles the display and processing for a scene page
*/
class SSMainFrontPage extends SSPage {

	/**  called before any rendering of the page is done */
	function _preRender () {
	
		// The section of the site 
		$GLOBALS['APP']->set ('section', SECTION_MAIN);

		// Check for login/logout
		$this->_smarty->processUserActions ();
		
		parent::_preRender();
	}
	
	/**  Displays the content of a page
	*/
	function _displayContent () {
				
		// Sets the title as it will appear in the caption of the web client.
		$this->set ('title', STR_100);
								
		$phpbb = new SSDiscussionBoard;
		$phpbb->prepareSmartyVariablesForRecentPosts($this->_smarty);
					
		// Highest Rated Stories.
		$lists = new SSItemLists;
		$highlight = $lists->getHighestRatedItems (OBJECT_TYPE_STORY, 3);
		$this->_smarty->assign ('ss_highlight_stories', $lists->convertListToSmartyVariables ($highlight));

		// Announcements
		$this->_smarty->prepareAnnouncementVariables ();
		
		/*				
		// Featured Members
		$featured = $lists->getFeaturedMembers ();
		$final = array ();
		$stories = new SSStoryCollection ('SSStory');
		foreach ($featured as $member) {
			$contribs = array ();
			$stories->prepareContributionList ($contribs, $member->get (PROP_USERNAME));
			$fma = array ();
			$member->prepareSmartyVariables ($fma);
			$fma['contributions'] = $contribs;
			$final[] = $fma;
		}
		$this->_smarty->assign ('ss_featured_members', $final);
		*/
		
		$this->set ('left_sidebar', false);
		
		// Output the index.		
		$this->_smarty->display ('index_main.tpl');		
	}	                      
		
	/** Override in page object to determine if the user has permission to view the requested info
	 *
	 * @return bool True if permission is granted, false otherwise.
	 *  @access protected
	 */
	function _hasPermission () {
		return true;
	}
}
?>