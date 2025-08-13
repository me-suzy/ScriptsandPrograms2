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
// | $RCSfile: folderview.js,v $ - $Revision: 1.19 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+
var paras = '';
if (INDEX_FILE.indexOf('?') == -1) { paras = '?'; } else { paras = '&'; }

function actionStuff(theform, command) {
	if (totalChecked == 0) {
		alert('Please select at least one message.');
		return false;
	}

	switch (command) {
		case 'move':
			selWin = window.open(INDEX_FILE+paras+'cmd=selfolder','selectfolders','resizable=no,width=230,height=180');
			selWin.opener = window;
			break;
		case 'copy':
			selWin = window.open(INDEX_FILE+paras+'cmd=selfolder','selectfolders','resizable=no,width=230,height=180');
			selWin.opener = window;
			break;
		case 'directdelete':
			theform.folderid.value = -3;
		case 'delete':
			if (!confirm('Are you sure you want to delete the selected messages?')) {
				return false;
			}
			changeFolderID();
			theform.cmd.value = 'delete';
			theform.submit();
			break;
		case 'addbook':
			theform.cmd.value = 'addbook';
			theform.submit();
			break;
		case 'blocksender':
			blockSender(theform);
			break;
		case 'blocksubject':
			blockSubject(theform);
			break;
	}
	
	return true;
}

function replyForward(theform, command) {
	if (totalChecked == 0) {
		alert('Please select at least one message.');
		return false;
	}
	if (totalChecked > 1 && command != 'forwardattach') {
		alert('You can only perform this action with one message.');
		return false;
	}

	var element = 0;
	for (var checkbox = theform.elements[element]; element < theform.elements.length; checkbox = theform.elements[element++]) {
		if (checkbox.name != 'allbox' && checkbox.type == 'checkbox' && checkbox.checked) {
			msgID = checkbox.name.substring(6, checkbox.name.indexOf(']'));
			break;
		}
	}

	switch (command) {
		case 'reply':
			window.location = 'compose.email.php?special=reply&messageid='+msgID;
			break;
		case 'replyall':
			window.location = 'compose.email.php?special=replyall&messageid='+msgID;
			break;
		case 'forward':
			window.location = 'compose.email.php?special=forward&messageid='+msgID;
			break;
		case 'forwardattach':
			theform.action = INDEX_FILE+paras+'asattach=1';
			theform.cmd.value = 'forward';
			theform.submit();
			break;
	}

	return true;
}

function changeButtonsStatus(status) {
	return; // problems with some browsers
	form.markas.disabled =
	form.actions.disabled =
	form.replystuff.disabled = status;
}

function showpreview(msgID) {
	frame = getElement('previewFrame' + (previewBoth ? 'Top' : ''));
	if (frame && frame.src != 'read.email.php?messageid='+msgID+'&show=msg') {
		frame.src = 'read.email.php?messageid='+msgID+'&show=msg';
		if (previewBoth) {
			getElement('previewFrameBottom').src = frame.src;
		}
	}
}

function openMail(msgID, newwindow) {
	if (msgID != -1) {
		if (newwindow) {
			window.open('read.email.php?messageid='+msgID);
		} else {
			window.location = 'read.email.php?messageid='+msgID;
		}
	} else {
		var element = 0;
		for (var checkbox = document.form.elements[element]; element < document.form.elements.length; checkbox = document.form.elements[element++]) {
			if (checkbox.id.substr(0, 5) == 'mails' && checkbox.type == 'checkbox' && checkbox.checked) {
				window.open('read.email.php?messageid='+checkbox.id.substr(5));
			}
		}
	}
}

function blockSender(theform) {
	if (confirm('Are you sure you want to block the senders of the selected messages?')) {
		if (confirm('Would you like to remove all messages from these senders from the current folder?')) {
			theform.remove.value = '1';
		} else {
			theform.remove.value = '0';
		}
		if (confirm('Would you like to completely block the domain name of the senders?\n(Otherwise only the specific email address will be blocked.)')) {
			theform.cmd.value = 'blockdomain';
		} else {
			theform.cmd.value = 'blocksender';
		}
		theform.submit();
	}
}

function blockSubject(theform) {
	if (confirm('Are you sure you want to block the subjects of the selected messages?')) {
		if (confirm('Would you like to remove all messages with these subjects from the current folder?')) {
			theform.remove.value = '1';
		} else {
			theform.remove.value = '0';
		}
		theform.cmd.value = 'blocksubject';
		theform.submit();
	}
}

function changeFolderID() {
	var shiftPressed = false;
	if (parseInt(navigator.appVersion) > 3) {
		if (navigator.appName == 'Netscape') {
			if (typeof(e) != 'undefined') {
				shiftPressed = e.modifiers & 4;
			}
		} else {
			shiftPressed = window.event.shiftKey;
		}
	}

	if (shiftPressed) { 
		document.form.folderid.value = -3; 
	}
}

