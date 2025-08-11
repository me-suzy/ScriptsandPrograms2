<?php 
// ----------------------------------------------------------------------
// ModName: 
// Purpose: 
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");
require_once("../_files/library/fun_sendmail.php");

SetDynamicContent();
CheckOpForAdminOnly();

$gFolderId = 0;
$gRequestPath = '/'; //FindPathFromFolderId($gFolderId);
$gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
$gRequestFile = 'index.html';
$gPageId = RequestGetValue('id', 0);

DBGetFolderData(0);

$gPageNavigation = array();
$gPageNavigation[] = array($gHomePageUrl.$gBaseUrlPath."/index.html", _NAV_FRONTPAGE);
$gPageNavigation[] = array($gHomePageUrl."/phpmod/cpanel.php", _NAV_CONTROL_PANEL);
$gPageNavigation[] = array($gHomePageUrl."/phpmod/service.php", _NAV_MEMBER_SERVICE);


$op = RequestGetValue('op', 'show');

switch ($op)
{
case 'edit':
	ServiceEdit();
	break;
case 'save':
	ServiceSave();
	break;
case 'add':
	ServiceAddNew();
	break;
case 'show':
	ServiceShowList();
	break;
case 'member':
	ServiceShowMemberList();
	break;
case 'sendmail':
	ServiceSendMailShowForm(true);
	break;
case 'dosend':
	ServiceSendMail();
	break;
default:
    WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_UNKNOWN_OPERATION);
	break;
}


function ServiceShowList()
{
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;

    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
    $gWebPage['page_title']     = _MEMBER_SERVICE_TITLE;
    $gWebPage['page_desc']      = '';
    $gWebPage['page_keywords']  = '';

    $gWebPage['fld_service_list'] = RenderServiceList();

    DoShowPageWithContent(TPL_WEB_PAGE, 'service.htm');
}

function ServiceShowMemberList()
{
    global $gPageNavigation, $gHomePageUrl;
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;

    $svc_id   = RequestGetValue('id', 0);
    $svc_name = RequestGetValue('name', ''); //dangerous if this page provide to any user. 

    $gPageNavigation[] = array($gHomePageUrl."/phpmod/service.php?op=member&id=$svc_id&name=".$svc_name, _NAV_SERVICE_MEMBER_LIST);

    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
    $gWebPage['page_title']     = _NAV_SERVICE_MEMBER_LIST;
    $gWebPage['page_desc']      = '';
    $gWebPage['page_keywords']  = '';

    $content  = '<h2>'.sprintf(_SERVICE_MEMBER_LIST_TITLE_FMT, $svc_name).'</h2>';
    $content .= RenderServiceMemberList($svc_id);

    $gWebPage['page_content']  = $content;
    DoShowPage(TPL_WEB_PAGE);
}


function ServiceEdit()
{
    global $gPageNavigation;
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;

    $svc_id = RequestGetValue('id', 0);
    $gPageNavigation[] = array($gHomePageUrl."/phpmod/service.php?op=edit&id=$svc_id", _NAV_MEMBER_SERVICE_EDIT);

    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
    $gWebPage['page_title']     = _MEMBER_SERVICE_EDIT_TITLE;
    $gWebPage['page_desc']      = '';
    $gWebPage['page_keywords']  = '';

    $gWebPage['id']  = $svc_id;
    $gWebPage['op']  = 'save';

    $sql = "select svc_name, svc_desc, svc_default, svc_level, svc_order, svc_active from service where svc_id=$svc_id";
    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $gWebPage['fld_name']  = $rs->fields[0];
        $gWebPage['fld_desc']  = $rs->fields[1];
        $gWebPage['chk_default']  = CheckBox('fld_default', '1', $rs->fields[2]);
        $gWebPage['fld_level']  = $rs->fields[3];
        $gWebPage['fld_order']  = $rs->fields[4];
        $gWebPage['fld_active']  = $rs->fields[5];
    }
    else
    {
        $gWebPage['fld_name']  = '';
        $gWebPage['fld_desc']  = '';
        $gWebPage['chk_default']  = CheckBox('fld_default', '1', 0);
        $gWebPage['fld_level']  = 1;
        $gWebPage['fld_order']  = DEFAULT_ORDER;
        $gWebPage['fld_active']  = 1;
    }

    $gWebPage['chk_active'] = CheckBox('fld_active', '1', $gWebPage['fld_active']);


    DoShowPageWithContent(TPL_WEB_PAGE, 'service_form.htm');
}

