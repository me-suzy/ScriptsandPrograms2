<?php

// $Id: install.php 250 2005-11-27 09:44:15Z ryan $

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

if(defined('WB_URL')) {
	
	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_news_posts`");
	$mod_news = 'CREATE TABLE `'.TABLE_PREFIX.'mod_news_posts` ( '
					 . '`post_id` INT NOT NULL AUTO_INCREMENT,'
					 . '`section_id` INT NOT NULL,'
					 . '`page_id` INT NOT NULL,'
					 . '`group_id` INT NOT NULL,'
					 . '`active` INT NOT NULL,'
					 . '`position` INT NOT NULL,'
					 . '`title` VARCHAR(255) NOT NULL,'
					 . '`link` TEXT NOT NULL,'
					 . '`content_short` TEXT NOT NULL,'
					 . '`content_long` TEXT NOT NULL,'
					 . '`commenting` VARCHAR(7) NOT NULL,'
		   	    	 . '`posted_when` INT NOT NULL ,'
					 . '`posted_by` INT NOT NULL ,'
					 . 'PRIMARY KEY (post_id)'
                . ' )';
	$database->query($mod_news);
	
	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_news_groups`");
	$mod_news = 'CREATE TABLE `'.TABLE_PREFIX.'mod_news_groups` ( '
					 . '`group_id` INT NOT NULL AUTO_INCREMENT,'
					 . '`section_id` INT NOT NULL,'
					 . '`page_id` INT NOT NULL,'
					 . '`active` INT NOT NULL,'
					 . '`position` INT NOT NULL,'
					 . '`title` VARCHAR(255) NOT NULL,'
					 . 'PRIMARY KEY (group_id)'
                . ' )';
	$database->query($mod_news);
	
	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_news_comments`");
	$mod_news = 'CREATE TABLE `'.TABLE_PREFIX.'mod_news_comments` ( '
					 . '`comment_id` INT NOT NULL AUTO_INCREMENT,'
					 . '`section_id` INT NOT NULL,'
					 . '`page_id` INT NOT NULL,'
					 . '`post_id` INT NOT NULL,'
					 . '`title` VARCHAR(255) NOT NULL,'
					 . '`comment` TEXT NOT NULL,'
		   	    . '`commented_when` INT NOT NULL ,'
					 . '`commented_by` INT NOT NULL ,'
					 . 'PRIMARY KEY (comment_id)'
                . ' )';
	$database->query($mod_news);
	
	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_news_settings`");
	$mod_news = 'CREATE TABLE `'.TABLE_PREFIX.'mod_news_settings` ( '
					 . '`section_id` INT NOT NULL,'
					 . '`page_id` INT NOT NULL,'
					 . '`header` TEXT NOT NULL,'
					 . '`post_loop` TEXT NOT NULL,'
					 . '`footer` TEXT NOT NULL,'
					 . '`posts_per_page` INT NOT NULL,'
					 . '`post_header` TEXT NOT NULL,'
					 . '`post_footer` TEXT NOT NULL,'
					 . '`comments_header` TEXT NOT NULL,'
					 . '`comments_loop` TEXT NOT NULL,'
					 . '`comments_footer` TEXT NOT NULL,'
					 . '`comments_page` TEXT NOT NULL,'
					 . '`commenting` VARCHAR(7) NOT NULL,'
					 . '`resize` INT NOT NULL,'
					 . ' `use_captcha` INT NOT NULL,'
					 . 'PRIMARY KEY (section_id)'
                . ' )';
	$database->query($mod_news);
		
	// Insert info into the search table
	// Module query info
	$field_info = array();
	$field_info['page_id'] = 'page_id';
	$field_info['title'] = 'page_title';
	$field_info['link'] = 'link';
	$field_info['description'] = 'description';
	$field_info['modified_when'] = 'modified_when';
	$field_info['modified_by'] = 'modified_by';
	$field_info = serialize($field_info);
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('module', 'news', '$field_info')");
	// Query start
	$query_start_code = "SELECT [TP]pages.page_id, [TP]pages.page_title,	[TP]pages.link, [TP]pages.description, [TP]pages.modified_when, [TP]pages.modified_by	FROM [TP]mod_news_posts, [TP]mod_news_groups, [TP]mod_news_comments, [TP]mod_news_settings, [TP]pages WHERE ";
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_start', '$query_start_code', 'news')");
	// Query body
	$query_body_code = "
	[TP]pages.page_id = [TP]mod_news_posts.page_id AND [TP]mod_news_posts.title LIKE \'%[STRING]%\'
	OR [TP]pages.page_id = [TP]mod_news_posts.page_id AND [TP]mod_news_posts.content_short LIKE \'%[STRING]%\'
	OR [TP]pages.page_id = [TP]mod_news_posts.page_id AND [TP]mod_news_posts.content_long LIKE \'%[STRING]%\'
	OR [TP]pages.page_id = [TP]mod_news_comments.page_id AND [TP]mod_news_comments.title LIKE \'%[STRING]%\'
	OR [TP]pages.page_id = [TP]mod_news_comments.page_id AND [TP]mod_news_comments.comment LIKE \'%[STRING]%\'
	OR [TP]pages.page_id = [TP]mod_news_settings.page_id AND [TP]mod_news_settings.header LIKE \'%[STRING]%\'
	OR [TP]pages.page_id = [TP]mod_news_settings.page_id AND [TP]mod_news_settings.footer LIKE \'%[STRING]%\'
	OR [TP]pages.page_id = [TP]mod_news_settings.page_id AND [TP]mod_news_settings.post_header LIKE \'%[STRING]%\'
	OR [TP]pages.page_id = [TP]mod_news_settings.page_id AND [TP]mod_news_settings.post_footer LIKE \'%[STRING]%\'
	OR [TP]pages.page_id = [TP]mod_news_settings.page_id AND [TP]mod_news_settings.comments_header LIKE \'%[STRING]%\'
	OR [TP]pages.page_id = [TP]mod_news_settings.page_id AND [TP]mod_news_settings.comments_footer LIKE \'%[STRING]%\'
	OR [TP]pages.page_id = [TP]mod_news_settings.page_id AND [TP]mod_news_settings.comments_footer LIKE \'%[STRING]%\'";
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_body', '$query_body_code', 'news')");
	// Query end
	$query_end_code = "";	
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_end', '$query_end_code', 'news')");
	
	// Insert blank row (there needs to be at least on row for the search to work)
	$database->query("INSERT INTO ".TABLE_PREFIX."mod_news_posts (section_id,page_id) VALUES ('0', '0')");
	$database->query("INSERT INTO ".TABLE_PREFIX."mod_news_groups (section_id,page_id) VALUES ('0', '0')");
	$database->query("INSERT INTO ".TABLE_PREFIX."mod_news_comments (section_id,page_id) VALUES ('0', '0')");
	$database->query("INSERT INTO ".TABLE_PREFIX."mod_news_settings (section_id,page_id) VALUES ('0', '0')");
	
	// Make news post access files dir
	make_dir(WB_PATH.PAGES_DIRECTORY.'/posts/');
	
}

?>