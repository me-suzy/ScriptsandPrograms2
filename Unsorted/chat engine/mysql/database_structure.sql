#
# Table structure for table 'chat_images'
#

CREATE TABLE chat_images (
  id bigint(20) NOT NULL auto_increment,
  filename varchar(255) NOT NULL default '',
  command varchar(255) NOT NULL default '',
  userlevels mediumtext NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY id (id)
) TYPE=MyISAM;

#
# Table structure for table 'chat_session_ids'
#

CREATE TABLE chat_session_ids (
  session_id varchar(25) NOT NULL default '',
  login varchar(50) NOT NULL default '',
  last_action datetime NOT NULL default '0000-00-00 00:00:00',
  status_online int(11) NOT NULL default '0',
  lastlogin datetime NOT NULL default '0000-00-00 00:00:00',
  IP varchar(255) NOT NULL default '',
  id bigint(20) NOT NULL auto_increment,
  userlevel int(11) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Table structure for table 'chat_stats'
#

CREATE TABLE chat_stats (
  nick varchar(17) NOT NULL default '',
  logins int(11) NOT NULL default '0',
  kicks int(11) NOT NULL default '0',
  chars bigint(20) NOT NULL default '0',
  words bigint(20) NOT NULL default '0',
  linez int(11) NOT NULL default '0',
  icons int(11) NOT NULL default '0',
  started_at datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (nick)
) TYPE=MyISAM;

#
# Table structure for table 'chat_text_db'
#

CREATE TABLE chat_text_db (
  saidby varchar(20) NOT NULL default '',
  saidto varchar(20) NOT NULL default '',
  status int(11) NOT NULL default '0',
  text varchar(255) NOT NULL default '',
  id bigint(20) NOT NULL auto_increment,
  date datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Table structure for table 'chat_users'
#

CREATE TABLE chat_users (
  login varchar(17) NOT NULL default '',
  password varchar(50) NOT NULL default '',
  e_mail varchar(150) NOT NULL default '0',
  status int(1) NOT NULL default '0',
  realname varchar(150) NOT NULL default '',
  last_date date NOT NULL default '0000-00-00',
  color varchar(25) NOT NULL default '',
  banned tinyint(4) NOT NULL default '0',
  img varchar(100) NOT NULL default '',
  PRIMARY KEY  (login)
) TYPE=MyISAM;

#
# Table structure for table 'ipbans'
#

CREATE TABLE ipbans (
  ip varchar(255) NOT NULL default '',
  id bigint(20) NOT NULL auto_increment,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
