function popuphelp(what)
{
	var url = "index.php?page=help&ex=nomenu&helppage=" + what;
	window.open(url, 'Help', 'HEIGHT=480,resizable=yes,WIDTH=400,scrollbars=yes');
}
function putin(formElem, text) 
{
	formElem.value += text;
	formElem.focus();
} 

function preload(theimgList) { 
	_Images = new Array()
	theCounter = theimgList.length - 1; 
	for(i = 0; i <= theCounter; i++) {
		_Images[i] = new Image()
		_Images[i].src = new String(theimgList[i])
	}
}