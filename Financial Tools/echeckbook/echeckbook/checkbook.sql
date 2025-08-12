CREATE TABLE `checkbook` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `Type` enum('Credit','Deficit') NOT NULL default 'Credit',
  `Date` date NOT NULL default '0000-00-00',
  `For` varchar(50) NOT NULL default '',
  `Number` text NOT NULL,
  PRIMARY KEY  (`id`)
);