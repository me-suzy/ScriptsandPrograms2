# MySQL-Front Dump 2.2
#
# Host: CyKuH.com    Database: adrevenue
#--------------------------------------------------------
# Server version 3.23.43 


#
# Table structure for table 'account'
#

DROP TABLE IF EXISTS account;
CREATE TABLE `account` (
  `id` int(11) NOT NULL auto_increment,
  `date` int(10) unsigned NOT NULL default '0',
  `adid` varchar(13) default NULL,
  `mapid` int(11) default '0',
  `clientid` int(11) default '0',
  `amount` float default '0',
  `ip` varchar(15) default NULL,
  PRIMARY KEY  (`id`),
  KEY `date` (`date`,`adid`,`clientid`,`ip`),
  KEY `adid` (`adid`),
  KEY `mapid` (`mapid`)
) TYPE=MyISAM;



#
# Dumping data for table 'account'
#


#
# Table structure for table 'admap'
#

DROP TABLE IF EXISTS admap;
CREATE TABLE `admap` (
  `id` int(11) NOT NULL auto_increment,
  `client` int(11) NOT NULL default '0',
  `ad` varchar(13) NOT NULL default '',
  `keyword` int(11) NOT NULL default '0',
  `bid` float default '0',
  `clicks` int(11) NOT NULL default '0',
  `views` int(11) NOT NULL default '0',
  `status` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `ad` (`ad`,`keyword`),
  KEY `client` (`client`),
  KEY `status` (`status`)
) TYPE=MyISAM;



#
# Dumping data for table 'admap'
#


#
# Table structure for table 'ads'
#

DROP TABLE IF EXISTS ads;
CREATE TABLE `ads` (
  `id` varchar(13) NOT NULL default '',
  `client` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `url` varchar(255) NOT NULL default '',
  `urlshow` text NOT NULL,
  `title` varchar(64) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `status` tinyint(4) NOT NULL default '1',
  `maxcpc` float default '0',
  PRIMARY KEY  (`id`),
  KEY `clientid` (`client`,`date`,`status`)
) TYPE=MyISAM;



#
# Dumping data for table 'ads'
#


#
# Table structure for table 'clients'
#

DROP TABLE IF EXISTS clients;
CREATE TABLE `clients` (
  `id` int(11) NOT NULL auto_increment,
  `date` int(11) NOT NULL default '0',
  `email` varchar(128) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `name` varchar(128) default NULL,
  `org` varchar(128) default NULL,
  `url` varchar(255) default NULL,
  `address` varchar(255) default NULL,
  `city` varchar(128) default NULL,
  `state` varchar(64) default NULL,
  `zip` varchar(64) default NULL,
  `phone` varchar(20) default NULL,
  `status` tinyint(4) NOT NULL default '1',
  `cpc` float default '0.1',
  `sid` varchar(32) default NULL,
  `balance` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `date` (`date`,`email`),
  KEY `status` (`status`),
  KEY `sid` (`sid`)
) TYPE=MyISAM;



#
# Dumping data for table 'clients'
#
LOCK TABLES clients WRITE;
INSERT INTO clients VALUES("1","0","admin","WTN","Administrator","mydomain.com","","","","","","","1","0.1","1234567890abcdefghijklmnopqrstuv","0");
UNLOCK TABLES;


#
# Table structure for table 'keywords'
#

DROP TABLE IF EXISTS keywords;
CREATE TABLE `keywords` (
  `id` int(11) NOT NULL auto_increment,
  `keyword` varchar(64) NOT NULL default '',
  `clicks` int(11) NOT NULL default '0',
  `cpc` float NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `keyword` (`keyword`)
) TYPE=MyISAM;



#
# Dumping data for table 'keywords'
#
