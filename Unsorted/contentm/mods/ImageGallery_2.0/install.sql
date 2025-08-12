DROP TABLE IF EXISTS mod_imagegallery;
CREATE TABLE mod_imagegallery (  id varchar(50) default NULL,  filename varchar(50) default NULL,  caption text) TYPE=MyISAM;
DROP TABLE IF EXISTS mod_imggallery_config;
CREATE TABLE mod_imagegallery_config (  img_per_page int(3) default NULL,  img_per_row int(3) default NULL,  show_size int(3) default NULL,  show_type int(3) default NULL,  show_caption int(3) default NULL,  use_thumbs int(3) default NULL,  thumbs_scale int(3) default NULL,  use_zoom int(3) default NULL) TYPE=MyISAM;
INSERT INTO mod_imagegallery_config VALUES("4", "2", "1", "1", "1", "1", "2", "1");
