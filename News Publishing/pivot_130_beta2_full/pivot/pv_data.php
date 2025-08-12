<?php

// ---------------------------------------------------------------------------
//
// PIVOT - LICENSE:
//
// This file is part of Pivot. Pivot and all its parts are licensed under
// the GPL version 2. see: http://www.pivotlog.net/help/help_about_gpl.php
// for more information.
//
// ---------------------------------------------------------------------------

// don't access directly..
if(!defined('INPIVOT')){ exit('not in pivot'); }

/**
 * This is a list of the functions / screens that are available  all users
 *
 */
function mainFunctions() {
	global $Pivot_Vars, $Users, $mainFunctions, $mainInternal, $mainCommon;

	/**
	 * 	for menus and submenus, it's really a bitch...
	 *  $mainFunctions[$function][0]] for the 'toplevel' display/category.
	 *  $mainFunctions[$function][x>0][1] for the 'sublevel displays, [0] for the links
	 * 	the key for the 'toplevel' categories is now obsolete, as it will redisplay
	 * 	the current page, expanding the sublevel of the category, it's merely a
	 * 	referance to get the current 'tree' of the menu..
	 */
	$mainFunctions = array(
		'main' => array(lang('userbar','main')),
		'entries' => array(lang('userbar','entries'), array('new_entry', lang('userbar','submit'))	),
		'userinfo'=> array(lang('userbar','userinfo'), array('u_marklet', lang('userbar','u_marklet')))
	);

	if($Users[$Pivot_Vars['user']]['userlevel'] > 1) {
		$mainFunctions['files'] = array(lang('userbar','files'));
	}
	if($Users[$Pivot_Vars['user']]['userlevel'] > 2) {
		$mainFunctions['admin'] = array(lang('userbar','admin'));
	}

	//for calling the actual function
	$mainInternal = array(
		'main' => 'main_screen',
		'entries' => 'entries_screen',
		'new_entry' => 'newentry_screen',
		'modify' => 'modifyentry_screen',
		'entrysubmit' => 'entrysubmit_screen',
		'editcomments' => 'edit_comments',
		'submitcomment' => 'submit_comment',
		'edittrackbacks' => 'edit_trackbacks',
		'submittrackback' => 'submit_trackback',
		'files' => 'files_main',
		'about' => 'show_about',
		'userinfo' => 'u_settings_screen',
		'save_user' => 'u_settings_save',
		'u_marklet' => 'u_marklet_screen',
		'updatetitles' => 'updatetitles_screen',
		'file_upload' => 'uploadfile',
		'buildfrontpage' => 'buildfrontpage', /* these ones are duplicated in the  */
		'send_pings' => 'send_pings',			/* admin array. Need to figure out if  */
		'build_index' => 'build_index',			 /* this is the best way to do this.. */
		'build_search' => 'build_search',
	);
}


/**
 * This is a list of the functions / screens that are available to admin-users
 *
 */
function adminFunctions() {
	global $adminFunctions, $adminInternal;

	// For show..
	$adminFunctions = array(
		'seeusers' => lang('adminbar','seeusers'),
		'categories' => lang('adminbar','categories'),
		'blogs' => lang('adminbar','blogs'),
		'filemappings' => lang('adminbar','filemappings'),
		'templates' => lang('adminbar','templates'),
		'seeconfig' => lang('adminbar','seeconfig'),
		'maintenance' => lang('adminbar','maintenance')
	);

	// For calling the actual function
	$adminInternal = array(
		'categories' => 'see_categories',
		'save_cat' => 'save_categories',
		'cat_edit' => 'edit_category',
		'del_cat' => 'delete_category',
		'index_cat' => 'searchindex_categories',
		'blogs' => 'main_blogs',
		'blog_edit1' => 'main_blogs_edit1',
		'blog_edit2' => 'main_blogs_edit2',
		'blog_edit3' => 'main_blogs_edit3',
		'blog_save' => 'main_blog_save',
		'blog_delete' => 'main_blog_delete',
		'seeusers' => 'see_users',
		'edituser' => 'change_user',
		'userfields' => 'userfields_main',
		'new_ufield' => 'userfields_new',
		'ufield_edit' => 'userfields_edit',
		'editsave_ufield' => 'userfields_editsave',
		'save_user' => 'save_change_user',
		'new_user' =>  'new_user',
		'new_user_save' => 'new_user_save',
		'seeconfig' =>  'see_config',
		'save_config' => 'save_config',
		'maintenance' => 'maint_overview',
		'regen' => 'regen',
		'buildfrontpage' => 'buildfrontpage',
		'send_pings' => 'send_pings',
		'verify_db' => 'verify_db',
		'build_index' => 'build_index',
		'build_search' => 'build_search',
		'backup' => 'backup',
		'filemappings' => 'filemappings',
		'templates' => 'templates',
		'ipblocks' => 'ipblocks',
		'ignoreddomains' => 'ignoreddomains',
		'fileexplore' => 'fileexplore',
		'backup_config' => 'backup',
	);
}



