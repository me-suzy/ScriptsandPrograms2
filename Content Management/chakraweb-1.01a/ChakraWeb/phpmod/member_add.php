<?php 
// ----------------------------------------------------------------------
// ModName: member_add.php
// Purpose: Add a new member (and send him/her email if appropriate)
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

DBGetFolderData(0);

$gPageNavigation = array();
$gPageNavigation[] = array($gHomePageUrl.$gBaseUrlPath."/index.html", _NAV_FRONTPAGE);
$gPageNavigation[] = array($gHomePageUrl."/phpmod/cpanel.php", _NAV_CONTROL_PANEL);
$gPageNavigation[] = array($gHomePageUrl."/phpmod/member_add.php", _NAV_MEMBER_ADD);


$op = RequestGetValue('op', 'show');

switch ($op)
{
case 'add':
default:
	MemberAddNew();
	break;
case 'show':
default:
	MemberAddNewShowForm(true);
	break;
}

function MemberAddNewShowForm($bclear, $errmsg='')
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
    $gWebPage['page_title']     = _MEMBER_ADD_TITLE;
    $gWebPage['page_desc']      = '';
    $gWebPage['page_keywords']  = '';

    $gWebPage['fld_message'] = $errmsg;

    if ($bclear)
    {
        $gWebPage['fld_from'] = $gSysVar['svc_email_from'];
        $gWebPage['fld_subject'] = _MEMBER_ADD_SUBJECT;
        $gWebPage['fld_output_test'] = '';

        $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/member_add_template.htm';
        $gWebPage['fld_content'] = ReadLocalFile($fname, $errmsg, true);

        $gWebPage['fld_name'] = '';
        $gWebPage['fld_fullname'] = '';
        $gWebPage['fld_email'] = '';
        $gWebPage['fld_send_email'] = 0;
        $gWebPage['fld_ccode'] = 0;
    }

    $gWebPage['chk_send_email'] = CheckBox('fld_send_email', '1', $gWebPage['fld_send_email']);
    $gWebPage['fld_country_select'] = CountryComboBox('fld_ccode', $gWebPage['fld_ccode']);

    $gWebPage['fld_content_editor'] = RenderHtmlEditor('fld_content', $gWebPage['fld_content'], 'chstd', 600, 220);
    DoShowPageWithContent(TPL_WEB_PAGE, 'member_add.htm');
}

function MemberAddNew()
{
    global $gHomePageUrl;
    global $gHomePageName;
    global $gHomePageSlogan;
    global $gWebPage;
    global $uid;
    global $gBaseLocalPath;

    CheckRequestRandom();

    if (!MemberAddNewCheck($errmsg))
    {
        MemberAddNewShowForm(false, $errmsg);
        die();
    }
    
    $saveas_template = RequestGetValue('fld_saveas_template', 0);
    if ($saveas_template)
    {
        $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/member_add_template.htm';
        WriteLocalFile($fname, $gWebPage['fld_content']);
    }

    $send_email = $gWebPage['fld_send_email'];
    $test_only  = RequestGetValue('test', '') != '';

    if ($test_only || $send_email)
    {
        $params = array();
        $params['member_fullname'] = $gWebPage['fld_fullname'];
        $params['member_name'] = $gWebPage['fld_name'];
        $params['member_email'] = $gWebPage['fld_email'];
        $params['member_password'] = $gWebPage['fld_password'];

        $params['hp_url']   = $gHomePageUrl;
        $params['hp_name']  = $gHomePageName;
        $params['hp_slogan']= $gHomePageSlogan;

        $email_htm = $gWebPage['fld_content'];
        $email_htm = ContentParseVar($email_htm, $params);

        $email_txt = HtmlToText($email_htm);
    }

    if ($test_only)
    {
        $gWebPage['fld_output_test'] = sprintf('<div id=box><h2>%s</h2><div id=content>%s<hr size=4 noshade><pre>%s</pre></div></div>', 'TEST OUTPUT', $email_htm, $email_txt);
        MemberAddNewShowForm(false);
    }
    else
    {
        if (!DoMemberAddNew($errmsg))
        {
            MemberAddNewShowForm(false, $errmsg);
            die();
        }

        if ($send_email)
            DoMemberSendMail($gWebPage['fld_email'], $email_htm, $email_txt);

        Header("Location: /phpmod/cpanel.php");
    }
}

