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
// | $RCSfile: columns.js,v $ - $Revision: 1.8 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

function updateDisabled(theform) {
	// Add / Delete
	if (theform.avail.selectedIndex == -1) {
		theform.add.disabled = true;
	} else {
		theform.add.disabled = false;
	}
	if (theform.using.selectedIndex == -1) {
		theform.del.disabled = true;
	} else {
		theform.del.disabled = false;
	}

	// Up / Down
	if (theform.using.selectedIndex == 0) {
		theform.up.disabled = true;
	} else {
		theform.up.disabled = false;
	}
	if (theform.using.selectedIndex == (theform.using.options.length - 1)) {
		theform.down.disabled = true;
	} else {
		theform.down.disabled = false;
	}
}

function goUp(theform) {
	thefield = theform.using;
	if (thefield.selectedIndex == -1 || thefield.selectedIndex == 0) {
		return;
	}
	selCol = thefield.selectedIndex;
	tmpoption = thefield.options[selCol];
	thefield.options[selCol] = new Option(thefield.options[selCol-1].text, thefield.options[selCol-1].value);
	thefield.options[selCol-1] = tmpoption;
	updateDisabled(theform);
}

function goDown(theform) {
	thefield = theform.using;
	if (thefield.selectedIndex == -1 || thefield.selectedIndex == thefield.options.length) {
		return;
	}
	selCol = thefield.selectedIndex;
	tmpoption = thefield.options[selCol];
	thefield.options[selCol] = new Option(thefield.options[selCol+1].text, thefield.options[selCol+1].value);
	thefield.options[selCol+1] = tmpoption;
	updateDisabled(theform);
}

function addCol(theform) {
	if (theform.avail.selectedIndex == -1) {
		return;
	}
	selectedCol = theform.avail.options[theform.avail.selectedIndex];
	theform.using.options[theform.using.options.length] = new Option(selectedCol.value, selectedCol.value);
	theform.avail.options[theform.avail.selectedIndex] = null;
	theform.add.disabled = true;
}

function delCol(theform) {
	if (theform.using.selectedIndex == -1) {
		return;
	}
	selectedCol = theform.using.options[theform.using.selectedIndex];
	theform.avail.options[theform.avail.options.length] = new Option(selectedCol.value, selectedCol.value);
	theform.using.options[theform.using.selectedIndex] = null;
	theform.del.disabled = true;
}

function extractList(theform) {
	for (var i = 0; i < theform.using.options.length; i++) {
		theform.finalusing.value += ',' + theform.using.options[i].value;
	}
}