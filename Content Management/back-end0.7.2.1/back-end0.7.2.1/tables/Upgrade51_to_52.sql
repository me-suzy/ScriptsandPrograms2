# $Id: Upgrade51_to_52.sql,v 1.3 2004/07/30 21:23:06 mgifford Exp $
# ADD DATES AND OTHER FUNKY STUFF (MG)

ALTER TABLE `be_sections` ADD `dateCreated`  INT( 10 ) UNSIGNED NOT NULL default '0';
ALTER TABLE `be_sections` ADD `dateModified`  INT( 10 ) UNSIGNED NOT NULL default '0';
ALTER TABLE `be_sections` ADD `dateAvailable`  INT( 10 ) UNSIGNED NOT NULL default '0';
ALTER TABLE `be_sections` ADD `dateRemoved`  INT( 10 ) UNSIGNED NOT NULL default '0';
ALTER TABLE `be_sections` ADD `author_id` SMALLINT( 5 ) NOT NULL AFTER `URLname` ;
ALTER TABLE `be_sections` ADD `hide` tinyint(2) NOT NULL default '0' AFTER `dateRemoved`;
ALTER TABLE `be_sections` ADD `restrict2members` int(5) NOT NULL default '0' AFTER `hide`;

ALTER TABLE `be_articles` CHANGE `date` `dateCreated`   INT( 10 ) UNSIGNED NOT NULL default '0';
ALTER TABLE `be_articles` ADD `dateModified`  INT( 10 ) UNSIGNED NOT NULL default '0';
ALTER TABLE `be_articles` ADD `dateAvailable`  INT( 10 ) UNSIGNED NOT NULL default '0';
ALTER TABLE `be_articles` ADD `dateRemoved`  INT( 10 ) UNSIGNED NOT NULL default '0';
ALTER TABLE `be_articles` CHANGE `userID` `author_id` SMALLINT( 5 ) UNSIGNED NOT NULL default '0';
ALTER TABLE `be_articles` ADD `restrict2members` int(5) NOT NULL default '0' AFTER `hide`;

ALTER TABLE `be_images` ADD `dateCreated`  INT( 10 ) UNSIGNED NOT NULL default '0',
ALTER TABLE `be_images` ADD `dateModified`  INT( 10 ) UNSIGNED NOT NULL default '0',
ALTER TABLE `be_images` ADD `dateAvailable`  INT( 10 ) UNSIGNED NOT NULL default '0',
ALTER TABLE `be_images` ADD `dateRemoved`  INT( 10 ) UNSIGNED NOT NULL default '0',
ALTER TABLE `be_images` ADD `URLname` VARCHAR(32) NOT NULL DEFAULT '';
ALTER TABLE `be_images` ADD `author_id` SMALLINT( 5 ) NOT NULL AFTER `URLname` ;
ALTER TABLE `be_images` ADD `hide` tinyint(2) NOT NULL DEFAULT '0' AFTER `dateRemoved`;
ALTER TABLE `be_images` ADD `restrict2members` int(5) NOT NULL default '0' AFTER `hide`;

ALTER TABLE `be_link` CHANGE `date` `dateCreated`  INT( 10 ) UNSIGNED NOT NULL default '0';
ALTER TABLE `be_link` ADD `dateModified`  INT( 10 ) UNSIGNED NOT NULL default '0';
ALTER TABLE `be_link` ADD `dateAvailable`  INT( 10 ) UNSIGNED NOT NULL default '0';
ALTER TABLE `be_link` ADD `dateRemoved`  INT( 10 ) UNSIGNED NOT NULL default '0';
ALTER TABLE `be_link` ADD `author_id` SMALLINT( 5 ) UNSIGNED NOT NULL default '0';
ALTER TABLE `be_link` ADD `hide` tinyint(2) NOT NULL DEFAULT '0' AFTER `dateRemoved`;
ALTER TABLE `be_link` ADD `restrict2members` int(5) NOT NULL default '0' AFTER `hide`;

UPDATE be_sections set dateCreated=0, dateModified=now(), dateAvailable=0, dateRemoved=0;
UPDATE be_link set dateCreated=0, dateModified=now(), dateAvailable=0, dateRemoved=0;
UPDATE be_images set dateCreated=0, dateModified=now(), dateAvailable=0, dateRemoved=0;
UPDATE be_articles set dateModified=now(), dateAvailable=0, dateRemoved=0;

# ADD COMMENT FACILITY (MG)

ALTER TABLE `be_sections` ADD `commentID` INT( 7 ) DEFAULT '0';
ALTER TABLE `be_sectionText` ADD `commentIDtext` INT( 7 ) DEFAULT '0';

ALTER TABLE `be_articles` ADD `commentID` INT( 7 ) DEFAULT '0';
ALTER TABLE `be_articleText` ADD `commentIDtext` INT( 7 ) NOT NULL DEFAULT '0';

ALTER TABLE `be_images` ADD `commentID` INT( 7 ) DEFAULT '0';
ALTER TABLE `be_imageText` ADD `commentIDtext` INT( 7 ) DEFAULT '0';


