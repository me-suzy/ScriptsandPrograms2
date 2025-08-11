<?php 
// ----------------------------------------------------------------------
// ModName: sysvar.php
// Purpose: Change the sysvar table content.
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
$gPageNavigation[] = array($gHomePageUrl."/phpmod/sysvar.php", _NAV_SYSVAR_EDIT);


$op = RequestGetValue('op', 'show');

switch ($op)
{
case 'save':
    SysvarSave();
	break;

case 'show':
default:
	SysvarShowForm();
	break;
}

function SysvarShowForm()
{
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;
    global $gSysVar;

    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
    $gWebPage['page_title']     = _SYSVAR_EDIT_TITLE;
    $gWebPage['page_desc']      = '';
    $gWebPage['page_keywords']  = '';

    $gWebPage['fld_hpname']     = $gSysVar['hp_name'];
    $gWebPage['fld_hpslogan']   = $gSysVar['hp_slogan'];
    $gWebPage['fld_hpdesc']     = $gSysVar['hp_desc'];
    $gWebPage['fld_hpkeywords'] = $gSysVar['hp_keywords'];

    $gWebPage['fld_hpheader_editor'] = RenderHtmlEditor('fld_hpheader', $gSysVar['hp_header'], 'chmini', 440, 90);
    $gWebPage['fld_hpfooter_editor'] = RenderHtmlEditor('fld_hpfooter', $gSysVar['hp_footer'], 'chmini', 440, 90);
    $gWebPage['fld_hpsidebar_editor'] = RenderHtmlEditor('fld_hpsidebar', $gSysVar['hp_sidebar'], 'chmini', 440, 90);

    $gWebPage['fld_help_box'] = RenderHelpBox(_URL_HELP_SYSVAR_EDIT);
    DoShowPageWithContent(TPL_WEB_PAGE, 'sysvar.htm');
}

function SysvarSave()
{
    global $gSysVar;

    CheckRequestRandom();

    SystemGetConstant();

    $gSysVar['hp_name']     = RequestGetValue('fld_hpname', '', CLEAN_ALL);
    $gSysVar['hp_slogan']   = RequestGetValue('fld_hpslogan', '', CLEAN_ALL);
    $gSysVar['hp_desc']     = RequestGetValue('fld_hpdesc', '', CLEAN_ALL);
    $gSysVar['hp_keywords'] = RequestGetValue('fld_hpkeywords', '', CLEAN_ALL);

    $gSysVar['hp_header'] = RequestGetValue('fld_hpheader', '', CLEAN_SAVE);
    $gSysVar['hp_footer'] = RequestGetValue('fld_hpfooter', '', CLEAN_SAVE);
    $gSysVar['hp_sidebar'] = RequestGetValue('fld_hpsidebar', '', CLEAN_SAVE);


    $gSysVar['hp_keywords'] = FixKeywords($gSysVar['hp_keywords']);

    SystemSaveVariables();

    Header("Location: /phpmod/cpanel.php");
}



?>
