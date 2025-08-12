
-- phpMyAdmin SQL Dump
-- version 2.6.3-pl1
-- http://www.phpmyadmin.net
-- 
-- Host: mysql08.powweb.com
-- Generation Time: Sep 18, 2005 at 12:54 PM
-- Server version: 4.1.9
-- PHP Version: 4.4.0
-- 
-- Database: `rickwtest`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `authors`
-- 

DROP TABLE IF EXISTS `authors`;
CREATE TABLE "authors" (
  "authorid" int(11) NOT NULL,
  "login" varchar(20) NOT NULL default '',
  "userpassword" varchar(32) NOT NULL default '',
  "authorname" varchar(50) default NULL,
  "authoremail" varchar(255) default NULL,
  "regdate" datetime default NULL,
  "usergroup" varchar(32) NOT NULL default '',
  "countrycode" char(2) default NULL,
  "language" char(2) default NULL,
  "phone" varchar(20) default NULL,
  "fax" varchar(20) default NULL,
  "address" varchar(100) default NULL,
  "city" varchar(50) default NULL,
  "state" varchar(50) default NULL,
  "zip" varchar(20) default NULL,
  "website" varchar(255) default NULL,
  "comments" text,
  "newsletter" char(1) default 'N',
  "privateemail" char(1) default 'N',
  "disuser" char(1) default 'N',
  PRIMARY KEY  ("authorid"),
  UNIQUE KEY "login" ("login")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `banners`
-- 

DROP TABLE IF EXISTS `banners`;
CREATE TABLE "banners" (
  "bannerid" int(11) NOT NULL,
  "bannerimage" varchar(255) default NULL,
  "bannerurl" varchar(255) default NULL,
  "banneralt" varchar(255) default NULL,
  "publishdate" datetime default NULL,
  "expiredate" datetime default NULL,
  "impressions" int(11) default NULL,
  "clicks" int(11) default NULL,
  "banneractive" char(1) default NULL,
  "bannerhtml" text,
  "authorid" int(11) NOT NULL default '1',
  PRIMARY KEY  ("bannerid")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `contents`
-- 

DROP TABLE IF EXISTS `contents`;
CREATE TABLE "contents" (
  "contentid" int(11) unsigned NOT NULL,
  "groupname" varchar(32) default NULL,
  "title" varchar(255) default NULL,
  "body" text,
  "publishdate" datetime default NULL,
  "expiredate" datetime default NULL,
  "contentactive" char(1) default 'Y',
  "teaser" text,
  "orderid" int(10) NOT NULL default '0',
  "authorid" int(11) default '1',
  "updatedate" datetime default NULL,
  "subgroupname" varchar(32) default NULL,
  "imagealign" char(1) default NULL,
  "image" varchar(255) default NULL,
  "headervisible" char(1) default 'Y',
  "authorvisible" char(1) default 'Y',
  "updatedatevisible" char(1) default 'Y',
  "headerimage" varchar(255) default NULL,
  "imagedetails" varchar(255) default NULL,
  "imagedetailsalign" char(1) default 'L',
  "leftright" char(1) default 'L',
  "contentname" varchar(32) default NULL,
  "language" char(2) default NULL,
  "canrate" char(1) default NULL,
  "cancomment" char(1) default NULL,
  "ratingtotal" int(11) NOT NULL default '0',
  "ratingvotes" int(11) NOT NULL default '0',
  "cbody" text,
  "cteaser" text,
  "printerfriendly" char(1) default 'N',
  "pdfprint" char(1) default 'N',
  "tellfriend" char(1) default 'N',
  "rssvisible" char(1) default 'Y',
  "searchvisible" char(1) default 'N',
  PRIMARY KEY  ("contentid"),
  UNIQUE KEY "contentref" ("contentname","language"),
  KEY "parentref" ("groupname","subgroupname","leftright","language")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `continents`
-- 

DROP TABLE IF EXISTS `continents`;
CREATE TABLE "continents" (
  "continentcode" varchar(4) NOT NULL default '',
  "continentname" varchar(32) NOT NULL default '',
  PRIMARY KEY  ("continentcode")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `countries`
-- 

DROP TABLE IF EXISTS `countries`;
CREATE TABLE "countries" (
  "countrycode" char(2) NOT NULL default '',
  "countryname" varchar(48) NOT NULL default '',
  "continent" varchar(4) NOT NULL default '',
  "flag" varchar(32) default NULL,
  PRIMARY KEY  ("countrycode"),
  KEY "countryname" ("countryname"),
  KEY "continent" ("continent","countrycode")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `filetypes`
-- 

DROP TABLE IF EXISTS `filetypes`;
CREATE TABLE "filetypes" (
  "filetypeid" int(11) NOT NULL,
  "filetype" varchar(8) NOT NULL default '',
  "mimetype" varchar(255) NOT NULL default '',
  "authorid" int(11) NOT NULL default '1',
  "filecat" varchar(12) NOT NULL default '',
  "fileicon" varchar(255) default NULL,
  PRIMARY KEY  ("filetypeid"),
  UNIQUE KEY "fileref" ("filecat","filetype")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `functiongroups`
-- 

DROP TABLE IF EXISTS `functiongroups`;
CREATE TABLE "functiongroups" (
  "groupname" varchar(32) NOT NULL default '',
  "grouporderid" int(4) NOT NULL default '0',
  "controlvar" varchar(32) default NULL,
  "controltype" char(2) default NULL,
  "controlvalue" varchar(16) default NULL,
  PRIMARY KEY  ("groupname","grouporderid")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `functions`
-- 

DROP TABLE IF EXISTS `functions`;
CREATE TABLE "functions" (
  "functionname" varchar(32) NOT NULL default '',
  "groupname" varchar(32) NOT NULL default '',
  "functionorderid" int(4) NOT NULL default '0',
  "controlvar" varchar(32) default NULL,
  "controltype" char(2) default NULL,
  "controlvalue" varchar(16) default NULL,
  PRIMARY KEY  ("groupname","functionorderid","functionname")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `groups`
-- 

DROP TABLE IF EXISTS `groups`;
CREATE TABLE "groups" (
  "groupid" int(11) unsigned NOT NULL,
  "groupdesc" varchar(100) default NULL,
  "grouplink" varchar(255) default NULL,
  "grouporderid" int(10) NOT NULL default '0',
  "menuimage1" varchar(255) default NULL,
  "menuimage2" varchar(255) default NULL,
  "menuvisible" char(1) default 'Y',
  "menuorderby" char(1) default '1',
  "menuorderdir" char(1) default 'A',
  "hovertitle" varchar(255) default NULL,
  "openinpage" char(1) default NULL,
  "topgroupname" varchar(32) default NULL,
  "loginreq" char(1) default NULL,
  "usergroups" varchar(255) default NULL,
  "groupname" varchar(32) default NULL,
  "language" char(2) NOT NULL default '',
  "menuimage3" varchar(255) default NULL,
  "menuimage4" varchar(255) default NULL,
  "authorid" int(11) NOT NULL default '0',
  "subgroupcount" int(11) NOT NULL default '0',
  PRIMARY KEY  ("groupid"),
  UNIQUE KEY "groupref" ("groupname","language"),
  KEY "parentref" ("topgroupname","language")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `imageformattemplates`
-- 

DROP TABLE IF EXISTS `imageformattemplates`;
CREATE TABLE "imageformattemplates" (
  "imageformatid" int(11) NOT NULL,
  "imageformatname" varchar(255) NOT NULL default '',
  "ifalign" char(1) default 'L',
  "ifborder" int(2) default '1',
  "ifbgcolor" varchar(255) default NULL,
  "authorid" int(11) NOT NULL default '0',
  PRIMARY KEY  ("imageformatid"),
  UNIQUE KEY "imageformatname" ("imageformatname")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `languages`
-- 

DROP TABLE IF EXISTS `languages`;
CREATE TABLE "languages" (
  "languagecode" char(2) NOT NULL default '',
  "languagename" varchar(24) NOT NULL default '',
  "charset" varchar(32) default NULL,
  "enabled" char(1) default 'N',
  "direction" char(3) default 'ltr',
  "translationby" varchar(255) default NULL,
  PRIMARY KEY  ("languagecode"),
  KEY "languagecode" ("enabled","languagecode")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `modules`
-- 

DROP TABLE IF EXISTS `modules`;
CREATE TABLE "modules" (
  "moduleid" int(11) NOT NULL,
  "modulename" varchar(32) NOT NULL default '',
  "extin" char(1) NOT NULL default 'E',
  "moduledirectory" varchar(255) NOT NULL default '',
  "modulescript" varchar(255) NOT NULL default '',
  "modulesubmit" char(1) default 'N',
  "hascats" enum('Y','N') NOT NULL default 'Y',
  PRIMARY KEY  ("moduleid"),
  UNIQUE KEY "module" ("modulename"),
  KEY "extin" ("extin")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `modulesettings`
-- 

DROP TABLE IF EXISTS `modulesettings`;
CREATE TABLE "modulesettings" (
  "modulename" varchar(32) NOT NULL default '',
  "settingname" varchar(50) NOT NULL default '',
  "settingvalue" text,
  PRIMARY KEY  ("modulename","settingname")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `ratings`
-- 

DROP TABLE IF EXISTS `ratings`;
CREATE TABLE "ratings" (
  "ratingid" int(11) NOT NULL,
  "authorid" varchar(32) NOT NULL default '',
  "contentname" varchar(32) NOT NULL default '',
  "rating" int(2) NOT NULL default '99',
  "comments" text,
  PRIMARY KEY  ("ratingid"),
  UNIQUE KEY "rated" ("authorid","contentname")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `sessions`
-- 

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE "sessions" (
  "SID" varchar(32) NOT NULL default '',
  "expiration" int(11) NOT NULL default '0',
  "sessvalue" text NOT NULL,
  PRIMARY KEY  ("SID")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `settings`
-- 

DROP TABLE IF EXISTS `settings`;
CREATE TABLE "settings" (
  "settingname" varchar(50) NOT NULL default '',
  "cssentry" char(1) default NULL,
  "settingvalue" text,
  PRIMARY KEY  ("settingname")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `sidebartemplates`
-- 

DROP TABLE IF EXISTS `sidebartemplates`;
CREATE TABLE "sidebartemplates" (
  "sidebarid" int(11) NOT NULL,
  "sidebarname" varchar(255) NOT NULL default '',
  "sbalign" char(1) default 'L',
  "sbborder" int(2) default '1',
  "sbbgcolor" varchar(255) default NULL,
  "sbwidth" varchar(4) NOT NULL default '50%',
  "authorid" int(11) NOT NULL default '0',
  PRIMARY KEY  ("sidebarid"),
  UNIQUE KEY "sidebarname" ("sidebarname")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `sites`
-- 

DROP TABLE IF EXISTS `sites`;
CREATE TABLE "sites" (
  "sitecode" varchar(16) NOT NULL default '',
  "sitename" varchar(64) NOT NULL default '',
  "sitedescription" text,
  "siteenabled" char(1) default NULL,
  PRIMARY KEY  ("sitecode")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `specialcontents`
-- 

DROP TABLE IF EXISTS `specialcontents`;
CREATE TABLE "specialcontents" (
  "scid" int(11) NOT NULL,
  "scname" varchar(32) NOT NULL default '',
  "sctitle" varchar(255) NOT NULL default '',
  "scdb" varchar(32) NOT NULL default '',
  "screg" char(1) default 'Y',
  "scvalid" char(1) default 'Y',
  "stextdisplay" char(1) default 'Y',
  "stext" varchar(255) default NULL,
  "sgraphicdisplay" char(1) default 'Y',
  "sgraphic" varchar(255) default NULL,
  "usergroups" varchar(255) default NULL,
  "scuseprefix" char(1) default 'N',
  "scusecategories" char(1) default 'N',
  "orderby" char(1) default 'D',
  "showpostedby" char(1) default 'N',
  "showposteddate" char(1) default 'N',
  "perpage" int(4) default '1',
  PRIMARY KEY  ("scid"),
  UNIQUE KEY "scname" ("scname")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `subgroups`
-- 

DROP TABLE IF EXISTS `subgroups`;
CREATE TABLE "subgroups" (
  "subgroupid" int(11) unsigned NOT NULL,
  "groupname" varchar(32) NOT NULL default '',
  "subgroupdesc" varchar(100) default NULL,
  "subgrouplink" varchar(255) default NULL,
  "subgrouporderid" int(10) NOT NULL default '0',
  "submenuimage1" varchar(255) default NULL,
  "submenuimage2" varchar(255) default NULL,
  "submenuvisible" char(1) default 'Y',
  "submenuorderby" char(1) default '1',
  "submenuorderdir" char(1) default 'A',
  "hovertitle" varchar(255) default NULL,
  "openinpage" char(1) default NULL,
  "loginreq" char(1) default NULL,
  "usergroups" varchar(255) default NULL,
  "subgroupname" varchar(32) default NULL,
  "language" char(2) NOT NULL default '',
  "submenuimage3" varchar(255) default NULL,
  "submenuimage4" varchar(255) default NULL,
  "authorid" int(11) NOT NULL default '0',
  PRIMARY KEY  ("subgroupid"),
  UNIQUE KEY "subgroupref" ("language","subgroupname"),
  KEY "parentref" ("groupname","language")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `tagcategories`
-- 

DROP TABLE IF EXISTS `tagcategories`;
CREATE TABLE "tagcategories" (
  "catid" int(11) unsigned NOT NULL,
  "catdesc" varchar(48) NOT NULL default '',
  "catname" varchar(32) NOT NULL default '',
  "language" char(2) NOT NULL default '',
  "authorid" int(11) NOT NULL default '1',
  PRIMARY KEY  ("catid"),
  UNIQUE KEY "catref" ("language","catname")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `tags`
-- 

DROP TABLE IF EXISTS `tags`;
CREATE TABLE "tags" (
  "tagid" int(11) NOT NULL,
  "tag" varchar(32) NOT NULL default '',
  "canedit" char(1) default NULL,
  "candelete" char(1) default NULL,
  "translation" text NOT NULL,
  "authorid" int(11) NOT NULL default '1',
  "cat" varchar(32) NOT NULL default '',
  PRIMARY KEY  ("tagid"),
  UNIQUE KEY "tagref" ("cat","tag")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `themes`
-- 

DROP TABLE IF EXISTS `themes`;
CREATE TABLE "themes" (
  "themecode" varchar(16) NOT NULL default '',
  "themename" varchar(64) NOT NULL default '',
  "themedescription" text,
  "themeenabled" char(1) default NULL,
  PRIMARY KEY  ("themecode")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `topgroups`
-- 

DROP TABLE IF EXISTS `topgroups`;
CREATE TABLE "topgroups" (
  "topgroupid" int(11) unsigned NOT NULL,
  "topgroupdesc" varchar(100) default NULL,
  "topgrouplink" varchar(255) default NULL,
  "topgrouporderid" int(10) NOT NULL default '0',
  "topmenuimage1" varchar(255) default NULL,
  "topmenuimage2" varchar(255) default NULL,
  "tophovertitle" varchar(255) default NULL,
  "topmenuvisible" char(1) default 'Y',
  "topmenuorderby" char(1) default '1',
  "topmenuorderdir" char(1) default 'A',
  "topopeninpage" char(1) default NULL,
  "loginreq" char(1) default NULL,
  "usergroups" varchar(255) default NULL,
  "topgroupname" varchar(32) default NULL,
  "language" char(2) NOT NULL default '',
  "topmenuimage3" varchar(255) default NULL,
  "topmenuimage4" varchar(255) default NULL,
  "authorid" int(11) NOT NULL default '0',
  "toptheme" varchar(16) default NULL,
  PRIMARY KEY  ("topgroupid"),
  UNIQUE KEY "topgroupref" ("language","topgroupname")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `userdata`
-- 

DROP TABLE IF EXISTS `userdata`;
CREATE TABLE "userdata" (
  "userdataname" varchar(32) NOT NULL default '',
  "userdataenabled" char(1) default NULL,
  "userdataorderid" int(2) NOT NULL default '0',
  "userdatavar" varchar(32) NOT NULL default '',
  "userdatatype" char(2) NOT NULL default '',
  "userdatavalue" varchar(16) NOT NULL default '',
  PRIMARY KEY  ("userdataname"),
  KEY "userdataorderid" ("userdataorderid","userdataname")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `usergroups`
-- 

DROP TABLE IF EXISTS `usergroups`;
CREATE TABLE "usergroups" (
  "usergroupid" int(11) unsigned NOT NULL,
  "usergroupdesc" varchar(48) NOT NULL default '',
  "usergroupname" varchar(32) NOT NULL default '',
  "language" char(2) NOT NULL default '',
  "authorid" int(11) NOT NULL default '1',
  PRIMARY KEY  ("usergroupid"),
  UNIQUE KEY "usergroupref" ("language","usergroupname")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `userprivileges`
-- 

DROP TABLE IF EXISTS `userprivileges`;
CREATE TABLE "userprivileges" (
  "refid" int(11) unsigned NOT NULL,
  "usergroupname" varchar(32) NOT NULL default '',
  "functionname" varchar(32) NOT NULL default '',
  "accessview" char(1) default NULL,
  "accessedit" char(1) default NULL,
  "accessadd" char(1) default NULL,
  "accessdelete" char(1) default NULL,
  "accesstranslate" char(1) default NULL,
  PRIMARY KEY  ("refid"),
  UNIQUE KEY "privilege" ("usergroupname","functionname")
);

-- --------------------------------------------------------

-- 
-- Table structure for table `visitorstats`
-- 

DROP TABLE IF EXISTS `visitorstats`;
CREATE TABLE "visitorstats" (
  "statid" int(11) unsigned NOT NULL,
  "site" varchar(16) NOT NULL default 'ALL',
  "visitdate" datetime NOT NULL default '2000-02-01 00:00:00',
  "visitorip" varchar(16) default NULL,
  "visitoragent" varchar(128) default NULL,
  "visitoros" varchar(32) default NULL,
  "visitorbrowser" varchar(32) default NULL,
  "visitorreferrer" varchar(128) default NULL,
  "country" varchar(32) default NULL,
  "countnumber" int(11) unsigned NOT NULL default '1',
  PRIMARY KEY  ("statid"),
  KEY "site" ("site","visitdate")
);
