# Adds table for storing optional fields for article records
# 
CREATE TABLE `be_articleTextOptions` (
  `articleID` smallint(5) unsigned NOT NULL,
  `languageID` char(3) NOT NULL default '',
  `options` text NOT NULL default '',
  `Author` varchar(255) NOT NULL default '', # Required to match enty in defautl_article_options
  PRIMARY KEY  (`articleID`,`languageID`)
) TYPE=MyISAM; 

