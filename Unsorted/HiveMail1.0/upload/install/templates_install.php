<?php
//
// +-------------------------------------------------------------+
// | HiveMail version 1.0
// | Copyright (C) 2002 Chen Avinadav
// | Supplied by  CyKuH [WTN]
// | Nullified by CyKuH [WTN]
// | Distribution via WebForum, ForumRU and associated file dumps
// +-------------------------------------------------------------+

// ############################################################################
// Templates
$templates = array (
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
<input type="hidden" name="do" value="update" />

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
<input type=\\"hidden\\" name=\\"do\\" value=\\"update\\" />

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
  'addbook_edit_entry' => 
  array (
    'templategroupid' => '4',
    'user_data' => '<tr class="$classname">
	<td class="normalLeftCell" width="45%"><input type="text" class="bginput" name="name[$addbook[contactid]]" value="$addbook[name]" size="40" /></td>
	<td class="normalCell" width="45%"><input type="text" class="bginput" name="email[$addbook[contactid]]" value="$addbook[email]" size="40" /></td>
	<td class="normalRightCell" align="center"><input type="checkbox" name="deletelist[$addbook[contactid]]" value="yes" onClick="checkMain(this.form);" /></td>
</tr>
',
    'parsed_data' => '"<tr class=\\"$classname\\">
	<td class=\\"normalLeftCell\\" width=\\"45%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"name[$addbook[contactid]]\\" value=\\"$addbook[name]\\" size=\\"40\\" /></td>
	<td class=\\"normalCell\\" width=\\"45%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"email[$addbook[contactid]]\\" value=\\"$addbook[email]\\" size=\\"40\\" /></td>
	<td class=\\"normalRightCell\\" align=\\"center\\"><input type=\\"checkbox\\" name=\\"deletelist[$addbook[contactid]]\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
"',
  ),
  'addbook_main' => 
  array (
    'templategroupid' => '4',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Address Book</title>
$css
<script type="text/javascript" src="misc/checkall.js"></script>
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

<form action="addressbook.add.php" method="post">
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
	<td class="normalBothCell"><span class="smallfont">Click the "Browse..." button to find the CSV file you wish to use.<br />Note that the file must have its columns named "Name" and "E-mail Address" for this to work.<br />When you are done, click "Upload CSV".<br /><br />
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

<form action=\\"addressbook.add.php{$GLOBALS[session_url]}\\" method=\\"post\\">
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
	<td class=\\"normalBothCell\\"><span class=\\"smallfont\\">Click the \\"Browse...\\" button to find the CSV file you wish to use.<br />Note that the file must have its columns named \\"Name\\" and \\"E-mail Address\\" for this to work.<br />When you are done, click \\"Upload CSV\\".<br /><br />
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
  'addbook_main_entry' => 
  array (
    'templategroupid' => '4',
    'user_data' => '<tr class="$classname">
	<td class="normalLeftCell" width="45%"><span class="normalfont"><a href="addressbook.update.php?contactid=$addbook[contactid]">$addbook[name]</a></span></td>
	<td class="normalCell" width="45%"><span class="normalfont"><a href="compose.email.php?email=$addbook[link]">$addbook[email]</a></span></td>
	<td class="normalRightCell" align="center"><input type="checkbox" name="deletelist[$addbook[contactid]]" value="yes" onClick="checkMain(this.form);" /></td>
</tr>
',
    'parsed_data' => '"<tr class=\\"$classname\\">
	<td class=\\"normalLeftCell\\" width=\\"45%\\"><span class=\\"normalfont\\"><a href=\\"addressbook.update.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}contactid=$addbook[contactid]\\">$addbook[name]</a></span></td>
	<td class=\\"normalCell\\" width=\\"45%\\"><span class=\\"normalfont\\"><a href=\\"compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}email=$addbook[link]\\">$addbook[email]</a></span></td>
	<td class=\\"normalRightCell\\" align=\\"center\\"><input type=\\"checkbox\\" name=\\"deletelist[$addbook[contactid]]\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
"',
  ),
  'addbook_main_noentries' => 
  array (
    'templategroupid' => '4',
    'user_data' => '<tr align="center" class="highRow">
	<td align="center" colspan="3" class="normalBothCell"><span class="normalfont">No contacts.</span></td>
</tr>
',
    'parsed_data' => '"<tr align=\\"center\\" class=\\"highRow\\">
	<td align=\\"center\\" colspan=\\"3\\" class=\\"normalBothCell\\"><span class=\\"normalfont\\">No contacts.</span></td>
</tr>
"',
  ),
  'addbook_mini' => 
  array (
    'templategroupid' => '4',
    'user_data' => '$skin[doctype]
<html>
<head>
<title>$appname: Address Book</title>
$css
<script type="text/javascript" src="misc/addressbook.js"></script>
<script language="Javascript">
<!--
self.focus();
// -->
</script>
</head>
<body style="background-color: #C7E1F4;">

$header

<form action="addressbook.view.php" method="post" name="contactsform" onSubmit="return false;">

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Address Book</b></span></th>
</tr>
</table>

<table align="center">
	<tr>
		<td rowspan="3" width="50%">
			<select name="contacts" style="width: 100%" multiple="multiple" size="17" onChange="updateDisabled(this.form, \'adds\');">
			$contacts
			</select>
		</td>
		<td>
			<input type="button" style="width: 55px;" value="To ->" onClick="addto(this.form, \'to\');" class="bginput" disabled="disabled" name="toto" /><br />
			<input type="button" style="width: 55px;" value="Delete" onClick="to.options[to.selectedIndex] = null; this.disabled = true;" class="bginput" disabled="disabled" name="deleteto" />
		</td>
		<td width="50%">
			<select name="tolist[]" style="width: 100%" id="to" multiple="multiple" size="5" onChange="updateDisabled(this.form, \'to\');">
			$to
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<input type="button" style="width: 55px;" value="Cc ->" onClick="addto(this.form, \'cc\');" class="bginput" disabled="disabled" name="tocc" /><br />
			<input type="button" style="width: 55px;" value="Delete" onClick="cc.options[cc.selectedIndex] = null; this.disabled = true;" class="bginput" disabled="disabled" name="deletecc" />
		</td>
		<td width="50%">
			<select name="cclist[]" style="width: 100%" id="cc" multiple="multiple" size="5" onChange="updateDisabled(this.form, \'cc\');">
			$cc
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<input type="button" style="width: 55px;" value="Bcc ->" onClick="addto(this.form, \'bcc\');" class="bginput" disabled="disabled" name="tobcc" /><br />
			<input type="button" style="width: 55px;" value="Delete" onClick="bcc.options[bcc.selectedIndex] = null; this.disabled = true;" class="bginput" disabled="disabled" name="deletebcc" />
		</td>
		<td width="50%">
			<select name="bcclist[]" style="width: 100%" id="bcc" multiple="multiple" size="5" onChange="updateDisabled(this.form, \'bcc\');">
			$bcc
			</select>
		</td>
	</tr>
	<tr width="100%">
		<td colspan="3" align="center"><input type="text" name="who" value="" class="bginput" style="width: 100%" readonly="readonly" /></td>
	</tr>
</table>

<div align="center">
<input type="submit" class="bginput" value="OK" onClick="extractList(this.form);" />&nbsp;&nbsp;
<input type="button" class="bginput" value="Cancel" onClick="window.close();" />
</div>
</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head>
<title>$appname: Address Book</title>
$GLOBALS[css]
<script type=\\"text/javascript\\" src=\\"misc/addressbook.js\\"></script>
<script language=\\"Javascript\\">
<!--
self.focus();
// -->
</script>
</head>
<body style=\\"background-color: #C7E1F4;\\">

$GLOBALS[header]

<form action=\\"addressbook.view.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"contactsform\\" onSubmit=\\"return false;\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Address Book</b></span></th>
</tr>
</table>

<table align=\\"center\\">
	<tr>
		<td rowspan=\\"3\\" width=\\"50%\\">
			<select name=\\"contacts\\" style=\\"width: 100%\\" multiple=\\"multiple\\" size=\\"17\\" onChange=\\"updateDisabled(this.form, \'adds\');\\">
			$contacts
			</select>
		</td>
		<td>
			<input type=\\"button\\" style=\\"width: 55px;\\" value=\\"To ->\\" onClick=\\"addto(this.form, \'to\');\\" class=\\"bginput\\" disabled=\\"disabled\\" name=\\"toto\\" /><br />
			<input type=\\"button\\" style=\\"width: 55px;\\" value=\\"Delete\\" onClick=\\"to.options[to.selectedIndex] = null; this.disabled = true;\\" class=\\"bginput\\" disabled=\\"disabled\\" name=\\"deleteto\\" />
		</td>
		<td width=\\"50%\\">
			<select name=\\"tolist[]\\" style=\\"width: 100%\\" id=\\"to\\" multiple=\\"multiple\\" size=\\"5\\" onChange=\\"updateDisabled(this.form, \'to\');\\">
			$to
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<input type=\\"button\\" style=\\"width: 55px;\\" value=\\"Cc ->\\" onClick=\\"addto(this.form, \'cc\');\\" class=\\"bginput\\" disabled=\\"disabled\\" name=\\"tocc\\" /><br />
			<input type=\\"button\\" style=\\"width: 55px;\\" value=\\"Delete\\" onClick=\\"cc.options[cc.selectedIndex] = null; this.disabled = true;\\" class=\\"bginput\\" disabled=\\"disabled\\" name=\\"deletecc\\" />
		</td>
		<td width=\\"50%\\">
			<select name=\\"cclist[]\\" style=\\"width: 100%\\" id=\\"cc\\" multiple=\\"multiple\\" size=\\"5\\" onChange=\\"updateDisabled(this.form, \'cc\');\\">
			$cc
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<input type=\\"button\\" style=\\"width: 55px;\\" value=\\"Bcc ->\\" onClick=\\"addto(this.form, \'bcc\');\\" class=\\"bginput\\" disabled=\\"disabled\\" name=\\"tobcc\\" /><br />
			<input type=\\"button\\" style=\\"width: 55px;\\" value=\\"Delete\\" onClick=\\"bcc.options[bcc.selectedIndex] = null; this.disabled = true;\\" class=\\"bginput\\" disabled=\\"disabled\\" name=\\"deletebcc\\" />
		</td>
		<td width=\\"50%\\">
			<select name=\\"bcclist[]\\" style=\\"width: 100%\\" id=\\"bcc\\" multiple=\\"multiple\\" size=\\"5\\" onChange=\\"updateDisabled(this.form, \'bcc\');\\">
			$bcc
			</select>
		</td>
	</tr>
	<tr width=\\"100%\\">
		<td colspan=\\"3\\" align=\\"center\\"><input type=\\"text\\" name=\\"who\\" value=\\"\\" class=\\"bginput\\" style=\\"width: 100%\\" readonly=\\"readonly\\" /></td>
	</tr>
</table>

<div align=\\"center\\">
<input type=\\"submit\\" class=\\"bginput\\" value=\\"OK\\" onClick=\\"extractList(this.form);\\" />&nbsp;&nbsp;
<input type=\\"button\\" class=\\"bginput\\" value=\\"Cancel\\" onClick=\\"window.close();\\" />
</div>
</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'addbook_mini_entry' => 
  array (
    'templategroupid' => '4',
    'user_data' => '<option value="$addbook[email]">$addbook[name]</option>
',
    'parsed_data' => '"<option value=\\"$addbook[email]\\">$addbook[name]</option>
"',
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

function insertSig() {
<%if $data[html] %>
	idContent.InsertCustomHTML(composeform.signature.value);
<%else%>
	composeform.tmessage.value += composeform.signature.value;
<%endif%>
	composeform.addsig.value = 1;
}

var contacts = new Array($contactArray);

// -->
</script>
<script type="text/javascript" src="misc/autocomplete.js"></script>
</head>
<body onLoad="editorInit();">
$header

<form enctype="multipart/form-data" action="compose.email.php" name="composeform" method="post" onSubmit="sumbitForm();">
<input type="hidden" name="do" value="compose" />
<input type="hidden" name="save" value="1" />
<input type="hidden" name="draftid" value="$draftid" />
<input type="hidden" name="data[special]" value="$data[special]" />
<input type="hidden" name="message" value="" />
<input type="hidden" name="data[html]" value="$data[html]" id="usehtml" />
<input type="hidden" name="data[bgcolor]" value="" id="bgcolor" />
<input type="hidden" name="signature" value="$signature" />
<input type="hidden" name="data[addedsig]" value="$data[addedsig]" id="addsig" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th colspan="2" class="headerBothCell"><span class="normalfonttablehead"><b>Send New Mail</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" style="padding-right: 40px;"><span class="normalfont"><b>From:</b></span></td>
	<td class="normalRightCell" style="width: 100%;"><span class="normalfont">$hiveuser[realname] ($hiveuser[username]$domainname)</span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" style="padding-right: 40px;"><span class="normalfont"><b><a href="#" onClick="popAddBook(); return false;"><img src="$skin[images]/addbook.gif" alt="Address Book" border="0" /></a> To:</b></span></td>
	<td class="highRightCell" style="width: 100%;"><input type="text" class="bginput" name="data[to]" value="$data[to]" size="72" autocomplete="off" onKeyUp="autoComplete(this, contacts);" id="to" /></td>
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
	<td class="normalRightCell" style="width: 100%;"><input type="text" class="bginput" value="$data[subject]" name="data[subject]" size="72" /></td>
</tr>
<tr class="highRow">
	<td class="highBothCell" colspan="2">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr valign="top">
				<td><%if $data[html] %><ACE:AdvContentEditor id="idContent" /><%else%><textarea name="data[message]" style="width: 573px; height: 380px;" wrap="virtual" id="tmessage">$data[message]</textarea><%endif%></td>
			</tr>
		</table>
	</td>
</tr>
<tr class="normalRow">
	<td class="normalBothCell" colspan="2"><span class="smallfont"><a href="#" onClick="insertSig(); return false;">Insert Signature</a><%if $hiveuser[cansendhtml] %>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" onClick="sumbitForm(1); composeform.submit();">Switch to $switchmode</a><%endif%></span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" style="padding-right: 40px;" valign="top"><span class="normalfont"><b>Options:</b></span></td>
	<td class="highRightCell" style="width: 100%;" valign="top"><span class="smallfont">
	<input type="checkbox" name="data[savecopy]" value="1" $savecopychecked /> <b>Save a copy:</b> Also save a copy in the Sent Items folder.<br />
	<input type="checkbox" name="data[requestread]" value="1" $requestreadchecked /> <b>Request read receipt:</b> Be notified when the receiver reads the message.<br />
	<input type="checkbox" name="data[addtobook]" value="1" $addtobookchecked /> <b>Add contacts to address book:</b> Automatically add all recipients of this email to<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;your address book after you send this message.
	</span></td>
</tr>
<%if $hiveuser[canattach] %>
<tr class="normalRow">
	<td class="normalLeftCell" style="padding-right: 40px;" valign="top"><span class="normalfont"><b>Attachments:</b></span></td>
	<td class="normalRightCell" style="width: 100%;" valign="top"><span class="normalfont">
	$attachlist
	<br /><input type="submit" class="bginput" name="manageattach" value="Manage Attachments" onClick="var attWnd = window.open(\'compose.attachments.php?draftid=$draftid\',\'manageattach\',\'statusbar=no,menubar=no,toolbar=no,scrollbars=yes,width=480,height=425\'); return false;" />
	</span></td>
</tr>
<tr class="highRow">
<%else%>
<tr class="normalRow">
<%endif%>
	<td class="normalLeftCell" style="padding-right: 40px;"><span class="normalfont"><b>Priority:</b></span></td>
	<td class="normalRighttCell" style="width: 100%;">
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
	<input type="submit" class="bginput" name="send" value="Send Email" onClick="this.form.action=\'compose.send.php\'; return true;" accesskey="s" /> 
	$updatedraft
	<input type="submit" class="bginput" name="draft" value="$draftbutton" onClick="this.form.action=\'compose.draft.php\'; return true;" />
	</td>
</tr>
</form>
</table>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html".(($data[html] ) ? (" XMLNS:ACE") : \'\').">
<head><title>$appname: Send New Mail</title>
".(($data[html] ) ? ("<".((1) ? ("") : \'\')."?import namespace=\\"ACE\\" implementation=\\"misc/ace.htc\\" />") : \'\')."
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
") : \'\')."
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

function insertSig() {
".(($data[html] ) ? ("
	idContent.InsertCustomHTML(composeform.signature.value);
") : ("
	composeform.tmessage.value += composeform.signature.value;
"))."
	composeform.addsig.value = 1;
}

var contacts = new Array($contactArray);

// -->
</script>
<script type=\\"text/javascript\\" src=\\"misc/autocomplete.js\\"></script>
</head>
<body onLoad=\\"editorInit();\\">
$GLOBALS[header]

<form enctype=\\"multipart/form-data\\" action=\\"compose.email.php{$GLOBALS[session_url]}\\" name=\\"composeform\\" method=\\"post\\" onSubmit=\\"sumbitForm();\\">
<input type=\\"hidden\\" name=\\"do\\" value=\\"compose\\" />
<input type=\\"hidden\\" name=\\"save\\" value=\\"1\\" />
<input type=\\"hidden\\" name=\\"draftid\\" value=\\"$draftid\\" />
<input type=\\"hidden\\" name=\\"data[special]\\" value=\\"$data[special]\\" />
<input type=\\"hidden\\" name=\\"message\\" value=\\"\\" />
<input type=\\"hidden\\" name=\\"data[html]\\" value=\\"$data[html]\\" id=\\"usehtml\\" />
<input type=\\"hidden\\" name=\\"data[bgcolor]\\" value=\\"\\" id=\\"bgcolor\\" />
<input type=\\"hidden\\" name=\\"signature\\" value=\\"$signature\\" />
<input type=\\"hidden\\" name=\\"data[addedsig]\\" value=\\"$data[addedsig]\\" id=\\"addsig\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th colspan=\\"2\\" class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Send New Mail</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" style=\\"padding-right: 40px;\\"><span class=\\"normalfont\\"><b>From:</b></span></td>
	<td class=\\"normalRightCell\\" style=\\"width: 100%;\\"><span class=\\"normalfont\\">$hiveuser[realname] ($hiveuser[username]$domainname)</span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" style=\\"padding-right: 40px;\\"><span class=\\"normalfont\\"><b><a href=\\"#\\" onClick=\\"popAddBook(); return false;\\"><img src=\\"{$GLOBALS[skin][images]}/addbook.gif\\" alt=\\"Address Book\\" border=\\"0\\" /></a> To:</b></span></td>
	<td class=\\"highRightCell\\" style=\\"width: 100%;\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"data[to]\\" value=\\"$data[to]\\" size=\\"72\\" autocomplete=\\"off\\" onKeyUp=\\"autoComplete(this, contacts);\\" id=\\"to\\" /></td>
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
	<td class=\\"normalRightCell\\" style=\\"width: 100%;\\"><input type=\\"text\\" class=\\"bginput\\" value=\\"$data[subject]\\" name=\\"data[subject]\\" size=\\"72\\" /></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\" colspan=\\"2\\">
		<table cellpadding=\\"0\\" cellspacing=\\"0\\" border=\\"0\\">
			<tr valign=\\"top\\">
				<td>".(($data[html] ) ? ("<ACE:AdvContentEditor id=\\"idContent\\" />") : ("<textarea name=\\"data[message]\\" style=\\"width: 573px; height: 380px;\\" wrap=\\"virtual\\" id=\\"tmessage\\">$data[message]</textarea>"))."</td>
			</tr>
		</table>
	</td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalBothCell\\" colspan=\\"2\\"><span class=\\"smallfont\\"><a href=\\"#\\" onClick=\\"insertSig(); return false;\\">Insert Signature</a>".(($hiveuser[cansendhtml] ) ? ("&nbsp;&nbsp;|&nbsp;&nbsp;<a href=\\"#\\" onClick=\\"sumbitForm(1); composeform.submit();\\">Switch to $switchmode</a>") : \'\')."</span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" style=\\"padding-right: 40px;\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Options:</b></span></td>
	<td class=\\"highRightCell\\" style=\\"width: 100%;\\" valign=\\"top\\"><span class=\\"smallfont\\">
	<input type=\\"checkbox\\" name=\\"data[savecopy]\\" value=\\"1\\" $savecopychecked /> <b>Save a copy:</b> Also save a copy in the Sent Items folder.<br />
	<input type=\\"checkbox\\" name=\\"data[requestread]\\" value=\\"1\\" $requestreadchecked /> <b>Request read receipt:</b> Be notified when the receiver reads the message.<br />
	<input type=\\"checkbox\\" name=\\"data[addtobook]\\" value=\\"1\\" $addtobookchecked /> <b>Add contacts to address book:</b> Automatically add all recipients of this email to<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;your address book after you send this message.
	</span></td>
</tr>
".(($hiveuser[canattach] ) ? ("
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" style=\\"padding-right: 40px;\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Attachments:</b></span></td>
	<td class=\\"normalRightCell\\" style=\\"width: 100%;\\" valign=\\"top\\"><span class=\\"normalfont\\">
	$attachlist
	<br /><input type=\\"submit\\" class=\\"bginput\\" name=\\"manageattach\\" value=\\"Manage Attachments\\" onClick=\\"var attWnd = window.open(\'compose.attachments.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}draftid=$draftid\',\'manageattach\',\'statusbar=no,menubar=no,toolbar=no,scrollbars=yes,width=480,height=425\'); return false;\\" />
	</span></td>
</tr>
<tr class=\\"highRow\\">
") : ("
<tr class=\\"normalRow\\">
"))."
	<td class=\\"normalLeftCell\\" style=\\"padding-right: 40px;\\"><span class=\\"normalfont\\"><b>Priority:</b></span></td>
	<td class=\\"normalRighttCell\\" style=\\"width: 100%;\\">
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
	<input type=\\"submit\\" class=\\"bginput\\" name=\\"send\\" value=\\"Send Email\\" onClick=\\"this.form.action=\'compose.send.php{$GLOBALS[session_url]}\'; return true;\\" accesskey=\\"s\\" /> 
	$updatedraft
	<input type=\\"submit\\" class=\\"bginput\\" name=\\"draft\\" value=\\"$draftbutton\\" onClick=\\"this.form.action=\'compose.draft.php{$GLOBALS[session_url]}\'; return true;\\" />
	</td>
</tr>
</form>
</table>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'compose_attachbit' => 
  array (
    'templategroupid' => '3',
    'user_data' => '	$attachdata[filename] ($attachdata[size] bytes)<br />
',
    'parsed_data' => '"	$attachdata[filename] ($attachdata[size] bytes)<br />
"',
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
	<span class="smallfont">To remove an attachment, click the "Delete" button next to it.</span><br /><br />
	$attachlist
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
	<span class=\\"smallfont\\">To remove an attachment, click the \\"Delete\\" button next to it.</span><br /><br />
	$attachlist
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
  'compose_manageattach_attachbit' => 
  array (
    'templategroupid' => '3',
    'user_data' => '	$attachdata[filename] ($attachdata[size] bytes) <input type="submit" class="bginput" name="delete$number" value="Delete" /><br />
',
    'parsed_data' => '"	$attachdata[filename] ($attachdata[size] bytes) <input type=\\"submit\\" class=\\"bginput\\" name=\\"delete$number\\" value=\\"Delete\\" /><br />
"',
  ),
  'compose_reply' => 
  array (
    'templategroupid' => '3',
    'user_data' => '
----- Original Message -----
From: "$mail[name]" <$mail[email]>
To: "$hiveuser[realname]" <$hiveuser[username]$domainname>
Sent: $mail[datetime]
Subject: $mail[subject]

$hiveuser[replychar] $mail[message]',
    'parsed_data' => '"
----- Original Message -----
From: \\"$mail[name]\\" <$mail[email]>
To: \\"$hiveuser[realname]\\" <$hiveuser[username]$domainname>
Sent: $mail[datetime]
Subject: $mail[subject]

$hiveuser[replychar] $mail[message]"',
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
	vertical-align: top;
	font-size: 11px;
	text-decoration: none;
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
	vertical-align: top;
	font-size: 11px;
	text-decoration: none;
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
") : \'\')."

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
<head><title>$appname Error</title>
$css
</head>
<body>
$header

<table cellpadding="4" cellspacing="0" class="normalTable" width="650" align="center" style="height: 100px;">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>$appname Error</b></span></th>
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
<head><title>$appname Error</title>
$GLOBALS[css]
</head>
<body>
$GLOBALS[header]

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"650\\" align=\\"center\\" style=\\"height: 100px;\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>$appname Error</b></span></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 100%;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\" style=\\"padding: 15px;\\"><span class=\\"normalfont\\">$message</span></td>
</tr>
</table>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'error_accessdenied' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'We\'re sorry, you do not have access to this page.',
    'parsed_data' => '"We\'re sorry, you do not have access to this page."',
  ),
  'error_altemail_notvalid' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The secondary email address you have provided is not valid.',
    'parsed_data' => '"The secondary email address you have provided is not valid."',
  ),
  'error_answer_dontmatch' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The new secret answers you have entered do not match. Please go back and try again.',
    'parsed_data' => '"The new secret answers you have entered do not match. Please go back and try again."',
  ),
  'error_answer_empty' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Your secret answer must not be empty. Please go back and try again.',
    'parsed_data' => '"Your secret answer must not be empty. Please go back and try again."',
  ),
  'error_cantuse' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'We\'re sorry, your account is currently disabled so you are unable to use this service. If you have just registered and the operators of $appname require user validation, your account will not be functional until they activate it. You will be notified by email ($hiveuser[altemail]) when your account is activated.',
    'parsed_data' => '"We\'re sorry, your account is currently disabled so you are unable to use this service. If you have just registered and the operators of $appname require user validation, your account will not be functional until they activate it. You will be notified by email ($hiveuser[altemail]) when your account is activated."',
  ),
  'error_couldntsend' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The server encountered a problem while trying to send your message and could not complete the process.',
    'parsed_data' => '"The server encountered a problem while trying to send your message and could not complete the process."',
  ),
  'error_invalid' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Invalid $name specified.',
    'parsed_data' => '"Invalid $name specified."',
  ),
  'error_invalidid' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Invalid $idname specified.',
    'parsed_data' => '"Invalid $idname specified."',
  ),
  'error_logout' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You were succesfully logged out! Please click <a href="index.php">here</a> to log in using a different account.',
    'parsed_data' => '"You were succesfully logged out! Please click <a href=\\"index.php{$GLOBALS[session_url]}\\">here</a> to log in using a different account."',
  ),
  'error_lostpw_wronganswer' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The secret answer you\'ve entered does not match the one in our database. Please go back and try again.',
    'parsed_data' => '"The secret answer you\'ve entered does not match the one in our database. Please go back and try again."',
  ),
  'error_nocolumns' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You must select at least one column to display.',
    'parsed_data' => '"You must select at least one column to display."',
  ),
  'error_nofolderselected' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You must select at least one folder to apply the rule to.',
    'parsed_data' => '"You must select at least one folder to apply the rule to."',
  ),
  'error_noid' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'No $name specified.',
    'parsed_data' => '"No $name specified."',
  ),
  'error_nomessage' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Your email must contain a message or an attachment.',
    'parsed_data' => '"Your email must contain a message or an attachment."',
  ),
  'error_noresults' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Sorry, no messages matched your search criteria. Please try some different terms.',
    'parsed_data' => '"Sorry, no messages matched your search criteria. Please try some different terms."',
  ),
  'error_nospace' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You are currently using {$_mailmb}MB, passing the limit of $hiveuser[maxmb]MB.<br />You will not be able to send or receive any further message until you delete some existing messages and empty your trash.',
    'parsed_data' => '"You are currently using {$_mailmb}MB, passing the limit of $hiveuser[maxmb]MB.<br />You will not be able to send or receive any further message until you delete some existing messages and empty your trash."',
  ),
  'error_nosubject' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Your email must have a subject.',
    'parsed_data' => '"Your email must have a subject."',
  ),
  'error_noto' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You must enter at least one valid email address of a recipient.',
    'parsed_data' => '"You must enter at least one valid email address of a recipient."',
  ),
  'error_optionsdone' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Thank you for updating your account. Since you created a new username, it has been put into our moderation queue and will be activated within 36 hours.<br />
