#  Update the version flag.
UPDATE psl_variable SET value="0.7.2.0" WHERE variable_name='BE_Version';

SELECT @blockTypeId:=max(id) FROM `psl_block_type`;
# Add Language switching block to default set
#INSERT INTO `psl_block_type` (`id`, `name`) VALUES (@blockTypeId:=@blockTypeId+1, 'BE_languageSwitching');

# Add the petition blocks.
INSERT INTO `psl_block_type` (`id`, `name`) VALUES ((@blockTypeId+1), 'BE_petitions');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES ((@blockTypeId+2), 'BE_petitionSigners');
UPDATE db_sequence SET nextid=@blockTypeId+3 WHERE seq_name='psl_blocktype_seq';

#INSERT INTO `psl_block_type` (`id`, `name`) VALUES (114, 'BE_petitions');
#INSERT INTO `psl_block_type` (`id`, `name`) VALUES (115, 'BE_petitionSigners');

# Set all petition2contact objects to be approved - signers are currently
# approved by default, and can only be "de-approved".  We may wish to
# modify this at some point in the future, to allow signatures to require
# approval before they can be seen.
UPDATE pet_petition2contact SET approved='1';

# Enhance subsite support for petitions
ALTER TABLE `pet_petition` ADD subsiteID smallint(10) default 0 not null;

#Gender
ALTER TABLE be_contact ADD gender char(2) default 'U' after displayName;

CREATE TABLE be_targetFinder (
  targetFinderID SMALLINT(5) UNSIGNED NOT NULL,
  countryID CHAR(3) NOT NULL default '',
  targetTypeName VARCHAR(30) NOT NULL,
  targetFinderClassName VARCHAR(40) NOT NULL,
  targetFinderClassVersion SMALLINT(4) UNSIGNED NOT NULL DEFAULT 1,
  targetFinderParameters VARCHAR(200) NOT NULL DEFAULT '',
  PRIMARY KEY (targetFinderID)
) TYPE=MyISAM;


CREATE TABLE be_targetFinder2action (
 targetFinderID SMALLINT(5) UNSIGNED NOT NULL,
 actionID smallint(5) UNSIGNED NOT NULL,
 PRIMARY KEY (targetFinderID,actionID),
 INDEX actionID (actionID)
) TYPE=MyISAM;

CREATE TABLE be_target2participant (
       targetFinderID SMALLINT(5) UNSIGNED NOT NULL,
       participantID SMALLINT(5) UNSIGNED NOT NULL,
       targetID SMALLINT(5) UNSIGNED NOT NULL,
       lastChecked INT( 10 ) UNSIGNED DEFAULT '0',
       success TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
       PRIMARY KEY (targetFinderID, participantID)
) TYPE=MyISAM;

INSERT INTO be_targetFinder
  (targetFinderID, countryID,  targetTypeName, targetFinderClassName, targetFinderClassVersion)
  VALUES
  (1, 'CAN', 'MP', '', 1),
  (2, 'CAN', 'MP', 'BE_TargetFinderMP_CA', 2),
  (3, 'CAN', 'Walmart', 'BE_TargetFinderWalmart_CA', 1);

# Associate MP with actions
INSERT INTO be_targetFinder2action SELECT 2, actionID FROM be_action WHERE targetType=2;

# Someday:
# ALTER TABLE be_action DROP targetType

ALTER TABLE be_action2contact ADD extraContent text default '' after targetID;

# Mutexes for block caching.  Can be used for anything else that requires
# threading access.
CREATE TABLE `be_mutex` (
  `mutexName` varchar(127) NOT NULL default '',
  `mutexTime` int(11) NOT NULL default '0',
  PRIMARY KEY  (`mutexName`)
) TYPE=MyISAM;

#  Allow articles to redirect to documents
ALTER TABLE`be_upload2article` ADD `mainPage` SMALLINT(2) NOT NULL DEFAULT '0';

# allow some targetfinders to be excluded
ALTER TABLE be_targetFinder ADD active smallint(1) UNSIGNED NOT NULL default '1' AFTER targetTypeName;

UPDATE be_targetFinder SET active = 0 WHERE targetFinderID = 1;

# Add dateCreated to text records, so that we can have language-specific new article blocks, etc.
ALTER TABLE be_articleText ADD dateCreated integer(11) UNSIGNED NOT NULL default '0';
ALTER TABLE be_articleText ADD INDEX dateCreated(dateCreated);