function get_userfields($admin=0) {

	$array = array(
		array('username', lang('userinfo','username'), '', 0, '', '', ''),
		array('pass1', lang('userinfo','pass1'), '', 1, '', '', 'maxlength="20"'),
		array('pass2', lang('userinfo','pass2'), '', 1, '', '', 'maxlength="20"'),
		array('language', lang('userinfo','language'), '', 3, Getlangs(), '', 'eng'),
		array('wysiwyg', lang('config','wysiwyg'), lang('config','wysiwyg_desc'), 3, 'yn'),
		array('view', lang('config','basic_view'), lang('config','basic_view_desc'), 3, 'yn'),
	);

	if($admin==1){
		array_push($array,
			array('userlevel', lang('userinfo','userlevel'), lang('userinfo','userlevel_desc'), 3, array(lang('userlevels',4), '0',lang('userlevels',3), '1', lang('userlevels',2), '2', lang('userlevels',1), '3'), '', '1')
		);
	}
	return $array;
}

function userfields_array($textbox=0){

	$ufield_arr = array(
		array('intname', lang('ufield_main', 'intname'), lang('ufield_main', 'intname_desc'), 0, '', '', ''),
		array('disp', lang('ufield_main', 'dispname'),'' , 0, '', '', ''),
	);

	if($textbox==1){
		array_push($ufield_arr,
			array('rows', lang('ufield_main', 'rows'), '', 0, '15', '5', ''),
			array('cols', lang('ufield_main', 'cols'),'' , 0, '40', '5', '')
		);
		// textareas will be added later on, along with other stuff. for now it's just textboxes
	}else{
		array_push($ufield_arr,
			array('size', lang('ufield_main', 'size'),'' , 0, '15', '5', '')
		);
	}
	array_push($ufield_arr,
		array('maxlen', lang('ufield_main', 'maxlen'),'' , 0, '25', '5', ''),
		array('minlevel', lang('userinfo','userlevel'), '' , 3, array(lang('userlevels',3), '1', lang('userlevels',2), '2', lang('userlevels',1), '3'), '', ''),
		array('filter', lang('ufield_main', 'filter'), lang('ufield_main', 'filter_desc'), 3, array(lang('ufield_main', 'no_filter'), '', lang('email'), 'email', lang('url'), 'url'), '', '', '')
	);

	return $ufield_arr;
}


/**
 * We use an array like in setup so that we can just get
 * the variable names from it to see what we need to save
 * from Pivot_Vars
 *
 * @return array
 */