Until then you will not be able to use the $appname system.',
    'parsed_data' => '"Thank you for updating your account. Since you created a new username, it has been put into our moderation queue and will be activated within 36 hours.<br />
Until then you will not be able to use the $appname system."',
  ),
  'error_password_dontmatch' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The new passwords you have entered do not match. Please go back and try again.',
    'parsed_data' => '"The new passwords you have entered do not match. Please go back and try again."',
  ),
  'error_password_empty' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Your password must not be empty. Please go back and try again.',
    'parsed_data' => '"Your password must not be empty. Please go back and try again."',
  ),
  'error_poplogin' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Could not login to {$serverinfo[$popid][server]}. Please check the login details you have entered again.',
    'parsed_data' => '"Could not login to {$serverinfo[$popid][server]}. Please check the login details you have entered again."',
  ),
  'error_processerror_nospace' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'This message was created automatically by mail delivery software (HiveMail).

A message that you sent could not be delivered to one of its
recipients:
    $user[username]$domainname
The error was:
    The account has reached its storage limit.

------ This is a copy of the message, including all the headers. ------

$message',
    'parsed_data' => '"This message was created automatically by mail delivery software (HiveMail).

A message that you sent could not be delivered to one of its
recipients:
    $user[username]$domainname
The error was:
    The account has reached its storage limit.

