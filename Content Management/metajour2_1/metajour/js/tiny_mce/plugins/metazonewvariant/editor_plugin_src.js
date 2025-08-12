/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('metazonewvariant', 'en');

/**
 * Returns the HTML contents of the save control.
 */
function TinyMCE_metazonewvariant_getControlHTML(control_name) {
	switch (control_name) {
		case "metazonewvariant":
			return '<img id="{$editor_id}_metazonewvariant" src="{$pluginurl}/images/metazonewvariant.gif" title="{$lang_metazonewvariant_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.switchClass(this,\'mceButtonNormal\');" onmousedown="tinyMCE.switchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceMetazoNewVariant\');" />';
	}
	return "";
}

/**
 * Executes the save command.
 */
function TinyMCE_metazonewvariant_execCommand(editor_id, element, command, user_interface, value) {
	// Handle commands
	switch (command) {
		case "mceMetazoNewVariant":
			getNewVariantHtml();
			return true;
	}
	// Pass to next handler in chain
	return false;
}