# Database: lusoswap
#--------------------------------------------------------
# Server version 3.23.36
#
# Table structure for table 'members'
#

CREATE TABLE members (
  numb int(5) NOT NULL auto_increment,
  id varchar(15) ,
  name varchar(50) ,
  email varchar(75) ,
  tax varchar(25) ,
  address varchar(100) ,
  zip varchar(15) ,
  city varchar(25) ,
  country varchar(50) ,
  tel varchar(15) ,
  password varchar(15) ,
  perm int(2) NOT NULL DEFAULT '1' ,
  ip1 varchar(20) ,
  ip2 varchar(20) ,
  referral varchar(15) ,
  state tinytext ,
  date date DEFAULT '0000-00-00' ,
  PRIMARY KEY (numb)
);


#
# Table structure for table 'payments'
#

CREATE TABLE payments (
  numb int(5) NOT NULL auto_increment,
  id varchar(15) ,
  value varchar(20) ,
  date date DEFAULT '0000-00-00' ,
  PRIMARY KEY (numb)
);


#
# Table structure for table 'transactions'
#

CREATE TABLE transactions (
  numb int(5) NOT NULL auto_increment,
  id varchar(15) ,
  time datetime DEFAULT '0000-00-00 00:00:00' ,
  ip varchar(20) ,
  url varchar(100) ,
  type_desc varchar(25) ,
  master_id varchar(15) ,
  payed tinyint(1) unsigned DEFAULT '0' ,
  value float(5,2) ,
  sale float(5,2) ,
  type tinyint(3) unsigned ,
  invoice varchar(15) ,
  PRIMARY KEY (numb)
);


#
# Table structure for table 'urls'
#

CREATE TABLE urls (
  numb int(5) NOT NULL auto_increment,
  id varchar(15) ,
  url varchar(100) ,
  status tinyint(1) unsigned DEFAULT '0' ,
  PRIMARY KEY (numb)
);


