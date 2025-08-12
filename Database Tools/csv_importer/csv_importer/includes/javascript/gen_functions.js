// These are all JS functions which are used by at least two stages

function CreateElements(element, clone, num) {
	var tempArray = new Array();
	
	for (var i = 0; i < num; i++) {
		tempArray[tempArray.length] = element.cloneNode(clone);
	}
	
	return tempArray;
}

function WhackDiv(div) {
	while (div.childNodes.length > 0) {
		for (var i = 0; i < div.childNodes.length; i++) {
			div.removeChild(div.childNodes[0]);
		}
	}
}