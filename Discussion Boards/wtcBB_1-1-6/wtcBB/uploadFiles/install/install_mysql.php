<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################### //INSTALL - MYSQL\\ ################### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

$mysql = Array();

// ##### CREATE QUERIES ###### \\

$mysql['create']['user_info'] = "CREATE TABLE user_info (
userid SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
username VARCHAR (255),
username_html_begin MEDIUMTEXT,
username_html_end MEDIUMTEXT,
password VARCHAR (255),
passwordDate INT,
vBsalt CHAR (3),
usertitle VARCHAR (255),
usertitle_option TINYINT (2),
email VARCHAR (255),
newEmail VARCHAR (255),
newEmailDate INT,
last_emailed INT,
parent_email VARCHAR (255),
is_coppa TINYINT (1),
usergroupid TINYINT (255),
date_joined INT,
birthday VARCHAR (255),
birthday_year TINYINT (1),
aim VARCHAR (255),
msn VARCHAR (255),
icq VARCHAR (255),
yahoo VARCHAR (255),
homepage VARCHAR (255),
biography VARCHAR (255),
locationUser VARCHAR (255),
interests VARCHAR (255),
occupation VARCHAR (255),
user_ip_address VARCHAR (255),
referrals SMALLINT DEFAULT '0',
referral_username MEDIUMTEXT,
signature MEDIUMTEXT,
posts INT,
threads INT,
lastvisit INT,
lastactivity INT,
lastpost INT,
lastpostid INT,
lastPM INT,
invisible TINYINT (1) DEFAULT '0',
admin_send_email TINYINT (1) DEFAULT '1',
receive_emails TINYINT (1) DEFAULT '0',
use_pm TINYINT (1) DEFAULT '1',
send_email_pm TINYINT (1) DEFAULT '0',
popup_pm TINYINT (1) DEFAULT '0',
view_signature TINYINT (1) DEFAULT '1',
view_avatar TINYINT (1) DEFAULT '1',
view_attachment TINYINT (1) DEFAULT '1',
display_order VARCHAR (255) DEFAULT 'ASC',
view_posts SMALLINT,
date_default_thread_age VARCHAR (255),
date_timezone TINYINT (12),
dst TINYINT (1) DEFAULT '0',
style_id SMALLINT DEFAULT '0',
avatar_url VARCHAR (255) DEFAULT 'none',
allow_html TINYINT (1),
toolbar TINYINT (1) DEFAULT '1',
ban_sig TINYINT (1),
auto_threadsubscription TINYINT (1),
useridHash VARCHAR (32),
default_font VARCHAR (255),
default_color VARCHAR (255),
default_size VARCHAR (255),
useDefault TINYINT (1),
warn MEDIUMINT,
enableGuestbook TINYINT (1)
);";

$mysql['create']['forums'] = "CREATE TABLE forums (
forumid SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
forum_name VARCHAR (255),
forum_description VARCHAR (255),
date_made INT,
link_redirect MEDIUMTEXT,
link_redirect_counter INT DEFAULT 0,
fpassword VARCHAR (255),
last_reply_username VARCHAR (255),
last_reply_userid MEDIUMINT,
last_reply_date INT,
last_reply_threadid MEDIUMINT,
last_reply_threadtitle VARCHAR (255),
is_category TINYINT (1) DEFAULT '1',
is_active TINYINT (1) DEFAULT '1',
is_open TINYINT (1) DEFAULT '1',
category_parent VARCHAR (255),
display_order SMALLINT,
default_view INT,
allow_wtcBB TINYINT (1) DEFAULT '1',
allow_smilies TINYINT (1) DEFAULT '1',
allow_img TINYINT (1) DEFAULT '0',
allow_html TINYINT (1) DEFAULT '1',
allow_posticons TINYINT (1),
show_on_forumjump TINYINT (1) DEFAULT '1',
count_posts TINYINT (1) DEFAULT '1',
posts MEDIUMINT DEFAULT '0',
threads MEDIUMINT DEFAULT '0',
default_style MEDIUMINT,
override_user_style TINYINT (1) DEFAULT '0',
childlist MEDIUMTEXT,
depth TINYINT (255),
INDEX(is_category)
);";

$mysql['create']['threads'] = "CREATE TABLE threads (
threadid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
forumid SMALLINT,
thread_name VARCHAR (255),
thread_starter MEDIUMINT,
threadUsername VARCHAR (255),
thread_views SMALLINT DEFAULT '0',
thread_replies SMALLINT DEFAULT '0',
last_reply_username VARCHAR (255),
last_reply_userid MEDIUMINT,
last_reply_date INT,
last_reply_postid MEDIUMINT UNSIGNED,
post_icon_thread VARCHAR (255),
deleted_thread TINYINT (1) DEFAULT '0',
deleted_by_thread VARCHAR (255),
deleted_reason_thread MEDIUMTEXT,
delete_time_thread INT,
closed TINYINT (1) DEFAULT '0',
sticky TINYINT (1) DEFAULT '0',
moved MEDIUMINT,
poll TINYINT (1) DEFAULT '0',
date_made INT,
first_post MEDIUMINT UNSIGNED,
INDEX(deleted_thread),
INDEX(thread_starter),
INDEX(threadUsername)
);";

$mysql['create']['posts'] = "CREATE TABLE posts (
postid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
threadid MEDIUMINT,
forumid MEDIUMINT,
userid SMALLINT,
postUsername VARCHAR (255),
message MEDIUMTEXT,
title VARCHAR (255),
ip_address VARCHAR (255),
date_posted INT,
post_icon VARCHAR (255),
deleted TINYINT (1) DEFAULT '0',
deleted_by VARCHAR (255),
deleted_reason MEDIUMTEXT,
deleted_time INT,
edited_by VARCHAR (255),
edited_time INT,
show_sig TINYINT (1),
parse_smilies TINYINT (1),
parse_bbcode TINYINT (1),
defBBCode TINYINT (1),
INDEX(deleted),
INDEX(threadid),
INDEX(forumid),
INDEX(userid),
INDEX(postUsername)
);";

$mysql['create']['thread_subscription'] = "CREATE TABLE thread_subscription (
subscribeid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
userid SMALLINT UNSIGNED,
threadid MEDIUMINT,
INDEX(userid),
INDEX(threadid)
);";

$mysql['create']['personal_msg'] = "CREATE TABLE personal_msg (
pid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
title VARCHAR (255),
message MEDIUMTEXT,
userid SMALLINT,
sentTo SMALLINT,
ip_address VARCHAR (255),
date_sent INT,
alert TINYINT (1) DEFAULT '0',
isRead TINYINT (1) DEFAULT '1',
folderid MEDIUMINT,
pmHash VARCHAR (255),
show_sig TINYINT (1),
parse_smilies TINYINT (1),
parse_bbcode TINYINT (1),
recipients MEDIUMTEXT,
defBBCode TINYINT (1),
INDEX(sentTo),
INDEX(alert)
);";

$mysql['create']['personal_folder'] = "CREATE TABLE personal_folder (
folderid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
folderName VARCHAR (255),
userid SMALLINT,
INDEX(userid)
);";

$mysql['create']['personal_rules'] = "CREATE TABLE personal_rules (
ruleid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
userid SMALLINT,
userORgroup VARCHAR (255),
value VARCHAR (255),
moveORdelete VARCHAR (255),
value2 MEDIUMINT,
exec_order SMALLINT,
INDEX(userid)
);";

$mysql['create']['personal_receipt'] = "CREATE TABLE personal_receipt (
receiptid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
receipt_pid MEDIUMINT UNSIGNED,
receipt_title VARCHAR (255),
confirmed TINYINT (1) DEFAULT '0',
checked TINYINT (1) DEFAULT '0',
userid MEDIUMINT,
receipt_sentTo MEDIUMINT,
receipt_sent INT,
receipt_received INT,
INDEX(userid),
INDEX(receipt_sentTo)
);";

$mysql['create']['usergroups'] = "CREATE TABLE usergroups (
usergroupid TINYINT (255) UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
name VARCHAR (255),
name_html_begin MEDIUMTEXT,
name_html_end MEDIUMTEXT,
usertitle VARCHAR (255),
description VARCHAR (255),
is_super_moderator TINYINT (1) DEFAULT '0',
is_admin TINYINT (1) DEFAULT '0',
show_groups TINYINT (1) DEFAULT '0',
show_memberlist TINYINT (1) DEFAULT '0',
show_birthdays TINYINT (1) DEFAULT '1',
is_banned TINYINT (1) DEFAULT '0',
see_invisible TINYINT (1) DEFAULT '0',
see_profile TINYINT (1) DEFAULT '0',
edit_own_profile TINYINT (1) DEFAULT '0',
can_invisible TINYINT (1) DEFAULT '1',
show_edited_notice TINYINT (1) DEFAULT '1',
can_usertitle TINYINT (1) DEFAULT '0',
can_sig TINYINT (1) DEFAULT '1',
can_view_board TINYINT (1) DEFAULT '1',
can_view_threads TINYINT (1) DEFAULT '1',
can_view_deletion TINYINT (1) DEFAULT '0',
can_perm_delete TINYINT (1) DEFAULT '0',
can_search TINYINT (1) DEFAULT '1',
can_attachments TINYINT (1) DEFAULT '1',
can_post_threads TINYINT (1) DEFAULT '1',
can_reply_own TINYINT (1) DEFAULT '1',
can_reply_others TINYINT (1) DEFAULT '1',
can_edit_own TINYINT (1) DEFAULT '1',
can_delete_own TINYINT (1) DEFAULT '0',
can_close_own TINYINT (1) DEFAULT '0',
can_delete_threads_own TINYINT (1) DEFAULT '0',
can_upload_attachments TINYINT (1) DEFAULT '1',
attachment_filesize INT,
flood_immunity TINYINT (1) DEFAULT '0',
can_post_polls TINYINT (1) DEFAULT '1',
can_vote_polls TINYINT (1) DEFAULT '1',
can_use_avatar TINYINT (1) DEFAULT '1',
can_upload_avatar TINYINT (1) DEFAULT '1',
avatar_width SMALLINT DEFAULT '64',
avatar_height SMALLINT DEFAULT '64',
avatar_filesize INT,
personal_max_messages SMALLINT DEFAULT '50',
personal_receipts TINYINT (1) DEFAULT '0',
personal_deny_receipt TINYINT (1) DEFAULT '0',
personal_max_users SMALLINT DEFAULT '0',
personal_folders TINYINT (1) DEFAULT '1',
personal_rules MEDIUMINT,
can_view_online TINYINT (1) DEFAULT '1',
can_view_online_details TINYINT (1) DEFAULT '1',
can_view_online_ip TINYINT (1) DEFAULT '0',
can_default_bbcode TINYINT (1),
warn_others TINYINT (1),
warn_protected TINYINT (1),
warn_viewOwn TINYINT (1),
warn_viewOthers TINYINT (1),
book_hidden TINYINT (1),
book_viewOthers TINYINT (1),
book_viewOwn TINYINT (1),
book_addOthers TINYINT (1),
book_addOwn TINYINT (1),
book_editOwn TINYINT (1),
book_deleteOwn TINYINT (1),
book_permDeleteOwn TINYINT (1),
book_editOthers TINYINT (1),
book_deleteOthers TINYINT (1),
book_permDeleteOthers TINYINT (1)
);";

$mysql['create']['wtcBBoptions'] = "CREATE TABLE wtcBBoptions (
optionid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
floodcheck MEDIUMINT DEFAULT '60',
active TINYINT (1) DEFAULT '1',
active_reason MEDIUMTEXT,
details_boardname VARCHAR (255),
details_boardurl VARCHAR (255),
details_homepage VARCHAR (255),
details_homepageurl VARCHAR (255),
details_contact VARCHAR (255),
details_privacy VARCHAR (255),
details_copyright MEDIUMTEXT,
details_company VARCHAR (255),
general_keywords VARCHAR (255),
general_description VARCHAR (255),
general_style TINYINT (255),
general_forumjump TINYINT (1) DEFAULT '1',
general_pagelinks SMALLINT,
general_wordwrap SMALLINT,
general_templatename TINYINT (1) DEFAULT '0',
general_modcp TINYINT (1) DEFAULT '1',
general_attachments TINYINT (1) DEFAULT '1',
date_timezone TINYINT (12) DEFAULT '-5',
date_dst TINYINT (1) DEFAULT '0',
date_default_thread_age TINYINT (255) DEFAULT '4',
date_todayYesterday TINYINT (1) DEFAULT '1',
date_formatted VARCHAR (255) DEFAULT 'm-d-Y',
date_time_format VARCHAR (255) DEFAULT 'h:i A',
date_register_format VARCHAR (255) DEFAULT 'M Y',
date_birthday_year VARCHAR (255) DEFAULT 'F j, Y',
date_birthday_noyear VARCHAR (255) DEFAULT 'F j',
cookie_timeout INT DEFAULT '900',
cookie_path VARCHAR (255) DEFAULT '/',
cookie_domain VARCHAR (255),
server_sessionlimit INT DEFAULT '0',
censor_enabled VARCHAR (255) DEFAULT '1',
censor_replace VARCHAR (255) DEFAULT '*',
censor_words MEDIUMTEXT,
enable_email TINYINT (1) DEFAULT '1',
enable_user_email TINYINT (1) DEFAULT '1',
allow_new_registrations TINYINT (1) DEFAULT '1',
use_coppa VARCHAR (255),
send_welcome_email TINYINT (1) DEFAULT '1',
notify_email_new VARCHAR (255),
verify_email TINYINT (1) DEFAULT '1',
usergroup_coppa_redirect MEDIUMINT DEFAULT '2',
usergroup_redirect MEDIUMINT DEFAULT '2',
require_unique_email TINYINT (1) DEFAULT '1',
minimum_username TINYINT (255) DEFAULT '3',
maximum_username TINYINT (255) DEFAULT '15',
illegal_username MEDIUMTEXT,
usertitle_maximum TINYINT (255) DEFAULT '25',
usertitle_censored MEDIUMTEXT,
exempt_mods TINYINT (1) DEFAULT '1',
allow_signatures TINYINT (1) DEFAULT '1',
maximum_signature MEDIUMINT DEFAULT '500',
allow_wtcBB_sig TINYINT (1) DEFAULT '1',
allow_smilies_sig TINYINT (1) DEFAULT '1',
allow_img_sig TINYINT (1) DEFAULT '0',
allow_html_sig TINYINT (1) DEFAULT '0',
allow_change_styles TINYINT (1) DEFAULT '1',
avatar_enabled TINYINT (1) DEFAULT '1',
avartar_display_width MEDIUMINT DEFAULT '5',
avatars_per_page MEDIUMINT DEFAULT '0',
memberlist_enabled TINYINT (1) DEFAULT '1',
members_per_page MEDIUMINT DEFAULT '30',
enable_banning TINYINT (1) DEFAULT '1',
blocked_ip MEDIUMTEXT,
blocked_email MEDIUMTEXT,
enable_quick_reply TINYINT (1) DEFAULT '1',
minimum_chars_post MEDIUMINT DEFAULT '5',
maximum_chars_post MEDIUMINT DEFAULT '30000',
maximum_images MEDIUMINT DEFAULT '15',
show_edit_message TINYINT (1) DEFAULT '1',
edit_timeout MEDIUMINT,
logip TINYINT (2) DEFAULT '1',
poll_timeout MEDIUMINT,
clickable_smilies_row MEDIUMINT DEFAULT '3',
clickable_smilies_total MEDIUMINT DEFAULT '12',
allow_attachments TINYINT (1) DEFAULT '1',
attachments_per_post MEDIUMINT DEFAULT '5',
maximum_poll_options MEDIUMINT DEFAULT '10',
toolbar TINYINT (1) DEFAULT '1',
search_enabled TINYINT (1) DEFAULT '1',
search_minimum TINYINT (255) DEFAULT '2',
search_maximum TINYINT (255) DEFAULT '20',
num_of_search_page MEDIUMINT DEFAULT '25',
maximum_search_results MEDIUMINT DEFAULT '500',
forumStatsLevel TINYINT (2) DEFAULT '1',
display_loggedin_users TINYINT (1) DEFAULT '1',
display_birthdays TINYINT (1) DEFAULT '1',
depth_forums TINYINT (255) DEFAULT '2',
show_subforums TINYINT (1) DEFAULT '0',
show_forum_descriptions TINYINT (1) DEFAULT '1',
hide_private TINYINT (1) DEFAULT '1',
show_mod_column TINYINT (1) DEFAULT '1',
other_depth_forums TINYINT (255) DEFAULT '2',
other_show_subforums TINYINT (1) DEFAULT '0',
other_show_forum_descriptions TINYINT (1) DEFAULT '1',
other_hide_private TINYINT (1) DEFAULT '1',
other_show_mod_column TINYINT (1) DEFAULT '0',
show_users_browsing_forum TINYINT (1) DEFAULT '1',
maximum_threads MEDIUMINT DEFAULT '25',
show_all_announcements TINYINT (1) DEFAULT '0',
show_sticky_all TINYINT (1) DEFAULT '0',
hot_views MEDIUMINT DEFAULT '150',
hot_replies MEDIUMINT DEFAULT '15',
multi_thread_links TINYINT (1) DEFAULT '1',
multi_thread_max_links MEDIUMINT DEFAULT '5',
thread_preview_max MEDIUMINT DEFAULT '300',
pre_sticky VARCHAR (255),
pre_poll VARCHAR (255),
pre_moved VARCHAR (255),
pre_closed VARCHAR (255),
show_users_browsing_thread TINYINT (1) DEFAULT '0',
max_posts MEDIUMINT DEFAULT '15',
user_set_max_posts VARCHAR (255) DEFAULT '5,10,15,30,45',
check_thread_subscribe TINYINT (1) DEFAULT '0',
personal_enabled TINYINT (1) DEFAULT '1',
personal_check TINYINT (1),
personal_max_chars MEDIUMINT DEFAULT '10000',
personal_messages_per_page MEDIUMINT DEFAULT '50',
allow_wtcBB_personal TINYINT (1) DEFAULT '1',
allow_smilies_personal TINYINT (1) DEFAULT '1',
allow_img_personal TINYINT (1) DEFAULT '0',
allow_html_personal TINYINT (1) DEFAULT '1',
online_enabled TINYINT (1) DEFAULT '1',
online_refresh MEDIUMINT DEFAULT '60',
online_guest TINYINT (1) DEFAULT '1',
online_resolveIP TINYINT (1) DEFAULT '0',
topicReview TINYINT (1),
robots MEDIUMTEXT,
robots_desc MEDIUMTEXT,
defaultBBCode TINYINT (1),
defaultFontsList MEDIUMTEXT,
defaultColorsList MEDIUMTEXT,
defaultSizeList MEDIUMTEXT,
enableWarn TINYINT (1),
warnAutoBan MEDIUMINT,
autoBanGroup MEDIUMINT,
sendWarnNotify TINYINT (1),
enableGuestbook TINYINT (1),
guestbookPerPage MEDIUMINT,
guestbookNotify TINYINT (1),
version_num VARCHAR (255),
version_text VARCHAR (255),
lastCoppaCheck INT,
css_in_file TINYINT (1) DEFAULT '1',
customTitle_days MEDIUMINT,
customTitle_posts MEDIUMINT,
customTitle_or TINYINT (1) DEFAULT '1',
record_date INT NOT NULL,
record_num INT
);";

$mysql['create']['moderators'] = "CREATE TABLE moderators (
moderatorid SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
userid SMALLINT,
forumid MEDIUMINT,
can_edit TINYINT (1) DEFAULT '1',
can_delete TINYINT (1) DEFAULT '1',
can_permanently_delete TINYINT (1) DEFAULT '0',
can_openClose_threads TINYINT (1) DEFAULT '1',
can_move TINYINT (1) DEFAULT '1',
can_edit_threads TINYINT (1) DEFAULT '1',
can_manage_threads TINYINT (1) DEFAULT '1',
can_edit_polls TINYINT (1) DEFAULT '1',
can_post_announcements TINYINT (1) DEFAULT '1',
can_massmove_threads TINYINT (1) DEFAULT '0',
can_massprune_threads TINYINT (1) DEFAULT '0',
can_view_ip TINYINT (1) DEFAULT '1',
can_view_wholeprofile TINYINT (1) DEFAULT '1',
can_ban TINYINT (1) DEFAULT '0',
can_restore TINYINT (1) DEFAULT '0',
can_edit_sigs TINYINT (1) DEFAULT '0',
can_edit_avatar TINYINT (1) DEFAULT '0',
receive_email_thread TINYINT (1) DEFAULT '0',
receive_email_post TINYINT (1) DEFAULT '0',
recurse TINYINT (1),
INDEX(userid),
INDEX(forumid)
);";

