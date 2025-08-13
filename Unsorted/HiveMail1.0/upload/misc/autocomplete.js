//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: autocomplete.js,v $
// | $Date: 2002/10/28 18:19:08 $
// | $Revision: 1.5 $
// +-------------------------------------------------------------+

var validSelect = false;

// additional property for correcting wrong typing
contacts.names = new Array();
for(var i = 0; i < contacts.length; i++) {
	contacts.names[contacts[i].toUpperCase()] = contacts[i];
}

// function for splitting emails with delimiters [;,]
function splitString(str, arr) {
	var a = new Array();
	a = str.split(/\s*[;,]\s*/);
	for(var i = 0; i < a.length; i++) {
		if (arr.names[a[i].toUpperCase()]) {
			a[i] = arr.names[a[i].toUpperCase()];
		}
	}
	return a;
}

function autoComplete(field, arr) {
	if (!field.createTextRange) {
		return -1;
	}

	// shift
	if (event.keyCode == 16) {
		return -1;
	}

	if (!field.lastposition) {
		field.lastposition = -1;
	}
	var oldposition = field.lastposition;
	field.lastposition = -1;

	if (!field.oldvalue) {
		field.oldvalue = '';
	}

	// getting old emails list
	var old_parts = new Array();
	old_parts = splitString(field.oldvalue, arr);

	var oldvalue = field.oldvalue;
	field.oldvalue = field.value;

	// do not process if pressing [;,]
	if (event.keyCode == 188 || event.keyCode == 186) {
		if (oldposition == -1) {
			return -1;
		}
		if (oldposition == oldvalue.length && validSelect) {
			field.value = oldvalue + '; ';
			var rNew = field.createTextRange();
			rNew.moveStart('character', field.value.length);
			rNew.collapse();
			rNew.select();
		} else {
			field.value = oldvalue;
			var rNew = field.createTextRange();
			rNew.moveStart('character', oldposition + 1);
			rNew.collapse();
			rNew.moveEnd('character', 0) ;
			rNew.select();
			validSelect = true;
		}
		field.oldvalue = field.value;
		return 0;
	}
	validSelect = false;

	// getting new emails list
	var new_parts = new Array();
	new_parts = splitString(field.value, arr);

	// search for current edited email
	for (var bi = 0; bi < new_parts.length; bi++) {
		if (new_parts[bi] != old_parts[bi]) {
			break;
		}
	}
	var lengthdelta = new_parts.length - old_parts.length;
	for (var ei = new_parts.length - 1; ei > -1; ei--) {
		if (new_parts[ei] != old_parts[ei - lengthdelta]) {
			break;
		}
	}

	if (field.value.indexOf(oldvalue) == 0 && new_parts.length - 1 == old_parts.length) {
		bi = new_parts.length - 1;
		ei = new_parts.length - 1;
	}

	if (bi!=ei) {
		return -1;
	}

	// current edited email
	text_changed = new_parts[bi].toUpperCase();

	// search for completed field
	var found = false;
	text_found = '';
	if (text_changed.length > 0) {
		for (var i = 0; i < arr.length; i++) {
			if (arr[i].toUpperCase().indexOf(text_changed) == 0) {
				text_found = arr[i];
				found = true;
				break;
			}
		}
	}

	// formatting input field
	if (found && text_found.length != text_changed.length) {
		var cursorKeys = '8;46;37;38;39;40;33;34;35;36;45;';
		if (cursorKeys.indexOf(event.keyCode + ';') == -1) {
			var left_part = '';
			for (var i = 0; i < bi; i++) {
				left_part += new_parts[i] + '; ';
			}
			var right_part = '';
			for (var i = bi + 1; i < new_parts.length; i++) {
				right_part += '; ' + new_parts[i];
			}
			field.value = left_part + text_found + right_part;
			var rNew = field.createTextRange();
			rNew.moveStart('character', left_part.length + text_changed.length);
			rNew.collapse() ;
			rNew.moveEnd('character', text_found.length - text_changed.length);
			field.lastposition = left_part.length + text_found.length;
			rNew.select();
			validSelect = true;
		}
	}

	field.oldvalue = field.value;
	return 0;
}
