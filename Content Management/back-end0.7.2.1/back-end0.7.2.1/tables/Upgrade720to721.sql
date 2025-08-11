## Make sure to add the date that sql was added
## $Id: Upgrade720to721.sql,v 1.26 2005/06/15 13:07:26 mgifford Exp $

#  Update the version flag.
UPDATE psl_variable SET value="0.7.2.1" WHERE variable_name='BE_Version';

# Make polls subsite-safe
ALTER table `psl_poll_question` add `subsite_id` smallint(5) unsigned not null default 0;
ALTER table `psl_poll_question` add index `subsite_id`(`subsite_id`);
# ensure MPs are targets
UPDATE  be_contact SET target = 1 WHERE contactType = 2;
# was obsoleted...
ALTER table be_actionText DROP title_source;
ALTER table be_actionText DROP content_source;
ALTER table be_actionText ADD content_htmlsource text NOT NULL default '' after content;

ALTER table be_action ADD content_type tinyint(1) unsigned not null default 3;

# Thank you text for actions
ALTER table be_actionText ADD thank_you text DEFAULT '' AFTER content;

# April 17, 2005 - subdir for Updir Module
ALTER TABLE `be_upload` ADD `subdir` VARCHAR( 25 ) AFTER `subsiteID` ;

# Add ratings for comments -- ian, April 18/2005
ALTER TABLE psl_comment ADD rating int(5) NOT NULL DEFAULT 0;
ALTER TABLE psl_author ADD defaultCommentThreshold int(5) NOT NULL DEFAULT 0;
ALTER TABLE psl_comment ADD INDEX rating(rating);

# Add verified, randomkey, sameContact to contact -- MAP, 2005-04-29
ALTER TABLE be_contact ADD verified tinyint(1) unsigned DEFAULT 0;
ALTER TABLE be_contact ADD randomKey varchar(10) DEFAULT "";
ALTER TABLE be_contact ADD sameContactAs smallint(5) unsigned DEFAULT 0;
ALTER TABLE be_contact ADD author_id smallint(11) unsigned DEFAULT 0;
ALTER TABLE be_contact ADD enteredBy smallint(11) unsigned DEFAULT 0;
ALTER TABLE be_contact ADD INDEX randomKey(randomKey);
ALTER TABLE be_contact ADD INDEX email(email);
ALTER TABLE be_contact ADD INDEX author_id(author_id);
ALTER TABLE be_contact DROP INDEX target;
ALTER TABLE be_contact DROP INDEX displayName;
ALTER TABLE be_contact ADD dateVerified int(10) unsigned default 0;

# correct for a bug in date handling
UPDATE be_action SET dateRemoved = 0
    WHERE dateRemoved > 0 AND dateRemoved = dateCreated;

# Update a variable to keep track of last date of db update
SELECT @VarId := max(variable_id) FROM psl_variable;
INSERT INTO `psl_variable` VALUES ((@VarId+1), 'DB_Date', '13May2005', 'DB_Date', '');
UPDATE db_sequence SET nextid=(@VarId+2) WHERE seq_name='psl_variable_seq';

# 2005-May-13: REBUILD SEQUENCING on group and block related tables
# (Sequencing has got out of sync over the past few releases)
# This uses an installation-independent technique, avoiding hardcoded ids

# Blocks
SELECT @NextBlock := max(id) FROM psl_block;
UPDATE db_sequence SET nextid =(@NextBlock + 1) WHERE seq_name = 'psl_block_seq';
SELECT @NextBlockText := max(textID) FROM psl_blockText;
UPDATE db_sequence SET nextid = (@NextBlockText + 1) WHERE seq_name = 'psl_block_text_seq';
SELECT @NextSectionBlock := max(lut_id) FROM psl_section_block_lut;
UPDATE db_sequence SET nextid = (@NextSectionBlock + 1) WHERE seq_name = 'psl_section_block_lut_seq';

