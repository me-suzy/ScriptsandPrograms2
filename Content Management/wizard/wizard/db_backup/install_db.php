<?php
/* ########################################################################

Copyright (C) 2005
Philip Shaddock
http://www.wizardinteractive.com

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

######################################################################## */

include_once '../inc/config_cms/configuration.php';

//test db 
    
	
	$db = mysql_connect(DB_SERVER, DB_USER, DB_PASS);
	if (!$db) { 
		$message = "<br>Error : your /inc/config_cms/configuration.php database settings are wrong.";
	}
	if (!$message && !mysql_select_db(DB_DATABASE, $db)) { 
		$message = "<br>Error : could not find the database ".DB_DATABASE." on the server..."; 
	}



if (!$message) {
	
	
	#==== sql scripts ====#
	
	
	include_once '../inc/db/db.php';
	$db = new DB(); 
	
$req = " 
CREATE TABLE `".DB_PREPEND."admin` (
  `id` int(11) NOT NULL auto_increment,
  `position` int(11) NOT NULL default '0',
  `title` varchar(30) NOT NULL default '',
  `parentId` int(11) NOT NULL default '0',
  `category` varchar(20) NOT NULL default '',
  `pageName` varchar(250) NOT NULL default '#',
  `visible` char(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=93
";
$db->query($req) or die(mysql_error());

$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (1, 2, 'Pages', 0, '', '#', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (2, 2, 'New Page', 1, 'page', 'pagenew.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (9, 3, 'Edit Page Properties', 1, 'page', 'pageedit.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (10, 5, 'Move Page', 1, 'page', 'pagemove.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (12, 6, 'Edit Group', 24, 'setup', 'groupEdit.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (19, 3, 'Menu-Sitemap', 0, '', '#', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (20, 1, 'Rebuild Menu', 19, 'menuSitemap', 'rebuildMenu.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (24, 1, 'Settings', 0, '', '#', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (25, 2, 'Identities', 24, 'setup', 'configure.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (27, 3, 'Users', 0, '', '#', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (28, 1, 'List Users', 27, 'user', 'userlist.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (29, 2, 'Add User', 27, 'user', 'useradd.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (30, 4, 'List Groups', 24, 'setup', 'groupList.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (31, 5, 'Add Group', 24, 'setup', 'groupAdd.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (32, 2, 'Edit User', 27, 'user', 'useredit.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (35, 1, 'Summaries', 70, 'stats', 'summaries.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (38, 4, 'Search Keywords', 70, 'stats', 'search.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (39, 8, 'Archive Stats', 70, 'stats', 'archive.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (40, 9, 'Archives', 70, 'stats', 'history.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (70, 7, 'Stats', 0, '', '#', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (71, 7, 'Raw Log', 70, 'stats', 'rawlog.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (72, 2, 'Page Views', 70, 'stats', 'pageviews.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (11, 1, 'List Pages', 1, 'page', 'pageList.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (75, 3, 'Permissions', 24, 'setup', 'permissions.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (76, 1, 'Interface', 24, 'setup', 'interface.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (77, 3, 'Top Members', 70, 'stats', 'topmembers.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (80, 6, 'Sort Pages', 1, 'page', 'pagerenumber.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (85, 4, 'Delete Page', 1, 'page', 'pageDelete.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (88, 2, 'Menu Properties', 19, 'menuSitemap', 'menuDepth.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (89, 3, 'Sitemap Properties', 19, 'menuSitemap', 'Sitemap.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (90, 6, 'Blog', 0, 'blog', '#', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (91, 1, 'List Comments', 90, 'blog', 'commentslist.php', '1');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."admin` VALUES (92, 2, 'Edit Comments', 90, 'blog', 'editComments.php', '1');
";
$db->query($req) or die(mysql_error());

$req = "
CREATE TABLE `".DB_PREPEND."comments` (
  `article_id` int(11) NOT NULL default '0',
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(30) NOT NULL default 'No Title Found',
  `page` varchar(255) NOT NULL default '',
  `username` varchar(255) NOT NULL default 'Guest',
  `subject` varchar(255) NOT NULL default '',
  `contact` varchar(255) NOT NULL default '',
  `comment` text NOT NULL,
  `ip` varchar(15) NOT NULL default '0',
  `date` varchar(255) NOT NULL default '',
  `time` varchar(11) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;
";
$db->query($req) or die(mysql_error());

$req = "
CREATE TABLE `".DB_PREPEND."config` (
  `id` tinyint(1) NOT NULL default '1',
  `name` varchar(250) default NULL,
  `siteAdmin` varchar(60) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `address` varchar(250) NOT NULL default '',
  `city` varchar(50) NOT NULL default '',
  `state` varchar(150) NOT NULL default '',
  `country` varchar(100) NOT NULL default '',
  `postal` varchar(15) NOT NULL default '',
  `phone` varchar(100) NOT NULL default '',
  `fax` varchar(25) NOT NULL default '',
  `copyright` text NOT NULL,
  `company` text NOT NULL,
  `reg_webmaster` enum('on','off') NOT NULL default 'on',
  `user_add` enum('on','off') NOT NULL default 'on',
  `user_view` enum('on','off') NOT NULL default 'on',
  `user_edit` enum('on','off') NOT NULL default 'on',
  `user_approve` enum('on','off') NOT NULL default 'off',
  `register` enum('on','off') NOT NULL default 'on',
  `login` enum('on','off') NOT NULL default 'on',
  `search` enum('on','off') NOT NULL default 'on',
  `searchRestrict` enum('on','off') NOT NULL default 'off',
  `topmenu` varchar(5) NOT NULL default '1',
  `leftmenu` varchar(5) NOT NULL default '2',
  `sitemapDepth` varchar(5) NOT NULL default '5',
  `newsletter` enum('on','off') NOT NULL default 'off'
) ENGINE=MyISAM ;
";
$db->query($req) or die(mysql_error());

$req = "
INSERT INTO `".DB_PREPEND."config` VALUES (1, 'Wizard Interactive', 'Wizard Interactive', 'info@ragepictures.com', '1008 London St.', 'New Westminster', 'B.C.', 'Canada', 'V3M 3B8', '', '', '(c) 2005 Rage Pictures', 'Rage Pictures Inc.', 'off', 'on', 'on', 'on', 'off', 'on', 'on', 'on', 'off', '5', '0', '5', 'off');
";
$db->query($req) or die(mysql_error());

$req = "
CREATE TABLE `".DB_PREPEND."groups` (
  `gid` int(11) NOT NULL auto_increment,
  `name` varchar(50) default NULL,
  `dsc` varchar(255) NOT NULL default '',
  UNIQUE KEY `gid` (`gid`)
) ENGINE=MyISAM  AUTO_INCREMENT=5 ;
";
$db->query($req) or die(mysql_error());

$req = "
INSERT INTO `".DB_PREPEND."groups` VALUES (1, 'Webmaster', 'Access to everything.');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."groups` VALUES (2, 'Administrator', 'Permissions are assigned to this group under ''Settings''');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."groups` VALUES (3, 'Registered', 'Registered users.');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."groups` VALUES (4, 'Guest', 'Visitors to the site.');
";
$db->query($req) or die(mysql_error());

$req = "
CREATE TABLE `".DB_PREPEND."groupusers` (
  `uid` int(11) NOT NULL default '0',
  `gid` int(11) NOT NULL default '0'
) ENGINE=MyISAM ;
";
$db->query($req) or die(mysql_error());

$req = "
INSERT INTO `".DB_PREPEND."groupusers` VALUES (1, 4);
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."groupusers` VALUES (1, 3);
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."groupusers` VALUES (1, 1);
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."groupusers` VALUES (1, 2);
";
$db->query($req) or die(mysql_error());


$req = "
CREATE TABLE `".DB_PREPEND."hits` (
  `ID` int(50) NOT NULL auto_increment,
  `Host` varchar(100) NOT NULL default '',
  `PageId` text NOT NULL,
  `Title` text NOT NULL,
  `Date` datetime NOT NULL default '0000-00-00 00:00:00',
  `Member` varchar(40) NOT NULL default '',
  `Referer` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  AUTO_INCREMENT=5 ;
";
$db->query($req) or die(mysql_error());


$req = "
CREATE TABLE `".DB_PREPEND."hitsArchive` (
  `id` int(11) NOT NULL auto_increment,
  `Date` date NOT NULL default '0000-00-00',
  `hits` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  AUTO_INCREMENT=1 ;
";
$db->query($req) or die(mysql_error());

$req = "
CREATE TABLE `".DB_PREPEND."menuData` (
  `id` int(11) NOT NULL default '0',
  `serialized` blob NOT NULL
) ENGINE=MyISAM ;
";
$db->query($req) or die(mysql_error());

$req = "
INSERT INTO `".DB_PREPEND."menuData` VALUES (0, 0x613a313a7b693a303b613a373a7b693a303b693a313b693a313b733a313a2231223b693a323b733a343a22486f6d65223b693a333b733a313a2230223b693a343b733a393a22696e6465782e706870223b693a353b733a323a226f6e223b693a363b733a313a2234223b7d7d);
";
$db->query($req) or die(mysql_error());

$req = "
INSERT INTO `".DB_PREPEND."menuData` VALUES (1, 0x613a313a7b693a303b613a373a7b693a303b693a313b693a313b733a313a2231223b693a323b733a343a22486f6d65223b693a333b733a313a2230223b693a343b733a393a22696e6465782e706870223b693a353b733a323a226f6e223b693a363b733a313a2234223b7d7d);
";
$db->query($req) or die(mysql_error());

$req = "
INSERT INTO `".DB_PREPEND."menuData` VALUES (2, 0x613a323a7b693a303b613a373a7b693a303b733a313a2230223b693a313b733a313a2231223b693a323b733a343a22486f6d65223b693a333b733a313a2230223b693a343b733a393a22696e6465782e706870223b693a353b733a323a226f6e223b693a363b733a313a2234223b7d693a313b613a373a7b693a303b693a303b693a313b733a313a2232223b693a323b733a343a22426c6f67223b693a333b733a313a2230223b693a343b733a383a22626c6f672e706870223b693a353b733a323a226f6e223b693a363b733a313a2234223b7d7d);
";
$db->query($req) or die(mysql_error());

$req = "
INSERT INTO `".DB_PREPEND."menuData` VALUES (3, 0x613a313a7b693a303b613a373a7b693a303b693a313b693a313b733a313a2231223b693a323b733a343a22486f6d65223b693a333b733a313a2230223b693a343b733a393a22696e6465782e706870223b693a353b733a323a226f6e223b693a363b733a313a2234223b7d7d);
";
$db->query($req) or die(mysql_error());

$req = "
CREATE TABLE `".DB_PREPEND."pages` (
  `id` int(20) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default 'temp',
  `filename` varchar(255) NOT NULL default 'newpage.php',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `description` text NOT NULL,
  `keywords` text NOT NULL,
  `robots` varchar(50) NOT NULL default 'ALL',
  `parentId` int(20) NOT NULL default '0',
  `menu` enum('on','off') NOT NULL default 'on',
  `position` int(7) NOT NULL default '9999',
  `admin` enum('0','1') NOT NULL default '0',
  `permit` char(3) NOT NULL default '4',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  FULLTEXT KEY `robots` (`robots`),
  FULLTEXT KEY `keywords` (`keywords`),
  FULLTEXT KEY `description` (`description`),
  FULLTEXT KEY `filename` (`filename`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `description_2` (`description`),
  FULLTEXT KEY `title_2` (`title`),
  FULLTEXT KEY `filename_2` (`filename`),
  FULLTEXT KEY `description_3` (`description`),
  FULLTEXT KEY `keywords_2` (`keywords`),
  FULLTEXT KEY `keywords_3` (`keywords`),
  FULLTEXT KEY `title_3` (`title`,`keywords`,`description`),
  FULLTEXT KEY `description_4` (`description`),
  FULLTEXT KEY `keywords_4` (`keywords`)
) ENGINE=MyISAM  AUTO_INCREMENT=51 ;
";
$db->query($req) or die(mysql_error());

$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (1, 'Home', 'index.php', '0000-00-00 00:00:00', 'Rage Pictures Wizard Site administration and content management (CMS) for websites using PHP and Javascript scripting. Good complement to Macromedia Contribute and all HTML editors.', 'PHP, CMS, content management, site administration, menu management', 'ALL', 0, 'on', 1, '0', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (2, 'Contact Form', 'contact_form.php', '0000-00-00 00:00:00', 'Contact us.', '', 'ALL', 0, 'off', 2, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (3, 'Site Map', 'sitemap.php', '0000-00-00 00:00:00', 'Explore this site using the sitemap.', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (4, 'Registration Form', 'register_form.php', '0000-00-00 00:00:00', 'Registration form for this site.', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (5, 'Login Link', 'login.php', '0000-00-00 00:00:00', '', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (6, 'About Us', 'aboutus.php', '0000-00-00 00:00:00', 'About us page.', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (7, 'Privacy Policy', 'privacypolicy.php', '0000-00-00 00:00:00', 'This site''s privacy policy.', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (8, 'Reserved', '', '0000-00-00 00:00:00', '', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (9, 'Reserved', '', '0000-00-00 00:00:00', '', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (10, 'Reserved', '', '0000-00-00 00:00:00', '', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (11, 'Reserved', '', '0000-00-00 00:00:00', '', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (12, 'Reserved', '', '0000-00-00 00:00:00', '', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (13, 'Reserved', '', '0000-00-00 00:00:00', '', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (14, 'Reserved', '', '0000-00-00 00:00:00', '', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (15, 'Reserved', '', '0000-00-00 00:00:00', '', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (16, 'Reserved', '', '0000-00-00 00:00:00', '', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (17, 'Reserved', '', '0000-00-00 00:00:00', '', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (18, 'Reserved', '', '0000-00-00 00:00:00', '', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (19, 'Reserved', '', '0000-00-00 00:00:00', '', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (20, 'Reserved', '', '0000-00-00 00:00:00', '', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (21, '1col_blog.tmpl.php', '1col_blog.tmpl.php', '0000-00-00 00:00:00', '', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (22, '1col_horizontal.tmpl.php', '1col_horizontal.tmpl.php', '0000-00-00 00:00:00', '', '', 'ALL', 0, 'on', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (23, '2col_basic.tmpl.php', '2col_basic.tmpl.php', '0000-00-00 00:00:00', '', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (24, '2col_blog.tmpl.php', '2col_blog.tmpl.php', '0000-00-00 00:00:00', '', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (25, '3col_basic.tmpl.php', '3col_basic.tmpl.php', '0000-00-00 00:00:00', '', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());
$req = "
INSERT INTO `".DB_PREPEND."pages` VALUES (26, '2col_poll.tmpl.php', '2col_poll.tmpl.php', '0000-00-00 00:00:00', '', '', 'ALL', 0, 'off', 9999, '1', '4');
";
$db->query($req) or die(mysql_error());

$req = "
CREATE TABLE `".DB_PREPEND."users` (
  `uid` int(10) unsigned NOT NULL auto_increment,
  `first_name` varchar(25) NOT NULL default '',
  `last_name` varchar(35) NOT NULL default '',
  `username` varchar(15) NOT NULL default '',
  `country` varchar(35) default NULL,
  `organization` varchar(50) default NULL,
  `phone` varchar(30) default NULL,
  `fax` varchar(30) default NULL,
  `address` varchar(250) default NULL,
  `address2` varchar(250) default NULL,
  `city` varchar(50) default NULL,
  `state` varchar(50) default NULL,
  `postal` varchar(15) default NULL,
  `password` varchar(150) NOT NULL default '',
  `email` varchar(200) default NULL,
  `register_date` datetime default NULL,
  `logins` int(9) default '0',
  `last_login` datetime default NULL,
  `activated` enum('0','1') NOT NULL default '1',
  `comment` text,
  `subscribe` varchar(8) NOT NULL default 'yeshtml',
  `is_confirmed` int(11) NOT NULL default '0',
  `confirm_hash` text NOT NULL,
  `remote_addr` text NOT NULL,
  `lastposttime` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`uid`),
  KEY `username` (`username`)
) ENGINE=MyISAM  AUTO_INCREMENT=6 ;
";
$db->query($req) or die(mysql_error());


$req = "
INSERT INTO `".DB_PREPEND."users` VALUES (1, 'Web', 'Master', 'admin', 'Canada', 'Rage Pictures', '', '', '1008 London St.', '', 'New Westminster', 'British Columbia', 'V3M 3B8', 'd8d3a01ba7e5d44394b6f0a8533f4647', 'info@ragepictures.com', '2004-05-08 07:35:07', 4, '2005-10-19 17:09:25', '1', '', 'yeshtml', 1, '087214d2ee526514e3ba1a2bdab32a38', '206.236.180.153', 0);
";
$db->query($req) or die(mysql_error());
        
	
	$db->close();


	
	
	// user message
	$content = "<p>&nbsp;</p><p align=\"center\">Wizard database installed.</p>";
	$content = $content."<p align=\"center\"><strong>Security Threat: delete the /db_backup directory on the server!!</strong></p>";
	$content = $content."<p align=\"center\">Administration panel: <a href=\"../admin.php\">here</a> Login: admin (username) wizard (password)</p>"; 
}
else {
$content = "
Installation failed.
";
}
?>


<html>
<head>
<title>Wizard Site Framework Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px}
-->
</style>
</head> 
<body bgcolor="#FFFFCC" text="#000000"> 
<?php
echo "<b> $message </b><br />&nbsp;";
echo $content ;
?>
</body>
</html>
