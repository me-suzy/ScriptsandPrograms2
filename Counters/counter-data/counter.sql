#
# Table structure for table `ips`
#

CREATE TABLE `ips` (
  `ip` varchar(255) NOT NULL default ''
) TYPE=MyISAM;

#
# Dumping data for table `ips`
#

INSERT INTO `ips` VALUES ('127.0.0.1');

# --------------------------------------------------------

#
# Table structure for table `myhits`
#

CREATE TABLE `myhits` (
  `name` varchar(255) NOT NULL default '',
  `hits` varchar(255) NOT NULL default ''
) TYPE=MyISAM;

#
# Dumping data for table `myhits`
#

INSERT INTO `myhits` VALUES ('frontpage', '26');
