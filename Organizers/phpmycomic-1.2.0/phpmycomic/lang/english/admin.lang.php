<?php

// Menu options
$tpl->assign("lang_menu_setup", "System Settings");
$tpl->assign("lang_menu_personal", "Personal Info");
$tpl->assign("lang_menu_user", "Users");
$tpl->assign("lang_menu_users", "Manage Users");
$tpl->assign("lang_menu_newuser", "New User");
$tpl->assign("lang_menu_backup", "Backup");
$tpl->assign("lang_menu_manage", "Manage");
$tpl->assign("lang_menu_images", "Manage Images");
$tpl->assign("lang_menu_options", "Manage Options");
$tpl->assign("lang_menu_loans", "Manage Loans");
$tpl->assign("lang_menu_favs", "Manage Favorites");
$tpl->assign("lang_menu_exit", "Exit");
$tpl->assign("lang_menu_home", "Home");
$tpl->assign("lang_menu_pref", "Preferences");
$tpl->assign("lang_menu_index", "Index Page");
$tpl->assign("lang_option", "Option");

// Head options
$tpl->assign("lang_head_mainpage", "Main Page");
$tpl->assign("lang_head_system", "System Configurations");
$tpl->assign("lang_head_index", "Index Page Settings");
$tpl->assign("lang_head_personal", "Personal Options");
$tpl->assign("lang_head_users", "Manage Users");
$tpl->assign("lang_head_edituser", "Edit User");
$tpl->assign("lang_head_newuser", "Add New User");
$tpl->assign("lang_head_backup", "Backup");
$tpl->assign("lang_head_backups", "Previous Backups");
$tpl->assign("lang_head_images", "Manage Images");
$tpl->assign("lang_head_manage", "Manage Artists");
$tpl->assign("lang_head_newoption", "Add New Option");
$tpl->assign("lang_head_loans", "Manage Loans");
$tpl->assign("lang_head_favs", "Manage Favorites");
$tpl->assign("lang_head_loanedit", "Edit Loan Info");
$tpl->assign("lang_head_edit", "Edit Item");

// Button options
$tpl->assign("lang_button_artist", "Add Artist");
$tpl->assign("lang_button_adduser", "New User");
$tpl->assign("lang_button_change", "Change");
$tpl->assign("lang_button_edit", "Edit");

// System Config Options
$tpl->assign("lang_sql_option", "MySql Options");
$tpl->assign("lang_sql_host", "MySql Hostname");
$tpl->assign("lang_sql_user", "MySql Username");
$tpl->assign("lang_sql_pass", "MySql Password");
$tpl->assign("lang_sql_data", "MySql Database");
$tpl->assign("lang_sys_paginate", "How many rows per page?");
$tpl->assign("lang_sys_option", "Program Options");
$tpl->assign("lang_sys_title", "Site Title");
$tpl->assign("lang_sys_rss", "Enable RSS Feed");
$tpl->assign("lang_sys_fav", "Enable Favorite System");
$tpl->assign("lang_sys_site", "PhpMyComic URL. Remember the trailing slash (/)");
$tpl->assign("lang_sys_theme", "Choose a theme");
$tpl->assign("lang_sys_lang", "Choose a language");
$tpl->assign("lang_sys_pdf", "Enable PDF");
$tpl->assign("lang_sys_loan", "Enable loan system");
$tpl->assign("lang_sys_print", "Enable Printer Friendly");
$tpl->assign("lang_img_option", "Image Options");
$tpl->assign("lang_img_width", "Max image width (pixel)");
$tpl->assign("lang_img_height", "Max image height (pixel)");
$tpl->assign("lang_img_size", "Max image size (bytes)");
$tpl->assign("lang_date_option", "Date and time Options");
$tpl->assign("lang_date_format", "Date / Time option");
$tpl->assign("lang_date_alt", "Click <a href=\"http://no.php.net/manual/en/function.date.php\" class=\"defaultlink\" target=\"_blank\">here</a> for alternatives");
$tpl->assign("lang_yes", "Yes");
$tpl->assign("lang_no", "No");

// Index Page Options
$tpl->assign("lang_stats_short", "Short");
$tpl->assign("lang_stats_full", "Full");
$tpl->assign("lang_list_latest", "Latest Comics");
$tpl->assign("lang_list_favs", "Favorites");
$tpl->assign("lang_index_head", "Front Page Settings");
$tpl->assign("lang_index_list", "Show what in list?");
$tpl->assign("lang_index_stats", "Enable Statistics?");
$tpl->assign("lang_index_statstype", "Show short or full stats?");
$tpl->assign("lang_index_number", "Show how many rows?");

