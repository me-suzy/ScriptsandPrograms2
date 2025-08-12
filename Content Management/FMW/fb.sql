-- phpMyAdmin SQL Dump
-- version 2.6.0-pl3
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: May 12, 2005 at 10:05 AM
-- Server version: 3.23.49
-- PHP Version: 4.3.9
-- 
-- Database: `fb`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `admin`
-- 

CREATE TABLE `admin` (
  `id` int(10) NOT NULL auto_increment,
  `username` varchar(50) default NULL,
  `avatar` varchar(50) default NULL,
  `col_back` varchar(7) NOT NULL default 'ffffff',
  `col_link` varchar(7) NOT NULL default 'ff9900',
  `col_text` varchar(7) NOT NULL default 'ffffff',
  `col_table_border` varchar(7) NOT NULL default 'FFFFFF',
  `col_table_border_2` varchar(7) NOT NULL default 'FFFFFF',
  `col_table_row` varchar(7) NOT NULL default '000000',
  `col_table_row2` varchar(7) NOT NULL default '000000',
  `col_table_header` varchar(7) NOT NULL default 'EB9518',
  `col_table_header_2` varchar(7) NOT NULL default '000000',
  `col_table_header_text` varchar(7) NOT NULL default 'FFFFFF',
  `col_table_row_text` varchar(7) NOT NULL default 'FFFFFF',
  `currency` char(3) NOT NULL default '£',
  `logo_pos` varchar(10) NOT NULL default 'center',
  `texture` varchar(30) NOT NULL default '',
  `admin_message` text NOT NULL,
  `title_message` text NOT NULL,
  `theme_col` varchar(12) NOT NULL default 'grey',
  `site_url` varchar(100) NOT NULL default 'Enter your site url here !!!!',
  `admin_email` varchar(100) NOT NULL default '',
  `pom_vote` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `admin`
-- 

INSERT INTO `admin` VALUES (1, 'admin', 'homer.gif', '000033', 'FFFF33', 'FFFFFF', 'CCCCCC', '888888', '330000', '660000', '000066', '000033', 'FFFF00', 'FFFFFF', '', 'Center', '0', 'This welcome message can be changed from the admin control panel.\r\n\r\nPut whatever message you like !\r\nit will always be displayed at the top of this page\r\n\r\nIMPORTANT: Create a new users straight away, giving yourself admin rights, then login with your new user and delete the admin user !!!', 'Welcome to PHP FC Homepage', 'Grey', '', '', 2);

-- --------------------------------------------------------

-- 
-- Table structure for table `fixtures`
-- 

CREATE TABLE `fixtures` (
  `fix_id` int(10) NOT NULL auto_increment,
  `opp_team` varchar(50) NOT NULL default '',
  `ground` varchar(50) NOT NULL default '',
  `fix_date` date NOT NULL default '0000-00-00',
  `fix_time` varchar(8) NOT NULL default '',
  `home_away` varchar(10) NOT NULL default '',
  `match_type` varchar(40) NOT NULL default '',
  `notes` text NOT NULL,
  `closed` char(3) NOT NULL default 'no',
  `fix_oldname` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`fix_id`)
) TYPE=MyISAM AUTO_INCREMENT=73 ;

-- 
-- Dumping data for table `fixtures`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `league_table`
-- 

CREATE TABLE `league_table` (
  `table_id` int(10) NOT NULL auto_increment,
  `match_id` int(11) NOT NULL default '0',
  `team_name` varchar(50) NOT NULL default '',
  `opp_team` varchar(50) NOT NULL default '',
  `w_d_l` varchar(5) NOT NULL default '',
  `points` int(10) NOT NULL default '0',
  `home_away` varchar(8) NOT NULL default '',
  `match_type` varchar(40) NOT NULL default '',
  `goals_for` int(11) NOT NULL default '0',
  `goals_against` int(11) NOT NULL default '0',
  `oldname` varchar(50) NOT NULL default '',
  `match_report` text NOT NULL,
  `match_tag` char(3) NOT NULL default '',
  `match_date` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`table_id`)
) TYPE=MyISAM AUTO_INCREMENT=153 ;

-- 
-- Dumping data for table `league_table`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `match_comment`
-- 

CREATE TABLE `match_comment` (
  `comment_id` int(10) NOT NULL auto_increment,
  `match_id` int(11) NOT NULL default '0',
  `name` varchar(40) NOT NULL default '',
  `comment` text NOT NULL,
  PRIMARY KEY  (`comment_id`)
) TYPE=MyISAM AUTO_INCREMENT=18 ;

