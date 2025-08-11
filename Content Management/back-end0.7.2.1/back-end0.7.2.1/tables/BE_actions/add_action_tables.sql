#
# add_action_tables.sql
#
# Add tables for Back-End Actions
#
# @package   Back-End on phpSlash
# @author    Peter Bojanic
# @copyright Copyright (C) 2003 OpenConcept Consulting
# @version   $Id: add_action_tables.sql,v 1.20 2005/06/11 21:17:55 maparent Exp $


# This file is part of Back-End.
#
# Back-End is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# Back-End is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Back-End; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA


# Table descriptions
#
# be_contact - any type of contact with a name and coordinates
# be_contactType - the type of be_contact (business, MP/MLA, citizen)
# be_target - defines the target of an action by associating a
#   be_contact with a be_action
#
# be_action - an lobying action directed at a set of be_target OR
#   against a target to be specified/looked up by the participants
#   such as an MP/MLA
# be_actionText - language-specific text attributes for a be_action
# be_actionType - the type of be_action (fax, email, etc.)
# be_action2section - defines associations between a be_action and
#   multiple be_sections rows (see Schema note 1)
# be_action2contact - defines contacts who have participated in a
#   be_action, their custom message (if applicable) and their own
#   target (if applicable)


# Schema notes
#
# 1. CUPEs initial requirements for Actions permits anonymous users
# to participate (i.e. no registration). To simplify the initial
# implementation, we will not provide registration or login
# features for Actions. For the initial implementation we will end
# up storing MULTIPLE ROWS in be_contact for a real person, should
# they participate in multiple actions.
#
# 2. Many Back-End tables make use of phpslash db_sequence for
# generating unique sequence numbers. This is particularly important
# if you need to insert rows into associated tables using the unique
# id of the parent row to join the two. This is cumbersome and prone
# to problems when systems get migrated or merged. Instead, Actions
# will make use of the mysql_insert_id() function in PHP


#
# Table structure for table `be_target`
#
DROP TABLE IF EXISTS be_target;
CREATE TABLE be_target (
  actionID smallint(5) unsigned NOT NULL,
  contactID smallint(5) unsigned NOT NULL,
  notes text NOT NULL default '',
  dateCreated INT( 10 ) UNSIGNED NOT NULL,
  dateModified INT( 10 ) UNSIGNED NOT NULL,
  PRIMARY KEY (actionID, contactID)
) TYPE=MyISAM;


#
# Table structure for table `be_contactType`
#
DROP TABLE IF EXISTS be_contactType;
CREATE TABLE be_contactType (
  contactTypeID  smallint(5) unsigned NOT NULL,
  description varchar(255) NOT NULL,
  PRIMARY KEY (contactTypeID)
) TYPE=MyISAM;


#
# Table structure for table `be_action`
#
DROP TABLE IF EXISTS be_action;
CREATE TABLE be_action (
  actionID smallint(5) unsigned NOT NULL auto_increment,
  URLname varchar(20) NOT NULL,
  author_id smallint(5) unsigned default NULL,
  subsiteID smallint(5) NOT NULL default '0',
  dateCreated INT( 10 ) UNSIGNED NOT NULL,
  dateModified INT( 10 ) UNSIGNED NOT NULL,
  dateAvailable INT( 10 ) UNSIGNED NOT NULL,
  dateRemoved INT( 10 ) UNSIGNED NOT NULL default '0',
  hide tinyint(2) default '0',
  restrict2members tinyint(5) default '0',
  customize tinyint(5) default '0',
  targetType tinyint(5) default '0',
  actionCounter smallint(10) UNSIGNED NOT NULL default '0',
  priority smallint(5) NOT NULL default '0',
  actionType smallint(5) unsigned NOT NULL,
  hitCounter smallint(10) UNSIGNED NOT NULL default '0',
  content_type tinyint(1) unsigned NOT NULL default 3,
  PRIMARY KEY (actionID),
  UNIQUE INDEX URLname (URLname),
  INDEX author_id (author_id),
  INDEX subsiteID(subsiteID)
) TYPE=MyISAM;


#
# Table structure for table `be_actionText`
#
DROP TABLE IF EXISTS be_actionText;
CREATE TABLE be_actionText (
  actionTextID smallint(5) unsigned NOT NULL auto_increment,
  actionID smallint(5) NOT NULL,
  languageID char(3) NOT NULL,
  title varchar(255) NOT NULL,
  blurb text NOT NULL default '',
  content text NOT NULL default '',
  content_htmlsource text NOT NULL default '',
  thank_you text DEFAULT '',
  spotlight tinyint(2) not null default 0,
  template varchar(55) default NULL,         # currently unused
  PRIMARY KEY (actionTextID),
  INDEX actionID (actionID),
  UNIQUE INDEX actionlanguage (actionID,languageID)
) TYPE=MyISAM;