$mysql['create']['attachments'] = "CREATE TABLE attachments (
attachmentid SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
attachmenturl VARCHAR (255),
frontURI VARCHAR (255),
attachmentname VARCHAR (255),
attachmentthread MEDIUMINT,
attachmentpost MEDIUMINT,
contents MEDIUMTEXT,
mime VARCHAR (255),
userid MEDIUMINT,
size MEDIUMINT,
attachmenthash VARCHAR (32),
isPM MEDIUMINT,
INDEX(attachmentthread),
INDEX(attachmentpost),
INDEX(isPM)
);";

$mysql['create']['bbcode'] = "CREATE TABLE bbcode (
bbcodeid SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
name VARCHAR (255),
tag VARCHAR (255),
replacement MEDIUMTEXT,
example MEDIUMTEXT,
description MEDIUMTEXT,
use_option TINYINT (1) DEFAULT '0'
);";

$mysql['create']['smilies'] = "CREATE TABLE smilies (
smilieid SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
title VARCHAR (255),
replacement VARCHAR (255),
filepath MEDIUMTEXT,
display_order MEDIUMINT
);";

$mysql['create']['post_icons'] = "CREATE TABLE post_icons (
post_iconid SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
title VARCHAR (255),
filepath MEDIUMTEXT,
display_order MEDIUMINT
);";

$mysql['create']['avatars'] = "CREATE TABLE avatars (
avatarid SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
title VARCHAR (255),
filepath MEDIUMTEXT,
filename MEDIUMTEXT,
display_order MEDIUMINT
);";

$mysql['create']['usertitles'] = "CREATE TABLE usertitles (
usertitleid SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
title VARCHAR (255),
minimumposts INT
);";

$mysql['create']['announcements'] = "CREATE TABLE announcements (
announcementid SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
title VARCHAR (255),
username VARCHAR (255),
userid MEDIUMINT,
start_date VARCHAR (255),
end_date VARCHAR (255),
message MEDIUMTEXT,
forum MEDIUMINT,
parse_bbcode TINYINT (1) DEFAULT '1',
parse_smilies TINYINT (1) DEFAULT '1',
views MEDIUMINT DEFAULT '0',
date_addedUpdated INT,
INDEX(userid),
INDEX(forum),
INDEX(start_date),
INDEX(end_date)
);";

$mysql['create']['log_admin'] = "CREATE TABLE log_admin (
logid SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
userid SMALLINT,
username VARCHAR (255),
filepath VARCHAR (255),
file_action VARCHAR (255),
ip_address VARCHAR (255),
action_date INT
);";

$mysql['create']['log_moderator'] = "CREATE TABLE log_moderator (
logid SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
userid SMALLINT,
username VARCHAR (255),
filepath VARCHAR (255),
file_action VARCHAR (255),
ip_address VARCHAR (255),
action_date INT
);";

$mysql['create']['poll'] = "CREATE TABLE poll (
pollid SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
threadid MEDIUMINT,
question VARCHAR (255),
date_made INT,
numberoptions SMALLINT,
multiple TINYINT (1) DEFAULT '0',
active TINYINT (1) DEFAULT '1',
public TINYINT (1),
totalVotes MEDIUMINT,
voters MEDIUMTEXT,
timeout INT,
INDEX(threadid)
);";

$mysql['create']['poll_options'] = "CREATE TABLE poll_options (
poll_optionid INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
pollid SMALLINT UNSIGNED NOT NULL,
option_value VARCHAR (255),
votes SMALLINT,
voters MEDIUMTEXT,
threadid MEDIUMINT,
INDEX(threadid)
);";

$mysql['create']['forums_permissions'] = "CREATE TABLE forums_permissions (
permissionsid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
usergroupid TINYINT (255) UNSIGNED NOT NULL,
forumid SMALLINT UNSIGNED NOT NULL,
can_view_board TINYINT (1),
can_view_threads TINYINT (1),
can_view_deletion TINYINT (1),
can_perm_delete TINYINT (1) DEFAULT '0',
can_search TINYINT (1),
can_attachments TINYINT (1),
can_post_threads TINYINT (1),
can_reply_own TINYINT (1),
can_reply_others TINYINT (1),
can_upload_attachments TINYINT (1),
can_edit_own TINYINT (1),
can_delete_threads_own TINYINT (1),
can_delete_own TINYINT (1),
can_close_own TINYINT (1),
can_post_polls TINYINT (1),
can_vote_polls TINYINT (1),
is_inherited TINYINT (1),
flood_immunity TINYINT (1),
INDEX(forumid),
INDEX(usergroupid)
);";

$mysql['create']['faq'] = "CREATE TABLE faq (
faqid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
parent MEDIUMINT,
is_category TINYINT (1),
title MEDIUMTEXT,
message MEDIUMTEXT DEFAULT '',
display_order MEDIUMINT,
INDEX(parent),
INDEX(is_category)
);";

$mysql['create']['admin_permissions'] = "CREATE TABLE admin_permissions (
adminid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
userid SMALLINT,
username VARCHAR (255),
wtcBBoptions TINYINT (1),
announcements TINYINT (1),
forums_moderators TINYINT (1),
users TINYINT (1),
usergroups TINYINT (1),
logs_stats TINYINT (1),
avatars TINYINT (1),
smilies TINYINT (1),
post_icons TINYINT (1),
usertitles TINYINT (1),
bbcode TINYINT (1),
faq TINYINT (1),
styles TINYINT (1),
attachments TINYINT (1),
threads_posts TINYINT (1),
updateinfo TINYINT (1),
warn TINYINT (1),
INDEX(userid)
);";

$mysql['create']['templates'] = "CREATE TABLE templates (
templateid INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
type TINYINT (3) DEFAULT '1',
templategroupid MEDIUMINT,
title VARCHAR (255),
template MEDIUMTEXT,
template_php MEDIUMTEXT,
defaultid MEDIUMINT UNSIGNED,
styleid MEDIUMINT UNSIGNED NOT NULL,
last_edit INT,
username VARCHAR (255),
version VARCHAR (255),
is_global TINYINT (1) DEFAULT '0',
is_custom TINYINT (1) DEFAULT '0',
INDEX(styleid),
INDEX(defaultid),
INDEX(version),
INDEX(is_global),
INDEX(is_custom),
INDEX(templategroupid)
);";

$mysql['create']['templates_default'] = "CREATE TABLE templates_default (
defaultid INT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
type TINYINT (3) DEFAULT '1',
templategroupid MEDIUMINT,
title VARCHAR (255),
template MEDIUMTEXT,
template_php MEDIUMTEXT,
version VARCHAR (255),
INDEX(templategroupid),
INDEX(version)
);";

$mysql['create']['templategroups'] = "CREATE TABLE templategroups (
templategroupid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
title VARCHAR (255)
);";

$mysql['create']['replacement_variables'] = "CREATE TABLE replacement_variables (
replaceid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
styleid MEDIUMINT,
is_global TINYINT (1) DEFAULT '0',
find MEDIUMTEXT,
replacement MEDIUMTEXT,
INDEX(styleid),
INDEX(is_global)
);";

$mysql['create']['styles'] = "CREATE TABLE styles (
styleid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
title VARCHAR (255),
user_selection TINYINT (1),
display_order MEDIUMINT,
enabled TINYINT (1)
);";

$mysql['create']['styles_colors_default'] = "CREATE TABLE styles_colors_default (
defaultid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
main_table_width VARCHAR (255) DEFAULT '95%',
inner_table_width VARCHAR (255) DEFAULT '95%',
border_color VARCHAR (255) DEFAULT '#000000',
border_width VARCHAR (255) DEFAULT '1px',
border_style VARCHAR (255) DEFAULT 'solid',
images_folder VARCHAR (255) DEFAULT 'images',
cell_padding VARCHAR (255) DEFAULT '4px',
title_image VARCHAR (255) DEFAULT 'images/wtcBB_header.jpg',
doctype VARCHAR (255) DEFAULT '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">',
body_background VARCHAR (255) DEFAULT '#bbc6d3',
body_family VARCHAR (255) DEFAULT 'verdana,arial,serif',
body_size VARCHAR (255) DEFAULT '80%',
body_color VARCHAR (255) DEFAULT '#000000',
body_style VARCHAR (255) DEFAULT '',
body_weight VARCHAR (255) DEFAULT '',
body_a_link_bg VARCHAR (255) DEFAULT '',
body_a_link_color VARCHAR (255) DEFAULT '#00285b',
body_a_link_decoration VARCHAR (255) DEFAULT 'none',
body_a_link_weight VARCHAR (255) DEFAULT 'bold',
body_a_link_style VARCHAR (255) DEFAULT '',
body_a_visited_bg VARCHAR (255) DEFAULT '',
body_a_visited_color VARCHAR (255) DEFAULT '#00285b',
body_a_visited_decoration VARCHAR (255) DEFAULT 'none',
body_a_visited_weight VARCHAR (255) DEFAULT '',
body_a_visited_style VARCHAR (255) DEFAULT '',
body_a_hover_bg VARCHAR (255) DEFAULT '',
body_a_hover_color VARCHAR (255) DEFAULT '#00285b',
body_a_hover_decoration VARCHAR (255) DEFAULT 'underline',
body_a_hover_weight VARCHAR (255) DEFAULT '',
body_a_hover_style VARCHAR (255) DEFAULT '',
body_extra MEDIUMTEXT DEFAULT '',
page_background VARCHAR (255) DEFAULT '#ffffff',
page_family VARCHAR (255) DEFAULT '',
page_size VARCHAR (255) DEFAULT '',
page_color VARCHAR (255) DEFAULT '',
page_weight VARCHAR (255) DEFAULT '',
page_style VARCHAR (255) DEFAULT '',
page_a_link_bg VARCHAR (255) DEFAULT '',
page_a_link_color VARCHAR (255) DEFAULT '',
page_a_link_decoration VARCHAR (255) DEFAULT '',
page_a_link_weight VARCHAR (255) DEFAULT '',
page_a_link_style VARCHAR (255) DEFAULT '',
page_a_visited_bg VARCHAR (255) DEFAULT '',
page_a_visited_color VARCHAR (255) DEFAULT '',
page_a_visited_decoration VARCHAR (255) DEFAULT '',
page_a_visited_weight VARCHAR (255) DEFAULT '',
page_a_visited_style VARCHAR (255) DEFAULT '',
page_a_hover_bg VARCHAR (255) DEFAULT '',
page_a_hover_color VARCHAR (255) DEFAULT '',
page_a_hover_decoration VARCHAR (255) DEFAULT '',
page_a_hover_weight VARCHAR (255) DEFAULT '',
page_a_hover_style VARCHAR (255) DEFAULT '',
page_extra MEDIUMTEXT DEFAULT '',
misc_background VARCHAR (255) DEFAULT '',
misc_family VARCHAR (255) DEFAULT '',
misc_size VARCHAR (255) DEFAULT '',
misc_color VARCHAR (255) DEFAULT '',
misc_style VARCHAR (255) DEFAULT '',
misc_weight VARCHAR (255) DEFAULT '',
misc_extra MEDIUMTEXT DEFAULT '',
cat_background VARCHAR (255) DEFAULT '#e2e7ed url(images/catHeaderBG.jpg) repeat-x',
cat_family VARCHAR (255) DEFAULT '',
cat_size VARCHAR (255) DEFAULT '',
cat_color VARCHAR (255) DEFAULT '',
cat_weight VARCHAR (255) DEFAULT 'bold',
cat_style VARCHAR (255) DEFAULT '',
cat_a_link_bg VARCHAR (255) DEFAULT '',
cat_a_link_color VARCHAR (255) DEFAULT '#00285b',
cat_a_link_decoration VARCHAR (255) DEFAULT '',
cat_a_link_weight VARCHAR (255) DEFAULT '',
cat_a_link_style VARCHAR (255) DEFAULT '',
cat_a_visited_bg VARCHAR (255) DEFAULT '',
cat_a_visited_color VARCHAR (255) DEFAULT '#00285b',
cat_a_visited_decoration VARCHAR (255) DEFAULT '',
cat_a_visited_weight VARCHAR (255) DEFAULT '',
cat_a_visited_style VARCHAR (255) DEFAULT '',
cat_a_hover_bg VARCHAR (255) DEFAULT '',
cat_a_hover_color VARCHAR (255) DEFAULT '#000000',
cat_a_hover_decoration VARCHAR (255) DEFAULT '',
cat_a_hover_weight VARCHAR (255) DEFAULT '',
cat_a_hover_style VARCHAR (255) DEFAULT '',
cat_extra MEDIUMTEXT DEFAULT '',
headFoot_background VARCHAR (255) DEFAULT '#adbed0 url(images/headFoot_grad.jpg) repeat-x',
headFoot_family VARCHAR (255) DEFAULT '',
headFoot_size VARCHAR (255) DEFAULT '',
headFoot_color VARCHAR (255) DEFAULT '#ffffff',
headFoot_weight VARCHAR (255) DEFAULT 'bold',
headFoot_style VARCHAR (255) DEFAULT '',
headFoot_a_link_bg VARCHAR (255) DEFAULT '',
headFoot_a_link_color VARCHAR (255) DEFAULT '#ffffff',
headFoot_a_link_decoration VARCHAR (255) DEFAULT 'underline',
headFoot_a_link_weight VARCHAR (255) DEFAULT '',
headFoot_a_link_style VARCHAR (255) DEFAULT '',
headFoot_a_visited_bg VARCHAR (255) DEFAULT '',
headFoot_a_visited_color VARCHAR (255) DEFAULT '#ffffff',
headFoot_a_visited_decoration VARCHAR (255) DEFAULT 'underline',
headFoot_a_visited_weight VARCHAR (255) DEFAULT '',
headFoot_a_visited_style VARCHAR (255) DEFAULT '',
headFoot_a_hover_bg VARCHAR (255) DEFAULT '',
headFoot_a_hover_color VARCHAR (255) DEFAULT '#000000',
headFoot_a_hover_decoration VARCHAR (255) DEFAULT 'underline',
headFoot_a_hover_weight VARCHAR (255) DEFAULT '',
headFoot_a_hover_style VARCHAR (255) DEFAULT '',
headFoot_extra MEDIUMTEXT DEFAULT '',
first_background VARCHAR (255) DEFAULT '#ffffff',
first_family VARCHAR (255) DEFAULT '',
first_size VARCHAR (255) DEFAULT '',
first_color VARCHAR (255) DEFAULT '',
first_weight VARCHAR (255) DEFAULT '',
first_style VARCHAR (255) DEFAULT '',
first_a_link_bg VARCHAR (255) DEFAULT '',
first_a_link_color VARCHAR (255) DEFAULT '',
first_a_link_decoration VARCHAR (255) DEFAULT '',
first_a_link_weight VARCHAR (255) DEFAULT '',
first_a_link_style VARCHAR (255) DEFAULT '',
first_a_visited_bg VARCHAR (255) DEFAULT '',
first_a_visited_color VARCHAR (255) DEFAULT '',
first_a_visited_decoration VARCHAR (255) DEFAULT '',
first_a_visited_weight VARCHAR (255) DEFAULT '',
first_a_visited_style VARCHAR (255) DEFAULT '',
first_a_hover_bg VARCHAR (255) DEFAULT '',
first_a_hover_color VARCHAR (255) DEFAULT '',
first_a_hover_decoration VARCHAR (255) DEFAULT '',
first_a_hover_weight VARCHAR (255) DEFAULT '',
first_a_hover_style VARCHAR (255) DEFAULT '',
first_extra MEDIUMTEXT DEFAULT '',
second_background VARCHAR (255) DEFAULT '#f3f3f3',
second_family VARCHAR (255) DEFAULT '',
second_size VARCHAR (255) DEFAULT '',
second_color VARCHAR (255) DEFAULT '',
second_weight VARCHAR (255) DEFAULT '',
second_style VARCHAR (255) DEFAULT '',
second_a_link_bg VARCHAR (255) DEFAULT '',
second_a_link_color VARCHAR (255) DEFAULT '',
second_a_link_decoration VARCHAR (255) DEFAULT '',
second_a_link_weight VARCHAR (255) DEFAULT '',
second_a_link_style VARCHAR (255) DEFAULT '',
second_a_visited_bg VARCHAR (255) DEFAULT '',
second_a_visited_color VARCHAR (255) DEFAULT '',
second_a_visited_decoration VARCHAR (255) DEFAULT '',
second_a_visited_weight VARCHAR (255) DEFAULT '',
second_a_visited_style VARCHAR (255) DEFAULT '',
second_a_hover_bg VARCHAR (255) DEFAULT '',
second_a_hover_color VARCHAR (255) DEFAULT '',
second_a_hover_decoration VARCHAR (255) DEFAULT '',
second_a_hover_weight VARCHAR (255) DEFAULT '',
second_a_hover_style VARCHAR (255) DEFAULT '',
second_extra MEDIUMTEXT DEFAULT '',
input_background VARCHAR (255) DEFAULT '#f5f5f5',
input_family VARCHAR (255) DEFAULT 'verdana,arial,serif',
input_size VARCHAR (255) DEFAULT '100%',
input_color VARCHAR (255) DEFAULT '',
input_style VARCHAR (255) DEFAULT '',
input_weight VARCHAR (255) DEFAULT 'normal',
input_extra MEDIUMTEXT,
button_background VARCHAR (255) DEFAULT '#f5f5f5 url(images/button_bg.jpg) repeat-x',
button_family VARCHAR (255) DEFAULT 'verdana,arial,serif',
button_size VARCHAR (255) DEFAULT '85%',
button_color VARCHAR (255) DEFAULT '#000000',
button_style VARCHAR (255) DEFAULT '',
button_weight VARCHAR (255) DEFAULT 'normal',
button_extra MEDIUMTEXT DEFAULT '',
select_background VARCHAR (255) DEFAULT '#f5f5f5',
select_family VARCHAR (255) DEFAULT 'verdana,arial,serif',
select_size VARCHAR (255) DEFAULT '85%',
select_color VARCHAR (255) DEFAULT '',
select_style VARCHAR (255) DEFAULT '',
select_weight VARCHAR (255) DEFAULT 'normal',
select_extra MEDIUMTEXT DEFAULT '',
small_background VARCHAR (255) DEFAULT '',
small_family VARCHAR (255) DEFAULT '',
small_size VARCHAR (255) DEFAULT '85%',
small_color VARCHAR (255) DEFAULT '',
small_style VARCHAR (255) DEFAULT '',
small_weight VARCHAR (255) DEFAULT '',
small_extra MEDIUMTEXT DEFAULT '',
time_background VARCHAR (255) DEFAULT '',
time_family VARCHAR (255) DEFAULT '',
time_size VARCHAR (255) DEFAULT '',
time_color VARCHAR (255) DEFAULT '#00285b',
time_style VARCHAR (255) DEFAULT '',
time_weight VARCHAR (255) DEFAULT '',
time_extra MEDIUMTEXT DEFAULT '',
nav_background VARCHAR (255) DEFAULT '',
nav_family VARCHAR (255) DEFAULT '',
nav_size VARCHAR (255) DEFAULT '',
nav_color VARCHAR (255) DEFAULT '',
nav_weight VARCHAR (255) DEFAULT 'bold',
nav_style VARCHAR (255) DEFAULT '',
nav_a_link_bg VARCHAR (255) DEFAULT '',
nav_a_link_color VARCHAR (255) DEFAULT '',
nav_a_link_decoration VARCHAR (255) DEFAULT '',
nav_a_link_weight VARCHAR (255) DEFAULT '',
nav_a_link_style VARCHAR (255) DEFAULT '',
nav_a_visited_bg VARCHAR (255) DEFAULT '',
nav_a_visited_color VARCHAR (255) DEFAULT '',
nav_a_visited_decoration VARCHAR (255) DEFAULT '',
nav_a_visited_weight VARCHAR (255) DEFAULT '',
nav_a_visited_style VARCHAR (255) DEFAULT '',
nav_a_hover_bg VARCHAR (255) DEFAULT '',
nav_a_hover_color VARCHAR (255) DEFAULT '',
nav_a_hover_decoration VARCHAR (255) DEFAULT '',
nav_a_hover_weight VARCHAR (255) DEFAULT '',
nav_a_hover_style VARCHAR (255) DEFAULT '',
nav_extra MEDIUMTEXT,
jump_1_color VARCHAR (255) DEFAULT '#ffffff',
jump_1_bg VARCHAR (255) DEFAULT '#7e94ac',
jump_2_color VARCHAR (255) DEFAULT '#000000',
jump_2_bg VARCHAR (255) DEFAULT '#ffffff',
jump_3_color VARCHAR (255) DEFAULT '#000000',
jump_3_bg VARCHAR (255) DEFAULT '#ffffff',
jump_4_color VARCHAR (255) DEFAULT '#000000',
jump_4_bg VARCHAR (255) DEFAULT '#ffffff',
extra_css MEDIUMTEXT
);";

