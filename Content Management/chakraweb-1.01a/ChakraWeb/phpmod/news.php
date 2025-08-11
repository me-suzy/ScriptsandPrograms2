<?php 
// ----------------------------------------------------------------------
// ModName: comment.php
// Purpose: Add comment to web page
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

SetDynamicContent();

$op = RequestGetValue('op', '');

switch ($op)
{
case 'add':
    CheckOpForAdminOnly();
    NewsAddNew();
    break;
case 'edit':
    CheckOpForAdminOnly();
    NewsEdit();
    break;
case 'save':
    CheckOpForAdminOnly();
    NewsSave();
    break;
case 'delete':
    CheckOpForAdminOnly();
    NewsDelete();
    break;
case 'detail':
    NewsShowDetail();
    break;
case 'show':
    CheckOpForAdminOnly();
	NewsShowAttr(1);
	break;
case 'hide':
    CheckOpForAdminOnly();
	NewsShowAttr(0);
	break;
default:
    WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_UNKNOWN_OPERATION);
    break;
}

function NewsAddNew()
{
    global $db;
    global $gWebPage;

    if (NewsFormCheck())
    {
        $columns = 'news_id, news_lid, news_title, news_desc, news_content, upload_on';
        $values  = DbGetUniqueID('news');
        $values .= ','.$db->qstr(UserGetLID());
        $values .= ','.$db->qstr($gWebPage['fld_title']);
        $values .= ','.$db->qstr($gWebPage['fld_desc']);
        $values .= ','.$db->qstr($gWebPage['fld_content']);
        $values .= ','.date("YmdHis", time());

        DbSqlInsert('news', $columns, $values);
    }

    $redirect = RequestGetValue('path');
    Header("Location: $redirect");
}

function NewsFormCheck()
{
    global $gWebPage;

    $gWebPage['fld_id']      = RequestGetValue('fld_id', 0);
    $gWebPage['fld_title']   = RequestGetValue('fld_title', '', CLEAN_ALL);
    $gWebPage['fld_desc']    = RequestGetValue('fld_desc', '', CLEAN_SAVE);
    $gWebPage['fld_content'] = RequestGetValue('fld_content', '', CLEAN_SAVE);
    $gWebPage['fld_show']    = RequestGetValue('fld_show', 0);

    if (empty($gWebPage['fld_title']))
        return false;

    $gWebPage['fld_desc']    = EncloseParagraph($gWebPage['fld_desc']);
    $gWebPage['fld_content'] = EncloseParagraph($gWebPage['fld_content']);

    return true;
}

function NewsShowDetail()
{
    $news_id = RequestGetValue('id', 0);
    $sql = "select news_title, news_desc, news_content from news where news_id=$news_id";
    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $title   = $rs->fields[0];
        $content = sprintf("<h1>%s</h1>\n<div id=\"info\"><p>%s</p></div>\n<div>%s</div>", $rs->fields[0], $rs->fields[1], $rs->fields[2]);
    }
    else
    {
        $title   = _ERR_NEWS_NOT_FOUND_TITLE;
        $content = _ERR_NEWS_NOT_FOUND_MESSAGE;
    }

    NewsShowPage($title, $content);
}


function NewsEdit()
{
    global $gFolderId;
    global $gBaseLocalPath;

    $title = _NEWS_EDIT_TITLE;
    $content = sprintf("<h1>%s</h1>\n%s\n", _NEWS_EDIT_TITLE, _NEWS_EDIT_MESSAGE);

    $news_id = RequestGetValue('id', 0);
    $sql = "select news_title, news_desc, news_content, news_show from news where news_id=$news_id";
    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $fld_title  = $rs->fields[0];
        $fld_desc   = $rs->fields[1];
        $fld_content= $rs->fields[2];
        $fld_show   = $rs->fields[3];
    }
    else
    {
        $fld_title  = _ERR_NEWS_NOT_FOUND_TITLE;
        $fld_desc   = _ERR_NEWS_NOT_FOUND_MESSAGE;
        $fld_content= '';
        $fld_show   = 0;
    }

    $news_attr_row = '
    <tr>
      <td valign="top" align="right">'._FLD_ATTR.'</td>
      <td valign="top" align="left">:</td>
      <td valign="top" align="left">'.CheckBox('fld_show', '1', $fld_show).' '._FLD_SHOW.'</td>
    </tr>';

    $params = array();
    $params['news_form_title']    = '';
    //$params['path']      = FindPathFromFolderId($gFolderId)."index.html"; 
    $params['fld_id']    = $news_id;
    $params['fld_title'] = $fld_title;
    $params['fld_desc_editor']    = RenderHtmlEditor('fld_desc', $fld_desc, 'chmini', 500, 90);
    $params['fld_content_editor'] = RenderHtmlEditor('fld_content', $fld_content, 'chmini', 500, 140);
    $params['op'] = 'save';
    $params['news_attr_row'] = $news_attr_row;

    $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/news.htm';
    $content .= LoadContentFile($fname, $params);

    NewsShowPage($title, $content);
}

function NewsSave()
{
    global $db;
    global $gFolderId;
    global $gWebPage;

    if (NewsFormCheck())
    {
        $colvalues  = 'news_title='.$db->qstr($gWebPage['fld_title']);
        $colvalues .= ', news_desc='.$db->qstr($gWebPage['fld_desc']);
        $colvalues .= ', news_content='.$db->qstr($gWebPage['fld_content']);
        $colvalues .= ', news_show='.$db->qstr($gWebPage['fld_show']);

        $where = 'news_id = '.$gWebPage['fld_id'];

        DbSqlUpdate('news', $colvalues, $where);
    }

    $redirect = RequestGetValue('path');
    Header("Location: $redirect");
}

function NewsDelete()
{
    global $gFolderId;

    $news_id = RequestGetValue('id', 0);
    $where   = "news_id=$news_id";
    DbSqlDelete('news', $where);
    
    $redirect = FindPathFromFolderId($gFolderId)."index.html"; 
    Header("Location: $redirect");
}

function NewsShowAttr($show)
{
    global $gFolderId;

    $news_id = RequestGetValue('id', 0);

    $colvalues = "news_show=$show";
    $where     = "news_id=$news_id";
    DbSqlUpdate('news', $colvalues, $where);
    
    $redirect = FindPathFromFolderId($gFolderId)."index.html"; 
    Header("Location: $redirect");
}


function NewsShowPage($title, $content)
{
    global $gFolderId, $gPageId;
    global $gRequestPath, $gCurrentUrlPath;
    global $gRequestFile;
    global $gHomePageHeader, $gHomePageFooter;
    global $gBaseUrlPath;
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
