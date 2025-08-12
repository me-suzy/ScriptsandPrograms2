-- phpMyAdmin SQL Dump
-- version 2.6.0-pl2
-- http://www.phpmyadmin.net
-- Server version: 4.0.18
-- PHP Version: 4.3.8
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `site_cats`
-- 

CREATE TABLE `site_cats` (
  `cat_id` int(11) NOT NULL auto_increment,
  `cat_name` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`cat_id`)
) TYPE=MyISAM AUTO_INCREMENT=7 ;

-- 
-- Dumping data for table `site_cats`
-- 

INSERT INTO `site_cats` VALUES (1, 'Gas');
INSERT INTO `site_cats` VALUES (2, 'Hotel');
INSERT INTO `site_cats` VALUES (3, 'Food');
INSERT INTO `site_cats` VALUES (4, 'Phone');
INSERT INTO `site_cats` VALUES (5, 'Transportation');
INSERT INTO `site_cats` VALUES (6, 'Air Travel');

-- --------------------------------------------------------

-- 
-- Table structure for table `site_comments`
-- 

CREATE TABLE `site_comments` (
  `comment_id` int(11) NOT NULL auto_increment,
  `comment_report` int(11) NOT NULL default '0',
  `comment_title` varchar(200) NOT NULL default '',
  `comment_body` text NOT NULL,
  `comment_date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`comment_id`)
) TYPE=MyISAM AUTO_INCREMENT=14 ;

-- 
-- Dumping data for table `site_comments`
-- 

INSERT INTO `site_comments` VALUES (8, 14, 'sdfg sd', 'fgs dgfsd fgds', 0);
INSERT INTO `site_comments` VALUES (6, 14, 'dsgsd', 'fgsdfgsfg', 0);
INSERT INTO `site_comments` VALUES (7, 22, 'sdg sdgf', ' fs dfgsfd gdsg ', 0);
INSERT INTO `site_comments` VALUES (5, 14, 'sdgsd', 'gsdfgsdgfsf', 0);
INSERT INTO `site_comments` VALUES (9, 20, 'asfasfd', 'asf asdfadsf', 0);
INSERT INTO `site_comments` VALUES (10, 20, 'asfasfd', 'asf asdfadsf', 0);
INSERT INTO `site_comments` VALUES (13, 2, 'Phone Charges', 'Joe, I can''t approve these phone charges until I get a detailed bill.', 1098805686);

-- --------------------------------------------------------

-- 
-- Table structure for table `site_expenses`
-- 

CREATE TABLE `site_expenses` (
  `expense_id` int(11) NOT NULL auto_increment,
  `expense_cat` int(11) NOT NULL default '0',
  `expense_location` varchar(200) NOT NULL default '',
  `expense_description` text NOT NULL,
  `expense_recepit` int(1) NOT NULL default '0',
  `expense_billable` int(1) NOT NULL default '0',
  `expense_amount` varchar(100) NOT NULL default '',
  `expense_report` int(11) NOT NULL default '0',
  `expense_date` int(11) NOT NULL default '0',
  `expense_status` int(1) NOT NULL default '3',
  PRIMARY KEY  (`expense_id`)
) TYPE=MyISAM AUTO_INCREMENT=14 ;

-- 
-- Dumping data for table `site_expenses`
-- 

INSERT INTO `site_expenses` VALUES (9, 6, 'Texas', 'UA flight 123 from Houtson to Boston.', 1, 1, '578', 2, 1097467200, 1);
INSERT INTO `site_expenses` VALUES (11, 5, 'Boston', 'Car rental.', 1, 1, '285.48', 2, 1097553600, 2);
INSERT INTO `site_expenses` VALUES (10, 2, 'Boston', 'Holiday Inn Express at 100 per night for 6 nights, plus taxes.', 1, 1, '685.22', 2, 1097467200, 1);
INSERT INTO `site_expenses` VALUES (12, 3, 'Red Lobster', 'Meal with clients.', 1, 0, '85.5', 2, 1097899200, 1);
INSERT INTO `site_expenses` VALUES (13, 4, 'Phone Charges', 'Phone connection charge in the room.', 1, 0, '18.25', 2, 1097553600, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `site_reports`
-- 

CREATE TABLE `site_reports` (
  `report_id` int(11) NOT NULL auto_increment,
  `report_user` int(11) NOT NULL default '0',
  `report_name` varchar(200) NOT NULL default '',
  `report_description` text NOT NULL,
  `report_date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`report_id`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `site_reports`
-- 

INSERT INTO `site_reports` VALUES (2, 2, 'Sales Trip Boston - 2346', 'Sales trip to Boston, MA. Meet with clients Jones, Smith, and Ruters. Explain new features of the acme product.', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `site_users`
-- 

CREATE TABLE `site_users` (
  `user_id` int(11) NOT NULL auto_increment,
  `user_name` varchar(100) NOT NULL default '',
  `user_email` varchar(200) NOT NULL default '',
  `user_login` varchar(100) NOT NULL default '',
  `user_password` varchar(100) NOT NULL default '',
  `user_level` int(1) NOT NULL default '0',
  PRIMARY KEY  (`user_id`)
) TYPE=MyISAM AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `site_users`
-- 

INSERT INTO `site_users` VALUES (3, 'Admin', 'user1@example.com', 'admin', 'test', 0);
INSERT INTO `site_users` VALUES (2, 'John Doe', 'user2@example.com', 'test', 'test', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `site_vars`
-- 

CREATE TABLE `site_vars` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=96 ;

-- 
-- Dumping data for table `site_vars`
-- 

INSERT INTO `site_vars` VALUES (87, 'home', '1');
INSERT INTO `site_vars` VALUES (88, 'title', '15');
INSERT INTO `site_vars` VALUES (89, 'task_user', '1');
INSERT INTO `site_vars` VALUES (90, 'ipp', '5');
INSERT INTO `site_vars` VALUES (91, 'ppp', '4');
INSERT INTO `site_vars` VALUES (92, 'status_on', '1');
INSERT INTO `site_vars` VALUES (93, 'status_address', 'test@example.com');
INSERT INTO `site_vars` VALUES (94, 'status_mail', '{USER_NAME} ({USER_EMAIL})\r\nReport: {REPORT_NAME} \r\nLocation: {EXPENSE_LOCATION} \r\nDate: {EXPENSE_DATE} \r\nAmount: {EXPENSE_AMOUNT}\r\nHas been changed to: \r\n{EXPENSE_NEW_STATUS} \r\nFrom: \r\n{EXPENSE_OLD_STATUS}');
INSERT INTO `site_vars` VALUES (95, 'status_title', 'Test Mail');
        