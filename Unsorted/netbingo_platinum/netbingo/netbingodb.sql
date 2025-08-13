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

INSERT INTO game_parallel VALUES( 'standard', '75', '45', '30', '1', 'yes');
INSERT INTO game_parallel VALUES( 'diagonal', '30', '20', '360', '5', 'yes');
INSERT INTO game_parallel VALUES( '4corners', '25', '20', '300', '10', 'yes');
INSERT INTO game_parallel VALUES( 'blackout', '49', '35', '480', '100', 'yes');

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

INSERT INTO game_sequence VALUES( '1', 'standard', '75', '45', '900', '1');
INSERT INTO game_sequence VALUES( '2', 'diagonal', '45', '30', '480', '5');
INSERT INTO game_sequence VALUES( '3', 'standard', '75', '45', '900', '1');
INSERT INTO game_sequence VALUES( '4', '4corners', '30', '20', '360', '10');
INSERT INTO game_sequence VALUES( '5', 'standard', '75', '45', '900', '1');
INSERT INTO game_sequence VALUES( '6', 'blackout', '49', '35', '600', '100');

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


# --------------------------------------------------------
#
# Table structure for table 'rewards'
#

CREATE TABLE rewards (
   level tinyint(4) DEFAULT '0' NOT NULL,
   reward varchar(255)
);


# --------------------------------------------------------
#
# Table structure for table 'sessions'
#

CREATE TABLE sessions (
   seshid char(32) NOT NULL,
   userid char(25),
   lastused int(10) unsigned,
   PRIMARY KEY (seshid)
);


# --------------------------------------------------------
#
# Table structure for table 'user'
#

CREATE TABLE user (
   username varchar(25) NOT NULL,
   password varchar(25) NOT NULL,
   visits int(11) DEFAULT '0' NOT NULL,
   wins int(11) DEFAULT '0' NOT NULL,
   email varchar(50) NOT NULL,
   last_won varchar(20) NOT NULL,
   thisweek int(11) DEFAULT '0' NOT NULL,
   winsthisweek int(11) DEFAULT '0' NOT NULL,
   lastweek int(11) DEFAULT '0' NOT NULL,
   winslastweek int(11) DEFAULT '0' NOT NULL,
   UNIQUE Key_email (email),
   UNIQUE Key_username (username)
);