# Groups
SELECT @NextGroup := max(group_id) FROM psl_group;
UPDATE db_sequence SET nextid = (@NextGroup + 1) WHERE seq_name = 'psl_group_seq';
SELECT @NextPermission := max(permission_id) FROM psl_permission;
UPDATE db_sequence SET nextid = (@NextPermission + 1) WHERE seq_name = 'psl_permission_seq';
SELECT @NextGroupGroup := max(lut_id) FROM psl_group_group_lut;
UPDATE db_sequence SET nextid = (@NextGroupGroup + 1) WHERE seq_name = 'psl_group_group_lut_seq';
SELECT @NextGroupSection := max(lut_id) FROM psl_group_section_lut;
UPDATE db_sequence SET nextid = (@NextGroupSection + 1) WHERE seq_name = 'psl_group_section_lut_seq';
SELECT @NextGroupPermission := max(lut_id) FROM psl_group_permission_lut;
UPDATE db_sequence SET nextid = (@NextGroupPermission + 1) WHERE seq_name = 'psl_group_permission_lut_seq';
SELECT @NextAuthorGroup := max(lut_id) FROM psl_author_group_lut;
UPDATE db_sequence SET nextid = (@NextAuthorGroup + 1) WHERE seq_name = 'psl_author_group_lut_seq';


# 2005-May-14 REBUILD TAF GROUPS
# This should have been dealt with in the last upgrade, but I expect that there were some conflicts in the sql that prevented it from working
# NB User variables @NextGroup etc are reused from above
SELECT @TafGroupId := group_id FROM psl_group WHERE group_name = 'taf';
SELECT @TafPermId := permission_id FROM psl_permission WHERE permission_name = 'taf';

DELETE FROM `psl_group` WHERE group_name = 'taf';
DELETE FROM `psl_permission` WHERE permission_name = 'taf';
DELETE FROM `psl_group_permission_lut` WHERE permission_id = @TafPermId OR group_id = @TafGroupId;
DELETE FROM `psl_group_section_lut` WHERE group_id = @TafGroupId;
DELETE FROM `psl_group_group_lut` WHERE group_id = @TafGroupId OR childgroup_id = @TafGroupId;

INSERT INTO `psl_group` (`group_id`, `group_name`, `group_description`) VALUES ((@NextGroup + 1), 'taf', 'Back-End - Change tell-a-friend messages');
INSERT INTO `psl_permission` (`permission_id`, `permission_name`, `permission_description`) VALUES ((@NextPermission + 1), 'taf', 'Can change tell-a-friend messages');
INSERT INTO `psl_group_group_lut` (`lut_id`, `group_id`, `childgroup_id`) VALUES ((@NextGroupGroup + 1), 24, (@NextGroup + 1));
INSERT INTO `psl_group_permission_lut` (`lut_id`, `group_id`, `permission_id`) VALUES ((@NextGroupPermission + 1), (@NextGroup + 1), (@NextPermission + 1));
INSERT INTO `psl_group_section_lut` (`lut_id`, `group_id`, `section_id`) VALUES ((@NextGroupSection + 1), (@NextGroup + 1), 0);

#Fix any authors linked with the old TAF group_id
UPDATE psl_author_group_lut SET group_id = (@NextGroup + 1) WHERE group_id = @TafGroupId;

UPDATE db_sequence SET nextid = (@NextGroup + 2) WHERE seq_name = 'psl_group_seq';
UPDATE db_sequence SET nextid = (@NextPermission + 2) WHERE seq_name = 'psl_permission_seq';
UPDATE db_sequence SET nextid = (@NextGroupGroup + 2) WHERE seq_name = 'psl_group_group_lut_seq';
UPDATE db_sequence SET nextid = (@NextGroupSection + 2) WHERE seq_name = 'psl_group_section_lut_seq';
UPDATE db_sequence SET nextid = (@NextGroupPermission + 2) WHERE seq_name = 'psl_group_permission_lut_seq';

# TAF REBUILD DONE
# NB The user vaiables have also been updated for the next sequence ids to use

