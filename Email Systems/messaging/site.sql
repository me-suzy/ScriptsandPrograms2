CREATE TABLE `site_mb_msg` (
  `msg_id` int(11) NOT NULL auto_increment,
  `msg_type` int(1) NOT NULL default '0',
  `msg_user` int(11) NOT NULL default '0',
  `msg_date` int(11) NOT NULL default '0',
  `msg_title` varchar(200) NOT NULL default '',
  `msg_from` int(11) NOT NULL default '0',
  `msg_to` int(11) NOT NULL default '0',
  `msg_body` longtext NOT NULL,
  `msg_new` int(1) NOT NULL default '0',
  `msg_delete` int(1) NOT NULL default '0',
  PRIMARY KEY  (`msg_id`)
) TYPE=MyISAM AUTO_INCREMENT=94 ;

CREATE TABLE `site_user_notes` (
  `note_id` int(11) NOT NULL auto_increment,
  `note_title` varchar(200) NOT NULL default '',
  `note_body` text NOT NULL,
  `note_relation` int(11) NOT NULL default '0',
  `note_type` int(1) NOT NULL default '0',
  `note_post_date` int(11) NOT NULL default '0',
  `note_post_ip` varchar(20) NOT NULL default '',
  `note_post_user` int(11) NOT NULL default '0',
  PRIMARY KEY  (`note_id`)
) TYPE=MyISAM AUTO_INCREMENT=3 ;

INSERT INTO `site_user_notes` VALUES (2, 'Note about Joe', 'Joe works in the R and D area.', 1003, 0, 1129213762, '127.0.0.1', 1);


CREATE TABLE `site_users` (
  `user_id` int(11) NOT NULL auto_increment,
  `user_login` varchar(30) NOT NULL default '',
  `user_password` varchar(30) NOT NULL default '',
  `user_name` varchar(200) NOT NULL default '',
  `user_address` varchar(200) NOT NULL default '',
  `user_city` varchar(100) NOT NULL default '',
  `user_state` char(3) NOT NULL default '',
  `user_zip` varchar(20) NOT NULL default '',
  `user_country` char(3) NOT NULL default '',
  `user_phone` varchar(39) NOT NULL default '',
  `user_email` varchar(200) NOT NULL default '',
  `user_email2` varchar(200) NOT NULL default '',
  `user_im_aol` varchar(100) NOT NULL default '',
  `user_im_icq` varchar(100) NOT NULL default '',
  `user_im_msn` varchar(100) NOT NULL default '',
  `user_im_yahoo` varchar(100) NOT NULL default '',
  `user_im_other` varchar(200) NOT NULL default '',
  `user_status` int(1) NOT NULL default '0',
  `user_level` int(1) NOT NULL default '0',
  `user_pending` int(11) NOT NULL default '0',
  `user_date` int(11) NOT NULL default '0',
  `last_login` int(11) NOT NULL default '0',
  `last_ip` varchar(20) NOT NULL default '',
  `user_msg_send` int(1) NOT NULL default '0',
  `user_msg_subject` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`user_id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=1004 ;

INSERT INTO `site_users` VALUES (1, 'admin', 'test', 'Site Admin', '', '', '', '', '', '', 'admin@example.com', 'someone@example.net', '', '', '', '', '', 0, 0, 0, 0, 1129213723, '127.0.0.1', 1, 'New Message');
INSERT INTO `site_users` VALUES (1003, 'Joe', 'joe', 'Joe User', '444 West Main', 'San Diego', 'CA', '', 'US', '', 'joe@example.com', 'joe@yahoo-example.com', 'aol', 'icq', 'msn', 'yahoo', 'other', 0, 1, 0, 1106772292, 1129212352, '127.0.0.1', 1, 'New Message');

CREATE TABLE `site_vars` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL default '',
  `value` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=36 ;