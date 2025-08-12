

function getDocument(name) {
	return document.getElementById(name).contentWindow.document;
}

function copyTextareaToEdit(editName, textareaName) {
		getDocument(editName).body.innerHTML	= document.getElementById(textareaName).value;
}

function copyEditToTextarea(editName, textareaName) {
		document.getElementById(textareaName).value = getDocument(editName).body.innerHTML
}


function allTextareaToContent() {
	var iframes = document.getElementsByTagName("iframe");
	for (i=0; i<iframes.length; i++) {
		copyTextareaToEdit(iframes[i].id, iframes[i].id+'_text');
	}
}

function allContentToTextarea() {
	var iframes = document.getElementsByTagName("iframe");
	for (i=0; i<iframes.length; i++) {
		if(document.getElementById(iframes[i].id+'tblCtrls').style.display == 'none') {
			doToggleView(iframes[i].id);
		}
		copyEditToTextarea(iframes[i].id, iframes[i].id+'_text');
	}
}



function foc(name) {
	document.getElementById("selHeading_"+name).selectedIndex = 0;
	document.getElementById(name).contentWindow.focus();
}

function docu(name) {
	return document.getElementById(name).contentWindow.document;
}

// Some functions to 'animate' the buttons..
function selOn(ctrl) {
	ctrl.style.borderColor = '#000000';
	ctrl.style.backgroundColor = '#B5BED6';
	ctrl.style.cursor =	'hand';	
}


function selOff(ctrl) {
	ctrl.style.borderColor = '#D6D3CE';	 
	ctrl.style.backgroundColor = '#DEDEDE';
}


function selDown(ctrl) {
	ctrl.style.backgroundColor = '#8492B5';
}


function selUp(ctrl) {
	ctrl.style.backgroundColor = '#DEDEDE';
}

function RTB_SetFontForeColor(name,value) {
	docu(name).execCommand('forecolor', false, value);
	foc(name);
}

function RTB_SetFontBgColor(name,value) {
	docu(name).execCommand('backcolor', false, value);
	foc(name);
}

function doLink(url, desc, title, name) {
	
	if (is_ie){

		docu(name).execCommand('createlink');

	} else {

		docu(name).execCommand('createlink',false,url);
		
		//text = docu(name).body.innerHTML;
		//text +=	'<a href="'+url+'" title="'+title+'">'+desc+'</a>'; 
		//docu(name).body.innerHTML =	text;
		//alert("pom");

	}
	foc(name);
}

function doImage(image_name, image_alt, image_align, image_border, name) {	

	document.form1.f_image.value= image_name;

	text = docu(name).body.innerHTML;
	text +=	'[[image:'+image_name+':'+image_alt+':'+image_align+':'+image_border+']]'; 
	docu(name).body.innerHTML =	text;
	foc(name);
}

function doPopupImage(image_name, image_alt, image_align, f_popup_descr, image_border, name) {	
		
	document.form1.f_image.value= image_name;
	document.form1.f_hasthumb.value = f_popup_descr;

	text = docu(name).body.innerHTML;
	text +=	'[[popup:'+image_name+':'+f_popup_descr+':'+image_alt+':'+image_align+':'+image_border+']]';	
	docu(name).body.innerHTML = text;
	foc(name);
}

function doDownload(file_name, f_icon, f_text, f_title, name) {	

	if (f_icon == 'icon') { f_text = ''; }
	text = docu(name).body.innerHTML;
	text +=	'[[download:'+file_name+':'+f_icon+':'+f_text+':'+f_title+']]';
	docu(name).body.innerHTML = text;
	foc(name);

}


function doFont(fName, name) {
	if(fName !=	'')
		docu(name).execCommand('fontname', false,	fName);
}

function doSize(fSize, name) {
	if(fSize !=	'')
	docu(name).execCommand('fontsize', false,	fSize);
}

function doHead(hType, name) {

	if ( (hType=='<p>')|| (hType=='<pre>') || (hType=='<h1>') || (hType=='<h2>') || (hType=='<h3>') )  {
		docu(name).execCommand('formatblock',	false, hType);	
	} else {
		docu(name).execCommand(hType, false, null);
	}
	foc(name);
}

function doToggleView(name) {	 


	if(document.getElementById(name+'tblCtrls').style.display != 'none') {

		if (is_ie) {
			iHTML	= docu(name).body.innerHTML;
			docu(name).body.innerText	= iHTML;
		} else {
			var html = document.createTextNode(docu(name).body.innerHTML);
			docu(name).body.innerHTML = "";
			docu(name).body.appendChild(html);
		}

		document.getElementById(name+'tblCtrls').style.display = 'none';
		
	} else {

		if (is_ie) {
			iText	= docu(name).body.innerText;
			docu(name).body.innerHTML	= iText;
		} else {
			var html = docu(name).body.ownerDocument.createRange();
			html.selectNodeContents(docu(name).body);
			docu(name).body.innerHTML = html.toString();
		}

		document.getElementById(name+'tblCtrls').style.display = 'inline';

	}

	foc(name);
}

// used only for compatibility with the non-wysi editor..
var g_txtarea="";

function getSel(name) {

	if (!document.getSelection) {
    return "";
  } else {
		return docu(name).getSelection();
	}

}

