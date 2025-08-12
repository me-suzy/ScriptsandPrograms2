CREATE TABLE `__PREFIX__fungl_auth` (
  `username` varchar(50) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  PRIMARY KEY  (`username`),
  KEY `password` (`password`)
) TYPE=InnoDB; 
INSERT INTO `__PREFIX__fungl_auth` VALUES ('admin', '21232f297a57a5a743894a0e4a801fc3'); 
CREATE TABLE `__PREFIX__fungl_polls` (
  `charttype` varchar(20) NOT NULL default 'Pie',
  `votetext` text NOT NULL,
  `title` text NOT NULL,
  `id` int(11) NOT NULL auto_increment,
  `projectid` int(11) NOT NULL default '0',
  `starttime` int(11) default '0',
  `endtime` int(11) default '0',
  `weekday` int(11) default '0',
  PRIMARY KEY  (`id`),
  KEY `projectid` (`projectid`)
) TYPE=InnoDB AUTO_INCREMENT=1 ; 
CREATE TABLE `__PREFIX__fungl_projects` (
  `title` text NOT NULL,
  `site` text,
  `id` int(11) NOT NULL auto_increment,
  `userid` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `userid` (`userid`)
) TYPE=InnoDB COMMENT='Project info' AUTO_INCREMENT=1 ; 
CREATE TABLE `__PREFIX__fungl_questions` (
  `question` text NOT NULL,
  `votes` int(11) NOT NULL default '0',
  `id` int(11) NOT NULL auto_increment,
  `pollid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pollid` (`pollid`)
) TYPE=InnoDB AUTO_INCREMENT=1 ; 
CREATE TABLE `__PREFIX__fungl_userpreferences` (
  `user_id` varchar(255) NOT NULL default '',
  `pref_id` varchar(32) NOT NULL default '',
  `pref_value` longtext NOT NULL,
  PRIMARY KEY  (`user_id`,`pref_id`)
) TYPE=MyISAM; 
INSERT INTO `__PREFIX__fungl_userpreferences` VALUES ('__default__', 'lvl', 'i:1;'); 
INSERT INTO `__PREFIX__fungl_userpreferences` VALUES ('admin', 'lvl', 's:4:"2000";'); 
INSERT INTO `__PREFIX__fungl_userpreferences` VALUES ('admin', 'email', 's:14:"admin@foo.com";'); 
INSERT INTO `__PREFIX__fungl_userpreferences` VALUES ('admin', 'projectamount', 's:3:"100";'); 
INSERT INTO `__PREFIX__fungl_userpreferences` VALUES ('admin', 'pollamount', 's:3:"100";'); 
ALTER TABLE `__PREFIX__fungl_polls` ADD CONSTRAINT `0_40` FOREIGN KEY (`projectid`) REFERENCES `__PREFIX__fungl_projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; 
ALTER TABLE `__PREFIX__fungl_questions` ADD CONSTRAINT `0_43` FOREIGN KEY (`pollid`) REFERENCES `__PREFIX__fungl_polls` (`id`) ON DELETE CASCADE ON UPDATE CASCADE; 