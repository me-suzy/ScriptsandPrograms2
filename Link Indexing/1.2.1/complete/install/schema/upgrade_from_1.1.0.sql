-- 
-- Table structure for table `pl_newtopics`
-- 

DROP TABLE IF EXISTS `pl_newtopics`;
CREATE TABLE IF NOT EXISTS `pl_newtopics` (
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
-- Table structure for table `pl_skinbase`
-- 

DROP TABLE IF EXISTS `pl_skinbase`;
CREATE TABLE IF NOT EXISTS `pl_skinbase` (
  `baseid` int(11) NOT NULL auto_increment,
  `shortie` varchar(255) NOT NULL default '',
  `code` text NOT NULL,
  PRIMARY KEY  (`baseid`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `pl_skinhistory`
-- 

DROP TABLE IF EXISTS `pl_skinhistory`;
CREATE TABLE IF NOT EXISTS `pl_skinhistory` (
  `historyid` int(11) NOT NULL auto_increment,
  `postdate` int(11) NOT NULL default '0',
  `skinid` int(11) NOT NULL default '0',
  `shortie` varchar(255) NOT NULL default '',
  `code` text NOT NULL,
  PRIMARY KEY  (`historyid`)
) TYPE=MyISAM AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

-- 
-- Structural changes
-- 

ALTER TABLE `pl_queue` ADD `ip` VARCHAR( 255 ) NOT NULL ;

ALTER TABLE `pl_topics` ADD `description` TEXT NOT NULL ;
ALTER TABLE `pl_topics` ADD `rules` TEXT NOT NULL ;

-- --------------------------------------------------------

-- 
-- Dumping data for table `pl_skinbase`
-- 

INSERT INTO `pl_skinbase` VALUES (1, 'overall_footer', '	</div>\r\n\r\n	<div class="subfoot">\r\n		{DATETIME}\r\n	</div>\r\n	\r\n	<div class="footer">\r\n		{SKIN_SELECTOR}\r\n		&copy; {SITENAME}. {ALLRIGHTSRESERVED}.<br />\r\n		<!-- This copyright notice and link may not be removed under the licence -->\r\n		{POWERED_BY} Particle Links {VERSION} &copy; 2005 <a href="http://www.particlesoft.net/">Particle Soft</a>.\r\n	</div>\r\n</div>\r\n</body>\r\n</html>');
INSERT INTO `pl_skinbase` VALUES (2, 'overall_header', '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">\r\n<html lang="{LANGUAGE_CODE}">\r\n<head>\r\n<title>{PAGETITLE}</title>\r\n<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />\r\n<meta name="Description" content="{META_DESCRIPTION}" />\r\n<meta name="Keywords" content="{META_KEYWORDS}" />\r\n<style type="text/css">\r\n{CSS_CODE}\r\n</style>\r\n<script language="JavaScript" type="text/javascript" src="{ROOT}shared/functions.js"></script>\r\n</head>\r\n<body>\r\n<div class="container">\r\n	<div class="search">\r\n		<form action="{ROOT}search.php" method="get">\r\n			<input type="text" size="15" id="q" name="q" maxlength="255" />\r\n			<input type="submit" value="{SEARCH}!" />\r\n		</form>\r\n	</div>\r\n	\r\n	<h1>{SITENAME}</h1>\r\n	\r\n	<div class="breadcrumbs">\r\n		<div class="adminlink">{ADMIN_LINK}</div>\r\n		{BREADCRUMBS}\r\n	</div>\r\n	\r\n	<div class="main">\r\n');

-- --------------------------------------------------------

-- 
-- Dumping data for table `pl_config`
-- 

INSERT INTO `pl_config` VALUES ('languagecode', 'en-uk', 'This describes the natural language used. The default is en-uk however you may want to change this if you translate into another language (including US English or other variation). If you can''t find the language code then leave it blank.');
INSERT INTO `pl_config` VALUES ('topicsubmissions', 'true', 'Set this to true to allow users to suggest new topics or set to false to disable it. User submissions must be enabled in order for this to be active.');
INSERT INTO `pl_config` VALUES ('showsubmissionrules', 'false', 'Set this to true to show the submission rules on the suggest URL page or set to false to hide it. You can edit the rules on the phrases page. You can also set rules for individual topics by editing the topic.');
INSERT INTO `pl_config` VALUES ('showstats', 'true', 'Set to true to show the stats on the homepage or set to false to hide them. This could save a bit of resources if you have a big directory but then it also reduces bragging power ;).');
INSERT INTO `pl_config` VALUES ('stretchedmenu', 'false', 'If you are short of space, you can make the menu column of the admin page run right accross the top of the page and give the main content the full width below it. It looks terrible as the links are still in vertical list but it does free up the space if you need it.');

-- --------------------------------------------------------

-- 
-- Dumping data for table `pl_phrases`
-- 

INSERT INTO `pl_phrases` VALUES (30, 'suggesttopic', 'Suggest Topic');
INSERT INTO `pl_phrases` VALUES (31, 'submit_topicexists', 'This topic already exists');
INSERT INTO `pl_phrases` VALUES (32, 'submit_rules', 'Please make sure that the submission is under the correct category.\r\n\r\nAbuse, illegal or inappropriate material will be rejected.');
INSERT INTO `pl_phrases` VALUES (33, 'submit_topic_success', 'Topic suggestion submitted successfully!');
INSERT INTO `pl_phrases` VALUES (34, 'submit_topic_email', 'Hi,\r\n\r\nWith regards to your submission to {TOPICNAME}, the suggested topic, {WEBSITE}, has been {MSG}.\r\n\r\nRegards,\r\n{SITENAME} Team');
INSERT INTO `pl_phrases` VALUES (35, 'total_links', 'Total Links');
INSERT INTO `pl_phrases` VALUES (36, 'total_topics', 'Total Topics');
INSERT INTO `pl_phrases` VALUES (37, 'total_queue', 'In Queue');