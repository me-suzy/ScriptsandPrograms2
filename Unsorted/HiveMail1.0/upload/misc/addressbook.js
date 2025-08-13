//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: addressbook.js,v $
// | $Date: 2002/10/28 18:19:08 $
// | $Revision: 1.2 $
// +-------------------------------------------------------------+

function updateDisabled(theform, forwhat) {
	if (forwhat == 'adds') {
		if (theform.contacts.selectedIndex == -1) {
			theform.toto.disabled = theform.tocc.disabled = theform.tobcc.disabled = true;
		} else {
			theform.toto.disabled = theform.tocc.disabled = theform.tobcc.disabled = false;
		}
		theform.who.value = theform.contacts.options[theform.contacts.selectedIndex].text + ': ' + theform.contacts.options[theform.contacts.selectedIndex].value;
	} else {
		var thisField = eval('theform.'+forwhat);
		var deleteButton = eval('theform.delete'+forwhat);
		if (thisField.selectedIndex == -1) {
			deleteButton.disabled = true;
		} else {
			deleteButton.disabled = false;
		}
		theform.who.value = thisField.options[thisField.selectedIndex].text + ': ' + thisField.options[thisField.selectedIndex].value;
	}
}

function addto(theform, towhat) {
	if (theform.contacts.selectedIndex == -1) {
		return;
	}
	var thefield = eval('theform.'+towhat);
	selectedEmail = theform.contacts.options[theform.contacts.selectedIndex];
	thefield.options[thefield.options.length] = new Option(selectedEmail.text, selectedEmail.value);
}

function extractList(theform) {
	var fields = new Array('to', 'cc', 'bcc');

	for (var j = 0; j < 3; j++) {
		var thisField = eval('theform.'+fields[j]);
		var openerField = eval('window.opener.document.forms.composeform.'+fields[j]);

		openerField.value = '';
		for (var i = 0; i < thisField.options.length; i++) {
			if (i != 0) {
				openerField.value += '; ';
			} else {
				openerField.value = '';
			}
			if (thisField.options[i].text != thisField.options[i].value) {
				openerField.value += thisField.options[i].text+' <'+thisField.options[i].value+'>';
			} else {
				openerField.value += thisField.options[i].value;
			}
		}
	}

	window.close();
}