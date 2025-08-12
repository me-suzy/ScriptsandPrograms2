<?php

// $Id: password.php 10 2005-09-04 08:59:31Z ryan $

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

if(!defined('WB_URL')) {
	header('Location: ../index.php');
}

// Get the values entered
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$new_password2 = $_POST['new_password2'];

// Create a javascript back link
$js_back = "javascript: history.go(-1);";

// Get existing password
$database = new database();
$query = "SELECT user_id FROM ".TABLE_PREFIX."users WHERE user_id = '".$admin->get_user_id()."' AND password = '".md5($current_password)."'";
$results = $database->query($query);

// Validate values
if($results->numRows() == 0) {
	$admin->print_error($MESSAGE['PREFERENCES']['CURRENT_PASSWORD_INCORRECT'], $js_back);
}
if(strlen($new_password) < 3) {
	$admin->print_error($MESSAGE['USERS']['PASSWORD_TOO_SHORT'], $js_back);
}
if($new_password != $new_password2) {
	$admin->print_error($MESSAGE['USERS']['PASSWORD_MISMATCH'], $js_back);
}

// MD5 the password
$md5_password = md5($new_password);

// Update the database
$database = new database();
$query = "UPDATE ".TABLE_PREFIX."users SET password = '$md5_password' WHERE user_id = '".$admin->get_user_id()."'";
$database->query($query);
if($database->is_error()) {
	$admin->print_error($database->get_error);
} else {
	$admin->print_success($MESSAGE['PREFERENCES']['PASSWORD_CHANGED'], WB_URL.'/account/preferences'.PAGE_EXTENSION);
}


?>