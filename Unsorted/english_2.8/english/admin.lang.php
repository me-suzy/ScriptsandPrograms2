<?php
//***************************************************************************//
//                                                                           //
//  Program Name    	: vCard PRO                                          //
//  Program Version     : 2.8                                                //
//  Program Author      : Joao Kikuchi,  Belchior Foundry                    //
//  Home Page           : http://www.belchiorfoundry.com                     //
//  Retail Price        : $80.00 United States Dollars                       //
//  WebForum Price      : $00.00 Always 100% Free                            //
//  Supplied by         : South [WTN]                                        //
//  Nullified By        : CyKuH [WTN]                                        //
//  Distribution        : via WebForum, ForumRU and associated file dumps    //
//                                                                           //
//                (C) Copyright 2001-2002 Belchior Foundry                   //
//***************************************************************************//
/* ############################################### ADIM PAGES TEXTS ##################################################### */
/* You can leave the admin section in english to less translation work */

$admin_charset = 'iso-8859-1'; // Language ISO Char Set code to Admin Pages
	// text direction attribute is used to declare the direction that the text should run, either left to right (default) or right to left
$admin_htmldir = 'ltr'; // ltr = Left to Right , rtl = Right to Left

$settinggroup_title['language']='Language Settings';
$settinggroup_title['server']='Server Settings';
$settinggroup_title['general']='General Settings';
$settinggroup_title['admin']='Admin Control Painel';
$settinggroup_title['visual']='Visual Settings';
$settinggroup_title['gallery']='Gallery Settings';
$settinggroup_title['email']='E-mails Messages';
$settinggroup_title['user']='Users Options';
$settinggroup_title['spam'] = 'Anti-Spam Options';

$msg_admin_main_cardcontrol = 'Postcard Controls';
$msg_admin_main_del_read = 'have been read';
$msg_admin_main_del_noread = 'have not been read';
$msg_admin_main_del_alert = 'This option will delete all the postcards in your database that :';
$msg_admin_main_del_dayold = 'days old. ';
$msg_admin_main_del_criteria = 'cards will be deleted based on your selection criteria.';
$msg_admin_main_del_result = 'cards were deleted from the database.';

$msg_admin_main_onlypicked = 'Only cards that have been picked-up';
$msg_admin_main_onlynopicked = 'Only cards that have NOT been picked-up';
$msg_admin_main_note = 'This utility will delete postcards XX days after send date.';

$msg_admin_cat_image = 'Category Image URL';
$msg_admin_cat_assub = 'Subcat of';
$msg_admin_cat_main = 'Main Category';
$msg_admin_cat_sub = 'Sub Category';

$msg_admin_card_thmpath = 'thumbnail path';
$msg_admin_card_note = 'thumbnail field must be associated with template and/or post image.';

$msg_admin_login_alert = 'You need login to access the control panel!';
$msg_admin_login_button = 'Log In';
$msg_admin_login_username = 'Username';
$msg_admin_login_password = 'Password';

$msg_admin_extrainfo = 'Extra Info';
$msg_admin_yourversion = 'Your version';
$msg_admin_availableversion = 'Available version';
$msg_admin_webcommunity = 'Web Community';
$msg_admin_warning = 'Warning!';
$msg_admin_homepage = 'Home Page';
$msg_admin_controlpanel = 'Control Panel';
$msg_admin_main = 'Main';

$msg_admin_on = 'ON';
$msg_admin_off = 'OFF';
$msg_admin_path = 'path';
$msg_admin_entry = 'database entry';
$msg_admin_name = 'name';
$msg_admin_email = 'email';
$msg_admin_empty = 'empty';
$msg_admin_separator = 'delimeter';
$msg_admin_author = 'author';
$msg_admin_genre = 'genre';
$msg_admin_file = 'file';
$msg_admin_filepath = 'file path';
$msg_admin_filethm = 'thumbnail file';
$msg_admin_top = 'top';
$msg_admin_hits = 'hits';
$msg_admin_music = 'music';
$msg_admin_pattern = 'pattern';
$msg_admin_card = 'card';
$msg_admin_image = 'image';
$msg_admin_postcard = 'postcard';
$msg_admin_poem = 'poem';
$msg_admin_title = 'title';
$msg_admin_text = 'text';
$msg_admin_stamp = 'stamp';
$msg_admin_category = 'category';
$msg_admin_template = 'template';
$msg_admin_word = 'word';
$msg_admin_find = 'find';
$msg_admin_replace = 'replace';
$msg_admin_mutiple = 'multiple';
$msg_admin_include = 'include';
$msg_admin_all = 'all';
$msg_admin_keywords = 'keywords';
$msg_admin_thumbnail = 'thumbnail';
$msg_admin_caption = 'caption';
$msg_admin_event = 'event';
$msg_admin_relativepath = 'relative path';
$msg_admin_path = 'path';
$msg_admin_emaillog = 'email log';
$msg_admin_footercontent = 'footer content';
$msg_admin_headercontent = 'header content';

