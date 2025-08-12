function ValidateForm(form, value) {
	var fileName = value;
	var regexp_file = /\.(csv|txt|dat)$/;
	var regexp_nofile = /\.[a-z]{1,3}$/;

	
	if (regexp_file.test(fileName)) {
		return true;
	} else if (regexp_nofile.test(fileName.toLowerCase())) {
		return false;
	} else {
		switch(fileName) {
			case "" :
				return false;
			break;
			
			default :
				form.stage.value = "";
				return true;
		}
	}
}