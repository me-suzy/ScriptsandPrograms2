//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | $RCSfile: columns.js,v $
// | $Date: 2002/10/28 18:19:08 $
// | $Revision: 1.2 $
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