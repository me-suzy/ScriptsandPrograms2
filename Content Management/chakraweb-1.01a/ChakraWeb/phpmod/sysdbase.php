<?php 
// ----------------------------------------------------------------------
// ModName: sysdbase.php
// Purpose: Update database setting
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
$gPageNavigation[] = array($gHomePageUrl."/phpmod/sysdbase.php", _NAV_SYSDBASE_EDIT);


$op = RequestGetValue('op', 'show');

switch ($op)
{
case 'save':
	SysDBaseSave();
	break;
case 'show':
default:
	SysDBaseShowForm(true);
	break;
}

function SysDBaseShowForm($bclear, $errmsg='')
{
    global $gWebPage;
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;

    $gWebPage['page_header'] = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer'] = WebContentParse($gHomePageFooter);
    $gWebPage['page_title'] = _SYSDBASE_EDIT_TITLE;
    $gWebPage['page_sidebar'] = RenderPageSidebar();

    if ($bclear)
    {
        $gWebPage['fld_host'] = DB_HOST;
        $gWebPage['fld_name'] = DB_NAME;
        $gWebPage['fld_user'] = DB_USER;
    }

    $gWebPage['fld_page_message'] = $errmsg;

    DoShowPageWithContent(TPL_WEB_PAGE, 'sysdbase.htm');
}


function SysDBaseSave()
{
    global $gWebPage;
    global $gSysConstant;

    if (!SysDBaseCheck($errmsg))
    {
        SysDBaseShowForm(false, $errmsg);
        die();
    }

    SystemGetConstant();

    $gSysConstant['DB_TYPE']     = DB_TYPE;
    $gSysConstant['DB_HOST']     = $gWebPage['fld_host'];
    $gSysConstant['DB_NAME']     = $gWebPage['fld_name'];
    $gSysConstant['DB_USER']     = $gWebPage['fld_user'];
    $gSysConstant['DB_PASSWORD'] = $gWebPage['fld_password'];

    SystemSaveVariables();
    Header("Location: /phpmod/cpanel.php");
}

function SysDBaseCheck(&$errmsg)
{
    global $gWebPage;

    $gWebPage['fld_host']       = RequestGetValue('fld_host', '');
    $gWebPage['fld_name']       = RequestGetValue('fld_name', '');
    $gWebPage['fld_user']       = RequestGetValue('fld_user', '');
    $gWebPage['fld_password']   = RequestGetValue('fld_password', '');
    $gWebPage['fld_password2']  = RequestGetValue('fld_password2', '');

    if (empty($gWebPage['fld_host']))
    {
        $errmsg = _ERR_DBHOST_EMPTY;
        return false;
    }

    if (empty($gWebPage['fld_name']))
    {
        $errmsg = _ERR_DBNAME_EMPTY;
        return false;
    }

    if (empty($gWebPage['fld_user']))
    {
        $errmsg = _ERR_DBUSER_EMPTY;
        return false;
    }

    if (!empty($gWebPage['fld_password']))
    {
        if ($gWebPage['fld_password'] != $gWebPage['fld_password2'])
        {
            $errmsg = _ERR_INVALID_PASSWORD2;
            return false;
        }
    }
    else
        $gWebPage['fld_password'] = DB_PASSWORD;

    $conn = @mysql_connect($gWebPage['fld_host'], $gWebPage['fld_user'], $gWebPage['fld_password']);
    if ($conn === false)
    {
        $errmsg = _ERR_UNABLE_TO_CONNECT_DBASE_HOST;
        return false;
    }

    if (!@mysql_select_db($gWebPage['fld_name'], $conn))
    {
        $errmsg = _ERR_UNABLE_TO_CONNECT_DBASE;
        return false;
    }

    return true;
}


?>
