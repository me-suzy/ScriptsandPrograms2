<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
?>
<script type="text/javascript" language="JavaScript">
<!--
function lightCase(mycolor,inputfield)
{
	var field=document.getElementById(inputfield);
	field.bgColor=mycolor;
}
function lightCase1(mycolor, inputfield)
{
	var field=document.getElementById(inputfield);
	field.bgColor=mycolor;
}
function addspecialchar(specialchar,inputfield) {
	var revisedText;
	eval('currentText = document.inputform.'+inputfield+'.value;');
	revisedText = currentText+specialchar;
	eval('document.inputform.'+inputfield+'.value=revisedText;');
	eval('document.inputform.'+inputfield+'.focus();');
	return;
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
	var apptext = '[color=' +strColor + ']' + strSelection + '[/color]';
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}
function listItem(inputfield) {
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
		if (strSelection == null) return;
	}
	var apptext = "[*]" + strSelection;
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}
function changeFont(font,inputfield) {
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
		if(strSelection == null) return;
	}
	if(font==null)
	{
		font = prompt("<?php echo $l_enterfont?>:","");
		if (font == null)
			return;
	}
	var apptext = "[font="+font+"]" + strSelection + "[/font]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}
function fontSize(strSize,inputfield) {
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
		if(strSelection == null) return;
	}
	if(strSize==null)
	{
		strSize = prompt("<?php echo $l_entertextsize?>:","");
		if (strSize == null)
			return;
	}
	var apptext = "[size=" +strSize + "]" + strSelection + "[/size]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}
