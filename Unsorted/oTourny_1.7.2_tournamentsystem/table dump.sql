# phpMyAdmin MySQL-Dump

# version 2.2.3

# http://phpwizard.net/phpMyAdmin/

# http://phpmyadmin.sourceforge.net/ (download page)

#

# Host: localhost

# Generation Time: Apr 24, 2004 at 07:06 PM

# Server version: 3.23.47

# PHP Version: 4.1.1

# Database : `tribal_botdb`

# --------------------------------------------------------



#

# Table structure for table `emails`

#



CREATE TABLE `emails` (

  `id` int(10) NOT NULL auto_increment,

  `email` blob NOT NULL,

  `title` longblob NOT NULL,

  `message` longblob NOT NULL,

  `xtra` blob NOT NULL,

  PRIMARY KEY  (`id`)

) TYPE=MyISAM;

# --------------------------------------------------------



#

# Table structure for table `games`

#



CREATE TABLE `games` (

  `id` int(10) NOT NULL auto_increment,

  `name` tinytext NOT NULL,

  `qstat` int(2) unsigned NOT NULL default '0',

  PRIMARY KEY  (`id`)

) TYPE=MyISAM;

# --------------------------------------------------------



#

# Table structure for table `images`

#



CREATE TABLE `images` (

  `id` int(10) NOT NULL auto_increment,

  `name` text NOT NULL,

  `type` text NOT NULL,

  `hits` int(10) unsigned NOT NULL default '0',

  `size` int(20) unsigned NOT NULL default '0',

  PRIMARY KEY  (`id`),

  FULLTEXT KEY `type` (`type`)

) TYPE=MyISAM;

# --------------------------------------------------------



#

# Table structure for table `regions`

#



CREATE TABLE `regions` (

  `id` int(10) NOT NULL auto_increment,

  `name` char(70) NOT NULL default '',

  `longitude` int(3) NOT NULL default '0',

  `latitude` int(3) NOT NULL default '0',

  PRIMARY KEY  (`id`)

) TYPE=MyISAM PACK_KEYS=0;

# --------------------------------------------------------



#

# Table structure for table `sequence`

#



CREATE TABLE `sequence` (

  `table` text NOT NULL,

  `index` text NOT NULL,

  `nextid` int(10) NOT NULL default '0'

) TYPE=MyISAM;

# --------------------------------------------------------



#

# Table structure for table `servers`

#



CREATE TABLE `servers` (

  `serverid` int(10) NOT NULL auto_increment,

  `ip` varchar(15) NOT NULL default '0.0.0.0',

  `name` varchar(50) NOT NULL default '',

  `srvmsg` varchar(50) NOT NULL default '',

  `region` int(10) NOT NULL default '0',

  `apass` varchar(50) NOT NULL default '',

  `sapass` varchar(50) NOT NULL default '',

  `cmsg` blob NOT NULL,

  `pmsg` blob NOT NULL,

  `amsg` blob NOT NULL,

  `jpass` varchar(50) NOT NULL default '',

  `port` int(4) NOT NULL default '0',

  `tournyid` int(10) NOT NULL default '0',

  `admins` blob NOT NULL,

  PRIMARY KEY  (`serverid`)

) TYPE=MyISAM;

# --------------------------------------------------------



#

# Table structure for table `teaminvites`

#



CREATE TABLE `teaminvites` (

  `inviteid` int(10) NOT NULL auto_increment,

  `userid` int(10) unsigned NOT NULL default '0',

  `team` int(10) unsigned NOT NULL default '0',

  `time` int(20) unsigned NOT NULL default '0',

  PRIMARY KEY  (`inviteid`)

) TYPE=MyISAM;

# --------------------------------------------------------



#

# Table structure for table `teams`

#



CREATE TABLE `teams` (

  `teamid` int(10) NOT NULL auto_increment,

  `name` varchar(50) NOT NULL default '',

  `tag` varchar(7) NOT NULL default '',

  `tagside` int(1) unsigned NOT NULL default '0',

  `leader` int(10) unsigned NOT NULL default '0',

  `lastleader` int(10) unsigned NOT NULL default '0',

  `email` varchar(100) NOT NULL default '',

  `players` longblob NOT NULL,

  `games` longblob NOT NULL,

  `website` text NOT NULL,

  `ircserv` tinytext NOT NULL,

  `irc` tinytext NOT NULL,

  `password` varchar(13) NOT NULL default '',

  `servlocation` tinytext NOT NULL,

  `description` longblob NOT NULL,

  `status` int(1) unsigned NOT NULL default '0',

  `ranks` longblob NOT NULL,

  `draft` int(1) unsigned NOT NULL default '0',

  `tournaments` longblob NOT NULL,

  PRIMARY KEY  (`teamid`)

) TYPE=MyISAM;

# --------------------------------------------------------



#

# Table structure for table `tournaments`

#



