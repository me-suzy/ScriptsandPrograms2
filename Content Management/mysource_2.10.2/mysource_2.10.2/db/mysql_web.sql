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
## File: db/web.sql
## Desc: Table structure definitions for web database
## $Source: /home/cvsroot/mysource/db/mysql_web.sql,v $
## $Revision: 2.27 $
## $Author: dofford $
## $Date: 2004/03/10 01:24:23 $
#######################################################################

 ####################################################################
# Creates a table that stores wizard server jobs
CREATE TABLE wizard_server_job (
  jobid        MEDIUMINT(8) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  status       ENUM('N','R','F','A') NOT NULL DEFAULT 'N',
  userid       MEDIUMINT(8) UNSIGNED NOT NULL,
  last_updated DATETIME NOT NULL,
  job_type     VARCHAR(255) NOT NULL,
  parameters   LONGTEXT NOT NULL,
  caller_type  VARCHAR(255) NOT NULL,
  callerid     MEDIUMINT(8) NOT NULL,
  message      LONGTEXT NOT NULL,
  taskid       VARCHAR(255) NOT NULL
);

 ##################################################################
# Associates an entire URL with a serialized chucnk of information
# MySource must know what to do with this info. The info can include
# a site, page or fileid, or a site extension codename or whatever.
CREATE TABLE url_lookup (
  url       VARCHAR(255) NOT NULL,
  info      TEXT,
  PRIMARY KEY(url)
);

 
 ####################################################################
# A site is a basic MySource unit. It has a design and a collection
# of pages.
CREATE TABLE site (
  siteid             MEDIUMINT    UNSIGNED NOT NULL AUTO_INCREMENT,
  name               VARCHAR(127) DEFAULT '' NOT NULL,
  description        TEXT         NOT NULL,
  index_pageid       MEDIUMINT    UNSIGNED NOT NULL,
  not_found_pageid   MEDIUMINT    UNSIGNED NOT NULL,
  forbidden_pageid   MEDIUMINT    UNSIGNED NOT NULL,
  designid           MEDIUMINT    UNSIGNED NOT NULL,
  public             TINYINT      UNSIGNED NOT NULL,
  max_pages          MEDIUMINT    UNSIGNED NOT NULL,
  max_files          MEDIUMINT    UNSIGNED NOT NULL,
  max_file_bytes     MEDIUMINT    UNSIGNED NOT NULL,
  default_languages  VARCHAR(255) NOT NULL,
  default_charset    VARCHAR(255) NOT NULL,
  last_update        DATETIME     NOT NULL,
  subpage_auto_order VARCHAR(2)     NULL,
  PRIMARY KEY        (siteid),
  UNIQUE             (name),
  KEY                (index_pageid),
  KEY                (not_found_pageid),
  KEY                (public),
  KEY                (last_update)
);


 #################################################
# Associates a URL with a site. These are ordered
# on a per-site basis to give priority to preferred
# URLS.
CREATE TABLE site_url (
  siteid       MEDIUMINT    UNSIGNED NOT NULL,
  url          VARCHAR(255) NOT NULL,
  orderno      TINYINT      UNSIGNED NOT NULL,
  protocol     VARCHAR(10)  NOT NULL,
  PRIMARY KEY (url),
  KEY         (siteid),
  KEY         (orderno)
);


 ############################################################
# A link table bewteen site and use (in the user database)
# These users have "Administrator" status on the site
CREATE TABLE site_admin (
  userid      MEDIUMINT UNSIGNED NOT NULL,
  siteid      MEDIUMINT UNSIGNED NOT NULL,
  parameters  VARCHAR(255) DEFAULT '0',
  PRIMARY KEY (userid,siteid),
  KEY         (userid),
  KEY         (siteid)
);

 
 ############################################################
# A link table bewteen site and use (in the user database)
# These users have "Editor" status on the site
CREATE TABLE site_editor (
  userid      MEDIUMINT UNSIGNED NOT NULL,
  siteid      MEDIUMINT UNSIGNED NOT NULL,
  PRIMARY KEY (userid,siteid),
  KEY         (userid),
  KEY         (siteid)
);


 #####################################################
