<?php
// +-------------------------------------------------------------+
// | HiveMail version 1.3 Beta 2 (English)
// | Copyright ©2002-2003 Chen Avinadav
// | Supplied by Scoons [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+
// | HIVEMAIL IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// +-------------------------------------------------------------+
// ############################################################################
// Templates
$templates[2] = array (
  'compose' => 
  array (
    'templategroupid' => '3',
    'user_data' => '$skin[doctype]
<html<%if $data[html] %> XMLNS:ACE<%endif%>>
<head><title>$appname: Send New Mail</title>
<%if $data[html] %><<%if 1%><%endif%>?import namespace="ACE" implementation="misc/ace.htc" /><%endif%>
$css
<script language="JavaScript">
<!--

function popAddBook () {
     var url = "addressbook.view.php?do=mini";
     url += "&pre[to]=" + escape (document.forms.composeform.to.value);
     url += "&pre[cc]=" + escape (document.forms.composeform.cc.value);
     url += "&pre[bcc]=" + escape (document.forms.composeform.bcc.value);
     var hWnd = window.open(url,"AddBook","width=520,height=390,resizable=yes,scrollbars=yes");
     if ((document.window != null) && (!hWnd.opener)) {
          hWnd.opener = document.window;
	 }
}

function editorInit() {
<%if $data[html] %>
	idContent.editorWidth = "578";
	idContent.editorHeight = "340";
	idContent.useSave = false;
	//idContent.useBtnInsertText = true;
	idContent.useBtnStyle = true;
	idContent.useBtnParagraph = true;
	idContent.useBtnFontName = true;
	idContent.useBtnFontSize = true;
	idContent.useBtnCut = true;
	idContent.useBtnCopy = true;
	idContent.useBtnPaste = true;
	idContent.useBtnRemoveFormat  = true;
	idContent.useBtnUndo = true;
	idContent.useBtnRedo = true;
	idContent.useBtnWord = true;
	idContent.putBtnBreak()//line break
	idContent.useBtnBold = true;
	idContent.useBtnItalic = true;
	idContent.useBtnUnderline = true;
	idContent.useBtnStrikethrough = true;
	idContent.useBtnSuperscript = true;
	idContent.useBtnSubscript = true;
	idContent.useBtnJustifyLeft = true;
	idContent.useBtnJustifyCenter = true;
	idContent.useBtnJustifyRight = true;
	idContent.useBtnJustifyFull = true;
	idContent.useBtnInsertOrderedList = true;
	idContent.useBtnInsertUnorderedList = true;
	idContent.useBtnIndent = true;
	idContent.useBtnOutdent = true;
	idContent.useBtnHorizontalLine = true;
	idContent.useBtnTable = true;
	idContent.useBtnExternalLink = true;
	idContent.useBtnInternalLink = false;
	idContent.useBtnUnlink = true;
	idContent.useBtnInternalImage  = false;
	idContent.useBtnForeground  = true;
	idContent.useBtnBackground  = true;
	idContent.useBtnDocumentBackground  = true;
	//idContent.useBtnAbsolute  = true;
	idContent.useBtnInsertSymbol  = true;
	idContent.applyButtons();
	idContent.content = "$data[message]";
	idContent.style.background = \'$data[bgcolor]\';
	if (\'$data[bgcolor]\' != \'$skin[formbackground]\') {
		idContent.docBgColor = \'$data[bgcolor]\';
	}
<%endif%>
}

function sumbitForm(useText, fromAttach) {
<%if $data[html] %>
	document.composeform.bgcolor.value = document.all.idContent.docBgColor;
	document.composeform.message.value = (useText ? idContent.getText() : document.all.idContent.content);
	if (document.composeform.action == \'compose.email.php\' && fromAttach != 1) {
		document.composeform.usehtml.value = 0;
	}
<%else%>
	if (document.composeform.action == \'compose.email.php\') {
		document.composeform.usehtml.value = 1;
	}
<%endif%>
}

function focusBox() {
	idContent.InsertCustomHTML(\'bla\');
}

function insertSig(whichsig) {
<%if $data[html] %>
	idContent.InsertCustomHTML(eval(\'composeform.\'+whichsig).value);
<%else%>
	composeform.tmessage.value += eval(\'composeform.\'+whichsig).value;
<%endif%>
	composeform.addsig.value = 1;
}

var contacts = new Array($contactArray);

// -->
</script>
<script type="text/javascript" src="misc/autocomplete.js"></script>
</head>
<body onLoad="editorInit(); document.forms.composeform.to.focus();">
$header

<form enctype="multipart/form-data" action="compose.email.php" name="composeform" method="post" onSubmit="sumbitForm();">
<input type="hidden" name="do" value="compose" />
<input type="hidden" name="save" value="1" />
<input type="hidden" name="draftid" value="$draftid" />
<input type="hidden" name="data[special]" value="$data[special]" />
<input type="hidden" name="message" value="" />
<input type="hidden" name="data[html]" value="$data[html]" id="usehtml" />
<input type="hidden" name="data[bgcolor]" value="" id="bgcolor" />
<input type="hidden" name="data[addedsig]" value="$data[addedsig]" id="addsig" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th colspan="2" class="headerBothCell"><span class="normalfonttablehead"><b>Send New Mail</b></span></th>
</tr>
<%if !$hiveuser[cansend] %>
<tr class="normalRow">
	<td class="highBothCell" style="padding-right: 40px; text-align: center;" colspan="2"><span class="important">You do not have permission to send messages, this compose page is provided for demonstration purposes only.</span>
</tr>
<%endif%>
<tr class="normalRow">
	<td class="normalLeftCell" style="padding-right: 40px;"><span class="normalfont"><b>From:</b></span></td>
	<td class="normalRightCell" style="width: 100%;"><span class="normalfont">$hiveuser[realname] ($hiveuser[username]$domainname)</span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" style="padding-right: 40px;"><span class="normalfont"><b><a href="#" onClick="popAddBook(); return false;"><img src="$skin[images]/addbook.gif" alt="Address Book" border="0" /></a> To:</b></span></td>
	<td class="highRightCell" style="width: 100%;"><input type="text" class="bginput" name="data[to]" value="$data[to]" size="72" autocomplete="off" onKeyUp="autoComplete(this, contacts);" id="to" tabindex="1" /></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" style="padding-right: 40px;"><span class="normalfont"><b><a href="#" onClick="popAddBook(); return false;"><img src="$skin[images]/addbook.gif" alt="Address Book" border="0" /></a> Cc:</b></span></td>
	<td class="normalRightCell" style="width: 100%;"><input type="text" class="bginput" name="data[cc]" value="$data[cc]" size="72" autocomplete="off" onKeyUp="autoComplete(this, contacts);" id="cc" /></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" style="padding-right: 40px;"><span class="normalfont"><b><a href="#" onClick="popAddBook(); return false;"><img src="$skin[images]/addbook.gif" alt="Address Book" border="0" /></a> Bcc:</b></span></td>
	<td class="highRightCell" style="width: 100%;"><input type="text" class="bginput" name="data[bcc]" value="$data[bcc]" size="72" autocomplete="off" onKeyUp="autoComplete(this, contacts);" id="bcc" /></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" style="padding-right: 40px;"><span class="normalfont"><b>Subject:</b></span></td>
	<td class="normalRightCell" style="width: 100%;"><input type="text" class="bginput" value="$data[subject]" name="data[subject]" size="72" tabindex="2" <%if $data[html] and 0 %>onBlur="idContent.InsertCustomHTML(\'\');"<%endif%> /></td>
</tr>
<tr class="highRow">
	<td class="highBothCell" colspan="2">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr valign="top">
				<td><%if $data[html] %><ACE:AdvContentEditor id="idContent" tabindex="3" /><%else%><textarea name="data[message]" style="width: 573px; height: 380px;" wrap="virtual" id="tmessage" tabindex="3">$data[message]</textarea><%endif%></td>
			</tr>
		</table>
	</td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" style="padding-right: 40px;" valign="top"><span class="normalfont"><b>Signatures:</b></span>
	<%if $hiveuser[cansendhtml] %><br /><br /><span class="smallfont"><a href="#" onClick="sumbitForm(1); composeform.submit();">(Switch to $switchmode)</a></span><%endif%></td>
	<td class="normalRightCell" style="width: 100%;" valign="top"><span class="smallfont">Click the signature name below to insert it at the bottom of your message.<br />
	<%if empty($sigs) %>
	No signatures found. <a href="options.signature.php" target="_blank">Click here</a> to create a new signature.
	<%else%>
	$sigs
	<%endif%>
	</span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" style="padding-right: 40px;" valign="top"><span class="normalfont"><b>Options:</b></span></td>
	<td class="highRightCell" style="width: 100%;" valign="top"><span class="smallfont">
	<input type="checkbox" name="data[savecopy]" value="1" $savecopychecked /> <b>Save a copy:</b> Also save a copy in the Sent Items folder.<br />
	<input type="checkbox" name="data[requestread]" value="1" $requestreadchecked /> <b>Request read receipt:</b> Be notified when the receiver reads the message.<br />
	<input type="checkbox" name="data[addtobook]" value="1" $addtobookchecked /> <b>Add contacts to address book:</b> Automatically add all recipients of this<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;email to your address book after you send this message.
	</span></td>
</tr>
<%if $hiveuser[canattach] %>
<tr class="normalRow">
	<td class="normalLeftCell" style="padding-right: 40px;" valign="top"><span class="normalfont"><b>Attachments:</b></span></td>
	<td class="normalRightCell" style="width: 100%;" valign="top"><span class="normalfont">
	<%if !empty($attachlist) %>
	$attachlist
	<%else%>
	No attachments.<br />
	<%endif%>
	<br /><input type="submit" class="bginput" name="manageattach" value="Manage Attachments" onClick="var attWnd = window.open(\'compose.attachments.php?draftid=$draftid\',\'manageattach\',\'statusbar=no,menubar=no,toolbar=no,scrollbars=yes,width=480,height=425\'); return false;" />
	</span></td>
</tr>
<tr class="highRow">
<%else%>
<tr class="normalRow">
<%endif%>
	<td class="normalLeftCell" style="padding-right: 40px;"><span class="normalfont"><b>Priority:</b></span></td>
	<td class="normalRightCell" style="width: 100%;">
		<select name="data[priority]" onChange="getElement(\'prio_img\').src = this.options[this.selectedIndex].name;">
			<option value="1" name="$skin[images]/prio_high.gif" $prio[1]>High</option>
			<option value="3" name="$skin[images]/spacer.gif" $prio[3]>Normal</option>
			<option value="5" name="$skin[images]/prio_low.gif" $prio[5]>Low</option>
		</select> <img src="$skin[images]/spacer.gif" alt="" id="prio_img" />
	</td>
</tr>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr>
	<td align="center">
	<input type="submit" class="bginput" name="send" value="Send Email" onClick="this.form.action=\'compose.send.php\'; return true;" accesskey="s" tabindex="4" /> 
	<input type="button" class="bginput" name="cancel" value="Cancel" onClick="window.location = \'index.php\'; return false;" /> 
	<%if isset($draft) and $draft[\'dateline\'] == 0 %>
	<input type="submit" class="bginput" name="updatedraft" value="Update Draft" onClick="this.form.action=\\\'compose.draft.php\\\'; return true;" />
	<%endif%>
	<input type="submit" class="bginput" name="draft" value="<%if isset($draft) and $draft[\'dateline\'] == 0 %>Remove Draft<%else%>Save as Draft<%endif%>" onClick="this.form.action=\'compose.draft.php\'; return true;" />
	</td>
</tr>
</form>
</table>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html".(($data[html] ) ? (" XMLNS:ACE") : (\'\')).">
<head><title>$appname: Send New Mail</title>
".(($data[html] ) ? ("<".((1) ? ("") : (\'\'))."?import namespace=\"ACE\" implementation=\"misc/ace.htc\" />") : (\'\'))."
$GLOBALS[css]
<script language=\"JavaScript\">
<!--

function popAddBook () {
     var url = \"addressbook.view.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}do=mini\";
     url += \"&pre[to]=\" + escape (document.forms.composeform.to.value);
     url += \"&pre[cc]=\" + escape (document.forms.composeform.cc.value);
     url += \"&pre[bcc]=\" + escape (document.forms.composeform.bcc.value);
     var hWnd = window.open(url,\"AddBook\",\"width=520,height=390,resizable=yes,scrollbars=yes\");
     if ((document.window != null) && (!hWnd.opener)) {
          hWnd.opener = document.window;
	 }
}

function editorInit() {
".(($data[html] ) ? ("
	idContent.editorWidth = \"578\";
	idContent.editorHeight = \"340\";
	idContent.useSave = false;
	//idContent.useBtnInsertText = true;
	idContent.useBtnStyle = true;
	idContent.useBtnParagraph = true;
	idContent.useBtnFontName = true;
	idContent.useBtnFontSize = true;
	idContent.useBtnCut = true;
	idContent.useBtnCopy = true;
	idContent.useBtnPaste = true;
	idContent.useBtnRemoveFormat  = true;
	idContent.useBtnUndo = true;
	idContent.useBtnRedo = true;
	idContent.useBtnWord = true;
	idContent.putBtnBreak()//line break
	idContent.useBtnBold = true;
	idContent.useBtnItalic = true;
	idContent.useBtnUnderline = true;
	idContent.useBtnStrikethrough = true;
	idContent.useBtnSuperscript = true;
	idContent.useBtnSubscript = true;
	idContent.useBtnJustifyLeft = true;
	idContent.useBtnJustifyCenter = true;
	idContent.useBtnJustifyRight = true;
	idContent.useBtnJustifyFull = true;
	idContent.useBtnInsertOrderedList = true;
	idContent.useBtnInsertUnorderedList = true;
	idContent.useBtnIndent = true;
	idContent.useBtnOutdent = true;
	idContent.useBtnHorizontalLine = true;
	idContent.useBtnTable = true;
	idContent.useBtnExternalLink = true;
	idContent.useBtnInternalLink = false;
	idContent.useBtnUnlink = true;
	idContent.useBtnInternalImage  = false;
	idContent.useBtnForeground  = true;
	idContent.useBtnBackground  = true;
	idContent.useBtnDocumentBackground  = true;
	//idContent.useBtnAbsolute  = true;
	idContent.useBtnInsertSymbol  = true;
	idContent.applyButtons();
	idContent.content = \"$data[message]\";
	idContent.style.background = \'$data[bgcolor]\';
	if (\'$data[bgcolor]\' != \'{$GLOBALS[skin][formbackground]}\') {
		idContent.docBgColor = \'$data[bgcolor]\';
	}
") : (\'\'))."
}

function sumbitForm(useText, fromAttach) {
".(($data[html] ) ? ("
	document.composeform.bgcolor.value = document.all.idContent.docBgColor;
	document.composeform.message.value = (useText ? idContent.getText() : document.all.idContent.content);
	if (document.composeform.action == \'compose.email.php{$GLOBALS[session_url]}\' && fromAttach != 1) {
		document.composeform.usehtml.value = 0;
	}
") : ("
	if (document.composeform.action == \'compose.email.php{$GLOBALS[session_url]}\') {
		document.composeform.usehtml.value = 1;
	}
"))."
}

function focusBox() {
	idContent.InsertCustomHTML(\'bla\');
}

function insertSig(whichsig) {
".(($data[html] ) ? ("
	idContent.InsertCustomHTML(eval(\'composeform.\'+whichsig).value);
") : ("
	composeform.tmessage.value += eval(\'composeform.\'+whichsig).value;
"))."
	composeform.addsig.value = 1;
}

var contacts = new Array($contactArray);

// -->
</script>
<script type=\"text/javascript\" src=\"misc/autocomplete.js\"></script>
</head>
<body onLoad=\"editorInit(); document.forms.composeform.to.focus();\">
$GLOBALS[header]

<form enctype=\"multipart/form-data\" action=\"compose.email.php{$GLOBALS[session_url]}\" name=\"composeform\" method=\"post\" onSubmit=\"sumbitForm();\">
<input type=\"hidden\" name=\"do\" value=\"compose\" />
<input type=\"hidden\" name=\"save\" value=\"1\" />
<input type=\"hidden\" name=\"draftid\" value=\"$draftid\" />
<input type=\"hidden\" name=\"data[special]\" value=\"$data[special]\" />
<input type=\"hidden\" name=\"message\" value=\"\" />
<input type=\"hidden\" name=\"data[html]\" value=\"$data[html]\" id=\"usehtml\" />
<input type=\"hidden\" name=\"data[bgcolor]\" value=\"\" id=\"bgcolor\" />
<input type=\"hidden\" name=\"data[addedsig]\" value=\"$data[addedsig]\" id=\"addsig\" />

<table cellpadding=\"4\" cellspacing=\"0\" class=\"normalTable\" width=\"100%\">
<tr class=\"headerRow\">
	<th colspan=\"2\" class=\"headerBothCell\"><span class=\"normalfonttablehead\"><b>Send New Mail</b></span></th>
</tr>
".((!$hiveuser[cansend] ) ? ("
<tr class=\"normalRow\">
	<td class=\"highBothCell\" style=\"padding-right: 40px; text-align: center;\" colspan=\"2\"><span class=\"important\">You do not have permission to send messages, this compose page is provided for demonstration purposes only.</span>
</tr>
") : (\'\'))."
<tr class=\"normalRow\">
	<td class=\"normalLeftCell\" style=\"padding-right: 40px;\"><span class=\"normalfont\"><b>From:</b></span></td>
	<td class=\"normalRightCell\" style=\"width: 100%;\"><span class=\"normalfont\">$hiveuser[realname] ($hiveuser[username]$domainname)</span></td>
</tr>
<tr class=\"highRow\">
	<td class=\"highLeftCell\" style=\"padding-right: 40px;\"><span class=\"normalfont\"><b><a href=\"#\" onClick=\"popAddBook(); return false;\"><img src=\"{$GLOBALS[skin][images]}/addbook.gif\" alt=\"Address Book\" border=\"0\" /></a> To:</b></span></td>
	<td class=\"highRightCell\" style=\"width: 100%;\"><input type=\"text\" class=\"bginput\" name=\"data[to]\" value=\"$data[to]\" size=\"72\" autocomplete=\"off\" onKeyUp=\"autoComplete(this, contacts);\" id=\"to\" tabindex=\"1\" /></td>
</tr>
<tr class=\"normalRow\">
	<td class=\"normalLeftCell\" style=\"padding-right: 40px;\"><span class=\"normalfont\"><b><a href=\"#\" onClick=\"popAddBook(); return false;\"><img src=\"{$GLOBALS[skin][images]}/addbook.gif\" alt=\"Address Book\" border=\"0\" /></a> Cc:</b></span></td>
	<td class=\"normalRightCell\" style=\"width: 100%;\"><input type=\"text\" class=\"bginput\" name=\"data[cc]\" value=\"$data[cc]\" size=\"72\" autocomplete=\"off\" onKeyUp=\"autoComplete(this, contacts);\" id=\"cc\" /></td>
</tr>
<tr class=\"highRow\">
	<td class=\"highLeftCell\" style=\"padding-right: 40px;\"><span class=\"normalfont\"><b><a href=\"#\" onClick=\"popAddBook(); return false;\"><img src=\"{$GLOBALS[skin][images]}/addbook.gif\" alt=\"Address Book\" border=\"0\" /></a> Bcc:</b></span></td>
	<td class=\"highRightCell\" style=\"width: 100%;\"><input type=\"text\" class=\"bginput\" name=\"data[bcc]\" value=\"$data[bcc]\" size=\"72\" autocomplete=\"off\" onKeyUp=\"autoComplete(this, contacts);\" id=\"bcc\" /></td>
</tr>
<tr class=\"normalRow\">
	<td class=\"normalLeftCell\" style=\"padding-right: 40px;\"><span class=\"normalfont\"><b>Subject:</b></span></td>
	<td class=\"normalRightCell\" style=\"width: 100%;\"><input type=\"text\" class=\"bginput\" value=\"$data[subject]\" name=\"data[subject]\" size=\"72\" tabindex=\"2\" ".(($data[html] and 0 ) ? ("onBlur=\"idContent.InsertCustomHTML(\'\');\"") : (\'\'))." /></td>
</tr>
<tr class=\"highRow\">
	<td class=\"highBothCell\" colspan=\"2\">
		<table cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
			<tr valign=\"top\">
				<td>".(($data[html] ) ? ("<ACE:AdvContentEditor id=\"idContent\" tabindex=\"3\" />") : ("<textarea name=\"data[message]\" style=\"width: 573px; height: 380px;\" wrap=\"virtual\" id=\"tmessage\" tabindex=\"3\">$data[message]</textarea>"))."</td>
			</tr>
		</table>
	</td>
</tr>
<tr class=\"normalRow\">
	<td class=\"normalLeftCell\" style=\"padding-right: 40px;\" valign=\"top\"><span class=\"normalfont\"><b>Signatures:</b></span>
	".(($hiveuser[cansendhtml] ) ? ("<br /><br /><span class=\"smallfont\"><a href=\"#\" onClick=\"sumbitForm(1); composeform.submit();\">(Switch to $switchmode)</a></span>") : (\'\'))."</td>
	<td class=\"normalRightCell\" style=\"width: 100%;\" valign=\"top\"><span class=\"smallfont\">Click the signature name below to insert it at the bottom of your message.<br />
	".((empty($sigs) ) ? ("
	No signatures found. <a href=\"options.signature.php{$GLOBALS[session_url]}\" target=\"_blank\">Click here</a> to create a new signature.
	") : ("
	$sigs
	"))."
	</span></td>
</tr>
<tr class=\"highRow\">
	<td class=\"highLeftCell\" style=\"padding-right: 40px;\" valign=\"top\"><span class=\"normalfont\"><b>Options:</b></span></td>
	<td class=\"highRightCell\" style=\"width: 100%;\" valign=\"top\"><span class=\"smallfont\">
	<input type=\"checkbox\" name=\"data[savecopy]\" value=\"1\" $savecopychecked /> <b>Save a copy:</b> Also save a copy in the Sent Items folder.<br />
	<input type=\"checkbox\" name=\"data[requestread]\" value=\"1\" $requestreadchecked /> <b>Request read receipt:</b> Be notified when the receiver reads the message.<br />
	<input type=\"checkbox\" name=\"data[addtobook]\" value=\"1\" $addtobookchecked /> <b>Add contacts to address book:</b> Automatically add all recipients of this<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;email to your address book after you send this message.
	</span></td>
</tr>
".(($hiveuser[canattach] ) ? ("
<tr class=\"normalRow\">
	<td class=\"normalLeftCell\" style=\"padding-right: 40px;\" valign=\"top\"><span class=\"normalfont\"><b>Attachments:</b></span></td>
	<td class=\"normalRightCell\" style=\"width: 100%;\" valign=\"top\"><span class=\"normalfont\">
	".((!empty($attachlist) ) ? ("
	$attachlist
	") : ("
	No attachments.<br />
	"))."
	<br /><input type=\"submit\" class=\"bginput\" name=\"manageattach\" value=\"Manage Attachments\" onClick=\"var attWnd = window.open(\'compose.attachments.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}draftid=$draftid\', \'manageattach\',\'statusbar=no,menubar=no,toolbar=no,scrollbars=yes,width=480,height=425\'); return false;\" />
	</span></td>
</tr>
<tr class=\"highRow\">
") : ("
<tr class=\"normalRow\">
"))."
	<td class=\"normalLeftCell\" style=\"padding-right: 40px;\"><span class=\"normalfont\"><b>Priority:</b></span></td>
	<td class=\"normalRightCell\" style=\"width: 100%;\">
		<select name=\"data[priority]\" onChange=\"getElement(\'prio_img\').src = this.options[this.selectedIndex].name;\">
			<option value=\"1\" name=\"{$GLOBALS[skin][images]}/prio_high.gif\" $prio[1]>High</option>
			<option value=\"3\" name=\"{$GLOBALS[skin][images]}/spacer.gif\" $prio[3]>Normal</option>
			<option value=\"5\" name=\"{$GLOBALS[skin][images]}/prio_low.gif\" $prio[5]>Low</option>
		</select> <img src=\"{$GLOBALS[skin][images]}/spacer.gif\" alt=\"\" id=\"prio_img\" />
	</td>
</tr>
</table>

<br />

<table cellpadding=\"4\" cellspacing=\"0\" class=\"normalTable\" width=\"100%\">
<tr>
	<td align=\"center\">
	<input type=\"submit\" class=\"bginput\" name=\"send\" value=\"Send Email\" onClick=\"this.form.action=\'compose.send.php{$GLOBALS[session_url]}\'; return true;\" accesskey=\"s\" tabindex=\"4\" /> 
	<input type=\"button\" class=\"bginput\" name=\"cancel\" value=\"Cancel\" onClick=\"window.location = \'index.php{$GLOBALS[session_url]}\'; return false;\" /> 
	".((isset($draft) and $draft[\'dateline\'] == 0 ) ? ("
	<input type=\"submit\" class=\"bginput\" name=\"updatedraft\" value=\"Update Draft\" onClick=\"this.form.action=\'compose.draft.php{$GLOBALS[session_url]}\'; return true;\" />
	") : (\'\'))."
	<input type=\"submit\" class=\"bginput\" name=\"draft\" value=\"".((isset($draft) and $draft[\'dateline\'] == 0 ) ? ("Remove Draft") : ("Save as Draft"))."\" onClick=\"this.form.action=\'compose.draft.php{$GLOBALS[session_url]}\'; return true;\" />
	</td>
</tr>
</form>
</table>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'options_general' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: General Options</title>
$css
</head>
<body>
$header

<form action="options.general.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="do" value="update" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>General Options</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Skin:</b></span>
	<br />
	<span class="smallfont">You can choose from several skins that change the look of this program.</span></td>
	<td class="normalRightCell" width="40%"><select name="skinid">
		$skinoptions
	</select></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Empty the trash can automatically:</b></span>
	<br />
	<span class="smallfont">If you want the system to automatically delete all messages<br />from your trash can, please select the appropriate option.<br />If this is turned on, messages in your trash can don\'t count<br />towards your account storage limit.</span></td>
	<td class="highRightCell" width="40%"><span class="normalfont"><input type="radio" name="emptybin" value="-2" id="emptybinonexit" $emptybinonexit /> <label for="emptybinonexit">Empty folder on exit</label><br /><input type="radio" name="emptybin" value="1" id="emptybinevery" $emptybinevery /> <label for="emptybinevery">Empty folder every &nbsp;<input type="text" class="bginput" name="binevery" value="$binevery" size="3" maxlength="3" onClick="emptybinevery.checked = true; this.focus();" />&nbsp; days</label><br /><input type="radio" name="emptybin" value="-1" id="emptybinno" $emptybinno /> <label for="emptybinno">Never empty folder</label></span></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Play sound when new messages arrive:</b></span>
	<br />
	<span class="smallfont">Play the "You\'ve got mail" sound whenever new messages arrive in your mail box.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="playsound" value="1" id="playsoundon" $playsoundon /><label for="playsoundon">Yes<label><br /><input type="radio" name="playsound" value="0" id="playsoundoff" $playsoundoff /><label for="playsoundoff">No<label></span></td>
</tr>
<%if $hiveuser[cansound] %>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Sound to play:</b></span>
	<br />
	<span class="smallfont">This is the sound you will hear if the option above is enabled.</span></td>
	<td class="highRightCell" width="40%"><span class="normalfont">
		<input type="radio" name="soundoption" value="0" id="soundoptiondef" $soundoptiondef /> <label for="soundoptiondef">Use <a href="user.sound.php?soundid=0" target="_blank">default</a>.<label><br />
		<%if $hiveuser[soundid] != 0 %><input type="radio" name="soundoption" value="1" id="soundoptioncus" $soundoptioncus /> <label for="soundoptioncus">Use custom file (<a href="user.sound.php?soundid=$hiveuser[soundid]" target="_blank">$cursound</a>).<label><br /><%endif%>
		<input type="radio" name="soundoption" value="2" id="soundoptionnew" /> <label for="soundoptionnew">Upload new file: <input type="file" class="bginput" name="newsound" onClick="this.form.soundoptionnew.checked = true;" /><input type="hidden" name="MAX_FILE_SIZE" value="$maxsoundfile" /><label>
	</span></td>
</tr>
<%endif%>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Auto-forwarding:</b></span>
	<br />
	<span class="smallfont">Emails that you receive will automatically be forwarded to this address.<br />Set this to nothing to disable the feature.</span></td>
	<td class="normalRightCell" width="40%"><input type="text" class="bginput" name="forward" value="$hiveuser[forward]" size="40" /></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Email notification:</b></span>
	<br />
	<span class="smallfont">A notifcation will be sent to this address every time you recieve an email.<br />Set this to nothing to disable the feature.</span></td>
	<td class="highRightCell" width="40%"><input type="text" class="bginput" name="notifyemail" value="$hiveuser[notifyemail]" size="40" /></td>
</tr>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="submit" value="Save Settings" />
		<input type="reset" class="bginput" name="reset" value="Reset Fields" />
	</td>
</tr>
</table>

</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: General Options</title>
$GLOBALS[css]
</head>
<body>
$GLOBALS[header]

<form action=\"options.general.php{$GLOBALS[session_url]}\" method=\"post\" enctype=\"multipart/form-data\">
<input type=\"hidden\" name=\"do\" value=\"update\" />

<table cellpadding=\"4\" cellspacing=\"0\" class=\"normalTable\" width=\"100%\">
<tr class=\"headerRow\">
	<th class=\"headerBothCell\" colspan=\"2\"><span class=\"normalfonttablehead\"><b>General Options</b></span></th>
</tr>
<tr class=\"normalRow\">
	<td class=\"normalLeftCell\" width=\"60%\" valign=\"top\"><span class=\"normalfont\"><b>Skin:</b></span>
	<br />
	<span class=\"smallfont\">You can choose from several skins that change the look of this program.</span></td>
	<td class=\"normalRightCell\" width=\"40%\"><select name=\"skinid\">
		$skinoptions
	</select></td>
</tr>
<tr class=\"highRow\">
	<td class=\"highLeftCell\" width=\"60%\" valign=\"top\"><span class=\"normalfont\"><b>Empty the trash can automatically:</b></span>
	<br />
	<span class=\"smallfont\">If you want the system to automatically delete all messages<br />from your trash can, please select the appropriate option.<br />If this is turned on, messages in your trash can don\'t count<br />towards your account storage limit.</span></td>
	<td class=\"highRightCell\" width=\"40%\"><span class=\"normalfont\"><input type=\"radio\" name=\"emptybin\" value=\"-2\" id=\"emptybinonexit\" $emptybinonexit /> <label for=\"emptybinonexit\">Empty folder on exit</label><br /><input type=\"radio\" name=\"emptybin\" value=\"1\" id=\"emptybinevery\" $emptybinevery /> <label for=\"emptybinevery\">Empty folder every &nbsp;<input type=\"text\" class=\"bginput\" name=\"binevery\" value=\"$binevery\" size=\"3\" maxlength=\"3\" onClick=\"emptybinevery.checked = true; this.focus();\" />&nbsp; days</label><br /><input type=\"radio\" name=\"emptybin\" value=\"-1\" id=\"emptybinno\" $emptybinno /> <label for=\"emptybinno\">Never empty folder</label></span></td>
</tr>
<tr class=\"normalRow\">
	<td class=\"normalLeftCell\" width=\"60%\" valign=\"top\"><span class=\"normalfont\"><b>Play sound when new messages arrive:</b></span>
	<br />
	<span class=\"smallfont\">Play the \"You\'ve got mail\" sound whenever new messages arrive in your mail box.</span></td>
	<td class=\"normalRightCell\" width=\"40%\"><span class=\"normalfont\"><input type=\"radio\" name=\"playsound\" value=\"1\" id=\"playsoundon\" $playsoundon /><label for=\"playsoundon\">Yes<label><br /><input type=\"radio\" name=\"playsound\" value=\"0\" id=\"playsoundoff\" $playsoundoff /><label for=\"playsoundoff\">No<label></span></td>
</tr>
".(($hiveuser[cansound] ) ? ("
<tr class=\"highRow\">
	<td class=\"highLeftCell\" width=\"60%\" valign=\"top\"><span class=\"normalfont\"><b>Sound to play:</b></span>
	<br />
	<span class=\"smallfont\">This is the sound you will hear if the option above is enabled.</span></td>
	<td class=\"highRightCell\" width=\"40%\"><span class=\"normalfont\">
		<input type=\"radio\" name=\"soundoption\" value=\"0\" id=\"soundoptiondef\" $soundoptiondef /> <label for=\"soundoptiondef\">Use <a href=\"user.sound.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}soundid=0\" target=\"_blank\">default</a>.<label><br />
		".(($hiveuser[soundid] != 0 ) ? ("<input type=\"radio\" name=\"soundoption\" value=\"1\" id=\"soundoptioncus\" $soundoptioncus /> <label for=\"soundoptioncus\">Use custom file (<a href=\"user.sound.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}soundid=$hiveuser[soundid]\" target=\"_blank\">$cursound</a>).<label><br />") : (\'\'))."
		<input type=\"radio\" name=\"soundoption\" value=\"2\" id=\"soundoptionnew\" /> <label for=\"soundoptionnew\">Upload new file: <input type=\"file\" class=\"bginput\" name=\"newsound\" onClick=\"this.form.soundoptionnew.checked = true;\" /><input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$maxsoundfile\" /><label>
	</span></td>
</tr>
") : (\'\'))."
<tr class=\"normalRow\">
	<td class=\"normalLeftCell\" width=\"60%\" valign=\"top\"><span class=\"normalfont\"><b>Auto-forwarding:</b></span>
	<br />
	<span class=\"smallfont\">Emails that you receive will automatically be forwarded to this address.<br />Set this to nothing to disable the feature.</span></td>
	<td class=\"normalRightCell\" width=\"40%\"><input type=\"text\" class=\"bginput\" name=\"forward\" value=\"$hiveuser[forward]\" size=\"40\" /></td>
</tr>
<tr class=\"highRow\">
	<td class=\"highLeftCell\" width=\"60%\" valign=\"top\"><span class=\"normalfont\"><b>Email notification:</b></span>
	<br />
	<span class=\"smallfont\">A notifcation will be sent to this address every time you recieve an email.<br />Set this to nothing to disable the feature.</span></td>
	<td class=\"highRightCell\" width=\"40%\"><input type=\"text\" class=\"bginput\" name=\"notifyemail\" value=\"$hiveuser[notifyemail]\" size=\"40\" /></td>
</tr>
</table>

<br />

<table cellpadding=\"4\" cellspacing=\"0\" class=\"normalTable\" width=\"100%\">
<tr>
	<td align=\"center\">
		<input type=\"submit\" class=\"bginput\" name=\"submit\" value=\"Save Settings\" />
		<input type=\"reset\" class=\"bginput\" name=\"reset\" value=\"Reset Fields\" />
	</td>
</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
);

?>