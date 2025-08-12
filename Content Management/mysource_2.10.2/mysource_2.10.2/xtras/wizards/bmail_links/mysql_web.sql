    ##############################################
   ### MySource ------------------------------###
  ##- MySource Reporting ------ MySQL --------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/wizards/bmail_links/mysql_web.sql,v $
## $Revision: 1.2 $
## $Author: bvial $
## $Date: 2003/09/23 02:07:39 $
#######################################################################

#---------------------------------------------------------------------#


CREATE TABLE xtra_wizard_bmail_links (
	bulkmailid MEDIUMINT UNSIGNED NOT NULL PRIMARY KEY,
	summary LONGTEXT NOT NULL
);