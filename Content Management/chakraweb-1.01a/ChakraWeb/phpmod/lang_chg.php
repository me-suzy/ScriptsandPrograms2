<?php 
// ----------------------------------------------------------------------
// ModName: lang_chg.php
// Purpose: Change the user language and refresh the current page
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

$lid  = RequestGetValue('lid', '');
$path = RequestGetValue('path', '');

if (empty($path))
    $path = '/index.html';
else
    $path = StrUnEscape($path);

ChangeCurrentUserLanguage($lid);
Header("Location: $path");

?>
