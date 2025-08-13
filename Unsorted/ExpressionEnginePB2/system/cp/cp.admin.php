<?php

/*
=====================================================
 ExpressionEngine - by pMachine
-----------------------------------------------------
 Nullified by GTT
-----------------------------------------------------
 Copyright (c) 2003 - 2004 pMachine, Inc.
=====================================================
 THIS IS COPYRIGHTED SOFTWARE
 PLEASE READ THE LICENSE AGREEMENT
=====================================================
 File: cp.admin.php
-----------------------------------------------------
 Purpose: Control panel Admin page class
=====================================================
*/


if ( ! defined('EXT'))
{
	exit('Invalid file request');
}



class Admin {


	function Admin()
	{
		global $IN, $LANG, $SESS, $LOG, $DSP;


		if ( ! $DSP->allowed_group('can_access_admin'))
		{
			return $DSP->no_access_message();
		}


		switch($IN->GBL('M'))
		{
		
			case 'config_mgr' :	
			
				if ( ! $DSP->allowed_group('can_admin_utilities'))
				{
					return $DSP->no_access_message();
				}
				
				switch($IN->GBL('P'))
				{
					case 'update_cfg'				: $this->update_config_prefs();
						break;
					default							: $this->config_manager();
						break;
				}

				break;		
			case 'members' :	
 
				// Instantiate the member administration class
		
				require PATH_CP.'cp.members'.EXT;
				
				$MBR = new Members;
			
				switch($IN->GBL('P'))	
				{
					case 'view_members'				: $MBR->view_all_members();
						break;
					case 'mbr_del_conf'				: $MBR->member_delete_confirm();
						break;
					case 'mbr_delete'				: $MBR->member_delete();
						break;
					case 'member_reg_form'			: $MBR->new_member_profile_form();
						break;
					case 'register_member'			: $MBR->create_member_profile();
						break;
					case 'mbr_group_manager'		: $MBR->member_group_manager();
						break;
					case 'edit_mbr_group'			: $MBR->edit_member_group_form();
						break;
					case 'update_mbr_group'			: $MBR->update_member_group();
						break;
					case 'mbr_group_del_conf'		: $MBR->delete_member_group_conf();
						break;
					case 'delete_mbr_group'			: $MBR->delete_member_group();
						break;
					case 'member_banning'			: $MBR->member_banning_forms();
						break;
					case 'save_ban_data'			: $MBR->update_banning_data();
						break;
					case 'profile_fields'			: $MBR->custom_profile_fields();
						break;
					case 'edit_field'				: $MBR->edit_profile_field_form();
						break;
					case 'del_field_conf'			: $MBR->delete_profile_field_conf();
						break;
					case 'delete_field'				: $MBR->delete_profile_field();
						break;
					case 'edit_field_order'			: $MBR->edit_field_order_form();
						break;
					case 'update_field_order'		: $MBR->update_field_order();
						break;
					case 'update_profile_fields'	: $MBR->update_profile_fields();
						break;
					case 'member_search'			: $MBR->member_search_form();
						break;
					case 'do_member_search'			: $MBR->do_member_search();
						break;
					case 'member_validation'		: $MBR->member_validation();
						break;							
					case 'validate_members'			: $MBR->validate_members();
						break;					
					case 'email_console_logs'		: $MBR->email_console_logs();
						break;					
					case 'view_email'				: $MBR->view_email();
						break;					
					case 'delete_email_console'		: $MBR->delete_email_console_messages();
						break;	
					case 'profile_templates'		: $MBR->profile_templates();
						break;	
					case 'list_templates'			: $MBR->list_templates();
						break;	
					case 'edit_template'			: $MBR->edit_template();
						break;	
					case 'save_template'			: $MBR->save_template();
						break;	
					default							: return false;
						break;
					}
					
				break;
			case 'sp_templ' :	
 
				// Instantiate the specialty templates class
		
				require PATH_CP.'cp.specialty_tmp'.EXT;
				
				$SP = new Specialty_Templates;
			
				switch($IN->GBL('P'))	
				{
					case 'mbr_notification_tmpl'		: $SP->mbr_notification_tmpl();
						break;
					case 'edit_notification_tmpl'		: $SP->edit_notification_tmpl();
						break;
					case 'update_notification_tmpl'		: $SP->update_notification_tmpl();
						break;
					case 'offline_tmpl' 				: $SP->offline_template();
						break;
					case 'update_offline_template'		: $SP->update_offline_template();
						break;
					case 'user_messages_tmpl' 			: $SP->user_messages_template();
						break;
					case 'update_user_messages_tmpl'	: $SP->update_user_messages_template();
						break;						
				}				
				break;
			case 'blog_admin' :	
			
				if ( ! $DSP->allowed_group('can_admin_weblogs'))
				{
					return $DSP->no_access_message();
				}
				
				// Instantiate the publish administration class
		
				require PATH_CP.'cp.publish_ad'.EXT;
				
				$PA = new PublishAdmin;
			
				switch($IN->GBL('P'))
				{
					case 'blog_list'			: $PA->weblog_overview();
						break;
					case 'new_weblog'			: $PA->new_weblog_form();
						break;
					case 'blog_prefs'			: $PA->edit_blog_form();
						break;
					case 'group_prefs'			: $PA->edit_group_form();
						break;
					case 'create_blog'			: $PA->update_weblog_prefs();
						break;
					case 'update_preferences'	: $PA->update_weblog_prefs();
						break;
					case 'delete_conf'			: $PA->delete_weblog_conf();
						break;
					case 'delete'				: $PA->delete_weblog();
						break;
					case 'categories'			: $PA->category_overview();
						break;
					case 'cat_group_editor'		: $PA->edit_category_group_form();
						break;
					case 'update_cat_group'		: $PA->update_category_group();
						break;
					case 'cat_group_del_conf'	: $PA->delete_category_group_conf();
						break;
					case 'delete_group'			: $PA->delete_category_group();
						break;
					case 'category_editor'		: $PA->category_manager();
						break;
					case 'update_category'		: $PA->update_category();
						break;
					case 'edit_category'		: $PA->edit_category_form();
						break;
					case 'del_category'			: $PA->delete_category();
						break;
					case 'statuses'				: $PA->status_overview();
						break;
					case 'status_group_editor'	: $PA->edit_status_group_form();
						break;
					case 'update_status_group'	: $PA->update_status_group();
						break;
					case 'status_group_del_conf': $PA->delete_status_group_conf();
						break;
					case 'delete_status_group'	: $PA->delete_status_group();
						break;
					case 'status_editor'		: $PA->status_manager();
						break;
					case 'update_status'		: $PA->update_status();
						break;
					case 'edit_status'			: $PA->edit_status_form();
						break;
					case 'del_status_conf'		: $PA->delete_status_confirm();
						break;
					case 'del_status'			: $PA->delete_status();
						break;
					case 'edit_status_order'	: $PA->edit_status_order();
						break;
					case 'update_status_order'	: $PA->update_status_order();
						break;
					case 'custom_fields'		: $PA->field_overview();
						break;
					case 'update_field_group'	: $PA->update_field_group();
						break;
					case 'del_field_group_conf'	: $PA->delete_field_group_conf();
						break;
					case 'delete_field_group'	: $PA->delete_field_group();
						break;
					case 'field_editor'			: $PA->field_manager();
						break;
					case 'edit_field'			: $PA->edit_field_form();
						break;
					case 'update_weblog_fields'	: $PA->update_weblog_fields();
						break;
					case 'field_group_editor'	: $PA->edit_field_group_form();
						break;
					case 'del_field_conf'		: $PA->delete_field_conf();
						break;
					case 'delete_field'			: $PA->delete_field();
						break;
					case 'edit_field_order'		: $PA->edit_field_order_form();
						break;
					case 'update_field_order'	: $PA->update_field_order();
						break;
					case 'html_buttons'			: $PA->html_buttons();
						break;
					case 'save_html_buttons'	: $PA->save_html_buttons();
						break;
					case 'ping_servers'			: $PA->ping_servers();
						break;
					case 'save_ping_servers'	: $PA->save_ping_servers();
						break;
					case 'upload_prefs'			: $PA->file_upload_preferences();
						break;
					case 'edit_upload_pref'		: $PA->edit_upload_preferences_form();
						break;
					case 'update_upload_prefs'	: $PA->update_upload_preferences();
						break;
					case 'del_upload_pref_conf'	: $PA->delete_upload_preferences_conf();
						break;
					case 'del_upload_pref'		: $PA->delete_upload_preferences();
						break;
					default						: return false;
						break;
					}
										
				break;
			case 'utilities' :	
			
				if ( ! $DSP->allowed_group('can_admin_utilities'))
				{
					return $DSP->no_access_message();
				}
				
				// We handle the pMachine import via a different class,
				// so we'll test for that separately
				
				if ($IN->GBL('P') == 'pm_import')
				{
					require PATH_CP.'cp.pm_import'.EXT;
					$PMI = new PM_Import();
					return;
				}


				if ($IN->GBL('P') == 'mt_import')
				{
					require PATH_CP.'cp.mt_import'.EXT;
					$MT= new MT_Import();
					return;
				}
												
				require PATH_CP.'cp.utilities'.EXT;
			
				switch($IN->GBL('P'))
				{
					case 'view_logs'			: $LOG->view_logs();
						break;
					case 'clear_cplogs'		 	: $LOG->clear_cp_logs();
						break;
					case 'clear_cache_form'	 	: Utilities::clear_cache_form();
						break;		 
					case 'clear_caching'		: Utilities::clear_caching();
						break;		 
					case 'run_query'			: Utilities::sql_manager('run_query');
						break;
					case 'sql_query'			: Utilities::sql_query_form();
						break;
					case 'sql_backup'			: Utilities::sql_backup();
						break;
					case 'do_sql_backup'		: Utilities::do_sql_backup();
						break;
					case 'view_database'		: Utilities::view_database();
						break;
					case 'table_action'		 	: Utilities::run_table_action();
						break;
					case 'sandr'				: Utilities::search_and_replace_form();
						break;
					case 'recount_stats'		: Utilities::recount_statistics();
						break;
					case 'recount_prefs'		: Utilities::recount_preferences_form();
						break;
					case 'set_recount_prefs'	: Utilities::set_recount_prefs();
						break;
					case 'do_recount'			: Utilities::do_recount();
						break;
					case 'do_stats_recount'		: Utilities::do_stats_recount();
						break;
					 case 'prune'				: Utilities::data_pruning();
						break;
					case 'run_sandr'			: Utilities::search_and_replace();
						break;
					case 'php_info'			 	: Utilities::php_info();
						break;
					case 'sql_manager'			: Utilities::sql_info();
						break;
					case 'sql_status'			: Utilities::sql_manager('status');
						break;
					case 'sql_sysvars'			: Utilities::sql_manager('sysvars');
						break;
					case 'sql_plist'			: Utilities::sql_manager('plist');
						break;
					case 'plugin_manager'		: Utilities::plugin_manager();
						break;
					case 'plugin_info'			: Utilities::plugin_info();
						break;
					case 'trans_menu'			: Utilities::translate_select();
						break;
					case 'translate'			: Utilities::translate();
						break;
					case 'save_translation'	 	: Utilities::save_translation();
						break;
					default					 	: return false;
						break;
					}
										
				break;
			default	: $this->admin_home_page();
				break;
		}	
	}
	// END
	
	
	// -----------------------------
	//	Main admin page
	// -----------------------------	
	
