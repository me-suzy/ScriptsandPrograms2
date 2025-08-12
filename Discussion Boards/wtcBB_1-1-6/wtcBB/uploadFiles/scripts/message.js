// this function accepts a simple bb code
// and an id for the destination... and adds it in
function addBBcode(simple,destination) {
	// get out if DOM isn't supported...
	if(!document.getElementById) {
		return;
	}

	// create object
	obj = document.getElementById(destination);

	stillSimple = true;

	// prompt...
	if(simple != "url") {
		inTheMiddle = prompt("Enter the text you want to be formatted:","");

		if(inTheMiddle == null) {
			return;
		}
	} else {
		advanced = prompt("Do you wish to use a named link?\n(1 = yes; 0 = no)","");

		if(advanced == null) {
			return;
		}

		if(advanced == 1 || advanced.toLowerCase() == "y" || advanced.toLowerCase() == "yes") {
			url = prompt("Enter the link:","");

			if(url == null) {
				return;
			}

			displayName = prompt("Enter the display name:","");

			if(displayName == null) {
				return;
			}

			stillSimple = false;
		} else {
			url = prompt("Enter the link:","");

			if(url == null) {
				return;
			}

			inTheMiddle = url;
			stillSimple = true;
		}
	}

	// get the text to add...
	if(stillSimple == true) {
		replaceText = "[" + simple + "]" + inTheMiddle + "[/" + simple + "]";
	} else {
		replaceText = "[" + simple + "=" + url + "]" + displayName + "[/" + simple + "]";
	}

	// set the focus!
	obj.focus();

	// mmmmhmmm
	if(document.selection) {
		document.selection.createRange().text = replaceText;
	}

	else if(obj.selectionStart && obj.selectionEnd) {
		start = obj.selectionStart;
		end = obj.selectionEnd;

		// rebuild value...
		obj.value = (obj.value).substring(0,start) + replaceText + (obj.value).substring(end, obj.value.length);
	}

	// tryed my best.. just add it onto the end...
	else {
		obj.value += replaceText;
	}

	obj.focus();
}

// this function adds the quote..
function addQuote(destination) {
	// get out if DOM isn't supported
	if(!document.getElementById) {
		return;
	}

	// create object
	obj = document.getElementById(destination);

	// direct or indirect?
	in_direct = prompt("Do you wish to direct the quote towards someone?\n(1 = yes; 0 = no)","");
	direct = false;

	if(in_direct == 1 && in_direct != "") {
		toWhom = prompt("Who do you wish to direct it to?","");
		direct = true;
	}
	
	formatted = prompt("Enter the text to be formatted:","");

	if(direct == true) {
		replaceText = "[quote=" + toWhom + "]" + formatted + "[/quote]";
	} else {
		replaceText = "[quote]" + formatted + "[/quote]";
	}

	// set the focus!
	obj.focus();

	// mmmmhmmm
	if(document.selection) {
		document.selection.createRange().text = replaceText;
	}

	else if(obj.selectionStart && obj.selectionEnd) {
		start = obj.selectionStart;
		end = obj.selectionEnd;

		// rebuild value...
		obj.value = (obj.value).substring(0,start) + replaceText + (obj.value).substring(end, obj.value.length);
	}

	// tryed my best.. just add it onto the end...
	else {
		obj.value += replaceText;
	}

	obj.focus();
}

