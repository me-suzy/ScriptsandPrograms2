#
# Table structure for table `pet_alert`
#
DROP TABLE IF EXISTS pet_alert;
CREATE TABLE pet_alert (
  `alertID` int(7) NOT NULL auto_increment,
  `sender` varchar(50) default NULL,
  `senderName` varchar(50) default NULL,
  `receiver` varchar(50) default NULL,
  `petitionID` int(7) NOT NULL default '0',
  `date` int(10) unsigned NOT NULL default '0',
  `IPaddress` varchar(30) NOT NULL default '0',
  PRIMARY KEY  (`alertID`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;


#
# Table structure for table `pet_country`
#
DROP TABLE IF EXISTS pet_country;
CREATE TABLE pet_country (
  `countryID` char(3) NOT NULL default '',
  `name` varchar(55) default NULL,
  PRIMARY KEY  (`countryID`)
) TYPE=MyISAM;


#
# Table structure for table `pet_data`
#
DROP TABLE IF EXISTS pet_data;
CREATE TABLE `pet_data` (
  `indID` int(7) NOT NULL default '0',
  `petitionID` int(7) NOT NULL default '0',
  `IPaddress` varchar(30) NOT NULL default '',
  `browser` varchar(50) NOT NULL default '',
  `comments` varchar(255) default NULL,
  `signedDateOld` date NOT NULL default '0000-00-00',
  `signedDate` int(10) unsigned NOT NULL default '0',
  `verified` tinyint(2) default NULL,
  `verifyDateOld` date NOT NULL default '0000-00-00',
  `verifyDate` int(10) unsigned NOT NULL default '0',
  `followupContact` tinyint(2) default NULL,
  `public` tinyint(2) default NULL,
  `genRandPassword` varchar(15) default NULL,
  `approved` tinyint(2) default NULL,
  PRIMARY KEY  (`indID`)
) TYPE=MyISAM;

#
# Table structure for table `pet_letters`
#
DROP TABLE IF EXISTS pet_letters;
CREATE TABLE `pet_letters` (
  `indID` int(7) NOT NULL default '0',
  `letterID` int(7) NOT NULL default '0',
  `randPassword` varchar(9) default NULL,
  `outreachDate` date default NULL,
  `confirmDate` date default NULL,
  PRIMARY KEY  (`letterID`,`indID`)
) TYPE=MyISAM;


#
# Table structure for table `pet_main`
#
DROP TABLE IF EXISTS pet_main;
CREATE TABLE `pet_main` (
  `indID` int(7) NOT NULL auto_increment,
  `firstName` varchar(30) NOT NULL default '',
  `middleName` varchar(30) NOT NULL default '',
  `lastName` varchar(30) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `organization` varchar(50) default NULL,
  `organizationVerified` char(1) default '0',
  `webSite` varchar(100) default NULL,
  `address` varchar(100) default NULL,
  `city` varchar(100) default NULL,
  `state` varchar(100) default NULL,
  `countryID` varchar(20) default NULL,
  `postalCode` varchar(20) default NULL,
  `PGP` varchar(20) default NULL,
  `birthNumber` varchar(20) default NULL,
  `contact` char(3) default NULL,
  `password` varchar(20) default NULL,
  `creationDateOld` date NOT NULL default '0000-00-00',
  `creationDate` int(10) unsigned NOT NULL default '0',
  `outreachDate` int(10) unsigned NOT NULL default '0',
  `outreachNumber` smallint(5) unsigned NOT NULL default '0',
  `outreachID` smallint(5) unsigned NOT NULL default '0',
  `rejectedMember` char(3) default NULL,
  `featuredMember` char(3) default NULL,
  PRIMARY KEY  (`indID`)
) TYPE=MyISAM AUTO_INCREMENT=1;


#
# Table structure for table `pet_petition`
#
DROP TABLE IF EXISTS pet_petition;
CREATE TABLE `pet_petition` (
  `petitionID` int(7) NOT NULL auto_increment,
  `URLname` varchar(20) default NULL,
  `author_id` int(11) NOT NULL default '0',
  `petitionAuthorName` varchar(255) default NULL,
  `petitionAuthorEmail` varchar(255) default NULL,
  `petitionAuthorOrganization` varchar(255) default NULL,
  `dateCreated` int(10) unsigned NOT NULL default '0',
  `dateModified` int(10) unsigned NOT NULL default '0',
  `dateAvailable` int(10) unsigned NOT NULL default '0',
  `dateRemoved` int(10) unsigned NOT NULL default '0',
  `dateEnded` int(10) unsigned default NULL,
  `hide` char(1) default '0',
  `restrict2members` int(1) default NULL,
  `priority` int(5) unsigned default NULL,
  `petitionCounter` smallint(10) unsigned not null default '0',
  `hitCounter` smallint(10) unsigned not null default '0',
  `sectionID` smallint(10) not null default '-1',
  PRIMARY KEY  (`petitionID`)
) TYPE=MyISAM AUTO_INCREMENT=5;


#
# Table structure for table `pet_petition2section`
#
DROP TABLE IF EXISTS pet_petition2section;
CREATE TABLE `pet_petition2section` (
  `sectionID` int(11) NOT NULL default '0',
  `petitionID` int(11) NOT NULL default '0'
) TYPE=MyISAM;


#
# Table structure for table `pet_petitionText`
#
DROP TABLE IF EXISTS pet_petitionText;
CREATE TABLE `pet_petitionText` (
  `petitionTextID` int(7) NOT NULL auto_increment,
  `petitionID` int(7) NOT NULL default '1',
  `languageID` char(3) NOT NULL default '',
  `title` varchar(255) default NULL,
  `title_source` varchar(255) default NULL,
  `blurb` text,
  `blurb_source` text,
  `content` text,
  `content_source` text,
  `credits` text,
  `credits_source` text,
  `support` text,
  `support_source` text,
  `faq` text,
  `faq_source` text,
  `meta_keywords` varchar(255) default NULL,
  `meta_description` varchar(255) default NULL,
  `template` varchar(55) default NULL,
  `confirmEmail` text,
  `alertEmail` text,
  `originalText` smallint(5) default NULL,
  `spotlight` int(1) default NULL,
  PRIMARY KEY  (`petitionTextID`),
  KEY `petitionTextID` (`petitionTextID`),
  KEY `petitionID` (`petitionID`),
  KEY `languageID` (`languageID`)
) TYPE=MyISAM AUTO_INCREMENT=2;

#
# Dumping data for table `pet_petitionText`
#
DROP TABLE IF EXISTS pet_petition2contact;
CREATE TABLE `pet_petition2contact` (
  `petitionID` smallint(5) unsigned NOT NULL default '0',
  `contactID` smallint(5) unsigned NOT NULL default '0',
  `targetID` smallint(5) unsigned default '0',
  `petitionComment` text NOT NULL,
  `followup` tinyint(5) default '0' NOT NULL,
  `public` tinyint(5) default '0',
  `organization` varchar(255) default NULL,
  `organizationalEndorsement` tinyint(5) default '0',
  `organizationApproved` tinyint(5) default '0',
  `dateDelivered` int(10) unsigned default '0',
  `dateVerified` int(10) unsigned default '0',
  `dateReminded` int(10) unsigned default '0',
  `browserInfo` varchar(255) NOT NULL default '',
  `IPaddress` varchar(50) NOT NULL default '',
  `randomKey` varchar(25) NOT NULL default '',
  `extraAttribute1` varchar(255) default NULL,
  `approved` tinyint(5) default '0',
  `verified` tinyint(5) default '0',
  PRIMARY KEY  (`contactID`,`petitionID`)
) TYPE=MyISAM;


#
# Table structure for table `be_followup`
#
DROP TABLE IF EXISTS be_followup;
CREATE TABLE `be_followup` (
  `followupID` int(10) NOT NULL auto_increment,
  `fromName` varchar(255) NOT NULL default '',
  `fromEmail` varchar(255) NOT NULL default '',
  `subject` varchar(255) NOT NULL default '',
  `message` text NOT NULL,
  `dateCreated` int(10) unsigned NOT NULL default '0',
  `dateModified` int(10) unsigned NOT NULL default '0',
  `dateAvailable` int(10) unsigned NOT NULL default '0',
  `dateRemoved` int(10) unsigned NOT NULL default '0',
  `type` varchar(255) default NULL,
  PRIMARY KEY  (`followupID`)
) TYPE=MyISAM AUTO_INCREMENT=1;

# --------------------------------------------------------


#
# Table structure for table `be_followup2contact`
# Modified 28Aug2003
#
DROP TABLE IF EXISTS be_followup2contact;
CREATE TABLE `be_followup2contact` (
  `id` int(10) NOT NULL auto_increment,
  `followupID` int(10) NOT NULL default '0',
  `contactID` int(10) NOT NULL default '0',
  `dateDelivered` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=2401;
# --------------------------------------------------------

#
# Table structure for table `be_followup2group`
#
DROP TABLE IF EXISTS be_followup2group;
CREATE TABLE `be_followup2group` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `groupType` char(5) NOT NULL default '',
  `followupID` int(10) NOT NULL default '0',
  `groupID` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1;