	function admin_home_page()
	{	
		global $DSP, $DB, $FNS, $SESS, $LANG, $PREFS;
				
	
		if ( ! $DSP->allowed_group('can_access_admin'))
		{
			return $DSP->no_access_message();
		}
				
		$DSP->title = $LANG->line('system_admin');						 
		$DSP->crumb = $LANG->line('system_admin');	

		$DSP->body	.=	$DSP->qdiv('', NBS);
		
		$DSP->body	.=	$DSP->table('', '0', '10', '', '', 'center');
		$DSP->body	.=	$DSP->tr();
		$DSP->body	.=	$DSP->td('leftCel', '47%', '', '', 'top');

		
		// This array contains the links that appear on the left site of the page
						
		$left = array( 
						'weblog_administration'	=> array(
															'weblog_management'		=>	AMP.'M=blog_admin'.AMP.'P=blog_list',
															'categories'			=>	AMP.'M=blog_admin'.AMP.'P=categories',
															'field_management'	 	=>	AMP.'M=blog_admin'.AMP.'P=blog_list'.AMP.'P=custom_fields',
															'status_management'		=>	AMP.'M=blog_admin'.AMP.'P=statuses',
															'file_upload_prefs'		=>	AMP.'M=blog_admin'.AMP.'P=upload_prefs',
															'default_ping_servers' 	=>	AMP.'M=blog_admin'.AMP.'P=ping_servers',
															'default_html_buttons' 	=>	AMP.'M=blog_admin'.AMP.'P=html_buttons'
														 ),
														 
													 	
						'specialty_templates'	=> array(
															'email_notification_template'	=> AMP.'M=sp_templ'.AMP.'P=mbr_notification_tmpl',
															'user_messages_template' 		=> AMP.'M=sp_templ'.AMP.'P=user_messages_tmpl',															
															'offline_template'				=> AMP.'M=sp_templ'.AMP.'P=offline_tmpl'														
													 	),
													 
						'utilities'				=> array(
															'view_log_files'		=>	AMP.'M=utilities'.AMP.'P=view_logs',
															'sql_manager'			=>	AMP.'M=utilities'.AMP.'P=sql_manager',
															'plugin_manager'		=>	AMP.'M=utilities'.AMP.'P=plugin_manager',
															'clear_caching'		 	=>	AMP.'M=utilities'.AMP.'P=clear_cache_form',
															'search_and_replace'	=>	AMP.'M=utilities'.AMP.'P=sandr',
														 // 'data_pruning'			=>	AMP.'M=utilities'.AMP.'P=prune',
															'recount_stats'		 	=>	AMP.'M=utilities'.AMP.'P=recount_stats',
															'php_info'				=>	AMP.'M=utilities'.AMP.'P=php_info',
															'translation_tool'		=>	AMP.'M=utilities'.AMP.'P=trans_menu',
															'import_from_pm'		=>  AMP.'M=utilities'.AMP.'P=pm_import',
															'import_from_mt'		=>  AMP.'M=utilities'.AMP.'P=mt_import'
													 	)
						);


		// Run through the array and write out the links
							
		foreach ($left as $key => $val)
		{		
			$DSP->body	.=	$DSP->div('menuWrapper');
			$DSP->body	.=	$DSP->qdiv('menuHeading', $LANG->line($key));
			
			foreach ($val as $k => $v)
			{
				$DSP->body	.=	$DSP->qdiv('menuItem', $DSP->anchor(BASE.AMP.'C=admin'.$v, $LANG->line($k)));
			}
			
			$DSP->body	.=	$DSP->div_c();
		}

		$DSP->body	.=	$DSP->td_c();
		$DSP->body	.=	$DSP->td('rightCel', '53%', '', '', 'top');
		
		
		// This array contains the links that appear on the right site of the page

		$right = array( 
						'members_and_groups' 	=> array(
															'register_member'		=> AMP.'M=members'.AMP.'P=member_reg_form',
															'member_validation'		=> AMP.'M=members'.AMP.'P=member_validation',
															'custom_profile_fields' => AMP.'M=members'.AMP.'P=profile_fields',
															'member_groups'		 	=> AMP.'M=members'.AMP.'P=mbr_group_manager',
															'view_members'			=> AMP.'M=members'.AMP.'P=view_members',
															'member_search'		 	=> AMP.'M=members'.AMP.'P=member_search',
															'view_email_logs'		=> AMP.'M=members'.AMP.'P=email_console_logs',
															'user_banning'			=> AMP.'M=members'.AMP.'P=member_banning',
															'member_cfg'			=> AMP.'M=config_mgr'.AMP.'P=member_cfg',
															'profile_templates'		=> AMP.'M=members'.AMP.'P=profile_templates'
													 	),
													 	
						'system_preferences'	=>	array(
															'general_cfg'			=> AMP.'M=config_mgr'.AMP.'P=general_cfg',
															'security_cfg'	 		=> AMP.'M=config_mgr'.AMP.'P=security_cfg',
															'localization_cfg' 		=> AMP.'M=config_mgr'.AMP.'P=localization_cfg',
															'email_cfg'				=> AMP.'M=config_mgr'.AMP.'P=email_cfg',
															'cookie_cfg'			=> AMP.'M=config_mgr'.AMP.'P=cookie_cfg',
															'image_cfg'				=> AMP.'M=config_mgr'.AMP.'P=image_cfg',
															'censoring_cfg'			=> AMP.'M=config_mgr'.AMP.'P=censoring_cfg',															
															'emoticon_cfg'	 		=> AMP.'M=config_mgr'.AMP.'P=emoticon_cfg',
															'referrer_cfg'	 		=> AMP.'M=config_mgr'.AMP.'P=referrer_cfg',
															'template_cfg'	 		=> AMP.'M=config_mgr'.AMP.'P=template_cfg'
														)
						);
						
													
		foreach ($right as $key => $val)
		{		
			$DSP->body	.=	$DSP->div('menuWrapper');
			$DSP->body	.=	$DSP->qdiv('menuHeading', $LANG->line($key));
			
			foreach ($val as $k => $v)
			{
				$DSP->body	.=	$DSP->qdiv('menuItem', $DSP->anchor(BASE.AMP.'C=admin'.$v, $LANG->line($k)));
			}
			
			$DSP->body	.=	$DSP->div_c();
		}		
		
		$DSP->body	.=	$DSP->td_c();
		$DSP->body	.=	$DSP->tr_c();
		$DSP->body	.=	$DSP->table_c();
	}
	// END	