# SPOTLIGHTS NOW LANGUAGE-SPECIFIC (PAC)

ALTER TABLE `be_articleText` ADD `spotlight` TINYINT(2) DEFAULT '0';

# This should work from mySQL 4.04 on:
# ALTER TABLE `be_articleText` ADD `spotlight` TINYINT(2) NOT NULL DEFAULT 0;
# UPDATE be_articleText t, be_article a SET t.spotlight = a.spotlight WHERE t.articleID=a.articleID;

# But this is the backwards compatible way:
CREATE TABLE `be2_articleText` (
 `articleTextID` smallint(5) unsigned NOT NULL auto_increment,
 `articleID` smallint(5) NOT NULL default '0',
 `languageID` char(3) NOT NULL default '',
 `title` varchar(255)  not null default '',
 `blerb` text not null default '',
 `content` text not null default '',
 `spotlight` char(1) not null default '0',
 `meta_keywords` varchar(255) default NULL,
 `meta_description` varchar(255) default NULL,
 `template` varchar(55) default NULL,
 `originalText` smallint(5) default NULL,
 `commentIDtext` int(7)  not null default '0',
 PRIMARY KEY  (`articleTextID`),
 KEY `articleID` (`articleID`),
 KEY `languageID` (`languageID`)
) TYPE=MyISAM;

INSERT INTO be2_articleText 
	( `articleTextID`,
	 `articleID`,
	 `languageID`,
	 `title`,
	 `blerb`,
	 `content`,
	 `spotlight`,
	 `meta_keywords`,
	 `meta_description`,
	 `template`,
	 `originalText`,
	 `commentIDtext` )
SELECT  
	t.articleTextID,
	t.articleID,
	t.languageID,
	t.title,
	t.blerb,
	t.content,
	a.spotlight,
	t.meta_keywords,
	t.meta_description,
	t.template,
	t.originalText,
	t.commentIDtext
FROM be_articleText t
LEFT JOIN be_articles a ON a.articleID = t.articleID;
		
ALTER TABLE be_articleText RENAME bex_articleText;
ALTER TABLE be2_articleText RENAME be_articleText;
ALTER TABLE be_articleText CHANGE articleTextID AUTOINCREMENT;
# End

# EXECUTE THESE TWO COMMANDS ONCE YOU'RE SURE EVERYTHING HAS GONE OK
#DROP TABLE bex_articleText;
#ALTER TABLE `be_articles` DROP `spotlight`;


# CONTENT MANAGER/PROVIDER RIGHTS (PART 1)

#
# Table structure for table `be_localrights` (this table superseded - see 30Jan03 below)
#

#CREATE TABLE be_localrights (
#  rightID smallint(5) unsigned NOT NULL default '0',
#  name varchar(255) NOT NULL default '',
#  description varchar(255) NOT NULL default '',
#  PRIMARY KEY  (rightID),
#  UNIQUE KEY rightID (rightID),
#  KEY rightID_2 (rightID)
#) TYPE=MyISAM COMMENT='BE: Implementing section/local level rights';

#
# Dumping data for table `be_localrights`
#

#INSERT INTO be_localrights VALUES (2, 'ContentManager', 'Post articles, modify any article, create sub-sections, assign Content Provider privilege');
#INSERT INTO be_localrights VALUES (1, 'ContentProvider', 'Post articles, modify their own articles');

#
# Table structure for table `be_author2localrights`
#

CREATE TABLE be_author2localrights (
  id smallint(5) unsigned NOT NULL default '0',
  localID smallint(5) unsigned NOT NULL default '0',
  author_id smallint(5) unsigned NOT NULL default '0',
  rightID smallint(5) unsigned NOT NULL default '0',
  rights varchar(255) NOT NULL default '0',
  PRIMARY KEY id (id),
  KEY (localID),
  KEY (author_id),
  KEY (rightID)
) TYPE=MyISAM COMMENT='BE: Links authors to rights over Locals';


# CATEGORIZATION OF ARTICLES ETC

CREATE TABLE be_categories (
   category_id smallint(5) NOT NULL DEFAULT 0 AUTO_INCREMENT,
   category_type char(8) NOT NULL DEFAULT '',
   category_code char(8) NOT NULL default '',
   languageID char(3) NOT NULL default '',
   name varchar(50) NOT NULL default '',
   PRIMARY KEY (category_id),
   KEY (category_type, category_code),
   KEY (languageID)
) TYPE=MyISAM;

CREATE TABLE be_category2item (
  id smallint(5) NOT NULL auto_increment,
  category_type varchar(8) NOT NULL default '',
  category_code varchar(8) NOT NULL default '',
  item_type varchar(16) NOT NULL default 'article',
  item_id varchar(50) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY category_type (category_type,category_code),
  KEY item_type (item_type,item_id)
) TYPE=MyISAM;


