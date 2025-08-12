DROP TABLE IF EXISTS `[table_prefix]_external_links`
#-----------------------------------------------
CREATE TABLE `[table_prefix]_external_links` (
  `link_id` int(10) unsigned NOT NULL auto_increment,
  `page_id` int(10) unsigned default NULL,
  `link_url` text,
  `link_target` varchar(255) default NULL,
  `error_code` int(10) unsigned default NULL,
  `processed` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`link_id`),
  KEY `i1` (`link_id`,`page_id`)
)
#-----------------------------------------------
DROP TABLE IF EXISTS `[table_prefix]_google_links` 
#-----------------------------------------------
CREATE TABLE `[table_prefix]_google_links` (
  `host_id` int(10) unsigned NOT NULL auto_increment,
  `host_name` varchar(255) default NULL,
  `links_count` int(10) unsigned default NULL,
  `page_to` int(10) unsigned NOT NULL default '0',
  `processed` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`host_id`),
  KEY `i1` (`page_to`,`host_id`)
)
#-----------------------------------------------
DROP TABLE IF EXISTS `[table_prefix]_internal_links` 
#-----------------------------------------------
CREATE TABLE `[table_prefix]_internal_links` (
  `page_from` int(10) unsigned default NULL,
  `page_to` int(10) unsigned default NULL,
  `link_target` varchar(255) default NULL,
  `processed` tinyint(1) unsigned NOT NULL default '0',
  KEY `i1` (`page_from`,`page_to`)
)
#-----------------------------------------------
DROP TABLE IF EXISTS `[table_prefix]_keywords` 
#-----------------------------------------------
CREATE TABLE `[table_prefix]_keywords` (
  `keyword` varchar(255) NOT NULL default '',
  `found` tinyint(1) unsigned NOT NULL default '0',
  `qnt` int(10) unsigned NOT NULL default '0',
  KEY `k` (`keyword`)
)
#-----------------------------------------------
DROP TABLE IF EXISTS `[table_prefix]_pages_groups` 
#-----------------------------------------------
CREATE TABLE `[table_prefix]_pages_groups` (
  `group_id` int(3) unsigned NOT NULL auto_increment,
  `group_name` varchar(255) default NULL,
  `shedule_id` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`group_id`)
)
#-----------------------------------------------
DROP TABLE IF EXISTS `[table_prefix]_reports` 
#-----------------------------------------------
CREATE TABLE `[table_prefix]_reports` (
  `report_id` int(10) unsigned NOT NULL auto_increment,
  `date` datetime default NULL,
  `scanned` int(10) unsigned NOT NULL default '0',
  `updated` int(10) unsigned NOT NULL default '0',
  `new` int(10) unsigned NOT NULL default '0',
  `bad` int(10) unsigned NOT NULL default '0',
  `not_found` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`report_id`)
)
#-----------------------------------------------
DROP TABLE IF EXISTS `[table_prefix]_rules` 
#-----------------------------------------------
CREATE TABLE `[table_prefix]_rules` (
  `rule_id` int(10) unsigned NOT NULL auto_increment,
  `rule_target` varchar(255) default NULL,
  `rule_rule` varchar(255) default NULL,
  `rule_group` int(11) NOT NULL default '0',
  `rule_query` varchar(255) default NULL,
  PRIMARY KEY  (`rule_id`)
)
#-----------------------------------------------
DROP TABLE IF EXISTS `[table_prefix]_settings` 
#-----------------------------------------------
CREATE TABLE `[table_prefix]_settings` (
  `esskey` varchar(255) NOT NULL default '',
  `essvalue` text NOT NULL,
  PRIMARY KEY  (`esskey`)
)
#-----------------------------------------------
DROP TABLE IF EXISTS `[table_prefix]_shedules` 
#-----------------------------------------------
CREATE TABLE `[table_prefix]_shedules` (
  `shedule_id` int(10) unsigned NOT NULL auto_increment,
  `enable` tinyint(1) unsigned NOT NULL default '1',
  `type` enum('daily','weekly','monthly') default 'daily',
  `daily_hour` tinyint(5) unsigned NOT NULL default '0',
  `daily_minute` tinyint(6) unsigned NOT NULL default '0',
  `weekly_day` varchar(255) NOT NULL default '',
  `weekly_hour` tinyint(5) unsigned NOT NULL default '0',
  `weekly_minute` tinyint(6) unsigned NOT NULL default '0',
  `monthly_day` varchar(255) NOT NULL default '',
  `monthly_hour` tinyint(5) unsigned NOT NULL default '0',
  `monthly_minute` tinyint(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (`shedule_id`)
)
#-----------------------------------------------
DROP TABLE IF EXISTS `[table_prefix]_site_index` 
#-----------------------------------------------
CREATE TABLE `[table_prefix]_site_index` (
  `link_id` int(10) unsigned NOT NULL auto_increment,
  `link_url` text,
  `error_code` int(10) unsigned default NULL,
  `content` text,
  `link_type` tinyint(3) unsigned default NULL,
  `processed` tinyint(3) unsigned default NULL,
  `group_id` int(10) unsigned NOT NULL default '0',
  `first_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `ignore_page` tinyint(1) unsigned NOT NULL default '0',
  `shedule_id` int(10) unsigned NOT NULL default '0',
  `title` text,
  `description` text,
  `keywords` text,
  `links` text,
  `links_hash` text,
  `size` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`link_id`),
  KEY `i1` (`link_url`(255))
)
#-----------------------------------------------
DROP TABLE IF EXISTS `[table_prefix]_start_links` 
#-----------------------------------------------
CREATE TABLE `[table_prefix]_start_links` (
  `start_link_id` int(10) unsigned NOT NULL auto_increment,
  `start_link_url` text,
  `status` tinyint(3) unsigned default NULL,
  PRIMARY KEY  (`start_link_id`)
)
#-----------------------------------------------
INSERT INTO [table_prefix]_settings VALUES("DO_PHRASES", "1");
INSERT INTO [table_prefix]_settings VALUES("SEARCH_TITLE", "1");
INSERT INTO [table_prefix]_settings VALUES("SEARCH_DESCRIPTION", "0");
INSERT INTO [table_prefix]_settings VALUES("SEARCH_KEYWORDS", "0");
INSERT INTO [table_prefix]_settings VALUES("SEARCH_LINKS", "1");
INSERT INTO [table_prefix]_settings VALUES("SEARCH_BODY", "1");
INSERT INTO [table_prefix]_settings VALUES("TITLE_WEIGHT", "50");
INSERT INTO [table_prefix]_settings VALUES("DESCRIPTION_WEIGHT", "10");
INSERT INTO [table_prefix]_settings VALUES("KEYWORDS_WEIGHT", "20");
INSERT INTO [table_prefix]_settings VALUES("LINKS_WEIGHT", "10");
INSERT INTO [table_prefix]_settings VALUES("BODY_WEIGHT", "5");
INSERT INTO [table_prefix]_settings VALUES("SORT_BY", "MATCHES");
INSERT INTO [table_prefix]_settings VALUES("MIN_TERM_LENGTH", "2");
INSERT INTO [table_prefix]_settings VALUES("SHOW_MATCHES", "10");
INSERT INTO [table_prefix]_settings VALUES("SHOW_MATCHES_LENGTH", "100");
INSERT INTO [table_prefix]_settings VALUES("IGNORE_TEXT", "");
INSERT INTO [table_prefix]_settings VALUES("ON_PAGE", "10");