	// -----------------------------
	//	Configuration manager
	// ----------------------------- 
	
	// This function displays the various Preferences pages
	
	function config_manager()
	{	
		global $IN, $DSP, $FNS, $LOC, $PREFS, $LANG;
		
		
		if ( ! $DSP->allowed_group('can_admin_preferences'))
		{
			return $DSP->no_access_message();
		}
		
		if ( ! $type = $IN->GBL('P'))
		{
			return false;
		}
						
		// No funny business with the URL
		
		if ( ! in_array($type, array(
										'general_cfg', 
										'member_cfg',
										'security_cfg',
										'localization_cfg',
										'email_cfg',
										'cookie_cfg',
										'image_cfg',
										'template_cfg',
										'censoring_cfg',
										'emoticon_cfg',
										'referrer_cfg'
										)
						)
		)
		{
			return $FNS->bounce();
		}
		

		// -----------------------------
		//	Preference matrix
		// ----------------------------- 

		$f_data = array(
		
			'general_cfg'		=>	array(
											'site_name'					=> '',
											'site_index'				=> '',
											'site_url'					=> '',
											'cp_url'					=> '',
											'doc_url'					=> '',
											'is_system_on'				=> array('r', array('y' => 'yes', 'n' => 'no')),
											'cp_theme'					=> array('f', 'theme_menu'),
											'debug'						=> array('s', array('0' => 'debug_zero', '1' => 'debug_one', '2' => 'debug_two')),
											'gzip_output'				=> array('r', array('y' => 'yes', 'n' => 'no')),
											'enable_db_caching'			=> array('r', array('y' => 'yes', 'n' => 'no')),
											'db_cache_refresh'			=> '',
											'show_queries'				=> array('r', array('y' => 'yes', 'n' => 'no')),											
											'force_query_string'		=> array('r', array('y' => 'yes', 'n' => 'no')),
											'send_headers'				=> array('r', array('y' => 'yes', 'n' => 'no')),
											'redirect_method'			=> array('s', array('redirect' => 'location_method', 'refresh' => 'refresh_method')),
											'safe_mode'					=> array('r', array('y' => 'yes', 'n' => 'no')),
											'xml_lang'					=> array('f', 'fetch_encoding'),
											'charset'					=> array('f', 'fetch_encoding'),
											'word_separator'			=> array('s', array('dash' => 'dash', 'underscore' => 'underscore'))
											),

			'member_cfg'		=>	array(
											'allow_member_registration'	=> array('r', array('y' => 'yes', 'n' => 'no')),
											'req_mbr_activation'		=> array('s', array('none' => 'no_activation', 'email' => 'email_activation', 'manual' => 'manual_activation')),
											'require_terms_of_service'	=> array('r', array('y' => 'yes', 'n' => 'no')),
											'new_member_notification'	=> array('r', array('y' => 'yes', 'n' => 'no')),
											'mbr_notification_emails'	=> '',
											'default_member_group'		=> array('f', 'member_groups'),
											'member_theme'				=> array('f', 'member_theme_menu'),
											'member_images'				=> ''
											),
									
			'security_cfg'		=>	array(												
											'admin_session_type'		=> array('s', array('cs' => 'cs_session', 'c' => 'c_session', 's' => 's_session')),
											'user_session_type'	 		=> array('s', array('cs' => 'cs_session', 'c' => 'c_session', 's' => 's_session')),
											'secure_forms'				=> array('r', array('y' => 'yes', 'n' => 'no')),
											'deny_duplicate_data'		=> array('r', array('y' => 'yes', 'n' => 'no')),
											'allow_username_change' 	=> array('r', array('y' => 'yes', 'n' => 'no')),
											'allow_multi_emails'		=> array('r', array('y' => 'yes', 'n' => 'no')),
											'allow_multi_logins'		=> array('r', array('y' => 'yes', 'n' => 'no')),
											'require_ip_for_login'		=> array('r', array('y' => 'yes', 'n' => 'no')),
											'password_lockout'			=> array('r', array('y' => 'yes', 'n' => 'no')),
											'password_lockout_interval'	=> '',
											'require_secure_passwords'	=> array('r', array('y' => 'yes', 'n' => 'no')),
											'allow_dictionary_pw'		=> array('r', array('y' => 'yes', 'n' => 'no')),
											'name_of_dictionary_file'	=> '',
											'un_min_len'				=> '',
											'pw_min_len'				=> ''
											),

			'localization_cfg'	=>	array(	 
											'server_timezone'			=> array('f', 'timezone'),
											'server_offset'				=> '',
											'daylight_savings'			=> array('r', array('y' => 'yes', 'n' => 'no')),
											'time_format'				=> array('s', array('us' => 'united_states', 'eu' => 'european')),
											'deft_lang'					=> array('f', 'language_menu')
										  ),

			'email_cfg'			=>	array(
											'webmaster_email'			=> '',
											'mail_protocol'				=> array('s', array('mail' => 'php_mail', 'sendmail' => 'sendmail', 'smtp' => 'smtp')),
											'smtp_server'				=> '',
											'smtp_username'				=> '',
											'smtp_password'				=> '',
											'email_batchmode'			=> array('r', array('y' => 'yes', 'n' => 'no')),
											'email_batch_size'			=> '',
											'mail_format'				=> array('s', array('plain' => 'plain_text', 'html' => 'html')),
											'word_wrap'					=> array('r', array('y' => 'yes', 'n' => 'no')),
											'email_console_timelock'	=> '',
											'log_email_console_msgs'	=> array('r', array('y' => 'yes', 'n' => 'no'))
										 ),

			'cookie_cfg' 		=>	array(												
											'cookie_domain'				=> '',
											'cookie_path'				=> '',
											'cookie_prefix'				=> ''
										 ),
										 
			'image_cfg' 		=>	array(												
											'enable_image_resizing' 	=> array('r', array('y' => 'yes', 'n' => 'no')),
											'image_resize_protocol'		=> array('s', array('gd' => 'gd', 'gd2' => 'gd2', 'imagemagick' => 'imagemagick', 'netpbm' => 'netpbm')),
											'image_library_path'		=> '',
											'thumbnail_prefix'			=> ''
										 ),
										 									
			'template_cfg' 		=>	array(												
											'save_tmpl_revisions' 		=> array('r', array('y' => 'yes', 'n' => 'no'))
										 ),
									
			'censoring_cfg' 	=>	array(												
											'enable_censoring' 			=> array('r', array('y' => 'yes', 'n' => 'no')),
											'censored_words'			=> array('t', array('rows' => '20', 'kill_pipes' => TRUE)),
										 ),
									
			'emoticon_cfg' 		=>	array(												
											'enable_emoticons' 			=> array('r', array('y' => 'yes', 'n' => 'no')),
											'emoticon_path'				=> ''
										 ),
									
			'referrer_cfg' 		=>	array(												
											'log_referrers' 			=> array('r', array('y' => 'yes', 'n' => 'no'))
										 )
						);
						
						
		// -----------------------------
		//	Secondary lines of text
		// ----------------------------- 
		
		// This array lets us define sub-text that appears below any given preference defenition	
			
		$subtext = array(	
							'site_url'					=> array('url_explanation'),
							'doc_url'					=> array('doc_url_explanation'),
							'is_system_on'		    	=> array('is_system_on_explanation'),
							'debug'						=> array('debug_explanation'),
							'show_queries'				=> array('show_queries_explanation'),
							'xml_lang'					=> array('used_in_meta_tags'),
							'charset'					=> array('used_in_meta_tags'),
							'gzip_output'				=> array('gzip_output_explanation'),
							'mbr_notification_emails'	=> array('separate_emails'),
							'server_offset'				=> array('server_offset_explain'),
							'default_member_group' 		=> array('group_assignment_defaults_to_two'),
							'smtp_server'				=> array('only_if_smpte_chosen'),
							'smtp_username'				=> array('only_if_smpte_chosen'),
							'smtp_password'				=> array('only_if_smpte_chosen'),
							'email_batchmode'			=> array('batchmode_explanation'),
							'email_batch_size'			=> array('batch_size_explanation'),
							'webmaster_email'			=> array('return_email_explanation'),
							'cookie_domain'				=> array('cookie_domain_explanation'),
							'cookie_prefix'				=> array('cookie_prefix_explain'),
							'cookie_path'				=> array('cookie_path_explain'),
							'secure_forms'				=> array('secure_forms_explanation'),
							'deny_duplicate_data'		=> array('deny_duplicate_data_explanation'),
							'require_secure_passwords'	=> array('secure_passwords_explanation'),
							'allow_dictionary_pw'		=> array('real_word_explanation', 'dictionary_note'),
							'censored_words'			=> array('censored_explanation'),
							'password_lockout'			=> array('password_lockout_explanation'),
							'password_lockout_interval'	=> array('login_interval_explanation'),
							'require_ip_for_login'		=> array('require_ip_explanation'),
							'allow_multi_logins'		=> array('allow_multi_logins_explanation'),
							'name_of_dictionary_file' 	=> array('dictionary_explanation'),
							'force_query_string'		=> array('force_query_string_explanation'),
							'log_referrers'				=> array('log_referrers_explanation'),
							'enable_image_resizing'		=> array('enable_image_resizing_exp'),
							'image_resize_protocol'		=> array('image_resize_protocol_exp'),
							'image_library_path'		=> array('image_library_path_exp'),
							'thumbnail_prefix'			=> array('thumbnail_prefix_exp'),
							'member_theme'				=> array('member_theme_exp'),
							'member_images'				=> array('member_images_exp'),
							'require_terms_of_service'	=> array('require_terms_of_service_exp'),
							'email_console_timelock'	=> array('email_console_timelock_exp'),
							'log_email_console_msgs'	=> array('log_email_console_msgs_exp'),
							'db_cache_refresh'			=> array('db_cache_refresh_exp')
						);


		// -----------------------------
		//	Build the output
		// ----------------------------- 
		
		$DSP->body	 =	$DSP->heading($LANG->line($type));
		
		// This is not finished.
		if (0)
		{
		
			// Create the help menu links
			
			$help = array(
							'general_cfg' 		=> 'general_configuration.html', 
							'member_cfg'		=> 'member_preferences.html',
							'security_cfg'		=> 'security_settings.html',		
							'localization_cfg'	=> 'localization_settings.html',
							'email_cfg'			=> 'email_configuration.html',
							'cookie_cfg'		=> 'cookie_settings.html',
							'image_cfg'			=> 'image_preferences.html',
							'template_cfg'		=> 'template_preferences.html',
							'censoring_cfg'		=> 'word_censoring.html',
							'emoticon_cfg'		=> 'emoticon_preferences.html',
							'referrer_cfg'		=> 'referrer_preferences.html'
						);
			
			
			
			if ($IN->GBL('P') AND isset($help[$_GET['P']]))
			{
				$DSP->body	.=	$DSP->qdiv('itemWrapper', $DSP->doc_link(array('title' => $LANG->line('consult_user_guide'), 'page' => 'cp/admin/system_preferences/'.$help[$_GET['P']])));
			}
		}	
		if ($IN->GBL('U'))
		{
			$DSP->body .= $DSP->qdiv('success', $LANG->line('preferences_updated'));
		}
				
		$DSP->body	.=	$DSP->form('C=admin'.AMP.'M=config_mgr'.AMP.'P=update_cfg');
		$DSP->body	.=	$DSP->input_hidden('return_location', $type);
				

		$DSP->body	.=	$DSP->table('tableBorder', '0', '0', '100%');
		$DSP->body	.=	$DSP->tr();
		$DSP->body	.=	$DSP->td('tablePad'); 
				
		$DSP->body	.=	$DSP->table('', '0', '', '100%');
		$DSP->body	.=	$DSP->tr();
		$DSP->body	.=	$DSP->table_qcell('tableHeadingBold', $LANG->line('preference'));
		$DSP->body	.=	$DSP->table_qcell('tableHeadingBold', $LANG->line('value'));
		$DSP->body	.=	$DSP->tr_c();
		
		$i = 0;
		
		// -----------------------------
		//	Blast through the array
		// ----------------------------- 
				
		foreach ($f_data[$type] as $key => $val)				
		{
			$style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;
			
			$DSP->body	.=	$DSP->tr();
			
			// If the form type is a textarea, we'll align the text at the top, otherwise, we'll center it
			
			if (is_array($val) AND $val['0'] == 't')
			{
				$DSP->body .= $DSP->td($style, '50%', '', '', 'top');
			}
			else
			{
				$DSP->body .= $DSP->td($style, '50%', '');
			}
			
			// -----------------------------
			//	Preference heading
			// ----------------------------- 
			
			$DSP->body .= $DSP->div('defaultBold');
					
			$label = ( ! is_array($val)) ? $key : '';
		
			$DSP->body .= $LANG->line($key, $label);

			$DSP->body .= $DSP->div_c();
			
			
			// -----------------------------
			//	Preference sub-heading
			// ----------------------------- 
			
			if (isset($subtext[$key]))
			{
				foreach ($subtext[$key] as $sub)
				{
					$DSP->body .= $DSP->qdiv('subtext', $LANG->line($sub));
				}
			}
			
			$DSP->body .= $DSP->td_c();
			
			// -----------------------------
			//	Preference value
			// ----------------------------- 
			
			$DSP->body .= $DSP->td($style, '50%', '');
			
				if (is_array($val))
				{
					// -----------------------------
					//	Drop-down menus
					// ----------------------------- 
								
					if ($val['0'] == 's')
					{
						$DSP->body .= $DSP->input_select_header($key);
				
						foreach ($val['1'] as $k => $v)
						{
							$selected = ($k == $PREFS->ini($key)) ? 1 : '';
						
							$DSP->body .= $DSP->input_select_option($k, $LANG->line($v), $selected);
						}
						
						$DSP->body .= $DSP->input_select_footer();
						
					} 
					elseif ($val['0'] == 'r')
					{
						// -----------------------------
						//	Radio buttons
						// ----------------------------- 
					
						foreach ($val['1'] as $k => $v)
						{
							$selected = ($k == $PREFS->ini($key)) ? 1 : '';
						
							$DSP->body .= $LANG->line($v).$DSP->nbs();
							$DSP->body .= $DSP->input_radio($key, $k, $selected).$DSP->nbs(3);
						}					
					}
					elseif ($val['0'] == 't')
					{
						// -----------------------------
						//	Textarea fileds
						// ----------------------------- 
						
						// The "kill_pipes" index instructs us to 
						// turn pipes into newlines
						
						if (isset($val['1']['kill_pipes']) AND $val['1']['kill_pipes'] === TRUE)
						{
							$text	= '';
							
							foreach (explode('|', $PREFS->ini($key)) as $exp)
							{
								$text .= $exp.NL;
							}
						}
						else
						{
							$text = stripslashes($PREFS->ini($key));
						}
												
						$rows = (isset($val['1']['rows'])) ? $val['1']['rows'] : '20';
						
						$DSP->body .= $DSP->input_textarea($key, $text, $rows);
						
					}					
					elseif ($val['0'] == 'f')
					{
						// -----------------------------
						//	Function calls
						// ----------------------------- 
					
						switch ($val['1'])
						{
							case 'language_menu'		: 	$DSP->body .= $FNS->language_pack_names($PREFS->ini($key));
								break;
							case 'member_groups'		:	$DSP->body .= $this->fetch_member_groups();
								break;	
							case 'fetch_encoding'		:	$DSP->body .= $this->fetch_encoding($key);
								break;
							case 'theme_menu'			: 	$DSP->body .= $this->fetch_themes($PREFS->ini($key));
								break;
							case 'member_theme_menu'	: 	$DSP->body .= $this->directory_list(PATH_MOD.'member/themes/', $PREFS->ini($key));
								break;	
							case 'timezone'				: 	$DSP->body .= $LOC->timezone_menu($PREFS->ini($key));
								break;
						}
					}
				}
				else
				{
					// -----------------------------
					//	Text input fields
					// ----------------------------- 
				
					$DSP->body .= $DSP->input_text($key, stripslashes($PREFS->ini($key)), '20', '120', 'input', '100%');
				}
				
			$DSP->body .= $DSP->td_c();
			$DSP->body .= $DSP->tr_c();
		}
				
		$DSP->body .= $DSP->table_c();
		
		$DSP->body .= $DSP->td_c();
		$DSP->body .= $DSP->tr_c();	
		$DSP->body .= $DSP->table_c();		
		
		$DSP->body .= $DSP->qdiv('', BR.$DSP->input_submit('update'));
				
		$DSP->body .= $DSP->form_c();
				
		$DSP->title = $LANG->line($type);								
		$DSP->crumb = $LANG->line($type);								
	}
	// END
	
		
	
