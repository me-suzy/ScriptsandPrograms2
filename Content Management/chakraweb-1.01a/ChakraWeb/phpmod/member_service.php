<?php 
// ----------------------------------------------------------------------
// ModName: member_service.php
// Purpose: Allow member to change the service provided
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
$gPageNavigation[] = array($gHomePageUrl."/phpmod/member_service.php", _NAV_MEMBER_SERVICE);


$op = RequestGetValue('op', 'show');

switch ($op)
{
case 'save':
	MemberServiceSave();
	break;
case 'show':
	MemberServiceShow();
	break;
default:
    WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_UNKNOWN_OPERATION);
	break;
}


function MemberServiceShow()
{
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;

    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
    $gWebPage['page_title']     = _MEMBER_SERVICE_TITLE;
    $gWebPage['page_desc']      = '';
    $gWebPage['page_keywords']  = '';

    $gWebPage['uid'] = UserGetID();
    $gWebPage['fld_member_service_list'] = RenderServiceList(UserGetID());

    DoShowPageWithContent(TPL_WEB_PAGE, 'member_service.htm');
}

function MemberServiceSave()
{
    $columns = 'svc_id, m_id';
    
    CheckRequestRandom();

    $uid = RequestGetValue('uid', 0);
    if ($uid != UserGetID())
        WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_NOT_USE_BROWSER);

    //we simply delete all service and build a new ones
    DbSqlDelete('svcmember', "m_id=$uid");

    $svc_count = RequestGetValue('item_count', 0);
    for ($i=1; $i<= $svc_count; $i++)
    {
        $svc_id = RequestGetValue('item_'.$i, 0);
        if ($svc_id > 0)
            DbSqlInsert('svcmember', $columns, "$svc_id, $uid");
    }

    Header("Location: /phpmod/cpanel.php");
}


function RenderServiceList($uid)
{
    global $db;

    $mselect = array();

    $sql = "select svc_id from svcmember where m_id=$uid";
    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        while (!$rs->EOF)
        {
            $mselect[] = $rs->fields[0];
            $rs->MoveNext();
        }
    }
    reset($mselect);

    $out = '';

    $sql  = 'select svc_id, svc_name, svc_desc from service where svc_lid='.$db->qstr(UserGetLID());
    $sql .= ' and svc_level<='.UserGetLevel().' order by svc_order, svc_name';

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $i = 1;
        while (!$rs->EOF)
        {
            $bselect = in_array($rs->fields[0], $mselect);
            $out .= "<tr><td valign=\"top\">".CheckBox('item_'.$i, $rs->fields[0], $bselect)."</td>";
            $out .= "<td valign=\"top\"><b>".$rs->fields[1]."</b>. <br>".$rs->fields[2]."</td></tr>\n";

            $i++;
            $rs->MoveNext();
        }

        $out .= "<input type=\"hidden\" name=\"item_count\" value=\"".($i-1)."\">\n";
    }

    return $out;
}


?>
