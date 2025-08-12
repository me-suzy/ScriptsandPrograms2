CREATE TABLE IF NOT EXISTS `dir_articles` (
  `articles_id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `body` mediumtext NOT NULL,
  `enterdate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`articles_id`)
) TYPE=MyISAM AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `dir_b_categories` (
  `ban_id` int(11) NOT NULL default '0',
  `ban_category` int(11) NOT NULL default '0',
  UNIQUE KEY `ban_category` (`ban_category`,`ban_id`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS `dir_banners` (
  `ban_id` int(11) NOT NULL auto_increment,
  `ban_text` text NOT NULL,
  `ban_start` date default NULL,
  `ban_end` date default NULL,
  `ban_sponsor` enum('Y','N') NOT NULL default 'N',
  PRIMARY KEY  (`ban_id`)
) TYPE=MyISAM AUTO_INCREMENT=90 ;

CREATE TABLE IF NOT EXISTS `dir_categories` (
  `cat_id` int(11) NOT NULL auto_increment,
  `cat_parent` varchar(120) NOT NULL default '',
  `cat_child` varchar(120) NOT NULL default '',
  PRIMARY KEY  (`cat_id`)
) TYPE=MyISAM AUTO_INCREMENT=134 ;

CREATE TABLE IF NOT EXISTS `dir_mail_list` (
  `mail_address` varchar(75) NOT NULL default '',
  `time_added` timestamp(14) NOT NULL,
  `auth_string` varchar(12) NOT NULL default '',
  `authorised` enum('Y','N') NOT NULL default 'Y',
  PRIMARY KEY  (`mail_address`),
  KEY `mail_address` (`mail_address`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS `dir_searchlogs` (
  `src_id` int(11) unsigned NOT NULL auto_increment,
  `src_string` varchar(250) default NULL,
  PRIMARY KEY  (`src_id`)
) TYPE=MyISAM AUTO_INCREMENT=2123 ;

CREATE TABLE IF NOT EXISTS `dir_site_list` (
  `site_id` int(11) NOT NULL auto_increment,
  `site_name` varchar(120) NOT NULL default '',
  `site_description` text NOT NULL,
  `site_email` varchar(120) NOT NULL default '',
  `site_url` varchar(120) NOT NULL default '',
  `site_linkback` varchar(250) NOT NULL default '',
  `site_category` varchar(120) NOT NULL default '',
  `site_live` enum('Y','N') NOT NULL default 'N',
  `site_sponsor` enum('Y','N') NOT NULL default 'N',
  `cat_id` int(11) NOT NULL default '0',
  `clicks_counter` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`site_id`),
  FULLTEXT KEY `site_name` (`site_name`,`site_description`,`site_category`,`site_url`)
) TYPE=MyISAM AUTO_INCREMENT=2057 ;

CREATE TABLE IF NOT EXISTS `dir_voting` (
  `siteid` int(11) NOT NULL default '0',
  `votes` int(11) default '0',
  `value` int(11) default '0',
  `average` decimal(3,2) unsigned default '0.00',
  PRIMARY KEY  (`siteid`)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS `dir_voting_history` (
  `site_id` int(11) NOT NULL default '0',
  `IP` varchar(255) NOT NULL default '',
  `lastvote` datetime default NULL,
  PRIMARY KEY  (`site_id`,`IP`)
) TYPE=MyISAM;

INSERT INTO `dir_categories` VALUES (1, 'Generic Category', 'Test Sub-category 1');
INSERT INTO `dir_categories` VALUES (2, 'Generic Category', 'Test Sub-category 2');
INSERT INTO `dir_categories` VALUES (3, 'Generic Category', 'Test Sub-category 3');

CREATE TABLE `dir_template` (
  `template_id` int(11) NOT NULL auto_increment,
  `template_name` varchar(255) NOT NULL default '',
  `template_value` mediumtext NOT NULL,
  `template_variables` mediumtext NOT NULL,
  `template_type` enum('text','html') NOT NULL default 'text',
  `template_title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`template_id`),
  UNIQUE KEY `config_name` (`template_name`)
) TYPE=MyISAM;


INSERT INTO `dir_template` VALUES (1, 'Add_Url_Email', 'Dear Webmaster\r\nYour request to be added to the directory {CONST_LINK_SITE} has been received.\r\nThe following data has been submitted for review:\r\nSite Name: {sitename}\r\nCategory: {category}\r\nDescription: {description}\r\nEmail: {email}\r\nURL: {url}\r\nLink URL: {linkurl}\r\n\r\nIf you have not already placed a link back, please use the following code placed not further than one click from your homepage:\r\n\r\n<p align=\'center\'><font face=\'Verdana\' size=\'1\'><a href=\'{CONST_LINK_ROOT}\'><img ALT=\'Click here\' border=\'0\' src=\'{CONST_LINK_ROOT}/images/ondating_1.gif\' width=\'120\' height=\'60\'><br>{CONST_LINK_SITE}</a></font></p>\r\n\r\n\r\nIf any of this information is incorrect then please respond to this email with the corrections. Your site will be reviewed for inclusion with a couple of days.\r\n\r\n\r\nAdministrator', 'CONST_LINK_SITE;sitename;category;description;email;url;linkurl;CONST_LINK_ROOT;', 'text', 'Link Request Received');
INSERT INTO `dir_template` VALUES (3, 'Add_Url_Accept_Email', '<p><font face=\'Verdana\' size=\'2\'>Dear webmaster<br><br>\r\n                            Your link {txtUrl} has been added to {CONST_LINK_ROOT} in the <b> {main_cat} </b> category and cross-referenced under <b>{sub_cat}</b>. \r\n                            You have also been included in the search engine. \r\n                            Links are checked once a month so please do not remove the link to our site or you may be removed without notice.<br><br>\r\n                            Thank you for advertising with us, we appreciate it and we wish you all the best with your site.<br><br>\r\n                            Regards<br><br>\r\n                            Administrator</font></p>', 'txtUrl;main_cat;CONST_LINK_ROOT;sub_cat;CONST_LINK_SITE;sitename;category;description;email;url;linkurl;', 'html', 'Link Request Approved');
INSERT INTO `dir_template` VALUES (4, 'Add_Url_Reject_Email', '<font face=\'Verdana\' size=\'2\'>Dear webmaster<br><br>\r\n                    Unfortunately we are unable to add your link to our directory/search engine for the following reason(s):<p><i>{lstReason}</i></p>\r\n				    <p><i>{txtReason}</i></p>\r\n                    Please resubmit your site when it meets the criteria and we will be happy to include you in the directory and search engine.<br><br>\r\n                    Regards<br><br>\r\n                    Administrator</font>', 'txtReason;lstReason;CONST_LINK_SITE;sitename;category;description;email;url;linkurl;CONST_LINK_ROOT;', 'html', 'Link Request Rejected');
    
        