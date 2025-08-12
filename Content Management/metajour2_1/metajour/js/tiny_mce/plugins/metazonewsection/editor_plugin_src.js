/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('metazonewsection', 'en');

/**
 * Returns the HTML contents of the save control.
 */
function TinyMCE_metazonewsection_getControlHTML(control_name) {
	switch (control_name) {
		case "metazonewsection":
			return '<img id="{$editor_id}_metazonewsection" src="{$pluginurl}/images/metazonewsection.gif" title="{$lang_metazonewsection_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.switchClass(this,\'mceButtonNormal\');" onmousedown="tinyMCE.switchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceMetazoNewSection\');" />';
	}
	return "";
}

/**
 * Executes the save command.
 */
function TinyMCE_metazonewsection_execCommand(editor_id, element, command, user_interface, value) {
	// Handle commands
	switch (command) {
		case "mceMetazoNewSection":
			var formObj = tinyMCE.selectedInstance.formElement.form;

			if (formObj) {
				tinyMCE.triggerSave();

				// Disable all UI form elements that TinyMCE created
				for (var i=0; i<formObj.elements.length; i++) {
					var elementId = formObj.elements[i].name ? formObj.elements[i].name : formObj.elements[i].id;

					if (elementId.indexOf('mce_editor_') == 0)
						formObj.elements[i].disabled = true;
				}

				formObj.cmd.value = formObj.cmd.value + ',createblank';
				tinyMCE.selectedInstance.formElement.form.submit();
			} else
				alert("Error: No form element found.");

			return true;
	}
	// Pass to next handler in chain
	return false;
}