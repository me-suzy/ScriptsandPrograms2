<?php 
// ----------------------------------------------------------------------
// ModName: lost_password.php
// Purpose: Send a new password
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");
require_once("../_files/library/fun_sendmail.php");

SetDynamicContent();

$gFolderId = 0;
$gRequestPath = '/'; //FindPathFromFolderId($gFolderId);
$gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
$gRequestFile = 'index.html';
$gPageId = RequestGetValue('id', 0);

DBGetFolderData(0);

$gPageNavigation = array();
$gPageNavigation[] = array($gHomePageUrl.$gBaseUrlPath."/index.html", _NAV_FRONTPAGE);
$gPageNavigation[] = array($gHomePageUrl."/phpmod/cpanel.php", _LOST_PASSWORD);


$op = RequestGetValue('op', 'show');

switch ($op)
{
case 'do':
	DoSendNewPassword();
	break;
case 'show':
default:
	LostPasswordForm(true);
	break;
}

function LostPasswordForm($bInit, $errmsg='')
{
    global $gFolder;
    global $gFolderId;
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;

    if ($bInit)
    {
        $gWebPage['fld_name'] = '';
        $gWebPage['fld_email'] = '';
    }

    $gWebPage['form_action'] = "/phpmod/lost_password.php";

    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
    $gWebPage['page_title']     = _LOST_PASSWORD_TITLE;
    $gWebPage['page_desc']      = '';
    $gWebPage['page_keywords']  = '';

    if (empty($errmsg))
        $gWebPage['page_message'] = _LOST_PASSWORD_MESSAGE;
    else
        $gWebPage['page_message'] = $errmsg;

    DoShowPageWithContent(TPL_WEB_PAGE, 'lost_password.htm');
}


function DoSendNewPassword()
{
    global $gSysVar;
    global $db;
    global $gWebPage;
    global $gBaseLocalPath;
    global $gHomePageName, $gHomePageSlogan, $gHomePageUrl;

    if (!LostPasswordCheck($errmsg))
    {
        LostPasswordForm(false, $errmsg);
        die();
    }

    $password = CreateRandomPassword();
    $colvalues = 'm_password = '.$db->qstr(md5($password));
    $where = 'm_id='.$gWebPage['fld_uid'];

    if (!DbSqlUpdate('sysmember', $colvalues, $where))
    {
        $errmsg = 'Unable to update member password';
        LostPasswordForm(false, $errmsg);
        die();
    }


    $subject = sprintf(_LOSTPASSWORD_SUBJECT_FMT, $gHomePageName);

    $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/new_password.htm';
    $msg_htm = ReadLocalFile($fname, $errmsg, true);

    $tm_now = time();
    $send_date = date('Y-m-d', $tm_now);
    $send_time = date('H:i:s', $tm_now);

    $params = array();
    $params['hp_name']      = $gHomePageName;
    $params['hp_url']       = $gHomePageUrl;
    $params['hp_slogan']    = $gHomePageSlogan;
    $params['send_date']    = $send_date;
    $params['send_time']    = $send_time;

    $params['member_fullname'] = sprintf(_USER_NAME_FMT, $gWebPage['fld_fullname']);
    $params['member_name'] = sprintf(_USER_NAME_FMT, $gWebPage['fld_name']);
    $params['member_password'] = $password;

    $params['m_fullname'] = $gWebPage['fld_fullname'];
    $params['m_name']     = $gWebPage['fld_name'];


    reset ($params);
    foreach ($params as $var => $value) 
    {
        //PrintLine($value, $var);

        $msg_htm = str_replace('{'.$var.'}', $value, $msg_htm);
    } 

    $from   = $gSysVar['svc_email_from'];
    $replay = $gSysVar['svc_email_replay'];

    $msg_txt = HtmlToText($msg_htm);

    //echo $msg_htm;
    //echo "<hr><pre>".$msg_txt."</pre>";die();

    $result = MailSend($gWebPage['fld_email'], $from, $replay, $subject, $msg_htm, $msg_txt);
    if ($result)
    {
        $content = sprintf("<h1>%s</h1>\n<p>%s</p>", _SENDPASSWORD_STATUS_TITLE, _SENDPASSWORD_STATUS_MSG1);
    }
    else
    {
        $tmp = sprintf(_SENDPASSWORD_STATUS_MSG2, $password);
        $content = sprintf("<h1>%s</h1>\n<p>%s</p>", _SENDPASSWORD_STATUS_TITLE, $tmp);
    }

    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header'] = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer'] = WebContentParse($gHomePageFooter);
    $gWebPage['page_title'] = _SENDPASSWORD_STATUS_TITLE;
    $gWebPage['page_desc']      = '';
    $gWebPage['page_keywords']  = '';
    $gWebPage['page_content'] = $content;
            
    DoShowPage(TPL_WEB_PAGE);

}

function LostPasswordCheck(&$errmsg)
{
    global $db;
    global $gWebPage;

    CheckRequestRandom();

    $gWebPage['fld_name'] = RequestGetValue('fld_name', '');
    $gWebPage['fld_email'] = RequestGetValue('fld_email', '');

    if (empty($gWebPage['fld_name']))
    {
        $errmsg = _ERR_INVALID_USER_NAME;
        return false;
    }

    if (!IsEmailValid($gWebPage['fld_email']))
    {
        $errmsg = _ERR_INVALID_EMAIL_FORMAT;
        return false;
    }

    $sql = 'select m_id, m_email, m_name, m_fullname from sysmember where m_name='.$db->qstr($gWebPage['fld_name']);
    $rs = DbExecute($sql);

    if ($rs == false || $rs->EOF)
    {
        $errmsg = _ERR_USER_NAME_NOT_FOUND;
        return false;
    }

    $email = $rs->fields[1];
    if ($gWebPage['fld_email'] != $email)
    {
        $errmsg = _ERR_USER_EMAIL_NOT_MATCH;
        return false;
    }

    $gWebPage['fld_uid']      = $rs->fields[0];
    $gWebPage['fld_name']     = $rs->fields[2];
    $gWebPage['fld_fullname'] = $rs->fields[3];

    return true;
}



?>
