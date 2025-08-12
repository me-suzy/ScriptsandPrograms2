<?php

/**  Handles the display and processing for the reading frontpage
*/
class SSReadFrontPage extends SSPage {

	/**  called before any rendering of the page is done */
	function _preRender () {
	
		// The section of the site 
		$GLOBALS['APP']->set ('section', SECTION_READ);
		
		parent::_preRender();
	}
	
	function PrepareHighestRatedStories (&$smarty)
	{
		// Highest Rated Stories.
		$highestRated = SSItemLists::getHighestRatedItems (OBJECT_TYPE_STORY, 10);
		$smarty->assign ('ss_highest_rated_stories', SSItemLists::convertListToSmartyVariables ($highestRated));
	}
	
	function PrepareMostViewedStories (&$smarty)
	{
		// Most Viewed Stories.
		$mostViewed = SSItemLists::getMostViewedItems (OBJECT_TYPE_STORY, 10);			
		$smarty->assign ('ss_most_viewed_stories', SSItemLists::convertListToSmartyVariables ($mostViewed));
	}
	
	function PrepareStoryClassifications (&$smarty)
	{
		// Story classification list
		$classifications = array ();
		foreach ($GLOBALS['classifications'] as $classification) {
			$class = array ('name'=>$classification);
			$storyObjects = SSItemLists::getClassifiedAsItems (OBJECT_TYPE_STORY, $classification, 10);	
			if (count ($storyObjects) > 0) {	
				$class['stories'] = SSItemLists::convertListToSmartyVariables ($storyObjects);
				$class['display_more'] = true;
			}
			else {
				$class['stories'] = array ();
				$class['display_more'] = false;				
			}
			$class ['is_adult'] = in_array (strtolower($classification), array_to_lower($GLOBALS['adult'])) ? true : false;
			
			array_push ($classifications, $class);
		}
		$smarty->assign ('ss_classification_list', $classifications);
	}	
	/**  Displays the content of a page
	*/
	function _displayContent () {
				
		// Sets the title as it will appear in the caption of the web client.
		$this->set ('title', STR_131);
				
		$this->_smarty->prepareUserVariables ();
		
		$this->PrepareMostViewedStories ($this->_smarty);
		$this->PrepareHighestRatedStories ($this->_smarty);
		$this->PrepareStoryClassifications ($this->_smarty);
			
		$this->_smarty->prepareErrorValues ();
		$this->_smarty->prepareNotificationValues ();
		$this->set ('left_sidebar', true);
		$this->set ('right_sidebar', false);
		
		// Output the index.		
		$this->_smarty->display ('reading/index_read.tpl');
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