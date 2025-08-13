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
<TITLE><?php print PEword(247) . str_repeat('&nbsp; ', 50) ?></TITLE>
<SCRIPT LANGUAGE="Javascript">
<!--

var D, bodyRef;
var DOM;

function init() {
	D = document;
	var f = D.phoundry;
	DOM = window.dialogArguments['dom'];
	f.title.value = DOM.title;
	f.bgColor.value = DOM.body.bgColor;
	D.getElementById('bgColorClr').style.backgroundColor = DOM.body.bgColor;
	f.text.value    = DOM.body.text;
	D.getElementById('textClr').style.backgroundColor = DOM.body.text;
	f.link.value    = DOM.body.link;
	D.getElementById('linkClr').style.backgroundColor = DOM.body.link;
	f.vLink.value   = DOM.body.vLink;
	D.getElementById('vLinkClr').style.backgroundColor = DOM.body.vLink;
	f.aLink.value   = DOM.body.aLink;
	D.getElementById('aLinkClr').style.backgroundColor = DOM.body.aLink;
	f.title.focus();
}

function setColor(what) {
	var A=[];
	A['color'] = D.phoundry[what].value;
	A = showModalDialog('colorpick.<?php print $php_ext ?>?l=<?php print $lang ?>', A, 'dialogWidth:390px; dialogHeight:210px;scroll:0;status:no;help:0;');
	if (A) {
		D.phoundry[what].value = A;
		D.getElementById(what+'Clr').style.backgroundColor = A;
	}
}

function setPreview(what, value) {
	try {
		D.getElementById(what+'Clr').style.backgroundColor = value;
	}
	catch(e) {
		D.getElementById(what+'Clr').style.backgroundColor = 'buttonFace';
	}
}


function submitMe() {
	var f = D.phoundry;
	DOM.body.bgColor = f.bgColor.value;
	DOM.body.text    = f.text.value;
	DOM.body.link    = f.link.value;
	DOM.body.aLink   = f.aLink.value;
	DOM.body.vLink   = f.vLink.value;
	DOM.title        = f.title.value;
	window.close();
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

.txt {padding:0px; margin:0px; border:1px inset window; width:60px }

.clr {
	border: 1px solid black; padding:0px; margin: 0px;
}

-->
</STYLE>
</HEAD>
<BODY BGCOLOR="buttonFace" LEFTMARGIN=3 TOPMARGIN=3 onLoad="init()">
<FIELDSET STYLE="width:100%;height:100%">
<LEGEND><B><?php print PEword(247) ?></B></LEGEND>
<FORM NAME="phoundry">
<TABLE>
<TR>
	<TD><?php print PEword(251) ?>:</TD>
	<TD COLSPAN=3><INPUT CLASS="txt" TYPE="text" NAME="title" SIZE=20 STYLE="width:160px"></TD>
</TR>
<TR>
	<TD><?php print PEword(12) ?>:</TD>
	<TD><INPUT CLASS="txt" TYPE="text" NAME="bgColor" SIZE=7 onBlur="setPreview('bgColor',this.value)">
	<TD ID="bgColorClr" CLASS="clr"><IMG SRC="pics/pixel.gif" WIDTH=20 HEIGHT=5></TD>
	<TD><IMG SRC="pics/bgcolor.gif" BORDER=0 onClick="setColor('bgColor')" STYLE="cursor:hand"></TD>
</TR>
<TR>
	<TD><?php print PEword(11) ?>:</TD>
	<TD><INPUT CLASS="txt" TYPE="text" NAME="text" SIZE=7 onBlur="setPreview('text',this.value)">
	<TD ID="textClr" CLASS="clr"><IMG SRC="pics/pixel.gif" WIDTH=20 HEIGHT=5></TD>
	<TD><IMG SRC="pics/bgcolor.gif" BORDER=0 onClick="setColor('text')" STYLE="cursor:hand"></TD>
</TR>
<TR>
	<TD><?php print PEword(248) ?>:</TD>
	<TD><INPUT CLASS="txt" TYPE="text" NAME="link" SIZE=7 onBlur="setPreview('link',this.value)">
	<TD ID="linkClr" CLASS="clr"><IMG SRC="pics/pixel.gif" WIDTH=20 HEIGHT=5></TD>
	<TD><IMG SRC="pics/bgcolor.gif" BORDER=0 onClick="setColor('link')" STYLE="cursor:hand"></TD>
</TR>
<TR>
	<TD><?php print PEword(249) ?>:</TD>
	<TD><INPUT CLASS="txt" TYPE="text" NAME="vLink" SIZE=7 onBlur="setPreview('vLink',this.value)">
	<TD ID="vLinkClr" CLASS="clr"><IMG SRC="pics/pixel.gif" WIDTH=20 HEIGHT=5></TD>
	<TD><IMG SRC="pics/bgcolor.gif" BORDER=0 onClick="setColor('vLink')" STYLE="cursor:hand"></TD>
</TR>
<TR>
	<TD><?php print PEword(250) ?>:</TD>
	<TD><INPUT CLASS="txt" TYPE="text" NAME="aLink" SIZE=7 onBlur="setPreview('aLink',this.value)">
	<TD ID="aLinkClr" CLASS="clr"><IMG SRC="pics/pixel.gif" WIDTH=20 HEIGHT=5></TD>
	<TD><IMG SRC="pics/bgcolor.gif" BORDER=0 onClick="setColor('aLink')" STYLE="cursor:hand"></TD>
</TR>
</TABLE>
<TABLE WIDTH="96%">
<TR>
	<TD ALIGN="right">
	<INPUT TYPE="button" VALUE="<?php print PEword(110) ?>" onClick="submitMe()">
	<INPUT TYPE="button" VALUE="<?php print PEword(138) ?>" onClick="window.close()">
	</TD>
</TR>
</TABLE>
</FORM>
</FIELDSET>

<?php
if($PE_BRANDED) {
?>
<span style="font-family:Arial; position:absolute;top:3px;left:170px; background
-color:buttonFace; font-size: 10px;">
&nbsp;&copy;<?php print strftime('%Y') ?> by Web Power&nbsp;
</span>
<?php 
}
?>
</BODY>
</HTML>
