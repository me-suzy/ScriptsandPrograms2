CREATE TABLE al_img (
  id smallint(5) unsigned NOT NULL auto_increment,
  type varchar(10) NOT NULL default '',
  login varchar(16) NOT NULL default '',
  format varchar(10) NOT NULL default '',
  extension char(3) NOT NULL default '',
  updated datetime NOT NULL default '0000-00-00 00:00:00',
  rawdata mediumblob NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
