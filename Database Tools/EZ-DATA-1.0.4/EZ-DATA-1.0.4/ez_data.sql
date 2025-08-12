CREATE TABLE `ez_data` (
  `id` int(30) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `street` varchar(30) NOT NULL default '',
  `city` varchar(30) NOT NULL default '',
  `state` varchar(2) NOT NULL default '',
  `zip` varchar(15) NOT NULL default '',
  `email` varchar (30) NOT NULL default '',
  PRIMARY KEY (`id`));


