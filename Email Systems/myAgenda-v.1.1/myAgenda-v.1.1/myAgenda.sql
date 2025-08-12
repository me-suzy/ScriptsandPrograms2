#############################################################################
# myAgenda v1.1																#
# =============																#
# Copyright (C) 2002  Mesut Tunga - mesut@tunga.com							#
# http://php.tunga.com														#
#																			#
# This program is free software. You can redistribute it and/or modify		#
# it under the terms of the GNU General Public License as published by 		#
# the Free Software Foundation; either version 2 of the License.       		#
#############################################################################

#
# Host: localhost Database : myagenda
#

#
# Table structure for table 'myagenda'
#

DROP TABLE IF EXISTS myagenda_reminders;
CREATE TABLE myagenda_reminders (
	id int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
	uid int(10) unsigned NOT NULL,
	remindtype tinyint(6) UNSIGNED NOT NULL,
	remindday tinyint(3) UNSIGNED NOT NULL,
	reminddate int(11) UNSIGNED NOT NULL,
	remindrepeat tinyint(3) UNSIGNED NOT NULL,
	remindnote varchar(125) NOT NULL,
	date int(11) UNSIGNED NOT NULL,
	PRIMARY KEY (id),
	INDEX (uid, id)
);

#
# Dumping data for table 'myagenda_reminders'
#

#
# Table structure for table 'myagenda_users'
#

DROP TABLE IF EXISTS myagenda_users;
CREATE TABLE myagenda_users (
	uid int(10) unsigned NOT NULL AUTO_INCREMENT,
	name varchar(50) NOT NULL,
	surname varchar(50) NOT NULL,
	email varchar(150) NOT NULL,
	username varchar(16) NOT NULL,
	password varchar(32) NOT NULL,
	sid varchar(32) NOT NULL,
	lastaccess int(10) unsigned NOT NULL,
	added int(10) unsigned NOT NULL,
	PRIMARY KEY (uid),
	UNIQUE (username),
	INDEX (uid)
);

#
# Dumping data for table 'myagenda_users'
#

