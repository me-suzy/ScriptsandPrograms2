UPDATE psl_variable SET value="0.7.1.1" WHERE variable_id='100';

ALTER TABLE `be_upload` CHANGE `imageHeight` `imageHeight` INT( 10 ) UNSIGNED DEFAULT NULL;
ALTER TABLE `be_upload` CHANGE `imageWidth` `imageWidth` INT( 10 ) UNSIGNED DEFAULT NULL;

# DB Optimization
ALTER TABLE `psl_block` ADD INDEX ( `id` );
ALTER TABLE `be_section2section` ADD INDEX ( `childSectionID` ) ;
ALTER TABLE `psl_group_section_lut` ADD INDEX ( `group_id` ) ;
ALTER TABLE `psl_group_section_lut` ADD INDEX ( `section_id` ) ;
ALTER TABLE `be_event` ADD INDEX `calendarDisplay` ( `eventID` , `calendar` , `draft` , `startDate` , `endDate` ) ;

# Action/Petition tables
# ALTER TABLE `be_contactType` ADD INDEX ( `contactTypeID` );
# ALTER TABLE `be_action` ADD INDEX ( `actionID` ); 

# Adding in author data to logs
ALTER TABLE `psl_infolog` ADD `userID` INT( 10 ) UNSIGNED NOT NULL ;

# This is a correction from the 705to710 script as actual values need to be inserted
UPDATE `be_sections` SET orderbySections='dateCreated';
UPDATE `be_sections` SET orderbySectionsLogic='desc';
UPDATE `be_sections` SET orderbyArticles='dateCreated';
UPDATE `be_sections` SET orderbyArticlesLogic='desc';
UPDATE `be_sections` SET orderbyLinks='dateCreated';
UPDATE `be_sections` SET orderbyLinksLogic='desc';

# More table indexing
ALTER TABLE `be_sectionText` ADD INDEX ( `sectionID` , `languageID` );
ALTER TABLE `be_articleText` ADD INDEX ( `articleID` , `languageID` );

# Add delete key to mark deleted sections/articles - Aug 5 
ALTER TABLE `be_articles` ADD `deleted` TINYINT( 2 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `hide` ;
ALTER TABLE `be_sections` ADD `deleted` TINYINT( 2 ) UNSIGNED DEFAULT '0' NOT NULL AFTER `hide` ;
UPDATE `be_articles` SET deleted='0';
UPDATE `be_sections` SET deleted='0';

# Add block cache table for optional block cache feature - Aug 11
CREATE TABLE `be_blockcache` (
  `blockID` int(11) NOT NULL default '0',
  `blockTypeID` int(11) NOT NULL default '0',
  `userID` int(11) NOT NULL default '0',
  `languageID` char(3) NOT NULL default '',
  `subsiteID` int(11) NOT NULL default '0',
  `expiryTime` int(11) NOT NULL default '0',
  `cacheData` text NOT NULL,
  KEY `expiryTime` (`expiryTime`),
  KEY `blockID` (`blockID`),
  KEY `blockTypeID` (`blockTypeID`),
  KEY `userID` (`userID`),
  KEY `languageID` (`languageID`),
  KEY `subsiteID` (`subsiteID`)
) TYPE=MyISAM;
