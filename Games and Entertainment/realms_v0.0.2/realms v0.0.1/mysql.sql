-- phpMyAdmin SQL Dump
-- version 2.6.2-rc1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Sep 11, 2005 at 02:56 AM
-- Server version: 4.1.12
-- PHP Version: 4.3.11
-- 
-- Database: `drak_realms`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `1p`
-- 

CREATE TABLE `1p` (
  `id` bigint(255) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default 'Monster',
  `owner` bigint(255) NOT NULL default '0',
  `level` int(32) NOT NULL default '1',
  `race` varchar(32) NOT NULL default 'monster',
  `hp` bigint(255) NOT NULL default '0',
  `max_hp` bigint(255) NOT NULL default '0',
  `max_hp_increase` double NOT NULL default '1',
  `attack` bigint(255) NOT NULL default '0',
  `attack_increase` double NOT NULL default '1',
  `defend` bigint(255) NOT NULL default '0',
  `defend_increase` double NOT NULL default '1',
  `weapons` varchar(255) NOT NULL default '',
  `skills` varchar(255) character set utf8 collate utf8_bin NOT NULL default '',
  `bp` bigint(255) NOT NULL default '1',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=494 ;

-- 
-- Dumping data for table `1p`
-- 

INSERT INTO `1p` VALUES (1, 'Rabbit', 0, 1, 'monster', 10, 10, 2, 1, 0.34, 1, 0.34, '1,7,1183,1184,19167', 0x312c322c3133, 1);
INSERT INTO `1p` VALUES (3, 'The Mage', 0, 3, 'human', 30, 25, 5, 5, 2, 1, 0.2, '4,6,1700,7,7,6326,1,1', '', 3);
INSERT INTO `1p` VALUES (33, 'Fear', 0, 5, 'monster', 40, 40, 25, 6, 6, 6, 6, '4,4,4,6,1700,1699,1653,1653', '', 12);
INSERT INTO `1p` VALUES (35, 'The Warrior', 0, 3, 'monster', 30, 30, 10, 10, 5, 10, 5, '1,5,5,1021,1156,9377,9378,19167', '', 9);
INSERT INTO `1p` VALUES (105, 'The disgruntled Admin', 0, 13, 'human', 50, 50, 20, 30, 15, 30, 15, '1699,1653,1653,12548,12548,25011', '', 21);
INSERT INTO `1p` VALUES (154, 'Thor', 0, 15, 'human', 400, 400, 100, 80, 10, 50, 10, '19167,1156,18735,6,6,18795,1699,1699', 0x32352c32322c312c3133, 18);
INSERT INTO `1p` VALUES (166, 'Head Ninja', 0, 100, 'human', 300, 300, 50, 200, 50, 200, 50, '9378,9378,1021,1021,1156,19167,19167,19167,20116,20116', '', 50);
INSERT INTO `1p` VALUES (167, 'jon', 0, 3, 'human', 30, 30, 5, 10, 5, 20, 10, '19167,19167,19167,18861,18862,9239,1156,1156,9377,20096,20096', '', 6);
INSERT INTO `1p` VALUES (279, 'The Ocean', 0, 1, 'monster', 200, 200, 50, 15, 10, 10, 5, '4,4,18861,23271,23271', '', 10);
INSERT INTO `1p` VALUES (302, 'The Reptile', 0, 1, 'reptile', 60, 60, 15, 6, 3, 4, 2, '19167,20349,20349,22648,1021,20188,1156', 0x31352c31352c312c3133, 5);

-- --------------------------------------------------------

-- 
-- Table structure for table `1p_records`
-- 

CREATE TABLE `1p_records` (
  `id` bigint(255) NOT NULL auto_increment,
  `owner` bigint(255) NOT NULL default '0',
  `opponent` varchar(255) NOT NULL default '0',
  `outcome` set('win','lose','heal','paid') NOT NULL default '',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1116 ;

-- 
-- Dumping data for table `1p_records`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `characters`
-- 

CREATE TABLE `characters` (
  `id` int(32) NOT NULL auto_increment,
  `quest` int(32) NOT NULL default '0',
  `battle` int(32) NOT NULL default '0',
  `stat` int(32) NOT NULL default '0',
  `owner` int(32) NOT NULL default '0',
  `name` varchar(32) NOT NULL default '',
  `sex` set('male','female') NOT NULL default 'male',
  `realm` varchar(32) NOT NULL default '001',
  `map` int(32) NOT NULL default '1',
  `pos` int(32) NOT NULL default '1',
  `race` varchar(32) NOT NULL default 'human',
  `job` varchar(32) NOT NULL default 'none',
  `job_level` int(32) NOT NULL default '1',
  `level` int(32) NOT NULL default '1',
  `magic_level` int(32) NOT NULL default '1',
  `magic_type` varchar(32) NOT NULL default 'none',
  `max_hp` float(32,2) NOT NULL default '15.00',
  `max_energy` float(32,2) NOT NULL default '0.00',
  `max_mana` float(32,2) NOT NULL default '0.00',
  `hp` float(32,2) NOT NULL default '15.00',
  `energy` float(32,2) NOT NULL default '0.00',
  `mana` float(32,2) NOT NULL default '0.00',
  `attack` float(32,2) NOT NULL default '1.00',
  `defend` float(32,2) NOT NULL default '1.00',
  `speed` float(32,2) NOT NULL default '0.00',
  `luck` int(32) NOT NULL default '0',
  `brains` int(32) NOT NULL default '0',
  `karma` float(32,2) NOT NULL default '50.00',
  `cash` float(32,2) NOT NULL default '0.00',
  `gems` int(32) NOT NULL default '0',
  `gold` int(32) NOT NULL default '0',
  `ore` int(32) NOT NULL default '0',
  `lastseen` int(32) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 COMMENT='Character data' AUTO_INCREMENT=23 ;

-- 
-- Dumping data for table `characters`
-- 

INSERT INTO `characters` VALUES (1, 0, 821, 51, 2, 'Guest', 'male', '001', 1, 1, 'human', 'none', 1, 17, 1, 'none', 65.00, 49.00, 40.00, 65.00, 49.00, 40.00, 32.00, 26.00, 24.00, 11, 11, 50.00, 185012.66, 0, 68, 0, 1126425293);

-- --------------------------------------------------------

-- 
-- Table structure for table `charclass`
-- 

CREATE TABLE `charclass` (
  `id` int(32) NOT NULL auto_increment,
  `character` int(32) NOT NULL default '0',
  `nomad` int(32) NOT NULL default '5',
  `bunnyslayer` int(32) NOT NULL default '0',
  `warrior` int(32) NOT NULL default '0',
  `hunter` int(32) NOT NULL default '0',
  `mage` int(32) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

-- 
-- Dumping data for table `charclass`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `charjob`
-- 

CREATE TABLE `charjob` (
  `id` int(32) NOT NULL auto_increment,
  `character` varchar(32) NOT NULL default '',
  `job` varchar(32) NOT NULL default 'Unemployed',
  `pay` int(32) NOT NULL default '0',
  `quota` int(32) NOT NULL default '0',
  `fulfilled` int(32) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='quota is total, fulfilled is what they have' AUTO_INCREMENT=17 ;

-- 
-- Dumping data for table `charjob`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `chat`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 PACK_KEYS=0 AUTO_INCREMENT=1778 ;

-- 
-- Dumping data for table `chat`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `classes`
-- 

CREATE TABLE `classes` (
  `classname` varchar(32) NOT NULL default '',
  `classdes` text NOT NULL,
  PRIMARY KEY  (`classname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='classname is to match to the class in charclass table';

-- 
-- Dumping data for table `classes`
-- 

INSERT INTO `classes` VALUES ('bunnyslayer', 'You are a slayer of bunnies, a destroyer of rabbits.... a splitter of hares.');
INSERT INTO `classes` VALUES ('hunter', 'You figured out fast that in this world you are the hunter, or you are the hunted, and you decided which one was more fun.');
INSERT INTO `classes` VALUES ('mage', 'since seeing your first bunny shoot fire out of its paw, you decided the path of magic was for you.');
INSERT INTO `classes` VALUES ('nomad', 'You are a homeless wanderer, you don''t know how you got here, and you don''t know what to do, but you notice a lot of other people like you here.');

-- --------------------------------------------------------

-- 
-- Table structure for table `economy`
-- 

CREATE TABLE `economy` (
  `id` bigint(255) NOT NULL auto_increment,
  `timenum` bigint(255) NOT NULL default '0',
  `totcash` bigint(255) NOT NULL default '0',
  `totplayers` bigint(255) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=98 ;

-- 
-- Dumping data for table `economy`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `events`
-- 

CREATE TABLE `events` (
  `id` int(32) NOT NULL auto_increment,
  `realm` varchar(32) NOT NULL default '001',
  `timesperday` int(32) NOT NULL default '100',
  `timesperdaymax` int(32) NOT NULL default '100',
  `effect` text NOT NULL,
  `event` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `events`
-- 

INSERT INTO `events` VALUES (1, 'any', 100, 100, 'mysql_query("update characters set cash=cash+100 where id=$stat[id]"); ', 'print"You found 100 cash just laying around";');
INSERT INTO `events` VALUES (2, 'any', 10, 10, '$chanceee=rand(0,5);\r\nif($chanceee==5){\r\n$whoacash=rand(1000,100000);\r\nmysql_query("update characters set cash=cash+$whoacash where id=$stat[id]");\r\n}else{\r\n$whoacash=rand(10,1000);\r\nmysql_query("update characters set cash=cash+$whoacash where id=$stat[id]");\r\n}', 'if($chanceee==5){\r\nprint"whoa, $whoacash cash... just sitting there.";\r\n}else{\r\nprint"you found $whoacash cash.";\r\n}');
INSERT INTO `events` VALUES (3, 'any', 20, 20, '$aknife=mysql_fetch_array(mysql_query("select * from `items` where `name`=''Knife'' and `owner`=''-1'' limit 1"));\r\nif($aknife[name]){\r\nmysql_query("update items set owner=$user[id] where id=$aknife[id]");\r\n$knifeworked=1;\r\n}', 'if($knifeworked==1){\r\nprint "Who would have just left a knife here?... Oh well, its yours now";\r\n}else{\r\nprint "you see someone pick up a knife.... wow.... exiting isnt it?";\r\n}');
INSERT INTO `events` VALUES (4, '001', 0, 1, 'mysql_query("update characters set battle=battle+42 where id=$stat[id]");', 'print"You find yourself confronted by 42 bunnies, so you kill them all..... good job..... i mean wow";');
INSERT INTO `events` VALUES (5, 'any', 100, 100, 'mysql_query("update characters set gold=gold+1 where id=$stat[id]");', 'print"you see a golden pebble.... wow.... its so pretty";');

-- --------------------------------------------------------

-- 
-- Table structure for table `forumboards`
-- 

CREATE TABLE `forumboards` (
  `id` bigint(255) NOT NULL auto_increment,
  `name` text NOT NULL,
  `descript` text NOT NULL,
  `lastpost` text NOT NULL,
  `admin` tinyint(3) NOT NULL default '0',
  `clan` int(255) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- 
-- Dumping data for table `forumboards`
-- 

INSERT INTO `forumboards` VALUES (2, 'Staff board', 'where the staff come out to play', '264', 1, 0);
INSERT INTO `forumboards` VALUES (3, 'General', 'Talk about anything', '1126390882', 0, 0);
INSERT INTO `forumboards` VALUES (4, 'Feedback', 'Report bugs, request features or any other feedback goes here.', '1123483440', 0, 0);
INSERT INTO `forumboards` VALUES (5, 'Word Games', 'Word games, good place to earn forum points to level up early on, no double posting in here.', '265', 0, 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `forumposts`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=286 ;

-- 
-- Dumping data for table `forumposts`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `forumread`
-- 

CREATE TABLE `forumread` (
  `user` int(32) NOT NULL default '0',
  `post` int(32) NOT NULL default '0',
  `time` int(32) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `forumread`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `forumtopics`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

-- 
-- Dumping data for table `forumtopics`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `gametime`
-- 

CREATE TABLE `gametime` (
  `id` int(32) NOT NULL auto_increment,
  `hour` int(32) NOT NULL default '0',
  `day` int(32) NOT NULL default '0',
  `daycheck` int(32) NOT NULL default '24',
  `month` int(32) NOT NULL default '0',
  `monthcheck` int(32) NOT NULL default '720',
  `year` int(32) NOT NULL default '0',
  `yearcheck` int(32) NOT NULL default '8766',
  `realm` varchar(32) NOT NULL default '001',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `gametime`
-- 

INSERT INTO `gametime` VALUES (1, 3, 0, 24, 0, 720, 0, 8766, '001');

-- --------------------------------------------------------

-- 
-- Table structure for table `gridgame`
-- 

CREATE TABLE `gridgame` (
  `id` bigint(255) NOT NULL auto_increment,
  `owner` bigint(255) NOT NULL default '0',
  `spots` varchar(255) NOT NULL default '',
  `time` bigint(20) default NULL,
  `status` tinyint(5) NOT NULL default '0',
  `treasure` bigint(255) NOT NULL default '0',
  `cash` bigint(255) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='owner is account, not char' AUTO_INCREMENT=945 ;

-- 
-- Dumping data for table `gridgame`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `items`
-- 

CREATE TABLE `items` (
  `id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `type` varchar(255) NOT NULL default '',
  `owner` double NOT NULL default '0',
  `image` varchar(255) NOT NULL default '',
  `price` bigint(255) NOT NULL default '1000',
  `usershop` varchar(10) NOT NULL default 'no',
  `usershop_price` bigint(255) NOT NULL default '0',
  `icons` varchar(255) NOT NULL default '0,0,0,0,0,0',
  `icon_def` varchar(255) NOT NULL default '0,0,0,0,0,0',
  `heal_min` varchar(255) NOT NULL default '0',
  `heal_max` varchar(255) NOT NULL default '0',
  `rarity` bigint(255) NOT NULL default '0',
  `phrase` longtext NOT NULL,
  `phrase2` varchar(255) NOT NULL default '',
  `equip` tinyint(3) NOT NULL default '0',
  `effect` varchar(255) NOT NULL default '',
  `effect_power` bigint(255) NOT NULL default '0',
  `uses` set('multi','once','once_ever') NOT NULL default 'multi',
  `used` tinyint(5) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='All game items' AUTO_INCREMENT=118073 ;

-- 
-- Dumping data for table `items`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `jobs`
-- 

CREATE TABLE `jobs` (
  `job` varchar(32) NOT NULL default '',
  `jobdes` text NOT NULL,
  PRIMARY KEY  (`job`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='classname is to match to the class in charclass table';

-- 
-- Dumping data for table `jobs`
-- 

INSERT INTO `jobs` VALUES ('Hunter', 'It''s your job to supply the realm with enough furs and other animal products to keep it happy, fulfil your quota and you will get paid up to once a day, you have unlimited time to fulfil your quota, but you will only get paid once you do. [br]\r\nthis job has the added bonus of an extra commision on the furs you sell in the guild, so you get paid even more.');
INSERT INTO `jobs` VALUES ('Unemployed', 'Basically you sit there and do whatever you want..... sounds good right?.... well the downside is you don''t get paid');

-- --------------------------------------------------------

-- 
-- Table structure for table `log`
-- 

CREATE TABLE `log` (
  `id` bigint(255) NOT NULL auto_increment,
  `owner` bigint(255) NOT NULL default '0',
  `log` longtext NOT NULL,
  `whenwas` timestamp NULL default CURRENT_TIMESTAMP,
  `read` char(1) NOT NULL default 'F',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=443 ;

-- 
-- Dumping data for table `log`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `mail`
-- 

CREATE TABLE `mail` (
  `id` int(32) NOT NULL auto_increment,
  `sender` varchar(15) NOT NULL default '',
  `senderid` int(32) NOT NULL default '0',
  `owner` int(32) NOT NULL default '0',
  `subject` text NOT NULL,
  `body` text NOT NULL,
  `unread` char(1) NOT NULL default 'F',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `map`
-- 

CREATE TABLE `map` (
  `id` int(32) NOT NULL auto_increment,
  `tile` int(32) NOT NULL default '1',
  `realm` varchar(32) NOT NULL default '001',
  `map` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- 
-- Dumping data for table `map`
-- 

INSERT INTO `map` VALUES (1, 1, '001', 'w*w*w*w*g*g*b*s*s*s*j*w*w*w*w*w*g*b*s*c*s*j*w*w*w*w*w*g*b*s*s*s*j*w*w*w*g*g*g*b*b*h*b*j*w*w*g*g*b*b*b*b*b*b*j*g*g*g*g*g*b*b*c*c*c*j*w*w*w*w*g*g*b*c*s*c*j*w*g*g*g*w*g*g*c*c*c*j*w*g*w*g*g*g*g*b*b*b*j*g*g*g*g*g*g*g*g*b*b');
INSERT INTO `map` VALUES (2, 2, '001', 'w*w*c*w*w*w*w*w*w*w*j*w*w*w*c*w*w*w*c*c*c*j*w*w*w*w*c*w*c*w*c*c*j*w*w*w*w*w*c*w*c*w*c*j*w*w*w*w*c*w*c*w*c*w*j*w*w*w*w*w*c*w*w*w*w*j*w*w*c*w*c*w*c*w*w*w*j*w*w*c*c*w*w*w*w*w*w*j*w*w*c*c*c*w*w*w*w*w*j*w*w*w*w*w*w*w*w*w*w');
INSERT INTO `map` VALUES (3, 3, '001', 't*t*c*t*t*t*t*t*t*t*j*t*t*t*c*t*t*t*c*c*c*j*t*t*t*t*c*t*c*t*c*c*j*t*t*t*t*t*c*t*c*t*c*j*t*t*t*t*c*t*c*t*c*t*j*t*t*t*t*t*c*t*t*t*t*j*t*t*c*t*c*t*c*t*t*t*j*t*t*c*c*t*t*t*t*t*t*j*t*t*c*c*c*t*t*t*t*t*j*t*t*t*t*t*t*t*t*t*t');
INSERT INTO `map` VALUES (4, 4, '001', 'c*c*c*c*c*c*c*c*c*c*j*c*h*h*h*h*h*h*h*h*c*j*c*h*b*b*b*b*b*b*h*c*j*c*h*b*h*h*h*h*b*h*c*j*c*h*b*h*c*c*h*b*h*c*j*c*h*b*h*c*c*h*b*h*c*j*c*h*b*h*h*h*h*b*h*c*j*c*h*b*b*b*b*b*b*h*c*j*c*h*h*h*h*h*h*h*h*c*j*c*c*c*c*c*c*c*c*c*c');
INSERT INTO `map` VALUES (5, 5, '001', 'c*c*c*c*c*c*c*c*c*c|1|75*j*c*s*s*s*s*s*s*s*s*c|1|75*j*c*s*b*b*b*b*b*b*s*c|1|75*j*c*s*b*s*s*s*s*b*s*c|1|75*j*c*s*b*s*c*c*s*b*s*c|1|75*j*c*s*b*s*c*c*s*b*s*c|1|75*j*c*s*b*s*s*s*s*b*s*c|1|75*j*c*s*b*b*b*b*b*b*s*c|1|75*j*c*s*s*s*s*s*s*s*s*c|1|75*j*c|1|75*c|1|75*c|1|75*c|1|75*c|1|75*c|1|75*c|1|75*c|1|75*c|1|75*c|1|75');
INSERT INTO `map` VALUES (6, 6, '001', 'b|1|100*s|1|100*c|1|100*cs|2|100*cc|2|100*cs|2|100*c|1|100*s|1|100*bb|2|100*bb|2|100*j*bb|2|100*s|1|50*c|1|50*cs|2|50*cc|2|50*cs|2|50*c|1|50*s|1|50*bb|2|100*bb|2|100*j*bb|2|100*s|1|25*c|1|25*cs|2|25*cc|2|25*cs|2|25*c|1|25*s|1|25*bb|2|100*bh|2|80*j*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*j*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*j*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bh|2|100*j*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*j*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*j*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bh|2|100*j*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100*bb|2|100');
INSERT INTO `map` VALUES (7, 7, '001', 's*s*s*s*s*s*s*s*s*s*j*s*b*s*b*b*b*b*b*b*s*j*s*b*s*b*s*s*s*s*b*s*j*s*b*s*b*s*b*s*b*b*s*j*s*b*s*b*s*b*s*s*b*s*j*s*b*b*b*s*b*s*b*b*s*j*s*s*s*b*s*b*s*s*b*s*j*s*b*s*b*b*b*s*s*s*s*j*s*b*b*b*s*b*b*b*b*b*j*s*s*s*s*s*s*s*s*s*s');



-- --------------------------------------------------------

-- 
-- Table structure for table `pages`
-- 

CREATE TABLE `pages` (
  `id` int(32) NOT NULL auto_increment,
  `page` varchar(32) NOT NULL default '',
  `description` text NOT NULL,
  `realm` varchar(32) NOT NULL default 'all',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- 
-- Dumping data for table `pages`
-- 

INSERT INTO `pages` VALUES (7, 'gridgame', 'The Grid Game is a simple game for getting some cash. Just click the green boxes', 'all');
INSERT INTO `pages` VALUES (8, 'items', 'These are your items. You can equip weapons, move things to your shop, ect', 'all');
INSERT INTO `pages` VALUES (2, 'forum', 'many people collect here to talk about various things', 'all');
INSERT INTO `pages` VALUES (3, 'overview', 'these are your stats, numbers in brackets are the maximum you can reach', 'all');
INSERT INTO `pages` VALUES (4, 'updates', 'game news will be posted here', 'all');
INSERT INTO `pages` VALUES (5, 'usershop', 'buy and sell your items with other players', 'all');
INSERT INTO `pages` VALUES (6, 'lostpage', 'note to coder : add a description here', 'all');
INSERT INTO `pages` VALUES (9, 'shops', 'These are the public shops. They restock frequently, so check back later for more items', 'all');
INSERT INTO `pages` VALUES (10, 'login', 'Login with your account and start playing! If you don''t have an account yet, you can create one', 'all');
INSERT INTO `pages` VALUES (11, 'pswap', 'Here, you can exchange points for stat points', 'all');
INSERT INTO `pages` VALUES (12, '1p', 'Fight monsters with your character', 'all');
INSERT INTO `pages` VALUES (13, 'itemedit', 'Don''t go into mysql to edit items, use this.<br>Rarity over 99 will not stock in the NPC shop. r100 and under can be obtained from special item events<br>Clicking "Create New Item" will put a statless item at the top of the list', 'all');
INSERT INTO `pages` VALUES (14, 'chat', 'This is a chatroom. You can talk to other people', 'all');
INSERT INTO `pages` VALUES (15, 'bank', 'Keep your cash safe in the bank', 'all');
INSERT INTO `pages` VALUES (16, 'options', 'Change your gameplay and account options', 'all');
INSERT INTO `pages` VALUES (17, 'vote', 'Vote for us and get some nice bonuses :)', 'all');

-- --------------------------------------------------------

-- 
-- Table structure for table `realms`
-- 

CREATE TABLE `realms` (
  `id` bigint(255) NOT NULL auto_increment,
  `world` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `description` longtext NOT NULL,
  `race` varchar(255) NOT NULL default 'any',
  `private` varchar(10) NOT NULL default 'N',
  `war` set('evil','illegal','none','okay') NOT NULL default 'okay',
  `defaultpos` bigint(255) NOT NULL default '0',
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `world` (`world`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `realms`
-- 

INSERT INTO `realms` VALUES (1, '001', 'nameless', 'This is where you woke up, no one knows where this place is, and no one remembers how they got here, some have given up escaping and have started a life here, but others will never give up, and try to escpae often', 'any', 'N', 'okay', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `skills`
-- 

CREATE TABLE `skills` (
  `id` bigint(255) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `owner` bigint(255) NOT NULL default '0',
  `icons` varchar(255) NOT NULL default '0,0,0,0,0,0',
  `icon_def` varchar(255) NOT NULL default '0,0,0,0,0,0',
  `heal_min` varchar(255) NOT NULL default '0',
  `heal_max` bigint(255) NOT NULL default '0',
  `uses` varchar(255) NOT NULL default 'multi',
  `used` bigint(255) NOT NULL default '0',
  `levelreq` bigint(255) NOT NULL default '0',
  `racereq` varchar(255) NOT NULL default 'any',
  `jobreq` varchar(255) NOT NULL default 'any',
  `job_levelreq` bigint(255) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='owner is char' AUTO_INCREMENT=72 ;

-- 
-- Dumping data for table `skills`
-- 

INSERT INTO `skills` VALUES (1, 'Attack', 0, '10%,10%,10%,10%,10%,10%', '0,0,0,0,0,0', '-2', 0, 'multi', 0, 5, 'any', 'any', 0);
INSERT INTO `skills` VALUES (2, 'Punch', 0, '0,2,0,0,0,0', '0,0,0,0,0,0', '0', 0, 'multi', 0, 0, 'any', 'any', 0);
INSERT INTO `skills` VALUES (13, 'Defend', 0, '0,0,0,0,0,0', '2,2,2,2,2,2', '0', 3, 'multi', 0, 0, 'any', 'any', 0);
INSERT INTO `skills` VALUES (15, 'Reptile Spit', 0, '0,0,0,0,2,0', '0,0,0,0,0,0', '-5%', -1, 'multi', 0, 0, 'reptile', 'any', 0);
INSERT INTO `skills` VALUES (22, 'Strong Attack', 0, '50%,50%,50%,50%,50%,50%', '0,0,0,0,0,0', '-5', -1, 'multi', 0, 50, 'any', 'any', 0);
INSERT INTO `skills` VALUES (23, 'Water Scroll', 0, '0,0,0,0,90%,0', '0,0,0,0,0,0', '0', 0, '10', 0, 20, 'any', 'any', 0);
INSERT INTO `skills` VALUES (24, 'Fire Scroll', 0, '0,0,0,90%,0,0', '0,0,0,0,0,0', '0', 0, '10', 0, 30, 'any', 'any', 0);
INSERT INTO `skills` VALUES (25, 'Lightning Scroll', 0, '0,0,0,0,0,90%', '0,0,0,0,0,0', '0', 0, '10', 0, 40, 'any', 'any', 0);
INSERT INTO `skills` VALUES (32, 'Run', 0, '0,0,0,0,0,0', '1-2,1-2,1-2,1-2,1-2,1-2', '0', 0, 'multi', 0, 0, 'any', 'any', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `stimit`
-- 

CREATE TABLE `stimit` (
  `id` int(32) NOT NULL auto_increment,
  `expn` double(32,3) NOT NULL default '2.100',
  `race` varchar(32) NOT NULL default 'human',
  `max_hp` double(32,3) NOT NULL default '1.500',
  `max_energy` double(32,3) NOT NULL default '1.390',
  `max_mana` double(32,3) NOT NULL default '1.320',
  `offense` double NOT NULL default '1.23',
  `defense` double(32,3) NOT NULL default '1.130',
  `agility` double(32,3) NOT NULL default '1.120',
  `luck` double(32,3) NOT NULL default '0.500',
  `smart` double(32,3) NOT NULL default '0.500',
  `bank` double(32,3) NOT NULL default '2.222',
  `bankextra` double(32,3) NOT NULL default '9.300',
  `expnextra` double(32,3) NOT NULL default '11.100',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `stimit`
-- 

INSERT INTO `stimit` VALUES (1, 2.300, 'human', 1.500, 1.390, 1.320, 1.23, 1.160, 1.120, 0.800, 0.800, 2.220, 9.300, 11.100);
INSERT INTO `stimit` VALUES (2, 2.100, 'monster', 1.690, 1.400, 0.900, 1.236, 1.125, 1.145, 0.500, 0.500, 1.800, 9.300, 11.100);
INSERT INTO `stimit` VALUES (3, 2.100, 'stone', 3.200, 1.190, 0.647, 1.114, 1.497, 0.620, 0.500, 0.500, 2.222, 9.300, 11.100);
INSERT INTO `stimit` VALUES (4, 2.100, 'reptile', 1.900, 1.200, 1.500, 1.2, 1.600, 1.100, 0.600, 0.400, 2.222, 9.300, 11.100);

-- --------------------------------------------------------

-- 
-- Table structure for table `time`
-- 

CREATE TABLE `time` (
  `id` int(25) NOT NULL default '0',
  `restock` bigint(255) NOT NULL default '0',
  `revive` bigint(255) NOT NULL default '0',
  `reset` bigint(255) NOT NULL default '0',
  `clock` bigint(255) NOT NULL default '0',
  `contestweek` bigint(255) NOT NULL default '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Dumping data for table `time`
-- 

INSERT INTO `time` VALUES (0, 1126426015, 7200, 1126505599, 1126425310, 1126951200);

-- --------------------------------------------------------

-- 
-- Table structure for table `updates`
-- 

CREATE TABLE `updates` (
  `id` int(32) NOT NULL auto_increment,
  `starter` text NOT NULL,
  `title` text NOT NULL,
  `updates` text NOT NULL,
  `stamp` varchar(10) NOT NULL default '01-01-69',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

-- 
-- Dumping data for table `updates`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='This is userdata, not game data, Credits are used to create ' AUTO_INCREMENT=111 ;

-- 
-- Dumping data for table `users`
-- 

INSERT INTO `users` VALUES (2, 'guest', 'guest', 'guest@tcgames.net', 'realmsie', '1024', '1280', '66.249.66.46', 'Guest', 12, 41322, 0, 12, 58, 0, 1, 1, 1126425293, 'forum', 'realms', 0);
