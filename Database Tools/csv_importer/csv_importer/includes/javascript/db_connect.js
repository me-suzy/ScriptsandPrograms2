function ValidateForm(form) {
	var e;
	var i;
	var valid = true;
	
	for (i = 0; i < form.length; i++) {
		e = form[i];
		
		if ((e.type == "text") && (e.value == "")) {
			alert("The server name and username must be entered.");
			valid = false;
			break;
		}
	}
	
	return valid;
}