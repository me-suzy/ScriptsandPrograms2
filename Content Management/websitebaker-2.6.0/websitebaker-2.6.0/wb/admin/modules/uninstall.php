<?php

// $Id: uninstall.php 210 2005-10-23 12:50:15Z stefan $

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

// Check if user selected module
if(!isset($_POST['file']) OR $_POST['file'] == "") {
	header("Location: index.php");
} else {
	$file = $_POST['file'];
}

// Setup admin object
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Addons', 'modules_uninstall');

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Check if the module exists
if(!is_dir(WB_PATH.'/modules/'.$file)) {
	$admin->print_error($MESSAGE['MODULES']['NOT_INSTALLED']);
}

// Check if the module is in use
$query_modules = $database->query("SELECT section_id FROM ".TABLE_PREFIX."sections WHERE module = '".$admin->add_slashes($_POST['file'])."' LIMIT 1");
if($query_modules->numRows() > 0) {
	$admin->print_error($MESSAGE['GENERIC']['CANNOT_UNINSTALL_IN_USE']);
}

// Check if we have permissions on the directory
if(!is_writable(WB_PATH.'/modules/'.$file)) {
	$admin->print_error($MESSAGE['GENERIC']['CANNOT_UNINSTALL']);
}

$database->query("DELETE FROM ".TABLE_PREFIX."modules WHERE directory = '$file'"); 

// Run the modules uninstall script if there is one
if(file_exists(WB_PATH.'/modules/'.$file.'/uninstall.php')) {
	require(WB_PATH.'/modules/'.$file.'/uninstall.php');
}

// Try to delete the module dir
if(!rm_full_dir(WB_PATH.'/modules/'.$file)) {
	$admin->print_error($MESSAGE['MODULES']['CANNOT_UNINSTALL']);
} else {
	// Remove entry from DB
	$database->query("DELETE FROM ".TABLE_PREFIX."addons WHERE directory = '".$file."' AND type = 'module'");
}

// Print success message
$admin->print_success($MESSAGE['GENERIC']['UNINSTALLED']);

// Print admin footer
$admin->print_footer();

?>