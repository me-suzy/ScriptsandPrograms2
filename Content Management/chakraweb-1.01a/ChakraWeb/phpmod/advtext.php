<?php 
// ----------------------------------------------------------------------
// ModName: advtext.php
// Purpose: Maintain advertizing text
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

SetDynamicContent();
CheckOpForAdminOnly();

//$gFolderId = 0;
//$gPageId = 0;
//$gRequestPath = '/phpmod/';
//$gCurrentUrlPath = $gBaseUrlPath.'/phpmod/';
//$gRequestFile = 'advtext.php';

$gFolderId = 0;
$gRequestPath = '/'; //FindPathFromFolderId($gFolderId);
$gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
$gRequestFile = 'index.html';
$gPageId = RequestGetValue('id', 0);

DBGetFolderData(0);

$gPageNavigation = array();
$gPageNavigation[] = array($gHomePageUrl.$gBaseUrlPath."/index.html", _NAV_FRONTPAGE);
$gPageNavigation[] = array($gHomePageUrl."/phpmod/cpanel.php", _NAV_CONTROL_PANEL);
$gPageNavigation[] = array($gHomePageUrl."/phpmod/advtext.php", _NAV_ADVTEXT);


$op = RequestGetValue('op', 'list');
switch ($op)
{
case 'add':
	AdvTextAddNew();
	break;
case 'delete':
	AdvTextDelete();
	break;
case 'edit':
	AdvTextEdit();
	break;
case 'save':
	AdvTextSave();
	break;
case 'list':
	AdvTextShowList();
	break;
case 'resethits':
	AdvTextResetHits();
	break;
default:
    WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_UNKNOWN_OPERATION);
    break;
}

function AdvTextShowList()
{
    global $gBaseLocalPath;

    $params = array();

    $params['fld_language'] = UserGetLangName();
    $params['fld_advtext_row'] = RenderAdvTextList();
    $params['fld_key'] = '';
    $params['fld_title'] = '';
    $params['fld_text'] = '';
    $params['page_message'] = '';

    //$params['fld_text_editor']    = RenderHtmlEditor('fld_text', $params['fld_text'], 'chsmall', 440, 90);
    $params['fld_text_editor'] = RenderTextArea('fld_text', $params['fld_text'], 80, 16);

    $params['fld_text_editor'] = RenderTextArea('fld_text', $params['fld_text'], 80, 16);

    $fname   = $gBaseLocalPath.'_lang/'.UserGetLID().'/advtext.htm';
    $content = LoadContentFile($fname, $params);

    AdvTextShowPage(_ADVTEXT_TITLE, $content);
}

function AdvTextResetHits()
{
    global $db;

    $sql = 'update advtext set adv_hits=0 where adv_lid='.$db->qstr(UserGetLID());
    DbExecute($sql);

    Header("Location: /phpmod/advtext.php?op=list");
}



function RenderAdvTextList()
{
    global $db;

    $list = '';

    $sql = 'select adv_key, adv_title, adv_hits, adv_active from advtext 
            where adv_lid = '.$db->qstr(UserGetLID()).' order by adv_key';

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        while (!$rs->EOF)
        {
            $status = ($rs->fields[3] ? _FLD_ACTIVE : _FLD_NOACTIVE);

            $op  = '<font face="Arial" size=1>';
            $op .= '['.HRef("/phpmod/advtext.php?op=edit&key=".$rs->fields[0], _NAV_EDIT).']&nbsp;';
            $op .= '['.HRef("/phpmod/advtext.php?op=delete&key=".$rs->fields[0], _NAV_DELETE).']';
            $op .= '</font>';

            $list .= "
  <tr>
    <td id=\"tbl_text\" align=\"left\" valign=\"top\">".$rs->fields[0]."</td>
    <td id=\"tbl_text\" align=\"left\" valign=\"top\">".$rs->fields[1]."</td>
    <td id=\"tbl_text\" align=\"right\" valign=\"top\">".$rs->fields[2]."</td>
    <td id=\"tbl_text\" align=\"left\" valign=\"top\">".$status."</td>
    <td id=\"tbl_text\" align=\"left\" valign=\"top\">".$op."</td>
  </tr>";

            $rs->MoveNext();
        }
    }

    return $list;
}

