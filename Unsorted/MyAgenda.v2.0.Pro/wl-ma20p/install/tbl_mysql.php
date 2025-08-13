<?php
#############################################################################
# myAgenda v2.0																#
# =============																#
# Copyright (C) 2003  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#############################################################################

$tbl[] = "DROP TABLE IF EXISTS AGENDA_CONFIGS";
$tbl[] = "
CREATE TABLE AGENDA_CONFIGS (
  ADMIN_USERNAME varchar(20) NOT NULL default '',
  ADMIN_PASSWORD varchar(20) NOT NULL default '',
  PROG_NAME varchar(20) NOT NULL default '',
  PROG_URL varchar(255) NOT NULL default '',
  PROG_PATH varchar(255) NOT NULL default '',
  PROG_EMAIL varchar(150) NOT NULL default '',
  PROG_LANG varchar(20) NOT NULL default '',
  WEEK_START char(1) NOT NULL default '',
  TIME_OFFSET varchar(10) NOT NULL default '',
  USER_TIMEOUT varchar(5) NOT NULL default '',
  TABLES_PREFIX varchar(10) NOT NULL default ''
) TYPE=MyISAM";

$tbl[] = "DROP TABLE IF EXISTS AGENDA_REMINDERS";
$tbl[] = "
CREATE TABLE AGENDA_REMINDERS (
  ID varchar(20) NOT NULL default '',
  UID varchar(20) NOT NULL default '',
  TYPE tinyint(6) unsigned NOT NULL default '0',
  ADVANCE tinyint(3) unsigned NOT NULL default '0',
  REPEAT tinyint(3) unsigned NOT NULL default '0',
  REMINDER varchar(255) NOT NULL default '',
  DATE int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (ID),
  KEY UID (UID,ID)
) TYPE=MyISAM";

$tbl[] = "DROP TABLE IF EXISTS AGENDA_USERS";
$tbl[] = "
CREATE TABLE AGENDA_USERS (
  UID varchar(20) NOT NULL default '',
  NAME varchar(50) NOT NULL default '',
  SURNAME varchar(50) NOT NULL default '',
  EMAIL varchar(150) NOT NULL default '',
  USERNAME varchar(16) NOT NULL default '',
  PASSWORD varchar(32) NOT NULL default '',
  APPROVED enum('Y','N') NOT NULL default 'Y',
  SID varchar(32) NOT NULL default '',
  LASTACCESS int(10) unsigned NOT NULL default '0',
  DATE int(10) unsigned NOT NULL default '0',
  UNIQUE KEY USERNAME (USERNAME),
  KEY UID (UID)
) TYPE=MyISAM";

$tbl[] = "DROP TABLE IF EXISTS PW_REQUEST";
$tbl[] = "
CREATE TABLE PW_REQUEST (
  UID int(5) unsigned NOT NULL default '0',
  DATE date NOT NULL default '0000-00-00',
  KEY UID (UID)
) TYPE=MyISAM";

$tbl[] = "DROP TABLE IF EXISTS USER_APPROVALS";
$tbl[] = "
CREATE TABLE USER_APPROVALS (
  UID varchar(20) NOT NULL default '',
  KEY UID (UID)
) TYPE=MyISAM";
?>