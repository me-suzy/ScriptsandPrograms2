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
$admin = new admin('Addons', 'languages');

// Setup template object
$template = new Template(ADMIN_PATH.'/languages');
$template->set_file('page', 'template.html');
$template->set_block('page', 'main_block', 'main');

// Insert values into language list
$template->set_block('main_block', 'language_list_block', 'language_list');
$result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'language'");
if($result->numRows() > 0) {
	while($addon = $result->fetchRow()) {
		$template->set_var('VALUE', $addon['directory']);
		$template->set_var('NAME', $addon['name'].' ('.$addon['directory'].')');
		$template->parse('language_list', 'language_list_block', true);
	}
}

// Insert permissions values
if($admin->get_permission('languages_install') != true) {
	$template->set_var('DISPLAY_INSTALL', 'hide');
}
if($admin->get_permission('languages_uninstall') != true) {
	$template->set_var('DISPLAY_UNINSTALL', 'hide');
}
if($admin->get_permission('languages_view') != true) {
	$template->set_var('DISPLAY_LIST', 'hide');
}

// Insert language headings
$template->set_var(array(
								'HEADING_INSTALL_LANGUAGE' => $HEADING['INSTALL_LANGUAGE'],
								'HEADING_UNINSTALL_LANGUAGE' => $HEADING['UNINSTALL_LANGUAGE'],
								'HEADING_LANGUAGE_DETAILS' => $HEADING['LANGUAGE_DETAILS']
								)
						);
// Insert language text and messages
$template->set_var(array(
								'TEXT_INSTALL' => $TEXT['INSTALL'],
								'TEXT_UNINSTALL' => $TEXT['UNINSTALL'],
								'TEXT_VIEW_DETAILS' => $TEXT['VIEW_DETAILS'],
								'TEXT_PLEASE_SELECT' => $TEXT['PLEASE_SELECT']
								)
						);

// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Print admin footer
$admin->print_footer();

?>