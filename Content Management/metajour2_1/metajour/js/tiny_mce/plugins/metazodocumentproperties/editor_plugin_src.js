/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('metazodocumentproperties', 'en,da');

/**
 * Returns the HTML contents of the save control.
 */
function TinyMCE_metazodocumentproperties_getControlHTML(control_name) {
	switch (control_name) {
		case "metazodocumentproperties":
			return '<img id="{$editor_id}_metazodocumentproperties" src="{$pluginurl}/images/metazodocumentproperties.gif" title="{$lang_metazodocumentproperties_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.switchClass(this,\'mceButtonNormal\');" onmousedown="tinyMCE.switchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceMetazoDocumentProperties\');" />';
	}
	return "";
}

/**
 * Executes the save command.
 */
function TinyMCE_metazodocumentproperties_execCommand(editor_id, element, command, user_interface, value) {
	// Handle commands
	switch (command) {
		case "mceMetazoDocumentProperties":
			getDocumentPropertiesHtml();

			return true;
	}
	// Pass to next handler in chain
	return false;
}