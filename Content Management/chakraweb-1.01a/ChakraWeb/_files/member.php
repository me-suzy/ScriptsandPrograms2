<?php 
// ----------------------------------------------------------------------
// ModName: Member Virtual Page
// Purpose: Get the proper path from mod_rewrite to show member list and profile
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("library/_config.php");

$gRequestPath = strtolower($gRequestPath);
if ($gRequestPath != '/members/')
{
    Header("Location: /index.html");
    die();
}

$gRequestPath = '/';
$uname = substr($gRequestFile, 0, strlen($gRequestFile)-5);

$gFolderId = 0;
$gPageId = 0;
$gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;

DBGetFolderData(0);

$gPageNavigation = array();
$gPageNavigation[] = array($gHomePageUrl.$gBaseUrlPath."/index.html", _NAV_FRONTPAGE);
$gPageNavigation[] = array($gHomePageUrl."/members/index.html", _MEMBER_PAGE);
if ($uname != 'index')
    $gPageNavigation[] = array($gHomePageUrl."/members/".$gRequestFile, ucwords($uname));

//$gWebPage['page_sidebar']   = RenderPageSidebar();
//$gWebPage['page_header']    = WebContentParse($gHomePageHeader);
//$gWebPage['page_footer']    = WebContentParse($gHomePageFooter);

if ($gRequestFile == 'index.html')
{
    MemberShowList();
}
else
{
    $op = RequestGetValue('op', 'show');
    switch ($op)
    {
    case 'edit':
        MemberPageEdit(true);
        break;
    case 'save':
        MemberPageSave();
        break;
    case 'show':
    default:
        MemberPageShow($uname);
        break;
    }
}


function MemberShowList()
{
    global $db;
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;

    $bIsAdmin = IsUserAdmin();

    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
    $gWebPage['page_title']     = _MEMBER_PAGE_TITLE;
    $gWebPage['page_desc']      = '';
    $gWebPage['page_keywords']  = '';

    $p = RequestGetValue('p', 'A');

    //$sql = 'select count(m_id) from sysmember where m_view_profile=1';
    $sql = 'select count(m_id) from sysmember';
    $member_count = DbGetOneValue($sql);

    $title    = '<h1>'._MEMBER_PAGE_TITLE.'</h1>';
    $message  = sprintf(_MEMBER_PAGE_MESSAGE_FMT, $member_count);

    $nav = '<table border="0" width="90%" cellspacing="0" cellpadding="4" bgcolor="#EAEAEA"><tr>'."\n";
    $nav .= '<td><font face="Verdana" size=2><b>'.HRef('/members/index.html?p=ALL', 'ALL').'</b></font></td>'."\n";

    for ($x = 65; $x<91; $x++)
    {
        $ch = chr($x);
        $url = '/members/index.html?p='.$ch;
        if ($p == $ch)
            $nav .= '<td><font face="Verdana" size=2><b>'.$ch.'</b></font></td>'."\n";
        else
            $nav .= '<td><font face="Verdana" size=2><b>'.HRef($url, ''.$ch.'').'</b></font></td>'."\n";
    }
    $nav .= '</tr></table>'."\n";

    $content = '<br>';

    $sql = 'select m_id, m_name, m_fullname, m_email, m_homepage, m_desc, m_view_email, m_ccode from sysmember';

    if ($p == 'ALL')
    {
        if (!$bIsAdmin)
            $sql .= ' where m_view_profile=1';
    }
    else
    {
        $sql .= ' where m_fullname like '.$db->qstr($p.'%');

        if (!$bIsAdmin)
            $sql .= ' and m_view_profile=1';
    }

    $sql .= " order by m_fullname";    

    $rs = DbExecute($sql);
    if ($rs && !$rs->EOF)
    {
        $content .= RenderMemberListFromRS($rs);
    }
    //$content .= '<br>';


    $gWebPage['page_content'] = $title.$message.$nav.$content.$nav;

    DoShowPage(TPL_WEB_PAGE);
}


