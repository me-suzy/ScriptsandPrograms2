#
# Table structure for table `snipe_gallery_cat`
#

CREATE TABLE `snipe_gallery_cat` (
  `id` int(11) NOT NULL auto_increment,
  `cat_parent` int(11) NOT NULL default '0',
  `name` varchar(200) default NULL,
  `description` blob,
  `imagefile` varchar(100) NOT NULL default '',
  `default_thumbtype` int(1) NOT NULL default '1',
  `frame_style` int(11) default NULL,
  `watermark_txt` varchar(200) default NULL,
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `display_orderby` varchar(10) default NULL,
  `display_order` varchar(4) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

# --------------------------------------------------------

#
# Table structure for table `snipe_gallery_data`
#

CREATE TABLE `snipe_gallery_data` (
  `id` int(11) NOT NULL auto_increment,
  `filename` varchar(200) default NULL,
  `thumbname` varchar(100) default NULL,
  `img_date` date default '0000-00-00',
  `title` varchar(255) default NULL,
  `details` text,
  `author` varchar(100) default NULL,
  `location` varchar(255) default NULL,
  `cat_id` int(11) default NULL,
  `keywords` varchar(250) default NULL,
  `publish` char(1) default NULL,
  `added` datetime default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

# --------------------------------------------------------

#
# Table structure for table `snipe_gallery_frames`
#

CREATE TABLE `snipe_gallery_frames` (
  `frame_id` int(11) NOT NULL auto_increment,
  `frame_name` varchar(60) NOT NULL default '',
  `top_left_sm` varchar(40) default NULL,
  `top_bg_sm` varchar(40) default NULL,
  `top_right_sm` varchar(40) default NULL,
  `left_bg_sm` varchar(40) default NULL,
  `right_bg_sm` varchar(40) default NULL,
  `bottom_left_sm` varchar(40) default NULL,
  `bottom_bg_sm` varchar(40) default NULL,
  `bottom_right_sm` varchar(40) default NULL,
  `top_left` varchar(40) default NULL,
  `top_bg` varchar(40) default NULL,
  `top_right` varchar(40) default NULL,
  `left_bg` varchar(40) default NULL,
  `right_bg` varchar(40) default NULL,
  `bottom_left` varchar(40) default NULL,
  `bottom_bg` varchar(40) default NULL,
  `bottom_right` varchar(40) default NULL,
  KEY `frame_id` (`frame_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;
