<?php

// $Id: install.php 227 2005-11-20 10:22:27Z ryan $

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

if(defined('WB_URL')) {
		
	// Create tables
	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_form_fields`");
	$mod_form = 'CREATE TABLE `'.TABLE_PREFIX.'mod_form_fields` ( `field_id` INT NOT NULL AUTO_INCREMENT,'
	                 . ' `section_id` INT NOT NULL ,'
	                 . ' `page_id` INT NOT NULL ,'
	                 . ' `position` INT NOT NULL ,'
	                 . ' `title` VARCHAR(255) NOT NULL ,'
	                 . ' `type` VARCHAR(255) NOT NULL ,'
	                 . ' `required` INT NOT NULL ,'
	                 . ' `value` TEXT NOT NULL ,'
	                 . ' `extra` TEXT NOT NULL ,'
	                 . ' PRIMARY KEY ( `field_id` ) )'
	                 . ' ';
	$database->query($mod_form);
	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_form_settings`");
	$mod_form = 'CREATE TABLE `'.TABLE_PREFIX.'mod_form_settings` ('
						  . ' `section_id` INT NOT NULL,'
						  . ' `page_id` INT NOT NULL,'
	                 . ' `header` TEXT NOT NULL ,'
	                 . ' `field_loop` TEXT NOT NULL ,'
	                 . ' `footer` TEXT NOT NULL ,'
	                 . ' `email_to` TEXT NOT NULL ,'
	                 . ' `email_from` VARCHAR(255) NOT NULL ,'
	                 . ' `email_subject` VARCHAR(255) NOT NULL ,'
	                 . ' `success_message` TEXT NOT NULL ,'
					 . ' `stored_submissions` INT NOT NULL,'
					 . ' `max_submissions` INT NOT NULL,'
					 . ' `use_captcha` INT NOT NULL,'
	                 . ' PRIMARY KEY ( `section_id` ) )'
	                 . ' ';
	$database->query($mod_form);
	$database->query("DROP TABLE IF EXISTS `".TABLE_PREFIX."mod_form_submissions`");
	$mod_form = 'CREATE TABLE `'.TABLE_PREFIX.'mod_form_submissions` ( `submission_id` INT NOT NULL AUTO_INCREMENT,'
						  . ' `section_id` INT NOT NULL,'
						  . ' `page_id` INT NOT NULL,'
						  . ' `submitted_when` INT NOT NULL,'
						  . ' `submitted_by` INT NOT NULL,'
	                 . ' `body` TEXT NOT NULL ,'
	                 . ' PRIMARY KEY ( `submission_id` ) )'
	                 . ' ';
	$database->query($mod_form);
		
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
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('module', 'form', '$field_info')");
	// Query start
	$query_start_code = "SELECT [TP]pages.page_id, [TP]pages.page_title,	[TP]pages.link, [TP]pages.description, [TP]pages.modified_when, [TP]pages.modified_by	FROM [TP]mod_form_fields, [TP]mod_form_settings, [TP]pages WHERE ";
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_start', '$query_start_code', 'form')");
	// Query body
	$query_body_code = " [TP]pages.page_id = [TP]mod_form_settings.page_id AND [TP]mod_form_settings.header [O] \'[W][STRING][W]\' AND [TP]pages.searching = \'1\'
	OR [TP]pages.page_id = [TP]mod_form_settings.page_id AND [TP]mod_form_settings.footer [O] \'[W][STRING][W]\' AND [TP]pages.searching = \'1\'
	OR [TP]pages.page_id = [TP]mod_form_fields.page_id AND [TP]mod_form_fields.title [O] \'[W][STRING][W]\' AND [TP]pages.searching = \'1\'";
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_body', '$query_body_code', 'form')");
	// Query end
	$query_end_code = '';
	$database->query("INSERT INTO ".TABLE_PREFIX."search (name,value,extra) VALUES ('query_end', '$query_end_code', 'form')");
	
	// Insert blank row (there needs to be at least on row for the search to work)
	$database->query("INSERT INTO ".TABLE_PREFIX."mod_form_fields (page_id,section_id) VALUES ('0','0')");
	$database->query("INSERT INTO ".TABLE_PREFIX."mod_form_settings (page_id,section_id) VALUES ('0','0')");

}

?>