SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `dadabik_others` (
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

insert into `dadabik_others` values('id','ID','text','alphanumeric','1','1','1','0','1','0','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','','','100','','1','~'),
 ('name','Description or name','textarea','alphanumeric','1','1','1','1','1','1','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','25','3','100','','2','~'),
 ('category','Category','select_single','alphanumeric','1','1','1','1','1','1','0','1','~Book~Computer hardware~Manual~Software~','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','25','','100','','3','~'),
 ('usage','Usage','textarea','alphanumeric','1','1','1','1','1','0','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','25','3','100','briefly','4','~'),
 ('quantity','Quantity','textarea','alphanumeric','1','1','1','1','1','0','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','25','2','100','','5','~'),
 ('condition','Condition or status','select_single','alphanumeric','1','1','1','1','1','0','0','1','~Lent~Lost~Misplaced~OK~Stolen~','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','25','','100','','6','~'),
 ('location','Location','textarea','alphanumeric','1','1','1','1','1','0','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','25','3','100','','7','~'),
 ('requirement','Requirement','textarea','alphanumeric','1','1','1','1','1','0','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','25','3','100','','8','~'),
 ('comment','Comment','textarea','alphanumeric','1','1','1','1','1','0','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','25','3','100','','9','~'),
 ('added_by','Added by','select_single','alphanumeric','1','1','1','1','1','1','0','0','','name','users','','name','name','',' WHERE `status` = \'Current\'','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','','','100','','21','~'),
 ('added_on','Added on','insert_date','alphanumeric','1','1','1','0','1','0','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','','','100','','11','~'),
 ('modified_by','Modified by','select_single','alphanumeric','1','1','1','1','1','0','0','0','','name','users','','name','name','',' WHERE `status` = \'Current\'','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','','','100','','23','~'),
 ('modified_on','Modified on','update_date','alphanumeric','1','1','1','0','1','0','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','','','100','','13','~'),
 ('ID_user','ID_user','ID_user','alphanumeric','0','0','0','0','1','0','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','','','100','','25','~');

CREATE TABLE `dadabik_parts` (
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

insert into `dadabik_parts` values('id','ID','text','alphanumeric','1','1','1','0','1','0','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','','','100','','1','~'),
 ('name','Description or name','textarea','alphanumeric','1','1','1','1','1','1','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','25','3','100','','2','~'),
 ('category','Category','select_single','alphanumeric','1','1','1','1','1','1','0','1','~Dionex HPLC~Olympus upright~Voyager MALDI-TOF~','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','25','','100','','3','~'),
 ('usage','Usage','textarea','alphanumeric','1','1','1','1','1','0','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','25','3','100','briefly','4','~'),
 ('quantity','Quantity','textarea','alphanumeric','1','1','1','1','1','0','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','25','2','100','','5','~'),
 ('condition','Condition','select_single','alphanumeric','1','1','1','1','1','0','0','1','~Damaged but working~Damaged and not working~Not working~Out of service~Retired but works~Unused~Working~','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','25','','100','','6','~'),
 ('location','Location','textarea','alphanumeric','1','1','1','1','1','0','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','25','3','100','','7','~'),
 ('requirement','Requirement','textarea','alphanumeric','1','1','1','1','1','0','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','25','3','100','','8','~'),
 ('comment','Comment','textarea','alphanumeric','1','1','1','1','1','0','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','25','3','100','','9','~'),
 ('added_by','Added by','select_single','alphanumeric','1','1','1','1','1','1','0','0','','name','users','','name','name','',' WHERE `status` = \'Current\'','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','','','100','','21','~'),
 ('added_on','Added on','insert_date','alphanumeric','1','1','1','0','1','0','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','','','100','','11','~'),
 ('modified_by','Modified by','select_single','alphanumeric','1','1','1','1','1','0','0','0','','name','users','','name','name','',' WHERE `status` = \'Current\'','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','','','100','','23','~'),
 ('modified_on','Modified on','update_date','alphanumeric','1','1','1','0','1','0','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','','','100','','13','~'),
 ('ID_user','ID_user','ID_user','alphanumeric','0','0','0','0','1','0','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','','','100','','25','~');

insert into `dadabik_table_list` values('others','1','1','1','1','1'),
 ('parts','1','1','1','1','1');

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

insert into `dadabik_users` values('ID_user','Unique user ID','text','numeric','0','1','1','0','1','0','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','','','100','','1','~'),
 ('name','Full name (first, last)','text','alphanumeric','1','1','1','1','1','1','1','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','','','100','','2','~'),
 ('md5_password','Password (MD5 encrypted)','text','alphanumeric','1','1','1','1','1','1','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','','','100','<a href=\"javascript:void(generic_js_popup(\'pwd.php\',\'\',400,300))\">create MD5-hashed password</a>','4','~'),
 ('status','Past or current','select_single','alphanumeric','1','1','1','1','1','1','0','0','~Current~Past~','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','','','100','choose one','6','~'),
 ('group','User group','select_single','alphanumeric','0','1','1','1','1','1','0','0','~Normal~Administrator~','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','','','100','options here should match those in config.php','5','~'),
 ('comment','Comment','textarea','alphanumeric','0','1','1','1','1','0','0','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','20','3','100','','7','~'),
 ('username','Username for logging in','text','alphanumeric','1','1','1','1','1','1','1','0','','','','','','','','','is_equal/contains/starts_with/ends_with/greater_than/less_then','','','','','100','single word of 6-12 characters','3','~');

CREATE TABLE `others` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `category` varchar(128) NOT NULL default '',
  `usage` text NOT NULL,
  `quantity` text NOT NULL,
  `condition` text NOT NULL,
  `location` text NOT NULL,
  `requirement` text NOT NULL,
  `comment` text NOT NULL,
  `added_by` text NOT NULL,
  `added_on` date NOT NULL default '0000-00-00',
  `modified_by` text NOT NULL,
  `modified_on` date NOT NULL default '0000-00-00',
  `ID_user` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `parts` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `category` varchar(128) NOT NULL default '',
  `usage` text NOT NULL,
  `quantity` text NOT NULL,
  `condition` text NOT NULL,
  `location` text NOT NULL,
  `requirement` text NOT NULL,
  `comment` text NOT NULL,
  `added_by` text NOT NULL,
  `added_on` date NOT NULL default '0000-00-00',
  `modified_by` text NOT NULL,
  `modified_on` date NOT NULL default '0000-00-00',
  `ID_user` varchar(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

insert into `parts` values('1','Wireless mouse for Wacom graphic tablet','Computer accessories','for Wacom graphic tablet','1','Working','Computer area drawer','','','Santosh Patnaik','2005-10-05','Avidin','2005-10-29','5');

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `ID_user` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `md5_password` varchar(32) NOT NULL,
  `status` varchar(32) NOT NULL default '',
  `group` varchar(64) NOT NULL default '',
  `comment` varchar(255) NOT NULL default '',
  `username` varchar(32) NOT NULL,
  PRIMARY KEY  (`ID_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

insert into `users` values('1','Common Lab','c833584a58d05124ca69af49805e6c20','Current','Administrator','','root');

DROP TABLE IF EXISTS `users_tab`;

SET FOREIGN_KEY_CHECKS = 1;