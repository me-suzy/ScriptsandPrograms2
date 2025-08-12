<?php
//===========================================================================\\
// Aardvark Topsites PHP 5                                                   \\
// Copyright (c) 2003-2005 Jeremy Scheff.  All rights reserved.              \\
//---------------------------------------------------------------------------\\
// http://www.aardvarkind.com/                        http://www.avatic.com/ \\
//---------------------------------------------------------------------------\\
// This program is free software; you can redistribute it and/or modify it   \\
// under the terms of the GNU General Public License as published by the     \\
// Free Software Foundation; either version 2 of the License, or (at your    \\
// option) any later version.                                                \\
//                                                                           \\
// This program is distributed in the hope that it will be useful, but       \\
// WITHOUT ANY WARRANTY; without even the implied warranty of                \\
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General \\
// Public License for more details.                                          \\
//===========================================================================\\

// When you make a new translation, fill out the following four variables to
// get credit for you work.
$translation = 'Bulgarian';
$translator_name = 'Ivan Kostadinov';
$translator_email = 'coolboy_@abv.bg';
$translator_url = 'http://web.hit.bg/ivan4o/';

// Global
$LNG['g_form_submit_short'] = "Íàïðåä";
$LNG['g_username'] = "Ïîòðåáèòåë";
$LNG['g_url'] = "URL";
$LNG['g_title'] = "Çàãëàâèå";
$LNG['g_description'] = "Îïèñàíèå";
$LNG['g_category'] = "Êàòåãîðèÿ"; // 4.1.0
$LNG['g_email'] = "Å-ìàéë";
$LNG['g_banner_url'] = "URL íà áàíåð";
$LNG['g_password'] = "Ïàðîëà";
$LNG['g_average'] = "Ðåéòèíã";
$LNG['g_today'] = "Äíåñ";
$LNG['g_yesterday'] = "Â÷åðà";
$LNG['g_daily'] = "Çà äåí"; // 5.0
$LNG['g_this_month'] = "Òîçè ìåñåö"; // 5.0
$LNG['g_last_month'] = "Ïðåäèøåí ìåñåö"; // 5.0
$LNG['g_monthly'] = "Ìåñå÷íî"; // 5.0
$LNG['g_this_week'] = "Òàçè ñåäìèöà"; // 5.0
$LNG['g_last_week'] = "Ïðåäèøíà ñåäìèöà"; // 5.0
$LNG['g_weekly'] = "Ñåäìè÷íî"; // 5.0
$LNG['g_pv'] = 'Âñè÷êè'; // 5.0
$LNG['g_overall'] = 'Îáîáùåíî'; // 5.0
$LNG['g_in'] = 'Âõîäÿùè'; // 5.0
$LNG['g_out'] = 'Èçõîäÿùè'; // 5.0
$LNG['g_unq_pv'] = "Óíèêàëíè"; // 5.0
$LNG['g_tot_pv'] = "Îáùî"; // 5.0
$LNG['g_unq_in'] = "Óíèêàëíè âõ."; // 5.0
$LNG['g_tot_in'] = "Îáùî âõ."; // 5.0
$LNG['g_unq_out'] = "Óíèêàëíè èçõ."; // 5.0
$LNG['g_tot_out'] = "Îáùî èçõ."; // 5.0
$LNG['g_invalid_u_or_p'] = "Íåïðàâèëåí ïîòðåáèòåë èëè ïàðîëà. Ìîëÿ îïèòàéòå îòíîâî."; // 5.0
$LNG['g_invalid_u'] = "Íåïðàâèëåí ïîòðåáèòåë. Ìîëÿ îïèòàéòå îòíîâî."; // 5.0
$LNG['g_invalid_p'] = "Íåïðàâèëíà ïàðîëà. Ìîëÿ îïèòàéòå îòíîâî."; // 5.0
$LNG['g_session_expired'] = "Ñåñèÿòà âè èçòå÷å. Ìîëÿ îïèòàéòå îòíîâî."; // 5.0
$LNG['g_error'] = "Ãðåøêà"; // 5.0
$LNG['g_delete_install'] = "Èçòðèéòå install ïðåäè äà çàðåäèòå ñòðàíèöàòà îòíîâî."; // 5.0

