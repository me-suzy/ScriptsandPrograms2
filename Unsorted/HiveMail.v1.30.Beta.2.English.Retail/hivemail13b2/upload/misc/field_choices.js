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
// | $RCSfile: field_choices.js,v $ - $Revision: 1.5 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

// This is the text that's added to default choices (don't forget leading space)
var defstr = ' (default)';

function in_array(thearray, match) {
	for (var i = 0; i < thearray.length; i++) {
		if (thearray[i] == match) {
			return i;
		}
	}
	return false;
}

function saveDate(thefield) {
	var data = '';
	for (var i = 0; i < thefield.options.length; i++) {
		data += thefield.options[i].value+' ';
		if (in_array(defaults, thefield.options[i].value)) {
			data += '1 '+thefield.options[i].text.substr(0, thefield.options[i].text.length - defstr.length);
		} else {
			data += '0 '+thefield.options[i].text;
		}
		data += "\n";
	}
	thefield.form.field_data.value = data;
	return true;
}

function makeDefault(theform) {
	if (onlyOneDef) {
		for (var i = 0; i < theform.choices.options.length; i++) {
			if (theform.choices.options[i].style.color != 'black' && i != theform.choices.selectedIndex) {
				theform.choices.options[i].style.color = 'black';
				if (in_array(defaults, theform.choices.options[i].value)) {
					theform.choices.options[i].text = theform.choices.options[i].text.substr(0, theform.choices.options[i].text.length - defstr.length);
				}
			}
		}
		for (var i = 0; i < defaults.length; i++) {
			if (!in_array(defaults, theform.choices.options[theform.choices.selectedIndex].value)) {
				defaults[i] = null;
			}
		}
	}

	if (matchkey = in_array(defaults, theform.choices.options[theform.choices.selectedIndex].value)) {
		defaults[matchkey] = null;
		theform.choices.options[theform.choices.selectedIndex].style.color = 'black';
		theform.choices.options[theform.choices.selectedIndex].text = theform.choices.options[theform.choices.selectedIndex].text.substr(0, theform.choices.options[theform.choices.selectedIndex].text.length - defstr.length);
	} else {
		defaults[defaults.length] = theform.choices.options[theform.choices.selectedIndex].value;
		theform.choices.options[theform.choices.selectedIndex].style.color = 'red';
		theform.choices.options[theform.choices.selectedIndex].text += defstr;
	}

	theform.choices.selectedIndex = -1;
	updateDisabled(theform);
}

function moveUp(thefield) {
	selChoice = thefield.selectedIndex;
	tmpoption = thefield.options[selChoice];
	thefield.options[selChoice] = new Option(thefield.options[selChoice-1].text, thefield.options[selChoice-1].value);
	thefield.options[selChoice-1] = tmpoption;

	if (in_array(defaults, thefield.options[selChoice].value)) {
		thefield.options[selChoice].style.color = 'red';
	}
	if (in_array(defaults, thefield.options[selChoice-1].value)) {
		thefield.options[selChoice-1].style.color = 'red';
	}

	updateDisabled(thefield.form);
}

function moveDown(thefield) {
	selChoice = thefield.selectedIndex;
	tmpoption = thefield.options[selChoice];
	thefield.options[selChoice] = new Option(thefield.options[selChoice+1].text, thefield.options[selChoice+1].value);
	thefield.options[selChoice+1] = tmpoption;

	if (in_array(defaults, thefield.options[selChoice].value)) {
		thefield.options[selChoice].style.color = 'red';
	}
	if (in_array(defaults, thefield.options[selChoice+1].value)) {
		thefield.options[selChoice+1].style.color = 'red';
	}

	updateDisabled(thefield.form);
}

function addChoice(theform) {
	name = prompt('Enter the new choice:', '');
	if (name == null || name == '') {
		return;
	}

	theform.choices.options[theform.choices.options.length] = new Option(name, bigID + 1);
	bigID++;
	while (theform.choices.options.length >= theform.choices.size) {
		theform.choices.size++;
	}
}

function removeChoice(theform) {
	if (confirm('Are you sure you want to remove this choice?\nIf you proceed, the choice of all users who\'ve\nselected this option will be deleted.')) {
		if (matchkey = in_array(defaults, theform.choices.options[theform.choices.selectedIndex].value)) {
			defaults[matchkey] = null;
		}
		theform.choices.options[theform.choices.selectedIndex] = null;
	}
}

function renameChoice(thefield) {
	choice = thefield.options[thefield.selectedIndex];

	if (in_array(defaults, thefield.options[thefield.selectedIndex].value)) {
		curname = choice.text.substr(0, choice.text.length - defstr.length);
	} else {
		curname = choice.text;
	}

	name = prompt('Enter the new name of this choice:', curname);
	if (name == curname || name == null) {
		return;
	}

	choice.text = name;
	if (in_array(defaults, thefield.options[thefield.selectedIndex].value)) {
		choice.text += defstr;
	}
}

function updateDisabled(theform) {
	theform.remove.disabled = theform.makedefault.disabled = theform.up.disabled = theform.down.disabled = theform.rename.disabled = (theform.choices.selectedIndex == -1);
	if (theform.choices.selectedIndex == -1) {
		return;
	}

	if (theform.choices.selectedIndex == 0) {
		theform.up.disabled = true;
	} else if (theform.choices.selectedIndex == (theform.choices.options.length - 1)) {
		theform.down.disabled = true;
	}

	if (in_array(defaults, theform.choices.options[theform.choices.selectedIndex].value)) {
		theform.makedefault.value = 'Make Non-Default';
	} else {
		theform.makedefault.value = 'Make Default';
	}
}