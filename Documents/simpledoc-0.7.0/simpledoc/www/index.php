<?php
// +--------------------------------------------------------------------+
// | DO NOT REMOVE THIS                                                 |
// +--------------------------------------------------------------------+
// | index.php                                                          |
// | Documents and folders management.                                  |
// +--------------------------------------------------------------------+
// | Author:  Cezary Tomczak [www.gosu.pl]                              |
// | Project: SimpleDoc                                                 |
// | URL:     http://gosu.pl/php/simpledoc.html                         |
// | License: GPL                                                       |
// +--------------------------------------------------------------------+

require 'shared/prepend.php';
include 'shared/initialization.php';

$tree = array();
build_tree($tree, $CONTENT);

function build_tree_html($tree) {
    $ret = '';
    foreach ($tree as $id => $v) {
        $name = strpos($id, '/') !== false ? substr($id, (int)strrpos($id, '/')+1) : $id;
        if (is_array($v)) {
            $ret .= sprintf('<div class="folder" id="tree-%s">%s', $id, $name);
            $ret .= build_tree_html($tree[$id]);
            $ret .= '</div>';
        } else {
            $ret .= sprintf('<div class="doc" id="tree-%s">%s</div>', $id, substr($name, 0, -5));
        }
    }
    return $ret;
}

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CONFIG['encoding']; ?>">
    <title>SimpleDoc</title>
    <link rel="stylesheet" type="text/css" href="shared/style.css">
    <link rel="stylesheet" type="text/css" href="shared/XulMenu/XulMenu.css">
    <link rel="stylesheet" type="text/css" href="shared/DynamicTree/DynamicTree.css">
    <link rel="stylesheet" type="text/css" href="shared/XulTabs/XulTabs.css">
    <script type="text/javascript" src="shared/common.js"></script>
    <script type="text/javascript" src="shared/XulMenu/XulMenu.js"></script>
    <script type="text/javascript" src="shared/DynamicTree/DynamicTree.js"></script>
    <script type="text/javascript" src="shared/XulTabs/XulTabs.js"></script>
    <script type="text/javascript" src="shared/management.js.php"></script>
    <script type="text/javascript" src="shared/request.js.php"></script>
    <script type="text/javascript" src="shared/SimpleTextEditor/SimpleTextEditor.js"></script>
    <script type="text/javascript" src="shared/debug.js"></script>
    <link rel="stylesheet" type="text/css" href="shared/SimpleTextEditor/SimpleTextEditor.css.php">
    <script type="text/javascript">
    /* fix for IE not loading wysiwyg editor images */
    var imgs = ["bold.gif", "center.gif", "help.gif", "image.gif", "indent.gif", "italic.gif", "left.gif", "link.gif", "ol.gif", "outdent.gif", "right.gif", "ul.gif", "underline.gif", "viewsource.gif"];
    for (var k = 0; k < imgs.length; ++k) {
        var image = new Image();
        image.src = "shared/SimpleTextEditor/images/"+imgs[k];
        imgs[k] = image;
    }
    </script>
</head>
<body>

<?php include ROOT.'/shared/menu.tpl'; ?>
     
<table cellspacing="0" cellpadding="0" width="100%" height="100%">
<tr>
    <td class="menu">
        &nbsp;
        <div id="user">admin</div>
    </td>
</tr>
<tr>
    <td id="top">
        <h1 class="nomargin">Management</h1>
    </td>
</tr>
<tr>
    <td>
        <!-- MAIN -->
        <table cellspacing="0" cellpadding="0" width="100%" height="100%" id="main">
        <tr>
            <td id="left">
                <div class="DynamicTree">
                    <div class="wrap1">
                        <div class="top">Tree View</div>
                        <div class="wrap2">
                            <div id="tree">
                                <?php echo build_tree_html($tree); ?>
                            </div>
                            <script type="text/javascript">var tree = new DynamicTree("tree"); tree.path = "shared/DynamicTree/images/"; tree.init();</script>
                        </div>
                    </div>
                    <div class="actions">
                        <a id="tree-moveUp" class="moveUp" href="javascript:void(0)"><img src="shared/DynamicTree/images/moveUp.gif" width="20" height="20" alt=""></a>
                        <a id="tree-moveDown" class="moveDown" href="javascript:void(0)"><img src="shared/DynamicTree/images/moveDown.gif" width="20" height="20" alt=""></a>
                        <a id="tree-moveLeft" class="moveLeft" href="javascript:void(0)"><img src="shared/DynamicTree/images/moveLeft.gif" width="20" height="20" alt=""></a>
                        <a id="tree-moveRight" class="moveRight" href="javascript:void(0)"><img src="shared/DynamicTree/images/moveRight.gif" width="20" height="20" alt=""></a>
                        <a id="tree-insert" class="insert" href="javascript:void(0)"><img src="shared/DynamicTree/images/insert.gif" width="20" height="20" alt=""></a>
                        <a id="tree-remove" class="remove" href="javascript:void(0)"><img src="shared/DynamicTree/images/delete.gif" width="20" height="20" alt=""></a>
                        <div class="tooltip" id="tree-tooltip"></div>
                    </div>
                    <div id="tree-insert-form">
                        <form action="javascript:void(0)" method="get">
                            <table cellspacing="0" cellpadding="0">
                            <tr id="tree-insert-where-div">
                                <td class="label">Where</td>
                                <td><select id="tree-insert-where" name="tree-insert-where" class="where"><option value="before">Before</option><option value="after">After</option></select></td>
                            </tr>
                            <tr>
                                <td class="label">Type</td>
                                <td><select id="tree-insert-type" name="tree-insert-type"><option value="doc">Document</option><option value="folder">Folder</option></select></td>
                            </tr>
                            <tr>
                                <td class="label">Name</td>
                                <td><input class="input" size="20" id="tree-insert-name" name="tree-insert-name" type="text" value="" /></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <input id="tree-insert-button" class="button" type="button" value="Insert" />
                                    <input id="tree-insert-cancel" type="button" value="Cancel" />
                                </td>
                            </tr>
                            </table>
                        </form>
                    </div>
                    <script type="text/javascript" src="shared/DynamicTree/actions.js"></script>
                </div>
            </td>
            <td id="right">
                <table cellspacing="0" cellpadding="0" width="100%" height="100%" id="tabs" class="XulTabs">
                <tr>
                    <td class="wrap1">
                        <table cellspacing="0" cellpadding="0">
                        <tr>
                            <td><a id="tab1" class="tab" href="javascript:void(0)" onclick="documentInfo(); this.blur();">Document Info</a></td>
                            <td><a id="tab2" class="tab right" href="javascript:void(0)" onclick="editContent(); this.blur();">Edit content</a></td>
                        </tr>
                        </table>
                    </td>
                    <td align="right">
                        <input id="openEditContent" type="checkbox" value="1" onclick="this.checked ? setCookie('openEditContent', 1, COOKIE_YEAR) : delCookie('openEditContent'); this.blur();"> open Edit Content by default
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table cellspacing="0" cellpadding="0" class="content">
                        <tr>
                            <td class="wrap2">
                                <div id="tabs-loading">Loading data ..</div>
                                <div id="tabs-saving">Saving data ..</div>
                                <div id="tabs-data"></div>
                            </td>
                        </tr>
                        </table>
                    </td>
                </tr>
                </table>
            </td>
        </tr>
        </table>
    </td>
</tr>
</table>

<script type="text/javascript">
if (getCookie("openEditContent")) el('openEditContent').checked = "checked";
</script>

</body>
</html>