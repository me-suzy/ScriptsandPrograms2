<?php

// $Id: restore.php 35 2005-09-06 18:45:52Z stefan $

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

// Get page id
if(!isset($_GET['page_id']) OR !is_numeric($_GET['page_id'])) {
	header("Location: index.php");
} else {
	$page_id = $_GET['page_id'];
}

// Create new admin object and print admin header
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_delete');

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Get perms
$results = $database->query("SELECT admin_groups,admin_users FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'");
$results_array = $results->fetchRow();

// Find out more about the page
$query = "SELECT * FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'";
$results = $database->query($query);
if($database->is_error()) {
	$admin->print_error($database->get_error());
}
if($results->numRows() == 0) {
	$admin->print_error($MESSAGE['PAGES']['NOT_FOUND']);
}
$results_array = $results->fetchRow();
$old_admin_groups = explode(',', str_replace('_', '', $results_array['admin_groups']));
$old_admin_users = explode(',', str_replace('_', '', $results_array['admin_users']));
if(!is_numeric(array_search($admin->get_group_id(), $old_admin_groups)) AND !is_numeric(array_search($admin->get_user_id(), $old_admin_users))) {
	$admin->print_error($MESSAGE['PAGES']['INSUFFICIENT_PERMISSIONS']);
}

$visibility = $results_array['visibility'];

if(PAGE_TRASH) {
	if($visibility == 'deleted') {	
		// Function to change all child pages visibility to deleted
		function restore_subs($parent = 0) {
			global $database;
			// Query pages
			$query_menu = $database->query("SELECT page_id FROM ".TABLE_PREFIX."pages WHERE parent = '$parent' ORDER BY position ASC");
			// Check if there are any pages to show
			if($query_menu->numRows() > 0) {
				// Loop through pages
				while($page = $query_menu->fetchRow()) {
					// Update the page visibility to 'deleted'
					$database->query("UPDATE ".TABLE_PREFIX."pages SET visibility = 'public' WHERE page_id = '".$page['page_id']."' LIMIT 1");
					// Run this function again for all sub-pages
					restore_subs($page['page_id']);
				}
			}
		}
		
		// Update the page visibility to 'deleted'
		$database->query("UPDATE ".TABLE_PREFIX."pages SET visibility = 'public' WHERE page_id = '$page_id.' LIMIT 1");
		
		// Run trash subs for this page
		restore_subs($page_id);
		
	}
}

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error());
} else {
	$admin->print_success($MESSAGE['PAGES']['RESTORED']);
}

// Print admin footer
$admin->print_footer();

?>