// Edit Account
$LNG['edit_header'] = "Ïðîìÿíà íà ïîòðåáèòåë";
$LNG['edit_info_edited'] = "Èíôîðìàöèÿòà âè å ïðîìåíåíà óñïåøíî.";
$LNG['edit_password_blank'] = "Îñòàâåòå ïðàçíî çà äà çàïàçèòå ñòàðàòà ñè ïàðîëà."; // 4.0

// Gateway Page
$LNG['gateway_header'] = "ÁÃ ÒÎÏ ñàéò";
$LNG['gateway_text'] = "Çà äà ïðåäîòâðàòèì èçìàìèòå ñå èçïîëçâà gateway ðàçïîçíàâàíå.  Êëèêíåòå âúðõó ëèíê-à çà äà âëåçåòå.";
$LNG['gateway_vote'] = "Ãëàñóâàé è âëåç.";
$LNG['gateway_no_vote'] = "Âëåç áåç äà ãëàñóâàø."; // 5.0

// Install
$LNG['install_header'] = "Install";
$LNG['install_welcome'] = "Welcome to Aardvark Topsites PHP 5.  Fill out the form below to install the script.";
$LNG['install_sql_prefix'] = "Table prefix - only change this if you are running more than one list from the same database";
$LNG['install_error_chmod'] = "Could not write to settings_sql.php.  Make sure you CHMOD 666 settings_sql.php.";
$LNG['install_error_sql'] = "Could not connect to the SQL database.  Please go back and check your SQL settings.";
$LNG['install_done'] = "Your topsites list has been installed.  Delete this directory now.";
$LNG['install_your'] = "Your Topsites List";
$LNG['install_admin'] = "Admin";
$LNG['install_manual'] = "Manual";
$LNG['upgrade_header'] = "Upgrade";
$LNG['upgrade_welcome'] = "Welcome to Aardvark Topsites PHP 5.  Before you upgrade, remember to back up your data.";
$LNG['upgrade_error_version'] = "Upgrading is only supported for Aardvark Topsites PHP 4.1.0 or higher.";
$LNG['upgrade_done'] = "Your topsites list has been upgraded.  Delete this directory now.";

// Join
$LNG['join_header'] = "Äîáàâè ñàéò";
$LNG['join_enter_text'] = "Ìîëÿ âúâåäåòå òîçè ïîòâúðæäàâàù êîä:"; // 4.2.2
$LNG['join_user'] = "Ïîòðåáèòåë"; // 5.0
$LNG['join_website'] = "Óåáñàéò"; // 5.0
$LNG['join_error_forgot'] = "Îòíîñíî:";
$LNG['join_error_username'] = "Âúâåäåòå âàëèäíî ïîòðåáèòåëñêî èìå: ìîæå äà ñå èçïîëçâàò áóêâè, öèôðè è _ ."; // 5.0
$LNG['join_error_username_duplicate'] = "Òîâà ïîòðåáèòåëñêî èìå å âå÷å çàåòî."; // 5.0
$LNG['join_error_url'] = "Âúâåäåòå âàëèäåí URL.";
$LNG['join_error_email'] = "Âúâåäåòå âàëèäåí å-ìàéë.";
$LNG['join_error_title'] = "Âúâåäåòå çàãëàâèå çà ñàéòà.";
$LNG['join_error_password'] = "Âúâåäåòå ïàðîëà.";
$LNG['join_error_urlbanner'] = "Âúâåäåòå âàëèäåí áàíåð.  Îñòàâåòå ïðàçíî àêî íÿìàòå òàêúâ.  Áàíåðúò òðÿáâà äà å ïî-ìàëúê îò"; // 4.0
$LNG['join_error_back'] = "Ìîëÿ âúðíåòå ñå íàçàä çà äà ïîïðàâèòå ãðåøêàòà.";
$LNG['join_error_time'] = "Ìîëÿ íàòèñêàéòå áóòîíà ÄÎÁÀÂÈ ÑÀÉÒ ñàìî âåäíúæ."; // 4.2.0
$LNG['join_error_captcha'] = "Ãðåøåí ïîòâúðæäàâàù êîä."; // 4.2.2
$LNG['join_thanks'] = "Áëàãîäàðèì âè ÷å ñå ðåãèñòðèðàõòå!  Ñëîæåòå òîçè êîä íà âàøàòà ñòðàíèöà çà äà áúäåòå îöåíåí.";
$LNG['join_change_warning'] = "Àêî ïðîìåíèòå òîçè êîä òîé ìîæå äà íå ïðîðàáîòè.";
$LNG['join_welcome'] = "Äîáðå äîøëè â %s";
$LNG['join_welcome_admin'] = "Èìà íîâ ó÷àñòíèê.";

