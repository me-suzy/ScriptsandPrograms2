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
## $Source: /home/cvsroot/xtras/page/templates/sub_page/mysql_web_ChangeLog.sql,v $
## $Revision: 1.11 $
## $Author: sagland $
## $Date: 2003/01/30 03:12:54 $
#######################################################################

#---------------------------------------------------------------------#
ALTER TABLE xtra_page_template_sub_page ADD COLUMN popup_window CHAR(1) DEFAULT '0';
ALTER TABLE xtra_page_template_sub_page ADD COLUMN popup_window_menu CHAR(1) DEFAULT '0';
ALTER TABLE xtra_page_template_sub_page ADD COLUMN popup_window_tool CHAR(1) DEFAULT '0';
ALTER TABLE xtra_page_template_sub_page ADD COLUMN popup_window_width  VARCHAR(5);
ALTER TABLE xtra_page_template_sub_page ADD COLUMN popup_window_height VARCHAR(5);
ALTER TABLE xtra_page_template_sub_page ADD COLUMN popup_window_resize CHAR(1) DEFAULT '0';
ALTER TABLE xtra_page_template_sub_page ADD COLUMN popup_window_status CHAR(1) DEFAULT '0';
ALTER TABLE xtra_page_template_sub_page ADD COLUMN popup_window_scroll CHAR(1) DEFAULT '0';
ALTER TABLE xtra_page_template_sub_page ADD COLUMN popup_window_location CHAR(1) DEFAULT '0';

 #########################
# MySource 2.2.0 - 2.3.0

ALTER TABLE xtra_page_template_sub_page ADD COLUMN use_anchors ENUM('0', '1')  DEFAULT '0';


# Sub Page 1.0 - Sub Page 1.1
ALTER TABLE xtra_page_template_sub_page ADD COLUMN page_copy LONGTEXT;
ALTER TABLE xtra_page_template_sub_page ADD COLUMN link_colour CHAR(6)  DEFAULT '';
ALTER TABLE xtra_page_template_sub_page MODIFY number_per_row SMALLINT UNSIGNED DEFAULT '1';
ALTER TABLE xtra_page_template_sub_page DROP textwrap;
ALTER TABLE xtra_page_template_sub_page DROP longname;
ALTER TABLE xtra_page_template_sub_page DROP showdesc;
ALTER TABLE xtra_page_template_sub_page DROP showthumb;
ALTER TABLE xtra_page_template_sub_page DROP shortname;

# Sub Page 1.0
ALTER TABLE xtra_page_template_sub_page CHANGE COLUMN divider horizontal_divider CHAR(1) DEFAULT '0';
ALTER TABLE xtra_page_template_sub_page ADD COLUMN vertical_divider CHAR(1) DEFAULT '0';
ALTER TABLE xtra_page_template_sub_page ADD COLUMN number_per_row INT DEFAULT 1;

 #########################
# MySource 2.0.2 - 2.2.0
ALTER TABLE xtra_page_template_sub_page ADD COLUMN textwrap char(1);

ALTER TABLE xtra_page_template_sub_page ADD COLUMN position char(1) DEFAULT 'b';
ALTER TABLE xtra_page_template_sub_page ADD COLUMN divider char(1) DEFAULT '0';
ALTER TABLE xtra_page_template_sub_page ADD COLUMN number_per_page INT DEFAULT 0;

