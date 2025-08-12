    ##############################################
   ### MySource ------------------------------###
  ##- MySource Reporting ------ MySQL --------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/wizards/bmail_summary/mysql_web.sql,v $
## $Revision: 1.2 $
## $Author: bvial $
## $Date: 2003/09/23 02:07:40 $
#######################################################################

#---------------------------------------------------------------------#


CREATE TABLE xtra_wizard_bmail_summary (
	bulkmailid MEDIUMINT UNSIGNED NOT NULL PRIMARY KEY,
	summary LONGTEXT NOT NULL
);