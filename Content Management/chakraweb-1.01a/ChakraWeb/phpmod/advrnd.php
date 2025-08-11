<?php 
// ----------------------------------------------------------------------
// ModName: advrnd.php
// Purpose: Maintain random advertizing text
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

SetDynamicContent();
CheckOpForAdminOnly();

$gFolderId = 0;
$gPageId = 0;
$gRequestPath = '/phpmod/';
$gCurrentUrlPath = $gBaseUrlPath.'/phpmod/';
$gRequestFile = 'advrnd.php';

$gCurrentUrlPath = '/';

DBGetFolderData(0);

$gPageNavigation = array();
$gPageNavigation[] = array($gHomePageUrl.$gBaseUrlPath."/index.html", _NAV_FRONTPAGE);
$gPageNavigation[] = array($gHomePageUrl."/phpmod/cpanel.php", _NAV_CONTROL_PANEL);
$gPageNavigation[] = array($gHomePageUrl."/phpmod/advrnd.php", _NAV_ADVRND);


$op = RequestGetValue('op', 'list');
switch ($op)
{
case 'add':
	AdvRndAddNew();
	break;
case 'delete':
	AdvRndDelete();
	break;
case 'edit':
	AdvRndEdit();
	break;
case 'save':
	AdvRndSave();
	break;
case 'list':
	AdvRndShowList();
	break;
case 'resethits':
	AdvRndResetHits();
	break;
default:
    WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_UNKNOWN_OPERATION);
    break;
}

function AdvRndShowList()
{
    global $gBaseLocalPath;

    $params = array();

    $params['fld_language'] = UserGetLangName();
    $params['fld_advrnd_row'] = RenderAdvRndList();
    $params['fld_key'] = '';
    $params['fld_title'] = '';
    $params['fld_text'] = '';
    $params['page_message'] = '';

    //$params['fld_text_editor']    = RenderHtmlEditor('fld_text', $params['fld_text'], 'chmini', 500, 90);
    $params['fld_text_editor'] = RenderTextArea('fld_text', $params['fld_text'], 80, 16);

    $fname   = $gBaseLocalPath.'_lang/'.UserGetLID().'/advrnd.htm';
    $content = LoadContentFile($fname, $params);

    AdvRndShowPage(_ADVRND_TITLE, $content);
}

function AdvRndResetHits()
{
    global $db;

    $sql = 'update advrnd set adv_hits=0 where adv_lid='.$db->qstr(UserGetLID());
    DbExecute($sql);

    Header("Location: /phpmod/advrnd.php?op=list");
}


function RenderAdvRndList()
{
    global $db;

    $list = '';

    $sql = 'select adv_id, adv_key, adv_title, adv_hits, adv_active from advrnd 
            where adv_lid = '.$db->qstr(UserGetLID()).' order by adv_key, adv_title';

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        while (!$rs->EOF)
        {
            $status = ($rs->fields[4] ? _FLD_ACTIVE : _FLD_NOACTIVE);

            $op  = '<font face="Arial" size=1>';
            $op .= '['.HRef("/phpmod/advrnd.php?op=edit&id=".$rs->fields[0], _NAV_EDIT).']&nbsp;';
            $op .= '['.HRef("/phpmod/advrnd.php?op=delete&id=".$rs->fields[0], _NAV_DELETE).']';
            $op .= '</font>';

            $list .= "
  <tr>
    <td id=\"tbl_text\" align=\"left\" valign=\"top\">".$rs->fields[1]."</td>
    <td id=\"tbl_text\" align=\"left\" valign=\"top\">".$rs->fields[2]."</td>
    <td id=\"tbl_text\" align=\"right\" valign=\"top\">".$rs->fields[3]."</td>
    <td id=\"tbl_text\" align=\"left\" valign=\"top\">".$status."</td>
    <td id=\"tbl_text\" align=\"left\" valign=\"top\">".$op."</td>
  </tr>";

            $rs->MoveNext();
        }
    }

    return $list;
}

