<?php
require("../../cms-config.php");

//----- START INCLUDE COMMON LIBS --------------------------------------------
$dir_name = "$CFG->dir_root/cms-libs";
$dir = dir("$dir_name/");
$dir->read(); $dir->read();
while (($lib = $dir->read())) {
    require("$dir_name/$lib");
}
$dir->close();
//----- END INCLUDE COMMON LIBS ----------------------------------------------

//----- START INCLUDE ADMIN LIBS ---------------------------------------------
$dir_name = "$CFG->dir_admin/libs";
$dir = dir("$dir_name/");
$dir->read(); $dir->read();
while (($lib = $dir->read())) {
    require("$dir_name/$lib");
}
$dir->close();
//----- END INCLUDE ADMIN LIBS -----------------------------------------------

//----- START INIT CLASSES -----
$ServerVars = new ServerVars();
$Db = new DB($CFG->db_host, $CFG->db_name, $CFG->db_user, $CFG->db_pass);
$Db->connect();
//$Session = new Session();
$Base = new Base();
//$Auth = new Auth();
$Pages = new Pages();
$Lang_pages = new LangPages();
$Lang = new Lang();
//----- END INIT CLASSES -----

$id = $ServerVars->GET["id"];
$sql = "SELECT * FROM `cms_pages` WHERE id=$id";
$page = $Db->fetch_array($Db->query($sql));
$f = fopen("$CFG->dir_root/cms-pages/$page[id]", "r");
$page["content"] = fread($f, filesize("$CFG->dir_root/cms-pages/$page[id]"));
fclose($f);

if (get_magic_quotes_runtime() || get_magic_quotes_gpc()) {
    $page["content"] = stripslashes($page["content"]);
}

?>

<html>
<head>
<title><?php echo $Lang->title ?></title>
<link rel=StyleSheet href="style.css" type="text/css">
<!-- load the main HTMLArea files -->
<script type="text/javascript" src="htmlarea.js"></script>
<script type="text/javascript" src="lang/lang.js"></script>
<script type="text/javascript" src="dialog.js"></script>
<script type="text/javascript" src="popupwin.js"></script>

<!-- load the TableOperations plugin files -->
<script type="text/javascript" src="plugins/TableOperations/table-operations.js"></script>
<script type="text/javascript" src="plugins/TableOperations/lang/lang.js"></script>

<style type="text/css">
@import url(htmlarea.css);
</style>

<script type="text/javascript">
var editor = null;
function initEditor() {
  editor = new HTMLArea("content");

  editor.config.toolbar = [
		[ "fontsize", "space",
		  "formatblock", "space",
		  "bold", "italic", "underline", "separator",
		  "strikethrough", "subscript", "superscript", "separator",
		  "justifyleft", "justifycenter", "justifyright", "justifyfull", "separator",
		  "insertorderedlist", "insertunorderedlist", "outdent", "indent", "separator",
		  "forecolor", "hilitecolor", "textindicator", "separator",
		  "inserthorizontalrule", "createlink", "insertimage", "inserttable",  "separator", "htmlmode"
		   ]
		];

editor.config.fontsize = {
		"1 (8 pt)":  "1",
		"2 (10 pt)": "2",
		"3 (12 pt)": "3",
		"4 (14 pt)": "4",
		"5 (18 pt)": "5",
		"6 (24 pt)": "6",
		"7 (36 pt)": "7"
	};

editor.config.formatblock = {
		"Normal": "p",
		"Header 1": "h1",
		"Header 2": "h2",
		"Header 3": "h3",
		"Header 4": "h4",
		"Header 5": "h5",
		"Header 6": "h6"
	};
  editor.config.pageStyle = "/cms-style.css"; 
  editor.registerPlugin("TableOperations");
  editor.generate();
  return false;
}
</script>
</head>

<body bgcolor=#ffffff text=#000000 link=#c80000 vlink=#c80000 alink=#c0c0c0 leftmargin=5 topmargin=5 marginwidth=5 marginheight=5 onload="initEditor()">

<center>
<table width=720 cellpadding=0 cellspacing=0 border=0>
<tr><td width=720 bgcolor=#c80000><img src=../images/trans.gif width=720 height=6 border=0></td></tr>
<tr><td width=720 bgcolor=#363636><img src=../images/trans.gif width=720 height=12 border=0></td></tr>
<tr><td width=720><img src=../images/trans.gif width=720 height=1 border=0></td></tr>
</table>
</center>

<h2>
<?php echo $Lang_pages->header_edit_page ?>: <?php echo $page["name_menu"] ?>
</h2>

<script>
function submit_form() {
    top.opener.document.page_form.content.value=editor._doc.body.innerHTML;
    top.opener.document.page_form.id.value=document.page_form.id.value;
    top.opener.document.page_form.mode.value=document.page_form.mode.value;
    top.opener.document.page_form.submit();    
    window.close();
    return false;
}
</script>

<!-- START CONTENT -->
<table width=720 cellpadding=0 cellspacin=0 border=0>
<tr><td bgcolor=#363636>
<table width=100% cellpadding=0 cellspacing=1 border=0>
<tr><td bgcolor=#ffffff>

<table width=100% cellpadding=4 cellspacing=2 border=0>
<form name=page_form>
<input type=hidden name=id value="<?php echo $page["id"] ?>">
<input type=hidden name=mode value="update_page">

<tr valign=top>
<td bgcolor=#f3f3f3>
<textarea id="content" name="content" style="width:100%; height:340px">
<?php echo $page["content"] ?>
</textarea>
</td>
</tr>
<tr valign=top>
<td bgcolor=#f3f3f3><input type=button onClick="return submit_form();" value="<?php echo $Lang_pages->button_save ?>" style="width:100%; border-width:1; border-color:#363636"></td>
</tr>

</form>
</table>
</td></tr>
</table>
</td></tr>
</table>
<!-- END CONTENT -->

</body>
</html>
