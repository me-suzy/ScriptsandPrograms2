<?php 
// ----------------------------------------------------------------------
// ModName: web_activate.php
// Purpose: Setting Maintenance Time
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
$gPageNavigation[] = array($gHomePageUrl."/phpmod/web_activate.php", _NAV_WEB_ACTIVATE);


$op = RequestGetValue('op', 'show');

switch ($op)
{
case 'active':
	WebActivate(true);
	break;
case 'noactive':
	WebActivate(false);
	break;
case 'show':
default:
	WebActivateShowStatus();
	break;
}


function WebActivateShowStatus()
{
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;
    global $gMaintenanceTime;

    $gWebPage['page_header'] = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer'] = WebContentParse($gHomePageFooter);
    $gWebPage['page_title'] = _WEB_ACTIVATE_TITLE;
    $gWebPage['page_sidebar'] = RenderPageSidebar();

    if ($gMaintenanceTime)
    {
        $gWebPage['fld_webstatus'] = _FLD_WEB_NOACTIVE;
        $gWebPage['fld_op']        = 'active';
        $gWebPage['fld_webreverse']= _FLD_WEB_ACTIVATE;
    }
    else
    {
        $gWebPage['fld_webstatus'] = _FLD_WEB_ACTIVE;
        $gWebPage['fld_op']        = 'noactive';
        $gWebPage['fld_webreverse']= _FLD_WEB_MAINTENANCE;
    }

    DoShowPageWithContent(TPL_WEB_PAGE, 'maintenance_time.htm');
}

function WebActivate($bactive)
{
    global $gMaintenanceTime;

    $gMaintenanceTime = !$bactive;

    SystemGetConstant();
    SystemSaveVariables();
    Header("Location: /phpmod/web_activate.php");

}


?>
