# $Id: BE_all.sql,v 1.62 2005/06/19 10:37:21 krabu Exp $
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
INSERT INTO `psl_variable` VALUES ((@VarId+2), 'DB_CVS_Date', '720to721 $Id: BE_all.sql,v 1.62 2005/06/19 10:37:21 krabu Exp $', 'Date of the cvs version from the last upgrade', '');
UPDATE db_sequence SET nextid= (@VarId+3) WHERE seq_name='psl_variable_seq';
#
# add_action_tables.sql
#
# Add tables for Back-End Actions
#
# @package   Back-End on phpSlash
# @author    Peter Bojanic
# @copyright Copyright (C) 2003 OpenConcept Consulting
# @version   $Id: BE_all.sql,v 1.62 2005/06/19 10:37:21 krabu Exp $


# This file is part of Back-End.
#
# Back-End is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# Back-End is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Back-End; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA


# Table descriptions
#
# be_contact - any type of contact with a name and coordinates
# be_contactType - the type of be_contact (business, MP/MLA, citizen)
# be_target - defines the target of an action by associating a
#   be_contact with a be_action
#
# be_action - an lobying action directed at a set of be_target OR
#   against a target to be specified/looked up by the participants
#   such as an MP/MLA
# be_actionText - language-specific text attributes for a be_action
# be_actionType - the type of be_action (fax, email, etc.)
# be_action2section - defines associations between a be_action and
#   multiple be_sections rows (see Schema note 1)
# be_action2contact - defines contacts who have participated in a
#   be_action, their custom message (if applicable) and their own
#   target (if applicable)


# Schema notes
#
# 1. CUPEs initial requirements for Actions permits anonymous users
# to participate (i.e. no registration). To simplify the initial
# implementation, we will not provide registration or login
# features for Actions. For the initial implementation we will end
# up storing MULTIPLE ROWS in be_contact for a real person, should
# they participate in multiple actions.
#
# 2. Many Back-End tables make use of phpslash db_sequence for
# generating unique sequence numbers. This is particularly important
# if you need to insert rows into associated tables using the unique
# id of the parent row to join the two. This is cumbersome and prone
# to problems when systems get migrated or merged. Instead, Actions
# will make use of the mysql_insert_id() function in PHP


#
# Table structure for table `be_target`
#
DROP TABLE IF EXISTS be_target;
CREATE TABLE be_target (
  actionID smallint(5) unsigned NOT NULL,
  contactID smallint(5) unsigned NOT NULL,
  notes text NOT NULL default '',
  dateCreated INT( 10 ) UNSIGNED NOT NULL,
  dateModified INT( 10 ) UNSIGNED NOT NULL,
  PRIMARY KEY (actionID, contactID)
) TYPE=MyISAM;


#
# Table structure for table `be_contactType`
#
DROP TABLE IF EXISTS be_contactType;
CREATE TABLE be_contactType (
  contactTypeID  smallint(5) unsigned NOT NULL,
  description varchar(255) NOT NULL,
  PRIMARY KEY (contactTypeID)
) TYPE=MyISAM;


#
# Table structure for table `be_action`
#
DROP TABLE IF EXISTS be_action;
CREATE TABLE be_action (
  actionID smallint(5) unsigned NOT NULL auto_increment,
  URLname varchar(20) NOT NULL,
  author_id smallint(5) unsigned default NULL,
  subsiteID smallint(5) NOT NULL default '0',
  dateCreated INT( 10 ) UNSIGNED NOT NULL,
  dateModified INT( 10 ) UNSIGNED NOT NULL,
  dateAvailable INT( 10 ) UNSIGNED NOT NULL,
  dateRemoved INT( 10 ) UNSIGNED NOT NULL default '0',
  hide tinyint(2) default '0',
  restrict2members tinyint(5) default '0',
  customize tinyint(5) default '0',
  targetType tinyint(5) default '0',
  actionCounter smallint(10) UNSIGNED NOT NULL default '0',
  priority smallint(5) NOT NULL default '0',
  actionType smallint(5) unsigned NOT NULL,
  hitCounter smallint(10) UNSIGNED NOT NULL default '0',
  content_type tinyint(1) unsigned NOT NULL default 3,
  PRIMARY KEY (actionID),
  UNIQUE INDEX URLname (URLname),
  INDEX author_id (author_id),
  INDEX subsiteID(subsiteID)
) TYPE=MyISAM;


#
# Table structure for table `be_actionText`
#
DROP TABLE IF EXISTS be_actionText;
CREATE TABLE be_actionText (
  actionTextID smallint(5) unsigned NOT NULL auto_increment,
  actionID smallint(5) NOT NULL,
  languageID char(3) NOT NULL,
  title varchar(255) NOT NULL,
  blurb text NOT NULL default '',
  content text NOT NULL default '',
  content_htmlsource text NOT NULL default '',
  thank_you text DEFAULT '',
  spotlight tinyint(2) not null default 0,
  template varchar(55) default NULL,         # currently unused
  PRIMARY KEY (actionTextID),
  INDEX actionID (actionID),
  UNIQUE INDEX actionlanguage (actionID,languageID)
) TYPE=MyISAM;


#
# Table structure for table `be_actionType`
#
DROP TABLE IF EXISTS be_actionType;
CREATE TABLE be_actionType (
  actionTypeID smallint(5) unsigned NOT NULL,
  description varchar(255) NOT NULL,
  PRIMARY KEY (actionTypeID)
) TYPE=MyISAM;


#
# Table structure for table `be_targetType`
#
DROP TABLE IF EXISTS be_targetType;
CREATE TABLE be_targetType (
  targetTypeID smallint(5) unsigned NOT NULL,
  description varchar(255) NOT NULL,
  PRIMARY KEY (targetTypeID)
) TYPE=MyISAM;


#
# Table structure for table `be_action2section`
#
DROP TABLE IF EXISTS be_action2section;
CREATE TABLE be_action2section (
  actionID smallint(5) unsigned NOT NULL,
  sectionID smallint(5) unsigned NOT NULL,
  PRIMARY KEY (actionID, sectionID)
) TYPE=MyISAM;


#
# Table structure for table `be_actionContact`
#
#
DROP TABLE IF EXISTS be_contact;
CREATE TABLE be_contact (
  contactID smallint(5) unsigned NOT NULL auto_increment,
  contactType smallint(5) unsigned NOT NULL,
  firstName varchar(50) NOT NULL,
  lastName varchar(50) NOT NULL,
  companyName varchar(100) default '',
  displayName varchar(100) NOT NULL,
  gender char(2) default 'U',
  title varchar(50) default '',
  email varchar(100) default '',
  phoneNumber varchar(50) default '',
  faxNumber varchar(50) default '',
  address varchar(100) default '',
  city varchar(50) default '',
  province varchar(20) default '',
  postalCode varchar(20) default '',
  country varchar(20) default '',
  notes text,
  target tinyint(2) default '0',
  dateCreated INT(10) UNSIGNED NOT NULL,
  dateModified INT(10) UNSIGNED NOT NULL,
  followupGlobal TINYINT(2) NOT NULL default '0',
  verified tinyint(1) unsigned DEFAULT 0,
  randomKey varchar(10) DEFAULT "",
  sameContactAs smallint(5) unsigned DEFAULT 0,
  author_id smallint(11) unsigned DEFAULT 0,
  enteredBy smallint(11) unsigned DEFAULT 0,
  dateVerified int(10) unsigned default 0,

  PRIMARY KEY (contactID),
  INDEX randomKey(randomKey),
  INDEX email(email),
  INDEX author_id(author_id)
) TYPE=MyISAM;

#
# Table structure for table `be_action2contact`
#
DROP TABLE IF EXISTS be_action2contact;
CREATE TABLE be_action2contact (
  actionID smallint(5) UNSIGNED NOT NULL,
  contactID smallint(5) UNSIGNED NOT NULL,
  targetID smallint(5) UNSIGNED NOT NULL,
  extraContent text default '',
  customContent text default '',
  followup tinyint(5) UNSIGNED default '0',
  dateDelivered INT( 10 ) UNSIGNED default '0',
  PRIMARY KEY (contactID, actionID, targetID)
) TYPE=MyISAM;


# Upgrade Sept4, 2003
#  ALTER TABLE `be_contact` ADD `followup` SMALLINT( 2 ) DEFAULT '0' NOT NULL;
# Upgrade Feb 2005: See Upgrade714to715.sql

DROP TABLE IF EXISTS be_targetFinder;
CREATE TABLE be_targetFinder (
  targetFinderID SMALLINT(5) UNSIGNED NOT NULL,
  countryID CHAR(3) NOT NULL default '',
  targetTypeName VARCHAR(30) NOT NULL,
  active SMALLINT(1) UNSIGNED NOT NULL DEFAULT '1',
  targetFinderClassName VARCHAR(40) NOT NULL,
  targetFinderClassVersion SMALLINT(4) UNSIGNED NOT NULL DEFAULT 1,
  targetFinderParameters VARCHAR(200) NOT NULL DEFAULT '',
  PRIMARY KEY (targetFinderID)
) TYPE=MyISAM;

DROP TABLE IF EXISTS be_targetFinder2action;
CREATE TABLE be_targetFinder2action (
 targetFinderID SMALLINT(5) UNSIGNED NOT NULL,
 actionID smallint(5) UNSIGNED NOT NULL,
 PRIMARY KEY (targetFinderID,actionID),
 INDEX actionID (actionID)
) TYPE=MyISAM;

DROP TABLE IF EXISTS be_target2participant;
CREATE TABLE be_target2participant (
       targetFinderID SMALLINT(5) UNSIGNED NOT NULL,
       participantID SMALLINT(5) UNSIGNED NOT NULL,
       targetID SMALLINT(5) UNSIGNED NOT NULL,
       lastChecked INT( 10 ) UNSIGNED DEFAULT '0',
       success TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
       PRIMARY KEY (targetFinderID, participantID)
) TYPE=MyISAM;

#
# populate_action_values.sql
#
# Population tables with initial values for Back-End Actions
#
# @package   Back-End on phpSlash
# @author    Peter Bojanic
# @copyright Copyright (C) 2003 OpenConcept Consulting
# @version   $Id: BE_all.sql,v 1.62 2005/06/19 10:37:21 krabu Exp $
#
#
# This file is part of Back-End.
#
# Back-End is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# Back-End is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Back-End; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA


#
# Table descriptions
#
# see the script add_action_tables.sql for details on these tables


#
# Populate values for tables `be_actionType`
#
INSERT INTO be_actionType
  (actionTypeID, description)
  VALUES
  (1, 'Email'),
  (2, 'Fax');

#
# Populate values for table `be_contactType`
#
INSERT INTO be_contactType
  (contactTypeID, description)
  VALUES
  (1, 'Private citizen'),
  (2, 'MP/MLA');


#
# Populate values for table `be_targetType`
#

