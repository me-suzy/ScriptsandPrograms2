<?php
	
/**  Holds information regarding a page in StoryStream
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
 *	This is the object that's returned from the page rendering
 *	callbacks.
 */
class SSPage extends SSObject
{
	/**  The smarty object to use for all smarty renderings */
	var $_smarty;
	
	function SSPage () {
		$this->_addProperties ();
	}
	
	/**  Adds all associated properties
     *	This need only be called once per instantiation of this class
     *	and is handled automatically by the base class as long
     *	as its constructor is called.		
	*/
	function _addProperties () {
		$this->_addProperty ('url', '');
		$this->_addProperty ('timeout', 3);
		$this->_addProperty ('title', 'StoryStream');
		$this->_addProperty ('left_sidebar', true);
		$this->_addProperty ('right_sidebar', true);
		$this->_addProperty ('template', 'main.tpl');
		$this->_addProperty ('content', '');
		
		if (SS_DEBUG) {
			$this->_addProperty ('debug', '');
		}
	}
	
	/** Makes the main template redirect itself after the given timeout period.
     *	The main template knows to redirect itself if the ss_page.url smarty
     *	var is set to something.  Use the timeout to set how long it takes to 
     *	do the redirect.
     *	@param string $url The URL to redirect to or an empty string to clear it.
     *	@param int $timeout How long to wait before redirecting or 0 for instant.
	*/	
	function setRedirect ($url, $timeout) {
		$this->set ('url', $url);
		$this->set ('timeout', $timeout);
	}
	
	/**  called before any rendering of the page is done */
	function _preRender () {
		// See if a user is trying to login or out through this page.
		$this->_smarty->prepareUserVariables ();
		$this->_smarty->assign ('section',$GLOBALS['APP']->get ('section'));
	}
	
	/**  Renders the page
	*/
	function render () {
	
		$this->_smarty = new SSSmarty;
			
		$this->_preRender ();
		if ($this->_hasPermission ()) {
		
			// Turn on output buffering so we can catch the content.
			ob_start ();
							
			// Render content
			$this->_displayContent ();
			
			// Get the content from the buffer
			$content = ob_get_contents ();		
						
			// Clear out the buffer.
			ob_end_clean ();
		}
		else {
			$content = '';
			$this->addError (STR_117, ERROR_TYPE_SERIOUS);
		}
				
		// Prepare the smarty variables for the template.
		$this->set ('content', $content);
		
		// This must come BEFORE prepareMainTemplate to assure
		//	that the sidebar settings are there.
		$this->_smarty->assign ('ss_page', array (
				'url'=>$this->get ('url'), 
				'timeout'=>$this->get ('timeout'), 
				'left_sidebar'=>$this->get ('left_sidebar'), 
				'right_sidebar'=>$this->get ('right_sidebar')));
				
		$this->_smarty->prepareMainTemplate ($this);
		
		if (SS_DEBUG) {		
			$this->_smarty->assign ('debug_messages', $GLOBALS['APP']->get ('debug'));
		}
		
		$this->_smarty->display ($this->get ('template'));
	}
	
	/** Override in page object to determine if the user has permission to view the requested info
	 *
	 * @return bool True if permission is granted, false otherwise.
	 *  @access protected
	 */
	function _hasPermission () {
		die ('SSPage::_hasViewPermission >> MUST OVERRIDE');
	}
	
	/**  Displays the content of a page
     *	This is a virtual function and should be overridden.
     *	@access protected
	*/
	function _displayContent () {
		die ('SSPage::_displayContent >> MUST OVERRIDE');
	}

	/**  Handles the display and processing of a form.
     *	All pages can handle the submission of a classification
     *	or a rating.  So we put the form handler for these 
     *	types of forms in the base page class.
	*/
	function _formHandling () {
	}
	
	/**  Retrieves the standard page action parameter from the URL
     *	@return string The value of the action parameter.
	*/	
	function _getPageAction () {
		return $GLOBALS['APP']->queryGetValue (PAGE_ACTION);
	}

	/**  Retrieves the standard form submit action parameter from the POST
     *	@return string The value of the form submit action parameter.
	*/	
	function _getFormAction () {
		return $GLOBALS['APP']->queryPostValue ('submit');
	}		
}

?>