-- 
-- Dumping data for table `match_comment`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `match_signup`
-- 

CREATE TABLE `match_signup` (
  `signup_id` int(10) NOT NULL auto_increment,
  `match_id` int(10) NOT NULL default '0',
  `name` varchar(40) NOT NULL default '',
  `playing` varchar(5) NOT NULL default '',
  `comment` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`signup_id`)
) TYPE=MyISAM AUTO_INCREMENT=21 ;

-- 
-- Dumping data for table `match_signup`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `match_squad`
-- 

CREATE TABLE `match_squad` (
  `squad_id` int(10) NOT NULL auto_increment,
  `match_id` int(10) NOT NULL default '0',
  `position` varchar(20) NOT NULL default '',
  `playername` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`squad_id`)
) TYPE=MyISAM AUTO_INCREMENT=219 ;

-- 
-- Dumping data for table `match_squad`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `matchtypes`
-- 

CREATE TABLE `matchtypes` (
  `matchtype_id` int(10) NOT NULL auto_increment,
  `matchtype` varchar(40) NOT NULL default '',
  `match_cat` varchar(40) NOT NULL default '',
  `division` varchar(40) NOT NULL default '',
  PRIMARY KEY  (`matchtype_id`),
  UNIQUE KEY `matchtype` (`matchtype`)
) TYPE=MyISAM AUTO_INCREMENT=9 ;

-- 
-- Dumping data for table `matchtypes`
-- 

INSERT INTO `matchtypes` VALUES (1, 'Enter league name here', 'league', '');

-- --------------------------------------------------------

-- 
-- Table structure for table `news`
-- 

CREATE TABLE `news` (
  `news_id` int(10) NOT NULL auto_increment,
  `news_username` varchar(40) default NULL,
  `news` text,
  `news_title` varchar(50) NOT NULL default '',
  `news_date` date NOT NULL default '0000-00-00',
  `topic_image` varchar(20) NOT NULL default 'topic_news.gif',
  `hyperlink_text1` varchar(255) default NULL,
  `hyperlink_url1` varchar(255) default NULL,
  `hyperlink_text2` varchar(255) default NULL,
  `hyperlink_url2` varchar(255) default NULL,
  `news_image` varchar(20) NOT NULL default 'none',
  PRIMARY KEY  (`news_id`)
) TYPE=MyISAM AUTO_INCREMENT=160 ;

-- 
-- Dumping data for table `news`
-- 

INSERT INTO `news` VALUES (158, 'admin', 'This is an example news post, please delete this post using the ''Delete'' option in the top right corner.\r\n\r\nAdd your own news posts via the administration screen.', 'Example News', '2005-05-12', 'club news2.gif', '', '', '', '', 'none');

-- --------------------------------------------------------

-- 
-- Table structure for table `player_of_match`
-- 

