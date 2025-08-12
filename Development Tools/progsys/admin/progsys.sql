-- phpMyAdmin SQL Dump
-- version 2.6.3-pl1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 26. Oktober 2005 um 23:49
-- Server Version: 4.1.13
-- PHP-Version: 4.4.0
-- 
-- Datenbank: `progsys`
-- 

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_admins`
-- 

CREATE TABLE progsys_admins (
  usernr tinyint(3) unsigned NOT NULL auto_increment,
  username varchar(80) NOT NULL default '',
  `password` varchar(40) character set latin1 collate latin1_bin NOT NULL default '',
  email varchar(80) default NULL,
  rights int(2) unsigned NOT NULL default '0',
  lastlogin datetime NOT NULL default '0000-00-00 00:00:00',
  lockpw tinyint(1) unsigned NOT NULL default '0',
  language varchar(4) NOT NULL default '',
  realname varchar(240) NOT NULL default '',
  lockentry tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (usernr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_allowed_referers`
-- 

CREATE TABLE progsys_allowed_referers (
  entrynr int(10) unsigned NOT NULL auto_increment,
  address varchar(255) NOT NULL default '',
  PRIMARY KEY  (entrynr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_banlist`
-- 

CREATE TABLE progsys_banlist (
  bannr int(10) unsigned NOT NULL auto_increment,
  ipadr varchar(16) NOT NULL default '0.0.0.0',
  subnetmask varchar(16) NOT NULL default '0.0.0.0',
  reason text,
  PRIMARY KEY  (bannr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_bugtraq`
-- 

CREATE TABLE progsys_bugtraq (
  bugnr int(10) unsigned NOT NULL auto_increment,
  programm int(10) unsigned NOT NULL default '0',
  custname varchar(120) NOT NULL default '',
  custmail varchar(120) NOT NULL default '',
  enterdate date NOT NULL default '0000-00-00',
  processor int(10) unsigned NOT NULL default '0',
  state tinyint(1) unsigned NOT NULL default '0',
  fixversion varchar(10) NOT NULL default '',
  lastedited date NOT NULL default '0000-00-00',
  bugtext text NOT NULL,
  fixtext text NOT NULL,
  usedversion varchar(10) NOT NULL default '',
  enterip varchar(16) NOT NULL default '0.0.0.0',
  PRIMARY KEY  (bugnr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_changelog`
-- 

CREATE TABLE progsys_changelog (
  entrynr int(10) unsigned NOT NULL auto_increment,
  version varchar(20) NOT NULL default '',
  programm int(10) unsigned NOT NULL default '0',
  versiondate date NOT NULL default '0000-00-00',
  changes text NOT NULL,
  isbeta tinyint(1) unsigned NOT NULL default '0',
  nlsenddate datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (entrynr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_compr_downloads`
-- 

CREATE TABLE progsys_compr_downloads (
  `month` date NOT NULL default '0000-00-00',
  filenr int(10) unsigned NOT NULL default '0',
  raw bigint(30) unsigned NOT NULL default '0',
  uni bigint(30) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_counts`
-- 

CREATE TABLE progsys_counts (
  entrynr int(10) unsigned NOT NULL auto_increment,
  lastdownload date NOT NULL default '0000-00-00',
  PRIMARY KEY  (entrynr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_download_files`
-- 

CREATE TABLE progsys_download_files (
  filenr int(10) unsigned NOT NULL auto_increment,
  url varchar(240) NOT NULL default '',
  programm int(10) unsigned NOT NULL default '0',
  description varchar(80) NOT NULL default '',
  downloadenabled tinyint(1) unsigned NOT NULL default '1',
  mirrorserver int(10) unsigned NOT NULL default '0',
  betaversion tinyint(1) unsigned NOT NULL default '0',
  nofinfo tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (filenr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_download_ips`
-- 

CREATE TABLE progsys_download_ips (
  `day` date NOT NULL default '0000-00-00',
  filenr int(10) NOT NULL default '0',
  ipadr varchar(15) NOT NULL default '',
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_downloads`
-- 

CREATE TABLE progsys_downloads (
  `day` date NOT NULL default '0000-00-00',
  filenr int(10) NOT NULL default '0',
  raw int(11) unsigned NOT NULL default '0',
  uni int(11) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_failed_logins`
-- 

CREATE TABLE progsys_failed_logins (
  loginnr int(10) unsigned NOT NULL auto_increment,
  username varchar(250) NOT NULL default '0',
  ipadr varchar(16) NOT NULL default '',
  logindate datetime NOT NULL default '0000-00-00 00:00:00',
  usedpw varchar(240) NOT NULL default '',
  PRIMARY KEY  (loginnr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_failed_notify`
-- 

CREATE TABLE progsys_failed_notify (
  usernr int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_feature_requests`
-- 

CREATE TABLE progsys_feature_requests (
  requestnr int(10) unsigned NOT NULL auto_increment,
  email varchar(120) NOT NULL default '',
  request text NOT NULL,
  publish tinyint(1) unsigned NOT NULL default '0',
  rating int(10) unsigned NOT NULL default '0',
  ratingcount int(10) unsigned NOT NULL default '0',
  ipadr varchar(16) NOT NULL default '',
  enterdate date NOT NULL default '0000-00-00',
  programm int(10) unsigned NOT NULL default '0',
  releasestate tinyint(1) unsigned NOT NULL default '0',
  `comment` text NOT NULL,
  PRIMARY KEY  (requestnr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_forbidden_referers`
-- 

CREATE TABLE progsys_forbidden_referers (
  entrynr int(10) unsigned NOT NULL auto_increment,
  address varchar(255) NOT NULL default '',
  PRIMARY KEY  (entrynr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_freemailer`
-- 

CREATE TABLE progsys_freemailer (
  entrynr int(10) unsigned NOT NULL auto_increment,
  address varchar(100) NOT NULL default '',
  PRIMARY KEY  (entrynr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_hostcache`
-- 

CREATE TABLE progsys_hostcache (
  ipadr varchar(16) NOT NULL default '0',
  hostname varchar(240) NOT NULL default '',
  UNIQUE KEY ipadr (ipadr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_iplog`
-- 

CREATE TABLE progsys_iplog (
  lognr int(10) unsigned NOT NULL auto_increment,
  usernr int(10) unsigned NOT NULL default '0',
  logtime datetime NOT NULL default '0000-00-00 00:00:00',
  ipadr varchar(16) NOT NULL default '',
  used_lang varchar(4) NOT NULL default '',
  PRIMARY KEY  (lognr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_layout`
-- 

CREATE TABLE progsys_layout (
  layoutnr tinyint(3) unsigned NOT NULL default '0',
  headingbg varchar(8) default NULL,
  bgcolor1 varchar(8) default NULL,
  bgcolor2 varchar(8) default NULL,
  pagebg varchar(8) default NULL,
  tablewidth varchar(10) default NULL,
  fontface varchar(80) default NULL,
  fontsize1 varchar(10) default NULL,
  fontsize2 varchar(10) default NULL,
  fontsize3 varchar(10) default NULL,
  fontcolor varchar(8) default NULL,
  fontsize4 varchar(10) default NULL,
  bgcolor3 varchar(8) default NULL,
  headingfontcolor varchar(8) default NULL,
  subheadingfontcolor varchar(8) default NULL,
  linkcolor varchar(8) default NULL,
  vlinkcolor varchar(8) default NULL,
  alinkcolor varchar(8) default NULL,
  groupfontcolor varchar(8) default NULL,
  tabledescfontcolor varchar(8) default NULL,
  fontsize5 varchar(10) default NULL,
  dateformat varchar(10) default NULL,
  watchlogins tinyint(1) unsigned default '1',
  urlautoencode tinyint(1) unsigned NOT NULL default '1',
  enablespcode tinyint(1) unsigned NOT NULL default '1',
  nofreemailer tinyint(1) unsigned NOT NULL default '0',
  enablefailednotify tinyint(1) unsigned NOT NULL default '0',
  loginlimit int(5) unsigned NOT NULL default '0',
  timezone int(10) NOT NULL default '0',
  enablehostresolve tinyint(1) unsigned default '1',
  usemenubar tinyint(1) unsigned NOT NULL default '1',
  newbugnotify tinyint(1) unsigned NOT NULL default '0',
  progsysmail varchar(80) NOT NULL default '',
  mailsig varchar(240) NOT NULL default '',
  entriesperpage int(2) NOT NULL default '0',
  checkrefs tinyint(1) unsigned NOT NULL default '1',
  refchkaffects int(10) unsigned NOT NULL default '0',
  autoapprove tinyint(1) unsigned NOT NULL default '0',
  msendlimit int(10) unsigned NOT NULL default '30',
  newreqnotify tinyint(1) unsigned NOT NULL default '1',
  newrefnotify tinyint(1) unsigned NOT NULL default '1',
  emaildisplay int(10) unsigned NOT NULL default '0',
  admdelconfirm tinyint(1) unsigned NOT NULL default '0',
  homepageurl varchar(240) NOT NULL default 'http://localhost',
  homepagedesc varchar(240) NOT NULL default 'Localhost',
  topfilter tinyint(1) unsigned NOT NULL default '0',
  psysmailname varchar(80) NOT NULL default '',
  admstorefilter tinyint(1) unsigned default '0',
  automscheck tinyint(1) unsigned NOT NULL default '0',
  thumbs_maxx int(10) NOT NULL default '0',
  thumbs_maxy int(10) NOT NULL default '0',
  thumbs_numcols int(10) NOT NULL default '0',
  autogenthumbs tinyint(1) NOT NULL default '0',
  dateformatlong varchar(20) NOT NULL default '',
  PRIMARY KEY  (layoutnr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_mirrorserver`
-- 

CREATE TABLE progsys_mirrorserver (
  servernr int(10) unsigned NOT NULL auto_increment,
  servername varchar(80) NOT NULL default '',
  description varchar(255) NOT NULL default '',
  downurl varchar(255) NOT NULL default '',
  iconurl varchar(255) NOT NULL default '',
  PRIMARY KEY  (servernr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_misc`
-- 

CREATE TABLE progsys_misc (
  `shutdown` tinyint(3) unsigned NOT NULL default '0',
  shutdowntext text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_newsletter`
-- 

CREATE TABLE progsys_newsletter (
  entrynr int(10) unsigned NOT NULL auto_increment,
  email varchar(240) NOT NULL default '',
  programm int(10) unsigned NOT NULL default '0',
  subscribeid int(10) unsigned NOT NULL default '0',
  unsubscribeid int(10) unsigned NOT NULL default '0',
  confirmed tinyint(1) unsigned NOT NULL default '0',
  enterdate datetime NOT NULL default '0000-00-00 00:00:00',
  emailtype tinyint(1) unsigned NOT NULL default '0',
  listtype tinyint(1) unsigned NOT NULL default '0',
  subscribername varchar(255) NOT NULL default '',
  userip varchar(16) NOT NULL default '0.0.0.0',
  mscheck tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (entrynr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_os`
-- 

CREATE TABLE progsys_os (
  osnr int(10) unsigned NOT NULL auto_increment,
  osname varchar(180) NOT NULL default '',
  PRIMARY KEY  (osnr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_partnerclicks`
-- 

CREATE TABLE progsys_partnerclicks (
  `day` date NOT NULL default '0000-00-00',
  sitenr int(10) NOT NULL default '0',
  clicks int(11) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_partnersites`
-- 

CREATE TABLE progsys_partnersites (
  sitenr int(10) unsigned NOT NULL auto_increment,
  name varchar(80) NOT NULL default '',
  siteurl varchar(255) NOT NULL default '',
  email varchar(255) NOT NULL default '',
  emaillang varchar(4) NOT NULL default '',
  disabled tinyint(1) unsigned NOT NULL default '0',
  logourl varchar(255) NOT NULL default '',
  linktarget varchar(80) NOT NULL default '',
  PRIMARY KEY  (sitenr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_prog_os`
-- 

CREATE TABLE progsys_prog_os (
  osnr int(10) unsigned NOT NULL default '0',
  prognr int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_programm`
-- 

CREATE TABLE progsys_programm (
  prognr int(10) unsigned NOT NULL auto_increment,
  programmname varchar(80) default NULL,
  progid varchar(10) NOT NULL default '',
  language varchar(5) NOT NULL default 'de',
  stylesheet varchar(250) NOT NULL default '',
  usecustomheader tinyint(1) unsigned NOT NULL default '0',
  usecustomfooter tinyint(1) unsigned NOT NULL default '0',
  headerfile varchar(240) NOT NULL default '',
  footerfile varchar(240) NOT NULL default '',
  pageheader text NOT NULL,
  pagefooter text NOT NULL,
  enablenewsletter tinyint(1) unsigned NOT NULL default '0',
  enabletodorating tinyint(1) unsigned NOT NULL default '0',
  enablebugentries tinyint(1) unsigned NOT NULL default '0',
  maxconfirmtime int(2) unsigned NOT NULL default '0',
  newsletterfreemailer tinyint(1) unsigned NOT NULL default '1',
  newsletterremark text NOT NULL,
  enablefeaturerequests tinyint(1) unsigned NOT NULL default '0',
  featurerequestspublic tinyint(1) unsigned NOT NULL default '0',
  ratefeaturerequests tinyint(1) unsigned NOT NULL default '1',
  publishnewbugentries tinyint(1) unsigned NOT NULL default '0',
  requestratingspublic tinyint(1) unsigned NOT NULL default '0',
  hasbeta tinyint(1) unsigned NOT NULL default '0',
  emailname varchar(80) NOT NULL default '',
  downpath varchar(255) NOT NULL default '',
  betapath varchar(255) NOT NULL default '',
  disableref tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (prognr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_programm_admins`
-- 

CREATE TABLE progsys_programm_admins (
  prognr int(10) unsigned NOT NULL default '0',
  usernr int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_references`
-- 

CREATE TABLE progsys_references (
  id int(10) unsigned NOT NULL auto_increment,
  url varchar(255) NOT NULL default '',
  publish tinyint(1) unsigned NOT NULL default '0',
  contactmail varchar(250) NOT NULL default '',
  contactname varchar(250) NOT NULL default '',
  sitename varchar(250) NOT NULL default '',
  heardfrom varchar(240) NOT NULL default '',
  enter_lang varchar(4) NOT NULL default '',
  pin int(10) unsigned NOT NULL default '0',
  programm int(10) NOT NULL default '0',
  approved tinyint(1) unsigned NOT NULL default '0',
  prot varchar(6) NOT NULL default 'http',
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_screenshotdirs`
-- 

CREATE TABLE progsys_screenshotdirs (
  entrynr int(10) unsigned NOT NULL auto_increment,
  program int(10) unsigned NOT NULL default '0',
  picdir varchar(255) NOT NULL default '',
  thumbdir varchar(255) NOT NULL default '',
  addheader text,
  picurl varchar(255) NOT NULL default '',
  thumburl varchar(255) NOT NULL default '',
  PRIMARY KEY  (entrynr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_screenshots`
-- 

CREATE TABLE progsys_screenshots (
  entrynr int(10) unsigned NOT NULL auto_increment,
  dir int(10) NOT NULL default '0',
  filename varchar(255) NOT NULL default '',
  longcomment text NOT NULL,
  shortcomment varchar(255) NOT NULL default '',
  displaypos int(10) NOT NULL default '0',
  thumbnailfile varchar(255) NOT NULL default '',
  PRIMARY KEY  (entrynr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_session`
-- 

CREATE TABLE progsys_session (
  sessid int(10) unsigned NOT NULL default '0',
  usernr int(10) NOT NULL default '0',
  starttime int(10) unsigned NOT NULL default '0',
  remoteip varchar(15) NOT NULL default '',
  lastlogin datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (sessid),
  KEY sess_id (sessid),
  KEY start_time (starttime),
  KEY remote_ip (remoteip)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_texts`
-- 

CREATE TABLE progsys_texts (
  textnr int(10) unsigned NOT NULL auto_increment,
  textid varchar(20) NOT NULL default '',
  `text` text NOT NULL,
  lang varchar(4) NOT NULL default '',
  PRIMARY KEY  (textnr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_todo`
-- 

CREATE TABLE progsys_todo (
  todonr int(10) unsigned NOT NULL auto_increment,
  programm int(10) unsigned NOT NULL default '0',
  lastedited date NOT NULL default '0000-00-00',
  editor int(10) unsigned NOT NULL default '0',
  `text` text NOT NULL,
  state tinyint(1) unsigned NOT NULL default '0',
  rating int(10) unsigned NOT NULL default '0',
  ratingcount int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (todonr)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `progsys_wparts`
-- 

CREATE TABLE progsys_wparts (
  id int(10) unsigned NOT NULL auto_increment,
  wpdesc varchar(255) default NULL,
  mainttxt text,
  maint tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
