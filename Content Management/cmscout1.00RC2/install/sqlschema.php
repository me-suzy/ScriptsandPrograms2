<?php
$sql[] = "CREATE TABLE `{$database['prefix']}advancements` (
  `ID` int(11) NOT NULL auto_increment,
  `advancement` varchar(30) default NULL,
  `position` mediumint(9) NOT NULL default '0',
  `scheme` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}album_track` (
  `ID` int(11) NOT NULL auto_increment,
  `album_name` varchar(80) default NULL,
  `patrol` varchar(30) default NULL,
  `owner` varchar(30) NOT NULL default '',
  `numphotos` int(11) NOT NULL default '0',
  `allowed` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}allowedpages` (
  `page` varchar(100) NOT NULL default ''
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}auth` (
  `id` int(11) NOT NULL auto_increment,
  `page` varchar(50) NOT NULL default '0',
  `level` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}authteam` (
  `id` int(4) NOT NULL auto_increment,
  `teamname` varchar(25) NOT NULL default '',
  `ispatrol` smallint(6) NOT NULL default '0',
  `getpoints` tinyint(4) NOT NULL default '0',
  `register` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `teamname` (`teamname`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}authuser` (
  `id` int(11) NOT NULL auto_increment,
  `uid` varchar(32) NOT NULL default '',
  `uname` varchar(25) NOT NULL default '',
  `passwd` varchar(32) NOT NULL default '',
  `status` varchar(10) NOT NULL default '',
  `level` tinyint(4) NOT NULL default '0',
  `team` varchar(50) NOT NULL default '',
  `lastlogin` int(11) default NULL,
  `prevlogin` int(11) default NULL,
  `logincount` int(11) default NULL,
  `theme_id` int(11) NOT NULL default '0',
  `timezone` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uname` (`uname`)
) TYPE=myISAM;";

 

