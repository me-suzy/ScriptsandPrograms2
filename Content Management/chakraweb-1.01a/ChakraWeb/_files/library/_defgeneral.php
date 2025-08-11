<?php
// ----------------------------------------------------------------------
// ModName: _defgeneral.php
// Purpose: General Definition
//          You can edit the content of these file if you want 
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_LIBRARY')) 
    die ("You can't access [_defgeneral.php] file directly...");


define(GUEST_UID, 0);			//guest id

define(GUEST_LEVEL, 0);
define(WEBADMIN_LEVEL, 9);

define(CLEAN_ALL, 2);
define(CLEAN_SAVE, 1);
define(CLEAN_NO, 0);

//templates
define(TPL_WEB_PAGE,        'wpage.htm');
define(TPL_SIMPLE_PAGE,     'spage.htm');
define(TPL_HDR_PAGE,        'header.htm');
define(TPL_BLANK_PAGE,      'blank.htm');

define(TPL_HOME_PAGE,       'hpage.htm');
define(TPL_FOLDER_ADD,      'folder_add.htm');
define(TPL_FOLDER_UPD,      'folder_upd.htm');
define(TPL_FOLDER_MOVE,     'folder_move.htm');
define(TPL_ITEM_ADD,        'item_add.htm');
define(TPL_ITEM_UPD,        'item_upd.htm');
define(TPL_LOGIN,           'login.htm');
define(TPL_ADMIN_HPAGE,     'admin_hpage.htm');
define(TPL_SEARCH_RESULT,   'search.htm');

//operating system
define(OS_WINDOWS, 10);
define(OS_UNIX, 20);


// ----------------------------------------------------------------------
// URL HELP
// ----------------------------------------------------------------------
define('_URL_HELP_EDIT_PAGE',       'http://localhost:891/Products/ChakraWeb/Docs/create_edit_page.html');
define('_URL_HELP_ADD_FOLDER',      'http://localhost:891/Products/ChakraWeb/Docs/add_folder.html');
define('_URL_HELP_EDIT_FOLDER',     'http://localhost:891/Products/ChakraWeb/Docs/edit_folder.html');
define('_URL_HELP_SYSVAR_EDIT',     '');

// ----------------------------------------------------------------------
// SQL FOR ACCESSING MEMBER INFO
// ----------------------------------------------------------------------
$sysmember_columns = 'm_id, m_level, m_lid, m_ccode, m_name, m_fullname, m_password, m_email, m_homepage, m_startpage, m_view_email, m_theme, m_photo, m_desc, m_page';

//base and image path 
$gBaseLocalPath = realpath("../").'/';
$gImageLocalPath = $gBaseLocalPath."images".'/';

$gReadLevel   = GUEST_LEVEL;
$gWriteLevel  = WEBADMIN_LEVEL;

//Current Page Navigation. Please don't change
$gCurrentPageNavigation = "";

$gMgmtMenu = true;

$gThemeList = array(
				'StdBlue',
				'StdGrey',
				'MagBlue',
				'MagGrey',
			);



?>
