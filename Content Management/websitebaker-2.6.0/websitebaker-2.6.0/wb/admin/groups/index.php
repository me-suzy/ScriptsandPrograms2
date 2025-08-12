<?php

// $Id: index.php 239 2005-11-22 11:50:41Z stefan $

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

// Print admin header
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Access', 'groups');

// Create new template object for the modify/remove menu
$template = new Template(ADMIN_PATH.'/groups');
$template->set_file('page', 'template.html');
$template->set_block('page', 'main_block', 'main');
$template->set_var('ADMIN_URL', ADMIN_URL);

// Get existing value from database
$database = new database();
$query = "SELECT group_id,name FROM ".TABLE_PREFIX."groups WHERE group_id != '1'";
$results = $database->query($query);
if($database->is_error()) {
	$admin->print_error($database->get_error(), 'index.php');
}

// Insert values into the modify/remove menu
$template->set_block('main_block', 'list_block', 'list');
if($results->numRows() > 0) {
	// Insert first value to say please select
	$template->set_var('VALUE', '');
	$template->set_var('NAME', $TEXT['PLEASE_SELECT'].'...');
	$template->parse('list', 'list_block', true);
	// Loop through groups
	while($group = $results->fetchRow()) {
		$template->set_var('VALUE', $group['group_id']);
		$template->set_var('NAME', $group['name']);
		$template->parse('list', 'list_block', true);
	}
} else {
	// Insert single value to say no groups were found
	$template->set_var('NAME', $TEXT['NONE_FOUND']);
	$template->parse('list', 'list_block', true);
}

// Insert permissions values
if($admin->get_permission('groups_add') != true) {
	$template->set_var('DISPLAY_ADD', 'hide');
}
if($admin->get_permission('groups_modify') != true) {
	$template->set_var('DISPLAY_MODIFY', 'hide');
}
if($admin->get_permission('groups_delete') != true) {
	$template->set_var('DISPLAY_DELETE', 'hide');
}

// Insert language headings
$template->set_var(array(
								'HEADING_MODIFY_DELETE_GROUP' => $HEADING['MODIFY_DELETE_GROUP'],
								'HEADING_ADD_GROUP' => $HEADING['ADD_GROUP']
								)
						);
// Insert language text and messages
$template->set_var(array(
								'TEXT_MODIFY' => $TEXT['MODIFY'],
								'TEXT_DELETE' => $TEXT['DELETE'],
								'TEXT_MANAGE_USERS' => $TEXT['MANAGE_USERS'],
								'CONFIRM_DELETE' => $MESSAGE['GROUPS']['CONFIRM_DELETE']
								)
						);

// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Setup template for add group form
$template = new Template(ADMIN_PATH.'/groups');
$template->set_file('page', 'group_form.html');
$template->set_block('page', 'main_block', 'main');
$template->set_var('DISPLAY_EXTRA', 'none');
$template->set_var('ACTION_URL', ADMIN_URL.'/groups/add.php');
$template->set_var('SUBMIT_TITLE', $TEXT['ADD']);
$template->set_var('ADVANCED_ACTION', 'index.php');

// Tell the browser whether or not to show advanced options
if(isset($_POST['advanced']) AND $_POST['advanced'] == $TEXT['SHOW_ADVANCED'].' >>') {
	$template->set_var('DISPLAY_ADVANCED', '');
	$template->set_var('DISPLAY_BASIC', 'none');
	$template->set_var('ADVANCED', 'yes');
	$template->set_var('ADVANCED_BUTTON', '<< '.$TEXT['HIDE_ADVANCED']);
} else {
	$template->set_var('DISPLAY_ADVANCED', 'none');
	$template->set_var('DISPLAY_BASIC', '');
	$template->set_var('ADVANCED', 'no');
	$template->set_var('ADVANCED_BUTTON', $TEXT['SHOW_ADVANCED'].' >>');
}

