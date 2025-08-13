function getAnchorPosition(anchorname) {
	var useWindow = false;
	var coordinates = new Object();
	var x = 0, y = 0;
	var use_gebi = false, use_css = false, use_layers = false;
	if (document.getElementById) { use_gebi = true; }
	else if (document.all) { use_css = true; }
	else if (document.layers) { use_layers = true; }
	if (use_gebi && document.all) {
		x = AnchorPosition_getPageOffsetLeft(document.all[anchorname]);
		y = AnchorPosition_getPageOffsetTop(document.all[anchorname]);
	}
	else if (use_gebi) {
		var o = document.getElementById(anchorname);
		x = AnchorPosition_getPageOffsetLeft(o);
		y = AnchorPosition_getPageOffsetTop(o);
	}
	else if (use_css) {
		x = AnchorPosition_getPageOffsetLeft(document.all[anchorname]);
		y = AnchorPosition_getPageOffsetTop(document.all[anchorname]);
	}
	else if (use_layers) {
		var found = 0;
		for (var i = 0; i < document.anchors.length; i++) {
			if (document.anchors[i].name == anchorname) { found = 1; break; }
		}
		if (found == 0) {
			coordinates.x = 0; coordinates.y = 0; return coordinates;
		}
		x = document.anchors[i].x;
		y = document.anchors[i].y;
	}
	else {
		coordinates.x = 0; coordinates.y = 0; return coordinates;
	}
	coordinates.x = x;
	coordinates.y = y;
	return coordinates;
}

function AnchorPosition_getPageOffsetLeft (el) {
	var ol = el.offsetLeft;
	while ((el = el.offsetParent) != null) { ol += el.offsetLeft; }
	return ol;
}	

function AnchorPosition_getPageOffsetTop (el) {
	var ot = el.offsetTop;
	while ((el = el.offsetParent) != null) { ot += el.offsetTop; }
	return ot;
}

function getScrollTop() {
	if (navigator.appName == "Microsoft Internet Explorer") {
		return document.documentElement.scrollTop || document.body.scrollTop;
	} else {
		return window.pageYOffset;
	}
}

function zeroUp(num) {
	if (num < 0) {
		return 0;
	} else {
		return num;
	}
}