// Link Code
$LNG['link_code_header'] = "Êîä çà ãëàñóâàíå"; // 5.0

// Lost Password
$LNG['lost_pw_header'] = "Èçãóáåíà ïàðîëà"; // 5.0
$LNG['lost_pw_forgot'] = "Çàáðàâåíà ïàðîëà?"; // 5.0
$LNG['lost_pw_get'] = "Âçåìè ïàðîëàòà"; // 5.0
$LNG['lost_pw_emailed'] = "Ìîëÿ ïðîâåðåòå å-ìàéëà ñè çà èíñòðóêöèè."; // 5.0
$LNG['lost_pw_email'] = "Çà äà ñìåíèòå ïàðîëàòà ñè îòèäåòå íà òîçè àäðåñ:"; // 5.0
$LNG['lost_pw_new'] = "Íîâà ïàðîëà"; // 5.0
$LNG['lost_pw_set_new'] = "Ïðîìåíè"; // 5.0
$LNG['lost_pw_finish'] = "Ïàðîëàòà âè å ñìåíåíà"; // 5.0

// Main Page
$LNG['main_header'] = "Ãëàñîâå"; // 5.0
$LNG['main_all'] = "Êàòåãîðèÿ - Âñè÷êè"; // 4.2.0
$LNG['main_method'] = "Ìåòîä íà ãëàñóâàíå:";
$LNG['main_members'] = "Ïîòðåáèòåëè";
$LNG['main_menu_rankings'] = "Êëàñàöèÿ";
$LNG['main_menu_join'] = "Äîáàâè ñàéò";
$LNG['main_menu_random'] = "Ïðîèçâîëåí";
$LNG['main_menu_search'] = "Òúðñè";
$LNG['main_menu_lost_code'] = "Çàãóáåí êîä çà âðúçêà"; // 5.0
$LNG['main_menu_lost_password'] = "Çàãóáåíà ïàðîëà"; // 5.0
$LNG['main_menu_edit'] = "Ðåäàêöèÿ íà ïîòðåáèòåë";
$LNG['main_menu_user_cp'] = "Âõîä"; // 5.0
$LNG['main_featured'] = "Featured Member"; // 4.0.2
$LNG['main_executiontime'] = "Âðåìå çà çàðåæäàíå"; // 4.0
$LNG['main_queries'] = "SQL çàïèòâàíèÿ"; // 4.0
$LNG['main_powered'] = "Áëàãîäàðåíèå íà";

// Ranking Table
$LNG['table_stats'] = "Èíôî";
$LNG['table_unique'] = "Óíèêàëíè";
$LNG['table_total'] = "Îáùî";
$LNG['table_rank'] = "Ðàíã";
$LNG['table_title'] = "Çàãëàâèå"; // 4.0
$LNG['table_description'] = "Îïèñàíèå"; // 4.0
$LNG['table_movement'] = "Äâèæåíèå";
$LNG['table_up'] = "Íàãîðå"; // 5.0
$LNG['table_down'] = "Íàäîëó"; // 5.0
$LNG['table_neutral'] = "Íåóòðàëíî"; // 5.0

// Rate and Review
$LNG['rate_header'] = "Ãëàñóâàè è îñòàâè êîìåíòàð";
$LNG['rate_rating'] = "Ãëàñóâàíå";
$LNG['rate_review'] = "Êîìåíòàðè - íå å ïîçâîëåí HTML"; // 5.0
$LNG['rate_thanks'] = "Áëàãîäàðèì âè, ÷å ãëàñóâàõòå.";
$LNG['rate_error'] = "Âèå âå÷å ñòå ãëàñóâàëè çà òîçè ñàéò.";
$LNG['rate_back'] = "Îáðàòíî êúì ñòàòèñòèêà";

// Search
$LNG['search_header'] = "Òúðñè";
$LNG['search_off'] = "Òúðñåíåòî å áëîêèðàíî.";
$LNG['search_for'] = "Âèå òúðñèòå";
$LNG['search_no_sites'] = "Ñúæàëÿâàìå, íî íå îòêðèõìå íèùî ïî âàøåòî çàïèòâàíå."; // 5.0
$LNG['search_prev'] = "íàçàä"; // 3.2.1
$LNG['search_next'] = "íàïðåä"; // 3.2.1

