CREATE TABLE `acronym` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `badip` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `badword` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `binfile` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `mimetype` varchar(50) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `revision` int(11) NOT NULL default '1',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `category` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `datatype` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `class` (
  `site` int(11) NOT NULL default '0',
  `app` varchar(50) NOT NULL default '',
  `type` int(11) NOT NULL default '0',
  `datatype` varchar(50) NOT NULL default '',
  `basedatatype` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`app`,`datatype`),
  KEY `combi` (`type`,`app`,`site`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `classname` (
  `datatype` varchar(50) NOT NULL default '',
  `language` char(3) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`datatype`,`language`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `company` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `address1` varchar(100) NOT NULL default '',
  `address2` varchar(100) NOT NULL default '',
  `address3` varchar(100) NOT NULL default '',
  `address4` varchar(100) NOT NULL default '',
  `postalcode` varchar(50) NOT NULL default '',
  `city` varchar(50) NOT NULL default '',
  `state` varchar(50) NOT NULL default '',
  `country` varchar(50) NOT NULL default '',
  `telephone1` varchar(50) NOT NULL default '',
  `telephone2` varchar(50) NOT NULL default '',
  `telephone3` varchar(50) NOT NULL default '',
  `telefax` varchar(50) NOT NULL default '',
  `website` varchar(100) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `timezone` varchar(10) NOT NULL default '',
  `comment` mediumtext NOT NULL,
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `contact` (
  `objectid` int(11) NOT NULL default '0',
  `companyid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `address1` varchar(100) NOT NULL default '',
  `address2` varchar(100) NOT NULL default '',
  `address3` varchar(100) NOT NULL default '',
  `address4` varchar(100) NOT NULL default '',
  `postalcode` varchar(50) NOT NULL default '',
  `city` varchar(50) NOT NULL default '',
  `state` varchar(50) NOT NULL default '',
  `country` varchar(50) NOT NULL default '',
  `binfile1` int(11) NOT NULL default '0',
  `telephone1` varchar(50) NOT NULL default '',
  `telephone2` varchar(50) NOT NULL default '',
  `telephone3` varchar(50) NOT NULL default '',
  `telefax` varchar(50) NOT NULL default '',
  `website` varchar(100) NOT NULL default '',
  `email1` varchar(100) NOT NULL default '',
  `email2` varchar(100) NOT NULL default '',
  `email3` varchar(100) NOT NULL default '',
  `timezone` varchar(10) NOT NULL default '',
  `birthday` varchar(10) NOT NULL default '',
  `comment` mediumtext NOT NULL,
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `currency` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `currency` decimal(15,5) NOT NULL default '0.00000',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `customer` (
  `objectid` int(11) NOT NULL default '0',
  `companyid` int(11) NOT NULL default '0',
  `contactid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `address1` varchar(255) NOT NULL default '',
  `address2` varchar(255) NOT NULL default '',
  `address3` varchar(255) NOT NULL default '',
  `address4` varchar(255) NOT NULL default '',
  `postalcode` varchar(50) NOT NULL default '',
  `city` varchar(50) NOT NULL default '',
  `state` varchar(50) NOT NULL default '',
  `country` varchar(50) NOT NULL default '',
  `telephone1` varchar(50) NOT NULL default '',
  `telephone2` varchar(50) NOT NULL default '',
  `telephone3` varchar(50) NOT NULL default '',
  `telefax` varchar(50) NOT NULL default '',
  `website` varchar(255) NOT NULL default '',
  `email1` varchar(100) NOT NULL default '',
  `email2` varchar(100) NOT NULL default '',
  `email3` varchar(100) NOT NULL default '',
  `comment` mediumtext NOT NULL,
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `dbversion` (
  `dbversion` int(11) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `document` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `alias` varchar(50) NOT NULL default '',
  `templateid` int(11) NOT NULL default '0',
  `stylesheetid` int(11) NOT NULL default '0',
  `metadataid` int(11) NOT NULL default '0',
  `structureid` int(11) NOT NULL default '0',
  `nolist` tinyint(4) NOT NULL default '0',
  `nosearch` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `document_statistics` (
  `sessionid` varchar(32) NOT NULL default '',
  `userid` int(11) NOT NULL default '0',
  `timestamp` datetime NOT NULL default '0000-00-00 00:00:00',
  `site` int(11) NOT NULL default '0',
  `pageid` int(11) NOT NULL default '0',
  `ip` varchar(15) NOT NULL default '',
  `useragent` varchar(60) NOT NULL default '',
  `browser` varchar(15) NOT NULL default '',
  `version` varchar(5) NOT NULL default '',
  `maj_ver` varchar(5) NOT NULL default '',
  `min_ver` varchar(5) NOT NULL default '',
  `letter_ver` varchar(5) NOT NULL default '',
  `javascript` varchar(5) NOT NULL default '',
  `platform` varchar(15) NOT NULL default '',
  `os` varchar(15) NOT NULL default '',
  `browserlanguage` varchar(7) NOT NULL default '',
  `userlanguage` varchar(5) NOT NULL default '',
  `usercreated` tinyint(4) NOT NULL default '0',
  `referer` varchar(255) NOT NULL default '',
  `generated` double NOT NULL default '0',
  `host` varchar(255) NOT NULL default '',
  `tld` varchar(10) NOT NULL default '',
  KEY `timestamp` (`timestamp`),
  KEY `sessionid` (`sessionid`),
  KEY `userid` (`userid`),
  KEY `site` (`site`),
  KEY `pageid` (`pageid`),
  KEY `ip` (`ip`),
  KEY `host` (`host`),
  KEY `tld` (`tld`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `documentsection` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `content` mediumtext NOT NULL,
  `extension` varchar(255) NOT NULL default '',
  `configset` varchar(255) NOT NULL default '',
  `subname` mediumtext NOT NULL,
  `params` varchar(255) NOT NULL default '',
  `script` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`),
  FULLTEXT KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `documentsection_modules` (
  `documentsectionid` int(11) NOT NULL default '0',
  `type` varchar(50) NOT NULL default '',
  `configset` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`documentsectionid`,`type`,`configset`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `edocument_edoccorrection` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `edocument_edocerrorcode` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `edocument_edocform` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `errorcodeid` int(11) NOT NULL default '0',
  `correctionid` int(11) NOT NULL default '0',
  `responsibleid` int(11) NOT NULL default '0',
  `cno` varchar(255) NOT NULL default '',
  `cname` varchar(255) NOT NULL default '',
  `caddress` varchar(255) NOT NULL default '',
  `cpostalcode` varchar(25) NOT NULL default '',
  `ccity` varchar(255) NOT NULL default '',
  `ccountryid` int(11) NOT NULL default '0',
  `ccontact` varchar(255) NOT NULL default '',
  `cphone` varchar(255) NOT NULL default '',
  `cemail` varchar(255) NOT NULL default '',
  `comment1` mediumtext NOT NULL,
  `comment2` mediumtext NOT NULL,
  `comment3` mediumtext NOT NULL,
  `status` int(11) NOT NULL default '0',
  `handleid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `edocument_edocresponsible` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `emoticon` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `eproject_layout` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `eproject_layoutelement` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `eproject_project` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `content` mediumblob NOT NULL,
  `uservarchar1` varchar(255) NOT NULL default '',
  `uservarchar2` varchar(255) NOT NULL default '',
  `uservarchar3` varchar(255) NOT NULL default '',
  `uservarchar4` varchar(255) NOT NULL default '',
  `uservarchar5` varchar(255) NOT NULL default '',
  `usertext1` mediumblob NOT NULL,
  `usertext2` mediumblob NOT NULL,
  `usertext3` mediumblob NOT NULL,
  `status` int(11) NOT NULL default '0',
  PRIMARY KEY  (`objectid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `eproject_projectelement` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) default NULL,
  `content` mediumtext NOT NULL,
  `comment` mediumtext NOT NULL,
  `status` int(11) NOT NULL default '0',
  `dato1` varchar(50) NOT NULL default '01-01-2004',
  `dato2` varchar(50) NOT NULL default '01-01-2004',
  `messageto` int(11) NOT NULL default '0',
  PRIMARY KEY  (`objectid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `erek_comp` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `itemtext` varchar(255) NOT NULL default '',
  `itemnum` varchar(255) NOT NULL default '',
  `compunitid` int(11) NOT NULL default '0',
  `compcauseid` int(11) NOT NULL default '0',
  `description` varchar(255) NOT NULL default '',
  `cno` varchar(255) NOT NULL default '',
  `cname` varchar(255) NOT NULL default '',
  `caddress` varchar(255) NOT NULL default '',
  `cpostalcode` varchar(255) NOT NULL default '',
  `ccity` varchar(255) NOT NULL default '',
  `compcountryid` int(11) NOT NULL default '0',
  `ccontact` varchar(255) NOT NULL default '',
  `cemail` varchar(255) NOT NULL default '',
  `compsolutionid` int(11) NOT NULL default '0',
  `compdecisionid` int(11) NOT NULL default '0',
  `credit` varchar(255) NOT NULL default '',
  `cost` varchar(255) NOT NULL default '',
  `comment` varchar(255) NOT NULL default '',
  `compdepartmentid` int(11) NOT NULL default '0',
  `comment1` varchar(255) NOT NULL default '',
  `closedtime` datetime NOT NULL default '0000-00-00 00:00:00',
  `closedby` int(11) NOT NULL default '0',
  `status` int(11) NOT NULL default '0',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `erek_compcause` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `erek_compcountry` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `erek_compdecision` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `erek_compdepartment` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `erek_compsolution` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `erek_compunit` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `event` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `subject` varchar(255) NOT NULL default '',
  `content` mediumblob NOT NULL,
  `triggertype` varchar(50) NOT NULL default '',
  `triggerevent` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `ext_forum` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `ext_forumdata` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `content` mediumtext NOT NULL,
  `uname` varchar(255) NOT NULL default '',
  `numread` int(11) NOT NULL default '0',
  `lastreply` datetime NOT NULL default '0000-00-00 00:00:00',
  `numreply` int(11) NOT NULL default '0',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `ext_search` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `pageid_result` int(11) NOT NULL default '0',
  `templateid_search` int(11) NOT NULL default '0',
  `templateid_result` int(11) NOT NULL default '0',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `extradata` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `extradata_data` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `type` varchar(50) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `sortorder` int(11) NOT NULL default '0',
  KEY `objectid` (`objectid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `filter` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `datatype` varchar(50) NOT NULL default '',
  `content` mediumblob NOT NULL,
  `mimetype` varchar(50) NOT NULL default '',
  `classtype` varchar(50) NOT NULL default '',
  `filtertype` int(11) NOT NULL default '0',
  `filterfiletype` varchar(10) NOT NULL default '',
  `binfileid` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `folder` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `frame` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `pageid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`objectid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `freight` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `init` decimal(10,2) NOT NULL default '0.00',
  `perweight` decimal(10,2) NOT NULL default '0.00',
  `vatid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`objectid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `item` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `content1` mediumtext NOT NULL,
  `content2` mediumtext NOT NULL,
  `content3` mediumtext NOT NULL,
  `price` decimal(10,2) NOT NULL default '0.00',
  `vatid` int(11) NOT NULL default '0',
  `image1` int(11) NOT NULL default '0',
  `image2` int(11) NOT NULL default '0',
  `image3` int(11) NOT NULL default '0',
  `exstr1` varchar(255) NOT NULL default '',
  `exstr2` varchar(255) NOT NULL default '',
  `exstr3` varchar(255) NOT NULL default '',
  `exstr4` varchar(255) NOT NULL default '',
  `exstr5` varchar(255) NOT NULL default '',
  `exstr6` varchar(255) NOT NULL default '',
  `exstr7` varchar(255) NOT NULL default '',
  `exstr8` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `itemgroup` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `letter` (
  `objectid` int(11) NOT NULL default '0',
  `contactid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `content` mediumtext NOT NULL,
  PRIMARY KEY  (`objectid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `listcol` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `makedefault` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `listcol_data` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `sortorder` int(11) NOT NULL default '0',
  KEY `objectid` (`objectid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `meeting` (
  `objectid` int(11) NOT NULL default '0',
  `companyid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `visitdate` varchar(100) NOT NULL default '',
  `content` mediumtext NOT NULL,
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `message` (
  `objectid` int(11) NOT NULL default '0',
  `touser` int(11) NOT NULL default '0',
  `fromuser` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `content` mediumtext NOT NULL,
  `readstatus` tinyint(4) NOT NULL default '0',
  `messagetype` varchar(100) NOT NULL default '',
  KEY `objectid` (`objectid`),
  KEY `touser` (`touser`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `metadata` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `keyword` varchar(255) NOT NULL default '',
  `copyright` varchar(255) NOT NULL default '',
  `publisher` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `note` (
  `objectid` int(11) NOT NULL default '0',
  `name` mediumtext NOT NULL default '',
  PRIMARY KEY  (`objectid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `object` (
  `objectid` int(11) NOT NULL auto_increment,
  `site` int(11) NOT NULL default '0',
  `type` varchar(50) NOT NULL default '',
  `createdby` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `changedby` int(11) NOT NULL default '0',
  `changed` datetime NOT NULL default '0000-00-00 00:00:00',
  `checkedby` int(11) NOT NULL default '0',
  `checked` datetime NOT NULL default '0000-00-00 00:00:00',
  `publish` datetime NOT NULL default '0000-00-00 00:00:00',
  `expire` datetime NOT NULL default '0000-00-00 00:00:00',
  `childorder` int(11) NOT NULL default '0',
  `parentid` int(11) NOT NULL default '0',
  `language` varchar(5) NOT NULL default '',
  `approved` tinyint(4) NOT NULL default '1',
  `active` tinyint(4) NOT NULL default '1',
  `readonly` tinyint(4) NOT NULL default '0',
  `haschild` tinyint(4) NOT NULL default '0',
  `haspermission` tinyint(4) NOT NULL default '0',
  `deleted` tinyint(4) NOT NULL default '0',
  `futurerevisionof` int(11) NOT NULL default '0',
  `hasfuturerevision` tinyint(4) NOT NULL default '0',
  `hascategory` tinyint(4) NOT NULL default '0',
  `hasextradata` tinyint(4) NOT NULL default '0',
  `hasrules` tinyint(4) NOT NULL default '0',
  `hasvariant` tinyint(4) NOT NULL default '0',
  `variantof` int(11) NOT NULL default '0',
  `oldrevisionof` int(11) NOT NULL default '0',
  `hasoldrevision` tinyint(4) NOT NULL default '0',
  `standard` tinyint(4) NOT NULL default '0',
  `webhidden` tinyint(4) NOT NULL default '0',
  `syshidden` tinyint(4) NOT NULL default '0',
  `useapp` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `parentid` (`parentid`),
  KEY `site` (`site`),
  KEY `site_parentid` (`site`,`parentid`),
  KEY `site_objectid` (`site`,`objectid`),
  KEY `variantof` (`variantof`),
  KEY `site_type` (`site`,`type`),
  KEY `syslist` (`site`,`type`,`parentid`,`syshidden`,`variantof`,`futurerevisionof`,`oldrevisionof`,`deleted`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `object_access` (
  `objectid` int(11) NOT NULL default '0',
  `user_read` int(11) NOT NULL default '0',
  `user_write` int(11) NOT NULL default '0',
  `group_read` int(11) NOT NULL default '0',
  `group_write` int(11) NOT NULL default '0',
  KEY `objectid` (`objectid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `object_category` (
  `objectid` int(11) NOT NULL default '0',
  `categoryid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`objectid`,`categoryid`),
  KEY `categoryid` (`categoryid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `object_dependency` (
  `objectid` int(11) NOT NULL default '0',
  `dependson` int(11) NOT NULL default '0',
  KEY `objectid` (`objectid`,`dependson`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `object_extradata` (
  `objectid` int(11) NOT NULL default '0',
  `field` varchar(255) NOT NULL default '',
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`,`field`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `object_multiple` (
  `objectid` int(11) NOT NULL default '0',
  `field` varchar(255) NOT NULL default '',
  `value` varchar(255) NOT NULL default '',
  KEY `objectid` (`objectid`),
  KEY `field` (`field`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `object_search` (
  `objectid` int(11) NOT NULL default '0',
  `fieldname` varchar(255) NOT NULL default '',
  `fieldvalue` int(11) NOT NULL default '0',
  `fieldrep` mediumtext NOT NULL,
  `language` varchar(5) NOT NULL default '',
  `variantof` int(11) NOT NULL default '0',
  FULLTEXT KEY `fieldrep` (`fieldrep`),
  KEY `objectid` (`objectid`),
  KEY `fieldname` (`fieldname`),
  KEY `fieldvalue` (`fieldvalue`),
  KEY `language` (`language`),
  KEY `variantof` (`variantof`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `object_statistics` (
  `sessionid` varchar(32) NOT NULL default '',
  `userid` int(11) NOT NULL default '0',
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `ip` varchar(15) NOT NULL default '',
  `objectid` int(11) NOT NULL default '0',
  `action` int(11) NOT NULL default '0',
  `result` int(11) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `object_variantfield` (
  `objectid` int(11) NOT NULL default '0',
  `field` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`,`field`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `payment` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `init` decimal(10,2) NOT NULL default '0.00',
  `percentage` decimal(10,2) NOT NULL default '0.00',
  `vatid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `profile` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `profile_data` (
  `objectid` int(11) NOT NULL default '0',
  `type` varchar(50) NOT NULL default '',
  `view` varchar(50) NOT NULL default '',
  KEY `objectid` (`objectid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `savedsearch` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `class` varchar(50) NOT NULL default '',
  `content` mediumtext NOT NULL,
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `shoporder` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `shippingid` int(11) NOT NULL default '0',
  `paymentid` int(11) NOT NULL default '0',
  `customerid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `totalsum` decimal(10,2) NOT NULL default '0.00',
  `totaldiscsum` decimal(10,2) NOT NULL default '0.00',
  `totalactsum` decimal(10,2) NOT NULL default '0.00',
  `totalsumvat` decimal(10,2) NOT NULL default '0.00',
  `totaldiscsumvat` decimal(10,2) NOT NULL default '0.00',
  `totalactsumvat` decimal(10,2) NOT NULL default '0.00',
  `total` decimal(10,2) NOT NULL default '0.00',
  `totalvat` decimal(10,2) NOT NULL default '0.00',
  `shippingprice` decimal(10,2) NOT NULL default '0.00',
  `shippingpricevat` decimal(10,2) NOT NULL default '0.00',
  `paymentprice` decimal(10,2) NOT NULL default '0.00',
  `paymentpricevat` decimal(10,2) NOT NULL default '0.00',
  `totalsumcur` decimal(10,2) NOT NULL default '0.00',
  `totaldiscsumcur` decimal(10,2) NOT NULL default '0.00',
  `totalactsumcur` decimal(10,2) NOT NULL default '0.00',
  `totalsumcurvat` decimal(10,2) NOT NULL default '0.00',
  `totaldiscsumcurvat` decimal(10,2) NOT NULL default '0.00',
  `totalactsumcurvat` decimal(10,2) NOT NULL default '0.00',
  `totalcur` decimal(10,2) NOT NULL default '0.00',
  `totalcurvat` decimal(10,2) NOT NULL default '0.00',
  `shippingpricecur` decimal(10,2) NOT NULL default '0.00',
  `shippingpricecurvat` decimal(10,2) NOT NULL default '0.00',
  `paymentpricecur` decimal(10,2) NOT NULL default '0.00',
  `paymentpricecurvat` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `shoporderline` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `itemtext` varchar(255) NOT NULL default '',
  `itemnum` varchar(10) NOT NULL default '',
  `itemid` int(11) NOT NULL default '0',
  `orderid` int(11) NOT NULL default '0',
  `price` decimal(10,2) NOT NULL default '0.00',
  `disc` decimal(10,2) NOT NULL default '0.00',
  `actprice` decimal(10,2) NOT NULL default '0.00',
  `actpricecur` decimal(10,2) NOT NULL default '0.00',
  `pricevat` decimal(10,2) NOT NULL default '0.00',
  `discvat` decimal(10,2) NOT NULL default '0.00',
  `actpricevat` decimal(10,2) NOT NULL default '0.00',
  `actpricecurvat` decimal(10,2) NOT NULL default '0.00',
  `sumprice` decimal(10,2) NOT NULL default '0.00',
  `sumdisc` decimal(10,2) NOT NULL default '0.00',
  `sumactprice` decimal(10,2) NOT NULL default '0.00',
  `sumactpricecur` decimal(10,2) NOT NULL default '0.00',
  `sumpricevat` decimal(10,2) NOT NULL default '0.00',
  `sumdiscvat` decimal(10,2) NOT NULL default '0.00',
  `sumactpricevat` decimal(10,2) NOT NULL default '0.00',
  `sumactpricecurvat` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `site` (
  `site` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `website_path` varchar(255) NOT NULL default '',
  `website_url` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `sortcol` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `makedefault` int(11) NOT NULL default '0',
  PRIMARY KEY  (`objectid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `sortcol_data` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `way` varchar(10) NOT NULL default '',
  `sortorder` int(11) NOT NULL default '0',
  KEY `objectid` (`objectid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `staticbinfile` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `staticfolder` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `statistics_login` (
  `sessionid` varchar(32) NOT NULL default '',
  `userid` int(11) NOT NULL default '0',
  `timestamp` datetime NOT NULL default '0000-00-00 00:00:00',
  `site` int(11) NOT NULL default '0',
  `username` varchar(255) NOT NULL default '',
  `ip` varchar(15) NOT NULL default '',
  `website` tinyint(4) NOT NULL default '0',
  `failed` tinyint(4) NOT NULL default '0',
  KEY `timestamp` (`timestamp`),
  KEY `sessionid` (`sessionid`),
  KEY `userid` (`userid`),
  KEY `site` (`site`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `stfilebinfile` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `stfilefolder` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `stimgbinfile` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `stimgfolder` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `structure` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `templateid` int(11) NOT NULL default '0',
  `cachelifetime` int(11) NOT NULL default '0',
  `cacheallowed` int(11) NOT NULL default '0',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `structureelement` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `pageid` int(11) NOT NULL default '0',
  `showtype` tinyint(4) NOT NULL default '0',
  `url` varchar(255) NOT NULL default '',
  `templateid` int(11) NOT NULL default '0',
  `image1` varchar(255) NOT NULL default '',
  `image2` varchar(255) NOT NULL default '',
  `image3` varchar(255) NOT NULL default '',
  `structureid` int(11) NOT NULL default '0',
  `target` varchar(50) NOT NULL default '',
  `binfile1` int(11) NOT NULL default '0',
  `binfile2` int(11) NOT NULL default '0',
  `binfile3` int(11) NOT NULL default '0',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `stylesheets` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `content` mediumblob NOT NULL,
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `system_colors` (
  `id` int(4) NOT NULL auto_increment,
  `navn` varchar(25) NOT NULL default '',
  `hexcode` varchar(7) NOT NULL default '',
  `show_default` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `navn` (`navn`,`show_default`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `system_country` (
  `country` varchar(50) NOT NULL default '',
  `countrycode` varchar(5) NOT NULL default '',
  PRIMARY KEY  (`country`),
  KEY `countrycode` (`countrycode`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `system_fonts` (
  `id` tinyint(4) NOT NULL auto_increment,
  `navn` varchar(25) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id_2` (`id`),
  UNIQUE KEY `navn` (`navn`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `system_languages` (
  `language` varchar(30) NOT NULL default '',
  `langcode` char(3) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `task` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `companyid` int(11) NOT NULL default '0',
  `content` mediumblob NOT NULL,
  `priority` int(11) NOT NULL default '0',
  `status` int(11) NOT NULL default '0',
  `typecode` int(11) NOT NULL default '0',
  `budget` decimal(10,2) NOT NULL default '0.00',
  `offer` decimal(10,2) NOT NULL default '0.00',
  `expstart` date NOT NULL default '0000-00-00',
  `expfinish` date NOT NULL default '0000-00-00',
  `finished` date NOT NULL default '0000-00-00',
  `taskid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `template_include` (
  `templateid` int(11) NOT NULL default '0',
  `includeid` int(11) NOT NULL default '0',
  KEY `templateid` (`templateid`),
  KEY `includeid` (`includeid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `template_modules` (
  `template` int(11) NOT NULL default '0',
  `type` varchar(50) NOT NULL default '',
  `configset` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`template`,`type`,`configset`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `templates` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `content` mediumblob NOT NULL,
  `filename` varchar(255) NOT NULL default '',
  `param` mediumblob NOT NULL,
  `setting` mediumblob NOT NULL,
  `tpltype` int(11) NOT NULL default '0',
  `header` mediumblob NOT NULL,
  `style` mediumblob NOT NULL,
  `doctype` int(11) NOT NULL default '0',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `user` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `password` varchar(255) NOT NULL default '',
  `realname` varchar(255) NOT NULL default '',
  `rootdir` varchar(255) NOT NULL default '',
  `profileid` int(11) NOT NULL default '0',
  `objectlanguage` varchar(5) NOT NULL default 'EN',
  `guilanguage` varchar(5) NOT NULL default 'EN',
  `old_hidden` tinyint(4) NOT NULL default '0',
  `restrictlanguage` tinyint(4) NOT NULL default '0',
  `email` varchar(100) NOT NULL default '',
  `country` varchar(5) NOT NULL default '',
  `exstr1` varchar(255) NOT NULL default '',
  `exstr2` varchar(255) NOT NULL default '',
  `exstr3` varchar(255) NOT NULL default '',
  `exstr4` varchar(255) NOT NULL default '',
  `exstr5` varchar(255) NOT NULL default '',
  `exstr6` varchar(255) NOT NULL default '',
  `exstr7` varchar(255) NOT NULL default '',
  `exstr8` varchar(255) NOT NULL default '',
  `app` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`),
  KEY `hidden` (`old_hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `usergroup` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `level` int(11) NOT NULL default '0',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `usergroupmember` (
  `groupid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`groupid`,`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `vat` (
  `objectid` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `vat` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`objectid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
INSERT INTO `class` VALUES (0,'',0,'document',''),(0,'',0,'documentsection',''),(0,'',0,'structure',''),(0,'',0,'structureelement',''),(0,'',0,'template',''),(0,'',0,'stylesheet',''),(0,'',0,'user',''),(0,'',0,'usergroup',''),(0,'',0,'metadata',''),(0,'',0,'category',''),(0,'',1,'menu',''),(0,'',1,'picturecatalog',''),(0,'',1,'search',''),(0,'',1,'forum',''),(0,'',0,'frame',''),(0,'',1,'forgottenpassword',''),(0,'',0,'profile',''),(0,'',0,'badip',''),(0,'',0,'badword',''),(0,'',0,'acronym',''),(0,'',0,'emoticon',''),(0,'',1,'breadcrumb',''),(0,'',0,'message',''),(0,'',0,'extradata',''),(0,'',0,'filter',''),(0,'',0,'binfile',''),(0,'',0,'folder',''),(0,'',5,'sys',''),(0,'',0,'task',''),(0,'',0,'company',''),(0,'',0,'contact',''),(0,'',0,'meeting',''),(0,'',0,'letter',''),(0,'',0,'item',''),(0,'',0,'itemgroup',''),(0,'',1,'shop',''),(0,'',0,'customer',''),(0,'',0,'shoporder',''),(0,'',0,'shoporderline',''),(0,'',0,'currency',''),(0,'',0,'vat',''),(0,'',0,'freight',''),(0,'',0,'payment',''),(0,'',1,'login',''),(0,'',0,'event',''),(0,'',0,'note',''),(0,'',0,'listcol',''),(0,'',1,'forumdata','forum'),(0,'erek',0,'compcountry',''),(0,'ssa',0,'model',''),(0,'ssa',0,'color',''),(0,'ssa',0,'fuel',''),(0,'ssa',0,'transmission',''),(0,'ssa',0,'problem',''),(0,'ssa',0,'cause',''),(0,'ssa',0,'bdcountry',''),(0,'ssa',0,'supplier',''),(0,'ssa',0,'district',''),(0,'ssa',0,'network',''),(0,'ssa',0,'function',''),(0,'ssa',0,'suppliercontact',''),(0,'ssa',0,'cust',''),(0,'ssa',0,'coverage',''),(0,'ssa',0,'product',''),(0,'ssa',0,'agreement',''),(0,'ssa',0,'client',''),(0,'ssa',0,'case',''),(0,'ssa',0,'vehicletype',''),(0,'ssa',0,'caseaction',''),(0,'ssa',0,'vehiclemake',''),(0,'erek',0,'compdepartment',''),(0,'erek',0,'compdecision',''),(0,'erek',0,'compcause',''),(0,'ssa',0,'casenote',''),(0,'ssa',0,'log',''),(0,'',1,'changepassword',''),(0,'',1,'index',''),(0,'',1,'indexadv',''),(0,'',1,'sitemap',''),(0,'',1,'slide',''),(0,'',1,'register',''),(0,'',1,'bulletinboard',''),(0,'eproject',0,'layout',''),(0,'eproject',0,'layoutelement',''),(0,'eproject',0,'project',''),(0,'eproject',0,'projectelement',''),(0,'erek',0,'comp',''),(0,'ah',0,'produktion',''),(0,'dxp',0,'dxplayout',''),(0,'dxp',0,'dxplayoutelement',''),(0,'dxp',0,'dxpproject',''),(0,'dxp',0,'dxpprojectelement',''),(0,'erek',0,'compsolution',''),(0,'erek',0,'compunit',''),(0,'erek_club8',0,'compveneer',''),(0,'erek_club8',0,'compsurface',''),(0,'erek_club8',0,'comprejectcause',''),(0,'edocument',0,'edocform',''),(0,'edocument',0,'edoccorrection',''),(0,'edocument',0,'edocerrorcode',''),(0,'edocument',0,'edocresponsible',''),(0,'',0,'staticfolder',''),(0,'',0,'staticbinfile',''),(0,'',0,'stimgfolder',''),(0,'',0,'stimgbinfile',''),(0,'',0,'stfilefolder',''),(0,'',0,'stfilebinfile',''),(0,'dxp',0,'dxptime',''),(0,'',0,'sortcol',''),(0,'ipw',0,'ipwtodo',''),(0,'ipw',0,'ipwtask',''),(0,'',0,'savedsearch',''),(0,'schur',0,'schurcase',''),(0,'schur',0,'schurdetail',''),(0,'schur',0,'schurpolice',''),(0,'schur',0,'schurbil',''),(0,'schur',0,'schurcause',''),(0,'schur',0,'schurkontrol',''),(0,'erek_club8',0,'compcause',''),(0,'erek_club8',0,'comp',''),(0,'erek_club8',0,'compcountry',''),(0,'erek_club8',0,'compdecision',''),(0,'erek_club8',0,'compdepartment',''),(0,'erek_club8',0,'compsolution',''),(0,'erek_club8',0,'compunit',''),(0,'rtx',0,'rtxcomp',''),(0,'rtx',0,'rtxfejl',''),(0,'rtx',0,'rtxhandling',''),(0,'rtx',0,'rtxkontrol',''),(0,'rtx',0,'rtxsped',''),(0,'rtx',0,'rtxtilstand',''),(0,'rtx',0,'rtxservicehandling',''),(0,'rtx',0,'rtxlog','');
INSERT INTO `dbversion` VALUES (200);
INSERT INTO `system_colors` VALUES (1,'black','#000000',1),(2,'navy','#000080',1),(3,'blue','#0000FF',1),(4,'green','#008000',1),(5,'teal','#008080',1),(6,'lime','#00FF00',1),(7,'aqua','#00FFFF',1),(8,'maroon','#800000',1),(9,'purple','#800080',1),(10,'olive','#808000',1),(11,'gray','#808080',1),(12,'silver','#C0C0C0',1),(13,'red','#FF0000',1),(14,'fuchsia','#FF00FF',1),(15,'yellow','#FFFF00',1),(16,'white','#FFFFFF',1),(17,'aliceblue','#F0F8FF',0),(18,'antiquewhite','#FAEBD7',0),(19,'aqua','#00FFFF',0),(20,'aquamarine','#7FFFD4',0),(21,'azure','#F0FFFF',0),(22,'beige','#F5F5DC',0),(23,'bisque','#FFE4C4',0),(24,'black','#000000',0),(25,'blanchedalmond','#FFEBCD',0),(26,'blue','#0000FF',0),(27,'blueviolet','#8A2BE2',0),(28,'brown','#A52A2A',0),(29,'burlywood','#DEB887',0),(30,'cadetblue','#5F9EA0',0),(31,'chartreuse','#7FFF00',0),(32,'chocolate','#D2691E',0),(33,'coral','#FF7F50',0),(34,'cornflowerblue','#6495ED',0),(35,'cornsilk','#FFF8DC',0),(36,'crimson','#DC143C',0),(37,'cyan','#00FFFF',0),(38,'darkblue','#00008B',0),(39,'darkcyan','#008B8B',0),(40,'darkgoldenrod','#B8860B',0),(41,'darkgray','#A9A9A9',0),(42,'darkgreen','#006400',0),(43,'darkkhaki','#BDB76B',0),(44,'darkmagenta','#8B008B',0),(45,'darkolivegreen','#556B2F',0),(46,'darkorange','#FF8C00',0),(47,'darkorchid','#9932CC',0),(48,'darkred','#8B0000',0),(49,'darksalmon','#E9967A',0),(50,'darkseagreen','#8FBC8F',0),(51,'darkslateblue','#483D8B',0),(52,'darkslategray','#2F4F4F',0),(53,'darkturquoise','#00CED1',0),(54,'darkviolet','#9400D3',0),(55,'deeppink','#FF1493',0),(56,'deepskyblue','#00BFFF',0),(57,'dimgray','#696969',0),(58,'dodgerblue','#1E90FF',0),(59,'firebrick','#B22222',0),(60,'floralwhite','#FFFAF0',0),(61,'forestgreen','#228B22',0),(62,'fuchsia','#FF00FF',0),(63,'gainsboro','#DCDCDC',0),(64,'ghostwhite','#F8F8FF',0),(65,'gold','#FFD700',0),(66,'goldenrod','#DAA520',0),(67,'gray','#808080',0),(68,'green','#008000',0),(69,'greenyellow','#ADFF2F',0),(70,'honeydew','#F0FFF0',0),(71,'hotpink','#FF69B4',0),(72,'indianred','#CD5C5C',0),(73,'indigo','#4B0082',0),(74,'ivory','#FFFFF0',0),(75,'khaki','#F0E68C',0),(76,'lavender','#E6E6FA',0),(77,'lavenderblush','#FFF0F5',0),(78,'lawngreen','#7CFC00',0),(79,'lemonchiffon','#FFFACD',0),(80,'lightblue','#ADD8E6',0),(81,'lightcoral','#F08080',0),(82,'lightcyan','#E0FFFF',0),(83,'lightgoldenrodyellow','#FAFAD2',0),(84,'lightgreen','#90EE90',0),(85,'lightgrey','#D3D3D3',0),(86,'lightpink','#FFB6C1',0),(87,'lightsalmon','#FFA07A',0),(88,'lightseagreen','#20B2AA',0),(89,'lightskyblue','#87CEFA',0),(90,'lightslategray','#778899',0),(91,'lightsteelblue','#B0C4DE',0),(92,'lightyellow','#FFFFE0',0),(93,'lime','#00FF00',0),(94,'limegreen','#32CD32',0),(95,'linen','#FAF0E6',0),(96,'magenta','#FF00FF',0),(97,'maroon','#800000',0),(98,'mediumaquamarine','#66CDAA',0),(99,'mediumblue','#0000CD',0),(100,'mediumorchid','#BA55D3',0),(101,'mediumpurple','#9370DB',0),(102,'mediumseagreen','#3CB371',0),(103,'mediumslateblue','#7B68EE',0),(104,'mediumspringgreen','#00FA9A',0),(105,'mediumturquoise','#48D1CC',0),(106,'mediumvioletred','#C71585',0),(107,'midnightblue','#191970',0),(108,'mintcream','#F5FFFA',0),(109,'mistyrose','#FFE4E1',0),(110,'moccasin','#FFE4B5',0),(111,'navajowhite','#FFDEAD',0),(112,'navy','#000080',0),(113,'oldlace','#FDF5E6',0),(114,'olive','#808000',0),(115,'olivedrab','#6B8E23',0),(116,'orange','#FFA500',0),(117,'orangered','#FF4500',0),(118,'orchid','#DA70D6',0),(119,'palegoldenrod','#EEE8AA',0),(120,'palegreen','#98FB98',0),(121,'paleturquoise','#AFEEEE',0),(122,'palevioletred','#DB7093',0),(123,'papayawhip','#FFEFD5',0),(124,'peachpuff','#FFDAB9',0),(125,'peru','#CD853F',0),(126,'pink','#FFC0CB',0),(127,'yellowgreen','#9ACD32',0),(128,'white','#FFFFFF',0);
INSERT INTO `system_country` VALUES ('AFGHANISTAN','AF'),('ÅLAND ISLANDS','AX'),('ALBANIA','AL'),('ALGERIA','DZ'),('AMERICAN SAMOA','AS'),('ANDORRA','AD'),('ANGOLA','AO'),('ANGUILLA','AI'),('ANTARCTICA','AQ'),('ANTIGUA AND BARBUDA','AG'),('ARGENTINA','AR'),('ARMENIA','AM'),('ARUBA','AW'),('AUSTRALIA','AU'),('AUSTRIA','AT'),('AZERBAIJAN','AZ'),('BAHAMAS','BS'),('BAHRAIN','BH'),('BANGLADESH','BD'),('BARBADOS','BB'),('BELARUS','BY'),('BELGIUM','BE'),('BELIZE','BZ'),('BENIN','BJ'),('BERMUDA','BM'),('BHUTAN','BT'),('BOLIVIA','BO'),('BOSNIA AND HERZEGOVINA','BA'),('BOTSWANA','BW'),('BOUVET ISLAND','BV'),('BRAZIL','BR'),('BRITISH INDIAN OCEAN TERRITORY','IO'),('BRUNEI DARUSSALAM','BN'),('BULGARIA','BG'),('BURKINA FASO','BF'),('BURUNDI','BI'),('CAMBODIA','KH'),('CAMEROON','CM'),('CANADA','CA'),('CAPE VERDE','CV'),('CAYMAN ISLANDS','KY'),('CENTRAL AFRICAN REPUBLIC','CF'),('CHAD','TD'),('CHILE','CL'),('CHINA','CN'),('CHRISTMAS ISLAND','CX'),('COCOS (KEELING) ISLANDS','CC'),('COLOMBIA','CO'),('COMOROS','KM'),('CONGO','CG'),('CONGO, THE DEMOCRATIC REPUBLIC OF THE','CD'),('COOK ISLANDS','CK'),('COSTA RICA','CR'),('COTE D\'IVOIRE','CI'),('CROATIA','HR'),('CUBA','CU'),('CYPRUS','CY'),('CZECH REPUBLIC','CZ'),('DENMARK','DK'),('DJIBOUTI','DJ'),('DOMINICA','DM'),('DOMINICAN REPUBLIC','DO'),('ECUADOR','EC'),('EGYPT','EG'),('EL SALVADOR','SV'),('EQUATORIAL GUINEA','GQ'),('ERITREA','ER'),('ESTONIA','EE'),('ETHIOPIA','ET'),('FALKLAND ISLANDS (MALVINAS)','FK'),('FAROE ISLANDS','FO'),('FIJI','FJ'),('FINLAND','FI'),('FRANCE','FR'),('FRENCH GUIANA','GF'),('FRENCH POLYNESIA','PF'),('FRENCH SOUTHERN TERRITORIES','TF'),('GABON','GA'),('GAMBIA','GM'),('GEORGIA','GE'),('GERMANY','DE'),('GHANA','GH'),('GIBRALTAR','GI'),('GREECE','GR'),('GREENLAND','GL'),('GRENADA','GD'),('GUADELOUPE','GP'),('GUAM','GU'),('GUATEMALA','GT'),('GUINEA','GN'),('GUINEA-BISSAU','GW'),('GUYANA','GY'),('HAITI','HT'),('HEARD ISLAND AND MCDONALD ISLANDS','HM'),('HOLY SEE (VATICAN CITY STATE)','VA'),('HONDURAS','HN'),('HONG KONG','HK'),('HUNGARY','HU'),('ICELAND','IS'),('INDIA','IN'),('INDONESIA','ID'),('IRAN, ISLAMIC REPUBLIC OF','IR'),('IRAQ','IQ'),('IRELAND','IE'),('ISRAEL','IL'),('ITALY','IT'),('JAMAICA','JM'),('JAPAN','JP'),('JORDAN','JO'),('KAZAKHSTAN','KZ'),('KENYA','KE'),('KIRIBATI','KI'),('KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF','KP'),('KOREA, REPUBLIC OF','KR'),('KUWAIT','KW'),('KYRGYZSTAN','KG'),('LAO PEOPLE\'S DEMOCRATIC REPUBLIC','LA'),('LATVIA','LV'),('LEBANON','LB'),('LESOTHO','LS'),('LIBERIA','LR'),('LIBYAN ARAB JAMAHIRIYA','LY'),('LIECHTENSTEIN','LI'),('LITHUANIA','LT'),('LUXEMBOURG','LU'),('MACAO','MO'),('MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','MK'),('MADAGASCAR','MG'),('MALAWI','MW'),('MALAYSIA','MY'),('MALDIVES','MV'),('MALI','ML'),('MALTA','MT'),('MARSHALL ISLANDS','MH'),('MARTINIQUE','MQ'),('MAURITANIA','MR'),('MAURITIUS','MU'),('MAYOTTE','YT'),('MEXICO','MX'),('MICRONESIA, FEDERATED STATES OF','FM'),('MOLDOVA, REPUBLIC OF','MD'),('MONACO','MC'),('MONGOLIA','MN'),('MONTSERRAT','MS'),('MOROCCO','MA'),('MOZAMBIQUE','MZ'),('MYANMAR','MM'),('NAMIBIA','NA'),('NAURU','NR'),('NEPAL','NP'),('NETHERLANDS','NL'),('NETHERLANDS ANTILLES','AN'),('NEW CALEDONIA','NC'),('NEW ZEALAND','NZ'),('NICARAGUA','NI'),('NIGER','NE'),('NIGERIA','NG'),('NIUE','NU'),('NORFOLK ISLAND','NF'),('NORTHERN MARIANA ISLANDS','MP'),('NORWAY','NO'),('OMAN','OM'),('PAKISTAN','PK'),('PALAU','PW'),('PALESTINIAN TERRITORY, OCCUPIED','PS'),('PANAMA','PA'),('PAPUA NEW GUINEA','PG'),('PARAGUAY','PY'),('PERU','PE'),('PHILIPPINES','PH'),('PITCAIRN','PN'),('POLAND','PL'),('PORTUGAL','PT'),('PUERTO RICO','PR'),('QATAR','QA'),('REUNION','RE'),('ROMANIA','RO'),('RUSSIAN FEDERATION','RU'),('RWANDA','RW'),('SAINT HELENA','SH'),('SAINT KITTS AND NEVIS','KN'),('SAINT LUCIA','LC'),('SAINT PIERRE AND MIQUELON','PM'),('SAINT VINCENT AND THE GRENADINES','VC'),('SAMOA','WS'),('SAN MARINO','SM'),('SAO TOME AND PRINCIPE','ST'),('SAUDI ARABIA','SA'),('SENEGAL','SN'),('SERBIA AND MONTENEGRO','CS'),('SEYCHELLES','SC'),('SIERRA LEONE','SL'),('SINGAPORE','SG'),('SLOVAKIA','SK'),('SLOVENIA','SI'),('SOLOMON ISLANDS','SB'),('SOMALIA','SO'),('SOUTH AFRICA','ZA'),('SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS','GS'),('SPAIN','ES'),('SRI LANKA','LK'),('SUDAN','SD'),('SURINAME','SR'),('SVALBARD AND JAN MAYEN','SJ'),('SWAZILAND','SZ'),('SWEDEN','SE'),('SWITZERLAND','CH'),('SYRIAN ARAB REPUBLIC','SY'),('TAIWAN, PROVINCE OF CHINA','TW'),('TAJIKISTAN','TJ'),('TANZANIA, UNITED REPUBLIC OF','TZ'),('THAILAND','TH'),('TIMOR-LESTE','TL'),('TOGO','TG'),('TOKELAU','TK'),('TONGA','TO'),('TRINIDAD AND TOBAGO','TT'),('TUNISIA','TN'),('TURKEY','TR'),('TURKMENISTAN','TM'),('TURKS AND CAICOS ISLANDS','TC'),('TUVALU','TV'),('UGANDA','UG'),('UKRAINE','UA'),('UNITED ARAB EMIRATES','AE'),('UNITED KINGDOM','GB'),('UNITED STATES','US'),('UNITED STATES MINOR OUTLYING ISLANDS','UM'),('URUGUAY','UY'),('UZBEKISTAN','UZ'),('VANUATU','VU'),('VENEZUELA','VE'),('VIET NAM','VN'),('VIRGIN ISLANDS, BRITISH','VG'),('VIRGIN ISLANDS, U.S.','VI'),('WALLIS AND FUTUNA','WF'),('WESTERN SAHARA','EH'),('YEMEN','YE'),('ZAMBIA','ZM'),('ZIMBABWE','ZW');
INSERT INTO `system_fonts` VALUES (1,'Tahoma'),(2,'Verdana'),(3,'Arial'),(4,'Times New Roman'),(5,'Courier New'),(8,'Arial Black'),(7,'Comic Sans MS'),(9,'Georgia'),(10,'Impact'),(11,'Lucida Console'),(12,'Lucida Sans Unicode'),(13,'Marlett'),(14,'Microsoft Sans Serif'),(15,'Modern'),(16,'Palatino Linotype'),(17,'Roman'),(18,'Script'),(19,'Symbol'),(20,'Trebuchet MS'),(21,'Webdings'),(22,'Wingdings');
INSERT INTO `system_languages` VALUES ('ABKHAZIAN','AB'),('AFAN (OROMO)','OM'),('AFAR','AA'),('AFRIKAANS','AF'),('ALBANIAN','SQ'),('AMHARIC','AM'),('ARABIC','AR'),('ARMENIAN','HY'),('ASSAMESE','AS'),('AYMARA','AY'),('AZERBAIJANI','AZ'),('BASHKIR','BA'),('BASQUE','EU'),('BENGALI','BN'),('BHUTANI','DZ'),('BIHARI','BH'),('BISLAMA','BI'),('BRETON','BR'),('BULGARIAN','BG'),('BURMESE','MY'),('BYELORUSSIAN','BE'),('CAMBODIAN','KM'),('CATALAN','CA'),('CHINESE','ZH'),('CORSICAN','CO'),('CROATIAN','HR'),('CZECH','CS'),('DANISH','DA'),('DUTCH','NL'),('ENGLISH','EN'),('ESPERANTO','EO'),('ESTONIAN','ET'),('FAROESE','FO'),('FIJI','FJ'),('FINNISH','FI'),('FRENCH','FR'),('FRISIAN','FY'),('GALICIAN','GL'),('GEORGIAN','KA'),('GERMAN','DE'),('GREEK','EL'),('GREENLANDIC','KL'),('GUARANI','GN'),('GUJARATI','GU'),('HAUSA','HA'),('HEBREW','HE'),('HINDI','HI'),('HUNGARIAN','HU'),('ICELANDIC','IS'),('INDONESIAN','ID'),('INTERLINGUA','IA'),('INTERLINGUE','IE'),('INUKTITUT','IU'),('INUPIAK','IK'),('IRISH','GA'),('ITALIAN','IT'),('JAPANESE','JA'),('JAVANESE','JV'),('KANNADA','KN'),('KASHMIRI','KS'),('KAZAKH','KK'),('KINYARWANDA','RW'),('KIRGHIZ','KY'),('KURUNDI','RN'),('KOREAN','KO'),('KURDISH','KU'),('LAOTHIAN','LO'),('LATIN','LA'),('LATVIAN','LV'),('LINGALA','LN'),('LITHUANIAN','LT'),('MACEDONIAN','MK'),('MALAGASY','MG'),('MALAY','MS'),('MALAYALAM','ML'),('MALTESE','MT'),('MAORI','MI'),('MARATHI','MR'),('MOLDAVIAN','MO'),('MONGOLIAN','MN'),('NAURU','NA'),('NEPALI','NE'),('NORWEGIAN','NO'),('OCCITAN','OC'),('ORIYA','OR'),('PASHTO','PS'),('PERSIAN (farsi)','FA'),('POLISH','PL'),('PORTUGUESE','PT'),('PUNJABI','PA'),('QUECHUA','QU'),('RHAETO-ROMANCE','RM'),('ROMANIAN','RO'),('RUSSIAN','RU'),('SAMOAN','SM'),('SANGHO','SG'),('SANSKRIT','SA'),('SCOTS GAELIC','GD'),('SERBIAN','SR'),('SERBO-CROATIAN','SH'),('SESOTHO','ST'),('SETSWANA','TN'),('SHONA','SN'),('SINDHI','SD'),('SINGHALESE','SI'),('SISWATI','SS'),('SLOVAK','SK'),('SLOVENIAN','SL'),('SOMALI','SO'),('SPANISH','ES'),('SUNDANESE','SU'),('SWAHILI','SW'),('SWEDISH','SV'),('TAGALOG','TL'),('TAJIK','TG'),('TAMIL','TA'),('TATAR','TT'),('TELUGU','TE'),('THAI','TH'),('TIBETAN','BO'),('TIGRINYA','TI'),('TONGA','TO'),('TSONGA','TS'),('TURKISH','TR'),('TURKMEN','TK'),('TWI','TW'),('UIGUR','UG'),('UKRAINIAN','UK'),('URDU','UR'),('UZBEK','UZ'),('VIETNAMESE','VI'),('VOLAPUK','VO'),('WELSH','CY'),('WOLOF','WO'),('XHOSA','XH'),('YIDDISH','YI'),('YORUBA','YO'),('ZHUANG','ZA'),('ZULU','ZU');
