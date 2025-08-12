<?php

// $Id: save_settings.php 250 2005-11-27 09:44:15Z ryan $

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

/*
The Website Baker Project would like to thank Rudolph Lartey <www.carbonect.com>
for his contributions to this module - adding extra field types
*/

require('../../config.php');

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

// This code removes any <?php tags and adds slashes
$friendly = array('&lt;', '&gt;', '?php');
$raw = array('<', '>', '');
$header = $admin->add_slashes($_POST['header']);
$field_loop = $admin->add_slashes($_POST['field_loop']);
$footer = $admin->add_slashes($_POST['footer']);
$email_to = $admin->add_slashes($_POST['email_to']);
if(extension_loaded('gd') AND function_exists('imageCreateFromJpeg')) {
	$use_captcha = $_POST['use_captcha'];
} else {
	$use_captcha = false;
}
if($_POST['email_from_field'] == '') {
	$email_from = $admin->add_slashes($_POST['email_from']);
} else {
	$email_from = $admin->add_slashes($_POST['email_from_field']);
}
$email_subject = $admin->add_slashes($_POST['email_subject']);
$success_message = $admin->add_slashes($_POST['success_message']);
if(!is_numeric($_POST['max_submissions'])) {
	$max_submissions = 50;
} else {
	$max_submissions = $_POST['max_submissions'];
}
if(!is_numeric($_POST['stored_submissions'])) {
	$stored_submissions = 100;
} else {
	$stored_submissions = $_POST['stored_submissions'];
}
// Make sure max submissions is not smaller than stored submissions
if($max_submissions < $stored_submissions) {
	$max_submissions = $stored_submissions;
}

// Update settings
$database->query("UPDATE ".TABLE_PREFIX."mod_form_settings SET header = '$header', field_loop = '$field_loop', footer = '$footer', email_to = '$email_to', email_from = '$email_from', email_subject = '$email_subject', success_message = '$success_message', max_submissions = '$max_submissions', stored_submissions = '$stored_submissions', use_captcha = '$use_captcha' WHERE section_id = '$section_id'");

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>