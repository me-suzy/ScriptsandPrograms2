function checkAll(form){
	for (i = 0, n = form.elements.length; i < n; i++) {
		if(form.elements[i].type == "checkbox") {
			if(form.elements[i].checked == true){
				form.elements[i].checked = false;
			}
			else{
				form.elements[i].checked = true;
			}
		}
	}
}
	
		
function ThumbPreviewPopup(page) {
	var winl = (screen.width-400)/2;
	var wint = (screen.height-400)/2;
	var settings  ='height='+'400'+',';
	settings +='width='+'410'+',';
	settings +='top='+wint+',';
	settings +='left='+winl+',';
	settings +='scrollbars=no,';
	settings +='location=no,';
	settings +='menubar=no,';
	settings +='toolbar=no,';
	settings +='resizable=yes';
	OpenWin = this.open(page, "Preview", settings);
} 

function focus_first_input() {
	fields = document.getElementsByTagName('input');
	if (fields.length > 0) {
		fields[0].focus();
	}
}
