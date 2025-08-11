<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link rel="stylesheet" type="text/css" href="style.css" />

<title><?php echo $page_title; ?></title>

<?php 
// add links to admin and options pages.
if ($page_name == "su") {
	$edit_page = ": Administration-All Pages";
	$page_links = "<a href=\"admin.php\">Administration</a> :: <a href=\"options.php\">Options</a> :: <a href=\"index.php\">Editor</a> :: <a href=\"upload.php\">File Upload</a>";
} elseif (($page_name == "admin") && ($fileupload == "on")) {
	$edit_page = ": Administration";
	$page_links = "<a href=\"admin.php\">Administration</a> :: <a href=\"options.php\">Options</a> :: <a href=\"index.php\">Editor</a> :: <a href=\"upload.php\">File Upload</a>";
} elseif ($page_name == "admin") {
	$edit_page = ": Administration";
	$page_links = "<a href=\"admin.php\">Administration</a> :: <a href=\"options.php\">Options</a> :: <a href=\"index.php\">Editor</a>";
} elseif (($page_name == "options") && ($fileupload == "on")) {
	$edit_page = ": Options";
	$page_links = "<a href=\"admin.php\">Administration</a> :: <a href=\"options.php\">Options</a> :: <a href=\"index.php\">Editor</a> :: <a href=\"upload.php\">File Upload</a> :: <a href=\"info.php\">PHP Info</a>";
} elseif ($page_name == "options") {
	$edit_page = ": Options";
	$page_links = "<a href=\"admin.php\">Administration</a> :: <a href=\"options.php\">Options</a> :: <a href=\"index.php\">Editor</a> :: <a href=\"info.php\">PHP Info</a>";
} elseif ($page_name == "opt_redirect") {
	$edit_page = "";
	$page_links = "&nbsp;";
} elseif (($page_name == "index") && ($fileupload == "on")) {
	$edit_page = ": Editor";
	$page_links = "<a href=\"upload.php\">File Upload</a>";
} elseif ($page_name == "upload") {
	$edit_page = ": File Upload";
	$page_links = "<a href=\"index.php\">Editor</a>";
} elseif (($page_name == "setup") && ($fileupload == "on")) {
	$edit_page = ": Setup Utility";
	$page_links = "<a href=\"admin.php\">Administration</a> :: <a href=\"options.php\">Options</a> :: <a href=\"index.php\">Editor</a> :: <a href=\"upload.php\">File Upload</a>";
} elseif ($page_name == "setup") {
	$edit_page = ": Setup Utility";
	$page_links = "<a href=\"admin.php\">Administration</a> :: <a href=\"options.php\">Options</a> :: <a href=\"index.php\">Editor</a>";
} else {
	$edit_page = ": Editor";
	$page_links = "&nbsp;";
}
?>

<!-- TinyMCE -->
<script language="javascript" type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "specific_textareas",
		theme : "advanced",
		plugins : "advhr,advimage,advlink,contextmenu,emotions,flash,autosave,iespell,insertdatetime,paste,preview,zoom,flash,print,save,noneditable,searchreplace,table,zoom,directionality,fullscreen,inlinepopups,imanager",
		theme_advanced_buttons1_add_before : "save,print,separator,forecolor,backcolor,separator,",
		theme_advanced_buttons1_add : "fontselect,fontsizeselect",
		theme_advanced_buttons2_add_before: "cut,copy,paste,separator,search,replace,separator",
		theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,zoom,separator,liststyle,ltr,rtl",
		theme_advanced_buttons3_add_before : "tablecontrols,separator",
		theme_advanced_buttons3_add : "emotions,iespell,flash,advhr,pastetext,pasteword,selectall,preview,zoom,separator,fullscreen,imanager",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		content_css : "example_full.css",
		plugin_insertdate_dateFormat : "%Y-%m-%d",
		plugin_insertdate_timeFormat : "%H:%M:%S",
		extended_valid_elements : "hr[class|width|size|noshade],a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],font[face|size|color|style],span[class|align|style]",
		paste_create_paragraphs : false,
		paste_use_dialog : true,
		paste_auto_cleanup_on_paste : true,
		paste_convert_middot_lists : false,
		paste_unindented_list_class : "unindentedList",
		paste_convert_headers_to_strong : true,
		plugin_preview_width : "500",
		plugin_preview_height : "600",
		table_styles : "Header 1=header1;Header 2=header2;Header 3=header3",
		table_cell_styles : "Header 1=header1;Header 2=header2;Header 3=header3;Table Cell=tableCel1",
		table_row_styles : "Header 1=header1;Header 2=header2;Header 3=header3;Table Row=tableRow1"
	});


</script>
<!-- /TinyMCE -->


</head>

<body>

<table class="main" cellpadding="0" cellspacing="0" border="0">
<tr>
<td colspan="2">
<h1><?php echo "$page_title $edit_page"; ?></h1>
</td>
</tr>
<tr>
<td class="bar" colspan="2">
<?php echo $page_links; ?>
</td>
</tr>

<tr>
<td colspan="2">