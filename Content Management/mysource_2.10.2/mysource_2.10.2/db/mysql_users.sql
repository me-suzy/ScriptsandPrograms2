    ##############################################
   ### MySource ------------------------------###
  ##- Database Table Definition File - MySQL--##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## File: db/user.sql
## Desc: Table structure definitions for the user database
## $Source: /home/cvsroot/mysource/db/mysql_users.sql,v $
## $Revision: 2.4 $
## $Author: sagland $
## $Date: 2003/01/30 03:12:13 $
#######################################################################


 ##################################################################################
# An account. These are used to log people in to the backend, frontend, everywhere
CREATE TABLE user (
  userid       MEDIUMINT    UNSIGNED NOT NULL AUTO_INCREMENT,
  login        VARCHAR(63)  NOT NULL,
  password     VARCHAR(127) NOT NULL,
  firstname    VARCHAR(127) NOT NULL,
  surname      VARCHAR(127) NOT NULL,
  email        VARCHAR(255) NOT NULL,
  mobile_no    VARCHAR(31)  NOT NULL,
  web_status   CHAR(1)      NOT NULL,
  created_date DATE         NOT NULL,
  expiry_date  DATE         NOT NULL,
  comments     TEXT         NOT NULL,
  PRIMARY KEY (userid),
  UNIQUE      (login),
  KEY         (password),
  KEY         (firstname),
  KEY         (surname),
  KEY         (email)
);


 #######################################################
# An organisaiton is a way of collecting users together
CREATE TABLE organisation (
  organisationid MEDIUMINT    UNSIGNED NOT NULL AUTO_INCREMENT,
  parentid       MEDIUMINT    UNSIGNED NOT NULL,
  name           VARCHAR(127) NOT NULL,
  description    VARCHAR(255) NOT NULL,
  p_address      VARCHAR(255) NOT NULL,
  p_suburb       VARCHAR(127) NOT NULL,
  p_state        VARCHAR(63)  NOT NULL,
  p_postcode     VARCHAR(31)  NOT NULL,
  p_countryid    CHAR(2)      NOT NULL,
  form           LONGTEXT,
  PRIMARY KEY    (organisationid),
  KEY            (parentid),
  KEY            (name)
);


 ####################
# Records an address
CREATE TABLE location (
  locationid  MEDIUMINT    UNSIGNED NOT NULL AUTO_INCREMENT,
  name        VARCHAR(255) DEFAULT '' NOT NULL,
  s_address   VARCHAR(255) NOT NULL,
  s_suburb    VARCHAR(127) NOT NULL,
  s_state     VARCHAR(63)  NOT NULL,
  s_postcode  VARCHAR(31)  NOT NULL,
  s_countryid CHAR(2)      NOT NULL,
  PRIMARY KEY (locationid),
  KEY         (s_address),
  KEY         (s_suburb),
  KEY         (s_state),
  KEY         (s_postcode),
  KEY         (s_countryid)
);


 ##################################################
# Link table between an organisaiton and a location
CREATE TABLE premises (
  organisationid MEDIUMINT   UNSIGNED NOT NULL,
  locationid     MEDIUMINT   UNSIGNED  NOT NULL,
  phone          VARCHAR(31) NOT NULL,
  fax            VARCHAR(31) NOT NULL,
  PRIMARY KEY (organisationid,locationid),
  KEY         (organisationid),
  KEY         (locationid),
  KEY         (phone),
  KEY         (fax)
);

 ##############################################
# Link between an user and a location
CREATE TABLE placement (
  userid       mediumint unsigned NOT NULL,
  locationid   mediumint unsigned NOT NULL,
  direct_phone varchar(31) NOT NULL,
  direct_fax   varchar(31) NOT NULL,
  PRIMARY KEY (userid,locationid),
  KEY         (userid),
  KEY         (locationid),
  KEY         (direct_phone),
  KEY         (direct_fax)
);

 ##############################################
# Link between an user and an organisation
CREATE TABLE affiliation (
  userid         mediumint     UNSIGNED NOT NULL,
  organisationid mediumint     UNSIGNED NOT NULL,
  title          VARCHAR(127)  NOT NULL,
  manager        enum('Y','N') DEFAULT 'N' NOT NULL,
  answers        LONGTEXT,
  PRIMARY KEY    (userid,organisationid),
  KEY            (userid),
  KEY            (organisationid),
  KEY            (title),
  KEY            (manager)
);

