<?php

// $Id: class.admin.php 239 2005-11-22 11:50:41Z stefan $

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

/*

Admin class

This class will be used for every program that will be included
in the administration section of Website Baker.

*/

if(!defined('WB_URL')) {
	header('Location: ../index.php');
}

require_once(WB_PATH.'/framework/class.wb.php');

// Include PHPLIB template class
require_once(WB_PATH."/include/phplib/template.inc");


// Get WB version
require_once(ADMIN_PATH.'/interface/version.php');

/*
Begin user changeable settings
*/


class admin extends wb {
	// Authenticate user then auto print the header
	function admin($section_name, $section_permission = 'start', $auto_header = true, $auto_auth = true) {
		$this->wb();
		global $MESSAGE;
		// Specify the current applications name
		$this->section_name = $section_name;
		$this->section_permission = $section_permission;
		// Authenticate the user for this application
		if($auto_auth == true) {
			// First check if the user is logged-in
			if($this->is_authenticated() == false) {
				header('Location: '.ADMIN_URL.'/login/index.php');
			}
			// Now check if they are allowed in this section
			if($this->get_permission($section_permission) == false) {
				die($MESSAGE['ADMIN']['INSUFFICIENT_PRIVELLIGES']);
			}
		}
		// Auto header code
		if($auto_header == true) {
			$this->print_header();
		}
	}
	
	// Print the admin header
	function print_header($body_tags = '') {
		// Get vars from the language file
		global $MENU;
		global $MESSAGE;
		global $TEXT;
		// Connect to database and get website title
		global $database;
		$get_title = $database->query("SELECT value FROM ".TABLE_PREFIX."settings WHERE name = 'title'");
		$title = $get_title->fetchRow();
		$header_template = new Template(ADMIN_PATH."/interface");
		$header_template->set_file('page', 'header.html');
		$header_template->set_block('page', 'header_block', 'header');
		$header_template->set_var(	array(
													'SECTION_NAME' => $MENU[strtoupper($this->section_name)],
													'INTERFACE_DIR' => ADMIN_URL.'/interface',
													'BODY_TAGS' => $body_tags,
													'WEBSITE_TITLE' => ($title['value']),
													'TEXT_ADMINISTRATION' => $TEXT['ADMINISTRATION'],
													'VERSION' => VERSION
													)
											);
		// Create the menu
		$menu = array(
					array(ADMIN_URL.'/start/index.php', '', $MENU['START'], 'start', 0),
					array(ADMIN_URL.'/pages/index.php', '', $MENU['PAGES'], 'pages', 1),
					array(ADMIN_URL.'/media/index.php', '', $MENU['MEDIA'], 'media', 1),
					array(ADMIN_URL.'/addons/index.php', '', $MENU['ADDONS'], 'addons', 1),
					array(ADMIN_URL.'/preferences/index.php', '', $MENU['PREFERENCES'], 'preferences', 0),
					array(ADMIN_URL.'/settings/index.php', '', $MENU['SETTINGS'], 'settings', 1),
					array(ADMIN_URL.'/access/index.php', '', $MENU['ACCESS'], 'access', 1),
					array('http://www.websitebaker.org/2/help/', '_blank', $MENU['HELP'], 'help', 0),
					array(WB_URL.'/', '_blank', $MENU['VIEW'], 'view', 0),
					array(ADMIN_URL.'/logout/index.php', '', $MENU['LOGOUT'], 'logout', 0)
					);
		$header_template->set_block('header_block', 'linkBlock', 'link');
		foreach($menu AS $menu_item) {
			$link = $menu_item[0];
			$target = $menu_item[1];
			$title = $menu_item[2];
			$permission_title = $menu_item[3];
			$required = $menu_item[4];
			$replace_old = array(ADMIN_URL, WB_URL, '/', 'index.php');
			if($required == false OR $this->get_link_permission($permission_title)) {
				$header_template->set_var('LINK', $link);
				$header_template->set_var('TARGET', $target);
				// If link is the current section apply a class name
				if($permission_title == strtolower($this->section_name)) {
					$header_template->set_var('CLASS', 'current');
				} else {
					$header_template->set_var('CLASS', '');
				}
				$header_template->set_var('TITLE', $title);
				// Print link
				$header_template->parse('link', 'linkBlock', true);
			}
		}
		$header_template->parse('header', 'header_block', false);
		$header_template->pparse('output', 'page');
	}
	
	// Print the admin footer
	function print_footer() {
		$footer_template = new Template(ADMIN_PATH."/interface");
		$footer_template->set_file('page', 'footer.html');
		$footer_template->set_block('page', 'footer_block', 'header');
		$footer_template->parse('header', 'footer_block', false);
		$footer_template->pparse('output', 'page');
	}
	
	// Print a success message which then automatically redirects the user to another page
	function print_success($message, $redirect = 'index.php') {
		global $TEXT;
		$success_template = new Template(ADMIN_PATH.'/interface');
		$success_template->set_file('page', 'success.html');
		$success_template->set_block('page', 'main_block', 'main');
		$success_template->set_var('MESSAGE', $message);
		$success_template->set_var('REDIRECT', $redirect);
		$success_template->set_var('NEXT', $TEXT['NEXT']);
		$success_template->parse('main', 'main_block', false);
		$success_template->pparse('output', 'page');
	}
	
	// Print a error message
	function print_error($message, $link = 'index.php', $auto_footer = true) {
		global $TEXT;
		$success_template = new Template(ADMIN_PATH.'/interface');
		$success_template->set_file('page', 'error.html');
		$success_template->set_block('page', 'main_block', 'main');
		$success_template->set_var('MESSAGE', $message);
		$success_template->set_var('LINK', $link);
		$success_template->set_var('BACK', $TEXT['BACK']);
		$success_template->parse('main', 'main_block', false);
		$success_template->pparse('output', 'page');
		if($auto_footer == true) {
			$this->print_footer();
		}
		exit();
	}

	// Return a system permission
	function get_permission($name, $type = 'system') {
		// Append to permission type
		$type .= '_permissions';
		// Check if we have a section to check for
		if($name == 'start') {
			return true;
		} else {
			// Set system permissions var
			$system_permissions = $this->get_session('SYSTEM_PERMISSIONS');
			// Set module permissions var
			$module_permissions = $this->get_session('MODULE_PERMISSIONS');
			// Set template permissions var
			$template_permissions = $this->get_session('TEMPLATE_PERMISSIONS');
			// Return true if system perm = 1
			if(is_numeric(array_search($name, $$type))) {
				if($type == 'system_permissions') {
					return true;
				} else {
					return false;
				}
			} else {
				if($type == 'system_permissions') {
					return false;
				} else {
					return true;
				}
			}
		}
	}

	// Returns a system permission for a menu link
	function get_link_permission($title) {
		$title = str_replace('_blank', '', $title);
		$title = strtolower($title);
		// Set system permissions var
		$system_permissions = $this->get_session('SYSTEM_PERMISSIONS');
		// Set module permissions var
		$module_permissions = $this->get_session('MODULE_PERMISSIONS');
		if($title == 'start') {
			return true;
		} else {
			// Return true if system perm = 1
			if(is_numeric(array_search($title, $system_permissions))) {
				return true;
			} else {
				return false;
			}
		}
	}
}

?>