<?php 
// ----------------------------------------------------------------------
// ModName: folder_move.php
// Purpose: Process folder move
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

SetDynamicContent();
DBGetFolderData($gFolderId);
CheckOpForAdminOnly();

if ($gFolderId == 0)
    WebPageError(_HPAGE_MOVE_TITLE, _HPAGE_MOVE_MESSAGE);

$gRequestPath = FindPathFromFolderId($gFolderId);
$gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
$gRequestFile = 'index.html';
$gPageId = 0;


$op = RequestGetValue('op', 'show');

switch ($op)
{
case 'do':
	DoFolderMove();
	break;
case 'show':
default:
	FolderMoveConfirm();
	break;
}

function FolderMoveConfirm()
{
    global $gWebPage;
    global $gFolderId, $gPageId;
    global $gFolder;
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;
    global $gRequestPath;

    $gWebPage['page_id']      = $gPageId;

    $gWebPage['folder_id']    = $gFolder['id'];
    $gWebPage['fld_name']  = $gFolder['name'];
    $gWebPage['fld_title'] = $gFolder['title'];
    $gWebPage['fld_desc']  = $gFolder['desc'];

    $gWebPage['fld_parent_path']  = GetParentPath($gRequestPath);
    $gWebPage['form_action']  = "/phpmod/folder_move.php";

    $gWebPage['page_header'] = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer'] = WebContentParse($gHomePageFooter);
    $gWebPage['page_title'] = sprintf(_FOLDER_DELETE_TITLE, $gFolder['title']);
    $gWebPage['page_sidebar'] = RenderPageSidebar();

    DoShowPageWithContent(TPL_WEB_PAGE, 'folder_move.htm');
}


function DoFolderMove()
{
    global $gFolderId;
    global $db;
    global $gRequestPath, $gRequestFile;

    CheckRequestRandom();

    $parent_path = RequestGetValue('fld_parent_path', '');
    if (!StrIsStartWith($parent_path, "\/"))
        $parent_path = "/".$parent_path;

    if (!StrIsEndWith($parent_path, "\/"))
        $parent_path .= "/";

    $folder_parent = FindFolderIdFromPath($parent_path);
    
    if ($folder_parent >= 0)
    {
        $lid = UserGetLID();
        $colvalues = "folder_parent=$folder_parent";
        $where = "folder_id=$gFolderId and folder_lid=".$db->qstr($lid);
        DbSqlUpdate('web_folder', $colvalues, $where);
    
        $redirect = FindPathFromFolderId($gFolderId);
    }
    else
    {
        $redirect = $gRequestPath.$gRequestFile;
    }

    Header("Location: $redirect");
}



?>
