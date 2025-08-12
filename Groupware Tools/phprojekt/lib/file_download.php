<?php

// file_download.php - PHProjekt Version 5.0
// copyright  ©  2000-2005 Albrecht Guenther  ag@phprojekt.com
// www.phprojekt.com
// Author: Nina

$path_pre = '../';
include_once($path_pre.'lib/lib.inc.php');
download_attached_file($download_attached_file, $module);

function download_attached_file($rnd) {
    global $tablename, $path_pre, $name, $file_ID;

    $arr = explode('|', $file_ID[$rnd]);

    // Prevent escaping from the attach dir
    if ((ereg('/', $arr[1])) or (ereg('^\.+$', $arr[1]))) die("You are not allowed to do this!");

    // assign the filename
    $name = $arr[0];

    // have a look whether this file exists
    if (!file_exists($path_pre.PHPR_DOC_PATH.'/'.$arr[1])) die("panic! specified file not found ...");

    // include content type definition
    $include_path = $path_pre.'lib/get_contenttype.inc.php';
    include_once $include_path;

    // stream the file
    readfile($path_pre.PHPR_DOC_PATH.'/'.$arr[1]);
}