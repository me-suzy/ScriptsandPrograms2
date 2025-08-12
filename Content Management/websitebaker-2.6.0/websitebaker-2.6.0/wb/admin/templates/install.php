<?php

// $Id: install.php 239 2005-11-22 11:50:41Z stefan $

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

// Check if user uploaded a file
if(!isset($_FILES['userfile'])) {
	header("Location: index.php");
}

// Setup admin object
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Addons', 'templates_install');

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Set temp vars
$temp_dir = WB_PATH.'/temp/';
$temp_file = $temp_dir . $_FILES['userfile']['name'];
$temp_unzip = WB_PATH.'/temp/unzip/';

// Try to upload the file to the temp dir
if(!move_uploaded_file($_FILES['userfile']['tmp_name'], $temp_file)) {
	$admin->print_error($MESSAGE['GENERIC']['CANNOT_UPLOAD']);
}

// Include the PclZip class file (thanks to 
require_once(WB_PATH.'/include/pclzip/pclzip.lib.php');

// Remove any vars with name "template_directory"
unset($template_directory);

// Setup the PclZip object
$archive = new PclZip($temp_file);
// Unzip the files to the temp unzip folder
$list = $archive->extract(PCLZIP_OPT_PATH, $temp_unzip);
// Include the templates info file
require($temp_unzip.'info.php');
// Delete the temp unzip directory
rm_full_dir($temp_unzip);

// Check if the file is valid
if(!isset($template_directory)) {
	if(file_exists($temp_file)) { unlink($temp_file); } // Remove temp file
	$admin->print_error($MESSAGE['GENERIC']['INVALID']);
}

// Check if this module is already installed
// and compare versions if so
$new_template_version=$template_version;
if(is_dir(WB_PATH.'/templates/'.$template_directory)) {
	if(file_exists(WB_PATH.'/templates/'.$template_directory.'/info.php')) {
		require(WB_PATH.'/templates/'.$template_directory.'/info.php');
		// Version to be installed is older than currently installed version
		if ($template_version>$new_template_version) {
			if(file_exists($temp_file)) { unlink($temp_file); } // Remove temp file
			$admin->print_error($MESSAGE['GENERIC']['ALREADY_INSTALLED']);
		}
	} 
	$success_message=$MESSAGE['GENERIC']['UPGRADED'];
} else {
	$success_message=$MESSAGE['GENERIC']['INSTALLED'];
}

// Check if template dir is writable
if(!is_writable(WB_PATH.'/templates/')) {
	if(file_exists($temp_file)) { unlink($temp_file); } // Remove temp file
	$admin->print_error($MESSAGE['TEMPLATES']['BAD_PERMISSIONS']);
}

// Set template dir
$template_dir = WB_PATH.'/templates/'.$template_directory;

// Make sure the template dir exists, and chmod if needed
if(!file_exists($template_dir)) {
	make_dir($template_dir);
} else {
	change_mode($template_dir, 'dir');
}

// Unzip template to the template dir
$list = $archive->extract(PCLZIP_OPT_PATH, $template_dir);
if(!$list) {
	$admin->print_error($MESSAGE['GENERIC']['CANNOT_UNZIP']);
}

// Delete the temp zip file
if(file_exists($temp_file)) { unlink($temp_file); }

// Chmod all the uploaded files
$dir = dir($template_dir);
while(false !== $entry = $dir->read()) {
	// Skip pointers
	if(substr($entry, 0, 1) != '.' AND $entry != '.svn' AND !is_dir($template_dir.'/'.$entry)) {
		// Chmod file
		change_mode($template_dir.'/'.$entry);
	}
}

// Load template info into DB
load_template($template_dir);

// Print success message
$admin->print_success($success_message);

// Print admin footer
$admin->print_footer();

?>