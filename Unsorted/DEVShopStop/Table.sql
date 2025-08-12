#
# Table structure for table `CWC_shopstop`
#

CREATE TABLE CWC_shopstop (
  pid int(11) NOT NULL auto_increment,
  name varchar(20) NOT NULL default '',
  des text NOT NULL,
  price text NOT NULL,
  catid int(11) NOT NULL default '0',
  PRIMARY KEY  (pid)
) TYPE=MyISAM;



#
# Table structure for table `CWC_shopstopcat`
#

CREATE TABLE CWC_shopstopcat (
  cid int(11) NOT NULL auto_increment,
  cat varchar(20) NOT NULL default '',
  catdes varchar(50) NOT NULL default '',
  PRIMARY KEY  (cid)
) TYPE=MyISAM;
