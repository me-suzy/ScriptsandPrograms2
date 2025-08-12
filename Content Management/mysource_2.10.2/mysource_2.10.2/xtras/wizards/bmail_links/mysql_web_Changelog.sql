    ##############################################
   ### MySource ------------------------------###
  ##- MySource Reporting ------ MySQL --------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/wizards/bmail_links/mysql_web_Changelog.sql,v $
## $Revision: 1.1 $
## $Author: bvial $
## $Date: 2003/12/05 00:05:02 $
#######################################################################

# 1.2 to 1.3

ALTER TABLE xtra_wizard_bmail_links DROP PRIMARY KEY;

ALTER TABLE xtra_wizard_bmail_links CHANGE bmailid bulkmailid MEDIUMINT UNSIGNED NOT NULL PRIMARY KEY;