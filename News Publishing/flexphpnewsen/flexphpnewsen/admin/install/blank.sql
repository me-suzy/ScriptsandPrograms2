CREATE TABLE catalog (
   catalogid bigint(32) NOT NULL auto_increment,
   catalogname varchar(255) NOT NULL,
   description text,
   parentid bigint(32) DEFAULT '0' NOT NULL,
   PRIMARY KEY (catalogid)
);

CREATE TABLE news (
   newsid bigint(32) NOT NULL auto_increment,
   catalogid bigint(32),
   title varchar(255) NOT NULL,
   content text,
   picture varchar(255),
   viewnum bigint(32),
   adddate date,
   rating float,
   ratenum bigint(32),
   source varchar(50),
   sourceurl varchar(50),
   isdisplay tinyint(4) DEFAULT '0' NOT NULL,
   PRIMARY KEY (newsid),
   KEY catalogid (catalogid)
);

CREATE TABLE newsadmin (
   adminid int(10) NOT NULL auto_increment,
   username varchar(50) NOT NULL,
   password varchar(50) NOT NULL,
   PRIMARY KEY (adminid)
);