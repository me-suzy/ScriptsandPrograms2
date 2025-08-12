-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Dec 04, 2004 at 11:24 PM
-- Server version: 4.0.20
-- PHP Version: 4.3.9
-- 
-- Database: `giftlist`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `gifts`
-- 

CREATE TABLE `gifts` (
  `gift_id` int(11) NOT NULL auto_increment,
  `username` varchar(40) default NULL,
  `gift_name` varchar(80) NOT NULL default '',
  `gift_price` decimal(8,2) default '0.00',
  `gift_url_store` varchar(255) default NULL,
  `gift_description` text,
  `gift_priority` enum('Low','Med','High') NOT NULL default 'Low',
  `buyer` varchar(40) default 'no',
  `give_date` date default '2090-01-01',
  `giver` varchar(40) default NULL,
  `del_gift` varchar(10) default 'no',
  `buyable` varchar(5) default NULL,
  PRIMARY KEY  (`gift_id`)
) TYPE=MyISAM AUTO_INCREMENT=116 ;

-- 
-- Dumping data for table `gifts`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

CREATE TABLE `users` (
  `id` int(10) NOT NULL auto_increment,
  `username` varchar(40) default NULL,
  `password` varchar(50) default NULL,
  `regdate` varchar(20) default NULL,
  `email` varchar(100) default NULL,
  `website` varchar(150) default NULL,
  `location` varchar(150) default NULL,
  `show_email` int(2) default '0',
  `last_login` varchar(20) default NULL,
  `movies` text,
  `access` varchar(12) default 'adults',
  `music` text,
  `books` text,
  `vouchers` text,
  `misc` text,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=21 ;

-- 
-- Dumping data for table `users`
-- 

