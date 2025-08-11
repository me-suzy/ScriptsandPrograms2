<?php 
// ----------------------------------------------------------------------
// ModName: cpanel.php
// Purpose: Control Panel for User and Administrator
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

SetDynamicContent();

if (!IsUserLogin())
	RedirectToPreviousPage();

$gFolderId = 0;
$gRequestPath = '/'; //FindPathFromFolderId($gFolderId);
$gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
$gRequestFile = 'index.html';
$gPageId = RequestGetValue('id', 0);

DBGetFolderData(0);

$gPageNavigation = array();
$gPageNavigation[] = array($gHomePageUrl.$gBaseUrlPath."/index.html", _NAV_FRONTPAGE);
$gPageNavigation[] = array($gHomePageUrl."/phpmod/cpanel.php", _NAV_CONTROL_PANEL);

$gWebPage['page_sidebar']   = RenderPageSidebar();
$gWebPage['page_header']    = WebContentParse($gHomePageHeader);
$gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
$gWebPage['page_title']     = _CONTROL_PANEL_TITLE;

$minfo = MemberGetInfo(UserGetID(), '');
if ($minfo)
{
    $gWebPage['fld_id']        = $minfo['m_id'];
    $gWebPage['fld_name']      = $minfo['m_name'];
    $gWebPage['fld_fullname']  = $minfo['m_fullname'];

    if ($minfo['m_view_email'])
        $gWebPage['fld_email'] = HRef('mailto:'.$minfo['m_email'], $minfo['m_email']);
    else
        $gWebPage['fld_email'] = '-';

    if (!empty($minfo['m_homepage']))
        $gWebPage['fld_hpage'] = HRef($minfo['m_homepage'], $minfo['m_homepage']);
    else
        $gWebPage['fld_hpage'] = '-';
}

$fname = IsUserAdmin() ? 'cpanel_adm.htm' : 'cpanel.htm';
DoShowPageWithContent(TPL_WEB_PAGE, $fname);

?>
