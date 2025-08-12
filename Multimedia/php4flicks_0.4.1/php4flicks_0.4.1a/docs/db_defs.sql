-- 
-- Table structure for table `movies`
-- 

CREATE TABLE `movies` (
  `fid` smallint(5) unsigned NOT NULL auto_increment,				# pk
  `id` mediumint(7) unsigned zerofill NOT NULL default '0000000',		# imdb-key
  `nr` smallint(3) unsigned zerofill NOT NULL default '000',
  `runtime` tinyint(3) unsigned NOT NULL default '0',
  `inserted` date default NULL,
  `year` year(4) default NULL,
  `genre` set('Action','Adult','Adventure','Animation','Comedy','Crime','Documentary','Drama','Family','Fantasy','Film-Noir','Horror','Music','Musical','Mystery','Romance','Sci-Fi','Short','Thriller',' War','Western') default NULL,
  `sound` set('DD','DTS','stereo','mono') default NULL,
  `lang` set('DE','EN','FR','ES','Other') default 'EN',
  `ratio` enum('16:9','4:3','letterbox') default '16:9',
  `format` enum('PAL','NTSC') default 'PAL',
  `medium` enum('dvd','vhs','svhs','divX/Xvid','vcd/Svcd','dvd-r') NOT NULL default 'dvd',
  `name` varchar(100) NOT NULL default '',
  `aka` varchar(200) default NULL,
  `cat` varchar(10) default NULL,
  `comment` varchar(200) default NULL,
  `poster` blob,
  PRIMARY KEY  (`fid`),
  KEY `nr` (`nr`),
  FULLTEXT KEY `name` (`name`,`aka`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `people`
-- 

CREATE TABLE `people` (
  `id` mediumint(7) unsigned zerofill NOT NULL default '0000000',
  `name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `plays_in`
-- 

CREATE TABLE `plays_in` (
  `movie_fid` smallint(5) unsigned NOT NULL default '0',
  `people_id` mediumint(7) unsigned zerofill NOT NULL default '0000000',
  PRIMARY KEY  (`movie_fid`,`people_id`)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Table structure for table `writes`
-- 

CREATE TABLE `writes` (
  `movie_fid` smallint(5) unsigned NOT NULL default '0',
  `people_id` mediumint(7) unsigned zerofill NOT NULL default '0000000',
  PRIMARY KEY  (`movie_fid`,`people_id`)
) TYPE=MyISAM;
        
-- --------------------------------------------------------

-- 
-- Table structure for table `directs`
-- 

CREATE TABLE `directs` (
  `movie_fid` smallint(5) unsigned NOT NULL default '0',
  `people_id` mediumint(7) unsigned zerofill NOT NULL default '0000000',
  PRIMARY KEY  (`movie_fid`,`people_id`)
) TYPE=MyISAM;
