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
$templates[1] = array (
  'addbook_main' => 
  array (
    'templategroupid' => '4',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Address Book</title>
$css
<script type="text/javascript" src="misc/checkall.js"></script>
<script language="JavaScript" type="text/javascript">
<!--
function validateAddForm() {
	var errors = \'\';
	if (document.addform.name.value.length<1) {
		errors+=\'You must provide a name\\n\';
	}
	if (document.addform.email.value.length<1 || document.addform.email.value.indexOf(\'@\')==-1) {
		errors+=\'You must provide a valid e-mail address\\n\';
	}
	if (errors!=\'\') {
		alert(\'Sorry, this address entry cannot be added:\\n\\n\'+errors);
	}
	return (errors==\'\');
}
// -->
</script>
</head>
<body>
$header

<form action="addressbook.update.php" method="post" name="form">
<input type="hidden" name="do" value="update" />
<table cellpadding="4" cellspacing="0" class="normalTable" width="730">
<tr class="headerRow">
	<th class="headerLeftCell" nowrap="nowrap"><span class="normalfonttablehead"><b>Contact Name</b></span> <span class="smallfonttablehead">(click to edit)</span></th>
	<th class="headerCell" nowrap="nowrap"><span class="normalfonttablehead"><b>Email Address</b></span> <span class="smallfonttablehead">(click to email)</span></th>
	<th class="headerRightCell"><input name="allbox" type="checkbox" value="Check All" title="Select/Deselect All" onClick="checkAll(this.form);" /></th>
</tr>
$contacts
<tr>
	<td align="right" colspan="3"><span class="smallfonttablehead"><b><input type="submit" class="bginput" name="edit" value="Edit" /> &nbsp;or &nbsp;<input type="submit" class="bginput" name="delete" value="Delete" onClick="return confirm(\'Are you sure you want to delete the selected contacts?\');" />&nbsp; selected contacts</b></span></td>
</tr>
</table>
</form>

<br />

<form action="addressbook.add.php" method="post" name="addform" onSubmit="return validateAddForm();">
<input type="hidden" name="do" value="insert" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="730">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Add New Contact</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" nowrap="nowrap"><span class="normalfont">Contact\'s name:</span></td>
	<td class="normalRightCell"><input type="text" class="bginput" name="name" value="" size="40" /></td>
</tr>
<tr class="highRow">
	<td class="normalLeftCell" nowrap="nowrap"><span class="normalfont">Contact\'s email:</span></td>
	<td class="normalRightCell"><input type="text" class="bginput" name="email" value="" size="40" /></td>
</tr>
</table>
<br />
<table cellpadding="4" cellspacing="0" class="normalTable" width="730">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="submit" value="Add Contact" />
	</td>
</tr>
</table>
</form>

<br />

<form enctype="multipart/form-data" action="addressbook.add.php" name="composeform" method="post">
<input type="hidden" name="do" value="upload" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="730">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Add Contacts from CSV File</b></span></th>
</tr>
<tr class="highRow">
	<td class="normalBothCell"><span class="smallfont">Click the "Browse..." button to find the CSV file you wish to use.<br />When you are done, click "Upload CSV".<br /><br />
	<input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
	<input type="file" class="bginput" name="attachment" /></span></td>
</tr>
</table>
<br />
<table cellpadding="4" cellspacing="0" class="normalTable" width="730">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="submit" value="Import CSV File" />
	</td>
</tr>
</table>
</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Address Book</title>
$GLOBALS[css]
<script type=\\"text/javascript\\" src=\\"misc/checkall.js\\"></script>
<script language=\\"JavaScript\\" type=\\"text/javascript\\">
<!--
function validateAddForm() {
	var errors = \'\';
	if (document.addform.name.value.length<1) {
		errors+=\'You must provide a name\\\\n\';
	}
	if (document.addform.email.value.length<1 || document.addform.email.value.indexOf(\'@\')==-1) {
		errors+=\'You must provide a valid e-mail address\\\\n\';
	}
	if (errors!=\'\') {
		alert(\'Sorry, this address entry cannot be added:\\\\n\\\\n\'+errors);
	}
	return (errors==\'\');
}
// -->
</script>
</head>
<body>
$GLOBALS[header]

<form action=\\"addressbook.update.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"do\\" value=\\"update\\" />
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"730\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerLeftCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>Contact Name</b></span> <span class=\\"smallfonttablehead\\">(click to edit)</span></th>
	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>Email Address</b></span> <span class=\\"smallfonttablehead\\">(click to email)</span></th>
	<th class=\\"headerRightCell\\"><input name=\\"allbox\\" type=\\"checkbox\\" value=\\"Check All\\" title=\\"Select/Deselect All\\" onClick=\\"checkAll(this.form);\\" /></th>
</tr>
$contacts
<tr>
	<td align=\\"right\\" colspan=\\"3\\"><span class=\\"smallfonttablehead\\"><b><input type=\\"submit\\" class=\\"bginput\\" name=\\"edit\\" value=\\"Edit\\" /> &nbsp;or &nbsp;<input type=\\"submit\\" class=\\"bginput\\" name=\\"delete\\" value=\\"Delete\\" onClick=\\"return confirm(\'Are you sure you want to delete the selected contacts?\');\\" />&nbsp; selected contacts</b></span></td>
</tr>
</table>
</form>

<br />

<form action=\\"addressbook.add.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"addform\\" onSubmit=\\"return validateAddForm();\\">
<input type=\\"hidden\\" name=\\"do\\" value=\\"insert\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"730\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Add New Contact</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">Contact\'s name:</span></td>
	<td class=\\"normalRightCell\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"name\\" value=\\"\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"normalLeftCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">Contact\'s email:</span></td>
	<td class=\\"normalRightCell\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"email\\" value=\\"\\" size=\\"40\\" /></td>
</tr>
</table>
<br />
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"730\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Add Contact\\" />
	</td>
</tr>
</table>
</form>

<br />

<form enctype=\\"multipart/form-data\\" action=\\"addressbook.add.php{$GLOBALS[session_url]}\\" name=\\"composeform\\" method=\\"post\\">
<input type=\\"hidden\\" name=\\"do\\" value=\\"upload\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"730\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Add Contacts from CSV File</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"normalBothCell\\"><span class=\\"smallfont\\">Click the \\"Browse...\\" button to find the CSV file you wish to use.<br />When you are done, click \\"Upload CSV\\".<br /><br />
	<input type=\\"hidden\\" name=\\"MAX_FILE_SIZE\\" value=\\"10485760\\" />
	<input type=\\"file\\" class=\\"bginput\\" name=\\"attachment\\" /></span></td>
</tr>
</table>
<br />
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"730\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Import CSV File\\" />
	</td>
</tr>
</table>
</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
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
".(($data[html] ) ? ("<".((1) ? ("") : (\'\'))."?import namespace=\\"ACE\\" implementation=\\"misc/ace.htc\\" />") : (\'\'))."
$GLOBALS[css]
<script language=\\"JavaScript\\">
<!--

function popAddBook () {
     var url = \\"addressbook.view.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}do=mini\\";
     url += \\"&pre[to]=\\" + escape (document.forms.composeform.to.value);
     url += \\"&pre[cc]=\\" + escape (document.forms.composeform.cc.value);
     url += \\"&pre[bcc]=\\" + escape (document.forms.composeform.bcc.value);
     var hWnd = window.open(url,\\"AddBook\\",\\"width=520,height=390,resizable=yes,scrollbars=yes\\");
     if ((document.window != null) && (!hWnd.opener)) {
          hWnd.opener = document.window;
	 }
}

function editorInit() {
".(($data[html] ) ? ("
	idContent.editorWidth = \\"578\\";
	idContent.editorHeight = \\"340\\";
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
	idContent.content = \\"$data[message]\\";
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
<script type=\\"text/javascript\\" src=\\"misc/autocomplete.js\\"></script>
</head>
<body onLoad=\\"editorInit(); document.forms.composeform.to.focus();\\">
$GLOBALS[header]

<form enctype=\\"multipart/form-data\\" action=\\"compose.email.php{$GLOBALS[session_url]}\\" name=\\"composeform\\" method=\\"post\\" onSubmit=\\"sumbitForm();\\">
<input type=\\"hidden\\" name=\\"do\\" value=\\"compose\\" />
<input type=\\"hidden\\" name=\\"save\\" value=\\"1\\" />
<input type=\\"hidden\\" name=\\"draftid\\" value=\\"$draftid\\" />
<input type=\\"hidden\\" name=\\"data[special]\\" value=\\"$data[special]\\" />
<input type=\\"hidden\\" name=\\"message\\" value=\\"\\" />
<input type=\\"hidden\\" name=\\"data[html]\\" value=\\"$data[html]\\" id=\\"usehtml\\" />
<input type=\\"hidden\\" name=\\"data[bgcolor]\\" value=\\"\\" id=\\"bgcolor\\" />
<input type=\\"hidden\\" name=\\"data[addedsig]\\" value=\\"$data[addedsig]\\" id=\\"addsig\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th colspan=\\"2\\" class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Send New Mail</b></span></th>
</tr>
".((!$hiveuser[cansend] ) ? ("
<tr class=\\"normalRow\\">
	<td class=\\"highBothCell\\" style=\\"padding-right: 40px; text-align: center;\\" colspan=\\"2\\"><span class=\\"important\\">You do not have permission to send messages, this compose page is provided for demonstration purposes only.</span>
</tr>
") : (\'\'))."
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" style=\\"padding-right: 40px;\\"><span class=\\"normalfont\\"><b>From:</b></span></td>
	<td class=\\"normalRightCell\\" style=\\"width: 100%;\\"><span class=\\"normalfont\\">$hiveuser[realname] ($hiveuser[username]$domainname)</span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" style=\\"padding-right: 40px;\\"><span class=\\"normalfont\\"><b><a href=\\"#\\" onClick=\\"popAddBook(); return false;\\"><img src=\\"{$GLOBALS[skin][images]}/addbook.gif\\" alt=\\"Address Book\\" border=\\"0\\" /></a> To:</b></span></td>
	<td class=\\"highRightCell\\" style=\\"width: 100%;\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"data[to]\\" value=\\"$data[to]\\" size=\\"72\\" autocomplete=\\"off\\" onKeyUp=\\"autoComplete(this, contacts);\\" id=\\"to\\" tabindex=\\"1\\" /></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" style=\\"padding-right: 40px;\\"><span class=\\"normalfont\\"><b><a href=\\"#\\" onClick=\\"popAddBook(); return false;\\"><img src=\\"{$GLOBALS[skin][images]}/addbook.gif\\" alt=\\"Address Book\\" border=\\"0\\" /></a> Cc:</b></span></td>
	<td class=\\"normalRightCell\\" style=\\"width: 100%;\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"data[cc]\\" value=\\"$data[cc]\\" size=\\"72\\" autocomplete=\\"off\\" onKeyUp=\\"autoComplete(this, contacts);\\" id=\\"cc\\" /></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" style=\\"padding-right: 40px;\\"><span class=\\"normalfont\\"><b><a href=\\"#\\" onClick=\\"popAddBook(); return false;\\"><img src=\\"{$GLOBALS[skin][images]}/addbook.gif\\" alt=\\"Address Book\\" border=\\"0\\" /></a> Bcc:</b></span></td>
	<td class=\\"highRightCell\\" style=\\"width: 100%;\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"data[bcc]\\" value=\\"$data[bcc]\\" size=\\"72\\" autocomplete=\\"off\\" onKeyUp=\\"autoComplete(this, contacts);\\" id=\\"bcc\\" /></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" style=\\"padding-right: 40px;\\"><span class=\\"normalfont\\"><b>Subject:</b></span></td>
	<td class=\\"normalRightCell\\" style=\\"width: 100%;\\"><input type=\\"text\\" class=\\"bginput\\" value=\\"$data[subject]\\" name=\\"data[subject]\\" size=\\"72\\" tabindex=\\"2\\" ".(($data[html] and 0 ) ? ("onBlur=\\"idContent.InsertCustomHTML(\'\');\\"") : (\'\'))." /></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\" colspan=\\"2\\">
		<table cellpadding=\\"0\\" cellspacing=\\"0\\" border=\\"0\\">
			<tr valign=\\"top\\">
				<td>".(($data[html] ) ? ("<ACE:AdvContentEditor id=\\"idContent\\" tabindex=\\"3\\" />") : ("<textarea name=\\"data[message]\\" style=\\"width: 573px; height: 380px;\\" wrap=\\"virtual\\" id=\\"tmessage\\" tabindex=\\"3\\">$data[message]</textarea>"))."</td>
			</tr>
		</table>
	</td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" style=\\"padding-right: 40px;\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Signatures:</b></span>
	".(($hiveuser[cansendhtml] ) ? ("<br /><br /><span class=\\"smallfont\\"><a href=\\"#\\" onClick=\\"sumbitForm(1); composeform.submit();\\">(Switch to $switchmode)</a></span>") : (\'\'))."</td>
	<td class=\\"normalRightCell\\" style=\\"width: 100%;\\" valign=\\"top\\"><span class=\\"smallfont\\">Click the signature name below to insert it at the bottom of your message.<br />
	".((empty($sigs) ) ? ("
	No signatures found. <a href=\\"options.signature.php{$GLOBALS[session_url]}\\" target=\\"_blank\\">Click here</a> to create a new signature.
	") : ("
	$sigs
	"))."
	</span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" style=\\"padding-right: 40px;\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Options:</b></span></td>
	<td class=\\"highRightCell\\" style=\\"width: 100%;\\" valign=\\"top\\"><span class=\\"smallfont\\">
	<input type=\\"checkbox\\" name=\\"data[savecopy]\\" value=\\"1\\" $savecopychecked /> <b>Save a copy:</b> Also save a copy in the Sent Items folder.<br />
	<input type=\\"checkbox\\" name=\\"data[requestread]\\" value=\\"1\\" $requestreadchecked /> <b>Request read receipt:</b> Be notified when the receiver reads the message.<br />
	<input type=\\"checkbox\\" name=\\"data[addtobook]\\" value=\\"1\\" $addtobookchecked /> <b>Add contacts to address book:</b> Automatically add all recipients of this<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;email to your address book after you send this message.
	</span></td>
</tr>
".(($hiveuser[canattach] ) ? ("
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" style=\\"padding-right: 40px;\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Attachments:</b></span></td>
	<td class=\\"normalRightCell\\" style=\\"width: 100%;\\" valign=\\"top\\"><span class=\\"normalfont\\">
	".((!empty($attachlist) ) ? ("
	$attachlist
	") : ("
	No attachments.<br />
	"))."
	<br /><input type=\\"submit\\" class=\\"bginput\\" name=\\"manageattach\\" value=\\"Manage Attachments\\" onClick=\\"var attWnd = window.open(\'compose.attachments.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}draftid=$draftid\',\'manageattach\',\'statusbar=no,menubar=no,toolbar=no,scrollbars=yes,width=480,height=425\'); return false;\\" />
	</span></td>
</tr>
<tr class=\\"highRow\\">
") : ("
<tr class=\\"normalRow\\">
"))."
	<td class=\\"normalLeftCell\\" style=\\"padding-right: 40px;\\"><span class=\\"normalfont\\"><b>Priority:</b></span></td>
	<td class=\\"normalRightCell\\" style=\\"width: 100%;\\">
		<select name=\\"data[priority]\\" onChange=\\"getElement(\'prio_img\').src = this.options[this.selectedIndex].name;\\">
			<option value=\\"1\\" name=\\"{$GLOBALS[skin][images]}/prio_high.gif\\" $prio[1]>High</option>
			<option value=\\"3\\" name=\\"{$GLOBALS[skin][images]}/spacer.gif\\" $prio[3]>Normal</option>
			<option value=\\"5\\" name=\\"{$GLOBALS[skin][images]}/prio_low.gif\\" $prio[5]>Low</option>
		</select> <img src=\\"{$GLOBALS[skin][images]}/spacer.gif\\" alt=\\"\\" id=\\"prio_img\\" />
	</td>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr>
	<td align=\\"center\\">
	<input type=\\"submit\\" class=\\"bginput\\" name=\\"send\\" value=\\"Send Email\\" onClick=\\"this.form.action=\'compose.send.php{$GLOBALS[session_url]}\'; return true;\\" accesskey=\\"s\\" tabindex=\\"4\\" /> 
	<input type=\\"button\\" class=\\"bginput\\" name=\\"cancel\\" value=\\"Cancel\\" onClick=\\"window.location = \'index.php{$GLOBALS[session_url]}\'; return false;\\" /> 
	".((isset($draft) and $draft[\'dateline\'] == 0 ) ? ("
	<input type=\\"submit\\" class=\\"bginput\\" name=\\"updatedraft\\" value=\\"Update Draft\\" onClick=\\"this.form.action=\\\\\'compose.draft.php{$GLOBALS[session_url]}\\\\\'; return true;\\" />
	") : (\'\'))."
	<input type=\\"submit\\" class=\\"bginput\\" name=\\"draft\\" value=\\"".((isset($draft) and $draft[\'dateline\'] == 0 ) ? ("Remove Draft") : ("Save as Draft"))."\\" onClick=\\"this.form.action=\'compose.draft.php{$GLOBALS[session_url]}\'; return true;\\" />
	</td>
</tr>
</form>
</table>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'compose_manageattach' => 
  array (
    'templategroupid' => '3',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Manage Attachments</title>
$css
</head>
<body style="background-color: #C7E1F4;">

$header

<span class="normalfont">$usermessage</span>

<form enctype="multipart/form-data" action="compose.attachments.php" name="composeform" method="post">
<input type="hidden" name="do" value="manageattach" />
<input type="hidden" name="draftid" value="$draft[draftid]" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Add New Attachment</b></span></th>
</tr>
<tr class="highRow">
	<td class="highBothCell"><span class="smallfont">Click the "Browse..." button to find the file you wish to attach.<br />When you are done, click "Attach File".<br /><br />
	<input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
	<input type="file" class="bginput" name="attachment" /><br /><br />
	&nbsp;&nbsp;&nbsp;<input type="submit" class="bginput" name="upload" value="Attach File" /></span></td>
</tr>
</table>
<br /><br /><br />

<!-- ****************************************** -->

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Current Attachments</b></span></th>
</tr>
<tr class="highRow">
	<td class="highBothCell"><span class="normalfont"><b>Below is the list of current attachments this email contains.</b><br />
	<span class="smallfont"><%if $hiveuser[maxattach] > 0 %>You are allowed to attach up to $hiveuser[maxattach]MB worth of files to this message.<br /><%endif%>
	To remove an attachment, click the "Delete" button next to it.</span><br /><br />
	<%if !empty($attachlist) %>
	$attachlist
	<%else%>
	No attachments.
	<%endif%>
	</span></td>
</tr>
</table>
<br /><br />

<center>
<input type="button" value=" Done " class="bginput" onclick="opener.sumbitForm(0, 1); opener.document.composeform.submit(); window.close();" />
</center>
</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Manage Attachments</title>
$GLOBALS[css]
</head>
<body style=\\"background-color: #C7E1F4;\\">

$GLOBALS[header]

<span class=\\"normalfont\\">$usermessage</span>

<form enctype=\\"multipart/form-data\\" action=\\"compose.attachments.php{$GLOBALS[session_url]}\\" name=\\"composeform\\" method=\\"post\\">
<input type=\\"hidden\\" name=\\"do\\" value=\\"manageattach\\" />
<input type=\\"hidden\\" name=\\"draftid\\" value=\\"$draft[draftid]\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Add New Attachment</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\"><span class=\\"smallfont\\">Click the \\"Browse...\\" button to find the file you wish to attach.<br />When you are done, click \\"Attach File\\".<br /><br />
	<input type=\\"hidden\\" name=\\"MAX_FILE_SIZE\\" value=\\"10485760\\" />
	<input type=\\"file\\" class=\\"bginput\\" name=\\"attachment\\" /><br /><br />
	&nbsp;&nbsp;&nbsp;<input type=\\"submit\\" class=\\"bginput\\" name=\\"upload\\" value=\\"Attach File\\" /></span></td>
</tr>
</table>
<br /><br /><br />

<!-- ****************************************** -->

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Current Attachments</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\"><span class=\\"normalfont\\"><b>Below is the list of current attachments this email contains.</b><br />
	<span class=\\"smallfont\\">".(($hiveuser[maxattach] > 0 ) ? ("You are allowed to attach up to $hiveuser[maxattach]MB worth of files to this message.<br />") : \'\')."
	To remove an attachment, click the \\"Delete\\" button next to it.</span><br /><br />
	".((!empty($attachlist) ) ? ("
	$attachlist
	") : ("
	No attachments.
	"))."
	</span></td>
</tr>
</table>
<br /><br />

<center>
<input type=\\"button\\" value=\\" Done \\" class=\\"bginput\\" onclick=\\"opener.sumbitForm(0, 1); opener.document.composeform.submit(); window.close();\\" />
</center>
</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'compose_manageattach_added' => 
  array (
    'templategroupid' => '3',
    'user_data' => 'The attachment was <b>successfully added</b>.',
    'parsed_data' => '"The attachment was <b>successfully added</b>."',
  ),
  'compose_manageattach_error' => 
  array (
    'templategroupid' => '3',
    'user_data' => 'The attachment was <b>not added</b> due to an error. Please try again.',
    'parsed_data' => '"The attachment was <b>not added</b> due to an error. Please try again."',
  ),
  'compose_manageattach_error_duplicate' => 
  array (
    'templategroupid' => '3',
    'user_data' => 'The attachment was <b>not added</b> as it is already attached to this message.',
    'parsed_data' => '"The attachment was <b>not added</b> as it is already attached to this message."',
  ),
  'compose_manageattach_error_toobig' => 
  array (
    'templategroupid' => '3',
    'user_data' => 'The attachment was <b>not added</b> due as it is too large. You are only allowed to attach up to $hiveuser[maxattach]MB.',
    'parsed_data' => '"The attachment was <b>not added</b> due as it is too large. You are only allowed to attach up to $hiveuser[maxattach]MB."',
  ),
  'compose_manageattach_removed' => 
  array (
    'templategroupid' => '3',
    'user_data' => 'The attachment was <b>successfully removed</b>.',
    'parsed_data' => '"The attachment was <b>successfully removed</b>."',
  ),
  'css' => 
  array (
    'templategroupid' => '1',
    'user_data' => '<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
$youvegotmail
<style type="text/css">
body {
	padding: 0px;
}
.firstalt {
	background: $skin[firstalt];
}
.secondalt {
	background: $skin[secondalt];
}

/***** Text *****/
.headerText {
	color: #3B6597;
	font-weight: bold;
	vertical-align: middle;
}
.timecolor {
	color: $skin[timecolor];
}
.smallfont {
	font-family: $skin[fontface];
	font-size: $skin[smallsize];
}
.smallfonttablehead {
	font-family: $skin[fontface];
	font-size: $skin[smallsize];
	color: $skin[tableheadfontcolor];
	text-decoration: none;
}
.normalfont {
	font-family: $skin[fontface];
	font-size: $skin[normalsize];
}
.normalfonttablehead {
	font-family: $skin[fontface];
	font-size: $skin[normalsize];
	color: $skin[tableheadfontcolor];
	text-decoration: none;
}
.important {
	font-family: $skin[fontface];
	font-size: $skin[normalsize];
	color: red;
                font-weight: bold;               
}

/***** Forms *****/
select {
	font-family: $skin[fontface];
	font-size: 11px;
	color: #000000;
	background: $skin[formbackground];
}
textarea, .bginput {
	font-family: $skin[fontface];
	font-size: 12px;
	color: #000000;
	background: $skin[formbackground];
}

/***** Links *****/
a:link, a:visited, a:active {
	color: $skin[linkcolor];
	
}
a:hover {
	color: $skin[linkhovercolor];
}
.headerLink {
	color: #DAEBFA;
	font-family: $skin[fontface];
	font-size: 12px;
	text-decoration: none;
}
.footerLink {
	color: #142F8A;
	font-family: Arial;
	font-size: 11px;
	text-decoration: none;
                margin-left: 1%;
                margin-right: 1%;
}
.folderLink {
	font-family: $skin[fontface];
	font-size: 11px;
	text-decoration: none;
}

/***** Tables *****/
.normalTable {
	border-width: 0px;
	font: 11px $skin[fontface];
}

/***** Normal Rows *****/
.normalRow {
	background-color: $skin[firstalt];
}
.highRow {
	background-color: $skin[secondalt];
}

/***** Header Row *****/
.headerRow {
	background: $skin[tableheadbgcolor];
	height: 23px;
}

/***** Normal Cells *****/
.normalCell {
	border: 0px $skin[border_normal_style] $skin[border_normal_color];
	border-width: 0px $skin[border_normal_vertical_width] $skin[border_normal_horizonal_width] 0px;
}
.normalLeftCell {
	border: 1px $skin[border_normal_style] $skin[border_normal_color];
	border-width: 0px $skin[border_normal_vertical_width] $skin[border_normal_horizonal_width] $skin[border_normal_edges_width];
}
.normalRightCell {
	border: 1px $skin[border_normal_style] $skin[border_normal_color];
	border-width: 0px $skin[border_normal_edges_width] $skin[border_normal_horizonal_width] 0px;
}
.normalBothCell {
	border: 1px $skin[border_normal_style] $skin[border_normal_color];
	border-width: 0px $skin[border_normal_edges_width] $skin[border_normal_horizonal_width] $skin[border_normal_edges_width];
}
.highCell {
	border: 0px $skin[border_normal_style] $skin[border_normal_color];
	border-width: 0px $skin[border_normal_vertical_width] $skin[border_normal_horizonal_width] 0px;
	background-color: $skin[secondalt];
}
.highLeftCell {
	border: 1px $skin[border_normal_style] $skin[border_normal_color];
	border-width: 0px $skin[border_normal_vertical_width] $skin[border_normal_horizonal_width] $skin[border_normal_edges_width];
	background-color: $skin[secondalt];
}
.highRightCell {
	border: 1px $skin[border_normal_style] $skin[border_normal_color];
	border-width: 0px $skin[border_normal_edges_width] $skin[border_normal_horizonal_width] 0px;
	background-color: $skin[secondalt];
}
.highBothCell {
	border: 1px $skin[border_normal_style] $skin[border_normal_color];
	border-width: 0px $skin[border_normal_edges_width] $skin[border_normal_horizonal_width] $skin[border_normal_edges_width];
	background-color: $skin[secondalt];
}

/***** Header Cells *****/
.headerCell {
	padding: 0px;
	border: 0px $skin[border_header_style] $skin[border_header_color];
	border-width: $skin[border_header_horizonal_width] $skin[border_header_vertical_width] $skin[border_header_horizonal_width] 0px;
}
.headerLeftCell {
	padding: 0px;
	border: 0px $skin[border_header_style] $skin[border_header_color];
	border-width: $skin[border_header_horizonal_width] $skin[border_header_vertical_width] $skin[border_header_horizonal_width] $skin[border_header_edges_width];
}
.headerRightCell {
	padding: 0px;
	border: 0px $skin[border_header_style] $skin[border_header_color];
	border-width: $skin[border_header_horizonal_width] $skin[border_header_edges_width] $skin[border_header_horizonal_width] 0px;
}
.headerBothCell {
	padding: 0px;
	border: 0px $skin[border_header_style] $skin[border_header_color];
	border-width: $skin[border_header_horizonal_width] $skin[border_header_edges_width] $skin[border_header_horizonal_width] $skin[border_header_edges_width];
}
</style>
<script type="text/javascript" src="misc/common.js"></script>
<%if !infile(\'compose\') and !defined(\'NO_JS\') %>
<link rel="stylesheet" href="misc/context.css">
<script type="text/javascript" src="misc/event.js"></script>
<script type="text/javascript" src="misc/context.js"></script>
<script type="text/javascript" language="JavaScript">
<!--

event_addListener( window, \'load\', function() { preloadImages(\'$skin[images]/header_icon_inbox_high.gif\', \'$skin[images]/header_icon_compose_high.gif\', \'$skin[images]/header_icon_addbook_high.gif\', \'$skin[images]/header_icon_options_high.gif\', \'$skin[images]/header_icon_search_high.gif\'); });

<%if $hiveuser[\'userid\'] <> 0 and $hiveuser[\'fixdst\'] %>
function checkDST() {
	var curDate = new Date();
	var js_hours = curDate.getHours();
	var js_minutes = curDate.getMinutes();

	var js_time = (js_hours * 60) + js_minutes;
	var difference = (js_time - $php_time) / 60;

	var int_diff = parseInt(difference);
	var flo_diff = difference - int_diff;
	if (difference >= 0) {
		if (flo_diff < 0.25) {
			flo_diff = 0;
		} else if (flo_diff < 0.75) {
			flo_diff = 0.5;
		} else {
			flo_diff = 0;
			int_diff++;
		}
	} else {
		if (flo_diff > -0.25) {
			flo_diff = 0;
		} else if (flo_diff > -0.75) {
			flo_diff = -0.5;
		} else {
			flo_diff = 0;
			int_diff--;
		}
	}
	difference = int_diff + flo_diff;

	while (difference > 12) {
		difference -= 12;
	}
	while (difference < -12) {
		difference += 12;
	}

	if (difference != 0 && confirm(\'The system has detected that the time zone in your preferences is wrong and off by \'+Math.abs(difference)+\' hour\'+((Math.abs(difference) == 1) ? (\'\') : (\'s\'))+\'. Would you like the system to correct this mistake?\')) {
		var newWin = window.open("options.personal.php?do=updatezone&difference="+difference,"FixTimeZone","width=10,height=10");
	}
}
setTimeout(checkDST, 1000);
<%endif%>

function contextForFolder(e, folderID, folderName) {
	var popupoptions = [
		new ContextItem(\'Open\', function(){ window.location = \'index.php?folderid=\'+folderID; }, false, true),
		new ContextSeperator(),
		new ContextItem(\'Rename...\', function(){ renameFolder(folderID, folderName); }, folderID < 0),
		new ContextSeperator(),
		new ContextItem(\'Empty\', function(){ if (confirm(\'Are you sure you want to empty this folder?\')) window.location = \'folders.update.php?empty=Empty&return=$folderid&folder[\'+folderID+\']=yes\'; }),
		new ContextItem(\'Delete\', function(){ if (confirm(\'Are you sure you want to delete this folder?\')) window.location = \'folders.update.php?delete=Delete&return=$folderid&folder[\'+folderID+\']=yes\'; }, folderID < 0)
	]
	ContextMenu.display(popupoptions, e);
	ContextMenu.folderID = folderID;
}

function renameFolder(folderid, currentName) {
	var name = window.prompt(\'New name for folder "\'+currentName+\'":\', currentName);
	if (name != null) {
		window.location = \'folders.rename.php?folderid=\'+folderid+\'&name=\'+name;
	}
}

//-->
</script>
<%else%>
<script type="text/javascript" language="JavaScript">
<!--

function contextForFolder() {
	return true;
}

//-->
</script>
<%endif%>',
    'parsed_data' => '"<meta http-equiv=\\"Content-Type\\" content=\\"text/html; charset=ISO-8859-1\\" />
$youvegotmail
<style type=\\"text/css\\">
body {
	padding: 0px;
}
.firstalt {
	background: {$GLOBALS[skin][firstalt]};
}
.secondalt {
	background: {$GLOBALS[skin][secondalt]};
}

/***** Text *****/
.headerText {
	color: #3B6597;
	font-weight: bold;
	vertical-align: middle;
}
.timecolor {
	color: {$GLOBALS[skin][timecolor]};
}
.smallfont {
	font-family: {$GLOBALS[skin][fontface]};
	font-size: {$GLOBALS[skin][smallsize]};
}
.smallfonttablehead {
	font-family: {$GLOBALS[skin][fontface]};
	font-size: {$GLOBALS[skin][smallsize]};
	color: {$GLOBALS[skin][tableheadfontcolor]};
	text-decoration: none;
}
.normalfont {
	font-family: {$GLOBALS[skin][fontface]};
	font-size: {$GLOBALS[skin][normalsize]};
}
.normalfonttablehead {
	font-family: {$GLOBALS[skin][fontface]};
	font-size: {$GLOBALS[skin][normalsize]};
	color: {$GLOBALS[skin][tableheadfontcolor]};
	text-decoration: none;
}
.important {
	font-family: {$GLOBALS[skin][fontface]};
	font-size: {$GLOBALS[skin][normalsize]};
	color: red;
                font-weight: bold;               
}

/***** Forms *****/
select {
	font-family: {$GLOBALS[skin][fontface]};
	font-size: 11px;
	color: #000000;
	background: {$GLOBALS[skin][formbackground]};
}
textarea, .bginput {
	font-family: {$GLOBALS[skin][fontface]};
	font-size: 12px;
	color: #000000;
	background: {$GLOBALS[skin][formbackground]};
}

/***** Links *****/
a:link, a:visited, a:active {
	color: {$GLOBALS[skin][linkcolor]};
	
}
a:hover {
	color: {$GLOBALS[skin][linkhovercolor]};
}
.headerLink {
	color: #DAEBFA;
	font-family: {$GLOBALS[skin][fontface]};
	font-size: 12px;
	text-decoration: none;
}
.footerLink {
	color: #142F8A;
	font-family: Arial;
	font-size: 11px;
	text-decoration: none;
                margin-left: 1%;
                margin-right: 1%;
}
.folderLink {
	font-family: {$GLOBALS[skin][fontface]};
	font-size: 11px;
	text-decoration: none;
}

/***** Tables *****/
.normalTable {
	border-width: 0px;
	font: 11px {$GLOBALS[skin][fontface]};
}

/***** Normal Rows *****/
.normalRow {
	background-color: {$GLOBALS[skin][firstalt]};
}
.highRow {
	background-color: {$GLOBALS[skin][secondalt]};
}

/***** Header Row *****/
.headerRow {
	background: {$GLOBALS[skin][tableheadbgcolor]};
	height: 23px;
}

/***** Normal Cells *****/
.normalCell {
	border: 0px {$GLOBALS[skin][border_normal_style]} {$GLOBALS[skin][border_normal_color]};
	border-width: 0px {$GLOBALS[skin][border_normal_vertical_width]} {$GLOBALS[skin][border_normal_horizonal_width]} 0px;
}
.normalLeftCell {
	border: 1px {$GLOBALS[skin][border_normal_style]} {$GLOBALS[skin][border_normal_color]};
	border-width: 0px {$GLOBALS[skin][border_normal_vertical_width]} {$GLOBALS[skin][border_normal_horizonal_width]} {$GLOBALS[skin][border_normal_edges_width]};
}
.normalRightCell {
	border: 1px {$GLOBALS[skin][border_normal_style]} {$GLOBALS[skin][border_normal_color]};
	border-width: 0px {$GLOBALS[skin][border_normal_edges_width]} {$GLOBALS[skin][border_normal_horizonal_width]} 0px;
}
.normalBothCell {
	border: 1px {$GLOBALS[skin][border_normal_style]} {$GLOBALS[skin][border_normal_color]};
	border-width: 0px {$GLOBALS[skin][border_normal_edges_width]} {$GLOBALS[skin][border_normal_horizonal_width]} {$GLOBALS[skin][border_normal_edges_width]};
}
.highCell {
	border: 0px {$GLOBALS[skin][border_normal_style]} {$GLOBALS[skin][border_normal_color]};
	border-width: 0px {$GLOBALS[skin][border_normal_vertical_width]} {$GLOBALS[skin][border_normal_horizonal_width]} 0px;
	background-color: {$GLOBALS[skin][secondalt]};
}
.highLeftCell {
	border: 1px {$GLOBALS[skin][border_normal_style]} {$GLOBALS[skin][border_normal_color]};
	border-width: 0px {$GLOBALS[skin][border_normal_vertical_width]} {$GLOBALS[skin][border_normal_horizonal_width]} {$GLOBALS[skin][border_normal_edges_width]};
	background-color: {$GLOBALS[skin][secondalt]};
}
.highRightCell {
	border: 1px {$GLOBALS[skin][border_normal_style]} {$GLOBALS[skin][border_normal_color]};
	border-width: 0px {$GLOBALS[skin][border_normal_edges_width]} {$GLOBALS[skin][border_normal_horizonal_width]} 0px;
	background-color: {$GLOBALS[skin][secondalt]};
}
.highBothCell {
	border: 1px {$GLOBALS[skin][border_normal_style]} {$GLOBALS[skin][border_normal_color]};
	border-width: 0px {$GLOBALS[skin][border_normal_edges_width]} {$GLOBALS[skin][border_normal_horizonal_width]} {$GLOBALS[skin][border_normal_edges_width]};
	background-color: {$GLOBALS[skin][secondalt]};
}

/***** Header Cells *****/
.headerCell {
	padding: 0px;
	border: 0px {$GLOBALS[skin][border_header_style]} {$GLOBALS[skin][border_header_color]};
	border-width: {$GLOBALS[skin][border_header_horizonal_width]} {$GLOBALS[skin][border_header_vertical_width]} {$GLOBALS[skin][border_header_horizonal_width]} 0px;
}
.headerLeftCell {
	padding: 0px;
	border: 0px {$GLOBALS[skin][border_header_style]} {$GLOBALS[skin][border_header_color]};
	border-width: {$GLOBALS[skin][border_header_horizonal_width]} {$GLOBALS[skin][border_header_vertical_width]} {$GLOBALS[skin][border_header_horizonal_width]} {$GLOBALS[skin][border_header_edges_width]};
}
.headerRightCell {
	padding: 0px;
	border: 0px {$GLOBALS[skin][border_header_style]} {$GLOBALS[skin][border_header_color]};
	border-width: {$GLOBALS[skin][border_header_horizonal_width]} {$GLOBALS[skin][border_header_edges_width]} {$GLOBALS[skin][border_header_horizonal_width]} 0px;
}
.headerBothCell {
	padding: 0px;
	border: 0px {$GLOBALS[skin][border_header_style]} {$GLOBALS[skin][border_header_color]};
	border-width: {$GLOBALS[skin][border_header_horizonal_width]} {$GLOBALS[skin][border_header_edges_width]} {$GLOBALS[skin][border_header_horizonal_width]} {$GLOBALS[skin][border_header_edges_width]};
}
</style>
<script type=\\"text/javascript\\" src=\\"misc/common.js\\"></script>
".((!infile(\'compose\') and !defined(\'NO_JS\') ) ? ("
<link rel=\\"stylesheet\\" href=\\"misc/context.css\\">
<script type=\\"text/javascript\\" src=\\"misc/event.js\\"></script>
<script type=\\"text/javascript\\" src=\\"misc/context.js\\"></script>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--

event_addListener( window, \'load\', function() { preloadImages(\'{$GLOBALS[skin][images]}/header_icon_inbox_high.gif\', \'{$GLOBALS[skin][images]}/header_icon_compose_high.gif\', \'{$GLOBALS[skin][images]}/header_icon_addbook_high.gif\', \'{$GLOBALS[skin][images]}/header_icon_options_high.gif\', \'{$GLOBALS[skin][images]}/header_icon_search_high.gif\'); });

".(($hiveuser[\'userid\'] <> 0 and $hiveuser[\'fixdst\'] ) ? ("
function checkDST() {
	var curDate = new Date();
	var js_hours = curDate.getHours();
	var js_minutes = curDate.getMinutes();

	var js_time = (js_hours * 60) + js_minutes;
	var difference = (js_time - $php_time) / 60;

	var int_diff = parseInt(difference);
	var flo_diff = difference - int_diff;
	if (difference >= 0) {
		if (flo_diff < 0.25) {
			flo_diff = 0;
		} else if (flo_diff < 0.75) {
			flo_diff = 0.5;
		} else {
			flo_diff = 0;
			int_diff++;
		}
	} else {
		if (flo_diff > -0.25) {
			flo_diff = 0;
		} else if (flo_diff > -0.75) {
			flo_diff = -0.5;
		} else {
			flo_diff = 0;
			int_diff--;
		}
	}
	difference = int_diff + flo_diff;

	while (difference > 12) {
		difference -= 12;
	}
	while (difference < -12) {
		difference += 12;
	}

	if (difference != 0 && confirm(\'The system has detected that the time zone in your preferences is wrong and off by \'+Math.abs(difference)+\' hour\'+((Math.abs(difference) == 1) ? (\'\') : (\'s\'))+\'. Would you like the system to correct this mistake?\')) {
		var newWin = window.open(\\"options.personal.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}do=updatezone&difference=\\"+difference,\\"FixTimeZone\\",\\"width=10,height=10\\");
	}
}
setTimeout(checkDST, 1000);
") : (\'\'))."

function contextForFolder(e, folderID, folderName) {
	var popupoptions = [
		new ContextItem(\'Open\', function(){ window.location = \'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=\'+folderID; }, false, true),
		new ContextSeperator(),
		new ContextItem(\'Rename...\', function(){ renameFolder(folderID, folderName); }, folderID < 0),
		new ContextSeperator(),
		new ContextItem(\'Empty\', function(){ if (confirm(\'Are you sure you want to empty this folder?\')) window.location = \'folders.update.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}empty=Empty&return=$folderid&folder[\'+folderID+\']=yes\'; }),
		new ContextItem(\'Delete\', function(){ if (confirm(\'Are you sure you want to delete this folder?\')) window.location = \'folders.update.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}delete=Delete&return=$folderid&folder[\'+folderID+\']=yes\'; }, folderID < 0)
	]
	ContextMenu.display(popupoptions, e);
	ContextMenu.folderID = folderID;
}

function renameFolder(folderid, currentName) {
	var name = window.prompt(\'New name for folder \\"\'+currentName+\'\\":\', currentName);
	if (name != null) {
		window.location = \'folders.rename.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=\'+folderid+\'&name=\'+name;
	}
}

//-->
</script>
") : ("
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--

function contextForFolder() {
	return true;
}

//-->
</script>
"))',
  ),
  'error' => 
  array (
    'templategroupid' => '1',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname <%if $iserror %>Error<%else%>Message<%endif%></title>
$css
</head>
<body>
$header

<table cellpadding="4" cellspacing="0" class="normalTable" width="650" align="center" style="height: 100px;">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>$appname <%if $iserror %>Error<%else%>Message<%endif%></b></span></th>
</tr>
<tr class="highRow" style="height: 100%;">
	<td class="highBothCell" valign="top" style="padding: 15px;"><span class="normalfont">$message</span></td>
</tr>
</table>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname ".(($iserror ) ? ("Error") : ("Message"))."</title>
$GLOBALS[css]
</head>
<body>
$GLOBALS[header]

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"650\\" align=\\"center\\" style=\\"height: 100px;\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>$appname ".(($iserror ) ? ("Error") : ("Message"))."</b></span></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 100%;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\" style=\\"padding: 15px;\\"><span class=\\"normalfont\\">$message</span></td>
</tr>
</table>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'error_cvsfail' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Sorry, $appname was unable to parse the uploaded file and import the details. Please ensure that the fields are seperated by commas and the file contains a header row.',
    'parsed_data' => '"Sorry, $appname was unable to parse the uploaded file and import the details. Please ensure that the fields are seperated by commas and the file contains a header row."',
  ),
  'error_invalidcontact' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The name or email of the contact you are trying to add is invalid. Please go back and try again.',
    'parsed_data' => '"The name or email of the contact you are trying to add is invalid. Please go back and try again."',
  ),
  'error_signup_nameillegal' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The username you chose, $username was not valid. Your username may only contain alphanumeric characters, underscores (_) and dots (.), must start with a letter and must be a minimum length of 2 characters.',
    'parsed_data' => '"The username you chose, $username was not valid. Your username may only contain alphanumeric characters, underscores (_) and dots (.), must start with a letter and must be a minimum length of 2 characters."',
  ),
  'error_signup_reserved' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Sorry, but your $type contained words which are not allowed to be used at $appname.',
    'parsed_data' => '"Sorry, but your $type contained words which are not allowed to be used at $appname."',
  ),
  'error_sig_toomany' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You may only have $hiveuser[maxsigs] signatures.',
    'parsed_data' => '"You may only have $hiveuser[maxsigs] signatures."',
  ),
  'error_soundfileattach' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Uploading your custom sound file has failed. Make sure the file is not corrupt, that it is a valid sound file and not bigger than $maxsoundfile bytes.',
    'parsed_data' => '"Uploading your custom sound file has failed. Make sure the file is not corrupt, that it is a valid sound file and not bigger than $maxsoundfile bytes."',
  ),
  'folders' => 
  array (
    'templategroupid' => '9',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Your Folders</title>
$css
<script type="text/javascript" src="misc/checkall.js"></script>
<script language="JavaScript" type="text/javascript">
<!--

function rename(folderid, currentName) {
	var name = window.prompt(\'New name for folder "\'+currentName+\'":\', currentName);

	if (name != null) {
		window.location = \'folders.rename.php?folderid=\'+folderid+\'&name=\'+name;
	}
}

// -->
</script>
</head>
<body>
$header

<form action="folders.update.php" method="post" name="form">
<table cellpadding="4" cellspacing="0" class="normalTable" width="730">
<tr class="headerRow">
	<th class="headerLeftCell" nowrap="nowrap"><span class="normalfonttablehead"><b>Folder Name</b></span></th>
	<th class="headerCell" nowrap="nowrap"><span class="normalfonttablehead"><b>Messages</b></span></th>
	<th class="headerCell" nowrap="nowrap"><span class="normalfonttablehead"><b>Unread</b></span></th>
	<th class="headerCell" nowrap="nowrap"><span class="normalfonttablehead"><b>Size</b></span></th>
	<th class="headerRightCell"><input name="allbox" type="checkbox" value="Check All" title="Select/Deselect All" onClick="checkAll(this.form);" /></th>
</tr>
<tr align="center" class="highRow">
	<td class="highLeftCell" align="left" width="50%"><span class="normalfont"><a href="index.php?folderid=-1">Inbox</a></span> <span class="smallfont">(not removable)</span></td>
	<td class="highCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$msgcount[inbox] message<%if $msgcount[\'inbox\'] != 1 %>s<%endif%></span></td>
	<td class="highCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$unreadcount[inbox] message<%if $unreadcount[\'inbox\'] != 1 %>s<%endif%></span></td>
	<td class="highCell" width="10%" align="center" nowrap="nowrap"><span class="normalfont">$presizes[inbox]KB</span></td>
	<td class="highRightCell"><input type="checkbox" name="folder[-1]" value="yes" onClick="checkMain(this.form);" /></td>
</tr>
<tr align="center" class="normalRow">
	<td class="normalLeftCell" align="left" width="50%"><span class="normalfont"><a href="index.php?folderid=-2">Sent Items</a></span> <span class="smallfont">(not removable)</span></td>
	<td class="normalCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$msgcount[sentitems] message<%if $msgcount[\'sentitems\'] != 1 %>s<%endif%></span></td>
	<td class="normalCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$unreadcount[sentitems] message<%if $unreadcount[\'sentitems\'] != 1 %>s<%endif%></span></td>
	<td class="normalCell" width="10%" align="center" nowrap="nowrap"><span class="normalfont">$presizes[sentitems]KB</span></td>
	<td class="normalRightCell"><input type="checkbox" name="folder[-2]" value="yes" onClick="checkMain(this.form);" /></td>
</tr>
<tr align="center" class="highRow">
	<td class="highLeftCell" align="left" width="50%"><span class="normalfont"><a href="index.php?folderid=-3">Trash Can</a></span> <span class="smallfont">(not removable)</span></td>
	<td class="highCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$msgcount[trashcan] message<%if $msgcount[\'trashcan\'] != 1 %>s<%endif%></span></td>
	<td class="highCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$unreadcount[trashcan] message<%if $unreadcount[\'trashcan\'] != 1 %>s<%endif%></span></td>
	<td class="highCell" width="10%" align="center" nowrap="nowrap"><span class="normalfont">$presizes[trashcan]KB</span></td>
	<td class="highRightCell"><input type="checkbox" name="folder[-3]" id="trashcan" value="yes" onClick="checkMain(this.form);" /></td>
</tr>
$folderbits
<tr align="center" class="headerRow">
	<th class="headerLeftCell" width="50%" align="right"><span class="normalfonttablehead"><b>Total:</b></span></th>
	<th class="headerCell" width="25%" nowrap="nowrap"><span class="normalfonttablehead"><b>$totalmsgs message<%if $totalmsgs != 1 %>s<%endif%></b></span></th>
	<th class="headerCell" width="25%" nowrap="nowrap"><span class="normalfonttablehead"><b>$totalunreads message<%if $totalunreads != 1 %>s<%endif%></b></span></th>
	<th class="headerCell" width="10%" nowrap="nowrap"><span class="normalfonttablehead"><b>{$totalsize}KB</b></span></th>
	<th class="headerRightCell">&nbsp;</th>
</tr>
<tr>
	<td align="right" colspan="7">
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td align="left"><span class="smallfonttablehead"><b>
		<input type="submit" class="bginput" name="move" value="Move" onClick="return confirm(\'Are you sure?\');" />&nbsp; messages from selected folders to &nbsp;<select name="movetofolderid">
			<option value="-1">Inbox</option>
			<option value="-2">Sent Items</option>
			<option value="-3">Trash Can</option>
$movefolderjump</select>
		</b></span></td>
        <td align="right"><span class="smallfonttablehead"><b>
		<input type="submit" class="bginput" name="empty" value="Empty" onClick="if (this.form.trashcan.checked) { var msgs = \'Messages from these folders will be irreversibly deleted!\'; } else { var msgs = \'Messages from these folders will be moved to the Trash Can folder.\'; } return confirm(\'Are you sure you want to empty the selected folders?\\n\'+msgs);" />&nbsp; or &nbsp;<input type="submit" class="bginput" name="delete" value="Delete" onClick="if (this.form.trashcan.checked) { var msgs = \'Messages from these folders will be irreversibly deleted!\'; } else { var msgs = \'Messages from these folders will be moved to the Trash Can folder.\'; } return confirm(\'Are you sure you want to delete the selected folders?\\n\'+msgs);" />&nbsp; selected folders</b></span></td>
      </tr>
    </table></td>
</tr>
</table>

<table cellpadding="2" cellspacing="0" class="normalTable" width="730">
<tr>
	<td><span class="smallfont"><b>Note:</b> when emptying/deleting folders, messages will be moved to the Trash Can, unless the Trash Can box is checked as well.</span></td>
</tr>
</table>

</form>

<br />

<form action="folders.add.php" method="post">

<table cellpadding="4" cellspacing="0" class="normalTable" width="730">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Add New Folders</b></span></th>
</tr>
<tr class="highRow">
	<td class="highLeftCell" valign="top" nowrap="nowrap"><span class="normalfont"><b>New Folders:</b></span><br /><span class="smallfont">You may leave one field empty if<br />you only wish to create one folder.</span></td>
	<td class="highRightCell"><input type="text" class="bginput" name="newfolderlist[]" value="" size="40" /><br /><input type="text" class="bginput" name="newfolderlist[]" value="" size="40" /></td>
</tr>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="730">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="submit" value="Add Folders" />
	</td>
</tr>
</table>

</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Your Folders</title>
$GLOBALS[css]
<script type=\\"text/javascript\\" src=\\"misc/checkall.js\\"></script>
<script language=\\"JavaScript\\" type=\\"text/javascript\\">
<!--

function rename(folderid, currentName) {
	var name = window.prompt(\'New name for folder \\"\'+currentName+\'\\":\', currentName);

	if (name != null) {
		window.location = \'folders.rename.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=\'+folderid+\'&name=\'+name;
	}
}

// -->
</script>
</head>
<body>
$GLOBALS[header]

<form action=\\"folders.update.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"730\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerLeftCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>Folder Name</b></span></th>
	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>Messages</b></span></th>
	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>Unread</b></span></th>
	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>Size</b></span></th>
	<th class=\\"headerRightCell\\"><input name=\\"allbox\\" type=\\"checkbox\\" value=\\"Check All\\" title=\\"Select/Deselect All\\" onClick=\\"checkAll(this.form);\\" /></th>
</tr>
<tr align=\\"center\\" class=\\"highRow\\">
	<td class=\\"highLeftCell\\" align=\\"left\\" width=\\"50%\\"><span class=\\"normalfont\\"><a href=\\"index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=-1\\">Inbox</a></span> <span class=\\"smallfont\\">(not removable)</span></td>
	<td class=\\"highCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$msgcount[inbox] message".(($msgcount[\'inbox\'] != 1 ) ? ("s") : \'\')."</span></td>
	<td class=\\"highCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$unreadcount[inbox] message".(($unreadcount[\'inbox\'] != 1 ) ? ("s") : \'\')."</span></td>
	<td class=\\"highCell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$presizes[inbox]KB</span></td>
	<td class=\\"highRightCell\\"><input type=\\"checkbox\\" name=\\"folder[-1]\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
<tr align=\\"center\\" class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" align=\\"left\\" width=\\"50%\\"><span class=\\"normalfont\\"><a href=\\"index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=-2\\">Sent Items</a></span> <span class=\\"smallfont\\">(not removable)</span></td>
	<td class=\\"normalCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$msgcount[sentitems] message".(($msgcount[\'sentitems\'] != 1 ) ? ("s") : \'\')."</span></td>
	<td class=\\"normalCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$unreadcount[sentitems] message".(($unreadcount[\'sentitems\'] != 1 ) ? ("s") : \'\')."</span></td>
	<td class=\\"normalCell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$presizes[sentitems]KB</span></td>
	<td class=\\"normalRightCell\\"><input type=\\"checkbox\\" name=\\"folder[-2]\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
<tr align=\\"center\\" class=\\"highRow\\">
	<td class=\\"highLeftCell\\" align=\\"left\\" width=\\"50%\\"><span class=\\"normalfont\\"><a href=\\"index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=-3\\">Trash Can</a></span> <span class=\\"smallfont\\">(not removable)</span></td>
	<td class=\\"highCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$msgcount[trashcan] message".(($msgcount[\'trashcan\'] != 1 ) ? ("s") : \'\')."</span></td>
	<td class=\\"highCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$unreadcount[trashcan] message".(($unreadcount[\'trashcan\'] != 1 ) ? ("s") : \'\')."</span></td>
	<td class=\\"highCell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$presizes[trashcan]KB</span></td>
	<td class=\\"highRightCell\\"><input type=\\"checkbox\\" name=\\"folder[-3]\\" id=\\"trashcan\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
$folderbits
<tr align=\\"center\\" class=\\"headerRow\\">
	<th class=\\"headerLeftCell\\" width=\\"50%\\" align=\\"right\\"><span class=\\"normalfonttablehead\\"><b>Total:</b></span></th>
	<th class=\\"headerCell\\" width=\\"25%\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>$totalmsgs message".(($totalmsgs != 1 ) ? ("s") : \'\')."</b></span></th>
	<th class=\\"headerCell\\" width=\\"25%\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>$totalunreads message".(($totalunreads != 1 ) ? ("s") : \'\')."</b></span></th>
	<th class=\\"headerCell\\" width=\\"10%\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>{$totalsize}KB</b></span></th>
	<th class=\\"headerRightCell\\">&nbsp;</th>
</tr>
<tr>
	<td align=\\"right\\" colspan=\\"7\\">
    <table border=\\"0\\" width=\\"100%\\" cellpadding=\\"0\\" cellspacing=\\"0\\">
      <tr>
        <td align=\\"left\\"><span class=\\"smallfonttablehead\\"><b>
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"move\\" value=\\"Move\\" onClick=\\"return confirm(\'Are you sure?\');\\" />&nbsp; messages from selected folders to &nbsp;<select name=\\"movetofolderid\\">
			<option value=\\"-1\\">Inbox</option>
			<option value=\\"-2\\">Sent Items</option>
			<option value=\\"-3\\">Trash Can</option>
$movefolderjump</select>
		</b></span></td>
        <td align=\\"right\\"><span class=\\"smallfonttablehead\\"><b>
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"empty\\" value=\\"Empty\\" onClick=\\"if (this.form.trashcan.checked) { var msgs = \'Messages from these folders will be irreversibly deleted!\'; } else { var msgs = \'Messages from these folders will be moved to the Trash Can folder.\'; } return confirm(\'Are you sure you want to empty the selected folders?\\\\n\'+msgs);\\" />&nbsp; or &nbsp;<input type=\\"submit\\" class=\\"bginput\\" name=\\"delete\\" value=\\"Delete\\" onClick=\\"if (this.form.trashcan.checked) { var msgs = \'Messages from these folders will be irreversibly deleted!\'; } else { var msgs = \'Messages from these folders will be moved to the Trash Can folder.\'; } return confirm(\'Are you sure you want to delete the selected folders?\\\\n\'+msgs);\\" />&nbsp; selected folders</b></span></td>
      </tr>
    </table></td>
</tr>
</table>

<table cellpadding=\\"2\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"730\\">
<tr>
	<td><span class=\\"smallfont\\"><b>Note:</b> when emptying/deleting folders, messages will be moved to the Trash Can, unless the Trash Can box is checked as well.</span></td>
</tr>
</table>

</form>

<br />

<form action=\\"folders.add.php{$GLOBALS[session_url]}\\" method=\\"post\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"730\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Add New Folders</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" valign=\\"top\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\"><b>New Folders:</b></span><br /><span class=\\"smallfont\\">You may leave one field empty if<br />you only wish to create one folder.</span></td>
	<td class=\\"highRightCell\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"newfolderlist[]\\" value=\\"\\" size=\\"40\\" /><br /><input type=\\"text\\" class=\\"bginput\\" name=\\"newfolderlist[]\\" value=\\"\\" size=\\"40\\" /></td>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"730\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Add Folders\\" />
	</td>
</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'folders_bit' => 
  array (
    'templategroupid' => '9',
    'user_data' => '<tr align="center" class="$classnameRow">
	<td class="$classnameLeftCell" align="left" width="50%"><span class="normalfont"><a href="index.php?folderid=$folder[folderid]">$folder[title]</a></span> <span class="smallfont">(<a href="#" onClick="rename($folder[folderid], \'$folder[title]\');">rename</a>)</span></td>
	<td class="$classnameCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$folder[msgcount] message<%if $folder[\'msgcount\'] != 1 %>s<%endif%></span></td>
	<td class="$classnameCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$folder[unreadcount] message<%if $folder[\'unreadcount\'] != 1 %>s<%endif%></span></td>
	<td class="$classnameCell" width="10%" align="center" nowrap="nowrap"><span class="normalfont">$folder[size]KB</span></td>
	<td class="$classnameRightCell"><input type="checkbox" name="folder[$folder[folderid]]" value="yes" onClick="checkMain(this.form);" /></td>
</tr>
',
    'parsed_data' => '"<tr align=\\"center\\" class=\\"$classnameRow\\">
	<td class=\\"$classnameLeftCell\\" align=\\"left\\" width=\\"50%\\"><span class=\\"normalfont\\"><a href=\\"index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folder[folderid]\\">$folder[title]</a></span> <span class=\\"smallfont\\">(<a href=\\"#\\" onClick=\\"rename($folder[folderid], \'$folder[title]\');\\">rename</a>)</span></td>
	<td class=\\"$classnameCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$folder[msgcount] message".(($folder[\'msgcount\'] != 1 ) ? ("s") : \'\')."</span></td>
	<td class=\\"$classnameCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$folder[unreadcount] message".(($folder[\'unreadcount\'] != 1 ) ? ("s") : \'\')."</span></td>
	<td class=\\"$classnameCell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$folder[size]KB</span></td>
	<td class=\\"$classnameRightCell\\"><input type=\\"checkbox\\" name=\\"folder[$folder[folderid]]\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
"',
  ),
  'footer' => 
  array (
    'templategroupid' => '1',
    'user_data' => '					</td>
				</tr>
				<tr>
					<td valign="top" style="background: $skin[pagebgcolor] url(\'$skin[images]/content_bottomleft.gif\'); width: 16px; height: 20px;">
						<img src="$skin[images]/spacer.gif" width="16" height="1" alt="" />
					</td>
					<td style="background-color: $skin[pagebgcolor]; border: 0px solid #9BC1E6; border-bottom-width: 1px; width: 100%;">
						<img src="$skin[images]/spacer.gif" width="1" height="1" alt="" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr style="height: 8px;">
		<td valign="top" style="width: 14px; height: 8px;">
		</td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td align="center" valign="top" style="padding-left: 0px; padding-top: 3px; width: 100%; background: url(\'$skin[images]/footer_mainbg.gif\'); border: 0px solid #254BAA; border-top-width: 1px;">
<%if $hiveuser[userid] <> 0%>
			<span class="smallfont" style="vertical-align: top;">
	<a href="index.php" class="footerLink">Inbox</a> | 
	<a href="compose.email.php" class="footerLink">Compose</a> | 
	<a href="addressbook.view.php" class="footerLink">Address Book</a> |
	<a href="options.menu.php" class="footerLink">Preferences</a> | 
	<%if $hiveuser[\'cansearch\'] %><a href="search.intro.php" class="footerLink">Search</a> | <%endif%>
	<a href="user.logout.php" class="footerLink">Logout</a>
</span>
<%else%>
			&nbsp;
<%endif%>
		</td>
		<td style="border: 0px solid #254BAA; border-top-width: 1px;">
			<!--CyKuH [WTN]--><img src="$skin[images]/footer_right.gif" align="middle" border="0" alt="" />
		</td>
	</tr>
</table>',
    'parsed_data' => '"					</td>
				</tr>
				<tr>
					<td valign=\\"top\\" style=\\"background: {$GLOBALS[skin][pagebgcolor]} url(\'{$GLOBALS[skin][images]}/content_bottomleft.gif\'); width: 16px; height: 20px;\\">
						<img src=\\"{$GLOBALS[skin][images]}/spacer.gif\\" width=\\"16\\" height=\\"1\\" alt=\\"\\" />
					</td>
					<td style=\\"background-color: {$GLOBALS[skin][pagebgcolor]}; border: 0px solid #9BC1E6; border-bottom-width: 1px; width: 100%;\\">
						<img src=\\"{$GLOBALS[skin][images]}/spacer.gif\\" width=\\"1\\" height=\\"1\\" alt=\\"\\" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr style=\\"height: 8px;\\">
		<td valign=\\"top\\" style=\\"width: 14px; height: 8px;\\">
		</td>
	</tr>
</table>
<table cellpadding=\\"0\\" cellspacing=\\"0\\" width=\\"100%\\">
	<tr>
		<td align=\\"center\\" valign=\\"top\\" style=\\"padding-left: 0px; padding-top: 3px; width: 100%; background: url(\'{$GLOBALS[skin][images]}/footer_mainbg.gif\'); border: 0px solid #254BAA; border-top-width: 1px;\\">
".(($hiveuser[userid] <> 0) ? ("
			<span class=\\"smallfont\\" style=\\"vertical-align: top;\\">
	<a href=\\"index.php{$GLOBALS[session_url]}\\" class=\\"footerLink\\">Inbox</a> | 
	<a href=\\"compose.email.php{$GLOBALS[session_url]}\\" class=\\"footerLink\\">Compose</a> | 
	<a href=\\"addressbook.view.php{$GLOBALS[session_url]}\\" class=\\"footerLink\\">Address Book</a> |
	<a href=\\"options.menu.php{$GLOBALS[session_url]}\\" class=\\"footerLink\\">Preferences</a> | 
	".(($hiveuser[\'cansearch\'] ) ? ("<a href=\\"search.intro.php{$GLOBALS[session_url]}\\" class=\\"footerLink\\">Search</a> | ") : (\'\'))."
	<a href=\\"user.logout.php{$GLOBALS[session_url]}\\" class=\\"footerLink\\">Logout</a>
</span>
") : ("
			&nbsp;
"))."
		</td>
		<td style=\\"border: 0px solid #254BAA; border-top-width: 1px;\\">
			<img src=\\"{$GLOBALS[skin][images]}/footer_right.gif\\" align=\\"middle\\" border=\\"0\\" alt=\\"\\" />
		</td>
	</tr>
</table>"',
  ),
  'index' => 
  array (
    'templategroupid' => '2',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname for $hiveuser[username]$domainname</title>
$css
$metarefresh
<script type="text/javascript" src="misc/checkall.js"></script>
<script type="text/javascript" src="misc/folderview.js"></script>
<script type="text/javascript" language="JavaScript">
<!--

var rows = new Array();
$rowjsbits

var useBG = $hiveuser[usebghigh];

function makeRows(which) {
	if (useBG) {
		$markallbg(which == \'first\' ? \'highRow\' : \'normalRow\');
	}
}

function contextForMail(e, msgID, isNew, isFlagged) {
	var popupoptions = [
		new ContextItem(\'Open\', function(){ openMail((totalChecked == 1 ? msgID : -1)); }, false, true),
		new ContextItem(\'Open in New Window\', function(){ openMail((totalChecked == 1 ? msgID : -1), true); }, false),
		new ContextItem(\'Print\', function(){ window.location = \'read.printable.php?messageid=\'+msgID; }, totalChecked != 1),
		new ContextSeperator(),
		new ContextItem(\'Reply to Sender\', function(){ window.location = \'compose.email.php?special=reply&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Reply to All\', function(){ window.location = \'compose.email.php?special=replyall&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Forward\', function(){ window.location = \'compose.email.php?special=forward&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Forward as Attachment\', function(){ form.action=\'index.php?asattach=1&forward=Forward\'; form.submit(); }),
		new ContextSeperator(),
		new ContextItem(\'Mark as Read\', function(){ form.action=\'index.php?mark=Mark\'; form.markas.selectedIndex=0; form.submit(); }, isNew.indexOf(\'new\') == -1 && totalChecked == 1),
		new ContextItem(\'Mark as Unread\', function(){ form.action=\'index.php?mark=Mark\'; form.markas.selectedIndex=1; form.submit(); }, isNew.indexOf(\'new\') != -1 && totalChecked == 1),
		new ContextItem(\'Flag\', function(){ form.action=\'index.php?mark=Mark\'; form.markas.selectedIndex=2; form.submit(); }, isFlagged == 1 && totalChecked == 1),
		new ContextItem(\'Unflag\', function(){ form.action=\'index.php?mark=Mark\'; form.markas.selectedIndex=3; form.submit(); }, isFlagged == 0 && totalChecked == 1),
		new ContextSeperator(),
		new ContextItem(\'Move...\', function(){ window.open(\'index.php?do=selfolder\',\'selectfolders\',\'resizable=no,width=270,height=150\'); }),
		new ContextItem(\'Delete\', function(){ if (confirm(\'Are you sure you want to delete this message?\')) window.location = \'read.update.php?delete=1&folderid=$folderid&messageid=\'+msgID; }),
		new ContextSeperator(),
		new ContextItem(\'Add Sender to Address Book\', function(){ window.location = \'addressbook.add.php?do=quick&return=$folderid&messageid=\'+msgID; }, totalChecked != 1)
<%if $hiveuser[canrule] %>,
		new ContextItem(\'Block Sender...\', function(){ blockSender(msgID, $folderid); }, totalChecked != 1),
		new ContextItem(\'Block Subject...\', function(){ blockSubject(msgID, $folderid); }, totalChecked != 1)
<%endif%>
	]
	ContextMenu.display(popupoptions, e);
	ContextMenu.msgID = msgID;
}

-->
</script>
</head>
<body onkeydown="return moveArrow();">

$header

<table cellpadding="0" border="0" cellspacing="1" width="100%" align="center">
<tr>
	<td width="100%" valign="top">
$topbox

<%if !empty($draftbits) %>
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="headerText"><span class="normalfonttablehead"><b>You still have unsent drafts!</b></span></span></th>
</tr>
$draftbits
</table>

<br />
<%endif%>

<%if $hiveuser[preview] == \'top\' %>
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="headerText"><span class="normalfonttablehead"><b>Preview Pane</b></span></span></th>
</tr>
<tr class="normalRow">
	<td class="normalBothCell" align="left"><iframe src="read.email.php?messageid=-1&show=msg" style="background-color: $skin[firstalt]; width: 100%; height: 160px;" scrolling="yes" allowtransparency="true" id="previewFrame" frameborder="no">Your browser does not support inline frames.</iframe></td>
</tr>
</table>

<br />
<%endif%>

<form action="index.php" method="post" name="form">
<input type="hidden" name="do" value="dostuff" />
<input type="hidden" name="folderid" value="$folderid" />
<input type="hidden" name="movetofolderid" value="$folderid" />
<input type="hidden" name="move" value="" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerLeftCell">&nbsp;</th>
$colheaders	<th class="headerRightCell"><input name="allbox" type="checkbox" value="Check All" title="Select/Deselect All" onClick="checkAll(this.form); if (this.checked) makeRows(\'first\'); else makeRows(\'second\');" /></th>
</tr>
$mailbits
<tr>
	<td colspan="10">
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td align="left"><span class="smallfonttablehead"><b>
		<input type="button" class="bginput" name="movebutton" value="Move" onClick="window.open(\'index.php?do=selfolder\',\'selectfolders\',\'resizable=no,width=230,height=180\');" />&nbsp; selected
		&nbsp;or &nbsp;<input type="submit" class="bginput" name="mark" value="Mark" />&nbsp; them as &nbsp;<select name="markas">
			<option value="read">read</option>
			<option value="unread">not read</option>
			<option value="flagged" selected="selected">flagged</option>
			<option value="unflagged">not flagged</option>
			<option value="replied">replied</option>
			<option value="unreplied">not replied</option>
			<option value="forwarded">forwarded</option>
			<option value="unforwarded">not forwarded</option>
		</select>
		</b></span></td>
        <td align="right"><span class="smallfonttablehead"><b>
		<input type="submit" class="bginput" name="forward" value="Forward" />&nbsp; or &nbsp;<input type="submit" class="bginput" name="delete" value="Delete" onClick="if (!confirm(\'Are you sure you want to delete the selected messages?\')) { return false; } changeFolderID(); return true;" />&nbsp; selected</b></span></td>
      </tr>
    </table></td>
</tr>
</table>

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr>
	<td><span class="smallfont">Showing messages $limitlower to $limitupper of $totalmails<br />$pagenav</span></td>
	<td align="right"><span class="smallfont"><%if $folderid != -3 %><b>Note:</b> deleted messages will be moved to the Trash Can.<br />Hold down Shift key when clicking to completely delete the messages.<%else%>&nbsp;<%endif%></span></td>
</tr>
</table>

<%if $hiveuser[preview] == \'bottom\' %>
<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="headerText"><span class="normalfonttablehead"><b>Preview Pane</b></span></span></th>
</tr>
<tr class="normalRow">
	<td class="normalBothCell" align="left"><iframe src="read.email.php?messageid=-1&show=msg" style="background-color: $skin[firstalt]; width: 100%; height: 160px;" scrolling="yes" allowtransparency="true" id="previewFrame" frameborder="no">Your browser does not support inline frames.</iframe></td>
</tr>
</table>
<%endif%>

</form>
	</td>
</tr>
</table>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname for $hiveuser[username]$domainname</title>
$GLOBALS[css]
$metarefresh
<script type=\\"text/javascript\\" src=\\"misc/checkall.js\\"></script>
<script type=\\"text/javascript\\" src=\\"misc/folderview.js\\"></script>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--

var rows = new Array();
$rowjsbits

var useBG = $hiveuser[usebghigh];

function makeRows(which) {
	if (useBG) {
		$markallbg(which == \'first\' ? \'highRow\' : \'normalRow\');
	}
}

function contextForMail(e, msgID, isNew, isFlagged) {
	var popupoptions = [
		new ContextItem(\'Open\', function(){ openMail((totalChecked == 1 ? msgID : -1)); }, false, true),
		new ContextItem(\'Open in New Window\', function(){ openMail((totalChecked == 1 ? msgID : -1), true); }, false),
		new ContextItem(\'Print\', function(){ window.location = \'read.printable.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=\'+msgID; }, totalChecked != 1),
		new ContextSeperator(),
		new ContextItem(\'Reply to Sender\', function(){ window.location = \'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}special=reply&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Reply to All\', function(){ window.location = \'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}special=replyall&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Forward\', function(){ window.location = \'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}special=forward&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Forward as Attachment\', function(){ form.action=\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}asattach=1&forward=Forward\'; form.submit(); }),
		new ContextSeperator(),
		new ContextItem(\'Mark as Read\', function(){ form.action=\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}mark=Mark\'; form.markas.selectedIndex=0; form.submit(); }, isNew.indexOf(\'new\') == -1 && totalChecked == 1),
		new ContextItem(\'Mark as Unread\', function(){ form.action=\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}mark=Mark\'; form.markas.selectedIndex=1; form.submit(); }, isNew.indexOf(\'new\') != -1 && totalChecked == 1),
		new ContextItem(\'Flag\', function(){ form.action=\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}mark=Mark\'; form.markas.selectedIndex=2; form.submit(); }, isFlagged == 1 && totalChecked == 1),
		new ContextItem(\'Unflag\', function(){ form.action=\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}mark=Mark\'; form.markas.selectedIndex=3; form.submit(); }, isFlagged == 0 && totalChecked == 1),
		new ContextSeperator(),
		new ContextItem(\'Move...\', function(){ window.open(\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}do=selfolder\',\'selectfolders\',\'resizable=no,width=270,height=150\'); }),
		new ContextItem(\'Delete\', function(){ if (confirm(\'Are you sure you want to delete this message?\')) window.location = \'read.update.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}delete=1&folderid=$folderid&messageid=\'+msgID; }),
		new ContextSeperator(),
		new ContextItem(\'Add Sender to Address Book\', function(){ window.location = \'addressbook.add.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}do=quick&return=$folderid&messageid=\'+msgID; }, totalChecked != 1)
".(($hiveuser[canrule] ) ? (",
		new ContextItem(\'Block Sender...\', function(){ blockSender(msgID, $folderid); }, totalChecked != 1),
		new ContextItem(\'Block Subject...\', function(){ blockSubject(msgID, $folderid); }, totalChecked != 1)
") : \'\')."
	]
	ContextMenu.display(popupoptions, e);
	ContextMenu.msgID = msgID;
}

-->
</script>
</head>
<body onkeydown=\\"return moveArrow();\\">

$GLOBALS[header]

<table cellpadding=\\"0\\" border=\\"0\\" cellspacing=\\"1\\" width=\\"100%\\" align=\\"center\\">
<tr>
	<td width=\\"100%\\" valign=\\"top\\">
$topbox

".((!empty($draftbits) ) ? ("
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"headerText\\"><span class=\\"normalfonttablehead\\"><b>You still have unsent drafts!</b></span></span></th>
</tr>
$draftbits
</table>

<br />
") : \'\')."

".(($hiveuser[preview] == \'top\' ) ? ("
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"headerText\\"><span class=\\"normalfonttablehead\\"><b>Preview Pane</b></span></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalBothCell\\" align=\\"left\\"><iframe src=\\"read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=-1&show=msg\\" style=\\"background-color: {$GLOBALS[skin][firstalt]}; width: 100%; height: 160px;\\" scrolling=\\"yes\\" allowtransparency=\\"true\\" id=\\"previewFrame\\" frameborder=\\"no\\">Your browser does not support inline frames.</iframe></td>
</tr>
</table>

<br />
") : \'\')."

<form action=\\"index.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"do\\" value=\\"dostuff\\" />
<input type=\\"hidden\\" name=\\"folderid\\" value=\\"$folderid\\" />
<input type=\\"hidden\\" name=\\"movetofolderid\\" value=\\"$folderid\\" />
<input type=\\"hidden\\" name=\\"move\\" value=\\"\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerLeftCell\\">&nbsp;</th>
$colheaders	<th class=\\"headerRightCell\\"><input name=\\"allbox\\" type=\\"checkbox\\" value=\\"Check All\\" title=\\"Select/Deselect All\\" onClick=\\"checkAll(this.form); if (this.checked) makeRows(\'first\'); else makeRows(\'second\');\\" /></th>
</tr>
$mailbits
<tr>
	<td colspan=\\"10\\">
    <table border=\\"0\\" width=\\"100%\\" cellpadding=\\"0\\" cellspacing=\\"0\\">
      <tr>
        <td align=\\"left\\"><span class=\\"smallfonttablehead\\"><b>
		<input type=\\"button\\" class=\\"bginput\\" name=\\"movebutton\\" value=\\"Move\\" onClick=\\"window.open(\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}do=selfolder\',\'selectfolders\',\'resizable=no,width=230,height=180\');\\" />&nbsp; selected
		&nbsp;or &nbsp;<input type=\\"submit\\" class=\\"bginput\\" name=\\"mark\\" value=\\"Mark\\" />&nbsp; them as &nbsp;<select name=\\"markas\\">
			<option value=\\"read\\">read</option>
			<option value=\\"unread\\">not read</option>
			<option value=\\"flagged\\" selected=\\"selected\\">flagged</option>
			<option value=\\"unflagged\\">not flagged</option>
			<option value=\\"replied\\">replied</option>
			<option value=\\"unreplied\\">not replied</option>
			<option value=\\"forwarded\\">forwarded</option>
			<option value=\\"unforwarded\\">not forwarded</option>
		</select>
		</b></span></td>
        <td align=\\"right\\"><span class=\\"smallfonttablehead\\"><b>
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"forward\\" value=\\"Forward\\" />&nbsp; or &nbsp;<input type=\\"submit\\" class=\\"bginput\\" name=\\"delete\\" value=\\"Delete\\" onClick=\\"if (!confirm(\'Are you sure you want to delete the selected messages?\')) { return false; } changeFolderID(); return true;\\" />&nbsp; selected</b></span></td>
      </tr>
    </table></td>
</tr>
</table>

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr>
	<td><span class=\\"smallfont\\">Showing messages $limitlower to $limitupper of $totalmails<br />$pagenav</span></td>
	<td align=\\"right\\"><span class=\\"smallfont\\">".(($folderid != -3 ) ? ("<b>Note:</b> deleted messages will be moved to the Trash Can.<br />Hold down Shift key when clicking to completely delete the messages.") : ("&nbsp;"))."</span></td>
</tr>
</table>

".(($hiveuser[preview] == \'bottom\' ) ? ("
<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"headerText\\"><span class=\\"normalfonttablehead\\"><b>Preview Pane</b></span></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalBothCell\\" align=\\"left\\"><iframe src=\\"read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=-1&show=msg\\" style=\\"background-color: {$GLOBALS[skin][firstalt]}; width: 100%; height: 160px;\\" scrolling=\\"yes\\" allowtransparency=\\"true\\" id=\\"previewFrame\\" frameborder=\\"no\\">Your browser does not support inline frames.</iframe></td>
</tr>
</table>
") : \'\')."

</form>
	</td>
</tr>
</table>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'index_folder_select' => 
  array (
    'templategroupid' => '2',
    'user_data' => '$skin[doctype]
<html>
<head>
<title>$appname: Move Messages</title>
$css
<script language="Javascript">
<!--
self.focus();

function sendAndClose(newvalue) {
	opener.form.movetofolderid.value = newvalue;
	opener.form.move.value = "Move";
	opener.focus();
	opener.form.submit();
	self.close();
}
// --></script>
</head>
<body style="background-color: #C7E1F4;">

$header

<form name="selectform" onSubmit="sendAndClose(this.folders.options[this.folders.selectedIndex].value);">

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Move Messages</b></span></th>
</tr>
<tr class="highRow">
	<td class="highBothCell"><span class="smallfont">To:
	<select name="folders">
		<option value="-1" selected="selected">Inbox</option>
		<option value="-2">Sent Items</option>
		<option value="-3">Trash Can</option>
		$movefolderjump
	</select></span></td>
</tr>
</table>

<br />

<div align="center">
<input type="submit" class="bginput" value="Move" />&nbsp;&nbsp;
<input type="button" class="bginput" value="Cancel" onClick="window.close();" />
</div>
</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head>
<title>$appname: Move Messages</title>
$GLOBALS[css]
<script language=\\"Javascript\\">
<!--
self.focus();

function sendAndClose(newvalue) {
	opener.form.movetofolderid.value = newvalue;
	opener.form.move.value = \\"Move\\";
	opener.focus();
	opener.form.submit();
	self.close();
}
// --></script>
</head>
<body style=\\"background-color: #C7E1F4;\\">

$GLOBALS[header]

<form name=\\"selectform\\" onSubmit=\\"sendAndClose(this.folders.options[this.folders.selectedIndex].value);\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Move Messages</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\"><span class=\\"smallfont\\">To:
	<select name=\\"folders\\">
		<option value=\\"-1\\" selected=\\"selected\\">Inbox</option>
		<option value=\\"-2\\">Sent Items</option>
		<option value=\\"-3\\">Trash Can</option>
		$movefolderjump
	</select></span></td>
</tr>
</table>

<br />

<div align=\\"center\\">
<input type=\\"submit\\" class=\\"bginput\\" value=\\"Move\\" />&nbsp;&nbsp;
<input type=\\"button\\" class=\\"bginput\\" value=\\"Cancel\\" onClick=\\"window.close();\\" />
</div>
</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'index_header_to' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell"><span class="headerText"><a href="index.php?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=recipients"><span class="normalfonttablehead"><b>To</b></span>$sortimages[recipients]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\"><span class=\\"headerText\\"><a href=\\"index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=recipients\\"><span class=\\"normalfonttablehead\\"><b>To</b></span>$sortimages[recipients]</a></span></th>
"',
  ),
  'index_poperror_connection' => 
  array (
    'templategroupid' => '2',
    'user_data' => 'Couldn\'t connect to server.',
    'parsed_data' => '"Couldn\'t connect to server."',
  ),
  'index_poperror_login' => 
  array (
    'templategroupid' => '2',
    'user_data' => 'The login information was not accepted by the server.',
    'parsed_data' => '"The login information was not accepted by the server."',
  ),
  'index_poperror_unexpected' => 
  array (
    'templategroupid' => '2',
    'user_data' => 'An unexpected error occurred.',
    'parsed_data' => '"An unexpected error occurred."',
  ),
  'index_topbox' => 
  array (
    'templategroupid' => '2',
    'user_data' => '<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Welcome back $hiveuser[realname] [<a href="user.logout.php"><span class="normalfonttablehead">log out</span></a>]</b></span></th>
</tr>
$space
$unreads
$poperror
</table>
<br />
',
    'parsed_data' => '"<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Welcome back $hiveuser[realname] [<a href=\\"user.logout.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\">log out</span></a>]</b></span></th>
</tr>
$space
$unreads
$poperror
</table>
<br />
"',
  ),
  'index_unreads' => 
  array (
    'templategroupid' => '2',
    'user_data' => '<tr class="highRow">
	<td class="highBothCell"><span class="normalfont">You have $unreads.</span></td>
</tr>',
    'parsed_data' => '"<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\"><span class=\\"normalfont\\">You have $unreads.</span></td>
</tr>"',
  ),
  'login' => 
  array (
    'templategroupid' => '1',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Please Log In</title>
$css
<script type="text/javascript" language="JavaScript">
<!--

function grabTime(theform) {
	var curDate = new Date();
	theform.jstime.value = curDate.getHours() + \':\' + curDate.getMinutes();
}

// -->
</script>
</head>
<body>
$header

<table cellpadding="4" cellspacing="0" class="normalTable" width="550" align="center">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Already have an account?</b></span></th>
</tr>
<tr class="highRow">
	<td class="highBothCell" align="center"><span class="normalfont">Enter your information below to log in.</span><br /><br />
	<form method="post" action="$_SERVER[PHP_SELF]">
	<input type="hidden" name="login" value="1">
	<input type="hidden" name="_postvars" value="$_postvars">
	<input type="hidden" name="_getvars" value="$_getvars">
	<table cellpadding="2">
		<tr>
			<td valign="top" align="right"><span class="normalfont">Account name:&nbsp;</span></td>
			<td align="left"><input type="text" name="username" class="bginput" /> <select name="userdomain">$domainname_options</select></td>
		</tr>
		<tr>
			<td valign="top" align="right"><span class="normalfont">Password:&nbsp;</span></td>
			<td align="left"><input type="password" name="password" class="bginput" /></td>
		</tr>
		<tr>
			<td valign="top" align="right"><span class="normalfont">Keep me logged in:&nbsp;</span></td>
			<td align="left"><input type="radio" name="staylogged" value="fornow" id="fornow" checked="checked" /> <label for="fornow">for this session only</label><br /><input type="radio" name="staylogged" value="days" id="fordays" /> <label for="fordays">for &nbsp;<input type="text" name="days" size="2" class="bginput" onClick="fordays.checked = true;" />&nbsp; days</label><br /><input type="radio" name="staylogged" value="forever" id="forever" /> <label for="forever">forever</label></td>
		</tr>
		<tr>
			<td align="center" colspan="2"><br /><input type="submit" value=" Log in " class="bginput" /></td>
		</tr>
	</form>
	</table><br /></td>
</tr>
<%if getop(\'regopen\') %>
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>New to $appname?</b></span></th>
</tr>
<tr class="highRow">
	<td class="highBothCell" align="center"><span class="normalfont">Sign up today and enjoy the great features $appname has to offer!<br /><br />
	<form method="post" action="user.signup.php" onSubmit="grabTime(this); return true;">
	<input type="hidden" name="do" value="getinfo">
	<input type="hidden" name="jstime" value="">
	<table cellpadding="2">
		<tr>
			<td valign="middle" align="right">Desired account name:&nbsp;</td>
			<td align="left"><input type="text" name="username" class="bginput" /> <select name="userdomain">$domainname_options</select></td>
		</tr>
		<tr>
			<td valign="middle" align="right">Desired password:&nbsp;</td>
			<td align="left"><input type="password" name="password" class="bginput" /></td>
		</tr>
		<tr>
			<td valign="middle" align="right">Retype password:&nbsp;</td>
			<td align="left"><input type="password" name="password_repeat" class="bginput" /></td>
		</tr>
		<tr>
			<td align="center" colspan="2"><br /><input type="submit" value=" Sign Up " class="bginput" /></td>
		</tr>
	</form>
	</table><br /></td>
</tr>
<%endif%>
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Forget your password?</b></span></th>
</tr>
<tr class="highRow">
	<td class="highBothCell" align="center"><span class="normalfont">Use the form below to recover it.<br /><br />
	<form method="post" action="user.lostpw.php">
	<input type="hidden" name="do" value="verify">
	<table cellpadding="2">
		<tr>
			<td valign="middle" align="right">Your account name:&nbsp;</td>
			<td align="left"><input type="text" name="username" class="bginput" /> <select name="userdomain">$domainname_options</select></td>
		</tr>
		<tr>
			<td align="center" colspan="2"><br /><input type="submit" value="Get New Password" class="bginput" /></td>
		</tr>
	</form>
	</table></td>
</tr>
</table>

<br />

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Please Log In</title>
$GLOBALS[css]
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--

function grabTime(theform) {
	var curDate = new Date();
	theform.jstime.value = curDate.getHours() + \':\' + curDate.getMinutes();
}

// -->
</script>
</head>
<body>
$GLOBALS[header]

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"550\\" align=\\"center\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Already have an account?</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\" align=\\"center\\"><span class=\\"normalfont\\">Enter your information below to log in.</span><br /><br />
	<form method=\\"post\\" action=\\"$_SERVER[PHP_SELF]\\">
	<input type=\\"hidden\\" name=\\"login\\" value=\\"1\\">
	<input type=\\"hidden\\" name=\\"_postvars\\" value=\\"$_postvars\\">
	<input type=\\"hidden\\" name=\\"_getvars\\" value=\\"$_getvars\\">
	<table cellpadding=\\"2\\">
		<tr>
			<td valign=\\"top\\" align=\\"right\\"><span class=\\"normalfont\\">Account name:&nbsp;</span></td>
			<td align=\\"left\\"><input type=\\"text\\" name=\\"username\\" class=\\"bginput\\" /> <select name=\\"userdomain\\">$domainname_options</select></td>
		</tr>
		<tr>
			<td valign=\\"top\\" align=\\"right\\"><span class=\\"normalfont\\">Password:&nbsp;</span></td>
			<td align=\\"left\\"><input type=\\"password\\" name=\\"password\\" class=\\"bginput\\" /></td>
		</tr>
		<tr>
			<td valign=\\"top\\" align=\\"right\\"><span class=\\"normalfont\\">Keep me logged in:&nbsp;</span></td>
			<td align=\\"left\\"><input type=\\"radio\\" name=\\"staylogged\\" value=\\"fornow\\" id=\\"fornow\\" checked=\\"checked\\" /> <label for=\\"fornow\\">for this session only</label><br /><input type=\\"radio\\" name=\\"staylogged\\" value=\\"days\\" id=\\"fordays\\" /> <label for=\\"fordays\\">for &nbsp;<input type=\\"text\\" name=\\"days\\" size=\\"2\\" class=\\"bginput\\" onClick=\\"fordays.checked = true;\\" />&nbsp; days</label><br /><input type=\\"radio\\" name=\\"staylogged\\" value=\\"forever\\" id=\\"forever\\" /> <label for=\\"forever\\">forever</label></td>
		</tr>
		<tr>
			<td align=\\"center\\" colspan=\\"2\\"><br /><input type=\\"submit\\" value=\\" Log in \\" class=\\"bginput\\" /></td>
		</tr>
	</form>
	</table><br /></td>
</tr>
".((getop(\'regopen\') ) ? ("
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>New to $appname?</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\" align=\\"center\\"><span class=\\"normalfont\\">Sign up today and enjoy the great features $appname has to offer!<br /><br />
	<form method=\\"post\\" action=\\"user.signup.php{$GLOBALS[session_url]}\\" onSubmit=\\"grabTime(this); return true;\\">
	<input type=\\"hidden\\" name=\\"do\\" value=\\"getinfo\\">
	<input type=\\"hidden\\" name=\\"jstime\\" value=\\"\\">
	<table cellpadding=\\"2\\">
		<tr>
			<td valign=\\"middle\\" align=\\"right\\">Desired account name:&nbsp;</td>
			<td align=\\"left\\"><input type=\\"text\\" name=\\"username\\" class=\\"bginput\\" /> <select name=\\"userdomain\\">$domainname_options</select></td>
		</tr>
		<tr>
			<td valign=\\"middle\\" align=\\"right\\">Desired password:&nbsp;</td>
			<td align=\\"left\\"><input type=\\"password\\" name=\\"password\\" class=\\"bginput\\" /></td>
		</tr>
		<tr>
			<td valign=\\"middle\\" align=\\"right\\">Retype password:&nbsp;</td>
			<td align=\\"left\\"><input type=\\"password\\" name=\\"password_repeat\\" class=\\"bginput\\" /></td>
		</tr>
		<tr>
			<td align=\\"center\\" colspan=\\"2\\"><br /><input type=\\"submit\\" value=\\" Sign Up \\" class=\\"bginput\\" /></td>
		</tr>
	</form>
	</table><br /></td>
</tr>
") : (\'\'))."
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Forget your password?</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\" align=\\"center\\"><span class=\\"normalfont\\">Use the form below to recover it.<br /><br />
	<form method=\\"post\\" action=\\"user.lostpw.php{$GLOBALS[session_url]}\\">
	<input type=\\"hidden\\" name=\\"do\\" value=\\"verify\\">
	<table cellpadding=\\"2\\">
		<tr>
			<td valign=\\"middle\\" align=\\"right\\">Your account name:&nbsp;</td>
			<td align=\\"left\\"><input type=\\"text\\" name=\\"username\\" class=\\"bginput\\" /> <select name=\\"userdomain\\">$domainname_options</select></td>
		</tr>
		<tr>
			<td align=\\"center\\" colspan=\\"2\\"><br /><input type=\\"submit\\" value=\\"Get New Password\\" class=\\"bginput\\" /></td>
		</tr>
	</form>
	</table></td>
</tr>
</table>

<br />

$GLOBALS[footer]

</body>
</html>"',
  ),
  'mailbit_to' => 
  array (
    'templategroupid' => '16',
    'user_data' => '	<td class="$bgcolors[name]Cell" width="25%" align="left" onContextMenu="checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;" onClick="checkMail($mail[messageid], 0, 1);"><span class="normalfont">$mail[recipients]</span></td>
',
    'parsed_data' => '"	<td class=\\"$bgcolors[name]Cell\\" width=\\"25%\\" align=\\"left\\" onContextMenu=\\"checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;\\" onClick=\\"checkMail($mail[messageid], 0, 1);\\"><span class=\\"normalfont\\">$mail[recipients]</span></td>
"',
  ),
  'notification_message' => 
  array (
    'templategroupid' => '12',
    'user_data' => 'You have new mail from: $good[fromname] <$good[fromemail]>, the subject provided was $good[subject]

You can view your e-mail here: $gotourl',
    'parsed_data' => '"You have new mail from: $good[fromname] <$good[fromemail]>, the subject provided was $good[subject]

You can view your e-mail here: $gotourl"',
  ),
  'notification_subject' => 
  array (
    'templategroupid' => '12',
    'user_data' => 'You have new mail',
    'parsed_data' => '"You have new mail"',
  ),
  'options_compose' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Compose Options</title>
$css
</head>
<body>
$header

<form action="options.compose.php" method="post">
<input type="hidden" name="do" value="update" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Reply Settings</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Include original message:</b></span>
	<br />
	<span class="smallfont">Enable this to include the original message when forwarding or replying to an email.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="includeorig" value="1" id="includeorigon" $includeorigon /> <label for="includeorigon">Yes</label><br /><input type="radio" name="includeorig" value="0" id="includeorigoff" $includeorigoff /> <label for="includeorigoff">No</label></span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Original message prefix:</b></span>
	<br />
	<span class="smallfont">When replying to a message, if you have the option above enabled,<br />each line of the original message will be prefixed with this string:</span></td>
	<td class="highRightCell" width="40%"><input type="text" class="bginput" name="replychar" value="$hiveuser[replychar]" size="15" maxlength="15" /></td>
</tr>
<!-- +++++++++++++++++++++++++++++++++++++ -->
<%if $hiveuser[\'cansendhtml\'] %>
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>WYSIWYG Editor Settings</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Enable advanced WYSIWYG editor:</b></span>
	<br />
	<span class="smallfont">Turn this on to use the \'What You See Is What You Get\' editor by default.<br />This editor only works under Windows with Internet Explorer 5.0+.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="wysiwyg" value="1" id="wysiwygon" $wysiwygon /> <label for="wysiwygon">Yes</label><br /><input type="radio" name="wysiwyg" value="0" id="wysiwygoff" $wysiwygoff /> <label for="wysiwygoff">No</label></span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Default font:</b></span>
	<br />
	<span class="smallfont">This is the font outgoing messages will be sent with, unless you change it.</span></td>
	<td class="highRightCell" width="40%">
		<table>
			<tr>
				<td>Font:</td>
				<td>Size:</td>
				<td>Style:</td>
				<td>Color:</td>
			</tr>
			<tr>
				<td>
					<select name="fontname">
						<option value="Arial" $fontnamesel[arial]>Arial</option>
						<option value="Arial Black" $fontnamesel[arialblack]>Arial Black</option>
						<option value="Arial Narrow" $fontnamesel[arialnarrow]>Arial Narrow</option>
						<option value="Comic Sans MS" $fontnamesel[comicsansms]>Comic Sans MS</option>
						<option value="Courier New" $fontnamesel[couriernew]>Courier New</option>
						<option value="System" $fontnamesel[system]>System</option>
						<option value="Tahoma" $fontnamesel[tahoma]>Tahoma</option>
						<option value="Times New Roman" $fontnamesel[timesnewroman]>Times New Roman</option>
						<option value="Verdana" $fontnamesel[verdana]>Verdana</option>
						<option value="Wingdings" $fontnamesel[wingdings]>Wingdings</option>
					</select>
				</td>
				<td>
					<select name="fontsize">
						<option value="8" $fontsizesel[8]>8</option>
						<option value="9" $fontsizesel[9]>9</option>
						<option value="10" $fontsizesel[10]>10</option>
						<option value="11" $fontsizesel[11]>11</option>
						<option value="12" $fontsizesel[12]>12</option>
						<option value="14" $fontsizesel[14]>14</option>
						<option value="16" $fontsizesel[16]>16</option>
						<option value="18" $fontsizesel[18]>18</option>
						<option value="20" $fontsizesel[20]>20</option>
						<option value="22" $fontsizesel[22]>22</option>
						<option value="24" $fontsizesel[24]>24</option>
						<option value="26" $fontsizesel[26]>26</option>
						<option value="28" $fontsizesel[28]>28</option>
						<option value="36" $fontsizesel[36]>36</option>
					</select>
				</td>
				<td>
					<select name="fontstyle">
						<option value="Regular" $fontstylesel[regular]>Regular</option>
						<option value="Italic" $fontstylesel[italic]>Italic</option>
						<option value="Bold" $fontstylesel[bold]>Bold</option>
						<option value="Bold Italic" $fontstylesel[bolditalic]>Bold Italic</option>
					</select>
				</td>
				<td>
					<select name="fontcolor">
						<option value="Black" style="color: Black;" $fontcolorsel[black]>Black</option>
						<option value="Maroon" style="color: Maroon;" $fontcolorsel[maroon]>Maroon</option>
						<option value="Green" style="color: Green;" $fontcolorsel[green]>Green</option>
						<option value="Olive" style="color: Olive;" $fontcolorsel[olive]>Olive</option>
						<option value="Navy" style="color: Navy;" $fontcolorsel[navy]>Navy</option>
						<option value="Purple" style="color: Purple;" $fontcolorsel[purple]>Purple</option>
						<option value="Teal" style="color: Teal;" $fontcolorsel[teal]>Teal</option>
						<option value="Gray" style="color: Gray;" $fontcolorsel[gray]>Gray</option>
						<option value="Silver" style="color: Silver;" $fontcolorsel[silver]>Silver</option>
						<option value="Red" style="color: Red;" $fontcolorsel[red]>Red</option>
						<option value="Lime" style="color: Lime;" $fontcolorsel[lime]>Lime</option>
						<option value="Yellow" style="color: Yellow;" $fontcolorsel[yellow]>Yellow</option>
						<option value="Blue" style="color: Blue;" $fontcolorsel[blue]>Blue</option>
						<option value="Fuchsia" style="color: Fuchsia;" $fontcolorsel[fuchsia]>Fuchsia</option>
						<option value="Aqua" style="color: Aqua;" $fontcolorsel[aqua]>Aqua</option>
						<option value="White" style="color: Black;" $fontcolorsel[white]>White</option>
					</select>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Default background color:</b></span>
	<br />
	<span class="smallfont">This is the background color outgoing messages will have, unless you change it.</span></td>
	<td class="normalRightCell" width="40%">
		<select name="bgcolor">
			<option value="None" style="color: Black;" $bgcolorsel[none]>None</option>
			<option value="White" style="color: Black;" $bgcolorsel[white]>White</option>
			<option value="Aqua" style="color: Aqua;" $bgcolorsel[aqua]>Aqua</option>
			<option value="Fuchsia" style="color: Fuchsia;" $bgcolorsel[fuchsia]>Fuchsia</option>
			<option value="Blue" style="color: Blue;" $bgcolorsel[blue]>Blue</option>
			<option value="Yellow" style="color: Yellow;" $bgcolorsel[yellow]>Yellow</option>
			<option value="Lime" style="color: Lime;" $bgcolorsel[lime]>Lime</option>
			<option value="Red" style="color: Red;" $bgcolorsel[red]>Red</option>
			<option value="Silver" style="color: Silver;" $bgcolorsel[silver]>Silver</option>
			<option value="Gray" style="color: Gray;" $bgcolorsel[gray]>Gray</option>
			<option value="Teal" style="color: Teal;" $bgcolorsel[teal]>Teal</option>
			<option value="Purple" style="color: Purple;" $bgcolorsel[purple]>Purple</option>
			<option value="Navy" style="color: Navy;" $bgcolorsel[navy]>Navy</option>
			<option value="Olive" style="color: Olive;" $bgcolorsel[olive]>Olive</option>
			<option value="Green" style="color: Green;" $bgcolorsel[green]>Green</option>
			<option value="Maroon" style="color: Maroon;" $bgcolorsel[maroon]>Maroon</option>
			<option value="Black" style="color: Black;" $bgcolorsel[black]>Black</option>
		</select>
	</td>
</tr>
<!-- +++++++++++++++++++++++++++++++++++++ --><%endif%>
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Miscellaneous Settings</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Default Reply-To address:</b></span>
	<br />
	<span class="smallfont">The default address the Reply-To field contains.</span></td>
	<td class="normalRightCell" width="40%"><input type="text" class="bginput" name="replyto" value="$hiveuser[replyto]" size="40" /></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Request read receipt:</b></span>
	<br />
	<span class="smallfont">Always request a read receipt for all outgoing messages.</span></td>
	<td class="highRightCell" width="40%"><span class="normalfont"><input type="radio" name="requestread" value="1" id="requestreadon" $requestreadon /> <label for="requestreadon">Yes</label><br /><input type="radio" name="requestread" value="0" id="requestreadoff" $requestreadoff /> <label for="requestreadoff">No</label></span></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Save copy of sent messages:</b></span>
	<br />
	<span class="smallfont">Keep a copy of messages you send in the Sent Items folder.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="savecopy" value="1" id="savecopyon" $savecopyon /> <label for="savecopyon">Yes</label><br /><input type="radio" name="savecopy" value="0" id="savecopyoff" $savecopyoff /> <label for="savecopyoff">No</label></span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Folder to return to:</b></span>
	<br />
	<span class="smallfont">Choose if you\'d like to be taken to your Inbox or Sent Items folders after sending a message.</span></td>
	<td class="highRightCell" width="40%"><span class="normalfont"><input type="radio" name="returnsent" value="0" id="returnsentoff" $returnsentoff /> <label for="returnsentoff">Inbox</label><br /><input type="radio" name="returnsent" value="1" id="returnsenton" $returnsenton /> <label for="returnsenton">Sent Items</label></span></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Add recipients to address book:</b></span>
	<br />
	<span class="smallfont">Automatically add recipients of outgoing messages to your address book.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="addrecips" value="1" id="addrecipson" $addrecipson /> <label for="addrecipson">Yes</label><br /><input type="radio" name="addrecips" value="0" id="addrecipsoff" $addrecipsoff /><label for="addrecipsoff">No</label></span></td>
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
<head><title>$appname: Compose Options</title>
$GLOBALS[css]
</head>
<body>
$GLOBALS[header]

<form action=\\"options.compose.php{$GLOBALS[session_url]}\\" method=\\"post\\">
<input type=\\"hidden\\" name=\\"do\\" value=\\"update\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Reply Settings</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Include original message:</b></span>
	<br />
	<span class=\\"smallfont\\">Enable this to include the original message when forwarding or replying to an email.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"includeorig\\" value=\\"1\\" id=\\"includeorigon\\" $includeorigon /> <label for=\\"includeorigon\\">Yes</label><br /><input type=\\"radio\\" name=\\"includeorig\\" value=\\"0\\" id=\\"includeorigoff\\" $includeorigoff /> <label for=\\"includeorigoff\\">No</label></span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Original message prefix:</b></span>
	<br />
	<span class=\\"smallfont\\">When replying to a message, if you have the option above enabled,<br />each line of the original message will be prefixed with this string:</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"replychar\\" value=\\"$hiveuser[replychar]\\" size=\\"15\\" maxlength=\\"15\\" /></td>
</tr>
<!-- +++++++++++++++++++++++++++++++++++++ -->
".(($hiveuser[\'cansendhtml\'] ) ? ("
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>WYSIWYG Editor Settings</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Enable advanced WYSIWYG editor:</b></span>
	<br />
	<span class=\\"smallfont\\">Turn this on to use the \'What You See Is What You Get\' editor by default.<br />This editor only works under Windows with Internet Explorer 5.0+.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"wysiwyg\\" value=\\"1\\" id=\\"wysiwygon\\" $wysiwygon /> <label for=\\"wysiwygon\\">Yes</label><br /><input type=\\"radio\\" name=\\"wysiwyg\\" value=\\"0\\" id=\\"wysiwygoff\\" $wysiwygoff /> <label for=\\"wysiwygoff\\">No</label></span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Default font:</b></span>
	<br />
	<span class=\\"smallfont\\">This is the font outgoing messages will be sent with, unless you change it.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\">
		<table>
			<tr>
				<td>Font:</td>
				<td>Size:</td>
				<td>Style:</td>
				<td>Color:</td>
			</tr>
			<tr>
				<td>
					<select name=\\"fontname\\">
						<option value=\\"Arial\\" $fontnamesel[arial]>Arial</option>
						<option value=\\"Arial Black\\" $fontnamesel[arialblack]>Arial Black</option>
						<option value=\\"Arial Narrow\\" $fontnamesel[arialnarrow]>Arial Narrow</option>
						<option value=\\"Comic Sans MS\\" $fontnamesel[comicsansms]>Comic Sans MS</option>
						<option value=\\"Courier New\\" $fontnamesel[couriernew]>Courier New</option>
						<option value=\\"System\\" $fontnamesel[system]>System</option>
						<option value=\\"Tahoma\\" $fontnamesel[tahoma]>Tahoma</option>
						<option value=\\"Times New Roman\\" $fontnamesel[timesnewroman]>Times New Roman</option>
						<option value=\\"Verdana\\" $fontnamesel[verdana]>Verdana</option>
						<option value=\\"Wingdings\\" $fontnamesel[wingdings]>Wingdings</option>
					</select>
				</td>
				<td>
					<select name=\\"fontsize\\">
						<option value=\\"8\\" $fontsizesel[8]>8</option>
						<option value=\\"9\\" $fontsizesel[9]>9</option>
						<option value=\\"10\\" $fontsizesel[10]>10</option>
						<option value=\\"11\\" $fontsizesel[11]>11</option>
						<option value=\\"12\\" $fontsizesel[12]>12</option>
						<option value=\\"14\\" $fontsizesel[14]>14</option>
						<option value=\\"16\\" $fontsizesel[16]>16</option>
						<option value=\\"18\\" $fontsizesel[18]>18</option>
						<option value=\\"20\\" $fontsizesel[20]>20</option>
						<option value=\\"22\\" $fontsizesel[22]>22</option>
						<option value=\\"24\\" $fontsizesel[24]>24</option>
						<option value=\\"26\\" $fontsizesel[26]>26</option>
						<option value=\\"28\\" $fontsizesel[28]>28</option>
						<option value=\\"36\\" $fontsizesel[36]>36</option>
					</select>
				</td>
				<td>
					<select name=\\"fontstyle\\">
						<option value=\\"Regular\\" $fontstylesel[regular]>Regular</option>
						<option value=\\"Italic\\" $fontstylesel[italic]>Italic</option>
						<option value=\\"Bold\\" $fontstylesel[bold]>Bold</option>
						<option value=\\"Bold Italic\\" $fontstylesel[bolditalic]>Bold Italic</option>
					</select>
				</td>
				<td>
					<select name=\\"fontcolor\\">
						<option value=\\"Black\\" style=\\"color: Black;\\" $fontcolorsel[black]>Black</option>
						<option value=\\"Maroon\\" style=\\"color: Maroon;\\" $fontcolorsel[maroon]>Maroon</option>
						<option value=\\"Green\\" style=\\"color: Green;\\" $fontcolorsel[green]>Green</option>
						<option value=\\"Olive\\" style=\\"color: Olive;\\" $fontcolorsel[olive]>Olive</option>
						<option value=\\"Navy\\" style=\\"color: Navy;\\" $fontcolorsel[navy]>Navy</option>
						<option value=\\"Purple\\" style=\\"color: Purple;\\" $fontcolorsel[purple]>Purple</option>
						<option value=\\"Teal\\" style=\\"color: Teal;\\" $fontcolorsel[teal]>Teal</option>
						<option value=\\"Gray\\" style=\\"color: Gray;\\" $fontcolorsel[gray]>Gray</option>
						<option value=\\"Silver\\" style=\\"color: Silver;\\" $fontcolorsel[silver]>Silver</option>
						<option value=\\"Red\\" style=\\"color: Red;\\" $fontcolorsel[red]>Red</option>
						<option value=\\"Lime\\" style=\\"color: Lime;\\" $fontcolorsel[lime]>Lime</option>
						<option value=\\"Yellow\\" style=\\"color: Yellow;\\" $fontcolorsel[yellow]>Yellow</option>
						<option value=\\"Blue\\" style=\\"color: Blue;\\" $fontcolorsel[blue]>Blue</option>
						<option value=\\"Fuchsia\\" style=\\"color: Fuchsia;\\" $fontcolorsel[fuchsia]>Fuchsia</option>
						<option value=\\"Aqua\\" style=\\"color: Aqua;\\" $fontcolorsel[aqua]>Aqua</option>
						<option value=\\"White\\" style=\\"color: Black;\\" $fontcolorsel[white]>White</option>
					</select>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Default background color:</b></span>
	<br />
	<span class=\\"smallfont\\">This is the background color outgoing messages will have, unless you change it.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\">
		<select name=\\"bgcolor\\">
			<option value=\\"None\\" style=\\"color: Black;\\" $bgcolorsel[none]>None</option>
			<option value=\\"White\\" style=\\"color: Black;\\" $bgcolorsel[white]>White</option>
			<option value=\\"Aqua\\" style=\\"color: Aqua;\\" $bgcolorsel[aqua]>Aqua</option>
			<option value=\\"Fuchsia\\" style=\\"color: Fuchsia;\\" $bgcolorsel[fuchsia]>Fuchsia</option>
			<option value=\\"Blue\\" style=\\"color: Blue;\\" $bgcolorsel[blue]>Blue</option>
			<option value=\\"Yellow\\" style=\\"color: Yellow;\\" $bgcolorsel[yellow]>Yellow</option>
			<option value=\\"Lime\\" style=\\"color: Lime;\\" $bgcolorsel[lime]>Lime</option>
			<option value=\\"Red\\" style=\\"color: Red;\\" $bgcolorsel[red]>Red</option>
			<option value=\\"Silver\\" style=\\"color: Silver;\\" $bgcolorsel[silver]>Silver</option>
			<option value=\\"Gray\\" style=\\"color: Gray;\\" $bgcolorsel[gray]>Gray</option>
			<option value=\\"Teal\\" style=\\"color: Teal;\\" $bgcolorsel[teal]>Teal</option>
			<option value=\\"Purple\\" style=\\"color: Purple;\\" $bgcolorsel[purple]>Purple</option>
			<option value=\\"Navy\\" style=\\"color: Navy;\\" $bgcolorsel[navy]>Navy</option>
			<option value=\\"Olive\\" style=\\"color: Olive;\\" $bgcolorsel[olive]>Olive</option>
			<option value=\\"Green\\" style=\\"color: Green;\\" $bgcolorsel[green]>Green</option>
			<option value=\\"Maroon\\" style=\\"color: Maroon;\\" $bgcolorsel[maroon]>Maroon</option>
			<option value=\\"Black\\" style=\\"color: Black;\\" $bgcolorsel[black]>Black</option>
		</select>
	</td>
</tr>
<!-- +++++++++++++++++++++++++++++++++++++ -->") : \'\')."
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Miscellaneous Settings</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Default Reply-To address:</b></span>
	<br />
	<span class=\\"smallfont\\">The default address the Reply-To field contains.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"replyto\\" value=\\"$hiveuser[replyto]\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Request read receipt:</b></span>
	<br />
	<span class=\\"smallfont\\">Always request a read receipt for all outgoing messages.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"requestread\\" value=\\"1\\" id=\\"requestreadon\\" $requestreadon /> <label for=\\"requestreadon\\">Yes</label><br /><input type=\\"radio\\" name=\\"requestread\\" value=\\"0\\" id=\\"requestreadoff\\" $requestreadoff /> <label for=\\"requestreadoff\\">No</label></span></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Save copy of sent messages:</b></span>
	<br />
	<span class=\\"smallfont\\">Keep a copy of messages you send in the Sent Items folder.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"savecopy\\" value=\\"1\\" id=\\"savecopyon\\" $savecopyon /> <label for=\\"savecopyon\\">Yes</label><br /><input type=\\"radio\\" name=\\"savecopy\\" value=\\"0\\" id=\\"savecopyoff\\" $savecopyoff /> <label for=\\"savecopyoff\\">No</label></span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Folder to return to:</b></span>
	<br />
	<span class=\\"smallfont\\">Choose if you\'d like to be taken to your Inbox or Sent Items folders after sending a message.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"returnsent\\" value=\\"0\\" id=\\"returnsentoff\\" $returnsentoff /> <label for=\\"returnsentoff\\">Inbox</label><br /><input type=\\"radio\\" name=\\"returnsent\\" value=\\"1\\" id=\\"returnsenton\\" $returnsenton /> <label for=\\"returnsenton\\">Sent Items</label></span></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Add recipients to address book:</b></span>
	<br />
	<span class=\\"smallfont\\">Automatically add recipients of outgoing messages to your address book.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"addrecips\\" value=\\"1\\" id=\\"addrecipson\\" $addrecipson /> <label for=\\"addrecipson\\">Yes</label><br /><input type=\\"radio\\" name=\\"addrecips\\" value=\\"0\\" id=\\"addrecipsoff\\" $addrecipsoff /><label for=\\"addrecipsoff\\">No</label></span></td>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Save Settings\\" />
		<input type=\\"reset\\" class=\\"bginput\\" name=\\"reset\\" value=\\"Reset Fields\\" />
	</td>
</tr>
</table>

</form>

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
	<td class="highRightCell" width="40%"><span class="normalfont"><input type="radio" name="emptybin" value="-2" id="emptybinonexit" $emptybinonexit /> <label for="emptybinonexit">Empty folder on exit</label><br /><input type="radio" name="emptybin" value="1" id="emptybinevery" $emptybinevery /> <label for="emptybinevery">Empty folder every &nbsp;<input type="text" class="bginput" name="binevery" value="$binevery" size="3" maxlength="3" onClick="emptybinevery.checked = true;" />&nbsp; days</label><br /><input type="radio" name="emptybin" value="-1" id="emptybinno" $emptybinno /> <label for="emptybinno">Never empty folder</label></span></td>
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

<form action=\\"options.general.php{$GLOBALS[session_url]}\\" method=\\"post\\" enctype=\\"multipart/form-data\\">
<input type=\\"hidden\\" name=\\"do\\" value=\\"update\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>General Options</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Skin:</b></span>
	<br />
	<span class=\\"smallfont\\">You can choose from several skins that change the look of this program.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><select name=\\"skinid\\">
		$skinoptions
	</select></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Empty the trash can automatically:</b></span>
	<br />
	<span class=\\"smallfont\\">If you want the system to automatically delete all messages<br />from your trash can, please select the appropriate option.<br />If this is turned on, messages in your trash can don\'t count<br />towards your account storage limit.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"emptybin\\" value=\\"-2\\" id=\\"emptybinonexit\\" $emptybinonexit /> <label for=\\"emptybinonexit\\">Empty folder on exit</label><br /><input type=\\"radio\\" name=\\"emptybin\\" value=\\"1\\" id=\\"emptybinevery\\" $emptybinevery /> <label for=\\"emptybinevery\\">Empty folder every &nbsp;<input type=\\"text\\" class=\\"bginput\\" name=\\"binevery\\" value=\\"$binevery\\" size=\\"3\\" maxlength=\\"3\\" onClick=\\"emptybinevery.checked = true;\\" />&nbsp; days</label><br /><input type=\\"radio\\" name=\\"emptybin\\" value=\\"-1\\" id=\\"emptybinno\\" $emptybinno /> <label for=\\"emptybinno\\">Never empty folder</label></span></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Play sound when new messages arrive:</b></span>
	<br />
	<span class=\\"smallfont\\">Play the \\"You\'ve got mail\\" sound whenever new messages arrive in your mail box.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"playsound\\" value=\\"1\\" id=\\"playsoundon\\" $playsoundon /><label for=\\"playsoundon\\">Yes<label><br /><input type=\\"radio\\" name=\\"playsound\\" value=\\"0\\" id=\\"playsoundoff\\" $playsoundoff /><label for=\\"playsoundoff\\">No<label></span></td>
</tr>
".(($hiveuser[cansound] ) ? ("
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Sound to play:</b></span>
	<br />
	<span class=\\"smallfont\\">This is the sound you will hear if the option above is enabled.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\">
		<input type=\\"radio\\" name=\\"soundoption\\" value=\\"0\\" id=\\"soundoptiondef\\" $soundoptiondef /> <label for=\\"soundoptiondef\\">Use <a href=\\"user.sound.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}soundid=0\\" target=\\"_blank\\">default</a>.<label><br />
		".(($hiveuser[soundid] != 0 ) ? ("<input type=\\"radio\\" name=\\"soundoption\\" value=\\"1\\" id=\\"soundoptioncus\\" $soundoptioncus /> <label for=\\"soundoptioncus\\">Use custom file (<a href=\\"user.sound.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}soundid=$hiveuser[soundid]\\" target=\\"_blank\\">$cursound</a>).<label><br />") : \'\')."
		<input type=\\"radio\\" name=\\"soundoption\\" value=\\"2\\" id=\\"soundoptionnew\\" /> <label for=\\"soundoptionnew\\">Upload new file: <input type=\\"file\\" class=\\"bginput\\" name=\\"newsound\\" onClick=\\"this.form.soundoptionnew.checked = true;\\" /><input type=\\"hidden\\" name=\\"MAX_FILE_SIZE\\" value=\\"$maxsoundfile\\" /><label>
	</span></td>
</tr>
") : \'\')."
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Auto-forwarding:</b></span>
	<br />
	<span class=\\"smallfont\\">Emails that you receive will automatically be forwarded to this address.<br />Set this to nothing to disable the feature.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"forward\\" value=\\"$hiveuser[forward]\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Email notification:</b></span>
	<br />
	<span class=\\"smallfont\\">A notifcation will be sent to this address every time you recieve an email.<br />Set this to nothing to disable the feature.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"notifyemail\\" value=\\"$hiveuser[notifyemail]\\" size=\\"40\\" /></td>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Save Settings\\" />
		<input type=\\"reset\\" class=\\"bginput\\" name=\\"reset\\" value=\\"Reset Fields\\" />
	</td>
</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'options_menu' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Preferences</title>
$css
</head>
<body>
$header

<table align="left">
	<tr>
		<td colspan="2" style="padding: 0px 12px 18px 12px;"><table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			<tr class="headerRow">
				<th class="headerBothCell"><span class="normalfonttablehead">Preferences</span></th>
			</tr>
		</table></td>
	</tr>
	<tr>
		<td style="padding: 12px; padding-top: 0px;">

<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="options.personal.php"><span class="normalfonttablehead"><b>Personal Information</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Edit personal information such as your name, location and birthday.</span></td>
</tr>
</table>

		</td>
		<td style="padding: 12px; padding-top: 0px;">

<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="options.password.php"><span class="normalfonttablehead"><b>Password and Security</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Change your account password or your secret question and answer.</span></td>
</tr>
</table>

		</td>
	</tr>
	<tr>
		<td style="padding: 12px;">

<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="options.folderview.php"><span class="normalfonttablehead"><b>Folder View Options</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Settings that pertain to folder viewing, such as using the preview pane, selecting columns to show and more.</span></td>
</tr>
</table>

		</td>
		<td style="padding: 12px;">

<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="options.general.php"><span class="normalfonttablehead"><b>General Options</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Miscellaneous settings, such as new mail sound, changing skins, and more.</span></td>
</tr>
</table>

		</td>
	</tr>
	<tr>
		<td style="padding: 12px;">

<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="options.read.php"><span class="normalfonttablehead"><b>Reading Options</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Settings that pertain to reading messages, such as showing HTML, sending read receipts and more.</span></td>
</tr>
</table>

		</td>
		<td style="padding: 12px;">

<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="options.compose.php"><span class="normalfonttablehead"><b>Compose Options</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Settings that pertain to composing messages, such as using the WYSIWYG editor, including original message, and more.</span></td>
</tr>
</table>

		</td>
	</tr>
<%if $hiveuser[canrule] %>
	<tr>
		<td style="padding: 12px;">

<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="rules.list.php"><span class="normalfonttablehead"><b>Message Rules and Spam Filtering</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Modify or add new message filters to avoid junk mail and edit the blocked and safe senders lists.</span></td>
</tr>
</table>

		</td>
<%endif%>
<%if $hiveuser[canpop] %>
	<%if !$hiveuser[canrule] %>
	<tr>
	<%endif%>
		<td style="padding: 12px;">

<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="pop.list.php"><span class="normalfonttablehead"><b>POP Accounts</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Have emails from other addresses delivered directly to $appname.</span></td>
</tr>
</table>

		</td>
<%endif%>
<%if $hiveuser[canrule] and $hiveuser[canpop] %>
	</tr>
	<tr style="padding-bottom: 0px;">
<%elseif !$hiveuser[canpop] and !$hiveuser[canrule] %>
	<tr style="padding-bottom: 0px;">
<%endif%>
		<td style="padding: 12px;">

<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="options.signature.php"><span class="normalfonttablehead"><b>Signatures</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Options about your signature and the signature editor.</span></td>
</tr>
</table>

		</td>
<%if !$hiveuser[canrule] and !$hiveuser[canpop] %>
<%elseif !$hiveuser[canrule] or !$hiveuser[canpop] %>
	</tr>
	<tr style="padding-bottom: 0px;">
<%endif%>	
<%if $hiveuser[canfolder] %>
		<td style="padding: 12px;">

<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="folders.list.php"><span class="normalfonttablehead"><b>Folders Management</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Create, rename, delete or empty your mail folders.</span></td>
</tr>
</table>

		</td>
<%else%>
		<td>&nbsp;</td>
<%endif%>
	</tr>
</table>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Preferences</title>
$GLOBALS[css]
</head>
<body>
$GLOBALS[header]

<table align=\\"left\\">
	<tr>
		<td colspan=\\"2\\" style=\\"padding: 0px 12px 18px 12px;\\"><table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			<tr class=\\"headerRow\\">
				<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\">Preferences</span></th>
			</tr>
		</table></td>
	</tr>
	<tr>
		<td style=\\"padding: 12px; padding-top: 0px;\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"options.personal.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Personal Information</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Edit personal information such as your name, location and birthday.</span></td>
</tr>
</table>

		</td>
		<td style=\\"padding: 12px; padding-top: 0px;\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"options.password.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Password and Security</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Change your account password or your secret question and answer.</span></td>
</tr>
</table>

		</td>
	</tr>
	<tr>
		<td style=\\"padding: 12px;\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"options.folderview.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Folder View Options</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Settings that pertain to folder viewing, such as using the preview pane, selecting columns to show and more.</span></td>
</tr>
</table>

		</td>
		<td style=\\"padding: 12px;\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"options.general.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>General Options</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Miscellaneous settings, such as new mail sound, changing skins, and more.</span></td>
</tr>
</table>

		</td>
	</tr>
	<tr>
		<td style=\\"padding: 12px;\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"options.read.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Reading Options</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Settings that pertain to reading messages, such as showing HTML, sending read receipts and more.</span></td>
</tr>
</table>

		</td>
		<td style=\\"padding: 12px;\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"options.compose.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Compose Options</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Settings that pertain to composing messages, such as using the WYSIWYG editor, including original message, and more.</span></td>
</tr>
</table>

		</td>
	</tr>
".(($hiveuser[canrule] ) ? ("
	<tr>
		<td style=\\"padding: 12px;\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"rules.list.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Message Rules and Spam Filtering</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Modify or add new message filters to avoid junk mail and edit the blocked and safe senders lists.</span></td>
</tr>
</table>

		</td>
") : \'\')."
".(($hiveuser[canpop] ) ? ("
	".((!$hiveuser[canrule] ) ? ("
	<tr>
	") : \'\')."
		<td style=\\"padding: 12px;\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"pop.list.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>POP Accounts</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Have emails from other addresses delivered directly to $appname.</span></td>
</tr>
</table>

		</td>
") : \'\')."
".(($hiveuser[canrule] and $hiveuser[canpop] ) ? ("
	</tr>
	<tr style=\\"padding-bottom: 0px;\\">
") : (((!$hiveuser[canpop] and !$hiveuser[canrule] ) ? ("
	<tr style=\\"padding-bottom: 0px;\\">
") : "")))."
		<td style=\\"padding: 12px;\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"options.signature.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Signatures</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Options about your signature and the signature editor.</span></td>
</tr>
</table>

		</td>
".((!$hiveuser[canrule] and !$hiveuser[canpop] ) ? ("
") : (((!$hiveuser[canrule] or !$hiveuser[canpop] ) ? ("
	</tr>
	<tr style=\\"padding-bottom: 0px;\\">
") : "")))."	
".(($hiveuser[canfolder] ) ? ("
		<td style=\\"padding: 12px;\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"folders.list.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Folders Management</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Create, rename, delete or empty your mail folders.</span></td>
</tr>
</table>

		</td>
") : ("
		<td>&nbsp;</td>
"))."
	</tr>
</table>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'options_signature' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Signatures</title>
$css
<script type="text/javascript" src="misc/signatures.js"></script>
</head>
<body>
$header

<form action="options.signature.php" method="post" name="sigform" onSubmit="updateSigDisplay(this);">
<input type="hidden" name="do" value="update" />

<!-- Current, default and new signatures -->
<input type="hidden" name="cursig" value="sig0" />
<input type="hidden" name="defsig" value="$defsig" />
<input type="hidden" name="newsig" value="" />
<input type="hidden" name="delsig" value="" />
<!-- Signatures text -->
$sig_text
<!-- Signatures title -->
$sig_title

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Your Signatures</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Automatically add signature:</b></span>
	<br />
	<span class="smallfont">If this is turned on, the default signature you specify below will automatically be added<br />to your messages before they are sent.<br />Otherwise, you will have the option to add the signature manually.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="autoaddsig" value="2" id="autoaddsigon" $autoaddsigon /> <label for="autoaddsigon">Yes</label><br /><input type="radio" name="autoaddsig" value="1" id="autoaddsigonly" $autoaddsigonly /> <label for="autoaddsigonly">Only when not replying</label><br /><input type="radio" name="autoaddsig" value="0" id="autoaddsigoff" $autoaddsigoff /> <label for="autoaddsigoff">No</label></span></td>
</tr>
<tr class="highRow">
	<td class="highBothCell" colspan="2" width="100%" valign="top"><span class="normalfont"><b>Signature Editor:</b><br />
	To edit a signature, select it from the list below and edit it in large box.<br />
	To rename a signature, select it then click the Rename button below and enter the new name.<br />
	To mark your default signature, select it from the list and click the Make Default button below.<br />
	To create a new signature, click the Create New button below and enter the name of the new signature.<%if $totalsigs >= $hiveuser[\'maxsigs\'] %><br />(<b>Note</b>: You may only have up to $hiveuser[maxsigs] signatures. You won\'t be able to create new signatures until you delete at least some of your current signatures.)<%endif%><br />
	To delete a signature, select it from the list and click the Delete button below. <b>Note</b>: You cannot remove your default signature.<br />
	<br />
	Please remember to click the Update Signatures button at the bottom of this page, or else any changes that you make here will have no effect!</span><br /><br />
	<table>
		<tr>
			<td valign="top"><select name="sigs" size="9" onChange="updateSigDisplay(this.form);">
					$sig_options
				</select></td>
			<td valign="top"><textarea name="sigedit" cols="70" rows="8">(select a signature to edit from the list)</textarea></td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="button" name="rename" class="bginput" disabled="disabled" value="Rename" onClick="renameSig(this.form, this.form.sigs.options[this.form.sigs.selectedIndex].text);" /> <input type="button" name="makedef" class="bginput" disabled="disabled" value="Make Default" onClick="updateDefaultSig(this.form);" /> <input type="submit" name="createnew" class="bginput" value="Create New" onClick="return createNewSig(this.form);" <%if $totalsigs >= $hiveuser[\'maxsigs\'] %>disabled="disabled"<%endif%> /> <input type="submit" name="deletesig" disabled="disabled" class="bginput" value="Delete" onClick="return deleteSig(this.form);" />
			</td>
		</tr>
	</table></td>
</tr>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="submit" value="Update Signatures" />
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
<head><title>$appname: Signatures</title>
$GLOBALS[css]
<script type=\\"text/javascript\\" src=\\"misc/signatures.js\\"></script>
</head>
<body>
$GLOBALS[header]

<form action=\\"options.signature.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"sigform\\" onSubmit=\\"updateSigDisplay(this);\\">
<input type=\\"hidden\\" name=\\"do\\" value=\\"update\\" />

<!-- Current, default and new signatures -->
<input type=\\"hidden\\" name=\\"cursig\\" value=\\"sig0\\" />
<input type=\\"hidden\\" name=\\"defsig\\" value=\\"$defsig\\" />
<input type=\\"hidden\\" name=\\"newsig\\" value=\\"\\" />
<input type=\\"hidden\\" name=\\"delsig\\" value=\\"\\" />
<!-- Signatures text -->
$sig_text
<!-- Signatures title -->
$sig_title

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Your Signatures</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Automatically add signature:</b></span>
	<br />
	<span class=\\"smallfont\\">If this is turned on, the default signature you specify below will automatically be added<br />to your messages before they are sent.<br />Otherwise, you will have the option to add the signature manually.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"autoaddsig\\" value=\\"2\\" id=\\"autoaddsigon\\" $autoaddsigon /> <label for=\\"autoaddsigon\\">Yes</label><br /><input type=\\"radio\\" name=\\"autoaddsig\\" value=\\"1\\" id=\\"autoaddsigonly\\" $autoaddsigonly /> <label for=\\"autoaddsigonly\\">Only when not replying</label><br /><input type=\\"radio\\" name=\\"autoaddsig\\" value=\\"0\\" id=\\"autoaddsigoff\\" $autoaddsigoff /> <label for=\\"autoaddsigoff\\">No</label></span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\" colspan=\\"2\\" width=\\"100%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Signature Editor:</b><br />
	To edit a signature, select it from the list below and edit it in large box.<br />
	To rename a signature, select it then click the Rename button below and enter the new name.<br />
	To mark your default signature, select it from the list and click the Make Default button below.<br />
	To create a new signature, click the Create New button below and enter the name of the new signature.".(($totalsigs >= $hiveuser[\'maxsigs\'] ) ? ("<br />(<b>Note</b>: You may only have up to $hiveuser[maxsigs] signatures. You won\'t be able to create new signatures until you delete at least some of your current signatures.)") : \'\')."<br />
	To delete a signature, select it from the list and click the Delete button below. <b>Note</b>: You cannot remove your default signature.<br />
	<br />
	Please remember to click the Update Signatures button at the bottom of this page, or else any changes that you make here will have no effect!</span><br /><br />
	<table>
		<tr>
			<td valign=\\"top\\"><select name=\\"sigs\\" size=\\"9\\" onChange=\\"updateSigDisplay(this.form);\\">
					$sig_options
				</select></td>
			<td valign=\\"top\\"><textarea name=\\"sigedit\\" cols=\\"70\\" rows=\\"8\\">(select a signature to edit from the list)</textarea></td>
		</tr>
		<tr>
			<td colspan=\\"2\\">
				<input type=\\"button\\" name=\\"rename\\" class=\\"bginput\\" disabled=\\"disabled\\" value=\\"Rename\\" onClick=\\"renameSig(this.form, this.form.sigs.options[this.form.sigs.selectedIndex].text);\\" /> <input type=\\"button\\" name=\\"makedef\\" class=\\"bginput\\" disabled=\\"disabled\\" value=\\"Make Default\\" onClick=\\"updateDefaultSig(this.form);\\" /> <input type=\\"submit\\" name=\\"createnew\\" class=\\"bginput\\" value=\\"Create New\\" onClick=\\"return createNewSig(this.form);\\" ".(($totalsigs >= $hiveuser[\'maxsigs\'] ) ? ("disabled=\\"disabled\\"") : \'\')." /> <input type=\\"submit\\" name=\\"deletesig\\" disabled=\\"disabled\\" class=\\"bginput\\" value=\\"Delete\\" onClick=\\"return deleteSig(this.form);\\" />
			</td>
		</tr>
	</table></td>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Update Signatures\\" />
		<input type=\\"reset\\" class=\\"bginput\\" name=\\"reset\\" value=\\"Reset Fields\\" />
	</td>
</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'pop' => 
  array (
    'templategroupid' => '15',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: POP Accounts</title>
$css
</head>
<body>
$header

<form action="pop.update.php" method="post" name="form">
<input type="hidden" name="do" value="update" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerLeftCell"><span class="normalfonttablehead"><b>POP Accounts</b></span></th>
	<th class="headerCell"><span class="normalfonttablehead"><b>Server</b></span></th>
	<th class="headerCell"><span class="normalfonttablehead"><b>Port</b></span></th>
	<th class="headerCell"><span class="normalfonttablehead"><b>Username</b></span></th>
	<th class="headerCell"><span class="normalfonttablehead"><b>Password</b></span></th>
	<th class="headerRightCell"><span class="normalfonttablehead"><b>Default Folder</b></span></th>
</tr>
$popbits
<tr>
	<td colspan="7"><span class="smallfonttablehead"><b>Leave password empty unless you want to change it. The Delete option controls whether or not messages are deleted after they are received from the POP server. If you do not check the box, messages will be left on the server so you will be able to fetch them with another mail program.</b></span></td>
</tr>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="submit" value="Save Changes" />
		<input type="reset" class="bginput" name="reset" value="Reset Fields" />
	</td>
</tr>
</table>

</form>

<br />

<form action="pop.update.php" method="post">
<input type="hidden" name="do" value="add" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow" >
	<th class="headerLeftCell"><span class="normalfonttablehead"><b>Add New Account</b></span></th>
	<th class="headerCell"><span class="normalfonttablehead"><b>Server</b></span></th>
	<th class="headerCell"><span class="normalfonttablehead"><b>Port</b></span></th>
	<th class="headerCell"><span class="normalfonttablehead"><b>Username</b></span></th>
	<th class="headerCell"><span class="normalfonttablehead"><b>Password</b></span></th>
	<th class="headerRightCell"><span class="normalfonttablehead"><b>Default Folder</b></span></th>
</tr>
<tr class="highRow" valign="top">
	<td class="highLeftCell"><span class="normalfont">New Account:</span></td>
	<td class="highCell" align="center"><input type="text" class="bginput" name="serverinfo[0][server]" value="" size="20" /></td>
	<td class="highCell" align="center"><input type="text" class="bginput" name="serverinfo[0][port]" value="110" size="20" /></td>
	<td class="highCell" align="center"><input type="text" class="bginput" name="serverinfo[0][username]" value="" size="20" /></td>
	<td class="highCell" align="center"><input type="password" class="bginput" name="serverinfo[0][password]" value="" size="20" /></td>
	<td class="highRightCell" align="center">
		<select name="serverinfo[0][folderid]">
			$newfolderbits
		</select></td>
</tr>
<tr class="highRow">
	<td class="highBothCell" valign="top" colspan="7"><span class="normalfont"><input type="checkbox" name="delete[0]" value="yes" checked="checked" id="delete0" /> <label for="delete0">Delete mails once recieved.</label><br /><input type="checkbox" name="active[0]" value="yes" checked="checked" id="active0" /> <label for="active0">Account active.</label></span></td>
</tr>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="submit" value="Add New Account" />
	</td>
</tr>
</table>

</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: POP Accounts</title>
$GLOBALS[css]
</head>
<body>
$GLOBALS[header]

<form action=\\"pop.update.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"do\\" value=\\"update\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerLeftCell\\"><span class=\\"normalfonttablehead\\"><b>POP Accounts</b></span></th>
	<th class=\\"headerCell\\"><span class=\\"normalfonttablehead\\"><b>Server</b></span></th>
	<th class=\\"headerCell\\"><span class=\\"normalfonttablehead\\"><b>Port</b></span></th>
	<th class=\\"headerCell\\"><span class=\\"normalfonttablehead\\"><b>Username</b></span></th>
	<th class=\\"headerCell\\"><span class=\\"normalfonttablehead\\"><b>Password</b></span></th>
	<th class=\\"headerRightCell\\"><span class=\\"normalfonttablehead\\"><b>Default Folder</b></span></th>
</tr>
$popbits
<tr>
	<td colspan=\\"7\\"><span class=\\"smallfonttablehead\\"><b>Leave password empty unless you want to change it. The Delete option controls whether or not messages are deleted after they are received from the POP server. If you do not check the box, messages will be left on the server so you will be able to fetch them with another mail program.</b></span></td>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Save Changes\\" />
		<input type=\\"reset\\" class=\\"bginput\\" name=\\"reset\\" value=\\"Reset Fields\\" />
	</td>
</tr>
</table>

</form>

<br />

<form action=\\"pop.update.php{$GLOBALS[session_url]}\\" method=\\"post\\">
<input type=\\"hidden\\" name=\\"do\\" value=\\"add\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\" >
	<th class=\\"headerLeftCell\\"><span class=\\"normalfonttablehead\\"><b>Add New Account</b></span></th>
	<th class=\\"headerCell\\"><span class=\\"normalfonttablehead\\"><b>Server</b></span></th>
	<th class=\\"headerCell\\"><span class=\\"normalfonttablehead\\"><b>Port</b></span></th>
	<th class=\\"headerCell\\"><span class=\\"normalfonttablehead\\"><b>Username</b></span></th>
	<th class=\\"headerCell\\"><span class=\\"normalfonttablehead\\"><b>Password</b></span></th>
	<th class=\\"headerRightCell\\"><span class=\\"normalfonttablehead\\"><b>Default Folder</b></span></th>
</tr>
<tr class=\\"highRow\\" valign=\\"top\\">
	<td class=\\"highLeftCell\\"><span class=\\"normalfont\\">New Account:</span></td>
	<td class=\\"highCell\\" align=\\"center\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"serverinfo[0][server]\\" value=\\"\\" size=\\"20\\" /></td>
	<td class=\\"highCell\\" align=\\"center\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"serverinfo[0][port]\\" value=\\"110\\" size=\\"20\\" /></td>
	<td class=\\"highCell\\" align=\\"center\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"serverinfo[0][username]\\" value=\\"\\" size=\\"20\\" /></td>
	<td class=\\"highCell\\" align=\\"center\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"serverinfo[0][password]\\" value=\\"\\" size=\\"20\\" /></td>
	<td class=\\"highRightCell\\" align=\\"center\\">
		<select name=\\"serverinfo[0][folderid]\\">
			$newfolderbits
		</select></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\" valign=\\"top\\" colspan=\\"7\\"><span class=\\"normalfont\\"><input type=\\"checkbox\\" name=\\"delete[0]\\" value=\\"yes\\" checked=\\"checked\\" id=\\"delete0\\" /> <label for=\\"delete0\\">Delete mails once recieved.</label><br /><input type=\\"checkbox\\" name=\\"active[0]\\" value=\\"yes\\" checked=\\"checked\\" id=\\"active0\\" /> <label for=\\"active0\\">Account active.</label></span></td>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Add New Account\\" />
	</td>
</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'pop_accountbit' => 
  array (
    'templategroupid' => '15',
    'user_data' => '<tr class="$class[name]Row" valign="middle">
	<td class="$class[name]LeftCell" valign="top"><span class="normalfont">$pop[server]:</span><span class="smallfont"><br />[<a href="pop.delete.php?popid=$pop[popid]" onClick="return confirm(\'Are you sure you want to remove this account?\');">remove</a>]</span></td>
	<td class="$class[name]Cell" align="center"><input type="text" class="bginput" name="serverinfo[$pop[popid]][server]" value="$pop[server]" size="20" /></td>
	<td class="$class[name]Cell" align="center"><input type="text" class="bginput" name="serverinfo[$pop[popid]][port]" value="$pop[port]" size="20" /></td>
	<td class="$class[name]Cell" align="center"><input type="text" class="bginput" name="serverinfo[$pop[popid]][username]" value="$pop[username]" size="20" /></td>
	<td class="$class[name]Cell" align="center"><input type="password" class="bginput" name="serverinfo[$pop[popid]][password]" value="" size="20" /></td>
	<td class="$class[name]RightCell" align="center">
		<select name="serverinfo[$pop[popid]][folderid]">
			$folderbits
		</select></td>
</tr>
<tr class="$class[name]Row" valign="top">
	<td class="$class[name]BothCell" colspan="7"><span class="normalfont"><input type="checkbox" name="delete[$pop[popid]]" id="delete$pop[popid]" value="yes" $deletechecked /> <label for="delete$pop[popid]">Delete mails once received.</label><br /><input type="checkbox" name="active[$pop[popid]]" id="active$pop[popid]" value="yes" $activechecked /> <label for="active$pop[popid]">Account active.</label></span></td>
</tr>
',
    'parsed_data' => '"<tr class=\\"$class[name]Row\\" valign=\\"middle\\">
	<td class=\\"$class[name]LeftCell\\" valign=\\"top\\"><span class=\\"normalfont\\">$pop[server]:</span><span class=\\"smallfont\\"><br />[<a href=\\"pop.delete.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}popid=$pop[popid]\\" onClick=\\"return confirm(\'Are you sure you want to remove this account?\');\\">remove</a>]</span></td>
	<td class=\\"$class[name]Cell\\" align=\\"center\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"serverinfo[$pop[popid]][server]\\" value=\\"$pop[server]\\" size=\\"20\\" /></td>
	<td class=\\"$class[name]Cell\\" align=\\"center\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"serverinfo[$pop[popid]][port]\\" value=\\"$pop[port]\\" size=\\"20\\" /></td>
	<td class=\\"$class[name]Cell\\" align=\\"center\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"serverinfo[$pop[popid]][username]\\" value=\\"$pop[username]\\" size=\\"20\\" /></td>
	<td class=\\"$class[name]Cell\\" align=\\"center\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"serverinfo[$pop[popid]][password]\\" value=\\"\\" size=\\"20\\" /></td>
	<td class=\\"$class[name]RightCell\\" align=\\"center\\">
		<select name=\\"serverinfo[$pop[popid]][folderid]\\">
			$folderbits
		</select></td>
</tr>
<tr class=\\"$class[name]Row\\" valign=\\"top\\">
	<td class=\\"$class[name]BothCell\\" colspan=\\"7\\"><span class=\\"normalfont\\"><input type=\\"checkbox\\" name=\\"delete[$pop[popid]]\\" id=\\"delete$pop[popid]\\" value=\\"yes\\" $deletechecked /> <label for=\\"delete$pop[popid]\\">Delete mails once received.</label><br /><input type=\\"checkbox\\" name=\\"active[$pop[popid]]\\" id=\\"active$pop[popid]\\" value=\\"yes\\" $activechecked /> <label for=\\"active$pop[popid]\\">Account active.</label></span></td>
</tr>
"',
  ),
  'read' => 
  array (
    'templategroupid' => '6',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: $mail[subject]</title>
$css
<script language="JavaScript" type="text/javascript">
<!--

function changeFolderID() {
	var shiftKey = 0; 
	if (parseInt(navigator.appVersion) > 3) { 
		if (navigator.appName == \'Netscape\') { 
			shiftKey = (e.modifiers-0>3); 
		} else { 
			shiftKey = event.shiftKey; 
		} 
	} 

	if (shiftKey) {  
		document.form.folderid.value = -3; 
	}
}

function sendReadReceipt() {
	send = confirm(\'The message sender has requested a response to indicate that you have read this message. Would you like to send a receipt?\');
	if (send == true) {
		var newWin = window.open("read.receipt.php?messageid=$messageid","SendReceipt","width=10,height=10");
	}
}

$callcomment setTimeout(sendReadReceipt, 1000);
$directcallcomment var newWin = window.open("read.receipt.php?messageid=$messageid","SendReceipt","width=10,height=10");

event_addListener( window, "load", function() { document.all.theMessage.style.height = document.frames(\'theMessage\').document.body.scrollHeight + 45; } )

//-->
</script>
</head>
<body>

$header

<form action="read.update.php" method="post" name="form">
<input type="hidden" name="do" value="dostuff" />
<input type="hidden" name="messageid" value="$messageid" />
<input type="hidden" name="folderid" value="$folderid" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Message</b></span></th>
</tr>
<tr class="highRow">
	<td class="highLeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>From:</b></span></td>
	<td class="highRightCell" align="left" width="90%" valign="middle"><span class="normalfont" style="vertical-align: middle;">$mail[fromname] (<a href="compose.email.php?email=$mail[fromemailenc]">$mail[fromemail]</a>)</span>&nbsp;<a href="addressbook.add.php?do=quick&messageid=$messageid"><img src="$skin[images]/addbook.gif" alt="Add sender to address book" align="middle" border="0" /></a>&nbsp;&nbsp;<span class="smallfont"><%if $hiveuser[cansearch] %><a href="search.results.php?folderids[]=0&searchdate=-1&fields[]=email&query=$mail[fromemailenc]"><img src="$skin[images]/find.gif" alt="Find more messages from sender" align="middle" border="0" /></a><%endif%><%if $hiveuser[canrule] %> <a href="rules.block.php?email=$mail[fromemailenc]"><img src="$skin[images]/block.gif" alt="Block sender" align="middle" border="0" /></a><%endif%></span></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>To:</b></span></td>
	<td class="normalRightCell" align="left" width="90%"><span class="normalfont">$tolist</span></td>
</tr>
$cc
<tr class="$afterto[first]Row">
	<td class="$afterto[first]LeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>Subject:</b></span></td>
	<td class="$afterto[first]RightCell" align="left" width="90%"><span class="normalfont">$mail[subject]</span></td>
</tr>
<tr class="$afterto[second]Row">
	<td class="$afterto[second]LeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>Date Sent:</b></span></td>
	<td class="$afterto[second]RightCell" align="left" width="90%"><span class="normalfont">$mail[datetime]</span></td>
</tr>
$attachments
$advheaders
<tr class="$afterattach[second]Row">
	<td class="$afterattach[second]LeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>Options:</b></span></td>
	<td class="$afterattach[second]RightCell" align="left" width="90%"><span class="normalfont"><a href="read.markas.php?messageid=$mail[messageid]&markas=$markas&back=message">mark as $markas</a> | <a href="read.source.php?messageid=$mail[messageid]">view source</a> | <a href="read.printable.php?messageid=$mail[messageid]">printable version</a></span></td>
</tr>
<tr class="$afterattach[first]Row">
	<td class="$afterattach[first]BothCell" valign="top" colspan="2">
	<table width="100%" cellpadding="4" cellspacing="0" border="0">
	<tr>
		<td width="100%"><iframe id="theMessage" src="read.email.php?messageid=$mail[messageid]&show=msg&bgcolor=$afterattach[first]" style="background-color: $iframebgcolor; width: 100%; height: 350px;" allowtransparency="true" frameborder="no"><span class="normalfont">$mail[message]</span></iframe></td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td align="right" colspan="2">
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td align="left">
		<input type="button" class="bginput" value="Reply" onClick="window.location=(\'compose.email.php?special=reply&amp;messageid=$messageid\')" />&nbsp;&nbsp;&nbsp;
		<input type="button" class="bginput" value="Reply All" onClick="window.location=(\'compose.email.php?special=replyall&amp;messageid=$messageid\')" />&nbsp;&nbsp;&nbsp;
		<input type="button" class="bginput" value="Forward" onClick="window.location=(\'compose.email.php?special=forward&amp;messageid=$messageid\')" />&nbsp;&nbsp;&nbsp;
		<input style="width: 170px;" type="button" class="bginput" value="Forward as Attachment" onClick="window.location=(\'compose.email.php?special=forward&amp;attach=1&amp;messageid=$messageid\')" /></td>
        <td align="right"><span class="smallfonttablehead"><b>
		<input type="submit" class="bginput" name="move" value="Move to" /> <select name="movetofolderid">
			<option value="-1">Inbox</option>
			<option value="-2">Sent Items</option>
			<option value="-3">Trash Can</option>
$movefolderjump</select>&nbsp; or &nbsp;
		<input type="submit" class="bginput" name="delete" value="Delete" onClick="changeFolderID();" /></b></span></td>
      </tr>
    </table></td>
</tr>
</table>

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr>
	<td align="left" valign="top"><span class="smallfont">$nextoldest $nextnewest</span></td>
	<td align="right" valign="top"><span class="smallfont"><%if $folderid != -3 %><b>Note:</b> deleted messages will be moved to the Trash Can.<br />Hold down Shift key when clicking to completely delete the messages.<%else%>&nbsp;<%endif%></span></td>
</tr>
</table>

</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: $mail[subject]</title>
$GLOBALS[css]
<script language=\\"JavaScript\\" type=\\"text/javascript\\">
<!--

function changeFolderID() {
	var shiftKey = 0; 
	if (parseInt(navigator.appVersion) > 3) { 
		if (navigator.appName == \'Netscape\') { 
			shiftKey = (e.modifiers-0>3); 
		} else { 
			shiftKey = event.shiftKey; 
		} 
	} 

	if (shiftKey) {  
		document.form.folderid.value = -3; 
	}
}

function sendReadReceipt() {
	send = confirm(\'The message sender has requested a response to indicate that you have read this message. Would you like to send a receipt?\');
	if (send == true) {
		var newWin = window.open(\\"read.receipt.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$messageid\\",\\"SendReceipt\\",\\"width=10,height=10\\");
	}
}

$callcomment setTimeout(sendReadReceipt, 1000);
$directcallcomment var newWin = window.open(\\"read.receipt.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$messageid\\",\\"SendReceipt\\",\\"width=10,height=10\\");

event_addListener( window, \\"load\\", function() { document.all.theMessage.style.height = document.frames(\'theMessage\').document.body.scrollHeight + 45; } )

//-->
</script>
</head>
<body>

$GLOBALS[header]

<form action=\\"read.update.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"do\\" value=\\"dostuff\\" />
<input type=\\"hidden\\" name=\\"messageid\\" value=\\"$messageid\\" />
<input type=\\"hidden\\" name=\\"folderid\\" value=\\"$folderid\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Message</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>From:</b></span></td>
	<td class=\\"highRightCell\\" align=\\"left\\" width=\\"90%\\" valign=\\"middle\\"><span class=\\"normalfont\\" style=\\"vertical-align: middle;\\">$mail[fromname] (<a href=\\"compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}email=$mail[fromemailenc]\\">$mail[fromemail]</a>)</span>&nbsp;<a href=\\"addressbook.add.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}do=quick&messageid=$messageid\\"><img src=\\"{$GLOBALS[skin][images]}/addbook.gif\\" alt=\\"Add sender to address book\\" align=\\"middle\\" border=\\"0\\" /></a>&nbsp;&nbsp;<span class=\\"smallfont\\">".(($hiveuser[cansearch] ) ? ("<a href=\\"search.results.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderids[]=0&searchdate=-1&fields[]=email&query=$mail[fromemailenc]\\"><img src=\\"{$GLOBALS[skin][images]}/find.gif\\" alt=\\"Find more messages from sender\\" align=\\"middle\\" border=\\"0\\" /></a>") : \'\').(($hiveuser[canrule] ) ? (" <a href=\\"rules.block.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}email=$mail[fromemailenc]\\"><img src=\\"{$GLOBALS[skin][images]}/block.gif\\" alt=\\"Block sender\\" align=\\"middle\\" border=\\"0\\" /></a>") : \'\')."</span></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>To:</b></span></td>
	<td class=\\"normalRightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\">$tolist</span></td>
</tr>
$cc
<tr class=\\"$afterto[first]Row\\">
	<td class=\\"$afterto[first]LeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>Subject:</b></span></td>
	<td class=\\"$afterto[first]RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\">$mail[subject]</span></td>
</tr>
<tr class=\\"$afterto[second]Row\\">
	<td class=\\"$afterto[second]LeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>Date Sent:</b></span></td>
	<td class=\\"$afterto[second]RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\">$mail[datetime]</span></td>
</tr>
$attachments
$advheaders
<tr class=\\"$afterattach[second]Row\\">
	<td class=\\"$afterattach[second]LeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>Options:</b></span></td>
	<td class=\\"$afterattach[second]RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\"><a href=\\"read.markas.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]&markas=$markas&back=message\\">mark as $markas</a> | <a href=\\"read.source.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\\">view source</a> | <a href=\\"read.printable.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\\">printable version</a></span></td>
</tr>
<tr class=\\"$afterattach[first]Row\\">
	<td class=\\"$afterattach[first]BothCell\\" valign=\\"top\\" colspan=\\"2\\">
	<table width=\\"100%\\" cellpadding=\\"4\\" cellspacing=\\"0\\" border=\\"0\\">
	<tr>
		<td width=\\"100%\\"><iframe id=\\"theMessage\\" src=\\"read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]&show=msg&bgcolor=$afterattach[first]\\" style=\\"background-color: $iframebgcolor; width: 100%; height: 350px;\\" allowtransparency=\\"true\\" frameborder=\\"no\\"><span class=\\"normalfont\\">$mail[message]</span></iframe></td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td align=\\"right\\" colspan=\\"2\\">
    <table border=\\"0\\" width=\\"100%\\" cellpadding=\\"0\\" cellspacing=\\"0\\">
      <tr>
        <td align=\\"left\\">
		<input type=\\"button\\" class=\\"bginput\\" value=\\"Reply\\" onClick=\\"window.location=(\'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}special=reply&amp;messageid=$messageid\')\\" />&nbsp;&nbsp;&nbsp;
		<input type=\\"button\\" class=\\"bginput\\" value=\\"Reply All\\" onClick=\\"window.location=(\'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}special=replyall&amp;messageid=$messageid\')\\" />&nbsp;&nbsp;&nbsp;
		<input type=\\"button\\" class=\\"bginput\\" value=\\"Forward\\" onClick=\\"window.location=(\'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}special=forward&amp;messageid=$messageid\')\\" />&nbsp;&nbsp;&nbsp;
		<input style=\\"width: 170px;\\" type=\\"button\\" class=\\"bginput\\" value=\\"Forward as Attachment\\" onClick=\\"window.location=(\'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}special=forward&amp;attach=1&amp;messageid=$messageid\')\\" /></td>
        <td align=\\"right\\"><span class=\\"smallfonttablehead\\"><b>
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"move\\" value=\\"Move to\\" /> <select name=\\"movetofolderid\\">
			<option value=\\"-1\\">Inbox</option>
			<option value=\\"-2\\">Sent Items</option>
			<option value=\\"-3\\">Trash Can</option>
$movefolderjump</select>&nbsp; or &nbsp;
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"delete\\" value=\\"Delete\\" onClick=\\"changeFolderID();\\" /></b></span></td>
      </tr>
    </table></td>
</tr>
</table>

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr>
	<td align=\\"left\\" valign=\\"top\\"><span class=\\"smallfont\\">$nextoldest $nextnewest</span></td>
	<td align=\\"right\\" valign=\\"top\\"><span class=\\"smallfont\\">".(($folderid != -3 ) ? ("<b>Note:</b> deleted messages will be moved to the Trash Can.<br />Hold down Shift key when clicking to completely delete the messages.") : ("&nbsp;"))."</span></td>
</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'read_attachments_bit' => 
  array (
    'templategroupid' => '6',
    'user_data' => '<a href="read.attachment.php?messageid=$messageid&attachnum=$attachnum">$filename</a> ({$filesize}KB)<br />
',
    'parsed_data' => '"<a href=\\"read.attachment.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$messageid&attachnum=$attachnum\\">$filename</a> ({$filesize}KB)<br />
"',
  ),
  'read_iframe_message' => 
  array (
    'templategroupid' => '6',
    'user_data' => '$skin[doctype]
<html>
<head>
$css
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" bgcolor="$bgcolor" style="background-color: transparent;"><span class="normalfont">
$mail[message]
</span>
</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head>
$GLOBALS[css]
</head>
<body topmargin=\\"0\\" leftmargin=\\"0\\" marginheight=\\"0\\" marginwidth=\\"0\\" bgcolor=\\"$bgcolor\\" style=\\"background-color: transparent;\\"><span class=\\"normalfont\\">
$mail[message]
</span>
</body>
</html>"',
  ),
  'read_iframe_nomessage' => 
  array (
    'templategroupid' => '6',
    'user_data' => '$skin[doctype]
<html>
<head>
$css
</head>
<body style="background-color: transparent;"><div class="normalfont">
There is no message selected.
</div>
</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head>
$GLOBALS[css]
</head>
<body style=\\"background-color: transparent;\\"><div class=\\"normalfont\\">
There is no message selected.
</div>
</body>
</html>"',
  ),
  'read_printable' => 
  array (
    'templategroupid' => '6',
    'user_data' => '$skin[doctype]
<html>
<head>
<title>$appname: $mail[subject]</title>
<style type="text/css">
<!--
h1 {  border-bottom: thick solid black; font-family: $skin[fontface];  }
body {  font-family: $skin[fontface]; margin: 6px; }
td,body { font-size: $skin[normalsize]; }
-->
</style>
</head>
<body>
<h1>$hiveuser[realname]</h1>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="20" width="25%"><b>From:</b></td>
    <td height="20">$mail[fromname] ($mail[fromemail])</td>
  </tr>
  <tr> 
    <td width="25%" height="20"><b>Sent:</b></td>
    <td height="20">$mail[datetime]</td>
  </tr>
  <tr> 
    <td width="25%" height="20"><b><font size="2">To:</font></b></td>
    <td height="20">$tolist</td>
  </tr>
<%if $mail[cc] <> ""%>
  <tr> 
    <td height="20"><b><font size="2">CC:</font></b></td>
    <td height="20"><font size="2">$mail[cc]</font></td>
  </tr>
<%endif%>
  <tr> 
    <td height="20"><b><font size="2">Subject:</font></b></td>
    <td height="20"><font size="2">$mail[subject]</font></td>
  </tr>
</table>
<p>$mail[message]</p>
</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head>
<title>$appname: $mail[subject]</title>
<style type=\\"text/css\\">
<!--
h1 {  border-bottom: thick solid black; font-family: {$GLOBALS[skin][fontface]};  }
body {  font-family: {$GLOBALS[skin][fontface]}; margin: 6px; }
td,body { font-size: {$GLOBALS[skin][normalsize]}; }
-->
</style>
</head>
<body>
<h1>$hiveuser[realname]</h1>
<table width=\\"100%\\" border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\">
  <tr> 
    <td height=\\"20\\" width=\\"25%\\"><b>From:</b></td>
    <td height=\\"20\\">$mail[fromname] ($mail[fromemail])</td>
  </tr>
  <tr> 
    <td width=\\"25%\\" height=\\"20\\"><b>Sent:</b></td>
    <td height=\\"20\\">$mail[datetime]</td>
  </tr>
  <tr> 
    <td width=\\"25%\\" height=\\"20\\"><b><font size=\\"2\\">To:</font></b></td>
    <td height=\\"20\\">$tolist</td>
  </tr>
".(($mail[cc] <> "") ? ("
  <tr> 
    <td height=\\"20\\"><b><font size=\\"2\\">CC:</font></b></td>
    <td height=\\"20\\"><font size=\\"2\\">$mail[cc]</font></td>
  </tr>
") : \'\')."
  <tr> 
    <td height=\\"20\\"><b><font size=\\"2\\">Subject:</font></b></td>
    <td height=\\"20\\"><font size=\\"2\\">$mail[subject]</font></td>
  </tr>
</table>
<p>$mail[message]</p>
</body>
</html>"',
  ),
  'redirect_loggedin' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Thank you for logging in, $username.',
    'parsed_data' => '"Thank you for logging in, $username."',
  ),
  'redirect_sending_goback' => 
  array (
    'templategroupid' => '8',
    'user_data' => '<br />Please click <a href="compose.email.php?draftid=$draftid"><b>here</b></a> to go back.',
    'parsed_data' => '"<br />Please click <a href=\\"compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}draftid=$draftid\\"><b>here</b></a> to go back."',
  ),
  'rules' => 
  array (
    'templategroupid' => '10',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Message Rules</title>
$css
<script type="text/javascript" src="misc/checkall.js"></script>
<script type="text/javascript" src="misc/blockedlist.js"></script>
<script type="text/javascript">
<!--
event_addListener( window, \'load\', function() { checkMain(document.forms.form, \'active\'); });
// -->
</script>
</head>
<body>
$header

<form action="rules.update.php" method="post" onSubmit="extract_lists(this); return true;">
<input type="hidden" name="do" value="lists" />
<input type="hidden" name="blocklist" value="lists" />
<input type="hidden" name="safelist" value="lists" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Blocked Senders</b></span></th>
</tr>
<tr class="highRow">
	<td class="highBothCell" valign="top" colspan="2"><span class="normalfont"><b>Blocked senders:</b></span>
	<br />
	<span class="smallfont">You may specify a list of email addresses you would like to block from your account below. Messages from blocked senders will automatically be moved to the Trash Can. You can enter full addreeses (e.g. email@example.net), or domain names only (e.g. example.net) to block all emails from the domain name.<br />Remember to click the Update Lists button below for changes to take effect.<br /><br />
	<table align="center" width="100%">
		<tr>
			<td valign="top" align="right" width="50%"><input type="text" value="" size="30" name="block" class="bginput" onFocus="this.form.addblock.disabled = false;" /></td>
			<td valign="top" align="center"><input type="button" disabled="disabled" value="Add ->" name="addblock" style="width: 70px;" class="bginput" onClick="addAddress(this.form, \'block\');" /><br />
						<br /><input type="button" disabled="disabled" value="Delete" name="deleteblock" style="width: 70px;" class="bginput" onClick="deleteAddress(this.form, \'block\');" /></td>
			<td valign="top" align="left" width="50%"><select name="new_blocks[]" id="blocks" multiple="multiple" size="7" onChange="this.form.deleteblock.disabled = (this.selectedIndex == -1);">
					$blocked_emails
				</select></td>
		</tr>
	</table>
	</span></td>
</tr>
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Safe Senders</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Protect messages from familiar contacts:</b></span>
	<br />
	<span class="smallfont">If this is turned on, messages from people who are in your address book will never be blocked or subject to message rules that you have.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="protectbook" value="1" id="protectbookon" $protectbookon /> <label for="protectbookon">Yes</label><br /><input type="radio" name="protectbook" value="0" id="protectbookoff" $protectbookoff /> <label for="protectbookoff">No</label></span></td>
</tr>
<tr class="highRow">
	<td class="highBothCell" valign="top" colspan="2"><span class="normalfont"><b>Additional safe senders:</b></span>
	<br />
	<span class="smallfont">You may specify a list of email addresses that are "safe" below. Messages from these addresses will never be blocked or subject to message rules that you have. You can enter full addresses (e.g. email@example.net), or domain names only (e.g. example.net) to protect all emails from the domain name.<br />Remember to click the Update Lists button below for changes to take effect.<br /><br />
	<table align="center" width="100%">
		<tr>
			<td valign="top" align="right" width="50%"><input type="text" value="" size="30" name="safe" class="bginput" onFocus="this.form.addsafe.disabled = false;" /></td>
			<td valign="top" align="center"><input type="button" disabled="disabled" value="Add ->" name="addsafe" style="width: 70px;" class="bginput" onClick="addAddress(this.form, \'safe\');" /><br />
						<br /><input type="button" disabled="disabled" value="Delete" name="deletesafe" style="width: 70px;" class="bginput" onClick="deleteAddress(this.form, \'safe\');" /></td>
			<td valign="top" align="left" width="50%"><select name="new_safes[]" id="safes" multiple="multiple" size="7" onChange="this.form.deletesafe.disabled = (this.selectedIndex == -1);">
					$safe_emails
				</select></td>
		</tr>
	</table>
	</span></td>
</tr>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="submit" value="Update Lists" />
		<input type="reset" class="bginput" name="reset" value="Reset Lists" />
	</td>
</tr>
</table>

</form>

<br />

<form action="rules.update.php" method="post" name="form">
<input type="hidden" name="do" value="update" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow" valign="middle">
	<th class="headerLeftCell"><span class="normalfonttablehead"><b>Order</b></span></th>
	<th class="headerCell" nowrap="nowrap" colspan="2"><span class="normalfonttablehead"><b>Message Rules</b></span></th>
	<th class="headerCell" align="right"><span class="normalfonttablehead"><b>Active?</b></span></th>
	<th class="headerRightCell"><input name="allbox" type="checkbox" value="Check All" title="Select/Deselect All" onClick="checkAll(this.form, \'active\');" /></th>
</tr>
$rulebits
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="submit" value="Save Changes" />
		<input type="reset" class="bginput" name="reset" value="Reset Fields" />
	</td>
</tr>
</table>

</form>

<br />

<form action="rules.update.php" method="post">
<input type="hidden" name="do" value="add" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="3"><span class="normalfonttablehead"><b>Add New Rule</b></span></th>
</tr>
<tr class="highRow" valign="top">
	<td class="highLeftCell"><span class="normalfont">When:</span></td>
	<td class="highCell">
	<select name="condsubjects[0]">
		<option value="1">email address</option>
		<option value="2">message</option>
		<option value="3">recipient</option>
		<option value="4">subject</option>
	</select>
	<select name="condhows[0]">
		<option value="2">contains</option>
		<option value="3">does not contain</option>
		<option value="1">equals</option>
		<option value="4">begins with</option>
		<option value="5">ends with</option>
	</select> <input type="text" class="bginput" name="condextras[0]" size="20" /></td>
	<td class="highRightCell"><span class="normalfont">
	<input type="checkbox" name="dowhat[0][folder]" value="1" /> <select name="folderstuff[0]">
		<option value="2">move it to</option>
		<option value="4">copy it to</option>
	</select>
	<select name="folders[0]">
		$newfolderbits
	</select><br />
	<input type="checkbox" name="dowhat[0][read]" value="1" /> mark it as read.<br />
	<input type="checkbox" name="dowhat[0][delete]" value="1" /> delete it.<br />
	<input type="checkbox" name="dowhat[0][flag]" value="1" /> flag it.</span></td>
</tr>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="submit" value="Add New Rule" />
	</td>
</tr>
</table>

</form>


$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Message Rules</title>
$GLOBALS[css]
<script type=\\"text/javascript\\" src=\\"misc/checkall.js\\"></script>
<script type=\\"text/javascript\\" src=\\"misc/blockedlist.js\\"></script>
<script type=\\"text/javascript\\">
<!--
event_addListener( window, \'load\', function() { checkMain(document.forms.form, \'active\'); });
// -->
</script>
</head>
<body>
$GLOBALS[header]

<form action=\\"rules.update.php{$GLOBALS[session_url]}\\" method=\\"post\\" onSubmit=\\"extract_lists(this); return true;\\">
<input type=\\"hidden\\" name=\\"do\\" value=\\"lists\\" />
<input type=\\"hidden\\" name=\\"blocklist\\" value=\\"lists\\" />
<input type=\\"hidden\\" name=\\"safelist\\" value=\\"lists\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Blocked Senders</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\" valign=\\"top\\" colspan=\\"2\\"><span class=\\"normalfont\\"><b>Blocked senders:</b></span>
	<br />
	<span class=\\"smallfont\\">You may specify a list of email addresses you would like to block from your account below. Messages from blocked senders will automatically be moved to the Trash Can. You can enter full addreeses (e.g. email@example.net), or domain names only (e.g. example.net) to block all emails from the domain name.<br />Remember to click the Update Lists button below for changes to take effect.<br /><br />
	<table align=\\"center\\" width=\\"100%\\">
		<tr>
			<td valign=\\"top\\" align=\\"right\\" width=\\"50%\\"><input type=\\"text\\" value=\\"\\" size=\\"30\\" name=\\"block\\" class=\\"bginput\\" onFocus=\\"this.form.addblock.disabled = false;\\" /></td>
			<td valign=\\"top\\" align=\\"center\\"><input type=\\"button\\" disabled=\\"disabled\\" value=\\"Add ->\\" name=\\"addblock\\" style=\\"width: 70px;\\" class=\\"bginput\\" onClick=\\"addAddress(this.form, \'block\');\\" /><br />
						<br /><input type=\\"button\\" disabled=\\"disabled\\" value=\\"Delete\\" name=\\"deleteblock\\" style=\\"width: 70px;\\" class=\\"bginput\\" onClick=\\"deleteAddress(this.form, \'block\');\\" /></td>
			<td valign=\\"top\\" align=\\"left\\" width=\\"50%\\"><select name=\\"new_blocks[]\\" id=\\"blocks\\" multiple=\\"multiple\\" size=\\"7\\" onChange=\\"this.form.deleteblock.disabled = (this.selectedIndex == -1);\\">
					$blocked_emails
				</select></td>
		</tr>
	</table>
	</span></td>
</tr>
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Safe Senders</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Protect messages from familiar contacts:</b></span>
	<br />
	<span class=\\"smallfont\\">If this is turned on, messages from people who are in your address book will never be blocked or subject to message rules that you have.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"protectbook\\" value=\\"1\\" id=\\"protectbookon\\" $protectbookon /> <label for=\\"protectbookon\\">Yes</label><br /><input type=\\"radio\\" name=\\"protectbook\\" value=\\"0\\" id=\\"protectbookoff\\" $protectbookoff /> <label for=\\"protectbookoff\\">No</label></span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\" valign=\\"top\\" colspan=\\"2\\"><span class=\\"normalfont\\"><b>Additional safe senders:</b></span>
	<br />
	<span class=\\"smallfont\\">You may specify a list of email addresses that are \\"safe\\" below. Messages from these addresses will never be blocked or subject to message rules that you have. You can enter full addresses (e.g. email@example.net), or domain names only (e.g. example.net) to protect all emails from the domain name.<br />Remember to click the Update Lists button below for changes to take effect.<br /><br />
	<table align=\\"center\\" width=\\"100%\\">
		<tr>
			<td valign=\\"top\\" align=\\"right\\" width=\\"50%\\"><input type=\\"text\\" value=\\"\\" size=\\"30\\" name=\\"safe\\" class=\\"bginput\\" onFocus=\\"this.form.addsafe.disabled = false;\\" /></td>
			<td valign=\\"top\\" align=\\"center\\"><input type=\\"button\\" disabled=\\"disabled\\" value=\\"Add ->\\" name=\\"addsafe\\" style=\\"width: 70px;\\" class=\\"bginput\\" onClick=\\"addAddress(this.form, \'safe\');\\" /><br />
						<br /><input type=\\"button\\" disabled=\\"disabled\\" value=\\"Delete\\" name=\\"deletesafe\\" style=\\"width: 70px;\\" class=\\"bginput\\" onClick=\\"deleteAddress(this.form, \'safe\');\\" /></td>
			<td valign=\\"top\\" align=\\"left\\" width=\\"50%\\"><select name=\\"new_safes[]\\" id=\\"safes\\" multiple=\\"multiple\\" size=\\"7\\" onChange=\\"this.form.deletesafe.disabled = (this.selectedIndex == -1);\\">
					$safe_emails
				</select></td>
		</tr>
	</table>
	</span></td>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Update Lists\\" />
		<input type=\\"reset\\" class=\\"bginput\\" name=\\"reset\\" value=\\"Reset Lists\\" />
	</td>
</tr>
</table>

</form>

<br />

<form action=\\"rules.update.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"do\\" value=\\"update\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\" valign=\\"middle\\">
	<th class=\\"headerLeftCell\\"><span class=\\"normalfonttablehead\\"><b>Order</b></span></th>
	<th class=\\"headerCell\\" nowrap=\\"nowrap\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Message Rules</b></span></th>
	<th class=\\"headerCell\\" align=\\"right\\"><span class=\\"normalfonttablehead\\"><b>Active?</b></span></th>
	<th class=\\"headerRightCell\\"><input name=\\"allbox\\" type=\\"checkbox\\" value=\\"Check All\\" title=\\"Select/Deselect All\\" onClick=\\"checkAll(this.form, \'active\');\\" /></th>
</tr>
$rulebits
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Save Changes\\" />
		<input type=\\"reset\\" class=\\"bginput\\" name=\\"reset\\" value=\\"Reset Fields\\" />
	</td>
</tr>
</table>

</form>

<br />

<form action=\\"rules.update.php{$GLOBALS[session_url]}\\" method=\\"post\\">
<input type=\\"hidden\\" name=\\"do\\" value=\\"add\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"3\\"><span class=\\"normalfonttablehead\\"><b>Add New Rule</b></span></th>
</tr>
<tr class=\\"highRow\\" valign=\\"top\\">
	<td class=\\"highLeftCell\\"><span class=\\"normalfont\\">When:</span></td>
	<td class=\\"highCell\\">
	<select name=\\"condsubjects[0]\\">
		<option value=\\"1\\">email address</option>
		<option value=\\"2\\">message</option>
		<option value=\\"3\\">recipient</option>
		<option value=\\"4\\">subject</option>
	</select>
	<select name=\\"condhows[0]\\">
		<option value=\\"2\\">contains</option>
		<option value=\\"3\\">does not contain</option>
		<option value=\\"1\\">equals</option>
		<option value=\\"4\\">begins with</option>
		<option value=\\"5\\">ends with</option>
	</select> <input type=\\"text\\" class=\\"bginput\\" name=\\"condextras[0]\\" size=\\"20\\" /></td>
	<td class=\\"highRightCell\\"><span class=\\"normalfont\\">
	<input type=\\"checkbox\\" name=\\"dowhat[0][folder]\\" value=\\"1\\" /> <select name=\\"folderstuff[0]\\">
		<option value=\\"2\\">move it to</option>
		<option value=\\"4\\">copy it to</option>
	</select>
	<select name=\\"folders[0]\\">
		$newfolderbits
	</select><br />
	<input type=\\"checkbox\\" name=\\"dowhat[0][read]\\" value=\\"1\\" /> mark it as read.<br />
	<input type=\\"checkbox\\" name=\\"dowhat[0][delete]\\" value=\\"1\\" /> delete it.<br />
	<input type=\\"checkbox\\" name=\\"dowhat[0][flag]\\" value=\\"1\\" /> flag it.</span></td>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Add New Rule\\" />
	</td>
</tr>
</table>

</form>


$GLOBALS[footer]

</body>
</html>"',
  ),
  'search_results' => 
  array (
    'templategroupid' => '5',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Search Results</title>
$css
<script type="text/javascript" src="misc/checkall.js"></script>
<script type="text/javascript" src="misc/folderview.js"></script>
<script type="text/javascript" language="JavaScript">
<!--

var rows = new Array();
$rowjsbits

var useBG = $hiveuser[usebghigh];

function makeRows(which) {
	if (useBG) {
		$markallbg(which == \'first\' ? \'highRow\' : \'normalRow\');
	}
}

function contextForMail(e, msgID, isNew, isFlagged) {
	var popupoptions = [
		new ContextItem(\'Open\', function(){ openMail((totalChecked == 1 ? msgID : -1)); }, false, true),
		new ContextItem(\'Open in New Window\', function(){ openMail((totalChecked == 1 ? msgID : -1), true); }, false),
		new ContextItem(\'Print\', function(){ window.location = \'read.printable.php?messageid=\'+msgID; }, totalChecked != 1),
		new ContextSeperator(),
		new ContextItem(\'Reply to Sender\', function(){ window.location = \'compose.email.php?special=reply&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Reply to All\', function(){ window.location = \'compose.email.php?special=replyall&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Forward\', function(){ window.location = \'compose.email.php?special=forward&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Forward as Attachment\', function(){ form.action=\'index.php?asattach=1&forward=Forward\'; form.submit(); }),
		new ContextSeperator(),
		new ContextItem(\'Mark as Read\', function(){ form.action=\'index.php?mark=Mark\'; form.markas.selectedIndex=0; form.submit(); }, isNew.indexOf(\'new\') == -1 && totalChecked == 1),
		new ContextItem(\'Mark as Unread\', function(){ form.action=\'index.php?mark=Mark\'; form.markas.selectedIndex=1; form.submit(); }, isNew.indexOf(\'new\') != -1 && totalChecked == 1),
		new ContextItem(\'Flag\', function(){ form.action=\'index.php?mark=Mark\'; form.markas.selectedIndex=2; form.submit(); }, isFlagged == 1 && totalChecked == 1),
		new ContextItem(\'Unflag\', function(){ form.action=\'index.php?mark=Mark\'; form.markas.selectedIndex=3; form.submit(); }, isFlagged == 0 && totalChecked == 1),
		new ContextSeperator(),
		new ContextItem(\'Move...\', function(){ window.open(\'index.php?do=selfolder\',\'selectfolders\',\'resizable=no,width=270,height=150\'); }),
		new ContextItem(\'Delete\', function(){ if (confirm(\'Are you sure you want to delete this message?\')) window.location = \'read.update.php?delete=1&folderid=$folderid&messageid=\'+msgID; }),
		new ContextSeperator(),
		new ContextItem(\'Add Sender to Address Book\', function(){ window.location = \'addressbook.add.php?do=quick&return=$folderid&messageid=\'+msgID; }, totalChecked != 1)
<%if $hiveuser[canrule] %>,
		new ContextItem(\'Block Sender...\', function(){ blockSender(msgID, $folderid); }, totalChecked != 1),
		new ContextItem(\'Block Subject...\', function(){ blockSubject(msgID, $folderid); }, totalChecked != 1)
<%endif%>
	]
	ContextMenu.display(popupoptions, e);
	ContextMenu.msgID = msgID;
}

-->
</script>
</head>
<body onkeydown="return moveArrow();">

$header

<table cellpadding="0" border="0" cellspacing="1" width="100%" align="center">
<tr>
	<td width="100%" valign="top">
<%if $hiveuser[preview] == \'top\' %>
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="headerText"><span class="normalfonttablehead"><b>Preview Pane</b></span></span></th>
</tr>
<tr class="normalRow">
	<td class="normalBothCell" align="left"><iframe src="read.email.php?messageid=-1&show=msg" style="background-color: $skin[firstalt]; width: 100%; height: 160px;" scrolling="yes" allowtransparency="true" id="previewFrame" frameborder="no">Your browser does not support inline frames.</iframe></td>
</tr>
</table>

<br />
<%endif%>

<form action="index.php" method="post" name="form">
<input type="hidden" name="do" value="dostuff" />
<input type="hidden" name="searchid" value="$searchid" />
<input type="hidden" name="folderid" value="$folderid" />
<input type="hidden" name="movetofolderid" value="$folderid" />
<input type="hidden" name="move" value="" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerLeftCell">&nbsp;</th>
$colheaders	<th class="headerRightCell"><input name="allbox" type="checkbox" value="Check All" title="Select/Deselect All" onClick="checkAll(this.form); if (this.checked) makeRows(\'first\'); else makeRows(\'second\');" /></th>
</tr>
$mailbits
<tr>
	<td colspan="10">
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td align="left"><span class="smallfonttablehead"><b>
		<input type="button" class="bginput" name="movebutton" value="Move" onClick="window.open(\'index.php?do=selfolder\',\'selectfolders\',\'resizable=no,width=230,height=180\');" />&nbsp; selected
		&nbsp;or &nbsp;<input type="submit" class="bginput" name="mark" value="Mark" />&nbsp; them as &nbsp;<select name="markas">
			<option value="read">read</option>
			<option value="unread">not read</option>
			<option value="flagged" selected="selected">flagged</option>
			<option value="unflagged">not flagged</option>
			<option value="replied">replied</option>
			<option value="unreplied">not replied</option>
			<option value="forwarded">forwarded</option>
			<option value="unforwarded">not forwarded</option>
		</select>
		</b></span></td>
        <td align="right"><span class="smallfonttablehead"><b>
		<input type="submit" class="bginput" name="forward" value="Forward" />&nbsp; or &nbsp;<input type="submit" class="bginput" name="delete" value="Delete" onClick="if (!confirm(\'Are you sure you want to delete the selected messages?\')) { return false; } changeFolderID(); return true;" />&nbsp; selected</b></span></td>
      </tr>
    </table></td>
</tr>
</table>

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr>
	<td><span class="smallfont">Showing messages $limitlower to $limitupper of $totalmails<br />$pagenav</span></td>
	<td align="right"><span class="smallfont"><b>Note:</b> deleted messages will be moved to the Trash Can.<br />Hold down Shift key when clicking to completely delete the messages.</span></td>
</tr>
</table>

<%if $hiveuser[preview] == \'bottom\' %>
<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="headerText"><span class="normalfonttablehead"><b>Preview Pane</b></span></span></th>
</tr>
<tr class="normalRow">
	<td class="normalBothCell" align="left"><iframe src="read.email.php?messageid=-1&show=msg" style="background-color: $skin[firstalt]; width: 100%; height: 160px;" scrolling="yes" allowtransparency="true" id="previewFrame" frameborder="no">Your browser does not support inline frames.</iframe></td>
</tr>
</table>
<%endif%>

</form>
	</td>
</tr>
</table>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Search Results</title>
$GLOBALS[css]
<script type=\\"text/javascript\\" src=\\"misc/checkall.js\\"></script>
<script type=\\"text/javascript\\" src=\\"misc/folderview.js\\"></script>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--

var rows = new Array();
$rowjsbits

var useBG = $hiveuser[usebghigh];

function makeRows(which) {
	if (useBG) {
		$markallbg(which == \'first\' ? \'highRow\' : \'normalRow\');
	}
}

function contextForMail(e, msgID, isNew, isFlagged) {
	var popupoptions = [
		new ContextItem(\'Open\', function(){ openMail((totalChecked == 1 ? msgID : -1)); }, false, true),
		new ContextItem(\'Open in New Window\', function(){ openMail((totalChecked == 1 ? msgID : -1), true); }, false),
		new ContextItem(\'Print\', function(){ window.location = \'read.printable.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=\'+msgID; }, totalChecked != 1),
		new ContextSeperator(),
		new ContextItem(\'Reply to Sender\', function(){ window.location = \'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}special=reply&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Reply to All\', function(){ window.location = \'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}special=replyall&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Forward\', function(){ window.location = \'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}special=forward&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Forward as Attachment\', function(){ form.action=\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}asattach=1&forward=Forward\'; form.submit(); }),
		new ContextSeperator(),
		new ContextItem(\'Mark as Read\', function(){ form.action=\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}mark=Mark\'; form.markas.selectedIndex=0; form.submit(); }, isNew.indexOf(\'new\') == -1 && totalChecked == 1),
		new ContextItem(\'Mark as Unread\', function(){ form.action=\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}mark=Mark\'; form.markas.selectedIndex=1; form.submit(); }, isNew.indexOf(\'new\') != -1 && totalChecked == 1),
		new ContextItem(\'Flag\', function(){ form.action=\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}mark=Mark\'; form.markas.selectedIndex=2; form.submit(); }, isFlagged == 1 && totalChecked == 1),
		new ContextItem(\'Unflag\', function(){ form.action=\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}mark=Mark\'; form.markas.selectedIndex=3; form.submit(); }, isFlagged == 0 && totalChecked == 1),
		new ContextSeperator(),
		new ContextItem(\'Move...\', function(){ window.open(\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}do=selfolder\',\'selectfolders\',\'resizable=no,width=270,height=150\'); }),
		new ContextItem(\'Delete\', function(){ if (confirm(\'Are you sure you want to delete this message?\')) window.location = \'read.update.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}delete=1&folderid=$folderid&messageid=\'+msgID; }),
		new ContextSeperator(),
		new ContextItem(\'Add Sender to Address Book\', function(){ window.location = \'addressbook.add.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}do=quick&return=$folderid&messageid=\'+msgID; }, totalChecked != 1)
".(($hiveuser[canrule] ) ? (",
		new ContextItem(\'Block Sender...\', function(){ blockSender(msgID, $folderid); }, totalChecked != 1),
		new ContextItem(\'Block Subject...\', function(){ blockSubject(msgID, $folderid); }, totalChecked != 1)
") : \'\')."
	]
	ContextMenu.display(popupoptions, e);
	ContextMenu.msgID = msgID;
}

-->
</script>
</head>
<body onkeydown=\\"return moveArrow();\\">

$GLOBALS[header]

<table cellpadding=\\"0\\" border=\\"0\\" cellspacing=\\"1\\" width=\\"100%\\" align=\\"center\\">
<tr>
	<td width=\\"100%\\" valign=\\"top\\">
".(($hiveuser[preview] == \'top\' ) ? ("
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"headerText\\"><span class=\\"normalfonttablehead\\"><b>Preview Pane</b></span></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalBothCell\\" align=\\"left\\"><iframe src=\\"read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=-1&show=msg\\" style=\\"background-color: {$GLOBALS[skin][firstalt]}; width: 100%; height: 160px;\\" scrolling=\\"yes\\" allowtransparency=\\"true\\" id=\\"previewFrame\\" frameborder=\\"no\\">Your browser does not support inline frames.</iframe></td>
</tr>
</table>

<br />
") : \'\')."

<form action=\\"index.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"do\\" value=\\"dostuff\\" />
<input type=\\"hidden\\" name=\\"searchid\\" value=\\"$searchid\\" />
<input type=\\"hidden\\" name=\\"folderid\\" value=\\"$folderid\\" />
<input type=\\"hidden\\" name=\\"movetofolderid\\" value=\\"$folderid\\" />
<input type=\\"hidden\\" name=\\"move\\" value=\\"\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerLeftCell\\">&nbsp;</th>
$colheaders	<th class=\\"headerRightCell\\"><input name=\\"allbox\\" type=\\"checkbox\\" value=\\"Check All\\" title=\\"Select/Deselect All\\" onClick=\\"checkAll(this.form); if (this.checked) makeRows(\'first\'); else makeRows(\'second\');\\" /></th>
</tr>
$mailbits
<tr>
	<td colspan=\\"10\\">
    <table border=\\"0\\" width=\\"100%\\" cellpadding=\\"0\\" cellspacing=\\"0\\">
      <tr>
        <td align=\\"left\\"><span class=\\"smallfonttablehead\\"><b>
		<input type=\\"button\\" class=\\"bginput\\" name=\\"movebutton\\" value=\\"Move\\" onClick=\\"window.open(\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}do=selfolder\',\'selectfolders\',\'resizable=no,width=230,height=180\');\\" />&nbsp; selected
		&nbsp;or &nbsp;<input type=\\"submit\\" class=\\"bginput\\" name=\\"mark\\" value=\\"Mark\\" />&nbsp; them as &nbsp;<select name=\\"markas\\">
			<option value=\\"read\\">read</option>
			<option value=\\"unread\\">not read</option>
			<option value=\\"flagged\\" selected=\\"selected\\">flagged</option>
			<option value=\\"unflagged\\">not flagged</option>
			<option value=\\"replied\\">replied</option>
			<option value=\\"unreplied\\">not replied</option>
			<option value=\\"forwarded\\">forwarded</option>
			<option value=\\"unforwarded\\">not forwarded</option>
		</select>
		</b></span></td>
        <td align=\\"right\\"><span class=\\"smallfonttablehead\\"><b>
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"forward\\" value=\\"Forward\\" />&nbsp; or &nbsp;<input type=\\"submit\\" class=\\"bginput\\" name=\\"delete\\" value=\\"Delete\\" onClick=\\"if (!confirm(\'Are you sure you want to delete the selected messages?\')) { return false; } changeFolderID(); return true;\\" />&nbsp; selected</b></span></td>
      </tr>
    </table></td>
</tr>
</table>

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr>
	<td><span class=\\"smallfont\\">Showing messages $limitlower to $limitupper of $totalmails<br />$pagenav</span></td>
	<td align=\\"right\\"><span class=\\"smallfont\\"><b>Note:</b> deleted messages will be moved to the Trash Can.<br />Hold down Shift key when clicking to completely delete the messages.</span></td>
</tr>
</table>

".(($hiveuser[preview] == \'bottom\' ) ? ("
<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"headerText\\"><span class=\\"normalfonttablehead\\"><b>Preview Pane</b></span></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalBothCell\\" align=\\"left\\"><iframe src=\\"read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=-1&show=msg\\" style=\\"background-color: {$GLOBALS[skin][firstalt]}; width: 100%; height: 160px;\\" scrolling=\\"yes\\" allowtransparency=\\"true\\" id=\\"previewFrame\\" frameborder=\\"no\\">Your browser does not support inline frames.</iframe></td>
</tr>
</table>
") : \'\')."

</form>
	</td>
</tr>
</table>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'signup_activate_message' => 
  array (
    'templategroupid' => '14',
    'user_data' => 'Dear $auser[realname],

We have now activated your account at the $appname. To log in to the service, please visit this page:
$appurl/index.php

Your account name is $auser[username]. Please don\'t forget that your password is case sensitive!

To edit your preferences at any time, please visit this page:
$appurl/options.menu.php

Thank you and enjoy the service,
$appname team',
    'parsed_data' => '"Dear $auser[realname],

We have now activated your account at the $appname. To log in to the service, please visit this page:
$appurl/index.php{$GLOBALS[session_url]}

Your account name is $auser[username]. Please don\'t forget that your password is case sensitive!

To edit your preferences at any time, please visit this page:
$appurl/options.menu.php{$GLOBALS[session_url]}

Thank you and enjoy the service,
$appname team"',
  ),
  'signup_activate_subject' => 
  array (
    'templategroupid' => '14',
    'user_data' => 'Account activated at $appname!',
    'parsed_data' => '"Account activated at $appname!"',
  ),
  'signup_notify_message' => 
  array (
    'templategroupid' => '14',
    'user_data' => 'Dear operator,

A new user has registered at $appname:
Username: $username
Real name: $realname
<%if getop(\'moderate\') %>

Please visit the administrative control panel to activate this user:
$appurl/admin/user.php?do=validate

<%endif%>
$appname team',
    'parsed_data' => '"Dear operator,

A new user has registered at $appname:
Username: $username
Real name: $realname
".((getop(\'moderate\') ) ? ("

Please visit the administrative control panel to activate this user:
$appurl/admin/user.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}do=validate

") : \'\')."
$appname team"',
  ),
  'signup_notify_subject' => 
  array (
    'templategroupid' => '14',
    'user_data' => 'New user at $appname',
    'parsed_data' => '"New user at $appname"',
  ),
  'signup_thankyou' => 
  array (
    'templategroupid' => '14',
    'user_data' => '<form action="index.php" method="post" name="login">
<input type="hidden" name="username" value="$username" />
<input type="hidden" name="password" value="$password" />
<input type="hidden" name="login" value="1" />
Thank for your signing up to our service!<br />
<%if getop(\'moderate\') %>
As the operators of $appname require user validation, your account will not be functional until they activate it. You will be notified by email ($altemail) when your account is activated.
<%else%>
To start using your account, <a href="javascript:getElement(\'login\').submit();">click here</a> and continue to your Inbox. Enjoy $appname!
<%endif%>
</form>',
    'parsed_data' => '"<form action=\\"index.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"login\\">
<input type=\\"hidden\\" name=\\"username\\" value=\\"$username\\" />
<input type=\\"hidden\\" name=\\"password\\" value=\\"$password\\" />
<input type=\\"hidden\\" name=\\"login\\" value=\\"1\\" />
Thank for your signing up to our service!<br />
".((getop(\'moderate\') ) ? ("
As the operators of $appname require user validation, your account will not be functional until they activate it. You will be notified by email ($altemail) when your account is activated.
") : ("
To start using your account, <a href=\\"javascript:getElement(\'login\').submit();\\">click here</a> and continue to your Inbox. Enjoy $appname!
"))."
</form>"',
  ),
  'signup_welcome_message' => 
  array (
    'templategroupid' => '14',
    'user_data' => 'Dear $realname,

Thank you for signing up at $appname! We hope you enjoy our services.

Thank you,
$appname team',
    'parsed_data' => '"Dear $realname,

Thank you for signing up at $appname! We hope you enjoy our services.

Thank you,
$appname team"',
  ),
  'signup_welcome_subject' => 
  array (
    'templategroupid' => '14',
    'user_data' => 'Welcome to $appname!',
    'parsed_data' => '"Welcome to $appname!"',
  ),
);

?>