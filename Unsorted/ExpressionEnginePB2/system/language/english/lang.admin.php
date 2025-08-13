<?php

$L = array(

//----------------------------
// Admin Page
//----------------------------


"word_separator" =>
"Word Separator for URL Titles",

"dash" =>
"Dash",

"underscore" =>
"Underscore",

"site_name" =>
"Name of your site",

"system_admin" =>
"System Administration",

"system_preferences" => 
"System Preferences",

"is_system_on" => 
"Is system on?",

"is_system_on_explanation" =>
"If system is off, only Super Admins will be able to see the site",

"system_off_msg" => 
"System Off Message",

"offline_template" =>
"System Offline Template",

"offline_template_desc" =>
"This template contains the page that is shown when your site is offline.",

"template_updated" =>
"Template Updated",

"preference_information" =>
"Preference Guide",

"preference" => 
"Preference",

"value" => 
"Value",

"general_cfg" => 
"General Configuration",

"enable_db_caching" =>
"Enable SQL Query Caching",

"db_cache_refresh" =>
"Query Cache Pruning",

"db_cache_refresh_exp" =>
"The number of <b>DAYS</b> to keep old cache files that have not been updated.  30 days is a good number for an average site.",

"member_cfg" =>
"Membership Preferences",

"profile_templates" =>
"Member Profile Templates",

"allow_member_registration" =>
"Allow New Member Registrations?",

"req_mbr_activation" =>
"Require Member Account Activation?",

"no_activation" =>
"No activation required",

"email_activation" =>
"Self-activation via email",

"manual_activation" =>
"Manual activation by an administrator",

"require_terms_of_service" =>
"Require Terms of Service",

"member_theme" =>
"Member Profile Theme",

"member_theme_exp" =>
"Determines which theme to use for the various membership pages: login, registration, profile, etc.",

"member_images" =>
"Path to Member Images",

"member_images_exp" =>
"This is the path to the directory containing the images used in the member profile pages.",

"require_terms_of_service_exp" =>
"Setting this to yes forces users to check the \"accept terms\" checkbox during registration",

"new_member_notification" =>
"Notify administrators of new registrations?",

"mbr_notification_emails" =>
"Email Address for Notification",

"separate_emails" =>
"Separate multiple emails with a comma",

"email_console_timelock" =>
"Email Console Timelock",

"email_console_timelock_exp" =>
"The number of minutes that must lapse before a member is allowed to send another email.  Note:  This only applies to the Email Console in the member profile pages.",

"log_email_console_msgs" =>
"Log Email Console Messages",

"log_email_console_msgs_exp" =>
"This preference lets you log all messages sent via the Email Console in the member profile pages.",

"default_member_group" =>
"Default Member Group Assigned to New Members",

"group_assignment_defaults_to_two" =>
"If you require account activation, members will be set to this once they are activated",

"view_email_logs" =>
"Email Console Logs",

"user_session_type" =>
"User Session Type",

"admin_session_type" =>
"Control Panel Session Type",

"security_cfg" =>
"Security and Session Settings",

"un_min_len" =>
"Minimum Username Length",

"pw_min_len" =>
"Minimum Password Length",

"cs_session" =>
"Cookies and session ID",

"c_session" =>
"Cookies only",

"s_session" =>
"Session ID only",

"secure_forms" =>
"Process form data in Secure Mode?",

"deny_duplicate_data" =>
"Deny Duplicate Data?",

"deny_duplicate_data_explanation" =>
"This option prevents data submitted by users (comments, trackbacks, etc.) from being received if it is an exact duplicate of data that already exists.",

"secure_forms_explanation" =>
"Prevents automated spamming and multiple accidental submissions.",

"allow_multi_logins" =>
"Allow multiple log-ins from a single account?",

"allow_multi_logins_explanation" =>
"Determines whether more than one person can simultaneously access the system using the same user account.  Note: If your Session Type above is set to \"Cookies Only\" this feature will not work.",

"password_lockout" =>
"Enable Password Lockout?",

"password_lockout_explanation" =>
"If enabled, only four invalid login attempts are permitted within the time interval specified below. This is a deterrent to hackers using collision attacks to guess poorly chosen passwords.",

"password_lockout_interval" =>
"Time Interval for Lockout",

"login_interval_explanation" =>
"Number is set in minutes.  You are allowed to use decimal fractions.  Example:  1.5",

"require_ip_for_login" =>
"Require IP Address and User Agent for Login?",

"require_ip_explanation" =>
"Prevents users from logging in unless their browser generates IP Address and User Agent data. This keeps hackers from logging in using direct socket connections.",

"allow_multi_emails" =>
"Allow Multiple Accounts Using the Same Email Address?",

"allow_username_change" =>
"Allow members to change their username?",

"require_secure_passwords" =>
"Require Secure Passwords?",

"secure_passwords_explanation" =>
"Users will have to choose passwords containing at least one uppercase, one lowercase, and one numeric character",

"allow_dictionary_pw" =>
"Allow Dictionary Words as Passwords?",

"real_word_explanation" =>
"This setting prevents users from using words and names contained in a dictionary as their password",

'dictionary_note'	=>
'Note: In order to use this feature you must install the dictionary file.  Consult the manual.',

"name_of_dictionary_file" =>
"Name of Dictionary File",

"dictionary_explanation" =>
"The name of the file containing your word list",

"image_path" =>
"Path to Images Directory",

"cp_url" =>
"URL to your Control Panel index page",

"with_trailing_slash" =>
"With trailing slash",

"site_url" =>
"URL to the root directory of your site",

"url_explanation" =>
"This is the directory containing your site index file.",

"doc_url" =>
"URL to Documentation Directory",

"doc_url_explanation" =>
"Root directory only, with trailing slash",

"site_index" =>
"Name of your site's index page",

"system_path" =>
"Absolute path to your %x folder",

"force_query_string" =>
"Force URL query strings",

"safe_mode" =>
"Is your server running PHP in Safe Mode?",

"force_query_string_explanation" =>
"This is a safety mechanism for servers that do not support the PATH_INFO variable.",

"debug" =>
"Debug Preference",

"debug_explanation" =>
"Enables the display of error messages, which are valuable during site development",

"show_queries" =>
"Display SQL Queries?",

"show_queries_explanation" =>
"If enabled, Super Admins will see all SQL queries displayed at the bottom of the browser window.  Useful for debugging.",

"debug_zero" =>
"0: No PHP/SQL error messages generated",

"debug_one" =>
"1: PHP/SQL error messages shown only to Super Admins",

"debug_two" =>
"2: PHP/SQL error messages shown to anyone - NOT SECURE",

"deft_lang" =>
"Default Language",

"xml_lang" =>
"Default XML Language",

"used_in_meta_tags" =>
"Used in control panel meta tags",

"charset" =>
"Default Character Set",

"gzip_output" =>
"Enable GZIP Output?",

"gzip_output_explanation" =>
"When enabled, your site will be shown in a compressed format for faster page loading",

"send_headers" =>
"Generate HTTP Page Headers?",

"redirect_method" => 
"Redirection Method",

"location_method" => 
"Location (faster)",

"refresh_method" => 
"Refresh (Windows servers)",

"localization_cfg" => 
"Localization Settings",

"time_format" => 
"Time Formatting",

"united_states" => 
"United States",

"european" => 
"European",

"server_timezone" => 
"Server Time Zone",

"server_offset" => 
"Server Offset (in minutes)",

"server_offset_explain" => 
"Use the minus sign to subtract minutes: -15",

"daylight_savings" =>
"Daylight Saving Time",

"cookie_cfg" => 
"Cookie Settings",

"cookie_domain" => 
"Cookie Domain",

"cookie_domain_explanation" => 
"Use .yourdomain.com for  site-wide cookies",

"cookie_prefix" => 
"Cookie Prefix",

"cookie_prefix_explain" => 
"Use only if you are running multiple installation of this program",

"cookie_path" => 
"Cookie Path",

"cookie_path_explain" => 
"Use only if you require a specific server path for cookies",

"image_cfg" =>
"Image Preferences",

"enable_image_resizing" =>
"Enable Image Resizing",

"enable_image_resizing_exp" =>
"When enabled, you will be able to create thumbnails when you upload images for placement in your weblog entries.",

"image_resize_protocol" =>
"Image Resizing Protocol",

"image_resize_protocol_exp" =>
"Please check with your hosting provider to verify that your server supports the chosen protocol.",

"image_library_path" =>
"Image Library Path",

"image_library_path_exp" =>
"If you chose either ImageMagick or NetPBM you must specify the server path to the library.",

"gd" =>
"GD",

"gd2" =>
"GD 2",

"netpbm" =>
"NetPBM",

"imagemagick" =>
"ImageMagik",

"thumbnail_prefix" =>
"Image Thumbnail Suffix",

"thumbnail_prefix_exp" =>
"This suffix will be added to all auto-generated thumbnails.  Example: photo_thumb.jpg",

"email_cfg" => 
"Email Configuration",

"mail_protocol" => 
"Email Protocol",

"smtp_server" => 
"SMTP Server Address",

"smtp_username" => 
"SMTP Username",

"smtp_password" => 
"SMTP Password",

"only_if_smpte_chosen" => 
"Use this only if you chose SMTP",

"email_batchmode" =>
"Use Batch Mode?",

"batchmode_explanation" =>
"Batch Mode breaks up large mailings into smaller groups, which get sent at intervals.  Recommended if your site is hosted on a shared-hosting account.",

"email_batch_size" =>
"Number of Emails Per Batch",

"batch_size_explanation" =>
"For average servers, 300 is a safe number",

"webmaster_email" => 
"Return email address for auto-generated emails",

"return_email_explanation" => 
"If you leave this blank, many email servers will consider your email spam",

"php_mail" => 
"PHP Mail",

"sendmail" => 
"Sendmail",

"smtp" => 
"SMTP",

"plain_text" =>
"Plain Text",

"html" =>
"HTML",

"mail_format" =>
"Default Mail Format",

"word_wrap" =>
"Enable Word-wrapping by Default?",

"cp_theme" => 
"Default Control Panel Theme",

"template_cfg" =>
"Template Preferences",

"save_tmpl_revisions" =>
"Save Template Revisions by Default",

"censoring_cfg" =>
"Word Censoring",

"enable_censoring" =>
"Enable Word Censoring?",

"censored_words" =>
"Censored Words",

"censored_explanation" =>
"Place each word on a separate line",

"emoticon_cfg" =>
"Emoticon Preferences",

"enable_emoticons" =>
"Display Smileys?",

"emoticon_path" =>
"URL to the directory containing your smileys ",

"referrer_cfg" =>
"Referrer Preferences",

"log_referrers" =>
"Enable Referrer Tracking?",

"log_referrers_explanation" =>
"When enabled, one additional database query per page load will be performed.",

"weblog_administration" => 
"Weblog Administration",

"weblog_management" => 
"Weblog Management",

"field_management" => 
"Custom Weblog Fields",

"file_upload_prefs" => 
"File Upload Preferences",

"categories" => 
"Category Management",

"default_ping_servers" => 
"Default Ping Servers",

"status_management" => 
"Custom Entry Statuses",

"edit_preferences" => 
"Edit Preferences",

"preferences_updated" => 
"Preferences Updated",

"edit_groups" => 
"Edit Groups",

"members_and_groups" => 
"Members and Groups",

"view_members" => 
"View Members",

"member_validation" =>
"Activate Pending Members",

"register_member" => 
"New Member Registration",

"member_search" => 
"Member Search",

"user_banning" => 
"User Banning",

"custom_profile_fields" => 
"Custom Profile Fields",

"email_notification_template" =>
"Email Notification Templates",

"member_groups" => 
"Member Groups",

"utilities" => 
"Utilities",

"view_log_files" => 
"View Log Files",

"clear_caching" =>
"Clear Cached Data Files",

"page_caching" =>
"Page (template) cache files",

"tag_caching" =>
"Tag cache files",

"db_caching" =>
"Database cache files",

"all_caching" =>
"All cache files",

"cache_deleted" =>
"Cache files have been deleted",

"php_info" => 
"PHP Info",

"sql_manager" =>
"SQL Manager",

"sql_info" =>
"SQL Info",

"sql_utilities" =>
"SQL Utilities",

"database_type" =>
"Database Type",

"sql_version" => 
"Database Version",

"database_size" =>
"Database Size",

"database_uptime" =>
"Database Uptime",

"total_queries" =>
"Total server queries since startup",

"sql_status" => 
"Status Info",

"sql_system_vars" => 
"System Variables",

"sql_processlist" => 
"Process List",

"sql_query" => 
"Database Query Form",

"query_result" => 
"Query Result",

"browse" => 
"Browse",

"tables" => 
"tables",

"table_name" =>
"Table Name",

"records" =>
"Records",

"size" =>
"Size",

"type" =>
"Type",

"analize" =>
"Analize Tables",

"optimize" =>
"Optimize SQL Tables",

"repair" =>
"Repair SQL Tables",

"optimize_table" =>
"Optimize selected tables",

"repair_table" =>
"Repair selected tables",

"view_table_sql" =>
"View SQL structure and data",

"backup_tables_file" =>
"Backup selected tables - Text file",

"backup_tables_zip" =>
"Backup selected tables - Zip file",

"backup_tables_gzip" =>
"Backup selected tables - Gzip file",

"select_all" =>
"Select All",

"no_buttons_selected" =>
"You must select the tables in which to perform this action",

"unsupported_compression" =>
"Your PHP installation does not support this compression method",

"backup_info" =>
"Use this form to backup your database.",

"save_as_file" =>
"Save SQL backup as file",

"view_in_browser" =>
"View SQL backup in browser",

"sql_query_instructions" => 
"Use this form to submit an SQL query",

"file_type" =>
"File Type: ",

"plain_text" =>
"Plain text",

"zip" =>
"Zip",

"gzip" =>
"Gzip",

"advanced_users_only" => 
"Advanced Users Only",

"recount_stats" => 
"Recount Statistics",

"preference_updated" =>
"Preference Updated",

"click_to_recount" =>
"Click to recount rows %x through %y",

"items_remaining" =>
"Records remaining:",

"recount_completed" =>
"Recount Completed",

"return_to_recount_overview" =>
"Return to Main Recount Page",

"recounting" =>
"Recounting",

"recount_info" =>
"The links below allow you to update various statistics, like how many entries each member has submitted.",

"source" =>
"Source",

"records" =>
"Database Records",

"total_records" =>
"Total Records:",

"recalculate" =>
"Recount Statistics",

"do_recount" =>
"Perform Recount",

"set_recount_prefs" =>
"Recount Preferences",

"recount_instructions" =>
"Total number of database rows processed per batch.",

"recount_instructions_cont" =>
"In order to prevent a server timeout, we recount the statistics in batches.  1000 is a safe number for most servers. If you run a high-performance or dedicated server you can increase the number.",

"exp_members" =>
"Members",

"exp_weblog_titles" =>
"Weblog Entries",

"search_and_replace" => 
"Search and Replace",

"data_pruning" => 
"Data Pruning",

"sandr_instructions" => 
"These forms enable you to search for specific text and replace it with different text",

"search_term" => 
"Search for this text",

"replace_term" => 
"And replace it with this text",

"replace_where" => 
"In what database field do you want the replacement to occur?",

"search_replace_disclaimer" =>
"Depending on the syntax used, this function can produce undesired results.  Consult the manual.",

"title" =>
"Title",

"weblog_entry_title" =>
"Weblog Entry Titles",

"weblog_fields" =>
"Weblog Fields:",

"templates" =>
"Templates",

"rows_replaced" => 
"Number of database records in which a replacement occurred:",

"view_database" => 
"Manage Database Tables",

"sql_backup" => 
"Database Backup",

"sql_no_result" => 
"There were no results for this query.",

"sql_not_allowed" => 
"Sorry, but that is not one of the allowed query types.",

"site_statistics" =>
"Site Statistics",

"translation_tool" => 
"Translation Utility",

"translation_dir_unwritable" => 
"Warning: Your translation directory is not writable.",

"please_set_permissions" => 
"Please set the permissions to 666 or 777 on the following directory:",

"choose_translation_file" => 
"Choose a file to translate",

"core_language_files" => 
"Core language files:",

"module_language_files" => 
"Module language files:",

"file_saved" => 
"The file has been saved",

"default_html_buttons" => 
"Default HTML Buttons",

"import_from_pm" =>
"pMachine Import Utility",

"import_from_mt" =>
"Movable Type Import Utility",

"specialty_templates" =>
"Specialty Templates",

"user_messages_template" =>
"User Message Template",

"plugin_manager" =>
"Plugin Manager",

"installed_plugins" =>
"Installed Plugins",

"no_plugins_exist" =>
"There are no plugins currently installed",

"view_info" =>
"View Info",

"plugin_information" =>
"Plugin Information",

"pi_name" =>
"Name",

"pi_author" =>
"Author",

"pi_version" =>
"Version",

"pi_author_url" =>
"Author URL",

"pi_description" =>
"Description",

"pi_usage" =>
"Usage",

"no_additional_info" =>
"No additional information is available for this plugin",

// END
''=>''
);
?>