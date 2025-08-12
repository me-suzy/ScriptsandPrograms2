<?php

// $Id: empty_trash.php 118 2005-09-16 21:56:01Z stefan $

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2005, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages');

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Get page list from database
$database = new database();
$query = "SELECT * FROM ".TABLE_PREFIX."pages WHERE visibility = 'deleted' ORDER BY level DESC";
$get_pages = $database->query($query);

// Insert values into main page list
if($get_pages->numRows() > 0)	{
	while($page = $get_pages->fetchRow()) {
		// Delete page subs
		$sub_pages = get_subs($page['page_id'], array());
		foreach($sub_pages AS $sub_page_id) {
			delete_page($sub_page_id);
		}	
		// Delete page
		delete_page($page['page_id']);
	}
}

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error());
} else {
	$admin->print_success($TEXT['TRASH_EMPTIED']);
}

// Print admin 
$admin->print_footer();

?>