$sql[] = "CREATE TABLE `{$database['prefix']}awardschemes` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}badges` (
  `id` int(11) NOT NULL auto_increment,
  `userid` varchar(50) NOT NULL default '',
  `badge` varchar(50) NOT NULL default '',
  `date` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}calendar_detail` (
  `id` int(11) NOT NULL default '0',
  `detail` longtext NOT NULL
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}calendar_items` (
  `id` int(11) NOT NULL auto_increment,
  `summary` varchar(50) NOT NULL default '',
  `startdate` date NOT NULL default '0000-00-00',
  `enddate` date NOT NULL default '0000-00-00',
  `detail` int(1) NOT NULL default '0',
  `owner` varchar(50) NOT NULL default '',
  `allowed` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}comments` (
  `id` int(11) NOT NULL auto_increment,
  `article_id` int(11) NOT NULL default '0',
  `uname` varchar(100) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  `comment` varchar(200) NOT NULL default '',
  `allowed` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}config` (
  `name` varchar(30) NOT NULL default '',
  `value` longtext,
  PRIMARY KEY  (`name`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}download_cats` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `upauth` longtext NOT NULL,
  `downauth` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}downloads` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `descs` longtext NOT NULL,
  `cat` int(11) NOT NULL default '0',
  `file` varchar(50) NOT NULL default '',
  `numdownloads` int(11) NOT NULL default '0',
  `size` int(11) NOT NULL default '0',
  `owner` varchar(50) NOT NULL default '',
  `allowed` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}forumauths` (
  `forum_id` int(11) NOT NULL default '0',
  `new_topic` longtext,
  `reply_topic` longtext,
  `edit_post` longtext,
  `delete_post` longtext,
  `moderate` longtext,
  `view_forum` longtext,
  `read_topics` longtext,
  PRIMARY KEY  (`forum_id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}forumnew` (
  `id` int(11) NOT NULL auto_increment,
  `uname` varchar(50) NOT NULL default '',
  `topic` int(11) NOT NULL default '0',
  `forum` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}forumposts` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(50) default NULL,
  `posttext` longtext NOT NULL,
  `userposted` varchar(50) NOT NULL default '',
  `dateposted` int(12) default NULL,
  `topic` int(11) NOT NULL default '0',
  `edittime` int(11) NOT NULL default '0',
  `edituser` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}forums` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `desc` varchar(100) default NULL,
  `lasttopic` int(11) default NULL,
  `lastpost` varchar(50) default NULL,
  `lastdate` int(11) default NULL,
  `cat` int(11) NOT NULL default '0',
  `pos` int(11) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";

 

$sql[] = "CREATE TABLE `{$database['prefix']}forumscats` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `pos` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";

 

$sql[] = "CREATE TABLE `{$database['prefix']}forumstopicwatch` (
  `topic_id` int(11) NOT NULL default '0',
  `username` varchar(50) NOT NULL default '',
  `notify` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`topic_id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}forumtopics` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(50) NOT NULL default '',
  `desc` varchar(100) default NULL,
  `numviews` int(11) default '0',
  `userposted` varchar(50) NOT NULL default '',
  `dateposted` int(11) NOT NULL default '0',
  `lastpost` varchar(50) NOT NULL default '',
  `lastdate` int(11) NOT NULL default '0',
  `forum` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}frontpage` (
  `id` int(11) NOT NULL auto_increment,
  `page` varchar(50) NOT NULL default '',
  `function` varchar(50) NOT NULL default '',
  `pos` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}functions` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `code` longtext NOT NULL,
  `type` smallint(6) NOT NULL default '0',
  `filetouse` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}help` (
  `page` varchar(50) NOT NULL default '',
  `title` varchar(30) NOT NULL default '',
  `help` longtext NOT NULL,
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}menu_cats` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `numitems` int(11) NOT NULL default '0',
  `position` mediumint(9) NOT NULL default '0',
  `side` varchar(5) NOT NULL default '',
  `showhead` tinyint(4) NOT NULL default '1',
  `showwhen` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}menu_items` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `cat` int(11) NOT NULL default '0',
  `url` varchar(100) default NULL,
  `item` varchar(50) default NULL,
  `pos` mediumint(9) NOT NULL default '0',
  `type` tinyint(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}newscontent` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) NOT NULL default '',
  `news` longtext NOT NULL,
  `event` int(11) NOT NULL default '0',
  `owner` varchar(50) NOT NULL default '',
  `allowed` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}onlineusers` (
  `uid` varchar(32) NOT NULL default '',
  `uname` varchar(40) NOT NULL default '',
  `logon` int(11) NOT NULL default '0',
  `lastupdate` int(11) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  `isactive` tinyint(1) NOT NULL default '0',
  `pages` int(11) NOT NULL default '0',
  `location` varchar(30) NOT NULL default '',
  `locchange` int(11) NOT NULL default '0',
  PRIMARY KEY  (`uid`)
) TYPE=myISAM;";

 

$sql[] = "CREATE TABLE `{$database['prefix']}pagecontent` (
  `pageid` int(11) NOT NULL default '0',
  `pagenum` int(11) NOT NULL default '0',
  `content` longtext NOT NULL,
  `id` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}pagetracking` (
  `id` int(11) NOT NULL auto_increment,
  `pagename` varchar(20) NOT NULL default '',
  `numpages` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}patrol_articles` (
  `ID` int(11) NOT NULL auto_increment,
  `patrol` text NOT NULL,
  `pic` varchar(40) default NULL,
  `title` varchar(150) NOT NULL default '',
  `detail` longtext NOT NULL,
  `date_happen` date default NULL,
  `date_post` int(11) default NULL,
  `album_id` int(3) default NULL,
  `author` varchar(80) NOT NULL default '',
  `owner` varchar(50) NOT NULL default '',
  `allowed` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) TYPE=myISAM;";


