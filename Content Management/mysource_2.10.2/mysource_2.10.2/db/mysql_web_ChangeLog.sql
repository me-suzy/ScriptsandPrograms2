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
## File: db/web_ChangeLog.sql
## Desc: Listing of changes to the web database structure for each release
## $Source: /home/cvsroot/mysource/db/mysql_web_ChangeLog.sql,v $
## $Revision: 2.27.2.1 $
## $Author: achadszinow $
## $Date: 2004/04/20 00:18:26 $
#######################################################################


 ############
# 2.8.0 -> 2.10.0
ALTER TABLE page ADD COLUMN subpage_auto_order VARCHAR(2) NULL;
ALTER TABLE site ADD COLUMN subpage_auto_order VARCHAR(2) NULL;
ALTER TABLE page ADD COLUMN page_notes TEXT NOT NULL;

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

ALTER TABLE file ADD COLUMN replaceid INT DEFAULT 0;

ALTER TABLE page ADD COLUMN level SMALLINT NOT NULL;
ALTER TABLE page CHANGE `ssl` usessl TINYINT DEFAULT 0;

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

# logging is now done using statistics reporters
# each of which will have its own mysql_web.sql file
DROP TABLE log_session;
DROP TABLE log_page_hit;
DROP TABLE log_file_hit;


 ####################
# 2.2.0 to 2.4.0
ALTER TABLE site_url ADD COLUMN protocol VARCHAR(10) NOT NULL;
ALTER TABLE page ADD COLUMN ssl TINYINT NOT NULL;


ALTER TABLE site ADD COLUMN forbidden_pageid MEDIUMINT UNSIGNED NOT NULL;
ALTER TABLE page ADD COLUMN imageid MEDIUMINT UNSIGNED DEFAULT 0 NOT NULL;
ALTER TABLE file ADD COLUMN log_hits SET('Y','N') DEFAULT 'N' NOT NULL AFTER visible;

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

ALTER TABLE page        CHANGE next_status_change next_action DATETIME NOT NULL;
ALTER TABLE page_status RENAME page_action;
ALTER TABLE page_action CHANGE status action_value VARCHAR(255) NOT NULL;
ALTER TABLE page_action ADD COLUMN action VARCHAR(255) NOT NULL;
ALTER TABLE page_action ADD INDEX (ACTION);
ALTER TABLE page_action DROP PRIMARY KEY;
ALTER TABLE page_action ADD  PRIMARY KEY (pageid,date,action);
UPDATE      page_action SET action='status';

CREATE TABLE page_admin (
  userid      MEDIUMINT UNSIGNED NOT NULL,
  pageid      MEDIUMINT UNSIGNED NOT NULL,
  PRIMARY KEY (userid,pageid),
  KEY         (pageid)
);

ALTER TABLE page_action DROP PRIMARY KEY;
ALTER TABLE page_action CHANGE userid userid MEDIUMINT(8) UNSIGNED NOT NULL;
ALTER TABLE page_action ADD KEY(pageid);
ALTER TABLE page_action ADD KEY(date);
ALTER TABLE page_action ADD KEY(userid);

ALTER TABLE page_admin ADD COLUMN parameters VARCHAR(255) DEFAULT '0';
ALTER TABLE site_admin ADD COLUMN parameters VARCHAR(255) DEFAULT '0';


 ####################
# 2.0.2 BETA to 2.2.0
ALTER TABLE page ADD COLUMN designid MEDIUMINT UNSIGNED DEFAULT 0 NOT NULL;
ALTER TABLE page CHANGE COLUMN short_name short_name VARCHAR(40) NOT NULL;
ALTER TABLE page_editor ADD COLUMN readonly TINYINT UNSIGNED DEFAULT 0 NOT NULL;

 ###########################
# 0.9.5 BETA to 2.0.2 BETA
CREATE TABLE site_design_customisation (
  customisationid  varchar(255) NOT NULL,
  designid         MEDIUMINT(7) UNSIGNED NOT NULL,
  design           LONGTEXT     NOT NULL,
  PRIMARY KEY   (customisationid)
);
ALTER TABLE site DROP COLUMN design;
ALTER TABLE site_design ADD COLUMN public TINYINT UNSIGNED NOT NULL DEFAULT 1;


ALTER TABLE site ADD COLUMN not_found_pageid MEDIUMINT UNSIGNED NOT NULL;
ALTER TABLE page ADD COLUMN visible TINYINT(3) UNSIGNED DEFAULT 1 NOT NULL;

ALTER TABLE page DROP COLUMN status;
ALTER TABLE page ADD COLUMN next_status_change datetime NOT NULL;
ALTER TABLE page ADD KEY (next_status_change);

CREATE TABLE page_status (
	pageid         MEDIUMINT     UNSIGNED NOT NULL,
	date           DATETIME      NOT NULL,
	status         CHAR(1)       NOT NULL,
	userid         MEDIUMINT(8)  UNSIGNED NOT NULL,
	log            VARCHAR(255)  NOT NULL,
	PRIMARY KEY(pageid,date),
	KEY(userid),
	KEY(status)
);



 ###########################
# 0.9.4 BETA to 0.9.5 BETA
ALTER TABLE site ADD default_languages VARCHAR(255) NOT NULL;
ALTER TABLE site ADD default_charset   VARCHAR(255) NOT NULL;

ALTER TABLE page ADD default_languages VARCHAR(255) NOT NULL;
ALTER TABLE page ADD default_charset   VARCHAR(255) NOT NULL;

 ##########################
# 0.9.1 BETA to 0.9.2 BETA
ALTER TABLE log_session ADD referer TEXT NOT NULL;

CREATE TABLE url_lookup (
	url       VARCHAR(255) NOT NULL,
	info      TEXT,
	PRIMARY KEY(url)
);

CREATE TABLE page_dir (
  pageid       MEDIUMINT    UNSIGNED NOT NULL,
  dir          VARCHAR(255) NOT NULL,
  orderno      TINYINT      UNSIGNED NOT NULL,
  PRIMARY KEY (pageid,dir),
  KEY         (dir),
  KEY         (orderno)
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

