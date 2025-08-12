CREATE TABLE ###TABLE_PREFIX###offers (
  offer_id         int(11)     NOT NULL auto_increment,
  is_dir 		   char(1)     NOT NULL default '0',
  parent           int(11)     NOT NULL default '0',
  headline         varchar(50),
  content          text,
  description      text,
  details          text,
  followup         date,
  due              date,
  starts           date,
  done             int(3)      NOT NULL default  '0',
  state            int(11)     NOT NULL default '0',
  KEY parent_key (parent),
  PRIMARY KEY  (offer_id)
) TYPE=MyISAM;
