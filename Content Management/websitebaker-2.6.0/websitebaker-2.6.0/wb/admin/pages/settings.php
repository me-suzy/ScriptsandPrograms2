<?php

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
$admin = new admin('Pages', 'pages_settings');

// Get perms
$database = new database();
$results = $database->query("SELECT * FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'");
$results_array = $results->fetchRow();
$old_admin_groups = explode(',', $results_array['admin_groups']);
$old_admin_users = explode(',', $results_array['admin_users']);
if(!is_numeric(array_search($admin->get_group_id(), $old_admin_groups)) AND !is_numeric(array_search($admin->get_user_id(), $old_admin_users))) {
	$admin->print_error($MESSAGE['PAGES']['INSUFFICIENT_PERMISSIONS']);
}

// Get page details
$database = new database();
$query = "SELECT * FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'";
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

// Setup template object, parse vars to it, then parse it
$template = new Template(ADMIN_PATH.'/pages');
$template->set_file('page', 'settings.html');
$template->set_block('page', 'main_block', 'main');
$template->set_var(array(
								'PAGE_ID' => $results_array['page_id'],
								'PAGE_TITLE' => ($results_array['page_title']),
								'MENU_TITLE' => ($results_array['menu_title']),
								'DESCRIPTION' => ($results_array['description']),
								'KEYWORDS' => ($results_array['keywords']),
								'MODIFIED_BY' => $user['display_name'],
								'MODIFIED_BY_USERNAME' => $user['username'],
								'MODIFIED_WHEN' => $modified_ts,
								'ADMIN_URL' => ADMIN_URL
								)
						);

// Work-out if we should show the "manage sections" link
$query_sections = $database->query("SELECT section_id FROM ".TABLE_PREFIX."sections WHERE page_id = '$page_id' AND module = 'menu_link'");
if($query_sections->numRows() > 0) {
	$template->set_var('DISPLAY_MANAGE_SECTIONS', 'none');
} elseif(MANAGE_SECTIONS == 'enabled') {
	$template->set_var('TEXT_MANAGE_SECTIONS', $HEADING['MANAGE_SECTIONS']);
} else {
	$template->set_var('DISPLAY_MANAGE_SECTIONS', 'none');
}

// Visibility
if($results_array['visibility'] == 'public') {
	$template->set_var('PUBLIC_SELECTED', ' selected');
} elseif($results_array['visibility'] == 'private') {
	$template->set_var('PRIVATE_SELECTED', ' selected');
} elseif($results_array['visibility'] == 'registered') {
	$template->set_var('REGISTERED_SELECTED', ' selected');
} elseif($results_array['visibility'] == 'hidden') {
	$template->set_var('HIDDEN_SELECTED', ' selected');
} elseif($results_array['visibility'] == 'none') {
	$template->set_var('NO_VIS_SELECTED', ' selected');
}
// Group list 1 (admin_groups)
	$admin_groups = explode(',', str_replace('_', '', $results_array['admin_groups']));
	if($admin->get_group_id() == 1) {
		$query = "SELECT * FROM ".TABLE_PREFIX."groups";
	} else {
		$query = "SELECT * FROM ".TABLE_PREFIX."groups WHERE group_id != '".$admin->get_group_id()."'";
	}
	$get_groups = $database->query($query);
	$template->set_block('main_block', 'group_list_block', 'group_list');
	// Insert admin group and current group first
	$admin_group_name = $get_groups->fetchRow();
	$template->set_var(array(
									'ID' => 1,
									'TOGGLE' => '',
									'DISABLED' => ' disabled',
									'LINK_COLOR' => '000000',
									'CURSOR' => 'default',
									'NAME' => $admin_group_name['name'],
									'CHECKED' => ' checked'
									)
							);
	$template->parse('group_list', 'group_list_block', true);
	if($admin->get_group_id() != 1) {
		$template->set_var(array(
										'ID' => $admin->get_group_id(),
										'TOGGLE' => '',
										'DISABLED' => ' disabled',
										'LINK_COLOR' => '000000',
										'CURSOR' => 'default',
										'NAME' => $admin->get_group_name(),
										'CHECKED' => ' checked'
										)
								);
		$template->parse('group_list', 'group_list_block', true);
	}
	while($group = $get_groups->fetchRow()) {
		// Check if the group is allowed to edit pages
		$system_permissions = explode(',', $group['system_permissions']);
		if(is_numeric(array_search('pages_modify', $system_permissions))) {
			$template->set_var(array(
											'ID' => $group['group_id'],
											'TOGGLE' => $group['group_id'],
											'DISABLED' => '',
											'LINK_COLOR' => '',
											'CURSOR' => 'pointer',
											'NAME' => $group['name'],
											'CHECKED' => ''
											)
									);
			if(is_numeric(array_search($group['group_id'], $admin_groups))) {
				$template->set_var('CHECKED', 'checked');
			} else {
				$template->set_var('CHECKED', '');
			}
			$template->parse('group_list', 'group_list_block', true);
		}
	}
