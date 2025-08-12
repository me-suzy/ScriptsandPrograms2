<?php

// $Id: signup2.php 253 2005-11-27 12:43:22Z ryan $

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
	header('Location: ../pages/index.php');
}

require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Start', 'start', false, false);

// Create new database object
$database = new database();

// Get details entered
$group_id = FRONTEND_SIGNUP;
$active = 1;
$username = strtolower($admin->get_post('username'));
$display_name = $admin->get_post('display_name');
$email = $admin->get_post('email');

// Create a javascript back link
$js_back = "javascript: history.go(-1);";

// Check values
if($group_id == "") {
	$admin->print_error($MESSAGE['USERS']['NO_GROUP'], $js_back);
}
if(strlen($username) < 3) {
	$admin->print_error($MESSAGE['USERS']['USERNAME_TOO_SHORT'], $js_back);
}
if($email != "") {
	if($admin->validate_email($email) == false) {
		$admin->print_error($MESSAGE['USERS']['INVALID_EMAIL'], $js_back);
	}
} else {
	$admin->print_error($MESSAGE['SIGNUP']['NO_EMAIL'], $js_back);
}
// Captcha
if(extension_loaded('gd') AND function_exists('imageCreateFromJpeg')) { /* Make's sure GD library is installed */
	if(isset($_POST['captcha']) AND $_POST['captcha'] != ''){
		// Check for a mismatch
		if(!isset($_POST['captcha']) OR !isset($_SESSION['captcha']) OR $_POST['captcha'] != $_SESSION['captcha']) {
			$admin->print_error($MESSAGE['MOD_FORM']['INCORRECT_CAPTCHA'], $js_back);
		}
	} else {
		$admin->print_error($MESSAGE['MOD_FORM']['INCORRECT_CAPTCHA'], $js_back);
	}
}
if(isset($_SESSION['catpcha'])) { unset($_SESSION['captcha']); }

// Generate a random password then update the database with it
$new_pass = '';
$salt = "abchefghjkmnpqrstuvwxyz0123456789";
srand((double)microtime()*1000000);
$i = 0;
while ($i <= 7) {
	$num = rand() % 33;
	$tmp = substr($salt, $num, 1);
	$new_pass = $new_pass . $tmp;
	$i++;
}
$md5_password = md5($new_pass);

// Check if username already exists
$results = $database->query("SELECT user_id FROM ".TABLE_PREFIX."users WHERE username = '$username'");
if($results->numRows() > 0) {
	$admin->print_error($MESSAGE['USERS']['USERNAME_TAKEN'], $js_back);
}

// Check if the email already exists
$results = $database->query("SELECT user_id FROM ".TABLE_PREFIX."users WHERE email = '".$wb->add_slashes($_POST['email'])."'");
if($results->numRows() > 0) {
	if(isset($MESSAGE['USERS']['EMAIL_TAKEN'])) {
		$admin->print_error($MESSAGE['USERS']['EMAIL_TAKEN'], $js_back);
	} else {
		$admin->print_error($MESSAGE['USERS']['INVALID_EMAIL'], $js_back);
	}
}

// MD5 supplied password
$md5_password = md5($new_pass);

// Inser the user into the database
$query = "INSERT INTO ".TABLE_PREFIX."users (group_id,active,username,password,display_name,email) VALUES ('$group_id', '$active', '$username','$md5_password','$display_name','$email')";
$database->query($query);

if($database->is_error()) {
	// Error updating database
	$message = $database->get_error();
} else {
	// Setup email to send
	$mail_subject = 'Your login details...';
	$mail_to = $email;
	$mail_message = ''.
'Hello '.$display_name.', 

Your '.WEBSITE_TITLE.' login details are:
Username: '.$username.'
Password: '.$new_pass.'

Your password has been set to the one above.

If you have recieved this message in error, please delete it immediatly.';

	// Try sending the email
	if(mail($mail_to, $mail_subject, $mail_message, 'From: '.SERVER_EMAIL)) {
		$admin->print_success($MESSAGE['FORGOT_PASS']['PASSWORD_RESET'], WB_URL.'/account/login'.PAGE_EXTENSION);
		$display_form = false;
	} else {
		$admin->print_error($MESSAGE['FORGOT_PASS']['CANNOT_EMAIL'], $js_back);
	}
}

?>