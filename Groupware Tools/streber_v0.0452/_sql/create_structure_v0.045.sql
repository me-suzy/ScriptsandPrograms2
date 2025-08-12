-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Aug 19, 2005 at 11:52 PM
-- Server version: 4.0.21
-- PHP Version: 5.0.4
-- 
-- Database: `streber`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `comment`
-- 

CREATE TABLE `comment` (
  `id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `occasion` tinyint(4) NOT NULL default '1',
  `view_collapsed` tinyint(4) NOT NULL default '0',
  `time` datetime NOT NULL default '0000-00-00 00:00:00',
  `person` int(11) NOT NULL default '0',
  `comment` int(11) NOT NULL default '0',
  `task` int(11) NOT NULL default '0',
  `effort` int(11) NOT NULL default '0',
  `file` int(11) NOT NULL default '0',
  `starts_discussion` tinyint(4) NOT NULL default '0',
  `description` longtext NOT NULL
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `company`
-- 

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL default '',
  `tagline` varchar(255) NOT NULL default '',
  `short` varchar(64) NOT NULL default '',
  `phone` varchar(64) NOT NULL default '',
  `fax` varchar(64) NOT NULL default '',
  `street` varchar(255) NOT NULL default '',
  `zipcode` varchar(255) NOT NULL default '',
  `homepage` varchar(255) NOT NULL default '',
  `intranet` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `comments` longtext NOT NULL,
  `state` tinyint(4) NOT NULL default '1',
  `pub_level` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`),
  KEY `name` (`name`),
  KEY `stated` (`state`),
  KEY `pub_level` (`pub_level`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

-- 
-- Table structure for table `db`
-- 

CREATE TABLE `db` (
  `id` int(11) NOT NULL default '0',
  `version` varchar(12) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated` datetime default NULL,
  `version_streber_required` varchar(12) NOT NULL default ''
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `effort`
-- 

CREATE TABLE `effort` (
  `id` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `time_start` datetime default NULL,
  `time_end` datetime default NULL,
  `person` int(10) unsigned NOT NULL default '0',
  `project` int(11) NOT NULL default '0',
  `description` text NOT NULL,
  `task` int(10) NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `employment`
-- 

CREATE TABLE `employment` (
  `id` int(11) NOT NULL,
  `person` int(11) NOT NULL default '0',
  `company` int(11) NOT NULL default '0',
  `comment` varchar(255) NOT NULL default '',
  `pub_level` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `person` (`person`),
  KEY `client` (`company`),
  KEY `pub_level` (`pub_level`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `issue`
-- 

CREATE TABLE `issue` (
  `id` int(11) NOT NULL,
  `reproducibility` tinyint(4) NOT NULL default '0',
  `severity` tinyint(4) NOT NULL default '0',
  `plattform` varchar(255) NOT NULL default '',
  `os` varchar(255) NOT NULL default '',
  `version` varchar(32) NOT NULL default '',
  `production_build` varchar(32) NOT NULL default '',
  `steps_to_reproduce` text NOT NULL,
  `expected_result` text NOT NULL,
  `suggested_solution` text NOT NULL,
  PRIMARY KEY  (`id`),
  FULLTEXT KEY `steps_to_reproduce` (`steps_to_reproduce`,`expected_result`,`suggested_solution`)
) TYPE=MyISAM ;

-- --------------------------------------------------------

-- 
-- Table structure for table `item`
-- 

CREATE TABLE `item` (
  `id` int(11) NOT NULL auto_increment,
  `pub_level` tinyint(4) NOT NULL default '4',
  `type` tinyint(4) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `deleted` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL default '0',
  `modified_by` int(11) NOT NULL default '0',
  `deleted_by` int(11) NOT NULL default '0',
  `project` int(11) default NULL,
  `state` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `i_my_tasks` (`type`,`state`,`project`,`pub_level`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `person`
-- 

CREATE TABLE `person` (
  `id` int(11) NOT NULL,
  `state` tinyint(4) NOT NULL default '1',
  `name` varchar(255) NOT NULL default '',
  `nickname` varchar(64) NOT NULL default '',
  `tagline` varchar(255) NOT NULL default '',

  `mobile_phone` varchar(128) NOT NULL default '',

  `personal_phone` varchar(128) NOT NULL default '',
  `personal_fax` varchar(128) NOT NULL default '',
  `personal_email` varchar(255) NOT NULL default '',
  `personal_street` varchar(255) NOT NULL default '',
  `personal_zipcode` varchar(255) NOT NULL default '',
  `personal_homepage` varchar(255) NOT NULL default '',

  `office_phone` varchar(20) NOT NULL default '',
  `office_fax` varchar(20) NOT NULL default '',
  `office_email` varchar(60) NOT NULL default '',
  `office_street` varchar(128) NOT NULL default '',
  `office_zipcode` varchar(60) NOT NULL default '',
  `office_homepage` varchar(128) NOT NULL default '',

  `comments` longtext NOT NULL,

  `password` varchar(255) NOT NULL default '',
  `security_question` varchar(128) NOT NULL default '',
  `security_answer` varchar(20) NOT NULL default '',
  `user_rights` int(11) NOT NULL default '0',
  `cookie_string` varchar(64) NOT NULL default '',
  `can_login` tinyint(4) NOT NULL default '0',
  `user_level_view` tinyint(4) NOT NULL default '0',
  `user_level_create` tinyint(4) NOT NULL default '0',
  `user_level_edit` tinyint(4) NOT NULL default '0',
  `user_level_reduce` tinyint(4) NOT NULL default '0',
  `pub_level` tinyint(4) NOT NULL default '0',
  `color` varchar(6) NOT NULL default '000000',
  `profile` tinyint(4) NOT NULL default '0',
  `theme` tinyint(4) NOT NULL default '0',
  `identifier` varchar(32) NOT NULL default '',
  `birthdate` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`id`),
  KEY `state` (`state`),
  KEY `id` (`id`),
  KEY `nickname` (`nickname`),
  KEY `cookie_string` (`cookie_string`),
  KEY `can_login` (`can_login`),
  KEY `pub_level` (`pub_level`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `project`
-- 

CREATE TABLE `project` (
  `id` int(11) NOT NULL,
  `state` int(11) NOT NULL default '1',
  `name` varchar(255) NOT NULL default '',
  `short` varchar(64) NOT NULL default '',

  `wikipage` varchar(128) NOT NULL default '',
  `projectpage` varchar(128) NOT NULL default '',

  `date_start` date NOT NULL default '0000-00-00',
  `date_closed` date NOT NULL default '0000-00-00',
  `company` tinyint(4) NOT NULL default '0',
  `status` tinyint(4) NOT NULL default '0',
  `prio` tinyint(4) NOT NULL default '0',
  `description` longtext NOT NULL,
  `labels` varchar(255) NOT NULL default '0',
  `show_in_home` tinyint(4) NOT NULL default '1',

  `pub_level` tinyint(4) NOT NULL default '0',
  `default_pub_level` tinyint(4) NOT NULL default '4',
  `color` varchar(6) NOT NULL default '000000',
  `status_summary` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pub_level` (`pub_level`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `projectperson`
-- 

CREATE TABLE `projectperson` (
  `id` int(11) NOT NULL default '0',
  `state` tinyint(4) NOT NULL default '1',
  `project` int(11) NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `person` int(11) NOT NULL default '0',
  `proj_rights` int(11) NOT NULL default '0',
  `level_view` tinyint(4) NOT NULL default '0',
  `level_edit` tinyint(4) NOT NULL default '0',
  `level_create` tinyint(4) NOT NULL default '0',
  `level_reduce` tinyint(4) NOT NULL default '0',
  `level_delete` tinyint(4) NOT NULL default '4',
  `role` varchar(12) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `task`
-- 

CREATE TABLE `task` (
  `id` int(11) NOT NULL default '0',
  `estimated` time NOT NULL default '00:00:00',
  `completion` tinyint(4) NOT NULL default '0',
  `parent_task` int(11) NOT NULL default '0',

  `is_folder` tinyint(4) NOT NULL default '0',
  `view_collapsed` tinyint(4) NOT NULL default '0',
  `label` tinyint(4) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `short` varchar(64) NOT NULL default '',
  `date_start` date NOT NULL default '0000-00-00',
  `date_due` date NOT NULL default '0000-00-00',
  `date_due_end` date NOT NULL default '0000-00-00',
  `date_closed` date NOT NULL default '0000-00-00',
  `status` tinyint(4) NOT NULL default '0',
  `prio` tinyint(4) NOT NULL default '0',
  `description` longtext NOT NULL,
  `issue_report` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `parent_task` (`parent_task`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `task_effort`
-- 

CREATE TABLE `task_effort` (
  `id` int(11) NOT NULL,
  `task` int(10) unsigned NOT NULL default '0',
  `effort` int(10) unsigned NOT NULL default '0',
  `state` tinyint(4) NOT NULL default '1',
  `created_by` int(11) NOT NULL default '0',
  `modified_by` int(11) NOT NULL default '0',
  `deleted_by` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `deleted` datetime NOT NULL default '0000-00-00 00:00:00',
  `pub_level` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `task` (`task`,`effort`,`state`),
  KEY `pub_level` (`pub_level`)
) TYPE=MyISAM;
        