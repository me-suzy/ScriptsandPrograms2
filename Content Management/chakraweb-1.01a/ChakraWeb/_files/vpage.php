<?php 
// ----------------------------------------------------------------------
// ModName: Virtual Page
// Purpose: Get the proper from mod_rewrite
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------


require_once("library/_config.php");


//shortcut
if ($gAction == 'show')
{
    WebPageShow();
    die();
}

SetDynamicContent();

switch($gAction)
{
case 'edit':
    WebPageEdit();
    break;

case 'create':
    WebPageAutoCreate();
    break;

case 'save':
    WebPageSave();
    break;

case 'delete':
    WebPageDelete();
    break;

case 'dodelete':
    DoWebPageDelete();
    break;

case 'move':
    WebPageMove();
    break;

case 'domove':
    DoWebPageMove();
    break;

case 'hits':
    WebPageShowHits();
    break;

default:
    WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_UNKNOWN_OPERATION);
    break;
}

function WebPagePrepare()
{
    global $gFolderId;
    global $gRequestPath;


	if ($gFolderId == 0)
	{
        $gFolderId  = FindFolderIdFromPath($gRequestPath);
        if ($gFolderId < 0)
        {
            WebPageNotFound();
            die();
        }
	}
	else
	{
		$gRequestPath = FindPathFromFolderId($gFolderId);
	}

    DBGetFolderData($gFolderId);
}

function WebPageShow()
{
    global $db;
    global $gWebPage;
	global $gFolderId, $gFolder;
    global $gPageId;
    global $gRequestPath, $gRequestFile;
    global $gHomePageHeader, $gHomePageFooter;
    
    WebPagePrepare();

    if (!IsUserCanRead())
    {
        WebPageError(_UNAUTHORISIZED_ACCESS_TITLE, _UNAUTHORISIZED_ACCESS_MESSAGE);
    }
    
    $gPageId = 0;
    $lid = UserGetLID();

    $sql = "select page_id,page_title,page_desc,page_keywords,page_robots,page_author,
            page_content,page_seealso_title,page_seealso,page_external,page_src_title,page_src_url,
            page_src_home,page_src_homeurl,page_redirect, page_rating, page_votes, page_hits, page_active from web_page
            where folder_id=$gFolderId and page_lid=".$db->qstr($lid)." and page_name=".$db->qstr($gRequestFile);
    
    $rs = DbExecute($sql);
    if ($rs === false || $rs->EOF)
    {
        WebPageNotFound();
        die();
    }
    
    $page_active = $rs->fields[18]; 
    if (!$page_active && !IsUserAdmin())
        WebPageError(_UNAUTHORISIZED_ACCESS_TITLE, _UNAUTHORISIZED_ACCESS_MESSAGE);

    $gPageId                    = $rs->fields[0];
    $gWebPage['page_id']        = $gPageId;

    $gWebPage['page_title']     = $rs->fields[1];
    $gWebPage['page_desc']      = $rs->fields[2];
    $gWebPage['page_keywords']  = $rs->fields[3];
    $gWebPage['page_robots']    = $rs->fields[4];

    //check if this page is a redirect page
    $page_redirect = $rs->fields[14];
    if (!empty($page_redirect))
    {
        SetDynamicContent();
        DoShowPageRedirect($page_redirect, $gWebPage['page_title'], $gWebPage['page_desc'], $gWebPage['page_keywords']);
        die();
    }

    $gWebPage['page_author_db'] = $rs->fields[5];
    $gWebPage['page_author']    = RenderPageAuthor($rs->fields[5]);

    $gWebPage['see_also_title'] = $rs->fields[7];
    $gWebPage['see_also_text']  = WebContentParse($rs->fields[8]);

    $src_external     = $rs->fields[9];
    if ($src_external > 0)
    {
        $src_title        = $rs->fields[10];
        $src_url          = $rs->fields[11];
        $src_home         = $rs->fields[12];
        $src_homeurl      = $rs->fields[13];

        $gWebPage['page_source'] = RenderPageSource($src_title, $src_url, $src_home, $src_homeurl);
    }
    else
    {
        $gWebPage['page_source'] = "";
    }

    $gWebPage['page_rating']    = sprintf("%3.2f", $rs->fields[15]); 
    $gWebPage['page_votes']     = $rs->fields[16]; 
    $gWebPage['page_hits']      = $rs->fields[17]; 

    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
    $gWebPage['page_content']   = WebContentParse($rs->fields[6]);

    global $gPageNavigation;

    if ($gRequestFile != 'index.html')
        $gPageNavigation[] = array($gRequestPath.$gRequestFile, $gWebPage['page_title']);

    DoShowPage(TPL_WEB_PAGE);
}

