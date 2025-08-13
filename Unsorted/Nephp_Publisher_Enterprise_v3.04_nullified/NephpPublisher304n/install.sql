# phpMyAdmin MySQL-Dump
# version 2.2.2
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# Host: localhost
# Generation Time: Aug 16, 2002 at 12:46 AM
# Server version: 4.00.01
# PHP Version: 4.2.1
# Database : `nnet2002`
# --------------------------------------------------------

#
# Table structure for table `nnet_articles`
#

CREATE TABLE nnet_articles (
  nnet_aid int(11) unsigned NOT NULL auto_increment,
  nnet_cid int(11) NOT NULL default '0',
  nnet_title varchar(255) NOT NULL default '',
  nnet_desc text NOT NULL,
  nnet_data text NOT NULL,
  nnet_uid int(11) NOT NULL default '0',
  nnet_time int(11) NOT NULL default '0',
  nnet_feature int(11) NOT NULL default '0',
  nnet_views int(11) NOT NULL default '0',
  nnet_trate int(11) NOT NULL default '0',
  nnet_nrate int(11) NOT NULL default '0',
  nnet_rrate int(11) NOT NULL default '0',
  nnet_approval int(1) NOT NULL default '0',
  nnet_parent int(11) NOT NULL default '0',
  PRIMARY KEY  (nnet_aid),
  KEY nnet_aid (nnet_aid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `nnet_category`
#

CREATE TABLE nnet_category (
  nnet_cid int(11) unsigned NOT NULL auto_increment,
  nnet_name char(255) NOT NULL default '',
  nnet_desc char(255) NOT NULL default '',
  nnet_icon char(255) NOT NULL default '',
  nnet_categories char(255) NOT NULL default '',
  nnet_listings char(255) NOT NULL default '',
  nnet_features char(255) NOT NULL default '',
  nnet_child int(11) NOT NULL default '0',
  nnet_parent int(11) NOT NULL default '0',
  nnet_flimit int(2) NOT NULL default '0',
  nnet_fmax int(2) NOT NULL default '0',
  nnet_nav char(255) NOT NULL default '',
  nnet_date int(11) NOT NULL default '0',
  PRIMARY KEY  (nnet_cid),
  KEY nnet_cid (nnet_cid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `nnet_polls`
#

CREATE TABLE nnet_polls (
  nnet_aid int(11) NOT NULL default '0',
  nnet_cid int(11) NOT NULL default '0',
  nnet_ques char(255) NOT NULL default '',
  nnet_ans1 char(255) NOT NULL default '',
  nnet_ans2 char(255) NOT NULL default '',
  nnet_ans3 char(255) NOT NULL default '',
  nnet_ans4 char(255) NOT NULL default '',
  nnet_nans1 int(11) NOT NULL default '0',
  nnet_nans2 int(11) NOT NULL default '0',
  nnet_nans3 int(11) NOT NULL default '0',
  nnet_nans4 int(11) NOT NULL default '0',
  PRIMARY KEY  (nnet_aid),
  KEY nnet_aid (nnet_aid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `nnet_reviews`
#

CREATE TABLE nnet_reviews (
  nnet_rid int(11) unsigned NOT NULL auto_increment,
  nnet_aid int(11) NOT NULL default '0',
  nnet_cid int(11) NOT NULL default '0',
  nnet_title varchar(255) NOT NULL default '',
  nnet_poster varchar(255) NOT NULL default '',
  nnet_rate int(2) NOT NULL default '0',
  nnet_isup int(1) NOT NULL default '0',
  nnet_msg text NOT NULL,
  nnet_date varchar(255) NOT NULL default '',
  PRIMARY KEY  (nnet_rid),
  UNIQUE KEY nnet_rid (nnet_rid),
  KEY nnet_rid_2 (nnet_rid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `nnet_session`
#

CREATE TABLE nnet_session (
  session char(255) NOT NULL default '',
  uid int(11) NOT NULL default '0',
  status char(255) NOT NULL default 'guest',
  usr char(255) NOT NULL default '',
  time int(11) NOT NULL default '0',
  PRIMARY KEY  (session),
  KEY session (session)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `nnet_users`
#

CREATE TABLE nnet_users (
  nnet_uid int(11) unsigned NOT NULL auto_increment,
  nnet_user char(255) NOT NULL default '',
  nnet_pass char(255) NOT NULL default '',
  nnet_uname char(255) NOT NULL default '',
  nnet_email char(255) NOT NULL default '',
  nnet_type tinyint(1) NOT NULL default '0',
  nnet_able int(1) NOT NULL default '0',
  nnet_posts int(11) NOT NULL default '0',
  nnet_date int(11) NOT NULL default '0',
  F1026542829 char(255) NOT NULL default '',
  F1026552984 char(255) NOT NULL default '',
  F1026552993 char(255) NOT NULL default '',
  F1026553032 char(255) NOT NULL default '',
  F1026553055 char(255) NOT NULL default '',
  F1026553069 char(255) NOT NULL default '',
  F1026553148 char(255) NOT NULL default '',
  F1029024617 char(255) NOT NULL default '',
  PRIMARY KEY  (nnet_uid),
  KEY nnet_uid (nnet_uid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `nnet_users_fields`
#

CREATE TABLE nnet_users_fields (
  nnet_fid char(255) NOT NULL default '',
  nnet_fname char(255) NOT NULL default '',
  nnet_type int(11) NOT NULL default '0',
  nnet_order tinyint(11) NOT NULL default '0',
  PRIMARY KEY  (nnet_fid),
  KEY nnet_fid (nnet_fid)
) TYPE=MyISAM;

