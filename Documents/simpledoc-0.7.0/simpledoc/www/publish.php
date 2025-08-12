<?php
// +--------------------------------------------------------------------+
// | DO NOT REMOVE THIS                                                 |
// +--------------------------------------------------------------------+
// | publish.php                                                        |
// | Publish the content using one of available templates.              |
// +--------------------------------------------------------------------+
// | Author:  Cezary Tomczak [www.gosu.pl]                              |
// | Project: SimpleDoc                                                 |
// | URL:     http://gosu.pl/php/simpledoc.html                         |
// | License: GPL                                                       |
// +--------------------------------------------------------------------+

require 'shared/prepend.php';

$documents = 0;
$folders = 0;
$size = 0;

$files = IoDir::readFull($CONTENT);
$sections = array();
foreach ($files as $file) {
    if (get_name($file) == $SORT) continue;
    $id = substr($file, strlen($CONTENT)+1);
    if (IoFile::exists($file)) {
        $documents++;
        $size += IoFile::getSize($file);
    } else if (IoDir::exists($file)) {
        $folders++;
        $sections[$id] = $id;
    }
}

$publish_dir_ok = true;
if ($CONFIG['publish_dir']) {
    if (!IoDir::exists($CONFIG['publish_dir']) || !IoDir::isWritable($CONFIG['publish_dir'])) { $publish_dir_ok = false; }
}

?>
<?php
$TITLE = 'Publish';
include ROOT.'/shared/header.tpl';
?>

    <h1>Publish</h1>

    <p>
        <table>
        <tr>
            <td>Documents:</td>
            <td>&nbsp;<b><?php echo $documents; ?></b></td>
        </tr>
        <tr>
            <td>Folders:</td>
            <td>&nbsp;<b><?php echo $folders; ?></b></td>
        </tr>
        <tr>
            <td>Size:</td>
            <td>&nbsp;<b><?php echo get_readable_size($size); ?></b></td>
        </tr>
        </table>
    </p>
    <p>
        <form name="zip" action="publish-export.php" method="get">
        <table>
        <tr>
            <td>Send as zip using template:</td>
            <td>
                <select name="template">
                    <option value="tree">Tree Menu</option>
                    <!--<option value="drop">Drop Down Menu</option>-->
                    <option value="raw">Raw html files</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" name="submit" value="Send zip" onclick="this.blur(); setCookie('publish-zip-template', this.form.template.selectedIndex, COOKIE_YEAR);">
            </td>
        </tr>
        </form>

        <tr><td colspan="2">&nbsp;</td></tr>
        
        <form name="dir" action="publish-export.php" method="get" onsubmit="this.submit.disabled=true;">
        <input type="hidden" name="publish_dir" value="1">
        <tr>
            <td>Publish using template:</td>
            <td>
                <select name="template">
                    <option value="tree">Tree Menu</option>
                    <!--<option value="drop">Drop Down Menu</option>-->
                    <option value="raw">Raw html files</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Only specified section:</td>
            <td>
                <select name="section"><option value="">(optional)</option><?php echo Template::htmlOptions($sections); ?></select>
            </td>
        </tr>
        <tr>
            <td>Publish Directory:</td>
            <td><?php echo $CONFIG['publish_dir']; ?></td>
        <tr>
            <td colspan="2">
                <input type="submit" name="submit" value="Publish" onclick="this.blur(); setCookie('publish-dir-template', this.form.template.selectedIndex, COOKIE_YEAR);" <?php if (!$CONFIG['publish_dir'] || !$publish_dir_ok) echo 'disabled="disabled"'; ?>>
                <?php if (!$publish_dir_ok): ?>
                    <p class="error">Publish Dir doesn't exist or is not writable.</p>
                <?php endif; ?>
            </td>
        </tr>
        </table>
        </form>
    </p>

    <script type="text/javascript">
    if (getCookie("publish-zip-template")) document.forms["zip"].template.selectedIndex = getCookie("publish-zip-template");
    if (getCookie("publish-dir-template")) document.forms["dir"].template.selectedIndex = getCookie("publish-dir-template");
    </script>

<?php
include ROOT.'/shared/footer.tpl';
?>