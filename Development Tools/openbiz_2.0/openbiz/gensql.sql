/*
SQLyog v4.06 RC1
Host - 4.1.12a-nt : Database - demo
*********************************************************************
Server version : 4.1.12a-nt
*/


create database if not exists `demo`;

USE `demo`;

/*Table structure for table `attendee` */

drop table if exists `attendee`;

CREATE TABLE `attendee` (
  `SYSID` varchar(20) NOT NULL default '',
  `LASTNAME` varchar(30) NOT NULL default '',
  `FIRSTNAME` varchar(30) NOT NULL default '',
  `OTHERNAME` varchar(50) default NULL,
  `PHONE` varchar(20) default NULL,
  `EMAIL` varchar(30) default NULL,
  `ADDRESS` varchar(50) default NULL,
  `DESCRIPTION` varchar(255) default NULL,
  `TYPE` varchar(20) default NULL,
  `UPORG_ID` varchar(20) default NULL,
  `CHILD_FLAG` char(1) default NULL,
  PRIMARY KEY  (`SYSID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `attendee` */

insert into `attendee` values ('ATD_1','Liu','Michael','','','mliu@yahoo.com','','','Individual','',''),('ATD_2','Houston','Alan','Houston','6501111001','ahouston@yahoo.com','','','Individual','',''),('ATD_6','Smith','Andy','','','asmith@yahoo.com','','','Individual','',''),('ATD_4','Lee','Pete','','4081112100','plee@yahoo.com','','','Individual','',''),('ATD_5','Lau','Frank','劉備','4081112100','flau@yahoo.com','','','Individual','',''),('ATD_7','Monk','Steve','','','smonk@yahoo.com','','','Individual','',''),('ATD_8','Louis','Jeff','','','jlouis@yahoo.com','','','Individual','','');

/*Table structure for table `calattds` */

drop table if exists `calattds`;

CREATE TABLE `calattds` (
  `SYSID` varchar(20) NOT NULL default '',
  `LNAME` varchar(20) NOT NULL default '',
  `FNAME` varchar(20) NOT NULL default '',
  `COMPANY` varchar(30) default NULL,
  `JOB_TITLE` varchar(30) default NULL,
  `CONTACT` varchar(50) default NULL,
  PRIMARY KEY  (`SYSID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `calattds` */

insert into `calattds` values ('ATD_1','Jordan','Micheal','TNT','Sport Commentor','mjordan@tnt.com'),('ATD_2','Johnson','Magic','','','mjohnson@coach.com'),('ATD_3','Bonds','Barry','','','');

/*Table structure for table `calevts` */

drop table if exists `calevts`;

CREATE TABLE `calevts` (
  `SYSID` varchar(20) NOT NULL default '',
  `SUBJECT` varchar(20) default NULL,
  `TYPE` varchar(20) default NULL,
  `LOCATION` varchar(50) default NULL,
  `NOTES` varchar(200) default NULL,
  `STARTTIME` datetime default NULL,
  `ENDTIME` datetime default NULL,
  `REPEATFLAG` char(1) default 'N',
  `REPEATCYCLE` varchar(20) default NULL,
  `REPEATEND` datetime default NULL,
  PRIMARY KEY  (`SYSID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `calevts` */

insert into `calevts` values ('CAL_1','Group Meeting','Meeting','Room 101','Marketing weekly group meeting','2005-06-01 08:00:00','2005-06-01 10:30:00','','','1999-11-30 00:00:00'),('CAL_2','BT Conf call','Appointment','Hall 201','Conference call with BT sales','2005-06-01 09:00:00','2005-06-01 12:30:00','','','1999-11-30 00:00:00'),('CAL_14','Test week event','Appointment','Room 300','Test repeated event','2005-06-01 14:38:34','2005-06-01 16:38:34','Y','Every week','2005-08-04 16:38:35'),('CAL_16','Test day repeat','Meeting','Cafe','','2005-06-06 12:15:00','2005-06-06 13:15:00','Y','Every day','2005-06-10 23:44:16'),('CAL_17','Test month repeat','Meeting','Conference 300','Monthly meeting','2005-06-07 09:10:28','2005-06-07 10:10:28','Y','Every month','2005-10-01 00:10:28'),('CAL_18','Test year repeat','Birthday','','Mike birthday','2005-06-10 00:14:34','2005-06-10 00:15:34','Y','Every year','2008-09-25 00:14:34');

/*Table structure for table `calevts_attds` */

drop table if exists `calevts_attds`;

CREATE TABLE `calevts_attds` (
  `SYSID` int(20) NOT NULL auto_increment,
  `EVT_ID` varchar(20) NOT NULL default '',
  `ATD_ID` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`SYSID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `calevts_attds` */

insert into `calevts_attds` values (1,'CAL_1','ATD_1'),(3,'CAL_1','ATD_3'),(4,'CAL_2','ATD_1'),(8,'CAL_2','ATD_2');

/*Table structure for table `events` */

drop table if exists `events`;

CREATE TABLE `events` (
  `SYSID` varchar(20) NOT NULL default '',
  `NAME` varchar(100) NOT NULL default '',
  `HOST` varchar(50) NOT NULL default '',
  `START` datetime default NULL,
  `END` datetime default NULL,
  `LOCATION` varchar(50) default NULL,
  `DESCRIPTION` varchar(255) default NULL,
  PRIMARY KEY  (`SYSID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `events` */

insert into `events` values ('EVT_2','NCCAF 2004 Badminton','NCCAF','','','San Jose State Univ',''),('EVT_3','NCCAF 2004 Tennis','NCCAF','2004-06-20 10:33:51','2004-06-20 13:33:51','San Jose State Univ',''),('EVT_4','NCCAF 2004 Soccer','NCCAF','','','San Jose State Univ',''),('EVT_5','NCCAF 2004 Basketball','NCCAF','2004-11-30 09:00:00','2004-11-30 16:00:00','San Jose State Univ',''),('EVT_6','NCCAF 2004 Table Tennis','NCCAF','2004-06-19 09:00:00','2004-06-19 21:00:00','San Jose State Univ','');

/*Table structure for table `ob_sysids` */

drop table if exists `ob_sysids`;

CREATE TABLE `ob_sysids` (
  `TABLENAME` char(20) NOT NULL default '',
  `PREFIX` char(10) default NULL,
  `IDBODY` int(11) default NULL,
  PRIMARY KEY  (`TABLENAME`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `ob_sysids` */

insert into `ob_sysids` values ('events','EVT',100),('regist','REG',141),('calevts','CAL',30),('ob_objects','OBJ',24),('ob_users','USR',1),('matches','MTC',223),('schedule','SCHD',289),('sponsors','SPSR',6),('calattds','ATD',5),('attendee','ATD',9);

/*Table structure for table `ob_users` */

drop table if exists `ob_users`;

CREATE TABLE `ob_users` (
  `SYSID` varchar(20) NOT NULL default '',
  `USERID` varchar(15) NOT NULL default '',
  `PASSWORD` varchar(15) default NULL,
  PRIMARY KEY  (`SYSID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `ob_users` */

insert into `ob_users` values ('USR_1','admin','admin'),('USR_2','bill','bill');

/*Table structure for table `regist` */

drop table if exists `regist`;

CREATE TABLE `regist` (
  `SYSID` varchar(20) NOT NULL default '',
  `ATTENDEE_ID` varchar(20) NOT NULL default '',
  `EVENT_ID` varchar(20) NOT NULL default '',
  `FEE` decimal(10,0) default NULL,
  `ONSCHD_FLAG` char(1) default NULL,
  PRIMARY KEY  (`SYSID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `regist` */

insert into `regist` values ('REG_103','ATD_2','EVT_4',15,''),('REG_102','ATD_7','EVT_4',25,''),('REG_101','ATD_1','EVT_4',20,''),('REG_130','ATD_8','EVT_3',15,''),('REG_11','ATD_4','EVT_5',25,''),('REG_139','ATD_5','EVT_3',0,'Y'),('REG_138','ATD_7','EVT_3',(null),''),('REG_137','ATD_2','EVT_3',(null),''),('REG_136','ATD_1','EVT_3',(null),''),('REG_100','ATD_4','EVT_4',(null),''),('REG_61','ATD_8','EVT_4',(null),''),('REG_95','ATD_5','EVT_4',(null),''),('REG_104','ATD_4','EVT_3',(null),'');

/*Table structure for table `sponsors` */

drop table if exists `sponsors`;

CREATE TABLE `sponsors` (
  `SYSID` varchar(20) NOT NULL default '',
  `NAME` varchar(50) NOT NULL default '',
  `CONTACT` varchar(100) default NULL,
  `ADDRESS` varchar(100) default NULL,
  `DONATION` decimal(10,0) default NULL,
  `EXPENSE` decimal(10,0) default NULL,
  `COMMENTS` varchar(200) default NULL,
  PRIMARY KEY  (`SYSID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `sponsors` */

insert into `sponsors` values ('SPSR_2','Tiger Balm','','',30000,25000,''),('SPSR_3','San Jose Mecury Daily','','',50000,45000,''),('SPSR_4','Starbucks Coffee','','',15000,20000,''),('SPSR_5','Midas Auto','','',25000,20000,''),('SPSR_6','Steves Creeks Ford','','',10000,12000,'');
