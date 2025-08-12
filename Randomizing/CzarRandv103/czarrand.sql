CREATE TABLE random_quotes (
id tinyint(3) NOT NULL auto_increment,
quote longtext NOT NULL,
author varchar(255) NOT NULL default '',
PRIMARY KEY (id)
) TYPE=MyISAM;

INSERT INTO random_quotes VALUES (1, '"42.7 percent of all statistics are made up on the spot."', 'The Hon. W. Richard Walton, Sr.');
INSERT INTO random_quotes VALUES (2, '"I\'m not repetative. I don\'t repeat myself."', 'Anonymous');