// Group list 2 (viewing_groups)
	$viewing_groups = explode(',', str_replace('_', '', $results_array['viewing_groups']));
	if($admin->get_group_id() == 1) {
		$query = "SELECT * FROM ".TABLE_PREFIX."groups";
	} else {
		$query = "SELECT * FROM ".TABLE_PREFIX."groups WHERE group_id != '".$admin->get_group_id()."'";
	}
	$get_groups = $database->query($query);
	$template->set_block('main_block', 'group_list_block2', 'group_list2');
	// Insert admin group and current group first
	$admin_group_name = $get_groups->fetchRow();
	$template->set_var(array(
									'ID' => 1,
									'TOGGLE' => '',
									'DISABLED' => ' disabled',
									'LINK_COLOR' => '000000',
									'CURSOR' => 'default',
									'NAME' => $admin_group_name['name'],
									'CHECKED' => ' checked'
									)
							);
	$template->parse('group_list2', 'group_list_block2', true);
	if($admin->get_group_id() != 1) {
		$template->set_var(array(
										'ID' => $admin->get_group_id(),
										'TOGGLE' => '',
										'DISABLED' => ' disabled',
										'LINK_COLOR' => '000000',
										'CURSOR' => 'default',
										'NAME' => $admin->get_group_name(),
										'CHECKED' => ' checked'
										)
								);
		$template->parse('group_list2', 'group_list_block2', true);
	}
	while($group = $get_groups->fetchRow()) {
		$template->set_var(array(
										'ID' => $group['group_id'],
										'TOGGLE' => $group['group_id'],
										'DISABLED' => '',
										'LINK_COLOR' => '',
										'CURSOR' => 'pointer',
										'NAME' => $group['name'],
										)
								);
		if(is_numeric(array_search($group['group_id'], $viewing_groups))) {
			$template->set_var('CHECKED', 'checked');
		} else {
			$template->set_var('CHECKED', '');
		}
		$template->parse('group_list2', 'group_list_block2', true);
	}
