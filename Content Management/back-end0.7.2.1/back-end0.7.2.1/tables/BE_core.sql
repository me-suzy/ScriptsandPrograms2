# $Id: BE_core.sql,v 1.43 2005/06/15 01:22:30 mgifford Exp $
# **************************************
# Back-End table creation script
# for Back-End Version: 0.7.2.1
# **************************************

#
# Table structure for table `CACHEDATA`
#

DROP TABLE IF EXISTS CACHEDATA;
CREATE TABLE CACHEDATA (
  CACHEKEY varchar(255) NOT NULL default '',
  CACHEEXPIRATION int(11) NOT NULL default '0',
  GZDATA blob,
  DATASIZE int(11) default NULL,
  DATACRC int(11) default NULL,
  PRIMARY KEY  (CACHEKEY)
) TYPE=MyISAM;

#
# Dumping data for table `CACHEDATA`
#


/*!40000 ALTER TABLE CACHEDATA DISABLE KEYS */;
LOCK TABLES CACHEDATA WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE CACHEDATA ENABLE KEYS */;

#
# Table structure for table `UidNumber`
#

DROP TABLE IF EXISTS UidNumber;
CREATE TABLE UidNumber (
  Uid int(11) default NULL
) TYPE=MyISAM;

#
# Dumping data for table `UidNumber`
#


/*!40000 ALTER TABLE UidNumber DISABLE KEYS */;
LOCK TABLES UidNumber WRITE;
INSERT INTO UidNumber VALUES (2000);
UNLOCK TABLES;
/*!40000 ALTER TABLE UidNumber ENABLE KEYS */;

#
# Table structure for table `active_sessions`
#

DROP TABLE IF EXISTS active_sessions;
CREATE TABLE active_sessions (
  sid varchar(32) NOT NULL default '',
  name varchar(32) NOT NULL default '',
  val text,
  changed varchar(14) NOT NULL default '',
  PRIMARY KEY  (name,sid),
  KEY changed (changed)
) TYPE=MyISAM;

#
# Dumping data for table `active_sessions`
#


/*!40000 ALTER TABLE active_sessions DISABLE KEYS */;
LOCK TABLES active_sessions WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE active_sessions ENABLE KEYS */;

#
# Table structure for table `active_sessions_split`
#

DROP TABLE IF EXISTS active_sessions_split;
CREATE TABLE active_sessions_split (
  ct_sid varchar(32) NOT NULL default '',
  ct_name varchar(32) NOT NULL default '',
  ct_pos varchar(6) NOT NULL default '',
  ct_val text,
  ct_changed varchar(14) NOT NULL default '',
  PRIMARY KEY  (ct_name,ct_sid,ct_pos),
  KEY ct_changed (ct_changed)
) TYPE=MyISAM;

#
# Dumping data for table `active_sessions_split`
#


/*!40000 ALTER TABLE active_sessions_split DISABLE KEYS */;
LOCK TABLES active_sessions_split WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE active_sessions_split ENABLE KEYS */;

#
# Table structure for table `auth_user`
#

DROP TABLE IF EXISTS auth_user;
CREATE TABLE auth_user (
  user_id varchar(32) NOT NULL default '',
  username varchar(32) NOT NULL default '',
  password varchar(32) NOT NULL default '',
  perms varchar(255) default NULL,
  PRIMARY KEY  (user_id),
  UNIQUE KEY k_username (username)
) TYPE=MyISAM;

#
# Dumping data for table `auth_user`
#


/*!40000 ALTER TABLE auth_user DISABLE KEYS */;
LOCK TABLES auth_user WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE auth_user ENABLE KEYS */;

#
# Table structure for table `auth_user_md5`
#

DROP TABLE IF EXISTS auth_user_md5;
CREATE TABLE auth_user_md5 (
  user_id varchar(32) NOT NULL default '',
  username varchar(32) NOT NULL default '',
  password varchar(32) NOT NULL default '',
  perms varchar(255) default NULL,
  PRIMARY KEY  (user_id),
  UNIQUE KEY k_username (username)
) TYPE=MyISAM;

#
# Dumping data for table `auth_user_md5`
#


/*!40000 ALTER TABLE auth_user_md5 DISABLE KEYS */;
LOCK TABLES auth_user_md5 WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE auth_user_md5 ENABLE KEYS */;

#
# Table structure for table `be_article2section`
#

DROP TABLE IF EXISTS be_article2section;
CREATE TABLE be_article2section (
  articleID smallint(5) unsigned NOT NULL default '0',
  sectionID smallint(5) unsigned NOT NULL default '0',
  KEY articleID (articleID),
  KEY articleSectionID (sectionID)
) TYPE=MyISAM;

#
# Dumping data for table `be_article2section`
#


/*!40000 ALTER TABLE be_article2section DISABLE KEYS */;
LOCK TABLES be_article2section WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_article2section ENABLE KEYS */;

#
# Table structure for table `be_articleText`
#

DROP TABLE IF EXISTS be_articleText;
CREATE TABLE be_articleText (
  articleTextID smallint(5) unsigned NOT NULL auto_increment,
  articleID smallint(5) NOT NULL default '0',
  languageID char(3) NOT NULL default '',
  URLname varchar(255) NOT NULL default '',
  title varchar(255) NOT NULL default '',
  blurb text NOT NULL,
  content text NOT NULL,
  content_source text NOT NULL,
  blurb_source text NOT NULL,
  title_source varchar(255) NOT NULL default '',
  spotlight tinyint(2) NOT NULL default '0',
  meta_keywords varchar(255) default NULL,
  meta_description varchar(255) default NULL,
  template varchar(55) default NULL,
  originalText smallint(5) default NULL,
  commentIDtext int(7) default '0',
  dateCreated int(11) NOT NULL default '0',
  PRIMARY KEY  (articleTextID),
  KEY articleID (articleID),
  KEY languageID (languageID),
  KEY articleLanguage (articleID,languageID),
  KEY URLname (URLname),
  KEY dateCreated (dateCreated)
) TYPE=MyISAM;

#
# Dumping data for table `be_articleText`
#


/*!40000 ALTER TABLE be_articleText DISABLE KEYS */;
LOCK TABLES be_articleText WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_articleText ENABLE KEYS */;

#
# Table structure for table `be_articleTextOptions`
#

DROP TABLE IF EXISTS be_articleTextOptions;
CREATE TABLE be_articleTextOptions (
  articleID smallint(5) unsigned NOT NULL default '0',
  languageID char(3) NOT NULL default '',
  options text NOT NULL,
  Author varchar(255) NOT NULL default '',
  PRIMARY KEY  (articleID,languageID)
) TYPE=MyISAM;

#
# Dumping data for table `be_articleTextOptions`
#


/*!40000 ALTER TABLE be_articleTextOptions DISABLE KEYS */;
LOCK TABLES be_articleTextOptions WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_articleTextOptions ENABLE KEYS */;

#
# Table structure for table `be_articles`
#

DROP TABLE IF EXISTS be_articles;
CREATE TABLE be_articles (
  articleID smallint(5) unsigned NOT NULL auto_increment,
  URLname varchar(20) NOT NULL default '',
  author_id smallint(5) unsigned default NULL,
  subsiteID smallint(5) unsigned default NULL,
  dateCreated int(10) unsigned NOT NULL default '0',
  dateModified int(10) unsigned NOT NULL default '0',
  dateAvailable int(10) unsigned NOT NULL default '0',
  dateRemoved int(10) unsigned NOT NULL default '0',
  dateForSort int(10) unsigned NOT NULL default '0',
  content_type varchar(8) NOT NULL default '',
  main_languageID char(2) NOT NULL default '',
  hide tinyint(2) unsigned NOT NULL default '0',
  deleted tinyint(2) unsigned NOT NULL default '0',
  restrict2members int(5) unsigned NOT NULL default '0',
  spotlight tinyint(2) default '0',
  showPrint tinyint(2) default '1',
  useIcons tinyint(2) default '0',
  hitCounter smallint(10) NOT NULL default '0',
  priority smallint(5) NOT NULL default '0',
  commentID int(7) default '0',
  PRIMARY KEY  (articleID,URLname),
  KEY articleID (articleID),
  KEY URLname (URLname),
  KEY author_id (author_id),
  KEY articleID_2 (articleID,hide,restrict2members,dateAvailable,dateRemoved,hitCounter),
  KEY hide (hide,restrict2members,dateAvailable,dateRemoved,hitCounter),
  KEY hide_2 (hide,restrict2members,dateAvailable,dateRemoved)
) TYPE=MyISAM;

#
# Dumping data for table `be_articles`
#


/*!40000 ALTER TABLE be_articles DISABLE KEYS */;
LOCK TABLES be_articles WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_articles ENABLE KEYS */;

#
# Table structure for table `be_blockcache`
#

DROP TABLE IF EXISTS be_blockcache;
CREATE TABLE be_blockcache (
  blockID int(11) NOT NULL default '0',
  blockTypeID int(11) NOT NULL default '0',
  userID int(11) NOT NULL default '0',
  languageID char(3) NOT NULL default '',
  subsiteID int(11) NOT NULL default '0',
  expiryTime int(11) NOT NULL default '0',
  cacheData text NOT NULL,
  KEY expiryTime (expiryTime),
  KEY blockID (blockID),
  KEY blockTypeID (blockTypeID),
  KEY userID (userID),
  KEY languageID (languageID),
  KEY subsiteID (subsiteID)
) TYPE=MyISAM;

#
# Dumping data for table `be_blockcache`
#


/*!40000 ALTER TABLE be_blockcache DISABLE KEYS */;
LOCK TABLES be_blockcache WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_blockcache ENABLE KEYS */;

#
# Table structure for table `be_card`
#

DROP TABLE IF EXISTS be_card;
CREATE TABLE be_card (
  cardID int(11) unsigned NOT NULL auto_increment,
  customize smallint(4) unsigned NOT NULL default '0',
  defaultCard smallint(4) unsigned NOT NULL default '0',
  senderName varchar(255) NOT NULL default '',
  senderEmail varchar(255) NOT NULL default '',
  PRIMARY KEY  (cardID),
  KEY defaultCard (defaultCard)
) TYPE=MyISAM;

#
# Dumping data for table `be_card`
#


/*!40000 ALTER TABLE be_card DISABLE KEYS */;
LOCK TABLES be_card WRITE;
INSERT INTO be_card VALUES (1,1,1,'','');
UNLOCK TABLES;
/*!40000 ALTER TABLE be_card ENABLE KEYS */;

#
# Table structure for table `be_card2action`
#

DROP TABLE IF EXISTS be_card2action;
CREATE TABLE be_card2action (
  cardID int(11) unsigned NOT NULL default '0',
  actionID int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (cardID,actionID)
) TYPE=MyISAM;

#
# Dumping data for table `be_card2action`
#


/*!40000 ALTER TABLE be_card2action DISABLE KEYS */;
LOCK TABLES be_card2action WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_card2action ENABLE KEYS */;

#
# Table structure for table `be_card2petition`
#

DROP TABLE IF EXISTS be_card2petition;
CREATE TABLE be_card2petition (
  cardID int(11) unsigned NOT NULL default '0',
  petitionID int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (cardID,petitionID)
) TYPE=MyISAM;

#
# Dumping data for table `be_card2petition`
#


/*!40000 ALTER TABLE be_card2petition DISABLE KEYS */;
LOCK TABLES be_card2petition WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_card2petition ENABLE KEYS */;

#
# Table structure for table `be_card2section`
#

DROP TABLE IF EXISTS be_card2section;
CREATE TABLE be_card2section (
  cardID int(11) unsigned NOT NULL default '0',
  sectionID int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (cardID,sectionID)
) TYPE=MyISAM;

#
# Dumping data for table `be_card2section`
#


/*!40000 ALTER TABLE be_card2section DISABLE KEYS */;
LOCK TABLES be_card2section WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_card2section ENABLE KEYS */;

#
# Table structure for table `be_cardText`
#

DROP TABLE IF EXISTS be_cardText;
CREATE TABLE be_cardText (
  cardTextID int(11) unsigned NOT NULL auto_increment,
  cardID int(11) unsigned NOT NULL default '0',
  languageID char(3) NOT NULL default '',
  cardTitle varchar(255) NOT NULL default '',
  cardBlurb text NOT NULL,
  cardText text NOT NULL,
  cardImage varchar(255) NOT NULL default '',
  PRIMARY KEY  (cardTextID),
  KEY languageID (languageID),
  KEY cardID (cardID),
  KEY languageCard (languageID,cardID)
) TYPE=MyISAM;

#
# Dumping data for table `be_cardText`
#


