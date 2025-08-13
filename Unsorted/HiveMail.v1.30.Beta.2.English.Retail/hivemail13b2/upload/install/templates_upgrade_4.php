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
$templates[4] = array (
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
	<th class="headerLeftCell" width="3%" nowrap="nowrap"><a href="addressbook.view.php?perpage=$perpage"><span class="normalfonttablehead">All</span></a></th>
	$letters
</tr>
</table>
<br />
<table cellpadding="4" cellspacing="0" class="normalTable" width="730">
<tr class="headerRow">
	<th class="headerLeftCell" nowrap="nowrap"><span class="normalfonttablehead"><b>Contact Name</b></span> <span class="smallfonttablehead">(click to edit)</span></th>
	<th class="headerCell" nowrap="nowrap"><span class="normalfonttablehead"><b>Email Address</b></span> <span class="smallfonttablehead">(click to email)</span></th>
	<th class="headerRightCell"><input name="allbox" type="checkbox" value="Check All" title="Select/Deselect All" onClick="checkAll(this.form);" /></th>
</tr>
$contacts
</table>

<table cellpadding="4" cellspacing="0" class="normalTable" width="730">
<tr>
	<td><span class="smallfont">Showing contacts $limitlower to $limitupper of $totalcontacts<br />$pagenav</span></td>
	<td align="right"><span class="smallfonttablehead"><b><input type="submit" class="bginput" name="edit" value="Edit" /> &nbsp;or &nbsp;<input type="submit" class="bginput" name="delete" value="Delete" onClick="return confirm(\'Are you sure you want to delete the selected contacts?\');" />&nbsp; selected contacts</b></span></td>
</tr>
</table>
</form>

<br />

<table align="left" width="730">
	<tr>
		<td width="50%" style="padding: 0px; padding-right: 5px;">
			<form action="addressbook.add.php" method="post" name="addform" onSubmit="return validateAddForm();">
			<input type="hidden" name="cmd" value="insert" />

			<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			<tr class="headerRow">
				<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Add New Contact</b></span></th>
			</tr>
			<tr class="highRow">
				<td class="highBothCell" align="center">
					<table width="100%" align="center">
						<tr>
							<td nowrap="nowrap"><span class="normalfont">Contact\'s name:</span></td>
							<td><input type="text" class="bginput" name="name" value="" size="30" /></td>
						</tr>
						<tr>
							<td nowrap="nowrap"><span class="normalfont">Contact\'s email:</span></td>
							<td><input type="text" class="bginput" name="email" value="" size="30" /></td>
						</tr>
					</table>
					<br />
					<input type="submit" class="bginput" name="submit" value="Add Contact" />
				</td>
			</tr>
			</table>
			</form>
		</td>
		<td width="50%" style="padding: 0px; padding-left: 5px;">
			<form action="addressbook.view.php" method="get" name="searchform">
			<input type="hidden" name="cmd" value="search" />
			<input type="hidden" name="perpage" value="$perpage" />

			<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			<tr class="headerRow">
				<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Search Contacts</b></span></th>
			</tr>
			<tr class="highRow">
				<td class="highBothCell" align="center">
					<table width="100%" align="center">
						<tr>
							<td nowrap="nowrap" align="left" colspan="2"><span class="normalfont">Name contains:</span></td>
							<td><input type="text" class="bginput" name="name" value="$name" size="25" /></td>
						</tr>
						<tr>
							<td nowrap="nowrap" align="left"><select name="cond"><option value="and" $condselect[and]>And</option><option value="or" $condselect[or]>Or</option></select></td>
							<td nowrap="nowrap" align="left"><span class="normalfont">Email contains:</span></td>
							<td><input type="text" class="bginput" name="email" value="$email" size="25" /></td>
						</tr>
					</table>
					<br />
					<input type="submit" class="bginput" value="Perform Search" />
				</td>
			</tr>
			</table>
			</form>
		</td>
	</tr>
	<tr>
		<td width="50%" style="padding: 0px; padding-right: 5px;">
			<form enctype="multipart/form-data" action="addressbook.add.php" name="composeform" method="post">
			<input type="hidden" name="cmd" value="upload" />

			<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			<tr class="headerRow">
				<th class="headerBothCell"><span class="normalfonttablehead"><b>Add Contacts from CSV File</b></span></th>
			</tr>
			<tr class="highRow">
				<td class="normalBothCell"><span class="smallfont">Click the "Browse..." button to find the CSV file you wish to use. When you are done, click "Upload CSV".<br /><br />
				<input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
				<input type="file" class="bginput" name="attachment" /></span>
				<br />
				<p align="center"><input type="submit" class="bginput" name="submit" value="Import CSV File" /></p></td>
			</tr>
			</table>
			</form>
		</td>
		<td width="50%" style="padding: 0px; padding-left: 5px;">
			<form enctype="multipart/form-data" action="addressbook.view.php" name="exportform" method="post">
			<input type="hidden" name="cmd" value="export" />

			<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			<tr class="headerRow">
				<th class="headerBothCell"><span class="normalfonttablehead"><b>Export Contacts To CSV File</b></span></th>
			</tr>
			<tr class="highRow">
				<td class="normalBothCell"><span class="smallfont">Export all of your address book in comma seperated value (CSV) format, suitable for importing into other software.</span>
				<span class="normalfont"><br />&nbsp;<br />&nbsp;<br />
				<p align="center"><input type="submit" class="bginput" name="submit" value="Export To CSV File" /></p></span></td>
			</tr>
			</table>
			</form>
		</td>
	</tr>
</table>

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
	<th class=\\"headerLeftCell\\" width=\\"3%\\" nowrap=\\"nowrap\\"><a href=\\"addressbook.view.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}perpage=$perpage\\"><span class=\\"normalfonttablehead\\">All</span></a></th>
	$letters
</tr>
</table>
<br />
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"730\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerLeftCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>Contact Name</b></span> <span class=\\"smallfonttablehead\\">(click to edit)</span></th>
	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>Email Address</b></span> <span class=\\"smallfonttablehead\\">(click to email)</span></th>
	<th class=\\"headerRightCell\\"><input name=\\"allbox\\" type=\\"checkbox\\" value=\\"Check All\\" title=\\"Select/Deselect All\\" onClick=\\"checkAll(this.form);\\" /></th>
</tr>
$contacts
</table>

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"730\\">
<tr>
	<td><span class=\\"smallfont\\">Showing contacts $limitlower to $limitupper of $totalcontacts<br />$pagenav</span></td>
	<td align=\\"right\\"><span class=\\"smallfonttablehead\\"><b><input type=\\"submit\\" class=\\"bginput\\" name=\\"edit\\" value=\\"Edit\\" /> &nbsp;or &nbsp;<input type=\\"submit\\" class=\\"bginput\\" name=\\"delete\\" value=\\"Delete\\" onClick=\\"return confirm(\'Are you sure you want to delete the selected contacts?\');\\" />&nbsp; selected contacts</b></span></td>
</tr>
</table>
</form>

<br />

<table align=\\"left\\" width=\\"730\\">
	<tr>
		<td width=\\"50%\\" style=\\"padding: 0px; padding-right: 5px;\\">
			<form action=\\"addressbook.add.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"addform\\" onSubmit=\\"return validateAddForm();\\">
			<input type=\\"hidden\\" name=\\"cmd\\" value=\\"insert\\" />

			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			<tr class=\\"headerRow\\">
				<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Add New Contact</b></span></th>
			</tr>
			<tr class=\\"highRow\\">
				<td class=\\"highBothCell\\" align=\\"center\\">
					<table width=\\"100%\\" align=\\"center\\">
						<tr>
							<td nowrap=\\"nowrap\\"><span class=\\"normalfont\\">Contact\'s name:</span></td>
							<td><input type=\\"text\\" class=\\"bginput\\" name=\\"name\\" value=\\"\\" size=\\"30\\" /></td>
						</tr>
						<tr>
							<td nowrap=\\"nowrap\\"><span class=\\"normalfont\\">Contact\'s email:</span></td>
							<td><input type=\\"text\\" class=\\"bginput\\" name=\\"email\\" value=\\"\\" size=\\"30\\" /></td>
						</tr>
					</table>
					<br />
					<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Add Contact\\" />
				</td>
			</tr>
			</table>
			</form>
		</td>
		<td width=\\"50%\\" style=\\"padding: 0px; padding-left: 5px;\\">
			<form action=\\"addressbook.view.php{$GLOBALS[session_url]}\\" method=\\"get\\" name=\\"searchform\\">
			<input type=\\"hidden\\" name=\\"cmd\\" value=\\"search\\" />
			<input type=\\"hidden\\" name=\\"perpage\\" value=\\"$perpage\\" />

			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			<tr class=\\"headerRow\\">
				<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Search Contacts</b></span></th>
			</tr>
			<tr class=\\"highRow\\">
				<td class=\\"highBothCell\\" align=\\"center\\">
					<table width=\\"100%\\" align=\\"center\\">
						<tr>
							<td nowrap=\\"nowrap\\" align=\\"left\\" colspan=\\"2\\"><span class=\\"normalfont\\">Name contains:</span></td>
							<td><input type=\\"text\\" class=\\"bginput\\" name=\\"name\\" value=\\"$name\\" size=\\"25\\" /></td>
						</tr>
						<tr>
							<td nowrap=\\"nowrap\\" align=\\"left\\"><select name=\\"cond\\"><option value=\\"and\\" $condselect[and]>And</option><option value=\\"or\\" $condselect[or]>Or</option></select></td>
							<td nowrap=\\"nowrap\\" align=\\"left\\"><span class=\\"normalfont\\">Email contains:</span></td>
							<td><input type=\\"text\\" class=\\"bginput\\" name=\\"email\\" value=\\"$email\\" size=\\"25\\" /></td>
						</tr>
					</table>
					<br />
					<input type=\\"submit\\" class=\\"bginput\\" value=\\"Perform Search\\" />
				</td>
			</tr>
			</table>
			</form>
		</td>
	</tr>
	<tr>
		<td width=\\"50%\\" style=\\"padding: 0px; padding-right: 5px;\\">
			<form enctype=\\"multipart/form-data\\" action=\\"addressbook.add.php{$GLOBALS[session_url]}\\" name=\\"composeform\\" method=\\"post\\">
			<input type=\\"hidden\\" name=\\"cmd\\" value=\\"upload\\" />

			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			<tr class=\\"headerRow\\">
				<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Add Contacts from CSV File</b></span></th>
			</tr>
			<tr class=\\"highRow\\">
				<td class=\\"normalBothCell\\"><span class=\\"smallfont\\">Click the \\"Browse...\\" button to find the CSV file you wish to use. When you are done, click \\"Upload CSV\\".<br /><br />
				<input type=\\"hidden\\" name=\\"MAX_FILE_SIZE\\" value=\\"10485760\\" />
				<input type=\\"file\\" class=\\"bginput\\" name=\\"attachment\\" /></span>
				<br />
				<p align=\\"center\\"><input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Import CSV File\\" /></p></td>
			</tr>
			</table>
			</form>
		</td>
		<td width=\\"50%\\" style=\\"padding: 0px; padding-left: 5px;\\">
			<form enctype=\\"multipart/form-data\\" action=\\"addressbook.view.php{$GLOBALS[session_url]}\\" name=\\"exportform\\" method=\\"post\\">
			<input type=\\"hidden\\" name=\\"cmd\\" value=\\"export\\" />

			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			<tr class=\\"headerRow\\">
				<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Export Contacts To CSV File</b></span></th>
			</tr>
			<tr class=\\"highRow\\">
				<td class=\\"normalBothCell\\"><span class=\\"smallfont\\">Export all of your address book in comma seperated value (CSV) format, suitable for importing into other software.</span>
				<span class=\\"normalfont\\"><br />&nbsp;<br />&nbsp;<br />
				<p align=\\"center\\"><input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Export To CSV File\\" /></p></span></td>
			</tr>
			</table>
			</form>
		</td>
	</tr>
</table>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'addbook_main_letterbit' => 
  array (
    'templategroupid' => '4',
    'user_data' => '<th class="header$whichCell" width="3%" nowrap="nowrap"><%if $curletter == $letter %><span class="normalfonttablehead">[$letter]</span><%else%><a href="addressbook.view.php?letter=$encletter&perpage=$perpage"><span class="normalfonttablehead">$letter</span></a><%endif%></th>',
    'parsed_data' => '"<th class=\\"header$whichCell\\" width=\\"3%\\" nowrap=\\"nowrap\\">".(($curletter == $letter ) ? ("<span class=\\"normalfonttablehead\\">[$letter]</span>") : ("<a href=\\"addressbook.view.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}letter=$encletter&perpage=$perpage\\"><span class=\\"normalfonttablehead\\">$letter</span></a>"))."</th>"',
  ),
  'addbook_main_noentries' => 
  array (
    'templategroupid' => '4',
    'user_data' => '<tr align="center" class="highRow">
	<td align="center" colspan="3" class="normalBothCell"><span class="normalfont"><%if !empty($sqlwhere) %>No contacts match criteria.<%else%>No contacts.<%endif%></span></td>
</tr>
',
    'parsed_data' => '"<tr align=\\"center\\" class=\\"highRow\\">
	<td align=\\"center\\" colspan=\\"3\\" class=\\"normalBothCell\\"><span class=\\"normalfont\\">".((!empty($sqlwhere) ) ? ("No contacts match criteria.") : ("No contacts."))."</span></td>
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
<script language="Javascript">
<!--
self.focus();
var oneListOnly = $onelistonly;
// -->
</script>
<script type="text/javascript" src="misc/addressbook.js"></script>
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
			<select name="contacts" style="width: 100%" multiple="multiple" size="<%if !$onelistonly %>17<%else%>8<%endif%>" onChange="updateDisabled(this.form, \'adds\');">
			$contacts
			</select>
		</td>
		<%if !$onelistonly %>
		<td>
			<input type="button" style="width: 55px;" value="To ->" onClick="addto(this.form, \'to\');" class="bginput" disabled="disabled" name="toto" /><br />
			<input type="button" style="width: 55px;" value="Delete" onClick="to.options[to.selectedIndex] = null; this.disabled = true;" class="bginput" disabled="disabled" name="deleteto" />
		</td>
		<%else%>
		<td>
			<input type="button" style="width: 55px;" value="Add" onClick="addto(this.form, \'to\');" class="bginput" disabled="disabled" name="toto" /><br />
			<input type="button" style="width: 55px;" value="Delete" onClick="to.options[to.selectedIndex] = null; this.disabled = true;" class="bginput" disabled="disabled" name="deleteto" />
		</td>
		<%endif%>
		<td width="50%">
			<select name="tolist[]" style="width: 100%" id="to" multiple="multiple" size="5" onChange="updateDisabled(this.form, \'to\');">
			$to
			</select>
		</td>
	</tr>
	<%if !$onelistonly %>
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
	<%endif%>
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
<script language=\\"Javascript\\">
<!--
self.focus();
var oneListOnly = $onelistonly;
// -->
</script>
<script type=\\"text/javascript\\" src=\\"misc/addressbook.js\\"></script>
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
			<select name=\\"contacts\\" style=\\"width: 100%\\" multiple=\\"multiple\\" size=\\"".((!$onelistonly ) ? ("17") : ("8"))."\\" onChange=\\"updateDisabled(this.form, \'adds\');\\">
			$contacts
			</select>
		</td>
		".((!$onelistonly ) ? ("
		<td>
			<input type=\\"button\\" style=\\"width: 55px;\\" value=\\"To ->\\" onClick=\\"addto(this.form, \'to\');\\" class=\\"bginput\\" disabled=\\"disabled\\" name=\\"toto\\" /><br />
			<input type=\\"button\\" style=\\"width: 55px;\\" value=\\"Delete\\" onClick=\\"to.options[to.selectedIndex] = null; this.disabled = true;\\" class=\\"bginput\\" disabled=\\"disabled\\" name=\\"deleteto\\" />
		</td>
		") : ("
		<td>
			<input type=\\"button\\" style=\\"width: 55px;\\" value=\\"Add\\" onClick=\\"addto(this.form, \'to\');\\" class=\\"bginput\\" disabled=\\"disabled\\" name=\\"toto\\" /><br />
			<input type=\\"button\\" style=\\"width: 55px;\\" value=\\"Delete\\" onClick=\\"to.options[to.selectedIndex] = null; this.disabled = true;\\" class=\\"bginput\\" disabled=\\"disabled\\" name=\\"deleteto\\" />
		</td>
		"))."
		<td width=\\"50%\\">
			<select name=\\"tolist[]\\" style=\\"width: 100%\\" id=\\"to\\" multiple=\\"multiple\\" size=\\"5\\" onChange=\\"updateDisabled(this.form, \'to\');\\">
			$to
			</select>
		</td>
	</tr>
	".((!$onelistonly ) ? ("
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
	") : (\'\'))."
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
  'calendar_addevent' => 
  array (
    'templategroupid' => '18',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: <%if $newevent %>Add New Event<%else%>Modify Event<%endif%></title>
$css
<script type="text/javascript" src="misc/common.js"></script>
<script type="text/javascript" src="misc/checkall.js"></script>
<script language="JavaScript">
<!--

function popAddBook () {
     var url = "addressbook.view.php?cmd=mini";
     url += "&pre[list]=" + escape (document.forms.eventform.addresses.value);
     var hWnd = window.open(url,"AddBook","width=520,height=250,resizable=yes,scrollbars=yes");
     if ((document.window != null) && (!hWnd.opener)) {
          hWnd.opener = document.window;
	 }
}

function getDay(tform, which) {
	var date = new Date(eval(\'tform.\'+which+\'year\').value, eval(\'tform.\'+which+\'month\').value - 1, eval(\'tform.\'+which+\'day\').value);
	eval(\'tform.\'+which+\'dayname\').value = DayNames[date.getDay()];
}

// Names of days that will be shown next to the date
var DayNames = new Array(\'($skin[cal_sun_long])\', \'($skin[cal_mon_long])\', \'($skin[cal_tue_long])\', \'($skin[cal_wed_long])\', \'($skin[cal_thu_long])\', \'($skin[cal_fri_long])\', \'($skin[cal_sat_long])\');
var contacts = new Array($contactArray);

// -->
</script>
<script type="text/javascript" src="misc/autocomplete.js"></script>
</head>
<body>
$header

<form action="calendar.event.php" method="post" name="eventform">
<input type="hidden" name="cmd" value="update" />
<%if !$newevent %><input type="hidden" name="eventid" value="$event[eventid]" /><%endif%>

<table cellpadding="4" cellspacing="0" class="normalTable" width="700">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Event Information</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="30%" valign="top"><span class="normalfont"><b>Event title:</b></span></td>
	<td class="normalRightCell" width="70%" align="right"><input type="text" class="bginput" name="title" value="$event[title]" size="72" /></td>
</tr>
<tr class="highRow">
	<td class="highBothCell" colspan="2">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr valign="top">
				<td><textarea name="message" style="width: 686px; height: 180px;" wrap="virtual" id="tmessage">$event[message]</textarea></td>
			</tr>
		</table>
	</td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="30%" valign="top"><span class="normalfont"><b><a href="#" onClick="popAddBook(); return false;"><img src="$skin[images]/addbook.gif" alt="Address Book" border="0" /></a> Related email addresses:</b></span></td>
	<td class="normalRightCell" width="70%" align="right"><input type="text" class="bginput" name="addresses" id="addresses" value="$event[addresses]" autocomplete="off" onKeyUp="autoComplete(this, contacts);" size="72" /></td>
</tr>
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Event Date and Time</b></span></th>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="30%" valign="top"><span class="normalfont"><b>Event date:</b></span></td>
	<td class="highRightCell" width="70%" nowrap="nowrap"><span class="normalfont">
		<select name="frommonth" onChange="getDay(this.form, \'from\');">
			$frommonthsel
		</select>
		<select name="fromday" onChange="getDay(this.form, \'from\');">
			$fromdaysel
		</select>
		<select name="fromyear" onChange="getDay(this.form, \'from\');">
			$fromyearsel
		</select>
		<input type="text" name="fromdayname" class="highInactive" readonly="readonly" value="" size="12" />
		</span>
	</td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="30%" valign="top"><span class="normalfont"><b>Event time:</b></span></td>
	<td class="normalRightCell" width="70%">
		<table>
			<tr>
				<td nowrap="nowrap">
					<span class="normalfont">Starts at:</span>
				</td>
				<td nowrap="nowrap">
					<select name="fromhour" $timedisabled>
						$fromhoursel
					</select>
					<select name="fromminute" $timedisabled>
						$fromminutesel
					</select>
					<select name="fromampm" $timedisabled>
						<option value="am" $fromampmsel[am]>AM</option>
						<option value="pm" $fromampmsel[pm]>PM</option>
					</select>
				</td>
			</tr>
			<tr>
				<td nowrap="nowrap">
					<span class="normalfont">Duration:</span>
				</td>
				<td nowrap="nowrap">
					<span class="normalfont">
					<select name="durhours" $timedisabled>
						$durhourssel
					</select>
					<select name="durminutes" $timedisabled>
						$durminutessel
					</select></span>
				</td>
				<td nowrap="nowrap">
					<span class="normalfont">
					<input type="checkbox" name="allday" $alldaychecked id="allday" value="1" onClick="this.form.fromhour.disabled = this.form.fromminute.disabled = this.form.fromampm.disabled = this.form.durhours.disabled = this.form.durminutes.disabled = this.checked;"/> <label for="allday">All day event</label>
					</span>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Recurrence Options</b></span></th>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="30%" valign="top"><span class="normalfont"><b>Recurrence pattern:</b></span></td>
	<td class="highRightCell" width="70%"><span class="normalfont">
		<input type="radio" name="recurtype" id="recur_none" value="0" $typecheck[0] /> <label for="recur_none">One time event (never recur)</label>
	</span></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="30%" valign="top">&nbsp;</td>
	<td class="normalRightCell" width="70%"><span class="normalfont">
		<input type="radio" name="recurtype" id="recur_daily" value="1" $typecheck[1] /> <label for="recur_daily">Recur every &nbsp;</label><input type="text" class="bginput" name="daily_every" value="$daily_every" size="3" maxlength="3" onClick="this.form.recur_daily.checked = true;" /><label for="recur_daily">&nbsp; day(s)</label><br />
		<input type="radio" name="recurtype" id="recur_weekday" value="2" $typecheck[2] /> <label for="recur_weekday">Recur every weekday</label>
	</span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="30%" valign="top">&nbsp;</td>
	<td class="highRightCell" width="70%"><span class="normalfont">
		<input type="radio" name="recurtype" id="recur_weekly" value="3" $typecheck[3] /> <label for="recur_weekly">Recur every &nbsp;</label><input type="text" class="bginput" name="weekly_every" value="$weekly_every" size="3" maxlength="3" onClick="this.form.recur_weekly.checked = true;" /><label for="recur_weekly">&nbsp; week(s) on:</label></b></span><br />
			<table width="80%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="25%"><span class="smallfont"><input type="checkbox" name="weekly_repon[]" value="1" $weeklycheck[1] onClick="checkMain(this.form, \'weekly_repon\'); this.form.recur_weekly.checked = true;" id="weekly_sun" /> <label for="weekly_sun">$skin[cal_sun_long]</label></span></td>
					<td width="25%"><span class="smallfont"><input type="checkbox" name="weekly_repon[]" value="2" $weeklycheck[2] onClick="checkMain(this.form, \'weekly_repon\'); this.form.recur_weekly.checked = true;" id="weekly_mon" /> <label for="weekly_mon">$skin[cal_mon_long]</label></span></td>
					<td width="25%"><span class="smallfont"><input type="checkbox" name="weekly_repon[]" value="3" $weeklycheck[3] onClick="checkMain(this.form, \'weekly_repon\'); this.form.recur_weekly.checked = true;" id="weekly_tue" /> <label for="weekly_tue">$skin[cal_tue_long]</label></span></td>
					<td width="25%"><span class="smallfont"><input type="checkbox" name="weekly_repon[]" value="4" $weeklycheck[4] onClick="checkMain(this.form, \'weekly_repon\'); this.form.recur_weekly.checked = true;" id="weekly_wed" /> <label for="weekly_wed">$skin[cal_wed_long]</label></span></td>
				</tr>
				<tr>
					<td width="25%"><span class="smallfont"><input type="checkbox" name="weekly_repon[]" value="5" $weeklycheck[5] onClick="checkMain(this.form, \'weekly_repon\'); this.form.recur_weekly.checked = true;" id="weekly_thu" /> <label for="weekly_thu">$skin[cal_thu_long]</label></span></td>
					<td width="25%"><span class="smallfont"><input type="checkbox" name="weekly_repon[]" value="6" $weeklycheck[6] onClick="checkMain(this.form, \'weekly_repon\'); this.form.recur_weekly.checked = true;" id="weekly_fri" /> <label for="weekly_fri">$skin[cal_fri_long]</label></span></td>
					<td width="25%"><span class="smallfont"><input type="checkbox" name="weekly_repon[]" value="7" $weeklycheck[7] onClick="checkMain(this.form, \'weekly_repon\'); this.form.recur_weekly.checked = true;" id="weekly_sat" /> <label for="weekly_sat">$skin[cal_sat_long]</label></span></td>
					<td width="25%"><span class="smallfont"><input name="allbox" id="allbox" type="checkbox" value="Check All" $weeklycheck[all] title="Select/Deselect All" onClick="checkAll(this.form, \'weekly_repon\'); this.form.recur_weekly.checked = true;" /> <label for="allbox">All days</label></span></td>
				</tr>
			</table>
	</span></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="30%" valign="top">&nbsp;</td>
	<td class="normalRightCell" width="70%"><span class="normalfont">
		<input type="radio" name="recurtype" id="recur_monthly" value="4" $typecheck[4] /> <label for="recur_monthly">Recur on day</label>
			&nbsp;<select name="monthly_on" onChange="this.form.recur_monthly.checked = true;">
				$monthly_onsel
			</select>&nbsp;
			<label for="recur_monthly">every &nbsp;</label><input type="text" class="bginput" name="monthly_every" value="$monthly_every" size="3" maxlength="3" onClick="this.form.recur_monthly.checked = true;" /><label for="recur_monthly">&nbsp; months(s)
			</label>
	</span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="30%" valign="top">&nbsp;</td>
	<td class="highRightCell" width="70%"><span class="normalfont">
		<input type="radio" name="recurtype" id="recur_yearly" value="5" $typecheck[5] /> <label for="recur_yearly">Recur on
			&nbsp;</label><select name="yearly_month" onChange="this.form.recur_yearly.checked = true;">
				$yearly_monthsel
			</select>
			<select name="yearly_day" onChange="this.form.recur_yearly.checked = true;">
				$yearly_daysel
			</select>&nbsp;
			<label for="recur_yearly">every &nbsp;</label><input type="text" class="bginput" name="yearly_every" value="$yearly_every" size="3" maxlength="3" onClick="this.form.recur_yearly.checked = true;" /><label for="recur_yearly">&nbsp; year(s)
			</label>
	</span></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="30%" valign="top"><span class="normalfont"><b>Recurrence range:</b></span></td>
	<td class="normalRightCell" width="70%"><span class="normalfont">
		<input type="radio" name="recurend" id="recurend_none" value="0" $ending[0] /> <label for="recurend_none">No end date</label>
	</span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="30%" valign="top">&nbsp;</td>
	<td class="highRightCell" width="70%"><span class="normalfont">
		<input type="radio" name="recurend" id="recur_count" value="1" $ending[1] /> <label for="recur_count">End after &nbsp;</label><input type="text" class="bginput" name="end_after" value="$end_after" size="3" maxlength="3" onClick="this.form.recur_count.checked = true;" /><label for="recur_count">&nbsp; occurrences</label>
	</span></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="30%" valign="top">&nbsp;</td>
	<td class="normalRightCell" width="70%" nowrap="nowrap"><span class="normalfont">
		<input type="radio" name="recurend" id="recurend_date" value="2" $ending[2] /> <label for="recurend_date">End by:</label>
		&nbsp;<select name="tomonth" onChange="this.form.recurend_date.checked = true; getDay(this.form, \'to\');" onChange="">
			$tomonthsel
		</select>
		<select name="today" onChange="this.form.recurend_date.checked = true; getDay(this.form, \'to\');">
			$todaysel
		</select>
		<select name="toyear" onChange="this.form.recurend_date.checked = true; getDay(this.form, \'to\');">
			$toyearsel
		</select>
		<input type="text" name="todayname" class="normalInactive" readonly="readonly" value="" size="12" />
	</span></td>
</tr>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="700">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="submit" value="<%if $newevent %>Create Event<%else%>Update Event<%endif%>" />
		<input type="reset" class="bginput" name="reset" value="Reset Fields" />
	</td>
</tr>
</table>

</form>

<script language="JavaScript">
<!--
getDay(document.forms.eventform, \'from\');
getDay(document.forms.eventform, \'to\');
// -->
</script>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: ".(($newevent ) ? ("Add New Event") : ("Modify Event"))."</title>
$GLOBALS[css]
<script type=\\"text/javascript\\" src=\\"misc/common.js\\"></script>
<script type=\\"text/javascript\\" src=\\"misc/checkall.js\\"></script>
<script language=\\"JavaScript\\">
<!--

function popAddBook () {
     var url = \\"addressbook.view.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=mini\\";
     url += \\"&pre[list]=\\" + escape (document.forms.eventform.addresses.value);
     var hWnd = window.open(url,\\"AddBook\\",\\"width=520,height=250,resizable=yes,scrollbars=yes\\");
     if ((document.window != null) && (!hWnd.opener)) {
          hWnd.opener = document.window;
	 }
}

function getDay(tform, which) {
	var date = new Date(eval(\'tform.\'+which+\'year\').value, eval(\'tform.\'+which+\'month\').value - 1, eval(\'tform.\'+which+\'day\').value);
	eval(\'tform.\'+which+\'dayname\').value = DayNames[date.getDay()];
}

// Names of days that will be shown next to the date
var DayNames = new Array(\'({$GLOBALS[skin][cal_sun_long]})\', \'({$GLOBALS[skin][cal_mon_long]})\', \'({$GLOBALS[skin][cal_tue_long]})\', \'({$GLOBALS[skin][cal_wed_long]})\', \'({$GLOBALS[skin][cal_thu_long]})\', \'({$GLOBALS[skin][cal_fri_long]})\', \'({$GLOBALS[skin][cal_sat_long]})\');
var contacts = new Array($contactArray);

// -->
</script>
<script type=\\"text/javascript\\" src=\\"misc/autocomplete.js\\"></script>
</head>
<body>
$GLOBALS[header]

<form action=\\"calendar.event.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"eventform\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />
".((!$newevent ) ? ("<input type=\\"hidden\\" name=\\"eventid\\" value=\\"$event[eventid]\\" />") : (\'\'))."

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Event Information</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Event title:</b></span></td>
	<td class=\\"normalRightCell\\" width=\\"70%\\" align=\\"right\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"title\\" value=\\"$event[title]\\" size=\\"72\\" /></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\" colspan=\\"2\\">
		<table cellpadding=\\"0\\" cellspacing=\\"0\\" border=\\"0\\">
			<tr valign=\\"top\\">
				<td><textarea name=\\"message\\" style=\\"width: 686px; height: 180px;\\" wrap=\\"virtual\\" id=\\"tmessage\\">$event[message]</textarea></td>
			</tr>
		</table>
	</td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b><a href=\\"#\\" onClick=\\"popAddBook(); return false;\\"><img src=\\"{$GLOBALS[skin][images]}/addbook.gif\\" alt=\\"Address Book\\" border=\\"0\\" /></a> Related email addresses:</b></span></td>
	<td class=\\"normalRightCell\\" width=\\"70%\\" align=\\"right\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"addresses\\" id=\\"addresses\\" value=\\"$event[addresses]\\" autocomplete=\\"off\\" onKeyUp=\\"autoComplete(this, contacts);\\" size=\\"72\\" /></td>
</tr>
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Event Date and Time</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Event date:</b></span></td>
	<td class=\\"highRightCell\\" width=\\"70%\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">
		<select name=\\"frommonth\\" onChange=\\"getDay(this.form, \'from\');\\">
			$frommonthsel
		</select>
		<select name=\\"fromday\\" onChange=\\"getDay(this.form, \'from\');\\">
			$fromdaysel
		</select>
		<select name=\\"fromyear\\" onChange=\\"getDay(this.form, \'from\');\\">
			$fromyearsel
		</select>
		<input type=\\"text\\" name=\\"fromdayname\\" class=\\"highInactive\\" readonly=\\"readonly\\" value=\\"\\" size=\\"12\\" />
		</span>
	</td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Event time:</b></span></td>
	<td class=\\"normalRightCell\\" width=\\"70%\\">
		<table>
			<tr>
				<td nowrap=\\"nowrap\\">
					<span class=\\"normalfont\\">Starts at:</span>
				</td>
				<td nowrap=\\"nowrap\\">
					<select name=\\"fromhour\\" $timedisabled>
						$fromhoursel
					</select>
					<select name=\\"fromminute\\" $timedisabled>
						$fromminutesel
					</select>
					<select name=\\"fromampm\\" $timedisabled>
						<option value=\\"am\\" $fromampmsel[am]>AM</option>
						<option value=\\"pm\\" $fromampmsel[pm]>PM</option>
					</select>
				</td>
			</tr>
			<tr>
				<td nowrap=\\"nowrap\\">
					<span class=\\"normalfont\\">Duration:</span>
				</td>
				<td nowrap=\\"nowrap\\">
					<span class=\\"normalfont\\">
					<select name=\\"durhours\\" $timedisabled>
						$durhourssel
					</select>
					<select name=\\"durminutes\\" $timedisabled>
						$durminutessel
					</select></span>
				</td>
				<td nowrap=\\"nowrap\\">
					<span class=\\"normalfont\\">
					<input type=\\"checkbox\\" name=\\"allday\\" $alldaychecked id=\\"allday\\" value=\\"1\\" onClick=\\"this.form.fromhour.disabled = this.form.fromminute.disabled = this.form.fromampm.disabled = this.form.durhours.disabled = this.form.durminutes.disabled = this.checked;\\"/> <label for=\\"allday\\">All day event</label>
					</span>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Recurrence Options</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Recurrence pattern:</b></span></td>
	<td class=\\"highRightCell\\" width=\\"70%\\"><span class=\\"normalfont\\">
		<input type=\\"radio\\" name=\\"recurtype\\" id=\\"recur_none\\" value=\\"0\\" $typecheck[0] /> <label for=\\"recur_none\\">One time event (never recur)</label>
	</span></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"30%\\" valign=\\"top\\">&nbsp;</td>
	<td class=\\"normalRightCell\\" width=\\"70%\\"><span class=\\"normalfont\\">
		<input type=\\"radio\\" name=\\"recurtype\\" id=\\"recur_daily\\" value=\\"1\\" $typecheck[1] /> <label for=\\"recur_daily\\">Recur every &nbsp;</label><input type=\\"text\\" class=\\"bginput\\" name=\\"daily_every\\" value=\\"$daily_every\\" size=\\"3\\" maxlength=\\"3\\" onClick=\\"this.form.recur_daily.checked = true;\\" /><label for=\\"recur_daily\\">&nbsp; day(s)</label><br />
		<input type=\\"radio\\" name=\\"recurtype\\" id=\\"recur_weekday\\" value=\\"2\\" $typecheck[2] /> <label for=\\"recur_weekday\\">Recur every weekday</label>
	</span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"30%\\" valign=\\"top\\">&nbsp;</td>
	<td class=\\"highRightCell\\" width=\\"70%\\"><span class=\\"normalfont\\">
		<input type=\\"radio\\" name=\\"recurtype\\" id=\\"recur_weekly\\" value=\\"3\\" $typecheck[3] /> <label for=\\"recur_weekly\\">Recur every &nbsp;</label><input type=\\"text\\" class=\\"bginput\\" name=\\"weekly_every\\" value=\\"$weekly_every\\" size=\\"3\\" maxlength=\\"3\\" onClick=\\"this.form.recur_weekly.checked = true;\\" /><label for=\\"recur_weekly\\">&nbsp; week(s) on:</label></b></span><br />
			<table width=\\"80%\\" border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\">
				<tr>
					<td width=\\"25%\\"><span class=\\"smallfont\\"><input type=\\"checkbox\\" name=\\"weekly_repon[]\\" value=\\"1\\" $weeklycheck[1] onClick=\\"checkMain(this.form, \'weekly_repon\'); this.form.recur_weekly.checked = true;\\" id=\\"weekly_sun\\" /> <label for=\\"weekly_sun\\">{$GLOBALS[skin][cal_sun_long]}</label></span></td>
					<td width=\\"25%\\"><span class=\\"smallfont\\"><input type=\\"checkbox\\" name=\\"weekly_repon[]\\" value=\\"2\\" $weeklycheck[2] onClick=\\"checkMain(this.form, \'weekly_repon\'); this.form.recur_weekly.checked = true;\\" id=\\"weekly_mon\\" /> <label for=\\"weekly_mon\\">{$GLOBALS[skin][cal_mon_long]}</label></span></td>
					<td width=\\"25%\\"><span class=\\"smallfont\\"><input type=\\"checkbox\\" name=\\"weekly_repon[]\\" value=\\"3\\" $weeklycheck[3] onClick=\\"checkMain(this.form, \'weekly_repon\'); this.form.recur_weekly.checked = true;\\" id=\\"weekly_tue\\" /> <label for=\\"weekly_tue\\">{$GLOBALS[skin][cal_tue_long]}</label></span></td>
					<td width=\\"25%\\"><span class=\\"smallfont\\"><input type=\\"checkbox\\" name=\\"weekly_repon[]\\" value=\\"4\\" $weeklycheck[4] onClick=\\"checkMain(this.form, \'weekly_repon\'); this.form.recur_weekly.checked = true;\\" id=\\"weekly_wed\\" /> <label for=\\"weekly_wed\\">{$GLOBALS[skin][cal_wed_long]}</label></span></td>
				</tr>
				<tr>
					<td width=\\"25%\\"><span class=\\"smallfont\\"><input type=\\"checkbox\\" name=\\"weekly_repon[]\\" value=\\"5\\" $weeklycheck[5] onClick=\\"checkMain(this.form, \'weekly_repon\'); this.form.recur_weekly.checked = true;\\" id=\\"weekly_thu\\" /> <label for=\\"weekly_thu\\">{$GLOBALS[skin][cal_thu_long]}</label></span></td>
					<td width=\\"25%\\"><span class=\\"smallfont\\"><input type=\\"checkbox\\" name=\\"weekly_repon[]\\" value=\\"6\\" $weeklycheck[6] onClick=\\"checkMain(this.form, \'weekly_repon\'); this.form.recur_weekly.checked = true;\\" id=\\"weekly_fri\\" /> <label for=\\"weekly_fri\\">{$GLOBALS[skin][cal_fri_long]}</label></span></td>
					<td width=\\"25%\\"><span class=\\"smallfont\\"><input type=\\"checkbox\\" name=\\"weekly_repon[]\\" value=\\"7\\" $weeklycheck[7] onClick=\\"checkMain(this.form, \'weekly_repon\'); this.form.recur_weekly.checked = true;\\" id=\\"weekly_sat\\" /> <label for=\\"weekly_sat\\">{$GLOBALS[skin][cal_sat_long]}</label></span></td>
					<td width=\\"25%\\"><span class=\\"smallfont\\"><input name=\\"allbox\\" id=\\"allbox\\" type=\\"checkbox\\" value=\\"Check All\\" $weeklycheck[all] title=\\"Select/Deselect All\\" onClick=\\"checkAll(this.form, \'weekly_repon\'); this.form.recur_weekly.checked = true;\\" /> <label for=\\"allbox\\">All days</label></span></td>
				</tr>
			</table>
	</span></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"30%\\" valign=\\"top\\">&nbsp;</td>
	<td class=\\"normalRightCell\\" width=\\"70%\\"><span class=\\"normalfont\\">
		<input type=\\"radio\\" name=\\"recurtype\\" id=\\"recur_monthly\\" value=\\"4\\" $typecheck[4] /> <label for=\\"recur_monthly\\">Recur on day</label>
			&nbsp;<select name=\\"monthly_on\\" onChange=\\"this.form.recur_monthly.checked = true;\\">
				$monthly_onsel
			</select>&nbsp;
			<label for=\\"recur_monthly\\">every &nbsp;</label><input type=\\"text\\" class=\\"bginput\\" name=\\"monthly_every\\" value=\\"$monthly_every\\" size=\\"3\\" maxlength=\\"3\\" onClick=\\"this.form.recur_monthly.checked = true;\\" /><label for=\\"recur_monthly\\">&nbsp; months(s)
			</label>
	</span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"30%\\" valign=\\"top\\">&nbsp;</td>
	<td class=\\"highRightCell\\" width=\\"70%\\"><span class=\\"normalfont\\">
		<input type=\\"radio\\" name=\\"recurtype\\" id=\\"recur_yearly\\" value=\\"5\\" $typecheck[5] /> <label for=\\"recur_yearly\\">Recur on
			&nbsp;</label><select name=\\"yearly_month\\" onChange=\\"this.form.recur_yearly.checked = true;\\">
				$yearly_monthsel
			</select>
			<select name=\\"yearly_day\\" onChange=\\"this.form.recur_yearly.checked = true;\\">
				$yearly_daysel
			</select>&nbsp;
			<label for=\\"recur_yearly\\">every &nbsp;</label><input type=\\"text\\" class=\\"bginput\\" name=\\"yearly_every\\" value=\\"$yearly_every\\" size=\\"3\\" maxlength=\\"3\\" onClick=\\"this.form.recur_yearly.checked = true;\\" /><label for=\\"recur_yearly\\">&nbsp; year(s)
			</label>
	</span></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Recurrence range:</b></span></td>
	<td class=\\"normalRightCell\\" width=\\"70%\\"><span class=\\"normalfont\\">
		<input type=\\"radio\\" name=\\"recurend\\" id=\\"recurend_none\\" value=\\"0\\" $ending[0] /> <label for=\\"recurend_none\\">No end date</label>
	</span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"30%\\" valign=\\"top\\">&nbsp;</td>
	<td class=\\"highRightCell\\" width=\\"70%\\"><span class=\\"normalfont\\">
		<input type=\\"radio\\" name=\\"recurend\\" id=\\"recur_count\\" value=\\"1\\" $ending[1] /> <label for=\\"recur_count\\">End after &nbsp;</label><input type=\\"text\\" class=\\"bginput\\" name=\\"end_after\\" value=\\"$end_after\\" size=\\"3\\" maxlength=\\"3\\" onClick=\\"this.form.recur_count.checked = true;\\" /><label for=\\"recur_count\\">&nbsp; occurrences</label>
	</span></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"30%\\" valign=\\"top\\">&nbsp;</td>
	<td class=\\"normalRightCell\\" width=\\"70%\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">
		<input type=\\"radio\\" name=\\"recurend\\" id=\\"recurend_date\\" value=\\"2\\" $ending[2] /> <label for=\\"recurend_date\\">End by:</label>
		&nbsp;<select name=\\"tomonth\\" onChange=\\"this.form.recurend_date.checked = true; getDay(this.form, \'to\');\\" onChange=\\"\\">
			$tomonthsel
		</select>
		<select name=\\"today\\" onChange=\\"this.form.recurend_date.checked = true; getDay(this.form, \'to\');\\">
			$todaysel
		</select>
		<select name=\\"toyear\\" onChange=\\"this.form.recurend_date.checked = true; getDay(this.form, \'to\');\\">
			$toyearsel
		</select>
		<input type=\\"text\\" name=\\"todayname\\" class=\\"normalInactive\\" readonly=\\"readonly\\" value=\\"\\" size=\\"12\\" />
	</span></td>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"".(($newevent ) ? ("Create Event") : ("Update Event"))."\\" />
		<input type=\\"reset\\" class=\\"bginput\\" name=\\"reset\\" value=\\"Reset Fields\\" />
	</td>
</tr>
</table>

</form>

<script language=\\"JavaScript\\">
<!--
getDay(document.forms.eventform, \'from\');
getDay(document.forms.eventform, \'to\');
// -->
</script>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'calendar_daily' => 
  array (
    'templategroupid' => '18',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Calendar - Daily View</title>
$css
<script language="JavaScript">
<!--

function getDay(tform, which) {
	var date = new Date(eval(\'tform.\'+which+\'year\').value, eval(\'tform.\'+which+\'month\').value - 1, eval(\'tform.\'+which+\'day\').value);
	eval(\'tform.\'+which+\'dayname\').value = DayNames[date.getDay()];
}

// Names of days that will be shown next to the date
var DayNames = new Array(\'($skin[cal_sun_long])\', \'($skin[cal_mon_long])\', \'($skin[cal_tue_long])\', \'($skin[cal_wed_long])\', \'($skin[cal_thu_long])\', \'($skin[cal_fri_long])\', \'($skin[cal_sat_long])\');

// -->
</script>
</head>
<body>
$header

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
	<tr>
		<td width="99%" valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
				<tr class="headerRow">
					<th class="headerBothCell" colspan="$totalcols">
					<span class="normalfonttablehead">
					<a href="calendar.display.php?cmd=day&date=$prevday"><span class="normalfonttablehead">&laquo;</span></a>
					$dayname &nbsp; $day$suffix $monthname, $year
					<a href="calendar.display.php?cmd=day&date=$nextday"><span class="normalfonttablehead">&raquo;</span></a>
					</span></th>
				</tr>
				$daybits
			</table>
		</td>
		<td width="1%" valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
				$current_month
			</table>
			<br />
			<form action="calendar.display.php" method="get">
			<input type="hidden" name="cmd" value="month" />
			<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
				<tr class="headerRow">
					<th class="headerBothCell"><span class="normalfonttablehead">Show me...</span></td>
				</tr>
				<tr class="normalRow">
					<td class="normalBothCell" nowrap="nowrap"><span class="normalfont">
						<select name="month" onChange="this.form.go.disabled = (this.selectedIndex == 1);">
							<option value="0">Whole year</option>
							<option value="-1">-------------</option>
							$monthsel
						</select>
						<select name="year">
							$yearsel
						</select>
						<input type="submit" id="go" class="bginput" value="Go" />
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
	<tr>
		<td colspan="2">
			<form action="calendar.event.php" method="post" name="eventform">
			<input type="hidden" name="cmd" value="update" />
			<input type="hidden" name="message" value="" />
			<input type="hidden" name="addresses" value="" />
			<input type="hidden" name="recurtype" value="0" />
			<input type="hidden" name="recurend" value="0" />
			<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
				<tr class="headerRow">
					<th class="headerBothCell" colspan="3"><span class="normalfonttablehead">Add New Event</span></td>
				</tr>
				<tr class="normalRow">
					<td class="normalLeftCell" nowrap="nowrap" valign="top">
						<table style="height: 40px;">
							<tr style="height: 50%;">
								<td nowrap="nowrap">
									<span class="normalfont">Event title:</span>
								</td>
							</tr>
							<tr style="height: 50%;">
								<td nowrap="nowrap">
									<input type="text" class="bginput" name="title" size="25" />
								</td>
							</tr>
						</table>
					</td>
					<td class="normalCell" nowrap="nowrap" valign="top">
						<table style="height: 40px;">
							<tr style="height: 50%;">
								<td nowrap="nowrap">
									<span class="normalfont">Event date:</span>
									&nbsp;<input type="text" name="fromdayname" class="normalInactive" readonly="readonly" value="" size="12" />
								</td>
							</tr>
							<tr style="height: 50%;">
								<td nowrap="nowrap">
									<select name="frommonth" onChange="getDay(this.form, \'from\');">
										$frommonthsel
									</select>
									<select name="fromday" onChange="getDay(this.form, \'from\');">
										$fromdaysel
									</select>
									<select name="fromyear" onChange="getDay(this.form, \'from\');">
										$fromyearsel
									</select>
								</td>
							</tr>
						</table>
					</td>
					<td class="normalRightCell" valign="top">
						<table style="height: 40px;">
							<tr style="height: 50%;">
								<td nowrap="nowrap">
									<span class="normalfont">Starts at:</span>
								</td>
								<td nowrap="nowrap">
									<select name="fromhour" $timedisabled>
										$fromhoursel
									</select>
									<select name="fromminute" $timedisabled>
										$fromminutesel
									</select>
									<select name="fromampm" $timedisabled>
										<option value="am" $fromampmsel[am]>AM</option>
										<option value="pm" $fromampmsel[pm]>PM</option>
									</select>
								</td>
							</tr>
							<tr style="height: 50%;">
								<td nowrap="nowrap">
									<span class="normalfont">Duration:</span>
								</td>
								<td nowrap="nowrap">
									<span class="normalfont">
									<select name="durhours" $timedisabled>
										$durhourssel
									</select>
									<select name="durminutes" $timedisabled>
										$durminutessel
									</select></span>
								</td>
								<td nowrap="nowrap">
									<span class="normalfont">
									<input type="checkbox" name="allday" $alldaychecked id="allday" value="1" onClick="this.form.fromhour.disabled = this.form.fromminute.disabled = this.form.fromampm.disabled = this.form.durhours.disabled = this.form.durminutes.disabled = this.checked;"/> <label for="allday">All day event</label>
									</span>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center" nowrap="nowrap" colspan="3"><span class="normalfont">
						<input type="submit" class="bginput" name="submit" value="Create New Event" />
						<input type="submit" class="bginput" name="submit" value="Use Advanced Form" onClick="this.form.fromhour.disabled = this.form.fromminute.disabled = this.form.fromampm.disabled = this.form.durhours.disabled = this.form.durminutes.disabled = false; this.form.cmd.value = \'reload\';" />
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>

<script language="JavaScript">
<!--
getDay(document.forms.eventform, \'from\');
// -->
</script>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Calendar - Daily View</title>
$GLOBALS[css]
<script language=\\"JavaScript\\">
<!--

function getDay(tform, which) {
	var date = new Date(eval(\'tform.\'+which+\'year\').value, eval(\'tform.\'+which+\'month\').value - 1, eval(\'tform.\'+which+\'day\').value);
	eval(\'tform.\'+which+\'dayname\').value = DayNames[date.getDay()];
}

// Names of days that will be shown next to the date
var DayNames = new Array(\'({$GLOBALS[skin][cal_sun_long]})\', \'({$GLOBALS[skin][cal_mon_long]})\', \'({$GLOBALS[skin][cal_tue_long]})\', \'({$GLOBALS[skin][cal_wed_long]})\', \'({$GLOBALS[skin][cal_thu_long]})\', \'({$GLOBALS[skin][cal_fri_long]})\', \'({$GLOBALS[skin][cal_sat_long]})\');

// -->
</script>
</head>
<body>
$GLOBALS[header]

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
	<tr>
		<td width=\\"99%\\" valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\" colspan=\\"$totalcols\\">
					<span class=\\"normalfonttablehead\\">
					<a href=\\"calendar.display.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=day&date=$prevday\\"><span class=\\"normalfonttablehead\\">&laquo;</span></a>
					$dayname &nbsp; $day$suffix $monthname, $year
					<a href=\\"calendar.display.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=day&date=$nextday\\"><span class=\\"normalfonttablehead\\">&raquo;</span></a>
					</span></th>
				</tr>
				$daybits
			</table>
		</td>
		<td width=\\"1%\\" valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
				$current_month
			</table>
			<br />
			<form action=\\"calendar.display.php{$GLOBALS[session_url]}\\" method=\\"get\\">
			<input type=\\"hidden\\" name=\\"cmd\\" value=\\"month\\" />
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\">Show me...</span></td>
				</tr>
				<tr class=\\"normalRow\\">
					<td class=\\"normalBothCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">
						<select name=\\"month\\" onChange=\\"this.form.go.disabled = (this.selectedIndex == 1);\\">
							<option value=\\"0\\">Whole year</option>
							<option value=\\"-1\\">-------------</option>
							$monthsel
						</select>
						<select name=\\"year\\">
							$yearsel
						</select>
						<input type=\\"submit\\" id=\\"go\\" class=\\"bginput\\" value=\\"Go\\" />
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
	<tr>
		<td colspan=\\"2\\">
			<form action=\\"calendar.event.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"eventform\\">
			<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />
			<input type=\\"hidden\\" name=\\"message\\" value=\\"\\" />
			<input type=\\"hidden\\" name=\\"addresses\\" value=\\"\\" />
			<input type=\\"hidden\\" name=\\"recurtype\\" value=\\"0\\" />
			<input type=\\"hidden\\" name=\\"recurend\\" value=\\"0\\" />
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\" colspan=\\"3\\"><span class=\\"normalfonttablehead\\">Add New Event</span></td>
				</tr>
				<tr class=\\"normalRow\\">
					<td class=\\"normalLeftCell\\" nowrap=\\"nowrap\\" valign=\\"top\\">
						<table style=\\"height: 40px;\\">
							<tr style=\\"height: 50%;\\">
								<td nowrap=\\"nowrap\\">
									<span class=\\"normalfont\\">Event title:</span>
								</td>
							</tr>
							<tr style=\\"height: 50%;\\">
								<td nowrap=\\"nowrap\\">
									<input type=\\"text\\" class=\\"bginput\\" name=\\"title\\" size=\\"25\\" />
								</td>
							</tr>
						</table>
					</td>
					<td class=\\"normalCell\\" nowrap=\\"nowrap\\" valign=\\"top\\">
						<table style=\\"height: 40px;\\">
							<tr style=\\"height: 50%;\\">
								<td nowrap=\\"nowrap\\">
									<span class=\\"normalfont\\">Event date:</span>
									&nbsp;<input type=\\"text\\" name=\\"fromdayname\\" class=\\"normalInactive\\" readonly=\\"readonly\\" value=\\"\\" size=\\"12\\" />
								</td>
							</tr>
							<tr style=\\"height: 50%;\\">
								<td nowrap=\\"nowrap\\">
									<select name=\\"frommonth\\" onChange=\\"getDay(this.form, \'from\');\\">
										$frommonthsel
									</select>
									<select name=\\"fromday\\" onChange=\\"getDay(this.form, \'from\');\\">
										$fromdaysel
									</select>
									<select name=\\"fromyear\\" onChange=\\"getDay(this.form, \'from\');\\">
										$fromyearsel
									</select>
								</td>
							</tr>
						</table>
					</td>
					<td class=\\"normalRightCell\\" valign=\\"top\\">
						<table style=\\"height: 40px;\\">
							<tr style=\\"height: 50%;\\">
								<td nowrap=\\"nowrap\\">
									<span class=\\"normalfont\\">Starts at:</span>
								</td>
								<td nowrap=\\"nowrap\\">
									<select name=\\"fromhour\\" $timedisabled>
										$fromhoursel
									</select>
									<select name=\\"fromminute\\" $timedisabled>
										$fromminutesel
									</select>
									<select name=\\"fromampm\\" $timedisabled>
										<option value=\\"am\\" $fromampmsel[am]>AM</option>
										<option value=\\"pm\\" $fromampmsel[pm]>PM</option>
									</select>
								</td>
							</tr>
							<tr style=\\"height: 50%;\\">
								<td nowrap=\\"nowrap\\">
									<span class=\\"normalfont\\">Duration:</span>
								</td>
								<td nowrap=\\"nowrap\\">
									<span class=\\"normalfont\\">
									<select name=\\"durhours\\" $timedisabled>
										$durhourssel
									</select>
									<select name=\\"durminutes\\" $timedisabled>
										$durminutessel
									</select></span>
								</td>
								<td nowrap=\\"nowrap\\">
									<span class=\\"normalfont\\">
									<input type=\\"checkbox\\" name=\\"allday\\" $alldaychecked id=\\"allday\\" value=\\"1\\" onClick=\\"this.form.fromhour.disabled = this.form.fromminute.disabled = this.form.fromampm.disabled = this.form.durhours.disabled = this.form.durminutes.disabled = this.checked;\\"/> <label for=\\"allday\\">All day event</label>
									</span>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align=\\"center\\" nowrap=\\"nowrap\\" colspan=\\"3\\"><span class=\\"normalfont\\">
						<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Create New Event\\" />
						<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Use Advanced Form\\" onClick=\\"this.form.fromhour.disabled = this.form.fromminute.disabled = this.form.fromampm.disabled = this.form.durhours.disabled = this.form.durminutes.disabled = false; this.form.cmd.value = \'reload\';\\" />
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>

<script language=\\"JavaScript\\">
<!--
getDay(document.forms.eventform, \'from\');
// -->
</script>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'calendar_daily_emptycell' => 
  array (
    'templategroupid' => '18',
    'user_data' => '		<td class="{$class}{$type}Cell" width="$width_percent%" colspan="$colspan" $mouseJS>&nbsp;</td>
',
    'parsed_data' => '"		<td class=\\"{$class}{$type}Cell\\" width=\\"$width_percent%\\" colspan=\\"$colspan\\" $mouseJS>&nbsp;</td>
"',
  ),
  'calendar_daily_eventcell' => 
  array (
    'templategroupid' => '18',
    'user_data' => '		<td class="highRightCell" rowspan="$rowspan" colspan="1" width="$width_percent%" align="left" valign="top" nowrap="nowrap"><span class="normalfont"><nobr><a title="$event[title]" href="calendar.event.php?eventid=$event[eventid]">$event[shorttitle]</a></span><%if $rowspan > 1 %></nobr><br /><nobr><%else%>&nbsp;<%endif%><span class="smallfont"><%if $event[\'allday\'] %>All day<%else%>$event[from_time]-$event[to_time]<%endif%></nobr></span></td>
',
    'parsed_data' => '"		<td class=\\"highRightCell\\" rowspan=\\"$rowspan\\" colspan=\\"1\\" width=\\"$width_percent%\\" align=\\"left\\" valign=\\"top\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\"><nobr><a title=\\"$event[title]\\" href=\\"calendar.event.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}eventid=$event[eventid]\\">$event[shorttitle]</a></span>".(($rowspan > 1 ) ? ("</nobr><br /><nobr>") : ("&nbsp;"))."<span class=\\"smallfont\\">".(($event[\'allday\'] ) ? ("All day") : ("$event[from_time]-$event[to_time]"))."</nobr></span></td>
"',
  ),
  'calendar_daily_hour' => 
  array (
    'templategroupid' => '18',
    'user_data' => '		<td class="<%if intval($hour) == $hour %>high<%else%>normal<%endif%>BothCell" $mouseJS width="1%" align="right">&nbsp;$displayhour</td>
',
    'parsed_data' => '"		<td class=\\"".((intval($hour) == $hour ) ? ("high") : ("normal"))."BothCell\\" $mouseJS width=\\"1%\\" align=\\"right\\">&nbsp;$displayhour</td>
"',
  ),
  'calendar_monthly' => 
  array (
    'templategroupid' => '18',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Calendar - Monthly View</title>
$css
<script language="JavaScript">
<!--

function getDay(tform, which) {
	var date = new Date(eval(\'tform.\'+which+\'year\').value, eval(\'tform.\'+which+\'month\').value - 1, eval(\'tform.\'+which+\'day\').value);
	eval(\'tform.\'+which+\'dayname\').value = DayNames[date.getDay()];
}

// Names of days that will be shown next to the date
var DayNames = new Array(\'($skin[cal_sun_long])\', \'($skin[cal_mon_long])\', \'($skin[cal_tue_long])\', \'($skin[cal_wed_long])\', \'($skin[cal_thu_long])\', \'($skin[cal_fri_long])\', \'($skin[cal_sat_long])\');

// -->
</script>
</head>
<body>
$header

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
	<tr>
		<td width="99%" valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
				$current_month
			</table>
		</td>
		<td width="1%" valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
				$prev_month
				<tr>
					<td colspan="8">&nbsp;</td>
				</tr>
				$next_month
			</table>
			<br />
			<form action="calendar.display.php" method="get">
			<input type="hidden" name="cmd" value="month" />
			<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
				<tr class="headerRow">
					<th class="headerBothCell"><span class="normalfonttablehead">Show me...</span></td>
				</tr>
				<tr class="normalRow">
					<td class="normalBothCell" nowrap="nowrap"><span class="normalfont">
						<select name="month" onChange="this.form.go.disabled = (this.selectedIndex == 1);">
							<option value="0">Whole year</option>
							<option value="-1">-------------</option>
							$monthsel
						</select>
						<select name="year">
							$yearsel
						</select>
						<input type="submit" id="go" class="bginput" value="Go" />
					</span></td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
	<tr>
		<td colspan="2">
			<form action="calendar.event.php" method="post" name="eventform">
			<input type="hidden" name="cmd" value="update" />
			<input type="hidden" name="message" value="" />
			<input type="hidden" name="addresses" value="" />
			<input type="hidden" name="recurtype" value="0" />
			<input type="hidden" name="recurend" value="0" />
			<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
				<tr class="headerRow">
					<th class="headerBothCell" colspan="3"><span class="normalfonttablehead">Add New Event</span></td>
				</tr>
				<tr class="normalRow">
					<td class="normalLeftCell" nowrap="nowrap" valign="top">
						<table style="height: 40px;">
							<tr style="height: 50%;">
								<td nowrap="nowrap">
									<span class="normalfont">Event title:</span>
								</td>
							</tr>
							<tr style="height: 50%;">
								<td nowrap="nowrap">
									<input type="text" class="bginput" name="title" size="25" />
								</td>
							</tr>
						</table>
					</td>
					<td class="normalCell" nowrap="nowrap" valign="top">
						<table style="height: 40px;">
							<tr style="height: 50%;">
								<td nowrap="nowrap">
									<span class="normalfont">Event date:</span>
									&nbsp;<input type="text" name="fromdayname" class="normalInactive" readonly="readonly" value="$skin[cal_sun_long]" size="12" />
								</td>
							</tr>
							<tr style="height: 50%;">
								<td nowrap="nowrap">
									<select name="frommonth" onChange="getDay(this.form, \'from\');">
										$frommonthsel
									</select>
									<select name="fromday" onChange="getDay(this.form, \'from\');">
										$fromdaysel
									</select>
									<select name="fromyear" onChange="getDay(this.form, \'from\');">
										$fromyearsel
									</select>
								</td>
							</tr>
						</table>
					</td>
					<td class="normalRightCell" valign="top">
						<table style="height: 40px;">
							<tr style="height: 50%;">
								<td nowrap="nowrap">
									<span class="normalfont">Starts at:</span>
								</td>
								<td nowrap="nowrap">
									<select name="fromhour" $timedisabled>
										$fromhoursel
									</select>
									<select name="fromminute" $timedisabled>
										$fromminutesel
									</select>
									<select name="fromampm" $timedisabled>
										<option value="am" $fromampmsel[am]>AM</option>
										<option value="pm" $fromampmsel[pm]>PM</option>
									</select>
								</td>
							</tr>
							<tr style="height: 50%;">
								<td nowrap="nowrap">
									<span class="normalfont">Duration:</span>
								</td>
								<td nowrap="nowrap">
									<span class="normalfont">
									<select name="durhours" $timedisabled>
										$durhourssel
									</select>
									<select name="durminutes" $timedisabled>
										$durminutessel
									</select></span>
								</td>
								<td nowrap="nowrap">
									<span class="normalfont">
									<input type="checkbox" name="allday" $alldaychecked id="allday" value="1" onClick="this.form.fromhour.disabled = this.form.fromminute.disabled = this.form.fromampm.disabled = this.form.durhours.disabled = this.form.durminutes.disabled = this.checked;"/> <label for="allday">All day event</label>
									</span>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center" nowrap="nowrap" colspan="3"><span class="normalfont">
						<input type="submit" class="bginput" name="submit" value="Create New Event" />
						<input type="submit" class="bginput" name="submit" value="Use Advanced Form" onClick="this.form.fromhour.disabled = this.form.fromminute.disabled = this.form.fromampm.disabled = this.form.durhours.disabled = this.form.durminutes.disabled = false; this.form.cmd.value = \'reload\';" />
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>

<script language="JavaScript">
<!--
getDay(document.forms.eventform, \'from\');
// -->
</script>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Calendar - Monthly View</title>
$GLOBALS[css]
<script language=\\"JavaScript\\">
<!--

function getDay(tform, which) {
	var date = new Date(eval(\'tform.\'+which+\'year\').value, eval(\'tform.\'+which+\'month\').value - 1, eval(\'tform.\'+which+\'day\').value);
	eval(\'tform.\'+which+\'dayname\').value = DayNames[date.getDay()];
}

// Names of days that will be shown next to the date
var DayNames = new Array(\'({$GLOBALS[skin][cal_sun_long]})\', \'({$GLOBALS[skin][cal_mon_long]})\', \'({$GLOBALS[skin][cal_tue_long]})\', \'({$GLOBALS[skin][cal_wed_long]})\', \'({$GLOBALS[skin][cal_thu_long]})\', \'({$GLOBALS[skin][cal_fri_long]})\', \'({$GLOBALS[skin][cal_sat_long]})\');

// -->
</script>
</head>
<body>
$GLOBALS[header]

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
	<tr>
		<td width=\\"99%\\" valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
				$current_month
			</table>
		</td>
		<td width=\\"1%\\" valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
				$prev_month
				<tr>
					<td colspan=\\"8\\">&nbsp;</td>
				</tr>
				$next_month
			</table>
			<br />
			<form action=\\"calendar.display.php{$GLOBALS[session_url]}\\" method=\\"get\\">
			<input type=\\"hidden\\" name=\\"cmd\\" value=\\"month\\" />
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\">Show me...</span></td>
				</tr>
				<tr class=\\"normalRow\\">
					<td class=\\"normalBothCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">
						<select name=\\"month\\" onChange=\\"this.form.go.disabled = (this.selectedIndex == 1);\\">
							<option value=\\"0\\">Whole year</option>
							<option value=\\"-1\\">-------------</option>
							$monthsel
						</select>
						<select name=\\"year\\">
							$yearsel
						</select>
						<input type=\\"submit\\" id=\\"go\\" class=\\"bginput\\" value=\\"Go\\" />
					</span></td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
	<tr>
		<td colspan=\\"2\\">
			<form action=\\"calendar.event.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"eventform\\">
			<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />
			<input type=\\"hidden\\" name=\\"message\\" value=\\"\\" />
			<input type=\\"hidden\\" name=\\"addresses\\" value=\\"\\" />
			<input type=\\"hidden\\" name=\\"recurtype\\" value=\\"0\\" />
			<input type=\\"hidden\\" name=\\"recurend\\" value=\\"0\\" />
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\" colspan=\\"3\\"><span class=\\"normalfonttablehead\\">Add New Event</span></td>
				</tr>
				<tr class=\\"normalRow\\">
					<td class=\\"normalLeftCell\\" nowrap=\\"nowrap\\" valign=\\"top\\">
						<table style=\\"height: 40px;\\">
							<tr style=\\"height: 50%;\\">
								<td nowrap=\\"nowrap\\">
									<span class=\\"normalfont\\">Event title:</span>
								</td>
							</tr>
							<tr style=\\"height: 50%;\\">
								<td nowrap=\\"nowrap\\">
									<input type=\\"text\\" class=\\"bginput\\" name=\\"title\\" size=\\"25\\" />
								</td>
							</tr>
						</table>
					</td>
					<td class=\\"normalCell\\" nowrap=\\"nowrap\\" valign=\\"top\\">
						<table style=\\"height: 40px;\\">
							<tr style=\\"height: 50%;\\">
								<td nowrap=\\"nowrap\\">
									<span class=\\"normalfont\\">Event date:</span>
									&nbsp;<input type=\\"text\\" name=\\"fromdayname\\" class=\\"normalInactive\\" readonly=\\"readonly\\" value=\\"{$GLOBALS[skin][cal_sun_long]}\\" size=\\"12\\" />
								</td>
							</tr>
							<tr style=\\"height: 50%;\\">
								<td nowrap=\\"nowrap\\">
									<select name=\\"frommonth\\" onChange=\\"getDay(this.form, \'from\');\\">
										$frommonthsel
									</select>
									<select name=\\"fromday\\" onChange=\\"getDay(this.form, \'from\');\\">
										$fromdaysel
									</select>
									<select name=\\"fromyear\\" onChange=\\"getDay(this.form, \'from\');\\">
										$fromyearsel
									</select>
								</td>
							</tr>
						</table>
					</td>
					<td class=\\"normalRightCell\\" valign=\\"top\\">
						<table style=\\"height: 40px;\\">
							<tr style=\\"height: 50%;\\">
								<td nowrap=\\"nowrap\\">
									<span class=\\"normalfont\\">Starts at:</span>
								</td>
								<td nowrap=\\"nowrap\\">
									<select name=\\"fromhour\\" $timedisabled>
										$fromhoursel
									</select>
									<select name=\\"fromminute\\" $timedisabled>
										$fromminutesel
									</select>
									<select name=\\"fromampm\\" $timedisabled>
										<option value=\\"am\\" $fromampmsel[am]>AM</option>
										<option value=\\"pm\\" $fromampmsel[pm]>PM</option>
									</select>
								</td>
							</tr>
							<tr style=\\"height: 50%;\\">
								<td nowrap=\\"nowrap\\">
									<span class=\\"normalfont\\">Duration:</span>
								</td>
								<td nowrap=\\"nowrap\\">
									<span class=\\"normalfont\\">
									<select name=\\"durhours\\" $timedisabled>
										$durhourssel
									</select>
									<select name=\\"durminutes\\" $timedisabled>
										$durminutessel
									</select></span>
								</td>
								<td nowrap=\\"nowrap\\">
									<span class=\\"normalfont\\">
									<input type=\\"checkbox\\" name=\\"allday\\" $alldaychecked id=\\"allday\\" value=\\"1\\" onClick=\\"this.form.fromhour.disabled = this.form.fromminute.disabled = this.form.fromampm.disabled = this.form.durhours.disabled = this.form.durminutes.disabled = this.checked;\\"/> <label for=\\"allday\\">All day event</label>
									</span>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align=\\"center\\" nowrap=\\"nowrap\\" colspan=\\"3\\"><span class=\\"normalfont\\">
						<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Create New Event\\" />
						<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Use Advanced Form\\" onClick=\\"this.form.fromhour.disabled = this.form.fromminute.disabled = this.form.fromampm.disabled = this.form.durhours.disabled = this.form.durminutes.disabled = false; this.form.cmd.value = \'reload\';\\" />
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>

<script language=\\"JavaScript\\">
<!--
getDay(document.forms.eventform, \'from\');
// -->
</script>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'calendar_options' => 
  array (
    'templategroupid' => '18',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Calendar Options</title>
$css
</head>
<body>
$header

<form action="calendar.options.php" method="post">
<input type="hidden" name="cmd" value="update" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Calendar Options</b></span></th>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Show current month on {$_folders[\'-1\'][\'title\']}:</b></span>
	<br />
	<span class="smallfont">Turn this on if you\'d like to see the current month displayed when viewing your {$_folders[\'-1\'][\'title\']}.</span></td>
	<td class="highRightCell" width="40%"><span class="normalfont"><input type="radio" name="caloninbox" value="1" id="caloninboxon" $caloninboxon /> <label for="caloninboxon">Yes</label><br /><input type="radio" name="caloninbox" value="0" id="caloninboxoff" $caloninboxoff /> <label for="caloninboxoff">No</label></span></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Type of layout for yearly display:</b></span>
	<br />
	<span class="smallfont">How months should be laid out when viewing a whole year.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="calyear3on4" value="1" id="calyear3on4on" $calyear3on4on /> <label for="calyear3on4on">3 wide / 4 high</label><br /><input type="radio" name="calyear3on4" value="0" id="calyear3on4off" $calyear3on4off /> <label for="calyear3on4off">4 wide / 3 high</label></span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Start of the week:</b></span>
	<br />
	<span class="smallfont">Choose the day of the week on which weeks start in your culture, to make your calendar appear correct.</span></td>
	<td class="highRightCell" width="40%">
		<select name="weekstart">
			<option value="0" $daysel[0]>$skin[cal_sun_long]</option>
			<option value="1" $daysel[1]>$skin[cal_mon_long]</option>
			<option value="2" $daysel[2]>$skin[cal_tue_long]</option>
			<option value="3" $daysel[3]>$skin[cal_wed_long]</option>
			<option value="4" $daysel[4]>$skin[cal_thu_long]</option>
			<option value="5" $daysel[5]>$skin[cal_fri_long]</option>
			<option value="6" $daysel[6]>$skin[cal_sat_long]</option>
		</select>
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
<head><title>$appname: Calendar Options</title>
$GLOBALS[css]
</head>
<body>
$GLOBALS[header]

<form action=\\"calendar.options.php{$GLOBALS[session_url]}\\" method=\\"post\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Calendar Options</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Show current month on {$_folders[\'-1\'][\'title\']}:</b></span>
	<br />
	<span class=\\"smallfont\\">Turn this on if you\'d like to see the current month displayed when viewing your {$_folders[\'-1\'][\'title\']}.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"caloninbox\\" value=\\"1\\" id=\\"caloninboxon\\" $caloninboxon /> <label for=\\"caloninboxon\\">Yes</label><br /><input type=\\"radio\\" name=\\"caloninbox\\" value=\\"0\\" id=\\"caloninboxoff\\" $caloninboxoff /> <label for=\\"caloninboxoff\\">No</label></span></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Type of layout for yearly display:</b></span>
	<br />
	<span class=\\"smallfont\\">How months should be laid out when viewing a whole year.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"calyear3on4\\" value=\\"1\\" id=\\"calyear3on4on\\" $calyear3on4on /> <label for=\\"calyear3on4on\\">3 wide / 4 high</label><br /><input type=\\"radio\\" name=\\"calyear3on4\\" value=\\"0\\" id=\\"calyear3on4off\\" $calyear3on4off /> <label for=\\"calyear3on4off\\">4 wide / 3 high</label></span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Start of the week:</b></span>
	<br />
	<span class=\\"smallfont\\">Choose the day of the week on which weeks start in your culture, to make your calendar appear correct.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\">
		<select name=\\"weekstart\\">
			<option value=\\"0\\" $daysel[0]>{$GLOBALS[skin][cal_sun_long]}</option>
			<option value=\\"1\\" $daysel[1]>{$GLOBALS[skin][cal_mon_long]}</option>
			<option value=\\"2\\" $daysel[2]>{$GLOBALS[skin][cal_tue_long]}</option>
			<option value=\\"3\\" $daysel[3]>{$GLOBALS[skin][cal_wed_long]}</option>
			<option value=\\"4\\" $daysel[4]>{$GLOBALS[skin][cal_thu_long]}</option>
			<option value=\\"5\\" $daysel[5]>{$GLOBALS[skin][cal_fri_long]}</option>
			<option value=\\"6\\" $daysel[6]>{$GLOBALS[skin][cal_sat_long]}</option>
		</select>
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
  'calendar_table' => 
  array (
    'templategroupid' => '18',
    'user_data' => '<tr class="headerRow">
	<th class="headerBothCell" colspan="8" style="border-bottom-width: 0px;"><span class="normalfonttablehead"><b>
		<%if $sidelinks %><a href="{$link}month=$prevmonth&year=$prevyear"><span class="normalfonttablehead">&laquo;</span></a><%endif%>
		<a href="{$link}month=$month&year=$year""><span class="normalfonttablehead">$monthname $year</span></a>
		<%if $sidelinks %><a href="{$link}month=$nextmonth&year=$nextyear"><span class="normalfonttablehead">&raquo;</span></a><%endif%>
	</b></span></th>
</tr>
<tr class="headerRow">
	<%if getop(\'cal_showweek\') %>
	<th class="headerLeftCell"  nowrap="nowrap" width="1%"><span class="{$fontsize}fonttablehead"><%if $bigview %>&nbsp;Week&nbsp;<%else%>&nbsp;<%endif%></span></th>
	<th class="headerCell"      nowrap="nowrap"><span class="{$fontsize}fonttablehead"><b>$day1</b></span></th>
	<%else%>
	<th class="headerLeftCell"  nowrap="nowrap"><span class="{$fontsize}fonttablehead"><b>$day1</b></span></th>
	<%endif%>
	<th class="headerCell"      nowrap="nowrap"><span class="{$fontsize}fonttablehead"><b>$day2</b></span></th>
	<th class="headerCell"      nowrap="nowrap"><span class="{$fontsize}fonttablehead"><b>$day3</b></span></th>
	<th class="headerCell"      nowrap="nowrap"><span class="{$fontsize}fonttablehead"><b>$day4</b></span></th>
	<th class="headerCell"      nowrap="nowrap"><span class="{$fontsize}fonttablehead"><b>$day5</b></span></th>
	<th class="headerCell"      nowrap="nowrap"><span class="{$fontsize}fonttablehead"><b>$day6</b></span></th>
	<th class="headerRightCell" nowrap="nowrap"><span class="{$fontsize}fonttablehead"><b>$day7</b></span></th>
</tr>
<tr>
	$calendarbits
</tr>',
    'parsed_data' => '"<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"8\\" style=\\"border-bottom-width: 0px;\\"><span class=\\"normalfonttablehead\\"><b>
		".(($sidelinks ) ? ("<a href=\\"{$link}month=$prevmonth&year=$prevyear\\"><span class=\\"normalfonttablehead\\">&laquo;</span></a>") : (\'\'))."
		<a href=\\"{$link}month=$month&year=$year\\"\\"><span class=\\"normalfonttablehead\\">$monthname $year</span></a>
		".(($sidelinks ) ? ("<a href=\\"{$link}month=$nextmonth&year=$nextyear\\"><span class=\\"normalfonttablehead\\">&raquo;</span></a>") : (\'\'))."
	</b></span></th>
</tr>
<tr class=\\"headerRow\\">
	".((getop(\'cal_showweek\') ) ? ("
	<th class=\\"headerLeftCell\\"  nowrap=\\"nowrap\\" width=\\"1%\\"><span class=\\"{$fontsize}fonttablehead\\">".(($bigview ) ? ("&nbsp;Week&nbsp;") : ("&nbsp;"))."</span></th>
	<th class=\\"headerCell\\"      nowrap=\\"nowrap\\"><span class=\\"{$fontsize}fonttablehead\\"><b>$day1</b></span></th>
	") : ("
	<th class=\\"headerLeftCell\\"  nowrap=\\"nowrap\\"><span class=\\"{$fontsize}fonttablehead\\"><b>$day1</b></span></th>
	"))."
	<th class=\\"headerCell\\"      nowrap=\\"nowrap\\"><span class=\\"{$fontsize}fonttablehead\\"><b>$day2</b></span></th>
	<th class=\\"headerCell\\"      nowrap=\\"nowrap\\"><span class=\\"{$fontsize}fonttablehead\\"><b>$day3</b></span></th>
	<th class=\\"headerCell\\"      nowrap=\\"nowrap\\"><span class=\\"{$fontsize}fonttablehead\\"><b>$day4</b></span></th>
	<th class=\\"headerCell\\"      nowrap=\\"nowrap\\"><span class=\\"{$fontsize}fonttablehead\\"><b>$day5</b></span></th>
	<th class=\\"headerCell\\"      nowrap=\\"nowrap\\"><span class=\\"{$fontsize}fonttablehead\\"><b>$day6</b></span></th>
	<th class=\\"headerRightCell\\" nowrap=\\"nowrap\\"><span class=\\"{$fontsize}fonttablehead\\"><b>$day7</b></span></th>
</tr>
<tr>
	$calendarbits
</tr>"',
  ),
  'calendar_table_daycell_big' => 
  array (
    'templategroupid' => '18',
    'user_data' => '<td align="left" onDblClick="document.location.href = \'calendar.display.php?cmd=day&date=$month-$day-$year\';" valign="top" style="padding: 10px; height: 65px; width: 100px;" class="<%if $thisweek %>high<%else%>normal<%endif%>$classType" <%if !$thisweek %>onMouseOver="this.className = \'high$classType\';" onMouseOut="this.className = \'normal$classType\';"<%endif%>><a href="calendar.display.php?cmd=day&date=$month-$day-$year" style="text-decoration: none;"><span class="normalfont" style="$style">$day</span></a>
<%if !empty($events) %>$events<%endif%></td>',
    'parsed_data' => '"<td align=\\"left\\" onDblClick=\\"document.location.href = \'calendar.display.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=day&date=$month-$day-$year\';\\" valign=\\"top\\" style=\\"padding: 10px; height: 65px; width: 100px;\\" class=\\"".(($thisweek ) ? ("high") : ("normal"))."$classType\\" ".((!$thisweek ) ? ("onMouseOver=\\"this.className = \'high$classType\';\\" onMouseOut=\\"this.className = \'normal$classType\';\\"") : (\'\'))."><a href=\\"calendar.display.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=day&date=$month-$day-$year\\" style=\\"text-decoration: none;\\"><span class=\\"normalfont\\" style=\\"$style\\">$day</span></a>
".((!empty($events) ) ? ("$events") : (\'\'))."</td>"',
  ),
  'calendar_table_daycell_eventbit' => 
  array (
    'templategroupid' => '18',
    'user_data' => '<li><span class="smallfont"><a title="$event[title]" href="calendar.event.php?eventid=$event[eventid]">$event[shorttitle]</a></li>',
    'parsed_data' => '"<li><span class=\\"smallfont\\"><a title=\\"$event[title]\\" href=\\"calendar.event.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}eventid=$event[eventid]\\">$event[shorttitle]</a></li>"',
  ),
  'calendar_table_daycell_small' => 
  array (
    'templategroupid' => '18',
    'user_data' => '<td align="center" onDblClick="document.location.href = \'calendar.display.php?cmd=day&date=$month-$day-$year\';" title="There are $eventstoday event(s) on this day" class="<%if $thisweek %>high<%else%>normal<%endif%>$classType" <%if !$thisweek %>onMouseOver="this.className = \'high$classType\';" onMouseOut="this.className = \'normal$classType\';"<%endif%>><a href="calendar.display.php?cmd=day&date=$month-$day-$year" style="text-decoration: none;"><span class="{$fontsize}font" style="$style">$day</span></a></td>',
    'parsed_data' => '"<td align=\\"center\\" onDblClick=\\"document.location.href = \'calendar.display.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=day&date=$month-$day-$year\';\\" title=\\"There are $eventstoday event(s) on this day\\" class=\\"".(($thisweek ) ? ("high") : ("normal"))."$classType\\" ".((!$thisweek ) ? ("onMouseOver=\\"this.className = \'high$classType\';\\" onMouseOut=\\"this.className = \'normal$classType\';\\"") : (\'\'))."><a href=\\"calendar.display.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=day&date=$month-$day-$year\\" style=\\"text-decoration: none;\\"><span class=\\"{$fontsize}font\\" style=\\"$style\\">$day</span></a></td>"',
  ),
  'calendar_table_endpad' => 
  array (
    'templategroupid' => '18',
    'user_data' => '<td <%if $bigview %>align="left" valign="top" style="padding: 10px;"<%else%>align="center"<%endif%> class="normal<%if $counter == (8 - $off) %>Right<%endif%>Cell"><span class="{$fontsize}font" style="color: #E1E1E1;">$counter</span></td>',
    'parsed_data' => '"<td ".(($bigview ) ? ("align=\\"left\\" valign=\\"top\\" style=\\"padding: 10px;\\"") : ("align=\\"center\\""))." class=\\"normal".(($counter == (8 - $off) ) ? ("Right") : (\'\'))."Cell\\"><span class=\\"{$fontsize}font\\" style=\\"color: #E1E1E1;\\">$counter</span></td>"',
  ),
  'calendar_table_startpad' => 
  array (
    'templategroupid' => '18',
    'user_data' => '<td <%if $bigview %>align="left" valign="top" style="padding: 10px;"<%else%>align="center"<%endif%> class="normal<%if $counter == 0 %>Left<%endif%>Cell"><span class="{$fontsize}font" style="color: #E1E1E1;">$prevday</span></td>',
    'parsed_data' => '"<td ".(($bigview ) ? ("align=\\"left\\" valign=\\"top\\" style=\\"padding: 10px;\\"") : ("align=\\"center\\""))." class=\\"normal".(($counter == 0 ) ? ("Left") : (\'\'))."Cell\\"><span class=\\"{$fontsize}font\\" style=\\"color: #E1E1E1;\\">$prevday</span></td>"',
  ),
  'calendar_table_weeknumber' => 
  array (
    'templategroupid' => '18',
    'user_data' => '<td class="highLeftCell" align="center"><span class="{$fontsize}font" style="color: #E1E1E1;">$weeknum</span></td>',
    'parsed_data' => '"<td class=\\"highLeftCell\\" align=\\"center\\"><span class=\\"{$fontsize}font\\" style=\\"color: #E1E1E1;\\">$weeknum</span></td>"',
  ),
  'calendar_yearly' => 
  array (
    'templategroupid' => '18',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Calendar - Yearly View</title>
$css
<script language="JavaScript">
<!--

function getDay(tform, which) {
	var date = new Date(eval(\'tform.\'+which+\'year\').value, eval(\'tform.\'+which+\'month\').value - 1, eval(\'tform.\'+which+\'day\').value);
	eval(\'tform.\'+which+\'dayname\').value = DayNames[date.getDay()];
}

// Names of days that will be shown next to the date
var DayNames = new Array(\'($skin[cal_sun_long])\', \'($skin[cal_mon_long])\', \'($skin[cal_tue_long])\', \'($skin[cal_wed_long])\', \'($skin[cal_thu_long])\', \'($skin[cal_fri_long])\', \'($skin[cal_sat_long])\');

// -->
</script>
</head>
<body>
$header

<table width="100%" cellpadding="8">
<tr>
	<td colspan="4" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			<form action="calendar.display.php" method="get">
			<input type="hidden" name="cmd" value="month" />
			<tr class="headerRow">
				<th class="headerLeftCell" width="30%">&nbsp;</th>
				<th class="headerCell" width="40%"><span class="normalfonttablehead">
				<a href="calendar.display.php?cmd=year&year=$prevyear"><span class="normalfonttablehead">&laquo;</span></a>
				$year
				<a href="calendar.display.php?cmd=year&year=$nextyear"><span class="normalfonttablehead">&raquo;</span></a>
				</span></th>
				<th class="headerRightCell" width="30%" align="right"><span class="normalfonttablehead">
				<select name="month" onChange="this.form.go.disabled = (this.selectedIndex == 1);">
					<option value="0">Whole year</option>
					<option value="-1">-------------</option>
					$monthsel
				</select>
				<select name="year">
					$yearsel
				</select>
				<input type="submit" id="go" class="bginput" value="Go" />&nbsp;</span></th>
			</tr>
			</form>
		</table>
	</td>
</tr>
<%if !$hiveuser[calyear3on4] %>
<tr>
	<td width="25%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month1
		</table>
	</td>
	<td width="25%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month2
		</table>
	</td>
	<td width="25%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month3
		</table>
	</td>
	<td width="25%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month4
		</table>
	</td>
</tr>
<tr>
	<td width="25%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month5
		</table>
	</td>
	<td width="25%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month6
		</table>
	</td>
	<td width="25%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month7
		</table>
	</td>
	<td width="25%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month8
		</table>
	</td>
</tr>
<tr>
	<td width="25%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month9
		</table>
	</td>
	<td width="25%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month10
		</table>
	</td>
	<td width="25%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month11
		</table>
	</td>
	<td width="25%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month12
		</table>
	</td>
</tr>
<%else%>
<tr>
	<td width="33%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month1
		</table>
	</td>
	<td width="33%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month2
		</table>
	</td>
	<td width="33%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month3
		</table>
	</td>
</tr>
<tr>
	<td width="33%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month4
		</table>
	</td>
	<td width="33%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month5
		</table>
	</td>
	<td width="33%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month6
		</table>
	</td>
</tr>
<tr>
	<td width="33%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month7
		</table>
	</td>
	<td width="33%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month8
		</table>
	</td>
	<td width="33%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month9
		</table>
	</td>
</tr>
<tr>
	<td width="33%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month10
		</table>
	</td>
	<td width="33%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month11
		</table>
	</td>
	<td width="33%" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			$month12
		</table>
	</td>
</tr>
<%endif%>
<tr>
	<td colspan="4" valign="top">
		<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			<form action="calendar.event.php" method="post" name="eventform">
			<input type="hidden" name="cmd" value="update" />
			<input type="hidden" name="message" value="" />
			<input type="hidden" name="addresses" value="" />
			<input type="hidden" name="recurtype" value="0" />
			<input type="hidden" name="recurend" value="0" />
			<tr class="headerRow">
				<th class="headerBothCell" colspan="3"><span class="normalfonttablehead">Add New Event</span></th>
			</tr>
			<tr class="normalRow">
				<td class="normalLeftCell" nowrap="nowrap" valign="top">
					<table style="height: 40px;">
						<tr style="height: 50%;">
							<td nowrap="nowrap">
								<span class="normalfont">Event title:</span>
							</td>
						</tr>
						<tr style="height: 50%;">
							<td nowrap="nowrap">
								<input type="text" class="bginput" name="title" size="25" />
							</td>
						</tr>
					</table>
				</td>
				<td class="normalCell" nowrap="nowrap" valign="top">
					<table style="height: 40px;">
						<tr style="height: 50%;">
							<td nowrap="nowrap">
								<span class="normalfont">Event date:</span>
								&nbsp;<input type="text" name="fromdayname" class="normalInactive" readonly="readonly" value="" size="12" />
							</td>
						</tr>
						<tr style="height: 50%;">
							<td nowrap="nowrap">
								<select name="frommonth" onChange="getDay(this.form, \'from\');">
									$frommonthsel
								</select>
								<select name="fromday" onChange="getDay(this.form, \'from\');">
									$fromdaysel
								</select>
								<select name="fromyear" onChange="getDay(this.form, \'from\');">
									$fromyearsel
								</select>
							</td>
						</tr>
					</table>
				</td>
				<td class="normalRightCell" valign="top">
					<table style="height: 40px;">
						<tr style="height: 50%;">
							<td nowrap="nowrap">
								<span class="normalfont">Starts at:</span>
							</td>
							<td nowrap="nowrap">
								<select name="fromhour" $timedisabled>
									$fromhoursel
								</select>
								<select name="fromminute" $timedisabled>
									$fromminutesel
								</select>
								<select name="fromampm" $timedisabled>
									<option value="am" $fromampmsel[am]>AM</option>
									<option value="pm" $fromampmsel[pm]>PM</option>
								</select>
							</td>
						</tr>
						<tr style="height: 50%;">
							<td nowrap="nowrap">
								<span class="normalfont">Duration:</span>
							</td>
							<td nowrap="nowrap">
								<span class="normalfont">
								<select name="durhours" $timedisabled>
									$durhourssel
								</select>
								<select name="durminutes" $timedisabled>
									$durminutessel
								</select></span>
							</td>
							<td nowrap="nowrap">
								<span class="normalfont">
								<input type="checkbox" name="allday" $alldaychecked id="allday" value="1" onClick="this.form.fromhour.disabled = this.form.fromminute.disabled = this.form.fromampm.disabled = this.form.durhours.disabled = this.form.durminutes.disabled = this.checked;"/> <label for="allday">All day event</label>
								</span>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="center" nowrap="nowrap" colspan="3"><span class="normalfont">
					<input type="submit" class="bginput" name="submit" value="Create New Event" />
					<input type="submit" class="bginput" name="submit" value="Use Advanced Form" onClick="this.form.fromhour.disabled = this.form.fromminute.disabled = this.form.fromampm.disabled = this.form.durhours.disabled = this.form.durminutes.disabled = false; this.form.cmd.value = \'reload\';" />
				</td>
			</tr>
			</form>
		</table>
	</td>
</tr>
</table>

<script language="JavaScript">
<!--
getDay(document.forms.eventform, \'from\');
// -->
</script>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Calendar - Yearly View</title>
$GLOBALS[css]
<script language=\\"JavaScript\\">
<!--

function getDay(tform, which) {
	var date = new Date(eval(\'tform.\'+which+\'year\').value, eval(\'tform.\'+which+\'month\').value - 1, eval(\'tform.\'+which+\'day\').value);
	eval(\'tform.\'+which+\'dayname\').value = DayNames[date.getDay()];
}

// Names of days that will be shown next to the date
var DayNames = new Array(\'({$GLOBALS[skin][cal_sun_long]})\', \'({$GLOBALS[skin][cal_mon_long]})\', \'({$GLOBALS[skin][cal_tue_long]})\', \'({$GLOBALS[skin][cal_wed_long]})\', \'({$GLOBALS[skin][cal_thu_long]})\', \'({$GLOBALS[skin][cal_fri_long]})\', \'({$GLOBALS[skin][cal_sat_long]})\');

// -->
</script>
</head>
<body>
$GLOBALS[header]

<table width=\\"100%\\" cellpadding=\\"8\\">
<tr>
	<td colspan=\\"4\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			<form action=\\"calendar.display.php{$GLOBALS[session_url]}\\" method=\\"get\\">
			<input type=\\"hidden\\" name=\\"cmd\\" value=\\"month\\" />
			<tr class=\\"headerRow\\">
				<th class=\\"headerLeftCell\\" width=\\"30%\\">&nbsp;</th>
				<th class=\\"headerCell\\" width=\\"40%\\"><span class=\\"normalfonttablehead\\">
				<a href=\\"calendar.display.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=year&year=$prevyear\\"><span class=\\"normalfonttablehead\\">&laquo;</span></a>
				$year
				<a href=\\"calendar.display.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=year&year=$nextyear\\"><span class=\\"normalfonttablehead\\">&raquo;</span></a>
				</span></th>
				<th class=\\"headerRightCell\\" width=\\"30%\\" align=\\"right\\"><span class=\\"normalfonttablehead\\">
				<select name=\\"month\\" onChange=\\"this.form.go.disabled = (this.selectedIndex == 1);\\">
					<option value=\\"0\\">Whole year</option>
					<option value=\\"-1\\">-------------</option>
					$monthsel
				</select>
				<select name=\\"year\\">
					$yearsel
				</select>
				<input type=\\"submit\\" id=\\"go\\" class=\\"bginput\\" value=\\"Go\\" />&nbsp;</span></th>
			</tr>
			</form>
		</table>
	</td>
</tr>
".((!$hiveuser[calyear3on4] ) ? ("
<tr>
	<td width=\\"25%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month1
		</table>
	</td>
	<td width=\\"25%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month2
		</table>
	</td>
	<td width=\\"25%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month3
		</table>
	</td>
	<td width=\\"25%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month4
		</table>
	</td>
</tr>
<tr>
	<td width=\\"25%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month5
		</table>
	</td>
	<td width=\\"25%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month6
		</table>
	</td>
	<td width=\\"25%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month7
		</table>
	</td>
	<td width=\\"25%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month8
		</table>
	</td>
</tr>
<tr>
	<td width=\\"25%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month9
		</table>
	</td>
	<td width=\\"25%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month10
		</table>
	</td>
	<td width=\\"25%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month11
		</table>
	</td>
	<td width=\\"25%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month12
		</table>
	</td>
</tr>
") : ("
<tr>
	<td width=\\"33%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month1
		</table>
	</td>
	<td width=\\"33%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month2
		</table>
	</td>
	<td width=\\"33%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month3
		</table>
	</td>
</tr>
<tr>
	<td width=\\"33%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month4
		</table>
	</td>
	<td width=\\"33%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month5
		</table>
	</td>
	<td width=\\"33%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month6
		</table>
	</td>
</tr>
<tr>
	<td width=\\"33%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month7
		</table>
	</td>
	<td width=\\"33%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month8
		</table>
	</td>
	<td width=\\"33%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month9
		</table>
	</td>
</tr>
<tr>
	<td width=\\"33%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month10
		</table>
	</td>
	<td width=\\"33%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month11
		</table>
	</td>
	<td width=\\"33%\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			$month12
		</table>
	</td>
</tr>
"))."
<tr>
	<td colspan=\\"4\\" valign=\\"top\\">
		<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			<form action=\\"calendar.event.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"eventform\\">
			<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />
			<input type=\\"hidden\\" name=\\"message\\" value=\\"\\" />
			<input type=\\"hidden\\" name=\\"addresses\\" value=\\"\\" />
			<input type=\\"hidden\\" name=\\"recurtype\\" value=\\"0\\" />
			<input type=\\"hidden\\" name=\\"recurend\\" value=\\"0\\" />
			<tr class=\\"headerRow\\">
				<th class=\\"headerBothCell\\" colspan=\\"3\\"><span class=\\"normalfonttablehead\\">Add New Event</span></th>
			</tr>
			<tr class=\\"normalRow\\">
				<td class=\\"normalLeftCell\\" nowrap=\\"nowrap\\" valign=\\"top\\">
					<table style=\\"height: 40px;\\">
						<tr style=\\"height: 50%;\\">
							<td nowrap=\\"nowrap\\">
								<span class=\\"normalfont\\">Event title:</span>
							</td>
						</tr>
						<tr style=\\"height: 50%;\\">
							<td nowrap=\\"nowrap\\">
								<input type=\\"text\\" class=\\"bginput\\" name=\\"title\\" size=\\"25\\" />
							</td>
						</tr>
					</table>
				</td>
				<td class=\\"normalCell\\" nowrap=\\"nowrap\\" valign=\\"top\\">
					<table style=\\"height: 40px;\\">
						<tr style=\\"height: 50%;\\">
							<td nowrap=\\"nowrap\\">
								<span class=\\"normalfont\\">Event date:</span>
								&nbsp;<input type=\\"text\\" name=\\"fromdayname\\" class=\\"normalInactive\\" readonly=\\"readonly\\" value=\\"\\" size=\\"12\\" />
							</td>
						</tr>
						<tr style=\\"height: 50%;\\">
							<td nowrap=\\"nowrap\\">
								<select name=\\"frommonth\\" onChange=\\"getDay(this.form, \'from\');\\">
									$frommonthsel
								</select>
								<select name=\\"fromday\\" onChange=\\"getDay(this.form, \'from\');\\">
									$fromdaysel
								</select>
								<select name=\\"fromyear\\" onChange=\\"getDay(this.form, \'from\');\\">
									$fromyearsel
								</select>
							</td>
						</tr>
					</table>
				</td>
				<td class=\\"normalRightCell\\" valign=\\"top\\">
					<table style=\\"height: 40px;\\">
						<tr style=\\"height: 50%;\\">
							<td nowrap=\\"nowrap\\">
								<span class=\\"normalfont\\">Starts at:</span>
							</td>
							<td nowrap=\\"nowrap\\">
								<select name=\\"fromhour\\" $timedisabled>
									$fromhoursel
								</select>
								<select name=\\"fromminute\\" $timedisabled>
									$fromminutesel
								</select>
								<select name=\\"fromampm\\" $timedisabled>
									<option value=\\"am\\" $fromampmsel[am]>AM</option>
									<option value=\\"pm\\" $fromampmsel[pm]>PM</option>
								</select>
							</td>
						</tr>
						<tr style=\\"height: 50%;\\">
							<td nowrap=\\"nowrap\\">
								<span class=\\"normalfont\\">Duration:</span>
							</td>
							<td nowrap=\\"nowrap\\">
								<span class=\\"normalfont\\">
								<select name=\\"durhours\\" $timedisabled>
									$durhourssel
								</select>
								<select name=\\"durminutes\\" $timedisabled>
									$durminutessel
								</select></span>
							</td>
							<td nowrap=\\"nowrap\\">
								<span class=\\"normalfont\\">
								<input type=\\"checkbox\\" name=\\"allday\\" $alldaychecked id=\\"allday\\" value=\\"1\\" onClick=\\"this.form.fromhour.disabled = this.form.fromminute.disabled = this.form.fromampm.disabled = this.form.durhours.disabled = this.form.durminutes.disabled = this.checked;\\"/> <label for=\\"allday\\">All day event</label>
								</span>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align=\\"center\\" nowrap=\\"nowrap\\" colspan=\\"3\\"><span class=\\"normalfont\\">
					<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Create New Event\\" />
					<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Use Advanced Form\\" onClick=\\"this.form.fromhour.disabled = this.form.fromminute.disabled = this.form.fromampm.disabled = this.form.durhours.disabled = this.form.durminutes.disabled = false; this.form.cmd.value = \'reload\';\\" />
				</td>
			</tr>
			</form>
		</table>
	</td>
</tr>
</table>

<script language=\\"JavaScript\\">
<!--
getDay(document.forms.eventform, \'from\');
// -->
</script>

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
<%if $data[html] %><?import namespace="ACE" implementation="misc/ace.htc" /><%endif%>
<!-- ?> -->
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
	<td class="normalRightCell" style="width: 100%;">
		<select name="data[popid]" style="width: 445px;">
			<option value="0" <%if $data[popid] == 0 %>selected="selected"<%endif%>>$hiveuser[realname] &lt;$hiveuser[username]$domainname&gt;</option>
			$popoptions
		</select>
	</td>
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
	<br /><input type="button" class="bginput" name="manageattach" value="Manage Attachments" onClick="var attWnd = window.open(\'compose.attachments.php?draftid=$draftid\',\'manageattach\',\'statusbar=no,menubar=no,toolbar=no,scrollbars=yes,width=480,height=425\'); return false;" />
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
".(($data[html] ) ? ("<?import namespace=\\"ACE\\" implementation=\\"misc/ace.htc\\" />") : (\'\'))."
<!-- ?> -->
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
	<td class=\\"normalRightCell\\" style=\\"width: 100%;\\">
		<select name=\\"data[popid]\\" style=\\"width: 445px;\\">
			<option value=\\"0\\" ".(($data[popid] == 0 ) ? ("selected=\\"selected\\"") : (\'\')).">$hiveuser[realname] &lt;$hiveuser[username]$domainname&gt;</option>
			$popoptions
		</select>
	</td>
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
	<br /><input type=\\"button\\" class=\\"bginput\\" name=\\"manageattach\\" value=\\"Manage Attachments\\" onClick=\\"var attWnd = window.open(\'compose.attachments.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}draftid=$draftid\',\'manageattach\',\'statusbar=no,menubar=no,toolbar=no,scrollbars=yes,width=480,height=425\'); return false;\\" />
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
	border-width: 1px;
}
.normalInactive {
	font-family: $skin[fontface];
	font-size: 12px;
	color: #000000;
	border: 1px $skin[firstalt] solid;
	font-weight: normal;
	background: $skin[firstalt];
}
.highInactive {
	font-family: $skin[fontface];
	font-size: 12px;
	color: #000000;
	border: 1px $skin[secondalt] solid;
	font-weight: normal;
	background: $skin[secondalt];
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

var INDEX_FILE = \'{<INDEX_FILE>}\';

event_addListener( window, \'load\', function() { preloadImages(\'$skin[images]/header_icon_inbox_high.gif\', \'$skin[images]/header_icon_compose_high.gif\', \'$skin[images]/header_icon_addbook_high.gif\', \'$skin[images]/header_icon_options_high.gif\', \'$skin[images]/header_icon_search_high.gif\'); });

<%if $hiveuser[\'userid\'] <> 0 and $hiveuser[\'fixdst\'] %>
function checkDST() {
	var curDate = new Date();
	var difference = parseInt(-curDate.getTimezoneOffset() / 60 - $hiveuser[timezone]);

	while (difference > 12) {
		difference -= 12;
	}
	while (difference < -12) {
		difference += 12;
	}

	if (difference != 0) {
		if (confirm(\'The system has detected that the time zone in your preferences is wrong and off by \'+Math.abs(difference)+\' hour\'+((Math.abs(difference) == 1) ? (\'\') : (\'s\'))+\'. Would you like the system to correct this mistake?\')) {
			imgevent("options.personal.php?cmd=updatezone&difference="+difference);
		} else if (confirm(\'Do you wish to disable Time Zone Auto-detection?\')) {
			imgevent("options.personal.php?cmd=disablezone");
		}
	}
}
setTimeout(checkDST, 1000);
<%endif%>

function contextForFolder(e, folderID, folderName) {
	var popupoptions = [
		new ContextItem(\'Open\', function(){ window.location = \'index.php?folderid=\'+folderID; }, false, true),
		new ContextItem(\'Search\', function(){ window.location = \'search.intro.php?folderid=\'+folderID; }),
		new ContextSeperator(),
		new ContextItem(\'Rename...\', function(){ renameFolder(folderID, folderName); }, folderID < 0),
		new ContextSeperator(),
		new ContextItem(\'Empty\', function(){ if (confirm(\'Are you sure you want to empty this folder?\')) window.location = \'folders.update.php?empty=Empty&return=$folderid&folder[\'+folderID+\']=yes\'; }),
		new ContextItem(\'Delete\', function(){ if (confirm(\'Are you sure you want to delete this folder?\')) window.location = \'folders.update.php?delete=Delete&return=$folderid&folder[\'+folderID+\']=yes\'; }, folderID < 0),
		new ContextSeperator(),
		new ContextItem(\'Mark as read\', function(){ markFolder(folderID, \'read\'); }),
		new ContextItem(\'Mark as not read\', function(){ markFolder(folderID, \'unread\'); })
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

function markFolder(folderid, markAs) {
	if (confirm(\'Are you sure you want to mark all messages in this folder as \'+markAs+\'?\')) {
		window.location = \'folders.update.php?cmd=mark&markas=\'+markAs+\'&folderid=\'+folderid;
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
	border-width: 1px;
}
.normalInactive {
	font-family: {$GLOBALS[skin][fontface]};
	font-size: 12px;
	color: #000000;
	border: 1px {$GLOBALS[skin][firstalt]} solid;
	font-weight: normal;
	background: {$GLOBALS[skin][firstalt]};
}
.highInactive {
	font-family: {$GLOBALS[skin][fontface]};
	font-size: 12px;
	color: #000000;
	border: 1px {$GLOBALS[skin][secondalt]} solid;
	font-weight: normal;
	background: {$GLOBALS[skin][secondalt]};
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

var INDEX_FILE = \'".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}\';

event_addListener( window, \'load\', function() { preloadImages(\'{$GLOBALS[skin][images]}/header_icon_inbox_high.gif\', \'{$GLOBALS[skin][images]}/header_icon_compose_high.gif\', \'{$GLOBALS[skin][images]}/header_icon_addbook_high.gif\', \'{$GLOBALS[skin][images]}/header_icon_options_high.gif\', \'{$GLOBALS[skin][images]}/header_icon_search_high.gif\'); });

".(($hiveuser[\'userid\'] <> 0 and $hiveuser[\'fixdst\'] ) ? ("
function checkDST() {
	var curDate = new Date();
	var difference = parseInt(-curDate.getTimezoneOffset() / 60 - $hiveuser[timezone]);

	while (difference > 12) {
		difference -= 12;
	}
	while (difference < -12) {
		difference += 12;
	}

	if (difference != 0) {
		if (confirm(\'The system has detected that the time zone in your preferences is wrong and off by \'+Math.abs(difference)+\' hour\'+((Math.abs(difference) == 1) ? (\'\') : (\'s\'))+\'. Would you like the system to correct this mistake?\')) {
			imgevent(\\"options.personal.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=updatezone&difference=\\"+difference);
		} else if (confirm(\'Do you wish to disable Time Zone Auto-detection?\')) {
			imgevent(\\"options.personal.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=disablezone\\");
		}
	}
}
setTimeout(checkDST, 1000);
") : (\'\'))."

function contextForFolder(e, folderID, folderName) {
	var popupoptions = [
		new ContextItem(\'Open\', function(){ window.location = \'index.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=\'+folderID; }, false, true),
		new ContextItem(\'Search\', function(){ window.location = \'search.intro.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=\'+folderID; }),
		new ContextSeperator(),
		new ContextItem(\'Rename...\', function(){ renameFolder(folderID, folderName); }, folderID < 0),
		new ContextSeperator(),
		new ContextItem(\'Empty\', function(){ if (confirm(\'Are you sure you want to empty this folder?\')) window.location = \'folders.update.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}empty=Empty&return=$folderid&folder[\'+folderID+\']=yes\'; }),
		new ContextItem(\'Delete\', function(){ if (confirm(\'Are you sure you want to delete this folder?\')) window.location = \'folders.update.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}delete=Delete&return=$folderid&folder[\'+folderID+\']=yes\'; }, folderID < 0),
		new ContextSeperator(),
		new ContextItem(\'Mark as read\', function(){ markFolder(folderID, \'read\'); }),
		new ContextItem(\'Mark as not read\', function(){ markFolder(folderID, \'unread\'); })
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

function markFolder(folderid, markAs) {
	if (confirm(\'Are you sure you want to mark all messages in this folder as \'+markAs+\'?\')) {
		window.location = \'folders.update.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=mark&markas=\'+markAs+\'&folderid=\'+folderid;
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
  'error_aliases_illegal' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The following aliases are either illegal or reserved:
<ul>
$badaliases
</ul>
All aliases must only contain alphanumeric characters, underscores (_) and dots (.), must start with a letter or a number and must be at a minimum length of 2 characters. Please go back and try again.',
    'parsed_data' => '"The following aliases are either illegal or reserved:
<ul>
$badaliases
</ul>
All aliases must only contain alphanumeric characters, underscores (_) and dots (.), must start with a letter or a number and must be at a minimum length of 2 characters. Please go back and try again."',
  ),
  'error_aliases_taken' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The following aliases are already taken by other users so you will not be able to use them:
<ul>
$takenaliases
</ul>
Please go back and try again.',
    'parsed_data' => '"The following aliases are already taken by other users so you will not be able to use them:
<ul>
$takenaliases
</ul>
Please go back and try again."',
  ),
  'error_aliases_toomany' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You may only have $hiveuser[maxaliases] signatures.',
    'parsed_data' => '"You may only have $hiveuser[maxaliases] signatures."',
  ),
  'error_alreadyreported' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'This message has already been reported, please don\'t send multiple reports of the same message.',
    'parsed_data' => '"This message has already been reported, please don\'t send multiple reports of the same message."',
  ),
  'error_couldntsend' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The server encountered a problem while trying to send your message and could not complete the process. $goback

<%if $data[\'popid\'] > 0 %><br /><br />If you would like to send this message using the default account, please <a href="compose.send.php?draftid=$draftid&popid=0">click here</a>.<%endif%>',
    'parsed_data' => '"The server encountered a problem while trying to send your message and could not complete the process. $goback

".(($data[\'popid\'] > 0 ) ? ("<br /><br />If you would like to send this message using the default account, please <a href=\\"compose.send.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}draftid=$draftid&popid=0\\">click here</a>.") : (\'\'))',
  ),
  'error_event_invalid_end' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The end date you have specifed is not valid. Please go back and try again.',
    'parsed_data' => '"The end date you have specifed is not valid. Please go back and try again."',
  ),
  'error_event_invalid_start' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The date you have specifed is not valid. Please go back and try again.',
    'parsed_data' => '"The date you have specifed is not valid. Please go back and try again."',
  ),
  'error_event_neveroccur' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The recurrence options you have selected are not valid - this event will never occur! Please go back and try again.',
    'parsed_data' => '"The recurrence options you have selected are not valid - this event will never occur! Please go back and try again."',
  ),
  'error_event_notitle' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Your event must have a title. Pleaes go back and try again.',
    'parsed_data' => '"Your event must have a title. Pleaes go back and try again."',
  ),
  'error_maxonline' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'There are currently too many users using the system. Please try again in a few minutes.',
    'parsed_data' => '"There are currently too many users using the system. Please try again in a few minutes."',
  ),
  'error_oversendingrate' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You may only send one message every $value $unit. Please wait approximately $lastsent $unit before sending this message.',
    'parsed_data' => '"You may only send one message every $value $unit. Please wait approximately $lastsent $unit before sending this message."',
  ),
  'error_processerror_nospace' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'This message was created automatically by mail delivery software (HiveMail).

A message that you sent could not be delivered to one of its
recipients:
    $user[alias]$domainname
The error was:
    The account has reached its storage limit.

------ This is a copy of the message, including all the headers. ------

$message',
    'parsed_data' => '"This message was created automatically by mail delivery software (HiveMail).

A message that you sent could not be delivered to one of its
recipients:
    $user[alias]$domainname
The error was:
    The account has reached its storage limit.

------ This is a copy of the message, including all the headers. ------

$message"',
  ),
  'error_processerror_unknown' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'This message was created automatically by mail delivery software (HiveMail).

A message that you sent could not be delivered to the following recipient(s):
    $bad_recips
The error was:
    Unknown mailbox.

------ This is a copy of the message, including all the headers. ------

$message',
    'parsed_data' => '"This message was created automatically by mail delivery software (HiveMail).

A message that you sent could not be delivered to the following recipient(s):
    $bad_recips
The error was:
    Unknown mailbox.

------ This is a copy of the message, including all the headers. ------

$message"',
  ),
  'error_signup_nameillegal' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The username you chose, $username was not valid. Your username may only contain alphanumeric characters, underscores (_) and dots (.), must start with a letter or a number and must be at a minimum length of 2 characters.',
    'parsed_data' => '"The username you chose, $username was not valid. Your username may only contain alphanumeric characters, underscores (_) and dots (.), must start with a letter or a number and must be at a minimum length of 2 characters."',
  ),
  'expired_account_removed_message' => 
  array (
    'templategroupid' => '17',
    'user_data' => 'Dear $user[realname],

You did not log into our system for the past $group[removetime] days, and as a result your email account, $user[username]$user[domain], has just been deleted from our system. If you would like to register this email address again, you may do so here:
$appurl/{<INDEX_FILE>}

We apologize for the inconvenience.

Best regards,
$appname team',
    'parsed_data' => '"Dear $user[realname],

You did not log into our system for the past $group[removetime] days, and as a result your email account, $user[username]$user[domain], has just been deleted from our system. If you would like to register this email address again, you may do so here:
$appurl/".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}

We apologize for the inconvenience.

Best regards,
$appname team"',
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
	<th class="headerLeftCell" width="1%">&nbsp;</th>
	<th class="headerCell" nowrap="nowrap" colspan="2"><span class="normalfonttablehead"><b>Folder Name</b></span></th>
	<th class="headerCell" nowrap="nowrap"><span class="normalfonttablehead"><b>Messages</b></span></th>
	<th class="headerCell" nowrap="nowrap"><span class="normalfonttablehead"><b>Unread</b></span></th>
	<th class="headerCell" nowrap="nowrap"><span class="normalfonttablehead"><b>Size</b></span></th>
	<th class="headerRightCell"><input name="allbox" type="checkbox" value="Check All" title="Select/Deselect All" onClick="checkAll(this.form);" /></th>
</tr>
<tr align="center" class="highRow">
	<td class="highLeftCell" width="1%">&nbsp;</td>
	<td class="highCell" align="left" width="50%" colspan="2"><span class="normalfont"><a href=INDEX_FILE."?folderid=-1">Inbox</a></span> <span class="smallfont">(not removable)</span></td>
	<td class="highCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$msgcount[inbox] message<%if $msgcount[\'inbox\'] != 1 %>s<%endif%></span></td>
	<td class="highCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$unreadcount[inbox] message<%if $unreadcount[\'inbox\'] != 1 %>s<%endif%></span></td>
	<td class="highCell" width="10%" align="center" nowrap="nowrap"><span class="normalfont">$presizes[inbox]KB</span></td>
	<td class="highRightCell"><input type="checkbox" name="folder[-1]" value="yes" onClick="checkMain(this.form);" /></td>
</tr>
<tr align="center" class="normalRow">
	<td class="normalLeftCell" width="1%">&nbsp;</td>
	<td class="normalCell" align="left" width="50%" colspan="2"><span class="normalfont"><a href=INDEX_FILE."?folderid=-2">Sent Items</a></span> <span class="smallfont">(not removable)</span></td>
	<td class="normalCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$msgcount[sentitems] message<%if $msgcount[\'sentitems\'] != 1 %>s<%endif%></span></td>
	<td class="normalCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$unreadcount[sentitems] message<%if $unreadcount[\'sentitems\'] != 1 %>s<%endif%></span></td>
	<td class="normalCell" width="10%" align="center" nowrap="nowrap"><span class="normalfont">$presizes[sentitems]KB</span></td>
	<td class="normalRightCell"><input type="checkbox" name="folder[-2]" value="yes" onClick="checkMain(this.form);" /></td>
</tr>
<tr align="center" class="highRow">
	<td class="highLeftCell" width="1%">&nbsp;</td>
	<td class="highCell" align="left" width="50%" colspan="2"><span class="normalfont"><a href=INDEX_FILE."?folderid=-3">Trash Can</a></span> <span class="smallfont">(not removable)</span></td>
	<td class="highCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$msgcount[trashcan] message<%if $msgcount[\'trashcan\'] != 1 %>s<%endif%></span></td>
	<td class="highCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$unreadcount[trashcan] message<%if $unreadcount[\'trashcan\'] != 1 %>s<%endif%></span></td>
	<td class="highCell" width="10%" align="center" nowrap="nowrap"><span class="normalfont">$presizes[trashcan]KB</span></td>
	<td class="highRightCell"><input type="checkbox" name="folder[-3]" id="trashcan" value="yes" onClick="checkMain(this.form);" /></td>
</tr>
<tr align="center" class="normalRow">
	<td class="normalLeftCell" width="1%">&nbsp;</td>
	<td class="normalCell" align="left" width="50%" colspan="2"><span class="normalfont"><a href=INDEX_FILE."?folderid=-4">Junk Mail</a></span> <span class="smallfont">(not removable)</span></td>
	<td class="normalCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$msgcount[junkmail] message<%if $msgcount[\'junkmail\'] != 1 %>s<%endif%></span></td>
	<td class="normalCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$unreadcount[junkmail] message<%if $unreadcount[\'junkmail\'] != 1 %>s<%endif%></span></td>
	<td class="normalCell" width="10%" align="center" nowrap="nowrap"><span class="normalfont">$presizes[junkmail]KB</span></td>
	<td class="normalRightCell"><input type="checkbox" name="folder[-4]" id="junkmail" value="yes" onClick="checkMain(this.form);" /></td>
</tr>
$folderbits
<tr align="center" class="headerRow">
	<th class="headerLeftCell" width="50%" nowrap="nowrap" align="left" colspan="2"><b>&nbsp;&nbsp;<a href="folders.rearrange.php?cmd=autosort"><span class="normalfonttablehead">Sort alphabetically</span></a></b></th>
	<th class="headerCell" width="50%" nowrap="nowrap" align="right"><span class="normalfonttablehead"><b>Total:&nbsp;&nbsp;</b></span></th>
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
	<th class=\\"headerLeftCell\\" width=\\"1%\\">&nbsp;</th>
	<th class=\\"headerCell\\" nowrap=\\"nowrap\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Folder Name</b></span></th>
	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>Messages</b></span></th>
	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>Unread</b></span></th>
	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>Size</b></span></th>
	<th class=\\"headerRightCell\\"><input name=\\"allbox\\" type=\\"checkbox\\" value=\\"Check All\\" title=\\"Select/Deselect All\\" onClick=\\"checkAll(this.form);\\" /></th>
</tr>
<tr align=\\"center\\" class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"1%\\">&nbsp;</td>
	<td class=\\"highCell\\" align=\\"left\\" width=\\"50%\\" colspan=\\"2\\"><span class=\\"normalfont\\"><a href=INDEX_FILE.\\"?folderid=-1\\">Inbox</a></span> <span class=\\"smallfont\\">(not removable)</span></td>
	<td class=\\"highCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$msgcount[inbox] message".(($msgcount[\'inbox\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"highCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$unreadcount[inbox] message".(($unreadcount[\'inbox\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"highCell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$presizes[inbox]KB</span></td>
	<td class=\\"highRightCell\\"><input type=\\"checkbox\\" name=\\"folder[-1]\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
<tr align=\\"center\\" class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"1%\\">&nbsp;</td>
	<td class=\\"normalCell\\" align=\\"left\\" width=\\"50%\\" colspan=\\"2\\"><span class=\\"normalfont\\"><a href=INDEX_FILE.\\"?folderid=-2\\">Sent Items</a></span> <span class=\\"smallfont\\">(not removable)</span></td>
	<td class=\\"normalCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$msgcount[sentitems] message".(($msgcount[\'sentitems\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"normalCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$unreadcount[sentitems] message".(($unreadcount[\'sentitems\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"normalCell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$presizes[sentitems]KB</span></td>
	<td class=\\"normalRightCell\\"><input type=\\"checkbox\\" name=\\"folder[-2]\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
<tr align=\\"center\\" class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"1%\\">&nbsp;</td>
	<td class=\\"highCell\\" align=\\"left\\" width=\\"50%\\" colspan=\\"2\\"><span class=\\"normalfont\\"><a href=INDEX_FILE.\\"?folderid=-3\\">Trash Can</a></span> <span class=\\"smallfont\\">(not removable)</span></td>
	<td class=\\"highCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$msgcount[trashcan] message".(($msgcount[\'trashcan\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"highCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$unreadcount[trashcan] message".(($unreadcount[\'trashcan\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"highCell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$presizes[trashcan]KB</span></td>
	<td class=\\"highRightCell\\"><input type=\\"checkbox\\" name=\\"folder[-3]\\" id=\\"trashcan\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
<tr align=\\"center\\" class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"1%\\">&nbsp;</td>
	<td class=\\"normalCell\\" align=\\"left\\" width=\\"50%\\" colspan=\\"2\\"><span class=\\"normalfont\\"><a href=INDEX_FILE.\\"?folderid=-4\\">Junk Mail</a></span> <span class=\\"smallfont\\">(not removable)</span></td>
	<td class=\\"normalCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$msgcount[junkmail] message".(($msgcount[\'junkmail\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"normalCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$unreadcount[junkmail] message".(($unreadcount[\'junkmail\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"normalCell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$presizes[junkmail]KB</span></td>
	<td class=\\"normalRightCell\\"><input type=\\"checkbox\\" name=\\"folder[-4]\\" id=\\"junkmail\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
$folderbits
<tr align=\\"center\\" class=\\"headerRow\\">
	<th class=\\"headerLeftCell\\" width=\\"50%\\" nowrap=\\"nowrap\\" align=\\"left\\" colspan=\\"2\\"><b>&nbsp;&nbsp;<a href=\\"folders.rearrange.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=autosort\\"><span class=\\"normalfonttablehead\\">Sort alphabetically</span></a></b></th>
	<th class=\\"headerCell\\" width=\\"50%\\" nowrap=\\"nowrap\\" align=\\"right\\"><span class=\\"normalfonttablehead\\"><b>Total:&nbsp;&nbsp;</b></span></th>
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
  'folders_bit' => 
  array (
    'templategroupid' => '9',
    'user_data' => '<tr align="center" class="$classnameRow">
	<td class="$classnameLeftCell" nowrap="nowrap" width="1%" valign="middle">$moveup $movedown</td>
	<td class="$classnameCell" align="left" width="50%" colspan="2"><span class="normalfont"><a href="{<INDEX_FILE>}?folderid=$folder[folderid]">$folder[title]</a></span> <span class="smallfont">(<a href="#" onClick="rename($folder[folderid], \'$folder[title]\');">rename</a>)</span></td>
	<td class="$classnameCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$folder[msgcount] message<%if $folder[\'msgcount\'] != 1 %>s<%endif%></span></td>
	<td class="$classnameCell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$folder[unreadcount] message<%if $folder[\'unreadcount\'] != 1 %>s<%endif%></span></td>
	<td class="$classnameCell" width="10%" align="center" nowrap="nowrap"><span class="normalfont">$folder[size]KB</span></td>
	<td class="$classnameRightCell"><input type="checkbox" name="folder[$folder[folderid]]" value="yes" onClick="checkMain(this.form);" /></td>
</tr>
',
    'parsed_data' => '"<tr align=\\"center\\" class=\\"$classnameRow\\">
	<td class=\\"$classnameLeftCell\\" nowrap=\\"nowrap\\" width=\\"1%\\" valign=\\"middle\\">$moveup $movedown</td>
	<td class=\\"$classnameCell\\" align=\\"left\\" width=\\"50%\\" colspan=\\"2\\"><span class=\\"normalfont\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folder[folderid]\\">$folder[title]</a></span> <span class=\\"smallfont\\">(<a href=\\"#\\" onClick=\\"rename($folder[folderid], \'$folder[title]\');\\">rename</a>)</span></td>
	<td class=\\"$classnameCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$folder[msgcount] message".(($folder[\'msgcount\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"$classnameCell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$folder[unreadcount] message".(($folder[\'unreadcount\'] != 1 ) ? ("s") : (\'\'))."</span></td>
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
		<td align="left" nowrap="nowrap" valign="top" style="padding-left: 3px; padding-top: 3px; background: url(\'$skin[images]/footer_mainbg.gif\'); border: 0px solid #254BAA; border-top-width: 1px;">
<%if $hiveuser[userid] <> 0%>
			<span class="smallfont" style="vertical-align: top;">
				<a href="#" onClick="showHideFolders(); return false;" class="footerLink">Toggle folder tab</a>
			</span>
<%else%>
			&nbsp;
<%endif%>
		</td>
		<td align="center" valign="top" style="padding-left: 0px; padding-top: 3px; width: 100%; background: url(\'$skin[images]/footer_mainbg.gif\'); border: 0px solid #254BAA; border-top-width: 1px;">
<%if $hiveuser[userid] <> 0%>
			<span class="smallfont" style="vertical-align: top;">
				<a href="{<INDEX_FILE>}" class="footerLink">Inbox</a> | 
				<a href="compose.email.php" class="footerLink">Compose</a> | 
				<a href="addressbook.view.php" class="footerLink">Address Book</a> |
				<%if $hiveuser[\'cancalendar\'] %><a href="calendar.display.php" class="footerLink">Calendar</a> | <%endif%>
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
<%if $runpop or !empty($runuserpop) %>
<script type="text/javascript" src="pop.gateway.php?<%if !empty($runuserpop) %>foruser=1&pops=$runuserpop&random={<TIMENOW>}<%endif%>"></script>
<script type="text/javascript" language="JavaScript">
<!--

if (gotNewMsgs > 0) {
	if (confirm(\'You have just received \'+gotNewMsgs+\' new message(s) to your account. Would you like to go to your {$_folders[\'-1\'][\'title\']} now?\')) {
		if (confirm(\'Open {$_folders[\'-1\'][\'title\']} in a new window?\\n\\n(Press cancel to use current window.)\')) {
			window.open(\'{<INDEX_FILE>}\', \'inbox\'); 
		} else {
			window.location = \'{<INDEX_FILE>}\';
		}
	}
}

// -->
</script>
<%endif%>
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
		<td align=\\"left\\" nowrap=\\"nowrap\\" valign=\\"top\\" style=\\"padding-left: 3px; padding-top: 3px; background: url(\'{$GLOBALS[skin][images]}/footer_mainbg.gif\'); border: 0px solid #254BAA; border-top-width: 1px;\\">
".(($hiveuser[userid] <> 0) ? ("
			<span class=\\"smallfont\\" style=\\"vertical-align: top;\\">
				<a href=\\"#\\" onClick=\\"showHideFolders(); return false;\\" class=\\"footerLink\\">Toggle folder tab</a>
			</span>
") : ("
			&nbsp;
"))."
		</td>
		<td align=\\"center\\" valign=\\"top\\" style=\\"padding-left: 0px; padding-top: 3px; width: 100%; background: url(\'{$GLOBALS[skin][images]}/footer_mainbg.gif\'); border: 0px solid #254BAA; border-top-width: 1px;\\">
".(($hiveuser[userid] <> 0) ? ("
			<span class=\\"smallfont\\" style=\\"vertical-align: top;\\">
				<a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}\\" class=\\"footerLink\\">Inbox</a> | 
				<a href=\\"compose.email.php{$GLOBALS[session_url]}\\" class=\\"footerLink\\">Compose</a> | 
				<a href=\\"addressbook.view.php{$GLOBALS[session_url]}\\" class=\\"footerLink\\">Address Book</a> |
				".(($hiveuser[\'cancalendar\'] ) ? ("<a href=\\"calendar.display.php{$GLOBALS[session_url]}\\" class=\\"footerLink\\">Calendar</a> | ") : (\'\'))."
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
".(($runpop or !empty($runuserpop) ) ? ("
<script type=\\"text/javascript\\" src=\\"pop.gateway.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}".((!empty($runuserpop) ) ? ("foruser=1&pops=$runuserpop&random=".(defined("TIMENOW") ? constant("TIMENOW") : "{<TIMENOW>}")."") : (\'\'))."\\"></script>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--

if (gotNewMsgs > 0) {
	if (confirm(\'You have just received \'+gotNewMsgs+\' new message(s) to your account. Would you like to go to your {$_folders[\'-1\'][\'title\']} now?\')) {
		if (confirm(\'Open {$_folders[\'-1\'][\'title\']} in a new window?\\\\n\\\\n(Press cancel to use current window.)\')) {
			window.open(\'".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}\', \'inbox\'); 
		} else {
			window.location = \'".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}\';
		}
	}
}

// -->
</script>
") : (\'\'))."
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
						<a href="{<INDEX_FILE>}"><img src="$skin[images]/header_icon_inbox$headimgs[1].gif" id="header_inbox" align="middle" border="0" alt="" onMouseOver="this.src = \'$skin[images]/header_icon_inbox_high.gif\';" onMouseOut="this.src = \'$skin[images]/header_icon_inbox$headimgs[1].gif\';" /></a>
					</td>
					<td align="center" width="110">
						<a href="compose.email.php"><img src="$skin[images]/header_icon_compose$headimgs[2].gif" id="header_compose" align="middle" border="0" alt="" onMouseOver="this.src = \'$skin[images]/header_icon_compose_high.gif\';" onMouseOut="this.src = \'$skin[images]/header_icon_compose$headimgs[2].gif\';" /></a>
					</td>
					<td align="center" width="110">
						<a href="addressbook.view.php"><img src="$skin[images]/header_icon_addbook$headimgs[3].gif" id="header_addbook" align="middle" border="0" alt="" onMouseOver="this.src = \'$skin[images]/header_icon_addbook_high.gif\';" onMouseOut="this.src = \'$skin[images]/header_icon_addbook$headimgs[3].gif\';" /></a>
					</td>
					<%if $hiveuser[\'cancalendar\'] %>
					<td align="center" width="110">
						<a href="calendar.display.php"><img src="$skin[images]/header_icon_calendar$headimgs[5].gif" id="header_calendar" align="middle" border="0" alt="" onMouseOver="this.src = \'$skin[images]/header_icon_calendar_high.gif\';" onMouseOut="this.src = \'$skin[images]/header_icon_calendar$headimgs[5].gif\';" /></a>
					</td>
					<%endif%>
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
						<a href="{<INDEX_FILE>}" class="headerLink" onMouseOver="header_inbox.src = \'$skin[images]/header_icon_inbox_high.gif\';" onMouseOut="header_inbox.src = \'$skin[images]/header_icon_inbox$headimgs[1].gif\';"><span class="headerLink">Inbox</span></a>
					</td>
					<td align="center" nowrap="nowrap">
						<a href="compose.email.php" class="headerLink" onMouseOver="header_compose.src = \'$skin[images]/header_icon_compose_high.gif\';" onMouseOut="header_compose.src = \'$skin[images]/header_icon_compose$headimgs[2].gif\';"><span class="headerLink">Compose</span></a>
					</td>
					<td align="center" nowrap="nowrap">
						<a href="addressbook.view.php" class="headerLink" onMouseOver="header_addbook.src = \'$skin[images]/header_icon_addbook_high.gif\';" onMouseOut="header_addbook.src = \'$skin[images]/header_icon_addbook$headimgs[3].gif\';"><span class="headerLink">Address Book</span></a>
					</td>
					<%if $hiveuser[\'cancalendar\'] %>
					<td align="center" nowrap="nowrap">
						<a href="calendar.display.php" class="headerLink" onMouseOver="header_calendar.src = \'$skin[images]/header_icon_calendar_high.gif\';" onMouseOut="header_calendar.src = \'$skin[images]/header_icon_calendar$headimgs[5].gif\';"><span class="headerLink">Calendar</span></a>
					</td>
					<%endif%>
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
		<td style="width: 157px; display: <%if !$hiveuser[showfoldertab]%>none<%endif%>;" valign="top" id="folderTab1">
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
		<td style="width: 14px; display: <%if !$hiveuser[showfoldertab]%>none<%endif%>;" id="folderTab2">
			<img src="$skin[images]/spacer.gif" width="14" height="1" alt="" />
		</td>
		<td style="width: 7px; display: <%if $hiveuser[showfoldertab]%>none<%endif%>;" id="folderTab3">
			<img src="$skin[images]/spacer.gif" width="7" height="1" alt="" />
		</td>
		<td style="width: 100%;" valign="top">
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
						<a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}\\"><img src=\\"{$GLOBALS[skin][images]}/header_icon_inbox$headimgs[1].gif\\" id=\\"header_inbox\\" align=\\"middle\\" border=\\"0\\" alt=\\"\\" onMouseOver=\\"this.src = \'{$GLOBALS[skin][images]}/header_icon_inbox_high.gif\';\\" onMouseOut=\\"this.src = \'{$GLOBALS[skin][images]}/header_icon_inbox$headimgs[1].gif\';\\" /></a>
					</td>
					<td align=\\"center\\" width=\\"110\\">
						<a href=\\"compose.email.php{$GLOBALS[session_url]}\\"><img src=\\"{$GLOBALS[skin][images]}/header_icon_compose$headimgs[2].gif\\" id=\\"header_compose\\" align=\\"middle\\" border=\\"0\\" alt=\\"\\" onMouseOver=\\"this.src = \'{$GLOBALS[skin][images]}/header_icon_compose_high.gif\';\\" onMouseOut=\\"this.src = \'{$GLOBALS[skin][images]}/header_icon_compose$headimgs[2].gif\';\\" /></a>
					</td>
					<td align=\\"center\\" width=\\"110\\">
						<a href=\\"addressbook.view.php{$GLOBALS[session_url]}\\"><img src=\\"{$GLOBALS[skin][images]}/header_icon_addbook$headimgs[3].gif\\" id=\\"header_addbook\\" align=\\"middle\\" border=\\"0\\" alt=\\"\\" onMouseOver=\\"this.src = \'{$GLOBALS[skin][images]}/header_icon_addbook_high.gif\';\\" onMouseOut=\\"this.src = \'{$GLOBALS[skin][images]}/header_icon_addbook$headimgs[3].gif\';\\" /></a>
					</td>
					".(($hiveuser[\'cancalendar\'] ) ? ("
					<td align=\\"center\\" width=\\"110\\">
						<a href=\\"calendar.display.php{$GLOBALS[session_url]}\\"><img src=\\"{$GLOBALS[skin][images]}/header_icon_calendar$headimgs[5].gif\\" id=\\"header_calendar\\" align=\\"middle\\" border=\\"0\\" alt=\\"\\" onMouseOver=\\"this.src = \'{$GLOBALS[skin][images]}/header_icon_calendar_high.gif\';\\" onMouseOut=\\"this.src = \'{$GLOBALS[skin][images]}/header_icon_calendar$headimgs[5].gif\';\\" /></a>
					</td>
					") : (\'\'))."
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
						<a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}\\" class=\\"headerLink\\" onMouseOver=\\"header_inbox.src = \'{$GLOBALS[skin][images]}/header_icon_inbox_high.gif\';\\" onMouseOut=\\"header_inbox.src = \'{$GLOBALS[skin][images]}/header_icon_inbox$headimgs[1].gif\';\\"><span class=\\"headerLink\\">Inbox</span></a>
					</td>
					<td align=\\"center\\" nowrap=\\"nowrap\\">
						<a href=\\"compose.email.php{$GLOBALS[session_url]}\\" class=\\"headerLink\\" onMouseOver=\\"header_compose.src = \'{$GLOBALS[skin][images]}/header_icon_compose_high.gif\';\\" onMouseOut=\\"header_compose.src = \'{$GLOBALS[skin][images]}/header_icon_compose$headimgs[2].gif\';\\"><span class=\\"headerLink\\">Compose</span></a>
					</td>
					<td align=\\"center\\" nowrap=\\"nowrap\\">
						<a href=\\"addressbook.view.php{$GLOBALS[session_url]}\\" class=\\"headerLink\\" onMouseOver=\\"header_addbook.src = \'{$GLOBALS[skin][images]}/header_icon_addbook_high.gif\';\\" onMouseOut=\\"header_addbook.src = \'{$GLOBALS[skin][images]}/header_icon_addbook$headimgs[3].gif\';\\"><span class=\\"headerLink\\">Address Book</span></a>
					</td>
					".(($hiveuser[\'cancalendar\'] ) ? ("
					<td align=\\"center\\" nowrap=\\"nowrap\\">
						<a href=\\"calendar.display.php{$GLOBALS[session_url]}\\" class=\\"headerLink\\" onMouseOver=\\"header_calendar.src = \'{$GLOBALS[skin][images]}/header_icon_calendar_high.gif\';\\" onMouseOut=\\"header_calendar.src = \'{$GLOBALS[skin][images]}/header_icon_calendar$headimgs[5].gif\';\\"><span class=\\"headerLink\\">Calendar</span></a>
					</td>
					") : (\'\'))."
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
		<td style=\\"width: 157px; display: ".((!$hiveuser[showfoldertab]) ? ("none") : (\'\')).";\\" valign=\\"top\\" id=\\"folderTab1\\">
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
		<td style=\\"width: 14px; display: ".((!$hiveuser[showfoldertab]) ? ("none") : (\'\')).";\\" id=\\"folderTab2\\">
			<img src=\\"{$GLOBALS[skin][images]}/spacer.gif\\" width=\\"14\\" height=\\"1\\" alt=\\"\\" />
		</td>
		<td style=\\"width: 7px; display: ".(($hiveuser[showfoldertab]) ? ("none") : (\'\')).";\\" id=\\"folderTab3\\">
			<img src=\\"{$GLOBALS[skin][images]}/spacer.gif\\" width=\\"7\\" height=\\"1\\" alt=\\"\\" />
		</td>
		<td style=\\"width: 100%;\\" valign=\\"top\\">
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
  'header_minifolderbit' => 
  array (
    'templategroupid' => '1',
    'user_data' => '<tr onContextMenu="contextForFolder(event, $thisfolder[folderid], \'$thisfolder[esctitle]\');">
	<td align="center"><a href="{<INDEX_FILE>}?folderid=$thisfolder[folderid]"><img src="$skin[images]/folders/$thisfolder[image].gif" border="0" alt="" /></a></td>
	<td nowrap="nowrap"><span class="folderLink"><a href="{<INDEX_FILE>}?folderid=$thisfolder[folderid]" class="folderLink"><span class="folderLink">$thisfolder[title]</a><%if $unreads[$thisfolder[folderid]] != 0%>  <span style="color: #0000FF;">({$unreads[$thisfolder[folderid]]})</span><%endif%></span></td>
</tr>
',
    'parsed_data' => '"<tr onContextMenu=\\"contextForFolder(event, $thisfolder[folderid], \'$thisfolder[esctitle]\');\\">
	<td align=\\"center\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$thisfolder[folderid]\\"><img src=\\"{$GLOBALS[skin][images]}/folders/$thisfolder[image].gif\\" border=\\"0\\" alt=\\"\\" /></a></td>
	<td nowrap=\\"nowrap\\"><span class=\\"folderLink\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$thisfolder[folderid]\\" class=\\"folderLink\\"><span class=\\"folderLink\\">$thisfolder[title]</a>".(($unreads[$thisfolder[folderid]] != 0) ? ("  <span style=\\"color: #0000FF;\\">({$unreads[$thisfolder[folderid]]})</span>") : (\'\'))."</span></td>
</tr>
"',
  ),
  'header_minifolderbit_current' => 
  array (
    'templategroupid' => '1',
    'user_data' => '<tr onContextMenu="contextForFolder(event, $thisfolder[folderid], \'$thisfolder[esctitle]\');">
	<td align="center"><img src="$skin[images]/folders/$thisfolder[image].gif" alt="" /></td>
	<td nowrap="nowrap" style="background-color: #A4C7E8; padding-left: 4px;"><span class="folderLink"><b>$thisfolder[title]</b><%if $unreads[$thisfolder[folderid]] != 0%>  <span style="color: #0000FF;">({$unreads[$thisfolder[folderid]]})</span><%endif%></span></td>
</tr>
',
    'parsed_data' => '"<tr onContextMenu=\\"contextForFolder(event, $thisfolder[folderid], \'$thisfolder[esctitle]\');\\">
	<td align=\\"center\\"><img src=\\"{$GLOBALS[skin][images]}/folders/$thisfolder[image].gif\\" alt=\\"\\" /></td>
	<td nowrap=\\"nowrap\\" style=\\"background-color: #A4C7E8; padding-left: 4px;\\"><span class=\\"folderLink\\"><b>$thisfolder[title]</b>".(($unreads[$thisfolder[folderid]] != 0) ? ("  <span style=\\"color: #0000FF;\\">({$unreads[$thisfolder[folderid]]})</span>") : (\'\'))."</span></td>
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
		new ContextItem(\'Move...\', function(){ window.open(\'{<INDEX_FILE>}?cmd=selfolder\',\'selectfolders\',\'resizable=no,width=270,height=150\'); }),
		new ContextItem(\'Delete\', function(){ if (confirm(\'Are you sure you want to delete the selected messages?\')) { form.cmd.value = \'delete\'; form.submit(); } }),
		new ContextItem(\'Rename Subject...\', function(){ window.open(\'read.rename.php?messageid=\'+msgID,\'renameSubject\',\'resizable=no,width=360,height=175\'); }),
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

<form action="{<INDEX_FILE>}" method="post" name="form">
<input type="hidden" name="cmd" id="cmd" value="dostuff" />
<input type="hidden" name="folderid" value="$folderid" />
<input type="hidden" name="movetofolderid" value="$folderid" />
<input type="hidden" name="remove" value="0" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
	<tr>
		<td width="100%" valign="top">
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
		<select name="replystuff" onChange="changeFolderID(); if (this.options[this.selectedIndex].value != \'nothing\') { if(replyForward(this.form, this.options[this.selectedIndex].value) == false) this.selectedIndex = 0; } else { this.selectedIndex = 0; }">
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
		</td>
		<%if $hiveuser[cancalendar] and $folderid == -1 and $hiveuser[caloninbox] %>
		<td valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="150">
				$calendar
			</table>
		</td>
		<%endif%>
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
		new ContextItem(\'Move...\', function(){ window.open(\'".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=selfolder\',\'selectfolders\',\'resizable=no,width=270,height=150\'); }),
		new ContextItem(\'Delete\', function(){ if (confirm(\'Are you sure you want to delete the selected messages?\')) { form.cmd.value = \'delete\'; form.submit(); } }),
		new ContextItem(\'Rename Subject...\', function(){ window.open(\'read.rename.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=\'+msgID,\'renameSubject\',\'resizable=no,width=360,height=175\'); }),
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

<form action=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"cmd\\" id=\\"cmd\\" value=\\"dostuff\\" />
<input type=\\"hidden\\" name=\\"folderid\\" value=\\"$folderid\\" />
<input type=\\"hidden\\" name=\\"movetofolderid\\" value=\\"$folderid\\" />
<input type=\\"hidden\\" name=\\"remove\\" value=\\"0\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
	<tr>
		<td width=\\"100%\\" valign=\\"top\\">
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
		<select name=\\"replystuff\\" onChange=\\"changeFolderID(); if (this.options[this.selectedIndex].value != \'nothing\') { if(replyForward(this.form, this.options[this.selectedIndex].value) == false) this.selectedIndex = 0; } else { this.selectedIndex = 0; }\\">
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
		</td>
		".(($hiveuser[cancalendar] and $folderid == -1 and $hiveuser[caloninbox] ) ? ("
		<td valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"150\\">
				$calendar
			</table>
		</td>
		") : (\'\'))."
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
  'index_drafts_bit' => 
  array (
    'templategroupid' => '2',
    'user_data' => '<tr class="{$bgcolor}Row">
	<td class="{$bgcolor}BothCell"><span class="normalfont"><a href="compose.email.php?draftid=$draft[draftid]">$mail[subject]</a></span></td>
</tr>
',
    'parsed_data' => '"<tr class=\\"{$bgcolor}Row\\">
	<td class=\\"{$bgcolor}BothCell\\"><span class=\\"normalfont\\"><a href=\\"compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}draftid=$draft[draftid]\\">$mail[subject]</a></span></td>
</tr>
"',
  ),
  'index_header_attach' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell" width="15" nowrap="nowrap"><span class="headerText"><a href="{<INDEX_FILE>}?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=attach"><img src="$skin[images]/paperclip.gif" alt="Has attachments?" border="0" /></a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\" width=\\"15\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=attach\\"><img src=\\"{$GLOBALS[skin][images]}/paperclip.gif\\" alt=\\"Has attachments?\\" border=\\"0\\" /></a></span></th>
"',
  ),
  'index_header_datetime' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell"><span class="headerText"><b><a href="{<INDEX_FILE>}?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=dateline"><span class="normalfonttablehead">Received</b></span>$sortimages[dateline]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\"><span class=\\"headerText\\"><b><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=dateline\\"><span class=\\"normalfonttablehead\\">Received</b></span>$sortimages[dateline]</a></span></th>
"',
  ),
  'index_header_datetime_sentitems' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell"><span class="headerText"><b><a href="{<INDEX_FILE>}?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=dateline"><span class="normalfonttablehead">Sent</b></span>$sortimages[dateline]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\"><span class=\\"headerText\\"><b><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=dateline\\"><span class=\\"normalfonttablehead\\">Sent</b></span>$sortimages[dateline]</a></span></th>
"',
  ),
  'index_header_from' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell"><span class="headerText"><a href="{<INDEX_FILE>}?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=name"><span class="normalfonttablehead"><b>From</b></span>$sortimages[name]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\"><span class=\\"headerText\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=name\\"><span class=\\"normalfonttablehead\\"><b>From</b></span>$sortimages[name]</a></span></th>
"',
  ),
  'index_header_priority' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell" width="15" nowrap="nowrap"><span class="headerText"><a href="{<INDEX_FILE>}?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=priority"><img src="$skin[images]/prio_high.gif" alt="Important?" border="0" /></a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\" width=\\"15\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=priority\\"><img src=\\"{$GLOBALS[skin][images]}/prio_high.gif\\" alt=\\"Important?\\" border=\\"0\\" /></a></span></th>
"',
  ),
  'index_header_size' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell" nowrap="nowrap"><span class="headerText"><b><a href="{<INDEX_FILE>}?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=size"><span class="normalfonttablehead">Size</b></span>$sortimages[size]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><b><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=size\\"><span class=\\"normalfonttablehead\\">Size</b></span>$sortimages[size]</a></span></th>
"',
  ),
  'index_header_subject' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell"><span class="headerText"><a href="{<INDEX_FILE>}?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=subject"><span class="normalfonttablehead"><b>Message Subject</b></span>$sortimages[subject]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\"><span class=\\"headerText\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=subject\\"><span class=\\"normalfonttablehead\\"><b>Message Subject</b></span>$sortimages[subject]</a></span></th>
"',
  ),
  'index_header_to' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell"><span class="headerText"><a href="{<INDEX_FILE>}?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=recipients"><span class="normalfonttablehead"><b>To</b></span>$sortimages[recipients]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\"><span class=\\"headerText\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=recipients\\"><span class=\\"normalfonttablehead\\"><b>To</b></span>$sortimages[recipients]</a></span></th>
"',
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
	theform.jstime.value = -curDate.getTimezoneOffset() / 60;
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
	theform.jstime.value = -curDate.getTimezoneOffset() / 60;
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
  'mailbit' => 
  array (
    'templategroupid' => '16',
    'user_data' => '<tr class="normalRow" $mail[unreadstyle] onSelectStart="return false;" id="row$mail[messageid]" onDblClick="window.location = \'read.email.php?messageid=$mail[messageid]\';">
	<td class="normalLeftCell"><img src="$skin[images]/messages/$mail[image].gif" alt="$skin[images]/$mail[image].gif" /></td>
$columns
	<%if infile(\'search\') %><td class="$bgcolors[folderid]Cell" nowrap="nowrap" width="20%" align="center" onContextMenu="checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;" onClick="checkMail($mail[messageid], 0, 1);"><a href="{<INDEX_FILE>}?folderid=$mail[folderid]"><span class="smallfont">$mail[folder]</span></a></td><%endif%>
	<td class="normalRightCell"><input type="checkbox" name="mails[$mail[messageid]]" id="mails$mail[messageid]" value="yes" onClick="this.checked = !this.checked; checkMail($mail[messageid], 0, 0, 1); this.checked = !this.checked;" /></td>
</tr>',
    'parsed_data' => '"<tr class=\\"normalRow\\" $mail[unreadstyle] onSelectStart=\\"return false;\\" id=\\"row$mail[messageid]\\" onDblClick=\\"window.location = \'read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\';\\">
	<td class=\\"normalLeftCell\\"><img src=\\"{$GLOBALS[skin][images]}/messages/$mail[image].gif\\" alt=\\"{$GLOBALS[skin][images]}/$mail[image].gif\\" /></td>
$columns
	".((infile(\'search\') ) ? ("<td class=\\"$bgcolors[folderid]Cell\\" nowrap=\\"nowrap\\" width=\\"20%\\" align=\\"center\\" onContextMenu=\\"checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;\\" onClick=\\"checkMail($mail[messageid], 0, 1);\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$mail[folderid]\\"><span class=\\"smallfont\\">$mail[folder]</span></a></td>") : (\'\'))."
	<td class=\\"normalRightCell\\"><input type=\\"checkbox\\" name=\\"mails[$mail[messageid]]\\" id=\\"mails$mail[messageid]\\" value=\\"yes\\" onClick=\\"this.checked = !this.checked; checkMail($mail[messageid], 0, 0, 1); this.checked = !this.checked;\\" /></td>
</tr>"',
  ),
  'mailbit_subject' => 
  array (
    'templategroupid' => '16',
    'user_data' => '	<td class="$bgcolors[subject]Cell" align="left" width="<%if infile(\'search\') %>45%<%else%>55%<%endif%>" onContextMenu="checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;" onClick="checkMail($mail[messageid], 0, 1);"><span class="normalfont"><%if $mail[\'isflagged\'] %><img src="$skin[images]/flag.gif" alt="Flagged" />&nbsp; <%endif%><a href="read.email.php?messageid=$mail[messageid]" <%if !empty($mail[\'color\']) %>style="color: $mail[color];"<%endif%>>$mail[subject]</a></span></td>
',
    'parsed_data' => '"	<td class=\\"$bgcolors[subject]Cell\\" align=\\"left\\" width=\\"".((infile(\'search\') ) ? ("45%") : ("55%"))."\\" onContextMenu=\\"checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;\\" onClick=\\"checkMail($mail[messageid], 0, 1);\\"><span class=\\"normalfont\\">".(($mail[\'isflagged\'] ) ? ("<img src=\\"{$GLOBALS[skin][images]}/flag.gif\\" alt=\\"Flagged\\" />&nbsp; ") : (\'\'))."<a href=\\"read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\\" ".((!empty($mail[\'color\']) ) ? ("style=\\"color: $mail[color];\\"") : (\'\')).">$mail[subject]</a></span></td>
"',
  ),
  'options_aliases' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Aliases</title>
$css
<script type="text/javascript" src="misc/aliases.js"></script>
<script type="text/javascript">
<!--
maxAliases = $hiveuser[maxaliases];
aliasesCount = $current_count;
domainName = \'$hiveuser[domain]\';
// -->
</script>
</head>
<body>
$header

<form action="options.aliases.php" method="post" onSubmit="extract_lists(this); return true;">
<input type="hidden" name="cmd" value="update" />
<input type="hidden" name="aliaslist" value="lists" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Your Aliases</b></span></th>
</tr>
<tr class="highRow">
	<td class="highBothCell" valign="top" colspan="2"><span class="normalfont"><b>Your aliases:</b></span>
	<br />
	<span class="smallfont">Aliases allow you to receive messages sent to addresses other than your primary one ($hiveuser[username]$hiveuser[domain]). Each new alias you enter below will become yours and all messages sent to it, under any available domain, will be delivered to your account.<%if $hiveuser[maxaliases] > 0 %> (You are currently limited to $hiveuser[maxaliases] aliases.)<%endif%><br /><br />
	<table align="center" width="100%">
		<tr>
			<td valign="top" align="right" width="50%"><input type="text" value="" size="30" name="alias" class="bginput" onFocus="this.form.addalias.disabled = false;" /></td>
			<td valign="top" align="center"><input type="button" disabled="disabled" value="Add ->" name="addalias" style="width: 70px;" class="bginput" onClick="addAlias(this.form, \'alias\');" /><br />
						<br /><input type="button" disabled="disabled" value="Delete" name="deletealias" style="width: 70px;" class="bginput" onClick="deleteAlias(this.form, \'alias\');" /></td>
			<td valign="top" align="left" width="50%"><select name="new_aliases[]" id="aliases" multiple="multiple" size="7" onChange="this.form.deletealias.disabled = (this.selectedIndex <= 1);">
					<option value="$hiveuser[username]">$hiveuser[username]$hiveuser[domain]</option>
					<option value="-">-----------------------</option>
					$alias_list
				</select></td>
		</tr>
	</table>
	</span></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Deliver messages multiple times:</b></span>
	<br />
	<span class="smallfont">Would you like to display the same message more than once if it is sent to more than one of your aliases? Please note that this setting will only effect new messages.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="aliasmultimails" value="1" id="aliasmultimailson" $aliasmultimailson /> <label for="aliasmultimailson">Yes</label><br /><input type="radio" name="aliasmultimails" value="0" id="aliasmultimailsoff" $aliasmultimailsoff /> <label for="aliasmultimailsoff">No</label></span></td>
</tr>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="submit" value="Submit Changes" />
		<input type="reset" class="bginput" name="reset" value="Reset Form" />
	</td>
</tr>
</table>

</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Aliases</title>
$GLOBALS[css]
<script type=\\"text/javascript\\" src=\\"misc/aliases.js\\"></script>
<script type=\\"text/javascript\\">
<!--
maxAliases = $hiveuser[maxaliases];
aliasesCount = $current_count;
domainName = \'$hiveuser[domain]\';
// -->
</script>
</head>
<body>
$GLOBALS[header]

<form action=\\"options.aliases.php{$GLOBALS[session_url]}\\" method=\\"post\\" onSubmit=\\"extract_lists(this); return true;\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />
<input type=\\"hidden\\" name=\\"aliaslist\\" value=\\"lists\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Your Aliases</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\" valign=\\"top\\" colspan=\\"2\\"><span class=\\"normalfont\\"><b>Your aliases:</b></span>
	<br />
	<span class=\\"smallfont\\">Aliases allow you to receive messages sent to addresses other than your primary one ($hiveuser[username]$hiveuser[domain]). Each new alias you enter below will become yours and all messages sent to it, under any available domain, will be delivered to your account.".(($hiveuser[maxaliases] > 0 ) ? (" (You are currently limited to $hiveuser[maxaliases] aliases.)") : (\'\'))."<br /><br />
	<table align=\\"center\\" width=\\"100%\\">
		<tr>
			<td valign=\\"top\\" align=\\"right\\" width=\\"50%\\"><input type=\\"text\\" value=\\"\\" size=\\"30\\" name=\\"alias\\" class=\\"bginput\\" onFocus=\\"this.form.addalias.disabled = false;\\" /></td>
			<td valign=\\"top\\" align=\\"center\\"><input type=\\"button\\" disabled=\\"disabled\\" value=\\"Add ->\\" name=\\"addalias\\" style=\\"width: 70px;\\" class=\\"bginput\\" onClick=\\"addAlias(this.form, \'alias\');\\" /><br />
						<br /><input type=\\"button\\" disabled=\\"disabled\\" value=\\"Delete\\" name=\\"deletealias\\" style=\\"width: 70px;\\" class=\\"bginput\\" onClick=\\"deleteAlias(this.form, \'alias\');\\" /></td>
			<td valign=\\"top\\" align=\\"left\\" width=\\"50%\\"><select name=\\"new_aliases[]\\" id=\\"aliases\\" multiple=\\"multiple\\" size=\\"7\\" onChange=\\"this.form.deletealias.disabled = (this.selectedIndex <= 1);\\">
					<option value=\\"$hiveuser[username]\\">$hiveuser[username]$hiveuser[domain]</option>
					<option value=\\"-\\">-----------------------</option>
					$alias_list
				</select></td>
		</tr>
	</table>
	</span></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Deliver messages multiple times:</b></span>
	<br />
	<span class=\\"smallfont\\">Would you like to display the same message more than once if it is sent to more than one of your aliases? Please note that this setting will only effect new messages.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"aliasmultimails\\" value=\\"1\\" id=\\"aliasmultimailson\\" $aliasmultimailson /> <label for=\\"aliasmultimailson\\">Yes</label><br /><input type=\\"radio\\" name=\\"aliasmultimails\\" value=\\"0\\" id=\\"aliasmultimailsoff\\" $aliasmultimailsoff /> <label for=\\"aliasmultimailsoff\\">No</label></span></td>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Submit Changes\\" />
		<input type=\\"reset\\" class=\\"bginput\\" name=\\"reset\\" value=\\"Reset Form\\" />
	</td>
</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
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
var workingWith = \'response\';
// This is the text that\'s added to default sigs (don\'t forget leading space)
var defstr = \' (default)\';

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
var workingWith = \'response\';
// This is the text that\'s added to default sigs (don\'t forget leading space)
var defstr = \' (default)\';

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
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Page refresh rate:</b></span>
	<br />
	<span class="smallfont">If not set to 0, the page will reload itself according to this setting.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="text" class="bginput" name="autorefresh" value="$hiveuser[autorefresh]" size="4" /> seconds</span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Mark messages as read after:</b></span>
	<br />
	<span class="smallfont">If not set to 0, messages will be automatically marked read when they are previewed.<br />Note: Requires the preview pane to be enabled as well as a browser with cookie support.</span></td>
	<td class="highRightCell" width="40%"><span class="normalfont"><input type="text" class="bginput" name="markread" value="$hiveuser[markread]" size="4" /> seconds</span></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Messages per page:</b></span>
	<br />
	<span class="smallfont">The number of messages to show per page.<br />You cannot set this to a value higher than $maxperpage.<br />It is not advisable to set this number too high, for performance reasons.</span></td>
	<td class="normalRightCell" width="40%"><input type="text" class="bginput" name="perpage" value="$hiveuser[perpage]" size="4" /></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>New message to sender:</b></span>
	<br />
	<span class="smallfont">Clicking a sender name creates a new message to the sender.</span></td>
	<td class="highRightCell" width="40%"><span class="normalfont"><input type="radio" name="senderlink" value="1" id="senderlinkon" $senderlinkon /> <label for="senderlinkon">Yes</label><br /><input type="radio" name="senderlink" value="0" id="senderlinkoff" $senderlinkoff /> <label for="senderlinkoff">No</label></span></td>
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
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Page refresh rate:</b></span>
	<br />
	<span class=\\"smallfont\\">If not set to 0, the page will reload itself according to this setting.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"autorefresh\\" value=\\"$hiveuser[autorefresh]\\" size=\\"4\\" /> seconds</span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Mark messages as read after:</b></span>
	<br />
	<span class=\\"smallfont\\">If not set to 0, messages will be automatically marked read when they are previewed.<br />Note: Requires the preview pane to be enabled as well as a browser with cookie support.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"markread\\" value=\\"$hiveuser[markread]\\" size=\\"4\\" /> seconds</span></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Messages per page:</b></span>
	<br />
	<span class=\\"smallfont\\">The number of messages to show per page.<br />You cannot set this to a value higher than $maxperpage.<br />It is not advisable to set this number too high, for performance reasons.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"perpage\\" value=\\"$hiveuser[perpage]\\" size=\\"4\\" /></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>New message to sender:</b></span>
	<br />
	<span class=\\"smallfont\\">Clicking a sender name creates a new message to the sender.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"senderlink\\" value=\\"1\\" id=\\"senderlinkon\\" $senderlinkon /> <label for=\\"senderlinkon\\">Yes</label><br /><input type=\\"radio\\" name=\\"senderlink\\" value=\\"0\\" id=\\"senderlinkoff\\" $senderlinkoff /> <label for=\\"senderlinkoff\\">No</label></span></td>
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
	<span class="smallfont">Notification e-mails will be sent to this address.</td>
	<td class="normalRightCell" width="40%"><input type="text" class="bginput" name="notifyemail" value="$hiveuser[notifyemail]" size="40" /></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Notify of all messages:</b></span>
	<br />
	<span class="smallfont">If set to yes, notification will be sent of all messages. Otherwise, notification will only be sent for messages that match a <a href="rules.list.php">rule</a> which is set to notify you.</span></td>
	<td class="highRightCell" width="40%"><span class="normalfont"><input type="radio" name="notifyall" value="1" id="notifyallon" $notifyallon /><label for="notifyallon">Yes<label><br /><input type="radio" name="notifyall" value="0" id="notifyalloff" $notifyalloff /><label for="notifyalloff">No<label></span></td>
</tr>
<%if $hiveuser[canforward] %>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Automatically forward messages:</b></span>
	<br />
	<span class="smallfont">Emails that you receive will automatically be forwarded to this address.<br />Set this to nothing to disable the feature.</span></td>
	<td class="normalRightCell" width="40%"><input type="text" class="bginput" name="forward" value="$hiveuser[forward]" size="40" /></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Keep copy of messages which are automatically forwarded:</b></span>
	<br />
	<span class="smallfont">If you decide to automatically forward messages to the address speicified above, would you like to still keep them in your inbox?</span></td>
	<td class="highRightCell" width="40%"><span class="normalfont"><input type="radio" name="deleteforwards" value="0" id="deleteforwardsoff" $deleteforwardsoff /><label for="deleteforwardsoff">Yes<label><br /><input type="radio" name="deleteforwards" value="1" id="deleteforwardson" $deleteforwardson /><label for="deleteforwardson">No<label></span></td>
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
	<span class=\\"smallfont\\">Notification e-mails will be sent to this address.</td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"notifyemail\\" value=\\"$hiveuser[notifyemail]\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Notify of all messages:</b></span>
	<br />
	<span class=\\"smallfont\\">If set to yes, notification will be sent of all messages. Otherwise, notification will only be sent for messages that match a <a href=\\"rules.list.php{$GLOBALS[session_url]}\\">rule</a> which is set to notify you.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"notifyall\\" value=\\"1\\" id=\\"notifyallon\\" $notifyallon /><label for=\\"notifyallon\\">Yes<label><br /><input type=\\"radio\\" name=\\"notifyall\\" value=\\"0\\" id=\\"notifyalloff\\" $notifyalloff /><label for=\\"notifyalloff\\">No<label></span></td>
</tr>
".(($hiveuser[canforward] ) ? ("
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Automatically forward messages:</b></span>
	<br />
	<span class=\\"smallfont\\">Emails that you receive will automatically be forwarded to this address.<br />Set this to nothing to disable the feature.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"forward\\" value=\\"$hiveuser[forward]\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Keep copy of messages which are automatically forwarded:</b></span>
	<br />
	<span class=\\"smallfont\\">If you decide to automatically forward messages to the address speicified above, would you like to still keep them in your inbox?</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"deleteforwards\\" value=\\"0\\" id=\\"deleteforwardsoff\\" $deleteforwardsoff /><label for=\\"deleteforwardsoff\\">Yes<label><br /><input type=\\"radio\\" name=\\"deleteforwards\\" value=\\"1\\" id=\\"deleteforwardson\\" $deleteforwardson /><label for=\\"deleteforwardson\\">No<label></span></td>
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
  'options_menu_aliases' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="options.aliases.php"><span class="normalfonttablehead"><b>Aliases</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Define and configure your account aliases.</span></td>
</tr>
</table>',
    'parsed_data' => '"<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"options.aliases.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Aliases</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Define and configure your account aliases.</span></td>
</tr>
</table>"',
  ),
  'options_menu_calendar' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<table cellpadding="4" cellspacing="0" class="normalTable" width="300">
<tr class="headerRow">
	<th class="headerBothCell"><a href="calendar.options.php"><span class="normalfonttablehead"><b>Calendar</b></span></a></th>
</tr>
<tr class="highRow" style="height: 50px;">
	<td class="highBothCell" valign="top"><span class="normalfont">Settings about your calendar, different display options, and more.</span></td>
</tr>
</table>',
    'parsed_data' => '"<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"300\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><a href=\\"calendar.options.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\"><b>Calendar</b></span></a></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 50px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"normalfont\\">Settings about your calendar, different display options, and more.</span></td>
</tr>
</table>"',
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
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Attachments open in new window:</b></span>
	<br />
	<span class="smallfont">Do you wish for a new window to open for each attachment?</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="attachwin" value="1" id="attachwinon" $attachwinon /> <label for="attachwinon">Yes</label><br /><input type="radio" name="attachwin" value="0" id="attachwinoff" $attachwinoff /> <label for="attachwinoff">No</label></span></td>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Displayed attached images below the message:</b></span>
	<br />
	<span class="smallfont">Images that are attached to the message you are reading will be automatically displayed below the message when reading it.</span></td>
	<td class="highRightCell" width="40%"><span class="normalfont"><input type="radio" name="showimginmsg" value="1" id="showimginmsgon" $showimginmsgon /> <label for="showimginmsgon">Yes</label><br /><input type="radio" name="showimginmsg" value="0" id="showimginmsgoff" $showimginmsgoff /> <label for="showimginmsgoff">No</label></span></td>
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
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Attachments open in new window:</b></span>
	<br />
	<span class=\\"smallfont\\">Do you wish for a new window to open for each attachment?</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"attachwin\\" value=\\"1\\" id=\\"attachwinon\\" $attachwinon /> <label for=\\"attachwinon\\">Yes</label><br /><input type=\\"radio\\" name=\\"attachwin\\" value=\\"0\\" id=\\"attachwinoff\\" $attachwinoff /> <label for=\\"attachwinoff\\">No</label></span></td>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Displayed attached images below the message:</b></span>
	<br />
	<span class=\\"smallfont\\">Images that are attached to the message you are reading will be automatically displayed below the message when reading it.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"showimginmsg\\" value=\\"1\\" id=\\"showimginmsgon\\" $showimginmsgon /> <label for=\\"showimginmsgon\\">Yes</label><br /><input type=\\"radio\\" name=\\"showimginmsg\\" value=\\"0\\" id=\\"showimginmsgoff\\" $showimginmsgoff /> <label for=\\"showimginmsgoff\\">No</label></span></td>
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
var workingWith = \'signature\';
// This is the text that\'s added to default sigs (don\'t forget leading space)
var defstr = \' (default)\';

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
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top"><span class="normalfont"><b>Automatically add signature:</b></span>
	<br />
	<span class="smallfont">If this is turned on, the default signature you specify below will automatically be added<br />to your messages before they are sent.<br />Otherwise, you will have the option to add the signature manually.</span></td>
	<td class="highRightCell" width="40%"><span class="normalfont"><input type="radio" name="autoaddsig" value="2" id="autoaddsigon" $autoaddsigon /> <label for="autoaddsigon">Yes</label><br /><input type="radio" name="autoaddsig" value="1" id="autoaddsigonly" $autoaddsigonly /> <label for="autoaddsigonly">Only when not replying</label><br /><input type="radio" name="autoaddsig" value="0" id="autoaddsigoff" $autoaddsigoff /> <label for="autoaddsigoff">No</label></span></td>
</tr>
<tr class="normalRow">
	<td class="normalLeftCell" width="60%" valign="top"><span class="normalfont"><b>Use a random signature every time:</b></span>
	<br />
	<span class="smallfont">If you have enabled the option above for automatically adding your signature, you can turn this on to have the system use a different, random signature every time. Otherwise the default signature as defined below will be used.</span></td>
	<td class="normalRightCell" width="40%"><span class="normalfont"><input type="radio" name="userandomsig" value="1" id="userandomsigon" $userandomsigon /> <label for="userandomsigon">Yes</label><br /><input type="radio" name="userandomsig" value="0" id="userandomsigoff" $userandomsigoff /> <label for="userandomsigoff">No</label></span></td>
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
var workingWith = \'signature\';
// This is the text that\'s added to default sigs (don\'t forget leading space)
var defstr = \' (default)\';

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
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Automatically add signature:</b></span>
	<br />
	<span class=\\"smallfont\\">If this is turned on, the default signature you specify below will automatically be added<br />to your messages before they are sent.<br />Otherwise, you will have the option to add the signature manually.</span></td>
	<td class=\\"highRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"autoaddsig\\" value=\\"2\\" id=\\"autoaddsigon\\" $autoaddsigon /> <label for=\\"autoaddsigon\\">Yes</label><br /><input type=\\"radio\\" name=\\"autoaddsig\\" value=\\"1\\" id=\\"autoaddsigonly\\" $autoaddsigonly /> <label for=\\"autoaddsigonly\\">Only when not replying</label><br /><input type=\\"radio\\" name=\\"autoaddsig\\" value=\\"0\\" id=\\"autoaddsigoff\\" $autoaddsigoff /> <label for=\\"autoaddsigoff\\">No</label></span></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalLeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Use a random signature every time:</b></span>
	<br />
	<span class=\\"smallfont\\">If you have enabled the option above for automatically adding your signature, you can turn this on to have the system use a different, random signature every time. Otherwise the default signature as defined below will be used.</span></td>
	<td class=\\"normalRightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"userandomsig\\" value=\\"1\\" id=\\"userandomsigon\\" $userandomsigon /> <label for=\\"userandomsigon\\">Yes</label><br /><input type=\\"radio\\" name=\\"userandomsig\\" value=\\"0\\" id=\\"userandomsigoff\\" $userandomsigoff /> <label for=\\"userandomsigoff\\">No</label></span></td>
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
<script type="text/javascript" language="JavaScript">
<!--

function popProps(popID) {
     var hWnd = window.open("pop.list.php?cmd=popup&popid="+popID, "POP3Props", "width=520,height=685,resizable=yes,scrollbars=no");
     if ((document.window != null) && (!hWnd.opener)) {
          hWnd.opener = document.window;
	 }
}

function popNew() {
     var hWnd = window.open("pop.add.php?cmd=step1", "newPOP3", "width=520,height=295,resizable=yes,scrollbars=no");
     if ((document.window != null) && (!hWnd.opener)) {
          hWnd.opener = document.window;
	 }
}

// -->
</script>
</head>
<body>
$header

<form action="pop.update.php" method="post" name="form">
<input type="hidden" name="cmd" value="updateall" />
<input type="hidden" name="origpass" value="$origpass" />

<table width="730" cellpadding="7">
	<tr>
		<td colspan="2" style="padding: 0px 12px 4px 12px;"><table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			<tr class="headerRow">
				<th class="headerBothCell"><span class="normalfonttablehead">Your POP3 Accounts</span></th>
			</tr>
			<tr>
				<td class="highBothCell" align="left"><span class="normalfont"><%if !empty($popbits) %>Please note that for security reasons the account passwords are not displayed on this page.<%else%>You currently have no external POP3 accounts set up. To create a new one, please click on the button below.<%endif%></span></td>
			</tr>
		</table></td>
	</tr>
	$popbits
	<tr>
		<td style="padding-top: 16px;" colspan="2" align="center"><input type="button" class="bginput" name="create" value="Add New Account" onClick="popNew();" /><%if !empty($popbits) %>&nbsp;&nbsp;<input type="submit" class="bginput" name="submit" value="Save Changes" />&nbsp;&nbsp;<input type="reset" class="bginput" name="reset" value="Reset All Fields" /><%endif%></td>
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
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--

function popProps(popID) {
     var hWnd = window.open(\\"pop.list.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=popup&popid=\\"+popID, \\"POP3Props\\", \\"width=520,height=685,resizable=yes,scrollbars=no\\");
     if ((document.window != null) && (!hWnd.opener)) {
          hWnd.opener = document.window;
	 }
}

function popNew() {
     var hWnd = window.open(\\"pop.add.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=step1\\", \\"newPOP3\\", \\"width=520,height=295,resizable=yes,scrollbars=no\\");
     if ((document.window != null) && (!hWnd.opener)) {
          hWnd.opener = document.window;
	 }
}

// -->
</script>
</head>
<body>
$GLOBALS[header]

<form action=\\"pop.update.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"updateall\\" />
<input type=\\"hidden\\" name=\\"origpass\\" value=\\"$origpass\\" />

<table width=\\"730\\" cellpadding=\\"7\\">
	<tr>
		<td colspan=\\"2\\" style=\\"padding: 0px 12px 4px 12px;\\"><table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			<tr class=\\"headerRow\\">
				<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\">Your POP3 Accounts</span></th>
			</tr>
			<tr>
				<td class=\\"highBothCell\\" align=\\"left\\"><span class=\\"normalfont\\">".((!empty($popbits) ) ? ("Please note that for security reasons the account passwords are not displayed on this page.") : ("You currently have no external POP3 accounts set up. To create a new one, please click on the button below."))."</span></td>
			</tr>
		</table></td>
	</tr>
	$popbits
	<tr>
		<td style=\\"padding-top: 16px;\\" colspan=\\"2\\" align=\\"center\\"><input type=\\"button\\" class=\\"bginput\\" name=\\"create\\" value=\\"Add New Account\\" onClick=\\"popNew();\\" />".((!empty($popbits) ) ? ("&nbsp;&nbsp;<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Save Changes\\" />&nbsp;&nbsp;<input type=\\"reset\\" class=\\"bginput\\" name=\\"reset\\" value=\\"Reset All Fields\\" />") : (\'\'))."</td>
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
    'user_data' => '<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
	<tr class="headerRow">
		<th class="headerBothCell" colspan="2"><span style="vertical-align: middle;"><a href="#" onClick="popProps($pop[popid]); return false;"><span class="normalfonttablehead"><b>$pop[accountname]</b>&nbsp;&nbsp;&nbsp;<img src="$skin[images]/pencil.gif" alt="Edit Account" border="0" align="middle" /></span></a>&nbsp;&nbsp;<a href="pop.delete.php?popid=$pop[popid]" onClick="if (!confirm(\'Are you sure you want to remove this account?\')) return false;"><img src="$skin[images]/delete.gif" alt="Remove Account" border="0" align="middle" /></a></span></th>
	</tr>
	<tr>
		<td class="highLeftCell" align="left"><span class="normalfont"><b><label for="displayemail$pop[popid]">Email address:</label></b></span></td>
		<td class="highRightCell" align="left"><input type="text" id="displayemail$pop[popid]" class="highInactive" name="serverinfo[$pop[popid]][displayemail]" value="$pop[displayemail]" size="26" onFocus="this.className = \'bginput\';" onBlur="this.className = \'highInactive\';" /></td>
	</tr>
	<tr>
		<td class="normalLeftCell" align="left" width="126"><span class="normalfont"><b><label for="server$pop[popid]">POP3 Server:</label></b></span></td>
		<td class="normalRightCell" align="left"><input type="text" id="server$pop[popid]" class="normalInactive" name="serverinfo[$pop[popid]][server]" value="$pop[server]" size="20" onFocus="this.className = \'bginput\';" onBlur="this.className = \'normalInactive\';" /><input type="text" id="port$pop[popid]" class="normalInactive" name="serverinfo[$pop[popid]][port]" value="$pop[port]" size="4" onFocus="this.className = \'bginput\';" onBlur="this.className = \'normalInactive\';" /></td>
	</tr>
	<tr>
		<td class="highLeftCell" align="left"><span class="normalfont"><b><label for="username$pop[popid]">Username:</label></b></span></td>
		<td class="highRightCell" align="left"><input type="text" id="username$pop[popid]" class="highInactive" name="serverinfo[$pop[popid]][username]" value="$pop[username]" size="26" onFocus="this.className = \'bginput\';" onBlur="this.className = \'highInactive\';" /></td>
	</tr>
	<tr>
		<td class="normalLeftCell" align="left"><span class="normalfont"><b><label for="password$pop[popid]">Password:</label></b></span></td>
		<td class="normalRightCell" align="left"><input type="password" autocomplete="off" id="password$pop[popid]" class="normalInactive" name="serverinfo[$pop[popid]][password]" value="$pop[password]" size="26" onFocus="this.className = \'bginput\';" onBlur="this.className = \'normalInactive\';" /></td>
	</tr>
	<tr>
		<td class="highLeftCell" align="left"><span class="normalfont"><b><label for="smtpserver$pop[popid]">SMTP Server:</label></b></span></td>
		<td class="highRightCell" align="left"><input type="text" id="smtpserver$pop[popid]" class="highInactive" name="serverinfo[$pop[popid]][smtp_server]" value="$pop[smtp_server]" size="20" onFocus="if (this.value == \'(none)\') this.value = \'\'; this.className = \'bginput\';" onBlur="if (this.value == \'\') this.value = \'(none)\'; this.className = \'highInactive\';" /><input type="text" id="smtpport$pop[popid]" class="highInactive" name="serverinfo[$pop[popid]][smtp_port]" value="$pop[smtp_port]" size="4" onFocus="this.className = \'bginput\';" onBlur="this.className = \'highInactive\';" /></td>
	</tr>
</table>',
    'parsed_data' => '"<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
	<tr class=\\"headerRow\\">
		<th class=\\"headerBothCell\\" colspan=\\"2\\"><span style=\\"vertical-align: middle;\\"><a href=\\"#\\" onClick=\\"popProps($pop[popid]); return false;\\"><span class=\\"normalfonttablehead\\"><b>$pop[accountname]</b>&nbsp;&nbsp;&nbsp;<img src=\\"{$GLOBALS[skin][images]}/pencil.gif\\" alt=\\"Edit Account\\" border=\\"0\\" align=\\"middle\\" /></span></a>&nbsp;&nbsp;<a href=\\"pop.delete.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}popid=$pop[popid]\\" onClick=\\"if (!confirm(\'Are you sure you want to remove this account?\')) return false;\\"><img src=\\"{$GLOBALS[skin][images]}/delete.gif\\" alt=\\"Remove Account\\" border=\\"0\\" align=\\"middle\\" /></a></span></th>
	</tr>
	<tr>
		<td class=\\"highLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><b><label for=\\"displayemail$pop[popid]\\">Email address:</label></b></span></td>
		<td class=\\"highRightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"displayemail$pop[popid]\\" class=\\"highInactive\\" name=\\"serverinfo[$pop[popid]][displayemail]\\" value=\\"$pop[displayemail]\\" size=\\"26\\" onFocus=\\"this.className = \'bginput\';\\" onBlur=\\"this.className = \'highInactive\';\\" /></td>
	</tr>
	<tr>
		<td class=\\"normalLeftCell\\" align=\\"left\\" width=\\"126\\"><span class=\\"normalfont\\"><b><label for=\\"server$pop[popid]\\">POP3 Server:</label></b></span></td>
		<td class=\\"normalRightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"server$pop[popid]\\" class=\\"normalInactive\\" name=\\"serverinfo[$pop[popid]][server]\\" value=\\"$pop[server]\\" size=\\"20\\" onFocus=\\"this.className = \'bginput\';\\" onBlur=\\"this.className = \'normalInactive\';\\" /><input type=\\"text\\" id=\\"port$pop[popid]\\" class=\\"normalInactive\\" name=\\"serverinfo[$pop[popid]][port]\\" value=\\"$pop[port]\\" size=\\"4\\" onFocus=\\"this.className = \'bginput\';\\" onBlur=\\"this.className = \'normalInactive\';\\" /></td>
	</tr>
	<tr>
		<td class=\\"highLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><b><label for=\\"username$pop[popid]\\">Username:</label></b></span></td>
		<td class=\\"highRightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"username$pop[popid]\\" class=\\"highInactive\\" name=\\"serverinfo[$pop[popid]][username]\\" value=\\"$pop[username]\\" size=\\"26\\" onFocus=\\"this.className = \'bginput\';\\" onBlur=\\"this.className = \'highInactive\';\\" /></td>
	</tr>
	<tr>
		<td class=\\"normalLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><b><label for=\\"password$pop[popid]\\">Password:</label></b></span></td>
		<td class=\\"normalRightCell\\" align=\\"left\\"><input type=\\"password\\" autocomplete=\\"off\\" id=\\"password$pop[popid]\\" class=\\"normalInactive\\" name=\\"serverinfo[$pop[popid]][password]\\" value=\\"$pop[password]\\" size=\\"26\\" onFocus=\\"this.className = \'bginput\';\\" onBlur=\\"this.className = \'normalInactive\';\\" /></td>
	</tr>
	<tr>
		<td class=\\"highLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><b><label for=\\"smtpserver$pop[popid]\\">SMTP Server:</label></b></span></td>
		<td class=\\"highRightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"smtpserver$pop[popid]\\" class=\\"highInactive\\" name=\\"serverinfo[$pop[popid]][smtp_server]\\" value=\\"$pop[smtp_server]\\" size=\\"20\\" onFocus=\\"if (this.value == \'(none)\') this.value = \'\'; this.className = \'bginput\';\\" onBlur=\\"if (this.value == \'\') this.value = \'(none)\'; this.className = \'highInactive\';\\" /><input type=\\"text\\" id=\\"smtpport$pop[popid]\\" class=\\"highInactive\\" name=\\"serverinfo[$pop[popid]][smtp_port]\\" value=\\"$pop[smtp_port]\\" size=\\"4\\" onFocus=\\"this.className = \'bginput\';\\" onBlur=\\"this.className = \'highInactive\';\\" /></td>
	</tr>
</table>"',
  ),
  'pop_addaccount_step1' => 
  array (
    'templategroupid' => '15',
    'user_data' => '$skin[doctype]
<html>
<head>
<title>$appname: POP3 Accounts - Create New Account (Step 1 of 4)</title>
$css
<script language="Javascript">
<!--

self.focus();

// -->
</script>
</head>
<body style="background-color: #C7E1F4;">

$header

<form action="pop.add.php" method="post" name="form">
<input type="hidden" name="cmd" value="step2" />
<input type="hidden" name="newpopinfo" value="$newpopinfo" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
	<tr class="headerRow">
		<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Account Information</b></span></th>
	</tr>
	<tr class="normalRow">
		<td class="normalLeftCell" align="left"><span class="normalfont"><label for="displayname">Your name:</label></span></td>
		<td class="normalRightCell" align="left"><input type="text" id="displayname" class="bginput" name="newpop[displayname]" size="20" /></td>
	</tr>
	<tr class="highRow">
		<td class="highLeftCell" align="left"><span class="normalfont"><label for="displayemail">Email address:</label></span></td>
		<td class="highRightCell" align="left"><input type="text" id="displayemail" class="bginput" name="newpop[displayemail]" size="20" /></td>
	</tr>
</table>

<br />
<div align="center">
<input type="submit" class="bginput" value="Continue" />&nbsp;
<input type="button" class="bginput" value=" Cancel " onClick="window.close();" />
</div>
</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head>
<title>$appname: POP3 Accounts - Create New Account (Step 1 of 4)</title>
$GLOBALS[css]
<script language=\\"Javascript\\">
<!--

self.focus();

// -->
</script>
</head>
<body style=\\"background-color: #C7E1F4;\\">

$GLOBALS[header]

<form action=\\"pop.add.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"step2\\" />
<input type=\\"hidden\\" name=\\"newpopinfo\\" value=\\"$newpopinfo\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
	<tr class=\\"headerRow\\">
		<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Account Information</b></span></th>
	</tr>
	<tr class=\\"normalRow\\">
		<td class=\\"normalLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"displayname\\">Your name:</label></span></td>
		<td class=\\"normalRightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"displayname\\" class=\\"bginput\\" name=\\"newpop[displayname]\\" size=\\"20\\" /></td>
	</tr>
	<tr class=\\"highRow\\">
		<td class=\\"highLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"displayemail\\">Email address:</label></span></td>
		<td class=\\"highRightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"displayemail\\" class=\\"bginput\\" name=\\"newpop[displayemail]\\" size=\\"20\\" /></td>
	</tr>
</table>

<br />
<div align=\\"center\\">
<input type=\\"submit\\" class=\\"bginput\\" value=\\"Continue\\" />&nbsp;
<input type=\\"button\\" class=\\"bginput\\" value=\\" Cancel \\" onClick=\\"window.close();\\" />
</div>
</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'pop_addaccount_step2' => 
  array (
    'templategroupid' => '15',
    'user_data' => '$skin[doctype]
<html>
<head>
<title>$appname: POP3 Accounts - Create New Account (Step 2 of 4)</title>
$css
</head>
<body style="background-color: #C7E1F4;">

$header

<form action="pop.add.php" method="post" name="form">
<input type="hidden" name="cmd" value="step3" />
<input type="hidden" name="newpopinfo" value="$newpopinfo" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
	<tr class="headerRow">
		<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Incoming Messages (POP3)</b></span></th>
	</tr>
	<tr class="highRow">
		<td class="highLeftCell" align="left"><span class="normalfont"><label for="popserver">Server</label> and <label for="popport">Port</label>:</span></td>
		<td class="highRightCell" align="left"><input type="text" id="popserver" class="bginput" name="newpop[server]" value="$newpop[server]" size="20" />&nbsp;<input type="text" id="popport" class="bginput" name="newpop[port]" value="$newpop[port]" size="4" /></td>
	</tr>
	<tr class="normalRow">
		<td class="normalLeftCell" align="left"><span class="normalfont"><label for="popusername">Username:</label></span></td>
		<td class="normalRightCell" align="left"><input type="text" id="popusername" class="bginput" name="newpop[username]" value="$newpop[username]" size="20" /></td>
	</tr>
	<tr class="highRow">
		<td class="highLeftCell" align="left"><span class="normalfont"><label for="poppassword">Password:</label></span></td>
		<td class="highRightCell" align="left"><input type="password" autocomplete="off" id="poppassword" class="bginput" name="newpop[password]" value="$newpop[password]" size="20" /></td>
	</tr>
</table>

<br />
<div align="center">
<input type="submit" class="bginput" value="Continue" />&nbsp;
<input type="button" class="bginput" value=" Cancel " onClick="window.close();" />
</div>
</form>

<%if $badinfo %>
<span class="important">The server information you provided was incorrect and we were unable to log into your account. Please try again.</span>
<%else%>
<br />&nbsp;
<%endif%>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head>
<title>$appname: POP3 Accounts - Create New Account (Step 2 of 4)</title>
$GLOBALS[css]
</head>
<body style=\\"background-color: #C7E1F4;\\">

$GLOBALS[header]

<form action=\\"pop.add.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"step3\\" />
<input type=\\"hidden\\" name=\\"newpopinfo\\" value=\\"$newpopinfo\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
	<tr class=\\"headerRow\\">
		<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Incoming Messages (POP3)</b></span></th>
	</tr>
	<tr class=\\"highRow\\">
		<td class=\\"highLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"popserver\\">Server</label> and <label for=\\"popport\\">Port</label>:</span></td>
		<td class=\\"highRightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"popserver\\" class=\\"bginput\\" name=\\"newpop[server]\\" value=\\"$newpop[server]\\" size=\\"20\\" />&nbsp;<input type=\\"text\\" id=\\"popport\\" class=\\"bginput\\" name=\\"newpop[port]\\" value=\\"$newpop[port]\\" size=\\"4\\" /></td>
	</tr>
	<tr class=\\"normalRow\\">
		<td class=\\"normalLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"popusername\\">Username:</label></span></td>
		<td class=\\"normalRightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"popusername\\" class=\\"bginput\\" name=\\"newpop[username]\\" value=\\"$newpop[username]\\" size=\\"20\\" /></td>
	</tr>
	<tr class=\\"highRow\\">
		<td class=\\"highLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"poppassword\\">Password:</label></span></td>
		<td class=\\"highRightCell\\" align=\\"left\\"><input type=\\"password\\" autocomplete=\\"off\\" id=\\"poppassword\\" class=\\"bginput\\" name=\\"newpop[password]\\" value=\\"$newpop[password]\\" size=\\"20\\" /></td>
	</tr>
</table>

<br />
<div align=\\"center\\">
<input type=\\"submit\\" class=\\"bginput\\" value=\\"Continue\\" />&nbsp;
<input type=\\"button\\" class=\\"bginput\\" value=\\" Cancel \\" onClick=\\"window.close();\\" />
</div>
</form>

".(($badinfo ) ? ("
<span class=\\"important\\">The server information you provided was incorrect and we were unable to log into your account. Please try again.</span>
") : ("
<br />&nbsp;
"))."

$GLOBALS[footer]

</body>
</html>"',
  ),
  'pop_addaccount_step3' => 
  array (
    'templategroupid' => '15',
    'user_data' => '$skin[doctype]
<html>
<head>
<title>$appname: POP3 Accounts - Create New Account (Step 3 of 4)</title>
$css
<script language="Javascript">
<!--

function changeAuth(option, theform) {
	if (option == \'none\' || option == \'same\') {
		theform.smtpusername.disabled = theform.smtppassword.disabled = true;
		if (option == \'same\') {
			theform.smtpusername.value = theform.popusername.value;
			theform.smtppassword.value = theform.poppassword.value;
		} else {
			theform.smtpusername.value = theform.smtppassword.value = \'\';
		}
	} else {
		theform.smtpusername.disabled = theform.smtppassword.disabled = false;
	}
}

// -->
</script>
</head>
<body style="background-color: #C7E1F4;">

$header

<form action="pop.add.php" method="post" name="form" onSubmit="this.smtpusername.disabled = this.smtppassword.disabled = false;">
<input type="hidden" name="cmd" value="step4" />
<input type="hidden" name="newpopinfo" value="$newpopinfo" />
<input type="hidden" name="popusername" value="$newpop[username]" />
<input type="hidden" name="poppassword" value="$newpop[password]" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
	<tr class="headerRow">
		<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Outgoing Messages (SMTP)</b></span> <span class="smallfonttablehead"><b>- Optional</b></span></th>
	</tr>
	<tr class="normalRow">
		<td class="normalLeftCell" align="left"><span class="normalfont"><label for="smtpserver">Server</label> and <label for="smtpport">Port</label>:</span></td>
		<td class="normalRightCell" align="left"><input type="text" id="smtpserver" class="bginput" name="newpop[smtp_server]" value="$newpop[smtp_server]" size="20" onKeyUp="this.form.smtpauth.disabled = (this.value == \'\');" />&nbsp;<input type="text" id="smtpport" class="bginput" name="newpop[smtp_port]" value="$newpop[smtp_port]" size="4" /></td>
	</tr>
	<tr class="highRow">
		<td class="highLeftCell" align="left"><span class="normalfont"><label for="smtpauth">Authentication:</label></span></td>
		<td class="highRightCell" align="left"><select name="smtpauth" $authdisabled onChange="changeAuth(this.options[this.selectedIndex].value, this.form);">
			<option value="none" $authsel[none]>No authentication required</option>
			<option value="same" $authsel[same]>Use same login values as POP3</option>
			<option value="diff" $authsel[diff]>Enter different login information</option>
		</select></td>
	</tr>
	<tr class="normalRow">
		<td class="normalLeftCell" align="left"><span class="normalfont"><label for="smtpusername">Username:</label></span></td>
		<td class="normalRightCell" align="left"><input type="text" $smtplogindisabled id="smtpusername" class="bginput" name="newpop[smtp_username]" value="$newpop[smtp_username]" size="20" /></td>
	</tr>
	<tr class="highRow">
		<td class="highLeftCell" align="left"><span class="normalfont"><label for="smtppassword">Password:</label></span></td>
		<td class="highRightCell" align="left"><input type="password" $smtplogindisabled autocomplete="off" id="smtppassword" class="bginput" name="newpop[smtp_password]" value="$newpop[smtp_password]" size="20" /></td>
	</tr>
</table>

<br />
<div align="center">
<input type="submit" class="bginput" value="Continue" />&nbsp;
<input type="button" class="bginput" value=" Cancel " onClick="window.close();" />
</div>
</form>

<%if $badinfo %>
<span class="important">The server information you provided was incorrect and we were unable to connect to the server. Please try again.</span>
<%else%>
<br />&nbsp;
<%endif%>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head>
<title>$appname: POP3 Accounts - Create New Account (Step 3 of 4)</title>
$GLOBALS[css]
<script language=\\"Javascript\\">
<!--

function changeAuth(option, theform) {
	if (option == \'none\' || option == \'same\') {
		theform.smtpusername.disabled = theform.smtppassword.disabled = true;
		if (option == \'same\') {
			theform.smtpusername.value = theform.popusername.value;
			theform.smtppassword.value = theform.poppassword.value;
		} else {
			theform.smtpusername.value = theform.smtppassword.value = \'\';
		}
	} else {
		theform.smtpusername.disabled = theform.smtppassword.disabled = false;
	}
}

// -->
</script>
</head>
<body style=\\"background-color: #C7E1F4;\\">

$GLOBALS[header]

<form action=\\"pop.add.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\" onSubmit=\\"this.smtpusername.disabled = this.smtppassword.disabled = false;\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"step4\\" />
<input type=\\"hidden\\" name=\\"newpopinfo\\" value=\\"$newpopinfo\\" />
<input type=\\"hidden\\" name=\\"popusername\\" value=\\"$newpop[username]\\" />
<input type=\\"hidden\\" name=\\"poppassword\\" value=\\"$newpop[password]\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
	<tr class=\\"headerRow\\">
		<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Outgoing Messages (SMTP)</b></span> <span class=\\"smallfonttablehead\\"><b>- Optional</b></span></th>
	</tr>
	<tr class=\\"normalRow\\">
		<td class=\\"normalLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"smtpserver\\">Server</label> and <label for=\\"smtpport\\">Port</label>:</span></td>
		<td class=\\"normalRightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"smtpserver\\" class=\\"bginput\\" name=\\"newpop[smtp_server]\\" value=\\"$newpop[smtp_server]\\" size=\\"20\\" onKeyUp=\\"this.form.smtpauth.disabled = (this.value == \'\');\\" />&nbsp;<input type=\\"text\\" id=\\"smtpport\\" class=\\"bginput\\" name=\\"newpop[smtp_port]\\" value=\\"$newpop[smtp_port]\\" size=\\"4\\" /></td>
	</tr>
	<tr class=\\"highRow\\">
		<td class=\\"highLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"smtpauth\\">Authentication:</label></span></td>
		<td class=\\"highRightCell\\" align=\\"left\\"><select name=\\"smtpauth\\" $authdisabled onChange=\\"changeAuth(this.options[this.selectedIndex].value, this.form);\\">
			<option value=\\"none\\" $authsel[none]>No authentication required</option>
			<option value=\\"same\\" $authsel[same]>Use same login values as POP3</option>
			<option value=\\"diff\\" $authsel[diff]>Enter different login information</option>
		</select></td>
	</tr>
	<tr class=\\"normalRow\\">
		<td class=\\"normalLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"smtpusername\\">Username:</label></span></td>
		<td class=\\"normalRightCell\\" align=\\"left\\"><input type=\\"text\\" $smtplogindisabled id=\\"smtpusername\\" class=\\"bginput\\" name=\\"newpop[smtp_username]\\" value=\\"$newpop[smtp_username]\\" size=\\"20\\" /></td>
	</tr>
	<tr class=\\"highRow\\">
		<td class=\\"highLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"smtppassword\\">Password:</label></span></td>
		<td class=\\"highRightCell\\" align=\\"left\\"><input type=\\"password\\" $smtplogindisabled autocomplete=\\"off\\" id=\\"smtppassword\\" class=\\"bginput\\" name=\\"newpop[smtp_password]\\" value=\\"$newpop[smtp_password]\\" size=\\"20\\" /></td>
	</tr>
</table>

<br />
<div align=\\"center\\">
<input type=\\"submit\\" class=\\"bginput\\" value=\\"Continue\\" />&nbsp;
<input type=\\"button\\" class=\\"bginput\\" value=\\" Cancel \\" onClick=\\"window.close();\\" />
</div>
</form>

".(($badinfo ) ? ("
<span class=\\"important\\">The server information you provided was incorrect and we were unable to connect to the server. Please try again.</span>
") : ("
<br />&nbsp;
"))."

$GLOBALS[footer]

</body>
</html>"',
  ),
  'pop_addaccount_step4' => 
  array (
    'templategroupid' => '15',
    'user_data' => '$skin[doctype]
<html>
<head>
<title>$appname: POP3 Accounts - Create New Account (Step 4 of 4)</title>
$css
</head>
<body style="background-color: #C7E1F4;">

$header

<form action="pop.add.php" method="post" name="form">
<input type="hidden" name="cmd" value="finish" />
<input type="hidden" name="newpopinfo" value="$newpopinfo" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
	<tr class="headerRow">
		<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Account Information</b></span></th>
	</tr>
	<tr class="highRow">
		<td class="highLeftCell" align="left"><span class="normalfont"><label for="accountname">Account name:</label></span></td>
		<td class="highRightCell" align="left"><input type="text" id="accountname" class="bginput" name="newpop[accountname]" value="$newpop[accountname]" size="20" /></td>
	</tr>
</table>

<br />
<div align="center">
<input type="submit" class="bginput" value=" Finish " />&nbsp;
<input type="button" class="bginput" value=" Cancel " onClick="window.close();" />
</div>
</form>

<%if $badinfo %>
<span class="important">The name you entered for this account was invalid. Please try again.</span>
<%else%>
<br />
<%endif%>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head>
<title>$appname: POP3 Accounts - Create New Account (Step 4 of 4)</title>
$GLOBALS[css]
</head>
<body style=\\"background-color: #C7E1F4;\\">

$GLOBALS[header]

<form action=\\"pop.add.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"finish\\" />
<input type=\\"hidden\\" name=\\"newpopinfo\\" value=\\"$newpopinfo\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
	<tr class=\\"headerRow\\">
		<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Account Information</b></span></th>
	</tr>
	<tr class=\\"highRow\\">
		<td class=\\"highLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"accountname\\">Account name:</label></span></td>
		<td class=\\"highRightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"accountname\\" class=\\"bginput\\" name=\\"newpop[accountname]\\" value=\\"$newpop[accountname]\\" size=\\"20\\" /></td>
	</tr>
</table>

<br />
<div align=\\"center\\">
<input type=\\"submit\\" class=\\"bginput\\" value=\\" Finish \\" />&nbsp;
<input type=\\"button\\" class=\\"bginput\\" value=\\" Cancel \\" onClick=\\"window.close();\\" />
</div>
</form>

".(($badinfo ) ? ("
<span class=\\"important\\">The name you entered for this account was invalid. Please try again.</span>
") : ("
<br />
"))."

$GLOBALS[footer]

</body>
</html>"',
  ),
  'pop_editaccount' => 
  array (
    'templategroupid' => '15',
    'user_data' => '$skin[doctype]
<html>
<head>
<title>$appname: POP3 Accounts - $pop[accountname]</title>
$css
<script language="Javascript">
<!--

self.focus();

function changeAuth(option, theform) {
	if (option == \'none\' || option == \'same\') {
		theform.smtpusername.disabled = theform.smtppassword.disabled = true;
		if (option == \'same\') {
			theform.smtpusername.value = theform.popusername.value;
			theform.smtppassword.value = theform.poppassword.value;
		} else {
			theform.smtpusername.value = theform.smtppassword.value = \'\';
		}
	} else {
		theform.smtpusername.disabled = theform.smtppassword.disabled = false;
	}
}

// -->
</script>
</head>
<body style="background-color: #C7E1F4;">

$header

<form action="pop.update.php" method="post" name="form">
<input type="hidden" name="cmd" value="singleupdate" />
<input type="hidden" name="popid" value="$pop[popid]" />
<input type="hidden" name="origpass" value="$origpass" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
	<tr class="headerRow">
		<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Account Information</b></span></th>
	</tr>
	<tr class="highRow">
		<td class="highLeftCell" align="left"><span class="normalfont"><label for="accountname">Account name:</label></span></td>
		<td class="highRightCell" align="left"><input type="text" id="accountname" class="bginput" name="newpop[accountname]" value="$pop[accountname]" size="20" /></td>
	</tr>
	<tr class="normalRow">
		<td class="normalLeftCell" align="left"><span class="normalfont"><label for="displayname">Your name:</label></span></td>
		<td class="normalRightCell" align="left"><input type="text" id="displayname" class="bginput" name="newpop[displayname]" value="$pop[displayname]" size="20" /></td>
	</tr>
	<tr class="highRow">
		<td class="highLeftCell" align="left"><span class="normalfont"><label for="displayemail">Email address:</label></span></td>
		<td class="highRightCell" align="left"><input type="text" id="displayemail" class="bginput" name="newpop[displayemail]" value="$pop[displayemail]" size="20" /></td>
	</tr>
	<tr>
		<td colspan="2"><span class="smallfonttablehead">&nbsp;</span></td>
	</tr>
	<tr class="headerRow">
		<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Incoming Messages (POP3)</b></span> <span class="smallfonttablehead"><b>- Required</b></span></th>
	</tr>
	<tr class="highRow">
		<td class="highLeftCell" align="left"><span class="normalfont"><label for="popserver">Server</label> and <label for="popport">Port</label>:</span></td>
		<td class="highRightCell" align="left"><input type="text" id="popserver" class="bginput" name="newpop[server]" value="$pop[server]" size="20" />&nbsp;<input type="text" id="popport" class="bginput" name="newpop[port]" value="$pop[port]" size="4" /></td>
	</tr>
	<tr class="normalRow">
		<td class="normalLeftCell" align="left"><span class="normalfont"><label for="popusername">Username:</label></span></td>
		<td class="normalRightCell" align="left"><input type="text" id="popusername" class="bginput" name="newpop[username]" value="$pop[username]" size="20" /></td>
	</tr>
	<tr class="highRow">
		<td class="highLeftCell" align="left"><span class="normalfont"><label for="poppassword">Password:</label></span></td>
		<td class="highRightCell" align="left"><input type="password" autocomplete="off" id="poppassword" class="bginput" name="newpop[password]" value="$pop[password]" size="20" /></td>
	</tr>
	<tr>
		<td colspan="2"><span class="smallfonttablehead">&nbsp;</span></td>
	</tr>
	<tr class="headerRow">
		<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Outgoing Messages (SMTP)</b></span> <span class="smallfonttablehead"><b>- Optional</b></span></th>
	</tr>
	<tr class="normalRow">
		<td class="normalLeftCell" align="left"><span class="normalfont"><label for="smtpserver">Server</label> and <label for="smtpport">Port</label>:</span></td>
		<td class="normalRightCell" align="left"><input type="text" id="smtpserver" class="bginput" name="newpop[smtp_server]" value="$pop[smtp_server]" size="20" onKeyUp="this.form.smtpauth.disabled = (this.value == \'\');" />&nbsp;<input type="text" id="smtpport" class="bginput" name="newpop[smtp_port]" value="$pop[smtp_port]" size="4" /></td>
	</tr>
	<tr class="highRow">
		<td class="highLeftCell" align="left"><span class="normalfont"><label for="smtpauth">Authentication:</label></span></td>
		<td class="highRightCell" align="left"><select name="smtpauth" $authdisabled onChange="changeAuth(this.options[this.selectedIndex].value, this.form);">
			<option value="none" $authsel[none]>No authentication required</option>
			<option value="same" $authsel[same]>Use same login values as above</option>
			<option value="diff" $authsel[diff]>Enter different login information</option>
		</select></td>
	</tr>
	<tr class="normalRow">
		<td class="normalLeftCell" align="left"><span class="normalfont"><label for="smtpusername">Username:</label></span></td>
		<td class="normalRightCell" align="left"><input type="text" $smtplogindisabled id="smtpusername" class="bginput" name="newpop[smtp_username]" value="$pop[smtp_username]" size="20" /></td>
	</tr>
	<tr class="highRow">
		<td class="highLeftCell" align="left"><span class="normalfont"><label for="smtppassword">Password:</label></span></td>
		<td class="highRightCell" align="left"><input type="password" autocomplete="off" $smtplogindisabled id="smtppassword" class="bginput" name="newpop[smtp_password]" value="$pop[smtp_password]" size="20" /></td>
	</tr>
	<tr>
		<td colspan="2"><span class="smallfonttablehead">&nbsp;</span></td>
	</tr>
	<tr class="headerRow">
		<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Account Options</b></span></th>
	</tr>
	<tr class="normalRow">
		<td class="normalLeftCell" align="left"><span class="normalfont"><label for="autopoll">Automatically download messages:</label></span></td>
		<td class="normalRightCell" align="left"><span class="normalfont"><input type="checkbox" name="newpop[autopoll]" value="1" id="autopoll" $autopollchecked /> <label for="autopoll">Yes</label></span></td>
	</tr>
	<tr class="highRow">
		<td class="highLeftCell" align="left"><span class="normalfont"><label for="delete">Leave messages on server:</label></span></td>
		<td class="highRightCell" align="left"><select id="delete" name="newpop[delete]">
			<option value="1" $delsel[1]>Delete immediately</option>
			<option value="2" $delsel[2]>Synchronize with local mailbox</option>
			<option value="0" $delsel[0]>Never delete messages</option>
		</select></td>
	</tr>
	<tr class="normalRow">
		<td class="normalLeftCell" align="left"><span class="normalfont"><label for="folderid">Place incoming messages in folder:</label></span></td>
		<td class="normalRightCell" align="left"><select id="folderid" name="newpop[folderid]" style="width: 150px;">
			$folderbits
		</select></td>
	</tr>
	<tr class="highRow">
		<td class="highLeftCell" align="left"><span class="normalfont"><label for="color">Highlight messages with color:</label></span></td>
		<td class="highRightCell" align="left"><select id="color" name="newpop[color]" style="width: 150px;">
			<option value="none" $colorsel[none]>No color</option>
			<option value="Black" style="color: Black;" $colorsel[black]>Black</option>
			<option value="Maroon" style="color: Maroon;" $colorsel[maroon]>Maroon</option>
			<option value="Green" style="color: Green;" $colorsel[green]>Green</option>
			<option value="Olive" style="color: Olive;" $colorsel[olive]>Olive</option>
			<option value="Navy" style="color: Navy;" $colorsel[navy]>Navy</option>
			<option value="Purple" style="color: Purple;" $colorsel[purple]>Purple</option>
			<option value="Teal" style="color: Teal;" $colorsel[teal]>Teal</option>
			<option value="Gray" style="color: Gray;" $colorsel[gray]>Gray</option>
			<option value="Silver" style="color: Silver;" $colorsel[silver]>Silver</option>
			<option value="Red" style="color: Red;" $colorsel[red]>Red</option>
			<option value="Lime" style="color: Lime;" $colorsel[lime]>Lime</option>
			<option value="Yellow" style="color: Yellow;" $colorsel[yellow]>Yellow</option>
			<option value="Blue" style="color: Blue;" $colorsel[blue]>Blue</option>
			<option value="Fuchsia" style="color: Fuchsia;" $colorsel[fuchsia]>Fuchsia</option>
			<option value="Aqua" style="color: Aqua;" $colorsel[aqua]>Aqua</option>
			<option value="White" style="color: Black;" $colorsel[white]>White</option>
		</select></td>
	</tr>
</table>

<br />
<div align="center">
<input type="submit" class="bginput" value="Save Changes" />&nbsp;
<input type="reset" class="bginput" value="Reset Options" />&nbsp;
<input type="button" class="bginput" value="Close Window" onClick="window.close();" />
</div>
</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head>
<title>$appname: POP3 Accounts - $pop[accountname]</title>
$GLOBALS[css]
<script language=\\"Javascript\\">
<!--

self.focus();

function changeAuth(option, theform) {
	if (option == \'none\' || option == \'same\') {
		theform.smtpusername.disabled = theform.smtppassword.disabled = true;
		if (option == \'same\') {
			theform.smtpusername.value = theform.popusername.value;
			theform.smtppassword.value = theform.poppassword.value;
		} else {
			theform.smtpusername.value = theform.smtppassword.value = \'\';
		}
	} else {
		theform.smtpusername.disabled = theform.smtppassword.disabled = false;
	}
}

// -->
</script>
</head>
<body style=\\"background-color: #C7E1F4;\\">

$GLOBALS[header]

<form action=\\"pop.update.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"singleupdate\\" />
<input type=\\"hidden\\" name=\\"popid\\" value=\\"$pop[popid]\\" />
<input type=\\"hidden\\" name=\\"origpass\\" value=\\"$origpass\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
	<tr class=\\"headerRow\\">
		<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Account Information</b></span></th>
	</tr>
	<tr class=\\"highRow\\">
		<td class=\\"highLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"accountname\\">Account name:</label></span></td>
		<td class=\\"highRightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"accountname\\" class=\\"bginput\\" name=\\"newpop[accountname]\\" value=\\"$pop[accountname]\\" size=\\"20\\" /></td>
	</tr>
	<tr class=\\"normalRow\\">
		<td class=\\"normalLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"displayname\\">Your name:</label></span></td>
		<td class=\\"normalRightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"displayname\\" class=\\"bginput\\" name=\\"newpop[displayname]\\" value=\\"$pop[displayname]\\" size=\\"20\\" /></td>
	</tr>
	<tr class=\\"highRow\\">
		<td class=\\"highLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"displayemail\\">Email address:</label></span></td>
		<td class=\\"highRightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"displayemail\\" class=\\"bginput\\" name=\\"newpop[displayemail]\\" value=\\"$pop[displayemail]\\" size=\\"20\\" /></td>
	</tr>
	<tr>
		<td colspan=\\"2\\"><span class=\\"smallfonttablehead\\">&nbsp;</span></td>
	</tr>
	<tr class=\\"headerRow\\">
		<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Incoming Messages (POP3)</b></span> <span class=\\"smallfonttablehead\\"><b>- Required</b></span></th>
	</tr>
	<tr class=\\"highRow\\">
		<td class=\\"highLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"popserver\\">Server</label> and <label for=\\"popport\\">Port</label>:</span></td>
		<td class=\\"highRightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"popserver\\" class=\\"bginput\\" name=\\"newpop[server]\\" value=\\"$pop[server]\\" size=\\"20\\" />&nbsp;<input type=\\"text\\" id=\\"popport\\" class=\\"bginput\\" name=\\"newpop[port]\\" value=\\"$pop[port]\\" size=\\"4\\" /></td>
	</tr>
	<tr class=\\"normalRow\\">
		<td class=\\"normalLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"popusername\\">Username:</label></span></td>
		<td class=\\"normalRightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"popusername\\" class=\\"bginput\\" name=\\"newpop[username]\\" value=\\"$pop[username]\\" size=\\"20\\" /></td>
	</tr>
	<tr class=\\"highRow\\">
		<td class=\\"highLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"poppassword\\">Password:</label></span></td>
		<td class=\\"highRightCell\\" align=\\"left\\"><input type=\\"password\\" autocomplete=\\"off\\" id=\\"poppassword\\" class=\\"bginput\\" name=\\"newpop[password]\\" value=\\"$pop[password]\\" size=\\"20\\" /></td>
	</tr>
	<tr>
		<td colspan=\\"2\\"><span class=\\"smallfonttablehead\\">&nbsp;</span></td>
	</tr>
	<tr class=\\"headerRow\\">
		<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Outgoing Messages (SMTP)</b></span> <span class=\\"smallfonttablehead\\"><b>- Optional</b></span></th>
	</tr>
	<tr class=\\"normalRow\\">
		<td class=\\"normalLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"smtpserver\\">Server</label> and <label for=\\"smtpport\\">Port</label>:</span></td>
		<td class=\\"normalRightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"smtpserver\\" class=\\"bginput\\" name=\\"newpop[smtp_server]\\" value=\\"$pop[smtp_server]\\" size=\\"20\\" onKeyUp=\\"this.form.smtpauth.disabled = (this.value == \'\');\\" />&nbsp;<input type=\\"text\\" id=\\"smtpport\\" class=\\"bginput\\" name=\\"newpop[smtp_port]\\" value=\\"$pop[smtp_port]\\" size=\\"4\\" /></td>
	</tr>
	<tr class=\\"highRow\\">
		<td class=\\"highLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"smtpauth\\">Authentication:</label></span></td>
		<td class=\\"highRightCell\\" align=\\"left\\"><select name=\\"smtpauth\\" $authdisabled onChange=\\"changeAuth(this.options[this.selectedIndex].value, this.form);\\">
			<option value=\\"none\\" $authsel[none]>No authentication required</option>
			<option value=\\"same\\" $authsel[same]>Use same login values as above</option>
			<option value=\\"diff\\" $authsel[diff]>Enter different login information</option>
		</select></td>
	</tr>
	<tr class=\\"normalRow\\">
		<td class=\\"normalLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"smtpusername\\">Username:</label></span></td>
		<td class=\\"normalRightCell\\" align=\\"left\\"><input type=\\"text\\" $smtplogindisabled id=\\"smtpusername\\" class=\\"bginput\\" name=\\"newpop[smtp_username]\\" value=\\"$pop[smtp_username]\\" size=\\"20\\" /></td>
	</tr>
	<tr class=\\"highRow\\">
		<td class=\\"highLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"smtppassword\\">Password:</label></span></td>
		<td class=\\"highRightCell\\" align=\\"left\\"><input type=\\"password\\" autocomplete=\\"off\\" $smtplogindisabled id=\\"smtppassword\\" class=\\"bginput\\" name=\\"newpop[smtp_password]\\" value=\\"$pop[smtp_password]\\" size=\\"20\\" /></td>
	</tr>
	<tr>
		<td colspan=\\"2\\"><span class=\\"smallfonttablehead\\">&nbsp;</span></td>
	</tr>
	<tr class=\\"headerRow\\">
		<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Account Options</b></span></th>
	</tr>
	<tr class=\\"normalRow\\">
		<td class=\\"normalLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"autopoll\\">Automatically download messages:</label></span></td>
		<td class=\\"normalRightCell\\" align=\\"left\\"><span class=\\"normalfont\\"><input type=\\"checkbox\\" name=\\"newpop[autopoll]\\" value=\\"1\\" id=\\"autopoll\\" $autopollchecked /> <label for=\\"autopoll\\">Yes</label></span></td>
	</tr>
	<tr class=\\"highRow\\">
		<td class=\\"highLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"delete\\">Leave messages on server:</label></span></td>
		<td class=\\"highRightCell\\" align=\\"left\\"><select id=\\"delete\\" name=\\"newpop[delete]\\">
			<option value=\\"1\\" $delsel[1]>Delete immediately</option>
			<option value=\\"2\\" $delsel[2]>Synchronize with local mailbox</option>
			<option value=\\"0\\" $delsel[0]>Never delete messages</option>
		</select></td>
	</tr>
	<tr class=\\"normalRow\\">
		<td class=\\"normalLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"folderid\\">Place incoming messages in folder:</label></span></td>
		<td class=\\"normalRightCell\\" align=\\"left\\"><select id=\\"folderid\\" name=\\"newpop[folderid]\\" style=\\"width: 150px;\\">
			$folderbits
		</select></td>
	</tr>
	<tr class=\\"highRow\\">
		<td class=\\"highLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"color\\">Highlight messages with color:</label></span></td>
		<td class=\\"highRightCell\\" align=\\"left\\"><select id=\\"color\\" name=\\"newpop[color]\\" style=\\"width: 150px;\\">
			<option value=\\"none\\" $colorsel[none]>No color</option>
			<option value=\\"Black\\" style=\\"color: Black;\\" $colorsel[black]>Black</option>
			<option value=\\"Maroon\\" style=\\"color: Maroon;\\" $colorsel[maroon]>Maroon</option>
			<option value=\\"Green\\" style=\\"color: Green;\\" $colorsel[green]>Green</option>
			<option value=\\"Olive\\" style=\\"color: Olive;\\" $colorsel[olive]>Olive</option>
			<option value=\\"Navy\\" style=\\"color: Navy;\\" $colorsel[navy]>Navy</option>
			<option value=\\"Purple\\" style=\\"color: Purple;\\" $colorsel[purple]>Purple</option>
			<option value=\\"Teal\\" style=\\"color: Teal;\\" $colorsel[teal]>Teal</option>
			<option value=\\"Gray\\" style=\\"color: Gray;\\" $colorsel[gray]>Gray</option>
			<option value=\\"Silver\\" style=\\"color: Silver;\\" $colorsel[silver]>Silver</option>
			<option value=\\"Red\\" style=\\"color: Red;\\" $colorsel[red]>Red</option>
			<option value=\\"Lime\\" style=\\"color: Lime;\\" $colorsel[lime]>Lime</option>
			<option value=\\"Yellow\\" style=\\"color: Yellow;\\" $colorsel[yellow]>Yellow</option>
			<option value=\\"Blue\\" style=\\"color: Blue;\\" $colorsel[blue]>Blue</option>
			<option value=\\"Fuchsia\\" style=\\"color: Fuchsia;\\" $colorsel[fuchsia]>Fuchsia</option>
			<option value=\\"Aqua\\" style=\\"color: Aqua;\\" $colorsel[aqua]>Aqua</option>
			<option value=\\"White\\" style=\\"color: Black;\\" $colorsel[white]>White</option>
		</select></td>
	</tr>
</table>

<br />
<div align=\\"center\\">
<input type=\\"submit\\" class=\\"bginput\\" value=\\"Save Changes\\" />&nbsp;
<input type=\\"reset\\" class=\\"bginput\\" value=\\"Reset Options\\" />&nbsp;
<input type=\\"button\\" class=\\"bginput\\" value=\\"Close Window\\" onClick=\\"window.close();\\" />
</div>
</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'pop_nopops' => 
  array (
    'templategroupid' => '15',
    'user_data' => 'You currently have no external POP3 accounts set up. To create a new one, please click on the button below.',
    'parsed_data' => '"You currently have no external POP3 accounts set up. To create a new one, please click on the button below."',
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
		imgevent("read.receipt.php?messageid=$messageid");
	}
}

$callcomment setTimeout(sendReadReceipt, 1000);
$directcallcomment imgevent("read.receipt.php?messageid=$messageid");

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
	<td class="$afterattach[second]RightCell" align="left" width="90%"><span class="normalfont"><a href="read.markas.php?messageid=$mail[messageid]&markas=$markas&back=message">mark as $markas</a> | <a href="read.source.php?messageid=$mail[messageid]">view source</a> | <a href="read.source.php?messageid=$mail[messageid]&cmd=save">save as</a> | <a href="read.printable.php?messageid=$mail[messageid]">printable version</a> | <a href="#" onClick="window.open(\'read.rename.php?messageid=$messageid\',\'renameSubject\',\'resizable=no,width=360,height=175\'); return false;">edit subject</a><%if getop(\'allowbouncing\') and !($mail[\'status\'] & MAIL_BOUNCED) %> | <a href="read.bounce.php?messageid=$mail[messageid]">bounce message</a><%endif%><%if $hiveuser[canreportspam] and !($mail[\'status\'] & MAIL_REPORTED) %> | <a href="read.report.php?messageid=$mail[messageid]">report spam</a><%endif%></span></td>
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
		imgevent(\\"read.receipt.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$messageid\\");
	}
}

$callcomment setTimeout(sendReadReceipt, 1000);
$directcallcomment imgevent(\\"read.receipt.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$messageid\\");

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
	<td class=\\"$afterattach[second]RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\"><a href=\\"read.markas.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]&markas=$markas&back=message\\">mark as $markas</a> | <a href=\\"read.source.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\\">view source</a> | <a href=\\"read.source.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]&cmd=save\\">save as</a> | <a href=\\"read.printable.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\\">printable version</a> | <a href=\\"#\\" onClick=\\"window.open(\'read.rename.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$messageid\',\'renameSubject\',\'resizable=no,width=360,height=175\'); return false;\\">edit subject</a>".((getop(\'allowbouncing\') and !($mail[\'status\'] & MAIL_BOUNCED) ) ? (" | <a href=\\"read.bounce.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\\">bounce message</a>") : (\'\')).(($hiveuser[canreportspam] and !($mail[\'status\'] & MAIL_REPORTED) ) ? (" | <a href=\\"read.report.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\\">report spam</a>") : (\'\'))."</span></td>
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
  'read_attachments_bit' => 
  array (
    'templategroupid' => '6',
    'user_data' => '<a href="read.attachment.php?messageid=$messageid&attachnum=$attachnum" <%if $hiveuser[\'attachwin\']%>target="_blank"<%endif%>>$filename</a> ({$filesize}KB)<br />
',
    'parsed_data' => '"<a href=\\"read.attachment.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$messageid&attachnum=$attachnum\\" ".(($hiveuser[\'attachwin\']) ? ("target=\\"_blank\\"") : (\'\')).">$filename</a> ({$filesize}KB)<br />
"',
  ),
  'read_cc' => 
  array (
    'templategroupid' => '6',
    'user_data' => '<tr class="$afterto[second]Row">
	<td class="$afterto[second]LeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>CC:</b></span></td>
	<td class="$afterto[second]RightCell" align="left" width="90%"><span class="normalfont">$cclist</span></td>
</tr>',
    'parsed_data' => '"<tr class=\\"$afterto[second]Row\\">
	<td class=\\"$afterto[second]LeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>CC:</b></span></td>
	<td class=\\"$afterto[second]RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\">$cclist</span></td>
</tr>"',
  ),
  'read_iframe_message' => 
  array (
    'templategroupid' => '6',
    'user_data' => '$skin[doctype]
<html>
<head>
$css
<script type="text/javascript" language="JavaScript">
<!--

if ($hiveuser[markread] > 0) {
	setTimeout(function () { imgevent(\'read.markas.php?messageid=$messageid&markas=read&img=1\'); }, $hiveuser[markread] * 1000);
}

// -->
</script>
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
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--

if ($hiveuser[markread] > 0) {
	setTimeout(function () { imgevent(\'read.markas.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$messageid&markas=read&img=1\'); }, $hiveuser[markread] * 1000);
}

// -->
</script>
</head>
<body topmargin=\\"0\\" leftmargin=\\"0\\" marginheight=\\"0\\" marginwidth=\\"0\\" bgcolor=\\"$bgcolor\\" style=\\"background-color: transparent;\\"><span class=\\"normalfont\\">
$mail[message]
</span>
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
<script language="JavaScript">
<!--
this.getPageUrl = function() { return this.partner.location.href; };
// -->
</script>
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
<script language=\\"JavaScript\\">
<!--
this.getPageUrl = function() { return this.partner.location.href; };
// -->
</script>
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
					<td valign="top" style="padding: 5px 0px 5px 5px; border: 0px solid #9BC1E6; border-width: 1px 0px 1px 0px; width: 100%; background-color: $skin[pagebgcolor]; width: 100%;"><span class="normalfont">You are visiting a site outside of $appname. To return to the message you were previously reading, <a href="$appurl/read.email.php?messageid=$messageid" target="_parent">click here</a>.<script language="JavaScript">
<!--
if (document.all) {
	document.write(\' To display this page in a full window, <a href="#" onClick="top.location.href = top.getPageUrl(); return false;">click here</a>.\');
}
// -->
</script></span>
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
					<td valign=\\"top\\" style=\\"padding: 5px 0px 5px 5px; border: 0px solid #9BC1E6; border-width: 1px 0px 1px 0px; width: 100%; background-color: {$GLOBALS[skin][pagebgcolor]}; width: 100%;\\"><span class=\\"normalfont\\">You are visiting a site outside of $appname. To return to the message you were previously reading, <a href=\\"$appurl/read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$messageid\\" target=\\"_parent\\">click here</a>.<script language=\\"JavaScript\\">
<!--
if (document.all) {
	document.write(\' To display this page in a full window, <a href=\\"#\\" onClick=\\"top.location.href = top.getPageUrl(); return false;\\">click here</a>.\');
}
// -->
</script></span>
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
  'read_popmessage' => 
  array (
    'templategroupid' => '6',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: $mail[subject]</title>
$css
<script language="JavaScript" type="text/javascript">
<!--

event_addListener( window, "load", function() { document.all.theMessage.style.height = document.frames(\'theMessage\').document.body.scrollHeight + 45; } )

//-->
</script>
</head>
<body>

$header

<form action="read.email.php" method="post" name="form">
<input type="hidden" name="popid" value="$popid" />
<input type="hidden" name="msgid" value="$msgid" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Message</b></span></th>
</tr>
<tr class="highRow">
	<td class="highLeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>From:</b></span></td>
	<td class="highRightCell" align="left" width="90%" valign="middle"><span class="normalfont" style="vertical-align: middle;">$mail[fromname] (<a href="compose.email.php?email=$mail[fromemailenc]">$mail[fromemail]</a>)</span>&nbsp;<a href="addressbook.add.php?cmd=quick&popid=$popid&msgid=$msgid"><img src="$skin[images]/addbook.gif" alt="Add sender to address book" align="middle" border="0" /></a>&nbsp;&nbsp;<span class="smallfont"><%if $hiveuser[cansearch] %><a href="search.results.php?folderids[]=0&searchdate=-1&fields[]=email&query=$mail[fromemailenc]"><img src="$skin[images]/find.gif" alt="Find more messages from sender" align="middle" border="0" /></a><%endif%><%if $hiveuser[canrule] %> <a href="rules.block.php?email=$mail[fromemailenc]"><img src="$skin[images]/block.gif" alt="Block sender" align="middle" border="0" /></a><%endif%></span></td>
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
	<td class="$afterattach[second]RightCell" align="left" width="90%"><span class="normalfont"><a href="read.source.php?popid=$popid&msgid=$msgid">view source</a> | <a href="read.source.php?popid=$popid&msgid=$msgid&cmd=save">save as</a> | <a href="read.printable.php?popid=$popid&msgid=$msgid">printable version</a><%if getop(\'allowbouncing\') and !($mail[\'status\'] & MAIL_BOUNCED) %> | <a href="read.bounce.php?popid=$popid&msgid=$msgid">bounce message</a><%endif%></span></td>
</tr>
<tr class="$afterattach[first]Row">
	<td class="$afterattach[first]BothCell" valign="top" colspan="2">
	<table width="100%" cellpadding="4" cellspacing="0" border="0">
	<tr>
		<td width="100%"><iframe id="theMessage" src="read.email.php?popid=$popid&msgid=$msgid&show=msg&bgcolor=$afterattach[first]" style="background-color: $iframebgcolor; width: 100%; height: 350px;" allowtransparency="true" frameborder="no"><span class="normalfont">$mail[message]</span></iframe></td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td align="right" colspan="2">
    <table border="0" width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td align="left">
		<input type="button" class="bginput" value="Reply" onClick="window.location=(\'compose.email.php?special=reply&amp;popid=$popid&amp;msgid=$msgid\')" />&nbsp;&nbsp;&nbsp;
		<input type="button" class="bginput" value="Reply All" onClick="window.location=(\'compose.email.php?special=replyall&amp;popid=$popid&amp;msgid=$msgid\')" />&nbsp;&nbsp;&nbsp;
		<input type="button" class="bginput" value="Forward" onClick="window.location=(\'compose.email.php?special=forward&amp;popid=$popid&amp;msgid=$msgid\')" />&nbsp;&nbsp;&nbsp;
		<input style="width: 170px;" type="button" class="bginput" value="Forward as Attachment" onClick="window.location=(\'compose.email.php?special=forward&amp;attach=1&amp;popid=$popid&amp;msgid=$msgid\')" /></td>
        <td align="right">&nbsp;</td>
      </tr>
    </table></td>
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

event_addListener( window, \\"load\\", function() { document.all.theMessage.style.height = document.frames(\'theMessage\').document.body.scrollHeight + 45; } )

//-->
</script>
</head>
<body>

$GLOBALS[header]

<form action=\\"read.email.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"popid\\" value=\\"$popid\\" />
<input type=\\"hidden\\" name=\\"msgid\\" value=\\"$msgid\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Message</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>From:</b></span></td>
	<td class=\\"highRightCell\\" align=\\"left\\" width=\\"90%\\" valign=\\"middle\\"><span class=\\"normalfont\\" style=\\"vertical-align: middle;\\">$mail[fromname] (<a href=\\"compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}email=$mail[fromemailenc]\\">$mail[fromemail]</a>)</span>&nbsp;<a href=\\"addressbook.add.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=quick&popid=$popid&msgid=$msgid\\"><img src=\\"{$GLOBALS[skin][images]}/addbook.gif\\" alt=\\"Add sender to address book\\" align=\\"middle\\" border=\\"0\\" /></a>&nbsp;&nbsp;<span class=\\"smallfont\\">".(($hiveuser[cansearch] ) ? ("<a href=\\"search.results.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderids[]=0&searchdate=-1&fields[]=email&query=$mail[fromemailenc]\\"><img src=\\"{$GLOBALS[skin][images]}/find.gif\\" alt=\\"Find more messages from sender\\" align=\\"middle\\" border=\\"0\\" /></a>") : (\'\')).(($hiveuser[canrule] ) ? (" <a href=\\"rules.block.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}email=$mail[fromemailenc]\\"><img src=\\"{$GLOBALS[skin][images]}/block.gif\\" alt=\\"Block sender\\" align=\\"middle\\" border=\\"0\\" /></a>") : (\'\'))."</span></td>
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
	<td class=\\"$afterattach[second]RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\"><a href=\\"read.source.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}popid=$popid&msgid=$msgid\\">view source</a> | <a href=\\"read.source.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}popid=$popid&msgid=$msgid&cmd=save\\">save as</a> | <a href=\\"read.printable.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}popid=$popid&msgid=$msgid\\">printable version</a>".((getop(\'allowbouncing\') and !($mail[\'status\'] & MAIL_BOUNCED) ) ? (" | <a href=\\"read.bounce.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}popid=$popid&msgid=$msgid\\">bounce message</a>") : (\'\'))."</span></td>
</tr>
<tr class=\\"$afterattach[first]Row\\">
	<td class=\\"$afterattach[first]BothCell\\" valign=\\"top\\" colspan=\\"2\\">
	<table width=\\"100%\\" cellpadding=\\"4\\" cellspacing=\\"0\\" border=\\"0\\">
	<tr>
		<td width=\\"100%\\"><iframe id=\\"theMessage\\" src=\\"read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}popid=$popid&msgid=$msgid&show=msg&bgcolor=$afterattach[first]\\" style=\\"background-color: $iframebgcolor; width: 100%; height: 350px;\\" allowtransparency=\\"true\\" frameborder=\\"no\\"><span class=\\"normalfont\\">$mail[message]</span></iframe></td>
	</tr>
	</table>
	</td>
</tr>
<tr>
	<td align=\\"right\\" colspan=\\"2\\">
    <table border=\\"0\\" width=\\"100%\\" cellpadding=\\"0\\" cellspacing=\\"0\\">
      <tr>
        <td align=\\"left\\">
		<input type=\\"button\\" class=\\"bginput\\" value=\\"Reply\\" onClick=\\"window.location=(\'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}special=reply&amp;popid=$popid&amp;msgid=$msgid\')\\" />&nbsp;&nbsp;&nbsp;
		<input type=\\"button\\" class=\\"bginput\\" value=\\"Reply All\\" onClick=\\"window.location=(\'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}special=replyall&amp;popid=$popid&amp;msgid=$msgid\')\\" />&nbsp;&nbsp;&nbsp;
		<input type=\\"button\\" class=\\"bginput\\" value=\\"Forward\\" onClick=\\"window.location=(\'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}special=forward&amp;popid=$popid&amp;msgid=$msgid\')\\" />&nbsp;&nbsp;&nbsp;
		<input style=\\"width: 170px;\\" type=\\"button\\" class=\\"bginput\\" value=\\"Forward as Attachment\\" onClick=\\"window.location=(\'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}special=forward&amp;attach=1&amp;popid=$popid&amp;msgid=$msgid\')\\" /></td>
        <td align=\\"right\\">&nbsp;</td>
      </tr>
    </table></td>
</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'read_renamesubject' => 
  array (
    'templategroupid' => '6',
    'user_data' => '$skin[doctype]
<html>
<head>
<title>$appname: Rename Message Subject</title>
$css
<script language="Javascript">
<!--
self.focus();
// -->
</script>
</head>
<body style="background-color: #C7E1F4;">

$header

<form name="renameform" action="read.rename.php" method="post">
<input type="hidden" name="messageid" value="$messageid" />
<input type="hidden" name="cmd" value="update" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Rename Message</b></span></th>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="10%" nowrap="nowrap"><span class="normalfont">New subject:</span></td>
	<td class="highRightCell"><input type="text" name="subject" value="$mail[subject]" class="bginput" size="35" /></td>
</tr>
</table>

<br />

<div align="center">
<input type="submit" class="bginput" value="Rename" />&nbsp;&nbsp;<input type="button" class="bginput" value="Cancel" onClick="window.close();" />
</div>
</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head>
<title>$appname: Rename Message Subject</title>
$GLOBALS[css]
<script language=\\"Javascript\\">
<!--
self.focus();
// -->
</script>
</head>
<body style=\\"background-color: #C7E1F4;\\">

$GLOBALS[header]

<form name=\\"renameform\\" action=\\"read.rename.php{$GLOBALS[session_url]}\\" method=\\"post\\">
<input type=\\"hidden\\" name=\\"messageid\\" value=\\"$messageid\\" />
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Rename Message</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"10%\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">New subject:</span></td>
	<td class=\\"highRightCell\\"><input type=\\"text\\" name=\\"subject\\" value=\\"$mail[subject]\\" class=\\"bginput\\" size=\\"35\\" /></td>
</tr>
</table>

<br />

<div align=\\"center\\">
<input type=\\"submit\\" class=\\"bginput\\" value=\\"Rename\\" />&nbsp;&nbsp;<input type=\\"button\\" class=\\"bginput\\" value=\\"Cancel\\" onClick=\\"window.close();\\" />
</div>
</form>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'read_source' => 
  array (
    'templategroupid' => '6',
    'user_data' => '$skin[doctype]
<html>
<head>
<title>$appname: Message Source</title>
$css
</head>
<body style="background-color: #C7E1F4;">

$header

<pre>$source</pre>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head>
<title>$appname: Message Source</title>
$GLOBALS[css]
</head>
<body style=\\"background-color: #C7E1F4;\\">

$GLOBALS[header]

<pre>$source</pre>

$GLOBALS[footer]

</body>
</html>"',
  ),
  'redirect_eventsaved' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The event information has been saved. You will now be taken back to your calendar.',
    'parsed_data' => '"The event information has been saved. You will now be taken back to your calendar."',
  ),
  'redirect_folrearrange' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The folders have been rearranged.',
    'parsed_data' => '"The folders have been rearranged."',
  ),
  'redirect_reported' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Thank you for reporting this message. The operators will review your report and take appropriate action.',
    'parsed_data' => '"Thank you for reporting this message. The operators will review your report and take appropriate action."',
  ),
  'redirect_sending_goback' => 
  array (
    'templategroupid' => '8',
    'user_data' => '&nbsp;Please <a href="compose.email.php?draftid=$draftid">click here</a> to go back to the composing screen.',
    'parsed_data' => '"&nbsp;Please <a href=\\"compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}draftid=$draftid\\">click here</a> to go back to the composing screen."',
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
<input type="hidden" name="ruleid" value="0" />

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
		<!--<input type="submit" class="bginput" name="submit" value="Apply All Rules" onClick="this.form.action = \'rules.apply.php\';" $disablesavechanges />-->
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
	<input type="checkbox" name="dowhat[0][flag]" value="1" /> flag it.<br />
	<input type="checkbox" name="dowhat[0][notify]" value="1" /> notify of it.</span></td>
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
<input type=\\"hidden\\" name=\\"ruleid\\" value=\\"0\\" />

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
		<!--<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Apply All Rules\\" onClick=\\"this.form.action = \'rules.apply.php{$GLOBALS[session_url]}\';\\" $disablesavechanges />-->
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
	<input type=\\"checkbox\\" name=\\"dowhat[0][flag]\\" value=\\"1\\" /> flag it.<br />
	<input type=\\"checkbox\\" name=\\"dowhat[0][notify]\\" value=\\"1\\" /> notify of it.</span></td>
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
	<input type="checkbox" name="dowhat[$rule[ruleid]][flag]" value="1" $flagchecked /> flag it.<br />
	<input type="checkbox" name="dowhat[$rule[ruleid]][notify]" value="1" $notifychecked /> notify of it.</span></td>
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
	<input type=\\"checkbox\\" name=\\"dowhat[$rule[ruleid]][flag]\\" value=\\"1\\" $flagchecked /> flag it.<br />
	<input type=\\"checkbox\\" name=\\"dowhat[$rule[ruleid]][notify]\\" value=\\"1\\" $notifychecked /> notify of it.</span></td>
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
<script language="JavaScript">
<!--

// This will define subFolders
$subFolders
function autoSelect(field, newSelect) {
	var toSelect = \'0\';
	newSelect = parseInt(newSelect);
	if (typeof subFolders[newSelect] == \'undefined\') {
		return toSelect;
	}
	var folders = subFolders[newSelect];
	for (var i = 0; i < folders.length; i++) {
		toSelect += \',\' + folders[i]; // + \',\' + autoSelect(field, folders[i]);
		alert(folders[i]);
	}
	alert(toSelect);
	return toSelect;
}

// -->
</script>
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
	<b>Advanced query:</b> Use double quotes to denote a phrase ("Dear John", for example).<br />
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
		<td width="33%" valign="top" style="padding: 12px; padding-top: 0px;">

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Search Folders...</b></span></th>
</tr>
<tr class="highRow" style="height: 125px;">
	<td class="highBothCell" valign="top"><span class="smallfont">
	<select name="folderids[]" multiple="multiple" size="$selectsize" onChange="if (this.form.dosubs.checked) autoSelect(this, this.options[this.selectedIndex].value);">
			<option value="0" <%if !$gotFolder %>selected="selected"<%endif%>>All folders</option>
			<option value="-">---------------------</option>
$folderjump
		</select>
	</span></td>
</tr>
</table>

		</td>
		<td width="34%" valign="top" style="padding: 12px; padding-top: 0px;">

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
		<td width="33%" valign="top" style="padding: 12px; padding-top: 0px;">

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
<script language=\\"JavaScript\\">
<!--

// This will define subFolders
$subFolders
function autoSelect(field, newSelect) {
	var toSelect = \'0\';
	newSelect = parseInt(newSelect);
	if (typeof subFolders[newSelect] == \'undefined\') {
		return toSelect;
	}
	var folders = subFolders[newSelect];
	for (var i = 0; i < folders.length; i++) {
		toSelect += \',\' + folders[i]; // + \',\' + autoSelect(field, folders[i]);
		alert(folders[i]);
	}
	alert(toSelect);
	return toSelect;
}

// -->
</script>
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
	<b>Advanced query:</b> Use double quotes to denote a phrase (\\"Dear John\\", for example).<br />
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
		<td width=\\"33%\\" valign=\\"top\\" style=\\"padding: 12px; padding-top: 0px;\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Search Folders...</b></span></th>
</tr>
<tr class=\\"highRow\\" style=\\"height: 125px;\\">
	<td class=\\"highBothCell\\" valign=\\"top\\"><span class=\\"smallfont\\">
	<select name=\\"folderids[]\\" multiple=\\"multiple\\" size=\\"$selectsize\\" onChange=\\"if (this.form.dosubs.checked) autoSelect(this, this.options[this.selectedIndex].value);\\">
			<option value=\\"0\\" ".((!$gotFolder ) ? ("selected=\\"selected\\"") : (\'\')).">All folders</option>
			<option value=\\"-\\">---------------------</option>
$folderjump
		</select>
	</span></td>
</tr>
</table>

		</td>
		<td width=\\"34%\\" valign=\\"top\\" style=\\"padding: 12px; padding-top: 0px;\\">

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
		<td width=\\"33%\\" valign=\\"top\\" style=\\"padding: 12px; padding-top: 0px;\\">

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
		new ContextItem(\'Rename Subject...\', function(){ window.open(\'read.rename.php?messageid=\'+msgID,\'renameSubject\',\'resizable=no,width=360,height=175\'); }),
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
$colheaders	<th class="headerCell" nowrap="nowrap"><span class="headerText"><b><a href="search.results.php?searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=folderid"><span class="normalfonttablehead">Folder</b></span>$sortimages[folderid]</a></span></th>
	<th class="headerRightCell"><input name="allbox" type="checkbox" value="Check All" title="Select/Deselect All" onClick="checkAll(this.form); changeButtonsStatus(!this.checked); if (this.checked) makeRows(\'first\'); else makeRows(\'second\');" /></th>
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
		new ContextItem(\'Rename Subject...\', function(){ window.open(\'read.rename.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=\'+msgID,\'renameSubject\',\'resizable=no,width=360,height=175\'); }),
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
$colheaders	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><b><a href=\\"search.results.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=folderid\\"><span class=\\"normalfonttablehead\\">Folder</b></span>$sortimages[folderid]</a></span></th>
	<th class=\\"headerRightCell\\"><input name=\\"allbox\\" type=\\"checkbox\\" value=\\"Check All\\" title=\\"Select/Deselect All\\" onClick=\\"checkAll(this.form); changeButtonsStatus(!this.checked); if (this.checked) makeRows(\'first\'); else makeRows(\'second\');\\" /></th>
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
<tr class="$afterpass[second]Row">
	<td class="$afterpass[second]LeftCell" width="60%" valign="top"><span class="normalfont"><b>Secret answer:</b></span></td>
	<td class="$afterpass[second]RightCell" width="40%"><input type="password" class="bginput" name="answer" value="$answer" size="40" /></td>
</tr>
<tr class="$afterpass[first]Row">
	<td class="$afterpass[first]LeftCell" width="60%" valign="top"><span class="normalfont"><b>Retype secret answer:</b></span><br /><span class="smallfont">Repeat the secret answer to verify it\'s correct.</span></td>
	<td class="$afterpass[first]RightCell" width="40%"><input type="password" class="bginput" name="answer_repeat" value="$answer_repeat" size="40" /></td>
</tr>
<%if getop(\'regcodecheck\') %>
<tr class="$afterpass[second]Row">
	<td class="$afterpass[second]LeftCell" width="60%" valign="top"><span class="normalfont"><%if $badcode %><span class="important"><%endif%><b>Registration code:</b><%if $badcode %></span><%endif%></span><br /><span class="smallfont">Please enter the numbers as they appear in the image to the right. If you cannot identify the numbers, make a guess - if the code you enter is incorrect, a new one will be created when the page is loaded again.<br />
	This measure helps us prevent automated registrations and give you a better service.</span></td>
	<td class="$afterpass[second]RightCell" width="40%">
		<table cellpadding="0" cellspacing="0" align="center"><tr><td style="border-style: solid; border-width: 1px; border-color: black;"><img src="user.signup.php?cmd=image&pos=1&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=2&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=3&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=4&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=5&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=6&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=7&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=8&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=9&x=$timenow" border="0" alt="" /></td></tr></table>
		<br /><input type="text" class="bginput" name="userregcode" value="$regcodevalue" size="40" /></td>
</tr>
<%endif%>
<%if $requirealt %>
<tr class="$afterpass[first]Row">
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
<tr class=\\"$afterpass[second]Row\\">
	<td class=\\"$afterpass[second]LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secret answer:</b></span></td>
	<td class=\\"$afterpass[second]RightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"answer\\" value=\\"$answer\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"$afterpass[first]Row\\">
	<td class=\\"$afterpass[first]LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Retype secret answer:</b></span><br /><span class=\\"smallfont\\">Repeat the secret answer to verify it\'s correct.</span></td>
	<td class=\\"$afterpass[first]RightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"answer_repeat\\" value=\\"$answer_repeat\\" size=\\"40\\" /></td>
</tr>
".((getop(\'regcodecheck\') ) ? ("
<tr class=\\"$afterpass[second]Row\\">
	<td class=\\"$afterpass[second]LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\">".(($badcode ) ? ("<span class=\\"important\\">") : (\'\'))."<b>Registration code:</b>".(($badcode ) ? ("</span>") : (\'\'))."</span><br /><span class=\\"smallfont\\">Please enter the numbers as they appear in the image to the right. If you cannot identify the numbers, make a guess - if the code you enter is incorrect, a new one will be created when the page is loaded again.<br />
	This measure helps us prevent automated registrations and give you a better service.</span></td>
	<td class=\\"$afterpass[second]RightCell\\" width=\\"40%\\">
		<table cellpadding=\\"0\\" cellspacing=\\"0\\" align=\\"center\\"><tr><td style=\\"border-style: solid; border-width: 1px; border-color: black;\\"><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=1&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=2&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=3&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=4&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=5&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=6&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=7&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=8&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=9&x=$timenow\\" border=\\"0\\" alt=\\"\\" /></td></tr></table>
		<br /><input type=\\"text\\" class=\\"bginput\\" name=\\"userregcode\\" value=\\"$regcodevalue\\" size=\\"40\\" /></td>
</tr>
") : (\'\'))."
".(($requirealt ) ? ("
<tr class=\\"$afterpass[first]Row\\">
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
  'signup_activate_message' => 
  array (
    'templategroupid' => '14',
    'user_data' => 'Dear $auser[realname],

We have now activated your account at the $appname. To log in to the service, please visit this page:
$appurl/{<INDEX_FILE>}

Your account name is $auser[username]. Please don\'t forget that your password is case sensitive!

To edit your preferences at any time, please visit this page:
$appurl/options.menu.php

Thank you and enjoy the service,
$appname team',
    'parsed_data' => '"Dear $auser[realname],

We have now activated your account at the $appname. To log in to the service, please visit this page:
$appurl/".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}

Your account name is $auser[username]. Please don\'t forget that your password is case sensitive!

To edit your preferences at any time, please visit this page:
$appurl/options.menu.php{$GLOBALS[session_url]}

Thank you and enjoy the service,
$appname team"',
  ),
);

?>