function get_config() {
	global $Cfg;

	// Define the array of options we want to display
	$i=-1;
	foreach(explode("|", $Cfg['upload_accepted']) as $type){
		$upload_accepted[$i++] = $type;
		$upload_accepted[$i++] = $type;
	}

	$upload_accepted = array('jpg', 'jpg', 'jpeg', 'jpeg', 'gif', 'gif', 'bmp', 'bmp', 'png', 'png', 'pcx', 'pcx');

	$config_array =  array(
		array('sitename', lang('config','sitename'), '', 0, 'Pivot Powered', '', ''),

		array('deflang', lang('config','defaultlanguage'), '', 3, GetLangs(), '', ''),
		array('defencoding', lang('config','defaultencoding'), lang('config','defaultencoding_desc'), 0, '', '', ''),
		array('selfreg', lang('config','selfreg'), lang('config', 'selfreg_desc'), 3, 'yn', '', '1'),
		array('deftheme', lang('config','defaulttheme'), '', 3, getthemes(), '', ''),

		array('heading', lang('config','debug_options'),'', 8, '', '2', ''),
		array('debug', lang('config','debug'), lang('config', 'debug_desc'), 3, 'yn', '', '1'),
		array('log', lang('config','log'), lang('config', 'log_desc'), 3, 'yn', '', '1'),
		array('unlink', lang('config','unlink'), lang('config', 'unlink_desc'), 3, 'yn', '', '1'),
		array('chmod', lang('config','chmod'), lang('config', 'chmod_desc'), 0, '0644', '', ''),

		array('heading', lang('config','header_uploads'),'', 8, '', '2', ''),
		array('upload_path', lang('config','upload_path'), '', 0, 'images/', '', ''),
		array('upload_extension', lang('config','upload_extension'), '', 0, '.jpg', '', ''),
		array('upload_save_mode', lang('config','upload_save_mode'), '', 3, array(lang('yes'), '1', lang('config','c_upload_save_mode'), '2', lang('no'), '3'), '', ''),
		array('upload_max_filesize', lang('config','max_filesize'), '', 0, '5000000', '', ''),

		array('upload_autothumb', lang('config','upload_autothumb'), '', 3, 'yn', '', '1'),
		array('upload_thumb_width', lang('config','upload_thumb_width'), '', 0, '240', '', ''),
		array('upload_thumb_height', lang('config','upload_thumb_height'), '', 0, '180', '', ''),
		array('upload_thumb_remote', lang('config', 'upload_thumb_remote'), lang('config', 'upload_thumb_remote_desc'), 0, '', 60),

		array('heading', lang('config','extensions_header'),'', 8, '', '2', ''),
		array('extensions_path', lang('config','extensions_path'), lang('config', 'extensions_desc'), 0, 'extensions/', '', ''),

		array('heading', lang('config','header_datetime'), '', 8, '', '2', ''),
		array('timeoffset_unit', lang('config','timeoffset_unit'), '', 3, array(
				lang('datetime_words','0'), 'y',
				lang('datetime_words','1'), 'm',
				lang('datetime_words','3'), 'd',
				lang('datetime_words','4'), 'h',
				lang('datetime_words','5'), 'i'
			), '', ''),
		array('timeoffset', lang('config','timeoffset'), '', 0, '0', '', ''),

		array('heading', lang('config', 'setup_ping'), '', 8, '', 2),
		array('ping', lang('config', 'ping_use'), lang('config', 'ping_use_desc'), 3, 'yn'),
		array('ping_urls', lang('config','ping_urls'), lang('config', 'ping_urls_desc'), 5,'', '60', 'rows=4'),

		array('heading', lang('config','header_extra'), '', 8, '', '2', ''),
		array('wysiwyg_editor', lang('config','wysiwyg'), '', 3, 'yn', '', '1'),
		array('text_processing', lang('config','def_text_processing'), lang('config','text_processing_desc'), 3, array(
				lang('config', 'none'), '0',
				lang('config', 'convert_br'), '1',
				lang('config', 'textile'), '2',
				lang('config', 'markdown'), '3',
				lang('config', 'markdown_smartypants'), '4'
			), '', '0'	),

		//array('targetblank', lang('config','new_window'), lang('config','new_window_desc'), 3, 'yn', '', '1'),
		array('mod_rewrite', lang('config','mod_rewrite'), lang('config','mod_rewrite_desc'), 3, array(
				lang('no'), '0',
				lang('config','mod_rewrite_1'), '1',
				lang('config','mod_rewrite_2'), '2',
				lang('config','mod_rewrite_3'), '3',
				lang('config','mod_rewrite_4'), '4'
			), '', ''),
		array('search_index', lang('config','search_index'), lang('config','search_index_desc'), 3, 'yn', '', '1'),
		array('allow_comments', lang('config','default_allow_comments'), lang('config','default_allow_comments_desc'), 3, 'yn', '', '1'),
		array('maxhrefs', lang('config','maxhrefs'), lang('config','maxhrefs_desc'), 0, '5', '', ''),
		array('rebuild_threshold', lang('config','rebuild_threshold'), lang('config','rebuild_threshold_desc'), 0, '28', '', ''),

	);

	return $config_array;


}