CREATE TABLE `tournaments` (

  `tournamentid` int(10) NOT NULL auto_increment,

  `name` varchar(80) NOT NULL default '',

  `time` int(20) NOT NULL default '0',

  `type` enum('1','2') NOT NULL default '2',

  `draft` int(1) NOT NULL default '0',

  `draft_data` longblob NOT NULL,

  `draft_users` longblob NOT NULL,

  `draft_capts` longblob NOT NULL,

  `draft_teams` longblob NOT NULL,

  `rules` longblob,

  `serverrequirments` longblob,

  `maps` longblob,

  `details` longblob,

  `maxjoin` int(10) unsigned default NULL,

  `status` int(10) unsigned default NULL,

  `game` int(10) unsigned default NULL,

  `gametype` tinytext,

  `mod` tinytext,

  `servers` longblob,

  `creator` int(10) unsigned default NULL,

  `admins` text,

  `teams` longtext,

  `maxteamspermatch` int(3) unsigned NOT NULL default '2',

  `banner` tinyint(10) unsigned default NULL,

  `sponsers` longblob,

  `schedule` longblob,

  `news` longblob,

  `prizes` longblob,

  `playermin` int(2) unsigned NOT NULL default '0',

  `playermax` int(2) unsigned NOT NULL default '0',

  `modules` text NOT NULL,

  PRIMARY KEY  (`tournamentid`)

) TYPE=MyISAM;

# --------------------------------------------------------



#

# Table structure for table `tournaments_auth`

#



CREATE TABLE `tournaments_auth` (

  `id` int(10) NOT NULL auto_increment,

  `name` tinytext NOT NULL,

  `type` enum('1','2') NOT NULL default '2',

  `draft` int(1) NOT NULL default '0',

  `creator` int(10) unsigned NOT NULL default '0',

  `purpose` blob NOT NULL,

  `time` int(14) unsigned NOT NULL default '0',

  `maxmatch` int(3) NOT NULL default '2',

  PRIMARY KEY  (`id`)

) TYPE=MyISAM;

# --------------------------------------------------------



#

# Table structure for table `tournaments_module`

#



CREATE TABLE `tournaments_module` (

  `id` int(10) unsigned NOT NULL auto_increment,

  `name` varchar(125) NOT NULL default '',

  `type` int(1) unsigned NOT NULL default '0',

  `tournyid` int(10) unsigned NOT NULL default '0',

  `teamspermatch` int(3) unsigned NOT NULL default '2',

  `mapspermatch` int(3) unsigned NOT NULL default '1',

  `pointsbymap` enum('1','0') NOT NULL default '0',

  `rounds` int(3) unsigned NOT NULL default '0',

  `round` int(3) unsigned NOT NULL default '0',

  `generated` enum('0','1') NOT NULL default '0',

  `tpl` tinytext NOT NULL,

  `config` longblob NOT NULL,

  `teams` longblob NOT NULL,

  `teams_qualifing` longblob NOT NULL,

  PRIMARY KEY  (`id`)

) TYPE=MyISAM;

# --------------------------------------------------------



#

# Table structure for table `users`

#



CREATE TABLE `users` (

  `userid` int(10) NOT NULL auto_increment,

  `name` tinytext,

  `password` text,

  `time_offset` int(20) NOT NULL default '0',

  `time_format` blob NOT NULL,

  `realname` tinytext,

  `email` text,

  `showemail` tinyint(1) unsigned default NULL,

  `location` tinytext,

  `srvlocation` tinytext,

  `access` tinyint(1) unsigned default NULL,

  `webpage` tinytext,

  `admin` tinytext,

  `gamesplayed` longblob,

  `affialtion` text,

  `icq` tinytext,

  `showicq` tinyint(1) unsigned default NULL,

  `aim` tinytext,

  `showaim` tinyint(1) unsigned default NULL,

  `msn` text,

  `showmsn` tinyint(1) unsigned default NULL,

  `teams` longblob,

  `tournaments` longblob NOT NULL,

  `tournyadmin` longblob NOT NULL,

  `draft_tournaments` longblob NOT NULL,

  `draft_teams` longblob NOT NULL,

  `primaryteam` int(10) unsigned NOT NULL default '0',

  `sessionkey` varchar(50) default NULL,

  `login_key` varchar(50) NOT NULL default '0',

  `login_time` int(20) NOT NULL default '0',

  `lastlogin` int(20) NOT NULL default '0',

  `ip` blob NOT NULL,

  `authemail` tinytext,

  `authemailkey` tinytext,

  `authemailtime` int(20) NOT NULL default '0',

  `loginlock` int(1) unsigned NOT NULL default '0',

  `tournyfounder` blob NOT NULL,

  PRIMARY KEY  (`userid`)

) TYPE=MyISAM;

# --------------------------------------------------------



#

# Table structure for table `usersauth`

#



CREATE TABLE `usersauth` (

  `userid` int(10) NOT NULL auto_increment,

  `name` tinytext,

  `password` varchar(13) NOT NULL default '',

  `email` text,

  `session` varchar(54) NOT NULL default '0',

  `requestime` int(20) NOT NULL default '0',

  PRIMARY KEY  (`userid`)

) TYPE=MyISAM;