<?php
	require './common.php';
	$lang = isset($_GET['l']) ? $_GET['l'] : 'english';
	require "lang/$lang.$php_ext";
?>
<!-- 
    Copyright (c) <?php print strftime('%Y') ?> by Web Power, The Netherlands (http://www.webpower.nl)
    Programming by Arjan Haverkamp (arjan-at-webpower.nl)       
-->
<HTML>
<HEAD>
<TITLE><?php print PEword(41) . str_repeat('&nbsp; ', 50) ?></TITLE>
<SCRIPT LANGUAGE="Javascript">
<!--

var D, range, ta;

function init() {
	D = document;
	ta = window.dialogArguments;
	range = ta.createTextRange();
}

function findNext() {
	var word = D.phoundry.word.value;
	if (word == '') return;
	var whole = D.phoundry.whole.checked == true;
	var cases = D.phoundry.cases.checked == true;
	var flags = 0;
	if (whole) flags += 2;
	if (cases) flags += 4;
	if (range.findText(word,0,flags)==true){
		range.select();
		range.moveStart('character',1);
		range.moveEnd('textedit');
	}
	else {
		alert('Finished searching the document!');
		range = ta.createTextRange();
	}
}

function checkInput(el) {
	var but = D.getElementById('nextBut');
	but.disabled = (el.value == '') ? true : false;
}

//-->
</SCRIPT>
<STYLE TYPE="text/css">
<!--

form {
	margin: 4px;
}

body, td, button, select, input {
	font: MessageBox;
}

.txt {padding:0px; margin:0px; border:1px inset window; width:180px }

.but {width:70px; margin:2px}

-->
</STYLE>
</HEAD>
<BODY BGCOLOR="buttonFace" LEFTMARGIN=3 TOPMARGIN=3 onLoad="init()">
<FIELDSET STYLE="width:100%; height:100%">
<LEGEND><B><?php print PEword(41) ?></B></LEGEND>
<FORM NAME="phoundry" onSubmit="findNext();return false">

<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0><TR>
<TD VALIGN="top">


<TABLE>
<TR>
	<TD NOWRAP>Find what:</TD>
	<TD><INPUT CLASS="txt" TYPE="text" NAME="word" SIZE=20 onKeyUp="checkInput(this)"></TD>
</TR>
</TABLE>

<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0>
<TR><TD VALIGN="top">

<TABLE>
<TR>
	<TD><INPUT ID="whole" TYPE="checkbox"></TD><TD><LABEL FOR="whole">Match whole word only</LABEL></TD>
</TR>
<TR>
	<TD><INPUT ID="cases" TYPE="checkbox"></TD><TD><LABEL FOR="cases">Match case</LABEL></TD>
</TR>
</TABLE>

</TD><TD>&nbsp;</TD>
</TR></TABLE>

</TD><TD>&nbsp;</TD>

</TD><TD VALIGN="top">
	<INPUT ID="nextBut" CLASS="but" TYPE="button" DISABLED VALUE="Find next" onClick="findNext()">
	<INPUT CLASS="but" TYPE="button" VALUE="<?php print PEword(138) ?>" onClick="window.close()">
</TD>
</TR></TABLE>

</FORM>
</FIELDSET>

<?php
if($PE_BRANDED) {
?>
<span style="font-family:Arial; position:absolute;top:3px;left:218px; z-index: 3;background
-color:buttonFace; font-size: 10px;">
&nbsp;&copy;<?php print strftime('%Y') ?> by Web Power&nbsp;
</span>
<?php 
}
?>
</BODY>
</HTML>