function WebPageEdit()
{
    global $gWebPage;
	global $gFolder, $gFolderId;
    global $gPageId;
    global $gRequestPath, $gRequestFile;
    global $gHomePageHeader, $gHomePageFooter;

   
    $lid    = UserGetLID();

    $init_wp = false;
    $gFolderId  = FindFolderIdFromPath($gRequestPath);

    DBGetFolderData($gFolderId);

    if (!IsUserCanWrite())
        WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_OPR_DENIED_MESSAGE);
	    //RedirectToPreviousPage();

    if ($gFolderId >= 0)
    {
        $init_wp = DBGetWebPageForEdit($gFolderId, $gRequestFile, $lid);
        if ($init_wp)
        {
            //the page already exist. check the autorization
            if (!IsUserAdmin() && UserGetName() != $gWebPage['upload_by'])
                WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_ADM_AUTHOR_ONLY);
        }
    }

    if (!$init_wp)
    {
        $gWebPage['page_id']            = "0";
        $gWebPage['fld_name']          = $gRequestFile;
        $gWebPage['fld_title']         = "TheTitle";
        $gWebPage['fld_desc']          = "";
        $gWebPage['fld_keywords']      = $gFolder['keywords'];
        $gWebPage['fld_robots']        = "index, follow";
        $gWebPage['fld_redirect']      = "";
        $gWebPage['fld_author']        = UserGetName();
        $gWebPage['fld_content']       = DEFAULT_PAGE_CONTENT;
        $gWebPage['fld_seealso_title'] = _FLD_SEEALSO_TITLE;
        $gWebPage['fld_seealso']       = "";
        $gWebPage['fld_src_title']     = "";
        $gWebPage['fld_src_url']       = "";
        $gWebPage['fld_src_home']      = "";
        $gWebPage['fld_src_homeurl']   = "";
        $gWebPage['fld_order']         = DEFAULT_ORDER;
        $gWebPage['fld_show']          = 1;
        $gWebPage['fld_active']        = 1;
    }

    $gPageId = $gWebPage['page_id'];

    $gWebPage['wpage_edit']     = _WPAGE_EDIT_CONTENT;
    $gWebPage['fld_lang_name'] = UserGetLangName();

    $gWebPage['page_header'] = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer'] = WebContentParse($gHomePageFooter);
    $gWebPage['form_action'] = $gRequestPath.$gRequestFile;

    $gWebPage['page_title']     = $gWebPage['fld_title'];
    $gWebPage['page_desc']      = $gWebPage['fld_desc'];
    $gWebPage['page_keywords']  = $gWebPage['fld_keywords'];

    $gWebPage['chk_show']    = CheckBox('fld_show', '1', $gWebPage['fld_show']);
    $gWebPage['chk_active']  = CheckBox('fld_active', '1', $gWebPage['fld_active']);

    $gWebPage['fld_content_editor'] = RenderHtmlEditor('fld_content', $gWebPage['fld_content'], 'chstd', 600, 400);
    $gWebPage['fld_seealso_editor'] = RenderHtmlEditor('fld_seealso', $gWebPage['fld_seealso'], 'chmini', 600, 90);

    $gWebPage['fld_help_box'] = RenderHelpBox(_URL_HELP_EDIT_PAGE);

    DoShowPageWithContent(TPL_SIMPLE_PAGE, 'wpage_edit.htm');
}


