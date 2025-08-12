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
## $Source: /home/cvsroot/xtras/page/templates/sub_page/mysql_web.sql,v $
## $Revision: 1.14 $
## $Author: dwong $
## $Date: 2003/04/24 06:09:11 $
#######################################################################

#---------------------------------------------------------------------#

CREATE TABLE xtra_page_template_sub_page (
  pageid                MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY,
  parameters            LONGTEXT NOT NULL
);
