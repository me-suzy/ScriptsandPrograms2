# phpMyAdmin MySQL-Dump
# http://phpwizard.net/phpMyAdmin/
#
# Host: localhost Database : netbingodb
# --------------------------------------------------------

#
# Table structure for table 'game_parallel'
#

CREATE TABLE game_parallel (
   game varchar(20) NOT NULL,
   ball_limit tinyint(4) DEFAULT '75' NOT NULL,
   computer_trigger tinyint(4) DEFAULT '0' NOT NULL,
   game_interval int(10) DEFAULT '0' NOT NULL,
   points int(11) DEFAULT '0' NOT NULL,
   enabled char(3) NOT NULL,
   PRIMARY KEY (game)
);

#
# Dumping data for table 'game_parallel'
#

INSERT INTO game_parallel VALUES( 'standard', '75', '45', '900', '1', 'yes');
INSERT INTO game_parallel VALUES( 'diagonal', '30', '20', '360', '5', 'yes');
INSERT INTO game_parallel VALUES( '4corners', '25', '20', '300', '10', 'yes');
INSERT INTO game_parallel VALUES( 'blackout', '49', '35', '480', '100', 'yes');
