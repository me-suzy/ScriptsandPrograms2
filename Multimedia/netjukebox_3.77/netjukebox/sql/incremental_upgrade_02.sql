# phpMyAdmin SQL Dump
# version 2.5.7-pl1
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Jul 27, 2004 at 10:10 PM
# Server version: 4.0.18
# PHP Version: 4.3.7
# 
# Database : `netjukebox`
# 


# --------------------------------------------------------

#
# Table structure for table `configuration_users`
#

CREATE TABLE configuration_users (
  username varchar(255) NOT NULL default '',
  password varchar(32) NOT NULL default '',
  access_browse enum('N','Y') NOT NULL default 'N',
  access_favorites enum('N','Y') NOT NULL default 'N',
  access_play enum('N','Y') NOT NULL default 'N',
  access_record enum('N','Y') NOT NULL default 'N',
  access_stream enum('N','Y') NOT NULL default 'N',
  access_cover enum('N','Y') NOT NULL default 'N',
  access_config enum('N','Y') NOT NULL default 'N',
  user_id int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (user_id),
  KEY username (username)
) TYPE=MyISAM;


#
# Dumping data for table `configuration_users`
#

INSERT INTO configuration_users VALUES ('admin', '21232f297a57a5a743894a0e4a801fc3', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 1);


#
# Database version
#

INSERT INTO configuration_database VALUES (2);
