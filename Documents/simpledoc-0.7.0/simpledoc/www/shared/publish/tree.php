<?php
// +--------------------------------------------------------------------+
// | DO NOT REMOVE THIS                                                 |
// +--------------------------------------------------------------------+
// | tree.php                                                           |
// | Tree Menu template.                                                |
// +--------------------------------------------------------------------+
// | Author:  Cezary Tomczak [www.gosu.pl]                              |
// | Project: SimpleDoc                                                 |
// | URL:     http://gosu.pl/php/simpledoc.html                         |
// | License: GPL                                                       |
// +--------------------------------------------------------------------+

$structure = array();
build_tree($structure, $SECTION);

IoDir::create($TMP, $CHMOD_DIR);
IoDir::create($TMP.'/html', $CHMOD_DIR);

$files = IoDir::readFull($SECTION);

foreach ($files as $file) {
    if ($file == $TMP) continue;
    if ($file == $TMP.'/html') continue;
    if (get_name($file) == $SORT) continue;
    
    $id = substr($file, strlen($SECTION.'/'));
    $real_id = substr($file, strlen($CONTENT.'/'));
    $file2 = $TMP .'/html/'. $id;
    
    if (IoFile::exists($file)) {
        IoFile::write($file2, fetch_document_tree($real_id));
        IoFile::chmod($file2, $CHMOD_FILE);
    } else if (IoDir::exists($file)) {
        IoDir::create($file2, $CHMOD_DIR);
    }
}

unset($files, $file, $file2);

$Page = new Template('shared/publish/tree-index.tpl');
$Page->set('encoding', $CONFIG['encoding']);
$index = $Page->fetch();

$Page = new Template('shared/publish/tree-tree.tpl');
$Page->set('encoding', $CONFIG['encoding']);
$Page->set('tree', $structure);
$tree = $Page->fetch();

unset($structure, $Page);

IoDir::copy('shared/publish/DynamicTree', $TMP.'/shared', $CHMOD_FILE, $CHMOD_DIR);

IoFile::create($TMP.'/empty.html', $CHMOD_FILE);

IoFile::write($TMP.'/index.html', $index);
IoFile::chmod($TMP.'/index.html', $CHMOD_FILE);

IoFile::write($TMP.'/tree.html', $tree);
IoFile::chmod($TMP.'/tree.html', $CHMOD_FILE);

unset($index, $tree);

?>