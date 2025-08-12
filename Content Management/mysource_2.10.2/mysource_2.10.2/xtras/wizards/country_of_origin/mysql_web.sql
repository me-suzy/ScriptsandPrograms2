    ##############################################
   ### MySource ------------------------------###
  ##- MySource Reporting ------ MySQL --------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/wizards/country_of_origin/mysql_web.sql,v $
## $Revision: 1.3 $
## $Author: sagland $
## $Date: 2003/01/30 03:13:25 $
#######################################################################

#---------------------------------------------------------------------#


CREATE TABLE log_host_lookup (
	ip   VARCHAR(15) NOT NULL PRIMARY KEY,
	host VARCHAR(128)
);