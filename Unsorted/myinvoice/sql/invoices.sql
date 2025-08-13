CREATE TABLE invoices (
   id int(8) NOT NULL auto_increment,
   clientid tinyint(4) NOT NULL,
   date date,
   details text NOT NULL,
   total varchar(50) NOT NULL,
   status enum('pending','paid') DEFAULT 'pending' NOT NULL,
   PRIMARY KEY (id)
);
