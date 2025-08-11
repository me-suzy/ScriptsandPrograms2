<?php 
// ----------------------------------------------------------------------
// ModName: member_sendmail.php
// Purpose: Send email to specific member
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");
require_once("../_files/library/fun_sendmail.php");

SetDynamicContent();
CheckOpForAdminOnly();

$gFolderId = 0;
$gRequestPath = '/'; 
$gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
$gRequestFile = 'index.html';
$gPageId = RequestGetValue('id', 0);
$uid = RequestGetValue('uid', 0);

DBGetFolderData(0);

$gPageNavigation = array();
$gPageNavigation[] = array($gHomePageUrl.$gBaseUrlPath."/index.html", _NAV_FRONTPAGE);
$gPageNavigation[] = array($gHomePageUrl."/members/index.html", _NAV_MEMBERS);
$gPageNavigation[] = array($gHomePageUrl."/phpmod/member_sendmail.php?uid=".$uid, _NAV_MEMBER_SENDMAIL);


$op = RequestGetValue('op', 'show');

switch ($op)
{
case 'dosend':
default:
	MemberSendMail();
	break;
case 'show':
default:
	MemberSendMailShowForm(true);
	break;
}

function MemberSendMailShowForm($bclear, $errmsg='')
{
    global $uid;
    global $gHomePageUrl;
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;
    global $gBaseLocalPath;
    global $gSysVar;

    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
    $gWebPage['page_title']     = _MEMBER_SERVICE_SENDMAIL_TITLE;
    $gWebPage['page_desc']      = '';
    $gWebPage['page_keywords']  = '';

    $gWebPage['fld_message'] = $errmsg;

    if ($bclear)
    {
        $gWebPage['fld_from'] = $gSysVar['svc_email_from'];
        $gWebPage['fld_replay'] = $gSysVar['svc_email_replay'];
        $gWebPage['fld_subject'] = $gSysVar['svc_email_subject'];
        $gWebPage['fld_output_test'] = '';

        $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/member_sendmail_template.htm';
        $gWebPage['fld_content'] = ReadLocalFile($fname, $errmsg, true);
    }

    GetEmailTargetInfo($uid, $m_name, $m_fullname, $m_email);
    
    $gWebPage['uid'] = $uid;
    $gWebPage['fld_name'] = $m_name;
    $gWebPage['fld_fullname'] = $m_fullname;
    $gWebPage['fld_email'] = $m_email;

    $gWebPage['fld_content_editor'] = RenderHtmlEditor('fld_content', $gWebPage['fld_content'], 'chstd', 600, 220);
    DoShowPageWithContent(TPL_WEB_PAGE, 'member_sendmail.htm');
}

function MemberSendMail()
{
    global $gSysVar;
    global $gHomePageUrl;
    global $gHomePageName;
    global $gHomePageSlogan;
    global $gWebPage;
    global $uid;
    global $gBaseLocalPath;

    CheckRequestRandom();

    $gWebPage['fld_from']    = RequestGetValue('fld_from', '', CLEAN_ALL);
    $gWebPage['fld_replay']  = RequestGetValue('fld_replay', '', CLEAN_ALL);
    $gWebPage['fld_subject'] = RequestGetValue('fld_subject', '', CLEAN_ALL);
    $gWebPage['fld_content'] = RequestGetValue('fld_content', '', CLEAN_SAVE);
    
    $saveas_template = RequestGetValue('fld_saveas_template', 0);

    if ($uid <= 0 || empty($gWebPage['fld_from']) || empty($gWebPage['fld_replay'])
        || empty($gWebPage['fld_subject']) )
    {
        MemberSendMailShowForm(false, _ERR_INVALID_EMAIL_SERVICE);
        die();        
    }

    if ($saveas_template)
    {
        $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/member_sendmail_template.htm';
        WriteLocalFile($fname, $gWebPage['fld_content']);
    }

    SystemGetConstant();
    $gSysVar['svc_email_from']    = $gWebPage['fld_from']);
    $gSysVar['svc_email_replay']  = $gWebPage['fld_replay']);
    $gSysVar['svc_email_subject'] = $gWebPage['fld_subject']);
    SystemSaveVariables();

    GetEmailTargetInfo($uid, $m_name, $m_fullname, $m_email);

    $params = array();
    $params['member_fullname'] = $m_fullname;
    $params['member_name'] = $m_name;
    $params['member_email'] = $m_email;

    $params['hp_url']   = $gHomePageUrl;
    $params['hp_name']  = $gHomePageName;
    $params['hp_slogan']= $gHomePageSlogan;

    $email_htm = $gWebPage['fld_content'];
    $email_htm = ContentParseVar($email_htm, $params);

    $email_txt = HtmlToText($email_htm);

    $test = RequestGetValue('test', '');
    if (!empty($test))
    {
        $gWebPage['fld_output_test'] = sprintf('<div id=box><h2>%s</h2><div id=content>%s<hr size=4 noshade><pre>%s</pre></div></div>', 'TEST OUTPUT', $email_htm, $email_txt);
        MemberSendMailShowForm(false);
    }
    else
    {
        $result = MailSend($m_email, $gWebPage['fld_from'], $gWebPage['fld_replay'], $gWebPage['fld_subject'], $email_htm, $email_txt);
        if ($result)
        {
            Header("Location: /members/index.html");
            die();
        }
        $gWebPage['fld_output_test'] = '';
        MemberSendMailShowForm(false, _ERR_SEND_EMAIL_FAILED);
    }
}

function GetEmailTargetInfo($uid, &$m_name, &$m_fullname, &$m_email)
{
    $sql = "select m_name, m_fullname, m_email from sysmember where m_id=$uid";
    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $m_name = $rs->fields[0];
        $m_fullname = $rs->fields[1];
        $m_email = $rs->fields[2];
    }
    else
    {
        $m_name = _ERR_INVALID_USER_NAME;
        $m_fullname = '';
        $m_email = '';
    }
}


?>