$msg_admin_linkforwarded = 'Click here to continue if you arent forwarded';

$msg_admin_yes = 'Yes';
$msg_admin_no = 'No';
$msg_admin_none = 'None';
$msg_admin_note = 'Note';

$msg_admin_day = 'day';
$msg_admin_month = 'month';
$msg_admin_calendar = 'calendar';

$msg_admin_start = 'start';
$msg_admin_end = 'end';

$msg_admin_play = 'play';
$msg_admin_days = 'days';
$msg_admin_stats = 'statistics';
$msg_admin_controlpanel = 'control panel';

$msg_admin_deleted = 'deleted';
$msg_admin_included = 'included';
$msg_admin_updated = 'updated';
$msg_admin_notfound = 'not found';
$msg_admin_notselected = 'No Selected';

$msg_admin_menu_add = 'add';
$msg_admin_menu_multiple = 'multiple';
$msg_admin_menu_edit = 'edit';
$msg_admin_menu_delete = 'delete';
$msg_admin_menu_upload = 'upload';
$msg_admin_menu_replace = 'replace';
$msg_admin_menu_show = 'show';
$msg_admin_menu_browse = 'browse';
$msg_admin_menu_view = 'view';
$msg_admin_menu_active = 'activate';
$msg_admin_menu_deactive = 'deactivate';
$msg_admin_menu_order = 'order';
$msg_admin_menu_options = 'options';
$msg_admin_menu_stats = 'statistics';
$msg_admin_menu_phpinfo = 'php info';
$msg_admin_menu_emaillog = 'email log';

$msg_admin_advancepostsend = 'Advance Post Send';

$msg_admin_op_confirm_question = 'Do you want continue with operation?';
$msg_admin_op_confirm_yes = 'Yes, Im sure!';
$msg_admin_op_confirm_no = 'No, I want back.';
$msg_admin_op_ok = 'operation sucess!';
$msg_admin_op_fail = 'operation fail!';

$msg_admin_reg_update = 'Update Registry';
$msg_admin_reg_delete = 'Delete Registry';
$msg_admin_reg_edit = 'Edit Registry';
$msg_admin_reg_add = 'Add Registry';
$msg_admin_reg_order = 'Order Registry';

$msg_admin_help_safemode = 'Using this feature requires your web server and PHP to have permission to write and move/delete files in web server (Safe Mode = off). If they do not have the neccessary permissions, it will fail.';
//$msg_admin_help_musicpath = 'Path to postcard music must be relative to your vcard music directory (ex.: friends/CoolKids.mid)';
$msg_admin_help_imagepath = 'Path to image must been relative to your vcard image directory (ex.: SomeSubDir/pic002.jpg)<br> OR the complete URL to image including http://xxxx/image.jpg';

$msg_admin_error_nocriteria = 'No cards will be deleted based on your selection criteria. Go back and select new criteria.';
$msg_admin_error_filenotfound = 'file not found!';
$msg_admin_error_upload = 'Did NO copy! Verify permission of your image directory OR read vCard documentation for more info!';
$msg_admin_error_card_assoc = 'Post Thumbnail File field cant be included WITHOUT being associated with a template or card image.';
$msg_admin_error_wrongext = 'Wrong Extension!';
$msg_admin_error_formempty = 'form field is empty!';
$msg_admin_error_attach = 'There has been an error with your attempt to upload the file';

$msg_admin_stat_title = 'statistics';
$msg_admin_stat_summary = 'summary';
$msg_admin_stat_totalcards = 'total cards available to users';
$msg_admin_stat_restart = 'restart statistics';
$msg_admin_stat_note = '1. If you like to restart the Statistics you are free to do so by pushing this button. Consider however that all your current statistics information will be deleted. In order to keep that information as a history file save your current reports to your own harddisk first or print this page.';
$msg_admin_stat_dbempty = 'stats database is empty';
$msg_admin_stat_dbnotempty = 'stats database not empty';

$msg_admin_operation_options = 'Which information do you want??';
$msg_admin_emillog_note = '1. If you like to restart the Email Log you are free to do so by pushing this button. Consider however that all your current information will be deleted. In order to keep that information as a history file save your current reports to your own harddisk first.';
$msg_admin_emillog_empty = 'empty email log';
$msg_admin_emillog_dbempty = 'email log database is empty';
$msg_admin_emillog_dbnotempty = 'email log database not empty';

$msg_admin_adv_result = ' postcard sent in this time.';

/* added in version 1.2 (9-july-2001) */

$msg_monthnames = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');

// style
$msg_admin_style_import = 'Style import';
$msg_admin_style_errorfile = 'Invalid style file!';
$msg_admin_style_errorver = '<p>The version of the style file does not match your version of vCard. Please obtain an updated version of vCard and/or the style file.';
$msg_admin_style_yourtplver = 'Your template version';
$msg_admin_style_yourstylever = 'Style file template version';
$msg_admin_style_note1 = 'Make sure that you upload any accompanying images too!';
$msg_admin_style_note2 = 'Please ensure that the vcard.style file exists in the current directory and then reload this current page.';
$msg_admin_style_note3 = 'Use style file even if it is for a different version of vCard';
$msg_admin_style_download = 'Download your style set';

