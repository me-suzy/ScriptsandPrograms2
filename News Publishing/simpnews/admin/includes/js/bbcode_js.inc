<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="JavaScript">
<!--
function listStart(inputfield,formname) {
	var revisedText;
	eval('currentText = document.'+formname+'.'+inputfield+'.value;');
	revisedText = currentText+"[list]";
	eval('document.'+formname+'.'+inputfield+'.value=revisedText;');
	eval('document.'+formname+'.'+inputfield+'.focus();');
	return;
}
function listEnd(inputfield,formname) {
	var revisedText;
	eval('currentText = document.'+formname+'.'+inputfield+'.value;');
	revisedText = currentText+"[/list]";
	eval('document.'+formname+'.'+inputfield+'.value=revisedText;');
	eval('document.'+formname+'.'+inputfield+'.focus();');
	return;
}
function colorThis(strColor,inputfield,formname) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	strSelection = document.selection.createRange().text;\n";
?>
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.'+formname+'.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_entertext?>:","");
		if(strSelection == null)
			return;
	}
	if(strColor==null)
	{
		strColor = prompt("<?php echo $l_entercolor?>:","");
		if (strColor == null)
			return;
	}
	var addText='[color=' +strColor + ']' + strSelection + '[/color]';
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	document.selection.createRange().text = addText;\n";
	else
		echo "	eval('document.'+formname+'.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function listItem(inputfield,formname) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.'+formname+'.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_entertext?>:","");
		if (strSelection == null) return;
	}
	var addText = "[*]" + strSelection;
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	document.selection.createRange().text = addText;\n";
	else
		echo "	eval('document.'+formname+'.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function changeFont(font,inputfield,formname) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.'+formname+'.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_entertext?>:","");
		if(strSelection == null) return;
	}
	if(font==null)
	{
		font = prompt("<?php echo $l_enterfont?>:","");
		if (font == null)
			return;
	}
	var addText = "[font="+font+"]" + strSelection + "[/font]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	document.selection.createRange().text = addText;\n";
	else
		echo "	eval('document.'+formname+'.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function fontSize(strSize,inputfield,formname) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.'+formname+'.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_entertext?>:","");
		if(strSelection == null) return;
	}
	if(strSize==null)
	{
		strSize = prompt("<?php echo $l_entertextsize?>:","");
		if (strSize == null)
			return;
	}
	var addText = "[size=" +strSize + "]" + strSelection + "[/size]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	document.selection.createRange().text = addText;\n";
	else
		echo "	eval('document.'+formname+'.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function setAlignment(alignment,inputfield,formname) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.'+formname+'.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_entertext?>:","");
		if(strSelection == null)
			return;
	}
	if(alignment==null)
	{
		alignment = prompt("<?php echo $l_alignment?>:","");
		if (alignment == null)
			return;
	}
	var addText = "[align="+alignment+"]" + strSelection + "[/align]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	document.selection.createRange().text = addText;\n";
	else
		echo "	eval('document.'+formname+'.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function bold(inputfield,formname) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.'+formname+'.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var addText = "[b]" + strSelection + "[/b]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	document.selection.createRange().text = addText;\n";
	else
		echo "	eval('document.'+formname+'.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function underline(inputfield,formname) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.'+formname+'.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var addText = "[u]" + strSelection + "[/u]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	document.selection.createRange().text = addText;\n";
	else
		echo "	eval('document.'+formname+'.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function italic(inputfield,formname) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.'+formname+'.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var addText = "[i]" + strSelection + "[/i]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	document.selection.createRange().text = addText;\n";
	else
		echo "	eval('document.'+formname+'.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function typewriter(inputfield,formname) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.'+formname+'.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var addText = "[tt]" + strSelection + "[/tt]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	document.selection.createRange().text = addText;\n";
	else
		echo "	eval('document.'+formname+'.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function center(inputfield,formname) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.'+formname+'.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var addText = "[center]" + strSelection + "[/center]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	document.selection.createRange().text = addText;\n";
	else
		echo "	eval('document.'+formname+'.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function subscript(inputfield,formname) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.'+formname+'.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var addText = "[sub]" + strSelection + "[/sub]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	document.selection.createRange().text = addText;\n";
	else
		echo "	eval('document.'+formname+'.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function superscript(inputfield,formname) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.'+formname+'.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var addText = "[sup]" + strSelection + "[/sup]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	document.selection.createRange().text = addText;\n";
	else
		echo "	eval('document.'+formname+'.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function strike(inputfield,formname) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.'+formname+'.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var addText = "[s]" + strSelection + "[/s]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	document.selection.createRange().text = addText;\n";
	else
		echo "	eval('document.'+formname+'.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function code(inputfield,formname) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.'+formname+'.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var addText = "[code]" + strSelection + "[/code]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	document.selection.createRange().text = addText;\n";
	else
		echo "	eval('document.'+formname+'.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function quote(inputfield,formname) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.'+formname+'.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var addText = "[quote]" + strSelection + "[/quote]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	document.selection.createRange().text = addText;\n";
	else
		echo "	eval('document.'+formname+'.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function aHref(inputfield,formname) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.'+formname+'.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_entertext?>:","");
		if(strSelection == null) return;
	}
	strHref = prompt("<?php echo $l_enterlink?>:","http://");
	if (strHref == null)
		return;
	var addText = "[url=" +strHref + "]" + strSelection + "[/url]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	document.selection.createRange().text = addText;\n";
	else
		echo "	eval('document.'+formname+'.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function email(inputfield,formname) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.'+formname+'.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_enteremail?>:","");
		if(strSelection == null) return;
	}
	var addText = "[email]" + strSelection + "[/email]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	document.selection.createRange().text = addText;\n";
	else
		echo "	eval('document.'+formname+'.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function pic(inputfield,formname) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.'+formname+'.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_enterurl?>:","http://");
		if (strSelection == null)
			return;
	}
	var addText = "[img]" + strSelection + "[/img]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	document.selection.createRange().text = addText;\n";
	else
		echo "	eval('document.'+formname+'.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function add_emoticon(code,inputfield,formname)
{
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
	{
?>
	strSelection = document.selection.createRange().text;
	if (strSelection == "") {
		eval('document.'+formname+'.'+inputfield+'.focus();');
	}
	document.selection.createRange().text = ' '+code+' ';
<?php
	}
	else
	{
		echo "	addText = ' ' + code + ' ';\n";
		echo "	eval('document.'+formname+'.'+inputfield+'.value += addText;');\n";
	}
?>
	return;
}
function helpline(helptext,formname,inputfield) {
<?php
	if(is_ns4())
		echo "	return;\n";
	else
	{
		echo "	eval('document.'+formname+'.'+inputfield+'_helpbox.value = helptext;');\n";
		echo "	return;\n";
	}
?>
}
function highlightbutton(helptext,formname,inputfield,imgname,picfilename) {
	helpline(helptext,formname,inputfield);
	highlightpic='../gfx/bbcodes/highlight/'+picfilename;
	eval("document."+imgname+".src='"+highlightpic+"';");
}
function unhighlightbutton(helptext,formname,inputfield,imgname,picfilename) {
	helpline(helptext,formname,inputfield);
	highlightpic='../gfx/bbcodes/normal/'+picfilename;
	eval("document."+imgname+".src='"+highlightpic+"';");
}
function bbc_imgpreload(){
<?php
	include_once('../includes/bbcode_pics.inc');
	for($i=0;$i<count($bbcode_pics);$i++)
	{
		echo "bbchimg".$i." = new Image();\n";
		echo "bbchimg".$i.".src = '../gfx/bbcodes/highlight/".$bbcode_pics[$i]."';\n";
	}
?>
}
//-->
</script>
