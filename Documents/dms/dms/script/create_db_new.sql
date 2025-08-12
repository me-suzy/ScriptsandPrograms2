-- ---------------------------------------------------------
-- Table structure for table `ACL`
-- 

CREATE TABLE `ACL` (
  `document_id` int(10) unsigned NOT NULL default '0',
  `user_id` int(10) unsigned NOT NULL default '0',
  `level` enum('R','W','G') NOT NULL default 'R',
  PRIMARY KEY  (`document_id`,`user_id`)
) TYPE=MyISAM;


-- --------------------------------------------------------

-- 
-- Table structure for table `chat`
-- 

CREATE TABLE `chat` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `ref_id` int(10) unsigned NOT NULL default '0',
  `user` int(10) unsigned NOT NULL default '0',
  `subject` varchar(128) NOT NULL default '',
  `content` text NOT NULL,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `ref_id` (`ref_id`),
  KEY `user` (`user`),
  KEY `subject` (`subject`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;


-- --------------------------------------------------------

-- 
-- Table structure for table `documents`
-- 

CREATE TABLE `documents` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `type` varchar(64) NOT NULL default '',
  `size` int(10) unsigned NOT NULL default '0',
  `author` int(10) unsigned NOT NULL default '0',
  `maintainer` int(10) unsigned NOT NULL default '0',
  `revision` int(10) unsigned NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `name` (`name`),
  KEY `size` (`size`),
  KEY `author` (`author`),
  KEY `maintainer` (`maintainer`),
  KEY `revision` (`revision`),
  KEY `created` (`created`),
  KEY `modified` (`modified`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;



-- --------------------------------------------------------

-- 
-- Table structure for table `documents_content`
-- 

CREATE TABLE `documents_content` (
  `id` int(10) unsigned NOT NULL default '0',
  `content` longblob NOT NULL,
  UNIQUE KEY `id_2` (`id`),
  KEY `id` (`id`)
) TYPE=MyISAM;


-- --------------------------------------------------------

-- 
-- Table structure for table `documents_info`
-- 

CREATE TABLE `documents_info` (
  `id` int(10) unsigned NOT NULL default '0',
  `info` tinytext NOT NULL,
  UNIQUE KEY `id_2` (`id`),
  KEY `id` (`id`)
) TYPE=MyISAM;


-- --------------------------------------------------------

-- 
-- Table structure for table `documents_keywords`
-- 

CREATE TABLE `documents_keywords` (
  `id` int(10) unsigned NOT NULL default '0',
  `keyword` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`,`keyword`)
) TYPE=MyISAM;


-- --------------------------------------------------------

-- 
-- Table structure for table `documents_log`
-- 

CREATE TABLE `documents_log` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` int(10) unsigned NOT NULL default '0',
  `document` int(10) unsigned NOT NULL default '0',
  `revision` int(10) unsigned NOT NULL default '0',
  `date` timestamp(14) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `document` (`document`),
  KEY `revision` (`revision`),
  KEY `date` (`date`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;



-- --------------------------------------------------------

-- 
-- Table structure for table `gods`
-- 

CREATE TABLE `gods` (
  `user` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`user`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `gods`
-- 

INSERT INTO `gods` VALUES (1);

-- --------------------------------------------------------

-- 
-- Table structure for table `session`
-- 

CREATE TABLE `session` (
  `id` varchar(32) NOT NULL default '',
  `active` int(10) unsigned NOT NULL default '0',
  `frog` text,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;



-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` varchar(16) NOT NULL default '',
  `pass` varchar(16) NOT NULL default '',
  `name` varchar(64) NOT NULL default '',
  `email` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `user_2` (`user`),
  KEY `user` (`user`)
) TYPE=MyISAM AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `users`
-- 

INSERT INTO `users` VALUES (1, 'Admin', '6257516627f9a39e', 'Admin User', 'root@localhost');
