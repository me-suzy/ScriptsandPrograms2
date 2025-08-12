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

if (!IoFile::exists($path)) {
    echo "Unknown document: $path";
    exit;
}

$html = IoFile::read($path);
header('Content-type: text/html; charset='.$CONFIG['encoding']);

?>

<form action="javascript:void(0)" method="post">
    
    <textarea id="body" name="body" cols="60" rows="10"><?php echo htmlspecialchars($html); ?></textarea>
    <p><input id="save-document" type="button" value="Save Document" onclick="ste.submit(); saveContent(); this.blur();"> (ctrl+s)</p>
    <input type="hidden" id="body-tmp" name="body-tmp" value="<?php echo htmlspecialchars($html); ?>">

</form>

<p id="saved"></p>