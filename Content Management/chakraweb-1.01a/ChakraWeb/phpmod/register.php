<?php 
// ----------------------------------------------------------------------
// ModName: 
// Purpose: 
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");
require_once("../_files/library/fun_sendmail.php");

SetDynamicContent();

if ($gFolderId < 0)
	RedirectToPreviousPage();

$gRequestPath = FindPathFromFolderId($gFolderId);
$gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
$gRequestFile = 'index.html';


$gPageNavigation = array();
$gPageNavigation[] = array($gHomePageUrl.$gBaseUrlPath."/index.html", _NAV_FRONTPAGE);
$gPageNavigation[] = array($gHomePageUrl."/phpmod/register.php", _NAV_REGISTER);


$op = RequestGetValue('op', 'show');

switch ($op)
{
case 'do':
	DoRegistration();
	break;
case 'show':
default:
	RegistrationShow(true);
	break;
}

function RegistrationShow($reset, $errmsg='')
{
    global $gFolder;
    global $gFolderId;
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;

    if ($reset)
    {
        $gWebPage['fld_name'] = '';
        $gWebPage['fld_fullname'] = '';
        $gWebPage['fld_email'] = '';
        $gWebPage['fld_hpage'] = '';
    }

    $gWebPage['fld_country_select'] = CountryComboBox('fld_ccode', 0);

    $gWebPage['form_action'] = "/phpmod/register.php";

    $gWebPage['page_header'] = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer'] = WebContentParse($gHomePageFooter);
    $gWebPage['page_title'] = _MEMBER_REGISTRATION_TITLE;

    $gWebPage['page_desc']      = '';
    $gWebPage['page_keywords']  = '';

    if (empty($errmsg))
        $gWebPage['page_message'] = _MEMBER_REGISTRATION_CONTENT;
    else
        $gWebPage['page_message'] = $errmsg;

    $gWebPage['page_sidebar'] = '';

    DoShowPageWithContent(TPL_SIMPLE_PAGE, 'member_register.htm');
}


function DoRegistration()
{
    global $gSysVar;
    global $db;
    global $gCurrentUrlPath, $gRequestFile;
    global $gWebPage;
    global $gBaseLocalPath;
    global $gHomePageName, $gHomePageSlogan, $gHomePageUrl;

    CheckRequestRandom();

    if (!RegistrationCheck($errmsg))
    {
        RegistrationShow(false, $errmsg);
        die();
    }

    $new_uid = DbGetUniqueID('sysmember');
    $lid   = UserGetLID();
    $level = 1;
    $password = CreateRandomPassword();

    $values  = "$new_uid, $level,";

    $values .= $db->qstr($lid).',';
    $values .= $gWebPage['fld_ccode'].',';
    $values .= $db->qstr($gWebPage['fld_name']).',';
    $values .= $db->qstr($gWebPage['fld_fullname']).',';
    $values .= $db->qstr($gWebPage['fld_email']).',';
    $values .= $gWebPage['fld_view_email'].',1,';
    $values .= $db->qstr($gWebPage['fld_hpage']).',';
    $values .= $db->qstr(md5($password)).',';
    $values .= $db->qstr(DEFAULT_THEME);

    $columns = 'm_id, m_level, m_lid, m_ccode, m_name, m_fullname, m_email, 
                m_view_email, m_view_profile, m_homepage, m_password, m_theme';

    if (!DbSqlInsert('sysmember', $columns, $values))
    {
        $errmsg = _ERR_REGISTER_FAILED;
        RegistrationShow(false, $errmsg);
        die();
    }

    AddDefaultMemberServices($new_uid, $lid);
    
    $subject = sprintf(_REGISTRATION_SUBJECT_FMT, $gHomePageName);

    $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/register_email.htm';
    $msg_htm = ReadLocalFile($fname, $errmsg, true);

    $params = array();
    $params['hp_name']      = $gHomePageName;
    $params['hp_url']       = $gHomePageUrl;
    $params['hp_slogan']    = $gHomePageSlogan;

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
        $content = sprintf("<h1>%s</h1>\n<p>%s</p>", _REGIST_STATUS_TITLE, _REGIST_STATUS_MSG1);
    }
    else
    {
        $tmp = sprintf(_REGIST_STATUS_MSG2, $password);
        $content = sprintf("<h1>%s</h1>\n<p>%s</p>", _REGIST_STATUS_TITLE, $tmp);
    }
            
    $gWebPage['page_header'] = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer'] = WebContentParse($gHomePageFooter);
    $gWebPage['page_title'] = _REGIST_STATUS_TITLE;
    $gWebPage['page_desc']      = '';
    $gWebPage['page_keywords']  = '';
    $gWebPage['page_content'] = $content;
            
    DoShowPage(TPL_SIMPLE_PAGE);

}

function RegistrationCheck(&$errmsg)
{
    global $gWebPage;

    $gWebPage['fld_name']       = RequestGetValue('fld_name', '', CLEAN_ALL);
    $gWebPage['fld_fullname']   = RequestGetValue('fld_fullname', '', CLEAN_ALL);
    $gWebPage['fld_email']      = RequestGetValue('fld_email', '', CLEAN_ALL);
    $gWebPage['fld_hpage']      = RequestGetValue('fld_hpage', '', CLEAN_ALL);
    $gWebPage['fld_ccode']      = RequestGetValue('fld_ccode', 0);
    $gWebPage['fld_view_email'] = RequestGetValue('fld_view_email', 0);

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

    if (!empty($gWebPage['fld_hpage']))
    {
        if (!IsHttpUrlValid($gWebPage['fld_hpage']))
        {
            $errmsg = _ERR_INVALID_HPAGE;
            return false;
        }
    }

    if ($gWebPage['fld_ccode'] == 0)
    {
        $errmsg = _ERR_INVALID_COUNTRY;
        return false;
    }

    return true;
}


?>
