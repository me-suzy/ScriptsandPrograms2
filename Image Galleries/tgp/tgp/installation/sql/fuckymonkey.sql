# $Id: mambo.sql,v 1.19 2005/02/16 15:44:24 stingrey Exp $

# 
# Table structure for table `#__tgpads`
# 

CREATE TABLE `#__tgpads` (
  `webcate` varchar(100) NOT NULL default '',
  `adcode` longtext NOT NULL,
  PRIMARY KEY  (`webcate`)
) TYPE=MyISAM;

# 
# Table structure for table `#__tgpblind`
# 

CREATE TABLE `#__tgpblind` (
  `linkname` varchar(25) NOT NULL default '',
  `linkurl` varchar(125) NOT NULL default '',
  `linkdesc` varchar(254) NOT NULL default '',
  `numpics` varchar(254) NOT NULL default ''
) TYPE=MyISAM;

# 
# Table structure for table `#__tgpcategories`
# 

CREATE TABLE `#__tgpcategories` (
  `catname` varchar(100) NOT NULL default '',
  `catdesc` varchar(254) NOT NULL default '',
  `autopost` int(3) NOT NULL default '1',
  PRIMARY KEY  (`catname`)
) TYPE=MyISAM;

# 
# Dumping data for table `#__tgpcategories`
# 

INSERT INTO `#__tgpcategories` VALUES ('Anal', 'anal', 10);
INSERT INTO `#__tgpcategories` VALUES ('Asian', 'asian', 10);
INSERT INTO `#__tgpcategories` VALUES ('Babe', 'babe', 10);
INSERT INTO `#__tgpcategories` VALUES ('Bikini', 'bikini', 10);
INSERT INTO `#__tgpcategories` VALUES ('Bizarre', 'bizarre', 10);
INSERT INTO `#__tgpcategories` VALUES ('Bondage', 'bondage', 10);
INSERT INTO `#__tgpcategories` VALUES ('Brunette', 'brunette', 10);
INSERT INTO `#__tgpcategories` VALUES ('Cartoon', 'cartoon', 10);
INSERT INTO `#__tgpcategories` VALUES ('Cheerleader', 'cheerleader', 10);
INSERT INTO `#__tgpcategories` VALUES ('Ebony', 'ebony', 10);
INSERT INTO `#__tgpcategories` VALUES ('Fetish', 'fetish', 10);
INSERT INTO `#__tgpcategories` VALUES ('Gay', 'gay', 10);
INSERT INTO `#__tgpcategories` VALUES ('Group', 'group', 10);
INSERT INTO `#__tgpcategories` VALUES ('Hairy', 'hairy', 10);
INSERT INTO `#__tgpcategories` VALUES ('Hardcore', 'hardcore', 10);
INSERT INTO `#__tgpcategories` VALUES ('Interracial', 'interracial', 10);
INSERT INTO `#__tgpcategories` VALUES ('Lesbian', 'lesbian', 10);
INSERT INTO `#__tgpcategories` VALUES ('Lingerie', 'lingerie', 10);
INSERT INTO `#__tgpcategories` VALUES ('Male', 'male', 10);
INSERT INTO `#__tgpcategories` VALUES ('Movie', 'movie', 10);
INSERT INTO `#__tgpcategories` VALUES ('Older', 'older', 10);
INSERT INTO `#__tgpcategories` VALUES ('Panty', 'panty', 10);
INSERT INTO `#__tgpcategories` VALUES ('Pregnant', 'pregnant', 10);
INSERT INTO `#__tgpcategories` VALUES ('Shemale', 'shemale', 10);
INSERT INTO `#__tgpcategories` VALUES ('Teen', 'teen', 10);
INSERT INTO `#__tgpcategories` VALUES ('Torture', 'torture', 10);
INSERT INTO `#__tgpcategories` VALUES ('Toy', 'toy', 10);
INSERT INTO `#__tgpcategories` VALUES ('Uniform', 'uniform', 10);
INSERT INTO `#__tgpcategories` VALUES ('Video', 'video', 10);
INSERT INTO `#__tgpcategories` VALUES ('Voyeur', 'voyeur', 10);
INSERT INTO `#__tgpcategories` VALUES ('Wife', 'wife', 10);
INSERT INTO `#__tgpcategories` VALUES ('Amateurs', 'amateurs', 10);

# 
# Table structure for table `#__tgpdead`
# 

CREATE TABLE `#__tgpdead` (
  `status` varchar(50) NOT NULL default '',
  `deadurl` varchar(255) NOT NULL default '',
  `idnum` varchar(20) NOT NULL default '',
  `filesize` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`idnum`)
) TYPE=MyISAM;

# 
# Table structure for table `#__tgpdeclines`
# 

CREATE TABLE `#__tgpdeclines` (
  `decname` varchar(35) NOT NULL default '',
  `decvalue` text NOT NULL,
  PRIMARY KEY  (`decname`)
) TYPE=MyISAM;

