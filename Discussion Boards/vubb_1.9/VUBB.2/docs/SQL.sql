-- phpMyAdmin SQL Dump
-- version 2.6.4-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Nov 15, 2005 at 03:23 PM
-- Server version: 4.0.25
-- PHP Version: 4.3.11
-- 
-- Database: `vubb_forum`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `config`
-- 

CREATE TABLE `config` (
  `id` int(1) NOT NULL auto_increment,
  `name` text NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=9 ;

-- 
-- Dumping data for table `config`
-- 

INSERT INTO `config` VALUES (1, 'site_name', 'VUBB');
INSERT INTO `config` VALUES (2, 'site_url', 'http://vubb.com/');
INSERT INTO `config` VALUES (3, 'site_path', '/home/vubb/public_html/');
INSERT INTO `config` VALUES (4, 'template', 'core');
INSERT INTO `config` VALUES (5, 'new_registrations', '1');
INSERT INTO `config` VALUES (6, 'language', 'english');
INSERT INTO `config` VALUES (7, 'website_link', 'http://vubb.com');
INSERT INTO `config` VALUES (8, 'website_name', 'VUBB');

-- --------------------------------------------------------

-- 
-- Table structure for table `forum_replies`
-- 

CREATE TABLE `forum_replies` (
  `id` int(11) NOT NULL auto_increment,
  `starter` varchar(30) NOT NULL default '',
  `topic_id` int(11) NOT NULL default '0',
  `starter_id` int(11) NOT NULL default '0',
  `forumroot` int(11) NOT NULL default '0',
  `date` varchar(11) NOT NULL default '',
  `time` varchar(11) NOT NULL default '',
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `forum_replies`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `forum_reply_text`
-- 

CREATE TABLE `forum_reply_text` (
  `reply_id` int(11) NOT NULL default '0',
  `topic_id` int(11) NOT NULL default '0',
  `body` text NOT NULL
) TYPE=MyISAM PACK_KEYS=0;

-- 
-- Dumping data for table `forum_reply_text`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `forum_topic_text`
-- 

CREATE TABLE `forum_topic_text` (
  `topic_id` int(11) NOT NULL default '0',
  `body` text NOT NULL
) TYPE=MyISAM PACK_KEYS=0;

-- 
-- Dumping data for table `forum_topic_text`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `forum_topics`
-- 

CREATE TABLE `forum_topics` (
  `id` int(11) NOT NULL auto_increment,
  `topic` varchar(40) NOT NULL default '',
  `starter` varchar(20) NOT NULL default '',
  `starter_id` int(11) NOT NULL default '0',
  `forumroot` int(11) NOT NULL default '0',
  `date` varchar(11) NOT NULL default '',
  `time` varchar(11) NOT NULL default '',
  `locked` int(1) NOT NULL default '0',
  `lastdate` varchar(11) NOT NULL default '',
  `replies` int(11) NOT NULL default '0',
  `views` int(11) NOT NULL default '0',
  `sticky` int(1) NOT NULL default '0',
  `poll` int(1) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `forum_topics`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `forums`
-- 

CREATE TABLE `forums` (
  `id` int(11) NOT NULL auto_increment,
  `name` longtext NOT NULL,
  `description` text NOT NULL,
  `is_cat` int(1) NOT NULL default '0',
  `is_link` int(1) NOT NULL default '0',
  `category` int(11) NOT NULL default '0',
  `link` text NOT NULL,
  `topics` int(11) NOT NULL default '0',
  `replies` int(11) NOT NULL default '0',
  `order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `forums`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `groups`
-- 

CREATE TABLE `groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `permanent` int(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `groups`
-- 

INSERT INTO `groups` VALUES (1, 'Guests', 1);
INSERT INTO `groups` VALUES (2, 'Members', 1);
INSERT INTO `groups` VALUES (3, 'Moderators', 1);
INSERT INTO `groups` VALUES (4, 'Administrators', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `guests_online`
-- 

CREATE TABLE `guests_online` (
  `id` int(11) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  `time` varchar(15) NOT NULL default ''
) TYPE=MyISAM;

-- 
-- Dumping data for table `guests_online`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `members`
-- 

CREATE TABLE `members` (
  `id` int(11) NOT NULL auto_increment,
  `user` varchar(32) NOT NULL default '',
  `email` varchar(60) NOT NULL default '',
  `pass` varchar(32) NOT NULL default '',
  `group` int(11) NOT NULL default '2',
  `ip` varchar(50) NOT NULL default '',
  `lpv` bigint(20) NOT NULL default '0',
  `online` char(1) NOT NULL default '',
  `avatar_link` text NOT NULL,
  `sig` text NOT NULL,
  `datereg` varchar(15) NOT NULL default '0',
  `locked` char(1) NOT NULL default 'N',
  `location` varchar(60) NOT NULL default '',
  `website` varchar(60) NOT NULL default '',
  `aim` varchar(30) NOT NULL default '',
  `msn` varchar(30) NOT NULL default '',
  `yahoo` varchar(30) NOT NULL default '',
  `icq` int(30) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `members`
-- 

INSERT INTO `members` VALUES (-1, 'Guest', '', '', 1, '', 0, '0', 'http://vubb.com//images/guestav.gif', '', '0', 'N', '', '', '', '', '', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `permissions`
-- 

CREATE TABLE `permissions` (
  `forum` int(11) NOT NULL default '0',
  `group` int(11) NOT NULL default '0',
  `cpost` int(1) NOT NULL default '0',
  `cview` int(1) NOT NULL default '0'
) TYPE=MyISAM;

-- 
-- Dumping data for table `permissions`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `poll_choices`
-- 

CREATE TABLE `poll_choices` (
  `choice` varchar(30) NOT NULL default '',
  `id` int(11) NOT NULL auto_increment,
  `poll_id` int(11) NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `poll_choices`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `poll_voters`
-- 

CREATE TABLE `poll_voters` (
  `user_id` int(11) NOT NULL default '0',
  `poll_id` int(11) NOT NULL default '0'
) TYPE=MyISAM;

-- 
-- Dumping data for table `poll_voters`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `polls`
-- 

CREATE TABLE `polls` (
  `name` varchar(30) NOT NULL default '',
  `id` int(11) NOT NULL auto_increment,
  `topic_id` int(11) NOT NULL default '0',
  `totalvotes` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `polls`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `smilies`
-- 

CREATE TABLE `smilies` (
  `id` int(11) NOT NULL auto_increment,
  `code` text NOT NULL,
  `image` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=13 ;

-- 
-- Dumping data for table `smilies`
-- 

INSERT INTO `smilies` VALUES (1, '[<]', 'images/smilies/back.gif');
INSERT INTO `smilies` VALUES (2, ':D', 'images/smilies/bigsmile.gif');
INSERT INTO `smilies` VALUES (3, ':(', 'images/smilies/cry.gif');
INSERT INTO `smilies` VALUES (4, '[>]', 'images/smilies/forward.gif');
INSERT INTO `smilies` VALUES (5, ':(', 'images/smilies/frown.gif');
INSERT INTO `smilies` VALUES (6, ':@', 'images/smilies/mad.gif');
INSERT INTO `smilies` VALUES (7, '["]', 'images/smilies/pause.gif');
INSERT INTO `smilies` VALUES (8, '[->]', 'images/smilies/play.gif');
INSERT INTO `smilies` VALUES (9, ':)', 'images/smilies/smile.gif');
INSERT INTO `smilies` VALUES (10, '[!]', 'images/smilies/stop.gif');
INSERT INTO `smilies` VALUES (11, '0.0', 'images/smilies/suprised.gif');
INSERT INTO `smilies` VALUES (12, ':P', 'images/smilies/tongue.gif');
