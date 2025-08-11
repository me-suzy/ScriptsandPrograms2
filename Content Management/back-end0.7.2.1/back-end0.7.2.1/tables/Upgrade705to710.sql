UPDATE psl_variable SET value="0.7.1.0" WHERE variable_id='100';

ALTER TABLE `be_sections` ADD `orderbySections` VARCHAR( 55 ) DEFAULT NULL;
ALTER TABLE `be_sections` ADD `orderbyArticles` VARCHAR( 55 ) DEFAULT NULL;
ALTER TABLE `be_sections` ADD `orderbyLinks` VARCHAR( 55 ) DEFAULT NULL;
ALTER TABLE `be_sections` ADD `orderbySectionsLogic` CHAR( 4 ) DEFAULT NULL AFTER `orderbySections` ;
ALTER TABLE `be_sections` ADD `orderbyArticlesLogic` CHAR( 4 ) DEFAULT NULL AFTER `orderbyArticles` ;
ALTER TABLE `be_sections` ADD `orderbyLinksLogic` CHAR( 4 ) DEFAULT NULL AFTER `orderbyLinks` ;

UPDATE `be_sections` SET orderbySections=orderby;
UPDATE `be_sections` SET orderbySectionsLogic=ascdesc;
UPDATE `be_sections` SET orderbyArticles=orderby;
UPDATE `be_sections` SET orderbyArticlesLogic=ascdesc;

# CUPE Specific
# UPDATE `be_sections` SET orderbySections=sectionDisplayOrder;
# UPDATE `be_sections` SET orderbyArticles=searchCriteria;
# UPDATE `be_sections` SET orderbySectionsLogic=sectionAscending;
# UPDATE `be_sections` SET orderbyArticlesLogic=ascending;
 
UPDATE `be_sections` SET orderbyLinks=orderby;
UPDATE `be_sections` SET orderbyLinksLogic=ascdesc;

ALTER TABLE `be_sections` DROP `orderby` ;
ALTER TABLE `be_sections` DROP `ascdesc` ;

# CUPE Specific
# ALTER TABLE `be_sections` DROP `searchCriteria` ;
# ALTER TABLE `be_sections` DROP `sectionDisplayOrder` ;
# ALTER TABLE `be_sections` DROP `sectionAscending` ;
# ALTER TABLE `be_sections` DROP `ascending` ;

# Correct any references with the table reference
UPDATE be_sections SET be_sections.orderbySections = 'dateCreated' WHERE be_sections.orderbySections = 'article.dateCreated';
UPDATE be_sections SET be_sections.orderbySections = 'dateModified' WHERE be_sections.orderbySections = 'article.dateModified';
UPDATE be_sections SET be_sections.orderbySections = 'dateAvailable' WHERE be_sections.orderbySections = 'article.dateAvailable';
UPDATE be_sections SET be_sections.orderbySections = 'priority' WHERE be_sections.orderbySections = 'article.priority';
UPDATE be_sections SET be_sections.orderbySections = 'title' WHERE be_sections.orderbySections = 'text.title';
UPDATE be_sections SET orderbyArticles = 'priority' WHERE orderbyArticles='section.priority';
UPDATE be_sections SET orderbyArticles = 'dateCreated' WHERE orderbyArticles='section.dateCreated';


#
# HISTORY AND TRANSLATION WORKFLOW - Sections and Articles
# PAC 2004-06-12

CREATE TABLE `be_history` (
  `id` int(11) NOT NULL auto_increment,
  `itemTable` char(32) NOT NULL,
  `itemKey` char(32) NOT NULL,
  `versionMajor` int(11) NOT NULL,
  `versionMinor` int(11) NOT NULL,
  `userId` varchar(32) NOT NULL,
  `date` int(11) NOT NULL,
  `content` text,
  `hash` char(32) NOT NULL,
  PRIMARY KEY  (`id`),
  INDEX `table_key` (`itemTable`,`itemKey`),
  INDEX `version` (`versionMajor`,`versionMinor`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;


# This change looks forward to translation workflow
ALTER TABLE `be_articles`
   ADD COLUMN `main_languageID` char(2) NOT NULL AFTER `content_type`;

ALTER TABLE `be_sections`
   ADD COLUMN `main_languageID` char(2) NOT NULL AFTER `content_type`;
   
# Update feedback page and standardize it
# ALTER TABLE `Feedback` ADD `TimeRespondedTo` INT( 10 ) NOT NULL, ADD `Responded` SMALLINT( 3 ) NOT NULL, ADD `ForwardedTo` VARCHAR( 255 ) NOT NULL, ADD `RespondedBy` VARCHAR( 50 ) NOT NULL, ADD `Response` TEXT NOT NULL, ADD `FeedbackComments` TEXT NOT NULL , ADD `ForwardComments` TEXT NOT NULL, ADD `subsite_id` SMALLINT( 5 ) NOT NULL, ADD `Location` VARCHAR( 255 ) NOT NULL ;
# ALTER TABLE `Feedback` RENAME `be_feedback`;
