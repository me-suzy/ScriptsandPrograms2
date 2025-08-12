INSERT INTO `pl_config` VALUES ('showadminlink', 'true', 'Set this to true to show the admin link in the breadcrumbs section at the top of the page (or wherever it has been placed by the skin) or set to false to hide the link.');
INSERT INTO `pl_config` VALUES ('topicdropdown', 'true', 'Set this to true to use the dropdown box listing categories when editing links and sub-topics. However if you have lots of topics (like thousands or if your paranoid then hundreds) then set this to false to use a textbox asking for the ID number.');
INSERT INTO `pl_config` VALUES ('notifyuser', 'true', 'If set to true it will email users using PHP''s mail function as to whether their submission has been accepted or rejected.');
INSERT INTO `pl_config` VALUES ('usersubmissions', 'true', 'Set this to true to allow users to suggest URLs. Or set it to false to disable this.');

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
  PRIMARY KEY  (`queueid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;