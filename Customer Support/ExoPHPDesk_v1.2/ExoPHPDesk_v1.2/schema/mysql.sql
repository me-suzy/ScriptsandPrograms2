DROP TABLE IF EXISTS `phpdesk_admin`
CREATE TABLE `phpdesk_admin` (  `id` int(255) NOT NULL default '0',  `name` varchar(255) NOT NULL default '',  `pass` varchar(255) NOT NULL default '',  `email` varchar(255) NOT NULL default '',  `notify_pm` int(1) NOT NULL default '1',  `notify_response` int(1) NOT NULL default '1',  `notify_ticket` int(1) NOT NULL default '1',  `tppage` int(255) NOT NULL default '25',  `signature` mediumtext NOT NULL,  PRIMARY KEY  (`id`))
DROP TABLE IF EXISTS `phpdesk_groups`
CREATE TABLE `phpdesk_groups` (  `id` int(255) NOT NULL auto_increment,  `name` varchar(255) NOT NULL default '',  `high_tickets` int(50) NOT NULL default '0',  `open_tickets` int(50) NOT NULL default '0',  `total_tickets` int(50) NOT NULL default '0',  PRIMARY KEY  (`id`))
DROP TABLE IF EXISTS `phpdesk_livechat`
CREATE TABLE `phpdesk_livechat` (  `chatid` int(255) NOT NULL default '0',  `timeout` int(255) NOT NULL default '0',  `starter` varchar(255) NOT NULL default '',  `status` varchar(255) NOT NULL default '',  `at` int(32) NOT NULL default '0',  `chatter` varchar(255) NOT NULL default '',  PRIMARY KEY  (`chatid`))
DROP TABLE IF EXISTS `phpdesk_liveonline`
CREATE TABLE `phpdesk_liveonline` (  `id` int(255) NOT NULL default '0',  `ip` varchar(255) NOT NULL default '',  `user` varchar(255) NOT NULL default '',  `negotiated` int(255) NOT NULL default '0',  `timeout` varchar(255) NOT NULL default '',  `utype` varchar(255) NOT NULL default '')
DROP TABLE IF EXISTS `phpdesk_members`
CREATE TABLE `phpdesk_members` (  `id` int(255) NOT NULL default '0',  `username` varchar(255) NOT NULL default '',  `name` varchar(255) NOT NULL default '',  `password` varchar(255) NOT NULL default '',  `email` varchar(255) NOT NULL default '',  `website` varchar(255) NOT NULL default '',  `registered` int(32) NOT NULL default '0',  `notify_pm` int(1) NOT NULL default '1',  `notify_response` int(1) NOT NULL default '1',  `disabled` int(1) NOT NULL default '0',  `validating` int(1) NOT NULL default '0',  `tppage` int(255) NOT NULL default '25',  `FIELDS` mediumtext NOT NULL,  `VALUES` mediumtext NOT NULL,  PRIMARY KEY  (`id`))
DROP TABLE IF EXISTS `phpdesk_pm`
CREATE TABLE `phpdesk_pm` (  `id` int(255) NOT NULL auto_increment,  `sentby` varchar(255) NOT NULL default '',  `sentfor` varchar(255) NOT NULL default '',  `title` varchar(255) NOT NULL default '',  `message` mediumtext NOT NULL,  `read` int(1) NOT NULL default '0',  `sent` int(32) NOT NULL default '0',  PRIMARY KEY  (`id`))
DROP TABLE IF EXISTS `phpdesk_responses`
CREATE TABLE `phpdesk_responses` (  `id` int(255) NOT NULL auto_increment,  `tid` int(255) NOT NULL default '0',  `sname` varchar(255) NOT NULL default '',  `comment` mediumtext NOT NULL,  `posted` varchar(255) NOT NULL default '',  PRIMARY KEY  (`id`))
DROP TABLE IF EXISTS `phpdesk_staff`
CREATE TABLE `phpdesk_staff` (  `id` int(255) NOT NULL default '0',  `username` varchar(255) NOT NULL default '',  `name` varchar(255) NOT NULL default '',  `password` varchar(255) NOT NULL default '',  `email` mediumtext NOT NULL,  `website` mediumtext NOT NULL,  `closed` int(255) NOT NULL default '0',  `responses` int(255) NOT NULL default '0',  `notify_pm` int(1) NOT NULL default '1',  `notify_response` int(1) NOT NULL default '1',  `notify_ticket` int(1) NOT NULL default '1',  `tppage` int(255) NOT NULL default '25',  `groups` varchar(255) NOT NULL default '',  `signature` mediumtext NOT NULL,  `rating` varchar(255) NOT NULL default '',  `edit_ticket` int(1) NOT NULL default '1',  `edit_response` int(1) NOT NULL default '1',  PRIMARY KEY  (`id`))
DROP TABLE IF EXISTS `phpdesk_lostpass`
CREATE TABLE `phpdesk_lostpass` (  `id` int(255) NOT NULL auto_increment,  `key` varchar(255) NOT NULL default '',  `user` varchar(255) NOT NULL default '',  `date` int(32) NOT NULL default '0',  `type` varchar(17) NOT NULL default '',  PRIMARY KEY  (`id`))
DROP TABLE IF EXISTS `phpdesk_fields`
CREATE TABLE `phpdesk_fields` (  `field` varchar(255) NOT NULL default '',  `mandatory` varchar(255) NOT NULL default '',  `type` varchar(100) NOT NULL default '')
DROP TABLE IF EXISTS `phpdesk_kb`
CREATE TABLE `phpdesk_kb` (  `id` int(255) NOT NULL auto_increment,  `title` varchar(255) NOT NULL default '',  `message` mediumtext NOT NULL,  `posted` int(32) NOT NULL default '0',  `view` varchar(10) NOT NULL default '',  `owner` varchar(255) NOT NULL default '',  `group` varchar(255) NOT NULL default '',  PRIMARY KEY  (`id`))
DROP TABLE IF EXISTS `phpdesk_notes`
CREATE TABLE `phpdesk_notes` (  `id` int(255) NOT NULL auto_increment,  `tid` int(255) NOT NULL default '0',  `sname` varchar(255) NOT NULL default '',  `note` mediumtext NOT NULL,  `posted` int(32) NOT NULL default '0',  PRIMARY KEY  (`id`))
DROP TABLE IF EXISTS `phpdesk_kbgroups`
CREATE TABLE `phpdesk_kbgroups` (  `id` int(255) NOT NULL auto_increment,  `name` varchar(255) NOT NULL default '',  `staff` int(1) NOT NULL default '1',  PRIMARY KEY  (`id`))
DROP TABLE IF EXISTS `phpdesk_ratings`
CREATE TABLE `phpdesk_ratings` (  `id` int(255) NOT NULL auto_increment,  `rating` int(1) NOT NULL default '0',  `type` varchar(100) NOT NULL default '',  `uid` int(255) NOT NULL default '0',  `ratedby` varchar(255) NOT NULL default '',  `ip` varchar(100) NOT NULL default '',  PRIMARY KEY  (`id`))
DROP TABLE IF EXISTS `phpdesk_saved`
CREATE TABLE `phpdesk_saved` (  `id` int(255) NOT NULL auto_increment,  `title` varchar(255) NOT NULL default '',  `text` mediumtext NOT NULL,  `type` varchar(60) NOT NULL default '',  PRIMARY KEY  (`id`))
DROP TABLE IF EXISTS `phpdesk_announce`
CREATE TABLE `phpdesk_announce` (  `id` int(255) NOT NULL auto_increment,  `title` varchar(255) NOT NULL default '',  `text` longtext NOT NULL,  `access` varchar(60) NOT NULL default '',  `expire` int(30) NOT NULL default '0',  `added` int(30) NOT NULL default '0',  PRIMARY KEY  (`id`))
DROP TABLE IF EXISTS `phpdesk_configs`
CREATE TABLE `phpdesk_configs` (  `tpldir` varchar(255) NOT NULL default '',  `langfile` varchar(255) NOT NULL default '',  `helpurl` varchar(255) NOT NULL default '',  `sitename` varchar(255) NOT NULL default '',  `remail` varchar(255) NOT NULL default '',  `chatdir` varchar(255) NOT NULL default '',  `registrations` varchar(6) NOT NULL default '',  `mem_serv` int(1) NOT NULL default '0',  `st_announce` int(1) NOT NULL default '1',  `at_allow` int(1) NOT NULL default '1',  `at_dir` varchar(255) NOT NULL default '',  `at_size` int(100) NOT NULL default '0',  `at_ext` mediumtext NOT NULL,  `at_prefix` varchar(255) NOT NULL default '',  `desk_offline` int(1) NOT NULL default '0',  `off_reason` mediumtext NOT NULL,  `mailtype` varchar(4) NOT NULL default '',  `mailhost` varchar(50) NOT NULL default '',  `mailuser` varchar(100) NOT NULL default '',  `mailpass` varchar(100) NOT NULL default '')
DROP TABLE IF EXISTS `phpdesk_diary`
CREATE TABLE `phpdesk_diary` (  `id` int(255) NOT NULL auto_increment,  `admin_user` varchar(255) NOT NULL default '',  `text` longtext NOT NULL,  PRIMARY KEY  (`id`))
DROP TABLE IF EXISTS `phpdesk_servers`
CREATE TABLE `phpdesk_servers` (  `id` int(255) NOT NULL auto_increment,  `ip` varchar(20) NOT NULL default '',  `name` varchar(255) NOT NULL default '',  `down` longtext NOT NULL,  `news` mediumtext NOT NULL,  `web_port` int(6) NOT NULL default '0',  `ssh_port` int(6) NOT NULL default '0',  `telnet_port` int(6) NOT NULL default '0',  `ftp_port` int(6) NOT NULL default '0',  `mysql_port` int(6) NOT NULL default '0',  `smtp_port` int(6) NOT NULL default '0',  `pop3_port` int(6) NOT NULL default '0',  `imap_port` int(6) NOT NULL default '0',  PRIMARY KEY  (`id`))
DROP TABLE IF EXISTS `phpdesk_sessions`
CREATE TABLE `phpdesk_sessions` (  `sid` varchar(32) NOT NULL default '0',  `name` varchar(255) NOT NULL default '',  `pass` varchar(32) NOT NULL default '',  `ip` varchar(100) NOT NULL default '',  `timeout` int(15) NOT NULL default '0',  `type` varchar(100) NOT NULL default '',  UNIQUE KEY `name` (`name`))
DROP TABLE IF EXISTS `phpdesk_tickets`
CREATE TABLE `phpdesk_tickets` (  `id` int(255) NOT NULL auto_increment,  `admin_id` int(255) NOT NULL default '0',  `admin_user` varchar(255) NOT NULL default '',  `admin_email` varchar(255) NOT NULL default '',  `title` varchar(255) NOT NULL default '',  `update` int(32) NOT NULL default '0',  `waiting` varchar(255) NOT NULL default '',  `text` mediumtext NOT NULL,  `status` varchar(6) NOT NULL default '',  `opened` int(32) NOT NULL default '0',  `priority` varchar(6) NOT NULL default '',  `group` varchar(255) NOT NULL default '',  `owner` varchar(255) NOT NULL default '',  `fields` varchar(255) NOT NULL default '',  `values` mediumtext NOT NULL,  `attach` varchar(255) NOT NULL default '',  `replies` int(100) NOT NULL default '0',  PRIMARY KEY  (`id`))
DROP TABLE IF EXISTS `phpdesk_troubles
CREATE TABLE `phpdesk_troubles` (  `id` int(255) NOT NULL auto_increment,  `title` varchar(255) NOT NULL default '',  `text` mediumtext NOT NULL,  `isparent` int(1) NOT NULL default '1',  `parent` int(100) NOT NULL default '0',  `view` varchar(10) NOT NULL default '',  PRIMARY KEY  (`id`))
DROP TABLE IF EXISTS `phpdesk_events`
CREATE TABLE `phpdesk_events` (  `id` int(255) NOT NULL auto_increment,  `title` varchar(255) NOT NULL default '',  `message` mediumtext NOT NULL,  `day` int(10) NOT NULL default '0',  `month` int(10) NOT NULL default '0',  `year` int(10) NOT NULL default '0',  `owner` varchar(255) NOT NULL default '',  `type` varchar(255) NOT NULL default '',  PRIMARY KEY  (`id`))
INSERT INTO `phpdesk_groups` SET `name`='EMAIL'
INSERT INTO `phpdesk_fields` VALUES ('', '', 'Ticket')
INSERT INTO `phpdesk_fields` VALUES ('', '', 'Profile')
INSERT INTO `phpdesk_groups` SET id = '2', name ='Support'