function WebPageSave()
{
    global $db;
    global $gFolderId, $gPageId;
    global $gWebPage;
    global $gRequestPath, $gRequestFile;
    global $gHomePageHeader, $gHomePageFooter;

    DBGetFolderData($gFolderId);

    if (!IsUserCanWrite())
	    RedirectToPreviousPage();

    //PrintLine($gFolderId, 'FolderId');
    //PrintLine($gRequestPath, 'RequestPath');
    //PrintLine($gCurrentUrlPath, 'CurrentUrlPath');
    //die();

    if (WebPageEditCheck($errmsg))
    {
        if ($gWebPage['page_id'] > 0)
        {
            $bresult = DoWebPageUpdate();
        }
        else
        {
            $gFolderId = FindOrCreateFolderFromPath($gRequestPath);
            if ($gFolderId == -1)
            {
                $bresult = false;
                $errmsg = "Unable to create folder $gRequestPath";
            }
            else
            {
                if ($gWebPage['fld_name'] == 'index.html')
                {
                    //the file already created on FindOrCreateFolderFromPath
                    //we will update it

                    $gWebPage['page_id'] = DbGetPageIdFromName('index.html', $gFolderId);

                    if ($gWebPage['page_id'] > 0)
                        $bresult = DoWebPageUpdate();
                    else
                        $bresult = DoWebPageAddNew();
                }
                else
                {
                    $bresult = DoWebPageAddNew();
                }
            }
        }
        
        if ($bresult)
        {
			if (empty($gWebPage['fld_redirect']))
            	$redirect = $gRequestPath.$gWebPage['fld_name'];
			else
				$redirect = $gRequestPath;

            Header("Location: $redirect");
            die();
        }
        
        $errmsg = 'Unable to save to database. '.$db->ErrorMsg();
    }

    $gWebPage['wpage_edit']     = _WPAGE_EDIT_CONTENT.'<p>'.$errmsg.' Please reenter the form.';
    $gWebPage['fld_lang_name'] = UserGetLangName();
    $gWebPage['form_action'] = $gRequestPath.$gRequestFile;
    $gWebPage['page_header'] = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer'] = WebContentParse($gHomePageFooter);

    $gWebPage['page_title']     = $gWebPage['fld_title'];
    $gWebPage['page_desc']      = $gWebPage['fld_desc'];
    $gWebPage['page_keywords']  = $gWebPage['fld_keywords'];

    $gWebPage['fld_content_editor'] = RenderHtmlEditor('fld_content', $gWebPage['fld_content'], 'chstd', 600, 400);
    $gWebPage['fld_seealso_editor'] = RenderHtmlEditor('fld_seealso', $gWebPage['fld_seealso'], 'chmini', 600, 90);

    $gWebPage['url_help']  = _URL_HELP_EDIT_PAGE;

    DoShowPageWithContent(TPL_SIMPLE_PAGE, 'wpage_edit.htm');
}

function DoWebPageUpdate()
{
    global $db;
    global $gWebPage;
    global $gFolderId;

    $colvalues  = 'page_name='.$db->qstr($gWebPage['fld_name']);
    $colvalues .= ', page_title='.$db->qstr($gWebPage['fld_title']);
    $colvalues .= ', page_desc='.$db->qstr($gWebPage['fld_desc']);
    $colvalues .= ', page_keywords='.$db->qstr($gWebPage['fld_keywords']);
    $colvalues .= ', page_robots='.$db->qstr($gWebPage['fld_robots']);
    $colvalues .= ', page_redirect='.$db->qstr($gWebPage['fld_redirect']);
    $colvalues .= ', page_author='.$db->qstr($gWebPage['fld_author']);
    $colvalues .= ', page_content='.$db->qstr($gWebPage['fld_content']);
    $colvalues .= ', page_seealso_title='.$db->qstr($gWebPage['fld_seealso_title']);
    $colvalues .= ', page_seealso='.$db->qstr($gWebPage['fld_seealso']);
    $colvalues .= ', page_src_title='.$db->qstr($gWebPage['fld_src_title']);
    $colvalues .= ', page_src_url='.$db->qstr($gWebPage['fld_src_url']);
    $colvalues .= ', page_src_home='.$db->qstr($gWebPage['fld_src_home']);
    $colvalues .= ', page_src_homeurl='.$db->qstr($gWebPage['fld_src_homeurl']);
    $colvalues .= ', page_order='.$gWebPage['fld_order'];
    $colvalues .= ', page_show='.$gWebPage['fld_show'];
    $colvalues .= ', page_active='.$gWebPage['fld_active'];

    if (!empty($gWebPage['fld_src_url']))
        $colvalues .= ', page_external=1';
    else
        $colvalues .= ', page_external=0';

    $utime = date("YmdHis", time());
    $colvalues .= ', upload_by='.$db->qstr(UserGetName());
    $colvalues .= ', update_on='.$utime;

    $lid = UserGetLID();
    $where = 'page_id='.$gWebPage['page_id'].' and page_lid='.$db->qstr($lid);
    $bresult = DbSqlUpdate('web_page', $colvalues, $where);

    if ($gWebPage['fld_name'] == 'index.html')
    {
        FolderUpdateAttr2($gFolderId, $lid, $gWebPage['fld_desc'], $gWebPage['fld_keywords'], $gWebPage['fld_robots']);
    }

    return $bresult;
}

