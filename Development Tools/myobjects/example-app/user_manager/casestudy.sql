DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `userid` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(80) default NULL,
  `username` varchar(32) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `active` enum('Y','N') NOT NULL default 'Y',
  `admin` enum('Y','N') NOT NULL default 'Y',
  `creationdate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`userid`),
  UNIQUE KEY `userid` (`userid`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--
INSERT INTO `users` VALUES (1,'Erdinc Yilmazel','erdinc','81dc9bdb52d04dc20036dbd8313ed055','erdinc@yilmazel.com','Y','Y','2004-09-07 03:09:49'),(2,'Admin','admin','21232f297a57a5a743894a0e4a801fc3','someone@somesite.com','Y','Y','2004-09-19 01:09:21'),(3,'Demo','demo','fe01ce2a7fbac8fafaed7c982a04e229','demo@demo.com','Y','N','2004-09-07 17:09:05');