	// -----------------------------------------
	//	Fetch Member groups
	// -----------------------------------------
		
	function fetch_member_groups()
	{
		global $DB, $LANG, $DSP, $PREFS, $SESS;
		
		$LANG->fetch_language_file('members');
		
    	$english = array('Guests', 'Banned', 'Members', 'Pending', 'Super Admins');

		$query = $DB->query("SELECT group_id, group_title FROM exp_member_groups WHERE group_id != '1' order by group_title");
			  
		$r = $DSP->input_select_header('default_member_group');
								
		foreach ($query->result as $row)
		{
			$group_title = $row['group_title'];
					
			if (in_array($group_title, $english))
			{
				$group_title = $LANG->line(strtolower(str_replace(" ", "_", $group_title)));
			}
			
			$selected = ($row['group_id'] == $PREFS->ini('default_member_group')) ? 1 : '';
			
			$r .= $DSP->input_select_option($row['group_id'], $group_title, $selected);
		}
		
		$r .= $DSP->input_select_footer();
		
		return $r;
	}
	// END
	
	
	
	// -----------------------------------------
	//	Update general preferences
	// -----------------------------------------
		
	function update_config_prefs()
	{
		global $IN, $DSP;

		if ( ! $DSP->allowed_group('can_admin_preferences'))
		{
			return $DSP->no_access_message();
		}
		
		$loc = $IN->GBL('return_location', 'POST');
		
		// We'll format censored words if they happen to cross our path
		
		if (isset($_POST['censored_words']))
		{
			$_POST['censored_words'] = trim($_POST['censored_words']);

			$_POST['censored_words'] = str_replace(NL, '|', $_POST['censored_words']);

			$_POST['censored_words'] = preg_replace("#\s+#", "", $_POST['censored_words']);
		}
		
		unset($_POST['return_location']);
		
		foreach ($_POST as $key => $val)
		{
			$_POST[$key] = stripslashes($val);		
		}
		
		$this->update_config_file($_POST,	BASE.AMP.'C=admin'.AMP.'M=config_mgr'.AMP.'P='.$loc.AMP.'U=1');		
	}
	// END


