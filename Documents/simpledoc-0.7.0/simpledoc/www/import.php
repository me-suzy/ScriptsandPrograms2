<?php
// +--------------------------------------------------------------------+
// | DO NOT REMOVE THIS                                                 |
// +--------------------------------------------------------------------+
// | import.php                                                         |
// | Import raw content.                                                |
// +--------------------------------------------------------------------+
// | Author:  Cezary Tomczak [www.gosu.pl]                              |
// | Project: SimpleDoc                                                 |
// | URL:     http://gosu.pl/php/simpledoc.html                         |
// | License: GPL                                                       |
// +--------------------------------------------------------------------+

require 'shared/prepend.php';
include 'shared/zip.php';

$ZipFile = new Upload('zip_file');
$err = array();

if (isPOST() && $ZipFile->isValid()) {
    
    $name = substr($ZipFile->filename, 0, -strlen($ZipFile->getExtension()));
    $path = $ZipFile->tmp;
    
    $zip = new zip;
    $list = @$zip->get_List($path);
    if (!$list || !count($list)) $err['invalid_zip'] = true;

    if (!count($err)) {
        $root = $list[0]['filename'];
        $root = substr($root, 0, strpos($root, '/'));
        if ($root != $name) $err['invalid_zip'] = true;
    }

    if (!count($err)) {
        $tmp = $PUBLISH.'/import-'.$name;
        IoDir::create($tmp, $CHMOD_DIR);
        extract_zip($path, $tmp);
        IoDir::delete($CONTENT, false);
        IoDir::copy($tmp.'/'.$name, $CONTENT, $CHMOD_FILE, $CHMOD_DIR);
        IoDir::delete($tmp);
        redirect('redirect.php?url=index.php&msg=Content+imported+successfully');
    }
}

$TITLE = 'Import';
include 'shared/header.tpl';
?>

    <h1>Import content</h1>

    <?php if (isPOST() && !$ZipFile->isValid()): ?>
        <p class="error">There was an error while uploading file</p>
    <?php endif; ?>

    <?php if (isset($err['invalid_zip'])): ?>
        <p class="error">The zip file contains invalid data</p>
    <?php endif; ?>

    <form action="import.php" method="post" enctype="multipart/form-data">
    <table>
    <tr>
        <td>Zip file:</td>
        <td><input type="file" name="zip_file" size="30"></td>
    </tr>
    <tr>
        <td colspan="2"><input type="submit" name="submit" value="Import" onclick="return validateForm(this.form)"></td>
    </tr>
    </table>
    </form>

    <script type="text/javascript" src="shared/form.js"></script>
    <script type="text/javascript">
    function validateForm(form) {
        if (!form.elements["zip_file"].value) { alert("Zip file is required"); return false; }
        return true;
    }
    </script>

    
    <p>The zip file should contain one folder (in which raw content is placed) with the same name as the zip file (without the .zip).</p>

<?php include 'shared/footer.tpl'; ?>