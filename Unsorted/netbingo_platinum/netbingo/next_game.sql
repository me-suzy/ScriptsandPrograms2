# phpMyAdmin MySQL-Dump
# http://phpwizard.net/phpMyAdmin/
#
# Host: localhost Database : netbingodb
# --------------------------------------------------------

#
# Table structure for table 'next_game'
#

CREATE TABLE next_game (
   game varchar(32) NOT NULL,
   bingo_game varchar(20) DEFAULT '0' NOT NULL,
   game_seq_no tinyint(4) DEFAULT '0' NOT NULL,
   points int(11) DEFAULT '0' NOT NULL,
   PRIMARY KEY (game)
);

#
# Dumping data for table 'next_game'
#

INSERT INTO next_game VALUES( '30228900', '4corners', '3', '10');
