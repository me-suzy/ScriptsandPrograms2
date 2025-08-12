function MDM_openWindow(theURL,winName,features)
{
	var _W=window.open(theURL,winName,features);
	_W.focus();
	_W.moveTo(50,50);
}