INSERT INTO be_targetFinder
  (targetFinderID, countryID,  targetTypeName, targetFinderClassName, active, targetFinderClassVersion)
  VALUES
  (1, 'CAN', 'MP', '', 0, 1),
  (2, 'CAN', 'MP', 'BE_TargetFinderMP_CA', 1, 2),
  (3, 'CAN', 'Walmart', 'BE_TargetFinderWalmart_CA', 1, 1);


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
INSERT INTO `pet_country` VALUES ('AFG', 'Afghanistan');
INSERT INTO `pet_country` VALUES ('ALB', 'Albania');
INSERT INTO `pet_country` VALUES ('DZA', 'Algeria');
INSERT INTO `pet_country` VALUES ('ASM', 'American Samoa');
INSERT INTO `pet_country` VALUES ('AND', 'Andorra');
INSERT INTO `pet_country` VALUES ('AGO', 'Angola');
INSERT INTO `pet_country` VALUES ('AIA', 'Anguilla');
INSERT INTO `pet_country` VALUES ('ATG', 'Antigua and Barbuda');
INSERT INTO `pet_country` VALUES ('ARG', 'Argentina');
INSERT INTO `pet_country` VALUES ('ARM', 'Armenia');
INSERT INTO `pet_country` VALUES ('ABW', 'Aruba');
INSERT INTO `pet_country` VALUES ('AUS', 'Australia');
INSERT INTO `pet_country` VALUES ('AUT', 'Austria');
INSERT INTO `pet_country` VALUES ('AZE', 'Azerbaijan');
INSERT INTO `pet_country` VALUES ('BHS', 'Bahamas');
INSERT INTO `pet_country` VALUES ('BHR', 'Bahrain');
INSERT INTO `pet_country` VALUES ('BGD', 'Bangladesh');
INSERT INTO `pet_country` VALUES ('BRB', 'Barbados');
INSERT INTO `pet_country` VALUES ('BLR', 'Belarus');
INSERT INTO `pet_country` VALUES ('BEL', 'Belgium');
INSERT INTO `pet_country` VALUES ('BLZ', 'Belize');
INSERT INTO `pet_country` VALUES ('BEN', 'Benin');
INSERT INTO `pet_country` VALUES ('BMU', 'Bermuda');
INSERT INTO `pet_country` VALUES ('BTN', 'Bhutan');
INSERT INTO `pet_country` VALUES ('BOL', 'Bolivia');
INSERT INTO `pet_country` VALUES ('BIH', 'Bosnia and Herzegovina');
INSERT INTO `pet_country` VALUES ('BWA', 'Botswana');
INSERT INTO `pet_country` VALUES ('BRA', 'Brazil');
INSERT INTO `pet_country` VALUES ('VGB', 'British Virgin Islands');
INSERT INTO `pet_country` VALUES ('BRN', 'Brunei Darussalam');
INSERT INTO `pet_country` VALUES ('BGR', 'Bulgaria');
INSERT INTO `pet_country` VALUES ('BFA', 'Burkina Faso');
INSERT INTO `pet_country` VALUES ('BDI', 'Burundi');
INSERT INTO `pet_country` VALUES ('KHM', 'Cambodia');
INSERT INTO `pet_country` VALUES ('CMR', 'Cameroon');
INSERT INTO `pet_country` VALUES ('CAN', 'Canada');
INSERT INTO `pet_country` VALUES ('CPV', 'Cape Verde');
INSERT INTO `pet_country` VALUES ('CYM', 'Cayman Islands');
INSERT INTO `pet_country` VALUES ('CAF', 'Central African Republic');
INSERT INTO `pet_country` VALUES ('TCD', 'Chad');
INSERT INTO `pet_country` VALUES ('CHL', 'Chile');
INSERT INTO `pet_country` VALUES ('CHN', 'China');
INSERT INTO `pet_country` VALUES ('HKG', 'Hong Kong Special Administrative');
INSERT INTO `pet_country` VALUES ('MAC', 'Macao Special Administrative Region of China');
INSERT INTO `pet_country` VALUES ('COL', 'Colombia');
INSERT INTO `pet_country` VALUES ('COM', 'Comoros');
INSERT INTO `pet_country` VALUES ('COG', 'Congo');
INSERT INTO `pet_country` VALUES ('COK', 'Cook Islands');
INSERT INTO `pet_country` VALUES ('CRI', 'Costa Rica');
INSERT INTO `pet_country` VALUES ('CIV', 'Cote d\'Ivoire');
INSERT INTO `pet_country` VALUES ('HRV', 'Croatia');
INSERT INTO `pet_country` VALUES ('CUB', 'Cuba');
INSERT INTO `pet_country` VALUES ('CYP', 'Cyprus');
INSERT INTO `pet_country` VALUES ('CZE', 'Czech Republic');
INSERT INTO `pet_country` VALUES ('PRK', 'Democratic People\'s Republic of Korea');
INSERT INTO `pet_country` VALUES ('COD', 'Democratic Republic of the Congo');
INSERT INTO `pet_country` VALUES ('DNK', 'Denmark');
INSERT INTO `pet_country` VALUES ('DJI', 'Djibouti');
INSERT INTO `pet_country` VALUES ('DMA', 'Dominica');
INSERT INTO `pet_country` VALUES ('DOM', 'Dominican Republic');
INSERT INTO `pet_country` VALUES ('TMP', 'East Timor');
INSERT INTO `pet_country` VALUES ('ECU', 'Ecuador');
INSERT INTO `pet_country` VALUES ('EGY', 'Egypt');
INSERT INTO `pet_country` VALUES ('SLV', 'El Salvador');
INSERT INTO `pet_country` VALUES ('GNQ', 'Equatorial Guinea');
INSERT INTO `pet_country` VALUES ('ERI', 'Eritrea');
INSERT INTO `pet_country` VALUES ('EST', 'Estonia');
INSERT INTO `pet_country` VALUES ('ETH', 'Ethiopia');
INSERT INTO `pet_country` VALUES ('FRO', 'Faeroe Islands');
INSERT INTO `pet_country` VALUES ('FLK', 'Falkland Islands (Malvinas)');
INSERT INTO `pet_country` VALUES ('FJI', 'Fiji');
INSERT INTO `pet_country` VALUES ('FIN', 'Finland');
INSERT INTO `pet_country` VALUES ('FRA', 'France');
INSERT INTO `pet_country` VALUES ('GUF', 'French Guiana');
INSERT INTO `pet_country` VALUES ('PYF', 'French Polynesia');
INSERT INTO `pet_country` VALUES ('GAB', 'Gabon');
INSERT INTO `pet_country` VALUES ('GMB', 'Gambia');
INSERT INTO `pet_country` VALUES ('GEO', 'Georgia');
INSERT INTO `pet_country` VALUES ('DEU', 'Germany');
INSERT INTO `pet_country` VALUES ('GHA', 'Ghana');
INSERT INTO `pet_country` VALUES ('GIB', 'Gibraltar');
INSERT INTO `pet_country` VALUES ('GRC', 'Greece');
INSERT INTO `pet_country` VALUES ('GRL', 'Greenland');
INSERT INTO `pet_country` VALUES ('GRD', 'Grenada');
INSERT INTO `pet_country` VALUES ('GLP', 'Guadeloupe');
INSERT INTO `pet_country` VALUES ('GUM', 'Guam');
INSERT INTO `pet_country` VALUES ('GTM', 'Guatemala');
INSERT INTO `pet_country` VALUES ('GIN', 'Guinea');
INSERT INTO `pet_country` VALUES ('GNB', 'Guinea-Bissau');
INSERT INTO `pet_country` VALUES ('GUY', 'Guyana');
INSERT INTO `pet_country` VALUES ('HTI', 'Haiti');
INSERT INTO `pet_country` VALUES ('VAT', 'Holy See');
INSERT INTO `pet_country` VALUES ('HND', 'Honduras');
INSERT INTO `pet_country` VALUES ('HUN', 'Hungary');
INSERT INTO `pet_country` VALUES ('ISL', 'Iceland');
INSERT INTO `pet_country` VALUES ('IND', 'India');
INSERT INTO `pet_country` VALUES ('IDN', 'Indonesia');
INSERT INTO `pet_country` VALUES ('IRN', 'Iran (Islamic Republic of)');
INSERT INTO `pet_country` VALUES ('IRQ', 'Iraq');
INSERT INTO `pet_country` VALUES ('IRL', 'Ireland');
INSERT INTO `pet_country` VALUES ('ISR', 'Israel');
INSERT INTO `pet_country` VALUES ('ITA', 'Italy');
INSERT INTO `pet_country` VALUES ('JAM', 'Jamaica');
INSERT INTO `pet_country` VALUES ('JPN', 'Japan');
INSERT INTO `pet_country` VALUES ('JOR', 'Jordan');
INSERT INTO `pet_country` VALUES ('KAZ', 'Kazakhstan');
INSERT INTO `pet_country` VALUES ('KEN', 'Kenya');
INSERT INTO `pet_country` VALUES ('KIR', 'Kiribati');
INSERT INTO `pet_country` VALUES ('KWT', 'Kuwait');
INSERT INTO `pet_country` VALUES ('KGZ', 'Kyrgyzstan');
INSERT INTO `pet_country` VALUES ('LAO', 'Lao People\'s Democratic Republic');
INSERT INTO `pet_country` VALUES ('LVA', 'Latvia');
INSERT INTO `pet_country` VALUES ('LBN', 'Lebanon');
INSERT INTO `pet_country` VALUES ('LSO', 'Lesotho');
INSERT INTO `pet_country` VALUES ('LBR', 'Liberia');
INSERT INTO `pet_country` VALUES ('LBY', 'Libyan Arab Jamahiriya');
INSERT INTO `pet_country` VALUES ('LIE', 'Liechtenstein');
INSERT INTO `pet_country` VALUES ('LTU', 'Lithuania');
INSERT INTO `pet_country` VALUES ('LUX', 'Luxembourg');
INSERT INTO `pet_country` VALUES ('MDG', 'Madagascar');
INSERT INTO `pet_country` VALUES ('MWI', 'Malawi');
INSERT INTO `pet_country` VALUES ('MYS', 'Malaysia');
INSERT INTO `pet_country` VALUES ('MDV', 'Maldives');
INSERT INTO `pet_country` VALUES ('MLI', 'Mali');
INSERT INTO `pet_country` VALUES ('MLT', 'Malta');
INSERT INTO `pet_country` VALUES ('MHL', 'Marshall Islands');
INSERT INTO `pet_country` VALUES ('MTQ', 'Martinique');
INSERT INTO `pet_country` VALUES ('MRT', 'Mauritania');
INSERT INTO `pet_country` VALUES ('MUS', 'Mauritius');
INSERT INTO `pet_country` VALUES ('MEX', 'Mexico');
INSERT INTO `pet_country` VALUES ('FSM', 'Micronesia Federated States of,');
INSERT INTO `pet_country` VALUES ('MCO', 'Monaco');
INSERT INTO `pet_country` VALUES ('MNG', 'Mongolia');
INSERT INTO `pet_country` VALUES ('MSR', 'Montserrat');
INSERT INTO `pet_country` VALUES ('MAR', 'Morocco');
INSERT INTO `pet_country` VALUES ('MOZ', 'Mozambique');
INSERT INTO `pet_country` VALUES ('MMR', 'Myanmar');
INSERT INTO `pet_country` VALUES ('NAM', 'Namibia');
INSERT INTO `pet_country` VALUES ('NRU', 'Nauru');
INSERT INTO `pet_country` VALUES ('NPL', 'Nepal');
INSERT INTO `pet_country` VALUES ('NLD', 'Netherlands');
INSERT INTO `pet_country` VALUES ('ANT', 'Netherlands Antilles');
INSERT INTO `pet_country` VALUES ('NCL', 'New Caledonia');
INSERT INTO `pet_country` VALUES ('NZL', 'New Zealand');
INSERT INTO `pet_country` VALUES ('NIC', 'Nicaragua');
INSERT INTO `pet_country` VALUES ('NER', 'Niger');
INSERT INTO `pet_country` VALUES ('NGA', 'Nigeria');
INSERT INTO `pet_country` VALUES ('NIU', 'Niue');
INSERT INTO `pet_country` VALUES ('NFK', 'Norfolk Island');
INSERT INTO `pet_country` VALUES ('MNP', 'Northern Mariana Islands');
INSERT INTO `pet_country` VALUES ('NOR', 'Norway');
INSERT INTO `pet_country` VALUES ('PSE', 'Occupied Palestinian Territory');
INSERT INTO `pet_country` VALUES ('OMN', 'Oman');
INSERT INTO `pet_country` VALUES ('PAK', 'Pakistan');
INSERT INTO `pet_country` VALUES ('PLW', 'Palau');
INSERT INTO `pet_country` VALUES ('PAN', 'Panama');
INSERT INTO `pet_country` VALUES ('PNG', 'Papua New Guinea');
INSERT INTO `pet_country` VALUES ('PRY', 'Paraguay');
INSERT INTO `pet_country` VALUES ('PER', 'Peru');
INSERT INTO `pet_country` VALUES ('PHL', 'Philippines');
INSERT INTO `pet_country` VALUES ('PCN', 'Pitcairn');
INSERT INTO `pet_country` VALUES ('POL', 'Poland');
INSERT INTO `pet_country` VALUES ('PRT', 'Portugal');
INSERT INTO `pet_country` VALUES ('PRI', 'Puerto Rico');
INSERT INTO `pet_country` VALUES ('QAT', 'Qatar');
INSERT INTO `pet_country` VALUES ('KOR', 'Republic of Korea');
INSERT INTO `pet_country` VALUES ('MDA', 'Republic of Moldova');
INSERT INTO `pet_country` VALUES ('REU', 'Réunion');
INSERT INTO `pet_country` VALUES ('ROM', 'Romania');
INSERT INTO `pet_country` VALUES ('RUS', 'Russian Federation');
INSERT INTO `pet_country` VALUES ('RWA', 'Rwanda');
INSERT INTO `pet_country` VALUES ('SHN', 'Saint Helena');
INSERT INTO `pet_country` VALUES ('KNA', 'Saint Kitts and Nevis');
INSERT INTO `pet_country` VALUES ('LCA', 'Saint Lucia');
INSERT INTO `pet_country` VALUES ('SPM', 'Saint Pierre and Miquelon');
INSERT INTO `pet_country` VALUES ('VCT', 'Saint Vincent and the Grenadines');
INSERT INTO `pet_country` VALUES ('WSM', 'Samoa');
INSERT INTO `pet_country` VALUES ('SMR', 'San Marino');
INSERT INTO `pet_country` VALUES ('STP', 'Sao Tome and Principe');
INSERT INTO `pet_country` VALUES ('SAU', 'Saudi Arabia');
INSERT INTO `pet_country` VALUES ('SEN', 'Senegal');
INSERT INTO `pet_country` VALUES ('SYC', 'Seychelles');
INSERT INTO `pet_country` VALUES ('SLE', 'Sierra Leone');
INSERT INTO `pet_country` VALUES ('SGP', 'Singapore');
INSERT INTO `pet_country` VALUES ('SVK', 'Slovakia');
INSERT INTO `pet_country` VALUES ('SVN', 'Slovenia');
INSERT INTO `pet_country` VALUES ('SLB', 'Solomon Islands');
INSERT INTO `pet_country` VALUES ('SOM', 'Somalia');
INSERT INTO `pet_country` VALUES ('ZAF', 'South Africa');
INSERT INTO `pet_country` VALUES ('ESP', 'Spain');
INSERT INTO `pet_country` VALUES ('LKA', 'Sri Lanka');
INSERT INTO `pet_country` VALUES ('SDN', 'Sudan');
INSERT INTO `pet_country` VALUES ('SUR', 'Suriname');
INSERT INTO `pet_country` VALUES ('SJM', 'Svalbard and Jan Mayen Islands');
INSERT INTO `pet_country` VALUES ('SWZ', 'Swaziland');
INSERT INTO `pet_country` VALUES ('SWE', 'Sweden');
INSERT INTO `pet_country` VALUES ('CHE', 'Switzerland');
INSERT INTO `pet_country` VALUES ('SYR', 'Syrian Arab Republic');
INSERT INTO `pet_country` VALUES ('TWN', 'Taiwan Province of China');
INSERT INTO `pet_country` VALUES ('TJK', 'Tajikistan');
INSERT INTO `pet_country` VALUES ('THA', 'Thailand');
INSERT INTO `pet_country` VALUES ('MKD', 'The former Yugoslav Republic of Macedonia');
INSERT INTO `pet_country` VALUES ('TGO', 'Togo');
INSERT INTO `pet_country` VALUES ('TKL', 'Tokelau');
INSERT INTO `pet_country` VALUES ('TON', 'Tonga');
INSERT INTO `pet_country` VALUES ('TTO', 'Trinidad and Tobago');
INSERT INTO `pet_country` VALUES ('TUN', 'Tunisia');
INSERT INTO `pet_country` VALUES ('TUR', 'Turkey');
INSERT INTO `pet_country` VALUES ('TKM', 'Turkmenistan');
INSERT INTO `pet_country` VALUES ('TCA', 'Turks and Caicos Islands');
INSERT INTO `pet_country` VALUES ('TUV', 'Tuvalu');
INSERT INTO `pet_country` VALUES ('UGA', 'Uganda');
INSERT INTO `pet_country` VALUES ('UKR', 'Ukraine');
INSERT INTO `pet_country` VALUES ('ARE', 'United Arab Emirates');
INSERT INTO `pet_country` VALUES ('GBR', 'United Kingdom');
INSERT INTO `pet_country` VALUES ('TZA', 'United Republic of Tanzania');
INSERT INTO `pet_country` VALUES ('USA', 'United States');
INSERT INTO `pet_country` VALUES ('VIR', 'United States Virgin Islands');
INSERT INTO `pet_country` VALUES ('URY', 'Uruguay');
INSERT INTO `pet_country` VALUES ('UZB', 'Uzbekistan');
INSERT INTO `pet_country` VALUES ('VUT', 'Vanuatu');
INSERT INTO `pet_country` VALUES ('VEN', 'Venezuela');
INSERT INTO `pet_country` VALUES ('VNM', 'Viet Nam');
INSERT INTO `pet_country` VALUES ('WLF', 'Wallis and Futuna Islands');
INSERT INTO `pet_country` VALUES ('ESH', 'Western Sahara');
INSERT INTO `pet_country` VALUES ('YEM', 'Yemen');
INSERT INTO `pet_country` VALUES ('YUG', 'Yugoslavia');
INSERT INTO `pet_country` VALUES ('ZMB', 'Zambia');
INSERT INTO `pet_country` VALUES ('ZWE', 'Zimbabwe');
# --------------------------------------------------------


#
# Dumping data for table `pet_petition`
#

INSERT INTO `pet_petition` VALUES (1, 'SamplePetition', 0, 'Anonymous', 'name@example.org', 'Campaign Team', 1057118400, 1059688751, 1057118400, 0, 1272686400, 0, 0, 0, 0, 0, -1);


# Dumping data for table `pet_petitionText`
#

INSERT INTO `pet_petitionText` VALUES (2, 1, 'en', 'Sample Petition', NULL, 'Petition for Social Change', NULL, 'The text of the petition', NULL, '', NULL, '', NULL, '', NULL, '', '', '', 'Hello [FIRST_NAME],\r\n\r\nWe have record of you signing our petition at:\r\n   [HOME_URL]\r\n\r\nPlease click here to confirm and validate this record and your support:\r\n   [VERIFY_URL]\r\n\r\nThank you, \r\n\r\n', 'This appears at the top of an email2friend alert which your supporters may distribute', NULL, 0);

# $Id: BE_all.sql,v 1.62 2005/06/19 10:37:21 krabu Exp $
#
# Dumping data for table `be_article2section`
#

# Clear previous entries
DELETE FROM be_article2section;

INSERT INTO be_article2section (`articleID`, `sectionID`) VALUES (7, 7);
INSERT INTO be_article2section (`articleID`, `sectionID`) VALUES (4, 4);
INSERT INTO be_article2section (`articleID`, `sectionID`) VALUES (6, 7);

# --------------------------------------------------------


#
# Dumping data for table `be_articleText`
#

# Clear previous entries
DELETE FROM `be_articleText`;

INSERT INTO be_articleText VALUES (4, 4, 'en', 'BEupgradeTemplates', 'Templates (why are there so many of them)', '', 'BE0.4.x used a single template to define a whole page.  BE0.5.x uses three (esentially). slashHead.tpl, BE_body.tpl and slashFoot.tpl.  It&#039;s more complicated than this as phpSlash&#039;s blocks allow you to add left or right had columns all through the web form.  It does make it more complicated to set up, however it becomes a lot more powerful and easy to maintain. The template option in the admin web form allows you to specify a new look for a specific page or article.  If you created a template &#039;newlook&#039; it would look for slashHead-newlook.tpl, BE_body-newlook.tpl and slashFoot-newlook.tpl.  If it didn&#039;t find one of them, it would just use the default (ie. slashHead.tpl, BE_body.tpl or slashFoot.tpl).  If you want to add threading to most web sites you could do this pretty easily by just adding a slashHead-thread1.tpl.\r\n\r\n<p>To upgrade your templates from .4.x the critical piece is to know where to splice them.  You would do this much as if you had a simple header.inc or footer.inc file.  if you had a template which you had called &#039;home.tpl&#039; that you wanted to use on the home page of BE5 you could split it into slashHead-home.tpl.tpl, BE_body-home.tpl.tpl and slashFoot-home.tpl.tpl.  The double &#039;.tpl&#039; would be required unless you change the template name to &#039;home&#039; in your database.\r\n\r\n</p><p>Best place to look for what BE5 is doing with the templates is in the source.  Viewing the source will show you which templates are being called to generate a given page.</p>', '', '', '', 0, '0', '2', '', 0, 1, unix_timestamp(now()));
INSERT INTO be_articleText VALUES (6, 6, 'en', 'wlp', 'Mirroring and then replacing static .html structure', '', 'If you have a static .html site.  Moving to a CMS often has disincentives if you have to change the URLs that your visitors have grown to know.\r\n\r\n<p>With the new Back-End you can avoid this by mirroring the given structure with .phtml files which pass along the critical Article/Section information.  This also allows you to provide multi-lingual content in the same structure as the static site.\r\n\r\n</p><p>Check out <a href="http://www.learningpartnership.org" target="_blank">WLP</a></p>', '', '', '', 0, '0', '3', '', 0, 2, unix_timestamp(now()));
INSERT INTO be_articleText VALUES (7, 7, 'en', 'javaScriptDD', 'javaScript Drop Downs', '', 'If you want to use JavaScript Dropdowns to build better navigation for your site, BE5 allows you to insert dynamic dropdowns with your site&#039;s main sections and sub-sections.\r\n\r\n<p>Check out <a href="http://www.billblaikie.org" target="_blank">billblaikie.org</a></p>', '', '', '', 0, '0', '4', '', 0, 3, unix_timestamp(now()));
INSERT INTO be_articleText VALUES (8, 4, 'fr', 'BEupgradeTemplates', 'FR Templates (why are there so many of them)', '', 'BE0.4.x used a single template to define a whole page.  BE0.5.x uses three (esentially). slashHead.tpl, BE_body.tpl and slashFoot.tpl.  It&#039;s more complicated than this as phpSlash&#039;s blocks allow you to add left or right had columns all through the web form.  It does make it more complicated to set up, however it becomes a lot more powerful and easy to maintain. The template option in the admin web form allows you to specify a new look for a specific page or article.  If you created a template &#039;newlook&#039; it would look for slashHead-newlook.tpl, BE_body-newlook.tpl and slashFoot-newlook.tpl.  If it didn&#039;t find one of them, it would just use the default (ie. slashHead.tpl, BE_body.tpl or slashFoot.tpl).  If you want to add threading to most web sites you could do this pretty easily by just adding a slashHead-thread1.tpl.\r\n\r\n<p>To upgrade your templates from .4.x the critical piece is to know where to splice them.  You would do this much as if you had a simple header.inc or footer.inc file.  if you had a template which you had called &#039;home.tpl&#039; that you wanted to use on the home page of BE5 you could split it into slashHead-home.tpl.tpl, BE_body-home.tpl.tpl and slashFoot-home.tpl.tpl.  The double &#039;.tpl&#039; would be required unless you change the template name to &#039;home&#039; in your database.\r\n\r\n</p><p>Best place to look for what BE5 is doing with the templates is in the source.  Viewing the source will show you which templates are being called to generate a given page.</p>', '', '', '', 0, '0', '2', '', 0, 4, unix_timestamp(now()));
INSERT INTO be_articleText VALUES (9, 6, 'fr', 'wlp', 'FR Mirroring and then replacing static .html structure', '', 'If you have a static .html site.  Moving to a CMS often has disincentives if you have to change the URLs that your visitors have grown to know.\r\n\r\n<p>With the new Back-End you can avoid this by mirroring the given structure with .phtml files which pass along the critical Article/Section information.  This also allows you to provide multi-lingual content in the same structure as the static site.\r\n\r\n</p><p>Check out <a href="http://www.learningpartnership.org" target="_blank">WLP</a></p>', '', '', '', 0, '0', '3', '', 0, 5, unix_timestamp(now()));
INSERT INTO be_articleText VALUES (10, 7, 'fr', 'javaScriptDD', 'FR javaScript Drop Downs', '', 'If you want to use JavaScript Dropdowns to build better navigation for your site, BE5 allows you to insert dynamic dropdowns with your site&#039;s main sections and sub-sections.\r\n\r\n<p>Check out <a href="http://www.billblaikie.org" target="_blank">billblaikie.org</a></p>', '', '', '', 0, '0', '4', '', 0, 6, unix_timestamp(now()));