function setAlignment(alignment,inputfield) {
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
	if(alignment==null)
	{
		alignment = prompt("<?php echo $l_alignment?>:","");
		if (alignment == null)
			return;
	}
	var apptext = "[align="+alignment+"]" + strSelection + "[/align]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}
function underline(inputfield) {
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
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var apptext = "[u]" + strSelection + "[/u]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}
function bold(inputfield) {
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
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var apptext = "[b]" + strSelection + "[/b]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}
function italic(inputfield) {
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
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var apptext = "[i]" + strSelection + "[/i]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}
function typewriter(inputfield) {
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
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var apptext = "[tt]" + strSelection + "[/tt]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}
function center(inputfield) {
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
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var apptext = "[center]" + strSelection + "[/center]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}
function subscript(inputfield) {
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
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var apptext = "[sub]" + strSelection + "[/sub]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}
function superscript(inputfield) {
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
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var apptext = "[sup]" + strSelection + "[/sup]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}
function strike(inputfield) {
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
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var apptext = "[s]" + strSelection + "[/s]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}
function unc(inputfield) {
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
		strSelection = prompt("<?php echo $l_enterlink?>","");
		if (strSelection == null)
			return;
	}
	var apptext = "[unc]" + strSelection + "[/unc]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}
function code(inputfield) {
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
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var apptext = "[code]" + strSelection + "[/code]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}
function phpcode(inputfield) {
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
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var apptext = "[php]" + strSelection + "[/php]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}
function quote(inputfield) {
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
		strSelection = prompt("<?php echo $l_entertext?>","");
		if (strSelection == null)
			return;
	}
	var apptext = "[quote]" + strSelection + "[/quote]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}
function aHref(inputfield) {
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
		if(strSelection == null) return;
	}
	strHref = prompt("<?php echo $l_enterlink?>:","http://");
	if (strHref == null)
		return;
	var apptext = "[url=" +strHref + "]" + strSelection + "[/url]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}
function email(inputfield) {
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
		strSelection = prompt("<?php echo $l_enteremail?>:","");
		if(strSelection == null) return;
	}
	var apptext = "[email]" + strSelection + "[/email]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}
function pic(inputfield) {
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
		strSelection = prompt('<?php echo $l_enterurl?>:','http://');
		if (strSelection == null)
			return;
	}
	var apptext = "[img]" + strSelection + "[/img]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}
function helpline(helptext,inputfield) {
<?php
	if(is_ns4())
		echo "	return;\n";
	else
		echo "	eval('document.inputform.'+inputfield+'_helpbox.value = helptext');\n";
?>
}
function gfx_uploader(fieldname)
{
	var url='<?php echo do_url_session("gfx_upload.php?$langvar=$act_lang")?>';
	url +='&usedfield=' + fieldname;
	var selprog=document.inputform.selprog.value;
	var localdata=selprog.split("|");
	if(localdata[1]==null)
	{
		alert('<?php echo $l_sel_cat_subcat_first?>');
		return;
	}
	url +='&prognr=' + localdata[1];
	openWindow(url);
}
function gfx_uploader2(fieldname)
{
	var url='<?php echo do_url_session("gfx_upload.php?$langvar=$act_lang")?>';
	url +='&usedfield=' + fieldname;
	var selprog=document.inputform.selprog.value;
	if(selprog=="")
	{
		alert('<?php echo $l_sel_prog_first?>');
		return;
	}
	url +='&prognr=' + selprog;
	openWindow(url);
}

function faqref(refdata, inputfield) {
	var localdata=refdata.split("|");
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	strSelection = document.selection.createRange().text;\n";
?>
	if (strSelection == "")
	{
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_enterdisplaytext?>","");
		if(strSelection == null)
			return;
	}
	var apptext = "[faqref faq=" + localdata[0] + " cat=" + localdata[1] + " prog=" + localdata[2] + "]" + strSelection + "[/faqref]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}

function faqref2(refdata, inputfield, target) {
	var localdata=refdata.split("|");
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	strSelection = document.selection.createRange().text;\n";
?>
	if (strSelection == "")
	{
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_enterdisplaytext?>","");
		if(strSelection == null)
			return;
	}
	var apptext = "[faqref faq=" + localdata[0] + " cat=" + localdata[1] + " prog=" + localdata[2] + " target=" + target + "]" + strSelection + "[/faqref]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}

function kbref(refdata, inputfield) {
	var localdata=refdata.split("|");
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	strSelection = document.selection.createRange().text;\n";
?>
	if (strSelection == "")
	{
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_enterdisplaytext?>","");
		if(strSelection == null)
			return;
	}
	var apptext = "[kbref=" + localdata[0] + " prog=" + localdata[1]+ "]" + strSelection + "[/kbref]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}

function kbref2(refdata, inputfield, target) {
	var localdata=refdata.split("|");
	var strSelection="";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "	strSelection = document.selection.createRange().text;\n";
?>
	if (strSelection == "")
	{
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo "		eval('document.inputform.'+inputfield+'.focus();');\n";
?>
		strSelection = prompt("<?php echo $l_enterdisplaytext?>","");
		if(strSelection == null)
			return;
	}
	var apptext = "[kbref=" + localdata[0] + " prog=" + localdata[1]+ " target=" + target + "]" + strSelection + "[/kbref]";
<?php
	if(is_msie() && is_win() && (get_browser_version()>=4))
		echo"	document.selection.createRange().text = apptext;\n";
 	else
 	{
 		echo "	eval('document.inputform.' + inputfield + '.value += apptext;');\n";
 		echo "	eval('document.inputform.' + inputfield + '.focus();');\n";
 	}
?>
	return;
}

function highlightbutton(helptext,inputfield,imgname,picfilename) {
	helpline(helptext,inputfield);
	highlightpic='gfx/bbcodes/highlight/'+picfilename;
	eval("document."+imgname+".src='"+highlightpic+"';");
}

function unhighlightbutton(helptext,inputfield,imgname,picfilename) {
	helpline(helptext,inputfield);
	highlightpic='gfx/bbcodes/normal/'+picfilename;
	eval("document."+imgname+".src='"+highlightpic+"';");
}

function bbc_imgpreload(){
<?php
	include('./includes/bbcode_defs.inc');
	for($i=0;$i<count($bbcode_pics);$i++)
	{
		echo "bbchimg".$i." = new Image();\n";
		echo "bbchimg".$i.".src = 'gfx/bbcodes/highlight/".$bbcode_pics[$i]."';\n";
	}
	echo "bbchlpimg = new Image();\r\n";
	echo "bbchlpimg.src = 'gfx/bbcodes/highlight/bbcode_help.gif';\r\n";
	echo "bbcp2img = new Image();\r\n";
	echo "bbcp2img.src = 'gfx/bbcodes/highlight/bbcode_pic2.gif';\r\n";
?>
}
//-->
</script>