------ This is a copy of the message, including all the headers. ------

$message"',
  ),
  'error_processerror_subject' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Mail delivery failed: returning message to sender',
    'parsed_data' => '"Mail delivery failed: returning message to sender"',
  ),
  'error_processerror_unknown' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'This message was created automatically by mail delivery software (HiveMail).

A message that you sent could not be delivered to one of its
recipients:
    $user[username]$domainname
The error was:
    Unknown mailbox.

------ This is a copy of the message, including all the headers. ------

$message',
    'parsed_data' => '"This message was created automatically by mail delivery software (HiveMail).

A message that you sent could not be delivered to one of its
recipients:
    $user[username]$domainname
The error was:
    Unknown mailbox.

------ This is a copy of the message, including all the headers. ------

$message"',
  ),
  'error_realname_empty' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You have left the require Real Name field empty. Please go back and try again.',
    'parsed_data' => '"You have left the require Real Name field empty. Please go back and try again."',
  ),
  'error_signup_disabled' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'We\'re sorry, the operators of $appname have disabled registration on the system.',
    'parsed_data' => '"We\'re sorry, the operators of $appname have disabled registration on the system."',
  ),
  'error_signup_nameillegal' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The username you chose, $username, contains illegal characters. Your username may only contain alphanumeric characters, underscores (_) and dots (.), and must start with a letter.',
    'parsed_data' => '"The username you chose, $username, contains illegal characters. Your username may only contain alphanumeric characters, underscores (_) and dots (.), and must start with a letter."',
  ),
  'error_signup_nametaken' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'We\'re sorry, $username$domainname is already used by another member. Please go back and enter a different name.',
    'parsed_data' => '"We\'re sorry, $username$domainname is already used by another member. Please go back and enter a different name."',
  ),
  'error_wrong_password' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The password you have entered is wrong. Please go back and try again.',
    'parsed_data' => '"The password you have entered is wrong. Please go back and try again."',
  ),
  'error_wrong_username' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The account name you have entered ($username) doesn\'t exist in our records. Please go back and try again.',
    'parsed_data' => '"The account name you have entered ($username) doesn\'t exist in our records. Please go back and try again."',
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
	<td class="highCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$msgcount[inbox]</span></td>
	<td class="highCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$unreadcount[inbox]</span></td>
	<td class="highCell" width="10%" align="center" nowrap="nowrap"><span class="normalfont">$presizes[inbox]KB</span></td>
	<td class="highRightCell"><input type="checkbox" name="folder[-1]" value="yes" onClick="checkMain(this.form);" /></td>
</tr>
<tr align="center" class="normalRow">
	<td class="normalLeftCell" align="left" width="50%"><span class="normalfont"><a href="index.php?folderid=-2">Sent Items</a></span> <span class="smallfont">(not removable)</span></td>
	<td class="normalCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$msgcount[sentitems]</span></td>
	<td class="normalCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$unreadcount[sentitems]</span></td>
	<td class="normalCell" width="10%" align="center" nowrap="nowrap"><span class="normalfont">$presizes[sentitems]KB</span></td>
	<td class="normalRightCell"><input type="checkbox" name="folder[-2]" value="yes" onClick="checkMain(this.form);" /></td>
</tr>
<tr align="center" class="highRow">
	<td class="highLeftCell" align="left" width="50%"><span class="normalfont"><a href="index.php?folderid=-3">Trash Can</a></span> <span class="smallfont">(not removable)</span></td>
	<td class="highCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$msgcount[trashcan]</span></td>
	<td class="highCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$unreadcount[trashcan]</span></td>
	<td class="highCell" width="10%" align="center" nowrap="nowrap"><span class="normalfont">$presizes[trashcan]KB</span></td>
	<td class="highRightCell"><input type="checkbox" name="folder[-3]" id="trashcan" value="yes" onClick="checkMain(this.form);" /></td>
</tr>
$folderbits
<tr align="center" class="headerRow">
	<th class="headerLeftCell" width="50%" align="right"><span class="normalfonttablehead"><b>Total:</b></span></th>
	<th class="headerCell" width="25%" nowrap="nowrap"><span class="normalfonttablehead"><b>$totalmsgs</b></span></th>
	<th class="headerCell" width="25%" nowrap="nowrap"><span class="normalfonttablehead"><b>$totalunreads</b></span></th>
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
	<td class=\\"highCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$msgcount[inbox]</span></td>
	<td class=\\"highCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$unreadcount[inbox]</span></td>
	<td class=\\"highCell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$presizes[inbox]KB</span></td>
	<td class=\\"highRightCell\\"><input type=\\"checkbox\\" name=\\"folder[-1]\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
<tr align=\\"center\\" class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" align=\\"left\\" width=\\"50%\\"><span class=\\"normalfont\\"><a href=\\"index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=-2\\">Sent Items</a></span> <span class=\\"smallfont\\">(not removable)</span></td>
	<td class=\\"normalCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$msgcount[sentitems]</span></td>
	<td class=\\"normalCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$unreadcount[sentitems]</span></td>
	<td class=\\"normalCell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$presizes[sentitems]KB</span></td>
	<td class=\\"normalRightCell\\"><input type=\\"checkbox\\" name=\\"folder[-2]\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
<tr align=\\"center\\" class=\\"highRow\\">
	<td class=\\"highLeftCell\\" align=\\"left\\" width=\\"50%\\"><span class=\\"normalfont\\"><a href=\\"index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=-3\\">Trash Can</a></span> <span class=\\"smallfont\\">(not removable)</span></td>
	<td class=\\"highCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$msgcount[trashcan]</span></td>
	<td class=\\"highCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$unreadcount[trashcan]</span></td>
	<td class=\\"highCell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$presizes[trashcan]KB</span></td>
	<td class=\\"highRightCell\\"><input type=\\"checkbox\\" name=\\"folder[-3]\\" id=\\"trashcan\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
$folderbits
<tr align=\\"center\\" class=\\"headerRow\\">
	<th class=\\"headerLeftCell\\" width=\\"50%\\" align=\\"right\\"><span class=\\"normalfonttablehead\\"><b>Total:</b></span></th>
	<th class=\\"headerCell\\" width=\\"25%\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>$totalmsgs</b></span></th>
	<th class=\\"headerCell\\" width=\\"25%\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>$totalunreads</b></span></th>
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
	<td class="$classnameCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$folder[msgcount]</span></td>
	<td class="$classnameCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$folder[unreadcount]</span></td>
	<td class="$classnameCell" width="10%" align="center" nowrap="nowrap"><span class="normalfont">$folder[size]KB</span></td>
	<td class="$classnameRightCell"><input type="checkbox" name="folder[$folder[folderid]]" value="yes" onClick="checkMain(this.form);" /></td>
</tr>
',
    'parsed_data' => '"<tr align=\\"center\\" class=\\"$classnameRow\\">
	<td class=\\"$classnameLeftCell\\" align=\\"left\\" width=\\"50%\\"><span class=\\"normalfont\\"><a href=\\"index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folder[folderid]\\">$folder[title]</a></span> <span class=\\"smallfont\\">(<a href=\\"#\\" onClick=\\"rename($folder[folderid], \'$folder[title]\');\\">rename</a>)</span></td>
	<td class=\\"$classnameCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$folder[msgcount]</span></td>
	<td class=\\"$classnameCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$folder[unreadcount]</span></td>
	<td class=\\"$classnameCell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$folder[size]KB</span></td>
	<td class=\\"$classnameRightCell\\"><input type=\\"checkbox\\" name=\\"folder[$folder[folderid]]\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
