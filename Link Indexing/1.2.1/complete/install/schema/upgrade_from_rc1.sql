-- 
-- Table structure for table `pl_skinfiles`
-- 

DROP TABLE IF EXISTS `pl_skinfiles`;
CREATE TABLE `pl_skinfiles` (
  `fileid` int(11) NOT NULL auto_increment,
  `skinid` int(11) NOT NULL default '0',
  `shortie` varchar(255) NOT NULL default '',
  `code` text NOT NULL,
  PRIMARY KEY  (`fileid`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `pl_skinfiles`
-- 

INSERT INTO `pl_skinfiles` VALUES (1, 1, 'overall_footer', '	</div>\r\n\r\n	<div class="subfoot">\r\n		{DATETIME}\r\n	</div>\r\n	\r\n	<div class="footer">\r\n		&copy; {SITENAME}. All rights reserved.<br />\r\n		<!-- This copyright notice and link may not be removed under the licence -->\r\n		Powered by Particle Links {VERSION} &copy; 2005 <a href="http://www.particlesoft.net/">Particle Soft</a>.\r\n	</div>\r\n</div>\r\n</body>\r\n</html>');
INSERT INTO `pl_skinfiles` VALUES (2, 1, 'overall_header', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\r\n<html>\r\n<head>\r\n<title>{PAGETITLE}</title>\r\n<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />\r\n<meta name="Description" content="{META_DESCRIPTION}" />\r\n<meta name="Keywords" content="{META_KEYWORDS}" />\r\n<style type="text/css">\r\n{CSS_CODE}\r\n</style>\r\n<script language="JavaScript" type="text/javascript" src="{ROOT}shared/functions.js"></script>\r\n</head>\r\n<body>\r\n<div class="container">\r\n	<div class="search">\r\n		<form action="{ROOT}search.php" method="get">\r\n			<input type="text" size="15" id="q" name="q" maxlength="255" />\r\n			<input type="submit" value="Search!" />\r\n		</form>\r\n	</div>\r\n	\r\n	<h1>{SITENAME}</h1>\r\n	\r\n	<div class="breadcrumbs">\r\n		<div class="adminlink"><a href="{ROOT}admin.php">Admin</a></div>\r\n		{BREADCRUMBS}\r\n	</div>\r\n	\r\n	<div class="main">\r\n');

-- 
-- Table structure for table `pl_skinsets`
-- 

DROP TABLE IF EXISTS `pl_skinsets`;
CREATE TABLE `pl_skinsets` (
  `skinid` int(11) NOT NULL auto_increment,
  `visible` int(11) NOT NULL default '1',
  `title` varchar(255) NOT NULL default '',
  `imagesdir` varchar(255) NOT NULL default '',
  `css` text NOT NULL,
  PRIMARY KEY  (`skinid`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `pl_skinsets`
-- 

INSERT INTO `pl_skinsets` VALUES (1, 1, 'Particle Blue', 'images/', 'A:link, A:visited, A:active{\r\n	color: #0000FF;\r\n	text-decoration: underline;\r\n}\r\nA:hover{\r\n	color: #FF0000;\r\n	text-decoration: underline;\r\n}\r\n.adminlink{\r\n	float: right;\r\n	padding-left: 50px;\r\n}\r\nbody{\r\n	background: url("{IMAGES_DIR}background.jpg") repeat-x;\r\n	font-family: Georgia, Arial, Verdana, Sans-Serif;\r\n}\r\n.breadcrumbs, .subfoot{\r\n	padding: 3px;\r\n	background: #DDDDDD;\r\n	border: #999999 1px solid;\r\n	color: #333333;\r\n}\r\n.breadcrumbs A:link, .breadcrumbs A:visited, .breadcrumbs A:active, .breadcrumbs A:hover{\r\n	color: #333333;\r\n	text-decoration: underline;\r\n	font-weight: bold;\r\n}\r\n.footer{\r\n	padding: 5px;\r\n	font-size: small;\r\n	text-align: center;\r\n}\r\nform{\r\n	margin: 3px;\r\n}\r\nh1{\r\n	margin: 3px;\r\n	font-size: 150%;\r\n}\r\n.login{\r\n	font-size: 150%;\r\n}\r\n.main{\r\n	padding: 5px 0px 5px 0px;\r\n}\r\n.search{\r\n	float: right;\r\n}\r\n.search, .search input{\r\n	font-size: 75%;\r\n}\r\n.search form{\r\n	margin: 0px;\r\n}\r\n.smalltext{\r\n	font-size: 75%;\r\n}\r\n.subfoot{\r\n	text-align: center;\r\n}');

-- 
-- Dumping data for table `pl_config`
-- 

INSERT INTO `pl_config` VALUES ('defaultskin', '1', 'The ID number of the skinset you want to be used as default.');
INSERT INTO `pl_config` VALUES ('dateformat', 'j F Y H:i A', 'This is the format according to PHP''s <a href="http://uk.php.net/date">date</a> format which is used at the bottom of the page.');
INSERT INTO `pl_config` VALUES ('skinselector', 'true', 'Set to true to show the skin selector box (allows users to pick which skin to use) or set to false to disable it.');