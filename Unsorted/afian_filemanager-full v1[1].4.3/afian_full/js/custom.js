/*
.author {
	name: Vlad;
	surname: Roman;
	email: vlad@afian.com;
	web: http://www.afian.com;
}
*/
var ns4 = (document.layers)? true:false
var ie4 = (document.all)? true:false
var writing = false;




function iconListOnSelect() {
	if (countSelected == 0) {
		hideMenu('FM~multiple');
		hideMenu('FM~menuFile');
		hideMenu('FM~menuDir');
		if (countAll == "1") {
		document.getElementById('FM~statusBar').innerHTML = 'One object';
		} else if (countAll == "0") {
		document.getElementById('FM~statusBar').innerHTML = "Folder empty.";
		} else {
		document.getElementById('FM~statusBar').innerHTML = countAll + " objects";
		}
	
	}else if (countSelected == 1) {
	var type;
			if (icons[getCurSelIconIndex()]['obj'].getAttribute('isdir') == "yes") {
				type = 'folder';
			} else {
				type ='file';
			}
		document.getElementById('FM~statusBar').innerHTML = 'One '+type+' selected';
		if (icons[getCurSelIconIndex()]['obj'].getAttribute('isdir') == "yes") {
			showMenu('FM~menuDir');
			hideMenu('FM~multiple');
			hideMenu('FM~menuFile');
		} else {
			showMenu('FM~menuFile');
			if (icons[getCurSelIconIndex()]['obj'].getAttribute('iszip') == "yes") {
	document.getElementById('FM~menuExtra').innerHTML='<br><a href="javascript:document.location.href=\'?act=extract&dir='+currentDir+'&filename='+icons[getCurSelIconIndex()]['obj'].getAttribute('title')+'\'" class="item">extract here</a>';
			} else {
		document.getElementById('FM~menuExtra').innerHTML ='';
			}
			hideMenu('FM~menuDir');
			hideMenu('FM~multiple');
		}
	}  else if (countSelected > 1){
		showMenu('FM~multiple');
		hideMenu('FM~menuFile');
		hideMenu('FM~menuDir');
		document.getElementById('FM~statusBar').innerHTML = countSelected + " objects selected";
	}
}



function showMenu(menuId) {
	meniu = document.getElementById(menuId);
	meniu.style.display='block';
	if (ie4) {
		var leftPos = parseInt(getX()+20);
		var topPos = parseInt(getY()+15);
		if (menuId != "FM~clipboard") {
			//+findScrollTop() - only for Opera
			if (leftPos+meniu.offsetWidth > document.body.offsetWidth+findScrollLeft()-25) {
				leftPos = leftPos-meniu.offsetWidth;
			}
			if (leftPos > document.body.offsetWidth+findScrollLeft()-25) {
				leftPos = leftPos-meniu.offsetWidth;
			}
			
			if (topPos+meniu.offsetHeight > document.body.offsetHeight+findScrollTop()-10) {
				topPos = topPos-meniu.offsetHeight;
			}
		}
	} else {
		var leftPos = '60';
		var topPos = '60';
	}
	meniu.style.visibility = 'visible';
	meniu.style.left = leftPos;
	meniu.style.top = topPos;

}

function hideMenu(menuId) {
	meniu = document.getElementById(menuId);
	//meniu.style.visibility = 'hidden';
	meniu.style.display='none';
}



document.onkeydown=function() {
	var k=event.keyCode;
	var ctrl=event.ctrlKey;
		if (ctrl && k == "65" && countAll > 0) {//CTRL+A
		iconListSelect('all');
		return false;
	} else if (ctrl && k == "73" && countAll > 0) {//CTRL+I
		iconListSelect('invert');
		return false;
	} else if (k == "39" && countAll > 0) {//RIGHT Arrow
		if (countSelected == 0) {
			iconListSel('0', true);
		} else if (countSelected == 1){
			iconListSelNext();
		}
	} else if (k == "37" && countAll > 0) {//LEFT Arrow
		if (countSelected == 0) {
			iconListSel(iconsLength-1, true);
		} else if (countSelected == 1) {
			iconListSelPrev();
		}
	} else if (k == "113") { //F2
		rename_prompt();
	} else if (k == "46") { //DEL
	
	if (!writing) {
		delete_prompt();
		return false;
	}
	
	} else if (k == "8") { //BACKSPACE
	if (!writing) {
		if (currentDir != "") {
			upOneDir();
		}
		return false;
	}
	}else if (k == "13") { //ENTER
	if (!writing) {
		if (countSelected == "1") {
			if (icons[getCurSelIconIndex()]['obj'].getAttribute('isdir')=='yes'){
document.location.href='?dir='+currentDir+'/'+icons[getCurSelIconIndex()]['obj'].getAttribute('title')+'';
			} else {
document.location.href='?act=download&dir='+currentDir+'&filename='+icons[getCurSelIconIndex()]['obj'].getAttribute('title')+'';
			}
		}
		return false;
	}
	} else if (k == "27") {// ESC
	if (!writing) {
		iconListSelect('none');
		hideMenu('FM~menuDir');
		hideMenu('FM~multiple');
		hideMenu('FM~menuFile');
		hideMenu('FM~renDiv');
		hideMenu('FM~clipboard');
		hideMenu('FM~upload');
		hideMenu('FM~mkdir');
		closePopup();
	}
	hideMenu('FM~renDiv');
	} else if (ctrl && k == "65") {// A
	if (!writing) {
		iconListSelect('all');
		return false;
	}
	} else if (k == "36") {//HOME
		if (currentDir != "") {
			document.location.href='?dir=';
		}
	} else if (ctrl && k == "88") {//X - cut
		if (countSelected > 0) {
			document.filemanform.act.value='cut';
			document.filemanform.submit();
		}
	} else  if (ctrl && k == "67") {//C - copy
		if (countSelected > 0) {
			document.filemanform.act.value='copy';
			document.filemanform.submit();
		}
	} else if (ctrl && k == "86") {//V - paste
		if (document.FM_clipform.submit[0] != null) {
		document.FM_clipform.submit[0].click();
		}
	}
}



