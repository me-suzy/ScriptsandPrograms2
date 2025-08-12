# phpMyAdmin MySQL-Dump
# version 2.5.1
# http://www.phpmyadmin.net/ (download page)
#
# Host: localhost
# Generation Time: Jul 07, 2003 at 02:28 PM
# Server version: 4.0.13
# PHP Version: 4.2.3
# --------------------------------------------------------

#
# Table structure for table `MDB_appointments`
#
# Creation: Jul 06, 2003 at 06:34 PM
# Last update: Jul 06, 2003 at 10:46 PM
#

CREATE TABLE `MDB_appointments` (
  `A_ID` int(11) NOT NULL auto_increment,
  `date` date NOT NULL default '0000-00-00',
  `time` varchar(20) NOT NULL default '',
  `type` varchar(24) NOT NULL default '',
  `notes` text NOT NULL,
  PRIMARY KEY  (`A_ID`)
) TYPE=MyISAM AUTO_INCREMENT=10 ;
# --------------------------------------------------------

#
# Table structure for table `MDB_contacts`
#
# Creation: Jun 30, 2003 at 09:47 PM
# Last update: Jul 05, 2003 at 11:40 PM
# Last check: Jul 04, 2003 at 03:03 PM
#

CREATE TABLE `MDB_contacts` (
  `C_ID` int(11) NOT NULL auto_increment,
  `first_name` varchar(36) NOT NULL default '',
  `last_name` varchar(36) default NULL,
  `birthday` varchar(36) default NULL,
  `title` varchar(36) default NULL,
  `company` varchar(120) default NULL,
  `email` varchar(180) default NULL,
  `home_phone` varchar(24) default NULL,
  `icq` varchar(24) default NULL,
  `work_phone` varchar(24) default NULL,
  `msn` varchar(24) default NULL,
  `other_phone` varchar(24) default NULL,
  `yahoo` varchar(24) default NULL,
  `cell_phone` varchar(24) default NULL,
  `aim` varchar(24) default NULL,
  `pager` varchar(24) default NULL,
  `website` varchar(255) default NULL,
  `street` varchar(255) default NULL,
  `city` varchar(120) default NULL,
  `state` varchar(120) default NULL,
  `country` varchar(255) default NULL,
  `zip` varchar(24) default NULL,
  `notes` text,
  `group_num` varchar(255) NOT NULL default '0',
  PRIMARY KEY  (`C_ID`)
) TYPE=MyISAM AUTO_INCREMENT=21 ;
# --------------------------------------------------------

#
# Table structure for table `MDB_diary`
#
# Creation: Jul 03, 2003 at 09:01 PM
# Last update: Jul 06, 2003 at 01:09 AM
#

CREATE TABLE `MDB_diary` (
  `D_ID` int(11) NOT NULL auto_increment,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `entry` text NOT NULL,
  PRIMARY KEY  (`D_ID`)
) TYPE=MyISAM AUTO_INCREMENT=12 ;
# --------------------------------------------------------

#
# Table structure for table `MDB_groups`
#
# Creation: Jun 29, 2003 at 11:21 PM
# Last update: Jul 05, 2003 at 11:39 PM
# Last check: Jul 04, 2003 at 03:03 PM
#

CREATE TABLE `MDB_groups` (
  `G_ID` int(11) NOT NULL auto_increment,
  `name` varchar(36) NOT NULL default '',
  PRIMARY KEY  (`G_ID`)
) TYPE=MyISAM AUTO_INCREMENT=16 ;
# --------------------------------------------------------

#
# Table structure for table `MDB_notes`
#
# Creation: Jun 28, 2003 at 09:18 PM
# Last update: Jul 05, 2003 at 11:33 PM
#

CREATE TABLE `MDB_notes` (
  `N_ID` int(11) NOT NULL auto_increment,
  `date` varchar(120) NOT NULL default '0000-00-00',
  `color` int(1) NOT NULL default '0',
  `note` text NOT NULL,
  PRIMARY KEY  (`N_ID`)
) TYPE=MyISAM AUTO_INCREMENT=20 ;
# --------------------------------------------------------

#
# Table structure for table `MDB_reminders`
#
# Creation: Jul 02, 2003 at 10:08 PM
# Last update: Jul 07, 2003 at 12:00 AM
# Last check: Jul 04, 2003 at 03:03 PM
#

CREATE TABLE `MDB_reminders` (
  `R_ID` int(11) NOT NULL auto_increment,
  `subject` varchar(48) NOT NULL default '',
  `message` text NOT NULL,
  `date` date NOT NULL default '0000-00-00',
  `status` int(1) NOT NULL default '0',
  PRIMARY KEY  (`R_ID`)
) TYPE=MyISAM AUTO_INCREMENT=16 ;
# --------------------------------------------------------

#
# Table structure for table `MDB_scheduled_notes`
#
# Creation: Jul 06, 2003 at 09:06 PM
# Last update: Jul 06, 2003 at 10:27 PM
#

CREATE TABLE `MDB_scheduled_notes` (
  `SN_ID` int(11) NOT NULL auto_increment,
  `date` date NOT NULL default '0000-00-00',
  `note` text NOT NULL,
  PRIMARY KEY  (`SN_ID`)
) TYPE=MyISAM AUTO_INCREMENT=9 ;
# --------------------------------------------------------

#
# Table structure for table `MDB_task_updates`
#
# Creation: Jul 05, 2003 at 10:08 AM
# Last update: Jul 07, 2003 at 09:33 AM
#

CREATE TABLE `MDB_task_updates` (
  `TU_ID` int(11) NOT NULL auto_increment,
  `date` date NOT NULL default '0000-00-00',
  `new_update` text NOT NULL,
  `sub` int(12) NOT NULL default '0',
  PRIMARY KEY  (`TU_ID`)
) TYPE=MyISAM AUTO_INCREMENT=6 ;
# --------------------------------------------------------

#
# Table structure for table `MDB_tasks`
#
# Creation: Jul 04, 2003 at 03:02 PM
# Last update: Jul 06, 2003 at 10:42 PM
#

CREATE TABLE `MDB_tasks` (
  `T_ID` int(11) NOT NULL auto_increment,
  `due_date` date NOT NULL default '0000-00-00',
  `inserted` date NOT NULL default '0000-00-00',
  `priority` int(1) NOT NULL default '0',
  `title` varchar(48) NOT NULL default '',
  `task` text NOT NULL,
  `completed` int(1) NOT NULL default '0',
  `email` int(1) NOT NULL default '0',
  PRIMARY KEY  (`T_ID`)
) TYPE=MyISAM AUTO_INCREMENT=19 ;

    