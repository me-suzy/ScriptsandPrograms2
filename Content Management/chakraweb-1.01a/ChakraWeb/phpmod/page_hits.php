<?php 
// ----------------------------------------------------------------------
// ModName: page_hits.php
// Purpose: Show page hits of several page on website
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");


SetDynamicContent();

if (!IsUserAdmin())
    WebPageError(_UNAUTHORISIZED_ACCESS_TITLE, _UNAUTHORISIZED_ACCESS_MESSAGE);

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


$op = RequestGetValue('op', 'show');
switch ($op)
{
case 'reset':
    PageHitsReset();
    break;
case 'doreset':
    DoPageHitsReset();
    break;
case 'show':
default:    
    PageHitsShow();
    break;
}

function PageHitsShow()
{
    global $db;
    global $gPageNavigation;
    global $gWebPage;
    global $gHomePageUrl;

    $lid = UserGetLID();
    $max   = RequestGetValue('max', 10);

    $order = strtolower(RequestGetValue('ord', 'desc'));
    if ($order != 'asc' && $order != 'desc')
        $order = 'desc';


    if ($order == 'desc')
    {
        $title   =  sprintf(_PAGE_HITS_DESC_TITLE_FMT, $max);
        $nav     =  _NAV_PAGE_HITS_DESC;
        $message =  _PAGE_HITS_DESC_MESSAGE;
    }
    else
    {
        $title   =  sprintf(_PAGE_HITS_ASC_TITLE_FMT, $max);
        $nav     =  _NAV_PAGE_HITS_ASC;
        $message =  _PAGE_HITS_ASC_MESSAGE;
    }

    $sql = "select folder_id, page_id, page_name, page_title, page_desc, page_hits from web_page where page_lid=".$db->qstr($lid)." order by page_hits $order limit 0,".$max;
    $rs = DbExecute($sql);
    $content = sprintf("<h1>%s</h1>%s\n", $title, $message).RenderWebPageHitsTableFromRS($rs);

    $gPageNavigation[] = array($gHomePageUrl."/phpmod/page_hits.php?max=".$max.'&ord='.$order, $nav);

    $gWebPage['page_title']     = $title;
    $gWebPage['page_content'] = $content;

    DoShowPage(TPL_WEB_PAGE);
}

function PageHitsReset()
{
    global $gPageNavigation;
    global $gWebPage;

    $gPageNavigation[] = array($gHomePageUrl."/phpmod/page_hits.php?op=reset", _NAV_PAGE_HITS_RESET);

    $gWebPage['page_title']   = _PAGE_HITS_RESET_TITLE;
    DoShowPageWithContent(TPL_WEB_PAGE, 'page_hits_reset.htm');
}

function DoPageHitsReset()
{
    $sql = 'update web_page set page_hits=0';
    DbExecute($sql);

    DbSetIntVar('hp_visitors', 0);
    DbSetIntVar('hp_hits', 0);
    DbSetIntVar('hp_visited_since', time());

    Header("Location: /phpmod/cpanel.php");
}


?>
