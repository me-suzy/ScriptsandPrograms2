CREATE TABLE `SaferMailAllow` (
  `Id` int(6) unsigned NOT NULL auto_increment,
  `status` varchar(20) default NULL,
  `addr` varchar(16) default NULL,
  PRIMARY KEY  (`Id`)
) ;

CREATE TABLE `SaferMailVisits` (
  `Id` int(6) unsigned NOT NULL auto_increment,
  `lastvisit` TIMESTAMP(14),
  `addr` varchar(16) default NULL,
  PRIMARY KEY  (`Id`)
) ;

INSERT INTO `SaferMailVisits` (`lastvisit`,`addr`) VALUES (NOW(),'1.2.3.4');

CREATE TABLE `SaferMailAddresses` (
  `Id` int(6) unsigned NOT NULL auto_increment,
  `key` varchar(20) default NULL,
  `email` varchar(40) default NULL,
  PRIMARY KEY  (`Id`)
) ;

CREATE TABLE `SaferMailLog` (
  `Id` int(6) unsigned NOT NULL auto_increment,
  `addr` varchar(16) default NULL,
  `emailkey` varchar(20) default NULL,
  `referer` varchar(100) default NULL,
  `visitdate` TIMESTAMP(14),
  PRIMARY KEY  (`Id`)
) ;
