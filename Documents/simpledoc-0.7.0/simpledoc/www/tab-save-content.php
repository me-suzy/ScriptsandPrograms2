<?php
// +--------------------------------------------------------------------+
// | DO NOT REMOVE THIS                                                 |
// +--------------------------------------------------------------------+
// | tab-edit-content.php                                               |
// | Editing document, wysiwyg editor, saving data, returns html.       |
// +--------------------------------------------------------------------+
// | Author:  Cezary Tomczak [www.gosu.pl]                              |
// | Project: SimpleDoc                                                 |
// | URL:     http://gosu.pl/php/simpledoc.html                         |
// | License: GPL                                                       |
// +--------------------------------------------------------------------+

require 'shared/prepend.php';
require 'shared/nocache.php';

$id = fix_charset(get('id'));
$path = $CONTENT.'/'.$id;
$body = fix_charset(post('body'));

if (!IoFile::exists($path)) {
    echo "Unknown document: $path";
    exit;
}

IoFile::write($path, $body);

?>