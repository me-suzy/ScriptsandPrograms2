DROP TABLE IF EXISTS mod_linksportal;
CREATE TABLE mod_linksportal (  id varchar(50) default NULL,  category varchar(50) default NULL,  title varchar(50) default NULL,  description text,  url varchar(50) default NULL) TYPE=MyISAM;
DROP TABLE IF EXISTS mod_linksportal_category;
CREATE TABLE mod_linksportal_category (  id varchar(50) default NULL,  category_title varchar(50) default NULL,  category_desc text) TYPE=MyISAM;