function ChangeHeaderClass(field) {
	var row = document.getElementById("headerRow");
	
	row.className = (field.checked == true) ? "header" : "";
	document.form1.useFRAH.value = (field.checked == true) ? "on" : "";
}

function ValidateForm() {
}