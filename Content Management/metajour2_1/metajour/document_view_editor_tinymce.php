<?php

/*************
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage view
 */
$stack = $this->userhandler->getObjectIdStack();
if (!empty($stack) && !is_null($stack[0])) $this->objectid = $stack;
$obj = owRead($this->objectid[0]);

if (!$obj && isset($this->data['_parentid'])) { 
	// this can occur, if we have just deleted the section
	// in this case, we just read the document (as set by _parentid)
	// this will be handled in the next if-statement, as if we
	// had just loaded the document from the document-list
	$obj = owRead($this->data['_parentid']);
	// we know what happened, so we remove the error from the
	// errorhandler
	$this->errorhandler->removeLastError();
}

if ($obj->gettype() == 'document') {
	/* if current document has a future revision
		and we're not opening in locked mode, load the future revision
		instead of the original document */
	
	if ($obj->hasFutureRevision() && !isset($_REQUEST['locked']) 
		&& $this->userhandler->getRevisionControl()) {
		$fr = $obj->getFutureRevisions();
		if (!empty($fr)) {
			$obj = owRead($fr[0]);
		}

	}

	$l = $obj->getchilds();
	$obj = owRead($l[0]);
	$this->objectid[0] = $l[0];
}

if ($obj->isVariant()) {
	# if the document section is a variant, the parentid is not set.
	# In this case we load the master object, and read the parentid
	$mobj = owRead($obj->getVariantOf());
	$parentid = $mobj->getParentId();
} else {
	$parentid = $obj->getparentid();
}

$extension = $obj->elements[0]['extension'];
$configset = $obj->elements[0]['configset'];
$name = $obj->elements[0]['name'];
$subname = $obj->elements[0]['subname'];
$params = $obj->elements[0]['params'];
$script = $obj->elements[0]['script'];
$document = owRead($parentid);
$approvestatus = -1;

if ($document && $this->userhandler->getRevisionControl()) {
	$approvestatus = $document->elements[0]['object']['approved'];
}


$newVariantJavascript = "
	<script language=\"javascript\" type=\"text/javascript\">
	function getNewVariantHtml() {
		".$this->ModelessDialog('',$this->objectid[0],'','createvariant','jstinymcesubmit,jswindowclose').";
	}
	</script>
	";
$this->context->addHeader($newVariantJavascript);

###### Create variant HTML for javascript plugin
	$js = '';
	$obj2 = new documentsection;
	$masterid = $this->getmasterid($this->objectid[0]);
	$obj2->readobject($masterid);
	$v = $obj2->getvariants();
	$count = 1;
	$js .= '<OPTION value="' . $masterid . '">'.$obj2->getlanguage().'</OPTION>';
	foreach ($v as $order) {
		$nyobj = owRead($order);
		if ($nyobj->getType() != 'documentsection') continue;
		$js .= '<OPTION value="'.$order . '"';
		if ($this->objectid[0] == $order ) $js .= ' SELECTED';
		$js .= '>'.$nyobj->getlanguage().'</OPTION>';
		$count++;
	}
		
$selectVariantJavascript = "
	<script language=\"javascript\" type=\"text/javascript\">
	function getSelectVariantHtml() {
		return '$js';
	}
	</script>
	";
$this->context->addHeader($selectVariantJavascript);
	######
	

###### Create section HTML for javascript plugin
$js = '';
$obj2 = owRead($this->objectid[0]);
$language = $obj2->getlanguage();
unset($obj2);
$obj2 = new documentsection;
$masterid = $this->getmasterid($this->objectid[0]);
$obj2->readobject($masterid);
$s = $obj2->getSiblings();
$count = 1;
foreach ($s as $order) {
	$nyobj = owRead($order);
	if ($nyobj->getType() == 'documentsection') {
		$v = $nyobj->getVariants($language);
		if ($v[0]) {
			$variant = new documentsection;
			$variant->readobject($v[0]);
			$name = $variant->getname();
			$oid = $variant->getobjectid();
			$lang = $variant->getlanguage();
			$active = $variant->isactive();
		} else {
			$name = $nyobj->getname();
			$oid = $order;
			$lang = $nyobj->getlanguage();
			$active = $nyobj->isactive();
		}
		$js .= '<OPTION value="'.$oid . '"';
		if (!$active) $js .= ' style="color: red;"';
		if ($this->objectid[0] == $oid ) $js .= ' SELECTED';
		$js .= '>['.$count."] ".$name.' (' . $lang . ')</OPTION>';
		$count++;
	}
}

