<script language="JavaScript" type="text/javascript">
<!--
function emoticon(text) {
	var txtarea = document.post.newspost;
	text = ' ' + text + ' ';
	if (txtarea.createTextRange && txtarea.caretPos) {
		var caretPos = txtarea.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text + ' ' : text;
		txtarea.focus();
	} else {
		txtarea.value  += text;
		txtarea.focus();
	}
}

function storeCaret(text) {
	if (text.createTextRange) text.caretPos = document.selection.createRange().duplicate();
}
// -->
</script>
<a href="javascript:emoticon(':sweden:')"><img src="gfx/flags/se.gif" border="0" alt="Sweden" title="Sweden" /></a>