<?php
/** @file SSPopupPage.class.php
 * Renders popup content
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
class SSPopupPage extends SSPage {

	/**  called before any rendering of the page is done */
	function _preRender () {
	
		$GLOBALS['APP']->set ('section', SECTION_ADMIN_MAIN);			
		parent::_preRender();
	}

	/**  Displays the content of a page
	*/
	function _displayContent () {

		$this->set ('template', 'popup.tpl');
		
		// Currently, this page is only for setting the license
		//	type after returning from the CC license selection
		//	site.
		$t = $this->queryGetValue ('t');
		$i = $this->queryGetValue ('i');
		$object = generateObject ($t, $i);
		if ($object) {
				
			$object->updateLicense ();
			
			$script = 'window.opener.location.reload()';
			$content = '<div align="center"><h3>You may now close this window</h3></div>';

			$content .= '<div align="center"><input type="button" value="Close Window" class="formButton" onClick="window.close()"></div>';

			$this->_smarty->assign ('ss_script', $script);
			$this->_smarty->assign ('ss_popup_content', $content);
				
		}
		else {
			$this->_smarty->assign ('ss_popup_content', '<div align="center" style="font-weight:bold">'.STR_118.'</div>');
			$this->addError ( STR_119, ERROR_TYPE_SERIOUS);
		}
	}	
	
	/** Override in page object to determine if the user has permission to view the requested info
	 *
	 * @return bool True if permission is granted, false otherwise.
	 *  @access protected
	 */
	function _hasPermission () {

		$user = $GLOBALS['APP']->getLoggedInUserObject();
		if ($user)  {
			return true;
		}
		return false;
	}
	
}
?>