if ($count > 2) {
	$multiplesection = true;
} else {
	$multiplesection = false;
}
	
$selectSectionJavascript = "
	<script language=\"javascript\" type=\"text/javascript\">
	function getSelectSectionHtml() {
		return '$js';
	}
	</script>
	";
$this->context->addHeader($selectSectionJavascript);
######

$previewJavascript = "
	<script language=\"javascript\" type=\"text/javascript\">
	function getPreviewHtml() {
		window.open('".$this->userhandler->getViewerUrl().'showpage.php?pageid=' . $parentid. "');
	}
	</script>
	";
$this->context->addHeader($previewJavascript);

$sectionPropJavascript = "
	<script language=\"javascript\" type=\"text/javascript\">
	function getSectionPropertiesHtml() {
		".$this->ModalWindowLarge('documentsection',$this->objectid[0],'','combidialog','jswindowclose').";
	}
	</script>
	";
$this->context->addHeader($sectionPropJavascript);

$documentPropJavascript = "
	<script language=\"javascript\" type=\"text/javascript\">
	function getDocumentPropertiesHtml() {
		".$this->ModalWindowLarge('document',$parentid,'','combidialog','jswindowclose').";
	}
	</script>
	";
$this->context->addHeader($documentPropJavascript);


$tobj = owNew('document');
$tobj->readobject($parentid);
if ($approvestatus == 0) {
	$approvebuttons = 'metazorequestapproval,';
} else if ($approvestatus == 2) {
	$approvebuttons = 'metazoapprovepublish,';
}

if ($tobj->isLocked() || $_REQUEST['locked']) {
	$plugins = 'metazoselectsection,metazoselectvariant,metazonewsection,metazonewsectionbelow,metazomovedownsection,metazomoveupsection,metazodeletesection,metazonewvariant,metazoapprovepublish,metazorequestapproval,metazopreview,metazosectionproperties,metazomenuspace,metazodocumentproperties';
	$theme_advanced_buttons1 = 'metazoselectvariant,metazonewvariant,separator,metazoselectsection,metazonewsection,metazonewsectionbelow,metazomovedownsection,metazomoveupsection,metazodeletesection,' . $approvebuttons . 'metazopreview,metazosectionproperties,metazodocumentproperties';
	$readonly = 'true';
} else {
	$plugins = 'paste,table,save,advimage,advlink,zoom,flash,searchreplace,print,contextmenu,,fullscreen,metazoselectsection,metazoselectvariant,metazonewsection,metazonewsectionbelow,metazomovedownsection,metazomoveupsection,metazodeletesection,metazonewvariant,metazoapprovepublish,metazorequestapproval,metazopreview,metazosectionproperties,metazomenuspace,metazodocumentproperties';
	$theme_advanced_buttons1 = '';
	if ($this->userhandler->getProfileEditor('editorstyle')) {
		if (!empty($theme_advanced_buttons1)) $theme_advanced_buttons1 .= ',';
		$theme_advanced_buttons1 .= 'bold,italic,underline,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,styleselect,sub,sup,separator,bullist,numlist,outdent,indent';
	}
	if ($this->userhandler->getProfileEditor('editorcolor')) {
		if (!empty($theme_advanced_buttons1)) $theme_advanced_buttons1 .= ',';
		$theme_advanced_buttons1 .= 'separator,forecolor,backcolor';
	}
	if ($this->userhandler->getProfileEditor('editortable')) {
		if (!empty($theme_advanced_buttons1)) $theme_advanced_buttons1 .= ',';
		$theme_advanced_buttons1 .= 'separator,tablecontrols';
	}
	$theme_advanced_buttons2 = 'save,print,separator,cut,copy,paste,pastetext,pasteword,selectall,removeformat,cleanup,separator,search,replace,separator,undo,redo,separator,code,hr,charmap';
	if ($this->userhandler->getProfileEditor('editorspecial')) {
		$theme_advanced_buttons2 .= ',separator,link,unlink,anchor,image,visualaid,flash';
	}
	$theme_advanced_buttons3 = 'metazoselectvariant,metazonewvariant,separator,metazoselectsection,metazonewsection,metazonewsectionbelow,metazomovedownsection,metazomoveupsection,metazodeletesection,' . $approvebuttons . 'metazopreview,metazosectionproperties,metazodocumentproperties';
	$readonly = 'false';
}
if ($tobj->elements[0]['stylesheetid']) {
	$stylesheetid = $tobj->elements[0]['stylesheetid'];
} else {
	$tmp = owNew('stylesheet');
	$stylesheetid = $tmp->locateDefault();
}