function DoWebPageAddNew()
{
    global $db;
    global $gWebPage;
    global $gFolderId, $gPageId;

    if (empty($gWebPage['fld_src_url']))
        $page_external = 0;
    else
        $page_external = 1;
    
    $gPageId = DbGetUniqueID('web_page');
    $columns = 'folder_id, page_id, page_lid, page_name, page_title, page_desc, page_keywords, 
                page_robots, page_redirect, page_author, page_content, page_seealso_title,
                page_seealso, page_external, page_src_title, page_src_url, page_src_home, page_src_homeurl,
                page_order, page_show, page_active, upload_by, upload_on, update_on';

    $lid = UserGetLID();

    $values = "$gFolderId, $gPageId, ".$db->qstr($lid).',';
    $values .= $db->qstr($gWebPage['fld_name']).',';
    $values .= $db->qstr($gWebPage['fld_title']).',';
    $values .= $db->qstr($gWebPage['fld_desc']).',';
    $values .= $db->qstr($gWebPage['fld_keywords']).',';

    $values .= $db->qstr($gWebPage['fld_robots']).',';
    $values .= $db->qstr($gWebPage['fld_redirect']).',';
    $values .= $db->qstr($gWebPage['fld_author']).',';
    $values .= $db->qstr($gWebPage['fld_content']).',';
    $values .= $db->qstr($gWebPage['fld_seealso_title']).',';
    
    $values .= $db->qstr($gWebPage['fld_seealso']).',';
    $values .= "$page_external, ";

    $values .= $db->qstr($gWebPage['fld_src_title']).',';
    $values .= $db->qstr($gWebPage['fld_src_url']).',';
    $values .= $db->qstr($gWebPage['fld_src_home']).',';
    $values .= $db->qstr($gWebPage['fld_src_homeurl']).',';

    $values .= $gWebPage['fld_order'].',';
    $values .= $gWebPage['fld_show'].',';
    $values .= $gWebPage['fld_active'].',';

    $utime = date("YmdHis", time());
    $values .= $db->qstr(UserGetName()).',';
    $values .= $utime.','.$utime;

    $bresult = DbSqlInsert('web_page', $columns, $values);

    if ($gWebPage['fld_name'] == 'index.html')
    {
        FolderUpdateAttr2($gFolderId, $lid, $gWebPage['fld_desc'], $gWebPage['fld_keywords'], $gWebPage['fld_robots']);
    }

    return $bresult;
}