	// -----------------------------------------
	//	Update config file
	// -----------------------------------------
		
	function update_config_file($newdata = '', $return_loc = FALSE)
	{
		global $FNS;
				
		if ( ! is_array($newdata))
		{
			return false;
		}
				
		require CONFIG_FILE;
		
		// -----------------------------------------
		//	Write config backup file
		// -----------------------------------------
				
		$old  = "<?php\n\n";
		$old .= "if ( ! defined('EXT')){\nexit('Invalid file request');\n}\n\n";
	 
		foreach ($conf as $key => $val)
		{
			$old .= "\$conf['".$key."'] = \"".addslashes($val)."\";\n";
		} 
		
		$old .= '?'.'>';
		
		$bak_path = str_replace(EXT, '', CONFIG_FILE);
		$bak_path .= '_bak'.EXT;
		
		if ($fp = @fopen($bak_path, 'wb'))
		{
			flock($fp, LOCK_EX);
			
			fwrite($fp, $old);
			
			flock($fp, LOCK_UN);
			
			fclose($fp);
		}		
		
		// -----------------------------------------
		//	Add new data values to config file
		// -----------------------------------------		
			
		foreach ($newdata as $key => $val)
		{
			$val = str_replace("\n", " ", $val);
		
			if (isset($conf[$key]))
			{			
				$conf[$key] = trim($val);	
			}
		}
		
		reset($conf);
		
		// -----------------------------------------
		//	Write config file as a string
		// -----------------------------------------
		
		$new  = "<?php\n\n";
		$new .= "if ( ! defined('EXT')){\nexit('Invalid file request');\n}\n\n";
	 
		foreach ($conf as $key => $val)
		{
			$new .= "\$conf['".$key."'] = \"".addslashes($val)."\";\n";
		} 
		
		$new .= '?'.'>';
		
		// -----------------------------------------
		//	Write config file
		// -----------------------------------------

		if ($fp = @fopen(CONFIG_FILE, 'wb'))
		{
			flock($fp, LOCK_EX);
			
			fwrite($fp, $new);
			
			flock($fp, LOCK_UN);
			
			fclose($fp);
		}
		
		if ($return_loc !== FALSE)
		{		
			$FNS->redirect($return_loc);
			exit;
		}
	}	
	// END
		
		
		
