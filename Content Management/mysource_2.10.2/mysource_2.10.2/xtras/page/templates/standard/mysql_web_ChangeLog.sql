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
## $Source: /home/cvsroot/xtras/page/templates/standard/mysql_web_ChangeLog.sql,v $
## $Revision: 2.2 $
## $Author: achadszinow $
## $Date: 2003/05/22 00:24:31 $
#######################################################################

#---------------------------------------------------------------------#

# Version 1.2.0 is parameters sets
# MUST RUN UPGRADE SCRIPT

ALTER TABLE xtra_page_template_standard MODIFY bodycopy LONGTEXT;