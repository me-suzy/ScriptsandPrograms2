<?php
// Name: TinyMCE editor extension.
// Version: 0.1
// Author: Pivot Development Team
// License: GPL 2.0

// This is an extension that replaces the built-in wysywig editor with TinyMCE
// editors. See tinmce.moxiecode.com for examples. 
//
// If you want to create another editor replacement, this is how it globally works:
// When the 'edit entry' screen is opened, the pre_editor_wysi.php hook file will 
// be included, if it is present. 
// pre_editor_wysi_init() will be called once, at the start of the execution.
// pre_editor_wysi_area() is called twice, for each of the textareas. name and
//   content are passed as parameters.
//
// If you create your own editor, you'll have to make sure you write javascript 
// functions to handle the popup windows for image, popup, and downloads. See 
// these functions, below:
// function doImage(.. 			// handles callback from image window..
// function doPopupImage(..		// handles callback from imagepopup window.
// function doDownload(..		// handles callback from download window.
// function getSel(..			// gets current selection from the editor
// 
// to open the windows, you need to assign the functionality to the 
// editors menu bar.. For tinyMCE, this looks like:
// (from editor_wysi/themes/pivot/editor_template.js)
//
//		['pivot_image', 'pivot_image.gif', 'Image', 'mcePivotImage', false, ''],
//		['pivot_popup', 'pivot_popup.gif', 'Popup', 'mcePivotPopup', false, ''],
//		['pivot_download', 'pivot_download.gif', 'Download', 'mcePivotDownload', false, '']
//
// (..)
//
//		case "mcePivotImage":
//			
//			openImageWindow('');	
//			return true;
//
//		case "mcePivotPopup":
//			
//			openImagePopupWindow('');	
//			return true;
//			
//		case "mcePivotDownload":
//			
//			openDownloadWindow('');	
//			return true;
//
// As you can see, these are just wrappers for the functions that are defined 
// in the javascript that's part of pivot. Shouldn't be too hard to hack this 
// into other editors..
//
//



/**
 * This will be executed once, on load of the 'entry edit' page..
 *
 */
function pre_editor_wysi_init() {
	global $Paths;

	echo <<< EOM

	<!-- tinyMCE -->
	<script language="javascript" type="text/javascript" src="{$Paths['extensions_url']}hooks/editor_wysi/tiny_mce_gzip.php"></script>
	<script language="javascript" type="text/javascript">
		tinyMCE.init({
			theme : "advanced",
			//language : "de",
			mode : "exact",
			elements : "f_introduction_text,f_body_text",
			//save_callback : "customSave",
			content_css : "{$Paths['extensions_url']}hooks/editor_wysi/example_advanced.css",
			insertimage_callback : "customInsertImage",
			insertpivot_image_callback : "customPivotImage",
			//extended_valid_elements : "a[href|target|name]",
			extended_valid_elements : "img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name]",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_path_location : "bottom",
			//plugins : "emotions",
			//theme_pivot_buttons1_add : "emotions",
			theme_advanced_resizing : true,
			theme_advanced_resize_horizontal : false,
			debug : false
		});
	
	// This function gets called after placing an image..
	function doImage(image_name, image_alt, image_align, image_border, name) {	
	
		document.form1.f_image.value= image_name;
	
		text =	'[[image:'+image_name+':'+image_alt+':'+image_align+':'+image_border+']]'; 
		
		alert('text: '+text);
		
		tinyMCE.execCommand('mceInsertContent',false,text);
		
	}
	
	// This function gets called after inserting a popupimage..
	function doPopupImage(image_name, image_alt, image_align, f_popup_descr, image_border, name) {	
			
		document.form1.f_image.value= image_name;
		document.form1.f_hasthumb.value = f_popup_descr;
	
		text =	'[[popup:'+image_name+':'+f_popup_descr+':'+image_alt+':'+image_align+':'+image_border+']]';	
		
		tinyMCE.execCommand('mceInsertContent',false,text);
		
	}
	
	// This function gets called after inserting a download..
	function doDownload(file_name, f_icon, f_text, f_title, name) {	
	
		if (f_icon == 'icon') { f_text = ''; }
		text =	'[[download:'+file_name+':'+f_icon+':'+f_text+':'+f_title+']]';
		
		tinyMCE.execCommand('mceInsertContent',false,text);

}		

	// Function getSel must be defined to get the current selection..
	function getSel() {
		return tinyMCE.selectedInstance.getSelectedText();
	}
		
	</script>
	<!-- /tinyMCE -->
	
EOM;
		
}

/**
 * This will be executed twice. Once for each of the text-areas. 
 * It outputs the HTML code that renders the editors. This will be executed
 * _instead_ of the code that normally inserts the editor.
 *
 * @param string $name
 * @param string $content
 *
 */
function pre_editor_wysi($name, $content) {

	printf("<div style='margin:6px 0px;'><textarea name='%s' style='width:100%%;' rows='12'>%s</textarea></div>", $name, $content);
}


?>