// Stats
$LNG['stats_header'] = "Ñòàòèñòèêà";
$LNG['stats_info'] = "Èíôî";
$LNG['stats_member_since'] = "Ðåãèñòðèðàí"; // 5.0
$LNG['stats_rating_avg'] = "Öÿëîñòåí ðåéòèíã";
$LNG['stats_rating_num'] = "Ãëàñîâå";
$LNG['stats_rate'] = "Ãëàñóâàé è îñòàâè êîìåíòàð.";
$LNG['stats_reviews'] = "Êîìåíòàðè";
$LNG['stats_allreviews'] = "Ïîêàæè âñè÷êè êîìåíòàðè"; // 4.0
$LNG['stats_week'] = "Ñåäìèöà"; // 5.0
$LNG['stats_highest'] = "Íàé-âèñîê"; // 5.0

// ssi.php
$LNG['ssi_top'] = "Top %s ñàéòîâå"; // 4.0
$LNG['ssi_new'] = "%s Íîâè ïîòðåáèòåëè"; // 5.0
$LNG['ssi_all'] = "Âñè÷êè"; // 4.0

// User Control Panel // 5.0
$LNG['user_cp_header'] = "Êîíòðîëåí ïàíåë"; // 5.0
$LNG['user_cp_login'] = "Âëåç"; // 5.0
$LNG['user_cp_logout'] = "Èçõîä"; // 5.0
$LNG['user_cp_welcome'] = "Äîáðå äîøëè â êîíòðîëíèÿ ïàíåë. Îò òóê ìîæå äà ïðîìåíÿòå íàñòðîéêèòå ñè."; // 5.0
$LNG['user_cp_logout_message'] = "Âèå èçëÿçîõòå îò êîíòðîëíèÿ ïàíåë."; // 5.0

// Admin > Approve New Members // 4.0
$LNG['a_approve_header'] = "Approve New Members"; // 5.0
$LNG['a_approve'] = "Approve"; // 4.0
$LNG['a_approve_none'] = "There are no members waiting to be approved."; // 4.0
$LNG['a_approve_done'] = "The member has been approved."; // 4.0
$LNG['a_approve_dones'] = "The members have been approved."; // 4.0
$LNG['a_approve_sel'] = "With selected:"; // 5.0

// Admin > Approve New Reviews // 5.0
$LNG['a_approve_rev_header'] = "Approve New Reviews"; // 5.0
$LNG['a_approve_rev_none'] = "There are no reviews waiting to be approved."; // 5.0
$LNG['a_approve_rev_done'] = "The review has been approved."; // 5.0
$LNG['a_approve_rev_dones'] = "The reviews have been approved."; // 5.0

// Admin > Delete Member
$LNG['a_del_header'] = "Delete Member"; // 5.0
$LNG['a_del_headers'] = "Delete Members"; // 5.0
$LNG['a_del_done'] = "The member has been deleted."; // 5.0
$LNG['a_del_dones'] = "The members have been deleted."; // 5.0
$LNG['a_del_warn'] = "Are you sure you want to delete %s?"; // 5.0
$LNG['a_del_multi'] = "these %s members"; //5.0

// Admin > Delete Review // 5.0
$LNG['a_del_rev_header'] = "Delete Review"; // 5.0
$LNG['a_del_rev_headers'] = "Delete Reviews"; // 5.0
$LNG['a_del_rev_done'] = "The review has been deleted."; // 5.0
$LNG['a_del_rev_dones'] = "The reviews have been deleted."; // 5.0
$LNG['a_del_rev_warn'] = "Are you sure you want to delete this review?"; //5.0
$LNG['a_del_rev_warns'] = "Are you sure you want to delete these reviews?"; //5.0
$LNG['a_del_rev_invalid_id'] = "Invalid review ID.  Please try again."; // 5.0

// Admin > Edit Member
$LNG['a_edit_header'] = "Edit Member"; // 5.0
$LNG['a_edit_site_is'] = "This site is"; // 4.0
$LNG['a_edit_active'] = "Active (Listed)"; // 4.0
$LNG['a_edit_inactive'] = "Inactive (Not Listed)"; // 5.0
$LNG['a_edit_edited'] = "The member has been edited.";

// Admin > Edit Review // 5.0
$LNG['a_edit_rev_header'] = "Edit Review"; // 5.0
$LNG['a_edit_rev_edited'] = "The review has been edited.";

