# Use this if are doing manual installation
# then use install.php?step=2 to setup your first account
#
# Remember u will need to change names of the tables
# manually, then reflect the names in config.php 
#--------------------------------------------------------


#
# Table structure for table 'adlogs'
#

CREATE TABLE adlogs (
  logid bigint(20) NOT NULL auto_increment,
  uid varchar(15) NOT NULL DEFAULT '0' ,
  acctimedate varchar(100) NOT NULL DEFAULT '' ,
  status tinyint(1) NOT NULL DEFAULT '1' ,
  msg text NOT NULL DEFAULT '' ,
  PRIMARY KEY (logid),
  KEY logid (logid),
  KEY uid (uid, logid),
  KEY status (status)
);


#
# Table structure for table 'albumlist'
#

CREATE TABLE albumlist (
  aname varchar(100) NOT NULL DEFAULT '' ,
  aid bigint(20) NOT NULL auto_increment,
  uid varchar(15) NOT NULL DEFAULT '0' ,
  password varchar(50) DEFAULT '0' ,
  private tinyint(1) DEFAULT '0' ,
  amsg varchar(99) ,
  cdate int(8) NOT NULL DEFAULT '0' ,
  sused bigint(20) NOT NULL DEFAULT '0' ,
  pused mediumint(9) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (aid),
  KEY aid (aid),
  KEY uid (uid, aid),
  KEY cdate (cdate)
);


#
# Table structure for table 'config'
#

CREATE TABLE config (
  fname varchar(25) NOT NULL DEFAULT '' ,
  fnvalue mediumtext ,
  PRIMARY KEY (fname),
  KEY fname (fname)
);


#
# Table structure for table 'ecards'
#

CREATE TABLE ecards (
  ecid bigint(20) NOT NULL auto_increment,
  uid varchar(15) NOT NULL DEFAULT '0' ,
  rec_name varchar(100) NOT NULL DEFAULT '0' ,
  rec_email varchar(150) NOT NULL DEFAULT '0' ,
  colors varchar(20) NOT NULL DEFAULT '0' ,
  message text NOT NULL DEFAULT '' ,
  pic varchar(20) NOT NULL DEFAULT '0' ,
  music varchar(10) DEFAULT '1' ,
  makedate varchar(8) NOT NULL DEFAULT '0' ,
  notify tinyint(1) NOT NULL DEFAULT '0' ,
  code varchar(100) NOT NULL DEFAULT '0' ,
  mailsent tinyint(1) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (ecid),
  KEY ecid (ecid),
  KEY uid (uid, ecid),
  KEY mailsent (mailsent)
);


#
# Table structure for table 'pictures'
#

CREATE TABLE pictures (
  pid bigint(20) NOT NULL auto_increment,
  aid int(11) NOT NULL DEFAULT '0' ,
  pname varchar(100) NOT NULL DEFAULT '0' ,
  pindex smallint(6) NOT NULL DEFAULT '0' ,
  pmsg text ,
  o_used mediumint(9) NOT NULL DEFAULT '0' ,
  i_used mediumint(9) NOT NULL DEFAULT '0' ,
  t_used mediumint(9) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (pid),
  KEY pid (pid),
  KEY aid (aid, pid),
  KEY pindex (pindex)
);


#
# Table structure for table 'publist'
#

CREATE TABLE publist (
  pubid bigint(20) NOT NULL auto_increment,
  name varchar(100) ,
  email varchar(200) NOT NULL DEFAULT '0' ,
  userval varchar(15) ,
  PRIMARY KEY (pubid),
  KEY pubid (pubid)
);


#
# Table structure for table 'reminders'
#

CREATE TABLE reminders (
  rid bigint(20) NOT NULL auto_increment,
  uid varchar(15) NOT NULL DEFAULT '0' ,
  event varchar(100) NOT NULL DEFAULT '0' ,
  message text ,
  estatus smallint(1) NOT NULL DEFAULT '1' ,
  date_year smallint(4) NOT NULL DEFAULT '0' ,
  date_month tinyint(2) NOT NULL DEFAULT '0' ,
  date_day tinyint(2) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (rid),
  KEY rid (rid),
  KEY uid (uid, rid)
);


#
# Table structure for table 'userinfo'
#

CREATE TABLE userinfo (
  uid varchar(15) NOT NULL DEFAULT '0' ,
  password varchar(50) NOT NULL DEFAULT '' ,
  uname varchar(100) NOT NULL DEFAULT '' ,
  email varchar(70) NOT NULL DEFAULT '' ,
  country varchar(40) ,
  sessiontime bigint(20) NOT NULL DEFAULT '0' ,
  lastip varchar(100) ,
  admin tinyint(1) DEFAULT '0' ,
  status tinyint(1) NOT NULL DEFAULT '0' ,
  prefs varchar(10) ,
  profile longtext NOT NULL DEFAULT '' ,
  adddate varchar(8) NOT NULL DEFAULT '0' ,
  limits varchar(15) DEFAULT '0' ,
  sused bigint(20) DEFAULT '0' ,
  pused mediumint(9) DEFAULT '0' ,
  langcode char(3) ,
  validity VARCHAR(8) DEFAULT '0' NOT NULL ,
  logintime smallint(5) unsigned DEFAULT '0' ,
  PRIMARY KEY (uid),
  KEY uid (uid),
  UNIQUE email (email),
  KEY email_2 (email),
  KEY status (status),
  KEY adddate (adddate)
);

#
# Table structure for table 'userprofile'
#

CREATE TABLE userprofile (
  fid tinyint(4) NOT NULL auto_increment,
  type varchar(10) NOT NULL DEFAULT '0' ,
  tname varchar(100) NOT NULL DEFAULT '0' ,
  topts text NOT NULL DEFAULT '' ,
  dvalue varchar(50) ,
  findex smallint(6) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (fid),
  KEY fid (fid),
  KEY findex (findex)
);


#
# Table structure for table 'userwait'
#

CREATE TABLE userwait (
  uid varchar(15) NOT NULL DEFAULT '0' ,
  code varchar(100) NOT NULL DEFAULT '0' ,
  adddate varchar(8) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (uid),
  KEY uid (uid)
);
