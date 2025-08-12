<?php
//English

//the above line is needed so that pivot knows how to display it in the user info.
//it also needs to be on the 2rd line.

// English translation of Pivot lang file
// Created by Dustgun (dustgun@sardonica.net)
// Last updated by Pivot Dev. (www.pivotlog.net) 25.04.2005

// allow for different encoding for non-western languages
$encoding="iso-8859-1";
$langname="en";


//		General		\\
$lang['general'] = array (
	'yes' => 'Yes',	//affirmative
	'no' => 'No',		//negative
	'go' => 'Go!',	//proceed

	'minlevel' => 'You don\'t have the permission to access this area of Pivot',
	'email' => 'Email',
	'url' => 'URL',
	'further_options' => "Further Options",
	'basic_view' => "Basic View",
	'basic_view_desc' => "View only the most common fields",
	'extended_view' => "Extended View",
	'extended_view_desc' => "View all the editable fields",
	'select' => "Select",
	'cancel' => "Cancel",
	'delete' => 'Delete',
	'welcome' => "Welcome to %build%.",
	'write' => "Write",
	'write_open_error' => "Write Error. Could not open file for writing",
	'write_write_error' => "Write Error. Could not write to file",
	'done' => "Done!",
	'shortcuts' => "Shortcuts",
	'cantdelete' => "You are not allowed to delete entry %title%!",
	'cantdothat' => "You are not allowed to do that with entry %title%!",
	'cantdeletelast' => "You can not delete the last entry. You must first post a new entry, before deleting this entry",
);


$lang['userlevels'] = array (
		'Superadmin', 'Administrator', 'Advanced', 'Normal', 'Moblogger'
		//  this one might be a bit hard to translate, but basically it's an order of
		//  power or trust.  Superadmin would be the person in charge - no one can do
		//  anything about his decisions. Admin is only regulated by the Superadmin,
		//  Advanced by the Admin and Superadmin, etc..
		//  Just get the idea of it.
);


$lang['numbers'] = array (
	'zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen'
);


$lang['months'] = array (
	'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
);


$lang['months_abbr'] = array (
	'Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
);


$lang['days'] = array (
	'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
);


$lang['days_abbr'] = array (
	'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'
);


$lang['days_calendar'] = array (
	'S', 'M', 'T', 'W', 'T', 'F', 'S'
);


$lang['datetime_words'] = array (
	'Year', 'Month', 'Week', 'Day', 'Hour', 'Minute', 'Second'	//the actual words for them.
);


//		Login Page		\\
$lang['login'] = array (
	'title' => 'Login',
	'name' => 'Username',
	'pass' => 'Password',
	'remember' => 'Remember',
	'rchoice' => array (
		'0' => 'Nothing',
		'1' => 'My Username and Password',
		'2' => 'That I want to stay logged in'
	),
	'delete_cookies_desc' => 'If you are certain you\'re using the correct username and password, but you are <br />having problems logging in, you might try deleting the cookies for this domain:',
	'delete_cookies' => 'Delete cookies',
	'retry' => 'Incorrect username/password',
	'banned' => 'You have failed to gain access in 10 attempts. As a result, this IP has been banned from attempting to login for 12 hours.',

);


//		Main Bar		\\
	$lang['userbar'] = array (
	'main' => 'Overview',
	'entries' => 'Entries',
	'submit' => 'New Entry',
	'comments' => 'Comments',
	'trackbacks' => 'Trackbacks',
	'modify' => 'Modify Entries',
	'userinfo' => 'My Info',
	'u_settings' => 'My Settings',
	'u_marklet' => 'Bookmarklets',
	'files' => 'Manage Media',
	'upload' => 'Upload',
	'stats' => 'Statistics',
	'admin' => 'Administration',

	'main_title' => 'Global Overview of Pivot',
	'entries_title' => 'Overview of Entries',
	'submit_title' => 'Write and Publish a new Entry',
	'comments_title' => 'Edit or Delete Comments',
	'trackbacks_title' => 'Edit or Delete Trackbacks',
	'modify_title' => 'Modify Entry',
	'userinfo_title' => 'View my personal Info',
	'u_settings_title' => 'Edit my personal Settings',
	'u_marklet_title' => 'Create Bookmarklets',
	'files_title' => 'Manage and Upload Media',
	'upload_title' => 'Upload Files',
	'uploaded_success' => 'File has been uploaded',
	'stats_title' => 'View Logs and Statistics.',
	'updatetitles_title' => 'View Logs and Statistics.',
	'admin_title' => 'Overview of Administration',
	'recent_entries' => 'Recent Entries',
	'recent_comments' => 'Recent Comments',
);