function MemberAddNewCheck(&$errmsg)
{
    global $gWebPage;

    $gWebPage['fld_name']       = RequestGetValue('fld_name', '', CLEAN_ALL);
    $gWebPage['fld_fullname']   = RequestGetValue('fld_fullname', '', CLEAN_ALL);
    $gWebPage['fld_email']      = RequestGetValue('fld_email', '', CLEAN_ALL);
    $gWebPage['fld_password']   = RequestGetValue('fld_password', '', CLEAN_ALL);
    $gWebPage['fld_password2']  = RequestGetValue('fld_password2', '', CLEAN_ALL);
    $gWebPage['fld_send_email'] = RequestGetValue('fld_send_email', 0);

    $gWebPage['fld_from']    = RequestGetValue('fld_from', '', CLEAN_ALL);
    $gWebPage['fld_subject'] = RequestGetValue('fld_subject', '', CLEAN_ALL);
    $gWebPage['fld_content'] = RequestGetValue('fld_content', '', CLEAN_SAVE);
    $gWebPage['fld_ccode']      = RequestGetValue('fld_ccode', '');

    if (empty($gWebPage['fld_name']) || !IsUPValid($gWebPage['fld_name']))
    {
        $errmsg = _ERR_INVALID_USER_NAME;
        return false;
    }

    if (empty($gWebPage['fld_fullname']) || !IsNameValid($gWebPage['fld_fullname']))
    {
        $errmsg = _ERR_INVALID_USER_FULLNAME;
        return false;
    }


    if (!IsEmailValid($gWebPage['fld_email']))
    {
        $errmsg = _ERR_INVALID_EMAIL_FORMAT;
        return false;
    }

    if (empty($gWebPage['fld_password']) || !IsUPValid($gWebPage['fld_password']))
    {
        $errmsg = _ERR_INVALID_PASSWORD;
        return false;
    }

    if ($gWebPage['fld_password'] != $gWebPage['fld_password2'])
    {
        $errmsg = _ERR_INVALID_PASSWORD2;
        return false;
    }

    if (empty($gWebPage['fld_from']) || empty($gWebPage['fld_subject']) )
    {
        $errmsg = _ERR_INVALID_EMAIL_SERVICE;
        return false;
    }

    return true;
}

function DoMemberAddNew(&$errmsg)
{
    global $gWebPage;
    global $db;

    $gWebPage['fld_view_email'] = 1;
    $gWebPage['fld_view_profile'] = 1;
    $gWebPage['fld_hpage'] = '';

    $new_uid = DbGetUniqueID('sysmember');
    $lid   = UserGetLID();
    $level = 1;
    $password = md5($gWebPage['fld_password']);

    $values  = "$new_uid, $level,";
    $values .= $db->qstr($lid).',';
    $values .= $gWebPage['fld_ccode'].',';
    $values .= $db->qstr($gWebPage['fld_name']).',';
    $values .= $db->qstr($gWebPage['fld_fullname']).',';
    $values .= $db->qstr($gWebPage['fld_email']).',';
    $values .= $gWebPage['fld_view_email'].',';
    $values .= $gWebPage['fld_view_profile'].',';
    $values .= $db->qstr($gWebPage['fld_hpage']).',';
    $values .= $db->qstr($password);

    $columns = 'm_id, m_level, m_lid, m_ccode, m_name, m_fullname, m_email, m_view_email, m_view_profile, m_homepage, m_password';

    if (!DbSqlInsert('sysmember', $columns, $values))
    {
        $errmsg = _ERR_ADDNEW_MEMBER_FAILED.$db->ErrorMsg();
        return false;
    }

    AddDefaultMemberServices($new_uid, $lid);

    return true;
}


function DoMemberSendMail($m_email, $email_htm, $email_txt)
{
    
}



?>
