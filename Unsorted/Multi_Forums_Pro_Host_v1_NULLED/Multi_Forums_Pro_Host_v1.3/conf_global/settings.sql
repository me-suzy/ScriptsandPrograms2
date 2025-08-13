# phpMyAdmin SQL Dump
# version 2.5.3
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Sep 10, 2003 at 10:08 PM
# Server version: 3.23.56
# PHP Version: 4.1.2
# 
# Database : `freeforums_db`
# 

# --------------------------------------------------------

#
# Table structure for table `multiforums_cats`
#

CREATE TABLE `multiforums_cats` (
  `id` int(11) NOT NULL auto_increment,
  `name` longtext NOT NULL,
  `desc` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM ;

#
# Dumping data for table `multiforums_cats`
#

INSERT INTO `multiforums_cats` VALUES (1, 'Art & Literature', 'Books, Poems, Essays, Artwork...');
INSERT INTO `multiforums_cats` VALUES (2, 'Automobiles', 'Racing, Car Care, Car Enthusiasts...');
INSERT INTO `multiforums_cats` VALUES (3, 'Computers & Internet', 'General Computer related forums.');
INSERT INTO `multiforums_cats` VALUES (4, 'Education', 'Education, Teaching, School Forums...');
INSERT INTO `multiforums_cats` VALUES (5, 'Family & Parents', 'Pets, Family Forums, Parenting help...');
INSERT INTO `multiforums_cats` VALUES (6, 'Forum Services', 'Skinning, Graphics, and other Resources for Forums.');
INSERT INTO `multiforums_cats` VALUES (7, 'Gaming: Clans', 'Clans and Guilds for online gaming.');
INSERT INTO `multiforums_cats` VALUES (8, 'Gaming: Console', 'Playstation, GameCube, Xbox, and Gameboy.');
INSERT INTO `multiforums_cats` VALUES (9, 'Gaming: General', 'Computer Games');
INSERT INTO `multiforums_cats` VALUES (10, 'Gaming: RPG', 'Role Playing Games.');
INSERT INTO `multiforums_cats` VALUES (11, 'Gaming: Specific Game', 'Forums dedicated to as single game.');
INSERT INTO `multiforums_cats` VALUES (12, 'Graphics & Design', 'Web Design and Graphics Design');
INSERT INTO `multiforums_cats` VALUES (13, 'Health & Medical', 'Dental, Healthcare, Beauty... ');
INSERT INTO `multiforums_cats` VALUES (14, 'Hobbies', 'Hobbies and Crafts');
INSERT INTO `multiforums_cats` VALUES (15, 'Macintosh', 'Mac OS 9, Mac OS X, Apple, Macintosh Gaming, Rumors, etc.');
INSERT INTO `multiforums_cats` VALUES (16, 'Music', 'CDs, Bands, Music groups, etc');
INSERT INTO `multiforums_cats` VALUES (17, 'News & Politics', 'Political Debating and Current Events');
INSERT INTO `multiforums_cats` VALUES (18, 'Online Communities', 'Communities for chatting and getting together.');
INSERT INTO `multiforums_cats` VALUES (19, 'Outdoors & Nature', 'Gardening, Outdoor Adventures, Camping, Hiking, Animals...');
INSERT INTO `multiforums_cats` VALUES (20, 'Programming', 'HTML, CSS, Javascript, C/C++, PHP, Perl...');
INSERT INTO `multiforums_cats` VALUES (21, 'Religious', 'Religious Groups');
INSERT INTO `multiforums_cats` VALUES (22, 'Sports', 'Golf, Football, Basketball, Baseball...');
INSERT INTO `multiforums_cats` VALUES (23, 'TV & Movies', 'Television and Movies');
INSERT INTO `multiforums_cats` VALUES (24, 'Teens', 'Teen Issues');
INSERT INTO `multiforums_cats` VALUES (25, 'Windows', 'Microsoft Windows 9x/2000/XP, Support, General Chat etc...');

#
# Table structure for table `multiforums_forums`
#

CREATE TABLE `multiforums_forums` (
  `id` int(11) NOT NULL auto_increment,
  `access_name` varchar(25) NOT NULL default '',
  `board_start` int(11) NOT NULL default '0',
  `forum_name` longtext NOT NULL,
  `cat` int(11) NOT NULL default '0',
  `cat_hits` int(11) NOT NULL default '0',
  `admin_email` longtext NOT NULL,
  `online` int(1) NOT NULL default '1',
  `normal_admin_pw` longtext NOT NULL,
  `c_posts` int(11) NOT NULL default '0',
  `c_members` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `access_name` (`access_name`)
) TYPE=MyISAM ;

#
# Table structure for table `multiforums_templates`
#

CREATE TABLE `multiforums_templates` (
  `tmid` int(10) NOT NULL auto_increment,
  `template` mediumtext,
  `name` varchar(128) default NULL,
  PRIMARY KEY  (`tmid`)
) TYPE=MyISAM ;

#
# Dumping data for table `multiforums_templates`
#

INSERT INTO `multiforums_templates` VALUES (1, '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> \r\n<html xml:lang="en" lang="en" xmlns="http://www.w3.org/1999/xhtml"> \r\n<head> \r\n<title><% TITLE %></title> \r\n<meta http-equiv="content-type" content="text/html; charset=iso-8859-1\\"> \r\n<% GENERATOR %> \r\n<% CSS %> \r\n<% JAVASCRIPT %> \r\n</head> \r\n<body>\r\n<div id="ipbwrapper">\r\n<% BOARD HEADER %> \r\n<% NAVIGATION %> \r\n<% BOARD %> \r\n<% STATS %> \r\n<% COPYRIGHT %> \r\n<div align=\'center\' class=\'copyright\'>Provided by <a href=\'http://www.sebflipper.com\' target=\'_blank\'>Multi-Forums</a>, setup your <a href=\'new_forum.php\' target=\'_blank\'>forum now</a>!<center></div>\r\n</body> \r\n</html>', 'Invision Board Standard');

#
# Table structure for table `multiforums_settings`
#

CREATE TABLE `multiforums_settings` (
  `id` int(11) NOT NULL auto_increment,
  `name` longtext NOT NULL,
  `v_name` longtext NOT NULL,
  `value` longtext NOT NULL,
  `type` varchar(25) NOT NULL default '',
  `desc` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM ;

#
# Dumping data for table `multiforums_settings`
#

INSERT INTO `multiforums_settings` VALUES (1, 'Script URL', 'mf_url', '%mf_url', 'text', 'The full URL to the script without any trailing slashes');
INSERT INTO `multiforums_settings` VALUES (2, 'Script Path', 'mf_path', '%mf_path', 'text', 'The full path to the script directory with trailing slash');
INSERT INTO `multiforums_settings` VALUES (3, 'Admin Email', 'email', '%email', 'text', 'Admin Email Address');
INSERT INTO `multiforums_settings` VALUES (4, 'Admin Password', 'password', '%password', 'text', 'Admin Password, leave blank to keep the current password (Note: passwords are encrypted in MD5 format)');
INSERT INTO `multiforums_settings` VALUES (5, 'No Forum Selected Error Page', 'noforum_error_url', '%noforum_error_url', 'text', 'e.g: http://www.ffhut.co.uk/%7Esebflipper/?page=freeforum_noforum');
INSERT INTO `multiforums_settings` VALUES (6, 'Forum Doesn\'t Exist Error Page', 'exist_error_url', '%exist_error_url', 'text', 'e.g: http://www.ffhut.co.uk/%7Esebflipper/?page=freeforum_noexist');
INSERT INTO `multiforums_settings` VALUES (7, 'Offline Error URL', 'offline_error_url', '%offline_error_url', 'text', 'If the forum has been turned offline or if all the forums are offline, redirect to this page');
INSERT INTO `multiforums_settings` VALUES (8, 'No Post forum delete', 'no_posts', '30', 'int', 'If the forum has not been posted on in the last x days, delete it');
INSERT INTO `multiforums_settings` VALUES (9, 'No admin login delete', 'no_admin_login', '5', 'int', 'If the forum admin has not logged in for the last x days, delete it');
INSERT INTO `multiforums_settings` VALUES (10, 'Add to top 10 Multi-Forums Hosts List', 'top_10', 'yes', 'yes_no', 'Adds your forum hosting service to: http://www.ffhut.co.uk/~sebflipper/?page=freeforums_top10');
INSERT INTO `multiforums_settings` VALUES (11, 'Email Admin on new forum', 'email_new', 'yes', 'yes_no', 'Send an email to the admin when a new forum is created');
INSERT INTO `multiforums_settings` VALUES (12, 'Number of hours before auto re-cache', 'auto_cache_time', '24', 'int', 'The system cache is used for searching/sorting though forum data, the cache is mainly used on the Board Directory and it used on the Admin CP  (set to 0 to disable and you will need to run it manually or as a Scheduled Task on the server)');
INSERT INTO `multiforums_settings` VALUES (13, 'Display Copyright', 'copyright', 'yes', 'yes_no', 'Diplays the copyright info at the bottom of every page');
INSERT INTO `multiforums_settings` VALUES (14, 'Registration Number', 'reg_no', '%reg_no', '', 'Your registration number');
INSERT INTO `multiforums_settings` VALUES (15, 'Turn all forums offline', 'master_offline', '', '', 'Allows the admin to turn all the forums offline');
INSERT INTO `multiforums_settings` VALUES (16, 'Last System Cache', 'last_cache', '0', '', 'This is the unix time stamp for the last system cache');
INSERT INTO `multiforums_settings` VALUES (17, 'Version', 'version', 'v1.0 Pro Host', '', 'Script version number (do not change)');
INSERT INTO `multiforums_settings` VALUES (18, 'Latest Version', 'latest_version', '', '', 'Latest Version of this script');
