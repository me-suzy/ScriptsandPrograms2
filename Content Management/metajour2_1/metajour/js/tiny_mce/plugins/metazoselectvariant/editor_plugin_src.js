/**
 * Returns the HTML contents of the save control.
 */
function TinyMCE_metazoselectvariant_getControlHTML(control_name) {
	switch (control_name) {
		case "metazoselectvariant":
				return '<select style="width: 45px" id="{$editor_id}_metazoselectvariant" onchange="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceMetazoSelectVariant\',false,this.options[this.selectedIndex].value);" class="mceSelectList">' + getSelectVariantHtml() + '</select>';
	}
	return "";
}

/**
 * Executes the save command.
 */
function TinyMCE_metazoselectvariant_execCommand(editor_id, element, command, user_interface, value) {
	// Handle commands
	switch (command) {
		case "mceMetazoSelectVariant":
			var formObj = tinyMCE.selectedInstance.formElement.form;

			if (formObj) {
				tinyMCE.triggerSave();

				// Disable all UI form elements that TinyMCE created
				for (var i=0; i<formObj.elements.length; i++) {
					var elementId = formObj.elements[i].name ? formObj.elements[i].name : formObj.elements[i].id;

					if (elementId.indexOf('mce_editor_') == 0)
						formObj.elements[i].disabled = true;
				}

				formObj.view.value = 'changevariant';
				formObj.nextparam.value = value;

				tinyMCE.selectedInstance.formElement.form.submit();
			} else
				alert("Error: No form element found.");

			return true;
	}
	// Pass to next handler in chain
	return false;
}