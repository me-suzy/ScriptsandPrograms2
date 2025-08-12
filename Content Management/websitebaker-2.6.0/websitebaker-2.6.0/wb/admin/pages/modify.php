<?php

// $Id: modify.php 116 2005-09-16 21:20:22Z stefan $

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

// Create new admin object
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_modify');

// Get perms
$database = new database();
$results = $database->query("SELECT admin_groups,admin_users FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'");
$results_array = $results->fetchRow();
$old_admin_groups = explode(',', str_replace('_', '', $results_array['admin_groups']));
$old_admin_users = explode(',', str_replace('_', '', $results_array['admin_users']));
if(!is_numeric(array_search($admin->get_group_id(), $old_admin_groups)) AND !is_numeric(array_search($admin->get_user_id(), $old_admin_users))) {
	$admin->print_error($MESSAGE['PAGES']['INSUFFICIENT_PERMISSIONS']);
}

// Get page details
$database = new database();
$query = "SELECT page_id,page_title,modified_by,modified_when FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'";
$results = $database->query($query);
if($database->is_error()) {
	$admin->print_header();
	$admin->print_error($database->get_error());
}
if($results->numRows() == 0) {
	$admin->print_header();
	$admin->print_error($MESSAGE['PAGES']['NOT_FOUND']);
}
$results_array = $results->fetchRow();

// Get display name of person who last modified the page
$query_user = "SELECT username,display_name FROM ".TABLE_PREFIX."users WHERE user_id = '".$results_array['modified_by']."'";
$get_user = $database->query($query_user);
if($get_user->numRows() != 0) {
	$user = $get_user->fetchRow();
} else {
	$user['display_name'] = 'Unknown';
	$user['username'] = 'unknown';
}
// Convert the unix ts for modified_when to human a readable form
if($results_array['modified_when'] != 0) {
	$modified_ts = gmdate(TIME_FORMAT.', '.DATE_FORMAT, $results_array['modified_when']+TIMEZONE);
} else {
	$modified_ts = 'Unknown';
}

// Include page info script
$template = new Template(ADMIN_PATH.'/pages');
$template->set_file('page', 'modify.html');
$template->set_block('page', 'main_block', 'main');
$template->set_var(array(
								'PAGE_ID' => $results_array['page_id'],
								'PAGE_TITLE' => ($results_array['page_title']),
								'MODIFIED_BY' => $user['display_name'],
								'MODIFIED_BY_USERNAME' => $user['username'],
								'MODIFIED_WHEN' => $modified_ts,
								'ADMIN_URL' => ADMIN_URL
								)
						);
if($modified_ts == 'Unknown') {
	$template->set_var('DISPLAY_MODIFIED', 'hide');
} else {
	$template->set_var('DISPLAY_MODIFIED', '');
}

// Work-out if we should show the "manage sections" link
$query_sections = $database->query("SELECT section_id FROM ".TABLE_PREFIX."sections WHERE page_id = '$page_id' AND module = 'menu_link'");
if($query_sections->numRows() > 0) {
	$template->set_var('DISPLAY_MANAGE_SECTIONS', 'none');
} elseif(MANAGE_SECTIONS == 'enabled') {
	$template->set_var('TEXT_MANAGE_SECTIONS', $HEADING['MANAGE_SECTIONS']);
} else {
	$template->set_var('DISPLAY_MANAGE_SECTIONS', 'none');
}

// Insert language TEXT
$template->set_var(array(
								'TEXT_CURRENT_PAGE' => $TEXT['CURRENT_PAGE'],
								'TEXT_CHANGE_SETTINGS' => $TEXT['CHANGE_SETTINGS'],
								'LAST_MODIFIED' => $MESSAGE['PAGES']['LAST_MODIFIED'],
								'HEADING_MODIFY_PAGE' => $HEADING['MODIFY_PAGE']
								)
						);

// Parse and print header template
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Get sections for this page
$query_sections = $database->query("SELECT section_id,module FROM ".TABLE_PREFIX."sections WHERE page_id = '$page_id' ORDER BY position ASC");
if($query_sections->numRows() > 0) {
	while($section = $query_sections->fetchRow()) {
		$section_id = $section['section_id'];
		$module = $section['module'];
		// Include the modules editing script if it exists
		if(file_exists(WB_PATH.'/modules/'.$module.'/modify.php')) {
			echo '<a name="'.$section_id.'"></a>';
			require(WB_PATH.'/modules/'.$module.'/modify.php');
		}
	}
}

// Print admin footer
$admin->print_footer();

?>