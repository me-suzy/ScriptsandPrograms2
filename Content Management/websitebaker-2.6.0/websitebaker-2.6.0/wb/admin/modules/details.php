<?php

// $Id: details.php 223 2005-11-19 20:40:44Z stefan $

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

// Include the config file
require('../../config.php');

// Get module name
if(!isset($_POST['file']) OR $_POST['file'] == "") {
	header("Location: index.php");
} else {
	$file = $_POST['file'];
}

// Check if the module exists
if(!file_exists(WB_PATH.'/modules/'.$file)) {
	header("Location: index.php");
}

// Print admin header
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Addons', 'modules_view');

// Setup module object
$template = new Template(ADMIN_PATH.'/modules');
$template->set_file('page', 'details.html');
$template->set_block('page', 'main_block', 'main');

// Insert values
$result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'module' AND directory = '$file'");
if($result->numRows() > 0) {
	$module = $result->fetchRow();
}

$template->set_var(array(
								'NAME' => $module['name'],
								'AUTHOR' => $module['author'],
								'DESCRIPTION' => $module['description'],
								'VERSION' => $module['version'],
								'DESIGNED_FOR' => $module['platform']
								)
						);
						
switch ($module['function']) {
	case NULL:
		$type_name = $TEXT['UNKNOWN'];
	break;
	case 'page':
		$type_name = $TEXT['PAGE'];
	break;
	case 'wysiwyg':
		$type_name = $TEXT['WYSIWYG_EDITOR'];
	break;
	case 'tool':
		$type_name = $TEXT['ADMINISTRATION_TOOL'];
	break;
	case 'admin':
		$type_name = $TEXT['ADMIN'];
	break;
	case 'administration':
		$type_name = $TEXT['ADMINISTRATION'];
	break;
	$type_name = $TEXT['unknown'];
}
$template->set_var('TYPE', $type_name);

// Insert language headings
$template->set_var(array(
								'HEADING_MODULE_DETAILS' => $HEADING['MODULE_DETAILS']
								)
						);
// Insert language text and messages
$template->set_var(array(
								'TEXT_NAME' => $TEXT['NAME'],
								'TEXT_TYPE' => $TEXT['TYPE'],
								'TEXT_AUTHOR' => $TEXT['AUTHOR'],
								'TEXT_VERSION' => $TEXT['VERSION'],
								'TEXT_DESIGNED_FOR' => $TEXT['DESIGNED_FOR'],
								'TEXT_DESCRIPTION' => $TEXT['DESCRIPTION']
								)
						);

// Parse module object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Print admin footer
$admin->print_footer();

?>