# Superuser may specify which page templates are allowed
# to be used on a particular site.
CREATE TABLE site_allowed_template (
  siteid      MEDIUMINT    UNSIGNED NOT NULL,
  template    VARCHAR(255) NOT NULL,
  PRIMARY KEY (siteid, template),
  KEY         (siteid)
);


 #####################################################
# Superuser may specify which site designs are allowed
# to be used on a particular site.
CREATE TABLE site_allowed_designid (
  siteid      MEDIUMINT UNSIGNED NOT NULL,
  designid    MEDIUMINT UNSIGNED NOT NULL,
  PRIMARY KEY (siteid, designid),
  KEY         (siteid)
);


 ########################################################
# Superuser may specify which site extensions are allowed
# to be used on a particular site.
CREATE TABLE site_allowed_extension (
  siteid      MEDIUMINT    UNSIGNED NOT NULL,
  extension   VARCHAR(255) NOT NULL,
  PRIMARY KEY (siteid, extension),
  KEY         (siteid)
);

 
 ############################################
# A link table between site and access_group
CREATE TABLE site_access_grant (
  siteid       MEDIUMINT UNSIGNED NOT NULL,
  groupid      MEDIUMINT UNSIGNED NOT NULL,
  PRIMARY KEY  (siteid,groupid),
  KEY          (siteid),
  KEY          (groupid)
);



 ######################################################
# A site design is basically a serialized PHP object
# defining the visual layout for a site
CREATE TABLE site_design (
  designid      MEDIUMINT(7) UNSIGNED NOT NULL AUTO_INCREMENT,
  name          VARCHAR(255) NOT NULL,
  design        LONGTEXT     NOT NULL,
  last_modified DATETIME     NOT NULL,
  creatorid     MEDIUMINT    UNSIGNED NOT NULL,
  modifierid    MEDIUMINT    UNSIGNED NOT NULL,
  public        TINYINT      UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY   (designid),
  UNIQUE        (name)
);

 ######################################################
# These are the customised versions of the site design
# these allow for changing colours, vars, etc
CREATE TABLE site_design_customisation (
  customisationid  varchar(255) NOT NULL,
  designid         MEDIUMINT(7) UNSIGNED NOT NULL,
  design           LONGTEXT     NOT NULL,
  PRIMARY KEY   (customisationid)
);




 ######################################################
# Pages are the atoms of the MySource web system
CREATE TABLE page (
  pageid             MEDIUMINT    UNSIGNED NOT NULL AUTO_INCREMENT,
  siteid             MEDIUMINT    UNSIGNED NOT NULL,
  template           VARCHAR(255) NOT NULL,
  short_name         VARCHAR(40)  NOT NULL,
  name               VARCHAR(127) NOT NULL,
  description        TEXT         NOT NULL,
  parentid           MEDIUMINT    UNSIGNED NOT NULL,
  orderno            SMALLINT     NOT NULL,
  keywords           TEXT         NOT NULL,
  next_action        DATETIME     NOT NULL,
  public             TINYINT      UNSIGNED NOT NULL,
  replaceid          MEDIUMINT    UNSIGNED NOT NULL, # Used by the SafeEdit function
  create_date        DATETIME     NOT NULL,
  default_languages  VARCHAR(255) NOT NULL,
  default_charset    VARCHAR(255) NOT NULL,
  last_update        DATETIME     NOT NULL,
  visible            TINYINT(3)   UNSIGNED DEFAULT 1 NOT NULL,
  designid           MEDIUMINT    UNSIGNED DEFAULT 0 NOT NULL,
  imageid            MEDIUMINT    UNSIGNED DEFAULT 0 NOT NULL, # FK to file table
  usessl             TINYINT      NOT NULL,
  level              SMALLINT     NOT NULL,
  page_notes         TEXT         NOT NULL,
  subpage_auto_order VARCHAR(2)     NULL,
  PRIMARY KEY (pageid),
  KEY         (siteid),
  KEY         (template),
  KEY         (short_name),
  KEY         (name),
  KEY         (parentid),
  KEY         (create_date),
  KEY         (last_update),
  KEY         (public),
  KEY         (next_action),
  KEY         (visible),
  KEY         (replaceid)
);

 ################################################