$lang['adminbar'] = array (
	//		Admin Bar		\\
	//'trebuild' => 'Rebuild all Files', rolled into maintenance
	'seeusers' => 'Users',
	'seeconfig' => 'Configuration',
	'filemappings' => 'File Mappings',
	'templates' => 'Templates',
	'maintenance' => 'Maintenance',
	'regen' => 'Rebuild All Files',
	'blogs' => 'Weblogs',
	'categories' => 'Categories',
	'verifydb' => 'Verify the Database',
	'buildindex' => 'Rebuild the Index',
	'buildsearchindex' => 'Rebuild the Search Index',
	'buildfrontpage' => 'Rebuild the Frontpage(s)',
	'sendping' => 'Send Pings',


	'backup' => 'Backup',
	'description' => 'Description',
	'conversion' => 'Conversion',
	'seeusers_title' => 'Create, edit and delete Users',
	'userfields' => 'User Information Fields',
	'userfields_title' => 'Create, edit and delete User Information Fields',
	'seeconfig_title' => 'Edit the Configuration file',
	'filemappings_title' => 'Show and overview of which files in your site are created and by the weblogs in this Pivot',
	'templates_title' => 'Create, edit and delete Templates',
	'maintenance_title' => 'Perform routine maintenance on Pivot\'s files',
	'regen_title' => 'Rebuild the Files and Archives that Pivot generates',
	'blogs_title' => 'Create, edit and delete Weblogs',
	'blogs_edit_title' => 'Edit Weblog Settings For ',
	'categories_title' => 'Create, edit and delete the Categories',
	'verifydb_title' => 'Verify the integrity of your Database',
	'buildindex_title' => 'Rebuild the index of your Database',
	'buildsearchindex_title' => 'Rebuild the Searchindex, to allow searching in entries',
	'buildfrontpage_title' => 'Rebuild the frontpage, last archives and RSS files for each of the weblogs.',
	'backup_title' => 'Create a Backup of your Entries',
	'backup_config' => 'Backup of configuration files',
	'backup_config_desc' => 'This will let you download a zip file containing your configuration files',
	'ipblocks' => 'IP blocks',
	'ipblocks_title' => 'View and Edit the blocked IP addresses.',
	'ipblocks_stored' => 'The IP-addresses have been stored.',
	'ipblocks_store' => 'Store these IP-addresses',
	'ignoreddomains' => 'Blocked Phrases',
	'ignoreddomains_title' => 'View and Edit the Blocked Phrases to combat referrer spam.',
	'ignoreddomains_stored' => 'The Blocked Phrases have been stored.',
	'ignoreddomains_store' => 'Store these Blocked Phrases',
	'ignoreddomains_asterisk' => 'Items marked with an asterisk ( * ) will be used only to block referers. All other items will be used to block referers, comments and trackbacks alike.',
	'fileexplore' => 'File Explorer',
	'fileexplore_title' => 'View files (both text and database files)',
	'sendping_title' => 'Send Pings to Update Trackers.',
	'buildindex_start' => 'Now building Index. This may take a short while, so please do not interrupt.',
	'buildsearchindex_start' => 'Now building Search Index. This may take a short while, so please do not interrupt.',
	'buildindex_finished' => 'Finished! Generating index took %num% seconds',

	'filemappings_desc' => 'Below you can see an overview of each weblog in this Pivot installation, together with which files are created by Pivot and what templates it uses to create these files. This can also be very useful when pinpointing trouble with the creation of files.',

	'debug' => 'Open Debug window',

	'latest_pivot_news' => "The Latest Pivot News",
	'remove_setup_header' => "Pivot installer script present",
	'remove_setup' => "The Pivot installer script 'pivot-setup.php' is still present in the parent folder. You should be aware that this is a potential security risk. We advise you to remove it, or to rename it, so that it can't be executed by people with bad intentions.",

);


$lang['templates'] = array (
	'rollback' => 'Rollback',
	'create_template' => 'Create template',
	'create_template_info' => 'Create a Pivot template from scratch',
	'no_comment' => 'No Comment',
	'comment' => 'Comment*',
	'comment_note' => '(*Note: Comments can only be saved at <b>first</b> save of changes or creation)',
	'create' => 'Create template',
	'editing' => 'Editing',
	'filename' => 'Filename',
	'save_changes' => 'Save changes!',
	'save_template' => 'Save template!',
	'aux_template' => 'Auxillary template',
	'sub_template' => 'Subtemplate',
	'standard_template' => 'Normal template',
	'feed_template' => 'Feed template',
	'css_template' => 'CSS file',
	'txt_template' => 'Text file',
	'php_template' => 'PHP file',
);


//		Admin			\\
// bob notes: Mark made these, i think they should be replaced by the 'adminbar']['xxx_title'] ones
$lang['admin'] = array (
	'seeusers' => 'Create, edit and delete users',
	'seeconfig' => 'Edit the configuration file',
	'templates' => 'Create, edit and delete templates',
	'maintenance' => 'Perform routine maintenance on Pivot\'s files, like \'Rebuild Files\', \'Verify the Database\', \'Rebuild the Index\' and \'Backup\'.',
	'regen' => 'Rebuild all the pages that pivot generates',
	'blogs' => 'Create, edit and delete the different Weblogs Pivot will publish to',
);


//		Maintenace		\\
$lang['maint'] = array (
	'title' => 'Maintenance',
	'gen_arc_title' => 'Generate Archive', /* bob notes: redundant, see 'regen' */
	'gen_arc_text' => 'Regenerate all of your archives', /* bob notes: redundant, see 'regen' */
	'xml_title' => 'Verify XML Files', /* bob notes: replace with more general 'Verify DB' */
	'xml_text' => 'Verify (and repair if needed) the intergrity of the XML files', /* bob notes: replace with more general 'Verify DB' */
	'backup_title' => 'Backup',
	'backup_text' => 'Create a backup of all pivot\'s essential files',
);


//		Stats and referers		\\
$lang['stats'] = array (
	'show_last' => "Show the last",
	'20ref' => "20 referrers",
	'50ref' => "50 referrers",
	'allref' => "all referrers",
	'showunblocked' => "only non-blocked lines",
	'showall' => "both blocked and non-blocked lines",
	'updateref' => "Update the referer to title mappings",
	'hostaddress' => 'Host-address (ip-address)',
	'whichpage' => 'Which page',

	'getting' => 'Getting new titles',
	'awhile' => 'This could take a while, please don\'t interrupt.',
	'firstpass' => 'First pass',
	'secondpass' => 'Second pass',
	'nowuptodate' => 'Your referer-title mappings are now up to date.',
	'finished' => 'Finished',
);


//		User Info		\\
	$lang['userinfo'] = array (
	'editfields' => 'Edit User Fields',
	'desc_editfields' => 'Edit the fields that users can use to describe themselves',
	'username' => 'Username',
	'pass1' => 'Password',
	'pass2' => 'Password (confirm)',
	'email' => 'Email',
	'nickname' => 'Nickname',
	'userlevel' => 'Userlevel',
	'userlevel_desc' => 'Userlevel will determine what kind of actions this user can perfom in pivot.',
	'language' => 'Language',
	'lastlogin' => 'Last Login',
	'edituser' => 'Edit User',  //the link to.. well, edit the user (also the title)
	'edituserinfo' => 'Edit User Info',
	'selfreg' => 'Self-registration',
	'newuser' => 'Create New User',
	'desc_newuser' => 'Create a new login account for Pivot, allowing them to post to a weblog.',
	'newuser_button' => 'Create',
	'edituser_button' => 'Change',
	'pass_too_short' => 'Password must be at least 4 letters long.',
	'pass_equal_name' => 'Password can\'t be the same as username.',
	'pass_dont_match' => 'Passwords do not match',
	'username_in_use' => 'Username already in use',
	'username_too_short' => 'Name needs to be 3 letters or longer',
	'username_not_valid' => 'Usernames can only contain alphanumeric characters (A-Z, 0-9) and underscores (_).',
	'not_good_email' => 'That\'s not a valid email address',
	'c_admin_title' => 'Confirm creating an administator',
	'c_admin_message' => 'An '.$lang['userlevels']['1'].' has full access to pivot, can edit all news items, all comments and change anysetting. Are you sure you\'d like to make %s an '.$lang['userlevels']['1'].'?',
);