// Admin > Email Members
$LNG['a_email_header'] = "Email Members"; // 5.0
$LNG['a_email_subject'] = "Subject"; // 4.2.0
$LNG['a_email_message'] = "Message"; // 4.2.0
$LNG['a_email_msg_sent'] = "An email has been sent to %s"; // 5.0
$LNG['a_email_not_sent'] = "An email couldn't be sent to %s"; // 5.0
$LNG['a_email_sent'] = "%s members were emailed."; // 4.2.0
$LNG['a_email_failed'] = "%s members were not emailed."; // 4.2.0

// Admin > Logout
$LNG['a_logout_message'] = "You are now logged out of the admin."; // 5.0

// Admin > Main
$LNG['a_header'] = "Admin"; // 5.0
$LNG['a_main'] = "Welcome to the admin.  Use the links to the left to manage your topsites list."; // 5.0
$LNG['a_main_approve'] = "There is 1 site waiting to be approved."; // 5.0
$LNG['a_main_approves'] = "There are %s sites waiting to be approved."; // 5.0
$LNG['a_main_approve_rev'] = "There is 1 review waiting to be approved."; // 5.0
$LNG['a_main_approve_revs'] = "There are %s reviews waiting to be approved."; // 5.0
$LNG['a_main_your'] = "Your version"; // 5.0
$LNG['a_main_latest'] = "Latest version"; // 5.0
$LNG['a_main_new'] = "<a href=\"http://www.aardvarkind.com/\">Aardvark Topsites PHP Website</a>"; // 5.0

// Admin > Manage Members
$LNG['a_man_header'] = "Manage Members"; // 5.0
$LNG['a_man_actions'] = "Actions"; // 4.2.0
$LNG['a_man_edit'] = "Edit"; // 4.2.0
$LNG['a_man_delete'] = "Delete"; // 4.2.0
$LNG['a_man_email'] = "Email"; // 4.2.0
$LNG['a_man_all'] = "Select All"; // 5.0
$LNG['a_man_none'] = "Select None"; // 5.0
$LNG['a_man_del_sel'] = "Delete Selected"; // 5.0

// Admin > Manage Reviews // 5.0
$LNG['a_man_rev_header'] = "Manage Reviews"; // 5.0
$LNG['a_man_rev_enter'] = "To manage the reviews of a site, enter the member's username below."; // 5.0
$LNG['a_man_rev_id'] = "ID"; // 5.0
$LNG['a_man_rev_rev'] = "Review"; // 5.0
$LNG['a_man_rev_date'] = "Date"; // 5.0

// Admin > Menu
$LNG['a_menu'] = "Menu";
$LNG['a_menu_main'] = "Main"; // 5.0
$LNG['a_menu_approve'] = "Approve New Members";
$LNG['a_menu_manage'] = "Manage Members"; // 4.2.0
$LNG['a_menu_settings'] = "Change Settings"; // 5.0
$LNG['a_menu_skins'] = "Skins and Categories"; // 5.0
$LNG['a_menu_approve_reviews'] = "Approve New Reviews"; // 5.0
$LNG['a_menu_manage_reviews'] = "Manage Reviews"; // 5.0
$LNG['a_menu_email'] = "Email Members";
$LNG['a_menu_delete_review'] = "Delete Review";
$LNG['a_menu_logout'] = "Logout";
$LNG['a_menu_delete'] = "Delete Member";
$LNG['a_menu_edit'] = "Edit Member";
$LNG['a_header_members'] = "Members"; // 5.0
$LNG['a_header_settings'] = "Settings"; // 5.0
$LNG['a_header_reviews'] = "Reviews"; // 5.0

// Admin > Settings
$LNG['a_s_header'] = "Change Settings";
$LNG['a_s_general'] = "General Settings";
$LNG['a_s_admin_password'] = "Admin password";
$LNG['a_s_list_name'] = "The name of your topsites list";
$LNG['a_s_list_url'] = "URL to the topsites directory";
$LNG['a_s_default_language'] = "Default language";
$LNG['a_s_your_email'] = "Your email address";

$LNG['a_s_sql'] = "SQL Settings";
$LNG['a_s_sql_type'] = "Database Type"; // 4.1.0
$LNG['a_s_sql_host'] = "Host";
$LNG['a_s_sql_database'] = "Database";
$LNG['a_s_sql_username'] = "Username";
$LNG['a_s_sql_password'] = "Password";

