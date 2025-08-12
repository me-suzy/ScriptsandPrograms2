<script type="text/javascript">
function go(){
var URL = document.drop.forum.options[document.drop.forum.selectedIndex].value;
window.location.href = URL;
}
</script>

<?
if($script == "textbox") {
echo "     <script type=\"text/javascript\">
     function storeCaret (textEl) {
	if (textEl.createTextRange) textEl.caretPos = document.selection.createRange().duplicate();
     }
function addcode(thecode) {
		document.msgform.message.value += thecode;
		document.msgform.message.focus();
		return;
	storeCaret(document.msgform.message);
}
</script>";
}
?>