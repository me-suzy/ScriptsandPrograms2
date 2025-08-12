CREATE TABLE af_manager (
  `id` int(11) NOT NULL auto_increment,
  `sitename` text,
  `siteurl` text,
  `where2` text,
  `type` text,
  `width` text,
  `height` text,
  `ss` text,
  `verified` text,
  `date` text,
  `clicks` INT(11) NOT NULL default '',
  PRIMARY KEY `id` (`id`),
  KEY `id` (`id`),
  FULLTEXT KEY `sitename` (`sitename`),
  FULLTEXT KEY `siteurl` (`siteurl`),
  FULLTEXT KEY `where2` (`where2`),
  FULLTEXT KEY `type` (`type`),
  FULLTEXT KEY `width` (`width`),
  FULLTEXT KEY `height` (`height`),
  FULLTEXT KEY `ss` (`ss`),
  FULLTEXT KEY `verified` (`verified`),
  FULLTEXT KEY `date` (`date`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_ad (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `type` text,
  `grp` text,
  `coding` text,
  `dim` text,
  `user` char(3) NOT NULL default '',
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `name` (`name`,`type`,`grp`,`coding`),
  FULLTEXT KEY `dim` (`dim`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_albums (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `type` text,
  `album` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `name` (`name`,`type`),
  FULLTEXT KEY `album` (`album`)
) TYPE=MyISAM AUTO_INCREMENT=1 ; 
-------
CREATE TABLE onecms_blog (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` text NOT NULL,
  `blog` text NOT NULL,
  `username` text NOT NULL,
  `date` text NOT NULL,
  PRIMARY KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_boardcp (
  `id` int(11) NOT NULL auto_increment,
  `uid` text,
  `place` text,
  `type` text,
  `level` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `uid` (`uid`,`place`,`type`,`level`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_cat (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `date` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `stats` (`date`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=7 ;
-------
CREATE TABLE onecms_chat (
  `id` int(11) NOT NULL auto_increment,
  `uid` text,
  `subject` text,
  `message` text,
  `date` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `username` (`uid`,`message`,`date`),
  FULLTEXT KEY `subject` (`subject`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_comments1 (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `field` text,
  `type` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `name` (`name`,`field`),
  FULLTEXT KEY `type` (`type`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=9 ;
-------
CREATE TABLE onecms_comments2 (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `subject` text,
  `comment` text,
  `aid` text,
  `email` text,
  `date` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `name` (`name`,`subject`,`comment`,`aid`,`email`,`date`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_content (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `cat` text,
  `username` text,
  `date` text,
  `ver` text,
  `postpone` text,
  `stats` INT(11) NOT NULL default '0',
  `lev` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `name` (`name`,`cat`),
  FULLTEXT KEY `date` (`date`),
  FULLTEXT KEY `username` (`username`),
  FULLTEXT KEY `ver` (`ver`),
  FULLTEXT KEY `postpone` (`postpone`),
  FULLTEXT KEY `lev` (`lev`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_contest (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `des` text,
  `email` text,
  `type` text,
  `rules` text,
  `priv` text,
  `posts` text,
  `username` text,
  `ip` text,
  `cid` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `name` (`name`,`des`,`email`,`type`,`rules`),
  FULLTEXT KEY `priv` (`priv`),
  FULLTEXT KEY `posts` (`posts`),
  FULLTEXT KEY `username` (`username`),
  FULLTEXT KEY `ip` (`ip`),
  FULLTEXT KEY `cid` (`cid`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_elite (
  `id` int(11) NOT NULL auto_increment,
  `pid` varchar(100) NOT NULL default '',
  `game` text NOT NULL,
  `type` varchar(30) NOT NULL default '',
  `date` varchar(200) NOT NULL default '',
  PRIMARY KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_fields (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `cat` text,
  `type` text,
  `des` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `cat` (`cat`),
  FULLTEXT KEY `type` (`type`),
  FULLTEXT KEY `des` (`des`)
) TYPE=MyISAM AUTO_INCREMENT=45 ;
-------
CREATE TABLE onecms_forums (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `des` text,
  `type` text,
  `cat` text,
  `ord` text,
  `locked` char(3) NOT NULL default '',
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `name` (`name`,`des`,`type`,`cat`),
  FULLTEXT KEY `order` (`ord`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;
-------
CREATE TABLE onecms_friends (
  `id` int(11) NOT NULL auto_increment,
  `pid` varchar(100) NOT NULL default '',
  `pid2` varchar(100) NOT NULL default '',
  `ver` char(3) NOT NULL default '',
  `date` varchar(200) NOT NULL default '',
  PRIMARY KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_game (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `type` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `name` (`name`,`type`)
) TYPE=MyISAM AUTO_INCREMENT=11 ;
-------
CREATE TABLE onecms_games (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `stats` INT(11) NOT NULL default '0',
  `username` text,
  `publisher` text,
  `developer` text,
  `genre` text,
  `release` text,
  `esrb` text,
  `boxart` text,
  `des` text,
  `skin` varchar(11) NOT NULL default '',
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `name` (`name`,`publisher`,`developer`,`genre`,`release`,`esrb`,`boxart`,`des`),
  FULLTEXT KEY `username` (`username`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_images (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `album` varchar(11) NOT NULL default '',
  `caption` text NOT NULL,
  `type` text,
  `date` text,
  `watermark` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `name` (`name`,`type`),
  FULLTEXT KEY `date` (`date`),
  FULLTEXT KEY `watermark` (`watermark`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=12 ;
-------
CREATE TABLE onecms_ipban (
  `id` int(11) NOT NULL auto_increment,
  `ip` text,
  `forums` text,
  `site` text,
  `cp` text,
  `date` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `ip` (`ip`,`forums`,`site`,`cp`,`date`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_log (
  `id` int(11) NOT NULL auto_increment,
  `url` text,
  `ip` text,
  `date` text,
  `username` text,
  PRIMARY KEY `id` (`id`),
  KEY `id` (`id`),
  FULLTEXT KEY `url` (`url`),
  FULLTEXT KEY `ip` (`ip`),
  FULLTEXT KEY `date` (`date`),
  FULLTEXT KEY `username` (`username`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_mods (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `url` text,
  `installed` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `name` (`name`,`url`,`installed`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_newsletter (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `type` text NOT NULL,
  `content` text NOT NULL,
  `cat` varchar(11) NOT NULL default '',
  `date` text NOT NULL,
  PRIMARY KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_pages (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `url` text,
  `content` text,
  `online` text,
  `type` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `name` (`name`,`url`,`content`,`online`),
  FULLTEXT KEY `type` (`type`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_permissions (
  `id` int(11) NOT NULL auto_increment,
  `username` text,
  `ver` text,
  `games` text,
  `reviews` varchar(10) NOT NULL default 'yes',
  `previews` varchar(10) NOT NULL default 'yes',
  `news` varchar(10) NOT NULL default 'yes',
  `media` varchar(10) NOT NULL default 'yes',
  `cheats` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `username` (`username`),
  FULLTEXT KEY `ver` (`ver`),
  FULLTEXT KEY `games` (`games`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_pm (
  `id` int(11) NOT NULL auto_increment,
  `viewed` text,
  `subject` text,
  `message` text,
  `who` text,
  `jo` text,
  `date` text,
  PRIMARY KEY `id` (`id`),
  KEY `id` (`id`),
  FULLTEXT KEY `read` (`viewed`),
  FULLTEXT KEY `subject` (`subject`),
  FULLTEXT KEY `message` (`message`),
  FULLTEXT KEY `from` (`who`),
  FULLTEXT KEY `to` (`jo`),
  FULLTEXT KEY `date` (`date`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_points (
  `id` int(11) NOT NULL auto_increment,
  `username` text NOT NULL,
  `item` varchar(11) NOT NULL default '',
  `points` text NOT NULL,
  `date` varchar(200) NOT NULL default '',
  PRIMARY KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_posts (
  `id` int(11) NOT NULL auto_increment,
  `subject` text,
  `message` text,
  `uid` text,
  `date` text,
  `fid` text,
  `tid` text,
  `stats` INT(11) NOT NULL default '0',
  `type` text,
  `locked` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `subject` (`subject`,`message`,`uid`,`date`,`fid`),
  FULLTEXT KEY `tid` (`tid`),
  FULLTEXT KEY `type` (`type`),
  FULLTEXT KEY `locked` (`locked`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_pr (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `type` text,
  `fname` text,
  `lname` text,
  `email` text,
  `site` text,
  `des` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `name` (`name`,`type`,`fname`,`lname`,`email`),
  FULLTEXT KEY `site` (`site`,`des`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_profile (
  `id` int(11) NOT NULL auto_increment,
  `username` text,
  `aim` text,
  `msn` text,
  `website` text,
  `nickname` text,
  `location` text,
  `sig` text,
  `avatar` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `username` (`username`,`aim`,`msn`,`website`,`nickname`,`location`),
  FULLTEXT KEY `sig` (`sig`,`avatar`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_ranks (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `color` text NOT NULL,
  `points` text NOT NULL,
  `date` varchar(200) NOT NULL default '',
  PRIMARY KEY `id` (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=3 ;
-------
CREATE TABLE onecms_settings (
  `id` int(11) NOT NULL auto_increment,
  `sname` text NOT NULL,
  `sitename` text,
  `siteurl` text,
  `online` text,
  `dformat` text,
  `warn` text,
  `images` text,
  `path` text,
  `max_results` text,
  `email` text,
  `name` text,
  `width` text,
  `height` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `sitename` (`sitename`,`siteurl`,`online`),
  FULLTEXT KEY `dformat` (`dformat`,`warn`,`images`,`path`,`max_results`,`email`,`name`),
  FULLTEXT KEY `width` (`width`,`height`)
) TYPE=MyISAM AUTO_INCREMENT=7 ;
-------
CREATE TABLE onecms_shop (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `price` text NOT NULL,
  `instock` text NOT NULL,
  `image` text NOT NULL,
  `pid` varchar(11) NOT NULL default '',
  `date` varchar(200) NOT NULL default '',
  PRIMARY KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_shop2 (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  `price` text NOT NULL,
  `pid` varchar(11) NOT NULL default '',
  `date` varchar(200) NOT NULL default '',
  PRIMARY KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
-------
CREATE TABLE onecms_skins (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `type` text,
  `header` text,
  `footer` text,
  `images` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `name` (`name`,`type`,`header`,`footer`),
  FULLTEXT KEY `images` (`images`)
) TYPE=MyISAM AUTO_INCREMENT=8 ;
-------
CREATE TABLE onecms_systems (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `abr` text,
  `icon` text,
  `status` char(3) NOT NULL default '',
  `skin` varchar(11) NOT NULL default '',
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `abr` (`abr`),
  FULLTEXT KEY `stats` (`icon`)
) TYPE=MyISAM AUTO_INCREMENT=8 ;
-------
CREATE TABLE onecms_templates (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `template` text,
  `type` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `template` (`template`),
  FULLTEXT KEY `type` (`type`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=27 ;
-------
CREATE TABLE onecms_userlevels (
  `id` int(11) NOT NULL auto_increment,
  `name` text,
  `level` text,
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `name` (`name`,`level`)
) TYPE=MyISAM AUTO_INCREMENT=5 ;
-------
CREATE TABLE onecms_users (
  `id` int(10) NOT NULL auto_increment,
  `username` text,
  `password` text,
  `email` text,
  `level` text,
  `warn` text,
  `bansite` text,
  `banadmin` text,
  `logged` text,
  `skin` varchar(10) NOT NULL default '1',
  `skin2` text,
  `slist` char(3) NOT NULL default '',
  PRIMARY KEY `id` (`id`),
  FULLTEXT KEY `warn` (`warn`,`bansite`,`banadmin`),
  FULLTEXT KEY `username` (`username`,`password`,`email`),
  FULLTEXT KEY `logged` (`logged`),
  FULLTEXT KEY `skin` (`skin`),
  FULLTEXT KEY `skin2` (`skin2`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1;