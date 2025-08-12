    ##############################################
   ### MySource ------------------------------###
  ##- Page Template Xtra ------ MySQL --------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## File: xtras/page/templates/form/database.sql
## Desc: SQL Statements to add this template to the web system
## $Source: /home/cvsroot/xtras/site/design_areas/email_page_update/mysql_web.sql,v $
## $Revision: 1.1 $
## $Author: mmcintyre $
## $Date: 2003/08/20 01:50:28 $
#######################################################################

#---------------------------------------------------------------------#


CREATE TABLE xtra_page_template_email_page_update (
  pageid                MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY,
  parameters            LONGTEXT NOT NULL
);