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
// | $RCSfile: addressbook.js,v $ - $Revision: 1.19 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

function clearWhoList(thefield) {
	while (thefield.options.length > 0) {
		thefield.options[0] = null;
	}
}

function populateWhoList(groupID, contactNum, thefield) {
	var cEmails = eval('group'+groupID+'emails');
	clearWhoList(thefield);
	for (var i = 0; i < cEmails[contactNum].length; i++) {
		thefield.options[thefield.options.length] = new Option(cEmails[contactNum][i], cEmails[contactNum][i]);
	}
}

function selectAll(thefield) {
	var allChecked = true;
	for (var i = 0; i < thefield.options.length; i++) {
		allChecked = (allChecked && thefield.options[i].selected);
	}
	for (var i = 0; i < thefield.options.length; i++) {
		thefield.options[i].selected = (!allChecked && thefield.options[i].value != '');
	}
	updateDisabled(thefield.form, 'adds');
}

function findContact(input, thefield) {
	if (input.value.length == 0) {
		thefield.selectedIndex = -1;
		updateDisabled(thefield.form, 'adds');
		return;
	}
	var notdone = true;
	var found = false;
	for (var i = 0; i < thefield.options.length; i++) {
		if (notdone && thefield.options[i].text.toUpperCase().substring(0, input.value.length) == input.value.toUpperCase()) {
			thefield.options[i].selected = true;
			found = true;
		} else {
			if (found) {
				notdone = false;
			}
			thefield.options[i].selected = false;
		}
	}
	updateDisabled(thefield.form, 'adds');
}

function updateList(groupID, theform) {
	var cNames = eval('group'+groupID+'names');
	var cEmails = eval('group'+groupID+'emails');
	var thefield = theform.contacts;

	thefield.selectedIndex = -1;
	while (thefield.options.length > 0) {
		thefield.options[0] = null;
	}
	for (var i = 0; i < cNames.length; i++) {
		thefield.options[i] = new Option(cNames[i], i);
	}
	updateDisabled(theform, 'adds');
}

function updateDisabled(theform, forwhat) {
	if (forwhat == 'adds') {
		if (theform.contacts.selectedIndex == -1) {
			theform.toto.disabled = true;
			if (!oneListOnly) {
				theform.tocc.disabled = theform.tobcc.disabled = true;
			}
			clearWhoList(theform.who);
		} else {
			theform.toto.disabled = false;
			if (!oneListOnly) {
				theform.tocc.disabled = theform.tobcc.disabled = false;
			}

			populateWhoList(theform.group.options[theform.group.selectedIndex].value, theform.contacts.options[theform.contacts.selectedIndex].value, theform.who);
			for (var i = 0; i < theform.contacts.options.length; i++) {
				if (theform.contacts.options[i].selected && i != theform.contacts.selectedIndex) {
					// We have more tha once contact selected
					clearWhoList(theform.who);
					break;
				}
			}
		}
		theform.to.selectedIndex = -1;
		theform.deleteto.disabled = true;
		if (!oneListOnly) {
			theform.cc.selectedIndex = theform.bcc.selectedIndex = -1;
			theform.deletecc.disabled = theform.deletebcc.disabled = true;
		}
	} else {
		var thisField = eval('theform.'+forwhat);
		var deleteButton = eval('theform.delete'+forwhat);
		if (thisField.selectedIndex == -1) {
			deleteButton.disabled = true;
		} else {
			deleteButton.disabled = false;
		}
		theform.contacts.selectedIndex = -1;
		theform.toto.disabled = true;
		if (!oneListOnly) {
			theform.tocc.disabled = theform.tobcc.disabled = true;
		}
		if (forwhat != 'to') {
			theform.to.selectedIndex = -1;
			theform.deleteto.disabled = true;
		}
		if (forwhat != 'cc' && !oneListOnly) {
			theform.cc.selectedIndex = -1;
			theform.deletecc.disabled = true;
		}
		if (forwhat != 'bcc' && !oneListOnly) {
			theform.bcc.selectedIndex = -1;
			theform.deletebcc.disabled = true;
		}
		clearWhoList(theform.who);
		if (thisField.selectedIndex != -1) {
			theform.who.options[0] = new Option(thisField.options[thisField.selectedIndex].value, thisField.options[thisField.selectedIndex].value);
			for (var i = 0; i < thisField.options.length; i++) {
				if (thisField.options[i].selected && i != thisField.selectedIndex) {
					// We have more tha once contact selected
					clearWhoList(theform.who);
					break;
				}
			}
		}
	}
}