	// -------------------------------------------
	//	Append config file 
	// -------------------------------------------
	
	// This function allows us to add new config file elements

	function append_config_file($new_config)
	{
		require CONFIG_FILE;

		if ( ! is_array($new_config))
			return false;
		
		// -----------------------------------------
		//	Write config backup file
		// -----------------------------------------
		
		$old  = "<?php\n\n";
		$old .= "if ( ! defined('EXT')){\nexit('Invalid file request');\n}\n\n";
	 
		foreach ($conf as $key => $val)
		{
			$old .= "\$conf['".$key."'] = \"".$val."\";\n";
		} 
		
		$old .= '?'.'>';
		
		$bak_path = str_replace(EXT, '', CONFIG_FILE);
		$bak_path .= '_bak'.EXT;

		if ($fp = @fopen($bak_path, 'wb'))
		{
			flock($fp, LOCK_EX);
			
			fwrite($fp, $old);
			
			flock($fp, LOCK_UN);
			
			fclose($fp);
		}		
		
		// -----------------------------------------
		//	Merge new data to the congig file
		// -----------------------------------------
		
		$conf = array_merge($conf, $new_config);		
				
		$new  = "<?php\n\n";
		$new .= "if ( ! defined('EXT')){\nexit('Invalid file request');\n}\n\n";
	 
		foreach ($conf as $key => $val)
		{
			$new .= "\$conf['".$key."'] = \"".$val."\";\n";
		} 
		
		$new .= '?'.'>';

		if ($fp = @fopen(CONFIG_FILE, 'wb'))
		{
			flock($fp, LOCK_EX);
			
			fwrite($fp, $new);
			
			flock($fp, LOCK_UN);
			
			fclose($fp);
		}		
	}
	// END
		
		
	