#
# Table structure for table `be_actionType`
#
DROP TABLE IF EXISTS be_actionType;
CREATE TABLE be_actionType (
  actionTypeID smallint(5) unsigned NOT NULL,
  description varchar(255) NOT NULL,
  PRIMARY KEY (actionTypeID)
) TYPE=MyISAM;


#
# Table structure for table `be_targetType`
#
DROP TABLE IF EXISTS be_targetType;
CREATE TABLE be_targetType (
  targetTypeID smallint(5) unsigned NOT NULL,
  description varchar(255) NOT NULL,
  PRIMARY KEY (targetTypeID)
) TYPE=MyISAM;


#
# Table structure for table `be_action2section`
#
DROP TABLE IF EXISTS be_action2section;
CREATE TABLE be_action2section (
  actionID smallint(5) unsigned NOT NULL,
  sectionID smallint(5) unsigned NOT NULL,
  PRIMARY KEY (actionID, sectionID)
) TYPE=MyISAM;


#
# Table structure for table `be_actionContact`
#
#
DROP TABLE IF EXISTS be_contact;
CREATE TABLE be_contact (
  contactID smallint(5) unsigned NOT NULL auto_increment,
  contactType smallint(5) unsigned NOT NULL,
  firstName varchar(50) NOT NULL,
  lastName varchar(50) NOT NULL,
  companyName varchar(100) default '',
  displayName varchar(100) NOT NULL,
  gender char(2) default 'U',
  title varchar(50) default '',
  email varchar(100) default '',
  phoneNumber varchar(50) default '',
  faxNumber varchar(50) default '',
  address varchar(100) default '',
  city varchar(50) default '',
  province varchar(20) default '',
  postalCode varchar(20) default '',
  country varchar(20) default '',
  notes text,
  target tinyint(2) default '0',
  dateCreated INT(10) UNSIGNED NOT NULL,
  dateModified INT(10) UNSIGNED NOT NULL,
  followupGlobal TINYINT(2) NOT NULL default '0',
  verified tinyint(1) unsigned DEFAULT 0,
  randomKey varchar(10) DEFAULT "",
  sameContactAs smallint(5) unsigned DEFAULT 0,
  author_id smallint(11) unsigned DEFAULT 0,
  enteredBy smallint(11) unsigned DEFAULT 0,
  dateVerified int(10) unsigned default 0,

  PRIMARY KEY (contactID),
  INDEX randomKey(randomKey),
  INDEX email(email),
  INDEX author_id(author_id)
) TYPE=MyISAM;

#
# Table structure for table `be_action2contact`
#
DROP TABLE IF EXISTS be_action2contact;
CREATE TABLE be_action2contact (
  actionID smallint(5) UNSIGNED NOT NULL,
  contactID smallint(5) UNSIGNED NOT NULL,
  targetID smallint(5) UNSIGNED NOT NULL,
  extraContent text default '',
  customContent text default '',
  followup tinyint(5) UNSIGNED default '0',
  dateDelivered INT( 10 ) UNSIGNED default '0',
  PRIMARY KEY (contactID, actionID, targetID)
) TYPE=MyISAM;


# Upgrade Sept4, 2003
#  ALTER TABLE `be_contact` ADD `followup` SMALLINT( 2 ) DEFAULT '0' NOT NULL;
# Upgrade Feb 2005: See Upgrade714to715.sql

DROP TABLE IF EXISTS be_targetFinder;
CREATE TABLE be_targetFinder (
  targetFinderID SMALLINT(5) UNSIGNED NOT NULL,
  countryID CHAR(3) NOT NULL default '',
  targetTypeName VARCHAR(30) NOT NULL,
  active SMALLINT(1) UNSIGNED NOT NULL DEFAULT '1',
  targetFinderClassName VARCHAR(40) NOT NULL,
  targetFinderClassVersion SMALLINT(4) UNSIGNED NOT NULL DEFAULT 1,
  targetFinderParameters VARCHAR(200) NOT NULL DEFAULT '',
  PRIMARY KEY (targetFinderID)
) TYPE=MyISAM;

DROP TABLE IF EXISTS be_targetFinder2action;
CREATE TABLE be_targetFinder2action (
 targetFinderID SMALLINT(5) UNSIGNED NOT NULL,
 actionID smallint(5) UNSIGNED NOT NULL,
 PRIMARY KEY (targetFinderID,actionID),
 INDEX actionID (actionID)
) TYPE=MyISAM;

DROP TABLE IF EXISTS be_target2participant;
CREATE TABLE be_target2participant (
       targetFinderID SMALLINT(5) UNSIGNED NOT NULL,
       participantID SMALLINT(5) UNSIGNED NOT NULL,
       targetID SMALLINT(5) UNSIGNED NOT NULL,
       lastChecked INT( 10 ) UNSIGNED DEFAULT '0',
       success TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
       PRIMARY KEY (targetFinderID, participantID)
) TYPE=MyISAM;