$sql[] = "CREATE TABLE `{$database['prefix']}patrolcontent` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `content` longtext NOT NULL,
  `patrol` varchar(50) NOT NULL default '',
  `public` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";


$sql[] = "CREATE TABLE `{$database['prefix']}patrolmenu` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `url` varchar(100) default NULL,
  `item` varchar(50) default NULL,
  `patrol` varchar(50) NOT NULL default '',
  `pos` int(11) NOT NULL default '0',
  `side` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}patrolpoints` (
  `ID` int(11) NOT NULL auto_increment,
  `Patrolname` varchar(20) NOT NULL default '',
  `Points` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}photos` (
  `ID` int(11) NOT NULL auto_increment,
  `filename` varchar(80) default NULL,
  `caption` longtext,
  `album_id` int(10) default NULL,
  `date` int(11) NOT NULL default '0',
  `allowed` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) TYPE=myISAM;";


$sql[] = "CREATE TABLE `{$database['prefix']}pms` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(100) NOT NULL default '',
  `text` longtext NOT NULL,
  `date` int(11) NOT NULL default '0',
  `type` tinyint(4) NOT NULL default '0',
  `readpm` tinyint(4) NOT NULL default '0',
  `newpm` tinyint(4) NOT NULL default '0',
  `fromuser` varchar(100) NOT NULL default '',
  `touser` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}records` (
  `id` smallint(6) NOT NULL auto_increment,
  `firstname` varchar(50) NOT NULL default '',
  `lastname` varchar(50) NOT NULL default '',
  `dob` int(11) default NULL,
  `tel` varchar(20) default NULL,
  `cell` varchar(20) default NULL,
  `address` longtext,
  `email` varchar(100) NOT NULL default '',
  `uname` varchar(50) NOT NULL default '',
  `avyfile` varchar(50) default NULL,
  `sig` varchar(255) default NULL,
  `newtopic` tinyint(4) NOT NULL default '0',
  `allowemail` tinyint(4) NOT NULL default '1',
  `newpm` tinyint(4) NOT NULL default '1',
  `scheme` mediumint(9) NOT NULL default '0',
  `troopuser` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `uname` (`uname`)
) TYPE=myISAM;";

 

$sql[] = "CREATE TABLE `{$database['prefix']}registerinfo` (
  `uname` varchar(30) NOT NULL default '',
  `patrol` varchar(30) NOT NULL default '',
  `pos` varchar(30) NOT NULL default '',
  `interest` longtext,
  `awards` longtext
) TYPE=myISAM;";


$sql[] = "CREATE TABLE `{$database['prefix']}requirements` (
  `ID` int(11) NOT NULL auto_increment,
  `item` varchar(50) default NULL,
  `description` longtext NOT NULL,
  `advancement` int(11) default NULL,
  `position` mediumint(9) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `award_id` (`advancement`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}scoutrecord` (
  `userid` mediumint(9) NOT NULL default '0',
  `requirement` int(11) NOT NULL default '0',
  `comment` mediumtext
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}static_content` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL default '',
  `content` longtext NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}subcontent` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `content` longtext NOT NULL,
  `site` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}submenu` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `url` varchar(100) default NULL,
  `item` varchar(100) default NULL,
  `site` varchar(50) NOT NULL default '',
  `pos` int(11) NOT NULL default '0',
  `side` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}subsites` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}temp` (
  `name` varchar(50) NOT NULL default '',
  `value` longtext NOT NULL,
  PRIMARY KEY  (`name`)
) TYPE=myISAM;";



$sql[] = "CREATE TABLE `{$database['prefix']}themes` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `dir` varchar(50) NOT NULL default '',
  `configfile` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";


$sql[] = "CREATE TABLE `{$database['prefix']}timezones` (
  `id` mediumint(9) NOT NULL auto_increment,
  `offset` decimal(3,1) NOT NULL default '0.0',
  PRIMARY KEY  (`id`)
) TYPE=myISAM;";
?>