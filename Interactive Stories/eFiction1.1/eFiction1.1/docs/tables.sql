#
# Table structure for table `fanfiction_authors`
#

CREATE TABLE fanfiction_authors (
  uid int(11) NOT NULL auto_increment,
  penname varchar(200) NOT NULL default '',
  realname varchar(200) NOT NULL default '',
  email varchar(200) NOT NULL default '',
  website varchar(200) NOT NULL default '',
  bio text NOT NULL,
  image varchar(200) NOT NULL default '',
  date datetime NOT NULL default '0000-00-00 00:00:00',
  newreviews int(11) NOT NULL default '0',
  admincreated int(11) NOT NULL default '0',
  password varchar(40) NOT NULL default '0',
  validated int(11) NOT NULL default '0',
  userskin varchar(60) NOT NULL default '',
  level tinyint(4) NOT NULL default '0',
  contact tinyint(4) NOT NULL default '0',
  carry tinyint(4) NOT NULL default '0',
  categories varchar(200) NOT NULL default '0',
  bob int(11) NOT NULL default '0',
  PRIMARY KEY  (uid),
  KEY penname (penname),
  KEY validated (validated),
  KEY admincreated (admincreated),
  KEY level (level),
  KEY contact (contact)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `fanfiction_categories`
#

CREATE TABLE fanfiction_categories (
  catid int(11) NOT NULL auto_increment,
  parentcatid int(11) NOT NULL default '-1',
  category varchar(60) NOT NULL default '',
  description text NOT NULL,
  image varchar(100) NOT NULL default '',
  locked int(11) NOT NULL default '0',
  leveldown tinyint(4) NOT NULL default '0',
  displayorder int(4) NOT NULL default '0',
  numitems int(11) NOT NULL default '0',
  PRIMARY KEY  (catid),
  KEY parentcatid (parentcatid),
  KEY category (category)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `fanfiction_characters`
#

CREATE TABLE fanfiction_characters (
  charid int(11) NOT NULL auto_increment,
  catid int(11) NOT NULL default '0',
  charname varchar(60) NOT NULL default '',
  PRIMARY KEY  (charid),
  KEY catid (catid),
  KEY charname (charname)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `fanfiction_comments`
#

CREATE TABLE fanfiction_comments (
  cid int(11) NOT NULL auto_increment,
  nid int(11) NOT NULL default '0',
  uname varchar(100) NOT NULL default '',
  comment text NOT NULL,
  time datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (cid),
  KEY nid (nid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `fanfiction_favauth`
#

CREATE TABLE fanfiction_favauth (
  uid int(11) NOT NULL default '0',
  favuid int(11) NOT NULL default '0',
  KEY uid (uid,favuid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `fanfiction_favstor`
#

CREATE TABLE fanfiction_favstor (
  uid int(11) NOT NULL default '0',
  sid int(11) NOT NULL default '0',
  KEY sid (sid),
  KEY uid (uid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `fanfiction_genres`
#

CREATE TABLE fanfiction_genres (
  gid int(11) NOT NULL auto_increment,
  genre varchar(60) NOT NULL default '',
  PRIMARY KEY  (gid),
  KEY genre (genre)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `fanfiction_news`
#

CREATE TABLE fanfiction_news (
  nid int(11) NOT NULL auto_increment,
  author varchar(60) NOT NULL default '',
  title varchar(255) NOT NULL default '',
  story text NOT NULL,
  time datetime default NULL,
  PRIMARY KEY  (nid)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `fanfiction_ratings`
#

CREATE TABLE fanfiction_ratings (
  rid int(11) NOT NULL auto_increment,
  rating varchar(60) NOT NULL default '',
  ratingwarning int(11) NOT NULL default '0',
  warningtext text NOT NULL,
  PRIMARY KEY  (rid),
  KEY rating (rating)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `fanfiction_reviews`
#

CREATE TABLE fanfiction_reviews (
  reviewid int(11) NOT NULL auto_increment,
  sid int(11) NOT NULL default '0',
  psid int(11) NOT NULL default '0',
  reviewer varchar(60) NOT NULL default '0',
  member int(11) NOT NULL default '0',
  review text NOT NULL,
  date datetime NOT NULL default '0000-00-00 00:00:00',
  rating int(11) NOT NULL default '0',
  PRIMARY KEY  (reviewid),
  KEY sid (sid),
  KEY psid (psid),
  KEY rating (rating)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `fanfiction_settings`
#

CREATE TABLE fanfiction_settings (
  welcome text NOT NULL,
  thankyou text NOT NULL,
  nothankyou text NOT NULL,
  rules text NOT NULL,
  copyright text NOT NULL,
  help text NOT NULL
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Dumping data for table `fanfiction_settings`
#

INSERT INTO fanfiction_settings VALUES ('This is a sample welcome message. It could say just about anything. It doesn\'t even need to be at the top of the page, as the template allows for it to be placed anywhere on the index page.','This is a default Thank You letter, that you can choose to send people upon accepting their story into the archive.','This is the default No Thank You letter, that you can choose to send people upon not accepting their story into the archive.','We have very basic submission rules: <br><br>\r\n\r\n1. No Mary Sues.<br><br>\r\n\r\n2. No crossovers.<br><br>\r\n\r\n3. Please check your grammar and spelling.<br><br>\r\n\r\nIf you accept the rules, please choose from the categories below.<br><br>','This is your copyright footer. You can put whatever you want here, but it makes sense to say something like "This site is not affiliated with big scary corporations that could sue my pants off, blah, blah, blah." It sure would be nice if you kept a note in here, too, about where you got this script from ;)','<center><h4>Help Page</h4></center>\r\n\r\nYou could use this page for a help or FAQ page, or anything you wanted.');

    
#
# Table structure for table `fanfiction_stories`
#

CREATE TABLE fanfiction_stories (
  sid int(11) NOT NULL auto_increment,
  psid int(11) NOT NULL default '0',
  title varchar(200) NOT NULL default '',
  chapter varchar(200) NOT NULL default '',
  summary text NOT NULL,
  catid int(11) NOT NULL default '0',
  gid varchar(250) NOT NULL default '0',
  charid varchar(250) NOT NULL default '0',
  wid varchar(250) NOT NULL default '0',
  rid varchar(25) NOT NULL default '0',
  date datetime NOT NULL default '0000-00-00 00:00:00',
  updated datetime NOT NULL default '0000-00-00 00:00:00',
  uid int(11) NOT NULL default '0',
  featured int(11) NOT NULL default '0',
  counter int(11) NOT NULL default '0',
  validated int(11) NOT NULL default '0',
  inorder tinyint(4) NOT NULL default '0',
  storytext text NOT NULL,
  completed tinyint(4) NOT NULL default '0',
  rr tinyint(4) NOT NULL default '0',
  wordcount int(11) NOT NULL default '0',
  numreviews int(4) NOT NULL default '0',
  PRIMARY KEY  (sid),
  KEY psid (psid),
  KEY title (title),
  KEY catid (catid),
  KEY gid (gid),
  KEY charid (charid),
  KEY wid (wid),
  KEY rid (rid),
  KEY uid (uid),
  KEY featured (featured),
  KEY validated (validated),
  KEY completed (completed),
  KEY rr (rr)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `fanfiction_warnings`
#

CREATE TABLE fanfiction_warnings (
  wid int(11) NOT NULL auto_increment,
  warning varchar(60) NOT NULL default '',
  PRIMARY KEY  (wid),
  KEY warning (warning)
) TYPE=MyISAM;