//		Config Page		\\
	$lang['config'] = array (
	'save' => 'Save Settings',

	'sitename' => 'Site Name',
	'defaultlanguage' => 'Default language',
	'defaultencoding' => 'Use encoding',
	'defaultencoding_desc' => 'This defines the encoding that is used (like utf-8 or iso-8859-1). You should leave this blank, unless you know what you\'re doing. If you leave this blank it will use the appropriate settings from the language files.',
	'defaulttheme' => 'Theme',
	'selfreg' => 'Allow self-registration',
	'selfreg_desc' => 'Setting this to yes enables people to register as (normal) users and hence post entries. (This is not a "comment" user.)',
	'siteurl' => 'Site URL',
	'header_fileinfo' => 'File Info',
	'localpath' => 'Local Path',
	'debug_options' => 'Debug Options',
	'debug' => 'Debug mode',
	'debug_desc' => 'Show random debug information, every here and there..',
	'log' => 'Logfiles',
	'log_desc' => 'Keep logfiles of various activities',

	'unlink' => 'Unlink Files',
	'unlink_desc' => 'Some instances of servers on which the ghastly safe_mode is enabled, might require playing with this option. On most servers this option will not have any effect',
	'chmod' => 'Chmod Files To',
	'chmod_desc' => 'Some servers require that created files are chmodded in a specific way. Common values are \'0644\' and \'0755\'. Do not change this, unless you know what you\'re doing.',
	'header_uploads' => 'File Upload settings',
	'upload_path' => 'File Upload Path',
	'upload_accept' => 'Accepted Types',
	'upload_extension' => 'Default Extension',
	'upload_save_mode' => 'Overwrite',
	'make_safe' => 'Clean Filename',
	'c_upload_save_mode' => 'Increment Filename',
	'max_filesize' => 'Maximum Filesize',
	'header_datetime' => 'Date/Time',
	'timeoffset_unit' => 'Time Offset Unit',
	'timeoffset' => 'Time Offset',
	'header_extra' => 'Various Settings',
	'wysiwyg' => 'Use Wysiwyg editor',
	'wysiwyg_desc' => 'Determines whether the Wysiwyg editor is on by default. Individual users can change this in their \'My Info\' settings.',
	'basic_view' => 'Use Basic View',
	'basic_view_desc' => 'Determines whether the \'New Entry\' opens in Basic View or in Extended View.',
	'def_text_processing' => 'Default Text Processing',
	'text_processing' => 'Text Processing',
	'text_processing_desc' => 'Determines the default text processing, when a user is using the non-wysiwyg editor. \'Convert Linebreaks\' does nothing more than change linebreaks to a &lt;br&gt;-tag. <a href="http://www.textism.com/tools/textile/" target="_blank">Textile</a> is a powerful, yet easy to learn markup style.',
	'none' => 'None',
	'convert_br' => 'Convert Linebreaks to &lt;br /&gt;',
	'textile' => 'Textile',
	'markdown' => 'Markdown',
	'markdown_smartypants' => 'Markdown and Smartypants',

	'allowed_cats' => 'Allowed Categories',
	'allowed_cats_desc' => 'This user is allowed to post entries in the selected categories',
	'delete_user' => "Delete user",
	'delete_user_desc' => "You can delete this user if you would like. All of their posts will remain, but they will no longer be able to login",
	'delete_user_confirm' => 'You\'re about to remove access for %s. Are you sure you want to do this?',

	'setup_ping' => 'Ping Setup',
	'ping_use' => 'Ping update trackers',
	'ping_use_desc' => 'This determines whether update trackers like weblogs.com will be automatically notified by Pivot if you post a new entry. Services like blogrolling.com depend on these pings',
	'ping_urls' => 'URLs to ping',
	'ping_urls_desc' => 'You can provide several urls to send pings to. Do not include the http:// part, otherwise it won\'t work. Just place each server on a new line, or seperated by a pipe character. Some common servers to ping are:<br /><b>rpc.weblogs.com/RPC2</b> (weblogs.com pinger, the one most widely used)<br /><b>pivotlog.net/pinger</b> (pivotlog pinger, not yet operational)<br /><b>rcs.datashed.net/RPC2</b> (euro.weblogs.com pinger)<br /><b>ping.blo.gs</b> (blo.gs pinger)<br />',

	'setup_tb' => 'Trackback Setup',
	'tb_email' => 'Email',
	'tb_email_desc' => 'If set, an email will be sent to this address when a Tracback is added.',

	'new_window' => 'Open links in new window',
	'emoticons' => 'Use emoticons',
	'javascript_email' => 'Encode Email Address?',
	'new_window_desc' => 'Determines if all links used in entries will open links in a new browser window.',

	'mod_rewrite' => 'Use Mod_rewrite',
	'mod_rewrite_desc' => 'If you use Apache\'s Mod_rewrite option, Pivot will make urls like www.mysite.com/archive/2003/05/30/nice_weather, instead of www.mysite.com/pivot/entry.php?id=134. Not all servers support this, so please read the section in the manual about this.',
	'mod_rewrite_1' => 'Yes, like /archive/2005/04/28/title_of_entry',
	'mod_rewrite_2' => 'Yes, like /archive/2005-04-28/title_of_entry',
	'mod_rewrite_3' => 'Yes, like /entry/1234',
	'mod_rewrite_4' => 'Yes, like /entry/1234/title_of_entry',

	'search_index' => 'Autoupdate Search Index',
	'search_index_desc' => 'This will determine if the Search Index files will be updated every time you submit a new entry, or you change an existing one.',

	'default_allow_comments' => 'Allow comments by default',
	'default_allow_comments_desc' => 'Determine whether entries are set to allow comments or not.',

  'maxhrefs' => 'Number of links',
  'maxhrefs_desc' => 'Maximum number of hyperlinks in allowed in comments. Useful to get rid of those pesky comment spammers. Set to 0 for unlimited links.',
  'rebuild_threshold' => 'Rebuild Threshold',
  'rebuild_threshold_desc' => 'The number of seconds rebuilding takes, before Pivot refreshes the page. The default is 28, but if you are having problems with rebuilding, try lowering this number to 10.',
	'default_introduction' => 'Default Introduction/Body',
	'default_introduction_desc' => 'This will determine the default values for Introduction and Body when an author writes a new entry. Normally this will be an empty paragraph, which makes the most sense semantically.',

	'upload_autothumb'	=> 'Automatic Thumbnails',
	'upload_thumb_width' => 'Thumbnail width',
	'upload_thumb_height' => 'Thumbnail height',
	'upload_thumb_remote' => 'Remote cropping script',
	'upload_thumb_remote_desc' => 'If your server does not have the necessary libraries installed to perform image cropping, you can use a remote cropping script.',

	'extensions_header' => 'Extensions directory',
	'extensions_desc'   => 'The \'extensions\' directory is the place to store your additions to Pivot.
		This makes updating a lot easier. See the Docs for more info.',
	'extensions_path'   => 'Extensions directory path',

);