$mysql['create']['styles_colors'] = "CREATE TABLE styles_colors (
colorid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
defaultid MEDIUMINT,
styleid MEDIUMINT,
main_table_width VARCHAR (255) DEFAULT '95%',
inner_table_width VARCHAR (255) DEFAULT '95%',
border_color VARCHAR (255) DEFAULT '#000000',
border_width VARCHAR (255) DEFAULT '1px',
border_style VARCHAR (255) DEFAULT 'solid',
images_folder VARCHAR (255) DEFAULT 'images',
cell_padding VARCHAR (255) DEFAULT '4px',
title_image VARCHAR (255) DEFAULT 'images/wtcBB_header.jpg',
doctype VARCHAR (255) DEFAULT '<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">',
body_background VARCHAR (255) DEFAULT '#bbc6d3',
body_family VARCHAR (255) DEFAULT 'verdana,arial,serif',
body_size VARCHAR (255) DEFAULT '80%',
body_color VARCHAR (255) DEFAULT '#000000',
body_style VARCHAR (255) DEFAULT '',
body_weight VARCHAR (255) DEFAULT '',
body_a_link_bg VARCHAR (255) DEFAULT '',
body_a_link_color VARCHAR (255) DEFAULT '#00285b',
body_a_link_decoration VARCHAR (255) DEFAULT 'none',
body_a_link_weight VARCHAR (255) DEFAULT 'bold',
body_a_link_style VARCHAR (255) DEFAULT '',
body_a_visited_bg VARCHAR (255) DEFAULT '',
body_a_visited_color VARCHAR (255) DEFAULT '#00285b',
body_a_visited_decoration VARCHAR (255) DEFAULT 'none',
body_a_visited_weight VARCHAR (255) DEFAULT '',
body_a_visited_style VARCHAR (255) DEFAULT '',
body_a_hover_bg VARCHAR (255) DEFAULT '',
body_a_hover_color VARCHAR (255) DEFAULT '#00285b',
body_a_hover_decoration VARCHAR (255) DEFAULT 'underline',
body_a_hover_weight VARCHAR (255) DEFAULT '',
body_a_hover_style VARCHAR (255) DEFAULT '',
body_extra MEDIUMTEXT DEFAULT '',
page_background VARCHAR (255) DEFAULT '#ffffff',
page_family VARCHAR (255) DEFAULT '',
page_size VARCHAR (255) DEFAULT '',
page_color VARCHAR (255) DEFAULT '',
page_weight VARCHAR (255) DEFAULT '',
page_style VARCHAR (255) DEFAULT '',
page_a_link_bg VARCHAR (255) DEFAULT '',
page_a_link_color VARCHAR (255) DEFAULT '',
page_a_link_decoration VARCHAR (255) DEFAULT '',
page_a_link_weight VARCHAR (255) DEFAULT '',
page_a_link_style VARCHAR (255) DEFAULT '',
page_a_visited_bg VARCHAR (255) DEFAULT '',
page_a_visited_color VARCHAR (255) DEFAULT '',
page_a_visited_decoration VARCHAR (255) DEFAULT '',
page_a_visited_weight VARCHAR (255) DEFAULT '',
page_a_visited_style VARCHAR (255) DEFAULT '',
page_a_hover_bg VARCHAR (255) DEFAULT '',
page_a_hover_color VARCHAR (255) DEFAULT '',
page_a_hover_decoration VARCHAR (255) DEFAULT '',
page_a_hover_weight VARCHAR (255) DEFAULT '',
page_a_hover_style VARCHAR (255) DEFAULT '',
page_extra MEDIUMTEXT DEFAULT '',
misc_background VARCHAR (255) DEFAULT '',
misc_family VARCHAR (255) DEFAULT '',
misc_size VARCHAR (255) DEFAULT '',
misc_color VARCHAR (255) DEFAULT '',
misc_style VARCHAR (255) DEFAULT '',
misc_weight VARCHAR (255) DEFAULT '',
misc_extra MEDIUMTEXT DEFAULT '',
cat_background VARCHAR (255) DEFAULT '#e2e7ed url(images/catHeaderBG.jpg) repeat-x',
cat_family VARCHAR (255) DEFAULT '',
cat_size VARCHAR (255) DEFAULT '',
cat_color VARCHAR (255) DEFAULT '',
cat_weight VARCHAR (255) DEFAULT 'bold',
cat_style VARCHAR (255) DEFAULT '',
cat_a_link_bg VARCHAR (255) DEFAULT '',
cat_a_link_color VARCHAR (255) DEFAULT '#00285b',
cat_a_link_decoration VARCHAR (255) DEFAULT '',
cat_a_link_weight VARCHAR (255) DEFAULT '',
cat_a_link_style VARCHAR (255) DEFAULT '',
cat_a_visited_bg VARCHAR (255) DEFAULT '',
cat_a_visited_color VARCHAR (255) DEFAULT '#00285b',
cat_a_visited_decoration VARCHAR (255) DEFAULT '',
cat_a_visited_weight VARCHAR (255) DEFAULT '',
cat_a_visited_style VARCHAR (255) DEFAULT '',
cat_a_hover_bg VARCHAR (255) DEFAULT '',
cat_a_hover_color VARCHAR (255) DEFAULT '#000000',
cat_a_hover_decoration VARCHAR (255) DEFAULT '',
cat_a_hover_weight VARCHAR (255) DEFAULT '',
cat_a_hover_style VARCHAR (255) DEFAULT '',
cat_extra MEDIUMTEXT DEFAULT '',
headFoot_background VARCHAR (255) DEFAULT '#adbed0 url(images/headFoot_grad.jpg) repeat-x',
headFoot_family VARCHAR (255) DEFAULT '',
headFoot_size VARCHAR (255) DEFAULT '',
headFoot_color VARCHAR (255) DEFAULT '#ffffff',
headFoot_weight VARCHAR (255) DEFAULT 'bold',
headFoot_style VARCHAR (255) DEFAULT '',
headFoot_a_link_bg VARCHAR (255) DEFAULT '',
headFoot_a_link_color VARCHAR (255) DEFAULT '#ffffff',
headFoot_a_link_decoration VARCHAR (255) DEFAULT 'underline',
headFoot_a_link_weight VARCHAR (255) DEFAULT '',
headFoot_a_link_style VARCHAR (255) DEFAULT '',
headFoot_a_visited_bg VARCHAR (255) DEFAULT '',
headFoot_a_visited_color VARCHAR (255) DEFAULT '#ffffff',
headFoot_a_visited_decoration VARCHAR (255) DEFAULT 'underline',
headFoot_a_visited_weight VARCHAR (255) DEFAULT '',
headFoot_a_visited_style VARCHAR (255) DEFAULT '',
headFoot_a_hover_bg VARCHAR (255) DEFAULT '',
headFoot_a_hover_color VARCHAR (255) DEFAULT '#000000',
headFoot_a_hover_decoration VARCHAR (255) DEFAULT 'underline',
headFoot_a_hover_weight VARCHAR (255) DEFAULT '',
headFoot_a_hover_style VARCHAR (255) DEFAULT '',
headFoot_extra MEDIUMTEXT DEFAULT '',
first_background VARCHAR (255) DEFAULT '#ffffff',
first_family VARCHAR (255) DEFAULT '',
first_size VARCHAR (255) DEFAULT '',
first_color VARCHAR (255) DEFAULT '',
first_weight VARCHAR (255) DEFAULT '',
first_style VARCHAR (255) DEFAULT '',
first_a_link_bg VARCHAR (255) DEFAULT '',
first_a_link_color VARCHAR (255) DEFAULT '',
first_a_link_decoration VARCHAR (255) DEFAULT '',
first_a_link_weight VARCHAR (255) DEFAULT '',
first_a_link_style VARCHAR (255) DEFAULT '',
first_a_visited_bg VARCHAR (255) DEFAULT '',
first_a_visited_color VARCHAR (255) DEFAULT '',
first_a_visited_decoration VARCHAR (255) DEFAULT '',
first_a_visited_weight VARCHAR (255) DEFAULT '',
first_a_visited_style VARCHAR (255) DEFAULT '',
first_a_hover_bg VARCHAR (255) DEFAULT '',
first_a_hover_color VARCHAR (255) DEFAULT '',
first_a_hover_decoration VARCHAR (255) DEFAULT '',
first_a_hover_weight VARCHAR (255) DEFAULT '',
first_a_hover_style VARCHAR (255) DEFAULT '',
first_extra MEDIUMTEXT DEFAULT '',
second_background VARCHAR (255) DEFAULT '#f3f3f3',
second_family VARCHAR (255) DEFAULT '',
second_size VARCHAR (255) DEFAULT '',
second_color VARCHAR (255) DEFAULT '',
second_weight VARCHAR (255) DEFAULT '',
second_style VARCHAR (255) DEFAULT '',
second_a_link_bg VARCHAR (255) DEFAULT '',
second_a_link_color VARCHAR (255) DEFAULT '',
second_a_link_decoration VARCHAR (255) DEFAULT '',
second_a_link_weight VARCHAR (255) DEFAULT '',
second_a_link_style VARCHAR (255) DEFAULT '',
second_a_visited_bg VARCHAR (255) DEFAULT '',
second_a_visited_color VARCHAR (255) DEFAULT '',
second_a_visited_decoration VARCHAR (255) DEFAULT '',
second_a_visited_weight VARCHAR (255) DEFAULT '',
second_a_visited_style VARCHAR (255) DEFAULT '',
second_a_hover_bg VARCHAR (255) DEFAULT '',
second_a_hover_color VARCHAR (255) DEFAULT '',
second_a_hover_decoration VARCHAR (255) DEFAULT '',
second_a_hover_weight VARCHAR (255) DEFAULT '',
second_a_hover_style VARCHAR (255) DEFAULT '',
second_extra MEDIUMTEXT DEFAULT '',
input_background VARCHAR (255) DEFAULT '#f5f5f5',
input_family VARCHAR (255) DEFAULT 'verdana,arial,serif',
input_size VARCHAR (255) DEFAULT '100%',
input_color VARCHAR (255) DEFAULT '',
input_style VARCHAR (255) DEFAULT '',
input_weight VARCHAR (255) DEFAULT 'normal',
input_extra MEDIUMTEXT,
button_background VARCHAR (255) DEFAULT '#f5f5f5 url(images/button_bg.jpg) repeat-x',
button_family VARCHAR (255) DEFAULT 'verdana,arial,serif',
button_size VARCHAR (255) DEFAULT '85%',
button_color VARCHAR (255) DEFAULT '#000000',
button_style VARCHAR (255) DEFAULT '',
button_weight VARCHAR (255) DEFAULT 'normal',
button_extra MEDIUMTEXT DEFAULT '',
select_background VARCHAR (255) DEFAULT '#f5f5f5',
select_family VARCHAR (255) DEFAULT 'verdana,arial,serif',
select_size VARCHAR (255) DEFAULT '85%',
select_color VARCHAR (255) DEFAULT '',
select_style VARCHAR (255) DEFAULT '',
select_weight VARCHAR (255) DEFAULT 'normal',
select_extra MEDIUMTEXT DEFAULT '',
small_background VARCHAR (255) DEFAULT '',
small_family VARCHAR (255) DEFAULT '',
small_size VARCHAR (255) DEFAULT '85%',
small_color VARCHAR (255) DEFAULT '',
small_style VARCHAR (255) DEFAULT '',
small_weight VARCHAR (255) DEFAULT '',
small_extra MEDIUMTEXT DEFAULT '',
time_background VARCHAR (255) DEFAULT '',
time_family VARCHAR (255) DEFAULT '',
time_size VARCHAR (255) DEFAULT '',
time_color VARCHAR (255) DEFAULT '#00285b',
time_style VARCHAR (255) DEFAULT '',
time_weight VARCHAR (255) DEFAULT '',
time_extra MEDIUMTEXT DEFAULT '',
nav_background VARCHAR (255) DEFAULT '',
nav_family VARCHAR (255) DEFAULT '',
nav_size VARCHAR (255) DEFAULT '',
nav_color VARCHAR (255) DEFAULT '',
nav_weight VARCHAR (255) DEFAULT 'bold',
nav_style VARCHAR (255) DEFAULT '',
nav_a_link_bg VARCHAR (255) DEFAULT '',
nav_a_link_color VARCHAR (255) DEFAULT '',
nav_a_link_decoration VARCHAR (255) DEFAULT '',
nav_a_link_weight VARCHAR (255) DEFAULT '',
nav_a_link_style VARCHAR (255) DEFAULT '',
nav_a_visited_bg VARCHAR (255) DEFAULT '',
nav_a_visited_color VARCHAR (255) DEFAULT '',
nav_a_visited_decoration VARCHAR (255) DEFAULT '',
nav_a_visited_weight VARCHAR (255) DEFAULT '',
nav_a_visited_style VARCHAR (255) DEFAULT '',
nav_a_hover_bg VARCHAR (255) DEFAULT '',
nav_a_hover_color VARCHAR (255) DEFAULT '',
nav_a_hover_decoration VARCHAR (255) DEFAULT '',
nav_a_hover_weight VARCHAR (255) DEFAULT '',
nav_a_hover_style VARCHAR (255) DEFAULT '',
nav_extra MEDIUMTEXT,
jump_1_color VARCHAR (255) DEFAULT '#ffffff',
jump_1_bg VARCHAR (255) DEFAULT '#7e94ac',
jump_2_color VARCHAR (255) DEFAULT '#000000',
jump_2_bg VARCHAR (255) DEFAULT '#ffffff',
jump_3_color VARCHAR (255) DEFAULT '#000000',
jump_3_bg VARCHAR (255) DEFAULT '#ffffff',
jump_4_color VARCHAR (255) DEFAULT '#000000',
jump_4_bg VARCHAR (255) DEFAULT '#ffffff',
extra_css MEDIUMTEXT
);";

$mysql['create']['sessions'] = "CREATE TABLE sessions (
sessionid VARCHAR (255) NOT NULL PRIMARY KEY,
username VARCHAR (255),
userid MEDIUMINT,
user_agent MEDIUMTEXT,
ip_address VARCHAR (255),
action VARCHAR (255),
title  VARCHAR (255),
location VARCHAR (255),
last_activity INT,
INDEX(userid),
INDEX(location),
INDEX(last_activity)
);";

$mysql['create']['logged_ips'] = "CREATE TABLE logged_ips (
ipId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
ip_address VARCHAR (255),
username VARCHAR (255),
userid MEDIUMINT,
INDEX(userid),
INDEX(ip_address)
);";

$mysql['create']['attachment_storage'] = "CREATE TABLE attachment_storage (
storageid MEDIUMINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
ext MEDIUMTEXT,
max_filesize INT,
max_width INT,
max_height INT,
mime_type MEDIUMTEXT,
enabled TINYINT (1),
INDEX(enabled)
);";

$mysql['create']['warn'] = "CREATE TABLE warn (
warnid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
userid MEDIUMINT,
typeid MEDIUMINT,
whoWarned MEDIUMINT,
note MEDIUMTEXT,
warnDate INT,
postid MEDIUMINT,
INDEX(userid), INDEX(typeid), INDEX(whoWarned), INDEX(postid)
);";

$mysql['create']['warn_type'] = "CREATE TABLE warn_type (
typeid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
name VARCHAR (255),
warnPoints MEDIUMINT
);";

$mysql['create']['guestbook'] = "CREATE TABLE guestbook (
bookid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
ownUserid MEDIUMINT,
userid MEDIUMINT,
title VARCHAR (255),
ip_address VARCHAR (255),
date_posted INT,
show_sig TINYINT (1),
parse_smilies TINYINT (1),
parse_bbcode TINYINT (1),
defBBCode TINYINT (1),
deleted TINYINT (1),
edited_by VARCHAR (255),
edited_time INT,
edited_reason VARCHAR (255),
message MEDIUMTEXT,
INDEX(ownUserid), INDEX(userid), INDEX(deleted)
);";


// ##### INSERT QUERIES ##### \\
$mysql['insert']['warn_type'] = "INSERT INTO warn_type (typeid, name, warnPoints) VALUES (1, 'Spamming', 1), (3, 'Flaming', 2), (4, 'Advertising', 1), (5, 'Excessive Ignorance For Rules', 5);";

