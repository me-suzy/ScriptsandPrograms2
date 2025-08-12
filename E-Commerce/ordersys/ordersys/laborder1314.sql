-- phpMyAdmin SQL Dump
-- version 2.6.3-pl1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Aug 27, 2005 at 08:46 PM
-- Server version: 4.1.12
-- PHP Version: 5.0.4
-- 
-- Database: `laborder`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `users_tab`
-- 

CREATE TABLE IF NOT EXISTS `users_tab` (
  `ID_user` int(10) unsigned NOT NULL auto_increment,
  `user_type_user` varchar(50) NOT NULL default '',
  `username_user` varchar(50) NOT NULL default '',
  `password_user` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`ID_user`),
  UNIQUE KEY `username_user` (`username_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `users_tab`
-- 

INSERT INTO `users_tab` (`ID_user`, `user_type_user`, `username_user`, `password_user`) VALUES (1, 'admin', 'root', 'c833584a58d05124ca69af49805e6c20');

-- --------------------------------------------------------

-- 
-- Table structure for table `dadabik_item`
-- 

DROP TABLE IF EXISTS `dadabik_item`;
CREATE TABLE IF NOT EXISTS `dadabik_item` (
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
  `primary_key_field_field` varchar(255) NOT NULL default '',
  `primary_key_table_field` varchar(255) NOT NULL default '',
  `primary_key_db_field` varchar(50) NOT NULL default '',
  `linked_fields_field` text NOT NULL,
  `linked_fields_order_by_field` text NOT NULL,
  `linked_fields_order_type_field` varchar(255) NOT NULL default '',
  `linked_fields_extra_mysql` varchar(255) NOT NULL default '',
  `select_type_field` varchar(100) NOT NULL default 'is_equal/contains/starts_with/ends_with/greater_than/less_then',
  `prefix_field` text NOT NULL,
  `default_value_field` text NOT NULL,
  `width_field` varchar(5) NOT NULL default '',
  `height_field` varchar(5) NOT NULL default '',
  `maxlength_field` varchar(5) NOT NULL default '100',
  `hint_insert_field` varchar(255) NOT NULL default '',
  `order_form_field` smallint(6) NOT NULL default '0',
  `separator_field` varchar(2) NOT NULL default '~',
  PRIMARY KEY  (`name_field`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `dadabik_item`
-- 

INSERT INTO `dadabik_item` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('ID', 'ID', 'text', 'numeric', '0', '0', '1', '0', '1', '0', '0', '0', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '', 1, '~');
INSERT INTO `dadabik_item` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('Category', 'Category', 'select_single', 'alphanumeric', '1', '1', '1', '1', '1', '1', '0', '1', '~Antibody~Chemicals~Cleanliness~Computer-related~Electrical-electronic~Equipment-related~Glassware~Glyco~Misc.~Mol-bio~Non-culture-plasticware~Proteins-and-related~Radioactivity~Stationery~Tissue-culture~', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '30', '', 2, '~');
INSERT INTO `dadabik_item` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('Name', 'Description', 'textarea', 'alphanumeric', '1', '1', '1', '1', '1', '1', '0', '1', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '30', '3', '', 'Keyword first, like ''Tips, 10-100ul''', 3, '~');
INSERT INTO `dadabik_item` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('Size', 'Unit size', 'text', 'alphanumeric', '0', '0', '1', '1', '1', '0', '0', '', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '30', 'case, 500/pk, each, 1ml, etc.', 4, '~');
INSERT INTO `dadabik_item` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('Manufacturer', 'Manufacturer', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '0', '0', '1', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '30', 'Optional', 9, '~');
INSERT INTO `dadabik_item` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('Manufacturer_cat_no', 'Man. cat. no.', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '0', '0', '', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '30', 'Optional', 10, '~');
INSERT INTO `dadabik_item` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('Vendor', 'Vendor', 'select_single', 'alphanumeric', '1', '1', '1', '1', '1', '1', '0', '0', '', 'Name', 'vendor', '', 'Name', 'Name', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '50', 'May differ from manufacturer', 5, '~');
INSERT INTO `dadabik_item` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('Vendor_cat_no', 'Vendor cat. no.', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '1', '0', '', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '30', '', 6, '~');
INSERT INTO `dadabik_item` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('Price', 'Price per unit size', 'text', 'numeric', '0', '0', '1', '1', '1', '0', '0', '1', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '10', 'Don''t type currency', 7, '~');
INSERT INTO `dadabik_item` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('Extra', 'Any comment', 'textarea', 'alphanumeric', '0', '0', '1', '1', '1', '0', '0', '', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '30', '3', '', '', 8, '~');
INSERT INTO `dadabik_item` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('update_date', 'Updated', 'update_date', 'alphanumeric', '1', '1', '1', '0', '1', '0', '0', '1', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '', 11, '~');
INSERT INTO `dadabik_item` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('insert_date', 'Added', 'insert_date', 'alphanumeric', '1', '1', '1', '0', '1', '0', '0', '1', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '', 12, '~');
INSERT INTO `dadabik_item` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('order_date', 'Last ordered', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '0', '0', '0', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '0000-00-00', '10', '', '10', 'yyyy-mm-dd format', 13, '~');

-- --------------------------------------------------------

-- 
-- Table structure for table `dadabik_order`
-- 

DROP TABLE IF EXISTS `dadabik_order`;
CREATE TABLE IF NOT EXISTS `dadabik_order` (
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
  `primary_key_field_field` varchar(255) NOT NULL default '',
  `primary_key_table_field` varchar(255) NOT NULL default '',
  `primary_key_db_field` varchar(50) NOT NULL default '',
  `linked_fields_field` text NOT NULL,
  `linked_fields_order_by_field` text NOT NULL,
  `linked_fields_order_type_field` varchar(255) NOT NULL default '',
  `linked_fields_extra_mysql` varchar(255) NOT NULL default '',
  `select_type_field` varchar(100) NOT NULL default 'is_equal/contains/starts_with/ends_with/greater_than/less_then',
  `prefix_field` text NOT NULL,
  `default_value_field` text NOT NULL,
  `width_field` varchar(5) NOT NULL default '',
  `height_field` varchar(5) NOT NULL default '',
  `maxlength_field` varchar(5) NOT NULL default '100',
  `hint_insert_field` varchar(255) NOT NULL default '',
  `order_form_field` smallint(6) NOT NULL default '0',
  `separator_field` varchar(2) NOT NULL default '~',
  PRIMARY KEY  (`name_field`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `dadabik_order`
-- 

INSERT INTO `dadabik_order` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('order_id', 'Order ID', 'text', 'numeric', '1', '1', '1', '0', '1', '0', '0', '0', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', 'Database ID', 1, '~');
INSERT INTO `dadabik_order` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('description', 'Order summary', 'textarea', 'alphanumeric', '1', '1', '1', '1', '1', '0', '0', '0', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '50', '4', '', '', 2, '~');
INSERT INTO `dadabik_order` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('total_amount', 'Total', 'text', 'numeric', '1', '1', '1', '1', '1', '0', '0', '0', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', 'Do not type in currency (e.g., $)', 3, '~');
INSERT INTO `dadabik_order` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('ordered_by', 'Ordered by', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '0', '0', '0', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '', 5, '~');
INSERT INTO `dadabik_order` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('comment', 'Comment', 'textarea', 'alphanumeric', '1', '1', '1', '1', '1', '0', '0', '0', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '50', '3', '', '', 10, '~');
INSERT INTO `dadabik_order` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('cost_reduce', '-ve cost adjustment', 'text', 'numeric', '1', '1', '1', '1', '1', '0', '0', '0', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', 'Do not type currency (e.g., $)', 8, '~');
INSERT INTO `dadabik_order` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('cost_add', '+ve cost adjustment', 'text', 'numeric', '1', '1', '1', '1', '1', '0', '0', '0', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', 'Extra, for increased amount', 9, '~');
INSERT INTO `dadabik_order` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('status', 'Status', 'select_single', 'alphanumeric', '1', '1', '1', '1', '1', '0', '0', '0', '~Ordered~Partly ordered/cancelled~Cancelled~On hold~', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '', 6, '~');
INSERT INTO `dadabik_order` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('modified_date', 'Modified date', 'update_date', 'alphanumeric', '1', '1', '1', '0', '1', '0', '0', '0', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '', 11, '~');
INSERT INTO `dadabik_order` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('ordered_date', 'Date of order', 'insert_date', 'alphanumeric', '1', '1', '1', '0', '1', '0', '0', '0', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '', 4, '~');
INSERT INTO `dadabik_order` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('reception_status', 'Reception status', 'select_single', 'alphanumeric', '1', '1', '1', '1', '1', '0', '0', '1', '~Received~Partly received~Not received~', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '', 7, '~');

-- --------------------------------------------------------

-- 
-- Table structure for table `dadabik_table_list`
-- 

DROP TABLE IF EXISTS `dadabik_table_list`;
CREATE TABLE IF NOT EXISTS `dadabik_table_list` (
  `name_table` varchar(255) NOT NULL default '',
  `allowed_table` tinyint(4) NOT NULL default '0',
  `enable_insert_table` varchar(5) NOT NULL default '',
  `enable_edit_table` varchar(5) NOT NULL default '',
  `enable_delete_table` varchar(5) NOT NULL default '',
  `enable_details_table` varchar(5) NOT NULL default '',
  PRIMARY KEY  (`name_table`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `dadabik_table_list`
-- 

INSERT INTO `dadabik_table_list` (`name_table`, `allowed_table`, `enable_insert_table`, `enable_edit_table`, `enable_delete_table`, `enable_details_table`) VALUES ('vendor', 1, '1', '1', '1', '1');
INSERT INTO `dadabik_table_list` (`name_table`, `allowed_table`, `enable_insert_table`, `enable_edit_table`, `enable_delete_table`, `enable_details_table`) VALUES ('item', 1, '1', '1', '1', '1');
INSERT INTO `dadabik_table_list` (`name_table`, `allowed_table`, `enable_insert_table`, `enable_edit_table`, `enable_delete_table`, `enable_details_table`) VALUES ('order', 1, '1', '1', '1', '1');

-- --------------------------------------------------------

-- 
-- Table structure for table `dadabik_vendor`
-- 

DROP TABLE IF EXISTS `dadabik_vendor`;
CREATE TABLE IF NOT EXISTS `dadabik_vendor` (
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
  `primary_key_field_field` varchar(255) NOT NULL default '',
  `primary_key_table_field` varchar(255) NOT NULL default '',
  `primary_key_db_field` varchar(50) NOT NULL default '',
  `linked_fields_field` text NOT NULL,
  `linked_fields_order_by_field` text NOT NULL,
  `linked_fields_order_type_field` varchar(255) NOT NULL default '',
  `linked_fields_extra_mysql` varchar(255) NOT NULL default '',
  `select_type_field` varchar(100) NOT NULL default 'is_equal/contains/starts_with/ends_with/greater_than/less_then',
  `prefix_field` text NOT NULL,
  `default_value_field` text NOT NULL,
  `width_field` varchar(5) NOT NULL default '',
  `height_field` varchar(5) NOT NULL default '',
  `maxlength_field` varchar(5) NOT NULL default '100',
  `hint_insert_field` varchar(255) NOT NULL default '',
  `order_form_field` smallint(6) NOT NULL default '0',
  `separator_field` varchar(2) NOT NULL default '~',
  PRIMARY KEY  (`name_field`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `dadabik_vendor`
-- 

INSERT INTO `dadabik_vendor` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('Vendor_ID', 'Vendor ID', 'text', 'numeric', '0', '0', '1', '0', '1', '0', '0', '0', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', 'Database ID', 5, '~');
INSERT INTO `dadabik_vendor` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('Name', 'Name of vendor', 'textarea', 'alphanumeric', '1', '1', '1', '1', '1', '0', '1', '1', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '50', '2', '', '', 1, '~');
INSERT INTO `dadabik_vendor` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('Phone', 'Phone', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '0', '0', '1', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '-', '', '', '50', '', 2, '~');
INSERT INTO `dadabik_vendor` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('Fax', 'Fax', 'text', 'alphanumeric', '1', '1', '1', '1', '1', '0', '0', '1', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '-', '', '', '50', '', 3, '~');
INSERT INTO `dadabik_vendor` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('Address', 'Address', 'textarea', 'alphanumeric', '1', '1', '1', '1', '1', '0', '0', '1', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '-', '50', '3', '', '', 4, '~');
INSERT INTO `dadabik_vendor` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('update_date', 'Updated', 'update_date', 'alphanumeric', '1', '1', '1', '0', '1', '0', '0', '1', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '', 6, '~');
INSERT INTO `dadabik_vendor` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('insert_date', 'Added', 'insert_date', 'alphanumeric', '0', '0', '1', '0', '1', '0', '0', '1', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '', '', '100', '', 7, '~');
INSERT INTO `dadabik_vendor` (`name_field`, `label_field`, `type_field`, `content_field`, `present_search_form_field`, `present_results_search_field`, `present_details_form_field`, `present_insert_form_field`, `present_ext_update_form_field`, `required_field`, `check_duplicated_insert_field`, `other_choices_field`, `select_options_field`, `primary_key_field_field`, `primary_key_table_field`, `primary_key_db_field`, `linked_fields_field`, `linked_fields_order_by_field`, `linked_fields_order_type_field`, `linked_fields_extra_mysql`, `select_type_field`, `prefix_field`, `default_value_field`, `width_field`, `height_field`, `maxlength_field`, `hint_insert_field`, `order_form_field`, `separator_field`) VALUES ('website', 'Website', 'text', 'alphanumeric', '0', '0', '1', '1', '1', '0', '0', '0', '', '', '', '', '', '', '', '', 'is_equal/contains/starts_with/ends_with/greater_than/less_then', '', '', '40', '', '100', '', 8, '~');
