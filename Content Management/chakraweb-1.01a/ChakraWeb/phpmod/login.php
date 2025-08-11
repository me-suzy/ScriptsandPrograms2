<?php 
// ----------------------------------------------------------------------
// ModName: login.php
// Purpose: Process login to the website
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

CheckRequestRandom();

$gRequestPath = FindPathFromFolderId($gFolderId);
$gCurrentUrlPath = $gBaseUrlPath.$gRequestPath;
DBGetFolderData($gFolderId);

$uid = RequestGetValue('uid', '');
$psw = RequestGetValue('psw', '');

//check validity of $uid and $psw first
if (!IsUPValid($uid) || !IsUPValid($psw))
    WebPageError(_ERR_LOGIN_FAILED_TITLE, _ERR_LOGIN_FAILED_MESSAGE);

if (!UserLogin($uid, $psw))
    WebPageError(_ERR_LOGIN_FAILED_TITLE, _ERR_LOGIN_FAILED_MESSAGE);


//Login success. Redirect to startpage 

$minfo = MemberGetInfo(UserGetID(), '');
if ($minfo)
    $redirect = $minfo['m_startpage'];

if (empty($redirect))
{
    $gPageId = RequestGetValue('id', 0);
    if ($gPageId > 0)
        $gRequestFile = DBGetFileName($gFolderId, $gPageId);
    else
        $gRequestFile = 'index.html';

    $redirect = $gCurrentUrlPath.$gRequestFile;
}

srand((double)microtime()*1000000);
Header("Location: $redirect?rnd=".rand());

?>
