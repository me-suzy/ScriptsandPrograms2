-- phpMyAdmin SQL Dump
-- version 2.6.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 27. Februar 2005 um 20:53
-- Server Version: 4.0.23
-- PHP-Version: 4.3.10
-- 
-- Datenbank: `news`
-- 

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_announce`
-- 

DROP TABLE IF EXISTS simpnews_announce;
CREATE TABLE simpnews_announce (
  entrynr int(10) unsigned NOT NULL auto_increment,
  lang varchar(4) NOT NULL default '',
  date datetime NOT NULL default '0000-00-00 00:00:00',
  text text NOT NULL,
  heading varchar(80) NOT NULL default '',
  poster varchar(240) NOT NULL default '',
  category int(10) unsigned NOT NULL default '0',
  posterid int(10) unsigned NOT NULL default '0',
  expiredate int(14) unsigned default NULL,
  headingicon varchar(100) NOT NULL default '',
  firstdate int(14) unsigned NOT NULL default '0',
  views int(10) unsigned NOT NULL default '0',
  tickerurl varchar(240) NOT NULL default '',
  wap_nopublish tinyint(1) unsigned NOT NULL default '0',
  wap_short text NOT NULL,
  PRIMARY KEY  (entrynr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_announce_attachs`
-- 

DROP TABLE IF EXISTS simpnews_announce_attachs;
CREATE TABLE simpnews_announce_attachs (
  entrynr int(10) unsigned NOT NULL auto_increment,
  announcenr int(10) unsigned NOT NULL default '0',
  attachnr int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (entrynr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_ansearch`
-- 

DROP TABLE IF EXISTS simpnews_ansearch;
CREATE TABLE simpnews_ansearch (
  annr int(10) unsigned NOT NULL default '0',
  text text NOT NULL
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_bad_words`
-- 

DROP TABLE IF EXISTS simpnews_bad_words;
CREATE TABLE simpnews_bad_words (
  indexnr int(10) unsigned NOT NULL auto_increment,
  word varchar(100) NOT NULL default '',
  replacement varchar(100) NOT NULL default '',
  PRIMARY KEY  (indexnr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_banlist`
-- 

DROP TABLE IF EXISTS simpnews_banlist;
CREATE TABLE simpnews_banlist (
  bannr int(10) unsigned NOT NULL auto_increment,
  ipadr varchar(16) NOT NULL default '0.0.0.0',
  subnetmask varchar(16) NOT NULL default '0.0.0.0',
  reason text,
  PRIMARY KEY  (bannr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_cat_adm`
-- 

DROP TABLE IF EXISTS simpnews_cat_adm;
CREATE TABLE simpnews_cat_adm (
  catnr int(10) unsigned NOT NULL default '0',
  usernr int(10) unsigned NOT NULL default '0'
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_categories`
-- 

DROP TABLE IF EXISTS simpnews_categories;
CREATE TABLE simpnews_categories (
  catnr int(10) unsigned NOT NULL auto_increment,
  catname varchar(40) NOT NULL default '',
  hideincatlist tinyint(1) unsigned NOT NULL default '0',
  enablepropose tinyint(1) unsigned NOT NULL default '0',
  customfooter text NOT NULL,
  footeroptions tinyint(4) unsigned NOT NULL default '0',
  newsframelayout varchar(10) NOT NULL default '',
  displaypos int(10) unsigned NOT NULL default '0',
  icon varchar(100) NOT NULL default '',
  iconoptions int(10) unsigned NOT NULL default '0',
  headertext text NOT NULL,
  hideintotallist tinyint(1) unsigned NOT NULL default '0',
  excludefromnewsletter tinyint(1) unsigned NOT NULL default '0',
  nlsenddate datetime NOT NULL default '0000-00-00 00:00:00',
  isarchiv tinyint(1) unsigned NOT NULL default '0',
  ignoreonsearch tinyint(1) unsigned NOT NULL default '0',
  rss_channel_title varchar(100) NOT NULL default '',
  rss_channel_description varchar(255) NOT NULL default '',
  rss_channel_link varchar(255) NOT NULL default '',
  rss_channel_copyright varchar(100) NOT NULL default '',
  rss_channel_editor varchar(100) NOT NULL default '',
  evmarkcolor varchar(7) NOT NULL default '#555555',
  enablerating tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (catnr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_catnames`
-- 

DROP TABLE IF EXISTS simpnews_catnames;
CREATE TABLE simpnews_catnames (
  catnr int(10) unsigned NOT NULL default '0',
  lang varchar(4) NOT NULL default '',
  catname varchar(40) NOT NULL default '',
  headertext text NOT NULL,
  UNIQUE KEY catlang (catnr,lang)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_comments`
-- 

DROP TABLE IF EXISTS simpnews_comments;
CREATE TABLE simpnews_comments (
  commentnr int(10) unsigned NOT NULL auto_increment,
  poster varchar(80) NOT NULL default '',
  email varchar(80) NOT NULL default '',
  entryref int(10) unsigned NOT NULL default '0',
  comment text NOT NULL,
  enterdate datetime NOT NULL default '0000-00-00 00:00:00',
  postingid varchar(40) NOT NULL default '0',
  PRIMARY KEY  (commentnr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_data`
-- 

DROP TABLE IF EXISTS simpnews_data;
CREATE TABLE simpnews_data (
  newsnr int(10) unsigned NOT NULL auto_increment,
  lang varchar(4) NOT NULL default '',
  date datetime NOT NULL default '0000-00-00 00:00:00',
  text text NOT NULL,
  heading varchar(80) NOT NULL default '',
  poster varchar(240) NOT NULL default '',
  headingicon varchar(100) NOT NULL default '',
  category int(10) unsigned NOT NULL default '0',
  allowcomments tinyint(1) unsigned NOT NULL default '1',
  tickerurl varchar(240) NOT NULL default '',
  posterid int(10) unsigned NOT NULL default '0',
  exposter int(10) unsigned NOT NULL default '0',
  dontemail tinyint(1) unsigned NOT NULL default '0',
  linknewsnr int(10) unsigned NOT NULL default '0',
  added datetime NOT NULL default '0000-00-00 00:00:00',
  displaypos int(10) unsigned NOT NULL default '0',
  views int(10) unsigned NOT NULL default '0',
  ratings int(10) unsigned NOT NULL default '0',
  ratingcount int(10) unsigned NOT NULL default '0',
  rss_short text NOT NULL,
  rss_nopublish tinyint(1) unsigned NOT NULL default '0',
  wap_nopublish tinyint(1) unsigned NOT NULL default '0',
  dontpurge tinyint(1) unsigned default '0',
  norating tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (newsnr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_emoticons`
-- 

DROP TABLE IF EXISTS simpnews_emoticons;
CREATE TABLE simpnews_emoticons (
  iconnr int(10) unsigned NOT NULL auto_increment,
  code varchar(20) NOT NULL default '',
  emoticon_url varchar(100) NOT NULL default '',
  emotion varchar(80) NOT NULL default '',
  PRIMARY KEY  (iconnr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_events`
-- 

DROP TABLE IF EXISTS simpnews_events;
CREATE TABLE simpnews_events (
  eventnr int(10) unsigned NOT NULL auto_increment,
  date datetime NOT NULL default '0000-00-00 00:00:00',
  lang varchar(4) NOT NULL default '',
  poster varchar(240) NOT NULL default '',
  category int(10) NOT NULL default '0',
  heading varchar(80) NOT NULL default '',
  headingicon varchar(240) NOT NULL default '',
  text text NOT NULL,
  added datetime NOT NULL default '0000-00-00 00:00:00',
  posterid int(10) unsigned NOT NULL default '0',
  exposter int(10) unsigned NOT NULL default '0',
  dontemail tinyint(1) unsigned NOT NULL default '0',
  linkeventnr int(10) unsigned NOT NULL default '0',
  views int(10) unsigned NOT NULL default '0',
  wap_nopublish tinyint(1) unsigned NOT NULL default '0',
  wap_short text NOT NULL,
  PRIMARY KEY  (eventnr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_events_attachs`
-- 

DROP TABLE IF EXISTS simpnews_events_attachs;
CREATE TABLE simpnews_events_attachs (
  entrynr int(10) unsigned NOT NULL auto_increment,
  eventnr int(10) unsigned NOT NULL default '0',
  attachnr int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (entrynr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_evsearch`
-- 

DROP TABLE IF EXISTS simpnews_evsearch;
CREATE TABLE simpnews_evsearch (
  eventnr int(10) unsigned NOT NULL default '0',
  text text NOT NULL
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_failed_logins`
-- 

DROP TABLE IF EXISTS simpnews_failed_logins;
CREATE TABLE simpnews_failed_logins (
  loginnr int(10) unsigned NOT NULL auto_increment,
  username varchar(250) NOT NULL default '0',
  ipadr varchar(16) NOT NULL default '',
  logindate datetime NOT NULL default '0000-00-00 00:00:00',
  usedpw varchar(240) NOT NULL default '',
  PRIMARY KEY  (loginnr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_failed_notify`
-- 

DROP TABLE IF EXISTS simpnews_failed_notify;
CREATE TABLE simpnews_failed_notify (
  usernr int(10) unsigned NOT NULL default '0'
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_fileextensions`
-- 

DROP TABLE IF EXISTS simpnews_fileextensions;
CREATE TABLE simpnews_fileextensions (
  entrynr int(10) unsigned NOT NULL auto_increment,
  mimetype int(10) unsigned NOT NULL default '0',
  extension varchar(20) NOT NULL default '',
  PRIMARY KEY  (entrynr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_files`
-- 

DROP TABLE IF EXISTS simpnews_files;
CREATE TABLE simpnews_files (
  entrynr int(10) unsigned NOT NULL auto_increment,
  bindata longblob,
  filename varchar(240) NOT NULL default '',
  mimetype varchar(240) NOT NULL default '',
  filesize int(10) unsigned NOT NULL default '0',
  downloads int(10) unsigned NOT NULL default '0',
  fs_filename varchar(240) NOT NULL default '',
  description varchar(255) NOT NULL default '',
  PRIMARY KEY  (entrynr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_filetypedescription`
-- 

DROP TABLE IF EXISTS simpnews_filetypedescription;
CREATE TABLE simpnews_filetypedescription (
  mimetype int(10) unsigned NOT NULL default '0',
  language varchar(10) NOT NULL default '',
  description varchar(80) NOT NULL default '',
  UNIQUE KEY filetypedescription (mimetype,language)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_freemailer`
-- 

DROP TABLE IF EXISTS simpnews_freemailer;
CREATE TABLE simpnews_freemailer (
  entrynr int(10) unsigned NOT NULL auto_increment,
  address varchar(100) NOT NULL default '',
  PRIMARY KEY  (entrynr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_globalmsg`
-- 

DROP TABLE IF EXISTS simpnews_globalmsg;
CREATE TABLE simpnews_globalmsg (
  entrynr int(10) unsigned NOT NULL auto_increment,
  added datetime NOT NULL default '0000-00-00 00:00:00',
  text text NOT NULL,
  lang varchar(4) NOT NULL default '',
  heading varchar(80) NOT NULL default '',
  PRIMARY KEY  (entrynr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_hn6cats`
-- 

DROP TABLE IF EXISTS simpnews_hn6cats;
CREATE TABLE simpnews_hn6cats (
  catnr int(10) NOT NULL default '0',
  UNIQUE KEY catnr (catnr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_hostcache`
-- 

DROP TABLE IF EXISTS simpnews_hostcache;
CREATE TABLE simpnews_hostcache (
  ipadr varchar(16) NOT NULL default '0',
  hostname varchar(240) NOT NULL default '',
  UNIQUE KEY ipadr (ipadr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_icons`
-- 

DROP TABLE IF EXISTS simpnews_icons;
CREATE TABLE simpnews_icons (
  iconnr int(10) unsigned NOT NULL auto_increment,
  icon_url varchar(100) NOT NULL default '',
  PRIMARY KEY  (iconnr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_iplog`
-- 

DROP TABLE IF EXISTS simpnews_iplog;
CREATE TABLE simpnews_iplog (
  lognr int(10) unsigned NOT NULL auto_increment,
  usernr int(10) unsigned NOT NULL default '0',
  logtime datetime NOT NULL default '0000-00-00 00:00:00',
  ipadr varchar(16) NOT NULL default '',
  used_lang varchar(4) NOT NULL default '',
  PRIMARY KEY  (lognr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_layout`
-- 

DROP TABLE IF EXISTS simpnews_layout;
CREATE TABLE simpnews_layout (
  lang varchar(4) NOT NULL default '0',
  heading varchar(80) NOT NULL default '',
  headingbgcolor varchar(7) NOT NULL default '',
  headingfontcolor varchar(7) NOT NULL default '',
  headingfont varchar(240) NOT NULL default '',
  headingfontsize varchar(4) NOT NULL default '',
  bordercolor varchar(7) NOT NULL default '',
  contentbgcolor varchar(7) NOT NULL default '',
  contentfontcolor varchar(7) NOT NULL default '',
  contentfont varchar(240) NOT NULL default '',
  contentfontsize varchar(4) NOT NULL default '',
  TableWidth varchar(10) NOT NULL default '',
  timestampfontcolor varchar(7) NOT NULL default '',
  timestampfontsize varchar(4) NOT NULL default '',
  timestampfont varchar(240) NOT NULL default '',
  dateformat varchar(20) NOT NULL default '',
  showcurrtime tinyint(1) unsigned NOT NULL default '0',
  customheader text NOT NULL,
  pagebgcolor varchar(7) NOT NULL default '',
  stylesheet varchar(80) NOT NULL default '',
  newsheadingbgcolor varchar(7) NOT NULL default '',
  newsheadingfontcolor varchar(7) NOT NULL default '',
  newsheadingstyle tinyint(1) NOT NULL default '0',
  posterbgcolor varchar(7) NOT NULL default '',
  posterfontcolor varchar(7) NOT NULL default '',
  posterstyle tinyint(1) unsigned NOT NULL default '0',
  displayposter tinyint(1) unsigned NOT NULL default '0',
  posterfont varchar(240) NOT NULL default '',
  posterfontsize varchar(4) NOT NULL default '',
  newsheadingfont varchar(240) NOT NULL default '',
  newsheadingfontsize varchar(4) NOT NULL default '',
  timestampbgcolor varchar(7) NOT NULL default '',
  timestampstyle tinyint(1) unsigned NOT NULL default '0',
  displaysubscriptionbox tinyint(1) unsigned NOT NULL default '0',
  defsignature varchar(240) NOT NULL default '',
  subscriptionbgcolor varchar(7) NOT NULL default '',
  subscriptionfontcolor varchar(7) NOT NULL default '',
  subscriptionfont varchar(240) NOT NULL default '',
  subscriptionfontsize varchar(4) NOT NULL default '',
  copyrightbgcolor varchar(7) NOT NULL default '',
  copyrightfontcolor varchar(7) NOT NULL default '',
  copyrightfont varchar(240) NOT NULL default '',
  copyrightfontsize varchar(4) NOT NULL default '',
  emailremark text NOT NULL,
  layoutnr int(10) NOT NULL auto_increment,
  id varchar(10) NOT NULL default '',
  deflayout tinyint(1) unsigned NOT NULL default '0',
  customfooter text NOT NULL,
  searchpic varchar(240) NOT NULL default 'search.gif',
  backpic varchar(240) NOT NULL default 'back.gif',
  pagepic_back varchar(240) NOT NULL default 'prev.gif',
  pagepic_first varchar(240) NOT NULL default 'first.gif',
  pagepic_next varchar(240) NOT NULL default 'next.gif',
  pagepic_last varchar(240) NOT NULL default 'last.gif',
  pagetoppic varchar(240) NOT NULL default 'pagetop.gif',
  newssignal_on varchar(240) NOT NULL default 'blink.gif',
  newssignal_off varchar(240) NOT NULL default 'off.gif',
  helppic varchar(240) NOT NULL default 'help.gif',
  attachpic varchar(240) NOT NULL default 'attach.gif',
  prevpic varchar(240) NOT NULL default 'prev_big.gif',
  fwdpic varchar(240) NOT NULL default 'next_big.gif',
  eventheading varchar(80) NOT NULL default '',
  event_dateformat varchar(20) NOT NULL default '',
  newstickerbgcolor varchar(7) NOT NULL default '#cccccc',
  newstickerfontcolor varchar(7) NOT NULL default '#000000',
  newstickerfont varchar(240) NOT NULL default 'Verdana',
  newstickerfontsize tinyint(2) unsigned NOT NULL default '12',
  newstickerhighlightcolor varchar(7) NOT NULL default '#0000ff',
  newstickerheight int(10) unsigned NOT NULL default '20',
  newstickerwidth int(10) unsigned NOT NULL default '300',
  newstickerscrollspeed tinyint(2) unsigned NOT NULL default '1',
  newstickerscrolldelay int(10) unsigned NOT NULL default '30',
  newstickermaxdays int(10) NOT NULL default '0',
  newstickermaxentries int(10) unsigned NOT NULL default '0',
  newsscrollerbgcolor varchar(7) NOT NULL default '#cccccc',
  newsscrollerfontcolor varchar(7) NOT NULL default '#000000',
  newsscrollerfont varchar(240) NOT NULL default 'Verdana',
  newsscrollerfontsize tinyint(2) unsigned NOT NULL default '12',
  newsscrollerheight int(10) unsigned NOT NULL default '300',
  newsscrollerwidth int(10) unsigned NOT NULL default '200',
  newsscrollerscrollspeed tinyint(2) unsigned NOT NULL default '1',
  newsscrollerscrolldelay int(10) unsigned NOT NULL default '100',
  newsscrollerscrollpause int(10) unsigned NOT NULL default '2000',
  newsscrollermaxdays int(10) NOT NULL default '0',
  newsscrollermaxentries int(10) unsigned NOT NULL default '0',
  newsscrollertype tinyint(4) unsigned NOT NULL default '4',
  newsscrollerbgimage varchar(240) NOT NULL default '',
  newsscrollerfgimage varchar(240) NOT NULL default '',
  newsscrollermousestop tinyint(1) unsigned NOT NULL default '0',
  newsscrollermaxchars int(10) unsigned NOT NULL default '0',
  newstickertarget varchar(80) NOT NULL default '_self',
  newsscrollertarget varchar(80) NOT NULL default '_self',
  newsscrollerxoffset tinyint(4) unsigned NOT NULL default '0',
  newsscrolleryoffset tinyint(4) unsigned NOT NULL default '0',
  newsscrollerwordwrap tinyint(1) unsigned NOT NULL default '1',
  newsscrollerdisplaydate tinyint(1) unsigned NOT NULL default '1',
  newsscrollerdateformat varchar(20) NOT NULL default 'Y-m-d',
  newentrypic varchar(240) NOT NULL default 'new.gif',
  newstypermaxentries int(10) unsigned NOT NULL default '0',
  newstyperbgcolor varchar(7) NOT NULL default '#cccccc',
  newstyperfontcolor varchar(7) NOT NULL default '#000000',
  newstyperfont varchar(240) NOT NULL default 'Verdana',
  newstyperfontsize tinyint(2) unsigned NOT NULL default '12',
  newstyperfontstyle tinyint(2) unsigned NOT NULL default '0',
  newstyperdisplaydate tinyint(1) unsigned NOT NULL default '1',
  newstyperdateformat varchar(20) NOT NULL default 'Y-m-d',
  newstyperxoffset tinyint(4) unsigned NOT NULL default '8',
  newstyperyoffset tinyint(4) unsigned NOT NULL default '8',
  newstypermaxdays int(10) NOT NULL default '0',
  newstypermaxchars int(10) unsigned NOT NULL default '0',
  newstyperwidth int(10) unsigned NOT NULL default '200',
  newstyperheight int(10) unsigned NOT NULL default '300',
  newstyperbgimage varchar(240) NOT NULL default '',
  newstyperscroll tinyint(1) unsigned NOT NULL default '1',
  newsscrollernolinking tinyint(1) unsigned NOT NULL default '0',
  newstyper2maxentries int(10) unsigned NOT NULL default '0',
  newstyper2bgcolor varchar(7) NOT NULL default '#cccccc',
  newstyper2fontcolor varchar(7) NOT NULL default '#000000',
  newstyper2fontsize tinyint(2) unsigned NOT NULL default '12',
  newstyper2displaydate tinyint(1) unsigned NOT NULL default '1',
  newstyper2newscreen tinyint(1) unsigned NOT NULL default '1',
  newstyper2waitentry tinyint(1) unsigned NOT NULL default '0',
  newstyper2dateformat varchar(20) NOT NULL default 'Y-m-d',
  newstyper2indent tinyint(4) unsigned NOT NULL default '8',
  newstyper2linespace tinyint(4) unsigned NOT NULL default '15',
  newstyper2maxdays int(10) NOT NULL default '-1',
  newstyper2maxchars int(10) unsigned NOT NULL default '0',
  newstyper2width int(10) unsigned NOT NULL default '300',
  newstyper2height int(10) unsigned NOT NULL default '200',
  newstyper2bgimage varchar(240) NOT NULL default '',
  newstyper2sound varchar(240) NOT NULL default 'sfx/tick.au',
  newstyper2charpause int(10) unsigned NOT NULL default '50',
  newstyper2linepause int(10) unsigned NOT NULL default '500',
  newstyper2screenpause int(10) unsigned NOT NULL default '5000',
  eventscrolleractdate tinyint(1) unsigned NOT NULL default '1',
  separatebylang tinyint(1) unsigned NOT NULL default '1',
  headerfile varchar(250) NOT NULL default '',
  footerfile varchar(250) NOT NULL default '',
  headerfilepos tinyint(1) unsigned NOT NULL default '0',
  footerfilepos tinyint(1) unsigned NOT NULL default '0',
  usecustomheader tinyint(1) unsigned NOT NULL default '0',
  usecustomfooter tinyint(1) unsigned NOT NULL default '0',
  copyrightpos tinyint(1) unsigned NOT NULL default '0',
  categorybgcolor varchar(7) NOT NULL default '#999999',
  categoryfont varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',
  categoryfontsize varchar(4) NOT NULL default '3',
  categoryfontcolor varchar(7) NOT NULL default '#EEEEEE',
  categorystyle int(2) unsigned NOT NULL default '0',
  hotnews2target varchar(240) NOT NULL default '_self',
  news2target varchar(240) NOT NULL default '_self',
  newsscrollermaxlines int(2) unsigned NOT NULL default '20',
  linkcolor varchar(7) NOT NULL default '#696969',
  vlinkcolor varchar(7) NOT NULL default '#696969',
  alinkcolor varchar(7) NOT NULL default '#696969',
  morelinkcolor varchar(7) NOT NULL default '#191970',
  morevlinkcolor varchar(7) NOT NULL default '#191970',
  morealinkcolor varchar(7) NOT NULL default '#191970',
  catlinkcolor varchar(7) NOT NULL default '#F0FFFF',
  catvlinkcolor varchar(7) NOT NULL default '#F0FFFF',
  catalinkcolor varchar(7) NOT NULL default '#F0FFFF',
  commentlinkcolor varchar(7) NOT NULL default '#191970',
  commentvlinkcolor varchar(7) NOT NULL default '#191970',
  commentalinkcolor varchar(7) NOT NULL default '#191970',
  attachlinkcolor varchar(7) NOT NULL default '#CD5C5C',
  attachvlinkcolor varchar(7) NOT NULL default '#CD5C5C',
  attachalinkcolor varchar(7) NOT NULL default '#CD5C5C',
  pagenavlinkcolor varchar(7) NOT NULL default '#FFF0C0',
  pagenavvlinkcolor varchar(7) NOT NULL default '#FFF0C0',
  pagenavalinkcolor varchar(7) NOT NULL default '#FFF0C0',
  colorscrollbars tinyint(1) NOT NULL default '1',
  sbfacecolor varchar(7) NOT NULL default '#94AAD6',
  sbhighlightcolor varchar(7) NOT NULL default '#AFEEEE',
  sbshadowcolor varchar(7) NOT NULL default '#ADD8E6',
  sbdarkshadowcolor varchar(7) NOT NULL default '#4682B4',
  sb3dlightcolor varchar(7) NOT NULL default '#1E90FF',
  sbarrowcolor varchar(7) NOT NULL default '#0000ff',
  sbtrackcolor varchar(7) NOT NULL default '#E0FFFF',
  snsel_bgcolor varchar(7) NOT NULL default '#DCDCDC',
  snsel_fontcolor varchar(7) NOT NULL default '#000000',
  snsel_font varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',
  snsel_fontsize varchar(10) NOT NULL default '10pt',
  snsel_fontstyle varchar(20) NOT NULL default 'normal',
  snsel_fontweight varchar(20) NOT NULL default 'normal',
  snsel_borderstyle varchar(20) NOT NULL default 'none',
  snsel_bordercolor varchar(7) NOT NULL default '',
  snsel_borderwidth varchar(20) NOT NULL default '',
  morelinkfontsize varchar(20) NOT NULL default '8pt',
  sninput_bgcolor varchar(7) NOT NULL default '#DCDCDC',
  sninput_fontcolor varchar(7) NOT NULL default '#000000',
  sninput_font varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',
  sninput_fontsize varchar(20) NOT NULL default '10pt',
  sninput_fontstyle varchar(20) NOT NULL default 'normal',
  sninput_fontweight varchar(20) NOT NULL default 'normal',
  sninput_borderstyle varchar(20) NOT NULL default 'solid',
  sninput_borderwidth varchar(20) NOT NULL default 'thin',
  sninput_bordercolor varchar(7) NOT NULL default '#696969',
  snisb_facecolor varchar(7) NOT NULL default '#708090',
  snisb_highlightcolor varchar(7) NOT NULL default '#A9A9A9',
  snisb_shadowcolor varchar(7) NOT NULL default '#191970',
  snisb_darkshadowcolor varchar(7) NOT NULL default '#000080',
  snisb_3dlightcolor varchar(7) NOT NULL default '#F5FFFA',
  snisb_arrowcolor varchar(7) NOT NULL default '#c0c0c0',
  snisb_trackcolor varchar(7) NOT NULL default '#b0b0b0',
  snbutton_bgcolor varchar(7) NOT NULL default '#94AAD6',
  snbutton_fontcolor varchar(7) NOT NULL default '#FFFAF0',
  snbutton_font varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',
  snbutton_fontsize varchar(20) NOT NULL default '7pt',
  snbutton_fontstyle varchar(20) NOT NULL default 'normal',
  snbutton_fontweight varchar(20) NOT NULL default 'normal',
  snbutton_borderstyle varchar(20) NOT NULL default 'ridge',
  snbutton_borderwidth varchar(20) NOT NULL default 'thin',
  snbutton_bordercolor varchar(7) NOT NULL default '#483D8B',
  eventlinkcolor varchar(7) NOT NULL default '#696969',
  eventalinkcolor varchar(7) NOT NULL default '#696969',
  eventvlinkcolor varchar(7) NOT NULL default '#696969',
  eventlinkfontsize varchar(20) NOT NULL default '9pt',
  actionlinkcolor varchar(7) NOT NULL default '#F0FFFF',
  actionvlinkcolor varchar(7) NOT NULL default '#F0FFFF',
  actionalinkcolor varchar(7) NOT NULL default '#F0FFFF',
  pagebgpic varchar(240) NOT NULL default 'pagebg.gif',
  eventcalshortnews tinyint(1) unsigned NOT NULL default '0',
  eventcalshortlength int(10) unsigned NOT NULL default '20',
  eventcalshortnum int(10) unsigned NOT NULL default '3',
  eventcalshortonlyheadings tinyint(1) unsigned NOT NULL default '1',
  hotnewstarget varchar(80) NOT NULL default '',
  hotnewsdisplayposter tinyint(1) unsigned NOT NULL default '1',
  hotnewsnohtmlformatting tinyint(1) unsigned NOT NULL default '0',
  hotnewsicons tinyint(1) unsigned NOT NULL default '1',
  ns4style varchar(80) NOT NULL default 'simpnews_ns4.css',
  ns6style varchar(80) NOT NULL default 'simpnews_ns6.css',
  operastyle varchar(80) NOT NULL default 'simpnews_opera.css',
  geckostyle varchar(80) NOT NULL default 'simpnews_gecko.css',
  konquerorstyle varchar(80) NOT NULL default 'simpnews_konqueror.css',
  jsnf_maxdays tinyint(4) NOT NULL default '-1',
  jsnf_maxentries tinyint(4) unsigned NOT NULL default '0',
  jsnf_displaydate tinyint(1) unsigned NOT NULL default '1',
  jsnf_maxchars int(10) NOT NULL default '-1',
  jsnf_nolinking tinyint(1) unsigned NOT NULL default '0',
  jsnf_font varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',
  jsnf_fontsize varchar(10) NOT NULL default '2',
  jsnf_fontcolor varchar(7) NOT NULL default '#000000',
  jsnf_delay int(10) unsigned NOT NULL default '3000',
  jsnf_width int(10) unsigned NOT NULL default '150',
  jsnf_height int(10) unsigned NOT NULL default '150',
  jsnf_linktarget varchar(80) NOT NULL default '_self',
  jsnf_dateformat varchar(20) NOT NULL default 'Y-m-d',
  news4maxchars int(10) unsigned NOT NULL default '30',
  news4useddlink tinyint(1) unsigned NOT NULL default '0',
  news4linktarget varchar(240) NOT NULL default '_self',
  news4dateformat varchar(20) NOT NULL default '%B %d, %Y',
  news3dateformat varchar(20) NOT NULL default '%B %d, %Y',
  news3useddlink tinyint(1) unsigned NOT NULL default '0',
  news3linktarget varchar(240) NOT NULL default '_self',
  news3maxchars int(10) unsigned NOT NULL default '30',
  ss_font varchar(80) NOT NULL default 'Verdana',
  ss_fontsize int(10) unsigned NOT NULL default '18',
  ss_fontcolor varchar(7) NOT NULL default '#ffffff',
  ss_fontstyle tinyint(4) unsigned NOT NULL default '0',
  ss_stars tinyint(4) unsigned NOT NULL default '1',
  ss_speed tinyint(4) unsigned NOT NULL default '1',
  ss_dir tinyint(4) unsigned NOT NULL default '0',
  ss_shadow tinyint(1) unsigned NOT NULL default '1',
  ss_bgcolor varchar(7) NOT NULL default '#000000',
  ss_targetframe varchar(80) NOT NULL default '_self',
  ss_height int(10) unsigned NOT NULL default '200',
  ss_width int(10) unsigned NOT NULL default '400',
  ss_maxentries int(10) unsigned NOT NULL default '0',
  ss_maxdays int(10) NOT NULL default '-1',
  ss_nolinking tinyint(1) unsigned NOT NULL default '0',
  jsns_font varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',
  jsns_fontsize int(10) unsigned NOT NULL default '12',
  jsns_fontcolor varchar(7) NOT NULL default '#000000',
  jsns_nolinking tinyint(1) unsigned NOT NULL default '0',
  jsns_displaydate tinyint(1) unsigned NOT NULL default '1',
  jsns_linktarget varchar(80) NOT NULL default '_self',
  jsns_bgcolor varchar(7) NOT NULL default '#eeeeee',
  jsns_direction tinyint(1) unsigned NOT NULL default '0',
  jsns_maxchars int(10) NOT NULL default '-1',
  jsns_maxdays int(10) NOT NULL default '-1',
  jsns_maxentries int(10) unsigned NOT NULL default '0',
  jsns_dateformat varchar(20) NOT NULL default 'Y-m-d',
  jsns_height int(10) unsigned NOT NULL default '150',
  jsns_width int(10) unsigned NOT NULL default '150',
  jsns_speed int(10) unsigned NOT NULL default '50',
  jsns_step tinyint(4) unsigned NOT NULL default '2',
  tablealign tinyint(4) unsigned NOT NULL default '2',
  clheadingbgcolor varchar(7) NOT NULL default '#eeeeee',
  clheadingfontcolor varchar(7) NOT NULL default '#333333',
  clheadingfont varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',
  clheadingfontsize varchar(20) NOT NULL default '2',
  clwidth int(10) unsigned NOT NULL default '260',
  clcontentbgcolor varchar(7) NOT NULL default '#ffffff',
  clcontentfontcolor varchar(7) NOT NULL default '#000000',
  clcontentfont varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',
  clcontentfontsize varchar(20) NOT NULL default '1',
  enablecatlist tinyint(1) unsigned NOT NULL default '0',
  catframenewslist tinyint(4) unsigned NOT NULL default '0',
  clcontenthighlight varchar(7) NOT NULL default '#ffffff',
  clactdontlink tinyint(1) unsigned NOT NULL default '1',
  clleftwidth varchar(10) NOT NULL default '30%',
  clrightwidth varchar(10) NOT NULL default '70%',
  clnowrap tinyint(1) unsigned NOT NULL default '1',
  contentcopy varchar(250) NOT NULL default '',
  addbodytags varchar(250) NOT NULL default '',
  proposepic varchar(240) NOT NULL default 'propose.gif',
  proposereq tinyint(4) unsigned NOT NULL default '0',
  caltabpic varchar(204) NOT NULL default 'caltab.gif',
  caljumpbox tinyint(1) unsigned NOT NULL default '0',
  cjminyear int(10) unsigned NOT NULL default '2',
  cjmaxyear int(10) unsigned NOT NULL default '2',
  hotevmaxdays int(10) NOT NULL default '14',
  hotevmaxentries int(10) unsigned NOT NULL default '0',
  hotevtarget varchar(80) NOT NULL default '_self',
  displayevnum tinyint(1) unsigned NOT NULL default '0',
  hotevmaxchars int(10) NOT NULL default '0',
  hotevnohtmlformatting tinyint(1) unsigned NOT NULL default '0',
  hotevdisplayposter tinyint(1) unsigned NOT NULL default '0',
  hotevicons tinyint(1) unsigned NOT NULL default '0',
  hotscriptsnoheading tinyint(1) unsigned NOT NULL default '0',
  evproposemaxyears int(10) unsigned NOT NULL default '3',
  printpic varchar(240) NOT NULL default '',
  printheader tinyint(1) unsigned NOT NULL default '0',
  TableWidth2 varchar(10) NOT NULL default '60%',
  news5enddate date NOT NULL default '0000-00-00',
  news5monthbgcolor varchar(7) NOT NULL default '#dddddd',
  news5monthfontcolor varchar(7) NOT NULL default '#000000',
  news5monthfont varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',
  news5monthfontsize varchar(20) NOT NULL default '1',
  news5monthfontstyle tinyint(4) unsigned NOT NULL default '0',
  news5startdate date NOT NULL default '0000-00-00',
  news5yearbgcolor varchar(7) NOT NULL default '#dddddd',
  news5yearfontcolor varchar(7) NOT NULL default '#000000',
  news5yearfont varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',
  news5yearfontsize varchar(20) NOT NULL default '2',
  news5yearfontstyle tinyint(4) unsigned NOT NULL default '0',
  news5monthdisplayyear tinyint(1) unsigned NOT NULL default '0',
  news5dateformat varchar(20) NOT NULL default '%B %d, %Y',
  news5maxchars int(10) unsigned NOT NULL default '30',
  news5linktarget varchar(240) NOT NULL default '_self',
  news5useddlink tinyint(1) unsigned NOT NULL default '0',
  news5displayposter tinyint(1) unsigned NOT NULL default '0',
  news5displayicons tinyint(1) unsigned NOT NULL default '0',
  csvexportpic varchar(240) NOT NULL default 'csvexport.gif',
  csvexportdateformat varchar(20) NOT NULL default '%d.%m.%Y',
  csvexportfields int(10) unsigned NOT NULL default '0',
  asclistpic varchar(240) NOT NULL default 'asclist.gif',
  bbchelp_bgcolor varchar(7) NOT NULL default '#483D8B',
  bbchelp_fontcolor varchar(7) NOT NULL default '#ffff00',
  bbchelp_fontsize varchar(20) NOT NULL default '8pt',
  bbchelp_font varchar(240) NOT NULL default '"Courier New", Courier, monospace',
  el_font varchar(240) NOT NULL default '"Courier New", Courier, monospace',
  el_fontweight varchar(20) NOT NULL default 'normal',
  el_fontsize varchar(20) NOT NULL default '8pt',
  el_hovercolor varchar(7) NOT NULL default '#B0C4DE',
  bbchelp_fontweight varchar(20) NOT NULL default 'normal',
  bbchelp_fontstyle varchar(20) NOT NULL default 'normal',
  sb_bgcolor varchar(7) NOT NULL default '#EEE8AA',
  sb_bordercolor varchar(7) NOT NULL default '#CD853F',
  bbc_bgcolor varchar(7) NOT NULL default '#B0C4DE',
  bbc_bordercolor varchar(7) NOT NULL default '#708090',
  bbcsel_font varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',
  bbcsel_fontsize varchar(20) NOT NULL default '10pt',
  bbcsel_fontcolor varchar(7) NOT NULL default '#ff00ff',
  bbcsel_bgcolor varchar(7) NOT NULL default '#333333',
  bbcsel_borderstyle varchar(20) NOT NULL default 'none',
  bbcsel_borderwidth varchar(20) NOT NULL default '',
  bbcsel_bordercolor varchar(7) NOT NULL default '#000000',
  bbcsel_fontstyle varchar(20) NOT NULL default 'normal',
  bbcsel_fontweight varchar(20) NOT NULL default 'normal',
  or_bgcolor varchar(7) NOT NULL default '#4682B4',
  or_fontcolor varchar(7) NOT NULL default '#ffffff',
  or_font varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',
  or_fontsize varchar(20) NOT NULL default '10pt',
  sep_char char(1) NOT NULL default '§',
  el_fontstyle varchar(20) NOT NULL default 'normal',
  hn_newsheadingfontsize varchar(20) NOT NULL default '3',
  nbindent varchar(20) NOT NULL default '5pt',
  evbindent varchar(20) NOT NULL default '5pt',
  morepic varchar(240) NOT NULL default 'more.gif',
  announcepic varchar(240) NOT NULL default 'announce.gif',
  gannouncepic varchar(240) NOT NULL default 'gannounce.gif',
  announceoptions int(10) unsigned NOT NULL default '0',
  maxevcannounce tinyint(4) unsigned NOT NULL default '4',
  noprinticon tinyint(1) unsigned NOT NULL default '0',
  nogotopicon tinyint(1) unsigned NOT NULL default '0',
  news5headingdateformat varchar(20) NOT NULL default '%B %d, %Y',
  news5noglobalprint tinyint(1) unsigned NOT NULL default '0',
  news4fontsize varchar(20) NOT NULL default '1',
  news3fontsize varchar(20) NOT NULL default '1',
  n5_newsheadingfontsize varchar(20) NOT NULL default '1',
  n5_timestampfontsize varchar(20) NOT NULL default '1',
  n5_timestampstyle tinyint(1) unsigned NOT NULL default '0',
  ev2onlyheadings tinyint(1) unsigned NOT NULL default '0',
  ev2_newsheadingfontsize varchar(20) NOT NULL default '1',
  ev2_newsheadingstyle tinyint(1) unsigned NOT NULL default '0',
  ev2_timestampfontsize varchar(20) NOT NULL default '1',
  ev2_timestampstyle tinyint(1) unsigned NOT NULL default '0',
  ev2_contentfontsize varchar(20) NOT NULL default '1',
  ev2_posterfontsize varchar(20) NOT NULL default '1',
  ev2_posterstyle tinyint(1) unsigned NOT NULL default '0',
  newsscrollerheadingsep tinyint(1) unsigned NOT NULL default '0',
  newsscrollerheadingsepchar char(1) NOT NULL default '-',
  newsscrollernumsepchars int(10) unsigned NOT NULL default '40',
  jsns_sepheading tinyint(1) unsigned NOT NULL default '0',
  highlightmarker varchar(240) NOT NULL default 'highlight.gif',
  catinfobgcolor varchar(7) NOT NULL default '#dddddd',
  catinfofont varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',
  catinfofontsize varchar(20) NOT NULL default '2',
  catinfofontcolor varchar(7) NOT NULL default '#000000',
  catinfoindent varchar(20) NOT NULL default '10px',
  searchonlyheadings tinyint(1) unsigned NOT NULL default '0',
  searchdetailtarget varchar(80) NOT NULL default '_self',
  searchshortchars int(10) unsigned NOT NULL default '20',
  sshort_timestampfontsize varchar(20) NOT NULL default '1',
  sshort_headingfontsize varchar(20) NOT NULL default '1',
  nofileinfo tinyint(1) unsigned NOT NULL default '0',
  sn_hideallnewslink tinyint(1) unsigned NOT NULL default '0',
  pagenavdetails tinyint(1) unsigned NOT NULL default '1',
  newsletterbgcolor varchar(7) NOT NULL default '#ffffff',
  newslettercustomheader text NOT NULL,
  newslettercustomfooter text NOT NULL,
  subredirecturl varchar(255) NOT NULL default '',
  newsletteralign int(4) unsigned NOT NULL default '2',
  linkposter tinyint(1) unsigned NOT NULL default '0',
  weekstart tinyint(1) unsigned NOT NULL default '0',
  evshowcalweek tinyint(1) unsigned NOT NULL default '0',
  nlsend_heading varchar(80) NOT NULL default '',
  nlsend_dateformat varchar(20) NOT NULL default '%d.%m.%Y %H:%M',
  nlsend_bgcolor varchar(7) NOT NULL default '#ffeedd',
  nlsend_font varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',
  nlsend_fontsize varchar(10) NOT NULL default '2',
  nlsend_fontcolor varchar(7) NOT NULL default '#000000',
  nfheight int(10) unsigned NOT NULL default '100',
  emailpic varchar(240) NOT NULL default 'email.gif',
  sncatlink varchar(40) NOT NULL default 'news.php',
  textareanoscroll tinyint(1) unsigned NOT NULL default '0',
  emailcustomheader text,
  emailcustomfooter text,
  emailbgcolor varchar(7) NOT NULL default '#ffffff',
  emailpageremark text,
  hn6_numentries int(10) unsigned NOT NULL default '10',
  anhn6numentries int(10) unsigned NOT NULL default '1',
  hotnews6target varchar(240) NOT NULL default '_self',
  hotnews7useddlink tinyint(1) unsigned NOT NULL default '0',
  ratingdisplay int(4) unsigned NOT NULL default '1',
  ratingprelude varchar(80) NOT NULL default '',
  sns_tablewidth varchar(10) NOT NULL default '80%',
  sns_options int(10) unsigned NOT NULL default '0',
  hnlinkdest varchar(80) NOT NULL default '',
  usehnlinkdest tinyint(1) unsigned NOT NULL default '0',
  aninc_options int(10) unsigned NOT NULL default '0',
  aninc_tablewidth varchar(10) NOT NULL default '80%',
  hnlinkdestan varchar(80) NOT NULL default '',
  evinc_tablewidth varchar(10) NOT NULL default '80%',
  evinc_options int(10) unsigned NOT NULL default '0',
  hnlinkdestev varchar(80) NOT NULL default '',
  useappletlinkdest tinyint(1) unsigned NOT NULL default '0',
  appletlinkdest varchar(80) NOT NULL default '',
  appletlinkdestan varchar(80) NOT NULL default '',
  appletlinkdestev varchar(80) NOT NULL default '',
  usejslinkdest tinyint(1) unsigned NOT NULL default '0',
  jslinkdest varchar(80) NOT NULL default '',
  jslinkdestev varchar(80) NOT NULL default '',
  jslinkdestan varchar(80) NOT NULL default '',
  evscrollevcal2 tinyint(1) unsigned NOT NULL default '0',
  evscrollcal2dest varchar(80) NOT NULL default '',
  applet_ganmark varchar(20) NOT NULL default '',
  applet_anmark varchar(20) NOT NULL default '',
  attachpos tinyint(1) unsigned NOT NULL default '0',
  searchmaxchars int(10) unsigned NOT NULL default '0',
  searchhighlightcolor varchar(7) NOT NULL default '#ff0000',
  searchhighlight tinyint(1) unsigned NOT NULL default '0',
  activcellcolor varchar(7) NOT NULL default '#ffff00',
  news4showcat tinyint(1) unsigned NOT NULL default '0',
  showproposer tinyint(1) unsigned NOT NULL default '0',
  event_dateformat2 varchar(20) NOT NULL default 'd.m.Y H:i',
  commentsinline tinyint(1) unsigned NOT NULL default '0',
  icdisplayemail tinyint(1) unsigned NOT NULL default '0',
  ic_heading_bgcolor varchar(7) NOT NULL default '#999999',
  ic_heading_fontcolor varchar(7) NOT NULL default '#000000',
  ic_heading_font varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',
  ic_heading_fontsize varchar(4) NOT NULL default '1',
  ic_body_bgcolor varchar(7) NOT NULL default '#bbbbbb',
  ic_body_font varchar(240) NOT NULL default 'Verdana, Geneva, Arial, Helvetica, sans-serif',
  ic_body_fontsize int(4) unsigned NOT NULL default '2',
  ic_body_fontcolor varchar(7) NOT NULL default '#000000',
  ic_heading_style tinyint(1) unsigned NOT NULL default '0',
  ic_body_style tinyint(1) unsigned NOT NULL default '0',
  commentspic varchar(240) NOT NULL default 'comment_small.gif',
  hotnewscommentslink tinyint(1) unsigned NOT NULL default '1',
  writecommentpic varchar(240) NOT NULL default 'writecomment.gif',
  ev3_dateformat varchar(40) NOT NULL default '%B %d, %Y',
  masssuboptions int(10) unsigned NOT NULL default '0',
  tablebgcolor varchar(7) NOT NULL default '#c0c0c0',
  hn_catlinking int(10) unsigned NOT NULL default '0',
  hn_linklayout varchar(20) NOT NULL default '',
  rss_channel_title varchar(100) NOT NULL default '',
  rss_channel_description varchar(255) NOT NULL default '',
  rss_channel_link varchar(255) NOT NULL default '',
  rss_auto_title int(10) unsigned NOT NULL default '80',
  rss_auto_short int(10) unsigned NOT NULL default '200',
  rss_channel_copyright varchar(100) NOT NULL default '',
  rss_maxentries int(10) unsigned NOT NULL default '0',
  rss_channel_editor varchar(100) NOT NULL default '',
  rss_channel_webmaster varchar(100) NOT NULL default '',
  wap_title varchar(100) NOT NULL default '',
  wap_description varchar(255) NOT NULL default '',
  wap_copyright varchar(100) NOT NULL default '',
  wap_auto_short int(10) unsigned NOT NULL default '200',
  wap_auto_title int(10) unsigned NOT NULL default '80',
  wap_maxentries int(10) unsigned NOT NULL default '0',
  wap_options int(10) unsigned NOT NULL default '0',
  wap_ev_maxdays int(10) unsigned NOT NULL default '8',
  wap_ev_title varchar(100) NOT NULL default '',
  wap_ev_description varchar(255) NOT NULL default '',
  wap_ev_maxentries int(10) unsigned NOT NULL default '0',
  wap_an_maxdays int(10) unsigned NOT NULL default '8',
  wap_an_maxentries int(10) unsigned NOT NULL default '0',
  wap_an_title varchar(100) NOT NULL default '',
  wap_an_description varchar(255) NOT NULL default '',
  wap_evs_dayrange int(10) unsigned NOT NULL default '1',
  wap_evs_maxldays int(10) unsigned NOT NULL default '5',
  wap_catlist_epp int(10) unsigned NOT NULL default '10',
  wap_evlist2_epp int(10) unsigned NOT NULL default '10',
  printpic_small varchar(240) NOT NULL default 'print_small.gif',
  expandpic varchar(240) NOT NULL default 'expand.gif',
  collapsepic varchar(240) NOT NULL default 'collapse.gif',
  wap_cl_title varchar(80) NOT NULL default '',
  wap_cl_description varchar(255) NOT NULL default '',
  wap_cl_logo varchar(240) NOT NULL default '',
  newsnodate int(10) unsigned NOT NULL default '0',
  rsspic varchar(240) NOT NULL default 'xml.gif',
  eventcalonlymarkers tinyint(1) unsigned NOT NULL default '0',
  evmarkcolgeneral varchar(7) NOT NULL default '#333333',
  n4nodate tinyint(1) unsigned NOT NULL default '0',
  n4tbmargin varchar(10) NOT NULL default '',
  n4leftmargin varchar(10) NOT NULL default '',
  srchnolimit tinyint(1) unsigned NOT NULL default '0',
  newsnoicons tinyint(1) unsigned NOT NULL default '0',
  snnodate tinyint(1) unsigned NOT NULL default '0',
  hotnewsmaxchars int(10) NOT NULL default '-1',
  entriesperpage int(2) unsigned NOT NULL default '20',
  numhotnews tinyint(2) unsigned NOT NULL default '5',
  newsnotifydays tinyint(2) unsigned NOT NULL default '0',
  news2entries int(2) unsigned NOT NULL default '5',
  news3entries int(2) unsigned NOT NULL default '5',
  srchaddoptions int(10) unsigned NOT NULL default '0',
  cheadnobr tinyint(1) unsigned default '0',
  cfootnobr tinyint(1) unsigned default '0',
  news4addoptions int(6) unsigned NOT NULL default '0',
  subemailtype tinyint(1) unsigned NOT NULL default '0',
  showfuturenews tinyint(1) unsigned NOT NULL default '0',
  nonltrans tinyint(4) unsigned default '0',
  hnnolinking tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (layoutnr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_leachers`
-- 

DROP TABLE IF EXISTS simpnews_leachers;
CREATE TABLE simpnews_leachers (
  entrynr int(10) unsigned NOT NULL auto_increment,
  useragent varchar(80) NOT NULL default '',
  description text,
  PRIMARY KEY  (entrynr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_mimetypes`
-- 

DROP TABLE IF EXISTS simpnews_mimetypes;
CREATE TABLE simpnews_mimetypes (
  entrynr int(10) unsigned NOT NULL auto_increment,
  mimetype varchar(240) NOT NULL default '',
  icon varchar(240) NOT NULL default '',
  noupload tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (entrynr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_misc`
-- 

DROP TABLE IF EXISTS simpnews_misc;
CREATE TABLE simpnews_misc (
  shutdown tinyint(3) unsigned NOT NULL default '0',
  shutdowntext text
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_newcommentnotify`
-- 

DROP TABLE IF EXISTS simpnews_newcommentnotify;
CREATE TABLE simpnews_newcommentnotify (
  usernr int(10) unsigned NOT NULL default '0',
  UNIQUE KEY usernr (usernr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_news_attachs`
-- 

DROP TABLE IF EXISTS simpnews_news_attachs;
CREATE TABLE simpnews_news_attachs (
  entrynr int(10) unsigned NOT NULL auto_increment,
  newsnr int(10) unsigned NOT NULL default '0',
  attachnr int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (entrynr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_newsletteradmins`
-- 

DROP TABLE IF EXISTS simpnews_newsletteradmins;
CREATE TABLE simpnews_newsletteradmins (
  usernr int(10) unsigned NOT NULL default '0',
  UNIQUE KEY usernr (usernr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_newsubnotify`
-- 

DROP TABLE IF EXISTS simpnews_newsubnotify;
CREATE TABLE simpnews_newsubnotify (
  usernr int(10) unsigned NOT NULL default '0',
  UNIQUE KEY usernr (usernr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_notifylist`
-- 

DROP TABLE IF EXISTS simpnews_notifylist;
CREATE TABLE simpnews_notifylist (
  usernr int(10) unsigned NOT NULL default '0',
  UNIQUE KEY usernr (usernr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_poster`
-- 

DROP TABLE IF EXISTS simpnews_poster;
CREATE TABLE simpnews_poster (
  entrynr int(10) unsigned NOT NULL auto_increment,
  email varchar(240) NOT NULL default '',
  name varchar(240) NOT NULL default '',
  password varchar(40) binary NOT NULL default '',
  pid int(10) unsigned NOT NULL default '0',
  pwconfirmed tinyint(1) unsigned NOT NULL default '0',
  disablebbcode tinyint(1) unsigned NOT NULL default '0',
  disablefileupload tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (entrynr),
  UNIQUE KEY FieldName (entrynr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_ratings`
-- 

DROP TABLE IF EXISTS simpnews_ratings;
CREATE TABLE simpnews_ratings (
  rating int(4) unsigned NOT NULL default '0',
  lang varchar(4) NOT NULL default '',
  text varchar(40) NOT NULL default '',
  UNIQUE KEY ratings_index (lang,rating)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_rss_catlist`
-- 

DROP TABLE IF EXISTS simpnews_rss_catlist;
CREATE TABLE simpnews_rss_catlist (
  catnr int(10) unsigned NOT NULL default '0',
  layoutid varchar(10) NOT NULL default '',
  displaypos int(10) unsigned NOT NULL default '0',
  UNIQUE KEY catlistkey (catnr,layoutid)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_search`
-- 

DROP TABLE IF EXISTS simpnews_search;
CREATE TABLE simpnews_search (
  newsnr int(10) unsigned NOT NULL default '0',
  text text NOT NULL
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_session`
-- 

DROP TABLE IF EXISTS simpnews_session;
CREATE TABLE simpnews_session (
  sessid int(10) unsigned NOT NULL default '0',
  usernr int(10) NOT NULL default '0',
  starttime int(10) unsigned NOT NULL default '0',
  remoteip varchar(15) NOT NULL default '',
  lastlogin datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (sessid),
  KEY sess_id (sessid),
  KEY start_time (starttime),
  KEY remote_ip (remoteip)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_session2`
-- 

DROP TABLE IF EXISTS simpnews_session2;
CREATE TABLE simpnews_session2 (
  sessid int(10) unsigned NOT NULL default '0',
  usernr int(10) NOT NULL default '0',
  starttime int(10) unsigned NOT NULL default '0',
  remoteip varchar(15) NOT NULL default '',
  PRIMARY KEY  (sessid),
  KEY sess_id (sessid),
  KEY start_time (starttime),
  KEY remote_ip (remoteip)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_settings`
-- 

DROP TABLE IF EXISTS simpnews_settings;
CREATE TABLE simpnews_settings (
  settingnr int(10) unsigned NOT NULL auto_increment,
  watchlogins tinyint(1) unsigned NOT NULL default '0',
  enablefailednotify tinyint(1) unsigned NOT NULL default '0',
  simpnewsmail varchar(180) NOT NULL default '',
  loginlimit int(2) unsigned NOT NULL default '0',
  usemenubar tinyint(1) unsigned NOT NULL default '0',
  nofreemailer tinyint(1) unsigned NOT NULL default '0',
  enablehostresolve tinyint(1) unsigned NOT NULL default '0',
  enablesubscriptions tinyint(1) NOT NULL default '0',
  maxconfirmtime int(1) unsigned NOT NULL default '2',
  subject varchar(80) NOT NULL default '',
  subscriptionsendmode tinyint(1) unsigned NOT NULL default '1',
  subscriptionfreemailer tinyint(1) unsigned NOT NULL default '1',
  sitename varchar(80) NOT NULL default '',
  maxage int(5) unsigned NOT NULL default '0',
  allowcomments tinyint(1) unsigned NOT NULL default '0',
  enablesearch tinyint(1) unsigned NOT NULL default '0',
  redirectdelay tinyint(2) NOT NULL default '-1',
  newsineventcal tinyint(1) unsigned NOT NULL default '0',
  lastvisitcookie tinyint(1) unsigned NOT NULL default '1',
  servertimezone int(10) NOT NULL default '0',
  displaytimezone int(10) NOT NULL default '0',
  simpnewsmailname varchar(180) NOT NULL default '',
  admrestrict tinyint(3) unsigned NOT NULL default '0',
  newsletternoicons tinyint(1) unsigned NOT NULL default '0',
  maxpropose int(10) unsigned NOT NULL default '0',
  enablepropose tinyint(1) unsigned NOT NULL default '0',
  enableevpropose tinyint(1) unsigned NOT NULL default '0',
  proposenotify tinyint(1) unsigned NOT NULL default '0',
  notifymode tinyint(1) unsigned NOT NULL default '0',
  admentrychars int(10) unsigned NOT NULL default '0',
  admonlyentryheadings tinyint(1) unsigned NOT NULL default '0',
  proposepermissions tinyint(4) unsigned NOT NULL default '0',
  exporttype tinyint(1) unsigned NOT NULL default '0',
  asclist tinyint(1) unsigned NOT NULL default '0',
  admdelconfirm tinyint(4) unsigned NOT NULL default '0',
  mailattach tinyint(1) unsigned NOT NULL default '0',
  evnewsletterinclude tinyint(1) unsigned NOT NULL default '0',
  msendlimit int(10) unsigned NOT NULL default '30',
  admepp int(10) unsigned NOT NULL default '0',
  secsettings int(10) unsigned NOT NULL default '0',
  bbcimgdefalign varchar(20) NOT NULL default 'Center',
  newsletterattachinlinepix tinyint(1) unsigned NOT NULL default '0',
  icons_maxheight int(10) unsigned NOT NULL default '20',
  icons_maxwidth int(10) unsigned NOT NULL default '20',
  inline_thumbwidth int(10) unsigned NOT NULL default '50',
  inline_thumbheight int(10) unsigned NOT NULL default '50',
  inline_genthumbs tinyint(1) unsigned NOT NULL default '0',
  inline_maxwidth int(10) unsigned NOT NULL default '640',
  inline_maxheight int(10) unsigned NOT NULL default '480',
  admstorefilter tinyint(1) unsigned NOT NULL default '1',
  newsubscriptionnotify tinyint(1) unsigned NOT NULL default '0',
  subremovenotify tinyint(1) unsigned NOT NULL default '0',
  maxuserupload int(10) unsigned NOT NULL default '100000',
  emailnews tinyint(1) unsigned NOT NULL default '0',
  yearrange tinyint(2) unsigned NOT NULL default '5',
  useviewcounts tinyint(1) unsigned NOT NULL default '0',
  minviews int(10) unsigned NOT NULL default '0',
  usedlcounts tinyint(1) unsigned NOT NULL default '0',
  admaltlayout tinyint(1) unsigned NOT NULL default '0',
  enablerating tinyint(1) unsigned NOT NULL default '0',
  blockleacher tinyint(1) unsigned NOT NULL default '0',
  sendnewsdelay int(10) unsigned NOT NULL default '0',
  senddelayinterval int(10) unsigned NOT NULL default '1',
  showsendprogress tinyint(1) unsigned NOT NULL default '1',
  sendprogressautohide tinyint(1) unsigned NOT NULL default '1',
  lastvisitdays int(10) unsigned NOT NULL default '365',
  lastvisitsessiontime int(10) unsigned NOT NULL default '60',
  dosearchlog tinyint(1) unsigned NOT NULL default '0',
  newcomnotify tinyint(1) unsigned NOT NULL default '0',
  rss_enable tinyint(1) unsigned NOT NULL default '0',
  wap_enable tinyint(1) unsigned NOT NULL default '0',
  emaillog int(2) unsigned NOT NULL default '0',
  emailerrordie tinyint(1) unsigned NOT NULL default '1',
  prohibitnoregfiletypes tinyint(1) unsigned NOT NULL default '1',
  newsletterlinking tinyint(1) unsigned NOT NULL default '0',
  mailmaxlinelength int(10) unsigned NOT NULL default '998',
  prop_nopwconfirm tinyint(1) unsigned default '0',
  admaltprv tinyint(1) unsigned NOT NULL default '0',
  enableevsearch tinyint(1) unsigned NOT NULL default '0',
  usebwlist tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (settingnr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_subscriptions`
-- 

DROP TABLE IF EXISTS simpnews_subscriptions;
CREATE TABLE simpnews_subscriptions (
  subscriptionnr int(10) unsigned NOT NULL auto_increment,
  email varchar(240) NOT NULL default '',
  confirmed int(1) unsigned NOT NULL default '0',
  language varchar(4) NOT NULL default '',
  subscribeid int(10) unsigned NOT NULL default '0',
  unsubscribeid int(10) unsigned NOT NULL default '0',
  enterdate datetime NOT NULL default '0000-00-00 00:00:00',
  lastsent datetime NOT NULL default '0000-00-00 00:00:00',
  emailtype tinyint(1) unsigned NOT NULL default '1',
  lastmanual datetime NOT NULL default '0000-00-00 00:00:00',
  category int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (subscriptionnr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_texts`
-- 

DROP TABLE IF EXISTS simpnews_texts;
CREATE TABLE simpnews_texts (
  textnr int(10) unsigned NOT NULL auto_increment,
  textid varchar(20) NOT NULL default '',
  lang varchar(4) NOT NULL default '',
  text text NOT NULL,
  PRIMARY KEY  (textnr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_tmpdata`
-- 

DROP TABLE IF EXISTS simpnews_tmpdata;
CREATE TABLE simpnews_tmpdata (
  entrynr int(10) unsigned NOT NULL auto_increment,
  lang varchar(4) NOT NULL default '',
  date datetime NOT NULL default '0000-00-00 00:00:00',
  text text NOT NULL,
  heading varchar(80) NOT NULL default '',
  category int(10) unsigned NOT NULL default '0',
  posterip varchar(16) NOT NULL default '',
  posterid int(10) unsigned NOT NULL default '0',
  chgnews int(10) unsigned NOT NULL default '0',
  postingid varchar(40) NOT NULL default '0',
  PRIMARY KEY  (entrynr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_tmpevents`
-- 

DROP TABLE IF EXISTS simpnews_tmpevents;
CREATE TABLE simpnews_tmpevents (
  entrynr int(10) unsigned NOT NULL auto_increment,
  date date NOT NULL default '0000-00-00',
  lang varchar(4) NOT NULL default '',
  category int(10) NOT NULL default '0',
  heading varchar(80) NOT NULL default '',
  text text NOT NULL,
  added datetime NOT NULL default '0000-00-00 00:00:00',
  posterip varchar(16) NOT NULL default '',
  posterid int(10) unsigned NOT NULL default '0',
  chgevent int(10) unsigned NOT NULL default '0',
  postingid varchar(40) NOT NULL default '0',
  PRIMARY KEY  (entrynr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_tmpevents_attachs`
-- 

DROP TABLE IF EXISTS simpnews_tmpevents_attachs;
CREATE TABLE simpnews_tmpevents_attachs (
  entrynr int(10) unsigned NOT NULL auto_increment,
  eventnr int(10) unsigned NOT NULL default '0',
  attachnr int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (entrynr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_tmpnews_attachs`
-- 

DROP TABLE IF EXISTS simpnews_tmpnews_attachs;
CREATE TABLE simpnews_tmpnews_attachs (
  entrynr int(10) unsigned NOT NULL auto_increment,
  newsnr int(10) unsigned NOT NULL default '0',
  attachnr int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (entrynr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_users`
-- 

DROP TABLE IF EXISTS simpnews_users;
CREATE TABLE simpnews_users (
  usernr tinyint(3) unsigned NOT NULL auto_increment,
  username varchar(80) NOT NULL default '',
  password varchar(40) binary NOT NULL default '',
  email varchar(80) default NULL,
  rights int(2) unsigned NOT NULL default '0',
  lastlogin datetime NOT NULL default '0000-00-00 00:00:00',
  lockpw tinyint(1) unsigned NOT NULL default '0',
  realname varchar(240) NOT NULL default '',
  autopin int(10) unsigned NOT NULL default '0',
  language varchar(10) NOT NULL default 'de',
  lockentry tinyint(1) unsigned NOT NULL default '0',
  addoptions int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (usernr)
) TYPE=MyISAM;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `simpnews_wap_catlist`
-- 

DROP TABLE IF EXISTS simpnews_wap_catlist;
CREATE TABLE simpnews_wap_catlist (
  catnr int(10) unsigned NOT NULL default '0',
  layoutid varchar(10) NOT NULL default '',
  modes int(10) unsigned NOT NULL default '0',
  displaypos int(10) unsigned NOT NULL default '0',
  UNIQUE KEY catlistkey (catnr,layoutid)
) TYPE=MyISAM;