$msg_admin_style_title = 'Import existing style set: (from local file)';
$msg_admin_stylefile = 'Style File';
$msg_admin_imported = 'imported';
$msg_admin_reindex = 'reindex';
$msg_admin_menu_style = 'style';
$msg_admin_menu_import = 'import';
$msg_admin_menu_download = 'download';
$msg_admin_menu_buildindex = 'build search index';
$msg_admin_menu_search = 'search';
$msg_admin_menu_template = 'template';
$msg_admin_menu_help = 'help';
$msg_admin_menu_manual = 'user manual';

$msg_admin_addressbook = 'Address Book';
$msg_admin_default = 'default settings';
$msg_admin_stat_regusers = 'total registered users';
$msg_admin_template_selectall = 'select all field content';
$msg_admin_search_reindex = 'database reindex';
$msg_admin_search_reindexnote = '1. the database reindex may take some time. But is a required action if you note some error in your search function or you are upgrading from previous version.';

$msg_admin_help_musicpath = 'Path to postcard music CAN be:<p> a relative to your vcard music directory (ex.: friends/CoolKids.mid)<br> <b>OR</b><br> a FULL file URL (ex.: http://www.somedomain.com/music/xyz/xyz.rm)';

// ####################### New text added in version 1.3 (20-aug-2001) ################# //
$msg_admin_users = 'users';
$msg_admin_username = 'username';
$msg_admin_password = 'password';
$msg_admin_superuser = 'super user';
$msg_admin_logout = 'logout';
$msg_admin_lvlacess_denied = 'You dont have access level to view this page.';

// updated
$msg_admin_help_multiple = ' If you choose the url path (nature) vCard will find an image file like (sunset_001.jpg). But there are so many possibilities that it is impossible to list here:<br> nature/thm/sunset_001.jpg<br> nature/t_sunset_001.jpg<br> nature/thm_sunset_001.jpg<br> nature/thm_sunset_001.gif<br> nature/sunset_001t.jpg<br> nature/sunset_001t.gif<br> nature/sunset_001small.jpg<br> etc.<br> <p>So vCard has a special syntax to help you.<br> You know that the real thumbnail name is: (nature/thm_sunset_001.gif). So you write this: [directory]/thm_[name].gif <p> [directory] = the path that you typed in form field<br> [name] = file name without extension<br> [fullname] = file name with extension <p> examples:<br> anyname/thm_001.gif - [directory]/thm_[name].gif<br> friends/thm/001s.gif - [directory]/thm/[name]s.gif<br> friends/001s.gif - [directory]/[name]s.gif<br> friends/001small.jpg - [directory]/[name]small.jpg<br>';

/* added in version 1.4 (19-oct-2001) */
$msg_admin_applet = 'java applet';
$msg_admin_height = 'height';
$msg_admin_width = 'width';

$msg_admin_cardsgroup = 'cards group';
$msg_admin_cardsgroup_notes = 'What is cards group? <br> Cards groups is a group permissions that you can set to some cards. Sometimes you want that some cards has a special settings that dont allow users use pattern, stamp, card color, font face options etc. but you dont want use these settings to ALL postcards in your site using control panel (options).<br> That the idea behind card group: create a group settings without use these rules to all postcards.';

$msg_admin_permission = 'permission';
$msg_admin_canusefeatures = 'allowed features';

$msg_admin_fontface = 'font face';
$msg_admin_fontcolor = 'font color';
$msg_admin_fontsize = 'font size';
$msg_admin_cardcolor = 'card color';
$msg_admin_background = 'background';
$msg_admin_advancedate = 'advance date';
$msg_admin_notify = 'notify';
$msg_admin_copy = 'card copy';
$msg_admin_layout = 'layout';
$msg_admin_heading = 'heading';
$msg_admin_signature = 'signature';
$msg_admin_cardsgroup_note = 'allow user access these options at creation page when postcard is included at this group card name.';

// stats
$msg_admin_stat_detailstats = 'show detailed stats by month, day or week';
$msg_admin_stat_sortbyday = 'Sort by Day';
$msg_admin_stat_sortbymonth = 'Sort by Month';
$msg_admin_stat_sortbyweek = 'Sort by Week';
$msg_admin_stat_chartnote = '1. ONLY IF you server has <b>the GD lib module</b> corrected installed with your PHP you are able too see this image! You can ask to your ISP install this module in your PHP server!<p>';

$msg_admin_stat_morestats = 'More Stats';
$msg_admin_next = 'next';
$msg_admin_previous = 'previous';
$msg_admin_week = 'week';
$msg_admin_day_array = array('Sunday','Monday','Tuesday','Wenesday','Thursday','Friday','Saturday');
$msg_admin_day2_array = array('Sun','Mon','Tue','Wen','Thur','Fri','Sat');

// updated
$msg_admin_stat_totalsent = 'total cards in stats database';

