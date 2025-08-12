# phpMyAdmin SQL Dump
# version 2.5.3
# http://www.phpmyadmin.net
#
# Ö÷»ú: localhost
# Éú³ÉÈÕÆÚ: 2004 Äê 12 ÔÂ 09 ÈÕ 00:34
# ·þÎñÆ÷°æ±¾: 4.0.14
# PHP °æ±¾: 4.3.3
# 
# Êý¾Ý¿â : `okphp`
# 

# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}announcements`
#

# DROP TABLE IF EXISTS `{prefix}announcements`;
CREATE TABLE `{prefix}announcements` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(80) NOT NULL default '',
  `content` text NOT NULL,
  `timeline` int(11) NOT NULL default '0',
  `all_forums` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=7 ;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}announcements`
#

INSERT INTO `{prefix}announcements` VALUES (6, 'Thanks for using Okphp BBS !', 'Okphp BBS can integrate with Okphp CMS\r\nplease visit our site:\r\nChinese: [url]http://cn.okphp.com[/url]\r\nEnglish: [url]http://en.okphp.com[/url]\r\n\r\n[color=olive](You can delete/edit this message in adminCP)[/color]', 1101352513, 1);

# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}attach`
#

# DROP TABLE IF EXISTS `{prefix}attach`;
CREATE TABLE `{prefix}attach` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `userid` int(10) unsigned NOT NULL default '0',
  `dateline` int(10) unsigned NOT NULL default '0',
  `filename` varchar(100) NOT NULL default '',
  `filedata` mediumtext NOT NULL,
  `counter` int(10) unsigned NOT NULL default '0',
  `filesize` int(10) unsigned NOT NULL default '0',
  `tmp_id` int(11) NOT NULL default '0',
  `ext` varchar(5) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `filesize` (`filesize`),
  KEY `userid` (`userid`),
  KEY `posthash` (`userid`),
  KEY `postid` (`tmp_id`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}attach`
#


# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}basic`
#

# DROP TABLE IF EXISTS `{prefix}basic`;
CREATE TABLE `{prefix}basic` (
  `id` varchar(12) NOT NULL default '',
  `bb_name` varchar(255) NOT NULL default '',
  `bb_url` varchar(255) NOT NULL default '',
  `web_name` varchar(255) NOT NULL default '',
  `web_url` varchar(255) NOT NULL default '',
  `admin_email` varchar(255) NOT NULL default '',
  `bb_header` text NOT NULL,
  `bb_footer` text NOT NULL,
  `settings` text NOT NULL,
  `designated_ip` text NOT NULL,
  `attitude` int(11) NOT NULL default '0',
  `last_update` int(11) NOT NULL default '0',
  `category` text NOT NULL,
  `maxol_time` int(11) NOT NULL default '0',
  `maxol_num` int(11) NOT NULL default '0'
) TYPE=MyISAM;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}basic`
#

INSERT INTO `{prefix}basic` VALUES ('1', 'Okphp BBS', 'forum.php', 'Okphp Group', 'http://yourdomain', 'yourname@yourdomain', '', '<font size="1">Copyright &copy; 2003-2005 by OKPHP group, All rights reserved. </font>\r\n<br>', '20&50000&10&10&1&&120&20&0&1&0&1&1&100&utf-8&bbs/lang/en&bbs/themes/default&bbs/themes/default/images/blue&bbs/themes/default/css/full_light&Y-m-d H:i&0&8&70&20&3&4&10&100&30&0&Sorry, site is updating..&1&20&100&3&5&1&1&&', '', 0, 1102523209, '<select name="forum_id" onchange="this.form.submit();">\r\n				<option value="0">Select..</option><option value="2">Category - Forum</option></select><input type="submit" value="Go">', 1100594226, 1);

# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}emote`
#

