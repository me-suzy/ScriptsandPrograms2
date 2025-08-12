# --------------------------------------------------------

#
# Album
#

ALTER TABLE `album` CHANGE `year` `year` SMALLINT( 4 ) UNSIGNED NULL DEFAULT NULL ;
ALTER TABLE `album` CHANGE `month` `month` TINYINT( 2 ) UNSIGNED NULL DEFAULT NULL ;
ALTER TABLE `album` CHANGE `counter` `counter` INT( 10 ) UNSIGNED NOT NULL DEFAULT '0' ; 
ALTER TABLE `album` CHANGE `cds` `cds` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT '0' ;
ALTER TABLE `album` CHANGE `updated` `updated` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' ;

# --------------------------------------------------------

#
# Configuration Database
#

ALTER TABLE `configuration_database` CHANGE `version` `version` TINYINT( 3 ) NOT NULL DEFAULT '0' ;

# --------------------------------------------------------

#
# Configuration httpQ
#

ALTER TABLE `configuration_httpq` CHANGE `httpq_port` `httpq_port` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '4800' ;

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
# Bitmap
#

ALTER TABLE `bitmap` CHANGE `flag` `flag` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT '0' ;
ALTER TABLE `bitmap` CHANGE `updated` `updated` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' ;

# --------------------------------------------------------

#
# Track
#

ALTER TABLE `track` CHANGE `audio_channels` `audio_channels` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT '0' ;
ALTER TABLE `track` CHANGE `cd` `cd` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT '0' ;
ALTER TABLE `track` CHANGE `updated` `updated` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' ;

# --------------------------------------------------------

#
# Favorites
#

TRUNCATE TABLE `favorites` ;
TRUNCATE TABLE `favoritesitems` ;
ALTER TABLE `favorites` CHANGE `stream` `stream` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' ;

# --------------------------------------------------------

#
# Configuration Users
#

DROP TABLE `configuration_users` ;
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
# Default users
#

INSERT INTO `configuration_users` VALUES ('admin', '21232f297a57a5a743894a0e4a801fc3', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '');
INSERT INTO `configuration_users` VALUES ('anonymous', '294de3557d9d00b3d2d8a1e6aab028cf', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '');

# --------------------------------------------------------

#
# Database version
#

INSERT INTO configuration_database VALUES (11) ;
