<?php
// +--------------------------------------------------------------------+
// | DO NOT REMOVE THIS                                                 |
// +--------------------------------------------------------------------+
// | settings.php                                                       |
// | Change some global application settings.                           |
// +--------------------------------------------------------------------+
// | Author:  Cezary Tomczak [www.gosu.pl]                              |
// | Project: SimpleDoc                                                 |
// | URL:     http://gosu.pl/php/simpledoc.html                         |
// | License: GPL                                                       |
// +--------------------------------------------------------------------+

require 'shared/prepend.php';

$mayEdit = IoFile::isWritable('config.php');

$encoding = post('encoding');
$editorWidth = post('editor-width');
$editorHeight = post('editor-height');
$publish_dir = post('publish_dir');

$encoding = config_safe($encoding);
$publish_dir = config_safe($publish_dir);
$publish_dir = str_replace('\\', '/', $publish_dir);
if (substr($publish_dir, -1) == '/') {
    $publish_dir = substr($publish_dir, 0, -1);
}

if (!is_numeric($editorWidth)) { $editorWidth = null; }
if (!is_numeric($editorHeight)) { $editorHeight = null; }

if (!$encoding) { $publish_dir = $CONFIG['publish_dir']; }

$err_publish_dir = false;
if (!$publish_dir || !IoDir::exists($publish_dir) || !IoDir::isWritable($publish_dir)) { $err_publish_dir = true;}

$ok = $mayEdit && $encoding && $editorWidth && $editorHeight && !$err_publish_dir;

if ($ok) {
    $s = "<"."?"."php\r\n";
    $s .= "\$CONFIG['username'] = '{$CONFIG['username']}';\r\n";
    $s .= "\$CONFIG['password'] = '{$CONFIG['password']}';\r\n";
    $s .= "\$CONFIG['encoding'] = '$encoding';\r\n";
    $s .= "\$CONFIG['editor-width'] = '$editorWidth';\r\n";
    $s .= "\$CONFIG['editor-height'] = '$editorHeight';\r\n";
    $s .= "\$CONFIG['publish_dir'] = '$publish_dir';\r\n";
    $s .= "?".">";
    IoFile::write('config.php', $s);
}

if (!$encoding) {
    $encoding = $CONFIG['encoding'];
    $editorWidth = $CONFIG['editor-width'];
    $editorHeight = $CONFIG['editor-height'];
    $publish_dir = $CONFIG['publish_dir'];
}

?>
<?php
$TITLE = 'Settings';
include ROOT.'/shared/header.tpl';
?>

    <h1>Settings</h1>

    <?php if ($ok): ?>
        <p class="message">Settings saved successfully.</p>
    <?php endif; ?>

    <?php if (!$mayEdit): ?>
        <p class="error">File /config.php must be writable to change settings.</p>
    <?php endif; ?>

    <?php if ($err_publish_dir): ?>
        <p class="error">Publish Dir doesn't exist or is not writable.</p>
    <?php endif; ?>

    <p>
        <form action="settings.php" method="post">
        <table>
        <tr>
            <td>Encoding:</td>
            <td><input type="text" name="encoding" value="<?php echo $encoding; ?>" <?php if (!$mayEdit) echo 'disabled="disabled"'; ?>></td>
        </tr>
        <tr>
            <td>Editor width:</td>
            <td><input type="text" name="editor-width" value="<?php echo $editorWidth; ?>" <?php if (!$mayEdit) echo 'disabled="disabled"'; ?>></td>
        </tr>
        <tr>
            <td>Editor height:</td>
            <td><input type="text" name="editor-height" value="<?php echo $editorHeight; ?>" <?php if (!$mayEdit) echo 'disabled="disabled"'; ?>></td>
        </tr>
        <tr>
            <td>Publish Dir:</td>
            <td><input type="text" name="publish_dir" value="<?php echo $publish_dir; ?>" <?php if (!$mayEdit) echo 'disabled="disabled"'; ?>></td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" name="submit" value="Change settings" onclick="return validateForm(this.form)" <?php if (!$mayEdit) echo 'disabled="disabled"'; ?>>
            </td>
        </tr>
        </table>
        </form>
    </p>

    <script type="text/javascript" src="shared/form.js"></script>
    <script type="text/javascript">
    function validateForm(form) {
        var encoding = form.elements['encoding'];
        var editorWidth = form.elements['editor-width'];
        var editorHeight = form.elements['editor-height'];
        var publish_dir = form.elements['publish_dir'];
        encoding.value = encoding.value.trim();
        editorWidth.value = editorWidth.value.trim();
        editorHeight.value = editorHeight.value.trim();
        publish_dir.value = publish_dir.value.trim();
        if (!encoding.value.length) { alert("Encoding is empty"); return false; }
        if (!isNumber(editorWidth.value)) { alert("Editor width must be a number"); return false; }
        if (!isNumber(editorHeight.value)) { alert("Editor height must be a number"); return false; }
        if (!publish_dir.value.length) { alert("Publish Dir is empty"); return false; }
        return true;
    }
    </script>

<?php
include ROOT.'/shared/footer.tpl';
?>