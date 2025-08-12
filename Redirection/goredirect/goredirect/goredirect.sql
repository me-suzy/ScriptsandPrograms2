/*

Go! Redirector SQL script
Generated for use with MySQL 3.23.x or better

*/
create database if not exists `goredirect`;

use `goredirect`;

/*
Table struture for redirs
*/

drop table if exists `redirs`;
CREATE TABLE `redirs` (
  `id` bigint(20) NOT NULL auto_increment,
  `redirect` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `redirect` (`redirect`)
) TYPE=MyISAM;

/*
Table struture for stats
*/

drop table if exists `stats`;
CREATE TABLE `stats` (
  `id` bigint(20) NOT NULL auto_increment,
  `url` varchar(255) default NULL,
  `ip` varchar(18) default NULL,
  `date` datetime default NULL,
  `browser` varchar(255) default NULL,
  `page` varchar(255) default NULL,
  `linkid` bigint(20) default NULL,
  `sitecode` varchar(4) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;
