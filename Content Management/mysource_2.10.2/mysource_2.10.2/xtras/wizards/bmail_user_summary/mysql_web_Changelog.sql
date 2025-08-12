    ##############################################
   ### MySource ------------------------------###
  ##- MySource Reporting ------ MySQL --------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/wizards/bmail_user_summary/mysql_web_Changelog.sql,v $
## $Revision: 1.1 $
## $Author: bvial $
## $Date: 2003/12/05 00:05:03 $
#######################################################################

# 1.1 to 1.3

ALTER TABLE xtra_wizard_bmail_user_summary DROP PRIMARY KEY;

ALTER TABLE xtra_wizard_bmail_user_summary CHANGE bmailid bulkmailid MEDIUMINT UNSIGNED NOT NULL PRIMARY KEY;