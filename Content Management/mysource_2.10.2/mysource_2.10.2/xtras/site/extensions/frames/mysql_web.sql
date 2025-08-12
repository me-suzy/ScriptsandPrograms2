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
## File: xtras/page/templates/standard/database.sql
## Desc: SQL Statements to add this template to the web system
## $Source: /home/cvsroot/xtras/site/extensions/frames/mysql_web.sql,v $
## $Revision: 2.2 $
## $Author: csmith $
## $Date: 2003/08/18 04:20:32 $
#######################################################################

#---------------------------------------------------------------------#

CREATE TABLE xtra_site_extension_frames (
  siteid         MEDIUMINT(9) UNSIGNED NOT NULL,
  frameset_text  TEXT,      # the html that forms the frameset
  index_pageid   MEDIUMINT(9) UNSIGNED NOT NULL, # the page to use as the index page, 
                                                 # because the index page is going to be taken by this template
  index_frameid  MEDIUMINT(9) UNSIGNED NOT NULL, # the frame that will contain the main content for the page
  PRIMARY KEY(siteid)
);

CREATE TABLE xtra_site_extension_frames_frame (
  siteid    MEDIUMINT(7) UNSIGNED NOT NULL,
  frameid   SMALLINT     NOT NULL DEFAULT 0,
  name      VARCHAR(255) NOT NULL,
  designid  MEDIUMINT    UNSIGNED NOT NULL,
  PRIMARY KEY (siteid, frameid),
  UNIQUE (siteid, name)
);
