<?php 
// ----------------------------------------------------------------------
// ModName: member_startpage.php
// Purpose: Change startpage or member
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

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
$gPageNavigation[] = array($gHomePageUrl."/phpmod/member_startpage.php", _NAV_MEMBER_STARTPAGE);


$op = RequestGetValue('op', 'show');

switch ($op)
{
case 'do':
	StartPageUpdate();
	break;
case 'show':
default:
	StartPageShowForm(true);
	break;
}

function StartPageShowForm($binit, $errmsg='')
{
    global $gFolder;
    global $gFolderId;
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;

    SetDynamicContent();

    if ($binit)
    {
        //only admin can change other user info

        if (IsUserAdmin())
        {
            $uid = RequestGetValue('uid', 0);
            if ($uid == 0)
                $uid = UserGetID();
        }
        else
        {
            $uid = UserGetID();
        }

        $gWebPage['uid'] = $uid;

        $minfo = MemberGetInfo($uid, '');
        if ($minfo)
        {
            $gWebPage['fld_startpage'] = $minfo['m_startpage'];
            $gWebPage['fld_name']      = $minfo['m_name'];
        }
        else
        {
            $gWebPage['fld_startpage'] = '';
            $gWebPage['fld_name'] = '';
        }
    }


    $gWebPage['form_action'] = "/phpmod/member_startpage.php";

    $gWebPage['page_header'] = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer'] = WebContentParse($gHomePageFooter);
    $gWebPage['page_title'] = _MEMBER_STARTPAGE_TITLE;
    $gWebPage['page_sidebar'] = RenderPageSidebar();
    $gWebPage['page_desc']      = '';
    $gWebPage['page_keywords']  = '';

    if (empty($errmsg))
        $gWebPage['page_message'] = _MEMBER_STARTPAGE_MESSAGE;
    else
        $gWebPage['page_message'] = $errmsg;

    DoShowPageWithContent(TPL_WEB_PAGE, 'member_startpage.htm');
}


function StartPageUpdate()
{
    global $db;
    global $gWebPage;

    if (StartPageCheck($errmsg))
    {
        $colvalues =  'm_startpage='.$db->qstr($gWebPage['fld_startpage']);
        $where =  'm_id='.$db->qstr($gWebPage['uid']);

        if (DbSqlUpdate('sysmember', $colvalues, $where))
        {
            Header("Location: /phpmod/cpanel.php");
            die();
        }
    }

    StartPageShowForm(false, $errmsg);
}

function StartPageCheck(&$errmsg)
{
    global $gWebPage;

    $gWebPage['uid']            = RequestGetValue('uid', 0);
    $gWebPage['fld_startpage']  = RequestGetValue('fld_startpage', '');
    $gWebPage['fld_name']       = RequestGetValue('fld_name', '');

    if (empty($gWebPage['fld_startpage']))
        $gWebPage['fld_startpage'] = '/index.html';

    if (!IsWebPageExist($gWebPage['fld_startpage']))
    {
        $errmsg = sprintf(_PAGE_NOTFOUND_MESSAGE, $gWebPage['fld_startpage']);
        return false;
    }

    return true;
}



?>
