<?php
// +--------------------------------------------------------------------+
// | DO NOT REMOVE THIS                                                 |
// +--------------------------------------------------------------------+
// | Author:  Cezary Tomczak [www.gosu.pl]                              |
// | Project: SimpleDoc                                                 |
// | URL:     http://gosu.pl/php/simpledoc.html                         |
// | License: GPL                                                       |
// +--------------------------------------------------------------------+

$error = array();

if (!IoDir::exists($CONFIG['publish_dir']) || !IoDir::isWritable($CONFIG['publish_dir'])) {
    $error[] = $CONFIG['publish_dir'];
}

if (IoDir::exists($CONTENT)) {
    if (!IoDir::isWritable($CONTENT)) $error[] = $CONTENT;
    $all = IoDir::readFull($CONTENT);
    foreach ($all as $v) {
        if (IoDir::exists($v)) {
            if (!IoDir::isWritable($v)) $error[] = $v;
        } else {
            if (!IoFile::isWritable($v)) $error[] = $v;
        }
    }
    if (count($error) == 0) {
        if (!IoFile::exists($CONTENT.'/.sort')) {
            IoFile::create($CONTENT.'/.sort', $CHMOD_FILE);
        }
        return;
    }
} else {
    $error[] = $CONTENT;
}

?>
<?php
$TITLE = 'Initialization';
include ROOT.'/shared/header.tpl';
?>

    <h1>Initialization</h1>

    <p>Initialization failed, following files/directories does not exist or are not writable:</p>
    <ul>
        <?php foreach ($error as $v) { echo '<li>'.$v.'</li>'; } ?>
    </ul>

<?php
include ROOT.'/shared/footer.tpl';
exit;
?>