# MySQL-Front Dump 2.5
#
# Host: localhost   Database: phpdbform
# --------------------------------------------------------
# Server version 3.23.57-max-nt


#
# Table structure for table 'contact'
#

CREATE TABLE contact (
  cod int(10) unsigned NOT NULL auto_increment,
  name varchar(30) NOT NULL default '',
  email varchar(50) NOT NULL default '',
  sex varchar(6) NOT NULL default 'male',
  type int(10) unsigned NOT NULL default '0',
  obs text NOT NULL,
  active enum('Y','N') NOT NULL default 'N',
  os enum('linux','windows','unix') NOT NULL default 'linux',
  birthday date NOT NULL default '0000-00-00',
  value tinyint(3) unsigned default NULL,
  address varchar(30) default NULL,
  nb int(10) unsigned default NULL,
  PRIMARY KEY  (cod),
  UNIQUE KEY email (email)
) TYPE=MyISAM;



#
# Dumping data for table 'contact'
#

INSERT INTO contact VALUES("1", "Paulo Assis", "paulo@coral.srv.br", "male", "7", "Creator of the wonderfull phpDBform script.", "N", "windows", "1973-01-25", "10", "Here", "0");
INSERT INTO contact VALUES("2", "Eric KASTLER", "free.sites@surlewoueb.com", "male", "6", "French froggy. Has translated phpDBform in French and made the french mirror site at http://phpdbform.surlewoueb.com", "N", "linux", "0000-00-00", "10", "endereço da casa é", NULL);
INSERT INTO contact VALUES("3", "Marcin Chojnowski", "martii@obgyn.edu.pl", "male", "2", "Has translated phpDBform in Polish and added support for charset", "N", "windows", "0000-00-00", "10", NULL, NULL);
INSERT INTO contact VALUES("4", "Roberto Rosario", "skeletor@iname.com", "male", "5", "Implemented a couple of cool features for phpdbform. Support for combo boxes with fixed list and listing 2 or more fields in the select form.", "N", "linux", "0000-00-00", "10", "Aonde eu moro?", "0");
INSERT INTO contact VALUES("5", "Tom Vander Aa", "Tom.VanderAa@esat.kuleuven.ac.be", "male", "2", "Added support for PostgreSQL database in phpdbform. Now phpdbform works with two databases!", "N", "linux", "0000-00-00", "10", NULL, NULL);
INSERT INTO contact VALUES("6", "Other person", "nobody@nowhere.com.xx", "male", "1", "I don\'t know... This is a test. Perhaps it can be you in the next release ;-)", "Y", "linux", "0000-00-00", "10", "", "0");


#
# Table structure for table 'photos'
#

CREATE TABLE photos (
  cod int(11) NOT NULL auto_increment,
  name varchar(30) NOT NULL default '',
  image blob,
  PRIMARY KEY  (cod)
) TYPE=MyISAM;



#
# Dumping data for table 'photos'
#



#
# Table structure for table 'type'
#

CREATE TABLE type (
  cod int(11) NOT NULL auto_increment,
  type varchar(20) NOT NULL default '',
  business tinyint(3) unsigned NOT NULL default '0',
  address varchar(30) default NULL,
  nb int(10) unsigned default NULL,
  PRIMARY KEY  (cod)
) TYPE=MyISAM;



#
# Dumping data for table 'type'
#

INSERT INTO type VALUES("1", "Personal", "0", "Sei lá", NULL);
INSERT INTO type VALUES("2", "Business", "1", "Teste teste", "12345");
INSERT INTO type VALUES("3", "School", "0", "Não estudo mais?", NULL);
INSERT INTO type VALUES("4", "Eventos", "0", "aonde tem eventos?", NULL);
INSERT INTO type VALUES("5", "Home", "0", "Aonde eu moro?", NULL);
INSERT INTO type VALUES("6", "Casa", "0", "endereço da casa", "54321");
INSERT INTO type VALUES("7", "Escola", "1", "endereço da escola", NULL);
