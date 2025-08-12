-- 
-- Table structure for table `pl_config`
-- 

DROP TABLE IF EXISTS `pl_config`;
CREATE TABLE `pl_config` (
  `config_name` varchar(255) NOT NULL default '',
  `config_value` varchar(255) NOT NULL default '',
  `config_help` text NOT NULL
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `pl_links`
-- 

DROP TABLE IF EXISTS `pl_links`;
CREATE TABLE `pl_links` (
  `linkid` int(11) NOT NULL auto_increment,
  `topicid` int(11) NOT NULL default '0',
  `priority` int(11) NOT NULL default '0',
  `postdate` int(11) NOT NULL default '1124620234',
  `website` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`linkid`),
  FULLTEXT KEY `website` (`website`),
  FULLTEXT KEY `description` (`description`),
  FULLTEXT KEY `url` (`url`)
) TYPE=MyISAM AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `pl_newtopics`
-- 

DROP TABLE IF EXISTS `pl_newtopics`;
CREATE TABLE `pl_newtopics` (
  `newtopicid` int(11) NOT NULL auto_increment,
  `topicid` int(11) NOT NULL default '0',
  `postdate` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `ip` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  PRIMARY KEY  (`newtopicid`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `pl_phrases`
-- 

DROP TABLE IF EXISTS `pl_phrases`;
CREATE TABLE `pl_phrases` (
  `phraseid` int(11) NOT NULL auto_increment,
  `phrase_name` varchar(255) NOT NULL default '',
  `phrase_value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`phraseid`)
) TYPE=MyISAM AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `pl_queue`
-- 

DROP TABLE IF EXISTS `pl_queue`;
CREATE TABLE `pl_queue` (
  `queueid` int(11) NOT NULL auto_increment,
  `topicid` int(11) NOT NULL default '0',
  `postdate` int(11) NOT NULL default '0',
  `website` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `ip` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`queueid`)
) TYPE=MyISAM AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `pl_skinbase`
-- 

DROP TABLE IF EXISTS `pl_skinbase`;
CREATE TABLE `pl_skinbase` (
  `baseid` int(11) NOT NULL auto_increment,
  `shortie` varchar(255) NOT NULL default '',
  `code` text NOT NULL,
  PRIMARY KEY  (`baseid`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

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
) TYPE=MyISAM AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `pl_skinhistory`
-- 

DROP TABLE IF EXISTS `pl_skinhistory`;
CREATE TABLE `pl_skinhistory` (
  `historyid` int(11) NOT NULL auto_increment,
  `postdate` int(11) NOT NULL default '0',
  `skinid` int(11) NOT NULL default '0',
  `shortie` varchar(255) NOT NULL default '',
  `code` text NOT NULL,
  PRIMARY KEY  (`historyid`)
) TYPE=MyISAM AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

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
) TYPE=MyISAM AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `pl_topics`
-- 

DROP TABLE IF EXISTS `pl_topic`;
CREATE TABLE `pl_topics` (
  `topicid` int(11) NOT NULL auto_increment,
  `visible` int(11) NOT NULL default '1',
  `parent` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `keywords` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `rules` text NOT NULL,
  PRIMARY KEY  (`topicid`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `keywords` (`keywords`)
) TYPE=MyISAM AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `pl_users`
-- 

DROP TABLE IF EXISTS `pl_users`;
CREATE TABLE `pl_users` (
  `userid` int(11) NOT NULL auto_increment,
  `username` varchar(255) NOT NULL default '',
  `password` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `ipaddress` varchar(255) NOT NULL default '',
  `joindate` int(11) NOT NULL default '0',
  `logindate` int(11) NOT NULL default '0',
  `status` int(11) NOT NULL default '1',
  PRIMARY KEY  (`userid`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;
-- 
-- Dumping data for table `pl_config`
-- 

INSERT INTO `pl_config` VALUES ('sitename', 'Particle Links Demo', 'Name of the directory or website');
INSERT INTO `pl_config` VALUES ('topicpage', 'index.php?topic=', 'This can be used to change the filename or more usually to enable friendly URLs, read the  documentation for instructions on how to do that');
INSERT INTO `pl_config` VALUES ('breadcrumb', '&raquo;', 'The symbol between each page in the navigation bar');
INSERT INTO `pl_config` VALUES ('metadescription', 'directory', 'Describe your site in natural language, for example, My website directory.');
INSERT INTO `pl_config` VALUES ('metakeywords', 'directory,websites', 'Keywords used in the meta tag seperate by commas such as directory,web links,etc');
INSERT INTO `pl_config` VALUES ('version', '1.2.1', '');
INSERT INTO `pl_config` VALUES ('versionint', '8', '');
INSERT INTO `pl_config` VALUES ('created', '1123250579', '');
INSERT INTO `pl_config` VALUES ('defaultskin', '1', 'The ID number of the skinset you want to be used as default.');
INSERT INTO `pl_config` VALUES ('dateformat', 'j F Y H:i A', 'This is the format according to PHP''s <a href="http://uk.php.net/date">date</a> format which is used at the bottom of the page.');
INSERT INTO `pl_config` VALUES ('virtualpath', '/particlelinks/', 'The folder from the web. For instance if it is on a domain just / or if it was in a sub-folder called example it would be /example/.');
INSERT INTO `pl_config` VALUES ('skinselector', 'true', 'Set to true to show the skin selector box (allows users to pick which skin to use) or set to false to disable it.');
INSERT INTO `pl_config` VALUES ('showadminlink', 'true', 'Set this to true to show the admin link in the breadcrumbs section at the top of the page (or wherever it has been placed by the skin) or set to false to hide the link.');
INSERT INTO `pl_config` VALUES ('topicdropdown', 'true', 'Set this to true to use the dropdown box listing categories when editing links and sub-topics. However if you have lots of topics (like thousands or if your paranoid then hundreds) then set this to false to use a textbox asking for the ID number.');
INSERT INTO `pl_config` VALUES ('notifyuser', 'true', 'If set to true it will email users using PHP''s mail function as to whether their submission has been accepted or rejected.');
INSERT INTO `pl_config` VALUES ('usersubmissions', 'true', 'Set this to true to allow users to suggest URLs. Or set it to false to disable this.');
INSERT INTO `pl_config` VALUES ('dropdownfullpaths', 'true', 'If you are using the topic drop down menu, set this to true to display full paths such as topic a > topic b > topic c or set it to false to use #id number - name (uses less resources especially with big directories).');
INSERT INTO `pl_config` VALUES ('topicresults', '10', 'Number of related topics (if any) to show on the search results page. Set to 99999 to essentially show them all (not recommended but setting it to  something like 50 won''t do any harm) or set to 0 to disable.');
INSERT INTO `pl_config` VALUES ('recentlyadded', '10', 'Number of sites listed on the recently added page. You can set this to 0 to disable the feature (users will be redirected back to the main topic page if the feature is disabled).');
INSERT INTO `pl_config` VALUES ('showrecentlink', 'true', 'Set to true to show the recently added page link at the top next to the admin link or set to false to disable it.');
INSERT INTO `pl_config` VALUES ('languagecode', 'en-uk', 'This describes the natural language used. The default is en-uk however you may want to change this if you translate into another language (including US English or other variation). If you can''t find the language code then leave it blank.');
INSERT INTO `pl_config` VALUES ('topicsubmissions', 'true', 'Set this to true to allow users to suggest new topics or set to false to disable it. User submissions must be enabled in order for this to be active.');
INSERT INTO `pl_config` VALUES ('showsubmissionrules', 'false', 'Set this to true to show the submission rules on the suggest URL page or set to false to hide it. You can edit the rules on the phrases page. You can also set rules for individual topics by editing the topic.');
INSERT INTO `pl_config` VALUES ('showstats', 'true', 'Set to true to show the stats on the homepage or set to false to hide them. This could save a bit of resources if you have a big directory but then it also reduces bragging power ;).');
INSERT INTO `pl_config` VALUES ('stretchedmenu', 'false', 'If you are short of space, you can make the menu column of the admin page run right accross the top of the page and give the main content the full width below it. It looks terrible as the links are still in vertical list but it does free up the space if you need it.');
INSERT INTO `pl_config` VALUES ('linktarget', '_parent', 'This allows you to control the target of the links in the directory. Set it to _parent to open them in the current window or _blank to open links in a new window.');

-- 
-- Dumping data for table `pl_phrases`
-- 

INSERT INTO `pl_phrases` VALUES (1, 'top', 'Top');
INSERT INTO `pl_phrases` VALUES (2, 'suggesturl', 'Suggest URL');
INSERT INTO `pl_phrases` VALUES (3, 'search', 'Search');
INSERT INTO `pl_phrases` VALUES (4, 'sitename', 'Site Name');
INSERT INTO `pl_phrases` VALUES (5, 'url', 'URL');
INSERT INTO `pl_phrases` VALUES (6, 'description', 'Description');
INSERT INTO `pl_phrases` VALUES (7, 'emailaddress', 'Email Address');
INSERT INTO `pl_phrases` VALUES (8, 'results', 'results');
INSERT INTO `pl_phrases` VALUES (9, 'topic', 'Topic');
INSERT INTO `pl_phrases` VALUES (10, 'noresults', 'No results were found for this search term, please try another');
INSERT INTO `pl_phrases` VALUES (11, 'resultspages', 'Results Pages');
INSERT INTO `pl_phrases` VALUES (12, 'submit_notitle', 'No title entered');
INSERT INTO `pl_phrases` VALUES (13, 'submit_nourl', 'No URL entered');
INSERT INTO `pl_phrases` VALUES (14, 'submit_nodescription', 'No description entered');
INSERT INTO `pl_phrases` VALUES (15, 'submit_missingtopic', 'Unable to locate the topic');
INSERT INTO `pl_phrases` VALUES (16, 'submit_urlinqueue', 'This URL is already in the queue');
INSERT INTO `pl_phrases` VALUES (17, 'submit_success', 'Website suggestion recorded successfully!');
INSERT INTO `pl_phrases` VALUES (18, 'submit_submission', 'Submission');
INSERT INTO `pl_phrases` VALUES (19, 'submit_emailbody', 'Hi,\r\n\r\nWith regards to your submissions to {SITENAME}, your site, {WEBSITE}, has been {MSG}.\r\n\r\nRegards,\r\n{SITENAME} Team');
INSERT INTO `pl_phrases` VALUES (20, 'submit_rejected', 'rejected');
INSERT INTO `pl_phrases` VALUES (21, 'submit_accepted', 'accepted');
INSERT INTO `pl_phrases` VALUES (22, 'allrightsreserved', 'All rights reserved');
INSERT INTO `pl_phrases` VALUES (23, 'poweredby', 'Powered by');
INSERT INTO `pl_phrases` VALUES (24, 'change', 'Change');
INSERT INTO `pl_phrases` VALUES (25, 'skin', 'Skin');
INSERT INTO `pl_phrases` VALUES (26, 'usedefaultskin', 'Use default skin');
INSERT INTO `pl_phrases` VALUES (27, 'recentlyadded', 'Recently Added');
INSERT INTO `pl_phrases` VALUES (28, 'admin', 'Admin');
INSERT INTO `pl_phrases` VALUES (29, 'newest', 'Newest');
INSERT INTO `pl_phrases` VALUES (30, 'suggesttopic', 'Suggest Topic');
INSERT INTO `pl_phrases` VALUES (31, 'submit_topicexists', 'This topic already exists');
INSERT INTO `pl_phrases` VALUES (32, 'submit_rules', 'Please make sure that the submission is under the correct category.\r\n\r\nAbuse, illegal or inappropriate material will be rejected.');
INSERT INTO `pl_phrases` VALUES (33, 'submit_topic_success', 'Topic suggestion submitted successfully!');
INSERT INTO `pl_phrases` VALUES (34, 'submit_topic_email', 'Hi,\r\n\r\nWith regards to your submission to {TOPICNAME}, the suggested topic, {WEBSITE}, has been {MSG}.\r\n\r\nRegards,\r\n{SITENAME} Team');
INSERT INTO `pl_phrases` VALUES (35, 'total_links', 'Total Links');
INSERT INTO `pl_phrases` VALUES (36, 'total_topics', 'Total Topics');
INSERT INTO `pl_phrases` VALUES (37, 'total_queue', 'In Queue');

-- 
-- Dumping data for table `pl_skinbase`
-- 

INSERT INTO `pl_skinbase` VALUES (1, 'overall_footer', '	</div>\r\n\r\n	<div class="subfoot">\r\n		{DATETIME}\r\n	</div>\r\n	\r\n	<div class="footer">\r\n		{SKIN_SELECTOR}\r\n		&copy; {SITENAME}. {ALLRIGHTSRESERVED}.<br />\r\n		<!-- This copyright notice and link may not be removed under the licence -->\r\n		{POWERED_BY} Particle Links {VERSION} &copy; 2005 <a href="http://www.particlesoft.net/">Particle Soft</a>.\r\n	</div>\r\n</div>\r\n</body>\r\n</html>');
INSERT INTO `pl_skinbase` VALUES (2, 'overall_header', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\r\n<html lang="{LANGUAGE_CODE}">\r\n<head>\r\n<title>{PAGETITLE}</title>\r\n<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />\r\n<meta name="Description" content="{META_DESCRIPTION}" />\r\n<meta name="Keywords" content="{META_KEYWORDS}" />\r\n<style type="text/css">\r\n{CSS_CODE}\r\n</style>\r\n<script language="JavaScript" type="text/javascript" src="{ROOT}shared/functions.js"></script>\r\n</head>\r\n<body>\r\n<div class="container">\r\n	<div class="search">\r\n		<form action="{ROOT}search.php" method="get">\r\n			<input type="text" size="15" id="q" name="q" maxlength="255" />\r\n			<input type="submit" value="{SEARCH}!" />\r\n		</form>\r\n	</div>\r\n	\r\n	<h1>{SITENAME}</h1>\r\n	\r\n	<div class="breadcrumbs">\r\n		<div class="adminlink">{ADMIN_LINK}</div>\r\n		{BREADCRUMBS}\r\n	</div>\r\n	\r\n	<div class="main">\r\n');

-- 
-- Dumping data for table `pl_skinsets`
-- 

INSERT INTO `pl_skinsets` VALUES (1, 1, 'Particle Blue', 'images/', 'A:link, A:visited, A:active{\r\n	color: #0000FF;\r\n	text-decoration: underline;\r\n}\r\nA:hover{\r\n	color: #FF0000;\r\n	text-decoration: underline;\r\n}\r\n.adminlink{\r\n	float: right;\r\n	padding-left: 50px;\r\n}\r\n.adminmenu{\r\n	background: #DDDDDD;\r\n	border: #999999 1px solid;\r\n}\r\nbody{\r\n	background: url("{IMAGES_DIR}background.jpg") repeat-x;\r\n	font-family: Georgia, Arial, Verdana, Sans-Serif;\r\n}\r\n.breadcrumbs, .subfoot{\r\n	padding: 3px;\r\n	background: #DDDDDD;\r\n	border: #999999 1px solid;\r\n	color: #333333;\r\n}\r\n.breadcrumbs A:link, .breadcrumbs A:visited, .breadcrumbs A:active, .breadcrumbs A:hover{\r\n	color: #333333;\r\n	text-decoration: underline;\r\n	font-weight: bold;\r\n}\r\n.footer{\r\n	padding: 5px;\r\n	font-size: small;\r\n	text-align: center;\r\n}\r\nform{\r\n	margin: 3px;\r\n}\r\nh1{\r\n	margin: 3px;\r\n	font-size: 150%;\r\n}\r\n.invisible A:link, .invisible A:visited, .invisible A:active{\r\n	color: #99CC66;\r\n	text-decoration: underline;\r\n}\r\n.invisible A:hover{\r\n	color: #FF0000;\r\n	text-decoration: underline;\r\n}\r\n.login{\r\n	font-size: 150%;\r\n}\r\n.main{\r\n	padding: 5px 0px 5px 0px;\r\n}\r\n.rules{\r\n	background: #CCCCCC;\r\n}\r\n.rules td{\r\n	background: #FFFFFF;\r\n}\r\n.search{\r\n	float: right;\r\n}\r\n.search, .search input{\r\n	font-size: 75%;\r\n}\r\n.search form{\r\n	margin: 0px;\r\n}\r\n.smalltext{\r\n	font-size: 75%;\r\n}\r\n.subfoot{\r\n	text-align: center;\r\n}');