CREATE TABLE al_stats (
  id int(10) unsigned NOT NULL auto_increment,
  day date NOT NULL default '0000-00-00',
  site char(16) NOT NULL default '',
  ref char(16) NOT NULL default '',
  hitsin smallint(6) NOT NULL default '0',
  hitsout smallint(6) NOT NULL default '0',
  clicks smallint(6) NOT NULL default '0',
  PRIMARY KEY  (id)
) TYPE=MyISAM;