function WebPageEditCheck(&$errmsg)
{
    global $gWebPage;

    $gWebPage['folder_id']         = RequestGetValue('folder_id', 0);
    $gWebPage['page_id']           = RequestGetValue('page_id', 0);
    $gWebPage['fld_name']          = RequestGetValue('fld_name', '', CLEAN_ALL);
    $gWebPage['fld_title']         = RequestGetValue('fld_title', '', CLEAN_ALL);
    $gWebPage['fld_desc']          = RequestGetValue('fld_desc', '', CLEAN_ALL);
    $gWebPage['fld_keywords']      = RequestGetValue('fld_keywords', '', CLEAN_ALL);
    $gWebPage['fld_robots']        = RequestGetValue('fld_robots', '', CLEAN_ALL);
    $gWebPage['fld_redirect']      = RequestGetValue('fld_redirect', '', CLEAN_ALL);
    $gWebPage['fld_author']        = RequestGetValue('fld_author', '', CLEAN_ALL);
    $gWebPage['fld_content']       = RequestGetValue('fld_content', '', CLEAN_SAVE);
    $gWebPage['fld_seealso_title'] = RequestGetValue('fld_seealso_title', '', CLEAN_ALL);
    $gWebPage['fld_seealso']       = RequestGetValue('fld_seealso', '', CLEAN_SAVE);
    $gWebPage['fld_src_title']     = RequestGetValue('fld_src_title', '', CLEAN_ALL);
    $gWebPage['fld_src_url']       = RequestGetValue('fld_src_url', '', CLEAN_ALL);
    $gWebPage['fld_src_home']      = RequestGetValue('fld_src_home', '', CLEAN_ALL);
    $gWebPage['fld_src_homeurl']   = RequestGetValue('fld_src_homeurl', '', CLEAN_ALL);
    $gWebPage['fld_order']         = RequestGetValue('fld_order', 0);
    $gWebPage['fld_show']          = RequestGetValue('fld_show', 0);
    $gWebPage['fld_active']        = RequestGetValue('fld_active', 0);


    if (empty($gWebPage['fld_name']) || !StrIsEndWith($gWebPage['fld_name'], '.html'))
    {
        $errmsg = _ERR_INVALID_FILE_NAME;
        return false;
    }

    //make sure the valid name
    $gWebPage['fld_name'] = strtolower($gWebPage['fld_name']);
    $gWebPage['fld_name'] = str_replace(' ', '_', $gWebPage['fld_name']);

    if (empty($gWebPage['fld_title']))
        $gWebPage['fld_title'] = $gWebPage['fld_name'];

    if (!empty($gWebPage['fld_redirect']))
    {
        if (!StrIsStartWith($gWebPage['fld_redirect'], "\/") && !IsHttpUrlValid($gWebPage['fld_redirect']))
        {
            $errmsg = _ERR_INVALID_REDIRECT_URL;
            return false;
        }
    }

    if (!empty($gWebPage['fld_src_url']) && !IsHttpUrlValid($gWebPage['fld_src_url']))
    {
        $errmsg = _ERR_INVALID_SOURCE_URL;
        return false;
    }

    if (!empty($gWebPage['fld_src_homeurl']) && !IsHttpUrlValid($gWebPage['fld_src_homeurl']))
    {
        $errmsg = _ERR_INVALID_SOURCE_URL;
        return false;
    }

    if (empty($gWebPage['fld_robots']))
        $gWebPage['fld_robots'] = DEFAULT_ROBOTS;

    $gWebPage['fld_keywords'] = FixKeywords($gWebPage['fld_keywords']);

    if (empty($gWebPage['fld_content']))
        $gWebPage['fld_content'] = DEFAULT_PAGE_CONTENT;

    return true;
}

