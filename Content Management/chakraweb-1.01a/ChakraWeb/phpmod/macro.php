<?php 
// ----------------------------------------------------------------------
// ModName: macro.php
// Purpose: Editing table macrotext
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

SetDynamicContent();
CheckOpForAdminOnly();

$gMgmtMenu = false;

$gFolderId = 0;
$gPageId = 0;
$gRequestPath = '/';
$gCurrentUrlPath = $gBaseUrlPath.'/';
$gRequestFile = 'macro.php';

$gCurrentUrlPath = '/';

DBGetFolderData(0);

$gPageNavigation = array();
$gPageNavigation[] = array($gHomePageUrl.$gBaseUrlPath."/index.html", _NAV_FRONTPAGE);
$gPageNavigation[] = array($gHomePageUrl."/phpmod/cpanel.php", _NAV_CONTROL_PANEL);
$gPageNavigation[] = array($gHomePageUrl."/phpmod/macro.php", _NAV_MACROTEXT);


$op = RequestGetValue('op', 'list');
switch ($op)
{
case 'add':
	MacroTextAddNew();
	break;
case 'delete':
	MacroTextDelete();
	break;
case 'edit':
	MacroTextEdit();
	break;
case 'save':
	MacroTextSave();
	break;
case 'list':
	MacroTextShowList();
	break;
default:
    WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_UNKNOWN_OPERATION);
    break;
}

function MacroTextShowList()
{
    global $gBaseLocalPath;

    $params = array();

    $params['fld_language'] = UserGetLangName();
    $params['fld_macro_row'] = RenderMacroTextList();
    $params['fld_key'] = '';
    $params['fld_title'] = '';
    $params['fld_content'] = '';
    $params['page_message'] = '';
    $params['fld_content_editor']    = RenderHtmlEditor('fld_content', $params['fld_content'], 'chmini', 500, 90);

    $fname   = $gBaseLocalPath.'_lang/'.UserGetLID().'/macrotext.htm';
    $content = LoadContentFile($fname, $params);

    MacroTextShowPage(_MACROTEXT_TITLE, $content);

}

function RenderMacroTextList()
{
    global $db;

    $list = '';

    $sql = 'select mac_key, mac_title, mac_active from macrotext 
            where mac_lid = '.$db->qstr(UserGetLID()).' order by mac_key';

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        while (!$rs->EOF)
        {
            $status = ($rs->fields[2] ? _FLD_ACTIVE : _FLD_NOACTIVE);

            $op  = '<font face="Arial" size=1>';
            $op .= '['.HRef("/phpmod/macro.php?op=edit&key=".$rs->fields[0], _NAV_EDIT).']&nbsp;';
            $op .= '['.HRef("/phpmod/macro.php?op=delete&key=".$rs->fields[0], _NAV_DELETE).']';
            $op .= '</font>';

            $list .= "
  <tr>
    <td id=\"tbl_text\" align=\"left\" valign=\"top\">".$rs->fields[0]."</td>
    <td id=\"tbl_text\" align=\"left\" valign=\"top\">".$rs->fields[1]."</td>
    <td id=\"tbl_text\" align=\"left\" valign=\"top\">".$status."</td>
    <td id=\"tbl_text\" align=\"left\" valign=\"top\">".$op."</td>
  </tr>";

            $rs->MoveNext();
        }
    }

    return $list;
}

function MacroTextAddNew()
{
    global $gBaseLocalPath;
    global $db;

    CheckRequestRandom();

    $errmsg = '';

    $mac_key    = RequestGetValue('fld_key', '', CLEAN_ALL);
    $mac_title  = RequestGetValue('fld_title', '', CLEAN_ALL);
    $mac_content   = RequestGetValue('fld_content', '');

    if (!empty($mac_key) && !empty($mac_title) && !empty($mac_content))
    {
        $columns = 'mac_key, mac_lid, mac_title, mac_content';

        $values  = $db->qstr($mac_key);
        $values .= ','.$db->qstr(UserGetLID());
        $values .= ','.$db->qstr($mac_title);
        $values .= ','.$db->qstr($mac_content);

        if (!DbSqlInsert('macrotext', $columns, $values))
            $errmsg = $db->ErrorMsg();
        else
        {
            $mac_key    = '';
            $mac_title  = '';
            $mac_content   = '';
        }
    }
    else
    {
        $errmsg = _ERR_INVALID_MACROTEXT;
    }

    $params = array();

    $params['fld_language'] = UserGetLangName();
    $params['fld_macro_row'] = RenderMacroTextList();
    $params['fld_key']    = $mac_key;
    $params['fld_title']  = $mac_title;
    $params['fld_content']   = $mac_content;
    $params['page_message'] = $errmsg;
    $params['fld_content_editor']    = RenderHtmlEditor('fld_content', $params['fld_content'], 'chmini', 500, 90);

    $fname   = $gBaseLocalPath.'_lang/'.UserGetLID().'/macrotext.htm';
    $content = LoadContentFile($fname, $params);

    MacroTextShowPage(_MACROTEXT_TITLE, $content);
}


