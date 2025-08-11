<?php 
// ----------------------------------------------------------------------
// ModName: file_mgmt.php
// Purpose: File Management
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

SetDynamicContent();
CheckOpForAdminOnly();

$op = RequestGetValue('op', 'frame');
switch ($op)
{
case 'addfolder':
    FileMgmtAddFolder();
    break;
case 'delfolder':
    FileMgmtDeleteFolder();
    break;
case 'upload':
    FileMgmtUploadFile();
    break;
case 'edit':
    FileMgmtEditFile();
    break;
case 'save':
    FileMgmtSaveFile();
    break;
case 'create':
    FileMgmtCreateFile();
    break;
case 'delete':
    FileMgmtDeleteFile();
    break;
case 'header':
    FileMgmtShowHeader();
    break;
case 'content':
    FileMgmtShowContent();
    break;
case 'folder':
    FileMgmtShowFolder();
    break;
case 'file':
    FileMgmtShowFile();
    break;
case 'frame':
default:
    FileMgmtShowFrame();
    break;
}

function FileMgmtAddFolder()
{
    global $gBaseLocalPath;

    CheckRequestRandom();

    $path = RequestGetValue('path', '');
    $name = RequestGetValue('fld_name', '');

    if (@mkdir($gBaseLocalPath.$path.$name, 0666))
    {
        Header("Location: /phpmod/file_mgmt.php?op=content&path=$path$name/");
    }
    else
    {
        Header("Location: /phpmod/file_mgmt.php?op=content&path=$path");
    }
}

function FileMgmtDeleteFolder()
{
    global $gBaseLocalPath;

    $path = RequestGetValue('path', '');
    
    if (@rmdir($gBaseLocalPath.$path))
    {
        $parent = GetParentPath($path);
        Header("Location: /phpmod/file_mgmt.php?op=content&path=$parent");
    }
    else
    {
        Header("Location: /phpmod/file_mgmt.php?op=content&path=$path");
    }
}

function FileMgmtUploadFile()
{
    CheckRequestRandom();

    $path = RequestGetValue('path', '');
    DoUploadFile($path);

    FileMgmtShowFile();
}

function FileMgmtEditFile()
{
    global $gWebPage;
    global $gBaseLocalPath;

    $path = RequestGetValue('path', '');
    $file = RequestGetValue('file', '');

    $content = ReadLocalFile($gBaseLocalPath.$path.$file, $errmsg, true);
    
    $params = array();

    $params['file_path']  = $path.$file;
    $params['fld_path']   = $path;
    $params['fld_file']   = $file;
    $params['fld_content']= $content;
    
    $gWebPage['page_content'] = LoadContentFile($gBaseLocalPath.'_lang/'.UserGetLID().'/file_mgmt_content.htm', $params);

    //echo $gWebPage['page_content'];

    DoShowPage(TPL_BLANK_PAGE);
}

function FileMgmtSaveFile()
{
    global $gWebPage;
    global $gBaseLocalPath;

    CheckRequestRandom();

    $path    = RequestGetValue('path', '');
    $file    = RequestGetValue('file', '');
    $content = RequestGetValue('fld_content', '');

    WriteLocalFile($gBaseLocalPath.$path.$file, $content);

    Header("Location: /phpmod/file_mgmt.php?op=file&path=$path");
}

function FileMgmtCreateFile()
{
    global $gWebPage;
    global $gBaseLocalPath;

    CheckRequestRandom();

    $path    = RequestGetValue('path', '');
    $file    = RequestGetValue('fld_file', '');
    $content = RequestGetValue('fld_content', '');

    if (is_file($gBaseLocalPath.$path.$file))
        $errmsg = _ERR_FILE_ALREADY_EXIST;
    else
    {
        $errmsg = '';
        WriteLocalFile($gBaseLocalPath.$path.$file, $content);
    }

    FileMgmtShowFile($errmsg);
}

function FileMgmtDeleteFile()
{
    global $gWebPage;
    global $gBaseLocalPath;

    $path    = RequestGetValue('path', '');
    $file    = RequestGetValue('file', '');

    unlink($gBaseLocalPath.$path.$file);

    Header("Location: /phpmod/file_mgmt.php?op=file&path=$path");
}

function FileMgmtShowFrame()
{
    print '
<html>

<head>
<title>'._FILE_MANAGEMENT_TITLE.'</title>
</head>

<frameset rows="90,*">
  <frame src="/phpmod/file_mgmt.php?op=header" name="header" scrolling="no" noresize>
  <frame src="/phpmod/file_mgmt.php?op=content" name="content">
  <noframes>
    <body>
    <p>This page uses frames, but your browser doesn\'t support them.</p>
    </body>
  </noframes>
</frameset>

</html>';
}

function FileMgmtShowContent()
{
    $path = RequestGetValue('path', '');
    
    print '
<html>

<head>
<title>'._FILE_MANAGEMENT_TITLE.'</title>
</head>

  <frameset cols="200,*">
    <frame src="/phpmod/file_mgmt.php?op=folder&path='.$path.'" name="folder">
    <frame src="/phpmod/file_mgmt.php?op=file&path='.$path.'" name="file">

  <noframes>
    <body>
    <p>This page uses frames, but your browser doesn\'t support them.</p>
    </body>
  </noframes>

  </frameset>


</html>';
}


