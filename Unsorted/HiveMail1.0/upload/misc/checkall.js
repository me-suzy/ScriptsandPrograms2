//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: checkall.js,v $
// | $Date: 2002/10/28 18:19:08 $
// | $Revision: 1.8 $
// +-------------------------------------------------------------+

var mainChecked = false;
var totalChecked = 0;
var lastChecked;
var selectStart;

function checkAll(form, name) {
	var element = 0;
	for (var checkbox = form.elements[element]; element < form.elements.length; checkbox = form.elements[element++]) {
		if (checkbox.name != 'allbox' && (!name || (name && checkbox.name.substr(0, name.length) == name)) && checkbox.type == 'checkbox') {
			if (checkbox.checked && mainChecked) {
				totalChecked--;
			} else if (!checkbox.checked && !mainChecked) {
				totalChecked++;
			}
			checkbox.checked = !mainChecked;
		}
	}
	mainChecked = !mainChecked;
}

function checkMain(form, name) {
	mainChecked = true;
	for (var element = 0; element < form.elements.length && mainChecked == true; element++) {
		var checkbox = document.form.elements[element];
		if (checkbox.name != 'allbox' && (!name || (name && checkbox.name.substr(0, name.length) == name)) && checkbox.type == 'checkbox' && checkbox.checked == false) {
			mainChecked = false;
		}
	}
	form.allbox.checked = mainChecked;
}
