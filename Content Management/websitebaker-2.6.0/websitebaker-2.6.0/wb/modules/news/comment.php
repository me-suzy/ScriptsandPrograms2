<?php

// $Id: comment.php 239 2005-11-22 11:50:41Z stefan $

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

// Include config file
require('../../config.php');

// Check if there is a post id
if(!isset($_GET['id']) OR !is_numeric($_GET['id'])) {
	if(!isset($_POST['post_id']) OR !is_numeric($_POST['post_id'])) {
		header('Location: '.WB_URL.'/pages/');
	} else {
		$post_id = $_POST['post_id'];
	}
} else {
	$post_id = $_GET['id'];
}

// Include database class
require_once(WB_PATH.'/framework/class.database.php');
$database = new database();

// Query post for page id
$query_post = $database->query("SELECT post_id,title,section_id,page_id FROM ".TABLE_PREFIX."mod_news_posts WHERE post_id = '$post_id'");
if($query_post->numRows() == 0) {
	header('Location: '.WB_URL.'/pages/');
} else {
	$fetch_post = $query_post->fetchRow();
	$page_id = $fetch_post['page_id'];
	$section_id = $fetch_post['section_id'];
	$post_id = $fetch_post['post_id'];
	$post_title = $fetch_post['title'];
	define('SECTION_ID', $section_id);
	define('POST_ID', $post_id);
	define('POST_TITLE', $post_title);
	// Get page details
	$query_page = $database->query("SELECT parent,page_title,menu_title,keywords,description,visibility FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'");
	if($query_page->numRows() == 0) {
		header('Location: '.WB_URL.'/pages/');
	} else {
		$page = $query_page->fetchRow();
		// Required page details
		define('PAGE_CONTENT', WB_PATH.'/modules/news/comment_page.php');
		// Include index (wrapper) file
		require(WB_PATH.'/index.php');
	}
}


?>