$mysql['insert']['faq'] = "REPLACE INTO faq (faqid, parent, is_category, title, message, display_order) VALUES (1, -1, 1, 'BB Code', NULL, 5),
(2, 1, -1, 'What is it?', 'BB Code is a special kind of code, that is usually particular to Message Boards. BB Code allows an end-user to use a dumbed down version of HTML, as allowing straight HTML will cause security issues. BB Code was developed so you can display images, format text, and more, all without the use or knowledge of HTML. Below you\'ll find some general rules.\r\n\r\nAlmost every BB Code will look something like this:\r\n\r\n[<strong>name_of_code</strong>]the formatted text[/<strong>name_of_code</strong>]\r\n\r\nHowever, it could also look something like this:\r\n\r\n[<strong>name_of_code</strong>=<strong>some_value</strong>]the formatted text[/<strong>name_of_code</strong>]\r\n\r\nUsually, the \"some_value\" will have an effect on how the text is formatted. For example, when using the <em>size</em> BB Code, you would use \"some_value\" to put a size for the text. However, if you were using the <em>b</em> BB Code, there would be no value, because it\'s only simply bolding the text. Also, there should be <strong>no quotes</strong> surrounding \"some_value\".\r\n\r\n\r\n You can find a list of all the default BB Codes, their function, and how to use them below.', 1),
(3, 1, -1, 'url', 'The <em>url</em> BB Code allows you to put a link to somewhere in your post.\r\n\r\nThere are two different ways of using the [url] BB Code. \r\n\r\nFirst way (Simple):\r\n[url]http://www.webtrickscentral.com[/url]\r\n\r\nIt will look like this:\r\n<a href=\"http://www.webtrickscentral.com\">http://www.webtrickscentral.com</a>\r\n\r\nSecond Way (Advanced):\r\n[url=http://www.webtrickscentral.com]wtc.com[/url]\r\n\r\nIt will look like this:\r\n<a href=\"http://www.webtrickscentral.com\">wtc.com</a>\r\n\r\nThere is a clear different between the two, in which you can specify a title for the link, so it doesn\'t actually display the link itself.', 2),
(4, 1, -1, 'b', 'The <em>b</em> BB Code will allow you to make your text bold.\r\n\r\nYou can make the text you want bold, by surrounding it with [b] and [/b]. Like this:\r\n\r\nThis is [b]bold[/b] text.\r\n\r\nIt will look like this:\r\n\r\nThis is <strong>bold</strong> text.', 3),
(5, 1, -1, 'center', 'The <em>center</em> BB Code will allow you to center text or images.\r\n\r\nTo center something, simply surround it with [center] and [/center]. Like this:\r\n\r\n[center]This text is centered[/center]\r\n\r\nIt will look like this:\r\n\r\n<p style=\"text-align: center;\">This text is centered</p>', 4),
(6, 1, -1, 'code', 'The <em>code</em> BB Code allows you to input code of any kind.\r\n\r\nTo display something as code, simply put the [code] and [/code] tags around your desired code. Like this:\r\n\r\n[code]&lt;body&gt;\r\n&lt;p&gt;This is code!&lt;/p&gt;\r\n&lt;/body&gt;[/code]\r\n\r\nIt will look like this:\r\n\r\n<div class=\"fullQuote\"><p class=\"quoteTitle\">Code:</p><div class=\"quote\" style=\"font-weight: normal; font-family: courier new;\">&lt;body&gt;\r\n&lt;p&gt;This is code!&lt;/p&gt;\r\n&lt;/body&gt;</div></div>', 5),
(7, 1, -1, 'color', 'The <em>color</em> BB Code allows you to change the color of text.\r\n\r\nTo change the color of text, simply put [color=<strong>x</strong>] and [/color] around the text, where <strong>x</strong> is your desired color.\r\n\r\n[color=#00285b]This text is dark blue[/color]\r\n\r\nIt will look like this:\r\n\r\n<span style=\"color: #00285b;\">This text is dark blue</span>', 6),
(8, 1, -1, 'email', 'The <em>email</em> BB Code allows you to make a link to email someone else. \r\n\r\nThe <em>email</em> BB Code works virtually the same as the <em>url</em> BB Code, except this provides a link to email someone. There are two ways to use the <em>email</em> BB Code:\r\n\r\nFirst Way (Simple):\r\n[email]example@example.com[/email]\r\n\r\nIt will look like this:\r\n<a href=\"mailto:example@example.com\">example@example.com</a>\r\n\r\nSecond Way (Advanced):\r\n[email=example@example.com]email scyth[/email]\r\n\r\nIt will look like this:\r\n<a href=\"mailto:example@example.com\">email scyth</a>', 7),
(9, 1, -1, 'font', 'The <em>font</em> BB Code allows you to change the font of any given text.\r\n\r\nTo change the font of text, simply put [font=<strong>x</strong>] and [/font] around the text whose font you want to change, where <strong>x</strong> is the font name.\r\n\r\nThis is [font=tahoma]tahoma[/font] text\r\n\r\nIt will look like this:\r\n\r\nThis is <span style=\"font-family: tahoma;\">tahoma</span> text', 8),
(10, 1, -1, 'php', 'The <em>php</em> BB Code allows you to syntax highlight any PHP Code.\r\n\r\nTo use syntax highlight php code put [php] and [/php] around the code you want highlighted. Like this:\r\n\r\n[php]<?php\r\nif($something == true) {\r\n    $increment++;\r\n} else {\r\n    $increment--;\r\n}\r\n?>[/php]\r\n\r\nIt will look like this:\r\n\r\n<div class=\"fullQuote\"><p class=\"quoteTitle\">PHP:</p><div class=\"quote\" style=\"font-weight: normal; font-family: courier new;\"><span style=\"color: #000000;\"><span style=\"color: #0000BB;\"></span><span style=\"color: #007700;\"><?</span><span style=\"color: #0000BB;\">php\r\n</span><span style=\"color: #007700;\">if(</span><span style=\"color: #0000BB;\">$something </span><span style=\"color: #007700;\">== </span><span style=\"color: #0000BB;\">true</span><span style=\"color: #007700;\">) {\r\n    </span><span style=\"color: #0000BB;\">$increment</span><span style=\"color: #007700;\">++;\r\n} else {\r\n    </span><span style=\"color: #0000BB;\">$increment</span><span style=\"color: #007700;\">--;\r\n}\r\n</span><span style=\"color: #0000BB;\">?>\r\n</span> </span></div></div>', 9),
(11, 1, -1, 'img', 'The <em>img</em> BB Code allows you to place an image in your message.\r\n\r\nIn order to put an image in your message simply put [img] and [/img] around the address of the image on the internet. Like this:\r\n\r\n[img]http://www.webtrickscentral.com/images/supportWTC.gif[/img]\r\n\r\nIt will look like this:\r\n\r\n<img src=\"http://www.webtrickscentral.com/images/supportWTC.gif\" alt=\"http://www.webtrickscentral.com/images/supportWTC.gif\" />', 10),
(12, 1, -1, 'i', 'The <em>i</em> BB Code will allow you to make text italic.\r\n\r\nTo make text italic put [i] and [/i] around the text you want to be italic. Like this:\r\n\r\nThis is [i]italic[/i] text.\r\n\r\nIt will look like this:\r\n\r\nThis is <em>italic</em> text.', 11),
(13, 1, -1, 'left', 'The <em>left</em> BB Code will allow you to align text to the left.\r\n\r\nTo align text to the left put [left] and [/left] around it. Like this:\r\n\r\n[left]Left-aligned text[/left]\r\n\r\nIt would look like this:\r\n\r\n<p style=\"text-align: left;\">Left-aligned text</p>', 12),
(14, 1, -1, 'list and !', 'In order to make a complete ordered or unordered list you need to make use of the <em>list</em> and <em>!</em> BB Codes. The <em>list</em> BB Code will actually form the list, and the <em>!</em> BB Code will form each list item. It would look something like this:\r\n\r\n[list=ol]\r\n[!]First List Item[/!]\r\n[!]Second List Item[/!]\r\n[!]Third List Item[/!]\r\n[/list]\r\n\r\nOr you can make an unordered list:\r\n\r\n[list=ul]\r\n[!]First List Item[/!]\r\n[!]Second List Item[/!]\r\n[!]Third List Item[/!]\r\n[/list]\r\n\r\nThe ordered list would look like this:\r\n<ol>\r\n<li>First List Item</li>\r\n<li>Second List Item</li>\r\n<li>Third List Item</li>\r\n</ol>\r\nThe unordered list would look like this:\r\n<ul>\r\n<li>First List Item</li>\r\n<li>Second List Item</li>\r\n<li>Third List Item</li>\r\n</ul>', 13),
(15, 1, -1, 'quote', 'The <em>quote</em> BB Code allows you to quote a source. There are two different types of quotes, a simple one and an advanced one. The simple one allows you to quote an unamed source, while the advanced one allows you to quote a named sourse. They will look something like this:\r\n\r\nSimple Quote:\r\n[quote]The incompatibility between science and religion is simply this: a scientist will not believe anything until he sees it; a religious man will not see anything until he believes in it.[/quote]\r\n\r\nIt would look like this:\r\n<div class=\"fullQuote\"><p class=\"quoteTitle\">Quote:</p><div class=\"quote\">The incompatibility between science and religion is simply this: a scientist will not believe anything until he sees it; a religious man will not see anything until he believes in it.</div></div>\r\n\r\nAdvanced Quote:\r\n[quote=Charles J. C. Lyall]The incompatibility between science and religion is simply this: a scientist will not believe anything until he sees it; a religious man will not see anything until he believes in it.[/quote]\r\n\r\nIt would look like this:\r\n<div class=\"fullQuote\"><p class=\"quoteTitle\">Quote By <em>Charles J. C. Lyall</em></p><div class=\"quote\">The incompatibility between science and religion is simply this: a scientist will not believe anything until he sees it; a religious man will not see anything until he believes in it.</div></div>', 14),
(16, 1, -1, 'right', 'The <em>right</em> BB Code allows you to align text or images to the right.\r\n\r\nTo align text to the right put [right] and [/right] around the desired text. Like this:\r\n\r\n[right]This is right-aligned text[/right]\r\n\r\nIt will look like this:\r\n\r\n<p style=\"text-align: right;\">This is right-aligned text</p>', 15),
(17, 1, -1, 'size', 'The <em>size</em> BB Code allows you to change the size of text in points. Which is the same system Microsoft Word or any other common text editor uses.\r\n\r\nTo set a size for specific text put [size=<strong>x</strong>] and [/size] around it. Where <strong>x</strong> is the size in points.\r\n\r\nThis is [size=22]big[/size] text\r\n\r\nIt will look like this:\r\n\r\nThis is <span style=\"font-size: 22pt;\">big</span> text', 16),
(18, 1, -1, 'u', 'The <em>u</em> BB Code will allow you to underline text.\r\n\r\nTo underline text put [u] and [/u] around it. Like this:\r\n\r\nThis is [u]underlined[/u] text\r\n\r\nIt will look like this:\r\n\r\nThis is <span style=\"text-decoration: underline;\">underlined</span> text', 17),
(19, -1, 1, 'User Control Panel', NULL, 3),
(20, 19, -1, 'Profile', 'If the administrator has enabled it for your usergroup, you may edit your profile via the <a href=\"usercp.php\">User Control Panel</a>. \r\n\r\nIn your profile, you can find various amounts of information that you can fill in, to let others know a little bit more about yourself.\r\n\r\nIf the administrator has enabled it, you will also be able to change and reset your custom title.\r\n\r\nAll information in your profile is optional.', 1),
(21, 19, -1, 'Preferences', 'The preferences area of the <a href=\"usercp.php\">User Control Panel</a> allows you to customize your experience on any given message board. Ranging from changing your time zone to fit all the dates on the message board, to being invisible, and disallowing all incoming personal messages. You can also pick the order in which posts in any given thread are displayed. If you\'re on a slower connection, you can completely disable the viewing of avatars, signatures, and/or attachments. ', 2),
(22, 19, -1, 'Editing Email Address', 'When you edit your email address, you are asked to put in your new desired email address, and then put it in again for confirmation.\r\n\r\nAfter you submit the form, either your new email address will be instated, or you will be sent an email notification. After you submit the form, you will know which one will happen. This outcome depends upon an administrative option. \r\n\r\nIf you are sent a verification email, it will be sent to the new email you provided. There will be a link in the email that you can follow that will oficially instate your new email address. You only have 24 hours to validate your new email address, or else you will need to request to change it again.', 3),
(23, 19, -1, 'Edit Password', 'In order to edit your password, you just need enter your current password, followed by your new desired password, then you must enter your new desired password again for confirmation. Your new password will take effect immediately.', 4),
(24, 19, -1, 'Edit Avatar', 'If the administrator has enabled it, you have the option to change your very own avatar. Which is usually a small picture that goes along side your name in all your posts.\r\n\r\nThere are three different ways that you can add an avatar. You can either use a pre-defined one, enter a URL, or upload one (if the administrator permits it).\r\n\r\nIf you enter a URL or upload an avatar they must meet certain restrictions set by the administrator. Whether you enter a URL or upload, it will be stored locally on the server.', 5),
(25, 19, -1, 'Edit Signature', 'If the administrator has enabled it, you may edit your signature. You can type in your signature, then view a preview of it before submitting. You will also see your current signature when you first edit it.', 6),
(26, -1, 1, 'Personal Messaging', NULL, 4),
(27, 26, -1, 'Overview', 'If personal messaging is enabled for your usergroup and it is enabled on the bulletin board on a global scale, you may use the Personal Messaging system.\r\n\r\nThe personal messaging system is a way to interact with another member on a personal level. You can send, receive, and organize messages. You can even send messages to multiple users at a time, if your group permits it.\r\n\r\nYou also will have a personal messaging limit, that limits you to the amount of messages you can store. You can easily see how much room you have left, by looking at the green bar at the top of every page associated with personal messaging. This also counts all your sent messages.', 1),
(28, 26, -1, 'Sending a Message', 'You can send a personal message to another member, by going to you <a href=\"usercp.php\">User Control Panel</a>, and clicking the link \"Send PM\" under the Personal Messaging category.\r\n\r\nWhen you get to the \"Send PM\" screen, there will be a few fields to fill out. In the first field, you\'ll find that you can enter usernames of the members you want to send to. If your group permits it, you may enter multiple member\'s usernames, so you can send the message to multiple users. Separate each name with a comma.\r\n\r\nThe next box is merely a place to enter the subject of the message. Which is also the title of the message.\r\n\r\nNext, is your message. If the administrator has permitted it, you may use smilies and BB Code.\r\n\r\nIf your group permits it, you will also have an option to \"Request a read receipt\". This will allow you to send a receipt to each of the members you send the message to. This will give you the ability to check if and when the member has read your message.\r\n\r\nIf your group permits it, you may also add attachments to your message via the \"Edit Attachments\" button.', 2),
(29, 26, -1, 'Folders', 'If your group permits it, you will also be able to add folders to greater increase your organization level of your messages. You may add as many folders as you wish. You can also edit existing folder name\'s, empty folders of all messages, and delete folders. If you delete an existing folder, all message contained in the folder will be automatically moved to the inbox folder.', 3),
(30, 26, -1, 'Message Receipts', 'Message receipts are a way of tracking your messages, to see if the recipient has read the message, and if it has been read, it will tell you when it was read. Besides that, you may delete your message receipts. There is no limit on the amount of receipts you may have. This feature is only enabled if your group permits it.', 4),
(31, 26, -1, 'Message Rules', 'You may only use message rules if your group permits it. There may also be a limit on the amount of message rules you can use.\r\n\r\nMessage rules work very smiliar to the messages rules in the program Microsoft Outlook (or perhaps Outlook Exprees). Message rules allow you a whole new level of sorting personal messages. You can set it so a whole entire usergroup\'s messages get automatically moved to a specific folder, or perhaps even deleted. You can even specify that personal messages sent by a specific user be moved or deleted. You can also specify an execution order, which is the order in which these message rules will be applied to a given message. When would this be necessary? For example, say you have all messages from the usergroup \"Moderators\" being moved to a folder named \"Staff Messages\", yet you want \"jamslam\"\'s messages to be moved to a folder named \"Jamslams\'s Messages\", and jamslam is a part of the moderator usergroup. If you execute the usergroup move, before the username move, you\'ll get the results you want. However, if you reverse the order, jamslam\'s messages will end up in the \"Staff Messages\" folder.\r\n\r\nYou can specify the criteria for a message by either entering an username, or you can select an usergroup. If you want to use an username, you must select \"Use Username\" from the usergroup drop down list. If you enter a username, and do not select \"Use Username\" from the usergroup list, it will use the usergroup you selected, you may however go back and edit it. You can choose an action by either selecting \"Delete\" or a \"Move to folder\" option. You can also specify an execution order number, as results vary depending upon the order of each of these rules being executed.', 5),
(32, -1, 1, 'Posting Messages', NULL, 2),
(33, 32, -1, 'Making a New Thread', 'If your group permits it, you may start a new thread for discussion. A thread will have a title, and usually a question of some sort to get a discussion going. Or perhaps a debate on a certain topic.\r\n\r\nTo make a new thread, simply find the relevant forum to the topic you have in mind, and enter the forum. You should see a \'Post Thread\' button right above all the other threads, you can\'t miss it. Click that button.\r\n\r\nYou will be taken to a page where you can type your thread title, chose your post icon, and write your message. You may also to subscribe to your own thread upon submission. This will subscription will send you an email every time someone makes a reply in your thread. If your group permits it, you may also add attachments to your thread. Also, if your group permits it, you may make a poll. This poll will allow viewers of your thread to vote on the criteria you specify.\r\n\r\nYour thread will show up in the list of threads for that forum immediately after submission. After you click submit, you will be redirected to your newly created thread, where you and others may make replies.\r\n\r\nIf you did subscribe to your thread, you may unsubscribe by going to your <a href=\"usercp.php\">User Control Panel</a>, or clicking the \"Unsubscribe\" link at the bottom of the corresponding thread.', 1),
(34, 32, -1, 'Making a Reply', 'If your group permits it, and the thread you want to reply to is open, then you may post a reply that will be added to that thread. Usually replies consist of contributing information or opinions about the topic at hand.\r\n\r\nYou may post a reply by either using the quick reply at the bottom of the thread (if it is enabled), or you may click the \"Post Reply\" button, located at the top and bottom of each thread. If you would like to quote someone\'s post in your reply, you may do so by clicking the \"reply\" button at the bottom of the corresponding post. You may also use a feature called multi-quote. This allows you to quote multiple posts at one time. Simply check the \"Quote\" checkbox for each post you wish to quote, and hit the \"reply\" button on any of the posts you checked.\r\n\r\nIf you are using the quick reply, you can simply type your message and hit submit. It will immediately show up in the thread.\r\n\r\nIf you quoted a reply (or replies), or simply hit the \"Post Reply\" button, you will be taken to a screen that is almost idential to that of \"Making a Thread\". Here, you can do multiple things, such as add smilies, pick a post icon, enter a title for your post, type your message, upload attachments, and some other miscellaneous options. You may also \"Preview\" your post, which is something you can do when your making a thread, making a new reply, or editing a post. Previewing a message will allow you to see what it will look like in the thread, without actually submitting it.', 2),
(35, 32, -1, 'Editing a Message', 'If you have made a reply or a thread, you may edit that reply if your group permits it. You will know if your group permits it, if you can see an \"edit\" button under your corresponding post. If you think your group permits it, and it still doesn\'t show up, you may have passed a set amount of time in which you can edit your own reply.\r\n\r\nWhen you are editing a message, you are presented with a screen much similar to that of the Making a Thread and Posting a Reply screens. One difference you might note here, is a little \"Reason:\" field that you may fill out. This allows you to specify a reason as to why you edited your post. Also, if your group permits it, you may be able to check the \"Delete Post\" option, in which your post will become unviewable.\r\n\r\nYou may edit virtually anything about your post, including attachments (you may add or delete them), your message, your title, post icons, etc.', 3),
(36, 32, -1, 'Attachments', 'If the administrator has permitted it for your group, and on a global spectrum, you may attach or upload files/images to a reply of yours. You can do this by clicking the \"Edit Attachments\" button when you are submitting or editing a reply. If you cannot see this button, then you cannot add or delete attachments.\r\n\r\nWhen you click this button, a new window will pop up. You will see a field, with a \"Browse\" button to the right of it. When you click the \"Browse\" button, you will be able to browse your computer, and select a file that you can upload. Remember, the file has to be on the valid file extensions list, which is to the left of the \"Browse\" field.\r\n\r\nYou may also have a limit to the number of attachments you may upload. If you suddenly see the \"Browse\" field box disappear, you know that you may not upload anymore attachments. You may however, delete an existing attachment for this post, and add a different. You can delete any given attachment by clicking the \"Delete\" link on the corresponding attachment.', 4),
(37, 32, -1, 'Adding a Poll', 'If your group permits it, you may add a poll to a topic, <strong>only</strong> if you created that topic. You will only be able to add a poll right when you are making the thread, by ticking the box that says \"Use Poll\", or in a short time (depends on how much time the administrator has set) period after making the thread by clicking an \"Add Poll\" link at the bottom of the corresponding thread.\r\n\r\nIn the \"Add Poll\" screen, you may enter a \"Poll Question\", which is what you will be polling users on. You may also enter options for the users to chose. You may edit the amount of options you want to use by changing the \"Poll Options\" field (you will not lose any current information when clicking the \"Update Options\" button). \r\n\r\nAfter you have entered each of the options that the user is allowed to chose, you may also enter a Time Out. This will allow you to specify the number of days in which the poll will close, and then, no further voting will be accepted. You can set the timeout to <strong>0</strong> (default value) to give the poll an indefinite open period.\r\n\r\nYou also have the option of making it multiple choice poll, which will allow the user to select multiple options, instead of just one. You may also make the poll a public poll. This will show the users who voted for each option, under the option.\r\n\r\nBe careful, once you submit the poll you cannot make any changes to it unless you contact a forum moderator, or someone on the administrative staff.', 5),
(38, -1, 1, 'Miscellaneous', NULL, 6),
(39, 38, -1, 'Searching', 'WebTricksCentral BB has a built-in search feature, that allows a fully customizable search, whether you want very precise results, or general results. To start searching the message board, click the \"Search\" link at the top of any page regarding WebTricksCentral BB. If you get an error, it means your group is not allowed to search the forums.\r\n\r\nOnce you get to the search page, you\'ll see the first field is for your <strong>Keyword</strong>. You can search posts, or you can search titles of posts/threads by ticking the \"Search Titles Only\" box.\r\n\r\nThe second field, <strong>Username</strong> allows you to make the search only find posts/threads that are made by the username you specified. By default, it will search for posts and threads made by this user. You may narrow your search by only search threads made by this user by ticking the \"Search for threads made by this user\" box. You can also match the usernames exactly, or find all similar usernames as well. You can either have this field and the Keyword field filled in, or you may have one or the other. But you must have at least the <strong>Username</strong> or <strong>Keyword</strong> field filled out.\r\n\r\nThe third field allows you to change how your results are shown to you. You can either view your results as posts (like they are in threads), or you can view them in a thread list (like they are in a forum).\r\n\r\nThe fourth field allows you to narrow your search down to specific times. You can select \"Any Time\" to not narrow your search using times. Simply select the appropriate options in each drop down select box. For example, you could find all posts made since yesterday, by selecting \"Yesterday\" from the first box, and \"and Newer\" from the second box.\r\n\r\nThe fifth field allows you to sort your results by a specific field. You can sort the results by the last reply (threads) and the date made with posts, replies (threads only), or you can sort it by the username of the user who made the post/thread.\r\n\r\nThe sixth field allows you to change the order of your results. For example, if your results consisted of the numbers 1, 2, 3, 4, and 5, then \"descending\" order would be \"5 4 3 2 1\", and ascending would be \"1 2 3 4 5\".\r\n\r\nThe seventh and last field allows you to specify which forum or forum<strong>s</strong> you want to make your search in. You can search all forums, or you can search individual forums. You may search more than one forum by clicking on a forum, then holding control, click on all the other forums you want. This should select multiple forums.', 1),
(40, 38, -1, 'Marking Forums Read', 'When you click the link at the bottom of the <a href=\"index.php\">forum index</a> page called \"Mark All Forums Read\", this will delete any and all cookies set by the message board, <strong>except</strong> the cookie(s) that you keep you logged in. It will also reset your last visit to the current time.\r\n\r\nWhy is this useful?\r\n\r\nWell, if you are away from the forum for a certain period of time, and you come back, how can you tell which forums have posts you haven\'t seen before? Well, when forums contain posts you haven\'t seen before, they will have a different icon to the left of them (on the forum list). When you enter the forum, any threads that you haven\'t read yet will have  a little graphic with an \"N\" next to it, standing for \"New\". If you click on this image, it will take you to the newest post that you haven\'t read (which isn\'t necessarily the last post).\r\n\r\nWhen you mark all forums read, this will make it so it appears you have read any and all threads on the forum.', 2),
(41, 38, -1, 'View New Posts', 'When you click the link at the bottom of the <a href=\"index.php\">forum index</a> called \"View New Posts\" you will be presented with a list of threads that have been made or have been updated since your last visit.\r\n\r\nThis is an easy and convenient way of viewing posts that have been made while you have been gone, instead of having to dig through each forum.', 3),
(42, 38, -1, 'Who\'s Online', 'You can view the <a href=\"online.php\">Who\'s Online</a> page by clicking the \"Currently Active Users\" link near the bottom of the <a href=\"index.php\">forum index</a> page. You will be presented with a screen that will give you a list of users and guests that are currently browsing the forums. You may also see where they are at, and if you have permission, you may see a detailed location and/or the IP Address of the user. It will also give you links to contact each of the users, by way of email or personal messaging. \r\n\r\nYou also have a few viewing options regarding viewing the users. You may display either Members & Guests, just members, or just guests. You may also sort the users currently online, by clicking on the corresponding header (ie: \"User Name\", \"Last Activity\", and \"Action\"). You can change the order as well, to either descending or ascending. If you have permission, you may also be able to show the user agent. You can also change how many users/guests are displayed per page.', 4),
(43, 38, -1, 'Listed Usergroups', 'You can find the link to <a href=\"index.php?do=usergroups\">Listed Usergroups</a> on the bottom of the <a href=\"index.php\">forum index</a> page. The listed usergroups page allows you to view the most important usergroups (and their corresponding members) of the forums, as deemed by the administrator. It also allows you to see each of the moderators, and what forums they moderate.', 5),
(44, 38, -1, 'Sending Emails', 'In order to send an email to a member, you first must find a link that says \"Send Email\". This link can be found in the member list, in the actual members profile, at the bottom of a corresponding post of the users, who\'s online, etc. When you follow the link, you will be taken to a form where you can type your subject and your message. Note that you cannot actually view the user\'s email address, this is for spam-bot protection, and privacy reasons. It also allows the forum to control how much email you send, by allowing the administrator to set a flood check.', 6),
(45, 38, -1, 'Flooding', 'If the administrator hasn\'t disabled the flood check, and you aren\'t part of a usergroup that has flood check immunity, you must wait an interval of a certain amount of seconds (30 is the default) before you can post another message. That includes emailing, personal messaging, and posting. This is to help protect against spammers.', 7),
(46, 38, -1, 'Members List', 'You can view a list of members in the <a href=\"members.php\">Members List</a>. The link is also at the top of every page regarding WebTricksCentral BB. In this list, you may view some various information about the user, such as post count, the last post, join date, and contact information. If you want more information about the user, then you may click on the username to view their profile.', 8),
(47, -1, 1, 'Registering', NULL, 1),
(48, 47, -1, 'Registering', 'You can find the link to <a href=\"register.php\">Register</a> at the top of every page concerning WebTricksCentral BB.\r\n\r\nIf the administrator has enabled COPPA registerations, you will need to provide your date of birth as the first step in the registeration. If you do not need to provide this information in the first step, then you may skip over these instructions.\r\n\r\nThe COPPA (Child Online Privacy Protection Act) compliancy is built into WebTricksCentral BB, and it allows applicants under the age of thirteen to register legally.\r\n\r\nIf you are under the age of thirteen, and COPPA registerations are enabled, your next step will only differ a little bit. You\'ll notice that there is some information on the COPPA Compliancy that you must follow. You must also agree to the forum rules. \r\n\r\nAfter you click \"I Agree\", you will be taken to another screen, which is where you can enter all the required information to register. \r\n\r\nIf you are a COPPA user (under the age of thirteen), there will be an extra field entitled \"Parent\'s E-mail Address\". Your parent must fill this portion out, as your parent will receive an email asking permission for you to join.\r\n\r\nAfter you have entered all the information, hit submit. If the administrator has required email verification, you will be sent an email that contains a link, that if you go to, your account will be validated. If the administrator does not require email verification, then you have completed the registeration process.\r\n\r\nIf you are a COPPA user, and your parent does not give you permission by the email verification, your account will be deleted within 14 days.\r\n\r\nIf you did not receive your activation email, you may request it again by going to your <a href=\"usercp.php\">User Control Panel</a>. There will be a link near the top of every page that will allow you to resend the email.', 1);";

