/**  **/
function closer() {
	opener.location.reload();
	self.close();
}

/**  **/
function openWindow(theURL,winName,winWidth,winHeight,sb,rs) {
	
	var winLeft = ((screen.availWidth - winWidth) / 2);
	var winTop = ((screen.availHeight - winHeight) / 2);
	
	if(sb == "no") {
		sb_string = ",scrollbars=no";
	}
	else {
		sb_string = ",scrollbars=yes";
	}
	if(rs == "yes") {
		rs_string = ",resizable=yes";
	}
	else {
		rs_string = ",resizable=no";
	}
	
	window.open(theURL,winName,'toolbar=no,scrollbars=yes,status=no,menubar=no' + rs_string + ',location=no,dependent=yes,width=' + winWidth + ',height=' + winHeight + ',left=' + winLeft + ',top=' + winTop);
}

/**  **/
function confirm_delete(url, deletetext){
	if(confirm("Möchten Sie den Eintrag '" + deletetext + "' wirklich löschen?") == true)
		document.location.href=url;
}

/**  **/
function reloadWindows(catID) {
	
	parent.frames[0].location.href = 'admin.php?action=category.tree&blank=true&category_id=' + catID;
	parent.frames[1].location.href = 'admin.php?action=category.files&blank=true&category_id=' + catID;
	parent.frames[2].location.href = 'admin.php?action=category.allfiles&blank=true&category_id=' + catID;
}