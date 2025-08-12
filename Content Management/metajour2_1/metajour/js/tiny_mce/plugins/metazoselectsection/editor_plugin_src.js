/**
 * Returns the HTML contents of the save control.
 */
function TinyMCE_metazoselectsection_getControlHTML(control_name) {
	switch (control_name) {
		case "metazoselectsection":
				return '<select style="width: 237px" id="{$editor_id}_metazoselectsection" onchange="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceMetazoSelectSection\',false,this.options[this.selectedIndex].value);" class="mceSelectList">' + getSelectSectionHtml() + '</select>';
	}
	return "";
}

/**
 * Executes the save command.
 */
function TinyMCE_metazoselectsection_execCommand(editor_id, element, command, user_interface, value) {
	// Handle commands
	switch (command) {
		case "mceMetazoSelectSection":
			var formObj = tinyMCE.selectedInstance.formElement.form;

			if (formObj) {
				tinyMCE.triggerSave();

				// Disable all UI form elements that TinyMCE created
				for (var i=0; i<formObj.elements.length; i++) {
					var elementId = formObj.elements[i].name ? formObj.elements[i].name : formObj.elements[i].id;

					if (elementId.indexOf('mce_editor_') == 0)
						formObj.elements[i].disabled = true;
				}

				formObj.view.value = 'changesection';
				formObj.nextparam.value = value;

				tinyMCE.selectedInstance.formElement.form.submit();
			} else
				alert("Error: No form element found.");

			return true;
	}
	// Pass to next handler in chain
	return false;
}