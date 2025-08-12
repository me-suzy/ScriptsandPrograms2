#
# Table structure for table `banner`
#

CREATE TABLE banner (
  id int(5) NOT NULL auto_increment,
  campaign_id int(5) default NULL,
  size int(2) default NULL,
  graphic char(255) default NULL,
  url char(255) default NULL,
  alt char(200) default NULL,
  master char(1) NOT NULL default '',
  show_text tinyint(4) NOT NULL default '0',
  popup tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `banner_campaign`
#

CREATE TABLE banner_campaign (
  id int(5) NOT NULL auto_increment,
  group_id char(20) default NULL,
  name char(200) default NULL,
  start_date datetime default NULL,
  end_date datetime default NULL,
  clicks int(5) default NULL,
  views int(8) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `banner_size`
#

CREATE TABLE banner_size (
  size_id int(11) NOT NULL auto_increment,
  size varchar(10) NOT NULL default '',
  PRIMARY KEY  (size_id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `banner_stat`
#

CREATE TABLE banner_stat (
  id int(5) NOT NULL auto_increment,
  campaign_id int(5) default NULL,
  banner_id int(5) default NULL,
  clicks int(5) default NULL,
  views int(8) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `banner_user`
#

CREATE TABLE banner_user (
  id int(5) NOT NULL auto_increment,
  full_name char(150) default NULL,
  user_id char(20) binary default NULL,
  pass char(15) binary default NULL,
  email char(200) default NULL,
  rights int(2) default NULL,
  group_id int(5) default NULL,
  feature int(2) default NULL,
  code char(20) default NULL,
  status tinyint(1) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

    