# Tables and default values for new tell-a-friend engine
# Added by ian@clysdale.ca, March 20/2005
CREATE TABLE `be_card` (
   cardID integer(11) unsigned not null auto_increment,
   customize smallint(4) unsigned not null default 0,
   defaultCard smallint(4) unsigned not null default 0,
   senderName varchar(255) not null default '',
   senderEmail varchar(255) not null default '',
   primary key(cardID),
   index defaultCard(defaultCard)
);

CREATE TABLE `be_cardText`(
   cardTextID integer(11) unsigned not null auto_increment,
   cardID integer(11) unsigned not null default 0,
   languageID char(3) not null default '',
   cardTitle varchar(255) not null default '',
   cardBlurb text not null default '',
   cardText text not null default '',
   cardImage varchar(255) not null default '',
   primary key (cardTextID),
   index languageID(languageID),
   index cardID(cardID),
   index languageCard(languageID,cardID)
);

CREATE TABLE `be_card2section` (
   cardID integer(11) unsigned not null default 0,
   sectionID integer(11) unsigned not null default 0,
   primary key(cardID,sectionID)
);

CREATE TABLE `be_card2action` (
   cardID integer(11) unsigned not null default 0,
   actionID integer(11) unsigned not null default 0,
   primary key(cardID,actionID)
);

CREATE TABLE `be_card2petition` (
   cardID integer(11) unsigned not null default 0,
   petitionID integer(11) unsigned not null default 0,
   primary key(cardID,petitionID)
);

INSERT INTO `be_card` (cardID, defaultCard, customize) VALUES (1, 1, 1);
INSERT INTO `be_cardText` (cardID, cardTextID, languageID, cardTitle, cardBlurb, cardText, cardImage) VALUES(1,1,'en','About Back-End', 'Spread the word about Back-End: tell your friends and colleagues about this site.', '[NAME] asked us to let you know about this page on the Back-End web site: [URL]', '');
INSERT INTO `be_cardText` (cardID, cardTextID, languageID, cardTitle, cardBlurb, cardText, cardImage) VALUES(1,2,'fr','Au sujet de Back-End', '(Needs French translation): Spread the word about Back-End: tell your friends and colleagues about this site.', '[NAME] asked us to let you know about this page on the Back-End web site:[URL]', '');

# Addressed in Upgrade720to721.sql
#INSERT INTO `psl_group` (`group_id`, `group_name`, `group_description`) VALUES (212, 'taf', 'Back-End - Change tell-a-friend messages');
#INSERT INTO `psl_group_group_lut` VALUES (212, 24, 212);
#INSERT INTO `psl_group_permission_lut` (`lut_id`, `group_id`, `permission_id`) VALUES (212, 212, 213);
#INSERT INTO `psl_permission` (`permission_id`, `permission_name`, `permission_description`) VALUES (213, 'taf', 'Can change tell-a-friend messages');

# Sort out inconsistency that's arisen in upgrade tracking record
UPDATE psl_variable
   SET variable_id=101
 WHERE variable_id=102;

# And allow ourselves to refer to it by name in the future
UPDATE psl_variable
   SET variable_name= 'BE_CompletedUpgrade',
       description  = 'Internal variable used for tracking use of DB Upgrade scripts'
 WHERE variable_id=101;

# Flag to the code that the last upgrade was complete
UPDATE psl_variable SET value='CompletedUpgrade0.7.2.0' WHERE variable_name='BE_CompletedUpgrade';

#
# THE FOLLOWING ARE OPTIONAL UPGRADES WHICH ONLY WORK IN MYSQL4.x
# Don't add any critical upgrade queries below here!
#

# associate participants to contacts...
# but exclude contacts that were "hard-associated" to a given action!
INSERT INTO be_target2participant
       SELECT DISTINCT 2, contactID, targetID, 0, 1
       FROM be_action2contact
       WHERE targetID not in
             (SELECT  contactID FROM be_target
             WHERE be_target.actionID = be_action2contact.actionID);

# Choose the latest sent date as the last checked date
UPDATE be_target2participant SET lastChecked =
       (SELECT dateDelivered FROM be_action2contact
        WHERE be_action2contact.contactID = be_target2participant.participantID
              AND be_action2contact.targetID = be_target2participant.targetID
         ORDER BY dateDelivered DESC LIMIT 1);