/*!40000 ALTER TABLE be_cardText DISABLE KEYS */;
LOCK TABLES be_cardText WRITE;
INSERT INTO be_cardText VALUES (1,1,'en','About Back-End','Spread the word about Back-End: tell your friends and colleagues about this site.','[NAME] asked us to let you know about this page on the Back-End web site: [URL]',''),(2,1,'fr','Au sujet de Back-End','(Needs French translation): Spread the word about Back-End: tell your friends and colleagues about this site.','[NAME] asked us to let you know about this page on the Back-End web site:[URL]','');
UNLOCK TABLES;
/*!40000 ALTER TABLE be_cardText ENABLE KEYS */;

#
# Table structure for table `be_history`
#

DROP TABLE IF EXISTS be_history;
CREATE TABLE be_history (
  id int(11) NOT NULL auto_increment,
  itemTable varchar(32) NOT NULL default '',
  itemKey varchar(32) NOT NULL default '',
  versionMajor int(11) NOT NULL default '0',
  versionMinor int(11) NOT NULL default '0',
  userId varchar(32) NOT NULL default '',
  date int(11) NOT NULL default '0',
  content text,
  hash varchar(32) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY table_key (itemTable,itemKey),
  KEY version (versionMajor,versionMinor)
) TYPE=MyISAM;

#
# Dumping data for table `be_history`
#


/*!40000 ALTER TABLE be_history DISABLE KEYS */;
LOCK TABLES be_history WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_history ENABLE KEYS */;

#
# Table structure for table `be_hits`
#

DROP TABLE IF EXISTS be_hits;
CREATE TABLE be_hits (
  hitTime int(11) unsigned NOT NULL default '0',
  articleID int(11) unsigned NOT NULL default '0',
  KEY hitTime (hitTime),
  KEY articleID (articleID)
) TYPE=MyISAM;

#
# Dumping data for table `be_hits`
#


/*!40000 ALTER TABLE be_hits DISABLE KEYS */;
LOCK TABLES be_hits WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_hits ENABLE KEYS */;

#
# Table structure for table `be_image2articleText`
#

DROP TABLE IF EXISTS be_image2articleText;
CREATE TABLE be_image2articleText (
  articleTextID int(11) NOT NULL default '0',
  imageID int(11) NOT NULL default '0',
  PRIMARY KEY  (articleTextID,imageID),
  UNIQUE KEY ImageArticleTextIndex (articleTextID,imageID)
) TYPE=MyISAM;

#
# Dumping data for table `be_image2articleText`
#


/*!40000 ALTER TABLE be_image2articleText DISABLE KEYS */;
LOCK TABLES be_image2articleText WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_image2articleText ENABLE KEYS */;

#
# Table structure for table `be_image2section`
#

DROP TABLE IF EXISTS be_image2section;
CREATE TABLE be_image2section (
  sectionID int(11) NOT NULL default '0',
  imageID int(11) NOT NULL default '0',
  PRIMARY KEY  (sectionID,imageID),
  UNIQUE KEY ImageSectionInidex (sectionID,imageID)
) TYPE=MyISAM;

#
# Dumping data for table `be_image2section`
#


/*!40000 ALTER TABLE be_image2section DISABLE KEYS */;
LOCK TABLES be_image2section WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_image2section ENABLE KEYS */;

#
# Table structure for table `be_imageText`
#

DROP TABLE IF EXISTS be_imageText;
CREATE TABLE be_imageText (
  imageTextID smallint(5) unsigned NOT NULL auto_increment,
  imageID smallint(5) NOT NULL default '0',
  languageID char(3) NOT NULL default '',
  title varchar(255) NOT NULL default '',
  description varchar(255) default NULL,
  originalText smallint(3) NOT NULL default '0',
  commentIDtext int(7) default '0',
  PRIMARY KEY  (imageTextID),
  KEY imageID (imageID),
  KEY languageID (languageID)
) TYPE=MyISAM;

#
# Dumping data for table `be_imageText`
#


/*!40000 ALTER TABLE be_imageText DISABLE KEYS */;
LOCK TABLES be_imageText WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_imageText ENABLE KEYS */;

#
# Table structure for table `be_images`
#

DROP TABLE IF EXISTS be_images;
CREATE TABLE be_images (
  imageID int(11) NOT NULL default '0',
  author_id smallint(5) unsigned default NULL,
  dateCreated int(10) unsigned NOT NULL default '0',
  dateModified int(10) unsigned NOT NULL default '0',
  dateAvailable int(10) unsigned NOT NULL default '0',
  dateRemoved int(10) unsigned NOT NULL default '0',
  hide tinyint(2) unsigned NOT NULL default '0',
  restrict2members int(5) unsigned NOT NULL default '0',
  views int(11) NOT NULL default '0',
  format varchar(32) NOT NULL default '',
  width int(11) NOT NULL default '0',
  height int(11) NOT NULL default '0',
  bytes int(11) NOT NULL default '0',
  image mediumblob NOT NULL,
  thumbnail mediumblob,
  publishedAt timestamp(14) NOT NULL,
  shotAt date default NULL,
  priority smallint(5) NOT NULL default '0',
  commentID int(7) default '0',
  filename varchar(255) default NULL,
  PRIMARY KEY  (imageID),
  KEY imageID (imageID)
) TYPE=MyISAM;

#
# Dumping data for table `be_images`
#


/*!40000 ALTER TABLE be_images DISABLE KEYS */;
LOCK TABLES be_images WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_images ENABLE KEYS */;

#
# Table structure for table `be_keyword2article`
#

DROP TABLE IF EXISTS be_keyword2article;
CREATE TABLE be_keyword2article (
  keyword varchar(32) NOT NULL default '',
  articleID int(11) NOT NULL default '0',
  languageID char(3) NOT NULL default '',
  KEY keyword (keyword),
  KEY articleID (articleID),
  KEY languageID (languageID)
) TYPE=MyISAM;

#
# Dumping data for table `be_keyword2article`
#


/*!40000 ALTER TABLE be_keyword2article DISABLE KEYS */;
LOCK TABLES be_keyword2article WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_keyword2article ENABLE KEYS */;

#
# Table structure for table `be_language`
#

DROP TABLE IF EXISTS be_language;
CREATE TABLE be_language (
  languageID char(2) NOT NULL default '0',
  name varchar(25) NOT NULL default '0',
  PRIMARY KEY  (languageID),
  KEY languageID (languageID,name)
) TYPE=MyISAM;

#
# Dumping data for table `be_language`
#


/*!40000 ALTER TABLE be_language DISABLE KEYS */;
LOCK TABLES be_language WRITE;
INSERT INTO be_language VALUES ('aa','Afar'),('ab','Abkhazian'),('af','Afrikaans'),('am','Amharic'),('ar','Arabic'),('as','Assamese'),('ay','Aymara'),('az','Azerbaijani'),('ba','Bashkir'),('be','Byelorussian'),('bg','Bulgarian'),('bh','Bihari'),('bi','Bislama'),('bn','Bengali'),('bo','Tibetan'),('br','Breton'),('ca','Catalan'),('co','Corsican'),('cs','Czech'),('cy','Welsh'),('da','Danish'),('de','German'),('dz','Bhutani'),('el','Greek'),('en','English'),('eo','Esperanto'),('es','Spanish'),('et','Estonian'),('eu','Basque'),('fa','Persian'),('fi','Finnish'),('fj','Fiji'),('fo','Faroese'),('fr','French'),('fy','Frisian'),('ga','Irish'),('gd','Scots Gaelic'),('gl','Galician'),('gn','Guarani'),('gu','Gujarati'),('ha','Hausa'),('he','Hebrew'),('hi','Hindi'),('hr','Croatian'),('hu','Hungarian'),('hy','Armenian'),('ia','Interlingua'),('id','Indonesian'),('ie','Interlingue'),('ik','Inupiak'),('is','Icelandic'),('it','Italian'),('iu','Inuktitut'),('ja','Japanese'),('jw','Javanese'),('ka','Georgian'),('kk','Kazakh'),('kl','Greenlandic'),('km','Cambodian'),('kn','Kannada'),('ko','Korean'),('ks','Kashmiri'),('ku','Kurdish'),('ky','Kirghiz'),('la','Latin'),('ln','Lingala'),('lo','Laothian'),('lt','Lithuanian'),('lv','Latvian, Lettish'),('mg','Malagasy'),('mi','Maori'),('mk','Macedonian'),('ml','Malayalam'),('mn','Mongolian'),('mo','Moldavian'),('mr','Marathi'),('ms','Malay'),('mt','Maltese'),('my','Burmese'),('na','Nauru'),('ne','Nepali'),('nl','Dutch'),('no','Norwegian'),('oc','Occitan'),('om','Oromo'),('or','Oriya'),('pa','Punjabi'),('pl','Polish'),('ps','Pashto, Pushto'),('pt','Portuguese'),('qu','Quechua'),('rm','Rhaeto-Romance'),('rn','Kirundi'),('ro','Romanian'),('ru','Russian'),('rw','Kinyarwanda'),('sa','Sanskrit'),('sd','Sindhi'),('sg','Sangho'),('sh','Serbo-Croatian'),('si','Sinhalese'),('sk','Slovak'),('sl','Slovenian'),('sm','Samoan'),('sn','Shona'),('so','Somali'),('sq','Albanian'),('sr','Serbian'),('ss','Siswati'),('st','Sesotho'),('su','Sundanese'),('sv','Swedish'),('sw','Swahili'),('ta','Tamil'),('te','Telugu'),('tg','Tajik'),('th','Thai'),('ti','Tigrinya'),('tk','Turkmen'),('tl','Tagalog'),('tn','Setswana'),('to','Tonga'),('tr','Turkish'),('ts','Tsonga'),('tt','Tatar'),('tw','Twi'),('ug','Uighur'),('uk','Ukrainian'),('ur','Urdu'),('uz','Uzbek'),('vi','Vietnamese'),('vo','Volapuk'),('wo','Wolof'),('xh','Xhosa'),('yi','Yiddish'),('yo','Yoruba'),('za','Zhuang'),('zh','Chinese'),('zu','Zulu');
UNLOCK TABLES;
/*!40000 ALTER TABLE be_language ENABLE KEYS */;

#
# Table structure for table `be_link`
#

DROP TABLE IF EXISTS be_link;
CREATE TABLE be_link (
  linkID smallint(5) unsigned NOT NULL auto_increment,
  url varchar(255) default NULL,
  author_id smallint(5) unsigned default NULL,
  dateCreated int(10) unsigned NOT NULL default '0',
  dateModified int(10) unsigned NOT NULL default '0',
  dateAvailable int(10) unsigned NOT NULL default '0',
  dateRemoved int(10) unsigned NOT NULL default '0',
  content_type varchar(8) NOT NULL default '',
  hide tinyint(2) default '0',
  restrict2members int(5) default NULL,
  hitCounter smallint(10) NOT NULL default '0',
  priority smallint(5) unsigned default '0',
  PRIMARY KEY  (linkID),
  KEY linkID (linkID),
  KEY url (url)
) TYPE=MyISAM;

#
# Dumping data for table `be_link`
#


/*!40000 ALTER TABLE be_link DISABLE KEYS */;
LOCK TABLES be_link WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_link ENABLE KEYS */;

#
# Table structure for table `be_link2articlesGroup`
#

DROP TABLE IF EXISTS be_link2articlesGroup;
CREATE TABLE be_link2articlesGroup (
  linkID smallint(5) unsigned NOT NULL default '0',
  articleID smallint(5) unsigned NOT NULL default '0',
  KEY linkID (linkID),
  KEY articleID (articleID)
) TYPE=MyISAM;

#
# Dumping data for table `be_link2articlesGroup`
#


/*!40000 ALTER TABLE be_link2articlesGroup DISABLE KEYS */;
LOCK TABLES be_link2articlesGroup WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_link2articlesGroup ENABLE KEYS */;

#
# Table structure for table `be_link2articlesGroupText`
#

DROP TABLE IF EXISTS be_link2articlesGroupText;
CREATE TABLE be_link2articlesGroupText (
  linkTextID smallint(5) unsigned NOT NULL auto_increment,
  linkID smallint(5) NOT NULL default '0',
  languageID char(3) NOT NULL default '',
  title varchar(255) NOT NULL default '',
  description varchar(255) default NULL,
  originalText smallint(5) default NULL,
  PRIMARY KEY  (linkTextID),
  KEY linkTextID (linkTextID),
  KEY linkID (linkID),
  KEY languageID (languageID)
) TYPE=MyISAM;

#
# Dumping data for table `be_link2articlesGroupText`
#


/*!40000 ALTER TABLE be_link2articlesGroupText DISABLE KEYS */;
LOCK TABLES be_link2articlesGroupText WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_link2articlesGroupText ENABLE KEYS */;

#
# Table structure for table `be_link2section`
#

DROP TABLE IF EXISTS be_link2section;
CREATE TABLE be_link2section (
  linkID smallint(5) unsigned NOT NULL default '0',
  sectionID smallint(5) unsigned NOT NULL default '0',
  KEY articleID (linkID),
  KEY articleSectionID (sectionID)
) TYPE=MyISAM;

#
# Dumping data for table `be_link2section`
#


