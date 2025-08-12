<?php

// $Id: install.php 211 2005-10-23 13:34:13Z stefan $

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
$admin = new admin('Addons', 'languages_install');

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Create temp string
$temp_string = '';
$salt = "abchefghjkmnpqrstuvwxyz0123456789";
srand((double)microtime()*1000000);
$i = 0;
while ($i <= 7) {
	$num = rand() % 33;
	$tmp = substr($salt, $num, 1);
	$temp_string = $temp_string . $tmp;
	$i++;
}

// Set temp vars
$temp_dir = WB_PATH.'/temp/';
$temp_file = $temp_dir . 'language'.$temp_string;

// Check if language dir is writable
if(!is_writable(WB_PATH.'/languages/')) {
	if(file_exists($temp_file)) { unlink($temp_file); } // Remove temp file
	$admin->print_error($MESSAGE['GENERIC']['BAD_PERMISSIONS']);
}

// Try to upload the file to the temp dir
if(!move_uploaded_file($_FILES['userfile']['tmp_name'], $temp_file)) {
	if(file_exists($temp_file)) { unlink($temp_file); } // Remove temp file
	$admin->print_error($MESSAGE['GENERIC']['CANNOT_UPLOAD']);
}

// Remove any vars with name "language_code"
unset($language_code);

// Read the temp file and look for a language code
require($temp_file);
$new_language_version=$language_version;

// Check if the file is valid
if(!isset($language_code)) {
	if(file_exists($temp_file)) { unlink($temp_file); } // Remove temp file
	// Restore to correct language
	require(WB_PATH.'/languages/'.LANGUAGE.'.php');
	$admin->print_error($MESSAGE['GENERIC']['INVALID']);
}

// Set destination for language file
$language_file = WB_PATH.'/languages/'.$language_code.'.php';

// Move to new location
if (file_exists($language_file)) {
	require($language_file);
	if ($language_version>$new_language_version) {
		$admin->print_error($MESSAGE['GENERIC']['ALREADY_INSTALLED']);
	}
	unlink($language_file);
}

rename($temp_file, $language_file);

// Chmod the file
change_mode($language_file, 'file');

// Load language info into DB
load_language($language_file);

// Restore to correct language
require(WB_PATH.'/languages/'.LANGUAGE.'.php');

// Print success message
$admin->print_success($MESSAGE['GENERIC']['INSTALLED']);

// Print admin footer
$admin->print_footer();

?>