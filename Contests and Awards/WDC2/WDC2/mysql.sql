CREATE TABLE `chat` (
  `id` int(32) NOT NULL auto_increment,
  `user` varchar(100) NOT NULL default '',
  `chat` text NOT NULL,
  `timem` int(2) NOT NULL default '0',
  `stamp` varchar(32) NOT NULL default '0:0',
  `show` varchar(32) NOT NULL default 'yes',
  UNIQUE KEY `id` (`id`),
  FULLTEXT KEY `chat` (`chat`),
  FULLTEXT KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=1715 ;



CREATE TABLE `contest_comments` (
  `id` int(32) NOT NULL auto_increment,
  `user` int(32) NOT NULL default '0',
  `entry` int(32) NOT NULL default '0',
  `stamp` varchar(32) NOT NULL default '',
  `title` varchar(32) NOT NULL default '',
  `comment` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE `contest_contest` (
  `id` int(32) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '',
  `des` text NOT NULL,
  `active` int(32) NOT NULL default '0',
  `vote` int(32) NOT NULL default '0',
  `end` int(32) NOT NULL default '0',
  `entries` int(32) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;


CREATE TABLE `contest_entries` (
  `id` int(32) NOT NULL auto_increment,
  `user` int(32) NOT NULL default '0',
  `contest` int(32) NOT NULL default '0',
  `filename` varchar(32) NOT NULL default '',
  `thumbnail` varchar(32) NOT NULL default '',
  `pop` int(32) NOT NULL default '0',
  `art` int(32) NOT NULL default '0',
  `con` int(32) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE `contest_votes` (
  `id` int(32) NOT NULL auto_increment,
  `contest` int(32) NOT NULL default '0',
  `entry` int(32) NOT NULL default '0',
  `user` int(32) NOT NULL default '0',
  `ip` varchar(32) NOT NULL default '',
  `type` set('art','concept','pop') NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE `forumboards` (
  `id` bigint(255) NOT NULL auto_increment,
  `name` text NOT NULL,
  `descript` text NOT NULL,
  `lastpost` text NOT NULL,
  `admin` tinyint(3) NOT NULL default '0',
  `clan` int(255) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

INSERT INTO `forumboards` VALUES (1, 'Staff board', 'where the staff come out to play', '264', 1, 0);
INSERT INTO `forumboards` VALUES (2, 'General', 'Talk about anything', '265', 0, 0);
INSERT INTO `forumboards` VALUES (3, 'Feedback', 'Report bugs, request features or any other feedback goes here.', '1119331731', 0, 0);



CREATE TABLE `forumposts` (
  `id` bigint(255) NOT NULL auto_increment,
  `poster` varchar(255) NOT NULL default '',
  `body` longtext NOT NULL,
  `topic` varchar(255) NOT NULL default '',
  `board` varchar(255) NOT NULL default '',
  `type` varchar(255) NOT NULL default '',
  `time` bigint(255) NOT NULL default '0',
  `timenum` bigint(255) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=273 ;


CREATE TABLE `forumread` (
  `user` int(32) NOT NULL default '0',
  `post` int(32) NOT NULL default '0',
  `time` int(32) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



CREATE TABLE `forumtopics` (
  `id` bigint(255) NOT NULL auto_increment,
  `name` varchar(70) NOT NULL default '',
  `type` text NOT NULL,
  `author` text NOT NULL,
  `board` int(32) NOT NULL default '0',
  `lastpost` text NOT NULL,
  `lastpostid` bigint(255) NOT NULL default '0',
  `lastposttime` int(32) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;


CREATE TABLE `log` (
  `id` bigint(255) NOT NULL auto_increment,
  `owner` bigint(255) NOT NULL default '0',
  `log` longtext NOT NULL,
  `whenwas` timestamp NULL default CURRENT_TIMESTAMP,
  `read` char(1) NOT NULL default 'F',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=439 ;


CREATE TABLE `mail` (
  `id` int(32) NOT NULL auto_increment,
  `sender` varchar(15) NOT NULL default '',
  `senderid` int(32) NOT NULL default '0',
  `owner` int(32) NOT NULL default '0',
  `subject` text NOT NULL,
  `body` text NOT NULL,
  `unread` char(1) NOT NULL default 'F',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;


CREATE TABLE `time` (
  `id` int(25) NOT NULL default '0',
  `contestweek` bigint(255) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


INSERT INTO `time` VALUES (0, 1124695230);


CREATE TABLE `users` (
  `id` int(32) NOT NULL auto_increment,
  `username` varchar(32) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `email` varchar(64) NOT NULL default '',
  `template` varchar(32) NOT NULL default 'realmsie',
  `height` varchar(32) NOT NULL default '',
  `width` varchar(32) NOT NULL default '',
  `ip` varchar(32) NOT NULL default '',
  `position` set('Guest','Admin','Moderator','Staff','User') NOT NULL default 'User',
  `credits` int(32) NOT NULL default '10',
  `bank` bigint(255) NOT NULL default '1000',
  `glomps` int(32) NOT NULL default '0',
  `forump` int(32) NOT NULL default '0',
  `forumposts` int(32) NOT NULL default '0',
  `vp` int(32) NOT NULL default '0',
  `characters` int(32) NOT NULL default '0',
  `activechar` int(32) NOT NULL default '0',
  `lastseen` int(32) NOT NULL default '0',
  `page` varchar(32) NOT NULL default '',
  `site` varchar(32) NOT NULL default '',
  `gmt` int(32) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='This is userdata, not game data, Credits are used to create ' AUTO_INCREMENT=94 ;
