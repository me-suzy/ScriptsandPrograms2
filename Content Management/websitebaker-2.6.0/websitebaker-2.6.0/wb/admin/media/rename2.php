<?php

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
$admin = new admin('Media', 'media_rename', false);

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Include the basic header file
require(ADMIN_PATH.'/media/basic_header.html');

// Get list of file types to which we're supposed to append 'txt'
$get_result=$database->query("SELECT value FROM ".TABLE_PREFIX."settings WHERE name='rename_files_on_upload' LIMIT 1");
$file_extension_string='';
if ($get_result->numRows()>0) {
	$fetch_result=$get_result->fetchRow();
	$file_extension_string=$fetch_result['value'];
}
$file_extensions=explode(",",$file_extension_string);


// Get the current dir
$directory = $admin->get_post('dir');
if($directory == '/') {
	$directory = '';
}
// Check to see if it contains ../
if(strstr($directory, '../')) {
	$admin->print_header();
	$admin->print_error($MESSAGE['MEDIA']['DIR_DOT_DOT_SLASH']);
}

// Get the temp id
if(!is_numeric($admin->get_post('id'))) {
	header("Location: browse.php?dir=$directory");
} else {
	$file_id = $admin->get_post('id');
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
			if($file_id == $temp_id) {
				$rename_file = $name;
				$type = 'folder';
			}
		}
	}
	if(isset($FILE)) {
		foreach($FILE AS $name) {
			$temp_id++;
			if($file_id == $temp_id) {
				$rename_file = $name;
				$type = 'file';
			}
		}
	}
}

if(!isset($rename_file)) {
	$admin->print_error($MESSAGE['MEDIA']['FILE_NOT_FOUND'], "browse.php?dir=$directory", false);
}

// Check if they entered a new name
if(media_filename($admin->get_post('name')) == "") {
	$admin->print_error($MESSAGE['MEDIA']['BLANK_NAME'], "rename.php?dir=$directory&id=$file_id", false);
} else {
	$old_name = $admin->get_post('old_name');
	$new_name =  media_filename($admin->get_post('name'));
}

// Check if they entered an extension
if($type == 'file') {
	if(media_filename($admin->get_post('extension')) == "") {
		$admin->print_error($MESSAGE['MEDIA']['BLANK_EXTENSION'], "rename.php?dir=$directory&id=$file_id", false);
	} else {
		$extension = media_filename($admin->get_post('extension'));
	}
} else {
	$extension = '';
}

// Join new name and extension
$name = $new_name.$extension;

// Check if the name contains ..
if(strstr($name, '..')) {
	$admin->print_error($MESSAGE['MEDIA']['NAME_DOT_DOT_SLASH'], "rename.php?dir=$directory&id=$file_id", false);
}

// Check if the name is index.php
if($name == 'index.php') {
	$admin->print_error($MESSAGE['MEDIA']['NAME_INDEX_PHP'], "rename.php?dir=$directory&id=$file_id", false);
}

// Check that the name still has a value
if($name == '') {
	$admin->print_error($MESSAGE['MEDIA']['BLANK_NAME'], "rename.php?dir=$directory&id=$file_id", false);
}

// Check for potentially malicious files and append 'txt' to their name
foreach($file_extensions as $file_ext) {
	$file_ext_len=strlen($file_ext);
	if (substr($name,-$file_ext_len)==$file_ext) {
		$name.='.txt';
	}
}		


// Check if we should overwrite or not
if($admin->get_post('overwrite') != 'yes' AND file_exists(WB_PATH.MEDIA_DIRECTORY.$directory.'/'.$name) == true) {
	if($type == 'folder') {
		$admin->print_error($MESSAGE['MEDIA']['DIR_EXISTS'], "rename.php?dir=$directory&id=$file_id", false);
	} else {
		$admin->print_error($MESSAGE['MEDIA']['FILE_EXISTS'], "rename.php?dir=$directory&id=$file_id", false);
	}
}

// Try and rename the file/folder
if(rename(WB_PATH.MEDIA_DIRECTORY.$directory.'/'.$rename_file, WB_PATH.MEDIA_DIRECTORY.$directory.'/'.$name)) {
	$admin->print_success($MESSAGE['MEDIA']['RENAMED'], "browse.php?dir=$directory");
} else {
	$admin->print_error($MESSAGE['MEDIA']['CANNOT_RENAME'], "rename.php?dir=$directory&id=$file_id", false);
}
?>