$mysql['insert']['usergroups'] = "INSERT INTO usergroups (usergroupid, name, name_html_begin, name_html_end, usertitle, description, is_super_moderator, is_admin, show_groups, show_memberlist, show_birthdays, is_banned, see_invisible, see_profile, edit_own_profile, can_invisible, show_edited_notice, can_usertitle, can_sig, can_view_board, can_view_threads, can_view_deletion, can_perm_delete, can_search, can_attachments, can_post_threads, can_reply_own, can_reply_others, can_edit_own, can_delete_own, can_close_own, can_delete_threads_own, can_upload_attachments, attachment_filesize, flood_immunity, can_post_polls, can_vote_polls, can_use_avatar, can_upload_avatar, avatar_width, avatar_height, avatar_filesize, personal_max_messages, personal_receipts, personal_deny_receipt, personal_max_users, personal_folders, personal_rules, can_view_online, can_view_online_details, can_view_online_ip, can_default_bbcode, warn_others, warn_protected, warn_viewOwn, warn_viewOthers, book_hidden, book_viewOthers, book_viewOwn, book_addOthers, book_addOwn, book_editOwn, book_deleteOwn, book_permDeleteOwn, book_editOthers, book_deleteOthers, book_permDeleteOthers) VALUES (1, 'Guest', '', '', 'Guest', '', 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 1, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, 0, 0, 0, 0, 64, 64, 20000, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 'COPPA Users', '', '', '<strong style=\"color: #bb0000;\">COPPA User</strong>', '', 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 0, 1, 1, 1, 0, 0, 1, 1, 1, 1, 1, 1, 0, 0, 0, 1, NULL, 0, 1, 1, 1, 0, 64, 64, 20000, 0, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, 1, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 'Users Awaiting Activation', '', '', '<strong style=\"color: #bb0000;\">Awaiting Activation</strong>', '', 0, 0, 0, 1, 0, 0, 0, 1, 1, 0, 1, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, 0, 0, 0, 0, 64, 64, 20000, 0, 0, 0, 1, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 'Registered Users', '', '', '', '', 0, 0, 0, 1, 1, 0, 0, 1, 1, 1, 1, 0, 1, 1, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, NULL, 0, 1, 1, 1, 1, 64, 64, 20000, 50, 1, 0, 5, 1, 10, 1, 0, 0, 1, 0, 0, 1, 0, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 0),
(5, 'Banned', '', '', '<strong style=\"color: #bb0000;\">Banned</strong>', '', 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, NULL, 0, 0, 0, 0, 0, 64, 64, 20000, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(6, 'Moderators', '<strong style=\"color: #bb0000;\">', '</strong>', '<strong style=\"color: #bb0000;\">Moderator</strong>', '', 0, 0, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, NULL, 0, 1, 1, 1, 1, 64, 64, 20000, 100, 1, 0, 10, 1, 5, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 0, 0),
(7, 'Super Moderators', '<strong style=\"color: #007100; text-decoration: underline;\">', '</strong>', '<strong style=\"color: #bb0000; text-decoration: underline;\">Super Moderator</strong>', '', 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, NULL, 0, 1, 1, 1, 1, 64, 64, 20000, 200, 1, 0, 10, 1, 20, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 0),
(8, 'Administrators', '<strong style=\"color: #e44214; font-style: italic; text-decoration: underline;\">', '</strong>', '<strong style=\"color: #bb0000; font-style: italic; text-decoration: underline;\">Administrator</strong>', '', 1, 1, 1, 1, 1, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, NULL, 1, 1, 1, 1, 1, 64, 64, 20000, 500, 1, 1, 10, 1, 20, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);";

$mysql['insert']['wtcBBoptions'] = "INSERT INTO wtcBBoptions (optionid, floodcheck, active, active_reason, details_boardname, details_boardurl, details_homepage, details_homepageurl, details_contact, details_privacy, details_copyright, details_company, general_keywords, general_description, general_style, general_forumjump, general_pagelinks, general_wordwrap, general_templatename, general_modcp, date_timezone, date_dst, date_default_thread_age, date_todayYesterday, date_formatted, date_time_format, date_register_format, date_birthday_year, date_birthday_noyear, cookie_timeout, cookie_path, cookie_domain, server_sessionlimit, censor_enabled, censor_replace, censor_words, enable_email, enable_user_email, allow_new_registrations, use_coppa, send_welcome_email, notify_email_new, verify_email, usergroup_coppa_redirect, usergroup_redirect, require_unique_email, minimum_username, maximum_username, illegal_username, usertitle_maximum, usertitle_censored, exempt_mods, allow_signatures, maximum_signature, allow_wtcBB_sig, allow_smilies_sig, allow_img_sig, allow_html_sig, allow_change_styles, avatar_enabled, avartar_display_width, avatars_per_page, memberlist_enabled, members_per_page, enable_banning, blocked_ip, blocked_email, enable_quick_reply, minimum_chars_post, maximum_chars_post, maximum_images, show_edit_message, edit_timeout, logip, poll_timeout, clickable_smilies_row, clickable_smilies_total, allow_attachments, attachments_per_post, maximum_poll_options, search_enabled, search_minimum, search_maximum, num_of_search_page, maximum_search_results, display_loggedin_users, display_birthdays, depth_forums, show_subforums, show_forum_descriptions, hide_private, show_mod_column, other_depth_forums, other_show_subforums, other_show_forum_descriptions, other_hide_private, other_show_mod_column, show_users_browsing_forum, maximum_threads, show_all_announcements, show_sticky_all, hot_views, hot_replies, multi_thread_links, multi_thread_max_links, thread_preview_max, pre_sticky, pre_poll, pre_moved, pre_closed, show_users_browsing_thread, max_posts, user_set_max_posts, check_thread_subscribe, personal_enabled, personal_max_chars, personal_messages_per_page, allow_wtcBB_personal, allow_smilies_personal, allow_img_personal, allow_html_personal, online_enabled, online_refresh, online_guest, online_resolveIP, version_num, version_text, toolbar, personal_check, general_attachments, forumStatsLevel, lastCoppaCheck, css_in_file, customTitle_days, customTitle_posts, customTitle_or, record_num, record_date, topicReview, robots, robots_desc, defaultBBCode, defaultFontsList, defaultColorsList, defaultSizeList, enableWarn, warnAutoBan, autoBanGroup, sendWarnNotify, enableGuestbook, guestbookPerPage, guestbookNotify) VALUES (1, 30, 0, 'This bulletin board is currently not activated.', '', '', '', '', '', '', '', '', '', '', 1, 1, 5, 70, 0, 1, -5, 0, 11, 1, 'm-d-Y', 'h:i A', 'M Y', 'F j, Y', 'F j', 900, '/', '', 0, '1', '*', '', 1, 1, 1, 'Enable COPPA form', 1, '', 1, 2, 4, 1, 3, 15, 'staff wtc wtcbb webtrickscentral moderator admin administrator manager', 25, 'staff wtc wtcbb webtrickscentral moderator admin administrator manager', 1, 1, 500, 1, 1, 0, 0, 1, 1, 4, 16, 1, 30, 1, '', '', 1, 5, 40000, 15, 1, 0, 1, 300, 3, 9, 1, 5, 10, 1, 2, 20, 30, 500, 1, 1, 2, 0, 1, 1, 1, 2, 0, 1, 1, 1, 1, 25, 0, 0, 200, 20, 1, 5, 300, '&lt;strong style=&quot;color: #004a00;&quot;&gt;Sticky:&lt;/strong&gt;', '&lt;strong style=&quot;color: #bb0000;&quot;&gt;Poll:&lt;/strong&gt;', '&lt;strong style=&quot;color: #bb0000;&quot;&gt;Moved:&lt;/strong&gt;', '&lt;strong style=&quot;color: #bb0000;&quot;&gt;Closed:&lt;/strong&gt;', 0, 15, '5,10,15,20,25,30,35,40,45,50', 1, 1, 20000, 30, 1, 1, 1, 0, 1, 60, 1, 0, '1.1.6', 'wtcBB 1.1.6', 1, 1, 1, 1, ".time().", 1, 0, 0, 1, 1, '".time()."',1,'lycos\r\nask jeeves\r\ngooglebot\r\nslurp@inktomi\r\nfast-webcrawler\r\nyahoo\r\nmsnbot','Lycos\r\nAsk Jeeves\r\nGoogle Bot\r\nInktomi\r\nAll The Web\r\nYahoo!\r\nMSN Bot',1,'verdana\r\narial\r\ntahoma\r\ncentury\r\ncomic sans ms\r\njester\r\ntrebuchet ms\r\ntimes new roman\r\nlucida sans\r\nteletype\r\ncourier new','#ff0000\r\n#0000ff\r\n#008000\r\n#800080\r\n#ffc0cb\r\n#000000\r\n#ffffff\r\n#ffff00\r\n#a52a2a\r\n#00ffff\r\n#ff00ff\r\n#4682b4\r\n#40e0d0\r\n#ffa500\r\n#ff4500\r\n#000080\r\n#32cd32\r\n#f08080\r\n#b22222\r\n#ffd700\r\n#c0c0c0\r\n#da70d6\r\n#cd5c5c\r\n#00ff00\r\n#4b0082\r\n#00285b','6\r\n8\r\n10\r\n12\r\n14\r\n16\r\n18\r\n22\r\n26\r\n32\r\n36',1,15,5,1,1,15,1);";

$mysql['insert']['attachment_storage'] = "INSERT INTO attachment_storage VALUES (1, 'gif', 100000, 0, 0, 'image/gif', 1),
(2, 'jpg', 100000, 0, 0, 'image/jpeg', 1),
(3, 'jpeg', 100000, 0, 0, 'image/jpeg', 1),
(4, 'psd', 100000, 0, 0, 'application/octet-stream', 1),
(5, 'png', 100000, 0, 0, 'image/png', 1),
(6, 'swf', 100000, 0, 0, 'application/x-shockwave-flash', 0),
(7, 'mp3', 100000, 0, 0, 'audio/mpeg', 0),
(8, 'bmp', 100000, 0, 0, 'image/bmp', 1),
(9, 'css', 100000, 0, 0, 'text/css', 0),
(10, 'html', 100000, 0, 0, 'text/html', 0),
(11, 'txt', 100000, 0, 0, 'text/plain', 1),
(12, 'mpeg', 100000, 0, 0, 'video/mpeg', 0),
(13, 'mov', 100000, 0, 0, 'video/quicktime', 0),
(14, 'xhtml', 100000, 0, 0, 'application/xhtml+xml', 0),
(15, 'zip', 100000, 0, 0, 'application/zip', 0),
(16, 'pdf', 100000, 0, 0, 'application/pdf', 1),
(17, 'doc', 100000, 0, 0, 'application/msword', 0),
(18, 'pjpeg', 100000, 0, 0, 'image/pjpeg', 1),
(19, 'png', 100000, 0, 0, 'image/x-png', 1),
(20, 'rar', 100000, 0, 0, 'application/octet-stream', 0),
(21, 'rtf', 100000, 0, 0, 'text/richtext', 0),
(22, 'ico', 100000, 0, 0, 'image/x-icon', 0),
(23, 'wbmp', 100000, 0, 0, 'image/vnd.wap.wbmp', 1);";

$mysql['insert']['forums'] = "INSERT INTO forums VALUES (1, 'General Category', 'This is a category, that can help organize your forum structure. You cannot make posts directly inside a category.', ".time().", '', 0, '', null, null, null, null, null, 1, 1, 1, -1, 1, 0, 1, 1, 1, 0, 1, 1, 1, 0, 0, 0, 0, '2', 1), (2, 'General Forum', 'This is a forum, which is a sub-forum of the &quot;General Category&quot; forum. You may make posts in this forum.', ".time().", '', 0, '', '".$theUsername."', 1, ".time().", 1, 'Welcome to wtcBB!', 0, 1, 1, 1, 1, 0, 1, 1, 1, 0, 1, 1, 1, 1, 1, 0, 0, null, 2);";

$mysql['insert']['usertitles'] = "INSERT INTO usertitles VALUES (1, 'New Member', 0),
(3, 'Junior Member', 50),
(4, 'Member', 100),
(5, 'Senior Member', 500),
(6, 'Veteran Member', 1000);";

$mysql['insert']['smilies'] = "INSERT INTO smilies VALUES (1, 'wink', ';)', 'images/smilies/wink.gif', 9),
(2, 'mad', ':mad:', 'images/smilies/mad.gif', 8),
(3, 'embarassed', ':o', 'images/smilies/embarassed.gif', 7),
(4, 'delighted', ':D', 'images/smilies/delighted.gif', 1),
(5, 'happy', ':)', 'images/smilies/happy.gif', 2),
(6, 'sad', ':(', 'images/smilies/sad.gif', 3),
(7, 'tongue', ':p', 'images/smilies/tongue.gif', 4),
(8, 'rolleyes', ':rolleyes:', 'images/smilies/rolleyes.gif', 5),
(9, 'dull', '-_-', 'images/smilies/dull.gif', 6);";

$mysql['insert']['post_icons'] = "INSERT INTO post_icons VALUES (1, 'wink', 'images/smilies/wink.gif', 9),
(2, 'tongue', 'images/smilies/tongue.gif', 8),
(3, 'sad', 'images/smilies/sad.gif', 7),
(4, 'rolleyes', 'images/smilies/rolleyes.gif', 6),
(5, 'mad', 'images/smilies/mad.gif', 5),
(6, 'happy', 'images/smilies/happy.gif', 4),
(7, 'delighted', 'images/smilies/delighted.gif', 1),
(8, 'dull', 'images/smilies/dull.gif', 2),
(9, 'embarassed', 'images/smilies/embarassed.gif', 3);";

$mysql['insert']['personal_folder'] = "INSERT INTO personal_folder VALUES (1, 'inbox', -1),
(2, 'sent items', -1);";

$mysql['insert']['bbcode'] = "INSERT INTO bbcode VALUES (1, 'bold', 'b', '<strong>{param}</strong>', '[b]This text is bold![/b]', 'makes text bold...', 0),
(2, 'image', 'img', '<img src=\"{param}\" />', '[img]./../images/lastpost.jpg[/img]', 'makes an image', 0),
(3, 'Advanced Link', 'url', '<a href=\"{option}\">{param}</a>', '[url=http://www.webtrickscentral.com]wtc.com[/url]', 'makes an \"advanced url\"', 1),
(4, 'italics', 'i', '<em>{param}</em>', '[i]This is italic text[/i]', 'makes italic text...', 0),
(5, 'underlined', 'u', '<span style=\"text-decoration: underline;\">{param}</span>', '[u]Underlined text![/u]', 'This makes underlined text', 0),
(6, 'link', 'url', '<a href=\"{param}\">{param}</a>', '[url]http://www.webtrickscentral.com[/url]', 'Makes a regular link...', 0),
(7, 'center', 'center', '<p style=\"text-align: center;\">{param}</p>', '[center]centered text[/center]', 'this centers text', 0),
(8, 'left', 'left', '<p style=\"text-align: left;\">{param}</p>', '[left]left aligned text[/left]', 'aligns text to the left', 0),
(9, 'right', 'right', '<p style=\"text-align: right;\">{param}</p>', '[right]right aligned text[/right]', 'aligns text to the right', 0),
(10, 'font', 'font', '<span style=\"font-family: {option};\">{param}</span>', '[font=tahoma]changes the font[/font]', 'changes the font', 1),
(11, 'color', 'color', '<span style=\"color: {option};\">{param}</span>', '[color=#006295]changes color of text[/color]', 'changes the color of text', 1),
(12, 'size', 'size', '<span style=\"font-size: {option}pt;\">{param}</span>', '[size=12]changes text size[/size]', 'changes the size of the text', 1),
(13, 'List', 'list', '<{option} style=\"margin-top: 0; margin-bottom: 0;\">{param}</{option}>', '[list=ol] [!]first[/!] [!]second[/!] [!]third[/!] [/list]', 'This starts a list', 1),
(14, 'List Item', '!', '<li>{param}</li>', '[list=ul] [!]List Item[/!] [/list]', 'This make a list item. Only to be used with the [list]', 0),
(15, 'Quote - Simple', 'quote', '<div class=\"fullQuote\"><p class=\"quoteTitle\">Quote:</p><div class=\"quote\">{param}</div></div>', '[quote]This is a quote[/quote]', 'This allows users to quote others\' posts. ', 0),
(16, 'Quote - Advanced', 'quote', '<div class=\"fullQuote\"><p class=\"quoteTitle\">Quote By <em>{option}</em></p><div class=\"quote\">{param}</div></div>', '[quote=jamslam]This is a quote by jamslam[/quote]', 'This allows you to quote specific users\' posts.', 1),
(17, 'Highlight PHP', 'php', 'See template: <strong>bbcode_php</strong> in the style system to edit the html.', 'See template: <strong>bbcode_php</strong> in the style system to edit the html.', 'Highlights given PHP code.', 0),
(18, 'Code/HTML', 'code', 'See template: <strong>bbcode_code</strong> in the style system to edit the html.', 'See template: <strong>bbcode_code</strong> in the style system to edit the html.', 'This allows you to enter coding.', 0),
(19, 'email', 'email', '<a href=\"mailto:{option}\">{param}</a>', '[email=scyth@wewub.com]Scyth\'s E-mail[/email]', 'Use this code to display a email adress', 1),
(20, 'Regular Email', 'email', '<a href=\"mailto:{param}\">{param}</a>', '[email]scyth@wewub.com[/email]', 'Use this code for a simple email address.', 0);";

$userid = 1;

$mysql['insert']['user_info'] = "INSERT INTO user_info (userid, username, username_html_begin, username_html_end, password, passwordDate, vBsalt, usertitle, usertitle_option, email, newEmail, newEmailDate, last_emailed, parent_email, is_coppa, usergroupid, date_joined, birthday, birthday_year, aim, msn, icq, yahoo, homepage, biography, locationUser, interests, occupation, user_ip_address, referrals, referral_username, signature, posts, threads, lastvisit, lastactivity, lastpost, lastpostid, lastPM, invisible, admin_send_email, receive_emails, use_pm, send_email_pm, popup_pm, view_signature, view_avatar, view_attachment, display_order, view_posts, date_default_thread_age, date_timezone, dst, style_id, avatar_url, allow_html, toolbar, ban_sig, auto_threadsubscription, useridHash, warn, enableGuestbook) VALUES (1, '".$theUsername."', null, null, '".md5($thePassword)."', null, null, '<strong style=\"color: #bb0000; font-style: italic;\">Administrator</strong>', 0, null, null, null, null, null, 0, 8, ".time().", null, null, null, null, null, null, null, null, null, null, null, '".$_SERVER['REMOTE_ADDR']."', 0, null, null, 1, 1, ".time().", ".time().", ".time().", 1, ".time().", 0, 1, 1, 1, 1, 1, 1, 1, 1, 'ASC', -1, null, '-5', 0, 0, 'none', 0, 1, null, null, '".md5(time().$userid)."', 0, 1), (0, 'Guest', null, null, null, null, null, null, null, null, null, null, null, null, null, 1, null, null, null, null, null, null, null, null, null, null, null, null, null, 0, null, null, null, null, null, null, null, null, null, 0, 0, 0, 0, 0, 0, 1, 1, 1, 'ASC', -1, null, null, 0, 0, 'none', null, 1, null, null, null, 0, 1);";

$mysql['update']['user_info'] = "UPDATE user_info SET userid = 0 WHERE username = 'Guest';";

$mysql['insert']['avatars'] = "INSERT INTO avatars VALUES (1, 'jamslam_av', 'avatars/jamslam_av.gif', 1, 'jamslam_av.gif'), (2, 'scyth_av', 'avatars/avatar2.gif', 2, 'avatar2.gif');";

$mysql['insert']['threads'] = "INSERT INTO threads (threadid, forumid, thread_name, thread_starter, thread_views, thread_replies, last_reply_date, post_icon_thread, deleted_thread, closed, sticky, date_made, last_reply_username, moved, poll, deleted_by_thread, deleted_reason_thread, first_post, last_reply_userid, last_reply_postid, delete_time_thread, threadUsername) VALUES (1, 2, 'Welcome to wtcBB!', 1, 1, 0, ".time().", '<img src=\"images/smilies/happy.gif\" alt=\"happy\" />', 0, 0, 0, ".time().", '".$theUsername."', 0, 0, null, null, 1, 1, 1, null, '".$theUsername."');";

$mysql['insert']['posts'] = "INSERT INTO posts (postid, threadid, userid, message, title, ip_address, date_posted, deleted, edited_by, forumid, post_icon, deleted_by, deleted_reason, show_sig, parse_smilies, parse_bbcode, edited_time, deleted_time, postUsername, defBBCode) VALUES (1, 1, 1, 'Congratulations, you have successfully setup your very own WebTricksCentral Bulletin Board! You may reply to this thread with a new reply, delete this thread, or even delete the forum \"General Forum\" that this thread is in via the Administrator Control Panel!\r\n\r\nThank you for choosing wtcBB as your bulletin board.\r\n\r\n- Andrew Gallant (wtcBB owner and developer)', 'Welcome to wtcBB!', '".$_SERVER['REMOTE_ADDR']."', ".time().", 0, null, 2, '<img src=\"images/smilies/happy.gif\" alt=\"happy\" />', null, null, 1, 1, 1, null, null, '".$theUsername."', 0);";

$mysql['insert']['admin_permissions'] = "INSERT INTO admin_permissions (adminid, userid, username, wtcBBoptions, announcements, forums_moderators, users, usergroups, logs_stats, avatars, smilies, post_icons, usertitles, bbcode, faq, styles, attachments, threads_posts, updateinfo, warn) VALUES (1, 1, '".$theUsername."', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);";


// UPGRADE TO 1.0.0 BETA 2 (from beta 1)
$mysql['upgrade_1-0-0_BETA_2']['alter']['wtcBBoptions'] = "ALTER TABLE wtcBBoptions ADD (
css_in_file TINYINT (1) DEFAULT '1',
customTitle_days MEDIUMINT,
customTitle_posts MEDIUMINT,
customTitle_or TINYINT (1)
);";