function ServiceSave()
{
    global $db;

    CheckRequestRandom();

    $svc_id      = RequestGetValue("id", 0);
    $svc_name    = RequestGetValue("fld_name", '');
    $svc_desc    = RequestGetValue("fld_desc", '');
    $svc_default = RequestGetValue("fld_default", 0);
    $svc_level   = RequestGetValue("fld_level", 0);
    $svc_order   = RequestGetValue("fld_order", 0);
    $svc_active  = RequestGetValue("fld_active", 0);

    $colvalues = 'svc_name='.$db->qstr($svc_name);
    $colvalues .= ', svc_desc='.$db->qstr($svc_desc);
    $colvalues .= ', svc_default='.$svc_default;
    $colvalues .= ', svc_level='.$svc_level;
    $colvalues .= ', svc_order='.$svc_order;
    $colvalues .= ', svc_active='.$svc_active;

    $where = "svc_id=$svc_id";

    DbSqlUpdate('service', $colvalues, $where);

    Header("Location: /phpmod/service.php");
}

function ServiceAddNew()
{
    global $db;

    CheckRequestRandom();

    $svc_name    = RequestGetValue("fld_name", '');
    $svc_desc    = RequestGetValue("fld_desc", '');
    $svc_default = RequestGetValue("fld_default", 0);
    $svc_level   = RequestGetValue("fld_level", 0);
    $svc_order   = RequestGetValue("fld_order", 0);

    if (!empty($svc_name))
    {
        $svc_id = DbGetUniqueID('service');

        $columns = 'svc_id, svc_lid, svc_name, svc_desc, svc_default, svc_level, svc_order';
        $values  = $svc_id.','.$db->qstr(UserGetLID());
        $values .= ','.$db->qstr($svc_name).','.$db->qstr($svc_desc);
        $values .= ",$svc_default,$svc_level,$svc_order";

        DbSqlInsert('service', $columns, $values);
    }

    Header("Location: /phpmod/service.php");
}

function RenderServiceList()
{
    global $db;

    $out = '';

    $nav_member_list = strtoupper(_NAV_SERVICE_MEMBER_LIST);
    $nav_sendmail    = strtoupper(_NAV_MEMBER_SERVICE_SENDMAIL);

    $sql  = 'select svc_id, svc_name, svc_desc, svc_active from service where svc_lid='.$db->qstr(UserGetLID());
    $sql .= ' order by svc_order, svc_name';

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $out = "<ul>\n";
        $i = 1;
        while (!$rs->EOF)
        {
            $out .= "<li><b>".$rs->fields[1].'</b>.<font face="Arial" size="1">';
            $out .= ($rs->fields[3] ? _FLD_ACTIVE : _FLD_NOACTIVE);
            $out .= ' ['.HRef("/phpmod/service.php?op=member&id=".$rs->fields[0].'&name='.$rs->fields[1], $nav_member_list).']';
            $out .= ' ['.HRef("/phpmod/service.php?op=sendmail&id=".$rs->fields[0], $nav_sendmail).']';
            $out .= ' ['.HRef("/phpmod/service.php?op=edit&id=".$rs->fields[0], _NAV_EDIT).']';
            $out .= ' ['.HRef("/phpmod/service.php?op=delete&id=".$rs->fields[0], _NAV_DELETE).']';
            $out .= "</font><br>".$rs->fields[2]."</li>\n";

            $i++;
            $rs->MoveNext();
        }
        $out .= "</ul>\n";
    }

    return $out;
}

function ServiceSendMailShowForm($clear, $errmsg='')
{
    global $gPageNavigation, $gHomePageUrl;
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;
    global $gBaseLocalPath;
    global $gSysVar;

    $svc_id  = RequestGetValue('id', 0);
    if ($svc_id == 0)
        $svc_id = RequestGetValue('fld_id', 0);

    $gPageNavigation[] = array($gHomePageUrl."/phpmod/service.php?op=sendmail&id=$svc_id", _NAV_MEMBER_SERVICE_SENDMAIL);

    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
    $gWebPage['page_title']     = _MEMBER_SERVICE_SENDMAIL_TITLE;
    $gWebPage['page_desc']      = '';
    $gWebPage['page_keywords']  = '';

    $gWebPage['fld_message'] = $errmsg;
    $gWebPage['fld_service_list'] = RenderServiceComboBox('fld_id', $svc_id);

    if ($clear)
    {
        $gWebPage['fld_from'] = $gSysVar['svc_email_from'];
        $gWebPage['fld_replay'] = $gSysVar['svc_email_replay'];
        $gWebPage['fld_subject'] = $gSysVar['svc_email_subject'];
        $gWebPage['fld_output_test'] = '';

        $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/service_sendmail_template.htm';
        $gWebPage['fld_content'] = ReadLocalFile($fname, $errmsg, true);
    }

    $gWebPage['fld_content_editor'] = RenderHtmlEditor('fld_content', $gWebPage['fld_content'], 'chstd', 600, 220);
    DoShowPageWithContent(TPL_WEB_PAGE, 'service_sendmail.htm');
}

