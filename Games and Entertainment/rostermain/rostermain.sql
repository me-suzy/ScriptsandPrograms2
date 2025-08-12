
CREATE TABLE `characters` (
  `username` varchar(16) NOT NULL default '',
  `charactername` varchar(50) NOT NULL default '',
  `level` int(3) unsigned NOT NULL default '1',
  `charclass` varchar(30) NOT NULL default 'not set',
  `race` varchar(30) NOT NULL default 'not set',
  `main` int(1) unsigned NOT NULL default '0'
) TYPE=MyISAM;

INSERT INTO `characters` VALUES ('test', 'testmain', 1, 'test', 'test', 1);
INSERT INTO `characters` VALUES ('test', 'test', 1, 'not set', 'not set', 0);


CREATE TABLE `users` (
  `username` varchar(16) NOT NULL default '',
  `passwd` varchar(16) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `approved` int(1) unsigned NOT NULL default '0',
  `rank` int(1) unsigned NOT NULL default '3',
  `admin` int(1) unsigned NOT NULL default '0',
  `rec` varchar(100) NOT NULL default '',
  `trialstart` date NOT NULL default '0000-00-00',
  `trialend` date NOT NULL default '0000-00-00'
) TYPE=MyISAM;


INSERT INTO `users` VALUES ('test', 'test', 'test@test.com', 1, 0, 1, '', '0000-00-00', '0000-00-00');