//		Weblog Config	\\
$lang['weblog_config'] = array (
	'edit_weblog' => 'Edit Weblog',
	'edit_blog' => 'Edit Weblogs',
	'new_weblog' => 'New Weblog',
	'new_weblog_desc' => 'Add a new Weblog',
	'del_weblog' => 'Delete Weblog',
	'del_this_weblog' => 'Delete this weblog.',
	'create_new' => 'Create New Weblog',
	'subw_heading' => 'For each of the subweblogs that were found in the templates, you can configure what template they use, as well as what categories are published in them',
	'create' => 'Finish',

	'create_1' => 'Create / Edit Weblog, step 1 of 3',
	'create_2' => 'Create / Edit Weblog, step 2 of 3',
	'create_3' => 'Create / Edit Weblog, step 3 of 3',

	'name' => 'Weblog Name',
	'payoff' => 'Payoff',
	'payoff_desc' => 'The Payoff can be used as a subtitle or a short description of your weblog',
	'url' => 'URL to Weblog',
	'url_desc' => 'Pivot will determine the url of your weblog if you leave this field blank. If you use your weblog as part of a frameset, or as a serverside include, you can use this to override it.',
	'index_name' => 'Front page (Index)',
	'index_name_desc' => 'The filename of the index-file. Normally this is something like \'index.html\' or \'index.php\'.',

	'ssi_prefix' => 'SSI Prefix',
	'ssi_prefix_desc' => 'If your weblog uses SSI (which is not recommended), you can use this to prefix Pivot\'s filenames with the filename used for the SSI. eg. \'index.shtml?p=\'. You should just leave this blank, unless you know what you\'re doing.',

	'front_path' => 'Frontpage Path',
	'front_path_desc' => 'The relative or absolute path to the directory where Pivot will create the frontpage to this blog.',
	'file_format' => 'Filename',
	'entry_heading' => 'Entry Settings',
	'entry_path' => 'Entries Path',
	'entry_path_desc' => 'The relative or absolute path to the directory where Pivot will create the single entry pages (if you choose not to use \'live entries\')',
	'live_comments' => 'Live entries',
	'live_comments_desc' => 'If you use \'Live entries\', Pivot will not need to generate files for every single entry. This is the preferred setting.',
	'readmore' => '\'Read More\' Text',
	'readmore_desc' => 'The text that is used to indicate that there is more text in this entry than is shown on the front page. If you leave this blank, Pivot will use the default as defined by the language settings',

	'arc_heading' => 'Archive Settings',
	'arc_index' => 'Index File',
	'arc_path' => 'Archive Path',
	'archive_amount' => 'Archive Amount',
	'archive_unit' => 'Archive type',
	'archive_format' => 'Archive Format',
	'archive_none' => 'No Archives',
	'archive_weekly' => 'Weekly Archives',
	'archive_monthly' => 'Monthly Archives',
	'archive_yearly' => 'Yearly Archives',

	'archive_link' => 'Archive Link',
	'archive_linkfile' => 'Archive List format',
	'archive_order' => 'Archive Order',
	'archive_ascending' => 'Ascending (oldest first)',
	'archive_descending' => 'Descending (newest first)',

	'templates_heading' => 'Templates',
	'frontpage_template' => 'Frontpage Template',
	'frontpage_template_desc' => 'The Template which determines the layout of the index page of this weblog.',
	'archivepage_template' => 'Archivepage Template',
	'archivepage_template_desc' => 'The Template which determines the layout of your archives. This can be the same as \'Frontpage Template\'.',
	'entrypage_template' => 'Entrypage Template',
	'entrypage_template_desc' => 'The Template which determines the layout of single entries.',
	'extrapage_template' => 'Extra Template',
	'extrapage_template_desc' => 'The Template that defines what your archive and search.php will look like.',

	'shortentry_template' => 'Shortentry Template',
	'shortentry_template_desc' => 'The Template which determines the layout of single entries, as they are shown within the weblog or archives.',
	'num_entries' => 'Number of Entries',
	'num_entries_desc' => 'The Number of entries in this subweblog that will be shown on the frontpage.',
	'offset' => 'Offset',
	'offset_desc' => 'If Offset is set to a number, that amount of entries will be skipped when generating the page. You can use this to make a \'Previous entries\' list, for example.',
	'comments' => 'Allow Comments?',
	'comments_desc' => 'Determines whether users will be able to leave comments on the entries in this subweblog.',

	'publish_cats' => 'Publish these categories',

	'setup_rss_head' => 'RSS and Atom Configuration',
	'rss_use' => 'Generate Feeds',
	'rss_use_desc' => 'This determines whether or not pivot will automatically generate an RSS and an Atom feed for this weblog.',
	'rss_filename' => 'RSS Filename',
	'atom_filename' => 'Atom Filename',
	'rss_path' => 'Feed Path',
	'rss_path_desc' => 'The relative or absolute path to the directory where Pivot will create the Feed files.',
//	'rss_size' => 'Feed Entry Length',	/* DEPRECATED */
//	'rss_size_desc' => 'The length (in characters) of an entry in the Feed files', /* DEPRECATED */
	'rss_full' => 'Create Full Feeds',
	'rss_full_desc' => 'Determines whether Pivot creates full Atom and RSS feeds. If set to \'no\' Pivot will create feeds that just contains short descriptions, thereby making your feeds less useful.',
	'rss_link' => 'Feed Link',
	'rss_link_desc' => 'The link to send with the Feed, to point to the main page. If you leave this blank, Pivot will send the weblog\'s index as link.',
	'rss_img' => 'Feed Image',
	'rss_img_desc' => 'You can specify an image to send with the Feed. Some feed readers will display this image along with your feed. Leave this blank, or specify a full URL.',

	'lastcomm_head' => 'Settings for Last Comments',
	'lastcomm_amount' => 'Show how many',
	'lastcomm_length' => 'Cut at length',
	'lastcomm_format' => 'Format',
	'lastcomm_format_desc' => 'These settings determine the appearance of the \'last comments\' on the weblog\'s frontpage.',
	'lastcomm_nofollow' => 'Use \'Nofollow\'',
	'lastcomm_nofollow_desc' => 'To combat refererspam you might choose to add a rel="nofollow" attribute to all links in comments and referers, as this will not help the spammer get a better pagerank in Google.',

	'lastref_head' => 'Settings for Last Referers',
	'lastref_amount' => 'Show how many',
	'lastref_length' => 'Cut at length',
	'lastref_format' => 'Format',
	'lastref_format_desc' => 'These settings determine the appearance of the \'last referers\' on the weblog\'s frontpage.',
	'lastref_graphic' => 'Use graphics',
	'lastref_graphic_desc' => 'This determines if the last referers use small icons for the most common search engines through which visitors may arrive.',
	'lastref_redirect' => 'Redirect Referers',
	'lastref_redirect_desc' => 'To combat refererspam you might choose to redirect outgoing links to referers, as this will not help the spammer get a better pagerank in Google.',

	'various_head' => 'Various Settings',
	'emoticons' => 'Use Emoticons',
	'emoticons_desc' => 'Determines whether emoticons like :-) will be transformed to their graphical equivalents.',
	'encode_email_addresses' => 'Encode Email Addresses',
	'encode_email_addresses_desc' => 'Determines whether email-addresses will be encoded in javascript, as protection from spam harvesters.',
	'target_blank' => 'Target Blank',
	'xhtml_workaround' => 'XHTML Workaround',
	'target_blank_desc' => 'If set to \'Yes\', all links entered in your entries will be opened in a new browser window. If set to \'XHTML workaround\', all links will have a rel="external" attribute, which will not break wellformed XHTML',

	'date_head' => 'Date display settings',
	'full_date' => 'Full date format',
	'full_date_desc' => 'This determines the format for the full date and time. Most often used at the top of a single entry page',
	'entry_date' => 'Entry Date',
	'diff_date' => 'Diff Date',
	'diff_date_desc' => 'The \'Diff Date\' is most commonly used in conjunction with the \'Entry Date\'. The Entry Date is displayed on every entry on your log, while the Diff Date is only displayed if the date differs from the previous entry.',
	'language' => 'Language',
	'language_desc' => 'The Language determines in what language the dates and numbers will be output, and also determines the page\'s charset encoding (like iso-8859-1 or koi8-r, for example).',

	'comment_head' => 'Commenting Settings',
	'comment_sendmail' => 'Send Mail?',
	'comment_sendmail_desc' => 'After a comment has been placed, mail can be sent to maintainers of this weblog.',
	'comment_emailto' => 'Mail to',
	'comment_emailto_desc' => 'Specify the email address(es) to whom mail will be sent. seperate multiple addresses with a comma.',
	'comment_texttolinks' => 'Text to links',
	'comment_texttolinks_desc' => 'Define whether typed urls and email addresses will be made clickable.',
	'comment_wrap' => 'Wrap comments after',
	'comment_wrap_desc' => 'To prevent long strings of characters from breaking your layout, text will be wrapped after the specified number of characters.',
	'comments_text_0' => 'label for \'no comments\'',
	'comments_text_1' => 'label for \'one comment\'',
	'comments_text_2' => 'label for \'X comments\'',
	'comments_text_2_desc' => 'The text that is used to indicate how many comments there are. If you leave this blank, Pivot will use the default as defined by the language settings',

	'comment_pop' => 'Comments Popup?',
	'comment_pop_desc' => 'determines whether the comments page (or \'single entry\') will be shown in a popup window, or in the original browser window.',
	'comment_width' => 'Width of Popup',
	'comment_height' => 'Height of Popup',
	'comment_height_desc' => 'Specify the width and height (in pixels) of the comments pop-up.',

	'comment_format' => "Format of Comments",
	'comment_format_desc' => "This specifies the formatting of comments on the entry pages.",

	'comment_reply' => "Format of 'reply ..'",
	'comment_reply_desc' => "This determines the formatting of the link that visitors can use to reply on a specific comment.",
	'comment_forward' => "Format of 'reply by ..'",
	'comment_forward_desc' => "This determines the formatting of the text that is displayed when the comment is replied by another comment.",
	'comment_backward' => "Format of 'reply on ..'",
	'comment_backward_desc' => "This determines the formatting of the text that is displayed when the comment is a reply on another comment.",

	'comment_textile' => 'Allow Textile',
	'comment_textile_desc' => 'If this is set to \'Yes\', visitors are allowed to use <a href="http://www.textism.com/tools/textile/" target="_blank">Textile</a> in their comments.',
	'save_comment' => 'Store Comment',
	'comment_gravatardefault' => 'Default Gravatar',
	'comment_gravatardefault_desc' => 'URL to the default Gravatar image. Start with http://',
	'comment_gravatarhtml' => 'Gravatar HTML',
	'comment_gravatarhtml_desc' => 'HTML to insert for a gravatar. %img% will be substituted by the url to the image.',
	'comment_gravatarsize' => 'Gravatar size',
	'comment_gravatarsize_desc' => 'Size (in pixels) of the gravatar. The default is 48.',

    'trackback_head' => 'Trackback Settings',
	'trackback_sendmail' => 'Send Mail?',
	'trackback_sendmail_desc' => 'After a trackback has been placed, mail can be sent to maintainers of this weblog.',
	'trackback_emailto' => 'Mail to',
	'trackback_emailto_desc' => 'Specify the email address(es) to whom mail will be sent. Seperate multiple addresses with a comma.',
	'trackbacks_text_0' => 'Label for \'no trackbacks\'',
	'trackbacks_text_1' => 'Label for \'one trackback\'',
	'trackbacks_text_2' => 'Label for \'X trackbacks\'',
	'trackbacks_text_2_desc' => 'The text that is used to indicate how many trackbacks there are. If you leave this blank, Pivot will use the default as defined by the language settings',
	'trackback_pop' => 'Trackbacks Popup?',
	'trackback_pop_desc' => 'determines whether the trackbacks page (or \'single entry\') will be shown in a popup window, or in the original browser window.',
	'trackback_width' => 'Width of Popup',
	'trackback_height' => 'Height of Popup',
	'trackback_height_desc' => 'Specify the width and height (in pixels) of the trackbacks pop-up.',
	'trackback_format' => "Format of Trackbacks",
	'trackback_format_desc' => "This specifies the formatting of trackbacks on the entry pages.",
	'trackback_link_format' => "Format of Trackback Link",
        'save_trackback' => 'Store Trackback',

	'saved_create' => 'The new weblog has been created.',
	'saved_update' => 'The weblog has been updated.',
	'deleted' => 'The weblog has been deleted.',
	'confirm_delete' => 'You are about to delete weblog %1. Are you sure?',

	'blogroll_heading' => 'Blogroll settings',
	'blogroll_id' => 'Blogrolling ID #',
	'blogroll_id_desc' => 'You can optionally include a <a href="http://www.blogrolling.com" target="_blank">blogrolling.com</a> blogroll in your weblog. Blogrolling is an excellent service to maintain a list of links, which shows how recently they were updated. If you do not want this, just skip skip these input field. Otherwise: When you\'re logged in to blogrolling.com, go to \'get code\', there you will find links containing your blogroll\'s ID #. It should look somthing like this: 2ef8b42161020d87223d42ae18191f6d',
	'blogroll_fg' => 'Text Color',
	'blogroll_bg' => 'Background Color',
	'blogroll_line1' => 'Line Color 1',
	'blogroll_line2' => 'Line Color 2',
	'blogroll_c1' => 'Color 1',
	'blogroll_c2' => 'Color 2',
	'blogroll_c3' => 'Color 3',
	'blogroll_c4' => 'Color 4',
	'blogroll_c4_desc' => 'These colors determine what your blogroll will look like. Color 1 to color 4 give a visual indication as to how recently updated a link is.',
);


