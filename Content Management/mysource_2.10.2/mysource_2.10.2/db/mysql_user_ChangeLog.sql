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
## File: db/user_ChangeLog.sql
## Desc: Listing of changes to the user database structure for each release
## $Source: /home/cvsroot/mysource/db/mysql_user_ChangeLog.sql,v $
## $Revision: 2.5 $
## $Author: sagland $
## $Date: 2003/01/30 03:12:13 $
#######################################################################


 ##########################
# 0.9.5 BETA to 2.0.2 BETA
ALTER TABLE affiliation ADD COLUMN answers LONGTEXT;
ALTER TABLE organisation ADD COLUMN form LONGTEXT;

 ##########################
# 2.0.2 BETA to 2.4.0
ALTER TABLE location ADD COLUMN name VARCHAR(255) DEFAULT '' NOT NULL;
ALTER TABLE user ADD COLUMN created_date date NOT NULL AFTER web_status;
