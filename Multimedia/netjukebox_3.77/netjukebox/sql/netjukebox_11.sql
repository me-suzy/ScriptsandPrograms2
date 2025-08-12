# phpMyAdmin SQL Dump
# version 2.6.4-pl2
# http://www.phpmyadmin.net
# 
# Database: `netjukebox`
# 

# --------------------------------------------------------

# 
# Table structure for table `album`
# 

CREATE TABLE `album` (
  `artist` varchar(255) NOT NULL default '',
  `artist_alphabetic` varchar(255) NOT NULL default '',
  `album` varchar(255) NOT NULL default '',
  `year` smallint(4) unsigned default NULL,
  `month` tinyint(2) unsigned default NULL,
  `genre_id` varchar(10) NOT NULL default '',
  `counter` int(10) unsigned NOT NULL default '0',
  `counter_update_time` int(10) unsigned NOT NULL default '0',
  `album_add_time` int(10) unsigned NOT NULL default '0',
  `cds` tinyint(3) unsigned NOT NULL default '0',
  `album_id` varchar(32) NOT NULL default '',
  `updated` tinyint(1) unsigned NOT NULL default '0',
  KEY `album` (`album`),
  KEY `genre_id` (`genre_id`),
  KEY `artist_alphabetic` (`artist_alphabetic`),
  KEY `updated` (`updated`),
  KEY `year` (`year`,`month`),
  KEY `artist` (`artist`),
  KEY `album_id` (`album_id`)
) TYPE=MyISAM;

# --------------------------------------------------------

# 
# Table structure for table `bitmap`
# 

CREATE TABLE `bitmap` (
  `image50` mediumblob NOT NULL,
  `image100` mediumblob NOT NULL,
  `image200` mediumblob NOT NULL,
  `filemtime` int(10) unsigned NOT NULL default '0',
  `flag` tinyint(3) unsigned NOT NULL default '0',
  `cd_front` varchar(255) NOT NULL default '',
  `cd_back` varchar(255) NOT NULL default '',
  `album_id` varchar(32) NOT NULL default '',
  `updated` tinyint(1) unsigned NOT NULL default '0',
  KEY `album_id` (`album_id`),
  KEY `filemtime` (`filemtime`),
  KEY `cd_front` (`cd_front`),
  KEY `cd_back` (`cd_back`),
  KEY `flag` (`flag`)
) TYPE=MyISAM;

# --------------------------------------------------------

# 
# Table structure for table `configuration_database`
# 

CREATE TABLE `configuration_database` (
  `version` tinyint(3) NOT NULL default '0',
  KEY `version` (`version`)
) TYPE=MyISAM;

# --------------------------------------------------------

# 
# Table structure for table `configuration_httpq`
# 

CREATE TABLE `configuration_httpq` (
  `name` varchar(255) NOT NULL default '',
  `httpq_host` varchar(255) NOT NULL default '',
  `httpq_port` smallint(5) unsigned NOT NULL default '4800',
  `httpq_pass` varchar(255) NOT NULL default '',
  `media_share` varchar(255) NOT NULL default '',
  `httpq_id` int(10) NOT NULL auto_increment,
  PRIMARY KEY  (`httpq_id`),
  KEY `name` (`name`)
) TYPE=MyISAM;

# --------------------------------------------------------

# 
# Table structure for table `configuration_session`
# 

CREATE TABLE `configuration_session` (
  `logged_in` tinyint(1) unsigned NOT NULL default '0',
  `user_id` int(10) unsigned NOT NULL default '0',
  `valid_counter` int(10) unsigned NOT NULL default '0',
  `failed_counter` int(10) unsigned NOT NULL default '0',
  `visit_counter` int(10) unsigned NOT NULL default '0',
  `create_time` int(10) unsigned NOT NULL default '0',
  `login_time` int(10) unsigned NOT NULL default '0',
  `idle_time` int(10) unsigned NOT NULL default '0',
  `ip` varchar(255) NOT NULL default '',
  `user_agent` varchar(255) NOT NULL default '',
  `sid` varchar(40) NOT NULL default '',
  `seed` varchar(32) NOT NULL default '',
  `secret` varchar(32) NOT NULL default '',
  `thumbnail_size` tinyint(3) unsigned NOT NULL default '100',
  `stream_id` int(10) NOT NULL default '0',
  `httpq_id` int(10) unsigned NOT NULL default '0',
  `session_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`session_id`),
  KEY `user_id` (`user_id`),
  KEY `idle_time` (`idle_time`),
  KEY `sid` (`sid`)
) TYPE=MyISAM;

# --------------------------------------------------------

# 
# Table structure for table `configuration_users`
# 

CREATE TABLE `configuration_users` (
  `username` varchar(255) NOT NULL default '',
  `password` varchar(32) NOT NULL default '',
  `access_browse` tinyint(1) unsigned NOT NULL default '0',
  `access_favorites` tinyint(1) unsigned NOT NULL default '0',
  `access_playlist` tinyint(1) unsigned NOT NULL default '0',
  `access_play` tinyint(1) unsigned NOT NULL default '0',
  `access_add` tinyint(1) unsigned NOT NULL default '0',
  `access_stream` tinyint(1) unsigned NOT NULL default '0',
  `access_download` tinyint(1) unsigned NOT NULL default '0',
  `access_cover` tinyint(1) unsigned NOT NULL default '0',
  `access_record` tinyint(1) unsigned NOT NULL default '0',
  `access_config` tinyint(1) unsigned NOT NULL default '0',
  `user_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`user_id`),
  KEY `username` (`username`)
) TYPE=MyISAM;