/* added in version 1.6 (10-dez-2001) */

$msg_admin_view = 'view';
$msg_admin_search_log = 'search log';
$msg_admin_search_viewlog = 'view log';
$msg_admin_search_term = 'term';
$msg_admin_search_restart = 'restart search log database';
$msg_admin_search_note_restart = 'If you like to restart the Search Log you are free to do so by pushing this button. Consider however that all your current information will be deleted. In order to keep that information as a history file save your current reports to your own harddisk first.';
$msg_admin_search_restart = 'restart search log';

$msg_admin_search_logbox = 'Select how many entries you want see most searched term from log';
$msg_admin_searchlog_dbempty = 'search log database empty';
$msg_admin_searchlog_dbnotempty = 'search log database no empty';
$msg_admin_operation_options2 = 'Download as TEXT file (.txt)';

/* added in version 2.0 */

$msg_button_copy = 'Copy';
$msg_button_find = 'Find';
$msg_button_preview = 'Preview';
$msg_button_next = 'Next';
$msg_button_previous = 'Prev';
$msg_button_back = 'Back';
$msg_button_confirm = 'Confirm';
$msg_button_update = 'Update';

$msg_admin_template_seeoriginal = 'see original';

$msg_admin_delete = 'delete';
$msg_admin_rating = 'rating';
$msg_admin_stat_external = 'external file';
$msg_admin_stat_totalentries = 'total entries';
$msg_admin_stat_ratingentries = 'rating entries';
$msg_admin_stat3_note = '1. If you like to restart the <b>Statistics of EXTERNAL FILES ONLY</b> you are free to do so by pushing this button. Consider however that all your current statistics information will be deleted. In order to keep that information as a history file save your current reports to your own harddisk first or print this page.';
$msg_admin_stat4_note = '1. If you like to restart the <b>Rating Sytem ONLY</b> you are free to do so by pushing this button. Consider however that all your current statistics information will be deleted. In order to keep that information as a history file save your current reports to your own harddisk first or print this page.';


$msg_admin_music = 'music';
$msg_admin_update = 'update';
$msg_admin_dbtools = 'database tools';
$msg_admin_optimize_db = 'optimize vCard database';
$msg_admin_optimize_note = '1. This tool is only to optimize MySQL (mysql 3.23 or greater) database because';
$msg_admin_sort_title = 'card sort';
$msg_admin_sort_rad = 'sort by Random';
$msg_admin_sort_caption = 'sort by caption';
$msg_admin_sort_dateasc = 'sort by date included - ascend';
$msg_admin_sort_datedesc = 'sort by date included - descend';
$msg_admin_sort_default = 'default (sytem default)';
$msg_admin_cache_update = 'Update vCard Cache';
$msg_admin_cache_note = '1. If you set vCard Cache = YES you will need update every time the vCard cache to reflect the changes that you make in your templates.';
// updated:
$msg_admin_help_imagewidth = 'If you leaving blank or 0 (zero) vCard will determine the size of any GIF, JPG, PNG, SWF image file and fill this value in data base for you.<br> Some java applet need that you specify the width and height, you can use the width and heigth to use with these applets.';


/* added version 2.2 */
$msg_admin_menu_activate = 'activate';
$msg_admin_menu_deactivate = 'deactivate';

/* added version 2.4 */
$msg_admin_poem_style = 'Did you include HTML code?';
$msg_admin_poem_note_style = '1. If NO vCard PRO will formate you text. If YES you can add your poem with HTML/Javascript code<br> 2. Some users are using this option into card creation page AS card effect replacing the text string <i>poem</i> for <i>card effect</i> in language files.';

/* added version 2.5 */
$msg_admin_stats_uploadimages = 'total uploaded images in data base';
$msg_admin_cat_linktext = 'Use text link to this category';

$msg_admin_dbbackup = 'database backup';
$msg_admin_menu_maintenance = 'maintenance';
$msg_admin_dbbkp_dobackup = 'Download Back-up file';
$msg_admin_dbbkp_serverfile = 'Save data to file on server';
$msg_admin_dbbkp_dbtoinclude = 'Database tables to include in backup';
$msg_admin_dbbkp_dumpviaweb = 'From here, you will download the MySQL dump file to your local computer';
$msg_admin_dbbkp_sucessfully = 'Data dumped sucessfully!';

