

CREATE TABLE IF NOT EXISTS ###scheme###dev_###name### (
  entry_id         int(11)     NOT NULL auto_increment,
  is_dir 		   char(1)     NOT NULL default '0',
  parent           int(11)     NOT NULL default '0',
  headline         varchar(50),
  content          text,
  KEY parent_key (parent),
  PRIMARY KEY  (entry_id)
) TYPE=MyISAM;

CREATE TABLE IF NOT EXISTS ###scheme###dev_metainfo (
  object_type  varchar(20) NOT NULL,
  object_id    int(11)     NOT NULL,
  creator      int(11)     NOT NULL,
  state        int(11)     NOT NULL,
  created      datetime    NOT NULL,
  last_changer int(11),
  last_change  datetime,
  access_level varchar(10) NOT NULL default '-rwx------',
  PRIMARY KEY  (object_type, object_id),
  FOREIGN KEY (owner) REFERENCES users(id)
) TYPE=MyISAM;