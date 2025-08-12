<?php

// $Id: groups.php 239 2005-11-22 11:50:41Z stefan $

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

// Include config file and admin class file
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');

// Create new database object
$database = new database();

if(!isset($_POST['action']) OR $_POST['action'] != "modify" AND $_POST['action'] != "delete") {
	header("Location: index.php");
}

// Check if group group_id is a valid number and doesnt equal 1
if(!isset($_POST['group_id']) OR !is_numeric($_POST['group_id']) OR $_POST['group_id'] == 1) {
	header("Location: index.php");
}

if($_POST['action'] == 'modify') {
	// Create new admin object
	$admin = new admin('Access', 'groups_modify', false);
	// Print header
	$admin->print_header();
	// Get existing values
	$results = $database->query("SELECT * FROM ".TABLE_PREFIX."groups WHERE group_id = '".$_POST['group_id']."'");
	$group = $results->fetchRow();
	// Setup template object
	$template = new Template(ADMIN_PATH.'/groups');
	$template->set_file('page', 'group_form.html');
	$template->set_block('page', 'main_block', 'main');
	$template->set_var(	array(
										'ACTION_URL' => ADMIN_URL.'/groups/save.php',
										'SUBMIT_TITLE' => $TEXT['SAVE'],
										'GROUP_ID' => $group['group_id'],
										'GROUP_NAME' => $group['name'],
										'ADVANCED_ACTION' => 'groups.php'
										)
							);
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
	
	// Explode system permissions
	$system_permissions = explode(',', $group['system_permissions']);
	// Check system permissions boxes
	foreach($system_permissions AS $name) {
			$template->set_var($name.'_checked', 'checked');
	}
	// Explode module permissions
	$module_permissions = explode(',', $group['module_permissions']);
	// Explode template permissions
	$template_permissions = explode(',', $group['template_permissions']);
	
	// Insert values into module list
	$template->set_block('main_block', 'module_list_block', 'module_list');
	$result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'module' AND function = 'page'");
	if($result->numRows() > 0) {
		while($addon = $result->fetchRow()) {
			$template->set_var('VALUE', $addon['directory']);
			$template->set_var('NAME', $addon['name']);
			if(!is_numeric(array_search($addon['directory'], $module_permissions))) {
				$template->set_var('CHECKED', 'checked');
			} else {
				$template->set_var('CHECKED', '');
			}
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
			if(!is_numeric(array_search($addon['directory'], $template_permissions))) {
				$template->set_var('CHECKED', 'checked');
			} else {
				$template->set_var('CHECKED', '');
			}
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
									'SECTION_LANGUAGES' => $MENU['LANGUAGES'],
									'SECTION_SETTINGS' => $MENU['SETTINGS'],
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
									'CHANGING_PASSWORD' => $MESSAGE['USERS']['CHANGING_PASSWORD']
									)
							);
	
	// Parse template object
	$template->parse('main', 'main_block', false);
	$template->pparse('output', 'page');
} elseif($_POST['action'] == 'delete') {
	// Create new admin object
	$admin = new admin('Access', 'groups_delete', false);
	// Print header
	$admin->print_header();
	// Delete the group
	$database->query("DELETE FROM ".TABLE_PREFIX."groups WHERE group_id = '".$_POST['group_id']."' LIMIT 1");
	if($database->is_error()) {
		$admin->print_error($database->get_error());
	} else {
		// Delete users in the group
		$database->query("DELETE FROM ".TABLE_PREFIX."users WHERE group_id = '".$_POST['group_id']."'");
		if($database->is_error()) {
			$admin->print_error($database->get_error());
		} else {
			$admin->print_success($MESSAGE['GROUPS']['DELETED']);
		}
	}
}

// Print admin footer
$admin->print_footer();

?>