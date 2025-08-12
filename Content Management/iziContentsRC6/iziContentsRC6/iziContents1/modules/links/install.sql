# --------------------------------------------------------
#
# Table structure for table `links`
#


DROP TABLE IF EXISTS links;
CREATE TABLE links (
	linksid int(11) NOT NULL auto_increment,
	publishdate datetime default NULL,
	linkurl varchar(255) default NULL,
	linkdescr text default NULL,
	activeentry char(1) default NULL,
	authorid int(11) default NULL,
	updatedate datetime default NULL,
	catid varchar(16) default NULL,
	PRIMARY KEY  (linksid)
) TYPE=MyISAM;


# --------------------------------------------------------
#
# Table structure for table `linkscategories`
#

DROP TABLE IF EXISTS linkscategories;
CREATE TABLE linkscategories (
	catid int(11) unsigned NOT NULL auto_increment,
	catname varchar(32) NOT NULL default '',
	catref varchar(32) default NULL,
	hiddencat char(1) default '0',
	PRIMARY KEY  (catid)
) TYPE=MyISAM;


# --------------------------------------------------------
#
# Data for table `modules`
#
INSERT INTO modules (modulename, extin, moduledirectory, modulescript, modulesubmit, hascats) VALUES ('Links', 'E', 'links', 'showlinks.php', 'Y', 'Y');

# --------------------------------------------------------
#
# Data for table `specialcontents`
#
INSERT INTO specialcontents (scname, sctitle, scdb, screg, scvalid, stextdisplay, stext, sgraphicdisplay, sgraphic, usergroups, scuseprefix, scusecategories, orderby, showpostedby, showposteddate, perpage) VALUES ('links', 'Links', 'links', 'Y', 'Y', 'Y', 'Submit Link', '', '', '', 'N', 'N', 'D', '', 'Y', 8);
