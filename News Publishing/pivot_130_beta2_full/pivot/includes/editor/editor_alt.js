
function foc(name) {
	document.getElementById(name).focus();
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


// Here, the actual button functions start


function RTB_SetFontForeColor(name,value) {
	wrapTag(name, '<span style="color:'+value+';">', '</span>');
}


function RTB_SetFontBgColor(name,value) {
	wrapTag(name, '<span style="bg-color:'+value+';">', '</span>');
}


function doLink(url, text, title, name, padding) {
	if (document.form1.convert_lb.value == 2) {
		if (title.length > 2) {
			setSel(name, '"' + text + '(' + title +')":'+ url + padding);
		} else {
			setSel(name, '"' + text + '":'+ url + padding);
		}
	} else {
		setSel(name, '<a href="'+url+'" title="'+title+'">'+ text +'</a>'+ padding);
	}
}


function doImage(image_name, image_alt, image_align, image_border, name) {	

	document.form1.f_image.value= image_name;
	document.form1.f_hasthumb.value= image_alt;

	text =	'[[image:'+image_name+':'+image_alt+':'+image_align+':'+image_border+']]'; 
	setSel(name, text);
}


function doPopupImage(image_name, image_alt, image_align, f_popup_descr, image_border, name) {	

	document.form1.f_image.value= image_name;
	document.form1.f_hasthumb.value= image_alt;

	text =	'[[popup:'+image_name+':'+f_popup_descr+':'+image_alt+':'+image_border+':'+image_align+']]';	
	setSel(name, text);

}


function doDownload(file_name, f_icon, f_text, f_title, name) {	

	if (f_icon == 'icon') { f_text = ''; }
	text =	'[[download:'+file_name+':'+f_icon+':'+f_text+':'+f_title+']]';
	setSel(name, text);

}


function doHead(hType, name) {
	
	// If textile is enabled..
	if (document.form1.convert_lb.value == 2) {
	
		switch (hType) {
			case 'bold': { wrapTag(name, "*", "*"); } 
			break;
			case 'italic': { wrapTag(name, "_", "_"); } 
			break;
			case '<p>': { wrapTag(name, "\n", "\n"); } 
			break;
			case '<pre>': { wrapTag(name, "<pre>", "</pre>"); } 
			break;
			case 'indent': { wrapTag(name, "bq.", ""); } 
			break;
			case 'justifyleft': { wrapTag(name, "p<. ", ""); } 
			break;
			case 'center': { wrapTag(name, "p=. ", ""); } 
			break;
			case 'justifyright': { wrapTag(name, "p>. ", ""); } 
			break;
			case 'inserthorizontalrule': { wrapTag(name, "<hr>", ""); } 
			break;
			case 'insertorderedlist': { wrapTag(name, "# ", ""); } 
			break;
			case 'insertunorderedlist': { wrapTag(name, "*", ""); } 
			break;
			case 'underline': { wrapTag(name, "+", "+"); } 
			break;
			case 'strikethrough': { wrapTag(name, "-", "-"); } 
			break;
			case '<h1>': { wrapTag(name, "h1. ", ""); } 
			break;
			case '<h2>': { wrapTag(name, "h2. ", ""); } 
			break;
			case '<h3>': { wrapTag(name, "h3. ", ""); } 
			break;
			case 'removeformat': { alert("Doesn't work yet in non-wysiwyg mode"); } 	
			break;
			default : {	alert("Doesn't work yet in non-wysiwyg mode"); }
		}
	} else {
		// no textile : Normal html..
	
		switch (hType) {
			case 'bold': { wrapTag(name, "<b>", "</b>"); } 
			break;
			case 'italic': { wrapTag(name, "<i>", "</i>"); } 
			break;
			case '<p>': { wrapTag(name, "<p>", "</p>"); } 
			break;
			case '<pre>': { wrapTag(name, "<pre>", "</pre>"); } 
			break;
			case 'indent': { wrapTag(name, "<blockquote><p>", "</p></blockquote>"); } 
			break;
			case 'justifyleft': { wrapTag(name, "<p align='left'>", "</p>"); } 
			break;
			case 'center': { wrapTag(name, "<p align='center'>", "</p>"); } 
			break;
			case 'justifyright': { wrapTag(name, "<p align='right'>", "</p>"); } 
			break;
			case 'inserthorizontalrule': { wrapTag(name, "<hr>", ""); } 
			break;
			case 'insertorderedlist': { wrapTag(name, "<ol><li>", "</ol>"); } 
			break;
			case 'insertunorderedlist': { wrapTag(name, "<ul><li>", "</ul>"); } 
			break;
			case 'underline': { wrapTag(name, "<u>", "</u>"); } 
			break;
			case 'strikethrough': { wrapTag(name, "<s>", "</s>"); } 
			break;
			case '<h1>': { wrapTag(name, "<h1>", "</h1>"); } 
			break;
			case '<h2>': { wrapTag(name, "<h2>", "</h2>"); } 
			break;
			case '<h3>': { wrapTag(name, "<h3>", "</h3>"); } 
			break;
			case 'removeformat': { alert("Doesn't work yet in non-wysiwyg mode"); } 	
			break;
			default : {	alert("Doesn't work yet in non-wysiwyg mode"); }
		}
		
		
		
	}


	foc(name);
}




/* 
	Processing code. used to wrap stuff in the textarea.

	Produced by Julian Bond
	julian_bond@voidstar.com

*/

var g_txtarea = "";

// IE only - stores the current cursor position on any textarea activity
  function storeCaret (txtarea) { 
		//g_txtarea = txtarea.name;
    //if (txtarea.createTextRange) { 
    //  txtarea.caretPos = document.selection.createRange().duplicate();
    //} 
		
  } 

// IE only - wraps selected text with lft and rgt
  function WrapIE(txtarea, lft, rgt) {

    strSelection = document.selection.createRange().text;
    if (strSelection!="") {
			// properly insert it
      document.selection.createRange().text = lft + strSelection + rgt;
    } else {
			// append it..
			document.form1[txtarea].value = document.form1[txtarea].value + lft + rgt;
		}
  }

// Moz only - wraps selected text with lft and rgt
  function WrapMoz(name, lft, rgt) {

		var txtarea = document.form1[name];

    var selLength = txtarea.textLength;
    var selStart = txtarea.selectionStart;
    var selEnd = txtarea.selectionEnd;
    if (selEnd==undefined || selEnd==1 || selEnd==2) {
    
    	txtarea.value = txtarea.value + lft +  rgt;
    
    } else {
 	   var s1 = (txtarea.value).substring(0,selStart);
   	 var s2 = (txtarea.value).substring(selStart, selEnd);
  	  var s3 = (txtarea.value).substring(selEnd, selLength);
   
 		// if you doubleclick a word in moz, it also selects the trailing space.
 		// to compensate, we move the space a bit. 		
 		if (s2[s2.length-1]==" ") {
 			s2 = s2.substr(0, s2.length-1);
	    txtarea.value = s1 + lft + s2 + rgt + " " + s3;
		} else {
			txtarea.value = s1 + lft + s2 + rgt + s3;
		}
	}
  }


	// others only - if we can not wrap, just insert both tags.
  function WrapDefault(name, lft, rgt) {

	var txtarea = document.form1[name];
  txtarea.value = txtarea.value + lft + rgt;

  }


  
// Chooses technique based on browser
  function wrapTag(txtarea, lft, rgt) {
    if (is_gecko) {
			WrapMoz(txtarea, lft, rgt);
    } else {
			
			// need to check this for Safari
			if (document.selection) {
				WrapIE(txtarea, lft, rgt);
			} else {
				WrapDefault(txtarea, lft, rgt)
			}
		}
  }


	function getMoz(name) {

	var txtarea = document.form1[name];
	var selLength = txtarea.textLength;
    var selStart = txtarea.selectionStart;
    var selEnd = txtarea.selectionEnd;
    if (selEnd==1 || selEnd==2) selEnd=selLength;
    var s2 = (txtarea.value).substring(selStart, selEnd)
		return s2;
	}



  function getIE() {

    return document.selection.createRange().text;

  }


	function getSel(txtarea) {
		if (is_gecko) {
			return getMoz(txtarea);
    } else {
			return getIE();
		}
	}



function setMoz(name, text) {

	var txtarea = document.form1[name];

	var selLength = txtarea.textLength;
	var selStart = txtarea.selectionStart;
	var selEnd = txtarea.selectionEnd;
	if (selEnd==1 || selEnd==2) selEnd=selLength;
	var s1 = (txtarea.value).substring(0,selStart);
	var s3 = (txtarea.value).substring(selEnd, selLength);
	txtarea.value = s1 + text + s3;

}

function setIE(txtarea, text) {

	strSelection = document.selection.createRange().text;
	if (strSelection!="") {
		document.selection.createRange().text = text;
	} else {
		document.form1[txtarea].value = document.form1[txtarea].value + text;
	}
}


	function setSel(txtarea, text) {
		if (is_gecko) {
			return setMoz(txtarea, text);
    } else {
			return setIE(txtarea, text);
		}
	}

// Get a link via a prompt and wrap selected text
  function wrapWithLink(txtarea) {
    var my_link = prompt("URL:","http://");
    if (my_link != null) {
      lft="<a href=\"" + my_link + "\">";
      rgt="</a>";
      wrapTag(txtarea, lft, rgt);
    }
    return;
  }

// IE only - Insert text at caret position or at start of selected text
  function insertIE (txtarea, text) {
    if (txtarea.createTextRange && txtarea.caretPos) { 
      var caretPos = txtarea.caretPos; 
      caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text+caretPos.text + ' ' : text+caretPos.text;
    } else {
      txtarea.value = txtarea.value + text; 
    }
    return;
  } 

// Moz only - Insert text at caret position or at start of selected text
  function insertMoz(txtarea , lft) {
    var rgt="";
    wrapTag(txtarea, lft, rgt);
    return;
  }

// Switch function based on browser - Insert text at caret position or at start of selected text
  function insertTag(txtarea , lft) {
    if (document.all) {
      insertIE(txtarea, lft);
    }
    else if (document.getElementById) {
      insertMoz(txtarea, lft);
    }
  }
    
// prompt for image name. Insert image at caret position or at start of selected text
  function insertImage(txtarea) {
    var my_link = prompt("IMG URL:","http://");
    if (my_link != null) {
      lft="<img src=\"" + my_link + "\" />";
      insertTag(txtarea, lft);
    }
    return;
  }

/*
	End Processing code.
*/
