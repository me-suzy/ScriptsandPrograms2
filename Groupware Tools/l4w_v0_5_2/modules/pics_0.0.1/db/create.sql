CREATE TABLE ###TABLE_PREFIX###pics (
  pic_id         int(11)     NOT NULL auto_increment,
  is_dir 		   char(1)     NOT NULL default '0',
  parent           int(11)     NOT NULL default '0',
  headline         varchar(50),
  description      text,
  pic              text,
  KEY parent_key (parent),
  PRIMARY KEY  (pic_id)
) TYPE=MyISAM;
