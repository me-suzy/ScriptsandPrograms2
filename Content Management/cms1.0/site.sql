-- phpMyAdmin SQL Dump
-- version 2.6.0-pl1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jun 24, 2005 at 01:58 PM
-- Server version: 4.0.21
-- PHP Version: 4.3.4
-- 
-- Database: `cmsus`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `footer`
-- 

CREATE TABLE `footer` (
  `serial` int(4) NOT NULL auto_increment,
  `text` text NOT NULL,
  PRIMARY KEY  (`serial`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `footer`
-- 

INSERT INTO `footer` VALUES (1, 'This is the footer text for my site');

-- --------------------------------------------------------

-- 
-- Table structure for table `header`
-- 

CREATE TABLE `header` (
  `serial` int(4) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL default '',
  `type` char(1) NOT NULL default '',
  `logo` varchar(20) NOT NULL default '',
  `bimage` varchar(20) NOT NULL default '',
  `company` varchar(20) NOT NULL default '',
  `punchline` text NOT NULL,
  PRIMARY KEY  (`serial`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `header`
-- 

INSERT INTO `header` VALUES (1, 'one', 'B', 'logo.gif', 'back003.jpg', 'My Website', 'some information here');

-- --------------------------------------------------------

-- 
-- Table structure for table `pages`
-- 

CREATE TABLE `pages` (
  `serial` int(4) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL default '',
  `heading` text NOT NULL,
  `text` text NOT NULL,
  `pageorder` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`serial`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `pages`
-- 

INSERT INTO `pages` VALUES (1, 'main', 'Home Page', 'This is the main text for this page.', 1);
INSERT INTO `pages` VALUES (2, 'about us', 'Information About Us', '<P>We are a company that now has a website.</P>', 2);
INSERT INTO `pages` VALUES (3, 'job openings', 'web development', 'This would contain information about a job.', 5);
INSERT INTO `pages` VALUES (4, 'projects', 'Our Projects', 'Information about projects etc...', 4);
INSERT INTO `pages` VALUES (5, 'contact us', 'How to contact us', 'We can be contacted at 555-1212\r\n\r\nThanks', 3);

-- --------------------------------------------------------

-- 
-- Table structure for table `stylesheet`
-- 

CREATE TABLE `stylesheet` (
  `sname` varchar(20) NOT NULL default '',
  `active` char(1) NOT NULL default 'n',
  PRIMARY KEY  (`sname`)
) TYPE=MyISAM COMMENT='table for stylesheet selection';

-- 
-- Dumping data for table `stylesheet`
-- 

INSERT INTO `stylesheet` VALUES ('cool-blue', 'n');
INSERT INTO `stylesheet` VALUES ('red', 'n');
INSERT INTO `stylesheet` VALUES ('green', 'n');
INSERT INTO `stylesheet` VALUES ('black', 'y');
        