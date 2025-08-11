DROP TABLE IF EXISTS `blogs_cats`;
CREATE TABLE blogs_cats (
  id int(11) NOT NULL auto_increment,
  title varchar(255) default NULL,
  pos varchar(255) default NULL,
  vis varchar(255) default NULL,
  anch int(11),
  PRIMARY KEY  (id)
) TYPE=MyISAM;


DROP TABLE IF EXISTS `blogs_page_block`;
CREATE TABLE blogs_page_block (
  id int(11) NOT NULL auto_increment,
  title blob,
  text blob,
  text_detail blob,
  block_position int(11) default NULL,
  img varchar(255) default NULL,
  img_position varchar(255) default NULL,
  img_visible varchar(255) default NULL,
  page_sub_id int(11) default NULL,
  visible varchar(255) default NULL,
  img_popup varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS `blogs_page_sub_block_sub`;
CREATE TABLE blogs_page_sub_block_sub (
  id int(11) NOT NULL auto_increment,
  title blob,
  text blob,
  text_detail blob,
  block_position int(11) default NULL,
  img varchar(255) default NULL,
  img_popup varchar(255) default NULL,
  img_position varchar(255) default NULL,
  img_visible varchar(255) default NULL,
  block_id int(11) default NULL,
  visible varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;
