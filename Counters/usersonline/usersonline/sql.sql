#Created by Razore



#
# Table structure for table 'online'
#

CREATE TABLE online (
   timestamp int(15) DEFAULT '0' NOT NULL,
   ip varchar(40) NOT NULL,
   file varchar(100) NOT NULL,
   PRIMARY KEY (timestamp),
   KEY ip (ip),
   KEY file (file)
);
