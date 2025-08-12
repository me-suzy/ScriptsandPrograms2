CREATE TABLE `currentdj` (
  `dj` int(11) NOT NULL auto_increment,
  `active` int(11) NOT NULL default '0',
  `name` text NOT NULL,
  `password` text NOT NULL,
  `address` text NOT NULL,
  `aim` text NOT NULL,
  `msn` text NOT NULL,
  `yim` text NOT NULL,
  `icq` text NOT NULL,
  `alias1` text NOT NULL,
  `alias2` text NOT NULL,
  `alias3` text NOT NULL,
  PRIMARY KEY  (`dj`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
