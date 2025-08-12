<?php

// $Id: save.php 239 2005-11-22 11:50:41Z stefan $

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

// Find out if the user was view advanced options or not
if($_POST['advanced'] == 'yes' ? $advanced = '?advanced=yes' : $advanced = '');

// Print admin header
require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
if($advanced == '') {
	$admin = new admin('Settings', 'settings_basic');
	$_POST['database_password'] = DB_PASSWORD;
} else {
	$admin = new admin('Settings', 'settings_advanced');
}

// Work-out file mode
if($advanced == '') {
	// Check if should be set to 777 or left alone
	if(isset($_POST['world_writeable']) AND $_POST['world_writeable'] == 'true') {
		$file_mode = '0777';
		$dir_mode = '0777';
	} else {
		$file_mode = STRING_FILE_MODE;
		$dir_mode = STRING_DIR_MODE;
	}
} else {
	// Work-out the octal value for file mode
	$u = 0;
	if(isset($_POST['file_u_r']) AND $_POST['file_u_r'] == 'true') {
		$u = $u+4;
	}
	if(isset($_POST['file_u_w']) AND $_POST['file_u_w'] == 'true') {
		$u = $u+2;
	}
	if(isset($_POST['file_u_e']) AND $_POST['file_u_e'] == 'true') {
		$u = $u+1;
	}
	$g = 0;
	if(isset($_POST['file_g_r']) AND $_POST['file_g_r'] == 'true') {
		$g = $g+4;
	}
	if(isset($_POST['file_g_w']) AND $_POST['file_g_w'] == 'true') {
		$g = $g+2;
	}
	if(isset($_POST['file_g_e']) AND $_POST['file_g_e'] == 'true') {
		$g = $g+1;
	}
	$o = 0;
	if(isset($_POST['file_o_r']) AND $_POST['file_o_r'] == 'true') {
		$o = $o+4;
	}
	if(isset($_POST['file_o_w']) AND $_POST['file_o_w'] == 'true') {
		$o = $o+2;
	}
	if(isset($_POST['file_o_e']) AND $_POST['file_o_e'] == 'true') {
		$o = $o+1;
	}
	$file_mode = "0".$u.$g.$o;
	// Work-out the octal value for dir mode
	$u = 0;
	if(isset($_POST['dir_u_r']) AND $_POST['dir_u_r'] == 'true') {
		$u = $u+4;
	}
	if(isset($_POST['dir_u_w']) AND $_POST['dir_u_w'] == 'true') {
		$u = $u+2;
	}
	if(isset($_POST['dir_u_e']) AND $_POST['dir_u_e'] == 'true') {
		$u = $u+1;
	}
	$g = 0;
	if(isset($_POST['dir_g_r']) AND $_POST['dir_g_r'] == 'true') {
		$g = $g+4;
	}
	if(isset($_POST['dir_g_w']) AND $_POST['dir_g_w'] == 'true') {
		$g = $g+2;
	}
	if(isset($_POST['dir_g_e']) AND $_POST['dir_g_e'] == 'true') {
		$g = $g+1;
	}
	$o = 0;
	if(isset($_POST['dir_o_r']) AND $_POST['dir_o_r'] == 'true') {
		$o = $o+4;
	}
	if(isset($_POST['dir_o_w']) AND $_POST['dir_o_w'] == 'true') {
		$o = $o+2;
	}
	if(isset($_POST['dir_o_e']) AND $_POST['dir_o_e'] == 'true') {
		$o = $o+1;
	}
	$dir_mode = "0".$u.$g.$o;
}

// Create new database object
$database = new database();

// Query current settings in the db, then loop through them and update the db with the new value
$query = "SELECT name FROM ".TABLE_PREFIX."settings";
$results = $database->query($query);
while($setting = $results->fetchRow()) {
	$setting_name = $setting['name'];
	$value = $admin->get_post($setting_name);
	if ($value!=null || $setting_name=='default_timezone' || $setting_name=='string_dir_mode' || $setting_name=='string_file_mode') {
		$value = $admin->add_slashes($value);
		switch ($setting_name) {
			case 'default_timezone':
				$value=$value*60*60;
				break;
			case 'string_dir_mode':
				$value=$dir_mode;
				break;
			case 'string_file_mode':
				$value=$file_mode;
				break;
		}
		$database->query("UPDATE ".TABLE_PREFIX."settings SET value = '$value' WHERE name = '$setting_name'");
	}
}

// Query current search settings in the db, then loop through them and update the db with the new value
$query = "SELECT name FROM ".TABLE_PREFIX."search WHERE extra = ''";
$results = $database->query($query);
while($search_setting = $results->fetchRow()) {
	$setting_name = $search_setting['name'];
	$post_name = 'search_'.$search_setting['name'];
	$value = $admin->get_post($post_name);
	$value = $admin->add_slashes($value);
	$database->query("UPDATE ".TABLE_PREFIX."search SET value = '$value' WHERE name = '$setting_name'");
}

// Check if there was an error updating the db
if($database->is_error()) {
	$admin->print_error($database->get_error, ADMIN_URL.'/settings/index.php'.$advanced);
	$admin->print_footer();
	exit();
}

$admin->print_success($MESSAGE['SETTINGS']['SAVED'], ADMIN_URL.'/settings/index.php'.$advanced);
$admin->print_footer();

?>