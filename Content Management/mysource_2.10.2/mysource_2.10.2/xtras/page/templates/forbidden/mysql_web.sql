    ##############################################
   ### MySource ------------------------------###
  ##- Page Template Xtra ------ MySQL --------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## $Source: /home/cvsroot/xtras/page/templates/forbidden/mysql_web.sql,v $
## $Revision: 1.1 $
## $Author: bo $
## $Date: 2002/05/08 07:04:13 $
#######################################################################

#---------------------------------------------------------------------#

CREATE TABLE xtra_page_template_forbidden (
	pageid          MEDIUMINT    UNSIGNED NOT NULL PRIMARY KEY,
	parameters      LONGTEXT     NOT NULL # Serialized array of EVERY option
);