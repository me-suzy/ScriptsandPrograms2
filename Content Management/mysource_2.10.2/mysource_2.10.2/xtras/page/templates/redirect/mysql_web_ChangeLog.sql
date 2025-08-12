    ##############################################
   ### MySource ------------------------------###
  ##- Database Table Definition File - MySQL--##
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
## File: xtras/page/templates/database_ChangeLog.sql
## Desc: upgrade from redirect page template 1.2.1 to 1.3, with support for CSV export
## $Source: /home/cvsroot/xtras/page/templates/redirect/mysql_web_ChangeLog.sql,v $
## $Revision: 2.3 $
## $Author: achadszinow $
## $Date: 2003/05/21 02:06:37 $
#######################################################################

# Change from redirect page template 1.4 to 1.5 (Now parameters) 
# USE UPGRADE SCRIPT

# Change from redirect page template 1.3 to 1.4
ALTER TABLE xtra_page_template_redirect ADD column extra_url VARCHAR(255);

# Change from redirect page template 1.2.1 to 1.3
ALTER TABLE xtra_page_template_redirect ADD column window_options TEXT;
