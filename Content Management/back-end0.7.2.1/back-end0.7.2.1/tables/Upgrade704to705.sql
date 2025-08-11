UPDATE psl_variable SET value='0.7.0.5' WHERE variable_id='100';

# Multilingual Polls
ALTER TABLE `psl_poll_question` ADD COLUMN  `language_id` char(2) NOT NULL default '';

# Extend be_upload tables
ALTER TABLE `be_upload` ADD `rawSize` INT( 11 ) ;
ALTER TABLE `be_upload` ADD `time` INT( 11 ) ;
ALTER TABLE `be_upload` ADD `perm` VARCHAR( 20 ) ;

# For subsites
ALTER TABLE `be_articles` ADD `subsiteID` INT( 10 ) UNSIGNED AFTER `author_id` ;

CREATE TABLE `be_image2articleText` (
  `articleTextID` int(11) NOT NULL default '0',
  `imageID` int(11) NOT NULL default '0',
  PRIMARY KEY  (`articleTextID`,`imageID`),
  UNIQUE KEY `ImageArticleTextIndex` (`articleTextID`,`imageID`)
) TYPE=MyISAM;

ALTER TABLE `be_images` ADD `filename` VARCHAR( 255 ) ;

CREATE TABLE `be_upload2article` (
  `articleTextID` smallint(5) unsigned NOT NULL default '0',
  `language` char(3) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `caption` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `dateUploaded` int(11) default '0',
  KEY `articleTextID` (`articleTextID`)
) TYPE=MyISAM;

# April 23
ALTER TABLE `be_upload` ADD `subsiteID` SMALLINT( 5 );

# Apr 25 - Text options can be extended here, but this framework table is presently required.
CREATE TABLE `be_articleTextOptions` (
  `articleID` smallint(5) unsigned NOT NULL default '0',
  `languageID` char(3) NOT NULL default '',
  `options` text NOT NULL,
  `Author` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`articleID`,`languageID`)
) TYPE=MyISAM;

UPDATE `psl_group` SET group_description = 'Administer all users/authors' WHERE group_id = '1';
UPDATE `psl_group` SET group_description = 'Administer all blocks' WHERE group_id = '4';
UPDATE `psl_group` SET group_description = 'Administer comments' WHERE group_id = '8';
UPDATE `psl_group` SET group_description = 'Administer glossary' WHERE group_id = '9';
UPDATE `psl_group` SET group_description = 'Administer groups of permissions' WHERE group_id = '10';
UPDATE `psl_group` SET group_description = 'View/Delete Infolog' WHERE group_id = '11';
UPDATE `psl_group` SET group_description = 'phpSlash - Administer Mailing List' WHERE group_id = '12';
UPDATE `psl_group` SET group_description = 'Administer permissions' WHERE group_id = '13';
UPDATE `psl_group` SET group_description = 'Administer Polls' WHERE group_id = '14';
UPDATE `psl_group` SET group_description = 'Administer Sections' WHERE group_id = '15';
UPDATE `psl_group` SET group_description = 'phpSlash - Administer Stories' WHERE group_id = '16';
UPDATE `psl_group` SET group_description = 'phpSlash - Administer Submissions' WHERE group_id = '17';
UPDATE `psl_group` SET group_description = 'phpSlash - Administer Topics' WHERE group_id = '18';
UPDATE `psl_group` SET group_description = 'Administer site variables' WHERE group_id = '19';
UPDATE `psl_group` SET group_description = 'Anonymous user' WHERE group_id = '20';
UPDATE `psl_group` SET group_description = 'General logged in user privileges' WHERE group_id = '21';
UPDATE `psl_group` SET group_description = 'Ability to add comments - if restricted' WHERE group_id = '22';
UPDATE `psl_group` SET group_description = 'phpSlash - Permision extend story editor privileges' WHERE group_id = '23';
UPDATE `psl_group` SET group_description = 'All privileges' WHERE group_id = '24';
UPDATE `psl_group` SET group_description = 'Back-End - Permissions allowing users to submit and edit stories in specified subsite' WHERE group_id = '200';
UPDATE `psl_group` SET group_description = 'Back-End - Administer subsites' WHERE group_id = '201';
UPDATE `psl_group` SET group_description = 'Back-End - Administer file uploads' WHERE group_id = '202';
UPDATE `psl_group` SET group_description = 'Back-End - Administer file gallery' WHERE group_id = '203';
UPDATE `psl_group` SET group_description = 'Back-End - Subsite administration including sections, stories, uploads' WHERE group_id = '204';
UPDATE `psl_group` SET group_description = 'Back-End - Permission to amend templates online' WHERE group_id = '205';
UPDATE `psl_group` SET group_description = 'Back-End - Administer Links' WHERE group_id = '206';
UPDATE `psl_group` SET group_description = 'Back-End - Administer targets for online actions' WHERE group_id = '207';
UPDATE `psl_group` SET group_description = 'Back-End - Administer online actions' WHERE group_id = '208';
UPDATE `psl_group` SET group_description = 'Back-End - Administer contacts for online actions' WHERE group_id = '209';
UPDATE `psl_group` SET group_description = 'Back-End - Administer bibliography' WHERE group_id = '210';
UPDATE `psl_group` SET group_description = 'Back-End - Administer petitions' WHERE group_id = '211';
UPDATE `psl_group` SET group_description = 'Back-End - Allows users to access restricted pages' WHERE group_id = '213';

