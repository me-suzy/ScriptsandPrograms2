<?php

// $Id: browse.php 239 2005-11-22 11:50:41Z stefan $

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

// Create admin object
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Media', 'media', false);

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Setup template object
$template = new Template(ADMIN_PATH.'/media');
$template->set_file('page', 'browse.html');
$template->set_block('page', 'main_block', 'main');

// Get the current dir
$directory = $admin->get_get('dir');
if($directory == '/') {
	$directory = '';
}

// Check to see if it contains ../
if(strstr($directory, '../')) {
	$admin->print_header();
	$admin->print_error($MESSAGE['MEDIA']['DIR_DOT_DOT_SLASH']);
}

if(!file_exists(WB_PATH.'/media'.$directory)) {
	$admin->print_header();
	$admin->print_error($MESSAGE['MEDIA']['DIR_DOES_NOT_EXIST']);
}

// Check to see if the user wanted to go up a directory into the parent folder
if($admin->get_get('up') == 1) {
	$parent_directory = dirname($directory);
	header("Location: browse.php?dir=$parent_directory");	
}

// Workout the parent dir link
$parent_dir_link = ADMIN_URL.'/media/browse.php?dir='.$directory.'&up=1';
// Workout if the up arrow should be shown
if($directory == '') {
	$display_up_arrow = 'hide';
} else {
	$display_up_arrow = '';
}

// Insert values
$template->set_var(array(
								'CURRENT_DIR' => $directory,
								'PARENT_DIR_LINK' => $parent_dir_link,
								'DISPLAY_UP_ARROW' => $display_up_arrow
								)
						);

// Get home folder not to show
$home_folders = get_home_folders();

// Generate list
$template->set_block('main_block', 'list_block', 'list');
if($handle = opendir(WB_PATH.MEDIA_DIRECTORY.'/'.$directory)) {
	// Loop through the files and dirs an add to list
   while(false !== ($file = readdir($handle))) {
		if(substr($file, 0, 1) != '.' AND $file != '.svn' AND $file != 'index.php') {
			if(is_dir(WB_PATH.MEDIA_DIRECTORY.$directory.'/'.$file)) {
				if(!isset($home_folders[$directory.'/'.$file])) {
					$DIR[] = $file;
				}
			} else {
				$FILE[] = $file;
			}
		}
	}
	// Now parse these values to the template
	$temp_id = 0;
	$row_bg_color = 'EEEEEE';
	if(isset($DIR)) {
		foreach($DIR AS $name) {
			$link_name = str_replace(' ', '%20', $name);
			$temp_id++;
			$template->set_var(array(
											'NAME' => $name,
											'NAME_SLASHED' => addslashes($name),
											'TEMP_ID' => $temp_id,
											'LINK' => "browse.php?dir=$directory/$link_name",
											'LINK_TARGET' => '',
											'ROW_BG_COLOR' => $row_bg_color,
											'FILETYPE_ICON' => ADMIN_URL.'/images/folder_16.png'
											)
									);
			$template->parse('list', 'list_block', true);
			// Code to alternate row colors
			if($row_bg_color == 'DEDEDE') {
				$row_bg_color = 'EEEEEE';
			} else {
				$row_bg_color = 'DEDEDE';
			}
		}
	}
	if(isset($FILE)) {
		foreach($FILE AS $name) {
			$temp_id++;
			$template->set_var(array(
											'NAME' => $name,
											'NAME_SLASHED' => addslashes($name),
											'TEMP_ID' => $temp_id,
											'LINK' => WB_URL.MEDIA_DIRECTORY.$directory.'/'.$name,
											'LINK_TARGET' => '_blank',
											'ROW_BG_COLOR' => $row_bg_color,
											'FILETYPE_ICON' => ADMIN_URL.'/images/blank.gif'
											)
									);
			$template->parse('list', 'list_block', true);
			// Code to alternate row colors
			if($row_bg_color == 'DEDEDE') {
				$row_bg_color = 'EEEEEE';
			} else {
				$row_bg_color = 'DEDEDE';
			}
		}
	}
}

// If no files are in the media folder say so
if($temp_id == 0) {
	$template->set_var('DISPLAY_LIST_TABLE', 'hide');
} else {
	$template->set_var('DISPLAY_NONE_FOUND', 'hide');
}

// Insert permissions values
if($admin->get_permission('media_rename') != true) {
	$template->set_var('DISPLAY_RENAME', 'hide');
}
if($admin->get_permission('media_delete') != true) {
	$template->set_var('DISPLAY_DELETE', 'hide');
}

// Insert language text and messages
$template->set_var(array(
								'MEDIA_DIRECTORY' => MEDIA_DIRECTORY,
								'TEXT_CURRENT_FOLDER' => $TEXT['CURRENT_FOLDER'],
								'TEXT_RELOAD' => $TEXT['RELOAD'],
								'TEXT_RENAME' => $TEXT['RENAME'],
								'TEXT_DELETE' => $TEXT['DELETE'],
								'TEXT_UP' => $TEXT['UP'],
								'NONE_FOUND' => $MESSAGE['MEDIA']['NONE_FOUND'],
								'CONFIRM_DELETE' => $MESSAGE['MEDIA']['CONFIRM_DELETE']
								)
						);

// Parse template object
$template->parse('main', 'main_block', false);
$template->pparse('output', 'page');

?>