function MemberPageShow($uname)
{
    global $db;
    global $gWebPage;
    global $gBaseLocalPath;
    global $gHomePageHeader, $gHomePageFooter;

    $fname = $gBaseLocalPath.'_lang/'.UserGetLID().'/member_page.htm';

    $content = ReadLocalFile($fname, $errmsg, true);
    if (empty($content))
        $content = $errmsg;

    $gWebPage['page_content']  = $content;


    $sql = 'select m_name, m_fullname, m_email, m_homepage, m_view_email, m_photo, m_desc, m_page, m_view_profile from sysmember
            where m_name = '.$db->qstr($uname);

    $rs = DbExecute($sql);
    if ($rs === false) DbFatalError("DBGetMemberData");
    if (!$rs->EOF)
    {
        if (!IsUserAdmin() && !$rs->fields[8] && $uname != UserGetName())
        {
            SetDynamicContent();
            WebPageError(_UNAUTHORISIZED_ACCESS_TITLE, _UNAUTHORISIZED_ACCESS_MESSAGE);
        }

        $gWebPage['user_name'] = $rs->fields[0];
        $gWebPage['user_fullname'] = $rs->fields[1];

        $view_email = $rs->fields[4];
        if ($view_email)
            $gWebPage['user_email'] = HRef('mailto:'.$rs->fields[2], $rs->fields[2]);
        else
            $gWebPage['user_email'] = '';

        $hpage = $rs->fields[3];
        if (empty($hpage))
            $gWebPage['user_hpage'] = '';
        else
            $gWebPage['user_hpage'] = HRef($hpage, $hpage);

        $gWebPage['user_desc'] = $rs->fields[6];

        $gWebPage['user_page'] = $rs->fields[7];
        if (empty($gWebPage['user_page']))
            $gWebPage['user_page'] = sprintf(_MEMBER_PAGE_EMPTY_FMT, $gWebPage['user_fullname'], "/members/$uname.html?op=edit");
        else
        {
            $gWebPage['user_page'] = WebContentParse($gWebPage['user_page']);

            if ($uname == UserGetName())
            {
                $txt = sprintf(_MEMBER_PAGE_FOOTNOTE_FMT, $gWebPage['user_fullname'], "/members/$uname.html?op=edit");
                $footnote = "<div id=footnote>$txt</div>";
                $gWebPage['user_page'] .= $footnote;
            }
        }

        $photo = $rs->fields[5];
        if ($photo)
        {
            $photo_cnt  = "<table border=0 cellspacing=0 cellpadding=0 align=right><tr><td>";
            if (!empty($hpage))
                $photo_cnt .= "<a href=\"".$hpage."\">";
            $photo_cnt .= "<img src=\"/members/images/".$uname.".jpg\" border=0 width=80 height=120>";
            if (!empty($hpage))
                $photo_cnt .= "</a>";
            $photo_cnt .= "</td></tr></table>";
    
            $gWebPage['user_photo'] = $photo_cnt;
        }
        else
        {
            $gWebPage['user_photo'] = '';
        }
    }
    else
    {
        $gWebPage['user_name'] = '';
        $gWebPage['user_fullname'] = '';
        $gWebPage['user_email'] = '';
        $gWebPage['user_hpage'] = '';
        $gWebPage['user_desc'] = '';
        $gWebPage['user_page'] = '';
        $gWebPage['user_photo'] = '';
    }

    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
    $gWebPage['page_title']     = sprintf(_MEMBER_PAGE_FMT, $uname);
    $gWebPage['page_desc']      = '';
    $gWebPage['page_keywords']  = '';

    DoShowPage(TPL_WEB_PAGE);
}

