# phpMyAdmin MySQL-Dump
# http://phpwizard.net/phpMyAdmin/
#
# Host: localhost Database : netbingodb
# --------------------------------------------------------

#
# Table structure for table 'game_sequence'
#

CREATE TABLE game_sequence (
   sequence tinyint(4) DEFAULT '0' NOT NULL auto_increment,
   game varchar(20) NOT NULL,
   ball_limit tinyint(4) DEFAULT '75' NOT NULL,
   computer_trigger tinyint(4) DEFAULT '0' NOT NULL,
   game_interval int(10) DEFAULT '0' NOT NULL,
   points int(11) DEFAULT '0' NOT NULL,
   KEY sequence (sequence)
);

#
# Dumping data for table 'game_sequence'
#

INSERT INTO game_sequence VALUES( '1', 'standard', '75', '45', '900', '1');
INSERT INTO game_sequence VALUES( '2', 'diagonal', '45', '30', '480', '5');
INSERT INTO game_sequence VALUES( '3', 'standard', '75', '45', '900', '1');
INSERT INTO game_sequence VALUES( '4', '4corners', '30', '20', '360', '10');
INSERT INTO game_sequence VALUES( '5', 'standard', '75', '45', '900', '1');
INSERT INTO game_sequence VALUES( '6', 'blackout', '49', '35', '600', '100');
