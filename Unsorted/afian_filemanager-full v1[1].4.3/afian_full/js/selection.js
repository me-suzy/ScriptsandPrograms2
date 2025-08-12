/*
The Afian file manager
.author {
	name: Vlad;
	surname: Roman;
	email: vlad@afian.com;
	web: http://www.afian.com;
}
*/

/******** ICON SELECTION & COUNT ************/
var nextIcon;
var prevIcon;
var lastSelIconIndex;
var countSelected = 0;
var countAll = 0;


//init
function iconListInit() {
	allDivs = document.getElementsByTagName('DIV');
	allDivsLength = allDivs.length;
	icons = new Array();
	for (var i=0; i < allDivsLength; i++) {
		index = icons.length;
 		if (allDivs[i].getAttribute('selectable') == "yes") {
			icons[index] = new Array();
			icons[index]['obj'] = allDivs[i];
			icons[index]['sel'] = 0;
			countAll++;
		}
	}
	iconListOnSelect();
	//document.getElementById('FM~statusBar').innerHTML = countAll + " objects";
	iconsLength = icons.length;
}

//select icon
function iconListSelOn(index, onSelect) {
	icons[index]['sel'] = 1;
	icons[index]['obj'].className='selIcon';
	icons[index]['obj'].lastChild.checked=true;
	countSelected++;
	if (onSelect) {iconListOnSelect();}
}
//deselect icon
function iconListSelOff(index,onSelect) {
	icons[index]['sel'] = 0;
	icons[index]['obj'].className='icon';
	icons[index]['obj'].lastChild.checked=false;
	countSelected--;
	if (onSelect) {iconListOnSelect();}
}

//select icon (if selected->deselect ; if not selected->select)
function iconListSel(index, onSelect) {
	if (icons[index]['sel'] == 0) {
		iconListSelOn(index, onSelect);
	} else {
		iconListSelOff(index, onSelect);
	}
}



function iconListSelect(type) {
if (countAll > 0) { 
//select all
	if (type == 'all') {
		for (var i=0; i < iconsLength; i++) {
			if (icons[i]['sel'] != 1) {
				iconListSelOn(i);
			}
		}
	
//deselect all
	} else if (type == 'none') {
		for (var i=0; i < iconsLength; i++) {
			if (icons[i]['sel'] != 0) {
				iconListSelOff(i);
			}
		}
//invert selection
	} else if (type == 'invert') {
		for (var i=0; i < iconsLength; i++) {
			iconListSel(i);
		}
	}
	iconListOnSelect();
	}
}

//get current selected icon's index nr
function getCurSelIconIndex() {
	if (countSelected == 1) {
			for (var i=0; i < iconsLength; i++) {
				if (icons[i]['sel'] == 1) {
					return i;
				}
			}
			return false;
	}
}

//select next icon
function iconListSelNext() {
	curSelIndex = getCurSelIconIndex();
	if (curSelIndex != 'undefined') {
		if (curSelIndex+1 < iconsLength) {
			iconListSelOff(curSelIndex);
			iconListSelOn(curSelIndex+1);
			iconListOnSelect();
		} else {
			iconListSelOff(curSelIndex);
			iconListSel('0', true);
		}
	}
}
//select previous icon
function iconListSelPrev() {
	curSelIndex = getCurSelIconIndex();
	if (curSelIndex != 'undefined') {
		if (curSelIndex-1 > -1) {
		iconListSelOff(curSelIndex, true);
		iconListSelOn(curSelIndex-1, true);
		} else {
			iconListSelOff(curSelIndex);
			iconListSel(iconsLength-1, true);
		}
	}
}


function selIconByLetter(letter) {
if (countAll > 0) { 
	if (letter.length == 0) {
		iconListSelect('none');
	} else {
		for (var i=0; i < iconsLength; i++) {
			if (icons[i]['obj'].getAttribute('title').toLowerCase().indexOf(letter.toLowerCase())!=-1) {
				if (icons[i]['sel'] == 0) {
					iconListSelOn(i);
				}
			} else {
				if (icons[i]['sel'] == 1) {
					iconListSelOff(i);
				}
			}
		}
	}
	iconListOnSelect();
}
}

/**************************************/


