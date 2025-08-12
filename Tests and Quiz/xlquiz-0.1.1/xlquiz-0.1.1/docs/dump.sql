
CREATE TABLE `results` (
  `result_id` int(10) unsigned NOT NULL auto_increment,
  `result_test_id` varchar(255) NOT NULL default '',
  `result_score` int(4) unsigned NOT NULL default '0',
  `result_user_name` varchar(60) NOT NULL default '',
  `result_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `result_time` int(6) NOT NULL default '0',
  `result_ip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`result_id`)
) TYPE=MyISAM;