	// -----------------------------------------
	//	Fetch Control Panel Themes
	// -----------------------------------------

	// Fetches the list of CSS files
	
	function fetch_themes($default = '')
	{
		global $PREFS, $DSP;
			
		$source_dir = PATH_THEMES;
	
		$filelist = array();
	
		if ($fp = @opendir($source_dir)) 
		{ 
			while (false !== ($file = readdir($fp))) 
			{ 
				$filelist[count($filelist)] = $file;
			} 
		} 
	
		closedir($fp); 
		
		sort($filelist);

		$r = $DSP->input_select_header('cp_theme');
		
			
		for ($i =0; $i < sizeof($filelist); $i++) 
		{
			if ( eregi(".css$",	$filelist[$i]))
			{
				$filelist[$i] = substr($filelist[$i] , 0, strpos($filelist[$i], '.'));
			
				$selected = ($filelist[$i] == $default) ? 1 : '';
				
				$name = ucwords(str_replace("_", " ", $filelist[$i]));
				
				$r .= $DSP->input_select_option($filelist[$i], $name, $selected);
			}
		}		

		$r .= $DSP->input_select_footer();

		return $r;
	
	}
	// END	
	
	
	
	// -----------------------------------------
	//	Show directory listing as a pull-down
	// -----------------------------------------
	
	function directory_list($path = '', $default = '')
	{
		global $PREFS, $DSP;
		
		if ($path == '')
			return;
		
		$r = '';

        if ($fp = @opendir($path))
        { 
			$r .= $DSP->input_select_header('cp_theme');
        
            while (false !== ($file = readdir($fp)))
            {
                if (is_dir($path.$file) && $file !== '.' && $file !== '..') 
                {                    
					$selected = ($file == $default) ? 1 : '';
					
					$name = ucwords(str_replace("_", " ", $file));
					
					$r .= $DSP->input_select_option($file, $name, $selected);
                }
            }         
            
			$r .= $DSP->input_select_footer();
			
			closedir($fp); 
        } 

		return $r;
	}
	// END	


	// -----------------------------------------
	//	Fetch encodings
	// -----------------------------------------

	function fetch_encoding($which)	
	{
		global $FNS, $PREFS;
		
		if ($which == 'xml_lang')
		{
			return $FNS->encoding_menu('languages', 'xml_lang', $PREFS->ini($which));
		}
		elseif ($which == 'charset')
		{
			return $FNS->encoding_menu('charsets', 'charset', $PREFS->ini($which));
		}
	}
	// END
}
// END CLASS
?>