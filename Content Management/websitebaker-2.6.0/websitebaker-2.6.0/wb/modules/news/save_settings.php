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

require('../../config.php');

// Include WB admin wrapper script
$update_when_modified = true; // Tells script to update when this page was last updated
require(WB_PATH.'/modules/admin.php');

// This code removes any <?php tags and adds slashes
$friendly = array('&lt;', '&gt;', '?php');
$raw = array('<', '>', '');
$header = $admin->add_slashes(str_replace($friendly, $raw, $_POST['header']));
$post_loop = $admin->add_slashes(str_replace($friendly, $raw, $_POST['post_loop']));
$footer = $admin->add_slashes(str_replace($friendly, $raw, $_POST['footer']));
$post_header = $admin->add_slashes(str_replace($friendly, $raw, $_POST['post_header']));
$post_footer = $admin->add_slashes(str_replace($friendly, $raw, $_POST['post_footer']));
$comments_header = $admin->add_slashes(str_replace($friendly, $raw, $_POST['comments_header']));
$comments_loop = $admin->add_slashes(str_replace($friendly, $raw, $_POST['comments_loop']));
$comments_footer = $admin->add_slashes(str_replace($friendly, $raw, $_POST['comments_footer']));
$comments_page = $admin->add_slashes(str_replace($friendly, $raw, $_POST['comments_page']));
$commenting = $_POST['commenting'];
$posts_per_page = $_POST['posts_per_page'];
if(extension_loaded('gd') AND function_exists('imageCreateFromJpeg')) {
	$resize = $_POST['resize'];
	$use_captcha = $_POST['use_captcha'];
} else {
	$resize = '';
	$use_captcha = false;
}

// Update settings
$database->query("UPDATE ".TABLE_PREFIX."mod_news_settings SET header = '$header', post_loop = '$post_loop', footer = '$footer', posts_per_page = '$posts_per_page', post_header = '$post_header', post_footer = '$post_footer', comments_header = '$comments_header', comments_loop = '$comments_loop', comments_footer = '$comments_footer', comments_page = '$comments_page', commenting = '$commenting', resize = '$resize', use_captcha = '$use_captcha' WHERE section_id = '$section_id'");

// Check if there is a db error, otherwise say successful
if($database->is_error()) {
	$admin->print_error($database->get_error(), ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
} else {
	$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
}

// Print admin footer
$admin->print_footer();

?>