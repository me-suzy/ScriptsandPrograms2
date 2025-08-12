-- phpMyAdmin SQL Dump
-- version 2.6.0-pl3
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Aug 07, 2005 at 07:05 AM
-- Server version: 4.0.15
-- PHP Version: 4.3.10
-- 
-- Database: `guestbook`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `guestbook`
-- 

CREATE TABLE `guestbook` (
  `id` tinyint(10) NOT NULL auto_increment,
  `date` varchar(255) default NULL,
  `name` varchar(255) default NULL,
  `email` varchar(255) default NULL,
  `url` varchar(255) default NULL,
  `comments` text,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=0 ;

-- 
-- Dumping data for table `guestbook`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

CREATE TABLE `users` (
  `id` int(10) NOT NULL auto_increment,
  `user` varchar(25) default NULL,
  `pass` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=0 ;

-- 
-- Dumping data for table `users`
-- 

INSERT INTO `users` VALUES (1, 'root', '63a9f0ea7bb98050796b649e85481845');
