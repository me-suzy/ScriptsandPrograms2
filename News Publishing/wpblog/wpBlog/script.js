function storeCaret (textEl) {
	if (textEl.createTextRange) 
		textEl.caretPos = document.selection.createRange().duplicate();
}
function insertAtCaret (textEl, text) {
	if (textEl.createTextRange && textEl.caretPos) {
		var caretPos = textEl.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
	}
	else
		textEl.value  = text;
}
function brfunc(){
	insertAtCaret (document.getElementById('contenth'),"<br />");
	document.getElementById('contenth').focus();
}
function bold(){
	var text = prompt("What text do you want to bold?","");
	if ( text != null ) {
		var stuff = "<b>"+text+"</b>";
		insertAtCaret (document.getElementById('contenth'), stuff);
		document.getElementById('contenth').focus();
	}
}
function italics(){
	var text = prompt("What text do you want to italicize?","");
	if ( text != null ) {
		var stuff = "<i>"+text+"</i>";
		insertAtCaret (document.getElementById('contenth'), stuff);
		document.getElementById('contenth').focus();
	}
}
function link(){
	var text = prompt("What is the URL of the link?","");
	if ( text != null ) {
		var stuff = "<a href=\""+text+"\">"+text+"</a>";
		insertAtCaret (document.getElementById('contenth'), stuff);
		document.getElementById('contenth').focus();
	}
}