# increment commentID and ensure it isn't set to 0
# UPDATE `be_articleText` SET `commentIDtext` = '1' WHERE `articleTextID` = '10';
# UPDATE `be_articleText` SET `commentIDtext` = '2' WHERE `articleTextID` = '9';
# UPDATE `be_articleText` SET `commentIDtext` = '3' WHERE `articleTextID` = '8';
# UPDATE `be_articleText` SET `commentIDtext` = '4' WHERE `articleTextID` = '7';
# UPDATE `be_articleText` SET `commentIDtext` = '5' WHERE `articleTextID` = '6';
# UPDATE `be_articleText` SET `commentIDtext` = '6' WHERE `articleTextID` = '4';
# UPDATE `be_articles` SET `subsiteID` = NULL ,
#`commentID` = '7' WHERE `articleID` = '7' AND `URLname` = 'javaScriptDD';
# UPDATE `be_articles` SET `subsiteID` = NULL ,
#`commentID` = '8' WHERE `articleID` = '6' AND `URLname` = 'wlp';
# UPDATE `be_articles` SET `subsiteID` = NULL ,
#`commentID` = '9' WHERE `articleID` = '4' AND `URLname` = 'BEupgradeTemplates';

# --------------------------------------------------------


#
# Dumping data for table `be_articles`
#

# Clear previous entries
DELETE FROM `be_articles`;

INSERT INTO `be_articles` (`articleID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `spotlight`, `showPrint`, `useIcons`, `hitCounter`, `priority`, `commentID`) VALUES (4, 'BEupgradeTemplates', 1, 1041835651, 1041835651, 1041835651, 1830775651, 0, '', 0, 1, 0, 0, 0, 0, 5, 0);
INSERT INTO `be_articles` (`articleID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `spotlight`, `showPrint`, `useIcons`, `hitCounter`, `priority`, `commentID`) VALUES (6, 'wlp', 1, 1041835651, 1041835651, 1041835651, 1830775651, 0, '', 0, 1, 0, 0, 0, 0, 6, 0);
INSERT INTO `be_articles` (`articleID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `spotlight`, `showPrint`, `useIcons`, `hitCounter`, `priority`, `commentID`) VALUES (7, 'javaScriptDD', 1, 1041835651, 1041835651, 1041835651, 1830775651, 0, '', 0, 0, 0, 0, 0, 0, 7, 0);

# --------------------------------------------------------


#
# Dumping data for table `be_link`
#

# Clear previous entries
DELETE FROM `be_link`;

INSERT INTO `be_link` (`linkID`, `url`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `content_type`, `hide`, `restrict2members`, `hitCounter`, `priority`) VALUES (1, 'http://www.back-end.org', 1, 1041835651, 1041835651, 1041835651, 1830775651, '', 0, 0, 0, 0);
INSERT INTO `be_link` (`linkID`, `url`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `content_type`, `hide`, `restrict2members`, `hitCounter`, `priority`) VALUES (2, 'http://www.openconcept.ca', 1, 1041835651, 1041835651, 1041835651, 1830775651, '', 0, 0, 0, 0);
INSERT INTO `be_link` (`linkID`, `url`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `content_type`, `hide`, `restrict2members`, `hitCounter`, `priority`) VALUES (3, 'http://phpslash.sf.net', 1, 1041835651, 1041835651, 1041835651, 1830775651, '', 0, 0, 0, 0);
INSERT INTO `be_link` (`linkID`, `url`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `content_type`, `hide`, `restrict2members`, `hitCounter`, `priority`) VALUES (4, 'http://www.sf.net', 1, 1041835651, 1041835651, 1041835651, 1830775651, '', 0, 0, 0, 0);
INSERT INTO `be_link` (`linkID`, `url`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `content_type`, `hide`, `restrict2members`, `hitCounter`, `priority`) VALUES (6, 'http://www.billblaikie.ca', 1, 1065326400, 1065346096, 1065326400, 0, '', 0, 0, 0, 0);
INSERT INTO `be_link` (`linkID`, `url`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `content_type`, `hide`, `restrict2members`, `hitCounter`, `priority`) VALUES (8, 'http://www.pgs.ca/', 1, 1065326400, 1065346199, 1065326400, 0, '', 0, 0, 0, 0);
INSERT INTO `be_link` (`linkID`, `url`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `content_type`, `hide`, `restrict2members`, `hitCounter`, `priority`) VALUES (9, 'http://www.cupe.ca/', 1, 1065326400, 1065346266, 1065326400, 0, '', 0, 0, 0, 0);
INSERT INTO `be_link` (`linkID`, `url`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `content_type`, `hide`, `restrict2members`, `hitCounter`, `priority`) VALUES (10, 'http://www.fairvotecanada.org/', 1, 1065326400, 1065346372, 1065326400, 0, '', 0, 0, 0, 0);
INSERT INTO `be_link` (`linkID`, `url`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `content_type`, `hide`, `restrict2members`, `hitCounter`, `priority`) VALUES (14, 'http://www.openconcept.ca/be_wiki/', 39, 1069056000, 1069070529, 1069056000, 0, '', 0, 0, 0, 90);
INSERT INTO `be_link` (`linkID`, `url`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `content_type`, `hide`, `restrict2members`, `hitCounter`, `priority`) VALUES (15, 'http://cvs-demo.back-end.org/', 39, 1069056000, 1069071667, 1069056000, 0, '', 0, 0, 0, 0);
INSERT INTO be_link VALUES (16, 'http://www.calgaryblizzard.com/', 1, 1087790400, 1087872005, 1087790400, 0, '', 0, 0, 0, 0);
INSERT INTO be_link VALUES (18, 'http://www.brianmasse.ca/', 1, 1087790400, 1087872183, 1087790400, 0, '', 0, 0, 0, 0);
INSERT INTO be_link VALUES (19, 'http://learningpartnership.org/', 1, 1087790400, 1087872260, 1087790400, 0, '', 0, 0, 0, 0);
INSERT INTO be_link VALUES (20, 'http://www.genderatwork.org/index.html', 1, 1087790400, 1087872312, 1087790400, 0, '', 0, 0, 0, 0);
INSERT INTO be_link VALUES (22, 'http://www.oxnet.org/', 1, 1087790400, 1087872452, 1087790400, 0, '', 0, 0, 0, 0);

# --------------------------------------------------------


#
# Dumping data for table `be_linkText`
#

# Clear previous entries
DELETE FROM `be_linkText`;

INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (1, 1, 'en', 'Back-End', '', 'This web application', '', '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (2, 2, 'en', 'OpenConcept', '', 'Primary Developers', '', '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (3, 3, 'en', 'phpSlash', '', 'A flexible web-logging system', '', '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (4, 4, 'en', 'SourceForge', '', 'The development area...', '', '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (5, 1, 'fr', 'Back-End', '', 'This web application', '', '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (6, 2, 'fr', 'OpenConcept', '', 'Primary Developers', '', '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (7, 3, 'fr', 'phpSlash', '', 'A flexible web-logging system', '', '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (8, 4, 'fr', 'SourceForge', '', 'The development area...', '', '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (9, 6, 'en', 'Bill Blaikie, MP', '', 'Canadian NDP MP', NULL, '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (11, 8, 'en', 'Physicians for Global Survival', '', ' Physicians for Global Survival (PGS) is a physician-led organization which, out of concern for global health, is committed to the abolition of nuclear weapons, the prevention of war, the promotion of non-violent means of conflict resolution and social justice in a sustainable world.', NULL, '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (12, 9, 'en', 'CUPE National', '', 'The Canadian Union of Public Employees (CUPE) is Canada\'s largest union. ', NULL, '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (13, 10, 'en', 'Fair Vote Canada', '', 'Fair Vote Canada (FVC) is a multi-partisan citizens\' campaign for voting system reform. ', NULL, '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (15, 12, 'en', 'United Church of Canada&#039;s Beads of Hope Campaign', '', 'Petition to address the global HIV/AIDS pandemic.', NULL, '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (16, 14, 'en', 'Developers Wiki', '', 'A resource for all developers to record their thought, ideas, and keep track of where the project is going', NULL, '', 0);
INSERT INTO `be_linkText` (`linkTextID`, `linkID`, `languageID`, `title`, `url`, `description`, `description_source`, `title_source`, `originalText`) VALUES (17, 15, 'en', 'Demo site', '', 'Demonstration site - rebuilt from the current CVS daily. It&#039;s generally available for you to have a play, log in as &#039;root&#039;, with password &#039;back-end&#039;.', NULL, '', 0);
INSERT INTO be_linkText VALUES (18, 16, 'en', 'Calgary Blizzard', '', '', NULL, '', 0);
INSERT INTO be_linkText VALUES (20, 18, 'en', 'Brian Masse, MP', '', '', NULL, '', 0);
INSERT INTO be_linkText VALUES (21, 19, 'en', 'Women&#039;s Learning Partnership', '', '', NULL, '', 0);
INSERT INTO be_linkText VALUES (22, 20, 'en', 'Gender At Work', '', '', NULL, '', 0);
INSERT INTO be_linkText VALUES (24, 22, 'en', 'Oxfam Canada&#039;s Volunteer Network (Oxnet)', '', '', NULL, '', 0);


# --------------------------------------------------------


#
# Dumping data for table `be_link2section`
#

# Clear previous entries
DELETE FROM `be_link2section`;

INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (1, 1);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (2, 1);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (3, 1);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (4, 1);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (6, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (7, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (8, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (9, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (10, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (11, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (12, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (13, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (14, 11);
INSERT INTO `be_link2section` (`linkID`, `sectionID`) VALUES (15, 11);
INSERT INTO `be_link2section` (`linkID`, `sectionID`)  VALUES (16, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`)  VALUES (17, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`)  VALUES (18, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`)  VALUES (19, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`)  VALUES (20, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`)  VALUES (21, 12);
INSERT INTO `be_link2section` (`linkID`, `sectionID`)  VALUES (22, 12);

# --------------------------------------------------------


#
# Dumping data for table `be_section2section`
#

# Clear previous entries
DELETE FROM `be_section2section`;

INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (0, 1);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (1, 2);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (1, 3);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (5, 4);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (11, 5);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (5, 6);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (0, 11);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (0, 12);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (12, 13);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (12, 14);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (12, 15);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (12, 16);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (12, 17);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (12, 18);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (5, 19);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (12, 20);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (15, 21);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (15, 22);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (15, 23);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (11, 24);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (5, 25);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (5, 26);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (5, 27);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (5, 28);
INSERT INTO `be_section2section` (`parentSectionID`, `childSectionID`) VALUES (5, 29);

# --------------------------------------------------------


#
# Dumping data for table `be_sectionText`
#

# Clear previous entries
DELETE FROM `be_sectionText`;