# 
# Dumping data for table `#__tgpdeclines`
# 

INSERT INTO `#__tgpdeclines` VALUES ('Broken Recip', 'We require a working recip. link. Yours does not seem to be working or doesnt exist.');
INSERT INTO `#__tgpdeclines` VALUES ('Sponsor Content', 'Overuse of sponsor content or content that we receive often.');
INSERT INTO `#__tgpdeclines` VALUES ('Broken Pics', 'Some of your pics/videos seemed to be broken.');
INSERT INTO `#__tgpdeclines` VALUES ('Popups', 'Popups on your page. We do not list ANY galleries with popups.');
INSERT INTO `#__tgpdeclines` VALUES ('Too many ads', 'Too many advertisements.');
INSERT INTO `#__tgpdeclines` VALUES ('Unspecified', 'No specific reason given.');
INSERT INTO `#__tgpdeclines` VALUES ('404', 'Your page returned a 404 (Page not found) error.');
INSERT INTO `#__tgpdeclines` VALUES ('Redirect Link', ' Your post must be Gallery only, not redirect pages. ');

# 
# Table structure for table `#__tgpdomain`
# 

CREATE TABLE `#__tgpdomain` (
  `banned` varchar(255) NOT NULL default '',
  KEY `affname` (`banned`)
) TYPE=MyISAM;

# 
# Table structure for table `#__tgpfilter`
# 

CREATE TABLE `#__tgpfilter` (
  `fname` varchar(255) NOT NULL default '',
  `ffilter` varchar(255) NOT NULL default '',
  `freason` varchar(255) NOT NULL default ''
) TYPE=MyISAM;

# 
# Dumping data for table `#__tgpfilter`
# 

INSERT INTO `#__tgpfilter` VALUES ('Lolita', 'Lolita', 'Recused');
INSERT INTO `#__tgpfilter` VALUES ('Bestiality', 'bestiality', 'recused');

# 
# Table structure for table `#__tgpgalleries`
# 

CREATE TABLE `#__tgpgalleries` (
  `webname` varchar(40) NOT NULL default '0',
  `webemail` varchar(40) NOT NULL default '0',
  `weburl` varchar(125) NOT NULL default '0',
  `webcate` varchar(100) NOT NULL default '0',
  `webpics` int(3) NOT NULL default '0',
  `webdesc` varchar(200) NOT NULL default '0',
  `webdate` varchar(14) NOT NULL default '00/00/0000',
  `datecode` int(10) NOT NULL default '0',
  `approval` char(2) NOT NULL default '0',
  `idnum` int(15) NOT NULL default '0',
  `webip` varchar(15) NOT NULL default '0.0.0.0',
  `uniqueid` varchar(8) NOT NULL default '00000000',
  `vermail` char(1) NOT NULL default '1',
  `stamp` varchar(14) NOT NULL default '',
  `rate` smallint(2) NOT NULL default '5',
  `aff` tinyint(1) NOT NULL default '0',
  `fsize` int(7) NOT NULL default '0',
  `thumb` char(1) NOT NULL default '0',
  `recip` char(1) NOT NULL default '0',
  PRIMARY KEY  (`idnum`),
  KEY `webdesc` (`webdesc`)
) TYPE=MyISAM;

# 
# Table structure for table `#__tgpip`
# 

CREATE TABLE `#__tgpip` (
  `banned` varchar(15) NOT NULL default '0.0.0.0',
  KEY `affname` (`banned`)
) TYPE=MyISAM;

# 
# Table structure for table `#__tgpoptions`
# 

CREATE TABLE `#__tgpoptions` (
  `min_pics` char(3) NOT NULL default '',
  `mnpppd` char(2) NOT NULL default '',
  `rrecip` char(2) NOT NULL default '',
  `posteremail` char(2) NOT NULL default '',
  `sendpemail` char(2) NOT NULL default '',
  `sendpemailap` char(2) NOT NULL default '',
  `sendpemailde` char(2) NOT NULL default '',
  `blocksoft` char(2) NOT NULL default '',
  `checkbot` char(2) NOT NULL default '',
  `drank` char(2) NOT NULL default '',
  `pdrank` char(2) NOT NULL default '',
  `precip` char(2) NOT NULL default '',
  `capitalize` char(2) NOT NULL default '',
  `scanpo` varchar(5) NOT NULL default '',
  `scanpn` varchar(5) NOT NULL default '',
  `blink` varchar(6) NOT NULL default '',
  `autopost` char(2) NOT NULL default '',
  `postopen` char(2) NOT NULL default '',
  `ppostopen` char(2) NOT NULL default '',
  `requireapproval` char(2) NOT NULL default '',
  `thumbopen` char(2) NOT NULL default '',
  `pthumbopen` char(2) NOT NULL default '',
  `appdate` char(3) NOT NULL default '',
  `pic` char(3) NOT NULL default '',
  `vid` char(3) NOT NULL default '',
  `min_vids` char(3) NOT NULL default '',
  PRIMARY KEY  (`min_pics`)
) TYPE=MyISAM;

