var isIE5 = (navigator.userAgent.indexOf('MSIE 5') > 0 || navigator.userAgent.indexOf('MSIE 6.0') > 0) ? 1 : 0;
var isNS6 = (navigator.userAgent.indexOf('Gecko') > 0) ? 1 : 0;
var activeButton = null;
var mouseJustDown = false;

if (isIE5) {
	document.styleSheets[document.styleSheets.length - 1].addRule('#menuBar', 'padding-top:2px');
	document.styleSheets[document.styleSheets.length - 1].addRule('#menuBar', 'padding-bottom:2px');
	document.onmousedown = pageMousedown;
}

if (isNS6) {
	document.addEventListener('mousedown', pageMousedown, true);
}

function pageMousedown(event) {
	var className;

	if (isIE5) {
		className = window.event.srcElement.className;
	}

	if (isNS6){
		className = (event.target.className ? event.target.className : event.target.parentNode.className);
	}

	if (className != 'menuButton' && className != 'menuItem' && activeButton) {
		resetButton(activeButton);
		mouseJustDown = true;
	}
}

function buttonClick(button, menuName) {
	button.blur();

	if (!button.menu) {
		button.menu = document.getElementById(menuName);
	}

	if (activeButton && activeButton != button) {
		resetButton(activeButton)
	}

	if (button.isDepressed) {
		resetButton(button);
	} else if (!mouseJustDown) {
		depressButton(button);	
	} else {
		buttonMouseover(button);
	}

	mouseJustDown = false;
	return false;
}

function buttonMouseover(button, menuName) {
	button.style.borderTopWidth = '1px';
	button.style.borderLeftWidth = '1px';
	button.style.borderRightWidth = '1px';
	button.style.paddingLeft = '6px';
	button.style.paddingRight = '6px';
	if (activeButton && activeButton != button) {
		resetButton(activeButton);
		if (menuName) {
			buttonClick(button, menuName);
		}
	}
	mouseJustDown = false;
}

function buttonMouseout(button, menuName) {
	if (activeButton != button) {
		button.style.borderTopWidth = '0px';
		button.style.borderLeftWidth = '0px';
		button.style.borderRightWidth = '0px';
		button.style.paddingLeft = '7px';
		button.style.paddingRight = '7px';
	}
	mouseJustDown = false;
}

function depressButton(button) {
	if (!button.oldBackgroundColor) {
		button.oldBackgroundColor = button.style.backgroundColor;
		button.oldBorderBottomColor = button.style.borderBottomColor;
		button.oldBorderRightColor = button.style.borderRightColor;
		button.oldBorderTopColor = button.style.borderTopColor;
		button.oldBorderLeftColor = button.style.borderLeftColor;
		button.oldColor = button.style.color;
		button.oldLeft = button.style.left;
		button.oldPosition = button.style.position;
		button.oldTop = button.style.top;
	}

	button.style.backgroundColor = '#9FC4D6';
	button.style.borderBottomColor = '#79C1E5';
	button.style.borderRightColor = '#79C1E5';
	button.style.borderTopColor = '#4986A5';
	button.style.borderLeftColor = '#4986A5';
	button.style.borderWidth = '1px';
	button.style.paddingLeft = '6px';
	button.style.paddingRight = '6px';
	button.style.color = '#FFFFFF';
	button.style.left = '1px';
	button.style.position = 'relative';
	button.style.top = '0px'; // 1px !!

	if (isIE5 && !button.menu.firstChild.style.width) {
		button.menu.firstChild.style.width = button.menu.offsetWidth + 'px';
	}

	x = getPageOffsetLeft(button);
	y = getPageOffsetTop(button) + button.offsetHeight;

	button.menu.style.left = x + 'px';
	button.menu.style.top  = y + 'px';
	button.menu.style.visibility = 'visible';
	button.isDepressed = true;
	activeButton = button;
	mouseJustDown = false;
}

function resetButton(button) {
	button.style.backgroundColor = button.oldBackgroundColor;
	button.style.borderBottomColor = button.oldBorderBottomColor;
	button.style.borderRightColor = button.oldBorderRightColor;
	button.style.borderTopColor = button.oldBorderTopColor;
	button.style.borderLeftColor = button.oldBorderLeftColor;
	button.style.color = button.oldColor;
	button.style.left = button.oldLeft;
	button.style.position = button.oldPosition;
	button.style.top = button.oldTop;
	button.style.borderTopWidth = '0px';
	button.style.borderLeftWidth = '0px';
	button.style.borderRightWidth = '0px';
	button.style.paddingLeft = '7px';
	button.style.paddingRight = '7px';

	if (button.menu) {
		button.menu.style.visibility = 'hidden';
	}

	button.isDepressed = false;
	activeButton = null;
	mouseJustDown = false;
}

function getPageOffsetLeft(el) {
	return el.offsetLeft + (el.offsetParent ? getPageOffsetLeft(el.offsetParent) : 0);
}

function getPageOffsetTop(el) {
	return el.offsetTop + (el.offsetParent ? getPageOffsetTop(el.offsetParent) : 0);
}