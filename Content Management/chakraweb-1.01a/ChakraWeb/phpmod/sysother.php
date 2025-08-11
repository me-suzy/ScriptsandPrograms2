<?php 
// ----------------------------------------------------------------------
// ModName: sysother.php
// Purpose: Update other system variables
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

SetDynamicContent();
CheckOpForAdminOnly();

$gFolderId = 0;
$gRequestPath = '/'; //FindPathFromFolderId($gFolderId);
$gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
$gRequestFile = 'index.html';
$gPageId = RequestGetValue('id', 0);

DBGetFolderData(0);

$gPageNavigation = array();
$gPageNavigation[] = array($gHomePageUrl.$gBaseUrlPath."/index.html", _NAV_FRONTPAGE);
$gPageNavigation[] = array($gHomePageUrl."/phpmod/cpanel.php", _NAV_CONTROL_PANEL);
$gPageNavigation[] = array($gHomePageUrl."/phpmod/sysother.php", _NAV_SYSOTHER_EDIT);


$op = RequestGetValue('op', 'show');

switch ($op)
{
case 'save':
	SysOtherSave();
	break;
case 'show':
default:
	SysOtherShowForm(true);
	break;
}

function SysOtherShowForm($bclear, $errmsg='')
{
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;
    global $gLogDBase;
    global $gLogVisitor;
    global $gHomePageUrl;
    global $gLanguageList;
    global $gThemeList;

    $gWebPage['page_header'] = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer'] = WebContentParse($gHomePageFooter);
    $gWebPage['page_title'] = _SYSOTHER_EDIT_TITLE;
    $gWebPage['page_sidebar'] = RenderPageSidebar();

    if ($bclear)
    {
        $gWebPage['fld_hp_url']    = $gHomePageUrl;

        $gWebPage['fld_smtp_type'] = MAIL_TYPE;
        $gWebPage['fld_smtp_host'] = SMTP_HOST;
        $gWebPage['fld_smtp_helo'] = SMTP_HELO;
        $gWebPage['fld_smtp_port'] = SMTP_PORT;
        $gWebPage['fld_logdb']     = $gLogDBase;
        $gWebPage['fld_logvisitor']     = $gLogVisitor;

        $gWebPage['fld_logdb_prefix'] = DBLOG_PREFIX;
        $gWebPage['fld_logvisitor_prefix'] = STATLOG_PREFIX;

        $gWebPage['fld_theme'] = DEFAULT_THEME;
        $gWebPage['fld_lang']  = DEFAULT_LID;
        $gWebPage['fld_order'] = DEFAULT_ORDER;
        $gWebPage['fld_robots']  = DEFAULT_ROBOTS;

        $gWebPage['fld_item_per_page'] = MAX_ITEM_PERPAGE;
    }

    $gWebPage['fld_page_message'] = $errmsg;

    $gWebPage['fld_smtp_type_select'] = ComboBoxFromArray1(array('mail', 'smtp'), 'fld_smtp_type', $gWebPage['fld_smtp_type']);
    $gWebPage['fld_theme_select']     = ComboBoxFromArray1($gThemeList, 'fld_theme', $gWebPage['fld_theme']);
    $gWebPage['fld_language_select']  = ComboBoxFromArray($gLanguageList, 'fld_lang', $gWebPage['fld_lang']);

    $gWebPage['fld_chk_logdb']        = CheckBox('fld_logdb', '1', $gWebPage['fld_logdb']);
    $gWebPage['fld_chk_logvisitor']   = CheckBox('fld_logvisitor', '1', $gWebPage['fld_logvisitor']);

    DoShowPageWithContent(TPL_WEB_PAGE, 'sysother.htm');
}


function SysOtherSave()
{
    global $gWebPage;
    global $gSysConstant;
    global $gLogDBase;
    global $gHomePageUrl;
    global $gLogVisitor;
    global $gLogDBase;

    if (!SysOtherCheck($errmsg))
    {
        SysOtherShowForm(false, $errmsg);
        die();
    }

    SystemGetConstant();

    $gHomePageUrl = $gWebPage['fld_hp_url'];

    $gSysConstant['MAIL_TYPE']   = $gWebPage['fld_smtp_type'];
    $gSysConstant['SMTP_HOST']   = $gWebPage['fld_smtp_host'];
    $gSysConstant['SMTP_HELO']   = $gWebPage['fld_smtp_helo'];
    $gSysConstant['SMTP_PORT']   = $gWebPage['fld_smtp_port'];

    $gLogDBase = $gWebPage['fld_logdb'];
    $gLogVisitor = $gWebPage['fld_logvisitor'];

    $gSysConstant['DBLOG_PREFIX']     = $gWebPage['fld_logdb_prefix'];
    $gSysConstant['STATLOG_PREFIX']   = $gWebPage['fld_logvisitor_prefix'];

    $gSysConstant['DEFAULT_THEME']   = $gWebPage['fld_theme'];
    $gSysConstant['DEFAULT_LID']     = $gWebPage['fld_lang'];
    $gSysConstant['DEFAULT_ORDER']   = $gWebPage['fld_order'];
    $gSysConstant['DEFAULT_ROBOTS']   = $gWebPage['fld_robots'];

    $gSysConstant['MAX_ITEM_PERPAGE']   = $gWebPage['fld_item_per_page'];

    SystemSaveVariables();
    Header("Location: /phpmod/cpanel.php");
}

function SysOtherCheck(&$errmsg)
{
    global $gWebPage;

    $gWebPage['fld_hp_url']    = RequestGetValue('fld_hp_url', '');

    $gWebPage['fld_smtp_type'] = RequestGetValue('fld_smtp_type', '');
    $gWebPage['fld_smtp_host'] = RequestGetValue('fld_smtp_host', '');
    $gWebPage['fld_smtp_helo'] = RequestGetValue('fld_smtp_helo', '');
    $gWebPage['fld_smtp_port'] = RequestGetValue('fld_smtp_port', 0);
    $gWebPage['fld_logdb']     = RequestGetValue('fld_logdb', 0);
    $gWebPage['fld_logvisitor']= RequestGetValue('fld_logvisitor', 0);

    $gWebPage['fld_logdb_prefix'] = RequestGetValue('fld_logdb_prefix', '');
    $gWebPage['fld_logvisitor_prefix'] = RequestGetValue('fld_logvisitor_prefix', '');

    $gWebPage['fld_theme'] = RequestGetValue('fld_theme', '');
    $gWebPage['fld_lang']  = RequestGetValue('fld_lang', '');
    $gWebPage['fld_order'] = RequestGetValue('fld_order', 0);
    $gWebPage['fld_robots']= RequestGetValue('fld_robots', '');

    $gWebPage['fld_item_per_page'] = RequestGetValue('fld_item_per_page', 0);


    return true;
}


?>
