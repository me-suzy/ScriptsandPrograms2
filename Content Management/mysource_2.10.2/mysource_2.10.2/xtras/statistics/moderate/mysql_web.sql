    ##############################################
   ### MySource ------------------------------###
  ##- MySource Reporting ------ MySQL --------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/statistics/moderate/mysql_web.sql,v $
## $Revision: 1.3 $
## $Author: brobertson $
## $Date: 2004/01/20 16:27:42 $
#######################################################################

#---------------------------------------------------------------------#

CREATE TABLE log_page_hit (
  pageid mediumint(8) unsigned NOT NULL default '0',
  hit_time date NOT NULL default '0000-00-00',
  hits mediumint(8) unsigned NOT NULL default '0',
  visits mediumint(8) unsigned NOT NULL default '0',
  users mediumint(8) unsigned NOT NULL default '0',
  KEY pageid (pageid),
  KEY hit_time (hit_time)
);

CREATE TABLE log_file_hit (
  fileid    MEDIUMINT UNSIGNED NOT NULL,
  hit_time  DATE  NOT NULL,
  hits      MEDIUMINT UNSIGNED DEFAULT 0 NOT NULL,
  visits    MEDIUMINT UNSIGNED DEFAULT 0 NOT NULL,
  users     MEDIUMINT UNSIGNED DEFAULT 0 NOT NULL,
  KEY fileid (fileid),
  KEY hit_time (hit_time)
);
