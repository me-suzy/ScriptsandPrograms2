<?php

// $Id: email.php 10 2005-09-04 08:59:31Z ryan $

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
$admin = new admin('Preferences');

// Get entered values
$password = $admin->get_post('current_password');
$email = $admin->get_post('email');

// Create a javascript back link
$js_back = "javascript: history.go(-1);";

// Get password
$database = new database();
$query = "SELECT user_id FROM ".TABLE_PREFIX."users WHERE user_id = '".$admin->get_user_id()."' AND password = '".md5($password)."'";
$results = $database->query($query);

// Validate values
if($results->numRows() == 0) {
	$admin->print_error($MESSAGE['PREFERENCES']['CURRENT_PASSWORD_INCORRECT']);
}
if(!$admin->validate_email($email)) {
	$admin->print_error($MESSAGE['USERS']['INVALID_EMAIL']);
}

// Update the database
$database = new database();
$query = "UPDATE ".TABLE_PREFIX."users SET email = '$email' WHERE user_id = '".$admin->get_user_id()."'";
$database->query($query);
if($database->is_error()) {
	$admin->print_error($database->get_error);
} else {
	$admin->print_success($MESSAGE['PREFERENCES']['EMAIL_UPDATED']);
	$_SESSION['EMAIL'] = $email;
}

// Print admin footer
$admin->print_footer();

?>