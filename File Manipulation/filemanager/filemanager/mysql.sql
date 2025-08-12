DROP TABLE IF EXISTS `relation_file2category`;
CREATE TABLE `relation_file2category` (
  `file_id` int(4) unsigned NOT NULL default '0',
  `category_id` int(4) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

DROP TABLE IF EXISTS `relation_group2category`;
CREATE TABLE `relation_group2category` (
  `group_id` int(4) unsigned NOT NULL default '0',
  `category_id` int(4) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

DROP TABLE IF EXISTS `relation_user2group`;
CREATE TABLE `relation_user2group` (
  `user_id` int(4) unsigned NOT NULL default '0',
  `group_id` int(4) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

DROP TABLE IF EXISTS `user_category`;
CREATE TABLE `user_category` (
  `category_id` int(4) unsigned NOT NULL auto_increment,
  `category_subof` int(4) unsigned default '0',
  `category_name` varchar(128) collate latin1_german1_ci default NULL,
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

DROP TABLE IF EXISTS `user_files`;
CREATE TABLE `user_files` (
  `file_id` int(4) unsigned NOT NULL auto_increment,
  `file_name` varchar(128) collate latin1_german1_ci default NULL,
  `file_desc` text collate latin1_german1_ci,
  `file_source` varchar(128) collate latin1_german1_ci default NULL,
  `file_date` datetime default NULL,
  `file_size` int(32) unsigned NOT NULL default '0',
  PRIMARY KEY  (`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

DROP TABLE IF EXISTS `user_group`;
CREATE TABLE `user_group` (
  `group_id` int(4) unsigned NOT NULL auto_increment,
  `group_name` varchar(128) collate latin1_german1_ci default NULL,
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

DROP TABLE IF EXISTS `user_profile`;
CREATE TABLE `user_profile` (
  `user_id` int(4) unsigned NOT NULL auto_increment,
  `user_role` enum('user','admin') collate latin1_german1_ci default 'user',
  `user_username` varchar(128) collate latin1_german1_ci default NULL,
  `user_password` varchar(128) collate latin1_german1_ci default NULL,
  `user_email` varchar(128) collate latin1_german1_ci default NULL,
  `user_form` enum('mr','mrs') collate latin1_german1_ci default 'mr',
  `user_firstname` varchar(128) collate latin1_german1_ci NOT NULL default '',
  `user_lastname` varchar(128) collate latin1_german1_ci NOT NULL default '',
  `user_company` varchar(128) collate latin1_german1_ci default NULL,
  `user_registered` datetime default '0000-00-00 00:00:00',
  `user_status` enum('active','inactive') collate latin1_german1_ci default 'active',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

INSERT INTO `user_profile` (`user_id`, `user_role`, `user_username`, `user_password`, `user_email`, `user_form`, `user_firstname`, `user_lastname`, `user_company`, `user_registered`, `user_status`) VALUES("1", "admin", "admin", "21232f297a57a5a743894a0e4a801fc3", "deine@email.com", "mr", "Vorname", "Nachname:", "Firma", "2005-08-04 09:57:34", "active");

DROP TABLE IF EXISTS `website_config`;
CREATE TABLE `website_config` (
  `config_key` varchar(128) collate latin1_german1_ci default NULL,
  `config_value` varchar(128) collate latin1_german1_ci default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

INSERT INTO `website_config` (`config_key`, `config_value`) VALUES("websiteName", "FileManager");
INSERT INTO `website_config` (`config_key`, `config_value`) VALUES("websiteUrl", "http://www.yourhost.com/filemanager/");
INSERT INTO `website_config` (`config_key`, `config_value`) VALUES("websitePath", "/home/www/public_html/filemanager/");
INSERT INTO `website_config` (`config_key`, `config_value`) VALUES("websiteEmail", "you@email.com");
INSERT INTO `website_config` (`config_key`, `config_value`) VALUES("ftpHost", "ftp.yourhost.com");
INSERT INTO `website_config` (`config_key`, `config_value`) VALUES("ftpDataPath", "/public_html/filemanager/data/");
INSERT INTO `website_config` (`config_key`, `config_value`) VALUES("ftpUsername", "username");
INSERT INTO `website_config` (`config_key`, `config_value`) VALUES("ftpPassword", "password");
INSERT INTO `website_config` (`config_key`, `config_value`) VALUES("executionTime", "false");