/* added version 2.6 */
$setting_title['site_lang']  = 'Language Lib';
$setting_title['site_lang_special']  = 'Special Language';
$setting_title['site_prog_url']  = 'Program URL';
$setting_title['site_music_url']  = 'Music URL';
$setting_title['site_music_path']  = 'Music path';
$setting_title['site_image_url']  = 'Image URL';
$setting_title['site_image_path']  = 'Image path';
$setting_title['admin_email']  = 'Admin e-mail address';
$setting_title['site_name']  = 'Site Name';
$setting_title['site_url']  = 'Site URL';
$setting_title['site_dateformat']  = 'Date Format';
$setting_title['site_font_face']  = 'Site Font Face';
$setting_title['site_body_bgimage']  = 'Site background image';
$setting_title['site_body_bgcolor']  = 'Site background color';
$setting_title['site_body_text']  = 'Site text color';
$setting_title['site_body_link']  = 'Site link color';
$setting_title['site_body_vlink']  = 'Site visited link color';
$setting_title['site_body_alink']  = 'Site active link color';
$setting_title['site_body_marginwidth']  = 'Site margin width';
$setting_title['site_body_marginheight']  = 'Site margin height';
$setting_title['gallery_toplist_allow']  = 'Allow Top list in Gallery?';
$setting_title['gallery_thm_width']  = 'Thumbnail Width';
$setting_title['gallery_thm_height']  = 'Thumbnail Height';
$setting_title['gallery_toplist_value']  = 'Top Cards list';
$setting_title['gallery_table_width']  = 'Gallery table width';
$setting_title['gallery_table_cols']  = 'Gallery tables columns';
$setting_title['gallery_thm_per_page']  = 'Gallery images per page';
$setting_title['form_font_list']  = 'Form Face list option';
$setting_title['form_bgcolor']  = 'Form background color';
$setting_title['form_table_width']  = 'Form Table Width';
$setting_title['form_field_size']  = 'Form Field Size';
$setting_title['form_areatext_cols']  = 'Form Field Area Cols';
$setting_title['form_font_face']  = 'Form Font Face';
$setting_title['form_font_size']  = 'Form Font Size';
$setting_title['mail_recip_subject']  = 'Email Subject';
$setting_title['mail_recip_message']  = 'Email message';
$setting_title['mail_sender_subject']  = 'Notification Subject';
$setting_title['mail_sender_message']  = 'Notification message';
$setting_title['admin_cards_deletelist']  = 'List cards';
$setting_title['admin_stats_toplist']  = 'Statistics Top List';
$setting_title['admin_gallery_cols']  = 'Gallery Columns';
$setting_title['admin_gallery_thm_per_page']  = 'Gallery Images per page';
$setting_title['user_upload_allow']  = 'Upload File';
$setting_title['user_upload_maxsize']  = 'Max Size of Upload file';
$setting_title['user_stamp_allow']  = 'Stamp';
$setting_title['user_stamp_default']  = 'Default Stamp Image';
$setting_title['user_notify_allow']  = 'Notification service';
$setting_title['user_pattern_allow']  = 'Background image';
$setting_title['user_music_allow']  = 'Background music';
$setting_title['antispam_allow_entries']  = 'Maximum number of cards per hour';
$setting_title['antispam_policy']  = 'Anti-Spam Policy';
$setting_title['vcachesys']  = 'vCard Cache System';
$setting_title['vcachereflesh']  = 'Cache Reflesh';
$setting_title['form_font_color']  = 'Form text color';
$setting_title['vcardversion']  = 'vcard version';
$setting_title['online_users']  = 'Online Users';
$setting_title['vcardactive']  = 'vCard Active';
$setting_title['site_new_days']  = 'New Card Icon';
$setting_title['user_rating_allow']  = 'Rating system';
$setting_title['antispam_check']  = 'Enable checking for card spamming?';
$setting_title['mail_copy_message']  = 'Card copy message';
$setting_title['mail_copy_subject']  = 'Postcard copy subject';
$setting_title['gallery_random']  = 'Show random postcard box';
$setting_title['mail_format']  = 'E-mail format';
$setting_title['mail_emailfriend_message']  = 'Refer a friend message';
$setting_title['mail_emailfriend_subject']  = 'Refer to friend';
$setting_title['mail_abpwd_message']  = 'Password retrieve message';
$setting_title['mail_abpwd_subject']  = 'Password retrieve subject';
$setting_title['site_timeoffset']  = 'Time Zone Offset';
$setting_title['gallery_newlist_value']  = 'Newest Card List';
$setting_title['user_poem_allow']  = 'Poem';
$setting_title['user_flash_height']  = 'Flash Height';
$setting_title['user_flash_width']  = 'Flash Width';
$setting_title['user_template_only']  = 'Use only this template';
$setting_title['user_multirecip_allow']  = 'Multiple Recipients';
$setting_title['user_advance_range']  = 'How long Advance date';
$setting_title['user_advance_allow']  = 'Advance send';

