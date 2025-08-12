CREATE TABLE `redcms_bb` (
  `bb_id` int(11) NOT NULL auto_increment,
  `bb_tag` text NOT NULL,
  `bb_code` text NOT NULL,
  `bb_desc` text NOT NULL,
  PRIMARY KEY  (`bb_id`));

INSERT INTO `redcms_bb` VALUES (1, '[b]', '<b>', 'Bold');
INSERT INTO `redcms_bb` VALUES (2, '[/b]', '</b>', 'Close Bold');
INSERT INTO `redcms_bb` VALUES (3, '[i]', '<i>', 'Italic');
INSERT INTO `redcms_bb` VALUES (4, '[/i]', '</i>', 'Italic');
INSERT INTO `redcms_bb` VALUES (5, '[u]', '<u>', 'Underline');
INSERT INTO `redcms_bb` VALUES (6, '[/u]', '</u>', 'Close Underline');
INSERT INTO `redcms_bb` VALUES (7, '[l]', '<div align=''left''>', 'Left Align');
INSERT INTO `redcms_bb` VALUES (8, '[/l]', '</div>', 'Close Left Align');
INSERT INTO `redcms_bb` VALUES (9, '[r]', '<div align=''right''>', 'Right Align');
INSERT INTO `redcms_bb` VALUES (10, '[/r]', '</div>', 'Close Right Align');
INSERT INTO `redcms_bb` VALUES (11, '[c]', '<div align=''center''>', 'Align Center');
INSERT INTO `redcms_bb` VALUES (12, '[/c]', '</div>', 'Close Center Align');
INSERT INTO `redcms_bb` VALUES (14, '[colour="', '<span style=\\"color:', 'Colour');
INSERT INTO `redcms_bb` VALUES (15, '[/colour]', '</span>', 'Close Colour');
INSERT INTO `redcms_bb` VALUES (16, '"]', '">', 'Required');
INSERT INTO `redcms_bb` VALUES (17, '[code]', '<code>', 'Code');
INSERT INTO `redcms_bb` VALUES (18, '[/code]', '</code>', 'Close Code');
INSERT INTO `redcms_bb` VALUES (19, '[quote]', '<table width=100% id="quoteTable"><tr><td id="tdquote">', 'Quote');
INSERT INTO `redcms_bb` VALUES (20, '[/quote]', '</td></tr></table>', 'Close Quote');
INSERT INTO `redcms_bb` VALUES (21, '[profile="', '<a href="profile.php?u=', 'Profile');
INSERT INTO `redcms_bb` VALUES (22, '[/profile]', '</a>', 'Close Profile');
INSERT INTO `redcms_bb` VALUES (23, '[url="', '<a href="', 'Link');
INSERT INTO `redcms_bb` VALUES (24, '[/url]', '</a>', 'Close Link');

CREATE TABLE `redcms_files` (
  `file_id` int(11) NOT NULL auto_increment,
  `cat_id` int(11) NOT NULL default '0',
  `file_name` text NOT NULL,
  `file_desc` text NOT NULL,
  `file_link` text NOT NULL,
  `file_size` double NOT NULL default '0',
  `file_downloads` int(11) NOT NULL default '0',
  `file_date` date NOT NULL default '0000-00-00',
  `file_time` time NOT NULL default '00:00:00',
  PRIMARY KEY  (`file_id`));

CREATE TABLE `redcms_file_categories` (
  `cat_id` int(11) NOT NULL auto_increment,
  `cat_name` text NOT NULL,
  PRIMARY KEY  (`cat_id`));

CREATE TABLE `redcms_hits` (
  `hit_id` int(11) NOT NULL auto_increment,
  `counter_id` int(11) NOT NULL default '0',
  `hit_ip` text NOT NULL,
  `hit_date` date NOT NULL default '0000-00-00',
  `hit_time` time NOT NULL default '00:00:00',
  PRIMARY KEY  (`hit_id`));

CREATE TABLE `redcms_hit_counters` (
  `counter_id` int(11) NOT NULL auto_increment,
  `counter_name` text NOT NULL,
  `counter_site` text NOT NULL,
  PRIMARY KEY  (`counter_id`));

CREATE TABLE `redcms_journal` (
  `journal_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `journal_title` text NOT NULL,
  `journal_text` text NOT NULL,
  `journal_rdate` text NOT NULL,
  `journal_date` date NOT NULL default '0000-00-00',
  `journal_time` time NOT NULL default '00:00:00',
  PRIMARY KEY  (`journal_id`));

CREATE TABLE `redcms_messenger` (
  `message_id` int(11) NOT NULL auto_increment,
  `from_user_id` int(11) NOT NULL default '0',
  `to_user_id` int(11) NOT NULL default '0',
  `message_subject` text NOT NULL,
  `message_text` text NOT NULL,
  `message_date` date NOT NULL default '0000-00-00',
  `message_time` time NOT NULL default '00:00:00',
  `message_read` text NOT NULL,
  PRIMARY KEY  (`message_id`));

CREATE TABLE `redcms_news` (
  `news_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `news_title` text NOT NULL,
  `news_text` text NOT NULL,
  `news_rdate` text NOT NULL,
  `news_date` date NOT NULL default '0000-00-00',
  `news_time` time NOT NULL default '00:00:00',
  PRIMARY KEY  (`news_id`));

CREATE TABLE `redcms_themes` (
  `theme_id` int(11) NOT NULL auto_increment,
  `theme_name` text NOT NULL,
  `theme_path` text NOT NULL,
  PRIMARY KEY  (`theme_id`));

INSERT INTO `redcms_themes` VALUES (1, 'Kill Bill', 'redcms_killbill.css');
INSERT INTO `redcms_themes` VALUES (2, 'Grey', 'redcms_grey.css');

CREATE TABLE `redcms_users` (
  `user_id` int(11) NOT NULL auto_increment,
  `user_uname` text NOT NULL,
  `user_password` text NOT NULL,
  `user_level` int(11) NOT NULL default '0',
  `user_name` text NOT NULL,
  `user_email` text NOT NULL,
  `user_location` text NOT NULL,
  `user_gender` text NOT NULL,
  `user_dob` date NOT NULL default '0000-00-00',
  `user_site` text NOT NULL,
  `user_msn` text NOT NULL,
  `user_aim` text NOT NULL,
  `user_yahoo` text NOT NULL,
  `user_icq` text NOT NULL,
  `user_joined_date` date NOT NULL default '0000-00-00',
  `user_joined_time` time NOT NULL default '00:00:00',
  `user_active` text NOT NULL,
  `user_key` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_id`));

CREATE TABLE `redcms_user_levels` (
  `level_id` int(11) NOT NULL auto_increment,
  `level_name` text NOT NULL,
  PRIMARY KEY  (`level_id`));

INSERT INTO `redcms_user_levels` VALUES (1, 'Basic');
INSERT INTO `redcms_user_levels` VALUES (2, '2');
INSERT INTO `redcms_user_levels` VALUES (3, '3');
INSERT INTO `redcms_user_levels` VALUES (4, '4');
INSERT INTO `redcms_user_levels` VALUES (5, 'Medium');
INSERT INTO `redcms_user_levels` VALUES (6, '6');
INSERT INTO `redcms_user_levels` VALUES (7, '7');
INSERT INTO `redcms_user_levels` VALUES (8, '8');
INSERT INTO `redcms_user_levels` VALUES (9, '9');
INSERT INTO `redcms_user_levels` VALUES (10, 'Admin');

CREATE TABLE `redcms_user_themes` (
  `user_id` int(11) NOT NULL auto_increment,
  `theme_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_id`));
        