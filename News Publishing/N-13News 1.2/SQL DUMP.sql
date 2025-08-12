-- phpMyAdmin SQL Dump
-- version 2.6.1-pl2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Sep 07, 2005 at 06:22 AM
-- Server version: 4.0.22
-- PHP Version: 4.3.11
-- 
-- Database: 
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `news12_admin`
-- 

CREATE TABLE `news12_admin` (
  `user` varchar(100) default NULL,
  `pass` varchar(100) default NULL,
  `email` varchar(100) default NULL,
  `avatar` varchar(255) default NULL,
  `god` char(1) default NULL
) TYPE=MyISAM;

-- 
-- Dumping data for table `news12_admin`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `news12_comments`
-- 

CREATE TABLE `news12_comments` (
  `user` varchar(50) default NULL,
  `email` varchar(100) default NULL,
  `date` varchar(30) default NULL,
  `message` text,
  `pid` varchar(10) default NULL,
  `id` varchar(10) default NULL
) TYPE=MyISAM;

-- 
-- Dumping data for table `news12_comments`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `news12_filter`
-- 

CREATE TABLE `news12_filter` (
  `filter` text,
  `alt` text,
  `id` text
) TYPE=MyISAM;

-- 
-- Dumping data for table `news12_filter`
-- 

INSERT INTO `news12_filter` VALUES ('bastard', 'person', '12');
INSERT INTO `news12_filter` VALUES ('fuck', 'f*ck', '11');
INSERT INTO `news12_filter` VALUES ('shit', 'poo', '1');
INSERT INTO `news12_filter` VALUES ('ass', 'rectal passage', '2');
INSERT INTO `news12_filter` VALUES ('cock', 'todger', '4');
INSERT INTO `news12_filter` VALUES ('whore', 'easy gal', '3');
INSERT INTO `news12_filter` VALUES ('cunt', 'genital feature', '5');
INSERT INTO `news12_filter` VALUES ('faggot', 'feminine guy', '6');
INSERT INTO `news12_filter` VALUES ('dick', 'trouser snake', '7');
INSERT INTO `news12_filter` VALUES ('minge', 'vagina', '8');
INSERT INTO `news12_filter` VALUES ('twat', 'vagina', '10');
INSERT INTO `news12_filter` VALUES ('muff', 'fluffy vagina', '9');

-- --------------------------------------------------------

-- 
-- Table structure for table `news12_options`
-- 

