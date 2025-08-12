    #############################################
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
## Desc: upgrade from form page template 1.2.1 to 1.3, with support for CSV export
## $Source: /home/cvsroot/xtras/page/templates/form/mysql_web_ChangeLog.sql,v $
## $Revision: 2.9 $
## $Author: sagland $
## $Date: 2003/01/30 03:12:49 $
#######################################################################

# 1.3.9 to 2.0
# RUN UPGRADE SCRIPT in the edit directory.

# 1.3.7 to 1.3.8
ALTER TABLE xtra_page_template_form ADD column recipient_email_body TEXT;
ALTER TABLE xtra_page_template_form ADD column receipt_email_body TEXT;

# 1.3.6 to 1.3.7
ALTER TABLE xtra_page_template_form ADD column hide_results TINYINT DEFAULT 0;
ALTER TABLE xtra_page_template_form ADD column formelements_keyword TEXT;

# 1.3.5 to 1.3.6
ALTER TABLE xtra_page_template_form ADD column receipt_email TINYINT DEFAULT 0;

# 1.3.4 to 1.3.5
ALTER TABLE xtra_page_template_form ADD column selective_emails TEXT;

# 1.3.2 to 1.3.3
ALTER TABLE xtra_page_template_form ADD column paginate TINYINT DEFAULT 0;
ALTER TABLE xtra_page_template_form ADD column back_button_text VARCHAR(128);

# 1.3 to 1.3.1
ALTER TABLE xtra_page_template_form DROP column log_form_submission;
ALTER TABLE xtra_page_template_form ADD column log_form_submission TINYINT DEFAULT 0;

# 1.2.1 to 1.3
ALTER TABLE xtra_page_template_form ADD column log_form_submission TINYINT;

CREATE TABLE xtra_page_template_form_log (
	logid            MEDIUMINT(9) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
	pageid           MEDIUMINT(9) UNSIGNED,
	submission_time  DATETIME,
	answers          LONGTEXT,
	userid           MEDIUMINT(8) UNSIGNED,
	sessionid        CHAR(32)
);

