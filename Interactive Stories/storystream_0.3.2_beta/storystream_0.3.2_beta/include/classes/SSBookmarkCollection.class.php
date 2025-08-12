<?php
/** @file SSBookmarkCollection.class.php
 *  
 * Provides easy access to collections of bookmark objects.
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
 *	@date 3/14/2004
 */

/** Used to collect and display story objects
 *	@author Karim Shehadeh
 *	@date 3/2/2004
 */
class SSBookmarkCollection extends SSCollection
{	
	function SSBookmarkCollection ()
	{
		parent::SSCollection('ssbookmark');
	}
	
	/** Retrieves all bookmarks entered by the given user
	 *
	 * Defaults to active status.
	 *
	 * @param string $username The user whose bookmarks are to be retreived or empty to use the logged-in username
	 * @return array An array of SSBookmark objects or an empty array if none was found.
	 */
	function getAllUserBookmarks ($username='') {
		
		if (!$username) {
			$user = $GLOBALS['APP']->getLoggedInUserObject ();
			$username = $user->get ('username');
		}
		
		// Get the stories started by this user
		$where = array ();
		if ($username) {
			$where = array ('user_id' => '"'.$username.'"');
		}		
		
		$this->load ($where);
		return $this->getObjects ();
	}

	/**  Handles filling in a smarty array of user bookmarks
     *	Variables that are initialized are:
     *		ss_user_bookmarks
     *		
     *	@param SSSmarty $smarty The smarty variable to populate
     *	@param string $userName The username of the user whose bookmarks will be retrieved. Empty to get logged-in user bookmarks
	 */
	function prepareSmartyUserBookmarkList (&$smarty, $user='') {
		$bmObjects = array ();
		
		if ($GLOBALS['APP']->isUserLoggedIn()) {
			
			// Bookmarks only are available if the user is logged in.
			$bmObjects = $this->getAllUserBookmarks ($user);
		}
				
		$this->prepareSmartyBookmarkList ($smarty, $bmObjects);
	}
	
	/**  Handles filling in a smarty array of given bookmarks
     *	Variables that are initialized are:
     *		ss_user_bookmarks
     *		
     *	@param SSSmarty $smarty The smarty variable to populate
     *	@param array $bmObjects The array of bookmark objects to convert to smarty variables.
	 */
	function prepareSmartyBookmarkList (&$smarty, &$bmObjects) {
		
		$bmList = array ();
		foreach ($bmObjects as $bm) {
		
			$ss_bookmark = array ();
			$bm->prepareSmartyVariables ($ss_bookmark, true);			
			array_push ($bmList, $ss_bookmark);			
		}
		
		$smarty->assign ('ss_user_bookmarks', $bmList);
	}
}
?>