/*!40000 ALTER TABLE be_link2section DISABLE KEYS */;
LOCK TABLES be_link2section WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_link2section ENABLE KEYS */;

#
# Table structure for table `be_linkText`
#

DROP TABLE IF EXISTS be_linkText;
CREATE TABLE be_linkText (
  linkTextID smallint(5) unsigned NOT NULL auto_increment,
  linkID smallint(5) NOT NULL default '0',
  languageID char(3) NOT NULL default '',
  title varchar(255) NOT NULL default '',
  url varchar(255) default '',
  description text,
  description_source text,
  title_source varchar(255) NOT NULL default '',
  originalText smallint(5) default NULL,
  PRIMARY KEY  (linkTextID),
  KEY linkTextID (linkTextID),
  KEY linkID (linkID),
  KEY languageID (languageID)
) TYPE=MyISAM;

#
# Dumping data for table `be_linkText`
#


/*!40000 ALTER TABLE be_linkText DISABLE KEYS */;
LOCK TABLES be_linkText WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_linkText ENABLE KEYS */;

#
# Table structure for table `be_linkTextValidation`
#

DROP TABLE IF EXISTS be_linkTextValidation;
CREATE TABLE be_linkTextValidation (
  linkTextID smallint(5) NOT NULL default '0',
  validationState enum('VALID','MALFORMED_URL','UNABLE_TO_CONNECT','INVALID_PROTOCOL','INVALID','UNKNOWN') default 'UNKNOWN',
  dateValid int(10) unsigned NOT NULL default '0',
  dateChecked int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (linkTextID)
) TYPE=MyISAM;

#
# Dumping data for table `be_linkTextValidation`
#


/*!40000 ALTER TABLE be_linkTextValidation DISABLE KEYS */;
LOCK TABLES be_linkTextValidation WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_linkTextValidation ENABLE KEYS */;

#
# Table structure for table `be_mutex`
#

DROP TABLE IF EXISTS be_mutex;
CREATE TABLE be_mutex (
  mutexName varchar(127) NOT NULL default '',
  mutexTime int(11) NOT NULL default '0',
  PRIMARY KEY  (mutexName)
) TYPE=MyISAM;

#
# Dumping data for table `be_mutex`
#


/*!40000 ALTER TABLE be_mutex DISABLE KEYS */;
LOCK TABLES be_mutex WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_mutex ENABLE KEYS */;

#
# Table structure for table `be_section2section`
#

DROP TABLE IF EXISTS be_section2section;
CREATE TABLE be_section2section (
  parentSectionID smallint(5) unsigned NOT NULL default '0',
  childSectionID smallint(5) unsigned NOT NULL default '0',
  KEY parentSectionID (parentSectionID),
  KEY childSectionID (childSectionID)
) TYPE=MyISAM;

#
# Dumping data for table `be_section2section`
#


/*!40000 ALTER TABLE be_section2section DISABLE KEYS */;
LOCK TABLES be_section2section WRITE;
INSERT INTO be_section2section VALUES (0,1),(1,2),(1,3);
UNLOCK TABLES;
/*!40000 ALTER TABLE be_section2section ENABLE KEYS */;

#
# Table structure for table `be_sectionText`
#

DROP TABLE IF EXISTS be_sectionText;
CREATE TABLE be_sectionText (
  sectionTextID smallint(5) unsigned NOT NULL auto_increment,
  sectionID smallint(5) NOT NULL default '0',
  languageID char(3) NOT NULL default '',
  URLname varchar(255) NOT NULL default '',
  title varchar(255) default NULL,
  blurb text,
  content text,
  content_source text NOT NULL,
  blurb_source text NOT NULL,
  title_source varchar(255) NOT NULL default '',
  meta_keywords varchar(255) default NULL,
  meta_description varchar(255) default NULL,
  keywordObjects tinytext,
  template varchar(55) default NULL,
  originalText smallint(5) default NULL,
  commentIDtext int(7) default '0',
  PRIMARY KEY  (sectionTextID),
  KEY sectionTextID (sectionTextID),
  KEY sectionID (sectionID),
  KEY languageID (languageID),
  KEY sectionLanguage (sectionID,languageID),
  KEY sectionID_2 (sectionID,languageID),
  KEY URLname (URLname)
) TYPE=MyISAM;

#
# Dumping data for table `be_sectionText`
#


/*!40000 ALTER TABLE be_sectionText DISABLE KEYS */;
LOCK TABLES be_sectionText WRITE;
INSERT INTO be_sectionText VALUES (1,1,'en','Home','Home','Introduction to Back-End','\r\n<p><strong>Back-End</strong>  allows even the non-technical to manage  any website easily, through a web browser, on any operating system. Fast, flexible and easy to understand, <strong>Back-End</strong> puts you in charge of your site, saving your organisation time and money in the process.<br /></p><p>We have released <a href=\"http://sourceforge.net/project/showfiles.php?group_id=6763\">Back-End version 0.7.2.1</a> which is easy to install and packed with new features including the ability to undelete sections and articles.  The latest release is also working to be fully xhtml compliant.  <br /></p>\r\n<p><strong>Back-End</strong> is particularly suitable for advocacy organisations, with a suite of tools that allow you to create and manage polls and petitions, and to interact with your visitors, making it faster and easier for you to respond to issues as they arise, and to organise members activity.</p>\r\n<p>An <strong>Open Source</strong> program, under the <strong>General Public License</strong>, <strong>Back-End</strong> saves you money on license fees while allowing you, or your IT volunteers, the freedom to adapt the program to your unique needs.</p>\r\n<p>To learn more, please explore the site.  For more detailed information, you can post a question in the <a href=\"http://sourceforge.net/forum/forum.php?forum_id=20982\"><strong>help forum</strong></a>, or join the <strong><a href=\"http://lists.sourceforge.net/lists/listinfo/back-end-support\">support mailing list</a></strong>.  If you run into a bug, please add it to the <a href=\"http://sourceforge.net/tracker/?group_id=6763&amp;atid=106763\" style=\"font-weight: bold;\">bug tracker</a>.  If you\'ve got a feature request, post an <a href=\"http://sourceforge.net/tracker/?group_id=6763&amp;atid=356763\">RFE</a>.<br /></p>','\r\n<p><strong>Back-End</strong>  allows even the non-technical to manage  any website easily, through a web browser, on any operating system. Fast, flexible and easy to understand, <strong>Back-End</strong> puts you in charge of your site, saving your organisation time and money in the process.<br /></p><p>We have released <a href=\"http://sourceforge.net/project/showfiles.php?group_id=6763\">Back-End version 0.7.2.1</a> which is easy to install and packed with new features including the ability to undelete sections and articles.  The latest release is also working to be fully xhtml compliant.  <br /></p>\r\n<p><strong>Back-End</strong> is particularly suitable for advocacy organisations, with a suite of tools that allow you to create and manage polls and petitions, and to interact with your visitors, making it faster and easier for you to respond to issues as they arise, and to organise members activity.</p>\r\n<p>An <strong>Open Source</strong> program, under the <strong>General Public License</strong>, <strong>Back-End</strong> saves you money on license fees while allowing you, or your IT volunteers, the freedom to adapt the program to your unique needs.</p>\r\n<p>To learn more, please explore the site.  For more detailed information, you can post a question in the <a href=\"http://sourceforge.net/forum/forum.php?forum_id=20982\"><strong>help forum</strong></a>, or join the <strong><a href=\"http://lists.sourceforge.net/lists/listinfo/back-end-support\">support mailing list</a></strong>.  If you run into a bug, please add it to the <a href=\"http://sourceforge.net/tracker/?group_id=6763&amp;atid=106763\" style=\"font-weight: bold;\">bug tracker</a>.  If you\'ve got a feature request, post an <a href=\"http://sourceforge.net/tracker/?group_id=6763&atid=356763\">RFE</a>.<br /></p>','Introduction to Back-End','Home','Home Page','Back-End.org A GPL CMS based on PHP/MySQL',NULL,'home',1,0),(2,2,'en','','Admin','','Just to show admin features.','Just to show admin features.','','Admin','','',NULL,'',0,21),(4,2,'fr','','Admin','','Just to show admin features.','Just to show admin features.','','Admin','','',NULL,'',0,21),(3,3,'en','','Site Map','','Site Map Control','Site Map Control','','Site Map','','',NULL,'',0,22),(5,3,'fr','','Site Map','','Site Map Control','Site Map Control','','Site Map','','',NULL,'',0,22);
UNLOCK TABLES;
/*!40000 ALTER TABLE be_sectionText ENABLE KEYS */;

#
# Table structure for table `be_sections`
#

DROP TABLE IF EXISTS be_sections;
CREATE TABLE be_sections (
  sectionID smallint(5) unsigned NOT NULL auto_increment,
  URLname varchar(255) NOT NULL default '',
  author_id smallint(5) unsigned default NULL,
  subsiteID smallint(5) NOT NULL default '0',
  dateCreated int(10) unsigned NOT NULL default '0',
  dateModified int(10) unsigned NOT NULL default '0',
  dateAvailable int(10) unsigned NOT NULL default '0',
  dateRemoved int(10) unsigned NOT NULL default '0',
  dateForSort int(10) unsigned NOT NULL default '0',
  content_type varchar(8) NOT NULL default '',
  main_languageID char(2) NOT NULL default '',
  hide tinyint(2) unsigned NOT NULL default '0',
  deleted tinyint(2) unsigned NOT NULL default '0',
  restrict2members int(5) unsigned NOT NULL default '0',
  showSections tinyint(2) default '0',
  showArticles tinyint(2) default '0',
  showLinkSubmit tinyint(2) default '1',
  pollID smallint(5) unsigned default NULL,
  hitCounter smallint(10) unsigned NOT NULL default '0',
  priority smallint(5) default '0',
  redirect varchar(255) default NULL,
  commentID int(7) default '0',
  orderbySections varchar(55) default NULL,
  orderbySectionsLogic varchar(4) default NULL,
  orderbyArticles varchar(55) default NULL,
  orderbyArticlesLogic varchar(4) default NULL,
  orderbyLinks varchar(55) default NULL,
  orderbyLinksLogic varchar(4) default NULL,
  PRIMARY KEY  (sectionID,URLname),
  KEY sectionID (sectionID),
  KEY URLname (URLname),
  KEY hide (hide,restrict2members,dateAvailable,dateRemoved,hitCounter),
  KEY hide_2 (hide,restrict2members,dateAvailable,dateRemoved),
  KEY sectionID_2 (sectionID,hide,restrict2members,dateAvailable,dateRemoved,hitCounter),
  KEY subsiteID (subsiteID)
) TYPE=MyISAM;

#
# Dumping data for table `be_sections`
#


/*!40000 ALTER TABLE be_sections DISABLE KEYS */;
LOCK TABLES be_sections WRITE;
INSERT INTO be_sections VALUES (1,'Home',0,0,1063782000,1066402005,1063782000,0,0,'html','',0,0,0,0,1,0,0,2,100,'',1,'dateCreated','desc','dateCreated','desc','dateCreated','desc'),(2,'Admin',1,0,1041835651,1041835651,1041835651,1830775651,0,'','',1,0,0,1,0,0,0,0,1,'',1,'dateCreated','desc','dateCreated','desc','dateCreated','desc'),(3,'sitemap',1,0,1041835651,1041835651,1041835651,1830775651,0,'','',1,0,0,1,0,0,0,0,1,'',1,'dateCreated','desc','dateCreated','desc','dateCreated','desc');
UNLOCK TABLES;
/*!40000 ALTER TABLE be_sections ENABLE KEYS */;

#
# Table structure for table `be_upload`
#

DROP TABLE IF EXISTS be_upload;
CREATE TABLE be_upload (
  uploadID smallint(6) NOT NULL auto_increment,
  filename varchar(255) NOT NULL default '',
  path varchar(255) NOT NULL default '',
  url varchar(255) NOT NULL default '',
  shortDescription varchar(255) default NULL,
  longDescription tinytext,
  fileType varchar(25) NOT NULL default '',
  uploadedBy varchar(25) default NULL,
  imageHeight int(10) default NULL,
  imageWidth int(10) default NULL,
  thumbnail blob,
  rawSize int(11) default NULL,
  time int(11) default NULL,
  perm varchar(20) default NULL,
  subsiteID smallint(5) default NULL,
  subdir VARCHAR( 25 ) default NULL,
  PRIMARY KEY  (uploadID),
  KEY uploadID (uploadID),
  KEY filename (filename),
  INDEX subdir(filename, subdir),
  INDEX subsiteID(filename, subsiteID)
) TYPE=MyISAM;

#
# Dumping data for table `be_upload`
#


/*!40000 ALTER TABLE be_upload DISABLE KEYS */;
LOCK TABLES be_upload WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_upload ENABLE KEYS */;

#
# Table structure for table `be_upload2article`
#

