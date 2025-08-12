# wpBlog SQL Structure
# phpMyAdmin MySQL-Dump

CREATE TABLE wpgblogs (
  id int(11) NOT NULL auto_increment,
  title text NOT NULL,
  content text NOT NULL,
  date timestamp(14) NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;