function AdvRndAddNew()
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
        $columns = 'adv_id, adv_key, adv_lid, adv_title, adv_text';
        $values  = DbGetUniqueID('advrnd');
        $values .= ','.$db->qstr($adv_key);
        $values .= ','.$db->qstr(UserGetLID());
        $values .= ','.$db->qstr($adv_title);
        $values .= ','.$db->qstr($adv_text);

        if (!DbSqlInsert('advrnd', $columns, $values))
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
        $errmsg = _ERR_INVALID_ADVRND;
    }

    $params = array();

    $params['fld_language'] = UserGetLangName();
    $params['fld_advrnd_row'] = RenderAdvRndList();
    $params['fld_key']    = $adv_key;
    $params['fld_title']  = $adv_title;
    $params['fld_text']   = $adv_text;
    $params['page_message'] = $errmsg;
    
    //$params['fld_text_editor']    = RenderHtmlEditor('fld_text', $params['fld_text'], 'chmini', 500, 90);
    $params['fld_text_editor'] = RenderTextArea('fld_text', $params['fld_text'], 80, 16);

    $fname   = $gBaseLocalPath.'_lang/'.UserGetLID().'/advrnd.htm';
    $content = LoadContentFile($fname, $params);

    AdvRndShowPage(_ADVRND_TITLE, $content);
}


function AdvRndDelete()
{
    global $db;

    $adv_id = RequestGetValue('id', 0);
    if ($adv_id > 0)
    {
        $where = "adv_id=$adv_id";
        DbSqlDelete('advrnd', $where);
    }

    Header("Location: /phpmod/advrnd.php");
}

function AdvRndEdit()
{
    global $db;
    global $gBaseLocalPath;
    global $gPageNavigation;

    $adv_id = RequestGetValue('id', 0);

    $gPageNavigation[] = array($gHomePageUrl."/phpmod/advrnd.php?op=edit&id=".$adv_id, _NAV_ADVRND_EDIT);

    $params = array();

    $sql = "select adv_key, adv_title, adv_text, adv_active from advrnd where adv_id=$adv_id";
    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $params['fld_key'] = $rs->fields[0];
        $params['fld_title'] = $rs->fields[1];
        $params['fld_text'] = $rs->fields[2];
        $params['fld_active'] = $rs->fields[3];
    }
    else
    {
        $params['fld_key'] = '';
        $params['fld_title'] = '';
        $params['fld_text'] = '';
        $params['fld_active'] = 1;
    }

    $params['fld_id'] = $adv_id;
    $params['page_message'] = '';

    //$params['fld_text_editor']    = RenderHtmlEditor('fld_text', $params['fld_text'], 'chsmall', 440, 260);
    $params['fld_text_editor'] = RenderTextArea('fld_text', $params['fld_text'], 80, 16);

    $params['chk_active']         = CheckBox('fld_active', '1', $params['fld_active']);

    $fname   = $gBaseLocalPath.'_lang/'.UserGetLID().'/advrnd_edit.htm';
    $content = LoadContentFile($fname, $params);

    AdvRndShowPage(_ADVRND_EDIT_TITLE, $content);
}

function AdvRndSave()
{
    global $gBaseLocalPath;
    global $db;

    CheckRequestRandom();

    $adv_id    = RequestGetValue('fld_id', 0);

    $adv_key    = RequestGetValue('fld_key', '', CLEAN_ALL);
    $adv_title  = RequestGetValue('fld_title', '', CLEAN_ALL);
    $adv_text   = RequestGetValue('fld_text', '');
    $adv_active = RequestGetValue('fld_active', 0);

    if (!empty($adv_key) && !empty($adv_title) && !empty($adv_text))
    {
        $colvalues  = 'adv_key='.$db->qstr($adv_key);
        $colvalues .= ', adv_title='.$db->qstr($adv_title);
        $colvalues .= ', adv_text='.$db->qstr($adv_text);
        $colvalues .= ', adv_active='.$adv_active;

        $where = "adv_id=$adv_id";
        
        if (DbSqlUpdate('advrnd', $colvalues, $where))
        {
            Header("Location: /phpmod/advrnd.php");
            die();
        }
        $errmsg = $db->ErrorMsg();
    }
    else
    {
        $errmsg = _ERR_INVALID_ADVRND;
    }

    $params = array();
    $params['fld_id'] = $adv_id;
    $params['fld_key'] = $adv_key;
    $params['fld_title'] = $adv_title;
    $params['fld_text'] = $adv_text;
    $params['fld_active'] = $adv_active;
    $params['page_message'] = $errmsg;

    //$params['fld_text_editor']    = RenderHtmlEditor('fld_text', $params['fld_text'], 'chstd', 500, 260);
    $params['fld_text_editor'] = RenderTextArea('fld_text', $params['fld_text'], 80, 16);

    $params['chk_active']         = CheckBox('fld_active', '1', $params['fld_active']);

    $fname   = $gBaseLocalPath.'_lang/'.UserGetLID().'/advrnd_edit.htm';
    $content = LoadContentFile($fname, $params);

    AdvRndShowPage(_ADVRND_EDIT_TITLE, $content);
}


function AdvRndShowPage($title, $content)
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
