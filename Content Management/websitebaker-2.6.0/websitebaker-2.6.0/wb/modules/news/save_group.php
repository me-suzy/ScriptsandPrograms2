<?php

// $Id: save_group.php 40 2005-09-07 19:22:34Z stefan $

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

require('../../config.php');

// Get id
if(!isset($_POST['group_id']) OR !is_numeric($_POST['group_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$group_id = $_POST['group_id'];
}

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

// Include WB functions file
require(WB_PATH.'/framework/functions.php');

// Vagroup_idate all fields
if($admin->get_post('title') == '') {
	$admin->print_error($MESSAGE['GENERIC']['FILL_IN_ALL'], WB_URL.'/modules/news/modify_group.php?page_id='.$page_id.'&section_id='.$section_id.'&group_id='.$group_id);
} else {
	$title = $admin->add_slashes($admin->get_post('title'));
	$active = $admin->get_post('active');
}

// Update row
$database->query("UPDATE ".TABLE_PREFIX."mod_news_groups SET title = '$title', active = '$active' WHERE group_id = '$group_id'");

// Check if the user uploaded an image or wants to delete one
if(isset($_FILES['image']['tmp_name']) AND $_FILES['image']['tmp_name'] != '') {
	// Get real filename and set new filename
	$filename = $_FILES['image']['name'];
	$new_filename = WB_PATH.MEDIA_DIRECTORY.'/.news/image'.$group_id.'.jpg';
	// Make sure the image is a jpg file
	if(substr($filename, -3, 3) != 'jpg') {
		$admin->print_error($MESSAGE['GENERIC']['FILE_TYPE'].' JPG (JPEG)');
	} elseif(mime_content_type($_FILES['image']['tmp_name']) != 'image/jpeg' AND mime_content_type($_FILES['image']['tmp_name']) != 'image/jpg') {
		$admin->print_error($MESSAGE['GENERIC']['FILE_TYPE'].' JPG (JPEG)');
	}
	// Make sure the target directory exists
	make_dir(WB_PATH.MEDIA_DIRECTORY.'/.news');
	// Upload image
	move_uploaded_file($_FILES['image']['tmp_name'], $new_filename);
	// Check if we need to create a thumb
	$query_settings = $database->query("SELECT resize FROM ".TABLE_PREFIX."mod_news_settings WHERE section_id = '$section_id'");
	$fetch_settings = $query_settings->fetchRow();
	$resize = $fetch_settings['resize'];
	if($resize != 0) {
		// Resize the image
		$thumb_location = WB_PATH.MEDIA_DIRECTORY.'/.news/thumb'.$group_id.'.jpg';
		if(make_thumb($new_filename, $thumb_location, $resize)) {
			// Delete the actual image and replace with the resized version
			unlink($new_filename);
			rename($thumb_location, $new_filename);
		}
	}
}
if(isset($_POST['delete_image']) AND $_POST['delete_image'] != '') {
	// Try unlinking image
	if(file_exists(WB_PATH.MEDIA_DIRECTORY.'/.news/image'.$group_id.'.jpg')) {
		unlink(WB_PATH.MEDIA_DIRECTORY.'/.news/image'.$group_id.'.jpg');
	}
}

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), WB_URL.'/modules/news/modify_group.php?page_id='.$page_id.'&section_id='.$section_id.'&group_id='.$group_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>