$lang['upload'] = array (
	//		File Upload		\\
	'preview' => 'Complete List Preview',
	'thumbs' => 'Thumbnail Preview',
	'create_thumb' => '(Create Thumbnail)',
	'title' => 'Files',
	'thisfile' => 'Upload a New file:',
	'button' => 'Upload',
	'filename' => 'Filename',
	'thumbnail' => 'Thumbnail',
	'date' => 'Date',
	'filesize' => 'Filesize',
	'dimensions' => 'Width x Height',
	'delete_title' => 'Delete Image',
	'areyousure' => 'Are you sure you want to delete the file %s?',
	'picheader' => 'Delete this picture?',
	'create' => 'create',
	'edit' => 'edit',

	'insert_image' => 'Insert an Image',
	'insert_image_desc' => 'To insert an image, you should upload an image, or select a previously uploaded image.',
	'insert_image_popup' => 'Insert an Image Popup',
	'insert_image_popup_desc' => 'To make a popup to an image, you should upload an image, or select a previously uploaded image. Then select a text or a thumbnail that triggers the popup.',
	'choose_upload' => 'upload',
	'choose_select' => 'or select',
	'imagename' => 'Image name',
	'alt_text' => 'Alternate text',
	'align' => 'Align',
	'border' => 'Border',
	'pixels' => 'pixels',
	'uploaded_as' => 'Your file has been uploaded as \'%s\'.',
	'not_uploaded' => 'Your file was not uploaded, and the following errors occured:',
	'center' => 'Center (default)',
	'left' => 'Left',
	'right' => 'Right',
	'inline' => 'Inline',
	'notice_upload_first' => 'You should first select or upload an image',
	'select_image' => 'Select Image',
	'select_file' => 'Select File',

	'for_popup' => 'For popup',
	'use_thumbnail' => 'Use Thumbnail',
	'edit_thumbnail' => 'edit thumbnail',
	'use_text' => 'Use text',
	'insert_download' => 'Insert a Download',
	'insert_download_desc' => 'To make a file download, you should upload a file, or select a previously uploaded file. Then select whether you want an icon or a text or a thumbnail that triggers the download.',
	'use_icon' => 'Use icon',
);