##############################
# updates to table psl_block
# - brings table definition into line with PSL0.7
##############################
CREATE TABLE psl_block_new (
  id int(11) unsigned NOT NULL default '0',
  type int(11) NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  expire_length int(11) NOT NULL default '0',
  last_update timestamp(14) NOT NULL,
  location varchar(254) NOT NULL default '',
  source_url varchar(254) NOT NULL default '',
  cache_data text NOT NULL,
  block_options text,
  ordernum int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

ALTER TABLE `psl_block_new` ADD `date_issued` INT DEFAULT NULL;

INSERT INTO psl_block_new 
            SELECT *,UNIX_TIMESTAMP(last_update)
              FROM psl_block;

#DROP TABLE psl_block;
ALTER TABLE `psl_block` RENAME `psl_block_old`;
ALTER TABLE `psl_block_new` RENAME `psl_block`;

ALTER TABLE `psl_block` DROP `last_update`;


# LINK BLOCKS TO LOCALS

CREATE TABLE `be_local2block` (
 `ID` smallint(5) unsigned NOT NULL auto_increment,
 `localID` smallint(5) unsigned NOT NULL default '0',
 `block_id` smallint(5) unsigned NOT NULL default '0',
 PRIMARY KEY  (`ID`),
 KEY `localID` (`localID`),
 KEY `block_id` (`block_id`)
) TYPE=MyISAM COMMENT='BE tracks any links between blocks and Locals';

 
# 29Jan03] - PAC
# Not strictly necessary - but make sure that god has the new rights
UPDATE psl_author 
   SET perms="user,topic,story,storyeditor,comment,section,link,gallery,submission,block,poll,author,variable,glossary,mailinglist,local,upload,logging,root"
 WHERE author_id=1;

# 29Jan03] - Mike
# Small change for BE_block What's New
INSERT INTO psl_block_type (id, name) VALUES (106, 'BE_whatsPopular');
UPDATE db_sequence SET nextid=106 WHERE seq_name='psl_blocktype_seq'; 

# 03Feb03] - Mike
# Small change for BE_block What's New
INSERT INTO psl_block_type (id, name) VALUES (107, 'BE_relatedKeywords');
UPDATE db_sequence SET nextid=107 WHERE seq_name='psl_blocktype_seq'; 

CREATE TABLE be_keywords (
  keywordID int(10) unsigned NOT NULL auto_increment,
  keyword varchar(255) NOT NULL default '',
  relatedObjects tinytext,
  PRIMARY KEY  (keywordID),
  KEY keyword (keyword)
) TYPE=MyISAM COMMENT='BE list of keywords & related articles/sections';

ALTER TABLE `be_articleText` ADD `keywordObjects` TINYTEXT AFTER `meta_description`;  
ALTER TABLE `be_sectionText` ADD `keywordObjects` TINYTEXT AFTER `meta_description`;

# CONTENT MANAGER/PROVIDER RIGHTS (PART 2)

# 03Feb03] - PAC
# Updates to allow built-in usergroups
ALTER TABLE `be_author2localrights` DROP `rights`;

DROP TABLE IF EXISTS `be_localrights`;
CREATE TABLE `be_localrights` (
  `rightID` smallint(5) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `perms` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`rightID`),
  UNIQUE KEY `rightID` (`rightID`),
  KEY `rightID_2` (`rightID`)
) TYPE=MyISAM COMMENT='BE: Implementing section/local level rights';

#
# Dumping data for table `be_localrights`
#

INSERT INTO `be_localrights` VALUES (0, 'Nobody', 'No special rights', 'nobody');
INSERT INTO `be_localrights` VALUES (1, 'ContentProvider', 'Post articles, modify their own articles', 'user,mailinglist,story,comment,submission');
INSERT INTO `be_localrights` VALUES (2, 'ContentManager', 'Post articles, modify any article, create sub-sections, assign Content Provider privilege', 'user,mailinglist,story,comment,submission,storyeditor,section,upload,block,author');
INSERT INTO `be_localrights` VALUES (3, 'User', 'Registered user of the CMS, registered for email', 'user,mailinglist');
INSERT INTO `be_localrights` VALUES (4, 'SuperUser', 'Full access throughout the site', 'user,topic,story,storyeditor,comment,section,link,gallery,submission,block,poll,author,variable,glossary,mailinglist,local,upload,logging,root');

# 4Feban03] - PAC
# Create user who can login using slashAuth rather than slashAuthCR
INSERT INTO `psl_author` VALUES (2, 'satan', '', 'http://www.hell.com', 'lucifer@heaven.org', 'For setups that use slashAuth.class', '7b5a86d8375961fe0808663e0d5ecd47', 1000000, 'user,topic,story,storyeditor,comment,section,link,gallery,submission,block,poll,author,variable,glossary,mailinglist,local,upload,logging,root');
UPDATE db_sequence SET nextid=2 WHERE seq_name='psl_author_seq';
ALTER TABLE `be_author2localrights` CHANGE `id` `id` SMALLINT( 5 ) UNSIGNED DEFAULT '0' NOT NULL AUTO_INCREMENT;