#2005-May-16 MORE SEQUENCE REBUILDING: block-types
SELECT @NextBlockType := max(id) FROM psl_block_type;
UPDATE db_sequence SET nextid = (@NextBlockType + 1) WHERE seq_name = 'psl_blocktype_seq';


# Update a variable to keep track of last date of db update & cvs version of the upgrade script
DELETE FROM `psl_variable` WHERE `variable_name` = 'DB_Date';
SELECT @VarId := max(variable_id) FROM psl_variable;
INSERT INTO `psl_variable` VALUES ((@VarId+1), 'DB_Upgrade_Date', now(), 'Date that the last database upgrade was done', '');
INSERT INTO `psl_variable` VALUES ((@VarId+2), 'DB_CVS_Date', '720to721 $Id: Upgrade720to721.sql,v 1.26 2005/06/15 13:07:26 mgifford Exp $', 'Date of the cvs version from the last upgrade', '');
UPDATE db_sequence SET nextid=(@VarId+3) WHERE seq_name='psl_variable_seq';

CREATE TABLE IF NOT EXISTS `be_feedback` (
  `id` smallint(5) default NULL,
  `SubmitterName` varchar(255) default NULL,
  `SubmitterEmail` varchar(255) default NULL,
  `Location` varchar(255) default NULL,
  `ReferringPage` varchar(255) default NULL,
  `CupeMember` smallint(3) default NULL,
  `CupeLocal` varchar(255) default NULL,
  `KnowsCupeMember` smallint(3) default NULL,
  `Comments` text,
  `TimeSubmitted` int(10) default NULL,
  `TimeRespondedTo` int(10) default NULL,
  `Responded` smallint(3) default NULL,
  `ForwardedTo` varchar(255) default NULL,
  `RespondedBy` varchar(50) default NULL,
  `Response` text,
  `Browser` varchar(255) default NULL,
  `UserIP` varchar(63) default NULL,
  `RemoteHost` varchar(255) default NULL,
  `FeedbackComments` text,
  `ForwardComments` text,
  `subsite_id` smallint(5) unsigned NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM COMMENT='Feedback Table';

#2005-May-20 adding new block-types
SELECT @NextBlockType := max(id) FROM psl_block_type;
INSERT INTO `psl_block_type` VALUES ((@NextBlockType+1), 'BE_listArticles');
INSERT INTO `psl_block_type` VALUES ((@NextBlockType+2), 'BE_listLinks');
UPDATE db_sequence SET nextid = (@NextBlockType + 3) WHERE seq_name = 'psl_blocktype_seq';


CREATE TABLE `be_searchlog` (
   `query` VARCHAR( 100 ) NOT NULL ,
   `dateUpdated` INT( 11 ) UNSIGNED NOT NULL ,
   `count` INT( 11 ) UNSIGNED NOT NULL ,
   `userAgent` varchar(255) default NULL,
    INDEX ( `query` )
) COMMENT = 'recording internal searches';


CREATE TABLE `be_errorlog` (
  `url` varchar(255) NOT NULL default '',
  `dateUpdated` int(11) unsigned NOT NULL default '0',
  `count` int(11) unsigned NOT NULL default '0',
  `referredFrom` varchar(255) NOT NULL default '',
  `redirectedTo` varchar(255) NOT NULL default '',
  `userAgent` varchar(255) default NULL,
  KEY `url` (`url`)
) TYPE=MyISAM COMMENT='Record urls which were directed to the error page';


# NEXT TO LAST LINES: Record date of last update
UPDATE psl_variable SET value=now() WHERE variable_name='DB_Upgrade_Date';
UPDATE psl_variable SET value='720to721 $Id: Upgrade720to721.sql,v 1.26 2005/06/15 13:07:26 mgifford Exp $' WHERE variable_name='DB_CVS_Date';
UPDATE psl_variable SET value='Upgrade720to721.sql (Manual)' WHERE variable_name='BE_CompletedUpgrade';

#  LAST LINES: SQL which may have errors on some upgrades but which isn't critical
ALTER table be_actionText DROP blurb_source;


