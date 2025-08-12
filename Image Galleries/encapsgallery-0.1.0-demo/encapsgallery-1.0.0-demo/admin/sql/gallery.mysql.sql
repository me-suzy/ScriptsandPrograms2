DROP TABLE IF EXISTS `encapsgallery_test_table`;
CREATE TABLE `encapsgallery_test_table` (`test_field` varchar(255)) ;
INSERT INTO encapsgallery_test_table VALUES('test_value');

DROP TABLE IF EXISTS encapsgallery_category;
CREATE TABLE encapsgallery_category (
  id int(11) NOT NULL auto_increment,
  title text,
  pos int(11) default NULL,
  vis_anons VARCHAR(80),
  PRIMARY KEY  (id)
) TYPE=MyISAM;

INSERT INTO encapsgallery_category VALUES (1,'Default',1,'checked');

#
# Table structure for table `encapsgallery`
#

DROP TABLE IF EXISTS `encapsgallery`;
CREATE TABLE `encapsgallery` (
  `id` int(11) NOT NULL auto_increment,
  `filename_normal` varchar(255) default NULL,
  `position` int(11) default NULL,
  `visible` varchar(255) default NULL,
  `title` varchar(255) default NULL,
  `comment` varchar(255) default NULL,
  `cat` int(11),
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;

#
# Dumping data for table `encapsgallery`
#

