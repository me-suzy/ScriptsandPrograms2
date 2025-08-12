function getObject(obj) {
	var theObj;
	if (document.layers) {
		if (typeof obj == "string") {
			return document.layers[obj];
		} else {
			return obj;
		}
	}
	if (document.all) {
		if (typeof obj == "string") {
			return document.getElementById(obj).style;
		} else {
			return obj.style;
		}
	}
	if (document.getElementById) {
		if (typeof obj == "string") {
			return document.getElementById(obj).style;
		} else {
			return obj.style;
		}
	}
	return null;
}

// position an object at a specific pixel coordinate
function shiftTo(obj, x, y) {
	var theObj = getObject(obj);
	if (theObj.moveTo) {
		theObj.moveTo(x,y);
	} else if (typeof theObj.left != "undefined") {
		theObj.left = x;
		theObj.top = y;
	}
}

// move an object by x and/or y pixels
function shiftBy(obj, deltaX, deltaY) {
	var theObj = getObject(obj)
	if (theObj.moveBy) {
		theObj.moveBy(deltaX, deltaY);
	} else if (typeof theObj.left != "undefined") {
		theObj.left = parseInt(theObj.left) + deltaX;
		theObj.top = parseInt(theObj.top) + deltaY;
	}
}

// set the z-order of an object
function setZIndex(obj, zOrder) {
	var theObj = getObject(obj);
	theObj.zIndex = zOrder;
}

// set the background color of an object
function setBGColor(obj, color) {
	var theObj = getObject(obj);
	if (theObj.bgColor) {
		theObj.bgColor = color;
	} else if (typeof theObj.backgroundColor != "undefined") {
		theObj.backgroundColor = color;
	}
}

// set the visibility of an object to visible
function show(obj) {
	var theObj = getObject(obj);
	theObj.visibility = "visible";
}

// set the visibility of an object to hidden
function hide(obj) {
	var theObj = getObject(obj);
	theObj.visibility = "hidden";
}

// retrieve the x coordinate of a positionable object
function getObjectLeft(obj)  {
	var theObj = getObject(obj);
	return parseInt(theObj.left);
}

// retrieve the y coordinate of a positionable object
function getObjectTop(obj)  {
	var theObj = getObject(obj);
	return parseInt(theObj.top);
}

// retrieve the width coordinate of an object
function getObjectWidth(obj)  {
	var theObj = getObject(obj);
	return parseInt(theObj.width);
}

// retrieve the height of an object
function getObjectHeight(obj)  {
	var theObj = getObject(obj);
	return parseInt(theObj.height);
}

// resize an object at a specific pixel value
function resizeTo(obj, w, h) {
	var theObj = getObject(obj);
	theObj.width = w;
	theObj.height = h;
}