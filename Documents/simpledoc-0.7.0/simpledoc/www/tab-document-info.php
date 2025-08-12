<?php
// +--------------------------------------------------------------------+
// | DO NOT REMOVE THIS                                                 |
// +--------------------------------------------------------------------+
// | tab-document-info.php                                              |
// | Returns document info in html format.                              |
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

if (!IoFile::exists($path)) {
    echo "Unknown document: $path";
    exit;
}

$created = IoFile::getCreationTime($path);
$modified = IoFile::getLastWriteTime($path);
$size = IoFile::getSize($path);

$html = IoFile::read($path);
$text = strip_tags($html);
$words = str_word_count($text);

header('Content-type: text/html; charset='.$CONFIG['encoding']);

?>

<table cellspacing="1" cellpadding="0" class="t0">
<tr>
    <td class="t1">Path</td>
    <td class="t2"><?php echo htmlspecialchars($id); ?></td>
</tr>
<tr>
    <td class="t1">Bytes</td>
    <td class="t2"><?php echo $size; ?></td>
</tr>
<tr>
    <td class="t1">Words</td>
    <td class="t2"><?php echo $words; ?></td>
</tr>
<tr>
    <td class="t1">Created</td>
    <td class="t2"><?php echo substr($created, 0, -3); ?></td>
</tr>
<tr>
    <td class="t1">Modified</td>
    <td class="t2"><?php echo substr($modified, 0, -3); ?></td>
</tr>
</table>

<p><a onclick="this.blur()" href="export-single.php?id=<?php echo urlencode($id); ?>">Export as single document</a></p>