$mysql['upgrade_1-0-0_BETA_2']['update']['wtcBBoptions'] = "UPDATE wtcBBoptions SET version_text = 'wtcBB 1.0.0 BETA 2' , css_in_file = 1 , customTitle_days = 0 , customTitle_posts = 0 , customTitle_or = 1;";

$mysql['upgrade_1-0-0_BETA_2']['update']['bbcode'] = "UPDATE bbcode SET replacement = '<{option} style=\"margin-top: 0; margin-bottom: 0;\">{param}</{option}>' WHERE name = 'List';";
// END UPGRADE TO 1.0.0 BETA 2 QUERIES


// UPGRADE TO 1.0.0 BETA 3 (from beta 2)
$mysql['upgrade_1-0-0_BETA_3']['insert']['bbcode'] = "INSERT INTO bbcode VALUES (20, 'Regular Email', 'email', '<a href=\"mailto:{param}\">{param}</a>', '[email]scyth@wewub.com[/email]', 'Use this code for a simple email address.', 0);";

$mysql['upgrade_1-0-0_BETA_3']['alter']['threads'] = "ALTER TABLE threads CHANGE moved moved MEDIUMINT;";

$mysql['upgrade_1-0-0_BETA_3']['update']['wtcBBoptions'] = "UPDATE wtcBBoptions SET version_text = 'wtcBB 1.0.0 BETA 3';";
// END UPGRADE TO 1.0.0 BETA 3 QUERIES


// UPGRADE TO 1.0.0 RELEASE CANDIDATE 1 (from beta 3)
$mysql['update_1-0-0_RC_1']['update']['wtcBBoptions'] = "UPDATE wtcBBoptions SET version_text = 'wtcBB 1.0.0 Release Candidate 1';";
// END UPGRADE TO 1.0.0 RELEASE CANDIDATE 1 QUERIES


// UPGRADE TO 1.0.0 RELEASE CANDIDATE 2 (from RC 1)
$mysql['update_1-0-0_RC_2']['update']['wtcBBoptions'] = "UPDATE wtcBBoptions SET version_text = 'wtcBB 1.0.0 Release Candidate 2';";
// END UPGRADE TO 1.0.0 RELEASE CANDIDATE 2 QUERIES


// UPGRADE TO 1.0.0 RELEASE CANDIDATE 3 (from RC 2)
$mysql['upgrade_1-0-0_RC_3']['alter']['wtcBBoptions'] = "ALTER TABLE wtcBBoptions ADD (
record_date INT NOT NULL,
record_num INT
);";

if(strpos($_SERVER['PHP_SELF'],"upgrade_1-0-0_RC_3") !== false) {
	$recordInfo = mysql_fetch_array(mysql_query("SELECT * FROM sessions_record"));
}

$mysql['upgrade_1-0-0_RC_3']['update']['wtcBBoptions'] = "UPDATE wtcBBoptions SET record_date = '".$recordInfo['record_date']."' , record_num = '".$recordInfo['record_num']."' , version_text = 'wtcBB 1.0.0 Release Candidate 3';";

$mysql['upgrade_1-0-0_RC_3']['drop']['sessions_record'] = "DROP TABLE sessions_record;";

$mysql['upgrade_1-0-0_RC_3']['alter']['templates_default'] = "ALTER TABLE templates_default ADD (
template_php MEDIUMTEXT
);";

$mysql['upgrade_1-0-0_RC_3']['alter']['templates'] = "ALTER TABLE templates ADD (
template_php MEDIUMTEXT
);";

$mysql['upgrade_1-0-0_RC_3']['insert']['templates_default'] = "INSERT INTO templates_default (defaultid,type,templategroupid,title,template,version,template_php) VALUES (0,1,0,NULL,NULL,'1.0.0',NULL);";

$mysql['upgrade_1-0-0_RC_3']['update']['templates_default'] = "UPDATE templates_default SET defaultid = 0 WHERE title IS NULL AND template IS NULL AND template_php IS NULL;";
// END UPGRADE TO 1.0.0 RELEASE CANDIDATE 3 QUERIES


// UPGRADE TO 1.0.0 RELEASE CANDIDATE 4 (from RC 3)
$mysql['upgrade_1-0-0_RC_4']['alter']['admin_permissions'] = "ALTER TABLE admin_permissions ADD INDEX(userid);";

$mysql['upgrade_1-0-0_RC_4']['alter']['announcements'] = "ALTER TABlE announcements ADD (
INDEX(userid),
INDEX(forum),
INDEX(start_date),
INDEX(end_date)
);";

$mysql['upgrade_1-0-0_RC_4']['alter']['enabled'] = "ALTER TABLE attachment_storage ADD INDEX(enabled);";

$mysql['upgrade_1-0-0_RC_4']['alter']['attachments'] = "ALTER TABLE attachments ADD (
INDEX(attachmentthread),
INDEX(attachmentpost),
INDEX(isPM)
);";

$mysql['upgrade_1-0-0_RC_4']['alter']['faq'] = "ALTER TABLE faq ADD (
INDEX(parent),
INDEX(is_category)
);";

$mysql['upgrade_1-0-0_RC_4']['alter']['forums'] = "ALTER TABLE forums ADD INDEX(is_category);";

$mysql['upgrade_1-0-0_RC_4']['alter']['forums_permissions'] = "ALTER TABLE forums_permissions ADD (
INDEX(forumid),
INDEX(usergroupid)
);";

$mysql['upgrade_1-0-0_RC_4']['alter']['logged_ips'] = "ALTER TABLE logged_ips ADD (
INDEX(userid),
INDEX(ip_address)
);";

$mysql['upgrade_1-0-0_RC_4']['alter']['moderators'] = "ALTER TABLE moderators ADD (
INDEX(userid),
INDEX(forumid)
);";

$mysql['upgrade_1-0-0_RC_4']['alter']['personal_folder'] = "ALTER TABLE personal_folder ADD INDEX(userid);";

$mysql['upgrade_1-0-0_RC_4']['alter']['personal_msg'] = "ALTER TABLE personal_msg ADD (
INDEX(sentTo),
INDEX(alert)
);";

$mysql['upgrade_1-0-0_RC_4']['alter']['personal_receipt'] = "ALTER TABLE personal_receipt ADD (
INDEX(userid),
INDEX(receipt_sentTo)
);";

$mysql['upgrade_1-0-0_RC_4']['alter']['personal_rules'] = "ALTER TABLE personal_rules ADD INDEX(userid);";

$mysql['upgrade_1-0-0_RC_4']['alter']['poll'] = "ALTER TABLE poll ADD INDEX(threadid);";

$mysql['upgrade_1-0-0_RC_4']['alter']['poll_options'] = "ALTER TABLE poll_options ADD INDEX(threadid);";

$mysql['upgrade_1-0-0_RC_4']['alter']['posts'] = "ALTER TABLE posts ADD (
INDEX(deleted),
INDEX(threadid),
INDEX(forumid),
INDEX(userid),
INDEX(postUsername)
);";

$mysql['upgrade_1-0-0_RC_4']['alter']['replacement_variables'] = "ALTER TABLE replacement_variables ADD (
INDEX(styleid),
INDEX(is_global)
);";

$mysql['upgrade_1-0-0_RC_4']['alter']['sessions'] = "ALTER TABLE sessions ADD (
INDEX(userid),
INDEX(location),
INDEX(last_activity)
);";

$mysql['upgrade_1-0-0_RC_4']['alter']['templates'] = "ALTER TABLE templates ADD (
INDEX(styleid),
INDEX(defaultid),
INDEX(version),
INDEX(is_global),
INDEX(is_custom),
INDEX(templategroupid)
);";

$mysql['upgrade_1-0-0_RC_4']['alter']['templates_default'] = "ALTER TABLE templates_default ADD (
INDEX(templategroupid),
INDEX(version)
);";

$mysql['upgrade_1-0-0_RC_4']['alter']['thread_subscription'] = "ALTER TABLE thread_subscription ADD (
INDEX(userid),
INDEX(threadid)
);";

$mysql['upgrade_1-0-0_RC_4']['alter']['threads'] = "ALTER TABLE threads ADD (
INDEX(deleted_thread),
INDEX(thread_starter),
INDEX(threadUsername)
);";

$mysql['upgrade_1-0-0_RC_4']['change']['forums'] = "ALTER TABLE forums CHANGE forum_description forum_description MEDIUMTEXT DEFAULT NULL;";

$mysql['upgrade_1-0-0_RC_4']['update']['wtcBBoptions'] = "UPDATE wtcBBoptions SET version_text = 'wtcBB 1.0.0 Release Candidate 4';";

$mysql['upgrade_1-0-0_RC_4']['update']['templates_default'] = "UPDATE templates_default SET defaultid = 0 WHERE title IS NULL AND template IS NULL AND template_php IS NULL;";
// END UPGRADE TO 1.0.0 RELEASE CANDIDATE 4 QUERIES


// UPGRADE TO 1.0.0 RELEASE CANDIDATE 5 (from RC 4)
$mysql['upgrade_1-0-0_RC_5']['update']['user_info'] = "UPDATE user_info SET userid = 0 WHERE username = 'Guest';";

$mysql['upgrade_1-0-0_RC_5']['update']['wtcBBoptions'] = "UPDATE wtcBBoptions SET version_text = 'wtcBB 1.0.0 Release Candidate 5';";
// END UPGRADE TO 1.0.0 RELEASE CANDIDATE 5 QUERIES


