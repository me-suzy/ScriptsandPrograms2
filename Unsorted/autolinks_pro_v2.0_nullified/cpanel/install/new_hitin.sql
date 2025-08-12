CREATE TABLE al_hitin (
  id bigint(20) unsigned NOT NULL auto_increment,
  sent datetime NOT NULL default '0000-00-00 00:00:00',
  site varchar(16) NOT NULL default '',
  ref varchar(16) NOT NULL default '',
  ip varchar(15) NOT NULL default '',
  host varchar(40) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;
