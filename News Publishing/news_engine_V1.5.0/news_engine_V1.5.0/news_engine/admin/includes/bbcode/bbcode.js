tags = new Array();

function getarraysize(thearray) {
	for (i = 0; i < thearray.length; i++) {
		if ((thearray[i] == "undefined") || (thearray[i] == "") || (thearray[i] == null))
			return i;
		}
	return thearray.length;
}

function arraypush(thearray,value) {
	thearraysize = getarraysize(thearray);
	thearray[thearraysize] = value;
}

function arraypop(thearray) {
	thearraysize = getarraysize(thearray);
	retval = thearray[thearraysize - 1];
	delete thearray[thearraysize - 1];
	return retval;
}

// *******************************************************

function setmode(modevalue) {
	document.cookie = "asebbcodemode="+modevalue+"; path=/; expires=Wed, 1 Jan 2020 00:00:00 GMT;";
}

function normalmode(theform) {
	if (theform.mode[0].checked) return true;
	else return false;
}

function stat(thevalue) {
	document.alp.status.value = eval(thevalue+"_text");
}

// *******************************************************

function closetag(theform) {
	if (normalmode(theform))
		stat('enhanced_only');
	else
		if (tags[0]) {
			theform.newstext.value += "[/"+ arraypop(tags) +"]";
			}
		else {
			stat('no_tags');
			}
	theform.message.focus();
}

function closeall(theform) {
	if (normalmode(theform))
		stat('enhanced_only');
	else {
		if (tags[0]) {
			while (tags[0]) {
				theform.newstext.value += "[/"+ arraypop(tags) +"]";
				}
			theform.newstext.value += " ";
			}
		else {
			stat('no_tags');
			}
		}
	theform.newstext.focus();
}

// *******************************************************geht
var text = "";
AddTxt = "";
function getActiveText(selectedtext) { 
	text = (document.all) ? document.selection.createRange().text : document.getSelection();
	if (selectedtext.createTextRange) {
    	selectedtext.caretPos = document.selection.createRange().duplicate();
	}
	return true;
}

function AddText(NewCode,theform) {
	if (theform.newstext.createTextRange && theform.newstext.caretPos) {
		var caretPos = theform.newstext.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? NewCode + ' ' : NewCode;
	} else {
		theform.newstext.value+=NewCode
	}
	setfocus(theform);
	AddTxt = "";
}


function setfocus(theform) {
theform.newstext.focus();
}

function bbcode(theform,bbcode,prompttext) {
	if ((normalmode(theform)) || (bbcode=="IMG")) {
		if (text) { var dtext=text; } else { var dtext=prompttext; }
		inserttext = prompt(tag_prompt+"\n["+bbcode+"]xxx[/"+bbcode+"]",dtext);
		if ((inserttext != null) && (inserttext != ""))
			AddTxt = "["+bbcode+"]"+inserttext+"[/"+bbcode+"] ";
			AddText(AddTxt,theform);
			
		}
	else {
		donotinsert = false;
		for (i = 0; i < tags.length; i++) {
			if (tags[i] == bbcode)
				donotinsert = true;
			}
		if (donotinsert)
			stat("already_open");
		else {
			theform.newstext.value += "["+bbcode+"]";
			arraypush(tags,bbcode);
			}
		}
	theform.newstext.focus();
}

function bbimg(theform,bbcode,prompttext) {
	if ((normalmode(theform)) || (bbcode=="IMG")) {
		if (text) { var dtext=text; } else { var dtext=prompttext; }
		inserttext = prompt(img_prompt,dtext);
		if ((inserttext != null) && (inserttext != ""))
			AddTxt = "["+bbcode+"="+inserttext+"] ";
			AddText(AddTxt,theform);
			
		}
	else {
		donotinsert = false;
		for (i = 0; i < tags.length; i++) {
			if (tags[i] == bbcode)
				donotinsert = true;
			}
		if (donotinsert)
			stat("already_open");
		else {
			theform.newstext.value += "["+bbcode+"]";
			arraypush(tags,bbcode);
			}
		}
	theform.newstext.focus();
}

