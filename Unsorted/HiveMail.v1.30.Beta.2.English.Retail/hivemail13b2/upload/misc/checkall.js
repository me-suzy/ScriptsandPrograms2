// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// | $RCSfile: checkall.js,v $ - $Revision: 1.12 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

var mainChecked = false;
var totalChecked = 0;
var lastChecked;
var selectStart;

function checkAll(form, name) {
	for (var element = 0; element < form.elements.length; element++) {
		var checkbox = form.elements[element];
		if (checkbox.type == 'checkbox' && checkbox.name.substr(0, 6) != 'allbox' && (!name || (name && checkbox.name.substr(0, name.length) == name))) {
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

function checkMain(form, name, allboxname) {
	if (!allboxname) {
		allboxname = 'allbox';
	}
	mainChecked = true;
	for (var element = 0; element < form.elements.length && mainChecked == true; element++) {
		var checkbox = form.elements[element];
		if (checkbox.type == 'checkbox' && checkbox.name.substr(0, 6) != 'allbox' && (!name || (name && checkbox.name.substr(0, name.length) == name)) && checkbox.checked == false) {
			mainChecked = false;
		}
	}
	if (allboxname != 'allbox') {
		getElement(allboxname).checked = mainChecked;
	} else {
		form.allbox.checked = mainChecked;
	}
}