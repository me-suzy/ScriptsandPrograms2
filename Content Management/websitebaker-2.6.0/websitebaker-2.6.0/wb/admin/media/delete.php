<?php

// $Id: delete.php 239 2005-11-22 11:50:41Z stefan $

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
$admin = new admin('Media', 'media_delete', false);

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Include the stripped-down header file
require('basic_header.html');

// Get the current dir
$directory = $admin->get_get('dir');
if($directory == '/') {
	$directory = '';
}
// Check to see if it contains ../
if(strstr($directory, '../')) {
	$admin->print_header();
	$admin->print_error($MESSAGE['MEDIA']['DOT_DOT_SLASH']);
}

// Get the temp id
if(!is_numeric($admin->get_get('id'))) {
	header("Location: browse.php?dir=$directory");
} else {
	$file_id = $admin->get_get('id');
}

// Get home folder not to show
$home_folders = get_home_folders();

// Figure out what folder name the temp id is
if($handle = opendir(WB_PATH.MEDIA_DIRECTORY.'/'.$directory)) {
	// Loop through the files and dirs an add to list
   while (false !== ($file = readdir($handle))) {
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
	$temp_id = 0;
	if(isset($DIR)) {
		foreach($DIR AS $name) {
			$temp_id++;
			if(!isset($delete_file) AND $file_id == $temp_id) {
				$delete_file = $name;
				$type = 'folder';
			}
		}
	}
	if(isset($FILE)) {
		foreach($FILE AS $name) {
			$temp_id++;
			if(!isset($delete_file) AND $file_id == $temp_id) {
				$delete_file = $name;
				$type = 'file';
			}
		}
	}
}

// Check to see if we could find an id to match
if(!isset($delete_file)) {
	$admin->print_error($MESSAGE['MEDIA']['FILE_NOT_FOUND'], "browse.php?dir=$directory", false);
}
$relative_path = WB_PATH.MEDIA_DIRECTORY.'/'.$directory.'/'.$delete_file;
// Check if the file/folder exists
if(!file_exists($relative_path)) {
	$admin->print_error($MESSAGE['MEDIA']['FILE_NOT_FOUND'], "browse.php?dir=$directory", false);	
}

// Find out whether its a file or folder
if($type == 'folder') {
	// Try and delete the directory
	if(rm_full_dir($relative_path)) {
		$admin->print_success($MESSAGE['MEDIA']['DELETED_DIR'], "browse.php?dir=$directory");
	} else {
		$admin->print_error($MESSAGE['MEDIA']['CANNOT_DELETE_DIR'], "browse.php?dir=$directory", false);
	}
} else {
	// Try and delete the file
	if(unlink($relative_path)) {
		$admin->print_success($MESSAGE['MEDIA']['DELETED_FILE'], "browse.php?dir=$directory");
	} else {
		$admin->print_error($MESSAGE['MEDIA']['CANNOT_DELETE_FILE'], "browse.php?dir=$directory", false);
	}
}

?>