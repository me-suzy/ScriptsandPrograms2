CREATE TABLE clients (
   clientid tinyint(4) NOT NULL auto_increment,
   name varchar(50) NOT NULL,
   password varchar(50) NOT NULL,
   email varchar(255) NOT NULL,
   ref varchar(50) NOT NULL,
   title varchar(255) NOT NULL,
   PRIMARY KEY (clientid)
);

#
#
#

INSERT INTO clients VALUES( '1', 'admin', '43e9a4ab75570f5b', 'your@email.com', '', 'admin');