# --------------------------------------------------------

# 
# Table structure for table `favorites`
# 

CREATE TABLE `favorites` (
  `name` varchar(255) NOT NULL default '',
  `comment` varchar(255) NOT NULL default '',
  `stream` tinyint(1) unsigned NOT NULL default '0',
  `favorites_id` int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (`favorites_id`),
  KEY `comment` (`comment`),
  KEY `name` (`name`)
) TYPE=MyISAM;

# --------------------------------------------------------

# 
# Table structure for table `favoritesitems`
# 

CREATE TABLE `favoritesitems` (
  `track_id` varchar(41) NOT NULL default '',
  `stream_url` varchar(255) NOT NULL default '',
  `position` int(10) unsigned NOT NULL default '0',
  `favorites_id` int(10) unsigned NOT NULL default '0',
  KEY `favorites_id` (`favorites_id`,`position`)
) TYPE=MyISAM;

# --------------------------------------------------------

# 
# Table structure for table `genre`
# 

CREATE TABLE `genre` (
  `genre_id` varchar(10) NOT NULL default '',
  `genre` varchar(255) NOT NULL default '',
  KEY `genre` (`genre`),
  KEY `genre_id` (`genre_id`)
) TYPE=MyISAM;

# --------------------------------------------------------

# 
# Table structure for table `track`
# 

CREATE TABLE `track` (
  `artist` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `featuring` varchar(255) NOT NULL default '',
  `relative_file` varchar(255) NOT NULL default '',
  `filemtime` int(10) unsigned NOT NULL default '0',
  `playtime` varchar(8) NOT NULL default '',
  `playtime_miliseconds` int(10) unsigned NOT NULL default '0',
  `file_size` int(10) unsigned NOT NULL default '0',
  `audio_bitrate` int(10) unsigned NOT NULL default '0',
  `audio_raw_decoded` int(10) unsigned NOT NULL default '0',
  `audio_bits_per_sample` int(10) unsigned NOT NULL default '0',
  `audio_sample_rate` int(10) unsigned NOT NULL default '0',
  `audio_channels` tinyint(3) unsigned NOT NULL default '0',
  `audio_dataformat` varchar(64) NOT NULL default '',
  `audio_encoder` varchar(64) NOT NULL default '',
  `audio_profile` varchar(64) NOT NULL default '',
  `video_dataformat` varchar(64) NOT NULL default '',
  `video_codec` varchar(64) NOT NULL default '',
  `video_resolution_x` int(10) unsigned NOT NULL default '0',
  `video_resolution_y` int(10) unsigned NOT NULL default '0',
  `video_framerate` int(10) unsigned NOT NULL default '0',
  `cd` tinyint(3) unsigned NOT NULL default '0',
  `album_id` varchar(32) NOT NULL default '',
  `track_id` varchar(41) NOT NULL default '',
  `updated` tinyint(1) unsigned NOT NULL default '0',
  KEY `cd` (`cd`),
  KEY `artist` (`artist`),
  KEY `title` (`title`),
  KEY `relative_file` (`relative_file`),
  KEY `track_id` (`track_id`),
  KEY `album_id` (`album_id`,`cd`),
  KEY `updated` (`updated`),
  KEY `audio_dataformat` (`audio_dataformat`),
  KEY `video_dataformat` (`video_dataformat`)
) TYPE=MyISAM;

# --------------------------------------------------------

#
# Genre example
# 

INSERT INTO genre VALUES ('a', 'Pop');
INSERT INTO genre VALUES ('aa', 'Rock');
INSERT INTO genre VALUES ('ab', 'Alternative');
INSERT INTO genre VALUES ('b', 'Soul');
INSERT INTO genre VALUES ('ba', 'R&B');
INSERT INTO genre VALUES ('c', 'Dance');
INSERT INTO genre VALUES ('ca', 'Triphop');
INSERT INTO genre VALUES ('cb', 'Rap & Hiphop');
INSERT INTO genre VALUES ('cc', 'Ambient');
INSERT INTO genre VALUES ('d', 'Roots');
INSERT INTO genre VALUES ('da', 'World');
INSERT INTO genre VALUES ('db', 'Folk');
INSERT INTO genre VALUES ('dc', 'Blues');
INSERT INTO genre VALUES ('e', 'Jazz');
INSERT INTO genre VALUES ('f', 'Klasiek');
INSERT INTO genre VALUES ('g', 'Cabaret');
INSERT INTO genre VALUES ('h', 'Video');
INSERT INTO genre VALUES ('de', 'Country');
INSERT INTO genre VALUES ('df', 'Regea');
INSERT INTO genre VALUES ('i', 'Soundtrack');

# --------------------------------------------------------

# 
# Default users
#

INSERT INTO `configuration_users` VALUES ('admin', '21232f297a57a5a743894a0e4a801fc3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '');
INSERT INTO `configuration_users` VALUES ('anonymous', '294de3557d9d00b3d2d8a1e6aab028cf', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '');

# --------------------------------------------------------

#
# Database version
#

INSERT INTO configuration_database VALUES (11) ;
