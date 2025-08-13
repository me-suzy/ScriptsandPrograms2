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
// | $RCSfile: blockedlist.js,v $ - $Revision: 1.6 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

function addAddress(theform, towhat, checkwhat) {
	var what = eval('theform.'+towhat);
	var where = eval('theform.'+towhat+'s');
	var other = eval('theform.'+checkwhat+'s');

	if (checkwhat == 'block') {
		checkwhat_word = 'blocked';
		towhat_word = 'safe';
	} else {
		checkwhat_word = 'safe';
		towhat_word = 'blocked';
	}

	for (var i = 0; i < where.options.length; i++) {
		if (what.value == where.options[i].value) {
			alert('This address is already in your '+towhat_word+' senders list.');
			return;
		}
	}
	for (var i = 0; i < other.options.length; i++) {
		if (what.value == other.options[i].value) {
			var change = confirm("You cannot have the same address in both lists.\nWould you like to remove it from the "+checkwhat_word+" senders list, and add it to the "+towhat_word+" senders list?");
			if (change) {
				other.options[i] = null;
				break;
			} else {
				return;
			}
		}
	}

	where.options[where.options.length] = new Option(what.value, what.value);
	what.value = '';
	eval('theform.add'+towhat).disabled = true;
}

function deleteAddress(theform, towhat) {
	var list = eval('theform.'+towhat+'s');
	var button = eval('theform.delete'+towhat);
	list.options[list.options.selectedIndex] = null;
	button.disabled = true;
}

function extract_lists(theform) {	
	var fields = new Array('block', 'safe');

	for (var j = 0; j < 2; j++) {
		var thisField = eval('theform.'+fields[j]+'s');
		var toField = eval('theform.'+fields[j]+'list');

		toField.value = '';
		for (var i = 0; i < thisField.options.length; i++) {
			if (i != 0) {
				toField.value += ' ';
			}
			toField.value += thisField.options[i].value;
		}
	}
}