function MemberPageEdit($bInit)
{
    global $db;
    global $gWebPage;
    global $gHomePageHeader, $gHomePageFooter;
    global $uname;

    SetDynamicContent();

    $sname = UserGetName();
    if (!IsUserAdmin() && $sname != $uname)
    {
        WebPageError(_UNAUTHORISIZED_ACCESS_TITLE, _ERR_CHANGE_OTHER_MEMBER_INFO);
        //Header("Location: /members/$uname.html");
        die();
    }

    if ($bInit)
    {
        $gWebPage['fld_fullname'] = '';
        $gWebPage['fld_desc'] = '';
        $gWebPage['fld_content'] = '';

        $sql = 'select m_id, m_fullname, m_desc, m_page from sysmember where m_name = '.$db->qstr($uname);

        $rs = DbExecute($sql);
        if ($rs && !$rs->EOF)
        {
            $gWebPage['uid']            = $rs->fields[0];
            $gWebPage['fld_fullname']   = $rs->fields[1];
            $gWebPage['fld_desc']       = $rs->fields[2];
            $gWebPage['fld_content']    = $rs->fields[3];
        }
    }

    $gWebPage['page_header'] = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer'] = WebContentParse($gHomePageFooter);
    $gWebPage['form_action'] = "/members/$uname.html";
    $gWebPage['page_title']  = sprintf(_MEMBER_PAGE_EDIT_FMT, $gWebPage['fld_fullname']);
    $gWebPage['fld_content_editor'] = RenderHtmlEditor('fld_content', $gWebPage['fld_content'], 'chstd', 600, 400);

    DoShowPageWithContent(TPL_SIMPLE_PAGE, 'upage_edit.htm');
}

function MemberPageSave()
{
    global $db;
    global $gWebPage;
    global $uname;

    $sname = UserGetName();
    if (!IsUserAdmin() && $sname != $uname)
    {
        Header("Location: /members/$uname.html");
        die();
    }

    if (UserPageCheck())
    {
        $bPhotoOK = UploadPhoto($errmsg);
        
        //if (!$bPhotoOK)
        //{
        //    PrintLine($errmsg, 'Error');
        //    die();
        //}
        
        $colvalues  = 'm_desc='.$db->qstr($gWebPage['fld_desc']);
        $colvalues .= ', m_page='.$db->qstr($gWebPage['fld_content']);
        if ($bPhotoOK)
            $colvalues .= ', m_photo=1';
    
        $where =  'm_id='.$gWebPage['uid'];

        if (DbSqlUpdate('sysmember', $colvalues, $where))
        {
            Header("Location: /members/$uname.html");
            die();
        }
    }

    MemberPageEdit(false);

}

function UserPageCheck()
{
    global $gWebPage;

    $gWebPage['uid']           = RequestGetValue('uid', 0);
    $gWebPage['fld_desc']      = RequestGetValue('fld_desc', '');
    $gWebPage['fld_content']   = RequestGetValue('fld_content', '');

    return true;
}

function UploadPhoto(&$errmsg)
{
    global $uname;
    global $gBaseLocalPath;

    $file_name 	= $_FILES['fld_photo']['name'];     	// original name
    $file_type 	= $_FILES['fld_photo']['type'];     	// mime type 
    $file_size 	= $_FILES['fld_photo']['size'];     	// filesize
    $file_temp 	= $_FILES['fld_photo']['tmp_name']; 	// temporary name
    $file_err 	= $_FILES['fld_photo']['error']; 		// error

    $file_type = strtolower($file_type);
    if ($file_type != 'image/jpeg' && $file_type != 'image/pjpeg')
    {
        $errmsg = "Your file type ($file_type). It must be JPG file";
        return false;
    }

    if ($file_size > 100000)
    {
        $errmsg = "Your file size: $file_size. We only accept no more than 100KB";
        return false;
    }

	$content = FileGetContent($file_temp, false);
	if (empty($content))
    {
        $errmsg = "$file_temp is empty";
        return false;
    }
    
    $size = GetStrImageSize($content);
    if ($size['w'] < 75 || $size['w'] > 85 || $size['h'] < 115 || $size['h'] > 125)
    {
        $errmsg = "Your image size is (".$size['w'].'x'.$size['h'].". We only accept (80x120) image";
        return false;
    }

    $file_dest = $gBaseLocalPath."members/images/$uname.jpg";
    $fp = @fopen($file_dest, 'wb');
    if ($fp)
    {
        @fputs($fp, $content);
        @fclose($fp);
        return true;
    }

    $errmsg = "Unable to save your image to server $file_dest";
    return false;
}


function FileGetContent($filename, $use_include_path = 0) 
{
    $fd = fopen ($filename, "rb", $use_include_path);
    $contents = fread($fd, filesize($filename));
    fclose($fd);
    return $contents;
}



?>