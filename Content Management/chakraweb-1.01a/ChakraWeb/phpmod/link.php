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
    LinkAddNew();
    break;
case 'add_some':
    LinkAddSome();
    break;
case 'upd':
    LinkUpdate();
    break;
case 'del':
case 'delete':
    LinkDelete();
    break;
case 'show':
    LinkInitVars();
    LinkFormShow('add', false);
    break;
case 'edit':
    LinkFormShow('upd', true);
    break;
case 'approve':
    LinkApprove();
    break;
default:
    WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_UNKNOWN_OPERATION);
    break;
}

function LinkAddNew()
{
    global $db;
    global $gFolderId;
    global $gWebPage, $gPageId;
    global $gCurrentUrlPath;
    global $gRequestPath, $gRequestFile;

    CheckRequestRandom();

    ParseRequestPathAndFile(RequestGetValue('path'), $gRequestPath, $gRequestFile);
    $gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
    $gPageId = RequestGetValue('id', 0);

    if (LinkFormCheck($errmsg))
    {
        $columns = 'link_id, page_id, page_lid, link_url, link_title, link_desc, link_note, 
                    link_order, link_active, link_show, link_great, m_id, upload_on';

        $values  = DbGetUniqueID('link');
        $values .= ','.$gPageId;
        $values .= ','.$db->qstr(UserGetLID());
        $values .= ','.$db->qstr($gWebPage['fld_url']);
        $values .= ','.$db->qstr($gWebPage['fld_title']);
        $values .= ','.$db->qstr($gWebPage['fld_desc']);
        $values .= ','.$db->qstr($gWebPage['fld_note']);

        if (IsUserAdmin())
        {
            $values .= ','.$gWebPage['fld_order'];
            $values .= ','.$gWebPage['fld_active'];
            $values .= ','.$gWebPage['fld_show'];
            $values .= ','.$gWebPage['fld_great'];
        }
        else
            $values .= ', '.DEFAULT_ORDER.', 0, 0, 0';

        $values .= ','.UserGetID();
        $values .= ','.date("YmdHis", time());

        if (DbSqlInsert('link', $columns, $values))
        {
            if (IsUserAdmin())
            {
                Header("Location: $gRequestPath$gRequestFile");
            }
            else
            {
                DBGetFolderData($gFolderId);

                $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
                $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
                $gWebPage['page_title']     = _LINK_ADD_TITLE;
                $gWebPage['page_desc']      = $gFolder['desc'];
                $gWebPage['page_keywords']  = $gFolder['keywords'];
                $gWebPage['page_sidebar']   = RenderPageSidebar();
                
                $gWebPage['page_content'] = sprintf("<h1>%s</h1>\n%s", _LINK_ADD_TITLE, _LINK_ADD_THANKYOU);
                DoShowPage(TPL_WEB_PAGE);
            }
            die();
        }
        $errmsg = $db->ErrorMsg();
    }

    LinkFormShow('add', false, $errmsg);
}

function LinkAddSome()
{
    global $db;
    global $gFolderId;
    global $gWebPage, $gPageId;
    global $gCurrentUrlPath;
    global $gRequestPath, $gRequestFile;

    CheckRequestRandom();
    CheckOpForAdminOnly();

    ParseRequestPathAndFile(RequestGetValue('path'), $gRequestPath, $gRequestFile);
    $gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
    $gPageId = RequestGetValue('id', 0);

    $links = RequestGetValue('fld_links', '');
    $arlinks = explode("\n", $links);

    foreach($arlinks as $link)
    {
        list($url, $title, $desc) = explode('|', $link);
        
		if (!empty($url) && !empty($title))
		{
        	$columns = 'link_id, page_id, page_lid, link_url, link_title, link_desc,  
            	        link_order, link_active, link_show, link_great, m_id, upload_on';

	        $values  = DbGetUniqueID('link');
    	    $values .= ','.$gPageId;
	        $values .= ','.$db->qstr(UserGetLID());
    	    $values .= ','.$db->qstr($url);
	        $values .= ','.$db->qstr($title);
    	    $values .= ','.$db->qstr($desc);

	        $values .= ', '.DEFAULT_ORDER.', 1, 1, 0';
    	    $values .= ','.UserGetID();
	        $values .= ','.date("YmdHis", time());

    	    DbSqlInsert('link', $columns, $values);
		}
    }

    Header("Location: $gRequestPath$gRequestFile");
}

