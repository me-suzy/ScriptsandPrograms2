<?php 
// ----------------------------------------------------------------------
// ModName: theme_chg.php
// Purpose: Change the user theme and refresh the current page
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

require_once("../_files/library/_config.php");

$theme = RequestGetValue('t', '');
if (!empty($theme))
    ChangeCurrentUserTheme($theme);

$path = RequestGetValue('path', '');
if (empty($path))
    $path = '/index.html';
else
    $path = StrUnEscape($path);

Header("Location: $path");


?>