// Show private viewers
if($results_array['visibility'] == 'private') {
	$template->set_var('DISPLAY_PRIVATE', '');
} else {
	$template->set_var('DISPLAY_PRIVATE', 'none');
}
// Parent page list
$database = new database();
function parent_list($parent) {
	global $admin, $database, $template, $results_array;
	$query = "SELECT * FROM ".TABLE_PREFIX."pages WHERE parent = '$parent' ORDER BY position ASC";
	$get_pages = $database->query($query);
	while($page = $get_pages->fetchRow()) {
		// If the current page cannot be parent, then its children neither
		$list_next_level = true;
		// Stop users from adding pages with a level of more than the set page level limit
		if($page['level']+1 < PAGE_LEVEL_LIMIT) {
			// Get user perms
			$admin_groups = explode(',', str_replace('_', '', $page['admin_groups']));
			$admin_users = explode(',', str_replace('_', '', $page['admin_users']));
			if(is_numeric(array_search($admin->get_group_id(), $admin_groups)) OR is_numeric(array_search($admin->get_user_id(), $admin_users))) {
				$can_modify = true;
			} else {
				$can_modify = false;
			}
			// Title -'s prefix
			$title_prefix = '';
			for($i = 1; $i <= $page['level']; $i++) { $title_prefix .= ' - '; }
			$template->set_var(array(
											'ID' => $page['page_id'],
											'TITLE' => ($title_prefix.$page['page_title'])
											)
									);
			if($results_array['parent'] == $page['page_id']) {
				$template->set_var('SELECTED', ' selected');
			} elseif($results_array['page_id'] == $page['page_id']) {
				$template->set_var('SELECTED', ' disabled');
				$list_next_level=false;
			} elseif($can_modify != true) {
				$template->set_var('SELECTED', ' disabled');
			} else {
				$template->set_var('SELECTED', '');
			}
			$template->parse('page_list2', 'page_list_block2', true);
		}
		if ($list_next_level)
			parent_list($page['page_id']);
	}
}
$template->set_block('main_block', 'page_list_block2', 'page_list2');
if($admin->get_permission('pages_add_l0') == true OR $results_array['level'] == 0) {
	if($results_array['parent'] == 0) { $selected = ' selected'; } else { $selected = ''; }
	$template->set_var(array(
									'ID' => '0',
									'TITLE' => $TEXT['NONE'],
									'SELECTED' => $selected
									)
							);
	$template->parse('page_list2', 'page_list_block2', true);
}
parent_list(0);

if($modified_ts == 'Unknown') {
	$template->set_var('DISPLAY_MODIFIED', 'hide');
} else {
	$template->set_var('DISPLAY_MODIFIED', '');
}
// Templates list
$template->set_block('main_block', 'template_list_block', 'template_list');
$result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'template'");
if($result->numRows() > 0) {
	while($addon = $result->fetchRow()) { 
		// Check if the user has perms to use this template
		if($addon['directory'] == $results_array['template'] OR $admin->get_permission($addon['directory'], 'template') == true) {
			$template->set_var('VALUE', $addon['directory']);
			$template->set_var('NAME', $addon['name']);
			if($addon['directory'] == $results_array['template']) {
				$template->set_var('SELECTED', ' selected');
			} else {
				$template->set_var('SELECTED', '');
			}
			$template->parse('template_list', 'template_list_block', true);
		}
	}
}

// Menu list
if(MULTIPLE_MENUS == false) {
	$template->set_var('DISPLAY_MENU_LIST', 'none');
}
// Include template info file (if it exists)
if($results_array['template'] != '') {
	$template_location = WB_PATH.'/templates/'.$results_array['template'].'/info.php';
} else {
	$template_location = WB_PATH.'/templates/'.DEFAULT_TEMPLATE.'/info.php';
}
if(file_exists($template_location)) {
	require($template_location);
}
// Check if $menu is set
if(!isset($menu[1]) OR $menu[1] == '') {
	// Make our own menu list
	$menu[1] = $TEXT['MAIN'];
}
// Add menu options to the list
$template->set_block('main_block', 'menu_list_block', 'menu_list');
foreach($menu AS $number => $name) {
	$template->set_var('NAME', $name);
	$template->set_var('VALUE', $number);
	if($results_array['menu'] == $number) {
		$template->set_var('SELECTED', 'selected');
	} else {
		$template->set_var('SELECTED', '');
	}
	$template->parse('menu_list', 'menu_list_block', true);
}

