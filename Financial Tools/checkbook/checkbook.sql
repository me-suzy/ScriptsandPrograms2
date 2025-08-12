# phpMyAdmin MySQL-Dump
# version 2.5.1
# http://www.phpmyadmin.net/ (download page)
#
# Host: 209.50.230.152
# Generation Time: Jul 19, 2004 at 07:49 PM
# Server version: 3.23.54
# PHP Version: 4.3.4
# Database : `cephlon_default`
# --------------------------------------------------------

#
# Table structure for table `checkbook`
#
# Creation: May 17, 2004 at 09:30 PM
# Last update: May 17, 2004 at 09:30 PM
#

CREATE TABLE `checkbook` (
  `entry` mediumint(50) NOT NULL auto_increment,
  `date` varchar(200) NOT NULL default '',
  `number` varchar(200) NOT NULL default '',
  `subject` varchar(200) NOT NULL default '',
  `payee` varchar(200) NOT NULL default '',
  `withdrawal` varchar(200) NOT NULL default '',
  `deposit` varchar(200) NOT NULL default '',
  `balance` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`entry`)
) TYPE=MyISAM AUTO_INCREMENT=80 ;

