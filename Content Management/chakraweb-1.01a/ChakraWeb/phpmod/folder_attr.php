<?php 
// ----------------------------------------------------------------------
// ModName: folder_attr.php
// Purpose: Show Page and Process Updating Folder Attribute
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

SetDynamicContent();
DBGetFolderData($gFolderId);

$gFolder['oldname'] = $gFolder['name'];

$gRequestPath = FindPathFromFolderId($gFolderId);
$gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
$gRequestFile = 'index.html';

// check the autorization
if ($gFolderId < 0 || !IsUserCanWrite())
    WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_OPR_DENIED_MESSAGE);
	//RedirectToPreviousPage();

// check the autorization 2
if (!IsUserAdmin() && UserGetName() != $gFolder['upload_by'])
    WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_ADM_AUTHOR_ONLY);

$op = RequestGetValue('op', 'show');

switch ($op)
{
case 'do':
	DoFolderUpdateAttr();
	break;
case 'show':
default:
	FolderAttrShow(true);
	break;
}

function FolderAttrShow($dbread, $errmsg='')
{
    global $gFolder;
    global $gFolderId;
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;

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

    $gWebPage['page_header'] = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer'] = WebContentParse($gHomePageFooter);
    $gWebPage['form_action'] = "/phpmod/folder_attr.php";

    $gWebPage['page_title'] = _FOLDER_ATTR_TITLE;
    $gWebPage['page_desc'] = $gWebPage['fld_desc'];
    $gWebPage['page_keywords'] = $gWebPage['fld_keywords'];

    $gWebPage['page_message'] = $errmsg;

    $gWebPage['fld_sidebar_editor'] = RenderHtmlEditor('fld_sidebar', $gWebPage['fld_sidebar'], 'chmini', 600, 90);

    $gWebPage['fld_help_box'] = RenderHelpBox(_URL_HELP_EDIT_FOLDER);

    DoShowPageWithContent(TPL_SIMPLE_PAGE, 'folder_attr.htm');
}


function DoFolderUpdateAttr()
{
    global $db;
    global $gRequestPath, $gRequestFile;
    global $gFolder, $gFolderId;

    CheckRequestRandom();

    if (FolderAttrCheck($errmsg))
    {
        $colvalues =  'folder_name='.$db->qstr($gFolder['name']);
        $colvalues .= ', folder_label='.$db->qstr($gFolder['label']);
        $colvalues .= ', folder_title='.$db->qstr($gFolder['title']);
        $colvalues .= ', folder_desc='.$db->qstr($gFolder['desc']);
        $colvalues .= ', folder_keywords='.$db->qstr($gFolder['keywords']);
        $colvalues .= ', folder_robots='.$db->qstr($gFolder['robots']);
        $colvalues .= ', folder_sidebar='.$db->qstr($gFolder['sidebar']);
        $colvalues .= ', folder_order='.$gFolder['order'];
        $colvalues .= ', folder_show='.$gFolder['show'];
        $colvalues .= ', folder_active='.$gFolder['active'];
        $colvalues .= ', read_level='.$gFolder['read_level'];
        $colvalues .= ', write_level='.$gFolder['write_level'];

        $utime = date("YmdHis", time());
        $colvalues .= ', upload_by='.$db->qstr(UserGetName());
        $colvalues .= ', update_on='.$utime;

        $where =  'folder_lid='.$db->qstr($gFolder['lid']);
        $where .= ' and folder_id='.$gFolder['id'];

        if (DbSqlUpdate('web_folder', $colvalues, $where))
        {
            if ($gFolder['name'] != $gFolder['oldname'])
                $redirect = FindPathFromFolderId($gFolderId);
            else
                $redirect = $gRequestPath.$gRequestFile;

            $page_id = DbGetPageIdFromName('index.html', $gFolder['id'], $gFolder['lid']);
            if ($page_id > 0)
                WebPageUpdateAttr2($page_id, $gFolder['lid'], $gFolder['desc'], $gFolder['keywords'], $gFolder['robots']);

            Header("Location: $redirect");
            die();
        }
    }

    FolderAttrShow(false, $errmsg);
}

function FolderAttrCheck(&$errmsg)
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
    $gFolder['active']  = RequestGetValue('fld_active', 0);

    if (empty($gFolder['lid']))
        $gFolder['lid'] = UserGetLID();

    if (empty($gFolder['label']))
        $gFolder['label'] = $gFolder['name'];
    
    if (empty($gFolder['title']))
        $gFolder['label'] = $gFolder['name'];


    $gFolder['read_level']   = RequestGetValue('read_level', GUEST_LEVEL);
    $gFolder['write_level']  = RequestGetValue('write_level', WEBADMIN_LEVEL);

    if ($gFolder['name'] != $gFolder['oldname'])
    {
        $chk_id = GetFolderIdFromName($gFolder['name'], $gFolder['id'], $gFolder['lid']);
        if ($chk_id > 0)
        {
            $errmsg = _ERR_FOLDER_ALREADY_EXIST;
            return false;
        }     
    }

    if (empty($gFolder['robots']))
        $gFolder['robots'] = DEFAULT_ROBOTS;

    $gFolder['keywords'] = FixKeywords($gFolder['keywords']);

    return true;
}



?>
