<?php 
// ----------------------------------------------------------------------
// ModName: find_page.php
// Purpose: Redirect to the proper web page
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

SetDynamicContent();

$op   = RequestGetValue('op', 'show');
$lid  = RequestGetValue('lid', '');
if (empty($lid))
    $lid = UserGetLID();

$folder_id = RequestGetValue('cat', 0);
$page_name = RequestGetValue('name', '');

if (empty($page_name))
{
    $page_id = RequestGetValue('id', 0);

    DbGetPageNameAndFolderFromId($page_id, $page_name, $folder_id);
}

$path = FindPathFromFolderId($folder_id, $lid);

Header("Location: $path$page_name?op=$op");



function DbGetPageNameAndFolderFromId($page_id, &$page_name, &$folder_id)
{
    $folder_id = 0;
    $page_name = '';

    $sql = "select folder_id, page_name from web_page where page_id=$page_id";
    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $folder_id = $rs->fields[0];
        $page_name = $rs->fields[1];
    }
    
    //PrintLine($folder_id);
    //PrintLine($page_name);
}


?>