# 
# Dumping data for table `#__tgpoptions`
# 

INSERT INTO `#__tgpoptions` VALUES ('1', '1', '0', '1', '1', '1', '1', '1', '1', '1', '5', '1', '1', '1', '800', '1', '0', '1', '1', '1', '1', '1','1','1','1','3');

# 
# Table structure for table `#__tgppartners`
# 

CREATE TABLE `#__tgppartners` (
  `affname` varchar(50) NOT NULL default '',
  `email` varchar(254) NOT NULL default '',
  `passw` varchar(25) NOT NULL default '',
  `ppd` int(2) NOT NULL default '0',
  `app` char(1) NOT NULL default '',
  `drate` int(2) NOT NULL default '0',
  `preciplink` varchar(50) NOT NULL default '',
  `precipost` char(2) NOT NULL default '',
  KEY `affname` (`affname`)
) TYPE=MyISAM;


# 
# Table structure for table `#__tgppolice`
# 

CREATE TABLE `#__tgppolice` (
  `idnum` int(10) NOT NULL default '0',
  `posturl` varchar(254) NOT NULL default '',
  `reports` int(4) NOT NULL default '0',
  `ipaddy` varchar(15) NOT NULL default ''
) TYPE=MyISAM;

# 
# Table structure for table `#__tgppposts`
# 

CREATE TABLE `#__tgppposts` (
  `idnum` varchar(20) NOT NULL default '',
  `affname` varchar(50) NOT NULL default '',
  `url` varchar(254) NOT NULL default '',
  `date` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`idnum`)
) TYPE=MyISAM;

# 
# Table structure for table `#__tgptemps`
# 

CREATE TABLE `#__tgptemps` (
  `tagname` varchar(45) NOT NULL default '',
  `category` varchar(100) NOT NULL default '',
  `startat` varchar(10) NOT NULL default '',
  `endat` varchar(10) NOT NULL default '',
  `rlimit` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`tagname`)
) TYPE=MyISAM;

# 
# Dumping data for table `#__tgptemps`
# 

INSERT INTO `#__tgptemps` VALUES ('ARCHSET', 'Universal', '0', '1000', '1');
INSERT INTO `#__tgptemps` VALUES ('ANA1', 'Anal', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('ANA2', 'Anal', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('ASI1', 'Asian', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('ASI2', 'Asian', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('BAB1', 'Babe', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('BAB2', 'Babe', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('BRU1', 'Brunette', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('BRU2', 'Brunette', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('CHE1', 'Cheerleader', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('CHE2', 'Cheerleader', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('BON1', 'Bondage', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('BON2', 'Bondage', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('FET1', 'Fetish', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('FET2', 'Fetish', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('GAY1', 'Gay', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('GAY2', 'Gay', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('GRO1', 'Group', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('GRO2', 'Group', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('HARD1', 'Hardcore', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('HARD2', 'Hardcore', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('INT1', 'Interracial', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('INT2', 'Interracial', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('TEE1', 'Teen', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('TEE2', 'Teen', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('LES1', 'Lesbian', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('LES2', 'Lesbian', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('OLD1', 'Older', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('OLD2', 'Older', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('PRE1', 'Pregnant', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('PRE2', 'Pregnant', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('VID1', 'Video', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('VID2', 'Video', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('BIK1', 'Bikini', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('BIK2', 'Bikini', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('CAR1', 'Cartoon', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('CAR2', 'Cartoon', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('EBO1', 'Ebony', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('EBO2', 'Ebony', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('HAI1', 'Hairy', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('HAI2', 'Hairy', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('BIZ1', 'Bizarre', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('BIZ2', 'Bizarre', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('MAL1', 'Male', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('MAL2', 'Male', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('MOV1', 'Movie', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('MOV2', 'Movie', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('LIN1', 'Lingerie', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('LIN2', 'Lingerie', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('PAN1', 'Panty', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('PAN2', 'Panty', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('SHE1', 'Shemale', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('SHE2', 'Shemale', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('TOR1', 'Torture', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('TOR2', 'Torture', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('WIF1', 'Wife', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('WIF2', 'Wife', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('TOY1', 'Toy', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('TOY2', 'Toy', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('UNI1', 'Uniform', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('UNI2', 'Uniform', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('VOY1', 'Voyeur', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('VOY2', 'Voyeur', '16', '15', '15');
INSERT INTO `#__tgptemps` VALUES ('ARC1', 'Universal', '0', '80', '1');
INSERT INTO `#__tgptemps` VALUES ('ARC2', 'Universal', '81', '80', '1');
INSERT INTO `#__tgptemps` VALUES ('AMA1', 'Amateurs', '0', '5', '3');
INSERT INTO `#__tgptemps` VALUES ('AMA2', 'Amateurs', '16', '15', '15');
