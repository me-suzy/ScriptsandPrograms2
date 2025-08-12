    ##############################################
   ### MySource ------------------------------###
  ##- Page Template Xtra ------ MySQL --------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## File: xtras/page/templates/form/database.sql
## Desc: SQL Statements to add this template to the web system
## $Source: /home/cvsroot/xtras/page/templates/form/mysql_web.sql,v $
## $Revision: 2.8 $
## $Author: sagland $
## $Date: 2003/01/30 03:12:49 $
#######################################################################

#---------------------------------------------------------------------#

CREATE TABLE xtra_page_template_form (
  pageid                MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY,
  parameters            LONGTEXT NOT NULL
);

CREATE TABLE xtra_page_template_form_log (
	logid            MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	pageid           MEDIUMINT(9) UNSIGNED,
	submission_time  DATETIME,
	answers          LONGTEXT,
	userid           MEDIUMINT(8) UNSIGNED,
	sessionid        CHAR(32)
);