# Associates past and future statuses with a page
CREATE TABLE page_action (
  pageid         MEDIUMINT     UNSIGNED NOT NULL,
  date           DATETIME      NOT NULL,
  action_value   VARCHAR(255)  NOT NULL,
  action         VARCHAR(255)  NOT NULL,
  userid         MEDIUMINT(8)  UNSIGNED NOT NULL,
  log            VARCHAR(255)  NOT NULL,
  KEY(pageid),
  KEY(date),
  KEY(userid)
);



 #######################################################
# Associates a directory with a site. These are ordered
# on a per-page basis to give priority to preferred
# urls.
CREATE TABLE page_dir (
  pageid       MEDIUMINT    UNSIGNED NOT NULL,
  dir          VARCHAR(255) NOT NULL,
  orderno      TINYINT      UNSIGNED NOT NULL,
  PRIMARY KEY (pageid,dir),
  KEY         (dir),
  KEY         (orderno)
);


 ############################################################
# A link table bewteen page and user (in the user database)
# These users have "Editor" status on the page
CREATE TABLE page_editor (
  userid      MEDIUMINT UNSIGNED NOT NULL,
  pageid      MEDIUMINT UNSIGNED NOT NULL,
  readonly    TINYINT   UNSIGNED NOT NULL,
  PRIMARY KEY (userid,pageid),
  KEY         (pageid)
);


 ############################################################
# A link table bewteen page and user (in the user database)
# These users have "administrator" status on the page
CREATE TABLE page_admin (
  userid      MEDIUMINT UNSIGNED NOT NULL,
  pageid      MEDIUMINT UNSIGNED NOT NULL,
  parameters  VARCHAR(255) DEFAULT '0',
  PRIMARY KEY (userid,pageid),
  KEY         (pageid)
);



 ############################################
# A link table between page and access_group
CREATE TABLE page_access_grant (
  pageid      MEDIUMINT UNSIGNED NOT NULL,
  groupid     MEDIUMINT UNSIGNED NOT NULL,
  PRIMARY KEY (pageid,groupid),
  KEY         (pageid),
  KEY         (groupid)
);


 ########################################################
# Keeps a log of each time a page not found error occurs
CREATE TABLE log_page_not_found (
  pageid    MEDIUMINT UNSIGNED NOT NULL,
  sessionid CHAR(32)  NOT NULL,
  hit_time  DATETIME  NOT NULL,
  userid    MEDIUMINT UNSIGNED NOT NULL,
  referer   TEXT      NOT NULL,
  KEY       (pageid),
  KEY       (hit_time),
  KEY       (sessionid),
  KEY       (userid)
);


 ########################################
# Record for a file atatchment to a page
CREATE TABLE file (
  fileid      MEDIUMINT    UNSIGNED NOT NULL AUTO_INCREMENT,
  pageid      MEDIUMINT    UNSIGNED NOT NULL,
  filename    varchar(255) NOT NULL,
  description TEXT         NOT NULL,
  keywords    TEXT         NOT NULL,
  orderno     SMALLINT     UNSIGNED NOT NULL,
  visible     SET('Y','N') DEFAULT 'Y' NOT NULL,
  log_hits    SET('Y','N') DEFAULT 'N' NOT NULL,
  replaceid   INT          DEFAULT 0,
  PRIMARY KEY (fileid),
  UNIQUE      (pageid,filename),
  KEY         (pageid),
  KEY         (filename),
  KEY         (orderno),
  KEY         (visible)
);


 ###############################################