/**
 * ok - we have to use all 8 parameters that the display engine can take, all because we
 * need to be able to get the saved settings when editing a weblog. eh.. it works, so
 * let's just leave it at that, shall we? -mark
 *
 * @return array
 */
function get_weblog_config1($orig_name="") {


	$templates= gettemplates();

	foreach ($templates as $key => $val) {
		$pos1 = strpos($val, '_aux_');
		$pos2 = strpos($val, '_sub_');
		if ( ($pos1 === 0) || ($pos2 === 0) ) {
			unset($templates[$key]);
		}
	}

	sort($templates);

	$config = array(

		array('orig_name', lang('weblog_config', 'name'), '', 7, $orig_name),
		array('name', lang('weblog_config', 'name'), '', 0),
		array('payoff', lang('weblog_config', 'payoff'), lang('weblog_config', 'payoff_desc'), 0, '', 60),

		array('siteurl', lang('weblog_config', 'url'), lang('weblog_config', 'url_desc'), 0, '', 40),
		array('front_filename', lang('weblog_config', 'index_name'), lang('weblog_config', 'index_name_desc'), 0),
		array('front_path', lang('weblog_config', 'front_path'), lang('weblog_config', 'front_path_desc'), 0),
		array('language', lang('weblog_config', 'language'), lang('weblog_config', 'language_desc'), 3, GetLangs(), '', ''),

		array('heading', lang('weblog_config', 'entry_heading'),'',8,'',2),
		array('live_entries', lang('weblog_config', 'live_comments'), lang('weblog_config', 'live_comments_desc'), 3, 'yn' ),
		array('entry_filename', lang('weblog_config', 'file_format'), '', 0),
		array('entry_path', lang('weblog_config', 'entry_path'), lang('weblog_config', 'entry_path_desc'), 0),
		array('read_more', lang('weblog_config', 'readmore'), lang('weblog_config', 'readmore_desc'), 0),

		array('heading', lang('weblog_config', 'arc_heading'),'',8,'',2),
		array('archive_filename', lang('weblog_config', 'arc_index'), '', 0),
		array('archive_path', lang('weblog_config', 'arc_path'), '', 0),
		array('archive_unit', lang('weblog_config','archive_unit'), '', 3, array(lang('weblog_config','archive_none'), 'none', lang('weblog_config','archive_weekly'), 'week', lang('weblog_config','archive_monthly'), 'month', lang('weblog_config','archive_yearly'), 'year')),
		array('archive_link', lang('weblog_config','archive_link'), '', 5,'', '60', 'rows=3'),
		array('archive_linkfile', lang('weblog_config','archive_linkfile'), '', 5,'', '60', 'rows=3'),
		array('archive_order', lang('weblog_config','archive_order'), '', 3, array(lang('weblog_config','archive_ascending'), 'ascending', lang('weblog_config','archive_descending'), 'descending')),

		array('heading', lang('weblog_config', 'templates_heading'), '', 8, '', 2),
		array('front_template', lang('weblog_config','frontpage_template'), lang('weblog_config','frontpage_template_desc'), 3, $templates),
		array('archive_template', lang('weblog_config','archivepage_template'), lang('weblog_config','archivepage_template_desc'), 3, $templates),
		array('entry_template', lang('weblog_config','entrypage_template'), lang('weblog_config','entrypage_template_desc'), 3, $templates),
		array('extra_template', lang('weblog_config','extrapage_template'), lang('weblog_config','extrapage_template_desc'), 3, $templates)
	);


	return $config;

}