CREATE TABLE `news12_options` (
  `header` text,
  `template` text,
  `footer` text,
  `comments` text,
  `commentsform` text,
  `nppage` varchar(10) default NULL,
  `newsorder` varchar(4) default NULL,
  `newstime` varchar(100) default NULL,
  `showavatars` char(3) default NULL,
  `commentsorder` varchar(4) default NULL,
  `commentstime` varchar(100) default NULL,
  `commentslength` varchar(6) default NULL,
  `cppage` varchar(10) default NULL,
  `npagintation` text,
  `cpagintation` text,
  FULLTEXT KEY `header` (`header`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `news12_options`
-- 

INSERT INTO `news12_options` VALUES ('<html>\r\n<head>\r\n<title>N-13 News</title>\r\n<style type="text/css">\r\n<!--\r\nselect , option , textarea , input {\r\nborder-right : 1px solid #808080;\r\nborder-top : 1px solid #808080;\r\nborder-bottom : 1px solid #808080;\r\nborder-left : 1px solid #808080;\r\ncolor : #000000;\r\nfont-size : 11px;\r\nfont-family : Verdana, Arial, Helvetica, sans-serif;\r\nbackground-color : #ffffff;\r\n}\r\na:active , a:visited , a:link {\r\ncolor : #666666;\r\ntext-decoration : none;\r\nfont-family : Verdana, Arial, Helvetica, sans-serif;\r\nfont-size : 8pt;\r\n}\r\na:hover {\r\ncolor : #000066;\r\ntext-decoration : none;\r\nfont-family : Verdana, Arial, Helvetica, sans-serif;\r\nfont-size : 8pt;\r\n}\r\n.panel {\r\n-moz-border-radius:6px;\r\nborder: 1px dotted silver; background-color: #F4F4F4;\r\n}\r\n.panelwhite {\r\n-moz-border-radius:6px;\r\nborder: 1px dotted silver; background-color: #FFFFFF;\r\n}\r\nBODY , TD , TR {\r\ntext-decoration : none;\r\nfont-family : Verdana, Arial, Helvetica, sans-serif;\r\nfont-size : 8pt;\r\ncursor : default;\r\n\r\n}\r\n-->\r\n</style>\r\n</head>\r\n<body>', '<table class=panel width="100%">\r\n<tr><td>\r\n{title}\r\n<br><br>\r\n{story}\r\n<br>\r\n{avatar}\r\n<br>\r\n<div align="center">Posted by <u>{author}</u> | Posted on <u>\r\n {date}</u> | [comments] Comments {comments}[/comments] </div>\r\n</td></tr>\r\n</table>\r\n<br>', '</body>\r\n</html>', '<table class=panelwhite width=100%>\r\n<tr>\r\n<td>Posted by <font color="#ABABAB">{author}</font></td>\r\n</tr>\r\n<tr>\r\n<td>\r\n{message}\r\n<div align="center"> Posted by {author} <u>\r\n<font color="#0000FF">{date}</font></u></div>\r\n</font></td>\r\n</tr>\r\n</table>\r\n<br>', '&lt;form method="POST" action="?comments=true&id={id}"&gt;\r\n Username:&lt;br&gt;\r\n &lt;input type="text" name="T1" size="20" value="{name}"&gt;&lt;br&gt;\r\n Email: &lt;br&gt;\r\n &lt;input type="text" name="T2" size="20" value="{email}"&gt;&lt;br&gt;\r\n Message:&lt;br&gt;\r\n &lt;textarea rows="7" name="S1" cols="25"&gt;&lt;/textarea&gt;\r\n &lt;br&gt;\r\n &lt;input type="submit" value="Submit" name="B1"&gt;\r\n&lt;/form&gt;', '5', 'DESC', 'l jS Y', 'YES', 'DESC', 'l jS Y', '1000', '3', '<center>[prev-link]<< Previous[/prev-link] {pages} [next-link]Next >>[/next-link]</center>', '<center>[prev-link]<< Previous[/prev-link] {pages} [next-link]Next >>[/next-link]</center>');

-- --------------------------------------------------------

-- 
-- Table structure for table `news12_smilies`
-- 

CREATE TABLE `news12_smilies` (
  `path` text,
  `keycode` text,
  `type` text,
  `id` text
) TYPE=MyISAM;

-- 
-- Dumping data for table `news12_smilies`
-- 

INSERT INTO `news12_smilies` VALUES ('smilies/chris.gif', ':CHRIS!:', 'news', '1');
INSERT INTO `news12_smilies` VALUES ('smilies/angry.gif', ':angry:', 'news', '2');
INSERT INTO `news12_smilies` VALUES ('smilies/flowers.gif', ':flowers:', 'news', '3');
INSERT INTO `news12_smilies` VALUES ('smilies/wassat.gif', ':wassat:', 'news', '4');
INSERT INTO `news12_smilies` VALUES ('smilies/blink.gif', ':blink:', 'news', '5');
INSERT INTO `news12_smilies` VALUES ('smilies/fear.gif', ':ninja:', 'news', '6');
INSERT INTO `news12_smilies` VALUES ('smilies/pinch.gif', ':pinch:', 'news', '7');
INSERT INTO `news12_smilies` VALUES ('smilies/nuke.gif', ':nuke:', 'news', '8');
INSERT INTO `news12_smilies` VALUES ('smilies/blushing.gif', ':blushing:', 'news', '9');
INSERT INTO `news12_smilies` VALUES ('smilies/crying.gif', ':crying:', 'news', '10');
INSERT INTO `news12_smilies` VALUES ('smilies/devil.gif', ':devil:', 'news', '11');
INSERT INTO `news12_smilies` VALUES ('smilies/ermm.gif', ':ermm:', 'news', '12');
INSERT INTO `news12_smilies` VALUES ('smilies/excl.gif', ':excl:', 'news', '13');
INSERT INTO `news12_smilies` VALUES ('smilies/getlost.gif', ':getlost:', 'news', '14');
INSERT INTO `news12_smilies` VALUES ('smilies/grin.gif', ':grin:', 'news', '15');
INSERT INTO `news12_smilies` VALUES ('smilies/happy.gif', ':happy:', 'news', '16');
INSERT INTO `news12_smilies` VALUES ('smilies/hug.gif', ':hug:', 'news', '17');
INSERT INTO `news12_smilies` VALUES ('smilies/innocent.gif', ':innocent:', 'news', '18');
INSERT INTO `news12_smilies` VALUES ('smilies/kiss.gif', ':kiss:', 'news', '19');
INSERT INTO `news12_smilies` VALUES ('smilies/laughing.gif', ':laugh:', 'news', '20');
INSERT INTO `news12_smilies` VALUES ('smilies/noexpression.gif', ':blah:', 'news', '21');
INSERT INTO `news12_smilies` VALUES ('smilies/online2long.gif', ':online2long:', 'news', '22');
INSERT INTO `news12_smilies` VALUES ('smilies/original.gif', ':smile:', 'news', '23');
INSERT INTO `news12_smilies` VALUES ('smilies/phone.gif', ':phone:', 'news', '24');
INSERT INTO `news12_smilies` VALUES ('smilies/sad.gif', ':sad:', 'news', '25');
INSERT INTO `news12_smilies` VALUES ('smilies/santa.gif', ':santa:', 'news', '26');
INSERT INTO `news12_smilies` VALUES ('smilies/shaun.gif', ':sheep:', 'news', '27');
INSERT INTO `news12_smilies` VALUES ('smilies/tongue.gif', ':tongue:', 'news', '28');
INSERT INTO `news12_smilies` VALUES ('smilies/wink.gif', ':wink:', 'news', '29');

-- --------------------------------------------------------

-- 
-- Table structure for table `news12_story`
-- 

CREATE TABLE `news12_story` (
  `title` varchar(255) default NULL,
  `story` text,
  `author` varchar(100) default NULL,
  `date` varchar(100) default NULL,
  `email` varchar(100) default NULL,
  `avatar` varchar(255) default NULL,
  `id` varchar(20) default NULL
) TYPE=MyISAM;

-- 
-- Dumping data for table `news12_story`
-- 

INSERT INTO `news12_story` VALUES ('Installation successful', 'This is a test story inserted to show everything is working fine.<br />\r\n<br />\r\nYou can remove this story by logging into the admin area.', 'Admin', 'Wednesday 7th 2005', '', '', '1');