function AdvTextAddNew()
{
    global $gBaseLocalPath;
    global $db;

    CheckRequestRandom();

    $errmsg = '';

    $adv_key    = RequestGetValue('fld_key', '', CLEAN_ALL);
    $adv_title  = RequestGetValue('fld_title', '', CLEAN_ALL);
    $adv_text   = RequestGetValue('fld_text', '');

    if (!empty($adv_key) && !empty($adv_title) && !empty($adv_text))
    {
        $columns = 'adv_key, adv_lid, adv_title, adv_text';

        $values  = $db->qstr($adv_key);
        $values .= ','.$db->qstr(UserGetLID());
        $values .= ','.$db->qstr($adv_title);
        $values .= ','.$db->qstr($adv_text);

        if (!DbSqlInsert('advtext', $columns, $values))
            $errmsg = $db->ErrorMsg();
        else
        {
            $adv_key    = '';
            $adv_title  = '';
            $adv_text   = '';
        }
    }
    else
    {
        $errmsg = _ERR_INVALID_ADVTEXT;
    }

    $params = array();

    $params['fld_language'] = UserGetLangName();
    $params['fld_advtext_row'] = RenderAdvTextList();
    $params['fld_key']    = $adv_key;
    $params['fld_title']  = $adv_title;
    $params['fld_text']   = $adv_text;
    $params['page_message'] = $errmsg;

    //$params['fld_text_editor']    = RenderHtmlEditor('fld_text', $params['fld_text'], 'chsmall', 440, 90);
    $params['fld_text_editor'] = RenderTextArea('fld_text', $params['fld_text'], 80, 16);

    $fname   = $gBaseLocalPath.'_lang/'.UserGetLID().'/advtext.htm';
    $content = LoadContentFile($fname, $params);

    AdvTextShowPage(_ADVTEXT_TITLE, $content);
}


function AdvTextDelete()
{
    global $db;

    $adv_key = RequestGetValue('key', '');
    if (!empty($adv_key))
    {
        $where  = 'adv_key='.$db->qstr($adv_key);
        $where .= ' and adv_lid='.$db->qstr(UserGetLID());
        DbSqlDelete('advtext', $where);
    }

    Header("Location: /phpmod/advtext.php");
}

function AdvTextEdit()
{
    global $db;
    global $gBaseLocalPath;
    global $gPageNavigation;

    $adv_key = RequestGetValue('key', '');

    $gPageNavigation[] = array($gHomePageUrl."/phpmod/advtext.php?op=edit&key=".$adv_key, _NAV_ADVTEXT_EDIT);

    $params = array();

    $sql  = 'select adv_title, adv_text, adv_active from advtext where adv_key='.$db->qstr($adv_key);
    $sql .= ' and adv_lid='.$db->qstr(UserGetLID());

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $params['fld_title'] = $rs->fields[0];
        $params['fld_text'] = $rs->fields[1];
        $params['fld_active'] = $rs->fields[2];
    }
    else
    {
        $params['fld_title'] = '';
        $params['fld_text'] = '';
        $params['fld_active'] = 1;
    }

    $params['fld_key'] = $adv_key;
    $params['fld_newkey'] = $adv_key;
    $params['page_message'] = '';

    //$params['fld_text_editor']    = RenderHtmlEditor('fld_text', $params['fld_text'], 'chsmall', 440, 260);
    $params['fld_text_editor'] = RenderTextArea('fld_text', $params['fld_text'], 80, 16);

    $params['chk_active']         = CheckBox('fld_active', '1', $params['fld_active']);

    $fname   = $gBaseLocalPath.'_lang/'.UserGetLID().'/advtext_edit.htm';
    $content = LoadContentFile($fname, $params);

    AdvTextShowPage(_ADVTEXT_EDIT_TITLE, $content);
}

function AdvTextSave()
{
    global $gBaseLocalPath;
    global $db;

    CheckRequestRandom();

    $adv_key    = RequestGetValue('fld_key', '', CLEAN_ALL);

    $adv_newkey = RequestGetValue('fld_newkey', '', CLEAN_ALL);
    $adv_title  = RequestGetValue('fld_title', '', CLEAN_ALL);
    $adv_text   = RequestGetValue('fld_text', '');
    $adv_active = RequestGetValue('fld_active', 0);

    if (!empty($adv_newkey) && !empty($adv_title) && !empty($adv_text))
    {
        $colvalues  = 'adv_key='.$db->qstr($adv_newkey);
        $colvalues .= ', adv_title='.$db->qstr($adv_title);
        $colvalues .= ', adv_text='.$db->qstr($adv_text);
        $colvalues .= ', adv_active='.$adv_active;

        $where  = 'adv_key='.$db->qstr($adv_key);
        $where .= ' and adv_lid='.$db->qstr(UserGetLID());
        
        if (DbSqlUpdate('advtext', $colvalues, $where))
        {
            Header("Location: /phpmod/advtext.php");
            die();
        }
        $errmsg = $db->ErrorMsg();
    }
    else
    {
        $errmsg = _ERR_INVALID_ADVTEXT;
    }

    $params = array();
    $params['fld_key'] = $adv_key;
    $params['fld_newkey'] = $adv_newkey;
    $params['fld_title'] = $adv_title;
    $params['fld_text'] = $adv_text;
    $params['fld_active'] = $adv_active;

    $params['page_message'] = $errmsg;

    //$params['fld_text_editor']    = RenderHtmlEditor('fld_text', $params['fld_text'], 'chstd', 500, 260);
    $params['fld_text_editor'] = RenderTextArea('fld_text', $params['fld_text'], 80, 16);

    $params['chk_active']         = CheckBox('fld_active', '1', $params['fld_active']);

    $fname   = $gBaseLocalPath.'_lang/'.UserGetLID().'/advtext_edit.htm';
    $content = LoadContentFile($fname, $params);

    AdvTextShowPage(_ADVTEXT_EDIT_TITLE, $content);
}


function AdvTextShowPage($title, $content)
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