/**
 * Get the config options for the third screen in weblog setup
 *
 * @return array
 */
function get_weblog_config3() {

	$d= dir("templates");
	while ($entry=$d->read()) {
		$ext=getextension($entry);
		if (($ext=="htm")||($ext=="html")) {
			$fullentry="templates/".$entry;

			$templates[]= $entry;
			$templates[]= $fullentry;

		}
	}
	$d->close();

	$config_array = array(

		array('heading', lang('weblog_config', 'setup_rss_head'), '', 8, '', 2),
		array('rss', lang('weblog_config', 'rss_use'), lang('weblog_config', 'rss_use_desc'), 3, 'yn'),
		array('rss_filename', lang('weblog_config', 'rss_filename'), '' , 0),
		array('atom_filename', lang('weblog_config', 'atom_filename'), '' , 0),
		array('rss_path', lang('weblog_config', 'rss_path'), lang('weblog_config', 'rss_path_desc'), 0),
		array('rss_full', lang('weblog_config', 'rss_full'), lang('weblog_config', 'rss_full_desc'), 3, 'yn'),

		array('rss_link', lang('weblog_config', 'rss_link'), lang('weblog_config', 'rss_link_desc'), 0, '', 60),
		array('rss_img', lang('weblog_config', 'rss_img'), lang('weblog_config', 'rss_img_desc'), 0, '', 60),


		array('heading', lang('weblog_config', 'various_head'), '', 8, '', 2),
		array('emoticons', lang('weblog_config', 'emoticons'), lang('weblog_config', 'emoticons_desc'), 3, 'yn'),
		array('encode_email_addresses', lang('weblog_config', 'encode_email_addresses'), lang('weblog_config', 'encode_email_addresses_desc'), 3, 'yn'),

		array('target_blank', lang('weblog_config', 'target_blank'), lang('weblog_config', 'target_blank_desc'), 3, array(lang('no'), '0', lang('yes'), '1', lang('weblog_config','xhtml_workaround'), '2')),

		array('heading', lang('weblog_config', 'lastcomm_head'), '', 8, '', 2),
		array('lastcomm_amount', lang('weblog_config', 'lastcomm_amount'), '', 0),
		array('lastcomm_length', lang('weblog_config', 'lastcomm_length'), '', 0),
		array('lastcomm_format', lang('weblog_config', 'lastcomm_format'), lang('weblog_config', 'lastcomm_format_desc'), 5,'', '60', 'rows=4'),
		array('lastcomm_redirect', lang('weblog_config','lastcomm_nofollow'), lang('weblog_config', 'lastcomm_nofollow_desc'), 3, 'yn', '', '1'),

		/** Deprecated..
		array('heading', lang('weblog_config', 'lastref_head'), '', 8, '', 2),
		array('lastref_amount', lang('weblog_config', 'lastref_amount'), '', 0),
		array('lastref_length', lang('weblog_config', 'lastref_length'), '', 0),
		array('lastref_format', lang('weblog_config', 'lastref_format'), lang('weblog_config', 'lastref_format_desc'), 5,'', '60', 'rows=4'),
		array('lastref_graphic', lang('weblog_config','lastref_graphic'), lang('weblog_config', 'lastref_graphic_desc'), 3, 'yn', '', '1'),
		*/

		array('heading', lang('weblog_config', 'date_head'), '', 8, '', 2),

		array('fulldate_format', lang('weblog_config','full_date'), lang('weblog_config', 'full_date_desc'), 5,'', '60', 'rows=3'),
		array('entrydate_format', lang('weblog_config','entry_date'), '', 5,'', '60', 'rows=3'),
		array('diffdate_format', lang('weblog_config','diff_date'), lang('weblog_config', 'diff_date_desc'), 5,'', '60', 'rows=3'),

		array('heading', lang('weblog_config', 'comment_head'), '', 8, '', 2),
		array('comment_sendmail', lang('weblog_config', 'comment_sendmail'), lang('weblog_config', 'comment_sendmail_desc'), 3, 'yn'),
		array('comment_emailto', lang('weblog_config', 'comment_emailto'), lang('weblog_config', 'comment_emailto_desc'), 0, '', 60),
		array('comment_texttolinks', lang('weblog_config', 'comment_texttolinks'), lang('weblog_config', 'comment_texttolinks_desc'), 3, 'yn'),
		array('comment_wrap', lang('weblog_config', 'comment_wrap'), lang('weblog_config', 'comment_wrap_desc'), 0),
		array('comments_text_0', lang('weblog_config', 'comments_text_0'), '', 0, '', 60),
		array('comments_text_1', lang('weblog_config', 'comments_text_1'), '', 0, '', 60),
		array('comments_text_2', lang('weblog_config', 'comments_text_2'), lang('weblog_config', 'comments_text_2_desc'), 0, '', 60),
		array('comment_pop', lang('weblog_config', 'comment_pop'), lang('weblog_config', 'comment_pop_desc'), 3, 'yn'),
		array('comment_width', lang('weblog_config', 'comment_width'), '', 0, '', 60),
		array('comment_height', lang('weblog_config', 'comment_height'), lang('weblog_config', 'comment_height_desc'), 0, '', 60),
		array('comment_format', lang('weblog_config', 'comment_format'), lang('weblog_config', 'comment_format_desc'), 5,'', '60', 'rows=6'),
		array('comment_reply', lang('weblog_config', 'comment_reply'), lang('weblog_config', 'comment_reply_desc'), 0, '', 60),
		array('comment_forward', lang('weblog_config', 'comment_forward'), lang('weblog_config', 'comment_forward_desc'), 0, '', 60),
		array('comment_backward', lang('weblog_config', 'comment_backward'), lang('weblog_config', 'comment_backward_desc'), 0, '', 60),

		array('comment_textile', lang('weblog_config', 'comment_textile'), lang('weblog_config', 'comment_textile_desc'), 3, 'yn'),



		array('comment_gravatardefault', lang('weblog_config', 'comment_gravatardefault'), lang('weblog_config', 'comment_gravatardefault_desc'), 0, 'http://www.pivotlog.net/images/gravatar.gif', 60),
		array('comment_gravatarhtml', lang('weblog_config', 'comment_gravatarhtml'), lang('weblog_config', 'comment_gravatarhtml_desc'), 0, "<img src=\"%img%\" align=\"right\" valign=\"top\" hspace=\"2\" vspace=\"2\" />", 60),
		array('comment_gravatarsize', lang('weblog_config', 'comment_gravatarsize'), lang('weblog_config', 'comment_gravatarsize_desc'), 0, "48", 20),



		array('heading', lang('weblog_config', 'trackback_head'), '', 8, '', 2),
		array('trackbacks_text_0', lang('weblog_config', 'trackbacks_text_0'), '', 0, '', 60),
		array('trackbacks_text_1', lang('weblog_config', 'trackbacks_text_1'), '', 0, '', 60),
		array('trackbacks_text_2', lang('weblog_config', 'trackbacks_text_2'), lang('weblog_config', 'trackbacks_text_2_desc'), 0, '', 60),
		array('trackback_format', lang('weblog_config', 'trackback_format'), lang('weblog_config', 'trackback_format_desc'), 5,'', '60', 'rows=6'),
		array('trackback_link_format', lang('weblog_config', 'trackback_link_format'), '', 0,'', '60', ''),

		array('heading', lang('weblog_config', 'blogroll_heading'), '' ,8,'',2),
		array('blogroll_id', lang('weblog_config', 'blogroll_id'), lang('weblog_config', 'blogroll_id_desc'), 0, '', 60),
		array('blogroll_fg', lang('weblog_config', 'blogroll_fg'), '', 0, '', 20),
		array('blogroll_bg', lang('weblog_config', 'blogroll_bg'), '', 0, '', 20),
		array('blogroll_line1', lang('weblog_config', 'blogroll_line1'), '', 0, '', 20),
		array('blogroll_line2', lang('weblog_config', 'blogroll_line2'), '', 0, '', 20),
		array('blogroll_c1', lang('weblog_config', 'blogroll_c1'), '', 0, '', 20),
		array('blogroll_c2', lang('weblog_config', 'blogroll_c2'), '', 0, '', 20),
		array('blogroll_c3', lang('weblog_config', 'blogroll_c3'), '', 0, '', 20),
		array('blogroll_c4', lang('weblog_config', 'blogroll_c4'), lang('weblog_config', 'blogroll_c4_desc'), 0, '', 20),


	);

	return $config_array;

}

