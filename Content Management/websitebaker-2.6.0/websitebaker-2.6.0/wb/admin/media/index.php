<?php

// $Id: index.php 10 2005-09-04 08:59:31Z ryan $

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
$admin = new admin('Media', 'media');

// Setup template object
$template = new Template(ADMIN_PATH.'/media');
$template->set_file('page', 'template.html');
$template->set_block('page', 'main_block', 'main');

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Get home folder not to show
$home_folders = get_home_folders();

// Insert values
$template->set_block('main_block', 'dir_list_block', 'dir_list');
foreach(directory_list(WB_PATH.MEDIA_DIRECTORY) AS $name) {
	if(!isset($home_folders[str_replace(WB_PATH.MEDIA_DIRECTORY, '', $name)])) {
		$template->set_var('NAME', str_replace(WB_PATH, '', $name));
		$template->parse('dir_list', 'dir_list_block', true);
	}
}

// Insert permissions values
if($admin->get_permission('media_create') != true) {
	$template->set_var('DISPLAY_CREATE', 'hide');
}
if($admin->get_permission('media_upload') != true) {
	$template->set_var('DISPLAY_UPLOAD', 'hide');
}

// Insert language headings
$template->set_var(array(
								'HEADING_BROWSE_MEDIA' => $HEADING['BROWSE_MEDIA'],
								'HEADING_CREATE_FOLDER' => $HEADING['CREATE_FOLDER'],
								'HEADING_UPLOAD_FILES' => $HEADING['UPLOAD_FILES']
								)
						);
// Insert language text and messages
$template->set_var(array(
								'MEDIA_DIRECTORY' => MEDIA_DIRECTORY,
								'TEXT_NAME' => $TEXT['TITLE'],
								'TEXT_TARGET_FOLDER' => $TEXT['TARGET_FOLDER'],
								'TEXT_OVERWRITE_EXISTING' => $TEXT['OVERWRITE_EXISTING'],
								'TEXT_FILES' => $TEXT['FILES'],
								'TEXT_CREATE_FOLDER' => $TEXT['CREATE_FOLDER'],
								'TEXT_UPLOAD_FILES' => $TEXT['UPLOAD_FILES']
								)
						);

// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

// Print admin 
$admin->print_footer();

?>