function FileMgmtShowHeader()
{
    global $gRequestPath, $gCurrentUrlPath, $gRequestFile;
    global $gFolderId, $gPageId;
    global $gPageNavigation, $gWebPage, $gHomePageHeader, $gHomePageFooter;

    $gFolderId = 0;
    $gRequestPath = '/'; //FindPathFromFolderId($gFolderId);
    $gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
    $gRequestFile = 'index.html';
    $gPageId = RequestGetValue('id', 0);

    DBGetFolderData(0);

    $gPageNavigation = array();
    $gPageNavigation[] = array($gHomePageUrl.$gBaseUrlPath."/index.html", _NAV_FRONTPAGE);
    $gPageNavigation[] = array($gHomePageUrl."/phpmod/cpanel.php", _NAV_CONTROL_PANEL);
    $gPageNavigation[] = array($gHomePageUrl."/phpmod/file_mgmt.php", _NAV_FILE_MANAGEMENT);

    $gWebPage['page_sidebar']   = RenderPageSidebar();
    $gWebPage['page_header']    = WebContentParse($gHomePageHeader);
    $gWebPage['page_footer']    = WebContentParse($gHomePageFooter);
    $gWebPage['page_title']     = _FILE_MANAGEMENT_TITLE;

    
    DoShowPage(TPL_HDR_PAGE);
}

function FileMgmtShowFolder()
{
    global $gWebPage;
    global $gBaseLocalPath;

    $base = RequestGetValue('path', '');
    $path = $gBaseLocalPath.$base;

    $list = "";

    $d = dir($path);
    while($entry = $d->read()) 
    {
        if ($entry != "." && $entry != "..") 
        {   
            if (is_dir($path.$entry)) 
            {
                $url = '/phpmod/file_mgmt.php?op=content&path='.$base.$entry.'/';
                $href = '<a href="'.$url.'" target="content">'.$entry.'</a>';
                $list .= '<tr><td id="tbl_text">'.$href."</td></tr>\n";
            }
        }
    }

    $gWebPage['fld_folder_title'] = GetProperPathForTitle($base);
    $gWebPage['fld_folder_list'] =  $list;
    $gWebPage['fld_path'] = $base;

    DoShowPageWithContent(TPL_BLANK_PAGE, 'file_mgmt_folder.htm');
}

function FileMgmtShowFile($errmsg='')
{
    global $gWebPage;
    global $gBaseLocalPath;

    $base = RequestGetValue('path', '');
    $path = $gBaseLocalPath.$base;

    $list = '';

    $d = dir($path);
    while($entry = $d->read()) 
    {
        if ($entry != "." && $entry != "..") 
        {   
            if (!is_dir($path.$entry)) 
            {
                $op  = '<font face="verdana" size="1">';
                $op .= '['.HRef('/phpmod/file_mgmt.php?op=edit&path='.$base.'&file='.$entry, _NAV_EDIT).'] ';
                $op .= '['.HRef('/phpmod/file_mgmt.php?op=delete&path='.$base.'&file='.$entry, _NAV_DELETE).'] ';
                $op .= '</font>'; 

                $list .= '<tr><td id="tbl_text">'.HRef('/'.$base.$entry, $entry)."</td><td>$op</td></tr>\n";
            }
        }
    }


    $gWebPage['fld_path_title'] = GetProperPathForTitle($base);
    $gWebPage['fld_file_list'] = $list;
    $gWebPage['fld_path'] = $base;

    $gWebPage['fld_create_msg'] = $errmsg;
    if (empty($errmsg))
    {
        $gWebPage['fld_file'] = '';
        $gWebPage['fld_content'] = '';
    }
    else
    {
        $gWebPage['fld_file']    = RequestGetValue('fld_file', '');
        $gWebPage['fld_content'] = RequestGetValue('fld_content', '');
    }

    DoShowPageWithContent(TPL_BLANK_PAGE, 'file_mgmt_file.htm');
}

function GetProperPathForTitle($path)
{
    $tpl = '/phpmod/file_mgmt.php?op=content&path=';

    $out = HRef($tpl, "ROOT", 'content');
    $cp = '';

    $list = explode('/', $path);
    foreach($list as $entry)
    {
        $cp .= $entry.'/';
        $out = $out.'/'.HRef($tpl.$cp, $entry, 'content');
    }

    return $out;

    //if (empty($path))
    //    return 'ROOT';
    //else
    //    return $path;
}

function DoUploadFile($path)
{
    global $uname;
    global $gBaseLocalPath;

    $file_name 	= $_FILES['fld_file']['name'];     	// original name
    $file_size 	= $_FILES['fld_file']['size'];     	// filesize
    $file_temp 	= $_FILES['fld_file']['tmp_name']; 	// temporary name

    if ($file_size <= 0)
        return false;

    $file_dest = $gBaseLocalPath.$path.$file_name;
    return @copy($file_temp, $file_dest);
}


?>
