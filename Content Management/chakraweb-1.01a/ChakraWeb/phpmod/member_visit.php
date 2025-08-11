<?php 
// ----------------------------------------------------------------------
// ModName: member_visit.php
// Purpose: Show member visit table
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
$gPageNavigation[] = array($gHomePageUrl."/phpmod/member_visit.php", _NAV_MEMBER_VISIT);


$op = RequestGetValue('op', 'show');

switch ($op)
{
case 'reset':
    MemberVisitResetCounter();
	break;

case 'show':
default:
	MemberVisitShowTable();
	break;
}

function MemberVisitShowTable()
{
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;
    global $gSysVar, $gSysVarInt;

    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
    $gWebPage['page_title']     = _MEMBER_VISIT_TITLE;
    $gWebPage['page_desc']      = '';
    $gWebPage['page_keywords']  = '';

    $order = RequestGetValue('ord', 'fullname');

    $content  = '<h2>'._MEMBER_VISIT_TITLE.'</h2>';
    $content .= RenderMemberVisitTable($order);

    $gWebPage['page_content']  = $content;
    DoShowPage(TPL_WEB_PAGE);
}

function MemberVisitResetCounter()
{
    $sql = 'update sysmember set m_visit=0, m_hits=0';
    DbExecute($sql);

    MemberVisitShowTable();
}

function RenderMemberVisitTable($order)
{
    global $gCountryList;

    InitCountryList();

    $out = '';

    $sql = "select m_name, m_ccode, m_fullname, m_visit, m_hits from sysmember order by m_$order";
    $rs = DbExecute($sql);

    if ($rs && !$rs->EOF)
    {
        $total_visits = 0;
        $total_hits = 0;

        $out .= '
<table border="1" cellspacing="0" cellpadding="2" bordercolor="#FFFFFF" bordercolorlight="#C0C0C0" bordercolordark="#FFFFFF" width="80%">
  <tr>
    <td id="tbl_title" height="24">'.HRef('/phpmod/member_visit.php?op=show&ord=fullname', _FLD_FULLNAME).'</td>
    <td id="tbl_title" height="24">'.HRef('/phpmod/member_visit.php?op=show&ord=visit', _FLD_VISIT).'</td>
    <td id="tbl_title" height="24">'.HRef('/phpmod/member_visit.php?op=show&ord=hits', _FLD_HITS).'</td>
  </tr>';

        while (!$rs->EOF)
        {
            $url_profile = '/members/'.$rs->fields[0].'.html';

            $out .= '<tr>
    <td id="tbl_text" valign="top" align="left">'.HRef($url_profile, $rs->fields[2]).', '.$gCountryList[$rs->fields[1]].'</td>
    <td id="tbl_text" valign="top" align="right">'.$rs->fields[3].'</td>
    <td id="tbl_text" valign="top" align="right">'.$rs->fields[4].'</td>
  </tr>';

            $total_visits += $rs->fields[3];
            $total_hits   += $rs->fields[4];

            $rs->MoveNext();
        }

            $out .= '<tr>
    <td id="tbl_bottom" valign="middle" align="right" height="20"><b>TOTAL</b></td>
    <td id="tbl_bottom" valign="middle" align="right" height="20"><b>'.$total_visits.'</b></td>
    <td id="tbl_bottom" valign="middle" align="right" height="20"><b>'.$total_hits.'</b></td>
  </tr>
</table>';


        $out .= _MEMBER_VISIT_RESET;
    }    

    return $out;
}


?>