function DBGetWebPageForEdit($folder_id, $file_name, $lid)
{
    global $db;
    global $gWebPage;

    $sql = "select page_id,page_name,page_title,page_desc,page_keywords,page_robots,page_redirect,page_author,
            page_content,page_seealso_title,page_seealso,page_src_title,page_src_url,
            page_src_home,page_src_homeurl, page_order, page_show, page_active, upload_by from web_page
            where folder_id=$folder_id and page_lid=".$db->qstr($lid)." and page_name=".$db->qstr($file_name);
    
    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $gWebPage['page_id']            = $rs->fields[0];
        $gWebPage['fld_name']          = $rs->fields[1];
        $gWebPage['fld_title']         = $rs->fields[2];
        $gWebPage['fld_desc']          = $rs->fields[3];
        $gWebPage['fld_keywords']      = $rs->fields[4];
        $gWebPage['fld_robots']        = $rs->fields[5];
        $gWebPage['fld_redirect']      = $rs->fields[6];
        $gWebPage['fld_author']        = $rs->fields[7];
        $gWebPage['fld_content']       = $rs->fields[8];
        $gWebPage['fld_seealso_title'] = $rs->fields[9];
        $gWebPage['fld_seealso']       = $rs->fields[10];
        $gWebPage['fld_src_title']     = $rs->fields[11];
        $gWebPage['fld_src_url']       = $rs->fields[12];
        $gWebPage['fld_src_home']      = $rs->fields[13];
        $gWebPage['fld_src_homeurl']   = $rs->fields[14];
        $gWebPage['fld_order']         = $rs->fields[15];
        $gWebPage['fld_show']          = $rs->fields[16];
        $gWebPage['fld_active']        = $rs->fields[17];
        $gWebPage['upload_by']         = $rs->fields[18];

        //PrintLine('OK'); die();
        return true;
    }

    return false;
}


function WebPageDelete()
{
    global $db;
    global $gWebPage;
	global $gFolderId;
    global $gPageId;
    global $gRequestPath, $gRequestFile;
    global $gHomePageHeader, $gHomePageFooter;

    WebPagePrepare();

    if (!IsUserCanWrite())
	    RedirectToPreviousPage();

    $lid = UserGetLID();

    $sql = "select page_id,page_title,page_desc,page_keywords,page_robots from web_page
            where folder_id=$gFolderId and page_lid=".$db->qstr($lid)." and page_name=".$db->qstr($gRequestFile);
    
    $rs = DbExecute($sql);
    if ($rs === false || $rs->EOF)
    {
        WebPageNotFound();
        die();
    }

    $gPageId                    = $rs->fields[0];
    $gWebPage['page_id']        = $gPageId;
    $gWebPage['page_name']      = $gRequestFile;

    $gWebPage['page_title']     = sprintf(_WPAGE_DELETE_TITLE, $rs->fields[1]);

    $gWebPage['page_desc']      = $rs->fields[2];
    $gWebPage['page_keywords']  = $rs->fields[3];
    $gWebPage['page_robots']    = $rs->fields[4];

    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);

    $gWebPage['url_delete'] = $gRequestFile.'?op=dodelete&cat='.$gFolderId.'&id='.$gPageId;

    $gWebPage['fld_name']      = $gRequestFile;
    $gWebPage['fld_title']     = $rs->fields[1];
    $gWebPage['fld_desc']      = $rs->fields[2];


    DoShowPageWithContent(TPL_WEB_PAGE, 'wpage_delete.htm');
}

function DoWebPageDelete()
{
    global $db;
    global $gRequestPath;

    $folder_id  = RequestGetValue('cat', 0);
    DBGetFolderData($folder_id);

    if (!IsUserAdmin())
        WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_OPR_DELETE);
	    //RedirectToPreviousPage();

    $page_id    = RequestGetValue('id', 0);
    $lid        = UserGetLID();

    $where = "folder_id=$folder_id and page_id=$page_id and page_lid=".$db->qstr($lid);
    DbSqlDelete('web_page', $where);

    Header("Location: $gRequestPath"."index.html");
}

