    ##############################################
   ### MySource ------------------------------###
  ##- MySource Reporting ------ MySQL --------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/statistics/simple/mysql_web.sql,v $
## $Revision: 1.1 $
## $Author: gsherwood $
## $Date: 2003/02/02 23:12:25 $
#######################################################################

#---------------------------------------------------------------------#


ALTER TABLE page ADD COLUMN hits MEDIUMINT(8) UNSIGNED DEFAULT 0;
ALTER TABLE page ADD COLUMN hits_running_total MEDIUMINT(8) UNSIGNED DEFAULT 0;

ALTER TABLE file ADD COLUMN hits MEDIUMINT(8) UNSIGNED DEFAULT 0;
ALTER TABLE file ADD COLUMN hits_running_total MEDIUMINT(8) UNSIGNED DEFAULT 0;