INSERT INTO be_sectionText VALUES (1, 1, 'en', 'Home', 'Home', 'Introduction to Back-End', '\r\n<p><strong>Back-End</strong>  allows even the non-technical to manage  any website easily, through a web browser, on any operating system. Fast, flexible and easy to understand, <strong>Back-End</strong> puts you in charge of your site, saving your organisation time and money in the process.<br /></p><p>We have released <a href="http://sourceforge.net/project/showfiles.php?group_id=6763">Back-End version 0.7.2</a> which is easy to install and packed with new features including the ability to undelete sections and articles.  The latest release is also working to be fully xhtml compliant.  <br /></p>\r\n<p><strong>Back-End</strong> is particularly suitable for advocacy organisations, with a suite of tools that allow you to create and manage polls and petitions, and to interact with your visitors, making it faster and easier for you to respond to issues as they arise, and to organise members activity.</p>\r\n<p>An <strong>Open Source</strong> program, under the <strong>General Public License</strong>, <strong>Back-End</strong> saves you money on license fees while allowing you, or your IT volunteers, the freedom to adapt the program to your unique needs.</p>\r\n<p>To learn more, please explore the site.  For more detailed information, you can post a question in the <a href="http://sourceforge.net/forum/forum.php?forum_id=20982"><strong>help forum</strong></a>, or join the <strong><a href="http://lists.sourceforge.net/lists/listinfo/back-end-support">support mailing list</a></strong>.  If you run into a bug, please add it to the <a href="http://sourceforge.net/tracker/?group_id=6763&amp;atid=106763" style="font-weight: bold;">bug tracker</a>.  If you\'ve got a feature request, post an <a href="http://sourceforge.net/tracker/?group_id=6763&amp;atid=356763">RFE</a>.<br /></p>', '\r\n<p><strong>Back-End</strong>  allows even the non-technical to manage  any website easily, through a web browser, on any operating system. Fast, flexible and easy to understand, <strong>Back-End</strong> puts you in charge of your site, saving your organisation time and money in the process.<br /></p><p>We have released <a href="http://sourceforge.net/project/showfiles.php?group_id=6763">Back-End version 0.7.2</a> which is easy to install and packed with new features including the ability to undelete sections and articles.  The latest release is also working to be fully xhtml compliant.  <br /></p>\r\n<p><strong>Back-End</strong> is particularly suitable for advocacy organisations, with a suite of tools that allow you to create and manage polls and petitions, and to interact with your visitors, making it faster and easier for you to respond to issues as they arise, and to organise members activity.</p>\r\n<p>An <strong>Open Source</strong> program, under the <strong>General Public License</strong>, <strong>Back-End</strong> saves you money on license fees while allowing you, or your IT volunteers, the freedom to adapt the program to your unique needs.</p>\r\n<p>To learn more, please explore the site.  For more detailed information, you can post a question in the <a href="http://sourceforge.net/forum/forum.php?forum_id=20982"><strong>help forum</strong></a>, or join the <strong><a href="http://lists.sourceforge.net/lists/listinfo/back-end-support">support mailing list</a></strong>.  If you run into a bug, please add it to the <a href="http://sourceforge.net/tracker/?group_id=6763&amp;atid=106763" style="font-weight: bold;">bug tracker</a>.  If you\'ve got a feature request, post an <a href="http://sourceforge.net/tracker/?group_id=6763&amp;atid=356763">RFE</a>.<br /></p>', 'Introduction to Back-End', 'Home', 'Home Page', 'Back-End.org A GPL CMS based on PHP/MySQL', NULL, 'home', 1, 0);
INSERT INTO be_sectionText VALUES (19, 15, 'en', 'WhyBE', 'Why Back-End?', 'Is Back-End the right CMS for you?', '<p >No CMS can meet all the needs of all possible users.  Many applications are either too expensive or too poorly adapted for the needs of small businesses, not-for-profit organisations, NGOs or advocacy groups. <strong >Back-End</strong> has most of the features of more expensive CMS software, but is specifically tailored to the needs of these groups.</p><p ><strong >Back-End</strong> offers the usual content creation and management tools, the ability to set different levels of access for different users, the ability to easily run mailing lists, have your own bulletin board, or use real-time chat. It has both simple and advanced Search options.</p><p ><strong >Back-End</strong> allows you to run polls, to have subsites -- related sites that appear to be independent of the main site, to maintain a photo gallery, to organise E-Actions like petitions and fax/letter-writing campaigns, to maintain an Events schedule to which your vistors may add events, to run an on-line store, to track user preferences.</p><p ><strong >Back-End</strong> also provides support for multiple languages in every aspect of the application -- not as an add-on, but built in from the ground up. </p><p >This is just a short list of some of the non-technical features. If you want to see a complete list of all the features of the latest version of <strong >Back-End</strong>, follow the links after this article...</p>', '<p>No CMS can meet all the needs of all possible users.═Many applications are either too expensive or too poorly adapted for the needs of small businesses, not-for-profit organisations, NGOs or advocacy groups. <strong>Back-End</strong> has most of the features of more expensive CMS software, but is specifically tailored to the needs of these groups.</p><p><strong>Back-End</strong> offers the usual content creation and management tools, the ability to set different levels of access for different users, the ability to easily run mailing lists, have your own bulletin board, or use real-time chat. It has both simple and advanced Search options.</p><p><strong>Back-End</strong> allows you to run polls, to have subsites -- related sites that appear to be independent of the main site, to maintain a photo gallery, to organise E-Actions like petitions and fax/letter-writing campaigns, to maintain an Events schedule to which your vistors may add events, to run an on-line store, to track user preferences.</p><p><strong>Back-End</strong> also provides support for multiple languages in every aspect of the application -- not as an add-on, but built in from the ground up. </p><p>This is just a short list of some of the non-technical features. If you want to see a complete list of all the features of the latest version of <strong>Back-End</strong>, follow the links after this article...</p>', 'Is Back-End the right CMS for you?', 'Why Back-End?', '', '', NULL, 'clients', 0, 35);
INSERT INTO be_sectionText VALUES (20, 16, 'en', 'WhatItCost', 'What\'ll it cost me?', 'What costs, what doesn\'t.', '<p ><strong >Back-End</strong> is an Open Source application made available under the General Public License.&nbsp; That means you do not pay a licensing fee to download, use or adapt the software.&nbsp; This can represent a substantial savings for your organisation.</p>\r\n<p ><strong >Back-End</strong> is written in php, an easily used programming language that will run on any platform.&nbsp; To run Back-End,&nbsp;you will need to have a web server with php enabled, and mySQL installed.&nbsp; You can download&nbsp;php from <a href="http://www.php.net/"><strong >php.net</strong></a>, and&nbsp; mySQL from <a href="http://www.mysql.com/"><strong >mySQL.com</strong></a>, both for free.</p>\r\n<p >That said, it will still cost you something to use <strong >Back-End</strong>.&nbsp; How much depends on a number of factors:</p>\r\n<p >1.&nbsp; You will have to have a web site or network.&nbsp; <strong >Back-End</strong> can help you create the site or service you want, but you still have to buy the hardware or contract with an ISP for a home for that site.</p>\r\n<p >2.&nbsp; If you need technical assistance to install, configure or the web software and&nbsp; <strong >Back-End</strong>, you may have to pay someone for those services.&nbsp; If you, or a volunteer, have the ability to take care of it, those costs can be small -- if you hire a large consultancy to help you, they will be substantially higher.</p>\r\n<p >3.&nbsp; If you want to add&nbsp; capabilities to <strong >Back-End</strong> beyond those it already has, you will have to pay a programmer or programmers.&nbsp; This cost will vary depending on the complexity of the programming involved and the type of programmer you hire.&nbsp; Many of <strong >Back-End</strong>\'s core developers are available to do additional programming.&nbsp; You can contact them <strong ><a href="mailto:back-end@openconcept.ca">by email</a>.</strong></p>\r\n<p >4.&nbsp; If you want a high-concept site design, and you are not a designer, you will still have to hire someone to design your site.</p>\r\n<p >The cost of using <strong >Back-End</strong> is not in the software, but in the type of site you want to have, the level of skill you bring to the task, and the people you have helping you.&nbsp; Every penny you spend will further the goals of your organisation instead of those of a multinational corporation.</p>\r\n<p >You will be saving more than money.</p>', '<p><strong>Back-End</strong> is an Open Source application made available under the General Public License.&nbsp; That means you do not pay a licensing fee to&nbsp;download, use or adapt&nbsp;the software.&nbsp; This can represent a substantial savings for your organisation.</p>\r\n<p><strong>Back-End</strong> is written in php, an easily used programming language that will run on any platform.&nbsp; To run Back-End,&nbsp;you will need to have a web server with php enabled, and mySQL installed.&nbsp; You can download&nbsp;php from <A href="http://www.php.net/"><strong>php.net</strong></a>, and&nbsp; mySQL from <A href="http://www.mysql.com/"><strong>MySQL.com</strong></a>, both for free.</p>\r\n<p>That said, it will still cost you something to use <strong>Back-End</strong>.&nbsp; How much depends on a number of factors:</p>\r\n<p>1.&nbsp; You will have to have a web site or network.&nbsp; <strong>Back-End</strong> can help you create the site or service you want, but you still have to buy the hardware or contract with an ISP for a home for that site.</p>\r\n<p>2.&nbsp; If you need technical assistance to install, configure or the web software and&nbsp; <strong>Back-End</strong>, you may have to pay someone for those services.&nbsp; If you, or a volunteer, have the ability to take care of it, those costs can be small -- if you hire a large consultancy to help you, they will be substantially higher.</p>\r\n<p>3.&nbsp; If you want to add&nbsp; capabilities to <strong>Back-End</strong> beyond those it already has, you will have to pay a programmer or programmers.&nbsp; This cost will vary depending on the complexity of the programming involved and the type of programmer you hire.&nbsp; Many of <strong>Back-End</strong>\'s core developers are available to do additional programming.&nbsp; You can contact them <strong><A href="mailto:back-end@openconcept.ca">by email</a>.</strong></p>\r\n<p>4.&nbsp; If you want a high-concept site design, and you are not a designer, you will still have to hire someone to design your site.</p>\r\n<p>The cost of using <strong>Back-End</strong> is not in the software, but in the type of site you want to have, the level of skill you bring to the task, and the people you have helping you.&nbsp; Every penny you spend will further the goals of your organisation instead of those of a multinational corporation.</p>\r\n<p>You will be saving more than money.</p>', 'What costs, what doesn\'t.', 'What\'ll it cost me?', '', '', NULL, 'clients', 0, 37);
INSERT INTO be_sectionText VALUES (21, 17, 'en', 'HowDoIGetIt', 'How do I get it?', 'Links to the latest version of Back-End, and its documentation.', '<p >The latest version of <strong >Back-End</strong> is available from <a href="http://sourceforge.net/projects/back-end/"><strong >SourceForge</strong></a>.&nbsp; To install it, you simply unzip it into your web directory.</p>\r\n<p >In the <strong >Back-End</strong> root directory, there is a file called Install.&nbsp; It contains all the information you or your IT person will need to get <strong >Back-End</strong> up and running. </p>\r\n<p >Pretty easy, isn\'t it?</p>', '<p>The latest version of <strong>Back-End</strong> is available from <A href="http://sourceforge.net/projects/back-end/"><strong>SourceForge</strong></a>.&nbsp; To install it, you simply unzip it into your web directory.</p>\r\n<p>In the <strong>Back-End</strong> root directory, there is a file called Install.&nbsp; It contains all the information you or your IT person will need to get <strong>Back-End</strong> up and running. </p>\r\n<p>Pretty easy, isn\'t it?</p>', 'Links to the latest version of Back-End, and its documentation.', 'How do I get it?', '', '', NULL, 'clients', 0, 39);
INSERT INTO be_sectionText VALUES (22, 18, 'en', 'GettingHelp', 'What if something goes wrong?', '', '<p >What could go wrong?</p>\r\n<p >If for some reason, you are having issues installing&nbsp; <strong >Back-End</strong>, &nbsp;try the following:</p>\r\n<p >First, Re-read the Install file and INDEX.html to be sure that you have correctl unzipped and configured the application.</p>\r\n<p >If that doesn\'t work, navigate to&nbsp;the <strong >Back-End</strong> /public_html/test.php file to see if there are any<br />outstanding issues highlighted on that page.&nbsp; Usually a misconfiguration or file peculiarity will result in an error message appearing at the top of this page, giving informationa bout the problem.</p>\r\n<p >There are several readme files and a docs directory with information about different aspects of the program. Read a few in areas that appear to be related to the issue.</p>\r\n<p >Search the&nbsp;<a href="http://sourceforge.net/forum/forum.php?forum_id=20982"><strong >support forum</strong> </a>and <a href="http://lists.sourceforge.net/lists/listinfo/back-end-support"><strong >mailing list</strong> </a>for others with similar issues -- usually there will be a solution posted, too.</p>\r\n<p >Write to the <a href="http://lists.sourceforge.net/lists/listinfo/back-end-support"><strong >mailing list</strong>&nbsp;</a>with details of your issue.&nbsp; Please&nbsp;include information about operating system, web software and the version of the script you are running.</p>\r\n<p >If you want to purchase a support contract for your back-End installation, please <strong ><a href="mailto:back-end@openconcept.ca">contact us</a></strong>.</p>\r\n<p >It is rare that a problem will not be solved within the first two or three steps of this procedure -- most&nbsp;are issues of misconfiguration.&nbsp; However, when that rare case does occur, there are a lot of qualified people&nbsp;available to give you the help you need.<br /></p>\r\n<p >&nbsp;</p>', '<p>What could go wrong?</p>\r\n<p>If for some reason, you are having issues installing&nbsp; <strong>Back-End</strong>, &nbsp;try the following:</p>\r\n<p>First, Re-read the Install file and INDEX.html to be sure that you have correctl unzipped and configured the application.</p>\r\n<p>If that doesn\'t work, navigate to&nbsp;the <strong>Back-End</strong> /public_html/test.php file to see if there are any<br />outstanding issues highlighted on that page.&nbsp; Usually a misconfiguration or file peculiarity will result in an error message appearing at the top of this page, giving informationa bout the problem.</p>\r\n<p>There are several readme files and a docs directory with information about different aspects of the program. Read a few in areas that appear to be related to the issue.</p>\r\n<p>Search the&nbsp;<A href="http://sourceforge.net/forum/forum.php?forum_id=20982"><strong>support forum</strong> </a>and <A href="http://lists.sourceforge.net/lists/listinfo/back-end-support"><strong>mailing list</strong> </a>for others with similar issues -- usually there will be a solution posted, too.</p>\r\n<p>Write to the <A href="http://lists.sourceforge.net/lists/listinfo/back-end-support"><strong>mailing list</strong>&nbsp;</a>with details of your issue.&nbsp; Please&nbsp;include information about operating system, web software and the version of the script you are running.</p>\r\n<p>If you want to purchase a support contract for your back-End installation, please <strong><A href="mailto:back-end@openconcept.ca">contact us</a></strong>.</p>\r\n<p>It is rare that a problem will not be solved within the first two or three steps of this procedure -- most&nbsp;are issues of misconfiguration.&nbsp; However, when that rare case does occur, there are a lot of qualified people&nbsp;available to give you the help you need.<br /></p>\r\n<p>&nbsp;</p>', '', 'What if something goes wrong?', '', '', NULL, 'clients', 0, 41);
INSERT INTO be_sectionText VALUES (2, 2, 'en', 'Admin', 'Admin', '', 'Just to show admin features.', 'Just to show admin features.', '', 'Admin', '', '', NULL, '', 0, 21);
INSERT INTO be_sectionText VALUES (3, 3, 'en', 'sitemap', 'Site Map', '', 'Site Map Control', 'Site Map Control', '', 'Site Map', '', '', NULL, '', 0, 22);


INSERT INTO `be_sectionText` (`sectionTextID`, `sectionID`, `languageID`, `title`, `blurb`, `content`, `content_source`, `blurb_source`, `title_source`, `meta_keywords`, `meta_description`, `keywordObjects`, `template`, `originalText`, `commentIDtext`) VALUES (35, 2, 'fr', 'Admin', '', 'Just to show admin features.', 'Just to show admin features.', '', 'Admin', '', '', NULL, '', 0, 21);
INSERT INTO `be_sectionText` (`sectionTextID`, `sectionID`, `languageID`, `title`, `blurb`, `content`, `content_source`, `blurb_source`, `title_source`, `meta_keywords`, `meta_description`, `keywordObjects`, `template`, `originalText`, `commentIDtext`) VALUES (36, 3, 'fr', 'Site Map', '', 'Site Map Control', 'Site Map Control', '', 'Site Map', '', '', NULL, '', 0, 22);

