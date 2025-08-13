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
$templates[3] = array (
  'addbook_edit' => 
  array (
    'templategroupid' => '4',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Edit Contacts</title>
$css
<script type="text/javascript" src="misc/checkall.js"></script>
</head>
<body>
$header

<form action="addressbook.update.php" method="post" name="form">
<input type="hidden" name="cmd" value="update" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="730">
<tr class="headerRow">
	<th class="headerLeftCell" nowrap="nowrap"><span class="normalfonttablehead"><b>Contact Name</b></span></th>
	<th class="headerCell" nowrap="nowrap"><span class="normalfonttablehead"><b>Email Address</b></span></th>
	<th class="headerRightCell"><input name="allbox" type="checkbox" value="Check All" title="Select/Deselect All" onClick="checkAll(this.form);" /></th>
</tr>
$contacts
<tr>
	<td align="right" colspan="7"><span class="smallfonttablehead"><b>
<input type="submit" class="bginput" name="delete" value="Delete" onClick="return confirm(\'Are you sure you want to delete the selected contacts?\');" />&nbsp; selected contacts</b></span></td>
</tr>
</table>

<table cellpadding="4" cellspacing="0" class="normalTable" width="730">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="submit" value="Update Contacts" />
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
<head><title>$appname: Edit Contacts</title>
$GLOBALS[css]
<script type=\\"text/javascript\\" src=\\"misc/checkall.js\\"></script>
</head>
<body>
$GLOBALS[header]

<form action=\\"addressbook.update.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"730\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerLeftCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>Contact Name</b></span></th>
	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>Email Address</b></span></th>
	<th class=\\"headerRightCell\\"><input name=\\"allbox\\" type=\\"checkbox\\" value=\\"Check All\\" title=\\"Select/Deselect All\\" onClick=\\"checkAll(this.form);\\" /></th>
</tr>
$contacts
<tr>
	<td align=\\"right\\" colspan=\\"7\\"><span class=\\"smallfonttablehead\\"><b>
<input type=\\"submit\\" class=\\"bginput\\" name=\\"delete\\" value=\\"Delete\\" onClick=\\"return confirm(\'Are you sure you want to delete the selected contacts?\');\\" />&nbsp; selected contacts</b></span></td>
</tr>
</table>

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"730\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Update Contacts\\" />
		<input type=\\"reset\\" class=\\"bginput\\" name=\\"reset\\" value=\\"Reset Fields\\" />
	</td>
</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
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
<input type="hidden" name="cmd" value="update" />
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
<input type="hidden" name="cmd" value="insert" />

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
<input type="hidden" name="cmd" value="upload" />

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
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />
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
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"insert\\" />

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
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"upload\\" />

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
  'autoresponse_subject' => 
  array (
    'templategroupid' => '12',
    'user_data' => '[Auto-response] ',
    'parsed_data' => '"[Auto-response] "',
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
     var url = "addressbook.view.php?cmd=mini";
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
	if (!useText) {
		document.composeform.plainmessage.value = idContent.getText();
	}
	if (document.composeform.action == \'compose.email.php\' && fromAttach != 1) {
		document.composeform.usehtml.value = 0;
	}
<%else%>
	if (document.composeform.action == \'compose.email.php\' && fromAttach != 1) {
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
<input type="hidden" name="cmd" value="compose" />
<input type="hidden" name="save" value="1" />
<input type="hidden" name="draftid" value="$draftid" />
<input type="hidden" name="data[special]" value="$data[special]" />
<input type="hidden" name="message" value="" />
<input type="hidden" name="data[plainmessage]" value="" id="plainmessage" />
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
<%if $hiveuser[composereplyto] %>
<tr class="highRow">
<%else%>
<tr class="normalRow">
<%endif%>
	<td class="normalLeftCell" style="padding-right: 40px;"><span class="normalfont"><b>From:</b></span></td>
	<td class="normalRightCell" style="width: 100%;"><span class="normalfont">$hiveuser[realname] ($hiveuser[username]$domainname)</span></td>
</tr>
<%if $hiveuser[composereplyto] %>
<tr class="normalRow">
	<td class="normalLeftCell" style="padding-right: 40px;"><span class="normalfont"><b>Reply-To:</b></span></td>
	<td class="normalRightCell" style="width: 100%;"><input type="text" class="bginput" value="$hiveuser[replyto]" size="72" name="data[replyto]" /></span></td>
</tr>
<%endif%>
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
	<input type="checkbox" name="data[savecopy]" value="1" id="savecopy" $savecopychecked /> <label for="savecopy"><b>Save a copy:</b> Also save a copy in the Sent Items folder.</label><br />
	<input type="checkbox" name="data[requestread]" value="1" id="requestread" $requestreadchecked /> <label for="requestread"><b>Request read receipt:</b> Be notified when the receiver reads the message.</label><br />
<%if $toomanycontacts%>
	<input type="hidden" name="data[addtobook]" value="0" />
<%else%>
	<input type="checkbox" name="data[addtobook]" value="1" id="addtobook" $addtobookchecked /> <label for="addtobook"><b>Add contacts to address book:</b> Automatically add all recipients of this<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;email to your address book after you send this message.</label>
<%endif%>
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
	<input type="submit" class="bginput" name="updatedraft" value="Update Draft" onClick="this.form.action=\'compose.draft.php\'; return true;" />
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
     var url = \\"addressbook.view.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=mini\\";
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
	if (!useText) {
		document.composeform.plainmessage.value = idContent.getText();
	}
	if (document.composeform.action == \'compose.email.php{$GLOBALS[session_url]}\' && fromAttach != 1) {
		document.composeform.usehtml.value = 0;
	}
") : ("
	if (document.composeform.action == \'compose.email.php{$GLOBALS[session_url]}\' && fromAttach != 1) {
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
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"compose\\" />
<input type=\\"hidden\\" name=\\"save\\" value=\\"1\\" />
<input type=\\"hidden\\" name=\\"draftid\\" value=\\"$draftid\\" />
<input type=\\"hidden\\" name=\\"data[special]\\" value=\\"$data[special]\\" />
<input type=\\"hidden\\" name=\\"message\\" value=\\"\\" />
<input type=\\"hidden\\" name=\\"data[plainmessage]\\" value=\\"\\" id=\\"plainmessage\\" />
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
".(($hiveuser[composereplyto] ) ? ("
<tr class=\\"highRow\\">
") : ("
<tr class=\\"normalRow\\">
"))."
	<td class=\\"normalLeftCell\\" style=\\"padding-right: 40px;\\"><span class=\\"normalfont\\"><b>From:</b></span></td>
	<td class=\\"normalRightCell\\" style=\\"width: 100%;\\"><span class=\\"normalfont\\">$hiveuser[realname] ($hiveuser[username]$domainname)</span></td>
</tr>
".(($hiveuser[composereplyto] ) ? ("
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" style=\\"padding-right: 40px;\\"><span class=\\"normalfont\\"><b>Reply-To:</b></span></td>
	<td class=\\"normalRightCell\\" style=\\"width: 100%;\\"><input type=\\"text\\" class=\\"bginput\\" value=\\"$hiveuser[replyto]\\" size=\\"72\\" name=\\"data[replyto]\\" /></span></td>
</tr>
") : (\'\'))."
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
	<input type=\\"checkbox\\" name=\\"data[savecopy]\\" value=\\"1\\" id=\\"savecopy\\" $savecopychecked /> <label for=\\"savecopy\\"><b>Save a copy:</b> Also save a copy in the Sent Items folder.</label><br />
	<input type=\\"checkbox\\" name=\\"data[requestread]\\" value=\\"1\\" id=\\"requestread\\" $requestreadchecked /> <label for=\\"requestread\\"><b>Request read receipt:</b> Be notified when the receiver reads the message.</label><br />
".(($toomanycontacts) ? ("
	<input type=\\"hidden\\" name=\\"data[addtobook]\\" value=\\"0\\" />
") : ("
	<input type=\\"checkbox\\" name=\\"data[addtobook]\\" value=\\"1\\" id=\\"addtobook\\" $addtobookchecked /> <label for=\\"addtobook\\"><b>Add contacts to address book:</b> Automatically add all recipients of this<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;email to your address book after you send this message.</label>
"))."
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
	<input type=\\"submit\\" class=\\"bginput\\" name=\\"updatedraft\\" value=\\"Update Draft\\" onClick=\\"this.form.action=\'compose.draft.php{$GLOBALS[session_url]}\'; return true;\\" />
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
<input type="hidden" name="cmd" value="manageattach" />
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
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"manageattach\\" />
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
  'compose_reply' => 
  array (
    'templategroupid' => '3',
    'user_data' => '----- Original Message -----
From: "$mail[name]" <$mail[email]>
To: $mail[to]
<%if $mail[cc]%>Cc: $mail[cc]
<%endif%>Sent: $mail[datetime]
Subject: $mail[subject]

$hiveuser[replychar] $mail[message]',
    'parsed_data' => '"----- Original Message -----
From: \\"$mail[name]\\" <$mail[email]>
To: $mail[to]
".(($mail[cc]) ? ("Cc: $mail[cc]
") : (\'\'))."Sent: $mail[datetime]
Subject: $mail[subject]

$hiveuser[replychar] $mail[message]"',
  ),
  'css' => 
  array (
    'templategroupid' => '1',
    'user_data' => '<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
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
	font-weight: normal;
}
textarea, .bginput {
	font-family: $skin[fontface];
	font-size: 12px;
	color: #000000;
	background: $skin[formbackground];
	font-weight: normal;
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
TH A {
	text-decoration: none;
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

	if (difference != 0) {
		if (confirm(\'The system has detected that the time zone in your preferences is wrong and off by \'+Math.abs(difference)+\' hour\'+((Math.abs(difference) == 1) ? (\'\') : (\'s\'))+\'. Would you like the system to correct this mistake?\')) {
			var newWin = window.open("options.personal.php?cmd=updatezone&difference="+difference,"FixTimeZone","width=10,height=10");
		} else if (confirm(\'Do you wish to disable Time Zone Auto-detection?\')) {
			var newWin = window.open("options.personal.php?cmd=disablezone","DisableTimeZone","width=10,height=10");
		}
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
	font-weight: normal;
}
textarea, .bginput {
	font-family: {$GLOBALS[skin][fontface]};
	font-size: 12px;
	color: #000000;
	background: {$GLOBALS[skin][formbackground]};
	font-weight: normal;
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
TH A {
	text-decoration: none;
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

	if (difference != 0) {
		if (confirm(\'The system has detected that the time zone in your preferences is wrong and off by \'+Math.abs(difference)+\' hour\'+((Math.abs(difference) == 1) ? (\'\') : (\'s\'))+\'. Would you like the system to correct this mistake?\')) {
			var newWin = window.open(\\"options.personal.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=updatezone&difference=\\"+difference,\\"FixTimeZone\\",\\"width=10,height=10\\");
		} else if (confirm(\'Do you wish to disable Time Zone Auto-detection?\')) {
			var newWin = window.open(\\"options.personal.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=disablezone\\",\\"DisableTimeZone\\",\\"width=10,height=10\\");
		}
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
  'error_badlists' => 
  array (
    'templategroupid' => '7',
    'user_data' => '<%if $bad_blocks %>The operator of this service doesn\'t allow blocking at least one of the addresses you are trying to block:
<ul>
$bad_blocks
</ul><br /><%endif%>

<%if $bad_safes %><%if $bad_blocks %>Additionally, the<%else%>The<%endif%> operator of this service has globally blocked at least one of the addresses you have added to your safe list:
<ul>
$bad_safes
</ul><br /><br /><%endif%>',
    'parsed_data' => '(($bad_blocks ) ? ("The operator of this service doesn\'t allow blocking at least one of the addresses you are trying to block:
<ul>
$bad_blocks
</ul><br />") : (\'\'))."

".(($bad_safes ) ? ((($bad_blocks ) ? ("Additionally, the") : ("The"))." operator of this service has globally blocked at least one of the addresses you have added to your safe list:
<ul>
$bad_safes
</ul><br /><br />") : (\'\'))',
  ),
  'error_banned' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Your IP/hostname has been banned from using $appname.',
    'parsed_data' => '"Your IP/hostname has been banned from using $appname."',
  ),
  'error_contacts_toomany' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You may only have $hiveuser[maxcontacts] contacts in your address book. You must remove some contacts before you may add more.',
    'parsed_data' => '"You may only have $hiveuser[maxcontacts] contacts in your address book. You must remove some contacts before you may add more."',
  ),
  'error_disabled' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Sorry, $appname is currently disabled for maintenance work. You can logout by clicking <a href="user.logout.php">here</a>. $appname will be available again shortly.',
    'parsed_data' => '"Sorry, $appname is currently disabled for maintenance work. You can logout by clicking <a href=\\"user.logout.php{$GLOBALS[session_url]}\\">here</a>. $appname will be available again shortly."',
  ),
  'error_field_below_min_options' => 
  array (
    'templategroupid' => '7',
    'user_data' => '<li>The field $field[title] requires at least $field[min] choices, and you\'ve only chosen $current_number.</li>
',
    'parsed_data' => '"<li>The field $field[title] requires at least $field[min] choices, and you\'ve only chosen $current_number.</li>
"',
  ),
  'error_field_below_min_text' => 
  array (
    'templategroupid' => '7',
    'user_data' => '<li>The field $field[title] requires at least $field[min] characters, and you\'ve only entered $current_number.</li>
',
    'parsed_data' => '"<li>The field $field[title] requires at least $field[min] characters, and you\'ve only entered $current_number.</li>
"',
  ),
  'error_field_options' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The following errors occurred while updating your profile:
<ul>
$fielderrors
</ul>
These erroneous values or choices were not updated in our database, only the valid information was kept. Please click <a href="options.personal.php">here</a> to go back and update your information.',
    'parsed_data' => '"The following errors occurred while updating your profile:
<ul>
$fielderrors
</ul>
These erroneous values or choices were not updated in our database, only the valid information was kept. Please click <a href=\\"options.personal.php{$GLOBALS[session_url]}\\">here</a> to go back and update your information."',
  ),
  'error_field_over_max_options' => 
  array (
    'templategroupid' => '7',
    'user_data' => '<li>In the field $field[title] you may only choose up-to $field[max] options, and you\'ve chosen $current_number.</li>
',
    'parsed_data' => '"<li>In the field $field[title] you may only choose up-to $field[max] options, and you\'ve chosen $current_number.</li>
"',
  ),
  'error_field_over_max_text' => 
  array (
    'templategroupid' => '7',
    'user_data' => '<li>In the field $field[title] you may only enter up-to $field[max] characters, and you\'ve entered $current_number.</li>
',
    'parsed_data' => '"<li>In the field $field[title] you may only enter up-to $field[max] characters, and you\'ve entered $current_number.</li>
"',
  ),
  'error_field_required_empty' => 
  array (
    'templategroupid' => '7',
    'user_data' => '<li>The field $field[title] is required and you must enter/choose a value for it.</li>
',
    'parsed_data' => '"<li>The field $field[title] is required and you must enter/choose a value for it.</li>
"',
  ),
  'error_field_signup' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The following errors occurred:
<ul>
$fielderrors
</ul>
Please click below to go back and try again.
<div align="center">
<form method="post" action="user.signup.php">
<input type="hidden" name="cmd" value="getinfo">
<input type="hidden" name="useolddata" value="1">
<input type="hidden" name="badcode" value="0">
<input type="hidden" name="noterms" value="0">
<input type="hidden" name="realname" value="$realname">
<input type="hidden" name="question" value="$question">
<input type="hidden" name="answer" value="$answer">
<input type="hidden" name="answer_repeat" value="$answer_repeat">
<input type="hidden" name="altemail" value="$altemail">
<input type="hidden" name="zip" value="$zip">
<input type="hidden" name="month" value="$zip">
<input type="hidden" name="day" value="$zip">
<input type="hidden" name="username" value="$username" />
<input type="hidden" name="userdomain" value="$domain" />
<input type="hidden" name="password_encrypted" value="1" />
<input type="hidden" name="password" value="$password" />
<input type="hidden" name="password_repeat" value="$password" />
<input type="hidden" name="password_length" value="$password_length" />
<input type="submit" value=" Go Back " class="bginput" />
</form>
</div>',
    'parsed_data' => '"The following errors occurred:
<ul>
$fielderrors
</ul>
Please click below to go back and try again.
<div align=\\"center\\">
<form method=\\"post\\" action=\\"user.signup.php{$GLOBALS[session_url]}\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"getinfo\\">
<input type=\\"hidden\\" name=\\"useolddata\\" value=\\"1\\">
<input type=\\"hidden\\" name=\\"badcode\\" value=\\"0\\">
<input type=\\"hidden\\" name=\\"noterms\\" value=\\"0\\">
<input type=\\"hidden\\" name=\\"realname\\" value=\\"$realname\\">
<input type=\\"hidden\\" name=\\"question\\" value=\\"$question\\">
<input type=\\"hidden\\" name=\\"answer\\" value=\\"$answer\\">
<input type=\\"hidden\\" name=\\"answer_repeat\\" value=\\"$answer_repeat\\">
<input type=\\"hidden\\" name=\\"altemail\\" value=\\"$altemail\\">
<input type=\\"hidden\\" name=\\"zip\\" value=\\"$zip\\">
<input type=\\"hidden\\" name=\\"month\\" value=\\"$zip\\">
<input type=\\"hidden\\" name=\\"day\\" value=\\"$zip\\">
<input type=\\"hidden\\" name=\\"username\\" value=\\"$username\\" />
<input type=\\"hidden\\" name=\\"userdomain\\" value=\\"$domain\\" />
<input type=\\"hidden\\" name=\\"password_encrypted\\" value=\\"1\\" />
<input type=\\"hidden\\" name=\\"password\\" value=\\"$password\\" />
<input type=\\"hidden\\" name=\\"password_repeat\\" value=\\"$password\\" />
<input type=\\"hidden\\" name=\\"password_length\\" value=\\"$password_length\\" />
<input type=\\"submit\\" value=\\" Go Back \\" class=\\"bginput\\" />
</form>
</div>"',
  ),
  'error_logindomain' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You cannot login using the form you have just completed. Please click <a href="index.php">here</a> to log in.',
    'parsed_data' => '"You cannot login using the form you have just completed. Please click <a href=\\"index.php{$GLOBALS[session_url]}\\">here</a> to log in."',
  ),
  'error_maxrecipients' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You may only send your message to $hiveuser[maxrecips] recipient(s), you have tried to send it to $numRecipients.',
    'parsed_data' => '"You may only send your message to $hiveuser[maxrecips] recipient(s), you have tried to send it to $numRecipients."',
  ),
  'error_processerror_rejected' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'This message was created automatically by mail delivery software (HiveMail).

A message that you sent could not be delivered to its recipient(s).
The error was:
    Message rejected as sender is on the DNSbl list at $blockedbyserver.

Please contact the list adminstrator for instructions on how you can be removed from these lists.

------ This is a copy of the message, including all the headers. ------

$message',
    'parsed_data' => '"This message was created automatically by mail delivery software (HiveMail).

A message that you sent could not be delivered to its recipient(s).
The error was:
    Message rejected as sender is on the DNSbl list at $blockedbyserver.

Please contact the list adminstrator for instructions on how you can be removed from these lists.

------ This is a copy of the message, including all the headers. ------

$message"',
  ),
  'error_processerror_unknown' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'This message was created automatically by mail delivery software (HiveMail).

A message that you sent could not be delivered to its recipient(s).
The error was:
    Unknown mailbox.

------ This is a copy of the message, including all the headers. ------

$message',
    'parsed_data' => '"This message was created automatically by mail delivery software (HiveMail).

A message that you sent could not be delivered to its recipient(s).
The error was:
    Unknown mailbox.

------ This is a copy of the message, including all the headers. ------

$message"',
  ),
  'error_response_toomany' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You may only have $hiveuser[maxresponses] response.',
    'parsed_data' => '"You may only have $hiveuser[maxresponses] response."',
  ),
  'expired_account_emptied_message' => 
  array (
    'templategroupid' => '17',
    'user_data' => 'Dear $user[realname],

You did not log into our system for the past $group[emptytime] days, and as a result your email account, $user[username]$user[domain], has just been emptied and all messages were deleted.

We apologize for the inconvenience.

Best regards,
$appname team',
    'parsed_data' => '"Dear $user[realname],

You did not log into our system for the past $group[emptytime] days, and as a result your email account, $user[username]$user[domain], has just been emptied and all messages were deleted.

We apologize for the inconvenience.

Best regards,
$appname team"',
  ),
  'expired_account_emptied_subject' => 
  array (
    'templategroupid' => '17',
    'user_data' => 'Email messages deleted',
    'parsed_data' => '"Email messages deleted"',
  ),
  'expired_account_removed_message' => 
  array (
    'templategroupid' => '17',
    'user_data' => 'Dear $user[realname],

You did not log into our system for the past $group[removetime] days, and as a result your email account, $user[username]$user[domain], has just been deleted from our system. If you would like to register this email address again, you may do so here:
$appurl/index.php

We apologize for the inconvenience.

Best regards,
$appname team',
    'parsed_data' => '"Dear $user[realname],

You did not log into our system for the past $group[removetime] days, and as a result your email account, $user[username]$user[domain], has just been deleted from our system. If you would like to register this email address again, you may do so here:
$appurl/index.php{$GLOBALS[session_url]}

We apologize for the inconvenience.

Best regards,
$appname team"',
  ),
  'expired_account_removed_subject' => 
  array (
    'templategroupid' => '17',
    'user_data' => 'Email account removed',
    'parsed_data' => '"Email account removed"',
  ),
  'expired_early_notification_emptying_message' => 
  array (
    'templategroupid' => '17',
    'user_data' => 'Dear $user[realname],

You did not log into our system for the past $days days. If you fail to log into the system before $group[mindays] pass, your email account, $user[username]$user[domain], will be emptied and all messages will be deleted.

We apologize for the inconvenience.

Best regards,
$appname team',
    'parsed_data' => '"Dear $user[realname],

You did not log into our system for the past $days days. If you fail to log into the system before $group[mindays] pass, your email account, $user[username]$user[domain], will be emptied and all messages will be deleted.

We apologize for the inconvenience.

Best regards,
$appname team"',
  ),
  'expired_early_notification_emptying_subject' => 
  array (
    'templategroupid' => '17',
    'user_data' => 'Notice of account expiration',
    'parsed_data' => '"Notice of account expiration"',
  ),
  'expired_early_notification_removal_message' => 
  array (
    'templategroupid' => '17',
    'user_data' => 'Dear $user[realname],

You did not log into our system for the past $days days. If you fail to log into the system before $group[mindays] pass, your email account, $user[username]$user[domain], will be removed from our system.

We apologize for the inconvenience.

Best regards,
$appname team',
    'parsed_data' => '"Dear $user[realname],

You did not log into our system for the past $days days. If you fail to log into the system before $group[mindays] pass, your email account, $user[username]$user[domain], will be removed from our system.

We apologize for the inconvenience.

Best regards,
$appname team"',
  ),
  'expired_early_notification_removal_subject' => 
  array (
    'templategroupid' => '17',
    'user_data' => 'Notice of account expiration',
    'parsed_data' => '"Notice of account expiration"',
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
<tr align="center" class="normalRow">
	<td class="normalLeftCell" align="left" width="50%"><span class="normalfont"><a href="index.php?folderid=-4">Junk Mail</a></span> <span class="smallfont">(not removable)</span></td>
	<td class="normalCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$msgcount[junkmail] message<%if $msgcount[\'junkmail\'] != 1 %>s<%endif%></span></td>
	<td class="normalCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$unreadcount[junkmail] message<%if $unreadcount[\'junkmail\'] != 1 %>s<%endif%></span></td>
	<td class="normalCell" width="10%" align="center" nowrap="nowrap"><span class="normalfont">$presizes[junkmail]KB</span></td>
	<td class="normalRightCell"><input type="checkbox" name="folder[-4]" id="junkmail" value="yes" onClick="checkMain(this.form);" /></td>
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
			<option value="-4">Junk Mail</option>
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
	<td class=\\"highCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$msgcount[inbox] message".(($msgcount[\'inbox\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"highCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$unreadcount[inbox] message".(($unreadcount[\'inbox\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"highCell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$presizes[inbox]KB</span></td>
	<td class=\\"highRightCell\\"><input type=\\"checkbox\\" name=\\"folder[-1]\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
<tr align=\\"center\\" class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" align=\\"left\\" width=\\"50%\\"><span class=\\"normalfont\\"><a href=\\"index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=-2\\">Sent Items</a></span> <span class=\\"smallfont\\">(not removable)</span></td>
	<td class=\\"normalCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$msgcount[sentitems] message".(($msgcount[\'sentitems\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"normalCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$unreadcount[sentitems] message".(($unreadcount[\'sentitems\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"normalCell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$presizes[sentitems]KB</span></td>
	<td class=\\"normalRightCell\\"><input type=\\"checkbox\\" name=\\"folder[-2]\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
<tr align=\\"center\\" class=\\"highRow\\">
	<td class=\\"highLeftCell\\" align=\\"left\\" width=\\"50%\\"><span class=\\"normalfont\\"><a href=\\"index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=-3\\">Trash Can</a></span> <span class=\\"smallfont\\">(not removable)</span></td>
	<td class=\\"highCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$msgcount[trashcan] message".(($msgcount[\'trashcan\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"highCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$unreadcount[trashcan] message".(($unreadcount[\'trashcan\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"highCell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$presizes[trashcan]KB</span></td>
	<td class=\\"highRightCell\\"><input type=\\"checkbox\\" name=\\"folder[-3]\\" id=\\"trashcan\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
<tr align=\\"center\\" class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" align=\\"left\\" width=\\"50%\\"><span class=\\"normalfont\\"><a href=\\"index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=-4\\">Junk Mail</a></span> <span class=\\"smallfont\\">(not removable)</span></td>
	<td class=\\"normalCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$msgcount[junkmail] message".(($msgcount[\'junkmail\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"normalCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$unreadcount[junkmail] message".(($unreadcount[\'junkmail\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"normalCell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$presizes[junkmail]KB</span></td>
	<td class=\\"normalRightCell\\"><input type=\\"checkbox\\" name=\\"folder[-4]\\" id=\\"junkmail\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
$folderbits
<tr align=\\"center\\" class=\\"headerRow\\">
	<th class=\\"headerLeftCell\\" width=\\"50%\\" align=\\"right\\"><span class=\\"normalfonttablehead\\"><b>Total:</b></span></th>
	<th class=\\"headerCell\\" width=\\"25%\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>$totalmsgs message".(($totalmsgs != 1 ) ? ("s") : (\'\'))."</b></span></th>
	<th class=\\"headerCell\\" width=\\"25%\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>$totalunreads message".(($totalunreads != 1 ) ? ("s") : (\'\'))."</b></span></th>
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
			<option value=\\"-4\\">Junk Mail</option>
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
</table>
$youvegotmail',
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
</table>
$youvegotmail"',
  ),
  'footer_mini' => 
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
$youvegotmail',
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
$youvegotmail"',
  ),
  'header' => 
  array (
    'templategroupid' => '1',
    'user_data' => '<table cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td rowspan="2" valign="top" style="height: 100px; background-color: #8AB8E5;">
			<img src="$skin[images]/header_left.gif" align="middle" alt="" />
		</td>
		<td rowspan="2" nowrap="nowrap" style="background: url(\'$skin[images]/header_namebg.gif\'); padding-top: 43px; padding-left: 6px;">
			<span style="color: #274EAD; font-family: Arial Black; font-size: 26pt;">$appname</span>
		</td>
		<td	rowspan="2" valign="top" style="height: 100px; background-color: #8AB8E5;">
			<img src="$skin[images]/header_middle_bridge.gif" align="middle" alt="" />
		</td>
		<td valign="top" style="width: 100%; height: 83px; background: url(\'$skin[images]/header_right_top.gif\'); padding: 0px;">
<%if $hiveuser[userid] <> 0%>
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td align="center" width="110" style="height: 58px;">
						<a href="index.php"><img src="$skin[images]/header_icon_inbox$headimgs[1].gif" id="header_inbox" align="middle" border="0" alt="" onMouseOver="this.src = \'$skin[images]/header_icon_inbox_high.gif\';" onMouseOut="this.src = \'$skin[images]/header_icon_inbox$headimgs[1].gif\';" /></a>
					</td>
					<td align="center" width="110">
						<a href="compose.email.php"><img src="$skin[images]/header_icon_compose$headimgs[2].gif" id="header_compose" align="middle" border="0" alt="" onMouseOver="this.src = \'$skin[images]/header_icon_compose_high.gif\';" onMouseOut="this.src = \'$skin[images]/header_icon_compose$headimgs[2].gif\';" /></a>
					</td>
					<td align="center" width="110">
						<a href="addressbook.view.php"><img src="$skin[images]/header_icon_addbook$headimgs[3].gif" id="header_addbook" align="middle" border="0" alt="" onMouseOver="this.src = \'$skin[images]/header_icon_addbook_high.gif\';" onMouseOut="this.src = \'$skin[images]/header_icon_addbook$headimgs[3].gif\';" /></a>
					</td>
					<td align="center" width="110">
						<a href="options.menu.php"><img src="$skin[images]/header_icon_options$headimgs[4].gif" id="header_options" align="middle" border="0" alt="" onMouseOver="this.src = \'$skin[images]/header_icon_options_high.gif\';" onMouseOut="this.src = \'$skin[images]/header_icon_options$headimgs[4].gif\';" /></a>
					</td>
					<%if $hiveuser[\'cansearch\'] %>
					<td align="center" width="110">
						<a href="search.intro.php"><img src="$skin[images]/header_icon_search$headimgs[5].gif" id="header_search" align="middle" border="0" alt="" onMouseOver="this.src = \'$skin[images]/header_icon_search_high.gif\';" onMouseOut="this.src = \'$skin[images]/header_icon_search$headimgs[5].gif\';" /></a>
					</td>
					<%endif%>
				</tr>
				<tr>
					<td align="center" nowrap="nowrap">
						<a href="index.php" class="headerLink" onMouseOver="header_inbox.src = \'$skin[images]/header_icon_inbox_high.gif\';" onMouseOut="header_inbox.src = \'$skin[images]/header_icon_inbox$headimgs[1].gif\';"><span class="headerLink">Inbox</span></a>
					</td>
					<td align="center" nowrap="nowrap">
						<a href="compose.email.php" class="headerLink" onMouseOver="header_compose.src = \'$skin[images]/header_icon_compose_high.gif\';" onMouseOut="header_compose.src = \'$skin[images]/header_icon_compose$headimgs[2].gif\';"><span class="headerLink">Compose</span></a>
					</td>
					<td align="center" nowrap="nowrap">
						<a href="addressbook.view.php" class="headerLink" onMouseOver="header_addbook.src = \'$skin[images]/header_icon_addbook_high.gif\';" onMouseOut="header_addbook.src = \'$skin[images]/header_icon_addbook$headimgs[3].gif\';"><span class="headerLink">Address Book</span></a>
					</td>
					<td align="center" nowrap="nowrap">
						<a href="options.menu.php" class="headerLink" onMouseOver="header_options.src = \'$skin[images]/header_icon_options_high.gif\';" onMouseOut="header_options.src = \'$skin[images]/header_icon_options$headimgs[4].gif\';"><span class="headerLink">Preferences</span></a>
					</td>
					<%if $hiveuser[\'cansearch\'] %>
					<td align="center" nowrap="nowrap">
						<a href="search.intro.php" class="headerLink" onMouseOver="header_search.src = \'$skin[images]/header_icon_search_high.gif\';" onMouseOut="header_search.src = \'$skin[images]/header_icon_search$headimgs[5].gif\';"><span class="headerLink">Search</span></a>
					</td>
					<%endif%>
				</tr>
			</table>
<%else%>
			&nbsp;
<%endif%>
		</td>
	</tr>
	<tr>
		<td style="height: 19px; background: url(\'$skin[images]/header_right_bottom.gif\'); padding-left: 10px;">
			<img src="$skin[images]/spacer.gif" width="1" height="1" alt="" /><!--<span style="color: #274EAD; font-family: $skin[fontface]; font-size: 12px;">$youarehere</span>-->
		</td>
	</tr>
	<tr>
		<td colspan="4" style="height: 9px; background: url(\'$skin[images]/middle_top.gif\'); width: 100%;">
		</td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0" width="100%" style="background-color: #C7E1F4;">
	<tr>
<%if $hiveuser[showfoldertab]%>
		<td style="width: 157px;" valign="top">
			<table cellpadding="0" cellspacing="0" style="width: 157px;">
				<tr>
					<td valign="top" style="background: url(\'$skin[images]/folders_top.gif\'); background-position: top right; width: 157px; padding-top: 6px; padding-left: 8px; padding-right: 15px;">
						<a href="folders.list.php" style="text-decoration: none;"><span style="color: #000000; font-family: Arial Black; font-size: 22pt; text-decoration: none;">Folders</span></a><!--<br />
						<img src="$skin[images]/folders_line.gif" align="middle" width="90%" height="2" alt="" />-->
					</td>
				</tr>
				<tr>
					<td valign="top" style="background: url(\'$skin[images]/folders_bg.gif\'); background-position: right; width: 157px; padding-top: 6px; padding-left: 8px; padding-right: 9px;">
						<table>
$defaultfolders
$customfolders
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top" style="background: url(\'$skin[images]/folders_bottom.gif\'); background-position: bottom right; width: 157px;">
						&nbsp;
					</td>
				</tr>
			</table>
		</td>
		<td style="width: 14px;">
			<img src="$skin[images]/spacer.gif" width="14" height="1" alt="" />
		</td>
<%else%>
		<td style="width: 7px;">
			<img src="$skin[images]/spacer.gif" width="7" height="1" alt="" />
		</td>
<%endif%>
		<td style="width: 100%;">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<tr>
					<td valign="top" style="background: $skin[pagebgcolor] url(\'$skin[images]/content_topleft.gif\'); width: 16px; height: 18px;">
						<img src="$skin[images]/spacer.gif" width="16" height="1" alt="" />
					</td>
					<td style="background-color: $skin[pagebgcolor]; padding-top: 5px; border: 0px solid #9BC1E6; border-top-width: 1px; width: 100%;">
						<span style="color: #274EAD; font-family: $skin[fontface]; font-size: 12px;">&nbsp;</span>
					</td>
				</tr>
				<tr>
					<td valign="top" style="background: $skin[pagebgcolor] url(\'$skin[images]/content_leftbg.gif\'); width: 16px; height: 100%;">
						&nbsp;
					</td>
					<td valign="top" style="background-color: $skin[pagebgcolor]; width: 100%; padding-right: 15px;">
<%if getop(\'maintain\')%>
<div style="border: 1px solid red; margin: 10px 0px; padding: 3px; background-color: #FFCECE;"><span class="normalfont">$appname is currently in maintenance mode. Non-administrators cannot use $appname.</span></div>
<%endif%>',
    'parsed_data' => '"<table cellpadding=\\"0\\" cellspacing=\\"0\\" width=\\"100%\\">
	<tr>
		<td rowspan=\\"2\\" valign=\\"top\\" style=\\"height: 100px; background-color: #8AB8E5;\\">
			<img src=\\"{$GLOBALS[skin][images]}/header_left.gif\\" align=\\"middle\\" alt=\\"\\" />
		</td>
		<td rowspan=\\"2\\" nowrap=\\"nowrap\\" style=\\"background: url(\'{$GLOBALS[skin][images]}/header_namebg.gif\'); padding-top: 43px; padding-left: 6px;\\">
			<span style=\\"color: #274EAD; font-family: Arial Black; font-size: 26pt;\\">$appname</span>
		</td>
		<td	rowspan=\\"2\\" valign=\\"top\\" style=\\"height: 100px; background-color: #8AB8E5;\\">
			<img src=\\"{$GLOBALS[skin][images]}/header_middle_bridge.gif\\" align=\\"middle\\" alt=\\"\\" />
		</td>
		<td valign=\\"top\\" style=\\"width: 100%; height: 83px; background: url(\'{$GLOBALS[skin][images]}/header_right_top.gif\'); padding: 0px;\\">
".(($hiveuser[userid] <> 0) ? ("
			<table cellpadding=\\"0\\" cellspacing=\\"0\\">
				<tr>
					<td align=\\"center\\" width=\\"110\\" style=\\"height: 58px;\\">
						<a href=\\"index.php{$GLOBALS[session_url]}\\"><img src=\\"{$GLOBALS[skin][images]}/header_icon_inbox$headimgs[1].gif\\" id=\\"header_inbox\\" align=\\"middle\\" border=\\"0\\" alt=\\"\\" onMouseOver=\\"this.src = \'{$GLOBALS[skin][images]}/header_icon_inbox_high.gif\';\\" onMouseOut=\\"this.src = \'{$GLOBALS[skin][images]}/header_icon_inbox$headimgs[1].gif\';\\" /></a>
					</td>
					<td align=\\"center\\" width=\\"110\\">
						<a href=\\"compose.email.php{$GLOBALS[session_url]}\\"><img src=\\"{$GLOBALS[skin][images]}/header_icon_compose$headimgs[2].gif\\" id=\\"header_compose\\" align=\\"middle\\" border=\\"0\\" alt=\\"\\" onMouseOver=\\"this.src = \'{$GLOBALS[skin][images]}/header_icon_compose_high.gif\';\\" onMouseOut=\\"this.src = \'{$GLOBALS[skin][images]}/header_icon_compose$headimgs[2].gif\';\\" /></a>
					</td>
					<td align=\\"center\\" width=\\"110\\">
						<a href=\\"addressbook.view.php{$GLOBALS[session_url]}\\"><img src=\\"{$GLOBALS[skin][images]}/header_icon_addbook$headimgs[3].gif\\" id=\\"header_addbook\\" align=\\"middle\\" border=\\"0\\" alt=\\"\\" onMouseOver=\\"this.src = \'{$GLOBALS[skin][images]}/header_icon_addbook_high.gif\';\\" onMouseOut=\\"this.src = \'{$GLOBALS[skin][images]}/header_icon_addbook$headimgs[3].gif\';\\" /></a>
					</td>
					<td align=\\"center\\" width=\\"110\\">
						<a href=\\"options.menu.php{$GLOBALS[session_url]}\\"><img src=\\"{$GLOBALS[skin][images]}/header_icon_options$headimgs[4].gif\\" id=\\"header_options\\" align=\\"middle\\" border=\\"0\\" alt=\\"\\" onMouseOver=\\"this.src = \'{$GLOBALS[skin][images]}/header_icon_options_high.gif\';\\" onMouseOut=\\"this.src = \'{$GLOBALS[skin][images]}/header_icon_options$headimgs[4].gif\';\\" /></a>
					</td>
					".(($hiveuser[\'cansearch\'] ) ? ("
					<td align=\\"center\\" width=\\"110\\">
						<a href=\\"search.intro.php{$GLOBALS[session_url]}\\"><img src=\\"{$GLOBALS[skin][images]}/header_icon_search$headimgs[5].gif\\" id=\\"header_search\\" align=\\"middle\\" border=\\"0\\" alt=\\"\\" onMouseOver=\\"this.src = \'{$GLOBALS[skin][images]}/header_icon_search_high.gif\';\\" onMouseOut=\\"this.src = \'{$GLOBALS[skin][images]}/header_icon_search$headimgs[5].gif\';\\" /></a>
					</td>
					") : (\'\'))."
				</tr>
				<tr>
					<td align=\\"center\\" nowrap=\\"nowrap\\">
						<a href=\\"index.php{$GLOBALS[session_url]}\\" class=\\"headerLink\\" onMouseOver=\\"header_inbox.src = \'{$GLOBALS[skin][images]}/header_icon_inbox_high.gif\';\\" onMouseOut=\\"header_inbox.src = \'{$GLOBALS[skin][images]}/header_icon_inbox$headimgs[1].gif\';\\"><span class=\\"headerLink\\">Inbox</span></a>
					</td>
					<td align=\\"center\\" nowrap=\\"nowrap\\">
						<a href=\\"compose.email.php{$GLOBALS[session_url]}\\" class=\\"headerLink\\" onMouseOver=\\"header_compose.src = \'{$GLOBALS[skin][images]}/header_icon_compose_high.gif\';\\" onMouseOut=\\"header_compose.src = \'{$GLOBALS[skin][images]}/header_icon_compose$headimgs[2].gif\';\\"><span class=\\"headerLink\\">Compose</span></a>
					</td>
					<td align=\\"center\\" nowrap=\\"nowrap\\">
						<a href=\\"addressbook.view.php{$GLOBALS[session_url]}\\" class=\\"headerLink\\" onMouseOver=\\"header_addbook.src = \'{$GLOBALS[skin][images]}/header_icon_addbook_high.gif\';\\" onMouseOut=\\"header_addbook.src = \'{$GLOBALS[skin][images]}/header_icon_addbook$headimgs[3].gif\';\\"><span class=\\"headerLink\\">Address Book</span></a>
					</td>
					<td align=\\"center\\" nowrap=\\"nowrap\\">
						<a href=\\"options.menu.php{$GLOBALS[session_url]}\\" class=\\"headerLink\\" onMouseOver=\\"header_options.src = \'{$GLOBALS[skin][images]}/header_icon_options_high.gif\';\\" onMouseOut=\\"header_options.src = \'{$GLOBALS[skin][images]}/header_icon_options$headimgs[4].gif\';\\"><span class=\\"headerLink\\">Preferences</span></a>
					</td>
					".(($hiveuser[\'cansearch\'] ) ? ("
					<td align=\\"center\\" nowrap=\\"nowrap\\">
						<a href=\\"search.intro.php{$GLOBALS[session_url]}\\" class=\\"headerLink\\" onMouseOver=\\"header_search.src = \'{$GLOBALS[skin][images]}/header_icon_search_high.gif\';\\" onMouseOut=\\"header_search.src = \'{$GLOBALS[skin][images]}/header_icon_search$headimgs[5].gif\';\\"><span class=\\"headerLink\\">Search</span></a>
					</td>
					") : (\'\'))."
				</tr>
			</table>
") : ("
			&nbsp;
"))."
		</td>
	</tr>
	<tr>
		<td style=\\"height: 19px; background: url(\'{$GLOBALS[skin][images]}/header_right_bottom.gif\'); padding-left: 10px;\\">
			<img src=\\"{$GLOBALS[skin][images]}/spacer.gif\\" width=\\"1\\" height=\\"1\\" alt=\\"\\" /><!--<span style=\\"color: #274EAD; font-family: {$GLOBALS[skin][fontface]}; font-size: 12px;\\">$youarehere</span>-->
		</td>
	</tr>
	<tr>
		<td colspan=\\"4\\" style=\\"height: 9px; background: url(\'{$GLOBALS[skin][images]}/middle_top.gif\'); width: 100%;\\">
		</td>
	</tr>
</table>
<table cellpadding=\\"0\\" cellspacing=\\"0\\" width=\\"100%\\" style=\\"background-color: #C7E1F4;\\">
	<tr>
".(($hiveuser[showfoldertab]) ? ("
		<td style=\\"width: 157px;\\" valign=\\"top\\">
			<table cellpadding=\\"0\\" cellspacing=\\"0\\" style=\\"width: 157px;\\">
				<tr>
					<td valign=\\"top\\" style=\\"background: url(\'{$GLOBALS[skin][images]}/folders_top.gif\'); background-position: top right; width: 157px; padding-top: 6px; padding-left: 8px; padding-right: 15px;\\">
						<a href=\\"folders.list.php{$GLOBALS[session_url]}\\" style=\\"text-decoration: none;\\"><span style=\\"color: #000000; font-family: Arial Black; font-size: 22pt; text-decoration: none;\\">Folders</span></a><!--<br />
						<img src=\\"{$GLOBALS[skin][images]}/folders_line.gif\\" align=\\"middle\\" width=\\"90%\\" height=\\"2\\" alt=\\"\\" />-->
					</td>
				</tr>
				<tr>
					<td valign=\\"top\\" style=\\"background: url(\'{$GLOBALS[skin][images]}/folders_bg.gif\'); background-position: right; width: 157px; padding-top: 6px; padding-left: 8px; padding-right: 9px;\\">
						<table>
$defaultfolders
$customfolders
						</table>
					</td>
				</tr>
				<tr>
					<td valign=\\"top\\" style=\\"background: url(\'{$GLOBALS[skin][images]}/folders_bottom.gif\'); background-position: bottom right; width: 157px;\\">
						&nbsp;
					</td>
				</tr>
			</table>
		</td>
		<td style=\\"width: 14px;\\">
			<img src=\\"{$GLOBALS[skin][images]}/spacer.gif\\" width=\\"14\\" height=\\"1\\" alt=\\"\\" />
		</td>
") : ("
		<td style=\\"width: 7px;\\">
			<img src=\\"{$GLOBALS[skin][images]}/spacer.gif\\" width=\\"7\\" height=\\"1\\" alt=\\"\\" />
		</td>
"))."
		<td style=\\"width: 100%;\\">
			<table cellpadding=\\"0\\" cellspacing=\\"0\\" style=\\"width: 100%;\\">
				<tr>
					<td valign=\\"top\\" style=\\"background: {$GLOBALS[skin][pagebgcolor]} url(\'{$GLOBALS[skin][images]}/content_topleft.gif\'); width: 16px; height: 18px;\\">
						<img src=\\"{$GLOBALS[skin][images]}/spacer.gif\\" width=\\"16\\" height=\\"1\\" alt=\\"\\" />
					</td>
					<td style=\\"background-color: {$GLOBALS[skin][pagebgcolor]}; padding-top: 5px; border: 0px solid #9BC1E6; border-top-width: 1px; width: 100%;\\">
						<span style=\\"color: #274EAD; font-family: {$GLOBALS[skin][fontface]}; font-size: 12px;\\">&nbsp;</span>
					</td>
				</tr>
				<tr>
					<td valign=\\"top\\" style=\\"background: {$GLOBALS[skin][pagebgcolor]} url(\'{$GLOBALS[skin][images]}/content_leftbg.gif\'); width: 16px; height: 100%;\\">
						&nbsp;
					</td>
					<td valign=\\"top\\" style=\\"background-color: {$GLOBALS[skin][pagebgcolor]}; width: 100%; padding-right: 15px;\\">
".((getop(\'maintain\')) ? ("
<div style=\\"border: 1px solid red; margin: 10px 0px; padding: 3px; background-color: #FFCECE;\\"><span class=\\"normalfont\\">$appname is currently in maintenance mode. Non-administrators cannot use $appname.</span></div>
") : (\'\'))',
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
		new ContextItem(\'Reply to Sender\', function(){ replyForward(form, \'reply\'); }, totalChecked != 1),
		new ContextItem(\'Reply to All\', function(){ replyForward(form, \'replyall\'); }, totalChecked != 1),
		new ContextItem(\'Forward\', function(){ replyForward(form, \'forward\'); }, totalChecked != 1),
		new ContextItem(\'Forward as Attachment\', function(){ replyForward(form, \'forwardattach\'); }),
		new ContextSeperator(),
		new ContextItem(\'Mark as Read\', function(){ form.cmd.value = \'mark\'; form.markas.selectedIndex = 2; form.submit(); }, isNew.indexOf(\'new\') == -1 && totalChecked == 1),
		new ContextItem(\'Mark as Unread\', function(){ form.cmd.value = \'mark\'; form.markas.selectedIndex = 3; form.submit(); }, isNew.indexOf(\'new\') != -1 && totalChecked == 1),
		new ContextItem(\'Flag\', function(){ form.cmd.value = \'mark\'; form.markas.selectedIndex = 4; form.submit(); }, isFlagged == 1 && totalChecked == 1),
		new ContextItem(\'Unflag\', function(){ form.cmd.value = \'mark\'; form.markas.selectedIndex = 5; form.submit(); }, isFlagged == 0 && totalChecked == 1),
		new ContextSeperator(),
		new ContextItem(\'Move...\', function(){ window.open(\'index.php?cmd=selfolder\',\'selectfolders\',\'resizable=no,width=270,height=150\'); }),
		new ContextItem(\'Delete\', function(){ if (confirm(\'Are you sure you want to delete the selected messages?\')) { form.cmd.value = \'delete\'; form.submit(); } }),
		new ContextSeperator(),
		new ContextItem(\'Add Sender(s) to Address Book\', function(){ actionStuff(form, \'addbook\'); })
<%if $hiveuser[canrule] %>,
		new ContextItem(\'Block Sender(s)...\', function(){ actionStuff(form, \'blocksender\'); }),
		new ContextItem(\'Block Subject(s)...\', function(){ actionStuff(form, \'blocksubject\'); })
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
<input type="hidden" name="cmd" id="cmd" value="dostuff" />
<input type="hidden" name="folderid" value="$folderid" />
<input type="hidden" name="movetofolderid" value="$folderid" />
<input type="hidden" name="remove" value="0" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerLeftCell">&nbsp;</th>
$colheaders	<th class="headerRightCell"><input name="allbox" type="checkbox" value="Check All" title="Select/Deselect All" onClick="checkAll(this.form); changeButtonsStatus(!this.checked); if (this.checked) makeRows(\'first\'); else makeRows(\'second\');" /></th>
</tr>
$mailbits
<tr>
	<td colspan="10">
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td align="left"><span class="smallfonttablehead"><b>
		<select name="actions" onChange="if (this.options[this.selectedIndex].value != \'nothing\') { if (actionStuff(this.form, this.options[this.selectedIndex].value) == false) this.selectedIndex = 0; } else { this.selectedIndex = 0; }">
			<option value="nothing" selected="selected">Actions to perform...</option>
			<option value="nothing">--------------------------</option>
			<option value="move">Move messages</option>
			<!--<option value="copy">Copy messages</option>-->
			<option value="delete">Delete messages</option>
			<option value="nothing">--------------------------</option>
			<option value="addbook">Add senders to address book</option>
			<option value="blocksender">Block senders</option>
			<option value="blocksubject">Block subjects</option>
		</select>
		&nbsp;
		<select name="replystuff" onChange="if (this.options[this.selectedIndex].value != \'nothing\') { if(replyForward(this.form, this.options[this.selectedIndex].value) == false) this.selectedIndex = 0; } else { this.selectedIndex = 0; }">
			<option value="nothing" selected="selected">Reply or forward...</option>
			<option value="nothing">--------------------------</option>
			<option value="reply">Reply to sender</option>
			<option value="replyall">Reply to all</option>
			<option value="forward">Forward message</option>
			<option value="forwardattach">Forward as attachment</option>
		</select>
		</b></span></td>
        <td align="right"><span class="smallfonttablehead"><b>
		<select name="markas" onChange="if (this.options[this.selectedIndex].value != \'nothing\') { this.form.cmd.value = \'mark\'; this.form.submit(); } else { this.selectedIndex = 0; }">
			<option value="nothing" selected="selected">Mark selected messages...</option>
			<option value="nothing">--------------------------</option>
			<option value="read">Mark as read</option>
			<option value="not read">Mark as not read</option>
			<option value="flagged">Mark as flagged</option>
			<option value="not flagged">Mark as not flagged</option>
			<option value="replied">Mark as replied</option>
			<option value="not replied">Mark as not replied</option>
			<option value="forwarded">Mark as forwarded</option>
			<option value="not forwarded">Mark as not forwarded</option>
		</select></b></span></td>
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
		new ContextItem(\'Reply to Sender\', function(){ replyForward(form, \'reply\'); }, totalChecked != 1),
		new ContextItem(\'Reply to All\', function(){ replyForward(form, \'replyall\'); }, totalChecked != 1),
		new ContextItem(\'Forward\', function(){ replyForward(form, \'forward\'); }, totalChecked != 1),
		new ContextItem(\'Forward as Attachment\', function(){ replyForward(form, \'forwardattach\'); }),
		new ContextSeperator(),
		new ContextItem(\'Mark as Read\', function(){ form.cmd.value = \'mark\'; form.markas.selectedIndex = 2; form.submit(); }, isNew.indexOf(\'new\') == -1 && totalChecked == 1),
		new ContextItem(\'Mark as Unread\', function(){ form.cmd.value = \'mark\'; form.markas.selectedIndex = 3; form.submit(); }, isNew.indexOf(\'new\') != -1 && totalChecked == 1),
		new ContextItem(\'Flag\', function(){ form.cmd.value = \'mark\'; form.markas.selectedIndex = 4; form.submit(); }, isFlagged == 1 && totalChecked == 1),
		new ContextItem(\'Unflag\', function(){ form.cmd.value = \'mark\'; form.markas.selectedIndex = 5; form.submit(); }, isFlagged == 0 && totalChecked == 1),
		new ContextSeperator(),
		new ContextItem(\'Move...\', function(){ window.open(\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=selfolder\',\'selectfolders\',\'resizable=no,width=270,height=150\'); }),
		new ContextItem(\'Delete\', function(){ if (confirm(\'Are you sure you want to delete the selected messages?\')) { form.cmd.value = \'delete\'; form.submit(); } }),
		new ContextSeperator(),
		new ContextItem(\'Add Sender(s) to Address Book\', function(){ actionStuff(form, \'addbook\'); })
".(($hiveuser[canrule] ) ? (",
		new ContextItem(\'Block Sender(s)...\', function(){ actionStuff(form, \'blocksender\'); }),
		new ContextItem(\'Block Subject(s)...\', function(){ actionStuff(form, \'blocksubject\'); })
") : (\'\'))."
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
") : (\'\'))."

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
") : (\'\'))."

<form action=\\"index.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"cmd\\" id=\\"cmd\\" value=\\"dostuff\\" />
<input type=\\"hidden\\" name=\\"folderid\\" value=\\"$folderid\\" />
<input type=\\"hidden\\" name=\\"movetofolderid\\" value=\\"$folderid\\" />
<input type=\\"hidden\\" name=\\"remove\\" value=\\"0\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerLeftCell\\">&nbsp;</th>
$colheaders	<th class=\\"headerRightCell\\"><input name=\\"allbox\\" type=\\"checkbox\\" value=\\"Check All\\" title=\\"Select/Deselect All\\" onClick=\\"checkAll(this.form); changeButtonsStatus(!this.checked); if (this.checked) makeRows(\'first\'); else makeRows(\'second\');\\" /></th>
</tr>
$mailbits
<tr>
	<td colspan=\\"10\\">
    <table border=\\"0\\" width=\\"100%\\" cellpadding=\\"0\\" cellspacing=\\"0\\">
      <tr>
        <td align=\\"left\\"><span class=\\"smallfonttablehead\\"><b>
		<select name=\\"actions\\" onChange=\\"if (this.options[this.selectedIndex].value != \'nothing\') { if (actionStuff(this.form, this.options[this.selectedIndex].value) == false) this.selectedIndex = 0; } else { this.selectedIndex = 0; }\\">
			<option value=\\"nothing\\" selected=\\"selected\\">Actions to perform...</option>
			<option value=\\"nothing\\">--------------------------</option>
			<option value=\\"move\\">Move messages</option>
			<!--<option value=\\"copy\\">Copy messages</option>-->
			<option value=\\"delete\\">Delete messages</option>
			<option value=\\"nothing\\">--------------------------</option>
			<option value=\\"addbook\\">Add senders to address book</option>
			<option value=\\"blocksender\\">Block senders</option>
			<option value=\\"blocksubject\\">Block subjects</option>
		</select>
		&nbsp;
		<select name=\\"replystuff\\" onChange=\\"if (this.options[this.selectedIndex].value != \'nothing\') { if(replyForward(this.form, this.options[this.selectedIndex].value) == false) this.selectedIndex = 0; } else { this.selectedIndex = 0; }\\">
			<option value=\\"nothing\\" selected=\\"selected\\">Reply or forward...</option>
			<option value=\\"nothing\\">--------------------------</option>
			<option value=\\"reply\\">Reply to sender</option>
			<option value=\\"replyall\\">Reply to all</option>
			<option value=\\"forward\\">Forward message</option>
			<option value=\\"forwardattach\\">Forward as attachment</option>
		</select>
		</b></span></td>
        <td align=\\"right\\"><span class=\\"smallfonttablehead\\"><b>
		<select name=\\"markas\\" onChange=\\"if (this.options[this.selectedIndex].value != \'nothing\') { this.form.cmd.value = \'mark\'; this.form.submit(); } else { this.selectedIndex = 0; }\\">
			<option value=\\"nothing\\" selected=\\"selected\\">Mark selected messages...</option>
			<option value=\\"nothing\\">--------------------------</option>
			<option value=\\"read\\">Mark as read</option>
			<option value=\\"not read\\">Mark as not read</option>
			<option value=\\"flagged\\">Mark as flagged</option>
			<option value=\\"not flagged\\">Mark as not flagged</option>
			<option value=\\"replied\\">Mark as replied</option>
			<option value=\\"not replied\\">Mark as not replied</option>
			<option value=\\"forwarded\\">Mark as forwarded</option>
			<option value=\\"not forwarded\\">Mark as not forwarded</option>
		</select></b></span></td>
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
") : (\'\'))."

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

function sendAndClose(newvalue, command) {
	var openerWin = window.opener;
	openerWin.document.form.movetofolderid.value = newvalue;
	openerWin.document.form.cmd.value = command;
	openerWin.focus();
	openerWin.document.form.submit();
	self.close();
}
// --></script>
</head>
<body style="background-color: #C7E1F4;">

$header

<form name="selectform">

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Move Messages</b></span></th>
</tr>
<tr class="highRow">
	<td class="highBothCell"><span class="smallfont">To:
	<select name="folders">
		$movefolderjump
	</select></span></td>
</tr>
</table>

<br />

<div align="center">
<input type="button" class="bginput" value="Move" onClick="sendAndClose(this.form.folders.options[this.form.folders.selectedIndex].value, \'move\');" /><!--&nbsp;&nbsp;<input type="button" class="bginput" value="Copy" onClick="sendAndClose(this.form.folders.options[this.form.folders.selectedIndex].value, \'copy\');" />-->&nbsp;&nbsp;<input type="button" class="bginput" value="Cancel" onClick="window.close();" />
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

function sendAndClose(newvalue, command) {
	var openerWin = window.opener;
	openerWin.document.form.movetofolderid.value = newvalue;
	openerWin.document.form.cmd.value = command;
	openerWin.focus();
	openerWin.document.form.submit();
	self.close();
}
// --></script>
</head>
<body style=\\"background-color: #C7E1F4;\\">

$GLOBALS[header]

<form name=\\"selectform\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Move Messages</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\"><span class=\\"smallfont\\">To:
	<select name=\\"folders\\">
		$movefolderjump
	</select></span></td>
</tr>
</table>

<br />

<div align=\\"center\\">
<input type=\\"button\\" class=\\"bginput\\" value=\\"Move\\" onClick=\\"sendAndClose(this.form.folders.options[this.form.folders.selectedIndex].value, \'move\');\\" /><!--&nbsp;&nbsp;<input type=\\"button\\" class=\\"bginput\\" value=\\"Copy\\" onClick=\\"sendAndClose(this.form.folders.options[this.form.folders.selectedIndex].value, \'copy\');\\" />-->&nbsp;&nbsp;<input type=\\"button\\" class=\\"bginput\\" value=\\"Cancel\\" onClick=\\"window.close();\\" />
</div>
</form>

$GLOBALS[footer]

</body>
</html>"',
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
	<form method="post" action="$_SERVER[PHP_SELF]" name="hivemail_login">
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
		<%if getop(\'skinonlogin\') %>
		<tr>
			<td valign="top" align="right"><span class="normalfont">Skin:&nbsp;</span></td>
			<td align="left"><select name="skinid" style="width: 135px;">
				<option value="0" selected="selected">Choose a skin...</option>
				$skinoptions
			</select></td>
		</tr>
		<%endif%>
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
<%if !getop(\'vb_use\') or getop(\'vb_allownormal\') %>
	<form method="post" action="user.signup.php" onSubmit="grabTime(this); return true;">
	<input type="hidden" name="cmd" value="getinfo">
	<input type="hidden" name="jstime" value="">
<%else%>
	<form method="post" action="$_options[vb_url]/register.php">
<%endif%>
	<table cellpadding="2">
<%if !getop(\'vb_use\') or getop(\'vb_allownormal\') %>
		<tr>
			<td valign="middle" align="right">Desired account name:&nbsp;</td>
			<td align="left"><input type="text" name="<%if !getop(\'vb_use\') or getop(\'vb_allownormal\') %>username<%else%>hive_username<%endif%>" class="bginput" /> <select name="userdomain">$domainname_options</select></td>
		</tr>
		<tr>
			<td valign="middle" align="right">Desired password:&nbsp;</td>
			<td align="left"><input type="password" name="password" class="bginput" /></td>
		</tr>
		<tr>
			<td valign="middle" align="right">Retype password:&nbsp;</td>
			<td align="left"><input type="password" name="password_repeat" class="bginput" /></td>
		</tr>
<%endif%>
		<tr>
			<td align="center" colspan="2"><%if !getop(\'vb_use\') or getop(\'vb_allownormal\') %><br /><%endif%><input type="submit" value=" Sign Up " class="bginput" /></td>
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
	<input type="hidden" name="cmd" value="verify">
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
	<form method=\\"post\\" action=\\"$_SERVER[PHP_SELF]\\" name=\\"hivemail_login\\">
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
		".((getop(\'skinonlogin\') ) ? ("
		<tr>
			<td valign=\\"top\\" align=\\"right\\"><span class=\\"normalfont\\">Skin:&nbsp;</span></td>
			<td align=\\"left\\"><select name=\\"skinid\\" style=\\"width: 135px;\\">
				<option value=\\"0\\" selected=\\"selected\\">Choose a skin...</option>
				$skinoptions
			</select></td>
		</tr>
		") : (\'\'))."
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
".((!getop(\'vb_use\') or getop(\'vb_allownormal\') ) ? ("
	<form method=\\"post\\" action=\\"user.signup.php{$GLOBALS[session_url]}\\" onSubmit=\\"grabTime(this); return true;\\">
	<input type=\\"hidden\\" name=\\"cmd\\" value=\\"getinfo\\">
	<input type=\\"hidden\\" name=\\"jstime\\" value=\\"\\">
") : ("
	<form method=\\"post\\" action=\\"$_options[vb_url]/register.php{$GLOBALS[session_url]}\\">
"))."
	<table cellpadding=\\"2\\">
".((!getop(\'vb_use\') or getop(\'vb_allownormal\') ) ? ("
		<tr>
			<td valign=\\"middle\\" align=\\"right\\">Desired account name:&nbsp;</td>
			<td align=\\"left\\"><input type=\\"text\\" name=\\"".((!getop(\'vb_use\') or getop(\'vb_allownormal\') ) ? ("username") : ("hive_username"))."\\" class=\\"bginput\\" /> <select name=\\"userdomain\\">$domainname_options</select></td>
		</tr>
		<tr>
			<td valign=\\"middle\\" align=\\"right\\">Desired password:&nbsp;</td>
			<td align=\\"left\\"><input type=\\"password\\" name=\\"password\\" class=\\"bginput\\" /></td>
		</tr>
		<tr>
			<td valign=\\"middle\\" align=\\"right\\">Retype password:&nbsp;</td>
			<td align=\\"left\\"><input type=\\"password\\" name=\\"password_repeat\\" class=\\"bginput\\" /></td>
		</tr>
") : (\'\'))."
		<tr>
			<td align=\\"center\\" colspan=\\"2\\">".((!getop(\'vb_use\') or getop(\'vb_allownormal\') ) ? ("<br />") : (\'\'))."<input type=\\"submit\\" value=\\" Sign Up \\" class=\\"bginput\\" /></td>
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
	<input type=\\"hidden\\" name=\\"cmd\\" value=\\"verify\\">
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
  'lostpw_verify' => 
  array (
    'templategroupid' => '12',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Lost Password</title>
$css
</head>
<body>
$header

<form action="user.lostpw.php" method="post" name="form">
<input type="hidden" name="cmd" value="update" />
<input type="hidden" name="username" value="$user[username]" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="700" align="center">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Lost Password</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Secret question:</b></span>
	<br />
	<span class="smallfont">In order to verify you are the holder of this account, please enter<br />the answer to the secret question you chose when signing up.</span></td>
	<td class="normalRightCell" width="40%" valign="top"><span class="normalfont"><b>$user[question]</b></span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Secret answer:</b></span></td>
	<td class="highRightCell" width="40%"><input type="text" class="bginput" name="answer" size="40" /></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>New password:</b></span>
	<br />
	<span class="smallfont">Please select a new password for your account.</span></td>
	<td class="normalRightCell" width="40%"><input type="password" class="bginput" name="password" size="40" /></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Retype password:</b></span>
	<br />
	<span class="smallfont">Repeat the password to verify it\'s correct.</span></td>
	<td class="highRightCell" width="40%"><input type="password" class="bginput" name="password_repeat" size="40" /></td>
</tr>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="700" align="center">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="pass" value="Update Password" onClick="if (form.password.value != form.password_repeat.value) { alert(\'The new passwords do not match. Please retype them and submit the form again.\'); return false; } else if (form.password.value.length == 0) { alert(\'Your new password must not be empty.\'); return false; } else { return true; }" />
	</td>
</tr>
</table>

</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Lost Password</title>
$GLOBALS[css]
</head>
<body>
$GLOBALS[header]

<form action=\\"user.lostpw.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />
<input type=\\"hidden\\" name=\\"username\\" value=\\"$user[username]\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\" align=\\"center\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Lost Password</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secret question:</b></span>
	<br />
	<span class=\\"smallfont\\">In order to verify you are the holder of this account, please enter<br />the answer to the secret question you chose when signing up.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>$user[question]</b></span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secret answer:</b></span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"answer\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>New password:</b></span>
	<br />
	<span class=\\"smallfont\\">Please select a new password for your account.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"password\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Retype password:</b></span>
	<br />
	<span class=\\"smallfont\\">Repeat the password to verify it\'s correct.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"password_repeat\\" size=\\"40\\" /></td>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\" align=\\"center\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"pass\\" value=\\"Update Password\\" onClick=\\"if (form.password.value != form.password_repeat.value) { alert(\'The new passwords do not match. Please retype them and submit the form again.\'); return false; } else if (form.password.value.length == 0) { alert(\'Your new password must not be empty.\'); return false; } else { return true; }\\" />
	</td>
</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'mailbit_from' => 
  array (
    'templategroupid' => '16',
    'user_data' => '	<td class="$bgcolors[name]Cell" width="25%" align="left" onContextMenu="checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;" onClick="checkMail($mail[messageid], 0, 1);"><span class="normalfont"><%if $hiveuser[senderlink]%><a href="compose.email.php?email=$mail[link]">$mail[fromname]</a><%else%>$mail[fromname]<%endif%></span></td>
',
    'parsed_data' => '"	<td class=\\"$bgcolors[name]Cell\\" width=\\"25%\\" align=\\"left\\" onContextMenu=\\"checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;\\" onClick=\\"checkMail($mail[messageid], 0, 1);\\"><span class=\\"normalfont\\">".(($hiveuser[senderlink]) ? ("<a href=\\"compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}email=$mail[link]\\">$mail[fromname]</a>") : ("$mail[fromname]"))."</span></td>
"',
  ),
  'mailbit_subject' => 
  array (
    'templategroupid' => '16',
    'user_data' => '	<td class="$bgcolors[subject]Cell" align="left" width="55%" onContextMenu="checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;" onClick="checkMail($mail[messageid], 0, 1);"><span class="normalfont"><%if $mail[\'isflagged\'] %><img src="$skin[images]/flag.gif" alt="Flagged" />&nbsp; <%endif%><a href="read.email.php?messageid=$mail[messageid]">$mail[subject]</a></span></td>
',
    'parsed_data' => '"	<td class=\\"$bgcolors[subject]Cell\\" align=\\"left\\" width=\\"55%\\" onContextMenu=\\"checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;\\" onClick=\\"checkMail($mail[messageid], 0, 1);\\"><span class=\\"normalfont\\">".(($mail[\'isflagged\'] ) ? ("<img src=\\"{$GLOBALS[skin][images]}/flag.gif\\" alt=\\"Flagged\\" />&nbsp; ") : (\'\'))."<a href=\\"read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\\">$mail[subject]</a></span></td>
"',
  ),
  'options_autoresponders' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Auto responder</title>
$css
<script language="JavaScript" type="text/javascript">
<!--
var totalSigs = $totalrResponses_real;
// -->
</script>
<script type="text/javascript" src="misc/signatures.js"></script>
</head>
<body>
$header

<form action="options.autoresponders.php" method="post" name="sigform" onSubmit="updateSigDisplay(this);">
<input type="hidden" name="cmd" value="update" />

<!-- Current, default and new responses -->
<input type="hidden" name="cursig" value="sig0" />
<input type="hidden" name="defsig" value="$defsig" />
<input type="hidden" name="newsig" value="" />
<input type="hidden" name="delsig" value="" />
<!-- Responses text -->
$sig_text
<!-- Responses title -->
$sig_title

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Your auto responder</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Automatically respond to all:</b></span>
	<br />
	<span class="smallfont">If this is turned on, the default response will be be sent to anyone who sends you mail.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="autorespond" value="1" id="autorespondon" $autorespondon /> <label for="autorespondon">Yes</label><br /><input type="radio" name="autorespond" value="0" id="autorespondoff" $autorespondoff /> <label for="autorespondoff">No</label></td>
</tr>
<tr class="highRow">
	<td class="highBothCell" colspan="2" width="100%" valign="top"><span class="normalfont"><b>Responses Editor:</b><br />
	To edit a response, select it from the list below and edit it in large box.<br />
	To rename a response, select it then click the Rename button below and enter the new name.<br />
	To mark your default response, select it from the list and click the Make Default button below.<br />
	To create a new response, click the Create New button below and enter the name of the new response.<%if $totalresponses >= $hiveuser[\'maxresponses\'] %><br />(<b>Note</b>: You may only have up to $hiveuser[maxresponses] responses. You won\'t be able to create new responses until you delete at least some of your current responses.)<%endif%><br />
	To delete a response, select it from the list and click the Delete button below. <b>Note</b>: You cannot remove your default response, unless it is the only response you have.<br />
	<br />
	Please remember to click the Update Responses button at the bottom of this page, or else any changes that you make here will have no effect!</span><br /><br />
	<table>
		<tr>
			<td valign="top"><select name="sigs" size="9" onChange="updateSigDisplay(this.form);">
					$sig_options
				</select></td>
			<td valign="top"><textarea name="sigedit" cols="70" rows="8">(select a response to edit from the list)</textarea></td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="button" name="rename" class="bginput" disabled="disabled" value="Rename" onClick="renameSig(this.form, this.form.sigs.options[this.form.sigs.selectedIndex].text);" /> <input type="button" name="makedef" class="bginput" disabled="disabled" value="Make Default" onClick="updateDefaultSig(this.form);" /> <input type="submit" name="createnew" class="bginput" value="Create New" <%if $totalresponses >= $hiveuser[\'maxresponses\'] %>disabled="disabled"<%endif%> onClick="return createNewSig(this.form);" /> <input type="submit" name="deletesig" disabled="disabled" class="bginput" value="Delete" onClick="return deleteSig(this.form);" />
			</td>
		</tr>
	</table></td>
</tr>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="submit" value="Update Responses" />
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
<head><title>$appname: Auto responder</title>
$GLOBALS[css]
<script language=\\"JavaScript\\" type=\\"text/javascript\\">
<!--
var totalSigs = $totalrResponses_real;
// -->
</script>
<script type=\\"text/javascript\\" src=\\"misc/signatures.js\\"></script>
</head>
<body>
$GLOBALS[header]

<form action=\\"options.autoresponders.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"sigform\\" onSubmit=\\"updateSigDisplay(this);\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />

<!-- Current, default and new responses -->
<input type=\\"hidden\\" name=\\"cursig\\" value=\\"sig0\\" />
<input type=\\"hidden\\" name=\\"defsig\\" value=\\"$defsig\\" />
<input type=\\"hidden\\" name=\\"newsig\\" value=\\"\\" />
<input type=\\"hidden\\" name=\\"delsig\\" value=\\"\\" />
<!-- Responses text -->
$sig_text
<!-- Responses title -->
$sig_title

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Your auto responder</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Automatically respond to all:</b></span>
	<br />
	<span class=\\"smallfont\\">If this is turned on, the default response will be be sent to anyone who sends you mail.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"autorespond\\" value=\\"1\\" id=\\"autorespondon\\" $autorespondon /> <label for=\\"autorespondon\\">Yes</label><br /><input type=\\"radio\\" name=\\"autorespond\\" value=\\"0\\" id=\\"autorespondoff\\" $autorespondoff /> <label for=\\"autorespondoff\\">No</label></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\" colspan=\\"2\\" width=\\"100%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Responses Editor:</b><br />
	To edit a response, select it from the list below and edit it in large box.<br />
	To rename a response, select it then click the Rename button below and enter the new name.<br />
	To mark your default response, select it from the list and click the Make Default button below.<br />
	To create a new response, click the Create New button below and enter the name of the new response.".(($totalresponses >= $hiveuser[\'maxresponses\'] ) ? ("<br />(<b>Note</b>: You may only have up to $hiveuser[maxresponses] responses. You won\'t be able to create new responses until you delete at least some of your current responses.)") : (\'\'))."<br />
	To delete a response, select it from the list and click the Delete button below. <b>Note</b>: You cannot remove your default response, unless it is the only response you have.<br />
	<br />
	Please remember to click the Update Responses button at the bottom of this page, or else any changes that you make here will have no effect!</span><br /><br />
	<table>
		<tr>
			<td valign=\\"top\\"><select name=\\"sigs\\" size=\\"9\\" onChange=\\"updateSigDisplay(this.form);\\">
					$sig_options
				</select></td>
			<td valign=\\"top\\"><textarea name=\\"sigedit\\" cols=\\"70\\" rows=\\"8\\">(select a response to edit from the list)</textarea></td>
		</tr>
		<tr>
			<td colspan=\\"2\\">
				<input type=\\"button\\" name=\\"rename\\" class=\\"bginput\\" disabled=\\"disabled\\" value=\\"Rename\\" onClick=\\"renameSig(this.form, this.form.sigs.options[this.form.sigs.selectedIndex].text);\\" /> <input type=\\"button\\" name=\\"makedef\\" class=\\"bginput\\" disabled=\\"disabled\\" value=\\"Make Default\\" onClick=\\"updateDefaultSig(this.form);\\" /> <input type=\\"submit\\" name=\\"createnew\\" class=\\"bginput\\" value=\\"Create New\\" ".(($totalresponses >= $hiveuser[\'maxresponses\'] ) ? ("disabled=\\"disabled\\"") : (\'\'))." onClick=\\"return createNewSig(this.form);\\" /> <input type=\\"submit\\" name=\\"deletesig\\" disabled=\\"disabled\\" class=\\"bginput\\" value=\\"Delete\\" onClick=\\"return deleteSig(this.form);\\" />
			</td>
		</tr>
	</table></td>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Update Responses\\" />
		<input type=\\"reset\\" class=\\"bginput\\" name=\\"reset\\" value=\\"Reset Fields\\" />
	</td>
</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
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
<input type="hidden" name="cmd" value="update" />

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
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Display Reply-To field when composing:</b></span>
	<br />
	<span class="smallfont">If you\'d like to change the Reply-To address for individual messages, turn this on.</span></td>
	<td class="highRightCell" width="40%"><span class="normalfont"><input type="radio" name="composereplyto" value="1" id="composereplytoon" $composereplytoon /> <label for="composereplytoon">Yes</label><br /><input type="radio" name="composereplyto" value="0" id="composereplytooff" $composereplytooff /> <label for="composereplytooff">No</label></span></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Request read receipt:</b></span>
	<br />
	<span class="smallfont">Always request a read receipt for all outgoing messages.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="requestread" value="1" id="requestreadon" $requestreadon /> <label for="requestreadon">Yes</label><br /><input type="radio" name="requestread" value="0" id="requestreadoff" $requestreadoff /> <label for="requestreadoff">No</label></span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Save copy of sent messages:</b></span>
	<br />
	<span class="smallfont">Keep a copy of messages you send in the Sent Items folder.</span></td>
	<td class="highRightCell" width="40%"><span class="normalfont"><input type="radio" name="savecopy" value="1" id="savecopyon" $savecopyon /> <label for="savecopyon">Yes</label><br /><input type="radio" name="savecopy" value="0" id="savecopyoff" $savecopyoff /> <label for="savecopyoff">No</label></span></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Folder to return to:</b></span>
	<br />
	<span class="smallfont">Choose if you\'d like to be taken to your Inbox or Sent Items folders after sending a message.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="returnsent" value="0" id="returnsentoff" $returnsentoff /> <label for="returnsentoff">Inbox</label><br /><input type="radio" name="returnsent" value="1" id="returnsenton" $returnsenton /> <label for="returnsenton">Sent Items</label></span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Add recipients to address book:</b></span>
	<br />
	<span class="smallfont">Automatically add recipients of outgoing messages to your address book.</span></td>
	<td class="highRightCell" width="40%"><span class="normalfont"><input type="radio" name="addrecips" value="1" id="addrecipson" $addrecipson /> <label for="addrecipson">Yes</label><br /><input type="radio" name="addrecips" value="0" id="addrecipsoff" $addrecipsoff /><label for="addrecipsoff">No</label></span></td>
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
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />

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
<!-- +++++++++++++++++++++++++++++++++++++ -->") : (\'\'))."
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
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Display Reply-To field when composing:</b></span>
	<br />
	<span class=\\"smallfont\\">If you\'d like to change the Reply-To address for individual messages, turn this on.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"composereplyto\\" value=\\"1\\" id=\\"composereplytoon\\" $composereplytoon /> <label for=\\"composereplytoon\\">Yes</label><br /><input type=\\"radio\\" name=\\"composereplyto\\" value=\\"0\\" id=\\"composereplytooff\\" $composereplytooff /> <label for=\\"composereplytooff\\">No</label></span></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Request read receipt:</b></span>
	<br />
	<span class=\\"smallfont\\">Always request a read receipt for all outgoing messages.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"requestread\\" value=\\"1\\" id=\\"requestreadon\\" $requestreadon /> <label for=\\"requestreadon\\">Yes</label><br /><input type=\\"radio\\" name=\\"requestread\\" value=\\"0\\" id=\\"requestreadoff\\" $requestreadoff /> <label for=\\"requestreadoff\\">No</label></span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Save copy of sent messages:</b></span>
	<br />
	<span class=\\"smallfont\\">Keep a copy of messages you send in the Sent Items folder.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"savecopy\\" value=\\"1\\" id=\\"savecopyon\\" $savecopyon /> <label for=\\"savecopyon\\">Yes</label><br /><input type=\\"radio\\" name=\\"savecopy\\" value=\\"0\\" id=\\"savecopyoff\\" $savecopyoff /> <label for=\\"savecopyoff\\">No</label></span></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Folder to return to:</b></span>
	<br />
	<span class=\\"smallfont\\">Choose if you\'d like to be taken to your Inbox or Sent Items folders after sending a message.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"returnsent\\" value=\\"0\\" id=\\"returnsentoff\\" $returnsentoff /> <label for=\\"returnsentoff\\">Inbox</label><br /><input type=\\"radio\\" name=\\"returnsent\\" value=\\"1\\" id=\\"returnsenton\\" $returnsenton /> <label for=\\"returnsenton\\">Sent Items</label></span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Add recipients to address book:</b></span>
	<br />
	<span class=\\"smallfont\\">Automatically add recipients of outgoing messages to your address book.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"addrecips\\" value=\\"1\\" id=\\"addrecipson\\" $addrecipson /> <label for=\\"addrecipson\\">Yes</label><br /><input type=\\"radio\\" name=\\"addrecips\\" value=\\"0\\" id=\\"addrecipsoff\\" $addrecipsoff /><label for=\\"addrecipsoff\\">No</label></span></td>
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
  'options_folderview' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Folder View Options</title>
$css
<script type="text/javascript" src="misc/columns.js"></script>
</head>
<body>
$header

<form action="options.folderview.php" method="post" name="columnsform" onSubmit="extractList(this);">
<input type="hidden" name="cmd" value="update" />
<input type="hidden" name="finalusing" value="" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Folder View Options</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Show message preview pane:</b></span>
	<br />
	<span class="smallfont">Use the preview pane to quickly read messages without openning them or reloading the page.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="preview" value="top" id="previewtop" $previewtop /> <label for="previewtop">At the top</label><br /><input type="radio" name="preview" value="bottom" id="previewbottom" $previewbottom /> <label for="previewbottom">At the bottom</label><br /><input type="radio" name="preview" value="none" id="previewnone" $previewnone /> <label for="previewnone">Don\'t show</label></span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Enable background highlighting:</b></span>
	<br />
	<span class="smallfont">If this turned on selected messages will have a different background color.<br />If you are experiencing performance problems try disabling this option.</span></td>
	<td class="highRightCell" width="40%"><span class="normalfont"><input type="radio" name="usebghigh" value="1" id="usebghighon" $usebghighon /> <label for="usebghighon">Yes</label><br /><input type="radio" name="usebghigh" value="0" id="usebghighoff" $usebghighoff /> <label for="usebghighoff">No</label></span></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Show folder list on left:</b></span>
	<br />
	<span class="smallfont">Use the folders tab to quickly navigate through your folders.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="showfoldertab" value="1" id="showfoldertabon" $showfoldertabon /> <label for="showfoldertabon">Yes</label><br /><input type="radio" name="showfoldertab" value="0" id="showfoldertaboff" $showfoldertaboff /> <label for="showfoldertaboff">No</label></span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Display statistics box:</b></span>
	<br />
	<span class="smallfont">This table lets you know how many unread messages you have, and where, as well your storage usage.<br />Note: When you reach $minpercentforgauge% the space gauge will be displayed even if this option is turned off.</span></td>
	<td class="highRightCell" width="40%"><span class="normalfont"><input type="radio" name="showtopbox" value="1" id="showtopboxon" $showtopboxon /> <label for="showtopboxon">Yes</label><br /><input type="radio" name="showtopbox" value="0" id="showtopboxoff" $showtopboxoff /> <label for="showtopboxoff">No</label></span></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Page refresh rate (in seconds):</b></span>
	<br />
	<span class="smallfont">If not set to 0, the page will reload itself according to this setting.</span></td>
	<td class="normalRightCell" width="40%"><input type="text" class="bginput" name="autorefresh" value="$hiveuser[autorefresh]" size="4" /></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Messages per page:</b></span>
	<br />
	<span class="smallfont">The number of messages to show per page.<br />You cannot set this to a value higher than $maxperpage.<br />It is not advisable to set this number too high, for performance reasons.</span></td>
	<td class="highRightCell" width="40%"><input type="text" class="bginput" name="perpage" value="$hiveuser[perpage]" size="4" /></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>New message to sender:</b></span>
	<br />
	<span class="smallfont">Clicking a sender name creates a new message to the sender.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="senderlink" value="1" id="senderlinkon" $senderlinkon /> <label for="senderlinkon">Yes</label><br /><input type="radio" name="senderlink" value="0" id="senderlinkoff" $senderlinkoff /> <label for="senderlinkoff">No</label></span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Message columns:</b></span>
	<br />
	<span class="smallfont">These are the columns that show up when viewing the list of messages.<br />At least one column must be selected.</span></td>
	<td class="highRightCell" width="40%">
		<table cellpadding="5">
			<tr>
				<td>
					<select name="avail" style="width: 100px;" multiple="multiple" size="6" onChange="updateDisabled(this.form);">
					$avail
					</select>
				</td>
				<td valign="middle">
					<input type="button" style="width: 75px;" value="Add" onClick="addCol(this.form);" class="bginput" name="add" disabled="disabled" /><br />
					<input type="button" style="width: 75px;" value="Remove" onClick="delCol(this.form);" class="bginput" name="del" disabled="disabled" />
				</td>
				<td>
					<select name="using[]" id="using" style="width: 100px;" multiple="multiple" size="6" onChange="updateDisabled(this.form);">
					$using
					</select>
				</td>
				<td valign="middle">
					<input type="button" style="width: 85px;" value="Move Up" onClick="goUp(this.form);" class="bginput" name="up" disabled="disabled" /><br />
					<input type="button" style="width: 85px;" value="Move Down" onClick="goDown(this.form);" class="bginput" name="down" disabled="disabled" />
				</td>
			</tr>
		</table>
	</td>
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
<head><title>$appname: Folder View Options</title>
$GLOBALS[css]
<script type=\\"text/javascript\\" src=\\"misc/columns.js\\"></script>
</head>
<body>
$GLOBALS[header]

<form action=\\"options.folderview.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"columnsform\\" onSubmit=\\"extractList(this);\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />
<input type=\\"hidden\\" name=\\"finalusing\\" value=\\"\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Folder View Options</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Show message preview pane:</b></span>
	<br />
	<span class=\\"smallfont\\">Use the preview pane to quickly read messages without openning them or reloading the page.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"preview\\" value=\\"top\\" id=\\"previewtop\\" $previewtop /> <label for=\\"previewtop\\">At the top</label><br /><input type=\\"radio\\" name=\\"preview\\" value=\\"bottom\\" id=\\"previewbottom\\" $previewbottom /> <label for=\\"previewbottom\\">At the bottom</label><br /><input type=\\"radio\\" name=\\"preview\\" value=\\"none\\" id=\\"previewnone\\" $previewnone /> <label for=\\"previewnone\\">Don\'t show</label></span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Enable background highlighting:</b></span>
	<br />
	<span class=\\"smallfont\\">If this turned on selected messages will have a different background color.<br />If you are experiencing performance problems try disabling this option.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"usebghigh\\" value=\\"1\\" id=\\"usebghighon\\" $usebghighon /> <label for=\\"usebghighon\\">Yes</label><br /><input type=\\"radio\\" name=\\"usebghigh\\" value=\\"0\\" id=\\"usebghighoff\\" $usebghighoff /> <label for=\\"usebghighoff\\">No</label></span></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Show folder list on left:</b></span>
	<br />
	<span class=\\"smallfont\\">Use the folders tab to quickly navigate through your folders.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"showfoldertab\\" value=\\"1\\" id=\\"showfoldertabon\\" $showfoldertabon /> <label for=\\"showfoldertabon\\">Yes</label><br /><input type=\\"radio\\" name=\\"showfoldertab\\" value=\\"0\\" id=\\"showfoldertaboff\\" $showfoldertaboff /> <label for=\\"showfoldertaboff\\">No</label></span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Display statistics box:</b></span>
	<br />
	<span class=\\"smallfont\\">This table lets you know how many unread messages you have, and where, as well your storage usage.<br />Note: When you reach $minpercentforgauge% the space gauge will be displayed even if this option is turned off.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"showtopbox\\" value=\\"1\\" id=\\"showtopboxon\\" $showtopboxon /> <label for=\\"showtopboxon\\">Yes</label><br /><input type=\\"radio\\" name=\\"showtopbox\\" value=\\"0\\" id=\\"showtopboxoff\\" $showtopboxoff /> <label for=\\"showtopboxoff\\">No</label></span></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Page refresh rate (in seconds):</b></span>
	<br />
	<span class=\\"smallfont\\">If not set to 0, the page will reload itself according to this setting.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"autorefresh\\" value=\\"$hiveuser[autorefresh]\\" size=\\"4\\" /></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Messages per page:</b></span>
	<br />
	<span class=\\"smallfont\\">The number of messages to show per page.<br />You cannot set this to a value higher than $maxperpage.<br />It is not advisable to set this number too high, for performance reasons.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"perpage\\" value=\\"$hiveuser[perpage]\\" size=\\"4\\" /></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>New message to sender:</b></span>
	<br />
	<span class=\\"smallfont\\">Clicking a sender name creates a new message to the sender.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"senderlink\\" value=\\"1\\" id=\\"senderlinkon\\" $senderlinkon /> <label for=\\"senderlinkon\\">Yes</label><br /><input type=\\"radio\\" name=\\"senderlink\\" value=\\"0\\" id=\\"senderlinkoff\\" $senderlinkoff /> <label for=\\"senderlinkoff\\">No</label></span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Message columns:</b></span>
	<br />
	<span class=\\"smallfont\\">These are the columns that show up when viewing the list of messages.<br />At least one column must be selected.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\">
		<table cellpadding=\\"5\\">
			<tr>
				<td>
					<select name=\\"avail\\" style=\\"width: 100px;\\" multiple=\\"multiple\\" size=\\"6\\" onChange=\\"updateDisabled(this.form);\\">
					$avail
					</select>
				</td>
				<td valign=\\"middle\\">
					<input type=\\"button\\" style=\\"width: 75px;\\" value=\\"Add\\" onClick=\\"addCol(this.form);\\" class=\\"bginput\\" name=\\"add\\" disabled=\\"disabled\\" /><br />
					<input type=\\"button\\" style=\\"width: 75px;\\" value=\\"Remove\\" onClick=\\"delCol(this.form);\\" class=\\"bginput\\" name=\\"del\\" disabled=\\"disabled\\" />
				</td>
				<td>
					<select name=\\"using[]\\" id=\\"using\\" style=\\"width: 100px;\\" multiple=\\"multiple\\" size=\\"6\\" onChange=\\"updateDisabled(this.form);\\">
					$using
					</select>
				</td>
				<td valign=\\"middle\\">
					<input type=\\"button\\" style=\\"width: 85px;\\" value=\\"Move Up\\" onClick=\\"goUp(this.form);\\" class=\\"bginput\\" name=\\"up\\" disabled=\\"disabled\\" /><br />
					<input type=\\"button\\" style=\\"width: 85px;\\" value=\\"Move Down\\" onClick=\\"goDown(this.form);\\" class=\\"bginput\\" name=\\"down\\" disabled=\\"disabled\\" />
				</td>
			</tr>
		</table>
	</td>
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
<input type="hidden" name="cmd" value="update" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>General Options</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Use cookies to track information:</b></span>
	<br />
	<span class="smallfont">If your browser is incapable of receiving and storing cookies from our system, please disable this option so you can still use the service.?</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="nocookies" value="0" id="nocookiesoff" $nocookiesoff /><label for="nocookiesoff">Yes<label><br /><input type="radio" name="nocookies" value="1" id="nocookieson" $nocookieson /><label for="nocookieson">No<label></span></td>
	<!-- NOTE: Yes, this may look wrong but it is correct. DO NOT ALTER FORMAT -->
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Email domain name:</b></span>
	<br />
	<span class="smallfont">Choose the domain name you would like to have associated with this account. All outgoing messages will be sent with the chosen name.</span></td>
	<td class="highRightCell" width="40%"><select name="domain">
		$domainname_options
	</select></td>
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
	<td class="highRightCell" width="40%"><span class="normalfont"><input type="radio" name="emptybin" value="-2" id="emptybinonexit" $emptybinonexit /> <label for="emptybinonexit">Empty folder on exit</label><br /><input type="radio" name="emptybin" value="1" id="emptybinevery" $emptybinevery /> Empty folder every &nbsp;<input type="text" class="bginput" name="binevery" value="$binevery" size="3" maxlength="3" onClick="document.getElementById(\'emptybinevery\').checked = true;" />&nbsp; days<br /><input type="radio" name="emptybin" value="-1" id="emptybinno" $emptybinno /> <label for="emptybinno">Never empty folder</label></span></td>
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
		<select name="soundid">
			<%if $havecustom %><option value="$cursound[soundid]">$cursound[filename] (Personal sound)</option><%endif%>
			$soundoptions
		</select> <input type="button" value="Preview" onClick="window.open(\'user.sound.php?soundid=\'+this.form.soundid.options[this.form.soundid.selectedIndex].value);" class="bginput" /><br /><br />
		<label for="newsound">Or upload your own file:<label><br /><input type="file" class="bginput" name="newsound" onClick="this.form.soundoptionnew.checked = true;" /><input type="hidden" name="MAX_FILE_SIZE" value="$maxsoundfile" />
	</span></td>
</tr>
<%endif%>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Email notification:</b></span>
	<br />
	<span class="smallfont">A notifcation will be sent to this address every time you recieve an email.<br />Set this to nothing to disable the feature.</span></td>
	<td class="normalRightCell" width="40%"><input type="text" class="bginput" name="notifyemail" value="$hiveuser[notifyemail]" size="40" /></td>
</tr>
<%if $hiveuser[canforward] %>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Automatically forward messages:</b></span>
	<br />
	<span class="smallfont">Emails that you receive will automatically be forwarded to this address.<br />Set this to nothing to disable the feature.</span></td>
	<td class="highRightCell" width="40%"><input type="text" class="bginput" name="forward" value="$hiveuser[forward]" size="40" /></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Keep copy of messages which are automatically forwarded:</b></span>
	<br />
	<span class="smallfont">If you decide to automatically forward messages to the address speicified above, would you like to still keep them in your inbox?</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="deleteforwards" value="0" id="deleteforwardsoff" $deleteforwardsoff /><label for="deleteforwardsoff">Yes<label><br /><input type="radio" name="deleteforwards" value="1" id="deleteforwardson" $deleteforwardson /><label for="deleteforwardson">No<label></span></td>
	<!-- NOTE: Yes, this may look wrong but it is correct. DO NOT ALTER FORMAT -->
</tr>
<%endif%>
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
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>General Options</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Use cookies to track information:</b></span>
	<br />
	<span class=\\"smallfont\\">If your browser is incapable of receiving and storing cookies from our system, please disable this option so you can still use the service.?</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"nocookies\\" value=\\"0\\" id=\\"nocookiesoff\\" $nocookiesoff /><label for=\\"nocookiesoff\\">Yes<label><br /><input type=\\"radio\\" name=\\"nocookies\\" value=\\"1\\" id=\\"nocookieson\\" $nocookieson /><label for=\\"nocookieson\\">No<label></span></td>
	<!-- NOTE: Yes, this may look wrong but it is correct. DO NOT ALTER FORMAT -->
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Email domain name:</b></span>
	<br />
	<span class=\\"smallfont\\">Choose the domain name you would like to have associated with this account. All outgoing messages will be sent with the chosen name.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><select name=\\"domain\\">
		$domainname_options
	</select></td>
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
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"emptybin\\" value=\\"-2\\" id=\\"emptybinonexit\\" $emptybinonexit /> <label for=\\"emptybinonexit\\">Empty folder on exit</label><br /><input type=\\"radio\\" name=\\"emptybin\\" value=\\"1\\" id=\\"emptybinevery\\" $emptybinevery /> Empty folder every &nbsp;<input type=\\"text\\" class=\\"bginput\\" name=\\"binevery\\" value=\\"$binevery\\" size=\\"3\\" maxlength=\\"3\\" onClick=\\"document.getElementById(\'emptybinevery\').checked = true;\\" />&nbsp; days<br /><input type=\\"radio\\" name=\\"emptybin\\" value=\\"-1\\" id=\\"emptybinno\\" $emptybinno /> <label for=\\"emptybinno\\">Never empty folder</label></span></td>
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
		<select name=\\"soundid\\">
			".(($havecustom ) ? ("<option value=\\"$cursound[soundid]\\">$cursound[filename] (Personal sound)</option>") : (\'\'))."
			$soundoptions
		</select> <input type=\\"button\\" value=\\"Preview\\" onClick=\\"window.open(\'user.sound.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}soundid=\'+this.form.soundid.options[this.form.soundid.selectedIndex].value);\\" class=\\"bginput\\" /><br /><br />
		<label for=\\"newsound\\">Or upload your own file:<label><br /><input type=\\"file\\" class=\\"bginput\\" name=\\"newsound\\" onClick=\\"this.form.soundoptionnew.checked = true;\\" /><input type=\\"hidden\\" name=\\"MAX_FILE_SIZE\\" value=\\"$maxsoundfile\\" />
	</span></td>
</tr>
") : (\'\'))."
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Email notification:</b></span>
	<br />
	<span class=\\"smallfont\\">A notifcation will be sent to this address every time you recieve an email.<br />Set this to nothing to disable the feature.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"notifyemail\\" value=\\"$hiveuser[notifyemail]\\" size=\\"40\\" /></td>
</tr>
".(($hiveuser[canforward] ) ? ("
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Automatically forward messages:</b></span>
	<br />
	<span class=\\"smallfont\\">Emails that you receive will automatically be forwarded to this address.<br />Set this to nothing to disable the feature.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"forward\\" value=\\"$hiveuser[forward]\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Keep copy of messages which are automatically forwarded:</b></span>
	<br />
	<span class=\\"smallfont\\">If you decide to automatically forward messages to the address speicified above, would you like to still keep them in your inbox?</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"deleteforwards\\" value=\\"0\\" id=\\"deleteforwardsoff\\" $deleteforwardsoff /><label for=\\"deleteforwardsoff\\">Yes<label><br /><input type=\\"radio\\" name=\\"deleteforwards\\" value=\\"1\\" id=\\"deleteforwardson\\" $deleteforwardson /><label for=\\"deleteforwardson\\">No<label></span></td>
	<!-- NOTE: Yes, this may look wrong but it is correct. DO NOT ALTER FORMAT -->
</tr>
") : (\'\'))."
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
	$menus
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
	$menus
	</tr>
</table>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'options_menu_autoresponses' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="options.autoresponders.php"><span class="normalfonttablehead"><b>Auto-Responder</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Options about your automatic responder and responses editor.</span></td>
</tr>
</table>',
    'parsed_data' => '"<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"options.autoresponders.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Auto-Responder</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Options about your automatic responder and responses editor.</span></td>
</tr>
</table>"',
  ),
  'options_menu_compose' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="options.compose.php"><span class="normalfonttablehead"><b>Compose Options</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Settings that pertain to composing messages, such as using the WYSIWYG editor, including original message, and more.</span></td>
</tr>
</table>',
    'parsed_data' => '"<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"options.compose.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Compose Options</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Settings that pertain to composing messages, such as using the WYSIWYG editor, including original message, and more.</span></td>
</tr>
</table>"',
  ),
  'options_menu_folders' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="folders.list.php"><span class="normalfonttablehead"><b>Folders Management</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Create, rename, delete or empty your mail folders.</span></td>
</tr>
</table>',
    'parsed_data' => '"<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"folders.list.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Folders Management</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Create, rename, delete or empty your mail folders.</span></td>
</tr>
</table>"',
  ),
  'options_menu_folderview' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="options.folderview.php"><span class="normalfonttablehead"><b>Folder View Options</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Settings that pertain to folder viewing, such as using the preview pane, selecting columns to show and more.</span></td>
</tr>
</table>',
    'parsed_data' => '"<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"options.folderview.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Folder View Options</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Settings that pertain to folder viewing, such as using the preview pane, selecting columns to show and more.</span></td>
</tr>
</table>"',
  ),
  'options_menu_general' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="options.general.php"><span class="normalfonttablehead"><b>General Options</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Miscellaneous settings, such as new mail sound, changing skins, and more.</span></td>
</tr>
</table>',
    'parsed_data' => '"<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"options.general.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>General Options</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Miscellaneous settings, such as new mail sound, changing skins, and more.</span></td>
</tr>
</table>"',
  ),
  'options_menu_password' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="options.password.php"><span class="normalfonttablehead"><b>Password and Security</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Change your account password or your secret question and answer.</span></td>
</tr>
</table>',
    'parsed_data' => '"<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"options.password.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Password and Security</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Change your account password or your secret question and answer.</span></td>
</tr>
</table>"',
  ),
  'options_menu_personal' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="options.personal.php"><span class="normalfonttablehead"><b>Personal Information</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Edit personal information such as your name, location and birthday.</span></td>
</tr>
</table>',
    'parsed_data' => '"<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"options.personal.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Personal Information</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Edit personal information such as your name, location and birthday.</span></td>
</tr>
</table>"',
  ),
  'options_menu_pop' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="pop.list.php"><span class="normalfonttablehead"><b>POP Accounts</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Have emails from other addresses delivered directly to $appname.</span></td>
</tr>
</table>',
    'parsed_data' => '"<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"pop.list.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>POP Accounts</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Have emails from other addresses delivered directly to $appname.</span></td>
</tr>
</table>"',
  ),
  'options_menu_read' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="options.read.php"><span class="normalfonttablehead"><b>Reading Options</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Settings that pertain to reading messages, such as showing HTML, sending read receipts and more.</span></td>
</tr>
</table>',
    'parsed_data' => '"<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"options.read.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Reading Options</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Settings that pertain to reading messages, such as showing HTML, sending read receipts and more.</span></td>
</tr>
</table>"',
  ),
  'options_menu_rules' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="rules.list.php"><span class="normalfonttablehead"><b>Message Rules and Spam Filtering</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Modify or add new message filters to avoid junk mail and edit the blocked and safe senders lists.</span></td>
</tr>
</table>',
    'parsed_data' => '"<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"rules.list.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Message Rules and Spam Filtering</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Modify or add new message filters to avoid junk mail and edit the blocked and safe senders lists.</span></td>
</tr>
</table>"',
  ),
  'options_menu_signature' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="options.signature.php"><span class="normalfonttablehead"><b>Signatures</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Options about your signature and the signature editor.</span></td>
</tr>
</table>',
    'parsed_data' => '"<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"options.signature.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Signatures</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Options about your signature and the signature editor.</span></td>
</tr>
</table>"',
  ),
  'options_password' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Password and Security</title>
$css
</head>
<body>
$header

<form action="options.password.php" method="post" name="form">
<input type="hidden" name="cmd" value="update" />
<input type="hidden" name="currentpass" value="$currentpass" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="650">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Password</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>New password:</b></span></td>
	<td class="normalRightCell" width="40%"><input type="password" class="bginput" name="password" size="40" /></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Retype new password:</b></span>
	<br />
	<span class="smallfont">Repeat the password to verify it\'s correct.</span></td>
	<td class="highRightCell" width="40%"><input type="password" class="bginput" name="password_repeat" size="40" /></td>
</tr>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="650">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="pass" value="Update Password" onClick="if (form.password.value != form.password_repeat.value) { alert(\'The new passwords do not match. Please retype them and submit the form again.\'); return false; } else if (form.password.value.length == 0) { alert(\'Your password cannot be empty.\'); return false; } else { return true; }" />
	</td>
</tr>
</table>

</form>

<!-- +++++++++++++++++++++++++++++++++++++ --><br />

<form action="options.password.php" method="post" name="form">
<input type="hidden" name="cmd" value="update" />
<input type="hidden" name="currentpass" value="$currentpass" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="650">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Secret Question and Answer</b></span></th>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Secret question:</b></span><br /><span class="smallfont">If you ever forget your password, you will be asked to answer this question in order to get a new one.</span></td>
	<td class="highRightCell" width="40%"><input type="text" class="bginput" name="question" value="$hiveuser[question]" size="40" /><br /><br />
		<select name="question_options" style="width: 100%;" onChange="if (this.options[this.selectedIndex].value != \'-1\') form.question.value = this.options[this.selectedIndex].text;">
			<option value="-1">(Or choose a question from below)</option>
			<option>Your mother\'s Maiden name?</option>
			<option>Your pet\'s name?</option>
			<option>City of birth?</option>
			<option>Last 4 digits of social security number?</option>
		</select>
	</td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Secret answer:</b></span></td>
	<td class="normalRightCell" width="40%"><input type="password" class="bginput" name="answer" size="40" /></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Retype secret answer:</b></span><br /><span class="smallfont">Repeat the secret answer to verify it\'s correct.</span></td>
	<td class="highRightCell" width="40%"><input type="password" class="bginput" name="answer_repeat" size="40" /></td>
</tr>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="650">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="ques" value="Update Secret Question and Answer" onClick="if (form.answer.value != form.answer_repeat.value) { alert(\'The new answers do not match. Please retype them and submit the form again.\'); return false; } else if (form.answer.value.length == 0) { alert(\'Your secret answer cannot be empty.\'); return false; } else if (form.question.value.length == 0) { alert(\'Your secret question cannot be empty.\'); return false; } else { return true; }" />
	</td>
</tr>
</table>

</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Password and Security</title>
$GLOBALS[css]
</head>
<body>
$GLOBALS[header]

<form action=\\"options.password.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />
<input type=\\"hidden\\" name=\\"currentpass\\" value=\\"$currentpass\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"650\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Password</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>New password:</b></span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"password\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Retype new password:</b></span>
	<br />
	<span class=\\"smallfont\\">Repeat the password to verify it\'s correct.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"password_repeat\\" size=\\"40\\" /></td>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"650\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"pass\\" value=\\"Update Password\\" onClick=\\"if (form.password.value != form.password_repeat.value) { alert(\'The new passwords do not match. Please retype them and submit the form again.\'); return false; } else if (form.password.value.length == 0) { alert(\'Your password cannot be empty.\'); return false; } else { return true; }\\" />
	</td>
</tr>
</table>

</form>

<!-- +++++++++++++++++++++++++++++++++++++ --><br />

<form action=\\"options.password.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />
<input type=\\"hidden\\" name=\\"currentpass\\" value=\\"$currentpass\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"650\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Secret Question and Answer</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secret question:</b></span><br /><span class=\\"smallfont\\">If you ever forget your password, you will be asked to answer this question in order to get a new one.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"question\\" value=\\"$hiveuser[question]\\" size=\\"40\\" /><br /><br />
		<select name=\\"question_options\\" style=\\"width: 100%;\\" onChange=\\"if (this.options[this.selectedIndex].value != \'-1\') form.question.value = this.options[this.selectedIndex].text;\\">
			<option value=\\"-1\\">(Or choose a question from below)</option>
			<option>Your mother\'s Maiden name?</option>
			<option>Your pet\'s name?</option>
			<option>City of birth?</option>
			<option>Last 4 digits of social security number?</option>
		</select>
	</td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secret answer:</b></span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"answer\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Retype secret answer:</b></span><br /><span class=\\"smallfont\\">Repeat the secret answer to verify it\'s correct.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"answer_repeat\\" size=\\"40\\" /></td>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"650\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"ques\\" value=\\"Update Secret Question and Answer\\" onClick=\\"if (form.answer.value != form.answer_repeat.value) { alert(\'The new answers do not match. Please retype them and submit the form again.\'); return false; } else if (form.answer.value.length == 0) { alert(\'Your secret answer cannot be empty.\'); return false; } else if (form.question.value.length == 0) { alert(\'Your secret question cannot be empty.\'); return false; } else { return true; }\\" />
	</td>
</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'options_password_enterpass' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Password and Security</title>
$css
</head>
<body>
$header

<form action="options.password.php" method="post" name="form">
<input type="hidden" name="cmd" value="change" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="500">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Verification</b></span></th>
</tr>
<tr class="highRow">
	<td class="highLeftCell" valign="top"><span class="normalfont"><b>Current password:</b></span><br /><span class="smallfont">Please enter your current account<br />password, for security purposes.</span></td>
	<td class="highRightCell"><span class="normalfont"><input type="password" class="bginput" name="currentpass" size="30" /></td>
</tr>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="500">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="submit" value=" Proceed " />
	</td>
</tr>
</table>

</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Password and Security</title>
$GLOBALS[css]
</head>
<body>
$GLOBALS[header]

<form action=\\"options.password.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"change\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"500\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Verification</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Current password:</b></span><br /><span class=\\"smallfont\\">Please enter your current account<br />password, for security purposes.</span></td>
	<td class=\\"highRightCell\\"><span class=\\"normalfont\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"currentpass\\" size=\\"30\\" /></td>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"500\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\" Proceed \\" />
	</td>
</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'options_personal' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Personal Information</title>
$css
</head>
<body>
$header

<form action="options.personal.php" method="post">
<input type="hidden" name="cmd" value="update" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Personal Information</b></span> <span class="smallfonttablehead">(* indicates a required field)</span></th>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Your name:</b></span> <span class="important">*</span>
	<br />
	<span class="smallfont">This name will be sent with all your outgoing emails.</span></td>
	<td class="normalRightCell" width="40%"><input type="text" class="bginput" name="realname" value="$hiveuser[realname]" size="40" /></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Secondary email address:</b></span><%if $requirealt %> <span class="important">*</span><%endif%>
	<br />
	<span class="smallfont">This address will be used to contact you outside of this system.</span></td>
	<td class="highRightCell" width="40%"><input type="text" class="bginput" name="altemail" value="$hiveuser[altemail]" size="40" /></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Birthday:</b></span><br /><span class="smallfont">Optional.</span></td>
	<td class="normalRightCell" width="40%"><select name="month">
			<option value="0" $monthsel[0]>Month</option>
			<option value="1" $monthsel[1]>January</option>
			<option value="2" $monthsel[2]>February</option>
			<option value="3" $monthsel[3]>March</option>
			<option value="4" $monthsel[4]>April</option>
			<option value="5" $monthsel[5]>May</option>
			<option value="6" $monthsel[6]>June</option>
			<option value="7" $monthsel[7]>July</option>
			<option value="8" $monthsel[8]>August</option>
			<option value="9" $monthsel[9]>September</option>
			<option value="10" $monthsel[10]>October</option>
			<option value="11" $monthsel[11]>November</option>
			<option value="12" $monthsel[12]>December</option>
		</select>
		<select name="day">
			<option value="0" $daysel[0]>Day</option>
			<option value="1" $daysel[1]>1</option>
			<option value="2" $daysel[2]>2</option>
			<option value="3" $daysel[3]>3</option>
			<option value="4" $daysel[4]>4</option>
			<option value="5" $daysel[5]>5</option>
			<option value="6" $daysel[6]>6</option>
			<option value="7" $daysel[7]>7</option>
			<option value="8" $daysel[8]>8</option>
			<option value="9" $daysel[9]>9</option>
			<option value="10" $daysel[10]>10</option>
			<option value="11" $daysel[11]>11</option>
			<option value="12" $daysel[12]>12</option>
			<option value="13" $daysel[13]>13</option>
			<option value="14" $daysel[14]>14</option>
			<option value="15" $daysel[15]>15</option>
			<option value="16" $daysel[16]>16</option>
			<option value="17" $daysel[17]>17</option>
			<option value="18" $daysel[18]>18</option>
			<option value="19" $daysel[19]>19</option>
			<option value="20" $daysel[20]>20</option>
			<option value="21" $daysel[21]>21</option>
			<option value="22" $daysel[22]>22</option>
			<option value="23" $daysel[23]>23</option>
			<option value="24" $daysel[24]>24</option>
			<option value="25" $daysel[25]>25</option>
			<option value="26" $daysel[26]>26</option>
			<option value="27" $daysel[27]>27</option>
			<option value="28" $daysel[28]>28</option>
			<option value="29" $daysel[29]>29</option>
			<option value="30" $daysel[30]>30</option>
			<option value="31" $daysel[31]>31</option>
		</select>
		<input type="text" class="bginput" name="year" value="$hiveuser[year]" size="4" maxlength="4"></td>
</tr>
$custom_fields
<!-- +++++++++++++++++++++++++++++++++++++ -->
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Time and Location</b></span></th>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Time zone:</b></span><br /><span class="smallfont">Please select the correct time zone from the list.</span></td>
	<td class="highRightCell" width="40%">$timezone</td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Auto-detect Daylight Saving Time:</b></span><br /><span class="smallfont">If this is enabled, the system will automatically try to adjust the<br />time offset if Daylight Saving Time (DST) is in effect.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="fixdst" value="1" id="fixdston" $fixdston /> <label for="fixdston">Yes</label><br /><input type="radio" name="fixdst" value="0" id="fixdstoff" $fixdstoff /> <label for="fixdstoff">No</label></span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Country:</b></span><br /><span class="smallfont">Optional.</span></td>
	<td class="highRightCell" width="40%"><select name="country" onChange="if (this.options[this.selectedIndex].value != \'us\') this.form.state.selectedIndex = 0;">
		$countries
	</select></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>State:</b></span><br /><span class="smallfont">Optional.</span></td>
	<td class="normalRightCell" width="40%"><select name="state">
		$states
	</select></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Zip code:</b></span><br /><span class="smallfont">Optional.</span></td>
	<td class="highRightCell" width="40%"><input type="text" class="bginput" name="zip" value="$hiveuser[zip]" size="7" maxlength="7" /></td>
</tr>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="submit" value="Update Information" />
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
<head><title>$appname: Personal Information</title>
$GLOBALS[css]
</head>
<body>
$GLOBALS[header]

<form action=\\"options.personal.php{$GLOBALS[session_url]}\\" method=\\"post\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Personal Information</b></span> <span class=\\"smallfonttablehead\\">(* indicates a required field)</span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Your name:</b></span> <span class=\\"important\\">*</span>
	<br />
	<span class=\\"smallfont\\">This name will be sent with all your outgoing emails.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"realname\\" value=\\"$hiveuser[realname]\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secondary email address:</b></span>".(($requirealt ) ? (" <span class=\\"important\\">*</span>") : (\'\'))."
	<br />
	<span class=\\"smallfont\\">This address will be used to contact you outside of this system.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"altemail\\" value=\\"$hiveuser[altemail]\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Birthday:</b></span><br /><span class=\\"smallfont\\">Optional.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><select name=\\"month\\">
			<option value=\\"0\\" $monthsel[0]>Month</option>
			<option value=\\"1\\" $monthsel[1]>January</option>
			<option value=\\"2\\" $monthsel[2]>February</option>
			<option value=\\"3\\" $monthsel[3]>March</option>
			<option value=\\"4\\" $monthsel[4]>April</option>
			<option value=\\"5\\" $monthsel[5]>May</option>
			<option value=\\"6\\" $monthsel[6]>June</option>
			<option value=\\"7\\" $monthsel[7]>July</option>
			<option value=\\"8\\" $monthsel[8]>August</option>
			<option value=\\"9\\" $monthsel[9]>September</option>
			<option value=\\"10\\" $monthsel[10]>October</option>
			<option value=\\"11\\" $monthsel[11]>November</option>
			<option value=\\"12\\" $monthsel[12]>December</option>
		</select>
		<select name=\\"day\\">
			<option value=\\"0\\" $daysel[0]>Day</option>
			<option value=\\"1\\" $daysel[1]>1</option>
			<option value=\\"2\\" $daysel[2]>2</option>
			<option value=\\"3\\" $daysel[3]>3</option>
			<option value=\\"4\\" $daysel[4]>4</option>
			<option value=\\"5\\" $daysel[5]>5</option>
			<option value=\\"6\\" $daysel[6]>6</option>
			<option value=\\"7\\" $daysel[7]>7</option>
			<option value=\\"8\\" $daysel[8]>8</option>
			<option value=\\"9\\" $daysel[9]>9</option>
			<option value=\\"10\\" $daysel[10]>10</option>
			<option value=\\"11\\" $daysel[11]>11</option>
			<option value=\\"12\\" $daysel[12]>12</option>
			<option value=\\"13\\" $daysel[13]>13</option>
			<option value=\\"14\\" $daysel[14]>14</option>
			<option value=\\"15\\" $daysel[15]>15</option>
			<option value=\\"16\\" $daysel[16]>16</option>
			<option value=\\"17\\" $daysel[17]>17</option>
			<option value=\\"18\\" $daysel[18]>18</option>
			<option value=\\"19\\" $daysel[19]>19</option>
			<option value=\\"20\\" $daysel[20]>20</option>
			<option value=\\"21\\" $daysel[21]>21</option>
			<option value=\\"22\\" $daysel[22]>22</option>
			<option value=\\"23\\" $daysel[23]>23</option>
			<option value=\\"24\\" $daysel[24]>24</option>
			<option value=\\"25\\" $daysel[25]>25</option>
			<option value=\\"26\\" $daysel[26]>26</option>
			<option value=\\"27\\" $daysel[27]>27</option>
			<option value=\\"28\\" $daysel[28]>28</option>
			<option value=\\"29\\" $daysel[29]>29</option>
			<option value=\\"30\\" $daysel[30]>30</option>
			<option value=\\"31\\" $daysel[31]>31</option>
		</select>
		<input type=\\"text\\" class=\\"bginput\\" name=\\"year\\" value=\\"$hiveuser[year]\\" size=\\"4\\" maxlength=\\"4\\"></td>
</tr>
$custom_fields
<!-- +++++++++++++++++++++++++++++++++++++ -->
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Time and Location</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Time zone:</b></span><br /><span class=\\"smallfont\\">Please select the correct time zone from the list.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\">$timezone</td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Auto-detect Daylight Saving Time:</b></span><br /><span class=\\"smallfont\\">If this is enabled, the system will automatically try to adjust the<br />time offset if Daylight Saving Time (DST) is in effect.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"fixdst\\" value=\\"1\\" id=\\"fixdston\\" $fixdston /> <label for=\\"fixdston\\">Yes</label><br /><input type=\\"radio\\" name=\\"fixdst\\" value=\\"0\\" id=\\"fixdstoff\\" $fixdstoff /> <label for=\\"fixdstoff\\">No</label></span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Country:</b></span><br /><span class=\\"smallfont\\">Optional.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><select name=\\"country\\" onChange=\\"if (this.options[this.selectedIndex].value != \'us\') this.form.state.selectedIndex = 0;\\">
		$countries
	</select></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>State:</b></span><br /><span class=\\"smallfont\\">Optional.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><select name=\\"state\\">
		$states
	</select></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Zip code:</b></span><br /><span class=\\"smallfont\\">Optional.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"zip\\" value=\\"$hiveuser[zip]\\" size=\\"7\\" maxlength=\\"7\\" /></td>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Update Information\\" />
		<input type=\\"reset\\" class=\\"bginput\\" name=\\"reset\\" value=\\"Reset Fields\\" />
	</td>
</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'options_personal_field' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<tr class="$field[class]Row">
	<td class="$field[class]LeftCell" width="60%" valign="top"><span class="normalfont"><b>$field[title]:</b></span><%if $field[required] and !$dontshowreq %> <span class="important">*</span><%endif%><br /><span class="smallfont">$field[description]</span></td>
	<td class="$field[class]RightCell" width="40%">$field_html</td>
</tr>
',
    'parsed_data' => '"<tr class=\\"$field[class]Row\\">
	<td class=\\"$field[class]LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>$field[title]:</b></span>".(($field[required] and !$dontshowreq ) ? (" <span class=\\"important\\">*</span>") : (\'\'))."<br /><span class=\\"smallfont\\">$field[description]</span></td>
	<td class=\\"$field[class]RightCell\\" width=\\"40%\\">$field_html</td>
</tr>
"',
  ),
  'options_personal_fields_checkbox' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$options
<input type="hidden" name="totals_$field[fieldid]" value="$total" />',
    'parsed_data' => '"$options
<input type=\\"hidden\\" name=\\"totals_$field[fieldid]\\" value=\\"$total\\" />"',
  ),
  'options_personal_fields_checkbox_option' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<input type="checkbox" value="$choiceid" id="fields_$field[fieldid]_$choiceid" name="fields[$field[fieldid]][]" $checked onClick="if (this.checked && this.form.totals_$field[fieldid].value >= $field[max] && $field[max] > 0) { alert(\'You may only select up to $field[max] option(s).\\nPlease deselect another option before selecting this one.\'); return false; } if (!this.checked && this.form.totals_$field[fieldid].value <= $field[min] && $field[min] > 0) { alert(\'You must select at least $field[min] option(s).\\nPlease select another option before deselecting this one.\'); return false; } if (this.checked) { this.form.totals_$field[fieldid].value++; } else { this.form.totals_$field[fieldid].value--; } " /> <label for="fields_$field[fieldid]_$choiceid">$choiceinfo[name]</label>
',
    'parsed_data' => '"<input type=\\"checkbox\\" value=\\"$choiceid\\" id=\\"fields_$field[fieldid]_$choiceid\\" name=\\"fields[$field[fieldid]][]\\" $checked onClick=\\"if (this.checked && this.form.totals_$field[fieldid].value >= $field[max] && $field[max] > 0) { alert(\'You may only select up to $field[max] option(s).\\\\nPlease deselect another option before selecting this one.\'); return false; } if (!this.checked && this.form.totals_$field[fieldid].value <= $field[min] && $field[min] > 0) { alert(\'You must select at least $field[min] option(s).\\\\nPlease select another option before deselecting this one.\'); return false; } if (this.checked) { this.form.totals_$field[fieldid].value++; } else { this.form.totals_$field[fieldid].value--; } \\" /> <label for=\\"fields_$field[fieldid]_$choiceid\\">$choiceinfo[name]</label>
"',
  ),
  'options_personal_fields_multiselect' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<select name="fields[$field[fieldid]][]" multiple="multiple" size="$field[height]" id="fields_$field[fieldid]" />
$options
</select>
<input type="hidden" name="totals_$field[fieldid]" value="$total" />',
    'parsed_data' => '"<select name=\\"fields[$field[fieldid]][]\\" multiple=\\"multiple\\" size=\\"$field[height]\\" id=\\"fields_$field[fieldid]\\" />
$options
</select>
<input type=\\"hidden\\" name=\\"totals_$field[fieldid]\\" value=\\"$total\\" />"',
  ),
  'options_personal_fields_radio' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$options
$linebreak
<%if $field[\'custom\'] %><br />
<input type="radio" value="-1" name="fields[$field[fieldid]]" $otherchecked id="default_radio_$field[fieldid]" onClick="this.form.fields_custom_$field[fieldid].focus();" /> <label for="default_radio_$field[fieldid]">Other: <input type="text" name="fields_custom[$field[fieldid]]" value="$customvalue" class="bginput" style="width: $field[width]px;" id="fields_custom_$field[fieldid]" onChange="if (this.value != \'\') { this.form.default_radio_$field[fieldid].checked = true; } else { this.form.fields$field[fieldid]choice$default_choice.checked = true; }" /></label>
<%else%>
<input type="hidden" name="fields_custom[$field[fieldid]]" id="fields_custom_$field[fieldid]" />
<%endif%><br />',
    'parsed_data' => '"$options
$linebreak
".(($field[\'custom\'] ) ? ("<br />
<input type=\\"radio\\" value=\\"-1\\" name=\\"fields[$field[fieldid]]\\" $otherchecked id=\\"default_radio_$field[fieldid]\\" onClick=\\"this.form.fields_custom_$field[fieldid].focus();\\" /> <label for=\\"default_radio_$field[fieldid]\\">Other: <input type=\\"text\\" name=\\"fields_custom[$field[fieldid]]\\" value=\\"$customvalue\\" class=\\"bginput\\" style=\\"width: $field[width]px;\\" id=\\"fields_custom_$field[fieldid]\\" onChange=\\"if (this.value != \'\') { this.form.default_radio_$field[fieldid].checked = true; } else { this.form.fields$field[fieldid]choice$default_choice.checked = true; }\\" /></label>
") : ("
<input type=\\"hidden\\" name=\\"fields_custom[$field[fieldid]]\\" id=\\"fields_custom_$field[fieldid]\\" />
"))."<br />"',
  ),
  'options_personal_fields_radio_option' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<input type="radio" value="$choiceid" id="fields$field[fieldid]choice$choiceid" name="fields[$field[fieldid]]" $checked onClick="this.form.fields_custom_$field[fieldid].value = \'\';" /> <label for="fields$field[fieldid]choice$choiceid">$choiceinfo[name]</label>
',
    'parsed_data' => '"<input type=\\"radio\\" value=\\"$choiceid\\" id=\\"fields$field[fieldid]choice$choiceid\\" name=\\"fields[$field[fieldid]]\\" $checked onClick=\\"this.form.fields_custom_$field[fieldid].value = \'\';\\" /> <label for=\\"fields$field[fieldid]choice$choiceid\\">$choiceinfo[name]</label>
"',
  ),
  'options_personal_fields_select' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<select name="fields[$field[fieldid]]" id="fields_$field[fieldid]" $onchange>
<%if !$field[\'required\'] %><option value="0">Please select...</option><%endif%>
$options
<%if $field[\'custom\'] %><option value="-1" $otherselected>Other (enter below)</option><%endif%>
</select>
<%if $field[\'custom\'] %>
<br /><br />Other: <input type="text" class="bginput" value="$customvalue" style="width: $field[width]px;" name="fields_custom[$field[fieldid]]" id="fields_custom_$field[fieldid]" onChange="this.form.fields_$field[fieldid].selectedIndex = ((this.value != \'\') ? ($select_index) : ($default_index));" />
<%else%>
<input type="hidden" name="fields_custom[$field[fieldid]]" id="fields_custom_$field[fieldid]" />
<%endif%>',
    'parsed_data' => '"<select name=\\"fields[$field[fieldid]]\\" id=\\"fields_$field[fieldid]\\" $onchange>
".((!$field[\'required\'] ) ? ("<option value=\\"0\\">Please select...</option>") : (\'\'))."
$options
".(($field[\'custom\'] ) ? ("<option value=\\"-1\\" $otherselected>Other (enter below)</option>") : (\'\'))."
</select>
".(($field[\'custom\'] ) ? ("
<br /><br />Other: <input type=\\"text\\" class=\\"bginput\\" value=\\"$customvalue\\" style=\\"width: $field[width]px;\\" name=\\"fields_custom[$field[fieldid]]\\" id=\\"fields_custom_$field[fieldid]\\" onChange=\\"this.form.fields_$field[fieldid].selectedIndex = ((this.value != \'\') ? ($select_index) : ($default_index));\\" />
") : ("
<input type=\\"hidden\\" name=\\"fields_custom[$field[fieldid]]\\" id=\\"fields_custom_$field[fieldid]\\" />
"))',
  ),
  'options_personal_fields_text' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<input type="text" name="fields[$field[fieldid]]" class="bginput" style="width: $field[width]px;" value="$field[defvalue]" $maxlength />',
    'parsed_data' => '"<input type=\\"text\\" name=\\"fields[$field[fieldid]]\\" class=\\"bginput\\" style=\\"width: $field[width]px;\\" value=\\"$field[defvalue]\\" $maxlength />"',
  ),
  'options_personal_fields_textarea' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<textarea name="fields[$field[fieldid]]" style="width: $field[width]px; height: $field[height]px;" class="bginput">$field[defvalue]</textarea>',
    'parsed_data' => '"<textarea name=\\"fields[$field[fieldid]]\\" style=\\"width: $field[width]px; height: $field[height]px;\\" class=\\"bginput\\">$field[defvalue]</textarea>"',
  ),
  'options_read' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Reading Options</title>
$css
</head>
<body>
$header

<form action="options.read.php" method="post">
<input type="hidden" name="cmd" value="update" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Reading Options</b></span></th>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Show HTML message:</b></span>
	<br />
	<span class="smallfont">Turn this on if you\'d like to see the HTML version of a message if it is available.</span></td>
	<td class="highRightCell" width="40%"><span class="normalfont"><input type="radio" name="showhtml" value="1" id="showhtmlon" $showhtmlon /> <label for="showhtmlon">Yes</label><br /><input type="radio" name="showhtml" value="0" id="showhtmloff" $showhtmloff /> <label for="showhtmloff">No</label></span></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Show advanced headers:</b></span>
	<br />
	<span class="smallfont">If enabled, complete MIME headers an email contains will be displayed when viewing the message.<br />Otherwise, only the basic (sender, recipients, subject and date) headers will be shown.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="showallheaders" value="1" id="showallheaderson" $showallheaderson /> <label for="showallheaderson">Yes</label><br /><input type="radio" name="showallheaders" value="0" id="showallheadersoff" $showallheadersoff /> <label for="showallheadersoff">No</label></span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Returning read receipts:</b></span>
	<br />
	<span class="smallfont">How should read receipt requests be treated?</span></td>
	<td class="highRightCell" width="40%"><span class="normalfont">
	<input type="radio" name="sendread" value="0" id="sendreadno" $sendreadno /> <label for="sendreadno">Never send a read receipt</label><br />
	<input type="radio" name="sendread" value="1" id="sendreadask" $sendreadask /> <label for="sendreadask">Notify me for each read receipt request</label><br />
	<input type="radio" name="sendread" value="2" id="sendreadalways" $sendreadalways /> <label for="sendreadalways">Always send a read receipt</label>
	</span></td>
</tr>
<%if getop(\'allowcid\')%>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Show inline attachments:</b></span>
	<br />
	<span class="smallfont">If enabled, inline attachments (such as embedded images) will appear in the attachments aswell as in the message body.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="showinline" value="1" id="showinlineon" $showinlineon /> <label for="showinlineon">Yes</label><br /><input type="radio" name="showinline" value="0" id="showinlineoff" $showinlineoff /> <label for="showinlineoff">No</label></span></td>
</tr>
<%endif%>
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
<head><title>$appname: Reading Options</title>
$GLOBALS[css]
</head>
<body>
$GLOBALS[header]

<form action=\\"options.read.php{$GLOBALS[session_url]}\\" method=\\"post\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Reading Options</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Show HTML message:</b></span>
	<br />
	<span class=\\"smallfont\\">Turn this on if you\'d like to see the HTML version of a message if it is available.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"showhtml\\" value=\\"1\\" id=\\"showhtmlon\\" $showhtmlon /> <label for=\\"showhtmlon\\">Yes</label><br /><input type=\\"radio\\" name=\\"showhtml\\" value=\\"0\\" id=\\"showhtmloff\\" $showhtmloff /> <label for=\\"showhtmloff\\">No</label></span></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Show advanced headers:</b></span>
	<br />
	<span class=\\"smallfont\\">If enabled, complete MIME headers an email contains will be displayed when viewing the message.<br />Otherwise, only the basic (sender, recipients, subject and date) headers will be shown.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"showallheaders\\" value=\\"1\\" id=\\"showallheaderson\\" $showallheaderson /> <label for=\\"showallheaderson\\">Yes</label><br /><input type=\\"radio\\" name=\\"showallheaders\\" value=\\"0\\" id=\\"showallheadersoff\\" $showallheadersoff /> <label for=\\"showallheadersoff\\">No</label></span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Returning read receipts:</b></span>
	<br />
	<span class=\\"smallfont\\">How should read receipt requests be treated?</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\">
	<input type=\\"radio\\" name=\\"sendread\\" value=\\"0\\" id=\\"sendreadno\\" $sendreadno /> <label for=\\"sendreadno\\">Never send a read receipt</label><br />
	<input type=\\"radio\\" name=\\"sendread\\" value=\\"1\\" id=\\"sendreadask\\" $sendreadask /> <label for=\\"sendreadask\\">Notify me for each read receipt request</label><br />
	<input type=\\"radio\\" name=\\"sendread\\" value=\\"2\\" id=\\"sendreadalways\\" $sendreadalways /> <label for=\\"sendreadalways\\">Always send a read receipt</label>
	</span></td>
</tr>
".((getop(\'allowcid\')) ? ("
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Show inline attachments:</b></span>
	<br />
	<span class=\\"smallfont\\">If enabled, inline attachments (such as embedded images) will appear in the attachments aswell as in the message body.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"showinline\\" value=\\"1\\" id=\\"showinlineon\\" $showinlineon /> <label for=\\"showinlineon\\">Yes</label><br /><input type=\\"radio\\" name=\\"showinline\\" value=\\"0\\" id=\\"showinlineoff\\" $showinlineoff /> <label for=\\"showinlineoff\\">No</label></span></td>
</tr>
") : (\'\'))."
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
  'options_signature' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Signatures</title>
$css
<script language="JavaScript" type="text/javascript">
<!--
var totalSigs = $totalSigs_real;
// -->
</script>
<script type="text/javascript" src="misc/signatures.js"></script>
</head>
<body>
$header

<form action="options.signature.php" method="post" name="sigform" onSubmit="updateSigDisplay(this);">
<input type="hidden" name="cmd" value="update" />

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
	To delete a signature, select it from the list and click the Delete button below. <b>Note</b>: You cannot remove your default signature, unless it is the only signature you have.<br />
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
<script language=\\"JavaScript\\" type=\\"text/javascript\\">
<!--
var totalSigs = $totalSigs_real;
// -->
</script>
<script type=\\"text/javascript\\" src=\\"misc/signatures.js\\"></script>
</head>
<body>
$GLOBALS[header]

<form action=\\"options.signature.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"sigform\\" onSubmit=\\"updateSigDisplay(this);\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />

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
	To create a new signature, click the Create New button below and enter the name of the new signature.".(($totalsigs >= $hiveuser[\'maxsigs\'] ) ? ("<br />(<b>Note</b>: You may only have up to $hiveuser[maxsigs] signatures. You won\'t be able to create new signatures until you delete at least some of your current signatures.)") : (\'\'))."<br />
	To delete a signature, select it from the list and click the Delete button below. <b>Note</b>: You cannot remove your default signature, unless it is the only signature you have.<br />
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
				<input type=\\"button\\" name=\\"rename\\" class=\\"bginput\\" disabled=\\"disabled\\" value=\\"Rename\\" onClick=\\"renameSig(this.form, this.form.sigs.options[this.form.sigs.selectedIndex].text);\\" /> <input type=\\"button\\" name=\\"makedef\\" class=\\"bginput\\" disabled=\\"disabled\\" value=\\"Make Default\\" onClick=\\"updateDefaultSig(this.form);\\" /> <input type=\\"submit\\" name=\\"createnew\\" class=\\"bginput\\" value=\\"Create New\\" onClick=\\"return createNewSig(this.form);\\" ".(($totalsigs >= $hiveuser[\'maxsigs\'] ) ? ("disabled=\\"disabled\\"") : (\'\'))." /> <input type=\\"submit\\" name=\\"deletesig\\" disabled=\\"disabled\\" class=\\"bginput\\" value=\\"Delete\\" onClick=\\"return deleteSig(this.form);\\" />
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
<input type="hidden" name="cmd" value="update" />

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
<input type="hidden" name="cmd" value="add" />

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
	<td class="highBothCell" valign="top" colspan="7"><span class="normalfont"><input type="checkbox" name="active[0]" value="yes" checked="checked" id="active0" /> <label for="active0">Account active.</label><br />
	Leave copy of messages on server? &nbsp;<select name="delete[0]">
		<option value="1">Delete immediately</option>
		<option value="2" selected="selected">Until I delete them from local mailbox</option>
		<option value="0">Never delete messages</option>
	</select></span></td>
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
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />

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
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"add\\" />

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
	<td class=\\"highBothCell\\" valign=\\"top\\" colspan=\\"7\\"><span class=\\"normalfont\\"><input type=\\"checkbox\\" name=\\"active[0]\\" value=\\"yes\\" checked=\\"checked\\" id=\\"active0\\" /> <label for=\\"active0\\">Account active.</label><br />
	Leave copy of messages on server? &nbsp;<select name=\\"delete[0]\\">
		<option value=\\"1\\">Delete immediately</option>
		<option value=\\"2\\" selected=\\"selected\\">Until I delete them from local mailbox</option>
		<option value=\\"0\\">Never delete messages</option>
	</select></span></td>
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
	<td class="$class[name]BothCell" colspan="7"><span class="normalfont"><input type="checkbox" name="active[$pop[popid]]" id="active$pop[popid]" value="yes" $activechecked /> <label for="active$pop[popid]">Account active.</label><br />
	Leave copy of messages on server? &nbsp;<select name="delete[$pop[popid]]">
		<option value="1" $delsel[1]>Delete immediately</option>
		<option value="2" $delsel[2]>Until I delete them from local mailbox</option>
		<option value="0" $delsel[0]>Never delete messages</option>
	</select></span></td>
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
	<td class=\\"$class[name]BothCell\\" colspan=\\"7\\"><span class=\\"normalfont\\"><input type=\\"checkbox\\" name=\\"active[$pop[popid]]\\" id=\\"active$pop[popid]\\" value=\\"yes\\" $activechecked /> <label for=\\"active$pop[popid]\\">Account active.</label><br />
	Leave copy of messages on server? &nbsp;<select name=\\"delete[$pop[popid]]\\">
		<option value=\\"1\\" $delsel[1]>Delete immediately</option>
		<option value=\\"2\\" $delsel[2]>Until I delete them from local mailbox</option>
		<option value=\\"0\\" $delsel[0]>Never delete messages</option>
	</select></span></td>
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
<input type="hidden" name="cmd" value="dostuff" />
<input type="hidden" name="messageid" value="$messageid" />
<input type="hidden" name="folderid" value="$folderid" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Message</b></span></th>
</tr>
<tr class="highRow">
	<td class="highLeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>From:</b></span></td>
	<td class="highRightCell" align="left" width="90%" valign="middle"><span class="normalfont" style="vertical-align: middle;">$mail[fromname] (<a href="compose.email.php?email=$mail[fromemailenc]">$mail[fromemail]</a>)</span>&nbsp;<a href="addressbook.add.php?cmd=quick&messageid=$messageid"><img src="$skin[images]/addbook.gif" alt="Add sender to address book" align="middle" border="0" /></a>&nbsp;&nbsp;<span class="smallfont"><%if $hiveuser[cansearch] %><a href="search.results.php?folderids[]=0&searchdate=-1&fields[]=email&query=$mail[fromemailenc]"><img src="$skin[images]/find.gif" alt="Find more messages from sender" align="middle" border="0" /></a><%endif%><%if $hiveuser[canrule] %> <a href="rules.block.php?email=$mail[fromemailenc]"><img src="$skin[images]/block.gif" alt="Block sender" align="middle" border="0" /></a><%endif%></span></td>
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
	<td class="$afterattach[second]RightCell" align="left" width="90%"><span class="normalfont"><a href="read.markas.php?messageid=$mail[messageid]&markas=$markas&back=message">mark as $markas</a> | <a href="read.source.php?messageid=$mail[messageid]">view source</a> | <a href="read.source.php?messageid=$mail[messageid]&cmd=save">save as</a> | <a href="read.printable.php?messageid=$mail[messageid]">printable version</a><%if getop(\'allowbouncing\') and !($mail[\'status\'] & MAIL_BOUNCED) %> | <a href="read.bounce.php?messageid=$mail[messageid]">bounce message</a><%endif%></span></td>
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
$movefolderjump</select>&nbsp; or &nbsp;
		<input type="submit" class="bginput" name="delete" value="Delete" onClick="changeFolderID(); if (!confirm(\'Are you sure you want to delete the selected messages?\')) return false; return true;" /></b></span></td>
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
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"dostuff\\" />
<input type=\\"hidden\\" name=\\"messageid\\" value=\\"$messageid\\" />
<input type=\\"hidden\\" name=\\"folderid\\" value=\\"$folderid\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Message</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>From:</b></span></td>
	<td class=\\"highRightCell\\" align=\\"left\\" width=\\"90%\\" valign=\\"middle\\"><span class=\\"normalfont\\" style=\\"vertical-align: middle;\\">$mail[fromname] (<a href=\\"compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}email=$mail[fromemailenc]\\">$mail[fromemail]</a>)</span>&nbsp;<a href=\\"addressbook.add.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=quick&messageid=$messageid\\"><img src=\\"{$GLOBALS[skin][images]}/addbook.gif\\" alt=\\"Add sender to address book\\" align=\\"middle\\" border=\\"0\\" /></a>&nbsp;&nbsp;<span class=\\"smallfont\\">".(($hiveuser[cansearch] ) ? ("<a href=\\"search.results.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderids[]=0&searchdate=-1&fields[]=email&query=$mail[fromemailenc]\\"><img src=\\"{$GLOBALS[skin][images]}/find.gif\\" alt=\\"Find more messages from sender\\" align=\\"middle\\" border=\\"0\\" /></a>") : (\'\')).(($hiveuser[canrule] ) ? (" <a href=\\"rules.block.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}email=$mail[fromemailenc]\\"><img src=\\"{$GLOBALS[skin][images]}/block.gif\\" alt=\\"Block sender\\" align=\\"middle\\" border=\\"0\\" /></a>") : (\'\'))."</span></td>
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
	<td class=\\"$afterattach[second]RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\"><a href=\\"read.markas.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]&markas=$markas&back=message\\">mark as $markas</a> | <a href=\\"read.source.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\\">view source</a> | <a href=\\"read.source.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]&cmd=save\\">save as</a> | <a href=\\"read.printable.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\\">printable version</a>".((getop(\'allowbouncing\') and !($mail[\'status\'] & MAIL_BOUNCED) ) ? (" | <a href=\\"read.bounce.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\\">bounce message</a>") : (\'\'))."</span></td>
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
$movefolderjump</select>&nbsp; or &nbsp;
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"delete\\" value=\\"Delete\\" onClick=\\"changeFolderID(); if (!confirm(\'Are you sure you want to delete the selected messages?\')) return false; return true;\\" /></b></span></td>
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
  'read_linkframe' => 
  array (
    'templategroupid' => '6',
    'user_data' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html>
<head><title>$appname</title>
$css
</head>
<frameset rows="50,*" border="0">
<frame name="navigate" src="read.link.php?cmd=topframe&messageid=$messageid" scrolling="no" marginheight="1" noresize="noresize" style="border-bottom: 1px solid #000000;">
<frame name="partner" src="$link" marginheight="1">
</frameset>
<noframes>
<meta http-equiv="refresh" content="1; url=$link" />
</noframes>
</html>',
    'parsed_data' => '"<!DOCTYPE html PUBLIC \\"-//W3C//DTD XHTML 1.0 Frameset//EN\\" \\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd\\">
<html>
<head><title>$appname</title>
$GLOBALS[css]
</head>
<frameset rows=\\"50,*\\" border=\\"0\\">
<frame name=\\"navigate\\" src=\\"read.link.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=topframe&messageid=$messageid\\" scrolling=\\"no\\" marginheight=\\"1\\" noresize=\\"noresize\\" style=\\"border-bottom: 1px solid #000000;\\">
<frame name=\\"partner\\" src=\\"$link\\" marginheight=\\"1\\">
</frameset>
<noframes>
<meta http-equiv=\\"refresh\\" content=\\"1; url=$link\\" />
</noframes>
</html>"',
  ),
  'read_linkframe_topframe' => 
  array (
    'templategroupid' => '6',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Outside Link</title>
$css
</head>
<body style="background-color: #C7E1F4;">
<table cellpadding="0" cellspacing="0" width="100%" style="background-color: #C7E1F4;">
	<tr style="height: 8px;">
		<td style="height: 8px;">
		</td>
	</tr>
	<tr>
		<td style="width: 100%;">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<tr>
					<td valign="top" style="padding: 5px 0px 5px 5px; border: 0px solid #9BC1E6; border-width: 1px 0px 1px 0px; width: 100%; background-color: $skin[pagebgcolor]; width: 100%;"><span class="normalfont">You are visiting a site outside of $appname. To return to the message you were previously reading, <a href="$appurl/read.email.php?messageid=$messageid" target="_parent">click here</a>.</span>
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
</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Outside Link</title>
$GLOBALS[css]
</head>
<body style=\\"background-color: #C7E1F4;\\">
<table cellpadding=\\"0\\" cellspacing=\\"0\\" width=\\"100%\\" style=\\"background-color: #C7E1F4;\\">
	<tr style=\\"height: 8px;\\">
		<td style=\\"height: 8px;\\">
		</td>
	</tr>
	<tr>
		<td style=\\"width: 100%;\\">
			<table cellpadding=\\"0\\" cellspacing=\\"0\\" style=\\"width: 100%;\\">
				<tr>
					<td valign=\\"top\\" style=\\"padding: 5px 0px 5px 5px; border: 0px solid #9BC1E6; border-width: 1px 0px 1px 0px; width: 100%; background-color: {$GLOBALS[skin][pagebgcolor]}; width: 100%;\\"><span class=\\"normalfont\\">You are visiting a site outside of $appname. To return to the message you were previously reading, <a href=\\"$appurl/read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$messageid\\" target=\\"_parent\\">click here</a>.</span>
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
</body>
</html>"',
  ),
  'redirect_addbook_quickadd' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The sender(s) has been added to your address book. You will now be returned to the message.',
    'parsed_data' => '"The sender(s) has been added to your address book. You will now be returned to the message."',
  ),
  'redirect_mailsent' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Your email was succesfully sent!<%if !$addedalladdresses%><br />
<br />
<b>Note:</b> Not all addresses could be added to the addressbook as you have reached your limt.
<%endif%>',
    'parsed_data' => '"Your email was succesfully sent!".((!$addedalladdresses) ? ("<br />
<br />
<b>Note:</b> Not all addresses could be added to the addressbook as you have reached your limt.
") : (\'\'))',
  ),
  'redirect_messagebounced' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The message was succesfully bounced to its sender, $mail[email].',
    'parsed_data' => '"The message was succesfully bounced to its sender, $mail[email]."',
  ),
  'redirect_msgblocked' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The selected messages have been blocked.',
    'parsed_data' => '"The selected messages have been blocked."',
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
<input type="hidden" name="cmd" value="lists" />
<input type="hidden" name="blocklist" value="lists" />
<input type="hidden" name="safelist" value="lists" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Blocked Senders</b></span></th>
</tr>
<tr class="highRow">
	<td class="highBothCell" valign="top" colspan="2"><span class="normalfont"><b>Blocked senders:</b></span>
	<br />
	<span class="smallfont">You may specify a list of email addresses you would like to block from your account below. Messages from blocked senders will automatically be considered spam and treated according to the action defined below for dealing with spam. You can enter full addreeses (e.g. email@example.net), or domain names only (e.g. example.net) to block all emails from the domain name.<br />Remember to click the Update Lists button below for changes to take effect.<br /><br />
	<table align="center" width="100%">
		<tr>
			<td valign="top" align="right" width="50%"><input type="text" value="" size="30" name="block" class="bginput" onFocus="this.form.addblock.disabled = false;" /></td>
			<td valign="top" align="center"><input type="button" disabled="disabled" value="Add ->" name="addblock" style="width: 70px;" class="bginput" onClick="addAddress(this.form, \'block\', \'safe\');" /><br />
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
	<span class="smallfont">If this is turned on, messages from people who are in your address book will never be blocked or checked against anti-spam measures.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="protectbook" value="1" id="protectbookon" $protectbookon /> <label for="protectbookon">Yes</label><br /><input type="radio" name="protectbook" value="0" id="protectbookoff" $protectbookoff /> <label for="protectbookoff">No</label></span></td>
</tr>
<tr class="highRow">
	<td class="highBothCell" valign="top" colspan="2"><span class="normalfont"><b>Additional safe senders:</b></span>
	<br />
	<span class="smallfont">You may specify a list of email addresses that are "safe" below. Messages from these addresses will never be blocked or checked against anti-spam measures. You can enter full addresses (e.g. email@example.net), or domain names only (e.g. example.net) to protect all emails from the domain name.<br />Remember to click the Update Lists button below for changes to take effect.<br /><br />
	<table align="center" width="100%">
		<tr>
			<td valign="top" align="right" width="50%"><input type="text" value="" size="30" name="safe" class="bginput" onFocus="this.form.addsafe.disabled = false;" /></td>
			<td valign="top" align="center"><input type="button" disabled="disabled" value="Add ->" name="addsafe" style="width: 70px;" class="bginput" onClick="addAddress(this.form, \'safe\', \'block\');" /><br />
						<br /><input type="button" disabled="disabled" value="Delete" name="deletesafe" style="width: 70px;" class="bginput" onClick="deleteAddress(this.form, \'safe\');" /></td>
			<td valign="top" align="left" width="50%"><select name="new_safes[]" id="safes" multiple="multiple" size="7" onChange="this.form.deletesafe.disabled = (this.selectedIndex == -1);">
					$safe_emails
				</select></td>
		</tr>
	</table>
	</span></td>
</tr>
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Anti-spam</b></span></th>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Your special spam key:</b></span>
	<br />
	<span class="smallfont">We allow you to choose a special, secret key that can be used by your friends so their message will not blocked. For example, if a friend of yours sends you a message with the word "free" in it, and you choose to block this kind of messages (by defining a rule below), tell him to include this special key anywhere in the subject line, and his message will not be filtered.<br />It is important that you do not set this key to a common word, so as to make sure it cannot be guessed or unintentionally found.</span></td>
	<td class="highRightCell" width="40%"><input type="text" class="bginput" name="spampass" value="$hiveuser[spampass]" size="40" /></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Suspected spam:</b></span>
	<br />
	<span class="smallfont">Action to take if mail is suspected of being unsolicited mail.</span></td>
	<td class="normalRightCell" width="40%">
		<SELECT NAME="spamaction">
			<option value="-4" $spamactions[junk]>Move to junk mail</option>
			<option value="-3" $spamactions[trash]>Move to trash can</option>
			<option value="0" $spamactions[reject]>Reject it</option>
		</SELECT>
	</td>
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
<input type="hidden" name="cmd" value="update" />

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
		<input type="submit" class="bginput" name="submit" value="Save Changes" $disablesavechanges />
		<input type="reset" class="bginput" name="reset" value="Reset Fields" $disablesavechanges />
	</td>
</tr>
</table>

</form>

<br />

<form action="rules.update.php" method="post">
<input type="hidden" name="cmd" value="add" />

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
	</select> <input type="text" class="bginput" name="condextras[0]" size="20" /><br /><input type="checkbox" name="exempt" value="yes" checked="checked" />Exempt safe senders from this rule</td>
	<td class="highRightCell"><span class="normalfont">
	<input type="checkbox" name="dowhat[0][folder]" value="1" /> <select name="folderstuff[0]">
		<option value="2">move it to</option>
		<option value="4">copy it to</option>
	</select>
	<select name="folders[0]">
		$newfolderbits
	</select><br />
<%if $numresponses>0%>
	<input type="checkbox" name="dowhat[0][respond]" value="1" /> respond <select name="responses[0]">
		$newresponsebits
	</select><br />
<%endif%>
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
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"lists\\" />
<input type=\\"hidden\\" name=\\"blocklist\\" value=\\"lists\\" />
<input type=\\"hidden\\" name=\\"safelist\\" value=\\"lists\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Blocked Senders</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\" valign=\\"top\\" colspan=\\"2\\"><span class=\\"normalfont\\"><b>Blocked senders:</b></span>
	<br />
	<span class=\\"smallfont\\">You may specify a list of email addresses you would like to block from your account below. Messages from blocked senders will automatically be considered spam and treated according to the action defined below for dealing with spam. You can enter full addreeses (e.g. email@example.net), or domain names only (e.g. example.net) to block all emails from the domain name.<br />Remember to click the Update Lists button below for changes to take effect.<br /><br />
	<table align=\\"center\\" width=\\"100%\\">
		<tr>
			<td valign=\\"top\\" align=\\"right\\" width=\\"50%\\"><input type=\\"text\\" value=\\"\\" size=\\"30\\" name=\\"block\\" class=\\"bginput\\" onFocus=\\"this.form.addblock.disabled = false;\\" /></td>
			<td valign=\\"top\\" align=\\"center\\"><input type=\\"button\\" disabled=\\"disabled\\" value=\\"Add ->\\" name=\\"addblock\\" style=\\"width: 70px;\\" class=\\"bginput\\" onClick=\\"addAddress(this.form, \'block\', \'safe\');\\" /><br />
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
	<span class=\\"smallfont\\">If this is turned on, messages from people who are in your address book will never be blocked or checked against anti-spam measures.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"protectbook\\" value=\\"1\\" id=\\"protectbookon\\" $protectbookon /> <label for=\\"protectbookon\\">Yes</label><br /><input type=\\"radio\\" name=\\"protectbook\\" value=\\"0\\" id=\\"protectbookoff\\" $protectbookoff /> <label for=\\"protectbookoff\\">No</label></span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\" valign=\\"top\\" colspan=\\"2\\"><span class=\\"normalfont\\"><b>Additional safe senders:</b></span>
	<br />
	<span class=\\"smallfont\\">You may specify a list of email addresses that are \\"safe\\" below. Messages from these addresses will never be blocked or checked against anti-spam measures. You can enter full addresses (e.g. email@example.net), or domain names only (e.g. example.net) to protect all emails from the domain name.<br />Remember to click the Update Lists button below for changes to take effect.<br /><br />
	<table align=\\"center\\" width=\\"100%\\">
		<tr>
			<td valign=\\"top\\" align=\\"right\\" width=\\"50%\\"><input type=\\"text\\" value=\\"\\" size=\\"30\\" name=\\"safe\\" class=\\"bginput\\" onFocus=\\"this.form.addsafe.disabled = false;\\" /></td>
			<td valign=\\"top\\" align=\\"center\\"><input type=\\"button\\" disabled=\\"disabled\\" value=\\"Add ->\\" name=\\"addsafe\\" style=\\"width: 70px;\\" class=\\"bginput\\" onClick=\\"addAddress(this.form, \'safe\', \'block\');\\" /><br />
						<br /><input type=\\"button\\" disabled=\\"disabled\\" value=\\"Delete\\" name=\\"deletesafe\\" style=\\"width: 70px;\\" class=\\"bginput\\" onClick=\\"deleteAddress(this.form, \'safe\');\\" /></td>
			<td valign=\\"top\\" align=\\"left\\" width=\\"50%\\"><select name=\\"new_safes[]\\" id=\\"safes\\" multiple=\\"multiple\\" size=\\"7\\" onChange=\\"this.form.deletesafe.disabled = (this.selectedIndex == -1);\\">
					$safe_emails
				</select></td>
		</tr>
	</table>
	</span></td>
</tr>
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Anti-spam</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Your special spam key:</b></span>
	<br />
	<span class=\\"smallfont\\">We allow you to choose a special, secret key that can be used by your friends so their message will not blocked. For example, if a friend of yours sends you a message with the word \\"free\\" in it, and you choose to block this kind of messages (by defining a rule below), tell him to include this special key anywhere in the subject line, and his message will not be filtered.<br />It is important that you do not set this key to a common word, so as to make sure it cannot be guessed or unintentionally found.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"spampass\\" value=\\"$hiveuser[spampass]\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Suspected spam:</b></span>
	<br />
	<span class=\\"smallfont\\">Action to take if mail is suspected of being unsolicited mail.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\">
		<SELECT NAME=\\"spamaction\\">
			<option value=\\"-4\\" $spamactions[junk]>Move to junk mail</option>
			<option value=\\"-3\\" $spamactions[trash]>Move to trash can</option>
			<option value=\\"0\\" $spamactions[reject]>Reject it</option>
		</SELECT>
	</td>
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
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />

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
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Save Changes\\" $disablesavechanges />
		<input type=\\"reset\\" class=\\"bginput\\" name=\\"reset\\" value=\\"Reset Fields\\" $disablesavechanges />
	</td>
</tr>
</table>

</form>

<br />

<form action=\\"rules.update.php{$GLOBALS[session_url]}\\" method=\\"post\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"add\\" />

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
	</select> <input type=\\"text\\" class=\\"bginput\\" name=\\"condextras[0]\\" size=\\"20\\" /><br /><input type=\\"checkbox\\" name=\\"exempt\\" value=\\"yes\\" checked=\\"checked\\" />Exempt safe senders from this rule</td>
	<td class=\\"highRightCell\\"><span class=\\"normalfont\\">
	<input type=\\"checkbox\\" name=\\"dowhat[0][folder]\\" value=\\"1\\" /> <select name=\\"folderstuff[0]\\">
		<option value=\\"2\\">move it to</option>
		<option value=\\"4\\">copy it to</option>
	</select>
	<select name=\\"folders[0]\\">
		$newfolderbits
	</select><br />
".(($numresponses>0) ? ("
	<input type=\\"checkbox\\" name=\\"dowhat[0][respond]\\" value=\\"1\\" /> respond <select name=\\"responses[0]\\">
		$newresponsebits
	</select><br />
") : (\'\'))."
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
  'rules_apply' => 
  array (
    'templategroupid' => '10',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Apply Rule</title>
$css
<script language="JavaScript" type="text/javascript">
<!--

function areYouSure() {
	sure = confirm(\'Are you sure you want to apply this rule to the selected folders?\');
	return sure;
}

//-->
</script>
</head>
<body>
$header

<form action="rules.apply.php" method="post" onSubmit="return areYouSure();">
<input type="hidden" name="cmd" value="doit" />
<input type="hidden" name="ruleid" value="$ruleid" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="650">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Apply Rule</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalBothCell" valign="top" colspan="2"><span class="normalfont"><b>$condition $dowhat.</b></span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" valign="top" width="50%"><span class="normalfont">Apply the rule to folders:</span></td>
	<td class="highRightCell" valign="top" width="50%"><span class="normalfont">
		<select name="folderids[]" multiple="multiple" size="$selectsize">
			<option value="0">All folders</option>
			<option value="-">---------------------</option>
$folderjump
		</select></span></td>
</tr>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="650">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="submit" value="Apply Rule" />
	</td>
</tr>
</table>

</form>


$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Apply Rule</title>
$GLOBALS[css]
<script language=\\"JavaScript\\" type=\\"text/javascript\\">
<!--

function areYouSure() {
	sure = confirm(\'Are you sure you want to apply this rule to the selected folders?\');
	return sure;
}

//-->
</script>
</head>
<body>
$GLOBALS[header]

<form action=\\"rules.apply.php{$GLOBALS[session_url]}\\" method=\\"post\\" onSubmit=\\"return areYouSure();\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"doit\\" />
<input type=\\"hidden\\" name=\\"ruleid\\" value=\\"$ruleid\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"650\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Apply Rule</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalBothCell\\" valign=\\"top\\" colspan=\\"2\\"><span class=\\"normalfont\\"><b>$condition $dowhat.</b></span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" valign=\\"top\\" width=\\"50%\\"><span class=\\"normalfont\\">Apply the rule to folders:</span></td>
	<td class=\\"highRightCell\\" valign=\\"top\\" width=\\"50%\\"><span class=\\"normalfont\\">
		<select name=\\"folderids[]\\" multiple=\\"multiple\\" size=\\"$selectsize\\">
			<option value=\\"0\\">All folders</option>
			<option value=\\"-\\">---------------------</option>
$folderjump
		</select></span></td>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"650\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Apply Rule\\" />
	</td>
</tr>
</table>

</form>


$GLOBALS[footer]

</body>
</html>"',
  ),
  'rules_rulebit' => 
  array (
    'templategroupid' => '10',
    'user_data' => '<tr class="$class[name]Row" valign="top">
	<td class="$class[name]LeftCell" nowrap="nowrap" width="1%" valign="middle">$moveup $movedown</td>
	<td class="$class[name]Cell"><span class="normalfont">When:</span><span class="smallfont"><br /><br />[<a href="rules.delete.php?ruleid=$rule[ruleid]" onClick="return confirm(\'Are you sure you want to delete this rule?\');">delete</a>]<br />[<a href="rules.apply.php?ruleid=$rule[ruleid]">apply</a>]</span></td>
	<td class="$class[name]Cell">
	<select name="condsubjects[$rule[ruleid]]">
		<option value="1" $condsubjects[1]>email address</option>
		<option value="2" $condsubjects[2]>message</option>
		<option value="3" $condsubjects[3]>recipient</option>
		<option value="4" $condsubjects[4]>subject</option>
	</select>
	<select name="condhows[$rule[ruleid]]">
		<option value="2" $condhows[2]>contains</option>
		<option value="3" $condhows[3]>does not contain</option>
		<option value="1" $condhows[1]>equals</option>
		<option value="4" $condhows[4]>begins with</option>
		<option value="5" $condhows[5]>ends with</option>
	</select> <input type="text" class="bginput" name="condextras[$rule[ruleid]]" value="$condextra" size="20" /><br /><input type="checkbox" name="exempt[$rule[ruleid]]" value="yes" $exemptchecked />Exempt safe senders from this rule</td>
	<td class="$class[name]Cell"><span class="normalfont">
	<input type="checkbox" name="dowhat[$rule[ruleid]][folder]" value="1" $movechecked$copychecked /> <select name="folderstuff[$rule[ruleid]]">
		<option value="2" $actionchecks[2]>move it to</option>
		<option value="4" $actionchecks[4]>copy it to</option>
	</select>
	<select name="folders[$rule[ruleid]]">
		$folderbits
	</select><br />
<%if $numresponses>0%>
	<input type="checkbox" name="dowhat[$rule[ruleid]][respond]" value="1" $respondchecked /> respond <select name="responses[$rule[ruleid]]">
		$responsebits
	</select><br />
<%endif%>
	<input type="checkbox" name="dowhat[$rule[ruleid]][read]" value="1" $readchecked /> mark it as read.<br />
	<input type="checkbox" name="dowhat[$rule[ruleid]][delete]" value="1" $deletechecked /> delete it.<br />
	<input type="checkbox" name="dowhat[$rule[ruleid]][flag]" value="1" $flagchecked /> flag it.</span></td>
	<td class="$class[name]RightCell" align="center"><input type="checkbox" name="active[$rule[ruleid]]" value="yes" $activechecked onClick="checkMain(this.form, \'active\');" /></td>
</tr>',
    'parsed_data' => '"<tr class=\\"$class[name]Row\\" valign=\\"top\\">
	<td class=\\"$class[name]LeftCell\\" nowrap=\\"nowrap\\" width=\\"1%\\" valign=\\"middle\\">$moveup $movedown</td>
	<td class=\\"$class[name]Cell\\"><span class=\\"normalfont\\">When:</span><span class=\\"smallfont\\"><br /><br />[<a href=\\"rules.delete.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}ruleid=$rule[ruleid]\\" onClick=\\"return confirm(\'Are you sure you want to delete this rule?\');\\">delete</a>]<br />[<a href=\\"rules.apply.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}ruleid=$rule[ruleid]\\">apply</a>]</span></td>
	<td class=\\"$class[name]Cell\\">
	<select name=\\"condsubjects[$rule[ruleid]]\\">
		<option value=\\"1\\" $condsubjects[1]>email address</option>
		<option value=\\"2\\" $condsubjects[2]>message</option>
		<option value=\\"3\\" $condsubjects[3]>recipient</option>
		<option value=\\"4\\" $condsubjects[4]>subject</option>
	</select>
	<select name=\\"condhows[$rule[ruleid]]\\">
		<option value=\\"2\\" $condhows[2]>contains</option>
		<option value=\\"3\\" $condhows[3]>does not contain</option>
		<option value=\\"1\\" $condhows[1]>equals</option>
		<option value=\\"4\\" $condhows[4]>begins with</option>
		<option value=\\"5\\" $condhows[5]>ends with</option>
	</select> <input type=\\"text\\" class=\\"bginput\\" name=\\"condextras[$rule[ruleid]]\\" value=\\"$condextra\\" size=\\"20\\" /><br /><input type=\\"checkbox\\" name=\\"exempt[$rule[ruleid]]\\" value=\\"yes\\" $exemptchecked />Exempt safe senders from this rule</td>
	<td class=\\"$class[name]Cell\\"><span class=\\"normalfont\\">
	<input type=\\"checkbox\\" name=\\"dowhat[$rule[ruleid]][folder]\\" value=\\"1\\" $movechecked$copychecked /> <select name=\\"folderstuff[$rule[ruleid]]\\">
		<option value=\\"2\\" $actionchecks[2]>move it to</option>
		<option value=\\"4\\" $actionchecks[4]>copy it to</option>
	</select>
	<select name=\\"folders[$rule[ruleid]]\\">
		$folderbits
	</select><br />
".(($numresponses>0) ? ("
	<input type=\\"checkbox\\" name=\\"dowhat[$rule[ruleid]][respond]\\" value=\\"1\\" $respondchecked /> respond <select name=\\"responses[$rule[ruleid]]\\">
		$responsebits
	</select><br />
") : (\'\'))."
	<input type=\\"checkbox\\" name=\\"dowhat[$rule[ruleid]][read]\\" value=\\"1\\" $readchecked /> mark it as read.<br />
	<input type=\\"checkbox\\" name=\\"dowhat[$rule[ruleid]][delete]\\" value=\\"1\\" $deletechecked /> delete it.<br />
	<input type=\\"checkbox\\" name=\\"dowhat[$rule[ruleid]][flag]\\" value=\\"1\\" $flagchecked /> flag it.</span></td>
	<td class=\\"$class[name]RightCell\\" align=\\"center\\"><input type=\\"checkbox\\" name=\\"active[$rule[ruleid]]\\" value=\\"yes\\" $activechecked onClick=\\"checkMain(this.form, \'active\');\\" /></td>
</tr>"',
  ),
  'search_intro' => 
  array (
    'templategroupid' => '5',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Search Messages</title>
$css
</head>
<body>
$header

<form action="search.results.php" method="post">

<table width="100%">
	<tr>
		<td colspan="3" style="padding: 0px 12px 18px 12px;"><table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			<tr class="headerRow">
				<th class="headerBothCell"><span class="normalfonttablehead">Search Messages</span></th>
			</tr>
		</table></td>
	</tr>
	<tr>
		<td colspan="2" width="67%" style="padding: 12px; padding-top: 0px;">

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Search Keyword...</b></span></th>
</tr>
<tr class="highRow" style="height: 150px;">
	<td class="highBothCell" valign="top"><span class="smallfont">
	<input type="text" class="bginput" name="query" size="35" />
	<br /><br />
	<b>Basic query:</b>
	Seperate your search terms with spaces.<br />
	<br />
	<b>Advanced query:</b> Use double quotes to denote a phrase ("hey pal", for example).<br />
	You can force a word or phrase to be present for the email to match your<br />query by putting a plus (+) sign in front of it. Similarly, use the minus (-)<br />sign to exclude a word or phrase.<br />
	Words or phrases that are not prefixed by neither + or - are examined as<br />well and will give an email a higher score if it contains them.<br />
	Add asterisks (*) to use wild cards in your search.
	</span></td>
</tr>
</table>

		</td>
		<td width="33%" style="padding: 12px; padding-top: 0px;">

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Search In...</b></span></th>
</tr>
<tr class="highRow" style="height: 150px;">
	<td class="highBothCell" valign="top"><span class="smallfont">
	<select name="fields[]" multiple="multiple" size="6">
			<option value="email">Sender\'s email</option>
			<option value="name">Sender\'s name</option>
			<option value="subject" selected="selected">Email subject</option>
			<option value="message" selected="selected">Full message</option>
		</select>
	</span></td>
</tr>
</table>

		</td>
	</tr>
	<tr>
		<td width="33%" style="padding: 12px; padding-top: 0px;">

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Search Folders...</b></span></th>
</tr>
<tr class="highRow" style="height: 125px;">
	<td class="highBothCell" valign="top"><span class="smallfont">
	<select name="folderids[]" multiple="multiple" size="$selectsize">
			<option value="0" selected="selected">All folders</option>
			<option value="-">---------------------</option>
$folderjump
		</select>
	</span></td>
</tr>
</table>

		</td>
		<td width="34%" style="padding: 12px; padding-top: 0px;">

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Search For Emails From...</b></span></th>
</tr>
<tr class="highRow" style="height: 125px;">
	<td class="highBothCell" valign="top"><span class="smallfont">
	<select name="searchdate">
		<option value="-1">any date</option>
		<option value="1">yesterday</option>
		<option value="5">a week ago</option>
		<option value="10">2 weeks ago</option>
		<option value="30">a month ago</option>
		<option value="90">3 months ago</option>
		<option value="180">6 months ago</option>
		<option value="365">a year ago</option>
	</select><br />
	<input type="radio" name="beforeafter" value="after" checked="checked" /> and newer<br />
	<input type="radio" name="beforeafter" value="before" /> and older</span></td>
</tr>
</table>

		</td>
		<td width="33%" style="padding: 12px; padding-top: 0px;">

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Sort Results By...</b></span></th>
</tr>
<tr class="highRow" style="height: 125px;">
	<td class="highBothCell" valign="top"><span class="smallfont">
	<select name="sortby">
		<option value="lastpost" selected="selected">received date</option>
		<option value="subject">subject</option>
		<option value="replies">sender\'s email</option>
		<option value="replies">sender\'s name</option>
	</select><br />
	<input type="radio" name="sortorder" value="asc" /> in ascending order<br />
	<input type="radio" name="sortorder" value="desc" checked="checked" /> in descending order
	</span></td>
</tr>
</table>

		</td>
	</tr>

</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%" align="center">
<tr>
	<td align="center"><span class="normalfont">
	<input type="submit" class="bginput" name="Submit" value="Perform Search" accesskey="s" />
	<input type="reset" class="bginput" name="Reset" value="Reset Fields" />
	</span></td>
</tr>
</table>

</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Search Messages</title>
$GLOBALS[css]
</head>
<body>
$GLOBALS[header]

<form action=\\"search.results.php{$GLOBALS[session_url]}\\" method=\\"post\\">

<table width=\\"100%\\">
	<tr>
		<td colspan=\\"3\\" style=\\"padding: 0px 12px 18px 12px;\\"><table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			<tr class=\\"headerRow\\">
				<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\">Search Messages</span></th>
			</tr>
		</table></td>
	</tr>
	<tr>
		<td colspan=\\"2\\" width=\\"67%\\" style=\\"padding: 12px; padding-top: 0px;\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Search Keyword...</b></span></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 150px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"smallfont\\">
	<input type=\\"text\\" class=\\"bginput\\" name=\\"query\\" size=\\"35\\" />
	<br /><br />
	<b>Basic query:</b>
	Seperate your search terms with spaces.<br />
	<br />
	<b>Advanced query:</b> Use double quotes to denote a phrase (\\"hey pal\\", for example).<br />
	You can force a word or phrase to be present for the email to match your<br />query by putting a plus (+) sign in front of it. Similarly, use the minus (-)<br />sign to exclude a word or phrase.<br />
	Words or phrases that are not prefixed by neither + or - are examined as<br />well and will give an email a higher score if it contains them.<br />
	Add asterisks (*) to use wild cards in your search.
	</span></td>
</tr>
</table>

		</td>
		<td width=\\"33%\\" style=\\"padding: 12px; padding-top: 0px;\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Search In...</b></span></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 150px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"smallfont\\">
	<select name=\\"fields[]\\" multiple=\\"multiple\\" size=\\"6\\">
			<option value=\\"email\\">Sender\'s email</option>
			<option value=\\"name\\">Sender\'s name</option>
			<option value=\\"subject\\" selected=\\"selected\\">Email subject</option>
			<option value=\\"message\\" selected=\\"selected\\">Full message</option>
		</select>
	</span></td>
</tr>
</table>

		</td>
	</tr>
	<tr>
		<td width=\\"33%\\" style=\\"padding: 12px; padding-top: 0px;\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Search Folders...</b></span></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 125px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"smallfont\\">
	<select name=\\"folderids[]\\" multiple=\\"multiple\\" size=\\"$selectsize\\">
			<option value=\\"0\\" selected=\\"selected\\">All folders</option>
			<option value=\\"-\\">---------------------</option>
$folderjump
		</select>
	</span></td>
</tr>
</table>

		</td>
		<td width=\\"34%\\" style=\\"padding: 12px; padding-top: 0px;\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Search For Emails From...</b></span></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 125px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"smallfont\\">
	<select name=\\"searchdate\\">
		<option value=\\"-1\\">any date</option>
		<option value=\\"1\\">yesterday</option>
		<option value=\\"5\\">a week ago</option>
		<option value=\\"10\\">2 weeks ago</option>
		<option value=\\"30\\">a month ago</option>
		<option value=\\"90\\">3 months ago</option>
		<option value=\\"180\\">6 months ago</option>
		<option value=\\"365\\">a year ago</option>
	</select><br />
	<input type=\\"radio\\" name=\\"beforeafter\\" value=\\"after\\" checked=\\"checked\\" /> and newer<br />
	<input type=\\"radio\\" name=\\"beforeafter\\" value=\\"before\\" /> and older</span></td>
</tr>
</table>

		</td>
		<td width=\\"33%\\" style=\\"padding: 12px; padding-top: 0px;\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Sort Results By...</b></span></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 125px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"smallfont\\">
	<select name=\\"sortby\\">
		<option value=\\"lastpost\\" selected=\\"selected\\">received date</option>
		<option value=\\"subject\\">subject</option>
		<option value=\\"replies\\">sender\'s email</option>
		<option value=\\"replies\\">sender\'s name</option>
	</select><br />
	<input type=\\"radio\\" name=\\"sortorder\\" value=\\"asc\\" /> in ascending order<br />
	<input type=\\"radio\\" name=\\"sortorder\\" value=\\"desc\\" checked=\\"checked\\" /> in descending order
	</span></td>
</tr>
</table>

		</td>
	</tr>

</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\" align=\\"center\\">
<tr>
	<td align=\\"center\\"><span class=\\"normalfont\\">
	<input type=\\"submit\\" class=\\"bginput\\" name=\\"Submit\\" value=\\"Perform Search\\" accesskey=\\"s\\" />
	<input type=\\"reset\\" class=\\"bginput\\" name=\\"Reset\\" value=\\"Reset Fields\\" />
	</span></td>
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
		new ContextItem(\'Reply to Sender\', function(){ replyForward(form, \'reply\'); }, totalChecked != 1),
		new ContextItem(\'Reply to All\', function(){ replyForward(form, \'replyall\'); }, totalChecked != 1),
		new ContextItem(\'Forward\', function(){ replyForward(form, \'forward\'); }, totalChecked != 1),
		new ContextItem(\'Forward as Attachment\', function(){ replyForward(form, \'forwardattach\'); }),
		new ContextSeperator(),
		new ContextItem(\'Mark as Read\', function(){ form.cmd.value = \'mark\'; form.markas.selectedIndex = 2; form.submit(); }, isNew.indexOf(\'new\') == -1 && totalChecked == 1),
		new ContextItem(\'Mark as Unread\', function(){ form.cmd.value = \'mark\'; form.markas.selectedIndex = 3; form.submit(); }, isNew.indexOf(\'new\') != -1 && totalChecked == 1),
		new ContextItem(\'Flag\', function(){ form.cmd.value = \'mark\'; form.markas.selectedIndex = 4; form.submit(); }, isFlagged == 1 && totalChecked == 1),
		new ContextItem(\'Unflag\', function(){ form.cmd.value = \'mark\'; form.markas.selectedIndex = 5; form.submit(); }, isFlagged == 0 && totalChecked == 1),
		new ContextSeperator(),
		new ContextItem(\'Move...\', function(){ window.open(\'index.php?cmd=selfolder\',\'selectfolders\',\'resizable=no,width=270,height=150\'); }),
		new ContextItem(\'Delete\', function(){ if (confirm(\'Are you sure you want to delete the selected messages?\')) { form.cmd.value = \'delete\'; form.submit(); } }),
		new ContextSeperator(),
		new ContextItem(\'Add Sender(s) to Address Book\', function(){ actionStuff(form, \'addbook\'); })
<%if $hiveuser[canrule] %>,
		new ContextItem(\'Block Sender(s)...\', function(){ actionStuff(form, \'blocksender\'); }),
		new ContextItem(\'Block Subject(s)...\', function(){ actionStuff(form, \'blocksubject\'); })
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
<input type="hidden" name="cmd" id="cmd" value="dostuff" />
<input type="hidden" name="searchid" value="$searchid" />
<input type="hidden" name="folderid" value="$folderid" />
<input type="hidden" name="movetofolderid" value="$folderid" />
<input type="hidden" name="remove" value="0" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerLeftCell">&nbsp;</th>
$colheaders	<th class="headerRightCell"><input name="allbox" type="checkbox" value="Check All" title="Select/Deselect All" onClick="checkAll(this.form); changeButtonsStatus(!this.checked); if (this.checked) makeRows(\'first\'); else makeRows(\'second\');" /></th>
</tr>
$mailbits
<tr>
	<td colspan="10">
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td align="left"><span class="smallfonttablehead"><b>
		<select name="actions" onChange="if (this.options[this.selectedIndex].value != \'nothing\') { if (actionStuff(this.form, this.options[this.selectedIndex].value) == false) this.selectedIndex = 0; } else { this.selectedIndex = 0; }">
			<option value="nothing" selected="selected">Actions to perform...</option>
			<option value="nothing">--------------------------</option>
			<option value="move">Move messages</option>
			<!--<option value="copy">Copy messages</option>-->
			<option value="delete">Delete messages</option>
			<option value="nothing">--------------------------</option>
			<option value="addbook">Add senders to address book</option>
			<option value="blocksender">Block senders</option>
			<option value="blocksubject">Block subjects</option>
		</select>
		&nbsp;
		<select name="replystuff" onChange="if (this.options[this.selectedIndex].value != \'nothing\') { if(replyForward(this.form, this.options[this.selectedIndex].value) == false) this.selectedIndex = 0; } else { this.selectedIndex = 0; }">
			<option value="nothing" selected="selected">Reply or forward...</option>
			<option value="nothing">--------------------------</option>
			<option value="reply">Reply to sender</option>
			<option value="replyall">Reply to all</option>
			<option value="forward">Forward message</option>
			<option value="forwardattach">Forward as attachment</option>
		</select>
		</b></span></td>
        <td align="right"><span class="smallfonttablehead"><b>
		<select name="markas" onChange="if (this.options[this.selectedIndex].value != \'nothing\') { this.form.cmd.value = \'mark\'; this.form.submit(); } else { this.selectedIndex = 0; }">
			<option value="nothing" selected="selected">Mark selected messages...</option>
			<option value="nothing">--------------------------</option>
			<option value="read">Mark as read</option>
			<option value="not read">Mark as not read</option>
			<option value="flagged">Mark as flagged</option>
			<option value="not flagged">Mark as not flagged</option>
			<option value="replied">Mark as replied</option>
			<option value="not replied">Mark as not replied</option>
			<option value="forwarded">Mark as forwarded</option>
			<option value="not forwarded">Mark as not forwarded</option>
		</select></b></span></td>
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
		new ContextItem(\'Reply to Sender\', function(){ replyForward(form, \'reply\'); }, totalChecked != 1),
		new ContextItem(\'Reply to All\', function(){ replyForward(form, \'replyall\'); }, totalChecked != 1),
		new ContextItem(\'Forward\', function(){ replyForward(form, \'forward\'); }, totalChecked != 1),
		new ContextItem(\'Forward as Attachment\', function(){ replyForward(form, \'forwardattach\'); }),
		new ContextSeperator(),
		new ContextItem(\'Mark as Read\', function(){ form.cmd.value = \'mark\'; form.markas.selectedIndex = 2; form.submit(); }, isNew.indexOf(\'new\') == -1 && totalChecked == 1),
		new ContextItem(\'Mark as Unread\', function(){ form.cmd.value = \'mark\'; form.markas.selectedIndex = 3; form.submit(); }, isNew.indexOf(\'new\') != -1 && totalChecked == 1),
		new ContextItem(\'Flag\', function(){ form.cmd.value = \'mark\'; form.markas.selectedIndex = 4; form.submit(); }, isFlagged == 1 && totalChecked == 1),
		new ContextItem(\'Unflag\', function(){ form.cmd.value = \'mark\'; form.markas.selectedIndex = 5; form.submit(); }, isFlagged == 0 && totalChecked == 1),
		new ContextSeperator(),
		new ContextItem(\'Move...\', function(){ window.open(\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=selfolder\',\'selectfolders\',\'resizable=no,width=270,height=150\'); }),
		new ContextItem(\'Delete\', function(){ if (confirm(\'Are you sure you want to delete the selected messages?\')) { form.cmd.value = \'delete\'; form.submit(); } }),
		new ContextSeperator(),
		new ContextItem(\'Add Sender(s) to Address Book\', function(){ actionStuff(form, \'addbook\'); })
".(($hiveuser[canrule] ) ? (",
		new ContextItem(\'Block Sender(s)...\', function(){ actionStuff(form, \'blocksender\'); }),
		new ContextItem(\'Block Subject(s)...\', function(){ actionStuff(form, \'blocksubject\'); })
") : (\'\'))."
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
") : (\'\'))."

<form action=\\"index.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"cmd\\" id=\\"cmd\\" value=\\"dostuff\\" />
<input type=\\"hidden\\" name=\\"searchid\\" value=\\"$searchid\\" />
<input type=\\"hidden\\" name=\\"folderid\\" value=\\"$folderid\\" />
<input type=\\"hidden\\" name=\\"movetofolderid\\" value=\\"$folderid\\" />
<input type=\\"hidden\\" name=\\"remove\\" value=\\"0\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerLeftCell\\">&nbsp;</th>
$colheaders	<th class=\\"headerRightCell\\"><input name=\\"allbox\\" type=\\"checkbox\\" value=\\"Check All\\" title=\\"Select/Deselect All\\" onClick=\\"checkAll(this.form); changeButtonsStatus(!this.checked); if (this.checked) makeRows(\'first\'); else makeRows(\'second\');\\" /></th>
</tr>
$mailbits
<tr>
	<td colspan=\\"10\\">
    <table border=\\"0\\" width=\\"100%\\" cellpadding=\\"0\\" cellspacing=\\"0\\">
      <tr>
        <td align=\\"left\\"><span class=\\"smallfonttablehead\\"><b>
		<select name=\\"actions\\" onChange=\\"if (this.options[this.selectedIndex].value != \'nothing\') { if (actionStuff(this.form, this.options[this.selectedIndex].value) == false) this.selectedIndex = 0; } else { this.selectedIndex = 0; }\\">
			<option value=\\"nothing\\" selected=\\"selected\\">Actions to perform...</option>
			<option value=\\"nothing\\">--------------------------</option>
			<option value=\\"move\\">Move messages</option>
			<!--<option value=\\"copy\\">Copy messages</option>-->
			<option value=\\"delete\\">Delete messages</option>
			<option value=\\"nothing\\">--------------------------</option>
			<option value=\\"addbook\\">Add senders to address book</option>
			<option value=\\"blocksender\\">Block senders</option>
			<option value=\\"blocksubject\\">Block subjects</option>
		</select>
		&nbsp;
		<select name=\\"replystuff\\" onChange=\\"if (this.options[this.selectedIndex].value != \'nothing\') { if(replyForward(this.form, this.options[this.selectedIndex].value) == false) this.selectedIndex = 0; } else { this.selectedIndex = 0; }\\">
			<option value=\\"nothing\\" selected=\\"selected\\">Reply or forward...</option>
			<option value=\\"nothing\\">--------------------------</option>
			<option value=\\"reply\\">Reply to sender</option>
			<option value=\\"replyall\\">Reply to all</option>
			<option value=\\"forward\\">Forward message</option>
			<option value=\\"forwardattach\\">Forward as attachment</option>
		</select>
		</b></span></td>
        <td align=\\"right\\"><span class=\\"smallfonttablehead\\"><b>
		<select name=\\"markas\\" onChange=\\"if (this.options[this.selectedIndex].value != \'nothing\') { this.form.cmd.value = \'mark\'; this.form.submit(); } else { this.selectedIndex = 0; }\\">
			<option value=\\"nothing\\" selected=\\"selected\\">Mark selected messages...</option>
			<option value=\\"nothing\\">--------------------------</option>
			<option value=\\"read\\">Mark as read</option>
			<option value=\\"not read\\">Mark as not read</option>
			<option value=\\"flagged\\">Mark as flagged</option>
			<option value=\\"not flagged\\">Mark as not flagged</option>
			<option value=\\"replied\\">Mark as replied</option>
			<option value=\\"not replied\\">Mark as not replied</option>
			<option value=\\"forwarded\\">Mark as forwarded</option>
			<option value=\\"not forwarded\\">Mark as not forwarded</option>
		</select></b></span></td>
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
") : (\'\'))."

</form>
	</td>
</tr>
</table>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'signup' => 
  array (
    'templategroupid' => '14',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Sign Up</title>
$css
</head>
<body>
$header

<form action="user.signup.php" method="post">
<input type="hidden" name="cmd" value="complete" />
<input type="hidden" name="username" value="$username" />
<input type="hidden" name="domain" value="$userdomain" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="650" align="center">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Sign up: Required Information</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Your name:</b></span>
	<br />
	<span class="smallfont">This name will be sent with all your outgoing emails.</span></td>
	<td class="normalRightCell" width="40%"><input type="text" class="bginput" name="realname" value="$realname" size="40" /></td>
</tr>
$password_row
<tr class="$afterpass[first]Row">
	<td class="$afterpass[first]LeftCell" width="60%" valign="top"><span class="normalfont"><b>Secret question:</b></span><br /><span class="smallfont">If you ever forget your password, you will be asked to answer this question in order to get a new one.</span></td>
	<td class="$afterpass[first]RightCell" width="40%"><input type="text" class="bginput" name="question" value="$question" size="40" /><br /><br />
		<select name="question_options" style="width: 100%;" onChange="if (this.options[this.selectedIndex].value != \'-1\') form.question.value = this.options[this.selectedIndex].text;">
			<option value="-1">(Or choose a question from below)</option>
			<option>Your mother\'s Maiden name?</option>
			<option>Your pet\'s name?</option>
			<option>City of birth?</option>
			<option>Last 4 digits of social security number?</option>
		</select>
	</td>
</tr>
<tr class="$afterpass[second]">
	<td class="$afterpass[second]LeftCell" width="60%" valign="top"><span class="normalfont"><b>Secret answer:</b></span></td>
	<td class="$afterpass[second]RightCell" width="40%"><input type="password" class="bginput" name="answer" value="$answer" size="40" /></td>
</tr>
<tr class="$afterpass[first]">
	<td class="$afterpass[first]LeftCell" width="60%" valign="top"><span class="normalfont"><b>Retype secret answer:</b></span><br /><span class="smallfont">Repeat the secret answer to verify it\'s correct.</span></td>
	<td class="$afterpass[first]RightCell" width="40%"><input type="password" class="bginput" name="answer_repeat" value="$answer_repeat" size="40" /></td>
</tr>
<%if getop(\'regcodecheck\') %>
<tr class="$afterpass[second]">
	<td class="$afterpass[second]LeftCell" width="60%" valign="top"><span class="normalfont"><%if $badcode %><span class="important"><%endif%><b>Registration code:</b><%if $badcode %></span><%endif%></span><br /><span class="smallfont">Please enter the numbers as they appear in the image to the right. If you cannot identify the numbers, make a guess - if the code you enter is incorrect, a new one will be created when the page is loaded again.<br />
	This measure helps us prevent automated registrations and give you a better service.</span></td>
	<td class="$afterpass[second]RightCell" width="40%">
		<table cellpadding="0" cellspacing="0" align="center"><tr><td style="border-style: solid; border-width: 1px; border-color: black;"><img src="user.signup.php?cmd=image&pos=1&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=2&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=3&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=4&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=5&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=6&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=7&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=8&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=9&x=$timenow" border="0" alt="" /></td></tr></table>
		<br /><input type="text" class="bginput" name="userregcode" value="$regcodevalue" size="40" /></td>
</tr>
<%endif%>
<%if $requirealt %>
<tr class="$afterpass[first]">
	<td class="$afterpass[first]LeftCell" width="60%" valign="top"><span class="normalfont"><b>Secondary email address:</b></span><br /><span class="smallfont">As the operators of $appname require user validation, your account will not be functional until they activate it. You will be notified by email at this address when your account is activated.</span></td>
	<td class="$afterpass[first]RightCell" width="40%"><input type="text" class="bginput" name="altemail" value="$altemail" size="40" /></td>
</tr>
<%endif%>
$required_custom_fields
<!-- +++++++++++++++++++++++++++++++++++++ -->
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Optional Information</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Time zone:</b></span><br /><span class="smallfont">Please select the correct time zone from the list.<br />The system will automatically try to adjust the time offset if Daylight Saving Time (DST) is in effect.</span></td>
	<td class="normalRightCell" width="40%">$timezone</td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Birthday:</b></span></td>
	<td class="highRightCell" width="40%"><select name="month">
			<option value="0" $monthsel[0]>Month</option>
			<option value="1" $monthsel[1]>January</option>
			<option value="2" $monthsel[2]>February</option>
			<option value="3" $monthsel[3]>March</option>
			<option value="4" $monthsel[4]>April</option>
			<option value="5" $monthsel[5]>May</option>
			<option value="6" $monthsel[6]>June</option>
			<option value="7" $monthsel[7]>July</option>
			<option value="8" $monthsel[8]>August</option>
			<option value="9" $monthsel[9]>September</option>
			<option value="10" $monthsel[10]>October</option>
			<option value="11" $monthsel[11]>November</option>
			<option value="12" $monthsel[12]>December</option>
		</select>
		<select name="day">
			<option value="0" $daysel[0]>Day</option>
			<option value="1" $daysel[1]>1</option>
			<option value="2" $daysel[2]>2</option>
			<option value="3" $daysel[3]>3</option>
			<option value="4" $daysel[4]>4</option>
			<option value="5" $daysel[5]>5</option>
			<option value="6" $daysel[6]>6</option>
			<option value="7" $daysel[7]>7</option>
			<option value="8" $daysel[8]>8</option>
			<option value="9" $daysel[9]>9</option>
			<option value="10" $daysel[10]>10</option>
			<option value="11" $daysel[11]>11</option>
			<option value="12" $daysel[12]>12</option>
			<option value="13" $daysel[13]>13</option>
			<option value="14" $daysel[14]>14</option>
			<option value="15" $daysel[15]>15</option>
			<option value="16" $daysel[16]>16</option>
			<option value="17" $daysel[17]>17</option>
			<option value="18" $daysel[18]>18</option>
			<option value="19" $daysel[19]>19</option>
			<option value="20" $daysel[20]>20</option>
			<option value="21" $daysel[21]>21</option>
			<option value="22" $daysel[22]>22</option>
			<option value="23" $daysel[23]>23</option>
			<option value="24" $daysel[24]>24</option>
			<option value="25" $daysel[25]>25</option>
			<option value="26" $daysel[26]>26</option>
			<option value="27" $daysel[27]>27</option>
			<option value="28" $daysel[28]>28</option>
			<option value="29" $daysel[29]>29</option>
			<option value="30" $daysel[30]>30</option>
			<option value="31" $daysel[31]>31</option>
		</select>
		<input type="text" class="bginput" name="year" value="$year" size="4" maxlength="4"></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Country:</b></span></td>
	<td class="normalRightCell" width="40%"><select name="country" onChange="if (this.options[this.selectedIndex].value != \'us\') this.form.state.selectedIndex = 0;">
		$countries
	</select></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>State:</b></span></td>
	<td class="highRightCell" width="40%"><select name="state">
		$states
	</select></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Zip code:</b></span></td>
	<td class="normalRightCell" width="40%"><input type="text" class="bginput" name="zip" value="$zip" size="7" maxlength="7"></td>
</tr>
<%if !$requirealt %>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Secondary email address:</b></span></td>
	<td class="highRightCell" width="40%"><input type="text" class="bginput" name="altemail" value="$altemail" size="40" /></td>
</tr>
<%endif%>
$optional_custom_fields
<%if getop(\'termsofservice\') != \'\' %>
<!-- +++++++++++++++++++++++++++++++++++++ -->
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Terms of Service</b></span></th>
</tr>
<tr class="highRow">
	<td class="highBothCell" colspan="2" align="center"><span class="normalfont"><b><input type="checkbox" name="agreeterms" value="1" id="agreeterms" $termschecked /> <%if $noterms %><span class="important"><%endif%><label for="agreeterms">I have read and understand the Terms of Service and agree to them</label>.<%if $noterms %></span><%endif%><br /><br /><textarea name="terms" cols="101" rows="5">$termsofservice</textarea></b></span></td>
</tr>
<%endif%>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="650" align="center">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="submit" value="Sign Up" onClick="if (form.answer.value != form.answer_repeat.value) { alert(\'Your secret answers do not match. Please retype them and submit the form again.\'); return false; } else if (form.answer.value.length == 0) { alert(\'Your secret answer must not be empty.\'); return false; } else if (form.question.value.length == 0) { alert(\'Your secret question must not be empty.\'); return false; } else if (form.password.value.length == 0) { alert(\'Your password must not be empty.\'); return false; } else if (form.password.value != form.password_repeat.value) { alert(\'Your passwords do not match. Please retype them and submit the form again.\'); return false; } else if (form.realname.value.length == 0) { alert(\'Your real name must not be empty.\'); return false; } else if ($moderate == 1 && form.altemail.value.length == 0) { alert(\'Your secondary email address must not be empty.\'); return false; } else { <%if getop(\'termsofservice\') != \'\' %>if (!this.form.agreeterms.checked) { alert(\'You must agree to the Terms of Service.\'); return false; } else { return true; }<%else%>return true;<%endif%> }" />
	</td>
</tr>
</table>

</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Sign Up</title>
$GLOBALS[css]
</head>
<body>
$GLOBALS[header]

<form action=\\"user.signup.php{$GLOBALS[session_url]}\\" method=\\"post\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"complete\\" />
<input type=\\"hidden\\" name=\\"username\\" value=\\"$username\\" />
<input type=\\"hidden\\" name=\\"domain\\" value=\\"$userdomain\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"650\\" align=\\"center\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Sign up: Required Information</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Your name:</b></span>
	<br />
	<span class=\\"smallfont\\">This name will be sent with all your outgoing emails.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"realname\\" value=\\"$realname\\" size=\\"40\\" /></td>
</tr>
$password_row
<tr class=\\"$afterpass[first]Row\\">
	<td class=\\"$afterpass[first]LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secret question:</b></span><br /><span class=\\"smallfont\\">If you ever forget your password, you will be asked to answer this question in order to get a new one.</span></td>
	<td class=\\"$afterpass[first]RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"question\\" value=\\"$question\\" size=\\"40\\" /><br /><br />
		<select name=\\"question_options\\" style=\\"width: 100%;\\" onChange=\\"if (this.options[this.selectedIndex].value != \'-1\') form.question.value = this.options[this.selectedIndex].text;\\">
			<option value=\\"-1\\">(Or choose a question from below)</option>
			<option>Your mother\'s Maiden name?</option>
			<option>Your pet\'s name?</option>
			<option>City of birth?</option>
			<option>Last 4 digits of social security number?</option>
		</select>
	</td>
</tr>
<tr class=\\"$afterpass[second]\\">
	<td class=\\"$afterpass[second]LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secret answer:</b></span></td>
	<td class=\\"$afterpass[second]RightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"answer\\" value=\\"$answer\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"$afterpass[first]\\">
	<td class=\\"$afterpass[first]LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Retype secret answer:</b></span><br /><span class=\\"smallfont\\">Repeat the secret answer to verify it\'s correct.</span></td>
	<td class=\\"$afterpass[first]RightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"answer_repeat\\" value=\\"$answer_repeat\\" size=\\"40\\" /></td>
</tr>
".((getop(\'regcodecheck\') ) ? ("
<tr class=\\"$afterpass[second]\\">
	<td class=\\"$afterpass[second]LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\">".(($badcode ) ? ("<span class=\\"important\\">") : (\'\'))."<b>Registration code:</b>".(($badcode ) ? ("</span>") : (\'\'))."</span><br /><span class=\\"smallfont\\">Please enter the numbers as they appear in the image to the right. If you cannot identify the numbers, make a guess - if the code you enter is incorrect, a new one will be created when the page is loaded again.<br />
	This measure helps us prevent automated registrations and give you a better service.</span></td>
	<td class=\\"$afterpass[second]RightCell\\" width=\\"40%\\">
		<table cellpadding=\\"0\\" cellspacing=\\"0\\" align=\\"center\\"><tr><td style=\\"border-style: solid; border-width: 1px; border-color: black;\\"><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=1&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=2&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=3&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=4&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=5&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=6&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=7&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=8&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=9&x=$timenow\\" border=\\"0\\" alt=\\"\\" /></td></tr></table>
		<br /><input type=\\"text\\" class=\\"bginput\\" name=\\"userregcode\\" value=\\"$regcodevalue\\" size=\\"40\\" /></td>
</tr>
") : (\'\'))."
".(($requirealt ) ? ("
<tr class=\\"$afterpass[first]\\">
	<td class=\\"$afterpass[first]LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secondary email address:</b></span><br /><span class=\\"smallfont\\">As the operators of $appname require user validation, your account will not be functional until they activate it. You will be notified by email at this address when your account is activated.</span></td>
	<td class=\\"$afterpass[first]RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"altemail\\" value=\\"$altemail\\" size=\\"40\\" /></td>
</tr>
") : (\'\'))."
$required_custom_fields
<!-- +++++++++++++++++++++++++++++++++++++ -->
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Optional Information</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Time zone:</b></span><br /><span class=\\"smallfont\\">Please select the correct time zone from the list.<br />The system will automatically try to adjust the time offset if Daylight Saving Time (DST) is in effect.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\">$timezone</td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Birthday:</b></span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><select name=\\"month\\">
			<option value=\\"0\\" $monthsel[0]>Month</option>
			<option value=\\"1\\" $monthsel[1]>January</option>
			<option value=\\"2\\" $monthsel[2]>February</option>
			<option value=\\"3\\" $monthsel[3]>March</option>
			<option value=\\"4\\" $monthsel[4]>April</option>
			<option value=\\"5\\" $monthsel[5]>May</option>
			<option value=\\"6\\" $monthsel[6]>June</option>
			<option value=\\"7\\" $monthsel[7]>July</option>
			<option value=\\"8\\" $monthsel[8]>August</option>
			<option value=\\"9\\" $monthsel[9]>September</option>
			<option value=\\"10\\" $monthsel[10]>October</option>
			<option value=\\"11\\" $monthsel[11]>November</option>
			<option value=\\"12\\" $monthsel[12]>December</option>
		</select>
		<select name=\\"day\\">
			<option value=\\"0\\" $daysel[0]>Day</option>
			<option value=\\"1\\" $daysel[1]>1</option>
			<option value=\\"2\\" $daysel[2]>2</option>
			<option value=\\"3\\" $daysel[3]>3</option>
			<option value=\\"4\\" $daysel[4]>4</option>
			<option value=\\"5\\" $daysel[5]>5</option>
			<option value=\\"6\\" $daysel[6]>6</option>
			<option value=\\"7\\" $daysel[7]>7</option>
			<option value=\\"8\\" $daysel[8]>8</option>
			<option value=\\"9\\" $daysel[9]>9</option>
			<option value=\\"10\\" $daysel[10]>10</option>
			<option value=\\"11\\" $daysel[11]>11</option>
			<option value=\\"12\\" $daysel[12]>12</option>
			<option value=\\"13\\" $daysel[13]>13</option>
			<option value=\\"14\\" $daysel[14]>14</option>
			<option value=\\"15\\" $daysel[15]>15</option>
			<option value=\\"16\\" $daysel[16]>16</option>
			<option value=\\"17\\" $daysel[17]>17</option>
			<option value=\\"18\\" $daysel[18]>18</option>
			<option value=\\"19\\" $daysel[19]>19</option>
			<option value=\\"20\\" $daysel[20]>20</option>
			<option value=\\"21\\" $daysel[21]>21</option>
			<option value=\\"22\\" $daysel[22]>22</option>
			<option value=\\"23\\" $daysel[23]>23</option>
			<option value=\\"24\\" $daysel[24]>24</option>
			<option value=\\"25\\" $daysel[25]>25</option>
			<option value=\\"26\\" $daysel[26]>26</option>
			<option value=\\"27\\" $daysel[27]>27</option>
			<option value=\\"28\\" $daysel[28]>28</option>
			<option value=\\"29\\" $daysel[29]>29</option>
			<option value=\\"30\\" $daysel[30]>30</option>
			<option value=\\"31\\" $daysel[31]>31</option>
		</select>
		<input type=\\"text\\" class=\\"bginput\\" name=\\"year\\" value=\\"$year\\" size=\\"4\\" maxlength=\\"4\\"></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Country:</b></span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><select name=\\"country\\" onChange=\\"if (this.options[this.selectedIndex].value != \'us\') this.form.state.selectedIndex = 0;\\">
		$countries
	</select></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>State:</b></span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><select name=\\"state\\">
		$states
	</select></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Zip code:</b></span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"zip\\" value=\\"$zip\\" size=\\"7\\" maxlength=\\"7\\"></td>
</tr>
".((!$requirealt ) ? ("
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secondary email address:</b></span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"altemail\\" value=\\"$altemail\\" size=\\"40\\" /></td>
</tr>
") : (\'\'))."
$optional_custom_fields
".((getop(\'termsofservice\') != \'\' ) ? ("
<!-- +++++++++++++++++++++++++++++++++++++ -->
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Terms of Service</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\" colspan=\\"2\\" align=\\"center\\"><span class=\\"normalfont\\"><b><input type=\\"checkbox\\" name=\\"agreeterms\\" value=\\"1\\" id=\\"agreeterms\\" $termschecked /> ".(($noterms ) ? ("<span class=\\"important\\">") : (\'\'))."<label for=\\"agreeterms\\">I have read and understand the Terms of Service and agree to them</label>.".(($noterms ) ? ("</span>") : (\'\'))."<br /><br /><textarea name=\\"terms\\" cols=\\"101\\" rows=\\"5\\">$termsofservice</textarea></b></span></td>
</tr>
") : (\'\'))."
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"650\\" align=\\"center\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Sign Up\\" onClick=\\"if (form.answer.value != form.answer_repeat.value) { alert(\'Your secret answers do not match. Please retype them and submit the form again.\'); return false; } else if (form.answer.value.length == 0) { alert(\'Your secret answer must not be empty.\'); return false; } else if (form.question.value.length == 0) { alert(\'Your secret question must not be empty.\'); return false; } else if (form.password.value.length == 0) { alert(\'Your password must not be empty.\'); return false; } else if (form.password.value != form.password_repeat.value) { alert(\'Your passwords do not match. Please retype them and submit the form again.\'); return false; } else if (form.realname.value.length == 0) { alert(\'Your real name must not be empty.\'); return false; } else if ($moderate == 1 && form.altemail.value.length == 0) { alert(\'Your secondary email address must not be empty.\'); return false; } else { ".((getop(\'termsofservice\') != \'\' ) ? ("if (!this.form.agreeterms.checked) { alert(\'You must agree to the Terms of Service.\'); return false; } else { return true; }") : ("return true;"))." }\\" />
	</td>
</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
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
$appurl/admin/user.php?cmd=validate

<%endif%>
$appname team',
    'parsed_data' => '"Dear operator,

A new user has registered at $appname:
Username: $username
Real name: $realname
".((getop(\'moderate\') ) ? ("

Please visit the administrative control panel to activate this user:
$appurl/admin/user.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=validate

") : \'\')."
$appname team"',
  ),
  'signup_password_static' => 
  array (
    'templategroupid' => '14',
    'user_data' => '<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Your password:</b></span></td>
	<td class="highRightCell" width="40%"><span class="normalfont">$hidden_password</span></td>
</tr>
<input type="hidden" name="password" value="$password" />
<input type="hidden" name="password_repeat" value="$password" />
<input type="hidden" name="password_encrypted" value="1" />
<input type="hidden" name="password_length" value="$passlen" />',
    'parsed_data' => '"<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Your password:</b></span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\">$hidden_password</span></td>
</tr>
<input type=\\"hidden\\" name=\\"password\\" value=\\"$password\\" />
<input type=\\"hidden\\" name=\\"password_repeat\\" value=\\"$password\\" />
<input type=\\"hidden\\" name=\\"password_encrypted\\" value=\\"1\\" />
<input type=\\"hidden\\" name=\\"password_length\\" value=\\"$passlen\\" />"',
  ),
  'signup_thankyou' => 
  array (
    'templategroupid' => '14',
    'user_data' => 'Thank for your signing up for our service!<br />
<%if getop(\'moderate\') %>
As the operators of $appname require user validation, your account will not be functional until they activate it. You will be notified by email ($altemail) when your account is activated.
<%else%>
Click <a href="index.php">here</a> to be taken to your Inbox. Enjoy $appname!
<%endif%>',
    'parsed_data' => '"Thank for your signing up for our service!<br />
".((getop(\'moderate\') ) ? ("
As the operators of $appname require user validation, your account will not be functional until they activate it. You will be notified by email ($altemail) when your account is activated.
") : ("
Click <a href=\\"index.php{$GLOBALS[session_url]}\\">here</a> to be taken to your Inbox. Enjoy $appname!
"))',
  ),
);

?>