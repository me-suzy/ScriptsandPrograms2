<?php 
// ----------------------------------------------------------------------
// ModName: wpage_redirect_add.php
// Purpose: Add Redirect Web Page
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");


SetDynamicContent();
CheckRequestRandom();

$op = RequestGetValue('op', '');
if ($op != 'do')
    WebPageError(_ERR_OPR_DENIED_TITLE, _ERR_OPR_DENIED_MESSAGE);

$path = RequestGetValue('path', '');
if (empty($path))
    $path = "/index.html";

$name       = strtolower(RequestGetValue('fld_name', '', CLEAN_ALL));
$title      = RequestGetValue('fld_title', '', CLEAN_ALL);
$desc       = RequestGetValue('fld_desc', '', CLEAN_ALL);
$keywords   = RequestGetValue('fld_keywords', '', CLEAN_ALL);
$redirect   = RequestGetValue('fld_redirect', '', CLEAN_ALL);
    
if (CheckRedirectParams($name, $redirect))
{
    AddPageRedirect($gFolderId, $name, $redirect, $title, $desc, $keywords);
}

Header("Location: $path");
    

function CheckRedirectParams($name, $redirect)
{
    $archk = explode('.', $name, 2); 
    if ($archk[1] != 'html')
        return false;

    if (!StrIsStartWith($redirect, "\/") && !IsHttpUrlValid($redirect))
        return false;

    return true; 
}


?>
