    ##############################################
   ### MySource ------------------------------###
  ##- MySource Reporting ------ MySQL --------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/statistics/detailed/mysql_web.sql,v $
## $Revision: 1.1 $
## $Author: gsherwood $
## $Date: 2003/02/17 03:24:10 $
#######################################################################

#---------------------------------------------------------------------#

 ########################################################
# In this table we log every session that is begun in the
# system.
CREATE TABLE log_session (
  sessionid   CHAR(32)     NOT NULL,
  start_time  DATETIME     NOT NULL,
  user_agent  VARCHAR(64)  NOT NULL,
  remote_addr VARCHAR(15)  NOT NULL,
  remote_host VARCHAR(127) NOT NULL,
  referer     TEXT         NOT NULL,
  PRIMARY KEY (sessionid),
  KEY         (start_time),
  KEY         (user_agent),
  KEY         (remote_addr),
  KEY         (remote_host)
);


 ###########################################
# Keeps a log of each time a page is viewed
CREATE TABLE log_page_hit (
  pageid    MEDIUMINT UNSIGNED NOT NULL,
  sessionid CHAR(32)  NOT NULL,
  hit_time  DATETIME  NOT NULL,
  userid    MEDIUMINT UNSIGNED NOT NULL,
  KEY       (pageid),
  KEY       (hit_time),
  KEY       (sessionid),
  KEY       (userid)
);


 ###########################################
# Log ever time a file is downloaded
CREATE TABLE log_file_hit (
  fileid      MEDIUMINT(9) UNSIGNED DEFAULT '0' NOT NULL,
  hit_time    DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
  sessionid   CHAR(32) DEFAULT '' NOT NULL,
  userid      MEDIUMINT(9) UNSIGNED DEFAULT '0' NOT NULL,
  KEY         (fileid),
  KEY         (hit_time),
  KEY         (sessionid),
  KEY         (userid)
);