function addto(theform, towhat) {
	if (theform.contacts.selectedIndex == -1) {
		return;
	}
	var skip = false;
	var thefield = eval('theform.'+towhat);
	var cEmails = eval('group'+theform.group.options[theform.group.selectedIndex].value+'emails');

	for (var j = 0; j < theform.contacts.options.length; j++) {
		if (!theform.contacts.options[j].selected) {
			continue;
		}
		selectedEmail = theform.contacts.options[j];
		if (theform.who.options.length > 0) {
			newEmail = theform.who.options[theform.who.selectedIndex].value;
		} else {
			newEmail = cEmails[selectedEmail.value][0];
		}

		// Make sure the address isn't there already
		skip = false;
		for (var i = 0; i < thefield.options.length; i++) {
			if (newEmail == thefield.options[i].value) {
				skip = true;
				break;
			}
		}

		if (!skip && selectedEmail.value != '') {
			if (theform.who.options.length > 0) {
				thefield.options[thefield.options.length] = new Option(selectedEmail.text, newEmail);
			} else {
				thefield.options[thefield.options.length] = new Option(selectedEmail.text, newEmail);
			}
		}
		theform.contacts.options[j].selected = false;
	}

	theform.toto.disabled = true;
	if (!oneListOnly) {
		theform.tocc.disabled = theform.tobcc.disabled = true;
	}
	clearWhoList(theform.who);
}

function deleteAdds(theform, fieldname) {
	var thefield = eval('theform.'+fieldname);
	if (thefield.selectedIndex == -1) {
		return;
	}

	for (var j = 0; j < thefield.options.length; j++) {
		if (thefield.options[j].selected) {
			thefield.options[j] = null;
			j--;
		}
	}
	thefield.selectedIndex = -1;
	eval('theform.delete'+fieldname).disabled = true;
	clearWhoList(theform.who);
}

function extractList(theform) {
	var noclose = 0;
	if (!oneListOnly) {
		var fields = new Array('to', 'cc', 'bcc');
	} else {
		var fields = new Array('to');
	}

	for (var j = 0; j < fields.length; j++) {
		var thisField = eval('theform.'+fields[j]);
		if (!oneListOnly) {
			var openerField = eval('window.opener.document.forms.composeform.'+fields[j]);
		} else if (local == 1) {
			var openerField = window.opener.document.forms.eventform.eventlistaddresses;
		} else {
			var openerField = window.opener.document.forms.eventform.addresses;
		}

		openerField.value = '';
		for (var i = 0; i < thisField.options.length; i++) {
			if (i != 0) {
				openerField.value += '; ';
			} else {
				openerField.value = '';
			}
			if (thisField.options[i].text != thisField.options[i].value && thisField.options[i].value != '') {
				openerField.value += thisField.options[i].text+' <'+thisField.options[i].value+'>';
			} else if (thisField.options[i].value == '') {
				openerField.value += thisField.options[i].text;
			} else {
				openerField.value += thisField.options[i].value;
			}
		}
	}

	if (cmd != '' && newevent != 1) {
		if (openerField.value == '' && frompage == 'event') {
			alert('You must select at least one user to share this event with, or set it as a Normal event.');
			noclose = 1;
		} else if (openerField.value == '' && frompage == 'fwd') {
			alert('You must select at least one user to forward this event to, or press Cancel to return to the event.');
		} else {
			window.opener.document.forms.eventform.cmd.value = cmd;
			window.opener.document.forms.eventform.submit();
		}
	} else if (newevent == 1) {
		alert('Your changes to the Shared Event Userlist have been saved. They will not be reflected on the event screen until you save it for the first time.');
	}
	
	if (noclose != 1) {
		window.close();
	}
}