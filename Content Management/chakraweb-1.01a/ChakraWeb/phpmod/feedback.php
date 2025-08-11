<?php 
// ----------------------------------------------------------------------
// ModName: feedback.php
// Purpose: Processing feedback
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

SetDynamicContent();

$op = RequestGetValue('op', '');

switch ($op)
{
case 'add':
	FeedbackAddNew();
	break;
case 'edit':
    CheckOpForAdminOnly();
	FeedbackEdit();
	break;
case 'save':
    CheckOpForAdminOnly();
	FeedbackSave();
	break;
case 'delete':
    CheckOpForAdminOnly();
	FeedbackDelete();
	break;
case 'show':
    CheckOpForAdminOnly();
	FeedbackShowAttr(1);
	break;
case 'hide':
    CheckOpForAdminOnly();
	FeedbackShowAttr(0);
	break;
default:
    WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_UNKNOWN_OPERATION);
    break;
}

function FeedbackAddNew()
{
    global $db;
    global $gHomePageHeader, $gHomePageFooter;
    global $gWebPage;
    global $gBaseLocalPath;

    CheckRequestRandom();

    if (FeedbackFormCheck($errmsg))
    {
        $columns = 'fb_id, fb_lid, fb_fullname, fb_email, fb_content, fb_show, fb_testimonial, upload_on';
        $values  = DbGetUniqueID('feedback');
        $values .= ','.$db->qstr(UserGetLID());
        $values .= ','.$db->qstr($gWebPage['fld_name']);
        $values .= ','.$db->qstr($gWebPage['fld_email']);
        $values .= ','.$db->qstr($gWebPage['fld_content']);
        $values .= ','.$gWebPage['fld_show'];
        $values .= ','.$gWebPage['fld_testimonial'];
        $values .= ','.date("YmdHis", time());

        if (DbSqlInsert('feedback', $columns, $values))
        {
            $title   = _FEEDBACK_THANK_TITLE;
            $content = sprintf("<h1>%s</h1>\n%s\n", _FEEDBACK_THANK_TITLE, _FEEDBACK_THANK_MESSAGE);

            FeedBackShowPage($title, $content);
            die();
        }
    }


    $title = _FEEDBACK_TITLE;
    $content = sprintf("<h1>%s</h1>\n%s\n", _FEEDBACK_TITLE, _FEEDBACK_MESSAGE);

    $gWebPage['fld_content_editor'] = RenderHtmlEditor('fld_content', $gWebPage['fld_content'], 'chmini', 500, 140);
    $gWebPage['op'] = 'add';
    $gWebPage['chk_show'] = CheckBox('fld_show', '1', $gWebPage['fld_show']);
    $gWebPage['chk_testimonial'] = CheckBox('fld_testimonial', '1', $gWebPage['fld_testimonial']);
    $gWebPage['page_message'] = $errmsg;

    $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/feedback.htm';
    $content .= LoadContentFile($fname, $gWebPage);

    FeedBackShowPage($title, $content);            
}

function FeedbackEdit()
{
    global $gWebPage;
    global $gBaseLocalPath;
    global $gHomePageHeader, $gHomePageFooter;
    
    
    $title = _FEEDBACK_TITLE;
    $content = sprintf("<h1>%s</h1>\n%s\n", _FEEDBACK_TITLE, _FEEDBACK_MESSAGE);

    $params = array();

    $bedit = false;

    $fb_id = RequestGetValue('id', 0);
    if ($fb_id > 0)
    {
        $sql = "select fb_fullname, fb_email, fb_content, fb_show, fb_testimonial from feedback where fb_id=$fb_id";
        $rs = DbExecute($sql);

        if ($rs && !$rs->EOF)
        {
            $params['fld_id']      = $fb_id;
            $params['fld_name']    = $rs->fields[0];
            $params['fld_email']   = $rs->fields[1];
            $params['fld_content'] = $rs->fields[2];

            $params['fld_show']        = $rs->fields[3];
            $params['fld_testimonial'] = $rs->fields[4];

            $bedit = true;
        }
    }

    if (!$bedit)
        WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_UNKNOWN_OPERATION);


    $params['fld_content_editor'] = RenderHtmlEditor('fld_content', $params['fld_content'], 'chmini', 500, 140);
    $params['op'] = 'save';
    $params['chk_show'] = CheckBox('fld_show', '1', $params['fld_show']);
    $params['chk_testimonial'] = CheckBox('fld_testimonial', '1', $params['fld_testimonial']);
    $params['page_message'] = '';

    $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/feedback.htm';
    $content .= LoadContentFile($fname, $params);

    FeedBackShowPage($title, $content);            
}