function bbco(theform,bbcode,prompttext) {
	if ((normalmode(theform)) || (bbcode=="IMG")) {
		if (text) { var dtext=text; } else { var dtext=prompttext; }
		inserttext = prompt(tag_prompt+"\n["+bbcode+"]xxx[/"+bbcode+"]",dtext);
		if ((inserttext != null) && (inserttext != ""))
			AddTxt = "["+bbcode+"]"+inserttext+"[/"+bbcode+"] ";
			AddText(AddTxt,theform);
			
		}
	else {
		donotinsert = false;
		for (i = 0; i < tags.length; i++) {
			if (tags[i] == bbcode)
				donotinsert = true;
			}
		if (donotinsert)
			stat("already_open");
		else {
			theform.newstext.value += "["+bbcode+"]";
			arraypush(tags,bbcode);
			}
		}
	theform.newstext.focus();
}

// *******************************************************

function fontformat(theform,thevalue,thetype) {
	if (normalmode(theform)) {
		if (thevalue != 0) {
			if (text) { var dtext=text; } else { var dtext=""; }
			inserttext = prompt(font_formatter_prompt+" "+thetype,dtext);
			if ((inserttext != null) && (inserttext != ""))
				AddTxt = "["+thetype+"="+thevalue+"]"+inserttext+"[/"+thetype+"] ";
				AddText(AddTxt,theform);
				
			}
		}
	else {
		theform.newstext.value += "["+thetype+"="+thevalue+"]";
		arraypush(tags,thetype);
		}
	theform.sizeselect.selectedIndex = 0;
	theform.fontselect.selectedIndex = 0;
	theform.colorselect.selectedIndex = 0;
	theform.newstext.focus();
}

// *******************************************************geht

function namedlink(theform,thetype) {
	if (text) { var dtext=text; } else { var dtext=""; }
	linktext = prompt(link_text_prompt,dtext);
		var prompttext;
		if (thetype == "url") {
			prompt_text = link_url_prompt;
			prompt_contents = "http://";
			}
		else {
			prompt_text = link_email_prompt;
			prompt_contents = "hierdie@adresse.de";
			thetype = "email";
			}
	linkurl = prompt(prompt_text,prompt_contents);
	if ((linkurl != null) && (linkurl != "")) {
		if ((linktext != null) && (linktext != "")) {
			AddTxt = "["+thetype+"="+linkurl+"]"+linktext+"[/"+thetype+"] ";
			AddText(AddTxt,theform);
			
			}
		else{
			AddTxt = "["+thetype+"]"+linkurl+"[/"+thetype+"] ";
			AddText(AddTxt,theform);
			
		}
	}
}

// *******************************************************

function dolist(theform) {
	listtype = prompt(list_type_prompt, "");
	if ((listtype == "ol") || (listtype == "ul")) {
		thelist = "[list="+listtype+"]\n";
		listend = "[/list] ";
		}
	else {
		thelist = "[list]\n";
		listend = "[/list] ";
		}
	listentry = "initial";
	while ((listentry != "") && (listentry != null)) {
		listentry = prompt(list_item_prompt, "");
		if ((listentry != "") && (listentry != null))
			thelist = thelist+"[*]"+listentry+"\n";
		}
	AddTxt = thelist+listend;
	AddText(AddTxt,theform);

}

// *******************************************************

function standard(theform,text) {
	text = ' ' + text + ' ';
	if (theform.newstext.createTextRange && theform.newstext.caretPos) {
		var caretPos = theform.newstext.caretPos;

		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? caretPos.text + text + ' ' : caretPos.text + text;
		theform.newstext.focus();
	} else {
		var selStart = theform.newstext.selectionStart;
		var selEnd = theform.newstext.selectionEnd;

		mozWrap(theform.newstext, text, '')
		theform.newstext.focus();
		theform.newstext.selectionStart = selStart + text.length;
		theform.newstext.selectionEnd = selEnd + text.length;
	}
}

// *******************************************************

function gethelp() {
	alert(HELPTEXT);
}

// ******************************************************

var neu = null;
function OpenWindow(theUrl,scrollbars) {
    neu = window.open('', 'Neues', 'width=600,height=500,scrollbars='+scrollbars+'');
    if (neu != null) {
    if (neu.opener == null) {
        neu.opener = self;
        }
    neu.location.href = theUrl;
    }
}

// ******************************************************

// From http://www.massless.org/mozedit/
function mozWrap(txtarea, open, close) {
	var selLength = txtarea.textLength;
	var selStart = txtarea.selectionStart;
	var selEnd = txtarea.selectionEnd;
	if (selEnd == 1 || selEnd == 2) 
		selEnd = selLength;

	var s1 = (txtarea.value).substring(0,selStart);
	var s2 = (txtarea.value).substring(selStart, selEnd)
	var s3 = (txtarea.value).substring(selEnd, selLength);
	txtarea.value = s1 + open + s2 + close + s3;
	return;
}
