# phpMyAdmin MySQL-Dump
# http://phpwizard.net/phpMyAdmin/
#
# Host: localhost Database : netbingodb

# --------------------------------------------------------
#
# Table structure for table 'acls'
#

CREATE TABLE acls (
   bannerID mediumint(9) DEFAULT '0' NOT NULL,
   acl_type enum('clientip','useragent','weekday') DEFAULT 'clientip' NOT NULL,
   acl_data varchar(255) NOT NULL,
   acl_ad set('allow','deny') NOT NULL,
   acl_order int(10) unsigned DEFAULT '0' NOT NULL,
   KEY bannerID (bannerID),
   UNIQUE bannerID_2 (bannerID, acl_order)
);


# --------------------------------------------------------
#
# Table structure for table 'adclicks'
#

CREATE TABLE adclicks (
   bannerID mediumint(9) DEFAULT '0' NOT NULL,
   t_stamp timestamp(14),
   host varchar(255) NOT NULL,
   KEY clientID (bannerID)
);


# --------------------------------------------------------
#
# Table structure for table 'adviews'
#

CREATE TABLE adviews (
   bannerID mediumint(9) DEFAULT '0' NOT NULL,
   t_stamp timestamp(14),
   host varchar(255) NOT NULL,
   KEY clientID (bannerID)
);


# --------------------------------------------------------
#
# Table structure for table 'banners'
#

CREATE TABLE banners (
   bannerID mediumint(9) DEFAULT '0' NOT NULL auto_increment,
   clientID mediumint(9) DEFAULT '0' NOT NULL,
   banner blob NOT NULL,
   width smallint(6) DEFAULT '0' NOT NULL,
   height smallint(6) DEFAULT '0' NOT NULL,
   format enum('gif','jpeg','png','html','url') DEFAULT 'gif' NOT NULL,
   url varchar(255) NOT NULL,
   alt varchar(255) NOT NULL,
   keyword varchar(255) NOT NULL,
   bannertext varchar(255) NOT NULL,
   active enum('true','false') DEFAULT 'true' NOT NULL,
   seq tinyint(4) DEFAULT '0' NOT NULL,
   target varchar(8) NOT NULL,
   PRIMARY KEY (bannerID)
);


# --------------------------------------------------------
#
# Table structure for table 'clients'
#

CREATE TABLE clients (
   clientID mediumint(9) DEFAULT '0' NOT NULL auto_increment,
   clientname varchar(255) NOT NULL,
   contact varchar(255),
   email varchar(64) NOT NULL,
   views mediumint(9),
   clicks mediumint(9),
   clientusername varchar(64) NOT NULL,
   clientpassword varchar(64) NOT NULL,
   expire date DEFAULT '0000-00-00',
   PRIMARY KEY (clientID)
);


# --------------------------------------------------------
#
# Table structure for table 'phpAdsessions'
#

CREATE TABLE phpAdsessions (
   SessionID varchar(32) NOT NULL,
   SessionData blob NOT NULL,
   LastUsed timestamp(14),
   PRIMARY KEY (SessionID)
);


# --------------------------------------------------------
#
# Table structure for table 'purchase'
#

CREATE TABLE purchase (
   email varchar(50) NOT NULL,
   product varchar(10) NOT NULL,
   installation char(3) NOT NULL,
   amount decimal(10,2) DEFAULT '0.00' NOT NULL,
   sale_completed char(3),
   payment_type char(2),
   date varchar(20),
   tran_id smallint(6) DEFAULT '0' NOT NULL auto_increment,
   name varchar(50) NOT NULL,
   banner char(3) NOT NULL,
   banner_installation char(3) NOT NULL,
   URL varchar(100) NOT NULL,
   UNIQUE tran_id (tran_id)
);