/**
 * When initializing a new weblog, these are the default settings
 *
 * @return array
 */
function get_tempweblog() {

	$weblog = array(
		name => "Weblog title",
		payoff => "A new Pivot weblog",
		siteurl => "",
		front_filename => 'new_weblog.php',
		front_path => '../',

		ssi_prefix => '',

		archive_filename => 'archive_%1.php',
		archive_path => '../archives/',
		archive_amount => 1,
		archive_unit => "month",

		live_entries => 1,
		entry_filename => "entry_%1.php",
		entry_path => '../',
		read_more => '',

		front_template => "frontpage_template_2columns.html",
		archive_template => "archivepage_template.html",
		entry_template => "entrypage_template_normal.html",

		rss => 1,
		rss_filename => "rss.xml",
		atom_filename => "atom.xml",
		rss_path => '../',
		rss_entry_length => 300,
		lastcomm_amount => 10,
		lastcomm_length => 24,
		lastcomm_format => "<a href='%url%' title='%day% %month% %year% - %hour24%:%minute%' %popup%><b>%name%</b></a> (%title%): %comm%<br />",
		lastref_amount => 10,
		lastref_length => 24,
		lastref_format => "",
		emoticons => 1,
		encode_email_addresses => 0,
		target_blank => 1,

		comment_sendmail => 0,
		comment_emailto => "you@yourdomain.com",
		comment_texttolinks => 1,
		comment_wrap => 100,
		comments_text_0 => lang('comments_text','0'),
		comments_text_1 => lang('comments_text','1'),
		comments_text_2 => lang('comments_text','2'),

		comment_pop => 0,
		comment_width => 530,
		comment_height => 460,
		comment_format => "<p>%comment%<br /><small><b>%name%</b> %email% %url% - %date%</small></p>",

		comment_reply => "comment on %name%",
        comment_forward => "- see: %name%",
        comment_backward => "@%name%: ",

		trackbacks_text_0 => lang('trackbacks_text','0'),
		trackbacks_text_1 => lang('trackbacks_text','1'),
		trackbacks_text_2 => lang('trackbacks_text','2'),


		trackback_format => "<p><strong>%title%</strong><br />%excerpt%<br /><small>Sent on %date%, via %url%</small></p>",
		trackback_link => "<p><small>Trackback link: <a href='%url%'>%url%</a>.</small></p>",

		archive_link => "Go to <a href=&quot;%url%&quot;>%name%</a>",
		archive_linkfile => "<a href=\"%url%\">%st_day% %st_monname% - %en_day% %en_monname% %st_year% </a><br />",
		diffdate_format => "%day% %month% %ye%",
		entrydate_format => "%hour24%:%minute%",
		fulldate_format => "%day% %month% %ye% - %hour24%:%minute%",

		language => "eng",

		blogroll_id => '',
		blogroll_fg => '#000000',
		blogroll_bg => '#FFFFFF',
		blogroll_line1 => '#666666',
		blogroll_line2 => '#E8E8E8',
		blogroll_c1 => '#90A8BF',
		blogroll_c2 => '#ACBECF',
		blogroll_c3 => '#C8D4DF',
		blogroll_c4 => '#E3E9EF',

		sub_weblog => array(
			"standard" => array(
				categories => array("default"),
				template => "log_entry_standard.html",
				num_entries => 8,
				offset => "",
				comments => 1
			),
			"linkdump" => array(
				categories => array("linkdump"),
				template => "log_entry_linkdump.html",
				num_entries => 8,
				offset => "",
				comments => 1
			)
		)

	);

	return $weblog;


}


?>
