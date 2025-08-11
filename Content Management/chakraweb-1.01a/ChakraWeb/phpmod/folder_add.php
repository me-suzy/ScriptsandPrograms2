<?php 
// ----------------------------------------------------------------------
// ModName: folder_add.php
// Purpose: Process subfolder addition
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

SetDynamicContent();
DBGetFolderData($gFolderId);

if ($gFolderId < 0 || !IsUserCanWrite())
	RedirectToPreviousPage();

$gRequestPath = FindPathFromFolderId($gFolderId);
$gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
$gRequestFile = 'index.html';

//PrintLine($gCurrentUrlPath, '$gCurrentUrlPath');
//die();

$op = RequestGetValue('op', 'show');

switch ($op)
{
case 'do':
	DoFolderAddNew();
	break;
case 'show':
default:
	FolderAddNewForm(true);
	break;
}

function FolderAddNewForm($bdbase, $errmsg='')
{
    global $gFolder;
    global $gFolderId;
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;


    if ($bdbase)
    {
        $gWebPage['fld_lid']     = $gFolder['lid'];
        $gWebPage['fld_id']      = $gFolder['id'];
        $gWebPage['fld_name']    = '';
        $gWebPage['fld_label']   = '';
        $gWebPage['fld_title']   = '';
        $gWebPage['fld_desc']    = '';
        $gWebPage['fld_keywords']= $gFolder['keywords'];
        $gWebPage['fld_robots']  = $gFolder['robots'];
        $gWebPage['fld_sidebar'] = $gFolder['sidebar'];
        $gWebPage['fld_order']   = DEFAULT_ORDER; //$gFolder['order'];

        $gWebPage['chk_show']    = CheckBox('fld_show', '1', $gFolder['show']);
        $gWebPage['chk_active']  = CheckBox('fld_active', '1', $gFolder['active']);

        $gWebPage['read_level']     = $gFolder['read_level'];
        $gWebPage['write_level']    = $gFolder['write_level'];
    }
    else
    {
        $gWebPage['fld_lid']     = $gFolder['lid'];
        $gWebPage['fld_id']      = $gFolder['id'];
        $gWebPage['fld_name']    = $gFolder['name'];
        $gWebPage['fld_label']   = $gFolder['label'];
        $gWebPage['fld_title']   = $gFolder['title'];
        $gWebPage['fld_desc']    = $gFolder['desc'];
        $gWebPage['fld_keywords']= $gFolder['keywords'];
        $gWebPage['fld_robots']  = $gFolder['robots'];
        $gWebPage['fld_sidebar'] = $gFolder['sidebar'];
        $gWebPage['fld_order']   = $gFolder['order'];

        $gWebPage['chk_show']    = CheckBox('fld_show', '1', $gFolder['show']);
        $gWebPage['chk_active']  = CheckBox('fld_active', '1', $gFolder['active']);

        $gWebPage['read_level']     = $gFolder['read_level'];
        $gWebPage['write_level']    = $gFolder['write_level'];
    }

    $gWebPage['form_action'] = "/phpmod/folder_add.php";

    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
    $gWebPage['page_sidebar']   = '';
    $gWebPage['page_title']     = _FOLDER_ADD_TITLE;
    $gWebPage['page_desc']      = $gWebPage['fld_desc'];
    $gWebPage['page_keywords']  = $gWebPage['fld_keywords'];

    $gWebPage['page_message'] = $errmsg;

    $gWebPage['fld_sidebar_editor'] = RenderHtmlEditor('fld_sidebar', $gWebPage['fld_sidebar'], 'chmini', 600, 90);

    $gWebPage['folder_id'] = $gWebPage['fld_id'];

    $gWebPage['fld_help_box'] = RenderHelpBox(_URL_HELP_ADD_FOLDER);

    DoShowPageWithContent(TPL_SIMPLE_PAGE, 'folder_add.htm');
}

