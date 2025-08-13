//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: folderview.js,v $
// | $Date: 2002/11/11 21:51:42 $
// | $Revision: 1.15 $
// +-------------------------------------------------------------+

function showpreview(msgID) {
	frame = getElement('previewFrame');
	if (frame && frame.src != 'read.email.php?messageid='+msgID+'&show=msg') {
		frame.src = 'read.email.php?messageid='+msgID+'&show=msg';
	}
}

function openMail(msgID) {
	if (msgID != -1) {
		window.location = 'read.email.php?messageid='+msgID;
	} else {
		var element = 0;
		for (var checkbox = document.form.elements[element]; element < document.form.elements.length; checkbox = document.form.elements[element++]) {
			if (checkbox.id.substr(0, 5) == 'mails' && checkbox.type == 'checkbox' && checkbox.checked) {
				window.open('read.email.php?messageid='+checkbox.id.substr(5));
			}
		}
	}
}

function blockSender(msgID, folderID) {
	if (confirm('Are you sure you want to block this sender?')) {
		if (confirm('Would you like to remove all messages from this sender from the current folder?')) {
			window.location = 'rules.block.php?block=sender&delete='+folderID+'&messageid='+msgID;
		} else {
			window.location = 'rules.block.php?block=sender&messageid='+msgID;
		}
	}
}

function blockSubject(msgID, folderID) {
	if (confirm('Are you sure you want to block this subject?')) {
		if (confirm('Would you like to remove all messages with this subject from the current folder?')) {
			window.location = 'rules.block.php?block=subject&delete='+folderID+'&messageid='+msgID;
		} else {
			window.location = 'rules.block.php?block=subject&messageid='+msgID;
		}
	}
}

function changeFolderID() {
	if (parseInt(navigator.appVersion) > 3) {
		if (navigator.appName == 'Netscape') {
			var shiftPressed = e.modifiers & 4;
		} else {
			var shiftPressed = window.event.shiftKey;
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

	if (window.event.keyCode == 27) {
		mainChecked = true;
		makeRows('second');
		checkAll(document.forms.form);
		document.forms.form.allbox.checked = false;
		showpreview(-1);
		return true;
	}

	for (var i = 0; i < rows.length; i++) {
		if (rows[i] == lastChecked) {
			var rowID = i;
			break;
		}
	}

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
	} else if (window.event.keyCode == 40) {
		if (rowID == null) {
			lastChecked = rows[rows.length - 1];
			rowID = rows.length - 1;
		}
		if (lastChecked == rows[rows.length-1]) {
			newID = rows[0];
		} else {
			newID = rows[rowID+1];
		}
	} else if (window.event.keyCode == 65 && window.event.ctrlKey) {
		mainChecked = false;
		document.forms.form.allbox.checked = true;
		makeRows('first');
		checkAll(document.forms.form);
		return false;
	} else if (window.event.keyCode == 67 && window.event.ctrlKey && totalChecked > 0) {
		window.open('index.php?do=selfolder', 'selectfolders', 'resizable=no,width=270,height=150');
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
}