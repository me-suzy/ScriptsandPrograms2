function DbFieldOnChange(form, field, index) {
	var d = document;
	var div;
	var freeVar;
	
	freeVar = "dbFieldDiv[" + index + "]";
	div = d.getElementById(freeVar);
	
	while (div.childNodes.length > 0) {
		for (var i = 0; i < div.childNodes.length; i++) {
			div.removeChild(div.childNodes[0]);
		}
	}
	
	if (field.value == "[setvalue]") {
		freeVar = d.createTextNode("Enter set value:  ");
		div.appendChild(freeVar);

		freeVar = _input.cloneNode(false);
			freeVar.name = "setValue" + index;
			freeVar.size = "15";
			freeVar.type = "text";
		div.appendChild(freeVar);
		freeVar.focus();
	}
	
	eval("form.colConfig" + index + ".value = " + (field.selectedIndex-3));
}

function Sequence(form) {
	var counter = 0;
	var e;
	var i;
	var seqIdx = 3;
	var regexp_field = /^dbFieldName/;
	
	for (i = 0; i < form.length; i++) {
		e = form[i];
		if (regexp_field.test(e.name)) {
			e.selectedIndex = seqIdx;
			seqIdx++;	
			
			DbFieldOnChange(form, e, counter);

			counter++;
		}
	}
}


function SetPrimaryKey(field, div) {
	var workarea = document.getElementById(div);

	WhackDiv(workarea);
			
	switch (field.value) {
		case "" :
		case "insertonly" :
		break;
		
		case "newfield" :
			var textbox = _input.cloneNode(false);
				textbox.border = "1px solid black";
				textbox.name = "txtNewField";
				textbox.type = "text";
				textbox.value = "id";
			
			var text = document.createTextNode("Name of new field: ");
			
			workarea.appendChild(text);
			workarea.appendChild(textbox);
		break;
		
		default :
			var freeVar;
			
			freeVar = doc.getElementById("primaryKeyField");
			freeVar.value = field.value;
			
			freeVar = doc.getElementById("primaryKeyIndex");
			freeVar.value = (field.selectedIndex-3);
			
			var checkbox = _input.cloneNode(false);
				checkbox.border = "1px solid black";
				checkbox.name = "chkSetPrimaryKey";
				checkbox.type = "checkbox";
			
			var text = document.createTextNode(" Permanently mark field as primary key in table.");
			
			workarea.appendChild(checkbox);
			workarea.appendChild(text);
	}
	
	field.blur();
}