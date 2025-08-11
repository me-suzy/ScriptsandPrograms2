<?php 
// ----------------------------------------------------------------------
// ModName: folder_del.php
// Purpose: Process folder delete
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

SetDynamicContent();
DBGetFolderData($gFolderId);
CheckOpForAdminOnly();

if ($gFolderId == 0)
    WebPageError(_HPAGE_DELETE_TITLE, _HPAGE_DELETE_MESSAGE);

$gRequestPath = FindPathFromFolderId($gFolderId);
$gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
$gRequestFile = 'index.html';
$gPageId = 0;


$op = RequestGetValue('op', 'show');

switch ($op)
{
case 'do':
	DoFolderDelete();
	break;
case 'show':
default:
	FolderDeleteConfirm();
	break;
}

function FolderDeleteConfirm()
{
    global $gWebPage;
    global $gFolderId, $gPageId;
    global $gFolder;
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;

    $gWebPage['page_id']      = $gPageId;

    $gWebPage['folder_id']    = $gFolder['id'];
    $gWebPage['fld_name']  = $gFolder['name'];
    $gWebPage['fld_title'] = $gFolder['title'];
    $gWebPage['fld_desc']  = $gFolder['desc'];

    $gWebPage['url_delete']  = "/phpmod/folder_del.php?op=do&cat=$gFolderId";

    $gWebPage['page_header'] = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer'] = WebContentParse($gHomePageFooter);
    $gWebPage['page_title'] = sprintf(_FOLDER_DELETE_TITLE, $gFolder['title']);
    $gWebPage['page_sidebar'] = RenderPageSidebar();

    DoShowPageWithContent(TPL_WEB_PAGE, 'folder_del.htm');
}


function DoFolderDelete()
{
    global $gRequestPath;
    global $gFolderId;

    
    $lid = UserGetLID();
    DBFolderDelete($gFolderId, $lid);
    
    $parent_path = GetParentPath($gRequestPath);
    Header("Location: $parent_path");
}

function DBFolderDelete($folder_id, $lid)
{
    global $db;

    //first: delete all web_page in this folder
    $where = "folder_id=$folder_id and page_lid=".$db->qstr($lid);
    if (!DbSqlDelete('web_page', $where))
        return false;

    //second: delete all children of the folder
    $arfolder = array();
    $sql = "select folder_id from web_folder where folder_parent=$folder_id and folder_lid=".$db->qstr($lid);
    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        while (!$rs->EOF)
        {
            $arfolder[] = $rs->fields[0];        
            $rs->MoveNext();
        }
    }

    reset($arfolder);
    foreach($arfolder as $folder_child)
    {
        if (!DBFolderDelete($folder_child, $lid))
            return false;
    }

    //third: delete the folder it self
    $where = "folder_id=$folder_id and folder_lid=".$db->qstr($lid);
    return DbSqlDelete('web_folder', $where);
}


?>
