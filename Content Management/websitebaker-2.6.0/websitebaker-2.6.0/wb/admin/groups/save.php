<?php

// $Id: save.php 10 2005-09-04 08:59:31Z ryan $

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
$admin = new admin('Access', 'groups_modify');

// Create new database object
$database = new database();

// Check if group group_id is a valid number and doesnt equal 1
if(!isset($_POST['group_id']) OR !is_numeric($_POST['group_id']) OR $_POST['group_id'] == 1) {
	header("Location: index.php");
} else {
	$group_id = $_POST['group_id'];
}

// Gather details entered
$group_name = $admin->get_post('group_name');

// Create a javascript back link
$js_back = "javascript: history.go(-1);";

// Check values
if($group_name == "") {
	$admin->print_error($MESSAGE['GROUPS']['GROUP_NAME_BLANK'], $js_back);
}

// Get system permissions
require_once(ADMIN_PATH.'/groups/get_permissions.php');

// Update the database
$query = "UPDATE ".TABLE_PREFIX."groups SET name = '$group_name', system_permissions = '$system_permissions', module_permissions = '$module_permissions', template_permissions = '$template_permissions' WHERE group_id = '$group_id'";

$database->query($query);
if($database->is_error()) {
	$admin->print_error($database->get_error());
} else {
	$admin->print_success($MESSAGE['GROUPS']['SAVED'], ADMIN_URL.'/groups/index.php');
}

// Print admin footer
$admin->print_footer();

?>