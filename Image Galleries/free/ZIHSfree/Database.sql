-- phpMyAdmin SQL Dump
-- version 2.6.3-pl1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Sep 28, 2005 at 08:44 PM
-- Server version: 4.0.25
-- PHP Version: 4.3.11
-- 
-- Database: `zihs_imagehost`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `Banned`
-- 

CREATE TABLE `Banned` (
  `ip_id` int(10) NOT NULL auto_increment,
  `logged_ip` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`ip_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `Uploads`
-- 

CREATE TABLE `Uploads` (
  `upload_id` int(10) NOT NULL auto_increment,
  `file_name` varchar(50) NOT NULL default '',
  `file_size` int(6) NOT NULL default '0',
  `date_entered` date NOT NULL default '0000-00-00',
  `user_id` varchar(10) NOT NULL default '',
  `logged_ip` varchar(20) NOT NULL default '',
  `viewable` varchar(4) NOT NULL default '',
  `hidden` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`upload_id`)
) TYPE=MyISAM AUTO_INCREMENT=49 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `Users`
-- 

CREATE TABLE `Users` (
  `user_id` int(8) NOT NULL auto_increment,
  `first_name` varchar(30) NOT NULL default '',
  `last_name` varchar(30) NOT NULL default '',
  `email` varchar(40) NOT NULL default '',
  `password` text NOT NULL,
  `address1` varchar(30) NOT NULL default '',
  `address2` varchar(30) NOT NULL default '',
  `city` varchar(30) NOT NULL default '',
  `state` varchar(20) NOT NULL default '',
  `zip` varchar(20) NOT NULL default '',
  `country` varchar(30) NOT NULL default '',
  `phone` varchar(20) NOT NULL default '',
  `access_level` varchar(20) NOT NULL default '',
  `access_code` varchar(50) NOT NULL default '',
  `confirmed` varchar(4) NOT NULL default '',
  `reg_date` date NOT NULL default '0000-00-00',
  `last_login` date NOT NULL default '0000-00-00',
  `logged_ip` varchar(20) NOT NULL default '',
  `banned` varchar(4) NOT NULL default 'No',
  PRIMARY KEY  (`user_id`)
) TYPE=MyISAM PACK_KEYS=1 AUTO_INCREMENT=4 ;
