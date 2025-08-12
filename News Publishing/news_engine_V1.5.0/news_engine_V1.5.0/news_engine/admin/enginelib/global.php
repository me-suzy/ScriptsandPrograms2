<?php
// +----------------------------------------------------------------------+
// | EngineLib - Image Functions                                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003,2004 AlexScriptEngine - e-Visions                 |
// +----------------------------------------------------------------------+
// | This code is not freeware. Please read our licence condition care-   |
// | fully to find out more. If there are any doubts please ask at the    |
// | Support Forum                                                        |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author: Alex Höntschel <info@alexscriptengine.de>                    |
// | Web: http://www.alexscriptengine.de                                  |
// | IMPORTANT: No email support, please use the support forum at         |
// |            http://www.alexscriptengine.de                            |
// +----------------------------------------------------------------------+
// $Id: global.php 2 2005-10-08 09:40:29Z alex $

$starttime = startTimer();

error_reporting(E_ALL & ~E_NOTICE);

set_error_handler("engineErrorHandler");

//Session Fehlermeldung deaktivieren
@ini_set("session.bug_compat_42", 1);
@ini_set("session.bug_compat_warn", 0);

// PHP-Version prüfen
//$curver = intval(str_replace(".","", phpversion()));
$ini_val = ( getPHPVersion() >= '400' ) ? 'ini_get' : 'get_cfg_var';

// bei PHP-Version älter als 4.1.0 neue superglobale Variablen füllen
if(getPHPVersion() < 410) {
	$_POST = array();
	$_GET = array();
	$_COOKIE = array();
	$_SESSION = array();
	$_FILES = array();
	$_SERVER = array();
	$_ENV = array();
	$_REQUEST = array();
	fill_new_vars();	
}
	
if (get_magic_quotes_gpc()) {
  if(is_array($_REQUEST)) $_REQUEST=stripslashes_array($_REQUEST);
  if(is_array($_POST)) $_POST=stripslashes_array($_POST);
  if(is_array($_GET)) $_GET=stripslashes_array($_GET);
  if(is_array($_COOKIE)) $_COOKIE=stripslashes_array($_COOKIE);
}
@set_magic_quotes_runtime(0);
	
// register_globals prüfen 
if(@$ini_val("register_globals")) {
	$register_globals = TRUE;
} else {
	$register_globals = FALSE;
}

$db_sql = new db_sql($dbName,$hostname,$dbUname,$dbPasswort);

$config = loadEngineSetting();

if(!defined("IN_ADMIN_CENTER")) {
    checkVariable('newsid',1);
    checkVariable('month',1);
    checkVariable('nameid',1);
    checkVariable('year',1);
    checkVariable('comid',1);
	checkVariable('start',1);
    $lang = array();
    include_once($_ENGINE['eng_dir']."lang/".$config['language']."/".$config['language'].".php");
} else {
    $a_lang = array();
    if($admin_lang == 1) include_once($_ENGINE['eng_dir']."admin/lang/german_admin.php");
    if($admin_lang == 2) include_once($_ENGINE['eng_dir']."admin/lang/english_admin.php");
    if($admin_lang != 1 && $admin_lang != 2) {
    	echo "No Language File available, you can not use the AdminCenter without!!!";
    	exit;
	}
}

// Session-Klasse initialisieren
$sess = new engineSession();

// Authentification-Klasse initialisieren
$auth = new engineAuth($user_table,$group_table);

if(!defined("IN_ADMIN_CENTER")) {
    $tpl = new engineTemplate($_ENGINE['eng_dir']."templates/".$config['template_folder']."/");
    
    if($own_header || $own_footer) {
        $tpl->setOwnBorder($own_footer,$own_header);
    }
    initStandardVars();
    $tpl->loadFile('header', 'header.html'); 
    $tpl->loadFile('footer', 'footer.html'); 
}

if(!defined("IN_ADMIN_CENTER")) {    
    if ($auth->user['userid'] != 2 || $auth->user['userid'] == 0) {
        $registered_member = true;
        if($auth->user['canmodifyownprofile']) {
            $modify_own_profile = true;
            $tpl->register('change_account_url', definedBoardUrls("changeaccount"));
        }
        $tpl->parseIf('header', 'modify_own_profile');
        $tpl->register('logout_url', $sess->url("misc.php?action=logout"));
    } else {
        $guest_member = true;
        $activate_login = false;
        $activate_registering = false;
        if ($config['userreg'] != '1') {
            $tpl->register('register_url', definedBoardUrls("addmember"));
            $activate_registering = true;
        }
        
        if ($config['userlogin'] != '1') {
            $tpl->register('login_url', $sess->url("misc.php?action=login"));
            $activate_login = true;
        }
    }
    $tpl->parseIf('header', 'activate_registering');
    $tpl->parseIf('header', 'activate_login');
    $tpl->parseIf('header', 'registered_member');
    $tpl->parseIf('header', 'guest_member');

    
    if ($auth->user['canseemembers']) {
        $use_memberlist = true;
        $tpl->register('memberlist_url', definedBoardUrls("memberlist"));
    }
    $tpl->parseIf('header', 'use_memberlist');
    
    if ($auth->user['canuseenginesearch']) {
        $use_search = true;
        $tpl->register('search_url', $sess->url("search.php?action=search"));
    }
    $tpl->parseIf('header', 'use_search');
    $tpl->parseIf('footer', 'use_search');
    
    if($auth->user['canpostnews']) {
        $post_news = true;
        $tpl->register('post_url', $sess->url("post_news.php"));    
    }
    $tpl->parseIf('header', 'post_news');
    
    if ($auth->user['canaccessadmincent'] == 1 ) {
        $access_admin_center = true;
        $tpl->register('admin_url', $sess->adminUrl("index.php"));       
    }
    $tpl->parseIf('footer', 'access_admin_center');
    
    $tpl->register(array('header_welcome' => $lang['header_welcome'],
                        'header_edit_profile' => $lang['header_edit_profile'],
                        'header_log_out' => $lang['header_log_out'],
                        'header_log_in' => $lang['header_log_in'],
                        'header_register' => $lang['header_register'],
                        'header_search' => $lang['header_search'],
                        'header_memberlist' => $lang['header_memberlist'],
                        'header_write_new_article' => $lang['header_write_new_article'],
                        'header_news_archive' => $lang['header_news_archive'],
                        'footer_quick_search' => $lang['footer_quick_search'],
                        'footer_admin_center' => $lang['footer_admin_center']));
}


?>