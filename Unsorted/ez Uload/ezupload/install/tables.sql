CREATE TABLE `ezu_fields` (
  `id` int(10) unsigned NOT NULL default '0',
  `order` int(10) unsigned NOT NULL default '0',
  `type` varchar(30) NOT NULL default '',
  `required` tinyint(3) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `description` text NOT NULL,
  `default` text NOT NULL,
  `minchars` varchar(5) NOT NULL default '',
  `maxchars` varchar(5) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE `ezu_files` (
  `id` bigint(20) unsigned NOT NULL default '0',
  `upload` bigint(20) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `type` varchar(100) NOT NULL default '',
  `size` bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE `ezu_options` (
  `id` bigint(20) unsigned NOT NULL default '0',
  `field` int(11) unsigned NOT NULL default '0',
  `value` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE `ezu_uploadinfos` (
  `id` bigint(20) unsigned NOT NULL default '0',
  `upload` bigint(20) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `value` mediumtext NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE `ezu_uploads` (
  `id` bigint(20) unsigned NOT NULL default '0',
  `uploaded` int(10) unsigned NOT NULL default '0',
  `subdir` varchar(50) NOT NULL default '',
  `name` varchar(50) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `user` bigint(20) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

CREATE TABLE `ezu_users` (
  `id` bigint(20) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `email` varchar(50) NOT NULL default '',
  `password` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;