$lang['link'] = array (
	//		Link Insertion \\
	'insert_link' => 'Insert a link',
	'insert_link_desc' => 'Insert a link, by typing an url in the field below. Visitors to your site will see the title when they hover their mouse over the link.',
	'url' => 'URL',
	'title' => 'Title',
	'text' => 'Text',
);


//		Categories		\\
$lang['category'] = array (
	'edit_who' => 'Edit who can post to the category \'%s\'',
	'name' => 'Name',
	'users' => 'Users',
	'make_new' => 'Create New Category',
	'create' => 'Create Category',
	'canpost' => 'Select the Users that you would like to give permission to post in this category',
	'same_name' => 'A category by this name already exists',
	'need_name' => 'This category needs a name',

	'allowed' => 'Allowed',
	'allow' => 'Allow',
	'denied' => 'Denied',
	'deny' => 'Deny',
	'edit' => 'Edit Category',

	'delete' => 'Delete Category',
	'delete_desc' => 'Select \'yes\', if you wish to delete this category',

	'delete_message' => 'In this build of Pivot, only the category name will be deleted. In later builds, you will be able to choose what to do with entries using this category.',
	'search_index_newctitle'   => 'Index this category',
	'search_index_newcdesc'    => 'Only set to \'No\' if you do not want visitors to your site to search in this category.',
	'search_index_editcheader' => 'Index Category',

	'order' => 'Sorting Order',
	'order_desc' => 'Categories with a lower sorting order will appear higher in the list. If you keep all the numbers the same, they will be sorted alphabetically',
	'public' => 'Public Category',
	'public_desc' => 'If set to \'No\', this category will only be viewable for registered visitors. (applies only to live pages)',
	'hidden' => 'Hidden Category',
	'hidden_desc' => 'If set to \'Yes\', this category will be hidden in archive listings. (applies only to live pages)',

);


