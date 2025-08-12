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
## File: xtras/page/templates/pullcontent/mysql_web_ChangeLog.sql
## Desc: upgrade from 1.1.1 to 1.1.2
## $Source: /home/cvsroot/xtras/page/templates/pullcontent/mysql_web_ChangeLog.sql,v $
## $Revision: 2.6 $
## $Author: gsherwood $
## $Date: 2003/03/20 03:37:26 $
#######################################################################

# Upgrade from 1.1.4 to 1.2
ALTER TABLE xtra_page_template_pullcontent ADD COLUMN submit_variables LONGTEXT;
ALTER TABLE xtra_page_template_pullcontent ADD COLUMN submit_type VARCHAR(255);
ALTER TABLE xtra_page_template_pullcontent ADD COLUMN restrict_links CHAR(1) DEFAULT '0';

# Upgrade from 1.1.2 to 1.1.4
ALTER TABLE xtra_page_template_pullcontent ADD COLUMN extra_querystring VARCHAR(255);

# Upgrade from 1.1.1 to 1.1.2
ALTER TABLE xtra_page_template_pullcontent ADD COLUMN title VARCHAR(255);

# Upgrade from 1.1.2 to 1.1.3
ALTER TABLE xtra_page_template_pullcontent ADD COLUMN subpage_emulation CHAR(1) DEFAULT '0';