# DROP TABLE IF EXISTS `{prefix}emote`;
CREATE TABLE `{prefix}emote` (
  `id` varchar(10) NOT NULL default '',
  `img` varchar(10) NOT NULL default ''
) TYPE=MyISAM;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}emote`
#

INSERT INTO `{prefix}emote` VALUES ('[e:1]', '1.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:2]', '2.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:3]', '3.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:4]', '4.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:5]', '5.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:6]', '6.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:7]', '7.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:8]', '8.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:9]', '9.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:10]', '10.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:11]', '11.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:12]', '12.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:13]', '13.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:14]', '14.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:15]', '15.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:16]', '16.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:17]', '17.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:18]', '18.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:19]', '19.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:20]', '20.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:21]', '21.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:22]', '22.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:23]', '23.gif');
INSERT INTO `{prefix}emote` VALUES ('[e:24]', '24.gif');

# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}fix`
#

# DROP TABLE IF EXISTS `{prefix}fix`;
CREATE TABLE `{prefix}fix` (
  `last_fix` int(11) NOT NULL default '0'
) TYPE=MyISAM;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}fix`
#

INSERT INTO `{prefix}fix` VALUES (1100757259);

# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}forums`
#

# DROP TABLE IF EXISTS `{prefix}forums`;
CREATE TABLE `{prefix}forums` (
  `id` int(11) NOT NULL auto_increment,
  `order_num` int(11) NOT NULL default '0',
  `belong_id` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `type_pic` varchar(255) NOT NULL default '',
  `intr` tinytext NOT NULL,
  `protect_view` varchar(255) NOT NULL default '',
  `protect_post` varchar(255) NOT NULL default '',
  `protect_reply` varchar(255) NOT NULL default '',
  `theme_id` int(11) NOT NULL default '0',
  `type_class` int(11) NOT NULL default '0',
  `topic_num` int(11) NOT NULL default '0',
  `faq_num` int(11) NOT NULL default '0',
  `cream_num` int(11) NOT NULL default '0',
  `recovery_num` int(11) NOT NULL default '0',
  `post_num` int(11) NOT NULL default '0',
  `lastpostby` varchar(32) NOT NULL default '',
  `lastpid` int(11) NOT NULL default '0',
  `lastpdate` int(11) NOT NULL default '0',
  `moderator` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `belong_id` (`belong_id`),
  KEY `order_num` (`order_num`),
  KEY `type_class` (`type_class`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}forums`
#

INSERT INTO `{prefix}forums` VALUES (1, 1, 0, 'Category', '', '', '', '', '', 0, 1, 0, 0, 0, 0, 0, '', 0, 0, '');
INSERT INTO `{prefix}forums` VALUES (2, 2, 1, 'Forum', '', 'A test forum', '13&3&2&1', '13&3&2', '13&3&2', 1, 2, 0, 0, 0, 0, 0, '', 0, 0, '');

# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}group`
#

# DROP TABLE IF EXISTS `{prefix}group`;
CREATE TABLE `{prefix}group` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `intr` text NOT NULL,
  `is_reg` varchar(10) NOT NULL default 'no',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=14 ;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}group`
#

INSERT INTO `{prefix}group` VALUES (1, 'Guest/Not login', 'don\'t delete this!', 'no');
INSERT INTO `{prefix}group` VALUES (2, 'Normal Members', 'don\'t delete this!', 'no');
INSERT INTO `{prefix}group` VALUES (13, 'test group', 'adf', 'yes');
INSERT INTO `{prefix}group` VALUES (3, 'Super Adminstor', 'don\'t delete this!', 'no');

# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}log`
#

# DROP TABLE IF EXISTS `{prefix}log`;
CREATE TABLE `{prefix}log` (
  `id` int(11) NOT NULL auto_increment,
  `time_added` int(11) NOT NULL default '0',
  `user` varchar(255) NOT NULL default '',
  `ip` varchar(40) NOT NULL default '',
  `action` varchar(255) NOT NULL default '',
  `result` varchar(255) NOT NULL default '',
  `reason` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=7 ;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}log`
#

INSERT INTO `{prefix}log` VALUES (6, 1102523098, 'admin', '127.0.0.1', 'System Settings', '1', '');

# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}online`
#

