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
// | $RCSfile: aliases.js,v $ - $Revision: 1.2 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

function addAlias(theform) {
	if (maxAliases > 0 && maxAliases <= aliasesCount) {
		var diff = aliasesCount - maxAliases + 1;
		alert('You currently have '+aliasesCount+' alias'+((aliasesCount == 1) ? ('') : ('es'))+'. You are only allowed to have '+maxAliases+', so please delete at least '+diff+' alias'+((diff == 1) ? ('') : ('es'))+' before adding a new alias.');
		return false;
	}

	var what = eval('theform.alias');
	var where = eval('theform.aliases');

	for (var i = 0; i < where.options.length; i++) {
		if (what.value == where.options[i].value) {
			alert('This alias already appears in your list.');
			return;
		}
	}

	where.options[where.options.length] = new Option(what.value+domainName, what.value);
	what.value = '';
	eval('theform.addalias').disabled = true;
	aliasesCount++;
}

function deleteAlias(theform) {
	var list = eval('theform.aliases');
	var button = eval('theform.deletealias');
	if (list.options.selectedIndex <= 1) {
		alert('You cannot remove this item.');
		return false;
	}
	list.options[list.options.selectedIndex] = null;
	button.disabled = true;
	aliasesCount--;
}

function extract_lists(theform) {
	var thisField = eval('theform.aliases');
	var toField = eval('theform.aliaslist');

	toField.value = '';
	for (var i = 0; i < thisField.options.length; i++) {
		if (i != 0) {
			toField.value += ' ';
		}
		toField.value += thisField.options[i].value;
	}
}