$setting_description['site_lang'] = 'Determines what language lib file that you want use.';
$setting_description['site_lang_special'] = 'Set <b>Yes</b> if you will use a special translated language (i.e. non-romanic char set). Some languages will make a error in creation page because the MySQL will order pattern and music names! Maybe your language wont be understood by MySQL. See more info in vCard documentation.';
$setting_description['site_prog_url'] = 'Full URL where scripts is placed *WITHOUT trailing slash*';
$setting_description['site_music_url'] = 'Full URL to your music dir *WITHOUT trailing slash*';
$setting_description['site_music_path'] = 'Full PATH to your music dir *WITHOUT trailing slash*';
$setting_description['site_image_url'] = 'Full URL to your image dir *WITHOUT trailing slash*';
$setting_description['site_image_path'] = 'Full PATH to your image dir *WITHOUT trailing slash*';
$setting_description['admin_email'] = 'Administrator e-mail address';
$setting_description['site_name'] = 'Card Service Name';
$setting_description['site_url'] = 'Full URL to Card Service Main Page';
$setting_description['site_dateformat'] = 'Date Format!';
$setting_description['site_font_face'] = 'You can use font combo example (Verdana, Arial, Helvetica)';
$setting_description['site_body_bgimage'] = '';
$setting_description['site_body_bgcolor'] = '';
$setting_description['site_body_text'] = '';
$setting_description['site_body_link'] = '';
$setting_description['site_body_vlink'] = '';
$setting_description['site_body_alink'] = '';
$setting_description['site_body_marginwidth'] = 'Left and right site margin size';
$setting_description['site_body_marginheight'] = 'Top and botton site margin size';
$setting_description['gallery_toplist_allow'] = 'Display Top postcard list of categories?';
$setting_description['gallery_thm_width'] = 'Width size of Thumb image. Leave blank if you want full thumbnaill size';
$setting_description['gallery_thm_height'] = 'Height size of Thumb image. Leave blank if you want full thumbnaill size';
$setting_description['user_pattern_allow'] = 'Allow users to choose a pattern/wallpaper/background image for greeting card?';
$setting_description['user_music_allow'] = 'Allow users to choose background music?';
$setting_description['user_notify_allow'] = 'Allow users to choose to be notified when card is retrieved?';
$setting_description['user_stamp_default'] = 'Set a default stamp image here if no stamp image is selected by user. If you dont want any stamp <i>leave blank</i>';
$setting_description['user_stamp_allow'] = 'Allow users choose a different stamp?';
$setting_description['user_upload_maxsize'] = '40 = 40Kbytes. Value is in Kbytes';
$setting_description['user_upload_allow'] = 'Allow user upload his your image/flash file to create  postcard?';
$setting_description['admin_gallery_thm_per_page'] = 'Number of images per page in admin gallery';
$setting_description['admin_gallery_cols'] = 'Number of coluns in admin gallery';
$setting_description['admin_stats_toplist'] = 'TOP XX of stat page';
$setting_description['admin_cards_deletelist'] = 'Want a list of cards that will be deleted in admin page? If your postcard service has a lots of activity I counsel you to set it to <i>N</i>';
$setting_description['mail_sender_message'] = 'Message of sender notification email.<blockquote>These special tags is used by vCard to replace this tags for correct content sending by email:</dd><br><i>{recipient_name}</i> = Recipients name<br><i>{sender_name}</i>    = Senders name<br><i>{pickup_url}</i>     = The pickup url that has been sent to recipient<br><i>{today_date}</i>     = Today Date, useful to notify e-mail<br><i>{user_ip}</i> = The postcard retriever IP address<br><i>{id_code}</i> = the ID code to retrieve the postcard<br></blockquote>';
$setting_description['mail_sender_subject'] = 'Subject text of sender notification email about postcard retrieving.';
$setting_description['mail_recip_message'] = 'Message of recipient email.<blockquote>These special tags is used by vCard to replace this tags for correct content sending by email:</dd><br><i>{recipient_name}</i> = Recipients name<br><i>{sender_name}</i>    = Senders name<br><i>{pickup_link}</i>     = The pickup url to recipients retrieve postcard<br><i>{user_ip}</i> = The sender IP address<br><i>{id_code}</i> = the ID code to retrieve the postcard<br></blockquote>';
$setting_description['mail_recip_subject'] = 'Subject text of recipients notification email.';
$setting_description['form_font_size'] = 'Font size of form in creation page';
$setting_description['form_font_face'] = 'If you want set diferent font face, you can leave blank or use Font Combo,( i.e., Verdana,Arial,Helvetica)';
$setting_description['form_areatext_cols'] = 'Its good to edit if you use a special language';
$setting_description['form_field_size'] = 'Its good to edit if you use a special language';
$setting_description['form_table_width'] = 'In pixel or percent';
$setting_description['form_bgcolor'] = 'Creation page form background color';
$setting_description['form_font_list'] = 'Font face list avaible in user creation page';
$setting_description['gallery_thm_per_page'] = 'Number of images per page';
$setting_description['gallery_table_cols'] = 'Number of Coluns in Gallery browser mode';
$setting_description['gallery_table_width'] = 'width of table in pixel or percent.';
$setting_description['gallery_toplist_value'] = 'TOP XX of main user page';
$setting_description['user_advance_allow'] = 'Allow users to choose to send postcard on advanced date? <p>You may have access to CRON (shell unix account) or use a url monitoring. See documentation.';
$setting_description['user_advance_range'] = 'Range of days that you want allow user choose? Formate is XX days.';
$setting_description['user_multirecip_allow'] = 'Allow users to use Multi Recipients Cards?';
$setting_description['user_template_only'] = 'If you want use only ONE template. Set the default template name, if you dont want this feature, leave blank to offer the 3 default layout templates.';
$setting_description['user_flash_width'] = 'Default width size of flash animation';
$setting_description['user_flash_height'] = 'Default height size of flash animation';
$setting_description['user_poem_allow'] = 'Allow users to choose a poem to postcard?';
$setting_description['gallery_newlist_value'] = 'Total number of cards that you want list';
$setting_description['site_timeoffset'] = 'Time (in hours) that the server is offset from GMT. Please select the most appropriate option.';
$setting_description['mail_abpwd_subject'] = 'Email subject text to delivery the password to email address. ';
$setting_description['mail_abpwd_message'] = 'Message text to delivery password. <blockquote>These special tags is used by vCard to replace this tags for correct content sending by email:<br> <i>{abook_realname}</i> = Addressbook owner name<br><i>{abook_email}</i> = Addressbook owner email<br><i>{abook_username}</i> = Addressbook username to login<br><i>{abook_password}</i> = Addressbook password to login<br><i>{today_date}</i> = Today Date<br><i>{user_ip}</i> = The requester IP address<br></blockquote>';
$setting_description['mail_emailfriend_subject'] = 'The default email subject to refer function';
$setting_description['mail_emailfriend_message'] = 'Default message text to refer a friend function <blockquote> These special tags is used by vCard to replace the data from form fields for correct content sending by email:<br><i>{sender_name}</i> = sender name<br><i>{sender_email}</i> = sender e-mail address<br><i>{recipient_name}</i> = recipient name<br><i>{recipient_email}</i> = recipient e-mail address<br><i>{user_message}</i> = the custom message from user <br><i>{site_url}</i> = Site URL address <br><i>{today_date}</i> = Today Date <br><i>{user_ip}</i> = The sender IP address<br></blockquote>';
$setting_description['mail_format'] = 'Select th e-mail format (HTML or PLAIN text) to all e-mail messages.';
$setting_description['gallery_random'] = 'Do you want show a box with postcards sorted randomly displayed at categories?';
$setting_description['mail_copy_subject'] = 'The email subject to card copy that will send to card sender';
$setting_description['mail_copy_message'] = 'Default mail message send to sender about card copy <blockquote> These special tags is used by vCard to replace the data from form fields for correct content sending by email:<br><i>{sender_name}</i> = sender name<br> <i>{sender_email}</i> = sender email<br> <i>{pickup_url}</i> = card pickup URL to sender (a card is created only to sender because we dont want the retrieve notification be useless)<br><i>{recipients_names}</i> = recipient(s) name(s) that sender entered<br><i>{recipients_mails}</i> = recipient(s) e-mail(s) address that sender entered<br><i>{site_url}</i> = Site URL address <br><i>{delivery_date}</i> = Delivery date<br><i>{user_ip}</i> = The sender IP address<br><i>{id_code}</i> = the ID code to retrieve the postcard<br></blockquote>';
$setting_description['vcardactive'] = 'From time to time, you may want to turn your vcard off to the public while you perform maintenance, update versions, etc. When you turn your vCard off, visitors will receive a message that states that the vCard is temporarily unavailable. <br>The page with unavailable message can be found in templates > unavailable page.';
$setting_description['site_new_days'] = 'How much time do you want that new card icon to be visible to end-user? Setting in days.';
$setting_description['user_rating_allow'] = 'Allow rating system available to end-user?';
$setting_description['antispam_check'] = 'You may prevent your end-users use your greeting card site to send spam by activating this feature. By enabling spamcheck, you disallow users from send more than x amount cards you set. In other words, if you set a spamcheck entries of 20 cards, a user may not send more than 20 cards/hour.';
$setting_description['antispam_allow_entries'] = 'The maximum number of cards that you want to allow user to send in a hour. Used to prevent spammer abuse';
$setting_description['antispam_policy'] = 'Type your anti-spam policy here. This text will be displayed when the same user/IP send more than allowed number of cards that you set .';
$setting_description['vcachesys'] = 'Every time the vCard page is loaded there are many MySQL queries in the statistic database to rank postcard popularity and it can make system be slow if you have a big site (big statistic database) because the ranking is made in real-time. If you want, you set YES this ranking can be made 1 time per day (set 1440 minutes) and the result is saved in database to easy and fast loading.';
$setting_description['vcachereflesh'] = 'Time period in minutes to refresh the vcard cache system if it is active. ';
$setting_description['form_font_color'] = 'Creation page form: text color.';
$setting_description['vcardversion'] = 'vcard version';
$setting_description['online_users'] = 'Do you want know online users feature?';