DROP TABLE IF EXISTS be_upload2article;
CREATE TABLE be_upload2article (
  articleTextID smallint(5) unsigned NOT NULL default '0',
  language char(3) NOT NULL default '',
  filename varchar(255) NOT NULL default '',
  caption varchar(255) NOT NULL default '',
  description text NOT NULL,
  dateUploaded int(11) default '0',
  mainPage smallint(2) NOT NULL default '0',
  KEY articleTextID (articleTextID)
) TYPE=MyISAM;

#
# Dumping data for table `be_upload2article`
#


/*!40000 ALTER TABLE be_upload2article DISABLE KEYS */;
LOCK TABLES be_upload2article WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE be_upload2article ENABLE KEYS */;

#
# Table structure for table `db_sequence`
#

DROP TABLE IF EXISTS db_sequence;
CREATE TABLE db_sequence (
  seq_name varchar(127) NOT NULL default '',
  nextid int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (seq_name)
) TYPE=MyISAM;

#
# Dumping data for table `db_sequence`
#


/*!40000 ALTER TABLE db_sequence DISABLE KEYS */;
LOCK TABLES db_sequence WRITE;
INSERT INTO db_sequence VALUES ('psl_comment_seq',58),('psl_topic_seq',36),('psl_comment_dep_seq',22),('psl_topic_lut_seq',70),('psl_section_lut_seq',243),('psl_submission_seq',11),('psl_topic_submission_lut_seq',21),('psl_section_submission_lut_seq',13),('psl_variable_seq',102),('psl_section_seq',9),('psl_block_seq',4),('psl_author_seq',20),('psl_infolog',211),('psl_mailinglist_seq',2),('psl_glossary_seq',1),('psl_blocktype_seq',120),('psl_section_block_lut_seq',292),('psl_permission_seq',213),('psl_group_seq',212),('psl_group_section_lut_seq',66),('psl_group_permission_lut_seq',213),('psl_group_group_lut_seq',221),('psl_author_group_lut_seq',27),('be_sections',4),('be_articles',8),('be_link',1),('be_images',1),('psl_blockText_seq',3);
UNLOCK TABLES;
/*!40000 ALTER TABLE db_sequence ENABLE KEYS */;


#
# Table structure for table `psl_author`
#

DROP TABLE IF EXISTS psl_author;
CREATE TABLE psl_author (
  author_id int(11) unsigned NOT NULL default '0',
  author_name varchar(50) NOT NULL default '',
  author_realname varchar(60) default NULL,
  url varchar(50) default NULL,
  email varchar(50) default NULL,
  quote varchar(50) default NULL,
  password varchar(64) NOT NULL default '',
  seclev int(11) NOT NULL default '0',
  perms varchar(255) default NULL,
  question varchar(255) default NULL,
  answer varchar(255) default NULL,
  author_options text,
  defaultCommentThreshold int(5) NOT NULL DEFAULT 0,
  PRIMARY KEY  (author_id),
  UNIQUE KEY author_name (author_name)
) TYPE=MyISAM;

#
# Dumping data for table `psl_author`
#


