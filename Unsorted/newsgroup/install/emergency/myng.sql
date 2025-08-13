# MyNewsGroups :) Emergency SQL schema v 0.6
# --------------------------------------------

#
# Table structure for table `myng_admin`
#

CREATE TABLE `myng_admin` (
  `adm_id` smallint(5) unsigned NOT NULL auto_increment,
  `adm_login` varchar(20) NOT NULL default '',
  `adm_passwd` varchar(20) NOT NULL default '',
  `adm_email` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`adm_id`)
) TYPE=MyISAM;

#
# Table structure for table `myng_config`
#

CREATE TABLE `myng_config` (
  `conf_id` tinyint(3) unsigned NOT NULL auto_increment,
  `conf_active_yn` char(1) NOT NULL default '',
  `conf_name` varchar(100) NOT NULL default '',
  `conf_description` tinytext NOT NULL,
  `conf_system_prefix` varchar(50) NOT NULL default '',
  `conf_system_root` varchar(100) NOT NULL default '',
  `conf_system_language` char(2) NOT NULL default '',
  `conf_system_zlib_yn` char(1) NOT NULL default '',
  `conf_system_debug_yn` char(1) NOT NULL default '',
  `conf_system_login_yn` char(1) NOT NULL default '',
  `conf_system_online_yn` char(1) NOT NULL default '',
  `conf_down_days` tinyint(3) unsigned NOT NULL default '0',
  `conf_down_list_items` mediumint(8) unsigned NOT NULL default '0',
  `conf_down_num_groups` tinyint(3) unsigned NOT NULL default '0',
  `conf_down_num_articles` tinyint(3) unsigned NOT NULL default '0',
  `conf_clean_MAX_days` tinyint(3) unsigned NOT NULL default '0',
  `conf_clean_MAX_articles` int(10) unsigned NOT NULL default '0',
  `conf_vis_theme` varchar(50) NOT NULL default '',
  `conf_vis_num_2_flames` mediumint(8) unsigned NOT NULL default '0',
  `conf_vis_articles_x_page` mediumint(8) unsigned NOT NULL default '0',
  `conf_vis_nav_bar_items` mediumint(3) unsigned NOT NULL default '0',
  `conf_vis_nav_bar_pages` mediumint(3) unsigned NOT NULL default '0',
  `conf_vis_time_highlight_new` mediumint(8) unsigned NOT NULL default '0',
  `conf_sec_protect_email_yn` char(1) NOT NULL default '',
  `conf_sec_send_poster_host_yn` char(1) NOT NULL default '',
  `conf_sec_test_group_yn` char(1) NOT NULL default '',
  `conf_sec_validate_email_yn` char(1) NOT NULL default '',
  `conf_sec_secret_string` tinytext NOT NULL,
  PRIMARY KEY  (`conf_id`)
) TYPE=MyISAM;

#
# Dumping data for table `myng_config`
#

INSERT INTO `myng_config` (`conf_id`, `conf_active_yn`, `conf_name`, `conf_description`, `conf_system_prefix`, `conf_system_root`, `conf_system_language`, `conf_system_zlib_yn`, `conf_system_debug_yn`, `conf_system_login_yn`, `conf_system_online_yn`, `conf_down_days`, `conf_down_list_items`, `conf_down_num_groups`, `conf_down_num_articles`, `conf_clean_MAX_days`, `conf_clean_MAX_articles`, `conf_vis_theme`, `conf_vis_num_2_flames`, `conf_vis_articles_x_page`, `conf_vis_nav_bar_items`, `conf_vis_nav_bar_pages`, `conf_vis_time_highlight_new`, `conf_sec_protect_email_yn`, `conf_sec_send_poster_host_yn`, `conf_sec_test_group_yn`, `conf_sec_validate_email_yn`, `conf_sec_secret_string`) VALUES (5, 'Y', 'Default', 'Default Configuration', '/MyNewsGroups/', '/www/MyNewsGroups/', 'en', 'N', 'Y', 'Y', 'Y', 30, 10, 3, 4, 0, 0, 'standard', 50, 0, 10, 5, 7200, 'Y', 'Y', 'Y', 'Y', 'howmuchwoodwouldawoodchuck');

#
# Table structure for table `myng_cron`
#

