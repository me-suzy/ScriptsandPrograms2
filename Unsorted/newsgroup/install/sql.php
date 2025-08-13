<?
// ---------------------------------------------------------------------------- //
// MyNewsGroups :) 'Share your knowledge'
// Copyright (C) 2002 Carlos Sánchez Valle (yosoyde@bilbao.com)

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
// ---------------------------------------------------------------------------- //

//------------------------------------------------------------------//
// sql.php
// Author: Carlos Sánchez
// Created: 17/09/02
//
// Description: Home Page
//
// Builds the required Databases
//
//------------------------------------------------------------------//
?>
<?


//------------ Create the System tables --------------- //




// ------------------ myng_admin --------------- //
$sql_query =

"CREATE TABLE `myng_admin` (
	`adm_id` smallint(5) unsigned NOT NULL auto_increment,
  	`adm_login` varchar(20) NOT NULL default '',
  	`adm_passwd` varchar(20) NOT NULL default '',
  	`adm_email` varchar(100) NOT NULL default '',
  	PRIMARY KEY  (`adm_id`)
)";

$db->query($sql_query);


// ------------------ myng_config --------------- //

$sql_query =

"CREATE TABLE `myng_config` (
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
  `conf_vis_nav_bar_items` tinyint(3) unsigned NOT NULL default '0',
  `conf_vis_nav_bar_pages` tinyint(3) unsigned NOT NULL default '0',
  `conf_vis_time_highlight_new` mediumint(8) unsigned NOT NULL default '0',
  `conf_sec_protect_email_yn` char(1) NOT NULL default '',
  `conf_sec_send_poster_host_yn` char(1) NOT NULL default '',
  `conf_sec_test_group_yn` char(1) NOT NULL default '',
  `conf_sec_validate_email_yn` char(1) NOT NULL default '',
  `conf_sec_secret_string` tinytext NOT NULL,
  PRIMARY KEY  (`conf_id`)
)";

$db->query($sql_query);



// ------------------ myng_cron --------------- //


$sql_query = "

CREATE TABLE `myng_cron` (
  `cron_id` int(10) unsigned NOT NULL auto_increment,
  `cron_num_times` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`cron_id`)
)";

$db->query($sql_query);



// ------------------ myng_library --------------- //

$sql_query = 

"CREATE TABLE `myng_library` (
  `lib_art_id` bigint(20) unsigned NOT NULL default '0',
  `lib_grp_id` int(10) unsigned NOT NULL default '0',
  `lib_usr_id` int(10) unsigned NOT NULL default '0',
  `lib_times` tinyint(4) NOT NULL default '1',
  `lib_my_article` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`lib_art_id`,`lib_grp_id`,`lib_usr_id`)
)"; 

$db->query($sql_query);


// ------------------ myng_newsgroup --------------- //

$sql_query = 

"CREATE TABLE `myng_newsgroup` (
  `grp_id` int(10) unsigned NOT NULL auto_increment,
  `grp_name` varchar(150) NOT NULL default '',
  `grp_description` text NOT NULL,
  `grp_num_messages` int(10) unsigned NOT NULL default '0',
  `grp_first_article` int(10) unsigned default NULL,
  `grp_last_article` int(10) unsigned default NULL,
  `grp_num_available` int(10) unsigned default NULL,
  `grp_MAX_days` tinyint(3) unsigned NOT NULL default '0',
  `grp_MAX_articles` int(10) unsigned NOT NULL default '0',
  `grp_serv_id` int(10) unsigned NOT NULL default '0',
  `grp_allow_post_yn` char(1) NOT NULL default '',
  `grp_activity_index` float unsigned NOT NULL default '0',
  PRIMARY KEY  (`grp_id`)
)";


$db->query($sql_query);


// ------------------ myng_server --------------- //

$sql_query =

"CREATE TABLE `myng_server` (
  `serv_id` mediumint(8) unsigned NOT NULL auto_increment,
  `serv_host` varchar(60) NOT NULL default '',
  `serv_port` smallint(5) unsigned NOT NULL default '119',
  `serv_login` varchar(50) NOT NULL default '',
  `serv_passwd` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`serv_id`)
)"; 

$db->query($sql_query);


// ------------------ myng_subscription --------------- //

$sql_query = 

"CREATE TABLE `myng_subscription` (
  `subs_id` int(10) unsigned NOT NULL auto_increment,
  `subs_grp_id` int(10) unsigned NOT NULL default '0',
  `subs_usr_id` int(10) unsigned NOT NULL default '0',
  `subs_last_article` int(10) unsigned default '0',
  `subs_last_article_timestamp` int(10) unsigned NOT NULL default '0',
  `subs_posted` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`subs_id`)
)"; 

$db->query($sql_query);


// ------------------ myng_user --------------- //

$sql_query = 

"CREATE TABLE `myng_user` (
  `usr_id` int(20) unsigned NOT NULL auto_increment,
  `usr_name` varchar(50) NOT NULL default '',
  `usr_passwd` varchar(32) NOT NULL default '',
  `usr_email` varchar(30) NOT NULL default '',
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
)"; 

$db->query($sql_query);


// ------------------ myng_user_online --------------- //

$sql_query=

"CREATE TABLE `myng_user_online` (
  `uonl_usr_name` varchar(30) NOT NULL default '',
  `uonl_session_time` bigint(10) NOT NULL default '0',
  `uonl_message_inbox` tinytext,
  `uonl_chat_room` varchar(20) default NULL,
  `uonl_message_time` bigint(20) default '0',
  `uonl_message_from` varchar(20) default NULL,
  PRIMARY KEY  (`uonl_usr_name`)
)"; 


$db->query($sql_query);




// Insert the data into myng_config table
$sql_query = "INSERT INTO myng_config (
		
	conf_active_yn, 
	conf_name,
	conf_description,			
	conf_system_prefix,
	conf_system_root,
	conf_system_language,
	conf_system_zlib_yn,
	conf_system_debug_yn,			
	conf_system_login_yn,
	conf_system_online_yn,
	conf_down_days,
	conf_down_list_items,
	conf_down_num_groups,
	conf_down_num_articles,
	conf_vis_theme,
	conf_vis_num_2_flames,
	conf_vis_articles_x_page,
	conf_vis_nav_bar_items,
	conf_vis_nav_bar_pages,
	conf_vis_time_highlight_new,			
	conf_sec_send_poster_host_yn,
	conf_sec_test_group_yn,
	conf_sec_validate_email_yn,
	conf_sec_secret_string		
		
	) VALUES (
		
	'Y', 
	'Default',						
	'Default Configuration',		
	'".$_POST['script_path']."',				
	'".rtrim($_SERVER['DOCUMENT_ROOT'],'/').$_POST['script_path']."',	
	'".$_POST['conf_system_language']."',				
	'".$_POST['conf_system_zlib_yn']."',				
	'Y',							
	'Y',							
	'Y',							
	4,								
	10,								
	2,								
	1,								
	'standard',						
	50,								
	10,								
	10,								
	5,
	86400,							
	'N',							
	'N',							
	'N',							
	'howmuchwoodwouldawoodchuck
		chuckifawoodchuckcould
		chuckwood...'				
		
	)";

$db->query($sql_query);
    

?>