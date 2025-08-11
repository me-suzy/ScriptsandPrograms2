<?php
include "connect.php";
$installartcats="CREATE TABLE `b_artcats` (
  `categoryid` bigint(20) NOT NULL auto_increment,
  `categoryname` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`categoryid`)
)";
mysql_query($installartcats) or die("Could not install article cats");
$installarticles="CREATE TABLE `b_articles` (
  `artID` bigint(20) NOT NULL auto_increment,
  `validates` int(11) NOT NULL default '0',
  `authorid` bigint(20) NOT NULL default '0',
  `shortdes` mediumtext NOT NULL,
  `body` longtext NOT NULL,
  `category` bigint(20) NOT NULL default '0',
  `titles` varchar(255) NOT NULL default '',
  `thedate` varchar(255) NOT NULL default '',
  `thetime` bigint(20) NOT NULL default '0',
  `forumtopic` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`artID`)
)";
mysql_query($installarticles) or die("Could not install articles");
$installbans="CREATE TABLE `b_banemails` (
  `emailid` bigint(20) NOT NULL auto_increment,
  `email` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`emailid`)
)";
mysql_query($installbans) or die("Could not install banned emails");
$installbanips="CREATE TABLE `b_banip` (
  `ipid` bigint(20) NOT NULL auto_increment,
  `ip` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ipid`)
)";
mysql_query($installbanips) or die("Could not install banned ips");
$installcats="CREATE TABLE `b_categories` (
  `categoryid` bigint(20) NOT NULL auto_increment,
  `categoryname` varchar(255) NOT NULL default '',
  `catsort` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`categoryid`)
)";
mysql_query($installcats) or die("Could not install categories");
$installforums="CREATE TABLE `b_forums` (
  `ID` bigint(20) NOT NULL auto_increment,
  `parentID` bigint(20) NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `description` tinytext NOT NULL,
  `numtopics` bigint(20) NOT NULL default '0',
  `numposts` bigint(20) NOT NULL default '0',
  `lastpost` varchar(255) NOT NULL default '',
  `sort` bigint(20) NOT NULL default '0',
  `lastpostuser` varchar(255) NOT NULL default '',
  `permission_min` int(11) NOT NULL default '0',
  `permission_post` int(11) NOT NULL default '0',
  `permission_reply` int(11) NOT NULL default '0',
  `lastposttime` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
)";
mysql_query($installforums) or die("Could not install forums");
$installpms="CREATE TABLE `b_pms` (
  `pmID` bigint(20) NOT NULL auto_increment,
  `sender` bigint(20) NOT NULL default '0',
  `receiver` bigint(20) NOT NULL default '0',
  `therealtime` bigint(20) NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `message` mediumtext NOT NULL,
  `hasread` int(11) NOT NULL default '0',
  `vartime` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`pmID`)
)";
mysql_query($installpms) or die("Could not install pms");
$installposts="CREATE TABLE `b_posts` (
  `ID` bigint(21) NOT NULL auto_increment,
  `title` varchar(60) NOT NULL default '',
  `author` bigint(20) NOT NULL default '0',
  `telapsed` bigint(21) NOT NULL default '0',
  `timepost` varchar(100) NOT NULL default '',
  `numreplies` int(10) NOT NULL default '0',
  `post` longtext NOT NULL,
  `threadparent` bigint(21) NOT NULL default '0',
  `lastpost` varchar(255) NOT NULL default '',
  `postforum` bigint(20) NOT NULL default '0',
  `views` bigint(20) NOT NULL default '0',
  `nosmilies` int(11) NOT NULL default '0',
  `value` int(11) NOT NULL default '0',
  `ipaddress` varchar(255) NOT NULL default '',
  `locked` int(11) NOT NULL default '0',
  `articleidentifier` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
)";
mysql_query($installposts) or die("Could not install posts");
$installranks="CREATE TABLE `b_ranks` (
  `rankID` bigint(20) NOT NULL auto_increment,
  `rankname` varchar(255) NOT NULL default '',
  `postsneeded` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`rankID`)
)";
mysql_query($installranks) or die("Could not install ranks");
$installusers="
CREATE TABLE b_users (
  userID bigint(21) NOT NULL auto_increment,
  username varchar(60) NOT NULL default '',
  password varchar(255) NOT NULL default '',
  status int(20) NOT NULL default '0',
  posts bigint(20) NOT NULL default '0',
  email varchar(255) NOT NULL default '',
  validated int(11) NOT NULL default '0',
  keynode bigint(21) NOT NULL default '0',
  sig tinytext NOT NULL,
  banned varchar(255) NOT NULL default 'no',
  rank varchar(255) NOT NULL default '0',
  usepm int(11) NOT NULL default '1',
  AIM varchar(50) NOT NULL default '',
  ICQ varchar(50) NOT NULL default '',
  location varchar(255) NOT NULL default '',
  showprofile smallint(6) NOT NULL default '1',
  lastposttime bigint(20) NOT NULL default '0',
  tsgone bigint(20) NOT NULL default '0',
  oldtime bigint(20) NOT NULL default '0',
  avatar varchar(255) NOT NULL default '',
  photo varchar(255) NOT NULL default '',
  rating bigint(255) NOT NULL default '0',
  totalvotes bigint(20) NOT NULL default '0',
  votedfor longtext NOT NULL,
  rps int(11) NOT NULL default '1',
  rpsscore bigint(20) NOT NULL default '0',
  lasttime bigint(20) NOT NULL default '0',
  PRIMARY KEY  (userID)
)";
$installrps="CREATE TABLE b_rps (
  rpsid bigint(20) NOT NULL auto_increment,
  challenger bigint(20) NOT NULL default '0',
  challenged bigint(20) NOT NULL default '0',
  throw int(11) NOT NULL default '0',
  accept int(11) NOT NULL default '0',
  result tinytext NOT NULL,
  PRIMARY KEY  (rpsid)
)";
$installpages="CREATE TABLE b_pages (
  pageid bigint(20) NOT NULL auto_increment,
  pagename varchar(255) NOT NULL default '',
  pagetext text NOT NULL,
  pagecat bigint(20) NOT NULL default '0',
  PRIMARY KEY  (pageid),
  FULLTEXT KEY pagetext (pagetext),
  FULLTEXT KEY pagename (pagename)
)";
$installpagecats="CREATE TABLE `b_pagecats` (
  `pagecatid` bigint(20) NOT NULL auto_increment,
  `pagecatname` varchar(255) NOT NULL default '',
  `pagecatorder` bigint(20) NOT NULL default '0',
  PRIMARY KEY  (`pagecatid`)
)";
mysql_query($installpagecats) or die("Could not install page cats");
mysql_query($installpages) or die("Could not install pages");
mysql_query($installrps) or die("Could not install rps");
mysql_query($installusers) or die("Could not install users");
$insertguest="INSERT into b_users (username, password, validated) values('Guest','asdgasdfasd','1')";
mysql_query($insertguest) or die("Could not create default guest account");
$insertrank="INSERT into b_ranks(rankname,postsneeded) values('member','0')";
mysql_query($insertrank) or die("Could not insert default rank");
$intcats="CREATE TABLE tut_cats (
  catID bigint(20) NOT NULL auto_increment,
  catname varchar(255) NOT NULL default '',
  parentcat bigint(20) NOT NULL default '0',
  numtutorials bigint(20) NOT NULL default '0',
  lastadded bigint(20) NOT NULL default '0',
  PRIMARY KEY  (catID)
)";
mysql_query($intcats) or die("Could not install categories");
$inttut="CREATE TABLE tut_entries (
  tutid bigint(20) NOT NULL auto_increment,
  title varchar(255) NOT NULL default '',
  shortdes tinytext NOT NULL,
  description mediumtext NOT NULL,
  url varchar(255) NOT NULL default '',
  hitsout bigint(20) NOT NULL default '0',
  totalscore bigint(20) NOT NULL default '0',
  totalvotes bigint(20) NOT NULL default '0',
  avgvote float NOT NULL default '0',
  rankscore bigint(20) NOT NULL default '0',
  validated int(11) NOT NULL default '0',
  catparent bigint(20) NOT NULL default '0',
  timeadded bigint(20) NOT NULL default '0',
  dateadded varchar(255) NOT NULL default '',
  author varchar(255) NOT NULL default '',
  passkey varchar(255) NOT NULL default '',
  email varchar(255) NOT NULL default '',
  PRIMARY KEY  (tutid)
)";
mysql_query($inttut) or die("Could not install entries");
$intchange="CREATE TABLE tut_changes (
  ID bigint(21) NOT NULL auto_increment,
  editid bigint(21) NOT NULL default '0',
  email varchar(255) NOT NULL default '',
  title varchar(255) NOT NULL default '',
  author varchar(255) NOT NULL default '',
  catparent bigint(20) NOT NULL default '0',
  url varchar(255) NOT NULL default '',
  shortdes tinytext NOT NULL,
  description longtext NOT NULL,
  usedcat bigint(20) NOT NULL default '0',
  PRIMARY KEY  (ID)
)";
mysql_query($intchange) or die("Could not install changes");
$intip="CREATE TABLE tut_ip (
  vid bigint(20) NOT NULL auto_increment,
  votedfor bigint(20) NOT NULL default '0',
  ip varchar(255) NOT NULL default '',
  time bigint(20) NOT NULL default '0',
  PRIMARY KEY  (vid)
)";
mysql_query($intip) or die("Could not install tut ips");



print "Installation complete.";
?>
