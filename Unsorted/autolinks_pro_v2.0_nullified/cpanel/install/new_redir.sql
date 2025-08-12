CREATE TABLE al_redir (
  id smallint(5) unsigned NOT NULL auto_increment,
  site char(16) NOT NULL default '',
  ref char(16) NOT NULL default '',
  url char(100) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;