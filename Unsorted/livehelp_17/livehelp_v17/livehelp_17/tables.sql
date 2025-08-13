CREATE TABLE livehelp_channels (
  id int(10) NOT NULL auto_increment,
  user_id int(10) NOT NULL default '0',
  statusof char(1) NOT NULL default '',
  startdate bigint(8) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

CREATE TABLE livehelp_config (
  version float(3,1) NOT NULL default '1.7',
  site_title varchar(100) NOT NULL default '',
  opening text NOT NULL,
  needname varchar(30) NOT NULL default '',
  leaveamessage varchar(10) NOT NULL default '',
  messageemail varchar(60) NOT NULL default '',
  use_flush varchar(10) NOT NULL default 'YES',
  alert_visit char(1) NOT NULL default 'N',
  membernum int(8) NOT NULL default '0',
  offset int(5) NOT NULL default '0'
) TYPE=MyISAM;


INSERT INTO livehelp_config VALUES ('1.7', 'Live Help!', '<blockquote>Welcome to Live Help. <br> Please Enter in your Name at the bottom of this screen to begin.</blockquote>', 'YES', 'YES', 'youremail@here.com', 'YES', 'N', 2, 0);

CREATE TABLE livehelp_departments (
  recno int(5) NOT NULL auto_increment,
  nameof varchar(30) NOT NULL default '',
  onlineimage varchar(100) NOT NULL default '',
  offlineimage varchar(100) NOT NULL default '',
  requirename char(1) NOT NULL default '',
  messageemail varchar(60) NOT NULL default '',
  PRIMARY KEY  (recno)
) TYPE=MyISAM;

INSERT INTO livehelp_departments VALUES (1, 'default', 'online.gif', 'leavemessage.gif', '', 'youremail');

CREATE TABLE livehelp_messages (
  id_num int(10) NOT NULL auto_increment,
  message text NOT NULL,
  channel int(10) NOT NULL default '0',
  timeof bigint(14) NOT NULL default '0',
  saidfrom int(10) NOT NULL default '0',
  saidto int(10) NOT NULL default '0',
  PRIMARY KEY  (id_num),
  KEY channel (channel),
  KEY timeof (timeof)
) TYPE=MyISAM;

CREATE TABLE livehelp_operator_channels (
  id int(10) NOT NULL auto_increment,
  user_id int(10) NOT NULL default '0',
  channel int(10) NOT NULL default '0',
  userid int(10) NOT NULL default '0',
  statusof char(1) NOT NULL default '',
  startdate bigint(8) NOT NULL default '0',
  bgcolor varchar(10) NOT NULL default '000000',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

INSERT INTO livehelp_operator_channels VALUES (1, 1, 1, 4, '', 0, '000000');

CREATE TABLE livehelp_quick (
  id int(10) NOT NULL auto_increment,
  name varchar(50) NOT NULL default '',
  typeof varchar(30) NOT NULL default '',
  message text NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

CREATE TABLE livehelp_users (
  user_id int(10) NOT NULL auto_increment,
  lastaction timestamp(14) NOT NULL,
  username varchar(30) NOT NULL default '',
  password varchar(60) NOT NULL default '',
  isonline char(1) NOT NULL default '',
  isoperator char(1) NOT NULL default 'N',
  onchannel int(10) NOT NULL default '0',
  isadmin char(1) NOT NULL default 'N',
  department int(5) NOT NULL default '0',
  identity varchar(255) NOT NULL default '',
  status varchar(30) NOT NULL default '',
  isnamed char(1) NOT NULL default 'N',
  showedup bigint(14) default NULL,
  email varchar(60) NOT NULL default '',
  PRIMARY KEY  (user_id)
) TYPE=MyISAM;

INSERT INTO livehelp_users VALUES (1, 20030510015911, 'admin', 'admin', 'N', 'Y', 0, 'Y', 0, 'mwzWL4ZkZs', 'chat', 'Y', NULL, 'your@yemail.com');

CREATE TABLE livehelp_visit_track (
  recno int(10) NOT NULL auto_increment,
  id varchar(30) NOT NULL default '0',
  location varchar(100) NOT NULL default '',
  page bigint(14) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  whendone timestamp(14) NOT NULL,
  referrer varchar(100) NOT NULL default '',
  PRIMARY KEY  (recno),
  KEY id (id)
) TYPE=MyISAM;