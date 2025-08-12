-- phpMyAdmin SQL Dump
-- version 2.6.3-pl1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Oct 16, 2005 at 04:30 AM
-- Server version: 5.0.13
-- PHP Version: 5.0.4
-- 
-- Database: `laborder`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `dadabik_item`
-- 

-- 
-- Table structure for table `dadabik_table_list`
-- 

INSERT INTO `dadabik_table_list` (`name_table`, `allowed_table`, `enable_insert_table`, `enable_edit_table`, `enable_delete_table`, `enable_details_table`) VALUES ('users', 1, '1', '1', '1', '1');

-- --------------------------------------------------------

-- 
-- Table structure for table `dadabik_users`
-- 

DROP TABLE IF EXISTS `dadabik_users`;
CREATE TABLE `dadabik_users` (
  `name_field` varchar(50) NOT NULL default '',
  `label_field` varchar(255) NOT NULL default '',
  `type_field` enum('text','textarea','rich_editor','password','insert_date','update_date','date','select_single','select_multiple','select_multiple_checkbox','generic_file','image_file','ID_user','unique_ID') NOT NULL default 'text',
  `content_field` enum('alphabetic','alphanumeric','numeric','url','email','html','phone') NOT NULL default 'alphanumeric',
  `present_search_form_field` enum('0','1') NOT NULL default '1',
  `present_results_search_field` enum('0','1') NOT NULL default '1',
  `present_details_form_field` enum('0','1') NOT NULL default '1',
  `present_insert_form_field` enum('0','1') NOT NULL default '1',
  `present_ext_update_form_field` enum('0','1') NOT NULL default '1',
  `required_field` enum('0','1') NOT NULL default '0',
  `check_duplicated_insert_field` enum('0','1') NOT NULL default '0',
  `other_choices_field` enum('0','1') NOT NULL default '0',
  `select_options_field` text NOT NULL,
  `primary_key_field_field` varchar(255) NOT NULL,
  `primary_key_table_field` varchar(255) NOT NULL,
  `primary_key_db_field` varchar(50) NOT NULL,
  `linked_fields_field` text NOT NULL,
  `linked_fields_order_by_field` text NOT NULL,
  `linked_fields_order_type_field` varchar(255) NOT NULL,
  `linked_fields_extra_mysql` varchar(255) NOT NULL,
  `select_type_field` varchar(100) NOT NULL default 'is_equal/contains/starts_with/ends_with/greater_than/less_then',
  `prefix_field` text NOT NULL,
  `default_value_field` text NOT NULL,
  `width_field` varchar(5) NOT NULL,
  `height_field` varchar(5) NOT NULL,
  `maxlength_field` varchar(5) NOT NULL default '100',
  `hint_insert_field` varchar(255) NOT NULL,
  `order_form_field` smallint(6) NOT NULL,
  `separator_field` varchar(2) NOT NULL default '~',
  PRIMARY KEY  (`name_field`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `dadabik_users`
-- 

INSERT INTO `dadabik_users` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('ID_user', 'Unique user ID', 'text', 'numeric', '0', '1', '1', '0', '1', '0', '0', '0', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '', 1, '~');
INSERT INTO `dadabik_users` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('name', 'Full name (first, last)', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '1', '1', '0', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '', 2, '~');
INSERT INTO `dadabik_users` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('md5_password', 'Password (MD5 encrypted)', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '1', '0', '0', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '<a href="javascript:void(generic_js_popup(''pwd.php'','''',400,300))">create MD5-hashed password</a>', 4, '~');
INSERT INTO `dadabik_users` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('status', 'Past or current', 'select_single', 'alphanumeric', '1', '1', '1', '1', '1', '1', '0', '0', '~Current~Past~', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', 'choose one', 6, '~');
INSERT INTO `dadabik_users` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('group', 'User group', 'select_single', 'alphanumeric', '0', '1', '1', '1', '1', '1', '0', '0', '~Normal~Administrator~', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', 'options here should match those in config.php', 5, '~');
INSERT INTO `dadabik_users` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('comment', 'Comment', 'textarea', 'alphanumeric', '0', '1', '1', '1', '1', '0', '0', '0', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '20', '3', '100', '', 7, '~');
INSERT INTO `dadabik_users` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('username', 'Username for logging in', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '1', '1', '0', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', 'single word of 6-12 characters', 3, '~');