function LinkUpdate()
{
    global $db;
    global $gFolderId, $gPageId;
    global $gBaseUrlPath;
    global $gRequestPath, $gRequestFile, $gCurrentUrlPath;
    global $gWebPage;
    
    CheckRequestRandom();

    if (!IsUserAdmin())
        WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_ADM_AUTHOR_ONLY);

    $from = RequestGetValue('from');
    if (!empty($from))
    {
        $redirect = '/phpmod/todo.php?op=link';
    }
    else
    {
        $gRequestPath = FindPathFromFolderId($gFolderId);
        $gRequestFile = 'index.html';
        $gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
        $gPageId = 0;

        $redirect = $gCurrentUrlPath.$gRequestFile;
    }

    if (LinkFormCheck($errmsg))
    {
        $colvalues .= 'link_url='.$db->qstr($gWebPage['fld_url']).',';
        $colvalues .= 'link_title='.$db->qstr($gWebPage['fld_title']).',';
        $colvalues .= 'link_desc='.$db->qstr($gWebPage['fld_desc']).',';
        $colvalues .= 'link_note='.$db->qstr($gWebPage['fld_note']).',';
        $colvalues .= 'link_order='.$gWebPage['fld_order'].',';
        $colvalues .= 'link_show='.$gWebPage['fld_show'].',';
        $colvalues .= 'link_active='.$gWebPage['fld_active'].',';
        $colvalues .= 'link_great='.$gWebPage['fld_great'];

        $where =  'link_id='.$gWebPage['fld_id'];

        if (DbSqlUpdate('link', $colvalues, $where))
        {
            Header("Location: $redirect");
            die();
        }

        $errmsg = $db->ErrorMsg();
    }

    LinkFormShow('upd', false, $errmsg);
}

function LinkDelete()
{
    global $gFolderId;
    global $gRequestPath, $gRequestFile;

    if (!IsUserAdmin())
        WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_ADM_AUTHOR_ONLY);

    $link_id = RequestGetValue('fld_id', 0);
    if ($link_id > 0)
    {
        $where = "link_id=$link_id";
        DbSqlDelete('link', $where);
    }

    $from = RequestGetValue('from');
    if (!empty($from))
    {
        $redirect = '/phpmod/todo.php?op=link';
    }
    else
    {
        $gRequestPath = FindPathFromFolderId($gFolderId);
        $gRequestFile = 'index.html';

        $redirect = $gRequestPath.$gRequestFile;
    }

    Header("Location: $redirect");
}

function LinkApprove()
{
    global $gFolderId;
    global $gRequestPath, $gRequestFile;

    if (!IsUserAdmin())
        WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_ADM_AUTHOR_ONLY);

    $link_id = RequestGetValue('fld_id', 0);
    if ($link_id > 0)
    {
        $colvalues = 'link_show=1, link_active=1';
        $where = "link_id=$link_id";
        DbSqlUpdate('link', $colvalues, $where);
    }

    $from = RequestGetValue('from');
    if (!empty($from))
    {
        $redirect = '/phpmod/todo.php?op=link';
    }
    else
    {
        $gRequestPath = FindPathFromFolderId($gFolderId);
        $gRequestFile = 'index.html';

        $redirect = $gRequestPath.$gRequestFile;
    }

    Header("Location: $redirect");
}

function LinkFormCheck(&$errmsg)
{
    global $gWebPage;
    
    $gWebPage['from']         = RequestGetValue('from',  '');
    $gWebPage['fld_id']       = RequestGetValue('fld_id',  0);
    $gWebPage['fld_url']      = RequestGetValue('fld_url', '');
    $gWebPage['fld_title']    = RequestGetValue('fld_title', '', CLEAN_ALL);
    $gWebPage['fld_desc']     = RequestGetValue('fld_desc', '', CLEAN_SAVE);
    $gWebPage['fld_note']     = strtoupper(RequestGetValue('fld_note', '', CLEAN_ALL));

    if (IsUserAdmin())
    {
        $gWebPage['fld_order'] = RequestGetValue('fld_order', DEFAULT_ORDER);
        $gWebPage['fld_show']  = RequestGetValue('fld_show', 0);
        $gWebPage['fld_active']  = RequestGetValue('fld_active', 0);
        $gWebPage['fld_great']  = RequestGetValue('fld_great', 0);
    }

    if (!empty($gWebPage['fld_url']))
    {
        if (!StrIsStartWith($gWebPage['fld_url'], "\/") && !IsUrlValid($gWebPage['fld_url']))
        {
            $errmsg = _ERR_INVALID_URL;
            return false;
        }
    }
	else
	{
		$errmsg = _ERR_INVALID_URL;
		return false;
	}
    
    if (empty($gWebPage['fld_title']))
    {
        $errmsg = _ERR_EMPTY_TITLE;
        return false;
    }

    return true;
}

function LinkInitVars()
{
    global $gWebPage;
    global $gPageId;
    global $gFolder, $gFolderId;
    

    $gWebPage['from']         = '';
    $gWebPage['fld_id']       = 0;
    $gWebPage['fld_url']      = '';
    $gWebPage['fld_title']    = '';
    $gWebPage['fld_desc']     = '';
    $gWebPage['fld_note']     = '';

    $gPageId = RequestGetValue('id', 0);
}

