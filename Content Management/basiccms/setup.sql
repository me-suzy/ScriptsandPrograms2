#
# Table structure for table `pages_t_details`
#

CREATE TABLE `pages_t_details` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `description` mediumtext,
  `startpage` char(1) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

#
# Dumping data for table `pages_t_details`
#

INSERT INTO `pages_t_details` VALUES (1, 'My First Page', '<title>My First Page</title>\r\n<h1>Welcome to my page</h1>\r\n<p>Here is my page!</p>', 'Y');
INSERT INTO `pages_t_details` VALUES (2, 'About this system', '<title>All about this system</title>\r\n<h2><font color="blue">About!</font></h2>', 'N');

# --------------------------------------------------------

#
# Table structure for table `pages_t_users`
#

CREATE TABLE `pages_t_users` (
  `userid` varchar(20) NOT NULL default '',
  `password` varchar(20) default NULL,
  `username` varchar(50) default NULL,
  `email` varchar(50) default NULL,
  `active` char(1) default NULL,
  PRIMARY KEY  (`userid`)
) TYPE=MyISAM;

#
# Dumping data for table `pages_t_users`
#

INSERT INTO `pages_t_users` VALUES ('ADMIN', 'admin', 'admin', 'admin', 'Y');