function WebPageMove()
{
    global $db;
    global $gWebPage;
	global $gFolderId;
    global $gPageId;
    global $gRequestPath, $gRequestFile;
    global $gHomePageHeader, $gHomePageFooter;

    WebPagePrepare();

    if (!IsUserAdmin())
        WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_OPR_DELETE);

    $lid = UserGetLID();

    $sql = "select page_id,page_title,page_desc,page_keywords,page_robots from web_page
            where folder_id=$gFolderId and page_lid=".$db->qstr($lid)." and page_name=".$db->qstr($gRequestFile);
    
    $rs = DbExecute($sql);
    if ($rs === false || $rs->EOF)
    {
        WebPageNotFound();
        die();
    }

    $gPageId                    = $rs->fields[0];
    $gWebPage['page_id']        = $gPageId;
    $gWebPage['page_name']      = $gRequestFile;

    $gWebPage['page_title']      = sprintf(_WPAGE_MOVE_TITLE, $rs->fields[1]);
    $gWebPage['page_title_move'] = sprintf(_WPAGE_MOVE_TITLE, $rs->fields[1]);

    $gWebPage['page_desc']      = $rs->fields[2];
    $gWebPage['page_keywords']  = $rs->fields[3];
    $gWebPage['page_robots']    = $rs->fields[4];

    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);

    $gWebPage['form_action']   = $gRequestFile;
    $gWebPage['fld_name']      = $gRequestFile;
    $gWebPage['fld_title']     = $rs->fields[1];
    $gWebPage['fld_desc']      = $rs->fields[2];

    $gWebPage['fld_parent_path'] = $gRequestPath;

    DoShowPageWithContent(TPL_WEB_PAGE, 'wpage_move.htm');
}

function DoWebPageMove()
{
    global $gFolderId, $gPageId;
    global $db;
    global $gRequestPath, $gRequestFile;

    if (!IsUserAdmin())
        WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_OPR_MOVE);

    CheckRequestRandom();

    $gPageId = RequestGetValue('id', 0);

    $parent_path = RequestGetValue('fld_parent_path', '');
    if (!StrIsStartWith($parent_path, "\/"))
        $parent_path = "/".$parent_path;

    if (!StrIsEndWith($parent_path, "\/"))
        $parent_path .= "/";

    $folder_parent = FindFolderIdFromPath($parent_path);
    
    if ($folder_parent >= 0)
    {
        $lid = UserGetLID();
        $colvalues = "folder_id=$folder_parent";
        $where = "page_id=$gPageId and page_lid=".$db->qstr($lid);
        DbSqlUpdate('web_page', $colvalues, $where);
    
        $redirect = $parent_path.$gRequestFile;
    }
    else
    {
        $redirect = $gRequestPath.$gRequestFile;
    }

    Header("Location: $redirect");
}

function WebPageAutoCreate()
{
    global $gFolderId;
    global $gRequestPath, $gRequestFile;

    $redirect = '';

    DBGetFolderData($gFolderId);

    if (!IsUserCanWrite())
	    RedirectToPreviousPage();

    $folder_id = FindOrCreateFolderFromPath($gRequestPath);
    if ($folder_id > 0)
    {
        if (strcasecmp($gRequestFile, 'index.html') != 0)
        {
            $page_id = DbGetPageIdFromName($gRequestFile, $folder_id);
            if ($page_id <= 0)
                $page_id = CreateEmptyPage($folder_id, $gRequestFile, $gRequestFile);
        }
        else
            $page_id = 1;

        if ($page_id > 0)
            $redirect = $gRequestPath.$gRequestFile;
    }

    if (empty($redirect))
        RedirectToPreviousPage();
    else
        Header("Location: $redirect");
}


function WebPageShowHits()
{
    global $gWebPage;
	global $gFolder, $gFolderId;
    global $gHomePageHeader, $gHomePageFooter;

    WebPagePrepare();

    if (!IsUserAdmin())
        WebPageError(_UNAUTHORISIZED_ACCESS_TITLE, _UNAUTHORISIZED_ACCESS_MESSAGE);


    $title = sprintf(_PAGE_HITS_TITLE_FMT, $gFolder['title']);
    $content = RenderWebPageHitsTable($gFolderId);

    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
    $gWebPage['page_title']     = $title;
    $gWebPage['page_content']   = sprintf("<h1>%s</h1>\n", $title).$content;

    DoShowPage(TPL_WEB_PAGE);
}

function RenderWebPageHitsTable($folder_id)
{
    global $db;

    $lid = UserGetLID();
    $sql = "select folder_id, page_id, page_name, page_title, page_desc, page_hits from web_page where folder_id=$folder_id 
            and page_lid=".$db->qstr($lid)." order by page_hits desc";

    $rs = DbExecute($sql);
    return RenderWebPageHitsTableFromRS($rs);
}



?>