$lang['entries'] = array (
	//		Entries			\\
	'post_entry' => "Post Entry",
	'preview_entry' => "Preview Entry",

	'first' => 'first',
	'last' => 'last',
	'next' => 'next',
	'previous' => 'previous',

	'jumptopage' => 'jump to page (%num%)',
	'filteron' => 'filter on (%name%)',
	'filteroff' => 'filter off',
	'title' => 'Title',
	'subtitle' => 'Subtitle',
	'introduction' => 'Introduction',
	'body' => 'Body',
	'publish_on' => 'Publish on',
	'status' => 'Status',
	'post_status' => 'Post Status',
	'category' => 'Category',
	'select_multi_cats' => '(Ctrl-click to select multiple categories)',
	'last_edited' => "Last edited on",
	'created_on' => "Created on",
	'date' => 'Date',
	'author' => 'Author',
	'code' => 'Code',
	'comm' => '# Comm',
	'track' => '# Track',
	'name' => 'Name',
	'allow_comments' => 'Allow Comments',

	'delete_entry' => "Delete Entry",
	'delete_entry_desc' => "Delete this Entry and the corresponding Comments ",
	'delete_one_confirm' => "Are you sure you want to delete this entry?",
	'delete_multiple_confirm' => "Are you sure you want to delete these entries?",

	'convert_lb' => 'Convert Linebreaks',
	'always_off' => '(This is always off, when in Wysiwyg mode)',
	'be_careful' => '(Be careful with this!)',
	'edit_comments' => 'Edit Comments',
	'edit_comments_desc' => 'Edit the comments that have been posted to this entry',
	'edit_comment' => 'Edit Comment',
	'delete_comment' => 'Delete Comment',
	'edit_trackback' => 'Edit Trackback',
	'delete_trackback' => 'Delete Trackback',
	'block_single' => 'Block IP %s',
	'block_range' => 'Block IP range %s',
	'unblock_single' => 'Unblock IP %s',
	'unblock_range' => 'Unblock IP range %s',
	'trackback' => 'Trackback ping',
	'trackback_desc' => 'Send Trackback Pings to the following url(s). To send to multiple urls, place each one on a seperate line.',
	'keywords' => 'Keywords',
	'keywords_desc' => 'Use this to set some keywords that can be used to find this entry, or to set the non-crufty url for this entry.',
	'vialink' => "Via link",
	'viatitle' => "Via title",
	'via_desc' => 'Use this to set a link to the source of this entry.',
	'entry_catnopost' => 'You are not allowed to post in category:\'%s\'.',
	'entry_saved_ok' => 'Your entry \'%s\' was successfully saved.',
	'entry_ping_sent' => 'A trackback ping has been sent to \'%s\'.',
);


//		Form Fun		\\
$lang['forms'] = array (
	'c_all' => 'Check All',
	'c_none' => 'Uncheck All',
	'choose' => '- select an option -',
	'publish' => 'Set Status to \'publish\'',
	'hold' => 'Set Status to \'hold\'',
	'delete' => 'Delete them',
	'generate' => 'Publish and generate',

	'with_checked_entries' => "With the checked entries, do:",
	'with_checked_files' => "With the checked files, do:",
	'with_checked_templates' => 'With the checked templates, do:',
);


//		Errors			\\
$lang['error'] = array (
	'path_open' => 'cannot open dir, check your rights.',
	'path_read' => 'cannot read dir, check your rights.',
	'path_write' => 'cannot write dir, check your rights.',

	'file_open' => 'cannot open file, check your rights.',
	'file_read' => 'cannot read file, check your rights.',
	'file_write' => 'cannot write file, check your rights.',
);


//		Notices			\\
$lang['notice'] = array (
	'comment_saved' => "The Comment has been saved.",
	'comment_deleted' => "The Comment has been deleted.",
	'comment_none' => "This entry has no comments.",
	'trackback_saved' => "The Trackback has been saved.",
	'trackback_deleted' => "The Trackback has been deleted.",
	'trackback_none' => "This entry has no trackbacks.",
);


// Comments, Karma and voting \\
$lang['karma'] = array (
	'vote' => 'Vote \'%val%\' on this entry',
	'good' => 'Good',
	'bad' => 'Bad',
	'already' => 'You already voted for this entry or poll',
	'register' => 'Your vote for \'%val%\' has been registered',
);


$lang['comment'] = array (
	'register' => 'Your comment has been stored.',
	'preview' => 'You are previewing your comment. Be sure to click on \'Post Comment\' to store it.',
	'duplicate' => 'Your comment has not been stored, because it seems to be a duplicate of a previous entry',
	'no_name' => 'You should type your name (or an alias) in the \'name\'-field. Be sure to click on \'Post Comment\' to store it permanently.',
	'no_comment' => 'You should type something in the \'comment\'-field. Be sure to click on \'Post Comment\' to store it permanently.',
	'too_many_hrefs' => 'The maximum number of hyperlinks was exceeded. Stop spamming.',
    'email_subject' => '[Comment] Re:',
);


$lang['comments_text'] = array (
	'0' => "No comments",
	'1' => "%num% comment",
	'2' => "%num% comments",
);

$lang['trackbacks_text'] = array (
	'0' => "No trackback",
	'1' => "%num% trackback",
	'2' => "%num% trackback",
);

$lang['weblog_text'] = array (
	// these are used in the weblogs, for the labels related to archives
	'archives' => "Archives",
	'next_archive' => "Next Archive",
	'previous_archive' => "Previous Archive",
	'last_comments' => "Last Comments",
	'last_referrers' => "Last Referrers",
	'calendar' => "Calendar",
	'links' => "Links",
	'xml_feed' => "XML Feed (RSS 1.0)",
	'atom_feed' => "XML: Atom Feed",
	'powered_by' => "Powered by",
	'blog_name' => "Weblog Name",
	'title' => "Title",
	'excerpt' => "Excerpt",
	'name' => "Name",
	'email' => "Email",
	'url' => "URL",
	'date' => "Date",
	'comment' => "Comment",
	'ip' => "IP-address",
	'yes' => "Yes",
	'no' => "No",
	'emoticons' => "Emoticons",
	'emoticons_reference' => "Open Emoticons Reference",
	'textile' => "Textile",
	'textile_reference' => "Open Textile Reference",
	'post_comment' => "Post Comment",
	'preview_comment' => "Preview Comment",
	'remember_info' => "Remember personal info?",
	'notify' => "Notify",
	'notify_yes' => "Yes, send me email when someone replies.",
	'register' => "Register your username / Log in",
	'disclaimer' => "<b>Small print:</b> All html tags except &lt;b&gt; and &lt;i&gt; will be removed from your comment. You can make links by just typing the url or mail-address.",
	'search_title' => "Search Results",
	'search' => "Search!",
	'nomatches' => "No matches found for '%name%'. Try something else.",
	'matches' => "Matches for '%name%':",
	'about' => "About",
	'stuff' => "Stuff",
	'linkdump' => "Linkdump",
	'discreet' => "Hide email",
	'discreet_yes' => "Yes, hide my email address.",
);


