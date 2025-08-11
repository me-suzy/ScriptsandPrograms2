<?php 
// ----------------------------------------------------------------------
// ModName: comment.php
// Purpose: Add comment to web page
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

SetDynamicContent();

$op = RequestGetValue('op', '');

switch ($op)
{
case 'add':
    CheckOpForMemberOnly();
	CommentAddNew();
	break;
case 'edit':
    CheckOpForAdminOnly();
	CommentEdit();
	break;
case 'save':
    CheckOpForAdminOnly();
	CommentSave();
	break;
case 'delete':
    CheckOpForAdminOnly();
	CommentDelete();
	break;
case 'show':
    CheckOpForAdminOnly();
	CommentShowAttr(1);
	break;
case 'hide':
    CheckOpForAdminOnly();
	CommentShowAttr(0);
	break;
default:
    WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_UNKNOWN_OPERATION);
	break;
}

function CommentEdit()
{
    global $gWebPage;
    global $gBaseLocalPath;
    global $gHomePageHeader, $gHomePageFooter;
    
    
    $params = array();

    $bedit = false;

    $id = RequestGetValue('id', 0);
    if ($id > 0)
    {
        $sql = "select comm_content, comm_show from comment where comm_id=$id";

        $sql = 'select m_fullname, m_email, a.comm_content, a.comm_show from comment 
            as a inner join sysmember as b on a.m_id=b.m_id where comm_id='.$id;

        $rs = DbExecute($sql);

        if ($rs && !$rs->EOF)
        {
            $params['comm_id']      = $id;
            $params['fld_fullname'] = $rs->fields[0];
            $params['fld_email']    = $rs->fields[1];
            $params['fld_content']  = $rs->fields[2];
            $params['fld_show']     = $rs->fields[3];

            $bedit = true;
        }
    }

    if (!$bedit)
        WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_UNKNOWN_OPERATION);


    $params['fld_content_editor'] = RenderHtmlEditor('fld_content', $params['fld_content'], 'chmini', 500, 140);
    $params['chk_show'] = CheckBox('fld_show', '1', $params['fld_show']);
    $params['page_message'] = '';

    $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/comment_edit.htm';
    $content .= LoadContentFile($fname, $params);

    CommentShowPage($content);            
}

function CommentDelete()
{
    global $gRequestPath, $gRequestFile;

    ParseRequestPathAndFile(RequestGetValue('path'), $gRequestPath, $gRequestFile);
    $comm_id = RequestGetValue('id', 0);

    DbSqlDelete('comment', "comm_id=$comm_id");

    Header("Location: $gRequestPath$gRequestFile");

}

function CommentSave()
{
    global $db;
    global $gWebPage;
    global $gRequestPath, $gRequestFile;

    CheckRequestRandom();

    ParseRequestPathAndFile(RequestGetValue('path'), $gRequestPath, $gRequestFile);
    $comm_id = RequestGetValue('id', 0);

    if (CommentFormCheck())
    {
        $colvalues = 'comm_content='.$db->qstr($gWebPage['fld_content']);
        $where = "comm_id=$comm_id";

        DbSqlUpdate('comment', $colvalues, $where);
    }

    Header("Location: $gRequestPath$gRequestFile");
}

function CommentShowAttr($show)
{
    global $gRequestPath, $gRequestFile;

    ParseRequestPathAndFile(RequestGetValue('path'), $gRequestPath, $gRequestFile);
    $comm_id = RequestGetValue('id', 0);

    $colvalues  = "comm_show=$show";
    $where      = "comm_id=$comm_id";

    DbSqlUpdate('comment', $colvalues, $where);

    Header("Location: $gRequestPath$gRequestFile");
}


function CommentAddNew()
{
    global $db;
    global $gWebPage;

    CheckRequestRandom();

    ParseRequestPathAndFile(RequestGetValue('path'), $gRequestPath, $gRequestFile);
    $gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
    $page_id = RequestGetValue('page_id', 0);

    if (CommentFormCheck())
    {
        $columns = 'comm_id, page_id, page_lid, comm_content, m_id, upload_on';
        $values  = DbGetUniqueID('comment');
        $values .= ','.$page_id;
        $values .= ','.$db->qstr(UserGetLID());
        $values .= ','.$db->qstr($gWebPage['fld_content']);
        $values .= ','.UserGetID();
        $values .= ','.date("YmdHis", time());

        DbSqlInsert('comment', $columns, $values);
    }

    Header("Location: $gRequestPath$gRequestFile");
}

function CommentFormCheck()
{
    global $gWebPage;

    $gWebPage['fld_content']  = RequestGetValue('fld_content', '', CLEAN_SAVE);

    if (empty($gWebPage['fld_content']))
    {
        return false;
    }

    return true;
}


function CommentShowPage($content)
{
    global $gFolderId, $gPageId;
    global $gRequestPath, $gCurrentUrlPath;
    global $gRequestFile;
    global $gHomePageHeader, $gHomePageFooter;
    global $gWebPage;

    ParseRequestPathAndFile(RequestGetValue('path'), $gRequestPath, $gRequestFile);
    $gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
    $gFolderId  = FindFolderIdFromPath($gRequestPath);
    $gPageId = 0;

    DBGetFolderData($gFolderId);

    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
    $gWebPage['page_title']     = DbGetPageTitleFromName($gRequestFile, $gFolderId);
    $gWebPage['page_content']   = $content;

    DoShowPage(TPL_WEB_PAGE);
}

?>