CREATE TABLE `player_of_match` (
  `pom_id` int(10) NOT NULL auto_increment,
  `username` varchar(50) NOT NULL default '',
  `playername` varchar(50) NOT NULL default '',
  `match_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`pom_id`)
) TYPE=MyISAM AUTO_INCREMENT=18 ;

-- 
-- Dumping data for table `player_of_match`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `results`
-- 

CREATE TABLE `results` (
  `results_id` int(10) NOT NULL auto_increment,
  `match_id` int(10) NOT NULL default '0',
  `playername` varchar(40) NOT NULL default '',
  `goals` tinyint(4) NOT NULL default '0',
  `position` varchar(20) NOT NULL default '',
  `pom` int(11) NOT NULL default '0',
  PRIMARY KEY  (`results_id`)
) TYPE=MyISAM AUTO_INCREMENT=97 ;

-- 
-- Dumping data for table `results`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `roles`
-- 

CREATE TABLE `roles` (
  `role_id` tinyint(10) NOT NULL auto_increment,
  `role_title` varchar(20) NOT NULL default '',
  `role_order` int(10) NOT NULL default '0',
  PRIMARY KEY  (`role_id`)
) TYPE=MyISAM AUTO_INCREMENT=19 ;

-- 
-- Dumping data for table `roles`
-- 

INSERT INTO `roles` VALUES (1, 'Manager', 2);
INSERT INTO `roles` VALUES (3, 'Player', 6);

-- --------------------------------------------------------

-- 
-- Table structure for table `seasons`
-- 

CREATE TABLE `seasons` (
  `season_id` int(10) NOT NULL auto_increment,
  `season_name` varchar(20) NOT NULL default '',
  `season_start` date NOT NULL default '0000-00-00',
  `season_end` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`season_id`)
) TYPE=MyISAM AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `seasons`
-- 

INSERT INTO `seasons` VALUES (2, '2004-2005', '2004-08-01', '2005-05-29');

-- --------------------------------------------------------

-- 
-- Table structure for table `teams`
-- 

CREATE TABLE `teams` (
  `team_id` int(10) NOT NULL auto_increment,
  `team_name` varchar(80) NOT NULL default '',
  `division` varchar(80) NOT NULL default '',
  `own_team` varchar(5) NOT NULL default 'no',
  `league_name` varchar(80) NOT NULL default '',
  `contact_name` varchar(40) NOT NULL default '',
  `contact_role` varchar(30) NOT NULL default '',
  `contact_name_2` varchar(30) NOT NULL default '',
  `contact_role_2` varchar(30) NOT NULL default '',
  `contact_name_3` varchar(30) NOT NULL default '',
  `contact_role_3` varchar(30) NOT NULL default '',
  `contact_email` varchar(50) NOT NULL default '',
  `contact_email_2` varchar(50) NOT NULL default '',
  `contact_email_3` varchar(50) NOT NULL default '',
  `contact_tel` varchar(20) NOT NULL default '',
  `contact_tel_2` varchar(20) NOT NULL default '',
  `contact_tel_3` varchar(20) NOT NULL default '',
  `contact_address` varchar(255) NOT NULL default '',
  `ground_name` varchar(50) NOT NULL default '',
  `home_strip` varchar(50) NOT NULL default '',
  `away_strip` varchar(50) NOT NULL default '',
  `points_total` int(11) NOT NULL default '0',
  `goaldiff` int(11) NOT NULL default '0',
  PRIMARY KEY  (`team_id`)
) TYPE=MyISAM AUTO_INCREMENT=18 ;

-- 
-- Dumping data for table `teams`
-- 

INSERT INTO `teams` VALUES (1, 'Your Team Name', '', 'yes', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 0, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `topic`
-- 

CREATE TABLE `topic` (
  `topic_id` int(10) NOT NULL auto_increment,
  `topic_image` varchar(20) NOT NULL default '',
  `topic_image_name` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`topic_id`)
) TYPE=MyISAM AUTO_INCREMENT=28 ;

-- 
-- Dumping data for table `topic`
-- 

INSERT INTO `topic` VALUES (27, 'cup news.gif', 'Cup News');
INSERT INTO `topic` VALUES (26, 'player match 2.gif', 'Player Of The Match');
INSERT INTO `topic` VALUES (21, 'match_news.gif', 'Match News');
INSERT INTO `topic` VALUES (24, 'club news2.gif', 'Club News');

-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

CREATE TABLE `users` (
  `id` int(10) NOT NULL auto_increment,
  `username` varchar(40) default NULL,
  `displayname` varchar(40) NOT NULL default '',
  `nickname` varchar(20) NOT NULL default '',
  `interests` text NOT NULL,
  `yim` varchar(50) NOT NULL default '',
  `joindate` varchar(20) NOT NULL default 'n/a',
  `clubs` text NOT NULL,
  `profile` text NOT NULL,
  `password` varchar(50) default NULL,
  `rights` int(10) NOT NULL default '0',
  `regdate` varchar(20) default NULL,
  `email` varchar(100) default NULL,
  `msn` varchar(40) NOT NULL default '',
  `icq` varchar(10) NOT NULL default '',
  `aim` varchar(40) NOT NULL default '',
  `tel` varchar(20) NOT NULL default '',
  `position` varchar(20) NOT NULL default '',
  `role` varchar(20) NOT NULL default '',
  `age` int(3) default NULL,
  `website` varchar(150) default NULL,
  `location` varchar(150) default NULL,
  `show_email` int(2) default '0',
  `last_login` varchar(20) default NULL,
  `avatar` varchar(30) NOT NULL default 'no_pic.gif',
  `player` char(3) NOT NULL default '',
  `selected` char(3) NOT NULL default 'no',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `displayname` (`displayname`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=124 ;

-- 
-- Dumping data for table `users`
-- 

INSERT INTO `users` VALUES (121, 'admin', 'admin', '', '', '', 'n/a', '', '', '21232f297a57a5a743894a0e4a801fc3', 5, '2005-05-12', '', '', '', '', '', '', 'Player', NULL, NULL, NULL, 0, 'Never', 'no_pic.gif', 'yes', 'no');
