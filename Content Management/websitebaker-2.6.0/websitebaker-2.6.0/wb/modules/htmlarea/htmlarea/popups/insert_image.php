<?php

// $Id: insert_image.php,v 1.3 2005/04/02 06:25:54 rdjurovich Exp $

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
require('../../../../config.php');

// Create new admin object
require(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_modify', false);

// Setup the template
$template = new Template(WB_PATH.'/modules/htmlarea/htmlarea/popups');
$template->set_file('page', 'insert_image.html');
$template->set_block('page', 'main_block', 'main');

// Get the directory to browse
$directory = $admin->get_post('folder');
if($directory == '') {
	$directory = '/media';
}
// If the directory contains ../ then set it to /media
if(strstr($directory, '../')) {
	$directory = '/media';
}

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Insert values into template
$template->set_var('WB_URL', WB_URL);
$template->set_var('POPUP', 'image');
$template->set_var('DIRECTORY', str_replace(WB_URL, '', $directory));

// Get home folder not to show
$home_folders = get_home_folders();

// Insert dirs into the dir list
$template->set_block('main_block', 'dir_list_block', 'dir_list');
foreach(directory_list(WB_PATH.MEDIA_DIRECTORY) AS $name) {
	$template->set_var('NAME', str_replace(WB_PATH, '', $name));
	if(!isset($home_folders[str_replace(WB_PATH.MEDIA_DIRECTORY, '', $name)])) {
		if($directory == str_replace(WB_PATH, '', $name)) {
			$template->set_var('SELECTED', ' selected');
		} else {
			$template->set_var('SELECTED', '');
		}
		$template->parse('dir_list', 'dir_list_block', true);
	}
}

// Parse the template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

?>