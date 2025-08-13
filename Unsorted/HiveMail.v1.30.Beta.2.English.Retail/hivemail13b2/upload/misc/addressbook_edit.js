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
// | $RCSfile: addressbook_edit.js,v $ - $Revision: 1.2 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

function autoSelect(field) {
	if (field.selectedIndex == field.oldSelectedIndex) {
		field.selectedIndex = -1;
	}
	field.oldSelectedIndex = field.selectedIndex;
	field.fireEvent('onChange');
}

function verifyData(form) {
	if (form.emails.options.length < 1) {
		alert('Please enter at least one email address.');
		return false;
	} else if (form.display.value == '') {
		alert('Please enter a display name for this contact.');
		return false;
	} else {
		return true;
	}
}

function gotoUrl(url) {
	if (url.match(/^www\./i)) {
		url = 'http://' + url;
	} else if (!url.match(/^[a-z0-9]+:\/\//i)) {
		url = '';
	}
	if (url != '') {
		window.open(url);
	}
}

function updateAddressInfo(itemsField, valueNumber, newValue) {
	addressArray = addressInfo[itemsField.options[itemsField.selectedIndex].value];
	addressArray[valueNumber] = newValue;
	itemText = new Array;
	if (addressArray[0] != '') {
		itemText[itemText.length] = addressArray[0]+':';
	}
	if (addressArray[1] != '') {
		itemText[itemText.length] = addressArray[1].replace(/\r?\n/g, ' ')+',';
	}
	if (addressArray[2] != '') {
		itemText[itemText.length] = addressArray[2]+',';
	}
	if (addressArray[3] != '') {
		itemText[itemText.length] = addressArray[3]+',';
	}
	itemsField.options[itemsField.selectedIndex].text = itemText.join(' ');
	itemsField.options[itemsField.selectedIndex].text = itemsField.options[itemsField.selectedIndex].text.substring(0, itemsField.options[itemsField.selectedIndex].text.length - 1);
}

function changeDefaultAddy(itemsField) {
	for (var i = 0; i < itemsField.options.length; i++) {
		if (itemsField.selectedIndex == i) {
			itemsField.options[i].className = 'defaultItem';
			addressInfo[itemsField.options[i].value][6] = '1';
		} else {
			itemsField.options[i].className = 'normalItem';
			addressInfo[itemsField.options[i].value][6] = '0';
		}
	}
}

function reloadAddressData(itemOption, form) {
	form.adrName.value = addressInfo[itemOption.value][0];
	form.adrStreet.value = addressInfo[itemOption.value][1];
	form.adrCity.value = addressInfo[itemOption.value][2];
	form.adrState.value = addressInfo[itemOption.value][3];
	form.adrZip.value = addressInfo[itemOption.value][4];
	form.adrCountry.value = addressInfo[itemOption.value][5];

	form.adrName.disabled = form.adrStreet.disabled = form.adrCity.disabled = form.adrState.disabled = form.adrZip.disabled = form.adrCountry.disabled = false;
}

function unloadAddressData(form) {
	form.adrName.value = form.adrName.defaultValue;
	form.adrStreet.value = form.adrStreet.defaultValue;
	form.adrCity.value = form.adrCity.defaultValue;
	form.adrState.value = form.adrState.defaultValue;
	form.adrZip.value = form.adrZip.defaultValue;
	form.adrCountry.value = form.adrCountry.defaultValue;

	form.adrName.disabled = form.adrStreet.disabled = form.adrCity.disabled = form.adrState.disabled = form.adrZip.disabled = form.adrCountry.disabled = true;
}

function updateAddressDisabled(itemsField) {
	itemsField.form.setDefAddress.disabled = itemsField.form.removeAddress.disabled = (itemsField.selectedIndex == -1);
}

function insertAddress(form) {
	var newAddress = prompt('Please enter a name for the new address:', lastAdd);
	if (!newAddress || newAddress.length < 1) {
		lastAdd = newAddress;
		alert('The name you entered was not valid. Please try again.');
		return false;
	}

	if (addressInfo.length > 0) {
		newPos = addressInfo.length;
	} else {
		newPos = 1;
	}
	form.addresses.options[form.addresses.options.length] = new Option(newAddress, newPos);
	addressInfo[newPos] = new Array(newAddress, '', '', '', '', '', '');
	form.addresses.selectedIndex = form.addresses.options.length - 1;
	reloadAddressData(form.addresses.options[form.addresses.selectedIndex], form);
	form.adrStreet.focus();
}

function removeAddy(itemsField) {
	if (!confirm('Are you sure you want to remove the selected address?')) {
		return false;
	}
	var resetDef = (addressInfo[itemsField.options[itemsField.selectedIndex].value][6] == '1');
	addressInfo[itemsField.options[itemsField.selectedIndex].value] = null;
	itemsField.options[itemsField.selectedIndex] = null;

	if (resetDef && itemsField.options.length > 0	) {
		itemsField.selectedIndex = 0;
		changeDefaultAddy(itemsField);
		reloadAddressData(itemsField.options[0], itemsField.form);
	} else {
		unloadAddressData(itemsField.form);
	}
}

function submitAddresses(dataField) {
	dataField.value = '';
	while (addressInfo.length > 0) {
		thisAddress = addressInfo.pop();
		dataField.value += thisAddress[0] + "\n";
		dataField.value += thisAddress[1].replace(/\r?\n/g, '~') + "\n";
		dataField.value += thisAddress[2] + "\n";
		dataField.value += thisAddress[3] + "\n";
		dataField.value += thisAddress[4] + "\n";
		dataField.value += thisAddress[5] + "\n";
		dataField.value += thisAddress[6] + "\n";
	}
}

function updateTypeSelection(itemOption, typesField) {
	if (!typesField) {
		return;
	}

	itemOption.value = 0;
	for (var i = 0; i < typesField.options.length; i++) {
		if (typesField.options[i].selected) {
			itemOption.value = parseInt(itemOption.value) + parseInt(typesField.options[i].value);
		}
	}
}

function reloadTypeSelection(itemOption, typesField) {
	if (!typesField) {
		return;
	}

	for (var i = 0; i < typesField.options.length; i++) {
		if (itemOption.value & typesField.options[i].value) {
			typesField.options[i].selected = true;
		} else {
			typesField.options[i].selected = false;
		}
	}
	typesField.disabled = false;
}

function unloadTypeSelection(typesField) {
	if (!typesField) {
		return;
	}

	for (var i = 0; i < typesField.options.length; i++) {
		typesField.options[i].selected = false;
	}
	typesField.disabled = true;
}

function changeDefault(itemsField, defVarname) {
	for (var i = 0; i < itemsField.options.length; i++) {
		if (itemsField.selectedIndex == i) {
			itemsField.options[i].className = 'defaultItem';
			eval(defVarname+' = '+i+';');
		} else {
			itemsField.options[i].className = 'normalItem';
		}
	}
}

function updateDisabled(itemsField, itemCode) {
	form = itemsField.form;
	eval('form.setDef'+itemCode).disabled = eval('form.edit'+itemCode).disabled = eval('form.remove'+itemCode).disabled = eval('form.send'+itemCode).disabled = eval('form.types'+itemCode).disabled = (itemsField.selectedIndex == -1);
}

function insertItem(itemsField, itemName, isEmail) {
	var newItem = prompt('Please enter the new '+itemName+':', lastAdd);
	if (!newItem || newItem.length < 1 || (isEmail && !newItem.match(/([-a-zA-Z0-9!\#$%&*+\/=?^_`{|}~.]+@[a-zA-Z0-9]{1}[-.a-zA-Z0-9_]*\.[a-zA-Z]{2,6})/i))) {
		lastAdd = newItem;
		alert('The '+itemName+' you entered was not valid. Please try again.');
		return false;
	}

	lastAdd = '';
	itemsField.options[itemsField.options.length] = new Option(newItem, 0);
	itemsField.selectedIndex = -1;
}

function editItem(itemsField, itemName, isEmail) {
	var newItem = prompt('Edit '+itemName+':', itemsField.options[itemsField.selectedIndex].text);
	if (!newItem || newItem.length < 1 || (isEmail && !newItem.match(/([-a-zA-Z0-9!\#$%&*+\/=?^_`{|}~.]+@[a-zA-Z0-9]{1}[-.a-zA-Z0-9_]*\.[a-zA-Z]{2,6})/i))) {
		alert('The '+itemName+' you entered was not valid. Please try again.');
		return false;
	}

	itemsField.options[itemsField.selectedIndex].text = newItem;
	itemsField.selectedIndex = -1;
	unloadTypeSelection(itemsField.form.phonetypes);
}

function removeItem(itemsField, itemName, defVarname) {
	if (!confirm('Are you sure you want to remove the selected '+itemName+'?')) {
		return false;
	}
	itemDef = eval(defVarname);
	var resetDef = (itemDef == itemsField.selectedIndex);
	itemsField.options[itemsField.selectedIndex] = null;

	if (resetDef && itemsField.options.length > 0) {
		changeDefault(itemsField, defVarname);
		itemsField.options[0].className = 'defaultItem';
		eval(defVarname+' = 0;');
	}
	unloadTypeSelection(itemsField.form.phonetypes);
}

function submitData(dataField, itemsField, defVarname) {
	itemDef = eval(defVarname);
	dataField.value = '';
	for (var i = 0; i < itemsField.options.length; i++) {
		if (i != 0) {
			dataField.value += "\n";
		}
		dataField.value += itemsField.options[i].value + '|' + itemsField.options[i].text;
		if (i == itemDef) {
			dataField.value += '|1';
		} else {
			dataField.value += '|0';
		}
	}
}