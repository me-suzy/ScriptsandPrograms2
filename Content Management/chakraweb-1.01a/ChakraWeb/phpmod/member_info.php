<?php 
// ----------------------------------------------------------------------
// ModName: member_info.php
// Purpose: Update Member Information
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

SetDynamicContent();

if (!IsUserLogin())
	RedirectToPreviousPage();

$gFolderId = 0;
$gRequestPath = '/'; //FindPathFromFolderId($gFolderId);
$gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
$gRequestFile = 'index.html';
$gPageId = RequestGetValue('id', 0);

DBGetFolderData(0);

$gPageNavigation = array();
$gPageNavigation[] = array($gHomePageUrl.$gBaseUrlPath."/index.html", _NAV_FRONTPAGE);
$gPageNavigation[] = array($gHomePageUrl."/phpmod/cpanel.php", _NAV_CONTROL_PANEL);
$gPageNavigation[] = array($gHomePageUrl."/phpmod/member_info.php", _NAV_MEMBER_INFO);


$op = RequestGetValue('op', 'show');

switch ($op)
{
case 'save':
	DoMemberInfoUpdate();
	break;
case 'show':
default:
	MemberInfoShow(true);
	break;
}

function MemberInfoShow($dbread, $errmsg='')
{
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;
    global $gLanguageList, $gThemeList;

    if ($dbread)
    {
        //only admin can change other user info

        if (IsUserAdmin())
        {
            $uid = RequestGetValue('uid', 0);
            if ($uid == 0)
                $uid = UserGetID();
        }
        else
        {
            $uid = UserGetID();
        }

        $gWebPage['uid'] = $uid;

        $minfo = MemberGetInfo($uid, '');
        if ($minfo)
        {
            $gWebPage['fld_level']      = $minfo['m_level'];
            $gWebPage['fld_name']       = $minfo['m_name'];
            $gWebPage['fld_fullname']   = $minfo['m_fullname'];
            $gWebPage['fld_email']      = $minfo['m_email'];
            $gWebPage['fld_hpage']      = $minfo['m_homepage'];
            $gWebPage['fld_desc']       = $minfo['m_desc'];
            $gWebPage['chk_view_email'] = CheckBox('fld_view_email', '1', $minfo['m_view_email']);
            $gWebPage['chk_view_profile'] = CheckBox('fld_view_profile', '1', $minfo['m_view_email']);
            $gWebPage['country_select'] = CountryComboBox('fld_ccode', $minfo['m_ccode']);
            $gWebPage['lang_select1'] = ComboBoxFromArray($gLanguageList, 'fld_lid', $minfo['m_lid']);
            $gWebPage['theme_select1'] = ComboBoxFromArray1($gThemeList, 'fld_theme', $minfo['m_theme']);

            if (IsUserAdmin())
                $gWebPage['member_level_row'] = RenderMemberLevelRow($gWebPage['fld_level']);
            else
                $gWebPage['member_level_row'] = '';
        }
        else
        {
            $gWebPage['fld_name']       = '';
            $gWebPage['fld_fullname']   = '';
            $gWebPage['fld_email']      = '';
            $gWebPage['fld_hpage']      = '';
            $gWebPage['fld_desc']       = '';
            $gWebPage['chk_view_email'] = CheckBox('fld_view_email', '1', true);
            $gWebPage['chk_view_profile'] = CheckBox('fld_view_profile', '1', true);
            $gWebPage['country_select'] = CountryComboBox('fld_ccode', 0);
            $gWebPage['lang_select1'] = ComboBoxFromArray($gLanguageList, 'fld_lid', UserGetLID());
            $gWebPage['theme_select1'] = ComboBoxFromArray1($gThemeList, 'fld_theme', UserGetTheme());
            $gWebPage['member_level_row'] = '';
        }
    }
    else
    {
        $gWebPage['chk_view_email'] = CheckBox('fld_view_email', '1', $gWebPage['fld_view_email']);
        $gWebPage['chk_view_profile'] = CheckBox('chk_view_profile', '1', $gWebPage['fld_view_profile']);
        $gWebPage['country_select'] = CountryComboBox('fld_ccode', $gWebPage['fld_ccode']);
        $gWebPage['lang_select1'] = ComboBoxFromArray($gLanguageList, 'fld_lid', $gWebPage['fld_lid']);
        $gWebPage['theme_select1'] = ComboBoxFromArray1($gThemeList, 'fld_theme', $gWebPage['fld_theme']);

        if (IsUserAdmin())
            $gWebPage['member_level_row'] = RenderMemberLevelRow($gWebPage['fld_level']);
        else
            $gWebPage['member_level_row'] = '';
    }

    $gWebPage['form_action'] = "/phpmod/member_info.php";

    $gWebPage['page_sidebar']   = ''; //RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
    $gWebPage['page_title']     = _MEMBER_INFO_TITLE;
    $gWebPage['page_desc']      = '';
    $gWebPage['page_keywords']  = '';

    if (empty($errmsg))
        $gWebPage['page_message'] = _MEMBER_INFO_MESSAGE;
    else
        $gWebPage['page_message'] = $errmsg;

    DoShowPageWithContent(TPL_SIMPLE_PAGE, 'member_info.htm');
}