// this function will take care of the lists..
function addList(bbcode,option,destination) {
	// get out if DOM isn't supported
	if(!document.getElementById || option == "null") {
		return;
	}

	// create object
	obj = document.getElementById(destination);

	// number of list items...
	numOfItems = prompt("Enter the number of items you wish to be in you list:","");

	if(numOfItems == null || numOfItems == 0) {
		return false;
	}

	replaceText = "[" + bbcode + "=" + option + "]\n";

	// loop through to format the list items...
	for(x = 1; x <= numOfItems; x++) {
		// prompt for the actual item..
		newItem = prompt("Enter Item Number " + x,"");

		if(newItem == null) {
			continue;
		}

		// format...
		replaceText += "[!]" + newItem + "[/!]\n";
	}

	// finish it up...
	replaceText += "[/" + bbcode + "]\n";

	// set the focus!
	obj.focus();

	// mmmmhmmm
	if(document.selection) {
		document.selection.createRange().text = replaceText;
	}

	else if(obj.selectionStart && obj.selectionEnd) {
		start = obj.selectionStart;
		end = obj.selectionEnd;

		// rebuild value...
		obj.value = (obj.value).substring(0,start) + replaceText + (obj.value).substring(end, obj.value.length);
	}

	// tryed my best.. just add it onto the end...
	else {
		obj.value += replaceText;
	}

	obj.focus();
}

// this function will take care of fonts, colors, and sizes for the toolbar...
function addDropdownOpt(bbcode,option,destination) {
	// get out if DOM isn't supported...
	if(!document.getElementById || option == "null") {
		return;
	}

	// create object
	obj = document.getElementById(destination);

	// font, size, and color are all complex.. so we should be able
	// to get away with doing one thing for all...
	formattedText = prompt("Enter the text you wish to be formatted: ","");

	// if empty or null.. return
	if(formattedText == "" || formattedText == null) {
		return;
	}

	// create replace text
	replaceText = "[" + bbcode + "=" + option + "]" + formattedText + "[/" + bbcode + "]";

	// set the focus!
	obj.focus();

	// mmmmhmmm
	if(document.selection) {
		document.selection.createRange().text = replaceText;
	}

	else if(obj.selectionStart && obj.selectionEnd) {
		start = obj.selectionStart;
		end = obj.selectionEnd;

		// rebuild value...
		obj.value = (obj.value).substring(0,start) + replaceText + (obj.value).substring(end, obj.value.length);
	}

	// tryed my best.. just add it onto the end...
	else {
		obj.value += replaceText;
	}

	obj.focus();
}

// this function basically does the same as above...
// same parameters and everything, except it does smilies... :)
function addSmiley(symbol,destination) {
	// get out if DOM isn't supported...
	if(!document.getElementById) {
		return;
	}

	// get object
	obj = document.getElementById(destination);

	// set the focus!
	obj.focus();

	// mmmmhmmm
	if(document.selection) {
		document.selection.createRange().text = symbol;
	}

	else if(obj.selectionStart && obj.selectionEnd) {
		start = obj.selectionStart;
		end = obj.selectionEnd;

		// rebuild value...
		obj.value = (obj.value).substring(0,start) + symbol + (obj.value).substring(end, obj.value.length);
	}

	// tryed my best.. just add it onto the end...
	else {
		obj.value += symbol;
	}

	obj.focus();
}

// this opens the window for the more smilies...
function openMoreSmilies() {
	// get out if DOM isn't supported
	if(!document.getElementById) {
		return;
	}

	openWindow = open("other.php?do=smilies","smiley","width=400,height=400,resizable=yes,scrollbars=yes,status=yes");

	if(openWindow.opener == null) {
		openWindow.opener = self;
	}
}

// this function will add smilies from the new window, to the message area
function addWindowSmiley(symbol,destination) {
	// get out if DOM isn't supported
	if(!document.getElementById) {
		return;
	}

	// get object..
	obj = top.opener.document.getElementById(destination);

	// set the focus!
	obj.focus();

	// mmmmhmmm
	if(top.opener.document.selection) {
		top.opener.document.selection.createRange().text = symbol;
	}

	else if(obj.selectionStart && obj.selectionEnd) {
		start = obj.selectionStart;
		end = obj.selectionEnd;

		// rebuild value...
		obj.value = (obj.value).substring(0,start) + symbol + (obj.value).substring(end, obj.value.length);
	}

	// tryed my best.. just add it onto the end...
	else {
		obj.value += symbol;
	}

	obj.focus();
}