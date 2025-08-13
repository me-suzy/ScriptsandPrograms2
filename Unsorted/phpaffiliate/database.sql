# phpMyAdmin MySQL-Dump
#
# Table structure for table `admin`
#

DROP TABLE IF EXISTS admin;
CREATE TABLE admin (
  user varchar(100) NOT NULL default '',
  pass varchar(100) NOT NULL default ''
) TYPE=MyISAM;
 
INSERT INTO admin VALUES ( 'admin', 'pass456'); 

# --------------------------------------------------------

#
# Table structure for table `affiliates`
#

DROP TABLE IF EXISTS affiliates;
CREATE TABLE affiliates (
  refid varchar(30) NOT NULL default '',
  pass varchar(20) NOT NULL default '',
  company varchar(100) NOT NULL default '',
  title varchar(5) NOT NULL default '',
  firstname varchar(40) NOT NULL default '',
  lastname varchar(40) NOT NULL default '',
  website varchar(100) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  payableto varchar(100) NOT NULL default '',
  street varchar(100) NOT NULL default '',
  town varchar(100) NOT NULL default '',
  county varchar(100) NOT NULL default '',
  postcode varchar(20) NOT NULL default '',
  country varchar(100) NOT NULL default '',
  phone varchar(30) NOT NULL default '',
  fax varchar(30) NOT NULL default '',
  date varchar(40) NOT NULL default ''
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `banners`
#

DROP TABLE IF EXISTS banners;
CREATE TABLE banners (
  number int(3) NOT NULL auto_increment,
  name varchar(50) NOT NULL default '',
  image varchar(60) NOT NULL default '',
  description varchar(200) NOT NULL default '',
  PRIMARY KEY  (number)
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `clickthroughs`
#

DROP TABLE IF EXISTS clickthroughs;
CREATE TABLE clickthroughs (
  refid varchar(20) default 'none',
  date date NOT NULL default '0000-00-00',
  time time NOT NULL default '00:00:00',
  browser varchar(200) default 'Could Not Find This Data',
  ipaddress varchar(50) default 'Could Not Find This Data',
  refferalurl varchar(200) default 'none detected (maybe a direct link)',
  buy varchar(10) default 'NO'
) TYPE=MyISAM;
# --------------------------------------------------------

#
# Table structure for table `sales`
#

DROP TABLE IF EXISTS sales;
CREATE TABLE sales (
  refid varchar(20) NOT NULL default '',
  date date NOT NULL default '0000-00-00',
  time time NOT NULL default '00:00:00',
  browser varchar(100) NOT NULL default '',
  ipaddress varchar(20) NOT NULL default '',
  payment varchar(10) NOT NULL default ''
) TYPE=MyISAM;

    