function DoMemberInfoUpdate()
{
    global $db;
    global $gWebPage, $gHomePageUrl;

    if (MemberInfoCheck($errmsg))
    {
        if (IsUserAdmin())
        {
            $colvalues = 'm_level='.$gWebPage['fld_level'].',';
        }
        else
        {
            $colvalues  = '';
        }

        $colvalues .= 'm_ccode='.$gWebPage['fld_ccode'].',';
        $colvalues .= 'm_name='.$db->qstr($gWebPage['fld_name']).',';
        $colvalues .= 'm_fullname='.$db->qstr($gWebPage['fld_fullname']).',';
        $colvalues .= 'm_email='.$db->qstr($gWebPage['fld_email']).',';
        $colvalues .= 'm_view_email='.$gWebPage['fld_view_email'].',';
        $colvalues .= 'm_view_profile='.$gWebPage['fld_view_profile'].',';
        $colvalues .= 'm_homepage='.$db->qstr($gWebPage['fld_hpage']).',';
        $colvalues .= 'm_lid='.$db->qstr($gWebPage['fld_lid']).',';
        $colvalues .= 'm_theme='.$db->qstr($gWebPage['fld_theme']).',';
        $colvalues .= 'm_desc='.$db->qstr($gWebPage['fld_desc']);
        
        if (!empty($gWebPage['fld_password']))
        {
            $colvalues  .= ',m_password='.$db->qstr(md5($gWebPage['fld_password']));
        }

        $where   = 'm_id='.$gWebPage['uid'];

        if (DbSqlUpdate('sysmember', $colvalues, $where))
        {
            //change session info if appropriate
            if ($gWebPage['uid'] == UserGetID())
            {
            	SessionSetValue('lid', $gWebPage['fld_lid']);
            	SessionSetValue('uname', $gWebPage['fld_name']);
            	SessionSetValue('ufname', $gWebPage['fld_fullname']);
            	SessionSetValue('theme', $gWebPage['fld_theme']);
            	SessionSetValue('email', $gWebPage['fld_email']);
            }

            Header("Location: $gHomePageUrl/phpmod/cpanel.php");
            die();
        }

        $errmsg = $db->ErrorMsg();
    }

    MemberInfoShow(false, $errmsg);
}

function MemberInfoCheck(&$errmsg)
{
    global $gWebPage;

    CheckRequestRandom();

    $gWebPage['uid']            = RequestGetValue('uid', 0);
    $gWebPage['fld_level']      = RequestGetValue('fld_level', 1);
    $gWebPage['fld_name']       = RequestGetValue('fld_name', '', CLEAN_ALL);
    $gWebPage['fld_fullname']   = RequestGetValue('fld_fullname', '', CLEAN_ALL);
    $gWebPage['fld_email']      = RequestGetValue('fld_email', '', CLEAN_ALL);
    $gWebPage['fld_view_email'] = RequestGetValue('fld_view_email', 0);
    $gWebPage['fld_view_profile'] = RequestGetValue('fld_view_profile', 0);
    $gWebPage['fld_hpage']      = RequestGetValue('fld_hpage', '', CLEAN_ALL);
    $gWebPage['fld_ccode']      = RequestGetValue('fld_ccode', 0);
    $gWebPage['fld_desc']       = RequestGetValue('fld_desc', '', CLEAN_SAVE);
    $gWebPage['fld_lid']        = RequestGetValue('fld_lid', '');
    $gWebPage['fld_theme']      = RequestGetValue('fld_theme', '');
    $gWebPage['fld_password1']  = RequestGetValue('fld_password1', '', CLEAN_ALL);
    $gWebPage['fld_password2']  = RequestGetValue('fld_password2', '', CLEAN_ALL);
    $gWebPage['fld_password']   = '';

    if (!IsUserAdmin() && $gWebPage['uid'] != UserGetID())
    {
        $errmsg = _ERR_CHANGE_OTHER_MEMBER_INFO;
        return false;
    }

    if (!IsEmailValid($gWebPage['fld_email']))
    {
        $errmsg = _ERR_INVALID_EMAIL_FORMAT;
        return false;
    }

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

    if (!empty($gWebPage['fld_hpage']))
    {
        if (!IsHttpUrlValid($gWebPage['fld_hpage']))
        {
            $errmsg = _ERR_INVALID_HPAGE;
            return false;
        }
    }

    if (!empty($gWebPage['fld_password1']))
    {
        if ($gWebPage['fld_password1'] != $gWebPage['fld_password2'])
        {
            $errmsg = _ERR_INVALID_PASSWORD2;
            return false;
        }

        $gWebPage['fld_password'] = $gWebPage['fld_password1'];
    }

    return true;
}

function RenderMemberLevelRow($level)
{
    $out = "
    <tr>
      <td valign=\"top\" align=\"right\">"._FLD_MEMBER_LEVEL."</td>
      <td valign=\"top\" align=\"left\">:</td>
      <td valign=\"top\" align=\"left\"><input class=\"inputbox\" type=\"text\" name=\"fld_level\" size=\"12\" value=\"$level\"></td>
    </tr>";

    return $out;
}


?>