$msg_admin_user_alreayusername = 'This username already has been taken. Try other username.';
$msg_admin_user_error01 = 'All accounts cannot be extinguished. At least one should exist!';

// v 2.7
// Some $setting_description variables WERE updated.
$msg_admin_active = 'active';

// v 2.8 by CyKuH [WTN]
$msg_admin_nullification['nv'] = 'Note Info';
$msg_admin_nullification['program_name'] = 'Program Name';
$msg_admin_nullification['program_version'] = 'Program Version';
$msg_admin_nullification['program_author'] = 'Program Author ';
$msg_admin_nullification['home_page'] = 'Home Page';
$msg_admin_nullification['retail_price'] = 'Retail Price';
$msg_admin_nullification['webForum_price'] = 'WebForum Price ';
$msg_admin_nullification['xcgi_price'] = 'xCGI Price';
$msg_admin_nullification['cgihaven_price'] = 'CGIHEAVEN Price';
$msg_admin_nullification['forumru_price'] = 'ForumRU Price  ';
$msg_admin_nullification['supplied_by'] = 'Supplied by ';
$msg_admin_nullification['nullified_by'] = 'Nullified by';
$msg_admin_nullification['distribution'] = 'Distribution';
$msg_admin_nullification['protection'] = 'Protection';
$msg_admin_nullification['language'] = 'Language';
$msg_admin_nullification['ss'] = 'Script Status';
$msg_admin_userinfo['User_Info'] = 'User Information';
$msg_admin_userinfo['Status'] = 'Status';
$msg_admin_userinfo['User'] = 'User';
$msg_admin_userinfo['IP'] = 'IP';
$msg_admin_userinfo['Login_Time'] = 'Login Time';
$msg_admin_userlevel['Super_User'] = 'Super User';
$msg_admin_userlevel['User'] = 'User';
$msg_admin_stats['Chart_Presentation'] = 'Chart Presentation';
$msg_admin_stats['Fact_Presentation'] = 'Fact Presentation';
$msg_admin_stats['Report_Presentation'] = 'Report Presentation';
$msg_admin_sysinfo['Information'] = 'Information'; 
$msg_admin_sysinfo['System_Information'] = 'System Information'; 
$msg_admin_sysinfo['Product'] = 'Product';
$msg_admin_sysinfo['Platform'] = 'Platform';
$msg_admin_sysinfo['PHP_Version'] = 'PHP Version';
$msg_admin_sysinfo['MySQL_Vesion'] = 'MySQL Version';
$msg_admin_sysinfo['Loaded_PHP_Modules'] = 'Loaded PHP Modules';
$msg_admin_sysinfo['MySQL_Host'] = 'MySQL Host';
$msg_admin_sysinfo['MySQL_Database'] = 'MySQL Database';
$msg_admin_sysinfo['Software_Version'] = 'Software Version';
$msg_admin_sysinfo['Software_Name'] = 'Software Name';