// Edit
$tpl->assign("lang_edit_info", "Edit Information");

// Main options
$tpl->assign("lang_adminname", "Admin Name");
$tpl->assign("lang_current", "Current PhpMyComic Version");
$tpl->assign("lang_installdate", "Installation Date");
$tpl->assign("lang_current_theme", "Current Theme");
$tpl->assign("lang_theme_version", "Theme Version");
$tpl->assign("lang_theme_author", "Theme Author");
$tpl->assign("lang_current_lang", "Current Language");
$tpl->assign("lang_lang_author", "Language Author");

// Personal
$tpl->assign("lang_user_name", "Your Name");
$tpl->assign("lang_user_mail", "You E-Mail");
$tpl->assign("lang_user_pass1", "Admin Password");
$tpl->assign("lang_user_pass2", "Confirm Password");
$tpl->assign("lang_user_user", "Admin Username");

// Manage loans
$tpl->assign("lang_loan_comic", "Comic title");
$tpl->assign("lang_loan_name", "Loaned to");
$tpl->assign("lang_loan_date", "Loan Date");
$tpl->assign("lang_loan_due", "Due Date");
$tpl->assign("lang_loan_notes", "Notes");
$tpl->assign("lang_loan_option", "Options");
$tpl->assign("lang_comic_name", "Comic title and issue number");
$tpl->assign("lang_comic_story", "Story title");
$tpl->assign("lang_lend_name", "Loan this comic to");
$tpl->assign("lang_lend_notes", "Other notes");
$tpl->assign("lang_lend_date", "Loan date");
$tpl->assign("lang_lend_due", "Due return date");
$tpl->assign("lang_comic_info", "Comic Info");
$tpl->assign("lang_loan_info", "Loan Info");
$tpl->assign("lang_loan_disabled", "You have disabled the loan system. This can be enabled through the setup page");
$lang_no_duedate = "No due date";

// Manage favs
$tpl->assign("lang_favs_comic", "Comic title");
$tpl->assign("lang_favs_date", "Added Date");
$tpl->assign("lang_favs_story", "Story");
$tpl->assign("lang_favs_option", "Options");
$tpl->assign("lang_favs_disabled", "You have disabled the favorite system. This can be enabled through the setup page");

// Users
$tpl->assign("lang_list_user", "Username");
$tpl->assign("lang_list_name", "Real Name");
$tpl->assign("lang_list_mail", "E-Mail");
$tpl->assign("lang_list_edit", "Edit");
$tpl->assign("lang_list_delete", "Delete");
$lang_edit = "Edit";
$lang_delete = "Delete";
$lang_remove = "Remove";
$lang_load = "Load";

// Add User
$tpl->assign("lang_add_name", "Real Name");
$tpl->assign("lang_add_pass1", "Enter Password");
$tpl->assign("lang_add_pass2", "Confirm Password");
$tpl->assign("lang_add_editpass", "New Password (Only use for new password)");
$tpl->assign("lang_add_mail", "E-Mail Adress");
$tpl->assign("lang_add_user", "Username");

// Backup
$tpl->assign("lang_backup_text", "You can at any time backup your database. All you backups will be listed and can be loaded back into your database!");
$tpl->assign("lang_backup_file", "File Name");
$tpl->assign("lang_backup_date", "Backup Date");
$tpl->assign("lang_backup_option", "Option");
$tpl->assign("lang_backup_now", "Backup Now!");

// Images
$tpl->assign("lang_image_name", "File Name");
$tpl->assign("lang_image_image", "Image Size");
$tpl->assign("lang_image_file", "File Size");
$tpl->assign("lang_image_option", "Options");

// Manage
$tpl->assign("lang_comictype", "Comic Type");
$tpl->assign("lang_format", "Issue Format");
$tpl->assign("lang_condition", "Issue Condition");
$tpl->assign("lang_variation", "Issue Variation");
$tpl->assign("lang_currency", "Currencies");
$tpl->assign("lang_manage_name", "Name");
$tpl->assign("lang_manage_option", "Options");
$tpl->assign("lang_add_new", "Add new");
?>