INSERT INTO be_sectionText VALUES (5, 5, 'en', 'Features', 'Hilighted Features', 'There are lots of them.', '\r\nThere are a lot of features in the new release of Back-End.  The multilingual articles capacity is a highlight as is the inline editing.', '\r\nThere are a lot of features in the new release of Back-End.  The multilingual articles capacity is a highlight as is the inline editing.', 'There are lots of them.', 'Hilighted Features', '', '0', NULL, 'clients', 0, 0);
INSERT INTO be_sectionText VALUES (4, 4, 'en', 'Templates', 'BE5 Expands the use of phplib templates', '', 'Back-End 0.5.x is using templates more extensively than 0.4.x', '', '', '', '', '0', NULL, '', 0, 0);
INSERT INTO be_sectionText VALUES (6, 6, 'en', 'Blocks', 'phpSlash&#039;s Block Infrastructure', '', 'You can now add, delete, move the blocks on the sidebars for Back-end.  All through the web interface.', '', '', '', '', '0', NULL, '', 0, 0);
INSERT INTO be_sectionText VALUES (8, 2, 'fr', 'Admin', 'FR Hilighted Features', 'There are lots of them.', 'There are a lot of features in the new release of Back-End.  The multilingual articles capacity is a highlight as is the inline editing.', '', '', '', '', '0', NULL, '', 0, 0);
INSERT INTO be_sectionText VALUES (9, 4, 'fr', 'Templates', 'FR BE5 Expands the use of phplib templates', '', 'Back-End 0.5.x is using templates more extensively than 0.4.x', '', '', '', '', '0', NULL, '', 0, 0);
INSERT INTO be_sectionText VALUES (10, 6, 'fr', 'Blocks', 'FR phpSlash&#039;s Block Infrastructure', '', 'You can now add, delete, move the blocks on the sidebars for Back-end.  All through the web interface.', '', '', '', '', '0', NULL, '', 0, 0);
INSERT INTO be_sectionText VALUES (12, 1, 'fr', 'accueil', 'Accueil', '', 'Accueil ', 'Accueil', '', 'Accueil', '', '', NULL, 'home', 0, 21);
INSERT INTO be_sectionText VALUES (18, 14, 'en', 'WhoShould', 'Who should use one?', 'The types of organisations and individuals that need a CMS.', '<p >Almost every organisation with a web site or network&nbsp;needs a CMS.&nbsp; Individuals can also enjoy substantial benefits from a CMS.</p>\r\n<p >How do you know if you need a CMS?</p>\r\n<p ><strong >If </strong>you have a large or content-rich website or intranet, you need a CMS.</p>\r\n<p ><strong >If</strong> you would <strong >like</strong> to have a content-rich website or intranet, but haven\'t got the resources, you need a CMS.</p>\r\n<p ><strong >If </strong>your organisation depends on the timely publication of up-to-date information, you need a CMS.</p>\r\n<p ><strong >If </strong>you need to have more than one individual or section submitting content for your website or intranet, you need a CMS.</p>\r\n<p ><strong >If</strong> you serve a geographically or organisationally diverse client-base, you need a CMS.</p>\r\n<p ><strong >If</strong> you have a limited time for site&nbsp;development and maintenance, you need a CMS.</p>\r\n<p ><strong >If</strong> you have a limited budget, you need a CMS.</p>\r\n<p ><strong ></strong>&nbsp;</p>', '<p>Almost every organisation with a web site or network&nbsp;needs a CMS.&nbsp; Individuals can also enjoy substantial benefits from a CMS.</p>\r\n<p>How do you know if you need a CMS?</p>\r\n<p><strong>If </strong>you have a large or content-rich website or intranet, you need a CMS.</p>\r\n<p><strong>If</strong> you would <strong>like</strong> to have a content-rich website or intranet, but haven\'t got the resources, you need a CMS.</p>\r\n<p><strong>If </strong>your organisation depends on the timely publication of up-to-date information, you need a CMS.</p>\r\n<p><strong>If </strong>you need to have more than one individual or section submitting content for your website or intranet, you need a CMS.</p>\r\n<p><strong>If</strong> you serve a geographically or organisationally diverse client-base, you need a CMS.</p>\r\n<p><strong>If</strong> you have a limited time for site&nbsp;development and maintenance, you need a CMS.</p>\r\n<p><strong>If</strong> you have a limited budget, you need a CMS.</p>\r\n<p><strong></strong>&nbsp;</p>', 'The types of organisations and individuals that need a CMS.', 'Who should use one?', '', '', NULL, 'clients', 0, 33);
INSERT INTO be_sectionText VALUES (15, 11, 'en', 'developers', 'Developers Area', '', '<p >This section contains information and resources that are useful to people implementing and tailoring Back-End.</p><p >See the <a href="http://www.back-end.org/index.php">Client Area</a> if you want more general information on Back-End\'s features.</p>', '<p>This section contains information and resources that are useful to people implementing and tailoring Back-End.</p><p>See the <a href="http://www.back-end.org/index.php">Client Area</a> if you want more general information on Back-End\'s features.</p>', '', 'Developers Area', '', '', NULL, 'developers', 0, 27);
INSERT INTO be_sectionText VALUES (16, 12, 'en', 'clients', 'Clients Area', '', 'Clients Area', 'Clients Area', '', 'Clients Area', '', '', NULL, 'clients', 0, 29);
INSERT INTO be_sectionText VALUES (17, 13, 'en', 'WhatIs', 'What is a Content Management System?', 'An explanation in simple English.', '<p >A content management system (CMS) is an application for managing web site content.&nbsp; It allows&nbsp; you to create, modify, arrange and delete content without knowing HTML, programming or design.&nbsp; There are many CMS applications available, at all levels of cost and complexity, with similar core features: browser-based publishing, format control,&nbsp;and built-in search tools.</p>\r\n<p >Most CMS applications, have templates which define the overall appearance of the site. Templates designate specific portions of a webpage as areas where content can be placed. <b >Back-End</b> is no exception. </p>\r\n<p>A CMS&nbsp; indexes content files in a database and retrieves it for visitors according to priorities you specify -- you can determine where, when and how information will appear on your site.&nbsp; Because the information is already in the database, your visitors can search your site easily.</p>\r\n<p >Commercial CMS systems&nbsp; focus on tailored marketting tools that allow companies to adapt content and advertising using information provided by the user or gathered by the site: if you search Yahoo for "business supplies" the banners will advertise sources of office supplies, not daycare centres.&nbsp; <strong >Back-End</strong>, because of its focus on advocacy groups, supplies a suite of tools useful to them.</p>', '<p><!--StartFragment -->A content management system (CMS) is an application for managing web site content.&nbsp; It allows&nbsp; you to create, modify, arrange and delete content without knowing HTML, programming or design.&nbsp; There are many CMS applications available, at all levels of cost and complexity, with similar core features: browser-based publishing, format control,&nbsp;and built-in search tools.</p>\r\n<p>Most CMS applications, have templates which define the overall appearance of the site. Templates designate specific portions of a webpage as areas where content can be placed. <b>Back-End</b> is no exception. </p>\r\n<p> A CMS&nbsp; indexes content files in a database and retrieves it for visitors according to priorities you specify -- you can determine where, when and how information will appear on your site.&nbsp; Because the information is already in the database, your visitors can search your site easily.</p>\r\n<p>Commercial CMS systems&nbsp; focus on tailored marketting tools that allow companies to adapt content and advertising using information provided by the user or gathered by the site: if you search Yahoo for "business supplies" the banners will advertise sources of office supplies, not daycare centres.&nbsp; <strong>Back-End</strong>, because of its focus on advocacy groups, supplies a suite of tools useful to them.</p>', 'An explanation in simple English.', 'What is a Content Management System?', '', '', NULL, 'clients', 0, 31);
INSERT INTO be_sectionText VALUES (23, 19, 'en', 'SEC3f8b6fa96da88', 'Back-End supports image gallaries.', 'Back-End supports image gallaries. ', 'Back-End supports image gallaries, amongst many other things. \r\n', 'Back-End supports image gallaries, amongst many other things. \r\n', 'Back-End supports image gallaries. ', 'Back-End supports image gallaries.', '', '', NULL, '', 0, 47);
INSERT INTO be_sectionText VALUES (24, 20, 'en', 'BackEnd_Features_List', 'Back-End CMS Features Short List', 'A short features list for Back-End.', '\r\n<span style="font-family: times new roman,times,serif;">Main Features</span><br style="font-family: times new roman,times,serif;" /><ul><li>simple installation wizard</li><li>web publishing system/content management system</li><li>a truly multilingual CMS with the templates and database configuration allowing for content and interation in any number of lanugages.<br /></li><li>main features include sections; articles; links; gallery, etc.</li><li>free software/GPL (no licensing fees)<br /></li><li>multi-user, browser-based management</li><li>inline editing (edit pages as you go)<br /></li><li>human readable urls</li><li>on-the-fly generation of PDFs</li><li>upload tool to allow you to easily post pdf\'s, Word Documents, etc<br /></li><li>friendly content editing through a cross platform WYSISWYG editor, requiring no plug-ins (on supported browsers)</li><li>images can be uploaded and included in article/section text</li><li>full site search built in</li><li>flexible structuring of sections and linked articles</li><li><span style="color: rgb(0, 0, 0);">built in page caching to reduce load times</span><i><br /></i></li><li>web campaign and on-line advocacy tools</li><ul><li>ePetition and eAction (fax/email) toolkit</li><li>events calendar module</li><li>email-to-a-friend and email alerts</li></ul><li>Friendly and supportive development team<br /></li></ul><tt><br /></tt>\r\n', '\r\n<span style="font-family: times new roman,times,serif;">Main Features</span><br style="font-family: times new roman,times,serif;" /><ul style="font-family: times new roman,times,serif;"><li style="color: rgb(0, 0, 0);">simple installation wizard</li><li>web publishing system/content management system</li><li>a truly multilingual CMS with the templates and database configuration allowing for content and interation in any number of lanugages.<br /></li><li>main features include sections; articles; links; gallery, etc.</li><li>free software/GPL (no licensing fees)<br /></li><li>multi-user, browser-based management</li><li>inline editing (edit pages as you go)<br /></li><li>human readable urls</li><li style="color: rgb(0, 0, 0);">on-the-fly generation of PDFs</li><li>upload tool to allow you to easily post pdf\'s, Word Documents, etc<br /></li><li>friendly content editing through a cross platform WYSISWYG editor, requiring no plug-ins (on supported browsers)</li><li>images can be uploaded and included in article/section text</li><li>full site search built in</li><li style="color: rgb(0, 0, 0);">flexible structuring of sections and linked articles</li><li style="color: rgb(0, 0, 0);"><span style="color: rgb(0, 0, 0);">built in page caching to reduce load times</span><i><br /></i></li><li>web campaign and on-line advocacy tools</li><ul><li>ePetition and eAction (fax/email) toolkit</li><li>events calendar module</li><li>email-to-a-friend and email alerts</li></ul><li>Friendly and supportive development team<br /></li></ul><tt><br /></tt>\r\n', 'A short features list for Back-End.', 'Back-End CMS Features Short List', '', '', NULL, '', 0, 53);
INSERT INTO be_sectionText VALUES (25, 20, 'fr', 'BackEnd_Features_List', 'Back-End CMS Features List', 'A short features list for Back-End.', '\r\n<br />\r\n', '\r\n<br />\r\n', 'A short features list for Back-End.', 'Back-End CMS Features List', '', '', NULL, '', 0, 54);
INSERT INTO be_sectionText VALUES (26, 21, 'en', 'FeaturesForUsers', 'As the user sees it', 'In the end, what really matters is the user experience. Back-End provides a rich enviroment for sites of any size.', '<p >In the end, what really matters is the user experience. Back-End provides a rich enviroment for sites of any size.<br /></p><ul><li>Contents are structured using sections and articles</li><li>To ensure that the site is always relevent, articles can be time-limited </li><li>Other features include links, an image gallery and polls</li><li>Human-readable URLs</li><li>On-the-fly generation of PDFs of any page</li><li>Truly multilingual content and interaction throughout</li><li>Full site search built in</li><li>Optional comment module allows users-feedback on articles</li></ul><p >Back-end also includes web campaign and on-line advocacy tools:</p><ul><li>ePetition and eAction (fax/email) toolkit</li><li>events calendar module</li><li>email-to-a-friend and email alerts</li></ul>', '<p>In the end, what really matters is the user experience. Back-End provides a rich enviroment for sites of any size.<br /></p><ul><li>Contents are structured using sections and articles</li><li>To ensure that the site is always relevent, articles can be time-limited </li><li>Other features include links, an image gallery and polls</li><li>Human-readable URLs</li><li>On-the-fly generation of PDFs of any page</li><li>Truly multilingual content and interaction throughout</li><li>Full site search built in</li><li>Optional comment module allows users-feedback on articles</li></ul><p>Back-end also includes web campaign and on-line advocacy tools:</p><ul><li>ePetition and eAction (fax/email) toolkit</li><li>events calendar module</li><li>email-to-a-friend and email alerts</li></ul>', 'In the end, what really matters is the user experience. Back-End provides a rich enviroment for sites of any size.', 'As the user sees it', '', '', NULL, '', 0, 56);
INSERT INTO be_sectionText VALUES (27, 22, 'en', 'FeaturesForEditors', 'Creating and managing content', 'Back-End provides all the tools you\'d expect from a content management system. Flexibility is at the core of our system.', 'Back-End provides all the tools you\'d expect from a content management system. Flexibility is at the core of our system.<br /><br /><ul><li>Online web publishing system/content management system</li><li>Multi-user, browser-based management of all features</li><li>Contents can be structured using sections and articles</li><li>Your site\'s structure can be changed at any time. It\'s just a case of re-linking sections and articles</li><li>Friendly content editing through a cross platform WYSISWYG editor, requiring no plug-ins (on supported browsers). HTML, Wiki and plain text input also supported</li><li>Optional online template editing - edit pages as you go</li><li>Upload tool to allow you to easily post pdf\'s, Word Documents, etc</li><li>Images can be uploaded and included in article and section text</li></ul>', 'Back-End provides all the tools you\'d expect from a content management system. Flexibility is at the core of our system.<br /><br /><ul><li>Online web publishing system/content management system</li><li>Multi-user, browser-based management of all features</li><li>Contents can be structured using sections and articles</li><li>Your site\'s structure can be changed at any time. It\'s just a case of re-linking sections and articles</li><li>Friendly content editing through a cross platform WYSISWYG editor, requiring no plug-ins (on supported browsers). HTML, Wiki and plain text input also supported</li><li>Optional online template editing - edit pages as you go</li><li>Upload tool to allow you to easily post pdf\'s, Word Documents, etc</li><li>Images can be uploaded and included in article and section text</li></ul>', 'Back-End provides all the tools you\'d expect from a content management system. Flexibility is at the core of our system.', 'Creating and managing content', '', '', NULL, '', 0, 58);
INSERT INTO be_sectionText VALUES (28, 23, 'en', 'FeaturesForBusiness', 'Management, Installation and Configuration', 'Find out more why Back-End could be the logical choice for your installation.', 'Back-End provides an excellent choice for your content needs<br /><br /><ul><li>Fine grained permission management</li><li>Option of LDAP/Active Directoy-based authentication</li><li>Back-End is FREE GPL software - no licence fees are payable</li><li>Runs under Linux/Unix and Windows</li><li>Friendly and supportive user community and development team</li><li>Simple installation wizard</li><li>Use of templates makes it easy to tailor Back-End\'s look and feel to your organization\'s needs</li><li>Simple configuration of templates and database configuration allowing for content and interation in any number of languages</li><li>Integrated optional page caching to reduce load times and server-load</li></ul>', 'Back-End provides an excellent choice for your content needs<br /><br /><ul><li>Fine grained permission management</li><li>Option of LDAP/Active Directoy-based authentication</li><li>Back-End is FREE GPL software - no licence fees are payable</li><li>Runs under Linux/Unix and Windows</li><li>Friendly and supportive user community and development team</li><li>Simple installation wizard</li><li>Use of templates makes it easy to tailor Back-End\'s look and feel to your organization\'s needs</li><li>Simple configuration of templates and database configuration allowing for content and interation in any number of languages</li><li>Integrated optional page caching to reduce load times and server-load</li></ul>', 'Find out more why Back-End could be the logical choice for your installation.', 'Management, Installation and Configuration', '', '', NULL, '', 0, 60);
INSERT INTO be_sectionText VALUES (29, 24, 'en', 'developerDocs', 'Developer Docs', 'Some explainations for how BE works for folks who want to develop it more.', '\r\n<ul><li>Browse our developer docs <a href="http://www.back-end.org/Developers_Guide/">online</a></li><li>Download the <a href="http://www.back-end.org/Developers_Guide.tar.gz">tarball</a></li></ul>\r\n', '\r\n<ul><li>Browse our developer docs <a href="http://www.back-end.org/Developers_Guide/">online</a></li><li>Download the <a href="http://www.back-end.org/Developers_Guide.tar.gz">tarball</a></li></ul>\r\n', 'Some explainations for how BE works for folks who want to develop it more.', 'Developer Docs', '', '', NULL, '', 0, 62);
INSERT INTO be_sectionText VALUES (30, 25, 'en', 'SEC3fbb9f6a7142b', 'Extensive multi-lingual support', 'Back-End has multi-lingual support built in, from the ground up. ', '<li> suport for an unlimited number of languages </li>\r\n  <li> every object in the system can have multiple languages associated\r\nwith it </li>\r\n  <li> designed into Back-End classes from the ground up </li>\r\n  <li> designed into the database schema to be flexible and extensible</li>\r\n', '<li> suport for an unlimited number of languages </li>\r\n  <li> every object in the system can have multiple languages associated\r\nwith it </li>\r\n  <li> designed into Back-End classes from the ground up </li>\r\n  <li> designed into the database schema to be flexible and extensible</li>\r\n', 'Back-End has multi-lingual support built in, from the ground up. ', 'Extensive multi-lingual support', '', '', NULL, '', 0, 68);
INSERT INTO be_sectionText VALUES (31, 26, 'en', 'SEC3fbba0106ddd2', 'Sub-sites', '', '<li> way to host multiple sites using a common database, site\r\nhierarchy, and content </li>\r\n  <li> intended for groups that sharing some common content</li>\r\n', '<li> way to host multiple sites using a common database, site\r\nhierarchy, and content </li>\r\n  <li> intended for groups that sharing some common content</li>\r\n', '', 'Sub-sites', '', '', NULL, '', 0, 70);
INSERT INTO be_sectionText VALUES (32, 27, 'en', 'SEC3fbba0f400006', 'Advanced Search', '', '<ul><li> search within a subsite, a single section, a branch of sections,\r\nor categories </li><li> ranking </li><li> optional integrated htdig support </li></ul>\r\n<p > </p>\r\n<h2 ><a name="Section_Hierarchy"></a></h2>\r\n', '<ul><li> search within a subsite, a single section, a branch of sections,\r\nor categories </li><li> ranking </li><li> optional integrated htdig support </li></ul>\r\n<p> </p>\r\n<h2><a name="Section_Hierarchy"></a></h2>\r\n', '', 'Advanced Search', '', '', NULL, '', 0, 72);
INSERT INTO be_sectionText VALUES (33, 28, 'en', 'SEC3fbba1d867f02', 'Section Hierarchy', 'Back-End\'s section hierarchy improves control over the site. ', '<p ><a name="Section_Hierarchy">Back-End provides sections to allow you to control and manage your site. A section has a name and content, and can be assigned a <span style="font-style: italic;">site url</span>. Each section can be the child of another section, and can act as a parent to other sections. Using section-specific controls, you can also control who sees the content of each section. <br /></a></p>\r\n', '<p><a name="Section_Hierarchy">Back-End provides sections to allow you to control and manage your site. A section has a name and content, and can be assigned a <span style="font-style: italic;">site url</span>. Each section can be the child of another section, and can act as a parent to other sections. Using section-specific controls, you can also control who sees the content of each section. <br /></a></p>\r\n', 'Back-End\'s section hierarchy improves control over the site. ', 'Section Hierarchy', '', '', NULL, '', 0, 74);
INSERT INTO be_sectionText VALUES (34, 29, 'en', 'SEC3fbba227f348a', 'Categories', '', ' Allows multiple levels of categorization for each article.\r\n', ' Allows multiple levels of categorization for each article.\r\n', '', 'Categories', '', '', NULL, '', 0, 76);


# --------------------------------------------------------


#
# Dumping data for table `be_sections`
#

# Clear previous entries
DELETE FROM `be_sections`;

INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (1, 'Home', 0, 1063782000, 1066402005, 1063782000, 0, 0, 'html', 0, 0, 0, 1, 0, 0, 0, 100, '', 1, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (2, 'Admin', 1, 1041835651, 1041835651, 1041835651, 1830775651, 0, '', 1, 0, 1, 0, 0, 0, 0, 1, '/login.php', 1, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (3, 'sitemap', 1, 1041835651, 1041835651, 1041835651, 1830775651, 0, '', 1, 0, 1, 0, 0, 0, 0, 1, '/sitemap.php', 1, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (5, 'Features', 0, 1041829200, 1065215697, 1041829200, 1830747600, 0, 'html', 0, 0, 1, 1, 0, 0, 0, 12, '', 0, 'title', 'asc', 'title', 'asc', 'title', 'asc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (4, 'Templates', 1, 1041835651, 1041835651, 1041835651, 1830775651, 0, '', 0, 1, 1, 0, 0, 0, 0, 13, '', 0, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (6, 'Blocks', 1, 1041835651, 1041835651, 1041835651, 1830775651, 0, '', 0, 0, 0, 0, 0, 0, 0, 14, '', 0, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (11, 'developers', 0, 1069056000, 1069070890, 1069056000, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 26, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (12, 'clients', 0, 1063771200, 1063832754, 1063771200, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 28, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (13, 'WhatIs', 0, 1063782000, 1065464307, 1063782000, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 30, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (14, 'WhoShould', 0, 1063857600, 1063931073, 1063857600, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 32, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (15, 'WhyBE', 0, 1063782000, 1069069857, 1063782000, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 34, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (16, 'WhatItCost', 0, 1063782000, 1065464450, 1063782000, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 36, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (17, 'HowDoIGetIt', 0, 1063857600, 1064188355, 1063857600, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 38, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (18, 'GettingHelp', 0, 1063857600, 1064189175, 1063857600, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 40, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (19, 'SEC3f8b6fa96da88', 0, 1066028400, 1066102933, 1066028400, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 46, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (20, 'BackEnd_Features_List', 0, 1067932800, 1068826137, 1067932800, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 52, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (21, 'FeaturesForUsers', 0, 1068969600, 1069069752, 1068969600, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 80, '', 55, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (22, 'FeaturesForEditors', 0, 1068969600, 1069069780, 1068969600, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 50, '', 57, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (24, 'developerDocs', 0, 1069142400, 1069173264, 1069142400, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 61, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (23, 'FeaturesForBusiness', 0, 1068969600, 1069069723, 1068969600, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 20, '', 59, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (25, 'SEC3fbb9f6a7142b', 40, 1069228800, 1069260650, 1069228800, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 67, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (26, 'SEC3fbba0106ddd2', 40, 1069228800, 1069260816, 1069228800, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 69, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (27, 'SEC3fbba0f400006', 40, 1069228800, 1069261043, 1069228800, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 71, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`, `dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`, `content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`, `showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`, `commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (28, 'SEC3fbba1d867f02', 40, 1069228800, 1069261272, 1069228800, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '', 73, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');
INSERT INTO `be_sections` (`sectionID`, `URLname`, `author_id`,
`dateCreated`, `dateModified`, `dateAvailable`, `dateRemoved`, `dateForSort`,
`content_type`, `hide`, `restrict2members`, `showSections`, `showArticles`,
`showLinkSubmit`, `pollID`, `hitCounter`, `priority`, `redirect`,
`commentID`, `orderbySections`, `orderbySectionsLogic`, `orderbyArticles`, `orderbyArticlesLogic`, `orderbyLinks`, `orderbyLinksLogic`) VALUES (29, 'SEC3fbba227f348a', 40,
1069228800, 1069261351, 1069228800, 0, 0, 'html', 0, 0, 1, 1, 1, 0, 0, 0, '',
75, 'dateCreated', 'desc', 'dateCreated', 'desc', 'dateCreated', 'desc');


# --------------------------------------------------------


#
# Dumping data for table `psl_block`
#

# Clear previous entries
DELETE FROM `psl_block`;


INSERT INTO `psl_block` VALUES (1, 12, 'Administration', 0, '', 'menu_ary=menuadmin&amp;tpl=navbarBlockh', '', 'a:4:{s:6:"column";s:6:"center";s:5:"width";s:0:"";s:8:"box_type";s:0:"";s:5:"perms";s:4:"user";}', 80, 1109856106);
INSERT INTO `psl_block` VALUES (3, 100, 'Navigation', 10000, '', '', '', 'a:11:{s:6:"column";s:4:"left";s:5:"width";s:3:"160";s:8:"box_type";s:0:"";s:5:"perms";s:0:"";s:5:"count";s:0:"";s:7:"orderby";s:8:"priority";s:7:"ascdesc";s:3:"asc";s:7:"section";s:1:"1";s:12:"showarticles";s:2:"no";s:12:"showSiblings";s:2:"no";s:8:"template";s:0:"";}', 100, 1109855722);
INSERT INTO `psl_block` VALUES (5, 3, 'Back-End newsfeeds', 10000, 'http://www.back-end.org/', 'http://www.back-end.org/backend.php', '', 'a:9:{s:6:"column";s:5:"right";s:5:"width";s:3:"150";s:8:"box_type";s:0:"";s:5:"perms";s:0:"";s:5:"count";s:0:"";s:6:"target";s:5:"_self";s:5:"title";s:0:"";s:3:"tpl";s:0:"";s:6:"errors";s:5:"debug";}', 10, 1109855440);
INSERT INTO `psl_block` VALUES (34, 109, 'Recent Signatures', 0, '', '', '', 'a:4:{s:6:"column";s:5:"right";s:5:"width";s:0:"";s:8:"box_type";s:0:"";s:10:"petitionID";s:1:"1";}', 0, NULL);
INSERT INTO `psl_block` VALUES (10, 5, 'Poll', 10000, '', '', '', 'a:6:{s:6:"column";s:5:"right";s:5:"width";s:4:"100%";s:8:"box_type";s:8:"headless";s:5:"perms";s:0:"";s:11:"question_id";s:0:"";s:11:"language_id";s:0:"";}', 40, 1109855825);
INSERT INTO `psl_block` VALUES (36, 110, 'Events', 0, '', '', '', 'a:3:{s:6:"column";s:4:"left";s:5:"width";s:0:"";s:8:"box_type";s:0:"";}', 0, NULL);
INSERT INTO `psl_block` VALUES (39, 1, 'Template Admin', 0, '', '', '', 'a:4:{s:6:"column";s:5:"right";s:5:"width";s:0:"";s:8:"box_type";s:0:"";s:5:"perms";s:8:"template";}', 0, 1109855914);
INSERT INTO `psl_block` VALUES (41, 113, 'Language', 10000, '', '', '', 'a:5:{s:6:"column";s:4:"left";s:5:"width";s:4:"100%";s:8:"box_type";s:5:"fancy";s:5:"perms";s:0:"";s:13:"template_file";s:0:"";}', 70, 1109856145);
INSERT INTO `psl_block` VALUES (44, 1, 'Validate HTML', 0, '', '', '', 'a:4:{s:6:"column";s:5:"right";s:5:"width";s:4:"100%";s:8:"box_type";s:5:"fancy";s:5:"perms";s:4:"user";}', 0, 1109855992);
INSERT INTO `psl_block` VALUES (50, 102, 'New items', 10000, '', '', '', 'a:8:{s:6:"column";s:4:"left";s:5:"width";s:0:"";s:8:"box_type";s:5:"fancy";s:5:"perms";s:0:"";s:5:"count";s:0:"";s:7:"orderby";s:13:"dateAvailable";s:7:"ascdesc";s:4:"desc";s:18:"showAllNewarticles";s:1:"0";}', 30, 1109855775);
INSERT INTO `psl_block` VALUES (51, 104, 'Related articles', 0, '', '', '', 'a:7:{s:6:"column";s:12:"centerbottom";s:5:"width";s:0:"";s:8:"box_type";s:5:"fancy";s:5:"perms";s:0:"";s:5:"count";s:0:"";s:7:"orderby";s:11:"dateCreated";s:7:"ascdesc";s:4:"desc";}', 20, 1109856161);
INSERT INTO `psl_block` VALUES (54, 103, 'Links at random', 6000, '', '', '', 'a:7:{s:6:"column";s:5:"right";s:5:"width";s:4:"100%";s:8:"box_type";s:5:"fancy";s:5:"perms";s:0:"";s:5:"count";s:0:"";s:7:"orderby";s:5:"title";s:7:"ascdesc";s:3:"asc";}', 10, 1109855606);
INSERT INTO `psl_block` VALUES (57, 1, 'Type preferences', 0, '', '', '', 'a:4:{s:6:"column";s:4:"left";s:5:"width";s:4:"100%";s:8:"box_type";s:5:"fancy";s:5:"perms";s:0:"";}', 12, 1109855944);



# --------------------------------------------------------

#
# Dumping data for table `psl_blockText`
#

# Clear previous entries
DELETE FROM `psl_blockText`;

INSERT INTO `psl_blockText` VALUES (9, 1, 'en', 'Administration', '', 'menu_ary=menuadmin&amp;tpl=navbarBlockh', '<!-- START: navbarBlock.tpl -->\n<div id="navbarblock">\n    <ul>\n      <li><a href="/login.php?logout=yes&redirect=%2Fadmin%2FblockAdmin.php%3F" title="Logout god">Logout god</a></li>      <li><a href="/profile.php" title="My Preferences">My Preferences</a></li>      <li><a href="/admin/blockAdmin.php" title="Block">Block</a></li>      <li><a href="/admin/pollAdmin.php" title="Poll">Poll</a></li>      <li><a href="/admin/authorAdmin.php" title="Users">Users</a></li>      <li><a href="/admin/infologAdmin.php" title="Logging">Logging</a></li>      <li><a href="/admin/groupAdmin.php" title="Group">Group</a></li>      <li><a href="/admin/BE_sectionAdmin.php" title="Section">Section</a></li>      <li><a href="/admin/BE_articleAdmin.php" title="Article">Article</a></li>      <li><a href="/admin/BE_linkAdmin.php" title="Links">Links</a></li>      <li><a href="/admin/BE_uploadAdmin.php" title="Upload">Upload</a></li>      <li><a href="/admin/BE_editTemplateAdmin.php" title="Templates">Templates</a></li>      <li><a href="/admin/BE_contactAdmin.php" title="Contact">Contact</a></li>      <li><a href="/admin/BE_followupAdmin.php" title="Followup">Followup</a></li>      <li><a href="/admin/BE_actionAdmin.php" title="Action">Action</a></li>      <li><a href="/admin/BE_petitionAdmin.php" title="Petitions">Petitions</a></li>      <li><a href="/admin/BE_feedbackAdmin.php" title="View Feedback">View Feedback</a></li>    </ul>\n    <div class="spacer">&nbsp;</div>\n</div>    \n<!-- END: navbarBlock.tpl -->', 1109856106);
INSERT INTO `psl_blockText` VALUES (2, 5, 'fr', 'Back-End newsfeeds', 'http://www.back-end.org/', 'http://www.back-end.org/backend.php', '    <a class="Link3" href="http://www.be.ca" target="_self"><img src="http://www.be.ca/images/BE/BE_logo-100x34.png" title="Back-End on phpSlash" alt="Back-End on phpSlash" /></a>\r\n    <i>Back-End builds on phpSlash to provide a simple and flexible CMS.</i><br />\r\n    <li><a class="Link3" target="_self" href="http://www.be.ca/index.php?article=ASiteMap">New Sitemap</a>\r\n    <br /><i>\r\n      We''ve added a site map to help your users navigate your site more easily.\r\n    </i>\r\n    <li><a class="Link3" target="_self" href="http://www.be.ca/index.php?article=Sidebar">Creating a Mozilla Sidebar with BE</a>\r\n    <br /><i>\r\n      \r\n\r\n\r\n    </i>\r\n    <li><a class="Link3" target="_self" href="http://www.be.ca/index.php?article=Comments">Threaded Comments in Articles</a>\r\n    <br /><i>\r\n      \r\n    </i>\r\n', 1109855440);
INSERT INTO `psl_blockText` VALUES (3, 3, 'fr', 'Navigation', '', '', '<!-- START OF TEMPLATED DISPLAY STORY BE_sectionLinks.tpl -->\r\n\r\n<table>\r\n<tr>\r\n   <td>\r\n    <a href="/index.php/Home"  class="TopLevelSections">FR Home</a>\r\n   </td>\r\n</tr>\r\n</table>\r\n\r\n<!-- END OF TEMPLATED DISPLAY STORY BE_sectionLinks.tpl -->\r\n\r\n\r\n', 1109855722);
INSERT INTO `psl_blockText` VALUES (77, 57, 'fr', 'Type preferences', '', '', '<ul>\r\n<li><a href="#" onclick="setActiveStyleSheet(''default''); return false;" onkeypress=="setActiveStyleSheet(''default''); return false;">default</a></li>\r\n<li><a href="#" onclick="setActiveStyleSheet(''bigger''); return false;" onkeypress=="setActiveStyleSheet(''bigger''); return false;">bigger</a></li>\r\n</ul>\r\n<noscript>Note: Enable Javascript</noscript>', 1109855944);
INSERT INTO `psl_blockText` VALUES (5, 10, 'en', 'Poll', '', '', '<!-- START: BE_pollDisplay.tpl -->\n    <form action="/poll.php">\n   <p> <img src="/images/poll_icon.gif" alt="poll icon" width="35" height="36" style="float: left" />\n        <input type="hidden" name="question_id" value="4" /> \n        <input type="hidden" name="submit" value="vote" /> </p>\n         <p>What Tools would you like to see in the next release?</p>\n   <div class="row">\n         <span class="inputrev"><input type="radio" name="answer_id" value="0" id="be_answerID0" /></span><span class="labelrev"><label for="be_answerID0">Content Approval</label></span><br />\n   </div>\n <div class="row">\n         <span class="inputrev"><input type="radio" name="answer_id" value="1" id="be_answerID1" /></span><span class="labelrev"><label for="be_answerID1">Problem Notification</label></span><br />\n  </div>\n <div class="row">\n         <span class="inputrev"><input type="radio" name="answer_id" value="2" id="be_answerID2" /></span><span class="labelrev"><label for="be_answerID2">Pluggable API</label></span><br />\n   </div>\n <div class="row">\n         <span class="inputrev"><input type="radio" name="answer_id" value="3" id="be_answerID3" /></span><span class="labelrev"><label for="be_answerID3">Content Staging</label></span><br />\n </div>\n <div class="row">\n         <span class="inputrev"><input type="radio" name="answer_id" value="4" id="be_answerID4" /></span><span class="labelrev"><label for="be_answerID4">Trash</label></span><br />\n  </div>\n <div class="row">\n         <span class="inputrev"><input type="radio" name="answer_id" value="5" id="be_answerID5" /></span><span class="labelrev"><label for="be_answerID5">Web-based Translation Management</label></span><br />\n  </div>\n <div class="row">\n         <span class="inputrev"><input type="radio" name="answer_id" value="6" id="be_answerID6" /></span><span class="labelrev"><label for="be_answerID6">Workflow Engine</label></span><br />\n </div>\n <div class="row">\n         <span class="inputrev"><input type="radio" name="answer_id" value="7" id="be_answerID7" /></span><span class="labelrev"><label for="be_answerID7">XHTML Compliant</label></span><br />\n </div>\n         <p><input type="submit" value="Vote" /> <br />[ <a href="/poll.php?submit=viewresults&amp;question_id=4">Results</a> | <a href="/poll.php">Polls</a> ] </p>\n    </form>\n\n<!-- END: BE_pollDisplay.tpl -->\n', 1109855825);
INSERT INTO `psl_blockText` VALUES (10, 5, 'en', 'Back-End newsfeeds', 'http://www.back-end.org/', 'http://www.back-end.org/backend.php', '    <a class="Link3" href="http://www.be.ca" target="_self"><img src="http://www.be.ca/images/BE/BE_logo-100x34.png" title="Back-End on phpSlash" alt="Back-End on phpSlash" /></a>\r\n    <i>Back-End builds on phpSlash to provide a simple and flexible CMS.</i><br />\r\n    <li><a class="Link3" target="_self" href="http://www.be.ca/index.php?article=ASiteMap">New Sitemap</a>\r\n    <br /><i>\r\n      We''ve added a site map to help your users navigate your site more easily.\r\n    </i>\r\n    <li><a class="Link3" target="_self" href="http://www.be.ca/index.php?article=Sidebar">Creating a Mozilla Sidebar with BE</a>\r\n    <br /><i>\r\n      \r\n\r\n\r\n    </i>\r\n    <li><a class="Link3" target="_self" href="http://www.be.ca/index.php?article=Comments">Threaded Comments in Articles</a>\r\n    <br /><i>\r\n      \r\n    </i>\r\n', 1109855440);
INSERT INTO `psl_blockText` VALUES (11, 3, 'en', 'Navigation', '', '', '<!-- START OF TEMPLATED DISPLAY STORY BE_sectionLinks.tpl -->\r\n\r\n<table>\r\n<tr>\r\n   <td>\r\n      <a href="/index.php/Home"  class="TopLevelSections">Home</a>\r\n   </td>\r\n</tr>\r\n<tr>\r\n   <td>\r\n    <a href="/index.php/developers"  class="TopLevelSections">Developers Area</a>\r\n   </td>\r\n</tr>\r\n<tr>\r\n   <td>\r\n     <a href="/index.php/clients"  class="TopLevelSections">Clients Area</a>\r\n   </td>\r\n</tr>\r\n</table>\r\n\r\n<!-- END OF TEMPLATED DISPLAY STORY BE_sectionLinks.tpl -->\r\n\r\n\r\n', 1109855722);
INSERT INTO `psl_blockText` VALUES (73, 50, 'fr', 'New items', '', '', '', 1109855774);
INSERT INTO `psl_blockText` VALUES (74, 10, 'fr', 'Poll', '', '', '', 1109855824);
INSERT INTO `psl_blockText` VALUES (75, 51, 'fr', 'Related articles', '', '', '', 1109855863);
INSERT INTO `psl_blockText` VALUES (76, 39, 'fr', 'Template Admin', '', '', '<script type="text/javascript">\r\n<!--\r\n // Code Contributed by Lasse Nielsen - http://www.infimum.dk\r\n function convert(node) {\r\n   if (!node) {node = document.body;}\r\n\r\n   switch (node.nodeType) {\r\n     case Node.COMMENT_NODE:\r\n       var text = node.nodeValue;\r\n       if (text.substr(0,7)==" START ") {\r\n         var newNode = createNewNode(text.substr(7));\r\n         node.parentNode.replaceChild(newNode,node);\r\n       }\r\n       break\r\n     case Node.ELEMENT_NODE: \r\n       for (var i=0;i<node.childNodes.length;i++) {\r\n         convert(node.childNodes[i]);\r\n       }\r\n       break;\r\n     default:\r\n       break;\r\n  }\r\n}\r\n\r\nfunction createNewNode(text) {\r\n  var a = document.createElement("a");\r\n  a.href =\r\n"{ROOTURL}/admin/BE_editTemplateAdmin.php?submit=edit&file="+escape(text);\r\n  var img = document.createElement("img");\r\n  img.src = "{IMAGEURL}/BE/buttons/templateView.gif";\r\n  a.appendChild(img);\r\n  return a;\r\n}\r\n-->\r\n</script>\r\n\r\n<input type="button" value="Convert!" onclick="convert()" />', 1109855914);
INSERT INTO `psl_blockText` VALUES (24, 34, 'en', 'Recent Signatures', '', '', '<em><li>Robert Tasher from San Ignacio  <li>Jillian Banfield from Petite Riviere  <li>Maria Lucia Cypriano from Guabiruba  <li>Amy Zeder from Keller  <li>Mary Alexander from Schenectady  <li>Alicia Butscher from Decatur  <li>bas van der pol from eindhoven de gexte  <li>Sue Willaims from Belmopan  <li>mags williams from cardiff  <li>Alice Brown from Laurens  </em>', NULL);
INSERT INTO `psl_blockText` VALUES (27, 36, 'en', 'Events', '', '', '<!-- START BE_upcomingEventsBlock.tpl -->\n\n<div class="box1"><h3>Upcoming Events</h3>\n<ul>\n{event_block}<li class="row0"><a href="/events.php?submit=addEvent&amp;calendar=default">Add an event</a></li>\n</ul>\n</div>\n\n<!-- END BE_upcomingEventsBlock.tpl -->', NULL);
INSERT INTO `psl_blockText` VALUES (30, 39, 'en', 'Template Admin', '', '', '<script type="text/javascript">\r\n<!--\r\n // Code Contributed by Lasse Nielsen - http://www.infimum.dk\r\n function convert(node) {\r\n   if (!node) {node = document.body;}\r\n\r\n   switch (node.nodeType) {\r\n     case Node.COMMENT_NODE:\r\n       var text = node.nodeValue;\r\n       if (text.substr(0,7)==" START ") {\r\n         var newNode = createNewNode(text.substr(7));\r\n         node.parentNode.replaceChild(newNode,node);\r\n       }\r\n       break\r\n     case Node.ELEMENT_NODE: \r\n       for (var i=0;i<node.childNodes.length;i++) {\r\n         convert(node.childNodes[i]);\r\n       }\r\n       break;\r\n     default:\r\n       break;\r\n  }\r\n}\r\n\r\nfunction createNewNode(text) {\r\n  var a = document.createElement("a");\r\n  a.href =\r\n"{ROOTURL}/admin/BE_editTemplateAdmin.php?submit=edit&file="+escape(text);\r\n  var img = document.createElement("img");\r\n  img.src = "{IMAGEURL}/BE/buttons/templateView.gif";\r\n  a.appendChild(img);\r\n  return a;\r\n}\r\n-->\r\n</script>\r\n\r\n<input type="button" value="Convert!" onclick="convert()" />', 1109855914);
INSERT INTO `psl_blockText` VALUES (32, 41, 'en', 'Language', '', '', '<!-- START BE_languageSwitchingBlockSelect.tpl -->\r\n\r\n<script type="text/javascript">\r\nfunction submitForm() {\r\n   window.location = document.getElementById(''languageSelect'').value;\r\n   return false;\r\n}\r\n</script>\r\n\r\n<form method="post" action="/index.php" id="languageChoice">\r\n<p><select name="language" id="languageSelect" onchange="javascript:submitForm();">\r\n   <option value="" selected="selected">English</option>\r\n   <option value="?lang=fr" >French</option>\r\n</select>\r\n</p>\r\n</form>\r\n\r\n<!-- END BE_languageSwitchingBlockSelect.tpl -->\r\n', 1109856145);
INSERT INTO `psl_blockText` VALUES (37, 44, 'en', 'Validate HTML', '', '', '<ul>\r\n<li><a href="http://validator.w3.org/check?uri={URL}" target="_blank">W3C HTML Validator</a>\r\n<li><a href="http://jigsaw.w3.org/css-validator/validator?usermedium=all&uri={URL}" target="_blank">W3C CSS Validator</a></li>\r\n<li><a href="http://htmlhelp.com/cgi-bin/validate.cgi?warnings=yes&url={URL}" target="_blank">WDG HTML Validator</a></li>\r\n<li><a href="http://valet.webthing.com/view=Asis/page/validate?parser=Any&resultsMode=traditional&parseMode=web&url={URL}" target="_blank">Webthing HTML Validator</a></li>\r\n<li><a href="http://bobby.watchfire.com/bobby/bobbyServlet?output=Submit&gl=wcag1-aaa&test=&URL={URL}" target="_blank">Bobby</a></li>\r\n<li><a href="http://valet.webthing.com/view=Asis/access/htnorm?suite=WCAG3&xslt=compact&url={URL}" target="_blank">Webthing Accessibility Valet</a></li>\r\n</ul>', 1109855992);
INSERT INTO `psl_blockText` VALUES (52, 51, 'en', 'Related articles', '', '', '', 1109856161);
INSERT INTO `psl_blockText` VALUES (58, 54, 'en', 'Links at random', '', '', '<!-- START BE_linkSidebar.tpl -->\n\n<ul>\n     <li><a href="http://www.back-end.org" class="sidebarLink" >Back-End</a> </li>\n     <li><a href="http://www.billblaikie.ca" class="sidebarLink" >Bill Blaikie, MP</a> </li>\n    <li><a href="http://www.brianmasse.ca/" class="sidebarLink" >Brian Masse, MP</a> </li>\n     <li><a href="http://www.calgaryblizzard.com/" class="sidebarLink" >Calgary Blizzard</a> </li>\n    <li><a href="http://www.cupe.ca/" class="sidebarLink" >CUPE National</a> </li>\n    <li><a href="http://cvs-demo.back-end.org/" class="sidebarLink" >Demo site</a> </li>\n    <li><a href="http://www.openconcept.ca/be_wiki/" class="sidebarLink" >Developers Wiki</a> </li>\n     <li><a href="http://ehcn.openconcept.ca/petition.php?petitionID=1" class="sidebarLink" >Ecumenical Health Care Network</a> </li>\n     <li><a href="http://www.fairvotecanada.org/" class="sidebarLink" >Fair Vote Canada</a> </li>\n     <li><a href="http://www.genderatwork.org/index.html" class="sidebarLink" >Gender At Work</a> </li>\n</ul>\n\n<p><a href="/links.php" class="sidebarLink">more...</a></p>\n\n<!-- END BE_linkSidebar.tpl -->\n', 1109855606);
INSERT INTO `psl_blockText` VALUES (50, 50, 'en', 'New items', '', '', '', 1109855775);
INSERT INTO `psl_blockText` VALUES (71, 41, 'fr', 'Langue', '', '', '', 1109856145);
INSERT INTO `psl_blockText` VALUES (72, 54, 'fr', 'Links at random', '', '', '', 1109855604);
INSERT INTO `psl_blockText` VALUES (78, 44, 'fr', 'Validate HTML', '', '', '<ul>\r\n<li><a href="http://validator.w3.org/check?uri={URL}" target="_blank">W3C HTML Validator</a>\r\n<li><a href="http://jigsaw.w3.org/css-validator/validator?usermedium=all&uri={URL}" target="_blank">W3C CSS Validator</a></li>\r\n<li><a href="http://htmlhelp.com/cgi-bin/validate.cgi?warnings=yes&url={URL}" target="_blank">WDG HTML Validator</a></li>\r\n<li><a href="http://valet.webthing.com/view=Asis/page/validate?parser=Any&resultsMode=traditional&parseMode=web&url={URL}" target="_blank">Webthing HTML Validator</a></li>\r\n<li><a href="http://bobby.watchfire.com/bobby/bobbyServlet?output=Submit&gl=wcag1-aaa&test=&URL={URL}" target="_blank">Bobby</a></li>\r\n<li><a href="http://valet.webthing.com/view=Asis/access/htnorm?suite=WCAG3&xslt=compact&url={URL}" target="_blank">Webthing Accessibility Valet</a></li>\r\n</ul>', 1109855992);
INSERT INTO `psl_blockText` VALUES (64, 57, 'en', 'Type preferences', '', '', '<ul>\r\n<li><a href="#" onclick="setActiveStyleSheet(''default''); return false;" onkeypress="setActiveStyleSheet(''default''); return false;">default</a></li>\r\n<li><a href="#" onclick="setActiveStyleSheet(''bigger''); return false;" onkeypress="setActiveStyleSheet(''bigger''); return false;">bigger</a></li>\r\n</ul>\r\n<noscript>Note: Enable Javascript</noscript>', 1109855944);
INSERT INTO `psl_blockText` VALUES (79, 1, 'fr', 'Administration', '', '', '', 1109856105);




# --------------------------------------------------------



#
# Dumping data for table `psl_block_type`
#

# Clear previous entries
DELETE FROM `psl_block_type`;

INSERT INTO `psl_block_type` (`id`, `name`) VALUES (1, 'html');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (2, 'url');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (3, 'rss');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (5, 'poll');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (6, 'query');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (9, 'quote');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (10, 'skin');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (11, 'login');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (12, 'navbar');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (100, 'BE_sectionList');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (101, 'BE_spotlightArticles');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (102, 'BE_newArticles');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (103, 'BE_randomLinks');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (104, 'BE_relatedArticles');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (105, 'BE_relatedCategories');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (106, 'BE_whatsPopular');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (107, 'BE_relatedKeywords');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (108, 'BE_action');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (111, 'BE_upcomingEvents');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (112, 'BE_recentPopular');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (113, 'BE_languageSwitching');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (114, 'BE_petitions');
INSERT INTO `psl_block_type` (`id`, `name`) VALUES (115, 'BE_petitionSigners');

#INSERT INTO psl_block_type (id, name) VALUES (105, 'BE_relatedCategories');

# --------------------------------------------------------


#
# Dumping data for table `psl_poll_answer`
#

# Clear previous entries
DELETE FROM `psl_poll_answer`;

INSERT INTO psl_poll_answer VALUES (4, '4', 'Trash', 0);
INSERT INTO psl_poll_answer VALUES (4, '1', 'Problem Notification', 0);
INSERT INTO psl_poll_answer VALUES (4, '2', 'Pluggable API', 0);
INSERT INTO psl_poll_answer VALUES (4, '3', 'Content Staging', 0);
INSERT INTO psl_poll_answer VALUES (4, '0', 'Content Approval', 0);
INSERT INTO psl_poll_answer VALUES (4, '5', 'Web-based Translation Management', 0);
INSERT INTO psl_poll_answer VALUES (4, '6', 'Workflow Engine', 0);
INSERT INTO psl_poll_answer VALUES (4, '7', 'XHTML Compliant', 0);
INSERT INTO psl_poll_answer VALUES (77, '0', 'Typo3', 0);
INSERT INTO psl_poll_answer VALUES (77, '1', 'Plone', 0);
INSERT INTO psl_poll_answer VALUES (77, '2', 'PostNuke', 0);
INSERT INTO psl_poll_answer VALUES (77, '3', 'Drupal', 0);
INSERT INTO psl_poll_answer VALUES (77, '4', 'eZ publish', 0);
INSERT INTO psl_poll_answer VALUES (77, '5', 'Geeklog', 0);
INSERT INTO psl_poll_answer VALUES (77, '6', 'Mambo Open Source', 0);
INSERT INTO psl_poll_answer VALUES (77, '7', 'phpWebSite', 0);

# --------------------------------------------------------


#
# Dumping data for table `psl_poll_question`
#

# Clear previous entries
DELETE FROM `psl_poll_question`;

INSERT INTO `psl_poll_question` VALUES (4, 'What Tools would you like to see in the next release?', 0, 1, 1, 'en', '');
INSERT INTO psl_poll_question VALUES (77, 'What other Open Source CMS Applications have you reviewed?', 0, 0, 1087865753, 'en', '');

# --------------------------------------------------------


#
# Dumping data for table `psl_poll_voter`
#

# --------------------------------------------------------

#
# Dumping data for table `db_sequence`
#

# Clear previous entries
DELETE FROM `db_sequence`;

# These are unused by BE and should be removed - mg May2005
# INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_topic_seq', 36);
# INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_topic_lut_seq', 70);
# INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_section_lut_seq', 243);
# INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_submission_seq', 11);
# INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_topic_submission_lut_seq', 21);
# INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_section_submission_lut_seq', 13);
# INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_section_seq', 9);
# INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_mailinglist_seq', 2);

INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_comment_seq', 1);
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_comment_dep_seq', 1);
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_infolog', 1);
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_glossary_seq', 1);
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('be_images', 1);
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('event_seq', 1);
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('eventText_seq', 1);

SELECT @VariableId := max(variable_id) FROM psl_variable;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_variable_seq', (@VariableId+1));
SELECT @BlockId := max(id) FROM psl_block;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_block_seq', (@BlockId+1));
SELECT @BlockTextId := max(textID) FROM psl_blockText;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_blockText_seq', (@BlockTextId+1));
SELECT @BlocktypeId := max(id) FROM psl_block_type;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_blocktype_seq', (@BlocktypeId+1));
SELECT @AuthorId := max(author_id) FROM psl_author;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_author_seq', (@AuthorId+1));
SELECT @SectionBlockLutId := max(lut_id) FROM psl_section_block_lut;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_section_block_lut_seq', (@SectionBlockLutId+1));
SELECT @PermissionId := max(permission_id) FROM psl_permission;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_permission_seq', (@PermissionId+1));
SELECT @GroupId := max(group_id) FROM psl_group;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_group_seq', (@GroupId+1));
SELECT @GroupSectionLutId := max(lut_id) FROM psl_group_section_lut;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_group_section_lut_seq', (@GroupSectionLutId+1));
SELECT @GroupPermissionLutId := max(lut_id) FROM psl_group_permission_lut;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_group_permission_lut_seq', (@GroupPermissionLutId+1));
SELECT @GroupGroupLutId := max(lut_id) FROM psl_group_group_lut;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_group_group_lut_seq', (@GroupGroupLutId+1));
SELECT @AuthorGroupId := max(lut_id) FROM psl_author_group_lut;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('psl_author_group_lut_seq', (@AuthorGroupId+1));
SELECT @SectionId := max(sectionID) FROM be_sections;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('be_sections', (@SectionId+1));
SELECT @ArticleId := max(articleID) FROM be_articles;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('be_articles', (@ArticleId+1));
SELECT @LinkId := max(linkID) FROM be_link;
INSERT INTO `db_sequence` (`seq_name`, `nextid`) VALUES ('be_link', (@LinkId+1));


# --------------------------------------------------------

#
# Dumping data for table `psl_commentcount`
#

# Clear previous entries
DELETE FROM `psl_commentcount`;

INSERT INTO psl_commentcount VALUES (4, 0);
INSERT INTO psl_commentcount VALUES (77, 0);

# --------------------------------------------------------

#
# Dumping data for table `psl_section_block_lut`
#


# Clear previous entries
DELETE FROM `psl_section_block_lut`;

INSERT INTO `psl_section_block_lut` VALUES (579, 151, 11);
INSERT INTO `psl_section_block_lut` VALUES (413, 152, 10);
INSERT INTO `psl_section_block_lut` VALUES (447, 0, 15);
INSERT INTO `psl_section_block_lut` VALUES (446, 0, 14);
INSERT INTO `psl_section_block_lut` VALUES (445, 0, 13);
INSERT INTO `psl_section_block_lut` VALUES (980, 10, 11);
INSERT INTO `psl_section_block_lut` VALUES (402, 0, 9);
INSERT INTO `psl_section_block_lut` VALUES (440, 36, 10);
INSERT INTO `psl_section_block_lut` VALUES (450, 0, 18);
INSERT INTO `psl_section_block_lut` VALUES (449, 0, 17);
INSERT INTO `psl_section_block_lut` VALUES (448, 0, 16);
INSERT INTO `psl_section_block_lut` VALUES (979, 10, 1);
INSERT INTO `psl_section_block_lut` VALUES (963, 3, 20);
INSERT INTO `psl_section_block_lut` VALUES (403, 0, 10);
INSERT INTO `psl_section_block_lut` VALUES (415, 35, 10);
INSERT INTO `psl_section_block_lut` VALUES (441, 36, 11);
INSERT INTO `psl_section_block_lut` VALUES (1072, 1, 20);
INSERT INTO `psl_section_block_lut` VALUES (429, 0, 12);
INSERT INTO `psl_section_block_lut` VALUES (428, 0, 11);
INSERT INTO `psl_section_block_lut` VALUES (418, 34, 10);
INSERT INTO `psl_section_block_lut` VALUES (537, 0, 19);
INSERT INTO `psl_section_block_lut` VALUES (573, 0, 20);
INSERT INTO `psl_section_block_lut` VALUES (574, 0, 21);
INSERT INTO `psl_section_block_lut` VALUES (575, 0, 22);
INSERT INTO `psl_section_block_lut` VALUES (576, 0, 23);
INSERT INTO `psl_section_block_lut` VALUES (580, 0, 24);
INSERT INTO `psl_section_block_lut` VALUES (581, 0, 25);
INSERT INTO `psl_section_block_lut` VALUES (582, 0, 26);
INSERT INTO `psl_section_block_lut` VALUES (583, 0, 27);
INSERT INTO `psl_section_block_lut` VALUES (584, 0, 28);
INSERT INTO `psl_section_block_lut` VALUES (585, 0, 29);
INSERT INTO `psl_section_block_lut` VALUES (1071, 1, 18);
INSERT INTO `psl_section_block_lut` VALUES (1070, 1, 17);
INSERT INTO `psl_section_block_lut` VALUES (1069, 1, 16);
INSERT INTO `psl_section_block_lut` VALUES (1068, 1, 23);
INSERT INTO `psl_section_block_lut` VALUES (1067, 1, 22);
INSERT INTO `psl_section_block_lut` VALUES (1066, 1, 21);
INSERT INTO `psl_section_block_lut` VALUES (1065, 1, 15);
INSERT INTO `psl_section_block_lut` VALUES (1064, 1, 14);
INSERT INTO `psl_section_block_lut` VALUES (1063, 1, 13);
INSERT INTO `psl_section_block_lut` VALUES (1062, 1, 12);
INSERT INTO `psl_section_block_lut` VALUES (1061, 1, 24);
INSERT INTO `psl_section_block_lut` VALUES (1060, 1, 11);
INSERT INTO `psl_section_block_lut` VALUES (1059, 1, 3);
INSERT INTO `psl_section_block_lut` VALUES (1058, 1, 2);
INSERT INTO `psl_section_block_lut` VALUES (1057, 1, 1);
INSERT INTO `psl_section_block_lut` VALUES (1048, 44, 14);
INSERT INTO `psl_section_block_lut` VALUES (1047, 44, 13);
INSERT INTO `psl_section_block_lut` VALUES (1046, 44, 12);
INSERT INTO `psl_section_block_lut` VALUES (1045, 44, 24);
INSERT INTO `psl_section_block_lut` VALUES (1044, 44, 11);
INSERT INTO `psl_section_block_lut` VALUES (1043, 44, 3);
INSERT INTO `psl_section_block_lut` VALUES (1042, 44, 2);
INSERT INTO `psl_section_block_lut` VALUES (1041, 44, 1);
INSERT INTO `psl_section_block_lut` VALUES (1040, 57, 20);
INSERT INTO `psl_section_block_lut` VALUES (1024, 39, 20);
INSERT INTO `psl_section_block_lut` VALUES (1023, 39, 18);
INSERT INTO `psl_section_block_lut` VALUES (1022, 39, 17);
INSERT INTO `psl_section_block_lut` VALUES (1021, 39, 16);
INSERT INTO `psl_section_block_lut` VALUES (1020, 39, 23);
INSERT INTO `psl_section_block_lut` VALUES (1019, 39, 22);
INSERT INTO `psl_section_block_lut` VALUES (1018, 39, 21);
INSERT INTO `psl_section_block_lut` VALUES (1017, 39, 15);
INSERT INTO `psl_section_block_lut` VALUES (1016, 39, 14);
INSERT INTO `psl_section_block_lut` VALUES (1015, 39, 13);
INSERT INTO `psl_section_block_lut` VALUES (1014, 39, 12);
INSERT INTO `psl_section_block_lut` VALUES (1013, 39, 24);
INSERT INTO `psl_section_block_lut` VALUES (1012, 39, 11);
INSERT INTO `psl_section_block_lut` VALUES (1011, 39, 3);
INSERT INTO `psl_section_block_lut` VALUES (1010, 39, 2);
INSERT INTO `psl_section_block_lut` VALUES (1009, 39, 1);
INSERT INTO `psl_section_block_lut` VALUES (962, 3, 18);
INSERT INTO `psl_section_block_lut` VALUES (961, 3, 17);
INSERT INTO `psl_section_block_lut` VALUES (960, 3, 16);
INSERT INTO `psl_section_block_lut` VALUES (959, 3, 23);
INSERT INTO `psl_section_block_lut` VALUES (958, 3, 22);
INSERT INTO `psl_section_block_lut` VALUES (957, 3, 21);
INSERT INTO `psl_section_block_lut` VALUES (956, 3, 15);
INSERT INTO `psl_section_block_lut` VALUES (955, 3, 14);
INSERT INTO `psl_section_block_lut` VALUES (1088, 41, 20);
INSERT INTO `psl_section_block_lut` VALUES (1087, 41, 18);
INSERT INTO `psl_section_block_lut` VALUES (1086, 41, 17);
INSERT INTO `psl_section_block_lut` VALUES (1085, 41, 16);
INSERT INTO `psl_section_block_lut` VALUES (1084, 41, 23);
INSERT INTO `psl_section_block_lut` VALUES (1083, 41, 22);
INSERT INTO `psl_section_block_lut` VALUES (1082, 41, 21);
INSERT INTO `psl_section_block_lut` VALUES (1081, 41, 15);
INSERT INTO `psl_section_block_lut` VALUES (1080, 41, 14);
INSERT INTO `psl_section_block_lut` VALUES (1079, 41, 13);
INSERT INTO `psl_section_block_lut` VALUES (1078, 41, 12);
INSERT INTO `psl_section_block_lut` VALUES (1077, 41, 24);
INSERT INTO `psl_section_block_lut` VALUES (1076, 41, 11);
INSERT INTO `psl_section_block_lut` VALUES (1075, 41, 3);
INSERT INTO `psl_section_block_lut` VALUES (1074, 41, 2);
INSERT INTO `psl_section_block_lut` VALUES (1073, 41, 1);
INSERT INTO `psl_section_block_lut` VALUES (775, 45, 30);
INSERT INTO `psl_section_block_lut` VALUES (774, 45, 23);
INSERT INTO `psl_section_block_lut` VALUES (773, 45, 22);
INSERT INTO `psl_section_block_lut` VALUES (772, 45, 21);
INSERT INTO `psl_section_block_lut` VALUES (771, 45, 15);
INSERT INTO `psl_section_block_lut` VALUES (770, 45, 14);
INSERT INTO `psl_section_block_lut` VALUES (769, 45, 13);
INSERT INTO `psl_section_block_lut` VALUES (768, 45, 12);
INSERT INTO `psl_section_block_lut` VALUES (767, 45, 24);
INSERT INTO `psl_section_block_lut` VALUES (766, 45, 11);
INSERT INTO `psl_section_block_lut` VALUES (765, 45, 3);
INSERT INTO `psl_section_block_lut` VALUES (764, 45, 2);
INSERT INTO `psl_section_block_lut` VALUES (763, 45, 1);
INSERT INTO `psl_section_block_lut` VALUES (738, 46, 15);
INSERT INTO `psl_section_block_lut` VALUES (739, 46, 21);
INSERT INTO `psl_section_block_lut` VALUES (740, 46, 22);
INSERT INTO `psl_section_block_lut` VALUES (741, 46, 23);
INSERT INTO `psl_section_block_lut` VALUES (742, 46, 16);
INSERT INTO `psl_section_block_lut` VALUES (743, 46, 17);
INSERT INTO `psl_section_block_lut` VALUES (744, 46, 18);
INSERT INTO `psl_section_block_lut` VALUES (745, 46, 20);
INSERT INTO `psl_section_block_lut` VALUES (954, 3, 13);
INSERT INTO `psl_section_block_lut` VALUES (953, 3, 12);
INSERT INTO `psl_section_block_lut` VALUES (931, 54, 20);
INSERT INTO `psl_section_block_lut` VALUES (930, 54, 18);
INSERT INTO `psl_section_block_lut` VALUES (929, 54, 17);
INSERT INTO `psl_section_block_lut` VALUES (928, 54, 16);
INSERT INTO `psl_section_block_lut` VALUES (927, 54, 23);
INSERT INTO `psl_section_block_lut` VALUES (926, 54, 22);
INSERT INTO `psl_section_block_lut` VALUES (925, 54, 21);
INSERT INTO `psl_section_block_lut` VALUES (924, 54, 15);
INSERT INTO `psl_section_block_lut` VALUES (923, 54, 14);
INSERT INTO `psl_section_block_lut` VALUES (922, 54, 13);
INSERT INTO `psl_section_block_lut` VALUES (921, 54, 12);
INSERT INTO `psl_section_block_lut` VALUES (920, 54, 24);
INSERT INTO `psl_section_block_lut` VALUES (919, 54, 11);
INSERT INTO `psl_section_block_lut` VALUES (918, 54, 3);
INSERT INTO `psl_section_block_lut` VALUES (917, 54, 1);
INSERT INTO `psl_section_block_lut` VALUES (776, 45, 16);
INSERT INTO `psl_section_block_lut` VALUES (777, 45, 17);
INSERT INTO `psl_section_block_lut` VALUES (778, 45, 18);
INSERT INTO `psl_section_block_lut` VALUES (779, 45, 20);
INSERT INTO `psl_section_block_lut` VALUES (1049, 44, 15);
INSERT INTO `psl_section_block_lut` VALUES (1008, 51, 20);
INSERT INTO `psl_section_block_lut` VALUES (1007, 51, 18);
INSERT INTO `psl_section_block_lut` VALUES (1006, 51, 17);
INSERT INTO `psl_section_block_lut` VALUES (1005, 51, 16);
INSERT INTO `psl_section_block_lut` VALUES (1004, 51, 23);
INSERT INTO `psl_section_block_lut` VALUES (1003, 51, 22);
INSERT INTO `psl_section_block_lut` VALUES (1002, 51, 21);
INSERT INTO `psl_section_block_lut` VALUES (1001, 51, 15);
INSERT INTO `psl_section_block_lut` VALUES (1000, 51, 14);
INSERT INTO `psl_section_block_lut` VALUES (999, 51, 13);
INSERT INTO `psl_section_block_lut` VALUES (998, 51, 12);
INSERT INTO `psl_section_block_lut` VALUES (997, 51, 24);
INSERT INTO `psl_section_block_lut` VALUES (996, 51, 11);
INSERT INTO `psl_section_block_lut` VALUES (995, 51, 3);
INSERT INTO `psl_section_block_lut` VALUES (994, 51, 2);
INSERT INTO `psl_section_block_lut` VALUES (993, 51, 1);
INSERT INTO `psl_section_block_lut` VALUES (982, 10, 12);
INSERT INTO `psl_section_block_lut` VALUES (981, 10, 24);
INSERT INTO `psl_section_block_lut` VALUES (978, 50, 20);
INSERT INTO `psl_section_block_lut` VALUES (977, 50, 18);
INSERT INTO `psl_section_block_lut` VALUES (976, 50, 17);
INSERT INTO `psl_section_block_lut` VALUES (975, 50, 16);
INSERT INTO `psl_section_block_lut` VALUES (974, 50, 23);
INSERT INTO `psl_section_block_lut` VALUES (973, 50, 22);
INSERT INTO `psl_section_block_lut` VALUES (972, 50, 21);
INSERT INTO `psl_section_block_lut` VALUES (971, 50, 15);
INSERT INTO `psl_section_block_lut` VALUES (970, 50, 14);
INSERT INTO `psl_section_block_lut` VALUES (969, 50, 13);
INSERT INTO `psl_section_block_lut` VALUES (968, 50, 12);
INSERT INTO `psl_section_block_lut` VALUES (967, 50, 24);
INSERT INTO `psl_section_block_lut` VALUES (966, 50, 11);
INSERT INTO `psl_section_block_lut` VALUES (965, 50, 3);
INSERT INTO `psl_section_block_lut` VALUES (964, 50, 1);
INSERT INTO `psl_section_block_lut` VALUES (1039, 57, 18);
INSERT INTO `psl_section_block_lut` VALUES (1038, 57, 17);
INSERT INTO `psl_section_block_lut` VALUES (1037, 57, 16);
INSERT INTO `psl_section_block_lut` VALUES (1036, 57, 23);
INSERT INTO `psl_section_block_lut` VALUES (1035, 57, 22);
INSERT INTO `psl_section_block_lut` VALUES (1034, 57, 21);
INSERT INTO `psl_section_block_lut` VALUES (1033, 57, 15);
INSERT INTO `psl_section_block_lut` VALUES (1032, 57, 14);
INSERT INTO `psl_section_block_lut` VALUES (1031, 57, 13);
INSERT INTO `psl_section_block_lut` VALUES (1030, 57, 12);
INSERT INTO `psl_section_block_lut` VALUES (1029, 57, 24);
INSERT INTO `psl_section_block_lut` VALUES (1028, 57, 11);
INSERT INTO `psl_section_block_lut` VALUES (1027, 57, 3);
INSERT INTO `psl_section_block_lut` VALUES (1026, 57, 2);
INSERT INTO `psl_section_block_lut` VALUES (1025, 57, 1);
INSERT INTO `psl_section_block_lut` VALUES (952, 3, 24);
INSERT INTO `psl_section_block_lut` VALUES (951, 3, 11);
INSERT INTO `psl_section_block_lut` VALUES (950, 3, 3);
INSERT INTO `psl_section_block_lut` VALUES (949, 3, 2);
INSERT INTO `psl_section_block_lut` VALUES (948, 3, 1);
INSERT INTO `psl_section_block_lut` VALUES (983, 10, 13);
INSERT INTO `psl_section_block_lut` VALUES (984, 10, 14);
INSERT INTO `psl_section_block_lut` VALUES (985, 10, 15);
INSERT INTO `psl_section_block_lut` VALUES (986, 10, 21);
INSERT INTO `psl_section_block_lut` VALUES (987, 10, 22);
INSERT INTO `psl_section_block_lut` VALUES (988, 10, 23);
INSERT INTO `psl_section_block_lut` VALUES (989, 10, 16);
INSERT INTO `psl_section_block_lut` VALUES (990, 10, 17);
INSERT INTO `psl_section_block_lut` VALUES (991, 10, 18);
INSERT INTO `psl_section_block_lut` VALUES (992, 10, 20);
INSERT INTO `psl_section_block_lut` VALUES (1050, 44, 21);
INSERT INTO `psl_section_block_lut` VALUES (1051, 44, 22);
INSERT INTO `psl_section_block_lut` VALUES (1052, 44, 23);
INSERT INTO `psl_section_block_lut` VALUES (1053, 44, 16);
INSERT INTO `psl_section_block_lut` VALUES (1054, 44, 17);
INSERT INTO `psl_section_block_lut` VALUES (1055, 44, 18);
INSERT INTO `psl_section_block_lut` VALUES (1056, 44, 20);


# --------------------------------------------------------

# Optimize Tables
OPTIMIZE TABLE
`CACHEDATA` ,
`UidNumber` ,
`active_sessions` ,
`active_sessions_split` ,
`auth_user` ,
`auth_user_md5` ,
# `be_action` ,
# `be_action2contact` ,
# `be_action2section` ,
# `be_actionText` ,
# `be_actionType` ,
`be_article2section` ,
`be_articleText` ,
`be_articles` ,
# `be_bib` ,
# `be_bib2category` ,
# `be_bib2country` ,
# `be_bib2keywords` ,
# `be_bib2profile2role` ,
# `be_bib2region` ,
# `be_bibMLA` ,
# `be_bib_category` ,
# `be_bib_country` ,
# `be_bib_language` ,
# `be_bib_region` ,
# `be_bib_types` ,
`be_categories` ,
`be_category2item` ,
# `be_contact` ,
# `be_contactType` ,
# `be_country2region` ,
`be_event` ,
`be_eventText` ,
# `be_followup` ,
# `be_followup2contact` ,
# `be_followup2group` ,
`be_image2section` ,
`be_imageText` ,
`be_images` ,
# `be_keywords` ,
`be_keyword2article` ,
`be_language` ,
`be_link` ,
`be_link2articlesGroup` ,
`be_link2articlesGroupText` ,
`be_link2section` ,
`be_linkText` ,
`be_linkTextValidation` ,
# `be_profession` ,
# `be_profile` ,
# `be_profile2category` ,
# `be_profile2country` ,
# `be_profile2keywords` ,
# `be_profile2nationality` ,
# `be_profile2profession` ,
# `be_profile2region` ,
# `be_profile2spokenLanguages` ,
# `be_profile2upload` ,
# `be_profile_keywords` ,
# `be_profile_photo` ,
# `be_profile_role` ,
# `be_publisher` ,
`be_rsstool` ,
`be_section2section` ,
`be_sectionText` ,
`be_sections` ,
`be_subsite_block_lut` ,
`be_subsite_types` ,
`be_subsites` ,
# `be_target` ,
# `be_targetType` ,
`be_upload` ,
`db_sequence` ,
# `pet_alert` ,
# `pet_country` ,
# `pet_data` ,
# `pet_letters` ,
# `pet_main` ,
# `pet_petition` ,
# `pet_petition2contact` ,
# `pet_petition2section` ,
# `pet_petitionText` ,
`psl_author` ,
`psl_author_group_lut` ,
`psl_block` ,
`psl_blockText` ,
`psl_block_type` ,
`psl_comment` ,
`psl_commentcount` ,
`psl_glossary` ,
`psl_group` ,
`psl_group_group_lut` ,
`psl_group_permission_lut` ,
`psl_group_section_lut` ,
`psl_infolog` ,
`psl_mailinglist` ,
`psl_mailinglist_frequency` ,
`psl_permission` ,
`psl_poll_answer` ,
`psl_poll_question` ,
`psl_poll_voter` ,
`psl_quote` ,
`psl_section` ,
`psl_section_block_lut` ,
`psl_section_lut` ,
`psl_section_submission_lut` ,
`psl_story` ,
`psl_submission` ,
`psl_topic` ,
`psl_topic_lut` ,
`psl_topic_submission_lut` ,
`psl_variable` ;


# CATEGORIZATION OF ARTICLES ETC




# mg - commented this out as it wasn't working in the sql

# NOTE: The category_type values are currently hard-coded in BE_config.php
#       - it is up to you to make sure the values match
# INSERT INTO be_categories (category_type, category_code, languageID, name)
# VALUES
#   ('CATDIVN', 'CA', 'en', 'National'),
#   ('CATDIVN', 'AE', 'en', 'Airline'),
#   ('CATSECT','1','en','Airline'),
#   ('CATISSUE','1','en','Aboriginal'),
