CREATE TABLE attachment (
  id int(10) unsigned NOT NULL auto_increment,
  filename varchar(32) default NULL,
  filedata mediumblob,
  viewcount int(10) unsigned default NULL,
  parent int(10) unsigned default NULL,
  PRIMARY KEY  (id)
);

CREATE TABLE avatar (
  id int(10) unsigned NOT NULL default '0',
  filename varchar(255) default NULL,
  data mediumblob,
  PRIMARY KEY  (id)
);

CREATE TABLE board (
  id smallint(5) unsigned NOT NULL auto_increment,
  disporder smallint(5) unsigned default NULL,
  name varchar(250) default NULL,
  description varchar(250) default NULL,
  type char(1) default NULL,
  parent smallint(5) unsigned default NULL,
  lpost int(10) unsigned default NULL,
  lposter int(10) unsigned default NULL,
  lthread int(10) unsigned default NULL,
  lthreadpcount int(10) unsigned default NULL,
  PRIMARY KEY  (id)
);

INSERT INTO board VALUES (1, 1, 'Main Category', 'Main category description', '0', NULL, NULL, NULL, NULL, NULL);
INSERT INTO board VALUES (2, 1, 'Main Forum', 'Main forum description', '1', 1, NULL, NULL, NULL, NULL);

CREATE TABLE event (
  id int(10) unsigned NOT NULL auto_increment,
  author int(10) unsigned default NULL,
  date date default NULL,
  title varchar(64) default NULL,
  body text,
  public tinyint(1) unsigned default NULL,
  dsmilies tinyint(1) unsigned default NULL,
  ipaddress int(10) default NULL,
  PRIMARY KEY  (id)
);

CREATE TABLE member (
  id int(10) unsigned NOT NULL auto_increment,
  username varchar(16) default NULL,
  password varchar(32) default NULL,
  email varchar(128) default NULL,
  datejoined date default NULL,
  website varchar(128) default NULL,
  aim varchar(16) default NULL,
  icq varchar(24) default NULL,
  msn varchar(128) default NULL,
  yahoo varchar(50) default NULL,
  referrer varchar(16) default NULL,
  birthday date default NULL,
  bio varchar(255) default NULL,
  location varchar(48) default NULL,
  interests varchar(255) default NULL,
  occupation varchar(255) default NULL,
  avatarid int(10) unsigned default NULL,
  customavatar mediumblob,
  signature varchar(255) default NULL,
  allowmail tinyint(1) unsigned default NULL,
  invisible tinyint(1) unsigned default NULL,
  publicemail tinyint(1) unsigned default NULL,
  enablepms tinyint(1) unsigned default NULL,
  pmnotifya tinyint(1) unsigned default NULL,
  pmnotifyb tinyint(1) unsigned default NULL,
  threadview tinyint(2) unsigned default NULL,
  postsperpage tinyint(1) unsigned default NULL,
  threadsperpage tinyint(1) unsigned default NULL,
  weekstart tinyint(1) unsigned default NULL,
  timeoffset mediumint(8) default NULL,
  title varchar(16) default NULL,
  lastactive int(10) default NULL,
  loggedin tinyint(1) unsigned default NULL,
  postcount int(10) unsigned default NULL,
  lastlocation varchar(64) default NULL,
  lastrequest mediumtext,
  ipaddress int(10) default NULL,
  dst tinyint(1) unsigned default NULL,
  dstoffset smallint(5) unsigned default NULL,
  showsigs tinyint(1) unsigned default NULL,
  showavatars tinyint(1) unsigned default NULL,
  autologin tinyint(1) unsigned default NULL,
  buddylist text,
  ignorelist text,
  pmfolders text,
  usergroup tinyint(2) unsigned default NULL,
  PRIMARY KEY  (id)
);

CREATE TABLE pm (
  id int(10) unsigned NOT NULL auto_increment,
  owner int(10) unsigned default NULL,
  datetime int(10) default NULL,
  author int(10) unsigned default NULL,
  recipient int(10) unsigned default NULL,
  subject varchar(64) default NULL,
  body text,
  parent smallint(5) unsigned default NULL,
  ipaddress int(10) unsigned default NULL,
  icon tinyint(1) unsigned default NULL,
  dsmilies tinyint(1) unsigned default NULL,
  beenread tinyint(1) unsigned default NULL,
  readtime int(10) unsigned default NULL,
  tracking tinyint(1) unsigned default NULL,
  replied tinyint(1) unsigned default NULL,
  PRIMARY KEY  (id)
);

CREATE TABLE poll (
  id int(10) unsigned NOT NULL default '0',
  datetime int(10) default NULL,
  question varchar(255) default NULL,
  answers text,
  multiplechoices tinyint(1) unsigned default NULL,
  timeout smallint(5) unsigned default NULL,
  PRIMARY KEY  (id)
);

CREATE TABLE pollvote (
  id int(10) unsigned NOT NULL auto_increment,
  parent int(10) unsigned default NULL,
  owner int(10) unsigned default NULL,
  vote int(10) unsigned default NULL,
  votedate int(10) unsigned default NULL,
  PRIMARY KEY  (id)
);

CREATE TABLE post (
  id int(10) unsigned NOT NULL auto_increment,
  author int(10) unsigned default NULL,
  datetime_posted int(10) default NULL,
  datetime_edited int(10) default NULL,
  title varchar(64) default NULL,
  body text,
  parent int(10) unsigned default NULL,
  ipaddress int(10) default NULL,
  icon tinyint(1) unsigned default NULL,
  dsmilies tinyint(1) unsigned default NULL,
  PRIMARY KEY  (id),
  FULLTEXT KEY search (body)
);

CREATE TABLE request (
  id int(10) unsigned NOT NULL auto_increment,
  rkey int(8) unsigned default NULL,
  rtimestamp int(10) default NULL,
  PRIMARY KEY  (id)
);

CREATE TABLE session (
  id varchar(32) NOT NULL default '',
  lastactive int(10) default NULL,
  lastrequest mediumtext,
  lastlocation varchar(64) default NULL,
  ipaddress int(10) default NULL,
  PRIMARY KEY  (id)
);

CREATE TABLE thread (
  id int(10) unsigned NOT NULL auto_increment,
  title varchar(64) default NULL,
  description varchar(128) default NULL,
  parent smallint(5) unsigned default NULL,
  viewcount int(10) unsigned default NULL,
  lposter int(10) unsigned default NULL,
  icon tinyint(1) unsigned default NULL,
  author int(10) unsigned default NULL,
  poll tinyint(1) unsigned default NULL,
  open tinyint(1) unsigned default NULL,
  visible tinyint(1) unsigned default NULL,
  sticky tinyint(1) unsigned default NULL,
  notes mediumtext,
  PRIMARY KEY  (id)
);