# DROP TABLE IF EXISTS `{prefix}online`;
CREATE TABLE `{prefix}online` (
  `ip` varchar(30) NOT NULL default '',
  `username` varchar(50) NOT NULL default '',
  `last_view` int(11) NOT NULL default '0',
  `last_input` int(11) NOT NULL default '0',
  `view_crigger` tinyint(4) NOT NULL default '0',
  `input_crigger` tinyint(4) NOT NULL default '0',
  `action` varchar(20) NOT NULL default '',
  `type_id` int(11) NOT NULL default '0',
  `has_pm` tinyint(1) NOT NULL default '0'
) TYPE=MyISAM;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}online`
#


# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}pm`
#

# DROP TABLE IF EXISTS `{prefix}pm`;
CREATE TABLE `{prefix}pm` (
  `id` int(11) NOT NULL auto_increment,
  `id_from` int(11) NOT NULL default '0',
  `id_to` int(11) NOT NULL default '0',
  `message` mediumtext NOT NULL,
  `send_time` int(11) NOT NULL default '0',
  `hasread` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}pm`
#


# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}poll`
#

# DROP TABLE IF EXISTS `{prefix}poll`;
CREATE TABLE `{prefix}poll` (
  `id` int(11) NOT NULL auto_increment,
  `question` varchar(100) NOT NULL default '',
  `timeline` int(11) NOT NULL default '0',
  `options` text NOT NULL,
  `votes` text NOT NULL,
  `timeout` int(11) NOT NULL default '0',
  `multiple` tinyint(4) NOT NULL default '0',
  `voters` text NOT NULL,
  `total` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}poll`
#


# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}posts`
#

