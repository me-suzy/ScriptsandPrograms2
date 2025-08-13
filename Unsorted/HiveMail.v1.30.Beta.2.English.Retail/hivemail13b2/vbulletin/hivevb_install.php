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
// | $RCSfile: hivevb_install.php,v $ - $Revision: 1.2 $
// | $Date: 2003/12/27 08:34:10 $ - $Author: tubedogg $
// +-------------------------------------------------------------+

error_reporting(E_ALL & ~E_NOTICE);
require('./global.php');

// ############################################################################
// Start
cpheader();
echo '<b>Installing vBulletin-HiveMail&trade; integration</b><br /><br />';

// ############################################################################
// Define templates
$_templates = array(
	'hivemail_error_nameillegal' => 'The username you chose, $hive_username was not valid. Your username may only contain alphanumeric characters, underscores (_) and dots (.), must start with a letter and must be a minimum length of 2 characters.',
	'hivemail_error_nametaken' => 'We\'re sorry, $hive_username$hive_userdomain is already used by another member. Please go back and enter a different name.',
	'hivemail_thankyou' => 'Thank you for signing up for our mail service, your password is the same one you use for this account.',
	'hivemail_signup' => '{htmldoctype}
<html>
<head>
<title>$bbtitle Email Account</title>
$headinclude
</head>
<body>

$header

<br>

<form action="register.php" method="post">
<input type="hidden" name="s" value="$session[sessionhash]">

<table cellpadding="{tableouterborderwidth}" cellspacing="0" border="0" bgcolor="{tablebordercolor}" {tableouterextra} width="{contenttablewidth}" align="center"><tr><td>
<table cellpadding="4" cellspacing="{tableinnerborderwidth}" border="0" {tableinnerextra} width="100%">
<tr>
	<td bgcolor="{tableheadbgcolor}" colspan="2"><normalfont color="{tableheadtextcolor}" class="thtcolor"><b>Sign Up for an Email Account</b></normalfont></td>
</tr>
<tr>
	<td bgcolor="{secondaltcolor}"><normalfont><b>Account name:</b></normalfont><br>
	<smallfont>The password for your email account will be the same as your forum account.</smallfont></td>
	<td bgcolor="{secondaltcolor}"><normalfont><input type="text" name="hive_username" class="bginput" /> <select name="hive_userdomain">$hive_domainname_options</select></normalfont></td>
</tr>
</table>
</td></tr></table>

<br>

<table cellpadding="2" cellspacing="0" border="0" width="{contenttablewidth}" {tableinvisibleextra} align="center">
<tr>
	<td align="center"><normalfont>
	<input type="hidden" name="url" value="$url">
	<input type="hidden" name="action" value="addmail">
	<input type="submit" class="bginput" name="Submit" value="Submit">
	<input type="reset" class="bginput" name="Reset" value="Reset">
	</normalfont></td>
</tr>
</table>

</form>

$footer

</body>
</html>',
);
$_titles = '"'.implode('","', array_keys($_templates)).'"';

// ############################################################################
// Check if the field has been created
echo 'Checking database structure ... ';
$user = $DB_site->query_first('
	SELECT *
	FROM user
	LIMIT 1
');
$create_field = !isset($user['hiveuserid']);
echo 'done!<br />';

// ############################################################################
// Create field is needed
if ($create_field) {
	echo 'Altering database structure ... ';
	$DB_site->query('
		ALTER TABLE user
		ADD hiveuserid int(10) unsigned NOT NULL default "0"
	');
	echo 'done!<br />';
}

// ############################################################################
// Check which templates already exist
echo 'Checking existing templates ... ';
$templates = $DB_site->query("
	SELECT *
	FROM template
	WHERE title IN ($_titles)
	AND templatesetid <> -1
");
$existing = array();
while ($template = $DB_site->fetch_array($templates)) {
	$existing[$template['templatesetid']]["$template[title]"] = true;
}
echo 'done!<br />';

// ############################################################################
// Get template set ID's
echo 'Fetching template sets ... ';
$templatesets = $DB_site->query('
	SELECT *
	FROM templateset
');
$_setids = array();
while ($templateset = $DB_site->fetch_array($templatesets)) {
	$_setids[] = $templateset['templatesetid'];
}
echo 'done!<br /><br />';

// ############################################################################
// Create new templates
echo '<b>Creating templates</b><br />';
foreach ($_templates as $title => $template) {
	$values = array();
	foreach ($_setids as $templatesetid) {
		if ($existing[$templatesetid]["$title"] != true) {
			$values[] = "(NULL, $templatesetid, '".addslashes($title)."', '".addslashes($template)."')";
		}
	}
	if (empty($values)) {
		continue;
	}
	echo 'Creating template <tt>'.$title.'</tt> ... ';
	$values = implode(', ', $values);
	$DB_site->query("
		INSERT INTO template
		(templateid, templatesetid, title, template)
		VALUES $values
	");
	echo 'done!<br />';
}

// ############################################################################
// All done
echo '<b>All done!</b>';
cpfooter();
?>