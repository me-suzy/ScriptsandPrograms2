CREATE TABLE accelgbook (
  ID int(8) NOT NULL auto_increment,
  firstName varchar(30) NOT NULL default '',
  lastName varchar(30) NOT NULL default '',
  country varchar(30) NOT NULL default '',
  email varchar(30) NOT NULL default '',
  website varchar(200) default NULL,
  comment text NOT NULL,
  date varchar(100) NOT NULL default '',
  marker date default '0000-00-00',
  time timestamp(14) NOT NULL,
  PRIMARY KEY  (ID)
) TYPE=MyISAM;
