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
// +-------------------------------------------------------------+

// ############################################################################
// Templates
$templates = array (
  'addbook_edit' => 
  array (
    'templategroupid' => '4',
    'user_data' => '$skin[doctype]
<html>
<head>
<title>$appname: Address Book - $contact[name]</title>
$css
<style type="text/css">
.defaultItem {
	color: #274EAD
}
.normalItem {
}
</style>
<script language="Javascript">
<!--

self.focus();

var emailDef = 0;
var phoneDef = 0;
var lastAdd = \'\';
var addressInfo = new Array();
$addressarray

// -->
</script>
<script type="text/javascript" src="misc/addressbook_edit.js"></script>
</head>
<body style="background-color: #C7E1F4;">

$header

<form action="addressbook.update.php" method="post" name="contactform" onSubmit="submitData(this.emailData, this.emails, \'emailDef\'); submitData(this.phoneData, this.phones, \'phoneDef\'); submitAddresses(this.addressData); return verifyData(this);">
<input type="hidden" name="cmd" value="update" />
<input type="hidden" name="contactid" value="$contact[contactid]" />
<input type="hidden" name="emailData" value="0" />
<input type="hidden" name="phoneData" value="0" />
<input type="hidden" name="addressData" value="0" />
<input type="hidden" name="sendAddress" value="" />
<input type="hidden" name="sendPhone" value="" />
<input type="hidden" name="typesEmail" value="" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="575">
	<tr class="headerRow">
		<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Personal Information</b></span></th>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}BothCell" colspan="2">
			<table width="100%" align="center">
				<tr>
					<td><span class="normalfont">First:</span></td>
					<td colspan="2"><input type="text" name="contact[nameinfo][first]" value="{$contact[nameinfo][first]}" class="bginput" style="width: 120px" /></td>
					<td><span class="normalfont">Middle:</span></td>
					<td><input type="text" name="contact[nameinfo][middle]" value="{$contact[nameinfo][middle]}" class="bginput" style="width: 120px" /></td>
					<td><span class="normalfont">Last:</span></td>
					<td><input type="text" name="contact[nameinfo][last]" value="{$contact[nameinfo][last]}" class="bginput" style="width: 120px" /></td>
				</tr>
				<tr>
					<td><span class="normalfont">Title:</span></td>
					<td><input type="text" name="contact[nameinfo][prefix]" value="{$contact[nameinfo][prefix]}" class="bginput" style="width: 60px" /></td>
					<td><span class="normalfont">Display:</span></td>
					<td colspan="2"><input type="text" name="contact[name]" id="display" class="bginput" value="{$contact[name]}" style="width: 175px" /></td>
					<td><span class="normalfont">Nickname:</span></td>
					<td><input type="text" name="contact[nameinfo][nickname]" value="{$contact[nameinfo][nickname]}" class="bginput" style="width: 120px" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}BothCell" colspan="2">
			<table width="575" align="center">
				<tr>
					<td nowrap="nowrap" width="15%"><span class="normalfont">Birthday:</span></td>
					<td nowrap="nowrap">
						<select name="contact[birthday][month]">
							<option value="0">(month)</option>
							$bmonthsel
						</select>
						<select name="contact[birthday][day]">
							<option value="0">(day)</option>
							$bdaysel
						</select>
						<select name="contact[birthday][year]">
							<option value="0">(year)</option>
							$byearsel
						</select>
					</td>
					<td nowrap="nowrap"><span class="normalfont">Time zone:</span></td>
					<td nowrap="nowrap">
						$timezone
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}BothCell" colspan="2">
			<table width="575" align="center">
				<tr>
					<td nowrap="nowrap" width="15%"><span class="normalfont">Web page:</span></td>
					<td><input type="text" name="contact[webpage]" id="webpage" value="$contact[webpage]" class="bginput" size="72" /> <input type="button" value="Go" onClick="gotoUrl(this.form.webpage.value);" class="bginput" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}BothCell" colspan="2">
			<table width="575" align="center">
				<tr>
					<td valign="top" nowrap="nowrap" width="15%"><span class="normalfont">Notes:</span></td>
					<td><textarea name="contact[notes]" cols="75" rows="3" style="overflow-y: visible; width: 475px">$contact[notes]</textarea></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2"><span class="smallfonttablehead">&nbsp;</span></td>
	</tr>
	<tr class="headerRow">
		<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Email Addresses</b></span></span></th>
	</tr>
	<tr class="highRow">
		<td class="highBothCell" colspan="2">
			<table width="575" align="center">
				<tr>
					<td>
						<select name="emails" size="7" style="width: 440px" onClick="autoSelect(this);" onChange="updateDisabled(this, \'Email\');" onDblClick="window.open(\'compose.email.php?email=\'+escape(this.form.display.value+\' <\'+this.form.emails.options[this.form.emails.selectedIndex].text+\'>\'));">
							$emailoptions
						</select>
					</td>
					<td style="width: 125px" valign="top">
						<input type="button" style="width: 100%" name="addEmail" value="Add Email" onClick="insertItem(this.form.emails, \'email address\', true); updateDisabled(this.form.emails, \'Email\');" class="bginput" /><br />
						<input type="button" style="width: 100%" name="setDefEmail" value="Set as Primary" onClick="changeDefault(this.form.emails, \'emailDef\');" disabled="disabled" class="bginput" /><br />
						<input type="button" style="width: 100%" name="editEmail" value="Edit Email" onClick="editItem(this.form.emails, \'email address\', true); updateDisabled(this.form.emails, \'Email\');" disabled="disabled" class="bginput" /><br />
						<input type="button" style="width: 100%" name="removeEmail" value="Remove Email" onClick="removeItem(this.form.emails, \'email address\', \'emailDef\'); updateDisabled(this.form.emails, \'Email\');" disabled="disabled" class="bginput" /><br />
						<input type="button" style="width: 100%" name="sendEmail" value="Send Email" onClick="window.open(\'compose.email.php?email=\'+escape(this.form.display.value+\' <\'+this.form.emails.options[this.form.emails.selectedIndex].text+\'>\'));" disabled="disabled" class="bginput" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<br />
<div align="center">
<input type="submit" class="bginput" value="Save Changes" />&nbsp;
<input type="button" class="bginput" value="Close Window" onClick="window.close();" />
</div>
<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="575">
	<tr class="headerRow">
		<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Contact Details</b></span></span></th>
	</tr>
	<tr class="highRow">
		<td class="highBothCell" colspan="2">
			<table width="575" align="center">
				<tr>
					<td valign="top" style="width: 265px">
						<input type="button" style="width: 100%" name="addAddress" value="Add Address" onClick="insertAddress(this.form); updateAddressDisabled(this.form.addresses);" class="bginput" /><br />
						<select name="addresses" size="6" style="width: 265px" onClick="autoSelect(this);" onChange="if (this.selectedIndex == -1) { unloadAddressData(this.form); } else { reloadAddressData(this.options[this.selectedIndex], this.form); } updateAddressDisabled(this);">
							$addressoptions
						</select><br />
						<input type="button" style="width: 100%" name="setDefAddress" value="Set as Primary" onClick="changeDefaultAddy(this.form.addresses);" disabled="disabled" class="bginput" /><br />
						<input type="button" style="width: 100%" name="removeAddress" value="Remove Address" onClick="removeAddy(this.form.addresses); updateAddressDisabled(this.form.addresses);" disabled="disabled" class="bginput" />
					</td>
					<td>
						<table cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td colspan="2"><input type="text" name="adrName" onChange="updateAddressInfo(this.form.addresses, 0, this.value);" value="Address Title" style="width: 100%" disabled="disabled" class="bginput" /></td>
							</tr>
							<tr>
								<td colspan="2"><textarea name="adrStreet" rows="4" onChange="updateAddressInfo(this.form.addresses, 1, this.value);" style="width: 99%" disabled="disabled" />Street Address</textarea></td>
							</tr>
							<tr>
								<td colspan="2"><input type="text" name="adrCity" onChange="updateAddressInfo(this.form.addresses, 2, this.value);" value="City" style="width: 100%" disabled="disabled" class="bginput" /></td>
							</tr>
							<tr>
								<td width="50%"><input type="text" name="adrState" onChange="updateAddressInfo(this.form.addresses, 3, this.value);" value="State/Province" style="width: 97%" disabled="disabled" class="bginput" /></td>
								<td align="right"><input type="text" name="adrZip" onChange="updateAddressInfo(this.form.addresses, 4, this.value);" value="Postal Code" style="width: 97%" disabled="disabled" class="bginput" /></td>
							</tr>
							<tr>
								<td colspan="2"><input type="text" name="adrCountry" onChange="updateAddressInfo(this.form.addresses, 5, this.value);" value="Country/Region" style="width: 100%" disabled="disabled" class="bginput" /></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br />
			<table width="575" align="center">
				<tr>
					<td>
						<select name="phones" size="7" style="width: 265px" onClick="autoSelect(this);" onChange="if (this.selectedIndex == -1) { unloadTypeSelection(this.form.phonetypes); } else { reloadTypeSelection(this.options[this.selectedIndex], this.form.phonetypes); } updateDisabled(this, \'Phone\');">
							$phoneoptions
						</select>
					</td>
					<td>
						<select multiple="multiple" name="phonetypes" id="typesPhone" disabled="disabled" size="7" style="width: 170px" onChange="updateTypeSelection(this.form.phones.options[this.form.phones.selectedIndex], this);">
							<option value="{<CONTACT_PHONE_HOME>}">Home phone</option>
							<option value="{<CONTACT_PHONE_VOICE>}">Voice messaging</option>
							<option value="{<CONTACT_PHONE_WORK>}">Work phone</option>
							<option value="{<CONTACT_PHONE_FAX>}">Fax number</option>
							<option value="{<CONTACT_PHONE_CELL>}">Cellular phone</option>
							<option value="{<CONTACT_PHONE_PAGER>}">Pager device</option>
						</select>
					</td>
					<td style="width: 125px" valign="top">
						<input type="button" style="width: 100%" name="addPhone" value="Add Phone" onClick="insertItem(this.form.phones, \'phone number\', false); updateDisabled(this.form.phones, \'Phone\');" class="bginput" /><br />
						<input type="button" style="width: 100%" name="setDefPhone" value="Set as Primary" onClick="changeDefault(this.form.phones, \'phoneDef\');" disabled="disabled" class="bginput" /><br />
						<input type="button" style="width: 100%" name="editPhone" value="Edit Phone" onClick="editItem(this.form.phones, \'phone number\', false); updateDisabled(this.form.phones, \'Phone\');" disabled="disabled" class="bginput" /><br />
						<input type="button" style="width: 100%" name="removePhone" value="Remove Phone" onClick="removeItem(this.form.phones, \'phone number\', \'phoneDef\'); updateDisabled(this.form.phones, \'Phone\');" disabled="disabled" class="bginput" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<br />
<div align="center">
<input type="submit" class="bginput" value="Save Changes" />&nbsp;
<input type="button" class="bginput" value="Close Window" onClick="window.close();" />
</div>
</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head>
<title>$appname: Address Book - $contact[name]</title>
$GLOBALS[css]
<style type=\\"text/css\\">
.defaultItem {
	color: #274EAD
}
.normalItem {
}
</style>
<script language=\\"Javascript\\">
<!--

self.focus();

var emailDef = 0;
var phoneDef = 0;
var lastAdd = \'\';
var addressInfo = new Array();
$addressarray

// -->
</script>
<script type=\\"text/javascript\\" src=\\"misc/addressbook_edit.js\\"></script>
</head>
<body style=\\"background-color: #C7E1F4;\\">

$GLOBALS[header]

<form action=\\"addressbook.update.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"contactform\\" onSubmit=\\"submitData(this.emailData, this.emails, \'emailDef\'); submitData(this.phoneData, this.phones, \'phoneDef\'); submitAddresses(this.addressData); return verifyData(this);\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />
<input type=\\"hidden\\" name=\\"contactid\\" value=\\"$contact[contactid]\\" />
<input type=\\"hidden\\" name=\\"emailData\\" value=\\"0\\" />
<input type=\\"hidden\\" name=\\"phoneData\\" value=\\"0\\" />
<input type=\\"hidden\\" name=\\"addressData\\" value=\\"0\\" />
<input type=\\"hidden\\" name=\\"sendAddress\\" value=\\"\\" />
<input type=\\"hidden\\" name=\\"sendPhone\\" value=\\"\\" />
<input type=\\"hidden\\" name=\\"typesEmail\\" value=\\"\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"575\\">
	<tr class=\\"headerRow\\">
		<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Personal Information</b></span></th>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}BothCell\\" colspan=\\"2\\">
			<table width=\\"100%\\" align=\\"center\\">
				<tr>
					<td><span class=\\"normalfont\\">First:</span></td>
					<td colspan=\\"2\\"><input type=\\"text\\" name=\\"contact[nameinfo][first]\\" value=\\"{$contact[nameinfo][first]}\\" class=\\"bginput\\" style=\\"width: 120px\\" /></td>
					<td><span class=\\"normalfont\\">Middle:</span></td>
					<td><input type=\\"text\\" name=\\"contact[nameinfo][middle]\\" value=\\"{$contact[nameinfo][middle]}\\" class=\\"bginput\\" style=\\"width: 120px\\" /></td>
					<td><span class=\\"normalfont\\">Last:</span></td>
					<td><input type=\\"text\\" name=\\"contact[nameinfo][last]\\" value=\\"{$contact[nameinfo][last]}\\" class=\\"bginput\\" style=\\"width: 120px\\" /></td>
				</tr>
				<tr>
					<td><span class=\\"normalfont\\">Title:</span></td>
					<td><input type=\\"text\\" name=\\"contact[nameinfo][prefix]\\" value=\\"{$contact[nameinfo][prefix]}\\" class=\\"bginput\\" style=\\"width: 60px\\" /></td>
					<td><span class=\\"normalfont\\">Display:</span></td>
					<td colspan=\\"2\\"><input type=\\"text\\" name=\\"contact[name]\\" id=\\"display\\" class=\\"bginput\\" value=\\"{$contact[name]}\\" style=\\"width: 175px\\" /></td>
					<td><span class=\\"normalfont\\">Nickname:</span></td>
					<td><input type=\\"text\\" name=\\"contact[nameinfo][nickname]\\" value=\\"{$contact[nameinfo][nickname]}\\" class=\\"bginput\\" style=\\"width: 120px\\" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}BothCell\\" colspan=\\"2\\">
			<table width=\\"575\\" align=\\"center\\">
				<tr>
					<td nowrap=\\"nowrap\\" width=\\"15%\\"><span class=\\"normalfont\\">Birthday:</span></td>
					<td nowrap=\\"nowrap\\">
						<select name=\\"contact[birthday][month]\\">
							<option value=\\"0\\">(month)</option>
							$bmonthsel
						</select>
						<select name=\\"contact[birthday][day]\\">
							<option value=\\"0\\">(day)</option>
							$bdaysel
						</select>
						<select name=\\"contact[birthday][year]\\">
							<option value=\\"0\\">(year)</option>
							$byearsel
						</select>
					</td>
					<td nowrap=\\"nowrap\\"><span class=\\"normalfont\\">Time zone:</span></td>
					<td nowrap=\\"nowrap\\">
						$timezone
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}BothCell\\" colspan=\\"2\\">
			<table width=\\"575\\" align=\\"center\\">
				<tr>
					<td nowrap=\\"nowrap\\" width=\\"15%\\"><span class=\\"normalfont\\">Web page:</span></td>
					<td><input type=\\"text\\" name=\\"contact[webpage]\\" id=\\"webpage\\" value=\\"$contact[webpage]\\" class=\\"bginput\\" size=\\"72\\" /> <input type=\\"button\\" value=\\"Go\\" onClick=\\"gotoUrl(this.form.webpage.value);\\" class=\\"bginput\\" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}BothCell\\" colspan=\\"2\\">
			<table width=\\"575\\" align=\\"center\\">
				<tr>
					<td valign=\\"top\\" nowrap=\\"nowrap\\" width=\\"15%\\"><span class=\\"normalfont\\">Notes:</span></td>
					<td><textarea name=\\"contact[notes]\\" cols=\\"75\\" rows=\\"3\\" style=\\"overflow-y: visible; width: 475px\\">$contact[notes]</textarea></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan=\\"2\\"><span class=\\"smallfonttablehead\\">&nbsp;</span></td>
	</tr>
	<tr class=\\"headerRow\\">
		<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Email Addresses</b></span></span></th>
	</tr>
	<tr class=\\"highRow\\">
		<td class=\\"highBothCell\\" colspan=\\"2\\">
			<table width=\\"575\\" align=\\"center\\">
				<tr>
					<td>
						<select name=\\"emails\\" size=\\"7\\" style=\\"width: 440px\\" onClick=\\"autoSelect(this);\\" onChange=\\"updateDisabled(this, \'Email\');\\" onDblClick=\\"window.open(\'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}email=\'+escape(this.form.display.value+\' <\'+this.form.emails.options[this.form.emails.selectedIndex].text+\'>\'));\\">
							$emailoptions
						</select>
					</td>
					<td style=\\"width: 125px\\" valign=\\"top\\">
						<input type=\\"button\\" style=\\"width: 100%\\" name=\\"addEmail\\" value=\\"Add Email\\" onClick=\\"insertItem(this.form.emails, \'email address\', true); updateDisabled(this.form.emails, \'Email\');\\" class=\\"bginput\\" /><br />
						<input type=\\"button\\" style=\\"width: 100%\\" name=\\"setDefEmail\\" value=\\"Set as Primary\\" onClick=\\"changeDefault(this.form.emails, \'emailDef\');\\" disabled=\\"disabled\\" class=\\"bginput\\" /><br />
						<input type=\\"button\\" style=\\"width: 100%\\" name=\\"editEmail\\" value=\\"Edit Email\\" onClick=\\"editItem(this.form.emails, \'email address\', true); updateDisabled(this.form.emails, \'Email\');\\" disabled=\\"disabled\\" class=\\"bginput\\" /><br />
						<input type=\\"button\\" style=\\"width: 100%\\" name=\\"removeEmail\\" value=\\"Remove Email\\" onClick=\\"removeItem(this.form.emails, \'email address\', \'emailDef\'); updateDisabled(this.form.emails, \'Email\');\\" disabled=\\"disabled\\" class=\\"bginput\\" /><br />
						<input type=\\"button\\" style=\\"width: 100%\\" name=\\"sendEmail\\" value=\\"Send Email\\" onClick=\\"window.open(\'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}email=\'+escape(this.form.display.value+\' <\'+this.form.emails.options[this.form.emails.selectedIndex].text+\'>\'));\\" disabled=\\"disabled\\" class=\\"bginput\\" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<br />
<div align=\\"center\\">
<input type=\\"submit\\" class=\\"bginput\\" value=\\"Save Changes\\" />&nbsp;
<input type=\\"button\\" class=\\"bginput\\" value=\\"Close Window\\" onClick=\\"window.close();\\" />
</div>
<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"575\\">
	<tr class=\\"headerRow\\">
		<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Contact Details</b></span></span></th>
	</tr>
	<tr class=\\"highRow\\">
		<td class=\\"highBothCell\\" colspan=\\"2\\">
			<table width=\\"575\\" align=\\"center\\">
				<tr>
					<td valign=\\"top\\" style=\\"width: 265px\\">
						<input type=\\"button\\" style=\\"width: 100%\\" name=\\"addAddress\\" value=\\"Add Address\\" onClick=\\"insertAddress(this.form); updateAddressDisabled(this.form.addresses);\\" class=\\"bginput\\" /><br />
						<select name=\\"addresses\\" size=\\"6\\" style=\\"width: 265px\\" onClick=\\"autoSelect(this);\\" onChange=\\"if (this.selectedIndex == -1) { unloadAddressData(this.form); } else { reloadAddressData(this.options[this.selectedIndex], this.form); } updateAddressDisabled(this);\\">
							$addressoptions
						</select><br />
						<input type=\\"button\\" style=\\"width: 100%\\" name=\\"setDefAddress\\" value=\\"Set as Primary\\" onClick=\\"changeDefaultAddy(this.form.addresses);\\" disabled=\\"disabled\\" class=\\"bginput\\" /><br />
						<input type=\\"button\\" style=\\"width: 100%\\" name=\\"removeAddress\\" value=\\"Remove Address\\" onClick=\\"removeAddy(this.form.addresses); updateAddressDisabled(this.form.addresses);\\" disabled=\\"disabled\\" class=\\"bginput\\" />
					</td>
					<td>
						<table cellpadding=\\"0\\" cellspacing=\\"0\\" width=\\"100%\\">
							<tr>
								<td colspan=\\"2\\"><input type=\\"text\\" name=\\"adrName\\" onChange=\\"updateAddressInfo(this.form.addresses, 0, this.value);\\" value=\\"Address Title\\" style=\\"width: 100%\\" disabled=\\"disabled\\" class=\\"bginput\\" /></td>
							</tr>
							<tr>
								<td colspan=\\"2\\"><textarea name=\\"adrStreet\\" rows=\\"4\\" onChange=\\"updateAddressInfo(this.form.addresses, 1, this.value);\\" style=\\"width: 99%\\" disabled=\\"disabled\\" />Street Address</textarea></td>
							</tr>
							<tr>
								<td colspan=\\"2\\"><input type=\\"text\\" name=\\"adrCity\\" onChange=\\"updateAddressInfo(this.form.addresses, 2, this.value);\\" value=\\"City\\" style=\\"width: 100%\\" disabled=\\"disabled\\" class=\\"bginput\\" /></td>
							</tr>
							<tr>
								<td width=\\"50%\\"><input type=\\"text\\" name=\\"adrState\\" onChange=\\"updateAddressInfo(this.form.addresses, 3, this.value);\\" value=\\"State/Province\\" style=\\"width: 97%\\" disabled=\\"disabled\\" class=\\"bginput\\" /></td>
								<td align=\\"right\\"><input type=\\"text\\" name=\\"adrZip\\" onChange=\\"updateAddressInfo(this.form.addresses, 4, this.value);\\" value=\\"Postal Code\\" style=\\"width: 97%\\" disabled=\\"disabled\\" class=\\"bginput\\" /></td>
							</tr>
							<tr>
								<td colspan=\\"2\\"><input type=\\"text\\" name=\\"adrCountry\\" onChange=\\"updateAddressInfo(this.form.addresses, 5, this.value);\\" value=\\"Country/Region\\" style=\\"width: 100%\\" disabled=\\"disabled\\" class=\\"bginput\\" /></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br />
			<table width=\\"575\\" align=\\"center\\">
				<tr>
					<td>
						<select name=\\"phones\\" size=\\"7\\" style=\\"width: 265px\\" onClick=\\"autoSelect(this);\\" onChange=\\"if (this.selectedIndex == -1) { unloadTypeSelection(this.form.phonetypes); } else { reloadTypeSelection(this.options[this.selectedIndex], this.form.phonetypes); } updateDisabled(this, \'Phone\');\\">
							$phoneoptions
						</select>
					</td>
					<td>
						<select multiple=\\"multiple\\" name=\\"phonetypes\\" id=\\"typesPhone\\" disabled=\\"disabled\\" size=\\"7\\" style=\\"width: 170px\\" onChange=\\"updateTypeSelection(this.form.phones.options[this.form.phones.selectedIndex], this);\\">
							<option value=\\"".(defined("CONTACT_PHONE_HOME") ? constant("CONTACT_PHONE_HOME") : "{<CONTACT_PHONE_HOME>}")."\\">Home phone</option>
							<option value=\\"".(defined("CONTACT_PHONE_VOICE") ? constant("CONTACT_PHONE_VOICE") : "{<CONTACT_PHONE_VOICE>}")."\\">Voice messaging</option>
							<option value=\\"".(defined("CONTACT_PHONE_WORK") ? constant("CONTACT_PHONE_WORK") : "{<CONTACT_PHONE_WORK>}")."\\">Work phone</option>
							<option value=\\"".(defined("CONTACT_PHONE_FAX") ? constant("CONTACT_PHONE_FAX") : "{<CONTACT_PHONE_FAX>}")."\\">Fax number</option>
							<option value=\\"".(defined("CONTACT_PHONE_CELL") ? constant("CONTACT_PHONE_CELL") : "{<CONTACT_PHONE_CELL>}")."\\">Cellular phone</option>
							<option value=\\"".(defined("CONTACT_PHONE_PAGER") ? constant("CONTACT_PHONE_PAGER") : "{<CONTACT_PHONE_PAGER>}")."\\">Pager device</option>
						</select>
					</td>
					<td style=\\"width: 125px\\" valign=\\"top\\">
						<input type=\\"button\\" style=\\"width: 100%\\" name=\\"addPhone\\" value=\\"Add Phone\\" onClick=\\"insertItem(this.form.phones, \'phone number\', false); updateDisabled(this.form.phones, \'Phone\');\\" class=\\"bginput\\" /><br />
						<input type=\\"button\\" style=\\"width: 100%\\" name=\\"setDefPhone\\" value=\\"Set as Primary\\" onClick=\\"changeDefault(this.form.phones, \'phoneDef\');\\" disabled=\\"disabled\\" class=\\"bginput\\" /><br />
						<input type=\\"button\\" style=\\"width: 100%\\" name=\\"editPhone\\" value=\\"Edit Phone\\" onClick=\\"editItem(this.form.phones, \'phone number\', false); updateDisabled(this.form.phones, \'Phone\');\\" disabled=\\"disabled\\" class=\\"bginput\\" /><br />
						<input type=\\"button\\" style=\\"width: 100%\\" name=\\"removePhone\\" value=\\"Remove Phone\\" onClick=\\"removeItem(this.form.phones, \'phone number\', \'phoneDef\'); updateDisabled(this.form.phones, \'Phone\');\\" disabled=\\"disabled\\" class=\\"bginput\\" />
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<br />
<div align=\\"center\\">
<input type=\\"submit\\" class=\\"bginput\\" value=\\"Save Changes\\" />&nbsp;
<input type=\\"button\\" class=\\"bginput\\" value=\\"Close Window\\" onClick=\\"window.close();\\" />
</div>
</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'addbook_export' => 
  array (
    'templategroupid' => '4',
    'user_data' => '$skin[doctype]
<html>
<head>
<title>$appname: Address Book - Export</title>
$css
<script language="Javascript">
<!--
self.focus();
// -->
</script>
</head>
<body style="background-color: #C7E1F4;">

$header

<form action="addressbook.view.php" method="post">
<input type="hidden" name="cmd" value="doexport" />
<input type="hidden" name="contacts" value="$contacts" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
	<tr class="headerRow">
		<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Export Contacts</b></span></th>
	</tr>
	<tr class="highRow">
		<td class="highBothCell" colspan="2"><span class="normalfont">You have selected $numcontacts contact(s) to export. Please select a form to export in:<br /><br />
		<div align="center">
			<select name="format">
				<%if $numcontacts == 1 %>
					<option value="csv">CSV file</option>
					<option value="vcard">vCard file</option>
				<%else%>
					<option value="vcard">vCard files</option>
				<%endif%>
			</select></div></span></td>
	</tr>
</table>

<br />
<div align="center">
<input type="submit" class="bginput" value="Export Contacts" />&nbsp;
<input type="button" class="bginput" value="Close Window" onClick="window.close();" />
</div>
</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head>
<title>$appname: Address Book - Export</title>
$GLOBALS[css]
<script language=\\"Javascript\\">
<!--
self.focus();
// -->
</script>
</head>
<body style=\\"background-color: #C7E1F4;\\">

$GLOBALS[header]

<form action=\\"addressbook.view.php{$GLOBALS[session_url]}\\" method=\\"post\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"doexport\\" />
<input type=\\"hidden\\" name=\\"contacts\\" value=\\"$contacts\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
	<tr class=\\"headerRow\\">
		<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Export Contacts</b></span></th>
	</tr>
	<tr class=\\"highRow\\">
		<td class=\\"highBothCell\\" colspan=\\"2\\"><span class=\\"normalfont\\">You have selected $numcontacts contact(s) to export. Please select a form to export in:<br /><br />
		<div align=\\"center\\">
			<select name=\\"format\\">
				".(($numcontacts == 1 ) ? ("
					<option value=\\"csv\\">CSV file</option>
					<option value=\\"vcard\\">vCard file</option>
				") : ("
					<option value=\\"vcard\\">vCard files</option>
				"))."
			</select></div></span></td>
	</tr>
</table>

<br />
<div align=\\"center\\">
<input type=\\"submit\\" class=\\"bginput\\" value=\\"Export Contacts\\" />&nbsp;
<input type=\\"button\\" class=\\"bginput\\" value=\\"Close Window\\" onClick=\\"window.close();\\" />
</div>
</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
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

function validateAddForm(theform) {
	var errors = \'\';
	if (theform.name.value.length < 1) {
		errors += \'You must provide a name\\n\';
	}
	if (theform.email.value.length < 1 || !theform.email.value.match(/([-a-zA-Z0-9!\\#$%&*+\\/=?^_`{|}~.]+@[a-zA-Z0-9]{1}[-.a-zA-Z0-9_]*\\.[a-zA-Z]{2,6})/i)) {
		errors += \'You must provide a valid e-mail address\\n\';
	}
	if (errors != \'\') {
		alert(\'Sorry, this address entry cannot be added:\\n\\n\'+errors);
	}
	if (errors == \'\') {
		popEdit(0);
		return true;
	} else {
		return false;
	}
}

function popEdit(contactID) {
	if (contactID == 0) {
		var hWnd = window.open("about:blank", "ContactAdd", "width=650,height=435,resizable=yes,scrollbars=yes");
	} else {
		var hWnd = window.open("addressbook.update.php?cmd=edit&contactid="+contactID, "ContactEdit", "width=650,height=435,resizable=yes,scrollbars=yes");
	}
	if ((document.window != null) && (!hWnd.opener)) {
		hWnd.opener = document.window;
	}
}

// -->
</script>
</head>
<body>
$header

<table cellpadding="4" cellspacing="0" class="normalTable" width="730">
<tr class="headerRow">
	<th class="headerLeftCell" width="3%" nowrap="nowrap"><a href="addressbook.view.php?cmd=$cmd&contactgroupid=$contactgroupid&name=$name&cond=$cond&email=$email&letter=&perpage=$perpage&sortby=$sortby&sortorder=$sortorder"><span class="normalfonttablehead">All</span></a></th>
	$letters
</tr>
</table>

<br />

<form action="addressbook.add.php" method="post" name="addform" target="ContactAdd" onSubmit="return validateAddForm(this);">
<input type="hidden" name="cmd" value="insert" />
<input type="hidden" name="gotoedit" value="1" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="730">
<tr class="headerRow">
	<th class="headerBothCell" colspan="3"><span class="normalfonttablehead"><b>Add New Contact</b></span></th>
</tr>
<tr class="normalRow">
	<td class="highBothCell">
		<table width="100%">
			<tr>
				<td nowrap="nowrap"><span class="normalfont">Contact\'s name:</span></td>
				<td nowrap="nowrap"><span class="normalfont">Contact\'s email:</span></td>
				<td nowrap="nowrap"><span class="normalfont">Place in group:</span></td>
				<td nowrap="nowrap"><span class="normalfont">&nbsp;</span></td>
			</tr>
			<tr>
				<td><input type="text" class="bginput" name="name" value="" size="30" /></td>
				<td><input type="text" class="bginput" name="email" value="" size="30" /></td>
				<td><select name="contactgroupid" style="width: 145px">
					<option value="0">-----------------------</option>
					$groupoptions_selected
				</select></td>
				<td nowrap="nowrap"><input type="submit" class="bginput" name="submit" value="Add Contact" /></td>
			</tr>
		</table>
	</td>
</tr>
</table>
</form>

<form action="addressbook.update.php" method="post" name="form" target="_self">
<input type="hidden" name="cmd" value="update" />
<input type="hidden" name="grouptitle" value="" />

<table cellpadding="0" border="0" cellspacing="1" width="100%" align="center">
	<tr>
		<td valign="top">
		
<table cellpadding="4" cellspacing="0" class="normalTable" width="190">
<tr class="headerRow">
	<th class="headerLeftCell" nowrap="nowrap"><span class="normalfonttablehead"><b>Contact Groups</b></span></th>
	<th class="headerRightCell"><input name="allboxgroup" type="checkbox" value="Check All" title="Select/Deselect All" onClick="checkAll(this.form, \'groupcheck\');" /></th>
</tr>
<tr class="{$allGroupClass}Row">
	<td class="{$allGroupClass}LeftCell" width="100%"><span class="normalfont"><a href="addressbook.view.php?cmd=$cmd&contactgroupid=0&name=$name&cond=$cond&email=$email&letter=$curletter&perpage=$perpage&sortby=$sortby&sortorder=$sortorder">All contacts</a> ($allcontacts)</span></td>
	<td class="{$allGroupClass}RightCell" align="center">&nbsp;</td>
</tr>
$groups
</table>
<table cellpadding="4" cellspacing="0" class="normalTable" width="190">
<tr>
	<td>
		<select name="dowhat" onChange="if (this.selectedIndex <= 1) { return false; } if (this.options[this.selectedIndex].value == \'delgroup\') { if (!confirm(\'Are you sure you want to remove the selected groups?\\n(Contacts in those groups will not be deleted from your address book)\')) { this.selectedIndex = 0; return false; } else { this.form.cmd.value = \'delgroup\'; } } else { this.form.grouptitle.value = prompt(\'Please enter a name for the new group:\', \'\'); if (this.form.grouptitle.value == \'\' || this.form.grouptitle.value == null) { return false; } else { this.form.cmd.value = \'addgroup\'; } } this.form.submit();">
			<option value="dowhat">Actions to perform...</option>
			<option value="--">---------------------</option>
			<option value="addgroup">Create new group</option>
			<option value="delgroup">Remove selected groups</option>
		</select>
	</td>
</tr>
<tr>
	<td><span class="smallfont">Showing contacts $limitlower to $limitupper of $totalcontacts<br />$pagenav</span></td>
</tr>
</table>

		</td>
		<td style="padding-left: 13px" width="100%" valign="top">

<table cellpadding="4" cellspacing="0" class="normalTable" width="525">
<tr class="headerRow">
	<th class="headerLeftCell" nowrap="nowrap"><span class="headerText"><a href="addressbook.view.php?cmd=$cmd&contactgroupid=$contactgroupid&name=$name&cond=$cond&email=$email&letter=$curletter&perpage=$perpage&sortby=name&sortorder=$newsortorder"><span class="normalfonttablehead"><b>Name</b></span>$sortimages[name]</a></span> <span class="smallfonttablehead">(click to edit)</span></th>
	<th class="headerCell" nowrap="nowrap"><span class="headerText"><a href="addressbook.view.php?cmd=$cmd&contactgroupid=$contactgroupid&name=$name&cond=$cond&email=$email&letter=$curletter&perpage=$perpage&sortby=email&sortorder=$newsortorder"><span class="normalfonttablehead"><b>Email</b></span>$sortimages[email]</a></span> <span class="smallfonttablehead">(click to email)</span></th>
	<th class="headerRightCell"><input name="allbox" type="checkbox" value="Check All" title="Select/Deselect All" onClick="checkAll(this.form, \'contactcheck\');" /></th>
</tr>
<%if empty($contacts) %>
<tr align="center" class="highRow">
	<td align="center" colspan="3" class="normalBothCell"><span class="normalfont"><%if !empty($sqlwhere) %>No contacts match criteria.<%elseif $contactgroupid != 0 %>No contacts in this group.<%else%>No contacts.<%endif%></span></td>
</tr>
<%else%>
$contacts
<%endif%>
</table>
<table cellpadding="4" cellspacing="0" class="normalTable" width="525">
<tr>
	<td align="left"><span class="smallfont">
		<select name="dowhat" onChange="if (this.selectedIndex <= 1) { return false; } if (this.options[this.selectedIndex].value == \'delete\') { if (!confirm(\'<%if $contactgroupid == 0 %>Are you sure you want to delete the selected contacts?<%else%>Are you sure you want to remove the selected contacts from this group?\\n(They will not be deleted from your address book)<%endif%>\')) { this.selectedIndex = 0; return false; } } this.form.cmd.value = this.options[this.selectedIndex].value; if (this.form.cmd.value == \'export\') { this.form.action = \'addressbook.view.php\'; this.form.target = \'exportaddbook\'; var tarWin = window.open(\'about:blank\', \'exportaddbook\', \'toolbar=no,menubar=no,scrollbars=yes,height=225,width=425,status=no\'); if (document.window != null && !tarWin.opener) { tarWin.opener = document.window; } } else { this.form.action = \'addressbook.update.php\'; this.form.target = \'_self\'; } this.selectedIndex = 0; this.form.submit();">
			<option value="dowhat">Actions to perform...</option>
			<option value="--">---------------------</option>
			<option value="email">Email selected contacts</option>
			<option value="export">Export selected contacts</option>
			<option value="delete">Delete selected contacts</option>
		</select>
		<%if $isthereglobal %><br />Contacts that are marked with * cannot be edited or deleted.<%endif%></span>
	</td>
	<td valign="top" align="right">
		&nbsp;
		<select name="copyto" onChange="if (this.selectedIndex <= 1) { return false; } this.form.cmd.value = \'copy\'; this.form.submit();">
			<option value="dowhat">Copy contacts to...</option>
			<option value="--">---------------------</option>
			$groupoptions
		</select>
	</td>
</tr>
</table>

		</td>
	</tr>
</table>
</form>

<table align="left" width="730">
	<tr>
		<td width="50%" style="padding: 0px; padding-right: 5px;">
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
						<tr>
							<td nowrap="nowrap" align="left" colspan="2"><span class="normalfont">Contacts from group:</span></td>
							<td><select name="contactgroupid" style="width: 165px">
								<option value="0">-----------------------</option>
								$groupoptions_selected
							</select></td>
						</tr>
					</table>
					<br />
					<br />
					<input type="submit" class="bginput" value="Perform Search" />
				</td>
			</tr>
			</table>
			</form>
		</td>
		<td width="50%" style="padding: 0px; padding-left: 5px;">
			<form enctype="multipart/form-data" action="addressbook.add.php" name="composeform" method="post">
			<input type="hidden" name="cmd" value="upload" />

			<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			<tr class="headerRow">
				<th class="headerBothCell"><span class="normalfonttablehead"><b>Import Contacts</b></span></th>
			</tr>
			<tr class="highRow">
				<td class="highBothCell"><span class="smallfont">Through here you can upload a CSV file, a vCard file or a ZIP archive with multiple vCards in it.<br /><br />
				<input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
				<table cellspacing="0">
					<tr>
						<td><span class="smallfont">File to upload:</span></td>
						<td>&nbsp;&nbsp;<input type="file" class="bginput" name="attachment" /></td>
					</tr>
					<tr>
						<td><span class="smallfont">File format:</span></td>
						<td>&nbsp;&nbsp;<select name="format"><option value="csv">Single CSV file</option><option value="vcard">Single vCard file</option><option value="vzip">Multiple vCards (ZIP)</option></select></td>
					</tr>
				</table>
				<p align="center"><input type="submit" class="bginput" name="submit" value="Import Contacts" /></p></td>
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

function validateAddForm(theform) {
	var errors = \'\';
	if (theform.name.value.length < 1) {
		errors += \'You must provide a name\\\\n\';
	}
	if (theform.email.value.length < 1 || !theform.email.value.match(/([-a-zA-Z0-9!\\\\#$%&*+\\\\/=?^_`{|}~.]+@[a-zA-Z0-9]{1}[-.a-zA-Z0-9_]*\\\\.[a-zA-Z]{2,6})/i)) {
		errors += \'You must provide a valid e-mail address\\\\n\';
	}
	if (errors != \'\') {
		alert(\'Sorry, this address entry cannot be added:\\\\n\\\\n\'+errors);
	}
	if (errors == \'\') {
		popEdit(0);
		return true;
	} else {
		return false;
	}
}

function popEdit(contactID) {
	if (contactID == 0) {
		var hWnd = window.open(\\"about:blank\\", \\"ContactAdd\\", \\"width=650,height=435,resizable=yes,scrollbars=yes\\");
	} else {
		var hWnd = window.open(\\"addressbook.update.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=edit&contactid=\\"+contactID, \\"ContactEdit\\", \\"width=650,height=435,resizable=yes,scrollbars=yes\\");
	}
	if ((document.window != null) && (!hWnd.opener)) {
		hWnd.opener = document.window;
	}
}

// -->
</script>
</head>
<body>
$GLOBALS[header]

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"730\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerLeftCell\\" width=\\"3%\\" nowrap=\\"nowrap\\"><a href=\\"addressbook.view.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=$cmd&contactgroupid=$contactgroupid&name=$name&cond=$cond&email=$email&letter=&perpage=$perpage&sortby=$sortby&sortorder=$sortorder\\"><span class=\\"normalfonttablehead\\">All</span></a></th>
	$letters
</tr>
</table>

<br />

<form action=\\"addressbook.add.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"addform\\" target=\\"ContactAdd\\" onSubmit=\\"return validateAddForm(this);\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"insert\\" />
<input type=\\"hidden\\" name=\\"gotoedit\\" value=\\"1\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"730\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"3\\"><span class=\\"normalfonttablehead\\"><b>Add New Contact</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"highBothCell\\">
		<table width=\\"100%\\">
			<tr>
				<td nowrap=\\"nowrap\\"><span class=\\"normalfont\\">Contact\'s name:</span></td>
				<td nowrap=\\"nowrap\\"><span class=\\"normalfont\\">Contact\'s email:</span></td>
				<td nowrap=\\"nowrap\\"><span class=\\"normalfont\\">Place in group:</span></td>
				<td nowrap=\\"nowrap\\"><span class=\\"normalfont\\">&nbsp;</span></td>
			</tr>
			<tr>
				<td><input type=\\"text\\" class=\\"bginput\\" name=\\"name\\" value=\\"\\" size=\\"30\\" /></td>
				<td><input type=\\"text\\" class=\\"bginput\\" name=\\"email\\" value=\\"\\" size=\\"30\\" /></td>
				<td><select name=\\"contactgroupid\\" style=\\"width: 145px\\">
					<option value=\\"0\\">-----------------------</option>
					$groupoptions_selected
				</select></td>
				<td nowrap=\\"nowrap\\"><input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Add Contact\\" /></td>
			</tr>
		</table>
	</td>
</tr>
</table>
</form>

<form action=\\"addressbook.update.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\" target=\\"_self\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />
<input type=\\"hidden\\" name=\\"grouptitle\\" value=\\"\\" />

<table cellpadding=\\"0\\" border=\\"0\\" cellspacing=\\"1\\" width=\\"100%\\" align=\\"center\\">
	<tr>
		<td valign=\\"top\\">
		
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"190\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerLeftCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>Contact Groups</b></span></th>
	<th class=\\"headerRightCell\\"><input name=\\"allboxgroup\\" type=\\"checkbox\\" value=\\"Check All\\" title=\\"Select/Deselect All\\" onClick=\\"checkAll(this.form, \'groupcheck\');\\" /></th>
</tr>
<tr class=\\"{$allGroupClass}Row\\">
	<td class=\\"{$allGroupClass}LeftCell\\" width=\\"100%\\"><span class=\\"normalfont\\"><a href=\\"addressbook.view.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=$cmd&contactgroupid=0&name=$name&cond=$cond&email=$email&letter=$curletter&perpage=$perpage&sortby=$sortby&sortorder=$sortorder\\">All contacts</a> ($allcontacts)</span></td>
	<td class=\\"{$allGroupClass}RightCell\\" align=\\"center\\">&nbsp;</td>
</tr>
$groups
</table>
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"190\\">
<tr>
	<td>
		<select name=\\"dowhat\\" onChange=\\"if (this.selectedIndex <= 1) { return false; } if (this.options[this.selectedIndex].value == \'delgroup\') { if (!confirm(\'Are you sure you want to remove the selected groups?\\\\n(Contacts in those groups will not be deleted from your address book)\')) { this.selectedIndex = 0; return false; } else { this.form.cmd.value = \'delgroup\'; } } else { this.form.grouptitle.value = prompt(\'Please enter a name for the new group:\', \'\'); if (this.form.grouptitle.value == \'\' || this.form.grouptitle.value == null) { return false; } else { this.form.cmd.value = \'addgroup\'; } } this.form.submit();\\">
			<option value=\\"dowhat\\">Actions to perform...</option>
			<option value=\\"--\\">---------------------</option>
			<option value=\\"addgroup\\">Create new group</option>
			<option value=\\"delgroup\\">Remove selected groups</option>
		</select>
	</td>
</tr>
<tr>
	<td><span class=\\"smallfont\\">Showing contacts $limitlower to $limitupper of $totalcontacts<br />$pagenav</span></td>
</tr>
</table>

		</td>
		<td style=\\"padding-left: 13px\\" width=\\"100%\\" valign=\\"top\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"525\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerLeftCell\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><a href=\\"addressbook.view.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=$cmd&contactgroupid=$contactgroupid&name=$name&cond=$cond&email=$email&letter=$curletter&perpage=$perpage&sortby=name&sortorder=$newsortorder\\"><span class=\\"normalfonttablehead\\"><b>Name</b></span>$sortimages[name]</a></span> <span class=\\"smallfonttablehead\\">(click to edit)</span></th>
	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><a href=\\"addressbook.view.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=$cmd&contactgroupid=$contactgroupid&name=$name&cond=$cond&email=$email&letter=$curletter&perpage=$perpage&sortby=email&sortorder=$newsortorder\\"><span class=\\"normalfonttablehead\\"><b>Email</b></span>$sortimages[email]</a></span> <span class=\\"smallfonttablehead\\">(click to email)</span></th>
	<th class=\\"headerRightCell\\"><input name=\\"allbox\\" type=\\"checkbox\\" value=\\"Check All\\" title=\\"Select/Deselect All\\" onClick=\\"checkAll(this.form, \'contactcheck\');\\" /></th>
</tr>
".((empty($contacts) ) ? ("
<tr align=\\"center\\" class=\\"highRow\\">
	<td align=\\"center\\" colspan=\\"3\\" class=\\"normalBothCell\\"><span class=\\"normalfont\\">".((!empty($sqlwhere) ) ? ("No contacts match criteria.") : ((($contactgroupid != 0 ) ? ("No contacts in this group.") : ("No contacts."))))."</span></td>
</tr>
") : ("
$contacts
"))."
</table>
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"525\\">
<tr>
	<td align=\\"left\\"><span class=\\"smallfont\\">
		<select name=\\"dowhat\\" onChange=\\"if (this.selectedIndex <= 1) { return false; } if (this.options[this.selectedIndex].value == \'delete\') { if (!confirm(\'".(($contactgroupid == 0 ) ? ("Are you sure you want to delete the selected contacts?") : ("Are you sure you want to remove the selected contacts from this group?\\\\n(They will not be deleted from your address book)"))."\')) { this.selectedIndex = 0; return false; } } this.form.cmd.value = this.options[this.selectedIndex].value; if (this.form.cmd.value == \'export\') { this.form.action = \'addressbook.view.php{$GLOBALS[session_url]}\'; this.form.target = \'exportaddbook\'; var tarWin = window.open(\'about:blank\', \'exportaddbook\', \'toolbar=no,menubar=no,scrollbars=yes,height=225,width=425,status=no\'); if (document.window != null && !tarWin.opener) { tarWin.opener = document.window; } } else { this.form.action = \'addressbook.update.php{$GLOBALS[session_url]}\'; this.form.target = \'_self\'; } this.selectedIndex = 0; this.form.submit();\\">
			<option value=\\"dowhat\\">Actions to perform...</option>
			<option value=\\"--\\">---------------------</option>
			<option value=\\"email\\">Email selected contacts</option>
			<option value=\\"export\\">Export selected contacts</option>
			<option value=\\"delete\\">Delete selected contacts</option>
		</select>
		".(($isthereglobal ) ? ("<br />Contacts that are marked with * cannot be edited or deleted.") : (\'\'))."</span>
	</td>
	<td valign=\\"top\\" align=\\"right\\">
		&nbsp;
		<select name=\\"copyto\\" onChange=\\"if (this.selectedIndex <= 1) { return false; } this.form.cmd.value = \'copy\'; this.form.submit();\\">
			<option value=\\"dowhat\\">Copy contacts to...</option>
			<option value=\\"--\\">---------------------</option>
			$groupoptions
		</select>
	</td>
</tr>
</table>

		</td>
	</tr>
</table>
</form>

<table align=\\"left\\" width=\\"730\\">
	<tr>
		<td width=\\"50%\\" style=\\"padding: 0px; padding-right: 5px;\\">
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
						<tr>
							<td nowrap=\\"nowrap\\" align=\\"left\\" colspan=\\"2\\"><span class=\\"normalfont\\">Contacts from group:</span></td>
							<td><select name=\\"contactgroupid\\" style=\\"width: 165px\\">
								<option value=\\"0\\">-----------------------</option>
								$groupoptions_selected
							</select></td>
						</tr>
					</table>
					<br />
					<br />
					<input type=\\"submit\\" class=\\"bginput\\" value=\\"Perform Search\\" />
				</td>
			</tr>
			</table>
			</form>
		</td>
		<td width=\\"50%\\" style=\\"padding: 0px; padding-left: 5px;\\">
			<form enctype=\\"multipart/form-data\\" action=\\"addressbook.add.php{$GLOBALS[session_url]}\\" name=\\"composeform\\" method=\\"post\\">
			<input type=\\"hidden\\" name=\\"cmd\\" value=\\"upload\\" />

			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			<tr class=\\"headerRow\\">
				<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Import Contacts</b></span></th>
			</tr>
			<tr class=\\"highRow\\">
				<td class=\\"highBothCell\\"><span class=\\"smallfont\\">Through here you can upload a CSV file, a vCard file or a ZIP archive with multiple vCards in it.<br /><br />
				<input type=\\"hidden\\" name=\\"MAX_FILE_SIZE\\" value=\\"10485760\\" />
				<table cellspacing=\\"0\\">
					<tr>
						<td><span class=\\"smallfont\\">File to upload:</span></td>
						<td>&nbsp;&nbsp;<input type=\\"file\\" class=\\"bginput\\" name=\\"attachment\\" /></td>
					</tr>
					<tr>
						<td><span class=\\"smallfont\\">File format:</span></td>
						<td>&nbsp;&nbsp;<select name=\\"format\\"><option value=\\"csv\\">Single CSV file</option><option value=\\"vcard\\">Single vCard file</option><option value=\\"vzip\\">Multiple vCards (ZIP)</option></select></td>
					</tr>
				</table>
				<p align=\\"center\\"><input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Import Contacts\\" /></p></td>
			</tr>
			</table>
			</form>
		</td>
	</tr>
</table>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'addbook_main_entry' => 
  array (
    'templategroupid' => '4',
    'user_data' => '<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="45%"><span class="normalfont"><%if $addbook[userid] != 0 %><a href="addressbook.update.php?contactid=$addbook[contactid]" onClick="popEdit($addbook[contactid]); return false;">$addbook[name]</a><%else%>$addbook[name]<%endif%></span></td>
	<td class="{classname}Cell" width="45%"><span class="normalfont"><a href="compose.email.php?email=$addbook[link]">$addbook[email]</a></span></td>
	<td class="{classname}RightCell" align="center"><%if $addbook[userid] == 0 %>&nbsp;<%endif%><input type="checkbox" name="contactcheck[$addbook[contactid]]" value="yes" onClick="checkMain(this.form, \'contactcheck\');" /><%if $addbook[userid] == 0 %><span class="smallfont" title="This predefined contact cannot be modified.">*</span><%endif%></td>
</tr>
',
    'parsed_data' => '"<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"45%\\"><span class=\\"normalfont\\">".(($addbook[userid] != 0 ) ? ("<a href=\\"addressbook.update.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}contactid=$addbook[contactid]\\" onClick=\\"popEdit($addbook[contactid]); return false;\\">$addbook[name]</a>") : ("$addbook[name]"))."</span></td>
	<td class=\\"{classname}Cell\\" width=\\"45%\\"><span class=\\"normalfont\\"><a href=\\"compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}email=$addbook[link]\\">$addbook[email]</a></span></td>
	<td class=\\"{classname}RightCell\\" align=\\"center\\">".(($addbook[userid] == 0 ) ? ("&nbsp;") : (\'\'))."<input type=\\"checkbox\\" name=\\"contactcheck[$addbook[contactid]]\\" value=\\"yes\\" onClick=\\"checkMain(this.form, \'contactcheck\');\\" />".(($addbook[userid] == 0 ) ? ("<span class=\\"smallfont\\" title=\\"This predefined contact cannot be modified.\\">*</span>") : (\'\'))."</td>
</tr>
"',
    'upgraded' => '0',
  ),
  'addbook_main_groupbit' => 
  array (
    'templategroupid' => '4',
    'user_data' => '<tr class="{$class}Row">
	<td class="{$class}LeftCell" width="45%" nowrap="nowrap"><span class="normalfont"><a href="addressbook.view.php?cmd=$cmd&contactgroupid=$contactgroup[contactgroupid]&name=$name&cond=$cond&email=$email&letter=$curletter&perpage=$perpage&sortby=$sortby&sortorder=$sortorder">$contactgroup[title]</a> ($contactgroup[total])</span></td>
	<td class="{$class}RightCell" align="center"><input type="checkbox" name="groupcheck[$contactgroup[contactgroupid]]" value="yes" onClick="checkMain(this.form, \'groupcheck\', \'allboxgroup\');" /></td>
</tr>
',
    'parsed_data' => '"<tr class=\\"{$class}Row\\">
	<td class=\\"{$class}LeftCell\\" width=\\"45%\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\"><a href=\\"addressbook.view.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=$cmd&contactgroupid=$contactgroup[contactgroupid]&name=$name&cond=$cond&email=$email&letter=$curletter&perpage=$perpage&sortby=$sortby&sortorder=$sortorder\\">$contactgroup[title]</a> ($contactgroup[total])</span></td>
	<td class=\\"{$class}RightCell\\" align=\\"center\\"><input type=\\"checkbox\\" name=\\"groupcheck[$contactgroup[contactgroupid]]\\" value=\\"yes\\" onClick=\\"checkMain(this.form, \'groupcheck\', \'allboxgroup\');\\" /></td>
</tr>
"',
    'upgraded' => '0',
  ),
  'addbook_main_letterbit' => 
  array (
    'templategroupid' => '4',
    'user_data' => '<th class="header$whichCell" width="3%" nowrap="nowrap"><%if $curletter == $letter %><span class="normalfonttablehead">[$letter]</span><%else%><a href="addressbook.view.php?cmd=$cmd&contactgroupid=$contactgroupid&name=$name&cond=$cond&email=$email&letter=$encletter&perpage=$perpage&sortby=$sortby&sortorder=$sortorder"><span class="normalfonttablehead">$letter</span></a><%endif%></th>',
    'parsed_data' => '"<th class=\\"header$whichCell\\" width=\\"3%\\" nowrap=\\"nowrap\\">".(($curletter == $letter ) ? ("<span class=\\"normalfonttablehead\\">[$letter]</span>") : ("<a href=\\"addressbook.view.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=$cmd&contactgroupid=$contactgroupid&name=$name&cond=$cond&email=$email&letter=$encletter&perpage=$perpage&sortby=$sortby&sortorder=$sortorder\\"><span class=\\"normalfonttablehead\\">$letter</span></a>"))."</th>"',
    'upgraded' => '0',
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
$groupVars

<%if $local %>
var local = 1;
<%endif%>
<%if $newevent %>
var newevent = 1;
<%else%>
var newevent = 0;
<%endif%>
<%if $cmd2 %>
var cmd = \'$cmd2\';
<%endif%>

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
		<td rowspan="<%if !$onelistonly %>3<%else%>1<%endif%>" width="50%">
			<table cellpadding="2" cellspacing="0" border="0" width="100%">
				<tr>
					<td><span class="smallfont"><input type="button" value=" All " onClick="selectAll(this.form.contacts);" class="bginput" name="allbutton" />&nbsp;</span></td>
					<td width="100%">
						<select name="group" style="width: 100%" onChange="updateList(this.options[this.selectedIndex].value, this.form);">
							<option value="0">All contacts</option>
							$groupoptions
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<select name="contacts" style="width: 100%" multiple="multiple" size="<%if !$onelistonly %>19<%else%>10<%endif%>" onChange="updateDisabled(this.form, \'adds\');">
							$contacts
						</select>
					</td>
				</tr>
			</table>
		</td>
		<%if !$onelistonly %>
		<td>
			<input type="button" style="width: 55px;" value="To ->" onClick="addto(this.form, \'to\');" class="bginput" disabled="disabled" name="toto" /><br />
			<input type="button" style="width: 55px;" value="Delete" onClick="deleteAdds(this.form, \'to\');" class="bginput" disabled="disabled" name="deleteto" />
		</td>
		<td width="50%">
			<br /><span class="normalfont">To recipients:</span><br />
			<select name="tolist[]" style="width: 100%" id="to" multiple="multiple" size="5" onChange="updateDisabled(this.form, \'to\');">
			$to
			</select>
		</td>
		<%else%>
		<td>
			<input type="button" style="width: 55px;" value="Add" onClick="addto(this.form, \'to\');" class="bginput" disabled="disabled" name="toto" /><br />
			<input type="button" style="width: 55px;" value="Delete" onClick="deleteAdds(this.form, \'to\');" class="bginput" disabled="disabled" name="deleteto" />
		</td>
		<td width="50%">
			<br /><span class="normalfont">Selected contacts:</span><br />
			<select name="tolist[]" style="width: 100%" id="to" multiple="multiple" size="9" onChange="updateDisabled(this.form, \'to\');">
			$to
			</select>
		</td>
		<%endif%>
	</tr>
	<%if !$onelistonly %>
	<tr>
		<td>
			<input type="button" style="width: 55px;" value="Cc ->" onClick="addto(this.form, \'cc\');" class="bginput" disabled="disabled" name="tocc" /><br />
			<input type="button" style="width: 55px;" value="Delete" onClick="deleteAdds(this.form, \'cc\');" class="bginput" disabled="disabled" name="deletecc" />
		</td>
		<td width="50%">
			<span class="normalfont">CC recipients:</span><br />
			<select name="cclist[]" style="width: 100%" id="cc" multiple="multiple" size="5" onChange="updateDisabled(this.form, \'cc\');">
			$cc
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<input type="button" style="width: 55px;" value="Bcc ->" onClick="addto(this.form, \'bcc\');" class="bginput" disabled="disabled" name="tobcc" /><br />
			<input type="button" style="width: 55px;" value="Delete" onClick="deleteAdds(this.form, \'bcc\');" class="bginput" disabled="disabled" name="deletebcc" />
		</td>
		<td width="50%">
			<span class="normalfont">BCC recipients:</span><br />
			<select name="bcclist[]" style="width: 100%" id="bcc" multiple="multiple" size="5" onChange="updateDisabled(this.form, \'bcc\');">
			$bcc
			</select>
		</td>
	</tr>
	<%endif%>
	<tr>
		<td align="left">
			<table cellpadding="2" cellspacing="0" border="0" width="100%">
				<tr>
					<td width="100%"><input type="text" name="find" value="" onKeyUp="findContact(this, this.form.contacts);" class="bginput" style="padding: 2px; width: 100%" /></td>
					<td><span class="smallfont">&nbsp;<input type="button" value=" Find " onClick="findContact(this.form.find, this.form.contacts);" class="bginput" name="findbutton" /></span></td>
				</tr>
			</table>
		</td>
		<td align="left"><span class="normalfont">&nbsp;</span></td>
		<td align="right" style="padding: 2px"><select name="who" style="width: 100%"></select></td>
	</tr>
</table>

<br />
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
$groupVars

".(($local ) ? ("
var local = 1;
") : (\'\'))."
".(($newevent ) ? ("
var newevent = 1;
") : ("
var newevent = 0;
"))."
".(($cmd2 ) ? ("
var cmd = \'$cmd2\';
") : (\'\'))."

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
		<td rowspan=\\"".((!$onelistonly ) ? ("3") : ("1"))."\\" width=\\"50%\\">
			<table cellpadding=\\"2\\" cellspacing=\\"0\\" border=\\"0\\" width=\\"100%\\">
				<tr>
					<td><span class=\\"smallfont\\"><input type=\\"button\\" value=\\" All \\" onClick=\\"selectAll(this.form.contacts);\\" class=\\"bginput\\" name=\\"allbutton\\" />&nbsp;</span></td>
					<td width=\\"100%\\">
						<select name=\\"group\\" style=\\"width: 100%\\" onChange=\\"updateList(this.options[this.selectedIndex].value, this.form);\\">
							<option value=\\"0\\">All contacts</option>
							$groupoptions
						</select>
					</td>
				</tr>
				<tr>
					<td colspan=\\"2\\">
						<select name=\\"contacts\\" style=\\"width: 100%\\" multiple=\\"multiple\\" size=\\"".((!$onelistonly ) ? ("19") : ("10"))."\\" onChange=\\"updateDisabled(this.form, \'adds\');\\">
							$contacts
						</select>
					</td>
				</tr>
			</table>
		</td>
		".((!$onelistonly ) ? ("
		<td>
			<input type=\\"button\\" style=\\"width: 55px;\\" value=\\"To ->\\" onClick=\\"addto(this.form, \'to\');\\" class=\\"bginput\\" disabled=\\"disabled\\" name=\\"toto\\" /><br />
			<input type=\\"button\\" style=\\"width: 55px;\\" value=\\"Delete\\" onClick=\\"deleteAdds(this.form, \'to\');\\" class=\\"bginput\\" disabled=\\"disabled\\" name=\\"deleteto\\" />
		</td>
		<td width=\\"50%\\">
			<br /><span class=\\"normalfont\\">To recipients:</span><br />
			<select name=\\"tolist[]\\" style=\\"width: 100%\\" id=\\"to\\" multiple=\\"multiple\\" size=\\"5\\" onChange=\\"updateDisabled(this.form, \'to\');\\">
			$to
			</select>
		</td>
		") : ("
		<td>
			<input type=\\"button\\" style=\\"width: 55px;\\" value=\\"Add\\" onClick=\\"addto(this.form, \'to\');\\" class=\\"bginput\\" disabled=\\"disabled\\" name=\\"toto\\" /><br />
			<input type=\\"button\\" style=\\"width: 55px;\\" value=\\"Delete\\" onClick=\\"deleteAdds(this.form, \'to\');\\" class=\\"bginput\\" disabled=\\"disabled\\" name=\\"deleteto\\" />
		</td>
		<td width=\\"50%\\">
			<br /><span class=\\"normalfont\\">Selected contacts:</span><br />
			<select name=\\"tolist[]\\" style=\\"width: 100%\\" id=\\"to\\" multiple=\\"multiple\\" size=\\"9\\" onChange=\\"updateDisabled(this.form, \'to\');\\">
			$to
			</select>
		</td>
		"))."
	</tr>
	".((!$onelistonly ) ? ("
	<tr>
		<td>
			<input type=\\"button\\" style=\\"width: 55px;\\" value=\\"Cc ->\\" onClick=\\"addto(this.form, \'cc\');\\" class=\\"bginput\\" disabled=\\"disabled\\" name=\\"tocc\\" /><br />
			<input type=\\"button\\" style=\\"width: 55px;\\" value=\\"Delete\\" onClick=\\"deleteAdds(this.form, \'cc\');\\" class=\\"bginput\\" disabled=\\"disabled\\" name=\\"deletecc\\" />
		</td>
		<td width=\\"50%\\">
			<span class=\\"normalfont\\">CC recipients:</span><br />
			<select name=\\"cclist[]\\" style=\\"width: 100%\\" id=\\"cc\\" multiple=\\"multiple\\" size=\\"5\\" onChange=\\"updateDisabled(this.form, \'cc\');\\">
			$cc
			</select>
		</td>
	</tr>
	<tr>
		<td>
			<input type=\\"button\\" style=\\"width: 55px;\\" value=\\"Bcc ->\\" onClick=\\"addto(this.form, \'bcc\');\\" class=\\"bginput\\" disabled=\\"disabled\\" name=\\"tobcc\\" /><br />
			<input type=\\"button\\" style=\\"width: 55px;\\" value=\\"Delete\\" onClick=\\"deleteAdds(this.form, \'bcc\');\\" class=\\"bginput\\" disabled=\\"disabled\\" name=\\"deletebcc\\" />
		</td>
		<td width=\\"50%\\">
			<span class=\\"normalfont\\">BCC recipients:</span><br />
			<select name=\\"bcclist[]\\" style=\\"width: 100%\\" id=\\"bcc\\" multiple=\\"multiple\\" size=\\"5\\" onChange=\\"updateDisabled(this.form, \'bcc\');\\">
			$bcc
			</select>
		</td>
	</tr>
	") : (\'\'))."
	<tr>
		<td align=\\"left\\">
			<table cellpadding=\\"2\\" cellspacing=\\"0\\" border=\\"0\\" width=\\"100%\\">
				<tr>
					<td width=\\"100%\\"><input type=\\"text\\" name=\\"find\\" value=\\"\\" onKeyUp=\\"findContact(this, this.form.contacts);\\" class=\\"bginput\\" style=\\"padding: 2px; width: 100%\\" /></td>
					<td><span class=\\"smallfont\\">&nbsp;<input type=\\"button\\" value=\\" Find \\" onClick=\\"findContact(this.form.find, this.form.contacts);\\" class=\\"bginput\\" name=\\"findbutton\\" /></span></td>
				</tr>
			</table>
		</td>
		<td align=\\"left\\"><span class=\\"normalfont\\">&nbsp;</span></td>
		<td align=\\"right\\" style=\\"padding: 2px\\"><select name=\\"who\\" style=\\"width: 100%\\"></select></td>
	</tr>
</table>

<br />
<div align=\\"center\\">
<input type=\\"submit\\" class=\\"bginput\\" value=\\"OK\\" onClick=\\"extractList(this.form);\\" />&nbsp;&nbsp;
<input type=\\"button\\" class=\\"bginput\\" value=\\"Cancel\\" onClick=\\"window.close();\\" />
</div>
</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'autoresponse_subject' => 
  array (
    'templategroupid' => '12',
    'user_data' => '[Auto-response] ',
    'parsed_data' => '"[Auto-response] "',
    'upgraded' => '0',
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
				<tr class="highRow">
					<td class="highLeftCell" nowrap="nowrap" valign="top">
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
					<td class="highCell" nowrap="nowrap" valign="top">
						<table style="height: 40px;">
							<tr style="height: 50%;">
								<td nowrap="nowrap">
									<span class="normalfont">Event date:</span>
									&nbsp;<input type="text" name="fromdayname" class="highInactive" readonly="readonly" value="" size="12" />
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
					<td class="highRightCell" valign="top">
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
				<tr class=\\"highRow\\">
					<td class=\\"highLeftCell\\" nowrap=\\"nowrap\\" valign=\\"top\\">
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
					<td class=\\"highCell\\" nowrap=\\"nowrap\\" valign=\\"top\\">
						<table style=\\"height: 40px;\\">
							<tr style=\\"height: 50%;\\">
								<td nowrap=\\"nowrap\\">
									<span class=\\"normalfont\\">Event date:</span>
									&nbsp;<input type=\\"text\\" name=\\"fromdayname\\" class=\\"highInactive\\" readonly=\\"readonly\\" value=\\"\\" size=\\"12\\" />
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
					<td class=\\"highRightCell\\" valign=\\"top\\">
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
    'upgraded' => '0',
  ),
  'calendar_daily_emptycell' => 
  array (
    'templategroupid' => '18',
    'user_data' => '		<td class="{$class}{$type}Cell" width="$width_percent%" colspan="$colspan" $mouseJS>&nbsp;</td>
',
    'parsed_data' => '"		<td class=\\"{$class}{$type}Cell\\" width=\\"$width_percent%\\" colspan=\\"$colspan\\" $mouseJS>&nbsp;</td>
"',
    'upgraded' => '0',
  ),
  'calendar_daily_eventcell' => 
  array (
    'templategroupid' => '18',
    'user_data' => '		<td class="highRightCell" rowspan="$rowspan" colspan="1" width="$width_percent%" align="left" valign="top" nowrap="nowrap"><span class="normalfont"><nobr><a title="$event[title]" href="calendar.event.php?eventid=$event[eventid]">$event[shorttitle]</a></span><%if $rowspan > 1 %></nobr><br /><nobr><%else%>&nbsp;<%endif%><span class="smallfont"><%if $event[\'allday\'] %>All day<%else%>$event[from_time]-$event[to_time]<%endif%></nobr></span></td>
',
    'parsed_data' => '"		<td class=\\"highRightCell\\" rowspan=\\"$rowspan\\" colspan=\\"1\\" width=\\"$width_percent%\\" align=\\"left\\" valign=\\"top\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\"><nobr><a title=\\"$event[title]\\" href=\\"calendar.event.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}eventid=$event[eventid]\\">$event[shorttitle]</a></span>".(($rowspan > 1 ) ? ("</nobr><br /><nobr>") : ("&nbsp;"))."<span class=\\"smallfont\\">".(($event[\'allday\'] ) ? ("All day") : ("$event[from_time]-$event[to_time]"))."</nobr></span></td>
"',
    'upgraded' => '0',
  ),
  'calendar_daily_hour' => 
  array (
    'templategroupid' => '18',
    'user_data' => '		<td class="<%if intval($hour) == $hour %>high<%else%>normal<%endif%>BothCell" $mouseJS width="1%" align="right">&nbsp;$displayhour</td>
',
    'parsed_data' => '"		<td class=\\"".((intval($hour) == $hour ) ? ("high") : ("normal"))."BothCell\\" $mouseJS width=\\"1%\\" align=\\"right\\">&nbsp;$displayhour</td>
"',
    'upgraded' => '0',
  ),
  'calendar_event_forward' => 
  array (
    'templategroupid' => '18',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Forward Event</title>
$css
<script type="text/javascript" src="misc/common.js"></script>
<script language="JavaScript">
<!--

function popAddBook (local) {
	var url = "addressbook.view.php?cmd=mini";
	url += "&frompage=fwd&cmd2=forwardrel&local=1&pre[list]=" + escape(document.forms.eventform.eventlistaddresses.value);
	var hWnd = window.open(url,"AddBook","width=530,height=345,resizable=yes,scrollbars=yes,menubar=yes");
	if ((document.window != null) && (!hWnd.opener)) {
		hWnd.opener = document.window;
	}
}
// -->
</script>
</head>
<body>
$header

<form action="calendar.event.php" method="post" name="eventform">
<input type="hidden" name="cmd" value="dofwd" />
<input type="hidden" name="eventlistaddresses" value="$eventlistaddresses" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="700">
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top"><span class="normalfont"><%if $event[shareoptions] & CAL_SHARE_CANEDIT or $event[userid] == $hiveuser[userid] %><a href="#stayhere" onClick="popAddBook(1); return false;"><img src="$skin[images]/addbook.gif" alt="Address Book" border="0" /></a> <%endif%><b>Shared Event Userlist:</b> <%if $event[shareoptions] & CAL_SHARE_CANEDIT or $event[userid] == $hiveuser[userid] %><%help {To add users here, they must exist in your address book. Click the Address Book icon to the left to add users to this list.} %><%endif%></span></td>
	<td class="{classname}RightCell" width="70%" valign="top" align="right">
	$event[groupuserlist]
	<%if $addl > 0 %>
	<input type="text" size="76" readonly="readonly" class="{classname}Inactive" value="Plus $addl other users who chose to not be listed." />
	<%endif%>
	</td>
</tr>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="700">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="forwardevent" value="Forward Event to Selected Users" />
	</td>
</tr>
</table>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Forward Event</title>
$GLOBALS[css]
<script type=\\"text/javascript\\" src=\\"misc/common.js\\"></script>
<script language=\\"JavaScript\\">
<!--

function popAddBook (local) {
	var url = \\"addressbook.view.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=mini\\";
	url += \\"&frompage=fwd&cmd2=forwardrel&local=1&pre[list]=\\" + escape(document.forms.eventform.eventlistaddresses.value);
	var hWnd = window.open(url,\\"AddBook\\",\\"width=530,height=345,resizable=yes,scrollbars=yes,menubar=yes\\");
	if ((document.window != null) && (!hWnd.opener)) {
		hWnd.opener = document.window;
	}
}
// -->
</script>
</head>
<body>
$GLOBALS[header]

<form action=\\"calendar.event.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"eventform\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"dofwd\\" />
<input type=\\"hidden\\" name=\\"eventlistaddresses\\" value=\\"$eventlistaddresses\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\">
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\">".(($event[shareoptions] & CAL_SHARE_CANEDIT or $event[userid] == $hiveuser[userid] ) ? ("<a href=\\"#stayhere\\" onClick=\\"popAddBook(1); return false;\\"><img src=\\"{$GLOBALS[skin][images]}/addbook.gif\\" alt=\\"Address Book\\" border=\\"0\\" /></a> ") : (\'\'))."<b>Shared Event Userlist:</b> ".(($event[shareoptions] & CAL_SHARE_CANEDIT or $event[userid] == $hiveuser[userid] ) ? ("<a href=\\"#\\" onClick=\\"alert(\'To add users here, they must exist in your address book. Click the Address Book icon to the left to add users to this list.\'); return false;\\"><img src=\\"{$GLOBALS[skin][images]}/help.gif\\" border=\\"0\\" /></a>") : (\'\'))."</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\" valign=\\"top\\" align=\\"right\\">
	$event[groupuserlist]
	".(($addl > 0 ) ? ("
	<input type=\\"text\\" size=\\"76\\" readonly=\\"readonly\\" class=\\"{classname}Inactive\\" value=\\"Plus $addl other users who chose to not be listed.\\" />
	") : (\'\'))."
	</td>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"forwardevent\\" value=\\"Forward Event to Selected Users\\" />
	</td>
</tr>
</table>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'calendar_event_grouplistbit' => 
  array (
    'templategroupid' => '18',
    'user_data' => '<input type="text" name="eventuserlist" class="{classname}InactiveLink" readonly="readonly" size="76" value="$name <$username$domain>" onClick="window.location=\'compose.email.php?data[to]=$username$domain\'" style="cursor:pointer" onMouseOver="this.className=\'{classname}InactiveLinkHover\'; window.status=\'{$appurl}compose.email.php?data[to]=$username$domain\';" onMouseOut="this.className=\'{classname}InactiveLink\'; window.status=\'\';" title="Email $name" /><br />',
    'parsed_data' => '"<input type=\\"text\\" name=\\"eventuserlist\\" class=\\"{classname}InactiveLink\\" readonly=\\"readonly\\" size=\\"76\\" value=\\"$name <$username$domain>\\" onClick=\\"window.location=\'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}data[to]=$username$domain\'\\" style=\\"cursor:pointer\\" onMouseOver=\\"this.className=\'{classname}InactiveLinkHover\'; window.status=\'{$appurl}compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}data[to]=$username$domain\';\\" onMouseOut=\\"this.className=\'{classname}InactiveLink\'; window.status=\'\';\\" title=\\"Email $name\\" /><br />"',
    'upgraded' => '0',
  ),
  'calendar_event_read' => 
  array (
    'templategroupid' => '18',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: <%if $event[eventtype] == -2 %>View Shared Event<%elseif $event[eventtype] == -1 %>View Global Event<%endif%></title>
$css
<script type="text/javascript" src="misc/common.js"></script>
<script type="text/javascript" src="misc/checkall.js"></script>
<script type="text/javascript" src="misc/cookies.js"></script>
<script type="text/javascript" src="misc/collapse.js"></script>
<script language="JavaScript">
<!--
function popAddBook (local) {
	var url = "addressbook.view.php?cmd=mini";
	if (local == 1) {
		url += "&local=1&pre[list]=" + escape(document.forms.eventform.eventlistaddresses.value);
	} else {
		url += "&pre[list]=" + escape (document.forms.eventform.addresses.value);
	}
	var hWnd = window.open(url,"AddBook","width=530,height=345,resizable=yes,scrollbars=yes");
	if ((document.window != null) && (!hWnd.opener)) {
		hWnd.opener = document.window;
	}
}
var contacts = new Array();
// -->
</script>
<script type="text/javascript" src="misc/autocomplete.js"></script>
</head>
<body>
$header

<form name="eventform">
<table cellpadding="4" cellspacing="0" class="normalTable" width="700">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Event Information</b> <img src="images/arrow_up.gif" name="info" onClick="renderSect(new Array(\'eventinfo\'), \'info\');" border="0" style="cursor: pointer; vertical-align: middle;" /></span></th>
</tr>
</table>
<table cellpadding="4" cellspacing="0" class="normalTable" width="700" id="eventinfo">
<%if $event[eventtype] == -2 %>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top"><span class="normalfont"><b>Event Poster:</b></span></td>
	<td class="{classname}RightCell" width="70%" align="right"><input type="text" name="eventposter" class="{classname}InactiveLink" readonly="readonly" size="72" value="$event[poster]$domain" onMouseOver="this.className=\'{classname}InactiveLinkHover\'; window.status=\'{$appurl}compose.email.php?data[to]=$event[poster]$domain\';" onMouseOut="this.className=\'{classname}InactiveLink\'; window.status=\'\';" onClick="window.location=\'compose.email.php?data[to]=$event[poster]$domain\'" style="cursor:pointer;" /></td>
</tr>
<%endif%>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top"><span class="normalfont"><b>Event Title:</b></span></td>
	<td class="{classname}RightCell" width="70%" align="right"><input type="text" class="{classname}Inactive" readonly="readonly" style="cursor:default;" name="title" value="$event[title]" size="72" /></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}BothCell" colspan="2">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr valign="top">
				<td><textarea name="message" style="width: 686px; height: 180px;" wrap="virtual" id="tmessage" class="{classname}Inactive" readonly="readonly">$event[message]</textarea></td>
			</tr>
		</table>
	</td>
</tr>
<%if $event[eventtype] == -2 and ($event[shareoptions] & CAL_EVENT_CANLIST) %>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top"><span class="normalfont"><b>Shared Event Userlist:</b></span></td>
	<td class="{classname}RightCell" width="70%" valign="top" align="right">
	$event[groupuserlist]
	<%if $addl > 0 %>
	<input type="text" size="72" class="{classname}Inactive" readonly="readonly" value="Plus $addl other users who chose to not be listed." />
	<%endif%>
	</td>
</tr>
<%endif%>
</table>
<table cellpadding="4" cellspacing="0" class="normalTable" width="700">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Event Date and Time</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top"><span class="normalfont"><b>Event Date:</b></span></td>
	<td class="{classname}RightCell" width="70%" nowrap="nowrap"><span class="normalfont">
		<select name="frommonth" onChange="getDay(this.form, \'from\');">
			$frommonthsel
		</select>
		<select name="fromday" onChange="getDay(this.form, \'from\');">
			$fromdaysel
		</select>
		<select name="fromyear" onChange="getDay(this.form, \'from\');">
			$fromyearsel
		</select>
		<input type="text" name="fromdayname" class="{classname}Inactive" readonly="readonly" value="$fromdayname" size="12" />
		</span>
	</td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top"><span class="normalfont"><b>Event Time:</b></span></td>
	<td class="{classname}RightCell" width="70%">
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
					<%if !getop(\'cal_use24\') %>
					<select name="fromampm" $timedisabled>
						<option value="">$fromampmsel</option>
					</select>
					<%else%>
					<input type="hidden" name="fromampm" value="" />
					<%endif%>
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
					<input type="checkbox" name="allday" $alldaychecked id="allday" value="1" onClick="this.form.fromhour.disabled = this.form.fromminute.disabled = this.form.fromampm.disabled = this.form.durhours.disabled = this.form.durminutes.disabled = this.checked;" disabled="disabled" /> <label for="allday">All day event</label>
					</span>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
<%if $event[addresses] %>
<table cellpadding="4" cellspacing="0" class="normalTable" width="700">
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top"><span class="normalfont"><b>Related email addresses:</b></span></td>
	<td class="{classname}RightCell" width="70%" align="right"><input type="text" class="{classname}Inactive" readonly="readonly" name="addresses" id="addresses" value="$event[addresses]" size="72" /></td>
</tr>
</table>
<%endif%>
<table cellpadding="4" cellspacing="0" class="normalTable" width="700">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Special Notes</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}RightCell" width="100%" colspan="2" align="center">You do not have permission to modify this event.<br />If you have any questions, you should contact the event poster, <a href="compose.email.php?data[to]=$event[poster]$domain">$event[poster]$domain</a>.</td>
</tr>
</table>

<br />

<%if $event[eventtype] == -2 and ($event[canforward] or $event[shareoptions] & CAL_SHARE_CANLIST) %>
<table cellpadding="4" cellspacing="0" class="normalTable" width="700">
<tr>
	<td align="center">
		<%if $event[shareoptions] & CAL_SHARE_CANFWD %>
		<input type="submit" class="bginput" name="forwardevent" value="Forward Event" onClick="document.eventform.cmd.value=\'forward\'; return true;" />
		<%endif%>
		<%if $event[shareoptions] & CAL_SHARE_CANLIST %>
		<input type="submit" class="bginput" name="emaillist" value="Email Event Userlist" onClick="document.eventform.cmd.value=\'emailgroup\'; return true;" />
		<%endif%>
	</td>
</tr>
</table>
<%endif%>
</form>

<script language="JavaScript">
<!--
if (getCookie(\'eventinfo\') == \'closed\') {
	renderSect(new Array(\'eventinfo\'), \'info\');
}
// -->
</script>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: ".(($event[eventtype] == -2 ) ? ("View Shared Event") : ((($event[eventtype] == -1 ) ? ("View Global Event") : "")))."</title>
$GLOBALS[css]
<script type=\\"text/javascript\\" src=\\"misc/common.js\\"></script>
<script type=\\"text/javascript\\" src=\\"misc/checkall.js\\"></script>
<script type=\\"text/javascript\\" src=\\"misc/cookies.js\\"></script>
<script type=\\"text/javascript\\" src=\\"misc/collapse.js\\"></script>
<script language=\\"JavaScript\\">
<!--
function popAddBook (local) {
	var url = \\"addressbook.view.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=mini\\";
	if (local == 1) {
		url += \\"&local=1&pre[list]=\\" + escape(document.forms.eventform.eventlistaddresses.value);
	} else {
		url += \\"&pre[list]=\\" + escape (document.forms.eventform.addresses.value);
	}
	var hWnd = window.open(url,\\"AddBook\\",\\"width=530,height=345,resizable=yes,scrollbars=yes\\");
	if ((document.window != null) && (!hWnd.opener)) {
		hWnd.opener = document.window;
	}
}
var contacts = new Array();
// -->
</script>
<script type=\\"text/javascript\\" src=\\"misc/autocomplete.js\\"></script>
</head>
<body>
$GLOBALS[header]

<form name=\\"eventform\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Event Information</b> <img src=\\"images/arrow_up.gif\\" name=\\"info\\" onClick=\\"renderSect(new Array(\'eventinfo\'), \'info\');\\" border=\\"0\\" style=\\"cursor: pointer; vertical-align: middle;\\" /></span></th>
</tr>
</table>
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\" id=\\"eventinfo\\">
".(($event[eventtype] == -2 ) ? ("
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Event Poster:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\" align=\\"right\\"><input type=\\"text\\" name=\\"eventposter\\" class=\\"{classname}InactiveLink\\" readonly=\\"readonly\\" size=\\"72\\" value=\\"$event[poster]$domain\\" onMouseOver=\\"this.className=\'{classname}InactiveLinkHover\'; window.status=\'{$appurl}compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}data[to]=$event[poster]$domain\';\\" onMouseOut=\\"this.className=\'{classname}InactiveLink\'; window.status=\'\';\\" onClick=\\"window.location=\'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}data[to]=$event[poster]$domain\'\\" style=\\"cursor:pointer;\\" /></td>
</tr>
") : (\'\'))."
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Event Title:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\" align=\\"right\\"><input type=\\"text\\" class=\\"{classname}Inactive\\" readonly=\\"readonly\\" style=\\"cursor:default;\\" name=\\"title\\" value=\\"$event[title]\\" size=\\"72\\" /></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\" colspan=\\"2\\">
		<table cellpadding=\\"0\\" cellspacing=\\"0\\" border=\\"0\\">
			<tr valign=\\"top\\">
				<td><textarea name=\\"message\\" style=\\"width: 686px; height: 180px;\\" wrap=\\"virtual\\" id=\\"tmessage\\" class=\\"{classname}Inactive\\" readonly=\\"readonly\\">$event[message]</textarea></td>
			</tr>
		</table>
	</td>
</tr>
".(($event[eventtype] == -2 and ($event[shareoptions] & CAL_EVENT_CANLIST) ) ? ("
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Shared Event Userlist:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\" valign=\\"top\\" align=\\"right\\">
	$event[groupuserlist]
	".(($addl > 0 ) ? ("
	<input type=\\"text\\" size=\\"72\\" class=\\"{classname}Inactive\\" readonly=\\"readonly\\" value=\\"Plus $addl other users who chose to not be listed.\\" />
	") : (\'\'))."
	</td>
</tr>
") : (\'\'))."
</table>
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Event Date and Time</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Event Date:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">
		<select name=\\"frommonth\\" onChange=\\"getDay(this.form, \'from\');\\">
			$frommonthsel
		</select>
		<select name=\\"fromday\\" onChange=\\"getDay(this.form, \'from\');\\">
			$fromdaysel
		</select>
		<select name=\\"fromyear\\" onChange=\\"getDay(this.form, \'from\');\\">
			$fromyearsel
		</select>
		<input type=\\"text\\" name=\\"fromdayname\\" class=\\"{classname}Inactive\\" readonly=\\"readonly\\" value=\\"$fromdayname\\" size=\\"12\\" />
		</span>
	</td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Event Time:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\">
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
					".((!getop(\'cal_use24\') ) ? ("
					<select name=\\"fromampm\\" $timedisabled>
						<option value=\\"\\">$fromampmsel</option>
					</select>
					") : ("
					<input type=\\"hidden\\" name=\\"fromampm\\" value=\\"\\" />
					"))."
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
					<input type=\\"checkbox\\" name=\\"allday\\" $alldaychecked id=\\"allday\\" value=\\"1\\" onClick=\\"this.form.fromhour.disabled = this.form.fromminute.disabled = this.form.fromampm.disabled = this.form.durhours.disabled = this.form.durminutes.disabled = this.checked;\\" disabled=\\"disabled\\" /> <label for=\\"allday\\">All day event</label>
					</span>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
".(($event[addresses] ) ? ("
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\">
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Related email addresses:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\" align=\\"right\\"><input type=\\"text\\" class=\\"{classname}Inactive\\" readonly=\\"readonly\\" name=\\"addresses\\" id=\\"addresses\\" value=\\"$event[addresses]\\" size=\\"72\\" /></td>
</tr>
</table>
") : (\'\'))."
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Special Notes</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}RightCell\\" width=\\"100%\\" colspan=\\"2\\" align=\\"center\\">You do not have permission to modify this event.<br />If you have any questions, you should contact the event poster, <a href=\\"compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}data[to]=$event[poster]$domain\\">$event[poster]$domain</a>.</td>
</tr>
</table>

<br />

".(($event[eventtype] == -2 and ($event[canforward] or $event[shareoptions] & CAL_SHARE_CANLIST) ) ? ("
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\">
<tr>
	<td align=\\"center\\">
		".(($event[shareoptions] & CAL_SHARE_CANFWD ) ? ("
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"forwardevent\\" value=\\"Forward Event\\" onClick=\\"document.eventform.cmd.value=\'forward\'; return true;\\" />
		") : (\'\'))."
		".(($event[shareoptions] & CAL_SHARE_CANLIST ) ? ("
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"emaillist\\" value=\\"Email Event Userlist\\" onClick=\\"document.eventform.cmd.value=\'emailgroup\'; return true;\\" />
		") : (\'\'))."
	</td>
</tr>
</table>
") : (\'\'))."
</form>

<script language=\\"JavaScript\\">
<!--
if (getCookie(\'eventinfo\') == \'closed\') {
	renderSect(new Array(\'eventinfo\'), \'info\');
}
// -->
</script>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'calendar_event_write' => 
  array (
    'templategroupid' => '18',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: <%if $newevent %>Add New Event<%elseif $event[eventtype] == -2 %>View Shared Event<%elseif $event[eventtype] == -1 %>View Global Event<%else%>Modify Event<%endif%></title>
$css
<script type="text/javascript" src="misc/common.js"></script>
<script type="text/javascript" src="misc/checkall.js"></script>
<script type="text/javascript" src="misc/cookies.js"></script>
<script type="text/javascript" src="misc/collapse.js"></script>
<script language="JavaScript">
<!--

function popAddBook (local) {
	var url = "addressbook.view.php?cmd=mini";
	if (local == 1) {
		url += "&frompage=event&cmd2=update2&local=1<%if $newevent %>&newevent=1<%endif%>&pre[list]=" + escape(document.forms.eventform.eventlistaddresses.value);
	} else {
		url += "&pre[list]=" + escape (document.forms.eventform.addresses.value);
	}
	var hWnd = window.open(url,"AddBook","width=530,height=345,resizable=yes,scrollbars=yes,menubar=yes");
	if ((document.window != null) && (!hWnd.opener)) {
		hWnd.opener = document.window;
	}
}

function getDay(tform, which) {
	var date = new Date(eval(\'tform.\'+which+\'year\').value, eval(\'tform.\'+which+\'month\').value - 1, eval(\'tform.\'+which+\'day\').value);
	eval(\'tform.\'+which+\'dayname\').value = DayNames[date.getDay()];
}

function doVal(tform) {
	if (tform.eventtype.value == -2 && tform.eventlistaddresses.value == \'\') {
		alert(\'You must choose at least one user to share this event with. Otherwise, you must change the Event Type to Normal.\');
		return false;
	}
	return true;
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

<form action="calendar.event.php" method="post" name="eventform" onsubmit="doVal(this.form)">
<input type="hidden" name="cmd" value="update" />
<%if !$newevent %>
<input type="hidden" name="eventid" value="$event[eventid]" />
<input type="hidden" name="cmd2" value="" />
<%endif%>
<input type="hidden" name="eventlistaddresses" value="$eventlistaddresses" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="700">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Event Information</b> <img src="images/arrow_up.gif" name="info" onClick="renderSect(new Array(\'eventinfo\'), \'info\');" border="0" style="cursor: pointer; vertical-align: middle;" /></span></th>
</tr>
</table>
<table cellpadding="4" cellspacing="0" class="normalTable" width="700" id="eventinfo">
<%if $event[eventtype] == -2 %>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top"><span class="normalfont"><b>Event Poster:</b></span></td>
	<td class="{classname}RightCell" width="70%" align="right"><input type="text" name="eventposter" class="{classname}InactiveLink" readonly="readonly" size="76" value="$event[poster]$domain" onMouseOver="this.className=\'{classname}InactiveLinkHover\'; window.status=\'{$appurl}compose.email.php?data[to]=$event[poster]$domain\';" onMouseOut="this.className=\'{classname}InactiveLink\'; window.status=\'\';" onClick="window.location=\'compose.email.php?data[to]=$event[poster]$domain\'" style="cursor:pointer;" /></td>
</tr>
<%endif%>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top"><span class="normalfont"><b>Event Title:</b></span></td>
	<td class="{classname}RightCell" width="70%" align="right"><input type="text" class="bginput" name="title" value="$event[title]" size="76" /></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}BothCell" colspan="2">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr valign="top">
				<td><textarea name="message" style="width: 686px; height: 180px;" wrap="virtual" id="tmessage">$event[message]</textarea></td>
			</tr>
		</table>
	</td>
</tr>
</table>
<table cellpadding="4" cellspacing="0" class="normalTable" width="700">
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top"><span class="normalfont"><b><a href="#stayhere" onClick="popAddBook(0); return false;"><img src="$skin[images]/addbook.gif" alt="Address Book" border="0" /></a> Related email addresses:</b></span></td>
	<td class="{classname}RightCell" width="70%" align="right"><input type="text" class="bginput" name="addresses" id="addresses" value="$event[addresses]" autocomplete="off" onKeyUp="autoComplete(this, contacts);" size="73" /></td>
</tr>
</table>

<table cellpadding="4" cellspacing="0" class="normalTable" width="700">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Event Date and Time</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top"><span class="normalfont"><b>Event Date:</b></span></td>
	<td class="{classname}RightCell" width="70%" nowrap="nowrap"><span class="normalfont">
		<select name="frommonth" onChange="getDay(this.form, \'from\');">
			$frommonthsel
		</select>
		<select name="fromday" onChange="getDay(this.form, \'from\');">
			$fromdaysel
		</select>
		<select name="fromyear" onChange="getDay(this.form, \'from\');">
			$fromyearsel
		</select>
		<input type="text" name="fromdayname" class="{classname}Inactive" readonly="readonly" value="$fromdayname" size="12" />
		</span>
	</td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top"><span class="normalfont"><b>Event Time:</b></span></td>
	<td class="{classname}RightCell" width="70%">
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
					<%if !getop(\'cal_use24\') %>
					<select name="fromampm" $timedisabled>
						<option value="am" $fromampmsel[am]>AM</option>
						<option value="pm" $fromampmsel[pm]>PM</option>
					</select>
					<%else%>
					<input type="hidden" name="fromampm" value="" />
					<%endif%>
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
					<input type="checkbox" name="allday" $alldaychecked id="allday" value="1" onClick="this.form.fromhour.disabled = this.form.fromminute.disabled = this.form.fromampm.disabled = this.form.durhours.disabled = this.form.durminutes.disabled = this.checked;" /> <label for="allday">All day event</label>
					</span>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
<table cellpadding="4" cellspacing="0" class="normalTable" width="700">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Recurrence Options</b> <img src="images/arrow_up.gif" name="recur" onClick="if (document.forms.eventform.recur_yes.checked == true) { renderSect(new Array(\'recurrence\', \'recuropt\'), \'recur\') } else { renderSect(new Array(\'recurrence\'), \'recur\'); }" border="0" style="cursor: pointer; vertical-align: middle;" /></span></th>
</tr>
</table>
<table cellpadding="4" cellspacing="0" class="normalTable" width="700" id="recurrence" $recurinitdisplay>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top"><span class="normalfont">&nbsp;</span></td>
	<td class="{classname}RightCell" width="70%"><span class="normalfont">
		<input type="radio" name="recurtype" id="recur_none" value="0" onClick="document.forms.eventform.recur_yes.checked = false; render(2, \'recuropt\')" $typecheck[0] /> <label for="recur_none">One Time Event</label><br />
		<input type="radio" name="dorecur" id="recur_yes" value="0" onClick="document.forms.eventform.recur_none.checked = false; render(1, \'recuropt\');" $recurcheck /> <label for="recur_yes">Recurring Event</label>
	</span></td>
</tr>
</table>
<table cellpadding="4" cellspacing="0" class="normalTable" width="700" id="recuropt" $recurdisplay>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top"><span class="normalfont"><b>Recurrence Pattern:</b></span></td>
	<td class="{classname}RightCell" width="70%"><span class="normalfont">
		<input type="radio" name="recurtype" id="recur_daily" value="1" $typecheck[1] /> <label for="recur_daily">Recur every &nbsp;</label><input type="text" class="bginput" name="daily_every" value="$daily_every" size="3" maxlength="3" onClick="this.form.recur_daily.checked = true;" /><label for="recur_daily">&nbsp; day(s)</label><br />
		<input type="radio" name="recurtype" id="recur_weekday" value="2" $typecheck[2] /> <label for="recur_weekday">Recur every weekday</label>
	</span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top">&nbsp;</td>
	<td class="{classname}RightCell" width="70%"><span class="normalfont">
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
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top">&nbsp;</td>
	<td class="{classname}RightCell" width="70%"><span class="normalfont">
		<input type="radio" name="recurtype" id="recur_monthly" value="4" $typecheck[4] /> <label for="recur_monthly">Recur on day</label>
			&nbsp;<select name="monthly_on" onChange="this.form.recur_monthly.checked = true;">
				$monthly_onsel
			</select>&nbsp;
			<label for="recur_monthly">every &nbsp;</label><input type="text" class="bginput" name="monthly_every" value="$monthly_every" size="3" maxlength="3" onClick="this.form.recur_monthly.checked = true;" /><label for="recur_monthly">&nbsp; months(s)
			</label>
	</span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top">&nbsp;</td>
	<td class="{classname}RightCell" width="70%"><span class="normalfont">
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
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top"><span class="normalfont"><b>Recurrence Range:</b></span></td>
	<td class="{classname}RightCell" width="70%"><span class="normalfont">
		<input type="radio" name="recurend" id="recurend_none" value="0" $ending[0] /> <label for="recurend_none">No end date</label>
	</span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top">&nbsp;</td>
	<td class="{classname}RightCell" width="70%"><span class="normalfont">
		<input type="radio" name="recurend" id="recur_count" value="1" $ending[1] /> <label for="recur_count">End after &nbsp;</label><input type="text" class="bginput" name="end_after" value="$end_after" size="3" maxlength="3" onClick="this.form.recur_count.checked = true;" /><label for="recur_count">&nbsp; occurrences</label>
	</span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top">&nbsp;</td>
	<td class="{classname}RightCell" width="70%" nowrap="nowrap"><span class="normalfont">
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
		<input type="text" name="todayname" class="{classname}Inactive" readonly="readonly" value="" size="12" />
	</span></td>
</tr>
</table>

<%if ($newevent and ($hiveuser[canadmin] or $hiveuser[cansharedevents])) or
$hiveuser[canadmin] or
($hiveuser[cansharedevents] and $event[eventtype] == -2 and ($hiveuser[userid] == $event[userid] or
$event[shareoptions] & CAL_SHARE_CANEDIT)) %>
<table cellpadding="4" cellspacing="0" class="normalTable" width="700">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Event Options</b> <img src="images/arrow_up.gif" name="opt" onClick="<%if $hiveuser[userid] == $event[userid] || $newevent %>if (document.forms.eventform.regevent.checked == true || document.forms.eventform.globalevent.checked == true) { renderSect(new Array(\'eventopt\'), \'opt\'); } else if (document.forms.eventform.shareevent.checked == true) { renderSect(new Array(\'eventopt\', \'eventshareopt\'), \'opt\'); }<%else%>renderSect(new Array(\'eventshareopt\'), \'opt\');<%endif%>" border="0" style="cursor: pointer; vertical-align: middle;" /></span></th>
</tr>
</table>
<%if $hiveuser[userid] == $event[userid] || $newevent %>
<table cellpadding="4" cellspacing="0" class="normalTable" width="700" id="eventopt">
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top"><span class="normalfont"><b>Event Type:</b></span></td>
	<td class="{classname}RightCell" width="70%" nowrap="nowrap"><span class="normalfont">
	<label for="regevent"><input type="radio" name="eventtype" id="regevent" onClick="render(2, \'eventshareopt\')" value="0" $eventtypecheck0 /> Normal Event</label><br />
	<%if $hiveuser[cansharedevents] %><label for="shareevent"><input type="radio" name="eventtype" id="shareevent" onClick="render(1, \'eventshareopt\')" value="-2" $eventtypecheck2 /> Shared Event</label> <%help {Selecting this option allows you to share this event with other $slashedappname users. After selecting this option, you will be able to select the users below.} %><br /><%endif%>
	<%if $hiveuser[canadmin] %><label for="globalevent"><input type="radio" name="eventtype" id="globalevent" onClick="render(2, \'eventshareopt\')" value="-1" $eventtypecheck1 /> Global Event</label> <%help {Global events are created by administrators and shown on the calendars of users whose usergroup has permission to view them.} %><%endif%>
	</span></td>
</tr>
</table>
<%endif%>
<table cellpadding="4" cellspacing="0" class="normalTable" width="700" id="eventshareopt" $shareinitdisplay>
<%if $hiveuser[userid] == $event[userid] || $newevent %>
<tr id="shareoptions" class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top"><span class="normalfont"><b>Share Options:</b></span></td>
	<td class="{classname}RightCell" width="70%" nowrap="nowrap"><label for="shareedit"><span class="normalfont"><input type="checkbox" name="shareedit" id="shareedit" value="1" $shareeditcheck /> Shared group members can modify this event</label></span><br />
	<span class="normalfont"><label for="shareforward"><input type="checkbox" name="shareforward" id="shareforward" value="1" $sharefwdcheck /> Shared group members can share this event with others</label></span><br />
	<span class="normalfont"><label for="sharelist"><input type="checkbox" name="sharelist" id="sharelist" value="1" $sharelistcheck /> Shared group members can see whole list of shared group members</label></span>
	</span></td>
</tr>
<%endif%>
<%if ($event[eventtype] == -2 and $event[shareoptions] & CAL_EVENT_CANLIST) or $event[userid] == $hiveuser[userid] or $newevent %>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top"><span class="normalfont"><%if $event[shareoptions] & CAL_SHARE_CANEDIT or $event[userid] == $hiveuser[userid] or $newevent %><a href="#stayhere" onClick="popAddBook(1); return false;"><img src="$skin[images]/addbook.gif" alt="Address Book" border="0" /></a> <%endif%><b>Shared Event Userlist:</b> <%if $event[shareoptions] & CAL_SHARE_CANEDIT or $event[userid] == $hiveuser[userid] or $newevent %><%help {To add users here, they must exist in your address book. Click the Address Book icon to the left to add or modify users on this list.} %><%endif%></span></td>
	<td class="{classname}RightCell" width="70%" valign="top" align="right">
	$event[groupuserlist]
	<%if $addl > 0 %>
	<input type="text" size="76" readonly="readonly" class="{classname}Inactive" value="Plus $addl other users who chose to not be listed." />
	<%endif%>
	</td>
</tr>
<%endif%>
<%if $event[userid] == $hiveuser[userid] and $event[eventtype] == -2 and !$newevent %>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="30%" valign="top"><span class="normalfont"><b>Event Notes:</b></span><br /><span class="smallfont">(only visible to you)</span></td>
	<td class="{classname}RightCell" width="70%" valign="top" align="right"><textarea name="eventnotes" cols="73" rows="5">$event[notes]</textarea></td>
</tr>
<%endif%>
</table>
<%endif%>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="700">
<tr>
    <td align="center">
        <input type="submit" class="bginput" name="updateevent" value="<%if $newevent %>Create<%else%>Update <%endif%> Event" />
        <input type="reset" class="bginput" name="reset" value="Reset Fields" />
<%if !$newevent and $event[userid] == $hiveuser[userid] %>
        <input type="submit" class="bginput" name="deleteevent" value="Delete Event" onClick="document.eventform.cmd.value=\'delete\'; return true;" />
<%endif%>
    </td>
</tr>
</table>

<%if $event[eventtype] == -2 and (($event[canforward] or $event[shareoptions] & CAL_SHARE_CANLIST) or $event[userid] == $hiveuser[userid]) %>
<table cellpadding="4" cellspacing="0" class="normalTable" width="700">
<tr>
	<td align="center">
		<%if $event[shareoptions] & CAL_SHARE_CANFWD or $event[userid] == $hiveuser[userid] %>
		<input type="submit" class="bginput" name="forwardevent" value="Forward Event" onClick="document.eventform.cmd.value=\'forward\'; return true;" />
		<%endif%>
		<%if $event[shareoptions] & CAL_SHARE_CANLIST or $event[userid] == $hiveuser[userid] %>
		<input type="submit" class="bginput" name="emaillist" value="Email Event Userlist" onClick="document.eventform.cmd.value=\'emailgroup\'; return true;" />
		<%endif%>
	</td>
</tr>
</table>
<%endif%>
</form>

<script language="JavaScript">
<!--
getDay(document.forms.eventform, \'from\');
getDay(document.forms.eventform, \'to\');

if (getCookie(\'recurrence\') == \'closed\') {
	if (document.forms.eventform.recur_yes.checked == true) {
		renderSect(new Array(\'recurrence\', \'recuropt\'), \'recur\');
	} else {
		renderSect(new Array(\'recurrence\'), \'recur\');
	}
} else {
	document.images[\'recur\'].title = \'Collapse This Section\';
}
<%if $hiveuser[userid] == $event[userid] or $event[shareoptions] & CAL_SHARE_CANEDIT or $hiveuser[canadmin] or ($newevent and $hiveuser[cansharedevents]) %>
if (getCookie(\'eventopt\') == \'closed\') {
	if (document.forms.eventform.regevent.checked == true || document.forms.eventform.globalevent.checked == true) {
		renderSect(new Array(\'eventopt\'), \'opt\');
	} else {
		renderSect(new Array(\'eventopt\', \'eventshareopt\'), \'opt\');
	}
} else {
	document.images[\'opt\'].title = \'Collapse This Section\';
}
<%endif%>
if (getCookie(\'eventinfo\') == \'closed\') {
	renderSect(new Array(\'eventinfo\'), \'info\');
} else {
	document.images[\'info\'].title = \'Collapse This Section\';
}
// -->
</script>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: ".(($newevent ) ? ("Add New Event") : ((($event[eventtype] == -2 ) ? ("View Shared Event") : (($event[eventtype] == -1 ) ? ("View Global Event") : ("Modify Event")))))."</title>
$GLOBALS[css]
<script type=\\"text/javascript\\" src=\\"misc/common.js\\"></script>
<script type=\\"text/javascript\\" src=\\"misc/checkall.js\\"></script>
<script type=\\"text/javascript\\" src=\\"misc/cookies.js\\"></script>
<script type=\\"text/javascript\\" src=\\"misc/collapse.js\\"></script>
<script language=\\"JavaScript\\">
<!--

function popAddBook (local) {
	var url = \\"addressbook.view.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=mini\\";
	if (local == 1) {
		url += \\"&frompage=event&cmd2=update2&local=1".(($newevent ) ? ("&newevent=1") : (\'\'))."&pre[list]=\\" + escape(document.forms.eventform.eventlistaddresses.value);
	} else {
		url += \\"&pre[list]=\\" + escape (document.forms.eventform.addresses.value);
	}
	var hWnd = window.open(url,\\"AddBook\\",\\"width=530,height=345,resizable=yes,scrollbars=yes,menubar=yes\\");
	if ((document.window != null) && (!hWnd.opener)) {
		hWnd.opener = document.window;
	}
}

function getDay(tform, which) {
	var date = new Date(eval(\'tform.\'+which+\'year\').value, eval(\'tform.\'+which+\'month\').value - 1, eval(\'tform.\'+which+\'day\').value);
	eval(\'tform.\'+which+\'dayname\').value = DayNames[date.getDay()];
}

function doVal(tform) {
	if (tform.eventtype.value == -2 && tform.eventlistaddresses.value == \'\') {
		alert(\'You must choose at least one user to share this event with. Otherwise, you must change the Event Type to Normal.\');
		return false;
	}
	return true;
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

<form action=\\"calendar.event.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"eventform\\" onsubmit=\\"doVal(this.form)\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />
".((!$newevent ) ? ("
<input type=\\"hidden\\" name=\\"eventid\\" value=\\"$event[eventid]\\" />
<input type=\\"hidden\\" name=\\"cmd2\\" value=\\"\\" />
") : (\'\'))."
<input type=\\"hidden\\" name=\\"eventlistaddresses\\" value=\\"$eventlistaddresses\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Event Information</b> <img src=\\"images/arrow_up.gif\\" name=\\"info\\" onClick=\\"renderSect(new Array(\'eventinfo\'), \'info\');\\" border=\\"0\\" style=\\"cursor: pointer; vertical-align: middle;\\" /></span></th>
</tr>
</table>
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\" id=\\"eventinfo\\">
".(($event[eventtype] == -2 ) ? ("
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Event Poster:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\" align=\\"right\\"><input type=\\"text\\" name=\\"eventposter\\" class=\\"{classname}InactiveLink\\" readonly=\\"readonly\\" size=\\"76\\" value=\\"$event[poster]$domain\\" onMouseOver=\\"this.className=\'{classname}InactiveLinkHover\'; window.status=\'{$appurl}compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}data[to]=$event[poster]$domain\';\\" onMouseOut=\\"this.className=\'{classname}InactiveLink\'; window.status=\'\';\\" onClick=\\"window.location=\'compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}data[to]=$event[poster]$domain\'\\" style=\\"cursor:pointer;\\" /></td>
</tr>
") : (\'\'))."
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Event Title:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\" align=\\"right\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"title\\" value=\\"$event[title]\\" size=\\"76\\" /></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\" colspan=\\"2\\">
		<table cellpadding=\\"0\\" cellspacing=\\"0\\" border=\\"0\\">
			<tr valign=\\"top\\">
				<td><textarea name=\\"message\\" style=\\"width: 686px; height: 180px;\\" wrap=\\"virtual\\" id=\\"tmessage\\">$event[message]</textarea></td>
			</tr>
		</table>
	</td>
</tr>
</table>
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\">
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b><a href=\\"#stayhere\\" onClick=\\"popAddBook(0); return false;\\"><img src=\\"{$GLOBALS[skin][images]}/addbook.gif\\" alt=\\"Address Book\\" border=\\"0\\" /></a> Related email addresses:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\" align=\\"right\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"addresses\\" id=\\"addresses\\" value=\\"$event[addresses]\\" autocomplete=\\"off\\" onKeyUp=\\"autoComplete(this, contacts);\\" size=\\"73\\" /></td>
</tr>
</table>

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Event Date and Time</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Event Date:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">
		<select name=\\"frommonth\\" onChange=\\"getDay(this.form, \'from\');\\">
			$frommonthsel
		</select>
		<select name=\\"fromday\\" onChange=\\"getDay(this.form, \'from\');\\">
			$fromdaysel
		</select>
		<select name=\\"fromyear\\" onChange=\\"getDay(this.form, \'from\');\\">
			$fromyearsel
		</select>
		<input type=\\"text\\" name=\\"fromdayname\\" class=\\"{classname}Inactive\\" readonly=\\"readonly\\" value=\\"$fromdayname\\" size=\\"12\\" />
		</span>
	</td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Event Time:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\">
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
					".((!getop(\'cal_use24\') ) ? ("
					<select name=\\"fromampm\\" $timedisabled>
						<option value=\\"am\\" $fromampmsel[am]>AM</option>
						<option value=\\"pm\\" $fromampmsel[pm]>PM</option>
					</select>
					") : ("
					<input type=\\"hidden\\" name=\\"fromampm\\" value=\\"\\" />
					"))."
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
					<input type=\\"checkbox\\" name=\\"allday\\" $alldaychecked id=\\"allday\\" value=\\"1\\" onClick=\\"this.form.fromhour.disabled = this.form.fromminute.disabled = this.form.fromampm.disabled = this.form.durhours.disabled = this.form.durminutes.disabled = this.checked;\\" /> <label for=\\"allday\\">All day event</label>
					</span>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Recurrence Options</b> <img src=\\"images/arrow_up.gif\\" name=\\"recur\\" onClick=\\"if (document.forms.eventform.recur_yes.checked == true) { renderSect(new Array(\'recurrence\', \'recuropt\'), \'recur\') } else { renderSect(new Array(\'recurrence\'), \'recur\'); }\\" border=\\"0\\" style=\\"cursor: pointer; vertical-align: middle;\\" /></span></th>
</tr>
</table>
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\" id=\\"recurrence\\" $recurinitdisplay>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\">&nbsp;</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\"><span class=\\"normalfont\\">
		<input type=\\"radio\\" name=\\"recurtype\\" id=\\"recur_none\\" value=\\"0\\" onClick=\\"document.forms.eventform.recur_yes.checked = false; render(2, \'recuropt\')\\" $typecheck[0] /> <label for=\\"recur_none\\">One Time Event</label><br />
		<input type=\\"radio\\" name=\\"dorecur\\" id=\\"recur_yes\\" value=\\"0\\" onClick=\\"document.forms.eventform.recur_none.checked = false; render(1, \'recuropt\');\\" $recurcheck /> <label for=\\"recur_yes\\">Recurring Event</label>
	</span></td>
</tr>
</table>
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\" id=\\"recuropt\\" $recurdisplay>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Recurrence Pattern:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\"><span class=\\"normalfont\\">
		<input type=\\"radio\\" name=\\"recurtype\\" id=\\"recur_daily\\" value=\\"1\\" $typecheck[1] /> <label for=\\"recur_daily\\">Recur every &nbsp;</label><input type=\\"text\\" class=\\"bginput\\" name=\\"daily_every\\" value=\\"$daily_every\\" size=\\"3\\" maxlength=\\"3\\" onClick=\\"this.form.recur_daily.checked = true;\\" /><label for=\\"recur_daily\\">&nbsp; day(s)</label><br />
		<input type=\\"radio\\" name=\\"recurtype\\" id=\\"recur_weekday\\" value=\\"2\\" $typecheck[2] /> <label for=\\"recur_weekday\\">Recur every weekday</label>
	</span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\">&nbsp;</td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\"><span class=\\"normalfont\\">
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
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\">&nbsp;</td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\"><span class=\\"normalfont\\">
		<input type=\\"radio\\" name=\\"recurtype\\" id=\\"recur_monthly\\" value=\\"4\\" $typecheck[4] /> <label for=\\"recur_monthly\\">Recur on day</label>
			&nbsp;<select name=\\"monthly_on\\" onChange=\\"this.form.recur_monthly.checked = true;\\">
				$monthly_onsel
			</select>&nbsp;
			<label for=\\"recur_monthly\\">every &nbsp;</label><input type=\\"text\\" class=\\"bginput\\" name=\\"monthly_every\\" value=\\"$monthly_every\\" size=\\"3\\" maxlength=\\"3\\" onClick=\\"this.form.recur_monthly.checked = true;\\" /><label for=\\"recur_monthly\\">&nbsp; months(s)
			</label>
	</span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\">&nbsp;</td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\"><span class=\\"normalfont\\">
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
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Recurrence Range:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\"><span class=\\"normalfont\\">
		<input type=\\"radio\\" name=\\"recurend\\" id=\\"recurend_none\\" value=\\"0\\" $ending[0] /> <label for=\\"recurend_none\\">No end date</label>
	</span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\">&nbsp;</td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\"><span class=\\"normalfont\\">
		<input type=\\"radio\\" name=\\"recurend\\" id=\\"recur_count\\" value=\\"1\\" $ending[1] /> <label for=\\"recur_count\\">End after &nbsp;</label><input type=\\"text\\" class=\\"bginput\\" name=\\"end_after\\" value=\\"$end_after\\" size=\\"3\\" maxlength=\\"3\\" onClick=\\"this.form.recur_count.checked = true;\\" /><label for=\\"recur_count\\">&nbsp; occurrences</label>
	</span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\">&nbsp;</td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">
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
		<input type=\\"text\\" name=\\"todayname\\" class=\\"{classname}Inactive\\" readonly=\\"readonly\\" value=\\"\\" size=\\"12\\" />
	</span></td>
</tr>
</table>

".((($newevent and ($hiveuser[canadmin] or $hiveuser[cansharedevents])) or
$hiveuser[canadmin] or
($hiveuser[cansharedevents] and $event[eventtype] == -2 and ($hiveuser[userid] == $event[userid] or
$event[shareoptions] & CAL_SHARE_CANEDIT)) ) ? ("
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Event Options</b> <img src=\\"images/arrow_up.gif\\" name=\\"opt\\" onClick=\\"".(($hiveuser[userid] == $event[userid] || $newevent ) ? ("if (document.forms.eventform.regevent.checked == true || document.forms.eventform.globalevent.checked == true) { renderSect(new Array(\'eventopt\'), \'opt\'); } else if (document.forms.eventform.shareevent.checked == true) { renderSect(new Array(\'eventopt\', \'eventshareopt\'), \'opt\'); }") : ("renderSect(new Array(\'eventshareopt\'), \'opt\');"))."\\" border=\\"0\\" style=\\"cursor: pointer; vertical-align: middle;\\" /></span></th>
</tr>
</table>
".(($hiveuser[userid] == $event[userid] || $newevent ) ? ("
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\" id=\\"eventopt\\">
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Event Type:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">
	<label for=\\"regevent\\"><input type=\\"radio\\" name=\\"eventtype\\" id=\\"regevent\\" onClick=\\"render(2, \'eventshareopt\')\\" value=\\"0\\" $eventtypecheck0 /> Normal Event</label><br />
	".(($hiveuser[cansharedevents] ) ? ("<label for=\\"shareevent\\"><input type=\\"radio\\" name=\\"eventtype\\" id=\\"shareevent\\" onClick=\\"render(1, \'eventshareopt\')\\" value=\\"-2\\" $eventtypecheck2 /> Shared Event</label> <a href=\\"#\\" onClick=\\"alert(\'Selecting this option allows you to share this event with other $slashedappname users. After selecting this option, you will be able to select the users below.\'); return false;\\"><img src=\\"{$GLOBALS[skin][images]}/help.gif\\" border=\\"0\\" /></a><br />") : (\'\'))."
	".(($hiveuser[canadmin] ) ? ("<label for=\\"globalevent\\"><input type=\\"radio\\" name=\\"eventtype\\" id=\\"globalevent\\" onClick=\\"render(2, \'eventshareopt\')\\" value=\\"-1\\" $eventtypecheck1 /> Global Event</label> <a href=\\"#\\" onClick=\\"alert(\'Global events are created by administrators and shown on the calendars of users whose usergroup has permission to view them.\'); return false;\\"><img src=\\"{$GLOBALS[skin][images]}/help.gif\\" border=\\"0\\" /></a>") : (\'\'))."
	</span></td>
</tr>
</table>
") : (\'\'))."
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\" id=\\"eventshareopt\\" $shareinitdisplay>
".(($hiveuser[userid] == $event[userid] || $newevent ) ? ("
<tr id=\\"shareoptions\\" class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Share Options:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\" nowrap=\\"nowrap\\"><label for=\\"shareedit\\"><span class=\\"normalfont\\"><input type=\\"checkbox\\" name=\\"shareedit\\" id=\\"shareedit\\" value=\\"1\\" $shareeditcheck /> Shared group members can modify this event</label></span><br />
	<span class=\\"normalfont\\"><label for=\\"shareforward\\"><input type=\\"checkbox\\" name=\\"shareforward\\" id=\\"shareforward\\" value=\\"1\\" $sharefwdcheck /> Shared group members can share this event with others</label></span><br />
	<span class=\\"normalfont\\"><label for=\\"sharelist\\"><input type=\\"checkbox\\" name=\\"sharelist\\" id=\\"sharelist\\" value=\\"1\\" $sharelistcheck /> Shared group members can see whole list of shared group members</label></span>
	</span></td>
</tr>
") : (\'\'))."
".((($event[eventtype] == -2 and $event[shareoptions] & CAL_EVENT_CANLIST) or $event[userid] == $hiveuser[userid] or $newevent ) ? ("
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\">".(($event[shareoptions] & CAL_SHARE_CANEDIT or $event[userid] == $hiveuser[userid] or $newevent ) ? ("<a href=\\"#stayhere\\" onClick=\\"popAddBook(1); return false;\\"><img src=\\"{$GLOBALS[skin][images]}/addbook.gif\\" alt=\\"Address Book\\" border=\\"0\\" /></a> ") : (\'\'))."<b>Shared Event Userlist:</b> ".(($event[shareoptions] & CAL_SHARE_CANEDIT or $event[userid] == $hiveuser[userid] or $newevent ) ? ("<a href=\\"#\\" onClick=\\"alert(\'To add users here, they must exist in your address book. Click the Address Book icon to the left to add or modify users on this list.\'); return false;\\"><img src=\\"{$GLOBALS[skin][images]}/help.gif\\" border=\\"0\\" /></a>") : (\'\'))."</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\" valign=\\"top\\" align=\\"right\\">
	$event[groupuserlist]
	".(($addl > 0 ) ? ("
	<input type=\\"text\\" size=\\"76\\" readonly=\\"readonly\\" class=\\"{classname}Inactive\\" value=\\"Plus $addl other users who chose to not be listed.\\" />
	") : (\'\'))."
	</td>
</tr>
") : (\'\'))."
".(($event[userid] == $hiveuser[userid] and $event[eventtype] == -2 and !$newevent ) ? ("
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"30%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Event Notes:</b></span><br /><span class=\\"smallfont\\">(only visible to you)</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"70%\\" valign=\\"top\\" align=\\"right\\"><textarea name=\\"eventnotes\\" cols=\\"73\\" rows=\\"5\\">$event[notes]</textarea></td>
</tr>
") : (\'\'))."
</table>
") : (\'\'))."

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\">
<tr>
    <td align=\\"center\\">
        <input type=\\"submit\\" class=\\"bginput\\" name=\\"updateevent\\" value=\\"".(($newevent ) ? ("Create") : ("Update "))." Event\\" />
        <input type=\\"reset\\" class=\\"bginput\\" name=\\"reset\\" value=\\"Reset Fields\\" />
".((!$newevent and $event[userid] == $hiveuser[userid] ) ? ("
        <input type=\\"submit\\" class=\\"bginput\\" name=\\"deleteevent\\" value=\\"Delete Event\\" onClick=\\"document.eventform.cmd.value=\'delete\'; return true;\\" />
") : (\'\'))."
    </td>
</tr>
</table>

".(($event[eventtype] == -2 and (($event[canforward] or $event[shareoptions] & CAL_SHARE_CANLIST) or $event[userid] == $hiveuser[userid]) ) ? ("
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\">
<tr>
	<td align=\\"center\\">
		".(($event[shareoptions] & CAL_SHARE_CANFWD or $event[userid] == $hiveuser[userid] ) ? ("
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"forwardevent\\" value=\\"Forward Event\\" onClick=\\"document.eventform.cmd.value=\'forward\'; return true;\\" />
		") : (\'\'))."
		".(($event[shareoptions] & CAL_SHARE_CANLIST or $event[userid] == $hiveuser[userid] ) ? ("
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"emaillist\\" value=\\"Email Event Userlist\\" onClick=\\"document.eventform.cmd.value=\'emailgroup\'; return true;\\" />
		") : (\'\'))."
	</td>
</tr>
</table>
") : (\'\'))."
</form>

<script language=\\"JavaScript\\">
<!--
getDay(document.forms.eventform, \'from\');
getDay(document.forms.eventform, \'to\');

if (getCookie(\'recurrence\') == \'closed\') {
	if (document.forms.eventform.recur_yes.checked == true) {
		renderSect(new Array(\'recurrence\', \'recuropt\'), \'recur\');
	} else {
		renderSect(new Array(\'recurrence\'), \'recur\');
	}
} else {
	document.images[\'recur\'].title = \'Collapse This Section\';
}
".(($hiveuser[userid] == $event[userid] or $event[shareoptions] & CAL_SHARE_CANEDIT or $hiveuser[canadmin] or ($newevent and $hiveuser[cansharedevents]) ) ? ("
if (getCookie(\'eventopt\') == \'closed\') {
	if (document.forms.eventform.regevent.checked == true || document.forms.eventform.globalevent.checked == true) {
		renderSect(new Array(\'eventopt\'), \'opt\');
	} else {
		renderSect(new Array(\'eventopt\', \'eventshareopt\'), \'opt\');
	}
} else {
	document.images[\'opt\'].title = \'Collapse This Section\';
}
") : (\'\'))."
if (getCookie(\'eventinfo\') == \'closed\') {
	renderSect(new Array(\'eventinfo\'), \'info\');
} else {
	document.images[\'info\'].title = \'Collapse This Section\';
}
// -->
</script>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
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
				<tr class="highRow">
					<td class="highLeftCell" nowrap="nowrap" valign="top">
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
					<td class="highCell" nowrap="nowrap" valign="top">
						<table style="height: 40px;">
							<tr style="height: 50%;">
								<td nowrap="nowrap">
									<span class="normalfont">Event date:</span>
									&nbsp;<input type="text" name="fromdayname" class="highInactive" readonly="readonly" value="$skin[cal_sun_long]" size="12" />
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
					<td class="highRightCell" valign="top">
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
									<%if !getop(\'cal_use24\') %>
									<select name="fromampm" $timedisabled>
										<option value="am" $fromampmsel[am]>AM</option>
										<option value="pm" $fromampmsel[pm]>PM</option>
									</select>
									<%else%>
									<input type="hidden" name="fromampm" value="" />
									<%endif%>
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
				<tr class=\\"highRow\\">
					<td class=\\"highLeftCell\\" nowrap=\\"nowrap\\" valign=\\"top\\">
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
					<td class=\\"highCell\\" nowrap=\\"nowrap\\" valign=\\"top\\">
						<table style=\\"height: 40px;\\">
							<tr style=\\"height: 50%;\\">
								<td nowrap=\\"nowrap\\">
									<span class=\\"normalfont\\">Event date:</span>
									&nbsp;<input type=\\"text\\" name=\\"fromdayname\\" class=\\"highInactive\\" readonly=\\"readonly\\" value=\\"{$GLOBALS[skin][cal_sun_long]}\\" size=\\"12\\" />
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
					<td class=\\"highRightCell\\" valign=\\"top\\">
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
									".((!getop(\'cal_use24\') ) ? ("
									<select name=\\"fromampm\\" $timedisabled>
										<option value=\\"am\\" $fromampmsel[am]>AM</option>
										<option value=\\"pm\\" $fromampmsel[pm]>PM</option>
									</select>
									") : ("
									<input type=\\"hidden\\" name=\\"fromampm\\" value=\\"\\" />
									"))."
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
    'upgraded' => '0',
  ),
  'calendar_options' => 
  array (
    'templategroupid' => '18',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Calendar Options</title>
$css
<script type="text/javascript" language="JavaScript">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$header

<form action="calendar.options.php" method="post">
<input type="hidden" name="cmd" value="update" />

<table cellpadding="4" cellspacing="0" width="100%">
	<tr>
		<td valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="150">
				<tr class="headerRow">
					<th class="headerBothCell"><span class="normalfonttablehead"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width="100%" valign="top">
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Calendar Options</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Show event reminders:</b></span>
	<br />
	<span class="smallfont">Choose whether or not you would like to see reminders to upcoming events on {$_folders[\'-1\'][\'title\']}.<br />Set to 0 to disable this option.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="text" name="calreminder" value="$hiveuser[calreminder]" class="bginput" size="2" /> days before they occur</span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Show current month on {$_folders[\'-1\'][\'title\']}:</b></span>
	<br />
	<span class="smallfont">Turn this on if you\'d like to see the current month displayed when viewing your {$_folders[\'-1\'][\'title\']}.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="caloninbox" value="1" id="caloninboxon" $caloninboxon /> <label for="caloninboxon">Yes</label><br /><input type="radio" name="caloninbox" value="0" id="caloninboxoff" $caloninboxoff /> <label for="caloninboxoff">No</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Display next or previous months on {$_folders[\'-1\'][\'title\']}:</b></span>
	<br />
	<span class="smallfont">If this option is enabled, the next or previous month will also be displayed if the current week spans beyond the current month.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="calspaninbox" value="1" id="calspaninboxon" $calspaninboxon /> <label for="calspaninboxon">Yes</label><br /><input type="radio" name="calspaninbox" value="0" id="calspaninboxoff" $calspaninboxoff /> <label for="calspaninboxoff">No</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Type of layout for yearly display:</b></span>
	<br />
	<span class="smallfont">How months should be laid out when viewing a whole year.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="calyear3on4" value="1" id="calyear3on4on" $calyear3on4on /> <label for="calyear3on4on">3 wide / 4 high</label><br /><input type="radio" name="calyear3on4" value="0" id="calyear3on4off" $calyear3on4off /> <label for="calyear3on4off">4 wide / 3 high</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Start of the week:</b></span>
	<br />
	<span class="smallfont">Choose the day of the week on which weeks start in your culture, to make your calendar appear correct.</span></td>
	<td class="{classname}RightCell" width="40%">
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
<%if $hiveuser[cansharedevents] %>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Allow other users to share events with me:</b></span>
	<br />
	<span class="smallfont">If this option is enabled, other users will be able to share calendar events with you, and vice-versa. Your name will appear on the Userlist associated with that event (unless you disable that option below, or the event poster disables the public userlist option).</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="calsharesok" value="1" id="calsharesokon" $calsharesokon /> <label for="calsharesokon">Yes</label><br /><input type="radio" name="calsharesok" value="0" id="calsharesokoff" $calsharesokoff /> <label for="calsharesokoff">No</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Show my email address on the userlist of shared events</b></span>
	<br />
	<span class="smallfont">If this option is enabled, other users will be able to see your email address on the public userlist for shared events that you are part of, unless the event poster disables the public userlist option.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="calshowmeonlist" value="1" id="calshowmeonliston" $calshowmeonliston /> <label for="calshowmeonliston">Yes</label><br /><input type="radio" name="calshowmeonlist" value="0" id="calshowmeonlistoff" $calshowmeonlistoff /> <label for="calshowmeonlistoff">No</label></span></td>
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
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$GLOBALS[header]

<form action=\\"calendar.options.php{$GLOBALS[session_url]}\\" method=\\"post\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" width=\\"100%\\">
	<tr>
		<td valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"150\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width=\\"100%\\" valign=\\"top\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Calendar Options</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Show event reminders:</b></span>
	<br />
	<span class=\\"smallfont\\">Choose whether or not you would like to see reminders to upcoming events on {$_folders[\'-1\'][\'title\']}.<br />Set to 0 to disable this option.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"text\\" name=\\"calreminder\\" value=\\"$hiveuser[calreminder]\\" class=\\"bginput\\" size=\\"2\\" /> days before they occur</span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Show current month on {$_folders[\'-1\'][\'title\']}:</b></span>
	<br />
	<span class=\\"smallfont\\">Turn this on if you\'d like to see the current month displayed when viewing your {$_folders[\'-1\'][\'title\']}.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"caloninbox\\" value=\\"1\\" id=\\"caloninboxon\\" $caloninboxon /> <label for=\\"caloninboxon\\">Yes</label><br /><input type=\\"radio\\" name=\\"caloninbox\\" value=\\"0\\" id=\\"caloninboxoff\\" $caloninboxoff /> <label for=\\"caloninboxoff\\">No</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Display next or previous months on {$_folders[\'-1\'][\'title\']}:</b></span>
	<br />
	<span class=\\"smallfont\\">If this option is enabled, the next or previous month will also be displayed if the current week spans beyond the current month.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"calspaninbox\\" value=\\"1\\" id=\\"calspaninboxon\\" $calspaninboxon /> <label for=\\"calspaninboxon\\">Yes</label><br /><input type=\\"radio\\" name=\\"calspaninbox\\" value=\\"0\\" id=\\"calspaninboxoff\\" $calspaninboxoff /> <label for=\\"calspaninboxoff\\">No</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Type of layout for yearly display:</b></span>
	<br />
	<span class=\\"smallfont\\">How months should be laid out when viewing a whole year.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"calyear3on4\\" value=\\"1\\" id=\\"calyear3on4on\\" $calyear3on4on /> <label for=\\"calyear3on4on\\">3 wide / 4 high</label><br /><input type=\\"radio\\" name=\\"calyear3on4\\" value=\\"0\\" id=\\"calyear3on4off\\" $calyear3on4off /> <label for=\\"calyear3on4off\\">4 wide / 3 high</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Start of the week:</b></span>
	<br />
	<span class=\\"smallfont\\">Choose the day of the week on which weeks start in your culture, to make your calendar appear correct.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\">
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
".(($hiveuser[cansharedevents] ) ? ("
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Allow other users to share events with me:</b></span>
	<br />
	<span class=\\"smallfont\\">If this option is enabled, other users will be able to share calendar events with you, and vice-versa. Your name will appear on the Userlist associated with that event (unless you disable that option below, or the event poster disables the public userlist option).</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"calsharesok\\" value=\\"1\\" id=\\"calsharesokon\\" $calsharesokon /> <label for=\\"calsharesokon\\">Yes</label><br /><input type=\\"radio\\" name=\\"calsharesok\\" value=\\"0\\" id=\\"calsharesokoff\\" $calsharesokoff /> <label for=\\"calsharesokoff\\">No</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Show my email address on the userlist of shared events</b></span>
	<br />
	<span class=\\"smallfont\\">If this option is enabled, other users will be able to see your email address on the public userlist for shared events that you are part of, unless the event poster disables the public userlist option.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"calshowmeonlist\\" value=\\"1\\" id=\\"calshowmeonliston\\" $calshowmeonliston /> <label for=\\"calshowmeonliston\\">Yes</label><br /><input type=\\"radio\\" name=\\"calshowmeonlist\\" value=\\"0\\" id=\\"calshowmeonlistoff\\" $calshowmeonlistoff /> <label for=\\"calshowmeonlistoff\\">No</label></span></td>
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
		</td>
	</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'calendar_table_daycell_big' => 
  array (
    'templategroupid' => '18',
    'user_data' => '<td align="left" onDblClick="document.location.href = \'calendar.display.php?cmd=day&date=$month-$day-$year\';" valign="top" style="padding: 10px; height: 65px; width: 100px;" class="<%if $thisweek %>high<%else%>normal<%endif%>$classType" <%if !$thisweek %>onMouseOver="this.className = \'high$classType\';" onMouseOut="this.className = \'normal$classType\';"<%endif%>><a href="calendar.display.php?cmd=day&date=$month-$day-$year" style="text-decoration: none;"><span class="normalfont" style="$style">$day</span></a>
<%if !empty($events) %>$events<%endif%></td>',
    'parsed_data' => '"<td align=\\"left\\" onDblClick=\\"document.location.href = \'calendar.display.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=day&date=$month-$day-$year\';\\" valign=\\"top\\" style=\\"padding: 10px; height: 65px; width: 100px;\\" class=\\"".(($thisweek ) ? ("high") : ("normal"))."$classType\\" ".((!$thisweek ) ? ("onMouseOver=\\"this.className = \'high$classType\';\\" onMouseOut=\\"this.className = \'normal$classType\';\\"") : (\'\'))."><a href=\\"calendar.display.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=day&date=$month-$day-$year\\" style=\\"text-decoration: none;\\"><span class=\\"normalfont\\" style=\\"$style\\">$day</span></a>
".((!empty($events) ) ? ("$events") : (\'\'))."</td>"',
    'upgraded' => '0',
  ),
  'calendar_table_daycell_eventbit' => 
  array (
    'templategroupid' => '18',
    'user_data' => '<li><span class="smallfont"><a title="$event[title]" href="calendar.event.php?eventid=$event[eventid]">$event[shorttitle]</a></li>',
    'parsed_data' => '"<li><span class=\\"smallfont\\"><a title=\\"$event[title]\\" href=\\"calendar.event.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}eventid=$event[eventid]\\">$event[shorttitle]</a></li>"',
    'upgraded' => '0',
  ),
  'calendar_table_daycell_small' => 
  array (
    'templategroupid' => '18',
    'user_data' => '<td align="center" onDblClick="document.location.href = \'calendar.display.php?cmd=day&date=$month-$day-$year\';" title="There are $eventstoday event(s) on this day" class="<%if $thisweek %>high<%else%>normal<%endif%>$classType" <%if !$thisweek %>onMouseOver="this.className = \'high$classType\';" onMouseOut="this.className = \'normal$classType\';"<%endif%>><a href="calendar.display.php?cmd=day&date=$month-$day-$year" style="text-decoration: none;"><span class="{$fontsize}font" style="$style">$day</span></a></td>',
    'parsed_data' => '"<td align=\\"center\\" onDblClick=\\"document.location.href = \'calendar.display.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=day&date=$month-$day-$year\';\\" title=\\"There are $eventstoday event(s) on this day\\" class=\\"".(($thisweek ) ? ("high") : ("normal"))."$classType\\" ".((!$thisweek ) ? ("onMouseOver=\\"this.className = \'high$classType\';\\" onMouseOut=\\"this.className = \'normal$classType\';\\"") : (\'\'))."><a href=\\"calendar.display.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=day&date=$month-$day-$year\\" style=\\"text-decoration: none;\\"><span class=\\"{$fontsize}font\\" style=\\"$style\\">$day</span></a></td>"',
    'upgraded' => '0',
  ),
  'calendar_table_endpad' => 
  array (
    'templategroupid' => '18',
    'user_data' => '<td <%if $bigview %>align="left" valign="top" style="padding: 10px;"<%else%>align="center"<%endif%> class="normal<%if $counter == (8 - $off) %>Right<%endif%>Cell"><span class="{$fontsize}font" style="color: #E1E1E1;">$counter</span></td>',
    'parsed_data' => '"<td ".(($bigview ) ? ("align=\\"left\\" valign=\\"top\\" style=\\"padding: 10px;\\"") : ("align=\\"center\\""))." class=\\"normal".(($counter == (8 - $off) ) ? ("Right") : (\'\'))."Cell\\"><span class=\\"{$fontsize}font\\" style=\\"color: #E1E1E1;\\">$counter</span></td>"',
    'upgraded' => '0',
  ),
  'calendar_table_startpad' => 
  array (
    'templategroupid' => '18',
    'user_data' => '<td <%if $bigview %>align="left" valign="top" style="padding: 10px;"<%else%>align="center"<%endif%> class="normal<%if $counter == 0 %>Left<%endif%>Cell"><span class="{$fontsize}font" style="color: #E1E1E1;">$prevday</span></td>',
    'parsed_data' => '"<td ".(($bigview ) ? ("align=\\"left\\" valign=\\"top\\" style=\\"padding: 10px;\\"") : ("align=\\"center\\""))." class=\\"normal".(($counter == 0 ) ? ("Left") : (\'\'))."Cell\\"><span class=\\"{$fontsize}font\\" style=\\"color: #E1E1E1;\\">$prevday</span></td>"',
    'upgraded' => '0',
  ),
  'calendar_table_weeknumber' => 
  array (
    'templategroupid' => '18',
    'user_data' => '<td class="highLeftCell" align="center"><span class="{$fontsize}font" style="color: #E1E1E1;">$weeknum</span></td>',
    'parsed_data' => '"<td class=\\"highLeftCell\\" align=\\"center\\"><span class=\\"{$fontsize}font\\" style=\\"color: #E1E1E1;\\">$weeknum</span></td>"',
    'upgraded' => '0',
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
			<tr class="highRow">
				<td class="highLeftCell" nowrap="nowrap" valign="top">
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
				<td class="highCell" nowrap="nowrap" valign="top">
					<table style="height: 40px;">
						<tr style="height: 50%;">
							<td nowrap="nowrap">
								<span class="normalfont">Event date:</span>
								&nbsp;<input type="text" name="fromdayname" class="highInactive" readonly="readonly" value="" size="12" />
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
				<td class="highRightCell" valign="top">
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
								<%if !getop(\'cal_use24\') %>
								<select name="fromampm" $timedisabled>
									<option value="am" $fromampmsel[am]>AM</option>
									<option value="pm" $fromampmsel[pm]>PM</option>
								</select>
								<%else%>
								<input type="hidden" name="fromampm" value="" />
								<%endif%>
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
			<tr class=\\"highRow\\">
				<td class=\\"highLeftCell\\" nowrap=\\"nowrap\\" valign=\\"top\\">
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
				<td class=\\"highCell\\" nowrap=\\"nowrap\\" valign=\\"top\\">
					<table style=\\"height: 40px;\\">
						<tr style=\\"height: 50%;\\">
							<td nowrap=\\"nowrap\\">
								<span class=\\"normalfont\\">Event date:</span>
								&nbsp;<input type=\\"text\\" name=\\"fromdayname\\" class=\\"highInactive\\" readonly=\\"readonly\\" value=\\"\\" size=\\"12\\" />
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
				<td class=\\"highRightCell\\" valign=\\"top\\">
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
								".((!getop(\'cal_use24\') ) ? ("
								<select name=\\"fromampm\\" $timedisabled>
									<option value=\\"am\\" $fromampmsel[am]>AM</option>
									<option value=\\"pm\\" $fromampmsel[pm]>PM</option>
								</select>
								") : ("
								<input type=\\"hidden\\" name=\\"fromampm\\" value=\\"\\" />
								"))."
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
    'upgraded' => '0',
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

// Checks if the spell checker can be used with this browser
function browserCompatible() {
	var ua = navigator.userAgent.toLowerCase(); 

	var isGecko = (ua.indexOf(\'gecko\') != -1);
	var isMozilla = (isGecko && ua.indexOf("gecko/") + 14 == ua.length);
	var isNS = (isGecko ? (ua.indexOf(\'netscape\') != -1) : (ua.indexOf(\'mozilla\') != -1 && (ua.indexOf(\'spoofer\') + ua.indexOf(\'compatible\') + ua.indexOf(\'opera\') + ua.indexOf(\'webtv\') + ua.indexOf(\'hotjava\')) == -5));
	var isIE = (ua.indexOf("msie") != -1 && ua.indexOf("opera") == -1 && ua.indexOf("webtv") == -1); 

	var versionMinor = parseFloat(navigator.appVersion); 
	if (isNS && isGecko) {
		versionMinor = parseFloat(ua.substring(ua.lastIndexOf(\'/\') + 1));
	} else if (isIE && versionMinor >= 4) {
		versionMinor = parseFloat(ua.substring(ua.indexOf(\'msie \') + 5));
	} else if (isMozilla) {
		versionMinor = parseFloat(ua.substring(ua.indexOf(\'rv:\') + 3));
	}
	var versionMajor = parseInt(versionMinor); 

	if (isMozilla || (isNS && versionMajor >= 6) || (isIE && versionMajor >= 5)) {
		return true;
	} else {
		return false;
	}
}

// Updates message with the spell checked text
function updateSpellChecked(str) {
	<%if $data[html] and $hiveuser[wysiwyg] %>
		document.all.idContent.content = str;
	<%else%>
		document.forms.composeform.tmessage.value = str;
	<%endif%>
}

// Opens spell checking window
function popIt() {
	if (browserCompatible()) {
		var n = window.open(\'about:blank\', \'formwin\', \'toolbar=no,menubar=no,scrollbars=yes,height=335,width=500,status=no\');
		return true;
	} else {
		alert(\'Spell Checker is only supported in Netscape 6.0+, IE 5.0+ and Mozilla\');
		return false;
	}
}

// Submits forms in the new window
function spellCheck(frm, doSend) {
	var origMeth;
	var origAction;
	var origTarget;
	var origCmd;
	var retVal = popIt();
	var rFrm;
	var rSend = (doSend ? \'1\' : \'0\');
	<%if $data[html] and $hiveuser[wysiwyg] %>
		document.composeform.message.value = document.all.idContent.content;
	<%endif%>
	if (retVal) {
		rFrm = eval(\'document.\'+frm);
		origMeth = rFrm.method;
		origAction = rFrm.action;
		origTarget = rFrm.target;
		origCmd = rFrm.cmd.value;
		rFrm.method = \'POST\';
		rFrm.action = \'compose.spell.php?cmd=procframeset&sendafter=\'+rSend;
		rFrm.target = \'formwin\';
		rFrm.cmd.value = \'procframeset\';
		rFrm.submit();
		rFrm.method = origMeth;
		rFrm.action = origAction;
		rFrm.target = origTarget;
		rFrm.cmd.value = origCmd;
	}
}

function sendMail(tform, skipSpell) {
	if (skipSpell != true && <%if $hiveuser[autospell] and $hiveuser[canspell] %>true<%else%>false<%endif%>) {
		spellCheck(\'composeform\', true);
	} else {
		tform.action = \'compose.send.php\';
		sumbitForm();
		tform.submit();
	}
}

function popAddBook () {
	var url = "addressbook.view.php?cmd=mini";
	url += "&pre[to]=" + escape (document.forms.composeform.to.value);
	url += "&pre[cc]=" + escape (document.forms.composeform.cc.value);
	url += "&pre[bcc]=" + escape (document.forms.composeform.bcc.value);
	var hWnd = window.open(url,"AddBook","width=530,height=465,resizable=yes,scrollbars=yes");
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
		<%if $hiveuser[canspell] %>
			idContent.useBtnSpellCheck = true;
		<%endif%>
		idContent.putBtnBreak(); //line break
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
	if (document.composeform.target == \'submitwindow$draftid\') {
		var tarWin = window.open(\'about:blank\', \'submitwindow$draftid\', \'toolbar=no,menubar=no,scrollbars=yes,height=225,width=425,status=no\');
		if (document.window != null && !tarWin.opener) {
			tarWin.opener = document.window;
		}
	}
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
<body onLoad="editorInit(); $focusfield">
$header

<form enctype="multipart/form-data" action="compose.email.php" name="composeform" method="post" onSubmit="sumbitForm();" target="submitwindow$draftid">
<input type="hidden" name="cmd" value="compose" />
<input type="hidden" name="save" value="1" />
<input type="hidden" name="draftid" value="$draftid" />
<input type="hidden" name="data[special]" value="$data[special]" />
<input type="hidden" name="message" value="" />
<input type="hidden" name="data[plainmessage]" value="" id="plainmessage" />
<input type="hidden" name="data[html]" value="$data[html]" id="usehtml" />
<input type="hidden" name="data[bgcolor]" value="" id="bgcolor" />
<input type="hidden" name="data[addedsig]" value="$data[addedsig]" id="addsig" />
<%if !$hiveuser[composereplyto] %>
<input type="hidden" name="data[replyto]" value="$data[replyto]" />
<%endif%>

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th colspan="2" class="headerBothCell"><span class="normalfonttablehead"><b>Send New Mail</b></span></th>
</tr>
<%if !$hiveuser[cansend] %>
<tr class="{newclassname}Row">
	<td class="{classname}BothCell" style="padding-right: 40px; text-align: center;" colspan="2"><span class="important">You do not have permission to send messages, this compose page is provided for demonstration purposes only.</span>
</tr>
<%endif%>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" style="padding-right: 40px;"><span class="normalfont"><b>From:</b></span></td>
	<td class="{classname}RightCell" style="width: 100%;">
		<select name="data[sendby]" style="width: 445px;">
			<option value="$hiveuser[username]" $defselected>$hiveuser[realname] &lt;$hiveuser[username]$hiveuser[domain]&gt;</option>
			<%if !empty($aliasoptions) %>
			<optgroup label="Aliases">
				$aliasoptions
			</optgroup>
			<%endif%>
			<%if !empty($popoptions) %>
			<optgroup label="POP3 Accounts">
				$popoptions
			</optgroup>
			<%endif%>
		</select>
	</td>
</tr>
<%if $hiveuser[composereplyto] %>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" style="padding-right: 40px;"><span class="normalfont"><b>Reply-To:</b></span></td>
	<td class="{classname}RightCell" style="width: 100%;"><input type="text" class="bginput" value="$data[replyto]" size="72" name="data[replyto]" /></span></td>
</tr>
<%endif%>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" style="padding-right: 40px;"><span class="normalfont"><b><a href="#" onClick="popAddBook(); return false;"><img src="$skin[images]/addbook.gif" alt="Address Book" border="0" /></a> To:</b></span></td>
	<td class="{classname}RightCell" style="width: 100%;"><input type="text" class="bginput" name="data[to]" value="$data[to]" size="72" autocomplete="off" onKeyUp="autoComplete(this, contacts);" id="to" tabindex="1" /></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" style="padding-right: 40px;"><span class="normalfont"><b><a href="#" onClick="popAddBook(); return false;"><img src="$skin[images]/addbook.gif" alt="Address Book" border="0" /></a> Cc:</b></span></td>
	<td class="{classname}RightCell" style="width: 100%;"><input type="text" class="bginput" name="data[cc]" value="$data[cc]" size="72" autocomplete="off" onKeyUp="autoComplete(this, contacts);" id="cc" /></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" style="padding-right: 40px;"><span class="normalfont"><b><a href="#" onClick="popAddBook(); return false;"><img src="$skin[images]/addbook.gif" alt="Address Book" border="0" /></a> Bcc:</b></span></td>
	<td class="{classname}RightCell" style="width: 100%;"><input type="text" class="bginput" name="data[bcc]" value="$data[bcc]" size="72" autocomplete="off" onKeyUp="autoComplete(this, contacts);" id="bcc" /></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" style="padding-right: 40px;"><span class="normalfont"><b>Subject:</b></span></td>
	<td class="{classname}RightCell" style="width: 100%;"><input type="text" class="bginput" value="$data[subject]" name="data[subject]" id="subject" size="72" tabindex="2" <%if $data[html] and 0 %>onBlur="idContent.InsertCustomHTML(\'\');"<%endif%> /></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}BothCell" colspan="2">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr valign="top">
				<td><%if $data[html] %><ACE:AdvContentEditor id="idContent" tabindex="3" /><%else%><textarea name="data[message]" style="width: 573px; height: 380px;" wrap="virtual" id="tmessage" tabindex="3">$data[message]</textarea><%endif%></td>
			</tr>
		</table>
	</td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" style="padding-right: 40px;" valign="top"><span class="normalfont"><b>Signatures:</b></span>
	<%if $hiveuser[cansendhtml] %><br /><br /><span class="smallfont"><a href="#" onClick="composeform.target = \'_self\'; sumbitForm(1); composeform.submit();">(Switch to $switchmode)</a></span><%endif%></td>
	<td class="{classname}RightCell" style="width: 100%;" valign="top"><span class="smallfont">Click the signature name below to insert it at the bottom of your message.<br />
	<%if empty($sigs) %>
	No signatures found. <a href="options.signature.php" target="_blank">Click here</a> to create a new signature.
	<%else%>
	$sigs
	<%endif%>
	</span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" style="padding-right: 40px;" valign="top"><span class="normalfont"><b>Options:</b></span></td>
	<td class="{classname}RightCell" style="width: 100%;" valign="top">
		<table cellspacing="0">
			<tr>
				<td><input type="checkbox" name="data[savecopy]" value="1" id="savecopy" $savecopychecked /></td>
				<td><span class="smallfont"><label for="savecopy"><b>Save a copy:</b> Also save a copy in the Sent Items folder.</label></span></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="data[requestread]" value="1" id="requestread" $requestreadchecked /></td>
				<td><span class="smallfont"><label for="requestread"><b>Request read receipt:</b> Be notified when the receiver reads the message.</label></span></td>
			</tr>
			<%if !empty($data[\'special\']) %>
			<tr>
				<td><input type="checkbox" name="data[deleteorig]" value="1" id="deleteorig" $deleteorigchecked /></td>
				<td><span class="smallfont"><label for="deleteorig"><b>Delete original message:</b> After this message is sent the original email will be deleted.</label></span></td>
			</tr>
			<%else%>
				<input type="hidden" name="data[deleteorig]" value="0" />
			<%endif%>
			<%if !$toomanycontacts%>
			<tr>
				<td><input type="checkbox" name="data[addtobook]" value="1" id="addtobook" $addtobookchecked /></td>
				<td><span class="smallfont"><label for="addtobook"><b>Add recipients to address book:</b> Automatically add all recipients of<br />this email to your address book after you send this message.</label></span></td>
			</tr>
			<%else%>
				<input type="hidden" name="data[addtobook]" value="0" />
			<%endif%>
		</table>
	</td>
</tr>
<%if $hiveuser[canattach] %>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" style="padding-right: 40px;" valign="top"><span class="normalfont"><b>Attachments:</b></span></td>
	<td class="{classname}RightCell" style="width: 100%;" valign="top"><span class="normalfont">
	<%if !empty($attachlist) %>
	$attachlist
	<%else%>
	No attachments.<br />
	<%endif%>
	<br /><input type="button" class="bginput" name="manageattach" value="Manage Attachments" onClick="var attWnd = window.open(\'compose.attachments.php?draftid=$draftid\',\'manageattach\',\'statusbar=no,menubar=no,toolbar=no,scrollbars=yes,width=480,height=425\'); return false;" />
	</span></td>
</tr>
<%endif%>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" style="padding-right: 40px;"><span class="normalfont"><b>Priority:</b></span></td>
	<td class="{classname}RightCell" style="width: 100%;">
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
	<input type="submit" class="bginput" name="send" value="Send Email" onClick="sendMail(this.form); return false;" accesskey="s" tabindex="4" />
	<%if $hiveuser[canspell] %>
	<input type="submit" class="bginput" name="spellcheck" value="Spell Check" onClick="spellCheck(\'composeform\'); return false;" />
	<%endif%>
	<input type="submit" class="bginput" name="cancel" value="Cancel" onClick="window.location = \'{<INDEX_FILE>}\'; return false;" /> 
	<%if isset($draft) and $draft[\'dateline\'] == 0 %>
	<input type="submit" class="bginput" name="updatedraft" value="Update Draft" onClick="this.form.action=\'compose.draft.php\'; this.form.target = \'_self\'; return true;" />
	<%endif%>
	<input type="submit" class="bginput" name="draft" value="<%if isset($draft) and $draft[\'dateline\'] == 0 %>Remove Draft<%else%>Save as Draft<%endif%>" onClick="this.form.action=\'compose.draft.php\'; this.form.target = \'_self\'; return true;" />
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

// Checks if the spell checker can be used with this browser
function browserCompatible() {
	var ua = navigator.userAgent.toLowerCase(); 

	var isGecko = (ua.indexOf(\'gecko\') != -1);
	var isMozilla = (isGecko && ua.indexOf(\\"gecko/\\") + 14 == ua.length);
	var isNS = (isGecko ? (ua.indexOf(\'netscape\') != -1) : (ua.indexOf(\'mozilla\') != -1 && (ua.indexOf(\'spoofer\') + ua.indexOf(\'compatible\') + ua.indexOf(\'opera\') + ua.indexOf(\'webtv\') + ua.indexOf(\'hotjava\')) == -5));
	var isIE = (ua.indexOf(\\"msie\\") != -1 && ua.indexOf(\\"opera\\") == -1 && ua.indexOf(\\"webtv\\") == -1); 

	var versionMinor = parseFloat(navigator.appVersion); 
	if (isNS && isGecko) {
		versionMinor = parseFloat(ua.substring(ua.lastIndexOf(\'/\') + 1));
	} else if (isIE && versionMinor >= 4) {
		versionMinor = parseFloat(ua.substring(ua.indexOf(\'msie \') + 5));
	} else if (isMozilla) {
		versionMinor = parseFloat(ua.substring(ua.indexOf(\'rv:\') + 3));
	}
	var versionMajor = parseInt(versionMinor); 

	if (isMozilla || (isNS && versionMajor >= 6) || (isIE && versionMajor >= 5)) {
		return true;
	} else {
		return false;
	}
}

// Updates message with the spell checked text
function updateSpellChecked(str) {
	".(($data[html] and $hiveuser[wysiwyg] ) ? ("
		document.all.idContent.content = str;
	") : ("
		document.forms.composeform.tmessage.value = str;
	"))."
}

// Opens spell checking window
function popIt() {
	if (browserCompatible()) {
		var n = window.open(\'about:blank\', \'formwin\', \'toolbar=no,menubar=no,scrollbars=yes,height=335,width=500,status=no\');
		return true;
	} else {
		alert(\'Spell Checker is only supported in Netscape 6.0+, IE 5.0+ and Mozilla\');
		return false;
	}
}

// Submits forms in the new window
function spellCheck(frm, doSend) {
	var origMeth;
	var origAction;
	var origTarget;
	var origCmd;
	var retVal = popIt();
	var rFrm;
	var rSend = (doSend ? \'1\' : \'0\');
	".(($data[html] and $hiveuser[wysiwyg] ) ? ("
		document.composeform.message.value = document.all.idContent.content;
	") : (\'\'))."
	if (retVal) {
		rFrm = eval(\'document.\'+frm);
		origMeth = rFrm.method;
		origAction = rFrm.action;
		origTarget = rFrm.target;
		origCmd = rFrm.cmd.value;
		rFrm.method = \'POST\';
		rFrm.action = \'compose.spell.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=procframeset&sendafter=\'+rSend;
		rFrm.target = \'formwin\';
		rFrm.cmd.value = \'procframeset\';
		rFrm.submit();
		rFrm.method = origMeth;
		rFrm.action = origAction;
		rFrm.target = origTarget;
		rFrm.cmd.value = origCmd;
	}
}

function sendMail(tform, skipSpell) {
	if (skipSpell != true && ".(($hiveuser[autospell] and $hiveuser[canspell] ) ? ("true") : ("false")).") {
		spellCheck(\'composeform\', true);
	} else {
		tform.action = \'compose.send.php{$GLOBALS[session_url]}\';
		sumbitForm();
		tform.submit();
	}
}

function popAddBook () {
	var url = \\"addressbook.view.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=mini\\";
	url += \\"&pre[to]=\\" + escape (document.forms.composeform.to.value);
	url += \\"&pre[cc]=\\" + escape (document.forms.composeform.cc.value);
	url += \\"&pre[bcc]=\\" + escape (document.forms.composeform.bcc.value);
	var hWnd = window.open(url,\\"AddBook\\",\\"width=530,height=465,resizable=yes,scrollbars=yes\\");
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
		".(($hiveuser[canspell] ) ? ("
			idContent.useBtnSpellCheck = true;
		") : (\'\'))."
		idContent.putBtnBreak(); //line break
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
	if (document.composeform.target == \'submitwindow$draftid\') {
		var tarWin = window.open(\'about:blank\', \'submitwindow$draftid\', \'toolbar=no,menubar=no,scrollbars=yes,height=225,width=425,status=no\');
		if (document.window != null && !tarWin.opener) {
			tarWin.opener = document.window;
		}
	}
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
<body onLoad=\\"editorInit(); $focusfield\\">
$GLOBALS[header]

<form enctype=\\"multipart/form-data\\" action=\\"compose.email.php{$GLOBALS[session_url]}\\" name=\\"composeform\\" method=\\"post\\" onSubmit=\\"sumbitForm();\\" target=\\"submitwindow$draftid\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"compose\\" />
<input type=\\"hidden\\" name=\\"save\\" value=\\"1\\" />
<input type=\\"hidden\\" name=\\"draftid\\" value=\\"$draftid\\" />
<input type=\\"hidden\\" name=\\"data[special]\\" value=\\"$data[special]\\" />
<input type=\\"hidden\\" name=\\"message\\" value=\\"\\" />
<input type=\\"hidden\\" name=\\"data[plainmessage]\\" value=\\"\\" id=\\"plainmessage\\" />
<input type=\\"hidden\\" name=\\"data[html]\\" value=\\"$data[html]\\" id=\\"usehtml\\" />
<input type=\\"hidden\\" name=\\"data[bgcolor]\\" value=\\"\\" id=\\"bgcolor\\" />
<input type=\\"hidden\\" name=\\"data[addedsig]\\" value=\\"$data[addedsig]\\" id=\\"addsig\\" />
".((!$hiveuser[composereplyto] ) ? ("
<input type=\\"hidden\\" name=\\"data[replyto]\\" value=\\"$data[replyto]\\" />
") : (\'\'))."

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th colspan=\\"2\\" class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Send New Mail</b></span></th>
</tr>
".((!$hiveuser[cansend] ) ? ("
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\" style=\\"padding-right: 40px; text-align: center;\\" colspan=\\"2\\"><span class=\\"important\\">You do not have permission to send messages, this compose page is provided for demonstration purposes only.</span>
</tr>
") : (\'\'))."
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" style=\\"padding-right: 40px;\\"><span class=\\"normalfont\\"><b>From:</b></span></td>
	<td class=\\"{classname}RightCell\\" style=\\"width: 100%;\\">
		<select name=\\"data[sendby]\\" style=\\"width: 445px;\\">
			<option value=\\"$hiveuser[username]\\" $defselected>$hiveuser[realname] &lt;$hiveuser[username]$hiveuser[domain]&gt;</option>
			".((!empty($aliasoptions) ) ? ("
			<optgroup label=\\"Aliases\\">
				$aliasoptions
			</optgroup>
			") : (\'\'))."
			".((!empty($popoptions) ) ? ("
			<optgroup label=\\"POP3 Accounts\\">
				$popoptions
			</optgroup>
			") : (\'\'))."
		</select>
	</td>
</tr>
".(($hiveuser[composereplyto] ) ? ("
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" style=\\"padding-right: 40px;\\"><span class=\\"normalfont\\"><b>Reply-To:</b></span></td>
	<td class=\\"{classname}RightCell\\" style=\\"width: 100%;\\"><input type=\\"text\\" class=\\"bginput\\" value=\\"$data[replyto]\\" size=\\"72\\" name=\\"data[replyto]\\" /></span></td>
</tr>
") : (\'\'))."
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" style=\\"padding-right: 40px;\\"><span class=\\"normalfont\\"><b><a href=\\"#\\" onClick=\\"popAddBook(); return false;\\"><img src=\\"{$GLOBALS[skin][images]}/addbook.gif\\" alt=\\"Address Book\\" border=\\"0\\" /></a> To:</b></span></td>
	<td class=\\"{classname}RightCell\\" style=\\"width: 100%;\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"data[to]\\" value=\\"$data[to]\\" size=\\"72\\" autocomplete=\\"off\\" onKeyUp=\\"autoComplete(this, contacts);\\" id=\\"to\\" tabindex=\\"1\\" /></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" style=\\"padding-right: 40px;\\"><span class=\\"normalfont\\"><b><a href=\\"#\\" onClick=\\"popAddBook(); return false;\\"><img src=\\"{$GLOBALS[skin][images]}/addbook.gif\\" alt=\\"Address Book\\" border=\\"0\\" /></a> Cc:</b></span></td>
	<td class=\\"{classname}RightCell\\" style=\\"width: 100%;\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"data[cc]\\" value=\\"$data[cc]\\" size=\\"72\\" autocomplete=\\"off\\" onKeyUp=\\"autoComplete(this, contacts);\\" id=\\"cc\\" /></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" style=\\"padding-right: 40px;\\"><span class=\\"normalfont\\"><b><a href=\\"#\\" onClick=\\"popAddBook(); return false;\\"><img src=\\"{$GLOBALS[skin][images]}/addbook.gif\\" alt=\\"Address Book\\" border=\\"0\\" /></a> Bcc:</b></span></td>
	<td class=\\"{classname}RightCell\\" style=\\"width: 100%;\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"data[bcc]\\" value=\\"$data[bcc]\\" size=\\"72\\" autocomplete=\\"off\\" onKeyUp=\\"autoComplete(this, contacts);\\" id=\\"bcc\\" /></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" style=\\"padding-right: 40px;\\"><span class=\\"normalfont\\"><b>Subject:</b></span></td>
	<td class=\\"{classname}RightCell\\" style=\\"width: 100%;\\"><input type=\\"text\\" class=\\"bginput\\" value=\\"$data[subject]\\" name=\\"data[subject]\\" id=\\"subject\\" size=\\"72\\" tabindex=\\"2\\" ".(($data[html] and 0 ) ? ("onBlur=\\"idContent.InsertCustomHTML(\'\');\\"") : (\'\'))." /></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\" colspan=\\"2\\">
		<table cellpadding=\\"0\\" cellspacing=\\"0\\" border=\\"0\\">
			<tr valign=\\"top\\">
				<td>".(($data[html] ) ? ("<ACE:AdvContentEditor id=\\"idContent\\" tabindex=\\"3\\" />") : ("<textarea name=\\"data[message]\\" style=\\"width: 573px; height: 380px;\\" wrap=\\"virtual\\" id=\\"tmessage\\" tabindex=\\"3\\">$data[message]</textarea>"))."</td>
			</tr>
		</table>
	</td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" style=\\"padding-right: 40px;\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Signatures:</b></span>
	".(($hiveuser[cansendhtml] ) ? ("<br /><br /><span class=\\"smallfont\\"><a href=\\"#\\" onClick=\\"composeform.target = \'_self\'; sumbitForm(1); composeform.submit();\\">(Switch to $switchmode)</a></span>") : (\'\'))."</td>
	<td class=\\"{classname}RightCell\\" style=\\"width: 100%;\\" valign=\\"top\\"><span class=\\"smallfont\\">Click the signature name below to insert it at the bottom of your message.<br />
	".((empty($sigs) ) ? ("
	No signatures found. <a href=\\"options.signature.php{$GLOBALS[session_url]}\\" target=\\"_blank\\">Click here</a> to create a new signature.
	") : ("
	$sigs
	"))."
	</span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" style=\\"padding-right: 40px;\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Options:</b></span></td>
	<td class=\\"{classname}RightCell\\" style=\\"width: 100%;\\" valign=\\"top\\">
		<table cellspacing=\\"0\\">
			<tr>
				<td><input type=\\"checkbox\\" name=\\"data[savecopy]\\" value=\\"1\\" id=\\"savecopy\\" $savecopychecked /></td>
				<td><span class=\\"smallfont\\"><label for=\\"savecopy\\"><b>Save a copy:</b> Also save a copy in the Sent Items folder.</label></span></td>
			</tr>
			<tr>
				<td><input type=\\"checkbox\\" name=\\"data[requestread]\\" value=\\"1\\" id=\\"requestread\\" $requestreadchecked /></td>
				<td><span class=\\"smallfont\\"><label for=\\"requestread\\"><b>Request read receipt:</b> Be notified when the receiver reads the message.</label></span></td>
			</tr>
			".((!empty($data[\'special\']) ) ? ("
			<tr>
				<td><input type=\\"checkbox\\" name=\\"data[deleteorig]\\" value=\\"1\\" id=\\"deleteorig\\" $deleteorigchecked /></td>
				<td><span class=\\"smallfont\\"><label for=\\"deleteorig\\"><b>Delete original message:</b> After this message is sent the original email will be deleted.</label></span></td>
			</tr>
			") : ("
				<input type=\\"hidden\\" name=\\"data[deleteorig]\\" value=\\"0\\" />
			"))."
			".((!$toomanycontacts) ? ("
			<tr>
				<td><input type=\\"checkbox\\" name=\\"data[addtobook]\\" value=\\"1\\" id=\\"addtobook\\" $addtobookchecked /></td>
				<td><span class=\\"smallfont\\"><label for=\\"addtobook\\"><b>Add recipients to address book:</b> Automatically add all recipients of<br />this email to your address book after you send this message.</label></span></td>
			</tr>
			") : ("
				<input type=\\"hidden\\" name=\\"data[addtobook]\\" value=\\"0\\" />
			"))."
		</table>
	</td>
</tr>
".(($hiveuser[canattach] ) ? ("
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" style=\\"padding-right: 40px;\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Attachments:</b></span></td>
	<td class=\\"{classname}RightCell\\" style=\\"width: 100%;\\" valign=\\"top\\"><span class=\\"normalfont\\">
	".((!empty($attachlist) ) ? ("
	$attachlist
	") : ("
	No attachments.<br />
	"))."
	<br /><input type=\\"button\\" class=\\"bginput\\" name=\\"manageattach\\" value=\\"Manage Attachments\\" onClick=\\"var attWnd = window.open(\'compose.attachments.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}draftid=$draftid\',\'manageattach\',\'statusbar=no,menubar=no,toolbar=no,scrollbars=yes,width=480,height=425\'); return false;\\" />
	</span></td>
</tr>
") : (\'\'))."
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" style=\\"padding-right: 40px;\\"><span class=\\"normalfont\\"><b>Priority:</b></span></td>
	<td class=\\"{classname}RightCell\\" style=\\"width: 100%;\\">
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
	<input type=\\"submit\\" class=\\"bginput\\" name=\\"send\\" value=\\"Send Email\\" onClick=\\"sendMail(this.form); return false;\\" accesskey=\\"s\\" tabindex=\\"4\\" />
	".(($hiveuser[canspell] ) ? ("
	<input type=\\"submit\\" class=\\"bginput\\" name=\\"spellcheck\\" value=\\"Spell Check\\" onClick=\\"spellCheck(\'composeform\'); return false;\\" />
	") : (\'\'))."
	<input type=\\"submit\\" class=\\"bginput\\" name=\\"cancel\\" value=\\"Cancel\\" onClick=\\"window.location = \'".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}\'; return false;\\" /> 
	".((isset($draft) and $draft[\'dateline\'] == 0 ) ? ("
	<input type=\\"submit\\" class=\\"bginput\\" name=\\"updatedraft\\" value=\\"Update Draft\\" onClick=\\"this.form.action=\'compose.draft.php{$GLOBALS[session_url]}\'; this.form.target = \'_self\'; return true;\\" />
	") : (\'\'))."
	<input type=\\"submit\\" class=\\"bginput\\" name=\\"draft\\" value=\\"".((isset($draft) and $draft[\'dateline\'] == 0 ) ? ("Remove Draft") : ("Save as Draft"))."\\" onClick=\\"this.form.action=\'compose.draft.php{$GLOBALS[session_url]}\'; this.form.target = \'_self\'; return true;\\" />
	</td>
</tr>
</form>
</table>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'compose_attachbit' => 
  array (
    'templategroupid' => '3',
    'user_data' => '	$attachdata[filename] ($attachdata[size] bytes)<br />
',
    'parsed_data' => '"	$attachdata[filename] ($attachdata[size] bytes)<br />
"',
    'upgraded' => '0',
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
<input type="button" value=" Done " class="bginput" onclick="opener.document.composeform.target = \'_self\'; opener.sumbitForm(0, 1); opener.document.composeform.submit(); window.close();" />
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
	<span class=\\"smallfont\\">".(($hiveuser[maxattach] > 0 ) ? ("You are allowed to attach up to $hiveuser[maxattach]MB worth of files to this message.<br />") : (\'\'))."
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
<input type=\\"button\\" value=\\" Done \\" class=\\"bginput\\" onclick=\\"opener.document.composeform.target = \'_self\'; opener.sumbitForm(0, 1); opener.document.composeform.submit(); window.close();\\" />
</center>
</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'compose_manageattach_added' => 
  array (
    'templategroupid' => '3',
    'user_data' => 'The attachment was <b>successfully added</b>.',
    'parsed_data' => '"The attachment was <b>successfully added</b>."',
    'upgraded' => '0',
  ),
  'compose_manageattach_attachbit' => 
  array (
    'templategroupid' => '3',
    'user_data' => '	$attachdata[filename] ($attachdata[size] bytes) <input type="submit" class="bginput" name="delete$number" value="Delete" /><br />
',
    'parsed_data' => '"	$attachdata[filename] ($attachdata[size] bytes) <input type=\\"submit\\" class=\\"bginput\\" name=\\"delete$number\\" value=\\"Delete\\" /><br />
"',
    'upgraded' => '0',
  ),
  'compose_manageattach_error' => 
  array (
    'templategroupid' => '3',
    'user_data' => 'The attachment was <b>not added</b> due to an error. Please try again.',
    'parsed_data' => '"The attachment was <b>not added</b> due to an error. Please try again."',
    'upgraded' => '0',
  ),
  'compose_manageattach_error_duplicate' => 
  array (
    'templategroupid' => '3',
    'user_data' => 'The attachment was <b>not added</b> as it is already attached to this message.',
    'parsed_data' => '"The attachment was <b>not added</b> as it is already attached to this message."',
    'upgraded' => '0',
  ),
  'compose_manageattach_error_toobig' => 
  array (
    'templategroupid' => '3',
    'user_data' => 'The attachment was <b>not added</b> due as it is too large. You are only allowed to attach up to $hiveuser[maxattach]MB.',
    'parsed_data' => '"The attachment was <b>not added</b> due as it is too large. You are only allowed to attach up to $hiveuser[maxattach]MB."',
    'upgraded' => '0',
  ),
  'compose_manageattach_removed' => 
  array (
    'templategroupid' => '3',
    'user_data' => 'The attachment was <b>successfully removed</b>.',
    'parsed_data' => '"The attachment was <b>successfully removed</b>."',
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'compose_spell_loading' => 
  array (
    'templategroupid' => '3',
    'user_data' => '$skin[doctype]
<html>
<head>
<title>$appname: Spell Checker</title>
$css
<script language="Javascript">
<!--

function js_dots() {
	getElement(\'dotproc\').innerText += \'.\';
	jstimer = setTimeout(\'js_dots();\', 100);}

// -->
</script>
</head>
<body style="background-color: #C7E1F4;" onload="js_dots();">

$header

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Spell Checker</b></span></th>
</tr>
</table>

<table align="center" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td style="padding: 10px" width="75%" colspan="2">
			<span id="dotproc" class="normalfont">Loading</span>
			<span class="normalfont">
				<br />&nbsp;<br />&nbsp;<br />&nbsp;
				<br />&nbsp;<br />&nbsp;<br />&nbsp;
				<br />&nbsp;<br />&nbsp;<br />&nbsp;
				<br />&nbsp;<br />&nbsp;<br />&nbsp;
				<br />&nbsp;<br />&nbsp;
			</span>
		</td>
	</tr>
</table>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head>
<title>$appname: Spell Checker</title>
$GLOBALS[css]
<script language=\\"Javascript\\">
<!--

function js_dots() {
	getElement(\'dotproc\').innerText += \'.\';
	jstimer = setTimeout(\'js_dots();\', 100);}

// -->
</script>
</head>
<body style=\\"background-color: #C7E1F4;\\" onload=\\"js_dots();\\">

$GLOBALS[header]

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Spell Checker</b></span></th>
</tr>
</table>

<table align=\\"center\\" width=\\"100%\\" cellspacing=\\"0\\" cellpadding=\\"0\\">
	<tr>
		<td style=\\"padding: 10px\\" width=\\"75%\\" colspan=\\"2\\">
			<span id=\\"dotproc\\" class=\\"normalfont\\">Loading</span>
			<span class=\\"normalfont\\">
				<br />&nbsp;<br />&nbsp;<br />&nbsp;
				<br />&nbsp;<br />&nbsp;<br />&nbsp;
				<br />&nbsp;<br />&nbsp;<br />&nbsp;
				<br />&nbsp;<br />&nbsp;<br />&nbsp;
				<br />&nbsp;<br />&nbsp;
			</span>
		</td>
	</tr>
</table>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'compose_spell_noerrors' => 
  array (
    'templategroupid' => '3',
    'user_data' => '$skin[doctype]
<html>
<head>
<title>$appname: Spell Checker</title>
$css
</head>
<body style="background-color: #C7E1F4;" onload="js_dots();">

$header

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Spell Checker</b></span></th>
</tr>
</table>

<table align="center" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td style="padding: 10px" width="75%" colspan="2">
			<span id="dotproc" class="normalfont">The spelling check is complete, no errors have been found.</span>
			<span class="normalfont">
				<br />&nbsp;<br />&nbsp;<br />&nbsp;
				<br />&nbsp;<br />&nbsp;<br />&nbsp;
				<br />&nbsp;<br />&nbsp;<br />&nbsp;
				<br />&nbsp;<br />&nbsp;<br />&nbsp;
				<br />&nbsp;<br />&nbsp;
			</span>
		</td>
	</tr>
</table>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head>
<title>$appname: Spell Checker</title>
$GLOBALS[css]
</head>
<body style=\\"background-color: #C7E1F4;\\" onload=\\"js_dots();\\">

$GLOBALS[header]

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Spell Checker</b></span></th>
</tr>
</table>

<table align=\\"center\\" width=\\"100%\\" cellspacing=\\"0\\" cellpadding=\\"0\\">
	<tr>
		<td style=\\"padding: 10px\\" width=\\"75%\\" colspan=\\"2\\">
			<span id=\\"dotproc\\" class=\\"normalfont\\">The spelling check is complete, no errors have been found.</span>
			<span class=\\"normalfont\\">
				<br />&nbsp;<br />&nbsp;<br />&nbsp;
				<br />&nbsp;<br />&nbsp;<br />&nbsp;
				<br />&nbsp;<br />&nbsp;<br />&nbsp;
				<br />&nbsp;<br />&nbsp;<br />&nbsp;
				<br />&nbsp;<br />&nbsp;
			</span>
		</td>
	</tr>
</table>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'compose_spell_suggestions' => 
  array (
    'templategroupid' => '3',
    'user_data' => '$skin[doctype]
<html>
<head>
<title>$appname: Spell Checker</title>
$css
<script language="Javascript">
<!--

var currNum=0;
var intTop;
var proDoc = window.top.process;
var userDictAvail = true;
var userDict = new Array();
var ignoreAllWords = new Array();
var changeAllWordsOriginal = new Array();
var changeAllWordsReplace = new Array();
var comMistakesOriginal = new Array();
var comMistakesReplace = new Array();
var lastAction = \'ignore\';

function removeWord(word) {
	for (var i = 0; i < userDict.length; i++) {
		if (userDict[i] == word) {
			userDict[i] = \'\';
		}
	}
	return;
}

function writeMe(strRepVal, wordNum) {       
	var wDoc = self.document;
	var eDiv = document.all ? wDoc.all[\'context\'] : wDoc.getElementById(\'context\');
	eDiv.innerHTML = strRepVal;
	var fDiv = document.all ? wDoc.all[\'wordnum\'] : wDoc.getElementById(\'wordnum\');
	fDiv.innerHTML = \'Word <b>\'+wordNum+\'</b> out of <b>\'+proDoc.getMisspelledCount()+\'</b>:\';
	return;
}

function startSpell() {
	var strFirstWord = getCurrentWord();
	intTop = proDoc.getMisspelledCount();
	setContext();
	setSelectList();
	return true;
}

function setContext() {
	writeMe(proDoc.getContext(currNum), currNum + 1);
}

function getCurrentWord() {
	return proDoc.getWord(currNum);
}

function isWordCapitalized() {
	var word = proDoc.getWord(currNum);
	var c = word.charAt(0);
	if (c.toUpperCase() == c) {
		return true;
	} else {
		return false;
	}
}

function capitalizeSuggs(sAr) {
	var c = sAr.length;
	for (x = 0; x < c; x++) {
		sStr = sAr[x];
		sC = sStr.charAt(0);
		sC = sC.toUpperCase();
		var newstr = sC + sStr.substr(1, sStr.length - 1);
		sAr[x] = newstr;
	}
	return sAr;
}

function setSelectList() {
	var sel = self.document.sugform.suggest_list;
	var sugAr = new Array();
	sel.length = 0;
	sugAr = proDoc.getSuggList(currNum);
	if (sugAr.length > 0) {
		if (isWordCapitalized()) {
			sugAr = capitalizeSuggs(sugAr);
		}
		for (x = 0; x < sugAr.length; x++) {
			var newOpt = new Option(sugAr[x], x);
			sel.options.length++;
			sel.options[sel.length - 1].text  = newOpt.text;
			sel.options[sel.length - 1].value = newOpt.value;
		}
		//sel.options[0].selected = true;
		document.sugform.new_word.value = sel.options[0].text;
		sel.disabled = false;
	} else {
		sel.options[sel.options.length] = new Option(\'(no suggestions)\', \'(no suggestions)\');
		sel.disabled = true;
		document.sugform.new_word.value = getCurrentWord();
	}
	document.sugform.new_word.focus();
	setSelectionRange(document.sugform.new_word, 0, document.sugform.new_word.value.length);
}

function suggest_list_change(sel) {
	self.document.sugform.new_word.value = sel.options[sel.options.selectedIndex].text;
}

function undoLast() {
	currNum--;
	if (lastAction == \'change\') {
		var oi = proDoc.getOi(currNum);
		var retVal = proDoc.setOrigWord(currNum, proDoc.getLastOrigWord());
		var where = in_array(changeAllWordsOriginal, proDoc.getLastOrigWord().toLowerCase());
		if (where) {
			changeAllWordsOriginal[where - 1] = \'\';
			changeAllWordsReplace[where - 1] = \'\';
		}
		var where = in_array(comMistakesOriginal, proDoc.getLastOrigWord().toLowerCase());
		if (where) {
			comMistakesOriginal[where - 1] = \'\';
			comMistakesReplace[where - 1] = \'\';
		}
	} else if (lastAction == \'ignore\' || lastAction == \'add\') {
		if (lastAction == \'add\') {
			removeWord(getCurrentWord());
		}
		var where = in_array(ignoreAllWords, getCurrentWord().toLowerCase());
		if (where) {
			ignoreAllWords[where - 1] = \'\';
		}
	}
	currNum--;
	nextWord();
	disableUndo(true);
}

function changeWord() {
	lastAction = \'change\';
	var oi = proDoc.getOi(currNum);
	var nw = self.document.sugform.new_word.value;
	var retVal = proDoc.setNewWord(currNum, nw);
	comMistakesOriginal[comMistakesOriginal.length] = getCurrentWord().toLowerCase();
	comMistakesReplace[comMistakesReplace.length] = self.document.sugform.new_word.value;
	nextWord();
}

function changeAllWord() {
	changeAllWordsOriginal[changeAllWordsOriginal.length] = getCurrentWord().toLowerCase();
	changeAllWordsReplace[changeAllWordsReplace.length] = self.document.sugform.new_word.value;
	changeWord();
}

function ignoreAllWord() {
	lastAction = \'ignore\';
	ignoreAllWords[ignoreAllWords.length] = getCurrentWord();
	nextWord();
}

function nextWord() {
	if (!proDoc.isEnd(currNum)) {
		currNum++;
		setContext();
		setSelectList();

		// Automatically ignore or change certain words
		if (in_array(ignoreAllWords, getCurrentWord().toLowerCase())) {
			nextWord();
		} else {
			var where = in_array(changeAllWordsOriginal, getCurrentWord().toLowerCase());
			if (where) {
				var retVal = proDoc.setNewWord(currNum, changeAllWordsReplace[where - 1]);
				changeWord();
			}
		}
		disableUndo(false);
	} else {
		finishChecking();
	}
}

// Like the PHP function, except that the key is incremented by 1!!!
function in_array(thearray, match) {
	for (var i = 0; i < thearray.length; i++) {
		if (thearray[i] == match) {
			return i + 1;
		}
	}
	return false;
}

function finishChecking() {
	// Still go through all words, if the user pressed Change All
	if (changeAllWordsOriginal.length > 0) {
		while (!proDoc.isEnd(currNum)) {
			nextWord();
		}
	}
	disableUndo(true);

	var strConcat = "";
	var oAr = new Array();
	oAr = proDoc.getOrigAr();
	var oArLen = oAr.length;
	for (x = 0; x < oArLen; x++) {
		if (typeof(oAr[x]) == \'undefined\') {
			continue;
		}
		strConcat += oAr[x];
	}
	if (window.top.opener && !window.top.opener.closed) {
		window.top.opener.updateSpellChecked(strConcat);
		<%if $sendafter %>
			window.top.opener.sendMail(window.top.opener.document.composeform, true);
		<%endif%>
	} else {
		//alert(\'The window containing your original data has been closed.\');
	}
	if (userDict.length > 0 || comMistakesOriginal.length > 0) {
		submitDict();
	} else {
		closeMe();
	}
}

function submitDict() {
	alert(\'New words in dictionary: \' + userDict.join(\'\\n\') + \'\\nWords that were changed: \' + comMistakesOriginal.join(\'\\n\') + \'\\nWords that came instead: \' + comMistakesReplace.join(\'\\n\'));
	document.sugform.newWords.value = userDict.join(\'\\n\');
	document.sugform.changedFrom.value = comMistakesOriginal.join(\'\\n\');
	document.sugform.changedTo.value = comMistakesReplace.join(\'\\n\');
	document.sugform.submit();             
	return;
}

function closeMe() {
	window.top.window.close();
}

function cancelChecking() {
	<%if $sendafter %>
		if (confirm(\'The spell check on the message was halted. Do you want to send anyway?\')) {
			window.top.opener.sendMail(window.top.opener.document.composeform, true);
		}
	<%endif%>
	closeMe();
}

function disableUndo(state) {
	self.document.sugform.undolast.disabled = state;
}

function addWord() {
	userDict[userDict.length] = getCurrentWord();
	ignoreAllWord();
	lastAction = \'add\';
	disableUndo(true);
}

function setSelectionRange(input, selectionStart, selectionEnd) {
	if (input.setSelectionRange) {
		input.focus();
		input.setSelectionRange(selectionStart, selectionEnd);
	} else if (input.createTextRange) {
		var range = input.createTextRange();
		range.collapse(true);
		range.moveEnd(\'character\', selectionEnd);
		range.moveStart(\'character\', selectionStart);
		range.select();
	}
}

//-->
</script>
</head>
<body style="background-color: #C7E1F4;" onload="startSpell();">

$header

<form action="compose.spell.php" name="sugform" method="post">
<input type="hidden" name="cmd" value="updatedata">
<input type="hidden" name="newWords" value="">
<input type="hidden" name="changedFrom" value="">
<input type="hidden" name="changedTo" value="">

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Spell Checker</b></span></th>
</tr>
</table>

<table align="center" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td style="padding: 2px; padding-top: 10px" width="75%" colspan="2"><div id="wordnum" class="normalfont" style="height: 14px; width: 98%"></div></td>
	</tr>
	<tr>
		<td style="padding: 2px; padding-top: 4px" width="75%"><div id="context" class="bginput" style="height: 70px; width: 98%; border: 1px inset; padding: 2px"></div></td>
		<td style="padding-top: 4px">
			<table width="100%" border="0" cellspacing="0" cellpadding="4">
				<tr>
					<td align="center"><input type="button" value="Ignore" onClick="nextWord();" class="bginput" name="ignore" style="width: 98%" /></td>
				</tr>
				<tr>
					<td align="center"><input type="button" value="Ignore All" onClick="ignoreAllWord();" class="bginput" name="ignoreall" style="width: 98%" /></td>
				</tr>
				<tr>
					<td align="center"><input type="button" value="Add Word" onClick="addWord();" class="bginput" name="addword" style="width: 98%" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="padding: 2px; padding-top: 4px"  width="75%">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td nowrap="nowrap"><span class="normalfont">Change to:</span></td>
					<td style="padding-left: 10px" width="100%"><input type="text" name="new_word" size="12" value="" class="bginput" style="width: 98%" /></td>
				</tr>
			</table>
		</td>
		<td rowspan="2">
			<table width="100%" border="0" cellspacing="0" cellpadding="4">
				<tr>
					<td align="center"><input type="button" value="Change" onClick="changeWord();" class="bginput" name="change" style="width: 98%" /></td>
				</tr>
				<tr>
					<td align="center" style="padding-top: 2px"><input type="button" value="Change All" onClick="changeAllWord();" class="bginput" name="changeall" style="width: 98%" /></td>
				</tr>
				<tr>
					<td align="center"><input type="button" value="Undo Last" onClick="undoLast();" class="bginput" name="undolast" style="width: 98%" disabled="disabled" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="padding: 2px; padding-top: 4px"  width="75%"><select name="suggest_list" style="width: 99%" onChange="suggest_list_change(this);" multiple="multiple" size="4"></select></td>
	</tr>
	<tr>
		<td align="center" style="padding: 2px; padding-top: 4px" colspan="2"><input type="button" value="   Finish   " onClick="finishChecking();" class="bginput" name="finish" />&nbsp;&nbsp;&nbsp;<input type="button" value="   Cancel   " onClick="cancelChecking();" class="bginput" name="cancel" /></td>
	</tr>
</table>

</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head>
<title>$appname: Spell Checker</title>
$GLOBALS[css]
<script language=\\"Javascript\\">
<!--

var currNum=0;
var intTop;
var proDoc = window.top.process;
var userDictAvail = true;
var userDict = new Array();
var ignoreAllWords = new Array();
var changeAllWordsOriginal = new Array();
var changeAllWordsReplace = new Array();
var comMistakesOriginal = new Array();
var comMistakesReplace = new Array();
var lastAction = \'ignore\';

function removeWord(word) {
	for (var i = 0; i < userDict.length; i++) {
		if (userDict[i] == word) {
			userDict[i] = \'\';
		}
	}
	return;
}

function writeMe(strRepVal, wordNum) {       
	var wDoc = self.document;
	var eDiv = document.all ? wDoc.all[\'context\'] : wDoc.getElementById(\'context\');
	eDiv.innerHTML = strRepVal;
	var fDiv = document.all ? wDoc.all[\'wordnum\'] : wDoc.getElementById(\'wordnum\');
	fDiv.innerHTML = \'Word <b>\'+wordNum+\'</b> out of <b>\'+proDoc.getMisspelledCount()+\'</b>:\';
	return;
}

function startSpell() {
	var strFirstWord = getCurrentWord();
	intTop = proDoc.getMisspelledCount();
	setContext();
	setSelectList();
	return true;
}

function setContext() {
	writeMe(proDoc.getContext(currNum), currNum + 1);
}

function getCurrentWord() {
	return proDoc.getWord(currNum);
}

function isWordCapitalized() {
	var word = proDoc.getWord(currNum);
	var c = word.charAt(0);
	if (c.toUpperCase() == c) {
		return true;
	} else {
		return false;
	}
}

function capitalizeSuggs(sAr) {
	var c = sAr.length;
	for (x = 0; x < c; x++) {
		sStr = sAr[x];
		sC = sStr.charAt(0);
		sC = sC.toUpperCase();
		var newstr = sC + sStr.substr(1, sStr.length - 1);
		sAr[x] = newstr;
	}
	return sAr;
}

function setSelectList() {
	var sel = self.document.sugform.suggest_list;
	var sugAr = new Array();
	sel.length = 0;
	sugAr = proDoc.getSuggList(currNum);
	if (sugAr.length > 0) {
		if (isWordCapitalized()) {
			sugAr = capitalizeSuggs(sugAr);
		}
		for (x = 0; x < sugAr.length; x++) {
			var newOpt = new Option(sugAr[x], x);
			sel.options.length++;
			sel.options[sel.length - 1].text  = newOpt.text;
			sel.options[sel.length - 1].value = newOpt.value;
		}
		//sel.options[0].selected = true;
		document.sugform.new_word.value = sel.options[0].text;
		sel.disabled = false;
	} else {
		sel.options[sel.options.length] = new Option(\'(no suggestions)\', \'(no suggestions)\');
		sel.disabled = true;
		document.sugform.new_word.value = getCurrentWord();
	}
	document.sugform.new_word.focus();
	setSelectionRange(document.sugform.new_word, 0, document.sugform.new_word.value.length);
}

function suggest_list_change(sel) {
	self.document.sugform.new_word.value = sel.options[sel.options.selectedIndex].text;
}

function undoLast() {
	currNum--;
	if (lastAction == \'change\') {
		var oi = proDoc.getOi(currNum);
		var retVal = proDoc.setOrigWord(currNum, proDoc.getLastOrigWord());
		var where = in_array(changeAllWordsOriginal, proDoc.getLastOrigWord().toLowerCase());
		if (where) {
			changeAllWordsOriginal[where - 1] = \'\';
			changeAllWordsReplace[where - 1] = \'\';
		}
		var where = in_array(comMistakesOriginal, proDoc.getLastOrigWord().toLowerCase());
		if (where) {
			comMistakesOriginal[where - 1] = \'\';
			comMistakesReplace[where - 1] = \'\';
		}
	} else if (lastAction == \'ignore\' || lastAction == \'add\') {
		if (lastAction == \'add\') {
			removeWord(getCurrentWord());
		}
		var where = in_array(ignoreAllWords, getCurrentWord().toLowerCase());
		if (where) {
			ignoreAllWords[where - 1] = \'\';
		}
	}
	currNum--;
	nextWord();
	disableUndo(true);
}

function changeWord() {
	lastAction = \'change\';
	var oi = proDoc.getOi(currNum);
	var nw = self.document.sugform.new_word.value;
	var retVal = proDoc.setNewWord(currNum, nw);
	comMistakesOriginal[comMistakesOriginal.length] = getCurrentWord().toLowerCase();
	comMistakesReplace[comMistakesReplace.length] = self.document.sugform.new_word.value;
	nextWord();
}

function changeAllWord() {
	changeAllWordsOriginal[changeAllWordsOriginal.length] = getCurrentWord().toLowerCase();
	changeAllWordsReplace[changeAllWordsReplace.length] = self.document.sugform.new_word.value;
	changeWord();
}

function ignoreAllWord() {
	lastAction = \'ignore\';
	ignoreAllWords[ignoreAllWords.length] = getCurrentWord();
	nextWord();
}

function nextWord() {
	if (!proDoc.isEnd(currNum)) {
		currNum++;
		setContext();
		setSelectList();

		// Automatically ignore or change certain words
		if (in_array(ignoreAllWords, getCurrentWord().toLowerCase())) {
			nextWord();
		} else {
			var where = in_array(changeAllWordsOriginal, getCurrentWord().toLowerCase());
			if (where) {
				var retVal = proDoc.setNewWord(currNum, changeAllWordsReplace[where - 1]);
				changeWord();
			}
		}
		disableUndo(false);
	} else {
		finishChecking();
	}
}

// Like the PHP function, except that the key is incremented by 1!!!
function in_array(thearray, match) {
	for (var i = 0; i < thearray.length; i++) {
		if (thearray[i] == match) {
			return i + 1;
		}
	}
	return false;
}

function finishChecking() {
	// Still go through all words, if the user pressed Change All
	if (changeAllWordsOriginal.length > 0) {
		while (!proDoc.isEnd(currNum)) {
			nextWord();
		}
	}
	disableUndo(true);

	var strConcat = \\"\\";
	var oAr = new Array();
	oAr = proDoc.getOrigAr();
	var oArLen = oAr.length;
	for (x = 0; x < oArLen; x++) {
		if (typeof(oAr[x]) == \'undefined\') {
			continue;
		}
		strConcat += oAr[x];
	}
	if (window.top.opener && !window.top.opener.closed) {
		window.top.opener.updateSpellChecked(strConcat);
		".(($sendafter ) ? ("
			window.top.opener.sendMail(window.top.opener.document.composeform, true);
		") : (\'\'))."
	} else {
		//alert(\'The window containing your original data has been closed.\');
	}
	if (userDict.length > 0 || comMistakesOriginal.length > 0) {
		submitDict();
	} else {
		closeMe();
	}
}

function submitDict() {
	alert(\'New words in dictionary: \' + userDict.join(\'\\\\n\') + \'\\\\nWords that were changed: \' + comMistakesOriginal.join(\'\\\\n\') + \'\\\\nWords that came instead: \' + comMistakesReplace.join(\'\\\\n\'));
	document.sugform.newWords.value = userDict.join(\'\\\\n\');
	document.sugform.changedFrom.value = comMistakesOriginal.join(\'\\\\n\');
	document.sugform.changedTo.value = comMistakesReplace.join(\'\\\\n\');
	document.sugform.submit();             
	return;
}

function closeMe() {
	window.top.window.close();
}

function cancelChecking() {
	".(($sendafter ) ? ("
		if (confirm(\'The spell check on the message was halted. Do you want to send anyway?\')) {
			window.top.opener.sendMail(window.top.opener.document.composeform, true);
		}
	") : (\'\'))."
	closeMe();
}

function disableUndo(state) {
	self.document.sugform.undolast.disabled = state;
}

function addWord() {
	userDict[userDict.length] = getCurrentWord();
	ignoreAllWord();
	lastAction = \'add\';
	disableUndo(true);
}

function setSelectionRange(input, selectionStart, selectionEnd) {
	if (input.setSelectionRange) {
		input.focus();
		input.setSelectionRange(selectionStart, selectionEnd);
	} else if (input.createTextRange) {
		var range = input.createTextRange();
		range.collapse(true);
		range.moveEnd(\'character\', selectionEnd);
		range.moveStart(\'character\', selectionStart);
		range.select();
	}
}

//-->
</script>
</head>
<body style=\\"background-color: #C7E1F4;\\" onload=\\"startSpell();\\">

$GLOBALS[header]

<form action=\\"compose.spell.php{$GLOBALS[session_url]}\\" name=\\"sugform\\" method=\\"post\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"updatedata\\">
<input type=\\"hidden\\" name=\\"newWords\\" value=\\"\\">
<input type=\\"hidden\\" name=\\"changedFrom\\" value=\\"\\">
<input type=\\"hidden\\" name=\\"changedTo\\" value=\\"\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Spell Checker</b></span></th>
</tr>
</table>

<table align=\\"center\\" width=\\"100%\\" cellspacing=\\"0\\" cellpadding=\\"0\\">
	<tr>
		<td style=\\"padding: 2px; padding-top: 10px\\" width=\\"75%\\" colspan=\\"2\\"><div id=\\"wordnum\\" class=\\"normalfont\\" style=\\"height: 14px; width: 98%\\"></div></td>
	</tr>
	<tr>
		<td style=\\"padding: 2px; padding-top: 4px\\" width=\\"75%\\"><div id=\\"context\\" class=\\"bginput\\" style=\\"height: 70px; width: 98%; border: 1px inset; padding: 2px\\"></div></td>
		<td style=\\"padding-top: 4px\\">
			<table width=\\"100%\\" border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"4\\">
				<tr>
					<td align=\\"center\\"><input type=\\"button\\" value=\\"Ignore\\" onClick=\\"nextWord();\\" class=\\"bginput\\" name=\\"ignore\\" style=\\"width: 98%\\" /></td>
				</tr>
				<tr>
					<td align=\\"center\\"><input type=\\"button\\" value=\\"Ignore All\\" onClick=\\"ignoreAllWord();\\" class=\\"bginput\\" name=\\"ignoreall\\" style=\\"width: 98%\\" /></td>
				</tr>
				<tr>
					<td align=\\"center\\"><input type=\\"button\\" value=\\"Add Word\\" onClick=\\"addWord();\\" class=\\"bginput\\" name=\\"addword\\" style=\\"width: 98%\\" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style=\\"padding: 2px; padding-top: 4px\\"  width=\\"75%\\">
			<table width=\\"100%\\" border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"0\\">
				<tr>
					<td nowrap=\\"nowrap\\"><span class=\\"normalfont\\">Change to:</span></td>
					<td style=\\"padding-left: 10px\\" width=\\"100%\\"><input type=\\"text\\" name=\\"new_word\\" size=\\"12\\" value=\\"\\" class=\\"bginput\\" style=\\"width: 98%\\" /></td>
				</tr>
			</table>
		</td>
		<td rowspan=\\"2\\">
			<table width=\\"100%\\" border=\\"0\\" cellspacing=\\"0\\" cellpadding=\\"4\\">
				<tr>
					<td align=\\"center\\"><input type=\\"button\\" value=\\"Change\\" onClick=\\"changeWord();\\" class=\\"bginput\\" name=\\"change\\" style=\\"width: 98%\\" /></td>
				</tr>
				<tr>
					<td align=\\"center\\" style=\\"padding-top: 2px\\"><input type=\\"button\\" value=\\"Change All\\" onClick=\\"changeAllWord();\\" class=\\"bginput\\" name=\\"changeall\\" style=\\"width: 98%\\" /></td>
				</tr>
				<tr>
					<td align=\\"center\\"><input type=\\"button\\" value=\\"Undo Last\\" onClick=\\"undoLast();\\" class=\\"bginput\\" name=\\"undolast\\" style=\\"width: 98%\\" disabled=\\"disabled\\" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style=\\"padding: 2px; padding-top: 4px\\"  width=\\"75%\\"><select name=\\"suggest_list\\" style=\\"width: 99%\\" onChange=\\"suggest_list_change(this);\\" multiple=\\"multiple\\" size=\\"4\\"></select></td>
	</tr>
	<tr>
		<td align=\\"center\\" style=\\"padding: 2px; padding-top: 4px\\" colspan=\\"2\\"><input type=\\"button\\" value=\\"   Finish   \\" onClick=\\"finishChecking();\\" class=\\"bginput\\" name=\\"finish\\" />&nbsp;&nbsp;&nbsp;<input type=\\"button\\" value=\\"   Cancel   \\" onClick=\\"cancelChecking();\\" class=\\"bginput\\" name=\\"cancel\\" /></td>
	</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
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
.normalInactiveLink {
	font-family: $skin[fontface];
	font-size: 12px;
	border: 1px $skin[firstalt] solid;
	font-weight: normal;
	background: $skin[firstalt];
	color: $skin[linkcolor];
}
.normalInactiveLinkHover {
	font-family: $skin[fontface];
	font-size: 12px;
	border: 1px $skin[firstalt] solid;
	font-weight: normal;
	background: $skin[firstalt];
	color: $skin[linkhovercolor];
}
.highInactiveLink {
	font-family: $skin[fontface];
	font-size: 12px;
	border: 1px $skin[secondalt] solid;
	font-weight: normal;
	background: $skin[secondalt];
	color: $skin[linkcolor];
}
.highInactiveLinkHover {
	font-family: $skin[fontface];
	font-size: 12px;
	border: 1px $skin[secondalt] solid;
	font-weight: normal;
	background: $skin[secondalt];
	color: $skin[linkhovercolor];
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
.Cell {
	border: 0px $skin[border_normal_style] $skin[border_normal_color];
	border-width: 0px $skin[border_normal_vertical_width] $skin[border_normal_horizonal_width] 0px;
}
.LeftCell {
	border: 1px $skin[border_normal_style] $skin[border_normal_color];
	border-width: 0px $skin[border_normal_vertical_width] $skin[border_normal_horizonal_width] $skin[border_normal_edges_width];
}
.RightCell {
	border: 1px $skin[border_normal_style] $skin[border_normal_color];
	border-width: 0px $skin[border_normal_edges_width] $skin[border_normal_horizonal_width] 0px;
}
.BothCell {
	border: 1px $skin[border_normal_style] $skin[border_normal_color];
	border-width: 0px $skin[border_normal_edges_width] $skin[border_normal_horizonal_width] $skin[border_normal_edges_width];
}
.normalCell {
	border: 0px $skin[border_normal_style] $skin[border_normal_color];
	border-width: 0px $skin[border_normal_vertical_width] $skin[border_normal_horizonal_width] 0px;
	background-color: $skin[firstalt];
}
.normalLeftCell {
	border: 1px $skin[border_normal_style] $skin[border_normal_color];
	border-width: 0px $skin[border_normal_vertical_width] $skin[border_normal_horizonal_width] $skin[border_normal_edges_width];
	background-color: $skin[firstalt];
}
.normalRightCell {
	border: 1px $skin[border_normal_style] $skin[border_normal_color];
	border-width: 0px $skin[border_normal_edges_width] $skin[border_normal_horizonal_width] 0px;
	background-color: $skin[firstalt];
}
.normalBothCell {
	border: 1px $skin[border_normal_style] $skin[border_normal_color];
	border-width: 0px $skin[border_normal_edges_width] $skin[border_normal_horizonal_width] $skin[border_normal_edges_width];
	background-color: $skin[firstalt];
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
th a {
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
<script type="text/javascript" language="JavaScript">
<!--
var INDEX_FILE = \'{<INDEX_FILE>}\';
// -->
</script>
<script type="text/javascript" src="misc/common.js"></script>
<%if !infile(\'compose\') and !defined(\'NO_JS\') %>
<link rel="stylesheet" href="misc/context.css">
<script type="text/javascript" src="misc/event.js"></script>
<script type="text/javascript" src="misc/context.js"></script>
<script type="text/javascript" language="JavaScript">
<!--

var gotNewMsgs = 0;

event_addListener( window, \'load\', function() { preloadImages(\'$skin[images]/header_icon_inbox_high.gif\', \'$skin[images]/header_icon_compose_high.gif\', \'$skin[images]/header_icon_addbook_high.gif\', \'$skin[images]/header_icon_options_high.gif\', \'$skin[images]/header_icon_search_high.gif\'); });

<%if $hiveuser[\'userid\'] <> 0 and $hiveuser[\'lastvisit\'] == TIMENOW and $hiveuser[\'fixdst\'] %>
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
		<%if $hiveuser[\'popupnotices\'] %>
			if (confirm(\'The system has detected that the time zone in your preferences is wrong and off by \'+Math.abs(difference)+\' hour\'+((Math.abs(difference) == 1) ? (\'\') : (\'s\'))+\'. Would you like the system to correct this mistake?\')) {
				imgevent("options.personal.php?cmd=updatezone&difference="+difference);
			} else if (confirm(\'Do you wish to disable the automatic detection of time-zones?\')) {
				imgevent("options.personal.php?cmd=disablezone");
			}
		<%else%>
			showNotice(\'The system has detected that the time zone in your preferences is wrong and off by \'+Math.abs(difference)+\' hour\'+((Math.abs(difference) == 1) ? (\'\') : (\'s\'))+\'. Click <a href="#" onClick="imgevent(\\\'options.personal.php?cmd=updatezone&difference=\'+difference+\'\\\'); closeNotice(); return false;">here</a> to correct this mistake or click <a href="#" onClick="imgevent(\\\'options.personal.php?cmd=disablezone\\\'); closeNotice(); return false;">here</a> to disable the automatic detection of time-zones.\');
		<%endif%>
	}
}
setTimeout(checkDST, 1000);
<%endif%>

function contextForFolder(e, folderID, folderName) {
	var popupoptions = [
		new ContextItem(\'Open\', function(){ window.location = \'{<INDEX_FILE>}?folderid=\'+folderID; }, false, true),
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
.normalInactiveLink {
	font-family: {$GLOBALS[skin][fontface]};
	font-size: 12px;
	border: 1px {$GLOBALS[skin][firstalt]} solid;
	font-weight: normal;
	background: {$GLOBALS[skin][firstalt]};
	color: {$GLOBALS[skin][linkcolor]};
}
.normalInactiveLinkHover {
	font-family: {$GLOBALS[skin][fontface]};
	font-size: 12px;
	border: 1px {$GLOBALS[skin][firstalt]} solid;
	font-weight: normal;
	background: {$GLOBALS[skin][firstalt]};
	color: {$GLOBALS[skin][linkhovercolor]};
}
.highInactiveLink {
	font-family: {$GLOBALS[skin][fontface]};
	font-size: 12px;
	border: 1px {$GLOBALS[skin][secondalt]} solid;
	font-weight: normal;
	background: {$GLOBALS[skin][secondalt]};
	color: {$GLOBALS[skin][linkcolor]};
}
.highInactiveLinkHover {
	font-family: {$GLOBALS[skin][fontface]};
	font-size: 12px;
	border: 1px {$GLOBALS[skin][secondalt]} solid;
	font-weight: normal;
	background: {$GLOBALS[skin][secondalt]};
	color: {$GLOBALS[skin][linkhovercolor]};
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
.Cell {
	border: 0px {$GLOBALS[skin][border_normal_style]} {$GLOBALS[skin][border_normal_color]};
	border-width: 0px {$GLOBALS[skin][border_normal_vertical_width]} {$GLOBALS[skin][border_normal_horizonal_width]} 0px;
}
.LeftCell {
	border: 1px {$GLOBALS[skin][border_normal_style]} {$GLOBALS[skin][border_normal_color]};
	border-width: 0px {$GLOBALS[skin][border_normal_vertical_width]} {$GLOBALS[skin][border_normal_horizonal_width]} {$GLOBALS[skin][border_normal_edges_width]};
}
.RightCell {
	border: 1px {$GLOBALS[skin][border_normal_style]} {$GLOBALS[skin][border_normal_color]};
	border-width: 0px {$GLOBALS[skin][border_normal_edges_width]} {$GLOBALS[skin][border_normal_horizonal_width]} 0px;
}
.BothCell {
	border: 1px {$GLOBALS[skin][border_normal_style]} {$GLOBALS[skin][border_normal_color]};
	border-width: 0px {$GLOBALS[skin][border_normal_edges_width]} {$GLOBALS[skin][border_normal_horizonal_width]} {$GLOBALS[skin][border_normal_edges_width]};
}
.normalCell {
	border: 0px {$GLOBALS[skin][border_normal_style]} {$GLOBALS[skin][border_normal_color]};
	border-width: 0px {$GLOBALS[skin][border_normal_vertical_width]} {$GLOBALS[skin][border_normal_horizonal_width]} 0px;
	background-color: {$GLOBALS[skin][firstalt]};
}
.normalLeftCell {
	border: 1px {$GLOBALS[skin][border_normal_style]} {$GLOBALS[skin][border_normal_color]};
	border-width: 0px {$GLOBALS[skin][border_normal_vertical_width]} {$GLOBALS[skin][border_normal_horizonal_width]} {$GLOBALS[skin][border_normal_edges_width]};
	background-color: {$GLOBALS[skin][firstalt]};
}
.normalRightCell {
	border: 1px {$GLOBALS[skin][border_normal_style]} {$GLOBALS[skin][border_normal_color]};
	border-width: 0px {$GLOBALS[skin][border_normal_edges_width]} {$GLOBALS[skin][border_normal_horizonal_width]} 0px;
	background-color: {$GLOBALS[skin][firstalt]};
}
.normalBothCell {
	border: 1px {$GLOBALS[skin][border_normal_style]} {$GLOBALS[skin][border_normal_color]};
	border-width: 0px {$GLOBALS[skin][border_normal_edges_width]} {$GLOBALS[skin][border_normal_horizonal_width]} {$GLOBALS[skin][border_normal_edges_width]};
	background-color: {$GLOBALS[skin][firstalt]};
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
th a {
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
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
var INDEX_FILE = \'".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}\';
// -->
</script>
<script type=\\"text/javascript\\" src=\\"misc/common.js\\"></script>
".((!infile(\'compose\') and !defined(\'NO_JS\') ) ? ("
<link rel=\\"stylesheet\\" href=\\"misc/context.css\\">
<script type=\\"text/javascript\\" src=\\"misc/event.js\\"></script>
<script type=\\"text/javascript\\" src=\\"misc/context.js\\"></script>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--

var gotNewMsgs = 0;

event_addListener( window, \'load\', function() { preloadImages(\'{$GLOBALS[skin][images]}/header_icon_inbox_high.gif\', \'{$GLOBALS[skin][images]}/header_icon_compose_high.gif\', \'{$GLOBALS[skin][images]}/header_icon_addbook_high.gif\', \'{$GLOBALS[skin][images]}/header_icon_options_high.gif\', \'{$GLOBALS[skin][images]}/header_icon_search_high.gif\'); });

".(($hiveuser[\'userid\'] <> 0 and $hiveuser[\'lastvisit\'] == TIMENOW and $hiveuser[\'fixdst\'] ) ? ("
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
		".(($hiveuser[\'popupnotices\'] ) ? ("
			if (confirm(\'The system has detected that the time zone in your preferences is wrong and off by \'+Math.abs(difference)+\' hour\'+((Math.abs(difference) == 1) ? (\'\') : (\'s\'))+\'. Would you like the system to correct this mistake?\')) {
				imgevent(\\"options.personal.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=updatezone&difference=\\"+difference);
			} else if (confirm(\'Do you wish to disable the automatic detection of time-zones?\')) {
				imgevent(\\"options.personal.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=disablezone\\");
			}
		") : ("
			showNotice(\'The system has detected that the time zone in your preferences is wrong and off by \'+Math.abs(difference)+\' hour\'+((Math.abs(difference) == 1) ? (\'\') : (\'s\'))+\'. Click <a href=\\"#\\" onClick=\\"imgevent(\\\\\'options.personal.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=updatezone&difference=\'+difference+\'\\\\\'); closeNotice(); return false;\\">here</a> to correct this mistake or click <a href=\\"#\\" onClick=\\"imgevent(\\\\\'options.personal.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=disablezone\\\\\'); closeNotice(); return false;\\">here</a> to disable the automatic detection of time-zones.\');
		"))."
	}
}
setTimeout(checkDST, 1000);
") : (\'\'))."

function contextForFolder(e, folderID, folderName) {
	var popupoptions = [
		new ContextItem(\'Open\', function(){ window.location = \'".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=\'+folderID; }, false, true),
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
    'upgraded' => '0',
  ),
  'error' => 
  array (
    'templategroupid' => '1',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname <%if $iserror %>Error<%else%>Message<%endif%></title>
$css
</head>
<body <%if defined(\'LOAD_MINI_TEMPLATES\') %>style="background-color: #C7E1F4;"<%endif%>>
$header

<table cellpadding="4" cellspacing="0" class="normalTable" width="<%if defined(\'LOAD_MINI_TEMPLATES\') %>100%<%else%>650<%endif%>" align="center" style="height: 100px;">
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
<body ".((defined(\'LOAD_MINI_TEMPLATES\') ) ? ("style=\\"background-color: #C7E1F4;\\"") : (\'\')).">
$GLOBALS[header]

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"".((defined(\'LOAD_MINI_TEMPLATES\') ) ? ("100%") : ("650"))."\\" align=\\"center\\" style=\\"height: 100px;\\">
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
    'upgraded' => '0',
  ),
  'error_accessdenied' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'We\'re sorry, you do not have access to this page.',
    'parsed_data' => '"We\'re sorry, you do not have access to this page."',
    'upgraded' => '0',
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
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'error_aliases_toomany' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You may only have $hiveuser[maxaliases] signatures.',
    'parsed_data' => '"You may only have $hiveuser[maxaliases] signatures."',
    'upgraded' => '0',
  ),
  'error_alreadyreported' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'This message has already been reported, please don\'t send multiple reports of the same message.',
    'parsed_data' => '"This message has already been reported, please don\'t send multiple reports of the same message."',
    'upgraded' => '0',
  ),
  'error_altemail_notvalid' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The secondary email address you have provided is not valid.',
    'parsed_data' => '"The secondary email address you have provided is not valid."',
    'upgraded' => '0',
  ),
  'error_answer_dontmatch' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The new secret answers you have entered do not match. Please go back and try again.',
    'parsed_data' => '"The new secret answers you have entered do not match. Please go back and try again."',
    'upgraded' => '0',
  ),
  'error_answer_empty' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Your secret answer must not be empty. Please go back and try again.',
    'parsed_data' => '"Your secret answer must not be empty. Please go back and try again."',
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'error_banned' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Your IP/hostname has been banned from using $appname.',
    'parsed_data' => '"Your IP/hostname has been banned from using $appname."',
    'upgraded' => '0',
  ),
  'error_cantuse' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'We\'re sorry, your account is currently disabled so you are unable to use this service. If you have just registered and the operators of $appname require user validation, your account will not be functional until they activate it. You will be notified by email ($hiveuser[altemail]) when your account is activated.',
    'parsed_data' => '"We\'re sorry, your account is currently disabled so you are unable to use this service. If you have just registered and the operators of $appname require user validation, your account will not be functional until they activate it. You will be notified by email ($hiveuser[altemail]) when your account is activated."',
    'upgraded' => '0',
  ),
  'error_contacts_toomany' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You may only have $hiveuser[maxcontacts] contacts in your address book. You must remove some contacts before you may add more.',
    'parsed_data' => '"You may only have $hiveuser[maxcontacts] contacts in your address book. You must remove some contacts before you may add more."',
    'upgraded' => '0',
  ),
  'error_couldntsend' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The server encountered a problem while trying to send your message and could not complete the process. $goback

<%if $data[\'popid\'] > 0 %><br /><br />If you would like to send this message using the default account, please <a href="compose.send.php?draftid=$draftid&popid=0">click here</a>.<%endif%>',
    'parsed_data' => '"The server encountered a problem while trying to send your message and could not complete the process. $goback

".(($data[\'popid\'] > 0 ) ? ("<br /><br />If you would like to send this message using the default account, please <a href=\\"compose.send.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}draftid=$draftid&popid=0\\">click here</a>.") : (\'\'))',
    'upgraded' => '0',
  ),
  'error_cvsfail' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Sorry, $appname was unable to parse the uploaded file and import the details. Please ensure that the fields are seperated by commas and the file contains a header row.',
    'parsed_data' => '"Sorry, $appname was unable to parse the uploaded file and import the details. Please ensure that the fields are seperated by commas and the file contains a header row."',
    'upgraded' => '0',
  ),
  'error_disabled' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Sorry, $appname is currently disabled for maintenance work. You can logout by clicking <a href="user.logout.php">here</a>. $appname will be available again shortly.',
    'parsed_data' => '"Sorry, $appname is currently disabled for maintenance work. You can logout by clicking <a href=\\"user.logout.php{$GLOBALS[session_url]}\\">here</a>. $appname will be available again shortly."',
    'upgraded' => '0',
  ),
  'error_event_cannotdelete' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You are not permitted to remove this event because you did not post it.',
    'parsed_data' => '"You are not permitted to remove this event because you did not post it."',
    'upgraded' => '0',
  ),
  'error_event_cannotedit' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You do not have permission to edit this event. This may be for one of the following reasons:<br />
- Are you trying to edit a shared event that another user posted? The event poster may not have given you permission to edit it.<br />
- Are you trying to post a shared event? Your administrator may not have allowed you to post shared events.<br />
- Are you trying to edit or create a global event? Only administrators can edit or create global events.',
    'parsed_data' => '"You do not have permission to edit this event. This may be for one of the following reasons:<br />
- Are you trying to edit a shared event that another user posted? The event poster may not have given you permission to edit it.<br />
- Are you trying to post a shared event? Your administrator may not have allowed you to post shared events.<br />
- Are you trying to edit or create a global event? Only administrators can edit or create global events."',
    'upgraded' => '0',
  ),
  'error_event_cannotfwd' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You do not have permission to forward this event to other users. If you feel this is in error, please contact the event poster, <a href="compose.email.php?data[to]=$event[poster]$domain">$event[poster]$domain</a>.',
    'parsed_data' => '"You do not have permission to forward this event to other users. If you feel this is in error, please contact the event poster, <a href=\\"compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}data[to]=$event[poster]$domain\\">$event[poster]$domain</a>."',
    'upgraded' => '0',
  ),
  'error_event_invalid_end' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The end date you have specifed is not valid. Please go back and try again.',
    'parsed_data' => '"The end date you have specifed is not valid. Please go back and try again."',
    'upgraded' => '0',
  ),
  'error_event_invalid_start' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The date you have specifed is not valid. Please go back and try again.',
    'parsed_data' => '"The date you have specifed is not valid. Please go back and try again."',
    'upgraded' => '0',
  ),
  'error_event_neveroccur' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The recurrence options you have selected are not valid - this event will never occur! Please go back and try again.',
    'parsed_data' => '"The recurrence options you have selected are not valid - this event will never occur! Please go back and try again."',
    'upgraded' => '0',
  ),
  'error_event_nofwdusers' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'There were no valid users specified to forward this event to. Please press back and try again.',
    'parsed_data' => '"There were no valid users specified to forward this event to. Please press back and try again."',
    'upgraded' => '0',
  ),
  'error_event_nosharedusers' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Either you did not select any users to share this event with, or none of the users you selected have accounts at $appname. Please go back and select some valid users to share this event with, or change it to a Normal event.',
    'parsed_data' => '"Either you did not select any users to share this event with, or none of the users you selected have accounts at $appname. Please go back and select some valid users to share this event with, or change it to a Normal event."',
    'upgraded' => '0',
  ),
  'error_event_notitle' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Your event must have a title. Please go back and try again.',
    'parsed_data' => '"Your event must have a title. Please go back and try again."',
    'upgraded' => '0',
  ),
  'error_field_below_min_options' => 
  array (
    'templategroupid' => '7',
    'user_data' => '<li>The field $field[title] requires at least $field[min] choices, and you\'ve only chosen $current_number.</li>
',
    'parsed_data' => '"<li>The field $field[title] requires at least $field[min] choices, and you\'ve only chosen $current_number.</li>
"',
    'upgraded' => '0',
  ),
  'error_field_below_min_text' => 
  array (
    'templategroupid' => '7',
    'user_data' => '<li>The field $field[title] requires at least $field[min] characters, and you\'ve only entered $current_number.</li>
',
    'parsed_data' => '"<li>The field $field[title] requires at least $field[min] characters, and you\'ve only entered $current_number.</li>
"',
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'error_field_over_max_options' => 
  array (
    'templategroupid' => '7',
    'user_data' => '<li>In the field $field[title] you may only choose up-to $field[max] options, and you\'ve chosen $current_number.</li>
',
    'parsed_data' => '"<li>In the field $field[title] you may only choose up-to $field[max] options, and you\'ve chosen $current_number.</li>
"',
    'upgraded' => '0',
  ),
  'error_field_over_max_text' => 
  array (
    'templategroupid' => '7',
    'user_data' => '<li>In the field $field[title] you may only enter up-to $field[max] characters, and you\'ve entered $current_number.</li>
',
    'parsed_data' => '"<li>In the field $field[title] you may only enter up-to $field[max] characters, and you\'ve entered $current_number.</li>
"',
    'upgraded' => '0',
  ),
  'error_field_required_empty' => 
  array (
    'templategroupid' => '7',
    'user_data' => '<li>The field $field[title] is required and you must enter/choose a value for it.</li>
',
    'parsed_data' => '"<li>The field $field[title] is required and you must enter/choose a value for it.</li>
"',
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'error_grouptitleempty' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The contact group title cannot be empty.',
    'parsed_data' => '"The contact group title cannot be empty."',
    'upgraded' => '0',
  ),
  'error_invalid' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Invalid $name specified.',
    'parsed_data' => '"Invalid $name specified."',
    'upgraded' => '0',
  ),
  'error_invalidcontact' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The name or email of the contact you are trying to add is invalid. Please go back and try again.',
    'parsed_data' => '"The name or email of the contact you are trying to add is invalid. Please go back and try again."',
    'upgraded' => '0',
  ),
  'error_invalidid' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Invalid $idname specified.',
    'parsed_data' => '"Invalid $idname specified."',
    'upgraded' => '0',
  ),
  'error_logindomain' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You cannot login using the form you have just completed. Please click <a href="{<INDEX_FILE>}">here</a> to log in.',
    'parsed_data' => '"You cannot login using the form you have just completed. Please click <a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}\\">here</a> to log in."',
    'upgraded' => '0',
  ),
  'error_logout' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You were successfully logged out! Please click <a href="{<INDEX_FILE>}">here</a> to log in using a different account.',
    'parsed_data' => '"You were successfully logged out! Please click <a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}\\">here</a> to log in using a different account."',
    'upgraded' => '0',
  ),
  'error_lostpw_wronganswer' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The secret answer you\'ve entered does not match the one in our database. Please go back and try again.',
    'parsed_data' => '"The secret answer you\'ve entered does not match the one in our database. Please go back and try again."',
    'upgraded' => '0',
  ),
  'error_maxonline' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'There are currently too many users using the system. Please try again in a few minutes.',
    'parsed_data' => '"There are currently too many users using the system. Please try again in a few minutes."',
    'upgraded' => '0',
  ),
  'error_maxrecipients' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You may only send your message to $hiveuser[maxrecips] recipient(s), you have tried to send it to $numRecipients.',
    'parsed_data' => '"You may only send your message to $hiveuser[maxrecips] recipient(s), you have tried to send it to $numRecipients."',
    'upgraded' => '0',
  ),
  'error_nocolumns' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You must select at least one column to display.',
    'parsed_data' => '"You must select at least one column to display."',
    'upgraded' => '0',
  ),
  'error_nofolderselected' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You must select at least one folder to apply the rule to.',
    'parsed_data' => '"You must select at least one folder to apply the rule to."',
    'upgraded' => '0',
  ),
  'error_noid' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'No $name specified.',
    'parsed_data' => '"No $name specified."',
    'upgraded' => '0',
  ),
  'error_nomessage' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Your email must contain a message or an attachment.',
    'parsed_data' => '"Your email must contain a message or an attachment."',
    'upgraded' => '0',
  ),
  'error_noresults' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Sorry, no messages matched your search criteria. Please try some different terms.',
    'parsed_data' => '"Sorry, no messages matched your search criteria. Please try some different terms."',
    'upgraded' => '0',
  ),
  'error_nospace' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You are currently using {$_mailmb}MB, passing the limit of $hiveuser[maxmb]MB.<br />You will not be able to send or receive any further message until you delete some existing messages and empty your trash.',
    'parsed_data' => '"You are currently using {$_mailmb}MB, passing the limit of $hiveuser[maxmb]MB.<br />You will not be able to send or receive any further message until you delete some existing messages and empty your trash."',
    'upgraded' => '0',
  ),
  'error_nosubject' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Your email must have a subject.',
    'parsed_data' => '"Your email must have a subject."',
    'upgraded' => '0',
  ),
  'error_noto' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You must enter at least one valid email address of a recipient.',
    'parsed_data' => '"You must enter at least one valid email address of a recipient."',
    'upgraded' => '0',
  ),
  'error_optionsdone' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Thank you for updating your account. Since you created a new username, it has been put into our moderation queue and will be activated within 36 hours.<br />
Until then you will not be able to use the $appname system.',
    'parsed_data' => '"Thank you for updating your account. Since you created a new username, it has been put into our moderation queue and will be activated within 36 hours.<br />
Until then you will not be able to use the $appname system."',
    'upgraded' => '0',
  ),
  'error_oversendingrate' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You may only send one message every $value $unit. Please wait approximately $lastsent $unit before sending this message.',
    'parsed_data' => '"You may only send one message every $value $unit. Please wait approximately $lastsent $unit before sending this message."',
    'upgraded' => '0',
  ),
  'error_password_dontmatch' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The new passwords you have entered do not match. Please go back and try again.',
    'parsed_data' => '"The new passwords you have entered do not match. Please go back and try again."',
    'upgraded' => '0',
  ),
  'error_password_empty' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Your password must not be empty. Please go back and try again.',
    'parsed_data' => '"Your password must not be empty. Please go back and try again."',
    'upgraded' => '0',
  ),
  'error_poplogin' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Could not login to {$serverinfo[$popid][server]}. Please check the login details you have entered again.',
    'parsed_data' => '"Could not login to {$serverinfo[$popid][server]}. Please check the login details you have entered again."',
    'upgraded' => '0',
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
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'error_processerror_subject' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Mail delivery failed: returning message to sender',
    'parsed_data' => '"Mail delivery failed: returning message to sender"',
    'upgraded' => '0',
  ),
  'error_processerror_toobig' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'This message was created automatically by mail delivery software (HiveMail).

A message that you sent could not be delivered to its recipient(s).
The error was:
    Message was too big and therefore rejected.

Please contact the list adminstrator for instructions on how you can be removed from these lists.

------ This is a copy of the message, including all the headers. ------

$message',
    'parsed_data' => '"This message was created automatically by mail delivery software (HiveMail).

A message that you sent could not be delivered to its recipient(s).
The error was:
    Message was too big and therefore rejected.

Please contact the list adminstrator for instructions on how you can be removed from these lists.

------ This is a copy of the message, including all the headers. ------

$message"',
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'error_realname_empty' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You have left the require Real Name field empty. Please go back and try again.',
    'parsed_data' => '"You have left the require Real Name field empty. Please go back and try again."',
    'upgraded' => '0',
  ),
  'error_response_toomany' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You may only have $hiveuser[maxresponses] response.',
    'parsed_data' => '"You may only have $hiveuser[maxresponses] response."',
    'upgraded' => '0',
  ),
  'error_signup_altonsamedomain' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The alternate email address you entered appears to be another email address from $appname. Please use an email address that is not from $appname.',
    'parsed_data' => '"The alternate email address you entered appears to be another email address from $appname. Please use an email address that is not from $appname."',
    'upgraded' => '0',
  ),
  'error_signup_disabled' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'We\'re sorry, the operators of $appname have disabled registration on the system.',
    'parsed_data' => '"We\'re sorry, the operators of $appname have disabled registration on the system."',
    'upgraded' => '0',
  ),
  'error_signup_ipusedtoomuch' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The administrator has specified that this IP address may not be used to create any more accounts.',
    'parsed_data' => '"The administrator has specified that this IP address may not be used to create any more accounts."',
    'upgraded' => '0',
  ),
  'error_signup_nameillegal' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The username you chose, $username was not valid. Your username may only contain alphanumeric characters, underscores (_) and dots (.), must start with a letter or a number and must be at a minimum length of 2 characters.',
    'parsed_data' => '"The username you chose, $username was not valid. Your username may only contain alphanumeric characters, underscores (_) and dots (.), must start with a letter or a number and must be at a minimum length of 2 characters."',
    'upgraded' => '0',
  ),
  'error_signup_nametaken' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'We\'re sorry, $username$domainname is already used by another member. Please go back and enter a different name.',
    'parsed_data' => '"We\'re sorry, $username$domainname is already used by another member. Please go back and enter a different name."',
    'upgraded' => '0',
  ),
  'error_signup_reserved' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Sorry, but your $type contained words which are not allowed to be used at $appname.',
    'parsed_data' => '"Sorry, but your $type contained words which are not allowed to be used at $appname."',
    'upgraded' => '0',
  ),
  'error_sig_toomany' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'You may only have $hiveuser[maxsigs] signatures.',
    'parsed_data' => '"You may only have $hiveuser[maxsigs] signatures."',
    'upgraded' => '0',
  ),
  'error_soundfileattach' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Uploading your custom sound file has failed. Make sure the file is not corrupt, that it is a valid sound file and not bigger than $maxsoundfile bytes.',
    'parsed_data' => '"Uploading your custom sound file has failed. Make sure the file is not corrupt, that it is a valid sound file and not bigger than $maxsoundfile bytes."',
    'upgraded' => '0',
  ),
  'error_subs_procerror' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'We are sorry, but during your payment for a subscription at $appname the following error occured:
$errortext

For futher information, please contact the administrators and include the following information:
Payment Processor: $processor[name]
Order Number: $remoteid
Date and time: $datetime

Best regards,
$appname',
    'parsed_data' => '"We are sorry, but during your payment for a subscription at $appname the following error occured:
$errortext

For futher information, please contact the administrators and include the following information:
Payment Processor: $processor[name]
Order Number: $remoteid
Date and time: $datetime

Best regards,
$appname"',
    'upgraded' => '0',
  ),
  'error_subs_procerror_another_sub' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Your payment was not accepted because you are already subscribed to another plan.',
    'parsed_data' => '"Your payment was not accepted because you are already subscribed to another plan."',
    'upgraded' => '0',
  ),
  'error_subs_procerror_bad_request' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The request made to our processing page was invalid and for security reasons, we did not accept it.',
    'parsed_data' => '"The request made to our processing page was invalid and for security reasons, we did not accept it."',
    'upgraded' => '0',
  ),
  'error_subs_procerror_cc_notprocessed' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The payment processor was unable to charge your credit card.',
    'parsed_data' => '"The payment processor was unable to charge your credit card."',
    'upgraded' => '0',
  ),
  'error_subs_procerror_demo_mode' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The payment processor was running in demo mode, you were not charged.',
    'parsed_data' => '"The payment processor was running in demo mode, you were not charged."',
    'upgraded' => '0',
  ),
  'error_subs_procerror_invalid_cart' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'This payment was already processed by our servers, and your subscription details were updated accordingly.',
    'parsed_data' => '"This payment was already processed by our servers, and your subscription details were updated accordingly."',
    'upgraded' => '0',
  ),
  'error_subs_procerror_not_enough' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The amount you paid was not sufficient for the subscription you chose.',
    'parsed_data' => '"The amount you paid was not sufficient for the subscription you chose."',
    'upgraded' => '0',
  ),
  'error_subs_procerror_subject' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'Your subscription at $appname',
    'parsed_data' => '"Your subscription at $appname"',
    'upgraded' => '0',
  ),
  'error_suspended' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'We are sorry, but your account has been suspended by the administrator on $bandate. The duration of your suspension is <%if $ban[\'duration\'] > 0 %>$ban[duration] day(s)<%else%>unlimited<%endif%>. <%if !empty($ban[\'reason\']) %>The reason for this suspension is:<br /><i>$ban[reason]</i><%endif%>',
    'parsed_data' => '"We are sorry, but your account has been suspended by the administrator on $bandate. The duration of your suspension is ".(($ban[\'duration\'] > 0 ) ? ("$ban[duration] day(s)") : ("unlimited")).". ".((!empty($ban[\'reason\']) ) ? ("The reason for this suspension is:<br /><i>$ban[reason]</i>") : (\'\'))',
    'upgraded' => '0',
  ),
  'error_wrong_password' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The password you have entered is wrong. Please go back and try again.',
    'parsed_data' => '"The password you have entered is wrong. Please go back and try again."',
    'upgraded' => '0',
  ),
  'error_wrong_username' => 
  array (
    'templategroupid' => '7',
    'user_data' => 'The account name you have entered ($username) doesn\'t exist in our records. Please go back and try again.',
    'parsed_data' => '"The account name you have entered ($username) doesn\'t exist in our records. Please go back and try again."',
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'expired_account_emptied_subject' => 
  array (
    'templategroupid' => '17',
    'user_data' => 'Email messages deleted',
    'parsed_data' => '"Email messages deleted"',
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'expired_account_removed_subject' => 
  array (
    'templategroupid' => '17',
    'user_data' => 'Email account removed',
    'parsed_data' => '"Email account removed"',
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'expired_early_notification_emptying_subject' => 
  array (
    'templategroupid' => '17',
    'user_data' => 'Notice of account expiration',
    'parsed_data' => '"Notice of account expiration"',
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'expired_early_notification_removal_subject' => 
  array (
    'templategroupid' => '17',
    'user_data' => 'Notice of account expiration',
    'parsed_data' => '"Notice of account expiration"',
    'upgraded' => '0',
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
<script type="text/javascript" language="JavaScript">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$header

<form action="folders.update.php" method="post" name="form">

<table cellpadding="4" cellspacing="0" width="100%">
	<tr>
		<td valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="150">
				<tr class="headerRow">
					<th class="headerBothCell"><span class="normalfonttablehead"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width="100%" valign="top">
<table cellpadding="4" cellspacing="0" class="normalTable" width="730">
<tr class="headerRow">
	<th class="headerLeftCell" width="1%">&nbsp;</th>
	<th class="headerCell" nowrap="nowrap" colspan="2"><span class="normalfonttablehead"><b>Folder Name</b></span></th>
	<th class="headerCell" nowrap="nowrap"><span class="normalfonttablehead"><b>Messages</b></span></th>
	<th class="headerCell" nowrap="nowrap"><span class="normalfonttablehead"><b>Unread</b></span></th>
	<th class="headerCell" nowrap="nowrap"><span class="normalfonttablehead"><b>Size</b></span></th>
	<th class="headerRightCell"><input name="allbox" type="checkbox" value="Check All" title="Select/Deselect All" onClick="checkAll(this.form);" /></th>
</tr>
<tr align="center" class="{newclassname}Row">
	<td class="{classname}LeftCell" width="1%">&nbsp;</td>
	<td class="{classname}Cell" align="left" width="50%" colspan="2"><span class="normalfont"><a href="{<INDEX_FILE>}?folderid=-1">Inbox</a></span> <span class="smallfont">(not removable)</span></td>
	<td class="{classname}Cell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$msgcount[inbox] message<%if $msgcount[\'inbox\'] != 1 %>s<%endif%></span></td>
	<td class="{classname}Cell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$unreadcount[inbox] message<%if $unreadcount[\'inbox\'] != 1 %>s<%endif%></span></td>
	<td class="{classname}Cell" width="10%" align="center" nowrap="nowrap"><span class="normalfont">$presizes[inbox]KB</span></td>
	<td class="{classname}RightCell"><input type="checkbox" name="folder[-1]" value="yes" onClick="checkMain(this.form);" /></td>
</tr>
<tr align="center" class="{newclassname}Row">
	<td class="{classname}LeftCell" width="1%">&nbsp;</td>
	<td class="{classname}Cell" align="left" width="50%" colspan="2"><span class="normalfont"><a href="{<INDEX_FILE>}?folderid=-2">Sent Items</a></span> <span class="smallfont">(not removable)</span></td>
	<td class="{classname}Cell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$msgcount[sentitems] message<%if $msgcount[\'sentitems\'] != 1 %>s<%endif%></span></td>
	<td class="{classname}Cell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$unreadcount[sentitems] message<%if $unreadcount[\'sentitems\'] != 1 %>s<%endif%></span></td>
	<td class="{classname}Cell" width="10%" align="center" nowrap="nowrap"><span class="normalfont">$presizes[sentitems]KB</span></td>
	<td class="{classname}RightCell"><input type="checkbox" name="folder[-2]" value="yes" onClick="checkMain(this.form);" /></td>
</tr>
<tr align="center" class="{newclassname}Row">
	<td class="{classname}LeftCell" width="1%">&nbsp;</td>
	<td class="{classname}Cell" align="left" width="50%" colspan="2"><span class="normalfont"><a href="{<INDEX_FILE>}?folderid=-3">Trash Can</a></span> <span class="smallfont">(not removable)</span></td>
	<td class="{classname}Cell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$msgcount[trashcan] message<%if $msgcount[\'trashcan\'] != 1 %>s<%endif%></span></td>
	<td class="{classname}Cell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$unreadcount[trashcan] message<%if $unreadcount[\'trashcan\'] != 1 %>s<%endif%></span></td>
	<td class="{classname}Cell" width="10%" align="center" nowrap="nowrap"><span class="normalfont">$presizes[trashcan]KB</span></td>
	<td class="{classname}RightCell"><input type="checkbox" name="folder[-3]" id="trashcan" value="yes" onClick="checkMain(this.form);" /></td>
</tr>
<tr align="center" class="{newclassname}Row">
	<td class="{classname}LeftCell" width="1%">&nbsp;</td>
	<td class="{classname}Cell" align="left" width="50%" colspan="2"><span class="normalfont"><a href="{<INDEX_FILE>}?folderid=-4">Junk Mail</a></span> <span class="smallfont">(not removable)</span></td>
	<td class="{classname}Cell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$msgcount[junkmail] message<%if $msgcount[\'junkmail\'] != 1 %>s<%endif%></span></td>
	<td class="{classname}Cell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$unreadcount[junkmail] message<%if $unreadcount[\'junkmail\'] != 1 %>s<%endif%></span></td>
	<td class="{classname}Cell" width="10%" align="center" nowrap="nowrap"><span class="normalfont">$presizes[junkmail]KB</span></td>
	<td class="{classname}RightCell"><input type="checkbox" name="folder[-4]" id="junkmail" value="yes" onClick="checkMain(this.form);" /></td>
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
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$GLOBALS[header]

<form action=\\"folders.update.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">

<table cellpadding=\\"4\\" cellspacing=\\"0\\" width=\\"100%\\">
	<tr>
		<td valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"150\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width=\\"100%\\" valign=\\"top\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"730\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerLeftCell\\" width=\\"1%\\">&nbsp;</th>
	<th class=\\"headerCell\\" nowrap=\\"nowrap\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Folder Name</b></span></th>
	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>Messages</b></span></th>
	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>Unread</b></span></th>
	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"normalfonttablehead\\"><b>Size</b></span></th>
	<th class=\\"headerRightCell\\"><input name=\\"allbox\\" type=\\"checkbox\\" value=\\"Check All\\" title=\\"Select/Deselect All\\" onClick=\\"checkAll(this.form);\\" /></th>
</tr>
<tr align=\\"center\\" class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"1%\\">&nbsp;</td>
	<td class=\\"{classname}Cell\\" align=\\"left\\" width=\\"50%\\" colspan=\\"2\\"><span class=\\"normalfont\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=-1\\">Inbox</a></span> <span class=\\"smallfont\\">(not removable)</span></td>
	<td class=\\"{classname}Cell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$msgcount[inbox] message".(($msgcount[\'inbox\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"{classname}Cell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$unreadcount[inbox] message".(($unreadcount[\'inbox\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"{classname}Cell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$presizes[inbox]KB</span></td>
	<td class=\\"{classname}RightCell\\"><input type=\\"checkbox\\" name=\\"folder[-1]\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
<tr align=\\"center\\" class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"1%\\">&nbsp;</td>
	<td class=\\"{classname}Cell\\" align=\\"left\\" width=\\"50%\\" colspan=\\"2\\"><span class=\\"normalfont\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=-2\\">Sent Items</a></span> <span class=\\"smallfont\\">(not removable)</span></td>
	<td class=\\"{classname}Cell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$msgcount[sentitems] message".(($msgcount[\'sentitems\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"{classname}Cell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$unreadcount[sentitems] message".(($unreadcount[\'sentitems\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"{classname}Cell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$presizes[sentitems]KB</span></td>
	<td class=\\"{classname}RightCell\\"><input type=\\"checkbox\\" name=\\"folder[-2]\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
<tr align=\\"center\\" class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"1%\\">&nbsp;</td>
	<td class=\\"{classname}Cell\\" align=\\"left\\" width=\\"50%\\" colspan=\\"2\\"><span class=\\"normalfont\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=-3\\">Trash Can</a></span> <span class=\\"smallfont\\">(not removable)</span></td>
	<td class=\\"{classname}Cell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$msgcount[trashcan] message".(($msgcount[\'trashcan\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"{classname}Cell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$unreadcount[trashcan] message".(($unreadcount[\'trashcan\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"{classname}Cell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$presizes[trashcan]KB</span></td>
	<td class=\\"{classname}RightCell\\"><input type=\\"checkbox\\" name=\\"folder[-3]\\" id=\\"trashcan\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
<tr align=\\"center\\" class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"1%\\">&nbsp;</td>
	<td class=\\"{classname}Cell\\" align=\\"left\\" width=\\"50%\\" colspan=\\"2\\"><span class=\\"normalfont\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=-4\\">Junk Mail</a></span> <span class=\\"smallfont\\">(not removable)</span></td>
	<td class=\\"{classname}Cell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$msgcount[junkmail] message".(($msgcount[\'junkmail\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"{classname}Cell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$unreadcount[junkmail] message".(($unreadcount[\'junkmail\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"{classname}Cell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$presizes[junkmail]KB</span></td>
	<td class=\\"{classname}RightCell\\"><input type=\\"checkbox\\" name=\\"folder[-4]\\" id=\\"junkmail\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
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
		</td>
	</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'folders_bit' => 
  array (
    'templategroupid' => '9',
    'user_data' => '<tr align="center" class="{newclassname}Row">
	<td class="{classname}LeftCell" nowrap="nowrap" width="1%" valign="middle">$moveup $movedown</td>
	<td class="{classname}Cell" align="left" width="50%" colspan="2"><span class="normalfont"><a href="{<INDEX_FILE>}?folderid=$folder[folderid]">$folder[title]</a></span> <span class="smallfont">(<a href="#" onClick="rename($folder[folderid], \'$folder[title]\');">rename</a>)</span></td>
	<td class="{classname}Cell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$folder[msgcount] message<%if $folder[\'msgcount\'] != 1 %>s<%endif%></span></td>
	<td class="{classname}Cell" width="25%" align="center" nowrap="nowrap"><span class="normalfont">$folder[unreadcount] message<%if $folder[\'unreadcount\'] != 1 %>s<%endif%></span></td>
	<td class="{classname}Cell" width="10%" align="center" nowrap="nowrap"><span class="normalfont">$folder[size]KB</span></td>
	<td class="{classname}RightCell"><input type="checkbox" name="folder[$folder[folderid]]" value="yes" onClick="checkMain(this.form);" /></td>
</tr>
',
    'parsed_data' => '"<tr align=\\"center\\" class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" nowrap=\\"nowrap\\" width=\\"1%\\" valign=\\"middle\\">$moveup $movedown</td>
	<td class=\\"{classname}Cell\\" align=\\"left\\" width=\\"50%\\" colspan=\\"2\\"><span class=\\"normalfont\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folder[folderid]\\">$folder[title]</a></span> <span class=\\"smallfont\\">(<a href=\\"#\\" onClick=\\"rename($folder[folderid], \'$folder[title]\');\\">rename</a>)</span></td>
	<td class=\\"{classname}Cell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$folder[msgcount] message".(($folder[\'msgcount\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"{classname}Cell\\" width=\\"25%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$folder[unreadcount] message".(($folder[\'unreadcount\'] != 1 ) ? ("s") : (\'\'))."</span></td>
	<td class=\\"{classname}Cell\\" width=\\"10%\\" align=\\"center\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\">$folder[size]KB</span></td>
	<td class=\\"{classname}RightCell\\"><input type=\\"checkbox\\" name=\\"folder[$folder[folderid]]\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
"',
    'upgraded' => '0',
  ),
  'folders_jumpbit' => 
  array (
    'templategroupid' => '9',
    'user_data' => '			<option value="$folder[folderid]">$folder[title]</option>
',
    'parsed_data' => '"			<option value=\\"$folder[folderid]\\">$folder[title]</option>
"',
    'upgraded' => '0',
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
	<%if $hiveuser[\'popupnotices\'] %>
		if (confirm(\'You have just received \'+gotNewMsgs+\' new message(s) to your account. Would you like to go to your {$_folders[\'-1\'][\'title\']} now?\')) {
			if (confirm(\'Open {$_folders[\'-1\'][\'title\']} in a new window?\\n\\n(Press cancel to use current window.)\')) {
				window.open(\'{<INDEX_FILE>}\', \'inbox\'); 
			} else {
				window.location = \'{<INDEX_FILE>}\';
			}
		}
	<%else%>
		showNotice(\'You have just received \'+gotNewMsgs+\' new message(s) to your account. Click <a href="{<INDEX_FILE>}">here</a> to go to your {$_folders[\'-1\'][\'title\']} now.\');
	<%endif%>
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
	".(($hiveuser[\'popupnotices\'] ) ? ("
		if (confirm(\'You have just received \'+gotNewMsgs+\' new message(s) to your account. Would you like to go to your {$_folders[\'-1\'][\'title\']} now?\')) {
			if (confirm(\'Open {$_folders[\'-1\'][\'title\']} in a new window?\\\\n\\\\n(Press cancel to use current window.)\')) {
				window.open(\'".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}\', \'inbox\'); 
			} else {
				window.location = \'".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}\';
			}
		}
	") : ("
		showNotice(\'You have just received \'+gotNewMsgs+\' new message(s) to your account. Click <a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}\\">here</a> to go to your {$_folders[\'-1\'][\'title\']} now.\');
	"))."
}

// -->
</script>
") : (\'\'))."
$youvegotmail"',
    'upgraded' => '0',
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
    'upgraded' => '0',
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
						<a href="calendar.display.php"><img src="$skin[images]/header_icon_calendar$headimgs[6].gif" id="header_calendar" align="middle" border="0" alt="" onMouseOver="this.src = \'$skin[images]/header_icon_calendar_high.gif\';" onMouseOut="this.src = \'$skin[images]/header_icon_calendar$headimgs[6].gif\';" /></a>
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
						<a href="calendar.display.php" class="headerLink" onMouseOver="header_calendar.src = \'$skin[images]/header_icon_calendar_high.gif\';" onMouseOut="header_calendar.src = \'$skin[images]/header_icon_calendar$headimgs[6].gif\';"><span class="headerLink">Calendar</span></a>
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
<div style="border: 1px solid red; margin: 10px 5px 15px 5px; padding: 3px; background-color: #FFCECE;"><span class="normalfont">$appname is currently in maintenance mode. Non-administrators cannot use $appname.</span></div>
<%endif%>

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%" id="noticeTable" style="display: none;">
	<tr>
		<td width="100%" valign="top">
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="headerText"><span class="normalfonttablehead"><b>System Notice [<a href="#" onClick="closeNotice(); return false;"><span class="normalfonttablehead">close</span></a>]</b></span></th>
</tr>
<tr style="highRow">
	<td class="highBothCell"><span class="normalfont" id="noticeText"></span></td>
</tr>
</table>
		</td>
	</tr>
</table>',
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
						<a href=\\"calendar.display.php{$GLOBALS[session_url]}\\"><img src=\\"{$GLOBALS[skin][images]}/header_icon_calendar$headimgs[6].gif\\" id=\\"header_calendar\\" align=\\"middle\\" border=\\"0\\" alt=\\"\\" onMouseOver=\\"this.src = \'{$GLOBALS[skin][images]}/header_icon_calendar_high.gif\';\\" onMouseOut=\\"this.src = \'{$GLOBALS[skin][images]}/header_icon_calendar$headimgs[6].gif\';\\" /></a>
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
						<a href=\\"calendar.display.php{$GLOBALS[session_url]}\\" class=\\"headerLink\\" onMouseOver=\\"header_calendar.src = \'{$GLOBALS[skin][images]}/header_icon_calendar_high.gif\';\\" onMouseOut=\\"header_calendar.src = \'{$GLOBALS[skin][images]}/header_icon_calendar$headimgs[6].gif\';\\"><span class=\\"headerLink\\">Calendar</span></a>
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
<div style=\\"border: 1px solid red; margin: 10px 5px 15px 5px; padding: 3px; background-color: #FFCECE;\\"><span class=\\"normalfont\\">$appname is currently in maintenance mode. Non-administrators cannot use $appname.</span></div>
") : (\'\'))."

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\" id=\\"noticeTable\\" style=\\"display: none;\\">
	<tr>
		<td width=\\"100%\\" valign=\\"top\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"headerText\\"><span class=\\"normalfonttablehead\\"><b>System Notice [<a href=\\"#\\" onClick=\\"closeNotice(); return false;\\"><span class=\\"normalfonttablehead\\">close</span></a>]</b></span></th>
</tr>
<tr style=\\"highRow\\">
	<td class=\\"highBothCell\\"><span class=\\"normalfont\\" id=\\"noticeText\\"></span></td>
</tr>
</table>
		</td>
	</tr>
</table>"',
    'upgraded' => '0',
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
    'upgraded' => '0',
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
    'upgraded' => '0',
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
    'upgraded' => '0',
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
var previewBoth = (\'$hiveuser[preview]\' == \'both\' ? true : false);

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

function confirmEmpty() {
	if (confirm("Are you sure you wish to empty this folder? This action is non-reversible!")) {
		window.location=\'folders.update.php?empty=Empty&return=-3&folder[-3]=yes\';
	}
}

-->
</script>
</head>
<body onkeydown="return moveArrow();">

$header

<%if $showexpiringsub %>
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
	<tr>
		<td width="100%" valign="top">
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="headerText"><span class="normalfonttablehead"><b>Your Subscription</b></span></th>
</tr>
<tr style="highRow">
	<td class="highBothCell"><span class="normalfont">Your subscription to the <b>$cursub[name]</b> plan will expire on <b>$cursub[expires]</b>. Click <a href="options.subscription.php">here</a> to renew.</span></td>
</tr>
</table>
		</td>
	</tr>
</table>
<%endif%>

<form action="{<INDEX_FILE>}" method="post" name="form">
<input type="hidden" name="cmd" id="cmd" value="dostuff" />
<input type="hidden" name="folderid" value="$folderid" />
<input type="hidden" name="movetofolderid" value="$folderid" />
<input type="hidden" name="remove" value="0" />

<table cellpadding="0" border="0" cellspacing="1" width="100%" align="center">
<tr>
	<td width="100%" valign="top">
$topbox

<%if !empty($draftbits) %>
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
	<tr>
		<td width="100%" valign="top">
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="headerText"><span class="normalfonttablehead"><b>You still have unsent drafts!</b></span></span></th>
</tr>
$draftbits
</table>
	</td>
</tr>
</table>

<br />
<%endif%>

<%if $hiveuser[preview] == \'top\' or $hiveuser[preview] == \'both\' %>
$previewtop<br />
<%endif%>

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
	<tr>
		<td width="100%" valign="top">
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerLeftCell">&nbsp;</th>
	<th class="headerCell" width="15" nowrap="nowrap"><span class="headerText"><a href="{<INDEX_FILE>}?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=flagged"><img src="$skin[images]/flag.gif" alt="Flagged?" border="0" /></a></span></th>
$colheaders
	<th class="headerRightCell"><input name="allbox" type="checkbox" value="Check All" title="Select/Deselect All" onClick="checkAll(this.form); changeButtonsStatus(!this.checked); if (this.checked) makeRows(\'first\'); else makeRows(\'second\');" /></th>
</tr>
<%if empty($mailbits) %>
	<tr style="normalRow">
		<td class="normalBothCell" align="center" colspan="10"><span class="normalfont">No messages in this folder!</span></td>
	</tr>
<%else%>
	$mailbits
<%endif%>
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
	<td align="right"><span class="smallfont"><%if $folderid != -3 %><b>Note:</b> deleted messages will be moved to the Trash Can.<br />Hold down Shift key when clicking to completely delete the messages.<%else%><input type="button" class="bginput" name="empty" value="Empty Trash" onClick="confirmEmpty()"><%endif%></span></td>
</tr>
</table>
		</td>
		<%if $hiveuser[cancalendar] and $folderid == -1 and $hiveuser[caloninbox] and (empty($topbox) or $wrap != 0) %>
		<td valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="150">
				$calendar
			</table>
		</td>
		<%endif%>
	</tr>
</table>

<%if $hiveuser[preview] == \'bottom\' or $hiveuser[preview] == \'both\' %>
<br />$previewbottom
<%endif%>

	</td>
</tr>
</table>
</form>

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
var previewBoth = (\'$hiveuser[preview]\' == \'both\' ? true : false);

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

function confirmEmpty() {
	if (confirm(\\"Are you sure you wish to empty this folder? This action is non-reversible!\\")) {
		window.location=\'folders.update.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}empty=Empty&return=-3&folder[-3]=yes\';
	}
}

-->
</script>
</head>
<body onkeydown=\\"return moveArrow();\\">

$GLOBALS[header]

".(($showexpiringsub ) ? ("
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
	<tr>
		<td width=\\"100%\\" valign=\\"top\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"headerText\\"><span class=\\"normalfonttablehead\\"><b>Your Subscription</b></span></th>
</tr>
<tr style=\\"highRow\\">
	<td class=\\"highBothCell\\"><span class=\\"normalfont\\">Your subscription to the <b>$cursub[name]</b> plan will expire on <b>$cursub[expires]</b>. Click <a href=\\"options.subscription.php{$GLOBALS[session_url]}\\">here</a> to renew.</span></td>
</tr>
</table>
		</td>
	</tr>
</table>
") : (\'\'))."

<form action=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"cmd\\" id=\\"cmd\\" value=\\"dostuff\\" />
<input type=\\"hidden\\" name=\\"folderid\\" value=\\"$folderid\\" />
<input type=\\"hidden\\" name=\\"movetofolderid\\" value=\\"$folderid\\" />
<input type=\\"hidden\\" name=\\"remove\\" value=\\"0\\" />

<table cellpadding=\\"0\\" border=\\"0\\" cellspacing=\\"1\\" width=\\"100%\\" align=\\"center\\">
<tr>
	<td width=\\"100%\\" valign=\\"top\\">
$topbox

".((!empty($draftbits) ) ? ("
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
	<tr>
		<td width=\\"100%\\" valign=\\"top\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"headerText\\"><span class=\\"normalfonttablehead\\"><b>You still have unsent drafts!</b></span></span></th>
</tr>
$draftbits
</table>
	</td>
</tr>
</table>

<br />
") : (\'\'))."

".(($hiveuser[preview] == \'top\' or $hiveuser[preview] == \'both\' ) ? ("
$previewtop<br />
") : (\'\'))."

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
	<tr>
		<td width=\\"100%\\" valign=\\"top\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerLeftCell\\">&nbsp;</th>
	<th class=\\"headerCell\\" width=\\"15\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=flagged\\"><img src=\\"{$GLOBALS[skin][images]}/flag.gif\\" alt=\\"Flagged?\\" border=\\"0\\" /></a></span></th>
$colheaders
	<th class=\\"headerRightCell\\"><input name=\\"allbox\\" type=\\"checkbox\\" value=\\"Check All\\" title=\\"Select/Deselect All\\" onClick=\\"checkAll(this.form); changeButtonsStatus(!this.checked); if (this.checked) makeRows(\'first\'); else makeRows(\'second\');\\" /></th>
</tr>
".((empty($mailbits) ) ? ("
	<tr style=\\"normalRow\\">
		<td class=\\"normalBothCell\\" align=\\"center\\" colspan=\\"10\\"><span class=\\"normalfont\\">No messages in this folder!</span></td>
	</tr>
") : ("
	$mailbits
"))."
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
	<td align=\\"right\\"><span class=\\"smallfont\\">".(($folderid != -3 ) ? ("<b>Note:</b> deleted messages will be moved to the Trash Can.<br />Hold down Shift key when clicking to completely delete the messages.") : ("<input type=\\"button\\" class=\\"bginput\\" name=\\"empty\\" value=\\"Empty Trash\\" onClick=\\"confirmEmpty()\\">"))."</span></td>
</tr>
</table>
		</td>
		".(($hiveuser[cancalendar] and $folderid == -1 and $hiveuser[caloninbox] and (empty($topbox) or $wrap != 0) ) ? ("
		<td valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"150\\">
				$calendar
			</table>
		</td>
		") : (\'\'))."
	</tr>
</table>

".(($hiveuser[preview] == \'bottom\' or $hiveuser[preview] == \'both\' ) ? ("
<br />$previewbottom
") : (\'\'))."

	</td>
</tr>
</table>
</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'index_drafts_bit' => 
  array (
    'templategroupid' => '2',
    'user_data' => '<tr class="{newclassname}Row">
	<td class="{classname}BothCell"><span class="normalfont"><a href="compose.email.php?draftid=$draft[draftid]">$mail[subject]</a></span></td>
</tr>
',
    'parsed_data' => '"<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\"><span class=\\"normalfont\\"><a href=\\"compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}draftid=$draft[draftid]\\">$mail[subject]</a></span></td>
</tr>
"',
    'upgraded' => '0',
  ),
  'index_events' => 
  array (
    'templategroupid' => '2',
    'user_data' => '<tr class="{newclassname}Row">
	<td class="{classname}BothCell"><span class="normalfont">Upcoming events for the next $hiveuser[calreminder] day(s): $upcoming_events.</span></td>
</tr>',
    'parsed_data' => '"<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\"><span class=\\"normalfont\\">Upcoming events for the next $hiveuser[calreminder] day(s): $upcoming_events.</span></td>
</tr>"',
    'upgraded' => '0',
  ),
  'index_events_eventbit' => 
  array (
    'templategroupid' => '2',
    'user_data' => '<a title="$event[title]" href="calendar.event.php?eventid=$event[eventid]">$event[shorttitle]</a> ($event[date])',
    'parsed_data' => '"<a title=\\"$event[title]\\" href=\\"calendar.event.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}eventid=$event[eventid]\\">$event[shorttitle]</a> ($event[date])"',
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'index_header_attach' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell" width="15" nowrap="nowrap"><span class="headerText"><a href="{<INDEX_FILE>}?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=attach"><img src="$skin[images]/paperclip.gif" alt="Has attachments?" border="0" /></a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\" width=\\"15\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=attach\\"><img src=\\"{$GLOBALS[skin][images]}/paperclip.gif\\" alt=\\"Has attachments?\\" border=\\"0\\" /></a></span></th>
"',
    'upgraded' => '0',
  ),
  'index_header_datetime' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell"><span class="headerText"><b><a href="{<INDEX_FILE>}?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=dateline"><span class="normalfonttablehead">Received</b></span>$sortimages[dateline]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\"><span class=\\"headerText\\"><b><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=dateline\\"><span class=\\"normalfonttablehead\\">Received</b></span>$sortimages[dateline]</a></span></th>
"',
    'upgraded' => '0',
  ),
  'index_header_datetime_sentitems' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell"><span class="headerText"><b><a href="{<INDEX_FILE>}?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=dateline"><span class="normalfonttablehead">Sent</b></span>$sortimages[dateline]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\"><span class=\\"headerText\\"><b><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=dateline\\"><span class=\\"normalfonttablehead\\">Sent</b></span>$sortimages[dateline]</a></span></th>
"',
    'upgraded' => '0',
  ),
  'index_header_from' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell"><span class="headerText"><a href="{<INDEX_FILE>}?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=name"><span class="normalfonttablehead"><b>From</b></span>$sortimages[name]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\"><span class=\\"headerText\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=name\\"><span class=\\"normalfonttablehead\\"><b>From</b></span>$sortimages[name]</a></span></th>
"',
    'upgraded' => '0',
  ),
  'index_header_priority' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell" width="15" nowrap="nowrap"><span class="headerText"><a href="{<INDEX_FILE>}?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=priority"><img src="$skin[images]/prio_high.gif" alt="Important?" border="0" /></a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\" width=\\"15\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=priority\\"><img src=\\"{$GLOBALS[skin][images]}/prio_high.gif\\" alt=\\"Important?\\" border=\\"0\\" /></a></span></th>
"',
    'upgraded' => '0',
  ),
  'index_header_size' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell" nowrap="nowrap"><span class="headerText"><b><a href="{<INDEX_FILE>}?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=size"><span class="normalfonttablehead">Size</b></span>$sortimages[size]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><b><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=size\\"><span class=\\"normalfonttablehead\\">Size</b></span>$sortimages[size]</a></span></th>
"',
    'upgraded' => '0',
  ),
  'index_header_subject' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell"><span class="headerText"><a href="{<INDEX_FILE>}?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=subject"><span class="normalfonttablehead"><b>Message Subject</b></span>$sortimages[subject]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\"><span class=\\"headerText\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=subject\\"><span class=\\"normalfonttablehead\\"><b>Message Subject</b></span>$sortimages[subject]</a></span></th>
"',
    'upgraded' => '0',
  ),
  'index_header_to' => 
  array (
    'templategroupid' => '2',
    'user_data' => '	<th class="headerCell"><span class="headerText"><a href="{<INDEX_FILE>}?folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=recipients"><span class="normalfonttablehead"><b>To</b></span>$sortimages[recipients]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\"><span class=\\"headerText\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$folderid&daysprune=$daysprune&perpage=$perpage&sortorder=$newsortorder&sortby=recipients\\"><span class=\\"normalfonttablehead\\"><b>To</b></span>$sortimages[recipients]</a></span></th>
"',
    'upgraded' => '0',
  ),
  'index_poperrors' => 
  array (
    'templategroupid' => '2',
    'user_data' => '<tr class="highRow">
	<td class="highBothCell"><span class="normalfont">The following errors occured while trying to connect to your POP accounts:<br />$poperror</span></td>
</tr>',
    'parsed_data' => '"<tr class=\\"highRow\\">
	<td class=\\"highBothCell\\"><span class=\\"normalfont\\">The following errors occured while trying to connect to your POP accounts:<br />$poperror</span></td>
</tr>"',
    'upgraded' => '0',
  ),
  'index_poperror_connection' => 
  array (
    'templategroupid' => '2',
    'user_data' => 'Couldn\'t connect to server.',
    'parsed_data' => '"Couldn\'t connect to server."',
    'upgraded' => '0',
  ),
  'index_poperror_login' => 
  array (
    'templategroupid' => '2',
    'user_data' => 'The login information was not accepted by the server.',
    'parsed_data' => '"The login information was not accepted by the server."',
    'upgraded' => '0',
  ),
  'index_poperror_unexpected' => 
  array (
    'templategroupid' => '2',
    'user_data' => 'An unexpected error occurred.',
    'parsed_data' => '"An unexpected error occurred."',
    'upgraded' => '0',
  ),
  'index_preview' => 
  array (
    'templategroupid' => '2',
    'user_data' => '<table cellpadding="4" cellspacing="0" class="normalTable" width="100%" id="previewPaneHide$which" style="display: <%if $hiveuser[previewhidden] %>none<%endif%>;">
<tr class="headerRow">
	<th class="headerBothCell"><span class="headerText"><b><span class="normalfonttablehead">Preview Pane</span> (<span onClick="showHidePreviewPane();" style="cursor: hand">hide</span>)</b></span></th>
</tr>
<tr class="normalRow">
	<td class="normalBothCell" align="left"><iframe src="read.email.php?messageid=-1&show=msg" style="background-color: $skin[firstalt]; width: 100%; height: 160px;" scrolling="yes" allowtransparency="true" id="previewFrame$which" frameborder="no">Your browser does not support inline frames.</iframe></td>
</tr>
</table>

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%" id="previewPaneShow$which" style="display: <%if !$hiveuser[previewhidden] %>none<%endif%>;">
<tr class="headerRow">
	<th class="headerBothCell"><span class="headerText"><b><span class="normalfonttablehead">Preview Pane</span> (<span onClick="showHidePreviewPane();" style="cursor: hand">show</span>)</b></span></th>
</tr>
</table>',
    'parsed_data' => '"<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\" id=\\"previewPaneHide$which\\" style=\\"display: ".(($hiveuser[previewhidden] ) ? ("none") : (\'\')).";\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"headerText\\"><b><span class=\\"normalfonttablehead\\">Preview Pane</span> (<span onClick=\\"showHidePreviewPane();\\" style=\\"cursor: hand\\">hide</span>)</b></span></th>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalBothCell\\" align=\\"left\\"><iframe src=\\"read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=-1&show=msg\\" style=\\"background-color: {$GLOBALS[skin][firstalt]}; width: 100%; height: 160px;\\" scrolling=\\"yes\\" allowtransparency=\\"true\\" id=\\"previewFrame$which\\" frameborder=\\"no\\">Your browser does not support inline frames.</iframe></td>
</tr>
</table>

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\" id=\\"previewPaneShow$which\\" style=\\"display: ".((!$hiveuser[previewhidden] ) ? ("none") : (\'\')).";\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"headerText\\"><b><span class=\\"normalfonttablehead\\">Preview Pane</span> (<span onClick=\\"showHidePreviewPane();\\" style=\\"cursor: hand\\">show</span>)</b></span></th>
</tr>
</table>"',
    'upgraded' => '0',
  ),
  'index_spacegauge' => 
  array (
    'templategroupid' => '2',
    'user_data' => '<tr class="{newclassname}Row">
	<td class="{classname}BothCell"><span class="normalfont">You are using $spacepercent% of your storage. ({$_mailmb}MB / $hiveuser[maxmb]MB).</span><br /><br />
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
    'parsed_data' => '"<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\"><span class=\\"normalfont\\">You are using $spacepercent% of your storage. ({$_mailmb}MB / $hiveuser[maxmb]MB).</span><br /><br />
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
    'upgraded' => '0',
  ),
  'index_spacewarning' => 
  array (
    'templategroupid' => '2',
    'user_data' => '<tr class="{newclassname}Row">
	<td class="{classname}BothCell"><b><span class="normalfont">You are currently using {$_mailmb}MB of data, passing the limit of $hiveuser[maxmb]MB.<br />You will not be able to send or receive any further messages until you delete older messages.</b></span></td>
</tr>',
    'parsed_data' => '"<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\"><b><span class=\\"normalfont\\">You are currently using {$_mailmb}MB of data, passing the limit of $hiveuser[maxmb]MB.<br />You will not be able to send or receive any further messages until you delete older messages.</b></span></td>
</tr>"',
    'upgraded' => '0',
  ),
  'index_topbox' => 
  array (
    'templategroupid' => '2',
    'user_data' => '<table cellpadding="4" cellspacing="0" width="100%">
	<tr>
		<td valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			<tr class="headerRow">
				<th class="headerBothCell"><span class="normalfonttablehead"><b>Welcome back $hiveuser[realname] [<a href="user.logout.php"><span class="normalfonttablehead">log out</span></a>]</b></span></th>
			</tr>
			$space
			$unreads
			$upcoming_events
			$poperror
			</table>
			<br />
		</td>
		<%if $hiveuser[cancalendar] and $folderid == -1 and $hiveuser[caloninbox] and $wrap == 0 %>
		<td valign="top" width="150">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="150">
				$calendar
			</table>
		</td>
		<%endif%>
	</tr>
</table>
<br />',
    'parsed_data' => '"<table cellpadding=\\"4\\" cellspacing=\\"0\\" width=\\"100%\\">
	<tr>
		<td valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			<tr class=\\"headerRow\\">
				<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Welcome back $hiveuser[realname] [<a href=\\"user.logout.php{$GLOBALS[session_url]}\\"><span class=\\"normalfonttablehead\\">log out</span></a>]</b></span></th>
			</tr>
			$space
			$unreads
			$upcoming_events
			$poperror
			</table>
			<br />
		</td>
		".(($hiveuser[cancalendar] and $folderid == -1 and $hiveuser[caloninbox] and $wrap == 0 ) ? ("
		<td valign=\\"top\\" width=\\"150\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"150\\">
				$calendar
			</table>
		</td>
		") : (\'\'))."
	</tr>
</table>
<br />"',
    'upgraded' => '0',
  ),
  'index_unreads' => 
  array (
    'templategroupid' => '2',
    'user_data' => '<tr class="{newclassname}Row">
	<td class="{classname}BothCell"><span class="normalfont">You have $unreads.</span></td>
</tr>',
    'parsed_data' => '"<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\"><span class=\\"normalfont\\">You have $unreads.</span></td>
</tr>"',
    'upgraded' => '0',
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
			<td align="left"><input type="text" name="username" class="bginput" tabindex="1" /> <select name="userdomain">$domainname_options</select></td>
		</tr>
		<tr>
			<td valign="top" align="right"><span class="normalfont">Password:&nbsp;</span></td>
			<td align="left"><input type="password" name="password" class="bginput" tabindex="2" /></td>
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
			<td align="center" colspan="2"><br /><input type="submit" value=" Log in " class="bginput" tabindex="3" /></td>
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
			<td align=\\"left\\"><input type=\\"text\\" name=\\"username\\" class=\\"bginput\\" tabindex=\\"1\\" /> <select name=\\"userdomain\\">$domainname_options</select></td>
		</tr>
		<tr>
			<td valign=\\"top\\" align=\\"right\\"><span class=\\"normalfont\\">Password:&nbsp;</span></td>
			<td align=\\"left\\"><input type=\\"password\\" name=\\"password\\" class=\\"bginput\\" tabindex=\\"2\\" /></td>
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
			<td align=\\"center\\" colspan=\\"2\\"><br /><input type=\\"submit\\" value=\\" Log in \\" class=\\"bginput\\" tabindex=\\"3\\" /></td>
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
    'upgraded' => '0',
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
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Secret question:</b></span>
	<br />
	<span class="smallfont">In order to verify you are the holder of this account, please enter<br />the answer to the secret question you chose when signing up.</span></td>
	<td class="{classname}RightCell" width="40%" valign="top"><span class="normalfont"><b>$user[question]</b></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Secret answer:</b></span></td>
	<td class="{classname}RightCell" width="40%"><input type="text" class="bginput" name="answer" size="40" /></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>New password:</b></span>
	<br />
	<span class="smallfont">Please select a new password for your account.</span></td>
	<td class="{classname}RightCell" width="40%"><input type="password" class="bginput" name="password" size="40" /></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Retype password:</b></span>
	<br />
	<span class="smallfont">Repeat the password to verify it\'s correct.</span></td>
	<td class="{classname}RightCell" width="40%"><input type="password" class="bginput" name="password_repeat" size="40" /></td>
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
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secret question:</b></span>
	<br />
	<span class=\\"smallfont\\">In order to verify you are the holder of this account, please enter<br />the answer to the secret question you chose when signing up.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>$user[question]</b></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secret answer:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"answer\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>New password:</b></span>
	<br />
	<span class=\\"smallfont\\">Please select a new password for your account.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"password\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Retype password:</b></span>
	<br />
	<span class=\\"smallfont\\">Repeat the password to verify it\'s correct.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"password_repeat\\" size=\\"40\\" /></td>
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
    'upgraded' => '0',
  ),
  'lostpw_verifysend' => 
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
<input type="hidden" name="cmd" value="updatesend" />
<input type="hidden" name="username" value="$user[username]" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="700" align="center">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Lost Password</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Secret question:</b></span>
	<br />
	<span class="smallfont">In order to verify you are the holder of this account, please enter<br />the answer to the secret question you chose when signing up.</span></td>
	<td class="{classname}RightCell" width="40%" valign="top"><span class="normalfont"><b>$user[question]</b></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Secret answer:</b></span></td>
	<td class="{classname}RightCell" width="40%"><input type="text" class="bginput" name="answer" size="40" /></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="100%" colspan="2" valign="top"><span class="normalfont">Upon clicking Update Password, your answer will be verified. Assuming it is correct, a new password will be generated and sent to the secondary email address you provided during initial registration. If you can no longer receive email at that address, you will need to contact the $appname administrator.</span>
</tr>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="700" align="center">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="pass" value="Send Password" />
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
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"updatesend\\" />
<input type=\\"hidden\\" name=\\"username\\" value=\\"$user[username]\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\" align=\\"center\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Lost Password</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secret question:</b></span>
	<br />
	<span class=\\"smallfont\\">In order to verify you are the holder of this account, please enter<br />the answer to the secret question you chose when signing up.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>$user[question]</b></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secret answer:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"answer\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"100%\\" colspan=\\"2\\" valign=\\"top\\"><span class=\\"normalfont\\">Upon clicking Update Password, your answer will be verified. Assuming it is correct, a new password will be generated and sent to the secondary email address you provided during initial registration. If you can no longer receive email at that address, you will need to contact the $appname administrator.</span>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"700\\" align=\\"center\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"pass\\" value=\\"Send Password\\" />
	</td>
</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'lostpw_verifysend_body' => 
  array (
    'templategroupid' => '12',
    'user_data' => 'Hello,

This email contains the new password for your $appname account. After logging in with it, you can change it to something that will be easier to remember by going to Preferences, then selecting Password and Security.

New password: $newpass

Regards,
The $appname Team',
    'parsed_data' => '"Hello,

This email contains the new password for your $appname account. After logging in with it, you can change it to something that will be easier to remember by going to Preferences, then selecting Password and Security.

New password: $newpass

Regards,
The $appname Team"',
    'upgraded' => '0',
  ),
  'lostpw_verifysend_subject' => 
  array (
    'templategroupid' => '12',
    'user_data' => 'Your New Password for $appname',
    'parsed_data' => '"Your New Password for $appname"',
    'upgraded' => '0',
  ),
  'mailbit' => 
  array (
    'templategroupid' => '16',
    'user_data' => '<tr class="normalRow" $mail[unreadstyle] onSelectStart="return false;" id="row$mail[messageid]" onDblClick="window.location = \'read.email.php?messageid=$mail[messageid]\';">
	<td class="LeftCell"><img src="$skin[images]/messages/$mail[image].gif" alt="$skin[images]/$mail[image].gif" /></td>
	<td class="$bgcolors[flagged]Cell" onContextMenu="checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;" onClick="checkMail($mail[messageid], 0, 1);">$mail[flagimg]</td>
$columns
	<%if infile(\'search\') %><td class="$bgcolors[folderid]Cell" nowrap="nowrap" width="20%" align="center" onContextMenu="checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;" onClick="checkMail($mail[messageid], 0, 1);"><a href="{<INDEX_FILE>}?folderid=$mail[folderid]"><span class="smallfont">$mail[folder]</span></a></td><%endif%>
	<td class="RightCell"><input type="checkbox" name="mails[$mail[messageid]]" id="mails$mail[messageid]" value="yes" onClick="this.checked = !this.checked; checkMail($mail[messageid], 0, 0, 1); this.checked = !this.checked;" /></td>
</tr>',
    'parsed_data' => '"<tr class=\\"normalRow\\" $mail[unreadstyle] onSelectStart=\\"return false;\\" id=\\"row$mail[messageid]\\" onDblClick=\\"window.location = \'read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\';\\">
	<td class=\\"LeftCell\\"><img src=\\"{$GLOBALS[skin][images]}/messages/$mail[image].gif\\" alt=\\"{$GLOBALS[skin][images]}/$mail[image].gif\\" /></td>
	<td class=\\"$bgcolors[flagged]Cell\\" onContextMenu=\\"checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;\\" onClick=\\"checkMail($mail[messageid], 0, 1);\\">$mail[flagimg]</td>
$columns
	".((infile(\'search\') ) ? ("<td class=\\"$bgcolors[folderid]Cell\\" nowrap=\\"nowrap\\" width=\\"20%\\" align=\\"center\\" onContextMenu=\\"checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;\\" onClick=\\"checkMail($mail[messageid], 0, 1);\\"><a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderid=$mail[folderid]\\"><span class=\\"smallfont\\">$mail[folder]</span></a></td>") : (\'\'))."
	<td class=\\"RightCell\\"><input type=\\"checkbox\\" name=\\"mails[$mail[messageid]]\\" id=\\"mails$mail[messageid]\\" value=\\"yes\\" onClick=\\"this.checked = !this.checked; checkMail($mail[messageid], 0, 0, 1); this.checked = !this.checked;\\" /></td>
</tr>"',
    'upgraded' => '0',
  ),
  'mailbit_attach' => 
  array (
    'templategroupid' => '16',
    'user_data' => '	<td class="$bgcolors[attach]Cell" onContextMenu="checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;" onClick="checkMail($mail[messageid], 0, 1);">$mail[attach]</td>
',
    'parsed_data' => '"	<td class=\\"$bgcolors[attach]Cell\\" onContextMenu=\\"checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;\\" onClick=\\"checkMail($mail[messageid], 0, 1);\\">$mail[attach]</td>
"',
    'upgraded' => '0',
  ),
  'mailbit_datetime' => 
  array (
    'templategroupid' => '16',
    'user_data' => '	<td class="$bgcolors[dateline]Cell" nowrap="nowrap" width="20%" align="center" onContextMenu="checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;" onClick="checkMail($mail[messageid], 0, 1);"><span class="smallfont">$mail[date] <span class="timecolor">$mail[time]</span></span></td>
',
    'parsed_data' => '"	<td class=\\"$bgcolors[dateline]Cell\\" nowrap=\\"nowrap\\" width=\\"20%\\" align=\\"center\\" onContextMenu=\\"checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;\\" onClick=\\"checkMail($mail[messageid], 0, 1);\\"><span class=\\"smallfont\\">$mail[date] <span class=\\"timecolor\\">$mail[time]</span></span></td>
"',
    'upgraded' => '0',
  ),
  'mailbit_from' => 
  array (
    'templategroupid' => '16',
    'user_data' => '	<td class="$bgcolors[name]Cell" width="25%" align="left" onContextMenu="checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;" onClick="checkMail($mail[messageid], 0, 1);"><span class="normalfont"><%if $hiveuser[senderlink]%><a href="compose.email.php?email=$mail[link]" title="$mail[fromname]">$mail[shortfromname]</a><%else%>$mail[shortfromname]<%endif%></span></td>
',
    'parsed_data' => '"	<td class=\\"$bgcolors[name]Cell\\" width=\\"25%\\" align=\\"left\\" onContextMenu=\\"checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;\\" onClick=\\"checkMail($mail[messageid], 0, 1);\\"><span class=\\"normalfont\\">".(($hiveuser[senderlink]) ? ("<a href=\\"compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}email=$mail[link]\\" title=\\"$mail[fromname]\\">$mail[shortfromname]</a>") : ("$mail[shortfromname]"))."</span></td>
"',
    'upgraded' => '0',
  ),
  'mailbit_priority' => 
  array (
    'templategroupid' => '16',
    'user_data' => '	<td class="$bgcolors[priority]Cell" onContextMenu="checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;" onClick="checkMail($mail[messageid], 0, 1);">$mail[priority]</td>
',
    'parsed_data' => '"	<td class=\\"$bgcolors[priority]Cell\\" onContextMenu=\\"checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;\\" onClick=\\"checkMail($mail[messageid], 0, 1);\\">$mail[priority]</td>
"',
    'upgraded' => '0',
  ),
  'mailbit_size' => 
  array (
    'templategroupid' => '16',
    'user_data' => '	<td class="$bgcolors[size]Cell" width="20%" align="center" onContextMenu="checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;" onClick="checkMail($mail[messageid], 0, 1);"><span class="smallfont">$mail[kbsize]KB</span></td>
',
    'parsed_data' => '"	<td class=\\"$bgcolors[size]Cell\\" width=\\"20%\\" align=\\"center\\" onContextMenu=\\"checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;\\" onClick=\\"checkMail($mail[messageid], 0, 1);\\"><span class=\\"smallfont\\">$mail[kbsize]KB</span></td>
"',
    'upgraded' => '0',
  ),
  'mailbit_subject' => 
  array (
    'templategroupid' => '16',
    'user_data' => '	<td class="$bgcolors[subject]Cell" align="left" width="<%if infile(\'search\') %>45%<%else%>55%<%endif%>" onContextMenu="checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;" onClick="checkMail($mail[messageid], 0, 1);" <%if !empty($mail[\'bgcolor\']) %>style="background-color: $mail[bgcolor];"<%endif%>><span class="normalfont">$mail[sysimage]<a href="read.email.php?messageid=$mail[messageid]" title="$mail[subject]" <%if !empty($mail[\'color\']) %>style="color: $mail[color];"<%endif%>>$mail[shortsubject]</a></span></td>
',
    'parsed_data' => '"	<td class=\\"$bgcolors[subject]Cell\\" align=\\"left\\" width=\\"".((infile(\'search\') ) ? ("45%") : ("55%"))."\\" onContextMenu=\\"checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;\\" onClick=\\"checkMail($mail[messageid], 0, 1);\\" ".((!empty($mail[\'bgcolor\']) ) ? ("style=\\"background-color: $mail[bgcolor];\\"") : (\'\'))."><span class=\\"normalfont\\">$mail[sysimage]<a href=\\"read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\\" title=\\"$mail[subject]\\" ".((!empty($mail[\'color\']) ) ? ("style=\\"color: $mail[color];\\"") : (\'\')).">$mail[shortsubject]</a></span></td>
"',
    'upgraded' => '0',
  ),
  'mailbit_to' => 
  array (
    'templategroupid' => '16',
    'user_data' => '	<td class="$bgcolors[name]Cell" width="25%" align="left" onContextMenu="checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;" onClick="checkMail($mail[messageid], 0, 1);"><span class="normalfont">$mail[recipients]</span></td>
',
    'parsed_data' => '"	<td class=\\"$bgcolors[name]Cell\\" width=\\"25%\\" align=\\"left\\" onContextMenu=\\"checkMail($mail[messageid], 1, 1, 0, 1); contextForMail(event, $mail[messageid], \'$mail[image]\', $mail[isflagged]); return false;\\" onClick=\\"checkMail($mail[messageid], 0, 1);\\"><span class=\\"normalfont\\">$mail[recipients]</span></td>
"',
    'upgraded' => '0',
  ),
  'notification_message' => 
  array (
    'templategroupid' => '12',
    'user_data' => 'You have new mail from: $good[fromname] <$good[fromemail]>, the subject provided was $good[subject]

You can view your e-mail here: $gotourl',
    'parsed_data' => '"You have new mail from: $good[fromname] <$good[fromemail]>, the subject provided was $good[subject]

You can view your e-mail here: $gotourl"',
    'upgraded' => '0',
  ),
  'notification_subject' => 
  array (
    'templategroupid' => '12',
    'user_data' => 'You have new mail',
    'parsed_data' => '"You have new mail"',
    'upgraded' => '0',
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
<script type="text/javascript" language="JavaScript">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$header

<form action="options.aliases.php" method="post" onSubmit="extract_lists(this); return true;">
<input type="hidden" name="cmd" value="update" />
<input type="hidden" name="aliaslist" value="lists" />

<table cellpadding="4" cellspacing="0" width="100%">
	<tr>
		<td valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="150">
				<tr class="headerRow">
					<th class="headerBothCell"><span class="normalfonttablehead"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width="100%" valign="top">
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Your Aliases</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}BothCell" valign="top" colspan="2"><span class="normalfont"><b>Your aliases:</b></span>
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
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Deliver messages multiple times:</b></span>
	<br />
	<span class="smallfont">Would you like to display the same message more than once if it is sent to more than one of your aliases? Please note that this setting will only effect new messages.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="aliasmultimails" value="1" id="aliasmultimailson" $aliasmultimailson /> <label for="aliasmultimailson">Yes</label><br /><input type="radio" name="aliasmultimails" value="0" id="aliasmultimailsoff" $aliasmultimailsoff /> <label for="aliasmultimailsoff">No</label></span></td>
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
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$GLOBALS[header]

<form action=\\"options.aliases.php{$GLOBALS[session_url]}\\" method=\\"post\\" onSubmit=\\"extract_lists(this); return true;\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />
<input type=\\"hidden\\" name=\\"aliaslist\\" value=\\"lists\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" width=\\"100%\\">
	<tr>
		<td valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"150\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width=\\"100%\\" valign=\\"top\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Your Aliases</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\" valign=\\"top\\" colspan=\\"2\\"><span class=\\"normalfont\\"><b>Your aliases:</b></span>
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
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Deliver messages multiple times:</b></span>
	<br />
	<span class=\\"smallfont\\">Would you like to display the same message more than once if it is sent to more than one of your aliases? Please note that this setting will only effect new messages.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"aliasmultimails\\" value=\\"1\\" id=\\"aliasmultimailson\\" $aliasmultimailson /> <label for=\\"aliasmultimailson\\">Yes</label><br /><input type=\\"radio\\" name=\\"aliasmultimails\\" value=\\"0\\" id=\\"aliasmultimailsoff\\" $aliasmultimailsoff /> <label for=\\"aliasmultimailsoff\\">No</label></span></td>
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
		</td>
	</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'options_autoresponders' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html<%if $hiveuser[wysiwyg] %> XMLNS:ACE<%endif%>>
<head><title>$appname: Auto responder</title>
<%if $hiveuser[wysiwyg] %><?import namespace="ACE" implementation="misc/ace.htc" /><%endif%>
<!-- ?> -->
$css
<script language="JavaScript" type="text/javascript">
<!--

var totalSigs = $totalrResponses_real;
var workingWith = \'response\';
// This is the text that\'s added to default sigs (don\'t forget leading space)
var defstr = \' (default)\';
// The text that is displayed when no signature is selected
var defaultValue = \'(select a response to edit from the list)\';

function editorInit() {
	<%if $hiveuser[wysiwyg] %>
		idContent.editorWidth = "560";
		idContent.editorHeight = "140";
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
		idContent.content = defaultValue;
		idContent.style.background = \'$skin[formbackground]\';
		idContent.docBgColor = \'$skin[formbackground]\';
	<%else%>
		document.sigform.sigedit.value = defaultValue;
	<%endif%>
}

function getContent() {
	<%if $hiveuser[wysiwyg] %>
		return idContent.content;
	<%else%>
		return document.sigform.sigedit.value;
	<%endif%>
}

function getContentTagLess() {
	<%if $hiveuser[wysiwyg] %>
		return idContent.getText();
	<%else%>
		return \'\';
	<%endif%>
}

function setContent(value) {
	<%if $hiveuser[wysiwyg] %>
		idContent.content = value;
	<%else%>
		document.sigform.sigedit.value = value;
	<%endif%>
}

// -->
</script>
<script type="text/javascript" language="JavaScript">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
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

<table cellpadding="4" cellspacing="0" width="100%">
	<tr>
		<td valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="150">
				<tr class="headerRow">
					<th class="headerBothCell"><span class="normalfonttablehead"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width="100%" valign="top">
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Your auto responder</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Automatically respond to all messages:</b></span>
	<br />
	<span class="smallfont">If this is turned on, the default response will be be sent to anyone who sends you mail.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="autorespond" value="1" id="autorespondon" $autorespondon /> <label for="autorespondon">Yes</label><br /><input type="radio" name="autorespond" value="0" id="autorespondoff" $autorespondoff /> <label for="autorespondoff">No</label></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}BothCell" colspan="2" width="100%" valign="top"><span class="normalfont"><b>Responses Editor:</b><br />
	To edit a response, select it from the list below and edit it in large box.<br />
	To rename a response, select it then click the Rename button below and enter the new name.<br />
	To mark your default response, select it from the list and click the Make Default button below.<br />
	To create a new response, click the Create New button below and enter the name of the new response.<%if $totalresponses >= $hiveuser[\'maxresponses\'] %><br />(<b>Note</b>: You may only have up to $hiveuser[maxresponses] responses. You won\'t be able to create new responses until you delete at least some of your current responses.)<%endif%><br />
	To delete a response, select it from the list and click the Delete button below. <b>Note</b>: You cannot remove your default response, unless it is the only response you have.<br />
	<br />
	Please remember to click the Update Responses button at the bottom of this page, or else any changes that you make here will have no effect!</span><br /><br />
	<table>
		<tr>
			<td valign="top"><select name="sigs" size="<%if $hiveuser[wysiwyg] %>14<%else%>9<%endif%>" onChange="updateSigDisplay(this.form);">
					$sig_options
				</select></td>
			<td valign="top"><%if $hiveuser[wysiwyg] %><ACE:AdvContentEditor id="idContent" tabindex="3" /><%else%><textarea name="sigedit" cols="70" rows="8">(select a response to edit from the list)</textarea><%endif%></td>
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
		</td>
	</tr>
</table>

</form>

<script language="JavaScript">
<!--
editorInit();
// -->
</script>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html".(($hiveuser[wysiwyg] ) ? (" XMLNS:ACE") : (\'\')).">
<head><title>$appname: Auto responder</title>
".(($hiveuser[wysiwyg] ) ? ("<?import namespace=\\"ACE\\" implementation=\\"misc/ace.htc\\" />") : (\'\'))."
<!-- ?> -->
$GLOBALS[css]
<script language=\\"JavaScript\\" type=\\"text/javascript\\">
<!--

var totalSigs = $totalrResponses_real;
var workingWith = \'response\';
// This is the text that\'s added to default sigs (don\'t forget leading space)
var defstr = \' (default)\';
// The text that is displayed when no signature is selected
var defaultValue = \'(select a response to edit from the list)\';

function editorInit() {
	".(($hiveuser[wysiwyg] ) ? ("
		idContent.editorWidth = \\"560\\";
		idContent.editorHeight = \\"140\\";
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
		idContent.content = defaultValue;
		idContent.style.background = \'{$GLOBALS[skin][formbackground]}\';
		idContent.docBgColor = \'{$GLOBALS[skin][formbackground]}\';
	") : ("
		document.sigform.sigedit.value = defaultValue;
	"))."
}

function getContent() {
	".(($hiveuser[wysiwyg] ) ? ("
		return idContent.content;
	") : ("
		return document.sigform.sigedit.value;
	"))."
}

function getContentTagLess() {
	".(($hiveuser[wysiwyg] ) ? ("
		return idContent.getText();
	") : ("
		return \'\';
	"))."
}

function setContent(value) {
	".(($hiveuser[wysiwyg] ) ? ("
		idContent.content = value;
	") : ("
		document.sigform.sigedit.value = value;
	"))."
}

// -->
</script>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
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

<table cellpadding=\\"4\\" cellspacing=\\"0\\" width=\\"100%\\">
	<tr>
		<td valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"150\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width=\\"100%\\" valign=\\"top\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Your auto responder</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Automatically respond to all messages:</b></span>
	<br />
	<span class=\\"smallfont\\">If this is turned on, the default response will be be sent to anyone who sends you mail.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"autorespond\\" value=\\"1\\" id=\\"autorespondon\\" $autorespondon /> <label for=\\"autorespondon\\">Yes</label><br /><input type=\\"radio\\" name=\\"autorespond\\" value=\\"0\\" id=\\"autorespondoff\\" $autorespondoff /> <label for=\\"autorespondoff\\">No</label></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\" colspan=\\"2\\" width=\\"100%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Responses Editor:</b><br />
	To edit a response, select it from the list below and edit it in large box.<br />
	To rename a response, select it then click the Rename button below and enter the new name.<br />
	To mark your default response, select it from the list and click the Make Default button below.<br />
	To create a new response, click the Create New button below and enter the name of the new response.".(($totalresponses >= $hiveuser[\'maxresponses\'] ) ? ("<br />(<b>Note</b>: You may only have up to $hiveuser[maxresponses] responses. You won\'t be able to create new responses until you delete at least some of your current responses.)") : (\'\'))."<br />
	To delete a response, select it from the list and click the Delete button below. <b>Note</b>: You cannot remove your default response, unless it is the only response you have.<br />
	<br />
	Please remember to click the Update Responses button at the bottom of this page, or else any changes that you make here will have no effect!</span><br /><br />
	<table>
		<tr>
			<td valign=\\"top\\"><select name=\\"sigs\\" size=\\"".(($hiveuser[wysiwyg] ) ? ("14") : ("9"))."\\" onChange=\\"updateSigDisplay(this.form);\\">
					$sig_options
				</select></td>
			<td valign=\\"top\\">".(($hiveuser[wysiwyg] ) ? ("<ACE:AdvContentEditor id=\\"idContent\\" tabindex=\\"3\\" />") : ("<textarea name=\\"sigedit\\" cols=\\"70\\" rows=\\"8\\">(select a response to edit from the list)</textarea>"))."</td>
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
		</td>
	</tr>
</table>

</form>

<script language=\\"JavaScript\\">
<!--
editorInit();
// -->
</script>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'options_compose' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Compose Options</title>
$css
<script type="text/javascript" language="JavaScript">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$header

<form action="options.compose.php" method="post">
<input type="hidden" name="cmd" value="update" />

<table cellpadding="4" cellspacing="0" width="100%">
	<tr>
		<td valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="150">
				<tr class="headerRow">
					<th class="headerBothCell"><span class="normalfonttablehead"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width="100%" valign="top">
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Reply Settings</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Include original message:</b></span>
	<br />
	<span class="smallfont">Enable this to include the original message when forwarding or replying to an email.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="includeorig" value="1" id="includeorigon" $includeorigon /> <label for="includeorigon">Yes</label><br /><input type="radio" name="includeorig" value="0" id="includeorigoff" $includeorigoff /> <label for="includeorigoff">No</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Original message prefix:</b></span>
	<br />
	<span class="smallfont">When replying to a message, if you have the option above enabled,<br />each line of the original message will be prefixed with this string:</span></td>
	<td class="{classname}RightCell" width="40%"><input type="text" class="bginput" name="replychar" value="$hiveuser[replychar]" size="15" maxlength="15" /></td>
</tr>
<!-- +++++++++++++++++++++++++++++++++++++ -->
<%if $hiveuser[\'cansendhtml\'] %>
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>WYSIWYG Editor Settings</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Enable advanced WYSIWYG editor:</b></span>
	<br />
	<span class="smallfont">Turn this on to use the \'What You See Is What You Get\' editor by default.<br />This editor only works under Windows with Internet Explorer 5.0+.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="wysiwyg" value="1" id="wysiwygon" $wysiwygon /> <label for="wysiwygon">Yes</label><br /><input type="radio" name="wysiwyg" value="0" id="wysiwygoff" $wysiwygoff /> <label for="wysiwygoff">No</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Default font:</b></span>
	<br />
	<span class="smallfont">This is the font outgoing messages will be sent with, unless you change it.</span></td>
	<td class="{classname}RightCell" width="40%">
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
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Default background color:</b></span>
	<br />
	<span class="smallfont">This is the background color outgoing messages will have, unless you change it.</span></td>
	<td class="{classname}RightCell" width="40%">
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
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Default account to use:</b></span>
	<br />
	<span class="smallfont">Choose the default account (or alias) to use when writing new messages.</span></td>
	<td class="{classname}RightCell" width="40%">
		<select name="defcompose">
			<option value="username" $defselected>$hiveuser[realname] &lt;$hiveuser[username]$hiveuser[domain]&gt;</option>
			<%if !empty($aliasoptions) %>
			<optgroup label="Aliases">
				$aliasoptions
			</optgroup>
			<%endif%>
			<%if !empty($popoptions) %>
			<optgroup label="POP3 Accounts">
				$popoptions
			</optgroup>
			<%endif%>
		</select>
	</td>
</tr>
<%if $hiveuser[canspell] %>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Automatically spell check messages:</b></span>
	<br />
	<span class="smallfont">If enabled, all messages will be checked for spelling mistakes before they are sent.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="autospell" value="1" id="autospellon" $autospellon /> <label for="autospellon">Yes</label><br /><input type="radio" name="autospell" value="0" id="autospelloff" $autospelloff /><label for="autospelloff">No</label></span></td>
</tr>
<%endif%>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Default Reply-To address:</b></span>
	<br />
	<span class="smallfont">The default address the Reply-To field contains.</span></td>
	<td class="{classname}RightCell" width="40%"><input type="text" class="bginput" name="replyto" value="$hiveuser[replyto]" size="40" /></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Display Reply-To field when composing:</b></span>
	<br />
	<span class="smallfont">If you\'d like to change the Reply-To address for individual messages, turn this on.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="composereplyto" value="1" id="composereplytoon" $composereplytoon /> <label for="composereplytoon">Yes</label><br /><input type="radio" name="composereplyto" value="0" id="composereplytooff" $composereplytooff /> <label for="composereplytooff">No</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Request read receipt:</b></span>
	<br />
	<span class="smallfont">Always request a read receipt for all outgoing messages.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="requestread" value="1" id="requestreadon" $requestreadon /> <label for="requestreadon">Yes</label><br /><input type="radio" name="requestread" value="0" id="requestreadoff" $requestreadoff /> <label for="requestreadoff">No</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Save copy of sent messages:</b></span>
	<br />
	<span class="smallfont">Keep a copy of messages you send in the Sent Items folder.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="savecopy" value="1" id="savecopyon" $savecopyon /> <label for="savecopyon">Yes</label><br /><input type="radio" name="savecopy" value="0" id="savecopyoff" $savecopyoff /> <label for="savecopyoff">No</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Folder to return to:</b></span>
	<br />
	<span class="smallfont">Choose if you\'d like to be taken to your Inbox or Sent Items folders after sending a message.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="returnsent" value="0" id="returnsentoff" $returnsentoff /> <label for="returnsentoff">Inbox</label><br /><input type="radio" name="returnsent" value="1" id="returnsenton" $returnsenton /> <label for="returnsenton">Sent Items</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Add recipients to address book:</b></span>
	<br />
	<span class="smallfont">Automatically add recipients of outgoing messages to your address book.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="addrecips" value="1" id="addrecipson" $addrecipson /> <label for="addrecipson">Yes</label><br /><input type="radio" name="addrecips" value="0" id="addrecipsoff" $addrecipsoff /><label for="addrecipsoff">No</label></span></td>
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
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$GLOBALS[header]

<form action=\\"options.compose.php{$GLOBALS[session_url]}\\" method=\\"post\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" width=\\"100%\\">
	<tr>
		<td valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"150\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width=\\"100%\\" valign=\\"top\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Reply Settings</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Include original message:</b></span>
	<br />
	<span class=\\"smallfont\\">Enable this to include the original message when forwarding or replying to an email.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"includeorig\\" value=\\"1\\" id=\\"includeorigon\\" $includeorigon /> <label for=\\"includeorigon\\">Yes</label><br /><input type=\\"radio\\" name=\\"includeorig\\" value=\\"0\\" id=\\"includeorigoff\\" $includeorigoff /> <label for=\\"includeorigoff\\">No</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Original message prefix:</b></span>
	<br />
	<span class=\\"smallfont\\">When replying to a message, if you have the option above enabled,<br />each line of the original message will be prefixed with this string:</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"replychar\\" value=\\"$hiveuser[replychar]\\" size=\\"15\\" maxlength=\\"15\\" /></td>
</tr>
<!-- +++++++++++++++++++++++++++++++++++++ -->
".(($hiveuser[\'cansendhtml\'] ) ? ("
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>WYSIWYG Editor Settings</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Enable advanced WYSIWYG editor:</b></span>
	<br />
	<span class=\\"smallfont\\">Turn this on to use the \'What You See Is What You Get\' editor by default.<br />This editor only works under Windows with Internet Explorer 5.0+.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"wysiwyg\\" value=\\"1\\" id=\\"wysiwygon\\" $wysiwygon /> <label for=\\"wysiwygon\\">Yes</label><br /><input type=\\"radio\\" name=\\"wysiwyg\\" value=\\"0\\" id=\\"wysiwygoff\\" $wysiwygoff /> <label for=\\"wysiwygoff\\">No</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Default font:</b></span>
	<br />
	<span class=\\"smallfont\\">This is the font outgoing messages will be sent with, unless you change it.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\">
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
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Default background color:</b></span>
	<br />
	<span class=\\"smallfont\\">This is the background color outgoing messages will have, unless you change it.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\">
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
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Default account to use:</b></span>
	<br />
	<span class=\\"smallfont\\">Choose the default account (or alias) to use when writing new messages.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\">
		<select name=\\"defcompose\\">
			<option value=\\"username\\" $defselected>$hiveuser[realname] &lt;$hiveuser[username]$hiveuser[domain]&gt;</option>
			".((!empty($aliasoptions) ) ? ("
			<optgroup label=\\"Aliases\\">
				$aliasoptions
			</optgroup>
			") : (\'\'))."
			".((!empty($popoptions) ) ? ("
			<optgroup label=\\"POP3 Accounts\\">
				$popoptions
			</optgroup>
			") : (\'\'))."
		</select>
	</td>
</tr>
".(($hiveuser[canspell] ) ? ("
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Automatically spell check messages:</b></span>
	<br />
	<span class=\\"smallfont\\">If enabled, all messages will be checked for spelling mistakes before they are sent.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"autospell\\" value=\\"1\\" id=\\"autospellon\\" $autospellon /> <label for=\\"autospellon\\">Yes</label><br /><input type=\\"radio\\" name=\\"autospell\\" value=\\"0\\" id=\\"autospelloff\\" $autospelloff /><label for=\\"autospelloff\\">No</label></span></td>
</tr>
") : (\'\'))."
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Default Reply-To address:</b></span>
	<br />
	<span class=\\"smallfont\\">The default address the Reply-To field contains.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"replyto\\" value=\\"$hiveuser[replyto]\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Display Reply-To field when composing:</b></span>
	<br />
	<span class=\\"smallfont\\">If you\'d like to change the Reply-To address for individual messages, turn this on.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"composereplyto\\" value=\\"1\\" id=\\"composereplytoon\\" $composereplytoon /> <label for=\\"composereplytoon\\">Yes</label><br /><input type=\\"radio\\" name=\\"composereplyto\\" value=\\"0\\" id=\\"composereplytooff\\" $composereplytooff /> <label for=\\"composereplytooff\\">No</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Request read receipt:</b></span>
	<br />
	<span class=\\"smallfont\\">Always request a read receipt for all outgoing messages.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"requestread\\" value=\\"1\\" id=\\"requestreadon\\" $requestreadon /> <label for=\\"requestreadon\\">Yes</label><br /><input type=\\"radio\\" name=\\"requestread\\" value=\\"0\\" id=\\"requestreadoff\\" $requestreadoff /> <label for=\\"requestreadoff\\">No</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Save copy of sent messages:</b></span>
	<br />
	<span class=\\"smallfont\\">Keep a copy of messages you send in the Sent Items folder.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"savecopy\\" value=\\"1\\" id=\\"savecopyon\\" $savecopyon /> <label for=\\"savecopyon\\">Yes</label><br /><input type=\\"radio\\" name=\\"savecopy\\" value=\\"0\\" id=\\"savecopyoff\\" $savecopyoff /> <label for=\\"savecopyoff\\">No</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Folder to return to:</b></span>
	<br />
	<span class=\\"smallfont\\">Choose if you\'d like to be taken to your Inbox or Sent Items folders after sending a message.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"returnsent\\" value=\\"0\\" id=\\"returnsentoff\\" $returnsentoff /> <label for=\\"returnsentoff\\">Inbox</label><br /><input type=\\"radio\\" name=\\"returnsent\\" value=\\"1\\" id=\\"returnsenton\\" $returnsenton /> <label for=\\"returnsenton\\">Sent Items</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Add recipients to address book:</b></span>
	<br />
	<span class=\\"smallfont\\">Automatically add recipients of outgoing messages to your address book.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"addrecips\\" value=\\"1\\" id=\\"addrecipson\\" $addrecipson /> <label for=\\"addrecipson\\">Yes</label><br /><input type=\\"radio\\" name=\\"addrecips\\" value=\\"0\\" id=\\"addrecipsoff\\" $addrecipsoff /><label for=\\"addrecipsoff\\">No</label></span></td>
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
		</td>
	</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'options_folderview' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Folder View Options</title>
$css
<script type="text/javascript" src="misc/columns.js"></script>
<script type="text/javascript" language="JavaScript">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$header

<form action="options.folderview.php" method="post" name="columnsform" onSubmit="extractList(this);">
<input type="hidden" name="cmd" value="update" />
<input type="hidden" name="finalusing" value="" />

<table cellpadding="4" cellspacing="0" width="100%">
	<tr>
		<td valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="150">
				<tr class="headerRow">
					<th class="headerBothCell"><span class="normalfonttablehead"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width="100%" valign="top">
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Folder View Options</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Show message preview pane:</b></span>
	<br />
	<span class="smallfont">Use the preview pane to quickly read messages without openning them or reloading the page.</span></td>
	<td class="{classname}RightCell" width="40%">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td><span class="normalfont"><input type="radio" name="preview" value="top" id="previewtop" $previewtop /> <label for="previewtop">At the top</label></span></td>
				<td><span class="normalfont">&nbsp;&nbsp;<input type="radio" name="preview" value="both" id="previewboth" $previewboth /> <label for="previewboth">Both locations</label></span></td>
			</tr>
			<tr>
				<td><span class="normalfont"><input type="radio" name="preview" value="bottom" id="previewbottom" $previewbottom /> <label for="previewbottom">At the bottom</label></span></td>
				<td><span class="normalfont">&nbsp;&nbsp;<input type="radio" name="preview" value="none" id="previewnone" $previewnone /> <label for="previewnone">Don\'t show</label></span></td>
			</tr>
		</table>
	</td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Enable background highlighting:</b></span>
	<br />
	<span class="smallfont">If this turned on selected messages will have a different background color.<br />If you are experiencing performance problems try disabling this option.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="usebghigh" value="1" id="usebghighon" $usebghighon /> <label for="usebghighon">Yes</label><br /><input type="radio" name="usebghigh" value="0" id="usebghighoff" $usebghighoff /> <label for="usebghighoff">No</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Show folder list on left:</b></span>
	<br />
	<span class="smallfont">Use the folders tab to quickly navigate through your folders.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="showfoldertab" value="1" id="showfoldertabon" $showfoldertabon /> <label for="showfoldertabon">Yes</label><br /><input type="radio" name="showfoldertab" value="0" id="showfoldertaboff" $showfoldertaboff /> <label for="showfoldertaboff">No</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Display statistics box:</b></span>
	<br />
	<span class="smallfont">This table lets you know how many unread messages you have, and where, as well your storage usage.<br />Note: When you reach $minpercentforgauge% the space gauge will be displayed even if this option is turned off.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="showtopbox" value="1" id="showtopboxon" $showtopboxon /> <label for="showtopboxon">Yes</label><br /><input type="radio" name="showtopbox" value="0" id="showtopboxoff" $showtopboxoff /> <label for="showtopboxoff">No</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Page refresh rate:</b></span>
	<br />
	<span class="smallfont">If not set to 0, the page will reload itself according to this setting.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="text" class="bginput" name="autorefresh" value="$hiveuser[autorefresh]" size="4" /> seconds</span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Mark messages as read after:</b></span>
	<br />
	<span class="smallfont">If not set to 0, messages will be automatically marked read when they are previewed.<br />Note: Requires the preview pane to be enabled as well as a browser with cookie support.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="text" class="bginput" name="markread" value="$hiveuser[markread]" size="4" /> seconds</span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Messages per page:</b></span>
	<br />
	<span class="smallfont">The number of messages to show per page.<br />You cannot set this to a value higher than $maxperpage.<br />It is not advisable to set this number too high, for performance reasons.</span></td>
	<td class="{classname}RightCell" width="40%"><input type="text" class="bginput" name="perpage" value="$hiveuser[perpage]" size="4" /></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>New message to sender:</b></span>
	<br />
	<span class="smallfont">Clicking a sender name creates a new message to the sender.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="senderlink" value="1" id="senderlinkon" $senderlinkon /> <label for="senderlinkon">Yes</label><br /><input type="radio" name="senderlink" value="0" id="senderlinkoff" $senderlinkoff /> <label for="senderlinkoff">No</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Message columns:</b></span>
	<br />
	<span class="smallfont">These are the columns that show up when viewing the list of messages.<br />At least one column must be selected.</span></td>
	<td class="{classname}RightCell" width="40%">
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
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$GLOBALS[header]

<form action=\\"options.folderview.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"columnsform\\" onSubmit=\\"extractList(this);\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />
<input type=\\"hidden\\" name=\\"finalusing\\" value=\\"\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" width=\\"100%\\">
	<tr>
		<td valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"150\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width=\\"100%\\" valign=\\"top\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Folder View Options</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Show message preview pane:</b></span>
	<br />
	<span class=\\"smallfont\\">Use the preview pane to quickly read messages without openning them or reloading the page.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\">
		<table cellpadding=\\"0\\" cellspacing=\\"0\\" border=\\"0\\">
			<tr>
				<td><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"preview\\" value=\\"top\\" id=\\"previewtop\\" $previewtop /> <label for=\\"previewtop\\">At the top</label></span></td>
				<td><span class=\\"normalfont\\">&nbsp;&nbsp;<input type=\\"radio\\" name=\\"preview\\" value=\\"both\\" id=\\"previewboth\\" $previewboth /> <label for=\\"previewboth\\">Both locations</label></span></td>
			</tr>
			<tr>
				<td><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"preview\\" value=\\"bottom\\" id=\\"previewbottom\\" $previewbottom /> <label for=\\"previewbottom\\">At the bottom</label></span></td>
				<td><span class=\\"normalfont\\">&nbsp;&nbsp;<input type=\\"radio\\" name=\\"preview\\" value=\\"none\\" id=\\"previewnone\\" $previewnone /> <label for=\\"previewnone\\">Don\'t show</label></span></td>
			</tr>
		</table>
	</td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Enable background highlighting:</b></span>
	<br />
	<span class=\\"smallfont\\">If this turned on selected messages will have a different background color.<br />If you are experiencing performance problems try disabling this option.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"usebghigh\\" value=\\"1\\" id=\\"usebghighon\\" $usebghighon /> <label for=\\"usebghighon\\">Yes</label><br /><input type=\\"radio\\" name=\\"usebghigh\\" value=\\"0\\" id=\\"usebghighoff\\" $usebghighoff /> <label for=\\"usebghighoff\\">No</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Show folder list on left:</b></span>
	<br />
	<span class=\\"smallfont\\">Use the folders tab to quickly navigate through your folders.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"showfoldertab\\" value=\\"1\\" id=\\"showfoldertabon\\" $showfoldertabon /> <label for=\\"showfoldertabon\\">Yes</label><br /><input type=\\"radio\\" name=\\"showfoldertab\\" value=\\"0\\" id=\\"showfoldertaboff\\" $showfoldertaboff /> <label for=\\"showfoldertaboff\\">No</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Display statistics box:</b></span>
	<br />
	<span class=\\"smallfont\\">This table lets you know how many unread messages you have, and where, as well your storage usage.<br />Note: When you reach $minpercentforgauge% the space gauge will be displayed even if this option is turned off.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"showtopbox\\" value=\\"1\\" id=\\"showtopboxon\\" $showtopboxon /> <label for=\\"showtopboxon\\">Yes</label><br /><input type=\\"radio\\" name=\\"showtopbox\\" value=\\"0\\" id=\\"showtopboxoff\\" $showtopboxoff /> <label for=\\"showtopboxoff\\">No</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Page refresh rate:</b></span>
	<br />
	<span class=\\"smallfont\\">If not set to 0, the page will reload itself according to this setting.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"autorefresh\\" value=\\"$hiveuser[autorefresh]\\" size=\\"4\\" /> seconds</span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Mark messages as read after:</b></span>
	<br />
	<span class=\\"smallfont\\">If not set to 0, messages will be automatically marked read when they are previewed.<br />Note: Requires the preview pane to be enabled as well as a browser with cookie support.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"markread\\" value=\\"$hiveuser[markread]\\" size=\\"4\\" /> seconds</span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Messages per page:</b></span>
	<br />
	<span class=\\"smallfont\\">The number of messages to show per page.<br />You cannot set this to a value higher than $maxperpage.<br />It is not advisable to set this number too high, for performance reasons.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"perpage\\" value=\\"$hiveuser[perpage]\\" size=\\"4\\" /></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>New message to sender:</b></span>
	<br />
	<span class=\\"smallfont\\">Clicking a sender name creates a new message to the sender.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"senderlink\\" value=\\"1\\" id=\\"senderlinkon\\" $senderlinkon /> <label for=\\"senderlinkon\\">Yes</label><br /><input type=\\"radio\\" name=\\"senderlink\\" value=\\"0\\" id=\\"senderlinkoff\\" $senderlinkoff /> <label for=\\"senderlinkoff\\">No</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Message columns:</b></span>
	<br />
	<span class=\\"smallfont\\">These are the columns that show up when viewing the list of messages.<br />At least one column must be selected.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\">
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
		</td>
	</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'options_general' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: General Options</title>
$css
<script type="text/javascript" language="JavaScript">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$header

<form action="options.general.php" method="post" enctype="multipart/form-data">
<input type="hidden" name="cmd" value="update" />

<table cellpadding="4" cellspacing="0" width="100%">
	<tr>
		<td valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="150">
				<tr class="headerRow">
					<th class="headerBothCell"><span class="normalfonttablehead"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width="100%" valign="top">
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>General Options</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Use cookies to track information:</b></span>
	<br />
	<span class="smallfont">If your browser is incapable of receiving and storing cookies from our system, please disable this option so you can still use the service.?</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="nocookies" value="0" id="nocookiesoff" $nocookiesoff /><label for="nocookiesoff">Yes<label><br /><input type="radio" name="nocookies" value="1" id="nocookieson" $nocookieson /><label for="nocookieson">No<label></span></td>
	<!-- NOTE: Yes, this may look wrong but it is correct. DO NOT ALTER FORMAT -->
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Email domain name:</b></span>
	<br />
	<span class="smallfont">Choose the domain name you would like to have associated with this account. All outgoing messages will be sent with the chosen name.</span></td>
	<td class="{classname}RightCell" width="40%"><select name="domain">
		$domainname_options
	</select></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Skin:</b></span>
	<br />
	<span class="smallfont">You can choose from several skins that change the look of this program.</span></td>
	<td class="{classname}RightCell" width="40%"><select name="skinid">
		$skinoptions
	</select></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Show system notices as popups:</b></span>
	<br />
	<span class="smallfont">If this is enabled system notices will be displayed as popups, otherwise they will be shown normally within the page.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="popupnotices" value="1" id="popupnoticeson" $popupnoticeson /><label for="popupnoticeson">Yes<label><br /><input type="radio" name="popupnotices" value="0" id="popupnoticesoff" $popupnoticesoff /><label for="popupnoticesoff">No<label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Empty the trash can automatically:</b></span>
	<br />
	<span class="smallfont">If you want the system to automatically delete all messages<br />from your trash can, please select the appropriate option.<br />If this is turned on, messages in your trash can don\'t count<br />towards your account storage limit.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="emptybin" value="-2" id="emptybinonexit" $emptybinonexit /> <label for="emptybinonexit">Empty folder on exit</label><br /><input type="radio" name="emptybin" value="1" id="emptybinevery" $emptybinevery /> Empty folder every &nbsp;<input type="text" class="bginput" name="binevery" value="$binevery" size="3" maxlength="3" onClick="document.getElementById(\'emptybinevery\').checked = true;" />&nbsp; days<br /><input type="radio" name="emptybin" value="-1" id="emptybinno" $emptybinno /> <label for="emptybinno">Never empty folder</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Play sound when new messages arrive:</b></span>
	<br />
	<span class="smallfont">Play the "You\'ve got mail" sound whenever new messages arrive in your mail box.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="playsound" value="1" id="playsoundon" $playsoundon /><label for="playsoundon">Yes<label><br /><input type="radio" name="playsound" value="0" id="playsoundoff" $playsoundoff /><label for="playsoundoff">No<label></span></td>
</tr>
<%if $hiveuser[cansound] %>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Sound to play:</b></span>
	<br />
	<span class="smallfont">This is the sound you will hear if the option above is enabled.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont">
		<select name="soundid">
			<%if $havecustom %><option value="$cursound[soundid]">$cursound[filename] (Personal sound)</option><%endif%>
			$soundoptions
		</select> <input type="button" value="Preview" onClick="window.open(\'user.sound.php?soundid=\'+this.form.soundid.options[this.form.soundid.selectedIndex].value);" class="bginput" /><br /><br />
		<label for="newsound">Or upload your own file:<label><br /><input type="file" class="bginput" name="newsound" onClick="this.form.soundoptionnew.checked = true;" /><input type="hidden" name="MAX_FILE_SIZE" value="$maxsoundfile" />
	</span></td>
</tr>
<%endif%>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Email notification:</b></span>
	<br />
	<span class="smallfont">Notification e-mails will be sent to this address.</td>
	<td class="{classname}RightCell" width="40%"><input type="text" class="bginput" name="notifyemail" value="$hiveuser[notifyemail]" size="40" /></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Notify of all messages:</b></span>
	<br />
	<span class="smallfont">If set to yes, notification will be sent of all messages. Otherwise, notification will only be sent for messages that match a <a href="rules.list.php">rule</a> which is set to notify you.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="notifyall" value="1" id="notifyallon" $notifyallon /><label for="notifyallon">Yes<label><br /><input type="radio" name="notifyall" value="0" id="notifyalloff" $notifyalloff /><label for="notifyalloff">No<label></span></td>
</tr>
<%if $hiveuser[canforward] %>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Automatically forward messages:</b></span>
	<br />
	<span class="smallfont">Emails that you receive will automatically be forwarded to this address.<br />Set this to nothing to disable the feature.</span></td>
	<td class="{classname}RightCell" width="40%"><input type="text" class="bginput" name="forward" value="$hiveuser[forward]" size="40" /></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Keep copy of messages which are automatically forwarded:</b></span>
	<br />
	<span class="smallfont">If you decide to automatically forward messages to the address speicified above, would you like to still keep them in your inbox?</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="deleteforwards" value="0" id="deleteforwardsoff" $deleteforwardsoff /><label for="deleteforwardsoff">Yes<label><br /><input type="radio" name="deleteforwards" value="1" id="deleteforwardson" $deleteforwardson /><label for="deleteforwardson">No<label></span></td>
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
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$GLOBALS[header]

<form action=\\"options.general.php{$GLOBALS[session_url]}\\" method=\\"post\\" enctype=\\"multipart/form-data\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" width=\\"100%\\">
	<tr>
		<td valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"150\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width=\\"100%\\" valign=\\"top\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>General Options</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Use cookies to track information:</b></span>
	<br />
	<span class=\\"smallfont\\">If your browser is incapable of receiving and storing cookies from our system, please disable this option so you can still use the service.?</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"nocookies\\" value=\\"0\\" id=\\"nocookiesoff\\" $nocookiesoff /><label for=\\"nocookiesoff\\">Yes<label><br /><input type=\\"radio\\" name=\\"nocookies\\" value=\\"1\\" id=\\"nocookieson\\" $nocookieson /><label for=\\"nocookieson\\">No<label></span></td>
	<!-- NOTE: Yes, this may look wrong but it is correct. DO NOT ALTER FORMAT -->
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Email domain name:</b></span>
	<br />
	<span class=\\"smallfont\\">Choose the domain name you would like to have associated with this account. All outgoing messages will be sent with the chosen name.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><select name=\\"domain\\">
		$domainname_options
	</select></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Skin:</b></span>
	<br />
	<span class=\\"smallfont\\">You can choose from several skins that change the look of this program.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><select name=\\"skinid\\">
		$skinoptions
	</select></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Show system notices as popups:</b></span>
	<br />
	<span class=\\"smallfont\\">If this is enabled system notices will be displayed as popups, otherwise they will be shown normally within the page.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"popupnotices\\" value=\\"1\\" id=\\"popupnoticeson\\" $popupnoticeson /><label for=\\"popupnoticeson\\">Yes<label><br /><input type=\\"radio\\" name=\\"popupnotices\\" value=\\"0\\" id=\\"popupnoticesoff\\" $popupnoticesoff /><label for=\\"popupnoticesoff\\">No<label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Empty the trash can automatically:</b></span>
	<br />
	<span class=\\"smallfont\\">If you want the system to automatically delete all messages<br />from your trash can, please select the appropriate option.<br />If this is turned on, messages in your trash can don\'t count<br />towards your account storage limit.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"emptybin\\" value=\\"-2\\" id=\\"emptybinonexit\\" $emptybinonexit /> <label for=\\"emptybinonexit\\">Empty folder on exit</label><br /><input type=\\"radio\\" name=\\"emptybin\\" value=\\"1\\" id=\\"emptybinevery\\" $emptybinevery /> Empty folder every &nbsp;<input type=\\"text\\" class=\\"bginput\\" name=\\"binevery\\" value=\\"$binevery\\" size=\\"3\\" maxlength=\\"3\\" onClick=\\"document.getElementById(\'emptybinevery\').checked = true;\\" />&nbsp; days<br /><input type=\\"radio\\" name=\\"emptybin\\" value=\\"-1\\" id=\\"emptybinno\\" $emptybinno /> <label for=\\"emptybinno\\">Never empty folder</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Play sound when new messages arrive:</b></span>
	<br />
	<span class=\\"smallfont\\">Play the \\"You\'ve got mail\\" sound whenever new messages arrive in your mail box.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"playsound\\" value=\\"1\\" id=\\"playsoundon\\" $playsoundon /><label for=\\"playsoundon\\">Yes<label><br /><input type=\\"radio\\" name=\\"playsound\\" value=\\"0\\" id=\\"playsoundoff\\" $playsoundoff /><label for=\\"playsoundoff\\">No<label></span></td>
</tr>
".(($hiveuser[cansound] ) ? ("
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Sound to play:</b></span>
	<br />
	<span class=\\"smallfont\\">This is the sound you will hear if the option above is enabled.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\">
		<select name=\\"soundid\\">
			".(($havecustom ) ? ("<option value=\\"$cursound[soundid]\\">$cursound[filename] (Personal sound)</option>") : (\'\'))."
			$soundoptions
		</select> <input type=\\"button\\" value=\\"Preview\\" onClick=\\"window.open(\'user.sound.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}soundid=\'+this.form.soundid.options[this.form.soundid.selectedIndex].value);\\" class=\\"bginput\\" /><br /><br />
		<label for=\\"newsound\\">Or upload your own file:<label><br /><input type=\\"file\\" class=\\"bginput\\" name=\\"newsound\\" onClick=\\"this.form.soundoptionnew.checked = true;\\" /><input type=\\"hidden\\" name=\\"MAX_FILE_SIZE\\" value=\\"$maxsoundfile\\" />
	</span></td>
</tr>
") : (\'\'))."
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Email notification:</b></span>
	<br />
	<span class=\\"smallfont\\">Notification e-mails will be sent to this address.</td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"notifyemail\\" value=\\"$hiveuser[notifyemail]\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Notify of all messages:</b></span>
	<br />
	<span class=\\"smallfont\\">If set to yes, notification will be sent of all messages. Otherwise, notification will only be sent for messages that match a <a href=\\"rules.list.php{$GLOBALS[session_url]}\\">rule</a> which is set to notify you.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"notifyall\\" value=\\"1\\" id=\\"notifyallon\\" $notifyallon /><label for=\\"notifyallon\\">Yes<label><br /><input type=\\"radio\\" name=\\"notifyall\\" value=\\"0\\" id=\\"notifyalloff\\" $notifyalloff /><label for=\\"notifyalloff\\">No<label></span></td>
</tr>
".(($hiveuser[canforward] ) ? ("
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Automatically forward messages:</b></span>
	<br />
	<span class=\\"smallfont\\">Emails that you receive will automatically be forwarded to this address.<br />Set this to nothing to disable the feature.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"forward\\" value=\\"$hiveuser[forward]\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Keep copy of messages which are automatically forwarded:</b></span>
	<br />
	<span class=\\"smallfont\\">If you decide to automatically forward messages to the address speicified above, would you like to still keep them in your inbox?</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"deleteforwards\\" value=\\"0\\" id=\\"deleteforwardsoff\\" $deleteforwardsoff /><label for=\\"deleteforwardsoff\\">Yes<label><br /><input type=\\"radio\\" name=\\"deleteforwards\\" value=\\"1\\" id=\\"deleteforwardson\\" $deleteforwardson /><label for=\\"deleteforwardson\\">No<label></span></td>
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
		</td>
	</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'options_menu' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Preferences</title>
$css
<script type="text/javascript" language="JavaScript">
<!--

var menuDesc = new Array;

function showDesc(id) {
	getElement(\'desc\').value = menuDesc[id];
	getElement(\'desc\').disabled = false;
	getElement(\'menuCell\'+id).className = getElement(\'menuCell\'+id).className.replace(/high/, \'normal\');
}
function hideDesc(id) {
	getElement(\'desc\').value = \'(choose a menu)\';
	getElement(\'desc\').disabled = true;
	getElement(\'menuCell\'+id).className = getElement(\'menuCell\'+id).className.replace(/normal/, \'high\');
}

// -->
</script>
</head>
<body>
$header

<form action="options.menu.php">
<table cellpadding="5" cellspacing="0" class="normalTable" width="750" align="left">
	<tr class="headerRow">
		<th colspan="10" class="headerBothCell"><span class="normalfonttablehead">Preferences</span></th>
	</tr>
	$menus
	<tr>
		<td colspan="10" class="highBothCell"><textarea id="desc" style="text-align: center; overflow: visible; border: 0px; background: transparent; width: 99%" rows="1" readonly="readonly" disabled="disabled">(choose a menu)</textarea></td>
	</tr>
</table>
</form>

<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Preferences</title>
$GLOBALS[css]
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--

var menuDesc = new Array;

function showDesc(id) {
	getElement(\'desc\').value = menuDesc[id];
	getElement(\'desc\').disabled = false;
	getElement(\'menuCell\'+id).className = getElement(\'menuCell\'+id).className.replace(/high/, \'normal\');
}
function hideDesc(id) {
	getElement(\'desc\').value = \'(choose a menu)\';
	getElement(\'desc\').disabled = true;
	getElement(\'menuCell\'+id).className = getElement(\'menuCell\'+id).className.replace(/normal/, \'high\');
}

// -->
</script>
</head>
<body>
$GLOBALS[header]

<form action=\\"options.menu.php{$GLOBALS[session_url]}\\">
<table cellpadding=\\"5\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"750\\" align=\\"left\\">
	<tr class=\\"headerRow\\">
		<th colspan=\\"10\\" class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\">Preferences</span></th>
	</tr>
	$menus
	<tr>
		<td colspan=\\"10\\" class=\\"highBothCell\\"><textarea id=\\"desc\\" style=\\"text-align: center; overflow: visible; border: 0px; background: transparent; width: 99%\\" rows=\\"1\\" readonly=\\"readonly\\" disabled=\\"disabled\\">(choose a menu)</textarea></td>
	</tr>
</table>
</form>

<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'options_menu_aliases' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<td id="menuCell$i" nowrap="nowrap" align="center" class="$cellType"><span class="normalfont" onMouseOver="showDesc($i);" onMouseOut="hideDesc($i);"><%if $sel %><b>[&nbsp;&nbsp;<%else%><a href="options.aliases.php"><%endif%>Email Aliases<%if !$sel %></a><%else%>&nbsp;&nbsp;]</b><%endif%></span></td>
<script type="text/javascript" language="JavaScript">
<!--
menuDesc[$i] = \'Define and configure your account aliases.\';
// -->
</script>',
    'parsed_data' => '"<td id=\\"menuCell$i\\" nowrap=\\"nowrap\\" align=\\"center\\" class=\\"$cellType\\"><span class=\\"normalfont\\" onMouseOver=\\"showDesc($i);\\" onMouseOut=\\"hideDesc($i);\\">".(($sel ) ? ("<b>[&nbsp;&nbsp;") : ("<a href=\\"options.aliases.php{$GLOBALS[session_url]}\\">"))."Email Aliases".((!$sel ) ? ("</a>") : ("&nbsp;&nbsp;]</b>"))."</span></td>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
menuDesc[$i] = \'Define and configure your account aliases.\';
// -->
</script>"',
    'upgraded' => '0',
  ),
  'options_menu_autoresponses' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<td id="menuCell$i" nowrap="nowrap" align="center" class="$cellType"><span class="normalfont" onMouseOver="showDesc($i);" onMouseOut="hideDesc($i);"><%if $sel %><b>[&nbsp;&nbsp;<%else%><a href="options.autoresponders.php"><%endif%>Auto-Responder<%if !$sel %></a><%else%>&nbsp;&nbsp;]</b><%endif%></span></td>
<script type="text/javascript" language="JavaScript">
<!--
menuDesc[$i] = \'Options about your automatic responder and responses editor.\';
// -->
</script>',
    'parsed_data' => '"<td id=\\"menuCell$i\\" nowrap=\\"nowrap\\" align=\\"center\\" class=\\"$cellType\\"><span class=\\"normalfont\\" onMouseOver=\\"showDesc($i);\\" onMouseOut=\\"hideDesc($i);\\">".(($sel ) ? ("<b>[&nbsp;&nbsp;") : ("<a href=\\"options.autoresponders.php{$GLOBALS[session_url]}\\">"))."Auto-Responder".((!$sel ) ? ("</a>") : ("&nbsp;&nbsp;]</b>"))."</span></td>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
menuDesc[$i] = \'Options about your automatic responder and responses editor.\';
// -->
</script>"',
    'upgraded' => '0',
  ),
  'options_menu_calendar' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<td id="menuCell$i" nowrap="nowrap" align="center" class="$cellType"><span class="normalfont" onMouseOver="showDesc($i);" onMouseOut="hideDesc($i);"><%if $sel %><b>[&nbsp;&nbsp;<%else%><a href="calendar.options.php"><%endif%>Calendar Options<%if !$sel %></a><%else%>&nbsp;&nbsp;]</b><%endif%></span></td>
<script type="text/javascript" language="JavaScript">
<!--
menuDesc[$i] = \'Settings about your calendar, different display options, and more.\';
// -->
</script>',
    'parsed_data' => '"<td id=\\"menuCell$i\\" nowrap=\\"nowrap\\" align=\\"center\\" class=\\"$cellType\\"><span class=\\"normalfont\\" onMouseOver=\\"showDesc($i);\\" onMouseOut=\\"hideDesc($i);\\">".(($sel ) ? ("<b>[&nbsp;&nbsp;") : ("<a href=\\"calendar.options.php{$GLOBALS[session_url]}\\">"))."Calendar Options".((!$sel ) ? ("</a>") : ("&nbsp;&nbsp;]</b>"))."</span></td>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
menuDesc[$i] = \'Settings about your calendar, different display options, and more.\';
// -->
</script>"',
    'upgraded' => '0',
  ),
  'options_menu_compose' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<td id="menuCell$i" nowrap="nowrap" align="center" class="$cellType"><span class="normalfont" onMouseOver="showDesc($i);" onMouseOut="hideDesc($i);"><%if $sel %><b>[&nbsp;&nbsp;<%else%><a href="options.compose.php"><%endif%>Compose Options<%if !$sel %></a><%else%>&nbsp;&nbsp;]</b><%endif%></span></td>
<script type="text/javascript" language="JavaScript">
<!--
menuDesc[$i] = \'Settings that pertain to composing messages, such as using the WYSIWYG editor, including original message, and more.\';
// -->
</script>',
    'parsed_data' => '"<td id=\\"menuCell$i\\" nowrap=\\"nowrap\\" align=\\"center\\" class=\\"$cellType\\"><span class=\\"normalfont\\" onMouseOver=\\"showDesc($i);\\" onMouseOut=\\"hideDesc($i);\\">".(($sel ) ? ("<b>[&nbsp;&nbsp;") : ("<a href=\\"options.compose.php{$GLOBALS[session_url]}\\">"))."Compose Options".((!$sel ) ? ("</a>") : ("&nbsp;&nbsp;]</b>"))."</span></td>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
menuDesc[$i] = \'Settings that pertain to composing messages, such as using the WYSIWYG editor, including original message, and more.\';
// -->
</script>"',
    'upgraded' => '0',
  ),
  'options_menu_folders' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<td id="menuCell$i" nowrap="nowrap" align="center" class="$cellType"><span class="normalfont" onMouseOver="showDesc($i);" onMouseOut="hideDesc($i);"><%if $sel %><b>[&nbsp;&nbsp;<%else%><a href="folders.list.php"><%endif%>Folders Management<%if !$sel %></a><%else%>&nbsp;&nbsp;]</b><%endif%></span></td>
<script type="text/javascript" language="JavaScript">
<!--
menuDesc[$i] = \'Create, rename, delete or empty your mail folders.\';
// -->
</script>',
    'parsed_data' => '"<td id=\\"menuCell$i\\" nowrap=\\"nowrap\\" align=\\"center\\" class=\\"$cellType\\"><span class=\\"normalfont\\" onMouseOver=\\"showDesc($i);\\" onMouseOut=\\"hideDesc($i);\\">".(($sel ) ? ("<b>[&nbsp;&nbsp;") : ("<a href=\\"folders.list.php{$GLOBALS[session_url]}\\">"))."Folders Management".((!$sel ) ? ("</a>") : ("&nbsp;&nbsp;]</b>"))."</span></td>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
menuDesc[$i] = \'Create, rename, delete or empty your mail folders.\';
// -->
</script>"',
    'upgraded' => '0',
  ),
  'options_menu_folderview' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<td id="menuCell$i" nowrap="nowrap" align="center" class="$cellType"><span class="normalfont" onMouseOver="showDesc($i);" onMouseOut="hideDesc($i);"><%if $sel %><b>[&nbsp;&nbsp;<%else%><a href="options.folderview.php"><%endif%>Folder View Options<%if !$sel %></a><%else%>&nbsp;&nbsp;]</b><%endif%></span></td>
<script type="text/javascript" language="JavaScript">
<!--
menuDesc[$i] = \'Settings that pertain to folder viewing, such as using the preview pane, selecting columns to show and more.\';
// -->
</script>',
    'parsed_data' => '"<td id=\\"menuCell$i\\" nowrap=\\"nowrap\\" align=\\"center\\" class=\\"$cellType\\"><span class=\\"normalfont\\" onMouseOver=\\"showDesc($i);\\" onMouseOut=\\"hideDesc($i);\\">".(($sel ) ? ("<b>[&nbsp;&nbsp;") : ("<a href=\\"options.folderview.php{$GLOBALS[session_url]}\\">"))."Folder View Options".((!$sel ) ? ("</a>") : ("&nbsp;&nbsp;]</b>"))."</span></td>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
menuDesc[$i] = \'Settings that pertain to folder viewing, such as using the preview pane, selecting columns to show and more.\';
// -->
</script>"',
    'upgraded' => '0',
  ),
  'options_menu_general' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<td id="menuCell$i" nowrap="nowrap" align="center" class="$cellType"><span class="normalfont" onMouseOver="showDesc($i);" onMouseOut="hideDesc($i);"><%if $sel %><b>[&nbsp;&nbsp;<%else%><a href="options.general.php"><%endif%>General Options<%if !$sel %></a><%else%>&nbsp;&nbsp;]</b><%endif%></span></td>
<script type="text/javascript" language="JavaScript">
<!--
menuDesc[$i] = \'Miscellaneous settings, such as new mail sound, changing skins, and more.\';
// -->
</script>',
    'parsed_data' => '"<td id=\\"menuCell$i\\" nowrap=\\"nowrap\\" align=\\"center\\" class=\\"$cellType\\"><span class=\\"normalfont\\" onMouseOver=\\"showDesc($i);\\" onMouseOut=\\"hideDesc($i);\\">".(($sel ) ? ("<b>[&nbsp;&nbsp;") : ("<a href=\\"options.general.php{$GLOBALS[session_url]}\\">"))."General Options".((!$sel ) ? ("</a>") : ("&nbsp;&nbsp;]</b>"))."</span></td>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
menuDesc[$i] = \'Miscellaneous settings, such as new mail sound, changing skins, and more.\';
// -->
</script>"',
    'upgraded' => '0',
  ),
  'options_menu_password' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<td id="menuCell$i" nowrap="nowrap" align="center" class="$cellType"><span class="normalfont" onMouseOver="showDesc($i);" onMouseOut="hideDesc($i);"><%if $sel %><b>[&nbsp;&nbsp;<%else%><a href="options.password.php"><%endif%>Password and Security<%if !$sel %></a><%else%>&nbsp;&nbsp;]</b><%endif%></span></td>
<script type="text/javascript" language="JavaScript">
<!--
menuDesc[$i] = \'Change your account password or your secret question and answer.\';
// -->
</script>',
    'parsed_data' => '"<td id=\\"menuCell$i\\" nowrap=\\"nowrap\\" align=\\"center\\" class=\\"$cellType\\"><span class=\\"normalfont\\" onMouseOver=\\"showDesc($i);\\" onMouseOut=\\"hideDesc($i);\\">".(($sel ) ? ("<b>[&nbsp;&nbsp;") : ("<a href=\\"options.password.php{$GLOBALS[session_url]}\\">"))."Password and Security".((!$sel ) ? ("</a>") : ("&nbsp;&nbsp;]</b>"))."</span></td>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
menuDesc[$i] = \'Change your account password or your secret question and answer.\';
// -->
</script>"',
    'upgraded' => '0',
  ),
  'options_menu_personal' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<td id="menuCell$i" nowrap="nowrap" align="center" class="$cellType"><span class="normalfont" onMouseOver="showDesc($i);" onMouseOut="hideDesc($i);"><%if $sel %><b>[&nbsp;&nbsp;<%else%><a href="options.personal.php"><%endif%>Personal Information<%if !$sel %></a><%else%>&nbsp;&nbsp;]</b><%endif%></span></td>
<script type="text/javascript" language="JavaScript">
<!--
menuDesc[$i] = \'Edit personal information such as your name, location and birthday.\';
// -->
</script>',
    'parsed_data' => '"<td id=\\"menuCell$i\\" nowrap=\\"nowrap\\" align=\\"center\\" class=\\"$cellType\\"><span class=\\"normalfont\\" onMouseOver=\\"showDesc($i);\\" onMouseOut=\\"hideDesc($i);\\">".(($sel ) ? ("<b>[&nbsp;&nbsp;") : ("<a href=\\"options.personal.php{$GLOBALS[session_url]}\\">"))."Personal Information".((!$sel ) ? ("</a>") : ("&nbsp;&nbsp;]</b>"))."</span></td>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
menuDesc[$i] = \'Edit personal information such as your name, location and birthday.\';
// -->
</script>"',
    'upgraded' => '0',
  ),
  'options_menu_pop' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<td id="menuCell$i" nowrap="nowrap" align="center" class="$cellType"><span class="normalfont" onMouseOver="showDesc($i);" onMouseOut="hideDesc($i);"><%if $sel %><b>[&nbsp;&nbsp;<%else%><a href="pop.list.php"><%endif%>POP Accounts<%if !$sel %></a><%else%>&nbsp;&nbsp;]</b><%endif%></span></td>
<script type="text/javascript" language="JavaScript">
<!--
menuDesc[$i] = \'Have emails from other addresses delivered directly to $appname and vice versa.\';
// -->
</script>',
    'parsed_data' => '"<td id=\\"menuCell$i\\" nowrap=\\"nowrap\\" align=\\"center\\" class=\\"$cellType\\"><span class=\\"normalfont\\" onMouseOver=\\"showDesc($i);\\" onMouseOut=\\"hideDesc($i);\\">".(($sel ) ? ("<b>[&nbsp;&nbsp;") : ("<a href=\\"pop.list.php{$GLOBALS[session_url]}\\">"))."POP Accounts".((!$sel ) ? ("</a>") : ("&nbsp;&nbsp;]</b>"))."</span></td>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
menuDesc[$i] = \'Have emails from other addresses delivered directly to $appname and vice versa.\';
// -->
</script>"',
    'upgraded' => '0',
  ),
  'options_menu_read' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<td id="menuCell$i" nowrap="nowrap" align="center" class="$cellType"><span class="normalfont" onMouseOver="showDesc($i);" onMouseOut="hideDesc($i);"><%if $sel %><b>[&nbsp;&nbsp;<%else%><a href="options.read.php"><%endif%>Reading Options<%if !$sel %></a><%else%>&nbsp;&nbsp;]</b><%endif%></span></td>
<script type="text/javascript" language="JavaScript">
<!--
menuDesc[$i] = \'Settings that pertain to reading messages, such as showing HTML, sending read receipts and more.\';
// -->
</script>',
    'parsed_data' => '"<td id=\\"menuCell$i\\" nowrap=\\"nowrap\\" align=\\"center\\" class=\\"$cellType\\"><span class=\\"normalfont\\" onMouseOver=\\"showDesc($i);\\" onMouseOut=\\"hideDesc($i);\\">".(($sel ) ? ("<b>[&nbsp;&nbsp;") : ("<a href=\\"options.read.php{$GLOBALS[session_url]}\\">"))."Reading Options".((!$sel ) ? ("</a>") : ("&nbsp;&nbsp;]</b>"))."</span></td>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
menuDesc[$i] = \'Settings that pertain to reading messages, such as showing HTML, sending read receipts and more.\';
// -->
</script>"',
    'upgraded' => '0',
  ),
  'options_menu_rules' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<td id="menuCell$i" nowrap="nowrap" align="center" class="$cellType"><span class="normalfont" onMouseOver="showDesc($i);" onMouseOut="hideDesc($i);"><%if $sel %><b>[&nbsp;&nbsp;<%else%><a href="rules.list.php"><%endif%>Message Rules<%if !$sel %></a><%else%>&nbsp;&nbsp;]</b><%endif%></span></td>
<script type="text/javascript" language="JavaScript">
<!--
menuDesc[$i] = \'Modify or add new message filters to avoid junk mail and edit the blocked and safe senders lists.\';
// -->
</script>',
    'parsed_data' => '"<td id=\\"menuCell$i\\" nowrap=\\"nowrap\\" align=\\"center\\" class=\\"$cellType\\"><span class=\\"normalfont\\" onMouseOver=\\"showDesc($i);\\" onMouseOut=\\"hideDesc($i);\\">".(($sel ) ? ("<b>[&nbsp;&nbsp;") : ("<a href=\\"rules.list.php{$GLOBALS[session_url]}\\">"))."Message Rules".((!$sel ) ? ("</a>") : ("&nbsp;&nbsp;]</b>"))."</span></td>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
menuDesc[$i] = \'Modify or add new message filters to avoid junk mail and edit the blocked and safe senders lists.\';
// -->
</script>"',
    'upgraded' => '0',
  ),
  'options_menu_signature' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<td id="menuCell$i" nowrap="nowrap" align="center" class="$cellType"><span class="normalfont" onMouseOver="showDesc($i);" onMouseOut="hideDesc($i);"><%if $sel %><b>[&nbsp;&nbsp;<%else%><a href="options.signature.php"><%endif%>Signatures<%if !$sel %></a><%else%>&nbsp;&nbsp;]</b><%endif%></span></td>
<script type="text/javascript" language="JavaScript">
<!--
menuDesc[$i] = \'Options about your signature and the signature editor.\';
// -->
</script>',
    'parsed_data' => '"<td id=\\"menuCell$i\\" nowrap=\\"nowrap\\" align=\\"center\\" class=\\"$cellType\\"><span class=\\"normalfont\\" onMouseOver=\\"showDesc($i);\\" onMouseOut=\\"hideDesc($i);\\">".(($sel ) ? ("<b>[&nbsp;&nbsp;") : ("<a href=\\"options.signature.php{$GLOBALS[session_url]}\\">"))."Signatures".((!$sel ) ? ("</a>") : ("&nbsp;&nbsp;]</b>"))."</span></td>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
menuDesc[$i] = \'Options about your signature and the signature editor.\';
// -->
</script>"',
    'upgraded' => '0',
  ),
  'options_menu_subscription' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<td id="menuCell$i" nowrap="nowrap" align="center" class="$cellType"><span class="normalfont" onMouseOver="showDesc($i);" onMouseOut="hideDesc($i);"><%if $sel %><b>[&nbsp;&nbsp;<%else%><a href="options.subscription.php"><%endif%>Subscriptions<%if !$sel %></a><%else%>&nbsp;&nbsp;]</b><%endif%></span></td>
<script type="text/javascript" language="JavaScript">
<!--
menuDesc[$i] = \'Edit subscription information.\';
// -->
</script>',
    'parsed_data' => '"<td id=\\"menuCell$i\\" nowrap=\\"nowrap\\" align=\\"center\\" class=\\"$cellType\\"><span class=\\"normalfont\\" onMouseOver=\\"showDesc($i);\\" onMouseOut=\\"hideDesc($i);\\">".(($sel ) ? ("<b>[&nbsp;&nbsp;") : ("<a href=\\"options.subscription.php{$GLOBALS[session_url]}\\">"))."Subscriptions".((!$sel ) ? ("</a>") : ("&nbsp;&nbsp;]</b>"))."</span></td>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
menuDesc[$i] = \'Edit subscription information.\';
// -->
</script>"',
    'upgraded' => '0',
  ),
  'options_password' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Password and Security</title>
$css
</head>
<script type="text/javascript" language="JavaScript">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
<body>
$header

<form action="options.password.php" method="post" name="form">
<input type="hidden" name="cmd" value="update" />
<input type="hidden" name="currentpass" value="$currentpass" />

<table cellpadding="4" cellspacing="0" width="100%">
	<tr>
		<td valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="150">
				<tr class="headerRow">
					<th class="headerBothCell"><span class="normalfonttablehead"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width="100%" valign="top">
<table cellpadding="4" cellspacing="0" class="normalTable" width="650">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Password</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>New password:</b></span></td>
	<td class="{classname}RightCell" width="40%"><input type="password" class="bginput" name="password" size="40" /></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Retype new password:</b></span>
	<br />
	<span class="smallfont">Repeat the password to verify it\'s correct.</span></td>
	<td class="{classname}RightCell" width="40%"><input type="password" class="bginput" name="password_repeat" size="40" /></td>
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
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Secret question:</b></span><br /><span class="smallfont">If you ever forget your password, you will be asked to answer this question in order to get a new one.</span></td>
	<td class="{classname}RightCell" width="40%"><input type="text" class="bginput" name="question" value="$hiveuser[question]" size="40" /><br />
		<select name="question_options" style="width: 100%;" onChange="if (this.options[this.selectedIndex].value != \'-1\') form.question.value = this.options[this.selectedIndex].text;">
			<option value="-1">(Or choose a question from below)</option>
			<option>Your mother\'s Maiden name?</option>
			<option>Your pet\'s name?</option>
			<option>City of birth?</option>
			<option>Last 4 digits of social security number?</option>
		</select>
	</td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Secret answer:</b></span></td>
	<td class="{classname}RightCell" width="40%"><input type="password" class="bginput" name="answer" size="40" /></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Retype secret answer:</b></span><br /><span class="smallfont">Repeat the secret answer to verify it\'s correct.</span></td>
	<td class="{classname}RightCell" width="40%"><input type="password" class="bginput" name="answer_repeat" size="40" /></td>
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
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
<body>
$GLOBALS[header]

<form action=\\"options.password.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />
<input type=\\"hidden\\" name=\\"currentpass\\" value=\\"$currentpass\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" width=\\"100%\\">
	<tr>
		<td valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"150\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width=\\"100%\\" valign=\\"top\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"650\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Password</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>New password:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"password\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Retype new password:</b></span>
	<br />
	<span class=\\"smallfont\\">Repeat the password to verify it\'s correct.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"password_repeat\\" size=\\"40\\" /></td>
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
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secret question:</b></span><br /><span class=\\"smallfont\\">If you ever forget your password, you will be asked to answer this question in order to get a new one.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"question\\" value=\\"$hiveuser[question]\\" size=\\"40\\" /><br />
		<select name=\\"question_options\\" style=\\"width: 100%;\\" onChange=\\"if (this.options[this.selectedIndex].value != \'-1\') form.question.value = this.options[this.selectedIndex].text;\\">
			<option value=\\"-1\\">(Or choose a question from below)</option>
			<option>Your mother\'s Maiden name?</option>
			<option>Your pet\'s name?</option>
			<option>City of birth?</option>
			<option>Last 4 digits of social security number?</option>
		</select>
	</td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secret answer:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"answer\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Retype secret answer:</b></span><br /><span class=\\"smallfont\\">Repeat the secret answer to verify it\'s correct.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"answer_repeat\\" size=\\"40\\" /></td>
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
		</td>
	</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'options_personal' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Personal Information</title>
$css
<script type="text/javascript" language="JavaScript">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$header

<form action="options.personal.php" method="post">
<input type="hidden" name="cmd" value="update" />

<table cellpadding="4" cellspacing="0" width="100%">
	<tr>
		<td valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="150">
				<tr class="headerRow">
					<th class="headerBothCell"><span class="normalfonttablehead"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width="100%" valign="top">
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Personal Information</b></span> <span class="smallfonttablehead">(* indicates a required field)</span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Your name:</b></span> <span class="important">*</span>
	<br />
	<span class="smallfont">This name will be sent with all your outgoing emails.</span></td>
	<td class="{classname}RightCell" width="40%"><input type="text" class="bginput" name="realname" value="$hiveuser[realname]" size="40" /></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Secondary email address:</b></span><%if $requirealt %> <span class="important">*</span><%endif%>
	<br />
	<span class="smallfont">This address will be used to contact you outside of this system.</span></td>
	<td class="{classname}RightCell" width="40%"><input type="text" class="bginput" name="altemail" value="$hiveuser[altemail]" size="40" /></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Birthday:</b></span><br /><span class="smallfont">Optional.</span></td>
	<td class="{classname}RightCell" width="40%"><select name="month">
			<option value="0" $monthsel[0]>Month</option>
			<option value="1" $monthsel[1]>$skin[cal_jan_long]</option>
			<option value="2" $monthsel[2]>$skin[cal_feb_long]</option>
			<option value="3" $monthsel[3]>$skin[cal_mar_long]</option>
			<option value="4" $monthsel[4]>$skin[cal_apr_long]</option>
			<option value="5" $monthsel[5]>$skin[cal_may_long]</option>
			<option value="6" $monthsel[6]>$skin[cal_jun_long]</option>
			<option value="7" $monthsel[7]>$skin[cal_jul_long]</option>
			<option value="8" $monthsel[8]>$skin[cal_aug_long]</option>
			<option value="9" $monthsel[9]>$skin[cal_sep_long]</option>
			<option value="10" $monthsel[10]>$skin[cal_oct_long]</option>
			<option value="11" $monthsel[11]>$skin[cal_nov_long]</option>
			<option value="12" $monthsel[12]>$skin[cal_dec_long]</option>
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
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Time zone:</b></span><br /><span class="smallfont">Please select the correct time zone from the list.</span></td>
	<td class="{classname}RightCell" width="40%">$timezone</td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Auto-detect Daylight Saving Time:</b></span><br /><span class="smallfont">If this is enabled, the system will automatically try to adjust the<br />time offset if Daylight Saving Time (DST) is in effect.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="fixdst" value="1" id="fixdston" $fixdston /> <label for="fixdston">Yes</label><br /><input type="radio" name="fixdst" value="0" id="fixdstoff" $fixdstoff /> <label for="fixdstoff">No</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Country:</b></span><br /><span class="smallfont">Optional.</span></td>
	<td class="{classname}RightCell" width="40%"><select name="country" onChange="if (this.options[this.selectedIndex].value != \'us\') this.form.state.selectedIndex = 0;">
		$countries
	</select></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>State:</b></span><br /><span class="smallfont">Optional.</span></td>
	<td class="{classname}RightCell" width="40%"><select name="state">
		$states
	</select></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Zip code:</b></span><br /><span class="smallfont">Optional.</span></td>
	<td class="{classname}RightCell" width="40%"><input type="text" class="bginput" name="zip" value="$hiveuser[zip]" size="7" maxlength="7" /></td>
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
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$GLOBALS[header]

<form action=\\"options.personal.php{$GLOBALS[session_url]}\\" method=\\"post\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" width=\\"100%\\">
	<tr>
		<td valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"150\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width=\\"100%\\" valign=\\"top\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Personal Information</b></span> <span class=\\"smallfonttablehead\\">(* indicates a required field)</span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Your name:</b></span> <span class=\\"important\\">*</span>
	<br />
	<span class=\\"smallfont\\">This name will be sent with all your outgoing emails.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"realname\\" value=\\"$hiveuser[realname]\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secondary email address:</b></span>".(($requirealt ) ? (" <span class=\\"important\\">*</span>") : (\'\'))."
	<br />
	<span class=\\"smallfont\\">This address will be used to contact you outside of this system.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"altemail\\" value=\\"$hiveuser[altemail]\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Birthday:</b></span><br /><span class=\\"smallfont\\">Optional.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><select name=\\"month\\">
			<option value=\\"0\\" $monthsel[0]>Month</option>
			<option value=\\"1\\" $monthsel[1]>{$GLOBALS[skin][cal_jan_long]}</option>
			<option value=\\"2\\" $monthsel[2]>{$GLOBALS[skin][cal_feb_long]}</option>
			<option value=\\"3\\" $monthsel[3]>{$GLOBALS[skin][cal_mar_long]}</option>
			<option value=\\"4\\" $monthsel[4]>{$GLOBALS[skin][cal_apr_long]}</option>
			<option value=\\"5\\" $monthsel[5]>{$GLOBALS[skin][cal_may_long]}</option>
			<option value=\\"6\\" $monthsel[6]>{$GLOBALS[skin][cal_jun_long]}</option>
			<option value=\\"7\\" $monthsel[7]>{$GLOBALS[skin][cal_jul_long]}</option>
			<option value=\\"8\\" $monthsel[8]>{$GLOBALS[skin][cal_aug_long]}</option>
			<option value=\\"9\\" $monthsel[9]>{$GLOBALS[skin][cal_sep_long]}</option>
			<option value=\\"10\\" $monthsel[10]>{$GLOBALS[skin][cal_oct_long]}</option>
			<option value=\\"11\\" $monthsel[11]>{$GLOBALS[skin][cal_nov_long]}</option>
			<option value=\\"12\\" $monthsel[12]>{$GLOBALS[skin][cal_dec_long]}</option>
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
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Time zone:</b></span><br /><span class=\\"smallfont\\">Please select the correct time zone from the list.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\">$timezone</td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Auto-detect Daylight Saving Time:</b></span><br /><span class=\\"smallfont\\">If this is enabled, the system will automatically try to adjust the<br />time offset if Daylight Saving Time (DST) is in effect.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"fixdst\\" value=\\"1\\" id=\\"fixdston\\" $fixdston /> <label for=\\"fixdston\\">Yes</label><br /><input type=\\"radio\\" name=\\"fixdst\\" value=\\"0\\" id=\\"fixdstoff\\" $fixdstoff /> <label for=\\"fixdstoff\\">No</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Country:</b></span><br /><span class=\\"smallfont\\">Optional.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><select name=\\"country\\" onChange=\\"if (this.options[this.selectedIndex].value != \'us\') this.form.state.selectedIndex = 0;\\">
		$countries
	</select></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>State:</b></span><br /><span class=\\"smallfont\\">Optional.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><select name=\\"state\\">
		$states
	</select></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Zip code:</b></span><br /><span class=\\"smallfont\\">Optional.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"zip\\" value=\\"$hiveuser[zip]\\" size=\\"7\\" maxlength=\\"7\\" /></td>
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
		</td>
	</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'options_personal_field' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>$field[title]:</b></span><%if $field[required] and !$dontshowreq %> <span class="important">*</span><%endif%><br /><span class="smallfont">$field[description]</span></td>
	<td class="{classname}RightCell" width="40%">$field_html</td>
</tr>
',
    'parsed_data' => '"<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>$field[title]:</b></span>".(($field[required] and !$dontshowreq ) ? (" <span class=\\"important\\">*</span>") : (\'\'))."<br /><span class=\\"smallfont\\">$field[description]</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\">$field_html</td>
</tr>
"',
    'upgraded' => '0',
  ),
  'options_personal_fields_checkbox' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$options
<input type="hidden" name="totals_$field[fieldid]" value="$total" />',
    'parsed_data' => '"$options
<input type=\\"hidden\\" name=\\"totals_$field[fieldid]\\" value=\\"$total\\" />"',
    'upgraded' => '0',
  ),
  'options_personal_fields_checkbox_option' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<input type="checkbox" value="$choiceid" id="fields_$field[fieldid]_$choiceid" name="fields[$field[fieldid]][]" $checked onClick="if (this.checked && this.form.totals_$field[fieldid].value >= $field[max] && $field[max] > 0) { alert(\'You may only select up to $field[max] option(s).\\nPlease deselect another option before selecting this one.\'); return false; } if (!this.checked && this.form.totals_$field[fieldid].value <= $field[min] && $field[min] > 0) { alert(\'You must select at least $field[min] option(s).\\nPlease select another option before deselecting this one.\'); return false; } if (this.checked) { this.form.totals_$field[fieldid].value++; } else { this.form.totals_$field[fieldid].value--; } " /> <label for="fields_$field[fieldid]_$choiceid">$choiceinfo[name]</label>
',
    'parsed_data' => '"<input type=\\"checkbox\\" value=\\"$choiceid\\" id=\\"fields_$field[fieldid]_$choiceid\\" name=\\"fields[$field[fieldid]][]\\" $checked onClick=\\"if (this.checked && this.form.totals_$field[fieldid].value >= $field[max] && $field[max] > 0) { alert(\'You may only select up to $field[max] option(s).\\\\nPlease deselect another option before selecting this one.\'); return false; } if (!this.checked && this.form.totals_$field[fieldid].value <= $field[min] && $field[min] > 0) { alert(\'You must select at least $field[min] option(s).\\\\nPlease select another option before deselecting this one.\'); return false; } if (this.checked) { this.form.totals_$field[fieldid].value++; } else { this.form.totals_$field[fieldid].value--; } \\" /> <label for=\\"fields_$field[fieldid]_$choiceid\\">$choiceinfo[name]</label>
"',
    'upgraded' => '0',
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
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'options_personal_fields_radio_option' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<input type="radio" value="$choiceid" id="fields$field[fieldid]choice$choiceid" name="fields[$field[fieldid]]" $checked onClick="this.form.fields_custom_$field[fieldid].value = \'\';" /> <label for="fields$field[fieldid]choice$choiceid">$choiceinfo[name]</label>
',
    'parsed_data' => '"<input type=\\"radio\\" value=\\"$choiceid\\" id=\\"fields$field[fieldid]choice$choiceid\\" name=\\"fields[$field[fieldid]]\\" $checked onClick=\\"this.form.fields_custom_$field[fieldid].value = \'\';\\" /> <label for=\\"fields$field[fieldid]choice$choiceid\\">$choiceinfo[name]</label>
"',
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'options_personal_fields_text' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<input type="text" name="fields[$field[fieldid]]" class="bginput" style="width: $field[width]px;" value="$field[defvalue]" $maxlength />',
    'parsed_data' => '"<input type=\\"text\\" name=\\"fields[$field[fieldid]]\\" class=\\"bginput\\" style=\\"width: $field[width]px;\\" value=\\"$field[defvalue]\\" $maxlength />"',
    'upgraded' => '0',
  ),
  'options_personal_fields_textarea' => 
  array (
    'templategroupid' => '13',
    'user_data' => '<textarea name="fields[$field[fieldid]]" style="width: $field[width]px; height: $field[height]px;" class="bginput">$field[defvalue]</textarea>',
    'parsed_data' => '"<textarea name=\\"fields[$field[fieldid]]\\" style=\\"width: $field[width]px; height: $field[height]px;\\" class=\\"bginput\\">$field[defvalue]</textarea>"',
    'upgraded' => '0',
  ),
  'options_read' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Reading Options</title>
$css
<script type="text/javascript" language="JavaScript">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$header

<form action="options.read.php" method="post">
<input type="hidden" name="cmd" value="update" />

<table cellpadding="4" cellspacing="0" width="100%">
	<tr>
		<td valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="150">
				<tr class="headerRow">
					<th class="headerBothCell"><span class="normalfonttablehead"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width="100%" valign="top">
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Reading Options</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Show HTML message:</b></span>
	<br />
	<span class="smallfont">Turn this on if you\'d like to see the HTML version of a message if it is available.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="showhtml" value="1" id="showhtmlon" $showhtmlon /> <label for="showhtmlon">Yes</label><br /><input type="radio" name="showhtml" value="0" id="showhtmloff" $showhtmloff /> <label for="showhtmloff">No</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Show advanced headers:</b></span>
	<br />
	<span class="smallfont">If enabled, complete MIME headers an email contains will be displayed when viewing the message.<br />Otherwise, only the basic (sender, recipients, subject and date) headers will be shown.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="showallheaders" value="1" id="showallheaderson" $showallheaderson /> <label for="showallheaderson">Yes</label><br /><input type="radio" name="showallheaders" value="0" id="showallheadersoff" $showallheadersoff /> <label for="showallheadersoff">No</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Returning read receipts:</b></span>
	<br />
	<span class="smallfont">How should read receipt requests be treated?</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont">
	<input type="radio" name="sendread" value="0" id="sendreadno" $sendreadno /> <label for="sendreadno">Never send a read receipt</label><br />
	<input type="radio" name="sendread" value="1" id="sendreadask" $sendreadask /> <label for="sendreadask">Notify me for each read receipt request</label><br />
	<input type="radio" name="sendread" value="2" id="sendreadalways" $sendreadalways /> <label for="sendreadalways">Always send a read receipt</label>
	</span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Attachments open in new window:</b></span>
	<br />
	<span class="smallfont">Do you wish for a new window to open for each attachment?</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="attachwin" value="1" id="attachwinon" $attachwinon /> <label for="attachwinon">Yes</label><br /><input type="radio" name="attachwin" value="0" id="attachwinoff" $attachwinoff /> <label for="attachwinoff">No</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Displayed attached images below the message:</b></span>
	<br />
	<span class="smallfont">Images that are attached to the message you are reading will be automatically displayed below the message when reading it.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="showimginmsg" value="1" id="showimginmsgon" $showimginmsgon /> <label for="showimginmsgon">Yes</label><br /><input type="radio" name="showimginmsg" value="0" id="showimginmsgoff" $showimginmsgoff /> <label for="showimginmsgoff">No</label></span></td>
</tr>
<%if getop(\'allowcid\')%>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Show inline attachments:</b></span>
	<br />
	<span class="smallfont">If enabled, inline attachments (such as embedded images) will appear in the attachments aswell as in the message body.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="showinline" value="1" id="showinlineon" $showinlineon /> <label for="showinlineon">Yes</label><br /><input type="radio" name="showinline" value="0" id="showinlineoff" $showinlineoff /> <label for="showinlineoff">No</label></span></td>
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
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$GLOBALS[header]

<form action=\\"options.read.php{$GLOBALS[session_url]}\\" method=\\"post\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" width=\\"100%\\">
	<tr>
		<td valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"150\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width=\\"100%\\" valign=\\"top\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Reading Options</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Show HTML message:</b></span>
	<br />
	<span class=\\"smallfont\\">Turn this on if you\'d like to see the HTML version of a message if it is available.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"showhtml\\" value=\\"1\\" id=\\"showhtmlon\\" $showhtmlon /> <label for=\\"showhtmlon\\">Yes</label><br /><input type=\\"radio\\" name=\\"showhtml\\" value=\\"0\\" id=\\"showhtmloff\\" $showhtmloff /> <label for=\\"showhtmloff\\">No</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Show advanced headers:</b></span>
	<br />
	<span class=\\"smallfont\\">If enabled, complete MIME headers an email contains will be displayed when viewing the message.<br />Otherwise, only the basic (sender, recipients, subject and date) headers will be shown.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"showallheaders\\" value=\\"1\\" id=\\"showallheaderson\\" $showallheaderson /> <label for=\\"showallheaderson\\">Yes</label><br /><input type=\\"radio\\" name=\\"showallheaders\\" value=\\"0\\" id=\\"showallheadersoff\\" $showallheadersoff /> <label for=\\"showallheadersoff\\">No</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Returning read receipts:</b></span>
	<br />
	<span class=\\"smallfont\\">How should read receipt requests be treated?</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\">
	<input type=\\"radio\\" name=\\"sendread\\" value=\\"0\\" id=\\"sendreadno\\" $sendreadno /> <label for=\\"sendreadno\\">Never send a read receipt</label><br />
	<input type=\\"radio\\" name=\\"sendread\\" value=\\"1\\" id=\\"sendreadask\\" $sendreadask /> <label for=\\"sendreadask\\">Notify me for each read receipt request</label><br />
	<input type=\\"radio\\" name=\\"sendread\\" value=\\"2\\" id=\\"sendreadalways\\" $sendreadalways /> <label for=\\"sendreadalways\\">Always send a read receipt</label>
	</span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Attachments open in new window:</b></span>
	<br />
	<span class=\\"smallfont\\">Do you wish for a new window to open for each attachment?</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"attachwin\\" value=\\"1\\" id=\\"attachwinon\\" $attachwinon /> <label for=\\"attachwinon\\">Yes</label><br /><input type=\\"radio\\" name=\\"attachwin\\" value=\\"0\\" id=\\"attachwinoff\\" $attachwinoff /> <label for=\\"attachwinoff\\">No</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Displayed attached images below the message:</b></span>
	<br />
	<span class=\\"smallfont\\">Images that are attached to the message you are reading will be automatically displayed below the message when reading it.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"showimginmsg\\" value=\\"1\\" id=\\"showimginmsgon\\" $showimginmsgon /> <label for=\\"showimginmsgon\\">Yes</label><br /><input type=\\"radio\\" name=\\"showimginmsg\\" value=\\"0\\" id=\\"showimginmsgoff\\" $showimginmsgoff /> <label for=\\"showimginmsgoff\\">No</label></span></td>
</tr>
".((getop(\'allowcid\')) ? ("
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Show inline attachments:</b></span>
	<br />
	<span class=\\"smallfont\\">If enabled, inline attachments (such as embedded images) will appear in the attachments aswell as in the message body.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"showinline\\" value=\\"1\\" id=\\"showinlineon\\" $showinlineon /> <label for=\\"showinlineon\\">Yes</label><br /><input type=\\"radio\\" name=\\"showinline\\" value=\\"0\\" id=\\"showinlineoff\\" $showinlineoff /> <label for=\\"showinlineoff\\">No</label></span></td>
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
		</td>
	</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'options_signature' => 
  array (
    'templategroupid' => '13',
    'user_data' => '$skin[doctype]
<html<%if $hiveuser[wysiwyg] %> XMLNS:ACE<%endif%>>
<head><title>$appname: Signatures</title>
<%if $hiveuser[wysiwyg] %><?import namespace="ACE" implementation="misc/ace.htc" /><%endif%>
<!-- ?> -->
$css
<script language="JavaScript" type="text/javascript">
<!--

var totalSigs = $totalSigs_real;
var workingWith = \'signature\';
// This is the text that\'s added to default sigs (don\'t forget leading space)
var defstr = \' (default)\';
// The text that is displayed when no signature is selected
var defaultValue = \'(select a signature to edit from the list)\';

function editorInit() {
	<%if $hiveuser[wysiwyg] %>
		idContent.editorWidth = "560";
		idContent.editorHeight = "140";
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
		idContent.content = defaultValue;
		idContent.style.background = \'$skin[formbackground]\';
		idContent.docBgColor = \'$skin[formbackground]\';
	<%else%>
		document.sigform.sigedit.value = defaultValue;
	<%endif%>
}

function getContent() {
	<%if $hiveuser[wysiwyg] %>
		return idContent.content;
	<%else%>
		return document.sigform.sigedit.value;
	<%endif%>
}

function getContentTagLess() {
	<%if $hiveuser[wysiwyg] %>
		return idContent.getText();
	<%else%>
		return \'\';
	<%endif%>
}

function setContent(value) {
	<%if $hiveuser[wysiwyg] %>
		idContent.content = value;
	<%else%>
		document.sigform.sigedit.value = value;
	<%endif%>
}

// -->
</script>
<script type="text/javascript" src="misc/signatures.js"></script>
<script type="text/javascript" language="JavaScript">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
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

<table cellpadding="4" cellspacing="0" width="100%">
	<tr>
		<td valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="150">
				<tr class="headerRow">
					<th class="headerBothCell"><span class="normalfonttablehead"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width="100%" valign="top">
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Your Signatures</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Automatically add signature:</b></span>
	<br />
	<span class="smallfont">If this is turned on, the default signature you specify below will automatically be added<br />to your messages before they are sent.<br />Otherwise, you will have the option to add the signature manually.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="autoaddsig" value="2" id="autoaddsigon" $autoaddsigon /> <label for="autoaddsigon">Yes</label><br /><input type="radio" name="autoaddsig" value="1" id="autoaddsigonly" $autoaddsigonly /> <label for="autoaddsigonly">Only when not replying</label><br /><input type="radio" name="autoaddsig" value="0" id="autoaddsigoff" $autoaddsigoff /> <label for="autoaddsigoff">No</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Use a random signature every time:</b></span>
	<br />
	<span class="smallfont">If you have enabled the option above for automatically adding your signature, you can turn this on to have the system use a different, random signature every time. Otherwise the default signature as defined below will be used.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="userandomsig" value="1" id="userandomsigon" $userandomsigon /> <label for="userandomsigon">Yes</label><br /><input type="radio" name="userandomsig" value="0" id="userandomsigoff" $userandomsigoff /> <label for="userandomsigoff">No</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}BothCell" colspan="2" width="100%" valign="top"><span class="normalfont"><b>Signature Editor:</b><br />
	To edit a signature, select it from the list below and edit it in large box.<br />
	To rename a signature, select it then click the Rename button below and enter the new name.<br />
	To mark your default signature, select it from the list and click the Make Default button below.<br />
	To create a new signature, click the Create New button below and enter the name of the new signature.<%if $totalsigs >= $hiveuser[\'maxsigs\'] %><br />(<b>Note</b>: You may only have up to $hiveuser[maxsigs] signatures. You won\'t be able to create new signatures until you delete at least some of your current signatures.)<%endif%><br />
	To delete a signature, select it from the list and click the Delete button below. <b>Note</b>: You cannot remove your default signature, unless it is the only signature you have.<br />
	<br />
	Please remember to click the Update Signatures button at the bottom of this page, or else any changes that you make here will have no effect!</span><br /><br />
	<table>
		<tr>
			<td valign="top"><select name="sigs" size="<%if $hiveuser[wysiwyg] %>14<%else%>9<%endif%>" onChange="updateSigDisplay(this.form);">
					$sig_options
				</select></td>
			<td valign="top"><%if $hiveuser[wysiwyg] %><ACE:AdvContentEditor id="idContent" tabindex="3" /><%else%><textarea name="sigedit" cols="70" rows="8"></textarea><%endif%></td>
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
		</td>
	</tr>
</table>

</form>

<script language="JavaScript">
<!--
editorInit();
// -->
</script>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html".(($hiveuser[wysiwyg] ) ? (" XMLNS:ACE") : (\'\')).">
<head><title>$appname: Signatures</title>
".(($hiveuser[wysiwyg] ) ? ("<?import namespace=\\"ACE\\" implementation=\\"misc/ace.htc\\" />") : (\'\'))."
<!-- ?> -->
$GLOBALS[css]
<script language=\\"JavaScript\\" type=\\"text/javascript\\">
<!--

var totalSigs = $totalSigs_real;
var workingWith = \'signature\';
// This is the text that\'s added to default sigs (don\'t forget leading space)
var defstr = \' (default)\';
// The text that is displayed when no signature is selected
var defaultValue = \'(select a signature to edit from the list)\';

function editorInit() {
	".(($hiveuser[wysiwyg] ) ? ("
		idContent.editorWidth = \\"560\\";
		idContent.editorHeight = \\"140\\";
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
		idContent.content = defaultValue;
		idContent.style.background = \'{$GLOBALS[skin][formbackground]}\';
		idContent.docBgColor = \'{$GLOBALS[skin][formbackground]}\';
	") : ("
		document.sigform.sigedit.value = defaultValue;
	"))."
}

function getContent() {
	".(($hiveuser[wysiwyg] ) ? ("
		return idContent.content;
	") : ("
		return document.sigform.sigedit.value;
	"))."
}

function getContentTagLess() {
	".(($hiveuser[wysiwyg] ) ? ("
		return idContent.getText();
	") : ("
		return \'\';
	"))."
}

function setContent(value) {
	".(($hiveuser[wysiwyg] ) ? ("
		idContent.content = value;
	") : ("
		document.sigform.sigedit.value = value;
	"))."
}

// -->
</script>
<script type=\\"text/javascript\\" src=\\"misc/signatures.js\\"></script>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
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

<table cellpadding=\\"4\\" cellspacing=\\"0\\" width=\\"100%\\">
	<tr>
		<td valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"150\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width=\\"100%\\" valign=\\"top\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Your Signatures</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Automatically add signature:</b></span>
	<br />
	<span class=\\"smallfont\\">If this is turned on, the default signature you specify below will automatically be added<br />to your messages before they are sent.<br />Otherwise, you will have the option to add the signature manually.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"autoaddsig\\" value=\\"2\\" id=\\"autoaddsigon\\" $autoaddsigon /> <label for=\\"autoaddsigon\\">Yes</label><br /><input type=\\"radio\\" name=\\"autoaddsig\\" value=\\"1\\" id=\\"autoaddsigonly\\" $autoaddsigonly /> <label for=\\"autoaddsigonly\\">Only when not replying</label><br /><input type=\\"radio\\" name=\\"autoaddsig\\" value=\\"0\\" id=\\"autoaddsigoff\\" $autoaddsigoff /> <label for=\\"autoaddsigoff\\">No</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Use a random signature every time:</b></span>
	<br />
	<span class=\\"smallfont\\">If you have enabled the option above for automatically adding your signature, you can turn this on to have the system use a different, random signature every time. Otherwise the default signature as defined below will be used.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"userandomsig\\" value=\\"1\\" id=\\"userandomsigon\\" $userandomsigon /> <label for=\\"userandomsigon\\">Yes</label><br /><input type=\\"radio\\" name=\\"userandomsig\\" value=\\"0\\" id=\\"userandomsigoff\\" $userandomsigoff /> <label for=\\"userandomsigoff\\">No</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\" colspan=\\"2\\" width=\\"100%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Signature Editor:</b><br />
	To edit a signature, select it from the list below and edit it in large box.<br />
	To rename a signature, select it then click the Rename button below and enter the new name.<br />
	To mark your default signature, select it from the list and click the Make Default button below.<br />
	To create a new signature, click the Create New button below and enter the name of the new signature.".(($totalsigs >= $hiveuser[\'maxsigs\'] ) ? ("<br />(<b>Note</b>: You may only have up to $hiveuser[maxsigs] signatures. You won\'t be able to create new signatures until you delete at least some of your current signatures.)") : (\'\'))."<br />
	To delete a signature, select it from the list and click the Delete button below. <b>Note</b>: You cannot remove your default signature, unless it is the only signature you have.<br />
	<br />
	Please remember to click the Update Signatures button at the bottom of this page, or else any changes that you make here will have no effect!</span><br /><br />
	<table>
		<tr>
			<td valign=\\"top\\"><select name=\\"sigs\\" size=\\"".(($hiveuser[wysiwyg] ) ? ("14") : ("9"))."\\" onChange=\\"updateSigDisplay(this.form);\\">
					$sig_options
				</select></td>
			<td valign=\\"top\\">".(($hiveuser[wysiwyg] ) ? ("<ACE:AdvContentEditor id=\\"idContent\\" tabindex=\\"3\\" />") : ("<textarea name=\\"sigedit\\" cols=\\"70\\" rows=\\"8\\"></textarea>"))."</td>
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
		</td>
	</tr>
</table>

</form>

<script language=\\"JavaScript\\">
<!--
editorInit();
// -->
</script>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'options_timezone' => 
  array (
    'templategroupid' => '13',
    'user_data' => '	<select name="$fieldname">
		<%if $noselect %>
		<option value="-13" $tzsel[n130]>(unknown)</option>
		<%endif%>
		<option value="-12" $tzsel[n120]>(GMT -12:00) $tztime[n120]</option>
		<option value="-11" $tzsel[n110]>(GMT -11:00) $tztime[n110]</option>
		<option value="-10" $tzsel[n100]>(GMT -10:00) $tztime[n100]</option>
		<option value="-9" $tzsel[n90]>(GMT -9:00) $tztime[n90]</option>
		<option value="-8" $tzsel[n80]>(GMT -8:00) $tztime[n80]</option>
		<option value="-7" $tzsel[n70]>(GMT -7:00) $tztime[n70]</option>
		<option value="-6" $tzsel[n60]>(GMT -6:00) $tztime[n60]</option>
		<option value="-5" $tzsel[n50]>(GMT -5:00) $tztime[n50]</option>
		<option value="-4" $tzsel[n40]>(GMT -4:00) $tztime[n40]</option>
		<option value="-3.5" $tzsel[n35]>(GMT -3:30) $tztime[n35]</option>
		<option value="-3" $tzsel[n30]>(GMT -3:00) $tztime[n30]</option>
		<option value="-2" $tzsel[n20]>(GMT -2:00) $tztime[n20]</option>
		<option value="-1" $tzsel[n10]>(GMT -1:00) $tztime[n10]</option>
		<option value="0" $tzsel[0]>(GMT) $tztime[0]</option>
		<option value="1" $tzsel[10]>(GMT +1:00) $tztime[10]</option>
		<option value="2" $tzsel[20]>(GMT +2:00) $tztime[20]</option>
		<option value="3" $tzsel[30]>(GMT +3:00) $tztime[30]</option>
		<option value="3.5" $tzsel[35]>(GMT +3:30) $tztime[35]</option>
		<option value="4" $tzsel[40]>(GMT +4:00) $tztime[40]</option>
		<option value="4.5" $tzsel[45]>(GMT +4:30) $tztime[45]</option>
		<option value="5" $tzsel[50]>(GMT +5:00) $tztime[50]</option>
		<option value="5.5" $tzsel[55]>(GMT +5:30) $tztime[55]</option>
		<option value="6" $tzsel[60]>(GMT +6:00) $tztime[60]</option>
		<option value="7" $tzsel[70]>(GMT +7:00) $tztime[70]</option>
		<option value="8" $tzsel[80]>(GMT +8:00) $tztime[80]</option>
		<option value="9" $tzsel[90]>(GMT +9:00) $tztime[90]</option>
		<option value="9.5" $tzsel[95]>(GMT +9:30) $tztime[95]</option>
		<option value="10" $tzsel[100]>(GMT +10:00) $tztime[100]</option>
		<option value="11" $tzsel[110]>(GMT +11:00) $tztime[110]</option>
		<option value="12" $tzsel[120]>(GMT +12:00)  $tztime[120]</option>
	</select>',
    'parsed_data' => '"	<select name=\\"$fieldname\\">
		".(($noselect ) ? ("
		<option value=\\"-13\\" $tzsel[n130]>(unknown)</option>
		") : (\'\'))."
		<option value=\\"-12\\" $tzsel[n120]>(GMT -12:00) $tztime[n120]</option>
		<option value=\\"-11\\" $tzsel[n110]>(GMT -11:00) $tztime[n110]</option>
		<option value=\\"-10\\" $tzsel[n100]>(GMT -10:00) $tztime[n100]</option>
		<option value=\\"-9\\" $tzsel[n90]>(GMT -9:00) $tztime[n90]</option>
		<option value=\\"-8\\" $tzsel[n80]>(GMT -8:00) $tztime[n80]</option>
		<option value=\\"-7\\" $tzsel[n70]>(GMT -7:00) $tztime[n70]</option>
		<option value=\\"-6\\" $tzsel[n60]>(GMT -6:00) $tztime[n60]</option>
		<option value=\\"-5\\" $tzsel[n50]>(GMT -5:00) $tztime[n50]</option>
		<option value=\\"-4\\" $tzsel[n40]>(GMT -4:00) $tztime[n40]</option>
		<option value=\\"-3.5\\" $tzsel[n35]>(GMT -3:30) $tztime[n35]</option>
		<option value=\\"-3\\" $tzsel[n30]>(GMT -3:00) $tztime[n30]</option>
		<option value=\\"-2\\" $tzsel[n20]>(GMT -2:00) $tztime[n20]</option>
		<option value=\\"-1\\" $tzsel[n10]>(GMT -1:00) $tztime[n10]</option>
		<option value=\\"0\\" $tzsel[0]>(GMT) $tztime[0]</option>
		<option value=\\"1\\" $tzsel[10]>(GMT +1:00) $tztime[10]</option>
		<option value=\\"2\\" $tzsel[20]>(GMT +2:00) $tztime[20]</option>
		<option value=\\"3\\" $tzsel[30]>(GMT +3:00) $tztime[30]</option>
		<option value=\\"3.5\\" $tzsel[35]>(GMT +3:30) $tztime[35]</option>
		<option value=\\"4\\" $tzsel[40]>(GMT +4:00) $tztime[40]</option>
		<option value=\\"4.5\\" $tzsel[45]>(GMT +4:30) $tztime[45]</option>
		<option value=\\"5\\" $tzsel[50]>(GMT +5:00) $tztime[50]</option>
		<option value=\\"5.5\\" $tzsel[55]>(GMT +5:30) $tztime[55]</option>
		<option value=\\"6\\" $tzsel[60]>(GMT +6:00) $tztime[60]</option>
		<option value=\\"7\\" $tzsel[70]>(GMT +7:00) $tztime[70]</option>
		<option value=\\"8\\" $tzsel[80]>(GMT +8:00) $tztime[80]</option>
		<option value=\\"9\\" $tzsel[90]>(GMT +9:00) $tztime[90]</option>
		<option value=\\"9.5\\" $tzsel[95]>(GMT +9:30) $tztime[95]</option>
		<option value=\\"10\\" $tzsel[100]>(GMT +10:00) $tztime[100]</option>
		<option value=\\"11\\" $tzsel[110]>(GMT +11:00) $tztime[110]</option>
		<option value=\\"12\\" $tzsel[120]>(GMT +12:00)  $tztime[120]</option>
	</select>"',
    'upgraded' => '0',
  ),
  'pagenav' => 
  array (
    'templategroupid' => '11',
    'user_data' => 'Pages ($totalpages): <b>$firstlink $prevlink $pagenav $nextlink $lastlink</b>',
    'parsed_data' => '"Pages ($totalpages): <b>$firstlink $prevlink $pagenav $nextlink $lastlink</b>"',
    'upgraded' => '0',
  ),
  'pagenav_curpage' => 
  array (
    'templategroupid' => '11',
    'user_data' => ' <font size="2">[$curpage]</font> ',
    'parsed_data' => '" <font size=\\"2\\">[$curpage]</font> "',
    'upgraded' => '0',
  ),
  'pagenav_firstlink' => 
  array (
    'templategroupid' => '11',
    'user_data' => ' <a href="$address&pagenumber=$curpage" title="first page">&laquo; First</a> ... ',
    'parsed_data' => '" <a href=\\"$address&pagenumber=$curpage\\" title=\\"first page\\">&laquo; First</a> ... "',
    'upgraded' => '0',
  ),
  'pagenav_lastlink' => 
  array (
    'templategroupid' => '11',
    'user_data' => '... <a href="$address&pagenumber=$curpage" title="last page">Last &raquo;</a>',
    'parsed_data' => '"... <a href=\\"$address&pagenumber=$curpage\\" title=\\"last page\\">Last &raquo;</a>"',
    'upgraded' => '0',
  ),
  'pagenav_nextlink' => 
  array (
    'templategroupid' => '11',
    'user_data' => '<a href="$address&pagenumber=$nextpage" title="next page">&raquo;</a>',
    'parsed_data' => '"<a href=\\"$address&pagenumber=$nextpage\\" title=\\"next page\\">&raquo;</a>"',
    'upgraded' => '0',
  ),
  'pagenav_pagelink' => 
  array (
    'templategroupid' => '11',
    'user_data' => ' <a href="$address&pagenumber=$curpage">$curpage</a> ',
    'parsed_data' => '" <a href=\\"$address&pagenumber=$curpage\\">$curpage</a> "',
    'upgraded' => '0',
  ),
  'pagenav_prevlink' => 
  array (
    'templategroupid' => '11',
    'user_data' => ' <a href="$address&pagenumber=$prevpage" title="previous page">&laquo;</a> ',
    'parsed_data' => '" <a href=\\"$address&pagenumber=$prevpage\\" title=\\"previous page\\">&laquo;</a> "',
    'upgraded' => '0',
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
     var hWnd = window.open("pop.list.php?cmd=popup&popid="+popID, "POP3Props", "width=520,height=385,resizable=yes,scrollbars=yes");
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
<script type="text/javascript" language="JavaScript">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$header

<form action="pop.update.php" method="post" name="form">
<input type="hidden" name="cmd" value="updateall" />
<input type="hidden" name="origpass" value="$origpass" />

<table cellpadding="4" cellspacing="0" width="100%">
	<tr>
		<td valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="150">
				<tr class="headerRow">
					<th class="headerBothCell"><span class="normalfonttablehead"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width="100%" valign="top">
<table width="730" cellpadding="7">
	<tr>
		<td colspan="2" style="padding: 0px 12px 4px 12px;">
<%if $hiveuser[\'canhivepop\'] %>
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Internal POP3 Account</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}BothCell" valign="top" colspan="2"><span class="normalfont"><b>Connection instructions:</b>
	<br />
	<span class="smallfont">When setting up your POP3 account on another client, set the server address to </span><b>$hivepop_serveraddr</b><span class="smallfont"> and the server port to </span><b>$hivepop_serverport</b><span class="smallfont"> (this can usually be found in the advanced settings). Your username is </span><b>$hiveuser[username]</b><span class="smallfont"> and the password is same as your online account.</span></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Where to store messages:</b></span>
	<br />
	<span class="smallfont">You can choose to store incoming messages in the online account or in the POP3 account. <%if getop(\'hivepop_allowdupe\') %>It is also possible to store messages at both locations, so you can read them wherever you wish.<%endif%></span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="savetopop" value="0" id="savetopopnosave" $savetopopnosave /> <label for="savetopopnosave">Online account only</label><br /><input type="radio" name="savetopop" value="1" id="savetopoponly" $savetopoponly /> <label for="savetopoponly">POP3 account only</label><%if getop(\'hivepop_allowdupe\') %><br /><input type="radio" name="savetopop" value="2" id="savetopopboth" $savetopopboth /> <label for="savetopopboth">Both online and POP3 accounts</label><%endif%></span></td>
</tr>
<%if getop(\'hivepop_allowdupe\') %>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Synchronize account with online mailbox:</b></span>
	<br />
	<span class="smallfont">If you turn this on, any messages that are deleted from the online account will also be removed from the POP3 account, if you chose to store messages at both locations.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="synchivepop" value="1" id="synchivepopon" $synchivepopon /><label for="synchivepopon">Yes<label><br /><input type="radio" name="synchivepop" value="0" id="synchivepopoff" $synchivepopoff /><label for="synchivepopoff">No<label></span></td>
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

<br />
<%endif%>

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
			<tr class="headerRow">
				<th class="headerBothCell"><span class="normalfonttablehead">External POP3 Accounts</span></th>
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
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--

function popProps(popID) {
     var hWnd = window.open(\\"pop.list.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=popup&popid=\\"+popID, \\"POP3Props\\", \\"width=520,height=385,resizable=yes,scrollbars=yes\\");
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
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$GLOBALS[header]

<form action=\\"pop.update.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"updateall\\" />
<input type=\\"hidden\\" name=\\"origpass\\" value=\\"$origpass\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" width=\\"100%\\">
	<tr>
		<td valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"150\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width=\\"100%\\" valign=\\"top\\">
<table width=\\"730\\" cellpadding=\\"7\\">
	<tr>
		<td colspan=\\"2\\" style=\\"padding: 0px 12px 4px 12px;\\">
".(($hiveuser[\'canhivepop\'] ) ? ("
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Internal POP3 Account</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\" valign=\\"top\\" colspan=\\"2\\"><span class=\\"normalfont\\"><b>Connection instructions:</b>
	<br />
	<span class=\\"smallfont\\">When setting up your POP3 account on another client, set the server address to </span><b>$hivepop_serveraddr</b><span class=\\"smallfont\\"> and the server port to </span><b>$hivepop_serverport</b><span class=\\"smallfont\\"> (this can usually be found in the advanced settings). Your username is </span><b>$hiveuser[username]</b><span class=\\"smallfont\\"> and the password is same as your online account.</span></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Where to store messages:</b></span>
	<br />
	<span class=\\"smallfont\\">You can choose to store incoming messages in the online account or in the POP3 account. ".((getop(\'hivepop_allowdupe\') ) ? ("It is also possible to store messages at both locations, so you can read them wherever you wish.") : (\'\'))."</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"savetopop\\" value=\\"0\\" id=\\"savetopopnosave\\" $savetopopnosave /> <label for=\\"savetopopnosave\\">Online account only</label><br /><input type=\\"radio\\" name=\\"savetopop\\" value=\\"1\\" id=\\"savetopoponly\\" $savetopoponly /> <label for=\\"savetopoponly\\">POP3 account only</label>".((getop(\'hivepop_allowdupe\') ) ? ("<br /><input type=\\"radio\\" name=\\"savetopop\\" value=\\"2\\" id=\\"savetopopboth\\" $savetopopboth /> <label for=\\"savetopopboth\\">Both online and POP3 accounts</label>") : (\'\'))."</span></td>
</tr>
".((getop(\'hivepop_allowdupe\') ) ? ("
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Synchronize account with online mailbox:</b></span>
	<br />
	<span class=\\"smallfont\\">If you turn this on, any messages that are deleted from the online account will also be removed from the POP3 account, if you chose to store messages at both locations.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"synchivepop\\" value=\\"1\\" id=\\"synchivepopon\\" $synchivepopon /><label for=\\"synchivepopon\\">Yes<label><br /><input type=\\"radio\\" name=\\"synchivepop\\" value=\\"0\\" id=\\"synchivepopoff\\" $synchivepopoff /><label for=\\"synchivepopoff\\">No<label></span></td>
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

<br />
") : (\'\'))."

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
			<tr class=\\"headerRow\\">
				<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\">External POP3 Accounts</span></th>
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
		</td>
	</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'pop_accountbit' => 
  array (
    'templategroupid' => '15',
    'user_data' => '<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
	<tr class="headerRow">
		<th class="headerBothCell" colspan="2"><span style="vertical-align: middle;"><a href="#" onClick="popProps($pop[popid]); return false;"><span class="normalfonttablehead"><b>$pop[accountname]</b>&nbsp;&nbsp;&nbsp;<img src="$skin[images]/pencil.gif" alt="Edit Account" border="0" align="middle" /></span></a>&nbsp;&nbsp;<a href="pop.delete.php?popid=$pop[popid]" onClick="if (!confirm(\'Are you sure you want to remove this account?\')) return false;"><img src="$skin[images]/delete.gif" alt="Remove Account" border="0" align="middle" /></a>&nbsp;&nbsp;<a href="pop.download.php?popid=$pop[popid]"><img src="$skin[images]/download.gif" alt="Download Messages" border="0" align="middle" /></a></span></th>
	</tr>
	<tr>
		<td class="highLeftCell" align="left"><span class="normalfont"><b><label for="displayemail$pop[popid]">Email address:</label></b></span></td>
		<td class="highRightCell" align="left"><input type="text" id="displayemail$pop[popid]" class="highInactive" name="serverinfo[$pop[popid]][displayemail]" value="$pop[displayemail]" size="26" onFocus="this.select(); this.className = \'bginput\';" onBlur="this.className = \'highInactive\';" /></td>
	</tr>
	<tr>
		<td class="normalLeftCell" align="left" width="126"><span class="normalfont"><b><label for="server$pop[popid]">POP3 Server:</label></b></span></td>
		<td class="normalRightCell" align="left"><input type="text" id="server$pop[popid]" class="normalInactive" name="serverinfo[$pop[popid]][server]" value="$pop[server]" size="20" onFocus="this.select(); this.className = \'bginput\';" onBlur="this.className = \'normalInactive\';" /><input type="text" id="port$pop[popid]" class="normalInactive" name="serverinfo[$pop[popid]][port]" value="$pop[port]" size="4" onFocus="this.select(); this.className = \'bginput\';" onBlur="this.className = \'normalInactive\';" /></td>
	</tr>
	<tr>
		<td class="highLeftCell" align="left"><span class="normalfont"><b><label for="username$pop[popid]">Username:</label></b></span></td>
		<td class="highRightCell" align="left"><input type="text" id="username$pop[popid]" class="highInactive" name="serverinfo[$pop[popid]][username]" value="$pop[username]" size="26" onFocus="this.select(); this.className = \'bginput\';" onBlur="this.className = \'highInactive\';" /></td>
	</tr>
	<tr>
		<td class="normalLeftCell" align="left"><span class="normalfont"><b><label for="password$pop[popid]">Password:</label></b></span></td>
		<td class="normalRightCell" align="left"><input type="password" autocomplete="off" id="password$pop[popid]" class="normalInactive" name="serverinfo[$pop[popid]][password]" value="$pop[password]" size="26" onFocus="this.select(); this.className = \'bginput\';" onBlur="this.className = \'normalInactive\';" /></td>
	</tr>
	<tr>
		<td class="highLeftCell" align="left"><span class="normalfont"><b><label for="smtpserver$pop[popid]">SMTP Server:</label></b></span></td>
		<td class="highRightCell" align="left"><input type="text" id="smtpserver$pop[popid]" class="highInactive" name="serverinfo[$pop[popid]][smtp_server]" value="$pop[smtp_server]" size="20" onFocus="if (this.value == \'(none)\') this.value = \'\'; this.select(); this.className = \'bginput\';" onBlur="if (this.value == \'\') this.value = \'(none)\'; this.className = \'highInactive\';" /><input type="text" id="smtpport$pop[popid]" class="highInactive" name="serverinfo[$pop[popid]][smtp_port]" value="$pop[smtp_port]" size="4" onFocus="this.select(); this.className = \'bginput\';" onBlur="this.className = \'highInactive\';" /></td>
	</tr>
</table>',
    'parsed_data' => '"<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
	<tr class=\\"headerRow\\">
		<th class=\\"headerBothCell\\" colspan=\\"2\\"><span style=\\"vertical-align: middle;\\"><a href=\\"#\\" onClick=\\"popProps($pop[popid]); return false;\\"><span class=\\"normalfonttablehead\\"><b>$pop[accountname]</b>&nbsp;&nbsp;&nbsp;<img src=\\"{$GLOBALS[skin][images]}/pencil.gif\\" alt=\\"Edit Account\\" border=\\"0\\" align=\\"middle\\" /></span></a>&nbsp;&nbsp;<a href=\\"pop.delete.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}popid=$pop[popid]\\" onClick=\\"if (!confirm(\'Are you sure you want to remove this account?\')) return false;\\"><img src=\\"{$GLOBALS[skin][images]}/delete.gif\\" alt=\\"Remove Account\\" border=\\"0\\" align=\\"middle\\" /></a>&nbsp;&nbsp;<a href=\\"pop.download.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}popid=$pop[popid]\\"><img src=\\"{$GLOBALS[skin][images]}/download.gif\\" alt=\\"Download Messages\\" border=\\"0\\" align=\\"middle\\" /></a></span></th>
	</tr>
	<tr>
		<td class=\\"highLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><b><label for=\\"displayemail$pop[popid]\\">Email address:</label></b></span></td>
		<td class=\\"highRightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"displayemail$pop[popid]\\" class=\\"highInactive\\" name=\\"serverinfo[$pop[popid]][displayemail]\\" value=\\"$pop[displayemail]\\" size=\\"26\\" onFocus=\\"this.select(); this.className = \'bginput\';\\" onBlur=\\"this.className = \'highInactive\';\\" /></td>
	</tr>
	<tr>
		<td class=\\"normalLeftCell\\" align=\\"left\\" width=\\"126\\"><span class=\\"normalfont\\"><b><label for=\\"server$pop[popid]\\">POP3 Server:</label></b></span></td>
		<td class=\\"normalRightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"server$pop[popid]\\" class=\\"normalInactive\\" name=\\"serverinfo[$pop[popid]][server]\\" value=\\"$pop[server]\\" size=\\"20\\" onFocus=\\"this.select(); this.className = \'bginput\';\\" onBlur=\\"this.className = \'normalInactive\';\\" /><input type=\\"text\\" id=\\"port$pop[popid]\\" class=\\"normalInactive\\" name=\\"serverinfo[$pop[popid]][port]\\" value=\\"$pop[port]\\" size=\\"4\\" onFocus=\\"this.select(); this.className = \'bginput\';\\" onBlur=\\"this.className = \'normalInactive\';\\" /></td>
	</tr>
	<tr>
		<td class=\\"highLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><b><label for=\\"username$pop[popid]\\">Username:</label></b></span></td>
		<td class=\\"highRightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"username$pop[popid]\\" class=\\"highInactive\\" name=\\"serverinfo[$pop[popid]][username]\\" value=\\"$pop[username]\\" size=\\"26\\" onFocus=\\"this.select(); this.className = \'bginput\';\\" onBlur=\\"this.className = \'highInactive\';\\" /></td>
	</tr>
	<tr>
		<td class=\\"normalLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><b><label for=\\"password$pop[popid]\\">Password:</label></b></span></td>
		<td class=\\"normalRightCell\\" align=\\"left\\"><input type=\\"password\\" autocomplete=\\"off\\" id=\\"password$pop[popid]\\" class=\\"normalInactive\\" name=\\"serverinfo[$pop[popid]][password]\\" value=\\"$pop[password]\\" size=\\"26\\" onFocus=\\"this.select(); this.className = \'bginput\';\\" onBlur=\\"this.className = \'normalInactive\';\\" /></td>
	</tr>
	<tr>
		<td class=\\"highLeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><b><label for=\\"smtpserver$pop[popid]\\">SMTP Server:</label></b></span></td>
		<td class=\\"highRightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"smtpserver$pop[popid]\\" class=\\"highInactive\\" name=\\"serverinfo[$pop[popid]][smtp_server]\\" value=\\"$pop[smtp_server]\\" size=\\"20\\" onFocus=\\"if (this.value == \'(none)\') this.value = \'\'; this.select(); this.className = \'bginput\';\\" onBlur=\\"if (this.value == \'\') this.value = \'(none)\'; this.className = \'highInactive\';\\" /><input type=\\"text\\" id=\\"smtpport$pop[popid]\\" class=\\"highInactive\\" name=\\"serverinfo[$pop[popid]][smtp_port]\\" value=\\"$pop[smtp_port]\\" size=\\"4\\" onFocus=\\"this.select(); this.className = \'bginput\';\\" onBlur=\\"this.className = \'highInactive\';\\" /></td>
	</tr>
</table>"',
    'upgraded' => '0',
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
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="displayname">Your name:</label></span></td>
		<td class="{classname}RightCell" align="left"><input type="text" id="displayname" class="bginput" name="newpop[displayname]" size="20" /></td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="displayemail">Email address:</label></span></td>
		<td class="{classname}RightCell" align="left"><input type="text" id="displayemail" class="bginput" name="newpop[displayemail]" size="20" /></td>
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
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"displayname\\">Your name:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"displayname\\" class=\\"bginput\\" name=\\"newpop[displayname]\\" size=\\"20\\" /></td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"displayemail\\">Email address:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"displayemail\\" class=\\"bginput\\" name=\\"newpop[displayemail]\\" size=\\"20\\" /></td>
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
    'upgraded' => '0',
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
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="popserver">Server</label> and <label for="popport">Port</label>:</span></td>
		<td class="{classname}RightCell" align="left"><input type="text" id="popserver" class="bginput" name="newpop[server]" value="$newpop[server]" size="20" />&nbsp;<input type="text" id="popport" class="bginput" name="newpop[port]" value="$newpop[port]" size="4" /></td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="popusername">Username:</label></span></td>
		<td class="{classname}RightCell" align="left"><input type="text" id="popusername" class="bginput" name="newpop[username]" value="$newpop[username]" size="20" /></td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="poppassword">Password:</label></span></td>
		<td class="{classname}RightCell" align="left"><input type="password" autocomplete="off" id="poppassword" class="bginput" name="newpop[password]" value="$newpop[password]" size="20" /></td>
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
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"popserver\\">Server</label> and <label for=\\"popport\\">Port</label>:</span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"popserver\\" class=\\"bginput\\" name=\\"newpop[server]\\" value=\\"$newpop[server]\\" size=\\"20\\" />&nbsp;<input type=\\"text\\" id=\\"popport\\" class=\\"bginput\\" name=\\"newpop[port]\\" value=\\"$newpop[port]\\" size=\\"4\\" /></td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"popusername\\">Username:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"popusername\\" class=\\"bginput\\" name=\\"newpop[username]\\" value=\\"$newpop[username]\\" size=\\"20\\" /></td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"poppassword\\">Password:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><input type=\\"password\\" autocomplete=\\"off\\" id=\\"poppassword\\" class=\\"bginput\\" name=\\"newpop[password]\\" value=\\"$newpop[password]\\" size=\\"20\\" /></td>
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
    'upgraded' => '0',
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
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="smtpserver">Server</label> and <label for="smtpport">Port</label>:</span></td>
		<td class="{classname}RightCell" align="left"><input type="text" id="smtpserver" class="bginput" name="newpop[smtp_server]" value="$newpop[smtp_server]" size="20" onKeyUp="this.form.smtpauth.disabled = (this.value == \'\');" />&nbsp;<input type="text" id="smtpport" class="bginput" name="newpop[smtp_port]" value="$newpop[smtp_port]" size="4" /></td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="smtpauth">Authentication:</label></span></td>
		<td class="{classname}RightCell" align="left"><select name="smtpauth" $authdisabled onChange="changeAuth(this.options[this.selectedIndex].value, this.form);">
			<option value="none" $authsel[none]>No authentication required</option>
			<option value="same" $authsel[same]>Use same login values as POP3</option>
			<option value="diff" $authsel[diff]>Enter different login information</option>
		</select></td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="smtpusername">Username:</label></span></td>
		<td class="{classname}RightCell" align="left"><input type="text" $smtplogindisabled id="smtpusername" class="bginput" name="newpop[smtp_username]" value="$newpop[smtp_username]" size="20" /></td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="smtppassword">Password:</label></span></td>
		<td class="{classname}RightCell" align="left"><input type="password" $smtplogindisabled autocomplete="off" id="smtppassword" class="bginput" name="newpop[smtp_password]" value="$newpop[smtp_password]" size="20" /></td>
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
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"smtpserver\\">Server</label> and <label for=\\"smtpport\\">Port</label>:</span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"smtpserver\\" class=\\"bginput\\" name=\\"newpop[smtp_server]\\" value=\\"$newpop[smtp_server]\\" size=\\"20\\" onKeyUp=\\"this.form.smtpauth.disabled = (this.value == \'\');\\" />&nbsp;<input type=\\"text\\" id=\\"smtpport\\" class=\\"bginput\\" name=\\"newpop[smtp_port]\\" value=\\"$newpop[smtp_port]\\" size=\\"4\\" /></td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"smtpauth\\">Authentication:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><select name=\\"smtpauth\\" $authdisabled onChange=\\"changeAuth(this.options[this.selectedIndex].value, this.form);\\">
			<option value=\\"none\\" $authsel[none]>No authentication required</option>
			<option value=\\"same\\" $authsel[same]>Use same login values as POP3</option>
			<option value=\\"diff\\" $authsel[diff]>Enter different login information</option>
		</select></td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"smtpusername\\">Username:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><input type=\\"text\\" $smtplogindisabled id=\\"smtpusername\\" class=\\"bginput\\" name=\\"newpop[smtp_username]\\" value=\\"$newpop[smtp_username]\\" size=\\"20\\" /></td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"smtppassword\\">Password:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><input type=\\"password\\" $smtplogindisabled autocomplete=\\"off\\" id=\\"smtppassword\\" class=\\"bginput\\" name=\\"newpop[smtp_password]\\" value=\\"$newpop[smtp_password]\\" size=\\"20\\" /></td>
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
    'upgraded' => '0',
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
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="accountname">Account name:</label></span></td>
		<td class="{classname}RightCell" align="left"><input type="text" id="accountname" class="bginput" name="newpop[accountname]" value="$newpop[accountname]" size="20" /></td>
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
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"accountname\\">Account name:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"accountname\\" class=\\"bginput\\" name=\\"newpop[accountname]\\" value=\\"$newpop[accountname]\\" size=\\"20\\" /></td>
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
    'upgraded' => '0',
  ),
  'pop_download' => 
  array (
    'templategroupid' => '15',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Download Messages from POP3 Account</title>
$css
<script type="text/javascript" src="misc/checkall.js"></script>
</head>
<body>
$header

<form action="pop.download.php" method="post" name="form">
<input type="hidden" name="cmd" value="process" />
<input type="hidden" name="popid" value="$popid" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerLeftCell"><span class="headerText"><span class="normalfonttablehead"><b>&nbsp;&nbsp;ID&nbsp;&nbsp;</b></span></span></th>
	<th class="headerCell" width="15" nowrap="nowrap"><span class="headerText"><img src="$skin[images]/prio_high.gif" alt="Important?" border="0" /></span></th>
	<th class="headerCell"><span class="headerText"><span class="normalfonttablehead"><b>From</b></span></span></th>
	<th class="headerCell"><span class="headerText"><span class="normalfonttablehead"><b>Message Subject</b></span></span></th>
	<th class="headerCell"><span class="headerText"><span class="normalfonttablehead"><b>Received</b></span>&nbsp;&nbsp;<a href="pop.download.php?popid=$popid&perpage=$perpage&sortorder=$newsortorder"><img src="$skin[images]/$arrow_image.gif" align="middle" alt="" border="0" /></span></th>
	<th class="headerCell"><span class="headerText"><span class="normalfonttablehead"><b>Size</b></span></span></th>
	<th class="headerRightCell"><input name="allbox" type="checkbox" value="Check All" title="Select/Deselect All" onClick="checkAll(this.form);" /></th>
</tr>
<%if empty($msgbits) %>
<tr style="highRow">
	<td class="highBothCell" align="center" colspan="10"><span class="normalfont">No messages in this account!</span></td>
</tr>
<%else%>
$msgbits
<%endif%>
</table>

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr>
	<td><span class="smallfont">Showing messages $limitlower to $limitupper of $totalmails<br />$pagenav</span></td>
	<td align="right"><span class="smallfonttablehead"><b>
	<select name="dowhat" onChange="if (this.options[this.selectedIndex].value != \'nothing\') { this.form.cmd.value = this.options[this.selectedIndex].value; if (this.form.cmd.value == \'process\' || confirm(\'Are you sure you want to delete the selected messages?\')) { this.form.submit(); } } else { this.selectedIndex = 0; }">
		<option value="nothing" selected="selected">Actions to perform...</option>
		<option value="nothing">--------------------------</option>
		<option value="process">Download selected messages</option>
		<option value="delete">Remove selected messages</option>
	</select></b></span></td>
</tr>
</table>
</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Download Messages from POP3 Account</title>
$GLOBALS[css]
<script type=\\"text/javascript\\" src=\\"misc/checkall.js\\"></script>
</head>
<body>
$GLOBALS[header]

<form action=\\"pop.download.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"process\\" />
<input type=\\"hidden\\" name=\\"popid\\" value=\\"$popid\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerLeftCell\\"><span class=\\"headerText\\"><span class=\\"normalfonttablehead\\"><b>&nbsp;&nbsp;ID&nbsp;&nbsp;</b></span></span></th>
	<th class=\\"headerCell\\" width=\\"15\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><img src=\\"{$GLOBALS[skin][images]}/prio_high.gif\\" alt=\\"Important?\\" border=\\"0\\" /></span></th>
	<th class=\\"headerCell\\"><span class=\\"headerText\\"><span class=\\"normalfonttablehead\\"><b>From</b></span></span></th>
	<th class=\\"headerCell\\"><span class=\\"headerText\\"><span class=\\"normalfonttablehead\\"><b>Message Subject</b></span></span></th>
	<th class=\\"headerCell\\"><span class=\\"headerText\\"><span class=\\"normalfonttablehead\\"><b>Received</b></span>&nbsp;&nbsp;<a href=\\"pop.download.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}popid=$popid&perpage=$perpage&sortorder=$newsortorder\\"><img src=\\"{$GLOBALS[skin][images]}/$arrow_image.gif\\" align=\\"middle\\" alt=\\"\\" border=\\"0\\" /></span></th>
	<th class=\\"headerCell\\"><span class=\\"headerText\\"><span class=\\"normalfonttablehead\\"><b>Size</b></span></span></th>
	<th class=\\"headerRightCell\\"><input name=\\"allbox\\" type=\\"checkbox\\" value=\\"Check All\\" title=\\"Select/Deselect All\\" onClick=\\"checkAll(this.form);\\" /></th>
</tr>
".((empty($msgbits) ) ? ("
<tr style=\\"highRow\\">
	<td class=\\"highBothCell\\" align=\\"center\\" colspan=\\"10\\"><span class=\\"normalfont\\">No messages in this account!</span></td>
</tr>
") : ("
$msgbits
"))."
</table>

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr>
	<td><span class=\\"smallfont\\">Showing messages $limitlower to $limitupper of $totalmails<br />$pagenav</span></td>
	<td align=\\"right\\"><span class=\\"smallfonttablehead\\"><b>
	<select name=\\"dowhat\\" onChange=\\"if (this.options[this.selectedIndex].value != \'nothing\') { this.form.cmd.value = this.options[this.selectedIndex].value; if (this.form.cmd.value == \'process\' || confirm(\'Are you sure you want to delete the selected messages?\')) { this.form.submit(); } } else { this.selectedIndex = 0; }\\">
		<option value=\\"nothing\\" selected=\\"selected\\">Actions to perform...</option>
		<option value=\\"nothing\\">--------------------------</option>
		<option value=\\"process\\">Download selected messages</option>
		<option value=\\"delete\\">Remove selected messages</option>
	</select></b></span></td>
</tr>
</table>
</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'pop_download_msgbit' => 
  array (
    'templategroupid' => '15',
    'user_data' => '<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" nowrap="nowrap" align="center"><span class="normalfont">$i</span></td>
	<td class="{classname}Cell" nowrap="nowrap">$mail[priority]</td>
	<td class="{classname}Cell" width="25%" align="left"><span class="normalfont"><%if $hiveuser[senderlink] %><a href="compose.email.php?email=$mail[link]">$mail[fromname]</a><%else%>$mail[fromname]<%endif%></span></td>
	<td class="{classname}Cell" align="left" width="55%"><span class="normalfont"><a href="read.email.php?popid=$popid&msgid=$i" target="_blank">$mail[subject]</a></span></td>
	<td class="{classname}Cell" nowrap="nowrap" width="20%" align="center"><span class="smallfont">$mail[date] <span class="timecolor">$mail[time]</span></span></td>
	<td class="{classname}Cell" width="20%" align="center"><span class="smallfont">$mail[kbsize]KB</span></td>
	<td class="{classname}RightCell" align="center"><input type="checkbox" name="selections[$i]" value="yes" onClick="checkMain(this.form);" /></td>
</tr>
',
    'parsed_data' => '"<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" nowrap=\\"nowrap\\" align=\\"center\\"><span class=\\"normalfont\\">$i</span></td>
	<td class=\\"{classname}Cell\\" nowrap=\\"nowrap\\">$mail[priority]</td>
	<td class=\\"{classname}Cell\\" width=\\"25%\\" align=\\"left\\"><span class=\\"normalfont\\">".(($hiveuser[senderlink] ) ? ("<a href=\\"compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}email=$mail[link]\\">$mail[fromname]</a>") : ("$mail[fromname]"))."</span></td>
	<td class=\\"{classname}Cell\\" align=\\"left\\" width=\\"55%\\"><span class=\\"normalfont\\"><a href=\\"read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}popid=$popid&msgid=$i\\" target=\\"_blank\\">$mail[subject]</a></span></td>
	<td class=\\"{classname}Cell\\" nowrap=\\"nowrap\\" width=\\"20%\\" align=\\"center\\"><span class=\\"smallfont\\">$mail[date] <span class=\\"timecolor\\">$mail[time]</span></span></td>
	<td class=\\"{classname}Cell\\" width=\\"20%\\" align=\\"center\\"><span class=\\"smallfont\\">$mail[kbsize]KB</span></td>
	<td class=\\"{classname}RightCell\\" align=\\"center\\"><input type=\\"checkbox\\" name=\\"selections[$i]\\" value=\\"yes\\" onClick=\\"checkMain(this.form);\\" /></td>
</tr>
"',
    'upgraded' => '0',
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
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="accountname">Account name:</label></span></td>
		<td class="{classname}RightCell" align="left"><input type="text" id="accountname" class="bginput" name="newpop[accountname]" value="$pop[accountname]" size="20" /></td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="displayname">Your name:</label></span></td>
		<td class="{classname}RightCell" align="left"><input type="text" id="displayname" class="bginput" name="newpop[displayname]" value="$pop[displayname]" size="20" /></td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="displayemail">Email address:</label></span></td>
		<td class="{classname}RightCell" align="left"><input type="text" id="displayemail" class="bginput" name="newpop[displayemail]" value="$pop[displayemail]" size="20" /></td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="displayname">Reply-to address:</label></span></td>
		<td class="{classname}RightCell" align="left"><input type="text" id="replyto" class="bginput" name="newpop[replyto]" value="$pop[replyto]" size="20" /></td>
	</tr>
	<tr>
		<td colspan="2"><span class="smallfonttablehead">&nbsp;</span></td>
	</tr>
	<tr class="headerRow">
		<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Incoming Messages (POP3)</b></span> <span class="smallfonttablehead"><b>- Required</b></span></th>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="popserver">Server</label> and <label for="popport">Port</label>:</span></td>
		<td class="{classname}RightCell" align="left"><input type="text" id="popserver" class="bginput" name="newpop[server]" value="$pop[server]" size="20" />&nbsp;<input type="text" id="popport" class="bginput" name="newpop[port]" value="$pop[port]" size="4" /></td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="popusername">Username:</label></span></td>
		<td class="{classname}RightCell" align="left"><input type="text" id="popusername" class="bginput" name="newpop[username]" value="$pop[username]" size="20" /></td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="poppassword">Password:</label></span></td>
		<td class="{classname}RightCell" align="left"><input type="password" autocomplete="off" id="poppassword" class="bginput" name="newpop[password]" value="$pop[password]" size="20" /></td>
	</tr>
	<%if getop(\'pop3_useimap\') %>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="usessl">Use secure connection (SSL):</label></span></td>
		<td class="{classname}RightCell" align="left"><span class="normalfont"><input type="checkbox" name="newpop[usessl]" value="1" id="usessl" $usesslchecked /> <label for="autopoll">Yes</label></span></td>
	</tr>
	<%endif%>
</table>

<br />
<div align="center">
<input type="submit" class="bginput" value="Save Changes" />&nbsp;
<input type="reset" class="bginput" value="Reset Options" />&nbsp;
<input type="button" class="bginput" value="Close Window" onClick="window.close();" />
</div>
<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
	<tr class="headerRow">
		<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Outgoing Messages (SMTP)</b></span> <span class="smallfonttablehead"><b>- Optional</b></span></th>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="smtpserver">Server</label> and <label for="smtpport">Port</label>:</span></td>
		<td class="{classname}RightCell" align="left"><input type="text" id="smtpserver" class="bginput" name="newpop[smtp_server]" value="$pop[smtp_server]" size="20" onKeyUp="this.form.smtpauth.disabled = (this.value == \'\');" />&nbsp;<input type="text" id="smtpport" class="bginput" name="newpop[smtp_port]" value="$pop[smtp_port]" size="4" /></td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="smtpauth">Authentication:</label></span></td>
		<td class="{classname}RightCell" align="left"><select name="smtpauth" $authdisabled onChange="changeAuth(this.options[this.selectedIndex].value, this.form);">
			<option value="none" $authsel[none]>No authentication required</option>
			<option value="same" $authsel[same]>Use same login values as above</option>
			<option value="diff" $authsel[diff]>Enter different login information</option>
		</select></td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="smtpusername">Username:</label></span></td>
		<td class="{classname}RightCell" align="left"><input type="text" $smtplogindisabled id="smtpusername" class="bginput" name="newpop[smtp_username]" value="$pop[smtp_username]" size="20" /></td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="smtppassword">Password:</label></span></td>
		<td class="{classname}RightCell" align="left"><input type="password" autocomplete="off" $smtplogindisabled id="smtppassword" class="bginput" name="newpop[smtp_password]" value="$pop[smtp_password]" size="20" /></td>
	</tr>
	<tr>
		<td colspan="2"><span class="smallfonttablehead">&nbsp;</span></td>
	</tr>
	<tr class="headerRow">
		<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Account Options</b></span></th>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="autopoll">Automatically download messages:</label></span></td>
		<td class="{classname}RightCell" align="left"><span class="normalfont"><input type="checkbox" name="newpop[autopoll]" value="1" id="autopoll" $autopollchecked /> <label for="autopoll">Yes</label></span></td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="delete">Leave messages on server:</label></span></td>
		<td class="{classname}RightCell" align="left"><select id="delete" name="newpop[delete]">
			<option value="1" $delsel[1]>Delete immediately</option>
			<option value="2" $delsel[2]>Synchronize with local mailbox</option>
			<option value="0" $delsel[0]>Never delete messages</option>
		</select></td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="folderid">Place incoming messages in folder:</label></span></td>
		<td class="{classname}RightCell" align="left"><select id="folderid" name="newpop[folderid]" style="width: 150px;">
			$folderbits
		</select></td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" align="left"><span class="normalfont"><label for="color">Highlight messages with color:</label></span></td>
		<td class="{classname}RightCell" align="left"><select id="color" name="newpop[color]" style="width: 150px;">
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
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"accountname\\">Account name:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"accountname\\" class=\\"bginput\\" name=\\"newpop[accountname]\\" value=\\"$pop[accountname]\\" size=\\"20\\" /></td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"displayname\\">Your name:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"displayname\\" class=\\"bginput\\" name=\\"newpop[displayname]\\" value=\\"$pop[displayname]\\" size=\\"20\\" /></td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"displayemail\\">Email address:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"displayemail\\" class=\\"bginput\\" name=\\"newpop[displayemail]\\" value=\\"$pop[displayemail]\\" size=\\"20\\" /></td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"displayname\\">Reply-to address:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"replyto\\" class=\\"bginput\\" name=\\"newpop[replyto]\\" value=\\"$pop[replyto]\\" size=\\"20\\" /></td>
	</tr>
	<tr>
		<td colspan=\\"2\\"><span class=\\"smallfonttablehead\\">&nbsp;</span></td>
	</tr>
	<tr class=\\"headerRow\\">
		<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Incoming Messages (POP3)</b></span> <span class=\\"smallfonttablehead\\"><b>- Required</b></span></th>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"popserver\\">Server</label> and <label for=\\"popport\\">Port</label>:</span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"popserver\\" class=\\"bginput\\" name=\\"newpop[server]\\" value=\\"$pop[server]\\" size=\\"20\\" />&nbsp;<input type=\\"text\\" id=\\"popport\\" class=\\"bginput\\" name=\\"newpop[port]\\" value=\\"$pop[port]\\" size=\\"4\\" /></td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"popusername\\">Username:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"popusername\\" class=\\"bginput\\" name=\\"newpop[username]\\" value=\\"$pop[username]\\" size=\\"20\\" /></td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"poppassword\\">Password:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><input type=\\"password\\" autocomplete=\\"off\\" id=\\"poppassword\\" class=\\"bginput\\" name=\\"newpop[password]\\" value=\\"$pop[password]\\" size=\\"20\\" /></td>
	</tr>
	".((getop(\'pop3_useimap\') ) ? ("
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"usessl\\">Use secure connection (SSL):</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><span class=\\"normalfont\\"><input type=\\"checkbox\\" name=\\"newpop[usessl]\\" value=\\"1\\" id=\\"usessl\\" $usesslchecked /> <label for=\\"autopoll\\">Yes</label></span></td>
	</tr>
	") : (\'\'))."
</table>

<br />
<div align=\\"center\\">
<input type=\\"submit\\" class=\\"bginput\\" value=\\"Save Changes\\" />&nbsp;
<input type=\\"reset\\" class=\\"bginput\\" value=\\"Reset Options\\" />&nbsp;
<input type=\\"button\\" class=\\"bginput\\" value=\\"Close Window\\" onClick=\\"window.close();\\" />
</div>
<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
	<tr class=\\"headerRow\\">
		<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Outgoing Messages (SMTP)</b></span> <span class=\\"smallfonttablehead\\"><b>- Optional</b></span></th>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"smtpserver\\">Server</label> and <label for=\\"smtpport\\">Port</label>:</span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><input type=\\"text\\" id=\\"smtpserver\\" class=\\"bginput\\" name=\\"newpop[smtp_server]\\" value=\\"$pop[smtp_server]\\" size=\\"20\\" onKeyUp=\\"this.form.smtpauth.disabled = (this.value == \'\');\\" />&nbsp;<input type=\\"text\\" id=\\"smtpport\\" class=\\"bginput\\" name=\\"newpop[smtp_port]\\" value=\\"$pop[smtp_port]\\" size=\\"4\\" /></td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"smtpauth\\">Authentication:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><select name=\\"smtpauth\\" $authdisabled onChange=\\"changeAuth(this.options[this.selectedIndex].value, this.form);\\">
			<option value=\\"none\\" $authsel[none]>No authentication required</option>
			<option value=\\"same\\" $authsel[same]>Use same login values as above</option>
			<option value=\\"diff\\" $authsel[diff]>Enter different login information</option>
		</select></td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"smtpusername\\">Username:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><input type=\\"text\\" $smtplogindisabled id=\\"smtpusername\\" class=\\"bginput\\" name=\\"newpop[smtp_username]\\" value=\\"$pop[smtp_username]\\" size=\\"20\\" /></td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"smtppassword\\">Password:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><input type=\\"password\\" autocomplete=\\"off\\" $smtplogindisabled id=\\"smtppassword\\" class=\\"bginput\\" name=\\"newpop[smtp_password]\\" value=\\"$pop[smtp_password]\\" size=\\"20\\" /></td>
	</tr>
	<tr>
		<td colspan=\\"2\\"><span class=\\"smallfonttablehead\\">&nbsp;</span></td>
	</tr>
	<tr class=\\"headerRow\\">
		<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Account Options</b></span></th>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"autopoll\\">Automatically download messages:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><span class=\\"normalfont\\"><input type=\\"checkbox\\" name=\\"newpop[autopoll]\\" value=\\"1\\" id=\\"autopoll\\" $autopollchecked /> <label for=\\"autopoll\\">Yes</label></span></td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"delete\\">Leave messages on server:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><select id=\\"delete\\" name=\\"newpop[delete]\\">
			<option value=\\"1\\" $delsel[1]>Delete immediately</option>
			<option value=\\"2\\" $delsel[2]>Synchronize with local mailbox</option>
			<option value=\\"0\\" $delsel[0]>Never delete messages</option>
		</select></td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"folderid\\">Place incoming messages in folder:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><select id=\\"folderid\\" name=\\"newpop[folderid]\\" style=\\"width: 150px;\\">
			$folderbits
		</select></td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" align=\\"left\\"><span class=\\"normalfont\\"><label for=\\"color\\">Highlight messages with color:</label></span></td>
		<td class=\\"{classname}RightCell\\" align=\\"left\\"><select id=\\"color\\" name=\\"newpop[color]\\" style=\\"width: 150px;\\">
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
    'upgraded' => '0',
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
			shiftKey = (e.modifiers - 0 > 3); 
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
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>$mail[subject]</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>From:</b></span></td>
	<td class="{classname}RightCell" align="left" width="90%" valign="middle"><span class="normalfont" style="vertical-align: middle;">$mail[fromname] (<a href="compose.email.php?email=$mail[fromemailenc]">$mail[fromemail]</a>)</span>&nbsp;<a href="addressbook.add.php?cmd=quick&messageid=$messageid"><img src="$skin[images]/addbook.gif" alt="Add sender to address book" align="middle" border="0" /></a>&nbsp;&nbsp;<span class="smallfont"><%if $hiveuser[cansearch] %><a href="search.results.php?folderids[]=0&searchdate=-1&fields[]=email&query=$mail[fromemailenc]"><img src="$skin[images]/find.gif" alt="Find more messages from sender" align="middle" border="0" /></a><%endif%><%if $hiveuser[canrule] %> <a href="rules.block.php?email=$mail[fromemailenc]"><img src="$skin[images]/block.gif" alt="Block sender" align="middle" border="0" /></a><%endif%></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>To:</b></span></td>
	<td class="{classname}RightCell" align="left" width="90%"><span class="normalfont">$tolist</span></td>
</tr>
<%if !empty($cc) %>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>CC:</b></span></td>
	<td class="{classname}RightCell" align="left" width="90%"><span class="normalfont">$cclist</span></td>
</tr>
<%endif%>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>Subject:</b></span></td>
	<td class="{classname}RightCell" align="left" width="90%"><span class="normalfont">$mail[subject]</span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>Date Sent:</b></span></td>
	<td class="{classname}RightCell" align="left" width="90%"><span class="normalfont">$mail[datetime]</span></td>
</tr>
<%if !empty($attachlist) %>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>Attachments:</b></span></td>
	<td class="{classname}RightCell" align="left" width="90%"><span class="normalfont">$attachlist</span></td>
</tr>
<%endif%>
$advheaders
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>Options:</b></span></td>
	<td class="{classname}RightCell" align="left" width="90%"><span class="normalfont"><a href="read.markas.php?messageid=$mail[messageid]&markas=$markas&back=message">mark as $markas</a> | <a href="read.source.php?messageid=$mail[messageid]">view source</a> | <a href="read.source.php?messageid=$mail[messageid]&cmd=save">save as</a> | <a href="read.printable.php?messageid=$mail[messageid]">printable version</a> | <a href="#" onClick="window.open(\'read.rename.php?messageid=$messageid\',\'renameSubject\',\'resizable=no,width=360,height=175\'); return false;">edit subject</a><%if getop(\'allowbouncing\') and !($mail[\'status\'] & MAIL_BOUNCED) %> | <a href="read.bounce.php?messageid=$mail[messageid]">bounce message</a><%endif%><%if $hiveuser[canreportspam] and !($mail[\'status\'] & MAIL_REPORTED) %> | <a href="read.report.php?messageid=$mail[messageid]">report spam</a><%endif%></span></td>
</tr>
<tr class="normalRow">
	<td class="normalBothCell" valign="top" colspan="2">
	<table width="100%" cellpadding="4" cellspacing="0" border="0">
	<tr>
		<td width="100%"><iframe id="theMessage" src="read.email.php?messageid=$mail[messageid]&show=msg&bgcolor={classname}" style="background-color: $iframebgcolor; width: 100%; height: 350px;" allowtransparency="true" frameborder="no"><span class="normalfont">$mail[message]</span></iframe></td>
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
	<td align="left" valign="top"><%if $nextoldestid or $nextnewestid %>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<%if $nextoldestid %><td align="center"><span class="smallfont"><a href="read.email.php?messageid=$nextoldestid">&laquo; Previous Message</a></span></td><%endif%>
				<%if $nextnewestid %><td align="center"><span class="smallfont">&nbsp;&nbsp;<a href="read.email.php?messageid=$nextnewestid">Next Message &raquo;</a></span></td><%endif%>
			</tr>
			<tr>
				<%if $nextoldestid %><td align="center"><span class="smallfont">(<a href="read.email.php?messageid=$nextoldestid">$nextoldestsubject</a>)</span></td><%endif%>
				<%if $nextnewestid %><td align="center"><span class="smallfont">&nbsp;&nbsp;(<a href="read.email.php?messageid=$nextnewestid">$nextnewestsubject</a>)</span></td><%endif%>
			</tr>
		</table><%endif%></td>
	<td align="right" valign="top"><span class="smallfont"><%if $folderid != -3 %><b>Note:</b> deleted messages will be moved to the Trash Can.<br />Hold down Shift key when clicking to completely delete the messages.<%else%>&nbsp;<%endif%></span></td>
</tr>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="550" align="center">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Message Notes</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}BothCell" align="center"><textarea rows="5" name="msgsnotes" style="overflow-y: visible; width: 550px">$mail[notes]</textarea><br /><input type="submit" name="upnotes" value="Update Notes" class="bginput" /></td>
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
			shiftKey = (e.modifiers - 0 > 3); 
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
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>$mail[subject]</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>From:</b></span></td>
	<td class=\\"{classname}RightCell\\" align=\\"left\\" width=\\"90%\\" valign=\\"middle\\"><span class=\\"normalfont\\" style=\\"vertical-align: middle;\\">$mail[fromname] (<a href=\\"compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}email=$mail[fromemailenc]\\">$mail[fromemail]</a>)</span>&nbsp;<a href=\\"addressbook.add.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=quick&messageid=$messageid\\"><img src=\\"{$GLOBALS[skin][images]}/addbook.gif\\" alt=\\"Add sender to address book\\" align=\\"middle\\" border=\\"0\\" /></a>&nbsp;&nbsp;<span class=\\"smallfont\\">".(($hiveuser[cansearch] ) ? ("<a href=\\"search.results.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderids[]=0&searchdate=-1&fields[]=email&query=$mail[fromemailenc]\\"><img src=\\"{$GLOBALS[skin][images]}/find.gif\\" alt=\\"Find more messages from sender\\" align=\\"middle\\" border=\\"0\\" /></a>") : (\'\')).(($hiveuser[canrule] ) ? (" <a href=\\"rules.block.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}email=$mail[fromemailenc]\\"><img src=\\"{$GLOBALS[skin][images]}/block.gif\\" alt=\\"Block sender\\" align=\\"middle\\" border=\\"0\\" /></a>") : (\'\'))."</span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>To:</b></span></td>
	<td class=\\"{classname}RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\">$tolist</span></td>
</tr>
".((!empty($cc) ) ? ("
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>CC:</b></span></td>
	<td class=\\"{classname}RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\">$cclist</span></td>
</tr>
") : (\'\'))."
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>Subject:</b></span></td>
	<td class=\\"{classname}RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\">$mail[subject]</span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>Date Sent:</b></span></td>
	<td class=\\"{classname}RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\">$mail[datetime]</span></td>
</tr>
".((!empty($attachlist) ) ? ("
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>Attachments:</b></span></td>
	<td class=\\"{classname}RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\">$attachlist</span></td>
</tr>
") : (\'\'))."
$advheaders
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>Options:</b></span></td>
	<td class=\\"{classname}RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\"><a href=\\"read.markas.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]&markas=$markas&back=message\\">mark as $markas</a> | <a href=\\"read.source.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\\">view source</a> | <a href=\\"read.source.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]&cmd=save\\">save as</a> | <a href=\\"read.printable.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\\">printable version</a> | <a href=\\"#\\" onClick=\\"window.open(\'read.rename.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$messageid\',\'renameSubject\',\'resizable=no,width=360,height=175\'); return false;\\">edit subject</a>".((getop(\'allowbouncing\') and !($mail[\'status\'] & MAIL_BOUNCED) ) ? (" | <a href=\\"read.bounce.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\\">bounce message</a>") : (\'\')).(($hiveuser[canreportspam] and !($mail[\'status\'] & MAIL_REPORTED) ) ? (" | <a href=\\"read.report.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]\\">report spam</a>") : (\'\'))."</span></td>
</tr>
<tr class=\\"normalRow\\">
	<td class=\\"normalBothCell\\" valign=\\"top\\" colspan=\\"2\\">
	<table width=\\"100%\\" cellpadding=\\"4\\" cellspacing=\\"0\\" border=\\"0\\">
	<tr>
		<td width=\\"100%\\"><iframe id=\\"theMessage\\" src=\\"read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$mail[messageid]&show=msg&bgcolor={classname}\\" style=\\"background-color: $iframebgcolor; width: 100%; height: 350px;\\" allowtransparency=\\"true\\" frameborder=\\"no\\"><span class=\\"normalfont\\">$mail[message]</span></iframe></td>
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
	<td align=\\"left\\" valign=\\"top\\">".(($nextoldestid or $nextnewestid ) ? ("
		<table cellpadding=\\"0\\" cellspacing=\\"0\\">
			<tr>
				".(($nextoldestid ) ? ("<td align=\\"center\\"><span class=\\"smallfont\\"><a href=\\"read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$nextoldestid\\">&laquo; Previous Message</a></span></td>") : (\'\'))."
				".(($nextnewestid ) ? ("<td align=\\"center\\"><span class=\\"smallfont\\">&nbsp;&nbsp;<a href=\\"read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$nextnewestid\\">Next Message &raquo;</a></span></td>") : (\'\'))."
			</tr>
			<tr>
				".(($nextoldestid ) ? ("<td align=\\"center\\"><span class=\\"smallfont\\">(<a href=\\"read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$nextoldestid\\">$nextoldestsubject</a>)</span></td>") : (\'\'))."
				".(($nextnewestid ) ? ("<td align=\\"center\\"><span class=\\"smallfont\\">&nbsp;&nbsp;(<a href=\\"read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$nextnewestid\\">$nextnewestsubject</a>)</span></td>") : (\'\'))."
			</tr>
		</table>") : (\'\'))."</td>
	<td align=\\"right\\" valign=\\"top\\"><span class=\\"smallfont\\">".(($folderid != -3 ) ? ("<b>Note:</b> deleted messages will be moved to the Trash Can.<br />Hold down Shift key when clicking to completely delete the messages.") : ("&nbsp;"))."</span></td>
</tr>
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"550\\" align=\\"center\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Message Notes</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\" align=\\"center\\"><textarea rows=\\"5\\" name=\\"msgsnotes\\" style=\\"overflow-y: visible; width: 550px\\">$mail[notes]</textarea><br /><input type=\\"submit\\" name=\\"upnotes\\" value=\\"Update Notes\\" class=\\"bginput\\" /></td>
</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'read_attachments_bit' => 
  array (
    'templategroupid' => '6',
    'user_data' => '<a href="read.attachment.php?messageid=$messageid&attachnum=$attachnum" <%if $hiveuser[\'attachwin\']%>target="_blank"<%endif%>>$filename</a> ({$filesize}KB)<br />
',
    'parsed_data' => '"<a href=\\"read.attachment.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}messageid=$messageid&attachnum=$attachnum\\" ".(($hiveuser[\'attachwin\']) ? ("target=\\"_blank\\"") : (\'\')).">$filename</a> ({$filesize}KB)<br />
"',
    'upgraded' => '0',
  ),
  'read_header' => 
  array (
    'templategroupid' => '6',
    'user_data' => '<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" valign="top" align="left" width="10%" nowrap="nowrap"><span class="normalfont"><b>${headername}:</b></span></td>
	<td class="{classname}RightCell" align="left" width="90%"><span class="normalfont">${headerinfo}</span></td>
</tr>',
    'parsed_data' => '"<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\"><b>${headername}:</b></span></td>
	<td class=\\"{classname}RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\">${headerinfo}</span></td>
</tr>"',
    'upgraded' => '0',
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
    'upgraded' => '0',
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
    'upgraded' => '0',
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
    'upgraded' => '0',
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
    'upgraded' => '0',
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
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>From:</b></span></td>
	<td class="{classname}RightCell" align="left" width="90%" valign="middle"><span class="normalfont" style="vertical-align: middle;">$mail[fromname] (<a href="compose.email.php?email=$mail[fromemailenc]">$mail[fromemail]</a>)</span>&nbsp;<a href="addressbook.add.php?cmd=quick&popid=$popid&msgid=$msgid"><img src="$skin[images]/addbook.gif" alt="Add sender to address book" align="middle" border="0" /></a>&nbsp;&nbsp;<span class="smallfont"><%if $hiveuser[cansearch] %><a href="search.results.php?folderids[]=0&searchdate=-1&fields[]=email&query=$mail[fromemailenc]"><img src="$skin[images]/find.gif" alt="Find more messages from sender" align="middle" border="0" /></a><%endif%><%if $hiveuser[canrule] %> <a href="rules.block.php?email=$mail[fromemailenc]"><img src="$skin[images]/block.gif" alt="Block sender" align="middle" border="0" /></a><%endif%></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>To:</b></span></td>
	<td class="{classname}RightCell" align="left" width="90%"><span class="normalfont">$tolist</span></td>
</tr>
$cc
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>Subject:</b></span></td>
	<td class="{classname}RightCell" align="left" width="90%"><span class="normalfont">$mail[subject]</span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>Date Sent:</b></span></td>
	<td class="{classname}RightCell" align="left" width="90%"><span class="normalfont">$mail[datetime]</span></td>
</tr>
$attachments
$advheaders
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" valign="top" align="left" width="10%"><span class="normalfont"><b>Options:</b></span></td>
	<td class="{classname}RightCell" align="left" width="90%"><span class="normalfont"><a href="read.source.php?popid=$popid&msgid=$msgid">view source</a> | <a href="read.source.php?popid=$popid&msgid=$msgid&cmd=save">save as</a> | <a href="read.printable.php?popid=$popid&msgid=$msgid">printable version</a><%if getop(\'allowbouncing\') and !($mail[\'status\'] & MAIL_BOUNCED) %> | <a href="read.bounce.php?popid=$popid&msgid=$msgid">bounce message</a><%endif%></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}BothCell" valign="top" colspan="2">
	<table width="100%" cellpadding="4" cellspacing="0" border="0">
	<tr>
		<td width="100%"><iframe id="theMessage" src="read.email.php?popid=$popid&msgid=$msgid&show=msg&bgcolor={classname}" style="background-color: $iframebgcolor; width: 100%; height: 350px;" allowtransparency="true" frameborder="no"><span class="normalfont">$mail[message]</span></iframe></td>
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
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>From:</b></span></td>
	<td class=\\"{classname}RightCell\\" align=\\"left\\" width=\\"90%\\" valign=\\"middle\\"><span class=\\"normalfont\\" style=\\"vertical-align: middle;\\">$mail[fromname] (<a href=\\"compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}email=$mail[fromemailenc]\\">$mail[fromemail]</a>)</span>&nbsp;<a href=\\"addressbook.add.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=quick&popid=$popid&msgid=$msgid\\"><img src=\\"{$GLOBALS[skin][images]}/addbook.gif\\" alt=\\"Add sender to address book\\" align=\\"middle\\" border=\\"0\\" /></a>&nbsp;&nbsp;<span class=\\"smallfont\\">".(($hiveuser[cansearch] ) ? ("<a href=\\"search.results.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}folderids[]=0&searchdate=-1&fields[]=email&query=$mail[fromemailenc]\\"><img src=\\"{$GLOBALS[skin][images]}/find.gif\\" alt=\\"Find more messages from sender\\" align=\\"middle\\" border=\\"0\\" /></a>") : (\'\')).(($hiveuser[canrule] ) ? (" <a href=\\"rules.block.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}email=$mail[fromemailenc]\\"><img src=\\"{$GLOBALS[skin][images]}/block.gif\\" alt=\\"Block sender\\" align=\\"middle\\" border=\\"0\\" /></a>") : (\'\'))."</span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>To:</b></span></td>
	<td class=\\"{classname}RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\">$tolist</span></td>
</tr>
$cc
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>Subject:</b></span></td>
	<td class=\\"{classname}RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\">$mail[subject]</span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>Date Sent:</b></span></td>
	<td class=\\"{classname}RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\">$mail[datetime]</span></td>
</tr>
$attachments
$advheaders
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" valign=\\"top\\" align=\\"left\\" width=\\"10%\\"><span class=\\"normalfont\\"><b>Options:</b></span></td>
	<td class=\\"{classname}RightCell\\" align=\\"left\\" width=\\"90%\\"><span class=\\"normalfont\\"><a href=\\"read.source.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}popid=$popid&msgid=$msgid\\">view source</a> | <a href=\\"read.source.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}popid=$popid&msgid=$msgid&cmd=save\\">save as</a> | <a href=\\"read.printable.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}popid=$popid&msgid=$msgid\\">printable version</a>".((getop(\'allowbouncing\') and !($mail[\'status\'] & MAIL_BOUNCED) ) ? (" | <a href=\\"read.bounce.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}popid=$popid&msgid=$msgid\\">bounce message</a>") : (\'\'))."</span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\" valign=\\"top\\" colspan=\\"2\\">
	<table width=\\"100%\\" cellpadding=\\"4\\" cellspacing=\\"0\\" border=\\"0\\">
	<tr>
		<td width=\\"100%\\"><iframe id=\\"theMessage\\" src=\\"read.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}popid=$popid&msgid=$msgid&show=msg&bgcolor={classname}\\" style=\\"background-color: $iframebgcolor; width: 100%; height: 350px;\\" allowtransparency=\\"true\\" frameborder=\\"no\\"><span class=\\"normalfont\\">$mail[message]</span></iframe></td>
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
    'upgraded' => '0',
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
h1 {
	border-bottom: thick solid black;
	font-family: $skin[fontface];
}
body {
	font-family: $skin[fontface];
	margin: 6px;
}
td, body {
	font-size: $skin[normalsize];
}
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
	<%if $mail[cc] %>
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
h1 {
	border-bottom: thick solid black;
	font-family: {$GLOBALS[skin][fontface]};
}
body {
	font-family: {$GLOBALS[skin][fontface]};
	margin: 6px;
}
td, body {
	font-size: {$GLOBALS[skin][normalsize]};
}
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
	".(($mail[cc] ) ? ("
	<tr> 
		<td height=\\"20\\"><b><font size=\\"2\\">CC:</font></b></td>
		<td height=\\"20\\"><font size=\\"2\\">$mail[cc]</font></td>
	</tr>
	") : (\'\'))."
	<tr> 
		<td height=\\"20\\"><b><font size=\\"2\\">Subject:</font></b></td>
		<td height=\\"20\\"><font size=\\"2\\">$mail[subject]</font></td>
	</tr>
</table>
<p>$mail[message]</p>
</body>
</html>"',
    'upgraded' => '0',
  ),
  'read_readreceipt' => 
  array (
    'templategroupid' => '6',
    'user_data' => 'This is a receipt for the email you sent to $receiptfrom at $timesent.

This receipt verifies that the message was displayed on the recipient\'s computer at $timeread.',
    'parsed_data' => '"This is a receipt for the email you sent to $receiptfrom at $timesent.

This receipt verifies that the message was displayed on the recipient\'s computer at $timeread."',
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'read_report' => 
  array (
    'templategroupid' => '6',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Report Spam</title>
$css
</head>
<body>
$header

<form action="read.report.php" method="post">
<input type="hidden" name="cmd" value="send" />
<input type="hidden" name="messageid" value="$messageid" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="730">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Send Spam Report</b></span></th>
</tr>
<tr class="highRow">
	<td class="highLeftCell" width="60%" valign="top" nowrap="nowrap"><span class="normalfont"><b>Reason for reporting this message as spam:</b> (<i>optional</i>)<br />
	<textarea name="reason" cols="60" rows="7"></textarea></span>
	</td>
	<td class="highRightCell" width="40%" valign="top" nowrap="nowrap"><span class="normalfont"><br /><input type="checkbox" name="blocksender" value="1" id="blockyes" /> <label for="blockyes">Block this sender</label>: <a href="compose.email.php?email=$mail[email]">$mail[email]</a>
	<br />
	<nobr><input type="checkbox" name="deletemsgs" value="1" id="deleteyes" onClick="this.form.deletetype.disabled = this.form.deletewhich.disabled = !this.checked;" /> <select name="deletetype" onChange="this.form.deleteyes.checked = true;" disabled="disabled"><option value="move">Move to {$_folders[\'-3\'][\'title\']}</option><option value="remove">Remove completely</option></select> <select name="deletewhich" onChange="this.form.deleteyes.checked = true;" disabled="disabled"><option value="onlythis">this message only</option><option value="all">all messages from sender</option></select></nobr>
	<br /><br />
	<span class="important">Please note that by sending this report, you agree<br />that a complete copy of this message will be sent<br />to the operators of this service to be examined.</span></span></td>
</tr>
</table>
<br />
<table cellpadding="4" cellspacing="0" class="normalTable" width="730">
<tr>
	<td align="center" colspan="2">
		<input type="submit" class="bginput" name="submit" value="Send Report" />
	</td>
</tr>
</table>

</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Report Spam</title>
$GLOBALS[css]
</head>
<body>
$GLOBALS[header]

<form action=\\"read.report.php{$GLOBALS[session_url]}\\" method=\\"post\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"send\\" />
<input type=\\"hidden\\" name=\\"messageid\\" value=\\"$messageid\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"730\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Send Spam Report</b></span></th>
</tr>
<tr class=\\"highRow\\">
	<td class=\\"highLeftCell\\" width=\\"60%\\" valign=\\"top\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\"><b>Reason for reporting this message as spam:</b> (<i>optional</i>)<br />
	<textarea name=\\"reason\\" cols=\\"60\\" rows=\\"7\\"></textarea></span>
	</td>
	<td class=\\"highRightCell\\" width=\\"40%\\" valign=\\"top\\" nowrap=\\"nowrap\\"><span class=\\"normalfont\\"><br /><input type=\\"checkbox\\" name=\\"blocksender\\" value=\\"1\\" id=\\"blockyes\\" /> <label for=\\"blockyes\\">Block this sender</label>: <a href=\\"compose.email.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}email=$mail[email]\\">$mail[email]</a>
	<br />
	<nobr><input type=\\"checkbox\\" name=\\"deletemsgs\\" value=\\"1\\" id=\\"deleteyes\\" onClick=\\"this.form.deletetype.disabled = this.form.deletewhich.disabled = !this.checked;\\" /> <select name=\\"deletetype\\" onChange=\\"this.form.deleteyes.checked = true;\\" disabled=\\"disabled\\"><option value=\\"move\\">Move to {$_folders[\'-3\'][\'title\']}</option><option value=\\"remove\\">Remove completely</option></select> <select name=\\"deletewhich\\" onChange=\\"this.form.deleteyes.checked = true;\\" disabled=\\"disabled\\"><option value=\\"onlythis\\">this message only</option><option value=\\"all\\">all messages from sender</option></select></nobr>
	<br /><br />
	<span class=\\"important\\">Please note that by sending this report, you agree<br />that a complete copy of this message will be sent<br />to the operators of this service to be examined.</span></span></td>
</tr>
</table>
<br />
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"730\\">
<tr>
	<td align=\\"center\\" colspan=\\"2\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Send Report\\" />
	</td>
</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
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
    'upgraded' => '0',
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
<body <%if defined(\'LOAD_MINI_TEMPLATES\') %>style="background-color: #C7E1F4;"<%endif%>>
$header

<table cellpadding="4" cellspacing="0" class="normalTable" width="<%if defined(\'LOAD_MINI_TEMPLATES\') %>100%<%else%>650<%endif%>" align="center" style="height: 100px;">
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
<body ".((defined(\'LOAD_MINI_TEMPLATES\') ) ? ("style=\\"background-color: #C7E1F4;\\"") : (\'\')).">
$GLOBALS[header]

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"".((defined(\'LOAD_MINI_TEMPLATES\') ) ? ("100%") : ("650"))."\\" align=\\"center\\" style=\\"height: 100px;\\">
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
    'upgraded' => '0',
  ),
  'redirect_addbook_addentries' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The contact has been added. You will now be taken back to your address book.',
    'parsed_data' => '"The contact has been added. You will now be taken back to your address book."',
    'upgraded' => '0',
  ),
  'redirect_addbook_copyentries' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The contact(s) have been placed in the $group[tite] group. You will now be taken back to your address book.',
    'parsed_data' => '"The contact(s) have been placed in the $group[tite] group. You will now be taken back to your address book."',
    'upgraded' => '0',
  ),
  'redirect_addbook_deleteentries' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The selected contact(s) have been deleted. You will now be taken back to your address book.',
    'parsed_data' => '"The selected contact(s) have been deleted. You will now be taken back to your address book."',
    'upgraded' => '0',
  ),
  'redirect_addbook_editentries' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The contact(s) have been updated. You will now be taken back to your address book.',
    'parsed_data' => '"The contact(s) have been updated. You will now be taken back to your address book."',
    'upgraded' => '0',
  ),
  'redirect_addbook_groupadded' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'A new contact group, "$title", has been created.',
    'parsed_data' => '"A new contact group, \\"$title\\", has been created."',
    'upgraded' => '0',
  ),
  'redirect_addbook_groupdeleted' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The selected groups have been removed.',
    'parsed_data' => '"The selected groups have been removed."',
    'upgraded' => '0',
  ),
  'redirect_addbook_quickadd' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The sender(s) has been added to your address book. You will now be returned to the message.',
    'parsed_data' => '"The sender(s) has been added to your address book. You will now be returned to the message."',
    'upgraded' => '0',
  ),
  'redirect_blocked' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The $block was successfully blocked.',
    'parsed_data' => '"The $block was successfully blocked."',
    'upgraded' => '0',
  ),
  'redirect_blockedupdated' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Your list of blocked addresses has been updated.',
    'parsed_data' => '"Your list of blocked addresses has been updated."',
    'upgraded' => '0',
  ),
  'redirect_draftdeleted' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The draft was successfully deleted.',
    'parsed_data' => '"The draft was successfully deleted."',
    'upgraded' => '0',
  ),
  'redirect_draftsaved' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The message was saved as draft and you will be able to complete it later.<br />
Please note that any attachments associated with the message were removed, and you will need to re-attach them when you send the message.',
    'parsed_data' => '"The message was saved as draft and you will be able to complete it later.<br />
Please note that any attachments associated with the message were removed, and you will need to re-attach them when you send the message."',
    'upgraded' => '0',
  ),
  'redirect_eventdeleted' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The event was successfully deleted.',
    'parsed_data' => '"The event was successfully deleted."',
    'upgraded' => '0',
  ),
  'redirect_eventsaved' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The event information has been saved. You will now be taken back to your calendar.',
    'parsed_data' => '"The event information has been saved. You will now be taken back to your calendar."',
    'upgraded' => '0',
  ),
  'redirect_eventsaved2' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The event information has been saved. You will now be taken back to the event.',
    'parsed_data' => '"The event information has been saved. You will now be taken back to the event."',
    'upgraded' => '0',
  ),
  'redirect_eventsomebadusers' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'One or more of the users you added to your Shared Event Userlist has specified that they do not wish to share events with other users. The following user(s) were not added to your Shared Event Userlist. (All other users were, however, added, and your event was saved.)<br /><br />
<ul>
$userlist
</ul>
<a href="calendar.event.php?eventid=$eventid">Click here</a> to continue.',
    'parsed_data' => '"One or more of the users you added to your Shared Event Userlist has specified that they do not wish to share events with other users. The following user(s) were not added to your Shared Event Userlist. (All other users were, however, added, and your event was saved.)<br /><br />
<ul>
$userlist
</ul>
<a href=\\"calendar.event.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}eventid=$eventid\\">Click here</a> to continue."',
    'upgraded' => '0',
  ),
  'redirect_eventsomebadusersbit' => 
  array (
    'templategroupid' => '8',
    'user_data' => '<li>$alias$domain</li>',
    'parsed_data' => '"<li>$alias$domain</li>"',
    'upgraded' => '0',
  ),
  'redirect_foladded' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The folders were added to your folder list!',
    'parsed_data' => '"The folders were added to your folder list!"',
    'upgraded' => '0',
  ),
  'redirect_foldeleted' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The messages from selected folders were successfully deleted and the folders were removed.',
    'parsed_data' => '"The messages from selected folders were successfully deleted and the folders were removed."',
    'upgraded' => '0',
  ),
  'redirect_folemptied' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The selected folders were successfully emptied.',
    'parsed_data' => '"The selected folders were successfully emptied."',
    'upgraded' => '0',
  ),
  'redirect_folmoved' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The messages from selected folders were successfully moved to the $newfolder[title] folder.',
    'parsed_data' => '"The messages from selected folders were successfully moved to the $newfolder[title] folder."',
    'upgraded' => '0',
  ),
  'redirect_folrearrange' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The folders have been rearranged.',
    'parsed_data' => '"The folders have been rearranged."',
    'upgraded' => '0',
  ),
  'redirect_folrenamed' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The folder has been renamed to $name.',
    'parsed_data' => '"The folder has been renamed to $name."',
    'upgraded' => '0',
  ),
  'redirect_loggedin' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Thank you for logging in, $username.',
    'parsed_data' => '"Thank you for logging in, $username."',
    'upgraded' => '0',
  ),
  'redirect_lostpw_sent' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Your new password has been sent to the secondary email address on file for you.',
    'parsed_data' => '"Your new password has been sent to the secondary email address on file for you."',
    'upgraded' => '0',
  ),
  'redirect_lostpw_updated' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Your password has been updated! You will now be able to log in with the new password you\'ve chosen.',
    'parsed_data' => '"Your password has been updated! You will now be able to log in with the new password you\'ve chosen."',
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'redirect_markedas' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The message$es been marked as $markas.',
    'parsed_data' => '"The message$es been marked as $markas."',
    'upgraded' => '0',
  ),
  'redirect_messagebounced' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The message was succesfully bounced to its sender, $mail[email].',
    'parsed_data' => '"The message was succesfully bounced to its sender, $mail[email]."',
    'upgraded' => '0',
  ),
  'redirect_msgblocked' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The selected messages have been blocked.',
    'parsed_data' => '"The selected messages have been blocked."',
    'upgraded' => '0',
  ),
  'redirect_msgdeleted' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Selected message(s) were successfully deleted.',
    'parsed_data' => '"Selected message(s) were successfully deleted."',
    'upgraded' => '0',
  ),
  'redirect_msgmoved' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Selected message(s) were successfully moved to $newfolder[title] folder.',
    'parsed_data' => '"Selected message(s) were successfully moved to $newfolder[title] folder."',
    'upgraded' => '0',
  ),
  'redirect_msgnotes' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The message notes have been updated.',
    'parsed_data' => '"The message notes have been updated."',
    'upgraded' => '0',
  ),
  'redirect_popadded' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The POP account has been added.',
    'parsed_data' => '"The POP account has been added."',
    'upgraded' => '0',
  ),
  'redirect_popdeleted' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'The POP account has been deleted.',
    'parsed_data' => '"The POP account has been deleted."',
    'upgraded' => '0',
  ),
  'redirect_popsupdated' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Your POP3 accounts settings have been updated.',
    'parsed_data' => '"Your POP3 accounts settings have been updated."',
    'upgraded' => '0',
  ),
  'redirect_pop_msgsdeleted' => 
  array (
    'templategroupid' => '8',
    'user_data' => '$msgsdeleted message(s) have been successfully deleted from the mail account.',
    'parsed_data' => '"$msgsdeleted message(s) have been successfully deleted from the mail account."',
    'upgraded' => '0',
  ),
  'redirect_pop_msgsprocessed' => 
  array (
    'templategroupid' => '8',
    'user_data' => '$msgsprocessed message(s) have been downloaded to your account and deleted from the remote mailbox.',
    'parsed_data' => '"$msgsprocessed message(s) have been downloaded to your account and deleted from the remote mailbox."',
    'upgraded' => '0',
  ),
  'redirect_reported' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Thank you for reporting this message. The operators will review your report and take appropriate action.',
    'parsed_data' => '"Thank you for reporting this message. The operators will review your report and take appropriate action."',
    'upgraded' => '0',
  ),
  'redirect_ruleapplied' => 
  array (
    'templategroupid' => '8',
    'user_data' => '<%if !$applyall %>The rule has been applied to the selected folders.<%else%>All rules have been applied to the selected folders.<%endif%>',
    'parsed_data' => '((!$applyall ) ? ("The rule has been applied to the selected folders.") : ("All rules have been applied to the selected folders."))',
    'upgraded' => '0',
  ),
  'redirect_rules' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Your message rules have been updated.',
    'parsed_data' => '"Your message rules have been updated."',
    'upgraded' => '0',
  ),
  'redirect_settings' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Your preferences have been saved!',
    'parsed_data' => '"Your preferences have been saved!"',
    'upgraded' => '0',
  ),
  'redirect_subscription_cancelled' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Your subscription has been cancelled.',
    'parsed_data' => '"Your subscription has been cancelled."',
    'upgraded' => '0',
  ),
  'redirect_subscription_payment' => 
  array (
    'templategroupid' => '8',
    'user_data' => 'Thank you for your payment, your subscription at $appname has been updated!',
    'parsed_data' => '"Thank you for your payment, your subscription at $appname has been updated!"',
    'upgraded' => '0',
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
event_addListener( window, \'load\', function() { checkMain(document.forms.rulesform, \'active\'); });
// -->
</script>
<script type="text/javascript" language="JavaScript">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
<script type="text/javascript" language="JavaScript">
<!--
function changeThisRow(form, type, ruleid) {
	conds1 = eval(\'form.condsubjects1\' + ruleid);
	condh = eval(\'form.condhows\' + ruleid);
	conde1 = eval(\'form.condextras1\' + ruleid);
	conds2 = eval(\'form.condsubjects2\' + ruleid);
	conde2 = eval(\'form.condextras2\' + ruleid);
	if (type == 1) {
		conds1.disabled = false;
		condh.disabled = false;
		conde1.disabled = false;
		conds2.disabled = true;
		conde2.disabled = true;
	} else {
		conds1.disabled = true;
		condh.disabled = true;
		conde1.disabled = true;
		conds2.disabled = false;
		conde2.disabled = false;
	}
}
// -->
</script>
</head>
<body>
$header

<form action="rules.update.php" method="post" onSubmit="extract_lists(this); return true;">
<input type="hidden" name="cmd" value="lists" />
<input type="hidden" name="blocklist" value="lists" />
<input type="hidden" name="safelist" value="lists" />

<table cellpadding="4" cellspacing="0" width="100%">
	<tr>
		<td valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="150">
				<tr class="headerRow">
					<th class="headerBothCell"><span class="normalfonttablehead"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width="100%" valign="top">
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Blocked Senders</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}BothCell" valign="top" colspan="2"><span class="normalfont"><b>Blocked senders:</b></span>
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
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Protect messages from familiar contacts:</b></span>
	<br />
	<span class="smallfont">If this is turned on, messages from people who are in your address book will never be blocked or checked against anti-spam measures.</span></td>
	<td class="{classname}RightCell" width="40%"><span class="normalfont"><input type="radio" name="protectbook" value="1" id="protectbookon" $protectbookon /> <label for="protectbookon">Yes</label><br /><input type="radio" name="protectbook" value="0" id="protectbookoff" $protectbookoff /> <label for="protectbookoff">No</label></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}BothCell" valign="top" colspan="2"><span class="normalfont"><b>Additional safe senders:</b></span>
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
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Your special spam key:</b></span>
	<br />
	<span class="smallfont">We allow you to choose a special, secret key that can be used by your friends so their message will not blocked. For example, if a friend of yours sends you a message with the word "free" in it, and you choose to block this kind of messages (by defining a rule below), tell him to include this special key anywhere in the subject line, and his message will not be filtered.<br />It is important that you do not set this key to a common word, so as to make sure it cannot be guessed or unintentionally found.</span></td>
	<td class="{classname}RightCell" width="40%"><input type="text" class="bginput" name="spampass" value="$hiveuser[spampass]" size="40" /></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Suspected spam:</b></span>
	<br />
	<span class="smallfont">Action to take if mail is suspected of being unsolicited mail.</span></td>
	<td class="{classname}RightCell" width="40%">
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

<form action="rules.update.php" method="post" name="rulesform">
<input type="hidden" name="cmd" value="update" />
<input type="hidden" name="ruleid" value="0" />
<input type="hidden" name="applyall" value="1" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow" valign="middle">
	<th class="headerLeftCell"><span class="normalfonttablehead"><b>Order</b></span></th>
	<th class="headerCell" nowrap="nowrap" colspan="2"><span class="normalfonttablehead"><b>Message Rules</b></span></th>
	<th class="headerCell" align="right"><span class="normalfonttablehead"><b>Active?</b></span></th>
	<th class="headerRightCell"><input name="allbox" type="checkbox" value="Check All" title="Select/Deselect All" onClick="checkAll(this.form, \'active\');" /></th>
</tr>
<%if empty($rulebits) %>
	<tr class="highRow">
		<td colspan="6" class="highBothCell" align="center"><span class="normalfont">You currently have no rules set up.</span></td>
	</tr>
<%else%>
	$rulebits
<%endif%>
</table>

<br />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr>
	<td align="center">
		<input type="submit" class="bginput" name="submit" value="Save Changes" $disablesavechanges />
		<input type="submit" class="bginput" name="submit" value="Apply All Rules" onClick="this.form.action = \'rules.apply.php\';" $disablesavechanges />
		<input type="reset" class="bginput" name="reset" value="Reset Fields" $disablesavechanges />
	</td>
</tr>
</table>

</form>

<br />

<form action="rules.update.php" method="post" name="newruleform">
<input type="hidden" name="cmd" value="add" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="3"><span class="normalfonttablehead"><b>Add New Rule</b></span></th>
</tr>
<tr class="highRow" valign="top">
	<td class="highLeftCell"><span class="normalfont">When:</span></td>
	<td class="highCell">
	<input type="radio" name="condtype[0]" value="1" onClick="changeThisRow(this.form,1,\'0\')" checked="checked" />
	<select name="condsubjects1[0]" id="condsubjects10">
		<option value="1">email address</option>
		<option value="2">message</option>
		<option value="3">recipient</option>
		<option value="4">subject</option>
	</select>
	<select name="condhows[0]" id="condhows0">
		<option value="2">contains</option>
		<option value="3">does not contain</option>
		<option value="1">equals</option>
		<option value="4">begins with</option>
		<option value="5">ends with</option>
	</select>
	<input type="text" class="bginput" name="condextras1[0]" id="condextras10" size="20" /><br />
	<input type="radio" name="condtype[0]" value="2" onClick="changeThisRow(this.form,2,\'0\')" />
	<select name="condsubjects2[0]" id="condsubjects20">
		<option value="51">email is from account</option>
	</select>
	<select name="condextras2[0]" id="condextras20">
		<option value="0" selected="selected">$appname</option>
		$accountbits
	</select><br />
	<input type="checkbox" name="exempt" value="yes" checked="checked" />Exempt safe senders from this rule</td>
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
	<input type="checkbox" name="dowhat[0][color]" value="1" /> highlight with
	<select name="colorstuff[0]">
		<option value="white" style="background-color: White;" selected="selected">White</option>
		<option value="aqua" style="background-color: Aqua;">Aqua</option>
		<option value="fuchsia" style="background-color: Fuchsia;">Fuchsia</option>
		<option value="blue" style="background-color: Blue;">Blue</option>
		<option value="yellow" style="background-color: Yellow;">Yellow</option>
		<option value="lime" style="background-color: Lime;">Lime</option>
		<option value="red" style="background-color: Red;">Red</option>
		<option value="silver" style="background-color: Silver;">Silver</option>
		<option value="gray" style="background-color: Gray; color: White;">Gray</option>
		<option value="teal" style="background-color: Teal; color: White;">Teal</option>
		<option value="purple" style="background-color: Purple; color: White;">Purple</option>
		<option value="navy" style="background-color: Navy; color: White;">Navy</option>
		<option value="olive" style="background-color: Olive; color: White;">Olive</option>
		<option value="green" style="background-color: Green; color: White;">Green</option>
		<option value="maroon" style="background-color: Maroon; color: White;">Maroon</option>
		<option value="black" style="background-color: Black; color: White;">Black</option>
	</select><br />
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
		</td>
	</tr>
</table>

</form>

$footer

<script type="text/javascript" language="JavaScript">
$onload
</script>
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
event_addListener( window, \'load\', function() { checkMain(document.forms.rulesform, \'active\'); });
// -->
</script>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
function changeThisRow(form, type, ruleid) {
	conds1 = eval(\'form.condsubjects1\' + ruleid);
	condh = eval(\'form.condhows\' + ruleid);
	conde1 = eval(\'form.condextras1\' + ruleid);
	conds2 = eval(\'form.condsubjects2\' + ruleid);
	conde2 = eval(\'form.condextras2\' + ruleid);
	if (type == 1) {
		conds1.disabled = false;
		condh.disabled = false;
		conde1.disabled = false;
		conds2.disabled = true;
		conde2.disabled = true;
	} else {
		conds1.disabled = true;
		condh.disabled = true;
		conde1.disabled = true;
		conds2.disabled = false;
		conde2.disabled = false;
	}
}
// -->
</script>
</head>
<body>
$GLOBALS[header]

<form action=\\"rules.update.php{$GLOBALS[session_url]}\\" method=\\"post\\" onSubmit=\\"extract_lists(this); return true;\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"lists\\" />
<input type=\\"hidden\\" name=\\"blocklist\\" value=\\"lists\\" />
<input type=\\"hidden\\" name=\\"safelist\\" value=\\"lists\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" width=\\"100%\\">
	<tr>
		<td valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"150\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width=\\"100%\\" valign=\\"top\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Blocked Senders</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\" valign=\\"top\\" colspan=\\"2\\"><span class=\\"normalfont\\"><b>Blocked senders:</b></span>
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
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Protect messages from familiar contacts:</b></span>
	<br />
	<span class=\\"smallfont\\">If this is turned on, messages from people who are in your address book will never be blocked or checked against anti-spam measures.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\"><input type=\\"radio\\" name=\\"protectbook\\" value=\\"1\\" id=\\"protectbookon\\" $protectbookon /> <label for=\\"protectbookon\\">Yes</label><br /><input type=\\"radio\\" name=\\"protectbook\\" value=\\"0\\" id=\\"protectbookoff\\" $protectbookoff /> <label for=\\"protectbookoff\\">No</label></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\" valign=\\"top\\" colspan=\\"2\\"><span class=\\"normalfont\\"><b>Additional safe senders:</b></span>
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
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Your special spam key:</b></span>
	<br />
	<span class=\\"smallfont\\">We allow you to choose a special, secret key that can be used by your friends so their message will not blocked. For example, if a friend of yours sends you a message with the word \\"free\\" in it, and you choose to block this kind of messages (by defining a rule below), tell him to include this special key anywhere in the subject line, and his message will not be filtered.<br />It is important that you do not set this key to a common word, so as to make sure it cannot be guessed or unintentionally found.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"spampass\\" value=\\"$hiveuser[spampass]\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Suspected spam:</b></span>
	<br />
	<span class=\\"smallfont\\">Action to take if mail is suspected of being unsolicited mail.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\">
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

<form action=\\"rules.update.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"rulesform\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"update\\" />
<input type=\\"hidden\\" name=\\"ruleid\\" value=\\"0\\" />
<input type=\\"hidden\\" name=\\"applyall\\" value=\\"1\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\" valign=\\"middle\\">
	<th class=\\"headerLeftCell\\"><span class=\\"normalfonttablehead\\"><b>Order</b></span></th>
	<th class=\\"headerCell\\" nowrap=\\"nowrap\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Message Rules</b></span></th>
	<th class=\\"headerCell\\" align=\\"right\\"><span class=\\"normalfonttablehead\\"><b>Active?</b></span></th>
	<th class=\\"headerRightCell\\"><input name=\\"allbox\\" type=\\"checkbox\\" value=\\"Check All\\" title=\\"Select/Deselect All\\" onClick=\\"checkAll(this.form, \'active\');\\" /></th>
</tr>
".((empty($rulebits) ) ? ("
	<tr class=\\"highRow\\">
		<td colspan=\\"6\\" class=\\"highBothCell\\" align=\\"center\\"><span class=\\"normalfont\\">You currently have no rules set up.</span></td>
	</tr>
") : ("
	$rulebits
"))."
</table>

<br />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr>
	<td align=\\"center\\">
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Save Changes\\" $disablesavechanges />
		<input type=\\"submit\\" class=\\"bginput\\" name=\\"submit\\" value=\\"Apply All Rules\\" onClick=\\"this.form.action = \'rules.apply.php{$GLOBALS[session_url]}\';\\" $disablesavechanges />
		<input type=\\"reset\\" class=\\"bginput\\" name=\\"reset\\" value=\\"Reset Fields\\" $disablesavechanges />
	</td>
</tr>
</table>

</form>

<br />

<form action=\\"rules.update.php{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"newruleform\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"add\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"3\\"><span class=\\"normalfonttablehead\\"><b>Add New Rule</b></span></th>
</tr>
<tr class=\\"highRow\\" valign=\\"top\\">
	<td class=\\"highLeftCell\\"><span class=\\"normalfont\\">When:</span></td>
	<td class=\\"highCell\\">
	<input type=\\"radio\\" name=\\"condtype[0]\\" value=\\"1\\" onClick=\\"changeThisRow(this.form,1,\'0\')\\" checked=\\"checked\\" />
	<select name=\\"condsubjects1[0]\\" id=\\"condsubjects10\\">
		<option value=\\"1\\">email address</option>
		<option value=\\"2\\">message</option>
		<option value=\\"3\\">recipient</option>
		<option value=\\"4\\">subject</option>
	</select>
	<select name=\\"condhows[0]\\" id=\\"condhows0\\">
		<option value=\\"2\\">contains</option>
		<option value=\\"3\\">does not contain</option>
		<option value=\\"1\\">equals</option>
		<option value=\\"4\\">begins with</option>
		<option value=\\"5\\">ends with</option>
	</select>
	<input type=\\"text\\" class=\\"bginput\\" name=\\"condextras1[0]\\" id=\\"condextras10\\" size=\\"20\\" /><br />
	<input type=\\"radio\\" name=\\"condtype[0]\\" value=\\"2\\" onClick=\\"changeThisRow(this.form,2,\'0\')\\" />
	<select name=\\"condsubjects2[0]\\" id=\\"condsubjects20\\">
		<option value=\\"51\\">email is from account</option>
	</select>
	<select name=\\"condextras2[0]\\" id=\\"condextras20\\">
		<option value=\\"0\\" selected=\\"selected\\">$appname</option>
		$accountbits
	</select><br />
	<input type=\\"checkbox\\" name=\\"exempt\\" value=\\"yes\\" checked=\\"checked\\" />Exempt safe senders from this rule</td>
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
	<input type=\\"checkbox\\" name=\\"dowhat[0][color]\\" value=\\"1\\" /> highlight with
	<select name=\\"colorstuff[0]\\">
		<option value=\\"white\\" style=\\"background-color: White;\\" selected=\\"selected\\">White</option>
		<option value=\\"aqua\\" style=\\"background-color: Aqua;\\">Aqua</option>
		<option value=\\"fuchsia\\" style=\\"background-color: Fuchsia;\\">Fuchsia</option>
		<option value=\\"blue\\" style=\\"background-color: Blue;\\">Blue</option>
		<option value=\\"yellow\\" style=\\"background-color: Yellow;\\">Yellow</option>
		<option value=\\"lime\\" style=\\"background-color: Lime;\\">Lime</option>
		<option value=\\"red\\" style=\\"background-color: Red;\\">Red</option>
		<option value=\\"silver\\" style=\\"background-color: Silver;\\">Silver</option>
		<option value=\\"gray\\" style=\\"background-color: Gray; color: White;\\">Gray</option>
		<option value=\\"teal\\" style=\\"background-color: Teal; color: White;\\">Teal</option>
		<option value=\\"purple\\" style=\\"background-color: Purple; color: White;\\">Purple</option>
		<option value=\\"navy\\" style=\\"background-color: Navy; color: White;\\">Navy</option>
		<option value=\\"olive\\" style=\\"background-color: Olive; color: White;\\">Olive</option>
		<option value=\\"green\\" style=\\"background-color: Green; color: White;\\">Green</option>
		<option value=\\"maroon\\" style=\\"background-color: Maroon; color: White;\\">Maroon</option>
		<option value=\\"black\\" style=\\"background-color: Black; color: White;\\">Black</option>
	</select><br />
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
		</td>
	</tr>
</table>

</form>

$GLOBALS[footer]

<script type=\\"text/javascript\\" language=\\"JavaScript\\">
$onload
</script>
</body>
</html>"',
    'upgraded' => '0',
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
<input type="hidden" name="applyall" value="$applyall" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="650">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Apply Rule</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}BothCell" valign="top" colspan="2"><span class="normalfont"><b><%if !$applyall %>$condition $dowhat.<%else%>All of your rules will be applied.<%endif%></b></span></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" valign="top" width="50%"><span class="normalfont">Apply the to folders:</span></td>
	<td class="{classname}RightCell" valign="top" width="50%"><span class="normalfont">
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
<input type=\\"hidden\\" name=\\"applyall\\" value=\\"$applyall\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"650\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Apply Rule</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\" valign=\\"top\\" colspan=\\"2\\"><span class=\\"normalfont\\"><b>".((!$applyall ) ? ("$condition $dowhat.") : ("All of your rules will be applied."))."</b></span></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" valign=\\"top\\" width=\\"50%\\"><span class=\\"normalfont\\">Apply the to folders:</span></td>
	<td class=\\"{classname}RightCell\\" valign=\\"top\\" width=\\"50%\\"><span class=\\"normalfont\\">
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
    'upgraded' => '0',
  ),
  'rules_rulebit' => 
  array (
    'templategroupid' => '10',
    'user_data' => '<tr class="{newclassname}Row" valign="top">
	<td class="{classname}LeftCell" nowrap="nowrap" width="1%" valign="middle">$moveup $movedown</td>
	<td class="{classname}Cell"><span class="normalfont">When:</span><span class="smallfont"><br /><br />[<a href="rules.delete.php?ruleid=$rule[ruleid]" onClick="return confirm(\'Are you sure you want to delete this rule?\');">delete</a>]<br />[<a href="rules.apply.php?cmd=select&ruleid=$rule[ruleid]">apply</a>]</span></td>
	<td class="{classname}Cell">
	<input type="radio" name="condtype[$rule[ruleid]]" value="1" onClick="changeThisRow(this.form,1,$rule[ruleid])" $condtype1 />
	<select name="condsubjects1[$rule[ruleid]]" id="condsubjects1$rule[ruleid]">
		<option value="1" $condsubjects[1]>email address</option>
		<option value="2" $condsubjects[2]>message</option>
		<option value="3" $condsubjects[3]>recipient</option>
		<option value="4" $condsubjects[4]>subject</option>
	</select>
	<select name="condhows[$rule[ruleid]]" id="condhows$rule[ruleid]">
		<option value="2" $condhows[2]>contains</option>
		<option value="3" $condhows[3]>does not contain</option>
		<option value="1" $condhows[1]>equals</option>
		<option value="4" $condhows[4]>begins with</option>
		<option value="5" $condhows[5]>ends with</option>
	</select>
	<input type="text" class="bginput" name="condextras1[$rule[ruleid]]" value="$condextra1" id="condextras1$rule[ruleid]" size="20" /><br />
	<input type="radio" name="condtype[$rule[ruleid]]" value="2" onClick="changeThisRow(this.form,2,$rule[ruleid])" $condtype2 />
	<select name="condsubjects2[$rule[ruleid]]" id="condsubjects2$rule[ruleid]">
		<option value="51" $condother[51]>email is from account</option>
	</select>
	<select name="condextras2[$rule[ruleid]]" id="condextras2$rule[ruleid]">
		<option value="0" $condextras2_0>$appname</option>
		$accountbits
	</select><br />
	<input type="checkbox" name="exempt[$rule[ruleid]]" id="exempt_$rule[ruleid]" value="yes" $exemptchecked /> <label for="exempt_$rule[ruleid]">Exempt safe senders from this rule</label></td>
	<td class="{classname}Cell"><span class="normalfont">
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
	<input type="checkbox" name="dowhat[$rule[ruleid]][color]" value="1" $colorchecked /> highlight with
		<select name="colorstuff[$rule[ruleid]]">
			<option value="white" style="background-color: White;" $bgcolorsel[white]>White</option>
			<option value="aqua" style="background-color: Aqua;" $bgcolorsel[aqua]>Aqua</option>
			<option value="fuchsia" style="background-color: Fuchsia;" $bgcolorsel[fuchsia]>Fuchsia</option>
			<option value="blue" style="background-color: Blue;" $bgcolorsel[blue]>Blue</option>
			<option value="yellow" style="background-color: Yellow;" $bgcolorsel[yellow]>Yellow</option>
			<option value="lime" style="background-color: Lime;" $bgcolorsel[lime]>Lime</option>
			<option value="red" style="background-color: Red;" $bgcolorsel[red]>Red</option>
			<option value="silver" style="background-color: Silver;" $bgcolorsel[silver]>Silver</option>
			<option value="gray" style="background-color: Gray; color: White;" $bgcolorsel[gray]>Gray</option>
			<option value="teal" style="background-color: Teal; color: White;" $bgcolorsel[teal]>Teal</option>
			<option value="purple" style="background-color: Purple; color: White;" $bgcolorsel[purple]>Purple</option>
			<option value="navy" style="background-color: Navy; color: White;" $bgcolorsel[navy]>Navy</option>
			<option value="olive" style="background-color: Olive; color: White;" $bgcolorsel[olive]>Olive</option>
			<option value="green" style="background-color: Green; color: White;" $bgcolorsel[green]>Green</option>
			<option value="maroon" style="background-color: Maroon; color: White;" $bgcolorsel[maroon]>Maroon</option>
			<option value="black" style="background-color: Black; color: White;" $bgcolorsel[black]>Black</option>
		</select><br />
	<input type="checkbox" name="dowhat[$rule[ruleid]][notify]" value="1" $notifychecked /> notify of it.</span></td>
	<td class="{classname}RightCell" align="center"><input type="checkbox" name="active[$rule[ruleid]]" value="yes" $activechecked onClick="checkMain(this.form, \'active\');" /></td>
</tr>',
    'parsed_data' => '"<tr class=\\"{newclassname}Row\\" valign=\\"top\\">
	<td class=\\"{classname}LeftCell\\" nowrap=\\"nowrap\\" width=\\"1%\\" valign=\\"middle\\">$moveup $movedown</td>
	<td class=\\"{classname}Cell\\"><span class=\\"normalfont\\">When:</span><span class=\\"smallfont\\"><br /><br />[<a href=\\"rules.delete.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}ruleid=$rule[ruleid]\\" onClick=\\"return confirm(\'Are you sure you want to delete this rule?\');\\">delete</a>]<br />[<a href=\\"rules.apply.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=select&ruleid=$rule[ruleid]\\">apply</a>]</span></td>
	<td class=\\"{classname}Cell\\">
	<input type=\\"radio\\" name=\\"condtype[$rule[ruleid]]\\" value=\\"1\\" onClick=\\"changeThisRow(this.form,1,$rule[ruleid])\\" $condtype1 />
	<select name=\\"condsubjects1[$rule[ruleid]]\\" id=\\"condsubjects1$rule[ruleid]\\">
		<option value=\\"1\\" $condsubjects[1]>email address</option>
		<option value=\\"2\\" $condsubjects[2]>message</option>
		<option value=\\"3\\" $condsubjects[3]>recipient</option>
		<option value=\\"4\\" $condsubjects[4]>subject</option>
	</select>
	<select name=\\"condhows[$rule[ruleid]]\\" id=\\"condhows$rule[ruleid]\\">
		<option value=\\"2\\" $condhows[2]>contains</option>
		<option value=\\"3\\" $condhows[3]>does not contain</option>
		<option value=\\"1\\" $condhows[1]>equals</option>
		<option value=\\"4\\" $condhows[4]>begins with</option>
		<option value=\\"5\\" $condhows[5]>ends with</option>
	</select>
	<input type=\\"text\\" class=\\"bginput\\" name=\\"condextras1[$rule[ruleid]]\\" value=\\"$condextra1\\" id=\\"condextras1$rule[ruleid]\\" size=\\"20\\" /><br />
	<input type=\\"radio\\" name=\\"condtype[$rule[ruleid]]\\" value=\\"2\\" onClick=\\"changeThisRow(this.form,2,$rule[ruleid])\\" $condtype2 />
	<select name=\\"condsubjects2[$rule[ruleid]]\\" id=\\"condsubjects2$rule[ruleid]\\">
		<option value=\\"51\\" $condother[51]>email is from account</option>
	</select>
	<select name=\\"condextras2[$rule[ruleid]]\\" id=\\"condextras2$rule[ruleid]\\">
		<option value=\\"0\\" $condextras2_0>$appname</option>
		$accountbits
	</select><br />
	<input type=\\"checkbox\\" name=\\"exempt[$rule[ruleid]]\\" id=\\"exempt_$rule[ruleid]\\" value=\\"yes\\" $exemptchecked /> <label for=\\"exempt_$rule[ruleid]\\">Exempt safe senders from this rule</label></td>
	<td class=\\"{classname}Cell\\"><span class=\\"normalfont\\">
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
	<input type=\\"checkbox\\" name=\\"dowhat[$rule[ruleid]][color]\\" value=\\"1\\" $colorchecked /> highlight with
		<select name=\\"colorstuff[$rule[ruleid]]\\">
			<option value=\\"white\\" style=\\"background-color: White;\\" $bgcolorsel[white]>White</option>
			<option value=\\"aqua\\" style=\\"background-color: Aqua;\\" $bgcolorsel[aqua]>Aqua</option>
			<option value=\\"fuchsia\\" style=\\"background-color: Fuchsia;\\" $bgcolorsel[fuchsia]>Fuchsia</option>
			<option value=\\"blue\\" style=\\"background-color: Blue;\\" $bgcolorsel[blue]>Blue</option>
			<option value=\\"yellow\\" style=\\"background-color: Yellow;\\" $bgcolorsel[yellow]>Yellow</option>
			<option value=\\"lime\\" style=\\"background-color: Lime;\\" $bgcolorsel[lime]>Lime</option>
			<option value=\\"red\\" style=\\"background-color: Red;\\" $bgcolorsel[red]>Red</option>
			<option value=\\"silver\\" style=\\"background-color: Silver;\\" $bgcolorsel[silver]>Silver</option>
			<option value=\\"gray\\" style=\\"background-color: Gray; color: White;\\" $bgcolorsel[gray]>Gray</option>
			<option value=\\"teal\\" style=\\"background-color: Teal; color: White;\\" $bgcolorsel[teal]>Teal</option>
			<option value=\\"purple\\" style=\\"background-color: Purple; color: White;\\" $bgcolorsel[purple]>Purple</option>
			<option value=\\"navy\\" style=\\"background-color: Navy; color: White;\\" $bgcolorsel[navy]>Navy</option>
			<option value=\\"olive\\" style=\\"background-color: Olive; color: White;\\" $bgcolorsel[olive]>Olive</option>
			<option value=\\"green\\" style=\\"background-color: Green; color: White;\\" $bgcolorsel[green]>Green</option>
			<option value=\\"maroon\\" style=\\"background-color: Maroon; color: White;\\" $bgcolorsel[maroon]>Maroon</option>
			<option value=\\"black\\" style=\\"background-color: Black; color: White;\\" $bgcolorsel[black]>Black</option>
		</select><br />
	<input type=\\"checkbox\\" name=\\"dowhat[$rule[ruleid]][notify]\\" value=\\"1\\" $notifychecked /> notify of it.</span></td>
	<td class=\\"{classname}RightCell\\" align=\\"center\\"><input type=\\"checkbox\\" name=\\"active[$rule[ruleid]]\\" value=\\"yes\\" $activechecked onClick=\\"checkMain(this.form, \'active\');\\" /></td>
</tr>"',
    'upgraded' => '0',
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
	<select name="folderids[]" multiple="multiple" size="$selectsize">
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
	<select name=\\"folderids[]\\" multiple=\\"multiple\\" size=\\"$selectsize\\">
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
    'upgraded' => '0',
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
var previewBoth = (\'$hiveuser[preview]\' == \'both\' ? true : false);

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
<%if $hiveuser[preview] == \'top\' %>
$preview<br />
<%endif%>

<form action="{<INDEX_FILE>}" method="post" name="form">
<input type="hidden" name="cmd" id="cmd" value="dostuff" />
<input type="hidden" name="searchid" value="$searchid" />
<input type="hidden" name="folderid" value="$folderid" />
<input type="hidden" name="movetofolderid" value="$folderid" />
<input type="hidden" name="remove" value="0" />

<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerLeftCell">&nbsp;</th>
	<th class="headerCell" width="15" nowrap="nowrap"><span class="headerText"><a href="search.results.php?searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=flagged"><img src="$skin[images]/flag.gif" alt="Flagged?" border="0" /></a></span></th>
$colheaders
	<th class="headerCell" nowrap="nowrap"><span class="headerText"><b><a href="search.results.php?searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=folderid"><span class="normalfonttablehead">Folder</b></span>$sortimages[folderid]</a></span></th>
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
<br />$preview
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
var previewBoth = (\'$hiveuser[preview]\' == \'both\' ? true : false);

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
".(($hiveuser[preview] == \'top\' ) ? ("
$preview<br />
") : (\'\'))."

<form action=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}\\" method=\\"post\\" name=\\"form\\">
<input type=\\"hidden\\" name=\\"cmd\\" id=\\"cmd\\" value=\\"dostuff\\" />
<input type=\\"hidden\\" name=\\"searchid\\" value=\\"$searchid\\" />
<input type=\\"hidden\\" name=\\"folderid\\" value=\\"$folderid\\" />
<input type=\\"hidden\\" name=\\"movetofolderid\\" value=\\"$folderid\\" />
<input type=\\"hidden\\" name=\\"remove\\" value=\\"0\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerLeftCell\\">&nbsp;</th>
	<th class=\\"headerCell\\" width=\\"15\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><a href=\\"search.results.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=flagged\\"><img src=\\"{$GLOBALS[skin][images]}/flag.gif\\" alt=\\"Flagged?\\" border=\\"0\\" /></a></span></th>
$colheaders
	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><b><a href=\\"search.results.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=folderid\\"><span class=\\"normalfonttablehead\\">Folder</b></span>$sortimages[folderid]</a></span></th>
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
<br />$preview
") : (\'\'))."

</form>
	</td>
</tr>
</table>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'search_results_header_attach' => 
  array (
    'templategroupid' => '5',
    'user_data' => '	<th class="headerCell" width="15" nowrap="nowrap"><span class="headerText"><a href="search.results.php?searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=attach"><img src="$skin[images]/paperclip.gif" alt="Has attachments?" border="0" /></a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\" width=\\"15\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><a href=\\"search.results.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=attach\\"><img src=\\"{$GLOBALS[skin][images]}/paperclip.gif\\" alt=\\"Has attachments?\\" border=\\"0\\" /></a></span></th>
"',
    'upgraded' => '0',
  ),
  'search_results_header_datetime' => 
  array (
    'templategroupid' => '5',
    'user_data' => '	<th class="headerCell"><span class="headerText"><b><a href="search.results.php?searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=dateline"><span class="normalfonttablehead">Received</b></span>$sortimages[dateline]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\"><span class=\\"headerText\\"><b><a href=\\"search.results.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=dateline\\"><span class=\\"normalfonttablehead\\">Received</b></span>$sortimages[dateline]</a></span></th>
"',
    'upgraded' => '0',
  ),
  'search_results_header_from' => 
  array (
    'templategroupid' => '5',
    'user_data' => '	<th class="headerCell"><span class="headerText"><a href="search.results.php?searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=name"><span class="normalfonttablehead"><b>From</b></span>$sortimages[name]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\"><span class=\\"headerText\\"><a href=\\"search.results.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=name\\"><span class=\\"normalfonttablehead\\"><b>From</b></span>$sortimages[name]</a></span></th>
"',
    'upgraded' => '0',
  ),
  'search_results_header_priority' => 
  array (
    'templategroupid' => '5',
    'user_data' => '	<th class="headerCell" width="15" nowrap="nowrap"><span class="headerText"><a href="search.results.php?searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=priority"><img src="$skin[images]/prio_high.gif" alt="Important?" border="0" /></a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\" width=\\"15\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><a href=\\"search.results.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=priority\\"><img src=\\"{$GLOBALS[skin][images]}/prio_high.gif\\" alt=\\"Important?\\" border=\\"0\\" /></a></span></th>
"',
    'upgraded' => '0',
  ),
  'search_results_header_size' => 
  array (
    'templategroupid' => '5',
    'user_data' => '	<th class="headerCell" nowrap="nowrap"><span class="headerText"><b><a href="search.results.php?searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=size"><span class="normalfonttablehead">Size</b></span>$sortimages[size]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><b><a href=\\"search.results.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=size\\"><span class=\\"normalfonttablehead\\">Size</b></span>$sortimages[size]</a></span></th>
"',
    'upgraded' => '0',
  ),
  'search_results_header_subject' => 
  array (
    'templategroupid' => '5',
    'user_data' => '	<th class="headerCell" nowrap="nowrap"><span class="headerText"><a href="search.results.php?searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=subject"><span class="normalfonttablehead"><b>Message Subject</b></span>$sortimages[subject]</a></span></th>
',
    'parsed_data' => '"	<th class=\\"headerCell\\" nowrap=\\"nowrap\\"><span class=\\"headerText\\"><a href=\\"search.results.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}searchid=$searchid&perpage=$perpage&sortorder=$newsortorder&sortby=subject\\"><span class=\\"normalfonttablehead\\"><b>Message Subject</b></span>$sortimages[subject]</a></span></th>
"',
    'upgraded' => '0',
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
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Your name:</b></span>
	<br />
	<span class="smallfont">This name will be sent with all your outgoing emails.</span></td>
	<td class="{classname}RightCell" width="40%"><input type="text" class="bginput" name="realname" value="$realname" size="40" /></td>
</tr>
<%if $passtype == \'static\' %>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Your password:</b></span></td>
		<td class="{classname}RightCell" width="40%"><span class="normalfont">$hidden_password</span></td>
	</tr>
	<input type="hidden" name="password" value="$password" />
	<input type="hidden" name="password_repeat" value="$password" />
	<input type="hidden" name="password_encrypted" value="1" />
	<input type="hidden" name="password_length" value="$passlen" />
<%else%>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Your password:</b></span></td>
		<td class="{classname}RightCell" width="40%"><input type="password" class="bginput" name="password" size="40" /></td>
	</tr>
	<tr class="{newclassname}Row">
		<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Retype password:</b></span>
		<br />
		<span class="smallfont">Repeat the password to verify it\'s correct.</span></td>
		<td class="{classname}RightCell" width="40%"><input type="password" class="bginput" name="password_repeat" size="40" /></td>
	</tr>
	<input type="hidden" name="password_encrypted" value="0" />
<%endif%>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Secret question:</b></span><br /><span class="smallfont">If you ever forget your password, you will be asked to answer this question in order to get a new one.</span></td>
	<td class="{classname}RightCell" width="40%"><input type="text" class="bginput" name="question" value="$question" size="40" /><br /><br />
		<select name="question_options" style="width: 100%;" onChange="if (this.options[this.selectedIndex].value != \'-1\') form.question.value = this.options[this.selectedIndex].text;">
			<option value="-1">(Or choose a question from below)</option>
			<option>Your mother\'s Maiden name?</option>
			<option>Your pet\'s name?</option>
			<option>City of birth?</option>
			<option>Last 4 digits of social security number?</option>
		</select>
	</td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Secret answer:</b></span></td>
	<td class="{classname}RightCell" width="40%"><input type="password" class="bginput" name="answer" value="$answer" size="40" /></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Retype secret answer:</b></span><br /><span class="smallfont">Repeat the secret answer to verify it\'s correct.</span></td>
	<td class="{classname}RightCell" width="40%"><input type="password" class="bginput" name="answer_repeat" value="$answer_repeat" size="40" /></td>
</tr>
<%if getop(\'regcodecheck\') %>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><%if $badcode %><span class="important"><%endif%><b>Registration code:</b><%if $badcode %></span><%endif%></span><br /><span class="smallfont">Please enter the numbers as they appear in the image to the right. If you cannot identify the numbers, make a guess - if the code you enter is incorrect, a new one will be created when the page is loaded again.<br />
	This measure helps us prevent automated registrations and give you a better service.</span></td>
	<td class="{classname}RightCell" width="40%"><table cellpadding="0" cellspacing="0" align="center"><tr><td style="border-style: solid; border-width: 1px; border-color: black;"><img src="user.signup.php?cmd=image&pos=1&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=2&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=3&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=4&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=5&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=6&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=7&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=8&x=$timenow" border="0" alt="" /><img src="user.signup.php?cmd=image&pos=9&x=$timenow" border="0" alt="" /></td></tr></table>
		<br /><input type="text" class="bginput" name="userregcode" value="$regcodevalue" size="40" /></td>
</tr>
<%endif%>
<%if $requirealt %>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Secondary email address:</b></span><br /><span class="smallfont">As the operators of $appname require user validation, your account will not be functional until they activate it. You will be notified by email at this address when your account is activated.</span></td>
	<td class="{classname}RightCell" width="40%"><input type="text" class="bginput" name="altemail" value="$altemail" size="40" /></td>
</tr>
<%endif%>
$required_custom_fields
<!-- +++++++++++++++++++++++++++++++++++++ -->
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Optional Information</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Time zone:</b></span><br /><span class="smallfont">Please select the correct time zone from the list.<br />The system will automatically try to adjust the time offset if Daylight Saving Time (DST) is in effect.</span></td>
	<td class="{classname}RightCell" width="40%">$timezone</td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Birthday:</b></span></td>
	<td class="{classname}RightCell" width="40%"><select name="month">
			<option value="0" $monthsel[0]>Month</option>
			<option value="1" $monthsel[1]>$skin[cal_jan_long]</option>
			<option value="2" $monthsel[2]>$skin[cal_feb_long]</option>
			<option value="3" $monthsel[3]>$skin[cal_mar_long]</option>
			<option value="4" $monthsel[4]>$skin[cal_apr_long]</option>
			<option value="5" $monthsel[5]>$skin[cal_may_long]</option>
			<option value="6" $monthsel[6]>$skin[cal_jun_long]</option>
			<option value="7" $monthsel[7]>$skin[cal_jul_long]</option>
			<option value="8" $monthsel[8]>$skin[cal_aug_long]</option>
			<option value="9" $monthsel[9]>$skin[cal_sep_long]</option>
			<option value="10" $monthsel[10]>$skin[cal_oct_long]</option>
			<option value="11" $monthsel[11]>$skin[cal_nov_long]</option>
			<option value="12" $monthsel[12]>$skin[cal_dec_long]</option>
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
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Country:</b></span></td>
	<td class="{classname}RightCell" width="40%"><select name="country" onChange="if (this.options[this.selectedIndex].value != \'us\') this.form.state.selectedIndex = 0;">
		$countries
	</select></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>State:</b></span></td>
	<td class="{classname}RightCell" width="40%"><select name="state">
		$states
	</select></td>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Zip code:</b></span></td>
	<td class="{classname}RightCell" width="40%"><input type="text" class="bginput" name="zip" value="$zip" size="7" maxlength="7"></td>
</tr>
<%if !$requirealt %>
<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%" valign="top"><span class="normalfont"><b>Secondary email address:</b></span></td>
	<td class="{classname}RightCell" width="40%"><input type="text" class="bginput" name="altemail" value="$altemail" size="40" /></td>
</tr>
<%endif%>
$optional_custom_fields
<%if getop(\'termsofservice\') %>
<!-- +++++++++++++++++++++++++++++++++++++ -->
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Terms of Service</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}BothCell" colspan="2" align="center"><span class="normalfont"><b><input type="checkbox" name="agreeterms" value="1" id="agreeterms" $termschecked /> <%if $noterms %><span class="important"><%endif%><label for="agreeterms">I have read and understand the Terms of Service and agree to them</label>.<%if $noterms %></span><%endif%><br /><br /><textarea name="terms" cols="101" rows="5">$termsofservice</textarea></b></span></td>
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
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Your name:</b></span>
	<br />
	<span class=\\"smallfont\\">This name will be sent with all your outgoing emails.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"realname\\" value=\\"$realname\\" size=\\"40\\" /></td>
</tr>
".(($passtype == \'static\' ) ? ("
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Your password:</b></span></td>
		<td class=\\"{classname}RightCell\\" width=\\"40%\\"><span class=\\"normalfont\\">$hidden_password</span></td>
	</tr>
	<input type=\\"hidden\\" name=\\"password\\" value=\\"$password\\" />
	<input type=\\"hidden\\" name=\\"password_repeat\\" value=\\"$password\\" />
	<input type=\\"hidden\\" name=\\"password_encrypted\\" value=\\"1\\" />
	<input type=\\"hidden\\" name=\\"password_length\\" value=\\"$passlen\\" />
") : ("
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Your password:</b></span></td>
		<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"password\\" size=\\"40\\" /></td>
	</tr>
	<tr class=\\"{newclassname}Row\\">
		<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Retype password:</b></span>
		<br />
		<span class=\\"smallfont\\">Repeat the password to verify it\'s correct.</span></td>
		<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"password_repeat\\" size=\\"40\\" /></td>
	</tr>
	<input type=\\"hidden\\" name=\\"password_encrypted\\" value=\\"0\\" />
"))."
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secret question:</b></span><br /><span class=\\"smallfont\\">If you ever forget your password, you will be asked to answer this question in order to get a new one.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"question\\" value=\\"$question\\" size=\\"40\\" /><br /><br />
		<select name=\\"question_options\\" style=\\"width: 100%;\\" onChange=\\"if (this.options[this.selectedIndex].value != \'-1\') form.question.value = this.options[this.selectedIndex].text;\\">
			<option value=\\"-1\\">(Or choose a question from below)</option>
			<option>Your mother\'s Maiden name?</option>
			<option>Your pet\'s name?</option>
			<option>City of birth?</option>
			<option>Last 4 digits of social security number?</option>
		</select>
	</td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secret answer:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"answer\\" value=\\"$answer\\" size=\\"40\\" /></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Retype secret answer:</b></span><br /><span class=\\"smallfont\\">Repeat the secret answer to verify it\'s correct.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"password\\" class=\\"bginput\\" name=\\"answer_repeat\\" value=\\"$answer_repeat\\" size=\\"40\\" /></td>
</tr>
".((getop(\'regcodecheck\') ) ? ("
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\">".(($badcode ) ? ("<span class=\\"important\\">") : (\'\'))."<b>Registration code:</b>".(($badcode ) ? ("</span>") : (\'\'))."</span><br /><span class=\\"smallfont\\">Please enter the numbers as they appear in the image to the right. If you cannot identify the numbers, make a guess - if the code you enter is incorrect, a new one will be created when the page is loaded again.<br />
	This measure helps us prevent automated registrations and give you a better service.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><table cellpadding=\\"0\\" cellspacing=\\"0\\" align=\\"center\\"><tr><td style=\\"border-style: solid; border-width: 1px; border-color: black;\\"><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=1&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=2&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=3&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=4&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=5&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=6&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=7&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=8&x=$timenow\\" border=\\"0\\" alt=\\"\\" /><img src=\\"user.signup.php{$GLOBALS[session_url]}{$GLOBALS[session_ampersand]}cmd=image&pos=9&x=$timenow\\" border=\\"0\\" alt=\\"\\" /></td></tr></table>
		<br /><input type=\\"text\\" class=\\"bginput\\" name=\\"userregcode\\" value=\\"$regcodevalue\\" size=\\"40\\" /></td>
</tr>
") : (\'\'))."
".(($requirealt ) ? ("
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secondary email address:</b></span><br /><span class=\\"smallfont\\">As the operators of $appname require user validation, your account will not be functional until they activate it. You will be notified by email at this address when your account is activated.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"altemail\\" value=\\"$altemail\\" size=\\"40\\" /></td>
</tr>
") : (\'\'))."
$required_custom_fields
<!-- +++++++++++++++++++++++++++++++++++++ -->
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Optional Information</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Time zone:</b></span><br /><span class=\\"smallfont\\">Please select the correct time zone from the list.<br />The system will automatically try to adjust the time offset if Daylight Saving Time (DST) is in effect.</span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\">$timezone</td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Birthday:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><select name=\\"month\\">
			<option value=\\"0\\" $monthsel[0]>Month</option>
			<option value=\\"1\\" $monthsel[1]>{$GLOBALS[skin][cal_jan_long]}</option>
			<option value=\\"2\\" $monthsel[2]>{$GLOBALS[skin][cal_feb_long]}</option>
			<option value=\\"3\\" $monthsel[3]>{$GLOBALS[skin][cal_mar_long]}</option>
			<option value=\\"4\\" $monthsel[4]>{$GLOBALS[skin][cal_apr_long]}</option>
			<option value=\\"5\\" $monthsel[5]>{$GLOBALS[skin][cal_may_long]}</option>
			<option value=\\"6\\" $monthsel[6]>{$GLOBALS[skin][cal_jun_long]}</option>
			<option value=\\"7\\" $monthsel[7]>{$GLOBALS[skin][cal_jul_long]}</option>
			<option value=\\"8\\" $monthsel[8]>{$GLOBALS[skin][cal_aug_long]}</option>
			<option value=\\"9\\" $monthsel[9]>{$GLOBALS[skin][cal_sep_long]}</option>
			<option value=\\"10\\" $monthsel[10]>{$GLOBALS[skin][cal_oct_long]}</option>
			<option value=\\"11\\" $monthsel[11]>{$GLOBALS[skin][cal_nov_long]}</option>
			<option value=\\"12\\" $monthsel[12]>{$GLOBALS[skin][cal_dec_long]}</option>
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
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Country:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><select name=\\"country\\" onChange=\\"if (this.options[this.selectedIndex].value != \'us\') this.form.state.selectedIndex = 0;\\">
		$countries
	</select></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>State:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><select name=\\"state\\">
		$states
	</select></td>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Zip code:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"zip\\" value=\\"$zip\\" size=\\"7\\" maxlength=\\"7\\"></td>
</tr>
".((!$requirealt ) ? ("
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\" valign=\\"top\\"><span class=\\"normalfont\\"><b>Secondary email address:</b></span></td>
	<td class=\\"{classname}RightCell\\" width=\\"40%\\"><input type=\\"text\\" class=\\"bginput\\" name=\\"altemail\\" value=\\"$altemail\\" size=\\"40\\" /></td>
</tr>
") : (\'\'))."
$optional_custom_fields
".((getop(\'termsofservice\') ) ? ("
<!-- +++++++++++++++++++++++++++++++++++++ -->
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Terms of Service</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\" colspan=\\"2\\" align=\\"center\\"><span class=\\"normalfont\\"><b><input type=\\"checkbox\\" name=\\"agreeterms\\" value=\\"1\\" id=\\"agreeterms\\" $termschecked /> ".(($noterms ) ? ("<span class=\\"important\\">") : (\'\'))."<label for=\\"agreeterms\\">I have read and understand the Terms of Service and agree to them</label>.".(($noterms ) ? ("</span>") : (\'\'))."<br /><br /><textarea name=\\"terms\\" cols=\\"101\\" rows=\\"5\\">$termsofservice</textarea></b></span></td>
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
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'signup_activate_subject' => 
  array (
    'templategroupid' => '14',
    'user_data' => 'Account activated at $appname!',
    'parsed_data' => '"Account activated at $appname!"',
    'upgraded' => '0',
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

") : (\'\'))."
$appname team"',
    'upgraded' => '0',
  ),
  'signup_notify_subject' => 
  array (
    'templategroupid' => '14',
    'user_data' => 'New user at $appname',
    'parsed_data' => '"New user at $appname"',
    'upgraded' => '0',
  ),
  'signup_thankyou' => 
  array (
    'templategroupid' => '14',
    'user_data' => 'Thank for your signing up for our service!<br />
<%if getop(\'moderate\') %>
As the operators of $appname require user validation, your account will not be functional until they activate it. You will be notified by email ($altemail) when your account is activated.
<%else%>
Click <a href="{<INDEX_FILE>}">here</a> to be taken to your Inbox. Enjoy $appname!
<%endif%>',
    'parsed_data' => '"Thank for your signing up for our service!<br />
".((getop(\'moderate\') ) ? ("
As the operators of $appname require user validation, your account will not be functional until they activate it. You will be notified by email ($altemail) when your account is activated.
") : ("
Click <a href=\\"".(defined("INDEX_FILE") ? constant("INDEX_FILE") : "{<INDEX_FILE>}")."{$GLOBALS[session_url]}\\">here</a> to be taken to your Inbox. Enjoy $appname!
"))',
    'upgraded' => '0',
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
    'upgraded' => '0',
  ),
  'signup_welcome_subject' => 
  array (
    'templategroupid' => '14',
    'user_data' => 'Welcome to $appname!',
    'parsed_data' => '"Welcome to $appname!"',
    'upgraded' => '0',
  ),
  'subscriptions' => 
  array (
    'templategroupid' => '19',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Subscriptions</title>
$css
<script type="text/javascript" language="JavaScript">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}

function subCancel(planID) {
	getElement(\'cancelSubTable\').style.display = \'\';
}
function subRenew(planID) {
	getElement(\'renewSubTable\').style.display = \'\';
}

// -->
</script>
</head>
<body>
$header

<form action="options.subscription.php" method="post">
<input type="hidden" name="cmd" value="payform" />
<input type="hidden" name="planid" value="0" />

<table cellpadding="4" cellspacing="0" width="750">
	<tr>
		<td valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="150">
				<tr class="headerRow">
					<th class="headerBothCell"><span class="normalfonttablehead"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width="100%" valign="top">
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell" colspan="2"><span class="normalfonttablehead"><b>Available Subscriptions</b></span></th>
</tr>
$plans
</table>
		</td>
	</tr>
</table>

</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Subscriptions</title>
$GLOBALS[css]
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}

function subCancel(planID) {
	getElement(\'cancelSubTable\').style.display = \'\';
}
function subRenew(planID) {
	getElement(\'renewSubTable\').style.display = \'\';
}

// -->
</script>
</head>
<body>
$GLOBALS[header]

<form action=\\"options.subscription.php{$GLOBALS[session_url]}\\" method=\\"post\\">
<input type=\\"hidden\\" name=\\"cmd\\" value=\\"payform\\" />
<input type=\\"hidden\\" name=\\"planid\\" value=\\"0\\" />

<table cellpadding=\\"4\\" cellspacing=\\"0\\" width=\\"750\\">
	<tr>
		<td valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"150\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width=\\"100%\\" valign=\\"top\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\" colspan=\\"2\\"><span class=\\"normalfonttablehead\\"><b>Available Subscriptions</b></span></th>
</tr>
$plans
</table>
		</td>
	</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'subscriptions_already_subscribed' => 
  array (
    'templategroupid' => '19',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Subscriptions</title>
$css
<script type="text/javascript" language="JavaScript">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$header

<table cellpadding="4" cellspacing="0" width="750">
	<tr>
		<td valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="150">
				<tr class="headerRow">
					<th class="headerBothCell"><span class="normalfonttablehead"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width="100%" valign="top">
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Subscriptions</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}BothCell"><span class="normalfont">You are currently subscribed to the <b>$cursub[name]</b> plan. Before signing up to a new plan you must cancel your current subscription. Please note that if you choose to sign up for this plan in the future you will have to pay again.<br />
	<form action="options.subscription.php" method="post" style="margin: 0px;"><input type="hidden" name="cmd" value="" />
	<input type="checkbox" name="verify" value="1" id="verify" /><label for="verify">I would like to cancel my current subscription.</label><br /><br />
	<div align="center"><input type="submit" value="Cancel Subscription" class="bginput" onClick="if (!this.form.verify.checked) { alert(\'Please check the verification box.\'); return false; } this.form.cmd.value = \'cancel\';" /></div>
	</form></span></td>
</tr>
<%if $canrenew %>
<tr class="{newclassname}Row">
	<td class="{classname}BothCell"><span class="normalfont">Your current subscription will expire on <b>$cursub[expires]</b>.<br />
	The cost of renewing your subscription is $$cursub[cost] for $cursub[length] $cursub[unit].
	<%if $cursub[canpayadvance] %>You can, if you wish, pay for a number of periods in advance. Please choose the number of periods you would like to pay for and proceed to checkout.<br /><br />
	<form style="margin: 0px;">
	Number of periods: <input type="text" name="periods" value="1" size="2" class="bginput" onChange="if (this.value < 1) { this.value = 1; } else { getElement(\'payform_cost_field\').value = $cursub[cost] * this.value; this.form.cost.value = \'$\' + ($cursub[cost] * this.value); }" /><br />
	Cost: <input type="text" name="cost" value="$$cursub[cost]" size="5" class="{classname}Inactive" readonly="readonly" /><%endif%><br /><br />
	</form>
	$payform
	<div align="center"><input type="submit" value="Renew Subscription" class="bginput" /></div>
	</form></span></td>
</tr>
<%endif%>
</table>

</form>
		</td>
	</tr>
</table>

</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Subscriptions</title>
$GLOBALS[css]
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$GLOBALS[header]

<table cellpadding=\\"4\\" cellspacing=\\"0\\" width=\\"750\\">
	<tr>
		<td valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"150\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width=\\"100%\\" valign=\\"top\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Subscriptions</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\"><span class=\\"normalfont\\">You are currently subscribed to the <b>$cursub[name]</b> plan. Before signing up to a new plan you must cancel your current subscription. Please note that if you choose to sign up for this plan in the future you will have to pay again.<br />
	<form action=\\"options.subscription.php{$GLOBALS[session_url]}\\" method=\\"post\\" style=\\"margin: 0px;\\"><input type=\\"hidden\\" name=\\"cmd\\" value=\\"\\" />
	<input type=\\"checkbox\\" name=\\"verify\\" value=\\"1\\" id=\\"verify\\" /><label for=\\"verify\\">I would like to cancel my current subscription.</label><br /><br />
	<div align=\\"center\\"><input type=\\"submit\\" value=\\"Cancel Subscription\\" class=\\"bginput\\" onClick=\\"if (!this.form.verify.checked) { alert(\'Please check the verification box.\'); return false; } this.form.cmd.value = \'cancel\';\\" /></div>
	</form></span></td>
</tr>
".(($canrenew ) ? ("
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\"><span class=\\"normalfont\\">Your current subscription will expire on <b>$cursub[expires]</b>.<br />
	The cost of renewing your subscription is $$cursub[cost] for $cursub[length] $cursub[unit].
	".(($cursub[canpayadvance] ) ? ("You can, if you wish, pay for a number of periods in advance. Please choose the number of periods you would like to pay for and proceed to checkout.<br /><br />
	<form style=\\"margin: 0px;\\">
	Number of periods: <input type=\\"text\\" name=\\"periods\\" value=\\"1\\" size=\\"2\\" class=\\"bginput\\" onChange=\\"if (this.value < 1) { this.value = 1; } else { getElement(\'payform_cost_field\').value = $cursub[cost] * this.value; this.form.cost.value = \'$\' + ($cursub[cost] * this.value); }\\" /><br />
	Cost: <input type=\\"text\\" name=\\"cost\\" value=\\"$$cursub[cost]\\" size=\\"5\\" class=\\"{classname}Inactive\\" readonly=\\"readonly\\" />") : (\'\'))."<br /><br />
	</form>
	$payform
	<div align=\\"center\\"><input type=\\"submit\\" value=\\"Renew Subscription\\" class=\\"bginput\\" /></div>
	</form></span></td>
</tr>
") : (\'\'))."
</table>

</form>
		</td>
	</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'subscriptions_payment_form' => 
  array (
    'templategroupid' => '19',
    'user_data' => '$skin[doctype]
<html>
<head><title>$appname: Subscriptions</title>
$css
<script type="text/javascript" language="JavaScript">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$header

<table cellpadding="4" cellspacing="0" width="750">
	<tr>
		<td valign="top">
			<table cellpadding="4" cellspacing="0" class="normalTable" width="150">
				<tr class="headerRow">
					<th class="headerBothCell"><span class="normalfonttablehead"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width="100%" valign="top">
<table cellpadding="4" cellspacing="0" class="normalTable" width="100%">
<tr class="headerRow">
	<th class="headerBothCell"><span class="normalfonttablehead"><b>Subscriptions</b></span></th>
</tr>
<tr class="{newclassname}Row">
	<td class="{classname}BothCell"><span class="normalfont">You have chosen to subscription for the <b>$plan[name]</b> plan.<br />
	The cost of this plan is $$plan[cost] <%if $plan[\'length\'] == 0 %>(one-time fee)<%else%>for $plan[length] $plan[unit]<%endif%>.
	<%if $plan[canpayadvance] and $plan[\'length\'] > 0 %>You can, if you wish, pay for a number of periods in advance. Please choose the number of periods you would like to pay for and proceed to checkout.<br /><br />
	<form style="margin: 0px;">
	Number of periods: <input type="text" name="periods" value="1" size="2" class="bginput" onChange="if (this.value < 1) { this.value = 1; } else { getElement(\'payform_cost_field\').value = $plan[cost] * this.value; this.form.cost.value = \'$\' + ($plan[cost] * this.value); }" /><br />
	Cost: <input type="text" name="cost" value="$$plan[cost]" size="5" class="{classname}Inactive" readonly="readonly" /><%endif%><br /><br />
	</form>
	$payform
	<div align="center"><input type="submit" value="Purchase Subscription" class="bginput" /></div>
	</form></span></td>
</tr>
</table>

</form>
		</td>
	</tr>
</table>

</form>

$footer

</body>
</html>',
    'parsed_data' => '"{$GLOBALS[skin][doctype]}
<html>
<head><title>$appname: Subscriptions</title>
$GLOBALS[css]
<script type=\\"text/javascript\\" language=\\"JavaScript\\">
<!--
var menuDesc = new Array;
function showDesc(id) {}
function hideDesc(id) {}
// -->
</script>
</head>
<body>
$GLOBALS[header]

<table cellpadding=\\"4\\" cellspacing=\\"0\\" width=\\"750\\">
	<tr>
		<td valign=\\"top\\">
			<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"150\\">
				<tr class=\\"headerRow\\">
					<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Preferences</b></span></th>
				</tr>
				$menus
			</table>
		</td>
		<td width=\\"100%\\" valign=\\"top\\">
<table cellpadding=\\"4\\" cellspacing=\\"0\\" class=\\"normalTable\\" width=\\"100%\\">
<tr class=\\"headerRow\\">
	<th class=\\"headerBothCell\\"><span class=\\"normalfonttablehead\\"><b>Subscriptions</b></span></th>
</tr>
<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}BothCell\\"><span class=\\"normalfont\\">You have chosen to subscription for the <b>$plan[name]</b> plan.<br />
	The cost of this plan is $$plan[cost] ".(($plan[\'length\'] == 0 ) ? ("(one-time fee)") : ("for $plan[length] $plan[unit]")).".
	".(($plan[canpayadvance] and $plan[\'length\'] > 0 ) ? ("You can, if you wish, pay for a number of periods in advance. Please choose the number of periods you would like to pay for and proceed to checkout.<br /><br />
	<form style=\\"margin: 0px;\\">
	Number of periods: <input type=\\"text\\" name=\\"periods\\" value=\\"1\\" size=\\"2\\" class=\\"bginput\\" onChange=\\"if (this.value < 1) { this.value = 1; } else { getElement(\'payform_cost_field\').value = $plan[cost] * this.value; this.form.cost.value = \'$\' + ($plan[cost] * this.value); }\\" /><br />
	Cost: <input type=\\"text\\" name=\\"cost\\" value=\\"$$plan[cost]\\" size=\\"5\\" class=\\"{classname}Inactive\\" readonly=\\"readonly\\" />") : (\'\'))."<br /><br />
	</form>
	$payform
	<div align=\\"center\\"><input type=\\"submit\\" value=\\"Purchase Subscription\\" class=\\"bginput\\" /></div>
	</form></span></td>
</tr>
</table>

</form>
		</td>
	</tr>
</table>

</form>

$GLOBALS[footer]

</body>
</html>"',
    'upgraded' => '0',
  ),
  'subscriptions_planbit' => 
  array (
    'templategroupid' => '19',
    'user_data' => '<tr class="{newclassname}Row">
	<td class="{classname}LeftCell" width="60%"><span class="normalfont"><b>$plan[name]</b><br /><br />$plan[description]<br /><br />Cost: <b>$$plan[cost]</b> <%if $plan[\'length\'] == 0 %>(one-time fee)<%else%>every $plan[length] $plan[unit]<%endif%></span></td>
	<td class="{classname}RightCell" align="center"><span class="normalfont">Pay through: <select name="processors[$plan[planid]]">$procselect</select><br /><br /><input type="submit" class="bginput" value="Sign-up" onClick="this.form.planid.value = $plan[planid];" /></span></td>
</tr>',
    'parsed_data' => '"<tr class=\\"{newclassname}Row\\">
	<td class=\\"{classname}LeftCell\\" width=\\"60%\\"><span class=\\"normalfont\\"><b>$plan[name]</b><br /><br />$plan[description]<br /><br />Cost: <b>$$plan[cost]</b> ".(($plan[\'length\'] == 0 ) ? ("(one-time fee)") : ("every $plan[length] $plan[unit]"))."</span></td>
	<td class=\\"{classname}RightCell\\" align=\\"center\\"><span class=\\"normalfont\\">Pay through: <select name=\\"processors[$plan[planid]]\\">$procselect</select><br /><br /><input type=\\"submit\\" class=\\"bginput\\" value=\\"Sign-up\\" onClick=\\"this.form.planid.value = $plan[planid];\\" /></span></td>
</tr>"',
    'upgraded' => '0',
  ),
  'subscriptions_thankyou' => 
  array (
    'templategroupid' => '19',
    'user_data' => 'Thank you for your payment! You will receive a confirmation email shortly.',
    'parsed_data' => '"Thank you for your payment! You will receive a confirmation email shortly."',
    'upgraded' => '0',
  ),
);

?>