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
## File: xtras/page/templates/redirect/database.sql
## Desc: SQL Statements to add this template to the web system
## $Source: /home/cvsroot/xtras/page/templates/pullcontent/mysql_web.sql,v $
## $Revision: 2.6 $
## $Author: gsherwood $
## $Date: 2003/03/20 03:37:26 $
#######################################################################
# Portions Copyright 2001, University Communications Services
#                          The University of Western Australia
#
# Contact: cwis@uwa.edu.au
#######################################################################


#---------------------------------------------------------------------#

CREATE TABLE xtra_page_template_pullcontent (
   pageid             MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY,
   content_pageid     MEDIUMINT(9) UNSIGNED,
   title              VARCHAR(255),
   subpage_emulation  CHAR(1) DEFAULT '0',
   extra_querystring  VARCHAR(255),
   submit_variables   LONGTEXT,
   submit_type        VARCHAR(255),
   restrict_links     CHAR(1) DEFAULT '0'
);
