-- phpMyAdmin SQL Dump
-- version 2.6.0-pl3
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Jul 20, 2005 at 12:10 PM
-- Server version: 4.1.8
-- PHP Version: 5.0.3
-- 
-- Database: `mypicalbum`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `album_permission`
-- 

CREATE TABLE `album_permission` (
  `ID` int(11) NOT NULL auto_increment,
  `dir` varchar(225) NOT NULL default '',
  `userAllowed` varchar(12) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=157 ;


-- --------------------------------------------------------

-- 
-- Table structure for table `album_users`
-- 

CREATE TABLE `album_users` (
  `ID` int(11) NOT NULL auto_increment,
  `userID` varchar(12) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `fullName` varchar(50) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `userType` varchar(12) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- 
-- Dumping data for table `album_users`
-- 

INSERT INTO `album_users` VALUES (7, 'admin', 'b27f9cf2fc251bf3de2a0b326bd766ae', 'Administrator', 'admin@admin.com', 'admin');
INSERT INTO `album_users` VALUES (10, 'test', '098f6bcd4621d373cade4e832627b4f6', 'Testing Log', 'test@test.com', 'regular');