function MacroTextDelete()
{
    global $db;

    $mac_key = RequestGetValue('key', '');
    if (!empty($mac_key))
    {
        $where  = 'mac_key='.$db->qstr($mac_key);
        $ehere .= ' and mac_lid='.$db->qstr(UserGetLID());
        DbSqlDelete('macrotext', $where);
    }

    Header("Location: /phpmod/macro.php");
}

function MacroTextEdit()
{
    global $db;
    global $gBaseLocalPath;
    global $gPageNavigation;

    $mac_key = RequestGetValue('key', '');

    $gPageNavigation[] = array($gHomePageUrl."/phpmod/macro.php?op=edit&key=".$mac_key, _NAV_MACROTEXT_EDIT);

    $params = array();

    $sql  = 'select mac_title, mac_content, mac_active from macrotext where mac_key='.$db->qstr($mac_key);
    $sql .= ' and mac_lid='.$db->qstr(UserGetLID());

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $params['fld_title'] = $rs->fields[0];
        $params['fld_content'] = $rs->fields[1];
        $params['fld_active'] = $rs->fields[2];
    }
    else
    {
        $params['fld_title'] = '';
        $params['fld_content'] = '';
        $params['fld_active'] = 1;
    }

    $params['fld_key'] = $mac_key;
    $params['fld_newkey'] = $mac_key;
    $params['page_message'] = '';
    $params['fld_content_editor']    = RenderHtmlEditor('fld_content', $params['fld_content'], 'chstd', 500, 260);
    $params['chk_active']            = CheckBox('fld_active', '1', $params['fld_active']);

    $fname   = $gBaseLocalPath.'_lang/'.UserGetLID().'/macrotext_edit.htm';
    $content = LoadContentFile($fname, $params);

    MacroTextShowPage(_MACROTEXT_EDIT_TITLE, $content);
}

function MacroTextSave()
{
    global $gBaseLocalPath;
    global $db;

    CheckRequestRandom();

    $mac_key    = RequestGetValue('fld_key', '', CLEAN_ALL);

    $mac_newkey = RequestGetValue('fld_newkey', '', CLEAN_ALL);
    $mac_title  = RequestGetValue('fld_title', '', CLEAN_ALL);
    $mac_content= RequestGetValue('fld_content', '');
    $mac_active = RequestGetValue('fld_active', 0);

    if (!empty($mac_newkey) && !empty($mac_title) && !empty($mac_content))
    {
        $colvalues  = 'mac_key='.$db->qstr($mac_newkey);
        $colvalues .= ', mac_title='.$db->qstr($mac_title);
        $colvalues .= ', mac_content='.$db->qstr($mac_content);
        $colvalues .= ', mac_active='.$mac_active;

        $where  = 'mac_key='.$db->qstr($mac_key);
        $where .= ' and mac_lid='.$db->qstr(UserGetLID());
        
        if (DbSqlUpdate('macrotext', $colvalues, $where))
        {
            Header("Location: /phpmod/macro.php");
            die();
        }
        $errmsg = $db->ErrorMsg();
    }
    else
    {
        $errmsg = _ERR_INVALID_MACROTEXT;
    }

    $params = array();
    $params['fld_key'] = $mac_key;
    $params['fld_newkey'] = $mac_newkey;
    $params['fld_title'] = $mac_title;
    $params['fld_content'] = $mac_content;
    $params['fld_active'] = $mac_active;
    $params['page_message'] = $errmsg;
    $params['fld_content_editor']    = RenderHtmlEditor('fld_content', $params['fld_content'], 'chstd', 500, 260);
    $params['chk_active']            = CheckBox('fld_active', '1', $params['fld_active']);

    $fname   = $gBaseLocalPath.'_lang/'.UserGetLID().'/macrotext_edit.htm';
    $content = LoadContentFile($fname, $params);

    MacroTextShowPage(_MACROTEXT_EDIT_TITLE, $content);
}


function MacroTextShowPage($title, $content)
{
    global $gHomePageHeader, $gHomePageFooter;
    global $gWebPage;

    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
    $gWebPage['page_title']     = $title;
    $gWebPage['page_content']   = $content;

    DoShowPage(TPL_WEB_PAGE);
}


?>