function moveArrow() {
	var newID;
	var rowID;

	if (!window.event) {
		return;
	}

	// Escape
	if (window.event.keyCode == 27) {
		mainChecked = true;
		makeRows('second');
		checkAll(document.forms.form);
		document.forms.form.allbox.checked = false;
		showpreview(-1);
		changeButtonsStatus(true);
		return true;
	}

	// Find the last checked row
	for (var i = 0; i < rows.length; i++) {
		if (rows[i] == lastChecked) {
			var rowID = i;
			break;
		}
	}

	// Up arrow
	if (window.event.keyCode == 38) {
		if (rowID == null) {
			lastChecked = rows[0];
			rowID = 0;
		}
		if (lastChecked == rows[0]) {
			newID = rows[rows.length-1];
		} else {
			newID = rows[rowID-1];
		}
	} else
		
	// Down arrow
	if (window.event.keyCode == 40) {
		if (rowID == null) {
			lastChecked = rows[rows.length - 1];
			rowID = rows.length - 1;
		}
		if (lastChecked == rows[rows.length-1]) {
			newID = rows[0];
		} else {
			newID = rows[rowID+1];
		}
	} else
	
	// Ctrl+A
	if (window.event.keyCode == 65 && window.event.ctrlKey) {
		mainChecked = false;
		document.forms.form.allbox.checked = true;
		makeRows('first');
		checkAll(document.forms.form);
		changeButtonsStatus(false);
		return false;
	} else
	
	// Ctrl+C
	if (window.event.keyCode == 67 && window.event.ctrlKey && totalChecked > 0) {
		window.open(INDEX_FILE+paras+'cmd=selfolder', 'selectfolders', 'resizable=no,width=270,height=150');
		return false;
	}

	if (newID != null) {
		window.scrollTo(0, getElement('row'+newID).offsetTop);
		checkMail(newID, 1, 1);
		return false;
	} else {
		return true;
	}
}

function checkMail(msgID, oneWay, requireCtrl, dontCheck, rightClick, overrideShift) {
	checkbox = eval('document.forms.form.mails'+msgID);
	row = getElement('row'+msgID);
	showpreview(-1);

	if (parseInt(navigator.appVersion) > 3) {
		if (navigator.appName == 'Netscape') {
			var ctrlPressed = false;// e.modifiers & 2;
			var shiftPressed = false;// e.modifiers & 4;
		} else {
			var ctrlPressed = window.event.ctrlKey;
			var shiftPressed = window.event.shiftKey;
		}
	}

	if (!ctrlPressed && requireCtrl) {
		if (rightClick != 1 || (!checkbox.checked && rightClick == 1)) {
			if (!(totalChecked == 1 && checkbox.checked)) {
				mainChecked = true;
				makeRows('second');
				checkAll(document.forms.form);
			}
		}
	}
	
	if (shiftPressed && !overrideShift) {
		var gotBoth = 0;
		for (var i = 0; i < rows.length && gotBoth < 2; i++) {
			if (rows[i] == msgID) {
				var thisRowID = i;
				gotBoth++;
			}
			if (rows[i] == selectStart) {
				var lastRowID = i;
				gotBoth++;
			}
		}
		if (lastRowID == null) {
			selectStart = rows[0];
			lastRowID = 0;
		}

		if (lastRowID < thisRowID) {
			for (i = lastRowID; i <= thisRowID; i++) {
				if (i < rows.length) {
					checkMail(rows[i], 1, 0, 0, 0, 1);
				}
			}
		} else if (thisRowID < lastRowID) {
			for (i = lastRowID; i >= thisRowID; i--) {
				if (i > -1) {
					checkMail(rows[i], 1, 0, 0, 0, 1);
				}
			}
		} else {
			checkMail(rows[thisRowID], 1, 0, 0, 0, 1);
		}

		checkMain(document.forms.form);
		return;
	}

	lastChecked = msgID;
	if (!overrideShift) {
		selectStart = msgID;
	}

	if (oneWay == 1 && checkbox.checked) {
		totalChecked--;
		checkbox.checked = false;
	}

	if (checkbox.checked) {
		if (!overrideShift) {
			showpreview(-1);
		}
		if (useBG) row.className = 'normalRow';
		totalChecked--;
		if (dontCheck != 1) {
			checkbox.checked = false;
			checkMain(document.forms.form);
		} else {
			checkbox.checked = !checkbox.checked;
			checkMain(document.forms.form);
			checkbox.checked = !checkbox.checked;
		}
	} else {
		if (!overrideShift) {
			showHidePreviewPane(true);
			showpreview(msgID);
		}
		if (useBG) row.className = 'highRow';
		totalChecked++;
		if (dontCheck != 1) {
			checkbox.checked = true;
			checkMain(document.forms.form);
		} else {
			checkbox.checked = !checkbox.checked;
			checkMain(document.forms.form);
			checkbox.checked = !checkbox.checked;
		}
	}

	// Disable a couple of things
	if (totalChecked == 0) {
		changeButtonsStatus(true);
	} else {
		changeButtonsStatus(false);
	}
}