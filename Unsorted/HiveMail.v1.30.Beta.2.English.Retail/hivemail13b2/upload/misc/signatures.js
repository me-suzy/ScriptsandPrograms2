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
// | $RCSfile: signatures.js,v $ - $Revision: 1.9 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

function updateSigDisplay(theform) {
	// Update current textarea data
	if (theform.cursig.value != 'sig0') {
		eval('theform.'+theform.cursig.value).value = getContent();
		eval('theform.'+theform.cursig.value+'_tagless').value = getContentTagLess();
	}

	// Replace the contents of the editor with the new signature
	if (theform.sigs.selectedIndex > -1) {
		theform.cursig.value = theform.sigs.options[theform.sigs.selectedIndex].value;
		setContent(eval('theform.'+theform.cursig.value).value);
	} else {
		// Nothing selected - display standard message
		setContent(defaultValue);
	}

	// Update the default, rename and delete buttons
	theform.makedef.disabled = theform.rename.disabled = theform.deletesig.disabled = true;
	if (theform.sigs.selectedIndex != -1) {
		if (theform.cursig.value != theform.defsig.value) {
			theform.makedef.disabled = theform.deletesig.disabled = false;
		} else if (totalSigs == 1) {
			theform.deletesig.disabled = false;
		}

		theform.rename.disabled = false;
	}
}

function updateDefaultSig(theform) {
	// Update color and text of options
	for (var i = 0; i < theform.sigs.options.length; i++) {
		if (theform.sigs.options[i].value == theform.cursig.value) {
			theform.sigs.options[i].text += defstr;
			theform.sigs.options[i].style.color = '#274EAD';
		} else if (theform.sigs.options[i].value == theform.defsig.value) {
			theform.sigs.options[i].text = theform.sigs.options[i].text.substr(0, theform.sigs.options[i].text.length - defstr.length);
			theform.sigs.options[i].style.color = 'black';
		}
	}

	// Update hidden field
	theform.defsig.value = theform.cursig.value;

	// Update the default and delete buttons
	theform.makedef.disabled = true;
	theform.deletesig.disabled = (totalSigs != 1);

	// Deselect the option
	theform.sigs.selectedIndex = -1;
}

function renameSig(theform, current) {
	// Remove (default)
	if (theform.sigs.options[theform.sigs.selectedIndex].value == theform.defsig.value) {
		current = current.substr(0, current.length - defstr.length);
	}

	var newtitle = window.prompt('Please enter a new title for "'+current+'":', current);
	if (newtitle == null) {
		return;
	}

	// Update real sig title
	theform.sigs.options[theform.sigs.selectedIndex].text = eval('theform.title'+theform.sigs.options[theform.sigs.selectedIndex].value.substr(3)).value = newtitle;
	
	// Add (default)
	if (theform.sigs.options[theform.sigs.selectedIndex].value == theform.defsig.value) {
		theform.sigs.options[theform.sigs.selectedIndex].text += defstr;
	}
}

function createNewSig(theform) {
	var newtitle = window.prompt('Please enter a title for the new '+workingWith+':', '');
	if (newtitle == null) {
		return false;
	}

	// Update hidden field
	theform.newsig.value = newtitle;

	// Create new option (for visual purpose only)
	theform.sigs.options[theform.sigs.options.length] = new Option(newtitle, 'foo');

	return true;
}

function deleteSig(theform) {
	// Verify
	if (theform.sigs.options[theform.sigs.selectedIndex].value == theform.defsig.value && totalSigs != 1) {
		alert('You cannot remove the default '+workingWith+'. Please select a different '+workingWith+' as default then try to delete it again.');
		return false;
	} else if (!confirm('Are you sure you want to delete this '+workingWith+'?')) {
		return false;
	}

	// Update hidden field
	theform.delsig.value = theform.sigs.options[theform.sigs.selectedIndex].value;

	// Delete option (for visual purpose only)
	theform.sigs.options[theform.sigs.selectedIndex] = null;

	return true;
}