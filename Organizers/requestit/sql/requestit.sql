-- 
-- Table structure for table `admin`
-- 

CREATE TABLE `admin` (
  `id` tinyint(3) NOT NULL auto_increment,
  `username` varchar(100) NOT NULL default '',
  `password` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Admin Account Section' AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `dj`
-- 

CREATE TABLE `dj` (
  `id` tinyint(3) NOT NULL auto_increment,
  `username` varchar(100) NOT NULL default '',
  `password` varchar(16) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='Admin Account Section' AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `request`
-- 

CREATE TABLE `request` (
  `id` int(6) NOT NULL auto_increment,
  `artist` varchar(100) NOT NULL default '',
  `song` varchar(100) NOT NULL default '',
  `username` varchar(100) NOT NULL default '',
  `info` text NOT NULL,
  `explicit` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM COMMENT='User Request Section' AUTO_INCREMENT=47 ;