// Language list
if($handle = opendir(WB_PATH.'/languages/')) {
	$template->set_block('main_block', 'language_list_block', 'language_list');
	while (false !== ($file = readdir($handle))) {
		if($file != '.' AND $file != '..' AND $file != '.svn' AND $file != 'index.php') {
			// Include the languages info file
			require(WB_PATH.'/languages/'.$file);
			// Work-out if this language is selected
			if($language_code == $results_array['language']) { $selected = ' selected'; } else { $selected = ''; }
			// Set the language info
			$template->set_var(array('VALUE' => $language_code, 'SELECTED' => $selected, 'NAME' => $language_name));
			// Parse row
			$template->parse('language_list', 'language_list_block', true);
		}
	}
}
// Restore to original language
require(WB_PATH.'/languages/'.LANGUAGE.'.php');

// Select disabled if searching is disabled
if($results_array['searching'] == 0) {
	$template->set_var('SEARCHING_DISABLED', ' selected');
}
// Select what the page target is
if($results_array['target'] == '_top') {
	$template->set_var('TOP_SELECTED', ' selected');
} elseif($results_array['target'] == '_blank') {
	$template->set_var('BLANK_SELECTED', ' selected');
}

// Insert language text
$template->set_var(array(
								'HEADING_MODIFY_PAGE_SETTINGS' => $HEADING['MODIFY_PAGE_SETTINGS'],
								'TEXT_CURRENT_PAGE' => $TEXT['CURRENT_PAGE'],
								'TEXT_MODIFY' => $TEXT['MODIFY'],
								'TEXT_MODIFY_PAGE' => $HEADING['MODIFY_PAGE'],
								'LAST_MODIFIED' => $MESSAGE['PAGES']['LAST_MODIFIED'],
								'TEXT_PAGE_TITLE' => $TEXT['PAGE_TITLE'],
								'TEXT_MENU_TITLE' => $TEXT['MENU_TITLE'],
								'TEXT_TYPE' => $TEXT['TYPE'],
								'TEXT_MENU' => $TEXT['MENU'],
								'TEXT_PARENT' => $TEXT['PARENT'],
								'TEXT_VISIBILITY' => $TEXT['VISIBILITY'],
								'TEXT_PUBLIC' => $TEXT['PUBLIC'],
								'TEXT_PRIVATE' => $TEXT['PRIVATE'],
								'TEXT_REGISTERED' => $TEXT['REGISTERED'],
								'TEXT_NONE' => $TEXT['NONE'],
								'TEXT_HIDDEN' => $TEXT['HIDDEN'],
								'TEXT_TEMPLATE' => $TEXT['TEMPLATE'],
								'TEXT_TARGET' => $TEXT['TARGET'],
								'TEXT_SYSTEM_DEFAULT' => $TEXT['SYSTEM_DEFAULT'],
								'TEXT_PLEASE_SELECT' => $TEXT['PLEASE_SELECT'],
								'TEXT_NEW_WINDOW' => $TEXT['NEW_WINDOW'],
								'TEXT_SAME_WINDOW' => $TEXT['SAME_WINDOW'],
								'TEXT_ADMINISTRATORS' => $TEXT['ADMINISTRATORS'],
								'TEXT_PRIVATE_VIEWERS' => $TEXT['PRIVATE_VIEWERS'],
								'TEXT_REGISTERED_VIEWERS' => $TEXT['REGISTERED_VIEWERS'],
								'TEXT_DESCRIPTION' => $TEXT['DESCRIPTION'],
								'TEXT_KEYWORDS' => $TEXT['KEYWORDS'],
								'TEXT_SEARCHING' => $TEXT['SEARCHING'],
								'TEXT_LANGUAGE' => $TEXT['LANGUAGE'],
								'TEXT_ENABLED' => $TEXT['ENABLED'],
								'TEXT_DISABLED' => $TEXT['DISABLED'],
								'TEXT_SAVE' => $TEXT['SAVE'],
								'TEXT_RESET' => $TEXT['RESET'],
								'LAST_MODIFIED' => $MESSAGE['PAGES']['LAST_MODIFIED'],
								'HEADING_MODIFY_PAGE' => $HEADING['MODIFY_PAGE']
								)
						);

$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Print admin footer
$admin->print_footer();

?>