if ($stylesheetid) {
	$mapping = owNew('stylemapping');
	$mapping->setsort_col('orderby');
	$mapping->listObjects($stylesheetid);
	$mappings = array();
	foreach ($mapping->elements as $element) {
		if ($element['name']) $mappings[] = $element['name'] . "=" . $element['mapping']; 
	}
	$mappingstring = implode(';', $mappings);
}
$this->context->addHeader('<!-- tinyMCE -->
<script language="javascript" type="text/javascript" src="js/tiny_mce/tiny_mce_gzip.php"></script>
	<!-- <script language="javascript" type="text/javascript" src="js/tiny_mce/tiny_mce_src.js"></script> -->
	<script language="javascript" type="text/javascript">

	tinyMCE.init({
		mode : "specific_textareas",
		theme : "advanced",
		language : "' . strtolower($this->userhandler->getGuiLanguage()) .  '",
		plugins : "' . $plugins . '",
		theme_advanced_buttons1 : "' . $theme_advanced_buttons1 . '",
		theme_advanced_buttons2 : "' . $theme_advanced_buttons2 . '",
		theme_advanced_buttons3 : "' . $theme_advanced_buttons3 . '",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_disable : "formatselect,help",
		plugin_insertdate_dateFormat : "%Y-%m-%d",
		plugin_insertdate_timeFormat : "%H:%M:%S",
		editor_css : "js/tiny_mce/themes/advanced/editor_ui.css",
		content_css : "'.$this->userhandler->getSystemUrl().'getstylesheet.php?objectid='.$parentid.'",
		//extended_valid_elements : "img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
		theme_advanced_styles : "' . $mappingstring . '",
		width : "940",
		height : "630",
		file_browser_callback : "fileBrowserCallBack",
		auto_focus : "mce_editor_0",
		paste_auto_cleanup_on_paste : true,
		paste_convert_headers_to_strong : true,
		paste_strip_class_attributes : "mso",
		accessibility_warnings : false,
		readonly : "' . $readonly . '"
	});
	
	function setLinktext(type, win, name) {
		if (type == "image") {
			if (win.document.getElementById("alt"))
				win.document.getElementById("alt").value = name;
			else
				win.document.getElementById("title").value = name;
		} else if (type == "flash") {
		} else {
			win.document.getElementById("title").value = name;
		}
	}
	
	function fileBrowserCallBack(field_name, url, type, win) {
		if (type == "image" || type == "flash") {
			var src='.$this->ListDialog('binfile','','','initdialog', '', '', '', 'win').';
			var url = "getfile.php?objectid="+src.id;
		} else {
			var src='.$this->ListDialog('document','','','listdialog', '', '', '', 'win').';
			var url = "showpage.php?objectid="+src.id;
		}

		win.document.getElementById(field_name).value=url;
		setLinktext(type, win, src.name);
		win.focus();
	}
	
	
</script>
<!-- /tinyMCE -->
');
	
?>

<SCRIPT LANGUAGE="javascript">
document.title = '<?php echo $name . " [" . $document->elements[0]["name"] . "]" ?>';
</SCRIPT>
<form name="logform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="cmd" value="update">
<input type="hidden" name="view" value="editor">
<input type="hidden" name="nextparam" value="">
<INPUT type="hidden" name="objectid" value="<?php echo $this->objectid[0] ?>">
<INPUT type="hidden" name="_parentid" value="<?php echo $parentid ?>">
<INPUT type="hidden" name="content" value="">
<INPUT type="hidden" name="locked" value="<?php echo @$_REQUEST['locked']; ?>">
<?php
echo '<textarea mce_editable=true rows="15" cols="80" id="content" name="content">'.$obj->elements[0]['content'].'</textarea>';

?>
</form>
