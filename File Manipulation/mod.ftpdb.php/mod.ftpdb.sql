CREATE TABLE ftpdb (
  id int(4) NOT NULL auto_increment,
  status int(1) unsigned zerofill NOT NULL default '0',
  host varchar(24) NOT NULL default 'unknown',
  port int(5) NOT NULL default '21',
  user varchar(24) NOT NULL default 'anonymous',
  pass varchar(24) NOT NULL default 'email@not.set',
  descr varchar(64) NOT NULL default '',
  PRIMARY KEY  (id)
);
