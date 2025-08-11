<?php 
// ----------------------------------------------------------------------
// ModName: page.php
// Purpose: ChakraWeb Setup 
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

//this file and all includes file loaded as library
define(LOADED_AS_LIBRARY, 1);

define('_ERR_UNKNOWN_SETUP_STAGE', 'Unknown setup stage (%s)');

$PhpSelf = $_SERVER['PHP_SELF'];
$PhpReferer = $_SERVER['HTTP_REFERER'];
$PhpRemoteAddr = $_SERVER['REMOTE_ADDR'];
$PhpMagicQuote = get_magic_quotes_gpc();

define(QUICK4ALL_DESC_ENG, 'Quick4All developed some free and open source software for build and accessing the internet easily: 
<a href="http://chakra.quick4all.com/Products/ChakraWeb/">ChakraWeb</a>, a CMS to build a wonderlful websites like this one;
and <a href="http://chakra.quick4all.com/Products/Chakra/">Chakra</a>, a web browser that include many offline websites and directories. You can search offline before you go surfing the internet.');

define(QUICK4ALL_DESC_IND, '
Quick4All mengembangkan beberapa software freeware dan open source untuk membuat dan mengakses internet dengan mudah: 
<a href="http://chakra.quick4all.com/Products/ChakraWeb/">ChakraWeb</a>, sebuah CMS untuk membuat website luar biasa seperti website ini;
dan <a href="http://chakra.quick4all.com/Products/Chakra/">Chakra</a>, sebuah web browser yang mengandung banyak website lokal dan direktori web. Anda dapat melakukan pencarian informasi yang anda butuhkan, bahkan sebelum anda pergi online ke internet.');


require_once('../_files/library/_defgenerate.php');
require_once('../_files/library/_defgeneral.php');
require_once('../_files/library/cls_dbase.php');
require_once('../_files/library/fun_dbvars.php');
require_once('../_files/library/fun_dbutils.php');
require_once('../_files/library/fun_utils.php');
require_once('../_files/library/fun_system.php');
require_once('../_files/library/fun_string.php');
require_once('../_files/library/fun_template.php');

$lid = RequestGetValue('lid', 'en');

$lang_module = '../_lang/'.$lid.'/global.php';
require_once($lang_module);

$lang_module = './lang_'.$lid.'.php';
require_once($lang_module);

SetDynamicContent();
GetOsInfo();

$gLocalPathSeparator = GetLocalPathSeparator();
$sep = $gLocalPathSeparator;

$gBaseLocalPath = ProperLocalFileName($_SERVER['DOCUMENT_ROOT'].$sep);
$gImageLocalPath = $gBaseLocalPath."images".$sep;
ParseRequestPathAndFile($_SERVER["REQUEST_URI"], $gRequestPath, $gRequestFile);


// ----------------------------------------------------------------------
// SetupDispatch
// ----------------------------------------------------------------------

$stage = RequestGetValue('stage', 'start');
$prev_stage = RequestGetValue('prev_stage');
$next_stage = RequestGetValue('next_stage');

$tmp = RequestGetValue('next', '');
if (!empty($tmp))
{
    $stage = $next_stage;
}
else
{
    $tmp = RequestGetValue('previous', '');
    if (!empty($tmp))
        $stage = $prev_stage;
}


switch ($stage)
{
case 'start':
    SetupStart($lid);
    break;

case 'chmod':
    SetupChmodCheck($lid);
    break;

case 'hpinfo':
    SetupHomePageInfo($lid);
    break;

case 'hpinfo_save':
    SetupHomePageInfoSave($lid);
    break;

case 'dbinfo':
    SetupDbInfo($lid);
    break;

case 'dbcreate':
    SetupDbCreate($lid);
    break;

case 'admin':
    SetupAdmin($lid);
    break;

case 'sample':
    SetupCreateAdminAndSamplePages($lid);
    break;

case 'finish':
    SetupFinish($lid);
    break;

default:
    $errmsg = sprintf(_ERR_UNKNOWN_SETUP_STAGE, $stage);
    SetupErrorScreen($errmsg, $prev_stage, $lid);
    break;    
}


// ----------------------------------------------------------------------
// Setup Stages
// ----------------------------------------------------------------------
function SetupStart($lid)
{
    $params = array();

    if ($lid == 'en')
    {
        $params['en_checked'] = 'checked';
        $params['id_checked'] = '';
    }
    else
    {
        $params['en_checked'] = '';
        $params['id_checked'] = 'checked';
    }

    $content = LoadContentFile('scr_intro_'.$lid.'.htm', $params);

    SetupScreen(SETUP_STAGE_START_TITLE, $content, 'start', '', 'chmod', $lid);
}

function SetupChmodCheck($lid)
{
    $chmod_list = ChmodCheck('/_files/library/_defgenerate.php');
    
    $params = array();
    $params['chmod_list'] = $chmod_list;
    $content = LoadContentFile('scr_chmod_'.$lid.'.htm', $params);

    SetupScreen(SETUP_STAGE_CHMOD_TITLE, $content, 'chmod', 'start', 'hpinfo', $lid);
}

function SetupHomePageInfo($lid)
{
    global $gSysVar, $gHomePageUrl;
    global $gThemeList, $gLanguageList;
    global $HTTP_SERVER_VARS;

    if (empty($gHomePageUrl))
        $gHomePageUrl = 'http://'.$HTTP_SERVER_VARS['SERVER_NAME'];

    $params = array();

    $params['hp_name']     = $gSysVar['hp_name'];
    $params['hp_url']      = $gHomePageUrl;
    $params['hp_desc']     = $gSysVar['hp_desc'];
    $params['hp_slogan']   = $gSysVar['hp_slogan'];
    $params['hp_keywords'] = $gSysVar['hp_keywords'];

    $params['theme_select'] = ComboBoxFromArray1($gThemeList, 'default_theme', DEFAULT_THEME);;
    $params['lang_select']  = ComboBoxFromArray($gLanguageList, 'default_lid', DEFAULT_LID);;

    $content = LoadContentFile('scr_hpinfo_'.$lid.'.htm', $params);
    SetupScreen(SETUP_STAGE_WEBINFO_TITLE, $content, 'hpinfo', 'chmod', 'hpinfo_save', $lid);
}

function SetupHomePageInfoSave($lid)
{
    global $gSysVar, $gHomePageUrl, $gSysConstant;

    $hp_name     = RequestGetValue('hp_name', '');
    $hp_url      = RequestGetValue('hp_url', '');
    $hp_desc     = RequestGetValue('hp_desc', '');
    $hp_slogan   = RequestGetValue('hp_slogan', '');
    $hp_keywords = RequestGetValue('hp_keywords', '');

    if (empty($hp_name) || empty($hp_url) || empty($hp_desc) || empty($hp_slogan) || empty($hp_keywords))
    {
        $content = SETUP_ERROR_HPINFO;
        SetupErrorScreen($content, 'hpinfo', $lid);
        die();
    }

    if (StrIsEndWith($hp_url, "\/"))
        $hp_url = substr($hp_url, 0, strlen($hp_url)-1);

    //Save to _defgenerate.php
    SystemGetConstant();

    $gSysVar['hp_name']     = $hp_name;
    $gHomePageUrl           = $hp_url;
    $gSysVar['hp_desc']     = $hp_desc;
    $gSysVar['hp_slogan']   = $hp_slogan;
    $gSysVar['hp_keywords'] = $hp_keywords;
  
    $gSysConstant['DEFAULT_THEME']  = RequestGetValue('default_theme');
    $gSysConstant['DEFAULT_LID']    = RequestGetValue('default_lid');

    SystemSaveVariables();

    Header("Location: page.php?stage=dbinfo&lid=$lid");
}

function SetupDbInfo($lid)
{
    $params = array();

    $params['db_host']      = DB_HOST;
    $params['db_user']      = DB_USER;
    $params['db_password']  = DB_PASSWORD;
    $params['db_name']      = DB_NAME;

    $content = LoadContentFile('scr_dbinfo_'.$lid.'.htm', $params);

    SetupScreen(SETUP_STAGE_DBINFO_TITLE, $content, 'dbinfo', 'hpinfo', 'dbcreate', $lid);
}

function SetupDbCreate($lid)
{
    global $gSysConstant;

    $bresult = true;

    $db_host        = RequestGetValue('db_host', '');
    $db_user        = RequestGetValue('db_user', '');
    $db_password    = RequestGetValue('db_password', '');
    $db_name        = RequestGetValue('db_name', '');
    $db_make        = RequestGetValue('db_make', 0);


    $params = array();
    $params['db_name'] = sprintf(DB_INFO_FMT, $db_name, $db_host);

    $conn = @mysql_connect($db_host, $db_user, $db_password);

    $params['db_server_status']  = $conn ? STATUS_SUCCESS : STATUS_FAILED;
    $params['db_create_status']  = STATUS_UNKNOWN;
    $params['db_connect_status'] = STATUS_UNKNOWN;

    if ($conn)
    {
        if ($db_make)
        {
            $sql = "create database $db_name";
            $handle = @mysql_query($sql, $conn);
            if ($handle)
            {
                $sql = "insert into mysql.db (Host, Db, User, Select_priv, Insert_priv, Update_priv, Delete_priv, Create_priv, Drop_priv, Grant_priv, References_priv, Index_priv, Alter_priv) VALUES ('$db_host', '$dbname', '$db_user', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y', 'Y')";
                @mysql_query($sql, $conn);
            }
            else
            {
                $bresult = false;
            }

            $params['db_create_status'] = $bresult ? STATUS_SUCCESS : STATUS_FAILED;
        }
        else
            $params['db_create_status'] = 'Already created.';

        if ($bresult)
        {
            if (!@mysql_select_db($db_name, $conn))
                $bresult = false;

            $params['db_connect_status'] = $bresult ? STATUS_SUCCESS : STATUS_FAILED;
        }

        if ($bresult)
            $params['table_create_status'] = SetupTableCreate($conn);
        else
            $params['table_create_status'] = '';
    }
    else
        $bresult = false;

    if ($conn != false)
        @mysql_close($conn);

    if ($bresult)
    {
        //Save to _defgenerate.php
        SystemGetConstant();

        $gSysConstant['DB_TYPE']     = DB_TYPE;
        $gSysConstant['DB_HOST']     = $db_host;
        $gSysConstant['DB_NAME']     = $db_name;
        $gSysConstant['DB_USER']     = $db_user;
        $gSysConstant['DB_PASSWORD'] = $db_password;

        SystemSaveVariables();
    }
    
    $content = LoadContentFile('scr_dbcreate_'.$lid.'.htm', $params);
    SetupScreen(SETUP_STAGE_DBCREATE_TITLE, $content, 'dbcreate', 'dbinfo', 'admin', $lid);
}

function SetupAdmin($lid)
{
    $params = array();
    $params['admin_name']     = 'admin';
    $params['admin_fullname'] = 'Administrator';
    $params['admin_email']    = 'webmaster@yourdomain.com';

    $content = LoadContentFile('scr_admin_'.$lid.'.htm', $params);

    SetupScreen(SETUP_STAGE_ADMIN_TITLE, $content, 'admin', 'dbinfo', 'sample', $lid);
}

function SetupCreateAdminAndSamplePages($lid)
{
    global $gHomePageUrl;
    global $gSysVar;
    global $db;
    global $gLogDBase;
    global $gLogVisitor;

    //set logging off
    $gLogDBase      = false;
    $gLogVisitor    = false;

    $db = new DBase();
    $db->Connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME); 


    $admin_name     = RequestGetValue('admin_name', '');
    $admin_fullname = RequestGetValue('admin_fullname', '');
    $admin_email    = RequestGetValue('admin_email', '');
    $admin_email    = RequestGetValue('admin_email', '');
    $admin_password = RequestGetValue('admin_password', '');
    $admin_password2= RequestGetValue('admin_password2', '');

    if (empty($admin_name) || empty($admin_fullname)  || empty($admin_email))
    {
        $content = SETUP_ERROR_ADMIN_INFO;
        SetupErrorScreen($content, 'admin', $lid);
        die();
    }

    if (empty($admin_password))
    {
        $content = SETUP_ERROR_ADMIN_PASSWORD;
        SetupErrorScreen($content, 'admin', $lid);
        die();
    }

    if ($admin_password != $admin_password2)
    {
        $content = SETUP_ERROR_ADMIN_PASSWORD;
        SetupErrorScreen($content, 'admin', $lid);
        die();
    }

    //success cheking admin info. Create guest and admin account 
    SetupDeleteAllData();
    SetupInitSysvarInt();

    SetupAddMember(0, GUEST_ACCOUNT, GUEST_ACCOUNT_FULLNAME, '', GUEST_LEVEL, $admin_password, '');

    $admin_id = DbGetUniqueID('sysmember');
    SetupAddMember($admin_id, $admin_name, $admin_fullname, $admin_email, WEBADMIN_LEVEL, $admin_password, $gHomePageUrl);


    SetupCreateSamplePagesEnglish($gSysVar['hp_name'], $gSysVar['hp_desc'], $gSysVar['hp_keywords'], $admin_name, $admin_email, $admin_id);
    SetupCreateSamplePagesIndonesia($gSysVar['hp_name'], $gSysVar['hp_desc'], $gSysVar['hp_keywords'], $admin_name, $admin_email, $admin_id);

    Header("Location: page.php?stage=finish&lid=$lid");
}

function SetupFinish($lid)
{
    $params = array();
    $params['chakraweb_url'] = '/index.html';

    $content = LoadContentFile('scr_finish_'.$lid.'.htm', $params);
    SetupScreen(SETUP_STAGE_FINISH_TITLE, $content, 'finish', 'admin', '', $lid);
}

// ----------------------------------------------------------------------
// Setup Utility Functions
// ----------------------------------------------------------------------
function SetupScreen($title, $content, $cur_stage, $prev_stage, $next_stage, $lid)
{
    global $gBaseLocalPath;

    $tplfile = $gBaseLocalPath.'setup/setup_screen_'.$lid.'.htm';
    
    $prev_stage_btn = empty($prev_stage) ? "" : "<input class=\"button\" type=\"submit\" value=\"".PREVIOUS_STAGE."\" name=\"previous\">";
    $next_stage_btn = empty($next_stage) ? "" : "<input class=\"button\" type=\"submit\" value=\"".NEXT_STAGE."\" name=\"next\">";

    $params = array();
    $params['page_title']   = $title;
    $params['page_content'] = $content;

    $params['prev_stage']   = $prev_stage;
    $params['next_stage']   = $next_stage;
    $params['cur_stage']    = $cur_stage;

    $params['prev_stage_btn']   = $prev_stage_btn;
    $params['next_stage_btn']   = $next_stage_btn;

    if ($cur_stage != 'start')
        $setup_params .= "<input type=\"hidden\" name=\"lid\" value=\"$lid\" />\n";

    $params['setup_params'] = $setup_params;

    echo LoadContentFile($tplfile, $params);
}

function SetupErrorScreen($content, $cur_stage, $lid)
{
    global $gBaseLocalPath;

    $tplfile = $gBaseLocalPath.'setup/setup_error_'.$lid.'.htm';
    
    $prev_stage_btn = "<input class=\"button\" type=\"submit\" value=\"".PREVIOUS_STAGE."\" name=\"previous\">";

    $params = array();
    $params['page_content'] = $content;
    $params['cur_stage']    = $cur_stage;
    $params['prev_stage']   = $cur_stage;
    $params['prev_stage_btn']   = $prev_stage_btn;
    $params['lid']          = $lid;

    echo LoadContentFile($tplfile, $params);
}

function ParseRequestPathAndFile($url, &$reqPath, &$reqFile)
{
	global $gBaseUrlPath;

    $url = StrUnEscape($url);
 
	$pos = strpos($url, "/");
	$x = strlen($url);
	
	while (is_integer($pos))
	{
		$x = $pos;
		$pos = strpos($url, "/", $x+1);
	}

	$reqPath = substr($url, 0, $x+1);
    $reqFile = substr($url, $x+1);

	if (strlen($gBaseUrlPath) > 0)
	{
		$prefix = substr($reqPath, 0, strlen($gBaseUrlPath));
		if (strcasecmp($prefix, $gBaseUrlPath) == 0)
			$reqPath = substr($reqPath, strlen($gBaseUrlPath));
	}

    $pos = strpos($reqFile, "?");
    if (is_integer($pos))
        $reqFile = substr($reqFile, 0, $pos);

    $reqFile = strtolower(trim($reqFile));
    if (empty($reqFile))
        $reqFile = "index.html";
}


function ChmodCheck($fname)
{
    global $gBaseLocalPath;

    $chkfile = $gBaseLocalPath.$fname;
    if (chmod($chkfile, 0666))
    {
        $out = "<p><img alt src=\"green_check.gif\" align=\"absMiddle\" border=\"0\" width=\"20\" height=\"22\">";
        $out .= sprintf(CHMOD_SUCCESS_FMT, $fname)."</p>\n";
    }
    else
    {
        $out = "<p><img alt src=\"red_check.gif\" align=\"absMiddle\" border=\"0\" width=\"20\" height=\"22\">";
        $out .= sprintf(CHMOD_FAILED_FMT, $fname)."</p>\n";
    }

    return $out;
}


function SetupTableCreate($conn)
{
    $sql_array = array (

"sysvarint" => "CREATE TABLE sysvarint
(
    var_key char(32) not null,
    var_data int(11) unsigned not null default 0,
    primary key (var_key)
);",


"syssessions" => "CREATE TABLE syssessions 
(
    sess_id char(32) not null,

    ip_addr char(20) not null,
    first_used int(11) unsigned not null,
    last_used int(11) unsigned not null,
    user_id int(3) unsigned not null default 0,

    sess_data text,
    primary key (sess_id)
);",


"sysuids" => "CREATE TABLE sysuids
(
    ui_code char(32) not null,
    ui_id int(3) unsigned not null,
    primary key (ui_code)
);",

"sysmember" => "CREATE TABLE sysmember
(
    m_id int(3) unsigned not null,
    m_level int(3) unsigned not null default 1,
    m_lid char(6) not null default 'en',
    m_ccode int(3) not null default 0,
    m_name varchar(32) not null,
    m_fullname varchar(255),
    m_password varchar(32) not null,
    m_email varchar(60),
    m_homepage varchar(255),
    m_startpage varchar(255) default '/index.html',

    m_view_email int(3) not null default 1,
    m_view_profile int(3) not null default 1,
    m_theme varchar(32) not null,

    m_photo int(3) not null default 0,
    m_desc text,
    m_page text,

    m_visit int(3) not null default 0,
    m_hits int(3) not null default 0,

    primary key (m_id),
    unique (m_name),
    unique(m_email)
);",

"web_folder" => "CREATE TABLE web_folder
(
    folder_lid char(6) not null,
    folder_id int(3) not null,
    folder_name varchar(255) not null,
    folder_label varchar(255) not null,
    folder_title varchar(255) not null,
    folder_desc text,
    folder_keywords text,
    folder_robots varchar(64) not null,
    folder_sidebar text,
    folder_parent int(3) not null,
    folder_show int(3) not null default 1,
    folder_active int(3) not null default 1,
    read_level int(3) not null default 0,
    write_level int(3) not null default 9,
    folder_order int(3) default 9999,

    upload_by varchar(20) not null,
    upload_on datetime not null default '1980-00-01 00:00:00',
    update_on datetime not null default '1980-00-01 00:00:00',

    primary key (folder_lid, folder_id),
    unique (folder_lid, folder_parent, folder_name)
);",

"web_page" => "CREATE TABLE web_page
(
    page_lid char(6) not null,
    page_id int(3) not null,
    folder_id int(3) not null,
    page_name varchar(255) not null,
    page_title varchar(255),
    page_desc text,
    page_keywords text,

    page_robots varchar(64) not null,
    page_author varchar(64) not null,

    page_content text,
    page_seealso_title varchar(255),
    page_seealso text,

    page_external int(3) not null default 0,
    page_src_title varchar(255),
    page_src_url varchar(255),
    page_src_home varchar(255),
    page_src_homeurl varchar(255),

    page_redirect varchar(255),

    page_show int(3) not null default 1,
    page_active int(3) not null default 1,
    page_order int(3) not null default 9999,
    page_type int(3) default 0,

    page_rating float default '0.0',
    page_votes int(3) unsigned default 0,
    page_hits int(3) unsigned default 0,

    upload_by varchar(20),
    upload_on datetime not null default '1980-00-01 00:00:00',
    update_on datetime not null default '1980-00-01 00:00:00',

    primary key (page_lid, page_id),
    unique (folder_id, page_lid, page_name)
);",

"advtext" => "CREATE TABLE advtext
(
    adv_key char(32) not null,
    adv_lid char(6) not null,
    adv_title varchar(255),
    adv_text text,
    adv_active int(3) not null default 1,
    adv_hits int(3) default 0,

    primary key (adv_key, adv_lid)
);",

"advrnd" => "CREATE TABLE advrnd
(
    adv_id int(3) unsigned not null,
    adv_key char(32) not null,
    adv_lid char(6) not null,
    adv_title varchar(255),
    adv_text text,
    adv_active int(3) not null default 1,
    adv_hits int(3) default 0,

    primary key (adv_id)
);",

"feedback" => "CREATE TABLE feedback
(
    fb_id int(3) unsigned not null,
    fb_lid char(6) not null,
    fb_fullname varchar(255),
    fb_email varchar(60),
    fb_content text,
    fb_ushow int(3) default 0,
    fb_utestimonial int(3) default 0,
    fb_show int(3) default 0,
    fb_testimonial int(3) default 0,
    upload_on datetime default '1980-00-01 00:00:00',
    primary key (fb_id)
);",

"news" => "CREATE TABLE news
(
    news_id int(3) unsigned not null,
    news_lid char(6) not null,
    news_title varchar(255),
    news_desc text,
    news_content text,
    news_show int(3) default 1,

    upload_on datetime default '1980-00-01 00:00:00',
    primary key (news_id)
);",

"comment" => "CREATE TABLE comment
(
    comm_id int(3) unsigned not null,
    page_lid char(6) not null,
    page_id int(3) not null,

    comm_content text,
    comm_show int(3) default 1,
    m_id int(3) unsigned not null,
    upload_on datetime default '1980-00-01 00:00:00',

    primary key (comm_id)
);",

"link" => "CREATE TABLE link
(
    link_id int(3) not null,
    page_lid char(6) not null,
    page_id int(3) not null,
    link_url varchar(255) not null,
    link_title varchar(255),
    link_desc text,

    link_note varchar(255),
    link_show int(3) default 0,
    link_active int(3) default 0,
    link_great int(3) default 0,
    link_order int(3) default 0,

    m_id int(3) unsigned not null,
    upload_on datetime not null default '1980-00-01 00:00:00',

    primary key (link_id),
    unique (page_lid, page_id, link_url)
);",

"macrotext" => "CREATE TABLE macrotext
(
    mac_key char(32) not null,
    mac_lid char(6) not null,
    mac_title varchar(255),
    mac_active int(3) not null default 1,
    mac_content text,
    primary key (mac_key, mac_lid)
);",

"service" => "CREATE TABLE service
(
    svc_id int(3) not null,
    svc_lid char(6) not null,
    svc_name varchar(255),
    svc_desc text,
    svc_default int(3) not null default 0,
    svc_level int(3) not null default 1,
    svc_order int(3) not null default 9999,
    svc_active int(3) not null default 1,
    primary key (svc_id)
);",

"svcmember" => "CREATE TABLE svcmember
(
    svc_id int(3) not null,
    m_id int(3) not null,
    primary key (svc_id, m_id)
);",

    );

    $out = '';
    foreach($sql_array as $tblname => $sql)
    {
        if (@mysql_query($sql, $conn))
            $status = STATUS_OK;
        else
            $status = STATUS_FAILED.'. '.@mysql_error();

        $out .= "<tr><td width=\"26%\">$tblname</td><td width=\"74%\">$status</td></tr>\n";
    }

    $sql = "CREATE INDEX link_folder_idx on web_folder (folder_name, folder_parent);";
    @mysql_query($sql, $conn);

    return $out;
}


function SetupDeleteAllData()
{
    DbExecute('delete from sysvarint');
    DbExecute('delete from syssessions');
    DbExecute('delete from sysuids');
    DbExecute('delete from sysmember');
    DbExecute('delete from web_page');
    DbExecute('delete from web_folder');
    DbExecute('delete from news');
    DbExecute('delete from link');
    DbExecute('delete from service');
    DbExecute('delete from svcmember');
    DbExecute('delete from feedback');
    DbExecute('delete from comment');
    DbExecute('delete from advtext');
    DbExecute('delete from advrnd');
    DbExecute('delete from macrotext');
}

// ----------------------------------------------------------------------
// SetupInitSysvarInt
// ----------------------------------------------------------------------
function SetupInitSysvarInt()
{
    DbSetIntVar('hp_hits', 0);
    DbSetIntVar('hp_visitors', 0);
    DbSetIntVar('hp_visited_since', time());
}


// ----------------------------------------------------------------------
// SetupCreateSamplePagesEnglish
// ----------------------------------------------------------------------
function SetupCreateSamplePagesEnglish($hp_name, $hp_desc, $hp_keywords, $admin, $admin_email, $admin_id)
{
	global $gBaseLocalPath;

    $terms_of_use   = ReadLocalFile($gBaseLocalPath.'setup/terms_of_use_en.htm', $errmsg);
    $privacy_policy = ReadLocalFile($gBaseLocalPath.'setup/privacy_policy_en.htm', $errmsg);
    $macro_help_card= ReadLocalFile($gBaseLocalPath.'setup/macro_help_card_en.htm', $errmsg);
    $start_page     = ReadLocalFile($gBaseLocalPath.'setup/start_page_en.htm', $errmsg);

    $news_sidebar = '
<DIV class=title>Other News</DIV>
<DIV id=sbtext>{news_list2:0:10}<A href="/News/index.html">Complete List...</A> </DIV>
<DIV id=sbspace></DIV>{login_form} {macro:feedback} 
<DIV id=sbspace></DIV>{powered_by_chakraweb}';

    $article_sidebar = '
{article_menu:ARTICLE LIST} {login_form} {search_form} {macro:feedback} 
{powered_by_chakraweb}';

    SetupCreatePage('en', 0, 'index.html', 'Welcome to '.$hp_name, $hp_desc, $hp_keywords, $admin, $start_page);
    SetupCreatePage('en', 0, 'aboutus.html', 'About '.$hp_name, '', $hp_keywords.', about', $admin, "
<div id=right>{see_also}{advrnd:example}</div>
<h1>{page_title}</h1>\n
<p>Describe here all about you and $hp_name, your mission, etc.</p>\n
<h2>A Brief History of $hp_name</h2>\n<p>Describe here A Brief History of $hp_name</p>\n");

    SetupCreatePage('en', 0, 'sitemap.html', $hp_name.' Sitemap', 'Sitemap of '.$hp_name, $hp_keywords.', sitemap', $admin, "<div id=right>{see_also}{advrnd:example}</div>\n<h1>{page_title}</h1>\n\n{sitemap:3:1}");

    SetupCreatePage('en', 0, 'linktous.html', 'How Link To Us', '', $hp_keywords.', link to us', $admin, "<div id=right>{see_also}{advrnd:example}</div>\n<h1>{page_title}</h1>\n
<p>Describe here several way to make link to this website</p>");

    SetupCreatePage('en', 0, 'terms_of_use.html', 'Term Of Use', '', $hp_keywords.', term, use', $admin, "<div id=right>{see_also}{advrnd:example}</div>\n".$terms_of_use);

    SetupCreatePage('en', 0, 'privacy_policy.html', 'Privacy Policy', '', $hp_keywords.', privacy, policy', $admin, "<div id=right>{see_also}{advrnd:example}</div>\n".$privacy_policy);

    SetupCreatePage('en', 0, 'macro_help_card.html', 'Macro Help Card', 'Macro Help Card list all macros available on ChakraWeb websites.', $hp_keywords.', macro, help, card', $admin, $macro_help_card);

    $folder_id = SetupCreateFolder('en', 0, 'Feedback', 'Feedback', 'This page contain feedback from our visitors', $hp_keywords.', feedback');    
    if ($folder_id > 0)
    {
        SetupCreatePage('en', $folder_id, 'index.html', 'Feedback', '', $hp_keywords.', feedback', $admin, "
<div id=right>{see_also}{advrnd:example}</div>\n
<h1>{page_title}</h1>\n
<b>{member_fullname}</b>, We welcome your feedback! Please send suggestions, ideas, and 
comments to: <a href=\"mailto:$admin_email\">$admin_email</a>, or fill our feedback form below.</p>
{feedback_list:0:10}{feedback_form}");

        SetupAddFeedback('en', 'SomeOne', 'someone@domain.com', 'Wow! this website is wonderful. I guest that this website is create by <a href="http://chakra.quick4all.com/Products/ChakraWeb/">ChakraWeb</a>. Am I right?');
    }

    $folder_id = SetupCreateFolder('en', 0, 'Articles', 'Articles', '', $hp_keywords.', article, articles', $article_sidebar);    
    if ($folder_id > 0)
    {
        SetupCreatePage('en', $folder_id, 'index.html', $hp_name.' Articles', '', $hp_keywords.', article, articles', $admin, "<div id=right>{see_also}{advrnd:example}</div>\n<h1>{page_title}</h1>\n{article_list}");
        SetupCreatePage('en', $folder_id, 'ex_profile.html', 'Example: Profiled Article', 'This example show you how to create an article that show author profile.', $hp_keywords.', article, articles, example', $admin, "
<div id=right>{author_profile}{see_also}</div>\n\n<h1>{page_title}</h1>\n{style:author:\$page_author}{style:info:\$page_desc}<p>Enter the content here</p>
<p><b>Notes:</b> If you <a href=\"/members/".$admin.".html?op=edit\">upload your photo</a>, you will see it on this page. :)</p>
{comment_list:0:5:VISITOR COMMENTS}{comment_form}{rating_form}");
        SetupCreatePage('en', $folder_id, 'ex_link.html', 'Example: Related Links', 'This example show you how to create an article that show related links.', $hp_keywords.', article, articles, example', $admin, "
<div id=right>{see_also}{link_list2:/Links/:Related Links}{advrnd:example}</div>\n
<h1>{page_title}</h1>\n{style:author:\$page_author}{style:info:\$page_desc}<p>Enter the content here</p>
{comment_list:0:5:VISITOR COMMENTS}{comment_form}{rating_form}");
    }

    $folder_id = SetupCreateFolder('en', 0, 'News', 'News', '', $hp_keywords.', news', $news_sidebar);    
    if ($folder_id > 0)
    {
        SetupCreatePage('en', $folder_id, 'index.html', $hp_name.' News', '', $hp_keywords.', news', $admin, "<div id=right>{see_also}{advrnd:example}</div>\n<h1>{page_title}</h1>\n{news_list:0:10} {news_form}");
        SetupAddNews('en', 'Sample News', "This is the description of $hp_name news", "<p>Enter your news content here.</p>");
    }

    $folder_id = SetupCreateFolder('en', 0, 'Links', 'Links', '', $hp_keywords.', link, resource');    
    if ($folder_id > 0)
    {
        $page_id = SetupCreatePage('en', $folder_id, 'index.html', $hp_name.' Links', '', $hp_keywords.', link, resource', $admin, "<div id=right>{see_also}{advrnd:example}</div>\n<h1>{page_title}</h1>\n{link_list}{link_form}");
        SetupAddLink('en', $page_id, 'http://chakra.quick4all.com/', 'Quick4All.com', QUICK4ALL_DESC_ENG, 'EXCELENT, GRAB IT', $admin_id);
    }
  
    $folder_id = SetupCreateFolder('en', 0, 'afd', 'Affiliate', 'Affiliate Directories', $hp_keywords.', affiliate, directories');
    if ($folder_id > 0)
    {
        SetupCreatePage('en', $folder_id, 'index.html', 'Affiliate Directories', '', $hp_keywords.', affiliate, directories', $admin, "<h1>{page_title}</h1>\n{redirect_table}<br clear=all>{redirect_form}");
        SetupCreatePage('en', $folder_id, 'quick4all.html', 'Quick4All Homepage', 'If you click the link on the name column, you will jump to quick4all.com, and the hits increment by one. :)', $hp_keywords, $admin, "", "http://chakra.quick4all.com/");
    }

    //add some advertizing here

    SetupAddAdvText('en', 'example', 'Advertizing Text', '
Do you know that Quick4All\'s <a href="http://chakra.quick4all.com/Products/Chakra/">Chakra Browser</a> 
can be used to collecting links before you go online? 
<p>Download now. <b>It\'s Freeware</b>.:)');

    SetupAddAdvRandom('en', 'example', 'Random Advertizing', '
Introducing <a href="http://chakra.quick4all.com/Products/Chakra/">Chakra Browser</a>, 
an easy way to surfing the internet. Search and collect the links before you go online.
<p>Download now. <b>It\'s Freeware</b>.:)');
    
    SetupAddAdvRandom('en', 'example', 'Random Advertizing', '
Do you know that Quick4All\'s <a href="http://chakra.quick4all.com/Products/Chakra/">Chakra Browser</a> 
can be used to collecting links before you go online? 
<p>Download now. <b>It\'s Freeware</b>.:)');

    SetupAddAdvRandom('en', 'example', 'Random Advertizing', '
I like to recommend you using Quick4All\'s <a href="http://chakra.quick4all.com/Products/Chakra/">Chakra Browser</a>. 
It\'s provide many offline contents and resource directories. 
<p>Download now. <b>It\'s Freeware</b>.:)');

    //add macro
    SetupAddMacro('en', 'feedback', 'Feedback', '
<p><b>{member_fullname}, <br>We welcome your feedback!</b></p>
<p>Send suggestions, ideas, and comments to: <a href="mailto:'.$admin_email.'">'.$admin_email.'</a>, 
or fill our <a href="/Feedback/index.html">Feedback Form</a>.</p>
');

	SetupAddMemberService('en', 'News', 'We will send you any news from our website.', 1, 1, 1);
}

// ----------------------------------------------------------------------
// SetupCreateSamplePagesIndonesia
// ----------------------------------------------------------------------
function SetupCreateSamplePagesIndonesia($hp_name, $hp_desc, $hp_keywords, $admin, $admin_email, $admin_id)
{
	global $gBaseLocalPath;

    $terms_of_use    = ReadLocalFile($gBaseLocalPath.'setup/terms_of_use_id.htm', $errmsg);
    $privacy_policy  = ReadLocalFile($gBaseLocalPath.'setup/privacy_policy_id.htm', $errmsg);
    $macro_help_card = ReadLocalFile($gBaseLocalPath.'setup/macro_help_card_id.htm', $errmsg);
    $start_page     = ReadLocalFile($gBaseLocalPath.'setup/start_page_id.htm', $errmsg);

    $news_sidebar = '
<DIV class=title>KABAR LAIN</DIV>
<DIV id=sbtext>{news_list2:0:10}<A href="/News/index.html">Selengkapnya...</A> </DIV>
<DIV id=sbspace></DIV>{login_form} {macro:feedback} 
<DIV id=sbspace></DIV>{powered_by_chakraweb}';

    $article_sidebar = '
{article_menu:DAFTAR ARTIKEL} {login_form} {search_form} {macro:feedback} 
{powered_by_chakraweb}';

    SetupCreatePage('id', 0, 'index.html', 'Selamat Datang di '.$hp_name, $hp_desc, $hp_keywords, $admin, $start_page);
    SetupCreatePage('id', 0, 'aboutus.html', 'Tentang Kami', '', $hp_keywords.', tentang, kami', $admin, "
<div id=right>{see_also}{advrnd:example}</div>
<h1>{page_title}</h1>\n
<p>Deskripsikan disini tentang anda dan website $hp_name: misi anda mendirikan website ini, siapa jati diri anda, dsb</p>\n
<h2>Sejarah $hp_name</h2>\n<p>Deskripsikan disini tonggak-tonggak bersejarah dari $hp_name</p>\n");

    SetupCreatePage('id', 0, 'sitemap.html', 'Sitemap '.$hp_name, 'Sitemap dari '.$hp_name, $hp_keywords.', sitemap', $admin, "<div id=right>{see_also}{advrnd:example}</div>\n<h1>{page_title}</h1>\n\n{sitemap:3:1}");

    SetupCreatePage('id', 0, 'linktous.html', 'Bagaimana Membuat Link ke Website Ini', '', $hp_keywords.', link to us', $admin, "<div id=right>{see_also}{advrnd:example}</div>\n<h1>{page_title}</h1>\n
<p>Deskripsikan disini berbagai cara yang dapat digunakan orang lain membuat link ke website ini sesuai dengan yang anda kehendaki</p>");

    SetupCreatePage('id', 0, 'terms_of_use.html', 'Aturan Penggunaan', '', $hp_keywords.', aturan, penggunaan', $admin, "<div id=right>{see_also}{advrnd:example}</div>\n".$terms_of_use);

    SetupCreatePage('id', 0, 'privacy_policy.html', 'Kebijakan Personal', '', $hp_keywords.', kebijakan, personal', $admin, "<div id=right>{see_also}{advrnd:example}</div>\n".$privacy_policy);

    SetupCreatePage('id', 0, 'macro_help_card.html', 'Kartu Bantuan Makro', 'Berisi daftar semua makro yang saat ini bisa digunakan untuk menciptakan halaman dinamis di ChakraWeb.', $hp_keywords.', kartu, bantuan, makro', $admin, $macro_help_card);

    $folder_id = SetupCreateFolder('id', 0, 'Feedback', 'Tanggapan', 'Halaman ini berisi beberapa tanggapan dari pengunjung website ini.', $hp_keywords.', feedback');    
    if ($folder_id > 0)
    {
        SetupCreatePage('id', $folder_id, 'index.html', 'Tanggapan Pengunjung', '', $hp_keywords.', tanggapan, komentar', $admin, "
<div id=right>{see_also}{advrnd:example}</div>\n
<h1>{page_title}</h1>\n
<b>{member_fullname}</b>, Kami senang dengan tanggapan anda! Silakan kirimkan pendapat, ide, dan
komentar anda ke: <a href=\"mailto:$admin_email\">$admin_email</a>, atau isi form tanggapan yang kami sediakan di bawah halaman ini.</p>
{feedback_list:0:10}{feedback_form}");

        SetupAddFeedback('id', 'SomeOne', 'someone@domain.com', 'Wow! website ini sangat luar biasa. Saya berani bertaruh, anda pasti menggunakan CMS <a href="http://chakra.quick4all.com/Products/ChakraWeb/">ChakraWeb</a>. Benar kan?');
    }

    $folder_id = SetupCreateFolder('id', 0, 'Articles', 'Artikel', '', $hp_keywords.', artikel', $article_sidebar);    
    if ($folder_id > 0)
    {
        SetupCreatePage('id', $folder_id, 'index.html', 'Daftar Artikel '.$hp_name, '', $hp_keywords.', artikel', $admin, "<div id=right>{see_also}{advrnd:example}</div>\n<h1>{page_title}</h1>\n{article_list}");

        SetupCreatePage('id', $folder_id, 'ex_profile.html', 'Contoh: Artikel Berprofil', 'Contoh ini menunjukkan bagaimana membuat artikel yang memperlihatkan profil pengarang di sebelah kanan halaman.', $hp_keywords.', artikel, contoh', $admin, "
<div id=right>{author_profile}{see_also}</div>\n\n<h1>{page_title}</h1>\n{style:author:\$page_author}{style:info:\$page_desc}<p>Ketikkan isi artikel disini</p>
<p><b>Catatan:</b> Jika anda <a href=\"/members/".$admin.".html?op=edit\">mengupload foto</a>, anda akan melihatnya di halaman ini. :)</p>
{comment_list:0:5:KOMENTAR PENGUNJUNG}{comment_form}{rating_form}");

        SetupCreatePage('id', $folder_id, 'ex_link.html', 'Contoh: Link Terkait', 'Contoh ini menunjukkan bagaimana membuat artikel yang memperlihatkan link yang terkait di sebelah kanan halaman.', $hp_keywords.', artikel, contoh', $admin, "
<div id=right>{see_also}{link_list2:/Links/:Link Terkait}{advrnd:example}</div>\n
<h1>{page_title}</h1>\n{style:author:\$page_author}{style:info:\$page_desc}<p>Ketikkan isi artikel disini</p>
{comment_list:0:5:KOMENTAR PENGUNJUNG}{comment_form}{rating_form}");
    }

    $folder_id = SetupCreateFolder('id', 0, 'News', 'Kabar Baru', '', $hp_keywords.', kabar, baru', $news_sidebar);    
    if ($folder_id > 0)
    {
        SetupCreatePage('id', $folder_id, 'index.html', 'Kabar Baru '.$hp_name, '', $hp_keywords.', kabar, baru', $admin, "<div id=right>{see_also}{advrnd:example}</div>\n<h1>{page_title}</h1>\n{news_list:0:10} {news_form}");
        SetupAddNews('id', 'Contoh Kabar', "Ini adalah deskripsi kabar baru dari $hp_name.", "<p>Isikan kabar baru secara lengkap disini.</p>");
    }

    $folder_id = SetupCreateFolder('id', 0, 'Links', 'Link', '', $hp_keywords.', link, resource');    
    if ($folder_id > 0)
    {
        $page_id = SetupCreatePage('id', $folder_id, 'index.html', $hp_name.' Links', '', $hp_keywords.', link, resource', $admin, "<div id=right>{see_also}{advrnd:example}</div>\n<h1>{page_title}</h1>\n{link_list}{link_form}");
        SetupAddLink('id', $page_id, 'http://chakra.quick4all.com/', 'Quick4All.com', QUICK4ALL_DESC_IND, 'WOW, DOWNLOAD SAJA', $admin_id);
    }
  
    $folder_id = SetupCreateFolder('id', 0, 'afd', 'Afiliasi', 'Direktori Afiliasi', $hp_keywords.', direktori, afiliasi');
    if ($folder_id > 0)
    {
        SetupCreatePage('id', $folder_id, 'index.html', 'Direktori Afiliasi', '', $hp_keywords.', direktori, afiliasi', $admin, "<h1>{page_title}</h1>\n{redirect_table}<br clear=all>{redirect_form}");
        SetupCreatePage('id', $folder_id, 'quick4all.html', 'Homepage Quick4All', 'Jika anda mengklik link yang ada pada Kolom Nama, maka anda akan dilontarkan ke quick4all.com, dan jumlah hits bertambah satu. :)', $hp_keywords, $admin, "", "http://chakra.quick4all.com/");
    }

    //add some advertizing here

    SetupAddAdvText('id', 'example', 'Teks Iklan', '
Tahukan anda bahwa <a href="http://chakra.quick4all.com/Products/Chakra/">Chakra Browser</a> 
buatan Quick4All dapat digunakan untuk mengkoleksi link bahkan sebelum pergi online? 
<p>Download sekarang juga, mumpung <b>GRATIS</b>. :)');

    SetupAddAdvRandom('id', 'example', 'Iklan Random', '
Memperkenalkan <a href="http://chakra.quick4all.com/Products/Chakra/">Chakra Browser</a>: 
Cara mudah untuk surfing ke internet. <i>Cari dan koleksi alamat dulu, baru online</i>.
<p>Download sekarang juga, mumpung <b>GRATIS</b>.:)');
    
    SetupAddAdvRandom('id', 'example', 'Iklan Random', '
Tahukan anda bahwa <a href="http://chakra.quick4all.com/Products/Chakra/">Chakra Browser</a> 
buatan Quick4All dapat digunakan untuk mengkoleksi link bahkan sebelum pergi online? 
<p>Download sekarang juga, mumpung <b>GRATIS</b>.:)');

    SetupAddAdvRandom('id', 'example', 'Iklan Random', '
Saya merekomendasikan anda untuk menggunakan <a href="http://chakra.quick4all.com/Products/Chakra/">Chakra Browser</a> 
buatan Quick4All. Browser ini menyediakan banyak website offline dan daftar alamat internet menarik. 
<p>Download sekarang juga, mumpung <b>GRATIS</b>.:)');

    //add macro
    SetupAddMacro('id', 'feedback', 'Tanggapan', '
<p><b>{member_fullname}, <br>Kami senang dengan tanggapan anda!</b></p>
<p>Kirimkan pendapat, ide, atau komentar anda ke: <a href="mailto:'.$admin_email.'">'.$admin_email.'</a>, 
atau isi <a href="/Feedback/index.html">Form Tanggapan</a> yang kami sediakan.</p>
');

	SetupAddMemberService('id', 'Kabar Baru', 'Kami akan mengirimkan email tentang kabar baru dari website ini.', 1, 1, 1);
}

// ----------------------------------------------------------------------
// SetupAddMember
// ----------------------------------------------------------------------
function SetupAddMember($uid, $name, $fullname, $email, $level, $password, $hpage)
{
    global $db;

    $lid   = DEFAULT_LID;

    $values  = "$uid, $level,";
    $values .= $db->qstr($lid).',';
    $values .= $db->qstr($name).',';
    $values .= $db->qstr($fullname).',';
    $values .= $db->qstr($email).',';
    $values .= '0,0,';
    $values .= $db->qstr($hpage).',';
    $values .= $db->qstr(md5($password));

    $columns = 'm_id, m_level, m_lid, m_name, m_fullname, m_email, m_view_email, m_view_profile, m_homepage, m_password';
    return DbSqlInsert('sysmember', $columns, $values);
}

// ----------------------------------------------------------------------
// SetupCreateFolder
// ----------------------------------------------------------------------
function SetupCreateFolder($lid, $parent_id, $folder_name, $folder_title, $folder_desc, $folder_keywords, $folder_sidebar='')
{
	global $db;

	$folder_id = DbGetUniqueID('web_folder');

    $columns = 'folder_lid, folder_name, folder_label, folder_title, folder_desc, 
                folder_keywords, folder_id, folder_parent, folder_sidebar';

    $values  = $db->qstr($lid).','.$db->qstr($folder_name);
    $values .= ','.$db->qstr($folder_title).','.$db->qstr($folder_title);
    $values .= ','.$db->qstr($folder_desc).','.$db->qstr($folder_keywords);
    $values .= ", $folder_id, $parent_id";
    $values .= ','.$db->qstr($folder_sidebar);

    if (!DbSqlInsert('web_folder', $columns, $values))
        $folder_id = -1;

    return $folder_id;
}

// ----------------------------------------------------------------------
// SetupCreatePage
// ----------------------------------------------------------------------
function SetupCreatePage($lid, $folder_id, $name, $title, $desc, $keywords, $uname, $content, $redirect='')
{
    global $db;

    if ($lid == 'en')
    {
        $seealso_title = 'See Also';
        $seealso_text  = 'This text will show on the right side of your page. Use it to add related information.';
    }
    else
    {
        $seealso_title = 'Lihat Juga';
        $seealso_text  = 'Teks ini akan tampil di sebelah kanan halaman. Gunakan untuk menambahkan informasi yang terkait.';
    }

    $page_id = DbGetUniqueID('web_page');
    $columns = 'folder_id, page_id, page_lid, page_name, page_title, page_desc, page_keywords, 
                page_redirect, page_robots, page_content, page_author, page_order, page_show, page_active, 
                upload_by, upload_on, update_on, page_seealso_title, page_seealso';

    $values = $folder_id.',';
    $values .= $page_id.',';
    $values .= $db->qstr($lid).',';
    $values .= $db->qstr($name).',';
    $values .= $db->qstr($title).',';
    $values .= $db->qstr($desc).',';
    $values .= $db->qstr($keywords).',';
    $values .= $db->qstr($redirect).',';
    $values .= $db->qstr(DEFAULT_ROBOTS).',';
    $values .= $db->qstr($content).',';
    $values .= $db->qstr($uname).',';

    if (strcmp($name, 'index.html') == 0)
        $values .= '1, 0, 1, ';
    else
        $values .= '1, 1, 1, ';

    $utime = date("YmdHis", time());
    $values .= $db->qstr($uname).',';
    $values .= $utime.','.$utime.',';

    $values .= $db->qstr($seealso_title).',';
    $values .= $db->qstr($seealso_text);

    if (!DbSqlInsert('web_page', $columns, $values))
        $page_id = -1;

    return $page_id;    
}

// ----------------------------------------------------------------------
// SetupAddNews
// ----------------------------------------------------------------------
function SetupAddNews($lid, $title, $desc, $content)
{
    global $db;

    $columns = 'news_id, news_lid, news_title, news_desc, news_content, upload_on';
    $values  = DbGetUniqueID('news');
    $values .= ','.$db->qstr($lid);
    $values .= ','.$db->qstr($title);
    $values .= ','.$db->qstr($desc);
    $values .= ','.$db->qstr($content);
    $values .= ','.date("YmdHis", time());

    DbSqlInsert('news', $columns, $values);
}

// ----------------------------------------------------------------------
// SetupAddFeedback
// ----------------------------------------------------------------------
function SetupAddFeedback($lid, $name, $email, $content)
{
    global $db;

    $columns = 'fb_id, fb_lid, fb_fullname, fb_email, fb_content, fb_show, fb_testimonial, upload_on';
    $values  = DbGetUniqueID('feedback');
    $values .= ','.$db->qstr($lid);
    $values .= ','.$db->qstr($name);
    $values .= ','.$db->qstr($email);
    $values .= ','.$db->qstr($content);
    $values .= ',1, 0';
    $values .= ','.date("YmdHis", time());

    DbSqlInsert('feedback', $columns, $values);
}

// ----------------------------------------------------------------------
// SetupAddLink
// ----------------------------------------------------------------------
function SetupAddLink($lid, $page_id, $url, $title, $desc, $note, $uid)
{
    global $db;

    $columns = 'link_id, page_id, page_lid, link_url, link_title, link_desc, link_note, 
                link_order, link_active, link_show, link_great, m_id, upload_on';

    $values  = DbGetUniqueID('link');
    $values .= ','.$page_id;
    $values .= ','.$db->qstr($lid);
    $values .= ','.$db->qstr($url);
    $values .= ','.$db->qstr($title);
    $values .= ','.$db->qstr($desc);
    $values .= ','.$db->qstr($note);

    $values .= ', 1, 1, 1, 1';
    $values .= ','.$uid;
    $values .= ','.date("YmdHis", time());

    DbSqlInsert('link', $columns, $values);
}

// ----------------------------------------------------------------------
// SetupAddAdvText
// ----------------------------------------------------------------------
function SetupAddAdvText($lid, $adv_key, $adv_title, $adv_text)
{
    global $db;

    $columns = 'adv_key, adv_lid, adv_title, adv_text';

    $values  = $db->qstr($adv_key);
    $values .= ','.$db->qstr($lid);
    $values .= ','.$db->qstr($adv_title);
    $values .= ','.$db->qstr($adv_text);

    DbSqlInsert('advtext', $columns, $values);
}

// ----------------------------------------------------------------------
// SetupAddAdvRandom
// ----------------------------------------------------------------------
function SetupAddAdvRandom($lid, $adv_key, $adv_title, $adv_text)
{
    global $db;

    $columns = 'adv_id, adv_key, adv_lid, adv_title, adv_text';
    $values  = DbGetUniqueID('advrnd');
    $values .= ','.$db->qstr($adv_key);
    $values .= ','.$db->qstr($lid);
    $values .= ','.$db->qstr($adv_title);
    $values .= ','.$db->qstr($adv_text);

    DbSqlInsert('advrnd', $columns, $values);
}

// ----------------------------------------------------------------------
// SetupAddMacro
// ----------------------------------------------------------------------
function SetupAddMacro($lid, $mac_key, $mac_title, $mac_content)
{
    global $db;

    $columns = 'mac_key, mac_lid, mac_title, mac_content';

    $values  = $db->qstr($mac_key);
    $values .= ','.$db->qstr($lid);
    $values .= ','.$db->qstr($mac_title);
    $values .= ','.$db->qstr($mac_content);

    DbSqlInsert('macrotext', $columns, $values);
}

function SetupAddMemberService($lid, $name, $desc, $default, $level, $order)
{
    global $db;

	$svc_id = DbGetUniqueID('service');

	$columns = 'svc_id, svc_lid, svc_name, svc_desc, svc_default, svc_level, svc_order';
	$values  = $svc_id.','.$db->qstr($lid);
	$values .= ','.$db->qstr($name).','.$db->qstr($desc);
	$values .= ",$default,$level,$order";

	DbSqlInsert('service', $columns, $values);
}


?>