##########################################
### 
### CzarNews v1.14 MySQL Table Structures
### Made by: Czaries  [czaries@czaries.net]
### http://www.czaries.net/scripts/
### for more scripts and updates.
###
##########################################
#
# Table structure for table `cn_cats`
#

CREATE TABLE `cn_cats` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `date` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

# --------------------------------------------------------

#
# Table structure for table `cn_comments`
#

CREATE TABLE `cn_comments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `news_id` bigint(20) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `comment` text NOT NULL,
  `date` int(10) unsigned NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  KEY `id` (`id`)
);

# --------------------------------------------------------

#
# Table structure for table `cn_config`
#

CREATE TABLE `cn_config` (
  `sitename` varchar(255) NOT NULL default '',
  `siteurl` varchar(255) NOT NULL default '',
  `scripturl` varchar(255) NOT NULL default '',
  `newslimit` int(3) NOT NULL default '0',
  `timezone` int(5) NOT NULL default '0',
  `dateform` varchar(255) NOT NULL default '',
  `output` text NOT NULL,
  `source` varchar(255) NOT NULL default '',
  `version` varchar(255) NOT NULL default '',
  `words` enum('on','off') NOT NULL default 'on',
  `comments` enum('on','off') NOT NULL default 'on',
  `search` enum('on','off') NOT NULL default 'on',
  `pages` enum('on','off') NOT NULL default 'on',
  `catbox` enum('on','off') NOT NULL default 'on',
  `images` enum('on','off') NOT NULL default 'on',
  `img_thumbw` varchar(255) NOT NULL default '150',
  `img_thumbh` varchar(255) NOT NULL default '150',
  `img_dir` varchar(255) NOT NULL default 'uploads',
  `img_maxsize` int(10) unsigned NOT NULL default '0',
  `author` varchar(255) NOT NULL default '',
  `coms_text` varchar(255) NOT NULL default ''
);

# --------------------------------------------------------

#
# Table structure for table `cn_images`
#

CREATE TABLE `cn_images` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `author` int(10) unsigned NOT NULL default '0',
  `type` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `text` varchar(255) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `thumbname` varchar(255) NOT NULL default '',
  `date` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

# --------------------------------------------------------

#
# Table structure for table `cn_news`
#

CREATE TABLE `cn_news` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `author` int(10) unsigned NOT NULL default '0',
  `cat` int(10) unsigned NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `content` longtext NOT NULL,
  `content2` longtext NOT NULL,
  `sumstory` enum('on','off') NOT NULL default 'off',
  `date` int(10) unsigned NOT NULL default '0',
  `source` varchar(255) NOT NULL default '',
  `sourceurl` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
);

# --------------------------------------------------------

#
# Table structure for table `cn_users`
#

CREATE TABLE `cn_users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `user` varchar(255) NOT NULL default '',
  `pass` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `created` int(10) unsigned NOT NULL default '0',
  `last_login` int(10) unsigned NOT NULL default '0',
  `cookie` tinyint(4) NOT NULL default '0',
  `categories` varchar(255) NOT NULL default '0',
  `admin` enum('on','off') NOT NULL default 'off',
  `news` enum('on','off') NOT NULL default 'off',
  `users` enum('on','off') NOT NULL default 'off',
  `cats` enum('on','off') NOT NULL default 'off',
  `config` enum('on','off') NOT NULL default 'off',
  `words` enum('on','off') NOT NULL default 'off',
  `images` enum('on','off') NOT NULL default 'off',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `user` (`user`),
  UNIQUE KEY `user_2` (`user`),
  UNIQUE KEY `user_3` (`user`)
);

# --------------------------------------------------------

#
# Table structure for table `cn_words`
#

CREATE TABLE `cn_words` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `word` varchar(255) NOT NULL default '',
  `type` varchar(255) NOT NULL default '',
  `replaced` longtext NOT NULL,
  PRIMARY KEY  (`id`)
);