//============================================================+
// File name   : inserttag.js                             
// Begin       : 2001-10-25                                    
// Last Update : 2002-11-19                                    
//                                                             
// Description : Insert TAGS on Textarea Form (XHTML)                                  
//                                                             
//                                                             
// Author: Nicola Asuni                                        
//                                                             
// (c) Copyright:                                              
//               Tecnick.com S.r.l.                            
//               Via Ugo Foscolo n.19                          
//               09045 Quartu Sant'Elena (CA)                  
//               ITALY                                         
//               www.tecnick.com                               
//               info@tecnick.com                              
//============================================================+

// --------------------------------------------------------------------------
// Only works with IE (account for text selection)
// --------------------------------------------------------------------------
function FJ_store_caret (editText) {
	if (editText.createTextRange) {
		editText.caretPos = document.selection.createRange().duplicate();
	}
}
 
// --------------------------------------------------------------------------
// Create open and close tags and call display tag
// use '&' as first tag character to obtain closed tag
// --------------------------------------------------------------------------
function FJ_insert_tag(editText, tag) {
	var opentag = tag;
	var closetag = '';
	
	if (tag.charAt(opentag.length-2) != '/') {
		tmpstr = opentag.split(' ');
		if(opentag.charAt(0)=='<'){ //XHTML tag
			var closetag = '</'+tmpstr[0].substring(1,(tmpstr[0].length));
			if (closetag.charAt(closetag.length-1)!='>') {closetag += '>';} //HTML style close tag
		}
		else{ //AIOCP tag
			var closetag = '[/'+tmpstr[0].substring(1,(tmpstr[0].length));
			if (closetag.charAt(closetag.length-1)!=']') {closetag += ']';} //AIOCP code style close tag
		}
	}
	FJ_display_tag(editText, opentag, closetag);
	return;
}

// --------------------------------------------------------------------------
// Insert text befor selected text or ath the end of text
// --------------------------------------------------------------------------
function FJ_insert_text(editText, newtext) {
	FJ_display_tag(editText, newtext, '');
	return;
}

// --------------------------------------------------------------------------
// Insert open and close TAG on selected text
// --------------------------------------------------------------------------
function FJ_display_tag(editText, opentag, closetag) {
	if (editText.createTextRange && editText.caretPos) { // if text has been selected (only IE browser)
		var caretPos = editText.caretPos;
		caretPos.text = opentag+''+caretPos.text+''+closetag;
	}
	else { //text has not been selected or other browser than IE
		editText.value = editText.value+''+opentag+''+closetag;
	}
	return;
}
 
// --------------------------------------------------------------------------
// Replace new line with HTML equivalent
// --------------------------------------------------------------------------
function FJ_auto_br (editText) {
	editText.value  = editText.value.replace(/\r\n/gi, "<br />\r\n");
	return;
}

// --------------------------------------------------------------------------
// Compact code (remove tabs and newlines)
// --------------------------------------------------------------------------
function FJ_remove_indentation (editText) {
	editText.value  = editText.value.replace(/[\r\n]+\t/gi, "\t");
	editText.value  = editText.value.replace(/\t/gi, "");
	editText.value  = editText.value.replace(/>[\r\n]+</gi, "><");
	return;
}

// -------------------------------------------------------------------------
// END OF SCRIPT
// -------------------------------------------------------------------------