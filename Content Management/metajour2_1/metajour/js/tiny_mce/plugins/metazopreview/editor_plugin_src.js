/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('metazopreview', 'en');

/**
 * Returns the HTML contents of the save control.
 */
function TinyMCE_metazopreview_getControlHTML(control_name) {
	switch (control_name) {
		case "metazopreview":
			return '<img id="{$editor_id}_metazopreview" src="{$pluginurl}/images/metazopreview.gif" title="{$lang_metazopreview_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.switchClass(this,\'mceButtonNormal\');" onmousedown="tinyMCE.switchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceMetazoPreview\');" />';
	}
	return "";
}

/**
 * Executes the save command.
 */
function TinyMCE_metazopreview_execCommand(editor_id, element, command, user_interface, value) {
	// Handle commands
	switch (command) {
		case "mceMetazoPreview":
			getPreviewHtml();

			return true;
	}
	// Pass to next handler in chain
	return false;
}