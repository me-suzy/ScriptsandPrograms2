/* Import plugin specific language pack */
tinyMCE.importPluginLanguagePack('metazodeletesection', 'en');

/**
 * Returns the HTML contents of the save control.
 */
function TinyMCE_metazodeletesection_getControlHTML(control_name) {
	switch (control_name) {
		case "metazodeletesection":
			return '<img id="{$editor_id}_metazodeletesection" src="{$pluginurl}/images/metazodeletesection.gif" title="{$lang_metazodeletesection_desc}" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.switchClass(this,\'mceButtonNormal\');" onmousedown="tinyMCE.switchClass(this,\'mceButtonDown\');" onclick="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceMetazoDeleteSection\');" />';
	}
	return "";
}

/**
 * Executes the save command.
 */
function TinyMCE_metazodeletesection_execCommand(editor_id, element, command, user_interface, value) {
	// Handle commands
	switch (command) {
		case "mceMetazoDeleteSection":
			var formObj = tinyMCE.selectedInstance.formElement.form;

			if (formObj) {
				tinyMCE.triggerSave();

				// Disable all UI form elements that TinyMCE created
				for (var i=0; i<formObj.elements.length; i++) {
					var elementId = formObj.elements[i].name ? formObj.elements[i].name : formObj.elements[i].id;

					if (elementId.indexOf('mce_editor_') == 0)
						formObj.elements[i].disabled = true;
				}

				var confirmstring = "Er du sikker på at du vil slette denne sektion/sektions-variant ?";
				if (confirm(confirmstring)) {
					formObj.cmd.value = 'delete';
					tinyMCE.selectedInstance.formElement.form.submit();
				}
			} else
				alert("Error: No form element found.");

			return true;
	}
	// Pass to next handler in chain
	return false;
}