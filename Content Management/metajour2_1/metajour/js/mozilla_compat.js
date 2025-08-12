function launchModeless(sUrl, iWidth, iHeight){

	// IE
	if(typeof(window.showModelessDialog) != "undefined"){
		window.showModelessDialog(sUrl, window, "dialogHeight:"+ iHeight +"px; dialogWidth: "+ iWidth + "px; help:no; scroll:no; resizable:no; status:no");
	}
	// Mozilla
	else{
		window.open(sUrl, null, "dialog, width="+ iWidth +", height="+ iHeight);
	}
	
	return false;
}

// outerHTML for Mozilla
if (typeof(HTMLElement) != 'undefined') {
	HTMLElement.prototype.__defineSetter__("outerHTML", function (sHTML) {
		var r = this.ownerDocument.createRange();
		r.setStartBefore(this);
		var df = r.createContextualFragment(sHTML);
		this.parentNode.replaceChild(df, this);
	});
}