# Thanks DR for this more sophisticated sql!  It is replacing the following:
# INSERT INTO `psl_group` (`group_id`, `group_name`, `group_description`) VALUES (213, 'Member', 'Back-End - Allows users to access restricted pages.');
# INSERT INTO `psl_group_group_lut` VALUES (223, 213, 21);
# INSERT INTO `psl_group_permission_lut` (`lut_id`, `group_id`,`permission_id`) VALUES (223, 213, 214);
# INSERT INTO `psl_group_section_lut` (`lut_id`, `group_id`, `section_id`)VALUES (67, 213, 0);
# INSERT INTO `psl_permission` (`permission_id`, `permission_name`,`permission_description`) VALUES (214, 'Member', 'Registered Members');
# UPDATE db_sequence SET nextid='215 WHERE seq_name='psl_permission_seq';
# UPDATE db_sequence SET nextid='223' WHERE seq_name='psl_group_group_lut_seq';
# UPDATE db_sequence SET nextid='223' WHERE seq_name='psl_group_permission_lut_seq';

# in using sequences we assume that the "nextid" in the table
# is really the last used id, so we increment before using it
# and save the used value.

# get some sequence values
SELECT @permissionID:=nextid+1 from db_sequence where seq_name = 'psl_permission_seq';
SELECT @groupID:=nextid+1 from db_sequence where seq_name = 'psl_group_seq';

# Member permission
INSERT INTO `psl_permission` (`permission_id`, `permission_name`, `permission_description`)
    VALUES (@permissionID, 'Member', 'Registered Members');
UPDATE db_sequence SET nextid=@permissionID WHERE seq_name = 'psl_permission_seq';

# Member group
INSERT INTO `psl_group` (`group_id`, `group_name`, `group_description`)
    VALUES (@groupID, 'Member', 'Back-End - Allows users to access restricted pages.');
UPDATE db_sequence SET nextid=@groupID WHERE seq_name = 'psl_group_seq';

# Member group has Member permission
SELECT @groupPermissionID:=nextid+1 from db_sequence where seq_name = 'psl_group_permission_lut_seq';
INSERT INTO `psl_group_permission_lut` (`lut_id`, `group_id`, `permission_id`)
    VALUES (@groupPermissionID, @groupID, @permissionID);
UPDATE db_sequence SET nextid=@groupPermissionID WHERE seq_name = 'psl_group_permission_lut_seq';

# Member has access to all sections
SELECT @groupSectionID:=nextid+1 from db_sequence where seq_name = 'psl_group_section_lut_seq';
INSERT INTO `psl_group_section_lut` (`lut_id`, `group_id`, `section_id`)
    VALUES (@groupSectionID, @groupID, 0);
UPDATE db_sequence SET nextid=@groupSectionID WHERE seq_name = 'psl_group_section_lut_seq';

# Root group subsumes member group
SELECT @rootGroupID:=group_id from psl_group where group_name = 'root';
SELECT @groupGroupID:=nextid+1 from db_sequence where seq_name = 'psl_group_group_lut_seq';
INSERT INTO `psl_group_group_lut` (`lut_id`, `group_id`, `childgroup_id`)
    VALUES (@groupGroupID, @rootGroupID, @groupID);
UPDATE db_sequence SET nextid=@groupGroupID WHERE seq_name = 'psl_group_group_lut_seq';

# Member group subsumes user group
SELECT @userGroupID:=group_id from psl_group where group_name = 'user';
SELECT @groupGroupID:=nextid+1 from db_sequence where seq_name = 'psl_group_group_lut_seq';
INSERT INTO `psl_group_group_lut` (`lut_id`, `group_id`, `childgroup_id`)
    VALUES (@groupGroupID, @groupID, @userGroupID);
UPDATE db_sequence SET nextid=@groupGroupID WHERE seq_name = 'psl_group_group_lut_seq';
