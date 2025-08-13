function openMenu(menuNum) {
	if (openedMenu > 0) {
		//closeMenu(openedMenu);
	}
	document.getElementById('menuRow'+menuNum).style.display = '';
	document.getElementById('menuTitleOpen'+menuNum).style.display = '';
	document.getElementById('menuTitleClosed'+menuNum).style.display = 'none';
	openedMenu = menuNum;
}
function closeMenu(menuNum) {
	document.getElementById('menuRow'+menuNum).style.display = 'none';
	document.getElementById('menuTitleOpen'+menuNum).style.display = 'none';
	document.getElementById('menuTitleClosed'+menuNum).style.display = '';
}

document.onmousemove = getMouseXY;
document.onmouseup = endResize;

mousePosX = 0;
layer = '';
origX = 0;
origLayerX = 0;
origWidth  = 0;
minWidth = 799;
resize = false;

function loadCookie() {
	document.getElementById('maincell').style.width = getCookie('layer13Width');
	document.getElementById('contentcell').style.width = getCookie('layer2Width');
	document.getElementById('bottomcell').style.width = getCookie('layer13Width');
}

function getMouseXY(e) {
	if (document.all) {
		mousePosX = event.clientX; + document.body.scrollLeft;
	} else {
		mousePosX = e.pageX;
	}

}

function startResize() {
	layer = document.getElementById('maincell');
	layer2 = document.getElementById('contentcell');
	layer3 = document.getElementById('bottomcell');
	origX = mousePosX;
	origWidth  = Math.abs(layer.style.width.substring(0, layer.style.width.length - 2));
	resize = true;
	document.onmousemove = onResize;
}

function endResize() {
	if (layer.style) {
		document.cookie = 'layer13Width='+layer.style.width;
		document.cookie = 'layer3Width='+layer2.style.width;
	}
	layer = layer2 = layer3 = '';
	origX = 0;
	resize = false;
	document.onmousemove = getMouseXY;
}

var foo = 0;
function onResize(e) {
	if (foo++%15 != 0) {
		return true;
	}
	getMouseXY(e);
	diffX = mousePosX - origX;
	if (origWidth + diffX <= minWidth) {
		layer.style.width  = layer3.style.width = minWidth + 'px';
		layer2.style.width = (minWidth - 150) + 'px';
	} else {
		layer.style.width  = layer3.style.width = origWidth + diffX + 'px';
		layer2.style.width = (origWidth + diffX - 150) + 'px';
	}
}