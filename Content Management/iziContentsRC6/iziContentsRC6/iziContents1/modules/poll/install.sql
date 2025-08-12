# --------------------------------------------------------
#
# Table structure for table `poll`
#

DROP TABLE IF EXISTS poll;
CREATE TABLE poll (
  pollid int(11) NOT NULL auto_increment,
  publishdate datetime default NULL,
  expiredate datetime default NULL,
  question varchar(255) default NULL,
  activeentry char(1) default NULL,
  authorid int(11) default NULL,
  catid varchar(16) default NULL,
  polltype char(1) default 'S',
  pollvotes int(11) default '0',
  PRIMARY KEY  (pollid)
) TYPE=MyISAM;


# --------------------------------------------------------
#
# Table structure for table `polloptions`
#

DROP TABLE IF EXISTS polloptions;
CREATE TABLE polloptions (
  polloptionid int(11) NOT NULL auto_increment,
  pollid int(11) NOT NULL,
  polloption varchar(255) default NULL,
  optioncount int(11) default 0,
  PRIMARY KEY  (polloptionid),
  KEY optionref (pollid,polloptionid)
) TYPE=MyISAM;


# --------------------------------------------------------
#
# Table structure for table `pollcategories`
#

DROP TABLE IF EXISTS pollcategories;
CREATE TABLE pollcategories (
  catid int(11) unsigned NOT NULL auto_increment,
  catname varchar(32) NOT NULL default '',
  catref varchar(32) default NULL,
  hiddencat char(1) default '0',
  PRIMARY KEY  (catid)
) TYPE=MyISAM;


# --------------------------------------------------------
#
# Table structure for table `pollresults`
#

DROP TABLE IF EXISTS pollresults;
CREATE TABLE pollresults (
  resultid int(11) NOT NULL auto_increment,
  userid varchar(32) NOT NULL default '',
  pollid int(11) NOT NULL default '0',
  pollresult int(11) NOT NULL default '0',
  PRIMARY KEY  (resultid),
  UNIQUE KEY rated (pollid,userid,pollresult)
) TYPE=MyISAM;


# --------------------------------------------------------
#
# Data for table `modules`
#
INSERT INTO modules (modulename, extin, moduledirectory, modulescript, modulesubmit, hascats) VALUES ('Poll', 'E', 'poll', 'showpoll.php', 'N', 'Y');
INSERT INTO modules (modulename, extin, moduledirectory, modulescript, modulesubmit, hascats) VALUES ('Inline Poll', 'I', 'poll', 'inlinepoll.php', 'N', 'Y');


# --------------------------------------------------------
#
# Data for table `modulesettings`
#
INSERT INTO modulesettings (modulename, settingname, settingvalue) VALUES ('poll', 'MainScreenWidthMultiplier', '3');
INSERT INTO modulesettings (modulename, settingname, settingvalue) VALUES ('poll', 'InlineScreenWidthMultiplier', '1');


# --------------------------------------------------------
#
# Data for table `specialcontents`
#
INSERT INTO specialcontents (scname, sctitle, scdb, screg, scvalid, stextdisplay, stext, sgraphicdisplay, sgraphic, usergroups, scuseprefix, scusecategories, orderby, showpostedby, showposteddate, perpage) VALUES ('poll', 'Poll', 'poll', 'Y', 'Y', '', '', '', '', '', 'N', 'N', 'D', '', 'Y', 1);
