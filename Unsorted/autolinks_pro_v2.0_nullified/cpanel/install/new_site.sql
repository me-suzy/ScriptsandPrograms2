CREATE TABLE al_site (
  id tinyint(3) unsigned NOT NULL auto_increment,
  login varchar(16) NOT NULL default '',
  name varchar(32) NOT NULL default '',
  url varchar(75) NOT NULL default '',
  alurl varchar(100) NOT NULL default '',
  status int(10) NOT NULL default '1',
  categories set('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30') NOT NULL default '1',
  added date NOT NULL default '0000-00-00',
  nextupdate datetime NOT NULL default '0000-00-00 00:00:00',
  updinterval mediumint(9) NOT NULL default '15',
  PRIMARY KEY  (id)
) TYPE=MyISAM;