"',
  ),
  'folders_jumpbit' => 
  array (
    'templategroupid' => '9',
    'user_data' => '			<option value="$folder[folderid]">$folder[title]</option>
',
    'parsed_data' => '"			<option value=\\"$folder[folderid]\\">$folder[title]</option>
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
			<span class="footerLink"><a href="index.php" class="footerLink">Inbox</a> &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <a href="compose.email.php" class="footerLink">Compose</a> &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <a href="addressbook.view.php" class="footerLink">Address Book</a> &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <a href="options.menu.php" class="footerLink">Preferences</a><%if $hiveuser[\'cansearch\'] %> &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <a href="search.intro.php" class="footerLink">Search</a><%endif%></span>
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
			<span class=\\"footerLink\\"><a href=\\"index.php{$GLOBALS[session_url]}\\" class=\\"footerLink\\">Inbox</a> &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <a href=\\"compose.email.php{$GLOBALS[session_url]}\\" class=\\"footerLink\\">Compose</a> &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <a href=\\"addressbook.view.php{$GLOBALS[session_url]}\\" class=\\"footerLink\\">Address Book</a> &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <a href=\\"options.menu.php{$GLOBALS[session_url]}\\" class=\\"footerLink\\">Preferences</a>".(($hiveuser[\'cansearch\'] ) ? (" &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; <a href=\\"search.intro.php{$GLOBALS[session_url]}\\" class=\\"footerLink\\">Search</a>") : \'\')."</span>
") : ("
			&nbsp;
"))."
		</td>
		<td style=\\"border: 0px solid #254BAA; border-top-width: 1px;\\">
			<!--CyKuH [WTN]--><img src=\\"{$GLOBALS[skin][images]}/footer_right.gif\\" align=\\"middle\\" border=\\"0\\" alt=\\"\\" />
		</td>
	</tr>
</table>"',
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
</table>"',
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
					<td valign="top" style="background-color: $skin[pagebgcolor]; width: 100%; padding-right: 15px;">',
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
					") : \'\')."
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
					") : \'\')."
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
					<td valign=\\"top\\" style=\\"background-color: {$GLOBALS[skin][pagebgcolor]}; width: 100%; padding-right: 15px;\\">"',
  ),
  'header_mini' => 
  array (
    'templategroupid' => '1',
    'user_data' => '<table cellpadding="0" cellspacing="0" width="100%" style="background-color: #C7E1F4;">
	<tr style="height: 8px;">
		<td style="height: 8px;">
		</td>
	</tr>
	<tr>
		<td style="width: 8px;">
			<img src="$skin[images]/spacer.gif" width="8" height="1" alt="" />
		</td>
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
					<td valign="top" style="background-color: $skin[pagebgcolor]; width: 100%; padding-right: 15px;">',
    'parsed_data' => '"<table cellpadding=\\"0\\" cellspacing=\\"0\\" width=\\"100%\\" style=\\"background-color: #C7E1F4;\\">
	<tr style=\\"height: 8px;\\">
		<td style=\\"height: 8px;\\">
		</td>
	</tr>
	<tr>
		<td style=\\"width: 8px;\\">
			<img src=\\"{$GLOBALS[skin][images]}/spacer.gif\\" width=\\"8\\" height=\\"1\\" alt=\\"\\" />
		</td>
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
					<td valign=\\"top\\" style=\\"background-color: {$GLOBALS[skin][pagebgcolor]}; width: 100%; padding-right: 15px;\\">"',
  ),
  'header_minifolderbit' => 
  array (
    'templategroupid' => '1',
    'user_data' => '<tr onContextMenu="contextForFolder(event, $thisfolder[folderid], \'$thisfolder[title]\');">
	<td align="center"><a href="index.php?folderid=$thisfolder[folderid]"><img src="$skin[images]/folders/$thisfolder[image].gif" border="0" alt="" /></a></td>
	<td nowrap="nowrap"><span class="folderLink"><a href="index.php?folderid=$thisfolder[folderid]" class="folderLink"><span class="folderLink">$thisfolder[title]</a><%if $unreads[$thisfolder[folderid]] != 0%>  <span style="color: #0000FF;">({$unreads[$thisfolder[folderid]]})</span><%endif%></span></td>
</tr>
',
    'parsed_data' => '"<tr onContextMenu=\\"contextForFolder(event, $thisfolder[folderid], \'$thisfolder[title]\');\\">
	<td align=\\"center\\"><a href=\\"index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$thisfolder[folderid]\\"><img src=\\"{$GLOBALS[skin][images]}/folders/$thisfolder[image].gif\\" border=\\"0\\" alt=\\"\\" /></a></td>
	<td nowrap=\\"nowrap\\"><span class=\\"folderLink\\"><a href=\\"index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$thisfolder[folderid]\\" class=\\"folderLink\\"><span class=\\"folderLink\\">$thisfolder[title]</a>".(($unreads[$thisfolder[folderid]] != 0) ? ("  <span style=\\"color: #0000FF;\\">({$unreads[$thisfolder[folderid]]})</span>") : \'\')."</span></td>
</tr>
"',
  ),
  'header_minifolderbit_current' => 
  array (
    'templategroupid' => '1',
    'user_data' => '<tr onContextMenu="contextForFolder(event, $thisfolder[folderid], \'$thisfolder[title]\');">
	<td align="center"><img src="$skin[images]/folders/$thisfolder[image].gif" alt="" /></td>
	<td nowrap="nowrap" style="background-color: #A4C7E8; padding-left: 4px;"><span class="folderLink"><b>$thisfolder[title]</b><%if $unreads[$thisfolder[folderid]] != 0%>  <span style="color: #0000FF;">({$unreads[$thisfolder[folderid]]})</span><%endif%></span></td>
</tr>
',
    'parsed_data' => '"<tr onContextMenu=\\"contextForFolder(event, $thisfolder[folderid], \'$thisfolder[title]\');\\">
	<td align=\\"center\\"><img src=\\"{$GLOBALS[skin][images]}/folders/$thisfolder[image].gif\\" alt=\\"\\" /></td>
	<td nowrap=\\"nowrap\\" style=\\"background-color: #A4C7E8; padding-left: 4px;\\"><span class=\\"folderLink\\"><b>$thisfolder[title]</b>".(($unreads[$thisfolder[folderid]] != 0) ? ("  <span style=\\"color: #0000FF;\\">({$unreads[$thisfolder[folderid]]})</span>") : \'\')."</span></td>
</tr>
"',
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
		new ContextItem(\'Print\', function(){ window.location = \'read.print.php?messageid=\'+msgID; }, totalChecked != 1),
		new ContextSeperator(),
		new ContextItem(\'Reply to Sender\', function(){ window.location = \'compose.email.php?special=reply&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Reply to All\', function(){ window.location = \'compose.email.php?special=replyall&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Forward\', function(){ window.location = \'compose.email.php?special=forward&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Forward as Attachment\', function(){ form.action=\'index.php?asattach=1&forward=Forward\'; form.submit(); }),
		new ContextSeperator(),
		new ContextItem(\'Mark as Read\', function(){ form.action=\'index.php?mark=Mark\'; form.markas.selectedIndex=0; form.submit(); }, isNew.substr(0, 3) != \'new\' && totalChecked == 1),
		new ContextItem(\'Mark as Unread\', function(){ form.action=\'index.php?mark=Mark\'; form.markas.selectedIndex=1; form.submit(); }, isNew.substr(0, 3) == \'new\' && totalChecked == 1),
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
		<input type="submit" class="bginput" name="forward" value="Forward" />&nbsp; or &nbsp;<input type="submit" class="bginput" name="delete" value="Delete" onClick="changeFolderID(); return confirm(\'Are you sure you want to delete the selected messages?\');" />&nbsp; selected</b></span></td>
      </tr>
    </table></td>
</tr>
</table>

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr>
	<td><span class="smallfont">Showing messages $limitlower to $limitupper of $totalmails<br />$pagenav</span></td>
	<td align="right"><span class="smallfont">$deletenote</span></td>
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
		new ContextItem(\'Print\', function(){ window.location = \'read.print.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=\'+msgID; }, totalChecked != 1),
		new ContextSeperator(),
		new ContextItem(\'Reply to Sender\', function(){ window.location = \'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}special=reply&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Reply to All\', function(){ window.location = \'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}special=replyall&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Forward\', function(){ window.location = \'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}special=forward&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Forward as Attachment\', function(){ form.action=\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}asattach=1&forward=Forward\'; form.submit(); }),
		new ContextSeperator(),
		new ContextItem(\'Mark as Read\', function(){ form.action=\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}mark=Mark\'; form.markas.selectedIndex=0; form.submit(); }, isNew.substr(0, 3) != \'new\' && totalChecked == 1),
		new ContextItem(\'Mark as Unread\', function(){ form.action=\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}mark=Mark\'; form.markas.selectedIndex=1; form.submit(); }, isNew.substr(0, 3) == \'new\' && totalChecked == 1),
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
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"forward\\" value=\\"Forward\\" />&nbsp; or &nbsp;<input type=\\"submit\\" class=\\"bginput\\" name=\\"delete\\" value=\\"Delete\\" onClick=\\"changeFolderID(); return confirm(\'Are you sure you want to delete the selected messages?\');\\" />&nbsp; selected</b></span></td>
      </tr>
    </table></td>
</tr>
</table>

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr>
	<td><span class=\\"smallfont\\">Showing messages $limitlower to $limitupper of $totalmails<br />$pagenav</span></td>
	<td align=\\"right\\"><span class=\\"smallfont\\">$deletenote</span></td>
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
  'index_drafts_bit' => 
  array (
    'templategroupid' => '2',
    'user_data' => '<tr class="normalRow">
	<td class="normalBothCell"><span class="normalfont"><a href="compose.email.php?draftid=$draft[draftid]">$mail[subject]</a></span></td>
</tr>
',
    'parsed_data' => '"<tr class=\\"normalRow\\">
	<td class=\\"normalBothCell\\"><span class=\\"normalfont\\"><a href=\\"compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}draftid=$draft[draftid]\\">$mail[subject]</a></span></td>
</tr>
"',
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
<input type="button" class="bginput" value="Move" />&nbsp;&nbsp;
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
<input type=\\"button\\" class=\\"bginput\\" value=\\"Move\\" />&nbsp;&nbsp;
<input type=\\"button\\" class=\\"bginput\\" value=\\"Cancel\\" onClick=\\"window.close();\\" />
</div>
</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'index_header_attach' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell" width="15" nowrap="nowrap"><span class="headerText"><a href="index.php?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=attach"><img src="$skin[images]/paperclip.gif" alt="Has attachments?" border="0" /></a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\" width=\\"15\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><a href=\\"index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=attach\\"><img src=\\"{$GLOBALS[skin][images]}/paperclip.gif\\" alt=\\"Has attachments?\\" border=\\"0\\" /></a></span></th>
"',
  ),
  'index_header_datetime' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell"><span class="headerText"><b><a href="index.php?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=dateline"><span class="normalfonttablehead">Received</b></span>$sortimages[dateline]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\"><span class=\\"headerText\\"><b><a href=\\"index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=dateline\\"><span class=\\"normalfonttablehead\\">Received</b></span>$sortimages[dateline]</a></span></th>
"',
  ),
  'index_header_datetime_sentitems' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell"><span class="headerText"><b><a href="index.php?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=dateline"><span class="normalfonttablehead">Sent</b></span>$sortimages[dateline]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\"><span class=\\"headerText\\"><b><a href=\\"index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=dateline\\"><span class=\\"normalfonttablehead\\">Sent</b></span>$sortimages[dateline]</a></span></th>
"',
  ),
  'index_header_from' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell"><span class="headerText"><a href="index.php?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=name"><span class="normalfonttablehead"><b>From</b></span>$sortimages[name]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\"><span class=\\"headerText\\"><a href=\\"index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=name\\"><span class=\\"normalfonttablehead\\"><b>From</b></span>$sortimages[name]</a></span></th>
"',
  ),
  'index_header_priority' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell" width="15" nowrap="nowrap"><span class="headerText"><a href="index.php?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=priority"><img src="$skin[images]/prio_high.gif" alt="Important?" border="0" /></a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\" width=\\"15\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><a href=\\"index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=priority\\"><img src=\\"{$GLOBALS[skin][images]}/prio_high.gif\\" alt=\\"Important?\\" border=\\"0\\" /></a></span></th>
"',
  ),
  'index_header_size' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell" nowrap="nowrap"><span class="headerText"><b><a href="index.php?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=size"><span class="normalfonttablehead">Size</b></span>$sortimages[size]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><b><a href=\\"index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=size\\"><span class=\\"normalfonttablehead\\">Size</b></span>$sortimages[size]</a></span></th>
"',
  ),
  'index_header_subject' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell"><span class="headerText"><a href="index.php?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=subject"><span class="normalfonttablehead"><b>Message Subject</b></span>$sortimages[subject]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\"><span class=\\"headerText\\"><a href=\\"index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=subject\\"><span class=\\"normalfonttablehead\\"><b>Message Subject</b></span>$sortimages[subject]</a></span></th>
"',
  ),
  'index_nomails' => 
  array (
    'templategroupid' => '2',
    'user_data' => '<tr style="normalRow">
	<td class="normalBothCell" align="center" colspan="10"><span class="normalfont">No messages in this folder!</span></td>
</tr>
',
    'parsed_data' => '"<tr style=\\"normalRow\\">
	<td class=\\"normalBothCell\\" align=\\"center\\" colspan=\\"10\\"><span class=\\"normalfont\\">No messages in this folder!</span></td>
</tr>
"',
  ),
  'index_spacegauge' => 
  array (
    'templategroupid' => '2',
    'user_data' => '<tr class="highRow">
	<td class="highBothCell"><span class="normalfont">You are using $spacepercent% of your storage. ({$_mailmb}MB / $hiveuser[maxmb]MB).</span><br /><br />
	<div style="width: $spacepercent%; background: $skin[tableheadbgcolor];" class="headerBothCell"><span class="smallfont">&nbsp;</span></div>
	<table border="0" cellspacing="1" width="100%">
		<tr>
			<td width="25%" align="left"><span class="smallfont">0%</span></td>
			<td width="25%" align="left"><span class="smallfont">25%</span></td>
			<td width="24%" align="left"><span class="smallfont">50%</span></td>
			<td width="25%" align="left"><span class="smallfont">75%</span></td>
			<td width="1%" align="right"><span class="smallfont">100%</span></td>
		</tr>
	</table></td>
</tr>',
    'parsed_data' => '"<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\"><span class=\\"normalfont\\">You are using $spacepercent% of your storage. ({$_mailmb}MB / $hiveuser[maxmb]MB).</span><br /><br />
	<div style=\\"width: $spacepercent%; background: {$GLOBALS[skin][tableheadbgcolor]};\\" class=\\"headerBothCell\\"><span class=\\"smallfont\\">&nbsp;</span></div>
	<table border=\\"0\\" cellspacing=\\"1\\" width=\\"100%\\">
		<tr>
			<td width=\\"25%\\" align=\\"left\\"><span class=\\"smallfont\\">0%</span></td>
			<td width=\\"25%\\" align=\\"left\\"><span class=\\"smallfont\\">25%</span></td>
			<td width=\\"24%\\" align=\\"left\\"><span class=\\"smallfont\\">50%</span></td>
			<td width=\\"25%\\" align=\\"left\\"><span class=\\"smallfont\\">75%</span></td>
			<td width=\\"1%\\" align=\\"right\\"><span class=\\"smallfont\\">100%</span></td>
		</tr>
	</table></td>
</tr>"',
  ),
  'index_spacewarning' => 
  array (
    'templategroupid' => '2',
    'user_data' => '<tr class="highRow">
	<td class="highBothCell"><b><span class="normalfont">You are currently using {$_mailmb}MB of data, passing the limit of $hiveuser[maxmb]MB.<br />You will not be able to send or receive any further messages until you delete older messages.</b></span></td>
</tr>',
    'parsed_data' => '"<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\"><b><span class=\\"normalfont\\">You are currently using {$_mailmb}MB of data, passing the limit of $hiveuser[maxmb]MB.<br />You will not be able to send or receive any further messages until you delete older messages.</b></span></td>
</tr>"',
  ),
  'index_topbox' => 
  array (
    'templategroupid' => '2',
    'user_data' => '<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Welcome back $hiveuser[realname]</b></span></th>
</tr>
$space
$unreads
$poperror
</table>
<br />
',
    'parsed_data' => '"<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Welcome back $hiveuser[realname]</b></span></th>
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
	<td class="highBothCell"><span class="normalfont">$unreads</span></td>
</tr>',
    'parsed_data' => '"<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\"><span class=\\"normalfont\\">$unreads</span></td>
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
			<td align="left"><input type="text" name="username" class="bginput" /></td>
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
			<td align="left"><input type="text" name="username" class="bginput" /></td>
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
			<td align="left"><input type="text" name="username" class="bginput" /></td>
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
			<td align=\\"left\\"><input type=\\"text\\" name=\\"username\\" class=\\"bginput\\" /></td>
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
			<td align=\\"left\\"><input type=\\"text\\" name=\\"username\\" class=\\"bginput\\" /></td>
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
") : \'\')."
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
			<td align=\\"left\\"><input type=\\"text\\" name=\\"username\\" class=\\"bginput\\" /></td>
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
<input type="hidden" name="do" value="update" />
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
<input type=\\"hidden\\" name=\\"do\\" value=\\"update\\" />
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
  'mailbit' => 
  array (
    'templategroupid' => '16',
    'user_data' => '<tr class="normalRow" $mail[unreadstyle] onSelectStart="return false;" id="row$mail[messageid]" onDblClick="window.location = \'read.email.php?messageid=$mail[messageid]\';">
	<td class="normalLeftCell"><img src="$skin[images]/messages/$mail[image].gif" alt="$skin[images]/$mail[image].gif" /></td>
$columns	<td class="normalRightCell"><input type="checkbox" name="mails[$mail[messageid]]" id="mails$mail[messageid]" value="yes" onClick="this.checked = !this.checked; checkMail($mail[messageid], 0, 0, 1); this.checked = !this.checked;" /></td>
</tr>',
    'parsed_data' => '"<tr class=\\"normalRow\\" $mail[unreadstyle] onSelectStart=\\"return false;\\" id=\\"row$mail[messageid]\\" onDblClick=\\"window.location = \'read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\';\\">
	<td class=\\"normalLeftCell\\"><img src=\\"{$GLOBALS[skin][images]}/messages/$mail[image].gif\\" alt=\\"{$GLOBALS[skin][images]}/$mail[image].gif\\" /></td>
$columns	<td class=\\"normalRightCell\\"><input type=\\"checkbox\\" name=\\"mails[$mail[messageid]]\\" id=\\"mails$mail[messageid]\\" value=\\"yes\\" onClick=\\"this.checked = !this.checked; checkMail($mail[messageid], 0, 0, 1); this.checked = !this.checked;\\" /></td>
</tr>"',
  ),
  'mailbit_attach' => 
  array (
    'templategroupid' => '16',
    'user_data' => '	<td class="$bgcolors[attach]Cell" onContextMenu="checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;" onClick="checkMail($mail[messageid], 0, 1);">$mail[attach]</td>
',
    'parsed_data' => '"	<td class=\\"$bgcolors[attach]Cell\\" onContextMenu=\\"checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;\\" onClick=\\"checkMail($mail[messageid], 0, 1);\\">$mail[attach]</td>
"',
  ),
  'mailbit_datetime' => 
  array (
    'templategroupid' => '16',
    'user_data' => '	<td class="$bgcolors[dateline]Cell" nowrap="nowrap" width="20%" align="center" onContextMenu="checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;" onClick="checkMail($mail[messageid], 0, 1);"><span class="smallfont">$mail[date] <span class="timecolor">$mail[time]</span></span></td>
',
    'parsed_data' => '"	<td class=\\"$bgcolors[dateline]Cell\\" nowrap=\\"nowrap\\" width=\\"20%\\" align=\\"center\\" onContextMenu=\\"checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;\\" onClick=\\"checkMail($mail[messageid], 0, 1);\\"><span class=\\"smallfont\\">$mail[date] <span class=\\"timecolor\\">$mail[time]</span></span></td>
"',
  ),
  'mailbit_from' => 
  array (
    'templategroupid' => '16',
    'user_data' => '	<td class="$bgcolors[name]Cell" width="25%" align="left" onContextMenu="checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;" onClick="checkMail($mail[messageid], 0, 1);"><span class="normalfont"><a href="compose.email.php?email=$mail[link]">$mail[fromname]</a></span></td>
',
    'parsed_data' => '"	<td class=\\"$bgcolors[name]Cell\\" width=\\"25%\\" align=\\"left\\" onContextMenu=\\"checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;\\" onClick=\\"checkMail($mail[messageid], 0, 1);\\"><span class=\\"normalfont\\"><a href=\\"compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}email=$mail[link]\\">$mail[fromname]</a></span></td>
"',
  ),
  'mailbit_priority' => 
  array (
    'templategroupid' => '16',
    'user_data' => '	<td class="$bgcolors[priority]Cell" onContextMenu="checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;" onClick="checkMail($mail[messageid], 0, 1);">$mail[priority]</td>
',
    'parsed_data' => '"	<td class=\\"$bgcolors[priority]Cell\\" onContextMenu=\\"checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;\\" onClick=\\"checkMail($mail[messageid], 0, 1);\\">$mail[priority]</td>
"',
  ),
  'mailbit_size' => 
  array (
    'templategroupid' => '16',
    'user_data' => '	<td class="$bgcolors[size]Cell" width="20%" align="center" onContextMenu="checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;" onClick="checkMail($mail[messageid], 0, 1);"><span class="smallfont">$mail[kbsize]KB</span></td>
',
    'parsed_data' => '"	<td class=\\"$bgcolors[size]Cell\\" width=\\"20%\\" align=\\"center\\" onContextMenu=\\"checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;\\" onClick=\\"checkMail($mail[messageid], 0, 1);\\"><span class=\\"smallfont\\">$mail[kbsize]KB</span></td>
"',
  ),
  'mailbit_subject' => 
  array (
    'templategroupid' => '16',
    'user_data' => '	<td class="$bgcolors[subject]Cell" align="left" width="55%" onContextMenu="checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;" onClick="checkMail($mail[messageid], 0, 1);"><span class="normalfont"><a href="read.email.php?messageid=$mail[messageid]"$mail[linkstyle]>$mail[subject]</a></span></td>
',
    'parsed_data' => '"	<td class=\\"$bgcolors[subject]Cell\\" align=\\"left\\" width=\\"55%\\" onContextMenu=\\"checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;\\" onClick=\\"checkMail($mail[messageid], 0, 1);\\"><span class=\\"normalfont\\"><a href=\\"read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\\"$mail[linkstyle]>$mail[subject]</a></span></td>
"',
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
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Signature Settings</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Signature:</b></span>
	<br />
	<span class="smallfont">A signature that may be added to your outgoing messages.</span></td>
	<td class="normalRightCell" width="40%"><textarea name="signature" rows="8" cols="60">$hiveuser[signature]</textarea></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Automatically add signature:</b></span>
	<br />
	<span class="smallfont">If this is turned on, the signature you specify above will automatically be added<br />to your emails before they are sent.<br />Otherwise, you will have the option to add the signature manually.</span></td>
	<td class="highRightCell" width="40%"><span class="normalfont"><input type="radio" name="autoaddsig" value="2" id="autoaddsigon" $autoaddsigon /> <label for="autoaddsigon">Yes</label><br /><input type="radio" name="autoaddsig" value="1" id="autoaddsigonly" $autoaddsigonly /> <label for="autoaddsigonly">Only when not replying</label><br /><input type="radio" name="autoaddsig" value="0" id="autoaddsigoff" $autoaddsigoff /> <label for="autoaddsigoff">No</label></span></td>
</tr>
<!-- +++++++++++++++++++++++++++++++++++++ -->
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
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Default reply-to address:</b></span>
	<br />
	<span class="smallfont">The default address the reply-to field contains.</span></td>
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
<input type=\\"hidden\\" name=\\"do\\" value=\\"update\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Signature Settings</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Signature:</b></span>
	<br />
	<span class=\\"smallfont\\">A signature that may be added to your outgoing messages.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><textarea name=\\"signature\\" rows=\\"8\\" cols=\\"60\\">$hiveuser[signature]</textarea></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Automatically add signature:</b></span>
	<br />
	<span class=\\"smallfont\\">If this is turned on, the signature you specify above will automatically be added<br />to your emails before they are sent.<br />Otherwise, you will have the option to add the signature manually.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"autoaddsig\\" value=\\"2\\" id=\\"autoaddsigon\\" $autoaddsigon /> <label for=\\"autoaddsigon\\">Yes</label><br /><input type=\\"radio\\" name=\\"autoaddsig\\" value=\\"1\\" id=\\"autoaddsigonly\\" $autoaddsigonly /> <label for=\\"autoaddsigonly\\">Only when not replying</label><br /><input type=\\"radio\\" name=\\"autoaddsig\\" value=\\"0\\" id=\\"autoaddsigoff\\" $autoaddsigoff /> <label for=\\"autoaddsigoff\\">No</label></span></td>
</tr>
<!-- CyKuH [WTN] -->
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
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Default reply-to address:</b></span>
	<br />
	<span class=\\"smallfont\\">The default address the reply-to field contains.</span></td>
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
<input type="hidden" name="do" value="update" />
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
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Message columns:</b></span>
	<br />
	<span class="smallfont">These are the columns that show up when viewing the list of messages.<br />At least one column must be selected.</span></td>
	<td class="normalRightCell" width="40%">
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
<input type=\\"hidden\\" name=\\"do\\" value=\\"update\\" />
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
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Message columns:</b></span>
	<br />
	<span class=\\"smallfont\\">These are the columns that show up when viewing the list of messages.<br />At least one column must be selected.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\">
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

<form action="options.general.php" method="post">
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
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="playsound" value="1" $playsoundon />Yes<br /><input type="radio" name="playsound" value="0" $playsoundoff />No</span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Auto-forwarding:</b></span>
	<br />
	<span class="smallfont">Emails that you receive will automatically be forwaded to this address.<br />Set this to nothing to disable the feature.</span></td>
	<td class="highRightCell" width="40%"><input type="text" class="bginput" name="forward" value="$hiveuser[forward]" size="40" /></td>
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

<form action=\\"options.general.php{$GLOBALS[session_url]}\\" method=\\"post\\">
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
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"playsound\\" value=\\"1\\" $playsoundon />Yes<br /><input type=\\"radio\\" name=\\"playsound\\" value=\\"0\\" $playsoundoff />No</span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Auto-forwarding:</b></span>
	<br />
	<span class=\\"smallfont\\">Emails that you receive will automatically be forwaded to this address.<br />Set this to nothing to disable the feature.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"forward\\" value=\\"$hiveuser[forward]\\" size=\\"40\\" /></td>
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
	<th class="headerBothCell"><a href="options.compose.php"><span class="normalfonttablehead"><b>Compose Options and Signature</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Settings that pertain to composing messages, such as using the WYSIWYG editor, including original message, and more.</span></td>
</tr>
</table>

		</td>
	</tr>
<%if $hiveuser[canrule] or $hiveuser[canpop] %>
	<tr>
<%if $hiveuser[canrule] %>
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
	</tr>
<%endif%>
<%if $hiveuser[canfolder] %>
	<tr>
		<td colspan="2" style="padding: 12px; padding-bottom: 0px;">

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><a href="folders.list.php"><span class="normalfonttablehead"><b>Folders Management</b></span></a></th>
</tr>
<tr class="highRow" style="height: 33px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Create, rename, delete or empty your mail folders.</span></td>
</tr>
</table>

		</td>
	</tr>
<%endif%>
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
	<th class=\\"headerBothCell\\"><a href=\\"options.compose.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Compose Options and Signature</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Settings that pertain to composing messages, such as using the WYSIWYG editor, including original message, and more.</span></td>
</tr>
</table>

		</td>
	</tr>
".(($hiveuser[canrule] or $hiveuser[canpop] ) ? ("
	<tr>
".(($hiveuser[canrule] ) ? ("
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
	</tr>
") : \'\')."
".(($hiveuser[canfolder] ) ? ("
	<tr>
		<td colspan=\\"2\\" style=\\"padding: 12px; padding-bottom: 0px;\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"folders.list.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Folders Management</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 33px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Create, rename, delete or empty your mail folders.</span></td>
</tr>
</table>

		</td>
	</tr>
") : \'\')."
</table>

$GLOBALS[footer]

</body>
</html>"',
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
<input type="hidden" name="do" value="update" />
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
<input type="hidden" name="do" value="update" />
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
<input type=\\"hidden\\" name=\\"do\\" value=\\"update\\" />
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

<!-- CyKuH [WTN] --><br />

<form action=\\"options.password.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"do\\" value=\\"update\\" />
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
<input type="hidden" name="do" value="change" />

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
<input type=\\"hidden\\" name=\\"do\\" value=\\"change\\" />

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
<input type="hidden" name="do" value="update" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Personal Information</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Your name:</b></span>
	<br />
	<span class="smallfont">This name will be sent with all your outgoing emails.</span></td>
	<td class="normalRightCell" width="40%"><input type="text" class="bginput" name="realname" value="$hiveuser[realname]" size="40" /></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Secondary email address:</b></span><br /><span class="smallfont">Optional.</span></td>
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
<input type=\\"hidden\\" name=\\"do\\" value=\\"update\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Personal Information</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Your name:</b></span>
	<br />
	<span class=\\"smallfont\\">This name will be sent with all your outgoing emails.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"realname\\" value=\\"$hiveuser[realname]\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secondary email address:</b></span><br /><span class=\\"smallfont\\">Optional.</span></td>
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
<input type="hidden" name="do" value="update" />

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
<input type=\\"hidden\\" name=\\"do\\" value=\\"update\\" />

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
  'options_timezone' => 
  array (
    'templategroupid' => '13',
    'user_data' => '	<select name="$fieldname">
		<option value="-12" $tzsel[n120]>(GMT -12:00 hours) $tztime[n120]</option>
		<option value="-11" $tzsel[n110]>(GMT -11:00 hours) $tztime[n110]</option>
		<option value="-10" $tzsel[n100]>(GMT -10:00 hours) $tztime[n100]</option>
		<option value="-9" $tzsel[n90]>(GMT -9:00 hours) $tztime[n90]</option>
		<option value="-8" $tzsel[n80]>(GMT -8:00 hours) $tztime[n80]</option>
		<option value="-7" $tzsel[n70]>(GMT -7:00 hours) $tztime[n70]</option>
		<option value="-6" $tzsel[n60]>(GMT -6:00 hours) $tztime[n60]</option>
		<option value="-5" $tzsel[n50]>(GMT -5:00 hours) $tztime[n50]</option>
		<option value="-4" $tzsel[n40]>(GMT -4:00 hours) $tztime[n40]</option>
		<option value="-3.5" $tzsel[n35]>(GMT -3:30 hours) $tztime[n35]</option>
		<option value="-3" $tzsel[n30]>(GMT -3:00 hours) $tztime[n30]</option>
		<option value="-2" $tzsel[n20]>(GMT -2:00 hours) $tztime[n20]</option>
		<option value="-1" $tzsel[n10]>(GMT -1:00 hours) $tztime[n10]</option>
		<option value="0" $tzsel[0]>(GMT) $tztime[0]</option>
		<option value="1" $tzsel[10]>(GMT +1:00 hours) $tztime[10]</option>
		<option value="2" $tzsel[20]>(GMT +2:00 hours) $tztime[20]</option>
		<option value="3" $tzsel[30]>(GMT +3:00 hours) $tztime[30]</option>
		<option value="3.5" $tzsel[35]>(GMT +3:30 hours) $tztime[35]</option>
		<option value="4" $tzsel[40]>(GMT +4:00 hours) $tztime[40]</option>
		<option value="4.5" $tzsel[45]>(GMT +4:30 hours) $tztime[45]</option>
		<option value="5" $tzsel[50]>(GMT +5:00 hours) $tztime[50]</option>
		<option value="5.5" $tzsel[55]>(GMT +5:30 hours) $tztime[55]</option>
		<option value="6" $tzsel[60]>(GMT +6:00 hours) $tztime[60]</option>
		<option value="7" $tzsel[70]>(GMT +7:00 hours) $tztime[70]</option>
		<option value="8" $tzsel[80]>(GMT +8:00 hours) $tztime[80]</option>
		<option value="9" $tzsel[90]>(GMT +9:00 hours) $tztime[90]</option>
		<option value="9.5" $tzsel[95]>(GMT +9:30 hours) $tztime[95]</option>
		<option value="10" $tzsel[100]>(GMT +10:00 hours) $tztime[100]</option>
		<option value="11" $tzsel[110]>(GMT +11:00 hours) $tztime[110]</option>
		<option value="12" $tzsel[120]>(GMT +12:00 hours)  $tztime[120]</option>
	</select>',
    'parsed_data' => '"	<select name=\\"$fieldname\\">
		<option value=\\"-12\\" $tzsel[n120]>(GMT -12:00 hours) $tztime[n120]</option>
		<option value=\\"-11\\" $tzsel[n110]>(GMT -11:00 hours) $tztime[n110]</option>
		<option value=\\"-10\\" $tzsel[n100]>(GMT -10:00 hours) $tztime[n100]</option>
		<option value=\\"-9\\" $tzsel[n90]>(GMT -9:00 hours) $tztime[n90]</option>
		<option value=\\"-8\\" $tzsel[n80]>(GMT -8:00 hours) $tztime[n80]</option>
		<option value=\\"-7\\" $tzsel[n70]>(GMT -7:00 hours) $tztime[n70]</option>
		<option value=\\"-6\\" $tzsel[n60]>(GMT -6:00 hours) $tztime[n60]</option>
		<option value=\\"-5\\" $tzsel[n50]>(GMT -5:00 hours) $tztime[n50]</option>
		<option value=\\"-4\\" $tzsel[n40]>(GMT -4:00 hours) $tztime[n40]</option>
		<option value=\\"-3.5\\" $tzsel[n35]>(GMT -3:30 hours) $tztime[n35]</option>
		<option value=\\"-3\\" $tzsel[n30]>(GMT -3:00 hours) $tztime[n30]</option>
		<option value=\\"-2\\" $tzsel[n20]>(GMT -2:00 hours) $tztime[n20]</option>
		<option value=\\"-1\\" $tzsel[n10]>(GMT -1:00 hours) $tztime[n10]</option>
		<option value=\\"0\\" $tzsel[0]>(GMT) $tztime[0]</option>
		<option value=\\"1\\" $tzsel[10]>(GMT +1:00 hours) $tztime[10]</option>
		<option value=\\"2\\" $tzsel[20]>(GMT +2:00 hours) $tztime[20]</option>
		<option value=\\"3\\" $tzsel[30]>(GMT +3:00 hours) $tztime[30]</option>
		<option value=\\"3.5\\" $tzsel[35]>(GMT +3:30 hours) $tztime[35]</option>
		<option value=\\"4\\" $tzsel[40]>(GMT +4:00 hours) $tztime[40]</option>
		<option value=\\"4.5\\" $tzsel[45]>(GMT +4:30 hours) $tztime[45]</option>
		<option value=\\"5\\" $tzsel[50]>(GMT +5:00 hours) $tztime[50]</option>
		<option value=\\"5.5\\" $tzsel[55]>(GMT +5:30 hours) $tztime[55]</option>
		<option value=\\"6\\" $tzsel[60]>(GMT +6:00 hours) $tztime[60]</option>
		<option value=\\"7\\" $tzsel[70]>(GMT +7:00 hours) $tztime[70]</option>
		<option value=\\"8\\" $tzsel[80]>(GMT +8:00 hours) $tztime[80]</option>
		<option value=\\"9\\" $tzsel[90]>(GMT +9:00 hours) $tztime[90]</option>
		<option value=\\"9.5\\" $tzsel[95]>(GMT +9:30 hours) $tztime[95]</option>
		<option value=\\"10\\" $tzsel[100]>(GMT +10:00 hours) $tztime[100]</option>
		<option value=\\"11\\" $tzsel[110]>(GMT +11:00 hours) $tztime[110]</option>
		<option value=\\"12\\" $tzsel[120]>(GMT +12:00 hours)  $tztime[120]</option>
	</select>"',
  ),
  'pagenav' => 
  array (
    'templategroupid' => '11',
    'user_data' => 'Pages ($totalpages): <b>$firstlink $prevlink $pagenav $nextlink $lastlink</b>',
    'parsed_data' => '"Pages ($totalpages): <b>$firstlink $prevlink $pagenav $nextlink $lastlink</b>"',
  ),
  'pagenav_curpage' => 
  array (
    'templategroupid' => '11',
    'user_data' => ' <font size="2">[$curpage]</font> ',
    'parsed_data' => '" <font size=\\"2\\">[$curpage]</font> "',
  ),
  'pagenav_firstlink' => 
  array (
    'templategroupid' => '11',
    'user_data' => ' <a href="$address&pagenumber=$curpage" title="first page">&laquo; First</a> ... ',
    'parsed_data' => '" <a href=\\"$address&pagenumber=$curpage\\" title=\\"first page\\">&laquo; First</a> ... "',
  ),
  'pagenav_lastlink' => 
  array (
    'templategroupid' => '11',
    'user_data' => '... <a href="$address&pagenumber=$curpage" title="last page">Last &raquo;</a>',
    'parsed_data' => '"... <a href=\\"$address&pagenumber=$curpage\\" title=\\"last page\\">Last &raquo;</a>"',
  ),
  'pagenav_nextlink' => 
  array (
    'templategroupid' => '11',
    'user_data' => '<a href="$address&pagenumber=$nextpage" title="next page">&raquo;</a>',
    'parsed_data' => '"<a href=\\"$address&pagenumber=$nextpage\\" title=\\"next page\\">&raquo;</a>"',
  ),
  'pagenav_pagelink' => 
  array (
    'templategroupid' => '11',
    'user_data' => ' <a href="$address&pagenumber=$curpage">$curpage</a> ',
    'parsed_data' => '" <a href=\\"$address&pagenumber=$curpage\\">$curpage</a> "',
  ),
  'pagenav_prevlink' => 
  array (
    'templategroupid' => '11',
    'user_data' => ' <a href="$address&pagenumber=$prevpage" title="previous page">&laquo;</a> ',
    'parsed_data' => '" <a href=\\"$address&pagenumber=$prevpage\\" title=\\"previous page\\">&laquo;</a> "',
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
	<th class="headerRightCell"><span class="normalfonttablehead"><b>Password</b></span></th>
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
	<th class="headerRightCell"><span class="normalfonttablehead"><b>Password</b></span></th>
</tr>
<tr class="highRow" valign="top">
	<td class="highLeftCell"><span class="normalfont">New Account:</span></td>
	<td class="highCell" align="center"><input type="text" class="bginput" name="serverinfo[0][server]" value="" size="20" /></td>
	<td class="highCell" align="center"><input type="text" class="bginput" name="serverinfo[0][port]" value="110" size="20" /></td>
	<td class="highCell" align="center"><input type="text" class="bginput" name="serverinfo[0][username]" value="" size="20" /></td>
	<td class="highRightCell" align="center"><input type="password" class="bginput" name="serverinfo[0][password]" value="" size="20" /></td>
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
	<th class=\\"headerRightCell\\"><span class=\\"normalfonttablehead\\"><b>Password</b></span></th>
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
	<th class=\\"headerRightCell\\"><span class=\\"normalfonttablehead\\"><b>Password</b></span></th>
</tr>
<tr class=\\"highRow\\" valign=\\"top\\">
	<td class=\\"highLeftCell\\"><span class=\\"normalfont\\">New Account:</span></td>
	<td class=\\"highCell\\" align=\\"center\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"serverinfo[0][server]\\" value=\\"\\" size=\\"20\\" /></td>
	<td class=\\"highCell\\" align=\\"center\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"serverinfo[0][port]\\" value=\\"110\\" size=\\"20\\" /></td>
	<td class=\\"highCell\\" align=\\"center\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"serverinfo[0][username]\\" value=\\"\\" size=\\"20\\" /></td>
	<td class=\\"highRightCell\\" align=\\"center\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"serverinfo[0][password]\\" value=\\"\\" size=\\"20\\" /></td>
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
  'poperrors' => 
  array (
    'templategroupid' => '15',
    'user_data' => '<tr class="highRow">
	<td class="highBothCell"><span class="normalfont">The following errors occured while trying to connect to your POP accounts:<br />$poperror</span></td>
</tr>',
    'parsed_data' => '"<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\"><span class=\\"normalfont\\">The following errors occured while trying to connect to your POP accounts:<br />$poperror</span></td>
</tr>"',
  ),
  'pop_accountbit' => 
  array (
    'templategroupid' => '15',
    'user_data' => '<tr class="$class[name]Row" valign="middle">
	<td class="$class[name]LeftCell" valign="top"><span class="normalfont">$pop[server]:</span><span class="smallfont"><br />[<a href="pop.delete.php?popid=$pop[popid]" onClick="return confirm(\'Are you sure you want to remove this account?\');">remove</a>]</span></td>
	<td class="$class[name]Cell" align="center"><input type="text" class="bginput" name="serverinfo[$pop[popid]][server]" value="$pop[server]" size="20" /></td>
	<td class="$class[name]Cell" align="center"><input type="text" class="bginput" name="serverinfo[$pop[popid]][port]" value="$pop[port]" size="20" /></td>
	<td class="$class[name]Cell" align="center"><input type="text" class="bginput" name="serverinfo[$pop[popid]][username]" value="$pop[username]" size="20" /></td>
	<td class="$class[name]RightCell" align="center"><input type="password" class="bginput" name="serverinfo[$pop[popid]][password]" value="" size="20" /></td>
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
	<td class=\\"$class[name]RightCell\\" align=\\"center\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"serverinfo[$pop[popid]][password]\\" value=\\"\\" size=\\"20\\" /></td>
</tr>
<tr class=\\"$class[name]Row\\" valign=\\"top\\">
	<td class=\\"$class[name]BothCell\\" colspan=\\"7\\"><span class=\\"normalfont\\"><input type=\\"checkbox\\" name=\\"delete[$pop[popid]]\\" id=\\"delete$pop[popid]\\" value=\\"yes\\" $deletechecked /> <label for=\\"delete$pop[popid]\\">Delete mails once received.</label><br /><input type=\\"checkbox\\" name=\\"active[$pop[popid]]\\" id=\\"active$pop[popid]\\" value=\\"yes\\" $activechecked /> <label for=\\"active$pop[popid]\\">Account active.</label></span></td>
</tr>
"',
  ),
  'pop_nopops' => 
  array (
    'templategroupid' => '15',
    'user_data' => '<tr class="highRow">
	<td colspan="10" class="highBothCell" align="center"><span class="normalfont">You currently have no POP accounts set up.</span></td>
</tr>
',
    'parsed_data' => '"<tr class=\\"highRow\\">
	<td colspan=\\"10\\" class=\\"highBothCell\\" align=\\"center\\"><span class=\\"normalfont\\">You currently have no POP accounts set up.</span></td>
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
	<td class="$afterattach[second]RightCell" align="left" width="90%"><span class="normalfont"><a href="read.markas.php?messageid=$mail[messageid]&markas=$markas&back=message">Mark message as $markas</a> or <a href="read.source.php?messageid=$mail[messageid]">view message source</a></span></td>
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
	<td align="right" valign="top"><span class="smallfont">$deletenote</span></td>
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
	<td class=\\"$afterattach[second]RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\"><a href=\\"read.markas.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]&markas=$markas&back=message\\">Mark message as $markas</a> or <a href=\\"read.source.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\\">view message source</a></span></td>
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
	<td align=\\"right\\" valign=\\"top\\"><span class=\\"smallfont\\">$deletenote</span></td>
</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'read_attachments' => 
  array (
    'templategroupid' => '6',
    'user_data' => '<tr class="$afterto[first]Row">
	<td class="$afterto[first]LeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>Attachments:</b></span></td>
	<td class="$afterto[first]RightCell" align="left" width="90%"><span class="normalfont">$attachlist</span></td>
</tr>',
    'parsed_data' => '"<tr class=\\"$afterto[first]Row\\">
	<td class=\\"$afterto[first]LeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>Attachments:</b></span></td>
	<td class=\\"$afterto[first]RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\">$attachlist</span></td>
</tr>"',
  ),
  'read_attachments_bit' => 
  array (
    'templategroupid' => '6',
    'user_data' => '$filename ({$filesize}KB)&nbsp;&nbsp;[<a href="read.attachment.php?messageid=$messageid&attachnum=$attachnum">download</a>]<br />
',
    'parsed_data' => '"$filename ({$filesize}KB)&nbsp;&nbsp;[<a href=\\"read.attachment.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$messageid&attachnum=$attachnum\\">download</a>]<br />
"',
  ),
  'read_cc' => 
  array (
    'templategroupid' => '6',
    'user_data' => '<tr class="$afterto[second]Row">
	<td class="$afterto[second]LeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>CC:</b></span></td>
	<td class="$afterto[second]RightCell" align="left" width="90%"><span class="smallfont">$cclist</span></td>
</tr>',
    'parsed_data' => '"<tr class=\\"$afterto[second]Row\\">
	<td class=\\"$afterto[second]LeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>CC:</b></span></td>
	<td class=\\"$afterto[second]RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"smallfont\\">$cclist</span></td>
</tr>"',
  ),
  'read_header' => 
  array (
    'templategroupid' => '6',
    'user_data' => '<tr class="${headerbgcolor}Row">
	<td class="${headerbgcolor}LeftCell" valign="top" align="left" width="10%" nowrap="nowrap"><span class="normalfont"><b>${headername}:</b></span></td>
	<td class="${headerbgcolor}RightCell" align="left" width="90%"><span class="normalfont">${headerinfo}</span></td>
</tr>',
    'parsed_data' => '"<tr class=\\"${headerbgcolor}Row\\">
	<td class=\\"${headerbgcolor}LeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\"><b>${headername}:</b></span></td>
	<td class=\\"${headerbgcolor}RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\">${headerinfo}</span></td>
</tr>"',
  ),
  'read_nextnewest' => 
  array (
    'templategroupid' => '6',
    'user_data' => '&nbsp; <a href="read.email.php?messageid=$nextnewestid">Next Message &raquo;</a>',
    'parsed_data' => '"&nbsp; <a href=\\"read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$nextnewestid\\">Next Message &raquo;</a>"',
  ),
  'read_nextoldest' => 
  array (
    'templategroupid' => '6',
    'user_data' => '<a href="read.email.php?messageid=$nextoldestid">&laquo; Previous Message</a> ',
    'parsed_data' => '"<a href=\\"read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$nextoldestid\\">&laquo; Previous Message</a> "',
  ),
  'read_readreceipt' => 
  array (
    'templategroupid' => '6',
    'user_data' => 'This is a receipt for the email you sent to $hiveuser[username]$domainname at $timesent.

This receipt verifies that the message was displayed on the recipient\'s computer at $timeread.',
    'parsed_data' => '"This is a receipt for the email you sent to $hiveuser[username]$domainname at $timesent.

This receipt verifies that the message was displayed on the recipient\'s computer at $timeread."',
  ),
  'redirect' => 
  array (
    'templategroupid' => '1',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname</title>
<meta http-equiv="Refresh" content="1; URL=$newurl">
$css
</head>
<body>
$header

<table cellpadding="4" cellspacing="0" class="normalTable" width="650" align="center" style="height: 100px;">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>You are being redirected...</b></span></th>
</tr>
<tr class="highRow" style="height: 100%;">
	<td class="highBothCell" valign="top" style="padding: 15px;">
		<span class="normalfont">$message</span>
		<br /><br />
		<span class="smallfont"><a href="$newurl">Click here if you do not want to wait any longer.<br />(Or if your browser does not automatically redirect you.)</a></span>
	</td>
</tr>
</table>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname</title>
<meta http-equiv=\\"Refresh\\" content=\\"1; URL=$newurl\\">
$GLOBALS[css]
</head>
<body>
$GLOBALS[header]

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"650\\" align=\\"center\\" style=\\"height: 100px;\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>You are being redirected...</b></span></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 100%;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\" style=\\"padding: 15px;\\">
		<span class=\\"normalfont\\">$message</span>
		<br /><br />
		<span class=\\"smallfont\\"><a href=\\"$newurl\\">Click here if you do not want to wait any longer.<br />(Or if your browser does not automatically redirect you.)</a></span>
	</td>
</tr>
</table>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'redirect_addbook_addentries' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The contact has been added. You will now be taken back to your address book.',
    'parsed_data' => '"The contact has been added. You will now be taken back to your address book."',
  ),
  'redirect_addbook_deleteentries' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The selected contact(s) have been deleted. You will now be taken back to your address book.',
    'parsed_data' => '"The selected contact(s) have been deleted. You will now be taken back to your address book."',
  ),
  'redirect_addbook_editentries' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The contact(s) have been updated. You will now be taken back to your address book.',
    'parsed_data' => '"The contact(s) have been updated. You will now be taken back to your address book."',
  ),
  'redirect_addbook_quickadd' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The sender has been added to your address book. You will now be returned to the message.',
    'parsed_data' => '"The sender has been added to your address book. You will now be returned to the message."',
  ),
  'redirect_blocked' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The $block was successfully blocked.',
    'parsed_data' => '"The $block was successfully blocked."',
  ),
  'redirect_blockedupdated' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Your list of blocked addresses has been updated.',
    'parsed_data' => '"Your list of blocked addresses has been updated."',
  ),
  'redirect_draftdeleted' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The draft was successfully deleted.',
    'parsed_data' => '"The draft was successfully deleted."',
  ),
  'redirect_draftsaved' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The message was saved as draft and you will be able to complete it later.<br />
Please note that any attachments associated with the message were removed, and you will need to re-attach them when you send the message.',
    'parsed_data' => '"The message was saved as draft and you will be able to complete it later.<br />
Please note that any attachments associated with the message were removed, and you will need to re-attach them when you send the message."',
  ),
  'redirect_foladded' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The folders were added to your folder list!',
    'parsed_data' => '"The folders were added to your folder list!"',
  ),
  'redirect_foldeleted' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The messages from selected folders were successfully deleted and the folders were removed.',
    'parsed_data' => '"The messages from selected folders were successfully deleted and the folders were removed."',
  ),
  'redirect_folemptied' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The selected folders were successfully emptied.',
    'parsed_data' => '"The selected folders were successfully emptied."',
  ),
  'redirect_folmoved' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The messages from selected folders were successfully moved to the $newfolder[title] folder.',
    'parsed_data' => '"The messages from selected folders were successfully moved to the $newfolder[title] folder."',
  ),
  'redirect_folrenamed' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The folder has been renamed to $name.',
    'parsed_data' => '"The folder has been renamed to $name."',
  ),
  'redirect_lostpw_updated' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Your password has been updated! You will now be able to log in with the new password you\'ve chosen.',
    'parsed_data' => '"Your password has been updated! You will now be able to log in with the new password you\'ve chosen."',
  ),
  'redirect_mailsent' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Your email was succesfully sent!',
    'parsed_data' => '"Your email was succesfully sent!"',
  ),
  'redirect_markedas' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The message$es been marked as $markas.',
    'parsed_data' => '"The message$es been marked as $markas."',
  ),
  'redirect_msgdeleted' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Selected message(s) were successfully deleted.',
    'parsed_data' => '"Selected message(s) were successfully deleted."',
  ),
  'redirect_msgmoved' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Selected message(s) were successfully moved to $newfolder[title] folder.',
    'parsed_data' => '"Selected message(s) were successfully moved to $newfolder[title] folder."',
  ),
  'redirect_popadded' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The POP account has been added.',
    'parsed_data' => '"The POP account has been added."',
  ),
  'redirect_popdeleted' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The POP account has been deleted.',
    'parsed_data' => '"The POP account has been deleted."',
  ),
  'redirect_popsupdated' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Your POP accounts have been updated.',
    'parsed_data' => '"Your POP accounts have been updated."',
  ),
  'redirect_ruleapplied' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The rule has been applied to the selected folders.',
    'parsed_data' => '"The rule has been applied to the selected folders."',
  ),
  'redirect_rules' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Your message rules have been updated.',
    'parsed_data' => '"Your message rules have been updated."',
  ),
  'redirect_settings' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Your preferences have been saved!',
    'parsed_data' => '"Your preferences have been saved!"',
  ),
  'rules' => 
  array (
    'templategroupid' => '10',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Message Rules</title>
$css
<script type="text/javascript" src="misc/checkall.js"></script>
<script type="text/javascript">
<!--
event_addListener( window, \'load\', function() { checkMain(document.forms.form, \'active\'); });
// -->
</script>
</head>
<body>
$header

<form action="rules.update.php" method="post">
<input type="hidden" name="do" value="lists" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Blocked Senders</b></span></th>
</tr>
<tr class="highRow">
	<td class="highBothCell" valign="top"><span class="normalfont">You may specify a list of email addresses you would like to block from your account below. Messages from blocked senders will automatically be moved to the Trash Can.<br />
	You can enter full addreeses (e.g. email@example.net), or domain names only (e.g. example.net) to block all emails from the domain name. Separate addresses with spaces or line breaks.<br /><br />
	<center><textarea cols="80" rows="4" name="blocked">$hiveuser[blocked]</textarea></center></span></td>
</tr>
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Safe List</b></span></th>
</tr>
<tr class="highRow">
	<td class="highBothCell" valign="top"><span class="normalfont">You may specify a list of email addresses that are "safe" below. Messages from these addresses will never be blocked or subject to message rules that you have.<br />
	You can enter full addresses (e.g. email@example.net), or domain names only (e.g. example.net) to protect all emails from the domain name. Separate addresses with spaces or line breaks.<br /><br />
	<center><textarea cols="80" rows="4" name="safe">$hiveuser[safe]</textarea></center></span></td>
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
<script type=\\"text/javascript\\">
<!--
event_addListener( window, \'load\', function() { checkMain(document.forms.form, \'active\'); });
// -->
</script>
</head>
<body>
$GLOBALS[header]

<form action=\\"rules.update.php{$GLOBALS[session_url]}\\" method=\\"post\\">
<input type=\\"hidden\\" name=\\"do\\" value=\\"lists\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Blocked Senders</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">You may specify a list of email addresses you would like to block from your account below. Messages from blocked senders will automatically be moved to the Trash Can.<br />
	You can enter full addreeses (e.g. email@example.net), or domain names only (e.g. example.net) to block all emails from the domain name. Separate addresses with spaces or line breaks.<br /><br />
	<center><textarea cols=\\"80\\" rows=\\"4\\" name=\\"blocked\\">$hiveuser[blocked]</textarea></center></span></td>
</tr>
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Safe List</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">You may specify a list of email addresses that are \\"safe\\" below. Messages from these addresses will never be blocked or subject to message rules that you have.<br />
	You can enter full addresses (e.g. email@example.net), or domain names only (e.g. example.net) to protect all emails from the domain name. Separate addresses with spaces or line breaks.<br /><br />
	<center><textarea cols=\\"80\\" rows=\\"4\\" name=\\"safe\\">$hiveuser[safe]</textarea></center></span></td>
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
<input type="hidden" name="do" value="doit" />
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
			<option value="-1">Inbox</option>
			<option value="-2">Sent Items</option>
			<option value="-3">Trash Can</option>
$separator
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
<input type=\\"hidden\\" name=\\"do\\" value=\\"doit\\" />
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
			<option value=\\"-1\\">Inbox</option>
			<option value=\\"-2\\">Sent Items</option>
			<option value=\\"-3\\">Trash Can</option>
$separator
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
  'rules_norules' => 
  array (
    'templategroupid' => '10',
    'user_data' => '<tr class="highRow">
	<td colspan="6" class="highBothCell" align="center"><span class="normalfont">You currently have no rules set up.</span></td>
</tr>
',
    'parsed_data' => '"<tr class=\\"highRow\\">
	<td colspan=\\"6\\" class=\\"highBothCell\\" align=\\"center\\"><span class=\\"normalfont\\">You currently have no rules set up.</span></td>
</tr>
"',
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
	</select> <input type="text" class="bginput" name="condextras[$rule[ruleid]]" value="$condextra" size="20" /></td>
	<td class="$class[name]Cell"><span class="normalfont">
	<input type="checkbox" name="dowhat[$rule[ruleid]][folder]" value="1" $movechecked$copychecked /> <select name="folderstuff[$rule[ruleid]]">
		<option value="2" $actionchecks[2]>move it to</option>
		<option value="4" $actionchecks[4]>copy it to</option>
	</select>
	<select name="folders[$rule[ruleid]]">
		$folderbits
	</select><br />
	<input type="checkbox" name="dowhat[$rule[ruleid]][read]" value="1" $readchecked /> mark it as read.<br />
	<input type="checkbox" name="dowhat[$rule[ruleid]][delete]" value="1" $deletechecked /> delete it.<br />
	<input type="checkbox" name="dowhat[$rule[ruleid]][flag]" value="1" $flagchecked /> flag it.</span></td>
	<td class="$class[name]RightCell" align="center"><input type="checkbox" name="active[$rule[ruleid]]" value="yes" $activechecked onClick="checkMain(this.form, \'active\');" /></td>
</tr>
',
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
	</select> <input type=\\"text\\" class=\\"bginput\\" name=\\"condextras[$rule[ruleid]]\\" value=\\"$condextra\\" size=\\"20\\" /></td>
	<td class=\\"$class[name]Cell\\"><span class=\\"normalfont\\">
	<input type=\\"checkbox\\" name=\\"dowhat[$rule[ruleid]][folder]\\" value=\\"1\\" $movechecked$copychecked /> <select name=\\"folderstuff[$rule[ruleid]]\\">
		<option value=\\"2\\" $actionchecks[2]>move it to</option>
		<option value=\\"4\\" $actionchecks[4]>copy it to</option>
	</select>
	<select name=\\"folders[$rule[ruleid]]\\">
		$folderbits
	</select><br />
	<input type=\\"checkbox\\" name=\\"dowhat[$rule[ruleid]][read]\\" value=\\"1\\" $readchecked /> mark it as read.<br />
	<input type=\\"checkbox\\" name=\\"dowhat[$rule[ruleid]][delete]\\" value=\\"1\\" $deletechecked /> delete it.<br />
	<input type=\\"checkbox\\" name=\\"dowhat[$rule[ruleid]][flag]\\" value=\\"1\\" $flagchecked /> flag it.</span></td>
	<td class=\\"$class[name]RightCell\\" align=\\"center\\"><input type=\\"checkbox\\" name=\\"active[$rule[ruleid]]\\" value=\\"yes\\" $activechecked onClick=\\"checkMain(this.form, \'active\');\\" /></td>
</tr>
"',
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
			<option value="-1">Inbox</option>
			<option value="-2">Sent Items</option>
			<option value="-3">Trash Can</option>
$separator
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
			<option value=\\"-1\\">Inbox</option>
			<option value=\\"-2\\">Sent Items</option>
			<option value=\\"-3\\">Trash Can</option>
$separator
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
		new ContextItem(\'Print\', function(){ window.location = \'read.print.php?messageid=\'+msgID; }, totalChecked != 1),
		new ContextSeperator(),
		new ContextItem(\'Reply to Sender\', function(){ window.location = \'compose.email.php?special=reply&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Reply to All\', function(){ window.location = \'compose.email.php?special=replyall&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Forward\', function(){ window.location = \'compose.email.php?special=forward&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Forward as Attachment\', function(){ form.action=\'index.php?asattach=1&forward=Forward\'; form.submit(); }),
		new ContextSeperator(),
		new ContextItem(\'Mark as Read\', function(){ form.action=\'index.php?mark=Mark\'; form.markas.selectedIndex=0; form.submit(); }, isNew.substr(0, 3) != \'new\' && totalChecked == 1),
		new ContextItem(\'Mark as Unread\', function(){ form.action=\'index.php?mark=Mark\'; form.markas.selectedIndex=1; form.submit(); }, isNew.substr(0, 3) == \'new\' && totalChecked == 1),
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
		<input type="submit" class="bginput" name="forward" value="Forward" />&nbsp; or &nbsp;<input type="submit" class="bginput" name="delete" value="Delete" onClick="changeFolderID(); return confirm(\'Are you sure you want to delete the selected messages?\');" />&nbsp; selected</b></span></td>
      </tr>
    </table></td>
</tr>
</table>

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr>
	<td><span class="smallfont">Showing messages $limitlower to $limitupper of $totalmails<br />$pagenav</span></td>
	<td align="right"><span class="smallfont">$deletenote</span></td>
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
		new ContextItem(\'Print\', function(){ window.location = \'read.print.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=\'+msgID; }, totalChecked != 1),
		new ContextSeperator(),
		new ContextItem(\'Reply to Sender\', function(){ window.location = \'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}special=reply&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Reply to All\', function(){ window.location = \'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}special=replyall&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Forward\', function(){ window.location = \'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}special=forward&messageid=\'+msgID; }, totalChecked != 1),
		new ContextItem(\'Forward as Attachment\', function(){ form.action=\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}asattach=1&forward=Forward\'; form.submit(); }),
		new ContextSeperator(),
		new ContextItem(\'Mark as Read\', function(){ form.action=\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}mark=Mark\'; form.markas.selectedIndex=0; form.submit(); }, isNew.substr(0, 3) != \'new\' && totalChecked == 1),
		new ContextItem(\'Mark as Unread\', function(){ form.action=\'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}mark=Mark\'; form.markas.selectedIndex=1; form.submit(); }, isNew.substr(0, 3) == \'new\' && totalChecked == 1),
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
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"forward\\" value=\\"Forward\\" />&nbsp; or &nbsp;<input type=\\"submit\\" class=\\"bginput\\" name=\\"delete\\" value=\\"Delete\\" onClick=\\"changeFolderID(); return confirm(\'Are you sure you want to delete the selected messages?\');\\" />&nbsp; selected</b></span></td>
      </tr>
    </table></td>
</tr>
</table>

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr>
	<td><span class=\\"smallfont\\">Showing messages $limitlower to $limitupper of $totalmails<br />$pagenav</span></td>
	<td align=\\"right\\"><span class=\\"smallfont\\">$deletenote</span></td>
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
  'search_results_header_attach' => 
  array (
    'templategroupid' => '5',
    'user_data' => '	<th class="headerCell" width="15" nowrap="nowrap"><span class="headerText"><a href="search.results.php?searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=attach"><img src="$skin[images]/paperclip.gif" alt="Has attachments?" border="0" /></a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\" width=\\"15\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><a href=\\"search.results.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=attach\\"><img src=\\"{$GLOBALS[skin][images]}/paperclip.gif\\" alt=\\"Has attachments?\\" border=\\"0\\" /></a></span></th>
"',
  ),
  'search_results_header_datetime' => 
  array (
    'templategroupid' => '5',
    'user_data' => '	<th class="headerCell"><span class="headerText"><b><a href="search.results.php?searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=dateline"><span class="normalfonttablehead">Received</b></span>$sortimages[dateline]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\"><span class=\\"headerText\\"><b><a href=\\"search.results.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=dateline\\"><span class=\\"normalfonttablehead\\">Received</b></span>$sortimages[dateline]</a></span></th>
"',
  ),
  'search_results_header_from' => 
  array (
    'templategroupid' => '5',
    'user_data' => '	<th class="headerCell"><span class="headerText"><a href="search.results.php?searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=name"><span class="normalfonttablehead"><b>From</b></span>$sortimages[name]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\"><span class=\\"headerText\\"><a href=\\"search.results.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=name\\"><span class=\\"normalfonttablehead\\"><b>From</b></span>$sortimages[name]</a></span></th>
"',
  ),
  'search_results_header_priority' => 
  array (
    'templategroupid' => '5',
    'user_data' => '	<th class="headerCell" width="15" nowrap="nowrap"><span class="headerText"><a href="search.results.php?searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=priority"><img src="$skin[images]/prio_high.gif" alt="Important?" border="0" /></a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\" width=\\"15\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><a href=\\"search.results.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=priority\\"><img src=\\"{$GLOBALS[skin][images]}/prio_high.gif\\" alt=\\"Important?\\" border=\\"0\\" /></a></span></th>
"',
  ),
  'search_results_header_size' => 
  array (
    'templategroupid' => '5',
    'user_data' => '	<th class="headerCell" nowrap="nowrap"><span class="headerText"><b><a href="search.results.php?searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=size"><span class="normalfonttablehead">Size</b></span>$sortimages[size]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><b><a href=\\"search.results.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=size\\"><span class=\\"normalfonttablehead\\">Size</b></span>$sortimages[size]</a></span></th>
"',
  ),
  'search_results_header_subject' => 
  array (
    'templategroupid' => '5',
    'user_data' => '	<th class="headerCell" nowrap="nowrap"><span class="headerText"><a href="search.results.php?searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=subject"><span class="normalfonttablehead"><b>Message Subject</b></span>$sortimages[subject]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><a href=\\"search.results.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=subject\\"><span class=\\"normalfonttablehead\\"><b>Message Subject</b></span>$sortimages[subject]</a></span></th>
"',
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
<input type="hidden" name="do" value="complete" />
<input type="hidden" name="username" value="$username" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="650" align="center">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Sign up: Required Information</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Your name:</b></span>
	<br />
	<span class="smallfont">This name will be sent with all your outgoing emails.</span></td>
	<td class="normalRightCell" width="40%"><input type="text" class="bginput" name="realname" size="40" /></td>
</tr>
$password_row
<tr class="$afterpass[first]Row">
	<td class="$afterpass[first]LeftCell" width="60%" valign="top"><span class="normalfont"><b>Secret question:</b></span><br /><span class="smallfont">If you ever forget your password, you will be asked to answer this question in order to get a new one.</span></td>
	<td class="$afterpass[first]RightCell" width="40%"><input type="text" class="bginput" name="question" size="40" /><br /><br />
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
	<td class="$afterpass[second]RightCell" width="40%"><input type="password" class="bginput" name="answer" size="40" /></td>
</tr>
<tr class="$afterpass[first]">
	<td class="$afterpass[first]LeftCell" width="60%" valign="top"><span class="normalfont"><b>Retype secret answer:</b></span><br /><span class="smallfont">Repeat the secret answer to verify it\'s correct.</span></td>
	<td class="$afterpass[first]RightCell" width="40%"><input type="password" class="bginput" name="answer_repeat" size="40" /></td>
</tr>
<%if getop(\'moderate\') %>
<tr class="$afterpass[second]">
	<td class="$afterpass[second]LeftCell" width="60%" valign="top"><span class="normalfont"><b>Secondary email address:</b></span><br /><span class="smallfont">As the operators of $appname require user validation, your account will not be functional until they activate it. You will be notified by email at this address when your account is activated.</span></td>
	<td class="$afterpass[second]RightCell" width="40%"><input type="text" class="bginput" name="altemail" size="40" /></td>
</tr>
<%endif%>
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
			<option value="0">Month</option>
			<option value="1">January</option>
			<option value="2">February</option>
			<option value="3">March</option>
			<option value="4">April</option>
			<option value="5">May</option>
			<option value="6">June</option>
			<option value="7">July</option>
			<option value="8">August</option>
			<option value="9">September</option>
			<option value="10">October</option>
			<option value="11">November</option>
			<option value="12">December</option>
		</select>
		<select name="day">
			<option value="0">Day</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>
			<option value="16">16</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="19">19</option>
			<option value="20">20</option>
			<option value="21">21</option>
			<option value="22">22</option>
			<option value="23">23</option>
			<option value="24">24</option>
			<option value="25">25</option>
			<option value="26">26</option>
			<option value="27">27</option>
			<option value="28">28</option>
			<option value="29">29</option>
			<option value="30">30</option>
			<option value="31">31</option>
		</select>
		<input type="text" class="bginput" name="year" size="4" maxlength="4"></td>
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
	<td class="normalRightCell" width="40%"><input type="text" class="bginput" name="zip" value="" size="7" maxlength="7"></td>
</tr>
<%if !getop(\'moderate\') %>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Secondary email address:</b></span></td>
	<td class="highRightCell" width="40%"><input type="text" class="bginput" name="altemail" size="40" /></td>
</tr>
<%endif%>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="650" align="center">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="submit" value="Sign Up" onClick="if (form.answer.value != form.answer_repeat.value) { alert(\'Your secret answers do not match. Please retype them and submit the form again.\'); return false; } else if (form.answer.value.length == 0) { alert(\'Your secret answer must not be empty.\'); return false; } else if (form.question.value.length == 0) { alert(\'Your secret question must not be empty.\'); return false; } else if (form.password.value.length == 0) { alert(\'Your password must not be empty.\'); return false; } else if (form.password.value != form.password_repeat.value) { alert(\'Your passwords do not match. Please retype them and submit the form again.\'); return false; } else if (form.realname.value.length == 0) { alert(\'Your real name must not be empty.\'); return false; } else if ($moderate == 1 && form.altemail.value.length == 0) { alert(\'Your secondary email address must not be empty.\'); return false; } else { return true; }" />
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
<input type=\\"hidden\\" name=\\"do\\" value=\\"complete\\" />
<input type=\\"hidden\\" name=\\"username\\" value=\\"$username\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"650\\" align=\\"center\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Sign up: Required Information</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Your name:</b></span>
	<br />
	<span class=\\"smallfont\\">This name will be sent with all your outgoing emails.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"realname\\" size=\\"40\\" /></td>
</tr>
$password_row
<tr class=\\"$afterpass[first]Row\\">
	<td class=\\"$afterpass[first]LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secret question:</b></span><br /><span class=\\"smallfont\\">If you ever forget your password, you will be asked to answer this question in order to get a new one.</span></td>
	<td class=\\"$afterpass[first]RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"question\\" size=\\"40\\" /><br /><br />
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
	<td class=\\"$afterpass[second]RightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"answer\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"$afterpass[first]\\">
	<td class=\\"$afterpass[first]LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Retype secret answer:</b></span><br /><span class=\\"smallfont\\">Repeat the secret answer to verify it\'s correct.</span></td>
	<td class=\\"$afterpass[first]RightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"answer_repeat\\" size=\\"40\\" /></td>
</tr>
".((getop(\'moderate\') ) ? ("
<tr class=\\"$afterpass[second]\\">
	<td class=\\"$afterpass[second]LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secondary email address:</b></span><br /><span class=\\"smallfont\\">As the operators of $appname require user validation, your account will not be functional until they activate it. You will be notified by email at this address when your account is activated.</span></td>
	<td class=\\"$afterpass[second]RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"altemail\\" size=\\"40\\" /></td>
</tr>
") : \'\')."
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
			<option value=\\"0\\">Month</option>
			<option value=\\"1\\">January</option>
			<option value=\\"2\\">February</option>
			<option value=\\"3\\">March</option>
			<option value=\\"4\\">April</option>
			<option value=\\"5\\">May</option>
			<option value=\\"6\\">June</option>
			<option value=\\"7\\">July</option>
			<option value=\\"8\\">August</option>
			<option value=\\"9\\">September</option>
			<option value=\\"10\\">October</option>
			<option value=\\"11\\">November</option>
			<option value=\\"12\\">December</option>
		</select>
		<select name=\\"day\\">
			<option value=\\"0\\">Day</option>
			<option value=\\"1\\">1</option>
			<option value=\\"2\\">2</option>
			<option value=\\"3\\">3</option>
			<option value=\\"4\\">4</option>
			<option value=\\"5\\">5</option>
			<option value=\\"6\\">6</option>
			<option value=\\"7\\">7</option>
			<option value=\\"8\\">8</option>
			<option value=\\"9\\">9</option>
			<option value=\\"10\\">10</option>
			<option value=\\"11\\">11</option>
			<option value=\\"12\\">12</option>
			<option value=\\"13\\">13</option>
			<option value=\\"14\\">14</option>
			<option value=\\"15\\">15</option>
			<option value=\\"16\\">16</option>
			<option value=\\"17\\">17</option>
			<option value=\\"18\\">18</option>
			<option value=\\"19\\">19</option>
			<option value=\\"20\\">20</option>
			<option value=\\"21\\">21</option>
			<option value=\\"22\\">22</option>
			<option value=\\"23\\">23</option>
			<option value=\\"24\\">24</option>
			<option value=\\"25\\">25</option>
			<option value=\\"26\\">26</option>
			<option value=\\"27\\">27</option>
			<option value=\\"28\\">28</option>
			<option value=\\"29\\">29</option>
			<option value=\\"30\\">30</option>
			<option value=\\"31\\">31</option>
		</select>
		<input type=\\"text\\" class=\\"bginput\\" name=\\"year\\" size=\\"4\\" maxlength=\\"4\\"></td>
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
	<td class=\\"normalRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"zip\\" value=\\"\\" size=\\"7\\" maxlength=\\"7\\"></td>
</tr>
".((!getop(\'moderate\') ) ? ("
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secondary email address:</b></span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"altemail\\" size=\\"40\\" /></td>
</tr>
") : \'\')."
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"650\\" align=\\"center\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Sign Up\\" onClick=\\"if (form.answer.value != form.answer_repeat.value) { alert(\'Your secret answers do not match. Please retype them and submit the form again.\'); return false; } else if (form.answer.value.length == 0) { alert(\'Your secret answer must not be empty.\'); return false; } else if (form.question.value.length == 0) { alert(\'Your secret question must not be empty.\'); return false; } else if (form.password.value.length == 0) { alert(\'Your password must not be empty.\'); return false; } else if (form.password.value != form.password_repeat.value) { alert(\'Your passwords do not match. Please retype them and submit the form again.\'); return false; } else if (form.realname.value.length == 0) { alert(\'Your real name must not be empty.\'); return false; } else if ($moderate == 1 && form.altemail.value.length == 0) { alert(\'Your secondary email address must not be empty.\'); return false; } else { return true; }\\" />
	</td>
</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'signup_email' => 
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
  'signup_email_subject' => 
  array (
    'templategroupid' => '14',
    'user_data' => 'Account activated at $appname!',
    'parsed_data' => '"Account activated at $appname!"',
  ),
  'signup_password_input' => 
  array (
    'templategroupid' => '14',
    'user_data' => '<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Your password:</b></span></td>
	<td class="highRightCell" width="40%"><input type="password" class="bginput" name="password" size="40" /></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Retype password:</b></span>
	<br />
	<span class="smallfont">Repeat the password to verify it\'s correct.</span></td>
	<td class="normalRightCell" width="40%"><input type="password" class="bginput" name="password_repeat" size="40" /></td>
</tr>
<input type="hidden" name="password_encrypted" value="0" />',
    'parsed_data' => '"<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Your password:</b></span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"password\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Retype password:</b></span>
	<br />
	<span class=\\"smallfont\\">Repeat the password to verify it\'s correct.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"password_repeat\\" size=\\"40\\" /></td>
</tr>
<input type=\\"hidden\\" name=\\"password_encrypted\\" value=\\"0\\" />"',
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
<input type="hidden" name="password_encrypted" value="1" />',
    'parsed_data' => '"<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Your password:</b></span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\">$hidden_password</span></td>
</tr>
<input type=\\"hidden\\" name=\\"password\\" value=\\"$password\\" />
<input type=\\"hidden\\" name=\\"password_repeat\\" value=\\"$password\\" />
<input type=\\"hidden\\" name=\\"password_encrypted\\" value=\\"1\\" />"',
  ),
  'signup_thankyou' => 
  array (
    'templategroupid' => '14',
    'user_data' => 'Thank for your signing up to our service!<br />
<%if getop(\'moderate\') %>
As the operators of $appname require user validation, your account will not be functional until they activate it. You will be notified by email ($altemail) when your account is activated.
<%else%>
To start using your account, <a href="index.php">click here</a> and log in using the information you have just provided. Enjoy $appname!
<%endif%>',
    'parsed_data' => '"Thank for your signing up to our service!<br />
".((getop(\'moderate\') ) ? ("
As the operators of $appname require user validation, your account will not be functional until they activate it. You will be notified by email ($altemail) when your account is activated.
") : ("
To start using your account, <a href=\\"index.php{$GLOBALS[session_url]}\\">click here</a> and log in using the information you have just provided. Enjoy $appname!
"))',
  ),
);

?>