function FeedbackSave()
{
    global $db;
    global $gWebPage;
    global $gBaseLocalPath;

    CheckRequestRandom();

    if (FeedbackFormCheck($errmsg))
    {
        $colvalues  = 'fb_fullname='.$db->qstr($gWebPage['fld_name']);
        $colvalues .= ', fb_email='.$db->qstr($gWebPage['fld_email']);
        $colvalues .= ', fb_content='.$db->qstr($gWebPage['fld_content']);

        $where = 'fb_id='.$gWebPage['fld_id'];

        if (DbSqlUpdate('feedback', $colvalues, $where))
        {
            $redirect = RequestGetValue('path');
            Header("Location: $redirect");
            die();
        }

        $errmsg = $db->ErrorMsg();
    }


    $title = _FEEDBACK_TITLE;
    $content = sprintf("<h1>%s</h1>\n%s\n", _FEEDBACK_TITLE, _FEEDBACK_MESSAGE);

    $gWebPage['fld_content_editor'] = RenderHtmlEditor('fld_content', $gWebPage['fld_content'], 'chmini', 500, 140);
    $gWebPage['op'] = 'save';
    $gWebPage['chk_show'] = CheckBox('fld_show', '1', $gWebPage['fld_show']);
    $gWebPage['chk_testimonial'] = CheckBox('fld_testimonial', '1', $gWebPage['fld_testimonial']);
    $gWebPage['page_message'] = $errmsg;

    $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/feedback.htm';
    $content .= LoadContentFile($fname, $gWebPage);

    FeedBackShowPage($title, $content);            
}

function FeedbackShowAttr($show)
{
    global $gFolderId;

    $fb_id = RequestGetValue('id', 0);
    if ($fb_id > 0)
    {
        $sql = "update feedback set fb_show=$show where fb_id=$fb_id";
        $rs = DbExecute($sql);
    }

    $redirect = FindPathFromFolderId($gFolderId)."index.html"; 
    Header("Location: $redirect");
}

function FeedbackDelete()
{
    global $gFolderId;

    $fb_id = RequestGetValue('id', 0);
    if ($fb_id > 0)
    {
        $where   = "fb_id=$fb_id";
        DbSqlDelete('feedback', $where);
    }
    
    $redirect = FindPathFromFolderId($gFolderId)."index.html"; 
    Header("Location: $redirect");
}



function FeedbackFormCheck(&$errmsg)
{
    global $gWebPage;

    $gWebPage['fld_id']         = RequestGetValue('fld_id', 0);
    $gWebPage['fld_name']       = RequestGetValue('fld_name', '', CLEAN_ALL);
    $gWebPage['fld_email']      = RequestGetValue('fld_email', '', CLEAN_ALL);
    $gWebPage['fld_content']    = RequestGetValue('fld_content', '', CLEAN_SAVE);
    $gWebPage['fld_show']       = RequestGetValue('fld_show', 0);
    $gWebPage['fld_testimonial']= RequestGetValue('fld_testimonial', 0);


    if (empty($gWebPage['fld_name']) || !IsNameValid($gWebPage['fld_name']))
    {
        $errmsg = _ERR_INVALID_USER_NAME;
        return false;
    }
    
    if (!IsEmailValid($gWebPage['fld_email']))
    {
        $errmsg = _ERR_INVALID_EMAIL_FORMAT;
        return false;
    }
    

    if (empty($gWebPage['fld_content']))
    {
        $errmsg = _ERR_FEEDBACK_MESSAGE_EMPTY;
        return false;
    }

    return true;
}


function FeedbackShowPage($title, $content)
{
    global $gFolderId, $gPageId;
    global $gRequestPath, $gCurrentUrlPath;
    global $gRequestFile;
    global $gHomePageHeader, $gHomePageFooter;
    global $gWebPage;

    $gRequestPath = FindPathFromFolderId($gFolderId);
    $gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
    $gRequestFile = 'index.html';
    $gPageId = 0;

    DBGetFolderData($gFolderId);

    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
    $gWebPage['page_title']     = $title;
    $gWebPage['page_content']   = $content;

    DoShowPage(TPL_WEB_PAGE);
}


?>