function ServiceSendMail()
{
    global $gSysVar;
    global $gHomePageUrl;
    global $gHomePageName;
    global $gHomePageSlogan;
    global $gWebPage;

    CheckRequestRandom();

    $svc_id = RequestGetValue('fld_id');

    $gWebPage['fld_from']    = RequestGetValue('fld_from', '', CLEAN_ALL);
    $gWebPage['fld_replay']  = RequestGetValue('fld_replay', '', CLEAN_ALL);
    $gWebPage['fld_subject'] = RequestGetValue('fld_subject', '', CLEAN_ALL);
    $gWebPage['fld_content'] = RequestGetValue('fld_content', '', CLEAN_SAVE);

    $saveas_template = RequestGetValue('fld_saveas_template', 0);

    if ($svc_id <= 0 || empty($gWebPage['fld_from']) || empty($gWebPage['fld_replay'])
        || empty($gWebPage['fld_subject']) )
    {
        ServiceSendMailShowForm(false, _ERR_INVALID_EMAIL_SERVICE);
        die();        
    }

    if ($saveas_template)
    {
        $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/service_sendmail_template.htm';
        WriteLocalFile($fname, $gWebPage['fld_content']);
    }

    SystemGetConstant();
    $gSysVar['svc_email_from']    = $gWebPage['fld_from'];
    $gSysVar['svc_email_replay']  = $gWebPage['fld_replay'];
    $gSysVar['svc_email_subject'] = $gWebPage['fld_subject'];
    SystemSaveVariables();

    $params = array();
    $params['member_fullname'] = sprintf(_USER_NAME_FMT, UserGetFullName());
    $params['member_name'] = sprintf(_USER_NAME_FMT, UserGetName());

    $params['hp_url']   = $gHomePageUrl;
    $params['hp_name']  = $gHomePageName;
    $params['hp_slogan']= $gHomePageSlogan;

    GetServiceNameAndDesc($svc_id, $params['svc_name'], $params['svc_desc']);
    $params['svc_subject'] = $gWebPage['fld_subject'];

    $email_htm = $gWebPage['fld_content'];

    $test = RequestGetValue('test', '');
    if (!empty($test))
    {
        $email_htm = ContentParseVar($email_htm, $params);
        $email_txt = HtmlToText($email_htm);

        $gWebPage['fld_output_test'] = sprintf('<div id=box><h2>%s</h2><div id=content>%s<hr size=4 noshade><pre>%s</pre></div></div>', 'TEST OUTPUT', $email_htm, $email_txt);
        ServiceSendMailShowForm(false);
    }
    else
    {
        DoServiceSendMail($svc_id, $email_htm, $params);
        Header("Location: /phpmod/service.php");
    }
}

//actual code for sending email to the members
function DoServiceSendMail($svc_id, $content, $params)
{
    global $gWebPage;

    set_time_limit(0);
    ignore_user_abort(true);


    $sql = "select m_email, m_name, m_fullname from sysmember as a inner join svcmember as b on a.m_id = b.m_id
            where svc_id=$svc_id order by m_fullname";    

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        while (!$rs->EOF)
        {
            $m_email = $rs->fields[0];
            $params['member_fullname'] = sprintf(_USER_NAME_FMT, $rs->fields[2]);
            $params['member_name'] = sprintf(_USER_NAME_FMT, $rs->fields[1]);

            $email_htm = ContentParseVar($content, $params);
            $email_txt = HtmlToText($email_htm);

            $result = MailSend($m_email, $gWebPage['fld_from'], $gWebPage['fld_replay'], $gWebPage['fld_subject'], $email_htm, $email_txt);

            $rs->MoveNext();
        }
    }
    
    ignore_user_abort(false);
}

function RenderServiceMemberList($svc_id)
{
    $sql = "select a.m_id, m_name, m_fullname, m_email, m_homepage, m_desc, m_view_email, m_ccode from sysmember as a
            inner join svcmember as b on a.m_id = b.m_id
            where svc_id=$svc_id order by m_fullname";    

    $out = '';

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $out = RenderMemberListFromRS($rs);
    }

    return $out;
}

function RenderServiceComboBox($fld_name, $selvalue)
{
    global $db;

    $sql = 'select svc_id, svc_name from service where svc_lid='.$db->qstr(UserGetLID());
    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $arsvc = array();
        $arsvc[0] = 'Select The Service';
        while (!$rs->EOF)
        {
            $arsvc[$rs->fields[0]] = $rs->fields[1];
            $rs->MoveNext();
        }

        return ComboBoxFromArray($arsvc, $fld_name, $selvalue);
    }
    return '';
}

function GetServiceNameAndDesc($svc_id, &$svc_name, &$svc_desc)
{
    $sql = "select svc_name, svc_desc from service where svc_id=$svc_id";
    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $svc_name = $rs->fields[0];
        $svc_desc = $rs->fields[1];
    }
    else
    {
        $svc_name = '';
        $svc_desc = '';
    }
}




?>
