<?php
// +--------------------------------------------------------------------+
// | DO NOT REMOVE THIS                                                 |
// +--------------------------------------------------------------------+
// | publish-export.php                                                 |
// | Export the content using one of available templates.               |
// +--------------------------------------------------------------------+
// | Author:  Cezary Tomczak [www.gosu.pl]                              |
// | Project: SimpleDoc                                                 |
// | URL:     http://gosu.pl/php/simpledoc.html                         |
// | License: GPL                                                       |
// +--------------------------------------------------------------------+

require 'shared/prepend.php';

$template = get('template');
$publish_dir = get('publish_dir');
$section = get('section');

switch ($template) {
    case 'tree':
    case 'drop':
    case 'raw':
        $DIR = "publish-$template-".date('Y-m-d');
        $TMP = $PUBLISH.'/tmp-'.$DIR;
        $SECTION = $section ? $CONTENT.'/'.$section : $CONTENT;
        if ($publish_dir) {
            IoDir::delete($PUBLISH, false);
            include "shared/publish/$template.php";
            IoDir::copy($TMP, $PUBLISH, $CHMOD_FILE, $CHMOD_DIR);
            IoDir::delete($TMP);
            redirect('redirect.php?msg=Published+successfully&url=publish.php');
        } else {
            include "shared/publish/$template.php";
            send_zip_clear($TMP, $DIR.'.zip', $DIR);
        }
        break;
    default:
        echo 'Unknown template.';
        exit;
        break;
}

function send_zip_clear($dir, $filename, $new_dir = null) {
    
    require ROOT.'/shared/zip.php';
    $files = IoDir::readFull($dir);

    $zip = new zip();
    foreach ($files as $file) {
        $file2 = $new_dir ? $new_dir . substr($file, strlen($dir)) : $file;
        if (IoDir::exists($file)) {
            $zip->add_dir($file2);
        } else if (IoFile::exists($file)) {
            $zip->add_file(IoFile::read($file), $file2);
        }
    }

    IoDir::delete($dir);

    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo $zip->get_file();
    exit;
}

?>