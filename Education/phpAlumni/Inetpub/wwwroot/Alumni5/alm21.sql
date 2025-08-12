# phpMyAdmin MySQL-Dump
# version 2.2.0
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# Host: localhost
# Generation Time: July 11, 2002, 2:26 pm
# Server version: 3.23.49
# PHP Version: 4.2.1
# Database : `kabwebs_com`
# --------------------------------------------------------

#
# Table structure for table `alm_alumni`
#

DROP TABLE IF EXISTS alm_alumni;
CREATE TABLE alm_alumni (
  RecordNum int(11) NOT NULL auto_increment,
  YearGrad varchar(4) default NULL,
  LastName varchar(30) default NULL,
  FirstName varchar(20) default NULL,
  MaidenName varchar(30) default NULL,
  MaritalStatus varchar(10) default NULL,
  Spouse varchar(20) default NULL,
  Children varchar(200) default NULL,
  HomeAddress varchar(60) default NULL,
  HomeCity varchar(40) default NULL,
  HomeState char(2) default NULL,
  Phone varchar(13) default NULL,
  EmailAddy varchar(60) default NULL,
  Website varchar(60) default NULL,
  Profession varchar(40) default NULL,
  Employed varchar(40) default NULL,
  About longtext,
  Picture text,
  LoginID varchar(15) default NULL,
  Password varchar(15) default NULL,
  LevelID int(11) default '1',
  PRIMARY KEY  (RecordNum,RecordNum)
) TYPE=MyISAM;

#
# Dumping data for table `alm_alumni`
#

INSERT INTO alm_alumni (RecordNum, YearGrad, LastName, FirstName, MaidenName, MaritalStatus, Spouse, Children, HomeAddress, HomeCity, HomeState, Phone, EmailAddy, Website, Profession, Employed, About, Picture, LoginID, Password, LevelID) VALUES (1,'1979','Bucci','Kenneth','None','Single','None','None','927 Nursery Street','Fogelsville','PA','111-222-2222','admin@kabwebs.com','http://www.kabwebs.com','Developer','',NULL,'http://www.kabwebs.com/images/kab.jpg','kbucci','pw',1);
INSERT INTO alm_alumni (RecordNum, YearGrad, LastName, FirstName, MaidenName, MaritalStatus, Spouse, Children, HomeAddress, HomeCity, HomeState, Phone, EmailAddy, Website, Profession, Employed, About, Picture, LoginID, Password, LevelID) VALUES ('',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'admin','pw',2);
INSERT INTO alm_alumni (RecordNum, YearGrad, LastName, FirstName, MaidenName, MaritalStatus, Spouse, Children, HomeAddress, HomeCity, HomeState, Phone, EmailAddy, Website, Profession, Employed, About, Picture, LoginID, Password, LevelID) VALUES (13,'1985','Person','Younger','People','Married','SomeoneEsle','Child1, Child2','123 Here','There','DE','222-333-4444','someone@home.com','http://www.yoursite.com','A Job','Work','Me?','http://www.kabwebs.com/images/kabwebswb.gif','me','pw',1);
# --------------------------------------------------------

#
# Table structure for table `alm_mstatus`
#

DROP TABLE IF EXISTS alm_mstatus;
CREATE TABLE alm_mstatus (
  STATUS char(10) NOT NULL default '',
  PRIMARY KEY  (STATUS)
) TYPE=MyISAM;

#
# Dumping data for table `alm_mstatus`
#

INSERT INTO alm_mstatus (STATUS) VALUES ('Married');
INSERT INTO alm_mstatus (STATUS) VALUES ('Single');
# --------------------------------------------------------

#
# Table structure for table `alm_states`
#

DROP TABLE IF EXISTS alm_states;
CREATE TABLE alm_states (
  state char(2) NOT NULL default '',
  PRIMARY KEY  (state)
) TYPE=MyISAM;

#
# Dumping data for table `alm_states`
#

INSERT INTO alm_states (state) VALUES ('AK');
INSERT INTO alm_states (state) VALUES ('AL');
INSERT INTO alm_states (state) VALUES ('AR');
INSERT INTO alm_states (state) VALUES ('AZ');
INSERT INTO alm_states (state) VALUES ('CA');
INSERT INTO alm_states (state) VALUES ('CO');
INSERT INTO alm_states (state) VALUES ('CT');
INSERT INTO alm_states (state) VALUES ('DC');
INSERT INTO alm_states (state) VALUES ('DE');
INSERT INTO alm_states (state) VALUES ('FL');
INSERT INTO alm_states (state) VALUES ('GA');
INSERT INTO alm_states (state) VALUES ('HI');
INSERT INTO alm_states (state) VALUES ('IA');
INSERT INTO alm_states (state) VALUES ('ID');
INSERT INTO alm_states (state) VALUES ('IL');
INSERT INTO alm_states (state) VALUES ('IN');
INSERT INTO alm_states (state) VALUES ('KS');
INSERT INTO alm_states (state) VALUES ('KY');
INSERT INTO alm_states (state) VALUES ('LA');
INSERT INTO alm_states (state) VALUES ('MA');
INSERT INTO alm_states (state) VALUES ('MD');
INSERT INTO alm_states (state) VALUES ('ME');
INSERT INTO alm_states (state) VALUES ('MI');
INSERT INTO alm_states (state) VALUES ('MN');
INSERT INTO alm_states (state) VALUES ('MO');
INSERT INTO alm_states (state) VALUES ('MS');
INSERT INTO alm_states (state) VALUES ('MT');
INSERT INTO alm_states (state) VALUES ('NC');
INSERT INTO alm_states (state) VALUES ('ND');
INSERT INTO alm_states (state) VALUES ('NE');
INSERT INTO alm_states (state) VALUES ('NH');
INSERT INTO alm_states (state) VALUES ('NJ');
INSERT INTO alm_states (state) VALUES ('NM');
INSERT INTO alm_states (state) VALUES ('NV');
INSERT INTO alm_states (state) VALUES ('NY');
INSERT INTO alm_states (state) VALUES ('OH');
INSERT INTO alm_states (state) VALUES ('OK');
INSERT INTO alm_states (state) VALUES ('OR');
INSERT INTO alm_states (state) VALUES ('PA');
INSERT INTO alm_states (state) VALUES ('RI');
INSERT INTO alm_states (state) VALUES ('SC');
INSERT INTO alm_states (state) VALUES ('SD');
INSERT INTO alm_states (state) VALUES ('TN');
INSERT INTO alm_states (state) VALUES ('TX');
INSERT INTO alm_states (state) VALUES ('UT');
INSERT INTO alm_states (state) VALUES ('VA');
INSERT INTO alm_states (state) VALUES ('VT');
INSERT INTO alm_states (state) VALUES ('WA');
INSERT INTO alm_states (state) VALUES ('WI');
INSERT INTO alm_states (state) VALUES ('WV');
INSERT INTO alm_states (state) VALUES ('WY');

