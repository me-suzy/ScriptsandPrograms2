var myBrowser;
var staticMenu;

function CreateStaticMenu(theObj, x, y)
{
	myBrowser = new xBrowser();

	staticMenu = new xLayerFromObj(theObj);
	staticMenu.baseX = x;
	staticMenu.baseY = y;
	staticMenu.x = x;
	staticMenu.y = y;
	staticMenu.moveTo(x,y);
	staticMenu.show();
	setInterval("ani()", 20);
}
function ani()
{
	var b = staticMenu;
	var targetX = myBrowser.getMinX() + b.baseX;
	var targetY = myBrowser.getMinY() + b.baseY;
	var dx = (targetX - b.x)/8;
	var dy = (targetY - b.y)/8;
	b.x += dx;
	b.y += dy;

	b.moveTo(b.x, b.y);
}
