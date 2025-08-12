    ##############################################
   ### MySource ------------------------------###
  ##- Site Extension Ecommerce- MySQL --------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## Desc: SQL Statements to add this extension to the web system
## $Source: /home/cvsroot/xtras/page/templates/frames/mysql_web.sql,v $
## $Revision: 2.1 $
## $Author: csmith $
## $Date: 2002/01/21 21:39:42 $
#######################################################################

#---------------------------------------------------------------------#

CREATE TABLE xtra_page_template_frames (
	pageid      MEDIUMINT(9) UNSIGNED NOT NULL,
  PRIMARY KEY(pageid)
);