$lang['ufield_main'] = array (
	//		Userfields		\\
	'title' => 'Edit Userfields',
	'edit' => 'Edit',
	'create' => 'Create',

	'dispname' => 'Display Name',
	'intname' => 'Internal Name',
	'intname_desc' => 'The Internal Name is the name of this item as it will appear when you tell pivot to display it in a template.',
	'size' => 'Size',
	'rows' => 'Rows',
	'cols' => 'Columns',
	'maxlen' => 'Maximum Length',
	'minlevel' => 'Min. User Level',
	'filter' => 'Filter by',
	'filter_desc' => 'By filtering this item, you limit the type of input that can be used in it',
	'no_filter' => 'Nothing',
	'del_title' => 'Confirm Delete',
	'del_desc' => 'Deleting this Userfield (<b>%s</b>) will also destroy all the data that users have stored withen it, and make any instances of it in a template appear empty.',

	'already' => 'This name is already in use',
	'int' => 'Internal Name must be longer than 3 characters',
	'short_disp' => 'Display Name must be longer than 3 characters',
);


$lang['bookmarklets'] = array (
	'bookmarklets' => 'Bookmarklets',
	'bm_add' => 'Add Bookmarklet.',
	'bm_withlink' => 'Pivot » New',
	'bm_withlink_desc' => 'This Bookmarklet opens a window with a New Entry, which contains a link to the page it was opened from.',

	'bm_nolink' => 'Pivot » New',
	'bm_nolink_desc' => 'This Bookmarklet opens a window with a blank New Entry.',

	'bookmarklets_info' => 'You can use Bookmarklets to quickly write New Entries with Pivot. To add a Bookmarklet to your browser, use one of the following options: (exact text varies, depending on which browser you are using)',
	'bookmarklets_info_1' => 'Click and drag the bookmarklet to your \'Links\'-toolbar or your browsers \'Bookmarks\'-button.',
	'bookmarklets_info_2' => 'Right-click on the bookmarklet and select \'Add to Bookmarks\'.',
);

// Accessibility - These are used for form fields, labels, fieldsets etc.
// for Web Content Accessibility Guidelines & 508 compliancy issues.
// see: http://bobby.watchfire.com/bobby/html/en/index.jsp
// JM =*=*= 2004/10/04
// 2004/11/25 =*=*= JM - minor correction for tim
$lang['accessibility'] = array(
	'search_idname'      => 'search',
	'search_formname'    => 'Search for words used in entries on this website',
	'search_fldname'     => 'Enter the words[s] to search for here:',
	'search_placeholder' => 'Enter searchterms',

	'calendar_summary'   => 'This table represents a calendar of entries in the weblog with hyperlinks on dates with entries.',
	'calendar_noscript'  => 'The calendar provides a means to access entries in this weblog',
	/*
	2-letter language code, used to designate the principal language used on the site
	see: http://www.oasis-open.org/cover/iso639a.html
	*/

	'lang' => $langname,
) ;


$lang['snippets_text'] = array (
    'word_plural'     => 'words',
    'image_single'    => 'image',
    'image_plural'    => 'images',
    'download_single' => 'file',
    'download_plural' => 'files',
);

$lang['trackback'] = array (
    'register' => 'Your trackback has been stored.',
    'duplicate' => 'Your trackback has not been stored, because it seems to be a duplicate of a previous entry',
    'too_many_hrefs' => 'The maximum number of hyperlinks was exceeded. Stop spamming.',
    'noid'      => 'No TrackBack ID (tb_id)',
    'nourl'     => 'No URL (url)',
    'tracked'   => 'Tracked',
    'email_subject' => '[Trackback] Re:',
);

$lang['commentuser'] = array (
    'title'             => 'Pivot user login',
    'header'            => 'Log in as a registered visitor',
    'logout'            => 'Log out.',
    'loggedout'         => 'Logged out',
    'login'             => 'Login',
    'loggedin'          => 'Logged in',
    'loggedinas'        => 'Logged in as',
    'pass_forgot'       => 'Forgotten your password?',
    'register_new'      => 'Register a new username.',
    'register'          => 'Register as a visitor',
    'register_info'     => 'Please fill out the following information. <strong>Be sure to give a valid email address</strong>, because we will send a verification email to that address.',
    'pass_note'         => 'Note: It\'s possible for the maintainer of this site <br /> to see your password.. Do <em>not</em> use a password<br /> that you use for other websites / accounts!',
    'show_email'        => 'Show my email address with comments',
    'notify'            => 'Notify me via email of new entries',
    'def_notify'        => 'Default notification of replies',
    'register'          => 'Register',
    'pass_invalid'      => 'Incorrect password',
    'nouser'            => 'No such user..',
    'change_info'       => 'Here you can change your information.',
    'pref_edit'         => 'Edit your preferences',
    'pref_change'       => 'Change preferences',
    'options'           => 'Options',
    'user_exists'       => 'User already exists.. Please pick another name.',
    'email_note'        => 'You must give your email address, since it\'ll be impossible to verify your account. You can always choose not to show your address to other visitors.',
    'stored'            => 'The changes have been stored',
    'verified'          => 'Your account is verified. Please log in..',
    'not_verified'      => 'That Code seems to incorrect. I\'m sorry, but I can\'t verify.',
    'pass_sent'         => 'Your password was sent to the mailbox given..',
    'user_pass_nomatch' => 'That username and email address do not seem to match.',
    'pass_send'         => 'Send password',
    'pass_send_desc'    => 'If you\'ve forgotten your password, fill in your username and e-mail address, and Pivot will send your password to your email address. ',
    'oops'              => 'Oops',
    'back'              => 'Back to',
    'back_login'        => 'Back to login',
    'forgotten_pass_mail' => "Your forgotten password for Pivot '%name%' is: \n\n%pass%\n\nDon't forget it again, please!\n\nTo log in to your account, click the following link:\n %link%"
);

// A little tool to help you check if the file is correct..
if (count(get_included_files())<2) {

	$groups = count($lang);
	$total = 0;
	foreach ($lang as $langgroup) {
		$total += count($langgroup);
	}
	echo "<h2>Language file is correct!</h2>";
	echo "This file contains $groups groups and a total of $total labels.";

}

?>