function LinkFormShow($op, $dbinit, $errmsg='')
{
    global $gFolder, $gFolderId;
    global $gRequestPath, $gCurrentUrlPath, $gRequestFile;
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;
    global $gBaseLocalPath;
    global $gHomePageUrl, $gPageNavigation;

    $from = RequestGetValue('from');
    if (!empty($from))
    {
        $gCurrentPageNavigation = '';
        $gPageNavigation = array();
        $gPageNavigation[] = array($gHomePageUrl.$gBaseUrlPath."/index.html", _NAV_FRONTPAGE);
        $gPageNavigation[] = array($gHomePageUrl."/phpmod/cpanel.php", _NAV_CONTROL_PANEL);
        $gPageNavigation[] = array($gHomePageUrl."/phpmod/todo.php", _NAV_TODO_LIST);
        $gPageNavigation[] = array($gHomePageUrl."/phpmod/todo.php?op=link", _NAV_TODO_LINK);

        $gWebPage['from']   = $from;
    }
    else
    {
        DBGetFolderData($gFolderId);

        $gRequestPath = FindPathFromFolderId($gFolderId);
        $gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
        $gRequestFile = 'index.html';

        $gWebPage['from']   = '';
    }

    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
    $gWebPage['page_title']     = _LINK_ADD_TITLE;
    $gWebPage['page_desc']      = $gFolder['desc'];
    $gWebPage['page_keywords']  = $gFolder['keywords'];
    $gWebPage['page_sidebar']   = RenderPageSidebar();

    $gWebPage['fld_op']   = $op;

    if (!IsUserLogin())
    {
        $gWebPage['page_content'] = sprintf("<h1>%s</h1>\n%s", _LINK_ADD_TITLE, _LINK_ADD_NOLOGIN);
        DoShowPage(TPL_WEB_PAGE);
    }
    else
    {
        $gWebPage['page_message']       = $errmsg;
        $gWebPage['link_form_title']    = _LINK_FORM_TITLE;

        if ($dbinit)
        {
            $link_id = RequestGetValue('fld_id', 0);
            
            $columns = 'link_url, link_title, link_desc, link_note, link_order, link_show, link_active, link_great';
            $where = "link_id = $link_id";
            $rs = DbSqlSelect('link', $columns, $where);
            if ($rs && !$rs->EOF)
            {
                $gWebPage['fld_id']         = $link_id;
                $gWebPage['fld_url']        = $rs->fields[0];
                $gWebPage['fld_title']      = $rs->fields[1];
                $gWebPage['fld_desc']       = $rs->fields[2];
                $gWebPage['fld_note']       = $rs->fields[3];
                $gWebPage['fld_order']      = $rs->fields[4];
                $gWebPage['fld_show']       = $rs->fields[5];
                $gWebPage['fld_active']     = $rs->fields[6];
                $gWebPage['fld_great']      = $rs->fields[7];

                // url already exist. change op and title
                $gWebPage['fld_op'] = 'upd';
                $gWebPage['link_form_title']    = _LINK_UPDATE_TITLE;
            }
        }

        if (!IsUserAdmin())
        {
            $fld_attr_row = '';
            $fld_order_row = '';
        }
        else
        {
            $fld_order_row = "
<tr>
<td valign=\"top\" align=\"right\">"._FLD_ORDER."</td>
<td valign=\"top\" align=\"left\">:</td>
<td valign=\"top\" align=\"left\"><input class=\"inputbox\" type=\"text\" name=\"fld_order\" size=\"12\" value=\"".$gWebPage['fld_order']."\"> "._FLD_ORDER_NOTE."</td>
</tr>";

            $chk_link_show   = CheckBox('fld_show', '1', $gWebPage['fld_show']).' '._FLD_SHOW;
            $chk_link_active = CheckBox('fld_active', '1', $gWebPage['fld_active']).' '._FLD_ACTIVE;
            $chk_link_great = CheckBox('fld_great', '1', $gWebPage['fld_great']).' '._FLD_GREAT;

            $fld_attr_row   = "
<tr>
<td valign=\"top\" align=\"right\">"._FLD_MISC_ATTR."</td>
<td valign=\"top\" align=\"left\">:</td>
<td valign=\"top\" align=\"left\">$chk_link_show $chk_link_active $chk_link_great</td>
</tr>";
        }

        $gWebPage['fld_attr_row']  = $fld_attr_row;
        $gWebPage['fld_order_row'] = $fld_order_row;
        DoShowPageWithContent(TPL_WEB_PAGE, 'link.htm');
    }
}

?>
