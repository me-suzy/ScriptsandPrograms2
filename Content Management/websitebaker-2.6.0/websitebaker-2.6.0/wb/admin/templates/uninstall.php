<?php

// $Id: uninstall.php 211 2005-10-23 13:34:13Z stefan $

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

// Check if user selected template
if(!isset($_POST['file']) OR $_POST['file'] == "") {
	header("Location: index.php");
} else {
	$file = $_POST['file'];
}

// Setup admin object
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Addons', 'templates_uninstall');

// Include the WB functions file
require_once(WB_PATH.'/framework/functions.php');

// Check if the template exists
if(!is_dir(WB_PATH.'/templates/'.$file)) {
	$admin->print_error($MESSAGE['GENERIC']['NOT_INSTALLED']);
}

// Check if the template is in use
if($_POST['file'] == DEFAULT_TEMPLATE) {
	$admin->print_error($MESSAGE['GENERIC']['CANNOT_UNINSTALL_IN_USE']);
} else {
	$query_templates = $database->query("SELECT page_id FROM ".TABLE_PREFIX."pages WHERE template = '".$admin->add_slashes($_POST['file'])."' LIMIT 1");
	if($query_templates->numRows() > 0) {
		$admin->print_error($MESSAGE['GENERIC']['CANNOT_UNINSTALL_IN_USE']);
	}
}

// Check if we have permissions on the directory
if(!is_writable(WB_PATH.'/templates/'.$file)) {
	$admin->print_error($MESSAGE['GENERIC']['CANNOT_UNINSTALL'].WB_PATH.'/templates/'.$file);
}

// Try to delete the template dir
if(!rm_full_dir(WB_PATH.'/templates/'.$file)) {
	$admin->print_error($MESSAGE['GENERIC']['CANNOT_UNINSTALL']);
} else {
	// Remove entry from DB
	$database->query("DELETE FROM ".TABLE_PREFIX."addons WHERE directory = '".$file."' AND type = 'template'");
}

// Update pages that use this template with default template
$database = new database();
$database->query("UPDATE ".TABLE_PREFIX."pages SET template = '".DEFAULT_TEMPLATE."' WHERE template = '$file'");

// Print success message
$admin->print_success($MESSAGE['GENERIC']['UNINSTALLED']);

// Print admin footer
$admin->print_footer();

?>