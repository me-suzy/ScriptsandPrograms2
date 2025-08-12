<?php

// $Id: details.php 10 2005-09-04 08:59:31Z ryan $

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

// Include the config code
require('../../config.php');

// Get language name
if(!isset($_POST['code']) OR $_POST['code'] == "") {
	header("Location: index.php");
} else {
	$code = $_POST['code'];
}

// Check if the language exists
if(!file_exists(WB_PATH.'/languages/'.$code.'.php')) {
	header("Location: index.php");
}

// Print admin header
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Addons', 'languages_view');

// Setup language object
$template = new Template(ADMIN_PATH.'/languages');
$template->set_file('page', 'details.html');
$template->set_block('page', 'main_block', 'main');

// Insert values
require(WB_PATH.'/languages/'.$code.'.php');
$template->set_var(array(
								'CODE' => $language_code,
								'NAME' => $language_name,
								'AUTHOR' => $language_author,
								'VERSION' => $language_version,
								'DESIGNED_FOR' => $language_designed_for
								)
						);

// Restore language to original code
require(WB_PATH.'/languages/'.LANGUAGE.'.php');

// Insert language headings
$template->set_var(array(
								'HEADING_LANGUAGE_DETAILS' => $HEADING['LANGUAGE_DETAILS']
								)
						);
// Insert language text and messages
$template->set_var(array(
								'TEXT_CODE' => $TEXT['CODE'],
								'TEXT_NAME' => $TEXT['NAME'],
								'TEXT_TYPE' => $TEXT['TYPE'],
								'TEXT_AUTHOR' => $TEXT['AUTHOR'],
								'TEXT_VERSION' => $TEXT['VERSION'],
								'TEXT_DESIGNED_FOR' => $TEXT['DESIGNED_FOR']
								)
						);

// Parse language object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Print admin footer
$admin->print_footer();

?>