$LNG['a_s_ranking'] = "Ranking Settings";
$LNG['a_s_num_list'] = "Number of members to list per page"; // 5.0
$LNG['a_s_ranking_period'] = "Ranking period"; // 5.0
$LNG['a_s_ranking_method'] = "Ranking method"; // 5.0
$LNG['a_s_ranking_average'] = "Rank by average or by just %s"; // 5.0
$LNG['a_s_featured_member'] = 'Featured member - You have to add {$featured_member} to wrapper.html after you turn this on.'; // 4.1.0
$LNG['a_s_top_skin_num'] = "Number of members to use the _top skin for";
$LNG['a_s_ad_breaks'] = "Show ad breaks after these ranks (separate with commas)";

$LNG['a_s_member'] = "Member Settings";
$LNG['a_s_active_default'] = "Require new members to be approved before being listed";
$LNG['a_s_active_default_review'] = "Require new reviews to be approved before being listed";
$LNG['a_s_delete_after'] = "Delete inactive members after this many days (set to 0 to turn off)"; // 4.1.0
$LNG['a_s_email_admin_on_join'] = "Email you when a new member joins";
$LNG['a_s_max_banner_width'] = "Member's maximum banner width (set to 0 to turn off)"; // 4.2.0
$LNG['a_s_max_banner_height'] = "Member's maximum banner height (set to 0 to turn off)"; // 4.2.0
$LNG['a_s_default_banner'] = "Default banner for members who do not supply one";

$LNG['a_s_button'] = "Button Settings";
$LNG['a_s_ranks_on_buttons'] = "Ranks on buttons -  See <a href=\"http://www.aardvarkind.com/topsitesphp/manual/\">the manual</a> for details.  Only choose Stat Buttons if you have already read that section of the manual.  If you choose Stat Buttons, the rest of this section will not have an effect."; // 4.2.0
$LNG['a_s_stat_buttons'] = "Stat Buttons"; // 4.2.0
$LNG['a_s_button_url'] = "If Yes/No - URL to the default button you want to appear on members' sites"; // 4.0
$LNG['a_s_button_dir'] = "If Yes - URL to the directory the buttons are in"; // 4.0
$LNG['a_s_button_ext'] = "If Yes - Extension of the buttons (gif, png, jpg, etc.)"; // 4.0
$LNG['a_s_button_num'] = "If Yes - Number of buttons you have made"; // 4.0

$LNG['a_s_other'] = "Other Settings";
$LNG['a_s_search'] = "Search";
$LNG['a_s_time_offset'] = "Time offset from your server (in hours)";
$LNG['a_s_gateway'] = "Gateway page to deter cheating for hits in";
$LNG['a_s_captcha'] = "Word verification on join - Security against spammers"; // 4.2.2

$LNG['a_s_on'] = "On";
$LNG['a_s_off'] = "Off";
$LNG['a_s_days'] = "Days";
$LNG['a_s_months'] = "Months";
$LNG['a_s_weeks'] = "Weeks"; // 4.2.0
$LNG['a_s_yes'] = "Yes";
$LNG['a_s_no'] = "No";

$LNG['a_s_updated'] = "Your settings have been updated.";

// Admin > Skins and Categories // 5.0
$LNG['a_skins_header'] = "Skins and Categories"; // 5.0
$LNG['a_skins_default'] = "Default Skin"; // 5.0
$LNG['a_skins_set_default'] = "Set Default Skin"; // 5.0
$LNG['a_skins_anon'] = "Anonymous"; // 5.0
$LNG['a_skins_default_done'] = "The default skin has been set."; // 5.0
$LNG['a_skins_categories_done'] = "The category skins have been set."; // 5.0
$LNG['a_skins_new_category_done'] = "The new category has been created."; // 5.0
$LNG['a_skins_delete_done'] = "The category has been deleted."; // 5.0
$LNG['a_skins_edit_done'] = "The category has been edited."; // 5.0
$LNG['a_skins_invalid_skin'] = "Invalid skin: %s.  Please try again."; // 5.0
$LNG['a_skins_categories'] = "Categories"; // 5.0
$LNG['a_skins_new_category'] = "Create New Category"; // 5.0
$LNG['a_skins_set_skins'] = "Set Category Skins"; // 5.0
$LNG['a_skins_edit_category'] = "Edit Category"; // 5.0
$LNG['a_skins_category_name'] = "Category Name"; // 5.0
$LNG['a_skins_diff_skins'] = "If you want different skins for different categories, select them below."; // 5.0
?>