# DROP TABLE IF EXISTS `{prefix}posts`;
CREATE TABLE `{prefix}posts` (
  `id` int(11) NOT NULL auto_increment,
  `tid` bigint(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `message` text NOT NULL,
  `postdate` int(10) NOT NULL default '0',
  `ip` varchar(30) NOT NULL default '',
  `is_first` tinyint(4) NOT NULL default '0',
  `attach_ids` varchar(255) NOT NULL default '',
  `bbcode_off` tinyint(4) NOT NULL default '0',
  `emote_off` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `tid` (`tid`),
  KEY `author_id` (`author_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}posts`
#


# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}posts_c`
#

# DROP TABLE IF EXISTS `{prefix}posts_c`;
CREATE TABLE `{prefix}posts_c` (
  `id` int(11) NOT NULL auto_increment,
  `tid` bigint(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `message` text NOT NULL,
  `postdate` int(10) NOT NULL default '0',
  `ip` varchar(30) NOT NULL default '',
  `is_first` tinyint(4) NOT NULL default '0',
  `attach_ids` varchar(255) NOT NULL default '',
  `bbcode_off` tinyint(4) NOT NULL default '0',
  `emote_off` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `tid` (`tid`),
  KEY `author_id` (`author_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}posts_c`
#


# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}posts_f`
#

# DROP TABLE IF EXISTS `{prefix}posts_f`;
CREATE TABLE `{prefix}posts_f` (
  `id` int(11) NOT NULL auto_increment,
  `tid` bigint(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `message` text NOT NULL,
  `postdate` int(10) NOT NULL default '0',
  `ip` varchar(30) NOT NULL default '',
  `is_first` tinyint(4) NOT NULL default '0',
  `attach_ids` varchar(255) NOT NULL default '',
  `bbcode_off` tinyint(4) NOT NULL default '0',
  `emote_off` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `tid` (`tid`),
  KEY `author_id` (`author_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}posts_f`
#


# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}posts_r`
#

# DROP TABLE IF EXISTS `{prefix}posts_r`;
CREATE TABLE `{prefix}posts_r` (
  `id` int(11) NOT NULL auto_increment,
  `tid` bigint(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `message` text NOT NULL,
  `postdate` int(10) NOT NULL default '0',
  `ip` varchar(30) NOT NULL default '',
  `is_first` tinyint(4) NOT NULL default '0',
  `attach_ids` varchar(255) NOT NULL default '',
  `bbcode_off` tinyint(4) NOT NULL default '0',
  `emote_off` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `tid` (`tid`),
  KEY `author_id` (`author_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}posts_r`
#


# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}themes`
#

# DROP TABLE IF EXISTS `{prefix}themes`;
CREATE TABLE `{prefix}themes` (
  `id` int(11) NOT NULL auto_increment,
  `flag` varchar(100) NOT NULL default '',
  `theme` varchar(255) NOT NULL default '',
  `img` varchar(255) NOT NULL default '',
  `css` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=17 ;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}themes`
#

INSERT INTO `{prefix}themes` VALUES (10, 'DarkRed', 'bbs/themes/default', 'bbs/themes/default/images/darkred', 'bbs/themes/default/css/darkred');
INSERT INTO `{prefix}themes` VALUES (9, 'DarkBlue', 'bbs/themes/default', 'bbs/themes/default/images/darkblue', 'bbs/themes/default/css/darkblue');
INSERT INTO `{prefix}themes` VALUES (12, 'Blue', 'bbs/themes/default', 'bbs/themes/default/images/blue', 'bbs/themes/default/css/blue');
INSERT INTO `{prefix}themes` VALUES (13, 'Full', 'bbs/themes/default', 'bbs/themes/default/images/blue', 'bbs/themes/default/css/full');
INSERT INTO `{prefix}themes` VALUES (14, 'FullBlue', 'bbs/themes/default', 'bbs/themes/default/images/blue', 'bbs/themes/default/css/fullblue');
INSERT INTO `{prefix}themes` VALUES (15, 'Simple', 'bbs/themes/default', 'bbs/themes/default/images/blue', 'bbs/themes/default/css/simple');
INSERT INTO `{prefix}themes` VALUES (16, 'Full_light', 'bbs/themes/default', 'bbs/themes/default/images/blue', 'bbs/themes/default/css/full_light');
 
# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}threads`
#

# DROP TABLE IF EXISTS `{prefix}threads`;
CREATE TABLE `{prefix}threads` (
  `id` int(11) NOT NULL auto_increment,
  `belong_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `views` int(3) NOT NULL default '0',
  `replies` int(3) NOT NULL default '0',
  `closed` smallint(1) NOT NULL default '0',
  `postdate` int(11) NOT NULL default '0',
  `lastpostby` varchar(255) NOT NULL default '0',
  `lastpostdate` int(10) NOT NULL default '0',
  `icon` tinyint(4) NOT NULL default '0',
  `top_it` tinyint(4) NOT NULL default '0',
  `lock_it` tinyint(4) NOT NULL default '0',
  `poll_id` int(11) NOT NULL default '0',
  `stressed` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `belong_id` (`belong_id`),
  KEY `author_id` (`author_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}threads`
#


# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}threads_c`
#

# DROP TABLE IF EXISTS `{prefix}threads_c`;
CREATE TABLE `{prefix}threads_c` (
  `id` int(11) NOT NULL default '0',
  `belong_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `views` int(3) NOT NULL default '0',
  `replies` int(3) NOT NULL default '0',
  `closed` smallint(1) NOT NULL default '0',
  `postdate` int(11) NOT NULL default '0',
  `lastpostby` varchar(255) NOT NULL default '0',
  `lastpostdate` int(10) NOT NULL default '0',
  `icon` tinyint(4) NOT NULL default '0',
  `top_it` tinyint(4) NOT NULL default '0',
  `lock_it` tinyint(4) NOT NULL default '0',
  `poll_id` int(11) NOT NULL default '0',
  `stressed` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `belong_id` (`belong_id`),
  KEY `author_id` (`author_id`)
) TYPE=MyISAM;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}threads_c`
#


# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}threads_f`
#

# DROP TABLE IF EXISTS `{prefix}threads_f`;
CREATE TABLE `{prefix}threads_f` (
  `id` int(11) NOT NULL default '0',
  `belong_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `views` int(3) NOT NULL default '0',
  `replies` int(3) NOT NULL default '0',
  `closed` smallint(1) NOT NULL default '0',
  `postdate` int(11) NOT NULL default '0',
  `lastpostby` varchar(255) NOT NULL default '0',
  `lastpostdate` int(10) NOT NULL default '0',
  `icon` tinyint(4) NOT NULL default '0',
  `top_it` tinyint(4) NOT NULL default '0',
  `lock_it` tinyint(4) NOT NULL default '0',
  `poll_id` int(11) NOT NULL default '0',
  `stressed` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `belong_id` (`belong_id`),
  KEY `author_id` (`author_id`)
) TYPE=MyISAM;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}threads_f`
#


# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}threads_r`
#

# DROP TABLE IF EXISTS `{prefix}threads_r`;
CREATE TABLE `{prefix}threads_r` (
  `id` int(11) NOT NULL default '0',
  `belong_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `views` int(3) NOT NULL default '0',
  `replies` int(3) NOT NULL default '0',
  `closed` smallint(1) NOT NULL default '0',
  `postdate` int(11) NOT NULL default '0',
  `lastpostby` varchar(255) NOT NULL default '0',
  `lastpostdate` int(10) NOT NULL default '0',
  `icon` tinyint(4) NOT NULL default '0',
  `top_it` tinyint(4) NOT NULL default '0',
  `lock_it` tinyint(4) NOT NULL default '0',
  `poll_id` int(11) NOT NULL default '0',
  `stressed` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `belong_id` (`belong_id`),
  KEY `author_id` (`author_id`)
) TYPE=MyISAM;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}threads_r`
#


# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}users`
#
# DROP TABLE IF EXISTS `{prefix}users`;
CREATE TABLE `{prefix}users` (
  `id` int(11) NOT NULL auto_increment,
  `group_id` varchar(255) NOT NULL default '0',
  `username` varchar(50) NOT NULL default '',
  `password` varchar(50) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `intr` text NOT NULL,
  `reg_time` int(11) NOT NULL default '0',
  `post_num` int(11) NOT NULL default '0',
  `score` int(11) NOT NULL default '0',
  `headpic` varchar(255) NOT NULL default '',
  `h_width` int(3) NOT NULL default '0',
  `h_height` int(3) NOT NULL default '0',
  `sig` tinytext NOT NULL,
  `hidden_info` text NOT NULL,
  `lastposttime` int(11) NOT NULL default '0',
  `sex` varchar(10) NOT NULL default '',
  `honor` varchar(20) NOT NULL default '',
  `banned` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=9 ;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}users`
#

INSERT INTO `{prefix}users` VALUES (1, '', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'webmaster@yourdomain.com', '', 1099015085, 0, 5000, '', 0, 0, '', '', 1052460461, '', '', 0);
INSERT INTO `{prefix}users` VALUES (6, '13', 'test', '098f6bcd4621d373cade4e832627b4f6', 'test@test.com', '', 1082886524, 0, 0, '', 0, 0, '', '', 0, '', '', 0);

# --------------------------------------------------------

#
# ±íµÄ½á¹¹ `{prefix}badlogin`
#
# DROP TABLE IF EXISTS `{prefix}badlogin`;
CREATE TABLE `{prefix}badlogin` (
  `ip` varchar(20) NOT NULL default '',
  `badlogins` tinyint(4) NOT NULL default '0',
  `timeline` int(11) NOT NULL default '0'
) TYPE=MyISAM;

#
# µ¼³ö±íÖÐµÄÊý¾Ý `{prefix}badlogin`
#

INSERT INTO `{prefix}badlogin` VALUES ('127.0.0.1', 1, 1118212192);