/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('metazosectionproperties', 'en,da');

/**
 * Returns the HTML contents of the save control.
 */
function TinyMCE_metazosectionproperties_getControlHTML(control_name) {
	switch (control_name) {
		case "metazosectionproperties":
			return '<img id="{$editor_id}_metazosectionproperties" src="{$pluginurl}/images/metazosectionproperties.gif" title="{$lang_metazosectionproperties_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.switchClass(this,\'mceButtonNormal\');" onmousedown="tinyMCE.switchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceMetazoSectionProperties\');" />';
	}
	return "";
}

/**
 * Executes the save command.
 */
function TinyMCE_metazosectionproperties_execCommand(editor_id, element, command, user_interface, value) {
	// Handle commands
	switch (command) {
		case "mceMetazoSectionProperties":
			getSectionPropertiesHtml();

			return true;
	}
	// Pass to next handler in chain
	return false;
}