# An access group is a list of organisations
# and users that can be granted acess to things
CREATE TABLE access_group (
  groupid     MEDIUMINT    UNSIGNED NOT NULL AUTO_INCREMENT,
  siteid      MEDIUMINT    UNSIGNED NOT NULL,
  name        VARCHAR(255) NOT NULL,
  description TEXT         NOT NULL,
  PRIMARY KEY (groupid),
  UNIQUE      (siteid, name),
  KEY         (siteid),
  KEY         (name)
);


 ############################################
# Linking a user with an access group
CREATE TABLE access_group_user_membership (
  userid      MEDIUMINT UNSIGNED NOT NULL,
  groupid     MEDIUMINT UNSIGNED NOT NULL,
  PRIMARY KEY (userid,groupid),
  KEY         (userid),
  KEY         (groupid)
);

 ##############################################
# Linking an organisation with an access group
CREATE TABLE access_group_organisation_membership (
  organisationid MEDIUMINT UNSIGNED NOT NULL,
  groupid        MEDIUMINT UNSIGNED NOT NULL,
  PRIMARY KEY    (organisationid,groupid),
  KEY            (organisationid),
  KEY            (groupid)
);


 ######################################################
# These are the metadata fields for pages and sites
CREATE TABLE meta_data (
  siteid      MEDIUMINT UNSIGNED NOT NULL,
  pageid      MEDIUMINT UNSIGNED NOT NULL,
  group_name  VARCHAR(100) DEFAULT '' NOT NULL,
  tag_name    VARCHAR(100) DEFAULT '' NOT NULL,
  tag_scheme  VARCHAR(100) DEFAULT '' NOT NULL,
  tag_lang    VARCHAR(100) DEFAULT '' NOT NULL,
  value_name  VARCHAR(100) DEFAULT '' NOT NULL,
  content     LONGTEXT DEFAULT '' NOT NULL,
  PRIMARY KEY (siteid, pageid, group_name, tag_name, value_name)
);

CREATE TABLE mysource_help (
  helpid mediumint(9) NOT NULL auto_increment,
  feature_name varchar(127) NOT NULL default '',
  feature_help_text longtext NOT NULL,
  PRIMARY KEY  (helpid),
  UNIQUE KEY feature_name (feature_name)
) TYPE=MyISAM;

INSERT INTO mysource_help (feature_name, feature_help_text) VALUES ('Name', 'Defines the name of the page.');
INSERT INTO mysource_help (feature_name, feature_help_text) VALUES ('Currently', 'Shows you the current status of this page.');
INSERT INTO mysource_help (feature_name, feature_help_text) VALUES ('Site Name', 'Name of this site. This name can appear in the frontend, but it\'s used in the backend mainly.\r\n<hr>\r\nName dieser Site. Dieser Name kann im Frontend angezeigt werden, hauptsächlich wird er aber im Backend benützt.');
INSERT INTO mysource_help (feature_name, feature_help_text) VALUES ('Web', 'The web tab.');
INSERT INTO mysource_help (feature_name, feature_help_text) VALUES ('Find:(login/email/name)', 'Type a users name, login name or email address and click the magnifying glass to find the user.\r\n\r\n<hr>\r\n\r\nUm einen User zu finden, dessen Namen, Login-Namen oder Email-Adresse eingeben. Ein Klick auf die Lupe started die Suche.');
INSERT INTO mysource_help (feature_name, feature_help_text) VALUES ('Create New Pages?', 'To add new subpages to the current page (or site, if you want to create main pages), type the names of the new pages in to the field. One page per line. \r\nYou can select the desired template of the new pages here. But you can also change the template of every single page later.\r\n\r\n<hr>\r\n\r\nErstellt neue Subpages in der aktuellen Page. Tippen die Namen der neuen Pages in das grosse Eingabefeld (eine Page pro Linie!).\r\nIm Auswahlfeld können Sie das Template der neuen Pages bestimmen. Dieses kann aber nachher bei jeder Page einzeln noch geändert werden.');
INSERT INTO mysource_help (feature_name, feature_help_text) VALUES ('News from mysource.squiz.net', 'What about an english course?');

