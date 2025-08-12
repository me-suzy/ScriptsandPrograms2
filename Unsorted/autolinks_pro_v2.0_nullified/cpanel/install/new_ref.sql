CREATE TABLE al_ref (
  id int(10) unsigned NOT NULL auto_increment,
  login varchar(16) NOT NULL default '',
  password varchar(16) NOT NULL default '',
  name varchar(32) NOT NULL default '',
  url varchar(150) NOT NULL default '',
  description varchar(255) NOT NULL default '',
  thumb varchar(32) NOT NULL default '/',
  email varchar(50) NOT NULL default '',
  status int(10) NOT NULL default '0',
  added date NOT NULL default '0000-00-00',
  category tinyint(3) unsigned NOT NULL default '1',
  fromsite varchar(16) NOT NULL default '',
  code varchar(8) NOT NULL default '',
  PRIMARY KEY  (id),
  UNIQUE KEY login (login)
) TYPE=MyISAM;