// Insert permissions values
if($admin->get_permission('groups_add') != true) {
	$template->set_var('DISPLAY_ADD', 'hide');
}

// Insert values into module list
$template->set_block('main_block', 'module_list_block', 'module_list');
$result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'module' AND function = 'page'");
if($result->numRows() > 0) {
	while($addon = $result->fetchRow()) {
		$template->set_var('VALUE', $addon['directory']);
		$template->set_var('NAME', $addon['name']);
		$template->parse('module_list', 'module_list_block', true);
	}
}

// Insert values into template list
$template->set_block('main_block', 'template_list_block', 'template_list');
$result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'template'");
if($result->numRows() > 0) {
	while($addon = $result->fetchRow()) {
		$template->set_var('VALUE', $addon['directory']);
		$template->set_var('NAME', $addon['name']);
		$template->parse('template_list', 'template_list_block', true);
	}
}

// Insert language text and messages
$template->set_var(array(
								'TEXT_RESET' => $TEXT['RESET'],
								'TEXT_ACTIVE' => $TEXT['ACTIVE'],
								'TEXT_DISABLED' => $TEXT['DISABLED'],
								'TEXT_PLEASE_SELECT' => $TEXT['PLEASE_SELECT'],
								'TEXT_USERNAME' => $TEXT['USERNAME'],
								'TEXT_PASSWORD' => $TEXT['PASSWORD'],
								'TEXT_RETYPE_PASSWORD' => $TEXT['RETYPE_PASSWORD'],
								'TEXT_DISPLAY_NAME' => $TEXT['DISPLAY_NAME'],
								'TEXT_EMAIL' => $TEXT['EMAIL'],
								'TEXT_GROUP' => $TEXT['GROUP'],
								'TEXT_SYSTEM_PERMISSIONS' => $TEXT['SYSTEM_PERMISSIONS'],
								'TEXT_MODULE_PERMISSIONS' => $TEXT['MODULE_PERMISSIONS'],
								'TEXT_TEMPLATE_PERMISSIONS' => $TEXT['TEMPLATE_PERMISSIONS'],
								'TEXT_NAME' => $TEXT['NAME'],
								'SECTION_PAGES' => $MENU['PAGES'],
								'SECTION_MEDIA' => $MENU['MEDIA'],
								'SECTION_MODULES' => $MENU['MODULES'],
								'SECTION_TEMPLATES' => $MENU['TEMPLATES'],
								'SECTION_SETTINGS' => $MENU['SETTINGS'],
								'SECTION_LANGUAGES' => $MENU['LANGUAGES'],
								'SECTION_USERS' => $MENU['USERS'],
								'SECTION_GROUPS' => $MENU['GROUPS'],
								'TEXT_VIEW' => $TEXT['VIEW'],
								'TEXT_ADD' => $TEXT['ADD'],
								'TEXT_LEVEL' => $TEXT['LEVEL'],
								'TEXT_MODIFY' => $TEXT['MODIFY'],
								'TEXT_DELETE' => $TEXT['DELETE'],
								'TEXT_MODIFY_CONTENT' => $TEXT['MODIFY_CONTENT'],
								'TEXT_MODIFY_SETTINGS' => $TEXT['MODIFY_SETTINGS'],
								'HEADING_MODIFY_INTRO_PAGE' => $HEADING['MODIFY_INTRO_PAGE'],
								'TEXT_CREATE_FOLDER' => $TEXT['CREATE_FOLDER'],
								'TEXT_RENAME' => $TEXT['RENAME'],
								'TEXT_UPLOAD_FILES' => $TEXT['UPLOAD_FILES'],
								'TEXT_BASIC' => $TEXT['BASIC'],
								'TEXT_ADVANCED' => $TEXT['ADVANCED'],
								'CHANGING_PASSWORD' => $MESSAGE['USERS']['CHANGING_PASSWORD'],
								'CHECKED' => 'checked'
								)
						);

// Parse template for add group form
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Print the admin footer
$admin->print_footer();

?>