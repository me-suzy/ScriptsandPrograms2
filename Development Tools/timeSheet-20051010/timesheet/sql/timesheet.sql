-- MySQL dump 9.11
--
-- Host: localhost    Database: ultrize_timecard
-- ------------------------------------------------------
-- Server version	4.0.25-standard

--
-- Table structure for table `clientShare`
--

CREATE TABLE clientShare (
  client_id int(10) unsigned NOT NULL default '0',
  owner_id int(10) unsigned NOT NULL default '0',
  share_owner_id int(10) unsigned NOT NULL default '0'
) TYPE=MyISAM;

--
-- Table structure for table `clients`
--

CREATE TABLE clients (
  id int(10) unsigned NOT NULL auto_increment,
  user_id int(10) unsigned NOT NULL default '0',
  email char(64) NOT NULL default '',
  clientDesc char(64) NOT NULL default '',
  dateAdded datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `jobs`
--

CREATE TABLE jobs (
  id int(10) unsigned NOT NULL auto_increment,
  jobDesc char(64) NOT NULL default '',
  rate float(6,2) NOT NULL default '0.00',
  client_id int(10) unsigned NOT NULL default '0',
  start datetime NOT NULL default '0000-00-00 00:00:00',
  finished datetime NOT NULL default '0000-00-00 00:00:00',
  user_id int(10) unsigned NOT NULL default '0',
  lastBilled datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `notes`
--

CREATE TABLE notes (
  id int(10) unsigned NOT NULL auto_increment,
  user_id int(10) unsigned NOT NULL default '0',
  job_id int(10) unsigned NOT NULL default '0',
  notes text,
  datePosted datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `tasks`
--

CREATE TABLE tasks (
  id int(10) unsigned NOT NULL auto_increment,
  punchIn int(10) unsigned NOT NULL default '0',
  punchOut int(10) unsigned NOT NULL default '0',
  punchDesc char(255) NOT NULL default '',
  user_id int(10) unsigned NOT NULL default '0',
  job_id int(10) unsigned NOT NULL default '0',
  archive datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Table structure for table `users`
--

CREATE TABLE users (
  id int(10) unsigned NOT NULL auto_increment,
  firstname char(64) NOT NULL default '',
  lastname char(64) NOT NULL default '',
  email char(64) NOT NULL default '',
  password char(64) NOT NULL default '',
  dateAdded datetime NOT NULL default '0000-00-00 00:00:00',
  ip char(16) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY email_2 (email),
  KEY email (email)
) TYPE=MyISAM;

