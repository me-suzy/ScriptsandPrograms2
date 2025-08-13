var win = this;
var n = 0;

function highlightAndCopy(theField) {
	theField.focus();
	theField.select();
	if (document.all){
		therange = theField.createTextRange();
		therange.execCommand('Copy');
		window.status = 'Contents highlighted and copied to clipboard!';
		setTimeout('window.status=""', 2400);
	}
}

function findInPage(query, theField) {
	var txt, i, found;
	if (query == '') {
		return false;
	}
	if (document.layers) {
		if (!win.find(query)) {
			while (win.find(query, false, true)) {
				n++;
			}
		} else {
			n++;
		}
		found = true;
		if (n == 0) {
			found = false;
			alert('Search term not found.');
		}
	}
	if (document.all) {
		txt = theField.createTextRange();
		for (i = 0; i <= n && (found = txt.findText(query)) != false; i++) {
			txt.moveStart('character', 1);
			txt.moveEnd('textedit');
		}
		if (found) {
			txt.moveStart('character', -1);
			txt.findText(query);
			txt.select();
			txt.scrollIntoView();
			n++;
		} else {
			if (n > 0) {
				n = 0;
				return findInPage(query, theField);
			} else {
				alert('Search term not found.');
			}
		}
	}
	return found;
}