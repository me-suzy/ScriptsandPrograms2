<?php 
// ----------------------------------------------------------------------
// ModName: 
// Purpose: 
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

DBGetFolderData($gFolderId);

if ($gFolderId < 0 || !IsUserCanWrite())
	RedirectToPreviousPage();

$gRequestPath = FindPathFromFolderId($gFolderId);
$gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
$gRequestFile = 'index.html';

$op = RequestGetValue('op', 'show');

switch ($op)
{
case 'do':
	DoSomething();
	break;
case 'show':
default:
	SomethingShow(true);
	break;
}

function SomethingShow($dbread)
{
    global $gWebPage;
    global $gFolderId;
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;

    SetDynamicContent();

    $gWebPage['form_action'] = "/phpmod/something.php";

    $gWebPage['page_header'] = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer'] = WebContentParse($gHomePageFooter);
    $gWebPage['page_title'] = 'NOT_YET';
    $gWebPage['page_sidebar'] = '';
    $gWebPage['page_content'] = 'NOT_YET';

    DoShowPage(TPL_WEB_PAGE);
}


function DoSomething()
{
    global $db;
    global $gCurrentUrlPath, $gRequestFile;
    global $gWebPage;

    if (FolderAttrCheck())
    {
        $colvalues =  'folder_name='.$db->qstr($gWebPage['name']);
        $colvalues .= ', folder_label='.$db->qstr($gWebPage['label']);
        $colvalues .= ', folder_title='.$db->qstr($gWebPage['title']);
        $colvalues .= ', folder_desc='.$db->qstr($gWebPage['desc']);
        $colvalues .= ', folder_keywords='.$db->qstr($gWebPage['keywords']);
        $colvalues .= ', folder_robots='.$db->qstr($gWebPage['robots']);
        $colvalues .= ', folder_sidebar='.$db->qstr($gWebPage['sidebar']);
        $colvalues .= ', folder_order='.$gWebPage['order'];

        $where =  'folder_lid='.$db->qstr($gWebPage['lid']);
        $where .= ' and folder_id='.$gWebPage['id'];

        if (DbSqlUpdate('web_folder', $colvalues, $where))
        {
            Header("Location: $gCurrentUrlPath$gRequestFile");
            die();
        }
    }

    FolderAttrShow(false);
}

function FolderAttrCheck()
{
    global $gWebPage;

    $gWebPage['lid']     = RequestGetValue('folder_lid', DEFAULT_LID);
    $gWebPage['id']      = RequestGetValue('folder_id', 0);
    $gWebPage['name']    = RequestGetValue('folder_name', '');
    $gWebPage['label']   = RequestGetValue('folder_label', '');
    $gWebPage['title']   = RequestGetValue('folder_title', '');
    $gWebPage['desc']    = RequestGetValue('folder_desc', '');
    $gWebPage['keywords']= RequestGetValue('folder_keywords', '');
    $gWebPage['robots']  = RequestGetValue('folder_robots', DEFAULT_ROBOTS);
    $gWebPage['sidebar'] = RequestGetValue('folder_sidebar', '');
    $gWebPage['order']   = RequestGetValue('folder_order', DEFAULT_ORDER);

    return true;
}



?>