function DoFolderAddNew()
{
    global $db;
    global $gCurrentUrlPath;
    global $gFolder;

    CheckRequestRandom();

    if (FolderAttrCheck($errmsg))
    {
        $folder_new_id = DbGetUniqueID('web_folder');

        $columns = 'folder_lid, folder_id, folder_name, folder_label, folder_title, folder_desc,
                    folder_keywords, folder_robots, folder_sidebar, folder_order,
                    folder_show, folder_active, read_level, write_level, folder_parent,
                    upload_by, upload_on, update_on';

        $values  = $db->qstr($gFolder['lid']).',';
        $values .= $folder_new_id.',';
        $values .= $db->qstr($gFolder['name']).',';
        $values .= $db->qstr($gFolder['label']).',';
        $values .= $db->qstr($gFolder['title']).',';
        $values .= $db->qstr($gFolder['desc']).',';
        $values .= $db->qstr($gFolder['keywords']).',';
        $values .= $db->qstr($gFolder['robots']).',';
        $values .= $db->qstr($gFolder['sidebar']).',';
        $values .= $gFolder['order'].',';
        $values .= $gFolder['show'].',';
        $values .= $gFolder['active'].',';
        $values .= $gFolder['read_level'].',';
        $values .= $gFolder['write_level'].',';
        $values .= $gFolder['id'].',';

        $utime = date("YmdHis", time());
        $values .= $db->qstr(UserGetName()).',';
        $values .= $utime.','.$utime;


        if (DbSqlInsert('web_folder', $columns, $values))
        {
            $page_id = DbGetUniqueID('web_page');
            $columns = 'folder_id, page_id, page_lid, page_name, page_title, page_desc, page_keywords, 
                        page_robots, page_content, page_author, page_active, page_show, upload_by, upload_on, update_on';

            $values = $folder_new_id.',';
            $values .= $page_id.',';
            $values .= $db->qstr($gFolder['lid']).',';
            $values .= $db->qstr('index.html').',';
            $values .= $db->qstr($gFolder['title']).',';
            $values .= $db->qstr($gFolder['desc']).',';
            $values .= $db->qstr($gFolder['keywords']).',';
            $values .= $db->qstr($gFolder['robots']).',';
            $values .= $db->qstr(DEFAULT_PAGE_CONTENT).',';
            $values .= $db->qstr(UserGetName()).',';
            $values .= '1, 0, ';

            $utime = date("YmdHis", time());
            $values .= $db->qstr(UserGetName()).',';
            $values .= $utime.','.$utime;

            if (DbSqlInsert('web_page', $columns, $values))
            {
                Header("Location: $gCurrentUrlPath".$gFolder['name'].'/index.html');
                die();
            }
        }

        $errmsg = 'Unable to add folder data. '.$db->ErrorMsg();
    }

    FolderAddNewForm(false, $errmsg);
}

function FolderAttrCheck($errmsg)
{
    global $gFolder;

    $gFolder['lid']     = RequestGetValue('fld_lid', DEFAULT_LID);
    $gFolder['id']      = RequestGetValue('fld_id', 0);
    $gFolder['name']    = RequestGetValue('fld_name', '', CLEAN_ALL);
    $gFolder['label']   = RequestGetValue('fld_label', '', CLEAN_ALL);
    $gFolder['title']   = RequestGetValue('fld_title', '', CLEAN_ALL);
    $gFolder['desc']    = RequestGetValue('fld_desc', '', CLEAN_ALL);
    $gFolder['keywords']= RequestGetValue('fld_keywords', '', CLEAN_ALL);
    $gFolder['robots']  = RequestGetValue('fld_robots', DEFAULT_ROBOTS);
    $gFolder['sidebar'] = RequestGetValue('fld_sidebar', '', CLEAN_SAVE);
    $gFolder['order']   = RequestGetValue('fld_order', DEFAULT_ORDER);
    $gFolder['show']    = RequestGetValue('fld_show', 0);
    $gFolder['active']   = RequestGetValue('fld_active', 0);

    if (empty($gFolder['label']))
        $gFolder['label'] = $gFolder['name'];
    
    if (empty($gFolder['title']))
        $gFolder['title'] = $gFolder['name'];

    $gFolder['read_level']   = RequestGetValue('read_level', GUEST_LEVEL);
    $gFolder['write_level']  = RequestGetValue('write_level', WEBADMIN_LEVEL);

    $chk_id = GetFolderIdFromName($gFolder['name'], $gFolder['id'], $gFolder['lid']);
    if ($chk_id > 0)
    {
        $errmsg = _ERR_FOLDER_ALREADY_EXIST;
        return false;
    }     

    if (empty($gFolder['robots']))
        $gFolder['robots'] = DEFAULT_ROBOTS;

    $gFolder['keywords'] = FixKeywords($gFolder['keywords']);

    return true;
}



?>
