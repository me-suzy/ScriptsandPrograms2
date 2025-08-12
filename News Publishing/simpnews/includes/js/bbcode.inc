<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="JavaScript">
<!--
function openWindow3(loc, windowID, top, left, width, height)
{
	openWin = openWindow_full(loc,windowID,20,100,500,450,0,1,0,0,1,0,0,0,1);
}
function openWindow_full(loc, windowid, top, left, width, height, buttons, scrollbars, location, menubar, resizeable, status, directories, toolbar, autoclose)
{
	var options="top="+top+",left="+left+",width="+width+",height="+height;
	if(buttons==1)
		options+=",buttons=yes";
	else
		options+=",buttons=no";
	if(scrollbars==1)
		options+=",scrollbars=yes";
	else
		options+=",scrollbars=no";
	if(location==1)
		options+=",location=yes";
	else
		options+=",location=no";
	if(menubar==1)
		options+=",menubar=yes";
	else
		options+=",menubar=no";
	if(resizeable==1)
		options+=",resizeable=yes";
	else
		options+=",resizeable=no";
	if(status==1)
		options+=",status=yes";
	else
		options+=",status=no";
	if(directories==1)
		options+=",directories=yes";
	else
		options+=",directories=no";
	if(toolbar==1)
		options+=",toolbar=yes";
	else
		options+=",toolbar=no";
	openWin = window.open(loc,windowid,options);
	openWin.focus()
	if (autoclose==1)
	{
		window.onunload = function(){openWin.close()}
	}
}
function listStart(inputfield) {
	var revisedText;
	eval('currentText = document.inputform.'+inputfield+'.value;');
	revisedText = currentText+"[list]";
	eval('document.inputform.'+inputfield+'.value=revisedText;');
	eval('document.inputform.'+inputfield+'.focus();');
	return;
}
function listEnd(inputfield) {
	var revisedText;
	eval('currentText = document.inputform.'+inputfield+'.value;');
	revisedText = currentText+"[/list]";
	eval('document.inputform.'+inputfield+'.value=revisedText;');
	eval('document.inputform.'+inputfield+'.focus();');
	return;
}
function colorThis(strColor,inputfield) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	strSelection = document.selection.createRange().text;\n";
?>
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
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
		echo "	eval('document.inputform.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function listItem(inputfield) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_entertext?>:","");
		if (strSelection == null) return;
	}
	var addText = "[*]" + strSelection;
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	document.selection.createRange().text = addText;\n";
	else
		echo "	eval('document.inputform.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function changeFont(font,inputfield) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
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
		echo "	eval('document.inputform.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function fontSize(strSize,inputfield) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
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
		echo "	eval('document.inputform.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function setAlignment(alignment,inputfield) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
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
		echo "	eval('document.inputform.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function bold(inputfield) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
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
		echo "	eval('document.inputform.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function italic(inputfield) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
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
		echo "	eval('document.inputform.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function typewriter(inputfield) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
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
		echo "	eval('document.inputform.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function center(inputfield) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
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
		echo "	eval('document.inputform.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function subscript(inputfield) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
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
		echo "	eval('document.inputform.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function superscript(inputfield) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
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
		echo "	eval('document.inputform.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function underline(inputfield) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
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
		echo "	eval('document.inputform.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function strike(inputfield) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
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
		echo "	eval('document.inputform.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function code(inputfield) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
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
		echo "	eval('document.inputform.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function quote(inputfield) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
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
		echo "	eval('document.inputform.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function aHref(inputfield) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
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
		echo "	eval('document.inputform.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function email(inputfield) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_enteremail?>:","");
		if(strSelection == null) return;
	}
	var addText = "[email]" + strSelection + "[/email]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	document.selection.createRange().text = addText;\n";
	else
		echo "	eval('document.inputform.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function pic(inputfield) {
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "strSelection = document.selection.createRange().text;\n";
?>	
	if (strSelection == "") {
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
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
		echo "	eval('document.inputform.'+inputfield+'.value += addText;');\n";
?>
	return;
}
function add_emoticon(code,inputfield)
{
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
	{
?>
	strSelection = document.selection.createRange().text;
	if (strSelection == "") {
		eval('document.inputform.'+inputfield+'.focus();');
	}
	document.selection.createRange().text = ' '+code+' ';
<?php
	}
	else
	{
		echo "	addText = ' ' + code + ' ';\n";
		echo "	eval('document.inputform.'+inputfield+'.value += addText;');\n";
	}
?>
	return;
}
function helpline(helptext) {
<?php
	if(is_ns4())
		echo "	return;\n";
	else
		echo "	document.inputform.helpbox.value = helptext;\n";
?>
}
function highlightbutton(helptext,imgname,picfilename) {
	helpline(helptext);
	highlightpic='gfx/bbcodes/highlight/'+picfilename;
	eval("document."+imgname+".src='"+highlightpic+"';");
}
function unhighlightbutton(helptext,imgname,picfilename) {
	helpline(helptext);
	highlightpic='gfx/bbcodes/normal/'+picfilename;
	eval("document."+imgname+".src='"+highlightpic+"';");
}
function bbc_imgpreload(){
<?php
	include_once('includes/bbcode_pics.inc');
	for($i=0;$i<count($bbcode_pics);$i++)
	{
		echo "bbchimg".$i." = new Image();\n";
		echo "bbchimg".$i.".src = 'gfx/bbcodes/highlight/".$bbcode_pics[$i]."';\n";
	}
?>
}
//-->
</script>