// UPDATE TO 1.0.0 FINAL (from RC 5)
$mysql['upgrade_1-0-0']['insert']['faq'] = "REPLACE INTO faq (faqid, parent, is_category, title, message, display_order) VALUES (1, -1, 1, 'BB Code', NULL, 5),
(2, 1, -1, 'What is it?', 'BB Code is a special kind of code, that is usually particular to Message Boards. BB Code allows an end-user to use a dumbed down version of HTML, as allowing straight HTML will cause security issues. BB Code was developed so you can display images, format text, and more, all without the use or knowledge of HTML. Below you\'ll find some general rules.\r\n\r\nAlmost every BB Code will look something like this:\r\n\r\n[<strong>name_of_code</strong>]the formatted text[/<strong>name_of_code</strong>]\r\n\r\nHowever, it could also look something like this:\r\n\r\n[<strong>name_of_code</strong>=<strong>some_value</strong>]the formatted text[/<strong>name_of_code</strong>]\r\n\r\nUsually, the \"some_value\" will have an effect on how the text is formatted. For example, when using the <em>size</em> BB Code, you would use \"some_value\" to put a size for the text. However, if you were using the <em>b</em> BB Code, there would be no value, because it\'s only simply bolding the text. Also, there should be <strong>no quotes</strong> surrounding \"some_value\".\r\n\r\n\r\n You can find a list of all the default BB Codes, their function, and how to use them below.', 1),
(3, 1, -1, 'url', 'The <em>url</em> BB Code allows you to put a link to somewhere in your post.\r\n\r\nThere are two different ways of using the [url] BB Code. \r\n\r\nFirst way (Simple):\r\n[url]http://www.webtrickscentral.com[/url]\r\n\r\nIt will look like this:\r\n<a href=\"http://www.webtrickscentral.com\">http://www.webtrickscentral.com</a>\r\n\r\nSecond Way (Advanced):\r\n[url=http://www.webtrickscentral.com]wtc.com[/url]\r\n\r\nIt will look like this:\r\n<a href=\"http://www.webtrickscentral.com\">wtc.com</a>\r\n\r\nThere is a clear different between the two, in which you can specify a title for the link, so it doesn\'t actually display the link itself.', 2),
(4, 1, -1, 'b', 'The <em>b</em> BB Code will allow you to make your text bold.\r\n\r\nYou can make the text you want bold, by surrounding it with [b] and [/b]. Like this:\r\n\r\nThis is [b]bold[/b] text.\r\n\r\nIt will look like this:\r\n\r\nThis is <strong>bold</strong> text.', 3),
(5, 1, -1, 'center', 'The <em>center</em> BB Code will allow you to center text or images.\r\n\r\nTo center something, simply surround it with [center] and [/center]. Like this:\r\n\r\n[center]This text is centered[/center]\r\n\r\nIt will look like this:\r\n\r\n<p style=\"text-align: center;\">This text is centered</p>', 4),
(6, 1, -1, 'code', 'The <em>code</em> BB Code allows you to input code of any kind.\r\n\r\nTo display something as code, simply put the [code] and [/code] tags around your desired code. Like this:\r\n\r\n[code]&lt;body&gt;\r\n&lt;p&gt;This is code!&lt;/p&gt;\r\n&lt;/body&gt;[/code]\r\n\r\nIt will look like this:\r\n\r\n<div class=\"fullQuote\"><p class=\"quoteTitle\">Code:</p><div class=\"quote\" style=\"font-weight: normal; font-family: courier new;\">&lt;body&gt;\r\n&lt;p&gt;This is code!&lt;/p&gt;\r\n&lt;/body&gt;</div></div>', 5),
(7, 1, -1, 'color', 'The <em>color</em> BB Code allows you to change the color of text.\r\n\r\nTo change the color of text, simply put [color=<strong>x</strong>] and [/color] around the text, where <strong>x</strong> is your desired color.\r\n\r\n[color=#00285b]This text is dark blue[/color]\r\n\r\nIt will look like this:\r\n\r\n<span style=\"color: #00285b;\">This text is dark blue</span>', 6),
(8, 1, -1, 'email', 'The <em>email</em> BB Code allows you to make a link to email someone else. \r\n\r\nThe <em>email</em> BB Code works virtually the same as the <em>url</em> BB Code, except this provides a link to email someone. There are two ways to use the <em>email</em> BB Code:\r\n\r\nFirst Way (Simple):\r\n[email]example@example.com[/email]\r\n\r\nIt will look like this:\r\n<a href=\"mailto:example@example.com\">example@example.com</a>\r\n\r\nSecond Way (Advanced):\r\n[email=example@example.com]email scyth[/email]\r\n\r\nIt will look like this:\r\n<a href=\"mailto:example@example.com\">email scyth</a>', 7),
(9, 1, -1, 'font', 'The <em>font</em> BB Code allows you to change the font of any given text.\r\n\r\nTo change the font of text, simply put [font=<strong>x</strong>] and [/font] around the text whose font you want to change, where <strong>x</strong> is the font name.\r\n\r\nThis is [font=tahoma]tahoma[/font] text\r\n\r\nIt will look like this:\r\n\r\nThis is <span style=\"font-family: tahoma;\">tahoma</span> text', 8),
(10, 1, -1, 'php', 'The <em>php</em> BB Code allows you to syntax highlight any PHP Code.\r\n\r\nTo use syntax highlight php code put [php] and [/php] around the code you want highlighted. Like this:\r\n\r\n[php]<?php\r\nif($something == true) {\r\n    $increment++;\r\n} else {\r\n    $increment--;\r\n}\r\n?>[/php]\r\n\r\nIt will look like this:\r\n\r\n<div class=\"fullQuote\"><p class=\"quoteTitle\">PHP:</p><div class=\"quote\" style=\"font-weight: normal; font-family: courier new;\"><span style=\"color: #000000;\"><span style=\"color: #0000BB;\"></span><span style=\"color: #007700;\"><?</span><span style=\"color: #0000BB;\">php\r\n</span><span style=\"color: #007700;\">if(</span><span style=\"color: #0000BB;\">$something </span><span style=\"color: #007700;\">== </span><span style=\"color: #0000BB;\">true</span><span style=\"color: #007700;\">) {\r\n    </span><span style=\"color: #0000BB;\">$increment</span><span style=\"color: #007700;\">++;\r\n} else {\r\n    </span><span style=\"color: #0000BB;\">$increment</span><span style=\"color: #007700;\">--;\r\n}\r\n</span><span style=\"color: #0000BB;\">?>\r\n</span> </span></div></div>', 9),
(11, 1, -1, 'img', 'The <em>img</em> BB Code allows you to place an image in your message.\r\n\r\nIn order to put an image in your message simply put [img] and [/img] around the address of the image on the internet. Like this:\r\n\r\n[img]http://www.webtrickscentral.com/images/supportWTC.gif[/img]\r\n\r\nIt will look like this:\r\n\r\n<img src=\"http://www.webtrickscentral.com/images/supportWTC.gif\" alt=\"http://www.webtrickscentral.com/images/supportWTC.gif\" />', 10),
(12, 1, -1, 'i', 'The <em>i</em> BB Code will allow you to make text italic.\r\n\r\nTo make text italic put [i] and [/i] around the text you want to be italic. Like this:\r\n\r\nThis is [i]italic[/i] text.\r\n\r\nIt will look like this:\r\n\r\nThis is <em>italic</em> text.', 11),
(13, 1, -1, 'left', 'The <em>left</em> BB Code will allow you to align text to the left.\r\n\r\nTo align text to the left put [left] and [/left] around it. Like this:\r\n\r\n[left]Left-aligned text[/left]\r\n\r\nIt would look like this:\r\n\r\n<p style=\"text-align: left;\">Left-aligned text</p>', 12),
(14, 1, -1, 'list and !', 'In order to make a complete ordered or unordered list you need to make use of the <em>list</em> and <em>!</em> BB Codes. The <em>list</em> BB Code will actually form the list, and the <em>!</em> BB Code will form each list item. It would look something like this:\r\n\r\n[list=ol]\r\n[!]First List Item[/!]\r\n[!]Second List Item[/!]\r\n[!]Third List Item[/!]\r\n[/list]\r\n\r\nOr you can make an unordered list:\r\n\r\n[list=ul]\r\n[!]First List Item[/!]\r\n[!]Second List Item[/!]\r\n[!]Third List Item[/!]\r\n[/list]\r\n\r\nThe ordered list would look like this:\r\n<ol>\r\n<li>First List Item</li>\r\n<li>Second List Item</li>\r\n<li>Third List Item</li>\r\n</ol>\r\nThe unordered list would look like this:\r\n<ul>\r\n<li>First List Item</li>\r\n<li>Second List Item</li>\r\n<li>Third List Item</li>\r\n</ul>', 13),
(15, 1, -1, 'quote', 'The <em>quote</em> BB Code allows you to quote a source. There are two different types of quotes, a simple one and an advanced one. The simple one allows you to quote an unamed source, while the advanced one allows you to quote a named sourse. They will look something like this:\r\n\r\nSimple Quote:\r\n[quote]The incompatibility between science and religion is simply this: a scientist will not believe anything until he sees it; a religious man will not see anything until he believes in it.[/quote]\r\n\r\nIt would look like this:\r\n<div class=\"fullQuote\"><p class=\"quoteTitle\">Quote:</p><div class=\"quote\">The incompatibility between science and religion is simply this: a scientist will not believe anything until he sees it; a religious man will not see anything until he believes in it.</div></div>\r\n\r\nAdvanced Quote:\r\n[quote=Charles J. C. Lyall]The incompatibility between science and religion is simply this: a scientist will not believe anything until he sees it; a religious man will not see anything until he believes in it.[/quote]\r\n\r\nIt would look like this:\r\n<div class=\"fullQuote\"><p class=\"quoteTitle\">Quote By <em>Charles J. C. Lyall</em></p><div class=\"quote\">The incompatibility between science and religion is simply this: a scientist will not believe anything until he sees it; a religious man will not see anything until he believes in it.</div></div>', 14),
(16, 1, -1, 'right', 'The <em>right</em> BB Code allows you to align text or images to the right.\r\n\r\nTo align text to the right put [right] and [/right] around the desired text. Like this:\r\n\r\n[right]This is right-aligned text[/right]\r\n\r\nIt will look like this:\r\n\r\n<p style=\"text-align: right;\">This is right-aligned text</p>', 15),
(17, 1, -1, 'size', 'The <em>size</em> BB Code allows you to change the size of text in points. Which is the same system Microsoft Word or any other common text editor uses.\r\n\r\nTo set a size for specific text put [size=<strong>x</strong>] and [/size] around it. Where <strong>x</strong> is the size in points.\r\n\r\nThis is [size=22]big[/size] text\r\n\r\nIt will look like this:\r\n\r\nThis is <span style=\"font-size: 22pt;\">big</span> text', 16),
(18, 1, -1, 'u', 'The <em>u</em> BB Code will allow you to underline text.\r\n\r\nTo underline text put [u] and [/u] around it. Like this:\r\n\r\nThis is [u]underlined[/u] text\r\n\r\nIt will look like this:\r\n\r\nThis is <span style=\"text-decoration: underline;\">underlined</span> text', 17),
(19, -1, 1, 'User Control Panel', NULL, 3),
(20, 19, -1, 'Profile', 'If the administrator has enabled it for your usergroup, you may edit your profile via the <a href=\"usercp.php\">User Control Panel</a>. \r\n\r\nIn your profile, you can find various amounts of information that you can fill in, to let others know a little bit more about yourself.\r\n\r\nIf the administrator has enabled it, you will also be able to change and reset your custom title.\r\n\r\nAll information in your profile is optional.', 1),
(21, 19, -1, 'Preferences', 'The preferences area of the <a href=\"usercp.php\">User Control Panel</a> allows you to customize your experience on any given message board. Ranging from changing your time zone to fit all the dates on the message board, to being invisible, and disallowing all incoming personal messages. You can also pick the order in which posts in any given thread are displayed. If you\'re on a slower connection, you can completely disable the viewing of avatars, signatures, and/or attachments. ', 2),
(22, 19, -1, 'Editing Email Address', 'When you edit your email address, you are asked to put in your new desired email address, and then put it in again for confirmation.\r\n\r\nAfter you submit the form, either your new email address will be instated, or you will be sent an email notification. After you submit the form, you will know which one will happen. This outcome depends upon an administrative option. \r\n\r\nIf you are sent a verification email, it will be sent to the new email you provided. There will be a link in the email that you can follow that will oficially instate your new email address. You only have 24 hours to validate your new email address, or else you will need to request to change it again.', 3),
(23, 19, -1, 'Edit Password', 'In order to edit your password, you just need enter your current password, followed by your new desired password, then you must enter your new desired password again for confirmation. Your new password will take effect immediately.', 4),
(24, 19, -1, 'Edit Avatar', 'If the administrator has enabled it, you have the option to change your very own avatar. Which is usually a small picture that goes along side your name in all your posts.\r\n\r\nThere are three different ways that you can add an avatar. You can either use a pre-defined one, enter a URL, or upload one (if the administrator permits it).\r\n\r\nIf you enter a URL or upload an avatar they must meet certain restrictions set by the administrator. Whether you enter a URL or upload, it will be stored locally on the server.', 5),
(25, 19, -1, 'Edit Signature', 'If the administrator has enabled it, you may edit your signature. You can type in your signature, then view a preview of it before submitting. You will also see your current signature when you first edit it.', 6),
(26, -1, 1, 'Personal Messaging', NULL, 4),
(27, 26, -1, 'Overview', 'If personal messaging is enabled for your usergroup and it is enabled on the bulletin board on a global scale, you may use the Personal Messaging system.\r\n\r\nThe personal messaging system is a way to interact with another member on a personal level. You can send, receive, and organize messages. You can even send messages to multiple users at a time, if your group permits it.\r\n\r\nYou also will have a personal messaging limit, that limits you to the amount of messages you can store. You can easily see how much room you have left, by looking at the green bar at the top of every page associated with personal messaging. This also counts all your sent messages.', 1),
(28, 26, -1, 'Sending a Message', 'You can send a personal message to another member, by going to you <a href=\"usercp.php\">User Control Panel</a>, and clicking the link \"Send PM\" under the Personal Messaging category.\r\n\r\nWhen you get to the \"Send PM\" screen, there will be a few fields to fill out. In the first field, you\'ll find that you can enter usernames of the members you want to send to. If your group permits it, you may enter multiple member\'s usernames, so you can send the message to multiple users. Separate each name with a comma.\r\n\r\nThe next box is merely a place to enter the subject of the message. Which is also the title of the message.\r\n\r\nNext, is your message. If the administrator has permitted it, you may use smilies and BB Code.\r\n\r\nIf your group permits it, you will also have an option to \"Request a read receipt\". This will allow you to send a receipt to each of the members you send the message to. This will give you the ability to check if and when the member has read your message.\r\n\r\nIf your group permits it, you may also add attachments to your message via the \"Edit Attachments\" button.', 2),
(29, 26, -1, 'Folders', 'If your group permits it, you will also be able to add folders to greater increase your organization level of your messages. You may add as many folders as you wish. You can also edit existing folder name\'s, empty folders of all messages, and delete folders. If you delete an existing folder, all message contained in the folder will be automatically moved to the inbox folder.', 3),
(30, 26, -1, 'Message Receipts', 'Message receipts are a way of tracking your messages, to see if the recipient has read the message, and if it has been read, it will tell you when it was read. Besides that, you may delete your message receipts. There is no limit on the amount of receipts you may have. This feature is only enabled if your group permits it.', 4),
(31, 26, -1, 'Message Rules', 'You may only use message rules if your group permits it. There may also be a limit on the amount of message rules you can use.\r\n\r\nMessage rules work very smiliar to the messages rules in the program Microsoft Outlook (or perhaps Outlook Exprees). Message rules allow you a whole new level of sorting personal messages. You can set it so a whole entire usergroup\'s messages get automatically moved to a specific folder, or perhaps even deleted. You can even specify that personal messages sent by a specific user be moved or deleted. You can also specify an execution order, which is the order in which these message rules will be applied to a given message. When would this be necessary? For example, say you have all messages from the usergroup \"Moderators\" being moved to a folder named \"Staff Messages\", yet you want \"jamslam\"\'s messages to be moved to a folder named \"Jamslams\'s Messages\", and jamslam is a part of the moderator usergroup. If you execute the usergroup move, before the username move, you\'ll get the results you want. However, if you reverse the order, jamslam\'s messages will end up in the \"Staff Messages\" folder.\r\n\r\nYou can specify the criteria for a message by either entering an username, or you can select an usergroup. If you want to use an username, you must select \"Use Username\" from the usergroup drop down list. If you enter a username, and do not select \"Use Username\" from the usergroup list, it will use the usergroup you selected, you may however go back and edit it. You can choose an action by either selecting \"Delete\" or a \"Move to folder\" option. You can also specify an execution order number, as results vary depending upon the order of each of these rules being executed.', 5),
(32, -1, 1, 'Posting Messages', NULL, 2),
(33, 32, -1, 'Making a New Thread', 'If your group permits it, you may start a new thread for discussion. A thread will have a title, and usually a question of some sort to get a discussion going. Or perhaps a debate on a certain topic.\r\n\r\nTo make a new thread, simply find the relevant forum to the topic you have in mind, and enter the forum. You should see a \'Post Thread\' button right above all the other threads, you can\'t miss it. Click that button.\r\n\r\nYou will be taken to a page where you can type your thread title, chose your post icon, and write your message. You may also to subscribe to your own thread upon submission. This will subscription will send you an email every time someone makes a reply in your thread. If your group permits it, you may also add attachments to your thread. Also, if your group permits it, you may make a poll. This poll will allow viewers of your thread to vote on the criteria you specify.\r\n\r\nYour thread will show up in the list of threads for that forum immediately after submission. After you click submit, you will be redirected to your newly created thread, where you and others may make replies.\r\n\r\nIf you did subscribe to your thread, you may unsubscribe by going to your <a href=\"usercp.php\">User Control Panel</a>, or clicking the \"Unsubscribe\" link at the bottom of the corresponding thread.', 1),
(34, 32, -1, 'Making a Reply', 'If your group permits it, and the thread you want to reply to is open, then you may post a reply that will be added to that thread. Usually replies consist of contributing information or opinions about the topic at hand.\r\n\r\nYou may post a reply by either using the quick reply at the bottom of the thread (if it is enabled), or you may click the \"Post Reply\" button, located at the top and bottom of each thread. If you would like to quote someone\'s post in your reply, you may do so by clicking the \"reply\" button at the bottom of the corresponding post. You may also use a feature called multi-quote. This allows you to quote multiple posts at one time. Simply check the \"Quote\" checkbox for each post you wish to quote, and hit the \"reply\" button on any of the posts you checked.\r\n\r\nIf you are using the quick reply, you can simply type your message and hit submit. It will immediately show up in the thread.\r\n\r\nIf you quoted a reply (or replies), or simply hit the \"Post Reply\" button, you will be taken to a screen that is almost idential to that of \"Making a Thread\". Here, you can do multiple things, such as add smilies, pick a post icon, enter a title for your post, type your message, upload attachments, and some other miscellaneous options. You may also \"Preview\" your post, which is something you can do when your making a thread, making a new reply, or editing a post. Previewing a message will allow you to see what it will look like in the thread, without actually submitting it.', 2),
(35, 32, -1, 'Editing a Message', 'If you have made a reply or a thread, you may edit that reply if your group permits it. You will know if your group permits it, if you can see an \"edit\" button under your corresponding post. If you think your group permits it, and it still doesn\'t show up, you may have passed a set amount of time in which you can edit your own reply.\r\n\r\nWhen you are editing a message, you are presented with a screen much similar to that of the Making a Thread and Posting a Reply screens. One difference you might note here, is a little \"Reason:\" field that you may fill out. This allows you to specify a reason as to why you edited your post. Also, if your group permits it, you may be able to check the \"Delete Post\" option, in which your post will become unviewable.\r\n\r\nYou may edit virtually anything about your post, including attachments (you may add or delete them), your message, your title, post icons, etc.', 3),
(36, 32, -1, 'Attachments', 'If the administrator has permitted it for your group, and on a global spectrum, you may attach or upload files/images to a reply of yours. You can do this by clicking the \"Edit Attachments\" button when you are submitting or editing a reply. If you cannot see this button, then you cannot add or delete attachments.\r\n\r\nWhen you click this button, a new window will pop up. You will see a field, with a \"Browse\" button to the right of it. When you click the \"Browse\" button, you will be able to browse your computer, and select a file that you can upload. Remember, the file has to be on the valid file extensions list, which is to the left of the \"Browse\" field.\r\n\r\nYou may also have a limit to the number of attachments you may upload. If you suddenly see the \"Browse\" field box disappear, you know that you may not upload anymore attachments. You may however, delete an existing attachment for this post, and add a different. You can delete any given attachment by clicking the \"Delete\" link on the corresponding attachment.', 4),
(37, 32, -1, 'Adding a Poll', 'If your group permits it, you may add a poll to a topic, <strong>only</strong> if you created that topic. You will only be able to add a poll right when you are making the thread, by ticking the box that says \"Use Poll\", or in a short time (depends on how much time the administrator has set) period after making the thread by clicking an \"Add Poll\" link at the bottom of the corresponding thread.\r\n\r\nIn the \"Add Poll\" screen, you may enter a \"Poll Question\", which is what you will be polling users on. You may also enter options for the users to chose. You may edit the amount of options you want to use by changing the \"Poll Options\" field (you will not lose any current information when clicking the \"Update Options\" button). \r\n\r\nAfter you have entered each of the options that the user is allowed to chose, you may also enter a Time Out. This will allow you to specify the number of days in which the poll will close, and then, no further voting will be accepted. You can set the timeout to <strong>0</strong> (default value) to give the poll an indefinite open period.\r\n\r\nYou also have the option of making it multiple choice poll, which will allow the user to select multiple options, instead of just one. You may also make the poll a public poll. This will show the users who voted for each option, under the option.\r\n\r\nBe careful, once you submit the poll you cannot make any changes to it unless you contact a forum moderator, or someone on the administrative staff.', 5),
(38, -1, 1, 'Miscellaneous', NULL, 6),
(39, 38, -1, 'Searching', 'WebTricksCentral BB has a built-in search feature, that allows a fully customizable search, whether you want very precise results, or general results. To start searching the message board, click the \"Search\" link at the top of any page regarding WebTricksCentral BB. If you get an error, it means your group is not allowed to search the forums.\r\n\r\nOnce you get to the search page, you\'ll see the first field is for your <strong>Keyword</strong>. You can search posts, or you can search titles of posts/threads by ticking the \"Search Titles Only\" box.\r\n\r\nThe second field, <strong>Username</strong> allows you to make the search only find posts/threads that are made by the username you specified. By default, it will search for posts and threads made by this user. You may narrow your search by only search threads made by this user by ticking the \"Search for threads made by this user\" box. You can also match the usernames exactly, or find all similar usernames as well. You can either have this field and the Keyword field filled in, or you may have one or the other. But you must have at least the <strong>Username</strong> or <strong>Keyword</strong> field filled out.\r\n\r\nThe third field allows you to change how your results are shown to you. You can either view your results as posts (like they are in threads), or you can view them in a thread list (like they are in a forum).\r\n\r\nThe fourth field allows you to narrow your search down to specific times. You can select \"Any Time\" to not narrow your search using times. Simply select the appropriate options in each drop down select box. For example, you could find all posts made since yesterday, by selecting \"Yesterday\" from the first box, and \"and Newer\" from the second box.\r\n\r\nThe fifth field allows you to sort your results by a specific field. You can sort the results by the last reply (threads) and the date made with posts, replies (threads only), or you can sort it by the username of the user who made the post/thread.\r\n\r\nThe sixth field allows you to change the order of your results. For example, if your results consisted of the numbers 1, 2, 3, 4, and 5, then \"descending\" order would be \"5 4 3 2 1\", and ascending would be \"1 2 3 4 5\".\r\n\r\nThe seventh and last field allows you to specify which forum or forum<strong>s</strong> you want to make your search in. You can search all forums, or you can search individual forums. You may search more than one forum by clicking on a forum, then holding control, click on all the other forums you want. This should select multiple forums.', 1),
(40, 38, -1, 'Marking Forums Read', 'When you click the link at the bottom of the <a href=\"index.php\">forum index</a> page called \"Mark All Forums Read\", this will delete any and all cookies set by the message board, <strong>except</strong> the cookie(s) that you keep you logged in. It will also reset your last visit to the current time.\r\n\r\nWhy is this useful?\r\n\r\nWell, if you are away from the forum for a certain period of time, and you come back, how can you tell which forums have posts you haven\'t seen before? Well, when forums contain posts you haven\'t seen before, they will have a different icon to the left of them (on the forum list). When you enter the forum, any threads that you haven\'t read yet will have  a little graphic with an \"N\" next to it, standing for \"New\". If you click on this image, it will take you to the newest post that you haven\'t read (which isn\'t necessarily the last post).\r\n\r\nWhen you mark all forums read, this will make it so it appears you have read any and all threads on the forum.', 2),
(41, 38, -1, 'View New Posts', 'When you click the link at the bottom of the <a href=\"index.php\">forum index</a> called \"View New Posts\" you will be presented with a list of threads that have been made or have been updated since your last visit.\r\n\r\nThis is an easy and convenient way of viewing posts that have been made while you have been gone, instead of having to dig through each forum.', 3),
(42, 38, -1, 'Who\'s Online', 'You can view the <a href=\"online.php\">Who\'s Online</a> page by clicking the \"Currently Active Users\" link near the bottom of the <a href=\"index.php\">forum index</a> page. You will be presented with a screen that will give you a list of users and guests that are currently browsing the forums. You may also see where they are at, and if you have permission, you may see a detailed location and/or the IP Address of the user. It will also give you links to contact each of the users, by way of email or personal messaging. \r\n\r\nYou also have a few viewing options regarding viewing the users. You may display either Members & Guests, just members, or just guests. You may also sort the users currently online, by clicking on the corresponding header (ie: \"User Name\", \"Last Activity\", and \"Action\"). You can change the order as well, to either descending or ascending. If you have permission, you may also be able to show the user agent. You can also change how many users/guests are displayed per page.', 4),
(43, 38, -1, 'Listed Usergroups', 'You can find the link to <a href=\"index.php?do=usergroups\">Listed Usergroups</a> on the bottom of the <a href=\"index.php\">forum index</a> page. The listed usergroups page allows you to view the most important usergroups (and their corresponding members) of the forums, as deemed by the administrator. It also allows you to see each of the moderators, and what forums they moderate.', 5),
(44, 38, -1, 'Sending Emails', 'In order to send an email to a member, you first must find a link that says \"Send Email\". This link can be found in the member list, in the actual members profile, at the bottom of a corresponding post of the users, who\'s online, etc. When you follow the link, you will be taken to a form where you can type your subject and your message. Note that you cannot actually view the user\'s email address, this is for spam-bot protection, and privacy reasons. It also allows the forum to control how much email you send, by allowing the administrator to set a flood check.', 6),
(45, 38, -1, 'Flooding', 'If the administrator hasn\'t disabled the flood check, and you aren\'t part of a usergroup that has flood check immunity, you must wait an interval of a certain amount of seconds (30 is the default) before you can post another message. That includes emailing, personal messaging, and posting. This is to help protect against spammers.', 7),
(46, 38, -1, 'Members List', 'You can view a list of members in the <a href=\"members.php\">Members List</a>. The link is also at the top of every page regarding WebTricksCentral BB. In this list, you may view some various information about the user, such as post count, the last post, join date, and contact information. If you want more information about the user, then you may click on the username to view their profile.', 8),
(47, -1, 1, 'Registering', NULL, 1),
(48, 47, -1, 'Registering', 'You can find the link to <a href=\"register.php\">Register</a> at the top of every page concerning WebTricksCentral BB.\r\n\r\nIf the administrator has enabled COPPA registerations, you will need to provide your date of birth as the first step in the registeration. If you do not need to provide this information in the first step, then you may skip over these instructions.\r\n\r\nThe COPPA (Child Online Privacy Protection Act) compliancy is built into WebTricksCentral BB, and it allows applicants under the age of thirteen to register legally.\r\n\r\nIf you are under the age of thirteen, and COPPA registerations are enabled, your next step will only differ a little bit. You\'ll notice that there is some information on the COPPA Compliancy that you must follow. You must also agree to the forum rules. \r\n\r\nAfter you click \"I Agree\", you will be taken to another screen, which is where you can enter all the required information to register. \r\n\r\nIf you are a COPPA user (under the age of thirteen), there will be an extra field entitled \"Parent\'s E-mail Address\". Your parent must fill this portion out, as your parent will receive an email asking permission for you to join.\r\n\r\nAfter you have entered all the information, hit submit. If the administrator has required email verification, you will be sent an email that contains a link, that if you go to, your account will be validated. If the administrator does not require email verification, then you have completed the registeration process.\r\n\r\nIf you are a COPPA user, and your parent does not give you permission by the email verification, your account will be deleted within 14 days.\r\n\r\nIf you did not receive your activation email, you may request it again by going to your <a href=\"usercp.php\">User Control Panel</a>. There will be a link near the top of every page that will allow you to resend the email.', 1);";