$msg_admin_form['Explanations'] = 'Explanations';
$msg_admin_download['EOL'] = 'EOL - End of Line Character';

$msg_admin_replace_sys['Note'] = 'vCard PRO build the end-user page in real-time. If you want replace some <b>string</b> for <b>other</b> you can add/edit here';

$msg_admin_checkuser['User_Card_Check'] = 'User Card Check';
$msg_admin_checkuser['Find_Postcard'] = 'Find Postcard';
$msg_admin_checkuser['Find_For'] = 'Find For';
$msg_admin_checkuser['Refine'] = 'Refine';
$msg_admin_checkuser['Result_Limit'] = 'Result Limit';
$msg_admin_checkuser['Check_If_Retrieved'] = 'Check if card has been retrieved';
$msg_admin_checkuser['Recipient_Name'] = 'Recipient´s name';
$msg_admin_checkuser['Recipient_Email'] = 'Recipient´s e-mail';
$msg_admin_checkuser['Sender_Name'] = 'Sender´s name';
$msg_admin_checkuser['Sender_Email'] = 'Sender´s e-mail';
$msg_admin_checkuser['Card_ID'] = 'Card ID';
$msg_admin_checkuser['Date'] = 'Date';
$msg_admin_checkuser['Readed'] = 'Readed';
$msg_admin_checkuser['Sent'] = 'Sent';
$msg_admin_checkuser['Note'] = 'Sometimes an end-user want know some info about his postcard. To find more info about some card, please fill the fields with the information available';
$msg_admin_checkuser['Cards_Not_Found'] = 'Cards Not Found';
$msg_admin_dboptimize['Optimize_DB'] = 'Optimize Database';
$msg_admin_dboptimize['Notes'] = '1. By clicking "Start Optimization" button, vCard will start to repair and optimize ALL current database tables. This will reduce the space used by database tables and speed-up the database operations.<br>2. We strong suggest you to turn off vCard site because the MySQL tables is locked during the time process is running<br>3. We suggest you to optimize your vCard with this section periodicaly.';
$msg_admin_dboptimize['Start_Optimization'] = 'Start Optimization';
$msg_admin_dboptimize['MySQL_Error'] = 'Your MySQL version dont accept Database command REPAIR / OPTIMIZE. Sorry.';
$setting_title['gallery_toplist_usetotal']  = 'Use only total top list?';
$setting_description['gallery_toplist_usetotal'] = 'Select yes if you only use TOTAL top list type into main page to better server performance. There are two top list, 1st: total top list and 2nd: week top list';
?>