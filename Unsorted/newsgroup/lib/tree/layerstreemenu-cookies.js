// PHP Layers Menu 2.3.5 (C) 2001-2003 Marco Pratesi (marco at telug dot it)

DOM = (document.getElementById) ? 1 : 0;
NS4 = (document.layers) ? 1 : 0;
// We need to explicitly detect Konqueror
// because Konqueror 3 sets IE = 1 ... AAAAAAAAAARGHHH!!!
Konqueror = (navigator.userAgent.indexOf("Konqueror") > -1) ? 1 : 0;
// We need to detect Konqueror 2.1 and 2.2 as they do not handle the window.onresize event
Konqueror21 = (navigator.userAgent.indexOf("Konqueror 2.1") > -1 || navigator.userAgent.indexOf("Konqueror/2.1") > -1) ? 1 : 0;
Konqueror22 = (navigator.userAgent.indexOf("Konqueror 2.2") > -1 || navigator.userAgent.indexOf("Konqueror/2.2") > -1) ? 1 : 0;
Konqueror2 = Konqueror21 || Konqueror22;
Opera = (navigator.userAgent.indexOf("Opera") > -1) ? 1 : 0;
Opera5 = (navigator.userAgent.indexOf("Opera 5") > -1 || navigator.userAgent.indexOf("Opera/5") > -1) ? 1 : 0;
Opera6 = (navigator.userAgent.indexOf("Opera 6") > -1 || navigator.userAgent.indexOf("Opera/6") > -1) ? 1 : 0;
Opera56 = Opera5 || Opera6;
IE = (document.all) ? 1 : 0;
IE4 = IE && !DOM;

function setLMCookie(name, value) {
	document.cookie = name + "=" + value;
}

function getLMCookie(name) {
	foobar = document.cookie.split(name + "=");
	if (foobar.length < 2) {
		return null;
	}
	tempString = foobar[1];
	if (tempString.indexOf(";") == -1) {
		return tempString;
	}
	yafoobar = tempString.split(";");
	return yafoobar[0];
}

function parseExpandString() {
	expandString = getLMCookie("expand");
	expand = new Array();
	if (expandString) {
		expanded = expandString.split("|");
		for (i=0; i<expanded.length-1; i++) {
			expand[expanded[i]] = 1;
		}
	}
}

function parseCollapseString() {
	collapseString = getLMCookie("collapse");
	collapse = new Array();
	if (collapseString) {
		collapsed = collapseString.split("|");
		for (i=0; i<collapsed.length-1; i++) {
			collapse[collapsed[i]] = 1;
		}
	}
}

parseExpandString();
parseCollapseString();

function saveExpandString() {
	expandString = "";
	for (i=0; i<expand.length; i++) {
		if (expand[i] == 1) {
			expandString += i + "|";
		}
	}
	setLMCookie("expand", expandString);
}

function saveCollapseString() {
	collapseString = "";
	for (i=0; i<collapse.length; i++) {
		if (collapse[i] == 1) {
			collapseString += i + "|";
		}
	}
	setLMCookie("collapse", collapseString);
}

