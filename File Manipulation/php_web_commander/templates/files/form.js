function makeToValues(as, escape_string) {
 
 	to_value = new Array();
 	j = 0;
 	for (var i=0; i<parent.curent_panel.document.forms['dir_content'].length; i++) {
 		if ((parent.curent_panel.document.forms['dir_content'].elements[i].name == 'dirs[]' || parent.curent_panel.document.forms['dir_content'].elements[i].name == 'files[]') && parent.curent_panel.document.forms['dir_content'].elements[i].type == 'checkbox' && parent.curent_panel.document.forms['dir_content'].elements[i].checked) {

 			if (to_value[j-1] != escape_string) {
 				
 				while ( (to_value[j] == undefined || to_value[j] == '' || to_value[0] == escape_string ) ) {
 					
 					to_value[j] = prompt(as + "\n" + parent.curent_panel.document.forms['dir_content'].elements[i].value + " to: ", "");
 				}
 				j++;
 			}
 		}
 	}

	parent.curent_panel.document.getElementById('dir_content').to_values.value = to_value;
}

function getQueryVariable(variable, fr) {

	if (fr == 'other') {
		query = parent.other_panel.location.search.substring(1);
	} else {
		query = parent.curent_panel.location.search.substring(1);
	}
	var vars = query.split("&");
	for (var i=0;i<vars.length;i++) {
		var pair = vars[i].split("=");
		if (pair[0] == variable) {
			referral = pair[0];
			return pair[1];
		}
	}
}