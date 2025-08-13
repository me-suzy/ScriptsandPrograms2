// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// | $RCSfile: common.js,v $ - $Revision: 1.16 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

function getElement(name) {
	if (document.getElementById) {
		return document.getElementById(name);
	} else if (document.all) {
		return document.all[name];
	} else if (document.layers){
		return document[name];
	}
}

function preloadImages() {
	var toLoad = preloadImages.arguments;
	document.preload = new Array();
	if (document.images) {
		for (var i = 0; i < toLoad.length; i++) {
			document.preload[i] = new Image();
			document.preload[i].src = toLoad[i];
		}
	}
}

function showHidePreviewPane(showAnyway) {
	if (!previewBoth) {
		var previewPaneShow = getElement('previewPaneShow');
		var previewPaneHide = getElement('previewPaneHide');
	} else {
		var previewPaneShow = getElement('previewPaneShowTop');
		var previewPaneHide = getElement('previewPaneHideTop');
	}

	if (previewPaneShow) {
		if (previewPaneShow.style.display != 'none' || showAnyway) {
			previewPaneShow.style.display = 'none';
			previewPaneHide.style.display = '';
			document.cookie = 'hiveshowpreview=1';
		} else {
			previewPaneShow.style.display = '';
			previewPaneHide.style.display = 'none';
			document.cookie = 'hiveshowpreview=0';
		}
		if (previewBoth) {
			getElement('previewPaneShowBottom').style.display = previewPaneShow.style.display;
			getElement('previewPaneHideBottom').style.display = previewPaneHide.style.display;
		}
	}
}

function showHideFolders() {
	var folderTab1 = getElement('folderTab1');
	var folderTab2 = getElement('folderTab2');
	var folderTab3 = getElement('folderTab3');

	if (folderTab1.style.display == 'none') {
		folderTab1.style.display = folderTab2.style.display = '';
		folderTab3.style.display = 'none';
		document.cookie = 'hiveshowtab=1';
		window.scrollTo(0, 0);
	} else {
		folderTab1.style.display = folderTab2.style.display = 'none';
		folderTab3.style.display = '';
		document.cookie = 'hiveshowtab=0';
	}
}

function imgevent(url) {
	randomnumber = Math.floor(Math.random() * 1000) + 1;
	triggerimage = new Image();
	triggerimage.src = url+'&rand='+randomnumber;
}

function showNotice(text) {
	getElement('noticeText').innerHTML = text;
	getElement('noticeTable').style.display = '';
}

function closeNotice() {
	getElement('noticeTable').style.display = 'none';
}