function addUploadFields(number) {
	data = '<br style="line-height:2px;"><input type="file" name="uploadfiles[]" class="inputfile" onFocus="javascript:writing=true;" onBlur="javascript:writing=false;">';
	var anotherSpan = document.createElement('span');
	anotherSpan.innerHTML = data;
	document.getElementById('FM~cust').appendChild(anotherSpan); 
}


function popup(url, width, height, top, left) {
	iframe = document.getElementById('FM~popupIFRAME');
	div = document.getElementById('FM~popupDIV');
	iframeDIV = document.getElementById('FM~iframeDIV');
	iframeDIV.innerHTML = '<iframe height="100%" width="100%" id="FM~popupIFRAME" frameborder="0" style="width:'+width+'px;height:'+height+'px" src="'+url+'" style="display:none;background-color:white;" onload="this.style.display=\'block\'"></iframe>';
	iframeDIV.style.width=width;
	iframeDIV.style.height=height;
	div.style.width=width;
	div.style.height=height;
	div.style.left=-(-left-findScrollLeft());
	div.style.top=-(-top-findScrollTop());
	div.style.display='block';
	div.style.visibility='visible';
}
function closePopup() {
	div = document.getElementById('FM~popupDIV');
	iframeDIV = document.getElementById('FM~iframeDIV');
	div.style.display='none';
	iframeDIV.innerHTML = "";
}



function makeDragable(objid) {
	Drag.init(document.getElementById(objid),null);
}



function launchEdit(newfile){
	if (newfile == "1") {
	popup('edit.php?dir='+currentDir+'&filename=newfile.html&new=1', '650', '455', '10', '15');
	} else {
popup('edit.php?dir='+currentDir+'&filename='+icons[getCurSelIconIndex()]['obj'].getAttribute('title')+'', '650', '455', '10', '15');
	}
}

window.name='filemanager';
window.defaultStatus='http://filemanager.afian.com';

//rollover js
function mover(el, bgcol, col) {
	el.style.backgroundColor=bgcol;
	el.style.color=col;
}


function rename_prompt(){
	if (countSelected == "1") {
	document.getElementById('FM~renDiv').style.visibility='visible';
	document.getElementById('FM~renDiv').style.display='block';
		document.getElementById('FM~renDiv').style.top=currentY;
		document.getElementById('FM~renDiv').style.left=currentX;
		hideMenu('FM~menuDir');
		hideMenu('FM~menuFile');
		document.renameForm.newName.value=icons[getCurSelIconIndex()]['obj'].lastChild.getAttribute('value');
		document.renameForm.oldName.value=icons[getCurSelIconIndex()]['obj'].getAttribute('title');
		document.renameForm.newName.select();
	}
}

function delete_prompt(){
	if (countSelected > 0) {
		if (confirm("Are you sure you want to delete selected object(s)? ")) {
			document.filemanform.act.value='multidel';
			//alert(document.filemanform.act.value);
			document.filemanform.submit();
		}
	}
}

function sendByEmail() {
popup('byemail.php?dir='+currentDir+'&filename='+icons[getCurSelIconIndex()]['obj'].getAttribute('title')+'', '250', '120', '20', '150');
}

function chmod(nr) {
	if (nr == '2') {
		var url = 'chmod.php?dir='+currentDir+'&multiple=1';
	} else {
		var url = 'chmod.php?dir='+currentDir+'&filename='+icons[getCurSelIconIndex()]['obj'].getAttribute('title')+'';
	}
	popup(url, '250', '270', '10', '150');
}

function down(filename) {
document.location.href='?act=download&dir='+currentDir+'&filename='+filename;
}
function browse(dirname){
document.location.href='?dir='+currentDir+'/'+dirname;
}