/*!40000 ALTER TABLE psl_author DISABLE KEYS */;
LOCK TABLES psl_author WRITE;
INSERT INTO psl_author VALUES (1,'root','Administrator','http://www.back-end.org','root@back-end.org','','db319ad4077281c5c1dbee6deeecb97e',1000000,'user,topic,story,storyeditor,comment,section,link,gallery,submission,block,poll,author,variable,glossary,mailinglist,local,upload,logging,root,template,subsite,linkAdmin,target,action,contact,bibliography,petition','','','',0),
(20,'nobody','Anonymous','','','','7dae6bd6d92a6c64367c27ea48169e4e',0,'','','','N;',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_author ENABLE KEYS */;

#
# Table structure for table `psl_author_group_lut`
#

DROP TABLE IF EXISTS psl_author_group_lut;
CREATE TABLE psl_author_group_lut (
  lut_id int(11) unsigned NOT NULL default '0',
  author_id int(11) unsigned default NULL,
  group_id int(11) unsigned default NULL,
  subsite_id int(11) default NULL,
  UNIQUE KEY lut_id_2 (lut_id),
  KEY lut_id (lut_id)
) TYPE=MyISAM;

#
# Dumping data for table `psl_author_group_lut`
#


/*!40000 ALTER TABLE psl_author_group_lut DISABLE KEYS */;
LOCK TABLES psl_author_group_lut WRITE;
INSERT INTO psl_author_group_lut VALUES (26,1,24,NULL),(27,20,20,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_author_group_lut ENABLE KEYS */;

#
# Table structure for table `psl_block`
#

DROP TABLE IF EXISTS psl_block;
CREATE TABLE psl_block (
  id int(11) unsigned NOT NULL default '0',
  type int(11) NOT NULL default '0',
  title varchar(255) NOT NULL default '',
  expire_length int(11) NOT NULL default '0',
  location varchar(254) NOT NULL default '',
  source_url varchar(254) NOT NULL default '',
  cache_data text NOT NULL,
  block_options text,
  ordernum int(10) unsigned NOT NULL default '0',
  date_issued int(11) default NULL,
  PRIMARY KEY  (id),
  KEY id (id)
) TYPE=MyISAM;

#
# Dumping data for table `psl_block`
#


/*!40000 ALTER TABLE psl_block DISABLE KEYS */;
LOCK TABLES psl_block WRITE;
INSERT INTO psl_block VALUES (1,12,'Administration',0,'','menu_ary=menuadmin&tpl=navbarBlockh','<!-- START: navbarBlock.tpl -->\n       &nbsp<a href=\"/profile.php\"><b><font size=\"-1\" face=\"Verdana,Arial,Helvetica,San-Serif\">My Preferences</b></a>\n       &nbsp<a href=\"/admin/blockAdmin.php\"><b><font size=\"-1\" face=\"Verdana,Arial,Helvetica,San-Serif\">Block</b></a>\n       &nbsp<a href=\"/admin/pollAdmin.php\"><b><font size=\"-1\" face=\"Verdana,Arial,Helvetica,San-Serif\">Poll</b></a>\n       &nbsp<a href=\"/admin/authorAdmin.php\"><b><font size=\"-1\" face=\"Verdana,Arial,Helvetica,San-Serif\">Author</b></a>\n       &nbsp<a href=\"/admin/infologAdmin.php\"><b><font size=\"-1\" face=\"Verdana,Arial,Helvetica,San-Serif\">Logging</b></a>\n       &nbsp<a href=\"/admin/groupAdmin.php\"><b><font size=\"-1\" face=\"Verdana,Arial,Helvetica,San-Serif\">Group</b></a>\n       &nbsp<a href=\"/admin/BE_bibAdmin.php\"><b><font size=\"-1\" face=\"Verdana,Arial,Helvetica,San-Serif\">Bibliography</b></a>\n       &nbsp<a href=\"/admin/BE_profileAdmin.php\"><b><font size=\"-1\" face=\"Verdana,Arial,Helvetica,San-Serif\">Profile</b></a>\n       &nbsp<a href=\"/admin/PET_petitionAdmin.php\"><b><font size=\"-1\" face=\"Verdana,Arial,Helvetica,San-Serif\">Petition</b></a>\n       &nbsp<a href=\"/admin/BE_followupAdmin.php\"><b><font size=\"-1\" face=\"Verdana,Arial,Helvetica,San-Serif\">Followup</b></a>\n       &nbsp<a href=\"/admin/BE_sectionAdmin.php\"><b><font size=\"-1\" face=\"Verdana,Arial,Helvetica,San-Serif\">Section</b></a>\n       &nbsp<a href=\"/admin/BE_articleAdmin.php\"><b><font size=\"-1\" face=\"Verdana,Arial,Helvetica,San-Serif\">Article</b></a>\n       &nbsp<a href=\"/admin/BE_linkAdmin.php\"><b><font size=\"-1\" face=\"Verdana,Arial,Helvetica,San-Serif\">Link</b></a>\n       &nbsp<a href=\"/admin/BE_editTemplateAdmin.php\"><b><font size=\"-1\" face=\"Verdana,Arial,Helvetica,San-Serif\">Edit Templates</b></a>\n       &nbsp<a href=\"/admin/BE_uploadAdmin.php\"><b><font size=\"-1\" face=\"Verdana,Arial,Helvetica,San-Serif\">Upload</b></a>\n       &nbsp<a href=\"/admin/BE_galleryAdmin.php\"><b><font size=\"-1\" face=\"Verdana,Arial,Helvetica,San-Serif\">Gallery</b></a>\n       &nbsp<a href=\"/admin/BE_actionAdmin.php\"><b><font size=\"-1\" face=\"Verdana,Arial,Helvetica,San-Serif\">Action</b></a>\n       &nbsp<a href=\"/admin/BE_contactAdmin.php\"><b><font size=\"-1\" face=\"Verdana,Arial,Helvetica,San-Serif\">Contact</b></a>\n<!-- END: navbarBlock.tpl -->\n','a:5:{s:6:\"column\";s:6:\"center\";s:5:\"width\";s:0:\"\";s:8:\"box_type\";s:0:\"\";s:5:\"perms\";s:4:\"user\";s:10:\"cache_data\";a:1:{s:2:\"fr\";a:1:{s:6:\"allcss\";a:2:{s:10:\"cache_data\";s:1987:\"    <ul>\n      <li class=\"fermerlasessionderoot\"><a href=\"/be_dev/login.php?logout=yes&redirect=%2Fbe_dev%2Fsearch.php%3Fquery%3D%26error%3DImpossible%2Bde%2Btrouver%2Bla%2Bsection%253A%2B%2B\" title=\"Fermer la session de root\">Fermer la session de root</a></li>\n      <li class=\"mypreferences\"><a href=\"/be_dev/profile.php\" title=\"Mes pr&eacute;f&eacute;nces\">Mes pr&eacute;f&eacute;nces</a></li>\n      <li class=\"block\"><a href=\"/be_dev/admin/blockAdmin.php\" title=\"Bloc\">Bloc</a></li>\n      <li class=\"poll\"><a href=\"/be_dev/admin/pollAdmin.php\" title=\"Sondage\">Sondage</a></li>\n      <li class=\"users\"><a href=\"/be_dev/admin/authorAdmin.php\" title=\"Users\">Users</a></li>\n      <li class=\"logging\"><a href=\"/be_dev/admin/infologAdmin.php\" title=\"Journal\">Journal</a></li>\n      <li class=\"group\"><a href=\"/be_dev/admin/groupAdmin.php\" title=\"groupe\">groupe</a></li>\n      <li class=\"section\"><a href=\"/be_dev/admin/BE_sectionAdmin.php\" title=\"Section\">Section</a></li>\n      <li class=\"article\"><a href=\"/be_dev/admin/BE_articleAdmin.php\" title=\"article\">article</a></li>\n      <li class=\"links\"><a href=\"/be_dev/admin/BE_linkAdmin.php\" title=\"Liens\">Liens</a></li>\n      <li class=\"upload\"><a href=\"/be_dev/admin/BE_uploadAdmin.php\" title=\"T&eacute;l&eacute;charger\">T&eacute;l&eacute;charger</a></li>\n      <li class=\"templates\"><a href=\"/be_dev/admin/BE_editTemplateAdmin.php\" title=\"Templates\">Templates</a></li>\n      <li class=\"contact\"><a href=\"/be_dev/admin/BE_contactAdmin.php\" title=\"Contacter\">Contacter</a></li>\n      <li class=\"followup\"><a href=\"/be_dev/admin/BE_followupAdmin.php\" title=\"Followup\">Followup</a></li>\n      <li class=\"action\"><a href=\"/be_dev/admin/BE_actionAdmin.php\" title=\"Action\">Action</a></li>\n      <li class=\"petitions\"><a href=\"/be_dev/admin/BE_petitionAdmin.php\" title=\"P&eacute;titions\">P&eacute;titions</a></li>\n      <li class=\"viewfeedback\"><a href=\"/be_dev/admin/BE_feedbackAdmin.php\" title=\"View Feedback\">View Feedback</a></li>\n    </ul>\n\";s:11:\"last_update\";i:1111576507;}}}}',0,1070903113),(3,113,'Language',0,'','','','a:5:{s:6:\"column\";s:4:\"left\";s:5:\"width\";s:4:\"100%\";s:8:\"box_type\";s:5:\"fancy\";s:5:\"perms\";s:0:\"\";s:13:\"template_file\";s:0:\"\";}',100,1111576103),(4,113,'Language',0,'','','<script type=\"text/javascript\">\nfunction submitForm() {\n/*\n   window.location = document.getElementById(\'languageSelect\').value;\n   return false;\n*/\n/*\n   window.document.languageChoice.currentURL.value = window.location;\n   window.document.languageChoice.submit();\n*/\n   document.getElementById(\'SwitchLangCurrentURL\').value = window.location;\n   document.getElementById(\'languageChoice\').submit();\n}\n</script>\n\n<form method=\"post\" action=\"/be_dev/index.php\" id=\"languageChoice\">\n<p><select name=\"language\" id=\"languageSelect\" onchange=\"javascript:submitForm();\">\n <option value=\"en\" selected=\"selected\">English</option>\n  <option value=\"fr\" >fran&ccedil;ais</option>\n</select>\n<input id=\"SwitchLangCurrentURL\" name=\"currentURL\" type=\"hidden\" />\n<noscript><input type=\"submit\" name=\"submit\" value=\"Go\" /></noscript>\n</p>\n</form>\n','a:6:{s:6:\"column\";s:4:\"left\";s:5:\"width\";s:4:\"100%\";s:8:\"box_type\";s:5:\"fancy\";s:5:\"perms\";s:0:\"\";s:13:\"template_file\";s:0:\"\";s:10:\"cache_data\";a:1:{s:2:\"fr\";a:1:{s:6:\"allcss\";a:2:{s:10:\"cache_data\";s:829:\"<script type=\"text/javascript\">\nfunction submitForm() {\n/*\n   window.location = document.getElementById(\'languageSelect\').value;\n   return false;\n*/\n/*\n   window.document.languageChoice.currentURL.value = window.location;\n   window.document.languageChoice.submit();\n*/\n   document.getElementById(\'SwitchLangCurrentURL\').value = window.location;\n   document.getElementById(\'languageChoice\').submit();\n}\n</script>\n\n<form method=\"post\" action=\"/be_dev/index.php\" id=\"languageChoice\">\n<p><select name=\"language\" id=\"languageSelect\" onchange=\"javascript:submitForm();\">\n  <option value=\"en\" >English</option>\n  <option value=\"fr\" selected=\"selected\">fran&ccedil;ais</option>\n</select>\n<input id=\"SwitchLangCurrentURL\" name=\"currentURL\" type=\"hidden\" />\n<noscript><input type=\"submit\" name=\"submit\" value=\"Go\" /></noscript>\n</p>\n</form>\n\";s:11:\"last_update\";i:1111576507;}}}}',100,1111579775);
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_block ENABLE KEYS */;

#
# Table structure for table `psl_blockText`
#

DROP TABLE IF EXISTS psl_blockText;
CREATE TABLE psl_blockText (
  textID int(11) unsigned NOT NULL default '0',
  id int(11) unsigned NOT NULL default '0',
  languageID char(3) NOT NULL default '',
  title varchar(255) NOT NULL default '',
  location varchar(254) NOT NULL default '',
  source_url varchar(254) NOT NULL default '',
  cache_data text NOT NULL,
  date_issued int(10) unsigned default NULL,
  PRIMARY KEY  (textID),
  KEY id (id),
  KEY id_2 (id,languageID)
) TYPE=MyISAM;

#
# Dumping data for table `psl_blockText`
#


/*!40000 ALTER TABLE psl_blockText DISABLE KEYS */;
LOCK TABLES psl_blockText WRITE;
INSERT INTO psl_blockText VALUES (1,1,'fr','Administration','','menu_ary=menuadmin&tpl=navbarBlockh','    <ul>\n      <li class=\"fermerlasessionderoot\"><a href=\"/be_dev/login.php?logout=yes&redirect=%2Fbe_dev%2Fsearch.php%3Fquery%3D%26error%3DImpossible%2Bde%2Btrouver%2Bla%2Bsection%253A%2B%2B\" title=\"Fermer la session de root\">Fermer la session de root</a></li>\n      <li class=\"mypreferences\"><a href=\"/be_dev/profile.php\" title=\"Mes pr&eacute;f&eacute;nces\">Mes pr&eacute;f&eacute;nces</a></li>\n      <li class=\"block\"><a href=\"/be_dev/admin/blockAdmin.php\" title=\"Bloc\">Bloc</a></li>\n      <li class=\"poll\"><a href=\"/be_dev/admin/pollAdmin.php\" title=\"Sondage\">Sondage</a></li>\n      <li class=\"users\"><a href=\"/be_dev/admin/authorAdmin.php\" title=\"Users\">Users</a></li>\n      <li class=\"logging\"><a href=\"/be_dev/admin/infologAdmin.php\" title=\"Journal\">Journal</a></li>\n      <li class=\"group\"><a href=\"/be_dev/admin/groupAdmin.php\" title=\"groupe\">groupe</a></li>\n      <li class=\"section\"><a href=\"/be_dev/admin/BE_sectionAdmin.php\" title=\"Section\">Section</a></li>\n      <li class=\"article\"><a href=\"/be_dev/admin/BE_articleAdmin.php\" title=\"article\">article</a></li>\n      <li class=\"links\"><a href=\"/be_dev/admin/BE_linkAdmin.php\" title=\"Liens\">Liens</a></li>\n      <li class=\"upload\"><a href=\"/be_dev/admin/BE_uploadAdmin.php\" title=\"T&eacute;l&eacute;charger\">T&eacute;l&eacute;charger</a></li>\n      <li class=\"templates\"><a href=\"/be_dev/admin/BE_editTemplateAdmin.php\" title=\"Templates\">Templates</a></li>\n      <li class=\"contact\"><a href=\"/be_dev/admin/BE_contactAdmin.php\" title=\"Contacter\">Contacter</a></li>\n      <li class=\"followup\"><a href=\"/be_dev/admin/BE_followupAdmin.php\" title=\"Followup\">Followup</a></li>\n      <li class=\"action\"><a href=\"/be_dev/admin/BE_actionAdmin.php\" title=\"Action\">Action</a></li>\n      <li class=\"petitions\"><a href=\"/be_dev/admin/BE_petitionAdmin.php\" title=\"P&eacute;titions\">P&eacute;titions</a></li>\n      <li class=\"viewfeedback\"><a href=\"/be_dev/admin/BE_feedbackAdmin.php\" title=\"View Feedback\">View Feedback</a></li>\n    </ul>\n',1111576507),(2,4,'en','Language','','','<script type=\"text/javascript\">\nfunction submitForm() {\n/*\n   window.location = document.getElementById(\'languageSelect\').value;\n   return false;\n*/\n/*\n   window.document.languageChoice.currentURL.value = window.location;\n   window.document.languageChoice.submit();\n*/\n   document.getElementById(\'SwitchLangCurrentURL\').value = window.location;\n   document.getElementById(\'languageChoice\').submit();\n}\n</script>\n\n<form method=\"post\" action=\"/be_dev/index.php\" id=\"languageChoice\">\n<p><select name=\"language\" id=\"languageSelect\" onchange=\"javascript:submitForm();\">\n  <option value=\"en\" selected=\"selected\">English</option>\n  <option value=\"fr\" >fran&ccedil;ais</option>\n</select>\n<input id=\"SwitchLangCurrentURL\" name=\"currentURL\" type=\"hidden\" />\n<noscript><input type=\"submit\" name=\"submit\" value=\"Go\" /></noscript>\n</p>\n</form>\n',1111579775),(3,4,'fr','Langue','','','<script type=\"text/javascript\">\nfunction submitForm() {\n/*\n   window.location = document.getElementById(\'languageSelect\').value;\n   return false;\n*/\n/*\n   window.document.languageChoice.currentURL.value = window.location;\n   window.document.languageChoice.submit();\n*/\n   document.getElementById(\'SwitchLangCurrentURL\').value = window.location;\n   document.getElementById(\'languageChoice\').submit();\n}\n</script>\n\n<form method=\"post\" action=\"/be_dev/index.php\" id=\"languageChoice\">\n<p><select name=\"language\" id=\"languageSelect\" onchange=\"javascript:submitForm();\">\n  <option value=\"en\" >English</option>\n  <option value=\"fr\" selected=\"selected\">fran&ccedil;ais</option>\n</select>\n<input id=\"SwitchLangCurrentURL\" name=\"currentURL\" type=\"hidden\" />\n<noscript><input type=\"submit\" name=\"submit\" value=\"Go\" /></noscript>\n</p>\n</form>\n',1111576507);
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_blockText ENABLE KEYS */;

#
# Table structure for table `psl_block_type`
#

DROP TABLE IF EXISTS psl_block_type;
CREATE TABLE psl_block_type (
  id int(11) NOT NULL default '0',
  name varchar(20) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY id_2 (id,name),
  KEY id (id)
) TYPE=MyISAM;

#
# Dumping data for table `psl_block_type`
#


/*!40000 ALTER TABLE psl_block_type DISABLE KEYS */;
LOCK TABLES psl_block_type WRITE;
INSERT INTO psl_block_type VALUES (1,'html'),(2,'url'),(3,'rss'),(5,'poll'),(6,'query'),(9,'quote'),(10,'skin'),(11,'login'),(12,'navbar'),(100,'BE_sectionList'),(101,'BE_spotlightArticles'),(102,'BE_newArticles'),(103,'BE_randomLinks'),(104,'BE_relatedArticles'),(105,'BE_relatedCategories'),(106,'BE_whatsPopular'),(107,'BE_relatedKeywords'),(108,'BE_action'),(113,'BE_languageSwitching'),(114,'BE_petitions'),(115,'BE_petitionSigners');
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_block_type ENABLE KEYS */;

SELECT @NextBlockType := max(id) FROM psl_block_type;
INSERT INTO `psl_block_type` VALUES ((@NextBlockType+1), 'BE_listArticles');
INSERT INTO `psl_block_type` VALUES ((@NextBlockType+2), 'BE_listLinks');


#
# Table structure for table `psl_comment`
#

DROP TABLE IF EXISTS psl_comment;
CREATE TABLE psl_comment (
  comment_id int(11) NOT NULL default '0',
  parent_id int(11) NOT NULL default '0',
  story_id int(11) NOT NULL default '0',
  user_id int(15) NOT NULL default '0',
  name varchar(50) NOT NULL default '',
  email varchar(50) default NULL,
  ip varchar(50) default NULL,
  subject varchar(50) NOT NULL default '',
  comment_text text NOT NULL,
  pending tinyint(3) unsigned NOT NULL default '0',
  date_created int(11) default NULL,
  rating int(5) NOT NULL DEFAULT 0,
  PRIMARY KEY  (story_id,comment_id),
  INDEX rating(rating)
) TYPE=MyISAM;

#
# Dumping data for table `psl_comment`
#


/*!40000 ALTER TABLE psl_comment DISABLE KEYS */;
LOCK TABLES psl_comment WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_comment ENABLE KEYS */;

#
# Table structure for table `psl_commentcount`
#

DROP TABLE IF EXISTS psl_commentcount;
CREATE TABLE psl_commentcount (
  count_id int(11) NOT NULL default '0',
  count int(11) unsigned NOT NULL default '0',
  UNIQUE KEY count_id (count_id)
) TYPE=MyISAM;

#
# Dumping data for table `psl_commentcount`
#


/*!40000 ALTER TABLE psl_commentcount DISABLE KEYS */;
LOCK TABLES psl_commentcount WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_commentcount ENABLE KEYS */;

#
# Table structure for table `psl_glossary`
#

DROP TABLE IF EXISTS psl_glossary;
CREATE TABLE psl_glossary (
  id int(10) unsigned NOT NULL default '0',
  term varchar(255) NOT NULL default '',
  def text NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY id (id),
  KEY term (term)
) TYPE=MyISAM;

#
# Dumping data for table `psl_glossary`
#


/*!40000 ALTER TABLE psl_glossary DISABLE KEYS */;
LOCK TABLES psl_glossary WRITE;
INSERT INTO psl_glossary VALUES (1,'PHPSlash','A port of the popular Slashdot code to PHP.');
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_glossary ENABLE KEYS */;

#
# Table structure for table `psl_group`
#

DROP TABLE IF EXISTS psl_group;
CREATE TABLE psl_group (
  group_id int(10) unsigned NOT NULL default '0',
  group_name varchar(60) NOT NULL default '',
  group_description text,
  PRIMARY KEY  (group_id),
  UNIQUE KEY group_name (group_name)
) TYPE=MyISAM;

#
# Dumping data for table `psl_group`
#


/*!40000 ALTER TABLE psl_group DISABLE KEYS */;
LOCK TABLES psl_group WRITE;
INSERT INTO psl_group VALUES (1,'author','Administer all users/authors'),(4,'block','Administer all blocks'),(8,'comment','Administer comments'),(9,'glossary','Administer glossary'),(10,'groupAdmin','Administer groups of permissions'),(11,'logging','View/Delete Infolog'),(12,'mailinglist','phpSlash - Administer Mailing List'),(13,'permissionAdmin','Administer permissions'),(14,'poll','Administer Polls'),(15,'section','Administer Sections'),(16,'story','phpSlash - Administer Stories'),(17,'submission','phpSlash - Administer Submissions'),(18,'topic','phpSlash - Administer Topics'),(19,'variable','Administer site variables'),(20,'nobody','Anonymous user'),(21,'user','General logged in user privileges.'),(22,'commentUser','Ability to add comments - if restricted'),(23,'storyeditor','phpSlash - Permision extend story editor privileges'),(24,'root','All privileges'),(212,'taf','Back-End - Change tell-a-friend messages'),(200,'ContentProvider','Back-End - Permissions allowing users to submit and edit stories in specified subsite'),(201,'subsite','Back-End - Administer subsites'),(202,'upload','Back-End - Administer file uploads'),(203,'gallery','Back-End - Administer file gallery'),(204,'ContentManager','Back-End - Subsite administration including sections, stories, uploads'),(205,'Template','Back-End - Permission to amend templates online'),(206,'linkAdmin','Back-End - Administer Links'),(207,'target','Back-End - Administer targets for online actions'),(208,'action','Back-End - Administer online actions'),(209,'contact','Back-End - Administer contacts for online actions'),(210,'bibliography','Back-End - Administer bibliography'),(211,'petition','Back-End - Administer petitions'),(213,'Member','Back-End - Allows users to access restricted pages.');
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_group ENABLE KEYS */;

#
# Table structure for table `psl_group_group_lut`
#

DROP TABLE IF EXISTS psl_group_group_lut;
CREATE TABLE psl_group_group_lut (
  lut_id int(11) unsigned NOT NULL default '0',
  group_id int(11) unsigned default NULL,
  childgroup_id int(11) unsigned default NULL,
  UNIQUE KEY lut_id_2 (lut_id),
  KEY lut_id (lut_id)
) TYPE=MyISAM;

#
# Dumping data for table `psl_group_group_lut`
#


/*!40000 ALTER TABLE psl_group_group_lut DISABLE KEYS */;
LOCK TABLES psl_group_group_lut WRITE;
INSERT INTO psl_group_group_lut VALUES (254,24,19),(253,24,21),(252,24,202),(251,24,18),(250,24,205),(249,24,207),(248,24,201),(247,24,17),(246,24,23),(245,24,16),(244,24,15),(243,24,14),(242,24,211),(241,24,13),(240,24,20),(239,24,213),(238,24,12),(200,200,16),(201,200,21),(202,204,15),(203,204,16),(204,204,23),(205,204,17),(206,204,202),(207,204,21),(237,24,11),(236,24,206),(235,24,10),(234,24,9),(233,24,203),(213,204,205),(232,24,200),(231,24,204),(230,24,209),(229,24,22),(228,24,8),(227,24,4),(226,24,210),(225,24,1),(224,24,208),(223,213,21);
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_group_group_lut ENABLE KEYS */;

#
# Table structure for table `psl_group_permission_lut`
#

DROP TABLE IF EXISTS psl_group_permission_lut;
CREATE TABLE psl_group_permission_lut (
  lut_id int(11) unsigned NOT NULL default '0',
  group_id int(11) unsigned default NULL,
  permission_id int(11) unsigned default NULL,
  UNIQUE KEY lut_id_2 (lut_id),
  KEY lut_id (lut_id)
) TYPE=MyISAM;

#
# Dumping data for table `psl_group_permission_lut`
#


/*!40000 ALTER TABLE psl_group_permission_lut DISABLE KEYS */;
LOCK TABLES psl_group_permission_lut WRITE;
INSERT INTO psl_group_permission_lut VALUES (10,1,50),(9,1,47),(8,1,49),(7,1,48),(6,1,45),(11,1,46),(20,4,35),(21,4,38),(22,4,39),(23,4,37),(24,4,36),(25,8,7),(26,8,9),(27,8,4),(28,8,6),(29,8,3),(30,8,8),(31,8,5),(32,9,56),(33,9,59),(34,9,60),(35,9,58),(36,9,57),(37,10,20),(38,10,23),(39,10,24),(40,10,22),(41,10,21),(42,11,66),(43,11,69),(44,11,70),(45,11,68),(46,11,67),(47,12,61),(48,12,64),(49,12,65),(50,12,63),(51,12,62),(52,13,25),(53,13,28),(54,13,29),(55,13,27),(56,13,26),(57,14,40),(58,14,43),(59,14,44),(60,14,42),(61,14,41),(62,15,30),(63,15,33),(64,15,34),(65,15,32),(66,15,31),(67,16,78),(68,16,77),(69,16,71),(70,16,79),(71,16,74),(72,16,76),(73,16,75),(74,16,80),(75,16,73),(76,16,72),(77,17,10),(78,17,13),(79,17,14),(80,17,12),(81,17,11),(82,18,15),(83,18,18),(84,18,19),(85,18,17),(86,18,16),(87,19,51),(88,19,54),(89,19,55),(90,19,53),(91,19,52),(92,20,4),(93,20,6),(94,20,3),(95,20,5),(109,20,12),(96,21,4),(97,21,6),(98,21,3),(99,21,5),(100,22,4),(101,22,6),(102,22,3),(103,22,5),(104,23,78),(105,23,77),(106,23,79),(107,23,76),(108,23,80),(110,20,81),(111,8,82),(112,21,50),(200,201,201),(201,202,200),(202,203,202),(203,205,203),(204,206,204),(205,206,205),(206,206,206),(207,206,207),(208,207,208),(209,208,209),(210,209,210),(211,210,211),(222,213,214),(223,213,214);
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_group_permission_lut ENABLE KEYS */;

#
# Table structure for table `psl_group_section_lut`
#

DROP TABLE IF EXISTS psl_group_section_lut;
CREATE TABLE psl_group_section_lut (
  lut_id int(11) unsigned NOT NULL default '0',
  group_id int(11) unsigned default NULL,
  section_id int(11) unsigned default NULL,
  UNIQUE KEY lut_id_2 (lut_id),
  KEY lut_id (lut_id),
  KEY group_id (group_id),
  KEY section_id (section_id)
) TYPE=MyISAM;

#
# Dumping data for table `psl_group_section_lut`
#


/*!40000 ALTER TABLE psl_group_section_lut DISABLE KEYS */;
LOCK TABLES psl_group_section_lut WRITE;
INSERT INTO psl_group_section_lut VALUES (19,3,0),(40,4,0),(41,5,0),(42,6,0),(43,7,0),(44,8,0),(45,9,0),(46,10,0),(47,11,0),(48,12,0),(49,13,0),(50,14,0),(51,15,0),(52,16,0),(53,17,0),(54,18,0),(55,19,0),(56,20,0),(57,21,0),(58,22,0),(59,23,0),(65,24,0),(62,26,0),(63,27,0),(67,213,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_group_section_lut ENABLE KEYS */;

#
# Table structure for table `psl_infolog`
#

DROP TABLE IF EXISTS psl_infolog;
CREATE TABLE psl_infolog (
  id smallint(10) unsigned NOT NULL default '0',
  description varchar(50) default NULL,
  data varchar(255) default NULL,
  date_created int(11) default NULL,
  userID int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (id),
  KEY data (data)
) TYPE=MyISAM;

#
# Dumping data for table `psl_infolog`
#


#
# Table structure for table `psl_mailinglist`
#

DROP TABLE IF EXISTS psl_mailinglist;
CREATE TABLE psl_mailinglist (
  id int(10) unsigned NOT NULL default '0',
  email varchar(100) NOT NULL default '',
  name varchar(100) default NULL,
  date_created int(11) default NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY id_2 (id),
  KEY id (id,email)
) TYPE=MyISAM;

#
# Dumping data for table `psl_mailinglist`
#


/*!40000 ALTER TABLE psl_mailinglist DISABLE KEYS */;
LOCK TABLES psl_mailinglist WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_mailinglist ENABLE KEYS */;

#
# Table structure for table `psl_mailinglist_frequency`
#

DROP TABLE IF EXISTS psl_mailinglist_frequency;
CREATE TABLE psl_mailinglist_frequency (
  id int(10) unsigned NOT NULL default '0',
  frequency varchar(30) NOT NULL default '',
  dayback int(11) NOT NULL default '0',
  PRIMARY KEY  (id),
  UNIQUE KEY id_2 (id),
  KEY id (id)
) TYPE=MyISAM;

#
# Dumping data for table `psl_mailinglist_frequency`
#


/*!40000 ALTER TABLE psl_mailinglist_frequency DISABLE KEYS */;
LOCK TABLES psl_mailinglist_frequency WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_mailinglist_frequency ENABLE KEYS */;

#
# Table structure for table `psl_permission`
#

DROP TABLE IF EXISTS psl_permission;
CREATE TABLE psl_permission (
  permission_id int(10) unsigned NOT NULL default '0',
  permission_name varchar(60) NOT NULL default '',
  permission_description text,
  PRIMARY KEY  (permission_id),
  UNIQUE KEY permission_name (permission_name)
) TYPE=MyISAM;

#
# Dumping data for table `psl_permission`
#


/*!40000 ALTER TABLE psl_permission DISABLE KEYS */;
LOCK TABLES psl_permission WRITE;
INSERT INTO psl_permission VALUES (3,'commentShow','can see comments'),(4,'commentPost','can post comments'),(5,'commentView','can preview? comments?'),(6,'commentSave','can edit comments?'),(7,'commentDelete','can delete comments'),(8,'commentUpdate','can moderate comments'),(9,'commentEdit','can edit comments'),(10,'submissionDelete','can delete submissions'),(11,'submissionSave','can save submissions'),(12,'submissionNew','can submit a story'),(13,'submissionEdit','can edit submissions'),(14,'submissioneditasstory','can post submissions as stories'),(15,'topicDelete','can delete topics'),(16,'topicSave','can save topics'),(17,'topicNew','can create new topics'),(18,'topicEdit','can update topics'),(19,'topicList','get lists of topics'),(20,'groupDelete','can delete groups'),(21,'groupSave','can save groups'),(22,'groupNew','can create groups'),(23,'groupEdit','can edit groups'),(24,'groupList','can list groups'),(25,'permissionDelete','can delete permissions'),(26,'permissionSave','can save permissions'),(27,'permissionNew','can create permissions'),(28,'permissionEdit','can edit permissions'),(29,'permissionList','can list permissions'),(30,'sectionDelete','delete sections'),(31,'sectionSave','save sections'),(32,'sectionNew','create sections'),(33,'sectionEdit','update sections'),(34,'sectionList','list sections'),(35,'blockDelete','delete blocks'),(36,'blockPut','save blocks'),(37,'blockNew','create blocks'),(38,'blockEdit','update a block'),(39,'blockList','list all blocks'),(40,'pollDelete','delete polls'),(41,'pollPut','save a poll'),(42,'pollNew','create a poll'),(43,'pollEdit','edit polls'),(44,'pollList','list all polls'),(45,'authorDelete','delete a user'),(46,'authorSave','save user info'),(47,'authorNew','create a user'),(48,'authorEdit','update user info'),(49,'authorList','list all authors'),(50,'authorprofileSave','update your own info'),(51,'variableDelete','delete a Variable'),(52,'variableSave','save a variable'),(53,'variableNew','create a variable'),(54,'variableEdit','edit a variable'),(55,'variableList','list all variables'),(56,'glossaryDelete','delete a glossary entry'),(57,'glossarySave','save a glossary entry'),(58,'glossaryNew','create a glossary entry'),(59,'glossaryEdit','update a glossary entry'),(60,'glossaryList','list all glossary entries'),(61,'mailinglistDelete','delete a list member'),(62,'mailinglistSave','save list member address'),(63,'mailinglistNew','new member form'),(64,'mailinglistEdit','update form'),(65,'mailinglistList','list all members'),(66,'infologDelete','delete a log entry'),(67,'infologSave','save a log entry'),(68,'infologNew','create a log entry'),(69,'infologEdit','change a log entry?'),(70,'infologList','display the infolog'),(71,'storyDelete','delete a story'),(72,'storySave','save a story'),(73,'storyNew','new story form'),(74,'storyEdit','edit story form'),(75,'storyList','list stories'),(76,'storyeditothers','edit other authors stories'),(77,'storychangedate','can change date of stories'),(78,'storychangeauthor','can change the author of the story'),(79,'storydeleteothers','can delete other author&#039;s stories'),(80,'storylistothers','story list contains other author&#039;s stories'),(81,'commentChangeName','can Change comment na\nme and url'),(82,'commentViewIP','Can view full IP/Host of person posting a comment'),(200,'upload','Can upload files for current subsite'),(201,'subsite','Can administer subsites'),(202,'gallery','Gallery administration'),(203,'template','Can edit templates'),(204,'linkNew','new link'),(205,'linkEdit','edit link'),(206,'linkSave','save link'),(207,'linkList','list link'),(208,'target','Can administer targets'),(209,'action','Can administer actions'),(210,'contact','Can administer contacts'),(211,'bibliography','Can administer bibliographies'),(212,'petition','Can administer petition'),(214,'Member','Registered Members');
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_permission ENABLE KEYS */;

#
# Table structure for table `psl_poll_answer`
#

DROP TABLE IF EXISTS psl_poll_answer;
CREATE TABLE psl_poll_answer (
  question_id int(10) unsigned NOT NULL default '0',
  answer_id varchar(32) NOT NULL default '',
  answer_text varchar(255) NOT NULL default '',
  votes int(11) default NULL,
  PRIMARY KEY  (question_id,answer_id)
) TYPE=MyISAM;

#
# Dumping data for table `psl_poll_answer`
#


/*!40000 ALTER TABLE psl_poll_answer DISABLE KEYS */;
LOCK TABLES psl_poll_answer WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_poll_answer ENABLE KEYS */;

#
# Table structure for table `psl_poll_question`
#

DROP TABLE IF EXISTS psl_poll_question;
CREATE TABLE `psl_poll_question` (
  `question_id` int(10) unsigned NOT NULL default '0',
  `question_text` varchar(255) NOT NULL default '',
  `question_total_votes` int(11) default NULL,
  `current` tinyint(4) NOT NULL default '0',
  `date_created` int(11) default NULL,
  `language_id` char(2) NOT NULL default '',
  `subsite_id` smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (`question_id`),
  KEY `subsite_id` (`subsite_id`)
) TYPE=MyISAM;

#
# Dumping data for table `psl_poll_question`
#

/*!40000 ALTER TABLE psl_poll_question DISABLE KEYS */;
LOCK TABLES psl_poll_question WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_poll_question ENABLE KEYS */;

#
# Table structure for table `psl_poll_voter`
#

DROP TABLE IF EXISTS psl_poll_voter;
CREATE TABLE psl_poll_voter (
  question_id int(10) unsigned NOT NULL default '0',
  voter_id varchar(30) default NULL,
  user_id int(11) NOT NULL default '0',
  date_created int(11) default NULL
) TYPE=MyISAM;

#
# Dumping data for table `psl_poll_voter`
#


/*!40000 ALTER TABLE psl_poll_voter DISABLE KEYS */;
LOCK TABLES psl_poll_voter WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_poll_voter ENABLE KEYS */;

#
# Table structure for table `psl_quote`
#

DROP TABLE IF EXISTS psl_quote;
CREATE TABLE psl_quote (
  quote text NOT NULL,
  author varchar(40) NOT NULL default ''
) TYPE=MyISAM;

#
# Dumping data for table `psl_quote`
#


/*!40000 ALTER TABLE psl_quote DISABLE KEYS */;
LOCK TABLES psl_quote WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_quote ENABLE KEYS */;

#
# Table structure for table `psl_section`
#

DROP TABLE IF EXISTS psl_section;
CREATE TABLE psl_section (
  section_id int(11) unsigned NOT NULL default '0',
  section_name varchar(32) NOT NULL default '',
  description varchar(128) default NULL,
  artcount int(11) default NULL,
  section_options text,
  PRIMARY KEY  (section_id),
  UNIQUE KEY section_name (section_name)
) TYPE=MyISAM;

#
# Dumping data for table `psl_section`
#


/*!40000 ALTER TABLE psl_section DISABLE KEYS */;
LOCK TABLES psl_section WRITE;
INSERT INTO psl_section VALUES (1,'Home','Everything that\'s associated with this section appears on the main index page.',NULL,NULL),(2,'Admin','Administration section - no stories',NULL,NULL),(3,'User','Logged in users section - no stories',NULL,NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_section ENABLE KEYS */;

#
# Table structure for table `psl_section_block_lut`
#

DROP TABLE IF EXISTS psl_section_block_lut;
CREATE TABLE psl_section_block_lut (
  lut_id int(11) unsigned NOT NULL default '0',
  block_id int(11) unsigned default NULL,
  section_id int(11) unsigned default NULL,
  UNIQUE KEY lut_id_2 (lut_id),
  KEY lut_id (lut_id)
) TYPE=MyISAM;

#
# Dumping data for table `psl_section_block_lut`
#


/*!40000 ALTER TABLE psl_section_block_lut DISABLE KEYS */;
LOCK TABLES psl_section_block_lut WRITE;
INSERT INTO psl_section_block_lut VALUES (595,1,24),(594,1,22),(579,151,11),(413,152,10),(447,0,15),(446,0,14),(445,0,13),(473,10,11),(402,0,9),(440,36,10),(450,0,18),(449,0,17),(448,0,16),(472,10,10),(416,2,10),(593,1,12),(592,1,29),(591,1,4),(590,1,19),(589,1,20),(588,1,21),(403,0,10),(415,35,10),(441,36,11),(587,1,27),(586,1,2),(429,0,12),(428,0,11),(418,34,10),(537,0,19),(573,0,20),(574,0,21),(575,0,22),(576,0,23),(580,0,24),(581,0,25),(582,0,26),(583,0,27),(584,0,28),(585,0,29),(596,1,11),(597,1,25),(598,1,5),(599,1,1),(600,1,17),(601,1,23),(602,1,6),(603,1,28),(604,1,3),(605,1,26),(606,1,18),(607,1,13),(608,1,16),(609,1,14),(610,1,15),(626,39,2),(627,39,27),(628,39,21),(629,39,20),(630,39,19),(631,39,4),(632,39,29),(633,39,12),(634,39,22),(635,39,24),(636,39,11),(637,39,25),(638,39,5),(639,39,1),(640,39,17),(641,39,23),(642,39,6),(643,39,28),(644,39,3),(645,39,26),(646,39,18),(647,39,13),(648,39,16),(649,39,14),(650,39,15),(290,4,1),(291,4,2),(292,4,3);
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_section_block_lut ENABLE KEYS */;

#
# Table structure for table `psl_section_lut`
#

DROP TABLE IF EXISTS psl_section_lut;
CREATE TABLE psl_section_lut (
  lut_id int(11) unsigned NOT NULL default '0',
  story_id int(11) unsigned NOT NULL default '0',
  section_id int(11) unsigned NOT NULL default '0',
  UNIQUE KEY lut_id_2 (lut_id),
  KEY lut_id (lut_id)
) TYPE=MyISAM;

#
# Dumping data for table `psl_section_lut`
#


/*!40000 ALTER TABLE psl_section_lut DISABLE KEYS */;
LOCK TABLES psl_section_lut WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_section_lut ENABLE KEYS */;

#
# Table structure for table `psl_section_submission_lut`
#

DROP TABLE IF EXISTS psl_section_submission_lut;
CREATE TABLE psl_section_submission_lut (
  lut_id int(11) unsigned NOT NULL default '0',
  story_id int(11) unsigned NOT NULL default '0',
  section_id int(11) unsigned NOT NULL default '0',
  UNIQUE KEY lut_id_2 (lut_id),
  KEY lut_id (lut_id)
) TYPE=MyISAM;

#
# Dumping data for table `psl_section_submission_lut`
#


/*!40000 ALTER TABLE psl_section_submission_lut DISABLE KEYS */;
LOCK TABLES psl_section_submission_lut WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_section_submission_lut ENABLE KEYS */;

#
# Table structure for table `psl_story`
#

DROP TABLE IF EXISTS psl_story;
CREATE TABLE psl_story (
  story_id int(11) unsigned NOT NULL default '0',
  user_id int(11) unsigned NOT NULL default '0',
  order_no int(10) unsigned NOT NULL default '0',
  title varchar(80) default NULL,
  dept varchar(80) default NULL,
  intro_text text NOT NULL,
  body_text text,
  hits int(11) unsigned default NULL,
  topic_cache text,
  story_options text,
  date_available int(11) default NULL,
  PRIMARY KEY  (story_id)
) TYPE=MyISAM;

#
# Dumping data for table `psl_story`
#


/*!40000 ALTER TABLE psl_story DISABLE KEYS */;
LOCK TABLES psl_story WRITE;
INSERT INTO psl_story VALUES (1,1,0,'Congratulations!  It Works!','new-stuff-is-cool','Welcome to phpslash!\r<br>\n\r<br>\nNow, login to the Admin Section with the default username of \'god\' and password of \'password\' and delete this story!\r<br>\n\r<br>\nThen, add yourself as an author (remember to give yourself all Security permissions) and logout / login again. ','',1,'','',975968996);
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_story ENABLE KEYS */;

#
# Table structure for table `psl_submission`
#

DROP TABLE IF EXISTS psl_submission;
CREATE TABLE psl_submission (
  story_id int(11) unsigned NOT NULL default '0',
  user_id int(11) unsigned NOT NULL default '0',
  title varchar(80) default NULL,
  dept varchar(80) default NULL,
  intro_text text NOT NULL,
  body_text text,
  hits int(11) unsigned default NULL,
  email varchar(50) default NULL,
  name varchar(50) NOT NULL default '',
  topic_cache text,
  date_created int(11) default NULL,
  PRIMARY KEY  (story_id)
) TYPE=MyISAM;

#
# Dumping data for table `psl_submission`
#


/*!40000 ALTER TABLE psl_submission DISABLE KEYS */;
LOCK TABLES psl_submission WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_submission ENABLE KEYS */;

#
# Table structure for table `psl_topic`
#

DROP TABLE IF EXISTS psl_topic;
CREATE TABLE psl_topic (
  topic_id int(10) unsigned NOT NULL default '0',
  topic_name varchar(60) NOT NULL default '',
  image varchar(30) default NULL,
  alt_text varchar(100) default NULL,
  width int(11) default NULL,
  height int(11) default NULL,
  onlinkbar tinyint(1) default NULL,
  PRIMARY KEY  (topic_id),
  UNIQUE KEY topic_name (topic_name)
) TYPE=MyISAM;

#
# Dumping data for table `psl_topic`
#


/*!40000 ALTER TABLE psl_topic DISABLE KEYS */;
LOCK TABLES psl_topic WRITE;
INSERT INTO psl_topic VALUES (1,'News','topicnews.gif','News',34,44,2);
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_topic ENABLE KEYS */;

#
# Table structure for table `psl_topic_lut`
#

DROP TABLE IF EXISTS psl_topic_lut;
CREATE TABLE psl_topic_lut (
  lut_id int(10) unsigned NOT NULL default '0',
  topic_id int(10) unsigned NOT NULL default '0',
  story_id int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (lut_id),
  KEY story_id (story_id)
) TYPE=MyISAM;

#
# Dumping data for table `psl_topic_lut`
#


/*!40000 ALTER TABLE psl_topic_lut DISABLE KEYS */;
LOCK TABLES psl_topic_lut WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_topic_lut ENABLE KEYS */;

#
# Table structure for table `psl_topic_submission_lut`
#

DROP TABLE IF EXISTS psl_topic_submission_lut;
CREATE TABLE psl_topic_submission_lut (
  lut_id int(10) unsigned NOT NULL default '0',
  topic_id int(10) unsigned NOT NULL default '0',
  story_id int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (lut_id)
) TYPE=MyISAM;

#
# Dumping data for table `psl_topic_submission_lut`
#


/*!40000 ALTER TABLE psl_topic_submission_lut DISABLE KEYS */;
LOCK TABLES psl_topic_submission_lut WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_topic_submission_lut ENABLE KEYS */;

#
# Table structure for table `psl_variable`
#

DROP TABLE IF EXISTS psl_variable;
CREATE TABLE psl_variable (
  variable_id int(10) unsigned NOT NULL default '0',
  variable_name varchar(32) NOT NULL default '',
  value varchar(127) default NULL,
  description varchar(127) default NULL,
  variable_group varchar(20) default NULL,
  UNIQUE KEY variable_name (variable_name),
  KEY variable_id (variable_id)
) TYPE=MyISAM;

#
# Dumping data for table `psl_variable`
#


/*!40000 ALTER TABLE psl_variable DISABLE KEYS */;
LOCK TABLES psl_variable WRITE;
INSERT INTO psl_variable VALUES
   (100,'BE_Version','0.7.2.1','Back-End Version Number',''),
   (101,'BE_CompletedUpgrade','Upgrade720to721.sql','Used for tracking DB upgrades',NULL);
UNLOCK TABLES;
/*!40000 ALTER TABLE psl_variable ENABLE KEYS */;


# EXTRA MODULES


# **************************************
# Tables and data for additional back-end modules
# for Back-End Version: 0.7.x
# **************************************

#BACK-END PERMISSIONS AND GROUPS =======================================
# --------------------------------------------------------

# Not strictly necessary - but make sure that god has the new rights
UPDATE psl_author
   SET perms="user,topic,story,storyeditor,comment,section,link,gallery,submission,block,poll,author,variable,glossary,mailinglist,local,upload,logging,root,template,subsite,linkAdmin,target,action,contact,bibliography,petition"
 WHERE author_id=1;

UPDATE db_sequence SET nextid=212 WHERE seq_name='psl_group_seq';
UPDATE db_sequence SET nextid=221 WHERE seq_name='psl_group_group_lut_seq';
UPDATE db_sequence SET nextid=213 WHERE seq_name='psl_group_permission_lut_seq';
UPDATE db_sequence SET nextid=213 WHERE seq_name='psl_permission_seq';


# Subsites =======================================
# --------------------------------------------------------


DROP TABLE IF EXISTS `be_subsites`;
CREATE TABLE `be_subsites` (
 `subsite_id` smallint(5) unsigned NOT NULL default '0',
 `name` varchar(32) NOT NULL default '',
 `description` varchar(255) NOT NULL default '',
 `subsite_type_id` smallint(5) NOT NULL default '0',
 `sectionID` smallint(5) unsigned NOT NULL default '0',
 `url` varchar(255) NOT NULL default '',
 PRIMARY KEY  (`subsite_id`),
 KEY `sectionID` (`sectionID`),
 KEY `subsite_type_id` (`subsite_type_id`)
) TYPE=MyISAM;

#INSERT INTO db_sequence (seq_name, nextid) VALUES ( 'be_locals', '1');

DROP TABLE IF EXISTS `be_subsite_types`;
CREATE TABLE `be_subsite_types` (
  `subsite_type_id` smallint(5) unsigned NOT NULL default '0',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`subsite_type_id`)
) TYPE=MyISAM;

#
# Dumping data for table `be_subsite_types`
#

INSERT INTO `be_subsite_types` (`subsite_type_id`, `description`) VALUES (0, 'Unassigned');



# LINK BLOCKS TO SUBSITES

DROP TABLE IF EXISTS `be_subsite_block_lut`;
CREATE TABLE `be_subsite_block_lut` (
 `ID` smallint(5) unsigned NOT NULL auto_increment,
 `subsite_id` smallint(5) unsigned NOT NULL default '0',
 `block_id` smallint(5) unsigned NOT NULL default '0',
 PRIMARY KEY  (`ID`),
 KEY `subsite_id` (`subsite_id`),
 KEY `block_id` (`block_id`)
) TYPE=MyISAM COMMENT='BE identifies blocks that belong to subsites';





# CATEGORIZATION OF ARTICLES ETC =========================
# --------------------------------------------------------


DROP TABLE IF EXISTS `be_categories`;
CREATE TABLE be_categories (
   category_id smallint(5) NOT NULL AUTO_INCREMENT,
   category_type char(8) NOT NULL DEFAULT '',
   category_code char(8) NOT NULL default '',
   languageID char(3) NOT NULL default '',
   name varchar(50) NOT NULL default '',
   PRIMARY KEY (category_id),
   KEY (category_type, category_code),
   KEY (languageID)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `be_category2item`;
CREATE TABLE be_category2item (
  id smallint(5) NOT NULL auto_increment,
  category_type varchar(8) NOT NULL default '',
  category_code varchar(8) NOT NULL default '',
  item_type varchar(16) NOT NULL default 'article',
  item_id varchar(50) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY category_type (category_type,category_code),
  KEY item_type (item_type,item_id)
) TYPE=MyISAM COMMENT='BE links categrories to articles. Could be used for sections, links, gallery items too';

DROP TABLE IF EXISTS `be_rsstool`;
CREATE TABLE be_rsstool (
  md5 varchar(50) NOT NULL default '0',
  url varchar(255) default '',
  dateCreated int(10) unsigned NOT NULL default '0',
  dateModified int(10) unsigned NOT NULL default '0',
  dateRemoved int(10) unsigned NOT NULL default '0',
  requests text NOT NULL,
  DATA text NOT NULL,
  PRIMARY KEY  (md5),
  KEY md5 (md5,dateRemoved)
) TYPE=MyISAM COMMENT='rss and html cache';

# CUPE added an events module - Ill be integrating this
DROP TABLE IF EXISTS `be_event`;
CREATE TABLE `be_event` (
  `eventID` smallint(5) NOT NULL default '0',
  `draft` smallint(3) NOT NULL default '0',
  `calendar` varchar(255) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  `contact` varchar(255) NOT NULL default '',
  `location` varchar(255) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `startDate` int(10) unsigned NOT NULL default '0',
  `endDate` int(10) unsigned NOT NULL default '0',
  `author_id` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`eventID`),
  UNIQUE KEY `eventID` (`eventID`)
) TYPE=MyISAM;

ALTER TABLE `be_event` ADD INDEX `calendarDisplay` ( `eventID` , `calendar` , `draft` , `startDate` , `endDate` ) ;

# --------------------------------------------------------


# CUPE added an events module - I'll be integrating this
DROP TABLE IF EXISTS `be_eventText`;
CREATE TABLE `be_eventText` (
  `eventID` smallint(5) NOT NULL default '0',
  `eventTextID` smallint(5) NOT NULL default '0',
  `language` char(3) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  PRIMARY KEY  (`eventTextID`),
  UNIQUE KEY `eventTextID` (`eventTextID`),
  KEY `eventID` (`eventID`),
  KEY `language` (`language`)
) TYPE=MyISAM;
# --------------------------------------------------------


# CUPE added a feedback module - still requires generalization.
DROP TABLE IF EXISTS `be_feedback`;
CREATE TABLE `be_feedback` (
  `id` smallint(5) default NULL,
  `SubmitterName` varchar(255) default NULL,
  `SubmitterEmail` varchar(255) default NULL,
  `Location` varchar(255) default NULL,
  `ReferringPage` varchar(255) default NULL,
  `CupeMember` smallint(3) default NULL,
  `CupeLocal` varchar(255) default NULL,
  `KnowsCupeMember` smallint(3) default NULL,
  `Comments` text,
  `TimeSubmitted` int(10) default NULL,
  `TimeRespondedTo` int(10) default NULL,
  `Responded` smallint(3) default NULL,
  `ForwardedTo` varchar(255) default NULL,
  `RespondedBy` varchar(50) default NULL,
  `Response` text,
  `Browser` varchar(255) default NULL,
  `UserIP` varchar(63) default NULL,
  `RemoteHost` varchar(255) default NULL,
  `FeedbackComments` text,
  `ForwardComments` text,
  `subsite_id` smallint(5) unsigned NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM COMMENT='CUPEs Feedback Table';

#
# BACK-END PERMISSIONS AND GROUPS =======================================
# --------------------------------------------------------

# Not strictly necessary - but make sure that god has the new rights
UPDATE psl_author
   SET perms="user,topic,story,storyeditor,comment,section,link,gallery,submission,block,poll,author,variable,glossary,mailinglist,local,upload,logging,root,template,subsite,linkAdmin,target,action,contact,bibliography,petition"
 WHERE author_id=1;

UPDATE db_sequence SET nextid=212 WHERE seq_name='psl_group_seq';
UPDATE db_sequence SET nextid=221 WHERE seq_name='psl_group_group_lut_seq';
UPDATE db_sequence SET nextid=213 WHERE seq_name='psl_group_permission_lut_seq';
UPDATE db_sequence SET nextid=213 WHERE seq_name='psl_permission_seq';

# Keywords table (lost between 0.5.2 and 0.7.2.1)
DROP TABLE IF EXISTS `be_keywords`;
CREATE TABLE be_keywords (
  keywordID int(10) unsigned NOT NULL auto_increment,
  keyword varchar(255) NOT NULL default '',
  relatedObjects tinytext,
  PRIMARY KEY  (keywordID),
  KEY keyword (keyword)
) TYPE=MyISAM COMMENT='BE list of keywords & related articles/sections';


# Optional Catalog module
# - code is focussed on an online oral history archive

DROP TABLE IF EXISTS `be_catalog`;
CREATE TABLE `be_catalog` (
  `catalogID` smallint(5) NOT NULL auto_increment,
  `transcriptFile` varchar(100) NOT NULL default '',
  `audioFile` varchar(100) NOT NULL default '',
  `imageFile` varchar(100) NOT NULL default '',
  `dateCreated` int(11) NOT NULL default '0',
  `dateModified` int(11) NOT NULL default '0',
  PRIMARY KEY  (`catalogID`)
) TYPE=MyISAM COMMENT='Used for FIS archive catalog';

DROP TABLE IF EXISTS `be_catalogText`;
CREATE TABLE `be_catalogText` (
  `catalogTextID` smallint(5) NOT NULL auto_increment,
  `catalogID` smallint(5) NOT NULL default '0',
  `languageID` char(2) NOT NULL default '',
  `status` varchar(100) NOT NULL default '',
  `language` varchar(100) NOT NULL default '',
  `condition` varchar(100) NOT NULL default '',
  `interviewDate` varchar(100) NOT NULL default '',
  `interviewee` varchar(100) NOT NULL default '',
  `position` varchar(100) NOT NULL default '',
  `location` varchar(100) NOT NULL default '',
  `interviewer` varchar(100) NOT NULL default '',
  `content` varchar(100) NOT NULL default '',
  `abstract` text NOT NULL,
  PRIMARY KEY  (`catalogTextID`),
  KEY catalog_language(catalogID, languageID),
  KEY (catalogID)
) TYPE=MyISAM COMMENT='Used for FIS archive catalog - language sensitive fields';

DROP TABLE IF EXISTS `be_keyword2catalog`;
CREATE TABLE `be_keyword2catalog` (
  `keyword` varchar(32) NOT NULL default '',
  `catalogID` int(11) NOT NULL default '0',
  `languageID` char(3) NOT NULL default '',
  KEY `keyword` (`keyword`),
  KEY `articleID` (`catalogID`),
  KEY `languageID` (`languageID`)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `be_searchlog`;
CREATE TABLE `be_searchlog` (
   `query` VARCHAR( 100 ) NOT NULL ,
   `dateUpdated` INT( 11 ) UNSIGNED NOT NULL ,
   `count` INT( 11 ) UNSIGNED NOT NULL ,
   `userAgent` varchar(255) default NULL,
    INDEX ( `query` )
) COMMENT = 'recording internal searches';

DROP TABLE IF EXISTS `be_errorlog`;
CREATE TABLE `be_errorlog` (
  `url` varchar(255) NOT NULL default '',
  `dateUpdated` int(11) unsigned NOT NULL default '0',
  `count` int(11) unsigned NOT NULL default '0',
  `referredFrom` varchar(255) NOT NULL default '',
  `redirectedTo` varchar(255) NOT NULL default '',
  `userAgent` varchar(255) default NULL,
  KEY `url` (`url`)
) TYPE=MyISAM COMMENT='Record urls which were directed to the error page';

# SEQUENCE REBUILDING

# Blocks
SELECT @NextBlock := max(id) FROM psl_block;
UPDATE db_sequence SET nextid = @NextBlock := (@NextBlock + 1) WHERE seq_name = 'psl_block_seq';
SELECT @NextBlockText := max(textID) FROM psl_blockText;
UPDATE db_sequence SET nextid = @NextBlockText := (@NextBlockText + 1) WHERE seq_name = 'psl_block_text_seq';
SELECT @NextSectionBlock := max(lut_id) FROM psl_section_block_lut;
UPDATE db_sequence SET nextid = @NextSectionBlock := (@NextSectionBlock + 1) WHERE seq_name = 'psl_section_block_lut_seq';

# Groups
SELECT @NextGroup := max(group_id) FROM psl_group;
UPDATE db_sequence SET nextid = (@NextGroup + 1) WHERE seq_name = 'psl_group_seq';
SELECT @NextPermission := max(permission_id) FROM psl_permission;
UPDATE db_sequence SET nextid = (@NextPermission + 1) WHERE seq_name = 'psl_permission_seq';
SELECT @NextGroupGroup := max(lut_id) FROM psl_group_group_lut;
UPDATE db_sequence SET nextid = (@NextGroupGroup + 1) WHERE seq_name = 'psl_group_group_lut_seq';
SELECT @NextGroupSection := max(lut_id) FROM psl_group_section_lut;
UPDATE db_sequence SET nextid = (@NextGroupSection + 1) WHERE seq_name = 'psl_group_section_lut_seq';
SELECT @NextGroupPermission := max(lut_id) FROM psl_group_permission_lut;
UPDATE db_sequence SET nextid = (@NextGroupPermission + 1) WHERE seq_name = 'psl_group_permission_lut_seq';
SELECT @NextAuthorGroup := max(lut_id) FROM psl_author_group_lut;
UPDATE db_sequence SET nextid = (@NextAuthorGroup + 1) WHERE seq_name = 'psl_author_group_lut_seq';

# block-types
SELECT @NextBlockType := max(id) FROM psl_block_type;
UPDATE db_sequence SET nextid = (@NextBlockType + 1) WHERE seq_name = 'psl_blocktype_seq';


# LAST LINES: Record date of last update
SELECT @VarId := max(variable_id) FROM psl_variable;
INSERT INTO `psl_variable` VALUES ((@VarId+1), 'DB_Upgrade_Date', now(), 'Date that the last database upgrade was done', '');
INSERT INTO `psl_variable` VALUES ((@VarId+2), 'DB_CVS_Date', '720to721 $Id: BE_core.sql,v 1.43 2005/06/15 01:22:30 mgifford Exp $', 'Date of the cvs version from the last upgrade', '');
UPDATE db_sequence SET nextid= (@VarId+3) WHERE seq_name='psl_variable_seq';
