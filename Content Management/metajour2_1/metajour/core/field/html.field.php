<?php
/**
 * @author Jan H. Andersen <jha@ipwsystems.dk>
 * @author Martin R. Larsen <mrl@ipwsystems.dk>
 * @copyright {@link http://www.ipwsystems.dk/ IPW Systems a.s}
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package METAjour
 * @subpackage field
 */

require_once('field.php');

class htmlfield extends field {
	
	function formOut() {
		$system_url = $this->userhandler->getSystemUrl();
		static $htmljsadded = false;
		if (!$htmljsadded) {
			$htmljsadded = true;
			$this->view->context->addHeader('<!-- tinyMCE -->
<script language="javascript" type="text/javascript" src="js/tiny_mce/tiny_mce_src.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "specific_textareas",
		theme : "advanced",
		language : "da",
		plugins : "table,save,advimage,advlink,zoom,flash,searchreplace,print,contextmenu,fullscreen",
		theme_advanced_buttons1_add : "zoom,forecolor,backcolor,separator,tablecontrols",
		theme_advanced_buttons2_add : "flash,fullscreen",
		theme_advanced_buttons2_add_before: "save,print,separator,cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_disable : "formatselect,help",
	  plugin_insertdate_dateFormat : "%Y-%m-%d",
	  plugin_insertdate_timeFormat : "%H:%M:%S",
		extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
		content_css : "'.$this->userhandler->getSystemUrl().'getstylesheet.php?objectid=0",
		file_browser_callback : "fileBrowserCallBack"
	});

	function fileBrowserCallBack(field_name, url, type) {
		var src='.$this->view->ListDialog('binfile','','','initdialog').';
		var win=tinyMCE.getWindowArg("window");
		win.document.getElementById(field_name).value="getfile.php?objectid="+src.id; 
	}
</script>
<!-- /tinyMCE -->
'); #'
		}
		$res = '<textarea mce_editable=true rows="15" cols="80" style="width: 100%" id="'.$this->_fieldname.'" name="'.$this->_fieldname.'">'.$this->_fieldvalue.'</textarea>';
		return $res;
	}
	
}

?>