CREATE TABLE `myng_cron` (
  `cron_id` int(10) unsigned NOT NULL auto_increment,
  `cron_num_times` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cron_id`)
) TYPE=MyISAM;

#
# Dumping data for table `myng_cron`
#

# --------------------------------------------------------

#
# Table structure for table `myng_library`
#

CREATE TABLE `myng_library` (
  `lib_art_id` bigint(20) unsigned NOT NULL default '0',
  `lib_grp_id` int(10) unsigned NOT NULL default '0',
  `lib_usr_id` int(10) unsigned NOT NULL default '0',
  `lib_times` tinyint(4) NOT NULL default '1',
  `lib_my_article` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`lib_art_id`,`lib_grp_id`,`lib_usr_id`)
) TYPE=MyISAM;


#
# Table structure for table `myng_newsgroup`
#

CREATE TABLE `myng_newsgroup` (
  `grp_id` int(10) unsigned NOT NULL auto_increment,
  `grp_name` varchar(150) NOT NULL default '',
  `grp_description` text NOT NULL,
  `grp_num_messages` int(10) unsigned NOT NULL default '0',
  `grp_first_article` int(10) unsigned default NULL,
  `grp_last_article` int(10) unsigned default NULL,
  `grp_num_available` int(10) unsigned NOT NULL default '0',
  `grp_MAX_days` tinyint(3) unsigned NOT NULL default '0',
  `grp_MAX_articles` int(10) unsigned NOT NULL default '0',
  `grp_serv_id` int(10) unsigned NOT NULL default '0',
  `grp_allow_post_yn` char(1) NOT NULL default '',
  `grp_activity_index` float unsigned NOT NULL default '0',
  PRIMARY KEY  (`grp_id`)
) TYPE=MyISAM;


#
# Table structure for table `myng_server`
#

CREATE TABLE `myng_server` (
  `serv_id` mediumint(8) unsigned NOT NULL auto_increment,
  `serv_host` varchar(60) NOT NULL default '',
  `serv_port` smallint(5) unsigned NOT NULL default '119',
  `serv_login` varchar(15) NOT NULL default '',
  `serv_passwd` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`serv_id`)
) TYPE=MyISAM;

#
# Table structure for table `myng_subscription`
#

CREATE TABLE `myng_subscription` (
  `subs_id` int(10) unsigned NOT NULL auto_increment,
  `subs_grp_id` int(10) unsigned NOT NULL default '0',
  `subs_usr_id` int(10) unsigned NOT NULL default '0',
  `subs_last_article` int(10) unsigned default '0',
  `subs_last_article_timestamp` int(10) unsigned NOT NULL default '0',
  `subs_posted` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`subs_id`)
) TYPE=MyISAM;


#
# Table structure for table `myng_user`
#

CREATE TABLE `myng_user` (
  `usr_id` int(20) unsigned NOT NULL auto_increment,
  `usr_name` varchar(50) NOT NULL default '',
  `usr_passwd` varchar(32) NOT NULL default '',
  `usr_email` varchar(100) NOT NULL default '',
  `usr_email_visible_yn` char(1) NOT NULL default '',
  `usr_fst_name` varchar(20) default NULL,
  `usr_lst_name` varchar(20) default NULL,
  `usr_country` char(2) default NULL,
  `usr_icq` int(10) unsigned default NULL,
  `usr_theme` varchar(20) default NULL,
  `usr_text` tinytext,
  `usr_reg_timestamp` int(10) default NULL,
  `usr_last_log_timestamp` int(10) default NULL,
  `usr_num_logs` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`usr_id`),
  UNIQUE KEY `email` (`usr_email`)
) TYPE=MyISAM;


#
# Table structure for table `myng_user_online`
#

CREATE TABLE `myng_user_online` (
  `uonl_usr_name` varchar(30) NOT NULL default '',
  `uonl_session_time` bigint(10) NOT NULL default '0',
  `uonl_message_inbox` tinytext,
  `uonl_chat_room` varchar(20) default NULL,
  `uonl_message_time` bigint(20) default '0',
  `uonl_message_from` varchar(20) default NULL,
  PRIMARY KEY  (`uonl_usr_name`)
) TYPE=MyISAM;