$mysql['upgrade_1-0-0']['update']['user_info'] = "UPDATE user_info SET userid = 0 WHERE username = 'Guest';";

$mysql['upgrade_1-0-0']['update']['wtcBBoptions'] = "UPDATE wtcBBoptions SET version_text = 'wtcBB 1.0.0';";

$mysql['upgrade_1-0-0']['delete']['templates_default'] = "DELETE FROM templates_default WHERE title IS NULL AND template IS NULL AND template_php IS NULL;";
// END UPGRADE TO 1.0.0 FINAL QUERIES


// BEGIN UPDATE TO 1.0.1 FROM 1.0.0
$mysql['upgrade_1-0-1']['update']['wtcBBoptions'] = "UPDATE wtcBBoptions SET version_text = 'wtcBB 1.0.1' , version_num = '1.0.1';";

$mysql['upgrade_1-0-1']['update']['templates_default'] = "UPDATE templates_default SET version = '1.0.1';";
// END UPGRADE TO 1.0.1 QUERIES


// BEGIN UPDATE TO 1.0.2 FROM 1.0.1
$mysql['upgrade_1-0-2']['update']['wtcBBoptions'] = "UPDATE wtcBBoptions SET version_text = 'wtcBB 1.0.2' , version_num = '1.0.2';";

$mysql['upgrade_1-0-2']['update']['templates_default'] = "UPDATE templates_default SET version = '1.0.2';";
// END UPGRADE TO 1.0.2 QUERIES


// BEGIN UPDATE TO 1.1.0 FROM 1.0.2
$mysql['upgrade_1-1-0']['create']['warn'] = "CREATE TABLE warn (
warnid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
userid MEDIUMINT,
typeid MEDIUMINT,
whoWarned MEDIUMINT,
note MEDIUMTEXT,
warnDate INT,
postid MEDIUMINT,
INDEX(userid), INDEX(typeid), INDEX(whoWarned), INDEX(postid)
);";

$mysql['upgrade_1-1-0']['create']['warn_type'] = "CREATE TABLE warn_type (
typeid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
name VARCHAR (255),
warnPoints MEDIUMINT
);";

$mysql['upgrade_1-1-0']['create']['guestbook'] = "CREATE TABLE guestbook (
bookid MEDIUMINT UNSIGNED AUTO_INCREMENT NOT NULL PRIMARY KEY,
ownUserid MEDIUMINT,
userid MEDIUMINT,
title VARCHAR (255),
ip_address VARCHAR (255),
date_posted INT,
show_sig TINYINT (1),
parse_smilies TINYINT (1),
parse_bbcode TINYINT (1),
defBBCode TINYINT (1),
deleted TINYINT (1),
edited_by VARCHAR (255),
edited_time INT,
edited_reason VARCHAR (255),
message MEDIUMTEXT,
INDEX(ownUserid), INDEX(userid), INDEX(deleted)
);";

$mysql['upgrade_1-1-0']['alter']['wtcBBoptions'] = "ALTER TABLE wtcBBoptions ADD (
topicReview TINYINT (1),
robots MEDIUMTEXT,
robots_desc MEDIUMTEXT,
defaultBBCode TINYINT (1),
defaultFontsList MEDIUMTEXT,
defaultColorsList MEDIUMTEXT,
defaultSizeList MEDIUMTEXT,
enableWarn TINYINT (1),
warnAutoBan MEDIUMINT,
autoBanGroup MEDIUMINT,
sendWarnNotify TINYINT (1),
enableGuestbook TINYINT (1),
guestbookPerPage MEDIUMINT,
guestbookNotify TINYINT (1)
);";

$mysql['upgrade_1-1-0']['alter']['user_info'] = "ALTER TABLE user_info ADD (
default_font VARCHAR (255),
default_color VARCHAR (255),
default_size VARCHAR (255),
useDefault TINYINT (1),
warn MEDIUMINT,
enableGuestbook TINYINT (1)
);";

$mysql['upgrade_1-1-0']['alter']['usergroups'] = "ALTER TABLE usergroups ADD (
can_default_bbcode TINYINT (1),
warn_others TINYINT (1),
warn_protected TINYINT (1),
warn_viewOwn TINYINT (1),
warn_viewOthers TINYINT (1),
book_hidden TINYINT (1),
book_viewOthers TINYINT (1),
book_viewOwn TINYINT (1),
book_addOthers TINYINT (1),
book_addOwn TINYINT (1),
book_editOwn TINYINT (1),
book_deleteOwn TINYINT (1),
book_permDeleteOwn TINYINT (1),
book_editOthers TINYINT (1),
book_deleteOthers TINYINT (1),
book_permDeleteOthers TINYINT (1)
);";

$mysql['upgrade_1-1-0']['alter']['posts'] = "ALTER TABLE posts ADD (
defBBCode TINYINT (1)
);";

$mysql['upgrade_1-1-0']['alter']['personal_msg'] = "ALTER TABLE personal_msg ADD (
defBBCode TINYINT (1)
);";

$mysql['upgrade_1-1-0']['alter']['admin_permissions'] = "ALTER TABLE admin_permissions ADD (
warn TINYINT (1)
);";

$mysql['upgrade_1-1-0']['update']['user_info'] = "UPDATE user_info SET warn = 0 , enableGuestbook = 1;";

$mysql['upgrade_1-1-0']['update']['usergroups0'] = "UPDATE usergroups SET can_default_bbcode = 1 , warn_others = 1 , warn_protected = 1 , warn_viewOwn = 1 , warn_viewOthers = 1 , book_hidden = 1 , book_viewOthers = 1 , book_viewOwn = 1 , book_addOthers = 1 , book_addOwn = 1 , book_editOwn = 1 , book_deleteOwn = 1 , book_deleteOwn = 1 , book_permDeleteOwn = 1 , book_editOthers = 1 , book_deleteOthers = 1 , book_permDeleteOthers = 1 WHERE usergroupid = 8;";

$mysql['upgrade_1-1-0']['update']['usergroups1'] = "UPDATE usergroups SET can_default_bbcode = 0 , warn_others = 0 , warn_protected = 0 , warn_viewOwn = 1 , warn_viewOthers = 0 , book_hidden = 0 , book_viewOthers = 0 , book_viewOwn = 0 , book_addOthers = 0 , book_addOwn = 0 , book_editOwn = 0 , book_deleteOwn = 0 , book_deleteOwn = 0 , book_permDeleteOwn = 0 , book_editOthers = 0 , book_deleteOthers = 0 , book_permDeleteOthers = 0 WHERE usergroupid = 5;";

$mysql['upgrade_1-1-0']['update']['usergroups2'] = "UPDATE usergroups SET can_default_bbcode = 1 , warn_others = 0 , warn_protected = 0 , warn_viewOwn = 1 , warn_viewOthers = 0 , book_hidden = 0 , book_viewOthers = 1 , book_viewOwn = 1 , book_addOthers = 0 , book_addOwn = 0 , book_editOwn = 0 , book_deleteOwn = 0 , book_deleteOwn = 0 , book_permDeleteOwn = 0 , book_editOthers = 0 , book_deleteOthers = 0 , book_permDeleteOthers = 0 WHERE usergroupid = 2;";

$mysql['upgrade_1-1-0']['update']['usergroups3'] = "UPDATE usergroups SET can_default_bbcode = 0 , warn_others = 0 , warn_protected = 0 , warn_viewOwn = 0 , warn_viewOthers = 0 , book_hidden = 0 , book_viewOthers = 1 , book_viewOwn = 0 , book_addOthers = 0 , book_addOwn = 0 , book_editOwn = 0 , book_deleteOwn = 0 , book_deleteOwn = 0 , book_permDeleteOwn = 0 , book_editOthers = 0 , book_deleteOthers = 0 , book_permDeleteOthers = 0 WHERE usergroupid = 1;";

$mysql['upgrade_1-1-0']['update']['usergroups4'] = "UPDATE usergroups SET can_default_bbcode = 1 , warn_others = 1 , warn_protected = 1 , warn_viewOwn = 1 , warn_viewOthers = 1 , book_hidden = 1 , book_viewOthers = 1 , book_viewOwn = 1 , book_addOthers = 1 , book_addOwn = 1 , book_editOwn = 1 , book_deleteOwn = 1 , book_deleteOwn = 1 , book_permDeleteOwn = 0 , book_editOthers = 1 , book_deleteOthers = 0 , book_permDeleteOthers = 0 WHERE usergroupid = 6;";

$mysql['upgrade_1-1-0']['update']['usergroups5'] = "UPDATE usergroups SET can_default_bbcode = 1 , warn_others = 0 , warn_protected = 0 , warn_viewOwn = 1 , warn_viewOthers = 0 , book_hidden = 1 , book_viewOthers = 1 , book_viewOwn = 1 , book_addOthers = 1 , book_addOwn = 1 , book_editOwn = 1 , book_deleteOwn = 1 , book_deleteOwn = 1 , book_permDeleteOwn = 0 , book_editOthers = 0 , book_deleteOthers = 0 , book_permDeleteOthers = 0 WHERE usergroupid = 4;";

$mysql['upgrade_1-1-0']['update']['usergroups6'] = "UPDATE usergroups SET can_default_bbcode = 1 , warn_others = 1 , warn_protected = 1 , warn_viewOwn = 1 , warn_viewOthers = 1 , book_hidden = 1 , book_viewOthers = 1 , book_viewOwn = 1 , book_addOthers = 1 , book_addOwn = 1 , book_editOwn = 1 , book_deleteOwn = 1 , book_deleteOwn = 1 , book_permDeleteOwn = 0 , book_editOthers = 1 , book_deleteOthers = 1 , book_permDeleteOthers = 0 WHERE usergroupid = 7;";

$mysql['upgrade_1-1-0']['update']['usergroups7'] = "UPDATE usergroups SET can_default_bbcode = 0 , warn_others = 0 , warn_protected = 0 , warn_viewOwn = 1 , warn_viewOthers = 0 , book_hidden = 0 , book_viewOthers = 1 , book_viewOwn = 1 , book_addOthers = 0 , book_addOwn = 0 , book_editOwn = 0 , book_deleteOwn = 0 , book_deleteOwn = 0 , book_permDeleteOwn = 0 , book_editOthers = 0 , book_deleteOthers = 0 , book_permDeleteOthers = 0 WHERE usergroupid = 5;";

$mysql['upgrade_1-1-0']['update']['posts'] = "UPDATE posts SET defBBCode = 0;";

$mysql['upgrade_1-1-0']['update']['personal_msg'] = "UPDATE personal_msg SET defBBCode = 0;";

$mysql['upgrade_1-1-0']['update']['admin_permissions'] = "UPDATE admin_permissions SET warn = 0;";

$mysql['upgrade_1-1-0']['update']['warn_type'] = "INSERT INTO warn_type (typeid, name, warnPoints) VALUES (1, 'Spamming', 1), (3, 'Flaming', 2), (4, 'Advertising', 1), (5, 'Excessive Ignorance For Rules', 5);";

$mysql['upgrade_1-1-0']['update']['wtcBBoptions'] = "UPDATE wtcBBoptions SET topicReview = 1 , robots = 'lycos\r\nask jeeves\r\ngooglebot\r\nslurp@inktomi\r\nfast-webcrawler\r\nyahoo\r\nmsnbot' , robots_desc = 'Lycos\r\nAsk Jeeves\r\nGoogle Bot\r\nInktomi\r\nAll The Web\r\nYahoo!\r\nMSN Bot' , defaultBBCode = 1 , defaultFontsList = 'verdana\r\narial\r\ntahoma\r\ncentury\r\ncomic sans ms\r\njester\r\ntrebuchet ms\r\ntimes new roman\r\nlucida sans\r\nteletype\r\ncourier new' , defaultColorsList = '#ff0000\r\n#0000ff\r\n#008000\r\n#800080\r\n#ffc0cb\r\n#000000\r\n#ffffff\r\n#ffff00\r\n#a52a2a\r\n#00ffff\r\n#ff00ff\r\n#4682b4\r\n#40e0d0\r\n#ffa500\r\n#ff4500\r\n#000080\r\n#32cd32\r\n#f08080\r\n#b22222\r\n#ffd700\r\n#c0c0c0\r\n#da70d6\r\n#cd5c5c\r\n#00ff00\r\n#4b0082\r\n#00285b' , defaultSizeList = '6\r\n8\r\n10\r\n12\r\n14\r\n16\r\n18\r\n22\r\n26\r\n32\r\n36' , enableWarn = 1 , warnAutoBan = 15 , autoBanGroup = 5 , sendWarnNotify = 1 , enableGuestbook = 1 , guestbookPerPage = 15 , guestbookNotify = 1 , version_text = 'wtcBB 1.1.0' , version_num = '1.1.0';";
// END UPGRADE TO 1.1.0 QUERIES


// BEGIN UPDATE TO 1.1.0 FROM 1.1.1
$mysql['upgrade_1-1-1']['update']['wtcBBoptions'] = "UPDATE wtcBBoptions SET version_text = 'wtcBB 1.1.1' , version_num = '1.1.1';";

$mysql['upgrade_1-1-1']['update']['templates_default'] = "UPDATE templates_default SET version = '1.1.1';";
// END UPGRADE TO 1.1.1 QUERIES


// BEGIN UPDATE TO 1.1.1 FROM 1.1.2
$mysql['upgrade_1-1-2']['alter']['styles'] = "ALTER TABLE styles ADD enabled TINYINT (1);";

$mysql['upgrade_1-1-2']['update']['styles'] = "UPDATE styles SET enabled = 1;";

$mysql['upgrade_1-1-2']['update']['wtcBBoptions'] = "UPDATE wtcBBoptions SET version_text = 'wtcBB 1.1.2' , version_num = '1.1.2';";

$mysql['upgrade_1-1-2']['update']['templates_default'] = "UPDATE templates_default SET version = '1.1.2';";
// END UPGRADE TO 1.1.2 QUERIES


// BEGIN UPDATE TO 1.1.2 FROM 1.1.3
$mysql['upgrade_1-1-3']['alter']['moderators'] = "ALTER TABLE moderators ADD recurse TINYINT (1);";

$mysql['upgrade_1-1-3']['update']['moderators'] = "UPDATE moderators SET recurse = 0;";

$mysql['upgrade_1-1-3']['update']['wtcBBoptions'] = "UPDATE wtcBBoptions SET version_text = 'wtcBB 1.1.3' , version_num = '1.1.3';";

$mysql['upgrade_1-1-3']['update']['templates_default'] = "UPDATE templates_default SET version = '1.1.3';";
// END UPGRADE TO 1.1.3 QUERIES


// BEGIN UPDATE TO 1.1.3 FROM 1.1.4
$mysql['upgrade_1-1-4']['update']['wtcBBoptions'] = "UPDATE wtcBBoptions SET version_text = 'wtcBB 1.1.4' , version_num = '1.1.4';";

$mysql['upgrade_1-1-4']['update']['templates_default'] = "UPDATE templates_default SET version = '1.1.4';";
// END UPGRADE TO 1.1.4 QUERIES


// BEGIN UPDATE TO 1.1.4 FROM 1.1.5
$mysql['upgrade_1-1-5']['update']['wtcBBoptions'] = "UPDATE wtcBBoptions SET version_text = 'wtcBB 1.1.5' , version_num = '1.1.5';";

$mysql['upgrade_1-1-5']['update']['templates_default'] = "UPDATE templates_default SET version = '1.1.5';";
// END UPGRADE TO 1.1.5 QUERIES


// BEGIN UPDATE TO 1.1.5 FROM 1.1.6
$mysql['upgrade_1-1-6']['update']['wtcBBoptions'] = "UPDATE wtcBBoptions SET version_text = 'wtcBB 1.1.6' , version_num = '1.1.6';";

$mysql['upgrade_1-1-6']['update']['templates_default'] = "UPDATE templates_default SET version = '1.1.6';";
// END UPGRADE TO 1.1.6 QUERIES

?>